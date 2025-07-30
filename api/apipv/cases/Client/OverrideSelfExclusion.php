<?php

use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\dto\Usuario;

/**
 * Client/OverrideSelfExclusion
 *
 * Este script permite anular la autoexclusión de un usuario.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param int $params ->Id ID de la autoexclusión a anular.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "danger").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si no se puede actualizar el estado de la autoexclusión.
 */


/* obtiene datos JSON y crea un objeto UsuarioConfiguracion con un ID específico. */
$params = file_get_contents('php://input');
$params = json_decode($params);
$Usermandante = $_SESSION['mandante'];
$idAutoexclusion = $params->Id;

$UsuarioConfiguracion = new UsuarioConfiguracion("", "", "", "", $idAutoexclusion);

/* Código que actualiza el estado del usuario a 'C' si está activo. */
$UserID = $UsuarioConfiguracion->getUsuarioId();
$Usuario = new Usuario($UserID);

if ($UsuarioConfiguracion->getEstado() == 'A') {
    $UsarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
    $UsuarioConfiguracion->setEstado('C');
    $UsarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
    $UsarioConfiguracionMySqlDAO->getTransaction()->commit();
}

/* Código que define una respuesta sin errores, con tipo y mensaje de alerta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
