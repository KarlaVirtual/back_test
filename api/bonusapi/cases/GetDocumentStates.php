<?php
/**
 * Obtiene una lista de estados de documentos disponibles.
 *
 * @return array $response Respuesta estructurada con los siguientes campos:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Lista de estados de documentos con los campos:
 *    - NumId (string): Identificador del estado.
 *    - Name (string): Nombre del estado.
 */

/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


/* Configura la respuesta de una API con estado y datos sobre aprobación. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    array("NumId" => "A", "Name" => "Aprobado"),
    array("NumId" => "R", "Name" => "Rechazado"),
    array("NumId" => "E", "Name" => "Enviado"),
);
