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
 * Dashboard/GetDashboardResume2
 *
 * Obtener el resumen para el dashboard versión 2.
 *
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param string $params->ToDateLocal Fecha final del rango en formato "Y-m-d H:i:s".
 * @param string $params->FromDateLocal Fecha inicial del rango en formato "Y-m-d H:i:s".
 * @param string $params->Region Región seleccionada para filtrar los datos.
 * @param string $params->CurrencyId Identificador de la moneda para conversión.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Orden de los elementos.
 * @param int $params->SkeepRows Número de filas a omitir.
 * 
 *
 * @return array $response Respuesta con los siguientes datos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "error").
 * - AlertMessage (string): Mensaje de alerta.
 * - Data (array): Contiene estadísticas como:
 *   - TotalPlayersByDeposit (int): Total de jugadores por depósito.
 *   - TotalPlayersByBet (int): Total de jugadores por apuesta.
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


/* procesa una fecha local desde un JSON en PHP. */
$UsuarioRecarga = new UsuarioRecarga();

$params = file_get_contents('php://input');
$params = json_decode($params);

// $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal)));


/* Código que procesa fechas y parámetros para configurar una consulta de datos. */
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
$Region = $params->Region;
$CurrencyId = $params->CurrencyId;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* construye reglas para filtrar fechas en recargas de usuarios. */
$SkeepRows = $params->SkeepRows;

$rules = [];

array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "l"));


/* Agrega reglas a un array si las variables $Region y $Currency no están vacías. */
if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}

if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}


/* Crea un filtro JSON con reglas, ajustando "SkeepRows" a cero si está vacío. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asignación de valores predeterminados a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000000;
}


/* Cuenta y suma depósitos de usuarios, convierte valores a moneda checa. */
$depositos = $UsuarioRecarga->getUsuarioRecargasCustom("COUNT(DISTINCT (usuario_recarga.usuario_id)) count,SUM(usuario_recarga.valor) valor,usuario.moneda", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

$depositos = json_decode($depositos);
setlocale(LC_ALL, 'czech');

$valor_convertido = 0;

/* Suma valores convertidos a euros desde diferentes monedas en un bucle. */
$total = 0;
foreach ($depositos->data as $key => $value) {

    $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".valor"}, 2));
    $valor_convertido = $valor_convertido + $converted_currency;
    $total = $total + $value->{".count"};

}


/* Define reglas para filtrar cuentas de cobro por estado y fecha de pago. */
$NumeroJugadoresDepositos = $total;
$TotalDepositos = $valor_convertido;

$rules = [];
array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));

/* añade reglas a un array basado en condiciones de fecha, región y moneda. */
array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));

if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}

if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}


/* Crea un filtro JSON y consulta cuentas de cobro agrupadas por moneda. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$CuentaCobro = new CuentaCobro();

$cuentas = $CuentaCobro->getCuentasCobroCustom("COUNT(*) count,SUM(cuenta_cobro.valor) valor,usuario.moneda", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");


/* Convierte valores monetarios y suma totales de cuentas mediante un bucle. */
$cuentas = json_decode($cuentas);

$valor_convertido = 0;
$total = 0;
foreach ($cuentas->data as $key => $value) {

    $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $CurrencyId, round($value->{".valor"}, 2));
    $valor_convertido = $valor_convertido + $converted_currency;
    $total = $total + $value->{".count"};

}


/* Código establece reglas para filtrar datos de tickets según estado y fecha. */
$TotalRetiros = $valor_convertido;

$rules = [];
array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));


/* Agrega reglas a un array según condiciones de región y moneda. */
if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}

if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}


/* Código que crea un filtro JSON y obtiene tickets personalizadamente desde una base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$ItTicketEnc = new ItTicketEnc();

$tickets = $ItTicketEnc->getTicketsCustom("  usuario.moneda,COUNT(DISTINCT (it_ticket_enc.usuario_id) ) count,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda", "", false, 2, true);


/* Convierte apuestas y premios de un objeto JSON a una divisa específica. */
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


/* Código asigna valores a variables y establece una respuesta sin errores. */
$NumeroJugadoresTickets = $total;
$ValorTickets = $valor_convertido_apuestas;
$ValorPremios = $valor_convertido_premios;


$response["HasError"] = false;

/* Código que estructura una respuesta con datos estadísticos sobre jugadores y transacciones. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "TotalPlayersByDeposit" => $NumeroJugadoresDepositos,
    "TotalPlayersByBet" => $NumeroJugadoresTickets,
    "BetPromByPlayer" => ($ValorTickets / $NumeroJugadoresTickets),
    "TotalAmountBets" => $ValorTickets,
    "TotalAmountWin" => $ValorPremios,
    "GGR" => floatval($ValorTickets - $ValorPremios),
    "TotalAmountDeposit" => $TotalDepositos,
    "TotalAmountWithDrawal" => $TotalRetiros,
    "DepositPromByPlayer" => ($TotalDepositos / $NumeroJugadoresDepositos)

);
