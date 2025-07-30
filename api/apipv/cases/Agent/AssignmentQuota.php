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
 * Procesa la asignación de cuotas para un agente.
 *
 * @param array $_REQUEST Datos de la solicitud HTTP, incluyendo:
 * @param string $id ID del usuario.
 * @param string $Name Nombre del usuario.
 * @param string $Type Tipo de transacción.
 * @param string $TransactionId ID de la transacción.
 * @param string $UserIdAgent ID del agente.
 * @param string $UserIdAgent2 ID del segundo agente.
 * @param string $CountrySelect País seleccionado.
 * @param string $MaxRows Número máximo de filas.
 * @param string $SkeepRows Número de filas a omitir.
 * @param string $FromDateLocal Fecha de inicio.
 * @param string $ToDateLocal Fecha de fin.
 *
 * @return array $response Respuesta con los siguientes valores:
 * -HasError: string Indica si hubo un error.
 * -AlertType: string Tipo de alerta.
 * -AlertMessage: string Mensaje de alerta.
 * -ModelErrors: string Errores del modelo.
 * -Data: string Datos procesados.
 * -pos: string Posición de inicio.
 * -total_count: string Conteo total de registros.
 * -data: string Datos finales.
 *
 * @throws Exception Si se detecta un perfil de usuario inusual.
 * @throws Exception Si el perfil del usuario no está permitido.
 */


/* obtiene datos de una solicitud HTTP para ser utilizados posteriormente. */
$id = $_REQUEST["id"];
$Name = $_REQUEST["Name"];
$Type = $_REQUEST["Type"];
$TransactionId = $_REQUEST["transactionId"];

$UserIdAgent = $_REQUEST["UserIdAgent"];

/* obtiene datos del usuario y parámetros de solicitud de una petición. */
$UserIdAgent2 = $_REQUEST["UserIdAgent2"];

$CountrySelect = $_REQUEST["CountrySelect"];

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* procesa fechas de inicio y fin desde una solicitud HTTP. */
if ($_REQUEST["dateFrom"] != "") {
    //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"])));

}
if ($_REQUEST["dateTo"] != "") {
    //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"])));

}


/* verifica si $SkeepRows está vacío y lo inicializa a '0'. */
$seguir = true;

if ($SkeepRows == "") {
    $SkeepRows = '0';
    $seguir = false;
}


/* Establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {

    $MaxRows = 100;
    $seguir = false;
}


/* Se crea un objeto Submenu y PerfilSubmenu usando datos de sesión. */
$Submenu = new Submenu("", "assignmentQuotaR", '3');

try {
    $PerfilSubmenu = new PerfilSubmenu($_SESSION["win_perfil"], $Submenu->getSubmenuId());

} catch (Exception $e) {
    /* Captura excepciones y crea un objeto PerfilSubmenu con datos específicos del usuario. */

    $PerfilSubmenu = new PerfilSubmenu('CUSTOM', $Submenu->getSubmenuId(), $_SESSION["usuario"]);
}


/* Valida el perfil del usuario antes de asignar un ID o lanzar una excepción. */
if ($id == "" && ($_SESSION["win_perfil"] == "USUONLINE" || $_SESSION["win_perfil"] == "CAJERO")) {
    throw new Exception("Inusual Detected", "11");

}


if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
    $id = $_SESSION["usuario"];
}


/* Asigna un ID de usuario basado en la sesión si está vacío. */
if ($id == "") {
    if ($_SESSION["win_perfil"] == "CONCESIONARIO" || $_SESSION["win_perfil"] == "CONCESIONARIO2" || $_SESSION["win_perfil"] == "CONCESIONARIO3") {
        $id = $_SESSION["usuario"];
    }


} else {

    /* Se crea un array de reglas basado en el perfil del usuario. */
    $rules = array();
    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* Verifica el perfil del usuario y agrega reglas a un array si coincide. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* Verifica el perfil del usuario y establece reglas de acceso en un array. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }

}

/* Se verifica el usuario y se agrega una regla al arreglo $rules. */
$CupoLog = new CupoLog();

$rules = [];

if ($_SESSION["usuario"] == 4089418) {

    array_push($rules, array("field" => "cupo_log.usucrea_id", "data" => 73737, "op" => "eq"));
}


/* Agrega reglas de filtrado si TransactionId o id no están vacíos. */
if ($TransactionId != "") {
    array_push($rules, array("field" => "cupo_log.numero_transaccion", "data" => $TransactionId, "op" => "eq"));
}


if ($id != "") {
    array_push($rules, array("field" => "cupo_log.usuario_id", "data" => "$id", "op" => "eq"));
}


/* Agrega reglas si los nombres y IDs son numéricos y no están vacíos. */
if ($Name != "" && is_numeric($Name)) {
    array_push($rules, array("field" => "cupo_log.usuario_id", "data" => "$Name", "op" => "eq"));
}

if ($UserIdAgent != "" && is_numeric($UserIdAgent)) {
    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => "$UserIdAgent", "op" => "eq"));
}


/* Agrega reglas a un array si las variables son no vacías y numéricas. */
if ($UserIdAgent2 != "" && is_numeric($UserIdAgent2)) {
    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => "$UserIdAgent2", "op" => "eq"));
}
if ($Name != "" && is_numeric($Name)) {
    array_push($rules, array("field" => "cupo_log.usuario_id", "data" => "$Name", "op" => "eq"));
}


/* Agrega reglas a un array si se cumplen condiciones específicas sobre país y fecha. */
if ($CountrySelect != "" && is_numeric($CountrySelect)) {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
}


if ($FromDateLocal != "") {
    array_push($rules, array("field" => "cupo_log.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));


}


/* añade reglas basadas en el tipo de usuario especificado. */
if ($TypeUser != "") {
    if ($TypeUser == "P") {
        array_push($rules, array("field" => "usuario_perfil2.perfil_id", "data" => "PUNTOVENTA ", "op" => "eq"));

    } elseif ($TypeUser == "A") {
        array_push($rules, array("field" => "usuario_perfil2.perfil_id", "data" => "'ADMIN','ADMIN2','CUSTOM' ", "op" => "in"));
    }


}


/* Agrega reglas de filtro dependiendo de la fecha y condiciones del país del usuario. */
if ($ToDateLocal != "") {
    array_push($rules, array("field" => "cupo_log.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
}

// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
}


// Si el usuario esta condicionado por el mandante y no es de Global

/* agrega condiciones a un arreglo basadas en variables de sesión. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}


/* Condiciona la adición de reglas según el perfil y región del usuario en sesión. */
if ($_SESSION['regionperfil'] != "0" && $_SESSION['regionperfil'] != null) {
    if ($_SESSION["win_perfil"] != "CONCESIONARIO" && $_SESSION["win_perfil"] != "CONCESIONARIO2" && $_SESSION["win_perfil"] != "CONCESIONARIO3" && $_SESSION["win_perfil"] != "PUNTOVENTA") {

        array_push($rules, array("field" => "usuario_perfil.region", "data" => $_SESSION['regionperfil'], "op" => "eq"));
    }
}


/* Genera una consulta SQL con condiciones de selección y agrupación según el tipo. */
$select = "cupo_log.*,usuario.usuario_id,usuario.nombre,usuario2.nombre,usuario.moneda";
$grouping = "";
if ($Type == '1') {
    $select = "cupo_log.*,SUM(cupo_log.valor) valor,usuario.usuario_id,usuario.nombre,usuario2.nombre,usuario.moneda";

    $grouping = "usuario2.usuario_id,DATE_FORMAT(cupo_log.fecha_crea,'%Y-%m-%d'),cupo_log.tipo_id";
}

// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


/* Se crea un filtro y se obtienen registros de CupoLog con parámetros específicos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$CupoLog = new CupoLog();


$mandantes = $CupoLog->getCupoLogsCustom($select, "cupo_log.cupolog_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);


/* Decodifica un JSON en PHP y prepara un arreglo vacío para datos finales. */
$mandantes = json_decode($mandantes);

$final = [];

foreach ($mandantes->data as $key => $value) {
    if ($Type == '1') {


        /* inicializa un arreglo y lo llena con datos de usuario y fecha. */
        $array = [];

        $array["Id"] = '';
        $array["UserId"] = $value->{"usuario.usuario_id"};
        $array["UserName"] = $value->{"usuario.nombre"};

        $array["Date"] = date("Y-m-d", strtotime($value->{"cupo_log.fecha_crea"}));

        /* asigna valores de un objeto a un array con claves específicas. */
        $array["TypeQuota"] = $value->{"cupo_log.tipocupo_id"};
        $array["TypeTransaction"] = $value->{"cupo_log.tipo_id"};
        $array["Amount"] = $value->{".valor"};
        $array["Currency"] = $value->{"usuario.moneda"};
        $array["Assigned"] = $value->{"usuario2.nombre"};
        $array["DepositId"] = $value->{"cupo_log.recarga_id"};

        /* almacena datos bancarios en un array y verifica el ID de transacción. */
        $array["NameBank"] = $value->{"cupo_log.nombre_banco2"};
        $array["transactionId"] = $value->{"cupo_log.numero_transaccion"};
        if ($array["transactionId"] == 0) {
            $array["transactionId"] = "";
        }
        array_push($final, $array);
    } else {


        /* crea un array con datos extraídos de un objeto en PHP. */
        $array = [];

        $array["Id"] = $value->{"cupo_log.cupolog_id"};
        $array["UserId"] = $value->{"usuario.usuario_id"};
        $array["UserName"] = $value->{"usuario.nombre"};

        $array["Date"] = $value->{"cupo_log.fecha_crea"};

        /* Asigna valores de un objeto a un array con claves específicas. */
        $array["TypeQuota"] = $value->{"cupo_log.tipocupo_id"};
        $array["TypeTransaction"] = $value->{"cupo_log.tipo_id"};
        $array["Amount"] = $value->{"cupo_log.valor"};
        $array["Currency"] = $value->{"usuario.moneda"};
        $array["Assigned"] = $value->{"usuario2.nombre"};
        $array["DepositId"] = $value->{"cupo_log.recarga_id"};

        /* asigna valores de un objeto a un array y lo agrega a otro array. */
        $array["NameBank"] = $value->{"cupo_log.nombre_banco2"};
        $array["transactionId"] = $value->{"cupo_log.numero_transaccion"};
        if ($array["transactionId"] == 0) {
            $array["transactionId"] = "";
        }
        array_push($final, $array);

    }

}


/* Código establece respuesta con estado exitoso y datos finales, sin errores ni alertas. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;


/* asigna valores a un array de respuesta en PHP. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $mandantes->count[0]->{".count"};
$response["data"] = $final;
