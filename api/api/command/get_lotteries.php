<?php

namespace Backend\integrations\casino;

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
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
use Backend\dto\UsuarioMarketing;
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
use Backend\mysql\BonoInternoMySqlDAO;
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
use Backend\mysql\UsuarioMarketingMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\integrations\casino\THELOTTERSERVICES;

/**
 * Procesa la solicitud para obtener loterías.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param object $json->params Parámetros de la solicitud.
 * @param int $json->params->site_id ID del sitio.
 * @param string $json->params->filter Filtro para la búsqueda de productos.
 * @param string $json->rid ID de la solicitud.
 *
 * @return array Respuesta con el código, datos y ID de la solicitud.
 *  -code:int Código de respuesta.
 *  -data:array Datos de la respuesta.
 *  -rid:string ID de la solicitud.
 * @throws Exception Si ocurre un error al obtener los productos.
 */

/*Obtención parámetros*/
$params = $json->params;
$site_id = $json->params->site_id;

$filter = $json->params->filter;

$data = array(
    "filter"=>$filter
);

/*Solicitud listados de productos vinculados a loterías*/
$THELOTTERSERVICES = new THELOTTERSERVICES();
$respon = $THELOTTERSERVICES->getProductos($filter);

$respon = json_decode($respon);



/*Formato de respuesta*/
$response = array();
$response["code"] = 0;
$response["data"] = $respon->data;
$response["rid"] = $json->rid;




