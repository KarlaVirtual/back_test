<?php

use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Mandante;


/**
 * Setting/GetCurrenciesTrm
 *
 * Este script obtiene las tasas de cambio (TRM) de los países mandantes.
 *
 * @param array $params
 * @param int $params->count - número máximo de filas a obtener.
 * @param int $params->start - posición inicial para la paginación.
 * @param int $params->id - identificador del país.
 * @param int $params->Partner - identificador del socio.
 *
 * @return array $response
 * - hasError: booleano que indica si hubo un error.
 * - AlertType: tipo de alerta (string).
 * - AlertMessage: mensaje de alerta (string).
 * - ModelErrors: lista de errores del modelo (array).
 * - data: lista de países con sus tasas de cambio (array).
 * - pos: posición inicial de los datos (int).
 * - total_count: número total de registros (int).
 *
 * @throws no
 */


/* obtiene parámetros de solicitud para paginación y datos de un usuario. */
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


$Id = $_REQUEST["id"];
$Partner = $_REQUEST["Partner"];

/* Inicializa el estado y la variable de filas a saltar en un script. */
$State = "A";

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

$OrderedItem = "";

/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}


/* Código para ordenar resultados según país y agregar reglas de filtrado. */
$orden = "pais_mandante.pais_id";
$tipoOrden = "asc";

$rules = [];

if ($Partner != "") {
    array_push($rules, array("field" => "pais_mandante.mandante", "data" => $Partner, "op" => "eq"));
}


/* Agrega condiciones a un arreglo de reglas basado en los valores de $Id y $State. */
if ($Id != "") {
    array_push($rules, array("field" => "pais.pais_id", "data" => "$Id", "op" => "eq"));
}

if ($State != "") {
    array_push($rules, array("field" => "pais_mandante.estado", "data" => $State, "op" => "eq"));
}


/* Genera un filtro en JSON con condiciones basadas en el estado proporcionado. */
if ($State != "") {
    array_push($rules, array("field" => "pais.estado", "data" => $State, "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);


/* Código para obtener y decodificar datos de países mandantes en formato JSON. */
$paisMandante = new PaisMandante();

$paises = $paisMandante->getPaisMandantesCustom2("pais_mandante.*, pais_moneda.moneda, pais.pais_nom", $orden, $tipoOrden, $SkeepRows, $MaxRows, $jsonfiltro, true);

$paises = json_decode($paises);


$final = [];

foreach ($paises->data as $key => $value) {


    /* Se crea un array asociativo con datos de países y sus tasas de cambio. */
    $array = [];
    $array["id"] = $value->{"pais_mandante.pais_id"};
    $array["value"] = $value->{"pais.pais_nom"};
    $array["baseCurrency"] = $value->{"pais_moneda.moneda"};
    $array["trmNio"] = $value->{"pais_mandante.trm_nio"};
    $array["trmMxn"] = $value->{"pais_mandante.trm_mxn"};

    /* Asigna valores de tipo de cambio a un array desde un objeto en PHP. */
    $array["trmPen"] = $value->{"pais_mandante.trm_pen"};
    $array["trmBrl"] = $value->{"pais_mandante.trm_brl"};
    $array["trmClp"] = $value->{"pais_mandante.trm_clp"};
    $array["trmCrc"] = $value->{"pais_mandante.trm_crc"};
    $array["trmUsd"] = $value->{"pais_mandante.trm_usd"};
    $array["trmGtq"] = $value->{"pais_mandante.trm_gtq"};

    /* Asigna valores de un objeto a un array y lo agrega a otro array final. */
    $array["trmGyd"] = $value->{"pais_mandante.trm_gyd"};
    $array["trmJmd"] = $value->{"pais_mandante.trm_jmd"};
    $array["trmVes"] = $value->{"pais_mandante.trm_ves"};

    array_push($final, $array);

}


/* Crea una respuesta estructurada con error, tipo de alerta y datos finales. */
$response["hasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

/* Asigna posiciones y conteo total de países a una respuesta en formato array. */
$response["data"] = $final;
$response["pos"] = $SkeepRows;
$response["total_count"] = $paises->count[0]->{".count"};


