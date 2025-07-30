<?php
use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\Helpers;
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
use Backend\dto\UsuarioNota;
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
 * Obtiene el ingreso consolidado por propietario para un período específico
 *
 * Este archivo procesa y consolida información financiera relacionada con:
 * - Transacciones de juego
 * - Bonos de casino
 * - Premios y devoluciones
 * - Comisiones y derechos de inscripción
 *
 * La información se agrupa por fecha y puede filtrarse por:
 * - Rango de fechas
 * - ID de jugador
 * - País
 * - Mandante
 * - Número de documento
 *
 * Estructura del array response:
 * {
 *   "pos": int,                    // Posición inicial de la paginación
 *   "total_count": int,            // Total de registros encontrados
 *   "data": [                      // Array de datos consolidados
 *     {
 *       "Date": string,            // Fecha de la transacción
 *       "TotalBets": float,        // Total de apuestas
 *       "TotalAward": float,       // Total de premios
 *       "TotalBonus": float,       // Total de bonos
 *       "TotalProgressiveSystemPrizes": float,  // Total de premios progresivos
 *       "TotalReturns": float,     // Total de devoluciones
 *       "TotalServiceFees": float, // Total de comisiones
 *       "RegistrationFees": float  // Total de derechos de inscripción
 *     }
 *   ]
 * }
 *
 * @subpackage Financial
 * @version 1.0
 */

/* captura datos de una solicitud y sesión de usuario para procesarlos. */
$Helpers = new Helpers;
$date_from =$_REQUEST['dateFrom'];
$date_to = $_REQUEST['dateTo'];
$doc_number = $_REQUEST['DocumentNumber'];
$mandante = $_SESSION['mandante'];
$user_id = $_REQUEST['PlayerId'];
$pais_id = $_SESSION['PaisCondS'];
$Country = $_REQUEST['CountrySelect'];

$SkeepRows = $_REQUEST["start"];
$MaxRows = $_REQUEST["count"];

$fdate = '';
$fuser_id = '';
if ($SkeepRows != '' && $SkeepRows != null && $MaxRows!= '' && $MaxRows != null) {

/* obtiene y formatea fechas desde y hasta, usando valores de una solicitud. */
if ($_REQUEST["dateFrom"] != "") {
        $date_from = date("Y-m-d 00:00:00", strtotime($date_from));
    }else{
        $date_from = date("Y-m-d 00:00:00");
    }
    if ($_REQUEST["dateTo"] != "") {
        $date_to = date("Y-m-d 23:59:59", strtotime($date_to));
    }else{
        $date_to = date("Y-m-d 23:59:59");
    }

/* Filtra resultados por fechas y usuario si se proporcionan parámetros válidos. */
if ($date_from != '' and $date_from != null &&  $date_to != '' and $date_to != null){
        $fdate ="AND tj.fecha_crea BETWEEN '$date_from' AND '$date_to'";
        $fdate2 ="AND bl.fecha_crea BETWEEN '$date_from' AND '$date_to'";
    }

    if ($user_id != '' && $user_id != null){
        $fuser_id =" AND u.usuario_id =  '$user_id' " ;
    }

/* Condicionales para filtrar resultados según mandante y país en una consulta SQL. */
if ($mandante != '' && $mandante != null){
        $fmandante =  "AND u.mandante = '$mandante'";
    }

    if ($Country != '' && $Country != null){
        $fpais = " AND u.pais_id = '$Country' ";
    }else{

/* Condicional que verifica el país y agrega un filtro a la consulta SQL. */
        if($pais_id != '' && $pais_id != null){
            $fpais = " AND u.pais_id = '$pais_id' ";
        }
    }


/* verifica el número de documento y consulta una transacción correspondiente. */
if ($doc_number != '' and $doc_number != null){
        $field2 = $Helpers->set_custom_field('r.cedula');
        $fdoc_number = "AND $field2 = '$doc_number'";
    }

    $BonointernoMySqlDAO = new BonoInternoMySqlDAO();
    $transaccion = $BonointernoMySqlDAO->getTransaction();
    if (date("Y-m-d",strtotime($date_from)) >= date("Y-m-d") ){
        $sql = "select Fecha,
                   Partner,
                   Pais,
                   sum(total_apuesta)       as total_apuesta,
                   sum(total_premios)       as total_premios,
                   sum(premios_bonos)       as premios_bonos,
                   sum(bonos_casino)        as bonos_casino,
                   sum(premios_progesivios) as premios_progesivios,
                   sum(total_devoluciones)  as total_devoluciones,
                   sum(total_comision)      as total_comision,
                   sum(derecho_inscripcion) as derecho_inscripcion
                from (SELECT date(tj.fecha_crea)           as Fecha,
                         m.nombre                      AS Partner,
                         p.pais_nom                    AS Pais,
                         ROUND(SUM(CASE
                                       WHEN tj.tipo != 'FREESPIN' AND tjl.tipo LIKE '%DEBIT%' THEN tjl.valor
                                       ELSE 0 END), 2) AS Total_Apuesta,
                         ROUND(SUM(CASE
                                       WHEN tj.tipo != 'FREESPIN' AND (tjl.tipo LIKE '%CREDIT%' OR tjl.tipo LIKE '%ROLLBACK%')
                                           THEN tjl.valor
                                       ELSE 0 END), 2) AS Total_premios,
                         ROUND(SUM(CASE
                                       WHEN tj.tipo = 'FREESPIN' AND (tjl.tipo LIKE '%CREDIT%' OR tjl.tipo LIKE '%ROLLBACK%')
                                           THEN tjl.valor
                                       ELSE 0 END), 2) AS Premios_Bonos,
                         0                             as Bonos_casino,
                         0                             as Premios_progesivios,
                         ROUND(SUM(CASE
                                       WHEN tjl.tipo LIKE '%ROLLBACK%'
                                           THEN tjl.valor
                                       ELSE 0 END), 2) AS Total_Devoluciones,
                         0                             as Total_Comision,
                         0                             as Derecho_inscripcion
                  FROM transaccion_juego tj
                           JOIN transjuego_log tjl on tj.transjuego_id = tjl.transjuego_id
                           JOIN usuario_mandante um on um.usumandante_id = tj.usuario_id
                           JOIN usuario u on um.usuario_mandante = u.usuario_id
                           JOIN mandante m ON u.mandante = m.mandante
                           JOIN pais p ON u.pais_id = p.pais_id
                  WHERE 1 = 1
                    {$fdate}
                    {$fuser_id}
                    {$fmandante}
                    {$fpais}
                    {$fdoc_number}
                  group by date(tj.fecha_crea)
                  Union
                  SELECT date(bl.fecha_crea)           as Fecha,
                         m.nombre                      AS Partner,
                         pais.pais_nom                 AS Pais,
                         0                             AS Total_Apuesta,
                         0                             AS Total_premios,
                         0                             AS Premios_Bonos,
                         ROUND(SUM(CASE
                                       WHEN (bl.estado = 'L' AND (tipo IN ('TC', 'SC', 'SCV', 'TL'))) THEN valor
                                       WHEN (bl.estado = 'E' AND (tipo IN ('TC', 'SC', 'SCV'))) THEN -valor
                                       ELSE 0 END), 2) AS Bonos_Casino,
                         0                             as Premios_progesivios,
                         0                             AS Total_Devoluciones,
                         0                             as Total_Comision,
                         0                             as Derecho_inscripcion
                  FROM bono_log bl
                           JOIN usuario u ON bl.usuario_id = u.usuario_id
                           JOIN usuario_mandante um ON um.usuario_mandante = u.usuario_id
                           JOIN mandante m ON u.mandante = m.mandante
                           JOIN pais pais ON u.pais_id = pais.pais_id
                  WHERE 1 = 1
                    {$fdate2}
                    {$fuser_id}
                    {$fmandante}
                    {$fpais}
                    {$fdoc_number}
                  group by date(bl.fecha_crea)) x
                group by Fecha
                LIMIT {$SkeepRows},{$MaxRows}; 
        ";

        $sql_count = "select sum(Fila) AS Fila
            from (
                     SELECT
                         case when count(*) > 1 then 1 else 1 end as Fila
            from (SELECT date(tj.fecha_crea)           as Fecha,
                         m.nombre                      AS Partner,
                         p.pais_nom                    AS Pais,
                         ROUND(SUM(CASE
                                       WHEN tj.tipo != 'FREESPIN' AND tjl.tipo LIKE 'DEBIT%' THEN tjl.valor
                                       ELSE 0 END) +
                               SUM(CASE
                                       WHEN tj.tipo = 'FREECASH' AND tjl.tipo LIKE 'DEBIT%' THEN tjl.saldo_free
                                       ELSE 0 END)
                             , 2)                                                   AS Total_Apuesta,
                         ROUND(SUM(CASE
                                       WHEN tj.tipo != 'FREESPIN' AND (tjl.tipo LIKE 'CREDIT%' OR tjl.tipo LIKE 'ROLLBACK%')
                                           THEN tjl.valor
                                       ELSE 0 END), 2)                              AS Total_premios,
                         ROUND(SUM(CASE
                                       WHEN tj.tipo = 'FREESPIN' AND (tjl.tipo LIKE '%CREDIT%' OR tjl.tipo LIKE '%ROLLBACK%')
                                           THEN tjl.valor
                                       ELSE 0 END), 2) AS Premios_Bonos,
                         0                             as Bonos_casino,
                         0                             as Premios_progesivios,
                         ROUND(SUM(CASE
                                       WHEN tjl.tipo LIKE '%ROLLBACK%'
                                           THEN tjl.valor
                                       ELSE 0 END), 2) AS Total_Devoluciones,
                         0                             as Total_Comision,
                         0                             as Derecho_inscripcion
                  FROM transaccion_juego tj
                           JOIN transjuego_log tjl on tj.transjuego_id = tjl.transjuego_id
                           JOIN usuario_mandante um on um.usumandante_id = tj.usuario_id
                           JOIN usuario u on um.usuario_mandante = u.usuario_id
                           JOIN mandante m ON u.mandante = m.mandante
                           JOIN pais p ON u.pais_id = p.pais_id
                  WHERE 1 = 1
                    {$fdate}
                    {$fuser_id}
                    {$fmandante}
                    {$fpais}
                    {$fdoc_number}
                  group by date(tj.fecha_crea)
                  Union
                  SELECT date(bl.fecha_crea)           as Fecha,
                         m.nombre                      AS Partner,
                         pais.pais_nom                 AS Pais,
                         0                             AS Total_Apuesta,
                         0                             AS Total_premios,
                         0                             AS Premios_Bonos,
                         ROUND(SUM(CASE
                                       WHEN (bl.estado = 'L' AND (tipo IN ('TC', 'SC', 'SCV', 'TL'))) THEN valor
                                       WHEN (bl.estado = 'E' AND (tipo IN ('TC', 'SC', 'SCV'))) THEN -valor
                                       ELSE 0 END), 2) AS Bonos_Casino,
                         0                             as Premios_progesivios,
                         0                             AS Total_Devoluciones,
                         0                             as Total_Comision,
                         0                             as Derecho_inscripcion
                  FROM bono_log bl
                           JOIN usuario u ON bl.usuario_id = u.usuario_id
                           JOIN usuario_mandante um ON um.usuario_mandante = u.usuario_id
                           JOIN mandante m ON u.mandante = m.mandante
                           JOIN pais pais ON u.pais_id = pais.pais_id
                  WHERE 1 = 1
                    {$fdate2}
                    {$fuser_id}
                    {$fmandante}
                    {$fpais}
                    {$fdoc_number}
                  group by date(bl.fecha_crea)) x
            group by Fecha)XX
        ";
    }else{

        /* Convierte dos fechas a formato "Año-Mes-Día" usando strtotime y date. */
        $date_from = date("Y-m-d",strtotime($date_from));
        $date_to = date("Y-m-d",strtotime($date_to));
        $sql = "
                select Fecha,
           Partner,
           Pais,
           sum(total_apuesta)       as total_apuesta,
           sum(total_premios)       as total_premios,
           sum(premios_bonos)       as premios_bonos,
           sum(bonos_casino)        as bonos_casino,
           sum(premios_progesivios) as premios_progesivios,
           sum(total_devoluciones)  as total_devoluciones,
           sum(total_comision)      as total_comision,
           sum(derecho_inscripcion) as derecho_inscripcion
            FROM
                (select date(ucr.fecha_crea)          as Fecha,
                        m.nombre                      AS Partner,
                        ps.pais_nom                   AS Pais,
                        ROUND(SUM(CASE
                                      WHEN ucr.tipo LIKE 'DEBIT%' THEN ucr.valor
                                      ELSE 0 END)
                            , 2)                      AS Total_Apuesta,
                        ROUND(SUM(CASE
                                      WHEN ucr.tipo LIKE 'CREDIT%' AND ucr.tipo != 'CREDITFREESPIN' AND ucr.tipo != 'ROLLBACKFREESPIN' THEN ucr.valor
                                      ELSE 0 END), 2) AS Total_premios,
                        ROUND(SUM(CASE
                                      WHEN ucr.tipo = 'CREDITFREESPIN' or ucr.tipo = 'ROLLBACKFREESPIN'
                                          THEN ucr.valor_premios
                                      ELSE 0 END), 2) AS Premios_Bonos,
                        0                             as Bonos_casino,
                        0                             as Premios_progesivios,
                        ROUND(SUM(CASE
                                      WHEN ucr.tipo LIKE '%ROLLBACK%'
                                          THEN ucr.valor
                                      ELSE 0 END), 2) AS Total_Devoluciones,
                        0                             as Total_Comision,
                        0                             as Derecho_inscripcion
                 from usucasino_detalle_resumen ucr
                          INNER JOIN usuario_mandante um ON ucr.usuario_id = um.usumandante_id
                          INNER JOIN usuario u ON um.usuario_mandante = u.usuario_id
                          INNER JOIN mandante m ON u.mandante = m.mandante
                          INNER JOIN pais ps ON u.pais_id = ps.pais_id
                 WHERE 1 = 1
                   AND ucr.fecha_crea BETWEEN CONCAT('{$date_from}', ' 00:00:00') AND CONCAT('{$date_to}', ' 23:59:59')
                    {$fuser_id}
                    {$fmandante}
                    {$fpais}
                    {$fdoc_number}
                 group by DATE(ucr.fecha_crea)
                 Union
                 SELECT date(bl.fecha_crea)           as Fecha,
                        m.nombre                      AS Partner,
                        pais.pais_nom                 AS Pais,
                        0                             AS Total_Apuesta,
                        0                             AS Total_premios,
                        0                             AS Premios_Bonos,
                        ROUND(SUM(CASE
                                      WHEN (bl.estado = 'L' AND (tipo IN ('TC', 'SC', 'SCV', 'TL'))) THEN valor
                                      WHEN (bl.estado = 'E' AND (tipo IN ('TC', 'SC', 'SCV'))) THEN -valor
                                      ELSE 0 END), 2) AS Bonos_Casino,
                        0                             as Premios_progesivios,
                        0                             AS Total_Devoluciones,
                        0                             as Total_Comision,
                        0                             as Derecho_inscripcion
                 FROM bono_log bl
                          JOIN usuario u ON bl.usuario_id = u.usuario_id
                          JOIN usuario_mandante um ON um.usuario_mandante = u.usuario_id
                          JOIN mandante m ON u.mandante = m.mandante
                          JOIN pais pais ON u.pais_id = pais.pais_id
                 WHERE 1 = 1
                   AND bl.fecha_crea BETWEEN CONCAT('{$date_from}', ' 00:00:00') AND CONCAT('{$date_to}', ' 23:59:59')
                    {$fuser_id}
                    {$fmandante}
                    {$fpais}
                    {$fdoc_number}
                 group by date(bl.fecha_crea))x
            group by Fecha
                 LIMIT {$SkeepRows},{$MaxRows};
        ";
        $sql_count = "select sum(Fila) AS Fila
                from (
                     SELECT
                         case when count(*) > 1 then 1 else 1 end as Fila
                     FROM
                         (select date(ucr.fecha_crea)          as Fecha,
                                 m.nombre                      AS Partner,
                                 ps.pais_nom                   AS Pais,
                                 ROUND(SUM(CASE
                                               WHEN ucr.tipo = 'DEBIT' THEN ucr.valor
                                               ELSE 0 END) +
                                       SUM(CASE
                                               WHEN ucr.tipo = 'DEBITFREECASH' THEN ucr.valor_premios + ucr.valor
                                               ELSE 0 END)
                                     , 2)                      AS Total_Apuesta,
                                 ROUND(SUM(CASE
                                               WHEN ucr.tipo = 'CREDIT' or ucr.tipo = 'ROLLBACK' or ucr.tipo = 'CREDITFREECASH'
                                                   THEN ucr.valor_premios
                                               ELSE 0 END), 2) AS Total_premios,
                                 ROUND(SUM(CASE
                                               WHEN ucr.tipo = 'CREDITFREESPIN' or ucr.tipo = 'ROLLBACKFREESPIN'
                                                   THEN ucr.valor_premios
                                               ELSE 0 END), 2) AS Premios_Bonos,
                                 0                             as Bonos_casino,
                                 0                             as Premios_progesivios,
                                 ROUND(SUM(CASE
                                               WHEN ucr.tipo LIKE '%ROLLBACK%'
                                                   THEN ucr.valor
                                               ELSE 0 END), 2) AS Total_Devoluciones,
                                 0                             as Total_Comision,
                                 0                             as Derecho_inscripcion
                          from usucasino_detalle_resumen ucr
                                   INNER JOIN usuario_mandante um ON ucr.usuario_id = um.usumandante_id
                                   INNER JOIN usuario u ON um.usuario_mandante = u.usuario_id
                                   INNER JOIN mandante m ON u.mandante = m.mandante
                       INNER JOIN pais ps ON u.pais_id = ps.pais_id
              WHERE 1 = 1
                AND ucr.fecha_crea BETWEEN CONCAT('{$date_from}', ' 00:00:00') AND CONCAT('{$date_to}', ' 23:59:59')
                    {$fuser_id}
                    {$fmandante}
                    {$fpais}
                    {$fdoc_number}
              group by DATE(ucr.fecha_crea)
              Union
              SELECT date(bl.fecha_crea)           as Fecha,
                     m.nombre                      AS Partner,
                     pais.pais_nom                 AS Pais,
                     0                             AS Total_Apuesta,
                     0                             AS Total_premios,
                     0                             AS Premios_Bonos,
                     ROUND(SUM(CASE
                                   WHEN (bl.estado = 'L' AND (tipo IN ('TC', 'SC', 'SCV', 'TL'))) THEN valor
                                   WHEN (bl.estado = 'E' AND (tipo IN ('TC', 'SC', 'SCV'))) THEN -valor
                                   ELSE 0 END), 2) AS Bonos_Casino,
                     0                             as Premios_progesivios,
                     0                             AS Total_Devoluciones,
                     0                             as Total_Comision,
                     0                             as Derecho_inscripcion
              FROM bono_log bl
                       JOIN usuario u ON bl.usuario_id = u.usuario_id
                       JOIN usuario_mandante um ON um.usuario_mandante = u.usuario_id
                       JOIN mandante m ON u.mandante = m.mandante
                       JOIN pais pais ON u.pais_id = pais.pais_id
              WHERE 1 = 1
                AND bl.fecha_crea BETWEEN CONCAT('{$date_from}', ' 00:00:00') AND CONCAT('{$date_to}', ' 23:59:59')
                    {$fuser_id}
                    {$fmandante}
                    {$fpais}
                    {$fdoc_number}
              group by date(bl.fecha_crea))x
         group by Fecha)XX;
        ";
    }

/* imprime variables SQL si vienen los parámetros de debug */
if ($_REQUEST['DXbDpfykzqwS'] =='Q43c69XSqL'){
        print_r($sql);
    }
    if ($_REQUEST['DXbDpfykzqwS'] =='Q43c69XSqL'){
        print_r($sql_count);
        die();
    }

/* Se ejecutan consultas SQL con la clase BonoInterno para obtener datos y conteo. */
    $Bonointerno = new BonoInterno();
    $data = $Bonointerno->execQuery($transaccion,$sql);

    $Bonointerno = new BonoInterno();
    $count =$Bonointerno->execQuery($transaccion,$sql_count);

    $dataFinal = [];

/* procesa datos, creando un nuevo arreglo con información específica de apuestas. */
foreach ($data as $value){

        $array = [];
        $array["Date"] =$value->{"x.Fecha"};
        $array["TotalBets"] = $value->{".total_apuesta"};
        $array["TotalAward"] = $value->{".total_premios"};
        $array["TotalBonus"] = $value->{".premios_bonos"};
        $array["TotalProgressiveSystemPrizes"] = $value->{".premios_progesivios"};
        $array["TotalReturns"] = $value->{".total_devoluciones"};
        $array["TotalServiceFees"] = $value->{".total_comision"};
        $array["RegistrationFees"] = $value->{".derecho_inscripcion"};

        array_push($dataFinal, $array);
    }


/* asigna información a un array de respuesta relacionado con una consulta. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $count[0]->{".Fila"};
    $response["data"] = $dataFinal;
}else{
/* maneja un error, estableciendo un mensaje de alerta y estado. */

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Invalid";
    $response["ModelErrors"] = [];
}




