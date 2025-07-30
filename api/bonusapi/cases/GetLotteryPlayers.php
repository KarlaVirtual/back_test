<?php

use Backend\dto\UsuarioSorteo;

/**
 * Procesa datos JSON de entrada para obtener detalles de jugadores en sorteos.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->ResultToDate Fecha final del rango de resultados.
 * @param string $params->ResultFromDate Fecha inicial del rango de resultados.
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonos.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param string $params->PlayerName Nombre del jugador.
 * @param string $params->State Estado del jugador en el sorteo.
 * @param string $params->Code Código del jugador.
 * @param int $params->limit Límite de filas a obtener.
 * @param int $params->offset Número de filas a omitir.
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
 *  - array $Data Datos procesados con detalles de jugadores.
 *  - array $Result Resultado procesado, idéntico a $Data.
 *  - int $Count Número total de resultados.
 */

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionsIds = $params->BonusDefinitionIds;

$PlayerExternalId = $params->PlayerExternalId;
$PlayerName = $params->PlayerName;
$State = $params->State;
$Code = $params->Code;

$MaxRows = $params->limit;

/* Asignación de variables para ordenar elementos de una base de datos con paginación. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->offset) * $MaxRows;


$OrderedItem = "usuario_sorteo.ususorteo_id";
$OrderedType = "desc";


/* obtiene valores de parámetros para su uso posterior en una aplicación web. */
$Id = $_REQUEST["Id"];
$Id = $params->Id;

$draw = $params->draw;
$length = $params->length;
$start = $params->start;


/* Asigna valores a variables si las condiciones de inicio y longitud son verdaderas. */
if ($start != "") {
    $SkeepRows = $start;

}

if ($length != "") {
    $MaxRows = $length;
}


/* Código que define columnas y orden, y omite filas según el valor de inicio. */
$columns = $params->columns;
$order = $params->order;


if ($start != "") {
    $SkeepRows = $start;
}


/* verifica longitud y agrega reglas condicionalmente basadas en un ID de jugador. */
if ($length != "") {
    $MaxRows = $length;
}

$rules = [];

if ($PlayerExternalId != "") {
    array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => $PlayerExternalId, "op" => "eq"));
}

/* Agrega reglas a un array si el nombre del jugador y el ID son válidos. */
if ($PlayerName != "") {
    array_push($rules, array("field" => "usuario_mandante.nombres", "data" => $PlayerName, "op" => "eq"));
}
if ($Id != "") {
    array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => "$Id", "op" => "eq"));
}

/* Agrega condiciones a un array si las variables $State y $Code no están vacías. */
if ($State != "") {
    array_push($rules, array("field" => "usuario_sorteo.estado", "data" => "$State", "op" => "eq"));
}
if ($Code != "") {
    array_push($rules, array("field" => "usuario_sorteo.ususorteo_id", "data" => "0000$Code", "op" => "eq"));
}

/* verifica condiciones de sesión y añade reglas a un arreglo. */
if ($_SESSION['PaisCond'] == "S") {

}
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


/* Definición de un filtro y asignación de valores predeterminados a variables. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

/* configura parámetros para ordenar y convertir un filtro a JSON. */
$OrderedItem = 'usuario_sorteo.posicion';
$OrderedType = 'DESC';
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);


/* Se crea un objeto de usuario sorteo y se obtienen datos personalizados en formato JSON. */
$UsuarioSorteo = new UsuarioSorteo();
$data = $UsuarioSorteo->getUsuarioSorteosCustom("usuario_sorteo.*,usuario_mandante.*", $OrderedItem, $OrderedType, $SkeepRows, $MaxRows, $json, true, true);


$data = json_decode($data);

$final = [];


foreach ($data->data as $key => $value) {


    /* crea un array con detalles de un usuario ganador de un sorteo. */
    $array = [];
    $array["Id"] = $value->{"usuario_sorteo.ususorteo_id"};
    if ($value->{"usuario_sorteo.estado"} == "R") {
        $array["State"] = "Ganador";
        $array["Prize"] = json_decode(str_replace(array("\r", "\n"), '', $value->{"usuario_sorteo.premio"}));
        $array["DetailPrize"] = $value->{"usuario_sorteo.premio_id"};
        $array["Code"] = substr("0000000" . $value->{"usuario_sorteo.ususorteo_id"}, -7);
    } else {
        /* asigna valores a un array basado en una condición específica. */

        $array["State"] = "Participante";
        $array["DetailPrize"] = "";
        $array["Prize"] = "";
        $array["Code"] = substr("0000000" . $value->{"usuario_sorteo.ususorteo_id"}, -7);
    }


    /* asigna valores a un array y lo agrega a un array final. */
    $array["PlayerExternalId"] = $value->{"usuario_mandante.usuario_mandante"};

    $array["PlayerName"] = $value->{"usuario_mandante.nombres"};
    $array["Amount"] = $value->{"usuario_sorteo.valor"};
    array_push($final, $array);
}


/* inicializa una respuesta sin errores, con tipo y mensaje de éxito. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = [];
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* asigna valores a un array dependiendo de condiciones específicas. */
$response["Result"] = $final;

if ($State != "") {

    $response["Count"] = $data->count[0]->{".count"};
} else {
    /* asigna un valor de conteo a la respuesta en caso de una condición. */

    $response["Count"] = $data->count[0]->{".count"};

}

?>
