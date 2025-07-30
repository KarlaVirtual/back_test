<?php


//require('/home/backend/public_html/api/vendor/autoload.php');
//require(__DIR__ . '../../../../../vendor/autoload.php');
require(__DIR__.'/../vendor/autoload.php');



use Backend\dto\CuentaCobro;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioHistorial;
use Backend\integrations\payout\PAYKUSERVICES;
use Backend\integrations\payment\PAYCASHSERVICES;
use Backend\integrations\payment\Paycash;
use Backend\dto\Proveedor;
use Backend\dto\ProductoMandante;
use Backend\dto\Producto;
use Backend\dto\Usuario;
use Backend\dto\TransaccionProducto;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;

$message="*CRON: (Inicio) * " . " PagosPAYKU - Fecha: " . date("Y-m-d H:i:s");

exec("php -f /home/backend/public_html/api/src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");





//date_default_timezone_set('America/Bogota');

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = '2023-08-11';
$FromDateLocal = '2023-08-11';

$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $ToDateLocal) . " 00:00:00"));
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal) . " 23:59:59"));


$TransaccionProducto = new TransaccionProducto();



$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$Proveedor = new Proveedor("", "PAYCASH");

//$ProductoMandante = new ProductoMandante("","",$Banco->productoPago);
//$Producto = new Producto(5214);

//$ProductoMandante = new ProductoMandante($Producto->getProductoId(), "0");

$rules = [];

array_push($rules, array("field" => "transaccion_producto.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "E", "op" => "eq"));
array_push($rules, array("field" => "producto.proveedor_id", "data" => "$Proveedor->proveedorId", "op" => "eq"));
array_push($rules, array("field" => "transaccion_producto.estado", "data" => "A", "op" => "eq"));
//array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
//array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

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

$final = [];

$PAYCASHSERVICES = new PAYCASHSERVICES();

foreach ($transacciones->data as $key => $value) {

    $transproducto_id = $value->{'transaccion_producto.transproducto_id'};
    $usuario_id = $value->{'transaccion_producto.usuario_id'};

    $Usuario = new Usuario($usuario_id);
    $mandante = $Usuario->mandante;

    $Paycash = new Paycash('', '', '', '');
    $resp = $Paycash->paisGet($usuario_id);
    $pais = 'mexico';
    switch ($resp) {
        case "173":
            $pais = 'peru';
            break;
        case "146":
            $pais = 'mexico';
            break;
        case "170":
            $pais = 'panama';
            break;
    }

    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $fecha = '2023-08-11';

    $nuevahora = strtotime ( '-2 hour' , strtotime ( $hora ) ) ;
    $nuevahora = date ( 'H:i:s' , $nuevahora );
    $respuesta = $PAYCASHSERVICES->requestGET($pais, $fecha, $nuevahora, '/v1/payments?', $mandante);
    $respuesta = json_decode($respuesta);

    foreach ($respuesta[0]->Payments as $key => $value) {
        $Paycash = new Paycash($value->PaymentId, $value->RefValue, $value->Amount, $value->Status);
        $Paycash->confirmation($respuesta);
    }
}

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;



$message="*CRON: (Fin) * " . " PagosPAYKU - Fecha: " . date("Y-m-d H:i:s");


exec("php -f /home/backend/public_html/api/src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");
