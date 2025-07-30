<?php
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioMandante;
use Backend\integrations\payment\TOTALPAGOSERVICES;

/**
 * Administra los casos de solicitud de depósito por transferencia bancaria a nombre de diversos proveedores.
 *@param int $json->params->productId Producto ID
 *@param int $json->params->type Tipo de operación
 *
 * @return array
 *  - code (int) Código de respuesta
 *  - rid (int) ID de respuesta
 *  - data (array) información adicional
 */

// Inicializa la respuesta
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""
);


/*El código inicializa objetos de usuario, producto y proveedor a partir de los parámetros recibidos en un JSON,
y luego realiza una acción específica basada en el tipo de proveedor y el tipo de operación solicitada.*/
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$productId =$json->params->productId;
$type =$json->params->type;

$Producto = new Producto($productId);
$Proveedor = new Proveedor($Producto->proveedorId);

/*El código realiza una acción específica basada en el tipo de proveedor y el tipo de operación solicitada, utilizando un switch para manejar
 el proveedor TOTALPAGO y ejecutando diferentes métodos según el tipo de operación (getBank o deposit).*/
switch ($Proveedor->getAbreviado()) {
    case 'TOTALPAGO':

        if ($type == "getBank") {
            $TOTALPAGOSERVICES = new TOTALPAGOSERVICES();
            $data = $TOTALPAGOSERVICES->getBank($Producto, $Usuario);
        } elseif ($type == "deposit") {
            $amount =$json->params->amount;
            $bankId =$json->params->bankId;
            $date =$json->params->date;
            $reference =$json->params->reference;
            $TOTALPAGOSERVICES = new TOTALPAGOSERVICES();
            $data = $TOTALPAGOSERVICES->createRequestPayment($Usuario, $Producto, $amount, $bankId, $date, $reference);
        }

        break;
}

// Decodifica el JSON recibido
$data = json_decode($data);

if($data->success == "true"){
    //Declaración de respuesta exitosa
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => $data->code
    );
}else{
    //Declaración de respuesta fallida
    $response = array();
    $response["code"] = 1;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => $data->Message
    );
}

