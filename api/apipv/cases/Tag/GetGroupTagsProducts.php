<?php

use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\dto\Etiqueta;
use Backend\dto\Producto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\EtiquetaProducto;
use Backend\mysql\TagoMandanteMySqlDAO;
use Backend\mysql\EtiquetaProductoMySqlDAO;

/**
 * Obtener etiquetas de grupo para productos.
 *
 * Este script permite recuperar etiquetas asociadas a productos con filtros personalizados.
 *
 * @param array $_REQUEST Valores de la solicitud HTTP:
 * @param int $_REQUEST["count"] Número máximo de filas a recuperar.
 * @param int $_REQUEST["start"] Número de filas a omitir.
 * @param int $_REQUEST["Id"] Identificador del producto.
 * @param string $_REQUEST["IsActivate"] Estado de la etiqueta ('A' para activo, 'I' para inactivo).
 * @param int $_REQUEST["LabelId"] Identificador de la etiqueta.
 * @param int $_REQUEST["Minimum"] Valor mínimo para el filtro.
 * @param int $_REQUEST["Maximum"] Valor máximo para el filtro.
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success' o 'error').
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Datos estructurados con etiquetas incluidas y excluidas.
 * - pos (int): Posición inicial de los datos.
 * - total_count (int): Total de registros encontrados.
 *
 * @throws Exception Si ocurre un error durante la consulta o el procesamiento de datos.
 */

/* obtiene parámetros de solicitud para controlar la paginación de datos. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

/* asigna valores por defecto a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}

/* Recoge y valida datos de solicitud, preparando reglas para uso posterior. */
$Id = $_REQUEST["Id"];
$IsActivate = ($_REQUEST["IsActivate"] == "A" || $_REQUEST["IsActivate"] == "I") ? $_REQUEST["IsActivate"] : '';;
$Label = $_REQUEST["LabelId"];
$Minimum = $_REQUEST["Minimum"];
$Maximum = $_REQUEST["Maximum"];

$rules = [];

/* agrega condiciones a un arreglo de reglas basadas en variables. */
if ($Id != "") {
    array_push($rules, array("field" => "etiqueta_producto.producto_id", "data" => $Id, "op" => "eq"));
}

array_push($rules, array("field" => "etiqueta_producto.estado", "data" => "A", "op" => "eq"));

if ($Label != "") {
    array_push($rules, array("field" => "etiqueta_producto.etiqueta_id", "data" => $Label, "op" => "eq"));
}

/* Crea un filtro JSON y obtiene datos de productos usando ese filtro. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$EtiquetaProducto = new EtiquetaProducto();

$datos = $EtiquetaProducto->getEtiquetaProductoCustom("etiqueta_producto.*,etiqueta.nombre", "etiqueta_producto.etiqprod_id", "desc", $SkeepRows, $MaxRows, $jsonfiltro, true);

/* Código que decodifica datos JSON y prepara variables para almacenar productos y resultados. */
$datos = json_decode($datos);

$productosString = '##';

$final = [];

$children_final = [];

/* crea una lista de identificadores de productos a partir de un objeto de datos. */
$children_final2 = [];

foreach ($datos->data as $key => $value) {

    $productosString = $productosString . "," . $value->{"etiqueta_producto.etiqueta_id"};

}

/* crea un filtro de reglas para validar un producto según su ID. */
$rules = [];

if ($Id != "") {
    array_push($rules, array("field" => "etiqueta_producto.producto_id", "data" => $Id, "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Convierte filtros a JSON, obtiene etiquetas personalizadas y las decodifica. */
$jsonfiltro = json_encode($filtro);

$etiqueta = new Etiqueta();
$datos = $etiqueta->getEtiquetasCustom("etiqueta.*","etiqueta.etiqueta_id","desc",$SkeepRows,$MaxRows,$jsonfiltro,false, true);

$datos = json_decode($datos);

/* Recorre datos, extrae información y la organiza en un arreglo final. */
foreach ($datos->data as $key => $value) {

    $array = [];

    $children = [];
    $children["id"] = $value->{"etiqueta.etiqueta_id"};
    $children["value"] = $value->{"etiqueta.nombre"};

    array_push($children_final, $children);

}

/* Código PHP que estructura una respuesta con éxito y datos específicos para la API. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

/* procesa una lista de productos y configura la respuesta estructurada. */
$response["Data"]["ExcludedTagList"] = $children_final;
$response["Data"]["IncludedTagList"] = str_replace("##", "", str_replace("##,", "", $productosString));
$response["pos"] = $SkeepRows;
$response["total_count"] = $productos->count[0]->{".count"};
$response["data"] = $final;

?>