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
 * command/video_url
 *
 * Obtención de URL de video relacionado con un evento
 *
 * Este código obtiene la URL de un video asociado a un evento específico mediante su `video_id`.
 * Realiza una consulta en la base de datos utilizando filtros para obtener el detalle del evento,
 * buscando el tipo "VIDEOURL". Posteriormente, extrae la URL del video y la devuelve en la respuesta.
 *
 * @param int $video_id : Id del video  asociado
 *
 * El objeto `$response` es un array con los siguientes atributos:
 *  - *code* (int): Código de respuesta, donde 0 indica éxito.
 *  - *rid* (string): ID de solicitud asociado a la operación.
 *  - *data* (string): Contiene la URL del video correspondiente al evento solicitado.
 *
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna un ID de video y estructura una respuesta JSON. */
$video_id = $json->params->video_id;
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
//$response["data"] = "https://vcdata-st1.inseincvirtuals.com/inggWebViewer/?cust=ingg&ch=RushFootball2a";
// $response["data"] = "rtmp://vcdata-st1.inseincvirtuals.com/inggWebViewer/?cust=ingg&ch=RushFootball2a";

$rules = [];


/* Se crean reglas de filtrado para eventos de video y se convierten a JSON. */
array_push($rules, array("field" => "int_evento_detalle.evento_id", "data" => $video_id, "op" => 'eq'));
array_push($rules, array("field" => "int_evento_detalle.tipo", "data" => "VIDEOURL", "op" => 'eq'));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);


/* Se obtienen detalles de eventos y se extrae la URL del video. */
$IntEventoDetalle = new IntEventoDetalle();
$eventos = $IntEventoDetalle->getEventoDetallesCustom("int_evento_detalle.*,int_evento.*", "int_evento_detalle.evento_id", "asc", 0, 10000, $jsonfiltro, true);
$eventos = json_decode($eventos);


$videourl = $eventos->data[0]->{"int_evento_detalle.valor"};


/* Asigna la URL del video a la clave "data" en el arreglo de respuesta. */
$response["data"] = $videourl;
