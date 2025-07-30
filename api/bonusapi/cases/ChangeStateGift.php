<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


use Backend\dto\LealtadInterna;
use Backend\dto\UsuarioLealtad;
use Backend\mysql\LealtadInternaMySqlDAO;
use Backend\mysql\UsuarioLealtadMySqlDAO;

/**
 * bonusapi/cases/ChangeStateGift
 *
 * Este recurso permite actualizar el estado de un usuario en el sistema de lealtad. Si se recibe un ID válido
 * y el estado es 'D' (desactivado), el recurso verifica el estado actual del usuario y el tipo de premio asociado
 * al programa de lealtad. Si se cumplen las condiciones necesarias (el usuario está en estado "R" y el tipo de premio
 * es igual a 1), se actualiza el estado del usuario y se registra una observación. Después, se realiza la actualización
 * en la base de datos.
 *
 * @param object $params : Objeto que contiene los parámetros necesarios para la operación:
 *   - *Id* (string): ID del usuario que se desea actualizar.
 *   - *State* (string): Nuevo estado del usuario ('D' para desactivado).
 *   - *Observation* (string): Observación que se debe registrar para el usuario.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista (success, error, etc.).
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Contiene errores específicos si los hubo.
 *
 * Objeto en caso de error:
 *
 * "HasError" => true,
 * "AlertType" => "error",
 * "AlertMessage" => "Mensaje de error",
 * "ModelErrors" => array(),
 *
 * @throws Exception Error general en la ejecución de la operación, como problemas con la base de datos,
 *                   errores al actualizar el estado del usuario o cualquier otra excepción que pueda ocurrir.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de `$params` a variables y establece un error inicial en falso. */
$Id = $params->Id;
$State = $params->State;
$Observation = $params->Observation;


$error = false;
if ($Id != '' && $State == 'D') {


    /* Actualiza el estado de un usuario leal basado en condiciones específicas. */
    $UsuarioLealtad = new UsuarioLealtad($Id);

    $LealtadInterna = new LealtadInterna($UsuarioLealtad->getLealtadId());
    if ($UsuarioLealtad->estado == "R" && $LealtadInterna->tipoPremio == 1) {
        $UsuarioLealtad->estado = $State;
        $UsuarioLealtad->observacion = $Observation;

        $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO();
        $UsuarioLealtadMySqlDAO->updateState($UsuarioLealtad);
        $UsuarioLealtadMySqlDAO->getTransaction()->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
    } else {
        /* Código que establece un error si no se cumple cierta condición. */

        $error = true;
    }
} else {
    /* Esta parte del código establece una variable de error si no se cumple una condición. */

    $error = true;

}

/* maneja errores, configurando respuestas apropiadas en un arreglo. */
if ($error) {
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}
