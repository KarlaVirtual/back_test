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
 *
 * @param string $dateFrom : Descripción: Fecha de inicio para el reporte de eventos futuros.
 * @param string $dateTo : Descripción: Fecha de fin para el reporte de eventos futuros.
 * @param string $DocumentNumber : Descripción: Número de documento del jugador.
 * @param string $DocumentType : Descripción: Tipo de documento del jugador.
 * @param string $PlayerName : Descripción: Nombre del jugador.
 * @param string $PlayerId : Descripción: Identificador del jugador.
 * @param int $start : Descripción: Número de filas a omitir en la consulta.
 * @param int $count : Descripción: Número máximo de filas a devolver.
 *
 * @Description Obtener eventos futuros de apuestas.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos de los eventos futuros.
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

/* captura datos del formulario y variables de sesión para procesamiento. */
$date_from = $_REQUEST['dateFrom'];
$Helpers = new Helpers;
$date_from =$_REQUEST['dateFrom'];
$date_to = $_REQUEST['dateTo'];
$doc_number = $_REQUEST['DocumentNumber'];
$mandante = $_SESSION['mandante'];
$user_id = $_REQUEST['PlayerId'];
$pais_id = $_SESSION['PaisCondS'];


/* recoge parámetros de entrada para gestionar filas y tipos de documentos en un formulario. */
$SkeepRows = $_REQUEST["start"];
$MaxRows = $_REQUEST["count"];


$doc_type = $_REQUEST['DocumentType'];
$user_name = $_REQUEST['PlayerName'];


/* Se inicializan dos variables vacías: $fdate y $fuser_id. */
$fdate = '';
$fuser_id = '';
if ($SkeepRows != '' && $SkeepRows != null && $MaxRows != '' && $MaxRows != null) {

    /* establece fechas a partir de la entrada del usuario o la fecha actual. */
    if ($_REQUEST["dateFrom"] != "") {
        $date_from = date("Y-m-d", strtotime($date_from));
    } else {
        $date_from = date("Y-m-d");
    }
    if ($_REQUEST["dateTo"] != "") {
        $date_to = date("Y-m-d", strtotime($date_to));
    } else {
        /* Asigna la fecha de mañana a la variable $date_to si la condición es falsa. */

        $date_to = date("Y-m-d", strtotime("+1 day"));
    }


    /* Filtra eventos por fecha y usuario si estos valores no son nulos o vacíos. */
    if ($date_from != '' and $date_from != null && $date_to != '' and $date_to != null) {
        $fdate = "AND itd.fecha_evento BETWEEN '$date_from' AND '$date_to'";
    }

    if ($user_id != '' && $user_id != null) {
        $fuser_id = " AND u.usuario_id =  '$user_id' ";
    }


    /* Condicionales que generan filtros SQL basados en variables definidas. */
    if ($mandante != '' && $mandante != null) {
        $fmandante = "AND u.mandante = '$mandante'";
    }

    if ($pais_id != '' && $pais_id != null) {
        $fpais = " AND u.pais_id = '$pais_id' ";
    }

    /* Filtra resultados según número y tipo de documento en una consulta SQL. */
    if ($doc_number != '' and $doc_number != null){
        $field2 = $Helpers->set_custom_field('r.cedula');
        $fdoc_number = "AND $field2 = '$doc_number'";
    }


    if (!empty($doc_type) && $doc_type != '0') {
        $doc_type = match ((int)$doc_type) {
            1 => 'C',
            2 => 'E',
            3 => 'P'
        };
        $fdoc_type = "AND r.tipo_doc = '$doc_type'";
    }

    /* Verifica si el nombre de usuario no está vacío antes de buscar transacciones. */
    if ($user_name != null && $user_name != ''){
        $field2 = $Helpers->set_custom_field('r.nombre');
        $fuser_name = "AND $field2 COLLATE utf8mb4_0900_ai_ci like '%$user_name%'";
    }

    $BonointernoMySqlDAO = new BonoInternoMySqlDAO();
    $transaccion = $BonointernoMySqlDAO->getTransaction();

    $sql = "select date(itd.fecha_evento) as Fecha,
       m.nombre               as Partner,
       pais.pais_nom          as Pais,
       ite.usuario_id         as Identificador_usuario,
       ite.ticket_id          as Identificador_apuesta,
       itd.fecha_evento       as Fecha_evento,
       itd.apuesta            as Evento,
       ite.fecha_crea_time    as Fecha_ticket,
       ite.vlr_apuesta        as Monto_Apostado,
        u.nombre,
        r.cedula,
        CASE
               WHEN r.tipo_doc = 'C' THEN 'DNI'
               WHEN r.tipo_doc = 'E' THEN 'Carnet de extranjería'
               WHEN r.tipo_doc = 'P' THEN 'Pasaporte'
               ELSE 'No definido' END AS Tipo_Documento
from it_ticket_enc ite
         join it_ticket_det itd on ite.ticket_id = itd.ticket_id
         join usuario u on ite.usuario_id = u.usuario_id
         JOIN pais pais ON u.pais_id = pais.pais_id
         JOIN registro r on u.usuario_id = r.usuario_id
         JOIN mandante m ON u.mandante = m.mandante
WHERE 1 = 1
    {$fuser_id}
    {$fmandante}
    {$fpais}
    {$fdoc_number}
    {$fdate}
    {$fuser_name}
    {$fdoc_type}
    AND ite.fecha_cierre_time is null
    group by ite.ticket_id, u.usuario_id
    LIMIT {$SkeepRows},{$MaxRows};
    ";

    /* Se crea un objeto BonoInterno y se ejecuta una consulta SQL. */
    $Bonointerno = new BonoInterno();
    $data = $Bonointerno->execQuery($transaccion, $sql);

    $sql_count = "select sum(Fila)
from (
         SELECT
             case when count(*) > 1 then 1 else 1 end as Fila
from it_ticket_enc ite
         join it_ticket_det itd on ite.ticket_id = itd.ticket_id
         join usuario u on ite.usuario_id = u.usuario_id
         JOIN pais pais ON u.pais_id = pais.pais_id
         JOIN registro r on u.usuario_id = r.usuario_id
         JOIN mandante m ON u.mandante = m.mandante
WHERE 1 = 1
    {$fuser_id}
    {$fmandante}
    {$fpais}
    {$fdoc_number}
    {$fdate}
    {$fuser_name}
    {$fdoc_type}
  AND ite.fecha_cierre_time is null
 group by ite.ticket_id, u.usuario_id)x;
        ";

    /* Se inicializa un objeto y se procesa una consulta para estructurar datos. */
    $Bonointerno = new BonoInterno();
    $count = $Bonointerno->execQuery($transaccion, $sql_count);

    $dataFinal = [];
    foreach ($data as $value) {
        $array = [];
        $array["Date"] = $value->{"itd.Fecha_evento"};
        $array["UniqueTransactionId"] = $value->{"ite.Identificador_apuesta"};
        $array["PlayerId"] = $value->{"ite.Identificador_usuario"};
        $array["TicketDate"] = $value->{"ite.Fecha_ticket"};
        $array["Bet"] = $value->{"ite.Monto_Apostado"};
        $array["DocumentNumber"] = $value->{"r.cedula"};
        $array["DocumentType"] = $value->{'.Tipo_Documento'};
        $array["PlayerName"] = $value->{'u.nombre'};

        array_push($dataFinal, $array);
    }


    /* asigna datos a un array de respuesta en formato estructurado. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $count[0]->{".sum(Fila)"};
    $response["data"] = $dataFinal;
} else {
    /* Código que maneja un error, estableciendo propiedades en una respuesta JSON. */

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Invalid";
    $response["ModelErrors"] = [];
}




