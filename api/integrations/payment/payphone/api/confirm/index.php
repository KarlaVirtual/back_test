<?php

/**
 * Este archivo maneja la confirmación de transacciones realizadas a través de PayPhone.
 * Procesa los datos recibidos, registra logs y realiza la confirmación de las transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\PayPhone
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
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
 * @var mixed $PayPhone     Variable que almacena información relacionada con PayPhone.
 * @var mixed $response     Esta variable almacena la respuesta generada por una operación o petición.
 */

ini_set('display_errors', 'OFF');
require(__DIR__ . '../../../../../../vendor/autoload.php');

use Backend\dto\TransaccionProducto;
use Backend\dto\Usuario;
use Backend\integrations\payment\PayPhone;
use Backend\integrations\payment\Visa;

header('Content-Type: text/html; charset=UTF-8');

$data = (file_get_contents('php://input'));

$data = json_decode($data);

if (isset($data) && ($_REQUEST["id"] == "" && $_REQUEST["clientTransactionId"] == '')) {
    $confirm = ($data);


    $invoice = $confirm->clientTransactionId;
    $result = $confirm->statusCode;
    $documento_id = $confirm->id;
    $valor = $confirm->amount;
    $usuario_id = $confirm->document;
    $control = "";

    /* Procesamos */
    $PayPhone = new PayPhone($invoice, $usuario_id, $documento_id, $valor, $control, $result);
    $response = $PayPhone->confirmation(json_encode($data));

    print_r($response);
} else {
    if ($_REQUEST["id"] != "" && $_REQUEST["clientTransactionId"] != '') {
        $confirm = ($data);

        $invoice = $_REQUEST["clientTransactionId"];
        $result = '';
        $documento_id = $_REQUEST["id"];
        $valor = '';
        $usuario_id = '';
        $control = "";

        /* Procesamos */
        $PayPhone = new PayPhone($invoice, $usuario_id, $documento_id, $valor, $control, $result);
        $response = $PayPhone->confirmation(json_encode($data));

        print_r($response);
    }
}