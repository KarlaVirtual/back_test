<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioLealtad;
use Backend\dto\PuntoVenta;
use Backend\dto\Ciudad;
use Backend\dto\Departamento;

/**
 * Report/GetLoyaltyPlayers
 * 
 * Obtiene los jugadores del programa de lealtad según los filtros especificados
 *
 * @param array $params {
 *   "Id": int,                  // ID del jugador
 *   "Code": string,             // Código del jugador
 *   "dateFrom2": string,        // Fecha inicial de modificación en formato Y-m-d
 *   "dateTo2": string,          // Fecha final de modificación en formato Y-m-d
 *   "PlayerExternalId": string, // ID externo del jugador
 *   "ResultTypeId": int,        // Tipo de resultado
 *   "Country": int,             // ID del país
 *   "CasinoId": int,           // ID del casino
 *   "IdBonus": int,            // ID del bono
 *   "TypeAward": string,       // Tipo de premio
 *   "ExternalId": string,      // ID externo
 *   "WayToPay": string,        // Forma de pago
 *   "start": int,              // Registro inicial (paginación)
 *   "count": int,              // Cantidad de registros a retornar
 *   "dateFrom": string,        // Fecha inicial en formato Y-m-d
 *   "dateTo": string          // Fecha final en formato Y-m-d
 * }
 *
 * @return array {
 *   "HasError": boolean,        // Indica si hubo error
 *   "AlertType": string,        // Tipo de alerta (success, error)
 *   "AlertMessage": string,     // Mensaje de alerta
 *   "ModelErrors": array,       // Errores del modelo
 *   "data": array {
 *     "Id": int,               // ID del jugador
 *     "Name": string,          // Nombre del jugador
 *     "Code": string,          // Código del jugador
 *     "ExternalId": string,    // ID externo
 *     "Country": string,       // País del jugador
 *     "Points": float,         // Puntos acumulados
 *     "Level": string,         // Nivel de lealtad
 *     "Status": string,        // Estado del jugador
 *     "CreatedDate": string,   // Fecha de creación
 *     "LastModified": string   // Última modificación
 *   }[],
 *   "pos": int,                // Posición actual
 *   "total_count": int         // Total de registros
 * }
 */


// Obtiene los parámetros de la solicitud
$Id=$_REQUEST['Id'];
$Code=$_REQUEST['Code'];
$BeginDateModified=$_REQUEST['dateFrom2'];
$EndDateModified=$_REQUEST['dateTo2'];
$PlayerExternalId=$_REQUEST['PlayerExternalId'];
$ResultTypeId=$_REQUEST['ResultTypeId'];
$Country=$_REQUEST['Country'];
$CasinoId=$_REQUEST['CasinoId'];
$IdBonus=$_REQUEST['IdBonus'];
$TypeAward=$_REQUEST['TypeAward'];
$ExternalId=$_REQUEST['ExternalId'];
$WayToPay=$_REQUEST['WayToPay'];

// Obtiene los parámetros de paginación
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$MaxRows = ($_REQUEST["count"] == "") ? $_REQUEST["?count"] : $_REQUEST["count"];

// Obtiene los parámetros de fechas
$dateFrom = $_REQUEST["dateFrom"];
$dateTo = $_REQUEST["dateTo"];

// Formatea las fechas de modificación
if ($BeginDateModified != "") {
    $BeginDateModified = date("Y-m-d 00:00:00", strtotime($dateFrom));
}
if ($EndDateModified != "") {
    $EndDateModified = date("Y-m-d 23:59:59", strtotime($dateTo));
}

// Formatea las fechas de creación
if ($dateFrom != "") {
    $dateFrom = date("Y-m-d 00:00:00", strtotime($dateFrom));
}
if ($dateTo != "") {
    $dateTo = date("Y-m-d 23:59:59", strtotime($dateTo));
}

// Convierte el tipo de resultado a su código correspondiente
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
    case 5:
        $ResultTypeId = 'D';
        break;
    default:
        $ResultTypeId = '';
        break;
}

// Define los parámetros de ordenamiento
$OrderedItem = "usuario_lealtad.usulealtad_id";
$OrderType = "desc";

$rules = [];


//$WayToPay=1; Local Propio Tipo_Premio=0 tabla Lealtad_interna y puntoventa_propio=1
//$WayToPay=2; Local Propio Tipo_Premio=0 tabla Lealtad_interna y puntoventa_propio=0
//$WayToPay=3; Local Propio Tipo_Premio=0 tabla Lealtad_interna y puntoventa_propio=1 y usuario_lealtad puntoventaentrega==null

if($WayToPay != ""){

    if($WayToPay === "1"){
        array_push($rules, array("field" => "lealtad_interna.tipo_premio", "data" => 0, "op" => "eq"));
        array_push($rules, array("field" => "lealtad_interna.puntoventa_propio", "data" => 1, "op" => "eq"));
        array_push($rules, array("field" => "usuario_lealtad.puntoventaentrega", "data" => 0, "op" => "ne"));

    }else if($WayToPay === "2"){
        array_push($rules, array("field" => "lealtad_interna.tipo_premio", "data" => 0, "op" => "eq"));
        array_push($rules, array("field" => "lealtad_interna.puntoventa_propio", "data" => 0, "op" => "eq"));

    }else if($WayToPay === "3"){
        array_push($rules, array("field" => "lealtad_interna.tipo_premio", "data" => 0, "op" => "eq"));
        array_push($rules, array("field" => "lealtad_interna.puntoventa_propio", "data" => 1, "op" => "eq"));
        array_push($rules, array("field" => "usuario_lealtad.puntoventaentrega", "data" => 0, "op" => "eq"));
    }
}

// Aplica los filtros por ID, Casino y Bono
if($Id != ""){
    array_push($rules, array("field" => "usuario_lealtad.usulealtad_id", "data" => $Id, "op" => "eq"));
}

if($CasinoId != ""){
    array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => "$CasinoId", "op" => "eq"));
}

if($IdBonus != ""){
    array_push($rules, array("field" => "usuario_lealtad.lealtad_id", "data" => "$IdBonus", "op" => "eq"));
}

// Aplica los filtros por ID de jugador, código y ID externo
if ($PlayerExternalId != "") {
    array_push($rules, array("field" => "usuario_lealtad.usuario_id", "data" => "$PlayerExternalId", "op" => "eq"));
}

if ($Code != "") {
    array_push($rules, array("field" => "usuario_lealtad.codigo", "data" => "$Code", "op" => "eq"));
}

if ($ExternalId != "") {
    array_push($rules, array("field" => "usuario_lealtad.externo_id", "data" => "$ExternalId", "op" => "eq"));
}

// Aplica los filtros por estado y fechas
if ($ResultTypeId != "") {
    array_push($rules, array("field" => "usuario_lealtad.estado", "data" => "$ResultTypeId", "op" => "eq"));
}

if ($dateFrom != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_crea", "data" => $dateFrom, "op" => "ge"));
}
if ($dateTo != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_crea", "data" => $dateTo, "op" => "le"));
}
if ($BeginDateModified != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_modif", "data" => $BeginDateModified, "op" => "ge"));
}
if ($EndDateModified != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_modif", "data" => $EndDateModified, "op" => "le"));
}

// Aplica los filtros por país, mandante y tipo de premio
if ($Country != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $Country, "op" => "eq"));
}
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "lealtad_interna.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}
if ($TypeAward != "") {
    array_push($rules, array("field" => "lealtad_interna.tipo_premio", "data" => $TypeAward, "op" => "eq"));
}

// Prepara el filtro final y los parámetros de paginación
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}

$json = json_encode($filtro);

// Ejecuta la consulta principal
$UsuarioLealtad = new UsuarioLealtad();
$data = $UsuarioLealtad->getUsuarioLealtadCustom("usuario_lealtad.*,usuario.nombre,usuario_mandante.usumandante_id,lealtad_interna.nombre,lealtad_interna.tipo_premio, lealtad_interna.puntoventa_propio", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, '');

$data = json_decode($data);

$final = [];

// Procesa cada registro obtenido
foreach ($data->data as $key => $value) {
    $array = [];
    $array["Id"] = $value->{"usuario_lealtad.usulealtad_id"};
    $array["PlayerExternalId"] = $value->{"usuario_lealtad.usuario_id"};
    $array["PlayerName"] = $value->{"usuario.nombre"};
    $array["Amount"] = $value->{"usuario_lealtad.valor"};
    $array["AmountBase"] = $value->{"usuario_lealtad.valor_base"};
    $array["AmountLealtad"] = $value->{"usuario_lealtad.valor_lealtad"};
    $array["Code"] = $value->{"usuario_lealtad.codigo"};
    $array["AmountToWager"] = $value->{"usuario_lealtad.rollower_requerido"};
    $array["WageredAmount"] = $value->{"usuario_lealtad.apostado"};
    $array["Date"] = $value->{"usuario_lealtad.fecha_crea"};
    $array["ExternalId"] = $value->{"usuario_lealtad.externo_id"};

    $array["TypeAward"] =intval($value->{"lealtad_interna.tipo_premio"});

    // Procesa información adicional del registro
    $array["Observation"] = $value->{"usuario_lealtad.observacion"};
    $array["Prize"] = $value->{"usuario_lealtad.premio"};
    if($array["Prize"] == ""){
        $array["Prize"] = $value->{"lealtad_interna.nombre"};
    }
    $array["CasinoId"] = $value->{"usuario_mandante.usumandante_id"};
    $array["GiftId"] = $value->{"usuario_lealtad.lealtad_id"};
    
    // Convierte el estado a su código numérico
    switch ($value->{"usuario_lealtad.estado"}) {
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
        case "D":
            $array["ResultTypeId"] = 5;
            break;
    }
    $array["DateModified"] = $value->{"usuario_lealtad.fecha_modif"};

    // Procesa información del punto de venta si existe
    $IdPuntoVenta=$value->{"usuario_lealtad.puntoventaentrega"};

    if($IdPuntoVenta !== "0"){
        $puntoVenta = new puntoventa("", $IdPuntoVenta);
        $DireccionPV = $puntoVenta->direccion;
        $CiudadId = $puntoVenta->ciudadId;
        $Ciudad = new Ciudad($CiudadId);
        $CiudadName = $Ciudad->ciudadNom;
        $array["BetShop"] = $DireccionPV;
        $array["CityBetShop"] = $CiudadName;

        $array["BetShopId"] = $IdPuntoVenta;
        $array["Names"] = $value->{"usuario_lealtad.nombreusuentrega"};
        $array["Surnames"] = $value->{"usuario_lealtad.apellidousuentrega"};
        $array["Identification"] = $value->{"usuario_lealtad.cedulausuentrega"};
        $array["Phone"] = $value->{"usuario_lealtad.telefonousuentrega"};
        $array["City"] = $value->{"usuario_lealtad.ciudadusuentrega"};
        $array["Province"] = $value->{"usuario_lealtad.provinciausuentrega"};
        $array["Address"] = $value->{"usuario_lealtad.direccionusuentrega"};
        $array["Team"] = $value->{"usuario_lealtad.teamusuentrega"};

    }else if($IdPuntoVenta == "0"){
        // Procesa información de entrega cuando no hay punto de venta
        $array["Names"] = $value->{"usuario_lealtad.nombreusuentrega"};
        $array["Surnames"] = $value->{"usuario_lealtad.apellidousuentrega"};
        $array["Identification"] = $value->{"usuario_lealtad.cedulausuentrega"};
        $array["Phone"] = $value->{"usuario_lealtad.telefonousuentrega"};

        $Ciudad = new Ciudad($value->{"usuario_lealtad.ciudadusuentrega"});
        $CiudadName = $Ciudad->ciudadNom;
        $array["City"] = $CiudadName;

        $array["Province"] = $value->{"usuario_lealtad.provinciausuentrega"};
        $array["Address"] = $value->{"usuario_lealtad.direccionusuentrega"};
        $array["Team"] = $value->{"usuario_lealtad.teamusuentrega"};
    }

    // Determina la forma de pago según las condiciones
    if($value->{"lealtad_interna.tipo_premio"}==0 && $value->{"lealtad_interna.puntoventa_propio"}==1 &&
        $value->{"usuario_lealtad.puntoventaentrega"}!=0){
        $array["WayToPay"] = "1";
    }else if($value->{"lealtad_interna.tipo_premio"}==0 && $value->{"lealtad_interna.puntoventa_propio"}==0 &&
        $value->{"lealtad_interna.bono_id"}==0 && $value->{"usuario_lealtad.puntoventaentrega"}==null){
        $array["WayToPay"] = "2";
    }else if($value->{"lealtad_interna.tipo_premio"}==0 && $value->{"lealtad_interna.puntoventa_propio"}==0 &&
    $value->{"lealtad_interna.bono_id"}!=0){
        $array["WayToPay"] = "2";
    }else if($value->{"lealtad_interna.tipo_premio"}==0 && $value->{"lealtad_interna.puntoventa_propio"}==1 &&
        $value->{"usuario_lealtad.puntoventaentrega"}==0){
        $array["WayToPay"] = "3";
    }

    array_push($final, $array);
}

// Prepara la respuesta final
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["pos"] = $SkeepRows;
$response["total_count"] = $data->count[0]->{".count"};
$response["data"] = $final;
