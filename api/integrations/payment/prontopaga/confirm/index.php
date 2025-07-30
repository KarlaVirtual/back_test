<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con ProntoPaga.
 * Procesa los datos recibidos, registra logs y realiza la confirmación de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\ProntoPaga
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $fp           Variable que almacena información sobre la forma de pago.
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $confirm      Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $ProntoPaga   Variable que almacena información sobre el sistema de pagos ProntoPaga.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\ProntoPaga;

$data = json_encode($_REQUEST);

if ($data == "[]") {
    $data = file_get_contents('php://input');
}

$data = json_decode($data);

if (isset($data)) {
    $confirm = ($data);


    $invoice = $confirm->order;
    $result = $confirm->status;
    $documento_id = $confirm->reference;
    $valor = $confirm->amount;
    $usuario_id = "";
    $control = "";

    /* Procesamos */
    $ProntoPaga = new ProntoPaga($invoice, $usuario_id, $documento_id, $valor, $control, $result);
    $ProntoPaga->confirmation(json_encode($data));
}