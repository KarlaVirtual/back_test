<?php

/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la integración
 * de un casino a través de la API de WAC. Proporciona métodos para obtener información de jugadores,
 * consultar saldos, realizar apuestas, registrar ganancias y procesar reembolsos de transacciones.
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
 * @var string  $_ENV     Configuración para habilitar el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"] .
 * @var string  $URI      URI de la solicitud actual.
 * @var string  $body     Cuerpo de la solicitud en formato XML.
 * @var string  $method   Método solicitado en la petición.
 * @var mixed   $response Respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Wac;

header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$URI = $_SERVER['REQUEST_URI'] . $_SERVER['REQUEST_METHOD'];
$body = trim(file_get_contents('php://input'));
$method = "";

if ($body != "") {
    Header('Content-type: application/xml');
    $data = simplexml_load_string($body);
    $method = (string)($data->method->attributes()->name);
}

switch ($method) {
    case "getPlayerInfo":

        $token = (string)($data->method->params->token->attributes()->value);
        $login = (string)($data->method->credentials->attributes()->login);
        $password = (string)($data->method->credentials->attributes()->password);

        $Wac = new Wac($token, $method, $login, $password);
        $respuesta = $Wac->getRespuesta();

        if ($respuesta !== null) {
            $respuesta2 = simplexml_load_string($respuesta);
            $errorCode = (string)($respuesta2->result->returnset->errorCode->attributes()->value);
        }

        if ($errorCode != '' && $errorCode == 103) {
            $response = $respuesta;
        } else {
            $response = ($Wac->Auth());
        }
        break;

    case "getBalance":

        $token = (string)($data->method->params->token->attributes()->value);
        $login = (string)($data->method->credentials->attributes()->login);
        $password = (string)($data->method->credentials->attributes()->password);

        $Wac = new Wac($token, $method, $login, $password);
        $respuesta = $Wac->getRespuesta();

        if ($respuesta !== null) {
            $respuesta2 = simplexml_load_string($respuesta);
            $errorCode = (string)($respuesta2->result->returnset->errorCode->attributes()->value);
        }

        if ($errorCode != '' && $errorCode == 103) {
            $response = $respuesta;
        } else {
            $response = ($Wac->getBalance());
        }
        break;

    case "bet":

        $token = (string)($data->method->params->token->attributes()->value);
        $transactionId = (string)($data->method->params->transactionId->attributes()->value);
        $amount = (string)($data->method->params->amount->attributes()->value);
        $gameReference = (string)($data->method->params->gameReference->attributes()->value);
        $roundId = (string)($data->method->params->roundId->attributes()->value);
        $login = (string)($data->method->credentials->attributes()->login);
        $password = (string)($data->method->credentials->attributes()->password);

        $Wac = new Wac($token, $method, $login, $password);

        $amount = floatval(round($amount, 2) / 100);

        $respuesta = $Wac->getRespuesta();

        if ($respuesta !== null) {
            $respuesta2 = simplexml_load_string($respuesta);
            $errorCode = (string)($respuesta2->result->returnset->errorCode->attributes()->value);
        }

        if ($errorCode != '' && $errorCode == 103) {
            $response = $respuesta;
        } else {
            $response = $Wac->Debit($gameReference, $amount, $roundId, $transactionId, json_encode($data));
        }
        break;

    case "win":

        $token = (string)($data->method->params->token->attributes()->value);
        $transactionId = (string)($data->method->params->transactionId->attributes()->value);
        $amount = (string)($data->method->params->amount->attributes()->value);
        $gameReference = (string)($data->method->params->gameReference->attributes()->value);
        $roundId = (string)($data->method->params->roundId->attributes()->value);

        $amount = floatval(round($amount, 2) / 100);

        $login = (string)($data->method->credentials->attributes()->login);
        $password = (string)($data->method->credentials->attributes()->password);

        $Wac = new Wac($token, $method, $login, $password);

        $respuesta = $Wac->getRespuesta();

        if ($respuesta !== null) {
            $respuesta2 = simplexml_load_string($respuesta);
            $errorCode = (string)($respuesta2->result->returnset->errorCode->attributes()->value);
        }

        if ($errorCode != '' && $errorCode == 103) {
            $response = $respuesta;
        } else {
            $response = $Wac->Credit($gameReference, $amount, $roundId, $transactionId, json_encode($data), false);
        }
        break;

    case "refundTransaction":

        $token = (string)($data->method->params->token->attributes()->value);
        $transactionId = (string)($data->method->params->transactionId->attributes()->value);
        $amount = (string)($data->method->params->amount->attributes()->value);
        $gameReference = (string)($data->method->params->gameReference->attributes()->value);
        $roundId = (string)($data->method->params->roundId->attributes()->value);
        $refundedTransactionId = (string)($data->method->params->refundedTransactionId->attributes()->value);
        $login = (string)($data->method->credentials->attributes()->login);
        $password = (string)($data->method->credentials->attributes()->password);

        $amount = floatval(round($amount, 2) / 100);

        $Wac = new Wac($token, $method, $login, $password);

        $respuesta = $Wac->getRespuesta();

        if ($respuesta !== null) {
            $respuesta2 = simplexml_load_string($respuesta);
            $errorCode = (string)($respuesta2->result->returnset->errorCode->attributes()->value);
        }

        if ($errorCode != '' && $errorCode == 103) {
            $response = $respuesta;
        } else {
            $response = $Wac->Rollback($amount, $roundId, $refundedTransactionId, json_encode($data));
        }
        break;

    default:

        break;
}

print_r($response);
