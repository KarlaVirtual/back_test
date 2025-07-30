<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\JackpotInterno;
use Backend\mysql\JackpotInternoMySqlDAO;

/**
 * bonusapi/cases/ChangeStateJackpot
 *
 * Actualizar estado de un Jackpot
 *
 * Este recurso permite actualizar el estado de un Jackpot, cambiándolo a "A" (Activo) o "I" (Inactivo),
 * siempre y cuando el estado actual sea diferente al proporcionado.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada.
 *   - *Id* (int): Identificador del Jackpot a actualizar.
 *   - *State* (string): Nuevo estado del Jackpot ("A" para Activo, "I" para Inactivo).
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta generada ("success" o "error").
 *  - *AlertMessage* (string): Mensaje de alerta generado por el proceso.
 *  - *ModelErrors* (array): Retorna array vacío si no hay errores en el modelo.
 *
 * Ejemplo de respuesta en caso de error:
 *  - *HasError* => true,
 *  - *AlertType* => "error",
 *  - *AlertMessage* => "[Mensaje de error]",
 *  - *ModelErrors* => array(),
 *
 * @throws Exception Si ocurre un error en la actualización del estado del Jackpot.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros a variables y define una variable de error. */
$Id = $params->Id;
$State = $params->State;


$error = false;
if ($Id != '' && ($State == 'A' || $State == 'I')) {


    /* Actualiza el estado de JackpotInterno y maneja la respuesta de éxito. */
    $JackpotInterno = new JackpotInterno($Id);

    if ($JackpotInterno->estado != $State) {
        $JackpotInterno->estado = $State;


        $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO();
        $JackpotInternoMySqlDAO->update($JackpotInterno);
        $JackpotInternoMySqlDAO->getTransaction()->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];


    } else {
        /* asigna verdadero a la variable $error si la condición anterior no se cumple. */

        $error = true;

    }


} else {
    /* establece una variable de error en verdadero si la condición no se cumple. */

    $error = true;

}

/* maneja errores, configurando una respuesta con detalles sobre el problema. */
if ($error) {
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}
