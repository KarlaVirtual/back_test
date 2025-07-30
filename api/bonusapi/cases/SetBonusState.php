<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\BonoInterno;
use Backend\mysql\BonoInternoMySqlDAO;

/**
 * Cambia el estado de un bono (habilitado o deshabilitado).
 *
 * @param int $_REQUEST["id"] Identificador del bono.
 * @param bool $_REQUEST["enabled"] Indica si el bono debe estar habilitado (true) o deshabilitado (false).
 *
 * @return array $response Respuesta del sistema con los siguientes valores:
 *  - HasError: bool Indica si hubo un error.
 *  - AlertType: string Tipo de alerta (success, danger, etc.).
 *  - AlertMessage: string Mensaje de alerta.
 *  - ModelErrors: array Errores específicos del modelo.
 */

/* asigna 'A' a $estado si $enabled está presente como verdadero. */
$bonoId = $_REQUEST["id"];
$enabled = $_REQUEST["enabled"];

$estado = 'A';

if ($enabled) {
    $estado = 'A';

} else {
    /* Define una variable `$estado` con el valor 'I' si no se cumple la condición previa. */

    $estado = 'I';

}


/* Se actualiza el estado de un bono interno en la base de datos. */
$BonoInterno = new BonoInterno($bonoId);
$BonoInterno->estado = "I";

$BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
$BonoInternoMySqlDAO->update($BonoInterno);
$BonoInternoMySqlDAO->getTransaction()->commit();


/* inicializa una respuesta sin errores y define un tipo de alerta. */
$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
