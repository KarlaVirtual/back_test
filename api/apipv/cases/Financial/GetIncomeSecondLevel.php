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

/** @param string $dateFrom : Descripción: Fecha de inicio para el reporte de ingresos de segundo nivel.
 * @param string $dateTo : Descripción: Fecha de fin para el reporte de ingresos de segundo nivel.
 * @param string $DocumentNumber : Descripción: Número de documento del jugador.
 * @param string $DocumentType : Descripción: Tipo de documento del jugador.
 * @param string $PlayerName : Descripción: Nombre del jugador.
 * @param string $PlayerId : Descripción: Identificador del jugador.
 * @param int $start : Descripción: Número de filas a omitir en la consulta.
 * @param int $count : Descripción: Número máximo de filas a devolver.
 *
 * @Description Obtener el rendimiento de ingresos de segundo nivel de las apuestas realizadas por los jugadores.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos del rendimiento de ingresos de segundo nivel.
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

/* captura datos de una solicitud y las almacena en variables. */

$Helpers = new Helpers;
$date_from =$_REQUEST['dateFrom'];
$date_to = $_REQUEST['dateTo'];
$doc_number = $_REQUEST['DocumentNumber'];
$mandante = $_SESSION['mandante'];
$user_id = $_REQUEST['PlayerId'];
$pais_id = $_SESSION['PaisCondS'];

/* obtiene parámetros de solicitud relacionados con un documento y un usuario. */
$doc_type = $_REQUEST['DocumentType'];
$user_name = $_REQUEST['PlayerName'];

$SkeepRows = $_REQUEST["start"];
$MaxRows = $_REQUEST["count"];
$fdate = '';

/* Se declara una variable vacía para almacenar un identificador de usuario. */
$fuser_id = '';

if ($SkeepRows != '' && $SkeepRows != null && $MaxRows != '' && $MaxRows != null) {

    /* establece rangos de fechas desde "dateFrom" hasta "dateTo". */
    if ($_REQUEST["dateFrom"] != "") {
        $date_from = date("Y-m-d 00:00:00", strtotime($date_from));
    } else {
        $date_from = date("Y-m-d 00:00:00");
    }
    if ($_REQUEST["dateTo"] != "") {
        $date_to = date("Y-m-d 23:59:59", strtotime($date_to));
    } else {
        /* Establece `$date_to` al final del día actual si no se cumple una condición. */

        $date_to = date("Y-m-d 23:59:59");
    }


    /* Filtra resultados por fechas y usuario si están definidos. */
    if ($date_from != '' and $date_from != null && $date_to != '' and $date_to != null) {
        $fdate = "AND  ite.fecha_cierre_time BETWEEN '$date_from' AND '$date_to'";
    }

    if ($user_id != '' && $user_id != null) {
        $fuser_id = " AND u.usuario_id =  '$user_id' ";
    }


    /* Condiciones para agregar filtros de consulta basados en variables PHP. */
    if ($mandante != '' && $mandante != null) {
        $fmandante = "AND u.mandante = '$mandante'";
    }

    if ($pais_id != '' && $pais_id != null) {
        $fpais = " AND u.pais_id = '$pais_id' ";
    }

    /* Filtra resultados basados en el número y tipo de documento proporcionados. */
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

    /* verifica si el nombre de usuario no está vacío antes de agregar condiciones. */
    if ($user_name != null && $user_name != ''){
        $field2 = $Helpers->set_custom_field('r.nombre');
        $fuser_name = "AND $field2 COLLATE utf8mb4_0900_ai_ci like '%$user_name%'";
    }


    $BonointernoMySqlDAO = new BonoInternoMySqlDAO();

    /* Se obtiene una transacción desde el DAO de la base de datos MySQL. */
    $transaccion = $BonointernoMySqlDAO->getTransaction();
    $sql = "SELECT DATE(ite.fecha_cierre_time)                                             AS Fecha,
       ite.ticket_id                                                           AS Identificador_transaccion,
       ite.usuario_id                                                          AS identificador_usuario,
       det.sportid                                                             AS Nombre_Deporte,
       det.ligaid                                                              AS Nombre_Competencia,
       det.apuesta                                                             AS Nombre_Evento,
       det.agrupador                                                           AS Nombre_Mercado,
       det.opcion                                                              AS Seleccion,
       ROUND((CASE
                  WHEN ite.eliminado = 'S' AND ite.fecha_crea != ite.fecha_cierre
                      THEN -ite.vlr_apuesta
                  WHEN (ite.eliminado = 'S' AND ite.fecha_crea = ite.fecha_cierre)
                      THEN 0
                  ELSE ite.vlr_apuesta END), 2)                                AS Apuesta,
       ROUND((CASE WHEN ite.premiado = 'S' THEN ite.vlr_premio ELSE 0 END), 2) AS Premio,
       det.logro                                                               AS Cuota,
       ite.fecha_crea_time                                                     AS Fecha_Ticket,
       CAST(CONCAT(det.fecha_evento, ' ', det.hora_evento) AS DATETIME)        AS Fecha_Inicio, 
       ite.fecha_cierre_time                                                   AS Fecha_Fin,
        CASE
        WHEN r.tipo_doc = 'C' THEN 'DNI'
        WHEN r.tipo_doc = 'E' THEN 'Carnet de extranjería'
        WHEN r.tipo_doc = 'P' THEN 'Pasaporte'
        ELSE 'No definido' END                                                 AS Tipo_Documento,
        u.nombre                                                               AS Nombre_Usuario,
        r.cedula                                                               AS Cedula
    FROM it_ticket_det det
             JOIN it_ticket_enc ite ON det.ticket_id = ite.ticket_id
             JOIN usuario u ON ite.usuario_id = u.usuario_id
             JOIN mandante m on u.mandante = m.mandante
             JOIN registro r on u.usuario_id = r.usuario_id
             JOIN pais p on u.pais_id = p.pais_id
    WHERE 1 = 1
      AND ite.eliminado = 'N'
      {$fuser_id}
      {$fmandante}
      {$fpais}
      {$fdoc_number}
      {$fdate}
      {$fdoc_type}
      {$fuser_name}
      AND u.test = 'N'
    GROUP BY det.it_ticketdet_id
    LIMIT {$SkeepRows},{$MaxRows};
    ";

    /* Se crea una consulta SQL para contar tickets en una base de datos. */
    $Bonointerno = new BonoInterno();
    $data = $Bonointerno->execQuery($transaccion, $sql);

    $sql_count = "SELECT count(*)
    FROM it_ticket_det det
         JOIN it_ticket_enc ite ON det.ticket_id = ite.ticket_id
         JOIN usuario u ON ite.usuario_id = u.usuario_id
         JOIN mandante m on u.mandante = m.mandante
         JOIN registro r on u.usuario_id = r.usuario_id
         JOIN pais p on u.pais_id = p.pais_id
    WHERE 1 = 1
      AND ite.eliminado = 'N'
      {$fuser_id}
      {$fmandante}
      {$fpais}
      {$fdoc_number}
      {$fdate}
      {$fdoc_type}
      {$fuser_name}
      AND u.test = 'N'
        ";

    /* Se crea un objeto BonoInterno y se ejecuta una consulta SQL para contar datos. */
    $Bonointerno = new BonoInterno();
    $count = $Bonointerno->execQuery($transaccion, $sql_count);

    $dataFinal = [];
    foreach ($data as $value) {


        /* Crea un array asociativo con información de transacciones y eventos deportivos. */
        $array = [];
        $array["Date"] = $value->{".Fecha"};
        $array["UniqueTransactionId"] = $value->{"ite.Identificador_transaccion"};
        $array["SportName"] = $value->{"det.Nombre_Deporte"};
        $array["EventName"] = $value->{"det.Nombre_Evento"};
        $array["MarketName"] = $value->{"det.Nombre_Mercado"};

        /* asigna valores de un objeto a un array asociativo para su procesamiento. */
        $array["Selection"] = $value->{"det.Seleccion"};
        $array["Bet"] = $value->{".Apuesta"};
        $array["Award"] = $value->{".Premio"};
        $array["Odds"] = $value->{"det.Cuota"};
        $array["TicketDate"] = $value->{"ite.Fecha_Ticket"};
        $array["StartDate"] = $value->{".Fecha_Inicio"};

        /* asigna valores de un objeto a un array asociativo en PHP. */
        $array["EndDate"] = $value->{"ite.Fecha_Fin"};
        $array["TransactionLocation"] = 0;
        $array["DocumentType"] = $value->{'.Tipo_Documento'};
        $array["PlayerName"] = $value->{'u.Nombre_Usuario'};
        $array["DocumentNumber"] = $value->{'r.Cedula'};
        $array["PlayerId"] = $value->{'ite.identificador_usuario'};


        /* Agrega un array al final del array `$dataFinal` en PHP. */
        array_push($dataFinal, $array);
    }


    /* Asigna valores a un array asociativo para una respuesta estructurada en PHP. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $count[0]->{".count(*)"};
    $response["data"] = $dataFinal;
} else {
    /* asigna un mensaje de error y estado a una respuesta. */

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Invalid";
    $response["ModelErrors"] = [];
}




