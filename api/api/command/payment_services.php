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
 *Obtiene la coleccion de servicios de pago disponibles para un usuario
 *@param int $json->session->usuario->usuario_id ID del usuario
 *
 * @return array
 *  -code:int Codigo de respuesta
 *  -rid:string ID de respuesta
 *  -data:array
 *      -withdraw:array Arreglo de servicios de retiro
 *      -deposit:array Arreglo de servicios de deposito
 */

/* Se crean instancias de usuario y se definen variables para paginación y orden. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
//$Pais = new Pais($Usuario->paisId);

$MaxRows = 1000;
$OrderedItem = 1;

/* Define reglas para filtrar productos activos en una estructura de datos. */
$SkeepRows = 0;


$rules = [];
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$Usuario->mandante", "op" => "eq"));
array_push($rules, array("field" => "proveedor.tipo", "data" => "PAYMENT", "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Se codifica un filtro JSON y se obtienen productos según criterios específicos del país. */
$json2 = json_encode($filtro);

$ProductoMandante = new ProductoMandante();

$ProductoMandantes = $ProductoMandante->getProductosMandantePaisCustom(" producto.producto_id , producto.descripcion ,CASE producto_mandante.filtro_pais WHEN 'A' THEN prodmandante_pais.min ELSE producto_mandante.min END min,CASE producto_mandante.filtro_pais WHEN 'A' THEN prodmandante_pais.max ELSE producto_mandante.max END max,CASE producto_mandante.filtro_pais WHEN 'A' THEN prodmandante_pais.tiempo_procesamiento ELSE producto_mandante.tiempo_procesamiento END tiempo, producto.image_url imagen,producto.orden ", "producto.orden", "DESC,producto.descripcion", $SkeepRows, $MaxRows, $json2, true, $Usuario->paisId);


$ProductoMandantes = json_decode($ProductoMandantes);


/* almacena IDs de productos en un arreglo para depósitos. */
$ProductoMandantesData = array();

$withdrawPaymets = array("local");
$depositPaymets = array();

foreach ($ProductoMandantes->data as $key => $value) {

    array_push($depositPaymets, $value->{"producto.producto_id"});

}


/* Crea una respuesta estructurada en formato JSON con información sobre pagos y un ID. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array();
$response["data"]["withdraw"] = $withdrawPaymets;
$response["data"] ["deposit"] = $depositPaymets;


