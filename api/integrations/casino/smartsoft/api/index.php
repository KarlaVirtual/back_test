<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API de casino SmartSoft.
 * Proporciona funcionalidades como activación de sesión, consulta de saldo, depósitos, retiros y reversión de transacciones.
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
 * @var array   $_REQUEST  Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var boolean $_ENV      Indica si el modo de depuración está habilitado ['debug'].
 * @var integer $_ENV      Controla si la conexión global está habilitada ['enabledConnectionGlobal'].
 * @var string  $_ENV      Configuración para el tiempo de espera de bloqueo ['ENABLEDSETLOCKWAITTIMEOUT'].
 * @var string  $log       Almacena información de registro para depuración.
 * @var string  $body      Contiene el cuerpo de la solicitud HTTP.
 * @var object  $data      Almacena los datos decodificados del cuerpo de la solicitud.
 * @var string  $URI       Contiene la URI de la solicitud actual.
 * @var string  $token     Token de sesión enviado en los encabezados de la solicitud.
 * @var string  $Signature Firma enviada en los encabezados de la solicitud.
 * @var string  $usuarioId Identificador del usuario enviado en los encabezados de la solicitud.
 * @var object  $SmartSoft Instancia de la clase SmartSoft para manejar las operaciones de la API.
 * @var mixed   $response  Almacena la respuesta generada por las operaciones de la API.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\SmartSoft;

header('Content-type: application/json; charset=utf-8');

if (!function_exists('getallheaders')) {
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

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$body = file_get_contents('php://input');

$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);
$URI = $_SERVER['REQUEST_URI'];

if (isset($_SERVER['HTTP_X_SESSIONID'])) {
    $token = $_SERVER['HTTP_X_SESSIONID'];
    $Signature = $_SERVER['HTTP_X_SIGNATURE'];
    $usuarioId = $_SERVER['HTTP_X_CLIENTEXTERNALKEY'];
} else {
    $headers = getallheaders();
    $token = $headers['HTTP_X_SESSIONID'];
    $Signature = $headers['HTTP_X_SIGNATURE'];
    $usuarioId = $headers['HTTP_X_CLIENTEXTERNALKEY'];
    if ($token == '') {
        $token = $headers['X-Sessionid'];
        $Signature = $headers['X-Signature'];
        $usuarioId = $headers['X-Clientexternalkey'];
    }
}

if (strpos($URI, "ActivateSession") !== false) {
    $token = $data->Token;
    $usuarioId = "";

    /* Procesamos */
    $SmartSoft = new SmartSoft($token, $usuarioId);
    $response = ($SmartSoft->Auth());
}

if (strpos($URI, "GetBalance") !== false) {
    /* Procesamos */
    $SmartSoft = new SmartSoft($token, $usuarioId);
    $response = ($SmartSoft->getBalance());
}

if (strpos($URI, "Deposit") !== false) {
    $transactionId = $data->TransactionId;
    $transactionRefId = $data->TransactionType;
    $CurrencyCode = $data->CurrencyCode;
    $Source = $data->TransactionInfo->Source;
    $RoundId = $data->TransactionInfo->RoundId;
    $GameName = $data->TransactionInfo->GameName;
    $GameNumber = $data->TransactionInfo->GameNumber;
    $CashierTransacitonId = $data->TransactionInfo->CashierTransacitonId;
    $Amount = round($data->Amount, 2);
    $freespin = false;
    $datos = $data;

    /* Procesamos */
    $SmartSoft = new SmartSoft($token, $usuarioId);
    $response = $SmartSoft->Debit($GameName, $Amount, $RoundId, $transactionId, json_encode($datos), $freespin);
}

if (strpos($URI, "Withdraw") !== false) {
    $transactionId = $data->TransactionId;
    $TransactionType = $data->TransactionType;
    $CurrencyCode = $data->CurrencyCode;
    $Source = $data->TransactionInfo->Source;
    $RoundId = $data->TransactionInfo->RoundId;
    $GameName = $data->TransactionInfo->GameName;
    $GameNumber = $data->TransactionInfo->GameNumber;
    $CashierTransacitonId = $data->TransactionInfo->CashierTransacitonId;
    $betTransactionId = $data->TransactionInfo->BetTransactionId;
    $Amount = round($data->Amount, 2);
    $datos = $data;

    if ($betTransactionId == null || $betTransactionId == '') {
        $betTransactionId = $data->TransactionInfo->BetTransactionIds[0];
    }

    $isEndRound = false;
    if ($TransactionType == "CloseRound") {
        $isEndRound = true;
    }
    if ($TransactionType == "ClearBet") {
        $isEndRound = false;
    }

    /* Procesamos */
    $SmartSoft = new SmartSoft($token, $usuarioId);
    $response = $SmartSoft->Credit($GameName, $Amount, $RoundId, $transactionId, json_encode($datos), $isEndRound, $betTransactionId);
}

if (strpos($URI, "RollbackTransaction") !== false) {
    $transactionId = $data->TransactionId;
    $CurrentTransactionId = $data->CurrentTransactionId;
    $CurrencyCode = $data->CurrencyCode;
    $Source = $data->TransactionInfo->Source;
    $RoundId = $data->TransactionInfo->RoundId;
    $GameName = $data->TransactionInfo->GameName;
    $GameNumber = $data->TransactionInfo->GameNumber;
    $CashierTransacitonId = $data->TransactionInfo->CashierTransacitonId;
    $Amount = round($data->Amount, 2);
    $datos = $data;

    /* Procesamos */
    $SmartSoft = new SmartSoft($token, $usuarioId);
    $response = $SmartSoft->Rollback($Amount, $RoundId, $transactionId, $usuarioId, json_encode($datos));
}

print_r($response);
