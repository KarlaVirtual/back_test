<?php

/**
 * Este archivo contiene un script para interactuar con usuarios y saldos en un sistema de casino.
 * Realiza operaciones como la consulta de saldo, generación de tokens de autenticación y envío de mensajes WebSocket.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $UsuarioMandante  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Usuario          Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Balance          Variable que almacena el balance o saldo disponible en una cuenta o sistema.
 * @var mixed $UsuarioToken     Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $data             Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $WebsocketUsuario Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 */

namespace Backend\integrations\casino;

error_reporting(E_ALL);
ini_set('display_errors', 'ON');
require_once __DIR__ . '../../../../vendor/autoload.php';

use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\websocket\WebsocketUsuario;

/**
 * Consultamos de nuevo el usuario para obtener el saldo.
 * Se crea una instancia de `UsuarioMandante` y se utiliza para obtener el saldo del usuario.
 */
$UsuarioMandante = new UsuarioMandante(1);
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);
$Balance = $Usuario->getBalance();
print_r("/NNNNNN2 ");

/**
 * Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket.
 * Se genera un token de autenticación para el usuario mandante.
 */
$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
print_r("/NNNNNN ");

/**
 * Enviamos el mensaje WebSocket al Usuario para que actualice el saldo.
 * Se genera un mensaje WebSocket con el RequestId y se envía al usuario.
 */
$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
$WebsocketUsuario->sendWSMessage();

/**
 * Imprimimos información de depuración sobre las instancias creadas y los datos generados.
 */
print_r($UsuarioMandante);
print_r($UsuarioMandante->getUsumandanteId());
print_r($Usuario);
print_r($UsuarioToken);
print_r($data);


