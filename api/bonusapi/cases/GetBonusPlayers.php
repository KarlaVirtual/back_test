<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\PromocionalLog;

/**
 * GetBonusPlayers
 * 
 * Obtiene la lista de jugadores que han recibido bonos según los filtros especificados
 *
 * @param object $params {
 *   "ResultToDate": string,      // Fecha final en formato "YYYY-MM-DD HH:mm:ss"
 *   "ResultFromDate": string,    // Fecha inicial en formato "YYYY-MM-DD HH:mm:ss"
 *   "BonusDefinitionIds": array, // IDs de definiciones de bonos a filtrar
 *   "PlayerExternalId": string,  // ID externo del jugador
 *   "Limit": int,               // Número máximo de registros
 *   "OrderedItem": string,      // Campo de ordenamiento
 *   "Offset": int               // Número de página
 * }
 * 
 * @return array {
 *   "HasError": boolean,        // Indica si hubo error
 *   "AlertType": string,        // Tipo de alerta (success/danger)
 *   "AlertMessage": string,     // Mensaje descriptivo
 *   "ModelErrors": array,       // Errores del modelo
 *   "Count": int,              // Total de registros
 *   "Result": array [          // Lista de jugadores con bonos
 *     {
 *       "Id": int,            // ID del registro
 *       "PlayerExternalId": string, // ID externo del jugador
 *       "Amount": float,      // Monto total del bono
 *       "AmountBase": float,  // Monto base del bono
 *       "AmountBonus": float  // Monto promocional del bono
 *     }
 *   ]
 * }
 *
 * @throws Exception           // Errores de procesamiento
 */
// Obtiene y decodifica los parámetros de entrada desde el request
$params = file_get_contents('php://input');
$params = json_decode($params);

// Extrae los parámetros de filtrado de fechas, bonos y jugador
$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;
$PlayerExternalId = $params->PlayerExternalId;

// Configura los parámetros de paginación
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

// Inicializa el array de reglas y prepara el string para IDs de bonos
$rules = [];
$string = "";

// Concatena los IDs de bonos para el filtro
foreach ($BonusDefinitionIds as $key => $value) {
    $string = $string . $value . ",";
}

// Agrega reglas de filtrado por fecha final si está definida
if ($ToDateLocal != "") {
    array_push($rules, array("field" => "promocional_log.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
}
// Agrega reglas de filtrado por fecha inicial si está definida
if ($FromDateLocal != "") {
    array_push($rules, array("field" => "promocional_log.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
}

// Agrega reglas de filtrado por IDs de bonos si están definidos
if ($BonusDefinitionIds != "") {
    array_push($rules, array("field" => "promocional_log.promocional_id", "data" => "" . $string . "0", "op" => "in"));
}

// Agrega reglas de filtrado por ID de jugador si está definido
if ($PlayerExternalId != "") {
    array_push($rules, array("field" => "promocional_log.usuario_id", "data" => $PlayerExternalId, "op" => "eq"));
}

// Verifica condiciones de acceso por país y mandante
// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "bono_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

// Construye el objeto de filtro y establece valores por defecto para la paginación
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 30;
}

// Prepara la consulta y crea la instancia del objeto de acceso a datos
$json = json_encode($filtro);

$PromocionalLog = new PromocionalLog();

// Ejecuta la consulta para obtener los registros de bonos según los filtros
$bonos = $PromocionalLog->getPromocionalLogsCustom(" promocional_log.* ", "promocional_log.promolog_id", "asc", $SkeepRows, $MaxRows, $json, true);

$bonos = json_decode($bonos);

// Prepara el array para almacenar los resultados procesados
$final = [];

// Procesa cada registro de bono y construye la estructura de respuesta
foreach ($bonos->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"promocional_log.promolog_id"};
    $array["PlayerExternalId"] = $value->{"promocional_log.usuario_id"};
    $array["Amount"] = $value->{"promocional_log.valor"};
    $array["AmountBase"] = $value->{"promocional_log.valor_base"};
    $array["AmountBonus"] = $value->{"promocional_log.valor_promocional"};

    // Agrega el registro procesado al array final
    array_push($final, $array);
}

// Construye y devuelve la respuesta final
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;
$response["Count"] = $bonos->count[0]->{".count"};
