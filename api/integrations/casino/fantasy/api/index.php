<?php
/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la API de casino 'Fantasy' en modo wallet.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Davison Valencia <davison.valencia@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var array   $_REQUEST             Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var boolean $_ENV                 Indica si el modo de depuración está habilitado ['debug'].
 * @var string  $URI                  URI de la solicitud actual.
 * @var string  $body                 Cuerpo de la solicitud en formato JSON.
 * @var string  $signature            Firma enviada en los encabezados de la solicitud.
 * @var object  $data                 Datos decodificados del cuerpo de la solicitud.
 * @var string  $messageTransactionId Identificador único de la transacción.
 * @var object  $Fantasy              Instancia de la clase Fantasy para manejar operaciones específicas.
 * @var mixed   $response             Respuesta generada por las operaciones realizadas.
 * @var string  $log                  Cadena utilizada para almacenar información de registro.
 */

header('Content-type: application/json');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

if (version_compare(phpversion(), '7.1', '>=')) {
    ini_set('precision', 17);
    ini_set('serialize_precision', -1);
}
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Fantasy;
use Backend\dto\ConfigurationEnvironment;

$_ENV["enabledConnectionGlobal"] = 1;
$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');

$signature = $_SERVER['HTTP_X_SIGNATURE'];

$date = date("Y-m-d H:i:s");

$log = "";
$log = $log . "\r\n";
$log = $log . "************ DATE: " . $date . "************" . " / " . time();
$log = $log . "\r\n";
$log = $log . "\r\n" . "--------------DATA REQUEST----------------" . "\r\n";
$log = $log . "\r\n";
$log = $log . $URI . ' // ' . $signature . "\r\n";
$log = $log . "\r\n";
$log = $log . json_encode($_REQUEST) . "\r\n";
$log = $log . "\r\n";
$log = $log . trim(file_get_contents('php://input'));
$log = $log . "\r\n" . "---------------------------------" . "\r\n";

//Save string to log, use FILE_APPEND to append.
//fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

$data = json_decode($body);

$URI = explode('/', $URI);
$URI = $URI[count($URI) - 1];
$URI = explode('?', $URI);
$URI = $URI[0];

$messageTransactionId = $data->MessageTransacionId;
$Fantasy = new Fantasy("", "", "");
$respSignature = $Fantasy->computeHash($messageTransactionId);

if ($signature == $respSignature) {
    if ($URI == "session") {
        $user = $data->UserId;
        $currency = $data->currency;
        $balance = $data->balance;

        /* Procesamos */
        $Fantasy = new Fantasy($user, "");
        $response = $Fantasy->Auth($currency);
    } elseif ($URI == "debit") {
        $user = $data->Payload->UserId;
        $debitAmount = $data->Payload->Amount;
        $gameId = 'FG_FAYG';
        $roundId = $data->Payload->TransactionId;
        $transactionId = $data->Payload->TransactionId;

        /* Procesamos */
        $Fantasy = new Fantasy($user, $transactionId);
        $response = $Fantasy->Debit($gameId, $debitAmount, $roundId, 'D_' . $transactionId, $data, false);
    } elseif ($URI == "credit") {
        $extTransactionId = $data->Payload[0]->TransactionId;

        $response = array(
            'isSuccess' => false,
            'extTransactionId' => $extTransactionId,
            'result' => array()
        );

        $gameId = 'FG_FAYG';

        foreach ($data->Payload as $i => $items) {
            $user = $items->ExternalUserId;
            $prizeToCredit = $items->PrizeToCredit;
            $roundId = $items->TransactionId;
            $transactionId = $items->TransactionId;

            /* Procesamos */
            $Fantasy = new Fantasy($user, $transactionId);
            $respp = $Fantasy->Credit($gameId, $prizeToCredit, $roundId, 'C_' . $transactionId, json_encode($data), false, true);
            $respuesta = json_decode($respp);
            array_push($response['result'], $respuesta);
        }

        $response = json_encode($response);

        $tranCodeCount = 0;
        $resp = json_decode($response, true);
        if (isset($resp['result']) && is_array($resp['result'])) {
            foreach ($resp['result'] as $resultItem) {
                if (isset($resultItem['extTransactionId'])) {
                    $tranCodeCount++;
                }
            }
        }

        $errorCodeCount = 0;
        $resp = json_decode($response, true);
        if (isset($resp['result']) && is_array($resp['result'])) {
            foreach ($resp['result'] as $resultItem) {
                if (isset($resultItem['errorCode'])) {
                    $errorCodeCount++;
                }
            }
        }

        if ($tranCodeCount == $errorCodeCount) {
            $response = $respp;
        } elseif ($errorCodeCount == 0) {
            $response = $respp;
        } else {
            http_response_code(207);
            $respons = $response;
            $data = json_decode($respons, true);
            if (isset($data['result']) && is_array($data['result'])) {
                foreach ($data['result'] as &$resultItem) {
                    if (isset($resultItem['extTransactionId'])) {
                        $resultItem['transactionId'] = $resultItem['extTransactionId'];
                        unset($resultItem['extTransactionId']);
                    }
                    if (array_key_exists('message?', $resultItem)) {
                        unset($resultItem['message?']);
                    }
                }
            }
            $response = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }
    } elseif ($URI == "refund") {
        $user = $data->Payload->UserId;
        $debitAmount = $data->Payload->amountToRefund;
        $gameId = 'FG_FAYG';
        $roundId = $data->Payload->TransactionId;
        $transactionId = $data->Payload->TransactionId;

        /* Procesamos */
        $Fantasy = new Fantasy($user, $transactionId);
        $response = $Fantasy->Rollback($CancelAmount, $roundId, 'D_' . $transactionId, $user, json_encode($data), false, $gameId);
    }

    $log = "";
    $log = $log . "\r\n";
    $log = $log . "************ DATE: " . $date . "************" . " / " . time();
    $log = $log . "\r\n";
    $log = $log . "\r\n" . "--------------DATA RESPONSE" . $URI . "-------------" . "\r\n";
    $log = $log . "\r\n";
    $log = $log . ($response);
    $log = $log . "\r\n" . "---------------------------------" . "\r\n";

    //Save string to log, use FILE_APPEND to append.
    //fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

    $resp = json_decode($response, true);
    if (isset($resp['errorCode'])) {
        http_response_code(400);
    }
    echo $response;
} else {
    $response = array(
        "isSuccess" => false,
        "extTransactionId" => 0,
        "errorCode" => 'signature.error',
    );

    $log = "";
    $log = $log . "\r\n";
    $log = $log . "************ DATE: " . $date . "************" . " / " . time();
    $log = $log . "\r\n";
    $log = $log . "\r\n" . "--------------DATA RESPONSE ERROR-------------" . "\r\n";
    $log = $log . "\r\n";
    $log = $log . ($response);
    $log = $log . "\r\n" . "---------------------------------" . "\r\n";

    http_response_code(400);
    echo json_encode($response);
}
