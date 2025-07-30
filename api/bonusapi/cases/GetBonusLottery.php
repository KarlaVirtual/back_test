<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;

/**
 * GetBonusLottery
 * 
 * Obtiene las definiciones y listado de bonos de lotería según los filtros especificados
 *
 * @param object $params {
 *   "StartTimeLocal": string,   // Fecha inicial en formato "YYYY-MM-DD HH:mm:ss"
 *   "EndTimeLocal": string,     // Fecha final en formato "YYYY-MM-DD HH:mm:ss"
 *   "TypeId": int,             // ID del tipo de bono
 *   "Limit": int,              // Número máximo de registros
 *   "OrderedItem": string,     // Campo de ordenamiento
 *   "Offset": int,             // Número de página
 *   "StateType": int           // Estado del bono (1: activo, 0: inactivo)
 * }
 * 
 * @return array {
 *   "HasError": boolean,       // Indica si hubo error
 *   "AlertType": string,       // Tipo de alerta (success/danger)
 *   "AlertMessage": string,    // Mensaje descriptivo
 *   "ModelErrors": array,      // Errores del modelo
 *   "Count": int,             // Total de registros
 *   "Data": array {           // Lista de bonos de lotería
 *     "Id": int,             // ID del bono
 *     "Name": string,        // Nombre del bono
 *     "Description": string, // Descripción del bono
 *     "BeginDate": string,   // Fecha de inicio
 *     "EndDate": string,     // Fecha de fin
 *     "Type": object {       // Tipo de bono
 *       "Id": int,          // ID del tipo
 *       "Name": string      // Nombre del tipo
 *     }
 *   }
 * }
 *
 * @throws Exception         // Errores de procesamiento
 */
// Obtiene y decodifica los parámetros de entrada desde el request
$params = file_get_contents('php://input');
$params = json_decode($params);

// Extrae los parámetros de fechas y tipo de bono
$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;

$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

// Configura los parámetros de paginación y estado
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$StateType = $params->StateType;

// Inicializa el array de reglas para el filtro
$rules = [];

if ($StateType == 1) {

} else {

}

/*if ($TypeId != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$TypeId", "op" => "eq"));
}*/

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

// Prepara la consulta y sus parámetros
$json = json_encode($filtro);

// Inicializa los objetos de acceso a datos
$BonoInterno = new BonoInterno();
$BonoDetalle = new BonoDetalle();

//$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
//$bonos = json_decode($bonos);

// Configura las reglas de filtrado para bonos de sorteo activos
$rules = [];

array_push($rules, array("field" => "bono_detalle.tipo", "data" => "BONOSORTEO", "op" => "eq"));
array_push($rules, array("field" => "bono_interno.estado", "data" => "A", "op" => "eq"));

// Construye el filtro final y establece valores de paginación
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

// Ejecuta la consulta para obtener los detalles de bonos
$json = json_encode($filtro);

$bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, TRUE);

$bonodetalles = json_decode($bonodetalles);

// Prepara el array para almacenar los resultados procesados
$final = [];

// Procesa cada bono y construye la estructura de respuesta
foreach ($bonodetalles->data as $key => $value) {

    $array = [];

    // Asigna los valores básicos del bono
    $array["Id"] = $value->{"bono_interno.bono_id"};
    $array["Name"] = $value->{"bono_interno.nombre"};
    $array["Description"] = $value->{"bono_interno.descripcion"};
    $array["BeginDate"] = $value->{"bono_interno.fecha_inicio"};
    $array["EndDate"] = $value->{"bono_interno.fecha_fin"};
    $array["ProductTypeId"] = $value->{"bono_detalle.valor"};
    $array["TypeId"] = $value->{"bono_interno.tipo"};

    // Determina y asigna el tipo específico de bono
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
        case "5":
            $array["Type"] = array(
                "Id" => $value->{"bono_interno.tipo"},
                "Name" => "FreeCasino",
                "TypeId" => $value->{"bono_interno.tipo"}
            );
            break;
        case "6":
            $array["Type"] = array(
                "Id" => $value->{"bono_interno.tipo"},
                "Name" => "FreeBet",
                "TypeId" => $value->{"bono_interno.tipo"}
            );
            break;
        case "8":
            $array["Type"] = array(
                "Id" => $value->{"bono_interno.tipo"},
                "Name" => "FreeSpin",
                "TypeId" => $value->{"bono_interno.tipo"}
            );
            break;

    }

    array_push($final, $array);
}

// Construye y devuelve la respuesta final
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Count"] = $bonodetalles->count[0]->{".count"};

$response["Result"] = $final;