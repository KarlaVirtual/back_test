<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\ItTicketEnc;

/**
 * Este script procesa datos JSON de entrada para obtener un resumen de apuestas deportivas,
 * con soporte para conversión de moneda y filtrado por región.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->FromDateLocal Fecha de inicio para filtrar apuestas.
 * @param string $params->ToDateLocal Fecha de fin para filtrar apuestas.
 * @param string $params->Region Región para filtrar apuestas.
 * @param string $params->Currency Moneda para filtrar apuestas.
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
 *   - BetAmount (int): Monto total apostado (convertido a EUR si aplica).
 *   - WinningAmount (int): Monto total ganado (convertido a EUR si aplica).
 *   - BetCount (int): Número total de apuestas.
 */

/* procesa datos JSON y ajusta una fecha local. */
$ItTicketEnc = new ItTicketEnc();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));

/* procesa fechas y parámetros de entrada para realizar operaciones específicas. */
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
$Region = $params->Region;
$Currency = $params->Currency;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* ajusta valores predeterminados para variables si están vacías. */
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un valor predeterminado y agrega reglas a un array. */
if ($MaxRows == "") {
    $MaxRows = 10;
}

$rules = [];
array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));

/* Se añaden reglas de filtrado para fechas y región en un array. */
array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));

if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}


/* Condiciona filtros de moneda y los convierte a formato JSON. */
if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


/* obtiene y procesa datos de boletos según la región, almacenando resultados. */
if ($Region != "") {

    $tickets = $ItTicketEnc->getTicketsCustom(" COUNT(*) count,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $tickets = json_decode($tickets);

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "BetAmount" => intval($tickets->data[0]->{".apuestas"}),
        "WinningAmount" => intval($tickets->data[0]->{".premios"}),
        "BetCount" => intval($tickets->data[0]->{".count"}),

    );

} else {

    /* Código que obtiene y procesa datos de tickets para convertir apuestas y premios. */
    $tickets = $ItTicketEnc->getTicketsCustom(" usuario.moneda,COUNT(*) count,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

    $tickets = json_decode($tickets);

    $valor_convertido_apuestas = 0;
    $valor_convertido_premios = 0;

    /* Calcula el total de apuestas y premios en euros a partir de los tickets. */
    $total = 0;
    foreach ($tickets->data as $key => $value) {

        $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".apuestas"}, 0));
        $valor_convertido_apuestas = $valor_convertido_apuestas + $converted_currency;
        $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".premios"}, 0));
        $valor_convertido_premios = $valor_convertido_premios + $converted_currency;

        $total = $total + $value->{".count"};

    }


    /* Se estructura una respuesta exitosa con datos sobre apuestas y premios. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "BetAmount" => intval($valor_convertido_apuestas),
        "WinningAmount" => intval($valor_convertido_premios),
        "BetCount" => $total,

    );
}