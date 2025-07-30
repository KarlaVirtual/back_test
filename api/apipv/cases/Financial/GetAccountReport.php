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
 * @param string $date_from : Descripción: Fecha de inicio del reporte en formato `Y-m-d`.
 * @param string $date_to : Descripción: Fecha de fin del reporte en formato `Y-m-d`.
 * @param string $doc_number : Descripción: Número de documento del usuario.
 * @param int $user_id : Descripción: Identificador único del usuario.
 * @param string $user_name : Descripción: Nombre del usuario.
 * @param int $doc_type : Descripción: Tipo de documento del usuario (1 para DNI, 2 para Carnet de extranjería, 3 para Pasaporte).
 * @param int $SkeepRows : Descripción: Número de filas a omitir en la consulta.
 * @param int $MaxRows : Descripción: Número máximo de filas a devolver en la consulta.
 *
 * @Description Este recurso permite obtener un reporte de cuentas de usuario en el sistema, filtrando por diferentes criterios como fechas, número de documento, identificador de usuario, nombre de usuario y tipo de documento.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en la operación.
 * - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 * - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 * - *ModelErrors* (array): Lista de errores de validación del modelo.
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos del reporte.
 *
 * Objeto en caso de error:
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'danger';
 * $response['AlertMessage'] = 'Invalid';
 * $response['ModelErrors'] = [];
 *
 * @throws Exception Permiso denegado
 * @throws Exception Inusual Detected
 * @throws Exception No tiene saldo para transferir
 *
 */


/* captura datos de un formulario para usarlos en una aplicación web. */
$Helpers = new Helpers;
$date_from =$_REQUEST['dateFrom'];
$date_to = $_REQUEST['dateTo'];
$doc_number = $_REQUEST['DocumentNumber'];
$mandante = $_SESSION['mandante'];
$user_id = $_REQUEST['PlayerId'];
$pais_id = $_SESSION['PaisCondS'];

/* Captura datos de entrada de un formulario para procesar solicitudes de usuarios. */
$user_name = $_REQUEST['PlayerName'];
$doc_type = $_REQUEST['DocumentType'];

$SkeepRows = $_REQUEST["start"];
$MaxRows = $_REQUEST["count"];
$fdate = '';

/* Se inicializa una variable `$fuser_id` como una cadena vacía en PHP. */
$fuser_id = '';

if ($SkeepRows != '' && $SkeepRows != null && $MaxRows != '' && $MaxRows != null) {

    /* valida fechas y las formatea en un rango específico. */
    if ($_REQUEST["dateFrom"] != "") {
        $date_from = date("Y-m-d 00:00:00", strtotime($date_from));
    } else {
        $date_from = date("Y-m-d 00:00:00");
    }
    if ($_REQUEST["dateTo"] != "") {
        $date_to = date("Y-m-d 23:59:59", strtotime($date_to));
    } else {
        /* Establece la variable $date_to con la fecha y hora final del día actual. */

        $date_to = date("Y-m-d 23:59:59");
    }


    /* Filtra registros por fechas y usuario en una consulta SQL. */
    if ($date_from != '' and $date_from != null && $date_to != '' and $date_to != null) {
        $fdate = "AND (u.fecha_crea BETWEEN '$date_from' AND '$date_to' OR ul.fecha_crea BETWEEN  '$date_from' AND '$date_to')";
    }

    if ($user_id != '' && $user_id != null) {
        $fuser_id = " AND u.usuario_id =  '$user_id' ";
    }


    /* Se construyen condiciones SQL basadas en variables si no están vacías o nulas. */
    if ($mandante != '' && $mandante != null) {
        $fmandante = "AND u.mandante = '$mandante'";
    }

    if ($pais_id != '' && $pais_id != null) {
        $fpais = " AND u.pais_id = '$pais_id' ";
    }

    /* filtra registros según documento y nombre de usuario proporcionados. */
    if ($doc_number != '' and $doc_number != null){
        $field2 = $Helpers->set_custom_field('r.cedula');
        $fdoc_number = "AND $field2 = '$doc_number'";
    }

    if ($user_name != null && $user_name != ''){
        $field2 = $Helpers->set_custom_field('r.nombre');
        $fuser_name = "AND $field2 COLLATE utf8mb4_0900_ai_ci like '%$user_name%'";
    }


    /* Selecciona el tipo de documento y crea una condición SQL si está definido. */
    if (!empty($doc_type) && $doc_type != '0') {
        $doc_type = match ((int)$doc_type) {
            1 => 'C',
            2 => 'E',
            3 => 'P'
        };
        $fdoc_type = "AND r.tipo_doc = '$doc_type'";
    }


    /* Se crea un objeto DAO y se obtiene una transacción de la base de datos. */
    $BonointernoMySqlDAO = new BonoInternoMySqlDAO();
    $transaccion = $BonointernoMySqlDAO->getTransaction();

    $sql = "select DATE(ul.fecha_crea)                                  as Fecha,
       u.usuario_id                                          as Id_Usuario,
       u.nombre                                              as Nombre_usuario,
       u.fecha_crea                                          as fecha_Registro,
       du.fecha_crea                                         as Fecha_TYC,
       case
           when u.estado = 'A' then 'Activo'
           when u.estado = 'I' then 'Inactivo'
           else u.estado end                                 as Estado_cuenta,
       u.observ                                              as Motivo,
       ul.fecha_crea                                         as Fecha_Inicio,
       ul.fecha_crea                                         as Fecha_Fin,
       case
           when (ul.tipo like 'CONTINGENCIA%' or ul.tipo = 'CANCELACCOUNT' or ul.tipo like 'ESTADOUSUARIO%' or
                 ul.tipo = 'RESTRICCIONIPUSUARIO' or ul.tipo = 'USUARIOPERMITERECARGA')
               then GROUP_CONCAT(ul.tipo SEPARATOR ', ') end as Tipo_exclusion,
       r.tipo_doc AS Tipo_Documento,
       r.Cedula AS Cedula,
       CASE
       WHEN r.tipo_doc = 'C' THEN 'DNI'
       WHEN r.tipo_doc = 'E' THEN 'Carnet de extranjería'
       WHEN r.tipo_doc = 'P' THEN 'Pasaporte'
       ELSE 'No definido' END AS Tipo_Documento
from usuario u
         left join (select *
                    from usuario_log ul
                    where 1 = 1
                      and (ul.tipo like 'CONTINGENCIA%' or ul.tipo = 'CANCELACCOUNT' or ul.tipo like 'ESTADOUSUARIO%' or
                           ul.tipo = 'RESTRICCIONIPUSUARIO' or ul.tipo = 'USUARIOPERMITERECARGA')) ul on u.usuario_id = ul.usuario_id
         left join documento_usuario du on u.usuario_id = du.usuario_id
         left join descarga d on du.documento_id = d.descarga_id
         left join registro r on r.usuario_id =  u.usuario_id
where 1 = 1
  and  ul.fecha_crea BETWEEN CONCAT('${date_from}') AND CONCAT('${date_to}')
       $fuser_id
       $fmandante
       $fpais
       $fdoc_number
       $fdate
       $fdoc_type
       $fuser_name
GROUP BY DATE(ul.fecha_crea), u.usuario_id
LIMIT $SkeepRows, $MaxRows;";


    /* Se crea un objeto BonoInterno y se ejecuta una consulta SQL. */
    $Bonointerno = new BonoInterno();
    $data = $Bonointerno->execQuery($transaccion, $sql);

    $sql_count = "select sum(Fila) from (SELECT case when count(*) > 1 then 1 else 1 end as Fila
         from usuario u
                  left join (select *
                             from usuario_log ul
                             where 1 = 1
                               and (ul.tipo like 'CONTINGENCIA%' or ul.tipo = 'CANCELACCOUNT' or ul.tipo like 'ESTADOUSUARIO%' or
                                    ul.tipo = 'RESTRICCIONIPUSUARIO' or ul.tipo = 'USUARIOPERMITERECARGA')) ul on u.usuario_id = ul.usuario_id
                  left join documento_usuario du on u.usuario_id = du.usuario_id
                  left join descarga d on du.documento_id = d.descarga_id
                  left join registro r on r.usuario_id =  u.usuario_id
         where 1 = 1
           and 
            ul.fecha_crea BETWEEN CONCAT('${date_from}') AND CONCAT('${date_to}')
        $fuser_id
        $fmandante
        $fpais
        $fdoc_number
        $fdate
        $fdoc_type
        $fuser_name
    GROUP BY DATE(ul.fecha_crea), u.usuario_id) x";

    /* recopila y organiza datos de jugadores en un array estructurado. */
    $Bonointerno = new BonoInterno();
    $count = $Bonointerno->execQuery($transaccion, $sql_count);

    $dataFinal = [];
    foreach ($data as $value) {

        $array = [];
        $array["Date"] = $value->{".Fecha"};
        $array["PlayerId"] = $value->{"u.Id_Usuario"};
        $array["PlayerName"] = $value->{"u.Nombre_usuario"};
        $array["RegistrationDate"] = $value->{"u.fecha_Registro"};
        $array["TermsAndConditionsDate"] = $value->{"du.Fecha_TYC"};
        $array["AccountStatus"] = $value->{".Estado_cuenta"};
        $array["StartDate"] = $value->{"ul.Fecha_Inicio"};
        $array["EndDate"] = $value->{"ul.Fecha_Fin"};
        $array["Reason"] = $value->{"u.Motivo"};
        $array["TypeExclusion"] = $value->{".Tipo_exclusion"};
        $array["DocumentNumber"] = $value->{"r.Cedula"};
        $array["DocumentType"] = $value->{".Tipo_Documento"};

        array_push($dataFinal, $array);
    }


    /* Asignación de valores a un arreglo de respuesta en formato estructurado. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $count[0]->{".sum(Fila)"};
    $response["data"] = $dataFinal;
} else {
    /* Manejo de errores en respuesta, indicando que la entrada es inválida. */

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Invalid";
    $response["ModelErrors"] = [];
}
