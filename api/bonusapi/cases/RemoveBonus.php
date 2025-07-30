<?php
/**
 * Este script maneja la lógica para eliminar un bono de usuario, actualizando el saldo y el estado del bono en la base de datos.
 * 
 * @param object $params Objeto que contiene los parámetros necesarios para la operación.
 * @param int $params->BonusId ID del bono que se desea eliminar.
 * 
 * @return array $response Respuesta de la operación que incluye:
 *                         - "HasError" (bool): Indica si ocurrió un error.
 *                         - "AlertType" (string): Tipo de alerta ("success" o "error").
 *                         - "AlertMessage" (string): Mensaje de alerta.
 *                         - "ModelErrors" (array): Lista de errores del modelo, si los hay.
 */

/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Registro;
use Backend\dto\UsuarioBono;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;


/* Se asigna un ID de bono y se crea un objeto UsuarioBono con ese ID. */
$BonusId = $params->BonusId;

$UsuarioBono = new UsuarioBono($BonusId);

if ($UsuarioBono->estado != "R" && $UsuarioBono->estado != "E" && $UsuarioBono->estado != "I") {


    /* Actualiza el saldo en la base de datos si se cumplen ciertas condiciones del usuario. */
    $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();

    if ($UsuarioBono->estado == 'A' && floatval($UsuarioBono->rollowerRequerido) > 0) {
        $Registro = new Registro("", $UsuarioBono->getUsuarioId());

        $RegistroMySqlDAO = new RegistroMySqlDAO($UsuarioBonoMySqlDAO->getTransaction());

        $update = $RegistroMySqlDAO->updateBalance($Registro, "", "", -$UsuarioBono->getValor(), "", "", false);

    }


    /* Actualizar el estado del usuario y confirmar la transacción sin errores. */
    $UsuarioBono->estado = "I";

    $UsuarioBonoMySqlDAO->update($UsuarioBono);
    $UsuarioBonoMySqlDAO->getTransaction()->commit();

    $response["HasError"] = false;

    /* inicializa una respuesta de éxito sin mensajes o errores. */
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

} else {
    /* establece una respuesta sin errores, indicando éxito. */

    $response["HasError"] = true;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}