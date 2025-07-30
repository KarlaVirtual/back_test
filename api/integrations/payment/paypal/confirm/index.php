<?php

/**
 * Este archivo maneja la confirmación de pagos realizados a través de PayPal.
 * Procesa los datos recibidos, valida el estado del pago y realiza las acciones necesarias
 * para registrar la transacción en el sistema.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\PayPal
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data                     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp                       Variable que almacena información sobre la forma de pago.
 * @var mixed $confirm                  Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $address_status           Variable que almacena el estado de la dirección.
 * @var mixed $payer_status             Variable que almacena el estado del pagador.
 * @var mixed $payer_email              Variable que almacena el correo electrónico del pagador.
 * @var mixed $payer_id                 Variable que almacena el ID del pagador.
 * @var mixed $mc_currency              Variable que define la moneda utilizada.
 * @var mixed $event_type               Variable que define el tipo de evento.
 * @var mixed $resource                 Variable que almacena un recurso específico.
 * @var mixed $purchase_units           Variable que almacena unidades de compra.
 * @var mixed $payment_status           Variable que define el estado del pago.
 * @var mixed $orderId                  Variable que almacena el ID de la orden.
 * @var mixed $reference_id             Variable que almacena un ID de referencia.
 * @var mixed $valor                    Variable que almacena un valor monetario o numérico.
 * @var mixed $TransaccionProducto      Variable que almacena información sobre una transacción de producto.
 * @var mixed $request                  Variable que representa la solicitud HTTP, conteniendo datos como parámetros y encabezados.
 * @var mixed $Subproveedor             Variable que almacena información del subproveedor.
 * @var mixed $Detalle                  Variable que almacena detalles adicionales.
 * @var mixed $clientId                 Esta variable se utiliza para almacenar y manipular el identificador del cliente.
 * @var mixed $clientSecretId           Variable que almacena el ID secreto del cliente.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $environment              Variable que define el entorno de ejecución.
 * @var mixed $client                   Variable que almacena información del cliente.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $txn_id                   Variable que almacena el ID de la transacción.
 * @var mixed $result                   Variable que almacena el resultado de una operación o transacción.
 * @var mixed $invoice                  Variable que almacena el identificador de una factura.
 * @var mixed $usuario_id               Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $documento_id             Variable que almacena el identificador de un documento.
 * @var mixed $control                  Variable que almacena un código de control para una operación.
 * @var mixed $Paypal                   Variable que almacena información relacionada con PayPal.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransaccionProducto;
use Backend\integrations\payment\Paypal;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Sample\PayPalClient;


/* Obtenemos Variables que nos llegan */

$data = (file_get_contents('php://input'));
$data = json_decode($data);


$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');

fwrite($fp, json_encode($data));
fwrite($fp, " DATA ");
fwrite($fp, file_get_contents('php://input'));

fclose($fp);
if (isset($data)) {
    $confirm = ($data);


    $address_status = $confirm->address_status;
    $payer_status = $confirm->payer_status;
    $payer_email = $confirm->payer_email;
    $payer_id = $confirm->payer_id;
    $mc_currency = $confirm->mc_currency;
    $event_type = $confirm->event_type;


    if ($event_type != 'CHECKOUT.ORDER.APPROVED') {
        exit();
    }

    $resource = $confirm->resource;
    $purchase_units = $resource->purchase_units;
    $purchase_units = $purchase_units[0];
    $payment_status = $resource->status;
    $orderId = $purchase_units->reference_id;

    $reference_id = $purchase_units->reference_id;
    $valor = $purchase_units->amount->value;

    $TransaccionProducto = new TransaccionProducto($reference_id);

    $orderId = $TransaccionProducto->externoId;
    $request = new OrdersCaptureRequest($orderId);


    $Subproveedor = new Subproveedor('', 'PAYPAL');

    $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $TransaccionProducto->mandante, '');
    $Detalle = $Subproveedor->detalle;
    $Detalle = json_decode($Detalle);

    $clientId = $Detalle->clientId;
    $clientSecretId = $Detalle->clientSecretId;


    $ConfigurationEnvironment = new ConfigurationEnvironment();

    if ($ConfigurationEnvironment->isDevelopment()) {
        $environment = new SandboxEnvironment($clientId, $clientSecretId);
    } else {
        $environment = new ProductionEnvironment($clientId, $clientSecretId);
    }
    $client = new PayPalHttpClient($environment);

    $response = $client->execute($request);


    $fp = fopen('log_' . date("Y-m-d") . '.log', 'a');

    fwrite($fp, " RESPONSEPAYPAL ");
    fwrite($fp, json_encode($response));

    fclose($fp);


    if ($response->statusCode != 201) {
        exit();
    }

    if ($response->result == null) {
        exit();
    }
    if ($response->result->status != 'COMPLETED') {
        exit();
    }


    $txn_id = $reference_id;


    $result = $payment_status;

    $invoice = $txn_id;

    $usuario_id = "";

    $documento_id = $confirm->id;


    $control = "";

    /* Procesamos */

    $Paypal = new Paypal($invoice, $usuario_id, $documento_id, $valor, $control, $result);

    print_r($Paypal->confirmation());
}