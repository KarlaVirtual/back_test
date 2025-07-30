<?php
/**
 * Este archivo contiene un script para manejar las solicitudes de la API del casino 'Merkur',
 * procesando operaciones como autenticación, balance, depósitos, liberaciones y cancelaciones de reservas.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     público
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var string $URI      URI de la solicitud actual, incluyendo el método HTTP.
 * @var string $body     Cuerpo de la solicitud HTTP recibido en formato XML.
 * @var string $method   Método HTTP utilizado en la solicitud.
 * @var string $log      Variable para almacenar información de registro (logs).
 * @var object $data     Objeto SimpleXML que contiene los datos del cuerpo de la solicitud.
 * @var object $Merkur   Instancia de la clase Merkur para manejar las operaciones de la API.
 * @var mixed  $response Respuesta generada por las operaciones de la API.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Merkur;

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
    $Authenticate = $data->authorizePlayer->authorizationRequest;

    $AccessToken = $Authenticate->sessionToken;
    $Token = $Authenticate->sessionToken;

    $Merkur = new Merkur($Token, $AccessToken);

    $response = ($Merkur->Auth());
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}

if (strpos($URI, "balance") !== false) {
    $Balance = $data->getBalance->BalanceRequest;

    $Token = $Balance->sessionId;
    $externalGameSessionId = '';
    $accessToken = '';

    $Merkur = new Merkur($Token, $accessToken, $externalId);

    $response = ($Merkur->getBalance($gameId));
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}

if (strpos($URI, "deposit") !== false) {
    $reserve = $data->deposit->DepositRequest;

    $externalId = (string)'';
    $Token = (string)$reserve->sessionId;

    $gameId = (string)$reserve->gameId;

    $productId = (string)$reserve->productId;
    $transactionRef = (string)$reserve->transactionRef;
    $amount = (string)$reserve->amount;
    $currency = (string)$reserve->currency;
    $gameSessionId = (string)$reserve->gameSessionId;
    $contextId = (string)$reserve->contextId;
    $accessToken = (string)$reserve->accessToken;
    $gameRoundRef = (string)$reserve->gameRoundRef;
    $externalGameSessionId = (string)$reserve->externalGameSessionId;

    $Merkur = new Merkur($Token, $accessToken, $externalId);

    $datos = array(
        "externalId" => (string)$externalId,
        "productId" => (string)$productId,
        "transactionRef" => (string)$transactionRef,
        "amount" => (string)$amount,
        "currency" => (string)$currency,
        "gameId" => (string)$gameId,
        "gameSessionId" => (string)$gameSessionId,
        "contextId" => (string)$contextId,
        "accessToken" => (string)$accessToken,
        "gameRoundRef" => (string)$gameRoundRef,
        "externalGameSessionId" => (string)$externalGameSessionId
    );

    $response = ($Merkur->Debit($gameId, $gameRoundRef, "", $amount, $transactionRef, json_encode($datos)));
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}

if (strpos($URI, "release") !== false) {
    $release = $data;

    $externalId = (string)$release->externalId;
    $productId = (string)$release->productId;
    $transactionRef = (string)$release->transactionRef;
    $amount = (string)$release->amount;
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
    $gameRoundRef = (string)$release->gameRoundRef;
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


    $Merkur = new Merkur($Token, $accessToken, $externalId);

    $datos = array(
        "externalId" => (string)$externalId,
        "productId" => (string)$productId,
        "transactionRef" => (string)$transactionRef,
        "amount" => (string)$amount,
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
        "gameRoundRef" => (string)$gameRoundRef,
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
        $response = ($Merkur->Debit($gameId, $gameRoundRef, "", 0, "FS" . $transactionRef, json_encode($datos), true));
    }

    $response = ($Merkur->Credit($gameId, $gameRoundRef, "", $amount, $transactionRef, $isEnd, json_encode($datos)));
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}

if (strpos($URI, "cancelReserve") !== false) {
    $cancelReserve = $data;

    $externalId = (string)$cancelReserve->externalId;
    $productId = (string)$cancelReserve->productId;
    $transactionRef = (string)$cancelReserve->transactionRef;
    $amount = (string)$cancelReserve->amount;
    $currency = (string)$cancelReserve->currency;
    $accessToken = (string)$cancelReserve->accessToken;
    $Token = (string)$cancelReserve->username;
    $gameRoundRef = (string)$cancelReserve->gameRoundRef;
    $gameId = (string)$cancelReserve->gameId;
    $externalGameSessionId = (string)$cancelReserve->externalGameSessionId;

    $Merkur = new Merkur($Token, $accessToken, $externalId);

    $datos = array(
        "externalId" => (string)$externalId,
        "productId" => (string)$productId,
        "transactionRef" => (string)$transactionRef,
        "amount" => (string)$amount,
        "currency" => (string)$currency,
        "accessToken" => (string)$accessToken,
        "gameRoundRef" => (string)$gameRoundRef,
        "gameId" => (string)$gameId,
        "externalGameSessionId" => (string)$externalGameSessionId
    );

    $response = ($Merkur->Rollback($gameId, $gameRoundRef, "", $amount, $transactionRef, json_encode($datos)));
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}

