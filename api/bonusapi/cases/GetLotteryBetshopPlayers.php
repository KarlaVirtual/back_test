<?php


use Backend\dto\UsuarioSorteo2;


/**
 * Procesa los datos de una solicitud para obtener información de jugadores en una lotería.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->ResultToDate Fecha de finalización del rango.
 * @param string $params->ResultFromDate Fecha de inicio del rango.
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonos.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param string $params->PlayerName Nombre del jugador.
 * @param string $params->State Estado del jugador.
 * @param string $params->Code Código del jugador.
 * @param int $params->limit Límite de filas a obtener.
 * @param string $params->OrderedItem Elemento por el cual ordenar.
 * @param int $params->offset Número de páginas a omitir.
 * @param string $params->draw Parámetro de paginación.
 * @param int $params->length Número de filas a obtener.
 * @param int $params->start Número de filas a omitir.
 * @param array $params->columns Parámetros de columnas.
 * @param array $params->order Parámetros de ordenamiento.
 *
 * @return array $response Respuesta estructurada que incluye:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success").
 * - AlertMessage (array): Mensajes de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Datos procesados de jugadores.
 * - Result (array): Resultado final.
 * - Count (int): Número total de registros.
 */


/* recibe datos JSON y extrae fechas y definiciones de bonos. */
$params = file_get_contents('php://input');
$params = json_decode($params);

/* Se asignan parámetros a variables para procesar información de un jugador. */
$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionsIds = $params->BonusDefinitionIds;
$PlayerExternalId = $params->PlayerExternalId;
$PlayerName = $params->PlayerName;
$State = $params->State;
$Code = $params->Code;

$MaxRows = $params->limit;

/* asigna valores para la ordenación y paginación de elementos en una consulta. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->offset) * $MaxRows;


$OrderedItem = "usuario_sorteo.ususorteo_id";
$OrderedType = "desc";


/* Captura y asigna parámetros de entrada en una aplicación PHP. */
$Id = $_REQUEST["Id"];
$Id = $params->Id;

$draw = $params->draw;
$length = $params->length;
$start = $params->start;


/* Asignación de variables si las condiciones de $start y $length son verdaderas. */
if ($start != "") {
    $SkeepRows = $start;

}

if ($length != "") {
    $MaxRows = $length;
}


/* asigna columnas y orden desde parámetros, definiendo filas a omitir. */
$columns = $params->columns;
$order = $params->order;


if ($start != "") {
    $SkeepRows = $start;
}


/* establece un valor para $MaxRows si $length no está vacío. */
if ($length != "") {
    $MaxRows = $length;
}


$rules = [];


/* agrega reglas de filtrado basadas en condiciones no vacías. */
if ($PlayerExternalId != "") {
    array_push($rules, array("field" => "registro2.registro2_id", "data" => $PlayerExternalId, "op" => "eq"));
}
if ($PlayerName != "") {
    array_push($rules, array("field" => "registro2.nombre", "data" => $PlayerName, "op" => "eq"));
}

/* Añade condiciones a un arreglo de reglas basadas en valores de $Id y $State. */
if ($Id != "") {
    array_push($rules, array("field" => "sorteo_interno2.sorteo2_id", "data" => "$Id", "op" => "eq"));
}
if ($State != "") {
    array_push($rules, array("field" => "usuario_sorteo2.estado", "data" => "$State", "op" => "eq"));
}

/* agrega una regla si $Code no está vacío y verifica una condición de sesión. */
if ($Code != "") {
    array_push($rules, array("field" => "usuario_sorteo2.codigo", "data" => $Code, "op" => "eq"));
}
if ($_SESSION['PaisCond'] == "S") {

}

/* verifica una condición y añade una regla a un array si se cumple. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "sorteo_interno2.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


$filtro = array("rules" => $rules, "groupOp" => "AND");


/* inicializa variables si estan vacías, asignando valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

/* ordena elementos y convierte un filtro a formato JSON. */
$OrderedItem = 'usuario_sorteo2.posicion';
$OrderedType = 'DESC';
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);


/* Crea un objeto y obtiene datos decodificados de sorteos personalizados para usuarios. */
$usuarioSorteo2 = new UsuarioSorteo2();

$data = $usuarioSorteo2->getUsuarioSorteosCustom("usuario_sorteo2.*,registro2.*", $OrderedItem, $OrderedType, $SkeepRows, $MaxRows, $json, true, true);

$data = json_decode($data, false);
$final = [];

foreach ($data->data as $key => $value) {

    /* Se crea un arreglo con datos de un usuario ganador de un sorteo. */
    $array = [];
    $array["Id"] = $value->{"usuario_sorteo2.ususorteo2_id"};
    if ($value->{"usuario_sorteo2.estado"} == "R") {
        $array["State"] = "Ganador";
        $premioDecodificado = json_decode($value->{"usuario_sorteo2.premio"}, true);
        $array["Prize"] = $premioDecodificado[1]; // Mantener "Prize" como arreglo asociativo
        $array["DetailPrize"] = $value->{"usuario_sorteo2.premio_id"};
        $array["Code"] = $value->{"usuario_sorteo2.codigo"};
    } else {
        /* Asignación de valores a un array según condición, representan el estado y los premios. */

        $array["State"] = "Participante";
        $array["DetailPrize"] = "";
        $array["Prize"] = "";
        $array["Code"] = $value->{"usuario_sorteo2.codigo"};
    }


    /* asigna valores a un array y lo agrega a otro array. */
    $array["PlayerExternalId"] = $value->{"registro2.registro2_id"};
    $array["PlayerName"] = $value->{"registro2.nombre"};
    $array["Amount"] = $value->{"usuario_sorteo2.valor"};
    array_push($final, $array);
}


/* establece una respuesta exitosa con datos finales y sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = [];
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* asigna datos a un arreglo HTTP de respuesta. */
$response["Result"] = $final;

$response["Count"] = $data->count[0]->{".count"};