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
 * Accounting/GetExpenses
 *
 * Obtención de Egresos Filtrados y Ordenados
 *
 * Este recurso obtiene datos de egresos filtrados y ordenados desde una base de datos, utilizando
 * una serie de reglas dinámicas basadas en parámetros como el nivel de usuario, fecha, consecutivo y otros filtros.
 * Los datos procesados incluyen información de egresos, cuentas, productos y otros detalles relacionados.
 *
 * @param object $params : Objeto que contiene los parámetros necesarios para la consulta de egresos.
 * @param string $params->Id : El ID del egreso, utilizado para consultas específicas.
 * @param int $params->MaxRows : Número máximo de filas a obtener en la consulta.
 * @param int $params->OrderedItem : Criterio de ordenación de los resultados.
 * @param int $params->SkeepRows : Número de filas a omitir en los resultados (para paginación).
 * @param string $params->CreditCards : Parámetro relacionado con tarjetas de crédito para filtrar los egresos.
 * @param string $params->Nivel : Nivel de usuario utilizado para aplicar reglas adicionales en los filtros.
 * @param string $params->Consecutive : Consecutivo utilizado para filtrar los egresos por usuario y consecutivo.
 * @param string $params->dateFrom : Fecha de inicio del rango de búsqueda de egresos.
 * @param string $params->dateTo : Fecha de finalización del rango de búsqueda de egresos.
 *
 * @param int $MaxRows : Número máximo de filas a mostrar en los resultados.
 * @param int $OrderedItem : Criterio de ordenación de los resultados.
 * @param int $SkeepRows : Número de filas a omitir en los resultados (para paginación).
 * @param string $json : Filtro de búsqueda en formato JSON utilizado para obtener los datos de los egresos.
 * @param object $UsuarioMandante : Objeto que gestiona los datos del usuario mandante basado en la sesión.
 * @param string $dateFrom : Fecha de inicio del rango de búsqueda de egresos.
 * @param string $dateTo : Fecha de finalización del rango de búsqueda de egresos.
 * @param string $Nivel : Nivel del usuario que determina qué reglas se aplican en el filtro.
 * @param string $Consecutive : Consecutivo del egreso utilizado para crear reglas de búsqueda.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna array vacío.
 *  - *Data* (array): Contiene los detalles de los egresos obtenidos, estructurados con ID, descripción, concepto y otros datos relevantes.
 *  - *pos* (int): Número de posiciones a saltar (para paginación).
 *  - *total_count* (int): Contador total de egresos disponibles en la base de datos.
 *
 * Ejemplo de respuesta en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["ModelErrors"] = [];
 *
 * @throws Exception Si ocurre un error al procesar los datos o al acceder a la base de datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */



/* Se crea un objeto UsuarioMandante y se definen parámetros como Id y MaxRows. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$Id = $params->Id;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* Código que recibe parámetros y maneja fechas para procesar datos de tarjetas de crédito. */
$SkeepRows = $params->SkeepRows;

$CreditCards = $_REQUEST["CreditCards"];
$Nivel = $_REQUEST["Nivel"];
$Consecutive = $_REQUEST["Consecutive"];


if ($_REQUEST["dateTo"] != "") {
    $dateTo = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));
}


/* convierte una fecha solicitada en formato estándar, considerando una zona horaria. */
if ($_REQUEST["dateFrom"] != "") {
    $dateFrom = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}


if ($dateFrom == "") {
    $dateFrom = date("Y-m-d 00:00:00", strtotime(time() . $timezone . ' hour '));
}

/* establece una fecha por defecto y obtiene un parámetro de solicitud. */
if ($dateTo == "") {
    $dateTo = date("Y-m-d 23:59:59", strtotime(time() . ' +0 day' . $timezone . ' hour '));

}


$MaxRows = $_REQUEST["count"];

/* asigna un valor a $SkeepRows según la solicitud HTTP recibida. */
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


/* Se verifica si $Nivel no es "C" para agregar una regla al array. */
$rules = [];


if ($Nivel != "C") {
    //array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

}


/* analiza un string y agrega reglas basadas en usuario y consecutivo. */
if ($Consecutive != "") {
    $base = explode("E", $Consecutive)[1];
    $usuario = explode("-", $base)[0];
    $consecutivo = explode("-", $base)[1];
    array_push($rules, array("field" => "egreso.consecutivo", "data" => $consecutivo, "op" => "eq"));
    array_push($rules, array("field" => "egreso.usuario_id", "data" => $usuario, "op" => "eq"));

}


/* Añade condiciones de fecha a un arreglo de reglas según entradas no vacías. */
if ($dateFrom != "") {
    array_push($rules, array("field" => "egreso.fecha_crea", "data" => "$dateFrom", "op" => "ge"));

}
if ($dateTo != "") {
    array_push($rules, array("field" => "egreso.fecha_crea", "data" => "$dateTo", "op" => "le"));

}


/* Condiciona reglas según el perfil de usuario en la sesión actual. */
if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
    array_push($rules, array("field" => "egreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
} elseif ($_SESSION["win_perfil2"] == "CAJERO") {
    array_push($rules, array("field" => "egreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
} else {

    /* verifica el perfil de usuario y añade reglas a un array. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* Verifica el perfil del usuario y agrega reglas a un arreglo condicionalmente. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* verifica un perfil de sesión y configura reglas para un concesionario. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }
    // Si el usuario esta condicionado por País

    /* añade reglas basadas en condiciones de sesión del usuario. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario_punto.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario_punto.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Condición que agrega regla basada en sesión si mandanteLista no está vacío. */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario_punto.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }
}


/* Código para obtener datos de egresos filtrados y ordenados desde una base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Egreso = new Egreso();

$data = $Egreso->getEgresosCustom("  egreso.*,producto_tercero.*,usuario_punto.nombre,usuario_punto.login,usuario_cajero.nombre,usuario_cajero.login,cuenta_producto.*,cuenta_concepto.* ", "egreso.egreso_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* Decodifica un JSON y inicializa un arreglo vacío para almacenar datos procesados. */
$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* inicializa un array y asigna valores de un objeto a sus claves. */
    $array = [];
    $array = [];


    $array["Id"] = $value->{"egreso.egreso_id"};
    $array["Consecutive"] = "E" . $value->{"egreso.usuario_id"} . "-" . $value->{"egreso.consecutivo"};

    /* Se asignan valores a un arreglo desde propiedades de un objeto. */
    $array["BetShop"] = $value->{"usuario_punto.nombre"};
    $array["UserCreated"] = $value->{"usuario_cajero.nombre"};
    $array["Description"] = $value->{"egreso.descripcion"};


    $array["Concept"] = $value->{"concepto.descripcion"};

    /* Asignación de valores a un array basado en condiciones del objeto $value. */
    $array["Account"] = "";
    $array["Reference"] = $value->{"egreso.documento"};
    $array["Document"] = $value->{"egreso.documento"};
    $array["ProvidersThird"] = $value->{"egreso.proveedorterc_id"};

    if ($value->{"egreso.productoterc_id"} != "" && $value->{"egreso.productoterc_id"} != "0") {
        $array["Description"] = $value->{"producto_tercero.descripcion"};
        $array["Account"] = $value->{"cuenta_producto.referencia"};

    }


    /* asigna valores a un arreglo basado en condiciones específicas del objeto. */
    if ($value->{"egreso.concepto_id"} != "" && $value->{"egreso.concepto_id"} != "0") {
        $array["Account"] = $value->{"cuenta_concepto.referencia"};

    }
    $array["Amount"] = $value->{"egreso.valor"};
    $array["Tax"] = 0;

    /* inicializa un array y lo agrega a un array final. */
    $array["Retraction"] = 0;
    $array["CreatedLocalDate"] = $value->{"egreso.fecha_crea"};


    array_push($final, $array);


}


/* crea una respuesta estructurada con estado de éxito y datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* Asigna posiciones, cuenta total y datos finales a la respuesta en formato JSON. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $clasificadores->count[0]->{".count"};
$response["data"] = $final;