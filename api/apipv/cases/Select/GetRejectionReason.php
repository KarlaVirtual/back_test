<?php

/**
 * Select/GetRejectionReason
 * 
 * Obtiene las razones de rechazo disponibles en el sistema
 *
 * @return array {
 *   "HasError": boolean,     // Indica si hubo error
 *   "AlertType": string,     // Tipo de alerta (success, warning, error) 
 *   "AlertMessage": string,  // Mensaje descriptivo
 *   "ModelErrors": array,    // Lista de errores del modelo
 *   "data": array[{         // Lista de razones de rechazo
 *     "id": string,         // ID de la razón de rechazo
 *     "value": string       // Descripción de la razón de rechazo
 *   }]
 * }
 *
 * @throws Exception        // Errores de procesamiento
 */

$response['HasError'] = false;
$response['AlertType'] = 'Success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['data'] = [
    ['id' => '0', 'value' => 'Rechazado por extraccion de datos'],
    ['id' => '1', 'value' => 'Rechazado por datos'],
    ['id' => '2', 'value' => 'Rechazado por imagen'],
    ['id' => '3', 'value' => 'Rechazado por usabilidad'],
    ['id' => '4', 'value' => 'Rechazado por semejanza'],
    ['id' => '5', 'value' => 'Rechazado por vivacidad'],

];


