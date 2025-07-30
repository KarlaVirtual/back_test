<?php

require('/home/backend/public_html/api/vendor/autoload.php');


use Backend\dto\CuentaCobro;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioHistorial;
use Backend\integrations\payout\GLOBOKASSERVICES;
use Backend\integrations\payout\LPGSERVICES;
use Backend\dto\Proveedor;
use Backend\dto\ProductoMandante;
use Backend\dto\Producto;
use Backend\dto\Usuario;
use Backend\dto\TransaccionProducto;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;

$message="*CRON: (Inicio) * " . " PagosLPG - Fecha: " . date("Y-m-d H:i:s");

exec("php -f /home/backend/public_html/api/src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log ."Inicia: ". date('Y-m-d H:i:s');
fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


ini_set("display_errors", "off");



$TransaccionProducto = new TransaccionProducto();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->StartTimeLocal;
$TypeId = $params->TypeId;

$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $ToDateLocal) . " 00:00:00"));
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal) . " 23:59:59"));


$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$Proveedor = new Proveedor("", "GLOBOKAS");

//$ProductoMandante = new ProductoMandante("","",$Banco->productoPago);
$Producto = new Producto("","GLOBOKAS",$Proveedor->getProveedorId());

$ProductoMandante = new ProductoMandante($Producto->getProductoId(), "0");

print_r($ProductoMandante);

$rules = [];

array_push($rules, array("field" => "transaccion_producto.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "E", "op" => "eq"));
array_push($rules, array("field" => "transaccion_producto.producto_id", "data" => "$ProductoMandante->productoId", "op" => "eq"));


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

//$LPGSERVICES = new LPGSERVICES();
$GLOBOKASSERVICES = new GLOBOKASSERVICES();
//$apiLogin = $LPGSERVICES->apiLogin();

//print_r($apiLogin);
foreach ($transacciones->data as $key => $value) {

    $transproducto_id = $value->{'transaccion_producto.transproducto_id'};
    $externo_id = $value->{'transaccion_producto.externo_id'};


   // $respuesta = $LPGSERVICES->requestGET("/api/transactions/" . $externo_id, array(), array("Authorization: LPG " . $LPGSERVICES->token, "Content-Type: application/json"));
    $respuesta = $GLOBOKASSERVICES->ListProcessed();
    $respuesta = json_decode($respuesta);

    $transfer_status = $respuesta->result->paymentOrders->status;

    /*
     *
        {
            "id": 1,
            "name": "in process"
        },
        {
            "id": 2,
            "name": "approved"
        },
        {
            "id": 3,
            "name": "rejected"
        },
        {
            "id": 4,
            "name": "rejected by bank"
        },
        {
            "id": 5,
            "name": "approved by bank"
        }
     */

    $estado = 'P';

    switch ($transfer_status) {
        case "Pending":
            $estado = 'R';
            break;
        case "Paid":
            $estado = 'A';
            $estado = 'P';
            break;
        case "3":
            $estado = 'R';
            break;
        case "4":
            $estado = 'R';
            break;
        case "5":
            $estado = 'A';
            break;
    }

    if ($estado != "P") {
        $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

        $TransaccionProducto = new TransaccionProducto($transproducto_id);
        $Transaction=$TransprodLogMysqlDAO->getTransaction();

        $TransaccionProducto->setEstado("I");
        $TransaccionProducto->setEstadoProducto($estado);
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
        $TransaccionProductoMySqlDAO->update($TransaccionProducto);

        $TransprodLog = new TransprodLog();
        $TransprodLog->setTransproductoId($transproducto_id);
        $TransprodLog->setEstado($estado);
        $TransprodLog->setTipoGenera('A');
        $TransprodLog->setComentario(json_decode($respuesta));
        $TransprodLog->setTValue(json_decode($respuesta));
        $TransprodLog->setUsucreaId(0);
        $TransprodLog->setUsumodifId(0);

        $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);



        $rowsUpdate=0;
        $CuentaCobro = new CuentaCobro("", $transproducto_id);

        if ($CuentaCobro->getEstado() == "S") {
            if ($estado == "A") {
                $CuentaCobro->setEstado("I");
                $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));
                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                $rowsUpdate=$CuentaCobroMySqlDAO->update($CuentaCobro," AND (estado = 'S') ");
            }
            if ($estado == "R") {
                $CuentaCobro->setEstado("R");
                $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                $rowsUpdate=$CuentaCobroMySqlDAO->update($CuentaCobro," AND (estado = 'S') ");
            }
        }



        if($estado == "R" && $rowsUpdate > 0){
            $Usuario = new Usuario($TransaccionProducto->usuarioId);
            $Usuario->creditWin($TransaccionProducto->getValor(), $Transaction);



            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('E');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(40);
            $UsuarioHistorial->setValor($TransaccionProducto->getValor());
            $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());


            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

        }


        $Transaction->commit();

        print_r($TransprodLog);
        print_r($transfer_status);
    }


}


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log ."Termino: ". date('Y-m-d H:i:s');
fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

$message="*CRON: (Fin) * " . " PagosKASNET - Fecha: " . date("Y-m-d H:i:s");

exec("php -f /home/backend/public_html/api/src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");









