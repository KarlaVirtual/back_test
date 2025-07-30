<?php

use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioSession;
use Backend\mysql\UsuarioSessionMySqlDAO;

/**
 * command/update_token_fcm
 *
 * Actualizar token de sesion del usuario
 *
 * @param string $tokenFCM : token nuevo
 *
 * @return object El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor 0 en caso de exito
 *  - *rid* (string): Contiene el mensaje de error.
 *
 * @throws Exception No existe - el token original
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se obtiene el token de FCM y se crea un nuevo usuario mandante. */
$tokenFCM = $json->params->tokenFCM;

$UsuarioMandante = new UsuarioMandante($json->session->usuario);

if (!empty($tokenFCM)) {

    /* Validación de token FCM y actualización del estado de sesión de usuario. */
    try {
        $UsuarioSession = new UsuarioSession(3, '', 'A', '', $UsuarioMandante->usumandanteId);
        if ($tokenFCM !== $UsuarioSession->requestId) {
            $UsuarioSession->setEstado('I');
            $UsuarioSession->setTipo(3);

            $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO();
            $UsuarioSessionMySqlDAO->update($UsuarioSession);
            $UsuarioSessionMySqlDAO->getTransaction()->commit();
            throw new Exception("No existe ", "99");
        }
    } catch (Exception $ex) {
        /* Manejo de excepciones que crea y guarda una sesión de usuario en la base de datos. */

        if ($ex->getCode() == 99) {
            $UsuarioSession = new UsuarioSession();
            $UsuarioSession->setTipo(3);
            $UsuarioSession->setRequestId($tokenFCM);
            $UsuarioSession->setUsuarioId($UsuarioTokenSite->usuarioId);
            $UsuarioSession->setEstado('A');
            $UsuarioSession->setPerfil('');
            $UsuarioSession->setUsucreaId('0');
            $UsuarioSession->setUsumodifId('0');

            $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO();
            $UsuarioSessionMySqlDAO->insert($UsuarioSession);
            $UsuarioSessionMySqlDAO->getTransaction()->commit();
        }
    }


    /* crea un array de respuesta con un código y un identificador. */
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
}


/* inicializa un arreglo de respuesta con un código y un ID. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;