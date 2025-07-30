<?php

/**
 * Este archivo maneja la confirmación de transacciones de pago a través de la integración con Inswitch.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Inswitch
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST        Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV            Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $data            Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp              Variable que almacena información sobre la forma de pago.
 * @var mixed $post_vars       Variable que almacena los datos enviados a través de POST.
 * @var mixed $method          Variable que almacena el método de pago o de ejecución de una acción.
 * @var mixed $_SERVER         Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $confirm         Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $amount          Variable que almacena un monto o cantidad.
 * @var mixed $Reference       Variable que almacena la referencia de una transacción o proceso.
 * @var mixed $descriptionText Variable que almacena una descripción en formato de texto.
 * @var mixed $value           Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $User            Variable que almacena información sobre un usuario.
 * @var mixed $metodo          Variable que almacena un método de procesamiento o pago.
 * @var mixed $status          Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $PayMethods      Variable que almacena los métodos de pago disponibles.
 * @var mixed $Kushki          Variable que almacena información sobre la pasarela de pago Kushki.
 * @var mixed $response        Esta variable almacena la respuesta generada por una operación o petición.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Inswitch;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}
$_ENV["enabledConnectionGlobal"] = 1;

$data = file_get_contents("php://input");
$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode($data);

if (isset($data) && $data != '') {
    $confirm = ($data);

    $amount = $confirm->amount;
    $Reference = $confirm->transactionReference;
    $descriptionText = $confirm->descriptionText;
    $value = $confirm->requestingOrganisationTransactionReference;
    $value = explode("_", $value);
    $User = $value[1];
    $metodo = false;

    if ($User != "" || $User != null) {
        $metodo = true;
        $value = $value[0];
    } else {
        $value = $confirm->transactionReference;
    }

    if ($confirm->requestingOrganisationTransactionReference != '') {
        $Reference = $confirm->requestingOrganisationTransactionReference;
        $value = $confirm->requestingOrganisationTransactionReference;
        if (strpos($Reference, '-') !== false) {
        } else {
            $metodo = true;
        }
        $Reference = $confirm->transactionReference;
    } else {
        if ($descriptionText != "" || $descriptionText != null) {
            $metodo = true;
            $value = $descriptionText;
        }
    }

    $status = $confirm->transactionStatus;

    $PayMethods = $confirm->requiredAction->relatedPaymentMethodData->paymentMethodType;

    if (
        $status == 'error' || $status == 'cancelled' || $status == 'pendingExpired' ||
        $status == 'ongoingExpired' || $status == 'pendingRejected' ||
        $status == 'pendingCancelled' || $status == 'reverting' || $status == 'conciliateReversal'
    ) {
        $status = 'declined';
    } elseif (
        $status == 'pending' || $status == 'waiting' || $status == 'processingPayment' ||
        $status == 'initial' || $status == 'secondStepInitial' || $status == 'conciliate'
    ) {
        $status = 'waiting';
    } else {
        $status = 'finished';
    }

    /* Procesamos */
    $Inswitch = new Inswitch($Reference, $value, $amount, $status, $metodo);
    $response = $Inswitch->confirmation($data, $PayMethods);
    print_r($response);
} else {
    print_r('ERROR');
}
