<?php

/**
 * Este archivo contiene la API de integración con el casino 'Onlyplay'.
 * Proporciona endpoints para manejar operaciones como autenticación,
 * finalización de apuestas gratuitas, apuestas, ganancias y cancelaciones.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed   $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var boolean $_ENV     Indica si el modo de depuración está habilitado ['debug'].
 * @var integer $_ENV     Variable que habilita la conexión global ["enabledConnectionGlobal"].
 * @var string  $_ENV     Configuración para habilitar el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var string  $URI      URI de la solicitud actual.
 * @var string  $body     Cuerpo de la solicitud en formato JSON.
 * @var object  $data     Datos decodificados del cuerpo de la solicitud.
 * @var array   $headers  Encabezados de la solicitud.
 * @var string  $log      Variable para almacenar información de registro.
 * @var mixed   $response Respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Onlyplay;

header('Content-Type: application/json');

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

$headers = getallheaders();

if (true) {
    if (strpos($URI, "info") !== false) {
        $user_id = $data->user_id;
        $session_id = $data->session_id;
        $token = $data->token;
        $sign = $data->sign;

        $Onlyplay = new Onlyplay($user_id, $session_id);
        $response = $Onlyplay->Auth();
    }

    if (strpos($URI, "finish_freebets") !== false) {
        $freebet_id = $data->freebet_id;
        $session_id = $data->session_id;
        $token = $data->token;
        $total_win = $data->total_win;
        $sign = $data->sign;

        $Onlyplay = new Onlyplay("", $session_id);

        $response = $Onlyplay->Debit(0, 0, $freebet_id, $token, $data, "", false);
        $response = $Onlyplay->Credit(0, $total_win / 100, $freebet_id, $token . "_FST", $data, true, false);
    }

    if (strpos($URI, "bet") !== false) {
        $freebet_id = $data->freebet_id;
        $user_id = $data->user_id;
        $session_id = $data->session_id;
        $round_id = $data->round_id;
        $tx_id = $data->tx_id;
        $amount = $data->amount;
        $game_bundle = $data->game_bundle;
        $token = $data->token;
        $sign = $data->sign;

        $Onlyplay = new Onlyplay($user_id, $session_id);
        $response = $Onlyplay->Debit($game_bundle, $amount / 100, $round_id, $tx_id, $data, "", false);
    }

    if (strpos($URI, "win") !== false) {
        $user_id = $data->user_id;
        $session_id = $data->session_id;
        $round_id = $data->round_id;
        $tx_id = $data->tx_id;
        $ref_tx_id = $data->ref_tx_id;
        $amount = $data->amount;
        $round_closed = $data->round_closed;
        $game_bundle = $data->game_bundle;
        $token = $data->token;
        $sign = $data->sign;

        $Onlyplay = new Onlyplay($user_id, $session_id);
        $response = $Onlyplay->Credit($game_bundle, $amount / 100, $round_id, $tx_id, $data, false, $round_closed);
    }


    if (strpos($URI, "cancel") !== false) {
        $user_id = $data->user_id;
        $tx_id = $data->tx_id;
        $ref_tx_id = $data->ref_tx_id;
        $session_id = $data->session_id;
        $round_id = $data->round_id;
        $game_bundle = $data->game_bundle;
        $token = $data->token;
        $sign = $data->sign;

        $Onlyplay = new Onlyplay($user_id, $session_id);
        $response = $Onlyplay->Rollback($round_id, $ref_tx_id, $game_bundle);
    }
}

print_r($response);
