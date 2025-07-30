<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

/**
 * GetBonusTypes
 * 
 * Obtiene la lista de tipos de bonos disponibles
 * 
 * @return array {
 *   "HasError": boolean,       // Indica si hubo error
 *   "AlertType": string,       // Tipo de alerta (success/danger)
 *   "AlertMessage": string,    // Mensaje descriptivo
 *   "ModelErrors": array,      // Errores del modelo
 *   "Result": array [          // Lista de tipos de bonos
 *     {
 *       "Id": int,             // ID del tipo de bono
 *       "Name": string         // Nombre del tipo de bono
 *     }
 *   ]
 * }
 */


$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "Success";
$response["ModelErrors"] = [];
$response["Result"] = array(



    array(
        "Id" => 2,
        "Name" => "Bono Deposito"),
    array(
        "Id" => 3,
        "Name" => "Bono No Deposito"),
    array(
        "Id" => 5,
        "Name" => "FreeCasino"),
    array(
        "Id" => 6,
        "Name" => "FreeBet"),
    array(
        "Id" => 8,
        "Name" => "FreeSpin")

);