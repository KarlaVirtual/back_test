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
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TranssportsbookDetalle;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBono;
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
 * @param object $json Objeto JSON que contiene la sesión del usuario y los parámetros de la solicitud.
 * @param object $json->session Información de la sesión del usuario.
 * @param object $json->params Parámetros de la solicitud.
 * @param string $json->params->where->from_date Fecha de inicio del rango de búsqueda.
 * @param string $json->params->where->to_date Fecha de fin del rango de búsqueda.
 * @param int $json->params->where->bet_id ID de la apuesta.
 * @param string $json->params->state Estado de la apuesta.
 * @param int $json->params->count Número máximo de filas a recuperar.
 * @param int $json->params->start Número de filas a omitir.
 * @param int $json->rid ID de la solicitud.
 *
 * @return array $response Respuesta que contiene el código de estado, ID de la solicitud, datos de las apuestas y el conteo total.
 *  -code:int Código de estado de la respuesta.
 *  -rid:int ID de la solicitud.
 *  -data:array Datos de las apuestas.
 *  -total_count:int Conteo total de las apuestas.
 */

// Establece la zona horaria predeterminada a 'America/Bogota'
date_default_timezone_set('America/Bogota');

// Crea una nueva instancia de UsuarioMandante a partir del usuario en la sesión JSON
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

// Obtiene las fechas y otros parámetros desde el objeto JSON
$from_date = $json->params->where->from_date;
$to_date = $json->params->where->to_date;
$bet_id = $json->params->where->bet_id;
$state = $json->params->state;

// Crea una nueva instancia de ItTicketEnc
$ItTicketEnc = new ItTicketEnc();

// Convierte la fecha 'to_date' a formato 'Y-m-d H:i:s' si no está vacía
if($to_date != ""){
    $ToDateLocal = date("Y-m-d H:i:s", $to_date);

}

// Convierte la fecha 'from_date' a formato 'Y-m-d H:i:s' si no está vacía
if($to_date != ""){
    $FromDateLocal = date("Y-m-d H:i:s", $from_date);
}

// Recupera parámetros de paginación desde el objeto JSON
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

// Asigna el conteo y el inicio para la paginación desde el objeto JSON
$MaxRows = $json->params->count;
$SkeepRows = $json->params->start;


if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}
$MaxRows = 100;

$rules = [];

// Crea una nueva instancia de ConfigurationEnvironment
$ConfigurationEnvironment = new ConfigurationEnvironment();

if(true){
    /**
     * Se genera un conjunto de reglas de filtrado para obtener bonos de usuarios
     * en base a criterios como fechas, ID de ticket, estado, y usuario mandante.
     */

    if ($FromDateLocal != "") {
        array_push($rules, array("field" => "CONCAT(usuario_bono.fecha_crea,' ',usuario_bono.hora_crea)", "data" => "$FromDateLocal ", "op" => "ge"));
    }
    if ($ToDateLocal != "") {
        array_push($rules, array("field" => "CONCAT(usuario_bono.fecha_crea,' ',usuario_bono.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));
    }


    if ($bet_id != "") {
        array_push($rules, array("field" => "usuario_bono.ticket_id", "data" => $bet_id, "op" => "eq"));
    }


    if ($state != "") {
        array_push($rules, array("field" => "usuario_bono.estado", "data" => $state, "op" => "eq"));
    }

    array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
   // array_push($rules, array("field" => "usuario_bono.eliminado", "data" => "N", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $UsuarioBono = new UsuarioBono();

    // Se obtienen los bonos de usuarios personalizados según los filtros y parámetros especificados.
    $bonos = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*,
                                        bono_interno.tipo,
                                        CASE bono_detalle2.tipo
                                            WHEN 'EXPDIA' THEN DATE_FORMAT(
                                                    (DATE_ADD(DATE_FORMAT(usuario_bono.fecha_crea, '%Y-%m-%d %H:%i:%s'),
                                                              INTERVAL bono_detalle2.valor DAY)), '%Y-%m-%d %H:%i:%s')
                                            ELSE DATE_FORMAT(usuario_bono.fecha_crea, '%Y-%m-%d %H:%i:%s') END AS fecha_expiracion ", "usuario_bono.usubono_id", "desc", $SkeepRows, $MaxRows, $json2, true, '', 'MINAMOUNT', "'EXPDIA','EXPFECHA'");
    $bonos = json_decode($bonos);

    $total = 0;
    $bets = [];
    $response = array();
/* Procesa la lista de bonos y genera un array de apuestas con información relevante.*/
    foreach ($bonos->data as $key => $value) {

        $outcome = 0;

        $arraybet = [];


        $arraybet = array();
        $arraybet["bonus_id"] = ($value->{"usuario_bono.usubono_id"});
        $arraybet["state"] = $value->{"usuario_bono.estado"};
        $arraybet["creation_date"] = $value->{"usuario_bono.fecha_crea"};
        $arraybet["expiration_date"] = $value->{".fecha_expiracion"};
        $arraybet["bonus_value"] = $value->{"usuario_bono.valor"};
        $arraybet["rollower"] = $value->{"usuario_bono.rollower_requerido"};
        $arraybet["bets"] = $value->{"usuario_bono.apostado"};
        $arraybet["missing_rollower"] = floatval($arraybet["rollower"])-floatval($arraybet["bets"]);

        $arraybet["bonus_type"] = ($value->{"bono_interno.tipo"});

        $bonusExpirationDate = null;
        $bonusExpirationDate = $value->{"usuario_bono.fecha_expiracion"};
        if (empty($bonusExpirationDate)) {
            $bonusExpirationDate = $value->{".fecha_expiracion"};
        }

        /*Actualización de estado en bonos expirados*/
        if($arraybet["state"]  == 'A' && date('Y-m-d H:i:s') > date('Y-m-d H:i:s' ,strtotime($bonusExpirationDate))){
            $arraybet["state"] = "E";
        }

        if($arraybet["missing_rollower"] <0){
            $arraybet["missing_rollower"]=0;
        }

        array_push($bets, $arraybet); // Agrega la apuesta procesada al array de apuestas.

    }

}

$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $bets;
$response["total_count"] = $bonos->count[0]->{".count"};
