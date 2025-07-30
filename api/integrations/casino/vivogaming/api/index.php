<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API de casino 'Vivogaming'.
 * Proporciona funcionalidades como autenticación, consulta de saldo, cambio de saldo, y manejo de transacciones.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Variable superglobal que contiene información del entorno de ejecución.
 * @var mixed $URI      Almacena la URI de la solicitud actual.
 * @var mixed $body     Contiene el cuerpo de la solicitud HTTP.
 * @var mixed $method   Método de la solicitud HTTP (GET, POST, etc.).
 * @var mixed $data     Almacena los datos procesados desde el cuerpo de la solicitud en formato XML.
 * @var mixed $log      Variable utilizada para almacenar información de registro.
 * @var mixed $response Almacena la respuesta generada por las operaciones realizadas.
 */


ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Vivogaming;

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-type: application/xml');

$URI = $_SERVER['REQUEST_URI'] . " C " . $_SERVER['REQUEST_METHOD'];
$body = trim(file_get_contents('php://input'));

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

if ($body != "") {
    Header('Content-type: text/xml');

    $data = simplexml_load_string($body);
}
$_ENV["enabledConnectionGlobal"] = 1;

if (strpos(strtolower($URI), "authenticate") !== false) {
    $Authenticate = $data;

    $AccessToken = $_REQUEST["hash"];
    $Token = $_REQUEST["token"];

    $Vivogaming = new Vivogaming($Token, $AccessToken, "");

    $response = ($Vivogaming->Auth());
}

if (strpos(strtolower($URI), "getbalance") !== false) {
    $Balance = $data;

    $externalId = intval(explode('Usuario', $_REQUEST["userId"])[1]);

    $AccessToken = $_REQUEST["hash"];

    $Vivogaming = new Vivogaming($Token, $AccessToken, $externalId);
    $response = ($Vivogaming->getBalance());
}


if (strpos(strtolower($URI), "status") !== false) {
    $Balance = $data;

    $externalId = intval(explode('Usuario', $_REQUEST["userId"])[1]);

    $AccessToken = $_REQUEST["hash"];
    $casinoTransactionId = $_REQUEST["casinoTransactionId"];

    $Vivogaming = new Vivogaming($Token, $AccessToken, $externalId);
    $response = ($Vivogaming->getStatusTransaction($casinoTransactionId));
}

if (strpos(strtolower($URI), "changebalance") !== false) {
    $reserve = $data;

    $Token = (string)$reserve->token;
    $accessToken = (string)$_REQUEST["hash"];

    $game = (string)$_REQUEST["gameId"];
    $transactionId = (string)$_REQUEST["TransactionID"];
    $real = (string)$_REQUEST["Amount"];
    $roundId = (string)$_REQUEST["roundId"];
    $gameId = (string)$_REQUEST["gameId"];
    $transactionDescription = (string)$_REQUEST["TrnDescription"];
    $history = (string)$_REQUEST["History"];
    $isRoundFinished = (string)$_REQUEST["isRoundFinished"];
    $TrnType = (string)$_REQUEST["TrnType"];
    $externalId = intval(explode('Usuario', $_REQUEST["userId"])[1]);

    $bets = [];

    $Vivogaming = new Vivogaming($Token, $accessToken, $externalId);

    $datos = array(
        "game" => (string)$game,
        "transactionId" => (string)$transactionId,
        "transactionDescription" => (string)$transactionDescription,
        "history" => (string)$history,
        "isRoundFinished" => (string)$isRoundFinished,
        "trnType" => (string)$TrnType,
        "real" => (string)$real,
        "currency" => (string)$currency,
        "gameId" => (string)$gameId,
        "accessToken" => (string)$accessToken,
        "roundId" => (string)$roundId
    );

    $parts = explode('=', $transactionDescription);
    $tableId = $parts[1];

    if ($isRoundFinished == 'true' || $isRoundFinished == 1 || $isRoundFinished == '1') {
        $isEnd = true;
    } else {
        $isEnd = false;
    }
    if ($gameId == "") {
        $gameId = "DEFAULT";
    }
    if ($TrnType == 'BET') {
        $response = ($Vivogaming->Debit($tableId, $gameId, $roundId, "", $real, $transactionId, json_encode($datos), false, $bets));
    }

    if ($TrnType == 'WIN') {
        $response = ($Vivogaming->Credit($tableId, $gameId, $roundId, "", $real, $transactionId, $isEnd, json_encode($datos)));
    }

    if ($TrnType == 'CANCELED_BET') {
        $response = ($Vivogaming->Rollback($tableId, $gameId, $roundId, "", $real, $transactionId, json_encode($datos)));
    }
}

print_r($response);
