<?php

/**
 * Este archivo contiene un script para procesar y manejar las integraciones de la API del casino 'Pragmatic'.
 * Proporciona funcionalidades para autenticar, consultar balances, realizar transacciones de juego,
 * y manejar operaciones relacionadas con bonos, jackpots y promociones.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed   $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var boolean $_ENV         Indica si el modo de depuración está habilitado.
 * @var integer $_ENV         Controla si la conexión global está habilitada.
 * @var string  $_ENV         Configuración para el tiempo de espera de bloqueo.
 * @var string  $URI          URI de la solicitud actual.
 * @var mixed   $body         Cuerpo de la solicitud en formato JSON.
 * @var mixed   $data         Datos decodificados del cuerpo de la solicitud.
 * @var string  $hashOriginal Hash original enviado en la solicitud.
 * @var mixed   $response     Respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Pragmatic;

header('Content-type: application/json');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$URI = $_SERVER['REQUEST_URI'];
$URI = strtolower($URI);

if ($_REQUEST['bonusCode'] != '') {
    $_REQUEST['bonusCode'] = preg_replace('/[^(\x20-\x7F)]*/', '', $_REQUEST['bonusCode']);
}

$body = json_encode($_REQUEST);

if ($body != "") {
    $data = json_decode($body);
}

ksort($_REQUEST);
$hashOriginal = $_REQUEST["hash"];

if (true) {
    if (strpos($URI, "authenticate") !== false) {
        $hash = $_REQUEST["hash"];
        $token = $_REQUEST["token"];
        $providerId = $_REQUEST["providerId"];
        $ipAddress = $_REQUEST["ipAddress"];
        $hash = $data->hash;
        $token = $data->token;
        $providerId = $data->providerId;
        $ipAddress = $data->ipAddress;

        /* Procesamos */
        $Pragmatic = new Pragmatic($token, $hash, "", $hashOriginal);
        $response = $Pragmatic->Auth();
    } elseif (strpos($URI, "balance") !== false) {
        $hash = $_REQUEST["hash"];
        $token = $_REQUEST["token"];
        $providerId = $_REQUEST["providerId"];
        $ipAddress = $_REQUEST["ipAddress"];
        $externalPlayerId = strpos($_REQUEST["externalPlayerId"], "_") !== false ? strstr($_REQUEST["externalPlayerId"], "_", true) : $_REQUEST["externalPlayerId"];
        $PlayerId = strpos($data->userId, "_") !== false ? strstr($data->userId, "_", true) : $data->userId;

        /* Procesamos */
        $Pragmatic = new Pragmatic($token, $hash, $externalPlayerId, $hashOriginal);
        $response = $Pragmatic->getBalance($PlayerId);
    } elseif (strpos($URI, "game-transactions") !== false) {
        $transactionId = explode("game-transactions/", $URI);
        $transactionId = $transactionId[1];

        $action = $data->action;

        if ($action == "CANCEL") {
            $token = strpos($data->playerId, "_") !== false ? strstr($data->playerId, "_", true) : $data->playerId;

            $rollbackAmount = 0;
            $GameCode = $data->gameCode;
            $roundId = $data->roundId;
            $transactionId = $data->transactionId;
            $datos = $data;

            /* Procesamos */
            $Pragmatic = new Pragmatic($token, $sign, "", $hashOriginal);
            $response = $Pragmatic->Rollback($rollbackAmount, $roundId, $transactionId, $player, json_encode($datos));
        }
    } else {
        $token = strpos($data->playerId, "_") !== false ? strstr($data->playerId, "_", true) : $data->playerId;
        $GameCode = $data->gameId;
        $PlayerId = strpos($data->userId, "_") !== false ? strstr($data->userId, "_", true) : $data->userId;
        $RoundId = $data->roundId;
        $transactionId = $data->reference;
        $campaignType = $data->campaignType;
        $amount = floatval($data->amount);

        $userId = strpos($data->userId, "_") !== false ? strstr($data->userId, "_", true) : $data->userId;

        $hash = $_REQUEST["hash"];
        $token = $_REQUEST["token"];

        $datos = $data;

        if ($GameCode == "" || $GameCode == null) {
            $GameCode = match ($campaignType) {
                "T" => 'T_TPG',
                "CJP" => 'CJP_JPG',
                "CB" => 'CB_DPG',
                default => 'DE_00',
            };
        }

        $isbonus = false;
        if ($data->bonusCode != '') {
            $isbonus = true;
        }

        if (strpos($URI, "bet")) {
            $Pragmatic = new Pragmatic($token, $hash, $userId, $hashOriginal);
            $response = $Pragmatic->Debit($GameCode, $amount, $RoundId, $transactionId, json_encode($datos), $isbonus);
        } elseif (strpos($URI, "result")) {
            $promoWinAmount = round($data->promoWinAmount, 2);

            if ($promoWinAmount != "" && $promoWinAmount > 0) {
                $amount = $promoWinAmount + $amount;
            }

            $amount = number_format((float)$amount, 2, '.', '');

            $Pragmatic = new Pragmatic($token, $hash, $userId, $hashOriginal);
            $response = $Pragmatic->Credit($GameCode, $amount, $RoundId, $transactionId, json_encode($datos), $isbonus);
        } elseif (strpos($URI, "bonuswin")) {
            $hash = $_REQUEST["hash"];
            $token = $_REQUEST["token"];
            $transactionId = $_REQUEST["reference"];
            $RoundId = $_REQUEST["bonusCode"];
            $userId = strpos($_REQUEST["userId"], "_") !== false ? strstr($_REQUEST["userId"], "_", true) : $_REQUEST["userId"];

            if (strpos($RoundId, 'BINGO_FSG_') !== false) {
                $amount = $_REQUEST["amount"];
            } else {
                $RoundId = $transactionId;
                $amount = 0;
            }

            $datos = $data;
            $Pragmatic = new Pragmatic($token, $hash, $userId, $hashOriginal);
            $response = $Pragmatic->Debit($GameCode, 0, 'R_' . $RoundId, "D" . $transactionId, json_encode($datos), true);
            $response = $Pragmatic->Credit($GameCode, $amount, 'R_' . $RoundId, $transactionId, json_encode($datos), true);
        } elseif (strpos($URI, "jackpotwin")) {
            $RoundId = "jackpot" . $transactionId;
            $Pragmatic = new Pragmatic($token, $hash, $userId, $hashOriginal);

            $response = $Pragmatic->Debit($GameCode, 0, $RoundId, "D" . $transactionId, json_encode($datos), false, true);
            $response = $Pragmatic->Credit($GameCode, $amount, $RoundId, $transactionId, json_encode($datos));
        } elseif (strpos($URI, "promowin")) {
            $RoundId = "camp" . $transactionId;
            $Pragmatic = new Pragmatic($token, $hash, $userId, $hashOriginal);

            $response = $Pragmatic->Debit($GameCode, 0, $RoundId, "D" . $transactionId, json_encode($datos), $isbonus);
            $response = $Pragmatic->Credit($GameCode, $amount, $RoundId, $transactionId, json_encode($datos), $isbonus);
        } elseif (strpos($URI, "endround")) {
            $Pragmatic = new Pragmatic($token, $hash, $userId, $hashOriginal);
            $response = $Pragmatic->EndRound($GameCode, $RoundId, json_encode($datos));
        } elseif (strpos($URI, "refund")) {
            $Pragmatic = new Pragmatic($token, $hash, $userId, $hashOriginal);
            $response = $Pragmatic->Rollback($amount, $RoundId, $transactionId, $PlayerId, json_encode($datos));
        }
    }

    print_r($response);
}
