<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\PerfilSubmenu;
use Backend\mysql\PerfilSubmenuMySqlDAO;

/**
 * Guarda los permisos asignados a un rol específico en la base de datos.
 *
 * @param array $params Arreglo de objetos que contiene:
 * @param int $params->Id Identificador del permiso.
 * @param string $params->Name Nombre del permiso.
 * @param bool $params->IsGiven Indica si el permiso está asignado.
 * @param string $params->Action Acción asociada al permiso.
 * @param bool $params->Selected Indica si el permiso está seleccionado.
 * @param int $params->PermissionId Identificador del permiso en la base de datos.
 * @param int $params->UserId Identificador del usuario que realiza la acción.
 * 
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (success o danger).
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Lista de errores del modelo.
 *  - array $Data Datos adicionales (vacío en este caso).
 *
 * @throws Exception Si ocurre un error al insertar o eliminar permisos en la base de datos.
 */

foreach ($params as $key => $value) {


    /* asigna propiedades de un objeto a variables en PHP. */
    $Id = $value->Id;
    $Name = $value->Name;
    $IsGiven = $value->IsGiven;
    $Action = $value->Action;
    $Selected = $value->Selected;
    $PermissionId = $value->PermissionId;

    /* gestiona roles y elimina un submenu de un perfil en una base de datos. */
    $UserId = $value->UserId;
    $role = $_REQUEST["roleId"];

    try {
        $msg = "entro4";

        $PerfilSubmenu = new PerfilSubmenu($role, $Id);

        if (!$IsGiven) {
            $PerfilSubmenuMySqlDAO = new PerfilSubmenuMySqlDAO();
            $PerfilSubmenuMySqlDAO->delete($PerfilSubmenu->perfilId, $PerfilSubmenu->submenuId);
            $PerfilSubmenuMySqlDAO->getTransaction()->commit();
            $msg = "entro5";

        }

    } catch (Exception $e) {
        /* maneja excepciones y gestiona la inserción de permisos de usuario. */

        $msg = "entro2";

        if ($IsGiven) {
            $PerfilSubmenu = new PerfilSubmenu();
            $PerfilSubmenu->perfilId = $UserId;
            $PerfilSubmenu->submenuId = $PermissionId;
            $PerfilSubmenu->adicionar = 'true';
            $PerfilSubmenu->editar = 'true';
            $PerfilSubmenu->eliminar = 'true';
            $msg = "entro";
            $PerfilSubmenuMySqlDAO = new PerfilSubmenuMySqlDAO();
            $PerfilSubmenuMySqlDAO->insert($PerfilSubmenu);
            $PerfilSubmenuMySqlDAO->getTransaction()->commit();
        }
    }
}

/* Código PHP que define una respuesta estructurada sin errores y con mensaje de éxito. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = $msg;
$response["ModelErrors"] = [];

$response["Data"] = [];