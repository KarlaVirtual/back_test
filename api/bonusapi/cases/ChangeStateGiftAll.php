<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


use Backend\dto\LealtadInterna;
use Backend\dto\UsuarioLealtad;
use Backend\mysql\LealtadInternaMySqlDAO;
use Backend\mysql\UsuarioLealtadMySqlDAO;

/**
 * bonusapi/cases/ChangeStateGiftAll
 *
 * Actualizar estado de usuarios en el programa de lealtad
 *
 * Este recurso permite actualizar el estado de uno o varios usuarios en el programa de lealtad,
 * validando que su estado actual sea "R" y que el tipo de premio asociado sea 1.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada.
 *   - *Ids* (array): Lista de identificadores de usuarios en el programa de lealtad.
 *   - *State* (string): Nuevo estado que se asignará al usuario.
 *   - *Observation* (string): Observación asociada al cambio de estado.
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
 * @throws Exception Si ocurre un error en la actualización del estado de los usuarios.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros y declara una variable de error. */
$Ids = $params->Ids;
$State = $params->State;
$Observation = $params->Observation;


$error = false;

foreach ($Ids as $key => $value) {


    /* Actualiza el estado de un usuario de lealtad basado en condiciones específicas. */
    $UsuarioLealtad = new UsuarioLealtad($value);
    $LealtadInterna = new LealtadInterna($UsuarioLealtad->getLealtadId());
    if ($UsuarioLealtad->estado == "R" && $LealtadInterna->tipoPremio == 1) {
        $UsuarioLealtad->estado = $State;
        $UsuarioLealtad->observacion = $Observation;
        $UsuarioLealtad->fechaModif = date("Y-m-d H:i:s");;

        $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO();
        $UsuarioLealtadMySqlDAO->updateState($UsuarioLealtad);
        $UsuarioLealtadMySqlDAO->getTransaction()->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
    } else {
        /* establece una variable de error si no se cumple cierta condición. */

        $error = true;

    }
}


/* maneja errores, configurando la respuesta con información sobre el error. */
if ($error) {
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}