<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con la plataforma Unlimint.
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
 * @var mixed $data     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp       Variable que almacena información sobre la forma de pago.
 * @var mixed $log      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $Username Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $_SERVER  Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $Password Variable que almacena una contraseña o clave de acceso.
 * @var mixed $trans    Variable que almacena información sobre una transacción.
 * @var mixed $uid      Variable que almacena el identificador único de un usuario.
 * @var mixed $status   Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $amonut   Variable que almacena un monto en una transacción (posible error tipográfico de "amount").
 * @var mixed $Unlimint Variable que hace referencia a la plataforma de pago Unlimint.
 * @var mixed $data_    Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use \Backend\integrations\payment\Unlimint;

header('Content-Type: application/json');

/* Obtenemos Variables que nos llegan */
$data = (file_get_contents('php://input'));

$data = json_decode($data);

if (isset($data)) {
    //Unlimint
    $trans = $data->merchant_order->id;
    $uid = $data->merchant_order->id;
    $status = $data->payment_data->status;
    $amonut = $data->payment_data->amount;

    if ($status == 'NEW' || $status == 'IN_PROGRESS' || $status == 'AUTHORIZED') {
        $status = 'PROGRESS';
    } elseif ($status == 'DECLINED' || $status == 'CANCELLED' || $status == 'VOIDED') {
        $status = 'CANCEL';
    } elseif ($status == 'COMPLETED' || $status == 'TERMINATED') {
        $status = 'SUCCESS';
    }

    /* Procesamos */
    $Unlimint = new Unlimint($trans, $status, $uid, $amonut);
    $response = $Unlimint->confirmation(json_encode($data));
    $response = json_decode($response);

    $data_ = array();
    if ($response->result == 'success') {
        $data_["code"] = "200";
        $data_["message"] = "OK";
    } elseif ($response->result == 'error') {
        $data_["code"] = "200";
        $data_["message"] = "OK";
    } elseif ($response->result == '') {
        $data_["code"] = "200";
        $data_["message"] = "OK";
    } else {
        http_response_code(400);
        $data_["code"] = "400";
        $data_["message"] = "ERROR";
    }

    echo json_encode($data_);
}
