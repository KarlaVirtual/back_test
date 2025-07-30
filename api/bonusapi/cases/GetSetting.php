<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

/**
 * Este script genera una respuesta con las configuraciones disponibles, como las divisas para reportes.
 *
 * @return array $response Respuesta estructurada con los datos solicitados, incluyendo:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (por ejemplo, "success").
 * - AlertMessage (string): Mensaje de la operación.
 * - ModelErrors (array): Lista de errores de modelo, si los hay.
 * - Data (array): Datos obtenidos, incluyendo:
 *   - ReportCurrencies (array): Lista de divisas disponibles para reportes, con:
 *     - Id (string): Identificador de la divisa.
 *     - IsSelected (int): Indica si la divisa está seleccionada.
 */

/* crea una respuesta exitosa con datos de divisas disponibles. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Data"] = array(
    "ReportCurrencies" => array(
        array(
            "Id" => "EUR",
            "IsSelected" => 0,
        ),
        array(
            "Id" => "PEN",
            "IsSelected" => 0,
        ),
    ),
);