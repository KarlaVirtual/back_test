<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\LealtadDetalle;
use Backend\dto\LealtadInterna;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;


/**
 * Procesa datos JSON de entrada para generar un dashboard de sorteos.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->ResultToDate Fecha final del rango de resultados.
 * @param string $params->ResultFromDate Fecha inicial del rango de resultados.
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonos.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param string $params->Country País para filtrar resultados.
 * @param int $params->Limit Límite de filas a obtener.
 * @param int $params->Offset Número de filas a omitir.
 * @param int $params->OrderedItem Elemento para ordenar los resultados.
 *
 * @return array $response Respuesta generada con:
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (e.g., "success").
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Lista de errores del modelo.
 *  - array $Result Resultado procesado con datos de lealtad activa.
 *  - array $Data Datos procesados, idénticos a $Result.
 */

/* analiza datos JSON entrantes y extrae parámetros específicos para su uso. */
$params = file_get_contents('php://input');
$params = json_decode($params);
$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;
$PlayerExternalId = $params->PlayerExternalId;


/* Se obtienen parámetros para filtrar y paginar resultados en una consulta. */
$Country = $params->Country;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;


$rules = [];


/* Agrega reglas a un arreglo si la variable $Country no está vacía. */
if ($Country != "") {
    array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_detalle.valor", "data" => "$Country", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));

} else {
    /* Agrega una regla al array si la condición anterior no se cumple. */

    //array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
}


/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Asigna un valor predeterminado a $MaxRows si está vacío y cuenta filas de sorteos. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}


$select = "count(sorteo_interno.sorteo_id) as cant_activos
        ";


// Si el usuario esta condicionado por el mandante y no es de Global

/* agrega reglas basadas en la sesión actual del usuario. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}

/* Genera un filtro en JSON para obtener sorteos personalizados de una base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$SorteoInterno = new SorteoInterno();
$SorteoDetalle = new SorteoDetalle();

$data = $SorteoInterno->getSorteosCustom($select, "sorteo_interno.sorteo_id", "asc", $SkeepRows, $MaxRows, $json, TRUE);


/* decodifica JSON y extrae el primer elemento de "data" en un array. */
$data = json_decode($data);

$value = $data->data[0];

$final = [];
$final["ActiveLoyalty"] = [];

/* asigna datos de lealtad y configura una respuesta sin errores. */
$final["ActiveLoyalty"]["Total"] = $value->{".cant_activos"};

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];


/* Asigna el valor de $final a dos claves en el array $response. */
$response["Result"] = $final;
$response["Data"] = $final;
