<?php

use Backend\dto\SorteoInterno;

/**
 * Obtiene información de un sorteo específico basado en su ID.
 *
 * @param string $LotteryId ID del sorteo obtenido de la solicitud.
 *
 * @return array $response Respuesta estructurada que incluye:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Count (int): Número total de registros.
 * - CountFiltered (int): Número de registros filtrados.
 * - Result (array): Resultado del sorteo o un arreglo vacío.
 */

/* obtiene un ID de lotería y crea un objeto SorteoInterno. */
$LotteryId = $_REQUEST['Id'];

try {
    $SorteoInterno = new SorteoInterno($LotteryId);
} catch (Exception $ex) {
}


/* Código para construir una respuesta estructurada sobre el estado de un sorteo. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Count'] = !empty($SorteoInterno->sorteoId) ? 1 : 0;
$response['CountFiltered'] = !empty($SorteoInterno->sorteoId) ? 1 : 0;


/* Decodifica un JSON y lo asigna a 'Result', o devuelve un arreglo vacío. */
$response['Result'] = json_decode($SorteoInterno->jsonTemp) ?: [];

?>