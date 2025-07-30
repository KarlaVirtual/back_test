<?php


use Backend\dto\ConfigurationEnvironment;

/**
 * Operator API Index
 *
 * Este script maneja solicitudes entrantes, verifica autenticación, incluye archivos de casos
 * según la URI y gestiona errores de manera centralizada.
 *
 * @param string $json JSON recibido desde la entrada que contiene los parámetros de la solicitud.
 * @param int $debugFixed Activa o desactiva el modo debug (1 para activar).
 * 
 * 
 * @return array $response Respuesta con el estado de la operación.
 *                         - error: bool Indica si ocurrió un error.
 *                         - code: int Código de error.
 *                         - message: string Mensaje de error.
 * @throws Exception Si ocurre un error al procesar la solicitud o incluir un archivo de caso.
 */


/* habilita la visualización de errores si se activó el modo debug. */
if ($_REQUEST["debugFixed"] == '1') {
    ini_set("display_errors", "ON");
}
// error_reporting(0);
// ini_set("display_errors", "OFF");
//print_r(time());

header('Access-Control-Allow-Credentials: true');

/* configura las cabeceras CORS y JSON para una respuesta HTTP. */
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');
ini_set('memory_limit', '-1');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);


/* ajusta la zona horaria y obtiene la URI de la solicitud actual. */
$timezone = $_SESSION["timezone"];
$timezone = -5 - ($timezone);
$timezone = 0;


$URI = $_SERVER["REQUEST_URI"];

/* almacena un registro con datos de entrada y marca de tiempo. */
$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . date('Y-m-d H:i:s');
$log = $log . $URI;
$log = $log . file_get_contents('php://input');

/* Guarda un log diario y recibe datos de entrada en formato JSON. */
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$currencies_valor = array();


$params = file_get_contents('php://input');


/* decodifica parámetros JSON y establece una respuesta inicial sin errores. */
$params = json_decode($params);
$response = array();
$response["error"] = false;
$response["code"] = 0;

$ENCRYPTION_KEY = "D!@#$%^&*";


/* verifica si la solicitud es de tipo OPTIONS y finaliza su ejecución. */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

require "includes.php";

if (!function_exists('getallheaders')) {
    /**
     * Obtiene todas las cabeceras HTTP de la solicitud actual.
     *
     * @return array Un arreglo asociativo de las cabeceras HTTP.
     */
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

/* obtiene la URI, lee datos JSON y recopila los encabezados HTTP. */
$URI = $_SERVER["REQUEST_URI"];
$params = file_get_contents('php://input');
$params = json_decode($params);
$response = array();

$headers = getallheaders();


/* define URLs y maneja solicitudes HTTP OPTIONS. */
$URL_ITAINMENT2 = 'https://dataexport-altenar.biahosted.com';
//$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';
$URL_ITAINMENT = 'https://dataexport-uof-altenar.biahosted.com';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}


/* Separa una cadena en un array usando "/" y obtiene la primera parte antes del "?". */
$arraySuper = explode("/", current(explode("?", $URI)));


try {

    /* Modifica un array y asigna un nombre de archivo basado en sus valores. */
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $arraySuper[count($arraySuper) - 2] = ucfirst($arraySuper[count($arraySuper) - 2]);
    if ($arraySuper[count($arraySuper) - 2] == "" || $arraySuper[count($arraySuper) - 2] == "OperatorApi") {
        $filename = 'cases/' . $arraySuper[count($arraySuper) - 1] . ".php";

    } else {
        /* construye una ruta de archivo utilizando elementos de un arreglo. */

        $filename = 'cases/' . $arraySuper[count($arraySuper) - 2] . "/" . $arraySuper[count($arraySuper) - 1] . ".php";

    }


    /* Genera un nombre de archivo en entorno de desarrollo y lo limpia de espacios. */
    if ($ConfigurationEnvironment->isDevelopment()) {
        $filename = 'cases/' . strtolower($arraySuper[count($arraySuper) - 2]) . "/" . strtolower($arraySuper[count($arraySuper) - 1]) . ".php";

    }
    $filename = trim($filename);
    $filename = str_replace('%20', '', $filename);

    /* Se valida si el usuario está logueado o realiza ciertas acciones. */
    $validacionLogueado = true;


    if ($_SESSION["logueado"] || $arraySuper[count($arraySuper) - 1] == "CheckAuthentication" || $arraySuper[count($arraySuper) - 1] == "uploadImage" || $arraySuper[count($arraySuper) - 1] == "Login" || $arraySuper[count($arraySuper) - 1] == "ImportBulkUsers" || $arraySuper[count($arraySuper) - 1] == "GenerateHashFiles" || $arraySuper[count($arraySuper) - 1] == "GeneraHashFilesBackend" || $arraySuper[count($arraySuper) - 1] == "CloseDaySpecific" || $arraySuper[count($arraySuper) - 1] == "LoginGoogle") {
        $validacionLogueado = true;
    }

    /* verifica si un archivo existe y el usuario está logueado antes de incluirlo. */
    if (file_exists(__DIR__ . "/" . $filename) && $validacionLogueado) {
        require $filename;
    } else {
        $response["Error"] = false;
        $response["Code"] = "0";


    }

} catch (Exception $e) {

    /* verifica un parámetro y muestra un objeto si coincide con un valor específico. */
    if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
        print_r($e);
    }

    $code = $e->getCode();
//$message = $e->getMessage();
    $message = '';

    /* inicializa variables para un proveedor y un arreglo para respuestas. */
    $codeProveedor = "";
    $messageProveedor = "";

    $response = array();

    switch ($code) {

        case 50003:

            /* asigna valores a variables según condiciones específicas de error. */
            $codeProveedor = "102";  //credenciales incorrectas
            $messageProveedor = $message;
            break;

        case 50001:
            $codeProveedor = "100";  //campos vacios

            /* Asigna un mensaje y código específico para un error de usuario en el país. */
            $messageProveedor = $message;
            break;

        case 50005:
            $codeProveedor = "101"; //Usuario no pertence al pais
            $messageProveedor = $message;
            break;
        case 50006:

            /* asigna un identificador a un proveedor en función de ciertos casos. */
            $codeProveedor = "101";  //usuario no pertenece al partner
            $messageProveedor = $message;
            break;

        case 50007:
            $codeProveedor = "106";     //nota de retirno no esta activa

            /* maneja un mensaje y un código de error para eliminación de notas de retiro. */
            $messageProveedor = $message;
            break;

        case 50008:
            $codeProveedor = "9"; //nota de retiro no puede ser eliminada
            $messageProveedor = $message;
            break;

        case 50009:

            /* maneja diferentes estados de notas de retiro mediante códigos específicos. */
            $codeProveedor = "9"; //nota de retiro ya eliminada
            $messageProveedor = $message;
            break;

        case 10018:
            $codeProveedor = "100"; //Codigo de pais incorrecto

            /* Asignación de mensajes y códigos de error en transacciones procesadas. */
            $messageProveedor = $message;
            break;

        case 10001:
            $codeProveedor = "6"; //Transacción ya procesada
            $messageProveedor = $message;
            break;

        case 100000:

            /* Código que asigna errores en un sistema de pago de notas de retiro. */
            $codeProveedor = "9"; //error general
            $messageProveedor = $message;
            break;
        case 100031:
            $codeProveedor = "9"; //No se puede pagar nota de retiro
            $messageProveedor = $message;
            break;
        case 12:

            /* gestiona diferentes códigos de proveedor según ciertas condiciones. */
            $codeProveedor = "12";  //no existe la nota de retiro
            $messageProveedor = $message;

            break;
        case 24:
            $codeProveedor = "101"; //no existe el usuario

            /* asigna un mensaje y un código basado en una condición específica. */
            $messageProveedor = $message;

            break;

        case 10:
            $codeProveedor = "100"; // key incorrecta

            /* La línea asigna el contenido de la variable $message a $messageProveedor. */
            $messageProveedor = $message;

            break;


        /*
        case 50001:
        $codeProveedor = "2";
        $messageProveedor = "Data Incorrect. (" . $e->getMessage() . ")";

        break;
        case 61:
        $codeProveedor = "3";
        $messageProveedor = "Incorrect login details.";

        break;

        case 86:
        $codeProveedor = "3";
        $messageProveedor = "Incorrect login details.";

        break;


        case 12:
        $codeProveedor = "20";
        $messageProveedor = "No Existe la nota de retiro.";

        break;*/


        default:

            /* extrae el código y mensaje de una excepción capturada en PHP. */
            $codeProveedor = $e->getCode();
            $messageProveedor = $e->getMessage();

            break;
    }


    /* asigna valores de error, código y mensaje a la respuesta. */
    $response["error"] = 1;
    $response["code"] = $codeProveedor;
    $response["message"] = $messageProveedor;

}

/* verifica si la respuesta no está vacía y la imprime en formato JSON. */
if (json_encode($response) != "[]") {
    print_r(json_encode($response));

}

/**
 * Genera una clave de ticket aleatoria.
 *
 * @param int $length La longitud de la clave a generar.
 * @return string La clave de ticket generada.
 */
function GenerarClaveTicket($length)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * Genera una clave de ticket aleatoria compuesta solo por dígitos.
 *
 * @param int $length La longitud de la clave a generar.
 * @return string La clave de ticket generada.
 */
function GenerarClaveTicket2($length)
{
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * Encripta o desencripta una cadena usando el método AES-256-CBC.
 *
 * @param string $action La acción a realizar: 'encrypt' para encriptar, 'decrypt' para desencriptar.
 * @param string $string La cadena a encriptar o desencriptar.
 * @return string|false La cadena encriptada/desencriptada o false en caso de error.
 */
function encrypt_decrypt($action, $string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'D0RAD0';
    $secret_iv = 'D0RAD0';
    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

/**
 * Obtiene la dirección IP del cliente.
 *
 * @return string La dirección IP del cliente.
 */
function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/**
 * Elimina duplicados de un array multidimensional basado en una clave específica.
 *
 * @param array $array El array multidimensional a procesar.
 * @param string $key La clave para verificar duplicados.
 * @return array El array sin duplicados.
 */
function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

/**
 * Quita tildes de una cadena de texto.
 *
 * @param string $cadena La cadena de texto a procesar.
 * @return string La cadena de texto sin tildes.
 */
function quitar_tildes($cadena)
{
    $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
    $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
    $texto = str_replace($no_permitidas, $permitidas, $cadena);
    return $texto;
}

/**
 * Encripta una cadena de texto.
 *
 * @param string $data La cadena de texto a encriptar.
 * @param string $encryption_key La clave de encriptación (opcional).
 * @return string La cadena de texto encriptada.
 */
function encrypt($data, $encryption_key = "")
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']), 0, $iv);
    return $encrypted_string;
}

/**
 * Desencripta una cadena de texto.
 *
 * @param string $data La cadena de texto a desencriptar.
 * @param string $encryption_key La clave de encriptación (opcional).
 * @return string|false La cadena de texto desencriptada o false en caso de error.
 */
function decrypt($data, $encryption_key = "")
{
    $iv_strlen = 2 * openssl_cipher_iv_length('AES-128-CTR');
    if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
        list(, $iv, $crypted_string) = $regs;
        $decrypted_string = openssl_decrypt($crypted_string, 'AES-128-CTR', gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']), 0, hex2bin($iv));
        return $decrypted_string;
    } else {
        return FALSE;
    }
}


/**
 * DepurarCaracteres
 *
 * Esta función elimina una serie de caracteres especiales de una cadena de texto
 * para depurarla y evitar posibles problemas de seguridad o formato.
 *
 * @param string $texto_depurar La cadena de texto que se desea depurar.
 * @return string La cadena de texto depurada, sin los caracteres especiales especificados.
 *
 * Caracteres eliminados:
 * - Comillas simples (')
 * - Comillas dobles (")
 * - Mayor que (>)
 * - Menor que (<)
 * - Corchetes ([, ])
 * - Llaves ({, })
 * - Caracteres especiales como: �, `, |, %, &, ~, +, ^, /
 */
function DepurarCaracteres($texto_depurar)
{

    $texto_depurar = str_replace("'", "", $texto_depurar);
    $texto_depurar = str_replace('"', "", $texto_depurar);
    $texto_depurar = str_replace(">", "", $texto_depurar);
    $texto_depurar = str_replace("<", "", $texto_depurar);
    $texto_depurar = str_replace("[", "", $texto_depurar);
    $texto_depurar = str_replace("]", "", $texto_depurar);
    $texto_depurar = str_replace("{", "", $texto_depurar);
    $texto_depurar = str_replace("}", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("`", "", $texto_depurar);
    $texto_depurar = str_replace("|", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("%", "", $texto_depurar);
    $texto_depurar = str_replace("&", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("~", "", $texto_depurar);
    $texto_depurar = str_replace("+", "", $texto_depurar);
    $texto_depurar = str_replace("^", "", $texto_depurar);
    $texto_depurar = str_replace("/", "", $texto_depurar);
    //$texto_retornar = addslashes($texto_depurar);

    $c = null;
    return $texto_depurar;
}

// Function to get the client ip address
/**
 * Obtiene la dirección IP del cliente desde las variables de entorno.
 *
 * Este método verifica varias variables de entorno para determinar la dirección IP
 * del cliente. Las variables se verifican en el siguiente orden:
 * - HTTP_CLIENT_IP
 * - HTTP_X_FORWARDED_FOR
 * - HTTP_X_FORWARDED
 * - HTTP_FORWARDED_FOR
 * - HTTP_FORWARDED
 * - REMOTE_ADDR
 *
 * Si no se encuentra ninguna dirección IP válida, se devuelve 'UNKNOWN'.
 *
 * @return string La dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
 */
function get_client_ip_env()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}


/**
 * Convierte una dirección IPv6 a una dirección IPv4.
 *
 * Esta función toma una dirección IPv6 como entrada y realiza una conversión
 * a una dirección IPv4. Si la dirección IPv6 es una dirección 6to4 que comienza
 * con "2002:", se extraen los bytes correspondientes a la dirección IPv4.
 * En otros casos, se realiza una operación XOR en los primeros 8 bytes de la
 * dirección IPv6 para generar una dirección IPv4 ficticia en el espacio de Clase E.
 *
 * @param string $ipv6 La dirección IPv6 que se desea convertir.
 * @return string|null La dirección IPv4 convertida, o null si la conversión falla.
 */
function convertIP6($ipv6)
{
    $ipv6Addr = @inet_pton($ipv6);
    if ($ipv6Addr === false || strlen($ipv6Addr) !== 16) {
    }
    if (strpos($ipv6Addr, chr(0x20) . chr(0x02)) === 0) { // 6to4 addresses starting with 2002:
        $ipv4Addr = substr($ipv6Addr, 2, 4);
    } else {
        $ipv4Addr = '';
        for ($i = 0; $i < 8; $i += 2) { // Get first 8 bytes because the most of ISP provide addresses with mask /64
            $ipv4Addr .= chr(ord($ipv6Addr[$i]) ^ ord($ipv6Addr[$i + 1]));
        }
        $ipv4Addr[0] = chr(ord($ipv4Addr[0]) | 240); // Class E space
    }
    $ipv4 = inet_ntop($ipv4Addr);
    return $ipv4;
}
?>