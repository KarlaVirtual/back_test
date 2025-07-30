<?php

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

/**
 * Obtiene las rifas vinculadas con el proveedor que las soporta
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param string $json->rid Identificador de la solicitud.
 * @param object $json->params Objeto que contiene los parámetros de la solicitud.
 * @param string $json->params->site_id Identificador del sitio.
 * @param string $json->params->filter Filtro proporcionado en los parámetros JSON.
 * @param string $json->params->roomName Nombre de la sala.
 * @param string $json->params->country País.
 *
 * @return array $response Arreglo que contiene el código de respuesta, identificador de la solicitud y datos adicionales.
 * @return int $response["code"] Código de respuesta inicializado en 0.
 * @return string $response["rid"] Identificador de la solicitud extraído de $json.
 * @return array $response["data"] Arreglo que contiene el resultado.
 *
 * @throws Exception Si ocurre un error durante el procesamiento de la solicitud.
 */

/**
 * Se inicializa un arreglo de respuesta con un código y un identificador.
 *
 * @var array $response Contiene el código de respuesta, identificador de la solicitud y datos adicionales.
 * @var int $response["code"] Código de respuesta inicializado en 0.
 * @var string $response["rid"] Identificador de la solicitud extraído de $json.
 * @var array $response["data"] Arreglo que contiene el resultado.
 */

$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""

);


//$UsuarioMandante = new UsuarioMandante($json->session->usuario);
//$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

/**
 * Se obtienen los parámetros necesarios desde el objeto JSON.
 *
 * @var string $site_id Identificador del sitio extraído de los parámetros JSON.
 * @var string $filter Filtro proporcionado en los parámetros JSON.
 * @var string $roomName Nombre de la sala extraído de los parámetros JSON.
 * @var string $country País extraído de los parámetros JSON.
 * @var array $data Arreglo que contiene el nombre de la sala y el filtro.
 */

$site_id = $json->params->site_id;

$filter = $json->params->filter;
$roomName = $json->params->roomName;
$country= $json->params->country;
$data = array(
    "roomName"=>$roomName,
    "filter"=>$filter);

/**
 * Se determina el país basado en el código proporcionado.
 *
 * @var int $paisId Identificador del país que se asignará en función del código de país.
 */
/*switch ($country){

    case "pe":
        $paisId = 173;
        break;
    case "ec":
        $paisId = 66;
        break;
    case "cr":
        $paisId = 60;
        break;
    case "cl":
        $paisId = 46;
        break;
}*/

/**
 * Se crea una instancia de la clase Pais con el código vacío y el país especificado.
 *
 * @var Pais $Pais Instancia de la clase Pais.
 */
$Pais = new Pais('',$country);
$Proveedor = new Proveedor("", "IESGAMES");

$Producto = new Producto("","IESGAMES",$Proveedor->getProveedorId());

/**
 * Se crea una instancia de la clase IESGAMESSERVICES del espacio de nombres Backend\integrations\casino.
 *
 * @var \Backend\integrations\casino\IESGAMESSERVICES $IESGAMESSERVICES Instancia de la clase IESGAMESSERVICES.
 */
$IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
$respon = $IESGAMESSERVICES->GetRaffles($data,$site_id,$Pais->paisId);


$respon = json_decode($respon);

/**
 * Se inicializa un arreglo de respuesta con código 0 y los datos decodificados.
 *
 * @var array $response Arreglo que contiene el código y los datos de la respuesta.
 */
$response = array();
$response["code"] = 0;
$response["data"] = $respon;


$response["rid"] = $json->rid;




