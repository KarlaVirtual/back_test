<?php

require_once __DIR__ . '../../vendor/autoload.php';

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
use Backend\integrations\payment\Totalpago;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\integrations\payout\PAYKUSERVICES;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\integrations\payment\TOTALPAGOSERVICES;

$message="*CRON: (Inicio) * " . " PagosPAYKU - Fecha: " . date("Y-m-d H:i:s");

exec("php -f /home/backend/public_html/api/src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");

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

$Proveedor = new Proveedor("", "TOTALPAGO");

$rules = [];

array_push($rules, array("field" => "transaccion_producto.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'E','P'", "op" => "in"));
array_push($rules, array("field" => "producto.proveedor_id", "data" => "$Proveedor->proveedorId", "op" => "eq"));

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
    $url = 'https://apidev.virtualsoft.tech/integrations/payment/totalpago/confirm/';
} else {
    $url = 'https://integrations.virtualsoft.tech/payment/totalpago/confirm/';
}

$final = array();

$TOTALPAGOSERVICES = new TOTALPAGOSERVICES();

foreach ($transacciones->data as $key => $value) {

    $transproducto_id = $value->{'transaccion_producto.transproducto_id'};
    $usuario_id = $value->{'transaccion_producto.usuario_id'};
    $Producto = new Producto($value->{'transaccion_producto.producto_id'});

    $Usuario = new Usuario($usuario_id);
    $mandante = $Usuario->mandante;


    $respuesta = $TOTALPAGOSERVICES->verifyDeposit($transproducto_id, $mandante, $Usuario, $Producto);
    $data = json_decode($respuesta);

    $transproducto_id= $data->code->idPago;
    $externo_id = $data->code->idPago;
    $status = $data->code->estatus;

    if ($status == 'EN PROCESO' || $status == 'PENDIENTE') {
        $status = 'PROGRESS';
    }else if ($status == 'RECHAZADO') {
        $status = 'CANCEL';
    }else if ($status == 'APROBADO') {
        $status = 'SUCCESS';
    }

    /* Procesamos */
    $Totalpago = new Totalpago($transproducto_id, $status, $externo_id);
    $Totalpago->confirmation(json_encode($data));

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
