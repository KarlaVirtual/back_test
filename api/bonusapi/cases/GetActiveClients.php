<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


use Backend\dto\ItTicketEnc;

/**
 * GetActiveClients
 * 
 * Obtiene la cantidad de clientes activos que han realizado apuestas en un período
 *
 * @param object $params {
 *   "ToDateLocal": string,    // Fecha final en formato "YYYY-MM-DD HH:mm:ss"
 *   "FromDateLocal": string,  // Fecha inicial en formato "YYYY-MM-DD HH:mm:ss" 
 *   "Region": string,         // ID de la región/país a filtrar
 *   "Currency": string,       // Moneda a filtrar
 *   "MaxRows": int,          // Número máximo de filas a retornar
 *   "OrderedItem": int,      // Campo por el cual ordenar
 *   "SkeepRows": int         // Número de filas a omitir
 * }
 *
 * @return array {
 *   "HasError": boolean,      // Indica si hubo error
 *   "AlertType": string,      // Tipo de alerta (success/danger)
 *   "AlertMessage": string,   // Mensaje descriptivo
 *   "ModelErrors": array,     // Errores del modelo
 *   "Result": array {         // Resultado de la consulta
 *     "ActiveClients": int    // Número de clientes activos
 *   }
 * }
 *
 * @throws Exception         // Errores de procesamiento
 */


// Inicializa el objeto para manejar tickets
$ItTicketEnc = new ItTicketEnc();

// Obtiene y decodifica los parámetros de entrada desde el request
$params = file_get_contents('php://input');
$params = json_decode($params);

// Procesa y formatea las fechas de inicio y fin del período
$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
$Region = $params->Region;
$Currency = $params->Currency;

// Obtiene y valida los parámetros de paginación y ordenamiento
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

// Establece valores por defecto para los parámetros de paginación
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

// Construye las reglas de filtrado para la consulta
$rules = [];
array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));

// Agrega filtros adicionales por región y moneda si están especificados
if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}

if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}

// Prepara el JSON de filtros para la consulta
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

// Ejecuta la consulta para obtener el conteo de usuarios únicos
$ItTicketEnc = new ItTicketEnc();
$tickets = $ItTicketEnc->getTicketsCustom(" COUNT( DISTINCT (it_ticket_enc.usuario_id) ) count  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true);
$tickets = json_decode($tickets);

// Extrae el número total de jugadores del resultado
$NumeroJugadoresTickets = $tickets->data[0]->{".count"};

// Prepara la respuesta con el resultado exitoso
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

// Asigna el conteo de jugadores activos a la respuesta
$response["Data"] = $NumeroJugadoresTickets;
