<?php

use Backend\dto\CriptoRed;
use Backend\mysql\CriptoRedMySqlDAO;
use Backend\dto\RedBlockChain;

 /**
 * Obtiene los parámetros de la solicitud HTTP para realizar una consulta sobre criptomonedas y blockchains.
 * @param string $cryptocurrency     ID de la criptomoneda.
 * @param string $criptoBlockchainId ID de la blockchain asociada.
 * @param string $state              Estado de la asociación entre criptomoneda y blockchain (A: Activo, I: Inactivo).
 * @param int   $MaxRows             Número máximo de filas a devolver (por defecto 100).
 */


$RedBlockchainId = $_REQUEST['BlockchainIdNetwork'];
$cryptocurrency = $_REQUEST['CryptoCurrencyId'];
$state = $_REQUEST['State'];
 $MaxRows = $_REQUEST['maxRows'];

if ($MaxRows == "" || $MaxRows == 0) {
    $MaxRows = 100;
}

 /**
 * Construcción de las reglas de filtrado para la consulta.
 *
 * @var array $rules Reglas de filtrado para la consulta.
 */

$rules = [];


if ($cryptocurrency != "") {
    array_push($rules, array("field" => "cripto_red.criptomoneda_id", "data" => $cryptocurrency, "op" => "eq"));
}

if ($RedBlockchainId != "") {
    array_push($rules, array("field" => "red_blockchain.redblockchain_id", "data" => $RedBlockchainId, "op" => "eq"));
}

if ($state != "" && $state == "A") {
    array_push($rules, array("field" => "cripto_red.estado", "data" => "A", "op" => "eq"));
} else if ($state != "" && $state == "I") {
    array_push($rules, array("field" => "cripto_red.estado", "data" => "I", "op" => "eq"));
}

/**
 * Convierte las reglas de filtrado en formato JSON para su uso en la consulta.
*/

$rules = json_encode(array("rules" => $rules, "groupOp" => "AND"));

 /**
 * Realiza la consulta personalizada a la base de datos.
 *
 * @var CriptoRed $CriptoRed Instancia del objeto Criptored.
 * @var array     $datos     Resultado de la consulta.
 */

$CriptoRed = new CriptoRed();
$datos = $CriptoRed->getCriptoRedCustom("cripto_red.*,red_blockchain.nombre,red_blockchain.redblockchain_id,red_blockchain.codigo_red,criptomoneda.nombre", "cripto_red.criptored_id", "asc", 0, 100, $rules, true);

$datos = json_decode($datos);

 /**
 * Procesa los datos obtenidos y los estructura para la respuesta.
 *
 */


$final = [];

foreach ($datos->data as $key => $value) {
    $array = [];
    $array["Id"] = $value->{"cripto_red.criptored_id"};
    $array["NameCryptoCurrency"] = $value->{"criptomoneda.nombre"};
    $array["CryptoCurrencyId"] = $value->{"cripto_red.criptomoneda_id"};
    $array["NameBlockchainNetwork"] = $value->{"red_blockchain.nombre"};
    $array["RedBlockchainCode"] = $value->{"red_blockchain.codigo_red"};
    $array["State"] = $value->{"cripto_red.estado"};
    $array["BlockchainIdNetwork"] = $value->{"red_blockchain.redblockchain_id"};


    array_push($final, $array);
}

 /**
 * Construye la respuesta final para el cliente.
 *
 */


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["data"] = $final;