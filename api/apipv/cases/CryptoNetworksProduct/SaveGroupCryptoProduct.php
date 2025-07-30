<?php

use Backend\dto\AuditoriaGeneral;
use Backend\dto\CriptoRed;
use Backend\dto\CriptoredProdmandante;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\CriptoredProdmandanteMySqlDAO;


/**
 * Este producto permite crear la asociacion de un producto a una criptored
 *
 * @param int $productId Identificador del producto asociado al país y al partnert
 * @param int $cryptoNetworskId Identificador de la criptored
 * @param string $state Estado de la asociación (activo o inactivo).
 */

$productId = $params->productPartnerCountryId;
$cryptoNetworskId = $params->cryptoNetworksId;
$state = $params->State;
$ProviderId = $params->ProviderId;


/*Instanciamos el nombre de la clase y obtenemos el estado de la criptored*/
$Criptored = new CriptoRed($cryptoNetworskId);
$EstadoCriptored = $Criptored->getEstado();


//Validamos que el estado de la criptored sea activo en caso de que no lo sea lanzamos una excepcion

if($EstadoCriptored != "A"){
    throw new Exception("Criptored no se encuentra activa, no se puede asociar el producto",300044);
}

//------------------------------------------------------ Validacion de si la criptored ya tiene asociado el producto  ----------------------------------------------


/*Definimos la condiciones del filtrado y posteriormente realizamos una consulta para verificar si la criptored ya se encuentra asociada al producto*/

$rules = [];

array_push($rules,array("field"=>"criptored_prodmandante.criptored_id","data"=>$cryptoNetworskId,"op"=>"eq"));
array_push($rules,array("field"=>"criptored_prodmandante.prodmandante_id","data"=>$productId,"op"=>"eq"));

$rules = json_encode(array("rules" => $rules, "groupOp" => "AND"));

//Instanciamos la clase CriptoredProdmandante y obtenemos los datos filtrados
$CriptoredProdmandante1 = new CriptoredProdmandante();
$datos = $CriptoredProdmandante1->getCriptoProdmandanteCustom("criptored_prodmandante.*","criptored_prodmandante.criptored_prodmandante_id","desc",0,10,$rules,true);

//Verificamos si la consulta ha devuelto datos
$datos = json_decode($datos, true);

$count = $datos['count'][0]['.count'];

//Si el conteo es mayor a 1, significa que el producto ya se encuentra asociado a la criptored
if($count >= 1){
    throw new Exception("El producto ya se encuentra asociado a la criptored",300045);
}


//---------------------------------------------------------------------SECCION DE ASOCIACION ----------------------------------------------------------------



/*Se realiza instancia de la clase CriptoredProdmandante*/
$criptoredProdmandante = new CriptoredProdmandante();

//Se guardan los valores de la criptored, producto, estado y usuario que crea y  usuario que modifica
$criptoredProdmandante->setCriptoredId($cryptoNetworskId);
$criptoredProdmandante->setProdmandanteId($productId);
$criptoredProdmandante->setEstado($state);
$criptoredProdmandante->setUsucreaId($_SESSION['usuario']);
$criptoredProdmandante->setUsumodifId($_SESSION['usuario']);

//Iniciamos la transaccion
$criptoredProdmandanteMySqlDAO = new CriptoredProdmandanteMySqlDAO();
$id=$criptoredProdmandanteMySqlDAO->insert($criptoredProdmandante);
$criptoredProdmandanteMySqlDAO->getTransaction()->commit();



/*Obtenemos la direccion ip del operador que realiza la asociacion*/
$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];


/*Se crea una instancia de AuditoriaGeneral para registrar la asociacion del producto a la criptored y dejar un registro de logs*/

$AuditoriaGeneral = new AuditoriaGeneral();
$AuditoriaGeneral->setUsuarioId($_SESSION['usuario']);
$AuditoriaGeneral->setUsuarioIp($ip);
$AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
$AuditoriaGeneral->setUsuariosolicitaIp($ip);
$AuditoriaGeneral->setUsuarioaprobarIp(0);
$AuditoriaGeneral->setTipo("ASOCIACIONPRODUCTOACRIPTOMONEDA");
$AuditoriaGeneral->setValorAntes("");
$AuditoriaGeneral->setValorDespues("El usuario que asocio el producto es: " . $_SESSION["usuario"] . " con el proveedor" . $ProviderId . " y el producto " . $productId . " a la criptored " . $cryptoNetworskId);
$AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
$AuditoriaGeneral->setUsumodifId(0);
$AuditoriaGeneral->setEstado("A");
$AuditoriaGeneral->setDispositivo(0);
$AuditoriaGeneral->setObservacion(json_encode($params));

$AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
$AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
$AuditoriaGeneralMySqlDAO->getTransaction()->commit();

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "producto asociado correctamente a la criptored";
$response["ModelErrors"] = [];