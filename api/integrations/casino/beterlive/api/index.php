<?php

/**
 * Este archivo contiene un script para procesar solicitudes relacionadas con la integración
 * de un casino llamado 'Beterlive'. Maneja operaciones como autenticación de sesión,
 * apuestas, confirmación de juegos y cancelaciones, asegurando la validación de firmas
 * y la comunicación con la API correspondiente.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    ninguna
 * @since      2025-05-09
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Beterlive;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$header = getallheaders();
header('Content-Type: application/json');

$_ENV["enabledConnectionGlobal"] = 1;

$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];

$URIRECURSO = explode("/", $URI);

$URL = $URIRECURSO[oldCount($URIRECURSO) - 1];

$data = json_decode($body);

$casino = $data->casino;
$username = $data->username;
$token = $data->sessionToken;

if ($data->gameId != null) {
    $gameId = $data->gameId;
} else {
    $gameId = 'DF_BETL';
}

$Beterlive = new Beterlive($token, $casino, $username);
$response = $Beterlive->getSecret($gameId);

$sing = hash("sha256", $response . $body, false);

$signature = $header["X-REQUEST-SIGN"];
if ($signature == "") {
    $signature = $header["x-request-sign"];
}
if ($signature == "") {
    $signature = $header["X-Request-Sign"];
}

if ($sing == $signature) {
    if ($body != "") {
        $data = json_decode($body);

        if (strpos($URL, "sessionInfo") !== false) {
            $casino = $data->casino;
            $username = $data->username;
            $token = $data->sessionToken;

            /* Procesamos */
            $Beterlive = new Beterlive($token, $casino, $username);
            $response = ($Beterlive->Auth());
        }


        if (strpos($URL, "bet") !== false) {
            $casino = $data->casino;
            $username = $data->username;
            $token = $data->sessionToken;
            $gameCode = $data->gameCode;

            if ($data->gameId != null) {
                $gameId = $data->gameId;
            } else {
                $gameId = 'DF_BETL';
            }

            $transactionId = $data->transactionId;
            $amount = floatval($data->amount) / 100;

            $freespin = false;
            $datos = $data;
            /* Procesamos */
            $Beterlive = new Beterlive($token, $casino, $username);
            $response = ($Beterlive->Debit($gameId, $amount, $gameCode, $transactionId, json_encode($datos), $freespin));
        }

        if (strpos($URL, "confirmGame") !== false) {
            $casino = $data->casino;
            $username = $data->username;
            $gameCode = $data->gameCode;
            $transactionId = $data->transactionId;
            $totalBetAmount = floatval($data->totalBetAmount) / 100;
            $totalWinAmount = floatval($data->totalWinAmount) / 100;
            $token = $data->sessionToken;
            $gameId = $data->gameId;

            $datos = $data;

            /* Procesamos */

            $Beterlive = new Beterlive($token, $casino, $username);
            $response = $Beterlive->Credit($gameId, $totalWinAmount, $gameCode, $transactionId, json_encode($datos), $totalBetAmount);
        }


        if (strpos($URL, "cancel") !== false) {
            $casino = $data->casino;
            $username = $data->username;
            $gameCode = $data->gameCode;
            $transactionId = $data->transactionId;
            $amount = floatval($data->amount) / 100;
            $token = $data->sessionToken;
            $gameId = $data->gameId;


            $datos = $data;

            $Beterlive = new Beterlive($token, $casino, $username);
            $response = $Beterlive->Rollback($amount, $gameCode, $transactionId, $username, json_encode($datos));
        }
    } else {
        http_response_code(422);
    }
} else {
    http_response_code(403);
}

print_r($response);
