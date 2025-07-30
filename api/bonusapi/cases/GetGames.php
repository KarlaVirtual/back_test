<?php
/**
 * Obtiene una lista de productos filtrados según los parámetros proporcionados.
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 * @param int $params->Type Tipo de producto (3 para 'LIVECASINO', 4 para 'VIRTUAL').
 * @param int $params->ProviderId ID del proveedor.
 * @param int $params->CountryId ID del país.
 * 
 * 
 * @return array $response Respuesta estructurada con los siguientes campos:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Lista de productos filtrados con los campos:
 *    - Id (int): ID del producto.
 *    - Name (string): Nombre del producto.
 *    - ProviderId (int): ID del proveedor.
 */

use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;

/* Asigna 'LIVECASINO' a $tipo si $type es igual a 3. */
$type = $params->Type;

$tipo = 'CASINO';

if ($type == 3) {
    $tipo = 'LIVECASINO';
}

/* Condición que asigna 'VIRTUAL' a $tipo si $type es 4 y obtiene ProviderId. */
if ($type == 4) {
    $tipo = 'VIRTUAL';
}

$ProviderId = $params->ProviderId;

/* Creación de un objeto ProductoMandante y definición de reglas para un país específico. */
$CountryId = $params->CountryId;

$ProductoMandante = new ProductoMandante();

$rules = [];

/* Se agregan reglas de filtrado a un arreglo según condiciones específicas. */
array_push($rules, array("field" => "subproveedor.subproveedor_id", "data" => "$ProviderId", "op" => "eq"));
//array_push($rules, array("field" => "subproveedor.tipo", "data" => "$tipo", "op" => "eq"));

if ($CountryId != '') {
    array_push($rules, array("field" => "producto_mandante.pais_id", "data" => $CountryId, "op" => "eq"));
}

// Si el usuario esta condicionado por País
/* Condiciona reglas basadas en el país y mandante del usuario en sesión. */
if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "producto_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
}

// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

/* Se generan reglas de filtrado y se convierten a formato JSON. */
array_push($rules, array("field" => "producto_mandante.estado", "data" => 'A', "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.habilitacion", "data" => 'A', "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$SkeepRows = 0;

/* obtiene productos de un mandante y los decodifica en un arreglo JSON. */
$MaxRows = 1000;

$productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
$productos = json_decode($productos);

$data = array();

/* crea un array final con datos de productos y su proveedor. */
$final = array();

foreach ($productos->data as $producto) {
    array_push($final, array(
        "Id" => $producto->{'producto_mandante.prodmandante_id'},
        "Name" => $producto->{'producto.descripcion'},
        "ProviderId" => $ProviderId
    ));
}

/* Código que establece un array de respuesta sin errores y con datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;
