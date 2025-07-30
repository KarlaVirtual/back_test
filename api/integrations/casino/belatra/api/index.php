<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API de casino 'Belatra'.
 * Incluye funcionalidades para jugar, realizar rollback de transacciones y manejar freespins.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    ninguna
 * @since      2025-05-09
 */

/**
 * Configura el encabezado de respuesta como JSON.
 */
header('Content-type: application/json');

/**
 * Carga automática de clases mediante Composer.
 */
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Belatra;

/**
 * Obtiene la URI de la solicitud.
 *
 * @var string $URI URI de la solicitud actual.
 */
$URI = $_SERVER['REQUEST_URI'];
$_ENV["enabledConnectionGlobal"] = 1;

/**
 * Activa el modo de depuración si se proporciona el parámetro correspondiente.
 */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

/**
 * Obtiene el cuerpo de la solicitud.
 *
 * @var string $body Contenido del cuerpo de la solicitud.
 */
$body = file_get_contents('php://input');

/**
 * Analiza la URI para obtener los parámetros de consulta.
 *
 * @var array $url   Información de la URI.
 * @var array $query Parámetros de consulta extraídos de la URI.
 */
$url = parse_url($URI);
parse_str($url['query'], $query);

/**
 * Obtiene la firma de la solicitud desde los encabezados.
 *
 * @var string $xRequestSign Firma de la solicitud.
 */
$xRequestSign = '';
if (isset($_SERVER['HTTP_X_REQUEST_SIGN'])) {
    $xRequestSign = $_SERVER['HTTP_X_REQUEST_SIGN'];
}

/**
 * Registra información de la solicitud en un log.
 *
 * @var string $date Fecha y hora actual.
 * @var string $log  Contenido del log generado.
 */
$date = date("Y-m-d H:i:s");
$log = "";
$log = $log . "\r\n";
$log = $log . "************ DATE: " . $date . "************" . " / " . time();
$log = $log . "\r\n";
$log = $log . "\r\n" . "--------------DATA REQUEST----------------" . "\r\n";
$log = $log . "\r\n";
$log = $log . (http_build_query($_REQUEST)) . "\r\n";
$log = $log . "\r\n";
$log = $log . trim(file_get_contents('php://input'));
$log = $log . "\r\n" . "---------------------------------" . "\r\n";

/**
 * Decodifica el cuerpo de la solicitud como JSON.
 *
 * @var object $data Datos decodificados de la solicitud.
 */
$data = json_decode($body);

/**
 * Procesa la URI para obtener el endpoint solicitado.
 *
 * @var string $URI Endpoint solicitado.
 */
$URI = explode('/', $URI);
$URI = $URI[count($URI) - 1];
$URI = explode('?', $URI);
$URI = $URI[0];

/**
 * Extrae datos del cuerpo de la solicitud.
 *
 * @var string $playerId  ID del jugador.
 * @var string $token     Token de sesión.
 * @var string $sessionId ID de la sesión.
 * @var string $game      Nombre del juego.
 */
$playerId = $data->user_id;
$token = $data->session_id;
$sessionId = $data->session_id;
$game = $data->game;

/**
 * Instancia de la clase Belatra para manejar las operaciones del casino.
 *
 * @var Belatra $Belatra Objeto para interactuar con la API de Belatra.
 */
$Belatra = new Belatra($playerId, $token, $sessionId);

/**
 * Genera la firma de autenticación para la solicitud.
 *
 * @var string $AUTH_TOKEN Token de autenticación generado.
 * @var string $signature  Firma generada para validar la solicitud.
 */
$AUTH_TOKEN = $Belatra->getSignature($game);
$signature = hash_hmac('sha256', $body, $AUTH_TOKEN);

/**
 * Valida la firma de la solicitud y procesa las acciones correspondientes.
 */
if ($signature == $xRequestSign) {
    if ($URI == "play") {
        /**
         * Procesa la acción de jugar.
         *
         * @var string $currency Moneda utilizada.
         * @var string $gameId   ID del juego.
         * @var bool   $finished Indica si el juego ha finalizado.
         */
        $currency = $data->currency;
        $playerId = $data->user_id;
        $game = $data->game;
        $gameId = $data->game_id;
        $token = $data->session_id;
        $sessionId = $data->session_id;
        $finished = false;

        $Belatra = new Belatra($playerId, $token, $sessionId);

        if ($data->actions[0]->action == 'bet' || $data->actions[0]->action == 'win' || $gameId != '') {
            /**
             * Respuesta inicial para la acción de jugar.
             *
             * @var array $response Respuesta generada.
             */
            $response = array(
                'balance' => 1000,
                'game_id' => $gameId,
                'transactions' => array()
            );

            foreach ($data->actions as $param) {
                /**
                 * Procesa cada acción dentro de la solicitud.
                 *
                 * @var string $action         Tipo de acción (bet, win, etc.).
                 * @var float  $amount         Monto de la acción.
                 * @var float  $amountCurrency Monto en la moneda especificada.
                 * @var string $transactionId  ID de la transacción.
                 * @var string $roundId        ID de la ronda.
                 * @var bool   $isfreeSpin     Indica si es un giro gratis.
                 */
                $action = $param->action;
                $amount = $param->amount;
                $amountCurrency = $param->amount_currency;
                $transactionId = $param->action_id;
                $roundId = $gameId;

                $isfreeSpin = false;
                if (floatval($amountCurrency) == 0) {
                    $isfreeSpin = true;
                }

                if ($action == 'bet') {
                    $respp = $Belatra->Debit($game, $amountCurrency, $roundId, $transactionId, $data, $finished, $currency, $action, $isfreeSpin, $amount);
                } elseif ($action == 'win') {
                    $respp = $Belatra->Credit($game, $amountCurrency, $roundId, $transactionId, $data, $isfreeSpin, $finished, $currency, $amount, $action);
                }


                $respuesta = json_decode($respp);
                if ( ! isset($respuesta->code)) {
                    $response['balance'] = $respuesta->balance;
                    unset($respuesta->balance);
                }
                array_push($response['transactions'], $respuesta);
            }

            if ($response['transactions'] == []) {
                unset($response['transactions']);
                $resp = $Belatra->Auth($currency);
                $resp = json_decode($resp);
                $response['balance'] = $resp->balance;
            }

            if (isset($respuesta->code)) {
                $response = json_encode($respuesta);
            } else {
                $response = json_encode($response);
            }
        } else {
            $response = $Belatra->Auth($currency);
        }
    } elseif ($URI == "rollback") {
        /**
         * Procesa la acción de rollback.
         */
        $playerId = $data->user_id;
        $currency = $data->currency;
        $game = $data->game;
        $gameId = $data->game_id;
        $finished = false;
        $sessionId = $data->session_id;
        $token = $data->session_id;

        $Belatra = new Belatra($playerId, $token, $sessionId);

        $response = array(
            'balance' => 1000,
            'game_id' => $gameId,
            'transactions' => array()
        );

        foreach ($data->actions as $param) {
            $action = $param->action;
            $originalAction = $param->original_action_id;
            $actionId = $param->action_id;
            $roundId = $gameId;

            if ($action == 'rollback') {
                $respp = $Belatra->rollback(0, $roundId, $actionId, $data, $finished, $game, $originalAction, $action);
            }

            $respuesta = json_decode($respp);
            if ( ! isset($respuesta->code)) {
                $response['balance'] = $respuesta->balance;
                unset($respuesta->balance);
            }
            array_push($response['transactions'], $respuesta);
        }

        if (isset($respuesta->code)) {
            $response = json_encode($respuesta);
        } else {
            $response = json_encode($response);
        }
    } elseif ($URI == "freespins") {
        /**
         * Procesa la acción de freespins.
         */
        $currency = $data->currency;
        $playerId = $data->user_id;
        $game = $data->game;
        $token = $data->session_id;
        $sessionId = $data->session_id;
        $amount = $data->total_amount;
        $amountCurrency = $data->total_amount_currency;

        $Belatra = new Belatra($playerId, $token, $sessionId);

        $action = 'free';
        $transactionId = $data->action->action_id;
        $roundId = $data->action->action_id;

        $isfreeSpin = true;
        $respp = $Belatra->Debit($game, 0, $roundId, 'FSD_' . $transactionId, $data, false, $currency, $action, $isfreeSpin, $amount);
        $respp = $Belatra->Credit($game, $amountCurrency, $roundId, 'FSC_' . $transactionId, $data, $isfreeSpin, false, $currency, $amount, $action);

        $response = $respp;
    }
} else {
    /**
     * Respuesta en caso de que la firma no coincida.
     *
     * @var array $response Respuesta de error.
     */
    $response = array(
        "code" => 403,
        "message" => "Forbidden. (Request sign doesn't match)",
        "balance" => 0
    );

    $response = json_encode($response);
}

/**
 * Registra la respuesta en un log.
 */
$log = "";
$log = "";
$log = $log . "/" . time();
$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($response);

print_r($response);
