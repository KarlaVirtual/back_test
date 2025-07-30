<?php

/**
 * Este archivo contiene el índice de la API de casino 'End2end', que maneja diversas operaciones
 * relacionadas con la autenticación, balance, apuestas, ganancias y cancelación de juegos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed   $_REQUEST          Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var integer $_ENV              Habilita la conexión global ["enabledConnectionGlobal"].
 * @var string  $_ENV              Configura el tiempo de espera para bloqueos ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var string  $log               Variable que almacena el registro de la solicitud actual.
 * @var string  $body              Contenido del cuerpo de la solicitud.
 * @var string  $URI               URI de la solicitud.
 * @var mixed   $data              Datos decodificados del cuerpo de la solicitud.
 * @var string  $token             Token utilizado para la autenticación.
 * @var string  $sign              Firma utilizada para la autenticación.
 * @var mixed   $response          Respuesta generada por las operaciones.
 * @var string  $userId            Identificador del usuario.
 * @var string  $accountIdentifier Identificador de la cuenta del usuario.
 * @var float   $DebitAmount       Monto a debitar en una operación de apuesta.
 * @var string  $GameCode          Código del juego asociado a una operación.
 * @var string  $transactionId     Identificador único de la transacción.
 * @var string  $RoundId           Identificador de la ronda de juego.
 * @var boolean $freespin          Indica si la operación incluye giros gratis.
 * @var float   $CreditAmount      Monto a acreditar en una operación de ganancia.
 * @var string  $gameId            Identificador del juego a cancelar.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\End2end;

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));

$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];

if (strpos($URI, "Authenticate") !== false) {
    if ($body != "") {
        $data = json_decode($body);
        $token = $data->Token;

        $sign = $_REQUEST["sign"];
        $End2end = new End2end($token, $sign);
        $response = ($End2end->Auth());

        print_r($response);
    }
}

if (strpos($URI, "balance") !== false) {
    if ($_GET['userId'] != "") {
        $userId = $_GET['userId'];
        $accountIdentifier = $_GET['accountIdentifier'];

        $End2end = new End2end($userId);
        $response = $End2end->getBalance($accountIdentifier);
        print_r($response);
    }
}

if (strpos($URI, "wager") !== false) {
    if ($body != "") {
        $data = json_decode($body);

        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $transactionId = $data->fundTransfer->uniqueId;
            $accountIdentifier = $data->accountIdentifier;
            $End2end = new End2end();

            print_r($End2end->RollbackIndividual('', '', $transactionId, '', json_encode($data)));
        } else {
            $userId = $data->userId;

            $DebitAmount = $data->fundTransfer->balance->realMoney;
            $GameCode = $data->gameTypeId;
            $transactionId = $data->fundTransfer->uniqueId;
            $RoundId = $data->gameId;
            $datos = $data;
            $freespin = false;

            /* Procesamos */
            $End2end = new End2end($userId);

            $respuestaDebit = $End2end->Debit($GameCode, $DebitAmount, $RoundId, $transactionId, $userId, json_encode($datos), $freespin);

            print_r($respuestaDebit);
        }
    }
}

if (strpos($URI, "win") !== false) {
    if ($body != "") {
        $data = json_decode($body);

        $userId = $data->userId;
        $CreditAmount = $data->fundTransfer->balance->realMoney;
        $GameCode = $data->gameTypeId;
        $transactionId = "CREDIT" . $data->fundTransfer->uniqueId;
        $RoundId = $data->gameId;
        $datos = $data;

        /* Procesamos */
        $End2end = new End2end($userId);
        $respuestaCredit = $End2end->Credit($GameCode, $CreditAmount, $RoundId, $transactionId, json_encode($datos));

        print_r($respuestaCredit);
    }
}

if (strpos($URI, "cancelGame") !== false) {
    if ($_REQUEST['gameId'] != "") {
        $gameId = $_REQUEST['gameId'];
        $accountIdentifier = $_REQUEST['accountIdentifier'];

        /* Procesamos */
        $End2end = new End2end();
        print_r($End2end->Rollback($gameId, $accountIdentifier));
    }
}




