<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con PayValida.
 * Procesa los datos recibidos en formato JSON, los decodifica y utiliza la clase PayValida
 * para gestionar la confirmación de la transacción.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\PayValida
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $confirm      Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $estado       Variable que almacena el estado de un proceso o entidad.
 * @var mixed $pv_po_id     Variable que almacena el ID del punto de venta.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $iso_currency Variable que define la moneda en formato ISO.
 * @var mixed $pv_payment   Variable que almacena información del pago en el punto de venta.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $PayValida    Variable que almacena información relacionada con PayValida.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\PayValida;


/* Obtenemos Variables que nos llegan */

$data = (file_get_contents('php://input'));


$data = json_decode($data);

if (isset($data)) {
    $confirm = ($data);

    $estado = $confirm->status;
    $pv_po_id = $confirm->pv_po_id;
    $valor = $confirm->amount;
    $iso_currency = $confirm->iso_currency;
    $pv_payment = $confirm->pv_payment;


    $result = $confirm->status;

    $invoice = $confirm->po_id;

    $usuario_id = "";

    $documento_id = $confirm->pv_po_id;

    $valor = $confirm->amount;

    $control = "";

    /* Procesamos */

    $PayValida = new PayValida($invoice, $usuario_id, $documento_id, $valor, $control, $result);

    print_r($PayValida->confirmation());
}