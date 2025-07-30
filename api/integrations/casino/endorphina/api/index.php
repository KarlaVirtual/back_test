<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API
 * del casino 'endorphina', incluyendo operaciones como autenticación, balance, apuestas,
 * ganancias, bonificaciones, reembolsos y pruebas de rollback.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 * @access     público
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST          Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV              Variable que habilita la conexión global ["enabledConnectionGlobal"].
 * @var mixed $_ENV              Variable que habilita el tiempo de espera para el bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"] .
 * @var mixed $_ENV              Variable que habilita el modo de depuración ['debug'].
 * @var mixed $URI               Contiene la URI de la solicitud actual.
 * @var mixed $body              Contiene el cuerpo de la solicitud HTTP.
 * @var mixed $contentType       Indica el tipo de contenido de la solicitud HTTP.
 * @var mixed $data              Almacena los datos procesados de la solicitud.
 * @var mixed $response          Almacena la respuesta generada por las operaciones de la API.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Endorphina;

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$URI = $_SERVER['REQUEST_URI'];

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$body = file_get_contents('php://input');
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if ($body != "") {
    if (strpos($contentType, 'application/json') !== false) {
        $data = json_decode($body, true);
    } else {
        $formData = [];
        parse_str($body, $formData);
        $data = json_encode($formData, JSON_PRETTY_PRINT);
        $data = json_decode($data);
    }
} else {
    $data = json_encode($_REQUEST);
    $data = json_decode($data);
}

header('Content-Type: application/json');

if (true) {
    if (strpos($URI, "api/session") !== false) {
        $nodeId = $data->nodeId;
        $token = $data->token;
        $sign = $data->sign;

        /* Procesamos */
        $Endorphina = new Endorphina($token, $sign);
        $response = $Endorphina->Auth();
    }

    if (strpos($URI, "api/balance") !== false) {
        $nodeId = $data->nodeId;
        $token = $data->token;
        $game = $data->game;
        $player = $data->player;
        $currency = $data->currency;
        $sign = $data->sign;

        /* Procesamos */
        $Endorphina = new Endorphina($token, $sign);
        $response = $Endorphina->getBalance();
    }

    if (strpos($URI, "api/win") !== false) {
        $token = $data->token;
        $roundId = $data->gameId;
        $creditAmount = ($data->amount) / 1000;
        $date = $data->date;
        $game = str_replace("%40", "@", $data->game);
        $player = $data->player;
        $currency = $data->currency;
        $transactionId = $data->id;
        $roundId = $data->betTransactionId;
        $betSessionId = $data->betSessionId;
        $count = $data->count;
        $nodeId = $data->nodeId;
        $sign = $data->sign;
        $datos = $data;

        /* Procesamos */
        $Endorphina = new Endorphina($token, $sign);
        $response = $Endorphina->Credit($game, $creditAmount, $roundId, $transactionId, json_encode($datos));
    }

    if (strpos($URI, "api/bonus") !== false) {
        $sign = $data->sign;
        $token = $data->token;

        $currency = $data->currency;
        $creditAmount = ($data->bonusWin) / 1000;
        $date = $data->date;
        $game = str_replace("%40", "@", $data->game);

        $player = $data->player;
        $player = str_replace("Usuario", "", $player);
        $roundId = $data->id;
        $state = $data->state;
        $transactionId = $data->id;

        $datos = $data;
        $isfreeSpin = true;

        /* Procesamos */
        $Endorphina = new Endorphina($token, $sign, $player);
        if ($state == "COMPLETED") {
            $response = $Endorphina->Debit($game, 0, $roundId, $transactionId . '_DFS', json_encode($datos), $isfreeSpin);
            $response = $Endorphina->Credit($game, $creditAmount, $roundId, $transactionId . '_CFS', json_encode($datos), $isfreeSpin);
        } else {
            $response = $Endorphina->getBalance();
        }
    }

    if (strpos($URI, "api/www2") !== false) {
        $sign = $data->sign;
        $token = $data->token;
        $currency = $data->currency;
        $creditAmount = ($data->amount) / 1000;
        $date = $data->date;
        $game = str_replace("%40", "@", $data->game);

        $player = $data->player;
        $roundId = $data->gameId;
        $transactionId = $data->id;
        $roundId = $data->betTransactionId;

        $datos = $data;

        /* Procesamos */
        $Endorphina = new Endorphina($token, $sign);
        $response = $Endorphina->Credit($game, $creditAmount, $roundId, $transactionId, json_encode($datos));
    }

    if (strpos($URI, "api/bet") !== false) {
        $transactionId = $data->id;
        $token = $data->token;
        $roundId = $data->gameId;
        $debitAmount = ($data->amount) / 1000;
        $date = $data->date;
        $game = str_replace("%40", "@", $data->game);
        $player = $data->player;
        $currency = $data->currency;
        $sign = $data->sign;
        $roundId = $data->id;

        $datos = $data;

        /* Procesamos */
        $Endorphina = new Endorphina($token, $sign);
        $response = $Endorphina->Debit($game, $debitAmount, $roundId, $transactionId, json_encode($datos));
    }

    if (strpos($URI, "api/refund") !== false) {
        $nodeId = $data->nodeId;
        $sign = $data->sign;
        $token = $data->token;
        $currency = $data->currency;
        $rollbackAmount = ($data->amount) / 1000;
        $date = $data->date;
        $game = str_replace("%40", "@", $data->game);
        $player = $data->player;
        $roundId = $data->gameId;
        $transactionId = $data->id;
        $roundId = $data->id;

        $datos = $data;

        /* Procesamos */
        $Endorphina = new Endorphina($token, $sign);
        $response = $Endorphina->Rollback($game, $rollbackAmount, $roundId, $transactionId, $player, json_encode($datos));
    }
}

print_r($response);
