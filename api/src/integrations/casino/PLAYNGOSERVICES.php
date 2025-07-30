<?php

/**
 * Clase principal para la integración con los servicios de Play'n GO.
 *
 * Este archivo contiene la implementación de la clase `PLAYNGOSERVICES` y su clase auxiliar `PLAYNGOUSER`.
 * Proporciona métodos para interactuar con la API de Play'n GO, incluyendo la gestión de juegos, usuarios y ofertas de giros gratis.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use DateTime;
use Exception;
use \SoapClient;
use \CurlWrapper;
use \SimpleXMLElement;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase `PLAYNGOSERVICES`
 *
 * Proporciona métodos para interactuar con los servicios de Play'n GO, incluyendo:
 * - Gestión de listas de juegos.
 * - Creación y autenticación de jugadores.
 * - Generación de tokens de usuario.
 * - Configuración de ofertas de giros gratis.
 */
class PLAYNGOSERVICES
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
     * URL base para las solicitudes de la API.
     *
     * @var string
     */
    private $URL = 'https://lamcw.playngonetwork.com';

    /**
     * URL base para las solicitudes de juegos mini.
     *
     * @var string
     */
    private $URLMINI = "https://lamcw.playngonetwork.com";

    /**
     * URL base para las solicitudes de juegos mini en desarrollo.
     *
     * @var string
     */
    private $URLMINIDEV = "https://lamcwstage.playngonetwork.com";

    /**
     * URL base para las solicitudes de juegos mini en producción.
     *
     * @var string
     */
    private $URLMINIPROD = "";

    /**
     * URL base para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://lamcwstage.playngonetwork.com';

    /**
     * URL de redirección para el entorno de producción.
     *
     * @var string
     */
    private $URLREDIRECTION = 'https://doradobet.com';

    /**
     * URL base para el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = "";

    /**
     * URL para el lanzamiento de juegos.
     *
     * @var string
     */
    private $LAUNCHURL = 'https://staging.joinwallet.net/api/generic/game/launch';

    /**
     * Identificador del operador.
     *
     * @var string
     */
    private $operatorId = 'doradobetwplay-stage';

    /**
     * URL secundaria para solicitudes adicionales.
     *
     * @var string
     */
    private $URL2 = "https://ws.casinonetwork.world/";

    /**
     * URL secundaria para el lanzamiento de juegos.
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
     * URL de origen para las solicitudes.
     *
     * @var string
     */
    private $Origin;

    /**
     * URL de origen para el entorno de desarrollo.
     *
     * @var string
     */
    private $OriginDEV = "https://devfrontend.virtualsoft.tech/doradobetv4/new-casino";

    /**
     * URL de origen para el entorno de producción.
     *
     * @var string
     */
    private $OriginPROD = "https://casino.virtualsoft.tech/";

    /**
     * Cliente SOAP utilizado para las solicitudes.
     *
     * @var SoapClient
     */
    private $soap;

    /**
     * Versión de la API utilizada.
     *
     * @var string
     */
    private $version;


    /**
     * Constructor de la clase.
     *
     * Configura las credenciales y URLs según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->api_login = $this->api_loginDEV;
            $this->api_password = $this->api_passwordDEV;
            $this->URL = $this->URLDEV;
            $this->URLSERVICES = $this->URLSERVICESDEV;

            $this->version = '2';

            $this->URLMINI = $this->URLMINIDEV;
            $this->Origin = $this->OriginDEV;
        } else {
            $this->Origin = $this->OriginPROD;
            $this->URLSERVICES = $this->URLSERVICESPROD;
        }
    }

    /**
     * Obtiene la lista de juegos disponibles.
     *
     * @param boolean $show_systems Indica si se deben mostrar los sistemas.
     *
     * @return mixed Lista de juegos.
     */
    public function getGameList($show_systems = false)
    {
        $this->method = "GameList";
        return $this->soap->getGameList();
    }

    /**
     * Obtiene el proveedor de un juego específico.
     *
     * @param string $gameid   ID del juego.
     * @param string $language Idioma del juego.
     *
     * @return mixed Proveedor del juego.
     */
    public function getGameProvider($gameid, $language)
    {
        return ($this->soap->getGame($gameid, $language));
    }

    /**
     * Configura la lista de juegos en la base de datos.
     *
     * @param boolean $show_systems Indica si se deben mostrar los sistemas.
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
                $Proveedor = new Proveedor("", "PLAYNGO");
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
     * @return mixed Respuesta de la creación del jugador.
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
     * Verifica si un jugador ya existe en el sistema.
     *
     * @param string $username Nombre de usuario.
     *
     * @return boolean `true` si el jugador existe, `false` en caso contrario.
     */
    public function playerExists($username)
    {
        $this->method = "playerExists";

        $array = array(
            "user_username" => "" . $username . ""
        );

        $response = $this->PLAYNGORequest($array);

        if ($response->response == false) {
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
     * @return mixed Respuesta del inicio de sesión.
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
     * Obtiene la URL para jugar un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma.
     * @param boolean $play_for_fun  Indica si es modo de práctica.
     * @param string  $usuarioToken  Token del usuario.
     * @param integer $productoId    ID del producto.
     * @param boolean $isMobile      Indica si es para móvil.
     * @param string  $usumandanteId ID del usuario mandante.
     *
     * @return mixed URL del juego.
     * @throws Exception Si ocurre un error al obtener la URL del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
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
                $Proveedor = new Proveedor("", "PLAYNGO");
                $Producto = new Producto($productoId);
                $ProductoDetalle = new ProductoDetalle('', $Producto->productoId, 'GAMEID');

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $UsuarioMandante = new UsuarioMandante($usumandanteId);

                $this->version = "2";

                try {
                    if ($this->version == "2") {
                        $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId, "", "", $productoId);
                    } else {
                        $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                    }

                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
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

                        if ($this->version == "2") {
                            $UsuarioToken->setProductoId($productoId);
                        }

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                        $UsuarioTokenMySqlDAO->getTransaction()->commit();

                    } else {
                        throw $e;
                    }
                }

                if ($lang == "en") {
                    $lang = "en_BG";
                } elseif ($lang == "pt") {
                    $lang = "pt_BR";
                } else {
                    $lang = "es_ES";
                }

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
                }

                try {
                    $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                    $credentials = json_decode($SubproveedorMandantePais->getCredentials());
                } catch (Exception $e) {}

                $channel = $isMobile ? "mobile" : "desktop";
                $requestUrl = "/casino/ContainerLauncher?pid=" . $credentials->PID .
                                "&gid=" . $ProductoDetalle->pValue . 
                                "&lang=" . $lang . 
                                "&ticket=" . $UsuarioToken->getToken() . 
                                "&practice=0" . 
                                "&channel=" . $channel . 
                                "&origin=" . $this->URLREDIRECTION;

                $url = $this->URL . $requestUrl;    
                $array = array(
                    "error" => false,
                    "response" => array(
                        "URL" => $url, 
                        "proveedor" => "PLAYNGO",
                        "requestUrl" => $requestUrl,
                        "urlBase" => $this->URL
                    )
                );
                    
                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {}
    }

    /**
     * Obtiene la URL para jugar un juego en mini.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma.
     * @param boolean $play_for_fun  Indica si es modo de práctica.
     * @param string  $usuarioToken  Token del usuario.
     * @param integer $productoId    ID del producto.
     * @param boolean $isMobile      Indica si es para móvil.
     * @param string  $usumandanteId ID del usuario mandante.
     * @param string  $partner       Nombre del socio.
     *
     * @return mixed URL del juego en mini.
     */
    public function getGameMini($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "", $partner = "")
    {
        if (strtolower($lang) == "en") {
            $lang = "en_BG";
        } else {
            $lang = "es_ES";
        }


        $Proveedor = new Proveedor("", "PLAYNGO");

        if ($play_for_fun) {
            $Mandante = new Mandante($partner);
        } else {
            $UsuarioMandante = new UsuarioMandante($usumandanteId);
            $Mandante = new Mandante($UsuarioMandante->mandante);
        }

        try {
            $Producto = new Producto($productoId);
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        } catch (Exception $e) {
        }

        if ($Mandante->baseUrl != '') {
            $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
        }

        try {
            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => array(
                        "url" => $this->URLMINI . "/casino/minigamelobby?pid=" . $Credentials->PID . "&practice=0" . "&lang=" . strtolower($lang) . "&origin=" . $this->Origin,
                    )
                );

                return json_decode(json_encode($array));
            } else {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                try {
                    if ($this->version == "2") {
                        $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId, "", "", $productoId);
                    } else {
                        $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                    }
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
                        if ($this->version == "2") {
                            $UsuarioToken->setProductoId($productoId);
                        }

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }


                if ($isMobile) {
                    $url = $this->URLMINI . "/casino/minigamelobby?pid=" . $Credentials->PID . "&name=MiniGames&lang=" . ($lang) . "&origin=" . $this->Origin . "&user=" . $UsuarioToken->getToken();
                    //$url =$this->URLMINI. "/casino/ContainerLauncher?language=es_ES&game=".$Producto->descripcion."&pid=570&user=".$Usuario->login."&password=".$Usuario->clave."&practice=0&ctx=&brand=&server=&channel=mini";
                    $array = array(
                        "error" => false,
                        "response" => array(
                            "url" => $url,
                            "token" => $UsuarioToken->getToken()
                        )
                    );
                } else {
                    $url = $this->URLMINI . "/casino/minigamelobby?pid=" . $Credentials->PID . "&name=MiniGames&lang=" . ($lang) . "&origin=" . $this->Origin . "&user=" . $UsuarioToken->getToken();
                    //$url =$this->URLMINI. "/casino/ContainerLauncher?language=es_ES&game=".$Producto->descripcion."&pid=570&user=".$Usuario->login."&password=".$Usuario->clave."&practice=0&ctx=&brand=&server=&channel=mini";
                    $array = array(
                        "error" => false,
                        "response" => array(
                            "url" => $url,
                            "token" => $UsuarioToken->getToken()
                        )
                    );
                }

                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Obtiene la URL para jugar un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma.
     * @param boolean $play_for_fun  Indica si es modo de práctica.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del usuario mandante.
     *
     * @return mixed URL del juego.
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

                $Proveedor = new Proveedor("", "PLAYNGO");

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
     * Obtiene la URL para jugar un juego específico en el entorno de producción.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma.
     * @param boolean $play_for_fun  Indica si es modo de práctica.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del usuario mandante.
     *
     * @return mixed URL del juego en producción.
     */
    public function getGame2($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "PLAYNGOPOKER");

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
     * Agrega ofertas de juegos gratuitos.
     *
     * @param string  $extBonusId     ID del bono externo.
     * @param integer $rounds         Número de rondas.
     * @param float   $amount         Monto.
     * @param string  $expirationDate Fecha de expiración.
     * @param string  $user           ID del usuario.
     * @param array   $games          Lista de juegos.
     * @param string  $userbonoId     ID del bono del usuario.
     * @param string  $TemplateCode   Código de la plantilla.
     *
     * @return array Resultado de la operación.
     */
    public function AddFreegameOffers($extBonusId, $rounds, $amount, $expirationDate, $user, $games, $userbonoId, $TemplateCode)
    {
        Header('Content-type: text/xml');
        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $user, $Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $url_free = 'https://lamapistage.playngonetwork.com/CasinoGameTPService';
            $SOAPAction = 'http://playngo.com/v1/CasinoGameTPService/AddFreegameOffers';
            if ($UsuarioMandante->paisId == 173) {
                $Key = 'yvlapi:pUTWXRHPBndtamyaIKNlUtmrm';
            } else {
                $Key = 'xebapi:metUNQZdmnNxQQoUyDAlIMits';
            }
            $autch = base64_encode($Key);
        } else {
            $url_free = 'https://lamapi.playngonetwork.com/CasinoGameTPService';
            $SOAPAction = 'http://playngo.com/v1/CasinoGameTPService/AddFreegameOffers';
            if ($UsuarioMandante->paisId == 173) {
                $Key = 'yvlapi:vgAbKZCAQqhoSYQOnGBqVBqfv';
            } else {
                $Key = 'xebapi:GcNaRbxqoHziiZmYGcdMZJgcK';
            }
            $autch = base64_encode($Key);
        }

        $game = '';
        foreach ($games as $valor) {
            $game .= '<arr:int>' . $valor . '</arr:int>';
        }


        $user = $UsuarioMandante->usumandanteId;

        $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $expirationDate);
        $fechaExpire = $fecha->format('Y-m-d\TH:i:s');

        if ($amount <= 5) {
            $amounts = $amount;
        } else {
            $amounts = 1;
        }

        $data = '<?xml version="1.0" encoding="utf-8"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v1="http://playngo.com/v1" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays">
            <soapenv:Header />
            <soapenv:Body>
                <v1:AddFreegameOffers>
                    <v1:UserId>' . $user . '</v1:UserId>
                    <v1:Rounds>' . $rounds . '</v1:Rounds>
                    <v1:ExpireTime>' . $fechaExpire . '</v1:ExpireTime>
                    <v1:FreegameExternalId>' . $extBonusId . '</v1:FreegameExternalId>
                    <v1:RequestId>' . $userbonoId . '</v1:RequestId>
                    <v1:TriggerId>' . $TemplateCode . '</v1:TriggerId>
                    <v1:GameIdList>
                        ' . $game . '
                    </v1:GameIdList>
                </v1:AddFreegameOffers>
            </soapenv:Body>
        </soapenv:Envelope>';
        if ($_ENV['debug']) {
            print_r(' CUMPLE FUNCION662 FINALENTROO ');
            print_r(preg_replace("/\r|\n/", "", $data));
        }
        syslog(LOG_WARNING, "PLAYNGOSERVICES BONO DATA: " . preg_replace("/\r|\n/", "", $data));

        $response = $this->connection($data, $url_free, $autch, $SOAPAction);
        syslog(LOG_WARNING, "PLAYNGOSERVICES BONO RESPONSE: " . ($response));

        $freegameExternalId = "";
        try {
            $xml = simplexml_load_string($response);
            if ($xml === false) {
                throw new Exception("El XML no es válido o está mal formado.");
            }
            $namespaces = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('s', $namespaces['s']);
            $xml->registerXPathNamespace('ns', $namespaces[null]);

            $freegameExternalId = (string)$xml->xpath('//ns:AddFreegameOffersResult/ns:FreegameExternalId')[0];
        } catch (Exception $e) {
            $freegameExternalId = "";
        }

        if ($freegameExternalId == "") {
            $return = array(
                "code" => 1,
                "response_code" => $freegameExternalId,
                "response_message" => 'ERROR'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $freegameExternalId,
                "response_message" => 'OK'
            );
        }
        return $return;
    }

    /**
     * Realiza una conexión SOAP.
     *
     * @param string $data       Datos a enviar.
     * @param string $url_free   URL del servicio.
     * @param string $autch      Autenticación.
     * @param string $SOAPAction Acción SOAP.
     *
     * @return mixed Respuesta del servicio.
     */
    public function connection($data, $url_free, $autch, $SOAPAction)
    {
        Header('Content-type: text/xml');

        $curl = new CurlWrapper($url_free);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url_free,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml; charset=utf-8',
                'SOAPAction: ' . $SOAPAction,
                'Authorization: Basic ' . $autch
            ),
        ));

        $response = $curl->execute();
        return $response;
    }

    /**
     * Verifica si un usuario existe.
     *
     * @param integer $userid ID del usuario.
     *
     * @return mixed Respuesta de la verificación.
     */
    public function checkUser($userid)
    {
        $string = "?client=Doradobet&op=check&usr=User" . $userid;
        $this->productname = "dbg";
        $return = $this->PLAYNGORequest2($string);
        return (($return));
    }

    /**
     * Inserta un nuevo usuario.
     *
     * @param integer $userid ID del usuario.
     *
     * @return mixed Respuesta de la inserción.
     */
    public function getGamelist2($userid)
    {
        $string = "?client=Doradobet&op=gamelist&usr=User" . $userid;
        $this->productname = "dbg";
        $return = $this->PLAYNGORequest2($string);
        return (json_decode($return));
    }

    /**
     * Obtiene un token de autenticación para la API de Play'n GO.
     *
     * Este método realiza una solicitud para obtener un token de acceso utilizando
     * las credenciales configuradas en la clase.
     *
     * @return string Token de acceso obtenido de la API.
     */
    public function getTokenQT()
    {
        $string = "auth/token?grant_type=password&response_type=token&" . "username=" . $this->api_login . "&password=" . $this->api_password;
        $return = $this->PLAYNGORequest($string, array("grant_type" => "password", "response_type" => "token"));

        return json_decode($return)->access_token;
    }

    /**
     * Realiza una solicitud a la API de PLAYNGO.
     *
     * @param string $text Texto de la solicitud.
     *
     * @return mixed Respuesta de la API.
     */
    public function PLAYNGORequest2($text)
    {
        $curl = new CurlWrapper($this->URL2 . $this->productname . "/Main.ashx" . $text);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $this->URL2 . $this->productname . "/Main.ashx" . $text,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_USERAGENT,
            $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();
        return $response;
    }

    /**
     * Realiza una solicitud a la API de PLAYNGO.
     *
     * @param string $string    Texto de la solicitud.
     * @param array  $array_tmp Datos adicionales.
     * @param array  $header    Encabezados adicionales.
     *
     * @return mixed Respuesta de la API.
     */
    public function PLAYNGORequest($string, $array_tmp, $header = array())
    {
        $data = array(
            "username" => "'" . $this->api_login . "'",
            "password" => "" . $this->api_password . ""
        );

        $data = array_merge($data, $array_tmp);
        $data = json_encode($data);

        $curl = new CurlWrapper($this->URL . $string);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $this->URL . $string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_USERAGENT,
            $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = $curl->execute();
        return $response;
    }

    /**
     * Genera una clave para el jugador.
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


/**
 * Clase `PLAYNGOUSER`
 *
 * Representa un usuario en el sistema de Play'n GO.
 * Proporciona métodos para gestionar la información del usuario y realizar operaciones relacionadas.
 */
class PLAYNGOUSER
{
    /**
     * Identificador único del usuario.
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
     * Instancia de la clase `PLAYNGO` para interactuar con los servicios de Play'n GO.
     *
     * @var PLAYNGO
     */
    private $PLAYNGO;


    /**
     * Constructor de la clase.
     *
     * @param integer $user_id ID del usuario.
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;

        $this->PLAYNGO = new PLAYNGO();
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
     * Crea un nuevo jugador en el sistema.
     *
     * @return mixed Respuesta de la creación del jugador.
     */
    public function createPlayer()
    {
        if ($this->playerExists() === false) {
            return $this->PLAYNGO->createPlayer($this->user_id);
        } else {
            return "player already exists";
        }
    }

    /**
     * Inicia sesión para un jugador.
     *
     * @return mixed Respuesta del inicio de sesión.
     */
    public function playerExists()
    {
        return $this->PLAYNGO->playerExists($this->user_name);
    }


}
