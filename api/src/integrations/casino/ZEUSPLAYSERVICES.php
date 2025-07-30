<?php

/**
 * Clase ZEUSPLAYSERVICES
 *
 * Esta clase proporciona servicios para la integración con ZEUSPLAY, incluyendo la gestión de sesiones de juego
 * y la generación de firmas para la comunicación con la API de ZEUSPLAY.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */


namespace Backend\integrations\casino;

use \CurlWrapper;
use Exception;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase ZEUSPLAYSERVICES
 *
 * Proporciona métodos para la integración con ZEUSPLAY, incluyendo la gestión de sesiones de juego
 * y la comunicación con la API de ZEUSPLAY.
 */
class ZEUSPLAYSERVICES
{
    /**
     * Constructor de la clase ZEUSPLAYSERVICES.
     *
     * Inicializa el entorno de configuración para determinar si se está en un entorno de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la URL de un juego y gestiona la sesión del usuario.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión (true) o real (false).
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $LaunchId      ID de lanzamiento del juego.
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object|null Respuesta con la URL del juego o null en caso de error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $LaunchId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "ZEUSPLAY");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken($token);
                $UsuarioToken->setProductoId($Producto->productoId);
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($usumandanteId);
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
                    $UsuarioToken->setSaldo(0);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            try {
                $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());
            } catch (Exception $e) {
            }

            if ($play_for_fun == true) {
                $playMode = "fun";
            } else {
                if ($play_for_fun == false) {
                    $playMode = "real";
                }
            }

            $body = array(
                "partnerPlayerId" => $UsuarioMandante->usumandanteId,
                "gameCode" => $Producto->externoId,
                "currencyCode" => $UsuarioMandante->moneda,
                "lang" => $lang,
                "playMode" => $playMode
            );

            $body = json_encode($body);

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $PARTNER_NAME = $credentials->PARTNER_NAME;
            $URL_LAUNCH = $credentials->URL_LAUNCH;
            $PARTNER_NAME = $credentials->PARTNER_NAME;
            $PARTNER_SECRET_KEY = $credentials->PARTNER_SECRET_KEY;

            $Api_ZP_Signature = $this->singnature($body, $PARTNER_SECRET_KEY);

            $Result = $this->GameSession($Api_ZP_Signature, $body, $URL_LAUNCH, $PARTNER_NAME);
            $Result = json_decode($Result);

            if ($Result !== "" && $Result !== null) {
                $token = str_replace("-", "", $Result->sessionId);

                $UsuarioToken->setToken($Result->sessionId);
                $UsuarioToken->setEstado("A");
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();

                $array = array(
                    "error" => false,
                    "response" => $Result->gameUrl
                );
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Genera una firma para la comunicación con la API de ZEUSPLAY.
     *
     * @param string $body               Cuerpo de la solicitud en formato JSON.
     * @param string $PARTNER_SECRET_KEY Clave secreta del socio.
     *
     * @return string Firma generada en base64.
     */
    public function singnature($body, $PARTNER_SECRET_KEY)
    {
        $Api_ZP_Signature = base64_encode(hash_hmac('sha256', $body, $PARTNER_SECRET_KEY, true));
        return $Api_ZP_Signature;
    }

    /**
     * Crea una sesión de juego en la API de ZEUSPLAY.
     *
     * @param string $Api_ZP_Signature Firma generada para la solicitud.
     * @param string $body             Cuerpo de la solicitud en formato JSON.
     * @param string $URL_LAUNCH       URL base para el lanzamiento del juego.
     * @param string $PARTNER_NAME     Nombre del socio.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    public function GameSession($Api_ZP_Signature, $body, $URL_LAUNCH, $PARTNER_NAME)
    {
        $header = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Api-ZP-Signature: ' . $Api_ZP_Signature
        );

        $URL = $URL_LAUNCH . $PARTNER_NAME . '/game-session';

        $curl = new CurlWrapper($URL);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => $header,
        ));

        $response=$curl->execute();

        return $response;
    }
}
