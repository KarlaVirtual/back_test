<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API
 * del casino 'Playtech', incluyendo autenticación, balance, transacciones y otros
 * métodos relacionados con el juego y torneos.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed  $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var bool   $_ENV     Indica si el modo de depuración está habilitado.
 * @var int    $_ENV     Controla si la conexión global está habilitada.
 * @var string $_ENV     Configuración para el tiempo de espera de bloqueo.
 * @var string $body     Contiene el cuerpo de la solicitud en formato JSON.
 * @var object $data     Objeto decodificado del cuerpo de la solicitud.
 * @var string $URI      URI de la solicitud actual.
 * @var float  $curTime  Marca de tiempo inicial para medir el tiempo consumido.
 * @var mixed  $response Respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Playtech;

header('Content-type: application/json; charset=utf-8');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$body = file_get_contents('php://input');

$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);
$URI = $_SERVER['REQUEST_URI'];

$curTime = microtime(true);

$timeConsumed = round(microtime(true) - $curTime, 3) * 1000;

$t = microtime(true);
$micro = sprintf("%06d", ($t - floor($t)) * 1000000);
$d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));

if ($body != "") {
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $URI = explode('/', $URI);
    $URI = $URI[count($URI) - 1];

    //Este método se comparte con Póker
    if ($URI == "authenticate") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = ($Playtech->Auth());
    }

    //Este método se comparte con Póker
    if ($URI == "getbalance") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = ($Playtech->getBalance());
    }

    if (strpos($URI, 'gameroundresult') !== false) {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $roundID = $data->gameRoundCode;
        $transactionId = $data->pay->transactionCode;
        $transactionDate = $data->pay->transactionDate;
        $Amount = $data->pay->amount;
        $type = $data->pay->type;
        $game_code = $data->gameCodeName;
        if ($data->liveTableDetails != null) {
            $game_code = $data->liveTableDetails->tableId;
        }
        $relatedTransCode = $data->pay->relatedTransactionCode;
        $internalFundChanges = $data->internalFundChanges;
        $liveTableDetails = $data->liveTableDetails;
        $bonusChanges = $data->bonusChanges;

        $isFreeSpin = false;
        if ($data->pay != null && $data->pay->bonusWinningsInfo != null) {
            $isFreeSp = $data->pay->bonusWinningsInfo->freeSpinWinning;
            $isGolden = $data->pay->bonusWinningsInfo->goldenChipWinnings;
            if (oldCount($isFreeSp) > 0 || oldCount($isGolden) > 0) {
                $isFreeSpin = true;
            }
        }
        $isbonus = "";

        if ($data->pay != null && is_array($data->pay->internalFundChanges)) {
            $isbonus = $data->pay->internalFundChanges[0]->type;
        }
        if ($isbonus == "BONUS") {
            $Amount = $data->pay->amount;
        } else {
            $Amount = $data->pay->amount;
        }

        $datos = $data;

        if ($type == '') {
            $type = 'NOWIN';
        }

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        if ($type == "WIN") {
            $response = $Playtech->Credit($game_code, $Amount, $roundID, $transactionId, json_encode($datos), $isFreeSpin);
        } elseif ($type == "NOWIN") {
            $response = $Playtech->Credit($game_code, 0, $roundID, $transactionId, json_encode($datos), $isFreeSpin);
        } elseif ($type == 'REFUND') {
            $response = $Playtech->Rollback($Amount, $roundID, $relatedTransCode, $username, json_encode($datos), false);
        }
    }

    if ($URI == "bet") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $roundID = $data->gameRoundCode;
        $transactionId = $data->transactionCode;
        $transactionDate = $data->transactionDate;
        $DebitAmount = $data->amount;
        $game_code = $data->gameCodeName;
        if ($data->liveTableDetails != null) {
            $game_code = $data->liveTableDetails->tableId;
        }

        $isFreeSpin = false;
        if ($data->internalFundChanges != null) {
            $isFreeSp = $data->internalFundChanges[0]->type;
            if ($isFreeSp == 'BONUS' && $DebitAmount == '0') {
                $isFreeSpin = true;
            }
        }

        $liveTableDetails = $data->liveTableDetails;
        $bonusChanges = $data->bonusChanges;
        $datos = $data;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username, $roundID);
        $response = ($Playtech->Debit($game_code, $DebitAmount, $roundID, $transactionId, json_encode($datos), $isFreeSpin));
    }

    //Este método se comparte con Póker
    if ($URI == "submitdialog") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $dialogId = $data->dialogId;
        $realityCheckChoice = $data->realityCheckChoice;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = ($Playtech->submitdialog());
    }

    if ($URI == "getbalancebycontext") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $gameCodeName = $data->gameContext->gameCodeName;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = ($Playtech->getBalance());
    }

    if ($URI == "keepalive") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $timeFromLastAction = $data->timeFromLastAction;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = ($Playtech->keepAlive());
    }

    if ($URI == "logout") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = ($Playtech->logout());
    }

    //Este método se comparte con Póker
    if ($URI == "transferfunds") {
        // Preguntar como procesar este recurso. ??
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $transactionId = $data->transactionCode;
        $transactionDate = $data->transactionDate;
        $amount = $data->amount;
        $remoteBonusCode = $data->remoteBonusCode;
        $bonusInstanceCode = $data->bonusInstanceCode;
        $token = $data->externalToken;

        $isFreeSpin = false;
        if ($amount <= 0) {
            $isFreeSpin = true;
        }

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $responseDebit = $Playtech->Debit("", 0, $transactionId, "TF" . $transactionId, json_encode($datos), true, $defaulGame = true);
        $response = $Playtech->Credit("00", $amount, $transactionId, $transactionId, json_encode($datos), $isFreeSpin);
    }

    if ($URI == "notifybonusevent") {
        //procesar Bonos de freespin ?
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $remoteBonusCode = $data->remoteBonusCode;
        $bonusInstanceCode = $data->bonusInstanceCode;
        $resultingStatus = $data->resultingStatus;
        $date = $data->date;
        $freeSpinsChange = $data->freeSpinsChange;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = $Playtech->submitdialog();
    }

    if ($URI == "livetip") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $transactionId = $data->transactionCode;
        $roundID = $data->transactionCode;
        $transactionDate = $data->transactionDate;
        $game_code = $data->gameCodeName;
        if ($data->liveTableDetails != null) {
            $game_code = $data->liveTableDetails->tableId;
        }
        $amount = $data->amount;
        $datos = $data;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = ($Playtech->Debit($game_code, $amount, 'PROP_' . $roundID, $transactionId, json_encode($datos)));
    }

    //Este método se comparte con Póker
    if ($URI == "refund") {
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $requestId = $data->requestId;
        $externalToken = $data->externalToken;
        $roundID = $data->externalToken;
        $gameSessionCode = $data->gameSessionCode;
        $transactionId = $data->transactionCode;
        $transactionDate = $data->transactionDate;
        $amount = $data->amount;
        $relatedTransactionCode = $data->relatedTransactionCode;

        $datos = $data;
        if ($amount == "") {
            $amount = 0;
        }

        /* Procesamos */
        $Playtech = new Playtech($externalToken, $requestId, $username);
        $response = $Playtech->Rollback($amount, $gameSessionCode, $transactionId, $username, json_encode($datos), true);
    }

    //MÉTODOS DE PÓKER
    if ($URI == "tablebuyingchips") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $sessionCode = $data->gameSessionCode;
        $transactionId = $data->transactionCode;
        $transactionDate = $data->transactionDate;
        $amountDebit = $data->amount;
        $gameCodeName = $data->tableDetails->gameCodeName;
        $tableCode = $data->tableDetails->tableCode;
        $tableName = $data->tableDetails->tableName;
        $currencyCode = $data->tableDetails->currencyCode;
        $currencyExchangeRate = $data->tableDetails->currencyExchangeRate;
        $type = $data->internalFundChanges->type;
        $amount = $data->internalFundChanges->amount;
        $gamePoker = 'ps';

        if ($amountDebit == "") {
            $amountDebit = 0;
        }

        $datos = $data;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = ($Playtech->Debit($tableCode, $amountDebit, $sessionCode, $transactionId, json_encode($datos), $freespin = false, false, $gamePoker));
    }

    if ($URI == "tableresult") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $sessionCode = $data->gameSessionCode;
        $gamePoker = 'ps';
        $gameCodeName = $data->tableDetails->gameCodeName;
        $tableCode = $data->tableDetails->tableCode;
        $tableName = $data->tableDetails->tableName;
        $transactionId = $data->gameSessionCode;

        if ($data->soldChips !== "") {
            $transactionId = $data->soldChips->transactionCode;
            $transactionDate = $data->soldChips->transactionDate;
            $amount = $data->soldChips->amount;
            $internalFundChanges = $data->soldChips->internalFundChanges;
        }

        if ($data->gameSessionClose !== "") {
            $rake = $data->gameSessionClose->rake;
            $totalRealBet = $data->gameSessionClose->totalRealBet;
            $totalRealWin = $data->gameSessionClose->totalRealWin;
        }

        if ($amount == "") {
            $amount = 0;
        }

        $datos = $data;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = $Playtech->Credit($tableCode, $amount, $sessionCode, $transactionId, json_encode($datos), $isFreeSpin, $gamePoker);
    }

    if ($URI == "tournamentmonetarybuy") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $sessionCode = $data->gameSessionCode;
        $transactionId = $data->transactionCode;
        $transactionDate = $data->transactionDate;
        $amountDebit = $data->amount;
        $buyType = $data->buyType;
        $gameCodeName = $data->tournamentDetails->gameCodeName;
        $tournamentCode = $data->tournamentDetails->tournamentCode;
        $tournamentName = $data->tournamentDetails->tournamentName;
        $fee = $data->fee;
        $gamePoker = 'ps';

        $datos = $data;

        if ($amountDebit == "") {
            $amountDebit = 0;
        }

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = ($Playtech->Debit($tournamentCode, $amountDebit, $sessionCode, $sessionCode . $buyType, json_encode($datos), $freespin = false, false, $gamePoker));
    }

    if ($URI == "tournamenttokenbuy") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $sessionCode = $data->gameSessionCode;
        $transactionId = $data->transactionCode;
        $transactionDate = $data->transactionDate;
        $tokenCode = $data->tokenCode;
        $value = $data->value;
        $fundedBy = $data->fundedBy;
        $buyType = $data->buyType;
        $gameCodeName = $data->tournamentDetails->gameCodeName;
        $tournamentCode = $data->tournamentDetails->tournamentCode;
        $tournamentName = $data->tournamentDetails->tournamentName;
        $gamePoker = 'ps';

        $datos = $data;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = ($Playtech->Debit($tournamentCode, $value, $sessionCode, $transactionId, json_encode($datos), $freespin = false, false, $gamePoker));
    }

    if ($URI == "tournamentmonetarywin") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $sessionCode = $data->gameSessionCode;
        $transactionId = $data->transactionCode;
        $transactionDate = $data->transactionDate;
        $amount = $data->amount;
        $gameCodeName = $data->tournamentDetails->gameCodeName;
        $tournamentCode = $data->tournamentDetails->tournamentCode;
        $tournamentName = $data->tournamentDetails->tournamentName;
        $gamePoker = 'ps';

        if ($data->jackpotWins !== "") {
            $amountJackpot = $data->jackpotWins->amount;
            $jackpotId = $data->jackpotWins->jackpotId;
        }

        if ($amount == "") {
            $amount = 0;
        }
        $datos = $data;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = $Playtech->Credit($tournamentCode, $amount, $sessionCode, $transactionId, json_encode($datos), $isFreeSpin, $gamePoker);
    }

    if ($URI == "tournamenttokenwin") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $sessionCode = $data->gameSessionCode;
        $transactionId = $data->transactionCode;
        $transactionDate = $data->transactionDate;
        $tokenCode = $data->tokenCode;
        $value = $data->value;
        $gameCodeName = $data->tournamentDetails->gameCodeName;
        $tournamentCode = $data->tournamentDetails->tournamentCode;
        $tournamentName = $data->tournamentDetails->tournamentName;
        $gamePoker = 'ps';

        $datos = $data;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = $Playtech->Credit("", $value, $sessionCode, $transactionId, json_encode($datos), $isFreeSpin, $gamePoker);
    }

    if ($URI == "tournamentphysicalprizewin") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $sessionCode = $data->gameSessionCode;
        $transactionId = $data->transactionCode;
        $transactionDate = $data->transactionDate;
        $physicalPrizeName = $data->physicalPrizeName;
        $physicalPrizeValue = $data->physicalPrizeValue;
        $gameCodeName = $data->tournamentDetails->gameCodeName;
        $tournamentCode = $data->tournamentDetails->tournamentCode;
        $tournamentName = $data->tournamentDetails->tournamentName;
        $gamePoker = 'ps';

        $datos = $data;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = $Playtech->Credit("", 0, $sessionCode, $transactionId, json_encode($datos), $isFreeSpin, $gamePoker);
    }

    if ($URI == "tournamentdropout") {
        $requestId = $data->requestId;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $user = $data->username;
            $username = str_replace('DOR__', '', $user);
        } else {
            $username = $data->username;
        }
        $token = $data->externalToken;
        $sessionCode = $data->gameSessionCode;
        $fee = $data->fee;
        $transactionDate = $data->transactionDate;
        $gameCodeName = $data->tournamentDetails->gameCodeName;
        $tournamentCode = $data->tournamentDetails->tournamentCode;
        $tournamentName = $data->tournamentDetails->tournamentName;
        $gamePoker = 'ps';

        $datos = $data;

        /* Procesamos */
        $Playtech = new Playtech($token, $requestId, $username);
        $response = $Playtech->logout();
    }
}

$timeConsumed = round(microtime(true) - $curTime, 3) * 1000;

$t = microtime(true);
$micro = sprintf("%06d", ($t - floor($t)) * 1000000);
$d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));

print_r($response);
