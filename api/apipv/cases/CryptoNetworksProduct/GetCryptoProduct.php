<?php
use Backend\dto\CriptoredProdmandante;


/**
 * Este recurso permite obtener las asociaciones de productos a criptoredes activas.
 *
 * @param string $State Estado actual del producto de criptomonedas (activo o inactivo).
 * @param int $CryptoNetworkId Identificador único de la red de criptomonedas.
 * @param int $CryptoCurrencyId Identificador único de la criptomoneda.
 * @param string $CryptoCurrencyName Nombre de la criptomoneda.
 * @param int $ProviderId Identificador único del proveedor asociado.
 * @param int $CountrySelect Identificador del país seleccionado.
 */

$State = $_REQUEST["State"];
$CryptoNetworkId = $_REQUEST["CryptoNetworkId"];
$CryptoCurrencyId = $_REQUEST["cryptoCurrencyId"];
$CryptoCurrencyName = $_REQUEST["CryptoCurrencyName"];
$ProviderId = $_REQUEST["providerId"];
$CountrySelect = $_REQUEST["CountrySelect"];



/**
 * Definimos las reglas de filtrado para obtener las asociaciones de productos con criptoredes.
 * Estas reglas se utilizan para construir los criterios de búsqueda en la base de datos.
 *
 * - Filtramos por estado del producto (activo o inactivo).
 * - Filtramos por país seleccionado.
 * - Filtramos por identificador único de la red de criptomonedas, criptomoneda y proveedor, si están definidos.
 */
$rules = [];

if($State == "A"){
    array_push($rules,array("field"=>"criptored_prodmandante.estado","data"=>"A","op"=>"eq"));
}else if ($State == "I"){
    array_push($rules,array("field"=>"criptored_prodmandante.estado","data"=>"I","op"=>"eq"));
}

array_push($rules,array("field"=>"pais.pais_id","data"=>$CountrySelect,"op"=>"eq"));

if($CryptoNetworkId != ""){
    array_push($rules,array("field"=>"cripto_red.red_blockchain_id","data"=>$CryptoNetworkId,"op"=>"eq"));
}

if($CryptoCurrencyId != ""){
    array_push($rules,array("field"=>"cripto_red.criptomoneda_id","data"=>$CryptoCurrencyId,"op"=>"eq"));
}

if($ProviderId != ""){
    array_push($rules,array("field"=>"proveedor.proveedor_id","data"=>$ProviderId,"op"=>"eq"));
}


/**
 * Se instancia la clase `CriptoredProdmandante` para interactuar con los datos de asociaciones de productos con criptomonedas.
 * Se pasan los filtros definidos previamente en formato JSON para realizar la consulta.
 * Se obtienen los datos de las asociaciones desde la base de datos utilizando el método `getCriptoProdmandanteCustom`.
 * Finalmente, los datos obtenidos se decodifican desde formato JSON para su posterior uso.
 */


$rules = json_encode(array("rules" => $rules, "groupOp" => "AND"));


$CriptoProveedorProdMandante = new CriptoredProdmandante();
$datos = $CriptoProveedorProdMandante->getCriptoProdmandanteCustom("criptored_prodmandante.*,proveedor.descripcion,criptomoneda.nombre,criptomoneda.criptomoneda_id,red_blockchain.nombre,red_blockchain.codigo_red,pais.pais_nom,mandante.descripcion,proveedor.proveedor_id,red_blockchain.redblockchain_id,producto.descripcion","criptored_prodmandante.criptored_prodmandante_id","desc",0,10,$rules,true);

$datos = json_decode($datos);

/**
 * @var string $Id Identificador único de la asociación del producto a la criptored.
 * @var int $CryptoNetworkId Identificador único de la red de criptomonedas asociada.
 * @var string $nameProvider Nombre del proveedor asociado al producto.
 * @var string $cryptoCurrencyName Nombre de la criptomoneda asociada.
 * @var string $networkName Nombre de la red blockchain asociada.
 * @var string $networkCode Código único de la red blockchain.
 * @var string $partnerCountry País y mandante asociados al producto.
 * @var int $providerId Identificador único del proveedor.
 * @var string $State Estado actual de la asociación (activo o inactivo).
 * @var int $networkBlockchainId Identificador único de la red blockchain.
 * @var int $cryptoCurrencyId Identificador único de la criptomoneda.
 * @var string $productName Nombre del producto asociado.
 * @var string $nameCryptoNetwork Nombre combinado de la criptomoneda y la red blockchain.
 */

$final = [];
foreach ($datos->data as $key => $value){
    $array = [];
    $array["Id"] = $value->{"criptored_prodmandante.criptored_prodmandante_id"};
    $array["CryptoNetworkId"] = $value->{"criptored_prodmandante.criptored_id"};
    $array["nameProvider"] = $value->{"proveedor.descripcion"};
    $array["cryptoCurrencyName"] = $value->{"criptomoneda.nombre"};
    $array["networkName"] = $value->{"red_blockchain.nombre"};
    $array["networkCode"] = $value->{"red_blockchain.codigo_red"};
    $array["partnerCountry"] = $value->{"pais.pais_nom"} . " - " . $value->{"mandante.descripcion"};
    $array["providerId"] = $value->{"proveedor.proveedor_id"};
    $array["State"] = $value->{"criptored_prodmandante.estado"};
    $array["networkBlockchainId"] = $value->{"red_blockchain.redblockchain_id"};
    $array["cryptoCurrencyId"] = $value->{"criptomoneda.criptomoneda_id"};
    $array["productName"] = $value->{"producto.descripcion"};
    $array["nameCryptoNetwork"] = $value->{"criptomoneda.nombre"} . " - " . $value->{"red_blockchain.nombre"};


    array_push($final, $array);

}


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["data"] = $final;