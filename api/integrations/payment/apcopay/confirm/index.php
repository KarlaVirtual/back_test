<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con ApcoPay.
 * Procesa los datos enviados mediante POST, registra logs y realiza la confirmación de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\ApcoPay
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables Globales:
 *
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_POST        Arreglo global que contiene los datos enviados mediante el método POST.
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $confirm      Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $ApcoPay      Variable que almacena información relacionada con ApcoPay.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\ApcoPay;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($_POST["params"]);
$log = $log . json_encode($_POST);
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


$data = $_POST["params"];

if (true) {
    $data = simplexml_load_string($data);

    $confirm = ($data);

    print_r($data);


    $result = $confirm->Result;

    $invoice = intval($confirm->ORef);

    $usuario_id = "";

    $documento_id = strval($confirm->pspid);

    $valor = 0;

    $control = "";

    /* Procesamos */

    $ApcoPay = new ApcoPay($invoice, $usuario_id, $documento_id, $valor, $control, $result);

    print_r($ApcoPay->confirmation());
}