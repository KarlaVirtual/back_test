<?php

/**
 * Este archivo contiene un script para manejar las solicitudes de la API de casino
 * relacionadas con la integración de 7777gaming. Proporciona funcionalidades como
 * autenticación, consulta de saldo, colocación de apuestas, liquidación de apuestas,
 * eventos de ganancia, cancelación de apuestas, y notificaciones.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo
 * @version    1.0.0
 * @since      2025-05-12
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\G7777gaming;

header('Content-type: application/json');

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

if ($_ENV['debug']) {
    print_r($data);
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV['enabledSlowIntegrations'] = true;

$requestOrder = "";
ksort($_REQUEST);
$cont = 0;

if (true) {
    if (strpos($URI, "auth") !== false) {
        $signature = $data->signature;
        $app_data = $data->app_data;
        $app_ = base64_decode($app_data);
        $token = base64_decode($app_data);
        $token = json_decode($token);
        $token = $token->token;

        /* Procesamos */
        $G7777gaming = new G7777gaming($token, $signature);
        $respuesta = $G7777gaming->Auth($app_);
    } elseif (strpos($URI, "get_balance") !== false) {
        $signature = $data->signature;
        $app_data = $data->app_data;
        $app_ = base64_decode($app_data);
        $token = base64_decode($app_data);
        $token = json_decode($token);
        $token = $token->token;

        /* Procesamos */
        $G7777gaming = new G7777gaming($token, $signature);
        $respuesta = $G7777gaming->getBalance($app_);
    } elseif (strpos($URI, "place_bet") !== false) {
        $signature = $data->signature;
        $app_data = $data->app_data;
        $data_ = base64_decode($app_data);
        $data_ = json_decode($data_);
        $token = $data_->token;
        $playerId = $data_->user_id;
        $gameId = $data_->game_id;
        $betAmount = $data_->bet_amount;
        $roundId = $data_->game_round_id . $playerId;
        $txId = $data_->transaction_id;
        $type = $data_->game_round_type;

        if ($type == 'CASH_BET') {
            $betAmount = $data_->bet_amount;
        } elseif ($type == 'FREE_BET') {
            $betAmount = 0;
        } else {
            $betAmount = $data_->bet_amount;
        }

        $G7777gaming = new G7777gaming($token, $signature, $playerId);
        $respuesta = $G7777gaming->Debit($gameId, $betAmount / 100, $roundId, $txId, json_encode($data_));
    } elseif (strpos($URI, "settle_bet") !== false) {
        $signature = $data->signature;
        $app_data = $data->app_data;

        $data_ = base64_decode($app_data);
        $data_ = json_decode($data_);
        $token = $data_->token;
        $playerId = $data_->user_id;
        $gameId = $data_->game_id;
        $winAmount = $data_->won_amount;
        $roundId = $data_->game_round_id . $playerId;
        $txId = $data_->transaction_id;

        $G7777gaming = new G7777gaming($token, $signature, $playerId);
        $respuesta = $G7777gaming->Credit($gameId, $winAmount / 100, $roundId, $txId, json_encode($data_), false, $type_ = 'settle_bet');
    } elseif (strpos($URI, "event_win") !== false) {
        $signature = $data->signature;
        $app_data = $data->app_data;
        $data_ = base64_decode($app_data);
        $data_ = json_decode($data_);
        $token = $data_->token;
        $playerId = $data_->user_id;
        $winAmount = $data_->won_amount;
        $roundId = $data_->game_round_id . $playerId;
        $txId = $data_->transaction_id;
        $type = $data_->game_round_type;

        $G7777gaming = new G7777gaming($token, $signature, $playerId);

        if ($type == 'BONUS_WIN') {
            $winAmount = 0;
            $isBonus = true;
            $roundId = $data_->game_session_id . $txId . $playerId;

            $respuesta = $G7777gaming->Debit('', 0, $roundId, $txId . "_D", json_encode($data_));
        } elseif ($type == 'TOURNAMENT_WIN') {
            $winAmount = $data_->won_amount;
        } elseif ($type == 'TOURNAMENT_WIN') {
            $winAmount = $data_->won_amount;
        } else {
            $winAmount = $data_->won_amount;
        }

        $respuesta = $G7777gaming->Credit('', $winAmount / 100, $roundId, $txId, json_encode($data_), $isBonus, $type_ = 'event_win');
    } elseif (strpos($URI, "cancel_bet") !== false) {
        $signature = $data->signature;
        $app_data = $data->app_data;
        $data_ = base64_decode($app_data);
        $data_ = json_decode($data_);
        $token = $data_->token;
        $playerId = $data_->user_id;
        $roundId = $data_->game_round_id . $playerId;
        $originalTxId = $data_->transaction_id;
        $txId = $data_->transaction_id;

        $G7777gaming = new G7777gaming($token, $signature, $playerId);
        $respuesta = $G7777gaming->Rollback("", $roundId, $originalTxId, $playerId, json_encode($data_), $txId);
    } elseif (strpos($URI, "keep_alive") !== false) {
        $signature = $data->signature;
        $app_data = $data->app_data;
        $app_ = base64_decode($app_data);
        $token = base64_decode($app_data);
        $token = json_decode($token);
        $token = $token->token;

        /* Procesamos */
        $G7777gaming = new G7777gaming($token, $signature);
        $respuesta = $G7777gaming->Alive($app_);
    } elseif (strpos($URI, "notify") !== false) {
        $signature = $data->signature;
        $app_data = $data->app_data;
        $data_ = base64_decode($app_data);
        $data_ = json_decode($data_);
        $token = $data_->token;
        $playerId = $data_->user_id;
        $gameId = $data_->game_id;
        $game_round_type = $data_->game_round_type;
        $amount = $data_->amount;
        $roundId = $data_->game_round_id . $playerId;
        $txId = $data_->transaction_id;

        /* Procesamos */
        $G7777gaming = new G7777gaming($token, $signature, $playerId);

        if ($game_round_type == 'BONUS_BET') {
            $isfreeSpin = true;
            $respuesta = $G7777gaming->Debit($gameId, 0, $roundId, $txId, json_encode($data_), $isfreeSpin, 'notify');
        } elseif ($game_round_type == 'BONUS_BET_SETTLE') {
            $isfreeSpin = true;
            $respuesta = $G7777gaming->Credit($gameId, $amount / 100, $roundId, $txId, json_encode($data_), $isfreeSpin, 'notify');
        } else {
            $respuesta = $G7777gaming->notify();
        }
    }

    echo json_decode(json_encode($respuesta));
}
