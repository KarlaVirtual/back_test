<?php
use Backend\dto\SitioTracking;
use Backend\mysql\SitioTrackingMySqlDAO;

/**
 * Report/GetRegisterLanding
 * 
 * Obtiene el reporte de registros por landing page
 *
 * @param int $UserId              ID del usuario
 * @param string $dateFrom         Fecha inicial (Y-m-d)
 * @param string $dateTo           Fecha final (Y-m-d) 
 * @param int $CountrySelect       ID del país a filtrar
 * @param int $count               Cantidad de registros a retornar
 * @param int $start               Posición inicial para paginación
 *
 * @return array {
 *   "HasError": boolean,          // Indica si hubo error
 *   "AlertType": string,          // Tipo de alerta (success, error)
 *   "AlertMessage": string,       // Mensaje descriptivo
 *   "ModelErrors": array,         // Errores del modelo
 *   "total_count": int,           // Total de registros encontrados
 *   "pos": int,                   // Posición actual en la paginación
 *   "data": array {              // Lista de registros encontrados
 *     "Id": int,                 // ID del registro
 *     "UserId": int,             // ID del usuario
 *     "UserName": string,        // Nombre del usuario
 *     "Country": string,         // País del usuario
 *     "RegisterDate": string,    // Fecha de registro
 *     "LandingPage": string      // Landing page de registro
 *   }[]
 * }
 */


// Obtiene los parámetros de la solicitud HTTP
$user_id = $_REQUEST["UserId"];
$FromDateLocal = $_REQUEST["dateFrom"];
$ToDateLocal = $_REQUEST["dateTo"]; 
$CountrySelect = $_REQUEST["CountrySelect"];
$MaxRows = $_REQUEST["count"];
$SkeepRows = $_REQUEST["start"];

// Configura los valores por defecto para la paginación
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

// Inicializa el array de reglas para el filtrado
$rules = [];

// Procesa y formatea las fechas para el filtrado
if ($FromDateLocal)
{
    $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal) . $timezone . ' hour '));
}else
{
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime($timezone . ' hour'));
}

if ($ToDateLocal) {
    $timestamp = strtotime(str_replace(" - ", " ", $ToDateLocal) . ' ' . $timezone . ' hour');
} else
{
    $timestamp = strtotime('+1 day ' . $timezone . ' hour');
}

// Agrega las reglas de filtrado por fecha
$ToDateLocal = date("Y-m-d 23:59:59", $timestamp);
array_push($rules, array("field" => "sitio_tracking.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
array_push($rules, array("field" => "sitio_tracking.fecha_crea", "data" => $ToDateLocal, "op" => "le"));

// Agrega filtros adicionales por usuario y país si están especificados
if ($user_id != '')
{
    array_push($rules, array("field" => "sitio_tracking.tabla_id", "data" => $user_id, "op" => "eq"));
}
if ($CountrySelect != '') {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
}

// Establece valores por defecto para la paginación si no están definidos
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}

// Prepara y ejecuta la consulta de tracking
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$sitioTracking = new SitioTracking();
$tracking = $sitioTracking->getSitioTrackingesCustom("usuario.login,usuario.fecha_verificado,sitio_tracking.tabla_id,sitio_tracking.tvalue,sitio_tracking.fecha_crea","sitio_tracking.fecha_crea","desc",$SkeepRows,$MaxRows,$json,true,true);

// Procesa los resultados y construye el array de datos
$data = json_decode($tracking);
$arrayData =[];
foreach ($data->data as $value){
    $rowData = [
        'DateRegister' => $value->{"sitio_tracking.fecha_crea"},
        'Id' => $value->{"sitio_tracking.tabla_id"},
        'Value' => $value->{"sitio_tracking.tvalue"},
        'DateVerification' => $value->{"usuario.fecha_verificado"},
    ];

    array_push($arrayData, $rowData);
}

// Prepara la respuesta con los resultados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["pos"] = $SkeepRows;
$response["total_count"] = $data->count[0]->{".count"};
$response["data"] = $arrayData;
