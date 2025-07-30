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
 * Agent/AssignmentQuota
 *
 * Obtener los cupos asignados a puntos de venta o agentes
 *
 * @param array $_REQUEST Datos de la solicitud HTTP:
 * @param string id Identificador del usuario.
 * @param string Id Identificador del usuario.
 * @param string Name Nombre del usuario.
 * @param string Type Tipo de cupo.
 * @param string TypeUser Tipo de usuario.
 * @param string transactionId Identificador de la transacción.
 * @param string UserIdAgent Identificador del agente.
 * @param string UserIdAgent2 Identificador del segundo agente.
 * @param string CountrySelect Selección del país.
 * @param int count Número máximo de filas.
 * @param int start Número de filas a omitir.
 * @param string dateFrom Fecha de inicio.
 * @param string dateTo Fecha de fin.
 *
 *
 * @return array $response Respuesta de la solicitud:
 * - HasError: boolean Indica si hubo un error.
 * - AlertType: string Tipo de alerta.
 * - AlertMessage: string Mensaje de alerta.
 * - ModelErrors: array Errores del modelo.
 * - Data: array Datos finales.
 * - pos: int Posición de inicio.
 * - total_count: int Conteo total de registros.
 * - data: array Datos finales.
 *
 * @throws Exception Si se detecta un comportamiento inusual.
 */


/* captura datos enviados mediante solicitudes HTTP y los asigna a variables. */
$id = $_REQUEST["id"];
$id = $_REQUEST["Id"];
$Name = $_REQUEST["Name"];
$Type = $_REQUEST["Type"];
$TypeUser = $_REQUEST["TypeUser"];
$TransactionId = $_REQUEST["transactionId"];


/* obtiene datos de entrada del usuario mediante solicitudes HTTP. */
$UserIdAgent = $_REQUEST["UserIdAgent"];
$UserIdAgent2 = $_REQUEST["UserIdAgent2"];
$CountrySelect = $_REQUEST["CountrySelect"];

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

/*Se inicializa la variable rules como un array vacio antes de su utilización para cada validación*/
$rules = [];


/* procesa fechas de entrada y las convierte a un formato específico. */
if ($_REQUEST["dateFrom"] != "") {
    //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"])));

}
if ($_REQUEST["dateTo"] != "") {
    //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"])));

}


/* inicializa una variable y verifica si otra está vacía para modificarla. */
$seguir = true;

if ($SkeepRows == "") {
    $seguir = false;
    $SkeepRows = "0";
}


/* establece valores predeterminados para variables vacías en un contexto de programación. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $seguir = false;
    $MaxRows = "100";
}


/* Se crea un submenú y se inicializa el perfil asociado al mismo. */
$Submenu = new Submenu("", "assignmentQuotaMade", '3');


try {
    $PerfilSubmenu = new PerfilSubmenu($_SESSION["win_perfil"], $Submenu->getSubmenuId());

} catch (Exception $e) {
    /* Captura excepciones y crea un objeto `PerfilSubmenu` con datos de sesión y submenú. */

    $PerfilSubmenu = new PerfilSubmenu('CUSTOM', $Submenu->getSubmenuId(), $_SESSION["usuario"]);
}


/* Valida el perfil del usuario antes de asignar el ID en sesión. */
if ($id == "" && ($_SESSION["win_perfil"] == "USUONLINE" || $_SESSION["win_perfil"] == "CAJERO")) {
    throw new Exception("Inusual Detected", "11");

}

if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
    $id = $_SESSION["usuario"];
}


/* asigna un ID de usuario si está vacío y pertenece a ciertos perfiles. */
if ($id == "") {
    if ($_SESSION["win_perfil"] == "CONCESIONARIO" || $_SESSION["win_perfil"] == "CONCESIONARIO2" || $_SESSION["win_perfil"] == "CONCESIONARIO3") {
        $id = $_SESSION["usuario"];
    }


} else {

    /* Verifica el perfil de usuario y establece reglas específicas en una sesión. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* Condiciona reglas según el perfil del usuario en sesión "CONCESIONARIO2". */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* verifica el perfil del usuario y establece reglas de acceso específicas. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


}


/* Se crea un objeto 'CupoLog' y se establece una regla basada en una condición. */
$CupoLog = new CupoLog();


if ($id != "") {
    array_push($rules, array("field" => "cupo_log.usucrea_id", "data" => "$id", "op" => "eq"));
}


/* Añade reglas a un array basadas en condiciones de sesión y variable de nombre. */
if ($_SESSION["usuario"] == 4089418) {

    array_push($rules, array("field" => "cupo_log.usucrea_id", "data" => 73737, "op" => "eq"));
}

if ($Name != "") {
    array_push($rules, array("field" => "usuario2.nombre", "data" => "$Name", "op" => "cn"));
}


/* Agrega reglas a un arreglo basado en condiciones de transacción y usuario. */
if ($TransactionId != "") {
    array_push($rules, array("field" => "cupo_log.numero_transaccion", "data" => $TransactionId, "op" => "eq"));
}


if ($UserIdAgent2 != "" && is_numeric($UserIdAgent2)) {
    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => "$UserIdAgent2", "op" => "eq"));
}

/* agrega reglas según condiciones de usuario y país a un array. */
if ($Name != "" && is_numeric($Name)) {
    array_push($rules, array("field" => "cupo_log.usuario_id", "data" => "$Name", "op" => "eq"));
}


// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
}

/* añade reglas basadas en selecciones de país y fechas. */
if ($CountrySelect != "" && is_numeric($CountrySelect)) {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
}


if ($FromDateLocal != "") {
    array_push($rules, array("field" => "cupo_log.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));


}


/* agrega reglas basadas en el tipo de usuario definido. */
if ($TypeUser != "") {
    if ($TypeUser == "P") {
        array_push($rules, array("field" => "usuario_perfil2.perfil_id", "data" => "PUNTOVENTA ", "op" => "eq"));

    } elseif ($TypeUser == "A") {
        array_push($rules, array("field" => "usuario_perfil2.perfil_id", "data" => "'ADMIN','ADMIN2','CUSTOM' ", "op" => "in"));
    }


}


/* Condiciona reglas basadas en fecha y mandante del usuario en sesión. */
if ($ToDateLocal != "") {
    array_push($rules, array("field" => "cupo_log.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
}


// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {
    /* Condición que agrega reglas basadas en la sesión de "mandanteLista". */


    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}


/* verifica el perfil de usuario y agrega reglas según sus permisos. */
if ($_ENV['debug']) {
    print_r($_SESSION);
}

if ($_SESSION['regionperfil'] != "0" && $_SESSION['regionperfil'] != null) {
    if ($_SESSION["win_perfil"] != "CONCESIONARIO" && $_SESSION["win_perfil"] != "CONCESIONARIO2" && $_SESSION["win_perfil"] != "CONCESIONARIO3" && $_SESSION["win_perfil"] != "PUNTOVENTA") {

        array_push($rules, array("field" => "usuario_perfil.region", "data" => $_SESSION['regionperfil'], "op" => "eq"));
    }
}
// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


/* Construye una consulta SQL select adaptada según el tipo especificado. */
$select = "cupo_log.*,usuario.usuario_id,usuario.nombre,usuario2.nombre,usuario.moneda,usuario2.usuario_id";
$grouping = "";
if ($Type == '1') {
    $select = "cupo_log.*,SUM(cupo_log.valor) valor,usuario.usuario_id,usuario.nombre,usuario2.nombre,usuario.moneda";

    $grouping = "usuario2.usuario_id,DATE_FORMAT(cupo_log.fecha_crea,'%Y-%m-%d'),cupo_log.tipo_id";
}


/* Se crea un filtro JSON y se obtienen logs de Cupo con parámetros específicos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$CupoLog = new CupoLog();


$mandantes = $CupoLog->getCupoLogsCustom($select, "cupo_log.cupolog_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);


/* Se decodifica un JSON y se inicializa un array vacío en PHP. */
$mandantes = json_decode($mandantes);

$final = [];

foreach ($mandantes->data as $key => $value) {
    if ($Type == '1') {


        /* Se crea un array con datos de usuario y fecha formateada. */
        $array = [];

        $array["Id"] = '';
        $array["UserId"] = $value->{"usuario.usuario_id"};
        $array["UserName"] = $value->{"usuario.nombre"};

        $array["Date"] = date("Y-m-d", strtotime($value->{"cupo_log.fecha_crea"}));

        /* asigna valores a un array y ajusta el monto negativo si es necesario. */
        $array["TypeQuota"] = $value->{"cupo_log.tipocupo_id"};
        $array["TypeTransaction"] = $value->{"cupo_log.tipo_id"};
        $array["Amount"] = $value->{".valor"};

        if ($value->{"cupo_log.tipo_id"} == 'S') {
            $array["Amount"] = -$array["Amount"];
        }


        /* Asigna valores de un objeto a un array asociativo en PHP. */
        $array["Currency"] = $value->{"usuario.moneda"};
        $array["Assigned"] = $value->{"usuario2.nombre"};
        $array["AssignedId"] = $value->{"usuario2.usuario_id"};
        $array["DepositId"] = $value->{"cupo_log.recarga_id"};
        $array["NameBank"] = $value->{"cupo_log.nombre_banco2"};
        $array["transactionId"] = $value->{"cupo_log.numero_transaccion"};

        /* Asignar cadena vacía si "transactionId" es cero y agregar el array a "final". */
        if ($array["transactionId"] == 0) {
            $array["transactionId"] = "";
        }

        array_push($final, $array);
    } else {


        /* crea un arreglo asociativo con información de un registro específico. */
        $array = [];

        $array["Id"] = $value->{"cupo_log.cupolog_id"};
        $array["UserId"] = $value->{"usuario.usuario_id"};
        $array["UserName"] = $value->{"usuario.nombre"};

        $array["Date"] = $value->{"cupo_log.fecha_crea"};

        /* Asigna valores de un objeto a un array asociativo en PHP. */
        $array["TypeQuota"] = $value->{"cupo_log.tipocupo_id"};
        $array["TypeTransaction"] = $value->{"cupo_log.tipo_id"};
        $array["Amount"] = $value->{"cupo_log.valor"};
        $array["Currency"] = $value->{"usuario.moneda"};
        $array["Assigned"] = $value->{"usuario2.nombre"};
        $array["AssignedId"] = $value->{"usuario2.usuario_id"};

        /* Asigna valores de un objeto a un array, manejando caso de transacción cero. */
        $array["DepositId"] = $value->{"cupo_log.recarga_id"};
        $array["NameBank"] = $value->{"cupo_log.nombre_banco2"};
        $array["transactionId"] = $value->{"cupo_log.numero_transaccion"};
        if ($array["transactionId"] == 0) {
            $array["transactionId"] = "";
        }

        /* verifica condiciones para permitir cancelar una transacción y la agrega a un arreglo. */
        $array["AllowCancelTransaction"] = false;

        if (floatval($value->{"cupo_log.valor"}) > 0 && $value->{"cupo_log.tipo_id"} == 'E' && strpos($value->{"cupo_log.observacion"}, 'Cancel') === false) {
            $array["AllowCancelTransaction"] = true;
        }

        array_push($final, $array);

    }
}


/* crea una respuesta exitosa sin errores para una solicitud. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;


/* Código para construir una respuesta JSON con posición, conteo total y datos finales. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $mandantes->count[0]->{".count"};
$response["data"] = $final;
