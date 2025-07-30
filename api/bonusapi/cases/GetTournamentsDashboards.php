<?php
/**
 * Este script maneja la obtención de datos de torneos activos con filtros personalizados.
 * 
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param string $params->ResultToDate Fecha de fin de los resultados.
 * @param string $params->ResultFromDate Fecha de inicio de los resultados.
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonos.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param int $params->Limit Límite de filas a obtener.
 * @param int $params->OrderedItem Elemento por el cual se ordenarán los resultados.
 * @param int $params->Offset Número de páginas a omitir.
 * 
 * @return array $response Arreglo que contiene:
 * - HasError: Indica si ocurrió un error (true/false).
 * - AlertType: Tipo de alerta (success).
 * - AlertMessage: Mensaje de alerta.
 * - ModelErrors: Lista de errores del modelo.
 * - Result: Datos de torneos activos.
 * - Data: Datos de torneos activos.
 */

/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\TorneoInterno;


/* obtiene y decodifica datos JSON desde la entrada, extrayendo fechas y IDs. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;

/* Se obtienen parámetros de entrada para definir la paginación y orden de los datos. */
$PlayerExternalId = $params->PlayerExternalId;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;


$rules = [];

/* Añade reglas a un arreglo según condiciones de sesión del usuario. */
array_push($rules, array("field" => "torneo_interno.estado", "data" => 'A', "op" => "eq"));

// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "torneo_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


/* Se configura un filtro en base a reglas y opciones de omisión y orden. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece $MaxRows a un valor predeterminado y codifica $filtro en formato JSON. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}


$json = json_encode($filtro);


/* suma torneos activos y los recupera con paginación personalizada. */
$select = "SUM(CASE WHEN torneo_interno.estado =  'A' THEN 1 ELSE 0 END) cant_activos
        ";


$TorneoInterno = new TorneoInterno();
$data = $TorneoInterno->getTorneosCustom($select, "torneo_interno.torneo_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* decodifica JSON, accede a un valor y crea un array para torneos activos. */
$data = json_decode($data);

$value = $data->data[0];

/* Asigna total de torneos activos y configura respuesta sin errores. */
$final = [];
$final["ActiveTournaments"] = [];
$final["ActiveTournaments"]["Total"] = $value->{".cant_activos"};

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];


/* Asigna el valor de `$final` a las claves "Result" y "Data" del array `$response`. */
$response["Result"] = $final;
$response["Data"] = $final;
