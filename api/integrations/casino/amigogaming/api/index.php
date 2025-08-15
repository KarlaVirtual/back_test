<?php

/**
 * Este archivo contiene la implementación de la API para la integración con el proveedor de casino 'AmigoGaming'.
 * Proporciona endpoints para autenticación, consulta de saldo, apuestas, reembolsos, resultados, y más.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    ninguna
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Contiene los datos enviados a través del método REQUEST.
 * @var mixed $URI      Almacena la URI de la solicitud actual.
 * @var mixed $data     Contiene los datos decodificados del cuerpo de la solicitud.
 * @var mixed $response Almacena la respuesta generada por las operaciones de la API.
 * @var mixed $log      Variable utilizada para registrar información de depuración y solicitudes.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\AmigoGaming;

header('Content-type: application/json');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$URI = $_SERVER['REQUEST_URI'];

$body = json_encode($_REQUEST);
if ($body != "") {
    $data = json_decode($body);
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$date = date("Y-m-d H:i:s");

$log = "";
$log = $log . "\r\n";
$log = $log . "************DATE:" . $date . "************";
$log = $log . "\r\n";
$log = "\r\n" . "----------------DATA REQUEST----------------" . time();
$log = $log . "\r\n";
$log = $log . ($URI);
$log = $log . "\r\n";
$log = $log . (http_build_query($_REQUEST));
$log = $log . "\r\n";
$log = $log . json_encode($data);
$log = $log . "\r\n";
$log = $log . file_get_contents('php://input');
$log = $log . "\r\n" . "---------------------------------" . "\r\n";
$requestOrder = "";
ksort($_REQUEST);
$cont = 0;

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$date = date("Y-m-d H:i:s");

if (strpos($URI, "authenticate.html") !== false) {
    $hash = $_REQUEST["hash"];
    $token = $_REQUEST["token"];
    $providerId = $_REQUEST["providerId"];
    $gameId = $_REQUEST["gameId"];

    /* Procesamos */
    $AmigoGaming = new AmigoGaming($token, $hash, "");
    $response = ($AmigoGaming->Auth());
} elseif (strpos($URI, "/balance.html") !== false) {
    $hash = $_REQUEST["hash"];
    $token = $_REQUEST["token"];
    $providerId = $_REQUEST["providerId"];
    $gameId = $_REQUEST["gameId"];
    $gameId = str_replace("%40", "@", $gameId);
    $PlayerId = $_REQUEST["userId"];

    /* Procesamos */
    $AmigoGaming = new AmigoGaming($token, $hash, "");
    $response = ($AmigoGaming->getBalance($PlayerId));
} elseif (strpos($URI, "refund.html") !== false) {
    $action = $data->action;

    $hash = $_REQUEST["hash"];
    $token = $_REQUEST["token"];
    $userId = $_REQUEST["userId"];
    $rollbackAmount = $_REQUEST["amount"];
    $gameId = $_REQUEST["gameId"];
    $gameId = str_replace("%40", "@", $gameId);
    $roundId = $_REQUEST["roundId"];
    $reference = $_REQUEST["reference"];
    $providerId = $_REQUEST["providerId"];
    $timestamp = $_REQUEST["timestamp"];

    $datos = $data;

    /* Procesamos */

    $AmigoGaming = new AmigoGaming($token, "", $userId);
    $response = ($AmigoGaming->Rollback($rollbackAmount, $roundId, $reference, $gameId, json_encode($datos)));
} elseif (strpos($URI, "bet.html")) {
    $hash = $_REQUEST["hash"];
    $userId = $_REQUEST["userId"];
    $gameId = $_REQUEST["gameId"];

    $gameId = str_replace("%40", "@", $gameId);
    $roundId = $_REQUEST["roundId"];
    $amount = floatval($_REQUEST["amount"]);
    $reference = $_REQUEST["reference"];
    $providerId = $_REQUEST["providerId"];
    $timestamp = $_REQUEST["timestamp"];
    $roundDetails = $_REQUEST["roundDetails"];
    $token = $_REQUEST["token"];

    $datos = $data;
    $isfreeSpin = false;

    if ($data->bonusCode != '') {
        $isfreeSpin = true;
        $amount = 0;
    }

    $AmigoGaming = new AmigoGaming($token, $hash, $userId);
    $response = ($AmigoGaming->Debit($gameId, $amount, $roundId, $reference, json_encode($datos), $isfreeSpin));
} elseif (strpos($URI, "result.html")) {
    $hash = $_REQUEST["hash"];
    $userId = $_REQUEST["userId"];
    $gameId = $_REQUEST["gameId"];
    $gameId = str_replace("%40", "@", $gameId);
    $roundId = $_REQUEST["roundId"];
    $amount = floatval($_REQUEST["amount"]);
    $reference = $_REQUEST["reference"];
    $providerId = $_REQUEST["providerId"];
    $timestamp = $_REQUEST["timestamp"];
    $roundDetails = $_REQUEST["roundDetails"];
    $token = $_REQUEST["token"];

    $datos = $data;
    $isfreeSpin = false;

    $datos = $data;

    if ($data->bonusCode != '') {
        $isfreeSpin = true;
    }

    $amount = number_format((float)$amount, 2, '.', '');
    $AmigoGaming = new AmigoGaming($token, $hash, $userId);
    $response = $AmigoGaming->Credit($gameId, $amount, $roundId, $reference, json_encode($datos), $isfreeSpin);
} elseif (strpos($URI, "jackpotWin.html")) {
    $hash = $_REQUEST["hash"];
    $userId = $_REQUEST["userId"];
    $gameId = $_REQUEST["gameId"];
    $gameId = str_replace("%40", "@", $gameId);
    $roundId = $_REQUEST["roundId"];
    $amount = floatval($_REQUEST["amount"]);
    $reference = $_REQUEST["reference"];
    $providerId = $_REQUEST["providerId"];
    $timestamp = $_REQUEST["timestamp"];
    $roundDetails = $_REQUEST["roundDetails"];
    $token = $_REQUEST["token"];
    $RoundId = "jackpot" . $reference;
    $datos = $data;

    $AmigoGaming = new AmigoGaming($token, $hash, $userId);

    $response = ($AmigoGaming->Debit($gameId, 0, $RoundId, "D" . $reference, json_encode($datos)));
    $response = $AmigoGaming->Credit($gameId, $amount, $RoundId, $reference, json_encode($datos));
} elseif (strpos($URI, "bonusWin.html")) {
    $hash = $_REQUEST["hash"];
    $userId = $_REQUEST["userId"];
    $gameId = $_REQUEST["gameId"];
    $roundId = $_REQUEST["roundId"];
    $amount = floatval($_REQUEST["amount"]);
    $reference = $_REQUEST["reference"];
    $providerId = $_REQUEST["providerId"];
    $timestamp = $_REQUEST["timestamp"];
    $roundDetails = $_REQUEST["roundDetails"];
    $token = $_REQUEST["token"];

    $datos = $data;
    $AmigoGaming = new AmigoGaming($token, $hash, $userId);
    $response = $AmigoGaming->bonusCheck($userId, $roundId, $reference);
} elseif (strpos($URI, "expired")) {
    $hash = $_REQUEST["hash"];
    $userId = $_REQUEST["userId"];
    $gameId = $_REQUEST["gameId"];
    $gameId = str_replace("%40", "@", $gameId);
    $roundId = $_REQUEST["roundId"];
    $amount = floatval($_REQUEST["amount"]);
    $reference = $_REQUEST["reference"];
    $providerId = $_REQUEST["providerId"];
    $timestamp = $_REQUEST["timestamp"];
    $roundDetails = $_REQUEST["roundDetails"];
    $token = $_REQUEST["token"];

    $datos = $data;

    $response = array(
        "error" => 0,
        "description" => "Success"
    );
    $response = json_encode($response);
}

$log = "";
$log = "";
$log = $log . "/" . time();
$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($response);
print_r($response);
