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
 * Procesa un mensaje y lo inserta en la base de datos, luego envía una notificación a través de WebSocket.
 * @param string $json->params->subject Asunto del mensaje
 * @param string $json->params->body Contenido del mensaje
 *
 * @return array $response
 *  - int data->result Resultado de la operación
 *  - int code Código de éxito
 *  - int rid ID de la solicitud original
 */

// Se obtienen el asunto y el cuerpo del mensaje desde el JSON recibido.
$subject = $json->params->subject;
$body = $json->params->body;

// Se crea una instancia de UsuarioMandante utilizando el usuario de la sesión.
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

// Se prepara el mensaje para el usuario.
$UsuarioMensaje = new UsuarioMensaje();
$UsuarioMensaje->usufromId = $UsuarioMandante->usumandanteId; // ID del usuario que envía
$UsuarioMensaje->usutoId = 0; // ID del usuario que recibe (0 indica que está vacío)
$UsuarioMensaje->isRead = 0; // Indica que el mensaje no ha sido leído
$UsuarioMensaje->body = $body; // Cuerpo del mensaje
$UsuarioMensaje->tipo = 'MENSAJE'; // Tipo de mensaje
$UsuarioMensaje->msubject = $subject; // Asunto del mensaje

// Configura el parentId si está vacío.
if ($UsuarioMensaje->parentId == "") {
    $UsuarioMensaje->parentId = 0; // Indica que no tiene padre
}

// Configura el proveedorId si está vacío.
if ($UsuarioMensaje->proveedorId == "") {
    $UsuarioMensaje->proveedorId = 0; // Indica que no tiene proveedor
}

if ($UsuarioMensaje->proveedorId == "") {
    $UsuarioMensaje->proveedorId = 0;
}

// Se crea una instancia del DAO para manejar el mensaje en la base de datos.
$UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
$UsuarioMensajeMySqlDAO->insert($UsuarioMensaje); // Inserta el mensaje en la base de datos
$UsuarioMensajeMySqlDAO->getTransaction()->commit(); // Confirma la transacción

// Se obtienen datos del usuario para enviar la notificación.
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);

/*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

// Se envía el mensaje Websocket al Usuario para que actualice el saldo.
$data = $Usuario->getWSMessage($UsuarioToken->getRequestId()); // Obtiene el mensaje para WebSocket
$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data); // Crea la instancia para WebSocket
$WebsocketUsuario->sendWSMessage(); // Envía el mensaje a través de WebSocket

// Se prepara la respuesta que se enviará de vuelta.
$response = array();
$response["data"] = array("result" => 0); // Resultado de la operación
$response["code"] = 0; // Código de éxito
$response["rid"] = $json->rid; // ID de la solicitud original
