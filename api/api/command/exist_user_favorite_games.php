<?php
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioFavorito;

/**
 *Indica si existen juegos favoritos por parte de usuario
 *
 * @param int $params->user_id Id de usuario
 *
 *@return array
 * - code: int Código de respuesta
 * - rid: string Id de respuesta
 * - data: array
 *  - ExistFavorites: bool Indica si existen juegos favoritos
 */

$params = $json->params;

//Recibiendo parámetros
$userId = $params->user_id;

//Sanitizando parámetros
$safeParameters = true;

$idsUnsafePattern = '/\D/';
if (preg_match($idsUnsafePattern, $userId)) $safeParameters = false;

if (!$safeParameters) throw new exception('Parametros invalidos', 300023);

//Verificando si cuenta con productos favoritos
$Usuario = new Usuario($userId);
$UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

$UsuarioFavorito = new UsuarioFavorito();
$favoriteGamesIds = $UsuarioFavorito->getUsuarioProductosFavoritos($UsuarioMandante->getUsumandanteId());

$existFavorites = false;
if (count($favoriteGamesIds) > 0) $existFavorites = true;

//Formatendo respuesta
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"]["ExistFavorites"] = $existFavorites;
?>
