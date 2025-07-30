<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Procesa un mensaje de usuario y actualiza su estado en la base de datos.
 *
 * @param object $json Objeto JSON que contiene la sesión del usuario y los parámetros del mensaje.
 * @param int $json->session->usuario Identificador del usuario.
 * @param int $json->params->message_id Identificador del mensaje.
 *
 * @return array Arreglo de respuesta con el resultado de la operación, código y un identificador.
 *  -code:int Código de respuesta.
 *  -data:array Resultado de la operación.
 *  -rid:int Identificador de la respuesta.
 *
 *
 * @throws Exception Si el mensaje no pertenece al usuario.
 */

/* crea instancias de `UsuarioMandante` y `UsuarioMensaje` a partir de datos JSON. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);


$mensaje_id = $json->params->message_id;

try {
    $UsuarioMensaje = new UsuarioMensaje($mensaje_id);
} catch (Exception $e) {
    /* Es un bloque de captura en PHP para manejar excepciones sin realizar acciones. */


}

/* Verifica pertenencia y actualiza estado de mensaje si condiciones se cumplen. */
if ($UsuarioMensaje->usutoId != "" && $UsuarioMensaje->usutoId != "0" && $UsuarioMensaje->usutoId != $UsuarioMandante->usumandanteId) {
    throw new Exception("El mensaje no pertence a ese usuario", "01");
}
if ($UsuarioMensaje->usufromId != "" && $UsuarioMensaje->usutoId != "0") {


    $UsuarioMensaje->isRead = 1;

    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

}


/* verifica y actualiza un mensaje de usuario en la base de datos. */
if ($UsuarioMensaje->usutoId == "0") {

    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
    $UsuarioMensaje->isRead = 1;
    $UsuarioMensaje->externoId = $UsuarioMensaje->getUsumensajeId();

    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

}


/* Código que envía un mensaje WebSocket al usuario para actualizar su saldo. */
if (false) {

    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

    /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
    $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
    $WebsocketUsuario->sendWSMessage();

}


/* Se crea un arreglo de respuesta con datos, código y un identificador. */
$response = array();
$response["data"] = array("result" => 0);
$response["code"] = 0;
$response["rid"] = $json->rid;
