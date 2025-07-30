<?php

/**
 * Archivo de confirmación de la API de pago 'EnglobaMarketing'.
 *
 * Este archivo procesa las solicitudes de confirmación de pagos realizadas
 * a través de la integración con EnglobaMarketing. Se encarga de recibir
 * los datos de la solicitud, procesarlos y enviar una respuesta adecuada
 * según el estado de la transacción.
 *
 * @package ninguno
 * @version ninguna
 * @access  public
 * @author  Karla Ramirez <karla.ramirez@virtualsoft.tech>
 * @date    18.02.2025
 */

require(__DIR__ . '../../../../../vendor/autoload.php');


use Backend\dto\ConfigurationEnvironment;
use \Backend\integrations\payment\Englobamarketing;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$data = (file_get_contents('php://input'));
$params = $_REQUEST;
$params = json_encode($params);
$params = json_decode($params);

$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');
$log = " /" . time();
$log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim($data);

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$data = json_decode($data);

if (isset($data) || isset($params)) {
    if ($params != '' && ! isset($data)) {
        $externo_id = $params->id;
        $trans = $params->clientTransactionId;
        $status = 'PROGRESS';

        /* Procesamos */
        $EnglobaMarketing = new Englobamarketing($trans, $status, $externo_id);
        $Response = $EnglobaMarketing->confirmation(json_encode($data), true);
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
        $EnglobaMarketing = new Englobamarketing($trans, $status, $externo_id);
        $EnglobaMarketing->confirmation(json_encode($data));
    }
}
