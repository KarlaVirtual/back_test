<?php

use Backend\dto\Clasificador;

 /**
 * Obtiene una lista de tipos de establecimientos basándose en los filtros y parámetros proporcionados.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - Data (array): Información de los tipos de establecimientos, incluyendo:
 *                             - id (int): Identificador del clasificador.
 *                             - value (string): Descripción del clasificador.
 */

/* Se crea un clasificador y se establecen parámetros para procesar datos. */
$Clasificador = new Clasificador();

$OrderedItem = 1;
$SkeepRows = 0;
$MaxRows = 10000;

$rules = [];


/* Se construye un filtro JSON para obtener clasificadores personalizados en un arreglo. */
array_push($rules, array("field" => "clasificador.tipo", "data" => "TE", "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$clasificadores = $Clasificador->getClasificadoresCustom("clasificador.*", "clasificador.clasificador_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* convierte datos JSON en un arreglo PHP con clasificadores específicos. */
$clasificadores = json_decode($clasificadores);
$final = [];

foreach ($clasificadores->data as $key => $value) {

    $array = [];


    $array["id"] = $value->{"clasificador.clasificador_id"};
    $array["value"] = $value->{"clasificador.descripcion"};

    array_push($final, $array);


}



/* inicializa un arreglo de respuesta con éxito y datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;
