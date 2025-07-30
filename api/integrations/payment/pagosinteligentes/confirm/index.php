<?php

/**
 * Este archivo maneja la confirmación de transacciones realizadas a través del sistema de Pagos Inteligentes.
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
 * @var mixed $log               Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_POST             Arreglo global que contiene los datos enviados mediante el método POST.
 * @var mixed $data              Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $confirm           Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $Status            Variable que almacena el estado actual.
 * @var mixed $invoice           Variable que almacena el identificador de una factura.
 * @var mixed $usuario_id        Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $documento_id      Variable que almacena el identificador de un documento.
 * @var mixed $valor             Variable que almacena un valor monetario o numérico.
 * @var mixed $control           Variable que almacena un código de control para una operación.
 * @var mixed $PagosInteligentes Variable relacionada con el sistema de Pagos Inteligentes.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\PagosInteligentes;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . file_get_contents('php://input');
$log = $log . json_encode($_POST);
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$data = json_encode($_POST);

if (true) {
    $data = json_decode($data);

    $confirm = ($data);


    $Status = $confirm->Status;

    $invoice = ($confirm->Ref1);

    $usuario_id = "";

    $documento_id = strval($confirm->TransactionId);

    $valor = 0;

    $control = "";

    /* Procesamos */

    $PagosInteligentes = new PagosInteligentes($invoice, $usuario_id, $documento_id, $valor, $control, $Status);

    print_r($PagosInteligentes->confirmation());
}