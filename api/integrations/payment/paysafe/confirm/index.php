<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Paysafe.
 * Procesa las solicitudes entrantes, registra logs y realiza la confirmación de pagos
 * según el tipo de evento recibido.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Paysafe
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST       Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV           Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $data           Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp             Variable que almacena información sobre la forma de pago.
 * @var mixed $log            Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $merchantRefNum Variable que almacena el número de referencia del comerciante.
 * @var mixed $status         Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $externoId      Variable que almacena un identificador externo en Internpay.
 * @var mixed $Paysafe        Variable que almacena información relacionada con Paysafe.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use \Backend\integrations\payment\Paysafe;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

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


if ($data->eventType == "cip.paid") {
    $merchantRefNum = $data->data->transactionCode;
    $status = "PAYABLE"; //ok
    $externoId = $data->data->cip;

    /* Procesamos */
    $Paysafe = new Paysafe($merchantRefNum, $status, $externoId);
    $Paysafe->confirmation(json_encode($data));
} else {
    $merchantRefNum = $data->payload->merchantRefNum;
    $status = $data->payload->status;
    $externoId = $data->payload->id;

    /* Procesamos */
    $Paysafe = new Paysafe($merchantRefNum, $status, $externoId);
    $Paysafe->confirmation(json_encode($data));
}

