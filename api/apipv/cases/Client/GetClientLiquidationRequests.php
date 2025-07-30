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
 * Client/GetClientLiquidationRequests
 *
 * Obtener las solicitudes de liquidación.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param string $params->ToDateLocal Fecha final en formato "Y-m-d H:i:s".
 * @param string $params->FromDateLocal Fecha inicial en formato "Y-m-d H:i:s".
 * @param string $params->BetShopId Identificador de la tienda de apuestas.
 * @param string $params->ClientId Identificador del cliente.
 * @param string $params->PaymentTypeId Identificador del tipo de pago.
 * @param string $params->State Estado de la solicitud.
 * @param string $params->WithDrawTypeId Tipo de retiro.
 * @param boolean $params->ByAllowDate Indica si se filtra por fecha permitida.
 * @param integer $params->MaxRows Número máximo de registros a devolver.
 * @param integer $params->OrderedItem Campo por el cual se ordenarán los resultados.
 * @param integer $params->SkeepRows Número de registros a omitir para la paginación.
 *
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta.
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Errores del modelo.
 * - Data (array): Información de las solicitudes de liquidación.
 *
 * @throws Exception Si ocurre un error general o de validación.
 */


/* Crea un resumen de comisiones utilizando fechas y parámetros específicos. */
$UsucomisionResumen = new UsucomisionResumen();

$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
$BetShopId = $params->BetShopId;
$ClientId = $params->ClientId;

/* Se asignan y convierten parámetros de entrada en variables específicas para procesamiento. */
$PaymentTypeId = $params->PaymentTypeId;
$State = $params->State;
$WithDrawTypeId = $params->WithDrawTypeId;
$ByAllowDate = $params->ByAllowDate;

$ByAllowDate = (bool)($ByAllowDate);


/* asigna valores y maneja una condición para filas a omitir. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables si están vacías: $OrderedItem y $MaxRows. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}


/* Código que agrega reglas de filtrado de fechas a un array si la condición se cumple. */
$rules = [];
//array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));

if (!$ByAllowDate || $ByAllowDate == "false") {
    array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

} else {
    /* Agrega reglas de fecha a un array para filtrar datos en consultas. */

    array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

}


/* Agrega reglas basadas en ClientId y State si no están vacíos. */
if ($ClientId != "") {
    array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => "$ClientId", "op" => "eq"));
}


if ($State != "") {
    array_push($rules, array("field" => "usucomision_resumen.estado", "data" => "$State", "op" => "eq"));
}


/* Codifica un filtro en JSON y obtiene datos agrupados de usuarios. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" usucomision_resumen.*,usuario.login,usuario.nombre ", "usucomision_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usucomresumen_id");
$UsucomisionResumens = json_decode($UsucomisionResumens);


/* Se inicializa un arreglo vacío llamado "final" en PHP. */
$final = array();
foreach ($UsucomisionResumens->data as $key => $value) {


    /* crea un array asociativo con información de usuarios y comisiones. */
    $array = [];

    $array["Id"] = $value->{"usucomision_resumen.usucomresumen_id"};
    $array["ClientId"] = $value->{"usucomision_resumen.usuario_id"};
    $array["ClientLogin"] = $value->{"usuario.login"};
    $array["ClientName"] = $value->{"usuario.nombre"};

    /* Asignación de valores de un objeto a un array en PHP. */
    $array["RequestTime"] = $value->{"usucomision_resumen.fecha_crea"};
    $array["CreatedLocal"] = $value->{"usucomision_resumen.fecha_crea"};
    $array["ModifiedLocal"] = $value->{"usucomision_resumen.fecha_crea"};

    $array["Amount"] = $value->{"usucomision_resumen.comision"};

    $nombreMetodoPago = '';

    /* Asigna un estado de pago basado en una condición específica del usuario. */
    $idMetodoPago = 0;

    $estado = 'Pendiente de Pago';

    if ($value->{"usucomision_resumen.estado"} == "P") {
        $estado = 'Pagado';
    } elseif ($value->{"cuenta_cobro.estado"} == "R") {
        /* Condición que verifica si el estado de cuenta de cobro es "R". */

        /* Estado de pago establecido como "Rechazado" en un arreglo con información del sistema. */
        $estado = 'Rechazado';
    }


    $array["PaymentSystemName"] = 'test';
    $array["PaymentSystemId"] = 'test';

    /* Inicializa un arreglo con claves y valores predeterminados en PHP. */
    $array["TypeName"] = "";

    $array["CurrencyId"] = 'test';
    $array["CashDeskId"] = 'test';
    $array["BetshopId"] = 'test';
    $array["BetShopName"] = 'test';

    /* Asigna valores de un objeto a un array asociativo en PHP. */
    $array["RejectUserName"] = $value->{"usucomision_resumen.usurechaza_id"};
    $array["AllowUserName"] = $value->{"usucomision_resumen.usucambio_id"};
    $array["PaidUserName"] = $value->{"usucomision_resumen.usupago_id"};
    $array["Notes"] = $value->{"usucomision_resumen.mensaje_usuario"};
    $array["RejectReason"] = $value->{"usucomision_resumen.observacion"};
    $array["StateName"] = $estado;

    /* asigna datos a un arreglo y lo encapsula en otro arreglo. */
    $array["State"] = $value->{"usucomision_resumen.estado"};
    $array["StateId"] = $value->{"usucomision_resumen.estado"};
    $array["Note"] = "";
    $array["ExternalId"] = "";
    $array["PaymentDocumentId"] = "";


    $array2["PaymentDocumentData"] = $array;

    /* Añade elementos de `$array` al final de `$final` en PHP. */
    array_push($final, $array);
}


/* Código que crea una respuesta estructurada con éxito, sin errores y datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;