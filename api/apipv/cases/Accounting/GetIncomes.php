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
 * Accounting/GetIncomes
 *
 * Obtiene ingresos personalizados con base en las reglas definidas en el sistema.
 *
 * Este recurso recibe parámetros de entrada, como identificadores de ingresos, fechas, niveles de acceso, y otros parámetros
 * necesarios para filtrar y obtener los ingresos de un usuario o entidad específica. Los resultados se filtran por reglas de
 * sesión y perfil del usuario, y se devuelven en formato JSON con información detallada.
 *
 * @param string $params->Id : Identificador del ingreso.
 * @param int $params->MaxRows : Número máximo de filas a obtener.
 * @param int $params->OrderedItem : Parámetro para ordenar los resultados.
 * @param int $params->SkeepRows : Número de filas a omitir para la paginación.
 * @param string $params->CreditCards : Datos de tarjetas de crédito asociadas.
 * @param string $params->Nivel : Nivel de acceso del usuario (por ejemplo, "C").
 * @param string $params->Consecutive : Consecutivo del ingreso.
 * @param string $params->dateTo : Fecha final del rango de búsqueda (formato 'Y-m-d H:i:s').
 * @param string $params->dateFrom : Fecha inicial del rango de búsqueda (formato 'Y-m-d H:i:s').
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna un array vacío.
 *  - *Data* (array): Array con los resultados de los ingresos filtrados, con cada elemento conteniendo:
 *      - *Id* (int): Identificador único del ingreso.
 *      - *Consecutive* (string): Consecutivo del ingreso.
 *      - *BetShop* (string): Nombre del punto de venta o betshop.
 *      - *UserCreated* (string): Nombre del usuario que creó el ingreso.
 *      - *Description* (string): Descripción del ingreso.
 *      - *Concept* (string): Concepto del ingreso.
 *      - *Account* (string): Cuenta asociada al ingreso.
 *      - *Reference* (string): Referencia del ingreso.
 *      - *Document* (string): Documento asociado al ingreso.
 *      - *ProvidersThird* (int): ID del proveedor tercero.
 *      - *Amount* (float): Monto del ingreso.
 *      - *Tax* (float): Impuesto asociado al ingreso (generalmente 0).
 *      - *Retraction* (float): Retracción asociada al ingreso (generalmente 0).
 *      - *CreatedLocalDate* (string): Fecha de creación del ingreso.
 *
 * Objeto en caso de error:
 *  - *code* (int): Código de error.
 *  - *result* (string): Mensaje de error.
 *  - *data* (array): Array vacío.
 *
 * @throws Exception Si ocurre un error durante el proceso de obtención de los ingresos.
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */



/* instancia un objeto y obtiene parámetros de una sesión y variable. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


$Id = $params->Id;

$MaxRows = $params->MaxRows;

/* extrae parámetros y datos de solicitudes relacionadas con artículos y tarjetas de crédito. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$CreditCards = $_REQUEST["CreditCards"];
$Nivel = $_REQUEST["Nivel"];
$Consecutive = $_REQUEST["Consecutive"];


/* obtiene y formatea fechas de entrada con un huso horario específico. */
if ($_REQUEST["dateTo"] != "") {
    $dateTo = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));
}

if ($_REQUEST["dateFrom"] != "") {
    $dateFrom = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}


/* establece valores predeterminados para fechas si están vacías. */
if ($dateFrom == "") {
    $dateFrom = date("Y-m-d 00:00:00", strtotime(time() . $timezone . ' hour '));
}
if ($dateTo == "") {
    $dateTo = date("Y-m-d 23:59:59", strtotime(time() . ' +0 day' . $timezone . ' hour '));

}


/* establece valores para la paginación basada en parámetros de solicitud. */
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Se inicializa un arreglo de reglas condicionalmente, si el nivel no es "C". */
$rules = [];

if ($Nivel != "C") {
//array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

}


/* procesa una cadena para extraer valores y crear reglas de evaluación. */
if ($Consecutive != "") {
    $base = explode("I", $Consecutive)[1];
    $usuario = explode("-", $base)[0];
    $consecutivo = explode("-", $base)[1];
    array_push($rules, array("field" => "ingreso.consecutivo", "data" => $consecutivo, "op" => "eq"));
    array_push($rules, array("field" => "ingreso.usuario_id", "data" => $usuario, "op" => "eq"));

}


/* Agrega reglas de fecha a un arreglo según las condiciones establecidas. */
if ($dateFrom != "") {
    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => "$dateFrom", "op" => "ge"));

}
if ($dateTo != "") {
    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => "$dateTo", "op" => "le"));

}


/* Condiciona la adición de reglas según el perfil del usuario en sesión. */
if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
    array_push($rules, array("field" => "ingreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
} elseif ($_SESSION["win_perfil2"] == "CAJERO") {
    array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
} else {

    /* Verifica el perfil del usuario y agrega reglas a un array si es concesionario. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* Verifica el perfil y establece reglas para concesionarios específicos en la sesión. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* verifica una condición de sesión y agrega reglas a un array. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }
// Si el usuario esta condicionado por País

    /* agrega reglas a un arreglo basado en condiciones de sesión del usuario. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario_punto.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
// Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario_punto.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Agrega reglas a un array si la sesión 'mandanteLista' tiene un valor válido. */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario_punto.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }
}


/* Se crea un filtro en formato JSON para obtener ingresos personalizados de una base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Ingreso = new Ingreso();

$data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.*,usuario_punto.nombre,usuario_punto.login,usuario_cajero.nombre,usuario_cajero.login,cuenta_producto.*,cuenta_concepto.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* decodifica datos JSON y los almacena en un array vacío llamado $final. */
$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* Se crea un array con datos de ingreso, usuario y betshop formateados. */
    $array = [];


    $array["Id"] = $value->{"ingreso.ingreso_id"};
    $array["Consecutive"] = "I" . $value->{"ingreso.usuario_id"} . "-" . $value->{"ingreso.consecutivo"};
    $array["BetShop"] = $value->{"usuario_punto.nombre"};

    /* asigna valores de un objeto a un array asociativo en PHP. */
    $array["UserCreated"] = $value->{"usuario_cajero.nombre"};
    $array["Description"] = $value->{"ingreso.descripcion"};


    $array["Concept"] = $value->{"concepto.descripcion"};
    $array["Account"] = "";

    /* Asigna valores a un array basado en condiciones de un objeto $value. */
    $array["Reference"] = $value->{"ingreso.documento"};
    $array["Document"] = $value->{"ingreso.documento"};
    $array["ProvidersThird"] = $value->{"ingreso.proveedorterc_id"};

    if ($value->{"ingreso.productoterc_id"} != "" && $value->{"ingreso.productoterc_id"} != "0") {
        $array["Description"] = $value->{"producto_tercero.descripcion"};
        $array["Account"] = $value->{"cuenta_producto.referencia"};

    }


    /* Condiciona la asignación de cuenta y establece monto y impuesto a cero. */
    if ($value->{"ingreso.concepto_id"} != "" && $value->{"ingreso.concepto_id"} != "0") {
        $array["Account"] = $value->{"cuenta_concepto.referencia"};

    }
    $array["Amount"] = $value->{"ingreso.valor"};
    $array["Tax"] = 0;

    /* Se asignan valores a un array y se añade a un array final. */
    $array["Retraction"] = 0;
    $array["CreatedLocalDate"] = $value->{"ingreso.fecha_crea"};

    array_push($final, $array);


}


/* construye una respuesta exitosa sin errores para una respuesta JSON. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* Asigna valores a la respuesta usando datos de paginación y conteo de clasificadores. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $clasificadores->count[0]->{".count"};
$response["data"] = $final;
