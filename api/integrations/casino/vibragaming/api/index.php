<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API del casino Vibragaming.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 *
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Variable superglobal que contiene datos del entorno de ejecución.
 * @var mixed $URI      Variable que almacena la URI de la solicitud actual.
 * @var mixed $body     Cuerpo de la solicitud en formato JSON.
 * @var mixed $data     Datos decodificados del cuerpo de la solicitud.
 * @var mixed $datos    Alias para los datos decodificados de la solicitud.
 * @var mixed $log      Variable utilizada para almacenar información de registro.
 * @var mixed $response Respuesta generada por las operaciones realizadas en el script.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Vibragaming;


header('Content-type: application/json');

$URI = $_SERVER['REQUEST_URI'];

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$body = json_encode($_REQUEST);

if ($body != "") {
    $data = json_decode($body);
}

$datos = $data;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($URI);


$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . ' URI ';
$log = $log . ($URI);
$log = $log . ' BODY';
$log = $log . $body;

$requestOrder = "";
ksort($_REQUEST);
$cont = 0;


if (true) {
    if (strpos($URI, "initializeGame") !== false) {
        $sessionId = $data->sessionId;
        $gameId = $data->gameId;
        $userId = $data->userId;
        $accountCurrency = $data->accountCurrency;
        $token = $data->token;
        $gameMode = $data->gameMode;
        $channel = $data->channel;
        $interruptedPlay = $data->interruptedPlay;

        $token = $data->SessionId;
        $hash = $data->Hash;
        $ExtraData = $data->ExtraData;

        /* Procesamos */
        $Vibragaming = new Vibragaming($token, $sessionId, $userId);
        $response = $Vibragaming->Auth("");
    } elseif (strpos($URI, "requestBalance") !== false) {
        $userId = $data->userId;
        $sessionId = $data->sessionId;
        $gameId = $data->gameId;
        $gameMode = $data->gameMode;
        $token = $data->token;

        /* Procesamos */
        $Vibragaming = new Vibragaming($token, $sessionId, $userId);
        $response = $Vibragaming->getBalance();
    } elseif ((strpos($URI, "updateBalance") !== false) || (strpos($URI, "updateBalanceForced") !== false)) {
        $userId = $data->userId;
        $sessionId = $data->sessionId;
        $gameId = $data->gameId;
        $operation = $data->operation;
        $amount = floatval(round($data->amount, 2) / 100);
        $amountCurrency = $data->amountCurrency;
        $gameMode = $data->gameMode;
        $transactionId = $data->transactionId;
        $playId = $data->playId;
        $token = $data->token;
        $type = $data->type;
        $playingFreePlay = $data->playingFreePlay;
        $promotionCode = $data->promotionCode;

        $Vibragaming = new Vibragaming($token, $sessionId, $userId);

        if ($operation == "DEBIT") {
            $response = $Vibragaming->Debit($gameId, $amount, $playId, $transactionId, json_encode($datos));
        } elseif ($operation == "CREDIT") {
            $response = $Vibragaming->Credit($gameId, $amount, $playId, $transactionId, json_encode($datos));
        }
    } elseif (strpos($URI, "voidTransaction") !== false) {
        $userId = $data->userId;
        $sessionId = $data->sessionId;
        $gameId = $data->gameId;
        $transactionId = $data->transactionId;
        $playId = $data->playId;
        $token = $data->token;

        $Vibragaming = new Vibragaming($token, $sessionId, $userId);
        $response = $Vibragaming->Rollback($playId, $transactionId, json_encode($datos));
    }

    $log = "";
    $log = "";
    $log = $log . "/" . time();

    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}
