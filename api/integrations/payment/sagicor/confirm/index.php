<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Sagicor.
 * Procesa los datos recibidos en formato JSON, registra logs y realiza la confirmación
 * de transacciones utilizando la clase Sagicor.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Sagicor
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $confirm      Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $Sagicor      Variable relacionada con el sistema o plataforma Sagicor.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');


use Backend\integrations\payment\Sagicor;


/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

$data = (file_get_contents('php://input'));

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$data = json_decode($data);

if (isset($data)) {
    exit();
    $confirm = ($data);


    $invoice = $confirm->order->reference;
    $result = $confirm->response->acquirerMessage;
    $documento_id = $confirm->transaction->acquirer->transactionId;
    $valor = $confirm->order->amount;
    $usuario_id = "";
    $control = "";

    /* Procesamos */
    $Sagicor = new Sagicor($invoice, $usuario_id, $documento_id, $valor, $control, $result);
    $Sagicor->confirmation(json_encode($data));
}
