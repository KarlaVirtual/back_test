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
use Backend\dto\UsuarioHistorial;
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
 * Inicializa las variables y establece reglas para la consulta de usuarios.
 *
 * @var int $MaxRows Número máximo de filas a recuperar.
 * @var int $SkeepRows Número de filas a omitir.
 * @var string $grouping Variable para agrupar resultados.
 * @var UsuarioMandante $UsuarioMandante Objeto que representa al usuario mandante.
 * @var string|null $FromDateLocal Fecha de inicio en formato local, o null si no se proporciona.
 * @var string|null $ToDateLocal Fecha de fin en formato local, o null si no se proporciona.
 * @var string $state Estado del usuario, modificado según las condiciones.
 * @var array $rules Reglas aplicadas para los filtros de la consulta.
 *
 * @return array $response Respuesta a enviar como resultado.
 *  - code:int Código de respuesta.
 *  - data:array Información adicional.
 *      - result_status:string Estado del resultado.
 *      - rid:string ID de la solicitud.
 *      - deposits_requests:array Información de las solicitudes de depósito.
 *      - total_count:int Cantidad total de solicitudes.
 */

$MaxRows = $json->params->count;
$SkeepRows = $json->params->start;
$grouping = "";

$UsuarioMandante = new UsuarioMandante($json->session->usuario);
/*Generación filtros para consulta personalizada en UsuarioRecarga*/
if ($json->params->from_date != "") {
    $FromDateLocal = date('Y-m-d 00:00:00', $json->params->from_date);

}

if ($json->params->to_date != "") {
    $ToDateLocal = date('Y-m-d 23:59:59', $json->params->to_date);

}

if($json->params->state != ""){
    $state = $json->params->where->state;
}
if($state == 0){

    $state = 'A';
}

if($state == 4){

    $state = 'I';
}
$rules = [];

if ($FromDateLocal != "") {
    //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
    array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
   // array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));
}
if ($ToDateLocal != "") {
    //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
    array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
    //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));
}


if($state !=""){
    array_push($rules, array("field" => "usuario_recarga.estado", "data" => "$state", "op" => "eq"));
}
array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$select = "usuario_recarga.*,usuario.nombre, usuario.moneda,producto.descripcion";

$UsuarioRecarga = new UsuarioRecarga();

// Se obtienen los datos de recargas de usuarios de forma personalizada.
$data = $UsuarioRecarga->getUsuarioRecargasCustom($select,"usuario_recarga.fecha_crea",'desc',$SkeepRows,$MaxRows,$jsonfiltro,true,'');
$depositos = json_decode($data);
$depositosData= array();
// Se itera sobre los datos decodificados para construir un nuevo array con la información relevante.
foreach ($depositos->data as $key => $value) {
    $array = array();
    $array["Id"]= ($value->{"usuario_recarga.recarga_id"});
    $array["date"]= ($value->{"usuario_recarga.fecha_crea"});
    $array["paymentMethod"]= ($value->{"producto.descripcion"});
    $array["amount"]= ($value->{"usuario_recarga.valor"});
    $array["bonus"]= 0;
    $array["total"]= ($value->{"usuario_recarga.valor"});
    $array["currency"]= ($value->{"usuario.moneda"});
    $array["status"]= ($value->{"usuario_recarga.estado"});

    // Se agrega el array creado a la colección de depositos.
    array_push($depositosData, $array);

}

// Se prepara la respuesta a enviar como resultado.
$response = array();
$response["code"] = 0;
$response["data"] = array(
    "result_status" => "OK",
    "rid"=>$json->rid,
    "deposits_requests"=> array(
        "request"=>$depositosData,
        "total_count"=> $depositos->count[0]->{".count"}

    )

);
$response["total_count"] = $depositos->count[0]->{".count"};



