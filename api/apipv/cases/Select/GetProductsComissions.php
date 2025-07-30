<?php

use Backend\dto\Clasificador;

/**
 * Select/GetProductsComissions
 * 
 * Obtiene la lista de tipos de comisiones de productos disponibles
 *
 * @return array {
 *   "HasError": boolean,     // Indica si hubo error
 *   "AlertType": string,     // Tipo de alerta (success, warning, error)
 *   "AlertMessage": string,  // Mensaje descriptivo
 *   "ModelErrors": array,    // Lista de errores del modelo
 *   "Data": array[{         // Lista de comisiones
 *     "id": int,           // ID de la comisión
 *     "value": string      // Descripción de la comisión
 *   }]
 * }
 *
 * @throws Exception        // Errores de procesamiento
 */
// Inicializa el objeto Clasificador para acceder a los tipos de comisiones
$Clasificador = new Clasificador();

// Configura los parámetros de paginación
$OrderedItem = 1;
$SkeepRows = 0;
$MaxRows = 10000;

// Configura las reglas de filtrado para obtener solo registros de tipo PCOM
$rules = [];
array_push($rules, array("field" => "clasificador.tipo", "data" => "PCOM", "op" => "eq"));

// Prepara el filtro JSON para la consulta
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

// Obtiene los tipos de comisiones según el filtro y los convierte a array
$clasificadores = $Clasificador->getClasificadoresCustom("clasificador.*", "clasificador.clasificador_id", "asc", $SkeepRows, $MaxRows, $json, true);
$clasificadores = json_decode($clasificadores);
$final = [];

// Procesa cada tipo de comisión y formatea los datos para la respuesta
foreach ($clasificadores->data as $key => $value) {
    $array = [];
    $array["id"] = $value->{"clasificador.clasificador_id"};
    $array["value"] = $value->{"clasificador.descripcion"};
    array_push($final, $array);
}

// Prepara la respuesta final con los tipos de comisiones formateados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Data"] = $final;
