<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Producto;

/**
 * Este script obtiene una lista de productos y los transforma en un formato específico.
 *
 * @param int $SkeepRows Número de filas a omitir en la consulta.
 * @param int $MaxRows Número máximo de filas a devolver.
 * @param string $json Filtro en formato JSON para la consulta.
 *
 * @return array $response Respuesta estructurada con los datos de los productos.
 * - HasError: Indica si hubo un error (false si no hay errores).
 * - AlertType: Tipo de alerta (success en caso de éxito).
 * - AlertMessage: Mensaje de alerta (vacío en caso de éxito).
 * - ModelErrors: Lista de errores del modelo (vacío en caso de éxito).
 * - Data: Lista de productos con los campos Id, CategoryId, ProviderId, ProviderName y Notes.
 */

/* crea un nuevo objeto 'Producto' y define criterios de búsqueda en JSON. */
$Producto = new Producto();

$SkeepRows = 0;
$MaxRows = 1000000;

$json = '{"rules" : [{"field" : "a.", "data": "1","op":"eq"}] ,"groupOp" : "AND"}';


/* obtiene productos, los decodifica y formatea en un nuevo array. */
$productos = $Producto->getProductosCustom(" producto.*,proveedor.* ", "producto.proveedor_id", "asc", $SkeepRows, $MaxRows, $json, false);

$productos = json_decode($productos);

$final = [];

foreach ($productos->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"producto.producto_id"};
    $array["CategoryId"] = 0;
    $array["ProviderId"] = $value->{"producto.proveedor_id"};
    $array["ProviderName"] = $value->{"proveedor.descripcion"};


    $array["Notes"] = $value->{"producto.descripcion"};
    array_push($final, $array);

}


/* Asigna el valor de $final a la variable $response en PHP. */
$response = $final;
