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
use Backend\dto\LealtadHistorial;
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
 * Obtiene el historial de puntos de lealtad de un usuario.
 *
 * @param object $json Objeto JSON que contiene los parámetros de entrada, incluyendo:
 *  - session: Objeto que contiene la sesión del usuario.
 *      - from_date: Fecha de inicio.
 *      - to_date: Fecha de fin.
 *    - site_id: ID del sitio.
 *    - MaxRows: Número máximo de filas.
 *    - OrderedItem: Elemento ordenado.
 *    - SkeepRows: Número de filas a omitir.
 *    - count: Número de filas a contar.
 *    - start: Inicio de las filas.
 *
 * @return void Modifica el array $response con el código de respuesta y los datos.
 *  - code: int Código de respuesta.
 *  - data: array Datos de respuesta.
 *    - pointsHistory: array Historial de puntos del usuario.
 *  - rid: string ID de la respuesta.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */

/* Se crea un objeto UsuarioMandante y se extraen parámetros de un JSON. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$from_date = $json->params->where->from_date;
$to_date = $json->params->where->to_date;
$site_id = $json->params->site_id;

/* Convierte site_id a minúsculas y crea un objeto Mandante con él. */
$site_id = strtolower($site_id);

$Mandante = new Mandante($site_id);

if ($to_date != "") {
    $ToDateLocal = $to_date;

}


/* Condicional que asigna una fecha local si $to_date no está vacío. */
if ($to_date != "") {
    $FromDateLocal = $from_date;

}

$MaxRows = $params->MaxRows;

/* asigna valores de parámetro y maneja filas para paginación. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $json->params->count;
$SkeepRows = $json->params->start;


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Inicializa variables si están vacías: $OrderedItem a 1 y $MaxRows a 10. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}


/* Inicializa un array vacío y configura el entorno de configuración en una variable. */
$rules = [];

$ConfigurationEnvironment = new ConfigurationEnvironment();
$arrayfinal4 = array();

if (true) {


    /* Código PHP que utiliza condicionales para agregar reglas a un array según fechas. */
    $arraygeneral = array();

    if ($FromDateLocal != "") {
        array_push($rules, array("field" => "lealtad_historial.fecha_crea", "data" => date("Y-m-d 00:00:00", $FromDateLocal), "op" => "lt"));
    }

    array_push($rules, array("field" => "lealtad_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

    /* Agrega una regla de filtro y lo codifica en formato JSON. */
    array_push($rules, array("field" => "lealtad_historial.movimiento", "data" => 'E', "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $LealtadHistorial = new LealtadHistorial();


    /* Obtiene y decodifica un historial de lealtad en formato JSON. */
    $lealtadhistorico = $LealtadHistorial->getLealtadHistorialCustom("lealtad_historial.creditos", 'lealtad_historial.usuario_id', "desc", 0, 2, $json2, true, 'lealtad_historial.movimiento');
    $lealtadhistorico = json_decode($lealtadhistorico);
    $arrayfinal = array();
    $arrayfinal2 = array();

    $array = array();


    /* Asigna puntos iniciales basados en lealtadhistorico, o cero si está vacío. */
    if ($lealtadhistorico != "") {

        $array['initialPoints'] = intval($lealtadhistorico->data[1]->{"lealtad_historial.creditos"});

    } else {
        $array['initialPoints'] = 0;
    }


    /* Se añaden elementos y puntos de lealtad a dos arrays finales. */
    array_push($arrayfinal, $array);


    $array['finalPoints'] = intval($Usuario->getPuntosLealtad());


    array_push($arrayfinal2, $array);


    /* Inicializa parámetros y objeto para clasificar datos basados en fecha de expiración de lealtad. */
    $SkeepRows = 0;
    $OrderedItem = 1;
    $MaxRows = 10;
    $rules = [];

    $Clasificador = new Clasificador("", "LOYALTYEXPIRATIONDATE");

    /* Se configura una serie de reglas basadas en datos de usuarios y expiraciones. */
    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
    $diasExpiracion = $MandanteDetalle->valor;

    array_push($rules, array("field" => "lealtad_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "lealtad_historial.fecha_exp", "data" => date("Y-m-d H:i:s", strtotime("-" . $diasExpiracion . " days")), "op" => "le"));

    array_push($rules, array("field" => "lealtad_historial.movimiento", "data" => 'E', "op" => "eq"));


    /* Se crea un filtro JSON y se obtienen datos de un historial utilizando condiciones. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $lealtadhistorico = $LealtadHistorial->getLealtadHistorialCustom("SUM(lealtad_historial.valor) as expirePoints ", 'lealtad_historial.usuario_id', "desc", $SkeepRows, $MaxRows, $json2, true, 'lealtad_historial.usuario_id');
    $lealtadhistorico = json_decode($lealtadhistorico);
    $arrayfinal3 = array();

    /* Asigna puntos expirados a un array, dependiendo de la existencia de lealtadhistorico. */
    if ($lealtadhistorico != "") {

        $array['expirePoints'] = intval($lealtadhistorico->data[0]->{".expirePoints"});

    } else {
        $array['expirePoints'] = 0;
    }


    /* Se añaden datos a un array y se definen variables para control de filas. */
    array_push($arrayfinal3, $array);

    $SkeepRows = 0;
    $OrderedItem = 1;
    $MaxRows = 10;
    $rules = [];

    /* establece reglas de filtros de fechas en lealtad_historial. */
    if ($FromDateLocal != "") {
        array_push($rules, array("field" => "lealtad_historial.fecha_crea", "data" => date("Y-m-d 00:00:00", $FromDateLocal), "op" => "ge"));
    }
    if ($ToDateLocal != "") {
        array_push($rules, array("field" => "lealtad_historial.fecha_crea", "data" => date("Y-m-d 23:59:59", $ToDateLocal), "op" => "le"));
    }


    /* Se agregan reglas de filtrado y se convierten a formato JSON. */
    array_push($rules, array("field" => "lealtad_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "lealtad_historial.movimiento", "data" => "S", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);


    /* obtiene y decodifica el historial de puntos de lealtad de un usuario. */
    $lealtadhistorico = $LealtadHistorial->getLealtadHistorialCustom("SUM(lealtad_historial.valor) as redeemedPoints ", 'lealtad_historial.usuario_id', "desc", $SkeepRows, $MaxRows, $json2, true, 'lealtad_historial.usuario_id');
    $lealtadhistorico = json_decode($lealtadhistorico);

    $arrayfinal4 = array();
    if ($lealtadhistorico != "") {

        $array['redeemedPoints'] = intval($lealtadhistorico->data[0]->{".redeemedPoints"});

    } else {
        /* Asigna 0 a 'redeemedPoints' si no se cumple una condición previa. */

        $array['redeemedPoints'] = 0;
    }


    /* agrega un array a otro y define variables para procesamiento de datos. */
    array_push($arrayfinal4, $array);

    $SkeepRows = 0;
    $OrderedItem = 1;
    $MaxRows = 10;
    $rules = [];

    /* agrega reglas de filtrado por fecha a un arreglo. */
    if ($FromDateLocal != "") {
        array_push($rules, array("field" => "lealtad_historial.fecha_crea", "data" => date("Y-m-d 00:00:00", $FromDateLocal), "op" => "ge"));
    }
    if ($ToDateLocal != "") {
        array_push($rules, array("field" => "lealtad_historial.fecha_crea", "data" => date("Y-m-d 23:59:59", $ToDateLocal), "op" => "le"));
    }


    /* Se crean reglas de filtrado y se codifican en formato JSON. */
    array_push($rules, array("field" => "lealtad_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "lealtad_historial.movimiento", "data" => "E", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);


    /* obtiene y decodifica puntos ganados de un historial de lealtad. */
    $lealtadhistorico = $LealtadHistorial->getLealtadHistorialCustom("SUM(lealtad_historial.valor) as pointsEarned ", 'lealtad_historial.usuario_id', "desc", $SkeepRows, $MaxRows, $json2, true, 'lealtad_historial.usuario_id');
    $lealtadhistorico = json_decode($lealtadhistorico);

    $arrayfinal4 = array();
    if ($lealtadhistorico != "") {

        $array['pointsEarned'] = intval($lealtadhistorico->data[0]->{".pointsEarned"});

    } else {
        /* Asignación de cero a 'pointsEarned' si no se cumple una condición específica. */

        $array['pointsEarned'] = 0;
    }


    /* Añade el contenido de `$array` al final de `$arrayfinal4`. */
    array_push($arrayfinal4, $array);

}


/* genera una respuesta con código, datos y un identificador. */
$response["code"] = 0;
$response["data"] = array(
    "pointsHistory" => $arrayfinal4[0]
);
$response["rid"] = $json->rid;
