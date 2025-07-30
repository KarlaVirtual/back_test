<?php

/**
 * Este archivo maneja la confirmación de pagos a través de VisaQR.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\VisaQR
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
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $VisaQR       Variable que almacena información relacionada con un código QR de Visa.
 * @var mixed $e            Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 */

require(__DIR__ . '../../../../../../vendor/autoload.php');

use Backend\integrations\payment\VisaQR;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}
$_ENV["enabledConnectionGlobal"] = 1;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$response = json_decode(file_get_contents('php://input'));

/* Obtenemos Variables que nos llegan */
$invoice = intval(str_replace("recibo:", "", $response->additionalData));

$usuario_id = 0;

$documento_id = $response->transactionIdCore;

$valor = $response->transactionAmount;

$control = $response->transactionAmount;

/* Procesamos */
try {
    if ($invoice != '' && is_numeric($invoice)) {
        $VisaQR = new VisaQR($invoice, $usuario_id, $documento_id, $invoice, $response->purchaseNumber);

        $VisaQR->confirmation(json_encode($response), $valor);
    }
} catch (Exception $e) {
    syslog(10, "ERRORAPIPAYMENT :" . json_encode($e));
}
