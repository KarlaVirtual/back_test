<?php

use Backend\dto\Franquicia;

/**
 * Obtener listado de Franquicias
 *
 * Obtiene una lista de Franquicias basándose en los filtros y parámetros de paginación proporcionados.
 *
 * @param int $count : Número máximo de registros a devolver. Valor por defecto: 100.
 * @param int $start : Número de registros a omitir para la paginación. Valor por defecto: 0.
 * @param int $OrderedItem : Criterio para ordenar los elementos. Valor por defecto: 1.
 * @param string $Name : Nombre de la Franquicia para aplicar filtro exacto.
 * @param string $IsActivate : Estado de la Franquicia ('A' para activo, 'I' para inactivo).
 * @param string $abreviado : Valor abreviado de la Franquicia para filtro exacto.
 * @param int $Id : Identificador específico de la Franquicia.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si ocurrió un error en la operación.
 *  - *AlertType* (string): Tipo de alerta que se debe mostrar ('success' en caso exitoso).
 *  - *AlertMessage* (string): Mensaje a mostrar en la alerta.
 *  - *ModelErrors* (array): Lista de errores del modelo (si existen).
 *  - *pos* (int): Posición inicial del resultado devuelto (para paginación).
 *  - *total_count* (int): Número total de registros encontrados con los filtros aplicados.
 *  - *data* (array): Lista de franquicias devueltas. Cada elemento contiene:
 *      - *id* (int): Identificador de la Franquicia.
 *      - *descripcion* (string): Descripción de la Franquicia.
 *      - *tipo* (string): Tipo de Franquicia.
 *      - *abreviado* (string): Valor abreviado de la Franquicia.
 *      - *estado* (string): Estado de la Franquicia ('A' o 'I').
 *      - *fecha_crea* (string): Fecha de creación del registro.
 *      - *fecha_modif* (string): Fecha de modificación del registro.
 *      - *usucrea_id* (int): ID del usuario que creó el registro.
 *      - *usumodif_id* (int): ID del usuario que modificó el registro.
 *      - *imagen* (string): URL o nombre del archivo de imagen asociado.
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* procesa parámetros de solicitud para filtrar y paginar datos. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$Name = $_REQUEST["Name"];  // parametro que permite filtar por nombre
$State = $_REQUEST["IsActivate"]; // parametro que permite filtar por estado
$Abreviado = $_REQUEST["abreviado"]; // parametro que permite filtar por estado
/* obtiene parámetros de solicitud para filtrar y establecer valores predeterminados. */
$Id = $_REQUEST["Id"]; // parametro que permite filtar por Id del Franquicia

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
    array_push($rules, array("field" => "franquicia.franquicia_id", "data" => "$Id", "op" => "eq"));
}


/* agrega condiciones a un arreglo basado en variables `$Name` y `$State`. */
if ($Name != "") {
    array_push($rules, array("field" => "franquicia.descripcion", "data" => "$Name", "op" => "eq"));
}

/* agrega condiciones a un arreglo basado en variables `$Name` y `$State`. */
if ($Abreviado != "") {
    array_push($rules, array("field" => "franquicia.abreviado", "data" => "$Abreviado", "op" => "eq"));
}

if ($State != "" and $State == "A") {
    array_push($rules, array("field" => "franquicia.estado", "data" => "A", "op" => "eq"));
} else if ($State != "" and $State == "I") {
    /* Agrega una regla si el estado es "I". */

    array_push($rules, array("field" => "franquicia.estado", "data" => "I", "op" => "eq"));
}


/* Se generan registros de Franquicias filtrados y paginados desde la base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$Franquicia = new Franquicia(); // se instancia la clase Franquicia para traer los registros de la Base de datos en la tabla Franquicia
$Franquicias = $Franquicia->getFranquiciasCustom("franquicia.*", "franquicia.franquicia_id", "desc", $SkeepRows, $MaxRows, $jsonfiltro, true);

$Franquicias = json_decode($Franquicias);


/* transforma datos de Franquicias en un arreglo estructurado. */
$final = [];

foreach ($Franquicias->data as $key => $value) {
    $array = [];
    $array["id"] = $value->{"franquicia.franquicia_id"};
    $array["descripcion"] = $value->{"franquicia.descripcion"};
    $array["tipo"] = $value->{"franquicia.tipo"};
    $array["abreviado"] = $value->{"franquicia.abreviado"};
    $array["estado"] = $value->{"franquicia.estado"};
    $array["fecha_crea"] = $value->{"franquicia.fecha_crea"};
    $array["fecha_modif"] = $value->{"franquicia.fecha_modif"};
    $array["usucrea_id"] = $value->{"franquicia.usucrea_id"};
    $array["usumodif_id"] = $value->{"franquicia.usumodif_id"};
    $array["imagen"] = $value->{"franquicia.imagen"};
    array_push($final, $array);

}


/* Código que inicializa una respuesta sin errores, y asigna valores relacionados con la respuesta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["pos"] = $SkeepRows;

/* Asigna el conteo de Franquicias y datos finales a la respuesta. */
$response["total_count"] = $Franquicias->count[0]->{".count"};
$response["data"] = $final;