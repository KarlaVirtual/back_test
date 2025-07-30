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
 * Accounting/GetExpensesToday
 *
 * Obtener Egresos de Cierre de Caja
 *
 * Esta función obtiene los egresos filtrados de acuerdo con los parámetros de la sesión del usuario,
 * el perfil y el cierre de caja especificado. Los egresos se retornan con detalles relacionados
 * como concepto, proveedor, documento y otros parámetros asociados.
 *
 * @param string $CloseBoxId : Identificador del cierre de caja. Si no se proporciona, se utilizan los datos de sesión.
 * @param int $MaxRows : Número máximo de filas a retornar.
 * @param int $SkeepRows : Número de filas a omitir, utilizado para la paginación.
 * @param int $OrderedItem : Orden del elemento solicitado (por defecto 1).
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna array vacío si no hay errores.
 *  - *Data* (array): Contiene el resultado de la consulta con los egresos filtrados.
 *
 * @throws Exception Si ocurre algún error al procesar la solicitud o consultar los datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se inicializa un objeto UsuarioMandante utilizando un valor de sesión y se definen variables. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


$CloseBoxId = $params->CloseBoxId;
$fechaEspecifica = '';
$BetShopId = 0;

/* Verifica si $CloseBoxId está vacío y obtiene datos del cierre de caja. */
if ($CloseBoxId != "") {
    $UsuarioCierrecaja = new UsuarioCierrecaja($CloseBoxId);
    $fechaEspecifica = $UsuarioCierrecaja->getFechaCierre();

    $BetShopId = $UsuarioCierrecaja->getUsuarioId();

}


/* asigna parámetros y obtiene el conteo de filas desde una solicitud. */
$Id = $params->Id;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];

/* asigna un valor a $SkeepRows basado en parámetros de solicitud. */
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* crea reglas de filtro basadas en condiciones de sesión y usuario. */
$rules = [];

if ($BetShopId == 0) {
    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
        array_push($rules, array("field" => "egreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    } else {
        array_push($rules, array("field" => "egreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    }

} else {
    /* Agrega una regla al array si no se cumple una condición previa. */

    array_push($rules, array("field" => "egreso.usuario_id", "data" => $BetShopId, "op" => "eq"));

}


/* Añade reglas de fecha a un array según una fecha específica o la actual. */
if ($fechaEspecifica != '') {
    array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime($fechaEspecifica)), "op" => "ge"));
    array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 23:59:59", strtotime($fechaEspecifica)), "op" => "le"));

} else {
    array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));

}

/* Se crean y codifican reglas de filtro para operaciones con el objeto Egreso. */
array_push($rules, array("field" => "egreso.tipo_id", "data" => "0", "op" => "eq"));
array_push($rules, array("field" => "egreso.productoterc_id", "data" => "0", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Egreso = new Egreso();


/* obtiene y decodifica datos de egresos en formato JSON. */
$data = $Egreso->getEgresosCustom(" concepto.descripcion,proveedor_tercero.descripcion,documento.descripcion, egreso.* ", "egreso.egreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* crea un arreglo asociativo con información de un objeto "egreso". */
    $array = [];


    $array["Id"] = $value->{"egreso.egreso_id"};
    $array["Description"] = $value->{"egreso.descripcion"};
    $array["ProvidersThird"] = $value->{"egreso.proveedorterc_id"};

    /* Asignación de valores a un array desde un objeto, utilizando propiedades específicas. */
    $array["ProvidersThird"] = $value->{"proveedor_tercero.descripcion"};
    $array["ProvidersThirdExpenses"] = $value->{"egreso.proveedorterc_id"};
    $array["ProvidersThirdExpenses"] = $value->{"proveedor_tercero.descripcion"};
    $array["Document"] = $value->{"egreso.documento"};

    $array["Concept"] = $value->{"egreso.concepto_id"};

    /* asigna valores de un objeto a un arreglo asociativo en PHP. */
    $array["ConceptExpenses"] = $value->{"egreso.concepto_id"};

    $array["Concept"] = $value->{"concepto.descripcion"};
    $array["ConceptExpenses"] = $value->{"concepto.descripcion"};

    $array["Reference"] = $value->{"egreso.documento"};

    /* Asigna valores de un objeto a un array y lo añade a un array final. */
    $array["Value"] = $value->{"egreso.valor"};
    $array["State"] = $value->{"egreso.estado"};

    $array["Serie"] = $value->{"egreso.serie"};
    $array["TypeDocument"] = $value->{"documento.descripcion"};

    array_push($final, $array);


}


/* Código PHP que establece respuesta exitosa sin errores y devuelve datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* asigna valores a un array asociativo llamado $response. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $clasificadores->count[0]->{".count"};
$response["data"] = $final;
