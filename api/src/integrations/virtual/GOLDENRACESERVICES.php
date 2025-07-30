<?php

/**
 * Clase que proporciona servicios para interactuar con la API de Golden Race.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */

namespace Backend\integrations\virtual;

include "SwaggerClient-php/autoload.php";


use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Swagger\Client\Api;
use Swagger\Client\Configuration;
use Swagger\Client\Model;
use Swagger\Client\ApiException;


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
 * Clase que proporciona servicios para interactuar con la API de Golden Race.
 *
 * Esta clase incluye métodos para realizar operaciones como autenticación,
 * creación de usuarios, configuración predeterminada, inicio de sesión y
 * obtención de información de juegos.
 */
class GOLDENRACESERVICES
{
    /**
     * URL de la API productiva de Golden Race.
     *
     * @var string
     */
    private $url_GR_productivo = 'http://virtual-api.golden-race.net:8081/api/external/v0.1/';

    /**
     * URL de la API para el entorno de desarrollo.
     *
     * @var string
     */
    private $url_GR_desarrollador = 'http://test-virtual.golden-race.net/api/';

    /**
     * URL de la API para el entorno de desarrollo (versión externa).
     *
     * @var string
     */
    private $url_GR_DEV = 'http://test-virtual.golden-race.net:8081/api/external/v0.1/';

    /**
     * Hash de la API para el entorno de desarrollo.
     *
     * @var string
     */
    private $api_hash_DEV = "f61e72056264107f0487b62013b8fb49";

    /**
     * ID de la API para el entorno de desarrollo.
     *
     * @var string
     */
    private $api_id_DEV = "1274";

    /**
     * Clave de la API para el entorno de desarrollo.
     *
     * @var string
     */
    private $api_key_DEV = "HyeFIr6QnrFAoecVFpml";

    /**
     * URL de la API para el entorno productivo.
     *
     * @var string
     */
    private $url_GR = 'http://virtual-api.golden-race.net:8081/api/external/v0.1/';

    /**
     * Hash de la API para el entorno productivo.
     *
     * @var string
     */
    private $api_hash = "8eef1fc167d3821ff06c4d9f2b572f78";

    /**
     * ID de la API para el entorno productivo.
     *
     * @var string
     */
    private $api_id = "284061";

    /**
     * Clave de la API para el entorno productivo.
     *
     * @var string
     */
    private $api_key = "Jz2cmxmaM7i5oZU3TBzn";

    /**
     * ID del padre para el entorno de desarrollo.
     *
     * @var string
     */
    private $parentIdDEV = "1275";

    /**
     * ID del padre para el entorno productivo.
     *
     * @var string
     */
    private $parentId = "284063";

    /**
     * ID del padre en USD para el entorno de desarrollo.
     *
     * @var string
     */
    private $parentIdUSDDEV = "1276";

    /**
     * ID del padre en USD para el entorno productivo.
     *
     * @var string
     */
    private $parentIdUSD = "1731180";

    /**
     * ID del padre en PEN para el entorno productivo.
     *
     * @var string
     */
    private $parentIdPEN = "1731168";

    /**
     * ID del padre en MXN para el entorno productivo.
     *
     * @var string
     */
    private $parentIdMXN = "1731158";

    /**
     * ID del padre en COP para el entorno productivo.
     *
     * @var string
     */
    private $parentIdCOP = "1731138";

    /**
     * ID del padre en DOP para el entorno productivo.
     *
     * @var string
     */
    private $parentIdDOP = "1731150";

    /**
     * ID del padre en CRC para el entorno productivo.
     *
     * @var string
     */
    private $parentIdCRC = "15702926";

    /**
     * Constructor de la clase GOLDENRACESERVICES.
     *
     * Este constructor inicializa las configuraciones de la API dependiendo del entorno
     * (desarrollo o productivo). Ajusta las URLs, hashes, IDs y claves de la API
     * según el entorno configurado.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->url_GR = $this->url_GR_DEV;
            $this->api_hash = $this->api_hash_DEV;
            $this->api_id = $this->api_id_DEV;
            $this->api_key = $this->api_key_DEV;
            $this->parentId = $this->parentIdDEV;
            $this->parentIdUSD = $this->parentIdUSDDEV;
            $this->parentIdPEN = $this->parentId;
        } else {
        }
    }

    /**
     * Envía una solicitud HTTP POST a una URL específica.
     *
     * @param string $url    La URL a la que se enviará la solicitud.
     * @param string $header El encabezado HTTP que se incluirá en la solicitud.
     * @param array  $data   Los datos que se enviarán en el cuerpo de la solicitud.
     *
     * @return string|false El resultado de la solicitud como una cadena, o false en caso de error.
     */
    function SendPost($url, $header, $data)
    {
        $options = array(
            'http' => array(
                'header' => $header,
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $options2 = array(
            'http' => array(
                'header' => $header
            ),
        );
        $context = stream_context_create($options);
        $context2 = stream_context_create($options2);
        $result = file_get_contents($url, false, $context);


        return $result;
    }

    /**
     * Envía una solicitud HTTP GET a una URL específica.
     *
     * @param string $url    La URL a la que se enviará la solicitud.
     * @param string $header El encabezado HTTP que se incluirá en la solicitud.
     * @param array  $data   Los datos que se enviarán en el cuerpo de la solicitud.
     *
     * @return string|false El resultado de la solicitud como una cadena, o false en caso de error.
     */
    function SendGet($url, $header, $data)
    {
        $options = array(
            'http' => array(
                'header' => $header
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    /**
     * Obtiene el código de error correspondiente a un mensaje de error de la API de Golden Race.
     *
     * @param string $error El mensaje de error recibido de la API.
     *
     * @return integer El código numérico asociado al mensaje de error.
     */
    function GR_Obtener_Codigo_Error($error)
    {
        switch ($error) {
            case "NoError":
                return 0;
                break;
            case "Success":
                return 0;
                break;
            case "UserRegDate":
                return 1;
                break;
            case "ClientNotFound":
                return 2;
                break;
            case "BonusPlanNotFound":
                return 3;
                break;
            case "HasOpenBonus":
                return 4;
                break;
            case "BonusCodeError":
                return 5;
                break;
            case "MinDepositError":
                return 6;
                break;
            case "CountryError":
                return 7;
                break;
            case "PlanAmountLimit":
                return 8;
                break;
            case "PlanCountLimit":
                return 9;
                break;
            case "InternalError":
                return 10;
                break;
            case "ZeroBonus":
                return 11;
                break;
            case "DuplicateUserName":
                return 12;
                break;
            case "UserRejected":
                return 13;
                break;
            case "ProviderError":
                return 14;
                break;
            case "User already exist":
                return 15;
                break;
        }
    }

    /**
     * Obtiene un token de autenticación desde la API de Golden Race.
     *
     * Este metodo realiza una solicitud HTTP GET a la API de Golden Race para autenticar
     * y obtener un token de sesión. Los parámetros necesarios para la autenticación
     * incluyen el ID de la API, el hash y la clave.
     *
     * @return array Un arreglo asociativo que contiene:
     *               - 'error': Código de error (entero).
     *               - 'error_message': Mensaje de error (cadena).
     *               - 'token': Token de sesión obtenido (cadena).
     * @global string $api_hash El hash de autenticación de la API.
     * @global string $api_id   El ID de la API.
     * @global string $api_key  La clave de la API.
     *
     * @global string $url_GR   La URL base de la API de Golden Race.
     */
    function GR_Obtener_Token()
    {
        global $url_GR;
        global $api_hash;
        global $api_id;
        global $api_key;

        $wsdl = $url_GR;
        $xml_array = array();
        $xml_array['method'] = 'auth';
        $xml_array['uid'] = $api_id;
        $xml_array['hash'] = $api_hash;
        $xml_array['key'] = $api_key;

        $data = $xml_array;
        $header = "Content-Type: text/xml";
        $result = SendGet(
            $url_GR . "?method=auth&uid=" . $api_id . "&hash=" . $api_hash . "&key=" . $api_key,
            $header,
            $data
        );

        print_r($result);
        $response = new SimpleXMLElement($result);

        $result = $response->response->result;
        $session_token = (string)$response->response->session_token;
        $error = "";

        if ($result == "success") {
            $error = "NoError";
        }


        $data = [
            'error' => GR_Obtener_Codigo_Error($error),
            'error_message' => $error,
            'token' => $session_token
        ];

        return $data;
    }

    /**
     * Crea un nuevo usuario en la API de Golden Race.
     *
     * Este metodo realiza una solicitud HTTP para crear una nueva entidad de tipo usuario
     * en el sistema de Golden Race. Los parámetros necesarios incluyen un token de autenticación,
     * el ID del cliente y el nombre del cliente.
     *
     * @param string  $token          Token de autenticación proporcionado por la API.
     * @param integer $cliente_id     ID del cliente bajo el cual se creará el usuario.
     * @param string  $cliente_nombre Nombre del usuario que se creará.
     *
     * @return array Un arreglo asociativo que contiene:
     *               - 'error': Código de error (entero).
     *               - 'error_message': Mensaje de error (cadena).
     *               - 'usuario_id': ID del cliente donde se asignó el usuario.
     *               - 'externo_id': ID del nuevo usuario creado en el sistema de Golden Race.
     *               - 'externo_nombre': Nombre del nuevo usuario.
     *               - 'externo_token': Token asociado al nuevo usuario.
     */
    function GR_Crear_Usuario($token, $cliente_id, $cliente_nombre)
    {
        global $url_GR;
        $wsdl = $url_GR;

        $xml_array = array();
        $xml_array['method'] = 'admin::add_entity';
        $xml_array['token'] = $token;
        $xml_array['target_id'] = $cliente_id;
        $xml_array['entity_type'] = 16;
        $xml_array['entity_name'] = $cliente_nombre;


        $data = $xml_array;
        $header = "Content-Type: text/xml";
        $result = SendGet(
            $url_GR . "v2.php?method=admin::add_entity&token=" . $token . "&target_id=" . $cliente_id . "&entity_type=16&entity_name=" . $cliente_nombre,
            $header,
            $data
        );

        print_r($result);

        $response = new SimpleXMLElement($result);

        $result = $response->response->result;
        $target_id = (string)$response->response->target_id;
        $new_entity_id = (string)$response->response->new_entity_id;
        $new_entity_name = (string)$response->response->new_entity_name;
        $new_hardware_id = (string)$response->response->new_hardware_id;
        $error = "";

        if ($result == "success") {
            $error = "NoError";
        }


        $data = [
            'error' => GR_Obtener_Codigo_Error($error),
            'error_message' => $error,
            'usuario_id' => $target_id,
            'externo_id' => $new_entity_id,
            'externo_nombre' => $new_entity_name,
            'externo_token' => $new_hardware_id
        ];

        return $data;
    }

    /**
     * Agrega una configuración predeterminada a un cliente en la API de Golden Race.
     *
     * Este metodo realiza una solicitud HTTP para aplicar una configuración predeterminada
     * a una unidad específica en el sistema de Golden Race. Utiliza un token de autenticación
     * y el ID del cliente como parámetros principales.
     *
     * @param string  $token      Token de autenticación proporcionado por la API.
     * @param integer $cliente_id ID del cliente al que se aplicará la configuración.
     *
     * @return array Un arreglo asociativo que contiene:
     *               - 'error': Código de error (entero).
     *               - 'error_message': Mensaje de error (cadena).
     *               - 'usuario_id': ID del cliente donde se aplicó la configuración.
     */
    function GR_Agregar_Configuracion_Default($token, $cliente_id)
    {
        global $url_GR;
        $wsdl = $url_GR;

        $xml_array = array();
        $xml_array['method'] = 'admin::set_configurator';
        $xml_array['token'] = $token;
        $xml_array['target_id'] = $cliente_id;
        $xml_array['source_id'] = 53;
        $xml_array['target_section'] = 'all';


        $data = $xml_array;
        $header = "Content-Type: text/xml";
        $result = SendGet(
            $url_GR . "v2?method=admin::set_configurator&token=" . $token . "&target_id=" . $cliente_id . "&source_id=1272&target_section='all'",
            $header,
            $data
        );

        $response = new SimpleXMLElement($result);

        $result = (string)$response->response->result;
        $target_id = (string)$response->response->target_id;
        $source_id = $response->response->source_id;
        $target_section = $response->response->target_section;
        $error = "";

        if ($result == "success") {
            $error = "NoError";
        }

        $data = [
            'error' => GR_Obtener_Codigo_Error($error),
            'error_message' => $error,
            'usuario_id' => $target_id
        ];

        return $data;
    }

    /**
     * Inicia sesión en la API de Golden Race.
     *
     * Este metodo realiza una solicitud HTTP para establecer un hash de sesión
     * para un cliente específico en el sistema de Golden Race. Utiliza un token
     * de autenticación, el ID del cliente y un hash de PIN como parámetros.
     *
     * @param string  $token      Token de autenticación proporcionado por la API.
     * @param integer $cliente_id ID del cliente para el cual se establecerá el hash de sesión.
     * @param string  $pin_hash   Hash MD5 del PIN del cliente.
     *
     * @return array Un arreglo asociativo que contiene:
     *               - 'error': Código de error (entero).
     *               - 'nuevo_pin': Nuevo hash del PIN generado (cadena).
     *               - 'usuario_id': ID del cliente (entero).
     *               - 'error_message': Mensaje de error (cadena).
     */
    function GR_Iniciar_Sesion($token, $cliente_id, $pin_hash)
    {
        global $url_GR;
        $wsdl = $url_GR;

        $xml_array = array();
        $xml_array['method'] = 'admin::set_staff_hash';
        $xml_array['token'] = $token;
        $xml_array['target_id'] = $cliente_id;
        $xml_array['pin_hash'] = $pin_hash;


        $data = $xml_array;
        $header = "Content-Type: text/xml";
        $result = SendGet(
            $url_GR . "v2?method=admin::set_staff_hash&token=" . $token . "&target_id=" . $cliente_id . "&pin_hash=" . $pin_hash,
            $header,
            $data
        );

        $response = new SimpleXMLElement($result);

        $result = (string)$response->response->result;
        $target_id = (string)$response->response->target_id;
        $new_pin_hash = (string)$response->response->new_pin_hash;
        $error = "";

        if ($result == "success") {
            $error = "NoError";
        }


        $data = [
            'error' => GR_Obtener_Codigo_Error($error),
            'nuevo_pin' => $new_pin_hash,
            'usuario_id' => $target_id,
            'error_message' => $error
        ];

        return $data;
    }

    /**
     * Obtiene la lista de juegos disponibles.
     *
     * Este metodo establece el metodo interno como "getGameList" y puede incluir
     * sistemas adicionales si se especifica.
     *
     * @param bool $show_systems Indica si se deben mostrar sistemas adicionales. Por defecto es false.
     *
     * @return void
     */
    public function getGameList($show_systems = false)
    {
        $this->method = "getGameList";
    }

    /**
     * Obtiene información de un juego específico.
     *
     * Este metodo establece el metodo interno como "getGame" y puede incluir
     * sistemas adicionales si se especifica.
     *
     * @param string  $gameid        ID del juego a obtener.
     * @param string  $lang          Idioma en el que se desea la información.
     * @param boolean $play_for_fun  Indica si se debe jugar por diversión. Por defecto es false.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del mandante del usuario.
     *
     * @return object Un objeto JSON con la información del juego solicitado.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {
        try {
            if ($usuarioToken != "" || $usumandanteId != "") {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "GDR");

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

                $this->parentId = "";

                switch ($UsuarioMandante->getMoneda()) {
                    case "USD":
                        $this->parentId = $this->parentIdUSD;

                        break;
                    case "PEN":
                        $this->parentId = $this->parentIdPEN;

                        break;
                    case "COP":
                        $this->parentId = $this->parentIdCOP;

                        break;
                    case "DOP":
                        $this->parentId = $this->parentIdDOP;

                        break;
                    case "MXN":
                        $this->parentId = $this->parentIdMXN;

                    case "CRC":
                        $this->parentId = $this->parentIdCRC;

                        break;
                }

                if ($this->parentId == "") {
                    throw new Exception("Moneda no valida para Goldenrace", "01");
                }
                $user_id = "Usuario" . $UsuarioToken->getUsuarioId();


                $config = Configuration::getDefaultConfiguration();
                $config->setApiKey('apiId', $this->api_id);

                $config->setApiKey('apiHash', $this->api_hash);
                $config->setHost($this->url_GR);

                $parentId = $this->parentId;
                $api = new Api\EntityApi();

                try {
                    $grEntities = $api->entityFind(
                        $parentId,
                        $user_id,
                        $UsuarioToken->getUsuarioId()
                    );


                    if ( ! $grEntities) {
                        $entityAdd = $api->entityAdd($parentId, $user_id, $UsuarioToken->getUsuarioId(), []);
                        $grEntities = $api->entityFind(
                            $parentId,
                            $user_id,
                            $UsuarioToken->getUsuarioId()
                        );

                        foreach ($grEntities as $entity) {
                            $api2 = new Api\SessionApi();
                            $startSession = $api2->sessionExternalLogin($entity->getId(), $entity->getId(), []);
                            $loginHash = $startSession->getOnlineHash();

                            $UsuarioToken->usuarioProveedor = $startSession->getUnit()->getId();


                            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                            $UsuarioTokenMySqlDAO->update($UsuarioToken);

                            $UsuarioTokenMySqlDAO->getTransaction()->commit();
                        }
                    } else {
                        foreach ($grEntities as $entity) {
                            $api2 = new Api\SessionApi();

                            $startSession = $api2->sessionExternalLogin($entity->getId(), $entity->getId(), []);
                            $loginHash = $startSession->getOnlineHash();

                            $UsuarioToken->usuarioProveedor = $startSession->getUnit()->getId();


                            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                            $UsuarioTokenMySqlDAO->update($UsuarioToken);

                            $UsuarioTokenMySqlDAO->getTransaction()->commit();
                        }
                    }
                } catch (Exception $e) {
                }
            } else {
                $play_for_fun = true;
            }

            $array = array(
                "error" => false,
                "response" => array(
                    "lang" => "" . $lang . "",
                    "loginHash" => "" . $loginHash . "",
                    "play_for_fun" => "" . $play_for_fun . ""
                )
            );


            return json_decode(json_encode($array));
        } catch
        (Exception $e) {
        }
    }

    /**
     * Genera una clave única para un jugador.
     *
     * Este metodo utiliza el nombre del jugador y lo combina con un hash MD5
     * para crear una clave única de 12 caracteres.
     *
     * @param string $player Nombre del jugador.
     *
     * @return string La clave generada.
     */
    function generateKey($player)
    {
        $hash = md5($player . md5("TMP" . $player));
        $hash = substr($hash, 0, 12);
        return ($hash);
    }

}

