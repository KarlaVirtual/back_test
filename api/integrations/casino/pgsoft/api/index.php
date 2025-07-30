<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API de casino 'Pgsoft'
 * en modo confirmación. Realiza operaciones como verificación de sesión, obtención de saldo, transferencias
 * y ajustes relacionados con los jugadores y sus transacciones.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Davison Valencia <davison.valencia@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed   $_REQUEST        Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed   $_ENV            Variable superglobal que contiene variables de entorno.
 * @var mixed   $URI             Contiene la URI de la solicitud actual.
 * @var mixed   $body            Almacena el cuerpo de la solicitud en formato de texto.
 * @var mixed   $data            Contiene los datos procesados del cuerpo de la solicitud en formato JSON.
 * @var mixed   $query           Almacena los parámetros de consulta extraídos de la URI.
 * @var mixed   $trace_id        Identificador de rastreo para la solicitud actual.
 * @var mixed   $user            Nombre del jugador o parámetro personalizado.
 * @var mixed   $token           Token de sesión del jugador proporcionado por el operador.
 * @var mixed   $Pgsoft          Instancia de la clase Pgsoft para manejar operaciones específicas.
 * @var mixed   $resp            Respuesta generada por la autenticación de la solicitud.
 * @var mixed   $response        Respuesta final generada por las operaciones realizadas.
 * @var mixed   $gameId          Identificador del juego asociado a la solicitud.
 * @var mixed   $currency        Código de moneda utilizado en las transacciones.
 * @var mixed   $timeUp          Marca de tiempo asociada a la operación.
 * @var mixed   $debitAmount     Monto debitado en una transacción.
 * @var mixed   $winAmount       Monto ganado en una transacción.
 * @var mixed   $transfer_amount Monto total transferido en una operación.
 * @var mixed   $Calc            Resultado del cálculo entre el monto ganado y el debitado.
 * @var mixed   $roundId         Identificador de la ronda de juego.
 * @var mixed   $transactionId   Identificador de la transacción asociada.
 * @var mixed   $balance         Saldo actual del jugador.
 * @var mixed   $before          Saldo previo a una operación de ajuste.
 * @var boolean $noError         Indica si no se han producido errores en las operaciones.
 */

$_ENV["enabledConnectionGlobal"] = 1;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Pgsoft;

$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');
parse_str($body, $data);
$data = json_encode($data);

$url = parse_url($URI);
parse_str($url['query'], $query);
$trace_id = isset($query['trace_id']) ? $query['trace_id'] : null;

$data = json_decode($data);

$noError = true;

$user = $data->custom_parameter;
if ($user == "") {
    $user = $data->player_name;
}
$token = $data->operator_player_session;

$Pgsoft = new Pgsoft($user, $token, '');
$resp = $Pgsoft->autchSign($data->secret_key, $data->operator_token);
$resp = json_decode($resp);

if ($resp->error->code !== 0) {
    $noError = false;
}

if ($noError) {
    if (strpos($URI, "VerifySession") !== false) {
        $user = $data->custom_parameter;
        $token = $data->operator_player_session;
        $trace_id = $trace_id;

        /* Procesamos */
        $Pgsoft = new Pgsoft($user, $token, $trace_id);
        $response = $Pgsoft->Auth();
    }

    if (strpos($URI, "Get") !== false) {
        $user = $data->player_name;
        $token = $data->operator_player_session;
        $trace_id = $trace_id;
        $gameId = $data->game_id;

        /* Procesamos */
        $Pgsoft = new Pgsoft($user, $token, $trace_id);
        $response = $Pgsoft->Balance($gameId);
    }

    if (strpos($URI, "TransferInOut") !== false) {
        $user = $data->player_name;
        $token = $data->operator_player_session;
        $trace_id = $trace_id;
        $gameId = $data->game_id;
        $currency = $data->currency_code;
        $timeUp = $data->updated_time;
        $wallet_type = $data->wallet_type;
        $is_end_round = $data->is_end_round;

        $debitAmount = $data->bet_amount;
        $winAmount = $data->win_amount;
        $transfer_amount = $data->transfer_amount;
        $real_transfer_amount = $data->real_transfer_amount;
        $Calc = number_format($winAmount - $debitAmount, 2);

        $roundId = $data->bet_id;
        $transactionId = $data->transaction_id;

        $gameRoundEnd = false;
        if ($is_end_round == "True") {
            $gameRoundEnd = true;
        }

        $isFreeSpin = false;
        if ($wallet_type == "G") {
            $isFreeSpin = true;
        }

        /* Procesamos */
        $Pgsoft = new Pgsoft($user, $token, $trace_id);
        if (abs($transfer_amount) == abs($real_transfer_amount)) {
            if (abs($transfer_amount) == abs($Calc)) {

                $response = $Pgsoft->Debit($gameId, $debitAmount, $roundId, $transactionId, $data, false, $currency, $timeUp, 0, 0, '', $real_transfer_amount, $isFreeSpin);
                $respDebit = json_decode($response);

                if ($respDebit->error == null) {
                    $response = $Pgsoft->Credit($gameId, $winAmount, $roundId, 'C_' . $transactionId, $data, $gameRoundEnd, $currency, $timeUp, false, '', '', '', $real_transfer_amount, $isFreeSpin);
                }
            } else {
                $response = $Pgsoft->convertError('3073', 'Invalid multiplier');
            }
        } else {
            $response = $Pgsoft->convertError('3107', 'Invalid real transfer amount');
        }
    }


    if (strpos($URI, "Adjustment") !== false) {
        $user = $data->player_name;
        $token = $data->operator_player_session;
        $trace_id = $trace_id;

        $gameId = '00_PGS';
        $currency = $data->currency_code;
        $timeUp = $data->adjustment_time;

        if ($data->transfer_amount < 0) {
            $debitAmount = abs($data->transfer_amount);
            $winAmount = 0;
        } else {
            $debitAmount = 0;
            $winAmount = $data->transfer_amount;
        }

        $amount = $data->transfer_amount;
        $transfer_amount = $data->transfer_amount;
        $real_transfer_amount = $data->real_transfer_amount;

        $roundId = $data->adjustment_id;
        $transactionId = $data->adjustment_transaction_id;

        /* Procesamos */
        $Pgsoft = new Pgsoft($user, $token, $trace_id);

        $response = $Pgsoft->Balance($gameId);
        $balanceD = json_decode($response);

        if (abs($transfer_amount) == abs($real_transfer_amount)) {
            if ($balanceD->error == null) {
                $before = $balanceD->data->balance_amount;

                $response = $Pgsoft->Debit($gameId, $debitAmount, $roundId, $transactionId, $data, false, $currency, $timeUp, $amount, $before, 'Adjustment', $real_transfer_amount);
                $respDebit = json_decode($response);
                if ($respDebit->error == null) {
                    $response =  $Pgsoft->Credit($gameId, $winAmount, $roundId, 'A_' . $transactionId, $data, false, $currency, $timeUp, true, $amount, $before, 'Adjustment', $real_transfer_amount);
                }
            }
        } else {
            $response = $Pgsoft->convertError('3107', 'Invalid real transfer amount');
        }
    }
} else {

    $response = json_encode($resp, JSON_PRESERVE_ZERO_FRACTION);
}

print_r($response);
