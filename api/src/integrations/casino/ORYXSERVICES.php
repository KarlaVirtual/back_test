<?php

/**
 * Clase ORYXSERVICES
 *
 * Esta clase proporciona servicios de integración con la plataforma ORYX Gaming.
 * Incluye métodos para gestionar jugadores, obtener listas de juegos y generar URLs
 * para acceder a juegos específicos, tanto en modo real como en modo de prueba.
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
 * Clase ORYXSERVICES
 *
 * Proporciona métodos para la integración con la plataforma ORYX Gaming,
 * incluyendo la gestión de jugadores, obtención de listas de juegos y generación
 * de URLs para acceder a juegos en diferentes modos.
 */
class ORYXSERVICES
{
    /**
     * ID del operador.
     *
     * @var string
     */
    private $operatorId = "10178001";

    /**
     * Método actual que se está ejecutando.
     *
     * @var string
     */
    private $method;

    /**
     * URL para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://play-prodcopy.oryxgaming.com/agg_plus_public/launch/wallets/DORADOBET/games/';

    /**
     * URL para el entorno de producción.
     *
     * @var string
     */
    private $URL = 'https://play-rghr.igplatform.net/agg_plus_public/launch/wallets/DORADOBET/games/';

    /**
     * Constructor de la clase.
     * Configura la URL según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
        } else {
        }
    }

    /**
     * Obtiene la lista de juegos disponibles.
     *
     * @param boolean $show_systems Indica si se deben mostrar sistemas adicionales.
     *
     * @return mixed Respuesta de la solicitud.
     */
    public function getGameList($show_systems = false)
    {
        $this->method = "getGameList";

        return $this->IGPRequest();
    }

    /**
     * Establece una lista de juegos.
     *
     * @param boolean $show_systems Indica si se deben mostrar sistemas adicionales.
     *
     * @return void
     */
    public function setGameList($show_systems = false)
    {
    }

    /**
     * Crea un jugador en la plataforma.
     *
     * @param string $user_id ID del usuario.
     *
     * @return void
     */
    public function createPlayer($user_id)
    {
    }

    /**
     * Verifica si un jugador existe en la plataforma.
     *
     * @param string $username Nombre de usuario.
     *
     * @return void
     */
    public function playerExists($username)
    {
    }

    /**
     * Inicia sesión de un jugador en la plataforma.
     *
     * @param string $user_id ID del usuario.
     *
     * @return void
     */
    public function loginPlayer($user_id)
    {
        $this->method = "loginPlayer";

        $array = array(
            "user_id" => "" . $user_id . "",
            "user_username" => "" . $user_id . "",
            "user_password" => "" . $this->generateKey($user_id) . ""
        );
    }

    /**
     * Obtiene la URL para acceder a un juego.
     *
     * @param string|int $gameid        ID del juego.
     * @param string     $lang          Idioma del juego.
     * @param boolean    $play_for_fun  Indica si es modo de prueba.
     * @param string     $usuarioToken  Token del usuario.
     * @param string     $productoId    ID del producto (opcional).
     * @param string     $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId = "", $usumandanteId = "")
    {
        try {
            if ($play_for_fun) {
                if (is_string($gameid)) {
                    $array = array(
                        "error" => false,
                        "response" => $this->URL . $gameid . "/open?token=" . strtolower($lang),
                    );
                } else {
                    $array = array(
                        "error" => false,
                        "response" => $this->URL . $gameid . "/open?token=" . strtolower($lang),
                    );
                }

                return json_decode(json_encode($array));
            } else {
                $Proveedor = new Proveedor("", "ORYX");

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

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


                if (is_string($gameid)) {
                    $array = array(
                        "error" => false,
                        "response" => $this->URL . $gameid . "/open?token=" . $UsuarioToken->getToken() . "&languageCode=" . strtolower($lang) . "&playMode=REAL&lobbyUrl=https%3A%2F%2Fdoradobet.com",
                    );
                } else {
                    $array = array(
                        "error" => false,
                        "response" => $this->URL . $gameid . "/open?token=" . $UsuarioToken->getToken() . "&languageCode=" . strtolower($lang) . "&playMode=REAL&lobbyUrl=https%3A%2F%2Fdoradobet.com",
                    );
                }

                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Genera una clave única para un jugador.
     *
     * @param string $player ID del jugador.
     *
     * @return string Clave generada.
     */
    function generateKey($player)
    {
        $hash = md5($player . md5("TMP" . $player));
        $hash = substr($hash, 0, 12);
        return ($hash);
    }

}
