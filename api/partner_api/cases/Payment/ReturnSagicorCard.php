<?php


use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionProducto;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;

try {
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $x_cardsave = $ConfigurationEnvironment->DepurarCaracteres($_POST['card']);

    if ($x_invoice == "") {
        $x_invoice = str_replace(" ","+",$_GET['x_invoice']);
        $x_invoice = $ConfigurationEnvironment->DepurarCaracteres($x_invoice);
    }

    if ($x_invoice == "") {
        $x_invoice = str_replace(" ","+",$_GET['id']);
        $x_invoice = $ConfigurationEnvironment->DepurarCaracteres($x_invoice);
    }
    if ($x_invoice == "") {
        $x_invoice = str_replace(" ","+",$_POST['id']);
        $x_invoice = $ConfigurationEnvironment->DepurarCaracteres($x_invoice);
    }
    if($ConfigurationEnvironment->isProduction()) {
        $x_invoice = str_replace(" ","+",$x_invoice);
        $x_invoice = $ConfigurationEnvironment->decrypt($x_invoice);

    }
    if ($x_invoice != '' && is_numeric($x_invoice) && $x_cardsave != '' && is_numeric($x_cardsave)) {

        $merchant='TESTb2c_psjljbet';
        $merchantPassword='afa5074fa7f61a80c591dbb16bb36dca';

        if($ConfigurationEnvironment->isProduction()){
            $merchant='b2c_psjljbet';
            $merchantPassword='a0340608c509f3da1bfed4b526ceffeb';

        }

        $TransaccionProducto = new TransaccionProducto($x_invoice);

        $UsuarioTarjetacredito = new UsuarioTarjetacredito($x_cardsave);

        if($TransaccionProducto->getEstado() != "A"){
            throw new Exception("Deposito ya procesado","500");

        }

        $Producto = new Producto($TransaccionProducto->productoId);
        $Proveedor = new Proveedor($Producto->proveedorId);
        $Usuario = new Usuario($TransaccionProducto->usuarioId);
        $Mandante = new Mandante($Usuario->mandante);

        if ($Proveedor->abreviado == 'SAGICOR') {


            $data = array();
            $data["apiOperation"] = "PAY";


            $data["order"] = array();
            $data["order"]["amount"] = $TransaccionProducto->valor;
            $data["order"]["currency"] = $Usuario->moneda;
                $data["order"]["reference"] = $TransaccionProducto->transproductoId;


            $data["session"] = array();
            $data["session"]["id"] = $TransaccionProducto->externoId;

            $data["sourceOfFunds"] = array();
            $data["sourceOfFunds"]["token"] = $ConfigurationEnvironment->decrypt($UsuarioTarjetacredito->getToken());

            $service_url = "https://sagicorbank.gateway.mastercard.com/api/rest/version/50/merchant/".$merchant."/order/" . $TransaccionProducto->transproductoId . "/transaction/1";


            $curl = curl_init($service_url);
            //curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);

            curl_setopt($curl, CURLOPT_USERPWD, 'merchant.'.$merchant . ":" . $merchantPassword);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_TIMEOUT, 300);


            $Result = (curl_exec($curl));

            $Result = json_decode($Result);
            curl_close($curl);

            if ($Result != "" && $Result != null) {


                if ($Result->response != null) {
                    if ($Result->response->acquirerCode == "00") {

                        $TransaccionProducto->setUsutarjetacredId($UsuarioTarjetacredito->getUsutarjetacreditoId());

                        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
                        $TransaccionProductoMySqlDAO->update($TransaccionProducto);
                        $TransaccionProductoMySqlDAO->getTransaction()->commit();


                        // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                        $estado = 'A';

                        // Comentario personalizado para el log
                        $comentario = 'Aprobada por Sagicor ';


                        // Tipo que genera el log (A: Automatico, M: Manual)
                        $tipo_genera = 'A';


                        $TransaccionProducto->setAprobada($TransaccionProducto->transproductoId, $tipo_genera, $estado, $comentario, json_encode($Result), $Result->authorizationResponse->transactionIdentifier);

                        $redirect = $Mandante->baseUrl . '/gestion/deposito/correcto';

                        // header('Location: ' . $redirect);

                        $response["HasError"] = false;
                        $response["Redirection"] = $redirect;

                    } else {
                        $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';

                        // header('Location: ' . $redirect);

                        $response["HasError"] = false;
                        $response["Redirection"] = $redirect;

                    }
                } else {
                    $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';

                    // header('Location: ' . $redirect);

                    $response["HasError"] = false;
                    $response["Redirection"] = $redirect;


                }

            }

        } else {

            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = '';

        }


    } else {
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = '';

    }


}catch (Exception $e){
throw ($e);
}
