<?php

use Backend\dto\TorneoInterno;


/**
 * Maneja la creación de un torneo y genera una respuesta estructurada.
 *
 * @param array $_REQUEST Contiene el parámetro 'Id' que representa el identificador del torneo.
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Count (int): Número de torneos encontrados.
 * - Data (array): Contiene las reglas del torneo.
 */
$TorneoId = $_REQUEST['Id'];

try {

    $TorneoInterno = new TorneoInterno($TorneoId);


    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Count'] = !empty($TorneoInterno->torneoId) ? 1 : 0;

    $response['Data'] = array(
        "Rules" => $TorneoInterno->reglas
    );

} catch (Exception $ex) {
/* Manejo de excepciones en PHP: captura errores sin realizar ninguna acción específica. */


}


?>