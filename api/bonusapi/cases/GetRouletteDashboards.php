<?php
/**
 * Este script procesa una solicitud HTTP para obtener estadísticas generales de ruletas.
 * 
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->ResultToDate Fecha y hora de fin de los resultados.
 * @param string $params->ResultFromDate Fecha y hora de inicio de los resultados.
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonificación.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param int $params->Limit Número máximo de filas a obtener.
 * @param string $params->OrderedItem Campo por el cual ordenar los resultados.
 * @param int $params->Offset Número de filas a omitir.
 * 
 * @return array $response Respuesta estructurada que contiene:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ("success", "error", etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores de modelo.
 *  - Result (array): Estadísticas generales de ruletas, incluyendo:
 *      - ActiveRoulettes (array): Total de ruletas activas.
 *  - Data (array): Datos de las estadísticas.
 */

use Backend\dto\RuletaInterno;


/* obtiene y decodifica datos JSON enviados a través de una solicitud HTTP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;

/* asigna valores de parámetros a variables y prepara un arreglo vacío. */
$PlayerExternalId = $params->PlayerExternalId;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;


$rules = [];

// Si el usuario esta condicionado por País

/* Condición para agregar reglas según el país y estado global del usuario. */
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "ruleta_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


/* Código configura un filtro y ajusta variables para manejo de datos y ordenación. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* asigna un valor por defecto a $MaxRows y convierte $filtro a JSON. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}


$json = json_encode($filtro);


/* suma registros activos y los obtiene desde la base de datos. */
$select = "SUM(CASE WHEN ruleta_interno.estado =  'A' THEN 1 ELSE 0 END) cant_activos
        ";


$RuletaInterno = new RuletaInterno();
$data = $RuletaInterno->getRuletasCustom($select, "ruleta_interno.ruleta_id", "asc", $SkeepRows, $MaxRows, $json, false);


/* decodifica JSON, extrae un valor y inicializa un arreglo vacío. */
$data = json_decode($data);

$value = $data->data[0];

$final = [];
$final["ActiveRoulettes"] = [];

/* Asignación de valores a un arreglo y respuesta sin errores en un sistema. */
$final["ActiveRoulettes"]["Total"] = $value->{".cant_activos"};

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];


/* asigna el valor de `$final` a dos claves en el array `$response`. */
$response["Result"] = $final;
$response["Data"] = $final;
