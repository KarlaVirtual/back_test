<?php

/**
 * Este archivo maneja la confirmación de pagos realizados a través de Payphone.
 * Procesa las solicitudes entrantes, registra logs y realiza la confirmación
 * de transacciones según los datos proporcionados.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Payphonepay
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST    Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV        Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $data        Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $params      Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $fp          Variable que almacena información sobre la forma de pago.
 * @var mixed $log         Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $Username    Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $_SERVER     Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $Password    Variable que almacena una contraseña o clave de acceso.
 * @var mixed $externo_id  Variable que almacena un identificador externo de una transacción.
 * @var mixed $trans       Variable que almacena información sobre una transacción.
 * @var mixed $status      Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $Payphonepay Variable que almacena información de pagos realizados con Payphone.
 * @var mixed $Response    Esta variable contiene la respuesta generada por una operación o solicitud.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');


use Backend\dto\ConfigurationEnvironment;
use \Backend\integrations\payment\Payphonepay;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}
$_ENV["enabledConnectionGlobal"] = 1;

$data = (file_get_contents('php://input'));
$params = $_REQUEST;
$params = json_encode($params);
$params = json_decode($params);

$data = json_decode($data);

if (isset($data) || isset($params)) {
    //Payphonepay

    if ($params != '' && ! isset($data)) {
        $externo_id = $params->id;
        $trans = $params->clientTransactionId;
        $status = 'PROGRESS';

        /* Procesamos */
        $Payphonepay = new Payphonepay($trans, $status, $externo_id);
        $Response = $Payphonepay->confirmation(json_encode($data), true);
        $Response = json_decode($Response);

        if ($_ENV['debug'] == true) {
            print_r(json_encode($Response));
        }

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
        $Payphonepay = new Payphonepay($trans, $status, $externo_id);
        $Payphonepay->confirmation(json_encode($data));
    }
}
