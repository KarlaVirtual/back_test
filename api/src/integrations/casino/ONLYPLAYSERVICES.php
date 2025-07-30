<?php

/**
 * Clase ONLYPLAYSERVICES
 *
 * Esta clase proporciona servicios de integración con el proveedor ONLYPLAY, incluyendo
 * la gestión de juegos, generación de tokens, y asignación de giros gratis.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @version    1.0.0
 * @since      2025-04-27
 * @author     Desconocido
 */

namespace Backend\integrations\casino;

use DateTime;
use Exception;
use Department;
use \CurlWrapper;
use DateTimeZone;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\BonoDetalle;
use Backend\dto\UsuarioBono;
use Backend\dto\Departamento;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase que proporciona servicios de integración con el proveedor ONLYPLAY.
 * Incluye funcionalidades como la gestión de juegos, generación de tokens,
 * y asignación de giros gratis.
 */
class ONLYPLAYSERVICES
{
    /**
     * URL de callback utilizada en el entorno actual.
     *
     * @var string
     */
    private $CALLBACKURL = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $CALLBACKURLDEV = "https://apidevintegrations.virtualsoft.tech/integrations/casino/onlyplay/api/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $CALLBACKURLPROD = "https://integrations.virtualsoft.tech/casino/onlyplay/api/";

    /**
     * Constructor de la clase.
     * Configura la URL de callback según el entorno actual.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->CALLBACKURL = $this->CALLBACKURLDEV;
        } else {
            $this->CALLBACKURL = $this->CALLBACKURLPROD;
        }
    }

    /**
     * Obtiene la URL de lanzamiento de un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID del minijuego.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     * @param boolean $isMobile      Indica si el juego es para móvil (opcional).
     * @param boolean $minigame      Indica si es un minijuego (opcional).
     *
     * @return object Respuesta con la URL del juego.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $UsuarioToken, $migameid, $usumandanteId = "", $isMobile = false, $minigame = false)
    {
        $Proveedor = new Proveedor("", "ONLYPLAY");
        $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($UsuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

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
                $UsuarioToken->setToken($UsuarioToken->createToken());
                $UsuarioToken->setSaldo(0);
                $UsuarioToken->setEstado('A');
                $UsuarioToken->setProductoId($Producto->productoId);

                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            } else {
                throw $e;
            }
        }

        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $Balance = intval($Usuario->getBalance() * 100);

        $dataLaunch = [
            "game_bundle" => $gameid,
            "partner_id" => intval($credentials->partnerId),
            "callback_url" => $this->CALLBACKURL,
            "balance" => $Balance,
            "currency" => $UsuarioMandante->moneda,
            "decimals" => 2,
            "lang" => strtoupper($lang),
            "nickname" => $UsuarioMandante->nombres,
            "user_id" => $UsuarioMandante->usumandanteId,
            "casino_id" => $UsuarioMandante->mandante,
        ];
        ksort($dataLaunch);

        $signatureBase = '';
        foreach ($dataLaunch as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_SLASHES);
            }
            $signatureBase .= $key . $value;
        }

        $signatureBase .= $credentials->secretKey;
        $sign = sha1($signatureBase);

        $dataLaunchTrue = array_merge($dataLaunch, [
            "sign" => $sign
        ]);
        $LaunchResponse = $this->LaunchUrl($credentials->launchUrl, $dataLaunchTrue);
        $decodedResponse = json_decode($LaunchResponse);

        $UsuarioToken = new UsuarioToken($UsuarioToken->getToken(), '', '', '', '', $Producto->productoId);
        $token = $decodedResponse->session_id;
        $UsuarioToken->setToken($token);
        $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
        $UsuarioToken->setProductoId($Producto->productoId);
        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
        $UsuarioTokenMySqlDAO->update($UsuarioToken);
        $UsuarioTokenMySqlDAO->getTransaction()->commit();

        $array = array(
            "error" => false,
            "response" => $decodedResponse->url
        );

        return json_decode(json_encode($array));
    }

    /**
     * Realiza una solicitud HTTP POST para lanzar un juego.
     *
     * @param string $url  URL del servicio de lanzamiento.
     * @param array  $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta del servicio.
     */
    public function LaunchUrl($url, $data)
    {
        $curl = new CurlWrapper($url);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        //Ejecutarlasolicitud
        $response = $curl->execute();

        return $response;
    }

    /**
     * Asigna giros gratis a un usuario.
     *
     * @param string  $bonoElegido ID del bono elegido.
     * @param integer $roundsFree  Cantidad de giros gratis.
     * @param float   $roundsValue Valor de cada giro.
     * @param string  $EndDate     Fecha de expiración de los giros.
     * @param string  $user        ID del usuario.
     * @param array   $games       Lista de juegos aplicables.
     *
     * @return array Respuesta con el resultado de la operación.
     */
    public function AddFreespins($bonoElegido, $roundsFree, $roundsValue, $EndDate, $user, $games)
    {
        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Proveedor = new Proveedor("", "ONLYPLAY");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URLBONUS = $credentials->bonusUrl;

        $dataFreeSpin = ([
            'bet_amount' => intval($roundsValue),
            'callback_url' => $this->CALLBACKURL,
            'casino_id' => (string)$UsuarioMandante->mandante,
            'currency' => (string)$UsuarioMandante->moneda,
            'decimals' => 2,
            'freebet_id' => $bonoElegido,
            'expire_date' => $EndDate,
            'user_id' => $UsuarioMandante->usumandanteId,
            "games" => [$games[0]],
            'partner_id' => intval($credentials->partnerId),
            'quantity' => $roundsFree,
        ]);

        ksort($dataFreeSpin);

        $signatureBase = '';
        foreach ($dataFreeSpin as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_SLASHES);
            }
            $signatureBase .= $key . $value;
        }

        $signatureBase .= $credentials->secretKey;
        $sign = sha1($signatureBase);

        $dataFreeSpinTrue = array_merge($dataFreeSpin, [
            "sign" => $sign
        ]);

        $response = $this->SendFreespins($URLBONUS, $dataFreeSpinTrue);
        syslog(LOG_WARNING, "ONLYPLAY BONO DATA: " . json_encode($dataFreeSpinTrue) . " | RESPONSE: " . $response);

        $response = json_decode($response);
        $responseCode = http_response_code();

        if ($responseCode != '200') {
            $return = array(
                "code" => 1,
                "response_code" => 0,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response,
                "response_message" => 'OK'
            );
        }
        return $return;
    }

    /**
     * Realiza una solicitud HTTP POST para asignar giros gratis.
     *
     * @param string $url          URL del servicio de giros gratis.
     * @param array  $dataFreeSpin Datos a enviar en la solicitud.
     *
     * @return string Respuesta del servicio.
     */
    public function SendFreespins($url, $dataFreeSpin)
    {
        $curl = new CurlWrapper($url);

        $curl->setOptionsArray([
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dataFreeSpin),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
        ]);

        $response = $curl->execute();

        return $response;
    }
}
