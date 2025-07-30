<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;

/**
 * Procesa datos JSON de entrada para obtener detalles de sorteos.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->StartTimeLocal Fecha de inicio del rango.
 * @param string $params->EndTimeLocal Fecha de fin del rango.
 * @param string $params->TypeId Tipo de sorteo.
 * @param string $params->Id ID del sorteo.
 * @param string $params->BeginDate Fecha inicial del rango.
 * @param string $params->EndDate Fecha final del rango.
 * @param int $params->Limit Límite de filas a obtener.
 * @param int $params->Offset Número de filas a omitir.
 * @param int $params->OrderedItem Elemento para ordenar los resultados.
 * @param int $params->StateType Tipo de estado del sorteo.
 * @param string $params->State Estado del sorteo.
 * @param string $params->Country País para filtrar resultados.
 * @param int $params->draw Número de sorteo.
 * @param int $params->length Longitud de los resultados.
 * @param int $params->start Inicio de los resultados.
 *
 *
 * @return array $response Respuesta generada con:
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (e.g., "success").
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Lista de errores del modelo.
 *  - int $Count Número total de resultados.
 *  - int $CountFiltered Número de resultados filtrados.
 *  - array $Data Datos procesados con detalles de sorteos.
 */

/* obtiene y decodifica datos JSON de una entrada en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;

/* asigna valores de parámetros a variables, posiblemente para su posterior uso. */
$Id = $params->Id;//OK en este modulo no nos filtraba la informacion por id

$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

$FromDateLocal = $params->EndDate;

/* Asignación de parámetros de fecha, límite, elemento ordenado, desplazamiento y tipo de estado. */
$ToDateLocal = $params->BeginDate;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$StateType = $params->StateType;

/* establece reglas de filtrado basado en una fecha proporcionada. */
$State = $params->State;
$rules = [];


if ($ToDateLocal != "") {
    $ToDateLocal = str_replace("T", " ", $ToDateLocal);
    $ToDateLocal = date('Y-m-d 23:59:59', strtotime($ToDateLocal));
    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "le"));
}


/* formatea una fecha y la agrega a un array de reglas. */
if ($FromDateLocal != "") {
    $FromDateLocal = str_replace("T", " ", $FromDateLocal);
    $FromDateLocal = date('Y-m-d 00:00:00', strtotime($FromDateLocal));
    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "ge"));
}


// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* Condicionales que añaden reglas a un array según sesión y tipo de estado. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

if ($StateType == 1) {

} else {
    /* Es una estructura condicional vacía que no realiza ninguna acción. */


}


/*if ($TypeId != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$TypeId", "op" => "eq"));
}*/


/* Define un filtro y establece valores predeterminados para variables vacías. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un límite máximo de filas si no se proporciona uno. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

/* asigna valores de parámetros y verifica si la variable "start" no está vacía. */
$draw = $params->draw;
$length = $params->length;
$start = $params->start;
$Country = $params->Country;
if ($start != "") {
    $SkeepRows = $start;

}


/* establece un máximo de filas y convierte datos a formato JSON. */
if ($length != "") {
    $MaxRows = $length;

}

$json = json_encode($filtro);


/* Se inicializan objetos para manejo de sorteos y se preparan reglas. */
$SorteoInterno = new SorteoInterno();
$SorteoDetalle = new SorteoDetalle();

//$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
//$bonos = json_decode($bonos);


$rules = [];


/* Añade reglas basadas en el estado y país a una lista. */
if ($State == "A" || $State == "I") {

    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "$State", "op" => "eq"));

}

if ($ToDateLocal != "") {
    $ToDateLocal = str_replace("T", " ", $ToDateLocal);
    $ToDateLocal = date('Y-m-d 23:59:59', strtotime($ToDateLocal));
    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "le"));
}

if ($FromDateLocal != "") {
    $FromDateLocal = str_replace("T", " ", $FromDateLocal);
    $FromDateLocal = date('Y-m-d 00:00:00', strtotime($FromDateLocal));
    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "ge"));
}


/* Condiciones de reglas para el sorteo, según la sesión del usuario y país. */
array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "", "op" => "nn"));
//array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));


// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "N") {
    if (empty($_SESSION['PaisCondS']) && empty($Country)) {
        array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
    }
    if (!empty($Country)) {
        array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_detalle.valor", "data" => "$Country", "op" => "eq"));
    }
    if (!empty($_SESSION['PaisCondS'])) {
        array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_detalle.valor", "data" => $_SESSION['PaisCondS'], "op" => "eq"));
    }
}else{
    if ($_SESSION['PaisCondS'] == $Country) {
        array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_detalle.valor", "data" => $Country, "op" => "eq"));
        array_push($rules, array("field" => "sorteo_detalle.valor", "data" => $_SESSION['PaisCondS'], "op" => "eq"));
    }else if (($_SESSION['PaisCondS']) != $Country && !empty($Country)) {
        array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_detalle.valor", "data" => $Country, "op" => "eq"));
        array_push($rules, array("field" => "sorteo_detalle.valor", "data" => $_SESSION['PaisCondS'], "op" => "eq"));
    }else if (!empty($_SESSION['PaisCondS']) && empty($Country)) {
        array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_detalle.valor", "data" => $_SESSION['PaisCondS'], "op" => "eq"));
    }
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* Añade reglas a un arreglo basado en condiciones de sesión y variable. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

if ($Id != "") {
    array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $Id, "op" => "eq"));
}


/* establece un filtro y maneja valores predeterminados para variables. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un límite máximo de filas y obtiene detalles de sorteo en formato JSON. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);

$sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.sorteo_id", "asc", $SkeepRows, $MaxRows, $json, TRUE, "sorteo_interno.sorteo_id");


/* decodifica un JSON y prepara un arreglo vacío para almacenar resultados. */
$sorteodetalles = json_decode($sorteodetalles);

$final = [];


foreach ($sorteodetalles->data as $key => $value) {


    /* Crea un array asociativo con datos de un objeto "sorteo_interno". */
    $array = [];

    $array["Id"] = $value->{"sorteo_interno.sorteo_id"};
    $array["Name"] = $value->{"sorteo_interno.nombre"};
    $array["Description"] = $value->{"sorteo_interno.descripcion"};
    $array["BeginDate"] = $value->{"sorteo_interno.fecha_inicio"};

    /* asigna valores de un objeto a un array asociativo en PHP. */
    $array["EndDate"] = $value->{"sorteo_interno.fecha_fin"};
    $array["ProductTypeId"] = $value->{"sorteo_detalle.valor"};
    $array["TypeId"] = $value->{"sorteo_interno.tipo"};

    $array["State"] = $value->{"sorteo_interno.estado"};

    switch ($value->{"sorteo_interno.tipo"}) {
        case "2":
            /* asigna información de tipo "Bono Deposito" a un arreglo. */

            $array["Type"] = array(
                "Id" => $value->{"sorteo_interno.tipo"},
                "Name" => "Bono Deposito",
                "TypeId" => $value->{"sorteo_interno.tipo"}
            );

            break;

        case "3":
            /* Configura un array con detalles de un tipo específico de sorteo. */

            $array["Type"] = array(
                "Id" => $value->{"sorteo_interno.tipo"},
                "Name" => "Bono No Deposito",
                "TypeId" => $value->{"sorteo_interno.tipo"}
            );

            break;

        case "4":
            /* asigna información sobre un tipo de sorteo a un arreglo. */

            $array["Type"] = array(
                "Id" => $value->{"sorteo_interno.tipo"},
                "Name" => "Bono Cash",
                "TypeId" => $value->{"sorteo_interno.tipo"}
            );

            break;


        case "6":
            /* Asignación de un tipo de "Freebet" a un array basado en un valor específico. */

            $array["Type"] = array(
                "Id" => $value->{"sorteo_interno.tipo"},
                "Name" => "Freebet",
                "TypeId" => $value->{"sorteo_interno.tipo"}
            );

            break;


    }


    /* Añade el contenido de $array al final de $final en PHP. */
    array_push($final, $array);
}


/* configura respuestas de éxito y cuenta detalles en un objeto. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Count"] = $sorteodetalles->count[0]->{".count"};
$response["CountFiltered"] = $sorteodetalles->count[0]->{".count"};


/* Asigna el valor de $final a la clave "Data" en el arreglo $response. */
$response["Data"] = $final;
