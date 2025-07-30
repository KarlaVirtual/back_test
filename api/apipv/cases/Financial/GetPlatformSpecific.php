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

/**@param string $dateFrom : Descripción: Fecha de inicio para el reporte específico de la plataforma.
 * @param string $dateTo : Descripción: Fecha de fin para el reporte específico de la plataforma.
 * @param string $DocumentNumber : Descripción: Número de documento del jugador.
 * @param string $PlayerId : Descripción: Identificador del jugador.
 * @param string $CountrySelect : Descripción: Identificador del país seleccionado.
 * @param int $PrType : Descripción: Tipo de programa de juego (1 para 'SPORT', 0 para otros).
 * @param int $start : Descripción: Número de filas a omitir en la consulta.
 * @param int $count : Descripción: Número máximo de filas a devolver.
 *
 * @Description Obtener los detalles específicos de los juegos de la plataforma .
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos específicos de la plataforma de juego.
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

/* captura fechas y datos de solicitudes y sesiones de usuario. */
$date_from = $_REQUEST['dateFrom'] . ' 00:00:00';
$date_to = $_REQUEST['dateTo'] . ' 23:59:59';
$doc_number = $_REQUEST['DocumentNumber'];
$mandante = $_SESSION['mandante'];
$user_id = $_REQUEST['PlayerId'];
$pais_id = $_SESSION['PaisCondS'];

/* recoge datos enviados por una solicitud, incluyendo país y tipo de producto. */
$Country = $_REQUEST['CountrySelect'];
$typepr = $_REQUEST['PrType'];
$SkeepRows = $_REQUEST["start"];
$MaxRows = $_REQUEST["count"];

$fdate = '';

/* Código que define condiciones para filtrar productos según su tipo. */
$fuser_id = '';
$ftyepr = '';

if ($typepr == 1) {
    $ftyepr = "and pr.tipo = 'SPORT'";
} else if ($typepr == 0) {
    /* Condicional que excluye productos de tipo "SPORT" si $typepr es igual a 0. */

    $ftyepr = "and pr.tipo != 'SPORT'";
}
if ($SkeepRows != '' && $SkeepRows != null && $MaxRows != '' && $MaxRows != null) {

    /* Se crea un objeto DAO y se obtiene una transacción de base de datos. */
    $BonointernoMySqlDAO = new BonoInternoMySqlDAO();
    $transaccion = $BonointernoMySqlDAO->getTransaction();

    $sql = "
        select date(pm.fecha_modif)               as Fecha,
           'Virtualsoft'                      as Nombre_plataforma,
           'v8.1.0'                           as Version,
           p.descripcion                      as Programa_juego,
           cm.descripcion                     AS Modalidad_juego,
           pm.codigo_minsetur                 as Codigo_mincetur,
           pm.fecha_modif                     as Fecha_inicio,
           (pm.fecha_modif + interval 1 year) as Fecha_fin,
           pr.descripcion                     as Proveedor
        from producto_mandante pm
                 join producto p on pm.producto_id = p.producto_id
                 LEFT JOIN categoria_mandante cm on (p.categoria_id = cm.catmandante_id AND cm.mandante = -1)
                 join proveedor pr on p.proveedor_id = pr.proveedor_id
        where 1 = 1
          and pm.fecha_modif between '{$date_from}' and '{$date_to}' 
          and pm.mandante = '{$_SESSION['mandante']}'
          and pm.pais_id = '{$_SESSION['PaisCondS']}'
         {$ftyepr}
        LIMIT $SkeepRows, $MaxRows;
    ";

    /* Consulta SQL para contar productos filtrados por fechas y condiciones específicas. */
    $Bonointerno = new BonoInterno();
    $data = $Bonointerno->execQuery($transaccion, $sql);

    $sql_count = "
        select count(*) as Fila
        from producto_mandante pm
                 join producto p on pm.producto_id = p.producto_id
                 LEFT JOIN categoria_mandante cm on (p.categoria_id = cm.catmandante_id AND cm.mandante = -1)
                 join proveedor pr on p.proveedor_id = pr.proveedor_id
        where 1 = 1
          and pm.fecha_modif between '{$date_from}' and '{$date_to}'
          and pm.mandante = '{$_SESSION['mandante']}'
          {$ftyepr}
          and pm.pais_id = '{$_SESSION['PaisCondS']}';
    ";

    /* crea un arreglo de información estructurada a partir de datos procesados. */
    $Bonointerno = new BonoInterno();
    $count = $Bonointerno->execQuery($transaccion, $sql_count);

    $dataFinal = [];
    foreach ($data as $value) {
        $array = [];
        $array["Date"] = $value->{".Fecha"};
        $array["Name"] = $value->{".Nombre_plataforma"};
        $array["Version"] = $value->{".Version"};
        $array["GameProgramMode"] = $value->{"p.Programa_juego"};
        $array["MinceturCode"] = $value->{"pm.Codigo_mincetur"};
        $array["StartDate"] = $value->{"pm.Fecha_inicio"};
        $array["EndDate"] = $value->{".Fecha_fin"};
        $array["ServiceProvider"] = $value->{"pr.Proveedor"};
        array_push($dataFinal, $array);
    }


    /* asigna valores a un array de respuesta estructurada para una API. */
    $response["HasError"] = false;
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $count[0]->{".Fila"};
    $response["data"] = $dataFinal;
} else {
    /* gestiona errores al devolver un mensaje de alerta de "inválido". */

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Invalid";
    $response["ModelErrors"] = [];
}




