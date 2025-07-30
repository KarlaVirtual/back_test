<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioBono;
use Backend\mysql\UsuarioBonoMySqlDAO;

/**
 * GetBonusDetailDashboards
 * 
 * Obtiene estadísticas detalladas de un bono específico incluyendo valores y cantidades por estado
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
 * @param int $_REQUEST['IdBonus']     // ID del bono a consultar
 * @param bool $_REQUEST['IsDeposit']  // Indica si se incluyen estadísticas de depósitos
 * 
 * @return array {
 *   "HasError": boolean,        // Indica si hubo error
 *   "AlertType": string,        // Tipo de alerta (success/danger)
 *   "AlertMessage": string,     // Mensaje descriptivo
 *   "ModelErrors": array,       // Errores del modelo
 *   "Count": int,              // Total de registros
 *   "Data": array {            // Estadísticas del bono
 *     "RedeemedAmount": float, // Valor total de bonos redimidos
 *     "ActiveAmount": float,   // Valor total de bonos activos
 *     "ExpiredAmount": float,  // Valor total de bonos expirados
 *     "RedeemedCount": int,    // Cantidad de bonos redimidos
 *     "ActiveCount": int,      // Cantidad de bonos activos 
 *     "ExpiredCount": int,     // Cantidad de bonos expirados
 *     "DepositAmount": float,  // Valor total de depósitos (si IsDeposit=true)
 *     "DepositCount": int      // Cantidad de depósitos (si IsDeposit=true)
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

// Obtiene el ID del bono y flag de depósito desde el request
$IdBonus = $_REQUEST["IdBonus"];
$isDeposit = $_REQUEST['IsDeposit'] == true ? true : false;

// Inicializa el array de reglas y agrega el filtro por ID de bono
$rules = [];
array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

// Construye el objeto de filtro y establece valores por defecto para la paginación
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

// Prepara la consulta y define los campos a seleccionar
$json = json_encode($filtro);

$select = "SUM(CASE WHEN usuario_bono.estado =  'R' THEN usuario_bono.valor ELSE 0 END) valor_redimidos,
        SUM(CASE WHEN usuario_bono.estado =  'A' THEN usuario_bono.valor ELSE 0 END) valor_activos,
        SUM(CASE WHEN usuario_bono.estado =  'E' THEN usuario_bono.valor ELSE 0 END) valor_expirados,
        SUM(CASE WHEN usuario_bono.estado =  'R' THEN 1 ELSE 0 END) cant_redimidos,
        SUM(CASE WHEN usuario_bono.estado =  'A' THEN 1 ELSE 0 END) cant_activos,
        SUM(CASE WHEN usuario_bono.estado =  'E' THEN 1 ELSE 0 END) cant_expirados
        ";

// Agrega campos adicionales si se requieren estadísticas de depósitos
if($isDeposit === true) $select .= ',SUM(CASE WHEN bono_interno.tipo = 2 THEN usuario_bono.valor_base ELSE 0 END) valor_depositos,
                           SUM(CASE WHEN bono_interno.tipo = 2 THEN 1 ELSE 0 END) cant_depositos';

// Ejecuta la consulta principal para obtener las estadísticas
$UsuarioBono = new UsuarioBono();
$data = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json, true, '');

$data = json_decode($data);
$value = $data->data[0];

// Prepara una nueva consulta para obtener la cantidad de bonos creados
$rules = [];
if($IdBonus != ""){
    array_push($rules,array("field"=>"usuario_bono.bono_id","data"=>$IdBonus,"op"=>"eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

$datos = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*","usuario_bono.usubono_id","desc",$SkeepRows,$MaxRows,$json2,true);
$datos = json_decode($datos);

// Calcula la cantidad total de bonos creados
$CantidadBonosCreados = $datos->count[0]->{".count"};

// Prepara una nueva consulta para obtener la cantidad de bonos asignados
$rules = [];
if($IdBonus != ""){
    array_push($rules,array("field"=>"usuario_bono.bono_id","data"=>$IdBonus,"op"=>"eq"));
}
array_push($rules,array("field"=>"usuario_bono.usuario_id","data"=>"","op"=>"ne"));
array_push($rules,array("field"=>"usuario_bono.usuario_id","data"=>0,"eq"=>"ne"));
array_push($rules,array("field"=>"usuario_bono.usuario_id","data"=>"NULL","eq"=>"ne"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

// Ejecuta la consulta para bonos asignados y calcula indicadores
$datos = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*","usuario_bono.usubono_id","desc",$SkeepRows,$MaxRows,$json2,true);
$datos = json_decode($datos);

$CantidadBonosAsignados = $datos->count[0]->{".count"};

if ($CantidadBonosCreados != 0) {
    $ActivationIndicator = ($CantidadBonosAsignados/$CantidadBonosCreados)*100;
} else {
    $ActivationIndicator = 0;
}

if((string)$ActivationIndicator === 'NAN') {
    $ActivationIndicator = 0;
}

// Prepara una nueva consulta para obtener la cantidad de bonos redimidos
$rules = [];
if($IdBonus != ""){
    array_push($rules,array("field"=>"usuario_bono.bono_id","data"=>$IdBonus,"op"=>"eq"));
}
array_push($rules,array("field"=>"usuario_bono.estado","data"=>"R","op"=>"eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

// Ejecuta la consulta y calcula indicadores de redención
$datos = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*","usuario_bono.usubono_id","desc",$SkeepRows,$MaxRows,$json2,true);
$datos = json_decode($datos);

$CantidadBonosRedimidos =  $datos->count[0]->{".count"};

if ($CantidadBonosCreados != 0) {
    $RedemptionIndicator = ($CantidadBonosRedimidos / $CantidadBonosCreados) * 100;
} else {
    $RedemptionIndicator = 0;
}

if((string)$RedemptionIndicator === 'NAN') {
    $RedemptionIndicator = 0;
}

// Construye la estructura de respuesta con todas las estadísticas
$final = [];
$final["ActiveBonus"] = [];
$final["ActiveBonus"]["Total"] = $value->{".cant_activos"};
$final["ActiveBonus"]["Amount"] = number_format($value->{".valor_activos"}, 2);
$final["RedimBonus"] = [];
$final["RedimBonus"]["Total"] = $value->{".cant_redimidos"};
$final["RedimBonus"]["Amount"] = number_format($value->{".valor_redimidos"}, 2);
$final["ExpiratedBonus"] = [];
$final["ExpiratedBonus"]["Total"] = $value->{".cant_expirados"};
$final["ExpiratedBonus"]["Amount"] = number_format($value->{".valor_expirados"}, 2);
$final["AllBonus"] = [];
$final["AllBonus"]["Total"] = $final["ActiveBonus"]["Total"] + $final["RedimBonus"]["Total"] + $final["ExpiratedBonus"]["Total"];
$final["AllBonus"]["Amount"] = $value->{".valor_activos"} + $value->{".valor_redimidos"} + $value->{".valor_expirados"};
$final["AllBonus"]["Amount"] = number_format($final["AllBonus"]["Amount"], 2);
$final['DepositBonus']['Total'] = $value->{'.cant_depositos'} !== null ? $value->{'.cant_depositos'} : 0 ;
$final['DepositBonus']['Amount'] = number_format($value->{'.valor_depositos'}, 2);
$final["Indicators"] = [];
$final["Indicators"]["ActivationIndicator"] = number_format($ActivationIndicator, 2);
$final["Indicators"]["RedemptionIndicator"] = number_format($RedemptionIndicator, 2);

// Configura la respuesta final con los resultados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Result"] = $final;
$response["Data"] = $final;