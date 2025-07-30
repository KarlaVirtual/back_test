<?php

/**
 * Este archivo contiene la implementación de la API para la integración con el casino 'Spinomenal'.
 * Proporciona endpoints para manejar operaciones como obtener información del jugador, balance,
 * procesar apuestas, y más, utilizando la clase `Spinomenal`.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 *
 * Variables globales utilizadas en el script:
 *
 * @var array   $_REQUEST       Contiene los datos enviados a través de métodos GET, POST o REQUEST.
 * @var string  $URI            Almacena la URI de la solicitud actual.
 * @var string  $body           Contiene el cuerpo de la solicitud en formato JSON.
 * @var object  $data           Objeto decodificado del cuerpo de la solicitud.
 * @var string  $response       Almacena la respuesta generada por las operaciones de la API.
 * @var boolean $signatureError Indica si hubo un error de firma en la solicitud.
 */
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Spinomenal;

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

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$URI = $_SERVER['REQUEST_URI'];

$body = file_get_contents('php://input');

$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);

$date = date("Y-m-d H:i:s");
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($URI);
$log = $log . (http_build_query($_REQUEST));
$log = $log . json_encode($data);

$headers = getallheaders();

$signatureError = false;

if (true) {
    $token = $data->GameToken;
    $PlayerId = $data->PlayerId;
    $GameCode = $data->GameCode;
    $Spinomenal = new Spinomenal($token, $PlayerId, $GameCode);

    if (strpos($URI, "getplayerinfo") !== false) {
        $dataSign = $data->TimeStamp . $data->GameToken;
        $signature = $Spinomenal->Sign($dataSign);

        if ($data->Sig == $signature) {
            $GameCode = $data->GameCode;
            $PartnerId = $data->PartnerId;
            $Sig = $data->Sig;
            $TimeStamp = $data->TimeStamp;
            $token = $data->GameToken;

            $usuarioId = "";

            /* Procesamos */
            $response = $Spinomenal->Auth($TimeStamp, $PartnerId);
        } else {
            $signatureError = true;
        }
    }

    if (strpos($URI, "getbalance") !== false) {
        $dataSign = $data->TimeStamp . $data->GameToken;
        $signature = $Spinomenal->Sign($dataSign);

        if ($data->Sig == $signature) {
            $GameCode = $data->GameCode;
            $PartnerId = $data->PartnerId;
            $Currency = $data->Currency;
            $Sig = $data->Sig;
            $TimeStamp = $data->TimeStamp;
            $token = $data->GameToken;

            /* Procesamos */
            $response = ($Spinomenal->getBalance($TimeStamp, $PartnerId));
        } else {
            $signatureError = true;
        }
    }

    if (strpos($URI, "processbet") !== false) {
        $dataSign = $data->TimeStamp . $data->PlayerId . $data->TransactionId;
        $signature = $Spinomenal->Sign($dataSign);

        if ($data->Sig == $signature) {
            $GameCode = $data->GameCode;
            $PartnerId = $data->PartnerId;
            $Sig = $data->Sig;
            $TimeStamp = $data->TimeStamp;
            $token = $data->GameToken;
            $transactionId = $data->TransactionId;
            $BetAmount = $data->BetAmount;
            $WinAmount = $data->WinAmount;
            $RoundId = $data->RoundId;
            $Currency = $data->Currency;
            $IsRoundFinish = $data->IsRoundFinish;
            $TransactionType = $data->TransactionType;
            $ProviderCode = $data->ProviderCode;
            $IsRetry = $data->IsRetry;
            $SubGameCode = $data->SubGameCode;
            $PlayerId = $data->PlayerId;
            $RefTransactionId = $data->RefTransactionId;

            $datos = $data;
            $isEndRound = false;

            if ($TransactionType == "BetAndWin") {
                $response = ($Spinomenal->DebitAndCredit($GameCode, $RoundId, $BetAmount, $WinAmount, $transactionId, 'C_' . $transactionId, $datos, false, $TransactionType, $PartnerId, $token, $IsRetry));
            } elseif ($TransactionType == "EndRound") {
                $response = ($Spinomenal->EndRound($RoundId, $datos, $TimeStamp, $transactionId));
            } elseif ($TransactionType == "CancelBet") {
                $response = ($Spinomenal->Rollback($RoundId, $transactionId, $PlayerId, json_encode($datos), $TimeStamp));
            } elseif ($TransactionType == "Win") {
                $IsBuyFeature = $data->TransactionDetails->IsBuyFeature;
                $SmResult = $data->TransactionDetails->SmResult;
                $BonusDetails = $data->TransactionDetails->BonusDetails;
                $RoundDetails = $data->TransactionDetails->RoundDetails;
                $FreeRoundsDetails = $data->TransactionDetails->FreeRoundsDetails;

                $response = ($Spinomenal->DebitAndCredit($GameCode, $RoundId, 0, $WinAmount, $transactionId, 'C_' . $transactionId, $datos, false, $TransactionType, $PartnerId, $token, $IsRetry));
            } elseif ($TransactionType == "Bonus") {
                $BonusType = $data->TransactionDetails->BonusDetails->BonusType;
                $BonusCode = $data->TransactionDetails->BonusDetails->BonusCode;
                $BonusPlayerId = $data->TransactionDetails->BonusDetails->BonusPlayerId;
                $TournamentRank = $data->TransactionDetails->BonusDetails->TournamentRank;
                $TournamentScore = $data->TransactionDetails->BonusDetails->TournamentScore;

                $response = ($Spinomenal->DebitAndCredit($GameCode, $RoundId, 0, $WinAmount, $transactionId, 'C_' . $transactionId, $datos, false, $TransactionType, $PartnerId, $token, $IsRetry));
            } elseif ($TransactionType == "FreeRounds_Start") {
                $FreeRoundsAssignCode = $data->TransactionDetails->FreeRoundsDetails->FreeRoundsAssignCode;
                $response = ($Spinomenal->FreeRoundStart($PartnerId));
            } elseif ($TransactionType == "FreeRounds_Win") {
                $RoundsLeft = $data->TransactionDetails->FreeRoundsDetails->RoundsLeft;
                $TotalWin = $data->TransactionDetails->FreeRoundsDetails->TotalWin;
                $tranId = $data->TransactionDetails->FreeRoundsDetails->FreeRoundsAssignCode;
                $ValAssingCode = true;

                $response = ($Spinomenal->DebitAndCredit($GameCode, $tranId, 0, $WinAmount, $tranId . $transactionId, 'C_' . $tranId . $transactionId, $datos, false, $TransactionType, $PartnerId, $token, $IsRetry, $ValAssingCode));
            } elseif ($TransactionType == "FreeRounds_End") {
                $RoundsLeft = $data->TransactionDetails->FreeRoundsDetails->RoundsLeft;
                $tranId = $data->TransactionDetails->FreeRoundsDetails->FreeRoundsAssignCode;
                $TotalWin = $data->TransactionDetails->FreeRoundsDetails->TotalWin;
                $ValAssingCode = true;
                $IsEndRound = true;

                $response = ($Spinomenal->DebitAndCredit($GameCode, $tranId, 0, $WinAmount, $tranId . $transactionId, 'C_' . $tranId . $transactionId, $datos, true, $TransactionType, $PartnerId, $token, $IsRetry, $ValAssingCode, $IsEndRound));
            }
        } else {
            $signatureError = true;
        }
    }

    if (strpos($URI, "resettoken") !== false) {
        $dataSign = $data->TimeStamp . $data->GameToken;
        $signature = $Spinomenal->Sign($dataSign);

        if ($data->Sig == $signature) {
            $GameCode = $data->GameCode;
            $Sig = $data->Sig;
            $PartnerId = $data->PartnerId;
            $TimeStamp = $data->TimeStamp;
            $token = $data->GameToken;
            $TargetGameCode = $data->TargetGameCode;

            /* Procesamos */
            $response = ($Spinomenal->ResetToken($TimeStamp));
        } else {
            $signatureError = true;
        }
    }

    if (strpos($URI, "checktransaction") !== false) {
        $dataSign = $data->TimeStamp . $data->PlayerId . $data->TransactionId;
        $signature = $Spinomenal->Sign($dataSign);

        if ($data->Sig == $signature) {
            $GameCode = $data->GameCode;
            $PartnerId = $data->PartnerId;
            $Sig = $data->Sig;
            $TimeStamp = $data->TimeStamp;
            $token = $data->GameToken;
            $transactionId = $data->TransactionId;
            $BetAmount = $data->BetAmount;
            $WinAmount = $data->WinAmount;
            $RoundId = $data->RoundId;
            $Currency = $data->Currency;
            $IsRoundFinish = $data->IsRoundFinish;
            $TransactionType = $data->TransactionType;
            $ProviderCode = $data->ProviderCode;
            $IsRetry = $data->IsRetry;
            $SubGameCode = $data->SubGameCode;
            $PlayerId = $data->PlayerId;
            $RefTransactionId = $data->RefTransactionId;

            $datos = $data;
            $isEndRound = false;

            $response = ($Spinomenal->CheckTransaccion($RoundId, $transactionId));
        } else {
            $signatureError = true;
        }
    }
}

if (strpos($URI, "healthcheck") !== false) {
    http_response_code(200);
}

if ($signatureError) {
    $response = json_encode(array(
        "ErrorCode" => 6002,
        "ErrorMessage" => 'InvalidSignature',
        "ErrorDisplayText" => 'InvalidSignature',
    ));
}

$log = $log . "\r\n" . "-------------Response------------" . "\r\n";
$log = $log . ($response);

print_r($response);