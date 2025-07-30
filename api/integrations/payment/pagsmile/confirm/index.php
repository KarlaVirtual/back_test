<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Pagsmile.
 * Procesa los datos recibidos en formato JSON, registra logs y utiliza la clase Pagsmile
 * para confirmar las transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Documentación generada automáticamente para este archivo
 *
 * @var mixed $data             Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp               Variable que almacena información sobre la forma de pago.
 * @var mixed $log              Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST         Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $Username         Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $_SERVER          Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $Password         Variable que almacena una contraseña o clave de acceso.
 * @var mixed $amount           Variable que almacena un monto o cantidad.
 * @var mixed $out_trade_no     Variable que almacena el número de la transacción externa.
 * @var mixed $method           Variable que almacena el método de pago o de ejecución de una acción.
 * @var mixed $trade_status     Variable que almacena el estado de la transacción.
 * @var mixed $trade_no         Variable que almacena el número de la transacción.
 * @var mixed $currency         Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $out_request_no   Variable que almacena el número de solicitud externa.
 * @var mixed $app_id           Variable que almacena el identificador de una aplicación.
 * @var mixed $timestamp        Variable que almacena la marca de tiempo.
 * @var mixed $number           Variable que almacena un número genérico.
 * @var mixed $type             Esta variable se utiliza para almacenar y manipular el tipo.
 * @var mixed $buyer_id         Variable que almacena el identificador del comprador.
 * @var mixed $name             Variable que almacena el nombre de un archivo, usuario o entidad.
 * @var mixed $phone            Variable que almacena el número de teléfono.
 * @var mixed $email            Variable que almacena la dirección de correo electrónico de un usuario.
 * @var mixed $card_no          Variable que almacena el número de tarjeta.
 * @var mixed $transaction_id   Variable que almacena el ID de la transacción.
 * @var mixed $payment_key      Variable que almacena la clave de pago.
 * @var mixed $transaction_key  Variable que almacena la clave de transacción.
 * @var mixed $verification_key Variable que almacena la clave de verificación.
 * @var mixed $order            Variable que almacena la orden.
 * @var mixed $status           Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $Pagsmile         Variable relacionada con la plataforma de pagos Pagsmile.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');


use \Backend\integrations\payment\Pagsmile;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');


$data = (file_get_contents('php://input'));

$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');

$log = " /" . time();

$log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim($data);
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$data = json_decode($data);

if (isset($data)) {
    //Pagsmile

    $amount = $data->amount;
    $out_trade_no = $data->out_trade_no;
    $method = $data->method;
    $trade_status = $data->trade_status;
    $trade_no = $data->trade_no;
    $currency = $data->currency;
    $out_request_no = $data->out_request_no;
    $app_id = $data->app_id;
    $timestamp = $data->timestamp;
    $number = $data->user->identify->number;
    $type = $data->user->identify->type;
    $buyer_id = $data->user->buyer_id;
    $name = $data->user->name;
    $phone = $data->user->phone;
    $email = $data->user->email;
    $card_no = $data->card_no;

    $transaction_id = $data->transaction_id;
    $payment_key = $data->payment_key;
    $transaction_key = $data->transaction_key;
    $verification_key = $data->verification_key;
    $order = $data->order;
    $status = $data->status;


    /* Procesamos */
    $Pagsmile = new Pagsmile($out_trade_no, $trade_status, $trade_no);
    $Pagsmile->confirmation(json_encode($data));
}
