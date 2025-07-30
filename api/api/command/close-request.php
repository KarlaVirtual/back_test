<?php

use Backend\dto\UsuarioSession;
use Backend\mysql\UsuarioSessionMySqlDAO;
/**
 * Se obtiene el identificador de recurso del objeto JSON.
 * Si el identificador de recurso no está vacío,
 * se crea una nueva sesión de usuario, se establece el identificador
 * de solicitud y se actualiza la sesión en la base de datos,
 * finalmente, se confirma la transacción.
 */

$requestId = $json->resourceId;

if($requestId != ''){
    $UsuarioSession = new UsuarioSession();
    $UsuarioSession->setRequestId($requestId);

    $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO();
    $UsuarioSessionMySqlDAO->updateClose($UsuarioSession);
    $UsuarioSessionMySqlDAO->getTransaction()->commit();
}
