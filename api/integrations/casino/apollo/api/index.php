<?php

/**
 * Este archivo actúa como controlador principal para enrutar las solicitudes entrantes
 * hacia los métodos correspondientes de la integración de casino Apollo.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Nicolas Guato <nicolas.guato@virtualsoft.tech>
 * @version    Ninguna
 * @since      2017-10-18
 */

/**
 * Carga el autoloader de Composer para gestionar dependencias.
 */
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Apollo;

/**
 * Habilita la conexión global.
 *
 * @var int $_ENV ["enabledConnectionGlobal"] Indica si la conexión global está habilitada.
 */
$_ENV["enabledConnectionGlobal"] = 1;

/**
 * Obtiene el cuerpo de la solicitud entrante.
 *
 * @var string $body Contenido del cuerpo de la solicitud en formato JSON.
 */
$body = file_get_contents('php://input');

/**
 * URI de la solicitud entrante.
 *
 * @var string $URI URI de la solicitud.
 */
$URI = $_SERVER['REQUEST_URI'];

/**
 * Habilita el modo de depuración si se recibe un parámetro específico.
 */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

/**
 * Decodifica el cuerpo de la solicitud JSON.
 *
 * @var object $data Datos decodificados de la solicitud.
 */
$data = json_decode($body);

/**
 * Token de autenticación proporcionado en la solicitud.
 *
 * @var string $token Token de autenticación.
 */
$token = $data->token;

if ($token == "") {
    $token = $data->sessionId;
} else {
    $token = $data->token;
}

/**
 * Firma de la solicitud.
 *
 * @var string $sign Firma proporcionada en la solicitud.
 */
$sign = $_REQUEST["sign"];

/**
 * Instancia de la clase Apollo para manejar la integración.
 *
 * @var Apollo $Apollo Objeto de integración Apollo.
 */
$Apollo = new Apollo($token, $sign);

/**
 * Clave de respuesta generada por Apollo.
 *
 * @var string $responseKey Clave de respuesta.
 */
$responseKey = ($Apollo->getResponseKey());

if ($body != "") {
    $data = json_decode($body);

    /**
     * Identifica al jugador si la URI contiene "IdentifyPlayer".
     */
    if (strpos($URI, "IdentifyPlayer") !== false) {
        $token = $data->token;
        $sign = $_REQUEST["sign"];

        /* Procesamos */
        $Apollo = new Apollo($token, $sign);
        $response = ($Apollo->Auth());
    }

    /**
     * Obtiene el balance del jugador si la URI contiene "GetBalance".
     */
    if (strpos($URI, "GetBalance") !== false) {
        $token = $data->sessionId;

        /* Procesamos */
        $Apollo = new Apollo($token, $sign);
        $response = ($Apollo->getBalance());
    }

    /**
     * Procesa una apuesta si la URI contiene "Bet".
     */
    if (strpos($URI, "Bet") !== false) {
        $token = $data->sessionId;
        $transactionId = $data->transactionId;
        $RoundId = $data->transactionId;
        $DebitAmount = $data->bet;
        $GameCode = $data->GameCode;
        $datos = $data;

        /* Procesamos */
        $Apollo = new Apollo($token, $sign);
        $response = ($Apollo->Debit($GameCode, $DebitAmount, $RoundId, $transactionId, json_encode($datos)));

        $token = $data->sessionId;

        if ($data->win != null && $data->win != '') {
            foreach ($data->win as $itemWin) {
                if ($itemWin->type == "WIN") {
                    $CreditAmount = $itemWin->amount;
                }
                if ($itemWin->type == "JACKPOT") {
                    $CreditAmount = $itemWin->amount;
                }

                $Currency = $data->Currency;
                $datos = $data;

                /* Procesamos */
                $Apollo = new Apollo($token, $sign);
                $transactionId = "credit" . $transactionId;

                $responseCredit = $Apollo->Credit($GameCode, $CreditAmount, $RoundId, $transactionId, json_encode($datos));

                $respuestaTemp = json_decode($response);
                $respuestaTemp->balance = json_decode($responseCredit)->balance;

                $response = json_encode($respuestaTemp);
            }
        }
    }

    /**
     * Realiza un rollback si la URI contiene "Rollback".
     */
    if (strpos($URI, "Rollback") !== false) {
        $sign = $data->sign;
        $token = $data->token;
        $currency = $data->currency;
        $rollbackAmount = 0;
        $date = $data->date;
        $player = $data->PlayerId;
        $roundId = $data->RGSRelatedTransactionId;
        $transactionId = $data->RGSRelatedTransactionId;
        $datos = $data;

        /* Procesamos */
        $Apollo = new Apollo($token, $sign);
        $response = ($Apollo->Rollback($rollbackAmount, $roundId, $transactionId, $player, json_encode($datos)));
    }

    /**
     * Cierra la sesión si la URI contiene "CloseSession".
     */
    if (strpos($URI, "CloseSession") !== false) {
        $token = $data->sessionId;

        /* Procesamos */
        $Apollo = new Apollo($token, $sign);
        $response = ($Apollo->getBalance());
    }

    /**
     * Notifica la sesión si la URI contiene "NotifySession".
     */
    if (strpos($URI, "NotifySession") !== false) {
        $token = $data->sessionId;

        /* Procesamos */
        $Apollo = new Apollo($token, $sign);
        $response = ($Apollo->refreshSesion());
    }

    /**
     * Genera la cabecera de la respuesta con la firma.
     */
    header('X-Signature: ' . strtoupper(hash_hmac('sha256', trim(($response)), $responseKey, false)));
    print_r($response);
}
