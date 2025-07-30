<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Ezzepay.
 *
 * Procesa los datos recibidos en la solicitud, registra información en un archivo de log
 * y utiliza la clase Ezzepay para confirmar transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Documentación de Variables Globales:
 *
 * @var mixed $data          Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $URI           Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER       Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $log           Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST      Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $confirm       Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $id            Variable que almacena un identificador genérico.
 * @var mixed $value         Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $TransactionId Variable que almacena el identificador de una transacción.
 * @var mixed $paid_at       Variable que almacena la fecha y hora de un pago.
 * @var mixed $status        Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $Ezzepay       Variable que almacena información relacionada con Ezzepay.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Ezzepay;

/* Obtenemos Variables que nos llegan */
$data = (file_get_contents('php://input'));

$data = json_decode($data);

$URI = $_SERVER['REQUEST_URI'];

if (isset($data)) {
    $confirm = ($data);

    $id = $confirm->requestBody->transactionId;
    $value = $confirm->requestBody->amount;
    $TransactionId = $confirm->requestBody->external_id;
    $paid_at = $confirm->requestBody->paid_at;
    $status = $confirm->requestBody->transactionType;

    if ($confirm->requestBody->transactionType == 'RECEIVEPIX') {
        $status = "APPROVED";
    } else {
        $status = "CANCELED";
    }

    /* Procesamos */
    $Ezzepay = new Ezzepay($id, $TransactionId, $value, $status);

    $Ezzepay->confirmation($data);
}