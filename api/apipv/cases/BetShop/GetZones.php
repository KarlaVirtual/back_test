<?php

use Backend\dto\Clasificador;


 /**
 * Obtiene una lista de zonas basándose en los filtros proporcionados.
 *
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - Data (array): Información de las zonas, incluyendo:
 *                             - id (int): Identificador de la zona.
 *                             - value (string): Descripción de la zona.
 */


/* Se crea un clasificador y se establecen parámetros para procesar datos. */
$Clasificador = new Clasificador();

$OrderedItem = 1;
$SkeepRows = 0;
$MaxRows = 10000;

$rules = [];


/* Se construye un filtro en formato JSON para obtener clasificadores personalizados. */
array_push($rules, array("field" => "clasificador.tipo", "data" => "TZ", "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$clasificadores = $Clasificador->getClasificadoresCustom("clasificador.*", "clasificador.clasificador_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* Convierte datos JSON en un array estructurado con identificadores y descripciones. */
$clasificadores = json_decode($clasificadores);
$final = [];

foreach ($clasificadores->data as $key => $value) {

    $array = [];


    $array["id"] = $value->{"clasificador.clasificador_id"};
    $array["value"] = $value->{"clasificador.descripcion"};

    array_push($final, $array);


}



/* Configuración de respuesta sin errores, tipo de alerta exitosa y datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;
