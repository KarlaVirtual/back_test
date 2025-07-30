<?php
use Backend\dto\Producto;

/**
 * Obtiene las verticales de casino disponibles para el reporte de productos no deportivos.
 *
 *  Recurso utilizado en el reporte de casino / productos no deportivos (Vinculado a api/apipv/cases/Report/GetCasinoGamesReport.php)
 *  para ofertar las verticales disponibles (Casino, casino en vivo ETC) en el proceso de separación de transacciones
 *
 * Este script utiliza el metodo estático `Producto::getCasinoVerticals()` para recuperar las verticales
 * disponibles (como Casino, Casino en Vivo, etc.) y las incluye en la respuesta.
 *
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - HasError (bool): Indica si ocurrió un error (false por defecto).
 * - AlertType (string): Tipo de alerta (success por defecto).
 * - AlertMessage (string): Mensaje de alerta (vacío por defecto).
 * - ModelErrors (array): Lista de errores del modelo (vacío por defecto).
 * - pos (int): Posición inicial (0 por defecto).
 * - totalCount (int): Número total de verticales obtenidas.
 * - data (array): Lista de verticales obtenidas.
 * - Data (array): Alias de `data` con la misma información.
 */



$verticals = Producto::getCasinoVerticals();

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["pos"] = 0;
$response["totalCount"] = count($verticals);
$response["data"] = $verticals;
$response["Data"] = $verticals;


