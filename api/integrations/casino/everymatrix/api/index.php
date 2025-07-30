<?php

/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la integración
 * de la API de casino Everymatrix, incluyendo operaciones como autenticación, balance, débitos, créditos
 * y rollbacks de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed   $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var boolean $_ENV     Indica si el modo de depuración está habilitado ['debug'].
 * @var integer $_ENV     Controla si la conexión global está habilitada ["enabledConnectionGlobal"].
 * @var string  $_ENV     Configuración para el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var string  $URI      URI de la solicitud actual.
 * @var string  $body     Contenido del cuerpo de la solicitud.
 * @var object  $data     Datos decodificados del cuerpo de la solicitud en formato JSON.
 * @var string  $log      Variable utilizada para almacenar información de registro.
 * @var mixed   $response Respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Everymatrix;

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

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . "\r\n" . $URI . "\r\n";
$log = $log . file_get_contents('php://input');

if (true) {
    if ($data->Request == 'GetAccount') {
        $ValidateSession = $data->ValidateSession;
        $SessionId = $data->SessionId;
        $ExternalUserId = $data->ExternalUserId;

        /* Procesamos */
        $Everymatrix = new Everymatrix($ExternalUserId, $SessionId);
        $response = $Everymatrix->Auth();
    }

    if ($data->Request == 'GetBalance') {
        $ValidateSession = $data->ValidateSession;
        $SessionId = $data->SessionId;
        $ExternalUserId = $data->ExternalUserId;
        $GPId = $data->GPId;

        /* Procesamos */
        $Everymatrix = new Everymatrix($ExternalUserId, $SessionId);
        $response = $Everymatrix->Balance();
    }

    if ($data->Request == 'WalletDebit') {
        $ValidateSession = $data->ValidateSession;
        $SessionId = $data->SessionId;
        $ExternalUserId = $data->ExternalUserId;
        $TransactionType = $data->TransactionType;
        $Amount = $data->Amount;
        $TransactionId = $data->TransactionId;
        $TransactionTimestamp = $data->TransactionTimestamp;
        $RollbackTransactionId = $data->RollbackTransactionId;
        $Device = $data->Device;
        $GameType = $data->GameType;
        $GPGameId = $data->GPGameId;
        $GPId = $data->GPId;
        $EMGameId = $data->EMGameId;
        $GameSlug = $data->AdditionalData->GameSlug;
        $Product = $data->Product;
        $RoundId = $data->RoundId;
        $RoundStatus = $data->RoundStatus;
        $VendorData = $data->VendorData;
        $BetPayload = $data->BetPayload;

        $datos = $data;
        /* Procesamos */
        $Everymatrix = new Everymatrix($ExternalUserId, $SessionId);
        $response = $Everymatrix->Debit($GameSlug, $Amount, $RoundId, $TransactionId, $datos, $ValidateSession, $TransactionType, $SessionId);
    }

    if ($data->Request == 'WalletCredit') {
        $ValidateSession = $data->ValidateSession;
        $SessionId = $data->SessionId;
        $ExternalUserId = $data->ExternalUserId;
        $TransactionType = $data->TransactionType;
        $Amount = $data->Amount;
        $TransactionId = $data->TransactionId;
        $TransactionTimestamp = $data->TransactionTimestamp;
        $RollbackTransactionId = $data->RollbackTransactionId;
        $Device = $data->Device;
        $GameType = $data->GameType;
        $GPGameId = $data->GPGameId;
        $GPId = $data->GPId;
        $EMGameId = $data->EMGameId;
        $GameSlug = $data->AdditionalData->GameSlug;
        $RoundId = $data->RoundId;
        $RoundStatus = $data->RoundStatus;
        $VendorData = $data->VendorData;
        $BetPayload = $data->BetPayload;
        $FreeRoundWinType = $data->FreeRoundWinType;
        $datos = $data;
        /* Procesamos */

        $Everymatrix = new Everymatrix($ExternalUserId, $SessionId);
        $response = $Everymatrix->Credit($GameSlug, $Amount, $RoundId, $TransactionId, $datos, $ValidateSession, $RoundStatus, $SessionId);
    }

    if ($data->TransactionType == 'Rollback') {
        $ValidateSession = $data->ValidateSession;
        $SessionId = $data->SessionId;
        $ExternalUserId = $data->ExternalUserId;
        $TransactionType = $data->TransactionType;
        $Amount = $data->Amount;
        $TransactionId = $data->TransactionId;
        $TransactionTimestamp = $data->TransactionTimestamp;
        $RollbackTransactionId = $data->RollbackTransactionId;
        $Device = $data->Device;
        $GameType = $data->GameType;
        $GPGameId = $data->GPGameId;
        $GPId = $data->GPId;
        $EMGameId = $data->EMGameId;
        $GameSlug = $data->AdditionalData->GameSlug;
        $RoundId = $data->RoundId;
        $RoundStatus = $data->RoundStatus;
        $VendorData = $data->VendorData;
        $BetPayload = $data->BetPayload;
        $FreeRoundWinType = $data->FreeRoundWinType;
        $datos = $data;
        /* Procesamos */

        $Everymatrix = new Everymatrix($ExternalUserId, $SessionId);
        $response = $Everymatrix->Rollback($Amount, $RoundId, $RollbackTransactionId, $datos, $ValidateSession, $GameSlug, $SessionId);
    }
}

$log = "";
$log = $log . "/" . time();

$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($response);

print_r($response);
