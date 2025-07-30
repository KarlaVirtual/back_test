<?php

/**
 * Este archivo contiene la implementación de la API para la integración con el casino 'BetGamesTV'.
 * Proporciona múltiples métodos para manejar solicitudes relacionadas con cuentas, balances, transacciones
 * y otros procesos específicos del casino.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    ninguna
 * @since      2025-05-09
 */

/**
 * Configuración inicial del script:
 * - Desactiva la visualización de errores.
 * - Carga las dependencias necesarias mediante Composer.
 */
ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Betgamestv;

/**
 * Configuración de encabezados HTTP para permitir solicitudes CORS.
 */
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');

/**
 * Variables globales utilizadas en el script:
 *
 * @var string $URI    URI de la solicitud actual.
 * @var string $body   Cuerpo de la solicitud HTTP.
 * @var string $method Método de la solicitud HTTP.
 */
$URI = $_SERVER['REQUEST_URI'] . " C " . $_SERVER['REQUEST_METHOD'];
$body = trim(file_get_contents('php://input'));
$method = "";

/**
 * Configuración de variables de entorno para habilitar conexiones globales y establecer tiempos de espera.
 */
$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

/**
 * Procesamiento del cuerpo de la solicitud si no está vacío.
 * Convierte el cuerpo en un objeto SimpleXML.
 */
if ($body != "") {
    Header('Content-type: text/xml');
    $data = simplexml_load_string($body);
}

/**
 * Habilita el modo de depuración si se recibe un parámetro específico en la solicitud.
 */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

/**
 * Manejo de métodos específicos enviados en la solicitud.
 * Cada bloque maneja un método particular y realiza las operaciones correspondientes.
 */

// Manejo del método "get_account_details".
if ($method == "get_account_details") {
    $Authenticate = $data;

    $AccessToken = $Authenticate->signature;
    $Token = $Authenticate->token;
    $requestId = $Authenticate->request_id;

    $Betgamestv = new Betgamestv($Token, $AccessToken, "", $method, "", $requestId);

    $response = ($Betgamestv->Auth());
}

// Manejo del método "ping".
if ($method == "ping") {
    $Balance = $data;

    $accessToken = $Balance->signature;
    $Token = $Balance->token;
    $requestId = $Balance->request_id;


    $Betgamestv = new Betgamestv($Token, $accessToken, $externalId, $method, "", $requestId);

    $response = ($Betgamestv->ping());
}

// Manejo del método "get_balance".
if ($method == "get_balance") {
    $Balance = $data;

    $externalId = intval($Balance->externalId);

    $accessToken = $Balance->signature;
    $Token = $Balance->token;
    $requestId = $Balance->request_id;


    $Betgamestv = new Betgamestv($Token, $accessToken, $externalId, $method, "", $requestId);

    $response = ($Betgamestv->getBalance($gameId));
}

// Manejo de métodos relacionados con la actualización de tokens.
if ($method == "refresh_token" || $method == 'request_new_token') {
    $Balance = $data;

    $accessToken = $Balance->signature;
    $Token = $Balance->token;
    $requestId = $Balance->request_id;


    $Betgamestv = new Betgamestv($Token, $accessToken, $externalId, $method, "", $requestId);

    $response = ($Betgamestv->refreshToken());
}

if ($method == "transaction_bet_payin" || $method == "transaction_bet_subscription_payin" || $method == 'transaction_bet_combination_payin' || $method == 'transaction_bet_multi_payin') {
    $reserve = $data;

    $ConfigurationEnvironment = new ConfigurationEnvironment();
    $isDevelopment = $ConfigurationEnvironment->isDevelopment();

    if ($isDevelopment) {
        $game = (string)$reserve->params->game;
        $gameId = (string)$reserve->params->game;
    } else {
        $gameId = null;
    }

    $Token = (string)$reserve->token;
    $accessToken = (string)$reserve->signature;
    $requestId = (string)$reserve->request_id;

    $game = (string)$reserve->params->game;
    $transactionId = (string)$reserve->params->transaction_id;
    $real = (string)$reserve->params->amount;
    $real = $real / 100;
    $currency = (string)$reserve->params->currency;
    $roundId = (string)$reserve->params->bet_id;

    if ( ! $isDevelopment) {
        $gameId = (string)$reserve->params->game;
    }

    $bets = [];

    if ($method == "transaction_bet_subscription_payin") {
        $gameId = (string)$reserve->params->game->id;

        $transactionId = 'TBSPI' . (string)$reserve->params->subscription_id;

        foreach ($reserve->params->children() as $child) {
            if ($child->getName() == "bet") {
                array_push($bets, array(
                    "id" => "BETGAMESTV" . (string)$child->bet_id,
                    "amount" => floatval((string)$child->amount) / 100,
                    "transactionId" => (string)$child->transaction_id
                ));
            }
        }
    }

    if ($method == "transaction_bet_combination_payin") {
        $gameId = (string)$reserve->params->game->id;

        $transactionId = 'TBCPI' . (string)$reserve->params->combination_id;
        $roundId = (string)$transactionId;

        foreach ($reserve->params->children() as $child) {
            if ($child->getName() == "bet") {
                $gameId = (string)$child->game->id;
            }
        }
        if ( ! $isDevelopment) {
            $gameId = '1aa1';
        }

        if ( ! $isDevelopment) {
            if ($game == '27') {
                $gameId = '27';
            } else {
                $gameId = '1aa1';
            }
        }
    }

    $retrying = (string)$reserve->params->retrying;
    $odd = (string)$reserve->params->odd;
    $bet_time = (string)$reserve->params->bet_time;
    $draw_code = (string)$reserve->params->draw_code;
    $draw_time = (string)$reserve->params->draw_time;

    $Betgamestv = new Betgamestv($Token, $accessToken, $externalId, $method, "", $requestId);

    $datos = array(
        "game" => (string)$game,
        "transactionId" => (string)$transactionId,
        "real" => (string)$real,
        "currency" => (string)$currency,
        "gameId" => (string)$gameId,
        "accessToken" => (string)$accessToken,
        "roundId" => (string)$roundId,
        "retrying" => (string)$retrying,
        "odd" => (string)$odd,
        "bet_time" => (string)$bet_time,
        "draw_code" => (string)$draw_code,
        "draw_time" => (string)$draw_time
    );

    $response = ($Betgamestv->Debit($gameId, $roundId, "", $real, $transactionId, json_encode($datos), false, $bets));
}

// Manejo de métodos relacionados con pagos de apuestas.
if ($method == "transaction_bet_payout" || $method == "transaction_bet_combination_payout" || $method == "transaction_promo_payout") {
    $release = $data;

    $ConfigurationEnvironment = new ConfigurationEnvironment();
    $isDevelopment = $ConfigurationEnvironment->isDevelopment();

    $Token = (string)$release->token;
    $accessToken = (string)$release->signature;
    $requestId = (string)$release->request_id;

    $externalId = (string)$release->params->player_id;
    $real = (string)$release->params->amount;
    $real = $real / 100;
    $currency = (string)$release->params->currency;
    $roundId = (string)$release->params->bet_id;
    $transactionId = (string)$release->params->transaction_id;

    $productId = (string)$release->params->game_id;

    if ($isDevelopment) {
        $gameId = (string)$release->params->game_id;
    }

    $bet_type = (string)$release->params->bet_type;

    if ($method == "transaction_bet_combination_payout") {
        if ( ! $isDevelopment) {
            $gameId = (string)$release->params->game->id;
        }
        $transactionId = 'TBCPO' . (string)$release->params->combination_id;
        $roundId = 'TBCPI' . (string)$release->params->combination_id;

        if ( ! $isDevelopment) {
            $gameId = '1aa1';
        }
    }

    if ( ! $isDevelopment) {
        $gameId = '1aa1';
    }

    $Betgamestv = new Betgamestv($Token, $accessToken, $externalId, $method, "", $requestId);

    $datos = array(
        "externalId" => (string)$externalId,
        "productId" => (string)$productId,
        "transactionId" => (string)$transactionId,
        "real" => (string)$real,
        "currency" => (string)$currency,
        "gameId" => (string)$gameId,
        "bet_type" => (string)$bet_type
    );

    if ($bet_type == 'return') {
        $response = ($Betgamestv->Rollback($gameId, $roundId, "", $real, $transactionId, $isEnd, json_encode($datos)));
    } else {
        if ($type == 1) {
            $response = ($Betgamestv->Debit($gameId, $roundId, "", 0, "FS" . $transactionId, json_encode($datos), true));
        }

        $response = ($Betgamestv->Credit($gameId, $roundId, "", $real, $transactionId, $isEnd, json_encode($datos)));
    }
}


if ($method == "cancelReserve") {
    $cancelReserve = $data;

    $externalId = (string)$cancelReserve->externalId;
    $productId = (string)$cancelReserve->productId;
    $transactionId = (string)$cancelReserve->transactionId;
    $real = (string)$cancelReserve->amount;
    $real = $real / 100;
    $currency = (string)$cancelReserve->currency;
    $accessToken = (string)$cancelReserve->accessToken;
    $Token = (string)$cancelReserve->username;
    $requestId = (string)$cancelReserve->request_id;
    $roundId = (string)$cancelReserve->roundId;
    $gameId = (string)$cancelReserve->gameId;
    $externalGameSessionId = (string)$cancelReserve->externalGameSessionId;

    $Betgamestv = new Betgamestv($Token, $accessToken, $externalId, $method, "", $requestId);

    $datos = array(
        "externalId" => (string)$externalId,
        "productId" => (string)$productId,
        "transactionId" => (string)$transactionId,
        "real" => (string)$real,
        "currency" => (string)$currency,
        "accessToken" => (string)$accessToken,
        "request_id" => (string)$requestId,
        "roundId" => (string)$roundId,
        "gameId" => (string)$gameId,
        "externalGameSessionId" => (string)$externalGameSessionId
    );

    $response = ($Betgamestv->Rollback($gameId, $roundId, "", $real, $transactionId, json_encode($datos)));
}

/**
 * Registro de la respuesta generada y finalización del script.
 */
$log = $log . "/" . time();

$log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . ($response);

print_r($response);
