<?php

/**
 * Este archivo contiene un script para procesar solicitudes relacionadas con la integración
 * de un sistema de casino, incluyendo operaciones de autenticación de usuarios, consulta de
 * balances y transacciones de usuarios.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\poker\ESAGAMING;
use Backend\integrations\poker\ESAGAMINGSERVICES;

// Configuración de variables globales
$_ENV["enabledConnectionGlobal"] = 1;

// Inicialización del log
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));
// Guardar log (comentado por defecto)

$body = file_get_contents('php://input');
$URI = $_SERVER['REQUEST_URI'];

if ($body != "") {
    $data = json_decode($body);

    /**
     * Manejo de la solicitud "UserGetInfo".
     * Procesa la autenticación de un usuario basado en los datos enviados.
     */
    if (strpos($URI, "UserGetInfo") !== false) {
        $userId = $data->userId;
        $username = $data->username;
        $password = $data->password;
        $partnerId = $data->partnerId;
        $source = $data->source;

        /* Procesamos */
        $ESAGAMING = new ESAGAMING($userId);
        $request = [
            "method" => "UserAutenticahte",
            "username" => $username,
            "password" => $password,
            "partnerId" => $partnerId,
            "source" => $source
        ];
        $response = $ESAGAMING->load(json_encode($request));

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        //Save string to log, use FILE_APPEND to append.

        //fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        print_r($response);
    }

    /**
     * Manejo de la solicitud "UserGetBalance".
     * Consulta el balance de un usuario basado en su ID.
     */
    if (strpos($URI, "UserGetBalance") !== false) {
        $Balance = $data;

        $userId = $data->userId;

        $ESAGAMING = new ESAGAMING($userId);
        $request = [
            "method" => "UserGetBalance",
            "userId" => $userId,
        ];
        $response = $ESAGAMING->load(json_encode($request));
        $log = $log . "/" . time();

        $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
        $log = $log . ($response);
        //Save string to log, use FILE_APPEND to append.

        //fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        print_r($response);
    }

    /**
     * Manejo de la solicitud "UserTransaction".
     * Procesa una transacción de usuario, incluyendo débitos y créditos.
     */
    if (strpos($URI, "UserTransaction") !== false) {
        $DebitAmount = $data->context->total_bet_amount;
        $CreditAmount = $data->context->total_win_amount;
        $sessionId = $data->context->session_id;
        $AAMS_token = $data->context->AAMS_token;
        $gameId = $data->context->game_id;
        $gameName = $data->context->game_name;
        $gameProvider = $data->context->game_provider;
        $userId = $data->userId;
        $amount = $data->amount;
        $currency = $data->currency;
        $descripcion = $data->descripcion;
        $sessionState = $data->sessionState;
        $transactionId = $data->transactionId;

        $ESAGAMING = new ESAGAMING($userId);

        $DebitAmount = intval($DebitAmount) / 100;
        $CreditAmount = intval($CreditAmount) / 100;
        $request = [
            "transactionId" => (string)$transactionId,
            "userId " => (string)$userId,
            "amount" => (string)$amount,
            "currency" => (string)$currency,
            "context" => array(
                "session_id" => (string)$sessionId,
                " game_id" => (string)$gameId,
                " AAMS_token" => (string)$AAMS_token,
                " game_name" => (string)$gameName,
                " game_provider" => (string)$gameProvider,
                "total_bet_amount" => (string)$DebitAmount,
                "total_win_amount " => (string)$CreditAmount
            ),
            "description " => (string)$descripcion,
            "sessionState " => (string)$sessionState
        ];


        $respuestaDebit = $ESAGAMING->load(json_encode($request));

        $transactionId = "credit" . $transactionId;


        $datos = $data;

        /* Procesamos */


        $ESAGAMING = new ESAGAMING($userId);


        $respuestaCredit = $ESAGAMING->load(json_encode($request));


        $log = $log . "/" . time();

        $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
        $log = $log . ($respuestaCredit);
        // Guardar log (comentado por defecto)
        print_r($respuestaCredit);
    }
}



