<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

/**
 * GetBonusTriggers
 * 
 * Obtiene la lista de disparadores (triggers) disponibles para bonos
 * 
 * @param object $params {
 *   "BonusTypeId": string      // ID del tipo de bono (opcional)
 * }
 * 
 * @return array {
 *   "HasError": boolean,       // Indica si hubo error
 *   "AlertType": string,       // Tipo de alerta (success/danger)
 *   "AlertMessage": string,    // Mensaje descriptivo
 *   "ModelErrors": array,      // Errores del modelo
 *   "Result": array [          // Lista de disparadores disponibles
 *     {
 *       "Id": int,             // ID del disparador
 *       "Name": string         // Nombre del disparador
 *     }
 *   ]
 * }
 */

$BonusTypeId = $params->BonusTypeId;

$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "Success";
$response["ModelErrors"] = [];
$response["Result"] = array(
    array(
        "Id" => 1,
        "Name" => "Deposit"),
    array(
        "Id" => 4,
        "Name" => "Withdraw"),
    array(
        "Id" => 5,
        "Name" => "Registration"),
    array(
        "Id" => 6,
        "Name" => "Login"),
    array(
        "Id" => 7,
        "Name" => "Activation"),
    array(
        "Id" => 8,
        "Name" => "RegistrationByPromoCode"),
    array(
        "Id" => 9,
        "Name" => "Verification")
);

if ($BonusTypeId != "") {

}