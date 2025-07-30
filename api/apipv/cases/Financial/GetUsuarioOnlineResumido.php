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
 * Obtener todos los detalles de un usuario online de manera resumida, estos incluyen información respecto a los productos a las transacciones que haya realizado, las recargas que se encuentran procesadas.
 *
 * @param string $dateFrom : Descripción: Fecha de inicio para el informe de usuario online resumido.
 * @param string $dateTo : Descripción: Fecha de fin para el informe de usuario online resumido.
 * @param int $PaymentSystemId : Descripción: Identificador del sistema de pago.
 * @param int $CashDeskId : Descripción: Identificador de la caja.
 * @param int $ClientId : Descripción: Identificador del cliente.
 * @param float $AmountFrom : Descripción: Monto mínimo para el informe de usuario online resumido.
 * @param float $AmountTo : Descripción: Monto máximo para el informe de usuario online resumido.
 * @param int $CurrencyId : Descripción: Identificador de la moneda.
 * @param string $ExternalId : Descripción: Identificador externo.
 * @param int $Id : Descripción: Identificador del informe de usuario online resumido.
 * @param bool $IsDetails : Descripción: Indicador para obtener información detallada.
 * @param int $CountrySelect : Descripción: Identificador del país seleccionado.
 * @param int $MaxRows : Descripción: Número máximo de filas a devolver.
 * @param int $OrderedItem : Descripción: Ítem ordenado.
 * @param int $SkeepRows : Descripción: Número de filas a omitir en la consulta.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos del informe de usuario online resumido.
 *
 * Objeto en caso de error:
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'danger';
 * $response['AlertMessage'] = 'Invalid';
 * $response['ModelErrors'] = [];
 *
 * @throws Exception Permiso denegado
 * @throws Exception Inusual Detectado
 */


/* recibe un JSON, lo decodifica y asigna una fecha a una variable. */
exit();
$PuntoVenta = new PuntoVenta();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->dateTo;


/* Convierte fechas de entrada a formato local considerando la zona horaria. */
$ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));

$FromDateLocal = $params->dateFrom;

$FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


$PaymentSystemId = $params->PaymentSystemId;

/* asigna parámetros de entrada a variables correspondientes para su uso posterior. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* asigna valores a variables según condiciones de parámetros y solicitudes. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;


$TypeBet = ($_REQUEST["TypeBet"] == 2) ? 2 : 1;
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';


/* valida parámetros de solicitud y determina si continuar con la ejecución. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$seguir = true;

if ($_REQUEST["dateFrom"] == "" || $_REQUEST["dateTo"] == "") {
    $seguir = false;

}


/* verifica si $FromDateLocal está vacío y establece una fecha por defecto. */
if ($FromDateLocal == "") {
    $seguir = false;


    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(time() . $timezone . ' hour '));

}

/* establece una fecha local si está vacía. */
if ($ToDateLocal == "") {
    $seguir = false;

    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(time() . ' +0 day' . $timezone . ' hour '));


}

if ($seguir) {

    /* Crea reglas de filtrado para productos basados en condiciones específicas. */
    $rules = [];

    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

    if ($PaymentSystemId != "") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
    }


    /* Agrega reglas de filtrado basadas en ID de caja y cliente si no están vacíos. */
    if ($CashDeskId != "") {
        array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
    }
    if ($ClientId != "") {
        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }


    /* Agrega condiciones a un array si los valores de $AmountFrom y $AmountTo no están vacíos. */
    if ($AmountFrom != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
    }
    if ($AmountTo != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
    }


    /* Agrega condiciones de filtrado a un arreglo basado en variables no vacías. */
    if ($CurrencyId != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }
    if ($ExternalId != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
    }

    /* añade reglas a un arreglo basado en condiciones específicas de ID y país. */
    if ($Id != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
    }

    if ($CountrySelect != "" && $CountrySelect != "0") {
        $Pais = $CountrySelect;

    }


    /* Configura la agrupación y selección de datos según una condición específica. */
    $grouping = "";
    $select = "";
    if ($IsDetails) {

    } else {
        $grouping = " usuario_recarga.puntoventa_id,producto.producto_id ";
        $select = "SUM(usuario_recarga.valor) valoru,SUM(transaccion_producto.valor) valor, ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));

    }


    /* establece un filtro y maneja valores por defecto para variables. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Establece un valor predeterminado y verifica la condición del país del usuario. */
    if ($MaxRows == "") {
        $MaxRows = 5;
    }

    $json = json_encode($filtro);

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        $Pais = $_SESSION['pais_id'];
    }

    // Si el usuario esta condicionado por el mandante y no es de Global

    /* asigna un valor a $Mandante según variables de sesión específicas. */
    if ($_SESSION['Global'] == "N") {
        $Mandante = $_SESSION['mandante'];
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            $Mandante = $_SESSION['mandanteLista'];
        }

    }


    /* Se obtienen y decodifican transacciones de un punto de venta en formato JSON. */
    $transacciones = $PuntoVenta->getUsuarioResumen($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $TypeBet, $Pais, $Mandante);


    $transacciones = json_decode($transacciones);

    $final = [];

    /* Inicializa la variable $totalm con el valor numérico 0. */
    $totalm = 0;
    foreach ($transacciones->data as $key => $value) {

        /* crea un array con datos de país, fecha y moneda sin tildes. */
        $array = [];

        $array["Pais"] = (new ConfigurationEnvironment())->quitar_tildes($value->{"x.pais_nom"});

        $array["Fecha"] = $value->{"x.fecha"};
        $array["Moneda"] = $value->{"x.moneda"};

        /* Asignación de valores de tickets y apuestas a un array para procesamiento posterior. */
        $array["CantidadTickets"] = $value->{"x.cant_tickets"};
        $array["CantidadTicketsCasino"] = $value->{"x.cant_tickets_casino"};
        $array["CantidadTicketsCasinoVivo"] = $value->{"x.cant_tickets_casinovivo"};
        $array["TotalTickets"] = $array["CantidadTickets"] + $array["CantidadTicketsCasino"] + $array["CantidadTicketsCasinoVivo"];
        $array["Stake"] = $value->{"x.valor_apostado"};
        $array["StakeCasino"] = $value->{"x.valor_apostado_casino"};

        /* Se crea un array que almacena valores relacionados con apuestas y premios de casinos. */
        $array["StakeLiveCasino"] = $value->{"x.valor_apostado_casinovivo"};
        $array["TotalStake"] = $array["StakeLiveCasino"] + $array["StakeCasino"] + $array["Stake"];
        $array["StakePromedio"] = $value->{"x.valor_ticket_prom"};
        $array["Payout"] = $value->{"x.valor_premios"};
        $array["PayoutCasino"] = $value->{"x.valor_premios_casino"};
        $array["PayoutLiveCasino"] = $value->{"x.valor_premios_casinovivo"};

        /* calcula y almacena datos financieros en un array asociativo. */
        $array["TotalPayout"] = $array["Payout"] + $array["PayoutCasino"] + $array["PayoutLiveCasino"];
        $array["Paid"] = $value->{"x.valor_pagado"};
        $array["WithdrawPending"] = $value->{"x.nota_retiro_pend"};
        $array["ValTicketOpen"] = $value->{"x.valor_tickets_abiertos"};
        $array["Deposits"] = $value->{"x.valor_recargas"};
        $array["AmountWinnings"] = $value->{"x.disp_retiro"};

        /* asigna valores a un array y calcula ganancias y porcentajes. */
        $array["AmountDeposits"] = $value->{"x.saldo_recarga"};

        $array["Bonos"] = ($value->{"pl2.bonos"} == "") ? 0 : $value->{"pl2.bonos"};
        $array["Ggr"] = $array["Stake"] - $array["Payout"] - $array["Bonos"];
        $array["GgrPorc"] = ($array["Ggr"] / $array["Stake"]) * 100;


        array_push($final, $array);
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    /* asigna valores a un array de respuesta con datos y conteos. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $transacciones->count[0]->{".count"};
    $response["data"] = $final;
} else {
    /* inicializa una respuesta vacía con posiciones y conteos en cero. */

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = [];

}
