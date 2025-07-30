<?php

/**
 * Clase BETIXONSERVICES
 *
 * Esta clase proporciona servicios relacionados con la integración de juegos de casino
 * para el proveedor BETIXON. Incluye métodos para obtener juegos, realizar solicitudes
 * a la API del proveedor y manejar información del usuario y del producto.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
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
 * Clase que proporciona servicios para la integración con el proveedor BETIXON.
 *
 * Esta clase incluye métodos para manejar juegos, realizar solicitudes a la API
 * y gestionar información relacionada con usuarios y productos.
 */
class BETIXONSERVICES
{
    /**
     * URL de redirección para el juego.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Metodo de la API que se utilizará en las solicitudes.
     *
     * @var string
     */
    private $METHOD;

    /**
     * Constructor de la clase.
     *
     * Inicializa el entorno de configuración para determinar si se está
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
     * Obtiene un juego desde el proveedor BETIXON.
     *
     * Este metodo maneja la lógica para obtener un juego, incluyendo la creación
     * de tokens de usuario, la configuración de credenciales y la realización
     * de solicitudes a la API del proveedor.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID alternativo del juego (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta de la API con la URL del juego.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid = "", $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "BTX");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

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
                    $UsuarioToken->setProductoId($Producto->productoId);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
            $Pais = new Pais($UsuarioMandante->paisId);
            $Mandante = new Mandante($UsuarioMandante->getMandante());

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
            }

            $ip = explode(',', $this->get_client_ip());
            $ip = $ip[0];
            $array = array(
                "CountryCode" => "$Pais->iso",
                "CurrencyCode" => "$UsuarioMandante->moneda",
                "Platform" => "0",
                "LanguageCode" => "EN",
                "HomeUrl" => $this->URLREDIRECTION,
                "GameCode" => $gameid,
                "PlayerIP" => $ip,
                "PlayerId" => $UsuarioMandante->getUsumandanteId(),
                "PlayerName" => $UsuarioMandante->getNombres(),
            );
            $this->METHOD = "/Server/LaunchGame";

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $USERNAME_API = $credentials->USERNAME_API;
            $PASSWORD_API = $credentials->PASSWORD_API;
            $URL_API = $credentials->URL_API;

            $response = $this->Request($array, $URL_API, $USERNAME_API, $PASSWORD_API);

            if ($_ENV['debug']) {
                print_r(' Data ');
                print_r(json_encode($array));
                print_r(' Response');
                print_r($response);
            }

            $UsuarioToken->setToken($response->Token);
            $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
            $UsuarioToken->setProductoId($Producto->productoId);
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $array = array(
                "error" => false,
                "response" => $response->Url,
            );
            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una solicitud a la API del proveedor.
     *
     * Este metodo utiliza CurlWrapper para enviar datos a la API del proveedor
     * y obtener una respuesta.
     *
     * @param array  $array_tmp    Datos de la solicitud.
     * @param string $URL_API      URL de la API.
     * @param string $USERNAME_API Nombre de usuario para la autenticación.
     * @param string $PASSWORD_API Contraseña para la autenticación.
     *
     * @return object Respuesta de la API.
     */
    public function Request($array_tmp, $URL_API, $USERNAME_API, $PASSWORD_API)
    {
        $data = array(
            "Account" => array(
                "UserName" => "" . $USERNAME_API . "",
                "Password" => "" . $PASSWORD_API . ""
            )
        );

        $data = array_merge($data, $array_tmp);
        $data = json_encode($data);

        // Usamos CurlWrapper
        $curl = new CurlWrapper($URL_API . $this->METHOD);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL_API . $this->METHOD,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => stripslashes($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        // Ejecutar la solicitud CURL
        $response = $curl->execute();
        $result = json_decode($response);

        return $result;
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * Este metodo detecta y devuelve la dirección IP del cliente desde
     * diferentes variables de entorno.
     *
     * @return string Dirección IP del cliente.
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
