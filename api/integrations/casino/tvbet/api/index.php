<?php
/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la integración
 * de la API de casino 'TvBet', incluyendo autenticación, manejo de tokens, pagos y promociones.
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
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Variable que habilita la conexión global ["enabledConnectionGlobal"].
 * @var mixed $_ENV     Variable que habilita el tiempo de espera para bloqueos ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var mixed $log      Variable utilizada para almacenar y registrar información de logs.
 * @var mixed $body     Contenido del cuerpo de la solicitud HTTP.
 * @var mixed $URI      URI de la solicitud actual.
 * @var mixed $data     Datos decodificados del cuerpo de la solicitud en formato JSON.
 * @var mixed $response Respuesta generada por las operaciones realizadas.
 */

ini_set('display_errors', 'OFF');
if ($_REQUEST["debugFixed"] == '1') {
    error_reporting(E_ALL);
    ini_set("display_errors", "ON");

    $debugFixed = '1';
}
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Booongo;
use Backend\integrations\casino\TvBet;


$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));
$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];


if ($body != "") {
    $data = json_decode($body);


    if (strpos($URI, "GetUserData") !== false) {
        $ti = $data->ti;
        $sign = $data->si;
        $token = $data->to;
        $ed = $data->ed;
        $datos = $data;

        /* Procesamos */
        $TvBet = new TvBet($token, $sign);
        $response = ($TvBet->Auth());

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    }


    if (strpos($URI, "RefreshToken") !== false) {
        $ti = $data->ti;
        $sign = $data->si;
        $token = $data->to;
        $ed = $data->ed;

        $datos = $data;
        /* Procesamos */
        $TvBet = new TvBet($token, $sign);
        $response = ($TvBet->RefreshToken());

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    }

    if (strpos($URI, "MakePayment") !== false) {
        $ti = $data->ti;
        $sign = $data->si;
        $token = $data->to;
        $transactionId = $data->bid;
        $round = $data->bid;
        $tt = $data->tt;
        $sm = $data->sm;
        $pr = $data->pr;

        $transactionId = $transactionId . "_" . $tt;

        $datos = $data;

        /* Procesamos */
        $TvBet = new TvBet($token, $sign);

        $freespin = false;

        if ($data->pr != null && $data->pr != '') {
            $freespin = true;
        }
        if ($tt == '-1') {
            $response = ($TvBet->Debit("", $sm, $round, $transactionId, json_encode($datos), $freespin));
        }
        if ($tt == '1') {
            $response = ($TvBet->Credit("", $sm, $round, $transactionId, json_encode($datos)));
        }
        if ($tt == '2') {
            $response = ($TvBet->Rollback($sm, $round, $transactionId, "", json_encode($datos)));
        }
        if ($tt == '-2') {
            $response = ($TvBet->Debit("", $sm, $round, $transactionId, json_encode($datos), $freespin));
        }
        if ($tt == '4') {
            $response = ($TvBet->Credit("", $sm, $round, $transactionId, json_encode($datos)));
        }


        $log = "/" . time();

        $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    }

    if (strpos($URI, "GetPaymentInfo") !== false) {
        $ti = $data->ti;
        $sign = $data->si;
        $transactionId = $data->tid;
        $token = $data->to;


        $datos = $data;

        /* Procesamos */

        $TvBet = new TvBet($token, $sign);
        print_r($TvBet->GetPaymentInfo($transactionId));
    }

    if (strpos($URI, "GetPromoInfo") !== false) {
        $ti = $data->ti;
        $sign = $data->si;
        $pr = $data->pr;
        $token = $data->to;

        $TvBet = new TvBet($token, $sign);

        $response = ($TvBet->GetPromoInfo());
        $log = $log . "/" . time();

        $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    }
}



