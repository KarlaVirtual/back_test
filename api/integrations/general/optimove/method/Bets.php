<?php

/**
 * Archivo que contiene la lógica para procesar solicitudes relacionadas con apuestas.
 *
 * Este archivo incluye la definición de variables, consultas SQL y procesamiento de datos
 * para obtener información sobre tickets de apuestas en un rango de fechas específico.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $UsuarioMandante          Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $userNow                  Variable que almacena la información del usuario actualmente autenticado.
 * @var mixed $params                   Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $dateFrom                 Variable que representa una fecha de inicio en un rango de fechas.
 * @var mixed $dateTo                   Variable que representa una fecha de finalización en un rango de fechas.
 * @var mixed $dateFromHour             Variable que almacena la hora de inicio de un intervalo de fechas.
 * @var mixed $dateToHour               Variable que almacena la hora de finalización de un intervalo de fechas.
 * @var mixed $TodayFrom                Variable que almacena la hora de inicio del día actual.
 * @var mixed $TodayTo                  Variable que almacena la hora de finalización del día actual.
 * @var mixed $partner                  Variable que almacena información sobre un socio o afiliado.
 * @var mixed $paisId                   Variable que almacena el identificador único de un país.
 * @var mixed $headers                  Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $sql                      Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $ItTicketEnc              Variable que representa una entidad de ticket en el sistema.
 * @var mixed $ItTicketEncMySqlDAO      Objeto que maneja operaciones de base de datos para la entidad ItTicketEnc en MySQL.
 * @var mixed $transaccion              Variable que almacena datos relacionados con una transacción.
 * @var mixed $count                    Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $final                    Esta variable se utiliza para indicar si un proceso o estado es final.
 * @var mixed $Tickets                  Variable que almacena un conjunto de tickets generados.
 * @var mixed $key                      Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $array                    Variable que almacena una lista o conjunto de datos.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 */

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

$params = file_get_contents('php://input');
$params = json_decode($params);
ini_set('memory_limit', '-1');
$dateFrom = date("Y-m-d H:i:s", strtotime($params->fromDate));
$dateTo = date("Y-m-d H:i:s", strtotime($params->toDate));
$dateFromHour = date("H:i:s", strtotime($params->fromDate));
$dateToHour = date("H:i:s", strtotime($params->toDate));
$TodayFrom = date("Y-m-d", strtotime($params->fromDate));
$TodayTo = date("Y-m-d", strtotime($params->toDate));
//variables fechas
$partner = $params->partner;
$paisId = $params->paisId;
header('Content-Type: application/json');

$headers = getallheaders();
$ConfigurationEnvironment = new ConfigurationEnvironment();


$sql =
    "SELECT count(*) count
    FROM it_ticket_det itd
               JOIN it_ticket_enc ite ON itd.ticket_id = ite.ticket_id
               JOIN usuario u ON ite.usuario_id = u.usuario_id
               JOIN registro r ON u.usuario_id = r.usuario_id
      WHERE 1 = 1
        AND ( ite.fecha_cierre_time BETWEEN '$dateFrom' AND '$dateTo' OR (
          (fecha_cierre >= '$TodayFrom'
              AND ite.hora_cierre >= '$dateFromHour' ) AND ( fecha_cierre <= '$TodayTo' AND ite.hora_cierre <= '$dateToHour')))
    AND u.pais_id = $paisId
    AND u.mandante = $partner
        ";


$ItTicketEnc = new ItTicketEnc();

$ItTicketEncMySqlDAO = new \Backend\mysql\ItTicketEncMySqlDAO();
$transaccion = $ItTicketEncMySqlDAO->getTransaction();

$count = $ItTicketEnc->execQuery($transaccion, $sql);
$count = json_decode(json_encode($count), true);

$final = array();

$sql =
    "SELECT *, BetAmount - WinAmount AS NetRevenue
FROM (SELECT ite.ticket_id                                   AS TicketID,
             u.usuario_id                                    AS PlayerID,
             CASE
                 WHEN ite.fecha_crea_time IS NULL THEN CONCAT(ite.fecha_crea, ' ', ite.hora_crea)
                 ELSE ite.fecha_crea_time END                AS Opendate,
             CASE
                 WHEN ite.fecha_cierre_time IS NULL THEN CONCAT(ite.fecha_cierre, ' ', ite.hora_cierre)
                 ELSE ite.fecha_cierre_time END              AS Settleddate,
             ite.vlr_apuesta                                 AS CashBetAmount,
             0                                               AS BonusBetAmount, 
             ite.vlr_apuesta                                 AS BetAmount,
             ite.vlr_premio                                  AS WinAmount,
             ite.bet_mode,
             round(exp(sum(log(COALESCE(itd.logro, 0)))), 2) AS Odds,           
             CASE
                 WHEN ite.cant_lineas > 1 THEN 'Combination'
                 WHEN ite.cant_lineas = 1 THEN 'Simple'
                 ELSE 'System' END                           AS TicketType,     
             'Mobile'                                           AS Platform,       
             CASE
                 WHEN ite.bet_mode = 'Live' THEN 1
                 ELSE 0 END                                  AS IsLive,         
             ite.bet_status                                  AS TicketStatus,   
             CASE
                 WHEN ite.freebet = 0 THEN 0
                 ELSE 1 END                                  AS IsFree,
             CASE
                 WHEN ite.bet_status = 'T' THEN 1
                 ELSE 0 END                                  AS CashoutFlag,
             CASE
                 WHEN ite.fecha_pago_time IS NOT NULL  THEN ite.fecha_pago_time
                 WHEN ite.fecha_cierre_time IS NOT NULL  THEN ite.fecha_cierre_time
                 ELSE ite.fecha_crea_time END                AS LastUpdated
      FROM it_ticket_det itd
               JOIN it_ticket_enc ite ON itd.ticket_id = ite.ticket_id
               JOIN usuario u ON ite.usuario_id = u.usuario_id
               JOIN registro r ON u.usuario_id = r.usuario_id
      WHERE 1 = 1
        AND ( ite.fecha_cierre_time BETWEEN '$dateFrom' AND '$dateTo' OR (
          (ite.fecha_cierre >= '$TodayFrom'
              AND ite.hora_cierre >= '$dateFromHour' ) AND ( ite.fecha_cierre <= '$TodayTo' AND ite.hora_cierre <= '$dateToHour')))
       AND u.pais_id = $paisId
       AND u.mandante = $partner
      GROUP BY ite.ticket_id) x";


$ItTicketEnc = new ItTicketEnc();

$ItTicketEncMySqlDAO = new \Backend\mysql\ItTicketEncMySqlDAO();
$transaccion = $ItTicketEncMySqlDAO->getTransaction();
$Tickets = $ItTicketEnc->execQuery($transaccion, $sql);

$Tickets = json_decode(json_encode($Tickets), true);
$final = array();
foreach ($Tickets as $key => $value) {
    $array = array();

    $array["TicketID"] = intval($value["x.TicketID"]);
    $array["PlayerID"] = $value["x.PlayerID"];
    $array["Opendate"] = $value["x.Opendate"];
    $array["Settleddate"] = $value["x.Settleddate"];
    $array["CashBetAmount"] = round($value["x.CashBetAmount"], 2);
    $array["BonusBetAmount"] = round($value["x.BonusBetAmount"], 2);
    $array["BetAmount"] = round($value["x.CashBetAmount"], 2);
    $array["WinAmount"] = round($value["x.WinAmount"], 2);
    $array["NetRevenue"] = round($value[".NetRevenue"], 2);
    $array["Odds"] = round($value["x.Odds"], 2);
    $array["TicketType"] = $value["x.TicketType"];
    $array["Platform"] = $value["x.Platform"];
    $array["TicketStatus"] = $value["x.TicketStatus"];
    $array["IsLive"] = intval($value["x.IsLive"]);
    $array["IsFree"] = intval($value["x.IsFree"]);
    $array["CashoutFlag"] = intval($value["x.CashoutFlag"]);
    $array["LastUpdated"] = $value["x.LastUpdated"];
    $array["BetMode"] = $value["x.bet_mode"];


    array_push($final, $array);
}

if ($count[0][".count"] != 0) {
    $response = array();
    $response["Error"] = false;
    $response["Mensaje"] = "success";
    $response["TotalCount"] = $count[0][".count"];
    $response["Data"] = $final;
} else {
    $response["Error"] = false;
    $response["Mensaje"] = "No hay tickets en este rango de fechas";
    $response["TotalCount"] = 0;
    $response["Data"] = [];
}





