<?php

/**
 * Este archivo actúa como controlador principal para enrutar las solicitudes entrantes
 * hacia los métodos correspondientes de la integración de casino Tomhorn.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Nicolas Guato <nicolas.guato@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     Public
 *
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Variable superglobal que contiene datos de entorno configurados para el script.
 * @var mixed $body     Contiene el cuerpo de la solicitud entrante en formato JSON.
 * @var mixed $URI      Almacena la URI de la solicitud entrante.
 * @var mixed $data     Objeto decodificado del cuerpo de la solicitud JSON.
 * @var mixed $response Almacena la respuesta generada por las operaciones realizadas.
 */
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Tomhorn;

$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

if ($body != "") {
    $data = json_decode($body);

    if (strpos($URI, "IdentifyPlayer") !== false) {
        $token = $data->token;
        $sign = $_REQUEST["sign"];

        $Tomhorn = new Tomhorn($token, $sign);
        $response = ($Tomhorn->Auth());
    }


    if (strpos($URI, "GetBalance") !== false) {
        $token = $data->sessionID;

        if ($token == '' || $token == '0') {
            $token = $data->name;
        }

        $Tomhorn = new Tomhorn($token, $sign);
        $response = ($Tomhorn->getBalance());
    }

    if (strpos($URI, "Deposit") !== false) {
        $token = $data->sessionID;
        $transactionId = $data->reference;
        $RoundId = $data->gameRoundID;
        $CreditAmount = $data->amount;

        $GameCode = $data->gameModule;

        $type = $data->type;
        $name = $data->name;
        $name = str_replace("Usuario", "", $name);

        $datos = $data;
        $Tomhorn = new Tomhorn($token, $sign, $name);
        $datos = json_encode($datos);

        if ($type == 2) {
            $freespin = true;

            $response = ($Tomhorn->Debit($GameCode, 0, $RoundId, 'FS' . $transactionId, $datos, true));
        }

        $response = $Tomhorn->Credit($GameCode, $CreditAmount, $RoundId, $transactionId, $datos, true, $freespin);
    }

    if (strpos($URI, "Withdraw") !== false) {
        $token = $data->sessionID;
        $transactionId = $data->reference;
        $RoundId = $data->gameRoundID;
        $DebitAmount = $data->amount;
        $GameCode = $data->gameModule;
        $name = $data->name;

        $datos = $data;

        $Tomhorn = new Tomhorn($token, $sign, $name);
        $response = ($Tomhorn->Debit($GameCode, $DebitAmount, $RoundId, $transactionId, json_encode($datos)));
    }


    if (strpos($URI, "Rollback") !== false) {
        $sign = $data->sign;
        $token = $data->token;
        $name = $data->name;

        $currency = $data->currency;
        $rollbackAmount = 0;
        $date = $data->date;
        $player = $data->PlayerId;
        $roundId = $data->RGSRelatedTransactionId;
        $transactionId = $data->RGSRelatedTransactionId;

        $datos = $data;

        $Tomhorn = new Tomhorn($token, $sign, $name);
        $response = ($Tomhorn->Rollback($rollbackAmount, $roundId, $transactionId, $player, json_encode($datos)));
    }

    if (strpos($URI, "CloseSession") !== false) {
        $token = $data->sessionId;

        $Tomhorn = new Tomhorn($token, $sign);
        $response = ($Tomhorn->getBalance());
    }


    if (strpos($URI, "NotifySession") !== false) {
        $token = $data->sessionId;

        $Tomhorn = new Tomhorn($token, $sign);
        $response = ($Tomhorn->refreshSesion());
    }

    print_r($response);
}
