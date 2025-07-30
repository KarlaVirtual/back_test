<?php

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

/**
 * Punto de entrada principal para la API 'partner'.
 *
 * Este script maneja solicitudes entrantes, valida dominios permitidos, configura CORS,
 * y redirige a los casos específicos según la URI solicitada.
 *
 * @param object $params Objeto JSON decodificado que contiene los parámetros de la solicitud.
 * @param string $params->command Comando a ejecutar.
 *
 * @return array $response Respuesta que incluye:
 * - bool $HasError Indica si ocurrió un error.
 * - string $AlertMessage Mensaje de alerta en caso de error.
 * - int $code Código de estado de la operación.
 * - string $msg Mensaje adicional.
 *
 * @throws Exception Si ocurre un error en la validación del dominio, procesamiento de la solicitud
 * o ejecución del caso correspondiente.
 */

/* Configura cabeceras CORS para permitir solicitudes desde orígenes específicos en JSON. */
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token,swarm-session,x-android-allowallhostnameverifier');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');

function checkOrigin(array $allowedOrigins)
{
    // Verifica si HTTP_ORIGIN está configurado

    /* Verifica si el origen de la solicitud es permitido; retorna verdadero o falso. */
    if (!isset($_SERVER['HTTP_ORIGIN'])) {
        return false;
    }

    $origin = $_SERVER['HTTP_ORIGIN'];

    // Recorre el array y verifica si contiene el origen
    foreach ($allowedOrigins as $allowedOrigin) {
        if (strpos($origin, $allowedOrigin) !== false) {
            return true;
        }
    }

    return false;
}

$domainsEnabled = array(
    'localhost',
    'virtualsoft',
    'doradobet',
    'ibetsupreme',
    'acropolisonline',
    'ibetgamesgy',
    'justbetja',
    'casinomiravallepalace',
    'casinogranpalaciomx',
    'casinointercontinental',
    'netabet',
    'casinoastoriamx',
    'ecuabet',
    'winbet',
    'betgo',
    'PartnerM',
    'powerbet',
    'eltribet',
    'lotosports',
    'hondubet',
    'latinbet',
    'milbets',
    'gangabet',
    'vsft',
    'sivarbet',
    'caman',
    'ecua.lat',
    'masbet',
    'ganamex',
    'paniplay',
    'gangabet',
    'redcasino',
    'ganaplay',
    'dorado.bet'

);

/* Establece el origen permitido para solicitudes CORS según la validación de dominio. */
if (checkOrigin($domainsEnabled)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

} else {
    header('Access-Control-Allow-Origin: virtualsoft.tech');
}
//ini_set('memory_limit', '-1');

/* desactiva errores y gestiona peticiones OPTIONS, saliendo en condiciones específicas. */
ini_set('display_errors', 'OFF');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Max-Age: 86400'); // 1 día
    http_response_code(200); // No Content
    exit();
}
if ($_SERVER['testtest'] === '1') {
    exit();
}
include "includes.php";

/* Configura encabezados para permitir CORS y controlar solicitudes API en JSON. */
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token,swarm-session,x-android-allowallhostnameverifier');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');
//ini_set('memory_limit', '-1');

/* gestiona solicitudes OPTIONS y ajusta la zona horaria en la sesión. */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Max-Age: 86400'); // 1 día
    http_response_code(200); // No Content
    exit();
}
$timezone = $_SESSION["timezone"];
$timezone = -5 - ($timezone);
$timezone = 0;

/* Configura el modo de depuración si la condición de solicitud se cumple. */
$start_time = microtime(true);


if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}


/* verifica una solicitud y establece variables de entorno en consecuencia. */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
    $_ENV["debugFixed2"] = '1';
}

$_ENV["enabledConnectionGlobal"] = 1;

$URI = $_SERVER["REQUEST_URI"];

/* asigna una URL, inicializa un array y obtiene parámetros de input. */
$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';

$currencies_valor = array();

$arraySuper = explode("/", current(explode("?", $URI)));

$params = file_get_contents('php://input');

/* decodifica parámetros JSON y verifica un comando específico para el registro. */
$params = json_decode($params);
$response = array();

if ($arraySuper[oldCount($arraySuper) - 1] == "Api") {

    if ($params->command != "whats_up") {

        /*$log = "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
        $log = $log . $URI;
        $log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

         fwriteCustom('log_' . date("Y-m-d") . '.log',$log);*/
    }
}


/* establece una cookie si el host es específico y está vacío. */
$ENCRYPTION_KEY = "D!@#$%^&*";


if ($_SERVER['HTTP_HOST'] == 'partnerapi.ecuabet.com') {
    try {
        if ($_COOKIE["cook"] == '') {
            setcookie("cook", time(), time() + 8640000, 'https://partnerapi.ecuabet.com'); // expires after 60 seconds
        }

    } catch (Exception $e) {

    }
}


/* establece una cookie si no existe, para un dominio específico. */
if ($_SERVER['HTTP_HOST'] == 'partnerapi.virtualsoft.tech') {
    try {
        if ($_COOKIE["cook"] == '') {
            setcookie("cook", time(), time() + 8640000, 'https://partnerapi.virtualsoft.tech'); // expires after 60 seconds
        }

    } catch (Exception $e) {

    }
}


/* verifica un archivo y configura una variable de entorno para conexiones globales. */
$responseEnable = file_get_contents(__DIR__ . '/../../logSit/enabled');
if ($_ENV['debug']) {
    print_r($_SERVER);

}

$_ENV["enabledConnectionGlobal"] = 1;

try {

    /* verifica si una dirección IP es considerada inusual. */
    $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
    $Global_IP = ($ConfigurationEnvironment)->get_client_ip();

    if (strpos($Global_IP, '146.70.41.22') !== false || strpos($Global_IP, '190.90.253.10') !== false || strpos($Global_IP, '172.105.16.250') !== false) {
        // throw new Exception("Inusual Detected", "12");

    } else {
        /* comenta una excepción para mantenimiento del sitio, evitando errores temporales. */

        //throw new Exception('We are currently in the process of maintaining the site.', 30004);

    }
    //throw new Exception('We are currently in the process of maintaining the site.', 30004);

    /* intenta leer un archivo y muestra información del servidor si está en modo debug. */
    try {
        $responseEnable = file_get_contents(__DIR__ . '/../../logSit/enabled');
    } catch (Exception $e) {
    }
    if ($_ENV['debug']) {
        print_r($_SERVER);

    }


    /* maneja un estado bloqueado y asigna un nombre de archivo basado en un arreglo. */
    if ($responseEnable == 'BLOCKED') {
        throw new Exception('We are currently in the process of maintaining the site.', 30004);
    }

    $arraySuper[oldCount($arraySuper) - 2] = ucfirst($arraySuper[oldCount($arraySuper) - 2]);


    $filename = 'cases/' . $arraySuper[oldCount($arraySuper) - 2] . "/" . $arraySuper[oldCount($arraySuper) - 1] . ".php";

    /* Verifica si un archivo existe y, si no, genera un error en la respuesta. */
    if (file_exists($filename)) {
        require $filename;
    } else {
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = '222';
        $response["AlertMessage2"] = '222';

    }

} catch (Exception $e) {


    /* maneja y documenta posibles errores en un sistema de pagos. */
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

    $codeProveedor = "";

    /* Se inicializa una variable de mensaje y un array de respuesta. */
    $messageProveedor = "";

    $response = array();

    switch ($code) {
        case 10011:
            /* Código que define un mensaje de error para sesión no válida en proveedor 400. */

            $codeProveedor = "400";
            $messageProveedor = "No such session.";

            break;
        case 21:
            /* Código que maneja el caso 21, asignando un mensaje de error por sesión inexistente. */

            $codeProveedor = "400";
            $messageProveedor = "No such session.";

            break;
        case 22:
            /* Código que maneja un caso específico, asignando un mensaje y un código de error. */

            $codeProveedor = "400";
            $messageProveedor = "No such session.";

            break;
        case 20001:
            /* asigna un mensaje de error para fondos insuficientes en un proveedor. */

            $codeProveedor = "402";
            $messageProveedor = "Insufficient funds.";

            break;

        case 0:
            /* asigna un mensaje de error general basado en el código proporcionado. */

            $codeProveedor = "402";
            $messageProveedor = "General Error. (" . $code . ")";

            break;
        case 27:
            /* maneja un error general asignando un código y mensaje específico. */

            $codeProveedor = "402";
            $messageProveedor = "General Error. (" . $code . ")";
            break;
        case 28:
            /* maneja un error general con un código específico para el proveedor. */


            $codeProveedor = "402";
            $messageProveedor = "General Error. (" . $code . ")";


            break;
        case 29:
            /* maneja un caso de error: transacción no encontrada para proveedor 402. */


            $codeProveedor = "402";
            $messageProveedor = "Transaction Not Found";

            break;

        case 10001:
            /* gestiona un caso específico marcando un proveedor como ya procesado. */


            $codeProveedor = 0;
            $messageProveedor = "Already processed";

            break;

        case 10004:
            /* maneja un caso de error declinado con un mensaje específico. */


            $codeProveedor = "REQUEST_DECLINED";
            $messageProveedor = "General Error. (" . $code . ")";

            break;
        case 10014:
            /* maneja un caso específico de error de proveedor. */


            $codeProveedor = "REQUEST_DECLINED";
            $messageProveedor = "General Error. (" . $code . ")";

            break;


        case 10010:
            /* gestiona un error general para el caso 10010, asignando mensajes específicos. */


            $codeProveedor = "REQUEST_DECLINED";
            $messageProveedor = "General Error. (" . $code . ")";


            break;


        case 20001:
            /* maneja un caso de error por fondos insuficientes. */


            $codeProveedor = "402";
            $messageProveedor = "Insufficients Funds";


            break;

        case 20002:
            /* muestra un caso en una estructura switch, pero no realiza ninguna acción. */


            break;

        case 20003:
            /* Código que maneja un caso de proveedor bloqueado, asignando valores de error específicos. */


            $codeProveedor = "ACCOUNT_BLOCKED";
            $messageProveedor = "ACCOUNT_BLOCKED";


            break;

        case 10005:
            /* Este fragmento representa un caso en una estructura switch sin ejecución. */


            break;

        case 50:
            /* Caso 50 define un mensaje de error para credenciales inválidas. */


            $messageProveedor = "El usuario o la clave son incorrectos. ";

            break;

        default:

            /* Se define un código de error y mensajes de error para notificar al proveedor. */
            $codeProveedor = 'UNKNOWN_ERROR';
            $messageProveedor = "Unexpected error. Reporte el codigo" . $code . ".";
            $messageProveedor = "Error en la solicitud2.";


            break;
    }


    /* Configura la respuesta de error para un intento de inicio de sesión fallido. */
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = 'Error en el login';
    //$response["AlertMessage"] = '-|' . $e->getMessage() . ' |-' . '(' . $e->getCode() . ')';
    $response["AlertMessage"] = $messageProveedor . ' (Report#' . $code . ")";
    // $response["AlertMessage"] = '-|' . "" . ' |-' . '(' . $e->getCode() . ')';
    $response["ModelErrors"] = [];


    /* Código asigna valores a un arreglo de respuesta, incluyendo código y mensaje. */
    $response['code'] = 12;
    $response['msg'] = "";

    $response['error_code'] = $code;
    //$response['error_msj'] = $e->getMessage();

    $response['msg'] = $messageProveedor;
}


/* Calcula la duración en horas, minutos y segundos desde un tiempo inicial. */
$end_time = microtime(true);
$duration = $end_time - $start_time;
$hours = (int)($duration / 60 / 60);
$minutes = (int)($duration / 60) - $hours * 60;

$seconds = (int)$duration - $hours * 60 * 60 - $minutes * 60;


/* ejecuta un script PHP si los segundos son mayores o iguales a 10. */
if ($seconds >= 10) {

    $body = file_get_contents('php://input');
    $data = '';
    if ($body != "") {
        $data = ($body);
    }

    //  exec("php -f /home/home2/backend/api/src/imports/Slack/message.php #slow-api' > /dev/null & ");
    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . base64_encode('TIME PARTNERAPI SLOW  "' . $seconds . '" s ' . $filename . ' D ' . $data) . "' '#slow-api' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '' '' > /dev/null & ");
}


/* verifica si la respuesta JSON no está vacía antes de imprimirla. */
if (json_encode($response) != "[]") {
    print_r(json_encode($response));

}

