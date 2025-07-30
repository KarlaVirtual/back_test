<?php
/**
 * Este archivo contiene la implementación de la API de casino 'Mascot'.
 * Procesa solicitudes relacionadas con el balance, depósitos, retiros y reversión de transacciones.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Jerson Polo <David.polomu@gmail.com>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Activa el modo de depuración si se cumplen ciertas condiciones ['debug'].
 * @var mixed $_ENV     Activa un modo de depuración adicional si se cumplen ciertas condiciones ['debugFixed2'].
 * @var mixed $_ENV     Indica si la conexión global está habilitada ['enabledConnectionGlobal'].
 * @var mixed $_ENV     Configuración para habilitar el tiempo de espera de bloqueo ['ENABLEDSETLOCKWAITTIMEOUT'].
 * @var mixed $log      Variable utilizada para almacenar información de registro.
 * @var mixed $body     Contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data     Datos decodificados del cuerpo de la solicitud HTTP.
 * @var mixed $response Respuesta generada por las operaciones realizadas.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Mascot;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
}

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
    $_ENV["debugFixed2"] = '1';
}


$_ENV["enabledConnectionGlobal"] = 1;

$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));
$body = file_get_contents('php://input');
$length = strlen($body);

header('Content-Type: application/json');
$URI = $_SERVER['REQUEST_URI'];

if ($body != "") {
    $data = json_decode($body);


    if (strpos($data->method, "getBalance") !== false) {
        $callerId = $data->params->callerId;
        $playerName = $data->params->playerName;
        $playerName = explode("Usuario", $playerName);
        $playerName = $playerName[1];
        $currency = $data->params->currency;
        $gameId = $data->params->gameId;
        $token = $data->params->sessionId;
        $sessionAlternativeId = $data->params->sessionAlternativeId;
        $bonusId = $data->params->bonusId;
        $id = $data->id;


        /* Procesamos */
        $Mascot = new Mascot($token, $playerName);
        $response = ($Mascot->getBalance($id, $bonusId));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    }

    if (strpos($data->method, "withdrawAndDeposit") !== false) {
        $callerId = $data->params->callerId;
        $playerName = $data->params->playerName;
        $playerName = explode("Usuario", $playerName);
        $playerName = $playerName[1];
        $withdraw = floatval(round($data->params->withdraw, 2) / 100);
        $deposit = floatval(round($data->params->deposit, 2) / 100);
        $currency = $data->params->currency;
        $transactionRef = $data->params->transactionRef;
        $gameRoundRef = $data->params->gameRoundRef;
        $gameId = $data->params->gameId;
        $source = $data->params->source;
        $reason = $data->params->reason;
        $token = $data->params->sessionId;
        $sessionAlternativeId = $data->params->sessionAlternativeId;
        $spinDetails = $data->params->spinDetails;
        $bonusId = $data->params->bonusId;
        $chargeFreerounds = $data->params->chargeFreerounds;

        $id = $data->id;

        $datos = $data;

        /* Procesamos */


        $Mascot = new Mascot($token, $playerName);


        $freespin = false;

        if ($bonusId != "" && $chargeFreerounds > 0) {
            $freespin = true;
            $withdraw = 0;
        }

        $respuestaDebit = ($Mascot->Debit($gameId, $withdraw, $gameRoundRef, $transactionRef, json_encode($datos), $freespin, $id, $bonusId, $chargeFreerounds));
        $respuestaDebit = json_decode($respuestaDebit);


        if ($respuestaDebit->error == "") {
            $transactionId = "credit" . $transactionRef;

            //$RoundId = $data->RoundId;

            $datos = $data;

            /* Procesamos */


            $Mascot = new Mascot($token, $playerName);


            $respuestaCredit = $Mascot->Credit($gameId, $deposit, $gameRoundRef, $transactionId, json_encode($datos), $id, $freespin, $bonusId);


            $log = "";
            $log = $log . "/" . $transactionId . ' TIME ' . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($respuestaCredit);
            print_r($respuestaCredit);
        } else {
            $respuestaDebit = json_encode($respuestaDebit);


            $log = "";
            $log = $log . "/" . $transactionRef . ' TIME ' . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($respuestaDebit);
            print_r($respuestaDebit);
        }
    }


    if (strpos($data->method, "rollbackTransaction") !== false) {
        $callerId = $data->params->callerId;
        $token = $data->params->sessionId;
        $sessionAlternativeId = $data->params->sessionAlternativeId;
        $transactionRef = $data->params->transactionRef;
        $gameId = $data->params->gameId;
        $playerName = $data->params->playerName;
        $playerName = explode("Usuario", $playerName);
        $playerName = $playerName[1];
        $rollbackAmount = 0;
        $roundId = $data->params->roundId;


        $id = $data->id;

        $datos = $data;

        /* Procesamos */

        $Mascot = new Mascot($token, $playerName);
        $response = $Mascot->Rollback($rollbackAmount, $roundId, $transactionRef, $playerName, json_encode($datos), $id);

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    }
}



