<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la pasarela Internpay.
 *
 * Procesa los datos recibidos en la solicitud, registra logs de las operaciones
 * y realiza la confirmación de transacciones según el estado proporcionado.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Internpay
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data       Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $params     Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $_REQUEST   Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $fp         Variable que almacena información sobre la forma de pago.
 * @var mixed $log        Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $Username   Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $_SERVER    Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $Password   Variable que almacena una contraseña o clave de acceso.
 * @var mixed $externo_id Variable que almacena un identificador externo de una transacción.
 * @var mixed $trans      Variable que almacena información sobre una transacción.
 * @var mixed $status     Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $Internpay  Variable que almacena información sobre la pasarela de pago Internpay.
 * @var mixed $Response   Esta variable contiene la respuesta generada por una operación o solicitud.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');


use Backend\dto\ConfigurationEnvironment;
use \Backend\integrations\payment\Internpay;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

$data = (file_get_contents('php://input'));
$params = $_REQUEST;
$params = json_encode($params);
$params = json_decode($params);

$data = json_decode($data);

if (isset($data) || isset($params)) {
    if ($params != '' && ! isset($data)) {
        $externo_id = $params->id;
        $trans = $params->clientTransactionId;
        $status = 'PROGRESS';

        /* Procesamos */
        $Internpay = new Internpay($trans, $status, $externo_id);
        $Response = $Internpay->confirmation(json_encode($data), true);
        $Response = json_decode($Response);

        header("Location: " . $Response->redirect);
        exit();
    } else {
        $externo_id = $data->id;
        $trans = $data->clientTransactionId;
        $status = $data->status;

        if ($status == 400 || $status == '400') {
            $status = 'PROGRESS';
        } elseif ($status == '500' || $status == 500) {
            $status = 'CANCEL';
        } elseif ($status == '200' || $status == 200) {
            $status = 'SUCCESS';
        }

        /* Procesamos */
        $Internpay = new Internpay($trans, $status, $externo_id);
        $Internpay->confirmation(json_encode($data));
    }
}
