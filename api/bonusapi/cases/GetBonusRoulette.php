<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;

/**
 * Obtiene la lista de bonos de tipo ruleta disponibles
 * 
 * Este recurso recupera los bonos de tipo ruleta que están activos en el sistema,
 * filtrando por fechas y tipo de bono. La respuesta incluye información detallada
 * sobre cada bono incluyendo su tipo, fechas de vigencia y descripción.
 * 
 * @param object $params Parámetros de la petición
 * @param string $params->StartTimeLocal Fecha de inicio para filtrar bonos
 * @param string $params->EndTimeLocal Fecha de fin para filtrar bonos
 * @param string $params->TypeId Tipo de bono a filtrar
 * @param int $params->Limit Límite de registros a retornar
 * @param int $params->Offset Número de registros a saltar
 * @param string $params->OrderedItem Campo por el cual ordenar
 * @param int $params->StateType Estado del bono a filtrar
 * 
 * @return array{
 *   HasError: bool,
 *   AlertType: string,
 *   AlertMessage: string,
 *   ModelErrors: array,
 *   Count: int,
 *   Result: array<array{
 *     Id: string,
 *     Name: string,
 *     Description: string,
 *     BeginDate: string,
 *     EndDate: string,
 *     ProductTypeId: string,
 *     TypeId: string,
 *     Type: array{
 *       Id: string,
 *       Name: string,
 *       TypeId: string
 *     }
 *   }>
 * }
 */

 
$params = file_get_contents('php://input');
$params = json_decode($params);

// Obtiene los parámetros de la solicitud para filtrar los bonos de ruleta
$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;

// Hay una duplicación en la asignación de variables que debería corregirse
$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

// Configura los parámetros de paginación y ordenamiento
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$StateType = $params->StateType;

// Inicializa el array de reglas para filtrado
$rules = [];

// Estructura condicional para filtrar por estado, actualmente vacía
if ($StateType == 1) {

} else {

}

/*if ($TypeId != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$TypeId", "op" => "eq"));
}*/

// Configura el filtro con las reglas definidas
$filtro = array("rules" => $rules, "groupOp" => "AND");

// Establece valores por defecto para los parámetros de paginación
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

// Codifica el filtro a formato JSON
$json = json_encode($filtro);

// Inicializa las clases para acceso a datos
$BonoInterno = new BonoInterno();
$BonoDetalle = new BonoDetalle();

//$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
//$bonos = json_decode($bonos);

// Crea un nuevo conjunto de reglas específicas para bonos de ruleta
$rules = [];

// Agrega reglas para filtrar solo bonos de tipo ruleta y en estado activo
array_push($rules, array("field" => "bono_detalle.tipo", "data" => "BONORULETA", "op" => "eq"));
array_push($rules, array("field" => "bono_interno.estado", "data" => "A", "op" => "eq"));

// Configura el nuevo filtro con las reglas específicas
$filtro = array("rules" => $rules, "groupOp" => "AND");

// Establece nuevamente valores por defecto para los parámetros de paginación
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

// Codifica el filtro a formato JSON
$json = json_encode($filtro);

// Ejecuta la consulta para obtener los detalles de bonos de ruleta
$bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, TRUE);

// Decodifica el resultado de la consulta
$bonodetalles = json_decode($bonodetalles);

// Inicializa el array para almacenar los resultados procesados
$final = [];

// Procesa cada registro de bono de ruleta
foreach ($bonodetalles->data as $key => $value) {

    // Crea un array para almacenar los datos del bono actual
    $array = [];

    // Asigna los valores básicos del bono
    $array["Id"] = $value->{"bono_interno.bono_id"};
    $array["Name"] = $value->{"bono_interno.nombre"};
    $array["Description"] = $value->{"bono_interno.descripcion"};
    $array["BeginDate"] = $value->{"bono_interno.fecha_inicio"};
    $array["EndDate"] = $value->{"bono_interno.fecha_fin"};
    $array["ProductTypeId"] = $value->{"bono_detalle.valor"};
    $array["TypeId"] = $value->{"bono_interno.tipo"};

    // Determina el tipo de bono y asigna la información correspondiente
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

    // Agrega el bono procesado al array final
    array_push($final, $array);
}

// Prepara la respuesta con los resultados obtenidos
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Count"] = $bonodetalles->count[0]->{".count"};

// Asigna los bonos procesados al resultado de la respuesta
$response["Result"] = $final;