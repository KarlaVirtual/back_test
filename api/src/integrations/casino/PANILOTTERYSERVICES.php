<?php

/**
 * Este archivo contiene la clase `PANILOTTERYSERVICES`, que proporciona servicios de integración
 * con el proveedor PANILOTTERY. Incluye métodos para gestionar juegos, realizar solicitudes
 * a la API y generar claves únicas, entre otros.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;

/**
 * Clase `PANILOTTERYSERVICES`.
 *
 * Proporciona servicios de integración con el proveedor PANILOTTERY, incluyendo
 * métodos para gestionar juegos, realizar solicitudes a la API y generar claves únicas.
 */
class PANILOTTERYSERVICES
{
    /**
     * URL del entorno de desarrollo.
     *
     * @var string
     */
    private $URL_DEV = "https://uat-honduplay-externo.codesa.com.co:7743/home/";

    /**
     * Método del entorno de desarrollo.
     *
     * @var string
     */
    private $METHOD_DEV = "";

    /**
     * Nombre de usuario para la API en desarrollo.
     *
     * @var string
     */
    private $USERNAME_API_DEV = "";

    /**
     * Contraseña para la API en desarrollo.
     *
     * @var string
     */
    private $PASSWORD_API_DEV = "";

    /**
     * URL de redirección en desarrollo.
     *
     * @var string
     */
    private $URLREDIRECTION_DEV = '';

    /**
     * URL del entorno de producción.
     *
     * @var string
     */
    private $URL_PROD = "https://ventas.paniplayloterias.com:23543/home/";

    /**
     * Método del entorno de producción.
     *
     * @var string
     */
    private $METHOD_PROD = "";

    /**
     * Nombre de usuario para la API en producción.
     *
     * @var string
     */
    private $USERNAME_API_PROD = "";

    /**
     * Contraseña para la API en producción.
     *
     * @var string
     */
    private $PASSWORD_API_PROD = '';

    /**
     * URL de redirección en producción.
     *
     * @var string
     */
    private $URLREDIRECTION_PROD = '';

    /**
     * URL activa según el entorno.
     *
     * @var string
     */
    private $URL = '';

    /**
     * Método activo según el entorno.
     *
     * @var string
     */
    private $METHOD = '';

    /**
     * Nombre de usuario activo según el entorno.
     *
     * @var string
     */
    private $USERNAME_API = '';

    /**
     * Contraseña activa según el entorno.
     *
     * @var string
     */
    private $PASSWORD_API = '';

    /**
     * URL de redirección activa según el entorno.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Indica si el entorno es de desarrollo.
     *
     * @var boolean
     */
    private $is_dev = false;

    /**
     * Constructor de la clase. Configura las variables según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URL_DEV;
            $this->METHOD = $this->METHOD_DEV;
            $this->USERNAME_API = $this->USERNAME_API_DEV;
            $this->PASSWORD_API = $this->PASSWORD_API_DEV;
            $this->URLREDIRECTION = $this->URLREDIRECTION_DEV;
        } else {
            $this->URL = $this->URL_PROD;
            $this->METHOD = $this->METHOD_PROD;
            $this->USERNAME_API = $this->USERNAME_API_PROD;
            $this->PASSWORD_API = $this->PASSWORD_API_PROD;
            $this->URLREDIRECTION = $this->URLREDIRECTION_PROD;
        }
    }

    /**
     * Obtiene la URL del juego para un usuario específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si es un juego de prueba.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en el sistema externo (opcional).
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego y el proveedor.
     * @throws Exception Si ocurre un error durante la ejecución.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid = "", $isMobile = false, $usumandanteId = "")
    {
        try {
            $forFun = "";

            try {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "PANILOTTERY");

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
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

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }


                $array = array(
                    "error" => false,
                    "response" => array("URL" => $this->URL . $UsuarioToken->getToken(), "proveedor" => "PANILOTTERY")
                );

                return json_decode(json_encode($array));
            } catch (Exception $e) {
                //  print_r($e);
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una solicitud a la API con los datos proporcionados.
     *
     * @param array $array_tmp Datos adicionales para la solicitud.
     *
     * @return object Respuesta de la API.
     */
    public function Request($array_tmp)
    {
        $data = array(
            "Account" => array(
                "UserName" => "" . $this->USERNAME_API . "",
                "Password" => "" . $this->PASSWORD_API . ""

            )
        );

        $data = array_merge($data, $array_tmp);

        $data = json_encode($data);


        $ch = curl_init($this->URL . $this->METHOD);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        //$rs = curl_exec($ch);
        $result = json_decode(curl_exec($ch));
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        return ($result);
    }

    /**
     * Genera una clave única basada en el identificador del jugador.
     *
     * @param string $player Identificador del jugador.
     *
     * @return string Clave generada.
     */
    function generateKey($player)
    {
        $hash = md5($player . md5("TMP" . $player));
        $hash = substr($hash, 0, 12);
        return ($hash);
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

