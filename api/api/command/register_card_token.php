<?php

use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\Proveedor;
use Backend\dto\Producto;

$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""
);

$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

$numTarjeta = $json->params->num_tarjeta;
$expiry_month = $json->params->expiry_month;
$expiry_year = $json->params->expiry_year;

$cvv = $json->params->cvv;
$valor = $json->params->amount;
$productId = $json->params->productId;
$saveCard = $json->params->saveCard;
$requestId = $json->params->requestId;
$referenceId = $json->params->referenceId;
$method = $json->params->method;
$transactionToken = $json->params->token;

$numTarjeta = str_replace(' ', '', $numTarjeta);
$datos = $json->params;

$Producto = new Producto($productId);
$Proveedor = new Proveedor($Producto->proveedorId);

if (true) {
    if ($Proveedor != null) {
        switch ($Proveedor->getAbreviado()) {

            case 'PUSHPAYMENT':

                $Producto = new Producto($productId);
                $Proveedor = new Proveedor($Producto->proveedorId);
                $PUSHPAYMENTSERVICES = new Backend\integrations\payout\PUSHPAYMENTSERVICES();

                if ($method == 'auth'){
                    $data = $PUSHPAYMENTSERVICES->Auth($Usuario, $Producto);
                }else{
                    $data = $PUSHPAYMENTSERVICES->AddCard($Usuario, $Producto, $numTarjeta, $UsuarioMandante->getNombres(), $expiry_month, $expiry_year, $cvv, $Proveedor->getProveedorId(),$transactionToken);
                }

                break;
        }
    } else {
        // Manejar el caso donde $Proveedor es nulo
        throw new Exception("Inusual Detected", "100001");
    }
}

if ($data->success == "true") {
    $response = array();
    $response["code"] = 0;
    if ($method == 'auth'){
        $response["sessiontoken"] = $data->sessionKey;
        $response["dataInfo"] = $data->dataInfo;
    }else{
        $response["cardNumber"] = $data->cardNumber;
    }
} else {
    $response = array();
    $response["code"] = 1;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => $data->errorDescription
    );
}







