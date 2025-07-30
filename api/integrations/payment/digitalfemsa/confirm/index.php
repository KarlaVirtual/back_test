<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con DigitalFemsa.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\DigitalFemsa
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Documentación de variables globales:
 *
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp           Variable que almacena información sobre la forma de pago.
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $confirm      Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $ping         Variable que almacena el resultado de una prueba de conexión.
 * @var mixed $array        Variable que almacena una lista o conjunto de datos.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $Digitalfemsa Variable que almacena información relacionada con DigitalFemsa.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Digitalfemsa;

header('Content-type: application/json; charset=utf-8');

$data = (file_get_contents('php://input'));

$data = json_decode($data);

if (isset($data)) {
    $confirm = ($data);

    $ping = $confirm->data->action;

    if ($ping == "ping") {
        $array = array(
            "status" => "successful"
        );
        http_response_code(200);
        print_r(json_encode($array));
    } else {
        $invoice = $confirm->data->object->metadata->transactionId;
        $result = $confirm->data->object->payment_status;
        $documento_id = $confirm->data->object->id;
        $valor = $confirm->data->object->amount / 100;
        $usuario_id = "";
        $control = "";

        /* Procesamos */
        $Digitalfemsa = new Digitalfemsa($invoice, $usuario_id, $documento_id, $valor, $control, $result);
        $Digitalfemsa->confirmation(json_encode($data));
    }
}