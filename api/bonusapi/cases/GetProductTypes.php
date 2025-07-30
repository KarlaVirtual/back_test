<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

/**
 * Obtiene una lista de tipos de productos disponibles.
 *
 * @return array $response Respuesta estructurada con los siguientes campos:
 * - HasError (bool): Indica si hubo un error (false si no hay errores).
 * - AlertType (string): Tipo de alerta (success en caso de éxito).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo (vacío en caso de éxito).
 * - Result (array): Lista de tipos de productos con los campos Id y Name.
 */

/* crea una respuesta estructurada con éxito y un listado de opciones. */
$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "Success";
$response["ModelErrors"] = [];
$response["Result"] = array(
    array(
        "Id" => 0,
        "Name" => "Directo"),
    array(
        "Id" => 1,
        "Name" => "Casino"),
    array(
        "Id" => 2,
        "Name" => "SportsBook"),
    array(
        "Id" => 3,
        "Name" => "Live Casino"),
    array(
        "Id" => 4,
        "Name" => "Virtual")
);