<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con AstroPay.
 * Procesa los datos recibidos, registra logs y ejecuta la confirmación de la transacción.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\AstroPay
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables Globales:
 *
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $Astropay     Variable que almacena información relacionada con AstroPay.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Astropay;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


/* Obtenemos Variables que nos llegan */

$result = $_REQUEST['result'];

$invoice = intval($_REQUEST['x_invoice']);

$usuario_id = $_REQUEST['x_iduser'];

$documento_id = $_REQUEST['x_document'];

$valor = $_REQUEST['x_amount'];

$control = $_REQUEST['x_control'];

/* Procesamos */


if ($result != '') {
    $Astropay = new Astropay($invoice, $usuario_id, $documento_id, $valor, $control, $result);

    $Astropay->confirmation();
}



