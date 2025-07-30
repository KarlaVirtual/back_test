<?php
/**
 * Este archivo contiene la implementación de la API de casino 'TADAGAMING'.
 * Procesa solicitudes relacionadas con autenticación, apuestas, cancelación de apuestas
 * y sesiones de apuestas, interactuando con la integración de Tadagaming.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Variable superglobal que contiene variables de entorno.
 * @var mixed $URI      Contiene la URI de la solicitud actual.
 * @var mixed $log      Almacena información de registro de solicitudes y respuestas.
 * @var mixed $body     Contiene el cuerpo de la solicitud en formato JSON.
 * @var mixed $data     Almacena los datos decodificados del cuerpo de la solicitud.
 * @var mixed $response Almacena la respuesta generada por las operaciones realizadas.
 */

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Tadagaming;

$URI = $_SERVER['REQUEST_URI'];
$_ENV["enabledConnectionGlobal"] = 1;

$body = file_get_contents('php://input');
$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);

if ($body != "") {
    header('Content-type: application/json');
    $data = json_decode($body);
}

$log = time();

$ConfigurationEnvironment = new ConfigurationEnvironment();

if (strpos($URI, "auth") !== false) {
    $reqId = $data->reqId;
    $token = $data->token;

    /* Procesamos */
    $Tadagaming = new Tadagaming($token, "");
    $response = ($Tadagaming->Auth());
}

if (strpos($URI, "bet") !== false) {
    $reqId = $data->reqId;
    $token = $data->token;
    $currency = $data->currency;
    $game = $data->game;
    $round = $data->round;
    $wagersTime = $data->wagersTime;
    $betAmount = $data->betAmount;
    $winloseAmount = $data->winloseAmount;
    $userId = $data->userId;
    $transactionId = $data->round . $data->wagersTime;

    $isFreeSpin = false;
    if ($data->freeSpinData != '') {
        $isFreeSpin = true;
    }

    /* Procesamos */
    $Tadagaming = new Tadagaming($token, $userId);

    $response = ($Tadagaming->Debit($game, $betAmount, $round, $transactionId, $data, $isFreeSpin));
    $resp = json_decode($response);
    if ($resp->errorCode == 0) {
        $response = $Tadagaming->credit($game, $winloseAmount, $round, 'C_' . $transactionId, $data);
    }
}

if (strpos($URI, "cancelBet") !== false) {
    $reqId = $data->reqId;
    $currency = $data->currency;
    $game = $data->game;
    $round = $data->round;
    $betAmount = $data->betAmount;
    $winloseAmount = $data->winloseAmount;
    $userId = $data->userId;
    $token = $data->token;
    $transactionId = $data->round . $data->reqId;

    /* Procesamos */
    $Tadagaming = new Tadagaming("", $userId);
    $response = ($Tadagaming->Rollback($game, $round, $transactionId, $userId, $data));
}

if (strpos($URI, "sessionBet") !== false) {
    $reqId = $data->reqId;
    $token = $data->token;
    $currency = $data->currency;
    $game = $data->game;
    $round = $data->round;
    $wagersTime = $data->wagersTime;
    $betAmount = $data->betAmount;
    $winloseAmount = $data->winloseAmount;
    $sessionId = $data->sessionId;
    $type = $data->type;
    $preserve = $data->preserve;
    $PlayerId = $data->userId;

    /* Procesamos */
    $Tadagaming = new Tadagaming($token, $PlayerId);

    if ($data->type == 1) {
        $Amount = $betAmount + $preserve;
        $response = $Tadagaming->Debit($game, $Amount, $sessionId, $round, $data);
    } else {
        $WinAmount = $preserve - $betAmount + $winloseAmount;
        $turnover = $data->turnover;
        $response = $Tadagaming->credit($game, $WinAmount, $sessionId, $round, $data);
    }
}

$log = $log . "\r\n" . "-------------Response------------" . "\r\n";
$log = $log . ($response);

print_r($response);


















