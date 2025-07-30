<?php

/**
 * Este archivo contiene un script para procesar y confirmar actividades de operación
 * relacionadas con el sistema de pagos SafetyPay en modo confirmación.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Payment
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed          $_REQUEST             Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var string         $log                  Variable que almacena los datos de entrada y los registra en un archivo de log.
 * @var SafetyPayProxy $proxy                Objeto que maneja la comunicación con el sistema SafetyPay.
 * @var array          $NewOperationActivity Datos de nuevas actividades de operación obtenidas desde SafetyPay.
 * @var array          $ListOfOperations     Lista de operaciones obtenidas desde SafetyPay.
 * @var array          $operation            Variable que representa una operación específica dentro de la lista de operaciones.
 * @var string         $CreationDateTime     Fecha y hora de creación de la operación.
 * @var string         $OperationID          Identificador único de la operación.
 * @var string         $MerchantSalesID      Identificador de ventas del comerciante.
 * @var string         $MerchantOrderID      Identificador de la orden del comerciante.
 * @var array          $OperationActivities  Actividades relacionadas con la operación.
 * @var array          $OperationActivity    Actividad específica dentro de las actividades de la operación.
 * @var array          $Status               Estado de la operación.
 * @var string         $StatusCode           Código del estado de la operación.
 * @var SafetyPay      $SafetyPay            Objeto que representa una operación de SafetyPay.
 * @var array          $toconfirm_array      Arreglo que contiene los datos de la operación a confirmar.
 * @var array          $toconfirm            Arreglo que agrupa las operaciones a confirmar.
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

$proxy = new SafetyPayProxy('1fdb4fd640b4ab492c9b3686acbca4e8', 'ed6380bd4d84784acc83ce4851fc0e85', '0');

$NewOperationActivity = $proxy->GetNewOperationActivity();


if (isset($NewOperationActivity['ListOfOperations']['Operation']['OperationID'])) {
    $ListOfOperations = $NewOperationActivity['ListOfOperations'];
} else {
    $ListOfOperations = $NewOperationActivity['ListOfOperations']['Operation'];
}


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

    $toconfirm_array = array(
        'CreationDateTime' => $CreationDateTime,
        'OperationID' => $OperationID,
        'MerchantSalesID' => $MerchantSalesID,
        'MerchantOrderID' => $MerchantOrderID,
        'OperationStatus' => $StatusCode
    );

    $toconfirm['ConfirmOperation'][] = $toconfirm_array;

    $SafetyPay->confirmation();
}
print_r('OK');
$proxy->ConfirmNewOperationActivity($toconfirm);

