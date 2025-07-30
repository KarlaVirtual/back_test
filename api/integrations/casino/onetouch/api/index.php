<?php

/**
 * Este archivo actúa como controlador principal para enrutar las solicitudes entrantes
 * hacia los métodos correspondientes de la integración de casino Onetouch.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     nicolas.guato@virtualsoft.tech
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Variable superglobal que contiene datos del entorno de ejecución.
 * @var mixed $URI      Variable que almacena la URI de la solicitud entrante.
 * @var mixed $data     Objeto que contiene los datos decodificados del cuerpo de la solicitud.
 * @var mixed $response Variable que almacena la respuesta generada por las operaciones realizadas.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Onetouch;

header('Content-type: application/json');

$_ENV["enabledConnectionGlobal"] = 1;

$URI = $_SERVER['REQUEST_URI'];

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$body = json_encode($_REQUEST);
$body = file_get_contents('php://input');

if ($body != "") {
    $data = json_decode($body);
}

$requestOrder = "";
ksort($_REQUEST);
$cont = 0;

if (true) {
    if (strpos($URI, "/user/balance") !== false) {
        $token = $data->token;
        $request_uuid = $data->request_uuid;
        $game_id = $data->game_id;

        /* Procesamos */
        $Onetouch = new Onetouch($token, $request_uuid);
        $response = ($Onetouch->getBalance($game_id));
    } else {
        $datos = $data;

        if (strpos($URI, "/transaction/bet")) {
            $transaction_uuid = $data->transaction_uuid;
            $token = $data->token;
            $round = $data->round;
            $request_uuid = $data->request_uuid;
            $game_id = $data->game_id;
            $currency = $data->currency;
            $offer_id = $data->trntype;
            $bet = $data->bet;

            $amount = floatval(round($data->amount, 5) / 100000);

            $Onetouch = new Onetouch($token, $request_uuid);
            $response = ($Onetouch->Debit($game_id, $amount, $round, $transaction_uuid, json_encode($datos)));
        } elseif (strpos($URI, "/transaction/win")) {
            $transaction_uuid = $data->transaction_uuid;
            $token = $data->token;
            $request_uuid = $data->request_uuid;
            $reference_transaction_uuid = $data->reference_transaction_uuid;
            $round = $data->round;
            $game_id = $data->game_id;
            $currency = $data->currency;
            $offer_id = $data->offer_id;
            $amount = floatval(round($data->amount, 5) / 100000);

            $Onetouch = new Onetouch($token, $request_uuid);
            $response = $Onetouch->Credit($game_id, $amount, $round, $transaction_uuid, json_encode($datos), false);
        } elseif (strpos($URI, "/transaction/rollback")) {
            $transaction_uuid = $data->transaction_uuid; //OK
            $token = $data->token; //OK
            $request_uuid = $data->request_uuid; //OK
            $reference_transaction_uuid = $data->reference_transaction_uuid; //OK
            $game_id = $data->game_id;

            $Onetouch = new Onetouch($token, $request_uuid);
            $response = ($Onetouch->Rollback($reference_transaction_uuid, $transaction_uuid, json_encode($datos)));
        } elseif (strpos($URI, "/transaction/end-round")) {
            $game_id = $data->game_id;
            $round = $data->round;
            $request_uuid = $data->request_uuid;
            $token = $data->token;
            $currency = $data->currency;
            $offer_id = $data->offer_id;

            $Onetouch = new Onetouch($token, $request_uuid);
            $response = $Onetouch->EndRound($round, json_encode($datos), $currency);
        }
    }
    print_r($response);
}
