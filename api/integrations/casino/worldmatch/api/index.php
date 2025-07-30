<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API de casino 'WorldMatch'.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Davison Valencia <davison.valencia@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

header('Content-type: application/json; charset=utf-8');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\WorldMatch;

header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$URI = $_SERVER['REQUEST_URI'];

$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);
}

$ConfigurationEnvironment = new ConfigurationEnvironment();

if (strpos($URI, "auth")) {
    $token = $data->token;
    $userid = $data->userid;
    $skin = $data->skin;

    $WorldMatch = new WorldMatch($token, $userid, $skin);
    $response = $WorldMatch->Auth();
}

if (strpos($URI, "balance")) {
    $token = $data->token;
    $userid = $data->userid;
    $skin = $data->skin;

    $WorldMatch = new WorldMatch($token, $userid, $skin);
    $response = $WorldMatch->Balance();
}

if (strpos($URI, "debit")) {
    $token = $data->auth->token;
    $userid = $data->auth->userid;
    $skin = $data->auth->skin;

    $gameId = $data->data->game;
    $sessionToken = $data->data->sessiontoken;
    $currency = $data->data->currency;
    $debitAmount = $data->data->amount;
    $roundId = $data->data->roundid;
    $transactionId = $data->data->transactionid;

    if ($ConfigurationEnvironment->isDevelopment()) {
        $partes = explode('-', $gameId);
        if (count($partes) > 1) {
            $gameId = $partes[1];
        }
    }

    $WorldMatch = new WorldMatch($token, $userid, $skin);
    $response = $WorldMatch->Debit($gameId, $debitAmount, $roundId, $transactionId, $data, false, $currency, 'Debit');
}

if (strpos($URI, "credit")) {
    $operatorId = "";
    $token = $data->auth->token;

    $gameId = $data->data->gameidentity;
    $sessionToken = $data->data->sessiontoken;
    $currency = $data->data->currency;
    $creditAmount = $data->data->amount;
    $roundId = $data->data->roundid;
    $transactionId = $data->data->transactionid;

    if ($ConfigurationEnvironment->isDevelopment()) {
        $partes = explode('-', $gameId);
        if (count($partes) > 1) {
            $gameId = $partes[1];
        }
    }

    $WorldMatch = new WorldMatch($token, $userid, $skin);
    $response = $WorldMatch->Credit($gameId, $creditAmount, $roundId, $transactionId, $data, false, false, $currency);
}

if (strpos($URI, "cancel")) {
    $token = $data->auth->token;
    $userid = $data->auth->userid;
    $skin = $data->auth->skin;

    $transactionId = $data->data->transactionid;

    $WorldMatch = new WorldMatch($token, $userid, $skin);
    $response = $WorldMatch->Rollback($transactionId, $data, false, 'Rollback');
}

print($response);
