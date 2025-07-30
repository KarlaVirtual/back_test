<?php

/**
 * Clase para la integración con los servicios de Pariplay.
 *
 * Este archivo contiene métodos para gestionar la interacción con los servicios
 * de Pariplay, incluyendo la obtención de juegos y la realización de solicitudes
 * a la API de Pariplay.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase principal para manejar los servicios de Pariplay.
 */
class PARIPLAYSERVICES
{

    /**
     * Método utilizado para realizar solicitudes a la API.
     *
     * @var string
     */
    private $METHOD = '';

    /**
     * URL de redirección utilizada en las respuestas de la API.
     *
     * @var string
     */
    private $URLREDIRECTION = '';


    /**
     * Constructor de la clase.
     *
     * Inicializa la configuración del entorno para determinar si se está
     * ejecutando en un entorno de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene un juego desde los servicios de Pariplay.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en el sistema.
     * @param boolean $isMobile      Indica si el juego es para dispositivos móviles.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta de la API con la URL del juego o un error.
     * @throws Exception Si ocurre un error durante la ejecución.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "PARIPLAY");
            $Producto = new Producto('', $gameid, $Proveedor->getProveedorId());

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
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
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
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

            $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $Pais = new Pais($UsuarioMandante->paisId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
            }

            if ($play_for_fun == true) { //Demo
                $array = array(
                    "GameCode" => $gameid,
                    "CurrencyCode" => "$UsuarioMandante->moneda",
                    "LanguageCode" => $lang,
                    "HomeUrl" => $this->URLREDIRECTION,
                    "PlayerId" => $UsuarioMandante->getUsumandanteId(),
                    "PlayerIP" => $this->get_client_ip()
                );
                $this->METHOD = "LaunchDemoGame";
            } elseif ($play_for_fun == false) {
                $array = array(
                    "GameCode" => $gameid, //OK
                    "CurrencyCode" => "$UsuarioMandante->moneda", //OK
                    "LanguageCode" => $lang, //OK
                    "PlayerId" => $UsuarioMandante->getUsumandanteId(), //OK
                    "PlayerIP" => explode(',', $this->get_client_ip())[0], //OK
                    "CountryCode" => "$Pais->iso", //OK
                    "HomeUrl" => $this->URLREDIRECTION //OK
                );
                $this->METHOD = "LaunchGame";
            }

            $array['RealityCheckInterval'] = $SubproveedorMandantePais->getDetalle();

            $response = $this->Request($array, $Credentials->URL, $Credentials->USERNAME_API, $Credentials->PASSWORD_API);

            if ($_ENV['debug']) {
                print_r($response);
            }

            if ($response->Token !== "" && $response->Token !== null) {
                $UsuarioToken->setToken($response->Token);
                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                $UsuarioToken->setProductoId($Producto->productoId);
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            }

            $array = array(
                "error" => false,
                "response" => $response->Url,
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
            //print_r($e);
        }
    }

    /**
     * Realiza una solicitud a la API de Pariplay.
     *
     * @param array  $array_tmp    Datos de la solicitud.
     * @param string $URL          URL de la API.
     * @param string $USERNAME_API Nombre de usuario para la autenticación.
     * @param string $PASSWORD_API Contraseña para la autenticación.
     *
     * @return object Respuesta de la API.
     */
    public function Request($array_tmp, $URL, $USERNAME_API, $PASSWORD_API)
    {
        $data = array(
            "Account" => array(
                "UserName" => $USERNAME_API,
                "Password" => $PASSWORD_API
            )
        );

        $data = array_merge($data, $array_tmp);
        $data = json_encode($data);

        $headers = array(
            "Content-type: application/json"
        );

        $ch = curl_init($URL . $this->METHOD);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //$rs = curl_exec($ch);
        $result = (curl_exec($ch));

        if ($_ENV['debug']) {
            print_r($data);
            print_r($result);
        }

        $result = json_decode($result);
        return ($result);
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * Este método verifica varias cabeceras HTTP para determinar la IP
     * del cliente que realiza la solicitud.
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
