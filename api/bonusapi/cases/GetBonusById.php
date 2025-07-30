<?php

use Backend\dto\BonoInterno;

/**
 * GetBonusById
 * 
 * Obtiene los detalles completos de un bono específico para permitir su duplicación
 *
 * @param int $_REQUEST['Id']  ID del bono a consultar
 * 
 * @return array {
 *   "HasError": boolean,        // Indica si hubo error
 *   "AlertType": string,        // Tipo de alerta (success/danger)  
 *   "AlertMessage": string,     // Mensaje descriptivo
 *   "ModelErrors": array,       // Errores del modelo
 *   "Count": int,              // Total de registros (1 o 0)
 *   "CountFiltered": int,      // Total de registros filtrados
 *   "Result": object {         // Detalles del bono en formato JSON
 *     // Estructura dinámica según el bono
 *   }
 * }
 *
 * @throws Exception           // Errores de procesamiento
 */

// Obtiene el ID del bono desde la solicitud HTTP
$BonusId = $_REQUEST['Id'];

// Intenta crear una instancia de BonoInterno con el ID proporcionado
try {
    $BonoInterno = new BonoInterno($BonusId);
} catch (Exception $ex) {
}

// Inicializa la estructura de respuesta base con valores por defecto
// Establece flags de error y mensajes
$response['HasError'] = false;
$response['AlertType'] = 'success'; 
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];

// Determina si se encontró el bono y establece contadores
$response['Count'] = !empty($BonoInterno->bonoId) ? 1 : 0;
$response['CountFiltered'] = !empty($BonoInterno->bonoId) ? 1 : 0;

// Decodifica y retorna los detalles del bono almacenados en formato JSON
// Si no hay datos, retorna un array vacío como valor predeterminado
$response['Result'] = json_decode($BonoInterno->jsonTemp) ?: [];
