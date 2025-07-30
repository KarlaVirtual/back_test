<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\PuntoVenta;

/**
 * GetCashDeskById
 * 
 * Obtiene los detalles de una caja de efectivo específica
 * 
 * @param int $id ID de la caja de efectivo a consultar
 * 
 * @return array {
 *   "HasError": boolean,       // Indica si hubo error
 *   "AlertType": string,       // Tipo de alerta (success/danger)
 *   "AlertMessage": string,    // Mensaje descriptivo
 *   "ModelErrors": array,      // Errores del modelo
 *   "Data": array {
 *     "Id": int,               // ID de la caja de efectivo
 *     "Name": string           // Nombre de la caja de efectivo
 *   }
 * }
 */

$params = file_get_contents('php://input');
$params = json_decode($params);

$id = $_REQUEST["id"];

$PuntoVenta = new PuntoVenta($id);

$final = [];

$final["Id"] = $PuntoVenta->puntoventaId;
$final["Name"] = $PuntoVenta->descripcion;

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;
