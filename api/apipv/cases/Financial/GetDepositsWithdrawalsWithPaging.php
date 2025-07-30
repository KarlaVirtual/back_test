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
use Backend\dto\Consecutivo;use Backend\dto\ConfigurationEnvironment;
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
 * Obtiene depósitos y retiros con paginación
 *
 * Este endpoint permite obtener un listado paginado de depósitos y retiros
 * aplicando diversos filtros como rango de fechas, sistema de pago, caja, cliente, etc.
 *
 * @param object $params {
 *   "ToCreatedDateLocal": string,    // Fecha hasta (formato Y-m-d H:i:s)
 *   "FromCreatedDateLocal": string,  // Fecha desde (formato Y-m-d H:i:s) 
 *   "PaymentSystemId": int,          // ID del sistema de pago
 *   "CashDeskId": int,              // ID de la caja
 *   "ClientId": string,             // ID del cliente
 *   "AmountFrom": float,            // Monto mínimo
 *   "AmountTo": float,              // Monto máximo
 *   "CurrencyId": string,           // ID de la moneda
 *   "ExternalId": string,           // ID externo
 *   "Id": int,                      // ID de la transacción
 *   "IsDetails": boolean,           // Indica si se requieren detalles
 *   "MaxRows": int,                 // Máximo de filas a retornar
 *   "OrderedItem": string,          // Campo de ordenamiento
 *   "SkeepRows": int               // Filas a omitir (offset)
 * }
 *
 * @return array {
 *   "pos": int,           // Posición actual
 *   "total_count": int,   // Total de registros
 *   "data": array        // Listado de transacciones
 * }
 *
 * @throws Exception Si hay errores en la consulta a la base de datos
 *
 * @access public
 */


/* Se crea un objeto y se decodifica JSON de entrada. */
$UsuarioRecarga = new UsuarioRecarga();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ToCreatedDateLocal;


/* transforma fechas y ajusta la zona horaria para un sistema de pagos. */
$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToCreatedDateLocal) . ' +1 day' . $timezone . ' hour '));

$FromDateLocal = $params->FromCreatedDateLocal;

$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromCreatedDateLocal) . $timezone . ' hour '));


$PaymentSystemId = $params->PaymentSystemId;

/* asigna valores de parámetros a variables para su uso posterior. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* asigna valores de parámetros a variables para su uso posterior. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;


$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* Genera un conjunto de reglas para filtrar datos según fechas definidas. */
$SkeepRows = $params->SkeepRows;

$rules = [];
//array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));
//array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));

array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

/* Se agregan reglas a un arreglo dependiendo de condiciones sobre fechas y parámetros. */
array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

if ($PaymentSystemId != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
}

if ($CashDeskId != "") {
    array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
}

/* agrega reglas de filtrado basado en ID de cliente y monto. */
if ($ClientId != "") {
    array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
}

if ($AmountFrom != "") {
    array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
}

/* Agrega reglas de validación según valores de monto y moneda proporcionados. */
if ($AmountTo != "") {
    array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
}

if ($CurrencyId != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
}

/* Agrega condiciones a un arreglo si las variables no están vacías. */
if ($ExternalId != "") {
    array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
}
if ($Id != "") {
    array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
}


/* Condición para agregar regla de IP a un arreglo y definir agrupación por recarga. */
if ($Ip != "") {
    array_push($rules, array("field" => "cuenta_cobro.dir_ip", "data" => "$Ip", "op" => "cn"));

}


$grouping = "usuario_recarga.recarga_id";

/* Condicional que define la variable $select según el estado de $IsDetails. */
$select = "";
if ($IsDetails) {

} else {
    $grouping = " usuario_recarga.puntoventa_id,producto.producto_id ";
    $select = "SUM(usuario_recarga.valor) valoru,SUM(transaccion_producto.valor) valor, ";
    //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));

}


/* crea un filtro y establece valores predeterminados para variables vacías. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un valor predeterminado y obtiene datos de transacciones en JSON. */
if ($MaxRows == "") {
    $MaxRows = 10;
}

$json = json_encode($filtro);

$transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);


/* Se decodifica un JSON y se inicializan un arreglo y un total. */
$transacciones = json_decode($transacciones);

$final = [];
$totalm = 0;
foreach ($transacciones->data as $key => $value) {

    /* Suma valores a $totalm según la condición de $IsDetails en un array. */
    $array = [];
    if ($IsDetails) {
        $totalm = $totalm + $value->{"transaccion_producto.valor"};

    } else {
        $totalm = $totalm + $value->{".valoru"};
    }
    if ($value->{"producto.descripcion"} == "") {


        /* asigna datos a un arreglo según condiciones específicas. */
        $array["Id"] = $value->{"usuario_recarga.recarga_id"};
        $array["ClientId"] = $value->{"usuario_recarga.usuario_id"};
        $array["CreatedLocal"] = $value->{"usuario_recarga.fecha_crea"};
        $array["ModifiedLocal"] = $value->{"usuario_recarga.fecha_crea"};

        if ($IsDetails) {
            $array["Amount"] = $value->{"usuario_recarga.valor"};

        } else {
            /* asigna un valor a un arreglo si se cumple cierta condición. */

            $array["Amount"] = $value->{".valoru"};

        }

        /* Asigna valores a un array relacionado con un sistema de pagos en efectivo. */
        $array["PaymentSystemName"] = "Efectivo";
        $array["TypeName"] = "Payment";

        $array["CurrencyId"] = $value->{"usuario.moneda"};
        $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
        $array["State"] = "A";

        /* Inicializa un arreglo con claves "Note" y "ExternalId" vacías. */
        $array["Note"] = "";
        $array["ExternalId"] = "";

    } else {


        /* asigna propiedades de un objeto a un arreglo, incluyendo detalles opcionales. */
        $array["Id"] = $value->{"usuario_recarga.recarga_id"};
        $array["ClientId"] = $value->{"transaccion_producto.usuario_id"};
        $array["CreatedLocal"] = $value->{"usuario_recarga.fecha_crea"};
        $array["ModifiedLocal"] = $value->{"transaccion_producto.fecha_modif"};

        if ($IsDetails) {
            $array["Amount"] = $value->{"transaccion_producto.valor"};
            $array["ExternalId"] = $value->{"transaccion_producto.externo_id"};

        } else {
            /* asigna valores a un array basado en una condición previa. */

            $array["Amount"] = $value->{".valor"};
            $array["ExternalId"] = "";

        }


        /* Asignación de valores de un objeto a un arreglo en PHP. */
        $array["PaymentSystemName"] = $value->{"producto.descripcion"};
        $array["TypeName"] = "Payment";

        $array["CurrencyId"] = $value->{"usuario.moneda"};
        $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
        $array["State"] = $value->{"transaccion_producto.estado_producto"};

        /* Se inicializa un elemento "Note" del arreglo con un valor vacío. */
        $array["Note"] = "";
    }
    
    /* agrega elementos de `$array` al final de `$final`. */
    array_push($final, $array);
}


/* configura una respuesta exitosa con datos y sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array("Documents" => array("Objects" => $final,
    "Count" => $transacciones->count[0]->{".count"}),
    "TotalAmount" => $totalm,
);
