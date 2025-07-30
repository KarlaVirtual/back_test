<?php

/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la integración
 * de la API del casino 'AVIATRIX'. Incluye operaciones como autenticación, apuestas, ganancias y promociones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    ninguna
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST         Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV             Variable superglobal que contiene variables de entorno configuradas.
 * @var mixed $URI              URI de la solicitud actual.
 * @var mixed $x_auth_signature Firma de autenticación enviada en los encabezados de la solicitud.
 * @var mixed $log              Variable que almacena el registro de la solicitud y la respuesta.
 * @var mixed $body             Cuerpo de la solicitud en formato JSON.
 * @var mixed $data             Datos decodificados del cuerpo de la solicitud.
 * @var mixed $response         Respuesta generada por las operaciones realizadas.
 */

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Aviatrix;

$_ENV["enabledConnectionGlobal"] = 1;

$URI = $_SERVER['REQUEST_URI'];

$x_auth_signature = $_SERVER['HTTP_X_AUTH_SIGNATURE'];

$log = "\r\n" . "-------------Request------------" . "\r\n";
$log = $log . ($URI) . $x_auth_signature . "\r\n";
$log = $log . (http_build_query($_REQUEST));
$log = $log . trim(file_get_contents('php://input'));
$body = file_get_contents('php://input');
$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);

if (true) {
    if (strpos($URI, "playerInfo") !== false) {
        $cid = $data->cid;
        $token = $data->sessionToken;
        $timestamp = $data->timestamp;

        /* Procesamos */
        $Aviatrix = new Aviatrix($token);
        $response = ($Aviatrix->Auth());
    }

    if (strpos($URI, "bet") !== false) {
        $token = $data->sessionToken;
        $usuarioId = $data->playerId;

        $betId = $data->betId;
        $cid = $data->cid;
        $Game = $data->productId;
        $transactionId = $data->txId;
        $roundId = $data->roundId;
        $Amount = $data->amount / 100;
        $CurrencyCode = $data->currency;
        $timestamp = $data->timestamp;

        $freespin = false;

        $datos = $data;

        /* Procesamos */
        $Aviatrix = new Aviatrix($token, $usuarioId);
        $response = $Aviatrix->Debit($Game, $Amount, $roundId . $betId, $transactionId, json_encode($datos), $freespin);
    }

    if (strpos($URI, "win") !== false) {
        $usuarioId = $data->playerId;
        $token = $data->sessionToken;

        $betId = $data->betId;
        $cid = $data->cid;
        $Game = $data->productId;
        $transactionId = $data->txId;
        $roundId = $data->roundId;
        $Amount = $data->amount / 100;
        $CurrencyCode = $data->currency;
        $operation = $data->operation;
        $timestamp = $data->timestamp;
        $bonusId = $data->bonusId;
        $roundClosed = $data->roundClosed;

        $datos = $data;

        /* Procesamos */
        $Aviatrix = new Aviatrix($token, $usuarioId);

        if ($operation == 'SettleBet') {
            $response = $Aviatrix->Credit($Game, $Amount, $roundId . $betId, $transactionId, json_encode($datos), $roundClosed);
        } elseif ($operation == 'CancelBet') {
            $response = $Aviatrix->Rollback($Game, $Amount, $roundId . $betId, $transactionId, json_encode($datos));
        }
    }

    if (strpos($URI, "promoWin")) {
        $usuarioId = $data->playerId;
        $token = $data->sessionToken;

        $cid = $data->cid;
        $transactionId = $data->txId;
        $RoundId = $data->txId;
        $Amount = $data->amount / 100;
        $CurrencyCode = $data->currency;
        $roundClosed = false;

        $datos = $data;
        $freespin = true;
        /* Procesamos */
        $Aviatrix = new Aviatrix($token, $usuarioId);
        $response = $Aviatrix->Debit('nft-aviatrix', 0, $RoundId, "D_" . $transactionId, json_encode($datos), $freespin);
        $response = $Aviatrix->Credit('nft-aviatrix', $Amount, $RoundId, 'C_' . $transactionId, json_encode($datos), $roundClosed, $freespin);
    }
} else {
    $array = array("message" => 'Invalid authentication signature');
    $response = json_encode($array);
}

$log = $log . "\r\n" . "-------------Response------------" . "\r\n";
$log = $log . ($response);
print_r($response);
