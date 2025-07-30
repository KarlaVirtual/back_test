<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con SmartFastPay.
 *
 * Procesa los datos recibidos en la solicitud, registra logs, y realiza la confirmación
 * de transacciones con la plataforma SmartFastPay. Además, genera una respuesta en formato JSON
 * indicando el estado del proceso.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST      Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $data          Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp            Variable que almacena información sobre la forma de pago.
 * @var mixed $log           Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $TransactionId Variable que almacena el identificador de una transacción.
 * @var mixed $externoId     Variable que almacena un identificador externo en Internpay.
 * @var mixed $status        Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $amonut        Variable que almacena un monto en una transacción (posible error tipográfico de "amount").
 * @var mixed $comment       Variable que almacena un comentario asociado a un proceso.
 * @var mixed $SmartFastPay  Variable que hace referencia a la plataforma de pago SmartFastPay.
 * @var mixed $response      Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $result        Variable que almacena el resultado de una operación o transacción.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use \Backend\integrations\payment\Kashio;
use Backend\dto\CuentaCobro;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\integrations\payment\SmartFastPay;
use Backend\integrations\payout\PAYBROKERSSERVICES;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$data = (file_get_contents('php://input'));

$data = json_decode($data);

if (isset($data)) {
    //SmartFastPay

    $TransactionId = $data->data[0]->transaction_id;
    $externoId = $data->data[0]->id;
    $status = $data->data[0]->status;
    $amonut = $data->data[0]->amount;
    $comment = $data->data[0]->type;

    if ($status == 'canceled') {
        $status = 'CANCEL';
    } elseif ($status == 'paid') {
        $status = 'SUCCESS';
    }

    /* Procesamos */
    $SmartFastPay = new SmartFastPay($TransactionId, $status, $externoId, $amonut);
    $response = $SmartFastPay->confirmation(json_encode($data));

    $response = json_decode($response);

    syslog(LOG_WARNING, "SMARTFASTPAY REQUEST: " . json_encode($data));

    syslog(LOG_WARNING, "SMARTFASTPAY RESPONSE: " . json_encode($response));

    $result = $response;

    $data = [];
    $data["success"] = true;
    $data["status"] = $status;
    $data["code"] = 0;
    $data["error"] = false;
    $data["message"] = "Recibido con exito. $TransactionId ";
    $data["result"] = $result;

    http_response_code(200);
    print_r(json_encode($data));
}
