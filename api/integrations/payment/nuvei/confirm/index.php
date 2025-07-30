<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Nuvei.
 * Procesa los datos recibidos, los valida y realiza las acciones necesarias para confirmar
 * la transacción en el sistema.
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
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $dataArray    Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp           Variable que almacena información sobre la forma de pago.
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $Username     Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $_SERVER      Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $Password     Variable que almacena una contraseña o clave de acceso.
 * @var mixed $confirm      Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $Nuvei        Variable relacionada con la plataforma de pagos Nuvei.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');


use Backend\integrations\payment\Nuvei;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

$data = json_encode($_REQUEST);

if ($data == "[]") {
    $data = file_get_contents('php://input');
    parse_str($data, $dataArray);
    $data = json_encode($dataArray, JSON_PRETTY_PRINT);
}

$data = json_decode($data);

if (isset($data)) {
    $confirm = ($data);


    $invoice = $confirm->userid;
    $result = $confirm->Status;
    $documento_id = $confirm->TransactionID;
    $valor = $confirm->totalAmount;
    $usuario_id = $confirm->userid;

    $invoice = explode("_", $invoice);
    $invoice = $invoice[1];
    $usuario_id = explode("_", $usuario_id);
    $usuario_id = $usuario_id[0];


    $control = "";

    /* Procesamos */
    $Nuvei = new Nuvei($invoice, $usuario_id, $documento_id, $valor, $control, $result);
    $Nuvei->confirmation(json_encode($data));
}
