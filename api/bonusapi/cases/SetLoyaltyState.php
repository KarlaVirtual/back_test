<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\LealtadInterna;
use Backend\mysql\LealtadInternaMySqlDAO;

/**
 * Cambia el estado de un programa de lealtad (habilitado o deshabilitado).
 *
 * @param int $_REQUEST["id"] Identificador del programa de lealtad.
 * @param bool $_REQUEST["enabled"] Indica si el programa debe estar habilitado (true) o deshabilitado (false).
 *
 * @return array $response Respuesta del sistema con los siguientes valores:
 *  - HasError: bool Indica si hubo un error.
 *  - AlertType: string Tipo de alerta (success, danger, etc.).
 *  - AlertMessage: string Mensaje de alerta.
 *  - ModelErrors: array Errores específicos del modelo.
 */

/* establece un estado 'A' basado en la solicitud de habilitación. */
$lealtadId = $_REQUEST["id"];
$enabled = $_REQUEST["enabled"];

$estado = 'A';

if ($enabled) {
    $estado = 'A';

} else {
    /* asigna el valor 'I' a la variable $estado si se cumple una condición. */

    $estado = 'I';

}


/* actualiza el estado de un objeto LealtadInterna en la base de datos. */
$LealtadInterna = new LealtadInterna($lealtadId);
$LealtadInterna->estado = "I";

$LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO();
$LealtadInternaMySqlDAO->update($LealtadInterna);
$LealtadInternaMySqlDAO->getTransaction()->commit();


/* inicializa un arreglo de respuesta para manejar errores en una aplicación. */
$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];