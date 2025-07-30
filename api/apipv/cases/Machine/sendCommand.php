<?php

use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\websocket\WebsocketUsuario;

/**
 * Machine/sendCommand
 *
 * Este script envía un comando a una máquina específica utilizando WebSocket.
 *
 * @param array $params Parámetros de entrada:
 * @param int $params->Id ID del usuario asociado a la máquina.
 * @param string $params->Command Comando a ejecutar en la máquina.
 *
 * @return array Respuesta en formato JSON:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta.
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si ocurre un error durante el envío del comando.
 */


/* maneja usuarios y envía mensajes a través de WebSocket. */
$Id = $params->Id;
$Command = $params->Command;

$Usuario = new Usuario($Id);
$UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
/*
$UsuarioToken = new UsuarioToken("",'0', $UsuarioMandante->usumandanteId);

$data = array(
    "messageIntern"=>"execCommand",
    "value"=>$Command

);
$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);

$WebsocketUsuario->sendWSMessage();*/

$UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

/*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */

/* Se crea un token de usuario y se obtiene un mensaje para actualizar el saldo. */
$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

/*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */


$UsuarioSession = new UsuarioSession();

/* Se crean reglas para filtrar datos mediante condiciones específicas de igualdad. */
$rules = [];

array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Convierte un filtro a JSON y obtiene usuarios personalizados desde una sesión. */
$json2 = json_encode($filtro);


$usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json2, true);

$usuarios = json_decode($usuarios);


/* Crea y envía mensajes de websocket para usuarios, modificando datos según su sesión. */
$usuariosFinal = [];

foreach ($usuarios->data as $key => $value) {

    $dataF = str_replace("7040" . $UsuarioToken->getRequestId() . "1", "7040" . $value->{'usuario_session.request_id'} . "1", $data);
    $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $dataF);
    $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});

}


/* Se define un filtro con reglas para validar sesiones de usuario. */
$UsuarioSession = new UsuarioSession();
$rules = [];

array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Convierte un filtro a JSON y obtiene usuarios desde una sesión personalizada. */
$json = json_encode($filtro);


$usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

$usuarios = json_decode($usuarios);


/* Se inicializa un array vacío llamado $usuariosFinal para almacenar datos posteriormente. */
$usuariosFinal = [];

foreach ($usuarios->data as $key => $value) {

    /* Crea un objeto de configuración y prepara datos para una ejecución de comando. */
    $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
    $idRecarga = $ConfigurationEnvironment->encrypt('4672452');

    $data = array(
        "messageIntern" => "execCommand",
        "value" => $Command,
        "machinePrint" => '',
        "continueToFront" => 1,
        "billenabled" => false,
        "machinePrintURL" => 'https://operatorapi.virtualsoft.tech/machine/machineprint?id=' . $idRecarga

    );

    /*"machinePrint" => '',
        "continueToFront" => 1,
        "billenabled" => false,
        "machinePrintURL" => 'https://operatorapi.virtualsoft.tech/machine/machineprint?id='.$idRecarga*/


    /* Código que crea un objeto WebsocketUsuario y envía un mensaje mediante WebSocket. */
    $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
    $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});

}


/* define una respuesta sin errores, con tipo y mensaje de alerta exitosos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
