<?php

use Backend\dto\TorneoInterno;

/**
 * Obtiene información de un torneo específico.
 *
 * @param array $_REQUEST Contiene el parámetro 'Id' que representa el identificador del torneo.
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Count (int): Número de torneos encontrados.
 * - CountFiltered (int): Número de torneos filtrados.
 * - Result (array): Datos del torneo en formato JSON.
 * @throws Exception Si ocurre un error al instanciar el objeto TorneoInterno.
 */

/* obtiene un ID de torneo y crea un objeto TorneoInterno con él. */
$TournamentId = $_REQUEST['Id'];

try {
    $TorneoInterno = new TorneoInterno($TournamentId);
} catch (Exception $ex) {
    /* Bloque para manejar excepciones en PHP sin ejecutar ninguna acción específica. */
}

/* inicializa un arreglo de respuesta para una operación, indicando éxito. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Count'] = !empty($TorneoInterno->torneoId) ? 1 : 0;
$response['CountFiltered'] = !empty($TorneoInterno->torneoId) ? 1 : 0;

/* Asigna el resultado decodificado de JSON a una variable, o un array vacío. */
$response['Result'] = json_decode($TorneoInterno->jsonTemp) ?: [];
