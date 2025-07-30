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
/** Recurso obtiene el historial de apuestas de casino con base en los filtros proporcionados
 *
 * @param string $json->params->where->from_date
 * @param string $json->params->where->to_date
 * @param string $json->params->where->bet_id
 * @param string $json->params->count
 * @param string $json->params->start
 *
 * @return array
 *   -code : int El estado de la respuesta
 *   -data.bets : array Lista de apuestas
 *   -data.total_count : int Total de apuestas
 *   -total_count : int Total de apuestas
 */
/*Este código PHP extrae parámetros de un objeto JSON, crea un objeto UsuarioMandante y convierte una fecha de inicio a un formato específico si está presente. */
$MaxRows = $json->params->count;
$SkeepRows = $json->params->start;
$grouping = "";

$UsuarioMandante = new UsuarioMandante($json->session->usuario);

if ($json->params->where->from_date != "") {
    $FromDateLocal = date('Y-m-d 00:00:00', $json->params->where->from_date);

}
if ($json->params->where->to_date != "") {
    $ToDateLocal = date('Y-m-d 23:59:59', $json->params->where->to_date);

}

/*El código selecciona apuestas de un historial de casino basándose en varios filtros y parámetros proporcionados en un objeto JSON.*/
if($json->params->where->bet_id != ""){
    $betId = $json->params->where->bet_id;
}

$rules = [];

if ($FromDateLocal != "") {
    array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

}

/*El código agrega reglas de filtrado a un array basado en la fecha de finalización, el ID de la apuesta y el ID del usuario.*/
if ($ToDateLocal != "") {
    array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
}
if ($betId != "") {
    array_push($rules, array("field" => "transaccion_juego.ticket_id", "data" => "$betId", "op" => "eq"));
}


array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

/*El código selecciona transacciones de juego de una base de datos aplicando filtros y parámetros proporcionados en un objeto JSON, y luego decodifica los datos obtenidos.*/
$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$select = "transaccion_juego.*,producto.descripcion, categoria_mandante.descripcion";

$transaccionJuego = new TransaccionJuego();


$data = $transaccionJuego->getTransaccionesCustom($select,"transaccion_juego.fecha_crea",'desc',$SkeepRows,$MaxRows,$jsonfiltro,true,'');
$apuestas = json_decode($data);
/*El código extrae y procesa datos de apuestas de un historial de casino basado en filtros proporcionados en un objeto JSON.*/
$apuestasData= array();


foreach ($apuestas->data as $key => $value) {
    /*El código determina el estado de una transacción de juego según las condiciones dadas de los valores estado y premiado.*/
    $array = array();
    $state = ($value->{"transaccion_juego.estado"});
    $premiado = ($value->{"transaccion_juego.premiado"});

    // Determina el estado según las condiciones dadas
    if( $state == 'A' && $premiado ='N'){
        $state = "0";
    }
    if( $state == 'I'&& $premiado == 'N'){
        $state = "1";
    }
    if( $state == 'I'&& $premiado == 'S'){
        $state = "3";
    }

    // Asigna valores al array de la apuesta
    /*El código extrae y procesa datos de apuestas de un historial de casino, asignando valores a un array y agregándolos a un array de apuestas procesadas.*/
    $array["Id"]= ($value->{"transaccion_juego.ticket_id"});
    $array["game"]= ($value->{"producto.descripcion"});
    $array["date"]= ($value->{"transaccion_juego.fecha_crea"});
    $array["bet"]= ($value->{"transaccion_juego.valor_ticket"});
    $array["win"]= ($value->{"transaccion_juego.valor_premio"});
    $array["state"]= $state;
    $array['current_rtp'] = $value->{'transaccion_juego.valor_ticket'} ? round(($value->{'transaccion_juego.valor_premio'} / $value->{'transaccion_juego.valor_ticket'}) * 100) : 0;
    $array['category'] = $value->{'categoria_mandante.descripcion'} ?: '';

    // Agrega la apuesta procesada al array de apuestas
    array_push($apuestasData, $array);
}

// Prepara la respuesta con el código y los datos de las apuestas
$response = array();
$response["code"] = 0;
$response["data"] = array(
        "bets"=>$apuestasData,
        "total_count"=> $apuestas->count[0]->{".count"}
);

$response["total_count"] = $apuestas->count[0]->{".count"};


