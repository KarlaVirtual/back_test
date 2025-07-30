<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Producto;
use Backend\dto\ProductoMandante;

/**
 * Obtiene los sistemas de pago disponibles para un mandante específico.
 *
 * Este script procesa la solicitud para obtener productos relacionados con sistemas de pago,
 * aplicando filtros basados en el proveedor y el mandante.
 *
 * @param string $_SESSION['mandante'] Identificador del mandante para filtrar los productos.
 *
 * @return array $response
 *   - HasError: boolean, indica si ocurrió un error.
 *   - AlertType: string, tipo de alerta (e.g., "success", "error").
 *   - AlertMessage: string, mensaje de alerta.
 *   - ModelErrors: array, lista de errores del modelo.
 *   - Data: array, lista de sistemas de pago con los siguientes campos:
 *       - Id: int, identificador del producto.
 *       - Name: string, descripción del producto y proveedor.
 */

/* Se crea un objeto Producto y se define un filtro JSON para consultas. */
$Producto = new Producto();

$SkeepRows = 0;
$MaxRows = 1000000;

$json = '{"rules" : [{"field" : "proveedor.tipo", "data": "PAYMENT","op":"eq"}] ,"groupOp" : "AND"}';


/* obtiene y decodifica productos, aplicando filtros según el proveedor y mandante. */
$productos = $Producto->getProductos("producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);

$productos = json_decode($productos);

$json = '{"rules" : [{"field" : "proveedor.tipo", "data": "PAYMENT","op":"eq"},{"field" : "producto_mandante.mandante", "data": "' . $_SESSION['mandante'] . '","op":"eq"}] ,"groupOp" : "AND"}';

$ProductoMandante = new ProductoMandante();

/* obtiene productos de un mandante y los transforma a un formato simplificado. */
$productos = $ProductoMandante->getProductosMandanteCustom(" producto.*,proveedor.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
$productos = json_decode($productos);

$final = [];

foreach ($productos->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"producto.producto_id"};
    $array["Name"] = $value->{"producto.descripcion"} . ' - ' . $value->{"proveedor.descripcion"};

    array_push($final, $array);

}


/* genera una respuesta JSON indicando éxito y sin errores, con datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;
