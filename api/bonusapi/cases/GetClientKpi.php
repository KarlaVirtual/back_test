<?php
/**
 * Este script gestiona la obtención de indicadores clave de rendimiento (KPI) de un cliente.
 * 
 * @param object $params Contiene los parámetros de entrada en formato JSON:
 * @param string $params->ToDateLocal Fecha final del rango de consulta.
 * @param string $params->FromDateLocal Fecha inicial del rango de consulta.
 * @param int $params->MaxRows Número máximo de filas a devolver (por defecto 1000000).
 * @param int $params->OrderedItem Orden de los elementos en la consulta (por defecto 1).
 * @param int $params->SkeepRows Número de filas a omitir en la consulta (por defecto 0).
 * @param int $params->id Identificador del usuario.
 * 
 * @return array $response Respuesta estructurada que incluye:
 * - HasError: Indica si ocurrió un error (boolean).
 * - AlertType: Tipo de alerta (string).
 * - AlertMessage: Mensaje de la operación (string).
 * - ModelErrors: Lista de errores de modelo (array).
 * - Data: Datos relacionados con apuestas deportivas y KPI (array).
 */

use Backend\dto\ItTicketEnc;

/* crea un objeto y obtiene parámetros JSON de una solicitud HTTP. */
$ItTicketEnc = new ItTicketEnc();

$params = file_get_contents('php://input');
$params = json_decode($params);

$id = $_GET["id"];

/* Asigna valores de parámetros a variables para gestionar fechas y registros. */
$ToDateLocal = $params->ToDateLocal;
$FromDateLocal = $params->FromDateLocal;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

/* inicializa variables si están vacías; asigna valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

/* recupera datos de tickets filtrando por usuario y límites configurados. */
if ($MaxRows == "") {
    $MaxRows = 1000000;
}

$json = '{"rules" : [{"field" : "it_ticket_enc.usuario_id", "data" : "' . $id . '","op":"eq"}] ,"groupOp" : "AND"}';

$tickets = $ItTicketEnc->getTicketsCustom(" COUNT(*) count,SUM(it_ticket_enc.vlr_apuesta) apuestas,SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios, SUM(CASE WHEN it_ticket_enc.estado = 'A' THEN 1 ELSE 0 END) count_sin,SUM(CASE WHEN it_ticket_enc.estado = 'A' THEN it_ticket_enc.vlr_apuesta ELSE 0 END) apuestas_sin  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true);

/* convierte tickets JSON a objeto y configura la respuesta sin errores. */
$tickets = json_decode($tickets);

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

/* Asigna datos relacionados con apuestas deportivas a un array de respuestas. */
$response["Data"] = array(
    "LastSportBetTimeLocal" => "",
    "TotalSportBets" => ($tickets->data[0]->{".count"}),
    "TotalUnsettledBets" => ($tickets->data[0]->{".count_sin"}),
    "TotalSportStakes" => ($tickets->data[0]->{".apuestas"}),
    "TotalUnsettledStakes" => ($tickets->data[0]->{".apuestas_sin"}),
    "TotalSportWinnings" => ($tickets->data[0]->{".premios"}),
    "SportProfitness" => (($tickets->data[0]->{".apuestas"}) / ($tickets->data[0]->{".premios"})),
);
