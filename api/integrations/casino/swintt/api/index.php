<?php
/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la API de casino 'SWINTT'.
 * Incluye operaciones como validación de jugadores, retiros, depósitos, consulta de saldo y reversión de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2024-10-31
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV                     Variable superglobal que contiene información del entorno de ejecución.
 * @var mixed $URI                      Almacena la URI de la solicitud actual.
 * @var mixed $log                      Variable utilizada para almacenar información de registro (logs).
 * @var mixed $body                     Contiene el cuerpo de la solicitud HTTP.
 * @var mixed $data                     Almacena los datos decodificados del cuerpo de la solicitud en formato JSON.
 * @var mixed $ConfigurationEnvironment Objeto que maneja la configuración del entorno.
 * @var mixed $response                 Almacena la respuesta generada por las operaciones realizadas.
 */

$_ENV["enabledConnectionGlobal"] = 1;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Swintt;

$URI = $_SERVER['REQUEST_URI'];


$log = "\r\n" . "-------------Request------------" . "\r\n";
$log = $log . (http_build_query($_REQUEST));
$log = $log . trim(file_get_contents('php://input'));

$body = file_get_contents('php://input');
$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);

if ($body != "") {
    header('Content-type: application/json');
    $data = json_decode($body);
}

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $URI;
$log = $log . trim(file_get_contents('php://input'));

$log = time();

$ConfigurationEnvironment = new ConfigurationEnvironment();

if (strpos($URI, "player/validate") !== false) {
    $operatorId = $data->operatorId;
    $integrationKey = $data->integrationKey;
    $gameId = $data->gameId;
    $token = $data->token;

    /* Procesamos */
    $Swintt = new Swintt($token, "");
    $response = ($Swintt->Auth());
}

if (strpos($URI, "player/withdraw") !== false) {
    $integrationKey = $data->integrationKey;
    $operatorId = $data->operatorId;
    $playerId = $data->playerId;
    $sessionId = $data->sessionId;
    $partnerSessionId = $data->partnerSessionId;
    $token = $data->launchToken;
    $amount = $data->amount;
    $currencyId = $data->currencyId;
    $gameId = $data->gameId;
    $roundId = $data->roundId;
    $transactionId = $data->transactionId;
    $jackpotsId = $data->jackpots->id;
    $bonusId = $data->bonusId;
    $freeSpinPackageId = $data->freeSpinPackageId;
    /* Procesamos */
    $Swintt = new Swintt($token, $playerId);
    $response = ($Swintt->debit($gameId, $amount, $roundId, $transactionId, $data));
}

if (strpos($URI, "player/deposit") !== false) {
    $integrationKey = $data->integrationKey;
    $operatorId = $data->operatorId;
    $playerId = $data->playerId;
    $sessionId = $data->sessionId;
    $partnerSessionId = $data->partnerSessionId;
    $token = $data->launchToken;
    $amount = $data->amount;
    $currencyId = $data->currencyId;
    $gameId = $data->gameId;
    $roundId = $data->roundId;
    $transactionId = $data->transactionId;
    $jackpotsId = $data->jackpots->id;
    $bonusId = $data->bonusId;
    $freeSpinPackageId = $data->freeSpinPackageId;
    $isRoundCompleted = $data->isRoundCompleted;
    /* Procesamos */
    $Swintt = new Swintt($token, $playerId);
    $response = ($Swintt->credit($gameId, $amount, $roundId, $transactionId, $data, $isRoundCompleted));
}

if (strpos($URI, "player/balance") !== false) {
    $operatorId = $data->operatorId;
    $integrationKey = $data->integrationKey;
    $playerId = $data->playerId;
    $token = $data->partnerSessionId;
    $launchToken = $data->launchToken;
    $currencyId = $data->currencyId;

    /* Procesamos */
    $Swintt = new Swintt($launchToken, $playerId);
    $response = ($Swintt->Balance());
}

if (strpos($URI, "transaction/rollback") !== false) {
    $operatorId = $data->operatorId;
    $integrationKey = $data->integrationKey;
    $gameId = $data->gameId;
    $playerId = $data->playerId;
    $roundId = $data->roundId;
    $transactionId = $data->transactionId;
    $partnerTransactionId = $data->partnerTransactionId;

    /* Procesamos */
    $Swintt = new Swintt("", $playerId);
    $response = ($Swintt->Rollback($gameId, "", $roundId, $transactionId, $data));
}

$log = $log . "\r\n" . "-------------Response------------" . "\r\n";
$log = $log . ($response);

print_r($response);


















