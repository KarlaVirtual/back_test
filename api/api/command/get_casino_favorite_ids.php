<?php
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioFavorito;

/**
 * Obtiene los IDs favoritos de un usuario.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param object $json->params Objeto que contiene los parámetros de la solicitud.
 * @param int $json->params->user_id ID del usuario.
 * @param int $json->rid Identificador de la solicitud.
 *
 * @throws Exception Si los parámetros son inválidos.
 *
 * @return array
 *  - code:int Código de respuesta.
 *  - rid:int Identificador de la solicitud.
 *  - data:array Datos de la respuesta.
 *      - AlertMessage:string Mensaje de alerta.
 *      - favorites:array Lista de favoritos del usuario.
 */

$params = $json->params;

//Recepción de parámetros
$userId = $params->user_id;

//Sanitización de parámetros
$safeParameters = true;

$idsUnsafePattern = '/\D/';
if (preg_match($idsUnsafePattern, $userId)) $safeParameters = false;

if (!$safeParameters) throw new exception('Parametros invalidos', 300023);

//Solicitud de favoritos
$Usuario = new Usuario($userId);
$UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

$UsuarioFavorito = new UsuarioFavorito();
$favorites = $UsuarioFavorito->getUsuarioProductosFavoritos($UsuarioMandante->getUsumandanteId());

//Formateo de respuesta
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"]["AlertMessage"] = "Ejecucion exitosa";
$response["data"]["favorites"] = $favorites;
?>
