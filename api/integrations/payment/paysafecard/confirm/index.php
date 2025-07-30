<?php

/**
 * Este archivo maneja la confirmación de pagos realizados a través de PaySafecard.
 * Procesa los datos de la solicitud HTTP, ejecuta la operación de pago y confirma
 * el resultado de la transacción.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\PaySafecard
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */


/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $body         Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $execute      Variable que indica la ejecución de un proceso.
 * @var mixed $parameter    Variable que define un parámetro.
 * @var mixed $_GET         Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $responsee    Variable que almacena la respuesta de una operación.
 * @var mixed $PaySafecard  Variable que almacena información relacionada con PaySafecard.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\PaySafecard;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$body = json_encode($_REQUEST);


if ($body != "") {
    $data = json_decode($body);

    $result = $data->pn;

    $invoice = $data->mtid;

    $usuario_id = $data->mtid;

    $documento_id = explode(";", $data->serialNumbers)[0];


    $valor = explode(";", $data->serialNumbers)[2];

    $control = $data->mtid;

    include_once('paysafecard/src/loader.php');
    new Loader('/payment');

    $execute = new paymentExecute();
    $parameter = array(
        'mtid' => $_GET['mtid'],
        'amount' => $valor,
        'currency' => 'PEN',
        'close' => '1'
    );

    $responsee = $execute->execute($parameter);

    if ($responsee) {
        /* Procesamos */

        $PaySafecard = new PaySafecard($invoice, $usuario_id, $documento_id, $valor, $control, $result);

        $PaySafecard->confirmation();
    } else {
        $PaySafecard = new PaySafecard($invoice, $usuario_id, $documento_id, $valor, $control, 3);

        $PaySafecard->confirmation();
    }
}