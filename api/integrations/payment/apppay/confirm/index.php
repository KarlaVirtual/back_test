<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Apppay.
 * Procesa los datos recibidos en formato JSON, valida la firma, y actualiza el estado de la transacción.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Apppay
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables Globales:
 *
 * @var mixed $data             Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp               Variable que almacena información sobre la forma de pago.
 * @var mixed $log              Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST         Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $transproducto_id Variable que almacena el identificador de un producto en la transacción.
 * @var mixed $externo_id       Variable que almacena un identificador externo de una transacción.
 * @var mixed $status           Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $Totalpago        Variable que almacena el monto total a pagar en una transacción.
 * @var mixed $data_            Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Apppay;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

$data = (file_get_contents('php://input'));

if ($data != '') {
    $data = str_replace("&", '","', $data);
    $data = str_replace("=", '":"', $data);
    $data = '{"' . $data . '"}';
    $data = json_decode($data);
}


if (isset($data)) {
    $signature = $data->signature;

    $transproducto_id = $data->identifier;

    $externo_id = $data->data->payment_trx;

    $status = $data->status;
    if ($status == 'success') {
        $status = 'SUCCESS';
    }

    /* Procesamos */
    $Apppay = new Apppay($transproducto_id, $status, $externo_id);
    $Apppay->confirmation(json_encode($data));

    $data_ = array();
    $data_["status"] = $status;

    echo json_encode($data_);
}
