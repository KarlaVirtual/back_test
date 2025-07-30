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
use Backend\dto\ProdMandantetipo;
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
use Backend\mysql\ProdMandantetipoMySqlDAO;
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
 *
 * @param string $dateFrom : Descripción: Fecha de inicio para el reporte de rendimiento de juegos.
 * @param string $dateTo : Descripción: Fecha de fin para el reporte de rendimiento de juegos.
 * @param string $DocumentNumber : Descripción: Número de documento del jugador.
 * @param string $DocumentType : Descripción: Tipo de documento del jugador.
 * @param string $PlayerName : Descripción: Nombre del jugador.
 * @param string $PlayerId : Descripción: Identificador del jugador.
 * @param int $start : Descripción: Número de filas a omitir en la consulta.
 * @param int $count : Descripción: Número máximo de filas a devolver.
 * @param int $Id : Descripción: Identificador del producto mandante.
 * @param int $CountrySelect : Descripción: Identificador del país seleccionado.
 * @param string $GameName : Descripción: Nombre del juego.
 *
 * @Description Obtener el rendimiento de los juegos.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos del rendimiento de los juegos.
 *
 * Objeto en caso de error:
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'danger';
 * $response['AlertMessage'] = 'Invalid';
 * $response['ModelErrors'] = [];
 *
 * @throws Exception Permiso denegado
 * @throws Exception Inusual Detectado
 *
 */

/* captura datos de entrada y sesión para procesar información específica. */
$Helpers = new Helpers;
$date_from =$_REQUEST['dateFrom'];
$date_to = $_REQUEST['dateTo'];
$doc_number = $_REQUEST['DocumentNumber'];
$mandante = $_SESSION['mandante'];
$id = $_REQUEST['Id'];
$user_id = $_REQUEST['PlayerId'];

/* Código que recupera información de país y juego desde la sesión y solicitud. */
$pais_id = $_SESSION['PaisCondS'];
$Country = $_REQUEST['CountrySelect'];
$game_name = $_REQUEST['GameName'];

$SkeepRows = $_REQUEST["start"];
$MaxRows = $_REQUEST["count"];


/* Variables vacías inicializadas para almacenar fecha y ID de usuario. */
$fdate = '';
$fuser_id = '';
if ($SkeepRows != '' && $SkeepRows != null && $MaxRows != '' && $MaxRows != null) {

    /* establece fechas inicial y final, formateándolas según input recibido. */
    if ($_REQUEST["dateFrom"] != "") {
        $date_from = date("Y-m-d 00:00:00", strtotime($date_from));
    } else {
        $date_from = date("Y-m-d 00:00:00");
    }
    if ($_REQUEST["dateTo"] != "") {
        $date_to = date("Y-m-d 23:59:59", strtotime($date_to));
    } else {
        /* Asignación de la fecha actual al final del día si la condición no se cumple. */

        $date_to = date("Y-m-d 23:59:59");
    }


    /* Filtra resultados entre fechas y por ID de usuario solo si están definidos. */
    if ($date_from != '' and $date_from != null && $date_to != '' and $date_to != null) {
        $fdate = "AND tj.fecha_crea BETWEEN '$date_from' AND '$date_to'";
    }

    if ($user_id != '' && $user_id != null) {
        $fuser_id = " AND u.usuario_id =  '$user_id' ";
    }


    /* Condiciones para agregar filtros SQL basados en variables `$mandante` e `$id`. */
    if ($mandante != '' && $mandante != null) {
        $fmandante = "AND u.mandante = '$mandante'";
    }

    if ($id != '' && $id != null) {
        $fProdmandanteId = "AND pm.prodmandante_id = '$id'";
    }


    /* asigna un filtro basado en el país si está definido. */
    if ($Country != '' && $Country != null) {
        $fpais = " AND u.pais_id = '$Country' ";
    } else {
        if ($pais_id != '' && $pais_id != null) {
            $fpais = " AND u.pais_id = '$pais_id' ";
        }
    }

    /* Condiciones para filtrar resultados en una consulta SQL basada en documentos y juegos. */
    if ($doc_number != '' and $doc_number != null){
        $field2 = $Helpers->set_custom_field('r.cedula');
        $fdoc_number = "AND $field2 = '$doc_number'";

    }

    if ($game_name != null && $game_name != '') {
        $fgame_name = "AND p.descripcion like '%$game_name%'";
    }


    /* Creación de objeto DAO y obtención de una transacción de la base de datos. */
    $BonointernoMySqlDAO = new BonoInternoMySqlDAO();
    $transaccion = $BonointernoMySqlDAO->getTransaction();
    if (date("Y-m-d", strtotime($date_from)) >= date("Y-m-d")) {
        $sql = "select fecha,
               id_juego,
               nombre_Juego,
               tipo,
               porce_retorno_real,
               total_apuesta,
               apuesta_bonificacion,
               total_premios,
               premios_bonificacion,
               total_apuestas_anuladas,
               total_devoluciones
                from (SELECT fecha,
                     id_juego,
                     nombre_Juego,
                     tipo,
                     catmandante,
                     CASE WHEN total_apuesta = 0 THEN 0 ELSE round((((total_premios)/total_apuesta) * 100), 2) END  as porce_retorno_real,
                     total_apuesta,
                     apuesta_bonificacion,
                     total_premios,
                     premios_bonificacion,
                     total_apuestas_anuladas,
                     total_devoluciones
              FROM (SELECT date(tj.fecha_crea)                                        as fecha,
                            pm.prodmandante_id                                              AS id_juego,
                           p.descripcion                                              AS nombre_Juego,
                           cm.descripcion                                             AS tipo,
                           cm.mandante                                                as catmandante,
                           ROUND(SUM(CASE
                                         WHEN tj.tipo != 'FREESPIN' AND tjl.tipo LIKE 'DEBIT%' THEN tjl.valor
                                         ELSE 0 END) +
                                 SUM(CASE
                                         WHEN tj.tipo = 'FREECASH' AND tjl.tipo LIKE 'DEBIT%' THEN tjl.saldo_free
                                         ELSE 0 END)
                               , 2)                                                   AS total_apuesta,
                           ROUND(SUM(CASE
                                         WHEN tj.tipo = 'FREECASH' AND tjl.tipo LIKE 'DEBIT%' THEN tjl.saldo_free
                                         ELSE 0
                               END), 2)                                               as apuesta_bonificacion,
                           ROUND(SUM(CASE
                                         WHEN tj.tipo != 'FREESPIN' AND (tjl.tipo LIKE 'CREDIT%' OR tjl.tipo LIKE 'ROLLBACK%')
                                             THEN tjl.valor
                                         ELSE 0 END), 2)                              AS total_premios,
                           ROUND(SUM(CASE
                                         WHEN tj.tipo = 'FREESPIN' AND (tjl.tipo LIKE 'CREDIT%' OR tjl.tipo LIKE 'ROLLBACK%')
                                             THEN tjl.valor
                                         ELSE 0 END), 2)                              AS premios_bonificacion,
        
                           SUM(CASE WHEN tjl.tipo LIKE 'ROLLBACK%' THEN 1 ELSE 0 END) AS total_apuestas_anuladas,
                           ROUND(SUM(CASE
                                         WHEN tjl.tipo LIKE 'ROLLBACK%' THEN tjl.valor
                                         ELSE 0 END), 2)                              AS total_devoluciones
                    FROM transjuego_log tjl
                             INNER JOIN transaccion_juego tj ON tjl.transjuego_id = tj.transjuego_id
                             INNER JOIN producto_mandante pm ON tj.producto_id = pm.prodmandante_id
                             INNER JOIN producto p ON pm.producto_id = p.producto_id
                             INNER JOIN usuario_mandante um ON tj.usuario_id = um.usumandante_id
                             INNER JOIN usuario u ON um.usuario_mandante = u.usuario_id
                             INNER JOIN categoria_mandante cm on cm.catmandante_id = p.categoria_id
                             INNER JOIN mandante m ON u.mandante = m.mandante
                             INNER JOIN pais ps ON u.pais_id = ps.pais_id
                    WHERE 1 = 1
                        {$fdate}
                        {$fuser_id}
                        {$fmandante}
                        {$fProdmandanteId}
                        {$fpais}
                        {$fdoc_number}
                        {$fgame_name}
                    group by p.descripcion) x
                    where x.catmandante = -1
              LIMIT {$SkeepRows},{$MaxRows}) xx;
        ";
        $sql_count = "select count(*) as Filas
            from (SELECT fecha,
                        id_juego,
                         nombre_Juego,
                         tipo,
                         catmandante,
                         CASE WHEN total_apuesta = 0 THEN 0 ELSE round((((total_premios)/total_apuesta) * 100), 2) END  as porce_retorno_real,
                         total_apuesta,
                         apuesta_bonificacion,
                         total_premios,
                         premios_bonificacion,
                         total_apuestas_anuladas,
                         total_devoluciones
            FROM (SELECT date(tj.fecha_crea)                                        as fecha,
                   pm.prodmandante_id                                              AS id_juego,
                   p.descripcion                                              AS nombre_Juego,
                   cm.descripcion                                             AS tipo,
                   cm.mandante                                                as catmandante,
                   ROUND(SUM(CASE
                                 WHEN tj.tipo != 'FREESPIN' AND tjl.tipo LIKE 'DEBIT%' THEN tjl.valor
                                 ELSE 0 END) +
                         SUM(CASE
                                 WHEN tj.tipo = 'FREECASH' AND tjl.tipo LIKE 'DEBIT%' THEN tjl.saldo_free
                                 ELSE 0 END)
                       , 2)                                                   AS total_apuesta,
                   ROUND(SUM(CASE
                                 WHEN tj.tipo = 'FREECASH' AND tjl.tipo LIKE 'DEBIT%' THEN tjl.saldo_free
                                 ELSE 0
                       END), 2)                                               as apuesta_bonificacion,
                   ROUND(SUM(CASE
                                 WHEN tj.tipo != 'FREESPIN' AND (tjl.tipo LIKE 'CREDIT%' OR tjl.tipo LIKE 'ROLLBACK%')
                                     THEN tjl.valor
                                 ELSE 0 END), 2)                              AS total_premios,
                   ROUND(SUM(CASE
                                 WHEN tj.tipo = 'FREESPIN' AND (tjl.tipo LIKE 'CREDIT%' OR tjl.tipo LIKE 'ROLLBACK%')
                                     THEN tjl.valor
                                 ELSE 0 END), 2)                              AS premios_bonificacion,

                   SUM(CASE WHEN tjl.tipo LIKE 'ROLLBACK%' THEN 1 ELSE 0 END) AS total_apuestas_anuladas,
                   ROUND(SUM(CASE
                                 WHEN tjl.tipo LIKE 'ROLLBACK%' THEN tjl.valor
                                 ELSE 0 END), 2)                              AS total_devoluciones
            FROM transjuego_log tjl
                     INNER JOIN transaccion_juego tj ON tjl.transjuego_id = tj.transjuego_id
                     INNER JOIN producto_mandante pm ON tj.producto_id = pm.prodmandante_id
                     INNER JOIN producto p ON pm.producto_id = p.producto_id
                     INNER JOIN usuario_mandante um ON tj.usuario_id = um.usumandante_id
                     INNER JOIN usuario u ON um.usuario_mandante = u.usuario_id
                     INNER JOIN categoria_mandante cm on cm.catmandante_id = p.categoria_id
                     INNER JOIN mandante m ON u.mandante = m.mandante
                     INNER JOIN pais ps ON u.pais_id = ps.pais_id
            WHERE 1 = 1
                {$fdate}
                {$fuser_id}
                {$fmandante}
                {$fpais}
                {$fdoc_number}
                {$fgame_name}
            group by p.descripcion) x
            where x.catmandante = -1) xx;
        ";
    } else {

        /* Convierte las fechas a formato "AAAA-MM-DD" usando strtotime(). */
        $date_from = date("Y-m-d", strtotime($date_from));
        $date_to = date("Y-m-d", strtotime($date_to));
        $sql = "
                SELECT FECHA,
               ID_JUEGO,
               NOMBRE_JUEGO,
               TIPO,
               round((((TOTAL_APUESTA - TOTAL_PREMIOS - PREMIOS_BONIFICACION) - TOTAL_APUESTA) / 100),
                     2) as PORCE_RETORNO_REAL,
               TOTAL_APUESTA,
               APUESTA_BONIFICACION,
               TOTAL_PREMIOS,
               PREMIOS_BONIFICACION,
               TOTAL_APUESTAS_ANULADAS,
               TOTAL_DEVOLUCIONES
            FROM (select date(ucr.fecha_crea)                                       as fecha,
                     
                   pm.prodmandante_id                                              AS id_juego,
                   p.descripcion                                              AS nombre_Juego,
                     cm.descripcion                                             AS tipo,
                     cm.mandante                                                as catmandante,
                     ROUND(SUM(CASE
                                   WHEN ucr.tipo = 'DEBIT' THEN ucr.valor
                                   ELSE 0 END) +
                           SUM(CASE
                                   WHEN ucr.tipo = 'DEBITFREECASH' THEN ucr.valor_premios + ucr.valor
                                   ELSE 0 END)
                         , 2)                                                   AS total_apuesta,
        
                     ROUND(SUM(CASE
                                   WHEN ucr.tipo = 'DEBITFREECASH' THEN ucr.valor_premios
                                   ELSE 0
                         END), 2)                                               as apuesta_bonificacion,
                     ROUND(SUM(CASE
                                   WHEN ucr.tipo = 'CREDIT' or ucr.tipo = 'ROLLBACK' or ucr.tipo = 'CREDITFREECASH'
                                       THEN ucr.valor_premios
                                   ELSE 0 END), 2)                              AS total_premios,
                     ROUND(SUM(CASE
                                   WHEN ucr.tipo = 'CREDITFREESPIN' or ucr.tipo = 'ROLLBACKFREESPIN'
                                       THEN ucr.valor_premios
                                   ELSE 0 END), 2)                              AS premios_bonificacion,
                     SUM(CASE WHEN ucr.tipo LIKE 'ROLLBACK%' THEN 1 ELSE 0 END) AS total_apuestas_anuladas,
                     ROUND(SUM(CASE
                                   WHEN ucr.tipo LIKE 'ROLLBACK%' THEN ucr.valor
                                   ELSE 0 END), 2)                              AS total_devoluciones
              from usucasino_detalle_resumen ucr
                       INNER JOIN producto_mandante pm ON ucr.producto_id = pm.prodmandante_id
                       INNER JOIN producto p ON pm.producto_id = p.producto_id
                       INNER JOIN usuario_mandante um ON ucr.usuario_id = um.usumandante_id
                       INNER JOIN usuario u ON um.usuario_mandante = u.usuario_id
                       INNER JOIN categoria_mandante cm on cm.catmandante_id = p.categoria_id
                       INNER JOIN mandante m ON u.mandante = m.mandante
                       INNER JOIN pais ps ON u.pais_id = ps.pais_id
              WHERE 1 = 1
                AND cm.mandante = -1
                AND ucr.fecha_crea BETWEEN CONCAT('{$date_from}', ' 00:00:00') AND CONCAT('{$date_to}', ' 23:59:59')
                {$fuser_id}
                {$fmandante}
                {$fProdmandanteId}
                {$fpais}
                {$fdoc_number}
                {$fgame_name}
              group by p.descripcion
              LIMIT {$SkeepRows},{$MaxRows}) X;
        ";

        $sql_count = "
            SELECT count(*) as FILAS
            FROM (select date(ucr.fecha_crea)                                       as fecha,
                   pm.prodmandante_id                                              AS id_juego,
                 p.descripcion                                              AS nombre_Juego,
                 cm.descripcion                                             AS tipo,
                 cm.mandante                                                as catmandante,
                 ROUND(SUM(CASE
                               WHEN ucr.tipo = 'DEBIT' THEN ucr.valor
                               ELSE 0 END) +
                       SUM(CASE
                               WHEN ucr.tipo = 'DEBITFREECASH' THEN ucr.valor_premios + ucr.valor
                               ELSE 0 END)
                     , 2)                                                   AS total_apuesta,
    
                 ROUND(SUM(CASE
                               WHEN ucr.tipo = 'DEBITFREECASH' THEN ucr.valor_premios
                               ELSE 0
                     END), 2)                                               as apuesta_bonificacion,
                 ROUND(SUM(CASE
                               WHEN ucr.tipo = 'CREDIT' or ucr.tipo = 'ROLLBACK' or ucr.tipo = 'CREDITFREECASH'
                                   THEN ucr.valor_premios
                               ELSE 0 END), 2)                              AS total_premios,
                 ROUND(SUM(CASE
                               WHEN ucr.tipo = 'CREDITFREESPIN' or ucr.tipo = 'ROLLBACKFREESPIN'
                                   THEN ucr.valor_premios
                               ELSE 0 END), 2)                              AS premios_bonificacion,
                 SUM(CASE WHEN ucr.tipo LIKE 'ROLLBACK%' THEN 1 ELSE 0 END) AS total_apuestas_anuladas,
                 ROUND(SUM(CASE
                               WHEN ucr.tipo LIKE 'ROLLBACK%' THEN ucr.valor
                               ELSE 0 END), 2)                              AS total_devoluciones
          from usucasino_detalle_resumen ucr
                   INNER JOIN producto_mandante pm ON ucr.producto_id = pm.prodmandante_id
                   INNER JOIN producto p ON pm.producto_id = p.producto_id
                   INNER JOIN usuario_mandante um ON ucr.usuario_id = um.usumandante_id
                   INNER JOIN usuario u ON um.usuario_mandante = u.usuario_id
                   INNER JOIN categoria_mandante cm on cm.catmandante_id = p.categoria_id
                   INNER JOIN mandante m ON u.mandante = m.mandante
                   INNER JOIN pais ps ON u.pais_id = ps.pais_id
          WHERE 1 = 1
            AND cm.mandante = -1
            AND ucr.fecha_crea BETWEEN CONCAT('{$date_from}', ' 00:00:00') AND CONCAT('{$date_to}', ' 23:59:59')
            {$fuser_id}
            {$fmandante}
            {$fProdmandanteId}
            {$fpais}
            {$fdoc_number}
            {$fgame_name}
          group by p.descripcion) X;
        ";
    }


    /* Verifica una condición y muestra variables si se cumple. */
    if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
        print_r($sql);
    }
    if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
        print_r($sql_count);
        die();
    }

    /* Se crean objetos de BonoInterno y se ejecutan consultas SQL para obtener datos. */
    $Bonointerno = new BonoInterno();
    $data = $Bonointerno->execQuery($transaccion, $sql);

    $Bonointerno = new BonoInterno();
    $count = $Bonointerno->execQuery($transaccion, $sql_count);

    $dataFinal = [];

    /* Recorre datos, asigna valores a un arreglo y lo agrega a un array final. */
    foreach ($data as $value) {

        $array = [];
        $array["Date"] = $value->{"X.FECHA"} ?? $value->{"xx.fecha"};
        $array["Id"] = $value->{"X.ID_JUEGO"} ?? $value->{"xx.id_juego"};
        $array["GameName"] = $value->{"X.NOMBRE_JUEGO"} ?? $value->{"xx.nombre_Juego"};
        $array["TypeGame"] = $value->{"X.TIPO"} ?? $value->{"xx.tipo"};
        $array["ReturnPercentage"] = $value->{".PORCE_RETORNO_REAL"} ?? $value->{"xx.porce_retorno_real"};
        $array["TotalBets"] = $value->{"X.TOTAL_APUESTA"} ?? $value->{"xx.total_apuesta"};
        $array["TotalBonusBet"] = $value->{"X.APUESTA_BONIFICACION"} ?? $value->{"xx.apuesta_bonificacion"};
        $array["TotalAward"] = $value->{"X.TOTAL_PREMIOS"} ?? $value->{"xx.total_premios"};
        $array["TotalBonusPrizes"] = $value->{"X.PREMIOS_BONIFICACION"} ?? $value->{"xx.premios_bonificacion"};
        $array["TotalBetsVoided"] = $value->{"X.TOTAL_APUESTAS_ANULADAS"} ?? $value->{"xx.total_apuestas_anuladas"};
        $array["TotalReturns"] = $value->{"X.TOTAL_DEVOLUCIONES"} ?? $value->{"xx.total_devoluciones"};

        array_push($dataFinal, $array);
    }


    /* asigna valores a un arreglo de respuesta para un API o función. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $count[0]->{".FILAS"} ?? $count[0]->{".Filas"};
    $response["data"] = $dataFinal;
} else {
    /* maneja un error, configurando un mensaje de alerta y estado. */

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Invalid";
    $response["ModelErrors"] = [];
}




