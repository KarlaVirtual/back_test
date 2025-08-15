<?php

/**
 * .PHP de la API del casino 'G21viral'.
 * Este archivo actúa como procesamiento y respuesta de solicitudes entrantes
 * como autenticación, débitos, créditos y rollbacks desde la plataforma de G21viral.
 *
 * @category    Documentación
 * @package     AutoDoc
 * @author      sebastian.rico@virtualsoft.tech
 * @version     1.0.0
 * @since       23/04/2025
 */


require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\G7777gaming;
use Backend\integrations\casino\G21viral;

header('Content-type: application/json');

/**
 * Habilita el modo de depuración si se recibe un parámetro específico.
 */
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

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV['enabledSlowIntegrations'] = true;

/**
 * Procesa las solicitudes entrantes basadas en la URI.
 */
if (true) {
    /**
     * Maneja la solicitud para obtener el balance de un jugador.
     */
    if (strpos($URI, "players/balance") !== false) {
        $token = $data->token;
        $gameId = $data->gameId;
        $timestamp = $data->timestamp;
        $playerId = $data->playerId;

        /* Procesamos */
        $G21viral = new G21viral($gameId, $token, $playerId);
        $response = $G21viral->Balance();
    } /**
     * Maneja las solicitudes relacionadas con transacciones de jugadores.
     */
    elseif (strpos($URI, "players/transactions") !== false) {
        $transactionType = $data->transactionType;
        $betType = $data->betType;
        $gameRoundStatus = $data->gameRoundStatus;
        $gameId = $data->gameId;
        $timestamp = $data->timestamp;
        $playerId = $data->playerId;
        $token = $data->token;
        $amount = $data->amount;
        $currency = $data->currency;
        $providerGameRoundId = $data->providerGameRoundId;
        $providerTransactionId = $data->providerTransactionId;

        /* Procesamos */
        $G21viral = new G21viral($gameId, $token, $playerId);


        /**
         * Procesa una transacción de débito, Credito y rollback.
         */
        if ($transactionType == 'Debit') {
            $response = $G21viral->Debit($gameId, $currency, $amount, $providerGameRoundId, $providerTransactionId, $data);
        } elseif ($transactionType == 'Credit') {
            if ($gameRoundStatus == 'Started') {
                $isEndRound = true;
            } else {
                $isEndRound = false;
            }
            $response = $G21viral->Credit($gameId, $currency, $amount, $providerGameRoundId, $providerTransactionId, $isEndRound, $data);
        } elseif ($transactionType == 'Reversal') {
            $response = $G21viral->Rollback($currency, $amount, $providerGameRoundId, $providerTransactionId, $data);
        }
    }



    /**
     * Devuelve la respuesta en formato JSON.
     */
    echo $response;
}
