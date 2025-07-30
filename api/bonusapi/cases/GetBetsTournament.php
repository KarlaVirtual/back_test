<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\ItTicketEncInfo1;
use Backend\dto\TorneoInterno;
use Backend\dto\TransjuegoInfo;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioTorneo;

/**
 * GetBetsTournament
 * 
 * Obtiene el listado de apuestas realizadas por un jugador en un torneo específico
 *
 * @param object $params {
 *   "ResultToDate": string,      // Fecha final en formato "YYYY-MM-DD HH:mm:ss"
 *   "ResultFromDate": string,    // Fecha inicial en formato "YYYY-MM-DD HH:mm:ss"
 *   "BonusDefinitionIds": array, // IDs de definiciones de bonos a filtrar
 *   "PlayerExternalId": string,  // ID externo del jugador
 *   "Limit": int,               // Número máximo de registros a retornar
 *   "OrderedItem": string,      // Campo por el cual ordenar
 *   "Offset": int,              // Número de página para paginación
 *   "search": string,           // Término de búsqueda
 *   "Id": int,                  // ID del torneo
 *   "draw": int,                // Número de petición para DataTables
 *   "length": int,              // Registros por página para DataTables
 *   "start": int,               // Registro inicial para DataTables
 * }
 *
 * @return array {
 *   "HasError": boolean,        // Indica si hubo error
 *   "AlertType": string,        // Tipo de alerta (success/danger)
 *   "AlertMessage": string,     // Mensaje descriptivo
 *   "ModelErrors": array,       // Errores del modelo
 *   "Data": array[{            // Lista de apuestas del torneo
 *     "TicketId": string,      // ID del ticket
 *     "DateCreate": string,    // Fecha de creación
 *     "Amount": float,         // Monto apostado
 *     "Status": string,        // Estado de la apuesta
 *     "Result": float,         // Resultado/ganancia
 *     "Points": int,          // Puntos obtenidos en el torneo
 *     "Position": int         // Posición en el torneo
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

// Obtiene parámetros de búsqueda y paginación
$search = $params->search;
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

// Obtiene el ID del torneo desde diferentes fuentes
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

// Inicializa objetos necesarios para procesar el torneo
$UsuarioTorneo = new UsuarioTorneo($Id);
$TorneoInterno = new TorneoInterno($UsuarioTorneo->torneoId);
$UsuarioMandante = new UsuarioMandante($UsuarioTorneo->usuarioId);

// Establece valores por defecto para la paginación
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

// Procesa torneos de tipo 1 (apuestas)
if ($TorneoInterno->tipo == 1) {
    $rules = [];

    // Configura las reglas de filtrado para búsqueda y torneo
    if ($search->value != "") {
        array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => $search->value, "op" => "eq"));
    }
    array_push($rules, array("field" => "it_ticket_enc_info1.valor", "data" => $UsuarioTorneo->usutorneoId, "op" => "eq"));
    array_push($rules, array("field" => "it_ticket_enc_info1.tipo", "data" => "TORNEO", "op" => "eq"));

    // Ejecuta la consulta de tickets para el torneo
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);
    $ItTicketEncInfo1 = new ItTicketEncInfo1();
    $tickets = $ItTicketEncInfo1->getTicketsCustom(" it_ticket_enc_info1.*,it_ticket_enc.vlr_apuesta,it_ticket_enc.fecha_cierre,it_ticket_enc.fecha_crea ", "it_ticket_enc_info1.it_ticket2_id", "asc", $SkeepRows, $MaxRows, $json2, true);
    $tickets = json_decode($tickets);

    // Procesa los resultados de tickets
    $final = [];
    foreach ($tickets->data as $key => $value) {
        $array = [];
        $array["Id"] = ($value->{"it_ticket_enc_info1.it_ticket2_id"});
        $array["Type"] = 0;
        $array["TicketId"] = ($value->{"it_ticket_enc_info1.ticket_id"});
        $array["Valor"] = ($value->{"it_ticket_enc_info1.valor"});
        $array["Amount"] = ($value->{"it_ticket_enc_info1.valor2"});
        $array["AmountBase"] = ($value->{"it_ticket_enc.vlr_apuesta"});
        $array["DateCreate"] = ($value->{"it_ticket_enc.fecha_crea"});
        $array["DateClose"] = ($value->{"it_ticket_enc.fecha_cierre"});

        array_push($final, $array);
    }

    $json = json_encode($filtro);
} elseif ($TorneoInterno->tipo == 2) {// Procesa torneos de tipo 2 (transacciones)
    $rules = [];
    array_push($rules, array("field" => "transaccion_api.usuario_id", "data" => $UsuarioMandante->usumandanteId, "op" => "eq"));
    array_push($rules, array("field" => "transjuego_info.descripcion", "data" => $UsuarioTorneo->usutorneoId, "op" => "eq"));
    array_push($rules, array("field" => "transjuego_info.tipo", "data" => "TORNEO", "op" => "eq"));

    // Ejecuta la consulta de transacciones para el torneo
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);
    $TransjuegoInfo = new TransjuegoInfo();
    $tickets = $TransjuegoInfo->getTransjuegoInfosCustom(" transjuego_info.*,transaccion_api.valor,transaccion_api.identificador ", "transjuego_info.transjuegoinfo_id", "asc", $SkeepRows, $MaxRows, $json2, true, '');
    $tickets = json_decode($tickets);

    // Procesa los resultados de transacciones
    $final = [];
    foreach ($tickets->data as $key => $value) {
        $array = [];
        $array["Id"] = ($value->{"transjuego_info.transjuegoinfo_id"});
        $array["Type"] = 1;
        $array["TicketId"] = ($value->{"transaccion_api.identificador"});
        $array["Valor"] = ($value->{"transjuego_info.valor"});
        $array["Amount"] = ($value->{"transjuego_info.valor"});
        $array["AmountBase"] = ($value->{"transaccion_api.valor"});
        $array["DateCreate"] = ($value->{"transjuego_info.fecha_crea"});
        $array["DateClose"] = ($value->{"transjuego_info.fecha_crea"});

        array_push($final, $array);
    }
}

// Prepara la respuesta final con los resultados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;
$response["Data"] = $final;
$response["Count"] = $tickets->count[0]->{".count"};
