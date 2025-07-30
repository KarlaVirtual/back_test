<?php

use Backend\dto\JackpotInterno;

/**
 * Este script obtiene los detalles de un jackpot específico.
 * 
 * @param string $Id ID del jackpot recibido a través de $_REQUEST.
 * 
 * @return array $response Respuesta estructurada que incluye:
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (e.g., "success").
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Errores del modelo, si los hay.
 *  - int $Count Número total de jackpots encontrados.
 *  - int $CountFiltered Número de jackpots filtrados.
 *  - array $Result Detalles del jackpot en formato JSON decodificado.
 */

/* Crea un objeto JackpotInterno basado en un ID recibido de la solicitud. */
$JackpotId = $_REQUEST['Id'];

try {
    $JackpotInterno = new JackpotInterno($JackpotId);
} catch (Exception $ex) {
}


/* Configura una respuesta exitosa sin errores ni mensajes para un jackpot. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Count'] = !empty($JackpotInterno->jackpotId) ? 1 : 0;
$response['CountFiltered'] = !empty($JackpotInterno->jackpotId) ? 1 : 0;


/* asigna un arreglo decodificado de JSON a $response['Result'], o un array vacío. */
$response['Result'] = json_decode($JackpotInterno) ?: [];

?>