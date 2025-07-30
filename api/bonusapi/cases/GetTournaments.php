<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\TorneoDetalle;
use Backend\dto\TorneoInterno;


/**
 * Este script procesa una solicitud HTTP para obtener detalles de torneos.
 * Recibe parámetros en formato JSON, aplica filtros y devuelve una respuesta estructurada.
 *
 * @param object $params Objeto JSON con los siguientes parámetros:
 * @param string $params->StartTimeLocal Fecha y hora de inicio en formato local.
 * @param string $params->EndTimeLocal Fecha y hora de fin en formato local.
 * @param int $params->TypeId Identificador del tipo de torneo.
 * @param int $params->Id Identificador único del torneo.
 * @param string $params->BeginDate Fecha de inicio del torneo.
 * @param string $params->EndDate Fecha de fin del torneo.
 * @param int $params->Limit Número máximo de filas a devolver.
 * @param int $params->OrderedItem Elemento por el cual ordenar los resultados.
 * @param int $params->Offset Número de filas a omitir.
 * @param int $params->StateType Tipo de estado del torneo.
 * @param string $params->State Estado del torneo ('A' para activo, 'I' para inactivo).
 * @param string $params->Country País asociado al torneo.
 * @param int $params->draw Parámetro para DataTables.
 * @param int $params->length Número de filas por página.
 * @param int $params->start Índice inicial para la paginación.
 * 
 *
 * @return array $response Respuesta estructurada con los siguientes elementos:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores de validación.
 *  - Count (int): Número total de torneos.
 *  - CountFiltered (int): Número de torneos después de aplicar filtros.
 *  - Data (array): Lista de torneos con detalles como Id, Name, Description, BeginDate, EndDate, ProductTypeId, TypeId, Order, State, Type, y TypeRule.
 */

/* recibe y decodifica datos JSON de una solicitud HTTP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;

/* asigna valores de parámetros a variables, sin filtrar por ID. */
$Id = $params->Id;//OK en este modulo no nos filtraba la informacion por id

$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

$FromDateLocal = $params->BeginDate;

/* Asignación de parámetros para gestionar fechas, límites y estados en un conjunto de datos. */
$ToDateLocal = $params->EndDate;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$StateType = $params->StateType;

/* Código que asigna valores de estado y país según parámetros y sesión. */
$State = $params->State;
$Country = $params->Country;
$rules = [];
if ($_SESSION['PaisCond'] == "S") {
    $Country = $_SESSION['pais_id'];

}

/* manipula fechas y agrega reglas a un arreglo basado en condición. */
if ($ToDateLocal != "") {
    $ToDateLocal = str_replace("T", " ", $ToDateLocal);
    array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "le"));
}

if ($FromDateLocal != "") {
    $FromDateLocal = str_replace("T", " ", $FromDateLocal);
    array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "ge"));
}


// Si el usuario esta condicionado por País

/* Condiciona reglas basadas en el país y el estado global del usuario. */
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "torneo_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


/* evalúa una condición y define un filtro con reglas específicas. */
if ($StateType == 1) {

} else {

}


/*if ($TypeId != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$TypeId", "op" => "eq"));
}*/

$filtro = array("rules" => $rules, "groupOp" => "AND");


/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Asigna un valor por defecto a $MaxRows y obtiene parámetros de entrada. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$draw = $params->draw;
$length = $params->length;

/* asigna un valor a $SkeepRows si $start no está vacío. */
$start = $params->start;

if ($start != "") {
    $SkeepRows = $start;

}


/* Condiciona el valor de $MaxRows y convierte $filtro a formato JSON. */
if ($length != "") {
    $MaxRows = $length;

}

$json = json_encode($filtro);


/* Se inicializan clases para manejar un torneo y sus detalles, junto con reglas vacías. */
$TorneoInterno = new TorneoInterno();
$TorneoDetalle = new TorneoDetalle();

//$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
//$bonos = json_decode($bonos);


$rules = [];


/* Condiciona la adición de reglas basadas en el estado y una fecha. */
if ($State == "A" || $State == "I") {

    array_push($rules, array("field" => "torneo_interno.estado", "data" => "$State", "op" => "eq"));

}

if ($ToDateLocal != "") {
    $ToDateLocal = str_replace("T", " ", $ToDateLocal);
    array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "le"));
}


/* agrega reglas para filtrar fechas y tipos en un torneo. */
if ($FromDateLocal != "") {
    $FromDateLocal = str_replace("T", " ", $FromDateLocal);
    array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "ge"));
}


array_push($rules, array("field" => "torneo_detalle.tipo", "data" => "", "op" => "nn"));
//array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));


// Si el usuario esta condicionado por País

/* agrega reglas basadas en si $Country está vacío o no. */
if ($Country != '') {
    array_push($rules, array("field" => "torneo_detalle.tipo", "data" => 'CONDPAISUSER', "op" => "eq"));
    array_push($rules, array("field" => "torneo_detalle.valor", "data" => $Country, "op" => "eq"));

} else {
    array_push($rules, array("field" => "torneo_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));

}
// Si el usuario esta condicionado por el mandante y no es de Global

/* Se agregan reglas a un array dependiendo de condiciones de sesión e identificación. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "torneo_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

if ($Id != "") {
    array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $Id, "op" => "eq"));
}


/* Inicializa un filtro y establece valores predeterminados para variables vacías. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un límite de filas y convierte un filtro a JSON. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);

$torneodetalles = $TorneoDetalle->getTorneoDetallesCustom("
    torneo_interno.torneo_id,
    torneo_interno.descripcion,
    torneo_interno.nombre as titulotorneo,
    torneo_interno.fecha_inicio,
    torneo_interno.fecha_fin,
    torneo_interno.reglas,
    torneo_interno.cantidad_torneos,
    torneo_interno.tipo,
    torneo_interno.maximo_torneos,
    torneo_interno.orden,
    torneo_interno.estado,

    torneo_detalle.*", "torneo_interno.torneo_id", "asc", $SkeepRows, $MaxRows, $json, TRUE, "torneo_interno.torneo_id");


/* transforma datos JSON en un arreglo estructurado para torneos. */
$torneodetalles = json_decode($torneodetalles);

$final = [];


foreach ($torneodetalles->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"torneo_interno.torneo_id"};
    $array["Name"] = $value->{"torneo_interno.titulotorneo"};
    $array["Description"] = $value->{"torneo_interno.descripcion"};
    $array["BeginDate"] = $value->{"torneo_interno.fecha_inicio"};
    $array["EndDate"] = $value->{"torneo_interno.fecha_fin"};
    $array["ProductTypeId"] = $value->{"torneo_detalle.valor"};
    $array["TypeId"] = $value->{"torneo_interno.tipo"};
    $array["Order"] = $value->{"torneo_interno.orden"};

    $array["State"] = $value->{"torneo_interno.estado"};

    switch ($value->{"torneo_interno.tipo"}) {
        case "2":
            $array["Type"] = array(
                "Id" => $value->{"torneo_interno.tipo"},
                "Name" => "Bono Deposito",
                "TypeId" => $value->{"torneo_interno.tipo"}
            );

            break;

        case "3":
            $array["Type"] = array(
                "Id" => $value->{"torneo_interno.tipo"},
                "Name" => "Bono No Deposito",
                "TypeId" => $value->{"torneo_interno.tipo"}
            );

            break;

        case "4":
            $array["Type"] = array(
                "Id" => $value->{"torneo_interno.tipo"},
                "Name" => "Bono Cash",
                "TypeId" => $value->{"torneo_interno.tipo"}
            );

            break;


        case "6":
            $array["Type"] = array(
                "Id" => $value->{"torneo_interno.tipo"},
                "Name" => "Freebet",
                "TypeId" => $value->{"torneo_interno.tipo"}
            );

            break;


    }

    try {
        $TorneoDetalleVisibilidad = new TorneoDetalle('', $value->{"torneo_interno.torneo_id"}, 'VISIBILIDAD');
        $array["TypeRule"] = $TorneoDetalleVisibilidad->valor;
    } catch (Exception $e) {
        if ($e->getCode() != 21) throw $e;
        $array["TypeRule"] = 0;
    }

    array_push($final, $array);
}


/* Código PHP para responder con un mensaje de éxito y contar elementos de un torneo. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Count"] = $torneodetalles->count[0]->{".count"};
$response["CountFiltered"] = $torneodetalles->count[0]->{".count"};


/* Asigna el valor de $final a la clave "Data" en el array $response. */
$response["Data"] = $final;
