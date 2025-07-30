<?php

/**
 * Archivo principal para confirmar actividades de operaciones relacionadas con SafetyPay.
 * Este script procesa las solicitudes entrantes y realiza las confirmaciones necesarias
 * utilizando la integración con la plataforma SafetyPay.
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
 * @var mixed $log                  Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST             Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $proxy                Variable que almacena la dirección de un servidor proxy.
 * @var mixed $NewOperationActivity Variable que almacena información sobre una nueva actividad de operación.
 * @var mixed $ListOfOperations     Variable que almacena una lista de operaciones.
 * @var mixed $operation            Variable que almacena información sobre una operación.
 * @var mixed $CreationDateTime     Variable que almacena la fecha y hora de creación de una operación.
 * @var mixed $OperationID          Variable que almacena el identificador único de una operación.
 * @var mixed $MerchantSalesID      Variable que almacena el identificador de venta del comerciante.
 * @var mixed $MerchantOrderID      Variable que almacena el identificador de la orden del comerciante.
 * @var mixed $OperationActivities  Variable que almacena actividades relacionadas con una operación.
 * @var mixed $OperationActivity    Variable que almacena una actividad específica dentro de una operación.
 * @var mixed $Status               Variable que almacena el estado actual.
 * @var mixed $StatusCode           Variable que almacena un código de estado.
 * @var mixed $SafetyPay            Variable que almacena información sobre la plataforma de pagos SafetyPay.
 * @var mixed $toconfirm_array      Variable que almacena una lista de elementos por confirmar.
 * @var mixed $toconfirm            Variable que indica si una transacción debe ser confirmada.
 * @var mixed $e                    Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 */

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\SafetyPay;


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

require_once '../safetypay/class/SafetyPayProxy.php';


$proxy = new SafetyPayProxy();

$NewOperationActivity = $proxy->GetNewOperationActivity();


if (isset($NewOperationActivity['ListOfOperations']['Operation']['OperationID'])) {
    $ListOfOperations = $NewOperationActivity['ListOfOperations'];
} else {
    $ListOfOperations = $NewOperationActivity['ListOfOperations']['Operation'];
}

print_r('ListOfOperations');
print_r($ListOfOperations);


foreach ($ListOfOperations as $operation) {
    $CreationDateTime = $operation['CreationDateTime'];
    $OperationID = $operation['OperationID'];
    $MerchantSalesID = $operation['MerchantSalesID'];
    $MerchantOrderID = $MerchantSalesID;
    $OperationID = $operation['OperationID'];
    $OperationActivities = $operation['OperationActivities'];
    $OperationActivity = $OperationActivities['OperationActivity'];
    $Status = $OperationActivity['Status'];
    $StatusCode = $Status['StatusCode'];

    $SafetyPay = new SafetyPay();
    $SafetyPay->setCreationDateTime($CreationDateTime);
    $SafetyPay->setMerchantSalesID($MerchantSalesID);
    $SafetyPay->setMerchantOrderID($MerchantOrderID);
    $SafetyPay->setOperationID($OperationID);
    $SafetyPay->setOperationStatus($StatusCode);
    try {
        $SafetyPay->confirmation();

        $toconfirm_array = array(
            'CreationDateTime' => $CreationDateTime,
            'OperationID' => $OperationID,
            'MerchantSalesID' => $MerchantSalesID,
            'MerchantOrderID' => $MerchantOrderID,
            'OperationStatus' => $StatusCode
        );

        $toconfirm['ConfirmOperation'][] = $toconfirm_array;
    } catch (Exception $e) {
    }
}

$proxy->ConfirmNewOperationActivity($toconfirm);

