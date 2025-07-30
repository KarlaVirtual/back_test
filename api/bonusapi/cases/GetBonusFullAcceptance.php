<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

/**
 * GetBonusFullAcceptance
 * 
 * "Obtiene la aceptación completa de un bono y sus condiciones"
 * De momento la respuesta es fija, no se recibe ningun parametro
 * 
 * @return array {
 *   "HasError": boolean,      // Indica si hubo error
 *   "AlertType": string,      // Tipo de alerta (success/danger)
 *   "AlertMessage": string,   // Mensaje descriptivo
 *   "ModelErrors": array,     // Errores del modelo
 *   "Result": array {}
 * }
 *
 */
 
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = array();
