<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\SorteoDetalle;
use Backend\dto\JackpotInterno;

/**
 * Obtiene la lista de jackpots según los filtros proporcionados.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->StartTimeLocal Fecha de inicio en formato local.
 * @param string $params->EndTimeLocal Fecha de fin en formato local.
 * @param int $params->TypeId ID del tipo de jackpot.
 * @param int $params->Id ID del jackpot.
 * @param string $params->BeginDate Fecha de inicio.
 * @param string $params->EndDate Fecha de fin.
 * @param int $params->Limit Número máximo de filas a recuperar.
 * @param int $params->OrderedItem Elemento por el cual ordenar.
 * @param int $params->Offset Número de páginas a omitir.
 * @param string $params->State Estado del jackpot (e.g., "A", "I", "G").
 * @param string $params->Country País del jugador.
 *
 * @return array $response Respuesta estructurada con:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Lista de jackpots procesados.
 *  - Count (int): Número total de jackpots.
 *  - CountFiltered (int): Número de jackpots filtrados.
 */

/* obtiene y decodifica datos JSON de una solicitud HTTP en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;

/* asigna valores de parámetros a variables, posiblemente para filtros de fechas. */
$Id = $params->Id;//OK en este modulo no nos filtraba la informacion por id

$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

$FromDateLocal = $params->EndDate;

/* asigna parámetros para manejo de fechas, límites y estado de elementos. */
$ToDateLocal = $params->BeginDate;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$State = $params->State;

/* Genera una regla de fecha fin si $ToDateLocal no está vacío. */
$rules = [];

if ($ToDateLocal != "") {
    $ToDateLocal = str_replace("T", " ", $ToDateLocal);
    $ToDateLocal = date('Y-m-d 23:59:59', strtotime($ToDateLocal));
    array_push($rules, array("field" => "jackpot_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "le"));
}

/* formatea una fecha y la agrega a un array de reglas. */
if ($FromDateLocal != "") {
    $FromDateLocal = str_replace("T", " ", $FromDateLocal);
    $FromDateLocal = date('Y-m-d 00:00:00', strtotime($FromDateLocal));
    array_push($rules, array("field" => "jackpot_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "ge"));
}

// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* Condicional que agrega reglas a un filtro basado en la sesión y un tipo. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "jackpot_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

/*filtro inicial*/
$filtro = array("rules" => $rules, "groupOp" => "AND");

/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

/* asigna un valor predeterminado a $MaxRows si está vacío. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

/* asigna valores de parámetros y verifica si el inicio no está vacío. */
$draw = $params->draw;
$length = $params->length;
$start = $params->start;
$Country = $params->Country;
if ($start != "") {
    $SkeepRows = $start;
}

/* Establece un valor máximo de filas y convierte un filtro a formato JSON. */
if ($length != "") {
    $MaxRows = $length;
}

$json = json_encode($filtro);

/* Se crea un objeto JackpotInterno y se inicializa un array de reglas vacío. */
$JackpotInterno = new JackpotInterno();

$rules = [];

/* Se crea una consulta SQL con condiciones basadas en el estado "A". */
$joins = [];
$select = "DISTINCT jackpot_interno.jackpot_id, jackpot_interno.*";

if ($State == "A") {
    array_push($rules, array("field" => "jackpot_interno.estado", "data" => "$State", "op" => "eq"));
} elseif ($State == 'I') {
    /* Condiciona consultas a la base de datos dependiendo del estado 'I'. */

    $joins[] = (object)['type' => 'LEFT', 'table' => 'usuario_jackpot', 'on' => 'jackpot_interno.jackpot_id = usuario_jackpot.jackpot_id AND usuario_jackpot.valor_premio > 0'];
    $rules[] = ['field' => 'jackpot_interno.estado', 'data' => "$State", 'op' => 'eq'];
    $rules[] = ['field' => 'usuario_jackpot.usujackpot_id', 'data' => "$State", 'op' => 'iu'];

    $select .= ', usuario_jackpot.usujackpot_id';
} elseif ($State == 'G') {
    /* Código que agrega condiciones y uniones para consultas en base de datos según estado. */

    $joins[] = (object)['type' => 'LEFT', 'table' => 'usuario_jackpot', 'on' => 'jackpot_interno.jackpot_id = usuario_jackpot.jackpot_id AND usuario_jackpot.valor_premio > 0'];
    $rules[] = ['field' => 'jackpot_interno.estado', 'data' => "I", 'op' => 'eq'];
    $rules[] = ['field' => 'usuario_jackpot.usujackpot_id', 'data' => "$State", 'op' => 'dn'];

    $select .= ', usuario_jackpot.usujackpot_id';
}

// Si el usuario esta condicionado por País

/* asigna reglas basadas en la variable $Country y la sesión del usuario. */
if ($Country != "") {
    array_push($rules, array("field" => "jackpot_detalle.tipo", "data" => 'CONDPAISUSER', "op" => "eq"));
    array_push($rules, array("field" => "jackpot_detalle.valor", "data" => "$Country", "op" => "eq"));
} else {
    array_push($rules, array("field" => "jackpot_detalle.tipo", "data" => 'CONDPAISUSER', "op" => "eq"));
    array_push($rules, array("field" => "jackpot_detalle.valor", "data" => $_SESSION['pais_id'], "op" => "eq"));
}

/* Convierte fechas y agrega reglas de comparación a un arreglo según condiciones. */
if ($ToDateLocal != "") {
    $ToDateLocal = str_replace("T", " ", $ToDateLocal);
    $ToDateLocal = date('Y-m-d 23:59:59', strtotime($ToDateLocal));
    array_push($rules, array("field" => "jackpot_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "le"));
}

if ($FromDateLocal != "") {
    $FromDateLocal = str_replace("T", " ", $FromDateLocal);
    $FromDateLocal = date('Y-m-d 00:00:00', strtotime($FromDateLocal));
    array_push($rules, array("field" => "jackpot_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "ge"));
}

/* agrega reglas de validación basadas en condiciones de sesión del usuario. */
array_push($rules, array("field" => "jackpot_interno.tipo", "data" => "", "op" => "dn"));

// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "jackpot_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

/* Se generan reglas de filtrado basadas en condiciones específicas y se gestionan filas. */
if ($Id != "") {
    array_push($rules, array("field" => "jackpot_interno.jackpot_id", "data" => $Id, "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

/* Inicializa variables $OrderedItem y $MaxRows si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

/* Codifica un filtro a JSON, recupera detalles del jackpot y los decodifica. */
$json = json_encode($filtro);

$jackpotdetalles = $JackpotInterno->getJackpotCustom($select, "jackpot_interno.jackpot_id", "asc", $SkeepRows, $MaxRows, $json, TRUE, $joins);

$jackpotdetalles = json_decode($jackpotdetalles);

/* Se inicializa un arreglo vacío llamado "final" en PHP. */
$final = [];

foreach ($jackpotdetalles->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"jackpot_interno.jackpot_id"};
    $array["Name"] = $value->{"jackpot_interno.nombre"};
    $array["Description"] = $value->{"jackpot_interno.descripcion"};
    $array["BeginDate"] = $value->{"jackpot_interno.fecha_inicio"};
    $array["EndDate"] = $value->{"jackpot_interno.fecha_fin"};
    $array["ProductTypeId"] = $value->{"jackpot_interno.tipo"};
    $array["imagen"] = $value->{"jackpot_interno.imagen"};
    $array["imagen2"] = $value->{"jackpot_interno.imagen2"};
    $array["gif"] = $value->{"jackpot_interno.gif"};
    $array["gif2"] = $value->{"jackpot_interno.gif2"};
    $array["videoMobile"] = $value->{"jackpot_interno.video_mobile"};
    $array["videoDesktop"] = $value->{"jackpot_interno.video_desktop"};
    $array["TypeId"] = $value->{"jackpot_interno.tipo"};
    $array["State"] = $value->{"jackpot_interno.estado"};

    switch ($value->{"jackpot_interno.tipo"}) {
        case "1":
            $array["Type"] = array(
                "Id" => $value->{"jackpot_interno.tipo"},
                "Name" => "Casino",
                "TypeId" => $value->{"jackpot_interno.tipo"}
            );
        case "0":
            $array["Type"] = array(
                "Id" => $value->{"jackpot_interno.tipo"},
                "Name" => "Sportbook",
                "TypeId" => $value->{"jackpot_interno.tipo"}
            );

            break;
    }

    /*Remoción sanitización parámetros*/
    $sanitizedQuotesPattern = '#\\\{1,}(\')#i';
    $array["imagen"] = preg_replace($sanitizedQuotesPattern, "'", $array["imagen"]);
    $array["imagen2"] = preg_replace($sanitizedQuotesPattern, "'", $array["imagen2"]);
    $array["gif"] = preg_replace($sanitizedQuotesPattern, "'", $array["gif"]);
    $array["gif2"] = preg_replace($sanitizedQuotesPattern, "'", $array["gif2"]);
    $array["videoMobile"] = preg_replace($sanitizedQuotesPattern, "'", $array["videoMobile"]);
    $array["videoDesktop"] = preg_replace($sanitizedQuotesPattern, "'", $array["videoDesktop"]);

    array_push($final, $array);
}

/*Se genera formato de respuesta*/
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Count"] = $jackpotdetalles->count[0]->{".count"};
$response["CountFiltered"] = $jackpotdetalles->count[0]->{".count"};

$response["Data"] = $final;
