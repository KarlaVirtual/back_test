<?php

/**
 * Este archivo contiene un script para procesar solicitudes relacionadas con la integración
 * de la API de casino Rubyplay, incluyendo operaciones como autenticación, balance, débito,
 * crédito y cancelación de transacciones.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Davison Valencia <davison.valencia@virtualsoft.tech>
 * @version    1.0.0
 * @since      2023-08-26
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Variable que habilita la conexión global en el entorno ["enabledConnectionGlobal"].
 * @var mixed $_ENV     Variable que habilita el tiempo de espera para bloqueos en el entorno ["ENABLEDSETLOCKWAITTIMEOUT"] .
 * @var mixed $URI      Variable que almacena la URI de la solicitud actual.
 * @var mixed $body     Contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data     Datos decodificados del cuerpo de la solicitud en formato JSON.
 * @var mixed $log      Variable utilizada para almacenar información de registro (logs).
 * @var mixed $response Respuesta generada por las operaciones realizadas en el script.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Rubyplay;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json');

$URI = $_SERVER['REQUEST_URI'];

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$body = file_get_contents('php://input');

if ($body != "") {
    $data = $body;
}

$log = " /";
$log = $log . "\r\n" . "-------------Request------------" . "\r\n";
$log = $log . ($URI);
$log = $log . $data;
$requestOrder = "";
ksort($_REQUEST);
$cont = 0;

$data = json_decode($data);

if (true) {
    if (strpos($URI, "playerInformation") !== false) {
        $token = $data->sessionToken;

        /* Procesamos */
        $Rubyplay = new Rubyplay($token, '');
        $response = $Rubyplay->Auth($token);
    } elseif (strpos($URI, "balance") !== false) {
        $token = $data->sessionToken;
        $playerId = $data->playerId;
        $currency = $data->currencyCode;
        $gameId = $data->gameId;

        /* Procesamos */
        $Rubyplay = new Rubyplay($token, '', $playerId);
        $response = $Rubyplay->getBalance($playerId, $currency);
    } elseif (strpos($URI, "debit") !== false) {
        $token = $data->sessionToken;
        $playerId = $data->playerId;
        $currency = $data->currencyCode;
        $gameId = $data->gameId;
        $amount = $data->amount;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;
        $deviceType = $data->deviceType;
        $gameRoundEnd = $data->gameRoundEnd;
        $freeRound = $data->freeRound;

        if ($freeRound == true) {
            $amount = 0;
        }

        $Rubyplay = new Rubyplay($token, '', $playerId);
        $response = $Rubyplay->Debit($gameId, $amount, $roundId, $transactionId, json_encode($data), $gameRoundEnd, $currency);
    } elseif (strpos($URI, "credit") !== false) {
        $token = $data->sessionToken;
        $playerId = $data->playerId;
        $currency = $data->currencyCode;
        $gameId = $data->gameId;
        $amount = $data->amount;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;
        $deviceType = $data->deviceType;
        $gameRoundEnd = $data->gameRoundEnd;

        $Rubyplay = new Rubyplay($token, '', $playerId);
        $response = $Rubyplay->Credit($gameId, $amount, $roundId, $transactionId, json_encode($data), false, $gameRoundEnd, $currency);
    } elseif (strpos($URI, "cancel") !== false) {
        $token = $data->sessionToken;
        $playerId = $data->playerId;
        $roundId = $data->roundId;
        $TransactionId = $data->referenceTransactionId;
        $gameRoundEnd = $data->gameRoundEnd;

        $Rubyplay = new Rubyplay($token, '', $playerId);
        $response = $Rubyplay->Rollback("", $roundId, $TransactionId, $playerId, json_encode($data), $gameRoundEnd);
    }

    $log = "";
    $log = $log . "\r\n" . "--------------Response-----------" . "\r\n";
    $log = $log . json_encode($response);
    $log = $log . "\r\n" . "---------------------------------" . "\r\n";

    echo json_encode($response);
}
