<?php
/**
 * Este archivo contiene un script para manejar las operaciones de la API del casino 'playngo',
 * incluyendo autenticación, consulta de saldo, reservas, liberaciones y cancelaciones de reservas.
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
 * @var mixed $_ENV     Indica si la conexión global está habilitada.
 * @var mixed $_ENV     Configura el tiempo de espera para el bloqueo de la base de datos.
 * @var mixed $URI      Contiene la URI de la solicitud actual y el método HTTP utilizado.
 * @var mixed $body     Almacena el cuerpo de la solicitud en formato de texto.
 * @var mixed $method   Variable para almacenar el método de la solicitud.
 * @var mixed $data     Contiene los datos de la solicitud en formato XML.
 * @var mixed $log      Almacena información de registro para depuración.
 * @var mixed $response Almacena la respuesta generada por las operaciones de la API.
 */

ini_set('display_errors', 'OFF');


require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Playngo;

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

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

if (strpos($URI, "authenticate") !== false || $data->getName() == "authenticate") {
    $Authenticate = $data;

    $AccessToken = $Authenticate->accessToken;
    $Token = $Authenticate->username;

    $Playngo = new Playngo($Token, $AccessToken, "", "authenticate");

    $response = ($Playngo->Auth());
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}

if (strpos($URI, "balance") !== false || $data->getName() == "balance") {
    $Balance = $data;

    $externalId = intval($Balance->externalId);
    $productId = $Balance->productId;
    $currency = $Balance->currency;
    $gameId = $Balance->gameId;
    $accessToken = $Balance->accessToken;
    $Token = $Balance->username;
    $externalGameSessionId = $Balance->externalGameSessionId;

    $Playngo = new Playngo($Token, $accessToken, $externalId, "balance");

    $response = ($Playngo->getBalance($gameId));
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}

if (strpos($URI, "reserve") !== false || $data->getName() == "reserve") {
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

    $Playngo = new Playngo($Token, $accessToken, $externalId, "reserve");

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

if (strpos($URI, "release") !== false || $data->getName() == "release") {
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

    if ($transactionId == "500605780067" || $transactionId == "500689476088" || $transactionId == "500679237836") {
        print_r(
            '<?xml version="1.0"?>
<release><externalTransactionId>TT' . $transactionId . '</externalTransactionId><real>1</real><currency>PEN</currency><statusCode>0</statusCode><statusMessage>ok</statusMessage></release>
'
        );
        exit();
    }
    if (floatval($real) <= 0 && ($roundId == '0' || $roundId == '')) {
        print_r(
            '<?xml version="1.0"?>
<release><externalTransactionId>TT' . $transactionId . '</externalTransactionId><real>1</real><currency>PEN</currency><statusCode>0</statusCode><statusMessage>ok</statusMessage></release>
'
        );
        exit();
    }

    $Playngo = new Playngo($Token, $accessToken, $externalId, "release");

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

    if ($roundId == '0') {
        $roundId = 'R0' . $transactionId;
        $response = ($Playngo->Debit($gameId, $roundId, "", 0, "R0" . $transactionId, json_encode($datos), true));
        $transactionId = "CREDIT" . "R0" . $transactionId;
    }

    $response = ($Playngo->Credit($gameId, $roundId, "", $real, $transactionId, $isEnd, json_encode($datos)));
    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);
    //Save string to log, use FILE_APPEND to append.

    //fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

    print_r($response);
}


if (strpos($URI, "cancelReserve") !== false || $data->getName() == "cancelReserve") {
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

    $Playngo = new Playngo($Token, $accessToken, $externalId, "cancelReserve");

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
    //Save string to log, use FILE_APPEND to append.

    //fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

    print_r($response);
}

