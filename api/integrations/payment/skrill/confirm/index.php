<?php

/**
 * Este archivo maneja la confirmación de pagos realizados a través de la plataforma Skrill.
 * Procesa los datos recibidos, registra logs y realiza la confirmación de la transacción.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Skrill
 * @author     Desconocido
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
 * @var mixed $Skrill       Variable que hace referencia a la plataforma de pago Skrill.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Skrill;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$response = json_decode(file_get_contents('php://input'));
$response = json_decode(file_get_contents('php://input'));
$response = json_decode(json_encode($_REQUEST));


/* Obtenemos Variables que nos llegan */
if ($response->status == '2') {
    $result = '9';
} elseif ($response->status == '0') {
    $result = '7';
} else {
    $result = '8';
}

$invoice = intval($response->transaction_id);

$usuario_id = 0;

$documento_id = $response->mb_transaction_id;

$valor = $response->amount;

$control = $response->amount;

/* Procesamos */


if ($result != '' && $invoice != '' && is_numeric($invoice)) {
    $Skrill = new Skrill($invoice, $usuario_id, $documento_id, $valor, $control, $result);

    $Skrill->confirmation($response);
}



