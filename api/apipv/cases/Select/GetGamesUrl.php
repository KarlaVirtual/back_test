<?php

/**
 * Select/GetGamesUrl
 *
 * Este script devuelve los carrusales de productos según los parámetros proporcionados.
 *
 * @return array Respuesta en formato JSON con los siguientes campos:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., 'success', 'danger').
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - data (array): Lista de carruseles con sus propiedades específicas.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */



/* Código que define una respuesta JSON sin errores y con datos de Carruseles. */

$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Data'] = [
    ['key' => 'getgames2', 'value' => 'Personalizado'],
    ['key' => 'getgames3', 'value' => 'Populares'],
    ['key' => 'getgames4', 'value' => 'Seguir jugando'],
    ['key' => 'getgames5', 'value' => 'Volver a jugar'],
    ['key' => 'getgames6', 'value' => 'Recomendados'],
    ['key' => 'getgames9', 'value' => 'Juegos nuevos']
];
