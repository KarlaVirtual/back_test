<?php

use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Mandante;


/**
 * Setting/GetCountriesAll
 *
 * Este script obtiene la lista de todos los países con sus detalles.
 *
 * @param array $params
 * @param int $params->Id - identificador del país.
 * @param string $params->Iso - código ISO del país.
 * @param string $params->NameCountry - nombre del país.
 * @param string $params->Currency - moneda del país.
 * @param string $params->State - estado del país.
 * @param string $params->UTC - zona horaria del país.
 * @param string $params->Language - idioma del país.
 * @param string $params->CodePath - código de ruta del país.
 * @param string $params->CellPrefix - prefijo celular del país.
 * @param int $params->count - número máximo de filas a obtener.
 * @param int $params->start - posición inicial para la paginación.
 * 
 *
 * @return array $response
 * - hasError: booleano que indica si hubo un error.
 * - AlertType: tipo de alerta (string).
 * - AlertMessage: mensaje de alerta (string).
 * - ModelErrors: lista de errores del modelo (array).
 * - data: lista de países con sus detalles (array).
 * - pos: posición inicial de los datos (int).
 * - total_count: número total de registros (int).
 *
 * @throws no
 */

/* obtiene datos de la solicitud HTTP y los asigna a variables. */
$Id = $_REQUEST["Id"];
$Iso = $_REQUEST["Iso"];
$NameCountry = $_REQUEST["NameCountry"];
$Currency = $_REQUEST["Currency"];
$State = $_REQUEST["State"];
$UTC = $_REQUEST["UTC"];

/* procesa solicitudes para obtener variables y formatear un prefijo celular. */
$Language = $_REQUEST["Language"];
$CodePATH = $_REQUEST["CodePath"];

$CellPrefix = $_REQUEST["CellPrefix"];
if ($CellPrefix != "") {
    $CellPrefix = ('+' . trim($CellPrefix));
}


/* obtiene parámetros de solicitud para paginar resultados de una consulta. */
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


$OrderedItem = "";

$tipoOrden = "asc";


/* inicializa variables a cero si están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 0;
}


/* inicializa $MaxRows y crea un nuevo objeto Pais con reglas vacías. */
if ($MaxRows == "") {
    $MaxRows = 10000000;
}

$pais = new Pais();

$rules = [];


/* Agrega reglas a un arreglo basadas en la presencia de	Id y Iso. */
if ($Id != "") {
    array_push($rules, array("field" => "pais.pais_id", "data" => $Id, "op" => "eq"));
}

if ($Iso != "") {
    array_push($rules, array("field" => "pais.iso", "data" => $Iso, "op" => "eq"));
}


/* Agrega reglas a un array si se proporcionan país y moneda. */
if ($NameCountry != "") {
    array_push($rules, array("field" => "pais.pais_nom", "data" => $NameCountry, "op" => "eq"));
}

if ($Currency != "") {
    array_push($rules, array("field" => "pais.moneda", "data" => $Currency, "op" => "eq"));
}


/* Agrega reglas a un array si $State o $UTC no están vacíos. */
if ($State != "") {
    array_push($rules, array("field" => "pais.estado", "data" => $State, "op" => "eq"));
}

if ($UTC != "") {
    array_push($rules, array("field" => "pais.utc", "data" => $UTC, "op" => "eq"));
}


/* Agrega condiciones a un array si las variables no están vacías. */
if ($Language != "") {
    array_push($rules, array("field" => "pais.idioma", "data" => $Language, "op" => "eq"));
}

if ($CodePATH != "") {
    array_push($rules, array("field" => "pais.codigo_path", "data" => $CodePATH, "op" => "eq"));
}


/* Crea un filtro JSON con reglas para un prefijo celular específico. */
if ($CellPrefix != "") {
    array_push($rules, array("field" => "pais.prefijo_celular", "data" => $CellPrefix, "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");

$jsonfiltro = json_encode($filtro);


/* obtiene países, los decodifica y organiza en un array final. */
$paises = $pais->getPaisesCustom2("pais_nom", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true);

$paises = json_decode($paises);

$final = [];

foreach ($paises->data as $key => $value) {
    $array = [];
    $array["Id"] = $value->{"pais.pais_id"};
    $array["Iso"] = $value->{"pais.iso"};
    $array["NameCountry"] = $value->{"pais.pais_nom"};
    $array["Currency"] = $value->{"pais.moneda"};
    $array["State"] = $value->{"pais.estado"};
    $array["Utc"] = $value->{"pais.utc"};
    $array["Language"] = $value->{"pais.idioma"};
    $array["CodePath"] = $value->{"pais.codigo_path"};
    $array["CellPrefix"] = $value->{"pais.prefijo_celular"};

    array_push($final, $array);
}


/* Código que estructura una respuesta con estado, tipo de alerta y datos finales. */
$response["hasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["data"] = $final;
$response["pos"] = $SkeepRows;

/* Asigna el conteo total de países a la variable $response["total_count"]. */
$response["total_count"] = $paises->count[0]->{".count"};

