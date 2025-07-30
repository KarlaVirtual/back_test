<?php
/**
 * Este archivo contiene un script para manejar las solicitudes de la API del casino 'virtualgeneration',
 * incluyendo autenticación, crédito, débito y reversión de transacciones.
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
 * @var mixed $_SERVER URI de la solicitud actual, utilizada para determinar la acción a realizar ['REQUEST_URI'].
 * @var mixed $data    Datos decodificados del cuerpo de la solicitud en formato JSON.
 * @var mixed $log     Variable utilizada para almacenar y registrar información de la solicitud.
 */

ini_set('display_errors', 'off');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugi;


$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . trim(file_get_contents('php://input'));

$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);
}


if ($URI == "/admin/dao/integrations/casino/ezugi/api/integrations/casino/ezugi/api/auth") {
    $operatorId = $data->operatorId;
    $token = $data->token;

    /* Procesamos */
    $Ezugi = new Ezugi("", "13346577889273645364");
    $Ezugi = new Ezugi($operatorId, $token);
    print_r($Ezugi->Auth());
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

    $Ezugi = new Ezugi($operatorId, $token);

    print_r($Ezugi->Credit($gameId, $uid, $betTypeID, $currency, $creditAmount, $serverId, $roundId, $transactionId, $seatId, $gameDataString, $isEndRound, $creditIndex, $hash));
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
    $seatId = $data->seatId;

    /* Procesamos */

    $Ezugi = new Ezugi($operatorId, $token);

    print_r($Ezugi->Debit($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
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

    $Ezugi = new Ezugi($operatorId, $token);

    print_r($Ezugi->Rollback($gameId, $uid, $betTypeID, $currency, $rollbackAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
}










