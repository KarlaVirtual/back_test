<?php

/**
 * Clase ISOFTBETSERVICES
 *
 * Esta clase proporciona servicios relacionados con la integración de juegos de casino
 * del proveedor ISOFTBET. Incluye métodos para obtener juegos y gestionar tokens de usuario.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase que proporciona servicios relacionados con la integración de juegos de casino
 * del proveedor ISOFTBET.
 */
class ISOFTBETSERVICES
{
    /**
     * URL de redirección para el casino.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * Constructor de la clase.
     *
     * Inicializa el entorno de configuración para determinar si se está en un entorno
     * de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la URL para lanzar un juego.
     *
     * Este método genera la URL necesaria para lanzar un juego, ya sea en modo
     * "jugar por diversión" o en modo real. También gestiona la creación y actualización
     * de tokens de usuario.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si es modo "jugar por diversión".
     * @param string  $usuarioToken  Token del usuario.
     * @param integer $productoId    ID del producto.
     * @param boolean $isMobile      Indica si es un dispositivo móvil (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => "https://stage-game-launcher-lux.isoftbet.com/1041/" . $gameid . "?lang=" . $lang . "&cur=PEN&mode=0"
                );
            } else {
                $Proveedor = new Proveedor("", "ISOFTBET");
                $Producto = new Producto('', $gameid, $Proveedor->getProveedorId());

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

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
                        $UsuarioToken->setToken($UsuarioToken->createToken());
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

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
                }

                $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $array = array(
                    "error" => false,
                    "response" => $Credentials->URL . $Credentials->LICENSEE_ID . "/" . $gameid . "?lang=" . $lang . "&cur=" . $UsuarioMandante->moneda . "&mode=1&user=Usuario" . $UsuarioMandante->usumandanteId . "&uid=" . $UsuarioMandante->usumandanteId . "&token=" . $UsuarioToken->getToken() . "&lobbyURL=" . $this->URLREDIRECTION
                );
            }
            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * Este método detecta y devuelve la dirección IP del cliente que realiza la solicitud.
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
