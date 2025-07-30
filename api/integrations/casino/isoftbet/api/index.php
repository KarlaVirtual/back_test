<?php
/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API de casino 'isoftbet'.
 * Incluye la lógica para autenticar, procesar transacciones, manejar saldos y generar informes.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Variable superglobal que contiene variables de entorno configuradas para el script.
 * @var mixed $log      Variable que almacena información de registro para depuración.
 * @var mixed $body     Contenido del cuerpo de la solicitud HTTP recibido en formato JSON.
 * @var mixed $data     Objeto decodificado del cuerpo de la solicitud JSON.
 * @var mixed $URI      URI de la solicitud actual.
 * @var mixed $response Respuesta generada por las operaciones realizadas en el script.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\ISoftBet;

header('Content-type: application/json; charset=utf-8');

ini_set('memory_limit', '-1');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$log = "\r\n" . "-------------------------/" . time() . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . " Body ";
$log = $log . trim(file_get_contents('php://input'));

$body = file_get_contents('php://input');

$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);

$URI = $_SERVER['REQUEST_URI'];

if ($body != "") {
    $ISoftBet = new ISoftBet($data->sessionid, "", $data->playerid);
    $PESignature = $ISoftBet->sign($body);

    if ($PESignature == $_GET["hash"]) {
        if (strpos($data->action->command, "init") !== false) {
            $sesion = $data->sessionid;
            $playerid = $data->playerid;
            $skinid = $data->skinid;
            $state = $data->state;
            $operator = $data->operator;
            $token = $data->action->parameters->token;
            $country = $data->action->parameters->country;
            $game_type = $data->action->parameters->game_type;
            $jppool = $data->action->parameters->jppool;

            $sign = $_REQUEST["sign"];

            /* Procesamos */
            $ISoftBet = new ISoftBet($token, $sign);
            $response = ($ISoftBet->Auth($sesion));
        }

        if (strpos($data->action->command, "token") !== false) {
            $token = $data->sessionid;
            $sign = $_REQUEST["sign"];

            /* Procesamos */
            $ISoftBet = new ISoftBet($token, $sign);
            $response = ($ISoftBet->Token($data));
        }

        if (strpos($data->action->command, "balance") !== false) {
            $token = $data->sessionid;;
            /* Procesamos */
            $ISoftBet = new ISoftBet($token, $sign);
            $response = ($ISoftBet->getBalance());
        }

        if (strpos($data->action->command, "win") !== false) {
            $token = $data->sessionid;
            $operator = $data->operator;
            $transactionId = $data->action->parameters->transactionid;
            $transactionRefId = $data->action->parameters->transactionRefId;
            $roundid = $data->action->parameters->roundid;
            $closeround = $data->action->parameters->closeround;
            $CreditAmount = floatval(round($data->action->parameters->amount, 2) / 100);
            $jpw = doubleval(round($data->action->parameters->jpw, 2) / 100);
            $jpw_from_jpc = doubleval(round($data->action->parameters->jpw_from_jpc, 2) / 100);
            $froundid = $data->action->parameters->froundid;
            $fround_campaignid = $data->action->parameters->fround_campaignid;
            $fround_coin_value = intval(round($data->action->parameters->fround_coin_value, 2) / 100);
            $fround_lines = $data->action->parameters->fround_lines;
            $game_id = $data->skinid;
            $fround_line_bet = $data->action->parameters->fround_line_bet;
            $timestamp = $data->action->parameters->timestamp;
            $game_type = $data->action->parameters->game_type;
            $additional_game_values = $data->action->parameters->additional_game_values;
            $jackpot = $data->action->parameters->additional_game_values->jackpot;

            $datos = $data;

            /* Procesamos */
            if ($operator != "testOperator") {
                $ISoftBet = new ISoftBet($token, $sign);
                $response = $ISoftBet->Credit($game_id, $CreditAmount, $roundid, $transactionId, json_encode($datos));
            } else {
                $respuesta = array(
                    "status" => "error",
                    "code" => "W_07",
                    "message" => "Operator should remain the same during the round.",
                    "action" => "void"
                );
                $response = json_encode($respuesta);
            }
        }

        if (strpos($data->action->command, "bet") !== false) {
            $token = $data->sessionid;
            $transactionId = $data->action->parameters->transactionid;
            $transactionRefId = $data->action->transactionRefId;
            $roundid = $data->action->parameters->roundid;
            $DebitAmount = floatval(round($data->action->parameters->amount, 2) / 100);
            $jpc = doubleval(round($data->action->parameters->jpc, 2) / 100);
            $froundid = $data->action->parameters->froundid;
            $fround_campaignid = $data->action->parameters->fround_campaignid;
            $fround_coin_value = intval(round($data->action->parameters->fround_coin_value, 2) / 100);
            $fround_lines = $data->action->parameters->fround_lines;
            $game_id = $data->skinid;
            $fround_line_bet = $data->action->parameters->fround_line_bet;
            $timestamp = $data->action->parameters->timestamp;
            $game_type = $data->action->parameters->game_type;
            $additional_game_values = $data->action->parameters->additional_game_values;
            $jackpot = $data->action->parameters->additional_game_values->jackpot;

            $freespin = false;
            $datos = $data;

            /* Procesamos */
            $ISoftBet = new ISoftBet($token, $sign);

            if ($froundid > 0) {
                $freespin = true;
                $response = ($ISoftBet->Debit($game_id, 0, $roundid, 'FS' . $transactionId, json_encode($datos), $freespin));
            } else {
                $response = ($ISoftBet->Debit($game_id, $DebitAmount, $roundid, $transactionId, json_encode($datos), $freespin));
            }
        }

        if ($data->state == "multi") {
            if (strpos($data->actions[0]->command, "bet") !== false) {
                $token = $data->sessionid;
                $transactionId = $data->actions[0]->parameters->transactionid;
                $transactionRefId = $data->actions[0]->transactionRefId;
                $roundid = $data->actions[0]->parameters->roundid;
                $DebitAmount = floatval(round($data->actions[0]->parameters->amount, 2) / 100);
                $jpc = doubleval(round($data->actions[0]->parameters->jpc, 2) / 100);
                $froundid = $data->actions[0]->parameters->froundid;
                $fround_campaignid = $data->actions[0]->parameters->fround_campaignid;
                $fround_coin_value = intval(round($data->actions[0]->parameters->fround_coin_value, 2) / 100);
                $fround_lines = $data->actions[0]->parameters->fround_lines;
                $game_id = $data->skinid;
                $fround_line_bet = $data->actions[0]->parameters->fround_line_bet;
                $timestamp = $data->actions[0]->parameters->timestamp;
                $game_type = $data->actions[0]->parameters->game_type;
                $additional_game_values = $data->actions[0]->parameters->additional_game_values;
                $jackpot = $data->actions[0]->parameters->additional_game_values->jackpot;

                $freespin = false;
                if ($froundid > 0) {
                    $freespin = true;
                    $DebitAmount = 0;
                }
                $datos = $data;

                /* Procesamos */
                $ISoftBet = new ISoftBet($token, $sign);
                $response = ($ISoftBet->Debit($game_id, $DebitAmount, $roundid, $transactionId, json_encode($datos), $freespin));

                if (strpos($data->actions[1]->command, "win") !== false) {
                    $token = $data->sessionid;
                    $operator = $data->operator;
                    $transactionId = $data->actions[1]->parameters->transactionid;
                    $transactionRefId = $data->actions[1]->parameters->transactionRefId;
                    $roundid = $data->actions[1]->parameters->roundid;
                    $closeround = $data->actions[1]->parameters->closeround;
                    $CreditAmount = floatval(round($data->actions[1]->parameters->amount, 2) / 100);
                    $jpw = doubleval(round($data->actions[1]->parameters->jpw, 2) / 100);
                    $jpw_from_jpc = doubleval(round($data->actions[1]->parameters->jpw_from_jpc, 2) / 100);
                    $froundid = $data->actions[1]->parameters->froundid;
                    $fround_campaignid = $data->actions[1]->parameters->fround_campaignid;
                    $fround_coin_value = intval(round($data->actions[1]->parameters->fround_coin_value, 2) / 100);
                    $fround_lines = $data->actions[1]->parameters->fround_lines;
                    $game_id = $data->skinid;
                    $fround_line_bet = $data->actions[1]->parameters->fround_line_bet;
                    $timestamp = $data->actions[1]->parameters->timestamp;
                    $game_type = $data->actions[1]->parameters->game_type;
                    $additional_game_values = $data->actions[1]->parameters->additional_game_values;
                    $jackpot = $data->actions[1]->parameters->additional_game_values->jackpot;

                    $datos = $data;

                    /* Procesamos */
                    if ($operator != "testOperator") {
                        $ISoftBet = new ISoftBet($token, $sign);
                        $response = $ISoftBet->Credit($game_id, $CreditAmount, $roundid, $transactionId, json_encode($datos));
                    } else {
                        $respuesta = array(
                            "status" => "error",
                            "code" => "W_07",
                            "message" => "Operator should remain the same during the round.",
                            "action" => "void"

                        );
                        $response = json_encode($respuesta);
                    }
                }
            }
        }

        if (strpos($data->action->command, "depositmoney") !== false) {
            $token = $data->sessionid;
            $playerid = $data->playerid;
            $game_id = $data->skinid;
            $roundid = $data->action->parameters->transactionid;
            $transactionId = $data->action->parameters->transactionid;
            $amount = floatval(round($data->action->parameters->amount, 2) / 100);
            $offerid = $data->action->parameters->offerid;
            $type = $data->action->parameters->type;
            $description = $data->action->parameters->description;
            $additional_values = $data->action->parameters->additional_values;

            $datos = $data;
            $ISoftBet = new ISoftBet($token, $sign, $playerid);

            if ($type == "leaderboard") {
                $game_id = "Bonos";
                $response = ($ISoftBet->Debit($game_id, 0, $roundid, "F.S" . $transactionId, json_encode($datos), true));
                $response = $ISoftBet->Credit($game_id, $amount, $roundid, $transactionId, json_encode($datos));
            }
            if ($type == "networkJP") {
                $response = ($ISoftBet->Debit($game_id, 0, $roundid, "J.P" . $transactionId, json_encode($datos), true));
                $response = $ISoftBet->Credit($game_id, $amount, $roundid, 'CJ.P' . $transactionId, json_encode($datos));
            }
        }

        if (strpos($data->action->command, "cancel") !== false) {
            $token = $data->sessionid;
            $playerid = $data->playerid;
            $transactionId = $data->action->parameters->transactionid;
            $transactionRefId = $data->action->parameters->transactionRefId;
            $roundid = $data->action->parameters->roundid;
            $rollbackAmount = floatval(round($data->action->parameters->amount, 2) / 100);
            $jpc = doubleval(round($data->action->parameters->jpc, 2) / 100);
            $froundid = $data->action->parameters->froundid;
            $fround_coin_value = intval(round($data->action->parameters->fround_coin_value, 2) / 100);
            $fround_lines = $data->action->parameters->fround_lines;
            $fround_line_bet = $data->action->parameters->fround_line_bet;
            $timestamp = $data->action->parameters->timestamp;
            $additional_game_values = $data->action->parameters->additional_game_values;

            $datos = $data;

            /* Procesamos */
            $ISoftBet = new ISoftBet($token, $sign);
            $response = ($ISoftBet->Rollback($rollbackAmount, $roundid, $transactionId, $playerid, json_encode($datos)));
        }

        if (strpos($data->action->command, "end") !== false) {
            $token = $data->sessionid;
            $sessionstatus = $data->action->parameters->sessionstatus;

            $ISoftBet = new ISoftBet($token, $sign);
            $response = ($ISoftBet->End());
        }

        if (strpos($data->action->command, "dialog") !== false) {
            $token = $data->sessionid;
            $action = $data->action;
            $choiceIndex = $data->choiceIndex;

            /* Procesamos */
            $ISoftBet = new ISoftBet($token, $sign);
            $response = ($ISoftBet->getBalance());
        }
    } else {
        $respuesta = array(
            "status" => "error",
            "code" => "R_03",
            "message" => "Invalid HMAC",
            "action" => "void"

        );
        $response = json_encode($respuesta);
    }
}


if (strpos($URI, "report") !== false) {
    $token = $data->sessionid;
    $playerid = $_GET["playerid"];
    $operator = $_GET["operator"];
    $dateFrom = $_GET["date_from"];
    $dateFrom = explode(" ", $dateFrom);
    $dateFrom = str_replace("T", ' ', $dateFrom[0]);
    $dateTo = $_GET["date_to"];
    $dateTo = explode(" ", $dateTo);
    $dateTo = str_replace("T", ' ', $dateTo[0]);

    /* Procesamos */
    $ISoftBet = new ISoftBet($token, $sign);
    $response = ($ISoftBet->report($playerid, $operator, $dateFrom, $dateTo));
}


$log = "";
$log = $log . "/" . time();
$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($response);

print_r($response);
