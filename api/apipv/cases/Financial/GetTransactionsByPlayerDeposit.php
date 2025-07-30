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
use Backend\sql\ConnectionProperty;
use Backend\websocket\WebsocketUsuario;

/**
 *
 * @param string $dateFrom : Descripción: Fecha de inicio para el reporte de transacciones de depósitos.
 * @param string $dateTo : Descripción: Fecha de fin para el reporte de transacciones de depósitos.
 * @param string $DocumentNumber : Descripción: Número de documento del jugador.
 * @param string $DocumentType : Descripción: Tipo de documento del jugador.
 * @param string $PlayerName : Descripción: Nombre del jugador.
 * @param string $PlayerId : Descripción: Identificador del jugador.
 * @param int $start : Descripción: Número de filas a omitir en la consulta.
 * @param int $count : Descripción: Número máximo de filas a devolver.
 *
 * @Description Obtener las transacciones de depósitos realizadas por un jugador.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos de las transacciones de depósitos.
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

/* captura datos de fecha y usuario de solicitudes y sesiones. */
$Helpers = new Helpers;
$date_from =$_REQUEST['dateFrom'];
$date_to = $_REQUEST['dateTo'];
$doc_number = $_REQUEST['DocumentNumber'];
$mandante = $_SESSION['mandante'];
$user_id = $_REQUEST['PlayerId'];
$pais_id = $_SESSION['PaisCondS'];

/* recoge datos de entrada del usuario mediante solicitudes HTTP. */
$user_name = $_REQUEST['PlayerName'];
$doc_type = $_REQUEST['DocumentType'];

$SkeepRows = $_REQUEST["start"];
$MaxRows = $_REQUEST["count"];
$fdate = '';

/* Código que inicializa la variable $fuser_id como una cadena vacía en PHP. */
$fuser_id = '';

if ($SkeepRows != '' && $SkeepRows != null && $MaxRows != '' && $MaxRows != null) {

    /* procesa fechas recibidas y establece valores predeterminados si están vacías. */
    if ($_REQUEST["dateFrom"] != "") {
        $date_from = date("Y-m-d 00:00:00", strtotime($date_from));
    } else {
        $date_from = date("Y-m-d 00:00:00");
    }

    if ($_REQUEST["dateTo"] != "") {
        $date_to = date("Y-m-d 23:59:59", strtotime($date_to));
    } else {
        /* Asigna la fecha y hora final del día si no se cumple una condición. */

        $date_to = date("Y-m-d 23:59:59");
    }


    /* Filtra resultados según fechas y un ID de usuario si están definidos. */
    if ($date_from != '' and $date_from != null && $date_to != '' and $date_to != null) {
        $fdate = "AND uh.fecha_crea BETWEEN '$date_from' AND '$date_to'";
    }

    if ($user_id != '' && $user_id != null) {
        $fuser_id = " AND u.usuario_id =  '$user_id' ";
    }


    /* Condiciona la inclusión de filtros en una consulta según variables no vacías. */
    if ($mandante != '' && $mandante != null) {
        $fmandante = "AND u.mandante = '$mandante'";
    }

    if ($pais_id != '' && $pais_id != null) {
        $fpais = " AND u.pais_id = '$pais_id' ";
    }

    /* Filtra resultados según el número de documento y el nombre de usuario proporcionados. */
    if ($doc_number != '' and $doc_number != null){
        $field2 = $Helpers->set_custom_field('r.cedula');
        $fdoc_number = "AND $field2 = '$doc_number'";
    }

    if ($user_name != null && $user_name != ''){
        $field2 = $Helpers->set_custom_field('r.nombre');
        $fuser_name = "AND $field2 COLLATE utf8mb4_0900_ai_ci like '%$user_name%'";
    }


    /* Asignar un tipo de documento basado en un valor entero y construir una condición SQL. */
    if (!empty($doc_type) && $doc_type != '0') {
        $doc_type = match ((int)$doc_type) {
            1 => 'C',
            2 => 'E',
            3 => 'P'
        };
        $fdoc_type = "AND r.tipo_doc = '$doc_type'";
    }


    /* Código para obtener una transacción usando el objeto BonoInternoMySqlDAO en PHP. */
    $BonointernoMySqlDAO = new BonoInternoMySqlDAO();
    $transaccion = $BonointernoMySqlDAO->getTransaction();
    if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

        /* Establece una conexión a la base de datos usando la configuración del entorno. */
        $connOriginal = $_ENV["connectionGlobal"]->getConnection();

        try {

            /* Establece conexión segura con base de datos en producción utilizando PDO y entorno ambiental. */
            $connDB5 = null;
            if ($_ENV['ENV_TYPE'] == 'prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            } else {
                /* Conexión a base de datos MySQL usando PDO en caso de una condición específica. */


                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            /* Configura la conexión a la base de datos para usar UTF-8 y zona horaria específica. */
            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
            }


            /* Configura el nivel de aislamiento y espera de bloqueo en la base de datos. */
            if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
            }
            if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
            }

            /* Configura el tiempo máximo de ejecución y establece codificación UTF-8 en la base de datos. */
            if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
            }
            if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                $connDB5->exec("SET NAMES utf8mb4");
            }


            /* Establece conexión a la base de datos en una transacción global. */
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $transaccion->setConnection($_ENV["connectionGlobal"]);
        } catch (\Exception $e) {
            /* Manejo de excepciones en PHP, captura errores sin procesar en el bloque try. */


        }

    }

    $sql = "SELECT fecha,
       partner,
       pais,
       id_usuario,
       nombre,
       fecha_deposito,
       medio_deposito,
       fecha_completa,
       Id_recarga,
       estado,
       valor_deposito,
       credito_bonificacion,
       ajuste_saldo,
       Nombre_Usuario,
       Cedula,
       Tipo_Documento
        FROM (SELECT Fecha,
             Partner,
             Pais,
             ID_Usuario,
             Nombre,
             Fecha_deposito,
             Medio_Deposito,
             Fecha_completa,
             Id_recarga,
             Estado,
             Movimiento,
             Tipo,
             CASE
                 WHEN movimiento = 'E' AND tipo = 10 THEN valor
                 ELSE 0
                 END AS Valor_deposito,
             CASE
                 WHEN movimiento = 'E' AND tipo = 50 THEN valor
                 ELSE 0
                 END AS Credito_bonificacion,
             CASE
                 WHEN movimiento = 'E' AND tipo = 15 THEN valor
                 ELSE 0
                 END AS Ajuste_saldo,
             Nombre_Usuario,
             Cedula,
             Tipo_Documento
      FROM (SELECT Date(uh.fecha_crea) as Fecha,
                   m.nombre            AS Partner,
                   pais.pais_nom       AS Pais,
                   uh.usuario_id       AS ID_Usuario,
                   u.nombre            AS Nombre,
                   uh.fecha_crea       as Fecha_deposito,
                   p2.descripcion      AS Medio_Deposito,
                   uh.fecha_crea       AS Fecha_completa,
                   uh.externo_id as Id_recarga,
                   CASE
                       WHEN tp.estado_producto = 'A' THEN 'Pagada'
                       WHEN tp.estado_producto = 'E' THEN 'Enviada'
                       WHEN tp.estado_producto = 'EX' THEN 'Expirada'
                       WHEN tp.estado_producto = 'P' THEN 'Pendiente'
                       WHEN tp.estado_producto = 'R' THEN 'Rechazada'
                       WHEN ur.estado = 'A' THEN 'Pagada'
                       WHEN ur.estado = 'I' THEN 'Rechazada'
                       ELSE 0 END      AS Estado,
                   uh.movimiento       AS Movimiento,
                   uh.tipo             AS Tipo,
                   uh.valor            AS Valor,
                   u.nombre            AS Nombre_Usuario,
                   r.cedula            AS Cedula,
                   CASE
                   WHEN r.tipo_doc = 'C' THEN 'DNI'
                   WHEN r.tipo_doc = 'E' THEN 'Carnet de extranjería'
                   WHEN r.tipo_doc = 'P' THEN 'Pasaporte'
                   ELSE 'No definido' END AS Tipo_Documento
            FROM usuario_historial uh
                     JOIN usuario u ON uh.usuario_id = u.usuario_id
                     left outer join saldo_usuonline_ajuste sua on uh.externo_id = sua.ajuste_id
                     left outer join usuario_recarga ur on uh.externo_id = ur.recarga_id
                     LEFT OUTER JOIN transaccion_producto tp ON ur.recarga_id = tp.final_id
                     LEFT OUTER JOIN producto p on tp.producto_id = p.producto_id
                     LEFT OUTER JOIN proveedor p2 ON p.proveedor_id = p2.proveedor_id
                     LEFT OUTER JOIN punto_venta pv ON ur.puntoventa_id = pv.usuario_id
                     JOIN pais pais ON u.pais_id = pais.pais_id
                     JOIN registro r on u.usuario_id = r.usuario_id
                     JOIN mandante m ON u.mandante = m.mandante
            WHERE 1 = 1
                {$fuser_id}
                {$fdate}
                {$fmandante}
                {$fpais}
                {$fdoc_number}
                {$fuser_name}
                {$fdoc_type}) x
      WHERE  x.movimiento = 'E'
         AND (x.tipo = 10 OR x.tipo = 50 OR x.tipo = 15)
      LIMIT {$SkeepRows},{$MaxRows}) xx;
    ";

    /* verifica un valor en la solicitud y muestra contenido SQL si coincide. */
    if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
        print_r($sql);
    }

    $sql_count = "SELECT COUNT(*)
        FROM (SELECT Fecha,
             Partner,
             Pais,
             ID_Usuario,
             Nombre,
             Fecha_deposito,
             Medio_Deposito,
             Fecha_completa,
             Id_recarga,
             Estado,
             Movimiento,
             Tipo,
             CASE
                 WHEN movimiento = 'E' AND tipo = 10 THEN valor
                 ELSE 0
                 END AS Valor_deposito,
             CASE
                 WHEN movimiento = 'E' AND tipo = 50 THEN valor
                 ELSE 0
                 END AS Credito_bonificacion,
             CASE
                 WHEN movimiento = 'E' AND tipo = 15 THEN valor
                 ELSE 0
                 END AS Ajuste_saldo,
            Nombre_Usuario,
            Cedula,
            Tipo_Documento
      FROM (SELECT Date(uh.fecha_crea) as Fecha,
                   m.nombre            AS Partner,
                   pais.pais_nom       AS Pais,
                   uh.usuario_id       AS ID_Usuario,
                   u.nombre            AS Nombre,
                   uh.fecha_crea       as Fecha_deposito,
                   p2.descripcion      AS Medio_Deposito,
                   uh.fecha_crea       AS Fecha_completa,
                   uh.externo_id as Id_recarga,
                   CASE
                       WHEN tp.estado_producto = 'A' THEN 'Pagada'
                       WHEN tp.estado_producto = 'E' THEN 'Enviada'
                       WHEN tp.estado_producto = 'EX' THEN 'Expirada'
                       WHEN tp.estado_producto = 'P' THEN 'Pendiente'
                       WHEN tp.estado_producto = 'R' THEN 'Rechazada'
                       WHEN ur.estado = 'A' THEN 'Pagada'
                       WHEN ur.estado = 'I' THEN 'Rechazada'
                       ELSE 0 END      AS Estado,
                   uh.movimiento       AS Movimiento,
                   uh.tipo             AS Tipo,
                   uh.valor            AS Valor,
                   u.nombre            AS Nombre_Usuario,
                   r.cedula            AS Cedula,
                   CASE
                   WHEN r.tipo_doc = 'C' THEN 'DNI'
                   WHEN r.tipo_doc = 'E' THEN 'Carnet de extranjería'
                   WHEN r.tipo_doc = 'P' THEN 'Pasaporte'
                   ELSE 'No definido' END AS Tipo_Documento
            FROM usuario_historial uh
                     JOIN usuario u ON uh.usuario_id = u.usuario_id
                     left outer join saldo_usuonline_ajuste sua on uh.externo_id = sua.ajuste_id
                     left outer join usuario_recarga ur on uh.externo_id = ur.recarga_id
                     LEFT OUTER JOIN transaccion_producto tp ON ur.recarga_id = tp.final_id
                     LEFT OUTER JOIN producto p on tp.producto_id = p.producto_id
                     LEFT OUTER JOIN proveedor p2 ON p.proveedor_id = p2.proveedor_id
                     LEFT OUTER JOIN punto_venta pv ON ur.puntoventa_id = pv.usuario_id
                     JOIN pais pais ON u.pais_id = pais.pais_id
                     JOIN registro r on u.usuario_id = r.usuario_id
                     JOIN mandante m ON u.mandante = m.mandante
            WHERE  1 = 1
                {$fuser_id}
                {$fdate}
                {$fmandante}
                {$fpais}
                {$fdoc_number}
                {$fuser_name}
                {$fdoc_type}) x
      WHERE  x.movimiento = 'E'
         AND (x.tipo = 10 OR x.tipo = 50 OR x.tipo = 15)
      ) xx;";

    /* Validación de solicitud y posible impresión de datos antes de crear un objeto. */
    if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
        print_r($sql_count);
        die();
    }

    $Bonointerno = new BonoInterno();
    $sqlQuery2 = "SET @@SESSION.block_encryption_mode = 'aes-128-cbc';";
    $Bonointerno->execQuery($transaccion,$sqlQuery2);

    /* ejecuta consultas SQL y establece conexiones según configuraciones ambientales. */
    $data = $Bonointerno->execQuery($transaccion,$sql);

    $Bonointerno = new BonoInterno();
    $count = $Bonointerno->execQuery($transaccion, $sql_count);

    if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
        $connDB5 = null;
        $_ENV["connectionGlobal"]->setConnection($connOriginal);
        $transaccion->setConnection($_ENV["connectionGlobal"]);;
    }

    /* transforma datos en un nuevo formato de arreglo simplificado. */
    $dataFinal = [];
    foreach ($data as $value) {
        $array = [];
        $array["Date"] = $value->{"xx.fecha"};
        $array["PlayerId"] = $value->{"xx.id_usuario"};
        $array["PlayerName"] = $value->{"xx.nombre"};
        $array["DateDeposit"] = $value->{"xx.fecha_deposito"};
        $array["Deposit"] = $value->{"xx.valor_deposito"};
        $array["PaymentMethod"] = $value->{"xx.medio_deposito"};
        $array["BonusCredits"] = $value->{"xx.credito_bonificacion"};
        $array["BalanceAdjustment"] = $value->{"xx.ajuste_saldo"};
        $array["State"] = $value->{"xx.estado"};
        $array["DocumentNumber"] = $value->{"xx.Cedula"};
        $array["DocumentType"] = $value->{'xx.Tipo_Documento'};
        array_push($dataFinal, $array);
    }


    /* asigna valores a un arreglo de respuesta en PHP. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $count[0]->{".COUNT(*)"};
    $response["data"] = $dataFinal;
} else {
    /* maneja un error estableciendo una respuesta con mensaje de alerta. */

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Invalid";
    $response["ModelErrors"] = [];
}




