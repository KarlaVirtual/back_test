<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Producto;

/**
 * Obtiene una lista de productos filtrados por usuario.
 *
 * @return array $response Respuesta estructurada con los siguientes campos:
 * - HasError (bool): Indica si hubo un error (false si no hay errores).
 * - AlertType (string): Tipo de alerta (success en caso de éxito).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo (vacío en caso de éxito).
 * - Data (array): Lista de productos con los campos Id y Name.
 */

/* Se crea un objeto Producto y se define un JSON para filtrar usuarios. */
$Producto = new Producto();

$SkeepRows = 0;
$MaxRows = 1000000;

$json = '{"rules" : [{"field" : "a.usuario_id", "data": "1","op":"eq"}] ,"groupOp" : "AND"}';


/* obtiene, decodifica y formatea productos en un arreglo final. */
$productos = $Producto->getProductos("producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, false);

$productos = json_decode($productos);

$final = [];

foreach ($productos->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"producto.producto_id"};
    $array["Name"] = $value->{"producto.descripcion"};

    array_push($final, $array);

}


/* define una respuesta estructurada sin errores, para una operación exitosa. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;