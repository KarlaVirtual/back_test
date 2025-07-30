<?php

use Backend\dto\Banco;

/**
 * Obtiene una lista de bancos basándose en los filtros y parámetros de paginación proporcionados.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param int $params->count Número máximo de registros a devolver. Por defecto, 100.
 * @param int $params->start Número de registros a omitir para la paginación. Por defecto, 0.
 * @param int $params->OrderedItem Orden de los elementos.
 * @param string $params->Name Nombre del banco para filtrar.
 * @param string $params->IsActivate Estado del banco ('A' para activo, 'I' para inactivo).
 * @param int $params->Id Identificador específico del banco.
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - pos (int): Posición inicial de los datos devueltos.
 *                         - total_count (int): Número total de registros encontrados.
 *                         - data (array): Datos de los bancos, incluyendo:
 *                             - id (int): Identificador del banco.
 *                             - descripcion (string): Nombre o descripción del banco.
 *                             - pais (int): Identificador del país asociado al banco.
 *                             - estado (string): Estado del banco ('A' para activo, 'I' para inactivo).
 *                             - producto pago (int): Producto de pago asociado al banco.
 */

/* procesa parámetros de solicitud para filtrar y paginar datos. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$Name = $_REQUEST["Name"];  // parametro que permite filtar por nombre
$State = $_REQUEST["IsActivate"]; // parametro que permite filtar por estado

/* obtiene parámetros de solicitud para filtrar y establecer valores predeterminados. */
$Id = $_REQUEST["Id"]; // parametro que permite filtar por Id del Banco

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un valor predeterminado y prepara filtros para una consulta. */
if ($MaxRows == "") {
    $MaxRows = 100;
}

$rules = [];

// filtros

if ($Id != "") {
    array_push($rules, array("field" => "banco.banco_id", "data" => "$Id", "op" => "eq"));
}


/* agrega condiciones a un arreglo basado en variables `$Name` y `$State`. */
if ($Name != "") {
    array_push($rules, array("field" => "banco.descripcion", "data" => "$Name", "op" => "eq"));
}

if ($State != "" and $State == "A") {
    array_push($rules, array("field" => "banco.estado", "data" => "A", "op" => "eq"));
} else if ($State != "" and $State == "I") {
    /* Agrega una regla si el estado es "I". */

    array_push($rules, array("field" => "banco.estado", "data" => "I", "op" => "eq"));
}


/* Se generan registros de bancos filtrados y paginados desde la base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$Banco = new Banco(); // se instancia la clase banco para traer los registros de la Base de datos en la tabla banco
$Bancos = $Banco->getBancosCustom2("banco.*", "banco.banco_id", "desc", $SkeepRows, $MaxRows, $jsonfiltro, true);

$Bancos = json_decode($Bancos);


/* transforma datos de bancos en un arreglo estructurado. */
$final = [];

foreach ($Bancos->data as $key => $value) {
    $array = [];
    $array["id"] = $value->{"banco.banco_id"};
    $array["descripcion"] = $value->{"banco.descripcion"};
    $array["pais"] = $value->{"banco.pais_id"};
    $array["estado"] = $value->{"banco.estado"};
    $array["producto pago"] = $value->{"banco.producto_pago"};
    array_push($final, $array);

}


/* Código que inicializa una respuesta sin errores, y asigna valores relacionados con la respuesta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["pos"] = $SkeepRows;

/* Asigna el conteo de bancos y datos finales a la respuesta. */
$response["total_count"] = $Bancos->count[0]->{".count"};
$response["data"] = $final;