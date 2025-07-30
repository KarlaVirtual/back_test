<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

/**
 * GetAgentSystems
 * 
 * Obtiene información de los sistemas de agentes
 *
 * @return array {
 *   "HasError": boolean,      // Indica si hubo error
 *   "AlertType": string,      // Tipo de alerta (success/danger) 
 *   "AlertMessage": string,   // Mensaje descriptivo
 *   "ModelErrors": array,     // Errores del modelo
 *   "Data": array[{          // Datos de los sistemas de agentes
 *     "UserName": string,     // Nombre de usuario
 *     "SystemName": string,   // Nombre del sistema
 *     "FirstName": string,    // Nombre
 *     "Phone": string,        // Teléfono
 *     "LastLoginLocalDate": string, // Última fecha de login
 *     "LastLoginIp": string   // IP del último login
 *   }]
 * }
 *
 * @throws Exception         // Errores de procesamiento
 */

// Inicializa la respuesta con valores por defecto indicando que no hay errores
$response["HasError"] = false;
$response["AlertType"] = "success"; 
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

// Crea un array temporal para almacenar los datos de un sistema de agente
$array = [];

// Asigna valores de prueba a los campos del sistema de agente
$array["UserName"] = "T ";
$array["SystemName"] = "T";
$array["FirstName"] = "T";
$array["FirstName"] = "T";
$array["Phone"] = "T";
$array["LastLoginLocalDate"] = "T";
$array["LastLoginIp"] = "T";

// Crea el array final y agrega el sistema de agente
$final = [];
array_push($final, $array);

// Asigna el array final a la propiedad Data de la respuesta
$response["Data"] = $final;