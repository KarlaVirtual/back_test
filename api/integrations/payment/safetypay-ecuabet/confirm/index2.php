<?php
/**
 * Este archivo contiene un script para procesar y confirmar operaciones de pago
 * utilizando la integración con SafetyPay en modo confirmación.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 * @access     public
 *
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST             Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $log                  Variable que almacena los datos de entrada y los registros de la operación.
 * @var mixed $Proveedor            Objeto que representa un proveedor en el sistema.
 * @var mixed $ProveedorMandante    Objeto que maneja la relación entre proveedores y mandantes.
 * @var mixed $rules                Arreglo que contiene las reglas de filtrado para las consultas.
 * @var mixed $filtro               Arreglo que define los filtros aplicados a las consultas.
 * @var mixed $jsonfiltro           Cadena JSON que representa los filtros aplicados.
 * @var mixed $productos            Resultado de la consulta de proveedores mandantes.
 * @var mixed $proveedor            Objeto que contiene los datos del proveedor seleccionado.
 * @var mixed $detalleP             Detalle del proveedor mandante, incluyendo claves de API.
 * @var mixed $Enviroment           Variable que indica el entorno de ejecución (0 para producción, 1 para desarrollo).
 * @var mixed $proxy                Objeto que maneja la comunicación con la API de SafetyPay.
 * @var mixed $NewOperationActivity Resultado de la consulta de nuevas actividades de operación.
 * @var mixed $ListOfOperations     Lista de operaciones obtenidas de la API de SafetyPay.
 * @var mixed $operation            Objeto que representa una operación individual.
 * @var mixed $CreationDateTime     Fecha y hora de creación de la operación.
 * @var mixed $OperationID          Identificador único de la operación.
 * @var mixed $MerchantSalesID      Identificador de ventas del comerciante.
 * @var mixed $MerchantOrderID      Identificador de orden del comerciante.
 * @var mixed $OperationActivities  Actividades asociadas a la operación.
 * @var mixed $OperationActivity    Actividad específica de la operación.
 * @var mixed $Status               Estado de la operación.
 * @var mixed $StatusCode           Código del estado de la operación.
 * @var mixed $SafetyPay            Objeto que maneja la confirmación de operaciones en SafetyPay.
 * @var mixed $toconfirm            Arreglo que contiene las operaciones a confirmar.
 * @var mixed $toconfirm_array      Arreglo que representa una operación específica a confirmar.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ProveedorMandante;
use Backend\dto\Proveedor;
use Backend\integrations\payment\SafetyPay;

error_reporting(E_ALL);
ini_set("display_errors", "ON");


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

require_once '../safetypay/class/SafetyPayProxy.php';


$Proveedor = new Proveedor("", "SFP");


$ProveedorMandante = new ProveedorMandante();


$rules = array();

array_push($rules, array("field" => "proveedor_mandante.mandante", "data" => "8", "op" => "eq"));
array_push($rules, array("field" => "proveedor_mandante.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$productos = $ProveedorMandante->getProveedoresMandanteCustom(" proveedor_mandante.*,proveedor.*,mandante.* ", "proveedor_mandante.provmandante_id", "asc", 0, 1, $jsonfiltro, true);

$productos = json_decode($productos);

$proveedor = $productos->data[0];

if ($proveedor != "" && $proveedor != null) {
    $detalleP = json_decode($proveedor->{"proveedor_mandante.detalle"});

    $Enviroment = '0';

    $ConfigurationEnvironment = new ConfigurationEnvironment();

    if ($ConfigurationEnvironment->isDevelopment()) {
        $Enviroment = '1';
    }

    $proxy = new SafetyPayProxy($detalleP->ApiKey, $detalleP->SignatureKey, $Enviroment);

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
        if ( ! in_array($MerchantSalesID, array('SF7', 'SF6', 'SF5', 'SF4', 'SF3', 'SF2', 'SF1'))) {
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
    }

    $proxy->ConfirmNewOperationActivity($toconfirm);
}
