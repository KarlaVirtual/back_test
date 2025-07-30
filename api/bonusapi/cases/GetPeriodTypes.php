<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

/**
 * Este script devuelve una lista de tipos de períodos predefinidos.
 *
 * @return array $response Respuesta estructurada con los tipos de períodos.
 * - HasError: Indica si hubo un error (false si no hay errores).
 * - AlertType: Tipo de alerta (danger en caso de éxito).
 * - AlertMessage: Mensaje de alerta (Success en caso de éxito).
 * - ModelErrors: Lista de errores del modelo (vacío en caso de éxito).
 * - Result: Lista de tipos de períodos con los campos Id y Name.
 */

/* Respuesta estructurada en formato JSON indicando éxito y resultados con opciones temporales. */
$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "Success";
$response["ModelErrors"] = [];
$response["Result"] = array(
    array(
        "Id" => 1,
        "Name" => "None"),
    array(
        "Id" => 2,
        "Name" => "Day"),
    array(
        "Id" => 3,
        "Name" => "Week"),
    array(
        "Id" => 4,
        "Name" => "Month"),
    array(
        "Id" => 5,
        "Name" => "Year")
);