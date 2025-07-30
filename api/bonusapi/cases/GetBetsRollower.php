<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\ItTicketEncInfo1;
use Backend\dto\UsuarioBono;

/**
 * GetBetsRollower
 * 
 * Obtiene el listado de apuestas realizadas por un jugador en un período específico
 *
 * @param object $params {
 *   "ResultToDate": string,      // Fecha final en formato "YYYY-MM-DD HH:mm:ss"
 *   "ResultFromDate": string,    // Fecha inicial en formato "YYYY-MM-DD HH:mm:ss"
 *   "BonusDefinitionIds": array, // IDs de definiciones de bonos a filtrar
 *   "PlayerExternalId": string,  // ID externo del jugador
 *   "Limit": int,               // Número máximo de registros a retornar
 *   "OrderedItem": string,      // Campo por el cual ordenar
 *   "Offset": int,              // Número de página para paginación
 *   "draw": int,                // Número de petición para DataTables
 *   "length": int,              // Registros por página para DataTables
 *   "start": int,               // Registro inicial para DataTables
 *   "columns": array,           // Definición de columnas para DataTables
 *   "order": array             // Configuración de ordenamiento para DataTables
 * }
 *
 * @return array {
 *   "HasError": boolean,        // Indica si hubo error
 *   "AlertType": string,        // Tipo de alerta (success/danger)
 *   "AlertMessage": string,     // Mensaje descriptivo
 *   "ModelErrors": array,       // Errores del modelo
 *   "Data": array[{            // Lista de apuestas
 *     "TicketId": string,      // ID del ticket
 *     "DateCreate": string,    // Fecha de creación
 *     "Amount": float,         // Monto apostado
 *     "Status": string,        // Estado de la apuesta
 *     "Result": float,         // Resultado/ganancia
 *     "BonusAmount": float    // Monto del bono aplicado
 *   }],
 *   "draw": int,              // Número de petición (DataTables)
 *   "recordsTotal": int,      // Total de registros
 *   "recordsFiltered": int    // Total de registros filtrados
 * }
 *
 * @throws Exception          // Errores de procesamiento
 */


// Obtiene y decodifica los parámetros de entrada desde el request
$params = file_get_contents('php://input');
$params = json_decode($params);

// Extrae los parámetros principales de fechas, bonos y jugador
$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;
$PlayerExternalId = $params->PlayerExternalId;

// Configura los parámetros de paginación y ordenamiento
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$Id = $_REQUEST["Id"];

$OrderedItem = "it_ticket_enc_info1.it_ticket2_id";
$OrderType = "desc";

// Obtiene el ID desde diferentes fuentes
$Id = $_REQUEST["Id"];
$Id = $params->Id;

// Procesa los parámetros de DataTables
$draw = $params->draw;
$length = $params->length;
$start = $params->start;

// Ajusta la paginación según los parámetros de DataTables
if ($start != "") {
    $SkeepRows = $start;
}

if ($length != "") {
    $MaxRows = $length;
}

// Procesa las columnas y el orden especificado en DataTables
$columns = $params->columns;
$order = $params->order;

// Define el ordenamiento según la columna seleccionada
foreach ($order as $item) {
    switch ($columns[$item->column]->data) {
        case "TicketId":
            $OrderedItem = "it_ticket_enc_info1.ticket_id";
            $OrderType = $item->dir;
            break;

        case "DateCreate":
            $OrderedItem = "it_ticket_enc.fecha_crea";
            $OrderType = $item->dir;
            break;

        case "DateClose":
            $OrderedItem = "it_ticket_enc.fecha_cierre";
            $OrderType = $item->dir;
            break;

        case "Amount":
            $OrderedItem = "it_ticket_enc_info1.valor";
            $OrderType = $item->dir;
            break;
    }
}

// Inicializa el objeto de usuario bono y establece valores por defecto
$UsuarioBono = new UsuarioBono($Id);

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);

// Construye las reglas de filtrado para la consulta
$rules = [];
array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioBono->usuarioId, "op" => "eq"));
array_push($rules, array("field" => "it_ticket_enc_info1.valor", "data" => $UsuarioBono->usubonoId, "op" => "eq"));
array_push($rules, array("field" => "it_ticket_enc_info1.tipo", "data" => "ROLLOWER", "op" => "eq"));

// Prepara el filtro final y ejecuta la consulta
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

$ItTicketEncInfo1 = new ItTicketEncInfo1();

$tickets = $ItTicketEncInfo1->getTicketsCustom(" it_ticket_enc_info1.*,it_ticket_enc.vlr_apuesta,it_ticket_enc.fecha_cierre,it_ticket_enc.fecha_crea ", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json2, true);

$tickets = json_decode($tickets);

// Procesa los resultados y construye el array de respuesta
$final = [];

foreach ($tickets->data as $key => $value) {
    $array = [];

    $array["Id"] = ($value->{"it_ticket_enc_info1.it_ticket2_id"});
    $array["TicketId"] = ($value->{"it_ticket_enc_info1.ticket_id"});
    $array["Valor"] = ($value->{"it_ticket_enc_info1.valor"});
    $array["Amount"] = ($value->{"it_ticket_enc_info1.valor2"});

    if($array["Amount"]  =='' || $array["Amount"] =='0'){
        $array["Amount"] = ($value->{"it_ticket_enc.vlr_apuesta"});
    }
    $array["DateCreate"] = ($value->{"it_ticket_enc.fecha_crea"});
    $array["DateClose"] = ($value->{"it_ticket_enc.fecha_cierre"});

    array_push($final, $array);
}

// Prepara la respuesta final con los resultados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;
$response["Data"] = $final;
$response["Count"] = $tickets->count[0]->{".count"};
