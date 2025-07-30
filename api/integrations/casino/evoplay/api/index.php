<?php

/**
 * Este archivo contiene un script para procesar solicitudes relacionadas con la integración
 * de la API de casino Evoplay, manejando acciones como autenticación, balance, apuestas,
 * ganancias, reembolsos y más.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed   $_REQUEST   Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var integer $_ENV       Indica si la conexión global está habilitada (1 para habilitado) ["enabledConnectionGlobal"].
 * @var string  $_ENV       Configuración para el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var boolean $_ENV       Activa o desactiva el modo de depuración ['debug'].
 * @var string  $URI        URI de la solicitud actual.
 * @var string  $body       Cuerpo de la solicitud en formato JSON o URL-encoded.
 * @var string  $log        Variable utilizada para almacenar registros de eventos o datos.
 * @var array   $headers    Encabezados de la solicitud HTTP.
 * @var string  $hash_value Valor del encabezado "Hash" de la solicitud.
 * @var object  $data       Datos decodificados del cuerpo de la solicitud.
 * @var string  $action     Acción solicitada en la API.
 * @var string  $user       Usuario asociado a la solicitud.
 * @var string  $password   Contraseña asociada al usuario.
 * @var string  $token      Token de autenticación.
 * @var string  $gameid     Identificador del juego en la solicitud.
 * @var string  $response   Respuesta generada por las operaciones de la API.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Evoplay;

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';


$URI = $_SERVER['REQUEST_URI'];

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$body = (file_get_contents('php://input'));
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . "\r\n" . $URI . "\r\n";
$log = $log . $body;

header('Content-Type: application/json');

$htext = 'HT';

/**
 * Obtiene todos los encabezados de la solicitud HTTP.
 *
 * @return array Un arreglo asociativo con los encabezados de la solicitud.
 */
function getRequestHeaders()
{
    $headers = array();
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}

$headers = getRequestHeaders();
$hash_value = "";
foreach ($headers as $header => $value) {
    if ($header == "Hash") {
        $hash_value = $value;
    }
    $htext = $htext . "$header: $value <br />\n";
}

parse_str($body, $body);

$body = json_decode(json_encode($body));
$data = ($body);

$action = $data->action;
$user = $data->user;
$password = $data->password;
$token = $data->token;
$action = $data->name;

$gameid = json_decode($data->data->details)->game->game_id;

$sign = '';

if (true) {
    if ($action == 'init') {
        $Evoplay = new Evoplay($token, $sign);
        $response = ($Evoplay->Auth());
    }

    if ($action == 'balance') {
        $Evoplay = new Evoplay($token, $sign);
        $response = ($Evoplay->getBalance());
    }


    if ($action == 'win') {
        $creditAmount = ($data->data->amount) / 1;
        $roundId = $data->data->round_id;
        $transactionId = $data->data->action_id;

        $transactionId = 'WIN' . $transactionId;

        $datos = $data;

        $Evoplay = new Evoplay($token, $sign);
        $response = ($Evoplay->Credit($gameid, $creditAmount, $roundId, $transactionId, json_encode($datos)));
    }

    if ($action == 'bet') {
        $debitAmount = ($data->data->amount) / 1;
        $roundId = $data->data->round_id;
        $transactionId = $data->data->action_id;
        $transactionId = $data->callback_id;

        $datos = $data;

        $Evoplay = new Evoplay($token, $sign);
        $response = ($Evoplay->Debit($gameid, $debitAmount, $roundId, $transactionId, json_encode($datos)));
    }

    if ($action == 'bonusWin') {
        $debitAmount = ($data->data->amount) / 1;
        $roundId = $data->data->round_id;
        $transactionId = $data->data->action_id;

        $transactionId = $data->callback_id;

        $datos = $data;

        $Evoplay = new Evoplay($token, $sign);

        $response = ($Evoplay->Debit($gameid, $debitAmount, $roundId, 'FS' . $transactionId, json_encode($datos), true));
        $response = ($Evoplay->Credit($gameid, $debitAmount, $roundId, $transactionId, json_encode($datos), true));
    }


    if ($action == 'refund') {
        $rollbackAmount = ($data->data->amount) / 1;
        $roundId = $data->data->refund_round_id;
        $transactionIdOrigin = $data->data->refund_action_id;
        $transactionId = $data->data->refund_action_id;

        $transactionId = $data->data->refund_callback_id;

        $datos = $data;

        $Evoplay = new Evoplay($token, $sign);
        $response = ($Evoplay->Rollback($gameid, $rollbackAmount, $roundId, $transactionId, json_encode($datos)));

        if (json_decode($response)->data->currency == "") {
            $responseArray = [
                "status" => "ok",
                "data" => [
                    "balance" => json_decode($response)->data->balance,
                    "currency" => $data->data->currency
                ]
            ];
            $response = json_encode($responseArray);
        }
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

        $Evoplay = new Evoplay($operatorId, $token, $uid);
        $response = ($Evoplay->Rollback($gameId, $uid, $betTypeID, $currency, $rollbackAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
    }
} else {
    $response = array(

        "operatorId" => 10178001,
        "errorCode" => 1,
        "errorDescription" => "General Error. (Hash)",
        "timestamp" => (round(microtime(true) * 1000))
    );
    $response = json_encode($response);
}
print_r($response);
