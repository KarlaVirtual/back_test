<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\websocket\WebsocketUsuario;

/**
 * Machine/sendCommand
 *
 * Este script permite enviar comandos a una máquina, gestionar usuarios y sesiones, 
 * subir archivos y enviarlos a Google Cloud Storage, y enviar mensajes a través de WebSocket.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->Id Identificador de la máquina.
 * @param string $params->Command Comando a ejecutar en la máquina.
 *
 * @return array $response Respuesta del sistema con los siguientes valores:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si el ID está vacío o si no se puede subir el archivo.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/* define una función para cifrar datos usando una clave y vector de inicialización. */
$key = '12345678901234567890123456789012';

/**
 * Encripta datos usando AES-256-CBC.
 *
 * @param string $data Los datos a encriptar.
 * @param string $key La clave de encriptación.
 * @return string El mensaje encriptado.
 */
function my_encrypt($data, $key)
{
    $encryption_key = ($key);
    $iv = "1234567890123456";

    /* encripta y desencripta mensajes usando AES-256 en CBC. */
    $encryptedMessage = openssl_encrypt($data, "AES-256-CBC", $encryption_key, 0, $iv);
    return $encryptedMessage;
}


/**
 * Desencripta datos usando AES-256-CBC.
 *
 * @param string $data Los datos encriptados.
 * @param string $key La clave de encriptación.
 * @return string Los datos desencriptados.
 */
function my_decrypt($data, $key)
{
    $encryption_key = ($key);

    /* Desencripta datos usando AES-256-CBC con clave y vector de inicialización. */
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}

$Id = $params->Id;

/* valida si el ID en POST está vacío y lanza una excepción. */
$Command = $params->Command;

$Id = $_POST["Id"];

if ($Id == "") {
    throw new Exception("Inusual Detected", "11");

}

/* Código para gestionar usuarios, mandantes y enviar mensajes por websocket usando PHP. */
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


/* Genera un nombre de archivo y crea un directorio si no existe. */
$filename = $name . "T" . time() . '.zip';

$dirsave = '/tmp/' . $filename;

if (!file_exists('/home/home2/backend/api/apipv/cases/Machine/files/')) {
    mkdir('/home/home2/backend/api/apipv/cases/Machine/files/', 0755, true);
}

/* sube un archivo y lo copia a Google Cloud Storage. */
if (move_uploaded_file($_FILES['upload']['tmp_name'], $dirsave)) {
    //$code = file_get_contents($dirsave); //Get the code to be encypted.
    //$encrypted_code = my_encrypt($code, $key); //Encrypt the code.

    //file_put_contents($dirsave, $encrypted_code); //Save the ecnypted code somewhere.

    shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/machine/');

} else {
    /* lanza una excepción si no se logra subir un archivo. */


    throw new Exception('No se pudo subir el archivo');
}

/* Crea reglas de validación para una sesión de usuario y las imprime. */
$UsuarioSession = new UsuarioSession();
$rules = [];

array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
print_r($rules);


/* filtra usuarios, los convierte a JSON y los decodifica nuevamente. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

$usuarios = json_decode($usuarios);


/* Crea un array final de usuarios y envía mensajes mediante Websocket con URLs. */
$usuariosFinal = [];
$ConfigurationEnvironment = new ConfigurationEnvironment();

foreach ($usuarios->data as $key => $value) {
    $fName = "https://backofficeapi.virtualsoft.tech/cases/Machine/files/" . $filename;
    $fName = "https://affiliatesapi.virtualsoft.tech/Bonus/Image/getFile?r=" . $ConfigurationEnvironment->encrypt($filename);
    $fName = "https://backofficeapi.virtualsoft.tech/Machine/File?r=" . $ConfigurationEnvironment->encrypt($filename);

    $data = array(
        "messageIntern" => "updBase",
        "value" => $fName
    );


    print_r($data);


    $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
    $WebsocketUsuario->sendWSMessage();

}


/* inicializa una respuesta sin errores y con mensaje de éxito. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];



