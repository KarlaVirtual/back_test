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
 * Este script filtra y estructura mensajes de un usuario específico en una plataforma.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud, incluyendo la sesión del usuario.
 * @param object $json->session Objeto JSON que contiene la información de la sesión del usuario.
 * @param int $json->session->usuario ID del usuario en la sesión.
 * 
 *
 * @return array $response Respuesta en formato JSON que incluye:
 *                         - code: Código de estado de la respuesta (0 para éxito).
 *                         - rid: Identificador único de la respuesta.
 *                         - data: Datos estructurados de los mensajes recibidos, incluyendo:
 *                           - subid: Identificador único generado a partir del ID de sesión.
 *                           - messages: Lista de mensajes con detalles como cuerpo, fecha, asunto, etc.
 *
 * @throws Exception Si ocurre un error al procesar los mensajes.
 */
exit();
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$mensajesRecibidos = [];


$json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "MENSAJE","op":"eq"}] ,"groupOp" : "AND"}';


/* Se procesa una lista de mensajes de usuario y se estructura en un nuevo arreglo. */
$UsuarioMensaje = new UsuarioMensaje();
$usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 10, $json2, true);

$usuarios = json_decode($usuarios);


foreach ($usuarios->data as $key => $value) {

    $array = [];

    $array["body"] = $value->{"usuario_mensaje.body"};
    $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
    $array["date"] = 1514649066;
    $array["id"] = 123213213;
    $array["subject"] = $value->{"usuario_mensaje.msubject"};
    $array["thread_id"] = null;

    array_push($mensajesRecibidos, $array);

}


/* crea un array de respuesta con mensajes recibidos y un ID de sesión. */
$response = array();


$response["data"] = array(
    "subid" => "7040" . $json->session->sid . "3",
    "data" => array("messages" => $mensajesRecibidos)
);


/* Asigna un código y un identificador a una respuesta JSON procesada. */
$response["code"] = 0;
$response["rid"] = $json->rid;
