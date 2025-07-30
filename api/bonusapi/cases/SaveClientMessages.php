<?php
/**
 * Guarda mensajes enviados a múltiples clientes en la base de datos.
 *
 * @param array $params Arreglo de objetos que contiene:
 * @param int $params->ClientId Identificador del cliente.
 * @param string $params->Message Contenido del mensaje.
 * @param string $params->Title Título del mensaje.
 * 
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (success o danger).
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Lista de errores del modelo.
 *  - array $Data Datos adicionales (vacío en este caso).
 *
 * @throws Exception Si ocurre un error al insertar mensajes en la base de datos.
 */

/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioToken;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\websocket\WebsocketUsuario;

foreach ($params as $key => $value) {
    /* Asigna valores de un objeto a variables: ClientId, Message y Title. */
    $ClientId = $value->ClientId;
    $Message = $value->Message;
    $Title = $value->Title;

    try {

        /* Se crean instancias de UsuarioMandante y UsuarioMensaje con identificadores específicos. */
        $UsuarioMandante = new UsuarioMandante("", $ClientId, '0');
        $msg = "entro4";

        $UsuarioMensaje = new UsuarioMensaje();
        $UsuarioMensaje->usufromId = 0;
        $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;

        /* inicializa un mensaje de usuario y crea un objeto DAO para manipularlo. */
        $UsuarioMensaje->isRead = 0;
        $UsuarioMensaje->body = $Message;
        $UsuarioMensaje->msubject = $Title;
        $msg = "entro5";

        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();

        /* Código que inserta un mensaje en MySQL y envía actualización vía WebSocket. */
        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

        /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
        //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

        /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
        /*$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
        $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
        $WebsocketUsuario->sendWSMessage();*/

        $msg = "entro6";

    } catch (Exception $e) {
        /* Captura excepciones y almacena el mensaje de error en una variable. */

        $msg = $e->getMessage();

    }
}

/* inicializa una respuesta estructurada para una operación exitosa. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = $msg;
$response["ModelErrors"] = [];

$response["Data"] = [];
