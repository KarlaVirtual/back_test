<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Directa24.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Documentación generada automáticamente para este archivo
 *
 * @var mixed $_REQUEST          Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV              Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $log               Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $data              Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $cashout_id        Variable que almacena el identificador de un retiro de fondos.
 * @var mixed $DIRECTA24SERVICES Variable que almacena información relacionada con Directa24 Services.
 * @var mixed $ResponseStatus    Variable que almacena el estado de una respuesta.
 * @var mixed $invoice           Variable que almacena el identificador de una factura.
 * @var mixed $valor             Variable que almacena un valor monetario o numérico.
 * @var mixed $result            Variable que almacena el resultado de una operación o transacción.
 * @var mixed $Directa24         Variable que almacena información relacionada con Directa24.
 * @var mixed $usuario_id        Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $documento_id      Variable que almacena el identificador de un documento.
 * @var mixed $control           Variable que almacena un código de control para una operación.
 */

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

// Carga las dependencias de Composer.
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Directa24;
use Backend\integrations\payment\DIRECTA24SERVICES;

// Registra los datos de la solicitud en un archivo de log.
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
// Guarda el log en un archivo con la fecha actual.
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

// Obtiene y decodifica los datos enviados en el cuerpo de la solicitud.
$data = trim(file_get_contents('php://input'));
$data = json_decode($data);

if (isset($data->deposit_id)) {
    // Procesa la confirmación de un depósito.
    $cashout_id = $data->deposit_id;

    $DIRECTA24SERVICES = new DIRECTA24SERVICES();
    $ResponseStatus = $DIRECTA24SERVICES->paymentStatusGetTupay($cashout_id);
    $ResponseStatus = json_decode($ResponseStatus);

    $invoice = $ResponseStatus->invoice_id;
    $valor = $ResponseStatus->local_amount;
    $result = $ResponseStatus->status;

    if ($ResponseStatus != '') {
        // Crea una instancia de Directa24 y confirma el pago.
        $Directa24 = new Directa24($invoice, '', $cashout_id, $valor, '', $result);
        $Directa24->confirmation();
    }
} else {
    // Procesa la confirmación de un pago utilizando parámetros de la solicitud.
    $result = $_REQUEST['result'];
    $invoice = intval($_REQUEST['x_invoice']);
    $usuario_id = $_REQUEST['x_iduser'];
    $documento_id = $_REQUEST['x_document'];
    $valor = $_REQUEST['x_amount'];
    $control = $_REQUEST['x_control'];

    if ($result != '') {
        // Crea una instancia de Directa24 y confirma el pago.
        $Directa24 = new Directa24($invoice, $usuario_id, $documento_id, $valor, $control, $result);
        $Directa24->confirmation();
    }
}
