<?php

use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\websocket\WebsocketUsuario;

/**
 * Machine/UpdateTwo
 *
 * Este script actualiza la base de datos de una máquina y envía un archivo encriptado.
 *
 * @param array $params Parámetros de entrada:
 * @param int $params->Id ID del usuario asociado a la máquina.
 * @param string $params->Command Comando a ejecutar en la máquina.
 * @param file $params->upload Archivo a ser encriptado y enviado.
 *
 * @return array Respuesta en formato JSON:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta.
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si ocurre un error durante la encriptación o envío del archivo.
 */

/* Función que encripta datos usando AES-256-CBC con una clave base64. */
$key = 'bRuD1325WYw5wd0rdHR9yLlM6wt213vteuiniQBqE70hU=';

function my_encrypt($data, $key)
{
    $encryption_key = base64_decode($key);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

    /* Código que cifra y descifra datos usando AES-256 con OpenSSL en PHP. */
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function my_decrypt($data, $key)
{
    $encryption_key = base64_decode($key);

    /* Desencripta datos usando AES-256-CBC con clave y vector de inicialización proporcionados. */
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}


$Id = $params->Id;

/* crea un objeto de usuario y prepara un mensaje para enviar por WebSocket. */
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


$fileTypeName = "zip";


/* Crea un archivo único en una carpeta, asegurando que exista primero. */
$filename = $name . "T" . time() . '.' . $fileTypeName;

$dirsave = '/tmp/' . $filename;

if (!file_exists('/home/home2/backend/api/apipv/cases/Machine/files/')) {
    mkdir('/home/home2/backend/api/apipv/cases/Machine/files/', 0755, true);
}


/* maneja la carga y encriptación de archivos subidos. */
if (move_uploaded_file($_FILES['upload']['tmp_name'], $dirsave)) {
    $code = file_get_contents($dirsave); //Get the code to be encypted.
    $encrypted_code = my_encrypt($code, $key); //Encrypt the code.

    file_put_contents($dirsave, $encrypted_code); //Save the ecnypted code somewhere.

} else {
    /* Estructura condicional vacía que no ejecuta ninguna acción si la condición no se cumple. */

}


/* Se definen reglas para filtrar sesiones de usuario activas por ID. */
$UsuarioSession = new UsuarioSession();
$rules = [];

array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte un filtro a JSON y obtiene usuarios personalizados desde una sesión. */
$json = json_encode($filtro);


$usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

$usuarios = json_decode($usuarios);


/* envía un mensaje mediante WebSocket a múltiples usuarios con información específica. */
$usuariosFinal = [];

foreach ($usuarios->data as $key => $value) {

    $data = array(
        "messageIntern" => "updBase",
        "value" => "http://backofficeapi.virtualsoft.tech/cases/Machine/files/" . $filename
    );

    $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
    $WebsocketUsuario->sendWSMessage();

}


/* inicializa una respuesta sin errores y lista para mostrar alertas. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];



