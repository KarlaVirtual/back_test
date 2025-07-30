<?php

/**
 * Este archivo contiene el índice de la API de casino 'boss', que maneja las solicitudes
 * relacionadas con el balance, débitos y créditos de los usuarios en el sistema.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Configuración inicial del script:
 *
 * - Desactiva la visualización de errores.
 * - Carga las dependencias necesarias mediante Composer.
 */
ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Boss;

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';


$log = "\r\n" . "-------------------------" . "\r\n";

$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));

$body = json_encode($_REQUEST);

if ($body != "") {
    $data = json_decode($body);

    $action = $_REQUEST["action"];
    $token = $data->sid;

    switch ($action) {
        case "balance":

            $Boss = new Boss($token);

            $response = $Boss->getBalance();


            $log = "";
            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);

            print_r($response);


            break;

        case "debit":

            $gameId = $data->gamecode;
            $amount = $data->amount;
            $txnId = $data->tid;
            $roundId = $data->rid;

            $Boss = new Boss($token, $sign);

            $respuestaDebit = ($Boss->Debit($gameId, $amount, $roundId, $txnId, json_encode($data)));


            $log = "";
            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($respuestaDebit);

            print_r($respuestaDebit);

            break;

        case "credit":

            switch ($data->action_type) {
                case "win":

                    $gameId = $data->gamecode;
                    $amount = $data->amount;
                    $txnId = $data->tid;
                    $roundId = $data->rid;

                    $Boss = new Boss($token, $sign);

                    $respuestaCredit = ($Boss->Credit($gameId, $amount, $roundId, $txnId, json_encode($data)));


                    $log = "";
                    $log = $log . "\r\n" . "-------------------------" . "\r\n";
                    $log = $log . ($respuestaCredit);

                    print_r($respuestaCredit);

                    break;

                case "refund":

                    $gameId = $data->gamecode;
                    $amount = $data->amount;
                    $txnId = $data->tid;
                    $roundId = $data->rid;

                    $Boss = new Boss($token, $sign);

                    $response = ($Boss->Rollback($amount, $roundId, $txnId, 0, json_encode($datos)));

                    $log = "";
                    $log = $log . "\r\n" . "-------------------------" . "\r\n";
                    $log = $log . ($response);
                    print_r($response);


                    break;
            }

            break;
    }
}



