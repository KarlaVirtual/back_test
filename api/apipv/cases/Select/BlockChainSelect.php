<?php


use Backend\dto\RedBlockchain;

/**
 * Select/FranchiseSelect
 *
 * Obtiene la lista de RedBlockchains para mostrar en un select
 *
 * @param string $params->Filter  Texto para filtrar RedBlockchains por descripción
 *
 * @return array  El objeto $response es un array con los siguientes atributos:
 *   "HasError" (boolean)      Indica si hubo error
 *   "AlertType" (string)      Tipo de alerta (success, warning, error)
 *   "AlertMessage" (string)   Mensaje descriptivo
 *   "Data" (array)            Datos de respuesta
 *     "Objects" (array)       Lista de RedBlockchains
 *       "id" (int)            ID del RedBlockchain
 *       "value" (string)      Descripción del RedBlockchain
 *     "Count" (int)           Total de registros
 *
 * @throws Exception no
 */

// Inicialización de variables y objeto RedBlockchain
/*error_reporting(E_ALL);
ini_set('display_errors', 'ON');*/
$RedBlockchain = new RedBlockchain();
$keyword = $params->Filter;

$OrderedItem = 1;
$SkeepRows = 0;
$MaxRows = 10000;

// Configuración de reglas de filtrado
$rules = [];

if ($keyword != "" & $keyword != null) {
    // Agrega reglas de filtro por descripción y estado activo
    array_push($rules, array("field" => "red_blockchain.nombre", "data" => $keyword, "op" => "cn"));
    //array_push($rules, array("field" => "red_blockchain.estado", "data" => "A", "op" => "eq"));


    // Construcción del filtro JSON y consulta de red_blockchains
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $RedBlockchains = $RedBlockchain->getRedBlockchainsCustom("red_blockchain.redblockchain_id, red_blockchain.nombre", "red_blockchain.redblockchain_id", "asc", $SkeepRows, $MaxRows, $json, true);

    // Procesamiento de resultados
    $RedBlockchains = json_decode($RedBlockchains);
    $final = [];

    // Construcción del array de respuesta con los datos de RedBlockchains
    foreach ($RedBlockchains->data as $key => $value) {
        $array = [];
        $array["id"] = $value->{"red_blockchain.redblockchain_id"};
        $array["value"] = $value->{"red_blockchain.nombre"};
        array_push($final, $array);
    }
}

// Configuración de la respuesta final
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

// Asignación de datos a la respuesta
$response["Data"] = $final;
$response["pos"] = $SkeepRows;
$response["total_count"] = $RedBlockchains->count[0]->{".count"};
$response["data"] = $final;

