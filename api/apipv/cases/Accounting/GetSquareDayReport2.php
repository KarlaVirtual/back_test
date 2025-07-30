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
 * Obtener los componentes de caja del punto de venta
 *
 * @param object $params Objeto que contiene los parámetros de la solicitud:
 * @param int $params ->Id ID del proveedor.
 * @param int $params ->MaxRows Número máximo de filas a obtener.
 * @param int $params ->OrderedItem Ítem ordenado.
 * @param int $params ->SkeepRows Número de filas a omitir.
 *
 * @return array Respuesta con los siguientes datos:
 *  - bool $response["HasError"] Indica si hubo un error.
 *  - string $response["AlertType"] Tipo de alerta.
 *  - string $response["AlertMessage"] Mensaje de alerta.
 *  - array $response["ModelErrors"] Errores del modelo.
 *  - array $response["Data"] Datos finales.
 *  - int $response["pos"] Posición de inicio.
 *  - int $response["total_count"] Conteo total de datos.
 *  - array $response["data"] Datos finales.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */


/* inicializa un objeto UsuarioMandante y obtiene parámetros de la solicitud. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$Id = $params->Id;

$Nivel = $_REQUEST["Nivel"];
$BetShopId = $_REQUEST["BetShopId"];

/* procesa fechas ingresadas por el usuario y ajusta el formato y hora. */
$UserId = $_REQUEST["UserId"];

if ($_REQUEST["dateTo"] != "") {
    $dateTo = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));
}

if ($_REQUEST["dateFrom"] != "") {
    $dateFrom = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}


/* configura fechas por defecto si no se proporcionan. */
if ($dateFrom == "") {
    $dateFrom = date("Y-m-d 00:00:00", strtotime(time() . $timezone . ' hour '));
}
if ($dateTo == "") {
    $dateTo = date("Y-m-d 23:59:59", strtotime(time() . ' +0 day' . $timezone . ' hour '));

}


/* asigna valores de parámetros y solicitudes HTTP a variables correspondientes. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* asigna valores predeterminados si las variables están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un límite de filas y define reglas según el nivel. */
if ($MaxRows == "") {
    $MaxRows = 1000;
}

$rules = [];
if ($Nivel != "C") {
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
}


/* Agrega condiciones a un arreglo según si variables están definidas. */
if ($BetShopId != "") {
    array_push($rules, array("field" => "usuario_cierrecaja.usuario_id", "data" => $BetShopId, "op" => "eq"));
}

if ($UserId != "") {
    array_push($rules, array("field" => "usuario_cierrecaja.usuario_id", "data" => $UserId, "op" => "eq"));
}


/* Agrega reglas de filtrado por fechas a un array si las fechas no están vacías. */
if ($dateFrom != "") {
    array_push($rules, array("field" => "usuario_cierrecaja.fecha_cierre", "data" => "$dateFrom", "op" => "ge"));

}
if ($dateTo != "") {
    array_push($rules, array("field" => "usuario_cierrecaja.fecha_cierre", "data" => "$dateTo", "op" => "le"));

}


/* Añade reglas basadas en el perfil de usuario en la sesión. */
if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
    array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
} elseif ($_SESSION["win_perfil2"] == "CAJERO") {
    array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
} else {

    /* asigna reglas basadas en la sesión de usuario "CONCESIONARIO". */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* Se configuran reglas de acceso basadas en el perfil y usuario de sesión. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* verifica perfil de sesión y añade reglas para concesionario. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }
    // Si el usuario esta condicionado por País

    /* agrega reglas a un arreglo según condiciones de sesión del usuario. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Agrega una regla si "mandanteLista" no está vacío ni es "-1". */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }
}

// array_push($rules, array("field" => "egreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
//array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));


/* Crea un filtro JSON y obtiene datos de cierre de caja de usuarios. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$UsuarioCierrecaja = new UsuarioCierrecaja();

$data = $UsuarioCierrecaja->getUsuarioCierrecajasCustom("  usuario.login,usuario_cierrecaja.* ", "usuario_cierrecaja.fecha_cierre asc,usuario_cierrecaja.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* decodifica un JSON y inicializa un array vacío para almacenar datos. */
$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* Código que crea un arreglo asociativo con datos de usuario extraídos de un objeto. */
    $array = [];


    $array["Id"] = $value->{"usuario_cierrecaja.usucierrecaja_id"};
    $array["User"] = $value->{"usuario_cierrecaja.usuario_id"};
    $array["UserName"] = $value->{"usuario.login"};

    /* asigna datos a un arreglo a partir de un objeto. */
    $array["UserName"] = $value->{"usuario.login"};
    $array["Date"] = date('Y-m-d', strtotime($value->{"usuario_cierrecaja.fecha_cierre"}));
    $array["AmountBegin"] = $value->{"usuario_cierrecaja.dinero_inicial"};
    $array["ProperIncomes"] = $value->{"usuario_cierrecaja.ingresos_propios"};
    $array["ProperExpenses"] = $value->{"usuario_cierrecaja.egresos_propios"};
    $array["ProductsIncomes"] = $value->{"usuario_cierrecaja.ingresos_productos"};

    /* Asigna valores de un objeto a un array y calcula el total. */
    $array["ProductsExpenses"] = $value->{"usuario_cierrecaja.egresos_productos"};
    $array["OthersIncomes"] = $value->{"usuario_cierrecaja.ingresos_otros"};
    $array["OthersExpenses"] = $value->{"usuario_cierrecaja.egresos_otros"};
    $array["IncomesCreditCards"] = $value->{"usuario_cierrecaja.ingresos_tarjetacredito"};
        /* Resta gastos y añade el resultado a un arreglo final. */
    $array["Total"] = $array["AmountBegin"] + $array["ProperIncomes"] + $array["ProductsIncomes"] + $array["OthersIncomes"]
        - $array["ProperExpenses"] - $array["ProductsExpenses"] - $array["OthersExpenses"] - $array["IncomesCreditCards"];

    array_push($final, $array);


}


/* prepara una respuesta sin errores, listando datos y mensajes de alerta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

/* asigna valores a un arreglo de respuesta en PHP. */
$response["Data"] = $final;
$response["pos"] = $SkeepRows;
$response["total_count"] = $data->count[0]->{".count"};
$response["data"] = $final;
