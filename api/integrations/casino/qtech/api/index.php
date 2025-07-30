<?php
/**
 * Este archivo contiene la implementación de la API de casino 'qtech'.
 * Procesa solicitudes relacionadas con sesiones, balances, transacciones y rollbacks.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var integer $_ENV Indica si la conexión global está habilitada.
 * @var string  $_ENV Configuración para el tiempo de espera de bloqueo.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\QTech;

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));

$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];

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

$headers = getallheaders();


if (true) {
    $data = json_decode($body);


    if (strpos($URI, "session") !== false) {
        $token = $headers['Wallet-Session'];
        $sign = $headers["Pass-Key"];

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . $token;
        $log = $log . $sign;
        $log = $log . trim(file_get_contents('php://input'));

        /* Procesamos */
        $QTech = new QTech($token, $sign);
        $response = ($QTech->Auth());

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    }


    if (strpos($URI, "balance") !== false) {
        $userid = explode("accounts/", $URI)[1];
        $userid = explode("/balance", $userid)[0];
        $token = $headers['Wallet-Session'];
        $sign = $headers["Pass-Key"];


        /* Procesamos */
        $QTech = new QTech($token, $sign);
        $response = ($QTech->getBalance($userid));

        $log = "";
        $log = $log . "/" . time();
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    }

    if (strpos($URI, "rollback") !== false) {
        $token = $headers['Wallet-Session'];
        $sign = $headers["Pass-Key"];


        $betId = $data->betId;
        $txnId = $data->txnId;
        $playerId = $data->playerId;

        $roundId = $data->roundId;
        $amount = $data->amount;
        $currency = $data->currency;
        $bonusBetAmount = $data->bonusBetAmount;
        $bonusType = $data->bonusType;
        $bonusPromoCode = $data->bonusPromoCode;
        $gameId = $data->gameId;
        $device = $data->device;
        $clientType = $data->clientType;
        $clientRoundId = $data->clientRoundId;
        $category = $data->category;
        $created = $data->created;
        $completed = $data->completed;

        $datos = $data;

        /* Procesamos */

        $QTech = new QTech($token, $sign);
        $response = ($QTech->Rollback($amount, $betId, $txnId, $playerId, json_encode($datos)));

        $log = "";
        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    } elseif (strpos($URI, "/transactions") !== false) {
        $token = $headers['Wallet-Session'];
        $sign = $headers["Pass-Key"];

        $txnType = $data->txnType;
        $txnId = $data->txnId;
        $playerId = $data->playerId;
        $roundId = $data->roundId;
        $amount = $data->amount;
        $currency = $data->currency;
        $bonusBetAmount = $data->bonusBetAmount;
        $bonusType = $data->bonusType;
        $bonusPromoCode = $data->bonusPromoCode;
        $gameId = $data->gameId;
        $device = $data->device;
        $clientType = $data->clientType;
        $clientRoundId = $data->clientRoundId;
        $category = $data->category;
        $created = $data->created;
        $completed = $data->completed;

        $DebitAmount = ($data->DebitAmount);
        $CreditAmount = $data->CreditAmount;
        $GameCode = $data->GameCode;

        $PlayerId = $data->PlayerId;
        $transactionId = $data->RGSTransactionId;

        $RoundId = $data->RGSTransactionId;

        $Currency = $data->Currency;

        $datos = $data;


        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . $token;
        $log = $log . $sign;

        /* Procesamos */

        if ($txnType == "DEBIT") {
            $QTech = new QTech($token, $sign);


            $respuestaDebit = ($QTech->Debit($gameId, $amount, $roundId, $txnId, json_encode($datos)));


            $log = "";
            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($respuestaDebit);

            print_r($respuestaDebit);
        }


        if ($txnType == "CREDIT") {
            $QTech = new QTech($token, $sign);


            $respuestaCredit = ($QTech->Credit($gameId, $amount, $roundId, $txnId, json_encode($datos)));


            $log = "";
            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($respuestaCredit);

            print_r($respuestaCredit);
        }
    }
}



