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
 * Obtiene los tipos de juegos disponibles.
 *
 * @param object $json Objeto JSON que contiene la solicitud.
 * @return array Respuesta con el código de estado, ID de la solicitud y datos de los productos.
 *  -code:int Código de estado de la respuesta.
 *  -rid:int ID de la solicitud de la que proviene la respuesta.
 *  -data:array Datos de los productos.
 * @throws Exception Si ocurre un error al procesar la solicitud.
 */

$ProductosData = array();

$arrayproducto = array();
$arrayproducto["Id"] = 0; // Identificador del producto
$arrayproducto["Name"] = "Deportes"; // Nombre del producto
/*$arrayproducto["children"] = array(
    array(
        "Id" => 4,
        "Name" => "Prematch"
    ),
    array(
        "Id" => 5,
        "Name" => "Live"
    )
);*/

array_push($ProductosData, $arrayproducto); // Agrega el producto actual al arreglo de productos

$arrayproducto = array();
$arrayproducto["Id"] = 1; // Identificador del próximo producto
$arrayproducto["Name"] = "Virtuales"; // Nombre del producto
$arrayproducto["children"] = array(); // Inicializa el arreglo de hijos como vacío

array_push($ProductosData, $arrayproducto); // Agrega el producto actual al arreglo de productos

$arrayproducto = array();
$arrayproducto["Id"] = 2; // Identificador del próximo producto
$arrayproducto["Name"] = "Casino en vivo"; // Nombre del producto
$arrayproducto["children"] = array(); // Inicializa el arreglo de hijos como vacío

array_push($ProductosData, $arrayproducto); // Agrega el producto actual al arreglo de productos

$arrayproducto = array();
$arrayproducto["Id"] = 3; // Identificador del próximo producto
$arrayproducto["Name"] = "Casino"; // Nombre del producto
$arrayproducto["children"] = array(); // Inicializa el arreglo de hijos como vacío

array_push($ProductosData, $arrayproducto); // Agrega el producto actual al arreglo de productos

$arrayproducto = array();
$arrayproducto["Id"] = 6; // Identificador del próximo producto
$arrayproducto["Name"] = "Todas las verticales"; // Nombre del producto
$arrayproducto["children"] = array(); // Inicializa el arreglo de hijos como vacío
// Agrega un elemento al final del arreglo $ProductosData
array_push($ProductosData, $arrayproducto);


$response = array();
$response["code"] = 0; // Código de respuesta, 0 indica éxito
$response["rid"] = $json->rid; // ID de la solicitud de la que proviene la respuesta
$response["data"] = $ProductosData; // Datos del producto agregados

