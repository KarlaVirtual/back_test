<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\BodegaInformeGerencial;
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
 * Financial/GetInformeGerencial
 *
 * Obtiene los detalles para el informe gerencial basado en los parámetros proporcionados.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->dateTo Fecha final del rango.
 * @param string $params->dateFrom Fecha inicial del rango.
 * @param int $params->PaymentSystemId ID del sistema de pago.
 * @param int $params->CashDeskId ID de la caja.
 * @param int $params->ClientId ID del cliente.
 * @param float $params->AmountFrom Monto mínimo.
 * @param float $params->AmountTo Monto máximo.
 * @param int $params->CurrencyId ID de la moneda.
 * @param string $params->ExternalId ID externo.
 * @param string $params->Id ID interno.
 * @param bool $params->IsDetails Indica si se deben incluir detalles.
 * 
 * 
 *
 * @return array $response Respuesta en formato JSON con los siguientes valores:
 *  - int $pos Posición inicial de los datos.
 *  - int $total_count Total de registros encontrados.
 *  - array $data Datos procesados para el informe.
 */

/* Registro de advertencia en syslog y obtención de parámetros JSON desde la solicitud. */
syslog(LOG_WARNING, "GetInformeGerencial :" . $_SERVER["REQUEST_URI"]);

$PuntoVenta = new PuntoVenta();

$params = file_get_contents('php://input');
$params = json_decode($params);


/* Convierte fechas string de solicitudes en formato datetime ajustado a la zona horaria. */
$ToDateLocal = $params->dateTo;

$ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));

$FromDateLocal = $params->dateFrom;

$FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


/* asigna valores de parámetros a variables para un sistema de pagos. */
$PaymentSystemId = $params->PaymentSystemId;
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;

/* asigna valores de parámetros y verifica el tipo de usuario. */
$ExternalId = $params->ExternalId;
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;


if ($_REQUEST["TypeUser"] == 2) {
    $TypeUser = 2;
} else {
    /* asigna un valor a $TypeUser según el input "TypeUser". */

    if ($_REQUEST["TypeUser"] == 1) {
        $TypeUser = 1;
    } else {
        $TypeUser = '';

    }
}

/* Obtención parámetros de consulta */
$TypeBet = ($_REQUEST["TypeBet"] == 2) ? 2 : 1;
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$Partner = '';

if (is_string($params)) {
    $params = json_decode(base64_decode($params), true);
    if ($params['sitebuilder'] == 1) $Partner = base64_decode($params['data']);
}


/* asigna valores de solicitudes a variables para gestionar filas y artículos. */
$MaxRows = $_REQUEST["count"];
$WalletId = ($_REQUEST["WalletId"] == '1' ? '1' : '0');

$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$seguir = true;


/* Sanitización parámetros para ejecución de consulta */
if ($_REQUEST["dateFrom"] == "" || $_REQUEST["dateTo"] == "") {
    $seguir = false;
}


/* Solicitud intervalos de fecha para consulta */
if ($FromDateLocal == "") {
    $seguir = false;


    $FromDateLocal = date("Y-m-d", strtotime(time() . $timezone . ' hour '));

}

if ($ToDateLocal == "") {
    $seguir = false;

    $ToDateLocal = date("Y-m-d", strtotime(time() . ' +1 day' . $timezone . ' hour '));


}

/* Declaración parámetros para almacenamiento final de los resultados */
$final = [];
$totalm = 0;


if ($seguir) {


    /* Convierte fechas en formato específico a objetos de fecha local considerando la zona horaria. */
    $ToDateLocal = $params->dateTo;

    $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));

    $FromDateLocal = $params->dateFrom;

    $FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


    /* Se definen reglas de filtrado para fechas en una consulta de informes gerenciales. */
    $rules = [];

    array_push($rules, array("field" => "bodega_informe_gerencial.fecha", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "bodega_informe_gerencial.fecha", "data" => "$ToDateLocal", "op" => "le"));


    /* Condiciones para agregar reglas basadas en IDs de moneda y externo. */
    if ($CurrencyId != "") {
        //array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }
    if ($ExternalId != "") {
        // array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
    }

    /* Condicionalmente agrega reglas a un array basado en variables definidas. */
    if ($Id != "") {
        // array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
    }
    if ($WalletId != "") {
        array_push($rules, array("field" => "bodega_informe_gerencial.billetera_id", "data" => "$WalletId", "op" => "eq"));
    }


    /* Se añaden reglas basadas en el valor de $TypeBet en un array. */
    if ($TypeBet == 2) {
        array_push($rules, array("field" => "bodega_informe_gerencial.tipo_fecha", "data" => "2", "op" => "eq"));

    } else {
        array_push($rules, array("field" => "bodega_informe_gerencial.tipo_fecha", "data" => "1", "op" => "eq"));

    }


    /* Agrega reglas según el tipo de usuario en un arreglo de condiciones. */
    if ($TypeUser == 2) {
        array_push($rules, array("field" => "bodega_informe_gerencial.tipo_usuario", "data" => "2", "op" => "eq"));

    } else if ($TypeUser == 1) {
        array_push($rules, array("field" => "bodega_informe_gerencial.tipo_usuario", "data" => "1", "op" => "eq"));

    }


    /* asigna un país basado en selección o sesión del usuario. */
    if ($CountrySelect != "" && $CountrySelect != "0") {
        $Pais = $CountrySelect;

    }

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        $Pais = $_SESSION['pais_id'];
    }

    // Si el usuario esta condicionado por el mandante y no es de Global

    /* Asigna valor a $Mandante según condiciones de $Partner y $_SESSION. */
    if ($Partner !== '') $Mandante = $Partner;

    if ($_SESSION['Global'] == "N") {
        $Mandante = strtolower($_SESSION['mandante']);
    } else {
        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            $Mandante = $_SESSION["mandanteLista"];
        }

    }


    /* Agrega condiciones a un array según los valores de $Pais y $Mandante. */
    if ($Pais != "") {
        array_push($rules, array("field" => "bodega_informe_gerencial.pais_id", "data" => "$Pais", "op" => "eq"));
    }

    if ($Mandante != "") {
        array_push($rules, array("field" => "bodega_informe_gerencial.mandante", "data" => "$Mandante", "op" => "in"));
    }


    /* agrega una regla para filtrar datos en una consulta. */
    array_push($rules, array("field" => "bodega_informe_gerencial.pais_id", "data" => "1", "op" => "ne"));

    $grouping = "";
    $select = "";
    if ($IsDetails) {

    } else {
        /* Condición alternativa de código que prepara agrupaciones y selecciones de datos. */

        //$grouping = " bodega_informe_gerencial.mandante,bodega_informe_gerencial.pais_id,bodega_informe_gerencial.fecha ";
        //$select = "bodega_informe_gerencial.fecha,SUM(bodega_informe_gerencial.cantidad) cantidad,SUM(bodega_informe_gerencial.saldo_apuestas) saldo_apuestas,SUM(bodega_informe_gerencial.saldo_premios) saldo_premios,SUM(bodega_informe_gerencial.usuarios_registrados) usuarios_registrados,SUM(bodega_informe_gerencial.primeros_depositos) primeros_depositos,SUM(bodega_informe_gerencial.saldo_bono) saldo_bono,SUM(bodega_informe_gerencial.impuesto_apuestas) impuesto_apuestas ,SUM(bodega_informe_gerencial.premio_jackpot) premio_jackpot,SUM(bodega_informe_gerencial.impuesto_premios) impuesto_premios, bodega_informe_gerencial.mandante,pais.* ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));
    }


    /* Configura un filtro con reglas y gestiona valores predeterminados para variables. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece un valor predeterminado de 5 a $MaxRows si está vacío. */
    if ($MaxRows == "") {
        $MaxRows = 5;
    }


    /* Convierte datos a JSON, obtiene transacciones y las decodifica nuevamente. */
    $json = json_encode($filtro);


    $BodegaInformeGerencial = new BodegaInformeGerencial();
    $transacciones = $BodegaInformeGerencial->getBodegaInformeGerencialCustom2($ToDateLocal, $FromDateLocal, $WalletId, $TypeBet, $TypeUser, $Pais, $Mandante, "asc", $SkeepRows, $MaxRows);

    $transacciones = json_decode($transacciones);

    /*    $final = [];
        $totalm = 0;*/

    if (!empty($transacciones->data)) {
        foreach ($transacciones->data as $key => $value) {

            /* Crea un array con país, icono y fecha según condiciones específicas. */
            $array = [];

            $array["Pais"] = (new ConfigurationEnvironment())->quitar_tildes($value->{"pais.pais_nom"});
            $array["CountryIcon"] = strtolower($value->{"pais.iso"});

            if ($TypeBet == 2) {
                $array["Fecha"] = $value->{"bodega_informe_gerencial.fecha"};

            } else {
                /* Asigna valor de fecha a un arreglo basado en una condición. */

                $array["Fecha"] = $value->{"bodega_informe_gerencial.fecha"};

            }

            /* asigna valores a un array asociativo desde un objeto. */
            $array["Moneda"] = $value->{"pais.moneda"};
            $array["CantidadTickets"] = $value->{".cantidad"};
            $array["Stake"] = $value->{".saldo_apuestas"};
            $array["StakePromedio"] = 0;
            $array["Payout"] = $value->{".saldo_premios"};
            $array["UsersRegistered"] = $value->{".usuarios_registrados"};

            /* asigna valores y calcula ganancias en un arreglo a partir de datos. */
            $array["FirstDeposits"] = $value->{".primeros_depositos"};

            $array["Bonos"] = ($value->{".saldo_bono"} == "") ? 0 : $value->{".saldo_bono"};
            $array["TotalJackpotPrizeSum"] = (string)($value->{".premio_jackpot"} == "") ? 0 : $value->{".premio_jackpot"};
            $array["Ggr"] = $array["Stake"] - $array["Payout"] - $array["Bonos"];
            $array["GgrPorc"] = ($array["Stake"] == '0' ? 0 : (($array["Ggr"] / $array["Stake"]) * 100));


            /* Asigna valores de un objeto a un arreglo asociativo en PHP. */
            $array["Partner"] = $value->{"bodega_informe_gerencial.mandante"};

            $array["TaxBet"] = $value->{".impuesto_apuestas"};
            $array["Tax"] = $value->{".impuesto_premios"};

            $array["LiveAwards"] = $value->{".premios_live"};

            /* Asigna valores de apuestas y premios a un array desde un objeto. */
            $array["LiveBets"] = $value->{".apuestas_live"};
            $array["LiveAmount"] = $value->{".cantidad_live"};
            $array["PrematchAwards"] = $value->{".premios_prematch"};
            $array["PrematchBets"] = $value->{".apuestas_prematch"};
            $array["PrematchAmount"] = $value->{".cantidad_prematch"};
            $array["MixedAwards"] = $value->{".premios_mixta"};

            /* Asigna valores de un objeto a un array asociativo en PHP. */
            $array["MixedBets"] = $value->{".apuestas_mixta"};
            $array["MixedAmount"] = $value->{".cantidad_mixta"};
            $array["HorseRacingAwards"] = $value->{".premios_hipicas"};
            $array["HorseRacingBets"] = $value->{".apuestas_hipicas"};
            $array["HorseRacingAmount"] = $value->{".cantidad_hipicas"};
            $array["VirtualAwards"] = $value->{".premios_virtuales"};

            /* calcula ganancias netas (ggr) de diferentes tipos de apuestas. */
            $array["VirtualBets"] = $value->{".apuestas_virtuales"};
            $array["VirtualAmount"] = $value->{".cantidad_virtuales"};
            $array["ggrLive"] = $array["LiveBets"] - $array["LiveAwards"];
            $array["ggrPreMatch"] = $array["PrematchBets"] - $array["PrematchAwards"];
            $array["ggrMixed"] = $array["MixedBets"] - $array["MixedAwards"];
            $array["ggrHorseRacing"] = $array["HorseRacingBets"] - $array["HorseRacingAwards"];

            /* Calcula la diferencia entre apuestas y premios virtuales, agregando a un arreglo final. */
            $array["ggrVirtual"] = $array["VirtualBets"] - $array["VirtualAwards"];

            array_push($final, $array);
        }
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    /* asigna valores a un array de respuesta: posición, total y datos finales. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = oldCount($final);
    $response["data"] = $final;
} else {
    /* Generación de respuesta vacía */

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = [];

}


/* Redefinición estructura de consulta ante intervalos de fecha solicitados */
if ($seguir && date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour ')) == date("Y-m-d", time())) {
    $rules = [];

    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


    /* Agrega reglas a un array si los IDs de pago y caja no están vacíos. */
    if ($PaymentSystemId != "") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
    }

    if ($CashDeskId != "") {
        array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
    }

    /* Agrega reglas de filtrado según ClientId y AmountFrom si no están vacíos. */
    if ($ClientId != "") {
        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }

    if ($AmountFrom != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
    }

    /* Agrega reglas basadas en valores de monto y moneda si no están vacíos. */
    if ($AmountTo != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
    }

    if ($CurrencyId != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }

    /* Agrega reglas a un arreglo si $ExternalId o $Id no están vacíos. */
    if ($ExternalId != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
    }
    if ($Id != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
    }


    /* asigna $CountrySelect a $Pais si no está vacío o es "0". */
    if ($CountrySelect != "" && $CountrySelect != "0") {
        $Pais = $CountrySelect;

    }


    /* Se define agrupamiento y selección de datos en función de una condición. */
    $grouping = "";
    $select = "";
    if ($IsDetails) {

    } else {
        $grouping = " usuario_recarga.puntoventa_id,producto.producto_id ";
        $select = "SUM(usuario_recarga.valor) valoru,SUM(transaccion_producto.valor) valor, ";
//array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));

    }


    /* Crea un filtro con reglas y establece filas a omitir si está vacío. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");


    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }


    /* inicializa variables si están vacías, asignando valores predeterminados. */
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 5;
    }


    /* convierte la variable $filtro a formato JSON utilizando json_encode. */
    $json = json_encode($filtro);

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        $Pais = $_SESSION['pais_id'];
    }

    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($Partner !== '') $Mandante = $Partner;
    elseif ($_SESSION['Global'] == "N") {
        $Mandante = strtolower($_SESSION['mandante']);
    } else {
        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            $Mandante = $_SESSION["mandanteLista"];
        }

    }


    /* obtiene transacciones de un punto de venta, filtrando por fechas y parámetros. */
    $FromDateLocal = date("Y-m-d");
    $ToDateLocal = date("Y-m-d");


    $transacciones = $PuntoVenta->getInformeGerencial($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $TypeBet, $Pais, $Mandante, $TypeUser, $WalletId);


    $transacciones = json_decode($transacciones);

    foreach ($transacciones->data as $key => $value) {

        /* crea un arreglo con información de país y fecha, eliminando tildes. */
        $array = [];

        $array["Pais"] = (new ConfigurationEnvironment())->quitar_tildes($value->{"x.pais_nom"});
        $array["CountryIcon"] = strtolower($value->{"x.pais_iso"});

        if ($TypeBet == 2) {
            $array["Fecha"] = $value->{"x.fecha_cierre"};

        } else {
            /* Asignar la fecha de creación de un objeto a un arreglo en PHP. */

            $array["Fecha"] = $value->{"x.fecha_crea"};

        }

        /* asigna valores de un objeto a un array asociativo en PHP. */
        $array["Moneda"] = $value->{"x.moneda"};
        $array["CantidadTickets"] = $value->{"x.cant_tickets"};
        $array["Stake"] = $value->{"x.valor_apostado"};
        $array["StakePromedio"] = $value->{"x.valor_ticket_prom"};
        $array["Payout"] = $value->{"x.valor_premios"};
        $array["UsersRegistered"] = $value->{"pl3.registros"};

        /* asigna valores a un array a partir de un objeto. */
        $array["FirstDeposits"] = $value->{"pl4.primerdepositos"};
        $array["Partner"] = $value->{"x.mandante"};

        $array["TaxBet"] = $value->{"x.impuesto_apuestas"};
        $array["Tax"] = $value->{"x.impuesto_premios"};

        $array["Bonos"] = ($value->{"pl2.bonos"} == "") ? 0 : $value->{"pl2.bonos"};

        /* calcula y almacena premios y porcentajes en un arreglo asociativo. */
        $array["TotalJackpotPrizeSum"] = (string)($value->{"pl5.jackpots"} == "") ? 0 : $value->{"pl5.jackpots"};
        $array["BonosCasino"] = ($value->{"pl4.bonoscasino"} == "") ? 0 : $value->{"pl4.bonoscasino"};
        $array["Ggr"] = $array["Stake"] - $array["Payout"] - $array["Bonos"];
        if ($array["Stake"] != 0) $array["GgrPorc"] = ($array["Ggr"] / $array["Stake"]) * 100;


        /* Asigna valores de un objeto a un array asociativo en PHP. */
        $array["LiveAwards"] = $value->{"x.premios_live"};
        $array["LiveBets"] = $value->{"x.apuestas_live"};
        $array["LiveAmount"] = $value->{"x.cantidad_live"};
        $array["PrematchAwards"] = $value->{"x.premios_prematch"};
        $array["PrematchBets"] = $value->{"x.apuestas_prematch"};
        $array["PrematchAmount"] = $value->{"x.cantidad_prematch"};

        /* Asigna valores de un objeto a un array asociativo en PHP. */
        $array["MixedAwards"] = $value->{"x.premios_mixta"};
        $array["MixedBets"] = $value->{"x.apuestas_mixta"};
        $array["MixedAmount"] = $value->{"x.cantidad_mixta"};
        $array["HorseRacingAwards"] = $value->{"x.premios_hipicas"};
        $array["HorseRacingBets"] = $value->{"x.apuestas_hipicas"};
        $array["HorseRacingAmount"] = $value->{"x.cantidad_hipicas"};

        /* Se asignan valores y se calculan ganancias brutas de diferentes apuestas virtuales. */
        $array["VirtualAwards"] = $value->{"x.premios_virtuales"};
        $array["VirtualBets"] = $value->{"x.apuestas_virtuales"};
        $array["VirtualAmount"] = $value->{"x.cantidad_virtuales"};
        $array["ggrLive"] = $array["LiveBets"] - $array["LiveAwards"];
        $array["ggrPreMatch"] = $array["PreMatchBets"] - $array["PreMatchAwards"];
        $array["ggrMixed"] = $array["MixedBets"] - $array["MixedAwards"];

        /* Calcula ganancias netas restando premios de las apuestas y las agrega a un arreglo final. */
        $array["ggrHorseRacing"] = $array["HorseRacingBets"] - $array["HorseRacingAwards"];
        $array["ggrVirtual"] = $array["VirtualBets"] - $array["VirtualAwards"];

        array_push($final, $array);
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);

    /*Generación de respuesta con datos solicitados*/
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $transacciones->count[0]->{".count"};
    $response["data"] = $final;
}


/* inicializa un array de respuesta con posición, total y datos finales. */
$response["pos"] = 0;
$response["total_count"] = oldCount($final);
$response["data"] = $final;
