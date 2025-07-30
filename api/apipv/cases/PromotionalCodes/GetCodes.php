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
 * PromotionalCodes/GetCodes
 *
 * Obtener codigos promocionales
 *
 * @param int $Id : codigo promocional
 * @param int $MaxRows : Límite de filas a recuperar.
 * @param string $OrderedItem : Columna a order para la consulta.
 * @param int $SkeepRows : Desplazamiento para paginación.
 *
 * @return object El objeto $response es un array con los siguientes atributos:
 *   - *HasError* (bool): Indica si hubo un error en la operación.
 *   - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *   - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *   - *ModelErrors* (array) : Contiene manejo de modales por error
 *   - *Data* (array) : Contiene la respuesta de la consulta con la información de los codigos promocionales
 *   - *pos* (int) : Contiene ubicación para paginación
 *   - *total_count* (int) : Contiene el numero de registros devueltos por la SQL
 *   - *data* (array) : Contiene la respuesta de la consulta con la información de los codigos promocionales
 *
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros y una variable de conteo desde la solicitud. */
$Id = $params->Id;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];

/* obtiene parámetros de solicitud, ajustando filas y almacenando variables. */
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$UserId = $_REQUEST["UserId"];

$Code = $_REQUEST["Code"];
$Id = $_REQUEST["Id"];

/* obtiene datos de un formulario y establece una variable para filas a omitir. */
$Name = $_REQUEST["Name"];
$State = $_REQUEST["State"];

$dateFrom2 = $_REQUEST['dateFrom2'];
$dateTo2 = $_REQUEST['dateTo2'];


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


/* Modifica fechas ingresadas a un formato específico, estableciendo inicio y fin del día. */
if ($dateFrom2 != "") {
    $BeginDateModified = date("Y-m-d 00:00:00", strtotime($dateFrom2));
}
if ($dateTo2 != "") {
    $EndDateModified = date("Y-m-d 23:59:59", strtotime($dateTo2));
}


/* Se generan reglas condicionales basadas en los IDs proporcionados. */
$rules = [];

if ($Id != "") {
    //array_push($rules,array("field"=>"codpromocional_id","data"=>"$Id","op"=>"eq"));
}

if ($UserId != "") {
    array_push($rules, array("field" => "codigo_promocional.usuario_id", "data" => "$UserId", "op" => "eq"));
}


/* Agrega reglas a un array según condiciones sobre código e ID proporcionados. */
if ($Code != "") {
    array_push($rules, array("field" => "codigo_promocional.codigo", "data" => $Code, "op" => "eq"));
}
if ($Id != "") {
    array_push($rules, array("field" => "codigo_promocional.codpromocional_id", "data" => $Id, "op" => "eq"));
}


/* agrega reglas basadas en el estado y nombre del código promocional. */
if ($State == "A") {
    array_push($rules, array("field" => "codigo_promocional.estado", "data" => "A", "op" => "eq"));
} else if ($State == "I") {
    array_push($rules, array("field" => "codigo_promocional.estado", "data" => "I", "op" => "eq"));
}
if ($Name != "") {
    array_push($rules, array("field" => "codigo_promocional.descripcion", "data" => $Name, "op" => "cn"));
}


/* Condicional que agrega reglas basadas en la sesión del usuario y promotores. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "codigo_promocional.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "codigo_promocional.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}

// Si el usuario esta condicionado por País

/* Condiciones para agregar reglas sobre el campo "pais_id" en base a la sesión. */
if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
} else {
    if ($_SESSION["PaisCondS"] != '') {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['PaisCondS'], "op" => "eq"));
    }
}


/* Se añaden reglas de filtrado basadas en fechas a un array. */
if ($dateFrom2 != "") {
    array_push($rules, array("field" => "codigo_promocional.fecha_crea", "data" => $dateFrom2, "op" => "ge"));
}
if ($dateTo2 != "") {
    array_push($rules, array("field" => "codigo_promocional.fecha_crea", "data" => $dateTo2, "op" => "le"));
}

/* Crea un filtro JSON y obtiene códigos promocionales personalizados desde la base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$CodigoPromocional = new CodigoPromocional();

$data = $CodigoPromocional->getCodigoPromocionalsCustom("  codigo_promocional.* ", "codigo_promocional.codpromocional_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* Convierte datos JSON en un arreglo estructurado con información de promociones. */
$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {

    $array = [];


    $array["Id"] = $value->{"codigo_promocional.codpromocional_id"};
    $array["Code"] = $value->{"codigo_promocional.codigo"};
    $array["Name"] = $value->{"codigo_promocional.descripcion"};
    $array["CreatedLocalDate"] = $value->{"codigo_promocional.fecha_crea"};
    $array["State"] = $value->{"codigo_promocional.estado"};
    $array["UserId"] = $value->{"codigo_promocional.usuario_id"};
    $array["Function"] = $value->{"codigo_promocional.funcion"};

    array_push($final, $array);


}


/* define una respuesta sin errores con datos finales y mensaje exitoso. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* asigna valores a un arreglo de respuesta en PHP. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $data->count[0]->{".count"};
$response["data"] = $final;
