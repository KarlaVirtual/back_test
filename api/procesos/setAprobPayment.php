<?php
/**
* Establecer el pago aprovado
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* @date 13.12.18
* 
*/
use Backend\dto\BonoInterno;
use Backend\dto\Usuario;
use Backend\integrations\payment\SafetyPay;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\dto\TransaccionProducto;

error_reporting(E_ALL);

ini_set("display_errors","ON");

require_once __DIR__ . '../../vendor/autoload.php';

$safetypay=json_decode($_REQUEST["safetypay"]);
print_r($safetypay);
if($safetypay->CreationDateTime != ""){
    $SafetyPay = new SafetyPay();
    $SafetyPay->setCreationDateTime($safetypay->CreationDateTime);
    $SafetyPay->setMerchantSalesID($safetypay->MerchantSalesID);
    $SafetyPay->setMerchantOrderID($safetypay->MerchantOrderID);
    $SafetyPay->setOperationID($safetypay->OperationID);
    $SafetyPay->setOperationStatus(102);

    $SafetyPay ->confirmation();

}

$pagoefectivo=json_decode($_REQUEST["pagoefectivo"]);
print_r($pagoefectivo);
if($pagoefectivo->pagada != ""){
    $TransaccionProducto = new TransaccionProducto($pagoefectivo->pagada);

    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
    $estado = 'A';

    // Comentario personalizado para el log
    $comentario = 'Aprobada por PagoEfectivo ';
    $tipo_genera = 'A';
        $OrdenID = $pagoefectivo->orden;

    print_r($TransaccionProducto -> setAprobada($pagoefectivo->pagada,$tipo_genera,$estado,$comentario,$t_value,$OrdenID));

}
