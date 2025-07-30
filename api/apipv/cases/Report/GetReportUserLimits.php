<?php

use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioMandante;

/**
 * Report/GetReportUserLimits
 * 
 * Obtiene el reporte de límites configurados por los usuarios
 *
 * @param string $dateFrom         Fecha inicial (Y-m-d)
 * @param string $dateTo           Fecha final (Y-m-d)
 * @param int $start               Posición inicial para paginación
 * @param int $count               Cantidad de registros a retornar
 * @param int $IdLimitation        ID del tipo de límite
 * @param int $IdUser              ID del usuario
 * @param string $Email            Email del usuario
 * @param string $TypeLimitation   Tipo de limitación
 * @param string $dateFromStart    Fecha inicial de inicio de límite (Y-m-d) 
 * @param string $dateToStart      Fecha final de inicio de límite (Y-m-d)
 * @param string $dateFromEnd      Fecha inicial de fin de límite (Y-m-d)
 * @param string $dateToEnd        Fecha final de fin de límite (Y-m-d)
 * @param string $State            Estado del límite
 * 
 * @return array {
 *   "HasError": boolean,          // Indica si hubo error
 *   "AlertType": string,          // Tipo de alerta (success, error)
 *   "AlertMessage": string,       // Mensaje descriptivo
 *   "ModelErrors": array,         // Errores del modelo
 *   "total_count": int,           // Total de registros encontrados
 *   "pos": int,                   // Posición actual en la paginación
 *   "data": array {              // Lista de límites encontrados
 *     "Id": int,                 // ID del límite
 *     "UserId": int,             // ID del usuario
 *     "UserName": string,        // Nombre del usuario
 *     "Email": string,           // Email del usuario
 *     "LimitType": string,       // Tipo de límite
 *     "Amount": float,           // Monto del límite
 *     "StartDate": string,       // Fecha de inicio
 *     "EndDate": string,         // Fecha de fin
 *     "Status": string           // Estado del límite
 *   }[]
 * }
 */

// Obtiene los parámetros de la solicitud HTTP
$date_from =$_REQUEST['dateFrom'];
$date_to = $_REQUEST['dateTo'];
$SkeepRows = $_REQUEST["start"];
$MaxRows = $_REQUEST["count"];
$mandante_id = $_SESSION["mandante"];
$Limit_id = $_REQUEST["IdLimitation"];
$User_id = $_REQUEST["IdUser"];
$Email = $_REQUEST["Email"];
$TypeLimitation = $_REQUEST["TypeLimitation"];
$dateFromStart = $_REQUEST["dateFromStart"];
$dateToStart = $_REQUEST["dateToStart"];
$dateFromEnd = $_REQUEST["dateFromEnd"];
$dateToEnd = $_REQUEST["dateFromEnd"];
$State = $_REQUEST["State"];

// Inicializa el array de reglas para el filtrado
$rules = [];

// Agrega regla para filtrar por tipos de límites permitidos
array_push($rules, array("field" => "clasificador.abreviado", "data" => "

        'LIMITEDEPOSITOSIMPLE',
'LIMITEDEPOSITODIARIO',
'LIMITEDEPOSITOSEMANA',
'LIMITEDEPOSITOMENSUAL',
'LIMAPUDEPORTIVASIMPLE',
'LIMAPUDEPORTIVADIARIO',
'LIMAPUDEPORTIVASEMANA',
'LIMAPUDEPORTIVAMENSUAL',
'LIMAPUDEPORTIVAANUAL',
'LIMAPUCASINOSIMPLE',
'LIMAPUCASINODIARIO',
'LIMAPUCASINOSEMANA',
'LIMAPUCASINOMENSUAL',
'LIMAPUCASINOANUAL',
'LIMAPUCASINOVIVOSIMPLE',
'LIMAPUCASINOVIVODIARIO',
'LIMAPUCASINOVIVOSEMANA',
'LIMAPUCASINOVIVOMENSUAL',
'LIMAPUCASINOVIVOANUAL',
'LIMAPUVIRTUALESSIMPLE',
'LIMAPUVIRTUALESDIARIO',
'LIMAPUVIRTUALESSEMANA',
'LIMAPUVIRTUALESMENSUAL'


", "op" => "in"));

// Agrega reglas de filtrado según los parámetros recibidos
array_push($rules, array("field" => "usuario.mandante", "data" => "$mandante_id", "op" => "eq"));

if (!empty($User_id)){
    array_push($rules, array("field" => "usuario_configuracion.usuario_id", "data" => $User_id, "op" => "eq"));
}
if (!empty($Limit_id)){
    array_push($rules, array("field" => "usuario_configuracion.usuconfig_id", "data" => $Limit_id, "op" => "eq"));
}
if (!empty($Email)){
    array_push($rules, array("field" => "usuario.login", "data" => $Email, "op" => "eq"));
}

// Agrega reglas de filtrado por fechas
if (!empty($dateFromStart) && $dateFromStart != 'undefined'){
    $dateFromStart = date('Y-m-d 00:00:00', strtotime($dateFromStart));
    array_push($rules, array("field" => "usuario_configuracion.fecha_crea", "data" => $dateFromStart, "op" => "ge"));
}
if (!empty($dateToStart) && $dateToStart != 'undefined'){
    $dateToStart = date('Y-m-d 23:59:59', strtotime($dateToStart));
    array_push($rules, array("field" => "usuario_configuracion.fecha_crea", "data" => $dateToStart, "op" => "le"));
}
if (!empty($dateFromEnd) && $dateFromEnd != 'undefined'){
    $dateFromEnd = date('Y-m-d 00:00:00', strtotime($dateFromEnd));
    array_push($rules, array("field" => "usuario_configuracion.fecha_fin", "data" => $dateFromEnd, "op" => "ge"));
}
if (!empty($dateToEnd) && $dateToEnd != 'undefined'){
    $dateToEnd = date('Y-m-d 23:59:59', strtotime($dateToEnd));
    array_push($rules, array("field" => "usuario_configuracion.fecha_fin", "data" => $dateToEnd, "op" => "le"));
}

// Agrega reglas según el tipo de limitación seleccionada
if ($TypeLimitation != '' && $TypeLimitation != 'null'){
    $arrayLimitation = [0 => 'LIMITEDEPOSITOSIMPLE', 1 => 'LIMAPUDEPORTIVASIMPLE', 2 => 'LIMAPUCASINOSIMPLE', 3 => 'LIMAPUCASINOVIVOSIMPLE', 4 => 'LIMAPUVIRTUALESSIMPLE'];
    $TypeLimitation = $arrayLimitation[$TypeLimitation];
    switch ($TypeLimitation){
        case 'LIMITEDEPOSITOSIMPLE':
            array_push($rules, array("field" => "clasificador.abreviado", "data" => "'LIMITEDEPOSITOSIMPLE','LIMITEDEPOSITODIARIO','LIMITEDEPOSITOSEMANA','LIMITEDEPOSITOMENSUAL'", "op" => "in"));

            break;
        case 'LIMAPUDEPORTIVASIMPLE':
            array_push($rules, array("field" => "clasificador.abreviado", "data" => "
'LIMAPUDEPORTIVASIMPLE',
'LIMAPUDEPORTIVADIARIO',
'LIMAPUDEPORTIVASEMANA',
'LIMAPUDEPORTIVAMENSUAL',
'LIMAPUDEPORTIVAANUAL'", "op" => "in"));

            break;
        case 'LIMAPUCASINOSIMPLE':
            array_push($rules, array("field" => "clasificador.abreviado", "data" => "
'LIMAPUCASINOSIMPLE',
'LIMAPUCASINODIARIO',
'LIMAPUCASINOSEMANA',
'LIMAPUCASINOMENSUAL',
'LIMAPUCASINOANUAL'", "op" => "in"));

            break;
        case 'LIMAPUCASINOVIVOSIMPLE':
            array_push($rules, array("field" => "clasificador.abreviado", "data" => "
'LIMAPUCASINOVIVOSIMPLE',
'LIMAPUCASINOVIVODIARIO',
'LIMAPUCASINOVIVOSEMANA',
'LIMAPUCASINOVIVOMENSUAL',
'LIMAPUCASINOVIVOANUAL'", "op" => "in"));

            break;

        case 'LIMAPUVIRTUALESSIMPLE':
            array_push($rules, array("field" => "clasificador.abreviado", "data" => "
'LIMAPUVIRTUALESSIMPLE',
'LIMAPUVIRTUALESDIARIO',
'LIMAPUVIRTUALESSEMANA',
'LIMAPUVIRTUALESMENSUAL',
'LIMAPUVIRTUALESANUAL'", "op" => "in"));

            break;
    }

}

// Agrega regla de filtrado por estado
if ($State != '' && $State != 'null'){
    $arrayState = [0 => 'A', 1 => 'I',2 => 'C'];
    $State = $arrayState[$State];
    array_push($rules, array("field" => "usuario_configuracion.estado", "data" => $State, "op" => "eq"));
}

// Configura la paginación
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($MaxRows == "") {
    $MaxRows = 100;
}

// Prepara y ejecuta la consulta
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

$UsuarioConfiguracion = new UsuarioConfiguracion();

$configuraciones = $UsuarioConfiguracion->getUsuarioConfiguracionesCustom(" usuario_configuracion.*,clasificador.*,usuario.login ", "usuario_configuracion.usuconfig_id", "asc", $SkeepRows, $MaxRows, $json2, true);

// Procesa los resultados de la consulta
$configuraciones = json_decode($configuraciones);
$dataFinal = [];
foreach ($configuraciones->data as $value){
    $array = [];
    $array["Id"] =$value->{"usuario_configuracion.usuconfig_id"};
    $array["UserId"] = $value->{"usuario_configuracion.usuario_id"};
    $array["Email"] = $value->{"usuario.login"};
    $array["Type"] = $value ->{"clasificador.abreviado"};
    $array["DateTime"] = $value ->{"usuario_configuracion.fecha_crea"};
    $array["EndDate"] = $value ->{"usuario_configuracion.fecha_fin"};
    
    // Calcula el tiempo restante para cada límite
    $dateNow = new DateTime();
    $endDate = new DateTime($value->{"usuario_configuracion.fecha_fin"});

    if ($endDate > $dateNow) {
        $interval = $dateNow->diff($endDate);
        $hoursRemaining = $interval->days * 24 + $interval->h;
        $array["TimeLimit"] = "faltan $hoursRemaining horas";
    } else {
        $array["TimeLimit"] = "faltan 0 horas";
    }

    // Mapea los estados a valores legibles
    $array["State"] = match ($value ->{"usuario_configuracion.estado"}) {
        'A' => 'Activo',
        'I' => 'Inactivo',
        'C' => 'Cancelado',
    };
    $array["ModifyDate"] = $value ->{"usuario_configuracion.fecha_modif"};
    $array["Observation"] = $value ->{"usuario_configuracion.nota"};
    $array["Amount"] = $value ->{"usuario_configuracion.valor"};
    array_push($dataFinal, $array);
}

// Prepara la respuesta final
$response["pos"] = $SkeepRows;
$response["total_count"] = $configuraciones->count[0]->{'.count'};
$response["data"] = $dataFinal;