<?php

/**
 * Clase URGENTGAMESSERVICES
 *
 * Esta clase proporciona servicios relacionados con juegos de casino, incluyendo la obtención de juegos,
 * la gestión de tokens de usuario y la interacción con APIs externas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use \CurlWrapper;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase que proporciona servicios relacionados con juegos de casino.
 *
 * Esta clase incluye métodos para la obtención de juegos, gestión de tokens de usuario
 * y la interacción con APIs externas relacionadas con casinos.
 */
class URGENTGAMESSERVICES
{
    /**
     * URL de redirección para el casino.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * URL para realizar depósitos.
     *
     * @var string
     */
    private $URLDEPOSITO = "";

    /**
     * Constructor de la clase.
     *
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
     * Obtiene un juego de casino.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $productoId    ID del producto.
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con los datos del juego.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => '' . "?action=get_demo&args[1][urs]=" . '' . "&args[1][passw]=" . '' . "&args[0][game_id]=" . $gameid
                );
            } else {
                $Proveedor = new Proveedor("", "URGENTGAMES");
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

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
                    $this->URLDEPOSITO = $Mandante->baseUrl . "gestion/deposito";
                }

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $Patch = "/games/admin?action=get_game&token=" . $Credentials->PASSWORD . "&game_id=" . $gameid . "&casino=" . $Credentials->USER;

                $result = $this->Launch($Credentials->URL . $Patch);
                $result = json_decode($result);

                $array = array(
                    "error" => false,
                    "response" => $result->response->url . "?token=" . $Credentials->PASSWORD . "&remote_id=" . $UsuarioMandante->getUsumandanteId() . "&casino=" . $Credentials->USER . "&language=" . $lang . "&currency=" . $UsuarioMandante->getMoneda() . "&session_id=" . $UsuarioToken->getToken() . "&home=" . $this->URLREDIRECTION . "&deposit=" . $this->URLDEPOSITO . "&server=" . $Credentials->URL
                );
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una solicitud HTTP GET a una URL específica.
     *
     * @param string $URL URL a la que se realizará la solicitud.
     *
     * @return string Respuesta de la solicitud.
     */
    public function Launch($URL)
    {
        $curl = new CurlWrapper($URL);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ));

        $response = $curl->execute();
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
