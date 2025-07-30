<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\LealtadDetalle;
use Backend\dto\LealtadInterna;

/**
 * Este script genera un resumen de lealtades activas basado en los datos de lealtad
 * de los usuarios y aplica filtros según el país y otros parámetros.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes parámetros:
 * @param string $params->ResultToDate Fecha de fin de resultados.
 * @param string $params->ResultFromDate Fecha de inicio de resultados.
 * @param array $params->BonusDefinitionIds Identificadores de definiciones de bonificación.
 * @param string $params->PlayerExternalId Identificador externo del jugador.
 * @param string $params->Country País asociado a la consulta.
 * @param int $params->Limit Límite de filas a consultar.
 * @param int $params->Offset Desplazamiento para la consulta.
 * @param int $params->OrderedItem Elemento por el cual ordenar.
 * 
 *
 * @return array $response Respuesta estructurada con los siguientes elementos:
 *  - HasError: Indica si ocurrió un error (false si no hay errores).
 *  - AlertType: Tipo de alerta (success, error, etc.).
 *  - AlertMessage: Mensaje de alerta.
 *  - ModelErrors: Lista de errores de validación.
 *  - Result: Resumen de lealtades activas.
 *  - Data: Resumen de lealtades activas.
 */

/* obtiene y decodifica datos JSON de una solicitud HTTP en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);
$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;
$PlayerExternalId = $params->PlayerExternalId;


/* asigna parámetros para paginación y ordenación de elementos en un país. */
$Country = $params->Country;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;


$rules = [];


/* Condiciona la adición de reglas en un arreglo según si $Country está vacío. */
if ($Country != "") {
    array_push($rules, array("field" => "lealtad_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
    array_push($rules, array("field" => "lealtad_detalle.valor", "data" => "$Country", "op" => "eq"));

} else {
    array_push($rules, array("field" => "lealtad_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
}


/* asigna valores predeterminados a variables si están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* asigna un valor predeterminado a $MaxRows y prepara una consulta SQL. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}


$select = "count(lealtad_detalle.lealtad_id) as cant_activos
        ";


/*// Si el usuario esta condicionado por el mandante y no es de Global
if ($Country != "") {
    array_push($rules, array("field" => "lealtad_interna.mandante", "data" => $Country, "op" => "eq"));
}else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "lealtad_interna.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}*/

/* Se crea un filtro JSON y se obtiene información de lealtades personalizadas. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$LealtadInterna = new LealtadInterna();
$LealtadDetalle = new LealtadDetalle();
$data = $LealtadDetalle->getLealtadDetallesCustom($select, "lealtad_interna.lealtad_id", "desc", $SkeepRows, $MaxRows, $json, TRUE);


/* decodifica JSON y asigna un valor específico a un array final. */
$data = json_decode($data);

$value = $data->data[0];

$final = [];
$final["ActiveLoyalty"] = [];

/* Asigna el total de lealtad activa y configura la respuesta sin errores. */
$final["ActiveLoyalty"]["Total"] = $value->{".cant_activos"};

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];


/* Asigna el valor de $final a las claves "Result" y "Data" del arreglo $response. */
$response["Result"] = $final;
$response["Data"] = $final;
