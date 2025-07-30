<?php

/**
 * Este archivo actúa como controlador principal para enrutar las solicitudes entrantes
 * hacia los métodos correspondientes de la integración de casino Popok.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Nicolas Guato <nicolas.guato@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 *
 * Variables globales utilizadas en el script:
 *
 * @var mixed   $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var integer $_ENV     Indica si la conexión global está habilitada (1 para habilitado).
 * @var string  $_ENV     Configuración para el tiempo de espera de bloqueo.
 * @var boolean $_ENV     Activa o desactiva el modo de depuración.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\POPOK;

header('Content-type: application/json; charset=utf-8');

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';


$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

if ($body != "") {
    $data = $body;
}

$requestOrder = "";
ksort($_REQUEST);
$cont = 0;

$data = json_decode($data);

if (true) {
    if (strpos($URI, "playerInfo") !== false) {
        $token = $data->externalToken;

        /* Procesamos */
        $POPOK = new POPOK($token);
        $response = $POPOK->getBalance();
    } elseif (strpos($URI, "bet") !== false) {
        $token = $data->externalToken;
        $playerId = $data->playerId;
        $currency = $data->currency;
        $gameId = $data->gameId;
        $amount = $data->amount;
        $roundId = "R_" . $data->transactionId;
        $transactionId = $data->transactionId;
        $free = false;


        $POPOK = new POPOK($token);

        $response = $POPOK->Debit($gameId, $amount, $roundId, "D" . $transactionId, json_encode($data), $free);
    } elseif (strpos($URI, "win") !== false) {
        $token = $data->externalToken;
        $playerId = $data->playerId;
        $currency = $data->currency;
        $gameId = $data->gameId;
        $amount = $data->amount;
        $roundId = "R_" . $data->transactionId;
        $transactionId = $data->transactionId;
        $gameRoundEnd = false;

        $POPOK = new POPOK($token);

        $response = $POPOK->Credit($gameId, $amount, $roundId, "C" . $transactionId, json_encode($data), false, $gameRoundEnd, $currency);
    } elseif (strpos($URI, "cancel") !== false) {
        $token = $data->externalToken;
        $playerId = $data->playerId;
        $roundId = "R_" . $data->transactionId;
        $TransactionId = $data->transactionId;


        $POPOK = new POPOK($token);

        $response = $POPOK->Rollback("", $roundId, $TransactionId, $playerId, json_encode($data));
    } elseif (strpos($URI, "tournamentWin") !== false) {
        $token = $data->externalToken;
        $playerId = $data->playerId;
        $currency = $data->currency;
        $gameId = $data->gameId;
        $amount = $data->amount;
        $roundId = "R_" . $data->transactionId;
        $transactionId = $data->transactionId;
        $gameRoundEnd = false;

        $POPOK = new POPOK($token);

        $response = $POPOK->Credit($gameId, $amount, $roundId, "C" . $transactionId, json_encode($data), false, $gameRoundEnd, $currency);
    } elseif (strpos($URI, "promoWin") !== false) {
        $token = $data->externalToken;
        $playerId = $data->playerId;
        $currency = $data->currency;
        $gameId = $data->gameId;
        $amount = $data->amount;
        $roundId = "R_" . $data->transactionId;
        $transactionId = $data->transactionId;
        $gameRoundEnd = false;

        $POPOK = new POPOK($token);

        $response = $POPOK->Credit($gameId, $amount, $roundId, "C" . $transactionId, json_encode($data), false, $gameRoundEnd, $currency);
    }
    print_r($response);
}
