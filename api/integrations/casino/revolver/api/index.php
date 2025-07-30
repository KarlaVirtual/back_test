<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API del casino 'Revolver'.
 * Proporciona funcionalidades para autenticación, consulta de saldo, débito, crédito y reversión de transacciones.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed  $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var bool   $_ENV         Indica si el modo de depuración está habilitado.
 * @var int    $_ENV         Variable que habilita la conexión global.
 * @var string $_ENV         Configuración para el tiempo de espera de bloqueo.
 * @var string $URI          URI de la solicitud actual.
 * @var string $body         Cuerpo de la solicitud en formato JSON.
 * @var mixed  $data         Datos decodificados del cuerpo de la solicitud.
 * @var array  $DatosArray   Datos de la solicitud convertidos a un arreglo.
 * @var string $requestOrder Orden de los datos de la solicitud para validación.
 * @var mixed  $response     Respuesta generada por las operaciones de la API.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Revolver;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json');

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$URI = $_SERVER['REQUEST_URI'];

$body = json_encode($_REQUEST);

$body = file_get_contents('php://input');

if ($body != "") {
    $data = json_decode($body);
}

$datos = $data;

$DatosArray = (array)$datos;
$requestOrder = "";
ksort($DatosArray);
$cont = 0;

foreach ($DatosArray as $key => $val) {
    if ($key != "sign" && $key != "sessionData" && $key != "launchVars" && ! is_object($val)) {
        if ($key == "isRoundFinished" && $val == true) {
            $requestOrder .= "true";
        } elseif ($key == "isRoundFinished" && $val == false) {
            $requestOrder .= "false";
        }

        if ($cont == 0) {
            $requestOrder .= $val;
        } else {
            $requestOrder .= $val;
        }
        $cont++;
    }
}

$hashOriginal = "";

if ($data != '') {
    if (true) {
        if (strpos($URI, "/auth") !== false) {
            $token = $data->token;
            $gameId = $data->gameId;
            $channel = $data->channel;
            $ip = $data->ip;
            $launchVars = $data->launchVars;
            $date = $data->date;
            $sign = $data->sign;

            /* Procesamos */
            $Revolver = new Revolver($token, "", $sign, $hashOriginal, $requestOrder);
            $response = ($Revolver->Authentication());
        } elseif (strpos($URI, "/balance") !== false) {
            $playerId = $data->playerId;
            $currency = $data->currency;
            $gameId = $data->gameId;
            $sessionState = $data->sessionState;
            $date = $data->date;
            $sign = $data->sign;

            /* Procesamos */
            $Revolver = new Revolver($sessionState, $playerId, $sign, $hashOriginal, $requestOrder);

            if ($Revolver->errorHash == true) {
                $response = array(
                    "code" => 1403,
                    "data" => array(
                        "sessionState" => $sessionState
                    ),
                    "message" => "Wrong Signature"
                );

                print_r(json_encode($response));
                exit();
            }

            $response = ($Revolver->getBalance($playerId));
        } else {
            if (strpos($URI, "/debit")) {
                $playerId = $data->playerId;
                $gameId = $data->gameId;
                $currency = $data->currency;
                $roundId = $data->roundId;
                $channel = $data->channel;
                $transactionId = $data->transactionId;
                $amount = floatval(round($data->amount, 2) / 100);
                $isRoundFinished = $data->isRoundFinished;
                $sessionState = $data->sessionState;
                $date = $data->date;
                $sign = $data->sign;

                $roundCreatedAt = $data->additionalData->roundCreatedAt;
                $roundUpdatedAt = $data->additionalData->roundUpdatedAt;
                $awardAdditionalData = $data->additionalData->awardAdditionalData;

                $campaignType = $data->campaignType;
                $awardId = $data->awardId;
                $transactionProviderPrefix = $data->transactionProviderPrefix;

                if ($campaignType == "FREESPIN" || $campaignType == "TOURNAMENT") {
                    $isbonus = true;
                } else {
                    $isbonus = false;
                }

                $AllowClosedRound = false;

                $Revolver = new Revolver($sessionState, $playerId, $sign, $hashOriginal, $requestOrder);

                if ($isbonus === true || $amount == null) {
                    $response = ($Revolver->Debit($gameId, 0, $roundId, $transactionId, $isRoundFinished, json_encode($datos), $amount));
                } else {
                    $response = ($Revolver->Debit($gameId, $amount, $roundId, $transactionId, $isRoundFinished, json_encode($datos), $amount));
                }

                if ($isRoundFinished === true) {
                    $Revolver->EndRound($sessionState, $gameId, $playerId, $roundId, $transactionId, "", json_encode($datos));
                }
            } elseif (strpos($URI, "/credit")) {
                $playerId = $data->playerId;
                $gameId = $data->gameId;
                $currency = $data->currency;
                $roundId = $data->roundId;
                $channel = $data->channel;
                $transactionId = $data->transactionId;
                $amount = floatval(round($data->amount, 2) / 100);
                $isRoundFinished = $data->isRoundFinished;
                $relatedExternalDebitTransactionId = $data->relatedExternalDebitTransactionId;
                $sessionState = $data->sessionState;
                $date = $data->date;
                $sign = $data->sign;

                $roundCreatedAt = $data->additionalData->roundCreatedAt;
                $roundUpdatedAt = $data->additionalData->roundUpdatedAt;
                $awardAdditionalData = $data->additionalData->awardAdditionalData;

                $campaignType = $data->campaignType;
                $awardId = $data->awardId;
                $transactionProviderPrefix = $data->transactionProviderPrefix;

                if ($campaignType == "FREESPIN" || $campaignType == "TOURNAMENT") {
                    $isbonus = true;
                } else {
                    $isbonus = false;
                }

                $Revolver = new Revolver($sessionState, $playerId, $sign, $hashOriginal, $requestOrder);
                $response = $Revolver->Credit($gameId, $amount, $roundId, $transactionId, json_encode($datos), $isbonus, $isRoundFinished);
            } elseif (strpos($URI, "/rollback")) {
                $playerId = $data->playerId;
                $transactionId = $data->transactionId;
                $gameId = $data->gameId;
                $amount = floatval(round($data->amount, 2) / 100);
                $debitAmount = $data->debitAmount;
                $creditAmount = $data->creditAmount;
                $currency = $data->currency;
                $roundId = $data->roundId;
                $reason = $data->reason;
                $relatedExternalDebitTransactionId = $data->relatedExternalDebitTransactionId;
                $sessionState = $data->sessionState;
                $transactionProviderPrefix = $data->transactionProviderPrefix;
                $roundCreatedAt = $data->additionalData->roundCreatedAt;
                $roundUpdatedAt = $data->additionalData->roundUpdatedAt;
                $awardAdditionalData = $data->additionalData->awardAdditionalData;
                $date = $data->date;
                $sign = $data->sign;

                $Revolver = new Revolver($sessionState, $playerId, $sign, $hashOriginal, $requestOrder);
                $response = ($Revolver->Rollback($roundId, $transactionId, json_encode($datos)));
            }
        }
    }

    print_r($response);
}
