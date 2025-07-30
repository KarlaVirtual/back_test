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
 * Dashboard/GetActiveClients
 *
 * Obtener los usuarios activos.
 *
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param string $params->ToDateLocal Fecha final del rango en formato "Y-m-d H:i:s".
 * @param string $params->FromDateLocal Fecha inicial del rango en formato "Y-m-d H:i:s".
 * @param string $params->Region Región seleccionada para filtrar los datos.
 * @param string $params->CurrencyId Identificador de la moneda para conversión.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Orden de los elementos.
 * @param int $params->SkeepRows Número de filas a omitir.
 * 
 *
 * @return array $response Respuesta con los siguientes datos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "error").
 * - AlertMessage (string): Mensaje de alerta.
 * - Data (array): Contiene estadísticas como:
 *   - DepositTotalCount (int): Total de depósitos realizados.
 *   - DepositTotalAmount (float): Monto total de depósitos.
 *   - WithDrawalTotalCount (int): Total de retiros realizados.
 *   - WithDrawalTotalAmount (float): Monto total de retiros.
 *
 * @throws Exception Si ocurre un error en la conversión de moneda o en las consultas a la base de datos.
 */


/* Código PHP que crea un objeto y obtiene una fecha formateada a partir de entrada JSON. */
$UsuarioRecarga = new UsuarioRecarga();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));

/* Se convierten fechas a formato local y se asignan parámetros de región y moneda. */
$ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $params->ToDateLocal)));
$FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));

$Region = $params->Region;
$Region = $params->Country;
$CurrencyId = $params->CurrencyId;


/* asigna parámetros y define reglas para filtrar datos en una consulta. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$rules = [];

array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

/* Agrega reglas a un array basadas en condiciones de fechas y región. */
array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
//array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));
//array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));

if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}


/* Se agregan reglas a un array basado en condiciones específicas de moneda y perfil. */
if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}


if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
    array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
}


/* agrega reglas según el perfil de usuario en la sesión. */
if ($_SESSION["win_perfil2"] == "CAJERO") {
    array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
}

if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
}


/* Se añaden reglas basadas en el perfil del usuario en la sesión. */
if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
}
if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
    array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
}

// Si el usuario esta condicionado por País

/* Condiciona reglas según país y mandante del usuario en sesión. */
if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
}

// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {
    /* añade una regla si 'mandanteLista' no está vacía ni es -1. */


    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}


/* Agrega reglas de filtrado para un usuario y las codifica en JSON. */
array_push($rules, array("field" => "usuario.pais_id", "data" => '1', "op" => "ne"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* establece valores predeterminados para variables vacías de pedido y filas máximas. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000000;
}


/* obtiene y decodifica datos de recargas de usuarios, configurando la localización. */
$usuarios = $UsuarioRecarga->getUsuarioRecargasCustom("COUNT(*) count,SUM(usuario_recarga.valor) valor,usuario.moneda", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda", "", false);

$usuarios = json_decode($usuarios);
setlocale(LC_ALL, 'czech');

$valor_convertido = 0;

/* calcula un total y convierte monedas según el perfil del usuario. */
$total = 0;
foreach ($usuarios->data as $key => $value) {

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO" || $_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        $converted_currency = round($value->{".valor"}, 2);
        $valor_convertido = $valor_convertido + $converted_currency;
    } else {
        $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $_SESSION["monedaReporte"], round($value->{".valor"}, 2));
        $valor_convertido = $valor_convertido + $converted_currency;
    }

    $total = $total + $value->{".count"};

}


/* crea un array de respuesta con información sobre un depósito exitoso. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "DepositTotalCount" => $total,
    "DepositTotalAmount" => $valor_convertido,

);


/* Crea una instancia de CuentaCobro y procesa datos JSON de entrada. */
$CuentaCobro = new CuentaCobro();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = date("Y-m-d 23:59:50", strtotime(str_replace(" - ", " ", $params->ToDateLocal)));

/* formatea una fecha y asigna el valor máximo de filas. */
$FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
//$Region = $params->Region;
//$CurrencyId = $params->CurrencyId;
//$IsNewRegistered = $params->IsNewRegistered;

$MaxRows = $params->MaxRows;

/* Inicializa $SkeepRows en 0 si no se proporciona un valor en $params. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}

/* Configuración de reglas para filtrar cuentas por estado y fechas de pago. */
$daydimensionFechaPorPago = false;
$rules = [];
array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));
array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));
//array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));
//array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));
$daydimensionFechaPorPago = true;


/* Agrega reglas para filtrar datos según región y moneda en un arreglo. */
if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}

if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}


/* Agrega reglas de filtrado según condiciones de registro nuevo y perfil de usuario. */
if ($IsNewRegistered) {
    array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

}

if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
    array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
}


/* agrega reglas de acceso basadas en el perfil de usuario en sesión. */
if ($_SESSION["win_perfil2"] == "CAJERO") {
    array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
}


if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
}


/* agrega reglas basadas en el perfil del usuario en sesión. */
if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
}

if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
    array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
}

// Si el usuario esta condicionado por País

/* Añade reglas de filtro basadas en condiciones de sesión del usuario. */
if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
}

// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {
    /* Añade una regla si "mandanteLista" no está vacío o es diferente de "-1". */


    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}

// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


/* crea un filtro y obtiene datos de cuentas de cobro en formato JSON. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$cuentas = $CuentaCobro->getCuentasCobroCustom("COUNT(*) count,SUM(cuenta_cobro.valor) valor,usuario.moneda", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda", "", false, $daydimensionFechaPorPago, true);

$cuentas = json_decode($cuentas);


/* calcula el total de retiros, considerando diferentes perfiles y conversiones de moneda. */
$valor_convertidoretiros = 0;
$totalretiros = 0;
foreach ($cuentas->data as $key => $value) {

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO" || $_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        $converted_currency = round($value->{".valor"}, 2);
        $valor_convertidoretiros = $valor_convertidoretiros + $converted_currency;
    } else {
        $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $_SESSION["monedaReporte"], round($value->{".valor"}, 2));
        $valor_convertidoretiros = $valor_convertidoretiros + $converted_currency;

    }
    $totalretiros = $totalretiros + $value->{".count"};

}


/* define una respuesta con información sobre retiros exitosos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "WithDrawalTotalCount" => $totalretiros,
    "WithDrawalTotalAmount" => $valor_convertidoretiros,

);

$response = array(
    array(
        "id" => 1,
        "mens" => "Depositos",
        "number" => round($valor_convertido, 2)
    ),
    array(
        "id" => 2,
        "mens" => "Retiros",
        "number" => round($valor_convertidoretiros, 2)
    ),
    array(
        "id" => 3,
        "mens" => "Neto",
        "number" => round(($valor_convertido - $valor_convertidoretiros), 2)
    )
    /*,
        array(
            "id" => 3,
            "name" => "CANTIDAD DE APUESTAS",
            "icon" => "icon-money",
            "value" => $total
        )*/
);
