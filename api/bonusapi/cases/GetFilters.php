<?php
/**
 * Obtiene una lista de filtros disponibles para la aplicación.
 *
 * @return array $response Respuesta estructurada con los siguientes campos:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Lista de filtros disponibles:
 *    - RegionFilter (string): Filtro por región.
 *    - CountryFilter (string): Filtro por país.
 *    - CurrencyFilter (string): Filtro por moneda.
 */


/* establece una respuesta exitosa con datos de filtros y sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "success";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "RegionFilter", "CountryFilter", "CurrencyFilter",
);