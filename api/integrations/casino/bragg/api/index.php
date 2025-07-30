<?php

/**
 * Este archivo contiene la implementación de la API para la integración con el casino 'BRAGG'.
 * Proporciona endpoints para autenticación, consulta de saldo y manejo de transacciones de juego.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Bragg;

header('Content-type: application/json');
$URI = $_SERVER['REQUEST_URI'];

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($URI);
$log = $log . trim(file_get_contents('php://input'));

// Obtenemos lo enviado en el body
$body = file_get_contents('php://input');
// Convertimos el json en objeto
$data = json_decode($body);

// Validamos si la solicitud es de autenticación
if (strpos($URI, "/authenticate") !== false) {
    //Obtenemos el token
    $token = $data->Token;
    $token = explode("/tokens/", $URI);
    $token = explode("/authenticate", $token[1]);
    $token = $token[0];

    /* Procesamos */
    $Bragg = new Bragg($token, $user);
    $response = ($Bragg->Auth());
}

// Validamos si la solicitud es de consulta de saldo o si es de transacciones
if (strpos($URI, "/balance") !== false) {
    $token = $data->Token;
    $user = explode("players/", $URI);
    $user = explode("/balance", $user[1]);
    $user = $user[0];

    /* Procesamos */
    $Bragg = new Bragg($token, $user);
    $response = ($Bragg->getBalance());
} elseif (strpos($URI, "game-transactions") !== false) {
    $transactionId = explode("game-transactions/", $URI);
    $transactionId = $transactionId[1];
    $action = $data->action;

    // Si la acción es de cancelación o Rollback
    if ($action == "CANCEL" || $data->roundAction == "CANCEL") {
        $user = $data->playerId;
        $rollbackAmount = 0;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;
        $player = 0;
        $datos = $data;

        /* Procesamos */
        $Bragg = new Bragg($token, $user);
        $response = ($Bragg->Rollback($rollbackAmount, $roundId, $transactionId, $player, json_encode($datos)));
    } elseif ($data->roundAction == "CLOSE") {
        $CreditAmount = $data->win->amount;

        if ($CreditAmount != "" && ($data->win)) {
            $user = $data->playerId;
            $GameCode = $data->gameCode;
            $PlayerId = $data->playerId;
            $RoundId = $data->roundId;
            $datos = $data;
            $transactionId = $data->win->transactionId;
            $CreditAmount = $data->win->amount;
            $CreditAmount = $CreditAmount / 100;
            $roundAction = $data->roundAction;
            $EndRound = false;

            if ($roundAction == 'CLOSE') {
                $EndRound = true;
            }

            //Procesamos y obtenemos la respuesta
            $Bragg = new Bragg($token, $user);
            $response = $Bragg->Credit($GameCode, $CreditAmount, $RoundId, $transactionId, json_encode($datos), $EndRound);
        } else {
            $user = $data->playerId;

            //Procesamos y obtenemos la respuesta
            $Bragg = new Bragg($token, $user);
            $response = ($Bragg->getBalance());
        }
    } elseif ($data->roundAction != "CLOSE") {
        $user = $data->playerId;
        $GameCode = $data->gameCode;
        $PlayerId = $data->playerId;
        $RoundId = $data->roundId;
        $freeRoundId = $data->freeRoundId;
        $datos = $data;
        $transactionId = $data->bet->transactionId;
        $DebitAmount = $data->bet->amount / 100;
        $roundAction = $data->roundAction;
        $EndRound = false;

        if ($roundAction == 'CLOSE') {
            $EndRound = true;
        }

        if ($freeRoundId != "") {
            $DebitAmount = 0;
        }

        //Procesamos y obtenemos la respuesta
        $Bragg = new Bragg($token, $user);
        $response = ($Bragg->Debit($GameCode, $DebitAmount, $RoundId, $transactionId, json_encode($datos)));

        $transactionId = $data->win->transactionId;
        $CreditAmount = $data->win->amount;

        if ($CreditAmount != "" && ($data->win)) {
            $CreditAmount = $CreditAmount / 100;

            //Procesamos y obtenemos la respuesta
            $Bragg = new Bragg($token, $user);
            $response = $Bragg->Credit($GameCode, $CreditAmount, $RoundId, $transactionId, json_encode($datos), $EndRound);
        }
    }
}

$log = "";
$log = $log . "\r\n" . "--------------Response------------" . "\r\n";
$log = $log . $response;
$log = $log . "\r\n" . "---------------------------------" . "\r\n";

print_r($response);
