<?php

/**
 * Clase UNIVERSALSOFTSERVICES para manejar integraciones con servicios de casino.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Constructor de la clase UNIVERSALSOFTSERVICES.
 * Configura las URLs y métodos según el entorno (desarrollo o producción).
 */
class UNIVERSALSOFTSERVICES
{
    /**
     * URL base utilizada en las solicitudes.
     *
     * @var string
     */
    private $URL = '';

    /**
     * URL base para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'http://api.universalrace.net/';

    /**
     * URL base para el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'http://api.universalrace.net/';

    /**
     * Constructor de la clase UNIVERSALSOFTSERVICES.
     *
     * Configura las URLs, métodos y redirecciones según el entorno actual
     * (desarrollo o producción) utilizando la configuración del entorno.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
        } else {
            $this->URL = $this->URLPROD;
        }
    }

    /**
     * Obtiene un juego según los parámetros proporcionados.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si es un juego de prueba.
     * @param string  $usuarioToken  Token del usuario.
     * @param integer $productoId    ID del producto.
     * @param boolean $isMobile      Indica si es un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta del servicio en formato JSON.
     * @throws Exception Si ocurre un error durante la ejecución.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($play_for_fun) {
                $array = array(
                    "accion" => 'API_Login',
                    "user_n" => "Usuario" . $usumandanteId,
                    "id" => "Usuario" . $usumandanteId,
                    "token" => "",
                    "saldo" => ""
                );

                $response = $this->Request2($array);
                return json_decode(json_encode($response));
            } else {
                $Proveedor = new Proveedor("", "UNIVERSALS");

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                    $UsuarioToken->setEstado("I");
                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->updateState($UsuarioToken);
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
                        $UsuarioToken->setEstado("A");
                        $UsuarioToken->setSaldo(0);
                        $UsuarioToken->setProductoId(0);

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                $Mandante = new Mandante($UsuarioMandante->mandante);

                $UsuarioToken->setToken($UsuarioToken->createToken());
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();

                $array = "accion=API_Login&user_n=Usuario" . $UsuarioMandante->usumandanteId . "&id=Usuariocasino" . $UsuarioMandante->usumandanteId;
                $response = $this->Request($array);

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante, "", "", $UsuarioMandante->mandante);

                $UsuarioToken->setToken($response[0]->token);
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();

                $Subproveedor = new Subproveedor("", "UNIVERSALS");
                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                if ($response[0]->resp == "OK") {
                    $URL = $Credentials->URL . "new/cliente_api/a_home.php?tk=" . $UsuarioToken->getToken() . "&ba=" . floatval($Usuario->getBalance()) . "&theme=" . $Credentials->THEME;
                }

                $array = array(
                    "error" => false,
                    "response" => $URL
                );
                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Crea un usuario en el sistema a través de la API.
     *
     * @param UsuarioMandante $UsuarioMandante Objeto con los datos del usuario mandante.
     *
     * @return object Respuesta del servicio en formato JSON.
     */
    public function API_crear_usuario($UsuarioMandante)
    {
        $Subproveedor = new Subproveedor("", "UNIVERSALS");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $id_grupo = $Credentials->GRUPO_ID;

        $Pais = new Pais($UsuarioMandante->paisId);

        $moneda = '';
        switch ($UsuarioMandante->moneda) {
            case "USD":
                $moneda = 3;
                break;
            case "ARS":
                $moneda = 18;
                break;
            case "DOP":
                break;
            case "PEN":
                $moneda = 9;
                break;
            case "MXN":
                $moneda = 13;
                break;
            case "GTQ":
                $moneda = 22;
                break;
            case "GYD":
                break;
            case "JMD":
                break;
            case "BRL":
                $moneda = 6;
                break;
            case "CLP":
                $moneda = 7;
                break;
            case "CRC":
                $moneda = 21;
                break;
            case "HNL":
                break;
            case "VES":
                break;
        }

        $array = "accion=API_crear_usuario_by_id&user_n=Usuario" . $UsuarioMandante->usumandanteId . "&nombre=" . $UsuarioMandante->nombres . "&pais=" . $Pais->prefijoCelular . "&moneda=" . $moneda . "&ID=Usuariocasino" . $UsuarioMandante->usumandanteId . "&id_group=" . $id_grupo;

        $response = $this->Request($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_decode(json_encode($array));
    }

    /**
     * Consulta el estado de un ticket en la API.
     *
     * @param string $id ID de la operación.
     *
     * @return object Respuesta del servicio en formato JSON.
     */
    public function status_tck_API($id)
    {
        $array = "accion=status_tck_API&id_operacion=" . $id;
        $response = $this->Request($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_decode(json_encode($array));
    }

    /**
     * Confirma el estado de una operación en la API.
     *
     * @param string $data Datos de la operación en formato JSON.
     *
     * @return object Respuesta del servicio en formato JSON.
     */
    public function confirma_status_API($data)
    {
        $data = json_decode($data);
        $accion = 'confirma_status_API';
        $ids = $data->id_operacion;
        $data = "accion=" . $accion . "&id_operacion=" . $ids;

        $response = $this->Request($data);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_decode(json_encode($array));
    }

    /**
     * Realiza una solicitud HTTP POST a la API.
     *
     * @param string $data Datos a enviar en la solicitud.
     *
     * @return object Respuesta de la API en formato JSON.
     */
    public function Request($data)
    {
        //InicializarlaclaseCurlWrapper
        $curl = new CurlWrapper($this->URL . "bet_api.php");

        $curl->setOptionsArray(array(
            CURLOPT_URL => $this->URL . "bet_api.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        // Ejecutar la solicitud
        $response = $curl->execute();
        return json_decode($response);
    }

    /**
     * Realiza una solicitud HTTP POST a una URL alternativa de la API.
     *
     * @param string $data Datos a enviar en la solicitud.
     *
     * @return object Respuesta de la API en formato JSON.
     */
    public function Request2($data)
    {
        //InicializarlaclaseCurlWrapper
        $curl = new CurlWrapper($this->URL . "betapi/bet_api.php");

        $curl->setOptionsArray(array(
            CURLOPT_URL => $this->URL . "betapi/bet_api.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        // Ejecutar la solicitud
        $response = $curl->execute();
        return json_decode($response);
    }

    /**
     * Obtiene la dirección IP del cliente.
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
}
