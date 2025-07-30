<?php
/**
 * Este script procesa una solicitud HTTP para obtener información sobre ruletas.
 * 
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->StartTimeLocal Fecha y hora de inicio en formato local.
 * @param string $params->EndTimeLocal Fecha y hora de fin en formato local.
 * @param int $params->TypeId ID del tipo de ruleta.
 * @param string $params->BeginDate Fecha de inicio.
 * @param string $params->EndDate Fecha de fin.
 * @param int $params->Limit Número máximo de filas a obtener.
 * @param int $params->Offset Número de filas a omitir.
 * @param string $params->OrderedItem Campo por el cual ordenar los resultados.
 * @param int $params->StateType Tipo de estado para filtrar.
 * @param string $params->State Estado de la ruleta ("A" para activo, "I" para inactivo).
 * @param int $params->draw Número de dibujo para paginación.
 * @param int $params->length Número de filas por página.
 * @param int $params->start Índice inicial para la paginación.
 * 
 * @return array $response Respuesta estructurada que contiene:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ("success", "error", etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores de modelo.
 *  - Count (int): Número total de registros.
 *  - CountFiltered (int): Número de registros filtrados.
 *  - Data (array): Datos de las ruletas, incluyendo:
 *      - Id (int): ID de la ruleta.
 *      - Name (string): Nombre de la ruleta.
 *      - Description (string): Descripción de la ruleta.
 *      - BeginDate (string): Fecha de inicio.
 *      - EndDate (string): Fecha de fin.
 *      - State (string): Estado de la ruleta.
 */

use Backend\dto\RuletaDetalle;
use Backend\dto\RuletaInterno;


/* obtiene y decodifica datos JSON de una entrada HTTP en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;


/* asigna valores de parámetros a variables para manejo de fechas y límite. */
$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

$FromDateLocal = $params->BeginDate;
$ToDateLocal = $params->EndDate;

$MaxRows = $params->Limit;

/* Se asignan parámetros y se inicializa un arreglo para reglas en el código. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$StateType = $params->StateType;
$State = $params->State;

$rules = [];


/* Convierte fechas y añade reglas según su validez a un array. */
if ($ToDateLocal != "") {
    $ToDateLocal = str_replace("T", " ", $ToDateLocal);
    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "le"));
}

if ($FromDateLocal != "") {
    $FromDateLocal = str_replace("T", " ", $FromDateLocal);
    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "ge"));
}


// Si el usuario esta condicionado por País

/* Condiciona reglas según el país y limita acceso si no es global. */
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "ruleta_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


/* Condicional para verificar el estado y crear un filtro con reglas específicas. */
if ($StateType == 1) {

} else {

}


/*if ($TypeId != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$TypeId", "op" => "eq"));
}*/

$filtro = array("rules" => $rules, "groupOp" => "AND");


/* Asignación de valores por defecto a variables si están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Configura $MaxRows a un valor predeterminado si no se proporciona; obtiene parámetros de paginación. */
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


/* Condiciona el valor de `$MaxRows` y convierte `$filtro` a JSON. */
if ($length != "") {
    $MaxRows = $length;

}

$json = json_encode($filtro);


/* Se crean instancias de dos clases y se inicializa un arreglo de reglas vacío. */
$RuletaInterno = new RuletaInterno();
$RuletaDetalle = new RuletaDetalle();

//$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
//$bonos = json_decode($bonos);


$rules = [];


/* Agrega reglas a un array según condiciones del estado y fecha proporcionada. */
if ($State == "A" || $State == "I") {

    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "$State", "op" => "eq"));

}


if ($ToDateLocal != "") {
    $ToDateLocal = str_replace("T", " ", $ToDateLocal);
    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "le"));
}


/* modifica fechas y crea reglas para filtrar productos. */
if ($FromDateLocal != "") {
    $FromDateLocal = str_replace("T", " ", $FromDateLocal);
    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "ge"));
}


array_push($rules, array("field" => "ruleta_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));

/* Agrega reglas a un array y verifica la condición del país del usuario. */
array_push($rules, array("field" => "ruleta_detalle.tipo", "data" => "", "op" => "nn"));
//array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));


// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* modifica reglas según condiciones de sesión de usuario. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "ruleta_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* maneja reglas de filtrado basadas en sesión y condición de filas. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "ruleta_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}


/* convierte datos a JSON, obtiene detalles de ruleta y los decodifica. */
$json = json_encode($filtro);

$ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", $SkeepRows, $MaxRows, $json, TRUE, "ruleta_interno.ruleta_id");

$ruletadetalles = json_decode($ruletadetalles);

$final = [];


/* Itera sobre datos de ruletas, extrayendo y organizando información en un array. */
foreach ($ruletadetalles->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"ruleta_interno.ruleta_id"};
    $array["Name"] = $value->{"ruleta_interno.nombre"};
    $array["Description"] = $value->{"ruleta_interno.descripcion"};
    $array["BeginDate"] = $value->{"ruleta_interno.fecha_inicio"};
    $array["EndDate"] = $value->{"ruleta_interno.fecha_fin"};
    $array["State"] = $value->{"ruleta_interno.estado"};

    array_push($final, $array);
}


/* crea una respuesta estructurada sin errores para una solicitud. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Count"] = $ruletadetalles->count[0]->{".count"};
$response["CountFiltered"] = $ruletadetalles->count[0]->{".count"};


/* Asigna el valor de $final a la clave "Data" del arreglo $response. */
$response["Data"] = $final;