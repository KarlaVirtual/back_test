<?php

/**
 * Este archivo actúa como punto de entrada para procesar solicitudes entrantes
 * relacionadas con la integración del casino 'Ezugi', incluyendo autenticación,
 * débitos, créditos y rollbacks desde la plataforma de Ezugi.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     nicolas.guato@virtualsoft.tech
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var array  $_REQUEST Contiene los datos enviados a través del método REQUEST.
 * @var string $URI      Almacena la URI de la solicitud entrante.
 * @var string $body     Contiene el cuerpo de la solicitud en formato JSON.
 * @var object $data     Objeto decodificado del cuerpo de la solicitud.
 * @var mixed  $response Almacena la respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugi;
$_ENV["enabledConnectionGlobal"] = 1;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);
}

if (true) {
    if (strpos($URI, "auth") !== false) {
        $operatorId = $data->operatorId;
        $token = $data->token;

        $Ezugi = new Ezugi($operatorId, $token);
        $response = $Ezugi->Auth();
    }

    if (strpos($URI, "debit") !== false) {
        $operatorId = $data->operatorId;
        $token = $data->token;

        $gameId = $data->tableId;
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

        $Ezugi = new Ezugi($operatorId, $token, $uid);
        $response = $Ezugi->Debit($gameId, $currency, $debitAmount, $roundId, $transactionId, $data);
    }

    if (strpos($URI, "credit") !== false) {
        $operatorId = $data->operatorId;
        $token = $data->token;

        $gameId = $data->tableId;
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

        $Ezugi = new Ezugi($operatorId, $token, $uid);
        $response = $Ezugi->Credit($gameId, $currency, $creditAmount, $roundId, $transactionId, $isEndRound, $data);
    }


    if (strpos($URI, "rollback") !== false) {
        $operatorId = $data->operatorId;
        $token = $data->token;

        $uid = $data->uid;
        $currency = $data->currency;
        $rollbackAmount = $data->rollbackAmount;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;

        $Ezugi = new Ezugi($operatorId, $token, $uid);
        $response = $Ezugi->Rollback($currency, $rollbackAmount, $roundId, $transactionId, $data);
    }

    print($response);
}
