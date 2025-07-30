<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con CoinPay.
 * Procesa los datos recibidos, registra logs y realiza la confirmación de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\CoinPay
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables Globales:
 *
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp           Variable que almacena información sobre la forma de pago.
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $confirm      Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $CoinPay      Variable que almacena información relacionada con CoinPay.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\CoinPay;


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
    $confirm = ($data);

    if ($confirm->TransactionStatus->Name != "Requested") {
        $invoice = $confirm->IdExternalReference;
        $result = $confirm->TransactionStatus->Name;
        $documento_id = $confirm->IdTransaction;
        $valor = $confirm->Amount;
        $usuario_id = "";
        $control = "";

        /* Procesamos */
        $CoinPay = new CoinPay($invoice, $usuario_id, $documento_id, $valor, $control, $result);
        $CoinPay->confirmation(json_encode($data));
    }
}
