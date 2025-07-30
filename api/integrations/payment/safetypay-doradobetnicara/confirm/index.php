<?php
/**
 * Este archivo contiene un script para procesar y confirmar actividades de operaciones
 * relacionadas con el sistema de pagos SafetyPay en modo confirmación.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST             Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $log                  Variable que almacena un registro de las solicitudes y datos recibidos.
 * @var mixed $proxy                Objeto que maneja la comunicación con el proxy de SafetyPay.
 * @var mixed $NewOperationActivity Resultado de la consulta de nuevas actividades de operación.
 * @var mixed $ListOfOperations     Lista de operaciones obtenidas del proxy.
 * @var mixed $operation            Variable que representa una operación individual dentro de la lista.
 * @var mixed $CreationDateTime     Fecha y hora de creación de la operación.
 * @var mixed $OperationID          Identificador único de la operación.
 * @var mixed $MerchantSalesID      Identificador de ventas del comerciante.
 * @var mixed $MerchantOrderID      Identificador de la orden del comerciante.
 * @var mixed $OperationActivities  Actividades relacionadas con la operación.
 * @var mixed $OperationActivity    Actividad específica de la operación.
 * @var mixed $Status               Estado de la operación.
 * @var mixed $StatusCode           Código del estado de la operación.
 * @var mixed $SafetyPay            Objeto que maneja la lógica de confirmación de operaciones.
 * @var mixed $toconfirm_array      Arreglo que contiene los datos de la operación a confirmar.
 * @var mixed $toconfirm            Arreglo que agrupa las operaciones a confirmar.
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

    $SafetyPay->confirmation();

    $toconfirm_array = array(
        'CreationDateTime' => $CreationDateTime,
        'OperationID' => $OperationID,
        'MerchantSalesID' => $MerchantSalesID,
        'MerchantOrderID' => $MerchantOrderID,
        'OperationStatus' => $StatusCode
    );

    $toconfirm['ConfirmOperation'][] = $toconfirm_array;
}

$proxy->ConfirmNewOperationActivity($toconfirm);

