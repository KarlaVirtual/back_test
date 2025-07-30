<?php
/**
 * Obtiene una lista de categorías filtradas según unidades de tiempo.
 *
 * @return array $response Respuesta estructurada con los siguientes campos:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success', 'danger', etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Result (array): Lista de categorías filtradas con los campos:
 *    - Id (int): ID de la categoría.
 *    - Name (string): Nombre de la categoría.
 */

/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

/* crea una respuesta estructurada, indicando éxito y listando unidades de tiempo. */
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