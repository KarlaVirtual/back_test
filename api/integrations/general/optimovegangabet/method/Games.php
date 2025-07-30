<?php

/**
 * Archivo que contiene la lógica para procesar y generar reportes de apuestas
 * realizadas por los usuarios en un rango de fechas específico.
 *
 * Este script recibe parámetros de entrada a través de una solicitud HTTP,
 * ejecuta consultas SQL para obtener datos relacionados con las apuestas y
 * genera una respuesta en formato JSON con los resultados.
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
 * @var mixed $UsuarioMandante       Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $userNow               Variable que almacena la información del usuario actualmente autenticado.
 * @var mixed $Mandante              Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $params                Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $headers               Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $dateFrom              Variable que representa una fecha de inicio en un rango de fechas.
 * @var mixed $dateTo                Variable que representa una fecha de finalización en un rango de fechas.
 * @var mixed $MaxRows               Variable que define el número máximo de registros a retornar en una consulta.
 * @var mixed $OrderedItem           Variable que representa un elemento ordenado en una lista.
 * @var mixed $SkeepRows             Variable que indica el número de registros a omitir en una consulta.
 * @var mixed $partner               Variable que almacena información sobre un socio o afiliado.
 * @var mixed $paisId                Variable que almacena el identificador único de un país.
 * @var mixed $_REQUEST              Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $sql                   Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $TransjuegoLog         Variable que almacena registros de transacciones del sistema Transjuego.
 * @var mixed $TransjuegoLogMySqlDAO Variable que representa la capa de acceso a datos MySQL para los registros de Transjuego.
 * @var mixed $transaccion           Variable que almacena datos relacionados con una transacción.
 * @var mixed $count                 Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $apuestas              Variable que almacena un conjunto de apuestas realizadas.
 * @var mixed $apuestasData          Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $key                   Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $value                 Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $array                 Variable que almacena una lista o conjunto de datos.
 * @var mixed $response              Esta variable almacena la respuesta generada por una operación o petición.
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

header('Content-Type: application/json');
ini_set('memory_limit', '-1');
$headers = getallheaders();


$dateFrom = $params->fromDate;
$dateTo = $params->toDate;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;
$partner = $params->partner;
$paisId = $params->paisId;
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


$sql =
    "SELECT count(*) count FROM (SELECT u.usuario_id
 FROM transjuego_log udr
               JOIN transaccion_juego tj on udr.transjuego_id = tj.transjuego_id
               JOIN producto_mandante pm ON pm.prodmandante_id = tj.producto_id
               JOIN producto p ON p.producto_id = pm.producto_id
               JOIN proveedor p2 on p.proveedor_id = p2.proveedor_id
               JOIN usuario_mandante um ON tj.usuario_id = um.usumandante_id
               JOIN usuario u ON um.usuario_mandante = u.usuario_id
      WHERE 1 = 1
        AND u.mandante = '18'
        AND udr.fecha_crea BETWEEN '$dateFrom' AND '$dateTo'
        
      GROUP BY udr.transjuego_id) x";


$TransjuegoLog = new TransjuegoLog();

$TransjuegoLogMySqlDAO = new \Backend\mysql\TransjuegoLogMySqlDAO();
$transaccion = $TransjuegoLogMySqlDAO->getTransaction();
$count = $TransjuegoLog->execQuery($transaccion, $sql);


$sql =
    "
    SELECT GameDate,
       PlayerID,
       GameID,
       Platform,
       ROUND(SUM(RealBetAmount), 2)  AS RealBetAmount,
       ROUND(SUM(RealWinAmount), 2)  AS RealWinAmount,
       ROUND(SUM(BonusBetAmount), 2) AS BonusBetAmount,
       ROUND(SUM(BonusWinAmount), 2) AS BonusWinAmount,
       ROUND(SUM(RealBetAmount) + SUM(BonusBetAmount) - SUM(RealWinAmount) - SUM(BonusWinAmount),
             2)                      AS NetGamingRevenue,
       SUM(NumberofRealBets)         AS NumberofRealBets,
       SUM(NumberofBonusBets)        AS NumberofBonusBets,
       SUM(NumberofSessions)         AS NumberofSessions,
       SUM(NumberofRealWins)         AS NumberofRealWins,
       SUM(NumberofBonusWins)        AS NumberofBonusWins
FROM (SELECT udr.fecha_crea                                                                  AS GameDate,
             u.usuario_id                                                                    AS PlayerID,
             p.producto_id                                                                   AS GameID,
             'Mobile'                                                                           AS Platform,
             udr.transjuego_id                                                               AS Transjuego_ID,
             ROUND(SUM(CASE
                           WHEN udr.tipo LIKE 'DEBIT%' AND tj.tipo NOT LIKE '%FREESPIN'
                               THEN udr.valor
                           ELSE 0 END), 2)                                                   AS RealBetAmount,
             ROUND(SUM(CASE
                           WHEN (udr.tipo LIKE 'CREDIT%' OR udr.tipo LIKE '%ROLLBACK%'
                                    ) AND tj.tipo NOT LIKE '%FREESPIN'
                               THEN udr.valor
                           ELSE 0 END), 2)                                                   AS RealWinAmount,
             ROUND(SUM(udr.saldo_free), 2)                                                   AS BonusBetAmount,
             ROUND(SUM(CASE
                           WHEN (udr.tipo LIKE 'CREDIT%' OR udr.tipo LIKE '%ROLLBACK%'
                                    ) AND tj.tipo LIKE '%FREESPIN'
                               THEN udr.valor
                           ELSE 0 END), 2)                                                   AS BonusWinAmount,
             SUM(CASE
                     WHEN udr.tipo LIKE 'DEBIT%'
                         THEN 1
                     ELSE 0 END)                                                             AS NumberofRealBets,
             SUM(CASE WHEN udr.tipo LIKE 'DEBIT%' AND udr.saldo_free != 0 THEN 1 ELSE 0 END) AS NumberofBonusBets,
             0                                                                               AS NumberofSessions,
             SUM(CASE
                     WHEN (udr.tipo LIKE 'CREDIT%' OR udr.tipo LIKE '%ROLLBACK%') AND
                          tj.tipo NOT LIKE '%FREESPIN' THEN 1
                     ELSE 0 END)                                                             AS NumberofRealWins,
             SUM(CASE
                     WHEN tj.tipo LIKE '%FREESPIN' AND udr.tipo NOT LIKE 'DEBIT%' THEN 1
                     ELSE 0 END)                                                             AS NumberofBonusWins

      FROM transjuego_log udr
               JOIN transaccion_juego tj on udr.transjuego_id = tj.transjuego_id
               JOIN producto_mandante pm ON pm.prodmandante_id = tj.producto_id
               JOIN producto p ON p.producto_id = pm.producto_id
               JOIN proveedor p2 on p.proveedor_id = p2.proveedor_id
               JOIN usuario_mandante um ON tj.usuario_id = um.usumandante_id
               JOIN usuario u ON um.usuario_mandante = u.usuario_id
      WHERE 1 = 1
         AND u.mandante = '18'
        AND udr.fecha_crea BETWEEN '$dateFrom' AND '$dateTo'
        
      GROUP BY udr.transjuego_id) x
GROUP BY Transjuego_ID";


$TransjuegoLog = new TransjuegoLog();

$TransjuegoLogMySqlDAO = new \Backend\mysql\TransjuegoLogMySqlDAO();
$transaccion = $TransjuegoLogMySqlDAO->getTransaction();
$apuestas = $TransjuegoLog->execQuery($transaccion, $sql);


$apuestasData = array();
$apuestas = json_decode(json_encode($apuestas), true);


foreach ($apuestas as $key => $value) {
    $array = array();
    $array["GameDate"] = $value["x.GameDate"];
    $array["PlayerID"] = $value["x.PlayerID"];
    $array["GameID"] = $value["x.GameID"];
    $array["Platform"] = $value["x.Platform"];
    $array["RealBetAmount"] = round($value[".RealBetAmount"], 2);
    $array["RealWinAmount"] = round($value[".RealWinAmount"], 2);
    $array["BonusBetAmount"] = round($value[".BonusBetAmount"], 2);
    $array["BonusWinAmount"] = round($value[".BonusWinAmount"], 2);
    $array["NetGamingRevenue"] = round($value[".NetGamingRevenue"], 2);
    $array["NumberofRealBets"] = intval($value[".NumberofRealBets"]);
    $array["NumberofBonusBets"] = intval($value[".NumberofBonusBets"]);
    $array["NumberofSessions"] = intval($value[".NumberofSessions"]);
    $array["NumberofRealWins"] = intval($value[".NumberofRealWins"]);
    $array["NumberofBonusWins"] = intval($value[".NumberofBonusWins"]);


    array_push($apuestasData, $array);
}

if ($count[0][".count"] != 0) {
    $response = array();
    $response["Error"] = false;
    $response["Mensaje"] = "success";
    $response["TotalCount"] = $count[0][".count"];
    $response["Data"] = $apuestasData;
} else {
    $response["Error"] = false;
    $response["Mensaje"] = "No hay usuarios en este rango de fechas";
    $response["TotalCount"] = 0;
    $response["Data"] = [];
}




