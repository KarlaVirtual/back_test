<?php

/**
 * Clase FastTrack
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-03-19
 */

namespace Backend\integrations\crm;

use Backend\dto\CategoriaMandante;
use Backend\dto\CategoriaProducto;
use Backend\dto\Ciudad;
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
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;

/**
 * Esta clase proporciona métodos para interactuar con el sistema FastTrack, incluyendo
 * funcionalidades de registro, inicio de sesión, consentimiento, bloqueos, actualizaciones,
 * pagos, casino, rondas de juego, bonos y saldos.
 */
class FastTrack
{

    /**
     * Nombre de usuario para el entorno actual.
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
     * URL base para el entorno actual.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL de autenticación para el entorno actual.
     *
     * @var string
     */
    private $URLAUTH = "";

    /**
     * URL base para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = '';

    /**
     * URL de autenticación para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEVAUTH = '';

    /**
     * URL base para el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = '';

    /**
     * URL de autenticación para el entorno de producción.
     *
     * @var string
     */
    private $URLPRODAUTH = '';

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token = "";

    /**
     * Metodo de la API a utilizar.
     *
     * @var string
     */
    private $metodo = "";

    /**
     * URL de callback para el entorno actual.
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
     * Clave privada para el entorno actual.
     *
     * @var string
     */
    private $KeyPRIVATE = "";

    /**
     * Clave privada para el entorno de desarrollo.
     *
     * @var string
     */
    private $KeyPRIVATEDEV = "";

    /**
     * Clave privada para el entorno de producción.
     *
     * @var string
     */
    private $KeyPRIVATEPROD = "";

    /**
     * Contraseña para el entorno actual.
     *
     * @var string
     */
    private $password = "";

    /**
     * Contraseña para el entorno de desarrollo.
     *
     * @var string
     */
    private $passwordDEV = "";

    /**
     * Contraseña para el entorno de producción.
     *
     * @var string
     */
    private $passwordPROD = "";

    /**
     * Clave pública para el entorno actual.
     *
     * @var string
     */
    private $KeyPUBLIC = "";

    /**
     * Clave pública para el entorno de desarrollo.
     *
     * @var string
     */
    private $KeyPUBLICDEV = "";

    /**
     * Clave pública para el entorno de producción.
     *
     * @var string
     */
    private $KeyPUBLICPROD = "";

    /**
     * Autenticación generada.
     *
     * @var string
     */
    private $Auth = "";

    /**
     * Constructor de la clase FastTrack.
     *
     * Inicializa las propiedades de la clase según el entorno de configuración.
     * Si el entorno es de desarrollo, se configuran las propiedades con los valores
     * correspondientes al entorno de desarrollo. De lo contrario, se configuran con
     * los valores del entorno de producción.
     */
    function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->username = $this->usernameDEV;
            $this->password = $this->passwordDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->URL = $this->URLDEV;
            $this->URLAUTH = $this->URLDEVAUTH;
        } else {
            $this->username = $this->usernamePROD;
            $this->password = $this->passwordPROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->URL = $this->URLPROD;
            $this->URLAUTH = $this->URLPRODAUTH;
        }
    }

    /**
     * Función Registrations.
     *
     * Esta función se utiliza para registrar un usuario en el sistema FastTrack.
     *
     * @param Usuario $Usuario Objeto Usuario que contiene la información del usuario a registrar.
     *
     * @return array Respuesta en formato JSON con la estructura:
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [int] $error: Código de error en caso de fallo.
     *  - [mixed] $response: Respuesta del sistema FastTrack.
     */
    function Registrations(Usuario $Usuario)
    {
        $Proveedor = new Proveedor("", "FASTTRACK");

        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMandate = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $date = date("Y-m-d H:i:s");

        $date = str_replace(" ", "T", $date) . "Z";
        $this->metodo = "/user";
        $IP = explode(",", $Usuario->dirIp);
        $return = array(
            "user_id" => $Usuario->usuarioId,
            "url_referer" => $Mandante->baseUrl,
            "note" => "",
            "user_agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36",
            "ip_address" => $IP[0],
            "timestamp" => $date,
            "origin" => $Mandante->baseUrl

        );

        $Response = $this->connectionPOST($return);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Función Login.
     *
     * Esta función se utiliza para iniciar sesión en el sistema FastTrack.
     *
     * @param Usuario $Usuario Objeto Usuario que contiene la información del usuario que intenta iniciar sesión.
     *
     * @return array Respuesta en formato JSON con la estructura:
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [int] $error: Código de error en caso de fallo.
     *  - [mixed] $response: Respuesta del sistema FastTrack.
     */
    function Login(Usuario $Usuario)
    {
        $Proveedor = new Proveedor("", "FASTTRACK");

        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMandate = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $date = date("Y-m-d H:i:s");
        $this->metodo = "/login";
        $date = str_replace(" ", "T", $date) . "Z";
        $IP = explode(",", $Usuario->dirIp);
        $return = array(
            "user_id" => $Usuario->usuarioId,
            "is_impersonated" => true,
            "ip_address" => $IP[0],
            "user_agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36",
            "timestamp" => $date,
            "origin" => $Mandante->baseUrl

        );

        $Response = $this->connectionPOST($return);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Función consents.
     *
     * Esta función se utiliza para gestionar los consentimientos de un usuario en el sistema FastTrack.
     *
     * @param Usuario $Usuario Objeto Usuario que contiene la información del usuario.
     *
     * @return array Respuesta en formato JSON con la estructura:
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [int] $error: Código de error en caso de fallo.
     *  - [mixed] $response: Respuesta del sistema FastTrack.
     */
    function consents(Usuario $Usuario)
    {
        $Proveedor = new Proveedor("", "FASTTRACK");

        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMandate = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $date = date("Y-m-d H:i:s");
        $this->metodo = "/user/consents";
        $date = str_replace(" ", "T", $date) . "Z";
        $return = array(
            "user_id" => $Usuario->usuarioId,
            "timestamp" => $date,
            "origin" => $Mandante->baseUrl

        );

        $Response = $this->connectionPUT($return);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Función Blocks.
     *
     * Esta función se utiliza para gestionar los bloqueos de un usuario en el sistema FastTrack.
     *
     * @param Usuario $Usuario Objeto Usuario que contiene la información del usuario.
     *
     * @return array Respuesta en formato JSON con la estructura:
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [int] $error: Código de error en caso de fallo.
     *  - [mixed] $response: Respuesta del sistema FastTrack.
     */
    function Blocks(Usuario $Usuario)
    {
        $Proveedor = new Proveedor("", "FASTTRACK");

        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMandate = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $date = date("Y-m-d H:i:s");

        $this->metodo = "/user/blocks";
        $date = str_replace(" ", "T", $date) . "Z";
        $return = array(
            "user_id" => $Usuario->usuarioId,
            "timestamp" => $date,
            "origin" => $Mandante->baseUrl

        );

        $Response = $this->connectionPUT($return);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Función Updates.
     *
     * Esta función se utiliza para actualizar la información de un usuario en el sistema FastTrack.
     *
     * @param Usuario $Usuario Objeto Usuario que contiene la información del usuario a actualizar.
     *
     * @return array Respuesta en formato JSON con la estructura:
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [int] $error: Código de error en caso de fallo.
     *  - [mixed] $response: Respuesta del sistema FastTrack.
     */
    function Updates(Usuario $Usuario)
    {
        $Proveedor = new Proveedor("", "FASTTRACK");

        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Mandante = new Mandante($Usuario->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMandate = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $date = date("Y-m-d H:i:s");
        $this->metodo = "/user";
        $date = str_replace(" ", "T", $date) . "Z";
        $return = array(
            "user_id" => $Usuario->usuarioId,
            "timestamp" => $date,
            "origin" => $Mandante->baseUrl
        );

        $Response = $this->connectionPUT($return);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Función payment.
     *
     * Esta función se utiliza para gestionar los pagos de un usuario en el sistema FastTrack.
     *
     * @param UsuarioRecarga $UsuarioRecarga Objeto UsuarioRecarga que contiene la información del usuario y la recarga.
     * @param int            $ProductoId     ID del producto asociado al pago.
     *
     * @return array Respuesta en formato JSON con la estructura:
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [int] $error: Código de error en caso de fallo.
     *  - [mixed] $response: Respuesta del sistema FastTrack.
     */
    function payment(UsuarioRecarga $UsuarioRecarga, $ProductoId)
    {
        $Registro = new Registro("", $UsuarioRecarga->usuarioId);
        $Pais = new Pais($UsuarioRecarga->paisId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $Producto = new Producto($ProductoId);
        $Proveedor = new Proveedor($Producto->proveedorId);
        $Mandante = new Mandante($UsuarioRecarga->mandante);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMandate = new UsuarioMandante("", $UsuarioRecarga->usuarioId, $Mandante->mandante);

        $date = date("Y-m-d H:i:s");
        $this->metodo = "/user";
        $date = str_replace(" ", "T", $date) . "Z";

        if ($UsuarioRecarga->getEstado() == "A") {
            $status = "Approved";
        }
        if ($UsuarioRecarga->getEstado() == "I") {
            $status = "Rejected";
        }
        $return = array(
            "amount" => $UsuarioRecarga->valor,
            "bonus_code" => "",
            "currency" => $UsuarioMandate->moneda,
            "exchange_rate" => 1,
            "fee_amount" => 0,
            "note" => "",
            "origin" => $Mandante->baseUrl,
            "payment_id" => $UsuarioRecarga->recargaId,
            "status" => $status,
            "timestamp" => $date,
            "type" => "Depósito",
            "user_id" => $UsuarioRecarga->usuarioId,
            "vendor_id" => $Mandante->mandante,
            "vendor_name" => $Mandante->descripcion,
        );

        $Response = $this->connectionPOST($return);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Función casino.
     *
     * Esta función se utiliza para gestionar las transacciones de casino de un usuario en el sistema FastTrack.
     *
     * @param object $UsuarioHistorial Objeto UsuarioHistorial que contiene la información del historial del
     *                                           usuario.
     *
     * @return array Respuesta en formato JSON con la estructura:
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [int] $error: Código de error en caso de fallo.
     *  - [mixed] $response: Respuesta del sistema FastTrack.
     */
    function casino($UsuarioHistorial)
    {
        $Usuario = new Usuario($UsuarioHistorial->usuarioId);
        $Mandante = new Mandante($Usuario->mandante);
        $UsuarioMandate = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $TransjuegoLog = new TransjuegoLog($UsuarioHistorial->externoId);

        $ProductoMandante = new ProductoMandante("", "", $TransjuegoLog->productoId);

        $Producto = new Producto($ProductoMandante->productoId);

        $SubProveedor = new Subproveedor($Producto->subproveedorId);

        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);

        $Registro = new Registro("", $Usuario->usuarioId);

        $Pais = new Pais($Usuario->paisId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $date = date("Y-m-d H:i:s");
        $this->metodo = "/casino";
        $date = str_replace(" ", "T", $date) . "Z";

        if ($UsuarioHistorial->tipo == 30 && $UsuarioHistorial->movimiento == "S") {
            $Valor = $UsuarioHistorial->valor;
            $Type = "BET";
            $balance_before = $UsuarioHistorial->creditos + $UsuarioHistorial->creditosBase + $UsuarioHistorial->valor;
        }

        if ($UsuarioHistorial->tipo == 30 && $UsuarioHistorial->movimiento == "E") {
            $Valor = $UsuarioHistorial->valor;
            $Type = "WIN";
            $balance_before = $UsuarioHistorial->creditos + $UsuarioHistorial->creditosBase - $UsuarioHistorial->valor;
        }

        if ($TransaccionJuego->tipo == "FREESPIN") {
            $bonus_wager_amount = $TransjuegoLog->valor;
        }

        if ($TransaccionJuego->estado == "I") {
            $is_round_end = true;
        } else {
            $is_round_end = false;
        }

        $CategoriaProducto = new CategoriaProducto(
            "",
            $Producto->productoId,
            $SubProveedor->tipo,
            $Producto->categoriaId,
            $Mandante->mandante,
            $Usuario->paisId
        );
        $CategoriaMandante = new CategoriaMandante($CategoriaProducto->categoriaId);
        $return = array(
            "activity_id" => $TransjuegoLog->transjuegologId,
            "amount" => $Valor,
            "balance_after" => $UsuarioHistorial->creditos + $UsuarioHistorial->creditosBase,
            "balance_before" => $balance_before,
            "bonus_wager_amount" => $bonus_wager_amount,
            "currency" => $UsuarioMandate->moneda,
            "exchange_rate" => 1,
            "game_id" => $ProductoMandante->prodmandanteId,
            "game_name" => $Producto->descripcion,
            "game_type" => $CategoriaMandante->getDescripcion(),
            "is_round_end" => $is_round_end,
            "locked_wager_amount" => 0,
            "origin" => $Mandante->baseUrl,
            "round_id" => $TransjuegoLog->transjuegoId,
            "timestamp" => $date,
            "status" => "Approved",
            "type" => $Type,
            "user_id" => $Usuario->usuarioId,
            "vendor_id" => $Mandante->mandante,
            "vendor_name" => $Mandante->descripcion,
            "wager_amount" => $Valor - $bonus_wager_amount,

        );

        $Response = $this->connectionPOST($return);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Función gameround.
     *
     * Esta función se utiliza para gestionar las rondas de juego de un usuario en el sistema FastTrack.
     *
     * @param object $UsuarioHistorial Objeto UsuarioHistorial que contiene la información del historial del
     *                                           usuario.
     *
     * @return array Respuesta en formato JSON con la estructura:
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [int] $error: Código de error en caso de fallo.
     *  - [mixed] $response: Respuesta del sistema FastTrack.
     */
    function gameround($UsuarioHistorial)
    {
        $Usuario = new Usuario($UsuarioHistorial->usuarioId);
        $Mandante = new Mandante($Usuario->mandante);
        $UsuarioMandate = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $TransjuegoLog = new TransjuegoLog($UsuarioHistorial->externoId);
        $Producto = new Producto($TransjuegoLog->productoId);
        $ProductoMandante = new ProductoMandante($Producto->productoId, $Mandante->mandante);
        $SubProveedor = new Subproveedor($Producto->subproveedorId);
        $transaccionId = explode("_", $TransjuegoLog->transaccionId);
        $TransaccionJuego = new TransaccionJuego("", $transaccionId[0]);
        $Registro = new Registro("", $Usuario->usuarioId);

        $Pais = new Pais($Usuario->paisId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $date = date("Y-m-d H:i:s");

        $this->metodo = "/gameround";
        $date = str_replace(" ", "T", $date) . "Z";
        $CategoriaProducto = new CategoriaProducto(
            "",
            $Producto->productoId,
            $SubProveedor->tipo,
            $Producto->categoriaId,
            $Mandante->mandante,
            $Usuario->paisId
        );
        $CategoriaMandante = new CategoriaMandante($CategoriaProducto->categoriaId);
        $return = array(
            "user_id" => $Usuario->usuarioId,
            "round_id" => $TransjuegoLog->transjuegoId,
            "game_id" => $ProductoMandante->prodmandanteId,
            "game_name" => $Producto->descripcion,
            "game_type" => $CategoriaMandante->getDescripcion(),
            "vendor_id" => $Mandante->mandante,
            "vendor_name" => $Mandante->descripcion,
            "user_currency" => $UsuarioMandate->moneda,
            "device_type" => "mobile",
            "timestamp" => $date,
            "origin" => $Mandante->baseUrl,

        );
        $Response = $this->connectionPOST($return);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Función bonus.
     *
     * Esta función se utiliza para gestionar los bonos de un usuario en el sistema FastTrack.
     *
     * @return array Respuesta en formato JSON con la estructura:
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [int] $error: Código de error en caso de fallo.
     *  - [mixed] $response: Respuesta del sistema FastTrack.
     */
    function bonus()
    {
        $Usuario = new Usuario();
        $Mandante = new Mandante($Usuario->mandante);
        $UsuarioMandate = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $SubProveedor = new Subproveedor();

        $Registro = new Registro("", $Usuario->usuarioId);

        $Pais = new Pais($Usuario->paisId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $date = date("Y-m-d H:i:s");
        $this->metodo = "/bonus";
        $date = str_replace(" ", "T", date("Y-m-d H:i:s", $date)) . "Z";

        $return = array(
            "amount" => "",
            "bonus_code" => "",
            "bonus_id" => "",
            "bonus_turned_real" => "",
            "currency" => "",
            "exchange_rate" => 1,
            "origin" => $Mandante->baseUrl,
            "product" => "",
            "status" => "",
            "timestamp" => $date,
            "type" => "",
            "user_bonus_id" => "",
            "user_id" => $Usuario->usuarioId

        );
        $Response = $this->connectionPOST($return);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Función balances.
     *
     * Esta función se utiliza para obtener los saldos de recargas y retiros de un usuario en el sistema FastTrack.
     *
     * @param object $Usuario Objeto Usuario que contiene la información del usuario.
     *
     * @return array Respuesta en formato JSON con la estructura:
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [int] $error: Código de error en caso de fallo.
     *  - [array] $response: Respuesta del sistema FastTrack.
     */
    function balances($Usuario)
    {
        $Usuario = new Usuario();
        $Mandante = new Mandante($Usuario->mandante);
        $UsuarioMandate = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);

        $Registro = new Registro("", $Usuario->usuarioId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $date = date("Y-m-d H:i:s");
        $this->metodo = "/user/balances";
        $date = str_replace(" ", "T", date("Y-m-d H:i:s", $date)) . "Z";
        $saldoRecargas = $Registro->getCreditosBase();
        $saldoRetiros = $Registro->getCreditos();

        $ArraySaldoRecarga = array(
            "amount" => $saldoRecargas,
            "currency" => $UsuarioMandate->moneda,
            "key" => "Saldo Recargas",
            "exchange_rate" => 1
        );
        $ArraysaldoRetiros = array(
            "amount" => $saldoRetiros,
            "currency" => $UsuarioMandate->moneda,
            "key" => "Saldo Retiros",
            "exchange_rate" => 1
        );
        $return = array(
            "balances" => array(
                $ArraySaldoRecarga,
                $ArraysaldoRetiros
            ),
            "origin" => $Mandante->baseUrl,
            "timestamp" => $date,
            "user_id" => $Usuario->usuarioId,

        );
        $Response = $this->connectionPOST($return);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Función Encrypta.
     *
     * Esta función se utiliza para generar un hash HMAC-SHA512 de los datos proporcionados.
     *
     * @param string $data Datos a ser encriptados.
     *
     * @return void
     */
    function Encrypta($data)
    {
        $this->Auth = hash_hmac('sha512', $data, $this->KeyPRIVATE);
    }

    /**
     * Función connectionAutentica.
     *
     * Esta función se utiliza para autenticar una conexión mediante una solicitud POST.
     *
     * @param mixed $data Datos a ser enviados en la solicitud.
     *
     * @return string Respuesta de la solicitud en formato JSON.
     */
    function connectionAutentica($data)
    {
        $data = json_encode($data);

        $curl = curl_init($this->URLAUTH);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($result);
        return $result;
    }

    /**
     * Realiza una solicitud GET a la URL configurada.
     *
     * @return string Respuesta de la solicitud en formato JSON.
     */
    function connectionGET()
    {
        $curl = curl_init($this->URL . $this->metodo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
     * Realiza una solicitud POST a la URL configurada.
     *
     * @param mixed $data Datos a ser enviados en la solicitud.
     *
     * @return string Respuesta de la solicitud en formato JSON.
     */
    function connectionPOST($data)
    {
        $data = json_encode($data);

        $headers = array(
            'Authorization: Basic ' . base64_encode($this->KeyPRIVATE),
            'Content-type: application/json ',
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

    /**
     * Realiza una solicitud PUT a la URL configurada.
     *
     * @param mixed $data Datos a ser enviados en la solicitud.
     *
     * @return string Respuesta de la solicitud en formato JSON.
     */
    function connectionPUT($data)
    {
        $data = json_encode($data);

        $headers = array(
            'Authorization: Basic ' . base64_encode($this->KeyPRIVATE),
            'Content-type: application/json ',
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
