<?php

/**
 * Este archivo maneja la confirmación de pagos realizados a través de AstroPay Card.
 * Procesa los datos recibidos, registra logs y realiza la confirmación de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables Globales:
 *
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp           Variable que almacena información sobre la forma de pago.
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $confirm      Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $datedeposit  Variable que almacena la fecha de un depósito.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $Astropaycard Variable que almacena información relacionada con AstroPay Card.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Astropaycard;


/* Obtenemos Variables que nos llegan */

$data = (file_get_contents('php://input'));

$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');
$log = "\r\n" . "-------------" . date("Y-m-d") . "------------" . "\r\n";
$log = $log . "\r\n" . $_REQUEST . "\r\n";
$log = $log . "\r\n" . $data . "\r\n";

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


$data = json_decode($data);

if (isset($data)) {
    $confirm = ($data);


    $invoice = $confirm->merchant_deposit_id;
    $result = $confirm->status;
    $documento_id = $confirm->deposit_external_id;
    $valor = "";
    $datedeposit = date('Y-m-d H:i:s', $confirm->end_status_dated);
    $usuario_id = $confirm->deposit_user_id;;
    $control = "";


    /* Procesamos */


    $Astropaycard = new Astropaycard($invoice, $usuario_id, $documento_id, $valor, $control, $result);

    $Astropaycard->confirmation();
}