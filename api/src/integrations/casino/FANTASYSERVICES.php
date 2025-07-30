<?php

/**
 * Este archivo contiene la clase `FANTASYSERVICES` que gestiona la integración con el proveedor de servicios de casino Fantasy.
 * Proporciona métodos para obtener juegos, autenticar usuarios y manejar configuraciones de entorno.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;

/**
 * Clase `FANTASYSERVICES`.
 * Gestiona la integración con el proveedor de servicios de casino Fantasy.
 * Proporciona métodos para obtener juegos, autenticar usuarios y manejar configuraciones de entorno.
 */
class FANTASYSERVICES
{
    /**
     * URL base para las solicitudes al servicio de Fantasy.
     *
     * @var string
     */
    private $URL = '';

    /**
     * URL de desarrollo para las solicitudes al servicio de Fantasy.
     *
     * @var string
     */
    private $URLDEV = 'https://fantasygoalgameapi-staging.azurewebsites.net/api/session/';

//private $URLDEV = 'https://fantasygoalgameapi-dev.azurewebsites.net/api/session';

    /**
     * URL de producción para las solicitudes al servicio de Fantasy.
     *
     * @var string
     */
    private $URLPROD = 'https://fantasygoalgameapi.azurewebsites.net/api/session/';

    /**
     * URL base para la redirección de sesión.
     *
     * @var string
     */
    private $URLSC = '';

    /**
     * URL de desarrollo para la redirección de sesión.
     *
     * @var string
     */
    private $URLSCDEV = 'https://fantasygoal-staging.azurewebsites.net/#/session/';

    /**
     * URL de producción para la redirección de sesión.
     *
     * @var string
     */
    private $URLSCPROD = 'https://fantasygoal.azurewebsites.net/#/session/';

    /**
     * Clave API utilizada para la autenticación.
     *
     * @var string
     */
    private $Key = '';

    /**
     * Clave API de desarrollo utilizada para la autenticación.
     *
     * @var string
     */
    private $KeyDEV = '6fdab705-4861-65d3-7fd9-a2078b66181a';

    /**
     * Clave API de producción utilizada para la autenticación.
     *
     * @var string
     */
    private $KeyPROD = '95621c4b-7a21-4d7b-b5c2-7ef01e087762';

    /**
     * Clave del plan utilizada para la configuración.
     *
     * @var string
     */
    private $planKey = '';

    /**
     * Clave del plan en desarrollo utilizada para la configuración.
     *
     * @var string
     */
    private $planKeyDEV = '60652f1b-a33e-4cdb-9a18-f1306f43d095';

    /**
     * Clave del plan en producción utilizada para la configuración.
     *
     * @var string
     */
    private $planKeyPROD = 'af70e51d-8897-40e3-b46f-8902347c6447';

    /**
     * URL de redirección base.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * URL de redirección en desarrollo.
     *
     * @var string
     */
    private $URLREDIRECTION_DEV = 'https://devfrontend.virtualsoft.tech/doradobet/new-casino';

    /**
     * URL de redirección en producción.
     *
     * @var string
     */
    private $URLREDIRECTION_PROD = 'https://doradobet.com/new-casino';

    /**
     * Constructor de la clase.
     * Configura las variables de entorno según el entorno actual (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->Key = $this->KeyDEV;
            $this->planKey = $this->planKeyDEV;
            $this->URL = $this->URLDEV;
            $this->URLSC = $this->URLSCDEV;
            $this->URLREDIRECTION = $this->URLREDIRECTION_DEV;
        } else {
            $this->Key = $this->KeyPROD;
            $this->planKey = $this->planKeyPROD;
            $this->URL = $this->URLPROD;
            $this->URLSC = $this->URLSCPROD;
            $this->URLREDIRECTION = $this->URLREDIRECTION_PROD;
        }
    }

    /**
     * Obtiene un juego del proveedor Fantasy.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $Producto      Producto asociado (opcional).
     * @param boolean $isMobile      Indica si el usuario está en un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $Producto = '', $isMobile, $usumandanteId = "")
    {
        $Proveedor = new Proveedor("", "FANTASY");
        $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

        $patch = "new-casino/proveedor/FANTASY";

        if ($usumandanteId == "") {
            $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
            $usumandanteId = $UsuarioTokenSite->getUsuarioId();
        }

        try {
            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => '9ce4b6a7-b43c-40b2-8f31-1380144dbbf6',
                );

                return json_decode(json_encode($array));
            } else {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                    $UsuarioToken->setToken($UsuarioToken->createToken());
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
                $Mandante = new Mandante($UsuarioMandante->mandante);
                $Pais = new Pais($UsuarioMandante->paisId);

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Balance = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . $patch;
                }

                $currency = $UsuarioMandante->moneda;
                $data = array();
                $data['userId'] = strval($UsuarioMandante->usumandanteId);
                $data['currency'] = $currency;
                $data['balance'] = $Balance;
                $data['planKey'] = $this->planKey;

                $userAuth = $this->userAuth($data, $this->URL);
                $userAuth = str_replace('"', '', $userAuth);

                if ($_ENV['debug'] == true) {
                    print_r('URL REQUEST');
                    print_r($this->URL);
                    print_r("\r\n");
                    print_r('DATA REQUEST');
                    print_r(json_encode($data));
                    print_r("\r\n");
                    print_r('LAUNCH RESPONSE');
                    print_r($userAuth);
                }

                $array = array(
                    "error" => false,
                    "response" => $this->URLSC . $userAuth . '/' . $lang
                );

                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Autentica a un usuario con los datos proporcionados.
     *
     * @param array  $data Datos del usuario para la autenticación.
     * @param string $url  URL del servicio de autenticación.
     *
     * @return string Respuesta del servicio de autenticación.
     */
    function userAuth($data, $url)
    {
        header('Access-Control-Allow-Origin: *');
        $curl = curl_init();
        curl_setopt_array($curl, array(
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
                'x-api-key: ' . $this->Key,
                'Content-Type: application/json',
                'Access-Control-Allow-Origin: *'
            ),
        ));
        try {
            $response = curl_exec($curl);
            if ($response === false) {
                throw new Exception('CURL error: ' . curl_error($curl));
            }
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($httpCode >= 400) {
                throw new Exception('HTTP error: ' . $httpCode);
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        } finally {
            curl_close($curl);
        }
        return $response;
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
