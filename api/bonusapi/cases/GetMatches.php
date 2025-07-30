<?php
/**
 * Este script obtiene una lista de partidos según los parámetros proporcionados.
 * 
 * @param object $params Contiene los siguientes campos:
 * @param string $params->BeginDate Fecha máxima de creación en formato local.
 * @param string $params->EndDate Fecha mínima de creación en formato local.
 * @param int $params->SportId Identificador del deporte.
 * @param int $params->RegionId Identificador de la región.
 * @param int $params->CompetitionId Identificador de la competición.
 * 
 * 
 * @return array $response Contiene los siguientes campos:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (por ejemplo, "success").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Lista de partidos obtenidos.
 * 
 * @throws Exception Si ocurre un error al procesar los datos.
 */

/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


/* obtiene partidos entre fechas específicas según parámetros de deportes y región. */
$BeginDate = $params->BeginDate;
$EndDate = $params->EndDate;

$sports = getMatches($params->SportId, $params->RegionId, $params->CompetitionId, $BeginDate, $EndDate);

$response["HasError"] = false;

/* Código establece respuesta con tipo de alerta, mensaje y datos relacionados con deportes. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfuly";
$response["ModelErrors"] = [];
$response["Data"] = $sports;