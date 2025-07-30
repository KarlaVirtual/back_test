<?php

/**
 * Clase EventsOptimove
 *
 * Esta clase maneja eventos y conexiones con el servidor Optimove.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-03-19
 */

namespace Backend\integrations\crm;

use Backend\dto\AuditoriaGeneral;
use Backend\dto\BonoInterno;
use Backend\dto\CategoriaMandante;
use Backend\dto\CategoriaProducto;
use Backend\dto\Ciudad;
use Backend\dto\JackpotInterno;
use Backend\dto\LealtadDetalle;
use Backend\dto\LealtadInterna;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\dto\SubproveedorMandante;
use Backend\dto\UsuariojackpotGanador;
use Backend\dto\UsuarioLealtad;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;
use \Exception;

/**
 * Esta clase maneja eventos y conexiones con el servidor Optimove.
 */
class EventsOptimove
{

    /**
     * Nombre de usuario.
     *
     * @var string
     */
    private $username = "";

    /**
     * Nombre de usuario para el entorno de desarrollo.
     *
     * @var string
     */
    private $usernameDEV = "";

    /**
     * Nombre de usuario para el entorno de producción.
     *
     * @var string
     */
    private $usernamePROD = "";

    /**
     * URL base.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL base para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://server-1058.optimove.net/reportServer';

    /**
     * URL de autenticación para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEVAUTH = "";

    /**
     * URL base para el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = "";

    /**
     * URL de autenticación para el entorno de producción.
     *
     * @var string
     */
    private $URLPRODAUTH = "";

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token = "";

    /**
     * Metodo de conexión.
     *
     * @var string
     */
    private $metodo = "";

    /**
     * URL de callback.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "";

    /**
     * Constructor de la clase EventsOptimove.
     *
     * Inicializa las variables de entorno según el entorno de desarrollo o producción.
     */
    function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->username = $this->usernameDEV;

            $this->callback_url = $this->callback_urlDEV;
            $this->URL = $this->URLDEV;
            $this->URLAUTH = $this->URLDEVAUTH;
        } else {
            $this->username = $this->usernamePROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->URL = $this->URLPROD;
            $this->URLAUTH = $this->URLPRODAUTH;
        }
    }

    /**
     * Maneja el evento de inicio de sesión.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     *
     * @return object Objeto con los campos:
     *                - success (boolean): Indica si la operación fue exitosa.
     *                - response (object): Respuesta del servidor.
     */
    function EventLogin($Usuario, $Server, $Ismobile)
    {
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);
        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        /**
         * Extrae y procesa la información del agente de usuario desde el objeto JSON decodificado.
         */
        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);
                $version = $version[0];

                if ($plaform == '') {
                    $version = $version[0];
                };
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }

        // Arreglo que contiene los datos del evento de inicio de sesión
        $array = array(
            "tenant" => $tenant,
            "event" => "login",
            "context" => array(
                "event_device_type" => $typeDevice, // Tipo de dispositivo del evento
                "event_native_mobile" => false, // Indica si es un dispositivo móvil nativo
                "event_platform" => $plaform, // Plataforma del evento
                "event_os" => $version, // Sistema operativo del evento
                "name" => $Registro->nombre1, // Nombre del usuario
                "phone" => $Registro->celular, // Teléfono del usuario
                "email" => $Registro->email, // Correo electrónico del usuario
                "iduser" => intval($Usuario->usuarioId), // ID del usuario
                "idcasino" => intval($UsuarioMandante->usumandanteId), // ID del casino
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom) // País del usuario
            ),
            "customer" => $Usuario->usuarioId, // ID del cliente
        );

        $this->Auth = $this->Encrypta($data);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * EventRegistros
     *
     * Esta función se utiliza para manejar el evento de registro.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     *
     * @return object            Objeto con los campos:
     *                           - success (boolean): Indica si la operación fue exitosa.
     *                           - response (object): Respuesta del servidor.
     */
    function EventRegistros($Usuario, $Server, $Ismobile)
    {
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        /**
         * Extrae y procesa la información del agente de usuario desde el objeto JSON decodificado.
         */
        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);
                $version = $version[0];

                if ($plaform == '') {
                    $version = $version[0];
                }
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }

        // Arreglo que contiene los datos del evento de inicio de sesión
        $array = array(
            "tenant" => intval($tenant),
            "event" => "register",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre1,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)
            ),
            "customer" => $Usuario->usuarioId,
        );

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Maneja el evento de apuestas deportivas.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     * @param float  $Valor    Valor de la apuesta.
     *
     * @return object Objeto con los campos:
     *                - success (boolean): Indica si la operación fue exitosa.
     *                - response (object): Respuesta del servidor.
     */
    function EventApuestasDeportivas($Usuario, $Server, $Ismobile, $Valor)
    {
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        /**
         * Extrae y procesa la información del agente de usuario desde el objeto JSON decodificado.
         */
        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);
                $version = $version[0];

                if ($plaform == '') {
                    $version = $version[0];
                };
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }

        // Arreglo que contiene los datos del evento de inicio de sesión
        $array = array(
            "tenant" => $tenant,
            "event" => "betsportbook",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre1,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "amount" => floatval($Valor),
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)
            ),
            "customer" => $Usuario->usuarioId,
        );

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Maneja el evento de ganancias deportivas.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     * @param float  $Valor    Valor de la ganancia.
     *
     * @return object Objeto con los campos:
     *                - success (boolean): Indica si la operación fue exitosa.
     *                - response (object): Respuesta del servidor.
     */
    function EventGananciasDeportivas($Usuario, $Server, $Ismobile, $Valor)
    {
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        $useragent = $jsonDeco->HTTP_USER_AGENT;
        $useragent = explode(")", $useragent);
        $useragent = explode(")", $useragent[0]);
        $useragent = explode("(", $useragent[0]);
        $version = explode(";", $useragent[1]);
        $version = $version[0];

        if ($plaform == '') {
            $version = $version[0];
        };

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }

        $array = array(
            "tenant" => $tenant,
            "event" => "winsportbook",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre1,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "amount" => floatval($Valor),
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)
            ),
            "customer" => $Usuario->usuarioId,
        );

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * EventApuestasCasino
     *
     * Esta función se utiliza para manejar el evento de apuestas en el casino.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     * @param float  $Valor    Valor de la apuesta.
     *
     * @return object            Objeto con los campos:
     *                           - success (boolean): Indica si la operación fue exitosa.
     *                           - response (object): Respuesta del servidor.
     */
    function EventApuestasCasino($Usuario, $Server, $Ismobile, $Valor)
    {
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);
                $version = $version[0];

                if ($plaform == '') {
                    $version = $version[0];
                };
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }

        $array = array(
            "tenant" => $tenant,
            "event" => "betcasino",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre1,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "amount" => floatval($Valor),
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)
            ),
            "customer" => $Usuario->usuarioId,
        );

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * EventGananciasCasino
     *
     * Esta función se utiliza para manejar el evento de ganancias en el casino.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     * @param float  $Valor    Valor de la ganancia.
     *
     * @return object            Objeto con los campos:
     *                           - success (boolean): Indica si la operación fue exitosa.
     *                           - response (object): Respuesta del servidor.
     */
    function EventGananciasCasino($Usuario, $Server, $Ismobile, $Valor)
    {
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);
        if ($Mandante->mandante == "0" && $Pais->paisId = 173) {
            $tenant = "1058";
        }

        if ($Mandante->mandante == "8" && $Pais->paisId = 66) {
            $tenant = "";
        }
        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        $useragent = $jsonDeco->HTTP_USER_AGENT;
        $useragent = explode(")", $useragent);
        $useragent = explode(")", $useragent[0]);
        $useragent = explode("(", $useragent[0]);
        $version = explode(";", $useragent[1]);
        $version = $version[0];

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);

                if ($plaform == '') {
                    $plaform = $version[0];
                }
                $version = $version[1];
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }
        $array = array(
            "tenant" => $tenant,
            "event" => "wincasino",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre1,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "amount" => floatval($Valor),
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)
            ),
            "customer" => $Usuario->usuarioId,
        );

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * EventRetiroPagado
     *
     * Esta función se utiliza para manejar el evento de retiro pagado.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     * @param float  $Valor    Valor del retiro.
     *
     * @return object            Objeto con los campos:
     *                           - success (boolean): Indica si la operación fue exitosa.
     *                           - response (object): Respuesta del servidor.
     */
    function EventRetiroPagado($Usuario, $Server, $Ismobile, $Valor)
    {
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);

                if ($plaform == '') {
                    $plaform = $version[0];
                }
                $version = $version[1];
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }
        $array = array(
            "tenant" => $tenant,
            "event" => "withdrawalpaid",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre1,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "amount" => floatval($Valor),
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)
            ),
            "customer" => $Usuario->usuarioId,
        );

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * EventRetiroCreado
     *
     * Esta función se utiliza para manejar el evento de retiro creado.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     * @param float  $Valor    Valor del retiro.
     *
     * @return object            Objeto con los campos:
     *                           - success (boolean): Indica si la operación fue exitosa.
     *                           - response (object): Respuesta del servidor.
     */
    function EventRetiroCreado($Usuario, $Server, $Ismobile, $Valor)
    {
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);

                if ($plaform == '') {
                    $plaform = $version[0];
                }
                $version = $version[1];
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }
        $array = array(
            "tenant" => $tenant,
            "event" => "withdrawalcreated",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre1,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "amount" => floatval($Valor),
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)
            ),
            "customer" => $Usuario->usuarioId,

        );

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * EventRetiroEliminado
     *
     * Esta función se utiliza para manejar el evento de retiro eliminado.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     * @param float  $Valor    Valor del retiro.
     *
     * @return object            Objeto con los campos:
     *                           - success (boolean): Indica si la operación fue exitosa.
     *                           - response (object): Respuesta del servidor.
     */
    function EventRetiroEliminado($Usuario, $Server, $Ismobile, $Valor)
    {
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Mandante = new Mandante($Usuario->mandante);

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);

                if ($plaform == '') {
                    $plaform = $version[0];
                }
                $version = $version[1];
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }
        $array = array(
            "tenant" => $tenant,
            "event" => "withdrawalremoved",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre1,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "amount" => floatval($Valor),
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)

            ),
            "customer" => $Usuario->usuarioId,
        );

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * EventSolicitudDeposito
     *
     * Esta función se utiliza para manejar el evento de solicitud de depósito.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     * @param float  $Valor    Valor del depósito.
     *
     * @return object            Objeto con los campos:
     *                           - success (boolean): Indica si la operación fue exitosa.
     *                           - response (object): Respuesta del servidor.
     */
    function EventSolicitudDeposito($Usuario, $Server, $Ismobile, $Valor)
    {
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);

                if ($plaform == '') {
                    $plaform = $version[0];
                }
                $version = $version[1];
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }
        $array = array(
            "tenant" => $tenant,
            "event" => "depositrequest",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre1,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "amount" => floatval($Valor),
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)
            ),
            "customer" => $Usuario->usuarioId,

        );

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * EventDeposito
     *
     * Esta función se utiliza para manejar el evento de depósito.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     * @param float  $Valor    Valor del depósito.
     *
     * @return object            Objeto con los campos:
     *                           - success (boolean): Indica si la operación fue exitosa.
     *                           - response (object): Respuesta del servidor.
     */
    function EventDeposito($Usuario, $Server, $Ismobile, $Valor)
    {
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);
                $version = $version[0];

                if ($plaform == '') {
                    $version = $version[0];
                }
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }

        $array = array(
            "tenant" => intval($tenant),
            "event" => "deposit",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre1,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "amount" => floatval($Valor),
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)
            ),
            "customer" => $Usuario->usuarioId,
        );

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * EventBono
     *
     * Esta función se utiliza para manejar el evento de bono.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     * @param float  $Valor    Valor del bono.
     *
     * @return object            Objeto con los campos:
     *                           - success (boolean): Indica si la operación fue exitosa.
     *                           - response (object): Respuesta del servidor.
     */
    function EventBono($Usuario, $Server, $Ismobile, $Valor)
    {
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);

                if ($plaform == '') {
                    $plaform = $version[0];
                }
                $version = $version[1];
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }
        $array = array(
            "tenant" => $tenant,
            "event" => "bono",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre1,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "amount" => $Valor,
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)
            ),
            "customer" => $Usuario->usuarioId,
        );

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * EventLealtad
     *
     * Esta función se utiliza para manejar el evento de lealtad.
     *
     * @param object $Usuario      Objeto que contiene la información del usuario.
     * @param string $Server       Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile     Indica si el dispositivo es móvil.
     * @param float  $Valor        Valor de la lealtad.
     * @param int    $IdMovimiento ID del movimiento de lealtad.
     *
     * @return object              Objeto con los campos:
     *                           - success (boolean): Indica si la operación fue exitosa.
     *                           - response (object): Respuesta del servidor.
     */
    public function EventLealtad($Usuario, $Server, $Ismobile, $Valor, $IdMovimiento)
    {
        $Proveedor = new Proveedor("", "Optimove");
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);
        $UsuarioLealtad = new UsuarioLealtad($IdMovimiento);
        $Usuario = new Usuario($Usuario->usuarioId);
        $LealtadInterna = new LealtadInterna($UsuarioLealtad->getLealtadId());
        $lealtadDetalle = new LealtadDetalle(
            "", $LealtadInterna->lealtadId, 'VERTICALREGALO', $UsuarioMandante->moneda
        );
        $sql =
            "SELECT  li.descripcion  as Nombre,
       case
           when ld.valor = 0 then 'Deportiva'
           when ld.valor = 1 then 'Casino'
           else ld.valor end as Vertical,
       ld1.valor             as Puntos
from usuario_lealtad ul
         join lealtad_interna li on li.lealtad_id = ul.lealtad_id
         join (select ld.lealtad_id, ld.tipo, ld.valor
               from lealtad_detalle ld
               where 1 = 1
                 and tipo = 'VERTICALREGALO') ld on ld.lealtad_id = ul.lealtad_id
         join (select ld1.lealtad_id, ld1.tipo, ld1.valor
               from lealtad_detalle ld1
               where 1 = 1
                 and tipo = 'PUNTOS') ld1 on ld1.lealtad_id = ul.lealtad_id
where 1 = 1
  and ul.usulealtad_id = $UsuarioLealtad->usulealtadI
";

        $UsuarioLealtadMySqlDAO = new \Backend\mysql\UsuarioLealtadMySqlDAO();

        $transaccion = $UsuarioLealtadMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();
        $Usuarios = $LealtadInterna->execQuery($transaccion, $sql);

        foreach ($Usuarios as $key => $value) {
            $Nombre = strval($value->{"li.Nombre"});
            $Vertical = strval($value->{".Vertical"});
            $PuntosCompra = strval($value->{"ld1.Puntos"});

            $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

            $tenant = $config['tenant'];
            $this->token = $config['token'];
            $this->URLPROD = $config['URLPROD'];

            $jsonDeco = base64_decode($Server);
            $jsonDeco = json_decode($jsonDeco);
            $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
            $plaform = str_replace('"', "", $plaform);

            try {
                $useragent = $jsonDeco->HTTP_USER_AGENT;
                if ($useragent != '') {
                    $useragent = explode(")", $useragent);
                    $useragent = explode(")", $useragent[0]);
                    $useragent = explode("(", $useragent[0]);
                    $version = explode(";", $useragent[1]);

                    if ($plaform == '') {
                        $plaform = $version[0];
                    }
                    $version = $version[1];
                    //$version=$useragent;
                }
            } catch (Exception $e) {
            }

            if ($Ismobile != "") {
                $typeDevice = "Mobile";
            } else {
                $typeDevice = "Web";
            }
            $array = array(
                "tenant" => $tenant,
                "event" => "loyaltystore",
                "context" => array(
                    "event_device_type" => $typeDevice,
                    "event_native_mobile" => false,
                    "event_platform" => $plaform,
                    "event_os" => $version,
                    "name" => $Registro->nombre1,
                    "phone" => $Registro->celular,
                    "email" => $Registro->email,
                    "iduser" => intval($Usuario->usuarioId),
                    "idcasino" => intval($UsuarioMandante->usumandanteId),
                    "balance" => $Usuario->getBalance(),
                    "purchasedate" => $UsuarioLealtad->fechaCrea,
                    "giftpoints" => $PuntosCompra,
                    "points" => $Usuario->puntosLealtad,
                    "nameaward" => $Nombre,
                    "vertical" => $Vertical,
                    "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)
                ),
                "customer" => $Usuario->usuarioId,
            );

            $this->Auth = $this->Encrypta($array);

            $Response = $this->connectionPOST($array);

            $Response = json_decode($Response);
        }
        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * EventSaldoAjuste
     *
     * Esta función se utiliza para manejar el evento de ajuste de saldo.
     *
     * @param object $Usuario  Objeto que contiene la información del usuario.
     * @param string $Server   Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     *
     * @return object            Objeto con los campos:
     *                           - success (boolean): Indica si la operación fue exitosa.
     *                           - response (object): Respuesta del servidor.
     */
    public function EventSaldoAjuste($Usuario, $Server, $Ismobile)
    {
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);

                if ($plaform == '') {
                    $plaform = $version[0];
                }
                $version = $version[1];
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }
        $array = array(
            "tenant" => $tenant,
            "event" => "balanceadjustment",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre1,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom)
            ),
            "customer" => $Usuario->usuarioId,
        );

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * EventJackpotCaido
     *
     * Esta función se utiliza para manejar el evento de caída de un jackpot.
     *
     * @param object $UsuarioJackpotGanador Objeto que contiene la información del usuario ganador del jackpot.
     * @param string $Server Cadena codificada en base64 que contiene información del servidor.
     * @param string $Ismobile Indica si el dispositivo es móvil.
     * @param object $JackpotInterno Objeto que contiene la información interna del jackpot.
     *
     * @return object       Objeto con los campos:
     *                       - success (boolean): Indica si la operación fue exitosa.
     *                       - response (object): Respuesta del servidor.
     */
    public function EventJackpotCaido($UsuarioJackpotGanador, $Server, $Ismobile, $JackpotInterno)
    {
        // Obtenemos información del usuario ganador y del jackpot caido
        $Proveedor = new Proveedor("", "Optimove");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Usuario = new Usuario($UsuarioJackpotGanador->usuarioId);
        $Mandante = new Mandante($Usuario->mandante);

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        //Solicitando usuarioJackpot ganadores y asignándolos a nombre del ganador
        $rules = [];
        $rules[] = ['field' => 'usuariojackpot_ganador.jackpot_id', 'data' => $JackpotInterno->jackpotId, 'op' => 'eq'];
        $rules[] = ['field' => 'jackpot_detalle.tipo', 'data' => 'TIPOSALDO', 'op' => 'eq']; //Con este filtro se evita duplicidad de registros
        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $select = 'usuariojackpot_ganador.usujackpotganador_id';
        $order = 'usuariojackpot_ganador.usujackpotganador_id';
        $JackpotInterno2 = new JackpotInterno();
        $joins = [];
        $joins[] = (object)['type' => 'INNER', 'table' => 'usuariojackpot_ganador', 'on' => 'jackpot_interno.jackpot_id = usuariojackpot_ganador.jackpot_id'];
        $usuJackpots = $JackpotInterno2->getJackpotCustom($select, $order, 'DESC', 0, 4, json_encode($filters), true, $joins);
        $usuJackpots = json_decode($usuJackpots)->data;

        $verticales = [];

        foreach ($usuJackpots as $usuJackpotGanador) {
            $UsuarioJackpotGanador = new UsuariojackpotGanador($usuJackpotGanador->{'usuariojackpot_ganador.usujackpotganador_id'});

            // Extraer tipo y eliminar el prefijo INCOME_
            $tipo = str_replace('INCOME_', '', $UsuarioJackpotGanador->tipo);

            // Guardar el tipo limpio en el array
            $verticales[] = $tipo;
        }

        // Convertir array en string separado por comas
        $verticales = implode(', ', $verticales);


        $config = $this->getConfig($Mandante->mandante, $Pais->paisId);

        $tenant = $config['tenant'];
        $this->token = $config['token'];
        $this->URLPROD = $config['URLPROD'];

        $jsonDeco = base64_decode($Server);
        $jsonDeco = json_decode($jsonDeco);
        $plaform = strval($jsonDeco->HTTP_SEC_CH_UA_PLATFORM);
        $plaform = str_replace('"', "", $plaform);

        try {
            $useragent = $jsonDeco->HTTP_USER_AGENT;
            if ($useragent != '') {
                $useragent = explode(")", $useragent);
                $useragent = explode(")", $useragent[0]);
                $useragent = explode("(", $useragent[0]);
                $version = explode(";", $useragent[1]);

                if ($plaform == '') {
                    $plaform = $version[0];
                }
                $version = $version[1];
            }
        } catch (Exception $e) {
        }

        if ($Ismobile != "") {
            $typeDevice = "Mobile";
        } else {
            $typeDevice = "Web";
        }
        $array = array(
            "tenant" => $tenant,
            "event" => "falljackpot",
            "context" => array(
                "event_device_type" => $typeDevice,
                "event_native_mobile" => false,
                "event_platform" => $plaform,
                "event_os" => $version,
                "name" => $Registro->nombre,
                "phone" => $Registro->celular,
                "email" => $Registro->email,
                "iduser" => intval($Usuario->usuarioId),
                "idcasino" => intval($UsuarioMandante->usumandanteId),
                "namejackpot" => $JackpotInterno->descripcion,
                "vertical" => $verticales,
                "partner" => $Mandante->descripcion,
                "country" => $ConfigurationEnvironment->quitar_tildes($Pais->paisNom),
                "amountwin" => intval(round($JackpotInterno->valorActual)),
                "datetime" => $UsuarioJackpotGanador->fechaModif
            ),
            "customer" => $Usuario->usuarioId,
        );

        /* Se crea una auditoría general con información de usuario e IP. */
        $AuditoriaGeneral = new AuditoriaGeneral();

        $AuditoriaGeneral->setUsuarioId(0);
        $AuditoriaGeneral->setUsuariosolicitaId(0);

        /* configura una auditoría para el envio del evento */
        $AuditoriaGeneral->setUsuarioaprobarIp(0);
        $AuditoriaGeneral->setTipo("ENVIO_CRM_FALLJACKPOT");
        $AuditoriaGeneral->setData(json_encode($array));
        $AuditoriaGeneral->setUsucreaId(0);
        $AuditoriaGeneral->setUsumodifId(0);

        /* Se establece el estado, dispositivo y observación en Auditoría General con MySQL. */
        $AuditoriaGeneral->setEstado("A");
        $AuditoriaGeneral->setDispositivo(0);
        $AuditoriaGeneral->setObservacion("ENVIADO_OPTIMOVE");

        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
        /* Inserta un registro de auditoría general en la base de datos MySQL. */
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
        $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

        $this->Auth = $this->Encrypta($array);

        $Response = $this->connectionPOST($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;


        return json_decode(json_encode($data));
    }

    /**
     * Obtiene la configuración para un mandante y país específicos.
     *
     * @param string $mandante El identificador del mandante.
     * @param string $paisId   El identificador del país.
     *
     * @return array            Un arreglo asociativo que contiene la configuración del mandante y país.
     */
    function getConfig($mandante, $paisId)
    {
        $configs = [
            '0' => [
                '173' => [
                    'tenant' => '1058',
                    'token' => 'f65fb4ccdde14ff685fd26aabeb4f25f',
                    'URLPROD' => 'https://server-1058.optimove.net/reportServer'
                ], // DoradoBet Peru
                '46' => [
                    'tenant' => '1223',
                    'token' => 'ebbd1490191d456e9a3fdb1bae3fd68c',
                    'URLPROD' => 'https://server-1223.optimove.net/reportServer'
                ],  // DoradoBet Chile
                '60' => [
                    'tenant' => '1212',
                    'token' => '3ae975ffbbd34002a5daa9f1b6f7289f',
                    'URLPROD' => 'https://server-1212.optimove.net/reportServer'
                ],  // DoradoBet Costa Rica
                '66' => [
                    'tenant' => '1218',
                    'token' => '5c04450fe1d742cb98954a6bd0191923',
                    'URLPROD' => 'https://server-1218.optimove.net/reportServer'
                ],  // DoradoBet Ecuador
                '94' => [
                    'tenant' => '1220',
                    'token' => '6f6d951a16c345c6a1d62eaa63d221f3',
                    'URLPROD' => 'https://server-1220.optimove.net/reportServer'
                ],  // DoradoBet Guatemala
                '2' => [
                    'tenant' => '1219',
                    'token' => 'a95f6195fa104c5a95762e2238a11b94',
                    'URLPROD' => 'https://server-1219.optimove.net/reportServer'
                ],  // DoradoBet Nicaragua
                '68' => [
                    'tenant' => '1390',
                    'token' => '3514747b73ce44e48b1d4185d983a168',
                    'URLPROD' => 'https://server-1390.optimove.net/reportServer'
                ], // DoradoBet Salvador
            ],
            '8' => [
                '66' => [
                    'tenant' => '1129',
                    'token' => '6c1594cfcd064dff8fbddc5e256068fd',
                    'URLPROD' => 'https://server-1129.optimove.net/reportServer'
                ],  // Ecuabet Ecuador
            ],
            '14' => [
                '33' => [
                    'tenant' => '1207',
                    'token' => '12d918fc0e694c18b8c368771ef03c70',
                    'URLPROD' => 'https://server-1207.optimove.net/reportServer'
                ],  // Lotosport Brasil
            ],
            '23' => [
                '102' => [
                    'tenant' => '1386',
                    'token' => 'fa7d3d414c484dc4a42f07a5482ef219',
                    'URLPROD' => 'https://server-1386.optimove.net/reportServer'
                ],  // Paniplay Honduras
            ],
            '18' => [
                'any' => [
                    'tenant' => '1160',
                    'token' => 'fc28dc1311ae40ef86e5a8c691b68d14',
                    'URLPROD' => 'https://server-1160.optimove.net/reportServer'
                ],  // GangaBet
            ],
        ];

        return $configs[$mandante][$paisId] ?: $configs[$mandante]['any'];
    }

    /**
     * Encrypta
     *
     * Esta función se utiliza para encriptar los datos utilizando HMAC-SHA256.
     *
     * @param array $data Datos a encriptar.
     *
     * @return string       Hash encriptado.
     */
    function Encrypta($data)
    {
        $data = (json_encode($data));
        $Hash = hash_hmac('sha256', $data, $this->token);
        return $Hash;
    }

    /**
     * ConnectionAutentica
     *
     * Esta función se utiliza para autenticar una conexión utilizando CurlWrapper.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string       Respuesta de la solicitud.
     */
    function connectionAutentica($data)
    {
        $data = json_encode($data);

        // Inicializar la clase CurlWrapper
        $curl = new \CurlWrapper($this->URL . $this->metodo);

        // Configurar opciones
        $curl->setOptionsArray([
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ]);

        // Ejecutar la solicitud
        $result = $curl->execute();

        return $result;
    }

    /**
     * ConnectionGET
     *
     * Esta función se utiliza para realizar una solicitud GET utilizando CurlWrapper.
     *
     * @return string Respuesta de la solicitud.
     */
    function connectionGET()
    {
        $headers = array(
            'Content-type: application/json',
            'X-Optimove-Signature-Version: 1',
            'X-Optimove-Signature-Content:' . $this->Auth
        );

        // Inicializar la clase CurlWrapper
        $curl = new \CurlWrapper($this->URL . $this->metodo);

        // Configurar opciones
        $curl->setOptionsArray([
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ]);

        // Ejecutar la solicitud
        $result = $curl->execute();

        return $result;
    }

    /**
     * ConnectionGETCamp
     *
     * Esta función se utiliza para realizar una solicitud GET para una campaña específica utilizando CurlWrapper.
     *
     * @param string $CampaignID El identificador de la campaña.
     *
     * @return string              Respuesta de la solicitud.
     */
    function connectionGETCamp($CampaignID)
    {
        $headers = array(
            'Content-type: application/json',
            'X-Optimove-Signature-Version: 1',
            'X-Optimove-Signature-Content:' . $this->Auth
        );

        // Inicializar la clase CurlWrapper
        $curl = new \CurlWrapper($this->URL . $this->metodo . "?" . $CampaignID);

        // Configurar opciones
        $curl->setOptionsArray([
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ]);

        // Ejecutar la solicitud
        $result = $curl->execute();

        return $result;
    }

    /**
     * ConnectionPOST
     *
     * Esta función se utiliza para realizar una solicitud POST utilizando CurlWrapper.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string       Respuesta de la solicitud.
     */
    function connectionPOST($data)
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->username = $this->usernameDEV;

            $this->callback_url = $this->callback_urlDEV;
            $this->URL = $this->URLDEV;
            $this->URLAUTH = $this->URLDEVAUTH;
        } else {
            $this->username = $this->usernamePROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->URL = $this->URLPROD;
            $this->URLAUTH = $this->URLPRODAUTH;
        }
        $data = json_encode($data);


        $headers = array(
            'Content-type: application/json',
            'X-Optimove-Signature-Version: 1',
            'X-Optimove-Signature-Content:' . $this->Auth
        );

        // Inicializar la clase CurlWrapper
        $curl = new \CurlWrapper($this->URL);

        // Configurar opciones
        $curl->setOptionsArray([
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ]);

        // Ejecutar la solicitud
        $result = $curl->execute();

        return $result;
    }

    /**
     * ConnectionPUT
     *
     * Esta función se utiliza para realizar una solicitud PUT utilizando CurlWrapper.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string       Respuesta de la solicitud.
     */
    function connectionPUT($data)
    {
        $data = json_encode($data);

        $headers = array(
            'Authorization:  ' . $this->Auth,
            'Content-type: application/json',
            'Accept: application/json'
        );

        $curl = curl_init($this->URL . $this->metodo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);

        curl_close($result);
        return $result;
    }
}
