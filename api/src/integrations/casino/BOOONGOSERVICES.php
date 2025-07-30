<?php

/**
 * Clase para integrar servicios de Booongo en la API.
 *
 * Este archivo contiene la implementación de la clase `BOOONGOSERVICES`,
 * que permite gestionar la integración con los servicios de Booongo,
 * incluyendo la configuración de entornos, manejo de juegos y solicitudes API.
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
 * Clase principal para la integración de servicios de Booongo.
 *
 * Esta clase proporciona métodos para interactuar con la API de Booongo,
 * gestionar juegos, realizar solicitudes y manejar configuraciones de entorno.
 */
class BOOONGOSERVICES
{
    /**
     * URL de la API en el entorno de desarrollo.
     *
     * @var string
     */
    private $URL_API_DEV = "https://box1-stage.betsrv.com/";

    /**
     * Método de la API en el entorno de desarrollo.
     *
     * @var string
     */
    private $METHOD_DEV = "";

    /**
     * Nombre del proyecto actual.
     *
     * @var string
     */
    private $PROJECT_NAME = "";

    /**
     * Nombre del proyecto en el entorno de desarrollo.
     *
     * @var string
     */
    private $PROJECT_NAMEDEV = "virtualsoft-stage";

    /**
     * Nombre del proyecto en el entorno de producción.
     *
     * @var string
     */
    private $PROJECT_NAMEPROD = "virtualsoft";

    /**
     * Identificador del entorno WL (White Label).
     *
     * @var string
     */
    private $WL = "prod";

    /**
     * Token de la API actual.
     *
     * @var string
     */
    private $API_TOKEN = "";

    /**
     * Token de la API en el entorno de desarrollo.
     *
     * @var string
     */
    private $API_TOKENDEV = "EWHptTCUgEdizwk1eK2M6mcnE";

    /**
     * Token de la API en el entorno de producción.
     *
     * @var string
     */
    private $API_TOKENPROD = "78VEP743ToInzR4q4EKeBGYNq";

    /**
     * Nombre de usuario de la API en el entorno de desarrollo.
     *
     * @var string
     */
    private $USERNAME_API_DEV = "";

    /**
     * Contraseña de la API en el entorno de desarrollo.
     *
     * @var string
     */
    private $PASSWORD_API_DEV = "";

    /**
     * URL de redirección en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLREDIRECTION_DEV = 'https://doradobet.com/new-casino';

    /**
     * URL de la API en el entorno de producción.
     *
     * @var string
     */
    private $URL_API_PROD = "https://box5.betsrv.com/";

    /**
     * Método de la API en el entorno de producción.
     *
     * @var string
     */
    private $METHOD_PROD = "";

    /**
     * Nombre de usuario de la API en el entorno de producción.
     *
     * @var string
     */
    private $USERNAME_API_PROD = "dorado_pd";

    /**
     * Contraseña de la API en el entorno de producción.
     *
     * @var string
     */
    private $PASSWORD_API_PROD = 'KF3#$Rfh38yfe39r3';

    /**
     * URL de redirección en el entorno de producción.
     *
     * @var string
     */
    private $URLREDIRECTION_PROD = 'https://doradobet.com/new-casino';

    /**
     * URL de la API actual.
     *
     * @var string
     */
    private $URL_API = "";

    /**
     * Método de la API actual.
     *
     * @var string
     */
    private $METHOD = "";

    /**
     * Nombre de usuario de la API actual.
     *
     * @var string
     */
    private $USERNAME_API = "";

    /**
     * Contraseña de la API actual.
     *
     * @var string
     */
    private $PASSWORD_API = "";

    /**
     * URL de redirección actual.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * Indica si el entorno actual es de desarrollo.
     *
     * @var boolean
     */
    private $is_dev = false;

    /**
     * Constructor de la clase BOOONGOSERVICES.
     *
     * Configura las variables de entorno dependiendo de si el entorno es de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL_API = $this->URL_API_DEV;
            $this->METHOD = $this->METHOD_DEV;
            $this->PROJECT_NAME = $this->PROJECT_NAMEDEV;
            $this->API_TOKEN = $this->API_TOKENDEV;
            $this->USERNAME_API = $this->USERNAME_API_DEV;
            $this->PASSWORD_API = $this->PASSWORD_API_DEV;
            $this->URLREDIRECTION = $this->URLREDIRECTION_DEV;
        } else {
            $this->URL_API = $this->URL_API_PROD;
            $this->METHOD = $this->METHOD_PROD;
            $this->PROJECT_NAME = $this->PROJECT_NAMEPROD;
            $this->API_TOKEN = $this->API_TOKENPROD;
            $this->USERNAME_API = $this->USERNAME_API_PROD;
            $this->PASSWORD_API = $this->PASSWORD_API_PROD;
            $this->URLREDIRECTION = $this->URLREDIRECTION_PROD;
        }
    }

    /**
     * Registra una lista de juegos en la base de datos.
     *
     * @param object $data Datos de los juegos proporcionados por la API externa.
     *
     * @return void
     */
    public function setGameList($data)
    {
        $response = $data;

        $error = false;
        $games = $response->applications;

        if ( ! $error) {
            $ProductoMySqlDAO = new ProductoMySqlDAO();
            $Transaction = $ProductoMySqlDAO->getTransaction();

            try {
                $Proveedor = new Proveedor("", "INB");

                if ($Proveedor != null && $Proveedor != "") {
                    foreach ($games as $clave => $game) {
                        if ($Transaction->isIsconnected()) {
                            $Producto = new Producto();

                            $Producto->setEstado("A");
                            $Producto->setProveedorId($Proveedor->getProveedorId());
                            $Producto->setDescripcion(str_replace("'", "", $game->name[0]->en));
                            $Producto->setImageUrl("http://games.inbetgames.com/static/" . $game->preview);
                            $Producto->setVerifica("I");
                            $Producto->setExternoId($game->source);
                            $Producto->setUsucreaId(0);
                            $Producto->setUsumodifId(0);

                            if ( ! $Producto->existsExternoId()) {
                                $producto_id = $Producto->insert($Transaction);

                                $slug = "";

                                switch ($game->type) {
                                    case "slot":
                                        $slug = "slots";
                                        break;

                                    default:
                                        $slug = "catinbet";
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
                                $ProductoDetalle->setPValue($clave);
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

                                $ProductoDetalle = new ProductoDetalle();
                                $ProductoDetalle->setProductoId($producto_id);
                                $ProductoDetalle->setPKey("IMAGE_BACKGROUND");
                                $ProductoDetalle->setPValue("https://i.pinimg.com/originals/33/92/f9/3392f99c1dd718211711848b811dd8da.jpg");
                                $ProductoDetalle->setUsucreaId(0);
                                $ProductoDetalle->setUsumodifId(0);

                                $ProductoDetalle->insert($Transaction);


                                foreach ($game as $key => $val) {
                                    if ($key != "id" && $key != "type") {
                                        $ProductoDetalle = new ProductoDetalle();
                                        $ProductoDetalle->setProductoId($producto_id);
                                        print_r($key);
                                        $ProductoDetalle->setPKey(strtoupper($key));

                                        if (is_object($val)) {
                                            $ProductoDetalle->setPValue(str_replace("'", "", json_encode($val)));
                                        } else {
                                            $ProductoDetalle->setPValue(str_replace("'", "", $val));
                                        }

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
     * Obtiene la URL de un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID alternativo del juego (opcional).
     * @param string  $ismobile      Indica si es para móvil (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid = "", $ismobile = '', $usumandanteId = "")
    {
        try {
            $forFun = "";

            try {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "Booongo");

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


                $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());

                $UsuarioToken->setToken($UsuarioToken->createToken());

                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                $UsuarioTokenMySqlDAO->update($UsuarioToken);

                $UsuarioTokenMySqlDAO->getTransaction()->commit();
                $Pais = new Pais($UsuarioMandante->paisId);

                $Mandante = new Mandante($UsuarioMandante->getMandante());

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
                }

                $array = array(
                    "error" => false,
                    "response" => $this->URL_API . $this->PROJECT_NAME . "/static/game.html?wl=" . $this->WL . "&token=" . $UsuarioToken->getToken() . "&game=" . $gameid . "&lang=" . $lang . "&sound=1"
                );


                return json_decode(json_encode($array));
            } catch (Exception $e) {
                //  print_r($e);
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una solicitud a la API externa.
     *
     * @param array $array_tmp Datos adicionales para la solicitud.
     *
     * @return object Respuesta de la API.
     */
    public function Request($array_tmp)
    {
        $data = array(
            "Account" => array(
                "UserName" => "" . $this->USERNAME_API . "",
                "Password" => "" . $this->PASSWORD_API . ""

            )
        );

        $data = array_merge($data, $array_tmp);

        $data = json_encode($data);


        $ch = curl_init($this->URL_API . $this->METHOD);
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

