<?php


use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionProducto;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;

try {


    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $x_invoice = str_replace(" ", "+", $_POST['x_invoice']);
    $x_invoice = $ConfigurationEnvironment->DepurarCaracteres($_POST['x_invoice']);

    if ($x_invoice == "") {
        $x_invoice = str_replace(" ", "+", $_GET['x_invoice']);
        $x_invoice = $ConfigurationEnvironment->DepurarCaracteres($_GET['x_invoice']);
    }

    if ($x_invoice == "") {
        $x_invoice = str_replace(" ", "+", $_GET['id']);
        $x_invoice = $ConfigurationEnvironment->DepurarCaracteres($_GET['id']);
    }
    if ($ConfigurationEnvironment->isProduction()) {
        $x_invoice = str_replace(" ", "+", $x_invoice);
        $x_invoice = $ConfigurationEnvironment->decrypt($x_invoice);

    }

    if ($x_invoice != '' && is_numeric($x_invoice)) {

        $merchant = 'TESTb2c_psjljbet';
        $merchantPassword = 'afa5074fa7f61a80c591dbb16bb36dca';

        if ($ConfigurationEnvironment->isProduction()) {
            $merchant = 'b2c_psjljbet';
            $merchantPassword = 'a0340608c509f3da1bfed4b526ceffeb';

        }

        $TransaccionProducto = new TransaccionProducto($x_invoice);

        if($TransaccionProducto->getEstado() == 'I'){

            $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/correcto';

            header('Location: ' . $redirect);
        }
        if($TransaccionProducto->getEstado() != 'A') {
            $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '';

            header('Location: ' . $redirect);
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

            $service_url = "https://sagicorbank.gateway.mastercard.com/api/rest/version/50/merchant/" . $merchant . "/order/" . $TransaccionProducto->transproductoId . "/transaction/1";


            $curl = curl_init($service_url);
            //curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);

            curl_setopt($curl, CURLOPT_USERPWD, 'merchant.' . $merchant . ":" . $merchantPassword);
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


                        $data = array();

                        $data["session"] = array();
                        $data["session"]["id"] = $TransaccionProducto->externoId;

                        $data["sourceOfFunds"] = array();
                        $data["sourceOfFunds"]["type"] = "CARD";

                        $service_url = "https://sagicorbank.gateway.mastercard.com/api/rest/version/50/merchant/" . $merchant . "/token";


                        $curl = curl_init($service_url);
                        //curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);

                        curl_setopt($curl, CURLOPT_USERPWD, 'merchant.' . $merchant . ":" . $merchantPassword);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_TIMEOUT, 300);


                        $Result2 = (curl_exec($curl));
                        $Result2 = json_decode($Result2);

                        $sagToken = $Result2->token;
                        $sagbrand = $Result2->sourceOfFunds->provided->card->brand;
                        $sagexpiry = $Result2->sourceOfFunds->provided->card->expiry;
                        $sagfundingMethod = $Result2->sourceOfFunds->provided->card->fundingMethod;
                        $sagnumber = $Result2->sourceOfFunds->provided->card->number;
                        $sagscheme = $Result2->sourceOfFunds->provided->card->scheme;

                        $canRegTC=false;


                        $MaxRows = 10;
                        $OrderedItem = 0;
                        $SkeepRows = 0;

                        $rules = [];
                        array_push($rules, array("field" => "usuario_tarjetacredito.usuario_id", "data" => $TransaccionProducto->getUsuarioId(), "op" => "eq"));
                        array_push($rules, array("field" => "usuario_tarjetacredito.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_tarjetacredito.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));


                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $UsuarioTarjetacredito = new UsuarioTarjetacredito();

                        $bancos = $UsuarioTarjetacredito->getUsuarioTarjetasCustom("usuario_tarjetacredito.*", "usuario_tarjetacredito.usutarjetacredito_id", "asc", $SkeepRows, $MaxRows, $json, true);

                        $bancos = json_decode($bancos);

                        if(oldCount($bancos)<=3){
                            $canRegTC=true;
                        }

                        if($canRegTC) {


                            $UsuarioTarjetacredito = new UsuarioTarjetacredito();

                            $UsuarioTarjetacredito->setUsuarioId($TransaccionProducto->getUsuarioId());
                            $UsuarioTarjetacredito->setProveedorId($Proveedor->getProveedorId());
                            $UsuarioTarjetacredito->setCuenta($sagnumber);
                            $UsuarioTarjetacredito->setCvv('');
                            $UsuarioTarjetacredito->setFechaExpiracion(('2000-01-01 00:00:00'));
                            $UsuarioTarjetacredito->setToken($ConfigurationEnvironment->encrypt($sagToken));
                            $UsuarioTarjetacredito->setEstado('A');
                            $UsuarioTarjetacredito->setUsucreaId('0');
                            $UsuarioTarjetacredito->setUsumodifId('0');
                            $UsuarioTarjetacredito->setDescripcion($sagbrand);

                            $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();
                            $UsuarioTarjetacreditoMySqlDAO->insert($UsuarioTarjetacredito);
                            $UsuarioTarjetacreditoMySqlDAO->getTransaction()->commit();
                        }

                        // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                        $estado = 'A';

                        // Comentario personalizado para el log
                        $comentario = 'Aprobada por Sagicor ';


                        // Tipo que genera el log (A: Automatico, M: Manual)
                        $tipo_genera = 'A';


                        // Obtenemos la transaccion


                        $TransaccionProducto->setAprobada($TransaccionProducto->transproductoId, $tipo_genera, $estado, $comentario, json_encode($Result), $Result->authorizationResponse->transactionIdentifier);

                        $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/correcto';

                         header('Location: ' . $redirect);

                    } else {
                        $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';

                         header('Location: ' . $redirect);

                    }
                } else {
                    $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';

                     header('Location: ' . $redirect);

                }

            }

        } else {


            if ($TransaccionProducto->getUsuarioId() != '') {
                $Usuario = new Usuario($TransaccionProducto->getUsuarioId());

                $Mandante = new Mandante($Usuario->mandante);

                $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';

                header('Location: ' . $redirect);


            }
        }


    } else {


        $reponseData = $_REQUEST["response"];
        $reponse = $_REQUEST["response"];

        $reponse = json_decode($reponse);

        if ($reponse != "") {
            if ($reponse->flwRef) {
                $txRef = $reponse->txRef;
                $orderRef = $reponse->orderRef;

                $chargeResponseCode = $reponse->chargeResponseCode;

                $TransaccionProducto = new TransaccionProducto($txRef);


                $Usuario = new Usuario($TransaccionProducto->getUsuarioId());

                $Mandante = new Mandante($Usuario->mandante);

                $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';

                header('Location: ' . $redirect);


                /*if ($TransaccionProducto->getEstado() != "A") {

                    $Usuario = new Usuario($TransaccionProducto->getUsuarioId());

                    $Mandante = new Mandante($Usuario->mandante);

                    $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';
                    header('Location: ' . $redirect);

                } else {
                    if ($chargeResponseCode == '00' || $chargeResponseCode == '0') {
                        $TransaccionProducto->setAprobada($txRef, "A", "A", 'Aprobada por el proveedor', $reponseData, $orderRef);

                        if ($TransaccionProducto->getUsuarioId() != '') {
                            $Usuario = new Usuario($TransaccionProducto->getUsuarioId());

                            $Mandante = new Mandante($Usuario->mandante);

                            $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/correcto';

                            //header('Location: ' . $redirect );


                        }

                    } else {
                        $TransaccionProducto->setRechazada($txRef, "A", "R", 'Aprobada por el proveedor', $reponseData, $orderRef);

                        if ($TransaccionProducto->getUsuarioId() != '') {
                            $Usuario = new Usuario($TransaccionProducto->getUsuarioId());

                            $Mandante = new Mandante($Usuario->mandante);

                            $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';

                            header('Location: ' . $redirect );


                        }

                    }
                }*/


            }


        } else {
            $reponseData = $_REQUEST["resp"];
            $resp = $_REQUEST["resp"];

            $resp = json_decode($resp);


            $data = $resp->tx;

            if ($data->flwRef) {
                $txRef = $data->txRef;
                $orderRef = $data->id;

                $chargeResponseCode = $data->chargeResponseCode;

                $TransaccionProducto = new TransaccionProducto($txRef);


                $Usuario = new Usuario($TransaccionProducto->getUsuarioId());

                $Mandante = new Mandante($Usuario->mandante);

                $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';

                header('Location: ' . $redirect);


                /*if ($TransaccionProducto->getEstado() != "A") {

                    $Usuario = new Usuario($TransaccionProducto->getUsuarioId());

                    $Mandante = new Mandante($Usuario->mandante);

                    $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';
                    header('Location: ' . $redirect);

                } else {

                    if ($chargeResponseCode == '00' || $chargeResponseCode == '0') {

                        $TransaccionProducto->setAprobada($txRef, "A", "A", 'Aprobada por el proveedor', $reponseData, $orderRef);

                        if ($TransaccionProducto->getUsuarioId() != '') {
                            $Usuario = new Usuario($TransaccionProducto->getUsuarioId());

                            $Mandante = new Mandante($Usuario->mandante);

                            $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/correcto';

                            header('Location: ' . $redirect );


                        }

                    } else {
                        $TransaccionProducto->setRechazada($txRef, "A", "R", 'Aprobada por el proveedor', $reponseData, $orderRef);

                        if ($TransaccionProducto->getUsuarioId() != '') {
                            $Usuario = new Usuario($TransaccionProducto->getUsuarioId());

                            $Mandante = new Mandante($Usuario->mandante);

                            $Mandante->baseUrl = 'https://mobile.justbetja.com/'; $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';

                            header('Location: ' . $redirect );


                        }

                    }

                }*/
            }

        }

    }

}catch (Exception $e){

}
