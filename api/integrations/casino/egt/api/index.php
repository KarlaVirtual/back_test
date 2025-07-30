<?php

/**
 * Index de la api de casino 'egt'
 *
 *
 * @package ninguno
 * @author  Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 18.10.17
 *
 */
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Egt;

header('Content-Type: application/json');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$URI = $_SERVER['REQUEST_URI'];

$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);
}

if (!function_exists('getallheaders')) {
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
$headerChecksum = $headers['HTTP_X_CHECKSUM'] ?? $_SERVER['HTTP_X_CHECKSUM'] ?? null;
$headerFields = $headers['HTTP_X_CHECKSUM_FIELDS'] ?? $_SERVER['HTTP_X_CHECKSUM_FIELDS'] ?? null;


if (true) {

    if (strpos($URI, "authenticate") !== false) {
        $requestId = $data->requestId;
        $timestamp = $data->timestamp;
        $playerId = $data->playerId;
        $defenceCode = $data->defenceCode;
        $sessionId = $data->sessionId;

        $Egt = new Egt($playerId, $defenceCode, $sessionId);
        $Checksum = $Egt->validateChecksum($data, $headerChecksum, $headerFields);
        $responseChecksum = json_decode($Checksum, true);

        if ($responseChecksum["statusCode"] == "ERR_INTEGRITY_CHECK_FAILED") {
            $response = $Checksum;
        } else {
            $response = $Egt->Auth();
        }
    }

    if (strpos($URI, "defence-code") !== false) {

        $requestId = $data->requestId;
        $timestamp = $data->timestamp;
        $playerId = $data->playerId;
        $sessionId = $data->sessionId;
        $gameKey = $data->gameKey;

        $Egt = new Egt($playerId, "", $sessionId);
        $response = $Egt->refreshToken();
    }

    if (strpos($URI, "terminate") !== false) {

        $requestId = $data->requestId;
        $timestamp = $data->timestamp;
        $playerId = $data->playerId;
        $defenceCode = $data->defenceCode;
        $sessionId = $data->sessionId;
        $gameKey = $data->gameKey;

        $Egt = new Egt($playerId, "", "");
        $response = $Egt->terminate();
    }

    if (strpos($URI, "balance") !== false) {

        $requestId = $data->requestId;
        $timestamp = $data->timestamp;
        $playerId = $data->playerId;
        $currency = $data->currency;
        $sessionId = $data->sessionId;
        $gameKey = $data->gameKey;

        $Egt = new Egt($playerId, "", $sessionId);
        $response = $Egt->Balance($currency);
    }

    if (strpos($URI, "api/withdraw") !== false) {

        $requestId = $data->requestId;
        $timestamp = $data->timestamp;
        $playerId = $data->playerId;
        $gameKey = $data->gameKey;
        $transferId = $data->transferId;
        $roundNumber = $data->roundNumber;
        $roundCompleted = $data->roundCompleted;
        $sessionId = $data->sessionId;
        $amount = $data->amount;
        $currency = $data->currency;
        $reason = $data->reason;
        $bonusCode = $data->giftSpin->bonusCode;
        $campaignId = $data->giftSpin->campaignId;
        $totalSpins = $data->giftSpin->totalSpins;
        $remainingSpins = $data->giftSpin->remainingSpins;

        $Egt = new Egt($playerId, "", $sessionId);

        if ($bonusCode != "") {
            $response = $Egt->Debit($gameKey, 0, $roundNumber, $transferId . "_FS", $data, true, $roundCompleted, $currency);
        } else {
            $response = $Egt->Debit($gameKey, $amount / 100, $roundNumber, $transferId, $data, "", $roundCompleted, $currency);
        }
    }

    if (strpos($URI, "reverse/withdraw") !== false) {

        $requestId = $data->requestId;
        $timestamp = $data->timestamp;
        $sessionId = $data->sessionId;
        $playerId = $data->playerId;
        $transferId = $data->transferId;
        $referenceId = $data->referenceId;
        $roundNumber = $data->roundNumber;

        $Egt = new Egt($playerId, "", "");
        $response = $Egt->Rollback($roundNumber, $referenceId);
    }

    if (strpos($URI, "deposit") !== false) {

        $requestId = $data->requestId;
        $timestamp = $data->timestamp;
        $playerId = $data->playerId;
        $gameKey = $data->gameKey;
        $transferId = $data->transferId;
        $roundNumber = $data->roundNumber;
        $roundCompleted = $data->roundCompleted;
        $sessionId = $data->sessionId;
        $amount = $data->amount;
        $currency = $data->currency;
        $reason = $data->reason;

        $bonusCode = $data->giftSpin->bonusCode;
        $campaignId = $data->giftSpin->campaignId;
        $totalSpins = $data->giftSpin->totalSpins;
        $remainingSpins = $data->giftSpin->remainingSpins;

        foreach ($data->jackpotWins as $jackpot) {
            $jackpotType = $jackpot->type;
            $jackpotName = $jackpot->jackpotName;
            $levelName = $jackpot->levelName;
            $jackpotAmount = $jackpot->amount;
            $winId = $jackpot->winId;
            $deduction = $jackpot->deduction;
        }

        $Egt = new Egt($playerId, "", "");

        $totalJackpotAmount = 0;
        $totalDeduction = 0;

        foreach ($data->jackpotWins as $jackpot) {
            $totalJackpotAmount += $jackpot->amount;
            $totalDeduction += $jackpot->deduction;
        }

        $totalAmount = $amount - $totalDeduction;

        $response = $Egt->Credit($gameKey, $totalAmount / 100, $roundNumber, $transferId, $data, $isBonus, $roundCompleted);
    }
}

print_r($response);
