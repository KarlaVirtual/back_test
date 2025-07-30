<?php

/**
 * Este archivo contiene un script para procesar y manejar las integraciones de la API del casino 'Zeusplay'.
 * Proporciona funcionalidades para gestionar el balance de jugadores, rondas de juego y cancelaciones de rondas.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 *
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Variable superglobal que contiene configuraciones de entorno.
 * @var mixed $URI      Almacena la URI de la solicitud actual.
 * @var mixed $body     Contiene el cuerpo de la solicitud en formato JSON.
 * @var mixed $data     Almacena los datos decodificados del cuerpo de la solicitud.
 * @var mixed $log      Variable utilizada para almacenar información de registro.
 * @var mixed $response Almacena la respuesta generada por las operaciones realizadas.
 */


require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Zeusplay;

header('Content-type: application/json');

$URI = $_SERVER['REQUEST_URI'];

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);
}

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . ' URI ';
$log = $log . ($URI);
$log = $log . ' BODY';
$log = $log . $body;


if (true) {
    if (strpos($URI, "/player-wallet") !== false) {
        $partnerPlayerId = $data->partnerPlayerId;
        $currencyCode = $data->currencyCode;
        $sessionId = $data->sessionId;
        $gameCode = $data->gameCode;

        /* Procesamos */
        $Zeusplay = new Zeusplay($sessionId);
        $response = ($Zeusplay->getBalance($partnerPlayerId));
    } else {
        if (strpos($URI, "/game-round") !== false) {
            $transactionId = $data->transactionId;
            $sessionId = $data->sessionId;
            $partnerPlayerId = $data->partnerPlayerId;
            $currencyCode = $data->currencyCode;
            $gameCode = $data->gameCode;
            $main = $data->sessionRound->main;
            $sub = $data->sessionRound->sub;
            $amountBet = $data->bet->amount;
            $type = $data->bet->type;
            $amountWin = $data->win->amount;

            $roundid = $sessionId . '_' . $main; //Revisar el comportamiento.

            if ($type == "normalBet") {
                $Zeusplay = new Zeusplay($sessionId, $partnerPlayerId);

                $response = $Zeusplay->Debit($gameCode, $amountBet, $roundid, $transactionId, json_encode($data));
                $response = $Zeusplay->Credit($gameCode, $amountWin, $roundid, 'C' . $transactionId, json_encode($data), "", "");
            } elseif ($type == "freeBet" or $type == "freeRound") {
                $Zeusplay = new Zeusplay($sessionId, $partnerPlayerId);

                $amountBet = 0;

                $response = $Zeusplay->Debit($gameCode, $amountBet, $roundid, 'FS' . $transactionId, json_encode($data));
                $response = $Zeusplay->Credit($gameCode, $amountWin, $roundid, 'C' . $transactionId, json_encode($data), "", "");
            }
        } elseif (strpos($URI, "/cancel-game-round")) {
            $transactionId = $data->transactionId;
            $sessionId = $data->sessionId;
            $partnerPlayerId = $data->partnerPlayerId;
            $currencyCode = $data->currencyCode;
            $gameCode = $data->gameCode;
            $main = $data->sessionRound->main;
            $sub = $data->sessionRound->sub;
            $amountBet = $data->bet->amount;
            $type = $data->bet->type;
            $amountWin = $data->win->amount;

            if ($type == "freeBet" or $type == "freeRound") {
                $transactionId = 'FS' . $transactionId;
            }

            $RoundId = $sessionId . '_' . $main;

            $CancelEntireRound = true;

            $Zeusplay = new Zeusplay($sessionId, $partnerPlayerId);
            $response = $Zeusplay->Rollback($transactionId, $gameCode, $RoundId, $CancelEntireRound, $transactionId, $amountBet, json_encode($data));
        }
    }

    $log = "";
    $log = $log . "/" . time();

    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}
