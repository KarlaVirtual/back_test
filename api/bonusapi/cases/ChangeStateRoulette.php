<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\RuletaInterno;
use Backend\mysql\RuletaInternoMySqlDAO;

/**
 * bonusapi/cases/ChangeStateRoulette
 *
 * Actualizar estado de RuletaInterno
 *
 * Este recurso permite actualizar el estado de un registro en RuletaInterno usando su identificador.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada.
 *   - *Id* (int): Identificador de RuletaInterno a actualizar.
 *   - *State* (string): Nuevo estado de RuletaInterno ('A' para activo, 'I' para inactivo).
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta generada ("success" o "error").
 *  - *AlertMessage* (string): Mensaje de alerta generado por el proceso.
 *  - *ModelErrors* (array): Retorna un array vacío si no hay errores en el modelo.
 *
 * Ejemplo de respuesta en caso de error:
 *  - *HasError* => true,
 *  - *AlertType* => "error",
 *  - *AlertMessage* => "[Mensaje de error]",
 *  - *ModelErrors* => array(),
 *
 * @throws Exception Si ocurre un error en la actualización del estado de RuletaInterno.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Asignación de valores de parámetros a variables y inicialización de error en falso. */
$Id = $params->Id;
$State = $params->State;


$error = false;
if ($Id != '' && ($State == 'A' || $State == 'I')) {


    /* Actualiza el estado de una ruleta y confirma transacción en la base de datos. */
    $RuletaInterno = new RuletaInterno($Id);

    if ($RuletaInterno->estado != $State) {
        $RuletaInterno->estado = $State;


        $RuletaInternoMySqlDAO = new RuletaInternoMySqlDAO();
        $RuletaInternoMySqlDAO->update($RuletaInterno);
        $RuletaInternoMySqlDAO->getTransaction()->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];


    } else {
        /* establece una variable de error si una condición no se cumple. */

        $error = true;

    }


} else {
    /* asigna verdadero a la variable $error si no se cumple una condición previa. */

    $error = true;

}

/* maneja errores y configura una respuesta en formato JSON. */
if ($error) {
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}
