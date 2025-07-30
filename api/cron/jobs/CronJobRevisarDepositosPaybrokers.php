<?php


require(__DIR__.'/../vendor/autoload.php');

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
use Backend\integrations\payment\Paybrokers;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\integrations\payout\PAYKUSERVICES;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\integrations\payment\PAYBROKERSSERVICES;


class CronJobRevisarDepositosPaybrokers
{


    public function __construct()
    {
    }

    public function execute()
    {

        $message = "*CRON: (Inicio) * " . " Paybrokers - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

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

        $Proveedor = new Proveedor("", "PAYBROKERS");
        $Producto = new Producto("", "0", $Proveedor->proveedorId);

        $rules = [];
        array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "2024-03-01 00:00:00 ", "op" => "ge"));

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

        $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "desc", $SkeepRows, $MaxRows, $json, true);

        $transacciones = json_decode($transacciones);

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $url = 'https://apidev.virtualsoft.tech/integrations/payment/paybrokers/confirm/';
        } else {
            $url = 'https://integrations.virtualsoft.tech/payment/paybrokers/confirm/';
        }

        $final = array();

        $PAYBROKERSSERVICES = new PAYBROKERSSERVICES();

        foreach ($transacciones->data as $key => $value) {

            $transproducto_id = $value->{'transaccion_producto.transproducto_id'};
            $usuario_id = $value->{'transaccion_producto.usuario_id'};
            $pix_id = $value->{'transaccion_producto.externo_id'};

            $Usuario = new Usuario($usuario_id);
            $mandante = $Usuario->mandante;
            $Credentials = $PAYBROKERSSERVICES->Credentials($Usuario);
            $URL = $Credentials->URL;

            $header = $PAYBROKERSSERVICES->buildAuthorizationHeader($mandante, $Usuario);

            $Respueta = $PAYBROKERSSERVICES->ConnectionToken($header, $mandante, $Usuario);

            $token = $Respueta->token;

            $data = $PAYBROKERSSERVICES->GetPIXPayment($pix_id, $token, $mandante, $URL);

            $transproducto_id = $data->reference_id;
            $externo_id = $data->acquirer_id;
            $status = $data->status;
            $value = $data->value;

            if ($status == 'CANCELED' || $status == 'EXPIRED' || $status == 'REFUNDED') {
                $status = 'CANCELED';
            }

            /* Procesamos */
            $Paybrokers = new Paybrokers($pix_id, $transproducto_id, $value, $status);
            $Paybrokers->confirmation(json_encode($data));

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

        echo json_encode($response);


        $message = "*CRON: (Fin) * " . " Paybrokers - Fecha: " . date("Y-m-d H:i:s");


        exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");
    }
}