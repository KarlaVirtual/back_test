<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioBono;

/**
 * Obtiene la lista de jugadores con bonos según los filtros especificados
 * 
 * Este recurso permite consultar los bonos asignados a jugadores con diferentes filtros
 * como fechas, estados, códigos y paginación.
 * 
 * @param object $params Objeto con los siguientes parámetros:
 * @param string $params->ResultToDate Fecha final para filtrar resultados
 * @param string $params->ResultFromDate Fecha inicial para filtrar resultados
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonos
 * @param string $params->PlayerExternalId ID externo del jugador
 * @param string $params->Code Código del bono
 * @param string $params->ExternalId ID externo
 * @param int $params->ResultTypeId Tipo de resultado (1:Activo, 2:Inactivo, 3:Expirado, 4:Redimido)
 * @param int $params->Id ID del bono
 * @param string $params->BeginDate Fecha inicial de la campaña
 * @param string $params->EndDate Fecha final de la campaña
 * @param string $params->BeginDateRedemption Fecha inicial de redención
 * @param string $params->EndDateRedemption Fecha final de redención
 * @param int $params->Limit Límite de registros por página
 * @param int $params->Offset Número de registros a saltar
 * @param int $params->draw Número de dibujo para DataTables
 * @param int $params->length Longitud de la página
 * @param int $params->start Registro inicial
 * @param array $params->columns Columnas para ordenamiento
 * @param array $params->order Configuración de ordenamiento
 * 
 * @return object Estructura de respuesta con el siguiente formato:
 * {
 *   "HasError": boolean,
 *   "AlertType": string,
 *   "AlertMessage": string,
 *   "ModelErrors": array,
 *   "Result": array, // Lista de bonos con la siguiente estructura:
 *   [
 *     {
 *       "Id": int,
 *       "PlayerExternalId": string,
 *       "PlayerName": string,
 *       "Amount": float,
 *       "AmountBase": float,
 *       "AmountBonus": float,
 *       "Code": string,
 *       "AmountToWager": float,
 *       "WageredAmount": float,
 *       "Date": string,
 *       "ExternalId": string,
 *       "DateRedemption": string, // Solo si el estado es 'R'
 *       "ResultTypeId": int // 1:Activo, 2:Inactivo, 3:Expirado, 4:Redimido, 5:Pendiente, 6:Liquidado
 *     }
 *   ],
 *   "Data": array, // Mismo contenido que Result
 *   "Count": int // Total de registros encontrados
 * }
 */

 
$params = file_get_contents('php://input');
$params = json_decode($params);

// Obtiene los parámetros de filtrado desde la solicitud JSON
$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
//$EndDate =str_replace(" ", "T", $params->EndDate) ;
//$BeginDate =str_replace(" ", "T", $params->BeginDate);

$BonusDefinitionIds = $params->BonusDefinitionIds;
$PlayerExternalId = $params->PlayerExternalId;
$Code = $params->Code;
$ExternalId = $params->ExternalId;
$ResultTypeId = $params->ResultTypeId;
$Id = $params->Id;

// Convierte el tipo de resultado numérico a su representación en letra
switch ($ResultTypeId) {
    case 1:
        $ResultTypeId = 'A';
        break;
    case 2:
        $ResultTypeId = 'I';
        break;
    case 3:
        $ResultTypeId = 'E';
        break;
    case 4:
        $ResultTypeId = 'R';
        break;
    default:
        $ResultTypeId = '';
        break;
}

// Procesa los parámetros de fechas para la campaña y redención
$BeginDate = $params->BeginDate; //Fecha Inicial de la campaña
$EndDate = $params->EndDate; //Fecha Final de la campaña

$BeginDateRedemption = $params->BeginDateRedemption; //Fecha Inicial de la campaña
$EndDateRedemption = $params->EndDateRedemption; //Fecha Final de la campaña

// Reemplaza el formato de fecha para hacerlo compatible con la base de datos
$BeginDate=str_replace("T"," ",$BeginDate);
$EndDate=str_replace("T"," ",$EndDate);

$BeginDateRedemption=str_replace("T"," ",$BeginDateRedemption);
$EndDateRedemption=str_replace("T"," ",$EndDateRedemption);

// Configura los parámetros de paginación y ordenamiento
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$IdBonus = $_REQUEST["IdBonus"];

$OrderedItem = "usuario_bono.usubono_id";
$OrderType = "desc";

$IdBonus = $params->IdBonus;

// Procesa los parámetros de DataTables si están presentes
$draw = $params->draw;
$length = $params->length;
$start = $params->start;

if ($start != "") {
    $SkeepRows = $start;
}

if ($length != "") {
    $MaxRows = $length;
}

$columns = $params->columns;
$order = $params->order;

// Determina el campo y dirección de ordenamiento basado en los parámetros de DataTables
foreach ($order as $item) {
    switch ($columns[$item->column]->data) {
        case "Id":
            $OrderedItem = "usuario_bono.usubono_id";
            $OrderType = $item->dir;
            break;

        case "Date":
            $OrderedItem = "usuario_bono.fecha_crea";
            $OrderType = $item->dir;
            break;

        case "PlayerExternalId":
            $OrderedItem = "usuario_bono.usuario_id";
            $OrderType = $item->dir;
            break;

        case "AmountBonus":
            $OrderedItem = "usuario_bono.valor";
            $OrderType = $item->dir;
            break;

        case "AmountBase":
            $OrderedItem = "usuario_bono.valor_base";
            $OrderType = $item->dir;
            break;
    }
}

// Inicializa el array de reglas para el filtrado de datos
$rules = [];

// Agrega reglas de filtrado según los parámetros proporcionados
if($Id != ""){
    array_push($rules,array("field"=>"usuario_bono.usubono_id","data"=>"$Id","op"=>"eq"));
}

array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

if ($PlayerExternalId != "") {
    array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => "$PlayerExternalId", "op" => "eq"));
}

// Agrega filtros adicionales para código, ID externo y estado
if ($Code != "") {
    array_push($rules, array("field" => "usuario_bono.codigo", "data" => "$Code", "op" => "eq"));
}

if ($ExternalId != "") {
    array_push($rules, array("field" => "usuario_bono.externo_id", "data" => "$ExternalId", "op" => "eq"));
}

if ($ResultTypeId != "") {
    array_push($rules, array("field" => "usuario_bono.estado", "data" => "$ResultTypeId", "op" => "eq"));
}

// Agrega filtros de fecha para la creación y redención de bonos
if ($BeginDate != "") {
    array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$BeginDate", "op" => "ge"));
}
if ($EndDate != "") {
    array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$EndDate", "op" => "le"));
}

if ($BeginDateRedemption != "") {
    array_push($rules, array("field" => "usuario_bono.fecha_modif", "data" => "$BeginDateRedemption", "op" => "ge"));
    array_push($rules, array("field" => "usuario_bono.estado", "data" => "R", "op" => "eq"));
}
if ($EndDateRedemption != "") {
    array_push($rules, array("field" => "usuario_bono.fecha_modif", "data" => "$EndDateRedemption", "op" => "le"));
    array_push($rules, array("field" => "usuario_bono.estado", "data" => "R", "op" => "eq"));
}

// Aplica restricciones de acceso según la configuración del usuario
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario está condicionado por mandante y no es global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "bono_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

// Construye el objeto de filtro y establece valores por defecto para paginación
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

// Codifica el filtro a JSON para la consulta
$json = json_encode($filtro);

// Crea instancia del modelo y ejecuta la consulta con los filtros
$UsuarioBono = new UsuarioBono();
$data = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*,usuario.nombre", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, '');

$data = json_decode($data);

// Prepara el array para almacenar los resultados procesados
$final = [];

// Procesa cada registro de bono y construye la estructura de respuesta
foreach ($data->data as $key => $value) {
    $array = [];
    $array["Id"] = $value->{"usuario_bono.usubono_id"};
    $array["PlayerExternalId"] = $value->{"usuario_bono.usuario_id"};
    $array["PlayerName"] = $value->{"usuario.nombre"};
    $array["Amount"] = $value->{"usuario_bono.valor"};
    $array["AmountBase"] = $value->{"usuario_bono.valor_base"};
    $array["AmountBonus"] = $value->{"usuario_bono.valor_bono"};
    $array["Code"] = $value->{"usuario_bono.codigo"};
    $array["AmountToWager"] = $value->{"usuario_bono.rollower_requerido"};
    $array["WageredAmount"] = $value->{"usuario_bono.apostado"};
    $array["Date"] = $value->{"usuario_bono.fecha_crea"};
    $array["ExternalId"] = $value->{"usuario_bono.externo_id"};

    // Agrega fecha de redención solo si el estado es 'R' (Redimido)
    if($value->{"usuario_bono.estado"} == 'R'){
        $array["DateRedemption"] = $value->{"usuario_bono.fecha_modif"};
    }

    // Convierte el estado del bono a su representación numérica para la API
    switch ($value->{"usuario_bono.estado"}) {
        case "A":
            $array["ResultTypeId"] = 1;
            break;
        case "E":
            $array["ResultTypeId"] = 3;
            break;
        case "R":
            $array["ResultTypeId"] = 4;
            break;
        case "I":
            $array["ResultTypeId"] = 2;
            break;
        case "P":
            $array["ResultTypeId"] = 5;
            break;
        case "L":
            $array["ResultTypeId"] = 6;
            break;
        default:
    }

    // Agrega el registro procesado al array final
    array_push($final, $array);
}

// Construye y devuelve la respuesta final con los datos procesados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;
$response["Data"] = $final;
$response["Count"] = $data->count[0]->{".count"};
