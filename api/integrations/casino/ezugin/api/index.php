<?php

/**
 * Este archivo contiene un script para procesar y manejar las operaciones de integración
 * con el casino 'Ezugi', incluyendo autenticación, créditos, débitos y rollbacks.
 *
 * @category   Integración
 * @package    API
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST       Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV           Variable superglobal que contiene variables de entorno.
 * @var mixed $URI            Contiene la URI de la solicitud actual.
 * @var mixed $body           Contiene el cuerpo de la solicitud HTTP.
 * @var mixed $data           Objeto decodificado del cuerpo de la solicitud HTTP.
 * @var mixed $log            Variable utilizada para almacenar información de registro.
 * @var mixed $operatorId     Identificador del operador proporcionado en la solicitud.
 * @var mixed $token          Token de autenticación proporcionado en la solicitud.
 * @var mixed $gameId         Identificador del juego proporcionado en la solicitud.
 * @var mixed $uid            Identificador único del usuario proporcionado en la solicitud.
 * @var mixed $betTypeID      Tipo de apuesta proporcionado en la solicitud.
 * @var mixed $currency       Moneda utilizada en la transacción.
 * @var mixed $creditAmount   Monto de crédito proporcionado en la solicitud.
 * @var mixed $debitAmount    Monto de débito proporcionado en la solicitud.
 * @var mixed $rollbackAmount Monto de rollback proporcionado en la solicitud.
 * @var mixed $serverId       Identificador del servidor proporcionado en la solicitud.
 * @var mixed $roundId        Identificador de la ronda proporcionado en la solicitud.
 * @var mixed $transactionId  Identificador de la transacción proporcionado en la solicitud.
 * @var mixed $hash           Hash de seguridad proporcionado en la solicitud.
 * @var mixed $gameDataString Cadena de datos del juego proporcionada en la solicitud.
 * @var mixed $isEndRound     Indica si la ronda ha finalizado.
 * @var mixed $creditIndex    Índice de crédito proporcionado en la solicitud.
 * @var mixed $seatId         Identificador del asiento proporcionado en la solicitud.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugin;


$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);
}


$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . trim(file_get_contents('php://input'));

if ($_REQUEST['test'] == 'auth') {
    $operatorId = $data->operatorId;
    $token = $data->token;

    /* Procesamos */
    $Ezugin = new Ezugin($operatorId, $token);
    print_r($Ezugin->Auth());
}

if ($URI == "/admin/dao/integrations/casino/ezugi/api/integrations/casino/ezugi/api/auth") {
    $operatorId = $data->operatorId;
    $token = $data->token;

    /* Procesamos */
    $Ezugin = new Ezugin($operatorId, $token);
    print_r($Ezugin->Auth());
}


if ($URI == "/admin/dao/integrations/casino/ezugi/api/integrations/casino/ezugi/api/credit") {
    $operatorId = $data->operatorId;
    $token = $data->token;


    $gameId = $data->gameId;
    $uid = $data->uid;
    $betTypeID = $data->betTypeID;
    $currency = $data->currency;
    $creditAmount = $data->creditAmount;
    $serverId = $data->serverId;
    $roundId = $data->roundId;
    $transactionId = $data->transactionId;
    $hash = $data->hash;
    $gameDataString = $data->gameDataString;
    $isEndRound = $data->isEndRound;
    $creditIndex = $data->creditIndex;
    $seatId = $data->seatId;


    /* Procesamos */

    $Ezugin = new Ezugin($operatorId, $token, $uid);

    print_r($Ezugin->Credit($gameId, $uid, $betTypeID, $currency, $creditAmount, $serverId, $roundId, $transactionId, $seatId, $gameDataString, $isEndRound, $creditIndex, $hash));
}

if ($_REQUEST['test'] == 'credit') {
    $operatorId = $data->operatorId;
    $token = $data->token;


    $gameId = $data->gameId;
    $uid = $data->uid;
    $betTypeID = $data->betTypeID;
    $currency = $data->currency;
    $creditAmount = $data->creditAmount;
    $serverId = $data->serverId;
    $roundId = $data->roundId;
    $transactionId = $data->transactionId;
    $hash = $data->hash;
    $gameDataString = $data->gameDataString;
    $isEndRound = $data->isEndRound;
    $creditIndex = $data->creditIndex;
    $seatId = $data->seatId;


    /* Procesamos */

    $Ezugin = new Ezugin($operatorId, $token, $uid);

    print_r($Ezugin->Credit($gameId, $uid, $betTypeID, $currency, $creditAmount, $serverId, $roundId, $transactionId, $seatId, $gameDataString, $isEndRound, $creditIndex, $hash));
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

    $Ezugin = new Ezugin($operatorId, $token, $uid);

    print_r($Ezugin->Debit($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
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

    $Ezugin = new Ezugin($operatorId, $token, $uid);

    print_r($Ezugin->Debit($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
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

    $Ezugin = new Ezugin($operatorId, $token, $uid);

    print_r($Ezugin->Rollback($gameId, $uid, $betTypeID, $currency, $rollbackAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
}










