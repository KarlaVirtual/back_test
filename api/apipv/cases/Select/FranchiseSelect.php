<?php


use Backend\dto\Franquicia;


/**
 * Select/FranchiseSelect
 *
 * Obtiene la lista de Franquicias para mostrar en un select
 *
 * @param object $params {
 *   "Filter": string      // Texto para filtrar Franquicias por descripción
 * }
 *
 * @return array {
 *   "HasError": boolean,     // Indica si hubo error
 *   "AlertType": string,     // Tipo de alerta (success, warning, error)
 *   "AlertMessage": string,  // Mensaje descriptivo
 *   "Data": array {         // Datos de respuesta
 *     "Objects": array[{    // Lista de Franquicias
 *       "id": int,         // ID del Franquicia
 *       "value": string    // Descripción del Franquicia
 *     }],
 *     "Count": int         // Total de registros
 *   }
 * }
 *
 * @throws Exception        // Errores de procesamiento
 */

// Inicialización de variables y objeto Franquicia
/*error_reporting(E_ALL);
ini_set('display_errors', 'ON');*/
$Franquicia = new Franquicia();
$keyword = $params->Filter;

$OrderedItem = 1;
$SkeepRows = 0;
$MaxRows = 10000;

// Configuración de reglas de filtrado
$rules = [];

if ($keyword != "" & $keyword != null) {
    // Agrega reglas de filtro por descripción y estado activo
    array_push($rules, array("field" => "franquicia.descripcion", "data" => $keyword, "op" => "cn"));
    array_push($rules, array("field" => "franquicia.estado", "data" => "A", "op" => "eq"));


    // Construcción del filtro JSON y consulta de Franquicias
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $Franquicias = $Franquicia->getFranquiciasCustom("franquicia.franquicia_id, franquicia.descripcion", "franquicia.franquicia_id", "asc", $SkeepRows, $MaxRows, $json, true);

    // Procesamiento de resultados
    $Franquicias = json_decode($Franquicias);
    $final = [];

    // Construcción del array de respuesta con los datos de Franquicias
    foreach ($Franquicias->data as $key => $value) {
        $array = [];
        $array["id"] = $value->{"franquicia.franquicia_id"};
        $array["value"] = $value->{"franquicia.descripcion"};
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
$response["total_count"] = $Franquicias->count[0]->{".count"};
$response["data"] = $final;
