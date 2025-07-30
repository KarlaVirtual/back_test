<?php

/**
 * Este archivo contiene la implementación de la API de casino 'general'.
 * Proporciona endpoints para autenticar, consultar saldo, realizar débitos, créditos y rollbacks.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var integer $_ENV Indica si la conexión global está habilitada (1 para habilitado) ["enabledConnectionGlobal"].
 * @var string  $_ENV Configuración para habilitar el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\General;

header('Content-Type: application/json');

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));

$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];

if ($body != "") {
    $data = json_decode($body);
    $sign = $data->sign;


    if (strpos($URI, "authenticate") !== false) {
        $token = $data->token;

        /* Procesamos */
        $General = new General($token, $sign);
        $response = ($General->Auth());

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    }


    if (strpos($URI, "balance") !== false) {
        $token = $data->token;

        /* Procesamos */
        $General = new General($token, $sign);
        $response = ($General->getBalance());

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    }

    if (strpos($URI, "debit") !== false) {
        $token = $data->token;


        /* Procesamos */

        $token = $data->token;
        $amount = ($data->amount);
        $GameCode = $data->gamecode;
        $transactionId = $data->transactionid;
        $RoundId = $data->roundid;


        $datos = $data;

        /* Procesamos */


        $General = new General($token, $sign);


        $respuestaCredit = $General->Debit($GameCode, $amount, $RoundId, $transactionId, json_encode($datos));

        print_r($respuestaCredit);
    }

    if (strpos($URI, "credit") !== false) {
        $token = $data->token;


        /* Procesamos */

        $token = $data->token;
        $amount = ($data->amount);
        $GameCode = $data->gamecode;
        $transactionId = $data->transactionid;
        $RoundId = $data->roundid;


        $datos = $data;

        /* Procesamos */


        $General = new General($token, $sign);


        $respuestaCredit = $General->Credit($GameCode, $amount, $RoundId, $transactionId, json_encode($datos));

        print_r($respuestaCredit);
    }


    if (strpos($URI, "rollback") !== false) {
        $token = $data->token;


        /* Procesamos */

        $token = $data->token;
        $amount = ($data->amount);
        $GameCode = $data->gamecode;
        $transactionRollback = $data->transactionRollback;
        $transactionId = $data->transactionid;
        $RoundId = $data->roundid;


        $datos = $data;

        /* Procesamos */

        $General = new General($token, $sign);

        $respuestaCredit = $General->Rollback($amount, $transactionRollback, $transactionId, json_encode($datos));

        print_r($respuestaCredit);
    }
}



