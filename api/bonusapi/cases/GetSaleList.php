<?php
/**
 * Este script obtiene una lista de productos disponibles según los parámetros especificados.
 *
 * @param int $SkeepRows Número de filas a omitir (predeterminado: 0).
 * @param int $OrderedItem Orden de los elementos (predeterminado: 1).
 * @param int $MaxRows Número máximo de filas a devolver (predeterminado: 10000).
 *
 * @return array $response Respuesta estructurada con los datos solicitados, incluyendo:
 * - Id (int): Identificador del producto.
 * - ProviderId (int): Identificador del proveedor.
 * - PartnerName (string): Nombre del socio.
 * - ProductName (string): Nombre del producto.
 * - IsWorking (bool): Indica si el producto está activo.
 * - Notes (string): Notas adicionales sobre el producto.
 * - RegionId (int): Identificador de la región.
 */

/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\ProductoMandante;


/* asigna valores predeterminados a variables si están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Inicializa $MaxRows a 10000 si está vacío y crea un arreglo vacío $final. */
if ($MaxRows == "") {
    $MaxRows = 10000;
}


$final = [];

/* Se crea un objeto y se obtienen productos según parámetros JSON especificados. */
$ProductoMandante = new ProductoMandante();
$json = '{"rules" : [{"field" : "", "data": "0","op":"eq"}] ,"groupOp" : "AND"}';

$productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, false);
$productos = json_decode($productos);

$final = [];


/* Convierte datos de productos en un formato de array para posterior procesamiento. */
foreach ($productos->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"producto_mandante.prodmandante_id"};
    $array["ProviderId"] = $value->{"producto.proveedor_id"};
    $array["PartnerName"] = $value->{"mandante.descripcion"};
    $array["ProductName"] = $value->{"producto.descripcion"};
    $array["IsWorking"] = ($value->{"producto.estado"} == "A") ? true : false;
    $array["Notes"] = $value->{"producto.descripcion"};
    $array["RegionId"] = 1;

    array_push($final, $array);

}


/* Asigna el valor de la variable "$final" a la variable "$response". */
$response = $final;