<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Payku.
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
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data             Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp               Variable que almacena información sobre la forma de pago.
 * @var mixed $log              Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST         Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $Username         Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $_SERVER          Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $Password         Variable que almacena una contraseña o clave de acceso.
 * @var mixed $transaction_id   Variable que almacena el ID de la transacción.
 * @var mixed $payment_key      Variable que almacena la clave de pago.
 * @var mixed $transaction_key  Variable que almacena la clave de transacción.
 * @var mixed $verification_key Variable que almacena la clave de verificación.
 * @var mixed $order            Variable que almacena la orden.
 * @var mixed $status           Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $Payku            Variable que almacena información relacionada con Payku.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');


use \Backend\integrations\payment\Payku;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');


$data = (file_get_contents('php://input'));

$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');

$log = " /" . time();

$log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim($data);
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$data = json_decode($data);

if (isset($data)) {
    $transaction_id = $data->transaction_id;
    $payment_key = $data->payment_key;
    $transaction_key = $data->transaction_key;
    $verification_key = $data->verification_key;
    $order = $data->order;
    $status = $data->status;

    /* Procesamos */
    $Payku = new Payku($order, $status, $transaction_key);
    $Payku->confirmation(json_encode($data));
}
