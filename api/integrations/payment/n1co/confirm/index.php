<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con el sistema N1CO.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\N1CO
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data                     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp                       Variable que almacena información sobre la forma de pago.
 * @var mixed $log                      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $secrekey                 Esta variable se utiliza para almacenar y manipular la clave secreta.
 * @var mixed $sing                     Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $confirm                  Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $status                   Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $transactionID            Variable que almacena el ID de la transacción.
 * @var mixed $orderId                  Variable que almacena el ID de la orden.
 * @var mixed $Amount                   Variable que almacena un monto o cantidad (posible duplicado de amount).
 * @var mixed $N1co                     Variable específica para el sistema N1co.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\payment\N1CO;
use Backend\integrations\payment\N1COSERVICES;

header('Content-Type: application/json');

$data = file_get_contents('php://input');

$data = json_decode($data);

if (isset($data)) {
    $confirm = ($data);

    if ($confirm->type != "Created") {
        $status = $confirm->metadata->Status;
        if ($confirm->metadata->OrderReference == '') {
            $transactionID = $confirm->metadata->transactionid;
        } else {
            $transactionID = $confirm->metadata->OrderReference;
        }
        $orderId = $confirm->orderId;
        $Amount = $confirm->metadata->PaidAmount;

        $N1co = new N1CO($transactionID, $orderId, $Amount, $status);
        $N1co->confirmation(json_encode($data));
    }
}