<?php

/**
 * Este archivo contiene la implementación de los servicios de integración con IGP.
 * Proporciona métodos para interactuar con la API de IGP, incluyendo la gestión de juegos,
 * creación de jugadores, verificación de existencia de jugadores, y más.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
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
use Exception;

/**
 * Clase que proporciona servicios de integración con la API de IGP.
 * Incluye métodos para gestionar juegos, jugadores y realizar solicitudes a la API.
 */
class IGPSERVICES
{
    /**
     * Nombre de usuario para autenticarse con la API de IGP.
     *
     * @var string
     */
    private $api_login = "winplay_s";

    /**
     * Contraseña para autenticarse con la API de IGP.
     *
     * @var string
     */
    private $api_password = "apiUSerwinplay_sU";

    /**
     * Método actual que se está utilizando para realizar solicitudes a la API.
     *
     * @var string
     */
    private $method;

    /**
     * URL base para realizar solicitudes a la API de IGP.
     *
     * @var string
     */
    private $URL = 'http://dev.pantaloo.com/api/seamless/provider/';

    /**
     * Constructor de la clase IGPSERVICES.
     * Inicializa una nueva instancia de la clase.
     */
    public function __construct()
    {
    }

    /**
     * Obtiene la lista de juegos desde la API de IGP.
     *
     * @param bool $show_systems Indica si se deben mostrar sistemas adicionales (opcional).
     *
     * @return mixed Respuesta de la API.
     */
    public function getGameList($show_systems = false)
    {
        $this->method = "getGameList";
        return $this->IGPRequest();
    }

    /**
     * Sincroniza la lista de juegos obtenida desde la API con la base de datos local.
     *
     * @param boolean $show_systems Indica si se deben mostrar sistemas adicionales (opcional).
     *
     * @return void
     */
    public function setGameList($show_systems = false)
    {
        $response = $this->getGameList();

        $error = $response->error;
        $games = $response->response;

        if ( ! $error) {
            $ProductoMySqlDAO = new ProductoMySqlDAO();
            $Transaction = $ProductoMySqlDAO->getTransaction();

            try {
                $Proveedor = new Proveedor("", "IGP");
                if ($Proveedor != null && $Proveedor != "") {
                    foreach ($games as $game) {
                        if ($Transaction->isIsconnected()) {
                            $Producto = new Producto();
                            $Producto->setEstado("A");
                            $Producto->setProveedorId($Proveedor->getProveedorId());
                            $Producto->setDescripcion(str_replace("'", "\'", $game->name));
                            $Producto->setImageUrl($game->image);
                            $Producto->setVerifica("I");
                            $Producto->setExternoId($game->id);
                            $Producto->setUsucreaId(0);
                            $Producto->setUsumodifId(0);

                            if ( ! $Producto->existsExternoId()) {
                                $producto_id = $Producto->insert($Transaction);

                                $slug = "";

                                switch ($game->type) {
                                    case "video-slots":
                                        $slug = "video-slots";
                                        break;

                                    case "table-games":
                                        $slug = "table-games";
                                        break;

                                    case "livecasino":
                                        $slug = "live-casino";
                                        break;

                                    case "video-poker":
                                        $slug = "video-poker";
                                        break;

                                    case "video-bingo":
                                        $slug = "video-bingo";
                                        break;
                                }

                                $Categoria = new Categoria("", "", $slug);

                                $CategoriaProducto = new CategoriaProducto();

                                $CategoriaProducto->setProductoId($producto_id);
                                $CategoriaProducto->setCategoriaId($Categoria->getCategoriaId());
                                $CategoriaProducto->setUsumodifId(0);
                                $CategoriaProducto->setUsucreaId(0);

                                $CategoriaProducto->insert($Transaction);

                                $ProductoDetalle = new ProductoDetalle();
                                $ProductoDetalle->setProductoId($producto_id);
                                $ProductoDetalle->setPKey("GAMEID");
                                $ProductoDetalle->setPValue($game->id);
                                $ProductoDetalle->setUsucreaId(0);
                                $ProductoDetalle->setUsumodifId(0);

                                $ProductoDetalle->insert($Transaction);

                                $ProductoDetalle = new ProductoDetalle();
                                $ProductoDetalle->setProductoId($producto_id);
                                $ProductoDetalle->setPKey("TYPE");
                                $ProductoDetalle->setPValue($game->type);
                                $ProductoDetalle->setUsucreaId(0);
                                $ProductoDetalle->setUsumodifId(0);

                                $ProductoDetalle->insert($Transaction);

                                foreach ($game as $key => $val) {
                                    if ($key != "id" && $key != "type") {
                                        $ProductoDetalle = new ProductoDetalle();
                                        $ProductoDetalle->setProductoId($producto_id);
                                        $ProductoDetalle->setPKey(strtoupper($key));
                                        $ProductoDetalle->setPValue(str_replace("'", "", $val));
                                        $ProductoDetalle->setUsucreaId(0);
                                        $ProductoDetalle->setUsumodifId(0);

                                        $ProductoDetalle->insert($Transaction);
                                    }
                                }
                            }
                        }
                    }

                    $Transaction->commit();
                }
            } catch (Exception $e) {
                print_r($e);
                $Transaction->rollback();
            }
        }
    }

    /**
     * Crea un jugador en la API de IGP.
     *
     * Este método establece el método de solicitud como "createPlayer" y
     * envía una solicitud a la API para crear un jugador con el ID proporcionado.
     *
     * @param string $user_id El ID del usuario que se desea crear.
     *
     * @return mixed Respuesta de la API después de intentar crear el jugador.
     */
    public function createPlayer($user_id)
    {
        $this->method = "createPlayer";

        $array = array(
            "user_id" => $user_id,
            "user_username" => "" . $user_id . "",
            "user_password" => $this->generateKey($user_id)

        );

        return $this->IGPRequest($array);
    }

    /**
     * Verifica si un jugador existe en la API de IGP.
     *
     * Este método establece el método de solicitud como "playerExists" y
     * envía una solicitud a la API para verificar si un jugador con el nombre
     * de usuario proporcionado existe.
     *
     * @param string $username El nombre de usuario del jugador a verificar.
     *
     * @return boolean Devuelve `true` si el jugador existe, de lo contrario `false`.
     */
    public function playerExists($username)
    {
        $this->method = "playerExists";

        $array = array(
            "user_username" => "" . $username . ""
        );

        $response = $this->IGPRequest($array);

        if ($response->response == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Inicia sesión para un jugador en la API de IGP.
     *
     * Este método establece el método de solicitud como "loginPlayer" y
     * envía una solicitud a la API para autenticar al jugador con el ID proporcionado.
     *
     * @param string $user_id El ID del usuario que se desea autenticar.
     *
     * @return mixed Respuesta de la API después de intentar iniciar sesión.
     */
    public function loginPlayer($user_id)
    {
        $this->method = "loginPlayer";

        $array = array(
            "user_id" => "" . $user_id . "",
            "user_username" => "" . $user_id . "",
            "user_password" => "" . $this->generateKey($user_id) . ""
        );

        $response = $this->IGPRequest($array);

        return $response;
    }

    /**
     * Obtiene un juego desde la API de IGP.
     *
     * Este método realiza una solicitud a la API para obtener un juego específico
     * basado en el ID del juego, el idioma, y si se desea jugar por diversión.
     * También maneja la creación de tokens de usuario y jugadores si no existen.
     *
     * @param string  $gameid        El ID del juego que se desea obtener.
     * @param string  $lang          El idioma en el que se desea obtener el juego.
     * @param boolean $play_for_fun  Indica si el juego es para jugar por diversión.
     * @param string  $usuarioToken  El token del usuario que solicita el juego.
     * @param string  $usumandanteId Opcional El ID del usuario mandante.
     *
     * @return mixed Respuesta de la API con los detalles del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }


            $Proveedor = new Proveedor("", "IGP");

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

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $user_id = "user" . $UsuarioToken->getUsuarioId();

            if ( ! $this->playerExists($user_id)) {
                $response2 = $this->createPlayer($user_id);
            }

            $this->method = "getGame";

            $array = array(
                "lang" => "" . $lang . "",
                "gameid" => "" . $gameid . "",
                "play_for_fun" => "" . $play_for_fun . "",
                "user_id" => "" . $user_id . "",
                "user_username" => "" . $user_id . "",
                "user_password" => "" . $this->generateKey($user_id) . ""
            );


            $response = $this->IGPRequest($array);
            return $response;
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una solicitud a la API de IGP.
     *
     * Este método construye los datos necesarios para la solicitud, los envía
     * utilizando cURL y devuelve la respuesta decodificada en formato JSON.
     *
     * @param array $array_tmp Datos adicionales que se incluirán en la solicitud.
     *
     * @return mixed Respuesta de la API decodificada en formato JSON.
     */
    public function IGPRequest($array_tmp)
    {
        $data = array(
            "method" => $this->method,
            "api_login" => "" . $this->api_login . "",
            "api_password" => "" . $this->api_password . ""
        );

        $data = array_merge($data, $array_tmp);


        $ch = curl_init($this->URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        //$rs = curl_exec($ch);
        $result = json_decode(curl_exec($ch));

        return ($result);
    }

    /**
     * Genera una clave única basada en el identificador del jugador.
     *
     * Este método utiliza un algoritmo de hash MD5 para generar una clave única
     * de 12 caracteres basada en el identificador del jugador proporcionado.
     *
     * @param string $player El identificador del jugador.
     *
     * @return string La clave generada de 12 caracteres.
     */
    function generateKey($player)
    {
        $hash = md5($player . md5("TMP" . $player));
        $hash = substr($hash, 0, 12);
        return ($hash);
    }

}

/**
 * Clase que representa un usuario en el sistema de integración con IGP.
 * Proporciona métodos para gestionar la creación y verificación de jugadores.
 */
class IGPUSER
{
    /**
     * Identificador único del usuario.
     *
     * @var string
     */
    private $user_id;

    /**
     * Nombre del usuario.
     *
     * @var string
     */
    private $user_name;

    /**
     * Contraseña del usuario.
     *
     * @var string
     */
    private $user_password;

    /**
     * Instancia de la clase IGP para gestionar integraciones.
     *
     * @var IGP
     */
    private $IGP;

    /**
     * Constructor de la clase IGPUSER.
     *
     * Inicializa una nueva instancia de la clase IGPUSER con el ID de usuario proporcionado
     * y crea una instancia de la clase IGP para gestionar integraciones.
     *
     * @param string $user_id El identificador único del usuario.
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;

        $this->IGP = new IGP();
    }

    /**
     * Obtiene el identificador único del usuario.
     *
     * @return string El ID del usuario.
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Establece el identificador único del usuario.
     *
     * @param string $user_id El nuevo ID del usuario.
     *
     * @return void
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Obtiene el nombre del usuario.
     *
     * @return string El nombre del usuario.
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * Establece el nombre del usuario.
     *
     * @param string $user_name El nuevo nombre del usuario.
     *
     * @return void
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    /**
     * Obtiene la contraseña del usuario.
     *
     * @return string La contraseña del usuario.
     */
    public function getUserPassword()
    {
        return $this->user_password;
    }

    /**
     * Establece la contraseña del usuario.
     *
     * @param string $user_password La nueva contraseña del usuario.
     *
     * @return void
     */
    public function setUserPassword($user_password)
    {
        $this->user_password = $user_password;
    }

    /**
     * Crea un jugador en la API de IGP si no existe.
     *
     * Este método verifica si el jugador ya existe en la API de IGP.
     * Si no existe, lo crea utilizando el método `createPlayer` de la clase IGP.
     *
     * @return mixed Devuelve la respuesta de la API al crear el jugador o un mensaje indicando que ya existe.
     */
    public function createPlayer()
    {
        if ($this->playerExists() === false) {
            return $this->IGP->createPlayer($this->user_id);
        } else {
            return "player already exists";
        }
    }

    /**
     * Verifica si un jugador existe en la API de IGP.
     *
     * Este método utiliza la clase IGP para comprobar si un jugador con el nombre
     * de usuario actual existe en la API de IGP.
     *
     * @return boolean Devuelve `true` si el jugador existe, de lo contrario `false`.
     */
    public function playerExists()
    {
        return $this->IGP->playerExists($this->user_name);
    }


}
