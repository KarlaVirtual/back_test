<?php

use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\ConfigurationEnvironment;

/**
 * command/check_user_online_email
 *
 * Verifica el usuario través de su correo electrónico.
 *
 * @param no
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *rid* (string): Identificador de la solicitud.
 *  - *data* (array): Contiene el resultado de la verificación.
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Código que inicializa usuarios y verifica configuración de entorno para email. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

$ConfigurationEnvironment = new ConfigurationEnvironment();
$ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario, true, true);

$response = [];

/* Se inicializa un arreglo de respuesta con código, datos vacíos y un identificador. */
$response['code'] = 0;
$response['data'] = [];
$response['rid'] = $json->rid;
?>