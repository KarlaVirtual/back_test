<?php

use Backend\dto\ProductoMandante;


/**
 * Este recurso permite obtener los productos ya asociados a un partner para poder ser usados al agregarse a una criptored
 *
 * @param object $params Objeto que contiene los parámetros de entrada.
 * @param int $ProviderId Identificador único del proveedor.
 * @param string $Partner Identificador del mandante asociado.
 * @param string $Country Identificador del país asociado.
 */


$ProviderId = $params->ProviderId;
$Partner = $params->Partner;
$Country = $params->Country;
$Filter = $params->Filter;


// Se inicializa un arreglo vacío para almacenar las reglas de filtrado.
$rules = [];

// Se agrega una regla al arreglo de reglas, indicando que el campo "proveedor.proveedor_id" debe ser igual al identificador único del proveedor.
array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $ProviderId, "op" => "eq"));

// Se agrega una regla al arreglo de reglas, indicando que el campo "producto_mandante.estado" debe ser igual a "A" (activo).
array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));

// Se agrega una regla al arreglo de reglas, indicando que el campo "producto_mandante.mandante" debe ser igual al identificador del mandante asociado.
array_push($rules, array("field" => "producto_mandante.mandante", "data" => $Partner, "op" => "eq"));

// Se agrega una regla al arreglo de reglas, indicando que el campo "producto_mandante.pais_id" debe ser igual al identificador del país asociado.
array_push($rules, array("field" => "producto_mandante.pais_id", "data" => $Country, "op" => "eq"));

if($Filter != ""){
    array_push($rules,array("field"=>"producto.descripcion","data"=>$Filter,"op"=>"cn"));
}

// Se crea un filtro que agrupa las reglas con el operador lógico "AND".
$filtro = array("rules" => $rules, "groupOp" => "AND");

// Se convierte el filtro a formato JSON para ser utilizado en la consulta.
$jsonfiltro = json_encode($filtro);

// Se instancia la clase ProductoMandante para realizar la consulta de productos.
$Producto = new ProductoMandante();

// Se ejecuta la consulta personalizada para obtener los datos de los productos filtrados.
$data = $Producto->getProductosMandanteCustom("producto_mandante.prodmandante_id,producto.descripcion", "producto_mandante.prodmandante_id", "desc", 0, 100, $jsonfiltro, true);

// Se decodifica el resultado de la consulta desde formato JSON a un objeto PHP.
$data = json_decode($data);



/**
 * @var array $final Arreglo que almacena el resultado final con los datos procesados.
 * @var object $data->data Contiene los datos obtenidos de la consulta.
 * @var object $value Elemento actual del recorrido en los datos.
 * @var array $array Arreglo temporal que almacena el id y descripción del producto.
 */

$final = [];

foreach ($data->data as $key => $value) {
    $array = [];
    $array["id"] = $value->{"producto_mandante.prodmandante_id"};
    $array["value"] = $value->{"producto.descripcion"};

    array_push($final, $array); // Agrega el array al resultado final
}

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Consulta exitosa";
$response["Data"] = $final;
