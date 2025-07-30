<?php


require(__DIR__.'/../vendor/autoload.php');
//require(__DIR__ . '../../../../../vendor/autoload.php');
//require_once __DIR__ . '../../vendor/autoload.php';

use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\CuentaCobro;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioAlerta;
use Backend\dto\ProductoMandante;
use Backend\dto\UsuarioHistorial;
use Backend\dto\TransaccionProducto;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\payment\Payphonepay;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\integrations\payout\PAYKUSERVICES;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\integrations\payment\PAYPHONEPAYSERVICES;

$message="*CRON: (Inicio) * " . " PagosPAYPHONE - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->StartTimeLocal;

$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $ToDateLocal) . " 00:00:00"));
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal) . " 23:59:59"));


$TransaccionProducto = new TransaccionProducto();


$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$Proveedor = new Proveedor("", "PAYPHONE");
$Producto = new Producto("", "PP_PAY", $Proveedor->proveedorId);

$rules = [];

array_push($rules, array("field" => "transaccion_producto.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'E','P'", "op" => "in"));
array_push($rules, array("field" => "producto.producto_id", "data" => "$Producto->productoId", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}

$json = json_encode($filtro);

$transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

$transacciones = json_decode($transacciones);

$ConfigurationEnvironment = new ConfigurationEnvironment();
if ($ConfigurationEnvironment->isDevelopment()) {
    $url = 'https://apidev.virtualsoft.tech/integrations/payment/payphonepay/confirm/';
} else {
    $url = 'https://integrations.virtualsoft.tech/payment/payphonepay/confirm/';
}

$final = array();

$PAYPHONEPAYSERVICES = new PAYPHONEPAYSERVICES();

$path = '/api/sale/client/';

foreach ($transacciones->data as $key => $value) {

    $transproducto_id = $value->{'transaccion_producto.transproducto_id'};
    $usuario_id = $value->{'transaccion_producto.usuario_id'};

    $Usuario = new Usuario($usuario_id);
    $mandante = $Usuario->mandante;

    $respuesta = $PAYPHONEPAYSERVICES->connectionGET($mandante, $transproducto_id, $path, $Usuario);
    $data = json_decode($respuesta);

    $transproducto_id= $data[0]->clientTransactionId;
    $externo_id = $data[0]->transactionId;
    $status = $data[0]->transactionStatus;

    if ($status == 'Pending') {
        $status = 'PROGRESS';
    }else if ($status == 'Canceled') {
        $status = 'CANCEL';
    }else if ($status == 'Approved') {
        $status = 'SUCCESS';
    }

    /* Procesamos */
    $Payphonepay = new Payphonepay($transproducto_id, $status, $externo_id);
    $Payphonepay->confirmation(json_encode($data));

    $data_ = array();
    $data_["status"] = $status;
    $resp = json_encode($data_);

    $resp = json_decode($resp);

    $data = array(
        "transaccion_producto" => $transproducto_id,
        "status" => $resp->status
    );
    $final[] = $data;
}

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Data"] = $final;

$message="*CRON: (Fin) * " . " PagosPAYKU - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");
