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

/**
 *
 * @param string $dateFrom : Descripción: Fecha de inicio para el reporte de pagos de jackpot.
 * @param string $dateTo : Descripción: Fecha de fin para el reporte de pagos de jackpot.
 * @param string $DocumentNumber : Descripción: Número de documento del jugador.
 * @param string $DocumentType : Descripción: Tipo de documento del jugador.
 * @param string $PlayerName : Descripción: Nombre del jugador.
 * @param string $PlayerId : Descripción: Identificador del jugador.
 * @param int $start : Descripción: Número de filas a omitir en la consulta.
 * @param int $count : Descripción: Número máximo de filas a devolver.
 *
 * @Description Obtener los detalles de los pagos de jackpot.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos de los pagos de jackpot.
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


/* obtiene datos de una solicitud y variables de sesión en PHP. */
$date_from = $_REQUEST['dateFrom'];
$date_to = $_REQUEST['dateTo'];
$doc_number = $_REQUEST['DocumentNumber'];
$mandante = $_SESSION['mandante'];
$user_id = $_REQUEST['PlayerId'];
$pais_id = $_SESSION['PaisCondS'];

/* Código PHP que recoge datos de una solicitud HTTP para manejar registros de documentos. */
$Country = $_REQUEST['CountrySelect'];
$doc_type = $_REQUEST['DocumentType'];
$user_name = $_REQUEST['PlayerName'];

$SkeepRows = $_REQUEST["start"];
$MaxRows = $_REQUEST["count"];


/* Variables vacías para almacenar una fecha y un identificador de usuario. */
$fdate = '';
$fuser_id = '';
if ($SkeepRows != '' && $SkeepRows != null && $MaxRows != '' && $MaxRows != null) {

    /* procesa fechas de entrada desde una solicitud HTTP. */
    if ($_REQUEST["dateFrom"] != "") {
        $date_from = date("Y-m-d 00:00:00", strtotime($date_from));
    } else {
        $date_from = date("Y-m-d 00:00:00");
    }
    if ($_REQUEST["dateTo"] != "") {
        $date_to = date("Y-m-d 23:59:59", strtotime($date_to));
    } else {
        /* establece una fecha y hora predeterminada si no se cumple una condición. */

        $date_to = date("Y-m-d 23:59:59");
    }


    /* Filtra resultados según fechas y usuario en una consulta SQL. */
    if ($date_from != '' and $date_from != null && $date_to != '' and $date_to != null) {
        $fdate = "AND tj.fecha_crea BETWEEN '$date_from' AND '$date_to'";
    }

    if ($user_id != '' && $user_id != null) {
        $fuser_id = " AND u.usuario_id =  '$user_id' ";
    }


    /* Condiciona la inclusión de filtros SQL según variables no nulas ni vacías. */
    if ($mandante != '' && $mandante != null) {
        $fmandante = "AND u.mandante = '$mandante'";
    }

    if ($Country != '' && $Country != null) {
        $fpais = " AND u.pais_id = '$Country' ";
    } else {
        /* Condicional que verifica si $pais_id no está vacío para filtrar resultados. */

        if ($pais_id != '' && $pais_id != null) {
            $fpais = " AND u.pais_id = '$pais_id' ";
        }
    }


    /* Filtra resultados basados en número y tipo de documento si son válidos. */
    if ($doc_number != '' and $doc_number != null) {
        $fdoc_number = "AND r.cedula = '$doc_number'";
    }

    if (!empty($doc_type) && $doc_type != '0') {
        $doc_type = match ((int)$doc_type) {
            1 => 'C',
            2 => 'E',
            3 => 'P'
        };
        $fdoc_type = "AND r.tipo_doc = '$doc_type'";
    }


    /* Verifica si el nombre de usuario no está vacío y lo prepara para una consulta. */
    if ($user_name != null && $user_name != '') {
        $fuser_name = "AND r.nombre like '%$user_name%'";
    }


    $dataFinal = [];


    /* asigna valores a un arreglo de respuesta para un procesamiento de datos. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $count[0]->{".sum(Fila)"};
    $response["data"] = $dataFinal;
} else {
    /* maneja un error asignando mensajes a una respuesta estructurada. */

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Invalid";
    $response["ModelErrors"] = [];
}




