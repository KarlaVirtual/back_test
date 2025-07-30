<?php


use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;

/**
 * Report/getSessions2
 * 
 * Obtiene el historial de sesiones de un usuario con filtro por proveedor
 *
 * @param object $params {
 *   "PlayerId": int,               // ID del jugador
 *   "ProviderId": int,             // ID del proveedor
 *   "State": string,               // Estado de la sesión
 *   "dateTo": string,              // Fecha final (Y-m-d)
 *   "dateFrom": string,            // Fecha inicial (Y-m-d)
 *   "MaxRows": int,                // Cantidad máxima de registros
 *   "SkeepRows": int               // Registros a omitir (paginación)
 * }
 *
 * @return array {
 *   "HasError": boolean,           // Indica si hubo error
 *   "AlertType": string,           // Tipo de alerta (success, error)
 *   "AlertMessage": string,        // Mensaje descriptivo
 *   "ModelErrors": array,          // Errores del modelo
 *   "Data": array {
 *     "Objects": array[],          // Lista de sesiones
 *     "Count": int                 // Total de registros
 *   }
 * }
 */


// Inicializa el objeto Usuario y obtiene los parámetros de entrada
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);

$PlayerId = $_GET['PlayerId'];
$ProviderId = $_GET['ProviderId'];
$MaxRows = $_GET['count'];
$SkeepRows = $_GET['start'];
$ToDateLocal =$_GET['dateTo'];
$FromDateLocal =$_GET['dateFrom'] ;

$State =$_GET['State'] ;

// Procesa las fechas para agregar las horas
if ($FromDateLocal != "") {
    $FromDateLocal =$FromDateLocal. " ". "00:00:00";
}
if ($ToDateLocal != "") {
    $ToDateLocal =  $ToDateLocal." ". "23:59:59";
}

// Establece valores por defecto para la paginación
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}

// Construye las reglas de filtrado según los parámetros recibidos
$rules = [];

if ($FromDateLocal != "") {
    array_push($rules, array("field" => "usuario_token.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
}

if ($ToDateLocal != "") {
    array_push($rules, array("field" => "usuario_token.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
}

// Agrega filtros para jugador, proveedor y estado
if ($PlayerId != "") {
    $Usuario = new Usuario($PlayerId);
    $UsuarioMandante = new UsuarioMandante('',$Usuario->usuarioId,$Usuario->mandante);
    array_push($rules, array("field" => "usuario_token.usuario_id", "data" => "$UsuarioMandante->usumandanteId", "op" => "eq"));
}

if ($ProviderId != "") {
    array_push($rules, array("field" => "usuario_token.proveedor_id", "data" => $ProviderId, "op" => "eq"));
}

if($State == 'A' || $State == 'I'){
    array_push($rules, array("field" => "usuario_token.estado", "data" => $State, "op" => "eq"));
}

// Prepara y ejecuta la consulta para obtener las sesiones
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$select = "usuario_token.*,proveedor.*,usuario_mandante.usuario_mandante ";

$UsurioToken = new UsuarioToken();
$data = $UsurioToken->getUsuariosCustom($select, "usuario_token.usuario_id", "desc", $SkeepRows, $MaxRows, $json, true);
$data = json_decode($data);

// Procesa los resultados y construye el array de respuesta
$final = [];

foreach ($data->data as $key => $value) {
    $array["SessionId"] = $value->{"usuario_token.usutoken_id"};
    $array["Token"] = $value->{"usuario_token.token"};
    $array["PlayerId"] = $value->{"usuario_mandante.usuario_mandante"};
    $array["StartTime"] = $value->{"usuario_token.fecha_crea"};
    $array["EndTime"] = $value->{"usuario_token.fecha_modif"};
    $array["ProviderId"] = $value->{"proveedor.proveedor_id"};
    $array["ProviderName"] = $value->{"proveedor.descripcion"};
    $array["State"] =$value->{"usuario_token.estado"};

    array_push($final, $array);
}

// Construye la respuesta final con los datos y metadatos
$response["AlertMessage"] = "";
$response["AlertType"] = "success";
$response["HasError"] = false;
$response["ModelErrors"] = [];
$response["data"] = $final;
$response["pos"] = $SkeepRows;
$response["total_count"] = $data->count[0]->{".count"};
