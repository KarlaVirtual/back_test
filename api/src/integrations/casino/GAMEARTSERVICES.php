<?php

/**
 * Clase GAMEARTSERVICES
 *
 * Esta clase proporciona servicios para la integración con el proveedor de juegos GAMEART.
 * Incluye métodos para obtener juegos, realizar solicitudes HTTP y obtener la dirección IP del cliente.
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
 * Clase GAMEARTSERVICES
 *
 * Proporciona servicios para la integración con el proveedor de juegos GAMEART.
 * Incluye métodos para obtener juegos, realizar solicitudes HTTP y obtener la dirección IP del cliente.
 */
class GAMEARTSERVICES
{
    /**
     * URL de redirección para los juegos.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * Tipo de acción a realizar (por ejemplo, obtener juego o demo).
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Constructor de la clase.
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene un juego de GAMEART.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si es un juego demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $productoId    ID del producto.
     * @param boolean $isMobile      Indica si es un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($play_for_fun) {
                $this->tipo = "get_demo";
                $array = array(
                    "error" => false,
                    "response" => '' . "?action= " . $this->tipo . "&args[1][urs]=" . '' . "&args[1][passw]=" . '' . "&args[0][game_id]=" . $gameid
                );
            } else {
                $Proveedor = new Proveedor("", "GAMEART");
                $Producto = new Producto('', $gameid, $Proveedor->proveedorId);

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

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
                }

                $exitUrl = $Mandante->baseUrl . 'new-casino';

                $this->tipo = "get_game";

                $URLSERVICE = $Credentials->URL . "?action=" . $this->tipo . "&args[1][usr]=" . $Credentials->USER . "&args[1][passw]=" . $Credentials->PASSWORD . "&args[0][remote_id]=" . $UsuarioMandante->getUsumandanteId() . "&args[0][game_id]=" . $gameid . "&args[0][locale]=" . $lang . "&args[0][home]=" . $exitUrl;

                $response = $this->launchUrl($URLSERVICE);
                $result = json_decode($response);

                $array = array(
                    "error" => false,
                    "response" => $result->response->game_url
                );
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una solicitud HTTP GET a una URL específica.
     *
     * @param string $url URL a la que se realizará la solicitud.
     *
     * @return string Respuesta de la solicitud.
     */
    public function launchUrl($url)
    {
        $curl = new CurlWrapper($url);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        ));

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
