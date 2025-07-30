<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la pasarela de pagos Rave.
 * Procesa la respuesta recibida, registra los datos en un log y realiza la confirmación
 * de la transacción si los datos son válidos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Rave
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $response     Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $Rave         Variable que almacena información sobre la pasarela de pagos Rave.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Rave;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$response = json_decode(file_get_contents('php://input'));


/* Obtenemos Variables que nos llegan */
if ($response->status == 'successful') {
    $result = '9';
} else {
    $result = '8';
}

$invoice = intval($response->txRef);

$usuario_id = 0;

$documento_id = $response->id;

$valor = $response->amount;

$control = $response->amount;

/* Procesamos */


if ($result != '' && $invoice != '' && is_numeric($invoice)) {
    $Rave = new Rave($invoice, $usuario_id, $documento_id, $valor, $control, $result);

    $Rave->confirmation($response);
}



