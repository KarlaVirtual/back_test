<?php

/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la API de casino 'pascal'.
 * Incluye funcionalidades para autenticación, consulta de saldo, transacciones, actualización de tokens y manejo de ganancias por lotes.
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
 * @var mixed   $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var boolean $_ENV     Indica si el modo de depuración está habilitado ['debug'].
 * @var integer $_ENV     Variable que habilita la conexión global ["enabledConnectionGlobal"].
 * @var mixed   $log      Almacena información de registro para depuración.
 * @var mixed   $body     Contiene el cuerpo de la solicitud HTTP.
 * @var mixed   $URI      Almacena la URI de la solicitud.
 * @var mixed   $data     Contiene los datos decodificados del cuerpo de la solicitud.
 * @var mixed   $response Almacena la respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Pascal;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json; charset=utf-8');

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));

$_ENV["enabledConnectionGlobal"] = 1;

$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];

if ($body != "") {
    $data = json_decode($body);

    $signature = $data->token;

    if (strpos($URI, "authentication") !== false) {
        $hash = $data->hash;
        $token = $data->token;

        /* Procesamos */
        $Pascal = new Pascal($token, $hash);
        $response = ($Pascal->Auth());
    }


    if (strpos($URI, "getbalance") !== false) {
        $hash = $data->hash;
        $token = $data->token;

        /* Procesamos */
        $Pascal = new Pascal($token, $hash);
        $response = ($Pascal->getBalance());
    }

    if (strpos($URI, "transaction") !== false) {
        $hash = $data->hash;
        $token = $data->token;
        $gameId = $data->gameId;
        $transactionId = $data->transactionId;
        $roundId = $data->roundId;
        $amount = round($data->amount, 2);
        $type = $data->type;
        $moneyType = $data->moneyType;
        $TransactionInfo = $data->TransactionInfo;

        $datos = $data;
        /* Procesamos */

        $Pascal = new Pascal($token, $hash);

        $freespin = false;

        if ($moneyType == 5 || $moneyType == 1) {
            $freespin = true;
            $amount = 0;
            $transactionId = "DFS" . $transactionId;
        }

        switch ($type) {
            case 1:
                $response = ($Pascal->Debit($gameId, $amount, $roundId, $transactionId, json_encode($datos), $freespin));
                break;

            case 0:
                $amount = round($data->amount, 2);
                $response = $Pascal->Credit($gameId, $amount, $roundId, $transactionId, json_encode($datos), $freespin);
                break;

            case 2:
                $response = ($Pascal->Rollback($amount, $roundId, $transactionId, "", json_encode($datos)));
                break;
        }
    }

    if (strpos($URI, "refreshtoken") !== false) {
        $hash = $data->hash;
        $token = $data->token;

        /* Procesamos */
        $Pascal = new Pascal($token, $hash);
        $response = ($Pascal->refreshToken());
    }

    if (strpos($URI, "winbybatch") !== false) {
        $hash = $data->timeStamp;
        $hash = $data->hash;
        $data = $data->data;
        foreach ($data as $key => $value) {
            $token = $value->token;
            $gameId = $value->gameId;
            $transactionId = $value->transactionId;
            $roundId = $value->roundId;
            $amount = round($value->amount, 2);
            $type = $value->type;
            $moneyType = $value->moneyType;
            $TransactionInfo = $value->TransactionInfo;
            $datos = $data;

            /* Procesamos */
            $Pascal = new Pascal($token, $hash);

            $freespin = false;

            if ($moneyType == "1") {
                $freespin = true;
            }

            switch ($type) {
                case "0":
                    $response = $Pascal->CreditTotal($gameId, $amount, $roundId, $transactionId, json_encode($datos));
                    break;
            }
        }
    }

    $log = "";
    $log = $log . "/" . time();
    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}
