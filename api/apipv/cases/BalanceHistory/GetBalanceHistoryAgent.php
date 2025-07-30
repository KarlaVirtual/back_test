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
 * BalanceHistory/GetBalanceHistory
 *
 * Obtener el historial de saldo de los usuarios
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 * @param $params ->IsDetails Indica si se deben mostrar los detalles.
 * @param $params ->CurrencyId ID de la moneda.
 * @param $params ->IsTest Indica si es una prueba.
 * @param $params ->ProductId ID del producto.
 * @param $params ->ProviderId ID del proveedor.
 * @param $params ->Region Región del usuario.
 * @param $params ->MaxRows Número máximo de filas a obtener.
 * @param $params ->OrderedItem Elemento ordenado.
 * @param $params ->SkeepRows Número de filas a omitir.
 * @param $params ->dateTo Fecha final.
 * @param $params ->dateFrom Fecha inicial.
 *
 * @return array Respuesta con los siguientes valores:
 *  - bool $HasError Indica si hubo un error.
 *  - string $AlertType Tipo de alerta.
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Errores del modelo.
 *  - int $pos Posición de las filas omitidas.
 *  - int $total_count Conteo total de filas.
 *  - array $data Datos obtenidos.
 */


/* Crea un objeto Submenu y luego un PerfilSubmenu basado en la sesión del usuario. */
$Submenu = new Submenu("", "balanceHistoryForAgent", '3');


try {
    $PerfilSubmenu = new PerfilSubmenu($_SESSION["win_perfil"], $Submenu->getSubmenuId());

} catch (Exception $e) {
    /* Manejo de excepciones en PHP, creando un objeto 'PerfilSubmenu' en caso de error. */

    $PerfilSubmenu = new PerfilSubmenu('CUSTOM', $Submenu->getSubmenuId(), $_SESSION["usuario"]);
}


/* Se crean variables que reciben datos JSON para un nuevo objeto Usuario. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);
$IsDetails = $params->IsDetails;
$CurrencyId = $params->CurrencyId;

/* extrae parámetros de entrada para su uso en un proceso. */
$IsTest = $params->IsTest;
$ProductId = $params->ProductId;
$ProviderId = $params->ProviderId;
$Region = $params->Region;

$MaxRows = $params->MaxRows;

/* Variables extraen parámetros como fechas y elementos ordenados de un objeto recibido. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;
$ToDateLocal = $params->dateTo;

$BetShopOwn = $params->BetShopOwn;


$FromDateLocal = $params->dateFrom;


/* procesa fechas de entrada para convertirlas a un formato específico. */
if ($_REQUEST["dateFrom"] != "") {
    //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
    $FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));

}
if ($_REQUEST["dateTo"] != "") {
    //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
    $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));

}


/* obtiene y valida parámetros de entrada mediante solicitudes HTTP. */
$PlayerId = $_REQUEST['PlayerId'];
$UserId = $_REQUEST['UserId'];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';

$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';
$ProductId = $_REQUEST["ProductId"];

/* captura datos enviados a través de solicitudes HTTP. */
$IsDetails = $_REQUEST["IsDetails"];
$Type = $_REQUEST["Type"];
$BetShopOwn = $_REQUEST["BetShopOwn"];

$UserIdAgent = $_REQUEST["UserIdAgent"];
$UserIdAgent2 = $_REQUEST["UserIdAgent2"];


/* obtiene parámetros de solicitud y determina si continuar con la operación. */
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$seguir = true;

if ($SkeepRows == "") {
    $seguir = false;
}


/* asigna valores predeterminados a variables vacías en un contexto específico. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $seguir = false;
}

if ($seguir) {


    /* alterna el valor booleano de la variable $IsDetails. */
    if ($IsDetails == 1) {
        $IsDetails = false;

    } else {
        $IsDetails = true;
    }


    /* Genera reglas de filtrado para fechas de saldo de usuario. */
    $rules = [];

    if ($FromDateLocal == $ToDateLocal && $FromDateLocal != "") {
        array_push($rules, array("field" => "usuario_saldo.fecha", "data" => "$FromDateLocal ", "op" => "eq"));
    } else {
        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "usuario_saldo.fecha", "data" => "$FromDateLocal ", "op" => "ge"));

        }

        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "usuario_saldo.fecha", "data" => "$ToDateLocal", "op" => "le"));
        }

    }


    /* Agrega reglas a un arreglo basadas en condiciones de región y jugador. */
    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }

    if ($PlayerId != "") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => "$PlayerId", "op" => "eq"));

    }


    /* añade reglas de filtrado basadas en condiciones específicas. */
    if ($UserId != "") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => "$UserId", "op" => "eq"));

    }

    if ($BetShopOwn == 'S') {
        array_push($rules, array("field" => "punto_venta.propio", "data" => "S", "op" => "eq"));
    }


    /* Añade reglas a un array basado en condiciones específicas de propiedades. */
    if ($BetShopOwn == 'N') {
        array_push($rules, array("field" => "punto_venta.propio", "data" => "N", "op" => "eq"));

    }


    if ($CountrySelect != "" && $CountrySelect != "0") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
    }

    // Si el usuario esta condicionado por País

    /* Agrega reglas basadas en condiciones de sesión del usuario y su país o mandante. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Se agrega una regla si "mandanteLista" no está vacía ni es "-1". */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }


    /* Agrega reglas al array según el perfil del usuario en sesión. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));

    }

    if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));

    }

    /* verifica el perfil de usuario y aplica reglas específicas para cada uno. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));

    }


    if ($_SESSION["win_perfil2"] == "CAJERO") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));

    }


    /* agrega reglas de filtro basadas en condiciones de sesión y usuario. */
    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));

    }


    if ($UserIdAgent != "") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => "$UserIdAgent", "op" => "eq"));

    }


    /* Se agregan reglas para filtrar usuarios según ID y perfil en reportes. */
    if ($UserIdAgent2 != "") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => "$UserIdAgent2", "op" => "eq"));

    }


    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO','CONCESIONARIO2','CONCESIONARIO3'", "op" => "in"));


    /* Se crea un filtro en formato JSON con reglas y operación "AND". */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $select = "
       SUM(saldo_inicial) saldo_inicial,
              SUM(saldo_creditos_inicial) saldo_creditos_inicial,
       SUM(saldo_creditos_base_inicial) saldo_creditos_base_inicial,


       SUM(saldo_recarga) saldo_recarga,
       SUM(saldo_notaret_creadas) saldo_notaret_creadas,
       SUM(saldo_notaret_eliminadas) saldo_notaret_eliminadas,
       SUM(saldo_notaret_pagadas) saldo_notaret_pagadas,
       SUM(saldo_notaret_pend) saldo_notaret_pend,
       SUM(saldo_notaret_pagadas) saldo_notaret_pagadas,
       
       SUM(saldo_apuestas) saldo_apuestas,
       SUM(saldo_premios) saldo_premios,
       SUM(saldo_impuestos_premios_deportivas) saldo_impuestos_premios_deportivas,
       SUM(saldo_impuestos_apuestas_deportivas) saldo_impuestos_apuestas_deportivas,
       SUM(saldo_apuestas_casino) saldo_apuestas_casino,
       SUM(saldo_premios_casino) saldo_premios_casino,
       SUM(saldo_bono) saldo_bono,
       SUM(saldo_bono_free_ganado) saldo_bono_free_ganado,
       SUM(saldo_ajustes_entrada) saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida) saldo_ajustes_salida,
       
       SUM(saldo_final) saldo_final,
       
       SUM(saldo_creditos_base_final) saldo_creditos_base_final,
       
       SUM(saldo_creditos_final) saldo_creditos_final,usuario.nombre,pais.iso,usuario_punto_venta.nombre,usuario_punto_venta.usuario_id,usuario_saldo.fecha ";


    /* define qué campos agrupar en una consulta de base de datos. */
    $grouping = "usuario_saldo.fecha,usuario.puntoventa_id";

    if ($Type == 1) {
        $select = "
                    pais.iso,
                    usuario_saldo.fecha,
       SUM(saldo_inicial) saldo_inicial,
              SUM(saldo_creditos_inicial) saldo_creditos_inicial,
       SUM(saldo_creditos_base_inicial) saldo_creditos_base_inicial,


       SUM(saldo_recarga) saldo_recarga,
       SUM(saldo_notaret_creadas) saldo_notaret_creadas,
       SUM(saldo_notaret_eliminadas) saldo_notaret_eliminadas,
       SUM(saldo_notaret_pagadas) saldo_notaret_pagadas,
       SUM(saldo_notaret_pend) saldo_notaret_pend,
       SUM(saldo_notaret_pagadas) saldo_notaret_pagadas,
       
       SUM(saldo_apuestas) saldo_apuestas,
       SUM(saldo_premios) saldo_premios,
       SUM(saldo_impuestos_premios_deportivas) saldo_impuestos_premios_deportivas,
       SUM(saldo_impuestos_apuestas_deportivas) saldo_impuestos_apuestas_deportivas,
       SUM(saldo_apuestas_casino) saldo_apuestas_casino,
       SUM(saldo_premios_casino) saldo_premios_casino,
       SUM(saldo_bono) saldo_bono,
       SUM(saldo_bono_free_ganado) saldo_bono_free_ganado,
       SUM(saldo_ajustes_entrada) saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida) saldo_ajustes_salida,
       
       SUM(saldo_final) saldo_final,
       
       SUM(saldo_creditos_base_final) saldo_creditos_base_final,
       
       SUM(saldo_creditos_final) saldo_creditos_final
       
       ";


        /* Se define un grupo de agrupación para un análisis de datos basado en país y fecha. */
        $grouping = "usuario_punto_venta.pais_id,usuario_saldo.fecha";
    }


    /* Condicionalmente elimina sumatorias de columnas en una consulta SQL según la fecha. */
    if ($ToDateLocal <= '2024-10-28 00:00:00') {

        $select = str_replace('SUM(saldo_impuestos_premios_deportivas) saldo_impuestos_premios_deportivas,', '', $select);
        $select = str_replace('SUM(saldo_impuestos_apuestas_deportivas) saldo_impuestos_apuestas_deportivas,', '', $select);
    }

    $order = "usuario_saldo.fecha";

    /* establece el orden de clasificación basado en la solicitud del usuario. */
    $orderType = "desc";

    if ($_REQUEST["sort[AmountWin]"] != "") {
        $order = "saldo_premios";
        $orderType = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

    }


    /* Código que ordena saldos por depósitos según la solicitud del usuario. */
    if ($_REQUEST["sort[AmountDeposits]"] != "") {
        $order = "saldo_recarga";
        $orderType = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";
    }


    $UsuarioSaldo = new UsuarioSaldo();

    /* obtiene y procesa saldos de usuario en formato JSON. */
    $data = $UsuarioSaldo->getUsuarioSaldosCustom($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping, '1', false, true, $FromDateLocal, $ToDateLocal);
    $data = json_decode($data);


    $final = [];

    $papuestas = 0;

    /* Se inicializan las variables $ppremios y $pcont en cero. */
    $ppremios = 0;
    $pcont = 0;

    foreach ($data->data as $key => $value) {

        if ($Type == 0) {


            /* asigna valores de un objeto a un array asociativo en PHP. */
            $array["Id"] = $value->{"usuario_saldo.usuario_id"};
            $array["UserId"] = $value->{"usuario_punto_venta.usuario_id"};

            $array["Country"] = strtolower($value->{"pais.iso"});
            $array["UserName"] = $value->{"usuario_punto_venta.nombre"};
            $array["CreatedLocalDate"] = $value->{"usuario_saldo.fecha"};


            /* Calcula y redondea diferentes cuotas y saldos del usuario a dos decimales. */
            $array["InitialRechargeQuota"] = round($value->{"usuario_saldo.saldo_creditos_inicial"}, 2);
            $array["InitialGameQuota"] = round($value->{"usuario_saldo.saldo_creditos_base_inicial"}, 2);

            $array["EndGameQuota"] = round($value->{"usuario_saldo.saldo_creditos_base_final"}, 2);
            $array["EndRechargeQuota"] = round($value->{"usuario_saldo.saldo_creditos_final"}, 2);


            $array["BalanceInitial"] = round($value->{"usuario_saldo.saldo_inicial"}, 2);

            /* redondea y almacena saldos relacionados a apuestas y premios en un array. */
            $array["AmountDeposits"] = round($value->{"usuario_saldo.saldo_recarga"}, 2);
            $array["AmountBets"] = round($value->{"usuario_saldo.saldo_apuestas"}, 2);
            $array["AmountWin"] = round($value->{"usuario_saldo.saldo_premios"}, 2);
            $array["retentionOnBets"] = round($value->{".saldo_impuestos_apuestas_deportivas"}, 2);
            $array["retentionsPrizesSports"] = round($value->{".saldo_impuestos_premios_deportivas"}, 2);
            $array["AmountBetsCasino"] = round($value->{"usuario_saldo.saldo_apuestas_casino"}, 2);

            /* Se asignan valores redondeados de un objeto a un array asociativo en PHP. */
            $array["AmountWinCasino"] = round($value->{"usuario_saldo.saldo_premios_casino"}, 2);
            $array["WithdrawCreates"] = round($value->{"usuario_saldo.saldo_notaret_creadas"}, 2);
            $array["WithdrawPaid"] = round($value->{"usuario_saldo.saldo_notaret_pagadas"}, 2);
            $array["WithdrawPend"] = round($value->{"usuario_saldo.saldo_notaret_pend"}, 2);
            $array["AdjustmentE"] = round($value->{"usuario_saldo.saldo_ajustes_entrada"}, 2);
            $array["AdjustmentS"] = round($value->{"usuario_saldo.saldo_ajustes_salida"}, 2);

            /* calcula y redondea diferentes saldos de usuario en un array. */
            $array["Bonus"] = round($value->{"usuario_saldo.saldo_bono"}, 2);
            $array["BalanceEnd"] = round($value->{"usuario_saldo.saldo_final"}, 2);
            $array["BalanceEnd"] = round($value->{"usuario_saldo.saldo_creditos_base_final"} + $value->{"usuario_saldo.saldo_creditos_final"}, 2);
            $array["BonusFreeWin"] = round($value->{"usuario_saldo.saldo_bono_free_ganado"}, 2);
            $array["WithdrawDeletes"] = round($value->{"usuario_saldo.saldo_notaret_eliminadas"}, 2);

            $array["BalanceEndCalc"] = round($array["BalanceInitial"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["WithdrawPend"], 2);


            /* Asigna y redondea valores financieros a un arreglo asociativo. */
            $array["InitialRechargeQuota"] = round($value->{".saldo_creditos_inicial"}, 2);
            $array["InitialGameQuota"] = round($value->{".saldo_creditos_base_inicial"}, 2);
            $array["EndGameQuota"] = round($value->{".saldo_creditos_base_final"}, 2);
            $array["EndRechargeQuota"] = round($value->{".saldo_creditos_final"}, 2);

            $array["BalanceInitial"] = round($value->{".saldo_inicial"}, 2);

            /* redondea y asigna saldo a un array para diferentes categorías financieras. */
            $array["AmountDeposits"] = round($value->{".saldo_recarga"}, 2);

            $array["AmountBets"] = round($value->{".saldo_apuestas"}, 2);
            $array["AmountWin"] = round($value->{".saldo_premios"}, 2);
            $array["AmountBetsCasino"] = round($value->{".saldo_apuestas_casino"}, 2);
            $array["AmountWinCasino"] = round($value->{".saldo_premios_casino"}, 2);

            /* redondea valores de saldo de diferentes categorías y los almacena en un array. */
            $array["WithdrawCreates"] = round($value->{".saldo_notaret_creadas"}, 2);
            $array["WithdrawPaid"] = round($value->{".saldo_notaret_pagadas"}, 2);
            $array["WithdrawPend"] = round($value->{".saldo_notaret_pend"}, 2);
            $array["AdjustmentE"] = round($value->{".saldo_ajustes_entrada"}, 2);
            $array["AdjustmentS"] = round($value->{".saldo_ajustes_salida"}, 2);
            $array["Bonus"] = round($value->{".saldo_bono"}, 2);

            /* Se calculan y redondean diversos saldos para crear un balance final. */
            $array["BalanceEnd"] = round($value->{".saldo_final"}, 2);
            $array["BalanceEnd"] = round($value->{".saldo_creditos_base_final"} + $value->{".saldo_creditos_final"}, 2);

            $array["BonusFreeWin"] = round($value->{".saldo_bono_free_ganado"}, 2);
            $array["WithdrawDeletes"] = -round($value->{".saldo_notaret_eliminadas"}, 2);
            $array["BalanceEndCalc"] = round($array["BalanceInitial"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["WithdrawPend"], 2);

            /* Calcula cuotas finales de recarga y juego, almacenándolas en un array. */
            $array["EndRechargeQuotaCalc"] = round($array["InitialRechargeQuota"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"], 2);
            $array["EndGameQuotaCalc"] = round($array["InitialGameQuota"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"], 2);


            array_push($final, $array);

        } else {

            /* Código para inicializar un array con datos procesados y formateados. */
            $array["Id"] = 0;
            $array["UserId"] = 0;
            $array["Country"] = strtolower($value->{"pais.iso"});
            $array["UserName"] = '';
            $array["CreatedLocalDate"] = $value->{"usuario_saldo.fecha"};

            $array["InitialRechargeQuota"] = round($value->{".saldo_creditos_inicial"}, 2);

            /* redondea y asigna valores de saldo a un array. */
            $array["InitialGameQuota"] = round($value->{".saldo_creditos_base_inicial"}, 2);
            $array["EndGameQuota"] = round($value->{".saldo_creditos_base_final"}, 2);
            $array["EndRechargeQuota"] = round($value->{".saldo_creditos_final"}, 2);

            $array["BalanceInitial"] = round($value->{".saldo_inicial"}, 2);
            $array["AmountDeposits"] = round($value->{".saldo_recarga"}, 2);


            /* redondea y almacena valores financieros en un array asociativo. */
            $array["AmountBets"] = round($value->{".saldo_apuestas"}, 2);
            $array["AmountWin"] = round($value->{".saldo_premios"}, 2);
            $array["retentionOnBets"] = round($value->{".saldo_impuestos_apuestas_deportivas"}, 2);
            $array["retentionsPrizesSports"] = round($value->{".saldo_impuestos_premios_deportivas"}, 2);
            $array["AmountBetsCasino"] = round($value->{".saldo_apuestas_casino"}, 2);
            $array["AmountWinCasino"] = round($value->{".saldo_premios_casino"}, 2);

            /* redondea varios valores de transacciones financieras a dos decimales. */
            $array["WithdrawCreates"] = round($value->{".saldo_notaret_creadas"}, 2);
            $array["WithdrawPaid"] = round($value->{".saldo_notaret_pagadas"}, 2);
            $array["WithdrawPend"] = round($value->{".saldo_notaret_pend"}, 2);
            $array["AdjustmentE"] = round($value->{".saldo_ajustes_entrada"}, 2);
            $array["AdjustmentS"] = round($value->{".saldo_ajustes_salida"}, 2);
            $array["Bonus"] = round($value->{".saldo_bono"}, 2);

            /* Cálculo y ajuste de saldos financieros redondeados en un array asociativo. */
            $array["BalanceEnd"] = round($value->{".saldo_final"}, 2);
            $array["BalanceEnd"] = round($value->{".saldo_creditos_base_final"} + $value->{".saldo_creditos_final"}, 2);

            $array["BonusFreeWin"] = round($value->{".saldo_bono_free_ganado"}, 2);
            $array["WithdrawDeletes"] = -round($value->{".saldo_notaret_eliminadas"}, 2);
            $array["BalanceEndCalc"] = round($array["BalanceInitial"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["WithdrawPend"], 2);

            /* Calcula cuotas finales de recarga y juego, almacenando resultados en un arreglo. */
            $array["EndRechargeQuotaCalc"] = round($array["InitialRechargeQuota"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"], 2);
            $array["EndGameQuotaCalc"] = round($array["InitialGameQuota"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"], 2);

            array_push($final, $array);

        }


    }
    /*if (!$IsDetails) {
        if ($pcont > 0) {
            $array["Game"] = $prod->{"producto.descripcion"};
            $array["ProviderName"] = $prod->{"proveedor.descripcion"};
            $array["Bets"] = $pcont;
            $array["Stakes"] = $papuestas;
            $array["Winnings"] = $ppremios;
            $array["Profit"] = 0;
            $array["BonusCashBack"] = 0;
            $array["CurrencyId"] = $prod->{"usuario_mandante.moneda"};

            array_push($final, $array);
        }
    }*/


    /* inicializa una respuesta con éxito y sin errores, incluyendo datos adicionales. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["pos"] = $SkeepRows;

    /* establece un valor igual para "total_count" en ambas condiciones. */
    if ($Type == 0) {

        $response["total_count"] = oldCount($final);
    } else {
        $response["total_count"] = oldCount($final);

    }

    /* Asigna el valor de $final a la clave "data" en el array $response. */
    $response["data"] = $final;


} else {
    /* inicializa una respuesta sin errores y con valores predeterminados. */

    $response["HasError"] = false;
    $response["AlertType"] = "success2";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}