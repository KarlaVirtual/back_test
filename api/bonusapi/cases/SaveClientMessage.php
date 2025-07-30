<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioToken;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * Guarda un mensaje enviado a un cliente específico en la base de datos.
 *
 * @param object $params Objeto que contiene:
 * @param int $params->ClientId Identificador del cliente.
 * @param string $params->Message Contenido del mensaje.
 * @param string $params->Title Título del mensaje.
 * @param int $params->ParentId Identificador del mensaje padre (si aplica).
 * 
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (success o error).
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Lista de errores del modelo.
 *  - array $Data Datos adicionales (vacío en este caso).
 *
 * @throws Exception Si ocurre un error al insertar o actualizar mensajes en la base de datos.
 */

/* Asignación de valores de los parámetros a variables en un script de programación. */
$ClientId = $params->ClientId;
$Message = $params->Message;
$Title = $params->Title;
$ParentId = $params->ParentId;


if ($ClientId != "") {
    try {

        /* Se crean instancias de UsuarioMandante y UsuarioMensaje, configurando propiedades específicas. */
        $UsuarioMandante = new UsuarioMandante($ClientId);
        $msg = "entro4";

        $UsuarioMensaje2 = new UsuarioMensaje($ParentId);
        $UsuarioMensaje2->isRead = 1;

        $UsuarioMensaje = new UsuarioMensaje();

        /* Configura un mensaje de usuario con ID de remitente, destinatario y contenido específico. */
        $UsuarioMensaje->usufromId = 0;
        $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
        $UsuarioMensaje->isRead = 0;
        $UsuarioMensaje->body = $Message;
        $UsuarioMensaje->msubject = $Title;
        $UsuarioMensaje->parentId = $ParentId;

        /* Código para insertar y actualizar mensajes de usuario en una base de datos MySQL. */
        $msg = "entro5";

        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
        $UsuarioMensajeMySqlDAO->getTransaction()->commit();


        /* Se crea un usuario y se envía un mensaje para actualizar su saldo. */
        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

        /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
        $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

        /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
        /*$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
        $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
        $WebsocketUsuario->sendWSMessage();*/

        $msg = "entro6";

    } catch (Exception $e) {
        /* Captura excepciones y almacena el mensaje de error en la variable `$msg`. */

        $msg = $e->getMessage();

    }

    /* estructura una respuesta con éxito, alertas y datos vacíos. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = $msg . " - " . $ClientId;
    $response["ModelErrors"] = [];

    $response["Data"] = [];

} else {
    /* Configura una respuesta de error ante datos incorrectos en un sistema. */

    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "Datos incorrectos";
    $response["ModelErrors"] = [];

    $response["Data"] = [];
}
