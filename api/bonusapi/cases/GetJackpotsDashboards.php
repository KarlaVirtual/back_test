<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\LealtadDetalle;
use Backend\dto\LealtadInterna;
use Backend\dto\SorteoDetalle;
use Backend\dto\JackpotInterno;

/**
 * Obtiene los datos del dashboard de jackpots.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->ResultToDate Fecha de fin de resultados.
 * @param string $params->ResultFromDate Fecha de inicio de resultados.
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonos.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param string $params->Country País del jugador.
 * @param int $params->Limit Número máximo de filas a recuperar.
 * @param int $params->OrderedItem Elemento por el cual ordenar.
 * @param int $params->Offset Número de páginas a omitir.
 *
 * @return array $response Respuesta estructurada con:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Result (array): Resultado procesado con los datos del dashboard.
 */

/* obtiene y decodifica datos JSON de una entrada en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);
$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;
$PlayerExternalId = $params->PlayerExternalId;

/* asigna parámetros de paginación y de ordenación para datos de un país. */
$Country = $params->Country;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$rules = [];

/* Agrega reglas a un arreglo si $Country no está vacío. */
if ($Country != "") {
    array_push($rules, array("field" => "jackpot_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
    array_push($rules, array("field" => "jackpot_detalle.valor", "data" => "$Country", "op" => "eq"));
    array_push($rules, array("field" => "jackpot_interno.estado", "data" => "A", "op" => "eq"));

} else {
    /* Agrega reglas para filtrar datos según país y estado en el sistema. */

    array_push($rules, array("field" => "jackpot_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
    array_push($rules, array("field" => "jackpot_detalle.valor", "data" => $_SESSION['pais_id'], "op" => "eq"));
    array_push($rules, array("field" => "jackpot_interno.estado", "data" => "A", "op" => "eq"));
}

/* inicializa variables a valores predeterminados si están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

/* asigna un valor predeterminado a $MaxRows y define una consulta SQL. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$select = "count(jackpot_interno.jackpot_id) as cant_activos
        ";

// Si el usuario esta condicionado por el mandante y no es de Global

/* agrega reglas a un arreglo según condiciones de sesión. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "jackpot_interno.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "jackpot_interno.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}

/* Se crea un filtro JSON para obtener datos de JackpotInterno personalizadamente. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$JackpotInterno = new JackpotInterno();

$data = $JackpotInterno->getJackpotCustom($select, "jackpot_interno.jackpot_id", "asc", $SkeepRows, $MaxRows, $json, TRUE);

/* decodifica JSON, extrae un valor y prepara un array inicial. */
$data = json_decode($data);

$value = $data->data[0];

$final = [];
$final["ActiveLoyalty"] = [];

/* asigna un valor y configura la respuesta sin errores. */
$final["ActiveLoyalty"]["Total"] = $value->{".cant_activos"};

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;
$response["Data"] = $final;
