<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\TorneoInterno;
use Backend\mysql\TorneoInternoMySqlDAO;

/**
 * bonusapi/cases/ChangeStateTournament
 *
 * Actualizar estado de TorneoInterno
 *
 * Este recurso permite actualizar el estado de un registro en TorneoInterno usando su identificador.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada.
 *   - *Id* (int): Identificador de TorneoInterno a actualizar.
 *   - *State* (string): Nuevo estado de TorneoInterno ('A' para activo, 'I' para inactivo).
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
 * @throws Exception Si ocurre un error en la actualización del estado de TorneoInterno.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros a variables y establece un error como false. */
$Id = $params->Id;
$State = $params->State;


$error = false;
if ($Id != '' && ($State == 'A' || $State == 'I')) {


    /* Actualiza el estado de un torneo y maneja la respuesta de éxito. */
    $TorneoInterno = new TorneoInterno($Id);

    if ($TorneoInterno->estado != $State) {
        $TorneoInterno->estado = $State;


        $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO();
        $TorneoInternoMySqlDAO->update($TorneoInterno);
        $TorneoInternoMySqlDAO->getTransaction()->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];


    } else {
        /* verifica una condición y establece $error como verdadero si no se cumple. */

        $error = true;

    }


} else {
    /* La condición 'else' asigna 'true' a '$error' si la condición previa falla. */

    $error = true;

}

/* gestiona errores, estableciendo una respuesta con detalles sobre el problema. */
if ($error) {
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}
