<?php

/**
 * Clase IESGAMESCASINOSERVICES
 *
 * Esta clase proporciona servicios de integración con la API de IES Games Casino.
 * Incluye métodos para autenticación, gestión de juegos, apuestas externas,
 * consultas de resultados, y más.
 *
 * @category   Integración
 * @package    API
 * @subpackage Casino
 * @version    1.0.0
 * @since      2025-04-27
 * @author     Desconocido
 */

namespace Backend\integrations\casino;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase principal para los servicios de integración con IES Games Casino.
 *
 * Esta clase contiene métodos para interactuar con la API de IES Games Casino,
 * incluyendo autenticación, gestión de juegos, apuestas externas, y más.
 */
class IESGAMESCASINOSERVICES
{
    /**
     * Tipo de operación o endpoint de la API.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Constructor de la clase.
     * Configura las URLs y credenciales según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la configuración de un juego.
     *
     * @param string  $GameCode      Código del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si es un juego de prueba.
     * @param string  $usuarioToken  Token del usuario.
     * @param integer $productoId    ID del producto.
     * @param boolean $isMobile      Indica si es un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante.
     *
     * @return object Respuesta con la configuración del juego.
     */
    public function getGame($GameCode, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($play_for_fun) {
                $array = array(
                    "GameCode" => $GameCode,
                    "CurrencyCode" => "",
                    "Platform" => 0,
                    "LanguageCode" => $lang,
                    "RoomsUrl" => "",
                    "RafflesUrl" => "",
                    "PlayerIP" => "",
                    "PlayerId" => "",
                    "userName" => "",
                    "password" => "",
                    "TotalBalance" => "",
                    "CountryCode" => ""
                );

                return json_decode(json_encode($array));
            } else {

                $Proveedor = new Proveedor("", "IESGAMES");
                $Producto = new Producto('', $GameCode, $Proveedor->proveedorId);

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                    $UsuarioToken->setToken($UsuarioToken->createToken());
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
                        $UsuarioToken->setProductoId($Producto->productoId);

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                $Mandante = new Mandante($UsuarioMandante->mandante);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante(), "", "", $Mandante->mandante);

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $userName = $Credentials->USERNAME;
                $password = $Credentials->PASSWORD;
                $url = $Credentials->URL;

                $Pais = new Pais($UsuarioMandante->paisId);

                $this->tipo = "/server/launchgame";

                $UserIp = explode(",", $Usuario->dirIp);
                $UserIp = $UserIp[0];

                switch (strtoupper($Pais->iso)) {
                    case "PE":
                        $Pais->iso = "PER";
                        break;
                    case "EC":
                        $Pais->iso = "ECU";
                        break;
                    case "CL":
                        $Pais->iso = "CHI";
                        break;
                    case "NI":
                        $Pais->iso = "NIC";
                        break;
                    case "CR":
                        $Pais->iso = "CRC";
                        break;
                }

                $mobile = 0;
                if ($isMobile) {
                    $mobile = 1;
                }

                $array = array(
                    "platform" => $mobile,
                    "gameCode" => $GameCode,
                    "playerIp" => $UserIp,
                    "playerId" => $UsuarioMandante->usumandanteId,
                    "currencyCode" => $UsuarioMandante->moneda,
                    "languageCode" => "ES",
                    "countryCode" => $Pais->iso,
                    "totalBalance" => floatval($Usuario->getBalance()),
                    "account" => array(
                        "userName" => $userName,
                        "password" => $password
                    )
                );

                $response = $this->Request($array, $url);

                if ($response->url != "") {
                    $array = array(
                        "error" => false,
                        "response" => $response->url
                    );
                    return json_decode(json_encode($array));
                } else {
                    throw new Exception("Error General", "1");
                }
            }
        } catch (Exception $e) {
            print_r($response);
        }
    }

    /**
     * Realiza una solicitud a la API.
     *
     * @param array $data Datos de la solicitud.
     *
     * @return object Respuesta de la API.
     */
    public function Request($data, $url)
    {
        $data =  json_encode($data);
        $curl = new CurlWrapper($url . $this->tipo);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $this->tipo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();
        $response = json_decode($response);
        return $response;
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * Este método verifica varias cabeceras del servidor para determinar
     * la dirección IP del cliente. Si no se encuentra ninguna dirección IP válida,
     * devuelve 'UNKNOWN'.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
     */
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}
