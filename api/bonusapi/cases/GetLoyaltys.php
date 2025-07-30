<?php


use Backend\dto\LealtadDetalle;
use Backend\dto\LealtadInterna;

/**
 * Este script procesa datos relacionados con lealtades, aplicando filtros y reglas
 * para generar una respuesta estructurada con información detallada.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes parámetros:
 * @param string $params->StartTimeLocal Fecha de inicio en formato local.
 * @param string $params->EndTimeLocal Fecha de fin en formato local.
 * @param string $params->TypeId Identificador del tipo de lealtad.
 * @param int $params->Limit Límite de filas a consultar.
 * @param int $params->Offset Desplazamiento para la consulta.
 * @param string $params->OrderedItem Elemento por el cual ordenar.
 * @param int $params->StateType Tipo de estado.
 * @param string $params->State Estado de la lealtad (A, I, etc.).
 * @param string $params->Country País asociado a la consulta.
 * @param int $params->draw Parámetro para DataTables.
 * @param int $params->length Número de filas por página.
 * @param int $params->start Índice inicial para la consulta.
 * 
 *
 * @return array $response Respuesta estructurada con los siguientes elementos:
 *  - HasError: Indica si ocurrió un error (false si no hay errores).
 *  - AlertType: Tipo de alerta (success, error, etc.).
 *  - AlertMessage: Mensaje de alerta.
 *  - ModelErrors: Lista de errores de validación.
 *  - Count: Número total de registros encontrados.
 *  - Data: Array con los datos procesados de lealtades.
 *
 * @throws Exception Si ocurre un error al obtener detalles de lealtad o imágenes.
 */



/* asigna fechas y un identificador de tipo desde parámetros dados. */
$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;

$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;


/* Se define un límite y un desplazamiento para manejar filas de datos. */
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$StateType = $params->StateType;

$State = $params->State;


/* Asignación de país y definición de reglas según el tipo de estado. */
$Country = $params->Country;
$rules = [];

if ($StateType == 1) {

} else {
    /* Este bloque de código representa una estructura condicional vacía en programación. */


}

/*if ($TypeId != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$TypeId", "op" => "eq"));
}*/


/* Se configura un filtro y se inicializan variables si están vacías. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un valor predeterminado para $MaxRows y captura parámetros de entrada. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$draw = $params->draw;
$length = $params->length;

/* Asigna el valor de inicio a $SkeepRows si no está vacío. */
$start = $params->start;

if ($start != "") {
    $SkeepRows = $start;

}


/* asigna un valor a $MaxRows y convierte $filtro a formato JSON. */
if ($length != "") {
    $MaxRows = $length;

}

$json = json_encode($filtro);


/* Se crean instancias de clases y se definen reglas vacías en el código. */
$LealtadInterna = new LealtadInterna();
$LealtadDetalle = new LealtadDetalle();

//$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
//$bonos = json_decode($bonos);


$rules = [];


/* Agrega reglas a un array según condiciones de estado y país. */
if ($State == "A" || $State == "I") {

    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "$State", "op" => "eq"));

}

if ($Country != "") {
    array_push($rules, array("field" => "lealtad_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
    array_push($rules, array("field" => "lealtad_detalle.valor", "data" => "$Country", "op" => "eq"));

} else {
    /* Agrega una regla al array si no se cumple una condición. */

    array_push($rules, array("field" => "lealtad_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
}


// Si el usuario esta condicionado por País

/* Valida condiciones para agregar reglas basadas en la sesión del usuario. */
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "lealtad_interna.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


/* Se configura un filtro y se inicializan variables si están vacías. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un máximo de filas y genera un JSON para una consulta. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);

$lealtaddetalles = $LealtadDetalle->getLealtadDetallesCustom("lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.orden DESC, lealtad_interna.lealtad_id", "desc", $SkeepRows, $MaxRows, $json, TRUE);


/* convierte datos JSON en un array estructurado, extrayendo información de lealtad. */
$lealtaddetalles = json_decode($lealtaddetalles);


$final = [];


foreach ($lealtaddetalles->data as $key => $value) {

    /*Asignación de valores a objetos de respuesta*/
    $array = [];

    $array["Id"] = $value->{"lealtad_interna.lealtad_id"};
    $array["Name"] = $value->{"lealtad_interna.nombre"};
    $array["Description"] = $value->{"lealtad_interna.descripcion"};
    $array["BeginDate"] = $value->{"lealtad_interna.fecha_inicio"};
    $array["EndDate"] = $value->{"lealtad_interna.fecha_fin"};
    $array["Order"] = $value->{"lealtad_interna.orden"};
    $array["ProductTypeId"] = $value->{"lealtad_detalle.valor"};
    $array["TypeId"] = $value->{"lealtad_interna.tipo"};

    $array["State"] = $value->{"lealtad_interna.estado"};


    /*Verifica si es una lealtad física o de tipo bono*/
    if ($value->{"lealtad_interna.bono_id"} == "0") {

        $array["Type"] = "Fisico";
    } else {
        $array["Type"] = "Bono Freebet";
    }

    switch ($value->{"lealtad_interna.tipo"}) {
        case "2":
            /*Lealtad depósito*/
            $array["Type"] = array(
                "Id" => $value->{"lealtad_interna.tipo"},
                "Name" => "lealtad Deposito",
                "TypeId" => $value->{"lealtad_interna.tipo"}
            );

            break;

        case "3":
            /*Lealtad NO depósito*/
            $array["Type"] = array(
                "Id" => $value->{"lealtad_interna.tipo"},
                "Name" => "Lealtad No Deposito",
                "TypeId" => $value->{"lealtad_interna.tipo"}
            );

            break;

        case "4":
            /*Lealtad cashback*/
            $array["Type"] = array(
                "Id" => $value->{"lealtad_interna.tipo"},
                "Name" => "lealtad Cash",
                "TypeId" => $value->{"lealtad_interna.tipo"}
            );

            break;


        case "6":
            /*Lealtad FreeBet*/
            $array["Type"] = array(
                "Id" => $value->{"lealtad_interna.tipo"},
                "Name" => "Freebet",
                "TypeId" => $value->{"lealtad_interna.tipo"}
            );

            break;


    }

    try {
        /*Obtención de la vertical*/
        $LealtadDetalleVerticalProducto = new LealtadDetalle('', $value->{"lealtad_interna.lealtad_id"}, 'VERTICALREGALO');
        $array["TypeProduct"] = $LealtadDetalleVerticalProducto->valor;
    } catch (Exception $e) {
        if ($e->getCode() != 21) throw $e;
        $array["TypeProduct"] = '-';
    }

    try {
        /*Obtención imagen de la lealtad*/
        $LealtadDetalle = new LealtadDetalle("", $value->{"lealtad_interna.lealtad_id"}, "IMGPPALURL");
        $valorImagen = $LealtadDetalle->valor;

        $array["MainImageURL"] = $valorImagen;
    } catch (Exception $e) {
        $array["MainImageURL"] = null; // O asignar un valor por defecto
    }


    array_push($final, $array);
}


/* Código define una respuesta estructurada con información sobre errores, alertas y datos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Count"] = $lealtaddetalles->count[0]->{".count"};

$response["Data"] = $final;