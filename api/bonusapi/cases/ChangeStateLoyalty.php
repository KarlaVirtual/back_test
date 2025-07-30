<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


use Backend\dto\LealtadInterna;
use Backend\mysql\LealtadInternaMySqlDAO;

/**
 * bonusapi/cases/ChangeStateLoyalty
 *
 * Actualizar estado de LealtadInterna
 *
 * Este recurso permite actualizar el estado de un registro en LealtadInterna usando su identificador.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada.
 *   - *Id* (int): Identificador de LealtadInterna a actualizar.
 *   - *State* (string): Nuevo estado de LealtadInterna ('A' para activo, 'I' para inactivo).
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
 * @throws Exception Si ocurre un error en la actualización del estado de LealtadInterna.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros a variables y establece un error inicial como falso. */
$Id = $params->Id;
$State = $params->State;


$error = false;
if ($Id != '' && ($State == 'A' || $State == 'I')) {


    /* Actualiza el estado de LealtadInterna y gestiona la respuesta de éxito. */
    $LealtadInterna = new LealtadInterna($Id);

    if ($LealtadInterna->estado != $State) {
        $LealtadInterna->estado = $State;

        $LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO();
        $LealtadInternaMySqlDAO->update($LealtadInterna);
        $LealtadInternaMySqlDAO->getTransaction()->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

    } else {
        /* establece una variable de error como verdadera en caso de una condición no cumplida. */

        $error = true;

    }


} else {
    /* Condición que establece `$error` como verdadero si no se cumple la expresión previa. */

    $error = true;

}

/* maneja un error, configurando el tipo y mensaje de alerta. */
if ($error) {
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}