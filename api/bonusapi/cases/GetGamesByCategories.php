<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Proveedor;

/**
 * Este script obtiene juegos agrupados por categorías.
 * 
 * @param int $ProviderId ID del proveedor recibido desde $params.
 * 
 * @return array $response Respuesta estructurada que incluye:
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (e.g., "success").
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Errores del modelo, si los hay.
 *  - array $Data Lista de categorías con:
 *    - int $Id ID de la categoría.
 *    - string $Name Nombre de la categoría.
 *    - array $Games Lista de juegos con:
 *      - int $Id ID del juego.
 *      - string $Name Nombre del juego.
 *      - int $ProviderId ID del proveedor.
 */

/* Se obtiene un proveedor y sus productos para almacenar en un array. */
$ProviderId = $params->ProviderId;
$Proveedor = new Proveedor($ProviderId);


$Productos = $Proveedor->getProductosTipo("", $Proveedor->getAbreviado(), 0, 100000, "", '0');

$data = array();

/* Se inicializa un arreglo vacío llamado $final para almacenar datos. */
$final = array();

foreach ($Productos as $producto) {

    /* Busca un producto en categorías y almacena la categoría correspondiente si se encuentra. */
    $encontroCategoria = false;
    $item = array();
    $pos = 0;
    foreach ($final as $category) {
        if ($producto['categoria.categoria_id'] == $category['Id']) {
            $item = $category;
            $encontroCategoria = true;
            break;
        }
        $pos = $pos + 1;
    }

    /* Condicional agrega una categoría al arreglo final si no se encontró previamente. */
    if (!$encontroCategoria) {
        $item["Id"] = $producto['categoria.categoria_id'];
        $item["Name"] = $producto['categoria.descripcion'];
        $item["Games"] = array();
        array_push($final, $item);
    }


    /* Agrega un nuevo juego a un array con su ID, nombre y proveedor. */
    array_push($final[$pos]["Games"], array(
        "Id" => $producto['producto_mandante.prodmandante_id'],
        "Name" => $producto['producto.descripcion'],
        "ProviderId" => $ProviderId
    ));

}


/* $Producto = new Producto();

 $SkeepRows = 0;
 $MaxRows = 1000000;

 $json = '{"rules" : [{"field" : "producto.proveedor_id", "data": "' . $ProviderId . '","op":"eq"}] ,"groupOp" : "AND"}';

 $productos = $Producto->getProductos("producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);

 $productos = json_decode($productos);

 $final = [];

 foreach ($productos->data as $key => $value) {

     $array = [];

     $array["Id"] = $value->{"producto.producto_id"};
     $array["Name"] = $value->{"producto.descripcion"};
     $array["ProviderId"] = $ProviderId;

     array_push($final, $array);

 }*/


/* crea una respuesta estructurada sin errores y con datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;