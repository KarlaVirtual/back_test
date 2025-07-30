<?php

use Backend\dto\CuentaCobro;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioHistorial;
use Backend\integrations\payout\PAYKUSERVICES;
use Backend\dto\Proveedor;
use Backend\dto\ProductoMandante;
use Backend\dto\Producto;
use Backend\dto\Usuario;
use Backend\dto\TransaccionProducto;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;



/**
 * Clase 'CronJobRevisarPagosPAYKU'
 *
 *
 *
 *
 * Ejemplo de uso:
 *
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class CronJobRevisarPagosPAYKU
{


    public function __construct()
    {
    }

    public function execute()
    {

        $message = "*CRON: (Inicio) * " . " PagosPAYKU - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "Inicia: " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

//error_reporting(E_ALL);
//ini_set("display_errors", "ON");

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

        $Proveedor = new Proveedor("", "PAYKUPAY");

//$ProductoMandante = new ProductoMandante("","",$Banco->productoPago);
//$Producto = new Producto(5214);

//$ProductoMandante = new ProductoMandante($Producto->getProductoId(), "0");


        $rules = [];

        array_push($rules, array("field" => "transaccion_producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "E", "op" => "eq"));
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


        $final = [];

        $PAYKUSERVICES = new PAYKUSERVICES();

        foreach ($transacciones->data as $key => $value) {


            $transproducto_id = $value->{'transaccion_producto.transproducto_id'};
            $externo_id = $value->{'transaccion_producto.externo_id'};

            $respuesta = $PAYKUSERVICES->requestGET($externo_id);
            $respuesta = json_decode($respuesta);
            print_r($respuesta);
            print_r($externo_id);
            $transfer_status = $respuesta->wallet_movements[0]->payout->status;

            $estado = 'P';

            switch ($transfer_status) {

                case "pending":
                    $estado = 'P';
                    break;

                case "processing":
                    $estado = 'P';
                    break;

                case "success":
                    $estado = 'A';
                    break;

                case "banking_error":
                    $estado = 'R';
                    break;

                case "error":
                    $estado = 'R';
                    break;

                case "fraud_prevention":
                    $estado = 'R';
                    break;

            }

            if ($estado != "P") {
                $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                $TransaccionProducto = new TransaccionProducto($transproducto_id);

                $Transaction = $TransprodLogMysqlDAO->getTransaction();

                $TransaccionProducto->setEstado("I");
                $TransaccionProducto->setEstadoProducto($estado);
                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);

                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproducto_id);
                $TransprodLog->setEstado($estado);
                $TransprodLog->setTipoGenera('A');
                $TransprodLog->setComentario('Aprobada por Payku');
                $TransprodLog->setTValue(json_encode($respuesta));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);


                $rowsUpdate = 0;


                $CuentaCobro = new CuentaCobro("", $transproducto_id);

                if ($CuentaCobro->getEstado() == "S") {
                    if ($estado == "A") {
                        $CuentaCobro->setEstado("I");
                        $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));
                        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                        $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado = 'S') ");
                    }
                    if ($estado == "R") {
                        $CuentaCobro->setEstado("R");
                        $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));

                        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                        $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado = 'S') ");
                    }
                }


                if ($estado == "R" && $rowsUpdate > 0) {
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

            }

        }


        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;


        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "Termino: " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        $message = "*CRON: (Fin) * " . " PagosPAYKU - Fecha: " . date("Y-m-d H:i:s");


        exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


    }
}