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
 * Obtener detalles de un agente específico por un ID (versión 3).
 *
 * @param object $params Datos JSON codificados que contienen la información de entrada.
 * @param int $params ->AgentId ID del agente.
 * @param object $params ->filter Filtro de búsqueda.
 * @param string $params ->filter->action Acción a realizar.
 * @param object $params ->filter->date Rango de fechas.
 * @param string $params ->filter->date->from Fecha de inicio.
 * @param string $params ->filter->date->to Fecha de fin.
 * @param string $params ->FromDateLocal Fecha de inicio local.
 * @param string $params ->ToDateLocal Fecha de fin local.
 * @param int $params ->MaxRows Número máximo de filas.
 * @param int $params ->OrderedItem Elemento ordenado.
 * @param int $params ->SkeepRows Filas omitidas.
 *
 * @return array
 * - status: boolean Indica si la operación fue exitosa.
 * - html: string Contenido HTML.
 * - result: array Resultados de la operación.
 *   - charts: array Gráficos generados.
 *     - MediaStat: array Estadísticas de medios.
 *       - records: array Registros de estadísticas.
 *     - MarketingVsRegistros: array Comparación de marketing y registros.
 *       - records: array Registros de marketing y registros.
 *     - MoneyUsers: array Usuarios y su dinero.
 *       - records: array Registros de usuarios y dinero.
 *     - AgeUsers: array Usuarios y sus edades.
 *       - records: array Registros de usuarios y edades.
 * - notification: array Notificaciones generadas.
 *
 * @throws Exception Si ocurre un error durante la operación.
 */


/* Extrae parámetros de entrada y establece un filtro de fecha para acciones recientes. */
$AgentId = $params->AgentId;

$filter = $params->filter;
$action = $filter->action;

$dateFilter1 = date("Y-m-d 00:00:00", strtotime('-1 days'));

/* establece filtros de fecha para un rango específico en formato datetime. */
$dateFilter2 = date("Y-m-d 23:59:59", strtotime('-1 days'));


$dateFilterFrom = date("Y-m-d H:i:s", strtotime($filter->date->from));
// $dateFilterFrom = "2018-01-01 00::s";
$dateFilterTo = date("Y-m-d 23:59:59", strtotime($filter->date->to));


/* establece filtros de fecha a partir de parámetros de entrada. */
if ($params->FromDateLocal != "") {
    $dateFilterFrom = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
} else {
    $seguir = false;
}

if ($params->ToDateLocal != "") {
    $dateFilterTo = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
} else {
    /* establece la variable $seguir como falsa si se cumple la condición "else". */

    $seguir = false;
}


/* Código para inicializar un reporte de productos con valores financieros en cero. */
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


/* inicializa un array para reportar montos de productos por jugadores. */
$productsReportByPlayersTotals = array();


//Obtenemos el monto por producto por fecha


$MaxRows = $params->MaxRows;

/* asigna valores de parámetros y establece un valor predeterminado para filas omitidas. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables si no están definidas, asignando valores predeterminados. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Se definen reglas de filtrado para consultas basadas en fechas y usuario. */
$rules = [];
array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $AgentId, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Codifica un filtro en JSON y consulta resúmenes de comisiones agrupadas. */
$json = json_encode($filtro);

$UsucomisionResumen = new UsucomisionResumen();
$UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total,usucomision_resumen.tipo,producto_interno.abreviado,DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m-%d') fecha ", "DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m-%d')", "asc ", $SkeepRows, $MaxRows, $json, true, "DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m-%d'),usucomision_resumen.tipo");
$UsucomisionResumens = json_decode($UsucomisionResumens);

$final = array();

/* Se declara una variable vacía llamada "fecha" en un lenguaje de programación. */
$fecha = "";

foreach ($UsucomisionResumens->data as $key => $value) {

    if ($fecha != $value->{'.fecha'}) {

        /* verifica la fecha y agrega datos financieros a un array. */
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


    /* suma totales a diferentes categorías según el valor del producto. */
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


/* agrega `$array1` a `$final` y obtiene el informe de jugadores. */
array_push($final, $array1);


//Obtenemos TOP Jugadores
$productsReportByPlayersTotals = $final;

$MediaStat = array();


//Obtenemos el monto por producto por fecha


/* inicializa variables y establece un valor predeterminado para $SkeepRows. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asignación de valores predeterminados si las variables están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Se crean reglas de filtros para una consulta, utilizando fechas y un ID de usuario. */
$rules = [];
array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $AgentId, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* procesa datos de marketing, sumando y formateando fechas en JSON. */
$json = json_encode($filtro);

$UsumarketingResumen = new UsumarketingResumen();
$UsumarketingResumens = $UsumarketingResumen->getUsumarketingResumenCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo,DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d') fecha ", "DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d')", "asc ", $SkeepRows, $MaxRows, $json, true, "DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d'),usumarketing_resumen.tipo", "DATE_FORMAT(usumarketing_resumen.fecha_crea, '%Y-%m-%d'),usumarketing_resumen.tipo");
$UsumarketingResumens = json_decode($UsumarketingResumens);

$final = array();

/* Se inicializan una variable de fecha y un array vacío en PHP. */
$fecha = "";
$array1 = array();


foreach ($UsumarketingResumens->data as $key => $value) {


    /* compara fechas, actualiza un array y agrega elementos según condición. */
    if ($fecha != $value->{'.fecha'}) {
        if ($fecha != "") {
            array_push($final, $array1);
        }
        $fecha = $value->{'.fecha'};
        $array1 = array(
            "date" => $fecha
        );

    }


    /* agrega valores a un array basado en el tipo de evento recibido. */
    switch ($value->{'usumarketing_resumen.tipo'}) {

        case "LINKVISIT":
            $array1["visits"] = $array1["visits"] + $value->{'.total'};
            break;

        case "CLICKBANNER":
            $array1["clicks"] = $array1["clicks"] + $value->{'.total'};
            break;

        case "REGISTRO":
            $array1["register"] = $array1["register"] + $value->{'.total'};
            break;

    }


}


/* Se añaden datos de `$array1` a `$final` y se prepara `$MediaStat`. */
array_push($final, $array1);

//Obtenemos TOP Jugadores
$MediaStat = $final;

$MarketingVsRegistros = array();


//Obtenemos el monto por producto por fecha


/* asigna valores de parámetros y maneja el caso de filas omitidas. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Define un conjunto de reglas de filtrado para consultas de datos en PHP. */
$rules = [];
array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
array_push($rules, array("field" => "usumarketing_resumen.tipo", "data" => "'LINKVISIT','CLICKBANNER'", "op" => "in"));
array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $AgentId, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Se obtiene un resumen de datos de marketing en formato JSON. */
$json = json_encode($filtro);

$UsumarketingResumen = new UsumarketingResumen();
$UsumarketingResumens = $UsumarketingResumen->getUsumarketingResumenCustom(" usumarketing_resumen.externo_id,SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo,DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d') fecha ", "DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d')", "asc ", $SkeepRows, $MaxRows, $json, true, "usumarketing_resumen.externo_id,usumarketing_resumen.tipo", "usumarketing_resumen.externo_id,usumarketing_resumen.tipo");
$UsumarketingResumens = json_decode($UsumarketingResumens);

$final = array();

/* Inicializa una variable vacía y un arreglo en PHP. */
$fecha = "";
$array1 = array();


foreach ($UsumarketingResumens->data as $key => $value) {


    /* Compara fechas y crea un nuevo array si son diferentes y no están vacías. */
    if ($fecha != $value->{'usumarketing_resumen.externo_id'}) {
        if ($fecha != "") {
            array_push($final, $array1);

        }
        $fecha = $value->{'usumarketing_resumen.externo_id'};
        $array1 = array(
            "marketing" => $fecha
        );

    }


    /* Se crea un objeto 'Usuario' y se asigna un valor a 'externoId'. */
    $Usuario = new Usuario();


    $rules = [];
    $externoId = $value->{'usumarketing_resumen.externo_id'};


    /* gestiona diferentes tipos de marketing y actualiza un arreglo y reglas. */
    switch ($value->{'usumarketing_resumen.tipo'}) {

        case "LINKVISIT":

            $array1["value"] = $array1["value"] + $value->{'.total'};
            array_push($rules, array("field" => "registro.link_id", "data" => $externoId, "op" => "eq"));

            break;

        case "CLICKBANNER":
            $array1["value"] = $array1["value"] + $value->{'.total'};
            array_push($rules, array("field" => "registro.banner_id", "data" => $externoId, "op" => "eq"));

            break;

        case "REGISTRO":
            // $array1["register"] = $array1["register"] + $value->{'.total'};
            break;

    }


    /* crea un filtro de reglas para obtener usuarios con fechas específicas. */
    array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateFilterFrom ", "op" => "ge"));
    array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateFilterTo", "op" => "le"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);


    $usuarios = $Usuario->getUsuariosCustom(" COUNT(*) count ", "usuario.usuario_id", "asc", 0, 10, $json2, true);


    /* Decodifica JSON de usuarios y asigna el conteo a un registro si no está vacío. */
    $usuarios = json_decode($usuarios);

    $usuariosFinal = [];
    $response["Data"] = $usuarios->data[0]->{".count"};
    $array1["register"] = 0;

    if ($response["Data"] = $usuarios->data[0]->{".count"} != "") {
        $array1["register"] = $usuarios->data[0]->{".count"};
    }

}


/* Se añade $array1 a $final y se asigna a $MarketingVsRegistros. */
array_push($final, $array1);

//Obtenemos TOP Jugadores
$MarketingVsRegistros = $final;


$MoneyUsers = array();

/* Se inicializa un array y se crea una instancia de la clase Usuario. */
$AgeUsers = array();


//Obtenemos el monto por producto por fecha
$Usuario = new Usuario();

$rules = [];

/* Se agregan reglas de filtrado a un arreglo para una consulta de base de datos. */
array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateFilterFrom ", "op" => "ge"));
array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateFilterTo", "op" => "le"));
array_push($rules, array("field" => "registro.afiliador_id", "data" => "198", "op" => "eq"));
//array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $AgentId, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Convierte datos a JSON, consulta usuarios y decodifica el resultado. */
$json = json_encode($filtro);


$usuarios = $Usuario->getUsuariosCustom(" usuario.usuario_id,registro.creditos_base, registro.creditos, ( registro.creditos_base + registro.creditos ) saldo,c.fecha_nacim ", "saldo", "desc", 0, 100, $json, true);

$usuarios = json_decode($usuarios);


/* Inicia un array vacío llamado 'final' para almacenar elementos posteriormente. */
$final = array();


/* Se procesan datos de usuarios, acumulando dinero y edades en dos arreglos. */
foreach ($usuarios->data as $key => $value) {

    $array1 = array();

    $array1["user"] = "Usuario " . $value->{"usuario.usuario_id"};
    $array1["money"] = $value->{"registro.creditos_base"} + $value->{"registro.creditos"};

    array_push($MoneyUsers, $array1);


    $array2 = array();

    $array1["user"] = "Usuario " . $value->{"usuario.usuario_id"};
    $array1["money"] = $value->{"registro.creditos_base"} + $value->{"registro.creditos"};
    $array1["age"] = $value->{"c.fecha_nacim"};

    array_push($AgeUsers, $array1);

}


//Obtenemos TOP Jugadores


/* inicializa un array con un estado verdadero y una cadena vacía. */
$response["status"] = true;
$response["html"] = "";
$response["result"] = array(
    "charts" => array(
        // Grafico de estadisticas

        "MediaStat" => array(
            "records" => $MediaStat

        ),
        "MarketingVsRegistros" => array(
            "records" => $MarketingVsRegistros

        ),

        "MoneyUsers" => array(
            "records" => $MoneyUsers

        ),

        "AgeUsers" => array(
            "records" => $AgeUsers

        )
    )
);


/* Se inicializa un arreglo vacío para almacenar notificaciones en la variable $response. */
$response["notification"] = array();
