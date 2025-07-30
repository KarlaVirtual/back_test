<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\BonoInterno;

/**
 * GetBonusDashboards
 * 
 * Obtiene estadísticas y métricas del dashboard de bonos
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
 *   "Data": array {
 *     "ActiveBonus": array {    // Estadísticas de bonos activos
 *       "Total": int           // Total de bonos activos
 *     }
 *   }
 * }
 *
 * @throws Exception           // Errores de procesamiento
 */

// Obtiene y decodifica los parámetros de entrada desde el request
$params = file_get_contents('php://input');
$params = json_decode($params);

// Extrae los parámetros de filtrado de fechas y bonos
$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;
$PlayerExternalId = $params->PlayerExternalId;

// Configura los parámetros de paginación
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

// Inicializa el array de reglas y construye el filtro
$rules = [];
$filtro = array("rules" => $rules, "groupOp" => "AND");

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

// Prepara la consulta y sus parámetros
$json = json_encode($filtro);

$select = "SUM(CASE WHEN bono_interno.estado =  'A' THEN 1 ELSE 0 END) cant_activos
        ";

// Ejecuta la consulta personalizada de bonos
$BonoInterno = new BonoInterno();
$data = $BonoInterno->getBonosCustom($select, "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);

// Procesa los resultados de la consulta
$data = json_decode($data);
$value = $data->data[0];

// Construye la estructura de respuesta con las estadísticas
$final = [];
$final["ActiveBonus"] = [];
$final["ActiveBonus"]["Total"] = $value->{".cant_activos"};

// Configura los metadatos de la respuesta
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

// Asigna los resultados a la respuesta
$response["Result"] = $final;
$response["Data"] = $final;
