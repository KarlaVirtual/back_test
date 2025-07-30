<?php

/**
 * Este archivo contiene un script para procesar las solicitudes de la API de casino 'Kagaming'
 * en modo confirmación. Maneja diferentes endpoints como inicio de sesión, balance, jugadas,
 * créditos, finalización de sesión, revocaciones y giros gratis.
 *
 * @category   Casino
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
 * @var mixed $_ENV     Variable superglobal que contiene información del entorno de ejecución.
 * @var mixed $URI      Variable que almacena la URI de la solicitud actual.
 * @var mixed $body     Contenido del cuerpo de la solicitud en formato JSON.
 * @var mixed $url      Resultado del análisis de la URI en componentes.
 * @var mixed $query    Parámetros de consulta extraídos de la URI.
 * @var mixed $data     Datos decodificados del cuerpo de la solicitud.
 * @var mixed $response Respuesta generada por las operaciones realizadas en los endpoints.
 */

header('Content-type: application/json');

$_ENV["enabledConnectionGlobal"] = 1;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

if (version_compare(phpversion(), '7.1', '>=')) {
    ini_set('precision', 17);
    ini_set('serialize_precision', -1);
}

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Kagaming;

$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');

$url = parse_url($URI);
parse_str($url['query'], $query);

$data = json_decode($body);

$URI = explode('/', $URI);
$URI = $URI[count($URI) - 1];
$URI = explode('?', $URI);
$URI = $URI[0];

if (true) {
    if ($URI == "start") {
        $timestamp = $data->timestamp;
        $sessionId = $data->sessionId;
        $playerId = $data->playerId;
        $currency = $data->currency;
        $action = $data->action;
        $gameId = $data->gameId;
        $playerIp = $data->playerIp;
        $token = $data->token;
        $partnerPlayerId = $data->partnerPlayerId;

        $Kagaming = new Kagaming($playerId, $token, $sessionId);
        $response = $Kagaming->Auth();
    } elseif ($URI == "balance") {
        $timestamp = $data->timestamp;
        $sessionId = $data->sessionId;
        $playerId = $data->playerId;
        $currency = $data->currency;
        $action = $data->action;
        $gameId = $data->gameId;
        $playerIp = $data->playerIp;
        $token = $data->token;
        $partnerPlayerId = $data->partnerPlayerId;
        $Kagaming = new Kagaming($playerId, $token, $sessionId);
        $response = $Kagaming->Balance();
    } elseif ($URI == "play") {
        $transactionId = $data->transactionId;
        $betAmount = $data->betAmount;
        $winAmount = $data->winAmount;
        $jpc = $data->jpc;
        $selections = $data->selections;
        $betPerSelection = $data->betPerSelection;
        $freeGames = $data->freeGames;
        $round = $data->round;
        $roundsRemaining = $data->roundsRemaining;
        $timestamp = $data->timestamp;
        $sessionId = $data->sessionId;
        $playerId = $data->playerId;
        $currency = $data->currency;
        $action = $data->action;
        $gameId = $data->gameId;
        $playerIp = $data->playerIp;
        $token = $data->token;
        $partnerPlayerId = $data->partnerPlayerId;

        if ($data->promotionSpins->status == 'active' || $data->promotionSpins->status == 'completed') {
            $betAmount = 0;
        }

        $rounds = base64_encode($transactionId . $action . $gameId . $timestamp);
        $roundss = $sessionId . '_' . $transactionId . $round;

        /* Procesamos */
        $Kagaming = new Kagaming($playerId, $token, $sessionId);
        $response = $Kagaming->Debit($gameId, number_format($betAmount / 100, 2, '.', ''), $roundss, $transactionId . '_' . $rounds, $data, false, $currency, $type = 'Debit');
        $response = $Kagaming->Credit($gameId, number_format($winAmount / 100, 2, '.', ''), $roundss, $transactionId . '_' . $rounds . '_C', $data, false, false, $currency);
    } elseif ($URI == "credit") {
        $transactionId = $data->transactionId;
        $amount = $data->amount;
        $jpw = $data->jpw;
        $type = $data->type;
        $creditIndex = $data->creditIndex;
        $complete = $data->complete;
        $timestamp = $data->timestamp;
        $sessionId = $data->sessionId;
        $playerId = $data->playerId;
        $currency = $data->currency;
        $action = $data->action;
        $gameId = $data->gameId;
        $playerIp = $data->playerIp;
        $token = $data->token;
        $partnerPlayerId = $data->partnerPlayerId;

        $rounds = base64_encode($transactionId . $action . $gameId . $timestamp);
        $roundss = $sessionId . '_' . $transactionId;

        /* Procesamos */
        $Kagaming = new Kagaming($playerId, $token, $sessionId);
        $response = $Kagaming->Debit($gameId, 0, $roundss, $transactionId . '_' . $rounds, $data, false, $currency, $type = 'Debit');
        $response = $Kagaming->Credit($gameId, number_format($amount / 100, 2, '.', ''), $roundss, $transactionId . '_' . $rounds . '_C', $data, false, false, $currency);
    } elseif ($URI == "end") {
        $sessionStatus = $data->sessionStatus;
        $timestamp = $data->timestamp;
        $sessionId = $data->sessionId;
        $playerId = $data->playerId;
        $currency = $data->currency;
        $action = $data->action;
        $gameId = $data->gameId;
        $playerIp = $data->playerIp;
        $token = $data->token;
        $partnerPlayerId = $data->partnerPlayerId;

        /* Procesamos */
        $Kagaming = new Kagaming($playerId, $token, $sessionId);
        $response = $Kagaming->End();
    } elseif ($URI == "revoke") {
        $transactionId = $data->transactionId;
        $round = $data->round;
        $type = $data->revokedAction;
        $win = $data->win;
        $bet = $data->bet;
        $timestamp = $data->timestamp;
        $sessionId = $data->sessionId;
        $playerId = $data->playerId;
        $currency = $data->currency;
        $action = $data->action;
        $gameId = $data->gameId;
        $playerIp = $data->playerIp;
        $token = $data->token;
        $partnerPlayerId = $data->partnerPlayerId;
        $operatoName = $data->operatoName;

        $roundss = $sessionId . '_' . $transactionId . $round;

        /* Procesamos */
        $Kagaming = new Kagaming($playerId, $token, $sessionId);
        $response = $Kagaming->Rollback(0, $roundss, $transactionId, json_encode($data), false, $gameId, $type);
        if ($type == 'credit' || $type == 'play') {
            $response = $Kagaming->Rollback(0, $roundss, $transactionId, json_encode($data), false, $gameId, 'debit');
        }
    } elseif ($URI == "freeSpin") {
        $partnerName = $data->partnerName;
        $operatorName = $data->operatorName;
        $revokedAction = $data->revokedAction;
        $playerId = $data->playerId;
        $numberSpins = $data->numberSpins;
        $betLevel = $data->betLevel;
        $endDate = $data->endDate;
        $startDate = $data->startDate;
        $currency = $data->currency;
        $username = $data->username;
        $app = $data->app;

        /* Procesamos */
        $Kagaming = new Kagaming($playerId, $token, $sessionId);
        $response = $Kagaming->freeSpin($currency);
    }
}

echo $response;
