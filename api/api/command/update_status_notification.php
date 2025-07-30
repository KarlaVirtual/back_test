<?php

/**
 * Recurso para actualizar el estado de las notificaciones que fueron recibidas por el front mediante Websocket
 *
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 */

use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioRuleta;
use Backend\dto\WebsocketNotificacion;
use Backend\mysql\UsuarioRuletaMySqlDAO;
use Backend\mysql\WebsocketNotificacionMySqlDAO;
use Backend\sql\Transaction;

try {

  $data = $json->params;

  $usuarioMandante = new UsuarioMandante($json->session->usuario);
  $usuario = new Usuario($usuarioMandante->getUsuarioMandante());

  $websocketNotification = new WebsocketNotificacion($data->id_notification);

  $transaction = new Transaction();
  $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO($transaction);

  $websocketNotification->setEstado('R');
  $websocketNotificacionMySqlDAO->update($websocketNotification);

  switch ($websocketNotification->getTipo()) {
    case 'ruleta':
      updateUsuarioRuleta($websocketNotification->getValor(), $usuarioMandante->getUsumandanteId(), $transaction);
      break;

    default:
      break;
  }

  $transaction->commit();

  $response["HasError"] = false;
  $response["AlertType"] = "success";
  $response["AlertMessage"] = "";
  $response["ModelErrors"] = [];
} catch (\Throwable $th) {
  $response["HasError"] = true;
  $response["AlertType"] = "danger";
  $response["AlertMessage"] = $th->getMessage();
}

function updateUsuarioRuleta(int $usuruletaId, int $usumandanteId, $transaccion)
{
  $UsuarioRuleta = new UsuarioRuleta($usuruletaId, $usumandanteId);
  $UsuarioRuleta->setEstado("P");
  $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($transaccion);
  $UsuarioRuletaMySqlDAO->update($UsuarioRuleta, " AND estado = 'A' ");
}
