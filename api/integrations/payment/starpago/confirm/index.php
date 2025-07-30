<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Starpago.
 * Procesa los datos recibidos, registra logs y realiza la confirmación de transacciones
 * en la plataforma de pago Starpago.
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
 * @var mixed $_REQUEST      Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV          Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $data          Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp            Variable que almacena información sobre la forma de pago.
 * @var mixed $log           Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $TransactionId Variable que almacena el identificador de una transacción.
 * @var mixed $externoId     Variable que almacena un identificador externo en Internpay.
 * @var mixed $status        Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $amonut        Variable que almacena un monto en una transacción (posible error tipográfico de "amount").
 * @var mixed $Starpago      Variable que hace referencia a la plataforma de pago Starpago.
 * @var mixed $response      Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $data_         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use \Backend\integrations\payment\Starpago;
use Backend\dto\CuentaCobro;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

$data = (file_get_contents('php://input'));

$data = json_decode($data);

if (isset($data)) {
    //Starpago

    $TransactionId = $data->merOrderNo;
    $externoId = $data->orderNo;
    $status = $data->orderStatus;
    $amonut = $data->amount;

    if ($status == '1' || $status == '0') {
        $status = 'PROGRESS';
    } elseif ($status == '-1' || $status == '-2' || $status == '-3') {
        $status = 'CANCEL';
    } elseif ($status == '2' || $status == '3') {
        $status = 'SUCCESS';
    }

    /* Procesamos */
    $Starpago = new Starpago($TransactionId, $status, $externoId, $amonut);
    $response = $Starpago->confirmation(json_encode($data));

    $response = json_decode($response);

    $data_ = array();
    if ($response->result == 'success') {
        $data_ = "success";
    } elseif ($response->result == 'error') {
        $data_ = "success";
    } else {
        http_response_code(400);
        $data_ = "error";
    }

    echo json_encode($data_);
}
