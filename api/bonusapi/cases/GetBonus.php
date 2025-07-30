<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioBono;

/**
 * GetBonus
 * 
 * Obtiene el listado de bonos según los filtros especificados
 *
 * @param object $params {
 *   "EndDate": string,           // Fecha final en formato "YYYY-MM-DD HH:mm:ss"
 *   "BeginDate": string,         // Fecha inicial en formato "YYYY-MM-DD HH:mm:ss"
 *   "TypeId": int,               // ID del tipo de bono
 *   "Loyalty": string,           // Indicador de lealtad ("SI"/"NO")
 *   "Id": int,                   // ID del bono
 *   "TypeBonus": string,         // Tipo de bono
 *   "ActiveBonus": boolean,      // Filtro de bonos activos
 *   "RedimBonus": boolean,       // Filtro de bonos redimidos
 *   "ExpiratedBonus": boolean,   // Filtro de bonos expirados
 *   "StateType": int,            // Tipo de estado
 *   "CampaingDetails": string,   // Detalles de campaña
 *   "CampaingCategory": string,  // Categoría de campaña
 *   "State": string,             // Estado del bono
 *   "Country": string,           // País
 *   "Limit": int,                // Número máximo de registros
 *   "OrderedItem": string,       // Campo de ordenamiento
 *   "Offset": int                // Número de página
 * }
 *
 * @return array {
 *   "HasError": boolean,         // Indica si hubo error
 *   "AlertType": string,         // Tipo de alerta (success/danger)
 *   "AlertMessage": string,      // Mensaje descriptivo
 *   "ModelErrors": array,        // Errores del modelo
 *   "Data": array[{             // Lista de bonos
 *     "Id": int,                // ID del bono
 *     "Name": string,           // Nombre del bono
 *     "Description": string,    // Descripción del bono
 *     "Amount": float,          // Monto del bono
 *     "Status": string,         // Estado del bono
 *     "CreationDate": string,   // Fecha de creación
 *     "ExpirationDate": string, // Fecha de expiración
 *     "Type": string,           // Tipo de bono
 *     "IsActive": boolean,      // Indica si está activo
 *     "IsRedeemed": boolean,    // Indica si está redimido
 *     "IsExpired": boolean      // Indica si está expirado
 *   }],
 *   "Count": int                // Total de registros
 * }
 *
 * @throws Exception            // Errores de procesamiento
 */

// Obtiene los parámetros de filtrado desde el request
$ToDateLocal = $params->EndDate;
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;
$Loyalty = $params->Loyalty; // OK se pidio que se guardara con si o no la lealtad
$Id = $params->Id;
$TypeBonus = $params->TypeBonus;
$ActiveBonus = $params->ActiveBonus;
$RedimBonus = $params->RedimBonus;
$ExpiratedBonus = $params->ExpiratedBonus;

// Normaliza las fechas de inicio y fin del filtro
$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->BeginDate;
$ToDateLocal = $params->EndDate;

// Configura los parámetros de paginación
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

// Obtiene parámetros adicionales de filtrado
$StateType = $params->StateType;
$CampaingDetails = $params->CampaingDetails;
$CampaingCategory = $params->CampaingCategory;
$State = $params->State;
$Country = $params->Country;

// Inicializa el array de reglas de filtrado
$rules = [];

if ($StateType == 1) {
} else {
}

// Construye el filtro inicial
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

// Procesa parámetros de DataTables si están presentes
$draw = $params->draw;
$length = $params->length;
$start = $params->start;

if ($start != "") {
    $SkeepRows = $start;
}

if ($length != "") {
    $MaxRows = $length;
}

// Prepara la consulta inicial
$json = json_encode($filtro);
$BonoInterno = new BonoInterno();
$BonoDetalle = new BonoDetalle();

// Construye las reglas de filtrado según los parámetros
$rules = [];

// Aplica filtros por país
if ($Country != "") {
    array_push($rules, array("field" => "bono_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
    array_push($rules, array("field" => "bono_detalle.valor", "data" => "$Country", "op" => "eq"));
} else {
    array_push($rules, array("field" => "bono_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
}

// Aplica filtros por estado
if ($State == "A" || $State == "I") {
    array_push($rules, array("field" => "bono_interno.estado", "data" => "$State", "op" => "eq"));
}

// Aplica filtros de campaña
if($CampaingDetails != ""){
    array_push($rules,array("field"=>"bono_interno.detalle_campaña","data"=>$CampaingDetails,"op"=>"eq"));
}

if($CampaingCategory != ""){
    array_push($rules,array("field"=>"bono_interno.categoria_campaña","data"=>$CampaingCategory,"op"=>"eq"));
}

// Aplica filtro de lealtad
if ($Loyalty == true) {
    array_push($rules, array("field" => "bono_interno.lealtad", "data" => "1", "op" => "eq"));
} else {
    array_push($rules, array("field" => "bono_interno.lealtad", "data" => "0", "op" => "eq"));
}

// Aplica filtros de fecha
if ($ToDateLocal != "") {
    $ToDateLocal = str_replace("T"," ",$ToDateLocal);
    array_push($rules, array("field" => "bono_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "le"));
}

if ($FromDateLocal != "") {
    $FromDateLocal = str_replace("T"," ",$FromDateLocal);
    array_push($rules, array("field" => "bono_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "ge"));
}

// Aplica filtros por ID y tipo de bono
if ($Id != ""){
    array_push($rules, array("field" => "bono_interno.bono_id", "data" => $Id, "op" => "eq" ));
}

// Mapea los tipos de bono a sus códigos internos
if ($TypeBonus != "" ){
    switch($TypeBonus){
        case '0':
            array_push($rules, array("field" => "bono_interno.tipo", "data" => '2', "op" => "eq" ));
            break;
        case '1':
            array_push($rules, array("field" => "bono_interno.tipo", "data" => '3', "op" => "eq" ));
            break;
        case '2':
            array_push($rules, array("field" => "bono_interno.tipo", "data" => '6', "op" => "eq" ));
            break;
        case '3':
            array_push($rules, array("field" => "bono_interno.tipo", "data" => '5', "op" => "eq" ));
            break;
        case '4':
            array_push($rules, array("field" => "bono_interno.tipo", "data" => '8', "op" => "eq" ));
            break;
    }
}

// Aplica restricciones según el perfil del usuario
if ($_SESSION['PaisCond'] == "S") {
}

if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "bono_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {
    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "bono_interno.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }
}

// Prepara el filtro final y ejecuta la consulta
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


$json = json_encode($filtro);
$bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "desc", $SkeepRows, $MaxRows, $json, TRUE);
$bonodetalles = json_decode($bonodetalles);

// Procesa los resultados
$final = [];

foreach ($bonodetalles->data as $key => $value) {
    // Construye el array con la información de cada bono
    $array = [];
    $array["Id"] = $value->{"bono_interno.bono_id"};
    $array["Name"] = $value->{"bono_interno.nombre"};
    $array["Description"] = $value->{"bono_interno.descripcion"};
    $array["BeginDate"] = $value->{"bono_interno.fecha_inicio"};
    $array["EndDate"] = $value->{"bono_interno.fecha_fin"};
    $array["ProductTypeId"] = $value->{"bono_detalle.valor"};
    $array["TypeId"] = $value->{"bono_interno.tipo"};
    $array["loyalty"] = $value->{"bono.interno.lealtad"};

    // Obtiene estadísticas del bono
    $rules = [];
    array_push($rules, array("field" => "bono_interno.bono_id", "data" => $array["Id"], "op" => "eq"));
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    // Define la consulta para obtener totales
    $select = "SUM(CASE WHEN usuario_bono.estado =  'R' THEN usuario_bono.valor ELSE 0 END) valor_redimidos,
            SUM(CASE WHEN usuario_bono.estado =  'A' THEN usuario_bono.valor ELSE 0 END) valor_activos,
            SUM(CASE WHEN usuario_bono.estado =  'E' THEN usuario_bono.valor ELSE 0 END) valor_expirados,
            SUM(CASE WHEN usuario_bono.estado =  'R' THEN 1 ELSE 0 END) cant_redimidos,
            SUM(CASE WHEN usuario_bono.estado =  'A' THEN 1 ELSE 0 END) cant_activos,
            SUM(CASE WHEN usuario_bono.estado =  'E' THEN 1 ELSE 0 END) cant_expirados
            ";

    // Ejecuta la consulta de totales
    $UsuarioBono = new UsuarioBono();
    $data = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", 0, 1, $json, true, '');
    $data = json_decode($data);
    $value2 = $data->data[0];

    // Calcula los totales para cada estado
    $array["ActiveBonus"] = number_format($value2->{".valor_activos"}, 2);
    $array["RedimBonus"] = number_format($value2->{".valor_redimidos"}, 2);
    $array["ExpiratedBonus"] = number_format($value2->{".valor_expirados"}, 2);
    $array["AllBonus"] = ($value2->{".valor_activos"} + $value2->{".valor_redimidos"} + $value2->{".valor_expirados"});
    $array["AllBonus"] = number_format($array["AllBonus"], 2);

    $array["State"] = $value->{"bono_interno.estado"};

    // Mapea las categorías de campaña
    switch ($value->{"bono_interno.categoria_campaña"}){
        case 351:
            $array["CampaingCategory"] = "Adquisicion";
            break;
        case 352:
            $array["CampaingCategory"] = "Retencion";
            break;
        case 353:
            $array["CampaingCategory"] = "Reactivacion";
            break;
        case 354:
            $array["CampaingCategory"] = "Retencion de saldos";
            break;
    }

    // Mapea los detalles de campaña
    switch ($value->{"bono_interno.detalle_campaña"}){
        case 355:
            $array["CampaingDetails"] = "Bono de bienvenida";
            break;
        case 356:
            $array["CampaingDetails"] = "Bono de registro";
            break;
        case 357:
            $array["CampaingDetails"] = "Bono extra por registro";
            break;
        case 358:
            $array["CampaingDetails"] = "Campaña local";
            break;
        case 359:
            $array["CampaingDetails"] = "Bono Torneos";
            break;
        case 360:
            $array["CampaingDetails"] = "Fidelizacion";
            break;
        case 361:
            $array["CampaingDetails"] = "Lealtad";
            break;
        case 362:
            $array["CampaingDetails"] = "CRM Fidelización";
            break;
        case 363:
            $array["CampaingDetails"] = "Bono Cumpleaños";
            break;
        case 364:
            $array["CampaingDetails"] = "Bono próximo depósito";
            break;
        case 365:
            $array["CampaingDetails"] = "Bono Sorteo";
            break;
        case 366:
            $array["CampaingDetails"] = "Bono Ruleta";
            break;
        case 367:
            $array["CampaingDetails"] = "CRM Activación";
            break;
        case 368:
            $array["CampaingDetails"] = "Activación";
            break;
    }





    $array["CampaingDetails"] = $value->{"bono_interno.detalle_campaña"};

    switch ($value->{"bono_interno.tipo"}) {
        case "2":
            $array["Type"] = array(
                "Id" => $value->{"bono_interno.tipo"},
                "Name" => "Bono Deposito",
                "TypeId" => $value->{"bono_interno.tipo"}
            );
            break;

        case "3":
            $array["Type"] = array(
                "Id" => $value->{"bono_interno.tipo"},
                "Name" => "Bono No Deposito",
                "TypeId" => $value->{"bono_interno.tipo"}
            );
            break;

        case "4":
            $array["Type"] = array(
                "Id" => $value->{"bono_interno.tipo"},
                "Name" => "Bono Cash",
                "TypeId" => $value->{"bono_interno.tipo"}
            );
            break;

        case "6":
            $array["Type"] = array(
                "Id" => $value->{"bono_interno.tipo"},
                "Name" => "Freebet",
                "TypeId" => $value->{"bono_interno.tipo"}
            );
            break;
    }

    array_push($final, $array);
}

// Prepara la respuesta final
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Count"] = $bonodetalles->count[0]->{".count"};
$response["Data"] = $final;
