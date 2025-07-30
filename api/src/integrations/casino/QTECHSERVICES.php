<?php

/**
 * Este archivo contiene la implementación de los servicios de integración con QTECH.
 * Proporciona métodos para interactuar con la API de QTECH, gestionar jugadores,
 * obtener listas de juegos, y realizar operaciones relacionadas con los juegos.
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
 * Clase QTECHSERVICES
 * Proporciona métodos para interactuar con la API de QTECH, gestionar jugadores,
 * obtener listas de juegos y realizar operaciones relacionadas con los juegos.
 */
class QTECHSERVICES
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
     * Método actual utilizado en las solicitudes a la API.
     *
     * @var string
     */
    private $method;

    /**
     * URL base de la API para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://api-int.qtplatform.com/v1/';

    /**
     * URL base de la API para el entorno de producción.
     *
     * @var string
     */
    private $URL = 'https://api.qtplatform.com/v1/';

    /**
     * URL de redirección utilizada en las operaciones.
     *
     * @var string
     */
    private $URLREDIRECTION = 'https://doradobet.com';

    /**
     * URL para el lanzamiento de juegos en el entorno de staging.
     *
     * @var string
     */
    private $LAUNCHURL = 'https://staging.joinwallet.net/api/generic/game/launch';

    /**
     * Identificador del operador utilizado en las solicitudes.
     *
     * @var string
     */
    private $operatorId = 'doradobetwplay-stage';

    /**
     * URL base para servicios adicionales de la red de casino.
     *
     * @var string
     */
    private $URL2 = "https://ws.casinonetwork.world/";

    /**
     * URL para el lanzamiento de juegos en la red de casino.
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
     * Cliente SOAP utilizado para interactuar con la API.
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
            $this->api_login = $this->api_loginDEV;
            $this->api_password = $this->api_passwordDEV;
            $this->URL = $this->URLDEV;
        } else {
        }
    }

    /**
     * Obtiene la lista de juegos disponibles.
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
     * Obtiene información del proveedor de un juego específico.
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
     * Sincroniza la lista de juegos con la base de datos local.
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
                $Proveedor = new Proveedor("", "QTECH");
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
     * Crea un jugador en el sistema QTECH.
     *
     * @param integer $user_id El ID del usuario que se utilizará para crear el jugador.
     *
     * @return mixed Respuesta de la API SOAP al intentar crear el jugador.
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
     * Verifica si un jugador existe en el sistema QTECH.
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

        $response = $this->QTECHRequest($array);

        if ($response->response == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Inicia sesión para un jugador en el sistema QTECH.
     *
     * @param integer $user_id El ID del usuario que se utilizará para iniciar sesión.
     *
     * @return mixed Respuesta de la API SOAP al intentar iniciar sesión el jugador.
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
     * Obtiene la URL de lanzamiento de un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma para la información del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return mixed Devuelve un objeto con la URL del juego o un error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "QTECH");

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

            $access_token = $this->getTokenQT();

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
                $return = $this->QTECHRequest($string, array(
                    "playerId" => $UsuarioMandante->getUsumandanteId(),
                    "currency" => $Usuario->moneda,
                    "country" => $Pais->iso,
                    "gender" => $Registro->getSexo(),
                    "birthDate" => "1999-11-11",
                    "lang" => "es_ES",
                    "mode" => "real",
                    "device" => "desktop",
                    "returnUrl" => $this->URLREDIRECTION,
                    "walletSessionId" => $UsuarioToken->getToken()
                ), array("Authorization: Bearer " . $access_token, "Content-Type: application/json"));


                if (json_decode($return)->url != "") {
                    $array = array(
                        "error" => false,
                        "response" => json_decode($return)->url,
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
        }
    }

    /**
     * Obtiene la página de un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma para la información del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return mixed Devuelve un objeto con la URL del juego o un error.
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

                $Proveedor = new Proveedor("", "QTECH");

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
     * Obtiene la URL de lanzamiento de un juego específico en el sistema QTECHPOKER.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma para la información del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return mixed Devuelve un objeto con la URL del juego o un error.
     */
    public function getGame2($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "QTECHPOKER");

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
     * Verifica si un usuario existe en el sistema QTECH.
     *
     * @param integer $userid El ID del usuario a verificar.
     *
     * @return mixed Respuesta de la API indicando si el usuario existe.
     */
    public function checkUser($userid)
    {
        $string = "?client=Doradobet&op=check&usr=User" . $userid;
        $this->productname = "dbg";
        $return = $this->QTECHRequest2($string);
        return (($return));
    }

    /**
     * Verifica si un usuario existe en el sistema QTECH.
     *
     * @param integer $userid El ID del usuario a verificar.
     *
     * @return mixed Respuesta de la API indicando si el usuario existe.
     */
    public function getGamelist2($userid)
    {
        $string = "?client=Doradobet&op=gamelist&usr=User" . $userid;
        $this->productname = "dbg";
        $return = $this->QTECHRequest2($string);
        return (json_decode($return));
    }

    /**
     * Obtiene un token de acceso para interactuar con la API de QTECH.
     *
     * Este método realiza una solicitud para autenticar al usuario y obtener
     * un token de acceso que se utilizará en las solicitudes posteriores.
     *
     * @return string El token de acceso obtenido de la API.
     */
    public function getTokenQT()
    {
        $string = "auth/token?grant_type=password&response_type=token&" . "username=" . $this->api_login . "&password=" . $this->api_password;
        $return = $this->QTECHRequest($string, array("grant_type" => "password", "response_type" => "token"));


        return json_decode($return)->access_token;
    }

    /**
     * Realiza una solicitud GET a la API de QTECH utilizando cURL.
     *
     * @param string $text El texto que se añadirá a la URL de la solicitud.
     *
     * @return string|false La respuesta de la API en caso de éxito, o `false` en caso de error.
     */
    public function QTECHRequest2($text)
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
     * Realiza una solicitud POST a la API de QTECH utilizando cURL.
     *
     * @param string $string    El texto que se añadirá a la URL de la solicitud.
     * @param array  $array_tmp Un arreglo con los datos adicionales que se enviarán en la solicitud.
     * @param array  $header    Un arreglo opcional con los encabezados HTTP que se incluirán en la solicitud.
     *
     * @return string|false La respuesta de la API en caso de éxito, o `false` en caso de error.
     */
    public function QTECHRequest($string, $array_tmp, $header = array())
    {
        $data = array(
            "username" => "'" . $this->api_login . "'",
            "password" => "" . $this->api_password . ""
        );

        $data = array_merge($data, $array_tmp);
        $data = json_encode($data);

        $ch = curl_init($this->URL . $string);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
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
     * Este método utiliza un algoritmo de hash MD5 para generar una clave única
     * de 12 caracteres basada en el ID del jugador proporcionado.
     *
     * @param integer|string $player El ID del jugador para generar la clave.
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
 * Clase QTECHUSER
 *
 * Esta clase representa un usuario en el sistema QTECH y proporciona métodos
 * para gestionar su información y realizar operaciones relacionadas con el jugador.
 */
class QTECHUSER
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
     * Instancia de la clase QTECH para interactuar con el sistema.
     *
     * @var QTECH
     */
    private $QTECH;

    /**
     * Constructor de la clase QTECHUSER.
     *
     * @param integer $user_id ID del usuario.
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->QTECH = new QTECH();
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
     * Crea un jugador en el sistema QTECH.
     *
     * @return mixed Respuesta de la API al intentar crear el jugador o un mensaje
     *               indicando que el jugador ya existe.
     */
    public function createPlayer()
    {
        if ($this->playerExists() === false) {
            return $this->QTECH->createPlayer($this->user_id);
        } else {
            return "player already exists";
        }
    }

    /**
     * Verifica si un jugador existe en el sistema QTECH.
     *
     * @return boolean `true` si el jugador existe, de lo contrario `false`.
     */
    public function playerExists()
    {
        return $this->QTECH->playerExists($this->user_name);
    }


}
