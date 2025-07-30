<?php

use Backend\dto\UsuarioSitebuilder;
use Backend\mysql\UsuarioSitebuilderMySqlDAO;

/**
 * UsuarioSitebuilder/Actualizar
 *
 * Actualiza el estado y/o la contraseña de un usuario en el sistema Sitebuilder.
 *
 * Este recurso permite modificar el estado de un usuario (activo/inactivo) y/o cambiar su contraseña.
 * Si el estado cambia a activo ('A') y el usuario tenía intentos fallidos, estos se restablecen a cero.
 *
 * @param int $Id : Identificador único del usuario en Sitebuilder.
 * @param string $NewPassword : Nueva contraseña del usuario (opcional).
 * @param int $State : Estado del usuario (1 = Activo, 0 = Inactivo).
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna un array vacío en caso de éxito.
 *
 * Objeto en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["ModelErrors"] = [];
 *
 * @throws Exception Error en los parámetros enviados (código de error: 100001).
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* presenta instrucciones para realizar tareas específicas utilizando programación. */
$Id = $params->Id;
$NewPassword = $params->NewPassword;
$State = $params->State == 1 ? 'A' : 'I';

if (empty($Id)) throw new Exception('Error en los parametros enviados', '100001');


/*Obtención del usuario sitebuilder*/
$UsuarioSitebuilder = new UsuarioSitebuilder($Id);

if (!empty($State) && $State !== $UsuarioSitebuilder->getEstado()) {
    if ($State === 'A' && $UsuarioSitebuilder->getIntentos() > 0) $UsuarioSitebuilder->setIntentos(0);
    $UsuarioSitebuilder->setEstado($State);
    $UsuarioSitebuilderMySqlDAO = new UsuarioSitebuilderMySqlDAO();
    $UsuarioSitebuilderMySqlDAO->update($UsuarioSitebuilder);
    $UsuarioSitebuilderMySqlDAO->getTransaction()->commit();
}


/* Actualización de credenciales */
if (!empty($NewPassword)) $UsuarioSitebuilder->changeClave($NewPassword);


/* Código PHP inicializa una respuesta JSON sin errores y con mensaje de éxito. */
$response = [];
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['']
?>