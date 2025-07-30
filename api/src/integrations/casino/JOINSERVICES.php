<?php

/**
 * Este archivo contiene la implementación de los servicios de integración con el proveedor de juegos JOIN.
 * Proporciona métodos para gestionar jugadores, juegos y transacciones relacionadas con el proveedor.
 *
 * @category   Integración
 * @package    API
 * @subpackage Casino
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
use \SoapClient;
use \SimpleXMLElement;

/**
 * Clase principal para la integración con los servicios de JOIN.
 * Proporciona métodos para interactuar con el proveedor de juegos, gestionar jugadores y realizar transacciones.
 */
class JOINSERVICES
{
    /**
     * Credenciales de inicio de sesión para la API.
     *
     * @var string
     */
    private $api_login = "wplaytestapi";

    /**
     * Contraseña de inicio de sesión para la API.
     *
     * @var string
     */
    private $api_password = "yp9t4bxv9qpg34zglxq1rrr";

    /**
     * Método actual utilizado en las solicitudes a la API.
     *
     * @var string
     */
    private $method;

    /**
     * URL del entorno de desarrollo para el servicio SOAP del proveedor de juegos.
     *
     * @var string
     */
    private $URLDEV = 'http://api.bcwgames.com/soap/gameprovider/v2?wsdl';

    /**
     * URL de lanzamiento de juegos en el entorno de desarrollo.
     *
     * @var string
     */
    private $LAUNCHURLDEV = 'https://staging-wallet.joinwallet.net/api/generic/game/launch';

    /**
     * Identificador del operador en el entorno de desarrollo.
     *
     * @var string
     */
    private $operatorIdDEV = 'doradobetwplay-stage';

    /**
     * URL base para el segundo servicio del proveedor.
     *
     * @var string
     */
    private $URL2 = "https://ws.casinonetwork.world/";

    /**
     * URL de lanzamiento de juegos para el segundo servicio del proveedor.
     *
     * @var string
     */
    private $LAUNCHURL2 = "//ws.casinonetwork.world/";

    /**
     * Nombre del producto utilizado en las solicitudes.
     *
     * @var string
     */
    private $productname;

    /**
     * URL del entorno de producción para el servicio SOAP del proveedor de juegos.
     *
     * @var string
     */
    private $URL = 'http://api.bcwgames.com/soap/gameprovider/v2?wsdl';

    /**
     * URL de lanzamiento de juegos en el entorno de producción.
     *
     * @var string
     */
    private $LAUNCHURL = 'https://joinwallet.net:81/api/generic/game/launch';

    /**
     * Identificador del operador en el entorno de producción.
     *
     * @var string
     */
    private $operatorId = 'doradobet-prod';

    /**
     * Cliente SOAP utilizado para realizar solicitudes al proveedor.
     *
     * @var SoapClient
     */
    private $soap;

    /**
     * Constructor de la clase.
     * Configura las URLs y credenciales según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->LAUNCHURL = $this->LAUNCHURLDEV;
            $this->operatorId = $this->operatorIdDEV;
        } else {
        }

        $this->soap = new SoapClient($this->URL, array('login' => $this->api_login, 'password' => $this->api_password));
    }

    /**
     * Obtiene la lista de juegos disponibles desde el proveedor.
     *
     * @param boolean $show_systems Indica si se deben mostrar sistemas adicionales.
     *
     * @return mixed Lista de juegos obtenida desde la API.
     */
    public function getGameList($show_systems = false)
    {
        $this->method = "GameList";
        return $this->soap->getGameList();
    }

    /**
     * Obtiene información de un juego específico desde el proveedor.
     *
     * @param string $gameid   ID del juego.
     * @param string $language Idioma para la información del juego.
     *
     * @return mixed Información del juego.
     */
    public function getGameProvider($gameid, $language)
    {
        return ($this->soap->getGame($gameid, $language));
    }

    /**
     * Sincroniza la lista de juegos del proveedor con la base de datos local.
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
                $Proveedor = new Proveedor("", "JOIN");
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
     * Crea un nuevo jugador en el sistema del proveedor.
     *
     * @param integer $user_id El identificador único del usuario.
     *
     * @return mixed Respuesta del proveedor al intentar crear el jugador.
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
     * Verifica si un jugador existe en el sistema del proveedor.
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

        $response = $this->JOINRequest($array);

        if ($response->response == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Inicia sesión para un jugador en el sistema del proveedor.
     *
     * @param integer $user_id El identificador único del usuario.
     *
     * @return mixed Respuesta del proveedor al intentar iniciar sesión.
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
     * Obtiene información de un juego específico desde el proveedor.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma para la información del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return mixed Información del juego obtenida desde el proveedor.
     * @throws Exception Si ocurre un error durante la ejecución.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "JOIN");

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


            //$response2 = $this->createPlayer($UsuarioToken->getUsuarioId());
            $response2 = $this->loginPlayer($UsuarioToken->getUsuarioId());

            print_r($response2);
            $response22 = $this->getGameProvider("573", "en");
            print_r("TT");
            print_r($response22);

            return $response22;
        } catch (Exception $e) {
        }
    }

    /**
     * Obtiene la página de un juego específico desde el proveedor.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma para la información del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return mixed Información de la página del juego o un error en caso de fallo.
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

                $Proveedor = new Proveedor("", "JOIN");

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
     * Obtiene la página de un juego específico desde el proveedor JOINPOKER.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma para la información del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return mixed Información de la página del juego o un error en caso de fallo.
     * @throws Exception Si ocurre un error durante la ejecución.
     */
    public function getGame2($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "JOINPOKER");

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
     * Obtiene la sesión de un usuario en el sistema del proveedor.
     *
     * @param integer $userid El identificador único del usuario.
     *
     * @return mixed Respuesta del proveedor con la información de la sesión.
     */
    public function getSession($userid)
    {
        $string = "?client=Doradobet&op=session&usr=User" . $userid;
        $this->productname = "dbg";
        $return = $this->JOINRequest2($string);

        return (($return));
    }

    /**
     * Obtiene el balance de un usuario en el sistema del proveedor.
     *
     * @param integer $userid El identificador único del usuario.
     *
     * @return mixed Respuesta del proveedor con el balance del usuario.
     */
    public function getBalance2($userid)
    {
        $string = "?client=Doradobet&op=balance&usr=User" . $userid;
        $this->productname = "dbg";
        $return = $this->JOINRequest2($string);

        return (($return));
    }

    /**
     * Inserta un nuevo usuario en el sistema del proveedor.
     *
     * @param integer $userid El identificador único del usuario.
     *
     * @return mixed Respuesta del proveedor al intentar insertar el usuario.
     */
    public function insertUser($userid)
    {
        $string = "?client=Doradobet&op=insert&usr=User" . $userid;
        $this->productname = "dbg";
        $return = $this->JOINRequest2($string);
        return (($return));
    }

    /**
     * Verifica si un usuario existe en el sistema del proveedor.
     *
     * @param integer $userid El identificador único del usuario.
     *
     * @return mixed Respuesta del proveedor indicando si el usuario existe.
     */
    public function checkUser($userid)
    {
        $string = "?client=Doradobet&op=check&usr=User" . $userid;
        $this->productname = "dbg";
        $return = $this->JOINRequest2($string);
        return (($return));
    }

    /**
     * Realiza un depósito para un usuario en el sistema del proveedor.
     *
     * @param integer $userid El identificador único del usuario.
     * @param float   $amount La cantidad a depositar.
     *
     * @return mixed Respuesta del proveedor al intentar realizar el depósito.
     */
    public function depositUser($userid, $amount)
    {
        $string = "?client=Doradobet&op=deposit&usr=User" . $userid . "&amount=" . $amount;
        $this->productname = "dbg";
        $return = $this->JOINRequest2($string);
        return (($return));
    }

    /**
     * Realiza un retiro para un usuario en el sistema del proveedor.
     *
     * @param integer $userid El identificador único del usuario.
     * @param float   $amount La cantidad a retirar.
     *
     * @return mixed Respuesta del proveedor al intentar realizar el retiro.
     */
    public function withdrawUser($userid, $amount)
    {
        $string = "?client=Doradobet&op=withdraw&usr=User" . $userid . "&amount=" . $amount;
        $this->productname = "dbg";
        $return = $this->JOINRequest2($string);
        return (($return));
    }

    /**
     * Obtiene la lista de juegos disponibles para un usuario específico.
     *
     * @param integer $userid El identificador único del usuario.
     *
     * @return mixed Lista de juegos obtenida desde el proveedor, decodificada como un objeto JSON.
     */
    public function getGamelist2($userid)
    {
        $string = "?client=Doradobet&op=gamelist&usr=User" . $userid;
        $this->productname = "dbg";
        $return = $this->JOINRequest2($string);
        return (json_decode($return));
    }

    /**
     * Realiza una solicitud HTTP GET al servicio del proveedor JOIN.
     *
     * @param string $text El texto que se añadirá a la URL de la solicitud.
     *
     * @return mixed La respuesta del proveedor como resultado de la solicitud.
     */
    public function JOINRequest2($text)
    {
        $ch = curl_init($this->URL2 . $this->productname . "/Main.ashx" . $text);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = (curl_exec($ch));

        return ($result);
    }

    /**
     * Realiza una solicitud HTTP POST al servicio del proveedor JOIN.
     *
     * Este método utiliza cURL para enviar una solicitud POST al servicio del proveedor
     * con los datos proporcionados. Los datos incluyen el método, las credenciales de la API
     * y cualquier información adicional proporcionada en el parámetro `$array_tmp`.
     *
     * @param array $array_tmp Datos adicionales que se incluirán en la solicitud.
     *
     * @return mixed La respuesta del proveedor decodificada como un objeto JSON.
     */
    public function JOINRequest($array_tmp)
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
        $result = json_decode(curl_exec($ch));
        print_r("TEST");
        print_r($result);

        return ($result);
    }

    /**
     * Genera una clave única basada en el identificador del jugador.
     *
     * @param string $player El identificador único del jugador.
     *
     * @return string La clave generada, truncada a 12 caracteres.
     */
    function generateKey($player)
    {
        $hash = md5($player . md5("TMP" . $player));
        $hash = substr($hash, 0, 12);
        return ($hash);
    }

}

/**
 * Clase `JOINUSER`
 *
 * Esta clase representa un usuario en el sistema JOIN y proporciona métodos
 * para gestionar la creación y verificación de jugadores en el proveedor.
 */
class JOINUSER
{
    /**
     * Identificador único del usuario.
     *
     * @var integer
     */
    private $user_id;

    /**
     * Nombre de usuario.
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
     * Instancia de la clase JOIN para gestionar integraciones.
     *
     * @var JOIN
     */
    private $JOIN;

    /**
     * Constructor de la clase `JOINUSER`.
     *
     * Inicializa el identificador único del usuario y crea una instancia
     * de la clase `JOIN` para gestionar integraciones con el proveedor.
     *
     * @param integer $user_id El identificador único del usuario.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;

        $this->JOIN = new JOIN();
    }

    /**
     * Obtiene el identificador único del usuario.
     *
     * @return integer El identificador único del usuario.
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Establece el identificador único del usuario.
     *
     * @param integer $user_id El identificador único del usuario.
     *
     * @return void
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Obtiene el nombre de usuario.
     *
     * @return string El nombre de usuario.
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * Establece el nombre de usuario.
     *
     * @param string $user_name El nombre de usuario a establecer.
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
     * @param string $user_password La contraseña del usuario a establecer.
     *
     * @return void
     */
    public function setUserPassword($user_password)
    {
        $this->user_password = $user_password;
    }

    /**
     * Crea un nuevo jugador en el sistema del proveedor.
     *
     * @return mixed Respuesta del proveedor al intentar crear el jugador,
     *               o un mensaje indicando que el jugador ya existe.
     */
    public function createPlayer()
    {
        if ($this->playerExists() === false) {
            return $this->JOIN->createPlayer($this->user_id);
        } else {
            return "player already exists";
        }
    }

    /**
     * Verifica si un jugador existe en el sistema del proveedor.
     *
     * @return boolean Devuelve `true` si el jugador existe, de lo contrario `false`.
     */
    public function playerExists()
    {
        return $this->JOIN->playerExists($this->user_name);
    }


}
