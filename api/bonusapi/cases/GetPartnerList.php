<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

/**
 * Obtiene una lista de socios disponibles.
 *
 * Este script genera una lista de socios con información básica, incluyendo su dominio,
 * estado y región.
 *
 * @return array $response
 *   - Id: int, identificador del socio.
 *   - Name: string, nombre del socio.
 *   - Notes: string, notas adicionales sobre el socio.
 *   - Domain: string, dominio del socio.
 *   - SalesManagerId: int, identificador del gerente de ventas.
 *   - LicenseOrigin: string, origen de la licencia.
 *   - StatusId: int, estado del socio.
 *   - IntegrationTypeId: int, tipo de integración.
 *   - ReleaseDate: string, fecha de lanzamiento.
 *   - RegionId: int, identificador de la región.
 */

/* inicializa variables si están vacías, estableciendo valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un valor predeterminado y define un filtro JSON para una consulta. */
if ($MaxRows == "") {
    $MaxRows = 10;
}

$final = [];


$json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "0","op":"eq"}] ,"groupOp" : "AND"}';


/* Se crea un arreglo asociativo con información sobre Doradobet. */
$array = [];
$array["Id"] = 0;
$array["Name"] = "Doradobet";
$array["Notes"] = "Dorado";
$array["Domain"] = "www.doradobet.com";
$array["SalesManagerId"] = 0;

/* Se crea un arreglo con atributos específicos y se agrega a un arreglo final. */
$array["LicenseOrigin"] = "TEST";
$array["StatusId"] = 1;
$array["IntegrationTypeId"] = 0;
$array["ReleaseDate"] = "";
$array["RegionId"] = 1;

array_push($final, $array);


/* Asigna el valor de la variable `$final` a la variable `$response`. */
$response = $final;
