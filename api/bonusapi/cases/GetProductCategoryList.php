<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Proveedor;

/**
 * Este script obtiene una lista de categorías de productos y los transforma en un formato específico.
 *
 * @param int $SkeepRows Número de filas a omitir en la consulta.
 * @param int $MaxRows Número máximo de filas a devolver.
 * @param string $json Filtro en formato JSON para la consulta.
 *
 * @return array $response Respuesta estructurada con los datos de las categorías.
 * - HasError: Indica si hubo un error (false si no hay errores).
 * - AlertType: Tipo de alerta (success en caso de éxito).
 * - AlertMessage: Mensaje de alerta (vacío en caso de éxito).
 * - ModelErrors: Lista de errores del modelo (vacío en caso de éxito).
 * - Data: Lista de categorías con los campos Id y Name.
 */

/* Se crea un objeto Proveedor y se define una consulta JSON para filtrar datos. */
$Proveedor = new Proveedor();

$SkeepRows = 0;
$MaxRows = 1000000;

$json = '{"rules" : [{"field" : "", "data": "1","op":"eq"}] ,"groupOp" : "AND"}';


/* Código que obtiene y procesa datos de proveedores en un array final. */
$proveedores = $Proveedor->getProveedoresCustom(" proveedor.* ", "proveedor.proveedor_id", "asc", $SkeepRows, $MaxRows, $json, false);
$proveedores = json_decode($proveedores);

$final = [];

foreach ($proveedores->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"proveedor.proveedor_id"};
    $array["Name"] = $value->{"proveedor.descripcion"};

    array_push($final, $array);

}


/* configura una respuesta sin errores y la asigna a una variable final. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response = $final;
