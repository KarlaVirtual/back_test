<?php

/**
 * Este archivo actúa como punto de entrada para manejar las solicitudes API del casino Zenith.
 * Se encarga de procesar las peticiones y delegarlas a la clase Zenith para su manejo.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Nicolás Guato González <nicolas.guato@virtualsoft.tech>
 * @version    1.0
 * @since      2025-05-11
 * @access     public
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed   $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var boolean $_ENV     Indica si el modo de depuración está habilitado ['debug'].
 * @var integer $_ENV     Controla si la conexión global está habilitada ["enabledConnectionGlobal"].
 * @var string  $_ENV     Configuración para el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"] .
 * @var string  $URI      Contiene la URI de la solicitud actual.
 * @var string  $body     Contiene el cuerpo de la solicitud en formato JSON.
 * @var array   $headers  Almacena los encabezados HTTP de la solicitud.
 * @var object  $data     Objeto decodificado del cuerpo JSON de la solicitud.
 * @var string  $traceId  Identificador único para rastrear la solicitud.
 * @var string  $username Nombre de usuario asociado a la solicitud.
 * @var string  $currency Moneda utilizada en la solicitud.
 * @var string  $token    Token de autenticación del usuario.
 * @var mixed   $response Respuesta generada por las operaciones realizadas.
 */
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Zenith;

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


if ( ! function_exists('getallheaders')) {
    /**
     * Obtiene los encabezados HTTP de la solicitud.
     *
     * @return array Los encabezados HTTP recibidos.
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

$headers = getallheaders();

$data = json_decode($body);

// $traceId = $data->traceId;
// $username = $data->username;
// $currency = $data->currency;
// $token = $data->token;

// $Zenith = new Zenith($currency, $username, $token, $traceId);
// $signHeader = $headers['HTTP_X_SIGNATURE'] ?? $_SERVER['HTTP_X_SIGNATURE'] ?? $headers['X-Signature'] ?? $_SERVER['X-Signature'] ?? null;
// $sign = $Zenith->getSign($data, $signHeader);
// $response = $sign;

// $validateSign = false;

// if ($signHeader == $sign) {
//     $validateSign = true;
// }

if (true) {

    if (strpos($URI, "wallet/balance") !== false) {
        $traceId = $data->traceId;
        $username = $data->username;
        $currency = $data->currency;
        $token = $data->token;

        $Zenith = new Zenith($currency, $username, $token, $traceId);
        $response = $Zenith->Balance();
    }

    if (strpos($URI, "wallet/bet_result") !== false || strpos($URI, "wallet/bet") !== false || strpos($URI, "wallet/bet_credit")) {

        $traceId = $data->traceId;
        $username = $data->username;
        $transactionId = $data->transactionId;
        $betId = $data->betId;
        $externalTransactionId = $data->externalTransactionId;
        $roundId = $data->roundId;
        $betAmount = $data->betAmount;
        $amount = $data->amount;
        $jackpotAmount = $data->jackpotAmount;
        $winAmount = $data->winAmount;
        $effectiveTurnover = $data->effectiveTurnover;
        $winLoss = $data->winLoss;
        $jackpotAmount = $data->jackpotAmount;
        $resultType = $data->resultType;
        $isFreespin = $data->isFreespin;
        $isEndRound = $data->isEndRound;
        $currency = $data->currency;
        $token = $data->token;
        $gameCode = $data->gameCode;
        $betTime = $data->betTime;
        $settledTime = $data->settledTime;

        $Zenith = new Zenith($currency, $username, $token, $traceId);
        if ($resultType == "WIN") {
            $betId = $transactionId;

            $response = $Zenith->Debit($gameCode, $betAmount, $roundId, $betId, $data, $isFreeSpin, $isEndRound);
            $responseDecoded = json_decode($response);

            if ($responseDecoded->status == "SC_INSUFFICIENT_FUNDS") {
                print_r($response);
                exit();
            }
            if ($jackpotAmount > 0) {
                $winAmount += $jackpotAmount;
            }
            $response = $Zenith->Credit($gameCode, 0, $winAmount, $roundId, "C_" . $betId, $data, $isFreeSpin, $isEndRound);
        } elseif ($resultType == "BET_WIN") {

            $response = $Zenith->Debit($gameCode, $betAmount, $roundId, $betId, $data, $isFreeSpin, $isEndRound);
            $responseDecoded = json_decode($response);

            if ($responseDecoded->status == "SC_INSUFFICIENT_FUNDS") {
                print_r($response);
                exit();
            }
            if ($jackpotAmount > 0) {
                $winAmount += $jackpotAmount;
            }
            $response = $Zenith->Credit($gameCode, $betAmount, $winAmount, $roundId, "C_" . $betId, $data, $isFreeSpin, $isEndRound);
        } elseif ($resultType == "BET_LOSE" || $resultType == "LOSE") {
            $response = $Zenith->Debit($gameCode, $betAmount, $roundId, $betId, $data, $isFreeSpin, $isEndRound);
        } elseif ($resultType == "" || strpos($URI, "wallet/bet_debit" || strpos($URI, "wallet/bet_credit"))) {
            if (strpos($URI, "wallet/bet_credit")) {
                $response = $Zenith->Debit($gameCode, 0, $roundId, $betId, $data, $isFreeSpin, $isEndRound);
                $response = $Zenith->Credit($gameCode, 0, $amount, $roundId, "C_" . $betId, $data, $isFreeSpin, $isEndRound);
            } elseif ((strpos($URI, "wallet/bet_debit"))) {
                $betId = $transactionId;
                $response = $Zenith->Debit($gameCode, $amount, $roundId, $betId, $data, $isFreeSpin, $isEndRound);
            } else {
                $response = $Zenith->Debit($gameCode, $amount, $roundId, $betId, $data, $isFreeSpin, $isEndRound);
            }
        } elseif ($resultType == "END") {
            $response = $Zenith->End($roundId);
        }
    }

    if (strpos($URI, "wallet/rollback") !== false || strpos($URI, "wallet/adjustment") !== false) {

        $traceId = $data->traceId;
        $transactionId = $data->transactionId;
        $betId = $data->betId;
        $externalTransactionId = $data->externalTransactionId;
        $roundId = $data->roundId;
        $amount = $data->amount;
        $gameCode = $data->gameCode;
        $username = $data->username;
        $currency = $data->currency;
        $timestamp = $data->timestamp;

        $Zenith = new Zenith($currency, $username, $token, $traceId);

        if (strpos($URI, "wallet/adjustment") !== false) {
            $isFreeSpin = false;
            $isEndRound = false;

            if ($amount > 0) {
                $winAmount = $amount;

                $response = $Zenith->adjustmentD($gameCode, 0, $roundId, $transactionId, $data, $isFreeSpin, $isEndRound);
                $responseDecoded = json_decode($response);

                if ($responseDecoded->status == "SC_TRANSACTION_NOT_EXISTS") {
                    print_r($response);
                    exit();
                }
                $response = $Zenith->Credit($gameCode, $betAmount, $winAmount, $roundId, "C_" . $transactionId, $data, $isFreeSpin, $isEndRound);
            } else {
                $betAmount = abs($amount);
                $response = $Zenith->adjustmentD($gameCode, $betAmount, $roundId, $transactionId, $data, $isFreeSpin, $isEndRound);
            }
        } else {
            $response = $Zenith->Rollback($betId, $roundId, $data, $gameCode);
        }
    }
}
print_r($response);