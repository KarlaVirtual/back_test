<?php

/**
 * Clase ONETOUCHSERVICES
 *
 * Esta clase proporciona servicios relacionados con la integración de juegos de casino
 * para el proveedor ONETOUCH. Incluye métodos para obtener juegos, gestionar tokens de usuario,
 * y realizar solicitudes HTTP a servicios externos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase que proporciona servicios para la integración con el proveedor de juegos ONETOUCH.
 *
 * Esta clase incluye métodos para gestionar tokens de usuario, obtener URLs de juegos,
 * realizar solicitudes HTTP y manejar configuraciones específicas del proveedor.
 */
class ONETOUCHSERVICES
{
    /**
     * URL de redirección para el proveedor ONETOUCH.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * Firma de autenticación generada para las solicitudes.
     *
     * @var string
     */
    private $XAuth = "";

    /**
     * URL para realizar depósitos en el proveedor ONETOUCH.
     *
     * @var string
     */
    private $URLDEPOSIT = '';

    /**
     * Constructor de la clase ONETOUCHSERVICES.
     *
     * Inicializa el entorno de configuración para determinar si se está en un entorno
     * de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la URL de un juego específico.
     *
     * Este método genera un token de usuario, obtiene las credenciales necesarias
     * y realiza una solicitud HTTP para obtener la URL del juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param integer $productoId    ID del producto.
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            $Proveedor = new Proveedor("", "ONETOUCH");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId, "", "", $Producto->productoId);
                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken($token);
                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
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
                    $UsuarioToken->setProductoId($productoId);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Mandante = new Mandante($UsuarioMandante->mandante);
            $Pais = new Pais($UsuarioMandante->paisId);

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
                $this->URLDEPOSIT = $Mandante->baseUrl . "casino";
            }

            if ($isMobile == true) {
                $platform = "GPL_MOBILE";
            } elseif ($isMobile == false) {
                $platform = "GPL_DESKTOP";
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

            $credentials = json_decode($SubproveedorMandantePais->getCredentials());
            $SUBPARTNER_ID = $credentials->SUBPARTNER_ID;
            $OPERATOR_ID = $credentials->OPERATOR_ID;
            $PRIVATE_KEY = $credentials->PRIVATE_KEY;
            $URL = $credentials->URL;

            $IP = $this->get_client_ip();

            $IP = explode(",", $IP);
            $array = array(
                "user" => $UsuarioMandante->usumandanteId,
                "token" => $UsuarioToken->getToken(),
                "sub_partner_id" => $SUBPARTNER_ID,
                "platform" => $platform,
                "operator_id" => $OPERATOR_ID,
                "lobbyURL" => $this->URLREDIRECTION,
                "lang" => $lang,
                "ip" => $IP[0],
                "game_id" => $gameid,
                "display_unit" => $UsuarioMandante->moneda,
                "deposit_url" => $this->URLDEPOSIT,
                "currency" => $UsuarioMandante->moneda,
                "country" => $Pais->iso
            );

            $data = json_encode($array);

            $binary_signature = "";

            $algo = "RSA-SHA256";
            $PRIVATE_KEY = "-----BEGIN PRIVATE KEY-----\n$PRIVATE_KEY\n-----END PRIVATE KEY-----";

            openssl_sign($data, $binary_signature, $PRIVATE_KEY, $algo);

            $this->XAuth = base64_encode($binary_signature);

            $response = $this->Request($data, $URL);

            if ($_ENV['debug']) {
                print_r($response);
            }

            $response = (json_decode($response));

            $array = array(
                "error" => false,
                "response" => $response->url
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }


    /**
     * Obtiene la dirección IP del cliente.
     *
     * Este método detecta y devuelve la dirección IP del cliente desde las variables
     * de entorno disponibles.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
     */
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

    /**
     * Realiza una solicitud HTTP POST al servicio externo para obtener la URL del juego.
     *
     * Este método utiliza la clase `CurlWrapper` para configurar y ejecutar una solicitud HTTP
     * con los datos proporcionados. Incluye la firma de autenticación en los encabezados.
     *
     * @param string $data Datos en formato JSON que se enviarán en el cuerpo de la solicitud.
     * @param string $URL  URL base del servicio externo.
     *
     * @return string Respuesta del servicio externo.
     */
    public function Request($data, $URL)
    {
        $curl = new CurlWrapper($URL . 'operator/generic/v2/game/url');

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL . 'operator/generic/v2/game/url',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'x-signature: ' . $this->XAuth,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();
        return $response;
    }
}
