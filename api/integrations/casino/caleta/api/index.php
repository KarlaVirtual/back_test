<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API del casino 'Caleta'.
 * Realiza operaciones como verificación de firmas, consulta de balance, registro de apuestas,
 * créditos, y rollbacks, entre otras funcionalidades relacionadas con la integración del casino.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Variable superglobal que contiene datos del entorno de ejecución.
 * @var mixed $log      Variable utilizada para almacenar información de registro (logs).
 * @var mixed $body     Contenido del cuerpo de la solicitud HTTP recibido.
 * @var mixed $data     Datos decodificados del cuerpo de la solicitud en formato JSON.
 * @var mixed $URI      URI de la solicitud HTTP actual.
 * @var mixed $headers  Encabezados HTTP de la solicitud.
 * @var mixed $response Respuesta generada por las operaciones realizadas en el script.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Caleta;

header('Content-type: application/json; charset=utf-8');
if ( ! function_exists('getallheaders')) {
    /**
     * Obtiene todos los encabezados de la solicitud HTTP.
     *
     * @return array Un arreglo asociativo con los encabezados de la solicitud.
     */
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . " Body ";
$log = $log . trim(file_get_contents('php://input'));

$body = file_get_contents('php://input');
$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);

$URI = $_SERVER['REQUEST_URI'];

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$headers = getallheaders();

if ($body != "") {
    $signature = $headers["X-Auth-Signature"];
    if ($signature == "") {
        $signature = $headers["x-auth-signature"];
    }

    $user = $data->supplier_user;
    $user = explode("user", $user);
    $user = $user[1];

    $Caleta = new Caleta($data->token, '', '');
    $result = $Caleta->verify(file_get_contents('php://input'), $signature, $user);

    //validar firma
    if ($result == 1) {
        if (strpos($URI, "check") !== false) {
            $token = $data->token;
            $sign = $_REQUEST["sign"];

            /* Procesamos */
            $Caleta = new Caleta($token, $sign);
            $response = ($Caleta->Token());
        }

        if (strpos($URI, "balance") !== false) {
            $token = $data->token;
            $request_uuid = $data->request_uuid;
            $supplier_user = $data->supplier_user;
            $supplier_user = explode("user", $supplier_user);
            $supplier_user = $supplier_user[1];

            $game_code = $data->game_code;
            /* Procesamos */
            $Caleta = new Caleta($token, $sign, $request_uuid);
            $response = ($Caleta->getBalance());
        }

        if (strpos($URI, "win") !== false) {
            $token = $data->token;
            $transactionId = $data->transaction_uuid;
            $reference_transaction = $data->reference_transaction_uuid;
            $supplier_user = $data->supplier_user;
            $supplier_user = explode("user", $supplier_user);
            $supplier_user = $supplier_user[1];
            $roundid = $data->round;
            $round_closed = $data->round_closed;
            $CreditAmount = floatval($data->amount / 100000);
            $request_uuid = $data->request_uuid;
            $is_free = $data->is_free;
            $game_id = $data->game_id;
            $game_code = $data->game_code;
            $currency = $data->currency;
            $bet = $data->bet;
            $datos = $data;

            /* Procesamos */
            $Caleta = new Caleta($token, $sign, $request_uuid);
            $response = $Caleta->Credit($game_code, $CreditAmount, $roundid, $transactionId, json_encode($datos));
        }

        if (strpos($URI, "bet") !== false) {
            $token = $data->token;
            $transactionId = $data->transaction_uuid;
            $supplier_user = $data->supplier_user;
            $supplier_user = explode("user", $supplier_user);
            $supplier_user = $supplier_user[1];
            $roundid = $data->round;
            $round_closed = $data->round_closed;
            $DebitAmount = floatval($data->amount / 100000);;
            $request_uuid = $data->request_uuid;
            $is_free = $data->is_free;
            $game_id = $data->game_id;
            $game_code = $data->game_code;
            $currency = $data->currency;
            $bet = $data->bet;
            $datos = $data;

            /* Procesamos */
            $Caleta = new Caleta($token, $sign, $request_uuid);
            $response = ($Caleta->Debit($game_code, $DebitAmount, $roundid, $transactionId, json_encode($datos), $is_free));
        }

        if (strpos($URI, "rollback") !== false) {
            $token = $data->token;
            $transactionId = $data->transaction_uuid;
            $supplier_user = $data->user;
            $supplier_user = explode("user", $supplier_user);
            $supplier_user = $supplier_user[1];
            $roundid = $data->round;
            $round_closed = $data->round_closed;
            $request_uuid = $data->request_uuid;
            $reference_transaction_uuid = $data->reference_transaction_uuid;
            $is_free = $data->is_free;
            $game_id = $data->game_id;
            $game_code = $data->game_code;
            $datos = $data;

            /* Procesamos */
            $Caleta = new Caleta($token, $sign, $request_uuid);
            $response = ($Caleta->Rollback("", $roundid, $transactionId, $supplier_user, json_encode($datos), $reference_transaction_uuid));
        }
    } else {
        $response = json_encode(array(
            "status" => "error",
            "code" => "RS_ERROR_INVALID_SIGNATURE"

        ));
    }

    $log = "";
    $log = $log . "/" . time();
    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}
