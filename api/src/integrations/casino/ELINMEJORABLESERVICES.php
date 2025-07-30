<?php

/**
 * Clase para gestionar la integración con los servicios de casino de ELINMEJORABLE.
 *
 * Este archivo contiene métodos para realizar solicitudes a la API de ELINMEJORABLE,
 * gestionar usuarios, mandantes, y realizar operaciones relacionadas con juegos.
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
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase principal para la integración con los servicios de casino de ELINMEJORABLE.
 *
 * Proporciona métodos para interactuar con la API de ELINMEJORABLE, gestionar usuarios,
 * mandantes y realizar operaciones relacionadas con juegos.
 */
class ELINMEJORABLESERVICES
{

    /**
     * Método actual de la API.
     *
     * @var string
     */
    private $method = "";

    /**
     * URL de la API en uso (según el entorno).
     *
     * @var string
     */
    private $URL_API = "";

    /**
     * Constructor de la clase.
     *
     * Inicializa las variables de configuración dependiendo del entorno (desarrollo o producción).
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
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID alternativo del juego (opcional).
     * @param string  $ismobile      Indica si es un dispositivo móvil (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid = "", $ismobile = '', $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "ELINMEJORABLE");
            $Producto = new Producto('', $gameid, $Proveedor->proveedorId);

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

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
            $Mandante = new Mandante($UsuarioMandante->getMandante());
            $Registro = new Registro($UsuarioMandante->getUsuarioMandante());

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
            $this->URL_API = $Credentials->URL;
            $this->method = $Credentials->METHOD;

            $array = array(
                "mobile" => "000",
                "email" => $Registro->email,
                "tipo_cedula" => $Registro->tipoDoc,
                "cedula" => $Registro->cedula,
                "alias" => $Registro->nombre1,
                "usuario" => $UsuarioMandante->usumandanteId,
                "empresa" => $Mandante->mandante,
                "moneda" => $UsuarioMandante->moneda,
                "SubAgenteNombre " => $Credentials->AGENTE_NOMBRE,
                "api_key" => $Credentials->API_KEY
            );

            if ($gameid == "ELINMEJORABLENACIONAL") {
                $this->URL_API = $Credentials->URL2;
            }

            $Response = $this->connection($array);
            $Response = json_decode($Response);
            
            $array = array(
                "error" => false,
                "response" => $Response->data->url
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una conexión HTTP POST con la API.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta de la API.
     */
    public function connection($data)
    {
        //Inicializar la clase CurlWrapper
        $curl = new CurlWrapper($this->URL_API . $this->method);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $this->URL_API . $this->method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        // Ejecutar la solicitud
        $response = $curl->execute();
        return $response;
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
     */
    public function get_client_ip()
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
