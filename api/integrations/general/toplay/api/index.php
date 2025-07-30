<?php

/**
 * Este archivo contiene la implementación de un API para manejar diversas operaciones relacionadas con juegos y apuestas.
 * Proporciona endpoints para autenticación, vinculación de cuentas, verificación de cuentas, depósitos, retiros,
 * historial de apuestas, lanzamiento de juegos y más.
 *
 * @category Red
 * @package  API
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $URI               Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER           Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body              Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data              Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $htext             Variable que almacena un texto con formato especial.
 * @var mixed $headers           Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $key               Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $value             Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $header            Variable que almacena un encabezado HTTP individual.
 * @var mixed $hash_value        Variable que almacena un valor hash generado a partir de un dato.
 * @var mixed $hash_value_propio Variable que almacena un valor hash personalizado.
 * @var mixed $log               Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $Username          Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $_REQUEST          Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $DocumentType      Variable que almacena el tipo de documento de un usuario.
 * @var mixed $sign              Variable que almacena una firma digital o de seguridad.
 * @var mixed $Toplay            Variable que almacena información sobre el juego o apuesta en curso.
 * @var mixed $token             Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $response          Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $EstablishmentId   Variable que almacena el identificador único de un establecimiento.
 * @var mixed $UsuarioId         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $establishmentId   Variable que almacena el identificador único de un establecimiento (forma alternativa).
 * @var mixed $VerificationCode  Variable que almacena un código de verificación utilizado en autenticación o procesos de seguridad.
 * @var mixed $DeviceId          Variable que almacena el identificador único de un dispositivo.
 * @var mixed $CellPhone         Variable que almacena el número de teléfono celular del usuario.
 * @var mixed $userName          Variable que almacena el nombre de usuario de la cuenta.
 * @var mixed $transactionId     Variable que almacena el identificador único de una transacción.
 * @var mixed $confirmationCode  Variable que almacena el código de confirmación utilizado en validaciones.
 * @var mixed $CategoryId        Variable que almacena el identificador de una categoría.
 * @var mixed $Keyword           Variable que almacena una palabra clave utilizada en búsquedas.
 * @var mixed $GameID            Variable que almacena el identificador único de un juego.
 * @var mixed $Demo              Variable que indica si una funcionalidad está en modo demostración.
 * @var mixed $Ip                Variable que almacena la dirección IP de un usuario o dispositivo.
 * @var mixed $startDate         Esta variable define el índice inicial o punto de partida para un proceso o iteración.
 * @var mixed $endDate           Variable que almacena la fecha de finalización de un proceso.
 * @var mixed $state             Variable que almacena el estado actual de un elemento o proceso.
 * @var mixed $gameType          Variable que almacena el tipo de juego en ejecución.
 * @var mixed $return            Variable que se usa para almacenar un valor de retorno en funciones.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\general\Toplay;


$URI = $_SERVER['REQUEST_URI'];

$body = file_get_contents('php://input');
$data = json_decode($body);


header('Content-Type: application/json');

$htext = 'HT';

/**
 * Obtiene los encabezados de la solicitud HTTP actual.
 *
 * Este método recorre la variable superglobal `$_SERVER` para identificar
 * y extraer los encabezados HTTP enviados en la solicitud. Los encabezados
 * se devuelven en un array asociativo donde las claves son los nombres de
 * los encabezados y los valores son sus respectivos contenidos.
 *
 * @return array Un array asociativo con los encabezados HTTP de la solicitud.
 */
function getRequestHeaders()
{
    $headers = array();
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}

$headers = getRequestHeaders();
$hash_value = "";
foreach ($headers as $header => $value) {
    if ($header == "Hash") {
        $hash_value = $value;
    }
    $htext = $htext . "$header: $value <br />\n";
}

$hash_value_propio = base64_encode(hash_hmac('sha256', $body, "8856b34f-65bf-4158-87dd-420411d829dc", true));

$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . "\r\n" . $URI . "\r\n";
$log = $log . $body;

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

if (true) {
    if (strpos($URI, "GETUSERINFORMATION") !== false) {
        $Username = $_REQUEST["username"];
        $DocumentType = $_REQUEST["documentType"];
        $sign = $_REQUEST["sign"];

        /* Procesamos */
        $Toplay = new Toplay($token, $sign);
        $response = ($Toplay->AuthByDocument($Username, $DocumentType));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        //Save string to log, use FILE_APPEND to append.

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }


    if (strpos($URI, "LINKACCOUNT") !== false) {
        $EstablishmentId = $_REQUEST["establishmentId"];
        $UsuarioId = $_REQUEST["userName"];

        $sign = $_REQUEST["sign"];

        /* Procesamos */
        $Toplay = new Toplay($token, $sign, $UsuarioId);
        $response = ($Toplay->LINKACCOUNT());

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }

    if (strpos($URI, "VERIFYACCOUNT") !== false) {
        $establishmentId = $_REQUEST["establishmentId"];
        $UsuarioId = $_REQUEST["userName"];
        $VerificationCode = $_REQUEST["verificationCode"];
        $DeviceId = $_REQUEST["deviceId"];
        $CellPhone = $_REQUEST["cellPhone"];

        $sign = $_REQUEST["sign"];

        /* Procesamos */
        $Toplay = new Toplay($token, $sign, $UsuarioId, $establishmentId);
        $response = ($Toplay->VERIFYACCOUNT($VerificationCode, $DeviceId, $CellPhone));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
    if (strpos($URI, "PERFORMAFFILIATION") !== false) {
        $sign = $_REQUEST["sign"];

        /* Procesamos */
        $Toplay = new Toplay('', '');
        $response = ($Toplay->PERFORMAFFILIATION($data));

        $log = "";
        $log = $log . "/" . time();
        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
    if (strpos($URI, "DEPOSIT") !== false) {
        $sign = $_REQUEST["sign"];

        $establishmentId = $data->establishmentId;
        $userName = $data->userName;
        $establishmentId = $data->establishmentId;
        $transactionId = $data->transactionId;
        if ($transactionId == "") {
            $transactionId = $data->transactionCode;
        }

        $value = $data->value;

        /* Procesamos */
        $Toplay = new Toplay('', '', $userName, $establishmentId);
        $response = ($Toplay->DEPOSIT($transactionId, $value, $data));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
    if (strpos($URI, "WIDTHDRAWALCONFIRMATION") !== false) {
        $sign = $_REQUEST["sign"];

        $userName = $data->userName;
        $establishmentId = $data->establishmentId;
        $confirmationCode = $data->confirmationCode;

        /* Procesamos */
        $Toplay = new Toplay('', '', $userName, $establishmentId);


        $response = ($Toplay->WIDTHDRAWALCONFIRMATION($confirmationCode));


        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    } elseif (strpos($URI, "WIDTHDRAWAL") !== false) {
        $sign = $_REQUEST["sign"];

        $establishmentId = $data->establishmentId;
        $userName = $data->userName;
        $establishmentId = $data->establishmentId;
        $transactionId = $data->transactionId;
        if ($transactionId == "") {
            $transactionId = $data->transactionCode;
        }

        $value = $data->value;

        /* Procesamos */
        $Toplay = new Toplay('', '', $userName, $establishmentId);
        $response = ($Toplay->WIDTHDRAWAL($transactionId, $value, $data));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }

    if (strpos($URI, "SLOTSGETGAMECATEGORIES") !== false) {
        $sign = $_REQUEST["sign"];

        $userName = $data->userName;
        $establishmentId = $data->establishmentId;
        $confirmationCode = $data->confirmationCode;

        /* Procesamos */
        $Toplay = new Toplay('', '');
        $response = ($Toplay->getCategories());

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
    if (strpos($URI, "SLOTSGETGAMES") !== false) {
        $sign = $_REQUEST["sign"];

        $CategoryId = $_REQUEST["categoryId"];
        $Keyword = $_REQUEST["keyword"];

        /* Procesamos */
        $Toplay = new Toplay('', '');
        $response = ($Toplay->getGames($Keyword, 'true', "", "", "", $CategoryId));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
    if (strpos($URI, "SLOTSGAMELAUNCH") !== false) {
        $sign = $_REQUEST["sign"];

        $userName = $data->userName;
        $GameID = $data->gameID;
        $Demo = $data->demo;
        $Ip = $data->ip;

        /* Procesamos */
        $Toplay = new Toplay('', '', $userName);
        $response = ($Toplay->launchGame($GameID, $Demo, $Ip));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
    if (strpos($URI, "SLOTSBETTINGHISTORY") !== false) {
        $sign = $_REQUEST["sign"];

        $startDate = $_REQUEST["startDate"];
        $endDate = $_REQUEST["endDate"];
        $userName = $_REQUEST["userName"];
        $state = $_REQUEST["state"];
        $gameType = $_REQUEST["gameType"];

        /* Procesamos */
        $Toplay = new Toplay('', '', $userName);
        $response = ($Toplay->getBettingHistory($startDate, $endDate, $state, $gameType));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }


    if (strpos($URI, "VIRTUALSGETGAMECATEGORIES") !== false) {
        $sign = $_REQUEST["sign"];

        $userName = $data->userName;
        $establishmentId = $data->establishmentId;
        $confirmationCode = $data->confirmationCode;

        /* Procesamos */
        $Toplay = new Toplay('', '');
        $response = ($Toplay->getCategories('VIRTUAL'));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
    if (strpos($URI, "VIRTUALSGETGAMES") !== false) {
        $sign = $_REQUEST["sign"];

        $CategoryId = $_REQUEST["categoryId"];
        $Keyword = $_REQUEST["keyword"];

        /* Procesamos */
        $Toplay = new Toplay('', '');
        $response = ($Toplay->getGames($Keyword, 'true', "", "", "", $CategoryId));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
    if (strpos($URI, "VIRTUALSGAMELAUNCH") !== false) {
        $sign = $_REQUEST["sign"];

        $userName = $data->userName;
        $GameID = $data->gameID;
        $Demo = $data->demo;
        $Ip = $data->ip;

        /* Procesamos */
        $Toplay = new Toplay('', '', $userName);
        $response = ($Toplay->launchGame($GameID, $Demo, $Ip));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
    if (strpos($URI, "VIRTUALSBETTINGHISTORY") !== false) {
        $sign = $_REQUEST["sign"];

        $startDate = $_REQUEST["startDate"];
        $endDate = $_REQUEST["endDate"];
        $userName = $_REQUEST["userName"];
        $state = $_REQUEST["state"];
        $gameType = $_REQUEST["gameType"];

        /* Procesamos */
        $Toplay = new Toplay('', '', $userName);
        $response = ($Toplay->getBettingHistory($startDate, $endDate, $state, $gameType, 'VIRTUAL'));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
} else {
    $return = array(

        "operatorId" => 10178001,
        "errorCode" => 1,
        "errorDescription" => "General Error. (Hash)",
        "timestamp" => (round(microtime(true) * 1000))
    );
    print_r(json_encode($return));
}
