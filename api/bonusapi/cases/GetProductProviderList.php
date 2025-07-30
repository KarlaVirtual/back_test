<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Proveedor;

/**
 * Este script obtiene una lista de proveedores de productos y los transforma en un formato específico.
 *
 * @param int $SkeepRows Número de filas a omitir en la consulta.
 * @param int $MaxRows Número máximo de filas a devolver.
 * @param string $json Filtro en formato JSON para la consulta.
 *
 * @return array $response Respuesta estructurada con los datos de los proveedores.
 * - HasError: Indica si hubo un error (false si no hay errores).
 * - AlertType: Tipo de alerta (success en caso de éxito).
 * - AlertMessage: Mensaje de alerta (vacío en caso de éxito).
 * - ModelErrors: Lista de errores del modelo (vacío en caso de éxito).
 * - Data: Lista de proveedores con los campos Id y Name.
 */

/* Se crea un objeto Proveedor y se define un filtro JSON para consulta. */
$Proveedor = new Proveedor();

$SkeepRows = 0;
$MaxRows = 1000000;

$json = '{"rules" : [{"field" : "", "data": "1","op":"eq"}] ,"groupOp" : "AND"}';


/* obtiene y transforma datos de proveedores en un arreglo específico. */
$proveedores = $Proveedor->getProveedoresCustom(" proveedor.* ", "proveedor.proveedor_id", "asc", $SkeepRows, $MaxRows, $json, false);
$proveedores = json_decode($proveedores);

$final = [];

foreach ($proveedores->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"proveedor.proveedor_id"};
    $array["Name"] = $value->{"proveedor.descripcion"};

    array_push($final, $array);

}


/* establece un mensaje de éxito sin errores en la respuesta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response = $final;