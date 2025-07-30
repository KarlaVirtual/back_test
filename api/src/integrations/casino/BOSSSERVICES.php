<?php

/**
 * Este archivo contiene la clase `BOSSSERVICES` y la clase `BOSSUSER`,
 * que proporcionan funcionalidades para la integración con servicios de casino.
 *
 * La clase `BOSSSERVICES` incluye métodos para gestionar juegos, jugadores y
 * realizar solicitudes a servicios externos relacionados con el casino.
 *
 * La clase `BOSSUSER` permite gestionar usuarios y verificar su existencia
 * en el sistema.
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
use Backend\dto\Registro;
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
use \SoapClient;
use \SimpleXMLElement;

/**
 * Clase `BOSSSERVICES`
 *
 * Proporciona métodos para interactuar con servicios de casino, incluyendo
 * la gestión de juegos, jugadores y solicitudes a servicios externos.
 */
class BOSSSERVICES
{
    /**
     * Credenciales de inicio de sesión para el entorno de desarrollo.
     *
     * @var string
     */
    private $api_loginDEV = "api_doradobet";

    /**
     * Contraseña de inicio de sesión para el entorno de desarrollo.
     *
     * @var string
     */
    private $api_passwordDEV = "sJBFxCVE";

    /**
     * Credenciales de inicio de sesión para el entorno de producción.
     *
     * @var string
     */
    private $api_login = "api_doradobet";

    /**
     * Contraseña de inicio de sesión para el entorno de producción.
     *
     * @var string
     */
    private $api_password = "8zRJez72";

    /**
     * Método actual utilizado en las solicitudes.
     *
     * @var string
     */
    private $method;

    /**
     * URL del entorno de desarrollo 2.
     *
     * @var string
     */
    private $URLDEV2 = 'https://api-int.qtplatform.com/v1/';

    /**
     * URL del entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://stageinterlayer.bossgs.net/';

    /**
     * URL del entorno de producción.
     *
     * @var string
     */
    private $URL = 'https://interlayer.bossgs.net/';

    /**
     * URL de redirección para el cliente.
     *
     * @var string
     */
    private $URLREDIRECTION = 'https://doradobet.com';

    /**
     * URL de lanzamiento para el entorno de producción.
     *
     * @var string
     */
    private $LAUNCHURLPROD = '';

    /**
     * Identificador del producto (pid).
     *
     * @var string
     */
    private $pid = 'anothergames'; // ags_id

    /**
     * Identificador del cliente (cid).
     *
     * @var string
     */
    private $cid = 'doradobet'; // ecp_id

    /**
     * URL del servicio externo 2.
     *
     * @var string
     */
    private $URL2 = "https://ws.casinonetwork.world/";

    /**
     * URL de lanzamiento del servicio externo 2.
     *
     * @var string
     */
    private $LAUNCHURL2 = "//ws.casinonetwork.world/";

    /**
     * Nombre del producto actual.
     *
     * @var string
     */
    private $productname;

    /**
     * Cliente SOAP utilizado para las solicitudes.
     *
     * @var SoapClient
     */
    private $soap;


    /**
     * Constructor de la clase.
     * Configura las credenciales y URLs según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->api_login = $this->api_loginDEV;
            $this->api_password = $this->api_passwordDEV;
        } else {
        }
    }

    /**
     * Obtiene la lista de juegos disponibles.
     *
     * @param boolean $show_systems Indica si se deben mostrar sistemas adicionales.
     *
     * @return mixed Lista de juegos obtenida del servicio SOAP.
     */
    public function getGameList($show_systems = false)
    {
        $this->method = "GameList";
        return $this->soap->getGameList();
    }

    /**
     * Obtiene información de un proveedor de juegos específico.
     *
     * @param string $gameid   ID del juego.
     * @param string $language Idioma para la información del juego.
     *
     * @return mixed Información del proveedor del juego.
     */
    public function getGameProvider($gameid, $language)
    {
        return ($this->soap->getGame($gameid, $language));
    }

    /**
     * Establece la lista de juegos en la base de datos.
     *
     * Obtiene la lista de juegos desde el servicio externo y la almacena en la base de datos.
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
                $Proveedor = new Proveedor("", "BOSS");
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
                $Transaction->rollback();
            }
        }
    }

    /**
     * Crea un jugador en el sistema.
     *
     * @param integer $user_id ID del usuario.
     *
     * @return mixed Respuesta del servicio SOAP al crear el jugador.
     */
    public function createPlayer($user_id)
    {
        $array = array(
            "username" => "User" . $user_id,
            "password" => $this->generateKey($user_id),
            "nickname" => "User" . $user_id,
            "fname" => "User" . $user_id,
            "lname" => "User" . $user_id

        );

        return $this->soap->createPlayer($array);
    }

    /**
     * Verifica si un jugador existe en el sistema.
     *
     * @param string $username Nombre de usuario del jugador.
     *
     * @return boolean `true` si el jugador existe, `false` en caso contrario.
     */
    public function playerExists($username)
    {
        $this->method = "playerExists";

        $array = array(
            "user_username" => "" . $username . ""
        );

        $response = $this->BOSSRequest($array);

        if ($response->response == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Inicia sesión para un jugador en el sistema.
     *
     * @param integer $user_id ID del usuario.
     *
     * @return mixed Respuesta del servicio SOAP al iniciar sesión.
     */
    public function loginPlayer($user_id)
    {
        $array = array(
            "username" => "User" . $user_id,
            "password" => "" . $this->generateKey($user_id) . ""
        );


        return $this->soap->playerLogin($array);
    }

    /**
     * Obtiene la URL para iniciar un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $productoId    ID del producto (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return mixed Respuesta con la URL del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId = "", $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "BOSS");

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


            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);


            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
            }


            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Pais = new Pais($Usuario->paisId);

                $Registro = new Registro("", $Usuario->usuarioId);


                $string = "games/" . $gameid . "/launch-url";
                $string = "initgame?" . "cid=" . $this->cid . "&pid=" . $this->pid . "&game=" . $gameid . "&uid=" . $UsuarioMandante->getUsumandanteId() . "&demo=0&currency=" . $UsuarioMandante->getMoneda() . "&homeurl=" . $this->URLREDIRECTION;

                $return = $this->BOSSRequest($string, array(), array());


                if ($UsuarioToken->getUsuarioId() == 16) {
                    //  print_r($return);
                }

                if (json_decode($return)->result->session != "") {
                    $UsuarioToken->setToken(json_decode($return)->result->session);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->update($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                }


                if (json_decode($return)->result->link != "") {
                    $array = array(
                        "error" => false,
                        "response" => json_decode($return)->result->link,
                    );
                } else {
                    $array = array(
                        "error" => true,
                        "response" => "",
                    );
                }
            }


            return (json_decode(json_encode($array)));

            return $array;
        } catch (Exception $e) {
            print_r($e);
        }
    }

    /**
     * Obtiene la página de un juego.
     *
     * Este método genera la URL para acceder a la página de un juego, ya sea en modo demo
     * o en modo real. Si el juego es en modo demo, se devuelve una respuesta vacía.
     * En caso contrario, se valida el token del usuario y se construye la URL del juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return mixed Respuesta con la URL del juego o un error.
     */
    public function getGamePage($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {
        if ($play_for_fun) {
            $array = array(
                "error" => false,
                "response" => "",
            );
            return json_decode(json_encode($array));
        } else {
            try {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "BOSS");

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


                $UsuarioMandante = new UsuarioMandante($usumandanteId);

                $array = array(
                    "error" => false,
                    "response" => $this->LAUNCHURL . "?mode=real&token=" . $UsuarioToken->getToken() . "&game_id=" . $gameid . "&language=" . strtolower($lang) . "&operator_id=" . $this->operatorId . "&currency=" . $UsuarioMandante->getMoneda(),
                );
                return json_decode(json_encode($array));
            } catch (Exception $e) {
                // print_r($e);
            }
        }
    }

    /**
     * Obtiene la URL para iniciar un juego en el sistema BOSSPOKER.
     *
     * Este método verifica si el usuario mandante tiene un token válido y, si no,
     * lo crea. Luego, valida si el usuario existe en el sistema externo y, si no,
     * lo inserta. Finalmente, obtiene una sesión válida para el usuario y genera
     * la URL de lanzamiento del juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return mixed Respuesta con la URL del juego o un error.
     */
    public function getGame2($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "BOSSPOKER");

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


            //$response2 = $this->createPlayer($UsuarioToken->getUsuarioId());
            $response2 = $this->checkUser($UsuarioToken->getUsuarioId());
            $checkXML = new SimpleXMLElement($response2);

            if ($checkXML->RESPONSE->RESULT != "KO") {
            } else {
                $response2 = $this->insertUser($UsuarioToken->getUsuarioId());
                $insertXML = new SimpleXMLElement($response2);
            }

            $response2 = $this->getSession($UsuarioToken->getUsuarioId());
            $sessionXML = new SimpleXMLElement($response2);

            if ($sessionXML->RESPONSE->RESULT != "KO") {
                $session = $sessionXML->RESPONSE->RESULT[0];
                $array = array(
                    "error" => false,
                    "response" => $this->LAUNCHURL2 . $this->productname . "/Player.aspx" . "?session=" . $session,
                );
            } else {
                $array = array(
                    "error" => true,
                    "response" => "",
                );
            }


            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Verifica si un usuario existe en el sistema.
     *
     * Este método realiza una solicitud al servicio externo para comprobar
     * la existencia de un usuario basado en su ID.
     *
     * @param integer $userid ID del usuario a verificar.
     *
     * @return mixed Respuesta del servicio externo indicando si el usuario existe.
     */
    public function checkUser($userid)
    {
        $string = "?client=Doradobet&op=check&usr=User" . $userid;
        $this->productname = "dbg";
        $return = $this->BOSSRequest2($string);
        return (($return));
    }

    /**
     * Obtiene la lista de juegos disponibles para un usuario específico.
     *
     * Este método realiza una solicitud al servicio externo para obtener
     * la lista de juegos asociados a un usuario dado.
     *
     * @param integer $userid ID del usuario.
     *
     * @return mixed Lista de juegos en formato JSON decodificado.
     */
    public function getGamelist2($userid)
    {
        $string = "?client=Doradobet&op=gamelist&usr=User" . $userid;
        $this->productname = "dbg";
        $return = $this->BOSSRequest2($string);
        return (json_decode($return));
    }

    /**
     * Obtiene un token de autenticación para el sistema QT.
     *
     * Este método realiza una solicitud al servicio externo para obtener un token
     * de acceso utilizando las credenciales configuradas.
     *
     * @return string Token de acceso obtenido del servicio.
     */
    public function getTokenQT()
    {
        $string = "auth/token?grant_type=password&response_type=token&" . "username=" . $this->api_login . "&password=" . $this->api_password;
        $return = $this->BOSSRequest($string, array("grant_type" => "password", "response_type" => "token"));


        return json_decode($return)->access_token;
    }

    /**
     * Realiza una solicitud al servicio externo BOSS utilizando cURL.
     *
     * @param string $text Parámetros de la solicitud en formato de texto.
     *
     * @return string Respuesta del servicio externo.
     */
    public function BOSSRequest2($text)
    {
        $ch = curl_init($this->URL2 . $this->productname . "/Main.ashx" . $text);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        //$rs = curl_exec($ch);
        $result = (curl_exec($ch));

        return ($result);
    }

    /**
     * Realiza una solicitud al servicio externo BOSS utilizando cURL.
     *
     * Este método envía una solicitud HTTP al servicio externo configurado en la URL
     * de la clase, utilizando los parámetros proporcionados.
     *
     * @param string $string    Ruta o parámetros adicionales para la solicitud.
     * @param array  $array_tmp Datos que se incluirán en el cuerpo de la solicitud.
     * @param array  $header    Encabezados HTTP opcionales para la solicitud.
     *
     * @return string Respuesta del servicio externo en formato JSON o texto.
     */
    public function BOSSRequest($string, $array_tmp, $header = array())
    {
        $data = array();

        $data = array_merge($data, $array_tmp);
        $data = json_encode($data);

        $ch = curl_init($this->URL . $string);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        //$rs = curl_exec($ch);
        $result = (curl_exec($ch));

        return ($result);
    }

    /**
     * Genera una clave única basada en el ID del jugador.
     *
     * Este método utiliza un hash MD5 para generar una clave única
     * de 12 caracteres basada en el ID del jugador proporcionado.
     *
     * @param integer|string $player ID del jugador.
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
 * Clase `BOSSUSER`
 *
 * Proporciona métodos para gestionar usuarios y verificar su existencia en el sistema.
 */
class BOSSUSER
{
    /**
     * Identificador del usuario.
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
     * Instancia de la clase BOSS para gestionar integraciones.
     *
     * @var BOSS
     */
    private $BOSS;

    /**
     * Constructor de la clase.
     *
     * @param integer $user_id ID del usuario.
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;

        $this->BOSS = new BOSS();
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
     * Crea un jugador en el sistema si no existe.
     *
     * @return mixed Respuesta al intentar crear el jugador.
     */
    public function createPlayer()
    {
        if ($this->playerExists() === false) {
            return $this->BOSS->createPlayer($this->user_id);
        } else {
            return "player already exists";
        }
    }

    /**
     * Verifica si un jugador existe en el sistema.
     *
     * @return boolean `true` si el jugador existe, `false` en caso contrario.
     */
    public function playerExists()
    {
        return $this->BOSS->playerExists($this->user_name);
    }


}
