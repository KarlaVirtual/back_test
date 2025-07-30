<?php

/**
 * Este archivo contiene un script para procesar y manejar diversas operaciones relacionadas
 * con la API de casino 'betixon', incluyendo autenticación de usuarios, transacciones,
 * consultas de saldo, y manejo de sesiones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    ninguna
 * @since      2025-05-09
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Atomo;


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));
$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];


if ($body != "") {
    $data = json_decode($body);


    if (strpos($URI, "UserGetInfo") !== false) {
        $userId = $_POST["userId"];
        $sign = $_REQUEST["sign"];

        /* Procesamos */
        $Atomo = new Atomo($token, $sign, $userId);
        $response = ($Atomo->Auth());


        header('X-Signature: ' . strtoupper(hash_hmac('sha256', trim(($response)), 'KEY', false)));

        $log = "";
        $log = $log . "/" . time();
        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    }


    if (strpos($URI, "UserAuthenticate") !== false) {
        $userId = $_POST["userId"];

        $username = $_POST["username"];
        $password = $_POST["password"];
        $partnerId = $_POST["partnerId"];

        $sign = $_REQUEST["sign"];

        /* Procesamos */
        $Atomo = new Atomo($token, $sign, $userId);
        $response = ($Atomo->AuthWithCredentials($username, $password, $partnerId));


        header('X-Signature: ' . strtoupper(hash_hmac('sha256', trim(($response)), 'KEY', false)));

        $log = "";
        $log = $log . "/" . time();
        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    }


    if (strpos($URI, "GetBalance") !== false) {
        $token = $data->sessionId;
        $userId = $_POST["userId"];

        /* Procesamos */
        $Atomo = new Atomo($token, $sign, $userId);
        $response = ($Atomo->getBalance());

        header('X-Signature: ' . strtoupper(hash_hmac('sha256', trim(($response)), 'KEY', false)));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    }

    if (strpos($URI, "UserTransaction") !== false) {
        $data = json_decode($_POST["context"]);

        $token = $data->session_id;

        $transactionId = $_POST["transactionId"];

        $RoundId = $_POST["transactionId"];

        $DebitAmount = $data->total_bet_amount;

        $GameCode = $data->game_id;

        $datos = $data;

        /* Procesamos */


        $Atomo = new Atomo($token, $sign);


        $respuestaDebit = ($Atomo->Debit($GameCode, $DebitAmount, $RoundId, $transactionId, json_encode($datos)));

        $token = $data->sessionId;

        if ($data->win != null && $data->win != '') {
            $CreditAmount = $data->total_win_amount;

            $transactionId = "CREDIT" . $transactionId;

            $datos = $data;

            /* Procesamos */


            $Atomo = new Atomo($token, $sign);


            $respuestaCredit = $Atomo->Credit($GameCode, $CreditAmount, $RoundId, $transactionId, json_encode($datos));
        }

        header('X-Signature: ' . strtoupper(hash_hmac('sha256', trim(($respuestaDebit)), '', false)));

        print_r($respuestaDebit);
    }


    if (strpos($URI, "Rollback") !== false) {
        $sign = $data->sign;
        $token = $data->token;

        $currency = $data->currency;
        $rollbackAmount = 0;
        $date = $data->date;
        $player = $data->PlayerId;
        $roundId = $data->RGSRelatedTransactionId;
        $transactionId = $data->RGSRelatedTransactionId;

        $datos = $data;

        /* Procesamos */

        $Atomo = new Atomo($token, $sign);
        $respuesta = ($Atomo->Rollback($rollbackAmount, $roundId, $transactionId, $player, json_encode($datos)));

        header('X-Signature: ' . strtoupper(hash_hmac('sha256', trim(($respuesta)), 'KEY', false)));

        print_r($respuesta);
    }


    if (strpos($URI, "CloseSession") !== false) {
        $token = $data->sessionId;

        /* Procesamos */
        $Atomo = new Atomo($token, $sign);
        $response = ($Atomo->getBalance());

        header('X-Signature: ' . strtoupper(hash_hmac('sha256', trim(($response)), 'KEY', false)));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    }


    if (strpos($URI, "NotifySession") !== false) {
        $token = $data->sessionId;

        /* Procesamos */
        $Atomo = new Atomo($token, $sign);
        $response = ($Atomo->refreshSesion());

        header('X-Signature: ' . strtoupper(hash_hmac('sha256', trim(($response)), 'KEY', false)));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    }
}



