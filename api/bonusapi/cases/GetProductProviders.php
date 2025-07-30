<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Proveedor;

/**
 * Obtiene una lista de proveedores de productos filtrados por tipo "CASINO".
 *
 * @return array $response Respuesta estructurada con los siguientes campos:
 * - HasError (bool): Indica si hubo un error (false si no hay errores).
 * - AlertType (string): Tipo de alerta (success en caso de éxito).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo (vacío en caso de éxito).
 * - Data (array): Lista de proveedores con los campos Id y Name.
 */

/* Se crea un objeto Proveedor y se obtienen sus datos filtrados por tipo "CASINO". */
$Proveedor = new Proveedor();
$Proveedor->setTipo("CASINO");

$proveedores = $Proveedor->getProveedores();

$final = [];


/* Crea un arreglo final con IDs y nombres de proveedores desde la lista dada. */
foreach ($proveedores as $key => $value) {

    $array = [];

    $array["Id"] = $value->getProveedorId();
    $array["Name"] = $value->getDescripcion();

    array_push($final, $array);

}


/* inicializa una respuesta sin errores, incluyendo datos finales y mensajes. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;