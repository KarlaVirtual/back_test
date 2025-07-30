<?php

/**
 * Este archivo contiene la implementación de la API para el casino 'EXPANSE'.
 * Procesa solicitudes relacionadas con autenticación, balance, transacciones de débito, crédito y cancelación.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed  $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed  $_ENV                     Variable superglobal que contiene información del entorno de ejecución.
 * @var string $URI                      URI de la solicitud actual.
 * @var string $log                      Variable utilizada para almacenar información de registro.
 * @var string $body                     Cuerpo de la solicitud HTTP recibido.
 * @var object $data                     Objeto decodificado del cuerpo de la solicitud en formato JSON.
 * @var mixed  $response                 Respuesta generada por las operaciones realizadas.
 * @var object $ConfigurationEnvironment Objeto para manejar la configuración del entorno.
 * @var object $Expanse                  Objeto que representa la integración con el casino 'EXPANSE'.
 */

$_ENV["enabledConnectionGlobal"] = 1;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Expanse;

$URI = $_SERVER['REQUEST_URI'];


$log = "\r\n" . "-------------Request------------" . "\r\n";
$log = $log . (http_build_query($_REQUEST));
$log = $log . trim(file_get_contents('php://input'));

$body = file_get_contents('php://input');
$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);

if ($body != "") {
    header('Content-type: application/json');
    $data = json_decode($body);
}

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $URI;
$log = $log . trim(file_get_contents('php://input'));

$log = time();

$ConfigurationEnvironment = new ConfigurationEnvironment();

if (strpos($URI, "authenticate") !== false) {
    $token = $data->token;
    $market_id = $data->market_id;

    /* Procesamos */
    $Expanse = new Expanse($token, "");
    $response = ($Expanse->Auth());
}

if (strpos($URI, "balance") !== false) {
    $token = $data->token;
    $market_id = $data->market_id;
    $player_id = $data->player_id;

    /* Procesamos */
    $Expanse = new Expanse($token, $player_id);
    $response = ($Expanse->getBalance());
}

if (strpos($URI, "transaction/debit") !== false) {
    $token = $data->token;
    $market_id = $data->market_id;
    $player_id = $data->player_id;
    $amount = $data->amount;
    $currency = $data->currency;
    $transaction_type = $data->description->transaction_type;
    $game_code = $data->description->game_code;
    $game_name = $data->description->game_name;
    $transactionId = $data->provider_transaction_id;
    $round = $data->bet_id;
    $bet_status = $data->bet_status;

    /* Procesamos */
    $Expanse = new Expanse($token, $player_id);

    $IsFreeSpin = false;
    if ($data->description->transaction_type == "FREE_SPIN_BONUS") {
        $IsFreeSpin = true;
        $amount = 0;
        $round = 'FS_' . $transactionId;
    }

    $response = ($Expanse->Debit($game_code, $amount, $round, $transactionId, $data, $IsFreeSpin));

    if ($IsFreeSpin) {
        $amountCredit = $data->amount;
        $transactionId = 'FSC_' . $transactionId;
        $response = $Expanse->credit($game_code, $amountCredit, $round, $transactionId, $data);
    }
}

if (strpos($URI, "transaction/credit") !== false) {
    $token = $data->token;
    $market_id = $data->market_id;
    $player_id = $data->player_id;
    $amount = $data->amount;
    $currency = $data->currency;
    $transaction_type = $data->description->transaction_type;
    $game = $data->description->game_code;
    $game_name = $data->description->game_name;
    $transactionId = $data->provider_transaction_id;
    $related_provider_transaction_id = $data->related_provider_transaction_id;
    $round = $data->bet_id;
    $bet_status = $data->bet_status;

    $IsFreeSpin = false;

    /* Procesamos */
    $Expanse = new Expanse($token, '');

    if ($data->description->transaction_type == "FREE_SPIN_BONUS") {
        $IsFreeSpin = true;
        $amountD = 0;
        $round = 'FS_' . $transactionId;
        $response = ($Expanse->Debit($game, $amountD, $round, $transactionId, $data, $IsFreeSpin));
        $transactionId = 'FSC_' . $transactionId;
    }

    $turnover = $data->turnover;
    $response = $Expanse->credit($game, $amount, $round, $transactionId, $data);
}

if (strpos($URI, "transaction/cancel") !== false) {
    $token = $data->token;
    $market_id = $data->market_id;
    $player_id = $data->player_id;
    $amount = $data->amount;
    $currency = $data->currency;
    $transaction = $data->provider_transaction_id;
    $transactionTipe = $data->transaction;

    /* Procesamos */
    $Expanse = new Expanse("", $player_id);
    $response = ($Expanse->Rollback($transaction, $transaction . 'R', $player_id, $data));
}

$log = $log . "\r\n" . "-------------Response------------" . "\r\n";
$log = $log . ($response);

print_r($response);


















