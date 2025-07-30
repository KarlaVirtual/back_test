<?php

/**
 * Clase `GENERALSERVICES` para manejar servicios generales relacionados con la integración de casinos.
 *
 * Este archivo contiene la implementación de una clase que gestiona la configuración
 * de entornos (desarrollo y producción), realiza solicitudes HTTP, genera claves,
 * y obtiene información del cliente, entre otras funcionalidades.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
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
 * Clase principal que gestiona los servicios generales para la integración de casinos.
 */
class GENERALSERVICES
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
     * Nombre de usuario para la API en el entorno de desarrollo.
     *
     * @var string
     */
    private $USERNAME_API_DEV = "";

    /**
     * Contraseña para la API en el entorno de desarrollo.
     *
     * @var string
     */
    private $PASSWORD_API_DEV = "";

    /**
     * URL de redirección en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLREDIRECTION_DEV = "";

    /**
     * URL del entorno de producción.
     *
     * @var string
     */
    private $URL_PROD = "";

    /**
     * Método del entorno de producción.
     *
     * @var string
     */
    private $METHOD_PROD = "";

    /**
     * Nombre de usuario para la API en el entorno de producción.
     *
     * @var string
     */
    private $USERNAME_API_PROD = "";

    /**
     * Contraseña para la API en el entorno de producción.
     *
     * @var string
     */
    private $PASSWORD_API_PROD = "";

    /**
     * URL de redirección en el entorno de producción.
     *
     * @var string
     */
    private $URLREDIRECTION_PROD = "";

    /**
     * URL configurada según el entorno actual.
     *
     * @var string
     */
    private $URL = "";

    /**
     * Método configurado según el entorno actual.
     *
     * @var string
     */
    private $METHOD = "";

    /**
     * Nombre de usuario configurado según el entorno actual.
     *
     * @var string
     */
    private $USERNAME_API = "";

    /**
     * Contraseña configurada según el entorno actual.
     *
     * @var string
     */
    private $PASSWORD_API = "";

    /**
     * URL de redirección configurada según el entorno actual.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * Indica si el entorno actual es de desarrollo.
     *
     * @var boolean
     */
    private $is_dev = false;

    /**
     * Constructor de la clase.
     *
     * Inicializa las variables de configuración dependiendo del entorno
     * (desarrollo o producción) utilizando la clase `ConfigurationEnvironment`.
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
     * Obtiene un juego basado en el ID del juego y otros parámetros.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es para diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      Opcional ID del juego en el sistema externo.
     * @param string  $usumandanteId Opcional ID del usuario mandante.
     *
     * @return object|null Respuesta con la URL del juego o `null` en caso de error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid = "", $usumandanteId = "")
    {
        try {
            $forFun = "";

            try {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "GENERAL");

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
                    "response" => $this->URL . $UsuarioToken->getToken()
                );

                return json_decode(json_encode($array));
            } catch (Exception $e) {
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una solicitud HTTP POST al servicio configurado.
     *
     * @param array $array_tmp Datos adicionales para la solicitud.
     *
     * @return object|null Respuesta del servicio en formato JSON decodificado.
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

