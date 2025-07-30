<?php

use Backend\dto\JackpotInterno;

/**
 * Obtiene los términos y condiciones de un jackpot específico.
 *
 * @param int $JackpotId ID del jackpot proporcionado a través de $_REQUEST.
 *
 * @return array $response Respuesta estructurada con:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Count (int): Número de jackpots encontrados.
 *  - Data (array): Reglas asociadas al jackpot.
 */
$JackpotId = $_REQUEST['Id'];

try {

    $JackpotInterno = new JackpotInterno($JackpotId);

    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Count'] = !empty($JackpotInterno->jackpotId) ? 1 : 0;

    $response['Data'] = array(
        "Rules" => $JackpotInterno->reglas
    );

} catch (Exception $ex) {
/* Manejo de excepciones en PHP para capturar errores sin interrumpir la ejecución. */


}


?>