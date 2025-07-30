<?php
/**
 * Este script obtiene los tipos de mercados deportivos según las fechas y parámetros proporcionados.
 * 
 * @param array $_REQUEST Contiene los siguientes campos:
 * @param string $_REQUEST["BeginDate"] Fecha de inicio.
 * @param string $_REQUEST["EndDate"] Fecha de fin.
 * @param int $_REQUEST["sportId"] Identificador del deporte.
 * 
 * 
 * @return array $response Contiene los siguientes campos:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (por ejemplo, "success").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Lista de tipos de mercados obtenidos.
 * 
 * @throws Exception Si ocurre un error al procesar los datos.
 */

/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


/* procesa fechas y obtiene tipos de mercados deportivos, sin errores. */
$BeginDate = $_REQUEST["BeginDate"];
$EndDate = $_REQUEST["EndDate"];
$sports = getMarketTypes($_REQUEST['sportId'], $BeginDate, $EndDate);

$response["HasError"] = false;
$response["AlertType"] = "success";

/* define un array de respuesta con mensaje, errores y datos de deportes. */
$response["AlertMessage"] = "Operation has completed successfuly";
$response["ModelErrors"] = [];
$response["Data"] = $sports;