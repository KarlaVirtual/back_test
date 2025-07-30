<?php

/**
 * Este archivo contiene un script para procesar solicitudes relacionadas con la API de casino 'endorphina'.
 * Maneja operaciones como autenticación, consulta de saldo, retiros, depósitos y reversión de transacciones.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2017-10-18
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed  $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var bool   $_ENV     Activa o desactiva el modo de depuración ['debug'].
 * @var int    $_ENV     ["enabledConnectionGlobal"] Indica si la conexión global está habilitada.
 * @var string $_ENV     ["ENABLEDSETLOCKWAITTIMEOUT"] Configuración para el tiempo de espera de bloqueo.
 * @var string $URI      URI de la solicitud actual.
 * @var string $body     Contenido del cuerpo de la solicitud.
 * @var object $data     Objeto que contiene los datos de la solicitud (convertido desde $_GET).
 * @var string $log      Variable utilizada para almacenar información de registro.
 * @var mixed  $response Respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Lambda;

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

if ( ! empty($_GET)) {
    $data = (object)$_GET; // Convertimos $_GET a un objeto para usarlo como $data
}

if (true) {
    if (strpos($URI, "Authenticate") !== false) {
        $Lambda = new Lambda($data->playerId, $data->token);
        // $signature = $Lambda->validateSignature($data);
        // $signResponse = json_decode($signature);

        //if ($signResponse->code == 450) {
            //$response = $signature;
        //} else {
            $response = $Lambda->Auth();
        //}
    }

    if (strpos($URI, "GetBalance") !== false) {
        $Lambda = new Lambda($data->playerId, $data->sessionId);
        // $signature = $Lambda->validateSignature($data);
        // $signResponse = json_decode($signature);

        // if ($signResponse->code == 450) {
        //     $response = $signature;
        // } else {
            $response = $Lambda->Balance();
        //}
    }

    if (strpos($URI, "Withdraw") !== false) {
        $Lambda = new Lambda($data->playerId, $data->sessionId);
        // $signature = $Lambda->validateSignature($data);
        // $signResponse = json_decode($signature);

        // if ($signResponse->code == 450) {
        //     $response = $signature;
        // } else {
            if ($data->freeSpinId != "") {
                $isFreeSpin = true;
            } else {
                $isFreeSpin = false;
            }

            $response = $Lambda->Debit($data->gameTypeId, $data->amount, $data->roundId, $data->transactionUniqueId, $data, $isFreeSpin, true);
        //}
    }

    if (strpos($URI, "Deposit") !== false) {
        $Lambda = new Lambda($data->playerId, $data->sessionId);

        // $signature = $Lambda->validateSignature($data);
        // $signResponse = json_decode($signature);

        // if ($signResponse->code == 450) {
        //     $response = $signature;
        // } else {
            if ($data->freeSpinId != "") {
                $isFreeSpin = true;
                $response = $Lambda->Debit($data->gameTypeId, 0, $data->roundId, $data->transactionUniqueId, $data, true, false);
            } else {
                $isFreeSpin = false;
            }
            $response = $Lambda->Credit($data->gameTypeId, $data->amount, $data->roundId, $data->transactionUniqueId . "_C", $data, $isFreeSpin, false);
        //}
    }

    if (strpos($URI, "Rollback") !== false) {
        $Lambda = new Lambda($data->playerId, $data->sessionId);
        // $signature = $Lambda->validateSignature($data);
        // $signResponse = json_decode($signature);

        // if ($signResponse->code == 450) {
        //     $response = $signature;
        // } else {
            $response = $Lambda->Rollback($data->amount, $data->roundId, $data->canceledTransactionUniqueId, false, $data->gameTypeId);
        // }
    }
}

print_r($response);
