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
 * @var mixed $log                  Variable que almacena los datos de registro generados durante la ejecución.
 * @var mixed $Proveedor            Objeto que representa un proveedor en el sistema.
 * @var mixed $ProveedorMandante    Objeto que representa la relación entre un proveedor y un mandante.
 * @var mixed $rules                Arreglo que contiene las reglas de filtrado para consultas.
 * @var mixed $filtro               Arreglo que almacena las reglas de filtrado en formato JSON.
 * @var mixed $productos            Variable que almacena los datos de productos obtenidos de la consulta.
 * @var mixed $proveedor            Objeto que contiene los datos del proveedor seleccionado.
 * @var mixed $toconfirm            Arreglo que almacena las operaciones a confirmar.
 * @var mixed $proxy                Objeto que maneja la comunicación con la API de SafetyPay.
 * @var mixed $NewOperationActivity Datos de nuevas actividades de operación obtenidas de SafetyPay.
 * @var mixed $ListOfOperations     Lista de operaciones obtenidas de la API de SafetyPay.
 * @var mixed $operation            Variable que representa una operación individual en la lista.
 * @var mixed $SafetyPay            Objeto que maneja la confirmación de operaciones en SafetyPay.
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

array_push($rules, array("field" => "proveedor_mandante.mandante", "data" => "6", "op" => "eq"));
array_push($rules, array("field" => "proveedor_mandante.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$productos = $ProveedorMandante->getProveedoresMandanteCustom(" proveedor_mandante.*,proveedor.*,mandante.* ", "proveedor_mandante.provmandante_id", "asc", 0, 1, $jsonfiltro, true);

$productos = json_decode($productos);

$proveedor = $productos->data[0];
$toconfirm = ['ConfirmOperation'];

try {
    if ($proveedor != "" && $proveedor != null) {
        if ($_ENV['debug']) {
            $detalleP = json_decode($proveedor->{"proveedor_mandante.detalle"});

            $Enviroment = '0';

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment()) {
                $Enviroment = '1';
            }

            $proxy = new SafetyPayProxy($detalleP->ApiKey, $detalleP->SignatureKey, $Enviroment);
            $toconfirm_array = array(
                'CreationDateTime' => "2021-02-15T23:24:56",
                'OperationID' => "0121046453011611",
                'MerchantSalesID' => "3815526",
                'MerchantOrderID' => "3815526",
                'OperationStatus' => "102"
            );

            $toconfirm['ConfirmOperation'][] = $toconfirm_array;
            $toconfirm_array = array(
                'CreationDateTime' => "2021-01-29T17:07:57",
                'OperationID' => "0121029724363263",
                'MerchantSalesID' => "3532085",
                'MerchantOrderID' => "3532085",
                'OperationStatus' => "102"
            );

            $toconfirm['ConfirmOperation'][] = $toconfirm_array;
            $toconfirm_array = array(
                'CreationDateTime' => "2021-02-12T22:14:39",
                'OperationID' => "0121043936930630",
                'MerchantSalesID' => "3770229",
                'MerchantOrderID' => "3770229",
                'OperationStatus' => "102"
            );

            $toconfirm['ConfirmOperation'][] = $toconfirm_array;

            print_r($toconfirm);

            try {
                $log = "\r\n" . "-------------confirm------------" . "\r\n";
                $log = $log . json_encode($toconfirm);
                $log = $log . "\r\n" . "-------------confirm2------------" . "\r\n";
                $log = $log . json_encode($proxy->conf);

                fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
            } catch (Exception $e) {
            }


            $proxy->ConfirmNewOperationActivity($toconfirm);

            exit();
        }
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


            try {
                $SafetyPay = new SafetyPay();
                $SafetyPay->setCreationDateTime($CreationDateTime);
                $SafetyPay->setMerchantSalesID($MerchantSalesID);
                $SafetyPay->setMerchantOrderID($MerchantOrderID);
                $SafetyPay->setOperationID($OperationID);
                $SafetyPay->setOperationStatus($StatusCode);

                $SafetyPay->confirmation();
            } catch (Exception $e) {
            }

            $toconfirm_array = array(
                'CreationDateTime' => $CreationDateTime,
                'OperationID' => $OperationID,
                'MerchantSalesID' => $MerchantSalesID,
                'MerchantOrderID' => $MerchantOrderID,
                'OperationStatus' => $StatusCode
            );

            $log = "\r\n" . "-------------confirmARRAY------------" . "\r\n";
            $log = $log . json_encode($toconfirm_array);

            $log = $log . json_encode($proxy->conf);
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            $toconfirm['ConfirmOperation'][] = $toconfirm_array;


        }
        try {
            $log = "\r\n" . "-------------confirm------------" . "\r\n";
            $log = $log . json_encode($toconfirm);
            $log = $log . "\r\n" . "-------------confirm2------------" . "\r\n";

            $log = $log . json_encode($proxy->conf);

            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
        } catch (Exception $e) {
        }


        $proxy->ConfirmNewOperationActivity($toconfirm);
    }
} catch (Exception $e) {
}