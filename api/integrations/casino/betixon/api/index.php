<?php

/**
 * Este archivo actúa como controlador principal para enrutar las solicitudes entrantes
 * hacia los métodos correspondientes de la integración de casino Betixon.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Nicolas Guato <nicolas.guato@virtualsoft.tech>
 * @version    Ninguna
 * @since      2025-05-09
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Betixon;

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
    $data = json_decode($body);


    if (strpos($URI, "Authenticate") !== false) {
        $token = $data->Token;
        $sign = $_REQUEST["sign"];

        $Betixon = new Betixon($token, $sign);
        $response = ($Betixon->Auth());
    }


    if (strpos($URI, "GetBalance") !== false) {
        $token = $data->Token;

        $Betixon = new Betixon($token, $sign);
        $response = ($Betixon->getBalance());
    }

    if (strpos($URI, "DebitAndCredit") !== false) {
        $token = $data->Token;

        $DebitAmount = ($data->DebitAmount);
        $CreditAmount = $data->CreditAmount;
        $GameCode = $data->GameCode;

        $PlayerId = $data->PlayerId;
        $transactionId = $data->RGSTransactionId;

        $RoundId = $data->RoundId;

        $Currency = $data->Currency;

        $datos = $data;

        /* Procesamos */

        $Betixon = new Betixon($token, $sign, $PlayerId);


        $freespin = false;

        if ($data->Promo != null && $data->Promo != '') {
            $freespin = true;
        }

        $response = ($Betixon->Debit($GameCode, $DebitAmount, $RoundId, $transactionId, json_encode($datos), $freespin));

        $token = $data->Token;


        $DebitAmount = ($data->DebitAmount);
        $CreditAmount = $data->CreditAmount;
        $GameCode = $data->GameCode;

        $PlayerId = $data->PlayerId;
        $transactionId = "credit" . $data->RGSTransactionId;

        $RoundId = $data->RoundId;

        $Currency = $data->Currency;

        $datos = $data;

        $Betixon = new Betixon($token, $sign, $PlayerId);
        $response = $Betixon->Credit($GameCode, $CreditAmount, $RoundId, $transactionId, json_encode($datos));
    }

    if (strpos($URI, "RollFIX") !== false) {
        $sign = $data->sign;
        $token = $data->token;

        $currency = $data->currency;
        $rollbackAmount = 0;
        $date = $data->date;
        $player = $data->PlayerId;
        $roundId = $data->RGSRelatedTransactionId;
        $transactionId = $data->RGSRelatedTransactionId;

        $datos = $data;

        $Betixon = new Betixon($token, $sign, $player);
        $response = $Betixon->Rollback($rollbackAmount, $roundId, $transactionId, $player, json_encode($datos));
    }

    if (strpos($URI, "Rollback") !== false) {
        $sign = $data->sign;
        $token = $data->token;

        $currency = $data->currency;
        $rollbackAmount = 0;
        $date = $data->date;
        $player = $data->PlayerId;
        $roundId = $data->RGSRelatedTransactionId;
        $transactionId = $data->RGSRelatedTransactionId;

        $datos = $data;

        $Betixon = new Betixon($token, $sign, $player);
        $response = $Betixon->Rollback($rollbackAmount, $roundId, $transactionId, $player, json_encode($datos));
    }
    print_r($response);
}
