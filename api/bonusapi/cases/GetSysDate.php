<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

/**
 * Obtiene la fecha y hora actual del servidor.
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Contiene el tiempo del servidor en milisegundos.
 */

/* define una respuesta estructurada indicando éxito y tiempo del servidor. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "ServerTime" => round(microtime(true) * 1000),

);