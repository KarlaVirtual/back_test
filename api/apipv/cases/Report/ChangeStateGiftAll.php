<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


use Backend\dto\LealtadInterna;
use Backend\dto\UsuarioLealtad;
use Backend\mysql\LealtadInternaMySqlDAO;
use Backend\mysql\UsuarioLealtadMySqlDAO;
use Backend\dto\UsuarioMandante;
use Backend\dto\PuntoVenta;

/**
 * Report/ChangeStateGiftAll
 *
 * Actualización del estado de UsuarioLealtad
 *
 * Este recurso permite actualizar el estado de múltiples registros en la tabla `UsuarioLealtad`.
 * La actualización se realiza bajo ciertas condiciones, dependiendo del tipo de premio asociado y
 * del punto de venta de entrega.
 *
 * @param array $Ids : Lista de identificadores de los registros a actualizar.
 * @param string $State : Nuevo estado que se asignará a los registros seleccionados.
 * @param string $Observation : Observación que se agregará a los registros modificados.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna array vacío en caso de éxito.
 *
 * Objeto en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "error";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["ModelErrors"] = [];
 *
 * @throws Exception Si ocurre un error en la validación o actualización de los datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* almacena parámetros en variables y establece un indicador de error. */
$Ids = $params->Ids;
$State = $params->State;
$Observation = $params->Observation;


$error = false;

foreach ($Ids as $key => $value) {


    /* Código que instancia objetos de lealtad y obtiene información de usuario. */
    $UsuarioLealtad = new UsuarioLealtad($value);
    $LealtadInterna = new LealtadInterna($UsuarioLealtad->getLealtadId());

    $Puntoventaentrega = $UsuarioLealtad->puntoventaentrega;

    $continue = false;


    /* Validación de perfil y condiciones para continuar en un sistema de puntos de venta. */
    if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->getUsuarioMandante());
        if ($PuntoVenta->usuarioId !== $Puntoventaentrega) {
            $continue = false;
            throw new Exception("Error BetShop", "50010");
        } else {
            if (($UsuarioLealtad->estado == "R" && $LealtadInterna->tipoPremio == 0 && $UsuarioLealtad->puntoventaentrega !== 0 && $UsuarioLealtad->puntoventaentrega !== null && $UsuarioLealtad->puntoventaentrega !== "")) {
                $continue = true;
            }
        }
    }


    /* Actualiza el estado de usuario según condiciones y maneja respuestas de éxito. */
    if (($UsuarioLealtad->estado == "R" && $LealtadInterna->tipoPremio == 1) || ($UsuarioLealtad->estado == "R" && $LealtadInterna->tipoPremio == 0 && $UsuarioLealtad->puntoventaentrega == 0) || $continue === true) {

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
        /* asigna 'true' a la variable '$error' si se cumple la condición del 'else'. */

        $error = true;

    }
}


/* Manejo de errores que configura la respuesta con información de alerta. */
if ($error) {
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}