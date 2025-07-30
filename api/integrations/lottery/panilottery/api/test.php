<?php

/**
 * Archivo principal para manejar las integraciones con Panilottery.
 *
 * Este script procesa solicitudes relacionadas con autenticación, balance, débitos, créditos y rollbacks
 * para la integración con el sistema Panilottery. Utiliza la clase `Panilottery` para realizar las operaciones
 * correspondientes y genera logs de las transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Documentación generada automáticamente para este archivo
 *
 * @var mixed $list Variable que almacena una lista de elementos.
 * @var mixed $item Variable que almacena un elemento genérico en una lista o estructura de datos.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Panilottery;
header('Content-Type: application/json');
$_ENV["enabledConnectionGlobal"]=1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"]='1';

$_ENV["debug"]=true;

/**
 * Genera un log inicial con la URI de la solicitud y el cuerpo recibido.
 */
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$body = '{"userKey":"373P7436658Pmnxlia3forr0b4lccg","sign":"d3650dfd65951b4236dc2a44316e9ed0","amount":5.0,"referenceInternal":"62f508b967173"}';

$URI = $_SERVER['REQUEST_URI'];
$URI='debit';
if ($body != "") {
    $data = json_decode($body);
    $sign = $data->sign;




    if (strpos($URI, "authenticate") !== false) {

        $token = $data->token;

        /* Procesamos */
        $Panilottery = new Panilottery($token, $sign);
        $response = ($Panilottery->Auth());

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);

    }

    // Procesa solicitudes de balance
    if (strpos($URI, "balance") !== false) {

        $token = $data->token;

        /* Procesamos */
        $Panilottery = new Panilottery($token, $sign);
        $response=($Panilottery->getBalance());

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);


    }

    // Procesa solicitudes de débito
    if (strpos($URI, "debit") !== false) {

        print_r('entro2');
        print_r($data);

        $token = $data->token;


        /* Procesamos */

        $token = $data->token;
        $amount = ($data->amount);
        $GameCode = $data->gamecode;
        $transactionId = $data->transactionid;
        $RoundId = $data->roundid;


        $datos = $data;

        /* Procesamos */


        $Panilottery = new Panilottery($token, $sign);

        print_r('antes');

        $respuestaCredit = $Panilottery->Debit($GameCode, $amount, $RoundId, $transactionId, json_encode($datos));

        print_r('respuestaCredit');
        print_r($respuestaCredit);


    }

    // Procesa solicitudes de crédito
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


        $Panilottery = new Panilottery($token, $sign);


        $respuestaCredit = $Panilottery->Credit($GameCode, $amount, $RoundId, $transactionId, json_encode($datos));

        print_r($respuestaCredit);


    }

    // Procesa solicitudes de rollback
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


        $Panilottery = new Panilottery($token, $sign);


        $respuestaCredit = $Panilottery->Rollback( $amount, $transactionRollback, $transactionId, json_encode($datos));

        print_r($respuestaCredit);


    }






}



