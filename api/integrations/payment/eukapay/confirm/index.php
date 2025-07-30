<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Eukapay.
 *
 * Procesa los datos recibidos en la solicitud, registra información en un archivo de log
 * y utiliza la clase Eukapay para confirmar transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Davison Valencia <davison.valencia@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-28
 */

/**
 * Documentación de Variables Globales:
 *
 * @var mixed $data          Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $TransactionId Variable que almacena el identificador de una transacción.
 * @var mixed $externoId     Variable que almacena un identificador genérico del proveedor.
 * @var mixed $status        Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $amonut        Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $Eukapay       Variable que almacena información relacionada con Eukapay.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use \Backend\integrations\payment\Eukapay;

header('Content-Type: application/json');

/* Obtenemos Variables que nos llegan */
$data = (file_get_contents('php://input'));
$data = json_decode($data);

if (isset($data)) {

    //Eukapay
    $TransactionId = $data->invoiceNumber;
    $externoId = $data->webhookId;
    $status = $data->status;
    $amonut = $data->paidAmount;

    $parts = explode('-', $TransactionId);
    $invoiceNumber = end($parts);

    if ($status == 'Paid' || $status == 'Overpaid') {
        $status = 'SUCCESS';
    } else if ($status == 'Underpaid') {
        $status = 'CANCEL';
    } else {
        $status = 'PROGRESS';
    }

    /* Procesamos */
    $Eukapay = new Eukapay($invoiceNumber, $status, $externoId, $amonut);
    $response = $Eukapay->confirmation(json_encode($data));

    $response = json_decode($response);

    $data_ = array();
    if ($response->result == 'success') {
        $data_["code"] = "200";
        $data_["message"] = "paid";
    } elseif ($response->result == 'error') {
        $data_["code"] = "200";
        $data_["message"] = "paid";
    } elseif ($response->result == '') {
        $data_["code"] = "200";
        $data_["message"] = "paid";
    } else {
        http_response_code(400);
        $data_["code"] = "400";
        $data_["message"] = "ERROR";
    }

    echo json_encode($data_);
}
