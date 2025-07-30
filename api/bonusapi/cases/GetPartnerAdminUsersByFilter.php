<?php

use Backend\dto\PuntoVenta;

/**
 * Este script procesa una solicitud para obtener usuarios administradores asociados a un punto de venta
 * basado en un filtro. Recibe datos en formato JSON, realiza consultas y devuelve una respuesta estructurada.
 *
 * @param string $params JSON que contiene los datos de entrada. Debe incluir:
 * @param int $params->CashDeskId ID de la caja registradora para filtrar los usuarios.
 *
 * @return array $response Respuesta estructurada que incluye:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success", "error").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores de validación.
 *  - Data (array): Datos procesados, incluyendo:
 *      - Id (int): ID del usuario.
 *      - Name (string): Nombre del usuario.
 */



/* recibe datos JSON y extrae el ID de la caja registradora. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$id = $params->CashDeskId;

$PuntoVenta = new PuntoVenta();


/* inicializa variables si no tienen valor asignado. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Se establece un límite de filas y se obtiene un árbol de usuarios. */
if ($MaxRows == "") {
    $MaxRows = 100000;
}

$json = '{"rules" : [{"field" : "punto_venta.puntoventa_id", "data": ' . $id . ',"op":"eq"}] ,"groupOp" : "AND"}';

$usuarios = $PuntoVenta->getUsuariosTree("usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* decodifica usuarios JSON y crea un nuevo arreglo con IDs y nombres. */
$usuarios = json_decode($usuarios);

$final = [];

foreach ($usuarios->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"usuario.usuario_id"};
    $array["Name"] = $value->{"usuario.nombre"};
    array_push($final, $array);

}


/* inicializa una respuesta sin errores y almacena datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;