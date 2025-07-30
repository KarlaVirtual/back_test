<?php

use Backend\dto\Clasificador;

/**
 * Select/GetTypesAdjustments
 * 
 * Obtiene la lista de tipos de ajustes disponibles en el sistema
 *
 * @return array {
 *   "HasError": boolean,     // Indica si hubo error
 *   "AlertType": string,     // Tipo de alerta (success, warning, error)
 *   "AlertMessage": string,  // Mensaje descriptivo
 *   "ModelErrors": array,    // Lista de errores del modelo
 *   "Data": array[{         // Lista de tipos de ajustes
 *     "id": int,           // ID del tipo de ajuste
 *     "value": string      // Descripción del tipo de ajuste
 *   }]
 * }
 *
 * @throws Exception        // Errores de procesamiento
 */
// Inicializa el objeto Clasificador para acceder a la base de datos
$Clasificador = new Clasificador();

// Define parámetros de paginación y ordenamiento
$OrderedItem = 1;
$SkeepRows = 0; 
$MaxRows = 10000;

// Configura las reglas de filtrado para obtener solo clasificadores de tipo TA (Tipos de Ajuste)
$rules = [];
array_push($rules, array("field" => "clasificador.tipo", "data" => "TA", "op" => "eq"));

// Construye el filtro JSON con las reglas definidas
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

// Ejecuta la consulta para obtener los clasificadores según los criterios especificados
$clasificadores = $Clasificador->getClasificadoresCustom("clasificador.*", "clasificador.clasificador_id", "asc", $SkeepRows, $MaxRows, $json, true);

// Decodifica el resultado JSON y prepara el array final
$clasificadores = json_decode($clasificadores);
$final = [];

// Procesa cada clasificador y formatea los datos para la respuesta
foreach ($clasificadores->data as $key => $value) {
    $array = [];
    $array["id"] = $value->{"clasificador.clasificador_id"};
    $array["value"] = $value->{"clasificador.descripcion"};
    array_push($final, $array);
}

// Prepara la respuesta final con los tipos de ajustes encontrados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Data"] = $final;
