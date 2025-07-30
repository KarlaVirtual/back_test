<?php
/**
 * Este archivo contiene un script para procesar y confirmar pagos a través de la integración con SafetyPay.
 * Se reciben datos de entrada, se procesan y se genera una respuesta con la información del estado de la operación.
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
 * @var mixed $_REQUEST           Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $data_              Contiene los datos de entrada en formato JSON obtenidos del cuerpo de la solicitud.
 * @var mixed $data               Objeto que contiene los datos decodificados de la solicitud.
 * @var mixed $log                Cadena que almacena información para ser registrada en un archivo de log.
 * @var mixed $CreationDateTime   Fecha y hora de creación de la operación.
 * @var mixed $MerchantSalesID    Identificador de ventas del comerciante.
 * @var mixed $OperationID        Identificador de la operación.
 * @var mixed $MerchantOrderID    Identificador de la orden del comerciante.
 * @var mixed $StatusCode         Código de estado de la operación.
 * @var mixed $ReferenceNo        Número de referencia de la operación.
 * @var mixed $Amount             Monto de la operación.
 * @var mixed $CurrencyID         Identificador de la moneda utilizada.
 * @var mixed $PaymentReferenceNo Número de referencia del pago.
 * @var mixed $Signature          Firma generada para validar la operación.
 * @var mixed $key                Clave utilizada para generar la firma de validación.
 * @var mixed $array              Arreglo que contiene la respuesta generada para la operación.
 * @var mixed $toconfirm          Arreglo que contiene los datos para confirmar la operación.
 */

ini_set('display_errors', 'off');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\payment\SafetyPay;

$data_ = (file_get_contents('php://input'));

$_ENV["enabledConnectionGlobal"] = 1;

$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');

$log = " /" . time();

$log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$data = json_decode(json_encode($_REQUEST));

if (isset($data) && $data->CreationDateTime != '') {
    $CreationDateTime = $data->CreationDateTime;
    $MerchantSalesID = $data->MerchantSalesID;
    $OperationID = $data->OperationID;
    $MerchantOrderID = $MerchantSalesID;
    $StatusCode = $data->Status;
    $ReferenceNo = $data->ReferenceNo;
    $Amount = $data->Amount;
    $CurrencyID = $data->CurrencyID;
    $PaymentReferenceNo = $data->PaymentReferenceNo;
    $Signature = $data->Signature;

    $ConfigurationEnvironment = new ConfigurationEnvironment();
    if ($ConfigurationEnvironment->isDevelopment()) {
        $key = '';
    } else {
        $key = '76b6025c8e446f5b24c4b34e04f36a9d';
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
    $SafetyPay->setOperationID($OperationID);

    $toconfirm_array = array(
        'CreationDateTime' => $CreationDateTime,
        'MerchantSalesID' => $MerchantSalesID,
        'MerchantOrderID' => $MerchantOrderID,
        'OperationStatus' => $StatusCode
    );

    $toconfirm['ConfirmOperation'][] = $toconfirm_array;

    $SafetyPay->confirmation();
}
