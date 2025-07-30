<?php


use Backend\dto\SorteoDetalle2;
use Backend\dto\SorteoInterno2;


/**
 * Procesa datos JSON de entrada para obtener detalles de sorteos en betshops.
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
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;

/* Asignación de valores a variables de parámetros, con posible error en duplicación. */
$Id = $params->Id;//OK en este modulo no nos filtraba la informacion por id

$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

$FromDateLocal = $params->EndDate;

/* Asignación de parámetros para manejar fechas, límites y filas en una consulta. */
$ToDateLocal = $params->BeginDate;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$StateType = $params->StateType;

/* establece reglas de filtrado basadas en una fecha proporcionada. */
$State = $params->State;


$rules = [];

if ($ToDateLocal != "") {
    $ToDateLocal = str_replace("T", " ", $ToDateLocal);
    $ToDateLocal = date('Y-m-d 23:59:59', strtotime($ToDateLocal));
    array_push($rules, array("field" => "sorteo_interno2.fecha_fin", "data" => "$ToDateLocal", "op" => "le"));
}


/* formatea una fecha y la agrega a un arreglo de reglas. */
if ($FromDateLocal != "") {
    $FromDateLocal = str_replace("T", " ", $FromDateLocal);
    $FromDateLocal = date('Y-m-d 00:00:00', strtotime($FromDateLocal));
    array_push($rules, array("field" => "sorteo_interno2.fecha_inicio", "data" => "$FromDateLocal", "op" => "ge"));
}


if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* verifica una condición de sesión para agregar reglas a un array. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "sorteo_interno2.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


if ($StateType == 1) {

} else {
    /* El bloque "else" se ejecuta si la condición anterior es falsa. */


}


/* configura un filtro y establece valores predeterminados para variables. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* asigna un valor máximo predeterminado si no se especifica. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$draw = $params->draw;
$length = $params->length;

/* asigna valores de parámetros a variables y verifica si "start" no está vacío. */
$start = $params->start;
$Country = $params->Country;
if ($start != "") {
    $SkeepRows = $start;

}


/* verifica si $length no está vacío y luego codifica $filtro en JSON. */
if ($length != "") {
    $MaxRows = $length;

}

$json = json_encode($filtro);


/* Se crean reglas para validar el estado de un sorteo interno. */
$SorteoInterno = new SorteoInterno2();
$SorteoDetalle = new SorteoDetalle2();


$rules = [];

if ($State == "A" || $State == "I") {

    array_push($rules, array("field" => "sorteo_interno2.estado", "data" => "$State", "op" => "eq"));
}


/* Condiciona reglas basadas en si el país está definido o no. */
if ($Country != "") {
    array_push($rules, array("field" => "sorteo_detalle2.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_detalle2.valor", "data" => "$Country", "op" => "eq"));

} else {
    array_push($rules, array("field" => "sorteo_detalle2.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
}


/* Convierte fechas y agrega reglas para filtrar por rango en un array. */
if ($ToDateLocal != "") {
    $ToDateLocal = str_replace("T", " ", $ToDateLocal);
    $ToDateLocal = date('Y-m-d 23:59:59', strtotime($ToDateLocal));
    array_push($rules, array("field" => "sorteo_interno2.fecha_fin", "data" => "$ToDateLocal", "op" => "le"));
}

if ($FromDateLocal != "") {
    $FromDateLocal = str_replace("T", " ", $FromDateLocal);
    $FromDateLocal = date('Y-m-d 00:00:00', strtotime($FromDateLocal));
    array_push($rules, array("field" => "sorteo_interno2.fecha_inicio", "data" => "$FromDateLocal", "op" => "ge"));
}


/* añade reglas de filtrado basadas en condiciones de usuario y país. */
array_push($rules, array("field" => "sorteo_detalle2.tipo", "data" => "", "op" => "nn"));
//array_push($rules, array("field" => "sorteo_interno2.estado", "data" => "A", "op" => "eq"));


// Si el usuario esta condicionado por País
// if ($_SESSION['PaisCond'] == "S") {
//     array_push($rules, array("field" => "sorteo_detalle2.tipo", "data" => 'CONDPAISUSER', "op" => "eq"));
//     array_push($rules, array("field" => "sorteo_detalle2.valor", "data" => $_SESSION['pais_id'], "op" => "eq"));

// }else{
//     array_push($rules, array("field" => "sorteo_detalle2.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
// }
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "sorteo_interno2.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


/* Condición para agregar reglas y definir filtros en un array basado en $Id. */
if ($Id != "") {
    array_push($rules, array("field" => "sorteo_interno2.sorteo2_id", "data" => $Id, "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}


/* Convierte un filtro en JSON y obtiene detalles de un sorteo decodificados. */
$json = json_encode($filtro);


$sorteodetalles = $SorteoDetalle->getSorteoDetalles2Custom(" sorteo_detalle2.*,sorteo_interno2.* ", "sorteo_interno2.sorteo2_id", "asc", $SkeepRows, $MaxRows, $json, TRUE, "sorteo_interno2.sorteo2_id");

$sorteodetalles = json_decode($sorteodetalles);


/* Se inicializa un arreglo vacío llamado "final" en PHP. */
$final = [];

foreach ($sorteodetalles->data as $key => $value) {

    /* crea un array asociativo con datos de un objeto. */
    $array = [];


    /* Extrae datos de un objeto y los asigna a un array asociativo. */
    $array["Id"] = $value->{"sorteo_interno2.sorteo2_id"};
    $array["Name"] = $value->{"sorteo_interno2.nombre"};
    $array["Description"] = $value->{"sorteo_interno2.descripcion"};
    $array["BeginDate"] = $value->{"sorteo_interno2.fecha_inicio"};
    $array["EndDate"] = $value->{"sorteo_interno2.fecha_fin"};
    $array["ProductTypeId"] = $value->{"sorteo_detalle2.valor"};
    $array["TypeId"] = $value->{"sorteo_interno2.tipo"};

    $array["State"] = $value->{"sorteo_interno2.estado"};

    switch ($value->{"sorteo_interno2.tipo"}) {
        case "2":
            /* Asigna tipo de bono de depósito a un array según la condición "2". */

            $array["Type"] = array(
                "Id" => $value->{"sorteo_interno2.tipo"},
                "Name" => "Bono Deposito",
                "TypeId" => $value->{"sorteo_interno2.tipo"}
            );

            break;

        case "3":
            /* Asigna datos de un sorteo a un array para el tipo "Bono No Deposito". */

            $array["Type"] = array(
                "Id" => $value->{"sorteo_interno2.tipo"},
                "Name" => "Bono No Deposito",
                "TypeId" => $value->{"sorteo_interno2.tipo"}
            );

            break;

        case "4":
            /* Asignación de valores a un arreglo usando datos de un objeto en PHP. */

            $array["Type"] = array(
                "Id" => $value->{"sorteo_interno2.tipo"},
                "Name" => "Bono Cash",
                "TypeId" => $value->{"sorteo_interno2.tipo"}
            );

            break;


        case "6":
            /* Código asigna un tipo de apuesta "Freebet" a un arreglo asociativo. */

            $array["Type"] = array(
                "Id" => $value->{"sorteo_interno2.tipo"},
                "Name" => "Freebet",
                "TypeId" => $value->{"sorteo_interno2.tipo"}
            );

            break;

    }


    /* Agrega elementos de $array al final de $final usando la función array_push. */
    array_push($final, $array);

}


/* inicializa un arreglo de respuesta sin errores, incluyendo detalles de conteo. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Count"] = $sorteodetalles->count[0]->{".count"};
$response["CountFiltered"] = $sorteodetalles->count[0]->{".count"};


/* Asignación del valor de $final al índice "Data" del array $response. */
$response["Data"] = $final;
