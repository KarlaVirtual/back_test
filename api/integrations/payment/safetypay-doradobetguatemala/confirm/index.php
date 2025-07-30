<?php
/**
 * Este archivo contiene un script para procesar y confirmar pagos a través de la integración con SafetyPay.
 * Está diseñado para manejar solicitudes de confirmación de pagos y generar respuestas adecuadas.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Payment
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 * @access     public
 *
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $data_                    Contiene los datos crudos enviados en el cuerpo de la solicitud.
 * @var mixed $data                     Objeto que contiene los datos decodificados de la solicitud.
 * @var mixed $log                      Cadena que almacena información para ser registrada en un archivo de log.
 * @var mixed $ConfigurationEnvironment Objeto que maneja la configuración del entorno (desarrollo o producción).
 * @var mixed $SafetyPay                Objeto que maneja la integración con SafetyPay.
 * @var mixed $array                    Arreglo que contiene la respuesta generada para la solicitud.
 * @var mixed $toconfirm                Arreglo que contiene los datos para confirmar la operación.
 */

ini_set('display_errors', 'off');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\payment\SafetyPay;

$data_ = (file_get_contents('php://input'));


$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');

$log = " /" . time();

$log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$data = json_decode(json_encode($_REQUEST));

if (isset($data)) {
    $CreationDateTime = $data->CreationDateTime;
    $MerchantSalesID = $data->MerchantSalesID;
    $MerchantOrderID = $MerchantSalesID;
    $StatusCode = $data->Status;
    $ReferenceNo = $data->ReferenceNo;
    $Amount = $data->Amount;
    $CurrencyID = $data->CurrencyID;
    $PaymentReferenceNo = $data->PaymentReferenceNo;
    $Signature = $data->Signature;

    $ConfigurationEnvironment = new ConfigurationEnvironment();
    if ($ConfigurationEnvironment->isDevelopment()) {
        $key = 'd0b6ac6c1bd482d90528de032166f11e';
    } else {
        $key = '4534f9d7c3a45669500a4a2768788f61';
    }

    if ($StatusCode == 101 || $StatusCode == 102) {
        $StatusCode = $data->Status;
    } else {
        $StatusCode = 100;
    }
    $hash = hash('sha256', $CreationDateTime . $MerchantSalesID . $ReferenceNo . $CreationDateTime . $Amount . $CurrencyID . $PaymentReferenceNo . $StatusCode . $MerchantSalesID . $key);
    $array = array(
        "ErrorNumber" => 0,
        "ResponseDateTime" => $CreationDateTime,
        "MerchantSalesID" => $MerchantSalesID,
        "ReferenceNo" => $ReferenceNo,
        "CreationDateTime" => $CreationDateTime,
        "Amount" => $Amount,
        "CurrencyID" => $CurrencyID,
        "PaymentReferenceNo" => $PaymentReferenceNo,
        "Status" => $StatusCode,
        "OrderNo" => $MerchantSalesID,
        "Signature" => $hash,
    );
    $array = implode(",", $array);
    echo $array;

    $SafetyPay = new SafetyPay();
    $SafetyPay->setCreationDateTime($CreationDateTime);
    $SafetyPay->setMerchantSalesID($MerchantSalesID);
    $SafetyPay->setMerchantOrderID($MerchantOrderID);
    $SafetyPay->setOperationStatus($StatusCode);

    $toconfirm_array = array(
        'CreationDateTime' => $CreationDateTime,
        'MerchantSalesID' => $MerchantSalesID,
        'MerchantOrderID' => $MerchantOrderID,
        'OperationStatus' => $StatusCode
    );

    $toconfirm['ConfirmOperation'][] = $toconfirm_array;

    $SafetyPay->confirmation();
}
