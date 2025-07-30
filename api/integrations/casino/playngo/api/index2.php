<?php
/**
 * Este archivo contiene la implementación de la API para la integración con el casino 'playngo'.
 * Proporciona endpoints para autenticar, consultar saldo, reservar, liberar y cancelar reservas de transacciones.
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
 * @var string $URI      URI de la solicitud actual, incluyendo el método HTTP.
 * @var string $body     Cuerpo de la solicitud HTTP recibido en formato XML.
 * @var string $method   Método HTTP utilizado en la solicitud.
 * @var mixed  $data     Datos decodificados del cuerpo de la solicitud en formato XML.
 * @var string $log      Cadena utilizada para almacenar información de registro.
 * @var mixed  $response Respuesta generada por las operaciones realizadas en la API.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Playngo;

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');

$URI = $_SERVER['REQUEST_URI'] . " C " . $_SERVER['REQUEST_METHOD'];
$body = trim(file_get_contents('php://input'));
$method = "";

if ($body != "") {
    Header('Content-type: text/xml');

    $data = simplexml_load_string($body);
}


$log = "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . $URI;
$log = $log . trim(file_get_contents('php://input'));

$log = time();


if (strpos($URI, "authenticate") !== false) {
    $Authenticate = $data;

    $AccessToken = $Authenticate->accessToken;
    $Token = $Authenticate->username;
    $gameId = $Authenticate->gameId;

    $Playngo = new Playngo($Token, $AccessToken, '', 'authenticate', $gameId);

    $response = ($Playngo->Auth());
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}

if (strpos($URI, "balance") !== false) {
    $Balance = $data;

    $externalId = intval($Balance->externalId);
    $productId = $Balance->productId;
    $currency = $Balance->currency;
    $gameId = $Balance->gameId;
    $accessToken = $Balance->accessToken;
    $Token = $Balance->username;
    $externalGameSessionId = $Balance->externalGameSessionId;
    $gameId = $Balance->gameId;

    $Playngo = new Playngo($accessToken, $Token, $externalId, 'balance', $gameId);

    $response = ($Playngo->getBalance($gameId));
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}

if (strpos($URI, "reserve") !== false) {
    $reserve = $data;

    $externalId = (string)$reserve->externalId;
    $productId = (string)$reserve->productId;
    $transactionId = (string)$reserve->transactionId;
    $real = (string)$reserve->real;
    $currency = (string)$reserve->currency;
    $gameId = (string)$reserve->gameId;
    $gameSessionId = (string)$reserve->gameSessionId;
    $contextId = (string)$reserve->contextId;
    $accessToken = (string)$reserve->accessToken;
    $Token = (string)$reserve->username;
    $roundId = (string)$reserve->roundId;
    $externalGameSessionId = (string)$reserve->externalGameSessionId;
    $gameId = $reserve->gameId;

    $Playngo = new Playngo($accessToken, $Token, $externalId, 'reserve', $gameId);

    $datos = array(
        "externalId" => (string)$externalId,
        "productId" => (string)$productId,
        "transactionId" => (string)$transactionId,
        "real" => (string)$real,
        "currency" => (string)$currency,
        "gameId" => (string)$gameId,
        "gameSessionId" => (string)$gameSessionId,
        "contextId" => (string)$contextId,
        "accessToken" => (string)$accessToken,
        "roundId" => (string)$roundId,
        "roundId" => (string)$roundId,
        "externalGameSessionId" => (string)$externalGameSessionId
    );

    $response = ($Playngo->Debit($gameId, $roundId, "", $real, $transactionId, json_encode($datos)));
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}

if (strpos($URI, "release") !== false) {
    $release = $data;

    $externalId = (string)$release->externalId;
    $productId = (string)$release->productId;
    $transactionId = (string)$release->transactionId;
    $real = (string)$release->real;
    $currency = (string)$release->currency;

    $gameId = (string)$release->gameId;
    $gameSessionId = (string)$release->gameSessionId;
    $contextId = (string)$release->contextId;
    $state = (string)$release->state;
    $totalLoss = (string)$release->totalLoss;
    $totalGain = (string)$release->totalGain;
    $numRounds = (string)$release->numRounds;
    $type = (string)$release->type;
    $accessToken = (string)$release->accessToken;
    $roundId = (string)$release->roundId;
    $Token = (string)$release->username;
    $jackpotGain = (string)$release->jackpotGain;
    $jackpotLoss = (string)$release->jackpotLoss;
    $jackpotGainSeed = (string)$release->jackpotGainSeed;
    $jackpotGainId = (string)$release->jackpotGainId;
    $freegameExternalId = (string)$release->freegameExternalId;
    $turnover = (string)$release->turnover;
    $freegameFinished = (string)$release->freegameFinished;
    $freegameGain = (string)$release->freegameGain;
    $freegameLoss = (string)$release->freegameLoss;
    $externalGameSessionId = (string)$release->externalGameSessionId;
    $gameMode = (string)$release->gameMode;
    $gameId = $release->gameId;


    $Playngo = new Playngo($accessToken, $Token, $externalId, 'release', $gameId);

    $datos = array(
        "externalId" => (string)$externalId,
        "productId" => (string)$productId,
        "transactionId" => (string)$transactionId,
        "real" => (string)$real,
        "currency" => (string)$currency,

        "gameId" => (string)$gameId,
        "gameSessionId" => (string)$gameSessionId,
        "contextId" => (string)$contextId,
        "state" => (string)$state,
        "totalLoss" => (string)$totalLoss,
        "totalGain" => (string)$totalGain,
        "numRounds" => (string)$numRounds,
        "type" => (string)$type,
        "accessToken" => (string)$accessToken,
        "roundId" => (string)$roundId,
        "jackpotGain" => (string)$jackpotGain,
        "jackpotLoss" => (string)$jackpotLoss,
        "jackpotGainSeed" => (string)$jackpotGainSeed,
        "jackpotGainId" => (string)$jackpotGainId,
        "freegameExternalId" => (string)$freegameExternalId,
        "turnover" => (string)$turnover,
        "freegameFinished" => (string)$freegameFinished,
        "freegameGain" => (string)$freegameGain,
        "freegameLoss" => (string)$freegameLoss,
        "externalGameSessionId" => (string)$externalGameSessionId,
        "gameMode" => (string)$gameMode
    );

    if ($type == 1) {
        $response = ($Playngo->Debit($gameId, $roundId, "", 0, "FS" . $transactionId, json_encode($datos), true));
    }

    $response = ($Playngo->Credit($gameId, $roundId, "", $real, $transactionId, $isEnd, json_encode($datos)));
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}

if (strpos($URI, "cancelReserve") !== false) {
    $cancelReserve = $data;

    $externalId = (string)$cancelReserve->externalId;
    $productId = (string)$cancelReserve->productId;
    $transactionId = (string)$cancelReserve->transactionId;
    $real = (string)$cancelReserve->real;
    $currency = (string)$cancelReserve->currency;
    $accessToken = (string)$cancelReserve->accessToken;
    $Token = (string)$cancelReserve->username;
    $roundId = (string)$cancelReserve->roundId;
    $gameId = (string)$cancelReserve->gameId;
    $externalGameSessionId = (string)$cancelReserve->externalGameSessionId;
    $gameId = $cancelReserve->gameId;

    $Playngo = new Playngo($accessToken, $Token, $externalId, 'cancelReserve', $gameId);

    $datos = array(
        "externalId" => (string)$externalId,
        "productId" => (string)$productId,
        "transactionId" => (string)$transactionId,
        "real" => (string)$real,
        "currency" => (string)$currency,
        "accessToken" => (string)$accessToken,
        "roundId" => (string)$roundId,
        "gameId" => (string)$gameId,
        "externalGameSessionId" => (string)$externalGameSessionId
    );

    $response = ($Playngo->Rollback($gameId, $roundId, "", $real, $transactionId, json_encode($datos)));
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}

