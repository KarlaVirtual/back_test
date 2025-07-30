<?php
/**
 * Index de la api 'apipv'
 *
 * @param int $usuario : Parametros de entrada para el login (usuario id)
 * @param int $PartnerLogin : Parametros de entrada para el login (mandante id)
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en la operación.
 * - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 * - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 * - *ModelErrors*  (array): retorna array vacio
 * - *CodeError* (int): Devuelve el codigo de error.
 *  -*messageUrl* (string): Devuelve URl a direccionar del sitio online.
 *
 *
 * $response["messageUrl"] = 'doradobet';
 *
 * Objeto en caso de error:
 *
 *  $response["HasError"] = true;
 *  $response["AlertType"] = "danger";
 *  $response["AlertMessage"] = 'Error en el login';
 *  $response["AlertMessage"] = $messageProveedor . ' (Report#' . $code . ")";
 *  $response["ModelErrors"] = [];
 *  $response["CodeError"] = $code;
 *
 * @throws Exception Inusual Detected
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */


/**
 * Obtiene todos los encabezados HTTP de la solicitud actual.
 *
 * Esta función emula el comportamiento de la función `getallheaders()` en entornos donde no está disponible,
 * extrayendo los encabezados de la variable global `$_SERVER` y devolviéndolos en un array asociativo.
 * Los nombres de los encabezados son normalizados, convirtiendo los guiones bajos en espacios y capitalizando cada palabra.
 *
 * @return array Devuelve un array asociativo con los encabezados HTTP, donde la clave es el nombre del encabezado y el valor es el valor correspondiente.
 * @throws no No contiene manejo de excepciones.
 */

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


if (!function_exists('array_column')) {
    /**
     * Devuelve los valores de una columna de un array multidimensional.
     *
     * Esta función extrae los valores de una columna específica de un array multidimensional
     * y puede opcionalmente usar una clave de índice personalizada para reorganizar el array resultante.
     *
     * @param array|null $input Array multidimensional de entrada desde el cual se extraerán los valores.
     * @param mixed $columnKey Clave de la columna que se desea extraer. Puede ser un entero, una cadena o nulo.
     * @param mixed|null $indexKey (Opcional) Clave para usar como índice en el array resultante.
     *                              Puede ser un entero, una cadena, o nulo. Si se omite, se usará el índice numérico.
     * @return array|null Devuelve un array con los valores de la columna especificada. Si ocurre algún error, se devuelve `null` o `false` en caso de que la validación falle.
     * @throws no No contiene manejo de excepciones.
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        $argc = func_num_args();
        $params = func_get_args();

        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }

        if (!is_array($params[0])) {
            trigger_error(
                'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
                E_USER_WARNING
            );
            return null;
        }

        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string)$params[1] : null;

        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int)$params[2];
            } else {
                $paramsIndexKey = (string)$params[2];
            }
        }

        $resultArray = array();

        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;

            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string)$row[$paramsIndexKey];
            }

            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }

            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }

        }

        return $resultArray;
    }

}


/* establece configuraciones de entorno y almacena la referencia HTTP en una respuesta. */
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$_ENV["ENABLEDSETMAX_EXECUTION_TIME"] = '1';
$response["Alert"] = $_SERVER['HTTP_REFERER'];

$_ENV["enabledConnectionGlobal"] = 1;

$domainSession = ".virtualsoft.tech";


/* asigna diferentes sesiones según el dominio del referer. */
if (strpos($_SERVER['HTTP_REFERER'], "netabet.com.mx") !== FALSE) {
    $domainSession = ".netabet.com.mx";
}
if (strpos($_SERVER['HTTP_REFERER'], "localhost") !== FALSE) {
    $domainSession = ".app.localhost";
}


/* verifica el dominio de referencia y establece una variable de sesión correspondiente. */
if (strpos($_SERVER['HTTP_REFERER'], "ecuabet.com") !== FALSE) {
    $domainSession = ".ecuabet.com";
}

if (strpos($_SERVER['HTTP_REFERER'], "doradobet.com") !== FALSE) {
    $domainSession = ".doradobet.com";
}

/* Se establece una sesión segura con configuración de cookies para un dominio específico. */
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
use Backend\dto\ReporteDinamico;
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


/* obtiene el valor del encabezado 'X-Token' para su uso posterior. */
$headers = getallheaders();

$xtoken = $headers['X-Token'];

if ($xtoken == '') {
    $xtoken = $headers['x-token'];
}


/* establece un ID de sesión basado en un token o cookie existente. */
if ($xtoken != "" && $xtoken != null && $xtoken != 'null' && $xtoken != 'undefined') {
    session_id($xtoken);
} else {
    $SessionName = $_COOKIE['SessionName'];

    if ($SessionName != '' && $SessionName != null) {
        $xtoken = $SessionName;
        session_id($xtoken);

    }
}

include "includes.php";

/* Verifica condiciones de sesión y usuario, destruyendo sesión o estableciendo cookie. */
if ($xtoken == 'null' || $xtoken == 'undefined') {
    //session_destroy();

}

if ($_SESSION["usuario"] == "6290") {
    // setcookie("AdminID", "1");
    // print_r($_SERVER);

}


/* Configura cabeceras CORS y ajustes de memoria para solicitudes JSON. */
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token,rept');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');
ini_set('memory_limit', '-1');

/* Función que verifica la existencia de HTTP_ORIGIN en solicitudes, utilizando un arreglo permitido. */

/**
 * Verifica si el origen de la solicitud HTTP está permitido.
 *
 * Esta función revisa el encabezado `HTTP_ORIGIN` de la solicitud entrante y compara
 * si el origen está presente en la lista de orígenes permitidos proporcionados.
 * Si el origen coincide con uno de los permitidos, devuelve `true`, de lo contrario,
 * devuelve `false`.
 *
 * @param array $allowedOrigins Array que contiene los orígenes permitidos para la solicitud.
 * @return bool Devuelve `true` si el origen de la solicitud está permitido, de lo contrario `false`.
 * @throws no No contiene manejo de excepciones.
 */
function checkOrigin(array $allowedOrigins)
{
    // Verifica si HTTP_ORIGIN está configurado
    if (!isset($_SERVER['HTTP_ORIGIN'])) {
        return false;
    }


    /* Verifica si el origen de la solicitud está en la lista de permitidos. */
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
    'ganaplay'

);

/* Configura encabezados CORS según el origen de la solicitud HTTP recibida. */
if (checkOrigin($domainsEnabled)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

} else {
    header('Access-Control-Allow-Origin: virtualsoft.tech');
}


/* Configura duración y expiración de sesiones en PHP a 6 horas. */
header('Access-Control-Max-Age: 1728000');

# Session timeout, 2628000 sec = 1 month, 604800 = 1 week, 57600 = 16 hours, 86400 = 1 day
ini_set('session.gc_maxlifetime', 21600);
ini_set('session.cookie_lifetime', 21600);
# session.cache_expire is in minutes unlike the other settings above
ini_set('session.cache_expire', 21600);


/* configura encabezados HTTP para permitir acceso y manipulación de datos JSON. */
$dir_ipG2 = $_SERVER["HTTP_X_FORWARDED_FOR"];
if (strpos($dir_ipG2, '172.105.16.250') !== false) {
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: refresh,authorization,Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token,rept');
    header('Access-Control-Expose-Headers: Authentication');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
    header('Content-Type: application/json');
    ini_set('memory_limit', '-1');
    header('Content-type: application/json');


    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Private-Network: true");

}
//ini_set('session.gc_divisor', 50);

//ini_set('session.gc_probability', 100);
# Session timeout, 2628000 sec = 1 month, 604800 = 1 week, 57600 = 16 hours, 86400 = 1 day


/* Configura la duración de la sesión y cookies a 6 horas en PHP. */
ini_set('session.gc_maxlifetime', 21600);

ini_set('session.cookie_lifetime', 21600);

# session.cache_expire is in minutes unlike the other settings above

ini_set('session.cache_expire', 21600);


/* Activa el modo depuración y muestra errores si se cumple una condición específica. */
$debugFixed2 = '';


if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}


/* verifica un valor y ajusta la zona horaria en función de la sesión. */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
    $_ENV["debugFixed2"] = '1';
}

$timezone = $_SESSION["timezone"];
$timezone = -5 - ($timezone);

/* Código para manejar solicitudes, establecer URL y definir un array de valores de monedas. */
$timezone = 0;


$URI = $_SERVER["REQUEST_URI"];
$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';

$currencies_valor = array();


/* recibe datos JSON, los decodifica y prepara una respuesta. */
$params = file_get_contents('php://input');
$params = json_decode($params);
$response = array();
try {
    //syslog(LOG_WARNING, "APISOLIC :". file_get_contents('php://input').json_encode($_SERVER)  );

} catch (Exception $e) {
    /* Bloque para manejar excepciones en PHP, evitando interrupciones del programa. */


}

/* Código básico que define una clave de encriptación y maneja solicitudes OPTIONS. */
$ENCRYPTION_KEY = "D!@#$%^&*";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}
$dir_ipG = $_SERVER["HTTP_X_FORWARDED_FOR"];


/* Registra advertencias en log para direcciones IP específicas y URI. */
if (strpos($dir_ipG, '200.24.151.70') !== false) {
    syslog(LOG_WARNING, '200.24.151.70' . ' ' . $URI . ' ' . $_SESSION['usuario']);

}

if (strpos($dir_ipG, '23.95.230.144') !== false) {
    syslog(LOG_WARNING, '23.95.230.144' . ' ' . $URI . ' ' . $_SESSION['usuario']);

}


/* registra un aviso si se detecta una IP sospechosa y establece una cookie. */
if (strpos($dir_ipG, '177.234.221.22') !== false) {

    syslog(LOG_WARNING, "SOSPECHOSO :" . $_SESSION['usuario'] . " " . $dir_ipG . json_encode($_SERVER) . json_encode($_REQUEST));

}
try {
    if ($_COOKIE["cook"] == '') {
        setcookie("cook", time(), time() + 8640000, 'https://backofficeapi.virtualsoft.tech'); // expires after 60 seconds
    }

} catch (Exception $e) {
    /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución del script. */


}

//Establecemos variables globales para el uso en la api

/* detecta el dispositivo del usuario, asignando "Mobile" o "Desktop". */
$MobileDetect = new MobileDetect();
$Global_dispositivo = "Desktop";
$Global_soperativo = "";
$Global_sversion = "";
$Global_IP = (new ConfigurationEnvironment())->get_client_ip();

if ($MobileDetect->isMobile()) {
    $Global_dispositivo = "Mobile";
}


/* verifica si el dispositivo es una tableta o iOS y asigna un valor. */
if ($MobileDetect->isTablet()) {
    $Global_soperativo = "Tablet";
}

if ($MobileDetect->is('iOs')) {
    $Global_soperativo = "iOS";
}


/* Separa la cadena `$URI` en un array usando '/' como delimitador y obtiene el primer elemento. */
$arraySuper = explode("/", current(explode("?", $URI)));


try {

    /* Modifica un elemento de un arreglo y construye un nombre de archivo específico. */
    $arraySuper[oldCount($arraySuper) - 2] = ucfirst($arraySuper[oldCount($arraySuper) - 2]);

    $filename = 'cases/' . $arraySuper[oldCount($arraySuper) - 2] . "/" . $arraySuper[oldCount($arraySuper) - 1] . ".php";

    if ($filename == 'cases/Betshop/BetPhoneBetshop.php') {
        $filename = 'cases/BetShop/BetPhoneBetshop.php';
    }


    /* Código que verifica si la depuración está activada y muestra la sesión actual. */
    $validacionLogueado = false;

    if ($_ENV["debug"]) {
        print_r('SESSION');
        print_r($_SESSION);
    }

    /* Valida el acceso de usuarios y ajusta la zona horaria según el ID del usuario. */
    if ($_SESSION["logueado"] || $arraySuper[oldCount($arraySuper) - 1] == "CheckAuthentication" || $arraySuper[oldCount($arraySuper) - 1] == "uploadImage" || $arraySuper[oldCount($arraySuper) - 1] == "Login" || $arraySuper[oldCount($arraySuper) - 1] == "ImportBulkUsers" || $arraySuper[oldCount($arraySuper) - 1] == "GenerateHashFiles" || $arraySuper[oldCount($arraySuper) - 1] == "GeneraHashFilesBackend" || $arraySuper[oldCount($arraySuper) - 1] == "CloseDaySpecific" || $arraySuper[oldCount($arraySuper) - 1] == "LoginGoogle" || ($arraySuper[oldCount($arraySuper) - 2] == "Machine" && $arraySuper[oldCount($arraySuper) - 1] == "File") || ($arraySuper[oldCount($arraySuper) - 3] == "Machine" && $arraySuper[oldCount($arraySuper) - 2] == "files") || (strpos($URI, "Machine") !== false && strpos($URI, "files") !== false) || $arraySuper[oldCount($arraySuper) - 1] == "UpdatePartnerSettingsConfig" || $arraySuper[oldCount($arraySuper) - 1] == "ResetPassword" || $arraySuper[oldCount($arraySuper) - 1] == "ResetPasswordToken" || $arraySuper[oldCount($arraySuper) - 1] == "GetCurrencies") {
        $validacionLogueado = true;
    }
    if ($_SESSION["logueado"]) {


        if ($_SESSION["usuario"] == '4622507') {

            $_ENV["TIMEZONE"] = "-03:00";
        }
        if ($_SESSION["usuario"] == '2872586') {

            $_ENV["TIMEZONE"] = "-03:00";
        }
        if ($_SESSION["usuario"] == '2872598') {

            $_ENV["TIMEZONE"] = "-03:00";
        }
        if ($_SESSION["usuario"] == '61906') {

            $_ENV["TIMEZONE"] = "-06:00";
        }
    }


    /* Verifica condiciones y, si se cumplen, carga un script o genera un error. */
    if ($arraySuper[oldCount($arraySuper) - 3] == "BonusApi" || $arraySuper[oldCount($arraySuper) - 2] == "BonusApi") {

        if ($validacionLogueado || $arraySuper[oldCount($arraySuper) - 1] == "CreateTournamentMANUAL" || $arraySuper[oldCount($arraySuper) - 1] == "CreateBonusMANUAL" || $arraySuper[oldCount($arraySuper) - 1] == "CreateBonusMANUAL2" || $arraySuper[oldCount($arraySuper) - 1] == "CreateBonusFreeSpinMANUAL") {


            require '../bonusapi/index.php';

            exit();
        } else {
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = 'f';
            $response["CodeError"] = 20000;
            $response["SS"] = $_SERVER['SERVER_ADDR'];


        }

    }


    /* Verifica si una IP específica está presente en Global_IP y lanza excepciones. */
    if (strpos($Global_IP, '146.70.41.22') !== false || strpos($Global_IP, '172.105.16.250') !== false) {
        // throw new Exception("Inusual Detected", "12");

    } else {
        // throw new Exception("Inusual Detected", "11");

    }

    /* verifica un archivo y lanza una excepción si el estado es "BLOCKED". */
    try {
        $responseEnable = file_get_contents(__DIR__ . '/../../logSit/enabled');
    } catch (Exception $e) {
    }


    if ($responseEnable == 'BLOCKED') {
        throw new Exception("Inusual Detected", "11");
    }


    /* Valida condiciones en un array y requiere un archivo si son ciertas. */
    if ($arraySuper[oldCount($arraySuper) - 3] == "ApiMkt" || $arraySuper[oldCount($arraySuper) - 2] == "ApiMkt") {

        if ($validacionLogueado || $arraySuper[oldCount($arraySuper) - 1] == "CreateTournamentMANUAL" || $arraySuper[oldCount($arraySuper) - 1] == "CreateBonusMANUAL") {


            require '../apimkt/index.php';

            exit();
        } else {
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = 'f';
            $response["CodeError"] = 20000;


        }

    } elseif (file_exists($filename) && $validacionLogueado) {
        /* verifica condiciones de sesión y genera excepciones ante perfiles específicos. */


        if ($_SESSION["win_perfil"] == "USUONLINE") {

            // Finally, destroy the session.
            session_destroy();
            throw new Exception("Inusual Detected", "11");
        }
        if ($_SESSION["win_perfil"] == "USUONLINE") {
            throw new Exception("Inusual Detected", "11");
        }
        require $filename;

        if ($_SESSION["win_perfil"] == "USUONLINE") {
            throw new Exception("Inusual Detected", "11");
        }
    } elseif (!file_exists($filename)) {
        /* verifica si un archivo existe y maneja errores si no. */

        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = 'f';
        $response["AlertMessage2"] = $Global_IP;

    } else {
        /* Código que gestiona errores, configurando respuestas y mensajes de alerta en formato JSON. */

        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = 'f';
        $response["CodeError"] = 20000;
        $response["SS"] = $_SERVER['SERVER_ADDR'];

    }


    if (false) {
        switch ($arraySuper[oldCount($arraySuper) - 2] . "/" . $arraySuper[oldCount($arraySuper) - 1]) {


            /**
             * Account/Login
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Account/Login':
                /* Estructura de control 'case' en un switch para la ruta de inicio de sesión de cuenta. */


                break;

            /**
             * Account/Logout
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Account/Logout':
                /* Código que maneja la opción de cierre de sesión en un sistema. */


                break;


            /**
             * Client/GetTokenBetting
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Client/GetTokenBetting':
                /* Código que maneja una solicitud para obtener un token de apuestas, sin implementación. */


                break;

            /**
             * Account/LoginGoogle
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Account/LoginGoogle':
                /* Caso en un switch que maneja la acción de inicio de sesión con Google. */


                break;

            /**
             * Account/CheckUserLoginPassword
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Account/CheckUserLoginPassword':
                /* Es un fragmento de código que maneja un caso específico sin acciones definidas. */


                break;


            /**
             * Agent/GetAgentPtGroups
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentPtGroups":
                /* representa un caso de switch que no ejecuta ninguna acción. */


                break;

            /**
             * Agent/GetAgentCommissionGroups
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentCommissionGroups":
                /* Código que define un caso para obtener grupos de comisiones de agentes, sin implementación. */


                break;

            /**
             * Agent/GetPtGroupForAgent
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetPtGroupForAgent":
                /* Código para manejar una ruta específica sin ninguna acción implementada. */


                break;

            /**
             * Agent/GetAgentGroupById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentGroupById":
                /* Es un fragmento de código que maneja un caso específico en un switch. */


                break;


            /**
             * Agent/GetAgentGroupItems
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentGroupItems":
                /* define un caso en una estructura switch, pero no ejecuta ninguna acción. */


                break;

            /**
             * AdminUser/SaveAdminUser
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "AdminUser/SaveAdminUser":
                /* representa un caso en un switch que no realiza acciones. */


                break;


            /**
             * Agent/SaveAgent22222
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/SaveAgent22222":
                /* Caso en un código que finaliza sin ejecutar acciones específicas. */


                break;


            /**
             * Agent/SaveAgent2
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/SaveAgent2":
                /* representa un caso en un switch que no realiza ninguna acción. */


                break;

            /**
             * Agent/SaveAgentGroupItem
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/SaveAgentGroupItem":
                /* es un caso vacío en una estructura switch, sin acciones definidas. */


                break;


            /**
             * Agent/GetAgentComissionItems
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentComissionItems":
                /* representa un caso en una estructura de control "switch" sin implementación. */


                break;

            /**
             * BetShop/GetAgentComissionItems
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/GetAgentComissionItems":
                /* es un caso vacío en un switch para "GetAgentComissionItems". */


                break;

            /**
             * BetShop/SaveComissions
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/SaveComissions":
                /* Se prepara un caso para guardar comisiones en una estructura de control sin contenido. */


                break;

            /**
             * Agent/SaveAgentCommissionGroups
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/SaveAgentCommissionGroups":
                /* define un caso en un switch, pero no ejecuta ninguna acción. */


                break;

            /**
             * Agent/MakePlayerTransfers
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/MakePlayerTransfers":
                /* Estructura de control que verifica un caso específico para transferencias de jugadores. */


                break;


            /**
             * Agent/MakeAgentTransfers
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/MakeAgentTransfers":
                /* fragmento es un caso vacío en una estructura de control switch. */


                break;


            /**
             * Agent/MakeAgentTransfersDeposit
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/MakeAgentTransfersDeposit":
                /* define un caso sin implementación para transferencias de agentes. */


                break;

            /**
             * Agent/MakeAgentTransfersGame
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/MakeAgentTransfersGame":
                /* es un caso sin acción en una estructura de control switch. */


                break;

            /**
             * Client/MakeDeposit
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/MakeDeposit":
                /* define un caso para hacer un depósito, pero no realiza ninguna acción. */


                break;


            /**
             * UserManagement/GetUserSecurityL
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetUserSecurityL22":
                /* define un caso específico en un switch, pero no realiza ninguna acción. */


                break;


            case "UserManagement/GetUserSecurityL":
                /* Código vacío en un caso de manejo de usuarios, sin funcionalidad implementada. */


                break;

            /**
             * UserManagement/GetUserNotes
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetUserNotes":
                /* define un caso para manejo de notas de usuario, pero no ejecuta ninguna acción. */


                break;

            /**
             * UserManagement/SaveUserSecurity
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/SaveUserSecurity22":
                /* parece ser parte de un sistema de gestión de usuarios, específico para seguridad. */


                break;

            case "UserManagement/SaveUserSecurity":
                /* Caso de manejo para guardar la seguridad del usuario, sin implementación actualmente. */


                break;

            /**
             * UserManagement/SaveUserNote
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/SaveUserNote":
                /* Código incompleto de un caso para guardar notas de usuario sin implementación. */


                break;

            /**
             * OddsFeed/SaveSport
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/SaveSport":
                /* define un caso "OddsFeed/SaveSport" sin implementación. */


                break;

            /**
             * OddsFeed/SaveRegion
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/SaveRegion":
                /* Un caso de estructura switch que no realiza ninguna acción en "SaveRegion". */


                break;

            /**
             * OddsFeed/SaveCompetition
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/SaveCompetition":
                /* Código para manejar un caso "OddsFeed/SaveCompetition" sin acciones definidas. */


                break;

            /**
             * OddsFeed/SaveMatch
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/SaveMatch":
                /* es un caso vacío que se encuentra dentro de una estructura de control switch. */


                break;


            /**
             * OddsFeed/SaveMatchMarket
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/SaveMatchMarket":
                /* representa un caso vacío en un switch para "SaveMatchMarket". */


                break;


            /**
             * OddsFeed/SaveMatchDetail
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/SaveMatchDetail":
                /* Código para manejar un caso específico en una estructura de control, sin acciones definidas. */


                break;

            /**
             * OddsFeed/SavePartnerPrices
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/SavePartnerPrices":
                /* define un caso en un switch, pero no realiza ninguna acción. */


                break;


            /**
             * OddsFeed/SaveMarketType
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/SaveMarketType":
                /* Es un fragmento de código que define un caso en una estructura switch. */


                break;

            /**
             * OddsFeed/SaveMarketTypeDetail
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/SaveMarketTypeDetail":
                /* Case para manejar una acción relacionada con "OddsFeed/SaveMarketTypeDetail", actualmente sin implementación. */


                break;

            /**
             * OddsFeed/SavePartnerMatchBookings
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/SavePartnerMatchBookings":
                /* Manejo de caso para guardar reservas de partidos de socios, sin implementación. */


                break;

            /**
             * OddsFeed/GetCurrentPartnerInfo
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetCurrentPartnerInfo" :
                /* Maneja la solicitud para obtener información del socio actual, sin implementación. */


                break;

            /**
             * OddsFeed/GetMarketGroups
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetMarketGroups":
                /* representa un caso en un switch, sin implementación actual. */


                break;

            /**
             * OddsFeed/GetPartnerMarketTypeBookingss
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetPartnerMarketTypeBookings":
                /* Código que maneja una solicitud específica sin realizar ninguna acción. */


                break;

            /**
             * OddsFeed/GetMarketTypeItems
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetMarketTypeItems":
                /* Es un fragmento de código que define un caso en una estructura switch. */


                break;


            /**
             * OddsFeed/GetMarketTypeGroupItems
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetMarketTypeGroupItems":
                /* indica un caso en un switch, pero no ejecuta ninguna acción. */


                break;

            /**
             * Competitors/CreateCompetitors
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Competitors/CreateCompetitors":
                /* Es un fragmento de código que maneja un caso específico en una estructura switch. */


                break;


            /**
             * Competitors/GetCompetitors
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Competitors/GetCompetitors":
                /* define un caso en un switch, pero no realiza ninguna acción. */


                break;

            /**
             * OddsFeed/GetPartnerSportBookings
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetPartnerSportBookings":
                /* es un fragmento de un caso en un switch que no realiza ninguna acción. */


                break;

            /**
             * OddsFeed/GetSportBookingById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetSportBookingById":
                /* gestiona un caso particular en un switch, pero no ejecuta ninguna acción. */


                break;

            /**
             * GetRegionBookingById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "GetRegionBookingById":
                /* Estructura de control que maneja una opción para obtener una reserva por ID. */


                break;

            /**
             * OddsFeed/GetPartnerRegionBookings
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetPartnerRegionBookings":
                /* muestra un caso vacío para manejar "GetPartnerRegionBookings" en un switch. */


                break;

            /**
             * OddsFeed/GetPartnerCompetitionBookings
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetPartnerCompetitionBookings":
                /* Un caso en un switch que no realiza ninguna acción para esa opción específica. */


                break;

            /**
             * OddsFeed/GetLiveMatchBookingsForCalendar
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetLiveMatchBookingsForCalendar":
                /* Código que maneja un caso específico, pero no ejecuta ninguna acción en este fragmento. */


                break;

            /**
             * OddsFeed/GetMatchMarketSelections
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetMatchMarketSelections":
                /* Es un fragmento de código que maneja una opción sin implementar lógica. */


                break;

            /**
             * OddsFeed/GetMatchDetails
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetMatchDetails":
                /* Un caso de un switch que no realiza ninguna acción para "GetMatchDetails". */


                break;

            /**
             * OddsFeed/GetCompetitionOverview
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetCompetitionOverview":
                /* Código de un caso en un switch, con acción vacía para "GetCompetitionOverview". */


                break;

            /**
             * OddsFeed/GetMatch
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetMatch":
                /* Código que define un caso en un switch para "OddsFeed/GetMatch", sin acción definida. */


                break;

            /**
             * OddsFeed/GetMatchBookings
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetMatchBookings":
                /* Caso para manejar solicitudes de "GetMatchBookings", sin implementación específica. */


                break;

            /**
             * OddsFeed/GetTeamsBookings
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "OddsFeed/GetTeamsBookings":
                /* Es un fragmento de código que maneja un caso específico en una estructura de control. */


                break;

            /**
             * Sport/GetSports
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Sport/GetSports":
                /* es un caso en un switch que no ejecuta ninguna acción. */


                break;

            /**
             * Sport/GetMarketTypes
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Sport/GetMarketTypes":
                /* es parte de un sistema que gestiona tipos de mercados deportivos. */


                break;


            /**
             * Sport/GetCompetitions
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Sport/GetCompetitions":
                /* es un caso vacío en una estructura de control switch. */


                break;

            /**
             * Sport/GetMatches
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Sport/GetMatches":
                /* Caso en un switch que maneja la ruta "Sport/GetMatches"; no realiza ninguna acción. */


                break;


            /**
             * ft/Sports
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "ft/Sports":
                /* representa un caso vacío en una estructura switch. */


                break;

            /**
             * Account/GetMenus
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Account/GetMenus':
                /* es un fragmento de un switch-case que no ejecuta acción definida. */


                break;


            /**
             * Menus/GetMenuUser
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Menus/GetMenuUser':
                /* Condicional que verifica la acción 'GetMenuUser' en un sistema de menús. */


                break;

            /**
             * Account/CheckAuthentication
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Account/CheckAuthentication':
                /* es un caso en un switch que no ejecuta ninguna acción. */


                break;

            /**
             * Account/CheckForLogin
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Account/CheckForLogin':
                /* es un caso vacío en una estructura de control switch. */


                break;

            /**
             * Account/CheckSecurityCode
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Account/CheckSecurityCode22":
                /* Es un caso de un switch que no ejecuta ninguna acción. */


                break;


            case "Account/CheckSecurityCode":
                /* maneja un caso específico sin acciones definidas (vacio). */


                break;

            /**
             * Setting/GetPartnerSettings
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Setting/GetPartnerSettings":
                /* define un caso en un switch, pero no ejecuta ninguna acción. */


                break;

            /**
             * setting/GetSysDate
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'setting/GetSysDate':
                /* define un caso de un switch, sin acciones definidas. */


                break;

            /**
             * setting/saveSetting
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'setting/saveSetting':
                /* es un fragmento de un caso de un switch sin funcionalidad implementada. */


                break;


            case 'setting/SavePartnerUser':
                /* es un caso en un switch, sin implementación. */


                break;

            /**
             * setting/saveSetting2
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'setting/saveSetting2':
                /* inicia un caso para guardar configuraciones, pero no realiza ninguna acción. */


                break;

            /**
             * setting/GetSetting
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'setting/GetSetting':
                /* Se trata de una estructura de control case en un switch para obtener configuraciones. */


                break;


            /**
             * Setting/UpdatePartnerSettings
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Setting/UpdatePartnerSettings":
                /* es un caso vacío para actualizar la configuración de un socio. */


                break;


            /**
             * Admin/GetUsersSearch
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Admin/GetUsersSearch":
                /* es un fragmento de un caso en un switch, está incompleto. */


                break;

            /**
             * Client/UpdateClients
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/UpdateClients":
                /* Es un caso de una estructura de control que no realiza acción alguna. */


                break;


            /**
             * Client/GetClients
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClients":
                /* Es un fragmento de código que maneja una opción en un switch. */


                break;

            /**
             * vapi/GetClients
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "vapi/GetClients":
                /* es un caso vacío en un switch, relacionado con "GetClients". */


                break;


            /**
             * GetUsers
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'GetUsers':
                /* define un caso para gestionar la acción 'GetUsers' sin implementación. */


                break;

            /**
             * Setting/GetReportColumns
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Setting/GetReportColumns':
                /* Código de un caso en un switch que no realiza ninguna acción. */


                break;

            /**
             * Setting/GetFilters
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Setting/GetFilters':
                /* Código incompleto para manejar una acción 'GetFilters' en una estructura de caso. */


                break;

            /**
             * Client/GetClientById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Client/GetClientById':
                /* representa un caso vacío para obtener un cliente por su ID en una estructura switch. */


                break;

            /**
             * Client/GetClientSpById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Client/GetClientSpById':
                /* Código de un caso en un switch para obtener cliente por ID, sin implementación. */


                break;

            /**
             * Client/GetClientSpMyInfo
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Client/GetClientSpMyInfo':
                /* Ese código es un fragmento de un switch-case en programación, específico de un cliente. */


                break;

            /**
             * Agent/GetAgentById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Agent/GetAgentById':
                /* Código que define un case para obtener un agente por su ID, sin implementación. */


                break;

            /**
             * Agent/GetAgentAccount
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Agent/GetAgentAccount':
                /* Un caso vacío en un switch para manejar la ruta 'Agent/GetAgentAccount'. */


                break;


            /**
             * Agent/GetAgentById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Agent/GetAgentById':
                /* Es un fragmento de código que maneja un caso específico sin implementar lógica. */


                break;

            /**
             * Agent/GetAgentById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentById":
                /* Caso de un switch que no contiene acción para "GetAgentById". */


                break;

            /**
             * Agent/SetStateValidate
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/SetStateValidate":
                /* define un caso en un switch, sin acciones dentro del bloque. */


                break;


            /**
             * Agent/GetMarketingStats2
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetMarketingStats2":
                /* maneja un caso específico en una estructura de control sin implementar acción. */


                break;


            /**
             * Agent/SearchAgent
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/SearchAgent":
                /* Es un bloque de código que maneja una acción relacionada con la búsqueda de agentes. */


                break;


            /**
             * Agent/GetAgentsTwoLevels
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentsTwoLevels":
                /* muestra un fragmento de una estructura de control para una ruta específica. */


                break;

            /**
             * Agent/GetAgentsTwoLevels2
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentsTwoLevels2":
                /* indica un caso en un switch que no realiza ninguna acción. */


                break;


            /**
             * Agent/GetAgentDownStreamAgents
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentDownStreamAgents":
                /* define un caso en un switch, pero no realiza ninguna acción. */


                break;


            /**
             * Agent/GetAgentsWithBalance
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentsWithBalance":
                /* Muestra un fragmento de código que gestiona la ruta "GetAgentsWithBalance". */


                break;

            /**
             * BetShop/GetBetShopById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/GetBetShopById":
                /* parece ser parte de un bloque de switch sin lógica implementada. */


                break;

            /**
             * BetShop/GetCashDesks
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/GetCashDesks":
                /* Es un fragmento de código que maneja un caso específico sin realizar acción. */


                break;

            /**
             * BetShop/GetCashDesks2
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/GetCashDesks2":
                /* maneja un caso específico, pero no realiza ninguna acción en este caso. */


                break;

            /**
             * BetShop/SaveBetShop
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/SaveBetShop":
                /* es un fragmento de un switch para manejar una opción específica. */


                break;

            /**
             * BetShop/SaveMachine
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/SaveMachine":
                /* muestra un caso de un switch para "BetShop/SaveMachine" que no realiza acciones. */


                break;


            /**
             * Configuration/ChangeMyPassword
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Configuration/ChangeMyPassword":
                /* es un bloque vacío para una acción de cambiar contraseña en configuración. */


                break;

            /**
             * betShop/ChangePassword
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "betShop/ChangePassword":
                /* Es un fragmento de código que define una acción para cambiar contraseña en una apuesta. */


                break;

            /**
             * AgentList/ChangePassword
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "AgentList/ChangePassword":
                /* es un fragmento de un switch-case en programación, sin acción definida. */


                break;


            /**
             * Agent/SaveAgent
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/SaveAgent":
                /* corresponde a una estructura de control que no ejecuta ninguna acción. */


                break;

            /**
             * BetShop/SaveCashDesks
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/SaveCashDesks":
                /* representa un caso vacío en un switch relacionado con BetShop. */


                break;

            /**
             * BetShop/UpdateCashDesks
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/UpdateCashDesks":
                /* Caso de un switch que no realiza ninguna acción para "BetShop/UpdateCashDesks". */


                break;

            /**
             * BetShop/SaveAdvertising
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/SaveAdvertising":
                /* define un caso en una estructura de control, pero no realiza ninguna acción. */


                break;

            /**
             * Machine/SaveAdvertising
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Machine/SaveAdvertising":
                /* representa un caso en una estructura switch que no ejecuta ninguna acción. */


                break;

            /**
             * BetShop/GetAdvertising
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/GetAdvertising":
                /* representa un caso en un switch, sin acciones definidas. */


                break;


            /**
             * Machine/GetAdvertising
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Machine/GetAdvertising":
                /* es un caso vacío en un bloque switch para "Machine/GetAdvertising". */


                break;

            /**
             * UserManagement/UpdateUserDetails
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/UpdateUserDetails":
                /* representa un caso vacío en un switch relacionado con la gestión de usuarios. */


                break;

            /**
             * UserManagement/CreateUserAlert
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/CreateUserAlert":
                /* Manejo de un caso para crear alerta de usuario, sin implementación. */


                break;

            /**
             * UserManagement/GetPartnerAdminUsers
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetPartnerAdminUsers":
                /* es parte de un switch que maneja la acción de obtener usuarios administradores. */


                break;

            /**
             * UserManagement/GetAdminUser
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetAdminUser":
                /* incluye un caso para gestionar la obtención de un usuario administrador. */


                require 'cases/UserManagement-GetAdminUser.php';

                break;


            /**
             * UserManagement/GetBlockedUser
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetBlockedUser":
                /* muestra un caso vacío para gestionar usuarios bloqueados en un sistema. */


                break;


            /**
             * UserManagement/SaveUserBlocked
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/SaveUserBlocked":
                /* Caso en un switch para manejar una acción de bloqueo de usuario. Sin implementación. */


                break;

            /**
             * UserManagement/GetUsersAffiliates
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetUsersAffiliates":
                /* indica un caso en un switch que no realiza acciones. */


                break;

            /**
             * UserManagement/GetContigencia
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetContigencia":
                /* Es un caso vacío en un switch, relacionado con la gestión de usuarios. */


                break;

            /**
             * UserManagement/GetPerfil
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetPerfil":
                /* representa un caso de gestión de perfiles de usuario, sin implementación. */


                break;

            /**
             * UserManagement/GetUsuarioPerfil
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetUsuarioPerfil":
                /* Código para manejar una solicitud de perfil de usuario, sin implementar lógica aún. */


                break;


            /**
             * UserManagement/GetRegisteredDocuments
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetRegisteredDocuments":
                /* es un fragmento de un caso vacío en un switch sobre gestión de usuarios. */


                break;

            /**
             * UserManagement/GetPagoPremio
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetPagoPremio":
                /* Código que define un caso sin acción en la gestión de pagos de premio de usuarios. */


                break;

            /**
             * UserManagement/GetPagoNotaRetiro
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetPagoNotaRetiro":
                /* Un case que responde a una ruta de gestión de pagos, sin implementación. */


                break;

            /**
             * UserManagement/GetUserAlerts
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetUserAlerts":
                /* define un caso en un switch relacionado con alertas de usuario. */


                break;


            /**
             * UserManagement/GetRecargaCredito
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetRecargaCredito":
                /* Caso vacío en una estructura de control para manejo de recarga de crédito de usuario. */


                break;


            /**
             * UserManagement/updateStateUserAlert
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/updateStateUserAlert":
                /* Es un caso de un switch para manejar la actualización del estado de alertas de usuario. */


                break;


            case "PromotionalCodes/GetCodes":
                /* Es un fragmento de código que maneja una ruta para obtener códigos promocionales. */


                break;


            case "PromotionalCodes/SaveCode":
                /* Es un caso de un switch que no realiza acciones para "SaveCode". */


                break;


            case "Management/GetManagementContact":
                /* Un caso vacío para manejar la ruta "Management/GetManagementContact" en un switch. */


                break;


            case "Management/GetManagementContactDetails":
                /* Código vacío espera una acción específica para obtener detalles de contacto de gestión. */


                break;

            case "ManagementContact/EndManagement":
                /* maneja una opción de caso sin acción específica. */


                break;

            case "Management/ResponseManagementContact":
                /* representa un caso dentro de una estructura de control que no hace nada. */


                break;

            case "Accounting/GetTypesCenterPositionSelect":
                /* es un fragmento de un switch para la gestión de tipos de posiciones contables. */


                break;

            case "Accounting/GetEmployeesSelect":
                /* es un case vacío en un switch relacionado con empleados de contabilidad. */


                break;

            case "Accounting/GetAreaSelect":
                /* Estructura de un caso en un switch, sin operación definida. */


                break;

            case "Accounting/GetPositionSelect":
                /* parece ser parte de una estructura switch-case para manejar una ruta específica. */


                break;


            case "Accounting/GetAccountsSelect":
                /* es un caso vacío en una estructura de control switch. */


                break;


            case "Accounting/GetConceptsSelect":
                /* El fragmento de código parece ser parte de un switch case vacío en programación. */


                break;


            case "Accounting/GetConceptsIncomesSelect":
                /* es un fragmento de un switch que no realiza ninguna acción. */


                break;


            case "Accounting/GetConceptsExpensesSelect":
                /* es un caso vacío en una estructura switch para "Accounting/GetConceptsExpensesSelect". */


                break;


            case "Accounting/GetProvidersSelect":
                /* representa un caso en un switch relacionado con proveedores de contabilidad. */


                break;


            case "Accounting/GetTypesCreditCardsSelect":
                /* es un fragmento de un switch que gestiona tipos de tarjetas de crédito. */


                break;


            case "Accounting/GetAreas":
                /* Código que maneja una solicitud específica relacionada con áreas contables, pero no hace nada. */


                break;

            case "Accounting/GetPositions":
                /* Es un fragmento de código que maneja una opción de "Contabilidad" sin acción. */


                break;

            /**
             * Accounting/GetTypes
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Accounting/GetTypes":
                /* muestra un caso en un switch para "Accounting/GetTypes", sin acciones definidas. */


                break;

            case "Accounting/GetCostCenter":
                /* Código que maneja un caso específico de "GetCostCenter" en contabilidad, sin implementación. */


                break;

            case "Accounting/GetEmployees":
                /* Manejo de una ruta en un switch para obtener empleados contables, sin implementación. */


                break;

            case "Accounting/GetProvidersThird":
                /* representa una estructura de control que no ejecuta acciones en esa opción. */


                break;


            case "Accounting/GetAccounts":
                /* muestra un caso vacío para la ruta "Accounting/GetAccounts". */


                break;

            case "Accounting/GetConcepts":
                /* define un caso para manejar "Accounting/GetConcepts", pero no realiza ninguna acción. */


                break;

            case "Accounting/GetProductsThird":
                /* es un segmento vacío para gestionar un caso específico en un switch. */


                break;


            case "Accounting/GetInitialMoney":
                /* define un caso en un switch pero no realiza ninguna acción. */


                break;


            case "Accounting/GetProductsByBetShop":
                /* Caso de código vacío para la solicitud de productos por casa de apuestas. */


                break;


            case "Accounting/GetIncomes":
                /* representa un caso en un switch relacionado con ingresos contables. */


                break;


            case "Accounting/GetExpenses":
                /* Estructura de código para manejar casos específicos en una aplicación contable. */


                break;

            case "Accounting/GetExpensesToday":
                /* es un caso vacío en una estructura de control. */


                break;

            case "Accounting/GetIncomesToday":
                /* es un caso vacío en un switch para obtener ingresos del día. */


                break;


            case "Accounting/GetIncomesCreditCardsToday":
                /* Caso en un switch que no realiza ninguna acción para ingresos de tarjetas hoy. */


                break;


            case "Accounting/GetProductsPlatformByBetShopToday":
                /* es un caso vacío en un switch para la obtención de productos hoy. */


                break;


            case "Accounting/GetSquareDayReport2":
                /* Es un fragmento de código que maneja una ruta específica en un sistema. */


                break;


            case "Accounting/GetDetailsSquareDay":
                /* Código que define un caso para manejar detalles del día cuadrado en contabilidad. */


                break;

            case "Accounting/GetDetailsSquareDayPDF":
                /* Estructura de un caso en un switch, sin implementación. */


                break;


            case "Accounting/GetDetailsIncomePDF":
                /* Código vacío para la ruta "Accounting/GetDetailsIncomePDF", sin funcionalidad implementada. */


                break;


            case "Accounting/GetDetailsExpensePDF":
                /* Este es un fragmento de código que maneja una acción específica relacionada con gastos. */


                break;

            /**
             * Accounting/SaveType
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Accounting/SaveType":
                /* Código que maneja una opción de guardar en contabilidad, pero no realiza ninguna acción. */


                break;

            case "Accounting/SavePosition":
                /* Código que establece un caso vacío para la opción "Accounting/SavePosition" en un switch. */


                break;

            case "Accounting/SaveArea":
                /* parece ser parte de un switch case para manejar una opción de contabilidad. */


                break;

            case "Accounting/SaveEmployee":
                /* Código muestra un caso vacío en un switch para "Guardar Empleado" en contabilidad. */


                break;

            case "Accounting/SaveCostCenter":
                /* es un caso vacío en una declaración switch que gestiona 'SaveCostCenter'. */


                break;

            case "Accounting/SaveProvidersThird":
                /* Fragmento de código que maneja un caso específico en una estructura de control. */


                break;

            case "Accounting/SaveAccount":
                /* es un caso vacío para una opción en una estructura switch. */


                break;

            case "Accounting/SaveConcept":
                /* Caso en un switch que no realiza ninguna acción para "Accounting/SaveConcept". */


                break;


            case "Accounting/SaveProductsThird":
                /* gestiona un caso para "Accounting/SaveProductsThird", sin implementación específica. */


                break;


            case "Accounting/SaveProductThirdByBetshop":
                /* Se trata de un caso en programación para guardar productos en un sistema contable. */


                break;

            case "Accounting/SaveExpenses":
                /* es un caso vacío para guardar gastos en contabilidad. */


                break;


            case "Accounting/SaveIncome":
                /* representa un case vacío en un switch para guardar ingresos en contabilidad. */


                break;


            case "Accounting/SaveBeginDate":
                /* muestra una estructura de control sin acciones específicas dentro del caso. */


                break;


            case "Accounting/SaveIncomeCreditCards":
                /* Caso en un switch que maneja la opción "Guardar Ingresos Tarjetas de Crédito". */


                break;


            case "Accounting/SaveCloseBoxBetShop":
                /* define un caso en un switch, pero no ejecuta ninguna acción. */


                break;


            /**
             * UserManagement/GetTypesAlert
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetTypesAlert":
                /* Código vacío en un caso de gestión de usuarios relacionado con tipos de alerta. */


                break;

            /* $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];


        $array = [];



        array_push($final, $array);


        $response["Data"] = $final;

        break;*/

            /**
             * UserManagement/GetPartnerAdminUserById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetPartnerAdminUserById":
                /* Código de un case en un switch para manejar la obtención de un usuario administrativo. */


                break;

            /**
             * GetUserChangeHistoryTypes
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "GetUserChangeHistoryTypes":
                /* define un caso sin acciones para "GetUserChangeHistoryTypes". */


                break;

            /**
             * UserManagement/GetPartnerAdminUsersByFilter
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetPartnerAdminUsersByFilter":
                /* maneja una ruta de usuario; actualmente no realiza ninguna acción. */


                break;

            /**
             * UserManagement/GetPartnerAdminUserById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetPartnerAdminUserById":
                /* Código que maneja la acción de obtener un usuario administrador asociado a un socio. */


                break;

            /**
             * BetShop/GetCashDeskById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/GetCashDeskById";


                break;

            /**
             * BetShop/GetBetShops
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/GetBetShops":
                /* maneja una solicitud para obtener información sobre casas de apuestas. */


                $MaxRows = $_REQUEST["count"];


                break;


            /**
             * BetShop/GetMachines
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/GetMachines":
                /* maneja una opción "GetMachines" en un caso sin acciones definidas. */


                break;

            /**
             * BetShop/GetBetShopsCompetence
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/GetBetShopsCompetence":
                /* Es un fragmento de código que maneja un caso específico en un switch. */


                break;

            /**
             * BetShop/GetBetShopsCompetenceMap
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/GetBetShopsCompetenceMap":
                /* es un caso vacío en una estructura de control switch. */


                break;

            case "BetShop/SaveBetshopCompetence2":
                /* Código de control de flujo sin acción, relacionado con "SaveBetshopCompetence2". */


                break;

            /**
             * BetShop/SaveBetshopCompetence
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "BetShop/SaveBetshopCompetence":
                /* es una estructura de control que no ejecuta acciones en ese caso específico. */


                break;

            /**
             * Report/GetProducts
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/GetProducts":
                /* asigna un nuevo objeto de la clase Producto a una variable. */


                $Producto = new Producto();

                break;

            /**
             * Report/GetProductProviders
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/GetProductProviders":
                /* indica un caso en un switch que no realiza ninguna acción. */


                break;

            /**
             * Providers/GetProductProviders
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Providers/GetProductProviders":
                /* Se trata de un caso vacío en un switch, relacionado con proveedores de productos. */


                break;

            /**
             * Client/Segments
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Client/Segments':
                /* Se identifica un caso en un switch que no realiza acciones y se termina. */


                break;

            case 'BalanceHistory/GetBalanceHistory':
                /* Código para manejar una acción de obtener historial de saldo, sin implementación. */


                break;


            case 'BalanceHistory/GetBalanceMovement':
                /* muestra un caso de un switch sin acción ejecutable. */


                break;


            /**
             * Report/GetCasinoGamesReport
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Report/GetCasinoGamesReport':
                /* Código para manejar una ruta específica, pero no realiza ninguna acción actualmente. */


                break;


            /**
             * Report/getRegisteredAutoexclusion
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/getRegisteredAutoexclusion":
                /* maneja un caso específico, pero no realiza ninguna acción dentro del bloque. */


                break;

            /**
             * Report/GetCasinoGamesAggregatorReportDetail
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Report/GetCasinoGamesAggregatorReportDetail':
                /* es un caso de un switch que no realiza ninguna acción. */


                break;


            /**
             * Report/GetCasinoGamesReportDetail
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case 'Report/GetCasinoGamesReportDetail':
                /* es un caso para manejar una solicitud específica en un switch-case. */


                break;


            /**
             * Report/GetPremiosPendientes
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/GetPremiosPendientes":
                /* es un caso vacío en un switch relacionado con premios pendientes. */


                break;

            /**
             * Report/GetBetHistory
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/GetBetHistory":
                /* Código que define un caso para obtener el historial de apuestas, pero no realiza acciones. */


                break;


            /**
             * Report/GetBetHistory2
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/GetBetHistory2":
                /* representa una estructura de control que maneja la ruta "GetBetHistory2". */


                break;

            /**
             * Report/GetBetHistoryDetail
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/GetBetHistoryDetail":
                /* Es un fragmento de código que indica un caso sin implementación para obtener historial de apuestas. */


                break;

            /**
             * Report/GetBetHistoryTransactions
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/GetBetHistoryTransactions":
                /* gestiona un caso para obtener el historial de transacciones de apuestas. */


                break;

            /**
             * Client/GetClientCasinoGames
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientCasinoGames":
                /* maneja un caso específico en una estructura de control, pero no ejecuta acciones. */


                break;

            /**
             * Client/GetLogins
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetLogins":
                /* es un fragmento de un switch que maneja la opción "GetLogins". */


                break;


            /**
             * UserManagement/GetChangeHistory
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetChangeHistory":
                /* define un caso en una estructura de control para gestionar cambios de usuario. */


                break;


            /**
             * UserManagement/GetUserLogs
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetUserLogs":
                /* Código para manejar la ruta de obtención de registros de usuario, actualmente vacío. */


                break;

            /**
             * Security/UpdateLogs
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Security/UpdateLogs":
                /* muestra un caso vacío para "Security/UpdateLogs" en una estructura case. */


                break;


            /**
             * Security/UpdateDNI
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Security/UpdateDNI":
                /* Se refiere a un caso en un switch para actualizar DNI, sin acción. */


                break;

            /**
             * UserManagement/ApproveUserLog
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/ApproveUserLog":
                /* Caso vacío en la gestión de usuarios, probablemente para aprobar registros de usuarios. */


                break;

            /**
             * UserManagement/DeclineUserLog
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/DeclineUserLog":
                /* maneja un caso para rechazar el registro de un usuario sin acción definida. */


                break;


            /**
             * Agent/GetAgentSystems
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentSystems":
                /* Código para manejar la solicitud de sistemas de un agente, sin implementación. */


                break;

            /**
             * Agent/GetAgentCountries
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentCountries":
                /* es un fragmento de un switch que maneja un caso específico. */


                break;

            /**
             * Agent/AssignmentQuota
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/AssignmentQuota":
                /* es un fragmento de un switch que no realiza acción en el caso mencionado. */


                break;

            /**
             * Agent/GetAgentMembers
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agent/GetAgentMembers":
                /* Caso para manejar la obtención de miembros de un agente en un sistema. */


                break;

            /**
             * Client/GetRegions
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetRegions":
                /* Es un fragmento de código que maneja una acción específica sin ninguna implementación. */


                break;

            /**
             * Dashboard/GetDateView
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetDateView":
                /* Estructura de control que maneja la opción "GetDateView" sin realizar acciones. */


                break;

            /**
             * Dashboard/GetActiveClients
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetActiveClients":
                /* es un fragmento de un switch que maneja una ruta específica. */


                break;

            /**
             * Dashboard/GetActiveClients2
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetActiveClients2":
                /* define un caso para manejar la ruta "Dashboard/GetActiveClients2", pero no realiza ninguna acción. */


                break;

            /**
             * Dashboard/GetNewRegisteredClients
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetNewRegisteredClients":
                /* Caso vacío en un switch para manejar la ruta de nuevos clientes registrados. */


                break;

            /**
             * Dashboard/GetDepositSummary2
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetDepositSummary2":
                /* maneja un caso en una estructura switch relacionada con resúmenes de depósitos. */


                break;

            /**
             * Dashboard/GetDepositSummary
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetDepositSummary":
                /* Código de un caso en un switch que no realiza ninguna acción. */


                break;

            /**
             * Dashboard/GetNewRegisteredClientsDepositSummary
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetNewRegisteredClientsDepositSummary":
                /* define un caso vacío para un endpoint relacionado con resúmenes de depósitos. */


                break;

            /**
             * Dashboard/GetWithDrawalSummary
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetWithDrawalSummary":
                /* Código que define un caso para obtener un resumen de retiros en un dashboard. */


                break;

            /**
             * Dashboard/GetCasinoBetSummary
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetCasinoBetSummary":
                /* Estructura de control para manejar la ruta "GetCasinoBetSummary" sin acción definida. */


                break;

            /**
             * Dashboard/GetSportBetSummary
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetSportBetSummary":
                /* indica un caso vacío para obtener el resumen de apuestas deportivas. */


                break;

            /**
             * Dashboard/GetDashboardResume
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetDashboardResume":
                /* define un caso en un switch, relacionado con un resumen del panel. */


                break;

            /**
             * Dashboard/GetTopCasinoPlayers
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetTopCasinoPlayers":
                /* es parte de un switch que maneja una ruta específica sin acción definida. */


                break;

            /**
             * Dashboard/GetTopSportsbookPlayers
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetTopSportsbookPlayers":
                /* maneja una solicitud para obtener jugadores destacados de sportsbooks, sin implementación. */


                break;

            /**
             * Dashboard/GetTopCasinoGames
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetTopCasinoGames":
                /* representa un caso en un switch que no realiza ninguna acción. */


                break;

            /**
             * Dashboard/GetDashboardResume2
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Dashboard/GetDashboardResume2":
                /* es un fragmento que maneja una ruta para obtener un resumen del tablero. */


                break;

            /**
             * UserManagement/GetPartnerRoles
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetPartnerRoles":
                /* define una opción para gestionar roles de usuario que no ejecuta acciones. */


                break;

            /**
             * UserManagement/GetGroupPermissions
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetGroupPermissions":
                /* Caso de manejo de permisos de grupo de usuario, sin acciones definidas. */


                break;

            /**
             * UserManagement/GetRoleById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/GetRoleById":
                /* Código que trata un caso específico en la gestión de roles de usuario. */


                break;

            /**
             * GetRolesForEditUser
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "GetRolesForEditUser":
                /* maneja un caso para obtener roles al editar un usuario, pero no realiza ninguna acción. */


                break;

            /**
             * GetGroupUsers
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "GetGroupUsers":
                /* es un caso vacío para la acción "GetGroupUsers" en un switch. */


                break;

            /**
             * GetClients
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "GetClients":
                /* es un fragmento de un switch-case para manejar la opción "GetClients". */


                break;

            /**
             * Client/GetClientProducts
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientProducts":
                /* define una opción en un switch para manejar clientes y sus productos. */


                break;

            /**
             * SaveClientProduct
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "SaveClientProduct":
                /* representa un caso para guardar un producto del cliente, sin implementación. */


                break;

            /**
             * saveStateProduct
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "saveStateProduct":
                /* es un caso en un switch que no ejecuta ninguna acción. */


                break;

            /**
             * Client/GetClientKpi
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientKpi22":
                /* Ejemplo de un caso en un switch que no realiza ninguna acción. */


                break;

            case "Client/GetClientKpi":
                /* define un caso en un switch, pero no realiza ninguna acción. */


                break;

            /**
             * Client/GetClientAccounts
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientAccounts":
                /* representa un caso de una instrucción switch, sin acciones definidas. */


                break;

            /**
             * Client/GetClientsWithBalance
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientsWithBalance":
                /* Es un fragmento de código que define un caso en una estructura switch. */


                break;

            /**
             * Reference/GetCurrencies
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Reference/GetCurrencies":
                /* maneja un caso específico, "GetCurrencies", que no realiza ninguna acción. */

                break;

            /**
             * Client/GetCurrencies
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetCurrencies":
                /* es un fragmento de un switch-case que no ejecuta ninguna acción. */


                break;

            /**
             * UserManagement/SavePermissionsForRole
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/SavePermissionsForRole":
                /* Se define un caso para gestionar permisos de usuario, sin implementación actual. */


                break;

            /**
             * SavePermissionsForRole
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "SavePermissionsForRole":
                /* representa un caso vacío para guardar permisos en un rol determinado. */


                break;


            /**
             * Reference/SaveFreeBetBonus2
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Reference/SaveFreeBetBonus2":
                /* Es un fragmento de código que maneja un caso específico sin ejecutar acciones. */


                break;


            case "Bonus/AddBonusBalance":
                /* representa un caso sin acción en un switch relacionado con bonos. */


                break;


            case "BalanceAdjustments/Adjustment":
                /* realiza una acción específica para "BalanceAdjustments/Adjustment" y luego lo detiene. */


                break;


            case "BalanceAdjustments/GetBalanceAdjustments":
                /* es un fragmento de un switch que maneja ajustes de balance. */


                break;


            case "Report/GetBalanceUsers":
                /* define un caso de ruta en un switch, sin acciones definidas. */


                break;

            /**
             * Report/GetClientBonusReport
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/GetClientBonusReport":
                /* define una casilla para manejar un reporte de bonificación de clientes. */


                break;

            /**
             * Report/GetClientBonusReportDetail
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/GetClientBonusReportDetail":
                /* Estructura de un caso en un switch para obtener detalles de un informe de bonificación. */


                break;

            /**
             * vapi/GetClientBonusReport
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "vapi/GetClientBonusReport":
                /* Estructura de control para manejar una solicitud a "GetClientBonusReport". Sin acción definida. */


                break;

            /**
             * Reference/SaveFreeBetBonus
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Reference/SaveFreeBetBonus":
                /* es un caso vacío en una estructura switch para gestión de bonos. */


                break;


            case "Report/GetProvidersPaymentSystem":
                /* corresponde a un caso en un switch que no realiza acciones. */


                break;


            /**
             * Report/GetPaymentSystems
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/GetPaymentSystems":
                /* define un caso sin acción para "GetPaymentSystems" en un switch. */


                break;

            /**
             * Reference/GetPaymentSystems
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Reference/GetPaymentSystems":
                /* Es un caso vacío en un switch que maneja "Reference/GetPaymentSystems". */


                break;

            /**
             * Report/GetSuperBets
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/GetSuperBets":
                /* Fragmento de código que maneja una ruta específica sin implementar lógica. */


                break;


            /**
             * Report/GetPaymentSystemsTurnovers
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/GetPaymentSystemsTurnovers":
                /* define un caso en un switch, pero no contiene acciones específicas. */


                break;


            /**
             * GetFreeBetBonusesByFilter
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "GetFreeBetBonusesByFilter":
                /* es un fragmento de una estructura de control sin implementar acciones específicas. */


                break;

            /**
             * GetFreeBetBonusById
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "GetFreeBetBonusById":
                /* presenta un caso de un switch sin implementación para obtener un bono. */


                break;

            /**
             * GetSportBetSummary
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "GetSportBetSummary":
                /* es un caso en una estructura switch, aún no implementado. */


                break;


            /**
             * Financial/GetHistoricalCashFlow
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetHistoricalCashFlow":
                /* Código para manejar la solicitud "GetHistoricalCashFlow" sin ninguna acción definida. */


                break;

            /**
             * Financial/GetFlujoCajaResumido
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetFlujoCajaResumido":
                /* es parte de un switch-case, maneja una opción sin acción definida. */


                break;


            case "Financial/GetFlujoCajaResumido22":
                /* define un caso vacío en un switch para un flujo de caja resumido. */


                break;


            /**
             * Financial/GetInformeGerencial
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetInformeGerencial":
                /* es un fragmento de un switch que maneja una ruta específica. */


                break;


            /**
             * Financial/GetUsuarioOnlineResumido
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetUsuarioOnlineResumido":
                /* Es un fragmento de código que maneja una acción específica en un caso. */


                break;

            /**
             * Agent/GetInformeGerencial
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Agents/GetInformeGerencial":
                /* es un fragmento de un switch que no realiza ninguna acción. */


                break;


            /**
             * Financial/GetComissions
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetComissions":
                /* representa un caso en un switch sin acciones definidas. */


                break;

            /**
             * Commisions/ApproveComission
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Commisions/ApproveComission":
                /* Este fragmento de código muestra un caso vacío en una estructura switch. */


                break;


            /**
             * Commisions/PayCommisions
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Commisions/PayCommisions":
                /* Código de control de flujo que no ejecuta ninguna acción para el caso específico. */


                break;

            /**
             * Financial/GetComissionsResume
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetComissionsResume":
                /* muestra una estructura de control sin implementación para "GetComissionsResume". */


                break;


            /**
             * Financial/GetDepositsWithdrawalsWithPaging2
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetDepositsWithdrawalsWithPaging2":
                /* representa un caso en una estructura de control, sin acciones definidas. */


                break;

            case "Financial/ActionDepositUser":
                /* Un fragmento de código que define una acción sin ejecutar ninguna operación. */


                break;

            case "Financial/DepositRequests":
                /* es un caso vacío en una estructura switch relacionada con solicitudes de depósitos financieros. */


                break;

            /**
             * Financial/GetDepositRequests
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetDepositRequests":
                /* Es un caso vacío en un switch, relacionado con solicitudes de depósitos financieros. */


                break;

            /**
             * Financial/GetDepositsDetail
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetDepositsDetail":
                /* Es un fragmento de código que maneja una opción en un sistema financiero. */


                break;

            /**
             * Financial/GetDepositsRequestsDetail
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetDepositsRequestsDetail":
                /* está en un bloque de control que maneja solicitudes sobre detalles de depósitos. */


                break;


            /**
             * Financial/GetDepositsWithdrawalsWithPaging
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetDepositsWithdrawalsWithPaging":
                /* es un caso en una estructura switch que no ejecuta acciones. */


                break;

            /**
             * Report/getDeposits
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/getDeposits":
                /* gestiona un caso específico, pero no ejecuta ninguna acción en este momento. */


                break;

            /**
             * Report/getWithdrawals
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Report/getWithdrawals":
                /* es un caso de switch que no ejecuta acciones para "Report/getWithdrawals". */


                break;


            /**
             * Client/GetClientDepositRequests
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientDepositRequests":
                /* maneja una opción de solicitud de depósito del cliente, sin acciones definidas. */


                break;


            /**
             * Client/GetClientKoreanDepositRequests
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientKoreanDepositRequests":
                /* representa un caso vacío en una estructura de control switch. */


                break;

            /**
             * Client/SaveClientBankAccount
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/SaveClientBankAccount":
                /* representa un caso vacío en una estructura de control "switch". */


                break;

            /**
             * Client/GetClientBankAccounts
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientBankAccounts":
                /* es un caso vacío para manejar una solicitud de cuentas bancarias del cliente. */


                break;

            /**
             * Client/UpdateStateDocument
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/UpdateStateDocument":
                /* Código que maneja un caso específico sin acciones definidas para "Client/UpdateStateDocument". */


                break;

            /**
             * Client/GetClientDocuments
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientDocuments":
                /* es un fragmento de un switch que maneja una acción específica. */


                break;

            /**
             * Client/GetClientWithdrawalRequests
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientWithdrawalRequests":
                /* Código que define un caso en un switch, relacionado con solicitudes de retiro de clientes. */


                break;

            /**
             * Client/GetClientWithdrawalRequestsDetail
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientWithdrawalRequestsDetail":
                /* Es un fragmento de código que maneja una solicitud específica en un sistema cliente. */


                break;

            /**
             * Client/GetClientLiquidationRequests
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientLiquidationRequests":
                /* Código que maneja una solicitud para obtener liquidaciones de clientes, sin implementación. */


                break;

            /**
             * Financial/GetDocumentStates
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetDocumentStates":
                /* Esto es un fragmento de código que maneja un caso en una estructura switch. */


                break;

            /**
             * Financial/GetClientRequestStates
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Financial/GetClientRequestStates":
                /* es un caso vacío en una estructura switch sobre estados de solicitud financiera. */


                break;

            /**
             * ClientAPI/SaveClientMessages
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "ClientAPI/SaveClientMessages":
                /* maneja una ruta específica sin implementar funcionalidad en este caso. */


                break;

            /**
             * Client/SaveClientMessages
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/SaveClientMessages":
                /* Estructura de control en código para manejar el guardado de mensajes de cliente. */


                break;

            /**
             * Client/GetClientMessages
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientMessages":
                /* Es un fragmento de código que maneja una ruta específica en un sistema. */


                break;

            /**
             * Client/SaveClientMessage
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/SaveClientMessage":
                /* es un fragmento de un switch que maneja un mensaje de cliente. */


                break;


            /**
             * Client/ResetPassword
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/ResetPassword":
                /* es parte de una estructura de control que maneja una acción específica. */


                break;

            /**
             * UserManagement/ResetUserPassword
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/ResetUserPassword":
                /* es un caso vacío para restablecer contraseñas de usuarios en un sistema. */


                break;

            /**
             * ResetUserPassword
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "ResetUserPassword":
                /* es un fragmento de un switch que maneja el caso de reiniciar contraseña. */


                break;

            /**
             * UserManagement/isNotUsedUserName
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UserManagement/isNotUsedUserName":
                /* Código que evalúa un caso específico sin realizar ninguna acción. */


                break;


            /**
             * Client/PayDepositRequests
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/PayDepositRequests":
                /* Este bloque de código maneja un caso específico, pero no realiza ninguna acción. */


                break;

            /**
             * Client/AllowWithdrawalRequests
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/AllowWithdrawalRequests":
                /* Interrumpe la ejecución en el caso de solicitudes de retiro de cliente. */


                break;

            /**
             * Reference/SaveTranslationEntries
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Reference/SaveTranslationEntries":
                /* representa un caso vacío en un bloque switch, sin funcionalidad definida. */


                break;

            /**
             * Reference/GetTranslations
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Reference/GetTranslations":
                /* Estructura de un case en un switch para manejar traducciones. */


                break;

            /**
             * Reference/GetTranslationTypes
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Reference/GetTranslationTypes":
                /* muestra un caso vacío para la ruta "Reference/GetTranslationTypes". */


                break;

            /**
             * Reference/GetCurrentPartnerLanguage
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Reference/GetCurrentPartnerLanguage":


                /* Código que define un método para obtener lenguajes, aún no implementado. */
                exit();


            /**
             * Reference/GetLanguages
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Reference/GetLanguages":


                break;

            /**
             * Client/PayWithdrawalRequests
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/PayWithdrawalRequests":
                /* maneja una opción para solicitudes de retiro, pero no implementa acciones. */


                break;

            /**
             * Client/PayWithdrawalRequestsAPI
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/PayWithdrawalRequestsAPI":
                /* maneja un caso específico en una estructura de control, pero no realiza ninguna acción. */


                break;

            /**
             * Client/CancelClientRequests
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/CancelClientRequests":
                /* maneja una acción para cancelar solicitudes de cliente, sin implementación adicional. */


                break;

            /**
             * Client/AllowLiquidationRequests
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/AllowLiquidationRequests":
                /* indica un caso vacío para permitir solicitudes de liquidación. */


                break;

            /**
             * Client/PayLiquidationRequests
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/PayLiquidationRequests":
                /* es un fragmento de una estructura de control que no realiza acciones. */


                break;

            /**
             * Client/CancelLiquidationRequests
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/CancelLiquidationRequests":
                /* maneja un caso específico en un switch, sin acción definida. */


                break;

            /**
             * Client/GetQRGoogle
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetQRGoogle":
                /* maneja una opción "GetQRGoogle" pero no ejecuta ninguna acción. */


                break;

            /**
             * Client/GetMyQRGoogle
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetMyQRGoogle":
                /* define un caso para gestionar la solicitud "Client/GetMyQRGoogle", sin acciones. */


                break;


            /**
             * Client/CreateClientPaymentDocument
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/CreateClientPaymentDocument":
                /* es un fragmento de una estructura de control sin operación definida. */


                break;

            /**
             * Client/SetClientDepositLimits
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/SetClientDepositLimits":
                /* Caso de código que corresponde a la acción de establecer límites de depósito para clientes. */


                break;

            /**
             * Client/UpdateClientSocialPreferens
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/UpdateClientSocialPreferens":
                /* representa un caso vacío para actualizar preferencias sociales de un cliente. */


                break;


            /**
             * oAuth/getoAuth
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "oAuth/getoAuth":
                /* Fragmento de código que maneja un caso específico en una estructura de control. */


                break;

            /**
             * geoapi/geoapi
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "geoapi/geoapi":
                /* Es un fragmento de código que maneja un caso específico en una estructura switch. */


                break;

            /**
             * Reference/GetClientSelfExclusionTypes
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Reference/GetClientSelfExclusionTypes":
                /* Este fragmento es parte de un switch-case sin lógica implementada en la opción seleccionada. */


                break;

            /**
             * UpdateClientSelfExclusion
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "UpdateClientSelfExclusion":
                /* Un caso de código vacío para la actualización de autoexclusión de clientes. */


                break;

            /**
             * Client/GetClientDepositLimits
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/GetClientDepositLimits":
                /* Fragmento de código que maneja un caso específico sin implementación. */


                break;

            /**
             * Client/ActivateDocumentClient
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/ActivateDocumentClient":
                /* Código para manejar el caso "Client/ActivateDocumentClient" sin ninguna acción implementada. */


                break;

            /**
             * Client/RejectDocumentClient
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/RejectDocumentClient":
                /* es un fragmento de un switch que no realiza acciones. */


                break;

            /**
             * Client/ActivateClient
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/ActivateClient":
                /* representa un caso sin acción en un switch relacionado con activación de cliente. */


                break;

            /**
             * Client/RejectClient
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/RejectClient":
                /* representa un caso vacío en una estructura de control "switch". */


                break;

            /**
             * Client/UpdateUserSecurity
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/UpdateUserSecurity":
                /* Es un fragmento de código que muestra un caso vacío en un switch. */


                break;


            /**
             * Client/UpdateClientDetails
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Client/UpdateClientDetails":
                /* Un caso de un switch que maneja la actualización de detalles del cliente. */


                break;


            /**
             * Partner/GetPartnerList
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Partner/GetPartnerList":
                /* representa un caso en un switch que no realiza ninguna acción. */


                break;

            /**
             * GetSaleList
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "GetSaleList":
                /* representa un caso en un switch que no realiza ninguna acción. */


                break;

            /**
             * Providers/GetProviders
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Providers/GetProviders":
                /* Código que gestiona una ruta para obtener proveedores, pero no contiene implementación. */


                break;

            /**
             * Provider/UpdateProviderDetails
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Provider/UpdateProviderDetails":
                /* es un fragmento de un switch sin acción definida en ese caso. */


                break;


            /**
             * Products/GetProducts
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Products/GetProducts":
                /* Es un fragmento de código que define un caso para manejar "GetProducts". */


                break;

            /**
             * Categories/GetCategories
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Categories/GetCategories":
                /* representa una estructura de control para manejar la ruta "GetCategories". */


                break;

            /**
             * Categories/UpdateCategory
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Categories/UpdateCategory":
                /* Es un fragmento de código que espera una acción para actualizar categorías, pero no hace nada. */


                break;

            /**
             * Categories-products/GetCategoriesProducts
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Categories-products/GetCategoriesProducts":
                /* Es un fragmento de código que maneja una acción relacionada con categorías y productos. */


                break;

            /**
             * Categories/SaveCategoryProduct
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Categories/SaveCategoryProduct":
                /* es un fragmento vacío para manejar la categoría de productos. */


                break;


            /**
             * Categories/UpdateCategoryProduct
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Categories/UpdateCategoryProduct":
                /* es un bloque vacío para la actualización de categorías de productos. */


                break;

            /**
             * PartnersProducts/GetPartnersProducts
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "PartnersProducts/GetPartnersProducts":
                /* es un caso vacío en un switch para obtener productos de socios. */


                break;


            /**
             * Image/uploadImage
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Image/uploadImage":
                /* es un bloque de un switch que no realiza ninguna acción. */


                break;


            /**
             * Product/UpdateProductDetails
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Product/UpdateProductDetails":
                /* indica un case en un switch que no realiza ninguna acción. */


                break;

            /**
             * PartnerProduct/CreatePartnerProduct
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "PartnerProduct/CreatePartnerProduct":
                /* es un fragmento de un switch que maneja una opción específica. */


                break;


            /**
             * PartnerProduct/UpdatePartnerProductDetails
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "PartnerProduct/UpdatePartnerProductDetails":
                /* Código para manejar la actualización de detalles de un producto asociado en un sistema. */


                break;

            /**
             * PartnersProducts/GetPartnersProviders
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "PartnersProducts/GetPartnersProviders":
                /* Código para manejar una ruta específica en un sistema, sin acciones definidas aún. */


                break;

            /**
             * RelationUserAggregator/GetRelationUserAggregator
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "RelationUserAggregator/GetRelationUserAggregator":
                /* Es un bloque de código para manejar una acción específica en un switch. */


                break;


            /**
             * PartnerProduct/UpdatePartnerProvider
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "PartnerProduct/UpdatePartnerProvider":
                /* maneja un caso específico dentro de una estructura de control sin funcionalidad definida. */


                break;

            /**
             * PartnerProduct/CreatePartnerProvider
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "PartnerProduct/CreatePartnerProvider":
                /* es un caso vacío en un switch relacionado con la creación de proveedores. */


                break;

            /**
             * PartnersProducts/GetPartnersTypeProduct
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "PartnersProducts/GetPartnersTypeProduct":
                /* muestra un caso para gestionar productos asociados a socios, sin acciones definidas. */


                break;


            /**
             * PartnerProduct/UpdatePartnerTypeProduct
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "PartnerProduct/UpdatePartnerTypeProduct":
                /* Código que define un caso en un switch, sin acciones específicas al ejecutarse. */


                break;

            /**
             * PartnerProduct/CreatePartnerTypeProduct
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "PartnerProduct/CreatePartnerTypeProduct":
                /* define un caso vacío para crear un tipo de producto asociado a un socio. */


                break;


            /**
             * PartnersProductsCountry/GetPartnersProductsCountry
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "PartnersProductsCountry/GetPartnersProductsCountry":
                /* Manejo de un caso específico en un switch, sin implementación. */


                break;


            /**
             * Product/GetProductList
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "Product/GetProductList":
                /* Código de un caso en un switch que maneja la obtención de una lista de productos. */


                break;

            /**
             * GetProductProviderList
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "GetProductProviderList":
                /* define un caso vacío para "GetProductProviderList" en un switch. */


                break;


            /**
             * GetProductCategoryList
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "GetProductCategoryList":
                /* Código para manejar la opción "GetProductCategoryList" sin funcionalidades implementadas aún. */


                break;

            /**
             * CreateProduct
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "CreateProduct":
                /* es un fragmento de una estructura de control 'switch' sin implementación. */


                break;


            /**
             * CreateSale
             *
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "CreateSale":
                /* Código para manejar la opción "Crear Venta" sin implementar ninguna acción. */


                break;


            default:
                # code...
                break;
        }
    }

} catch (Exception $e) {


    /* registra errores y muestra detalles si está en modo debug. */
    if ($_ENV["debug"]) {
        print_r('eNTRO');
        print_r($e);
    }
    syslog(LOG_WARNING, "ERRORAPIPV :" . $e->getCode() . ' LINE ' . $e->getLine() . ' - ' . $e->getMessage() . json_encode($params) . json_encode($_SERVER) . json_encode($_REQUEST) . json_encode($_SESSION));

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


    /* Se inicializan variables vacías y un arreglo para manejar respuestas. */
    $codeProveedor = "";
    $messageProveedor = "";

    $response = array();

    switch ($code) {
        case 10011:
            /* Código que maneja un caso específico, asignando un código y mensaje de error. */

            $codeProveedor = "400";
            $messageProveedor = "No such session.";

            break;
        case 21:
            /* Código que asigna un mensaje de error si no hay sesión activa. */

            $codeProveedor = "400";
            $messageProveedor = "No such session.";

            break;
        case 22:
            /* Código que maneja un caso de error por sesión inexistente en un sistema. */

            $codeProveedor = "400";
            $messageProveedor = "No such session.";

            break;
        case 20001:
            /* asigna un mensaje de error por fondos insuficientes al proveedor. */

            $codeProveedor = "402";
            $messageProveedor = "Insufficient funds.";

            break;

        case 0:
            /* maneja un caso de error general asignando un código y mensaje específico. */

            $codeProveedor = "402";
            $messageProveedor = "General Error. (" . $code . ")";

            break;
        case 27:
            /* Caso 27 asigna un código y mensaje de error general. */

            $codeProveedor = "402";
            $messageProveedor = "General Error. (" . $code . ")";
            break;
        case 28:
            /* maneja un error general asignando un código y un mensaje específico. */


            $codeProveedor = "402";
            $messageProveedor = "General Error. (" . $code . ")";


            break;
        case 29:
            /* establece un mensaje de error para una transacción no encontrada. */


            $codeProveedor = "402";
            $messageProveedor = "Transaction Not Found";

            break;

        case 10001:
            /* maneja un caso específico que indica que ya fue procesado. */


            $codeProveedor = 0;
            $messageProveedor = "Already processed";

            break;

        case 10004:
            /* maneja un caso de error general con un proveedor específico. */


            $codeProveedor = "REQUEST_DECLINED";
            $messageProveedor = "General Error. (" . $code . ")";

            break;
        case 10014:
            /* Maneja un caso de error general, asignando un código y mensaje de error. */


            $codeProveedor = "REQUEST_DECLINED";
            $messageProveedor = "General Error. (" . $code . ")";

            break;


        case 10010:
            /* maneja un caso de error con un mensaje específico para el proveedor. */


            $codeProveedor = "REQUEST_DECLINED";
            $messageProveedor = "General Error. (" . $code . ")";


            break;


        case 20001:
            /* maneja un caso de error por fondos insuficientes. */


            $codeProveedor = "402";
            $messageProveedor = "Insufficients Funds";


            break;

        case 20002:
            /* Es un fragmento de código en un bloque switch, sin acciones definidas. */


            break;

        case 20003:
            /* Código para gestionar un caso de cuenta bloqueada, asignando un mensaje específico. */


            $codeProveedor = "ACCOUNT_BLOCKED";
            $messageProveedor = "ACCOUNT_BLOCKED";


            break;

        case 10005:
            /* Es un bloque en un switch que maneja el caso 10005 y termina sin acción. */


            break;

        case 30003:
            /* asigna un mensaje de error relacionado con credenciales incorrectas. */


            $messageProveedor = "El usuario o la clave son incorrectos. ";

            break;

        case 100002:
            /* maneja un caso específico donde no hay disponibilidad de cupo. */


            $messageProveedor = "Punto de venta no tiene cupo disponible para realizar la recarga";

            break;

        case 30001:
            /* Fragmento de código que captura un mensaje de error en el caso 30001. */


            $messageProveedor = $e->getMessage();

            break;

        case 300172:
            /* Fragmento de código que captura un mensaje de error en el caso 300172. */


            $messageProveedor = "Red asociada a una criptomoneda, el codigo de red no se puede cambiar.";

            break;

        case 300173:
            /* Fragmento de código que captura un mensaje de error en el caso 300173. */

            $messageProveedor = "El código de red ingresado ya existe en el sistema.";

            break;

        case 30002:
            if ($arraySuper[oldCount($arraySuper) - 1] == "Login") {


                /* Inicializa variables de usuario y crea instancias de clases relacionadas con usuarios. */
                $usuario = $params->Username;
                $PartnerLogin = $params->PartnerLogin;

                $Usuario = new Usuario();

                $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                /* Consulta un usuario en la base de datos y obtiene el primer resultado. */
                $Usuario = $UsuarioMySqlDAO->queryByLogin($usuario, '0', '');
                $Usuario = $Usuario[0];

                if ($Usuario != "") {

                    /* Asigna un URL de mensaje basado en el mandante del usuario. */
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


                    /* Verifica si el usuario tiene permisos y redirige si no es partner. */
                    if (in_array($usuario, array('ADMINMIRAVALLE', 'OPERMIRAVALLE', 'FINMIRAVALLE'))) {

                        if ($PartnerLogin != 3) {
                            $response["messageUrl"] = 'miravalle';
                        }
                    }
                } else {
                    /* representa una estructura condicional sin acciones definidas en el bloque else. */


                }


            }

            break;

        default:

            /* maneja un error inesperado y proporciona un mensaje para el usuario. */
            $codeProveedor = 'UNKNOWN_ERROR';
            $messageProveedor = "Unexpected error. Reporte el codigo" . $code . ".";
            $messageProveedor = "Error en la solicitud.";


            break;
    }


    /* asigna un mensaje de error y configura una respuesta de advertencia. */
    if ($messageProveedor == "") {
        $messageProveedor = getErrormsj($code);
    }

    $response["HasError"] = true;
    $response["AlertType"] = "danger";

    /* Configura mensajes de error y códigos de respuesta para el login fallido. */
    $response["AlertMessage"] = 'Error en el login';
    //$response["AlertMessage"] = '-|' . $e->getMessage() . ' |-' . '(' . $e->getCode() . ')';
    $response["AlertMessage"] = $messageProveedor . ' (Report#' . $code . ")";
    // $response["AlertMessage"] = '-|' . "" . ' |-' . '(' . $e->getCode() . ')';
    $response["ModelErrors"] = [];
    $response["CodeError"] = $code;
}


/* verifica si $response no está vacío y lo imprime en formato JSON. */
if (json_encode($response) != "[]") {
    print_r(json_encode($response));


}
/**
 * Convierte caracteres UTF-8 a sus equivalentes en ANSI.
 *
 * Esta función reemplaza los caracteres UTF-8 específicos por sus versiones
 * equivalentes en el conjunto de caracteres ANSI. Los caracteres que se encuentran
 * en el array `$utf8_ansi2` se transforman según el mapeo proporcionado.
 *
 * @param string $valor El texto en formato UTF-8 que será convertido.
 * @return string Devuelve el texto convertido a formato ANSI.
 * @throws no No contiene manejo de excepciones.
 */
function Utf8_ansi($valor = '')
{

    $utf8_ansi2 = array(
        "u00c0" => "À",
        "u00c1" => "Á",
        "u00c2" => "Â",
        "u00c3" => "Ã",
        "u00c4" => "Ä",
        "u00c5" => "Å",
        "u00c6" => "Æ",
        "u00c7" => "Ç",
        "u00c8" => "È",
        "u00c9" => "É",
        "u00ca" => "Ê",
        "u00cb" => "Ë",
        "u00cc" => "Ì",
        "u00cd" => "Í",
        "u00ce" => "Î",
        "u00cf" => "Ï",
        "u00d1" => "Ñ",
        "u00d2" => "Ò",
        "u00d3" => "Ó",
        "u00d4" => "Ô",
        "u00d5" => "Õ",
        "u00d6" => "Ö",
        "u00d8" => "Ø",
        "u00d9" => "Ù",
        "u00da" => "Ú",
        "u00db" => "Û",
        "u00dc" => "Ü",
        "u00dd" => "Ý",
        "u00df" => "ß",
        "u00e0" => "à",
        "u00e1" => "á",
        "u00e2" => "â",
        "u00e3" => "ã",
        "u00e4" => "ä",
        "u00e5" => "å",
        "u00e6" => "æ",
        "u00e7" => "ç",
        "u00e8" => "è",
        "u00e9" => "é",
        "u00ea" => "ê",
        "u00eb" => "ë",
        "u00ec" => "ì",
        "u00ed" => "í",
        "u00ee" => "î",
        "u00ef" => "ï",
        "u00f0" => "ð",
        "u00f1" => "ñ",
        "u00f2" => "ò",
        "u00f3" => "ó",
        "u00f4" => "ô",
        "u00f5" => "õ",
        "u00f6" => "ö",
        "u00f8" => "ø",
        "u00f9" => "ù",
        "u00fa" => "ú",
        "u00fb" => "û",
        "u00fc" => "ü",
        "u00fd" => "ý",
        "u00ff" => "ÿ");

    return str_replace(array_keys($utf8_ansi2), array_values($utf8_ansi2), $valor);

}

/**
 * Obtiene el mensaje de error correspondiente al código proporcionado.
 *
 * Esta función recibe un código de error como parámetro y devuelve una descripción
 * detallada del error correspondiente según una lista predefinida de códigos y mensajes.
 * Si el código no está en la lista, devuelve un mensaje genérico indicando un error inesperado.
 *
 * @param int $code El código de error a consultar.
 * @return string El mensaje de error asociado al código proporcionado.
 * @throws no No contiene manejo de excepciones.
 */
function getErrormsj($code)
{

    $codeProveedor = "";
    $messageProveedor = "";


    //Crear una función que reciba el codigo y me retorne la descripción

    switch ($code) {

        case 10001:
            /* Código que asigna un mensaje a una variable si el caso es 10001. */

            $messageProveedor = "Existe transacción para el proveedor";
            break;

        case 10002:
            /* asigna un mensaje específico para el caso 10002 en un switch. */

            $messageProveedor = "Monto negativo";
            break;

        case 10003:
            /* Código que maneja un caso específico y asigna un mensaje a una variable. */

            $messageProveedor = "Valor ticket diferente a rollback";
            break;

        case 10004:
            /* Un caso que asigna un mensaje de error relacionado con un débito. */

            $messageProveedor = "Debit con rollback antes";
            break;

        case 10005:
            /* Muestra un mensaje cuando la transacción identificada con el código 10005 no existe. */

            $messageProveedor = "No existe la transacción";
            break;

        case 10006:
            /* Muestra un mensaje cuando la transacción no se puede procesar. */

            $messageProveedor = "La transacción no es debit";
            break;

        case 10007:
            /* Código para manejar un error específico en la transacción, mostrando un mensaje de alerta. */

            $messageProveedor = "Detalles de la trasacción no coinciden";
            break;

        case 10008:
            /* maneja un caso específico, generando un mensaje de error para una transacción. */

            $messageProveedor = "Valor de la transacción anterior diferente";
            break;

        case 10010:
            /* Código que maneja un caso específico con un mensaje de transacción existente. */

            $messageProveedor = "Transaccion Juego Existe";
            break;

        case 10011:
            /* asigna un mensaje cuando el caso 10011 se ejecuta. */

            $messageProveedor = "Token vacío";
            break;

        case 10012:
            /* maneja un caso donde se indica que un token ya existe. */

            $messageProveedor = "Token ya existe";
            break;

        case 10013:
            /* maneja un caso específico con un mensaje sobre un UID vacío. */

            $messageProveedor = "UID vacío";
            break;

        case 10014:
            /* asigna un mensaje específico basado en el caso 10014. */

            $messageProveedor = "# Debit diferente a # Credit";
            break;

        case 10015:
            /* Código que define un mensaje para una transacción cancelada en caso 10015. */

            $messageProveedor = "Trasacción con rollback";
            break;

        case 10016:
            /* asigna un mensaje a una variable según un caso específico. */

            $messageProveedor = "Ronda cerrada";
            break;

        case 10017:
            /* maneja un caso específico para mostrar un mensaje de error. */

            $messageProveedor = "Moneda Incorrecta";
            break;

        case 10018:
            /* asigna un mensaje de error si la moneda es incorrecta. */

            $messageProveedor = "Moneda Incorrecta";
            break;

        case 10021:
            /* maneja un caso de error para un país incorrecto. */

            $messageProveedor = "Código de país incorrecto";
            break;

        case 10025:
            /* maneja un caso donde se muestra un mensaje por ID de ticket duplicado. */

            $messageProveedor = "Ticket ID ya existe";
            break;

        case 10026:
            /* maneja un caso de error para un ID de ticket inexistente. */

            $messageProveedor = "Ticket ID no existe";
            break;

        case 10027:
            /* Código que asigna un mensaje cuando un ticket específico está cerrado. */

            $messageProveedor = "El ticket ya esta cerrado";
            break;

        case 19000:
            /* maneja un caso específico, mostrando un mensaje sobre una cédula duplicada. */

            $messageProveedor = "La cédula ya existe";
            break;

        case 19001:
            /* Código para manejar el caso de un email ya registrado en un sistema. */

            $messageProveedor = "El email ya esta registrado";
            break;

        case 20000:
            /* establece un mensaje para sesión de usuario expiró o es inválida. */

            $messageProveedor = "Sesion del usuario expirada o invalida";
            break;

        case 20001:
            /* asigna un mensaje de error por fondos insuficientes en un caso específico. */

            $messageProveedor = "Fondos insuficientes";
            break;


        case 20002:
            /* maneja un caso de error específico: "SIGN incorrecto". */

            $messageProveedor = "SIGN incorrecto";
            break;

        case 20003:
            /* Establece un mensaje para un usuario inactivo en un sistema. */

            $messageProveedor = "Usuario Inactivo";
            break;

        case 20004:
            /* Se define un mensaje para el caso de autoexclusión en un casino. */

            $messageProveedor = "Autoexclusion Casino Producto Interno";
            break;

        case 20005:
            /* asigna un mensaje específico para el caso 20005 en un switch. */

            $messageProveedor = "Autoexclusion Casino Categoria";
            break;

        case 20006:
            /* asigna un mensaje específico para un caso determinado en un switch. */

            $messageProveedor = "Autoexclusion Casino SubCategoria";
            break;

        case 20007:
            /* asigna un mensaje sobre autoexclusión de casino a una variable específica. */

            $messageProveedor = "Autoexclusion Casino Juego";
            break;

        case 20008:
            /* define un mensaje cuando se alcanza un límite de depósito específico. */

            $messageProveedor = "Limite de deposito simple";
            break;

        case 20009:
            /* Caso 20009: asigna un mensaje sobre límite de depósito diario. */

            $messageProveedor = "Limite de deposito diario";
            break;

        case 20010:
            /* Código que define un mensaje de límite de depósito semanal para el caso 20010. */

            $messageProveedor = "Limite de deposito semanal";
            break;

        case 20011:
            /* asigna un mensaje de error relacionado con el límite de depósito mensual. */

            $messageProveedor = "Limite de deposito mensual";
            break;

        case 20012:
            /* maneja un caso específico: límite de depósito anual para un proveedor. */

            $messageProveedor = "Limite de deposito anual";
            break;

        case 20013:
            /* Es un caso de mensaje de error para un límite de casino específico. */

            $messageProveedor = "Limite de casino simple";
            break;

        case 20014:
            /* asigna un mensaje al proveedor sobre el límite diario del casino. */

            $messageProveedor = "Limite de casino diario";
            break;

        case 20015:
            /* maneja un caso específico con un mensaje sobre límite semanal de casino. */

            $messageProveedor = "Limite de casino semanal";
            break;

        case 20016:
            /* asigna un mensaje específico para el caso 20016 en un switch. */

            $messageProveedor = "Limite de casino mensual";
            break;

        case 20017:
            /* asigna un mensaje relacionado con un límite anual de casino. */

            $messageProveedor = "Limite de casino anual";
            break;

        case 20018:
            /* asigna un mensaje específico al caso 20018 en un switch. */

            $messageProveedor = "Limite de casino en vivo simple";
            break;

        case 20019:
            /* Código que define un mensaje para un límite diario en un casino. */

            $messageProveedor = "Limite de casino en vivo diario";
            break;

        case 20020:
            /* Código que asigna un mensaje específico para el caso 20020 en un sistema. */

            $messageProveedor = "Limite de casino en vivo semanal";
            break;

        case 20021:
            /* asigna un mensaje específico para el caso correspondiente al código 20021. */

            $messageProveedor = "Limite de casino en vivo mensual";
            break;

        case 20022:
            /* define un mensaje para un caso específico relacionado con límites de casino. */

            $messageProveedor = "Limite de casino en vivo anual";
            break;

        case 20023:
            /* establece un mensaje para un proveedor inactivo bajo el caso 20023. */

            $messageProveedor = "Casino Inactivo";
            break;

        case 20024:
            /* asigna un mensaje cuando se recibe el código 20024. */

            $messageProveedor = "Casino en Contingencia";
            break;

        case 20025:
            /* asigna un mensaje para un caso específico en un switch. */

            $messageProveedor = "LiveCasino Inactivo";
            break;

        case 20026:
            /* asigna un mensaje específico a una variable según un código de caso. */

            $messageProveedor = "LiveCasino en Contingencia";
            break;

        case 20027:
            /* asigna un mensaje basado en un caso específico de usuario autoexcluido. */

            $messageProveedor = "Usuario autoexcluido total por tiempo";
            break;

        case 20028:
            /* asigna un mensaje específico cuando el caso es 20028. */

            $messageProveedor = "Usuario autoexcluido total";
            break;

        case 20029:
            /* Código que genera un mensaje de error para usuario no registrado en la plataforma. */

            $messageProveedor = "El usuario no esta registrado en la plataforma (Eliminado)";
            break;

        case 21000:
            /* maneja un caso donde no se encuentra un número de retiro. */

            $messageProveedor = "No se encuentra el numero de retiro";
            break;

        case 21001:
            /* Manejo de un caso específico que indica un retiro procesado. */

            $messageProveedor = "El retiro ya fue procesado";
            break;

        case 21002:
            /* muestra un mensaje cuando el valor es inferior al mínimo permitible. */

            $messageProveedor = "Valor menor al minimo permitido para retirar";
            break;

        case 21003:
            /* maneja un caso de error específico para retiros no permitidos. */

            $messageProveedor = "Valor mayor al máximo permitido para retirar";
            break;

        case 21004:
            /* Código que muestra un mensaje si la cuenta no está verificada para retirar. */

            $messageProveedor = "La cuenta necesita estar verificada para poder retirar";
            break;

        case 21005:
            /* maneja un mensaje de error para registros no aprobados. */

            $messageProveedor = "El registro debe de estar aprobado para poder retirar";
            break;

        case 21006:
            /* maneja un caso específico que notifica sobre cuentas no verificadas. */

            $messageProveedor = "La cuenta necesita estar verificada para poder depositar";
            break;

        case 21007:
            /* muestra un mensaje de error si el registro no está aprobado. */

            $messageProveedor = "El registro debe de estar aprobado para poder depositar";
            break;

        case 21008:
            /* Código para gestionar un error si el valor de depósito es menor al mínimo permitido. */

            $messageProveedor = "Valor menor al minimo permitido para depositar";
            break;

        case 21009:
            /* gestiona un caso específico, mostrando un mensaje de error de depósito. */

            $messageProveedor = "Valor mayor al máximo permitido para depositar";
            break;

        case 21010:
            /* Se establece un mensaje de error para el producto no disponible en un caso específico. */

            $messageProveedor = "Producto no disponible";
            break;

        case 30001:
            /* Código que asigna un mensaje de error por bloque de usuario por logueos incorrectos. */

            $messageProveedor = "Usuario Bloqueado por minima cantidad de logueos incorrectos";
            break;

        case 30003:
            /* maneja un caso de error de autenticación de usuario. */

            $messageProveedor = "El usuario o la clave son incorrectos";
            break;

        case 30004:
            /* maneja un caso de error mostrando un mensaje de mantenimiento. */

            $messageProveedor = "En el momento nos encontramos en proceso de mantenimiento del sitio";
            break;

        case 30005:
            /* asigna un mensaje de error para un usuario inactivo. */

            $messageProveedor = "Usuario con registro inactivo";
            break;

        case 30006:
            /* Mensaje de error para usuarios que exceden el límite de cuentas bancarias permitidas. */

            $messageProveedor = "El usuario excede el número de cuentas bancarias registradas permitidas";
            break;

        case 30007:
            /* Cualquier clave errónea produce un mensaje de error específico para el usuario. */

            $messageProveedor = "La clave ingresada es incorrecta";
            break;

        case 30008:
            /* muestra un mensaje de error para un bono inválido. */

            $messageProveedor = "El codigo de bono ingresado es incorrecto";
            break;

        case 30009:
            /* Código que maneja un error relacionado con una billetera no configurada. */

            $messageProveedor = "Billetera no configurada -> QUISK";
            break;

        case 30010:
            /* Muestra un mensaje de error al intentar iniciar sesión en el sitio. */

            $messageProveedor = "No puede iniciar sesion en el sitio";
            break;

        case 30020:
            /* asigna un mensaje específico para un caso particular en programación. */

            $messageProveedor = "Bono para lealtad no disponible";
            break;

        case 50001:
            /* Muestra un mensaje de error específico para el código 50001 en un sistema. */

            $messageProveedor = "Error en los datos enviados";
            break;

        case 50002:
            /* muestra un mensaje de error para solicitudes vacías del mandante. */

            $messageProveedor = "La solicitud al mandante fue vacia";
            break;


        case 60001:
            /* Código que asigna un mensaje específico si se cumple un caso particular. */

            $messageProveedor = "Rechazada por Automation";
            break;

        case 60002:
            /* Código que asigna un mensaje específico si se cumple el caso 60002. */

            $messageProveedor = "Necesita aprobación por automation";
            break;

        case 100000:
            /* Código PHP que asigna un mensaje de error general basado en un caso específico. */

            $messageProveedor = "Error General";
            break;

        case 100001:
            /* muestra un mensaje de error por parámetros incorrectos en una estructura de casos. */

            $messageProveedor = "Error en los parametros enviados";
            break;

        case 100002:
            /* maneja un caso donde no hay cupo para recargar un punto de venta. */

            $messageProveedor = "Punto de venta no tiene cupo disponible para realizar la recarga";
            break;

        case 100003:
            /* Código que maneja un caso específico: informa sobre falta de cupo en un punto de venta. */

            $messageProveedor = "Punto de venta no tiene cupo disponible para realizar la apuesta";
            break;

        case 100004:
            /* maneja un caso donde el límite diario de ventas es excedido. */

            $messageProveedor = "Punto de venta excedió el cupo permitido por dia";
            break;

        case 100005:
            /* maneja un caso donde un punto de venta no está habilitado para depósitos. */

            $messageProveedor = "Punto de venta no tiene habilitado para realizar depósitos";
            break;

        case 100010:
            /* maneja un caso específico, mostrando un mensaje de error al usuario. */

            $messageProveedor = "No existe el usuario por documento y tipo de documento";
            break;

        case 100011:
            /* Código que maneja un caso donde un recurso ha expirado y muestra un mensaje. */

            $messageProveedor = "El recurso ha expirado";
            break;

        case 100012:
            /* Código maneja el caso 100012, estableciendo un mensaje de error por hash incorrecto. */

            $messageProveedor = "hash incorrecto";
            break;

        case 100013:
            /* maneja un caso donde se asigna un mensaje específico si la IP no se encuentra. */

            $messageProveedor = "IP no encontrada";
            break;

        case 100030:
            /* Código que asigna un mensaje de error al superar el límite de saldo. */

            $messageProveedor = "El usuario ha excedido el limite de saldo que puede tener";
            break;

        default:

            /* define mensajes de error para reportar problemas con un código específico. */
            $messageProveedor = "Unexpected error. Reporte el codigo" . $code . ".";
            $messageProveedor = "Error en la solicitud.(CODE#" . $code . ")";


            break;
    }
    return $messageProveedor;
}


/**
 * Obtiene la lista de países para un reporte basado en el perfil del usuario y configuración de la sesión.
 *
 * Dependiendo del perfil del usuario y las configuraciones en la sesión, se recuperan los países asociados y se estructuran en un array con detalles como el nombre, icono y moneda.
 *
 * @return array Devuelve un array con la lista de países, cada uno con su id, nombre, icono y monedas asociadas.
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function obtenerPaisesReport()
{
    $Pais = new Pais ($_SESSION["pais_id"]);
    $paisesparamenu = array(
        array(
            "id" => "0",
            "value" => "Todos",
            "icon" => ""
        )
    );

    switch ($_SESSION["win_perfil2"]) {
        case "CONCESIONARIO":
            /* Agrega un país a un menú, almacenando id, nombre e ícono en un array. */

            array_push($paisesparamenu,
                array(
                    "id" => $Pais->paisId,
                    "value" => $Pais->paisNom,
                    "icon" => strtolower($Pais->iso)
                )
            );
            break;
        case "CONCESIONARIO2":
            /* Agrega información sobre un país a un menú según la opción seleccionada. */

            array_push($paisesparamenu,
                array(
                    "id" => $Pais->paisId,
                    "value" => $Pais->paisNom,
                    "icon" => strtolower($Pais->iso)
                )
            );
            break;
        case "PUNTOVENTA":
            /* Agrega elementos de país a un arreglo usando ID, nombre e ícono. */


            array_push($paisesparamenu,
                array(
                    "id" => $Pais->paisId,
                    "value" => $Pais->paisNom,
                    "icon" => strtolower($Pais->iso)
                )
            );
            break;

        case "CAJERO":
            /* agrega un país a un menú específico según el caso "CAJERO". */

            array_push($paisesparamenu,
                array(
                    "id" => $Pais->paisId,
                    "value" => $Pais->paisNom,
                    "icon" => strtolower($Pais->iso)
                )
            );
            break;

        case "USUARIO":
            /* Agrega información de un país a un array si se cumple una condición. */

            array_push($paisesparamenu,
                array(
                    "id" => $Pais->paisId,
                    "value" => $Pais->paisNom,
                    "icon" => strtolower($Pais->iso)
                )
            );
            break;
        default:
            if ($_SESSION['PaisCond'] == "S") {

                /*array_push($paisesparamenu,
                    array(
                        "id" => $Pais->paisId,
                        "value" => $Pais->paisNom,
                        "icon" => strtolower($Pais->iso)
                    )
                );*/


                /* Se crea un arreglo con información del país, incluyendo ID, nombre e icono. */
                $array = [];

                $array["id"] = $Pais->paisId;
                $array["value"] = $Pais->paisNom;
                $array["icon"] = strtolower($Pais->iso);
                $array['currencies'] = [];

                /* Agrega información de moneda a un arreglo y luego lo añade a otro arreglo. */
                array_push($array['currencies'], [
                    'Id' => $_SESSION["moneda"],
                    'Name' => $_SESSION["moneda"]
                ]);

                array_push($paisesparamenu, $array);

            } else {


                if ($_SESSION['Global'] == "N") {

                    /* Se crea una instancia de PaisMandante y se define una regla de validación. */
                    $PaisMandante = new PaisMandante();


                    $rules = [];

                    array_push($rules, array("field" => "pais_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));

                    /* Se define un filtro de búsqueda y se obtienen países mandantes personalizados. */
                    array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);


                    /* Convierte datos de países en un formato estructurado para un menú. */
                    $paises = json_decode($paises);

                    $paisesparamenu = [];

                    foreach ($paises->data as $key => $value) {

                        $array = [];

                        $array["id"] = $value->{"pais.pais_id"};
                        $array["value"] = $value->{"pais.pais_nom"};
                        $array["icon"] = strtolower($value->{"pais.iso"});
                        $array['currencies'] = [];
                        array_push($array['currencies'], [
                            'Id' => $value->{'pais_mandante.moneda'},
                            'Name' => $value->{'pais_mandante.moneda'}
                        ]);
                        array_push($paisesparamenu, $array);

                    }

                }


                if ($_SESSION['Global'] == "S") {

                    /* Se crean instancias de las clases 'Pais' y 'PaisMandante' y se inicializa un array de reglas. */
                    $Pais = new Pais();

                    $PaisMandante = new PaisMandante();


                    $rules = [];


                    /* Se definen reglas de filtrado para base de datos en PHP con condiciones dinámicas. */
                    array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));

                    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                        array_push($rules, array("field" => "pais_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                    }

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* convierte un filtro a JSON y obtiene países desde la base de datos. */
                    $json = json_encode($filtro);


                    $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);

                    $paises = json_decode($paises);


                    /* Crea un arreglo de IDs de países desde un objeto de datos. */
                    $paisesArray = [];

                    foreach ($paises->data as $key => $value) {

                        $array = [];
                        $array["id"] = $value->{"pais.pais_id"};
                        array_push($paisesArray, $array["id"]);


                    }


                    /* Se definen reglas para validar campos de país y estado en un formulario. */
                    $rules = [];


                    array_push($rules, array("field" => "pais.pais_id", "data" => implode(", ", $paisesArray), "op" => "in"));


                    array_push($rules, array("field" => "pais.estado", "data" => "A", "op" => "eq"));


                    /* Se crea un filtro JSON para obtener países desde una base de datos. */
                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    $paises = $Pais->getPaisesCustom("pais.pais_id", "asc", 0, 1000, $json, true);

                    $paises = json_decode($paises);


                    /* Se define un array en PHP con un país y su correspondiente icono. */
                    $paisesparamenu = array(
                        array(
                            "id" => "0",
                            "value" => "Todos",
                            "icon" => ""
                        )
                    );

                    /* Itera sobre países, extrayendo datos para formar un nuevo arreglo estructurado. */
                    foreach ($paises->data as $key => $value) {

                        $array = [];

                        $array["id"] = $value->{"pais.pais_id"};
                        $array["value"] = $value->{"pais.pais_nom"};
                        $array["icon"] = strtolower($value->{"pais.iso"});
                        $array['currencies'] = [];
                        array_push($array['currencies'], [
                            'Id' => $value->{'pais_mandante.moneda'},
                            'Name' => $value->{'pais_mandante.moneda'}
                        ]);

                        array_push($paisesparamenu, $array);

                    }

                }

            }


            break;

    }

    return $paisesparamenu;
}


/**
 * Obtener menú
 *
 * @param no
 *
 * @return String $menus_string devuelve los menus establecidos para la plataforma
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function obtenerMenu()
{

    $menus_string = json_decode(
        '[
                {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
                
                {
    "id": "informationFather",
    "icon": "icon-pie-chart",
    "value": "Informes",
    "data": [
        {
            "id": "informationCasino",
            "value": "Casino",
            "add": true,
            "edit": true,
            "delete": true
        },
        {
            "id": "informationLiveCasino",
            "value": "Casino en vivo",
            "add": true,
            "edit": true,
            "delete": true
        },
        {
            "id": "informationVirtual",
            "value": "Virtuales",
            "add": true,
            "edit": true,
            "delete": true
        },
        {
            "id": "informationHipica",
            "value": "Hípica",
            "add": true,
            "edit": true,
            "delete": true
        }
    ],
    "add": true,
    "edit": true,
    "delete": true
},
                {
                    "id": "productsFather", "icon": "icon-cubes", "value": "Products", "data": [
                        {"id": "providers", "value": "Providers"},
                        {"id": "products", "value": "Products"},
                        {"id": "partnersProducts", "value": "Partners Products"},
                        {"id": "partnersProductsCountry", "value": "Partners Products Country"},
                        {"id": "categories", "value": "Categories"},
                        {"id": "categoriesProducts", "value": "CategoriesProducts"}
                        
                    ]
                },
                {"id": "players", "icon": "icon-players", "value": "Players"},
                {
                    "id": "reports", "icon": "icon-pie-chart", "value": "Reports", "data": [
                        {"id": "depositReport", "value": "Deposit Report"},
                        {"id": "autoexclusionUsers", "value": "Autoexclusion Users"},
                        {"id": "casinoGamesReport", "value": "Casino Games Report"},
                        {"id": "bonusReport", "value": "Bonus Report"},
                        {"id": "playersReport", "value": "Players Report"},
                        {"id": "historicalCashFlow", "value": "Historical Cash Flow"},
                        {"id": "summaryCashFlow", "value": "Summary Cash Flow"},
                        {"id": "summaryCashFlow2", "value": "2.Summary Cash Flow"},
                        {"id": "informeGerencial", "value": "Gerencial Report"},
                        {"id": "informeGerencial2", "value": "2.Gerencial Report"},
                        {"id": "betsReport", "value": "Bets Report"}
                    ]
                },
                {
                    "id": "security", "icon": "icon-security", "value": "Security", "data": [
                        {"id": "adminUser", "value": "Admin User"},
                        {"id": "contingency", "value": "Contingency"},
                        {"id": "menus", "value": "Menus"},
                        {"id": "profile", "value": "Profile"},
                        {"id": "profileOptions", "value": "Profile - Options"},
                        {"id": "subMenu", "value": "Sub Menu"},
                        {"id": "userProfile", "value": "User Profile"}
                    ]
                },
                {
                    "id": "teacher", "icon": "icon-storage", "value": "Teacher", "data": [
                        {"id": "qualifying", "value": "Qualifying"},
                        {"id": "franchisee", "value": "Franchisee"},
                        {"id": "registeredDocuments", "value": "Registered Documents"}
                    ]
                },
                {
                    "id": "Management", "icon": "icon-database", "value": "Management", "data": [
                        {"id": "adjustPayment", "value": "Adjust Payment"},
                        {"id": "assignmentQuota", "value": "assignment Quota"},
                        {"id": "bonus", "value": "Bonus"},
                        {"id": "eliminateNoteWithdraw", "value": "Eliminate Note Withdraw"},
                        {"id": "managementNetwork", "value": "Management Network"},
                        {"id": "registerFast", "value": "Register Fast"},
                        {"id": "reprintCheck", "value": "Reprint Check"},
                        {"id": "reversionReload", "value": "Reversion Reload"},
                        {"id": "managementContact", "value": "Management Contact"}
                    ]
                },
                {
                    "id": "Cash", "icon": "icon-banknote", "value": "Cash", "data": [
                        {"id": "flujoCaja", "value": "Flujo de Caja"},
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"}
                    ]
                },
                {
                    "id": "queries", "icon": "icon-file-text", "value": "Queries", "data": [
                        {"id": "flujoCajaHistorico", "value": "Flujo Caja Historico"},
                        {"id": "flujoCajaResumido", "value": "Flujo Caja Resumido"},
                        {"id": "informeCasino", "value": "Informe Casino"},
                        {"id": "informeGerencial", "value": "Informe Gerencial"},
                        {"id": "listadoRecargasRetiros", "value": "Listado Recargas Retiros"},
                        {"id": "premiosPendientesPagar", "value": "Premios Pendientes Pagar"},
                        {"id": "consultaOnlineDetalle", "value": "Consulta Online Detalle"},
                        {"id": "consultaOnlineResumen", "value": "Consulta Online Resumen"}
                    ]
                },
                {
                    "id": "betShopManagement", "icon": "icon-shop", "value": "Bet Shop Management", "data": [
                        {"id": "betShop", "value": "Bet Shop"},
                        {"id": "managePointsGraphics", "value": "Manage Points Graphics"}
                    ]
                },
                {
                    "id": "agentSystem", "icon": "icon-user-secret", "value": "Agent System", "data": [
                        {"id": "myAccount", "value": "My Account"},
                        {"id": "agentList", "value": "Agent List"},
                        {"id": "agentsTree", "value": "Agents Tree"},
                        {"id": "subAccounts", "value": "Sub Accounts"},
                        {"id": "playersList", "value": "Players List"},
                        {"id": "transfers", "value": "Transfers"},
                        {"id": "groupManagement", "value": "Group Management"}
                    ]
                },
                {
                    "id": "financial", "icon": "icon-financial", "value": "Financial", "data": [
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"},
                        {"id": "depositRequests", "value": "Deposit Requests"},
                        {"id": "withdrawalRequests", "value": "Withdrawal Requests"},
                        {"id": "transactionss", "value": "Transactions"}
                    ]
                },
                {
                    "id": "tools", "icon": "icon-tools", "value": "Tools", "data": [
                        {"id": "partnerSettings", "value": "Partner Settings"},
                        {"id": "translationManager", "value": "Translation Manager"},
                        {"id": "emailTemplate", "value": "Email Template"},
                        {"id": "messagesList", "value": "Messages List"}
                    ]
                },

                {"id": "transactions", "value": "Transactions", "icon": "mdi mdi-cart"},
                {"id": "customers", "value": "Customers", "icon": "mdi mdi-account-box"},
                {"id": "payhistoryview", "value": "Payment History", "icon": "mdi mdi-chart-areaspline"},
                {"id": "widgets", "value": "Widgets", "icon": "mdi mdi-widgets"},
                {"id": "demos", "value": "Demos", "icon": "mdi mdi-monitor-dashboard"},
                {"id": "prices", "value": "Prices", "icon": "mdi mdi-currency-usd"},
                {"id": "tutorials", "value": "Tutorials", "icon": "mdi mdi-school"}
            ]'
    );
    /*
                             {"id": "accounting.position", "value": "Cargo", "add": true},
                            {"id": "accounting.typeCenterPosition", "value": "Tipos", "add": true},
                            {"id": "accounting.employees", "value": "Empleados", "add": true},

                            {"id": "accounting.productsThirdBetShop", "value": "Productos terceros", "add": true},
                            {"id": "accounting.productsThirdByBetShop", "value": "Productos terceros Punto de venta", "add": true},
                            {"id": "accounting.closingDayReport", "value": "Reporte cierre de dia"},
                            {"id": "accounting.squareDayReport", "value": "Reporte de cuadre de dia", "add": true},


     */
    $menu_string = json_decode(
        '[
                {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
                {"id": "menuBonus", "icon": "icon-gift", "value": "Torneos y Bonos"},
                {"id": "menuMarketing", "icon": "icon-pie-chart", "value": "Marketing"},
                {"id": "menuBuilder", "icon": "icon-tools", "value": "Site builder"},
{
                    "id": "myconfiguration", "icon": "icon-tools", "value": "Mi Configuracion", "data": [
                        {"id": "myConfiguration.myInformation", "value": "Mi Información"},
                        {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                        {"id": "myConfiguration.qrgoogle", "value": "QR Google"}
                        
                    ]
                },
                
                {
                    "id": "productsFather", "icon": "icon-cubes", "value": "Productos", "data": [
                        {"id": "partners", "value": "Partners"},
                        {"id": "providers", "value": "Proveedores"},
                        {"id": "products", "value": "Productos"},
                        {"id": "partnersProviders", "value": "Partners Proveedores"},
                        {"id": "partnersTypeProduct", "value": "Partners Tipo Producto"},
                        {"id": "partnersProducts", "value": "Partners Productos"},
                        {"id": "partnersProductsCountry", "value": "Partners Productos País"},
                        {"id": "categories", "value": "Categorías"},
                        {"id": "categoriesProducts", "value": "Categorías Productos"},
                        {"id": "depositReportWallet", "value": "Billeteras"}


                    ]
                },
                {
    "id": "informationFather",
    "icon": "icon-pie-chart",
    "value": "Informes",
    "data": [
        {
            "id": "informationCasino",
            "value": "Casino",
            "add": true,
            "edit": true,
            "delete": true
        },
        {
            "id": "informationLiveCasino",
            "value": "Casino en vivo",
            "add": true,
            "edit": true,
            "delete": true
        },
        {
            "id": "informationVirtual",
            "value": "Virtuales",
            "add": true,
            "edit": true,
            "delete": true
        },
        {
            "id": "informationHipica",
            "value": "Hípica",
            "add": true,
            "edit": true,
            "delete": true
        }
    ],
    "add": true,
    "edit": true,
    "delete": true
},
                {"id": "betsSport", "icon": "icon-players", "value": "Apuestas Deportivas"},
                {"id": "betsSportLive", "icon": "icon-players", "value": "Apuestas deportivas en vivo"},
                {"id": "betsVirtual", "icon": "icon-players", "value": "Apuestas Virtuales"},
                {"id": "betsHistory", "icon": "icon-players", "value": "Reporte de Apuestas"},
                {"id": "betsHistory2", "icon": "icon-players", "value": "Apuestas Antiguas"},
                
                {"id": "adminUserManagement", "icon": "icon-players", "show":"false", "value": "adminUserManagement"},
                {"id": "customers", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "customersAggregator", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "agentListManagement", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "addAgentListManagement", "icon": "icon-players", "show":"false", "value": "addAgentListManagement"},
                {"id": "addBetShopManagement", "icon": "icon-players", "show":"false", "value": "addBetShopManagement"},
                {"id": "addAdminUserManagement", "icon": "icon-players", "show":"false", "value": "addAdminUserManagement"},
                 {"id": "machine.addMachineManagement", "icon": "icon-players", "show":"false", "value": "Añadir Maquina"},
                {"id": "machine.machineManagement", "icon": "icon-players", "show":"false", "value": "Detalles maquina "},
                 {"id": "settings", "icon": "icon-players", "show":"false", "value": "settings"},

                {"id": "aggregatorList", "icon": "icon-players", "show":"true", "value": "Jugadores Partner"},

                {"id": "playersList", "icon": "icon-players", "value": "Jugadores"},
                {"id": "playersListAggregator", "icon": "icon-players", "value": "Jugadores Aggregator"},
                {
                    "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                        {"id": "depositReport", "value": "Reporte de depósitos"},
                                             {"id": "depositReportBetShop", "value": "Reporte de depósitos"},
                        {"id": "withdrawalRequestsForBetShop", "value": "Reporte de retiros"},
   {"id": "autoexclusionUsers", "value": "Autoexclusiones de Usuario"},
                        {"id": "casinoGamesReport", "value": "Reporte de casino "},
                        {"id": "casinoGamesReport2", "value": "2.Reporte de casino "},
                        {"id": "balanceUsers", "value": "Reporte de Saldos"},
                        {"id": "bonusReport", "value": "Reporte de bonos"},
                        {"id": "bonusReport2", "value": "Reporte de bonos Redimidos"},
                        {"id": "playersReport", "value": "Reporte de Jugadores"},
                        {"id": "historicalCashFlow", "value": "Flujo de Caja Histórico"},
                        {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                        {"id": "summaryCashFlow2", "value": "2.Flujo de Caja Resumido"},
                        {"id": "informeGerencial", "value": "Informe Gerencial"},
                        {"id": "informeGerencial2", "value": "2.Informe Gerencial"},
                        {"id": "betsReportBetShop", "value": "Reporte de Apuestas"},
                        {"id": "betsReport", "value": "Reporte de Apuestas"},
                        {"id": "transactionReport", "value": "Transacciones Apuestas"},
                        {"id": "couponsReport", "value": "Reporte de Cupones"},
                        {"id": "usuarioOnlineResumido", "value": "Usuario online Resumido"},
                        {"id": "transactionReport", "value": "Reporte de transacciones de apuestas"},
                        {"id": "relationUserAggregator", "value": "Usuario - Agregator"},
                        {"id": "paidPendingAwards", "value": "Premios pendientes por pagar"},
                        {"id": "sessionsReport", "value": "Reporte de Sesiones"},
                        {"id": "balanceAdjustments", "value": "Reporte de Ajustes de Saldos"},
                        {"id": "sportsTransactions", "value": "Reporte Trans. Sportbook"},
                        {"id": "historyMovement", "value": "Reporte de  Movimientos Saldo"},
                        {"id": "balanceHistory", "value": "Historico de  Saldos"},
                        {"id": "balanceHistory2", "value": "2.Historico de  Saldos"},
                        {"id": "balanceHistoryBetShop", "value": "Historico Saldos Punto Venta"},
                        {"id": "balanceHistoryBetShop2", "value": "2.Historico Saldos Punto Venta"},
                        {"id": "balanceHistoryForBetShop", "value": "Historico Saldos Punto Venta"},
                        {"id": "balanceHistoryForAgent", "value": "Historico Saldos Agentes"},
                        {"id": "balanceHistoryAdminUser", "value": "Historico Saldos Administrativos"},
                                                {"id": "registeredDocument", "value": "Documentos Registrados"},
                        {"id": "comissionsReport", "value": "Reporte Comisiones Global"},
                        {"id": "comissionsReport2", "value": "V2-Reporte Comisiones Global 2"},
                        {"id": "myComissionsReport", "value": "Reporte Mis Comisiones"},
                        {"id": "logsUserGeneral", "value": "Reporte General Logs"},
                                                {"id": "assignmentQuotaR", "value": "Reporte de transferencias de saldo Recibidas"},
                                                {"id": "assignmentQuotaMade", "value": "Reporte de transferencias de saldo Realizadas"},

                        
                        {"id": "userAffiliatesAgent", "value": "Reporte de Afiliados"},
                        {"id": "userAffiliatesBetShop", "value": "Reporte de Afiliados"},
                        {"id": "sessionsReport2", "value": "Reporte de Sesiones"}
                        


                    ]
                },
                
                
                {
                    "id": "accounting", "icon": "icon-security", "value": "Contabilidad", "data": [
                        {"id": "accounting.costCenter", "value": "Centros de costo", "add": true},
                        {"id": "accounting.area", "value": "Estructural", "add": true},
                        {"id": "accounting.expenses", "value": "Egresos", "add": true},
                        {"id": "accounting.incomes", "value": "Ingresos", "add": true},
                        {"id": "accounting.providers", "value": "Productos Terceros ", "add": true},
                        {"id": "accounting.concepts", "value": "Conceptos", "add": true},
                        {"id": "accounting.accounts", "value": "Cuentas", "add": true},
                        {"id": "accounting.squareDayReport2", "value": "Cierres de Caja", "add": true}

                    ]
                },
                {
                    "id": "security", "icon": "icon-security", "value": "Seguridad", "data": [
                        {"id": "approvalLogs", "value": "Aprobar logs", "add": true},

                        {"id": "adminUser", "value": "Usuarios administrativos"},
                        {"id": "usuariosbloqueados", "value": "Usuarios Bloqueados"},
                        {"id": "contingency", "value": "Contingencia"},
                        {"id": "menus", "value": "Menus"},
                        {"id": "profile", "value": "Perfiles"},
                        {"id": "profileOptions", "value": "Perfiles - Options"},
                        {"id": "subMenu", "value": "Sub Menu"},
                        {"id": "userProfile", "value": "Usuario Perfil"},
                        {"id": "competitors.competitors", "value": "Competidores"}
                    ]
                },
                {
                    "id": "management", "icon": "icon-security", "value": "Gestion", "data": [
                        {"id": "promotionalCodes", "value": "Codigos Promocionales", "add": true},
                        {"id": "activateRegistration", "value": "Activar Registros", "add": true},
                        {"id": "adjustPayment", "value": "Ajustar pasarela de pago"},
                        {"id": "assignmentQuota", "value": "Reporte de transferencias de saldo"},
                        {"id": "bonus", "value": "Bonos"},
                        {"id": "eliminateNoteWithdraw", "value": "Eliminar nota de retiro"},
                        {"id": "managementNetwork", "value": "Manejar red"},
                        {"id": "registerFast", "value": "Registro rapido"},
                        {"id": "reprintCheck", "value": "Reimprimir cheque"},
                        {"id": "reversionReload", "value": "Reversion"},
                        {"id": "managementContact", "value": "Trabaja con nosotros"},
                        {"id": "managementContactUser", "value": "Contacto"},
                        {"id": "managementClaims", "value": "Reclamaciones"},
                        {"id": "coupons", "value": "Cupones de Recarga"}
                    ]
                },
                {
                    "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                        {"id": "flujoCaja", "value": "Flujo de Caja"},
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"},
                        {"id": "recargarCreditoAgente", "value": "Recargar Agentes"}
                    ]
                },
                
                {
                    "id": "messages", "icon": "icon-pie-chart", "value": "Mensajes", "data": [
                        {"id": "messages.messageList", "value": "Lista"}
                                            ]
                },
                
                {
                    "id": "machine", "icon": "icon-pie-chart", "value": "Maquinas", "data": [
                        {"id": "machine.machineRegister", "value": "Lista"},
                        {"id": "machine.information", "value": "Registrar"},
                        {"id": "machine.pagoPremioMaquina", "value": "Pago Premio"},
                        {"id": "machine.pagoNotaCobro", "value": "Pago Nota de cobro"},
                                                {"id": "machine.managePointsGraphics", "value": "Maquinas Grafica"}

                                            ]
                },
                {
                    "id": "tools", "icon": "icon-pie-chart", "value": "Herramientas", "data": [
                                            {"id": "partner.PartnerSettings", "value": "Partner Ajustes"},

                        {"id": "tools.translationManager", "value": "Traducción"},
                                                {"id": "tools.uploadImage", "value": "Subir imagen"}

                                            ]
                },
                
                
                {
                    "id": "betShopManagement", "icon": "icon-shop", "value": "Gestión Punto de Venta", "data": [
                        {"id": "betShop", "value": "Punto de Venta"},
                        {"id": "cashiers", "value": "Cajeros"},
                        {"id": "managePointsGraphics", "value": "Gestión Puntos Gráfico"},
                        {"id": "betShopCompetence", "value": "Puntos de venta Competencia"}
                    ]
                },
                {
                    "id": "agentSystem", "icon": "icon-user-secret", "value": "Agentes", "data": [
                        {"id": "agentList", "value": "Lista de Agentes"},
                        {"id": "agentsTree", "value": "Árbol de Agentes"},
                        {"id": "agentsInform", "value": "Informe de Agentes"},                        
                        {"id": "agentTransfers", "value": "Transferencias de saldo"},
                        {"id": "agentRetraction", "value": "Retracción de saldo"},
                        {"id": "agent.requestsAgent", "value": "Solicitudes"},
                        {"id": "agent.requirementsAgent", "value": "Requisitos"}

                    ]
                },
                {
                    "id": "financial", "icon": "icon-financial", "value": "Financiero", "data": [
                        {"id": "depositRequests", "value": "Solicitudes de Deposito"},
                        {"id": "withdrawalRequests", "value": "Solicitudes de Retiro"}
                    ]
                }   ,
                {"id": "closeBox", "icon": "icon-financial", "value": "Cierre de caja"},
                {"id": "closeBoxEdit", "icon": "icon-financial", "show":"false", "value": "Cierre de caja"}
                         ]'
    );

    if ($_SESSION["mandante"] != "0") {

        $menu_string = json_decode(
            '[
                {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
                {"id": "menuBonus", "icon": "icon-gift", "value": "Torneos y Bonos"},
                {"id": "menuMarketing", "icon": "icon-pie-chart", "value": "Marketing"},
                {"id": "menuBuilder", "icon": "icon-tools", "value": "Site builder"},

{
                    "id": "myconfiguration", "icon": "icon-tools", "value": "Mi Configuracion", "data": [
                        {"id": "myConfiguration.myInformation", "value": "Mi Información"},
                        {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                        {"id": "myConfiguration.qrgoogle", "value": "QR Google"}
                        
                    ]
                },
                
                {
                    "id": "productsFather", "icon": "icon-cubes", "value": "Productos", "data": [
                        {"id": "partners", "value": "Partners"},
                        {"id": "providers", "value": "Proveedores"},
                        {"id": "products", "value": "Productos"},
                        {"id": "partnersProviders", "value": "Partners Proveedores"},
                        {"id": "partnersTypeProduct", "value": "Partners Tipo Producto"},
                        {"id": "partnersProducts", "value": "Partners Productos"},
                        {"id": "partnersProductsCountry", "value": "Partners Productos País"},
                        {"id": "categories", "value": "Categorías"},
                        {"id": "categoriesProducts", "value": "Categorías Productos"}

                    ]
                },
                {
    "id": "informationFather",
    "icon": "icon-pie-chart",
    "value": "Informes",
    "data": [
        {
            "id": "informationCasino",
            "value": "Casino",
            "add": true,
            "edit": true,
            "delete": true
        },
        {
            "id": "informationLiveCasino",
            "value": "Casino en vivo",
            "add": true,
            "edit": true,
            "delete": true
        },
        {
            "id": "informationVirtual",
            "value": "Virtuales",
            "add": true,
            "edit": true,
            "delete": true
        },
        {
            "id": "informationHipica",
            "value": "Hípica",
            "add": true,
            "edit": true,
            "delete": true
        }
    ],
    "add": true,
    "edit": true,
    "delete": true
},
                {"id": "betsSport", "icon": "icon-players", "value": "Apuestas Deportivas"},
                {"id": "betsSportLive", "icon": "icon-players", "value": "Apuestas deportivas en vivo"},
                {"id": "betsVirtual", "icon": "icon-players", "value": "Apuestas Virtuales"},
                {"id": "betsHistory", "icon": "icon-players", "value": "Reporte de Apuestas"},
                                {"id": "betsHistory2", "icon": "icon-players", "value": "Apuestas Antiguas"},
                {"id": "adminUserManagement", "icon": "icon-players", "show":"false", "value": "adminUserManagement"},
                {"id": "customers", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "customersAggregator", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "agentListManagement", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "addAgentListManagement", "icon": "icon-players", "show":"false", "value": "addAgentListManagement"},
                {"id": "addBetShopManagement", "icon": "icon-players", "show":"false", "value": "addBetShopManagement"},
                {"id": "addAdminUserManagement", "icon": "icon-players", "show":"false", "value": "addAdminUserManagement"},
                 {"id": "machine.addMachineManagement", "icon": "icon-players", "show":"false", "value": "Añadir Maquina"},
                {"id": "machine.machineManagement", "icon": "icon-players", "show":"false", "value": "Detalles maquina "},
                 {"id": "settings", "icon": "icon-players", "show":"false", "value": "settings"},

                {"id": "aggregatorList", "icon": "icon-players", "show":"true", "value": "Jugadores Partner"},

                {"id": "playersList", "icon": "icon-players", "value": "Jugadores"},
                {"id": "playersListAggregator", "icon": "icon-players", "value": "Jugadores Aggregator"},
                {
                    "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                        {"id": "depositReport", "value": "Reporte de depósitos"},
                                             {"id": "depositReportBetShop", "value": "Reporte de depósitos"},
                        {"id": "withdrawalRequestsForBetShop", "value": "Reporte de retiros"},
                        {"id": "autoexclusionUsers", "value": "Autoexclusiones de Usuario"},
                        {"id": "casinoGamesReport", "value": "Reporte de casino "},
                        {"id": "casinoGamesReport2", "value": "2.Reporte de casino "},
                        {"id": "balanceUsers", "value": "Reporte de Saldos"},
                        {"id": "bonusReport", "value": "Reporte de bonos"},
                        {"id": "bonusReport2", "value": "Reporte de bonos Redimidos"},
                        {"id": "playersReport", "value": "Reporte de Jugadores"},
                        {"id": "historicalCashFlow", "value": "Flujo de Caja Histórico"},
                        {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                        {"id": "summaryCashFlow2", "value": "2.Flujo de Caja Resumido"},
                        {"id": "informeGerencial", "value": "Informe Gerencial"},
                        {"id": "informeGerencial2", "value": "2.Informe Gerencial"},
                        {"id": "betsReport", "value": "Reporte de Apuestas"},
                        {"id": "transactionReport", "value": "Transacciones Apuestas"},
                        {"id": "couponsReport", "value": "Reporte de Cupones"},
                        {"id": "usuarioOnlineResumido", "value": "Usuario online Resumido"},
                         {"id": "transactionReport", "value": "Reporte de transacciones de apuestas"},
                        {"id": "relationUserAggregator", "value": "Usuario - Agregator"},
                        {"id": "paidPendingAwards", "value": "Premios pendientes por pagar"},
                        {"id": "sessionsReport", "value": "Reporte de Sesiones"},
                        {"id": "balanceAdjustments", "value": "Reporte de Ajustes de Saldos"},
                        {"id": "sportsTransactions", "value": "Reporte Trans. Sportbook"},
                        {"id": "historyMovement", "value": "Reporte de  Movimientos Saldo"},
                        {"id": "balanceHistory", "value": "Historico de  Saldos"},
                        {"id": "balanceHistory2", "value": "2.Historico de  Saldos"},
                        {"id": "balanceHistoryBetShop", "value": "Historico Saldos Punto Venta"},
                        {"id": "balanceHistoryBetShop2", "value": "2.Historico Saldos Punto Venta"},
                        {"id": "balanceHistoryForBetShop", "value": "Historico Saldos Punto Venta"},
                        {"id": "balanceHistoryForAgent", "value": "Historico Saldos Agentes"},
                         {"id": "balanceHistoryAdminUser", "value": "Historico Saldos Administrativos"},
                         {"id": "registeredDocument", "value": "Documentos Registrados"},
                        {"id": "comissionsReport", "value": "Reporte Comisiones Global"},
                        {"id": "comissionsReport2", "value": "V2-Reporte Comisiones Global 2"},
                        {"id": "myComissionsReport", "value": "Reporte Mis Comisiones"},
                        {"id": "logsUserGeneral", "value": "Reporte General Logs"},
                                                {"id": "assignmentQuotaR", "value": "Reporte de transferencias de saldo Recibidas"},
                                                {"id": "assignmentQuotaMade", "value": "Reporte de transferencias de saldo Realizadas"},

                        {"id": "userAffiliatesAgent", "value": "Reporte de Afiliados"},
                        {"id": "userAffiliatesBetShop", "value": "Reporte de Afiliados"},
                        {"id": "sessionsReport2", "value": "Reporte de Sesiones"}


                    ]
                },
                
                
                {
                    "id": "accounting", "icon": "icon-security", "value": "Contabilidad", "data": [
                        {"id": "accounting.costCenter", "value": "Centros de costo", "add": true},
                        {"id": "accounting.area", "value": "Estructural", "add": true},
                        {"id": "accounting.expenses", "value": "Egresos", "add": true},
                        {"id": "accounting.incomes", "value": "Ingresos", "add": true},
                        {"id": "accounting.providers", "value": "Productos Terceros ", "add": true},
                        {"id": "accounting.concepts", "value": "Conceptos", "add": true},
                        {"id": "accounting.accounts", "value": "Cuentas", "add": true},
                        {"id": "accounting.squareDayReport2", "value": "Cierres de Caja", "add": true}

                    ]
                },
                {
                    "id": "security", "icon": "icon-security", "value": "Seguridad", "data": [
                        {"id": "approvalLogs", "value": "Aprobar logs", "add": true},

                        {"id": "adminUser", "value": "Usuarios administrativos"},
                        {"id": "usuariosbloqueados", "value": "Usuarios Bloqueados"},
                        {"id": "contingency", "value": "Contingencia"},
                        {"id": "menus", "value": "Menus"},
                        {"id": "profile", "value": "Perfiles"},
                        {"id": "profileOptions", "value": "Perfiles - Options"},
                        {"id": "subMenu", "value": "Sub Menu"},
                        {"id": "userProfile", "value": "Usuario Perfil"},
                        {"id": "competitors.competitors", "value": "Competidores"}
                    ]
                },
                {
                    "id": "management", "icon": "icon-security", "value": "Gestion", "data": [
                        {"id": "promotionalCodes", "value": "Codigos Promocionales", "add": true},
                        {"id": "activateRegistration", "value": "Activar Registros", "add": true},
                        {"id": "adjustPayment", "value": "Ajustar pasarela de pago"},
                        {"id": "assignmentQuota", "value": "Reporte de transferencias de saldo"},
                        {"id": "bonus", "value": "Bonos"},
                        {"id": "eliminateNoteWithdraw", "value": "Eliminar nota de retiro"},
                        {"id": "managementNetwork", "value": "Manejar red"},
                        {"id": "registerFast", "value": "Registro rapido"},
                        {"id": "reprintCheck", "value": "Reimprimir cheque"},
                        {"id": "reversionReload", "value": "Reversion"},
                        {"id": "managementContact", "value": "Trabaja con nosotros"},
                        {"id": "managementContactUser", "value": "Contacto"},
                        {"id": "managementClaims", "value": "Reclamaciones"},
                        {"id": "coupons", "value": "Cupones de Recarga"}
                    ]
                },
                {
                    "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                        {"id": "flujoCaja", "value": "Flujo de Caja"},
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"},
                        {"id": "recargarCreditoAgente", "value": "Recargar Agentes"}
                    ]
                },
                
                {
                    "id": "messages", "icon": "icon-pie-chart", "value": "Mensajes", "data": [
                        {"id": "messages.messageList", "value": "Lista"}
                                            ]
                },
                
                {
                    "id": "machine", "icon": "icon-pie-chart", "value": "Maquinas", "data": [
                        {"id": "machine.machineRegister", "value": "Lista"},
                        {"id": "machine.information", "value": "Registrar"},
                        {"id": "machine.pagoPremioMaquina", "value": "Pago Premio"},
                        {"id": "machine.pagoNotaCobro", "value": "Pago Nota de cobro"},
                                                {"id": "machine.managePointsGraphics", "value": "Maquinas Grafica"}

                                            ]
                },
                {
                    "id": "tools", "icon": "icon-pie-chart", "value": "Herramientas", "data": [
                                            {"id": "partner.PartnerSettings", "value": "Partner Ajustes"},

                        {"id": "tools.translationManager", "value": "Traducción"},
                                                {"id": "tools.uploadImage", "value": "Subir imagen"}

                                            ]
                },
                
                
                {
                    "id": "betShopManagement", "icon": "icon-shop", "value": "Gestión Punto de Venta", "data": [
                        {"id": "betShop", "value": "Punto de Venta"},
                        {"id": "cashiers", "value": "Cajeros"},
                        {"id": "managePointsGraphics", "value": "Gestión Puntos Gráfico"},
                        {"id": "betShopCompetence", "value": "Puntos de venta Competencia"}
                    ]
                },
                {
                    "id": "agentSystem", "icon": "icon-user-secret", "value": "Agentes", "data": [
                        {"id": "agentList", "value": "Lista de Agentes"},
                        {"id": "agentsTree", "value": "Árbol de Agentes"},
                        {"id": "agentsInform", "value": "Informe de Agentes"},
                        {"id": "agentTransfers", "value": "Transferencias de saldo"},
                        {"id": "agentRetraction", "value": "Retracción de saldo"},
                        {"id": "agent.requestsAgent", "value": "Solicitudes"},
                        {"id": "agent.requirementsAgent", "value": "Requisitos"}

                    ]
                },
                {
                    "id": "financial", "icon": "icon-financial", "value": "Financiero", "data": [
                        {"id": "depositRequests", "value": "Solicitudes de Deposito"},
                        {"id": "withdrawalRequests", "value": "Solicitudes de Retiro"}
                    ]
                } 
                         ]'
        );
    }

    /*
        switch ($_SESSION["win_perfil2"]) {
            case "PUNTOVENTA":
                $menu_string = json_decode(
                    '[
                    {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
                                    {"id": "betting", "icon": "icon-dashboard", "value": "Apuestas"},
                    {"id": "bettingVirtual", "icon": "icon-dashboard", "value": "Apuestas Virtuales"},
                    {
                        "id": "myconfiguration", "icon": "icon-cubes", "value": "Mi Configuracion", "data": [
                            {"id": "myConfiguration.myInformation", "value": "Mi Informacion"},
                            {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                            {"id": "myConfiguration.qrgoogle", "value": "QR Google"}

                        ]
                    },

                    {
                        "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                            {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                                                                            {"id": "betsReportSecond", "value": "Reporte de Apuestas"}

                                                ]
                    },
                    {
                        "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                            {"id": "flujoCaja", "value": "Flujo de Caja"},
                            {"id": "pagoPremio", "value": "Pago Premio"},
                            {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                            {"id": "recargarCredito", "value": "Recargar Credito"}
                        ]
                    },
                    {
                        "id": "betShopManagement", "icon": "icon-shop", "value": "Gestión Punto de Venta", "data": [
                            {"id": "cashiers", "value": "Cajeros"}
                                                ]
                    }
                           ]'
                );

                break;

            case "CAJERO":
                $menu_string = json_decode(
                    '[
                    {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
                    {"id": "betting", "icon": "icon-dashboard", "value": "Apuestas"},
                    {"id": "bettingVirtual", "icon": "icon-dashboard", "value": "Apuestas Virtuales"},
                    {
                        "id": "myconfiguration", "icon": "icon-cubes", "value": "Mi Configuracion", "data": [
                            {"id": "myConfiguration.myInformation", "value": "Mi Informacion"},
                            {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                            {"id": "myConfiguration.qrgoogle", "value": "QR Google"}

                        ]
                    },
                    {
                        "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                            {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                                                    {"id": "betsReportSecond", "value": "Reporte de Apuestas"}

                                                ]
                    },
                    {
                        "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                            {"id": "flujoCaja", "value": "Flujo de Caja"},
                            {"id": "pagoPremio", "value": "Pago Premio"},
                            {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                            {"id": "recargarCredito", "value": "Recargar Credito"}
                        ]
                    }
                           ]'
                );

                break;

            case "CONCESIONARIO":

                $menu_string = json_decode(
                    '[
                    {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
    {
                        "id": "myconfiguration", "icon": "icon-cubes", "value": "Mi Configuracion", "data": [
                            {"id": "myConfiguration.myInformation", "value": "Mi Informacion"},
                            {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                            {"id": "myConfiguration.qrgoogle", "value": "QR Google"}

                        ]
                    },
                    {"id": "agentListManagement", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                    {"id": "addAgentListManagement", "icon": "icon-players", "show":"false", "value": "addAgentListManagement"},
                    {"id": "addBetShopManagement", "icon": "icon-players", "show":"false", "value": "addBetShopManagement"},
                    {
                        "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                            {"id": "depositReport", "value": "Reporte de depósitos"},
                            {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                            {"id": "betsReportSecond", "value": "Reporte de Apuestas"}
                        ]
                    },
                    {
                        "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                            {"id": "flujoCaja", "value": "Flujo de Caja"}
                                                ]
                    },
                    {
                        "id": "betShopManagement", "icon": "icon-shop", "value": "Gestión Punto de Venta", "data": [
                            {"id": "betShop", "value": "Punto de Venta"},
                            {"id": "cashiers", "value": "Cajeros"}
                        ]
                    },
                    {
                        "id": "agentSystem", "icon": "icon-user-secret", "value": "Agentes", "data": [
                            {"id": "agentList", "value": "Lista de Agentes"},
                            {"id": "agentsTree", "value": "Árbol de Agentes"},
                            {"id": "agentsInform", "value": "Informe de Agentes"},
                            {"id": "agentTransfers", "value": "Transferencias"}

                        ]
                    }
                             ]'
                );


                break;

        }

    */

    /* Se inicia una instancia de PerfilSubmenu y se obtienen variables de sesión. */
    $PerfilSubmenu = new PerfilSubmenu();

    $Perfil_id = $_SESSION["win_perfil2"];
    $Usuario_id = $_SESSION["usuario"];
    $MaxRows = "";
    $OrderedItem = "";

    /* inicializa variables si están vacías, asignando valores predeterminados. */
    $SkeepRows = "";

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Se establece un valor predeterminado para $MaxRows y se inicializan variables. */
    if ($MaxRows == "") {
        $MaxRows = 100000;
    }

    $mismenus = "0";

    $rules = [];


    /* Se agregan reglas para validar versiones y permisos de usuarios en un menú. */
    array_push($rules, array("field" => "menu.version", "data" => "3", "op" => "eq"));
    array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => "$Perfil_id", "op" => "eq"));


    if ($Perfil_id == "CUSTOM") {
        array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => "$Usuario_id", "op" => "eq"));
    } else {

        /* verifica permisos y agrega reglas según el perfil y mandante. */
        if ($_SESSION["win_perfil2"] != "SA") {
            if ($_SESSION["mandante"] == '6' && ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO")) {
                // array_push($rules, array("field" => "perfil_submenu.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));
                array_push($rules, array("field" => "perfil_submenu.mandante", "data" => '6', "op" => "eq"));
            } elseif ($_SESSION["mandante"] == '8' && ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO")) {
                // array_push($rules, array("field" => "perfil_submenu.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));
                array_push($rules, array("field" => "perfil_submenu.mandante", "data" => '6', "op" => "eq"));
            } elseif ($_SESSION["mandante"] == '8' && ($_SESSION["win_perfil2"] == "CONCESIONARIO")) {
                // array_push($rules, array("field" => "perfil_submenu.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));
                array_push($rules, array("field" => "perfil_submenu.mandante", "data" => '6', "op" => "eq"));
            } elseif ($_SESSION["mandante"] == '8' && ($_SESSION["win_perfil2"] == "CONCESIONARIO2")) {
                // array_push($rules, array("field" => "perfil_submenu.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));
                array_push($rules, array("field" => "perfil_submenu.mandante", "data" => '6', "op" => "eq"));
            } else {
                // array_push($rules, array("field" => "perfil_submenu.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));
                array_push($rules, array("field" => "perfil_submenu.mandante", "data" => '-1', "op" => "eq"));

            }

        }
    }


    /* Crea un filtro JSON y obtiene submenús personalizados de la base de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $menus = json_decode($menus);


    /* Inicializa arrays vacíos para almacenar menús, submenús y cadenas de menús. */
    $menus3 = [];
    $arrayf = [];
    $submenus = [];

    $menus_string = array();

    foreach ($menus->data as $key => $value) {


        /* Asignación de valores a un arreglo asociativo desde un objeto en PHP. */
        $m = [];
        $m["Id"] = $value->{"menu.menu_id"};
        $m["Name"] = $value->{"menu.descripcion"};

        $array = [];

        $array["Id"] = $value->{"submenu.submenu_id"};

        /* Asigna valores a un array basado en propiedades de un objeto. */
        $array["Name"] = $value->{"submenu.descripcion"};
        $array["Pagina"] = $value->{"submenu.pagina"};
        $array["IsGiven"] = true;
        $array["Action"] = "view";
        $array["add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
        $array["edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;

        /* Se verifica permisos de perfil y se construye una cadena de menús. */
        $array["delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;
        $array["Add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
        $array["Edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
        $array["Delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;

        $mismenus = $mismenus . "," . $array["Id"];


        /* verifica IDs y agrega elementos a arrays si se cumplen condiciones. */
        if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
            array_push($menus_string, $arrayf["Pagina"]);

            $arrayf["Permissions"] = $submenus;
            array_push($menus3, $arrayf);
            // $submenus = [];
        }

        /* Agrega datos de menú y submenú a los arreglos especificados en PHP. */
        array_push($menus_string, $array["Pagina"]);

        $arrayf["Id"] = $value->{"menu.menu_id"};
        $arrayf["Name"] = $value->{"menu.descripcion"};
        $arrayf["Pagina"] = $value->{"menu.pagina"};

        array_push($submenus, $array);
    }

    /* Agrega un elemento del array "Pagina" al final de "menus_string". */
    array_push($menus_string, $arrayf["Pagina"]);


    if ($Perfil_id != "CUSTOM") {


        /* Define un conjunto de reglas para validar campos específicos con condiciones. */
        $rules = [];

        array_push($rules, array("field" => "menu.version", "data" => "3", "op" => "eq"));
        array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => "$Perfil_id", "op" => "eq"));

        array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => "$Usuario_id", "op" => "eq"));


        /* crea un filtro JSON para obtener submenús personalizados desde la base de datos. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $menus = json_decode($menus);


        /* Se inicializan dos arreglos vacíos en PHP: $menus3 y $arrayf. */
        $menus3 = [];
        $arrayf = [];

        foreach ($menus->data as $key => $value) {


            /* Se asignan valores de menú y submenú a arrays asociativos en PHP. */
            $m = [];
            $m["Id"] = $value->{"menu.menu_id"};
            $m["Name"] = $value->{"menu.descripcion"};

            $array = [];

            $array["Id"] = $value->{"submenu.submenu_id"};

            /* Se crea un array con propiedades basadas en valores de un objeto. */
            $array["Name"] = $value->{"submenu.descripcion"};
            $array["Pagina"] = $value->{"submenu.pagina"};
            $array["IsGiven"] = true;
            $array["Action"] = "view";
            $array["add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
            $array["edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;

            /* Asigna permisos booleanos a un array según valores de un objeto. */
            $array["delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;
            $array["Add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
            $array["Edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
            $array["Delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;

            $mismenus = $mismenus . "," . $array["Id"];


            /* verifica condiciones y agrega elementos a dos arrays basados en ellas. */
            if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
                array_push($menus_string, $arrayf["Pagina"]);

                $arrayf["Permissions"] = $submenus;
                array_push($menus3, $arrayf);
                // $submenus = [];
            }

            /* Se añaden datos a arrays para menús y submenús en PHP. */
            array_push($menus_string, $array["Pagina"]);

            $arrayf["Id"] = $value->{"menu.menu_id"};
            $arrayf["Name"] = $value->{"menu.descripcion"};
            $arrayf["Pagina"] = $value->{"menu.pagina"};

            array_push($submenus, $array);
        }

        /* agrega elementos a arreglos y define reglas para validaciones. */
        array_push($menus_string, $arrayf["Pagina"]);


        $rules = [];

        array_push($rules, array("field" => "menu.version", "data" => "3", "op" => "eq"));

        /* Se preparan reglas de filtrado y se convierten a formato JSON. */
        array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => "CUSTOM", "op" => "eq"));

        array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => "$Usuario_id", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        /* obtiene y decodifica submenús de perfil en formato JSON. */
        $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $menus = json_decode($menus);

        $menus3 = [];
        $arrayf = [];

        foreach ($menus->data as $key => $value) {


            /* Se asignan valores de menú y submenú a arrays asociativos en PHP. */
            $m = [];
            $m["Id"] = $value->{"menu.menu_id"};
            $m["Name"] = $value->{"menu.descripcion"};

            $array = [];

            $array["Id"] = $value->{"submenu.submenu_id"};

            /* asigna valores a un array basado en propiedades de un objeto. */
            $array["Name"] = $value->{"submenu.descripcion"};
            $array["Pagina"] = $value->{"submenu.pagina"};
            $array["IsGiven"] = true;
            $array["Action"] = "view";
            $array["add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
            $array["edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;

            /* Asignación de permisos a un array basado en condiciones de un objeto. */
            $array["delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;
            $array["Add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
            $array["Edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
            $array["Delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;

            $mismenus = $mismenus . "," . $array["Id"];


            /* Agrega páginas y permisos a menús si las condiciones son satisfechas. */
            if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
                array_push($menus_string, $arrayf["Pagina"]);

                $arrayf["Permissions"] = $submenus;
                array_push($menus3, $arrayf);
                // $submenus = [];
            }

            /* Se agregan datos de menú y submenú a arrays en PHP. */
            array_push($menus_string, $array["Pagina"]);

            $arrayf["Id"] = $value->{"menu.menu_id"};
            $arrayf["Name"] = $value->{"menu.descripcion"};
            $arrayf["Pagina"] = $value->{"menu.pagina"};

            array_push($submenus, $array);
        }

        /* Agrega el valor de "Pagina" al final del arreglo $menus_string. */
        array_push($menus_string, $arrayf["Pagina"]);


    }


    /* Convierte un objeto JSON a un objeto PHP para facilitar su manipulación. */
    $submenus = json_decode(json_encode($submenus));


    foreach ($menu_string as $key => $item) {

        /* verifica si un elemento está en un array y lo elimina si no. */
        $continuar = true;

        if (!in_array($item->id, $menus_string)) {

            unset($menu_string[$key]);
            $continuar = false;

        } else {
            /* Filtra el array $submenus buscando coincidencias con el ID de $item. */

            $searchedValue = $item->id;
            $item2 = reset(array_filter(
                $submenus,
                function ($e) use (&$searchedValue) {
                    return $e->Pagina == $searchedValue;
                }
            ));


            /* asigna permisos a un objeto a partir de otro, sobrescribiéndolos. */
            $item->add = $item2->add;
            $item->edit = $item2->edit;
            $item->delete = $item2->delete;
            $item->add = true;
            $item->edit = true;
            $item->delete = true;

        }

        if ($continuar) {
            if (oldCount($item->data) > 0) {

                foreach ($item->data as $key2 => $datum) {

                    /* verifica y elimina elementos de un array basado en condiciones específicas. */
                    if (!in_array($datum->id, $menus_string)) {
                        unset($menu_string[$key]->data[$key2]);

                    } else {
                        $searchedValue = $datum->id;
                        $item3 = reset(array_filter(
                            $submenus,
                            function ($e) use (&$searchedValue) {
                                return $e->Pagina == $searchedValue;
                            }
                        ));


                        /* Asigna valores de permisos de agregar, editar y eliminar a un objeto. */
                        $datum->add = $item3->add;
                        $datum->edit = $item3->edit;
                        $datum->delete = $item3->delete;
                        $datum->add = true;
                        $datum->edit = true;
                        $datum->delete = true;

                    }


                }

            }
        }
    }


    /* Busca elementos en un array filtrado y los compara con un valor específico. */
    foreach ($submenus as $key => $item) {
        $continuar = true;

        $searchedValue = $item->Pagina;

        $item2 = reset(array_filter(
            $menu_string,
            function ($e) use (&$searchedValue) {
                return $e->Pagina == $searchedValue;
            }
        ));


        /* Crea un elemento de menú si $item2 es nulo o sin ID. */
        if ($item2 == null || $item2->id == null || $item2->id == "") {
            $itemD = array(
                "id" => $item->Pagina,
                "add" => true,
                "edit" => true,
                "delete" => true,
                "show" => "false"

            );
            array_push($menu_string, $itemD);

        }
    }


    /* Copia los elementos de `$menu_string` a `$menu_string2`, incluyendo datos adicionales. */
    $menu_string2 = array();
    foreach ($menu_string as $key => $item) {
        array_push($menu_string2, $item);

        if (oldCount($item->data) > 0) {
            $arr = $item->data;
            $menu_string2[oldCount($menu_string2) - 1]->data = array();

            foreach ($arr as $key2 => $datum) {
                array_push($menu_string2[oldCount($menu_string2) - 1]->data, $datum);
            }

        }
    }


    $menu_string3 = json_decode(
        '[
                {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
{
                    "id": "myconfiguration", "icon": "icon-tools", "value": "Mi Configuracion", "data": [
                        {"id": "myConfiguration.myInformation", "value": "Mi Informacion"},
                        {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                        {"id": "myConfiguration.qrgoogle", "value": "QR Google"}
                        
                    ]
                },
                
                {
                    "id": "productsFather", "icon": "icon-cubes", "value": "Products", "data": [
                        {"id": "partners", "value": "Partners"},
                        {"id": "providers", "value": "Providers"},
                        {"id": "products", "value": "Products"},
                        {"id": "partnersProviders", "value": "Partners Proveedores"},
                        {"id": "partnersTypeProduct", "value": "Partners Tipo Producto"},
                        {"id": "partnersProducts", "value": "Partners Products"},
                        {"id": "partnersProductsCountry", "value": "Partners Products Country"},
                        {"id": "categories", "value": "Categories"},
                        {"id": "categoriesProducts", "value": "CategoriesProducts"}
                        
                    ]
                },
                {
    "id": "informationFather",
    "icon": "icon-pie-chart",
    "value": "Informes",
    "data": [
        {
            "id": "informationCasino",
            "value": "Casino",
            "add": true,
            "edit": true,
            "delete": true
        },
        {
            "id": "informationLiveCasino",
            "value": "Casino en vivo",
            "add": true,
            "edit": true,
            "delete": true
        },
        {
            "id": "informationVirtual",
            "value": "Virtuales",
            "add": true,
            "edit": true,
            "delete": true
        },
        {
            "id": "informationHipica",
            "value": "Hípica",
            "add": true,
            "edit": true,
            "delete": true
        }
    ],
    "add": true,
    "edit": true,
    "delete": true
},
                {"id": "adminUserManagement", "icon": "icon-players", "show":"false", "value": "adminUserManagement"},
                {"id": "aggregatorList", "icon": "icon-players", "show":"false", "value": "Jugadores Partner"},
                {"id": "customers", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "agentListManagement", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "addAgentListManagement", "icon": "icon-players", "show":"false", "value": "addAgentListManagement"},
                {"id": "addBetShopManagement", "icon": "icon-players", "show":"false", "value": "addBetShopManagement"},
                {"id": "addAdminUserManagement", "icon": "icon-players", "show":"false", "value": "addAdminUserManagement"},
                {"id": "leagues.addLeagueManagement", "icon": "icon-players", "show":"false", "value": "Añadir ligas"},
                {"id": "machine.addMachineManagement", "icon": "icon-players", "show":"false", "value": "Añadir Maquina"},
                {"id": "machine.machineManagement", "icon": "icon-players", "show":"false", "value": "Detalles maquina "},
                {"id": "settings", "icon": "icon-players", "show":"false", "value": "settings"},
                {"id": "withdrawalRequestsApprove", "icon": "icon-players", "show":"false", "value": "withdrawalRequestsApprove"},

                {"id": "playersList", "icon": "icon-players", "value": "Jugadores"},
                 {
                    "id": "partner", "icon": "icon-partner", "value": "Partner", "data": [
                        {"id": "partner.PartnerSettings", "value": "PartnerSettings"}
                                            ]
                },
                                {"id": "leagues.leaguesManagement", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {
                        "id": "leagues", "icon": "icon-pie-chart", "value": "Ligas", "data": [
                            {"id": "leagues.leaguesList", "value": "Lista de Ligas"}
                                                ]
                    },
                {
                    "id": "requests", "icon": "icon-pie-chart", "value": "Request", "data": [
                        {"id": "requests.registrationRequests", "value": "registrationRequests"}
                                            ]
                },
                {
                    "id": "machine", "icon": "icon-pie-chart", "value": "Maquinas", "data": [
                        {"id": "machine.machineRegister", "value": "Lista"},
                        {"id": "machine.information", "value": "Registrar"},
                        {"id": "machine.pagoPremioMaquina", "value": "Pago Premio"},
                        {"id": "machine.pagoNotaCobro", "value": "Pago Premio"},
                                                {"id": "machine.managePointsGraphics", "value": "Maquinas Grafica"}

                                            ]
                },
                {
                    "id": "messages", "icon": "icon-pie-chart", "value": "Mensajes", "data": [
                        {"id": "messages.messageList", "value": "Lista"}
                                            ]
                },
                {
                    "id": "tools", "icon": "icon-pie-chart", "value": "Herramientas", "data": [
                        {"id": "tools.translationManager", "value": "Traducción"},
                        {"id": "tools.uploadImage", "value": "Subir imagen"}
                                            ]
                },
                
                
                {
                    "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                        {"id": "depositReport", "value": "Reporte de depósitos"},
                        {"id": "autoexclusionUsers", "value": "Autoexclusiones de Usuario"},
                        {"id": "casinoGamesReport", "value": "Reporte de casino "},
                        {"id": "casinoGamesReport2", "value": "2.Reporte de casino "},
                        {"id": "bonusReport", "value": "Reporte de bonos"},
                        {"id": "playersReport", "value": "Reporte de Jugadores"},
                        {"id": "historicalCashFlow", "value": "Flujo de Caja Histórico"},
                        {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                        {"id": "summaryCashFlow2", "value": "2.Flujo de Caja Resumido"},
                        {"id": "informeGerencial", "value": "Informe Gerencial"},
                        {"id": "informeGerencial2", "value": "2.Informe Gerencial"},
                        {"id": "betsReport", "value": "Reporte de Apuestas"},
                        {"id": "transactionReport", "value": "Transacciones Apuestas"},
                        {"id": "usuarioOnlineResumido", "value": "Usuario online Resumido"},
                         {"id": "transactionReport", "value": "Reporte de transacciones de apuestas"},
                        {"id": "promotionalCodes", "value": "Codigos Promocionales"},
                        {"id": "relationUserAggregator", "value": "Usuario - Agregator"},
                        {"id": "paidPendingAwards", "value": "premiosPendientesPagar"},
                        {"id": "sessionsReport2", "value": "Reporte de Sesiones"}
                    ]
                },
                
                {
                    "id": "accounting", "icon": "icon-security", "value": "Contabilidad", "data": [
                        {"id": "accounting.costCenter", "value": "Centros de costo", "add": true},
                        {"id": "accounting.area", "value": "Area", "add": true},
                        {"id": "accounting.position", "value": "Cargo", "add": true},
                        {"id": "accounting.typeCenterPosition", "value": "Tipos", "add": true},
                        {"id": "accounting.employees", "value": "Empleados", "add": true},
                        {"id": "accounting.expenses", "value": "Egresos", "add": true},
                        {"id": "accounting.incomes", "value": "Ingresos", "add": true},
                        {"id": "accounting.providers", "value": "Proveedores terceros", "add": true},
                        {"id": "accounting.concepts", "value": "Conceptos", "add": true},
                        {"id": "accounting.accounts", "value": "Cuentas", "add": true},
                        {"id": "accounting.productsThirdBetShop", "value": "Productos terceros", "add": true},
                        {"id": "accounting.productsThirdByBetShop", "value": "Productos terceros Punto de venta", "add": true},
                        {"id": "accounting.closingDayReport", "value": "Reporte cierre de dia"},
                        {"id": "accounting.squareDayReport", "value": "Reporte de cuadre de dia", "add": true},
                        {"id": "accounting.squareDayReport2", "value": "Reporte de cuadre de dia 2", "add": true}

                    ]
                },
                {
                    "id": "security", "icon": "icon-security", "value": "Security", "data": [
                        {"id": "approvalLogs", "value": "Aporbar Logs", "add": true},
                        {"id": "adminUser", "value": "Admin User", "add": true},
                        {"id": "usuariosbloqueados", "value": "Usuarios Bloqueados"},
                        {"id": "contingency", "value": "Contingency"},
                        {"id": "menus", "value": "Menus"},
                        {"id": "profile", "value": "Profile"},
                        {"id": "profileOptions", "value": "Profile - Options"},
                        {"id": "subMenu", "value": "Sub Menu"},
                        {"id": "userProfile", "value": "User Profile"},
                        {"id": "competitors.competitors", "value": "Competidores"}
                    ]
                },
                {
                    "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                        {"id": "flujoCaja", "value": "Flujo de Caja"},
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"},
                        {"id": "recargarCreditoAgente", "value": "Recargar Agentes"}
                    ]
                },
                {
                    "id": "betShopManagement", "icon": "icon-shop", "value": "Gestión Punto de Venta", "data": [
                        {"id": "betShop", "value": "Punto de Venta", "add": true},
                        {"id": "cashiers", "value": "Cajeros"},
                        {"id": "managePointsGraphics", "value": "Gestión Puntos Gráfico"},
                        {"id": "betShopCompetence", "value": "Puntos de venta Competencia"}
                    ]
                },
                {
                    "id": "agentSystem", "icon": "icon-user-secret", "value": "Agentes", "data": [
                        {"id": "agentList", "value": "Lista de Agentes"},
                        {"id": "agentsTree", "value": "Árbol de Agentes"},
                        {"id": "agentsInform", "value": "Informe de Agentes"},
                        {"id": "agentTransfers", "value": "Transferencias de saldo"},
                        {"id": "agentRetraction", "value": "Retracción de saldo"},
                        {"id": "agent.requestsAgent", "value": "Solicitudes"},
                        {"id": "agent.requirementsAgent", "value": "Requisitos"}

                    ]
                },
                {
                    "id": "financial", "icon": "icon-financial", "value": "Financiero", "data": [
                        {"id": "depositRequests", "value": "Solicitudes de Deposito"},
                        {"id": "withdrawalRequests", "value": "Solicitudes de Retiro","Edit":"true"}
                    ]
                }            ]'
    );
    /*{
                        "id": "leagues", "icon": "icon-pie-chart", "value": "Ligas", "data": [
                            {"id": "leagues.leaguesList", "value": "Lista de Ligas"}
                                                ]
                    },*/

    return ($menu_string2);

}


/**
 * Establece una cookie con la opción SameSite, compatible con versiones de PHP anteriores y posteriores a la 7.3.
 *
 * Esta función permite establecer una cookie con la directiva `SameSite` incluida, mejorando la seguridad de la cookie en aplicaciones web.
 * Se asegura de que la cookie esté configurada de manera compatible tanto con versiones anteriores a PHP 7.3 como con versiones más recientes.
 *
 * @param string $name El nombre de la cookie.
 * @param string $value El valor de la cookie.
 * @param int $expire La fecha de expiración de la cookie, como un timestamp UNIX.
 * @param string $path La ruta en la que la cookie será accesible.
 * @param string $domain El dominio en el que la cookie será válida.
 * @param bool $secure Indica si la cookie solo se debe enviar a través de conexiones seguras (HTTPS).
 * @param bool $httponly Indica si la cookie solo debe ser accesible a través de HTTP, no mediante JavaScript.
 * @param string $samesite El valor para la directiva `SameSite` (None, Lax, Strict). El valor predeterminado es "None".
 * @return void No devuelve ningún valor.
 * @throws no No contiene manejo de excepciones.
 */
function setcookieSameSite($name, $value, $expire, $path, $domain, $secure, $httponly, $samesite = "None")
{
    if (PHP_VERSION_ID < 70300) {
        setcookie($name, $value, $expire, "$path; samesite=$samesite", $domain, $secure, $httponly);
    } else {
        setcookie($name, $value, [
            'expires' => $expire,
            'path' => $path,
            'domain' => $domain,
            'samesite' => $samesite,
            'secure' => $secure,
            'httponly' => $httponly,
        ]);
    }
}