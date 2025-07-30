<?php

/**
 * Este archivo contiene la implementación de la API para la integración con el casino 'bgb'.
 * Proporciona endpoints para autenticación, consulta de saldo, depósitos, débitos y rollbacks.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Variables de entorno utilizadas para configurar la conexión global y el tiempo de espera.
 */

ini_set('display_errors', 'off');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Bgb;

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';


$URI = $_SERVER['REQUEST_URI'];
$body = json_encode($_REQUEST);
if ($body != "") {
    $data = json_decode($body);
}


$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . "\r\n" . $URI . "\r\n";
$log = $log . "\r\n" . json_encode($data) . "\r\n";
if ($_REQUEST['test'] == 'auth') {
    $token = $data->Token;

    /* Procesamos */
    $Bgb = new Bgb($token);
    print_r($Bgb->Auth());
}

if ($URI == "/admin/dao/integrations/casino/bgb/api//GetBalance") {
    $token = $data->Token;

    /* Procesamos */
    $Bgb = new Bgb($token);
    $respuesta = ($Bgb->Auth());

    $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
    $log = $log . (($respuesta));

    print_r($respuesta);
}


if ($URI == "/admin/dao/integrations/casino/bgb/api//Deposit") {
    $token = $data->Token;
    $transaccionId = $data->OperationCode;
    $debitAmount = $data->Amount;


    $gameId = 4369;

    /* Procesamos */

    $Bgb = new Bgb($token);

    print_r($Bgb->Debit($gameId, $debitAmount, $transaccionId, json_encode($data)));
}

if ($_REQUEST['test'] == 'deposit') {
    $token = $data->Token;
    $transaccionId = $data->OperationCode;
    $debitAmount = $data->Amount;


    $gameId = 4369;

    /* Procesamos */

    $Bgb = new Bgb($token);

    print_r($Bgb->Debit($gameId, $debitAmount, $transaccionId, json_encode($data)));
}

if ($_REQUEST['test'] == 'debit') {
    $operatorId = $data->operatorId;
    $token = $data->token;


    $gameId = $data->gameId;
    $uid = $data->uid;
    $betTypeID = $data->betTypeID;
    $currency = $data->currency;
    $debitAmount = $data->debitAmount;
    $serverId = $data->serverId;
    $roundId = $data->roundId;
    $transactionId = $data->transactionId;
    $hash = $data->hash;
    $seatId = $data->seatId;

    if ($betTypeID == '6') {
        if (strpos($seatId, '-') === false) {
            $seatId = $seatId . '-2';
        }
    }

    /* Procesamos */

    $Bgb = new Bgb($operatorId, $token, $uid);

    print_r($Bgb->Debit($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
}

if ($URI == "/admin/dao/integrations/casino/ezugi/api/integrations/casino/ezugi/api/debit") {
    $operatorId = $data->operatorId;
    $token = $data->token;


    $gameId = $data->gameId;
    $uid = $data->uid;
    $betTypeID = $data->betTypeID;
    $currency = $data->currency;
    $debitAmount = $data->debitAmount;
    $serverId = $data->serverId;
    $roundId = $data->roundId;
    $transactionId = $data->transactionId;
    $hash = $data->hash;
    $hash = $_GET['hash'];
    $seatId = $data->seatId;

    if ($betTypeID == '6') {
        if (strpos($seatId, '-') === false) {
            $seatId = $seatId . '-2';
        }
    }

    /* Procesamos */

    $Bgb = new Bgb($operatorId, $token, $uid);

    print_r($Bgb->Debit($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
}


if ($URI == "/admin/dao/integrations/casino/ezugi/api/integrations/casino/ezugi/api/rollback") {
    $operatorId = $data->operatorId;
    $token = $data->token;


    $gameId = $data->gameId;
    $uid = $data->uid;
    $betTypeID = $data->betTypeID;
    $currency = $data->currency;
    $rollbackAmount = $data->rollbackAmount;
    $serverId = $data->serverId;
    $roundId = $data->roundId;
    $transactionId = $data->transactionId;
    $hash = $data->hash;
    $seatId = $data->seatId;

    /* Procesamos */

    $Bgb = new Bgb($operatorId, $token, $uid);

    print_r($Bgb->Rollback($gameId, $uid, $betTypeID, $currency, $rollbackAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
}










