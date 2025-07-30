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
 * UserManagement/GetUsersAffiliates
 *
 * Obtiene la lista de afiliados de un usuario basado en filtros y parámetros de paginación.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param int $params->Id ID del usuario.
 * @param int $params->MaxRows Número máximo de filas a retornar.
 * @param int $params->OrderedItem Orden de los elementos.
 * @param int $params->SkeepRows Número de filas a omitir.
 *
 * @return array $response Respuesta en formato JSON con los siguientes valores:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta generada.
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo, si los hay.
 *  - data (array): Datos de los afiliados.
 *  - pos (int): Posición inicial de los datos.
 *  - total_count (int): Conteo total de registros.
 */


/* Se crea un objeto Usuario y se decodifica un JSON recibido del input. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);


$Id = $params->Id;

/* obtiene parámetros de entrada y configura variables para consulta de datos. */
$FromId = $_REQUEST["FromId"];

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];

/* asigna un valor a $SkeepRows basado en parámetros de solicitud. */
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* verifica el perfil del usuario y asigna un ID si es válido. */
if ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO") {
    $FromId = $_SESSION["usuario"];
}


$rules = [];


/* Agrega condiciones a un array si $Id o $FromId no están vacíos. */
if ($Id != "") {
    array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Id", "op" => "eq"));
}

if ($FromId != "") {
    array_push($rules, array("field" => "registro.afiliador_id", "data" => "$FromId", "op" => "eq"));
}

/* Agrega una regla para filtrar afiliador_id y procesa una fecha de entrada. */
array_push($rules, array("field" => "registro.afiliador_id", "data" => "0", "op" => "ne"));


if ($_REQUEST["dateFrom"] != "") {
    $dateFrom = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}


/* procesa fechas recibidas, ajustando y añadiéndolas a un arreglo de reglas. */
if ($_REQUEST["dateTo"] != "") {
    $dateTo = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
}


if ($dateFrom != "") {
    array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateFrom", "op" => "ge"));

}

/* agrega reglas de filtro basadas en condiciones específicas. */
if ($dateTo != "") {
    array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateTo", "op" => "le"));

}


if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {

    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));

}


/* agrega reglas a un array según el perfil de sesión del usuario. */
if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {

    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));

}


if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {

    array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));

}


// Si el usuario esta condicionado por País

/* Agrega reglas basadas en la sesión del usuario para filtrado de datos. */
if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
}


// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {
    /* Verifica si "mandanteLista" no está vacío y añade reglas al array. */


    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}

/* Se crea un filtro y se obtiene información de usuarios en formato JSON. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),registro.afiliador_id,usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,usuario.nombre,usuario.fecha_ult,usuario.fecha_primerdeposito,usuario.monto_primerdeposito  ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

$usuarios = json_decode($usuarios);


/* Se inicializan un arreglo vacío para usuarios y otro para afiliadores. */
$usuariosFinal = [];

$arrayAfiliadores = array();
foreach ($usuarios->data as $key => $value) {


    /* Verifica si un afiliador está en el array y lo agrega si no está. */
    if ($arrayAfiliadores[$value->{"registro.afiliador_id"}] == '') {

        $Usuario = new Usuario($value->{"registro.afiliador_id"});
        $arrayAfiliadores[$Usuario->usuarioId] = $Usuario->nombre;
    }

    $Islocked = false;


    /* verifica el estado de un usuario y crea un array con su ID. */
    if ($value->{"usuario.estado"} == "I") {
        $Islocked = true;
    }

    $array = [];

    $array["id"] = $value->{"usuario.usuario_id"};

    /* asigna valores de un objeto a un arreglo asociativo en PHP. */
    $array["Id"] = $value->{"usuario.usuario_id"};
    $array["Name"] = $value->{"usuario.nombre"};
    //$array["Name"] = $value->{"usuario.nombre"};
    $array["State"] = $value->{"usuario.estado"};


    $array["NameAffiliate"] = $arrayAfiliadores[$value->{"registro.afiliador_id"}];

    /* Asignación de datos de usuario y verificación de fecha de primer depósito en un array. */
    $array["BetShopName"] = $arrayAfiliadores[$value->{"registro.afiliador_id"}];

    $array["UserName"] = $value->{"usuario.login"};
    $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};
    $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};

    if ($value->{"usuario.fecha_primerdeposito"} == "null" || $value->{"usuario.fecha_primerdeposito"} == null || $value->{"usuario.fecha_primerdeposito"} == '') {
        $array["FirstDepositDate"] = null;
    } else {
        /* Asignación de la fecha del primer depósito a un array si no se cumple una condición. */

        $array["FirstDepositDate"] = $value->{"usuario.fecha_primerdeposito"};
    }


    /* Asigna '0' al primer depósito si está vacío o nulo, luego lo añade a un array. */
    if ($value->{"usuario.monto_primerdeposito"} == "null" || $value->{"usuario.monto_primerdeposito"} == null || $value->{"usuario.monto_primerdeposito"} == '') {
        $array["FirstDepositValue"] = '0';
    } else {
        $array["FirstDepositValue"] = $value->{"usuario.monto_primerdeposito"};
    }


    array_push($usuariosFinal, $array);

}


/* Código que configura una respuesta exitosa sin errores ni mensajes, incluyendo posición. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

// $response["Data"] = $usuariosFinal;

/* asigna el conteo total de usuarios y datos finales a un arreglo. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $usuarios->count[0]->{".count"};
$response["data"] = $usuariosFinal;
