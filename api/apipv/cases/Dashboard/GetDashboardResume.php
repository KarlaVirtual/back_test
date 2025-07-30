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
 * Dashboard/GetDashboardResume
 *
 * Obtener el resumen para el dashboard.
 *
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param string $params->ToDateLocal Fecha final del rango en formato "Y-m-d H:i:s".
 * @param string $params->FromDateLocal Fecha inicial del rango en formato "Y-m-d H:i:s".
 * @param string $params->Region Región seleccionada para filtrar los datos.
 * @param string $params->CurrencyId Identificador de la moneda para conversión.
 * 
 *
 * @return array $response Respuesta con los siguientes datos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "error").
 * - AlertMessage (string): Mensaje de alerta.
 * - Data (array): Contiene estadísticas como:
 *   - TotalPlayersByDeposit (int): Total de jugadores por depósito.
 *   - TotalPlayersByBet (int): Total de jugadores por apuesta.
 *   - TotalPlayersByWithDrawal (int): Total de jugadores por retiro.
 *   - BetPromByPlayer (float): Promedio de apuestas por jugador.
 *   - TotalAmountBets (float): Monto total de apuestas.
 *   - TotalAmountWin (float): Monto total de premios.
 *   - GGR (float): Ganancia bruta (Gross Gaming Revenue).
 *   - TotalAmountDeposit (float): Monto total de depósitos.
 *   - TotalAmountWithDrawal (float): Monto total de retiros.
 *   - DepositPromByPlayer (float): Promedio de depósitos por jugador.
 *
 * @throws Exception Si ocurre un error en la conversión de moneda o en las consultas a la base de datos.
 */


/* procesa fechas locales y extrae parámetros de región y moneda. */
$Region = $params->Region;
$CurrencyId = $params->CurrencyId;

$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal)));

$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));


/* Consulta SQL para sumar valores y cantidades de recargas de usuarios en un rango de fechas. */
$UsuarioRecargaMySqlDAO = new \Backend\mysql\UsuarioRecargaMySqlDAO();

$sql = "SELECT usuario.moneda,SUM(usuario_recarga_resumen.valor) valor, SUM(usuario_recarga_resumen.cantidad) cantidad FROM usuario_recarga_resumen INNER JOIN usuario ON ( usuario_recarga_resumen.usuario_id = usuario.usuario_id ) WHERE  1=1 ";

$sql = $sql . " AND usuario_recarga_resumen.fecha_crea >='" . $FromDateLocal . "' ";
$sql = $sql . " AND usuario_recarga_resumen.fecha_crea <'" . $ToDateLocal . "' ";

/* Código SQL que filtra usuarios según país y opcionalmente por moneda. */
$sql = $sql . " AND usuario.pais_id !='1' ";

if ($Region != "") {
    $sql = $sql . " AND usuario.pais_id='" . $Region . "' ";
}

if ($Currency != "") {
    //$sql = $sql. "AND usuario.moneda='".$Currency."'";
}


/* Agrupa resultados por usuario y realiza una consulta SQL sobre depósitos. */
$sql = $sql . " GROUP BY usuario_recarga_resumen.usuario_id";

$depositos = $UsuarioRecargaMySqlDAO->querySQL($sql);

$valor_convertido = 0;
$total = 0;

/* Convierte monedas de depósitos y calcula el total acumulado en una variable. */
foreach ($depositos as $key => $value) {
    $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value["usuario.moneda"], $CurrencyId, round($value[".valor"], 2));
    $valor_convertido = $valor_convertido + $converted_currency;
    $total = $total + $value[".cantidad"];

}

// $NumeroJugadoresDepositos = $total;

/* Consulta SQL para sumar valores y cantidades de retiros por usuario dentro de un rango de fechas. */
$TotalDepositos = $valor_convertido;


$sql = "SELECT usuario.moneda,SUM(usuario_retiro_resumen.valor) valor, SUM(usuario_retiro_resumen.cantidad) cantidad FROM usuario_retiro_resumen INNER JOIN usuario ON ( usuario_retiro_resumen.usuario_id = usuario.usuario_id ) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id) WHERE  1=1 ";

$sql = $sql . " AND usuario_retiro_resumen.fecha_crea >='" . $FromDateLocal . "' ";

/* Construye una consulta SQL filtrando resultados por fecha, perfil y país. */
$sql = $sql . " AND usuario_retiro_resumen.fecha_crea <'" . $ToDateLocal . "' ";
$sql = $sql . " AND usuario_perfil.perfil_id='USUONLINE' ";
$sql = $sql . " AND usuario.pais_id !='1' ";

if ($Region != "") {
    $sql = $sql . " AND usuario.pais_id='" . $Region . "' ";
}


/* consulta datos de usuarios agrupados, ignorando una condición de moneda. */
if ($Currency != "") {
    //$sql = $sql. "AND usuario.moneda='".$Currency."'";
}

$sql = $sql . " GROUP BY usuario_retiro_resumen.usuario_id";

$depositos = $UsuarioRecargaMySqlDAO->querySQL($sql);


/* Convierte y suma valores de depósitos en diferentes monedas utilizando un conversor. */
$valor_convertido = 0;
$total = 0;
foreach ($depositos as $key => $value) {
    $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value["usuario.moneda"], $CurrencyId, round($value[".valor"], 2));
    $valor_convertido = $valor_convertido + $converted_currency;
    $total = $total + $value[".cantidad"];

}

// $NumeroJugadoresRetiros = $total;

/* Consulta SQL que suma valores y cantidades de usuarios en un rango de fechas. */
$TotalRetiros = $valor_convertido;

$sql = "SELECT usuario.moneda,usuario_deporte_resumen.tipo,SUM(usuario_deporte_resumen.valor) valor, SUM(usuario_deporte_resumen.cantidad) cantidad FROM usuario_deporte_resumen INNER JOIN usuario ON ( usuario_deporte_resumen.usuario_id = usuario.usuario_id ) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id) WHERE  1=1 ";

$sql = $sql . " AND usuario_deporte_resumen.fecha_crea >='" . $FromDateLocal . "' ";
$sql = $sql . " AND usuario_deporte_resumen.fecha_crea <'" . $ToDateLocal . "' ";

/* Código SQL que filtra usuarios por perfil y país, considerando una región opcional. */
$sql = $sql . " AND usuario_perfil.perfil_id='USUONLINE' ";
$sql = $sql . " AND usuario.pais_id !='1' ";

if ($Region != "") {
    $sql = $sql . " AND usuario.pais_id='" . $Region . "' ";
}


/* construye una consulta SQL agrupando por moneda y tipo de deporte. */
if ($Currency != "") {
    //$sql = $sql. "AND usuario.moneda='".$Currency."'";
}

$sql = $sql . " GROUP BY usuario.moneda,usuario_deporte_resumen.tipo";

$depositos = $UsuarioRecargaMySqlDAO->querySQL($sql);


/* convierte valores de depósitos en distintas monedas y calcula totales. */
$valor_convertido_premios = 0;
$valor_convertido_apuestas = 0;
$total = 0;
foreach ($depositos as $key => $value) {
    $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value["usuario.moneda"], $CurrencyId, round($value[".valor"], 2));

    if ($value["usuario_deporte_resumen.tipo"] == '1') {
        $valor_convertido_apuestas = $valor_convertido_apuestas + $converted_currency;
    }

    if ($value["usuario_deporte_resumen.tipo"] == '2') {
        $valor_convertido_premios = $valor_convertido_premios + $converted_currency;

    }
    $valor_convertido = $valor_convertido + $converted_currency;
    $total = $total + $value[".cantidad"];

}

//$NumeroJugadoresTickets = $total;

/* Selecciona y suma valores y cantidades de apuestas de usuarios en un rango de fechas. */
$ValorTicketsUsuario = $valor_convertido_apuestas;
$ValorPremiosUsuario = $valor_convertido_premios;


$sql = "SELECT usuario.moneda,usuario_deporte_resumen.tipo,SUM(usuario_deporte_resumen.valor) valor, SUM(usuario_deporte_resumen.cantidad) cantidad FROM usuario_deporte_resumen INNER JOIN usuario ON ( usuario_deporte_resumen.usuario_id = usuario.usuario_id ) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id) WHERE  1=1 ";

$sql = $sql . " AND usuario_deporte_resumen.fecha_crea >='" . $FromDateLocal . "' ";

/* Construcción de una consulta SQL para filtrar usuarios según condiciones específicas. */
$sql = $sql . " AND usuario_deporte_resumen.fecha_crea <'" . $ToDateLocal . "' ";
$sql = $sql . " AND usuario_perfil.perfil_id='PUNTOVENTA' ";
$sql = $sql . " AND usuario.pais_id !='1' ";

if ($Region != "") {
    $sql = $sql . " AND usuario.pais_id='" . $Region . "' ";
}


/* Condiciona una consulta SQL agrupando resultados por moneda y tipo de deporte. */
if ($Currency != "") {
    //$sql = $sql. "AND usuario.moneda='".$Currency."'";
}

$sql = $sql . " GROUP BY usuario.moneda,usuario_deporte_resumen.tipo";

$depositos = $UsuarioRecargaMySqlDAO->querySQL($sql);


/* convierte y suma valores de depósitos según su tipo y moneda. */
$valor_convertido_premios = 0;
$valor_convertido_apuestas = 0;
$total = 0;
foreach ($depositos as $key => $value) {
    $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value["usuario.moneda"], $CurrencyId, round($value[".valor"], 2));

    if ($value["usuario_deporte_resumen.tipo"] == '1') {
        $valor_convertido_apuestas = $valor_convertido_apuestas + $converted_currency;
    }

    if ($value["usuario_deporte_resumen.tipo"] == '2') {
        $valor_convertido_premios = $valor_convertido_premios + $converted_currency;

    }
    $valor_convertido = $valor_convertido + $converted_currency;
    $total = $total + $value[".cantidad"];

}

//$NumeroJugadoresTickets = $total;

/* Construye una consulta SQL para obtener usuarios según fecha en sistema de ventas. */
$ValorTicketsPuntoVenta = $valor_convertido_apuestas;
$ValorPremiosPuntoVenta = $valor_convertido_premios;


$sql = "SELECT usuario.usuario_id FROM usuario_deporte_resumen INNER JOIN usuario ON ( usuario_deporte_resumen.usuario_id = usuario.usuario_id ) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id) WHERE  1=1 ";

$sql = $sql . " AND usuario_deporte_resumen.fecha_crea >='" . $FromDateLocal . "' ";

/* Construye una consulta SQL filtrando por fecha, perfil y país. */
$sql = $sql . " AND usuario_deporte_resumen.fecha_crea <'" . $ToDateLocal . "' ";
$sql = $sql . " AND usuario_perfil.perfil_id='USUONLINE' ";
$sql = $sql . " AND usuario.pais_id !='1' ";

if ($Region != "") {
    $sql = $sql . " AND usuario.pais_id='" . $Region . "' ";
}


/* construye una consulta SQL para contar usuarios agrupados por ID. */
if ($Currency != "") {
    //$sql = $sql. "AND usuario.moneda='".$Currency."'";
}

$sql = $sql . " GROUP BY usuario.usuario_id";

$depositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_usuarios
FROM (" . $sql . ") a ");


/* Inicializa variables y selecciona IDs de usuarios mediante una consulta SQL. */
$valor_convertido_premios = 0;
$valor_convertido_apuestas = 0;
$total = 0;
$NumeroJugadoresTickets = $depositos[0][".cantidad_usuarios"];


$sql = "SELECT usuario.usuario_id FROM usuario_recarga_resumen INNER JOIN usuario ON ( usuario_recarga_resumen.usuario_id = usuario.usuario_id ) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id) WHERE  1=1 ";


/* Construye una consulta SQL con condiciones de fecha y filtrado por perfil y país. */
$sql = $sql . " AND usuario_recarga_resumen.fecha_crea >='" . $FromDateLocal . "' ";
$sql = $sql . " AND usuario_recarga_resumen.fecha_crea <'" . $ToDateLocal . "' ";
$sql = $sql . " AND usuario_perfil.perfil_id='USUONLINE' ";
$sql = $sql . " AND usuario.pais_id !='1' ";

if ($Region != "") {
    $sql = $sql . " AND usuario.pais_id='" . $Region . "' ";
}


/* genera una consulta SQL que agrupa usuarios y cuenta su cantidad. */
if ($Currency != "") {
    //$sql = $sql. "AND usuario.moneda='".$Currency."'";
}

$sql = $sql . " GROUP BY usuario.usuario_id";

$depositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_usuarios
FROM (" . $sql . ") a ");


/* Código consulta SQL para obtener IDs de usuarios con retiros recientes. */
$NumeroJugadoresDepositos = $depositos[0][".cantidad_usuarios"];


$sql = "SELECT usuario.usuario_id FROM usuario_retiro_resumen INNER JOIN usuario ON ( usuario_retiro_resumen.usuario_id = usuario.usuario_id ) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id) WHERE  1=1 ";

$sql = $sql . " AND usuario_retiro_resumen.fecha_crea >='" . $FromDateLocal . "' ";

/* Construcción de una consulta SQL filtrando por fecha, perfil y país del usuario. */
$sql = $sql . " AND usuario_retiro_resumen.fecha_crea <'" . $ToDateLocal . "' ";
$sql = $sql . " AND usuario_perfil.perfil_id='USUONLINE'";
$sql = $sql . " AND usuario.pais_id !='1' ";

if ($Region != "") {
    $sql = $sql . " AND usuario.pais_id='" . $Region . "' ";
}


/* Consulta SQL que agrupa usuarios y cuenta su cantidad, filtrando por moneda opcional. */
if ($Currency != "") {
    //$sql = $sql. "AND usuario.moneda='".$Currency."'";
}

$sql = $sql . " GROUP BY usuario.usuario_id";

$depositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_usuarios
FROM (" . $sql . ") a ");


/* Asignación del número de jugadores que realizaron retiros desde un arreglo de depósitos. */
$NumeroJugadoresRetiros = $depositos[0][".cantidad_usuarios"];

if (false) {

    /* crea un objeto UsuarioRecarga y obtiene parámetros de entrada en formato JSON. */
    $UsuarioRecarga = new UsuarioRecarga();

    $params = file_get_contents('php://input');
    $params = json_decode($params);

    // $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
    $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal)));


    /* procesa fechas y obtiene parámetros relacionados con región, moneda, y filas ordenadas. */
    $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
    $Region = $params->Region;
    $CurrencyId = $params->CurrencyId;

    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;

    /* define reglas de filtrado para fechas en una recarga de usuario. */
    $SkeepRows = $params->SkeepRows;

    $rules = [];

    array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "l"));


    /* Agrega condiciones a un arreglo de reglas según la región y la moneda. */
    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }

    if ($Currency != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
    }


    /* Se define un filtro y se codifica en JSON, manejando filas a omitir. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }


    /* inicializa variables si están vacías, asignando valores predeterminados. */
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 10000000;
    }


    /* obtiene y decodifica recargas de usuario en moneda específica. */
    $depositos = $UsuarioRecarga->getUsuarioRecargasCustom("COUNT(DISTINCT (usuario_recarga.usuario_id)) count,SUM(usuario_recarga.valor) valor,usuario.moneda", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

    $depositos = json_decode($depositos);
    setlocale(LC_ALL, 'czech');

    $valor_convertido = 0;

    /* suma valores convertidos a euros y cuenta depósitos. */
    $total = 0;
    foreach ($depositos->data as $key => $value) {

        $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".valor"}, 2));
        $valor_convertido = $valor_convertido + $converted_currency;
        $total = $total + $value->{".count"};

    }


    /* Define reglas para filtrar datos de cuentas de cobro según criterios específicos. */
    $NumeroJugadoresDepositos = $total;
    $TotalDepositos = $valor_convertido;

    $rules = [];
    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
    array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));

    /* Agrega reglas a un array según condiciones de fecha, región y moneda. */
    array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));

    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }

    if ($Currency != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
    }


    /* Genera un filtro JSON y obtiene cuentas de cobro personalizadas con parámetros definidos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $CuentaCobro = new CuentaCobro();

    $cuentas = $CuentaCobro->getCuentasCobroCustom("COUNT(*) count,SUM(cuenta_cobro.valor) valor,usuario.moneda", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");


    /* convierte monedas y calcula el total de cuentas desde un JSON. */
    $cuentas = json_decode($cuentas);

    $valor_convertido = 0;
    $total = 0;
    foreach ($cuentas->data as $key => $value) {

        $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $CurrencyId, round($value->{".valor"}, 2));
        $valor_convertido = $valor_convertido + $converted_currency;
        $total = $total + $value->{".count"};

    }


    /* Se define un conjunto de reglas para filtrar tickets por estado y fecha. */
    $TotalRetiros = $valor_convertido;

    $rules = [];
    array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
    array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));


    /* Agrega reglas de filtrado según región y moneda si no están vacíos. */
    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }

    if ($Currency != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
    }


    /* Se crea un filtro JSON para obtener tickets personalizados mediante consulta a una base de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $ItTicketEnc = new ItTicketEnc();

    $tickets = $ItTicketEnc->getTicketsCustom("  usuario.moneda,COUNT(DISTINCT (it_ticket_enc.usuario_id) ) count,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda", "", false, 2, true);


    /* Convierte apuestas y premios de tickets a una moneda específica, calculando totales. */
    $tickets = json_decode($tickets);

    $valor_convertido_apuestas = 0;
    $valor_convertido_premios = 0;
    $total = 0;
    foreach ($tickets->data as $key => $value) {

        $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
        $valor_convertido_apuestas = $valor_convertido_apuestas + $converted_currency;
        $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
        $valor_convertido_premios = $valor_convertido_premios + $converted_currency;

        $total = $total + $value->{".count"};

    }


    /* asigna valores a variables relacionadas con jugadores, tickets y premios. */
    $NumeroJugadoresTickets = $total;
    $ValorTickets = $valor_convertido_apuestas;
    $ValorPremios = $valor_convertido_premios;

}


/* Se suman valores de tickets y premios, indicando éxito en la respuesta. */
$ValorTickets = $ValorTicketsUsuario + $ValorTicketsPuntoVenta;
$ValorPremios = $ValorPremiosUsuario + $ValorPremiosPuntoVenta;

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* Estructura de respuesta que incluye estadísticas de jugadores y transacciones financieras. */
$response["ModelErrors"] = [];

$response["Data"] = array(
    "TotalPlayersByDeposit" => $NumeroJugadoresDepositos,
    "TotalPlayersByBet" => $NumeroJugadoresTickets,
    "TotalPlayersByWithDrawal" => $NumeroJugadoresRetiros,
    "BetPromByPlayer" => ($ValorTickets / $NumeroJugadoresTickets),
    "TotalAmountBets" => $ValorTicketsUsuario + $ValorTicketsPuntoVenta,
    "TotalAmountWin" => $ValorPremiosUsuario + $ValorPremiosPuntoVenta,
    "TotalAmountBetsUser" => $ValorTicketsUsuario,
    "TotalAmountWinUser" => $ValorPremiosUsuario,

    "TotalAmountBetsCashDesk" => $ValorTicketsPuntoVenta,
    "TotalAmountWinCashDesk" => $ValorPremiosPuntoVenta,

    "GGR" => floatval($ValorTickets - $ValorPremios),
    "TotalAmountDeposit" => $TotalDepositos,
    "TotalAmountWithDrawal" => $TotalRetiros,
    "DepositPromByPlayer" => ($TotalDepositos / $NumeroJugadoresDepositos)

);

