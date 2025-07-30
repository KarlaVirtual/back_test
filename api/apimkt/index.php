<?php
/**
 * Procesa la solicitud de la API y maneja la autenticación y la respuesta.
 *
 * @param array $params Datos de entrada decodificados de la solicitud HTTP.
 *  - `DXbDpfykzqwS` (string) Bandera debugging.
 *  - `X-Token` (string) Token de sesión.
 *
 * @return array $response Imprime la respuesta en formato JSON.
 *  - `Alert` (string) Mensaje de alerta.
 *  - `HasError` (boolean) Indica si hay un error.
 *  - `AlertType` (string) Tipo de alerta.
 *  - `AlertMessage` (string) Mensaje de alerta.
 *  - `CodeError` (int) Código de error.
 *  - `messageUrl` (string) URL de mensaje.
 */

/* Desactiva la visualización de errores y define la función `getallheaders` si no existe. */
error_reporting(
    0

);
ini_set("display_errors", "OFF");

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}


/* establece un dominio de sesión basado en la referencia HTTP. */
$response["Alert"] = $_SERVER['HTTP_REFERER'];

$domainSession = ".virtualsoft.tech";

if (strpos($_SERVER['HTTP_REFERER'], "netabet.com.mx") !== FALSE) {
    $domainSession = ".netabet.com.mx";
}


/* Configura sesiones con parámetros de cookie para mayor seguridad en un dominio específico. */
if ($domainSession != "") {
    session_name('SessionName');
    session_set_cookie_params(['SameSite' => 'None', 'Secure' => true]);
    session_set_cookie_params(
        1800,
        ini_get('session.cookie_path'),
        $domainSession
    );
}


use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\imports\MobileDetect\MobileDetect;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/* obtiene el valor del encabezado 'X-Token' de la solicitud HTTP. */
$headers = getallheaders();

$xtoken = $headers['X-Token'];


$xtoken = $headers['x-Token'] ?? null;

//$xtoken = "5vrchoakfe8eg7an506in0klka";

/* asigna un token de sesión a partir de los encabezados. */
if ($xtoken == '') {
    $xtoken = $headers['x-token'];
}
if ($xtoken != "" && $xtoken != null && $xtoken != 'null') {
    session_id($xtoken);
}

require_once "includes.php";

/* gestiona sesiones y permite encabezados para solicitudes CORS. */
if ($xtoken == 'null') {
    session_destroy();
}

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token');

/* Configura encabezados CORS, permite métodos HTTP y habilita la depuración bajo ciertas condiciones. */
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');
ini_set('memory_limit', '-1');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);


if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}


/* valida una solicitud y ajusta la zona horaria en una sesión. */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
    $_ENV["debugFixed2"] = '1';
}
$timezone = $_SESSION["timezone"];
$timezone = -5 - ($timezone);
$timezone = 0;


/* genera un registro con la URI y contenido de la solicitud HTTP. */
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


/* obtiene la URI de la solicitud y lee datos de entrada en PHP. */
$URI = $_SERVER["REQUEST_URI"];
$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';

$currencies_valor = array();

$params = file_get_contents('php://input');

/* decodifica parámetros JSON y maneja solicitudes OPTIONS en PHP. */
$params = json_decode($params);
$response = array();

$ENCRYPTION_KEY = "D!@#$%^&*";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}
//Establecemos variables globales para el uso en la api

/* detecta si el dispositivo es móvil y asigna valores globales. */
$MobileDetect = new MobileDetect();
$Global_dispositivo = "Desktop";
$Global_soperativo = "";
$Global_sversion = "";
$Global_IP = (new ConfigurationEnvironment())->get_client_ip();

if ($MobileDetect->isMobile()) {
    $Global_dispositivo = "Mobile";
}


/* identifica el tipo de dispositivo y asigna un valor a una variable. */
if ($MobileDetect->isTablet()) {
    $Global_soperativo = "Tablet";
}

if ($MobileDetect->is('iOs')) {
    $Global_soperativo = "iOS";
}


/* Separa una cadena en un array usando "/" y toma la primera parte antes del "?". */
$arraySuper = explode("/", current(explode("?", $URI)));


try {

    /* verifica la autenticación del usuario y construye una ruta de archivo. */
    $arraySuper[oldCount($arraySuper) - 2] = ucfirst($arraySuper[oldCount($arraySuper) - 2]);

    $filename = __DIR__ . '/../apimkt/cases/' . $arraySuper[oldCount($arraySuper) - 2] . "/" . $arraySuper[oldCount($arraySuper) - 1] . ".php";

    $validacionLogueado = false;

    if ($_SESSION["logueado"] || $arraySuper[oldCount($arraySuper) - 1] == "CheckAuthentication" || $arraySuper[oldCount($arraySuper) - 1] == "uploadImage" || $arraySuper[oldCount($arraySuper) - 1] == "Login" || $arraySuper[oldCount($arraySuper) - 1] == "ImportBulkUsers" || $arraySuper[oldCount($arraySuper) - 1] == "GenerateHashFiles" || $arraySuper[oldCount($arraySuper) - 1] == "GeneraHashFilesBackend" || $arraySuper[oldCount($arraySuper) - 1] == "CloseDaySpecific" || $arraySuper[oldCount($arraySuper) - 1] == "LoginGoogle") {
        $validacionLogueado = true;
    }

    /* verifica si existe un archivo y un usuario está logueado, dando errores si no. */
    if (file_exists($filename) && $validacionLogueado) {
        require_once $filename;
    } else {
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = 'f';
        $response["CodeError"] = $code;

    }


} catch (Exception $e) {

    /* obtiene el código de error de una excepción en PHP. */
    $code = $e->getCode();

    /*
     *
     errorCode  Error Description
        0       Completed successfully

    1   General error
    2   Saved for future use
    3   Insufficient funds
    4   Operator limit to the player 1 (insufficient behavior)
    5   Operator limit to the player 2 (insufficient behavior)
    6   Token not found
    7   User not found
    8   User blocked
    9   Transaction not found
    10  Transaction timed out
    11  Real balance is not enough for tipping

     */


    /* Inicializa variables para el proveedor y un arreglo para almacenar respuestas. */
    $codeProveedor = "";
    $messageProveedor = "";

    $response = array();

    switch ($code) {
        case 10011:
            /* maneja un caso específico, asignando un mensaje de error para sesiones no válidas. */

            $codeProveedor = "400";
            $messageProveedor = "No such session.";

            break;
        case 21:
            /* asigna un mensaje de error para una sesión inexistente. */

            $codeProveedor = "400";
            $messageProveedor = "No such session.";

            break;
        case 22:
            /* maneja un caso específico indicando que no hay sesión disponible. */

            $codeProveedor = "400";
            $messageProveedor = "No such session.";

            break;
        case 20001:
            /* maneja un caso de error por fondos insuficientes en un proveedor. */

            $codeProveedor = "402";
            $messageProveedor = "Insufficient funds.";

            break;

        case 0:
            /* maneja un caso de error para un proveedor específico. */

            $codeProveedor = "402";
            $messageProveedor = "General Error. (" . $code . ")";

            break;
        case 27:
            /* maneja un error general asignando un código y mensaje específico. */

            $codeProveedor = "402";
            $messageProveedor = "General Error. (" . $code . ")";
            break;
        case 28:
            /* maneja un caso específico de error general para un proveedor. */


            $codeProveedor = "402";
            $messageProveedor = "General Error. (" . $code . ")";


            break;
        case 29:
            /* define un caso para manejar un error de transacción no encontrada. */


            $codeProveedor = "402";
            $messageProveedor = "Transaction Not Found";

            break;

        case 10001:
            /* asigna valores a variables si el caso es 10001. */


            $codeProveedor = 0;
            $messageProveedor = "Already processed";

            break;

        case 10004:
            /* Maneja el caso 10004, asignando un código y mensaje de error a variables. */


            $codeProveedor = "REQUEST_DECLINED";
            $messageProveedor = "General Error. (" . $code . ")";

            break;
        case 10014:
            /* maneja un caso de error al recibir un proveedor de servicios. */


            $codeProveedor = "REQUEST_DECLINED";
            $messageProveedor = "General Error. (" . $code . ")";

            break;


        case 10010:
            /* maneja un caso de error con mensaje y código específico. */


            $codeProveedor = "REQUEST_DECLINED";
            $messageProveedor = "General Error. (" . $code . ")";


            break;


        case 20001:
            /* maneja un caso con errores de fondos insuficientes para un proveedor. */


            $codeProveedor = "402";
            $messageProveedor = "Insufficients Funds";


            break;

        case 20002:
            /* representa un caso en una estructura switch, pero no realiza ninguna acción. */


            break;

        case 20003:
            /* Maneja el caso de un proveedor cuya cuenta ha sido bloqueada. */


            $codeProveedor = "ACCOUNT_BLOCKED";
            $messageProveedor = "ACCOUNT_BLOCKED";


            break;

        case 10005:
            /* El fragmento muestra un caso en un switch, pero no ejecuta ninguna acción. */


            break;

        case 30003:
            /* maneja un caso de error de autenticación de usuario y clave. */


            $messageProveedor = "El usuario o la clave son incorrectos. ";

            break;

        case 100002:
            /* maneja un caso donde no hay cupo disponible para recargas. */


            $messageProveedor = "Punto de venta no tiene cupo disponible para realizar la recarga";

            break;

        case 30001:
            /* Captura un mensaje de error en un caso específico dentro de un bloque de código. */


            $messageProveedor = $e->getMessage();

            break;

        case 30002:
            if ($arraySuper[oldCount($arraySuper) - 1] == "Login") {


                /* Se inicializan variables y se crean instancias de Usuario y UsuarioMySqlDAO. */
                $usuario = $params->Username;
                $PartnerLogin = $params->PartnerLogin;

                $Usuario = new Usuario();

                $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                /* consulta un usuario en la base de datos y lo asigna a una variable. */
                $Usuario = $UsuarioMySqlDAO->queryByLogin($usuario, '0', '');
                $Usuario = $Usuario[0];

                if ($Usuario != "") {

                    /* asigna diferentes URLs según el valor de $Usuario->mandante. */
                    if ($PartnerLogin != $Usuario->mandante) {
                        switch ($Usuario->mandante) {
                            case '0':
                                $response["messageUrl"] = 'doradobet';
                                break;
                            case '1':
                                $response["messageUrl"] = 'ibetsupreme';
                                break;
                            case '2':
                                $response["messageUrl"] = 'justbet';
                                break;
                            case '3':
                                $response["messageUrl"] = 'miravalle';
                                break;
                            case '4':
                                $response["messageUrl"] = 'casinopalacio';
                                break;
                        }

                    }


                    /* Verifica si el usuario es autorizado y establece la URL de respuesta. */
                    if (in_array($usuario, array('ADMINMIRAVALLE', 'OPERMIRAVALLE', 'FINMIRAVALLE'))) {

                        if ($PartnerLogin != 3) {
                            $response["messageUrl"] = 'miravalle';
                        }
                    }
                } else {
                    /* está incompleto; no contiene instrucciones dentro del bloque "else". */


                }


            }

            break;

        default:

            /* Define un código de error y mensaje para problemas en las solicitudes. */
            $codeProveedor = 'UNKNOWN_ERROR';
            $messageProveedor = "Unexpected error. Reporte el codigo" . $code . ".";
            $messageProveedor = "Error en la solicitud.";


            break;
    }


    /* genera un mensaje de error para un intento de inicio de sesión. */
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = 'Error en el login';
    //$response["AlertMessage"] = '-|' . $e->getMessage() . ' |-' . '(' . $e->getCode() . ')';
    $response["AlertMessage"] = $messageProveedor . ' (Report#' . $code . ")";
    // $response["AlertMessage"] = '-|' . "" . ' |-' . '(' . $e->getCode() . ')';
    $response["ModelErrors"] = [];

    /* Asigna un código de error a la respuesta JSON en un arreglo PHP. */
    $response["CodeError"] = $code;
}


/* verifica si `$response` no está vacío y lo imprime en formato JSON. */
if (json_encode($response) != "[]") {
    print_r(json_encode($response));

}

