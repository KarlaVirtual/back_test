<?php

/**
 * Este archivo contiene un script para procesar y confirmar pagos realizados a través de la integración con Izipay.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    ninguna
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST      Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $data          Contiene los datos JSON recibidos en la solicitud HTTP.
 * @var mixed $URI           Almacena la URI de la solicitud actual.
 * @var mixed $log           Variable que almacena el contenido del log generado.
 * @var mixed $confirm       Objeto que contiene los datos decodificados de la solicitud.
 * @var mixed $TransactionId Identificador único de la transacción.
 * @var mixed $externoId     Identificador único externo de la transacción.
 * @var mixed $value         Monto de la transacción.
 * @var mixed $status        Estado de la transacción (Autorizado o Cancelado).
 * @var mixed $response      Respuesta generada por el método de confirmación de Izipay.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Izipay;

/* Obtenemos Variables que nos llegan */
$data = (file_get_contents('php://input'));

$data = json_decode($data);

$URI = $_SERVER['REQUEST_URI'];

if (isset($data)) {
    $confirm = ($data);

    $TransactionId = $confirm->response->order[0]->orderNumber;

    $externoId = $confirm->response->order[0]->uniqueId;

    $value = $confirm->response->order[0]->amount;

    $status = $confirm->response->order[0]->stateMessage;

    if ($status == 'Autorizado') {
        $status = "APPROVED";
    } else {
        $status = "CANCELED";
    }

    /* Procesamos */
    $Izipay = new Izipay($externoId, $TransactionId, $value, $status);
    $response = $Izipay->confirmation($data);

    print_r($response);
}