<?php

/**
 * Este archivo contiene un script para manejar solicitudes HTTP relacionadas con la integración de la plataforma virtualgeneration.
 * Proporciona endpoints para autenticación, crédito, débito y reversión de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $log            Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $URI            Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER        Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body           Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data           Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $operatorId     Variable que almacena el identificador del operador.
 * @var mixed $token          Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $Ezugi          Variable que almacena información relacionada con la plataforma Ezugi.
 * @var mixed $gameId         Variable que almacena el identificador de un juego.
 * @var mixed $uid            Variable que almacena el identificador único de un usuario.
 * @var mixed $betTypeID      Variable que almacena el identificador del tipo de apuesta.
 * @var mixed $currency       Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $creditAmount   Variable que almacena el monto del crédito.
 * @var mixed $serverId       Variable que almacena el identificador del servidor.
 * @var mixed $roundId        Variable que almacena el identificador de la ronda.
 * @var mixed $transactionId  Variable que almacena el identificador único de una transacción.
 * @var mixed $hash           Variable que almacena un valor hash para seguridad o verificación.
 * @var mixed $gameDataString Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $isEndRound     Variable que indica si la ronda ha finalizado.
 * @var mixed $creditIndex    Variable que almacena el índice de crédito.
 * @var mixed $seatId         Variable que almacena el identificador del asiento.
 * @var mixed $debitAmount    Variable que almacena el monto del débito.
 * @var mixed $rollbackAmount Variable que almacena el monto de reversión.
 */

ini_set('display_errors', 'off');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugi;


$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

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










