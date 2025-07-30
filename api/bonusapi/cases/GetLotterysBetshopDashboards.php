<?php

use Backend\dto\SorteoDetalle2;
use Backend\dto\SorteoInterno2;

/**
 * Procesa datos JSON de entrada para generar un dashboard de sorteos en betshops.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $ResultToDate Fecha final del rango de resultados.
 * @param string $ResultFromDate Fecha inicial del rango de resultados.
 * @param array $BonusDefinitionIds IDs de definiciones de bonos.
 * @param string $PlayerExternalId ID externo del jugador.
 * @param string $Country País para filtrar resultados.
 * @param int $Limit Límite de filas a obtener.
 * @param int $Offset Número de filas a omitir.
 * @param int $OrderedItem Elemento para ordenar los resultados.
 * 
 *
 * @return array $response Respuesta generada con:
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (e.g., "success").
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Lista de errores del modelo.
 *  - array $Result Resultado procesado con datos de lealtad activa.
 *  - array $Data Datos procesados, idénticos a $Result.
 */

/* obtiene y decodifica datos JSON de entrada en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);
$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;
$PlayerExternalId = $params->PlayerExternalId;

/* Se extraen parámetros para paginación y ordenamiento de un conjunto de datos. */
$Country = $params->Country;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$rules = [];

/* Agrega reglas condicionales basadas en el país si está especificado. */
if ($Country != "") {
    array_push($rules, array("field" => "sorteo_detalle2.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_detalle2.valor", "data" => "$Country", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno2.estado", "data" => "A", "op" => "eq"));

} else {
    /* Agrega una regla para verificar el estado de "sorteo_interno2". */

    //array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno2.estado", "data" => "A", "op" => "eq"));
}

/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un valor predeterminado y cuenta registros en una base de datos. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$select = "count(sorteo_interno2.sorteo2_id) as cant_activos";

// Si el usuario esta condicionado por el mandante y no es de Global

/* Agrega reglas basadas en condiciones de sesión para un sorteo interno. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "sorteo_interno2.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "sorteo_interno2.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}

/* Se crea un filtro JSON para aplicar reglas en un sorteo interno. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$SorteoInterno = new SorteoInterno2();
$SorteoDetalle = new SorteoDetalle2();

/* Se obtienen sorteos, se decodifica JSON y se prepara un arreglo final. */
$data = $SorteoInterno->getSorteos2Custom($select, "sorteo_interno2.sorteo2_id", "asc", $SkeepRows, $MaxRows, $json, TRUE);

$data = json_decode($data);

$value = $data->data[0];

$final = [];

/* inicializa una estructura de datos y configura una respuesta sin errores. */
$final["ActiveLoyalty"] = [];
$final["ActiveLoyalty"]["Total"] = $value->{".cant_activos"};

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* Se inicializan errores y se asignan resultados a una respuesta en PHP. */
$response["ModelErrors"] = [];

$response["Result"] = $final;
$response["Data"] = $final;

?>