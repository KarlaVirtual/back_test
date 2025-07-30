<?php

/**
 * Archivo: Transactions.php
 *
 * Este archivo contiene la lógica para procesar transacciones relacionadas con usuarios,
 * incluyendo consultas a la base de datos para obtener recargas y retiros, así como la
 * generación de respuestas en formato JSON.
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
 * @var mixed $headers                  Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $dateFrom                 Variable que representa una fecha de inicio en un rango de fechas.
 * @var mixed $dateTo                   Variable que representa una fecha de finalización en un rango de fechas.
 * @var mixed $partner                  Variable que almacena información sobre un socio o afiliado.
 * @var mixed $paisId                   Variable que almacena el identificador único de un país.
 * @var mixed $MaxRows                  Variable que define el número máximo de registros a retornar en una consulta.
 * @var mixed $OrderedItem              Variable que representa un elemento ordenado en una lista.
 * @var mixed $SkeepRows                Variable que indica el número de registros a omitir en una consulta.
 * @var mixed $sql                      Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $UsuarioHistorial         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioHistorialMySqlDAO Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $transaccion              Variable que almacena datos relacionados con una transacción.
 * @var mixed $count                    Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $movimientos              Variable que almacena una lista de movimientos financieros.
 * @var mixed $movimientosData          Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
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

header('Content-Type: application/json');
$headers = getallheaders();
$ConfigurationEnvironment = new ConfigurationEnvironment();

$dateFrom = $params->fromDate;
$dateTo = $params->toDate;

$partner = $params->partner;
$paisId = $params->paisId;
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;


$sql =
    "SELECT count(*) count
FROM usuario_recarga ur
         JOIN usuario u ON (ur.usuario_id = u.usuario_id)
         JOIN usuario_perfil up ON up.usuario_id = u.usuario_id
WHERE 1 = 1
     AND u.mandante = '18'
  AND up.perfil_id = 'USUONLINE'
  AND ur.fecha_crea BETWEEN '$dateFrom' AND '$dateTo'

";


$UsuarioHistorial = new UsuarioHistorial();
$UsuarioHistorialMySqlDAO = new \Backend\mysql\UsuarioHistorialMySqlDAO();
$transaccion = $UsuarioHistorialMySqlDAO->getTransaction();

$count = $UsuarioHistorial->execQuery($transaccion, $sql);

$count = json_decode(json_encode($count), true);

$sql =
    "
            SELECT CONCAT('R',cc.cuenta_id)                      AS TransactionID,
       u.usuario_id                      AS PlayerID,
       cc.fecha_crea                     AS TransactionDate,
       'Retiro'                          AS Transaction_Type,
       cc.valor                          AS TransactionAmount,
       CASE
           WHEN ( cc.estado ='R') THEN 'Rechazado'
           WHEN ( cc.estado ='I') THEN 'Pagado'
           WHEN ( cc.estado ='P') THEN 'Pendiente'
           WHEN ( cc.estado ='A') THEN 'Pendiente'
           WHEN ( cc.estado ='E') THEN 'Eliminado'
           ELSE 'Aprobado' END             AS Status,
       'Mobile'                             AS Platform,
       CASE
           WHEN (cc.estado ='R') THEN cc.fecha_accion
           WHEN (cc.estado ='E') THEN cc.fecha_accion
           WHEN (cc.estado ='I') THEN cc.fecha_pago
           ELSE cc.fecha_crea END AS LastUpdated
FROM cuenta_cobro cc
         JOIN usuario u ON (cc.usuario_id = u.usuario_id)
         JOIN usuario_perfil up ON up.usuario_id = u.usuario_id
WHERE 1 = 1
  AND up.perfil_id = 'USUONLINE'
   AND (cc.fecha_crea BETWEEN '$dateFrom' AND '$dateTo' OR
       cc.fecha_eliminacion BETWEEN '$dateFrom' AND '$dateTo' OR
       cc.fecha_accion BETWEEN '$dateFrom' AND '$dateTo' OR
       cc.fecha_pago BETWEEN '$dateFrom' AND '$dateTo')
UNION
SELECT CONCAT('D',ur.recarga_id)          AS TransactionID,
       u.usuario_id           AS PlayerID,
       ur.fecha_crea          AS TransactionDate,
       'Recarga'               AS Transaction_Type,
       ur.valor               AS TransactionAmount,
       CASE
           WHEN ur.fecha_elimina IS NOT NULL THEN 'Eliminado'
           ELSE 'Aprobado' END AS Status,
       'Mobile'                  AS Platform,
       CASE
           WHEN (ur.fecha_elimina IS NOT NULL) THEN ur.fecha_elimina
           ELSE ur.fecha_crea END  AS LastUpdated
FROM usuario_recarga ur
         JOIN usuario u ON (ur.usuario_id = u.usuario_id)
         JOIN usuario_perfil up ON up.usuario_id = u.usuario_id
WHERE 1 = 1
   AND u.mandante = '18'
  AND up.perfil_id = 'USUONLINE'
  AND ur.fecha_crea BETWEEN '$dateFrom' AND '$dateTo'
";


$UsuarioHistorial = new UsuarioHistorial();
$UsuarioHistorialMySqlDAO = new \Backend\mysql\UsuarioHistorialMySqlDAO();
$transaccion = $UsuarioHistorialMySqlDAO->getTransaction();

$movimientos = $UsuarioHistorial->execQuery($transaccion, $sql);

$movimientosData = array();
$movimientos = json_decode(json_encode($movimientos), true);
foreach ($movimientos as $key => $value) {
    $array = array();
    $array["TransactionID"] = $value[".TransactionID"];
    $array["PlayerID"] = $value[".PlayerID"];
    $array["TransactionDate"] = $value[".TransactionDate"];
    $array["Transaction_Type"] = $value[".Transaction_Type"];
    $array["TransactionAmount"] = round($value[".TransactionAmount"], 2);
    $array["Status"] = $value[".Status"];
    $array["Platform"] = $value[".Platform"];
    $array["LastUpdated"] = $value[".LastUpdated"];

    array_push($movimientosData, $array);
}

if ($count[0][".count"] != 0) {
    $response = array();
    $response["Error"] = false;
    $response["Mensaje"] = "success";
    $response["TotalCount"] = $count[0][".count"];
    $response["Data"] = $movimientosData;
} else {
    $response["Error"] = false;
    $response["Mensaje"] = "No hay transacciones en este rango de fechas";
    $response["TotalCount"] = 0;
    $response["Data"] = [];
}


