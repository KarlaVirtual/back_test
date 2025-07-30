<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Agent/GetMarketingStats2
 *
 * Obtener las estadísticas de marketing.
 *
 * @param object $params Objeto que contiene los parámetros de la solicitud, incluyendo:
 * @param int $params->AgentId ID del agente.
 * @param object $params->filter Filtro de fechas y acciones.
 * @param int $params->MaxRows Número máximo de filas a obtener.
 * @param int $params->OrderedItem Elemento ordenado.
 * @param int $params->SkeepRows Número de filas a omitir.
 * @param string $params->FromDateLocal Fecha de inicio local.
 * @param string $params->ToDateLocal Fecha de fin local.
 *
 * @return array Respuesta con la estructura:
 * - HasError: booleano indicando si hubo un error.
 * - AlertType: tipo de alerta.
 * - AlertMessage: mensaje de alerta.
 * - Data: datos de la respuesta, incluyendo estadísticas y usuarios.
 * - notification: notificaciones.
 */


/* obtiene un ID de agente y establece un filtro de fecha. */
$AgentId = $params->AgentId;

$filter = $params->filter;
$action = $filter->action;

$dateFilter1 = date("Y-m-d 00:00:00", strtotime('-1 days'));

/* establece filtros de fecha utilizando formatos específicos en PHP. */
$dateFilter2 = date("Y-m-d 23:59:59", strtotime('-1 days'));


$dateFilterFrom = date("Y-m-d H:i:s", strtotime($filter->date->from));
// $dateFilterFrom = "2018-01-01 00::s";
$dateFilterTo = date("Y-m-d 23:59:59", strtotime($filter->date->to));


/* filtra fechas, ajustando el formato y gestionando condiciones de existencia. */
if ($params->FromDateLocal != "") {
    $dateFilterFrom = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
} else {
    $seguir = false;
}

if ($params->ToDateLocal != "") {
    $dateFilterTo = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
} else {
    /* establece que $seguir es falso si se activa la condición en el bloque else. */

    $seguir = false;
}


/* Se inicializa un array para reportar datos financieros de jugadores. */
$productsReportPlayersTotal = array(array(
    "administrativeCost" => 0,
    "deposit" => 0,
    "bets" => 0,
    "wins" => 0,
    "grossRevenue" => 0,
    "expences" => 0,
    "convertedBonuses" => 0,
    "netRevenue" => 0,
    "bonus" => 0,
    "tax" => 0,
    "commission" => 0,

));


/* Se inicializa un array para almacenar estadísticas de productos y se obtienen parámetros. */
$TotalProducsStatistics = array();


//Obtenemos los montos de los productos en las fechas
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* Configura valores predeterminados para variables si están vacías. */
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un límite de filas y define reglas para filtrar datos. */
if ($MaxRows == "") {
    $MaxRows = 1000;
}

$rules = [];
array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));

/* Se crean reglas de filtrado y se convierten a formato JSON. */
array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));

array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $AgentId, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


/* obtiene y decodifica un resumen de comisiones agrupado por tipo. */
$UsucomisionResumen = new UsucomisionResumen();
$UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total,usucomision_resumen.tipo,producto_interno.abreviado ", "usucomision_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.tipo");
$UsucomisionResumens = json_decode($UsucomisionResumens);

$final = array();
$array1 = array(

    "bettingGames " => "0",
    "bettingGamesCommission " => "0",
    "bettingGamesProfit " => "0",
    "brandId " => "0",
    "brandIdCommission " => "0",
    "brandIdProfit " => "0",
    "liveGames " => "0",
    "liveGamesCommission " => "0",
    "liveGamesProfit " => "0",
    "nativePoker " => "0",
    "nativePokerCommission " => "0",
    "nativePokerProfit " => "0",
    "poolBettingGames " => "0",
    "poolBettingGamesCommission " => "0",
    "poolBettingGamesProfit " => "0",
    "skillGames " => "0",
    "skillGamesCommission " => "0",
    "skillGamesProfit " => "0",
    "slots " => "0",
    "slotsCommission " => "0",
    "slotsProfit " => "0",
    "sportsbook " => "0",
    "sportsbookCommission " => "0",
    "sportsbookProfit " => "0",
    "tableGames " => "0",
    "tableGamesCommission " => "0",
    "tableGamesProfit " => "0",
    "total " => "0",
    "totalCommission " => "0",
    "totalProfit " => "0",
    "videoPoker " => "0",
    "videoPokerCommission " => "0",
    "videoPokerProfit " => "0",
    "virtualGames " => "0",
    "virtualGamesCommission " => "0",
    "virtualGamesProfit " => "0"

);
foreach ($UsucomisionResumens->data as $key => $value) {


    switch ($value->{'producto_interno.abreviado'}) {

        case "BETSPORT":
            /* Acumula totales de apuestas deportivas en un reporte de jugadores. */

            $productsReportPlayersTotal[0]["bets"] = $productsReportPlayersTotal[0]["bets"] + $value->{'.total'};
            $array1["sportsbook"] = $array1["sportsbook"] + $value->{'.total'};
            $array1["total"] = $array1["total"] + $value->{'.total'};
            //$array1["sportsbookProfit"] = $array1[0]["sportsbookProfit"] + $value->{'.total'};
            break;


        case "WINSPORT":
            /* Actualiza totales de ganancias y ajusta cantidades en un reporte de sportsbook. */

            $productsReportPlayersTotal[0]["wins"] = $productsReportPlayersTotal[0]["wins"] + $value->{'.total'};
            $array1["sportsbook"] = $array1["sportsbook"] - $value->{'.total'};
            $array1["total"] = $array1["total"] - $value->{'.total'};

            // $array1["sportsbookProfit"] = $array1[0]["sportsbookProfit"] + $value->{'.total'};
            break;

        case "DEPOSITO":
            /* Acumula el total de depósitos en un informe de productos por jugador. */

            $productsReportPlayersTotal[0]["deposit"] = $productsReportPlayersTotal[0]["deposit"] + $value->{'.total'};

            break;


    }


}


/* Se agrega un arreglo a otro y se configura el límite de filas para productos. */
array_push($TotalProducsStatistics, $array1);


$productsReportByPlayersTotals = array();


//Obtenemos el monto por producto por fecha


$MaxRows = $params->MaxRows;

/* Asignación de valores y manejo de filas omitidas en parámetros. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* crea un filtro con condiciones para una consulta de datos. */
$rules = [];
array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $AgentId, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* genera un resumen de comisiones y lo convierte a formato JSON. */
$json = json_encode($filtro);

$UsucomisionResumen = new UsucomisionResumen();
$UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total,usucomision_resumen.tipo,producto_interno.abreviado,DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m-%d') fecha ", "DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m-%d')", "asc ", $SkeepRows, $MaxRows, $json, true, "DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m-%d'),usucomision_resumen.tipo");
$UsucomisionResumens = json_decode($UsucomisionResumens);

$final = array();

/* Se inicializa la variable "fecha" como una cadena vacía en PHP. */
$fecha = "";

foreach ($UsucomisionResumens->data as $key => $value) {

    if ($fecha != $value->{'.fecha'}) {

        /* crea un array con valores iniciales y verifica una fecha. */
        if ($fecha != "") {
            array_push($final, $array1);
        }
        $fecha = $value->{'.fecha'};
        $array1 = array(
            "administrativeCost" => "0",
            "bets" => "0",
            "bonus" => "0",
            "commission" => "0",
            "convertedBonuses" => "0",
            "date" => $fecha,
            "deposit" => "0",
            "expences" => "0",
            "grossRevenue" => "0",
            "netRevenue" => "0",
            "tax" => "0",
            "wins" => "0"
        );

    }


    /* suma totales a un arreglo según el producto interno. */
    switch ($value->{'producto_interno.abreviado'}) {

        case "BETSPORT":
            $array1["bets"] = $array1["bets"] + $value->{'.total'};
            break;

        case "WINSPORT":
            $array1["wins"] = $array1["wins"] + $value->{'.total'};
            break;

        case "DEPOSITO":
            $array1["deposit"] = $array1["deposit"] + $value->{'.total'};

            break;


    }


}


/* Se agrega un array a otro y se prepara para estadísticas de jugadores. */
array_push($final, $array1);


//Obtenemos TOP Jugadores
$productsReportByPlayersTotals = $final;

$MediaStat = array();


//Obtenemos el monto por producto por fecha


/* asigna parámetros y establece un valor predeterminado para "SkeepRows". */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Se crean reglas de filtrado para una consulta, aplicando condiciones de fecha y usuario. */
$rules = [];
array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $AgentId, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* obtiene y decodifica un resumen de marketing en formato JSON. */
$json = json_encode($filtro);

$UsumarketingResumen = new UsumarketingResumen();
$UsumarketingResumens = $UsumarketingResumen->getUsumarketingResumenCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo,DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d') fecha ", "DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d')", "asc ", $SkeepRows, $MaxRows, $json, true, "DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d'),usumarketing_resumen.tipo", "DATE_FORMAT(usumarketing_resumen.fecha_crea, '%Y-%m-%d'),usumarketing_resumen.tipo");
$UsumarketingResumens = json_decode($UsumarketingResumens);

$final = array();

/* Se inicializan una variable vacía y un arreglo vacío en PHP. */
$fecha = "";
$array1 = array();

foreach ($UsumarketingResumens->data as $key => $value) {


    /* compara fechas y agrupa datos en un arreglo si son diferentes. */
    if ($fecha != $value->{'.fecha'}) {
        if ($fecha != "") {
            array_push($final, $array1);
        }
        $fecha = $value->{'.fecha'};
        $array1 = array(
            "date" => $fecha
        );

    }


    /* Estructura de control que acumula visitas y clics según el tipo de resumen. */
    switch ($value->{'usumarketing_resumen.tipo'}) {

        case "LINKVISIT":
            $array1["visits"] = $array1["visits"] + $value->{'.total'};
            break;

        case "CLICKBANNER":
            $array1["clicks"] = $array1["clicks"] + $value->{'.total'};
            break;


    }


}


/* agrega `$array1` a `$final` y prepara `$TopUsers` como un array vacío. */
array_push($final, $array1);

//Obtenemos TOP Jugadores
$MediaStat = $final;


$TopUsers = array();


/* asigna valores de parámetros y establece un valor predeterminado para saltar filas. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Se crean reglas de filtrado para consultar registros con condiciones específicas. */
$rules = [];
array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $AgentId, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Convierte datos de comisiones en formato JSON y los organiza en un arreglo. */
$json = json_encode($filtro);

$UsucomisionResumen = new UsucomisionResumen();
$UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total,usucomision_resumen.usuarioref_id ", "usucomision_resumen.usuarioref_id", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usuarioref_id");
$UsucomisionResumens = json_decode($UsucomisionResumens);


foreach ($UsucomisionResumens->data as $key => $value) {
    $array = array(
        "playerId" => $value->{'usucomision_resumen.usuarioref_id'},
        "profit" => $value->{'.total'},
        "commission" => $value->{'.totalcomision'}
    );

    array_push($TopUsers, $array);


}


/* Variables inicializan contadores para clics y registros, tanto para hoy como para ayer. */
$sumClick = 0;
$sumClickAyer = 0;
$sumClickTodos = 0;

$sumRegistro = 0;
$sumRegistroAyer = 0;

/* Inicializa variables para sumar registros y comisiones, además de establecer un máximo de filas. */
$sumRegistroTodos = 0;

$sumComision = 0;
$sumComisionAyer = 0;
$sumComisionTodos = 0;


//Obtenemos el marketing en las fechas


$MaxRows = $params->MaxRows;

/* asigna valores de parámetros, manejando un caso de filas omitidas. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados a variables si están vacías o no definidas. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Se crea un filtro con reglas para consultar fechas y usuario en una base de datos. */
$rules = [];
array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $AgentId, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Codifica un filtro a JSON y obtiene un resumen de datos de marketing. */
$json = json_encode($filtro);

$UsumarketingResumen = new UsumarketingResumen();
$UsuarioMarketings = $UsumarketingResumen->getUsumarketingResumenGroupCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo ", "usumarketing_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usumarketing_resumen.tipo");
$UsuarioMarketings = json_decode($UsuarioMarketings);


foreach ($UsuarioMarketings->data as $key => $value) {

    switch ($value->{'usumarketing_resumen.tipo'}) {
        case "LINKVISIT":
            /* asigna el total de clics a la variable `$sumClick` para el caso "LINKVISIT". */


            $sumClick = $value->{'.total'};

            break;

        case "CLICKBANNER":
            /* Se asigna el total de clics al variable $sumClick para "CLICKBANNER". */


            $sumClick = $value->{'.total'};

            break;


        case "REGISTRO":
            /* captura el total al procesar el caso "REGISTRO". */


            $sumRegistro = $value->{'.total'};

            break;


    }

}


//Obtenemos los clicks


/* Define reglas de filtro para consultar datos basados en fechas y usuario. */
$rules = [];

array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilter1, "op" => "ge"));
array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilter2, "op" => "le"));
array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $AgentId, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte un filtro a JSON y obtiene un resumen de marketing. */
$json = json_encode($filtro);


$UsumarketingResumen = new UsumarketingResumen();
$UsuarioMarketings = $UsumarketingResumen->getUsumarketingResumenGroupCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo ", "usumarketing_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usumarketing_resumen.tipo");
$UsuarioMarketings = json_decode($UsuarioMarketings);


foreach ($UsuarioMarketings->data as $key => $value) {


    /* clasifica y suma datos según el tipo de evento. */
    switch ($value->{'usumarketing_resumen.tipo'}) {
        case "CLICKBANNER":

            $sumClickTodos = $value->{'.total'};
            $sumClickAyer = $value->{'.total'};

            break;

        case "REGISTRO":

            $sumRegistroAyer = $value->{'.total'};
            $sumRegistroTodos = $value->{'.total'};

            break;


    }

}

//Obtenemos los clicks


/* Construye un filtro con reglas para consultar datos en un sistema. */
$rules = [];

array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilter1, "op" => "ge"));
array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilter2, "op" => "le"));
array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $AgentId, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Se codifica un filtro JSON y se obtiene un resumen de marketing agrupado. */
$json = json_encode($filtro);


$UsumarketingResumen = new UsumarketingResumen();
$UsuarioMarketings = $UsumarketingResumen->getUsumarketingResumenGroupCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo ", "usumarketing_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usumarketing_resumen.tipo");
$UsuarioMarketings = json_decode($UsuarioMarketings);


/* suma totales según el tipo de resumen en la estructura de datos. */
foreach ($UsuarioMarketings->data as $key => $value) {

    switch ($value->{'usumarketing_resumen.tipo'}) {
        case "CLICKBANNER":

            $sumClickTodos = $value->{'.total'};

            break;

        case "REGISTRO":

            $sumRegistroTodos = $value->{'.total'};

            break;


    }

}


/* Código para establecer reglas de filtrado de datos en una consulta. */
$MaxRows = 1;
$OrderedItem = 1;
$SkeepRows = 0;

$rules = [];
array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilter1, "op" => "ge"));

/* Se construye un filtro JSON con reglas para consultar "UsucomisionResumen". */
array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilter2, "op" => "le"));
array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $AgentId, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$UsucomisionResumen = new UsucomisionResumen();

/* resume comisiones de usuarios, obteniendo totales en formato JSON. */
$UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total ", "usucomision_resumen.usuario_id", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usuario_id");
$UsucomisionResumens = json_decode($UsucomisionResumens);

$sumComisionAyer = $UsucomisionResumens->data[0]->{'.totalcomision'};
$sumComision = $UsucomisionResumens->data[0]->{'.totalcomision'};
$sumComisionTodos = $UsucomisionResumens->data[0]->{'.totalcomision'};


/* inicializa un arreglo de respuesta con estado verdadero y contenido vacío. */
$response["status"] = true;
$response["html"] = "";
$response["result"] = array(
    "charts" => array(
        // Grafico de estadisticas
        "getMediaStats" => array(
            array(
                "activeMedia" => 0,
                "sumClick" => $sumClick,
                "sumSignUps" => $sumRegistro,
                "sumUnique" => 0,
                "sumView" => 0,

            )

        ),
        "getTotalProductStatistics" => $TotalProducsStatistics,
        "productsReportByPlayersTotals" => array(
            "records" => $productsReportByPlayersTotals

        ),
        "MediaStat" => array(
            "records" => $MediaStat

        ),
        "getUsersStatistics" => array(
            array(
                "count" => 0,
                "name" => "signUps",
                "title" => "Sign Ups",

            ),
            array(
                "count" => 0,
                "name" => "depositing",
                "title" => "Depositing",

            ), array(
                "count" => 0,
                "name" => "firstDepositing",
                "title" => "firstDepositing",

            ), array(
                "count" => 0,
                "name" => "activeUsers",
                "title" => "activeUsers",

            ), array(
                "count" => 0,
                "name" => "firstActiveUsers",
                "title" => "firstActiveUsers",

            ),

        )
    ),
    "widgets" => array(
        "activeBannersCount" => array(
            "activeBannersCount" => 0,
            "yesterdayBannersCount" => $sumClickAyer,

            "allBannersCount" => $sumClickTodos

        ),

        "commissionsForYesterday" => array(
            "allCommission" => $sumComisionAyer,
            "yesterdayCommission" => $sumComisionTodos

        ),
        "getTopUsers" => $TopUsers,
        "getNewRegisteredPlayersCount" => array(
            "count" => $sumRegistroAyer,
            "totalPlayers" => $sumRegistroTodos

        ),
        "productsReportByPlayersTotals" => array(
            "records" => $productsReportPlayersTotal,
            "titles" => "",
            "total" => "",
            "totalRecordsCount" => "1",
        ),
        "getAcceptedWithdrawCount" => array(
            "count" => "0",
            "total" => "$ 0"

        ),
        "getDeniedWithdrawCount" => array(
            "count" => "0",
            "total" => "$ 0"

        ),
        "getPendingWithdrawCount" => array(
            "count" => "0",
            "total" => "$ 0"

        ),

    )
);


/* Se inicializa un arreglo vacío para almacenar notificaciones en la respuesta. */
$response["notification"] = array();
