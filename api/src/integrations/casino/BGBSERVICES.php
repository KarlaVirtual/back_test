<?php

/**
 * Este archivo contiene la clase `BGBSERVICES` y la clase `IGPUSER`, que proporcionan
 * funcionalidades para la integración con servicios de casino, como la gestión de juegos,
 * creación de jugadores, autenticación y más.
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
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\Pais;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Exception;

/**
 * Clase `BGBSERVICES` que proporciona métodos para la integración con servicios de casino.
 * Incluye funcionalidades como la gestión de juegos, creación de jugadores, autenticación, entre otros.
 */
class BGBSERVICES
{
    /**
     * Nombre de usuario para la autenticación con la API.
     *
     * @var string
     */
    private $api_login = "DoradoBet";

    /**
     * Código de skin utilizado para identificar la integración.
     *
     * @var string
     */
    private $api_skincode = "23";

    /**
     * Contraseña para la autenticación con la API.
     *
     * @var string
     */
    private $api_password = "711cb47f4fac416e971b74a4c62168d2";

    /**
     * Método actual que se está utilizando en la solicitud.
     *
     * @var string
     */
    private $method;

    /**
     * URL base del servicio de la API.
     *
     * @var string
     */
    private $URL = 'http://api-hub.bestgoldbet.net';

    /**
     * Endpoint específico para la solicitud actual.
     *
     * @var string
     */
    private $endpoint;

    /**
     * Constructor de la clase `BGBSERVICES`.
     */
    public function __construct()
    {
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
        return $this->BGBRequest();
    }

    /**
     * Establece la lista de juegos en la base de datos.
     *
     * @param boolean $show_systems Indica si se deben mostrar sistemas adicionales.
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
     * Crea un jugador en el sistema.
     *
     * @param integer $user_id ID del usuario.
     *
     * @return mixed Respuesta de la solicitud.
     */
    public function createPlayer($user_id)
    {
        try {
            $UsuarioMandante = new UsuarioMandante($user_id);
            $Mandante = new Mandante($UsuarioMandante->getMandante());

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                $Registro = new Registro("", $UsuarioMandante->getUsuarioMandante());
                $Pais = new Pais($Usuario->paisId);

                $this->endpoint = "/hub/PlayerCreate";

                $array = array(
                    "Name" => $Registro->getNombre(),
                    "Surname" => $Registro->getApellido1() . " " . $Registro->getApellido2(),
                    "Country" => $Pais->iso,
                    "Lang" => $Usuario->idioma,
                    "Currency" => $Usuario->moneda,
                    "Username" => "Usuario" . $user_id . "",
                    "Password" => $this->generateKey($user_id)

                );

                return $this->BGBRequest($array);
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Verifica si un jugador existe en el sistema.
     *
     * @param integer $user_id ID del usuario.
     *
     * @return boolean `true` si el jugador existe, `false` en caso contrario.
     */
    public function playerExists($user_id)
    {
        $this->endpoint = "/hub/PlayerExist";

        $array = array(
            "Username" => "Usuario" . $user_id . "",
        );

        $response = $this->BGBRequest($array);

        if ($response->Message->Message == "PLAYER_NOT_EXIST") {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Inicia sesión para un jugador.
     *
     * @param integer $user_id ID del usuario.
     *
     * @return mixed Respuesta de la solicitud.
     */
    public function loginPlayer($user_id)
    {
        $this->endpoint = "/hub/PlayerLogin";

        $array = array(
            "Username" => "Usuario" . $user_id . "",
            "Password" => "" . $this->generateKey($user_id) . ""
        );

        $response = $this->BGBRequest($array);

        return $response;
    }

    /**
     * Prueba la funcionalidad de integración sin interrupciones.
     *
     * @param integer $user_id ID del usuario.
     *
     * @return mixed Respuesta de la solicitud.
     */
    public function TestSeamless($user_id)
    {
        try {
            $UsuarioToken = new UsuarioToken("", 12, 1);

            print_r($UsuarioToken);

            $this->endpoint = "/hub/TestSeamless";

            $array = array(
                "Token" => "" . $UsuarioToken->getToken() . "",
                "Url" => "https://dev.doradobet.com/admin/dao/integrations/casino/bgb/api/"
            );

            $response = $this->BGBRequest($array);

            return $response;
        } catch (Exception $e) {
        }
    }

    /**
     * Obtiene una lista dinámica de juegos.
     *
     * @return mixed Respuesta de la solicitud.
     */
    public function GetDynamicGamesList()
    {
        try {
            $this->endpoint = "/hub/GetDynamicGamesList ";
            $array = array();
            $response = $this->BGBRequest($array);

            return $response;
        } catch (Exception $e) {
        }
    }

    /**
     * Obtiene información de un juego específico.
     *
     * @param integer $game_id ID del juego.
     * @param string  $token   Token de autenticación.
     *
     * @return mixed Respuesta de la solicitud.
     */
    public function GetSingleGame($game_id, $token)
    {
        try {
            $this->endpoint = "/hub/GetSingleGame";

            $array = array(
                "GameId" => $game_id,
                "GameType" => "MOBILE-JSP",
                "Token" => $token
            );

            print_r($array);

            $response = $this->BGBRequest($array);
            return $response;
        } catch (Exception $e) {
        }
    }

    /**
     * Obtiene un juego con parámetros específicos.
     *
     * @param integer $gameid       ID del juego.
     * @param string  $lang         Idioma del juego.
     * @param boolean $play_for_fun Indica si el juego es para diversión.
     * @param string  $usuarioToken Token del usuario.
     *
     * @return mixed Respuesta de la solicitud.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken)
    {
        try {
            $UsuarioToken = new UsuarioToken($usuarioToken);

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


            $response = $this->BGBRequest($array);
            return $response;
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una solicitud al servicio BGB.
     *
     * @param array $array_tmp Datos de la solicitud.
     *
     * @return mixed Respuesta de la solicitud.
     */
    public function BGBRequest($array_tmp)
    {
        $data = array(
            "ApiUsername" => "" . $this->api_login . "",
            "ApiSkinCode" => "" . $this->api_skincode . "",
            "ApiPassword" => "" . $this->api_password . ""
        );

        $data = array_merge($data, $array_tmp);


        $ch = curl_init($this->URL . $this->endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = json_decode(curl_exec($ch));

        return ($result);
    }

    /**
     * Genera una clave única para un jugador.
     *
     * @param integer $player ID del jugador.
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

/**
 * Clase `IGPUSER` que representa un usuario en el sistema IGP.
 * Proporciona métodos para gestionar la información del usuario y realizar operaciones
 * relacionadas con la creación y verificación de jugadores.
 */
class IGPUSER
{
    /**
     * ID del usuario.
     *
     * @var integer
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
     * Instancia de la clase IGP.
     *
     * @var IGP
     */
    private $IGP;

    /**
     * Constructor de la clase `IGPUSER`.
     *
     * @param integer $user_id ID del usuario.
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;

        $this->IGP = new IGP();
    }

    /**
     * Obtiene el ID del usuario.
     *
     * @return integer ID del usuario.
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Establece el ID del usuario.
     *
     * @param integer $user_id ID del usuario.
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
     * @return string Nombre del usuario.
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * Establece el nombre del usuario.
     *
     * @param string $user_name Nombre del usuario.
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
     * @return string Contraseña del usuario.
     */
    public function getUserPassword()
    {
        return $this->user_password;
    }

    /**
     * Establece la contraseña del usuario.
     *
     * @param string $user_password Contraseña del usuario.
     *
     * @return void
     */
    public function setUserPassword($user_password)
    {
        $this->user_password = $user_password;
    }

    /**
     * Crea un jugador si no existe.
     *
     * @return mixed Respuesta de la creación o mensaje de existencia.
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
     * Verifica si el jugador existe.
     *
     * @return boolean `true` si el jugador existe, `false` en caso contrario.
     */
    public function playerExists()
    {
        return $this->IGP->playerExists($this->user_name);
    }


}
