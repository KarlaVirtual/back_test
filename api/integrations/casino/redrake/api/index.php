<?php

/**
 * Este archivo contiene un script para procesar y gestionar las solicitudes de la API del casino 'RedRake'.
 *
 * @category   Red
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
 * @var mixed $_ENV     Variable superglobal que contiene configuraciones de entorno utilizadas en el script.
 * @var mixed $URI      Almacena la URI de la solicitud actual.
 * @var mixed $body     Contiene el cuerpo de la solicitud HTTP en formato JSON.
 * @var mixed $data     Almacena los datos decodificados del cuerpo de la solicitud.
 * @var mixed $log      Variable utilizada para almacenar información de registro.
 * @var mixed $action   Acción solicitada en la petición.
 * @var mixed $user     Usuario proporcionado en la solicitud.
 * @var mixed $password Contraseña proporcionada en la solicitud.
 * @var mixed $token    Token de autenticación proporcionado en la solicitud.
 * @var mixed $gameid   Identificador del juego proporcionado en la solicitud.
 * @var mixed $response Respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\RedRake;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$URI = $_SERVER['REQUEST_URI'];

$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);
}

header('Content-Type: application/json');

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . "\r\n" . $URI . "\r\n";
$log = $log . file_get_contents('php://input');

$action = $data->request->action;
$user = $data->request->user;
$password = $data->request->password;
$token = $data->request->token;
$gameid = $data->request->gameid;
$sign = '';

if (true) {
    if ($action == 'account') {
        /* Procesamos */
        $RedRake = new RedRake($token, $sign);
        $response = $RedRake->Auth();
    }

    if ($action == 'balance') {
        /* Procesamos */
        $RedRake = new RedRake($token, $sign);
        $response = $RedRake->getBalance();
    }

    if ($action == 'win') {
        $creditAmount = ($data->params->amount) / 1;
        $roundId = $data->params->roundid;
        $transactionId = $data->params->transactionid;

        $datos = $data;

        /* Procesamos */
        $RedRake = new RedRake($token, $sign);
        $response = $RedRake->Credit($gameid, $creditAmount, $roundId, $transactionId, json_encode($datos));
    }

    if ($action == 'bet') {
        $debitAmount = ($data->params->amount) / 1;
        $roundId = $data->params->roundid;
        $transactionId = $data->params->transactionid;

        $datos = $data;
        $timeInit = time();

        /* Procesamos */
        $RedRake = new RedRake($token, $sign);
        $response = $RedRake->Debit($gameid, $debitAmount, $roundId, $transactionId, json_encode($datos));

        try {
            if (strpos($token, '71P16P') !== false) {
                $timeInit2 = time();
                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . (($timeInit2 - $timeInit)) . 'msFINAL ' . $transactionId . ' ' . $response . "' '#virtualsoft-cron' > /dev/null & ");
            }
        } catch (Exception $e) {
        }
    }

    if ($action == 'bonusWin') {
        $debitAmount = ($data->params->amount) / 1;
        $roundId = $data->params->roundid;
        $transactionId = $data->params->transactionid;

        $datos = $data;

        /* Procesamos */
        $RedRake = new RedRake($token, $sign);
        $response = $RedRake->Credit($gameid, $debitAmount, $roundId, $transactionId, json_encode($datos), true, true);
    }

    if ($action == 'refund') {
        $rollbackAmount = ($data->params->amount) / 1;
        $roundId = $data->params->roundid;
        $transactionIdOrigin = $data->params->transactionid;
        $transactionId = $data->params->refundedtransactionid;

        $datos = $data;

        /* Procesamos */
        $RedRake = new RedRake($token, $sign);
        $response = $RedRake->Rollback($game, $rollbackAmount, $roundId, $transactionId, json_encode($datos));
    }

    if ($_REQUEST['test'] == 'rollback') {
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

        if ($betTypeID == '6') {
            if (strpos($seatId, '-') === false) {
                $seatId = $seatId . '-2';
            }
        }

        /* Procesamos */
        $RedRake = new RedRake($operatorId, $token, $uid);
        $response = $RedRake->Rollback($gameId, $uid, $betTypeID, $currency, $rollbackAmount, $serverId, $roundId, $transactionId, $seatId, $hash);
    }
} else {
    $return = array(
        "operatorId" => 10178001,
        "errorCode" => 1,
        "errorDescription" => "General Error. (Hash)",
        "timestamp" => (round(microtime(true) * 1000))
    );
    $response = json_encode($return);
}

$log = "";
$log = $log . "/" . time();
$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($response);

print_r($response);
