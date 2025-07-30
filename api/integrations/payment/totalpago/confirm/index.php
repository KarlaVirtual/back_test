<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Totalpago.
 * Procesa los datos recibidos en formato JSON, registra logs y actualiza el estado de las transacciones.
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
 * @var mixed $transproducto_id Variable que almacena el identificador de un producto en la transacción.
 * @var mixed $externo_id       Variable que almacena un identificador externo de una transacción.
 * @var mixed $status           Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $Totalpago        Variable que almacena el monto total a pagar en una transacción.
 * @var mixed $data_            Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 */

require(__DIR__ . '../../../../../vendor/autoload.php');


use \Backend\integrations\payment\Totalpago;

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
    $transproducto_id = $data->code->idPago;
    $externo_id = $data->code->idPago;
    $status = $data->code->estatus;

    if ($status == 'EN PROCESO' || $status == 'PENDIENTE') {
        $status = 'PROGRESS';
    } elseif ($status == 'RECHAZADO') {
        $status = 'CANCEL';
    } elseif ($status == 'APROBADO') {
        $status = 'SUCCESS';
    }

    /* Procesamos */
    $Totalpago = new Totalpago($transproducto_id, $status, $externo_id);
    $Totalpago->confirmation(json_encode($data));

    $data_ = array();
    $data_["status"] = $status;

    echo json_encode($data_);
}
