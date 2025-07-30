<?php

use Backend\dto\Helpers;
use Backend\dto\Usuario;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioSitebuilder;
use Backend\mysql\UsuarioSitebuilderMySqlDAO;


/**
 * Agregar usuario a Sitebuilder.
 *
 * Este script valida y agrega un usuario al sistema Sitebuilder, asignándole una contraseña.
 *
 * @param $params object Objeto con los siguientes valores:
 * @param string $params->Password Contraseña del usuario.
 * @param int $params->UserBackofficeId ID del usuario en el sistema backoffice.
 *
 * @return array Respuesta con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 */


/* Valida contraseña y perfil de usuario, lanzando excepción si hay error. */
$Password = $params->Password;
$UserBackofficeId = $params->UserBackofficeId;

$UsuarioPerfil = new UsuarioPerfil($id);

if (empty($Password) || in_array($UsuarioPerfil->getPerfilId(), ['ADMIN', 'ADMIN2', 'ADMINAGGREGATOR', 'ADMINPARTNER', 'CUSTOM', 'SA'])) {
    throw new Exception('Error en los parametros enviados', '100001');
}


/* Se crean objetos para gestionar usuarios y ayuda en un sistema backoffice. */
$Usuario = new Usuario($UserBackofficeId);

$UsuarioSitebuilderMySqlDAO = new UsuarioSitebuilderMySqlDAO();
$UsuarioSitebuilder = new UsuarioSitebuilder();

$Helpers = new Helpers();


/* Código para asignar usuario, insertar en base de datos y cambiar la contraseña. */
$UsuarioSitebuilder->setUsuarioId($Usuario->usuarioId);
$UsuarioSitebuilder->setLogin($Usuario->login);

$UsuarioSitebuilderMySqlDAO->insert($UsuarioSitebuilder);
$UsuarioSitebuilderMySqlDAO->getTransaction()->commit();

$UsuarioSitebuilder->changeClave($Password);


/* Código inicializa un arreglo de respuesta sin errores, preparado para alertas y mensajes. */
$response = [];
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];

?>