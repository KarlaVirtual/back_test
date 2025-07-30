<?php

use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioMandante;

/**
 * Procesa una solicitud de juego de bingo y genera una respuesta con la URL del juego.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 *  - params:object Parámetros de la solicitud.
 *    - site_id:int Identificador del sitio.
 *    - gameCode:string Código del juego.
 *    - lang:string Idioma del juego.
 *    - isMobile:bool Indica si el juego se ejecuta en un dispositivo móvil.
 *    - token:string Token de autenticación.
 *  - session:object Información de la sesión del usuario.
 *    - usuario:string Identificador del usuario.
 *  - rid:string Identificador de la respuesta.
 *
 * @return array Respuesta con el código de estado, identificador de respuesta y datos del juego.
 *  - code:int Código de respuesta.
 *  - rid:string Identificador de respuesta.
 *  - data:array Datos de la respuesta.
 *    - result:string URL del juego.
 *
 * @throws Exception Si ocurre un error al obtener la URL del juego.
 */

/* crea un arreglo JSON con un código y un identificador de respuesta. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""
);

/* procesa parámetros JSON para crear instancias de usuario y obtener información. */
$params = $json->params;
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

$site_id = $json->params->site_id;
$GameCode = $json->params->gameCode;
$lang = $json->params->lang;
$isMobile = $json->params->isMobile;
$token = $json->params->token;

try {
    /*Obtiene instancias del proveedor*/
    $Proveedor = new Proveedor("", "IESGAMES");
    $Producto = new Producto("", "IESGAMES", $Proveedor->getProveedorId());

    $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
    $URL = $IESGAMESSERVICES->getGame($GameCode, $lang, false, $token, $Producto->getProductoId(), $isMobile, $UsuarioMandante->getUsumandanteId());

    $url = str_replace('\/', '/', $URL->url);
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = stripslashes($url);
} catch (Exception $e) {
    /* captura excepciones en PHP, permitiendo manejar errores sin detener la ejecución. */
}
