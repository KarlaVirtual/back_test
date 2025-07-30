<?php
/**
 * Archivo de idioma en alemán para mensajes de error y validación relacionados con pagos.
 *
 * Este archivo contiene una lista de mensajes de error y validación utilizados
 * en el contexto de la integración de pagos con spotupay.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Paysafecard
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */


require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\SpotUpay;

/* Obtenemos Variables que nos llegan */
$data = (file_get_contents('php://input'));

$data = json_decode($data);

$URI = $_SERVER['REQUEST_URI'];

if (isset($data)) {
    $confirm = ($data);

    $reference = $confirm->trans_id;
    $value = $confirm->amount_paid;
    $TransactionId = $confirm->customer_code;
    $status = $confirm->status;
    $estado = '';

    if ($status == 'true'){
        $estado = 'OK';
    }else{
        $estado = 'DECLINE';
    }

    /* Procesamos */
    $SpotUpay = new SpotUpay($reference, $TransactionId, $value, $estado);

    $SpotUpay->confirmation($data);
}