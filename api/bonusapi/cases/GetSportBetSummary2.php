<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\ItTicketEnc;

/**
 * Este script procesa datos JSON de entrada para obtener un resumen de apuestas deportivas.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->FromDateLocal Fecha de inicio para filtrar apuestas.
 * @param string $params->ToDateLocal Fecha de fin para filtrar apuestas.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Orden de los elementos.
 * @param int $params->SkeepRows Número de filas a omitir.
 *
 * @return array $response Respuesta estructurada con los datos solicitados, incluyendo:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (por ejemplo, "success").
 * - AlertMessage (string): Mensaje de la operación.
 * - ModelErrors (array): Lista de errores de modelo, si los hay.
 * - Data (array): Datos obtenidos, incluyendo:
 *   - BetAmount (int): Monto total apostado.
 *   - WinningAmount (int): Monto total ganado.
 *   - BetCount (int): Número total de apuestas.
 */

/* crea un objeto y decodifica datos JSON de una entrada PHP. */
$ItTicketEnc = new ItTicketEnc();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ToDateLocal;

/* asigna valores de parámetros y maneja la lógica de filas. */
$FromDateLocal = $params->FromDateLocal;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Inicializa $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}


/* Construye un filtro JSON para obtener estadísticas de tickets en la base de datos. */
$json = '{"rules" : [{"field" : "it_ticket_enc.estado", "data" : "I","op":"eq"},{"field" : " CONCAT(it_ticket_enc.fecha_cierre,\' - \',it_ticket_enc.hora_cierre) ", "data": "' . $FromDateLocal . '","op":"ge"},{"field" : "CONCAT(it_ticket_enc.fecha_cierre,\' - \',it_ticket_enc.hora_cierre)", "data": "' . $ToDateLocal . '","op":"le"}] ,"groupOp" : "AND"}';

$tickets = $ItTicketEnc->getTicketsCustom(" COUNT(*) count,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true);

$tickets = json_decode($tickets);

$response["HasError"] = false;

/* crea una respuesta estructurada con datos sobre apuestas y premios. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "BetAmount" => intval($tickets->data[0]->{".apuestas"}),
    "WinningAmount" => intval($tickets->data[0]->{".premios"}),
    "BetCount" => intval($tickets->data[0]->{".count"}),

);