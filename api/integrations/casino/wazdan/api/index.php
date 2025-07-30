<?php
/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API
 * del casino 'Wazdan', incluyendo autenticación, gestión de fondos, apuestas,
 * devoluciones y cierre de juegos.
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
 * @var mixed $_ENV    Indica si la conexión global está habilitada ["enabledConnectionGlobal"]    .
 * @var mixed $_ENV    Configuración para habilitar el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"]  .
 * @var mixed $log     Variable utilizada para almacenar registros de actividad.
 * @var mixed $body    Contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data    Datos decodificados del cuerpo de la solicitud en formato JSON.
 * @var mixed $URI     URI de la solicitud actual.
 * @var mixed $headers Encabezados HTTP de la solicitud.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\ISoftBet;
use Backend\integrations\casino\Wazdan;

header('Content-type: application/json; charset=utf-8');
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

ini_set('display_errors', 'OFF');

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . " Body ";

$log = $log . trim(file_get_contents('php://input'));
$body = file_get_contents('php://input');

$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);
$URI = $_SERVER['REQUEST_URI'];


$headers = getallheaders();
if ($body != "") {
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    if ($ConfigurationEnvironment->isDevelopment()) {
        $secretKey = "";
    } else {
        $secretKey = "";
    }

    if (strpos($URI, "authenticate") !== false) {
        $ip = $data->ip;
        $token = $data->token;
        $gameId = $data->gameId;


        /* Procesamos */
        $Wazdant = new Wazdan($token);
        $response = ($Wazdant->Auth());


        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    }


    if (strpos($URI, "getFunds") !== false) {
        $id = $data->user->id;
        $skinId = $data->user->skinId;
        $token = $data->user->token;
        /* Procesamos */
        $Wazdant = new Wazdan($token);
        $response = ($Wazdant->getBalance());


        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    }

    if (strpos($URI, "returnWin") !== false) {
        $type = intval($data->type);
        $amount = doubleval($data->amount);
        $id = $data->user->id;
        $skinId = $data->user->skinId;
        $token = $data->user->token;

        $gameId = $data->gameId;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;

        $round_betTransactionId = $data->round->betTransactionId;
        $round_endRound = $data->round->endRound;
        $round_lastFreeSpin = $data->round->lastFreeSpin;
        $round_lastFreeRound = $data->round->lastFreeRound;

        //Objeto durante rondas gratis type = 1
        $freeRoundInfo_totalBetAmount = $data->freeRoundInfo->totalBetAmount;
        $freeRoundInfo_totalWinAmount = $data->freeRoundInfo->totalWinAmount;
        $freeRoundInfo_count = $data->freeRoundInfo->count;
        $freeRoundInfo_id = $data->freeRoundInfo->id;
        $freeRoundInfo_txId = $data->freeRoundInfo->txId;
        $freeRoundInfo_campaignId = $data->freeRoundInfo->campaignId;
        $freeRoundInfo_meta = $data->freeRoundInfo->meta;


        //Objeto solo para type = 4
        $cashDropInfo_promotionId = $data->cashDropInfo->promotionId;
        $cashDropInfo_cashDropId = $data->cashDropInfo->cashDropId;
        $cashDropInfo_prizeId = $data->cashDropInfo->prizeId;
        $cashDropInfo_meta = $data->cashDropInfo->meta;

        $datos = $data;

        /* Procesamos */
        $Wazdant = new Wazdan($token);

        if ($type == 0) {
            $response = $Wazdant->Credit($gameId, $amount, $roundId, $transactionId, json_encode($datos));
        }

        if ($type == 1) {
            $response = ($Wazdant->Debit($gameId, $freeRoundInfo_totalBetAmount, $roundId, "FS" . $transactionId, json_encode($datos), true));
            $response = $Wazdant->Credit($gameId, $amount, $roundId, $transactionId, json_encode($datos));
        }
        if ($type == 2) {
            $response = $Wazdant->Credit($gameId, $amount, $roundId, $transactionId, json_encode($datos));
        }

        if ($type == 3) {
            $response = $Wazdant->Credit($gameId, $amount, $roundId, $transactionId, json_encode($datos));
        }

        if ($type == 4) {
            $response = $Wazdant->Credit($gameId, $amount, $roundId, $transactionId, json_encode($datos));
        }


        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    }

    if (strpos($URI, "getStake") !== false) {
        $id = $data->user->id;
        $skinId = $data->user->skinId;
        $token = $data->user->token;
        $amount = doubleval($data->amount);
        $freeSpin = $data->freeSpin;
        $freeRound = $data->freeRound;
        $gameId = $data->gameId;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;

        $freeRoundInfo_betAmount = doubleval($data->freeRoundInfo->betAmount);
        $freeRoundInfo_id = $data->freeRoundInfo->id;
        $freeRoundInfo_txId = $data->freeRoundInfo->txId;
        $freeRoundInfo_campaignId = $data->freeRoundInfo->campaignId;


        $datos = $data;

        /* Procesamos */

        $Wazdant = new Wazdan($token);

        if ($freeRound == true) {
            $response = ($Wazdant->Debit($gameId, $freeRoundInfo_betAmount, $freeRoundInfo_id, $transactionId, json_encode($datos), $freeSpin));

            $log = "";
            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);
            print_r($response);
        }


        $response = ($Wazdant->Debit($gameId, $amount, $roundId, $transactionId, json_encode($datos), $freeSpin));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    }


    if (strpos($URI, "rollbackStake") !== false) {
        $id = $data->user->id;
        $skinId = $data->user->skinId;
        $token = $data->user->token;
        $amount = doubleval($data->amount);
        $bonusAmount = doubleval($data->bonusAmount);
        $gameId = $data->gameId;
        $roundId = $data->roundId;
        $originalTransactionId = $data->originalTransactionId;
        $transactionId = $data->transactionId;


        $datos = $data;

        /* Procesamos */

        $Wazdant = new Wazdan($token);
        $response = ($Wazdant->Rollback($amount, $roundId, $transactionId, $id, json_encode($datos), $originalTransactionId));
        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    }

    if (strpos($URI, "gameClose") !== false) {
        $id = $data->user->id;
        $skinId = $data->user->skinId;
        $token = $data->user->token;

        $Wazdant = new Wazdan($token);

        $response = ($Wazdant->gameClose());
        $log = $log . "/" . time();

        $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    }
}


