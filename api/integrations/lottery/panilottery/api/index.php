<?php

/**
 * Archivo principal para manejar las integraciones de la API de PaniLottery.
 *
 * Este archivo procesa solicitudes HTTP relacionadas con autenticación, balance,
 * débitos, créditos y reversión de transacciones para la integración con PaniLottery.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la integración.
 *
 * @var mixed $_ENV                Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $log                 Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_SERVER             Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body                Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $URI                 Esta variable contiene el URI de la petición actual.
 * @var mixed $data                Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $sign                Variable que almacena una firma digital o de seguridad.
 * @var mixed $token               Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $Panilottery         Variable que almacena información relacionada con PaniLottery.
 * @var mixed $response            Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $amount              Variable que almacena un monto o cantidad.
 * @var mixed $GameCode            Variable que almacena el código de un juego.
 * @var mixed $transactionId       Variable que almacena el identificador único de una transacción.
 * @var mixed $RoundId             Variable que almacena el identificador de una ronda de juego.
 * @var mixed $datos               Variable que almacena datos genéricos.
 * @var mixed $respuestaCredit     Variable que almacena la respuesta de una operación de crédito.
 * @var mixed $transactionRollback Variable que indica si una transacción debe ser revertida.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Panilottery;

header('Content-Type: application/json');

// Configuración inicial del entorno
$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

// Registro inicial de la solicitud
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$body = file_get_contents('php://input');
$URI = $_SERVER['REQUEST_URI'];

if ($body != "") {
    $data = json_decode($body);
    $sign = $data->sign;

    /**
     * Manejo de autenticación.
     * Procesa solicitudes con el endpoint "authenticate".
     */
    if (strpos($URI, "authenticate") !== false) {
        $token = $data->token;

        $Panilottery = new Panilottery($token, $sign);
        $response = ($Panilottery->Auth());

        $log = "";
        $log = $log . "/" . time();
        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }

    /**
     * Manejo de balance.
     * Procesa solicitudes con el endpoint "balance".
     */
    if (strpos($URI, "balance") !== false) {
        $token = $data->token;

        /* Procesamos */
        $Panilottery = new Panilottery($token, $sign);
        $response = ($Panilottery->getBalance());

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        //Save string to log, use FILE_APPEND to append.

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
        print_r($response);
    }

    /**
     * Manejo de débitos.
     * Procesa solicitudes con el endpoint "debit".
     */
    if (strpos($URI, "debit") !== false) {
        $token = $data->token;
        $amount = ($data->amount);
        $GameCode = $data->gamecode;
        $transactionId = $data->transactionid;
        $RoundId = $data->roundid;

        $datos = $data;

        $Panilottery = new Panilottery($token, $sign);
        $respuestaCredit = $Panilottery->Debit($GameCode, $amount, $RoundId, $transactionId, json_encode($datos));

        print_r($respuestaCredit);
    }

    /**
     * Manejo de créditos.
     * Procesa solicitudes con el endpoint "credit".
     */
    if (strpos($URI, "credit") !== false) {
        $token = $data->token;
        $amount = ($data->amount);
        $GameCode = $data->gamecode;
        $transactionId = $data->transactionid;
        $RoundId = $data->roundid;

        $datos = $data;

        $Panilottery = new Panilottery($token, $sign);
        $respuestaCredit = $Panilottery->Credit($GameCode, $amount, $RoundId, $transactionId, json_encode($datos));

        print_r($respuestaCredit);
    }

    /**
     * Manejo de reversión de transacciones.
     * Procesa solicitudes con el endpoint "rollback".
     */
    if (strpos($URI, "rollback") !== false) {
        $token = $data->token;
        $amount = ($data->amount);
        $GameCode = $data->gamecode;
        $transactionRollback = $data->transactionRollback;
        $transactionId = $data->transactionid;
        $RoundId = $data->roundid;

        $datos = $data;

        $Panilottery = new Panilottery($token, $sign);
        $respuestaCredit = $Panilottery->Rollback($amount, $transactionRollback, $transactionId, json_encode($datos));

        print_r($respuestaCredit);
    }
}



