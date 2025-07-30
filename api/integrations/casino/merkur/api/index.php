<?php

/**
 * Este archivo contiene la implementación de la API para la integración con el casino 'Merkur'.
 * Proporciona endpoints para manejar solicitudes relacionadas con login, balance, apuestas, ganancias y reversión.
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
 * @var mixed $_ENV     Indica si la conexión global está habilitada ["enabledConnectionGlobal"].
 * @var mixed $_ENV     Configuración para habilitar el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var mixed $URI      URI de la solicitud actual junto con el método HTTP.
 * @var mixed $body     Contenido del cuerpo de la solicitud HTTP.
 * @var mixed $method   Método HTTP utilizado en la solicitud.
 * @var mixed $headers  Encabezados HTTP de la solicitud.
 * @var mixed $log      Variable utilizada para almacenar información de registro.
 * @var mixed $data     Datos decodificados del cuerpo de la solicitud.
 * @var mixed $response Respuesta generada por las operaciones de la API.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Merkur;

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');

$_ENV["enabledConnectionGlobal"] = 1;

$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$URI = $_SERVER['REQUEST_URI'] . " C " . $_SERVER['REQUEST_METHOD'];
$body = trim(file_get_contents('php://input'));
$method = "";

if ($body != "") {
    Header('Content-type: text/xml');
    $data = json_decode($body);
}


if ( ! function_exists('getallheaders')) {
    /**
     * Obtiene todos los encabezados de la solicitud HTTP.
     *
     * @return array Un arreglo asociativo con los encabezados de la solicitud.
     */
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

$headers = getallheaders();

$log = "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . $URI;
$log = $log . trim(file_get_contents('php://input'));
$log = $log . json_encode($headers);

$log = time();


if ($data->requestType == "login") {
    $Authenticate = $data->authorizePlayer->authorizationRequest;

    $AccessToken = $data->playerId;
    $Token = $data->startToken;

    $Merkur = new Merkur($Token, $AccessToken);

    $response = ($Merkur->Auth());
}

if ($data->requestType == "balance") {
    $Token = $headers['edict-session-token'];

    $externalGameSessionId = '';
    $accessToken = '';

    $Merkur = new Merkur($Token, $accessToken);

    $response = ($Merkur->getBalance());
}

if ($data->requestType == "stake") {
    $reserve = $data;

    $externalId = (string)'';
    $Token = $headers['edict-session-token'];

    $gameId = (string)$reserve->gameKey;

    $transactionRef = (string)$reserve->transactionReference;
    $amount = (string)$reserve->amount->amount;
    $currency = (string)$reserve->amount->currency;

    $gameRoundRef = (string)$reserve->gameRoundReference;
    $externalGameSessionId = (string)$reserve->externalGameSessionId;

    $Merkur = new Merkur($Token, $accessToken, $externalId);

    $datos = array(
        "externalId" => (string)$externalId,
        "transactionRef" => (string)$transactionRef,
        "amount" => (string)$amount,
        "currency" => (string)$currency,
        "gameId" => (string)$gameId,
        "gameRoundRef" => (string)$gameRoundRef,
        "externalGameSessionId" => (string)$externalGameSessionId
    );

    $free = false;

    $response = ($Merkur->Debit($gameId, $gameRoundRef, $amount / 100, $transactionRef, json_encode($datos), $free));
}

if ($data->requestType == "winnings") {
    $reserve = $data;

    $externalId = (string)'';
    $Token = $headers['edict-session-token'];

    $gameId = (string)$reserve->gameKey;

    $transactionRef = (string)$reserve->transactionReference;
    $amount = (string)$reserve->amount->amount;
    $currency = (string)$reserve->amount->currency;

    $gameRoundRef = (string)$reserve->gameRoundReference;
    $externalGameSessionId = (string)$reserve->externalGameSessionId;
    $status = (string)$reserve->casinoFreeSpins->status;
    $Merkur = new Merkur($Token, $accessToken, $externalId);
    $datos = $data;

    $free = false;

    if (strpos($status, 'CASINO_FREESPIN_RUNNING') !== false) {
        $free = true;
        $amount = (string)$reserve->casinoFreeSpins->amount->amount;

        $response = ($Merkur->Debit($gameId, $gameRoundRef, 0, $transactionRef, json_encode($datos), $free));
        $response = ($Merkur->Credit($gameId, $gameRoundRef, $amount / 100, "FS_" . $transactionRef, false, json_encode($datos), $free));
    } elseif (strpos($status, 'CASINO_FREESPIN_FINISHED') !== false) {
        $free = true;

        $response = ($Merkur->Debit($gameId, $gameRoundRef, 0, $transactionRef, json_encode($datos), $free));
        $response = ($Merkur->Credit($gameId, $gameRoundRef, 0, "FS_" . $transactionRef, false, json_encode($datos), $free));
    } else {
        $response = ($Merkur->Credit($gameId, $gameRoundRef, $amount / 100, $transactionRef, false, json_encode($datos), $free));
    }
}

if ($data->requestType == "rollback") {
    $cancelReserve = $data;
    $externalId = (string)'';
    $Token = $headers['edict-session-token'];

    $gameId = (string)$cancelReserve->gameKey;
    $transactionRef = (string)$cancelReserve->transactionReference;

    $Merkur = new Merkur($Token, $accessToken, $externalId);

    $datos = $data;

    $response = ($Merkur->Rollback($gameId, $gameRoundRef, "", $amount / 100, $transactionRef, json_encode($datos)));
}

print_r($response);

$log = "";
$log = $log . "/" . time();

$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($response);
