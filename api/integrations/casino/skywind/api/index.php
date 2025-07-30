<?php

/**
 * Este archivo contiene un script para procesar solicitudes relacionadas con la API de casino 'Skywind'.
 * Maneja operaciones como validación de tickets, obtención de balances, débitos, créditos y rollbacks.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 *
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV         Variable superglobal que contiene configuraciones de entorno.
 * @var mixed $URI          Contiene la URI de la solicitud actual.
 * @var mixed $body         Contiene el cuerpo de la solicitud en formato JSON.
 * @var mixed $data         Contiene los datos decodificados del cuerpo de la solicitud.
 * @var mixed $requestOrder Cadena que almacena los parámetros de la solicitud ordenados alfabéticamente.
 * @var mixed $status       Indica el estado de validación de la solicitud.
 * @var mixed $log          Almacena información de registro para depuración.
 * @var mixed $response     Contiene la respuesta generada por las operaciones realizadas.
 */
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Skywind;

header('Content-type: application/json');

$URI = $_SERVER['REQUEST_URI'];

$body = file_get_contents('php://input');

if ($body != "") {
    $data = json_decode($body);
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($URI);
$log = $log . (http_build_query($_REQUEST));
$log = $log . json_encode($data);

$requestOrder = "";
ksort($_REQUEST);
$cont = 0;

foreach ($_REQUEST as $key => $val) {
    if ($key != "hash") {
        if ($cont == 0) {
            $requestOrder .= "$key=$val";
        } else {
            $requestOrder .= "&$key=$val";
        }
        $cont++;
    }
}

$ConfigurationEnvironment = new ConfigurationEnvironment();

$status = true;

if (($data == "" || $data->ticket == "" || $data->ticket == null)) {
    $status = false;
}

if ((strpos($URI, "validate_ticket") !== false)) {
    $status = true;
    if (json_encode($data) == "{}" || $data == "") {
        $status = false;
    }
}

if ((strpos($URI, "get_balance") !== false)) {
    $status = true;
    if (json_encode($data) == "{}" || $data == "") {
        print_r(
            '{
            "error_code": -1,
            "error_msg": "Merchant internal error"
           }'
        );
        exit();
        $status = false;
    }
}

if ((strpos($URI, "debit") !== false)) {
    $status = true;
    if (json_encode($data) == "{}" || $data == "") {
        print_r(
            '{
            "error_code": -1,
            "error_msg": "Merchant internal error"
           }'
        );
        exit();
        $status = false;
    }
}

if ((strpos($URI, "credit") !== false)) {
    $status = true;
    if (json_encode($data) == "{}" || $data == "") {
        print_r(
            '{
            "error_code": -1,
            "error_msg": "Merchant internal error"
           }'
        );
        exit();
        $status = false;
    }
}

if ((strpos($URI, "rollback") !== false)) {
    $status = true;
    if (json_encode($data) == "{}" || $data == "") {
        $status = false;
    }
}


if ($status) {
    if (strpos($URI, "get_ticket") !== false) {
        $hash = $data->hash;
        $ticket = $data->ticket;
        $merch_id = $data->merch_id;
        $merch_pwd = $data->merch_pwd;
        $cust_id = $data->cust_id;
        $single_session = $data->single_session;
        $currency_code = $data->currency_code;

        $Skywind = new Skywind($ticket, $cust_id, $merch_id, $merch_pwd);
        $response = $Skywind->CreateTicket($merch_id);
    } elseif (strpos($URI, "validate_ticket") !== false) {
        $hash = $data->hash;

        $ticket = $data->ticket;
        $merch_id = $data->merch_id;
        $merch_pwd = $data->merch_pwd;
        $ip = $data->ip;

        /* Procesamos */
        $Skywind = new Skywind($ticket, "", $merch_id, $merch_pwd);
        $respuesta = $Skywind->getRespuesta();

        if ($respuesta != '' && json_decode($respuesta)->error_code == -1) {
            $response = $respuesta;
        } else {
            $response = $Skywind->Auth($merch_id, $merch_pwd, $ip);
        };

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    } elseif (strpos($URI, "get_balance") !== false) {
        $hash = $_REQUEST["hash"];

        $merch_id = $data->merch_id;
        $merch_pwd = $data->merch_pwd;
        $cust_id = $data->cust_id;
        $cust_session_id = $data->cust_session_id;
        $game_code = $data->game_code;


        /* Procesamos */
        $Skywind = new Skywind($cust_session_id, $cust_id, $merch_id, $merch_pwd);
        $respuesta = $Skywind->getRespuesta();

        if (json_decode($respuesta)->error_code == -1) {
            $response = $respuesta;
        } else {
            $response = $Skywind->getBalance($cust_id, $merch_id, $merch_pwd);
        };

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    } else {
        $hash = $_REQUEST["hash"];

        $datos = $data;

        $merch_id = $data->merch_id;
        $merch_pwd = $data->merch_pwd;
        $cust_id = $data->cust_id; //UserId - PlayerId
        $cust_session_id = $data->cust_session_id; //Token
        $round_id = $data->round_id;
        $amount = $data->amount;
        $currency_code = $data->currency_code;
        $game_code = $data->game_code;
        $trx_id = $data->trx_id;
        $game_id = $data->game_id;
        $event_type = $data->event_type;
        $event_id = $data->event_id; //TransactionId
        $timestamp = $data->timestamp;
        $game_type = $data->game_type;
        $platform = $data->platform;
        $promo_id = $data->promo_id;
        $promo_pid = $data->promo_pid;
        $jp_contribution = $data->jp_contribution;
        $isbonus = false;

        if (strpos($URI, "debit")) {
            $Skywind = new Skywind($cust_session_id, $cust_id, $merch_id, $merch_pwd);

            if ($trx_id == "" || $trx_id == null) {
                print_r(
                    '{
                    "error_code": -1,
                    "error_msg": "Merchant internal error"
                   }'
                );
                exit();
            }


            if ($event_type === "free-bet") {
                print_r(
                    '{
                    "error_code": -5,
                    "error_msg": "Insufficient free bets Balance"
                   }'
                );
                exit();
            }

            if ($game_type === "free-bet") {
                $respuesta = $Skywind->Debit($game_code, 0, $round_id, 'FS' . $trx_id, json_encode($datos), $event_id, $trx_id, $merch_id, $merch_pwd);
            } else {
                $respuesta = $Skywind->Debit($game_code, $amount, $round_id, $event_type . '_' . $trx_id, json_encode($datos), $event_id, $trx_id, $merch_id, $merch_pwd);
            }
        } elseif (strpos($URI, "credit")) {
            $game_status = $data->game_status;

            $Skywind = new Skywind($cust_session_id, $cust_id, $merch_id, $merch_pwd);


            if ($game_status === "settled") {
                $respuesta = $Skywind->Credit($game_code, $amount, $round_id, $event_type . '_' . $trx_id, json_encode($datos), $isbonus, $event_id, $trx_id, $merch_id, $merch_pwd);

                if (json_decode($respuesta)->error_code == 0) {
                    $respuesta = $Skywind->EndRound($game_code, $round_id, json_encode($datos), $trx_id);
                }
            } else {
                $respuesta = $Skywind->Credit($game_code, $amount, $round_id, $event_type . '_' . $trx_id, json_encode($datos), $isbonus, $event_id, $trx_id, $merch_id, $merch_pwd);
            }
        } elseif (strpos($URI, "rollback")) {
            $Skywind = new Skywind($cust_session_id, $cust_id, $merch_id, $merch_pwd);

            $respuesta = $Skywind->Rollback($amount, "", "bet_" . $trx_id, $cust_id, json_encode($datos), $event_id, $trx_id, $merch_id, $merch_pwd);
        }

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($respuesta);

        print_r($respuesta);
    }
}



