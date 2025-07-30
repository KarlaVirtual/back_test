<?php

use Backend\dto\UsuarioFavorito;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\mysql\UsuarioFavoritoMySqlDAO;
use Backend\sql\Transaction;

/**
 * Maneja los juegos favoritos de un usuario [Adición y remoción].
 *
 * @param object $json Objeto JSON que contiene los parámetros de entrada y la sesión del usuario.
 * - params: Objeto que contiene los parámetros de entrada.
 *   - user: ID del usuario.
 *   - game_id: ID del juego.
 *   - status: Estado del juego favorito ('I' para inactivo, 'A' para activo).
 * - session: Objeto que contiene la información de la sesión del usuario.
 *   - usuario: ID del operador de usuario.
 *
 * @return void Modifica el array $response con el código de respuesta y el mensaje de alerta.
 * - code: Código de respuesta (0 para éxito).
 * - rid: ID de la solicitud.
 * - data: Datos adicionales de la respuesta.
 *   - AlertMessage: Mensaje de alerta indicando el resultado de la ejecución.
 *
 * @throws Exception Si los parámetros de entrada son inválidos (código 300023).
 * @throws Exception Si se intenta agregar un juego ya activo a favoritos (código 300022).
 */

/* recibe parámetros JSON y asigna variables para manejar transacciones. */
$params = $json->params;
$userOperator = $json->session->usuario;
$Transaction = new Transaction();

//Recepción de parámetros
$userId = $params->user;

/* Verifica parámetros de entrada */
$gameId = $params->game_id;
$status = $params->status;

//Verificación de parámetros
$safeParameters = true;

$idsUnsafePattern = '/\D/';

/* Verifica patrones regex en un proceso de sanitización de parámetros */
if (preg_match($idsUnsafePattern, $userId)) $safeParameters = false;
if (preg_match($idsUnsafePattern, $gameId)) $safeParameters = false;
if (strlen($status) > 1 || !in_array($status, ['I', 'A'])) $safeParameters = false;
if (empty($userOperator)) $safeParameters = false;

if (!$safeParameters) throw new exception('Parametros invalidos', 300023);

//Registro/Actualización de favorito

/* permite interactuar con el objeto de usuario */
$Usuario = new Usuario($userId);
$UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
$UsuarioFavoritoMySqlDAO = new UsuarioFavoritoMySqlDAO($Transaction);

try {
    //Se solicita un registro ACTIVO
    $UsuarioFavorito = new UsuarioFavorito('', $UsuarioMandante->getUsumandanteId(), $gameId);

    //Si se desea agregar a favoritos un producto que ya existe y es un favorito activo lanza la excepción
    if ($UsuarioFavorito->getEstado() == $status || $status != 'I') throw new Exception('', 300022);

    //Inactivando favorito
    $UsuarioFavorito->setEstado($status);
    $UsuarioFavorito->setUsumodifId($userOperator);
    $UsuarioFavoritoMySqlDAO->update($UsuarioFavorito);
    $Transaction->commit();
} catch (exception $e) {
    /* Previene en lanzado de excepciones distintas a la 300022 */

    if ($e->getCode() != 300022) throw $e;

    //Verifica que no se quiera activar un producto activo en favoritos
    if (empty($UsuarioFavorito) && $status == 'A') {
        $UsuarioFavorito = new UsuarioFavorito();
        $UsuarioFavorito->setUsuarioId($UsuarioMandante->getUsumandanteId());
        $UsuarioFavorito->setProductoId($gameId);
        $UsuarioFavorito->setEstado($status);
        $UsuarioFavorito->setUsucreaId($userOperator);
        $UsuarioFavorito->setUsumodifId($userOperator);
        $UsuarioFavoritoMySqlDAO->insert($UsuarioFavorito);

        $Transaction->commit();
    }
}


/* asigna valores a un array de respuesta tras una ejecución exitosa. */
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"]["AlertMessage"] = "Ejecucion exitosa";
?>