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
 * Obtener las KPI de un usuario versión 2
 *
 * Este script procesa y calcula las métricas clave de rendimiento (KPI) de un usuario, 
 * incluyendo apuestas deportivas, ganancias, depósitos, retiros, bonos y más.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param string $params->ToDateLocal Fecha de inicio del rango de consulta.
 * @param string $params->FromDateLocal Fecha de fin del rango de consulta.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Elemento por el cual ordenar los resultados.
 * @param int $params->SkeepRows Número de filas a omitir en la consulta.
 * 
 * 
 * @return array $response Arreglo con la estructura:
 *  - HasError: booleano que indica si hubo errores.
 *  - AlertType: tipo de alerta (success, error, etc.).
 *  - AlertMessage: mensaje de alerta.
 *  - Data: arreglo con las métricas calculadas, incluyendo:
 *    - LastSportBetTimeLocal: Última hora de apuesta deportiva.
 *    - TotalSportBets: Total de apuestas deportivas.
 *    - TotalUnsettledBets: Total de apuestas no resueltas.
 *    - TotalSportStakes: Total apostado en deportes.
 *    - TotalUnsettledStakes: Total apostado no resuelto.
 *    - TotalSportWinnings: Total de ganancias deportivas.
 *    - TotalCasinoWinnings: Total de ganancias en casino.
 *    - TotalCasinoStakes: Total apostado en casino.
 *    - SportProfitness: Rentabilidad deportiva.
 *    - TotalDeposit: Total de depósitos.
 *    - TotalWithdrawal: Total de retiros.
 *    - TotalPendingWithdrawal: Total de retiros pendientes.
 *    - CasinoProfitness: Rentabilidad del casino.
 *    - TotalBonus: Total de bonos.
 *
 * @throws Exception Captura cualquier excepción generada durante la ejecución.
 */


/* obtiene valores de parámetros y crea una instancia de ItTicketEnc. */
$id = $_GET["id"];
$ToDateLocal = $params->ToDateLocal;
$FromDateLocal = $params->FromDateLocal;


$ItTicketEnc = new ItTicketEnc();


/* asigna valores de parámetros y establece un valor predeterminado para $SkeepRows. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asignación de valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000;
}


/* Se crea un filtro para consultar tickets no eliminados por usuario específico. */
$rules = [];

array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => "$id", "op" => "eq"));
array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");


/* procesa datos de tickets y agrega estadísticas en formato JSON. */
$json = json_encode($filtro);

$tickets = $ItTicketEnc->getTicketsCustom(" COUNT(*) count,SUM(it_ticket_enc.vlr_apuesta) apuestas,SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios, SUM(CASE WHEN it_ticket_enc.estado = 'A' THEN 1 ELSE 0 END) count_sin,SUM(CASE WHEN it_ticket_enc.estado = 'A' THEN it_ticket_enc.vlr_apuesta ELSE 0 END) apuestas_sin  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false, 0, true);

$tickets = json_decode($tickets);

try {

    $UsuarioMandante = new UsuarioMandante("", $id, "0");

} catch (Exception $e) {
    /* Manejo de excepciones en PHP, permite capturar errores sin interrumpir la ejecución. */


}

/*
            $rules = [];

            array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => "$UsuarioMandante->usumandanteId", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            setlocale(LC_ALL, 'czech');


            $TransaccionJuego = new TransaccionJuego();
            $casino = $TransaccionJuego->getTransaccionesCustom(" COUNT(transaccion_juego.transjuego_id) count,SUM(transaccion_juego.valor_ticket) apuestas, SUM(transaccion_juego.valor_premio) premios ", "transaccion_juego.transjuego_id", "asc", $SkeepRows, $MaxRows, $json, true, "");

            $casino = json_decode($casino);
*/


/* Se crea un filtro en formato JSON para validar una transacción de usuario. */
$rules = [];

array_push($rules, array("field" => "transaccion_api.usuario_id", "data" => "$UsuarioMandante->usumandanteId", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);


/* configura la localización en checo y obtiene transacciones del API en formato JSON. */
setlocale(LC_ALL, 'czech');


$TransaccionApi = new TransaccionApi();
$casino = $TransaccionApi->getTransaccionesCustom(" SUM(CASE WHEN tipo = 'DEBIT' THEN valor ELSE 0 END) apuestas,SUM(CASE WHEN tipo = 'CREDIT' THEN valor ELSE 0 END) premios ", "transaccion_api.transapi_id", "asc", $SkeepRows, $MaxRows, $json, true, "");

$casino = json_decode($casino);


/* construye un filtro para consultas, creando reglas en JSON. */
$rules = [];

array_push($rules, array("field" => "ApiTransactions.cliID", "data" => "$id", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);


/* Se establece la configuración regional en checo y se realiza una consulta a transacciones. */
setlocale(LC_ALL, 'czech');


$ApiTransaction = new ApiTransaction();
$casino2 = $ApiTransaction->getTransaccionesCustom(" SUM(CASE WHEN ApiTransactions.trnType = 'BET' THEN ApiTransactions.trnMonto ELSE 0 END) apuestas, SUM(CASE WHEN ApiTransactions.trnType = 'WIN' THEN ApiTransactions.trnMonto ELSE 0 END) premios ", "ApiTransactions.trnID", "asc", $SkeepRows, $MaxRows, $json, true, "");

$casino2 = json_decode($casino2);


/* Se crea un filtro con reglas para validar el campo usuario_id. */
$rules = [];


array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$id", "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");


/* Establece valores predeterminados para $SkeepRows y $OrderedItem si están vacíos. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un valor predeterminado y convierte un filtro a JSON. */
if ($MaxRows == "") {
    $MaxRows = 10;
}

$json = json_encode($filtro);
$UsuarioRecarga = new UsuarioRecarga();


/* obtiene y decodifica recargas de usuarios, estableciendo condiciones de búsqueda. */
$transacciones = $UsuarioRecarga->getUsuarioRecargasCustom(" SUM(usuario_recarga.valor) recargas  ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true);

$transacciones = json_decode($transacciones);

$rules = [];
array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => "$id", "op" => "eq"));

/* Se añade una regla a un filtro y se convierte a JSON para su uso. */
array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'I'", "op" => "in"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$CuentaCobro = new CuentaCobro();

/* obtiene y decodifica cuentas de cobro, aplicando reglas de filtrado. */
$cuentas = $CuentaCobro->getCuentasCobroCustom("COUNT(*) count,SUM(cuenta_cobro.valor) retiros", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "");

$cuentas = json_decode($cuentas);

$rules = [];
array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => "$id", "op" => "eq"));

/* Agrega una regla de filtrado y convierte el filtro a formato JSON. */
array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'A'", "op" => "in"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$CuentaCobro = new CuentaCobro();

/* obtiene y decodifica cuentas de cobro desde una base de datos. */
$cuentaspendientes = $CuentaCobro->getCuentasCobroCustom("COUNT(*) count,SUM(cuenta_cobro.valor) retiros", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "");

$cuentaspendientes = json_decode($cuentaspendientes);


$rules = [];

/* Agrega reglas de filtrado a un arreglo y lo convierte a JSON para uso posterior. */
array_push($rules, array("field" => "bono_log.usuario_id", "data" => "$id", "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Bono = new BonoLog();

/* consulta y decodifica datos de bonos aplicando reglas específicas. */
$bonos = $Bono->getBonoLogsCustom("COUNT(*) count,SUM(bono_log.valor) bonos", "bono_log.bonolog_id", "asc", $SkeepRows, $MaxRows, $json, true, "");

$bonos = json_decode($bonos);

$rules = [];
array_push($rules, array("field" => "promocional_log.usuario_id", "data" => "$id", "op" => "eq"));


/* Se codifican reglas y se obtienen logs promocionales en formato JSON. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$PromocionalLog = new PromocionalLog();
$promocionales = $PromocionalLog->getPromocionalLogsCustom("COUNT(*) count,SUM(promocional_log.valor) bonos", "promocional_log.promolog_id", "asc", $SkeepRows, $MaxRows, $json, true, "");

$promocionales = json_decode($promocionales);


/* Suma apuestas y premios de dos casinos, y configura respuesta sin errores. */
$apuestas_casino = $casino->data[0]->{".apuestas"} + $casino2->data[0]->{".apuestas"};
$premios_casino = $casino->data[0]->{".premios"} + $casino2->data[0]->{".premios"};

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* asigna datos estadísticos de apuestas deportivas y casino a un arreglo. */
$response["ModelErrors"] = [];

$response["Data"] = array(
    "LastSportBetTimeLocal" => "",
    "TotalSportBets" => ($tickets->data[0]->{".count"}),
    "TotalUnsettledBets" => ($tickets->data[0]->{".count_sin"}),
    "TotalSportStakes" => round(($tickets->data[0]->{".apuestas"}), 2),
    "TotalUnsettledStakes" => ($tickets->data[0]->{".apuestas_sin"}),
    "TotalSportWinnings" => round(($tickets->data[0]->{".premios"}), 2),
    "TotalCasinoWinnings" => round($premios_casino, 2),
    "TotalCasinoStakes" => round($apuestas_casino, 2),
    "SportProfitness" => (($tickets->data[0]->{".apuestas"}) / ($tickets->data[0]->{".premios"})),
    "TotalDeposit" => round(($transacciones->data[0]->{".recargas"}), 2),
    "TotalWithdrawal" => round(($cuentas->data[0]->{".retiros"}), 2),
    "TotalPendingWithdrawal" => round(($cuentaspendientes->data[0]->{".retiros"}), 2),
    "CasinoProfitness" => (($apuestas_casino) / ($premios_casino)),
    "TotalBonus" => round(($bonos->data[0]->{".bonos"}) + ($promocionales->data[0]->{".bonos"}), 2)

);


/* Código que construye un arreglo con estadísticas de apuestas y transacciones. */
$response = [array(
    "LastSportBetTimeLocal" => "",
    "TotalSportBets" => intval($tickets->data[0]->{".count"}),
    "TotalUnsettledBets" => intval($tickets->data[0]->{".count_sin"}),
    "TotalSportStakes" => round(($tickets->data[0]->{".apuestas"}), 2),
    "TotalUnsettledStakes" => ($tickets->data[0]->{".apuestas_sin"}),
    "TotalSportWinnings" => round(($tickets->data[0]->{".premios"}), 2),
    "TotalCasinoWinnings" => round($premios_casino, 2),
    "TotalCasinoStakes" => round($apuestas_casino, 2),
    "SportProfitness" => round(($tickets->data[0]->{".apuestas"}) / max(($tickets->data[0]->{".premios"}), 1)),
    "TotalDeposit" => round(($transacciones->data[0]->{".recargas"}), 2),
    "TotalWithdrawal" => round(($cuentas->data[0]->{".retiros"}), 2),
    "TotalPendingWithdrawal" => round(($cuentaspendientes->data[0]->{".retiros"}), 2),
    "CasinoProfitness" => (($apuestas_casino) / ($premios_casino)),
    "TotalBonus" => round(($bonos->data[0]->{".bonos"}) + ($promocionales->data[0]->{".bonos"}), 2)

)];
