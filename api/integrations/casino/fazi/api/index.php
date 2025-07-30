<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API
 * del casino Fazi, incluyendo autenticación, balance, apuestas, ganancias, reembolsos
 * y otras operaciones relacionadas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2017-10-18
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Variable que habilita la conexión global ["enabledConnectionGlobal"].
 * @var mixed $_ENV     Variable que habilita el tiempo de espera para bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var mixed $_ENV     Variable que habilita el modo de depuración['debug'].
 * @var mixed $URI      Contiene la URI de la solicitud actual.
 * @var mixed $body     Contiene el cuerpo de la solicitud HTTP.
 * @var mixed $data     Contiene los datos decodificados de la solicitud.
 * @var mixed $response Almacena la respuesta generada por las operaciones de la API.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Fazi;

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$URI = $_SERVER['REQUEST_URI'];

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$body = file_get_contents('php://input');
if ($body != "") {
    $body = str_replace("&", '","', $body);
    $body = str_replace("=", '":"', $body);
    $data = json_decode($body);
}

header('Content-Type: application/json');

if (true) {
    if (strpos($URI, "api/authenticate") !== false) {
        $sign = $data->sign;
        $token = $data->token;

        /* Procesamos */
        $Fazi = new Fazi($token, $sign);
        $response = $Fazi->Auth();
    }

    if (strpos($URI, "api/balance") !== false) {
        $sign = $data->sign;
        $token = $data->token;

        /* Procesamos */
        $Fazi = new Fazi($token, $sign);
        $response = $Fazi->getBalance();
    }

    if (strpos($URI, "api/win") !== false) {
        $sign = $data->sign;
        $token = $data->token;
        $currency = $data->currency;
        $creditAmount = ($data->amount) / 100;
        $date = $data->date;
        $game = str_replace("%40", "@", $data->game);

        $player = $data->player;
        $roundId = $data->gameId;
        $transactionId = $data->id;

        $datos = $data;

        /* Procesamos */
        $Fazi = new Fazi($token, $sign);
        $response = $Fazi->Credit($game, $creditAmount, $roundId, $transactionId, json_encode($datos));
    }

    if (strpos($URI, "api/www2") !== false) {
        print_r($body);
        print_r($data);
        print_r(json_encode($data));
        exit();

        $sign = $data->sign;
        $token = $data->token;

        $currency = $data->currency;
        $creditAmount = ($data->amount) / 100;
        $date = $data->date;
        $game = str_replace("%40", "@", $data->game);

        $player = $data->player;
        $roundId = $data->gameId;
        $transactionId = $data->id;

        $roundId = $data->betTransactionId;
        $datos = $data;

        /* Procesamos */
        $Fazi = new Fazi($token, $sign);
        $response = $Fazi->Credit($game, $creditAmount, $roundId, $transactionId, json_encode($datos));
    }

    if (strpos($URI, "api/bet") !== false) {
        $sign = $data->sign;
        $token = $data->token;
        $currency = $data->currency;
        $debitAmount = ($data->amount) / 100;
        $date = $data->date;
        $game = str_replace("%40", "@", $data->game);

        $player = $data->player;
        $roundId = $data->gameId;
        $transactionId = $data->id;

        $datos = $data;

        /* Procesamos */
        $Fazi = new Fazi($token, $sign);
        $response = $Fazi->Debit($game, $debitAmount, $roundId, $transactionId, json_encode($datos));
    }

    if (strpos($URI, "api/refund") !== false) {
        $sign = $data->sign;
        $token = $data->token;

        $currency = $data->currency;
        $rollbackAmount = ($data->amount) / 100;
        $date = $data->date;
        $game = str_replace("%40", "@", $data->game);
        $player = $data->player;
        $roundId = $data->gameId;
        $transactionId = $data->id;

        $transactionId = $data->betTransactionId;
        $datos = $data;

        /* Procesamos */
        $Fazi = new Fazi($token, $sign);
        $response = $Fazi->Rollback($game, $rollbackAmount, $roundId, $transactionId, $player, json_encode($datos));
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
        $Fazi = new Fazi($operatorId, $token, $uid);
        $response = $Fazi->Rollback($gameId, $uid, $betTypeID, $currency, $rollbackAmount, $serverId, $roundId, $transactionId, $seatId, $hash);
    }
}

print_r($response);
