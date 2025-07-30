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
use Backend\dto\SorteoDetalle2;
use Backend\dto\SorteoInterno2;
use Backend\dto\TicketEnc;
use Backend\dto\UsuarioSorteo;
use Backend\dto\UsuarioSorteo2;
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
use Backend\mysql\SorteoInterno2MySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioSorteo2MySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Obtiene la colección de tickets de un sorteo respecto al filtrdo que lo requiere
 *
 * @param object $json Objeto JSON que contiene los parámetros necesarios.
 * @param object $json->params Parámetros del JSON.
 * @param object $json->params->data Datos del JSON.
 * @param string $json->params->id ID del registro.
 * @param int $json->params->start Filas a omitir.
 * @param int $json->params->count Número máximo de filas a retornar.
 * @param string $json->params->lotteryId ID de la lotería.
 * @param string $json->params->site_id ID del sitio.
 * @param bool $json->params->isMobile Indica si es móvil.
 * @param string $json->rid ID de la solicitud.
 *
 * @return array $response Arreglo de respuesta con los datos procesados.
 *
 * @throws Exception Si ocurre un error durante el procesamiento.
 */

// Se obtiene el objeto JSON y se extraen los parámetros necesarios
$data = $json->params->data;

// Se extraen varios parámetros del objeto JSON
$id = $json->params->id; // ID del registro
$skeepRows = $json->params->start; // Filas a omitir
$MaxRows = $json->params->count; // Número máximo de filas a retornar
$lotteryId = $json->params->lotteryId; // ID de la lotería
$site_id = $json->params->site_id; // ID del sitio
$isMobile = $json->params->isMobile; // Indica si es móvil

// Inicializa un array para las reglas de filtrado
$rules = [];

// Verifica si el ID no está vacío y agrega una regla para filtrar por ID
if($id != ""){
    array_push($rules,array("field"=>"usuario_sorteo2.registro2_id","data"=>$id,"op"=>"eq"));
}

// Verifica si el lotteryId no está vacío y agrega una regla para filtrar por lotteryId
if($lotteryId != ""){
    array_push($rules,array("field"=>"usuario_sorteo2.sorteo2_id","data"=>$lotteryId,"op"=>"eq"));
}

// Crea un array de filtros con las reglas y la operación de grupo
$filters = array("rules" => $rules, "groupOp" => "AND");

$filters = json_encode($filters);

// Instancia la clase UsuarioSorteo2
$usuarioSorteo2 = new UsuarioSorteo2();
$datos = $usuarioSorteo2->getusuarioSorteoCustom("usuario_sorteo2.*","usuario_sorteo2.ususorteo2_id","desc",$skeepRows,$MaxRows,$filters,true);

// Decodifica el JSON recibido
$datos = json_decode($datos);

// Inicializa un array para almacenar los datos finales
$final = [];

// Itera sobre los datos decodificados para construir el array final
foreach ($datos->data as $key => $value) {


    $data["ticket"] = $value->{"usuario_sorteo2.codigo"};
 
    array_push($final,$data);
}


// Inicialización del arreglo de respuesta
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $final;

$response["total_count"] = $datos->count[0]->{".count"};;


?>