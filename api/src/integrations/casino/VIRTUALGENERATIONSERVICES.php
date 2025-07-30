<?php

/**
 * Este archivo contiene la implementación de los servicios de integración con Virtual Generation.
 * Proporciona métodos para gestionar juegos, autenticar usuarios y realizar solicitudes a la API de Virtual Generation.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
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
 * Clase que implementa los servicios de integración con Virtual Generation.
 */
class VIRTUALGENERATIONSERVICES
{
    /**
     * Credenciales de inicio de sesión para la API.
     *
     * @var string
     */
    private $api_login = "";

    /**
     * Contraseña de inicio de sesión para la API.
     *
     * @var string
     */
    private $api_password = "";

    /**
     * Método de la API que se está utilizando.
     *
     * @var string
     */
    private $method;

    /**
     * Endpoint de la API que se está utilizando.
     *
     * @var string
     */
    private $endpoint;

    /**
     * Código del comerciante.
     *
     * @var string
     */
    private $merchantcode = "";

    /**
     * Código del comerciante en el entorno de desarrollo.
     *
     * @var string
     */
    private $merchantcodeDEV = "AQUEDOM";

    /**
     * Código del comerciante en el entorno de producción.
     *
     * @var string
     */
    private $merchantcodePROD = "AQUEDOM";

    /**
     * Palabra de seguridad del comerciante.
     *
     * @var string
     */
    private $MerchantSecurityWord = "";

    /**
     * Palabra de seguridad del comerciante en el entorno de desarrollo.
     *
     * @var string
     */
    private $MerchantSecurityWordDEV = "C#oB0|4YtAmh";

    /**
     * Palabra de seguridad del comerciante en el entorno de producción.
     *
     * @var string
     */
    private $MerchantSecurityWordPROD = "WpHYTSWKt.ly";

    /**
     * URL base de la API en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://qacom-apisecapuestasdominicanas.ody-services.net';

    /**
     * URL base de la API en el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://vg-apisecapuestasdominicanas.odissea-services.net';

    /**
     * URL alternativa de la API.
     *
     * @var string
     */
    private $URL2 = 'https://apisecapuestasdominicanas.vg-services.net:14443';

    /**
     * URL base de la API que se está utilizando.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL de redirección para el casino.
     *
     * @var string
     */
    private $URLREDIRECTION = 'https://doradobet.com/new-casino';

    /**
     * URL del lobby en el entorno de desarrollo.
     *
     * @var string
     */
    private $URL_LOBBYDEV = 'https://virtapuestasdominicana.vg-services.net/?';

    /**
     * URL del lobby en el entorno de producción.
     *
     * @var string
     */
    private $URL_LOBBYPROD = "";

    /**
     * URL alternativa del lobby.
     *
     * @var string
     */
    private $URL_LOBBY2 = 'https://virtapuestasdominicana.vg-services.net/full/?';

    /**
     * URL del lobby que se está utilizando.
     *
     * @var string
     */
    private $URL_LOBBY = 'https://vg-virtapuestasdominicanasfull.odissea-services.net/?';

    /**
     * URL del lobby móvil alternativa.
     *
     * @var string
     */
    private $URL_LOBBYMOBILE2 = 'https://mobvirtapuestasdominicanas.vg-services.net/#/app/home?';

    /**
     * URL del lobby móvil que se está utilizando.
     *
     * @var string
     */
    private $URL_LOBBYMOBILE = 'https://vg-mobvirtapuestasdominicanas.odissea-services.net/?';

    /**
     * Constructor de la clase.
     * Configura las URLs y credenciales según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL_LOBBY = $this->URL_LOBBYDEV;
            $this->URL = $this->URLDEV;
            $this->merchantcode = $this->merchantcodeDEV;
            $this->MerchantSecurityWord = $this->MerchantSecurityWordDEV;
        } else {
            $this->URL_LOBBY = $this->URL_LOBBYPROD;
            $this->URL = $this->URLPROD;
            $this->merchantcode = $this->merchantcodePROD;
            $this->MerchantSecurityWord = $this->MerchantSecurityWordPROD;
        }
    }

    /**
     * Obtiene la lista de juegos desde la API de Virtual Generation.
     *
     * @param boolean $show_systems Indica si se deben mostrar sistemas adicionales.
     *
     * @return mixed Respuesta de la API.
     */
    public function getGameList($show_systems = false)
    {
        $this->method = "getGameList";
        return $this->VIRTUALGENERATIONRequest();
    }

    /**
     * Sincroniza a lista de juegos obtenida desde la API con la base de datos local.
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
                //print_r($e);
                $Transaction->rollback();
            }
        }
    }

    /**
     * Autentica a un usuario en la API de Virtual Generation.
     *
     * @param integer $user_id ID del usuario a autenticar.
     *
     * @return mixed Respuesta de la API.
     */
    public function Auth($user_id)
    {
        $this->endpoint = "/api/webclientauth/v2/login";

        try {
            $UsuarioMandante = new UsuarioMandante($user_id);
            $Proveedor = new Proveedor("", "VGT");

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId(), "");
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
                    $UsuarioToken->setToken($UsuarioToken->createToken());
                    $UsuarioToken->setSaldo(0);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $Mandante = new Mandante($UsuarioMandante->getMandante());
            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                if ($Usuario->paisId == 46) {
                    $array = array(
                        "Username" => "Usuario" . $user_id . "",
                        "FirstName" => "Usuario" . $user_id . "",
                        "LastName" => "",
                        "Town" => "",
                        "State" => "",
                        "Country" => "",
                        "BirthDate" => "",
                        "Gender" => "M",
                        "ZipCode" => "",
                        "SessionId" => $UsuarioToken->getToken(),
                        "CurrencyISoCode" => $UsuarioMandante->getMoneda(),
                        "Balance" => round($Usuario->getBalance(), 2),
                        "LoginType" => 'web',
                        "MerchantCode" => $this->merchantcode,
                        "MerchantSecurityWord" => $this->MerchantSecurityWord,
                        "OperatorUsername" => $UsuarioMandante->nombres,
                        "Products" => "dog,horse,horse-virt,keno,eng-league,ita-league,clrs"
                    );
                } elseif ($Usuario->paisId == 60) {
                    $array = array(
                        "Username" => "Usuario" . $user_id . "",
                        "FirstName" => "Usuario" . $user_id . "",
                        "LastName" => "",
                        "Town" => "",
                        "State" => "",
                        "Country" => "",
                        "BirthDate" => "",
                        "Gender" => "M",
                        "ZipCode" => "",
                        "SessionId" => $UsuarioToken->getToken(),
                        "CurrencyISoCode" => $UsuarioMandante->getMoneda(),
                        "Balance" => round($Usuario->getBalance(), 2),
                        "LoginType" => 'web',
                        "MerchantCode" => $this->merchantcode,
                        "MerchantSecurityWord" => $this->MerchantSecurityWord,
                        "OperatorUsername" => $UsuarioMandante->nombres,
                        "Products" => "dog,horse,horse-virt,keno,eng-league,ita-league,clrs"
                    );
                } elseif ($Usuario->mandante == 16) {
                    $array = array(
                        "Username" => "Usuario" . $user_id . "",
                        "FirstName" => "Usuario" . $user_id . "",
                        "LastName" => "",
                        "Town" => "",
                        "State" => "",
                        "Country" => "",
                        "BirthDate" => "",
                        "Gender" => "M",
                        "ZipCode" => "",
                        "SessionId" => $UsuarioToken->getToken(),
                        "CurrencyISoCode" => $UsuarioMandante->getMoneda(),
                        "Balance" => round($Usuario->getBalance(), 2),
                        "LoginType" => 'web',
                        "MerchantCode" => $this->merchantcode,
                        "MerchantSecurityWord" => $this->MerchantSecurityWord,
                        "OperatorUsername" => $UsuarioMandante->email,
                        "Products" => "dog,cock,keno,eng-league,ita-league,clrs"
                    );
                } else {
                    $array = array(
                        "Username" => "Usuario" . $user_id . "",
                        "FirstName" => "Usuario" . $user_id . "",
                        "LastName" => "",
                        "Town" => "",
                        "State" => "",
                        "Country" => "",
                        "BirthDate" => "",
                        "Gender" => "M",
                        "ZipCode" => "",
                        "SessionId" => $UsuarioToken->getToken(),
                        "CurrencyISoCode" => $UsuarioMandante->getMoneda(),
                        "Balance" => round($Usuario->getBalance(), 2),
                        "LoginType" => 'web',
                        "MerchantCode" => $this->merchantcode,
                        "MerchantSecurityWord" => $this->MerchantSecurityWord,
                        "OperatorUsername" => $UsuarioMandante->nombres

                    );
                }


                $response = $this->VIRTUALGENERATIONRequest($array);

                return $response;
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Verifica si un jugador existe en la API de Virtual Generation.
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

        $response = $this->VIRTUALGENERATIONRequest($array);

        if ($response->response == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Inicia sesión para un jugador en la API de Virtual Generation.
     *
     * @param integer $user_id ID del usuario.
     *
     * @return mixed Respuesta de la API.
     */
    public function loginPlayer($user_id)
    {
        $this->method = "loginPlayer";

        $array = array(
            "user_id" => "",
            "user_username" => "",
            "user_password" => "" . $this->generateKey($user_id) . ""
        );

        $response = $this->VIRTUALGENERATIONRequest($array);

        return $response;
    }

    /**
     * Obtiene la URL para iniciar un juego específico.
     *
     * @param string        $gameid        ID del juego.
     * @param string        $lang          Idioma del juego.
     * @param boolean       $play_for_fun  Indica si el juego es en modo demo.
     * @param string        $usuarioToken  Token del usuario.
     * @param boolean       $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param string        $usumandanteId ID del usuario mandante.
     * @param Mandante|null $Mandante      Objeto Mandante.
     *
     * @return mixed Respuesta con la URL del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $isMobile, $usumandanteId = "", Mandante $Mandante = null)
    {
        try {
            if ($usumandanteId == "" && $gameid == '') {
                $homeUrl = '';
                if ($Mandante != null) {
                    $homeUrl = '&homeURL=' . $Mandante->baseUrl;
                }
                if ( ! $isMobile) {
                    $homeUrl = '';
                }
                $response = array(
                    "error" => false,
                    "response" => 'https://vg-vplayv2apuestasdominicanas.odissea-services.net?demo=true' . $homeUrl
                );
                return json_decode(json_encode($response));

                exit();
            }

            $Auth = $this->Auth($usumandanteId);

            $homeUrl = '&homeURL=' . $this->URLREDIRECTION;
            if ( ! $isMobile) {
                $homeUrl = '';
            }
            if ($usuarioToken == "") {
                if ($isMobile) {
                    //$URLFINAL = $this->URL_LOBBYMOBILE . "token=#&l=es";
                    //$URLFINAL='http://vg-vplaydemovg.odissea-services.net/token=#&l=es';
                    $URLFINAL = $Auth->CallbackUrl . "&client=mobile&language=" . $lang . "" . $homeUrl;
                } else {
                    // $URLFINAL = $this->URL_LOBBY . "l=es#token=#";
                    // $URLFINAL='http://vg-vplaydemovg.odissea-services.net/token=#&l=es';
                    $URLFINAL = $Auth->CallbackUrl . "&client=web&language=" . $lang . "" . $homeUrl;
                }
            } else {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }
                //$UsuarioToken = new UsuarioToken($usuarioToken);


                $UsuarioMandante = new UsuarioMandante($usumandanteId);

                $Mandante = new Mandante($UsuarioMandante->getMandante());

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
                }


                if ($isMobile) {
                    $URLFINAL = $Auth->CallbackUrl . "&client=mobile&language=" . $lang . "" . $homeUrl;
                } else {
                    $URLFINAL = $Auth->CallbackUrl . "&client=web&language=" . $lang . "" . $homeUrl;
                }
            }


            $response = array(
                "error" => false,
                "response" => $URLFINAL
            );


            return json_decode(json_encode($response));
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una solicitud a la API de Virtual Generation.
     *
     * @param array $array_tmp Datos de la solicitud.
     *
     * @return mixed Respuesta de la API.
     */
    public function VIRTUALGENERATIONRequest($array_tmp)
    {
        $data = array();

        $data = array_merge($data, $array_tmp);

        $data = json_encode($data);

        $ch = curl_init($this->URL . $this->endpoint);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        $result = (curl_exec($ch));

        $result = json_decode($result);

        return ($result);
    }

    /**
     * Genera una clave única para un jugador.
     *
     * @param string $player Identificador del jugador.
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
 * Clase que representa un usuario de IGP.
 */
class IGPUSER
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
     * Instancia de la clase IGP para gestionar operaciones relacionadas.
     *
     * @var IGP
     */
    private $IGP;

    /**
     * Constructor de la clase.
     *
     * @param integer $user_id ID del usuario.
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->IGP = new IGP();
    }

    /**
     * Obtiene el identificador único del usuario.
     *
     * @return integer El ID del usuario.
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Establece el identificador único del usuario.
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
     * @return string El nombre del usuario.
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * Establece el nombre del usuario.
     *
     * @param string $user_name El nombre del usuario.
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
     * @param string $user_password La contraseña del usuario.
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
     * @return string Mensaje indicando el resultado de la operación.
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
     * Verifica si un jugador ya existe en el sistema.
     *
     * @return boolean `true` si el jugador existe, `false` en caso contrario.
     */
    public function playerExists()
    {
        return $this->IGP->playerExists($this->user_name);
    }


}
