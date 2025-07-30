<?php


/**
 * Este archivo contiene la implementación de la API de integración con el casino 'Airdice'.
 * Proporciona métodos para manejar operaciones como autenticación, consulta de saldo,
 * colocación de apuestas, premios y cancelación de transacciones.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Sebastian Rico <it@virtualsoft.tech>
 * @version    Ninguna
 * @since      2025-05-09
 * @access     Público
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\AirDice;

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}
$_ENV["enabledConnectionGlobal"] = 1;

$URI = $_SERVER['REQUEST_URI'];
$body = trim(file_get_contents('php://input'));
$method = "";
$date = date("Y-m-d H:i:s");

if ($body != "") {
    $data = json_decode($body);
}

$URI = explode('/', $URI);
$URI = $URI[count($URI) - 1];
$URI = explode('?', $URI);
$URI = $URI[0];

$userId = '';
$token = $data->token;
if (preg_match('/P(\d+)P/', $token, $coincidencias)) {
    $userId = $coincidencias[1];
}

if ($data->method == 'GetAccountDetails') {

    $UserName = $data->auth->login;
    $Password = $data->auth->password;
    $token = $data->token;
    $game_ref = $data->game_ref;

    $AirDice = new AirDice($token, $userId, $game_ref);
    $response = $AirDice->Auth($game_ref, $UserName, $Password);
} elseif ($data->method == 'GetBalance') {

    $UserName = $data->auth->login;
    $Password = $data->auth->password;
    $token = $data->token;
    $game_ref = $data->game_ref;
    $customer = $data->customer;
    $player_id = $data->player_id;

    $AirDice = new AirDice($token, $userId, $game_ref);
    $response = $AirDice->balance($UserName, $Password);
} elseif ($data->method == "PlaceBet") {

    $UserName = $data->auth->login;
    $Password = $data->auth->password;
    $token = $data->token;
    $GameId = $data->game_ref;
    $Custumer = $data->customer;
    $PlayerId = $data->player_id;
    $TransferId = $data->trans_id;

    $RoundId = $data->round_id;
    $Amount = $data->amount;
    $Amount = $Amount / 100;
    $Currency = $data->currency;
    $CurrencyBase = $data->currency_base;
    $BetType = $data->bet_type;
    $freespin = $data->Freespin;

    $freespin = false;
    if ($BetType != "normal") {
        $freespin = true;
        $Amount = 0;
    }

    $AirDice = new AirDice($token, $PlayerId, $GameId);
    $response = ($AirDice->Debit($GameId, $Amount, $RoundId, $TransferId, json_encode($data), $freespin, $UserName, $Password));
} elseif ($data->method == "AwardWinnings") {

    $UserName = $data->auth->login;
    $Password = $data->auth->password;
    $token = $data->token;
    $GameId = $data->game_ref;
    $Custumer = $data->customer;
    $PlayerId = $data->player_id;
    $timestamp = $data->timestamp;
    $supplier = $data->supplier;
    $TransferId = $data->trans_id;
    $RoundId = $data->round_id;
    $Amount = $data->amount;
    $Amount = $Amount / 100;
    $Currency = $data->currency;
    $CurrencyBase = $data->currency_base;
    $BetType = $data->bet_type;
    $Gamestatus = $data->gamestatus;
    $freespin = $data->Freespin;

    $EndRound = false;
    if ($Gamestatus == 'completed') {
        $EndRound = true;
    }

    $AirDice = new AirDice($token, $PlayerId, $GameId);

    $freespin = false;
    if ($BetType != "normal" && $BetType != "internal_free_credit" && $BetType != "supplier_free_credit") {
        $freespin = true;
    }

    if ($BetType == "JACKPOT_END") {
        $Amount = 0;
        $freespin = true;
        $response = ($AirDice->Debit($GameId, 0, $RoundId, "JP" . $TransferId, json_encode($data), $freespin, $UserName, $Password));
    }

    $response = $AirDice->Credit($GameId, $Amount, $RoundId, $TransferId, json_encode($data), $freespin, $UserName, $Password, $EndRound);
} elseif ($data->method == "CancelTransaction") {

    $UserName = $data->auth->login;
    $Password = $data->auth->password;
    $token = $data->token;
    $GameId = $data->game_ref;
    $Custumer = $data->customer;
    $timestamp = $data->timestamp;
    $supplier = $data->supplier;
    $PlayerId = $data->player_id;
    $TransferId = $data->trans_id;
    $RoundId = $data->round_id;
    $Amount = $data->amount;
    $Amount = $Amount / 100;
    $Currency = $data->currency;
    $CurrencyBase = $data->currency_base;
    $BetType = $data->bet_type;
    $gamestatus = $data->gamestatus;
    $freespin = $data->Freespin;

    $gameRoundEnd = false;
    $freespin = false;

    $AirDice = new AirDice($token, $PlayerId, $GameId);
    $response = $AirDice->Rollback($Amount, $RoundId, $TransferId, json_encode($data), $gameRoundEnd, $UserName, $Password);
}

print_r($response);
