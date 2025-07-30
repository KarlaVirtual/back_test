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
 * @param object $params Objeto JSON decodificado que contiene los parámetros de entrada:
 * @param bool $IsDetails Indica si se deben mostrar los detalles.
 * @param int $CurrencyId ID de la moneda.
 * @param bool $IsTest Indica si es una prueba.
 * @param int $ProductId ID del producto.
 * @param int $ProviderId ID del proveedor.
 * @param string $Region Región.
 * @param int $MaxRows Número máximo de filas.
 * @param int $OrderedItem Artículo ordenado.
 * @param int $SkeepRows Número de filas a omitir.
 * @param string $dateTo Fecha de finalización.
 * @param string $dateFrom Fecha de inicio.
 *
 *
 * @return array Respuesta en formato JSON:
 *  - bool $HasError Indica si hubo un error.
 *  - string $AlertType Tipo de alerta.
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Errores del modelo.
 *  - int $pos Posición de las filas.
 *  - int $total_count Conteo total.
 *  - array $data Datos del historial de saldo.
 *
 */

/* inicializa configuraciones y decodifica datos JSON recibidos por entrada. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);
$IsDetails = $params->IsDetails;

/* Asignación de parámetros de entrada a variables en un script programático. */
$CurrencyId = $params->CurrencyId;
$IsTest = $params->IsTest;
$ProductId = $params->ProductId;
$ProviderId = $params->ProviderId;
$Region = $params->Region;

$MaxRows = $params->MaxRows;

/* Se extraen parámetros relacionados con artículos ordenados y fechas de un objeto `$params`. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;
$ToDateLocal = $params->dateTo;


$FromDateLocal = $params->dateFrom;


/* Asignación de país con base en condiciones de sesión en PHP. */
$pais_id = '';
if ($_SESSION['PaisCondS'] != '') {
    $pais_id = ($_SESSION['PaisCondS']);
} else {

    if ($_SESSION['PaisCond'] == "S") {
        $pais_id = $_SESSION['pais_id'];
    }


}


/* Se crea un submenú y se inicializa un perfil de submenú. */
$Submenu = new Submenu("", "balanceHistory", '3');


try {
    $PerfilSubmenu = new PerfilSubmenu($_SESSION["win_perfil"], $Submenu->getSubmenuId());

} catch (Exception $e) {
    /* Captura excepciones y crea un nuevo objeto PerfilSubmenu con datos de sesión. */

    $PerfilSubmenu = new PerfilSubmenu('CUSTOM', $Submenu->getSubmenuId(), $_SESSION["usuario"]);
}


/* procesa fechas recibidas en una solicitud para formatearlas adecuadamente. */
if ($_REQUEST["dateFrom"] != "") {
    //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
    $FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));

}
if ($_REQUEST["dateTo"] != "") {
    //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
    $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));

}


/* valida y asigna valores de entrada a variables para su uso posterior. */
$PlayerId = $_REQUEST['PlayerId'];
$UserId = $_REQUEST['UserId'];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';

$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';
$ProductId = $_REQUEST["ProductId"];

/* Código PHP que obtiene parámetros de una solicitud HTTP para procesar datos. */
$IsDetails = $_REQUEST["IsDetails"];
$Type = $_REQUEST["Type"];

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$seguir = true;


/* verifica condiciones y asigna valores a variables según resultados específicos. */
if ($SkeepRows == "") {
    $seguir = false;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* verifica si $MaxRows está vacío y asigna false a $seguir. */
if ($MaxRows == "") {
    $seguir = false;
}
if ($seguir && $Type == 1) {


    /* alterna el valor de la variable $IsDetails entre verdadero y falso. */
    if ($IsDetails == 1) {
        $IsDetails = false;

    } else {
        $IsDetails = true;
    }


    /* genera reglas de filtrado basadas en fechas de un saldo. */
    $rules = [];

    if ($FromDateLocal == $ToDateLocal && $FromDateLocal != "") {
        array_push($rules, array("field" => "bodega_usuario_saldo.fecha", "data" => "$FromDateLocal ", "op" => "eq"));
    } else {
        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "bodega_usuario_saldo.fecha", "data" => "$FromDateLocal ", "op" => "ge"));

        }

        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "bodega_usuario_saldo.fecha", "data" => "$ToDateLocal", "op" => "le"));
        }

    }


    /* Agrega condiciones a un array basado en la existencia de región o país. */
    if ($Region != "") {
        array_push($rules, array("field" => "bodega_usuario_saldo.pais_id", "data" => "$Region", "op" => "eq"));
    }

    if ($pais_id != "") {
        array_push($rules, array("field" => "bodega_usuario_saldo.pais_id", "data" => "$pais_id", "op" => "eq"));
    }


    /* verifica selección de país y condiciones de usuario para agregar reglas. */
    if ($CountrySelect != "" && $CountrySelect != "0") {
        array_push($rules, array("field" => "bodega_usuario_saldo.pais_id", "data" => "$CountrySelect", "op" => "eq"));
    }

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "bodega_usuario_saldo.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global

    /* Condiciona reglas basadas en el valor de sesión 'Global' y lista de mandantes. */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "bodega_usuario_saldo.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "bodega_usuario_saldo.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }
    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    /* Se crea un filtro JSON con reglas para validar condiciones específicas. */
    array_push($rules, array("field" => "bodega_usuario_saldo.tipo", "data" => "1", "op" => "eq"));
    array_push($rules, array("field" => "bodega_usuario_saldo.billetera_id", "data" => "0", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    /* establece el orden de una consulta basado en una dirección solicitada. */
    $order = " bodega_usuario_saldo.fecha ";
    $orderType = "desc";


    if ($_REQUEST["Dir"] == "0") {
        $orderType = "asc";

    }


    /* establece criterios de ordenamiento basados en la variable 'Sort' de la solicitud. */
    if ($_REQUEST['Sort'] == '1') {
        $order = " desfase ";
    }


    if ($_REQUEST['Sort'] == '2') {
        $order = " saldo_apuestas - saldo_premios ";
    }


    /* asigna un orden de clasificación basado en una condición de entrada. */
    if ($_REQUEST['Sort'] == '3') {
        $order = " saldo_apuestas_casino - saldo_premios_casino ";
    }

    $grouping = "";


    $select = "
                    pais.iso,
                    bodega_usuario_saldo.fecha,
       SUM(saldo_inicial) saldo_inicial,
       SUM(saldo_recarga) saldo_recarga,
       SUM(saldo_notaret_creadas) saldo_notaret_creadas,
       SUM(saldo_notaret_eliminadas) saldo_notaret_eliminadas,
       SUM(saldo_notaret_pagadas) saldo_notaret_pagadas,
       SUM(saldo_notaret_pend) saldo_notaret_pend,
       SUM(saldo_notaret_pagadas) saldo_notaret_pagadas,
       
       SUM(saldo_apuestas) saldo_apuestas,
       SUM(saldo_premios) saldo_premios,
       SUM(saldo_impuestos_premios_deportivas) saldo_impuestos_premios_deportivas,
       SUM(saldo_impuestos_depositos) saldo_impuestos_depositos,
       SUM(saldo_impuestos_apuestas_casino) saldo_impuestos_apuestas_casino,
       SUM(saldo_impuestos_apuestas_deportivas) saldo_impuestos_apuestas_deportivas,
       SUM(saldo_premios_jackpot_casino) saldo_premios_jackpot_casino,
       SUM(saldo_premios_jackpot_deportivas) saldo_premios_jackpot_deportivas,
       SUM(saldo_ventas_loteria) saldo_ventas_loteria,
       SUM(saldo_rollbacks_loteria) saldo_rollbacks_loteria,
       SUM(saldo_apuestas_casino) saldo_apuestas_casino,
       SUM(saldo_bono_casino_free_ganado) saldo_bono_casino_free_ganado,
       SUM(saldo_premios_casino) saldo_premios_casino,
       SUM(saldo_bono) saldo_bono,
       SUM(saldo_bono_casino_vivo) saldo_bono_casino_vivo,
       SUM(saldo_bono_free_ganado) saldo_bono_free_ganado,
       SUM(saldo_ajustes_entrada) saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida) saldo_ajustes_salida,
       
       SUM(saldo_final) saldo_final,
              SUM(saldo_inicial) + SUM(saldo_recarga) - SUM(saldo_apuestas) + SUM(saldo_premios) - SUM(saldo_apuestas_casino)
           + SUM(saldo_premios_casino) - SUM(saldo_notaret_creadas) + SUM(saldo_notaret_eliminadas) +
       SUM(saldo_ajustes_entrada) - sum(saldo_ajustes_salida) + sum(saldo_bono) + sum(saldo_bono_casino_vivo)-SUM(saldo_final) + 
       SUM(saldo_premios_jackpot_casino) + SUM(saldo_premios_jackpot_deportivas) desfase,

       SUM(saldo_creditos_base_final) saldo_creditos_base_final,
       SUM(saldo_creditos_final) saldo_creditos_final";
//,
//       SUM(usuario_recargas.usuarios_recargas)      usuarios_recargas,
//       SUM(usuario_recargas.cantidad_recargas)      cantidad_recargas

    /* Configura agrupación y ordenación de datos según criterios especificados en la solicitud. */
    $grouping = "bodega_usuario_saldo.pais_id,bodega_usuario_saldo.fecha";

    $conRecargas = true;

    if ($_REQUEST["sort"] != "") {

        if ($_REQUEST["sort"]["AmountWin"] != "") {
            $order = "saldo_premios";
            $orderType = ($_REQUEST["sort[AmountWin]"] == "asc") ? "asc" : "desc";

        }
        if ($_REQUEST["sort"]["AmountDeposits"] != "") {
            $order = "saldo_recarga";
            $orderType = ($_REQUEST["sort[AmountDeposits]"] == "asc") ? "asc" : "desc";
        }

    }


    /* Se obtiene el saldo de usuarios y se decodifica en formato JSON. */
    $UsuarioSaldo = new UsuarioSaldo();
    $data = $UsuarioSaldo->getUsuarioSaldosCustomBodega($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping, '', $conRecargas);
    $data = json_decode($data);


    $final = [];


    /* Inicializa contadores para apuestas, premios y un contador general en PHP. */
    $papuestas = 0;
    $ppremios = 0;
    $pcont = 0;

    foreach ($data->data as $key => $value) {


        /* Se asignan valores a un arreglo, incluyendo país y fecha de creación. */
        $array["Id"] = 0;
        $array["UserId"] = 0;
        $array["Country"] = strtolower($value->{"pais.iso"});

        $array["UserName"] = '';
        $array["CreatedLocalDate"] = $value->{"bodega_usuario_saldo.fecha"};


        /* redondea y asigna valores de saldo a un arreglo. */
        $array["BalanceInitial"] = round($value->{".saldo_inicial"}, 2);
        $array["AmountDeposits"] = round($value->{".saldo_recarga"}, 2);

        $array["AmountBets"] = round($value->{".saldo_apuestas"}, 2);
        $array["AmountWin"] = round($value->{".saldo_premios"}, 2);

        $array["retentionOnBets"] = round($value->{".saldo_impuestos_apuestas_deportivas"}, 2);

        /* redondea y asigna valores de impuestos y premios a un array. */
        $array["retentionOnBetsCasino"] = round($value->{".saldo_impuestos_apuestas_casino"}, 2);
        $array["retentionsPrizesSports"] = round($value->{".saldo_impuestos_premios_deportivas"}, 2);
        $array["retentionsDeposit"] = round($value->{".saldo_impuestos_depositos"}, 2);

        $array["AwardsJackpotCasino"] = round($value->{".saldo_premios_jackpot_casino"}, 2);
        $array["AwardsJackpotSport"] = round($value->{".saldo_premios_jackpot_deportivas"}, 2);

        /* redondea y asigna saldos de diferentes categorías a un array. */
        $array["StakesLotteries"] = round($value->{".saldo_ventas_loteria"}, 2);
        $array["AwardsLotteries"] = round($value->{".saldo_rollbacks_loteria"}, 2);
        $array["AmountBetsCasino"] = round($value->{".saldo_apuestas_casino"}, 2);
        $array["BonusFreeCasino"] = round($value->{".saldo_bono_casino_free_ganado"}, 2);
        $array["AmountWinCasino"] = round($value->{".saldo_premios_casino"}, 2);
        $array["WithdrawCreates"] = round($value->{".saldo_notaret_creadas"}, 2);

        /* Se redondean y almacenan saldos financieros en un array asociativo. */
        $array["WithdrawPaid"] = round($value->{".saldo_notaret_pagadas"}, 2);
        $array["WithdrawPend"] = round($value->{".saldo_notaret_pend"}, 2);
        $array["AdjustmentE"] = round($value->{".saldo_ajustes_entrada"}, 2);
        $array["AdjustmentS"] = round($value->{".saldo_ajustes_salida"}, 2);
        $array["Bonus"] = round($value->{".saldo_bono"}, 2);
        $array["BonusCasino"] = round($value->{".saldo_bono_casino_vivo"}, 2);

        /* calcula y redondea saldos y ajustes financieros en un array. */
        $array["BalanceEnd"] = round($value->{".saldo_final"}, 2);
        $array["BalanceEnd"] = round($value->{".saldo_creditos_base_final"} + $value->{".saldo_creditos_final"}, 2);

        $array["BonusFreeWin"] = round($value->{".saldo_bono_free_ganado"}, 2);
        $array["WithdrawDeletes"] = round($value->{".saldo_notaret_eliminadas"}, 2);
        $array["BalanceEndCalc"] = round($array["BalanceInitial"] + $array["AmountDeposits"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] - $array["retentionOnBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["Bonus"] + $array["BonusCasino"] - $array["retentionsPrizesSports"] - $array["retentionOnBets"] + $array["AwardsJackpotCasino"] + $array["AwardsJackpotSport"] - $array["StakesLotteries"] + $array["AwardsLotteries"], 2);


        /* calcula y redondea cifras relacionadas con depósitos y ganancias. */
        $array["DepositsUsersCount"] = round($value->{".usuarios_recargas"}, 2);
        $array["DepositsCount"] = round($value->{".cantidad_recargas"}, 2);


        $array["GgrAmountWin"] = round(floatval($array["AmountBets"]) - floatval($array["AmountWin"]) - floatval($array["Bonus"]), 2);
        $array["GgrAmountWinCasino"] = round(floatval($array["AmountBetsCasino"]) - floatval($array["BonusCasino"]) - floatval($array["AmountWinCasino"]), 2);


        /* Agrega un array al final de otro array en PHP. */
        array_push($final, $array);


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


    /* inicializa una respuesta sin errores y define propiedades de alerta. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["pos"] = $SkeepRows;

    /* asigna un conteo total basado en la condición del tipo. */
    if ($Type == 0) {

        $response["total_count"] = $data->count[0]->{".count"};;
    } else {
        $response["total_count"] = oldCount($final);

    }

    /* Asigna el valor de $final al campo "data" del array $response. */
    $response["data"] = $final;


} elseif ($seguir) {


    /* Invierte el valor de la variable $IsDetails entre true y false. */
    if ($IsDetails == 1) {
        $IsDetails = false;

    } else {
        $IsDetails = true;
    }


    /* Genera reglas de filtro basadas en fechas ingresadas por el usuario. */
    $rules = [];

    if ($FromDateLocal == $ToDateLocal && $FromDateLocal != "") {
        array_push($rules, array("field" => "usuario_saldo.fecha", "data" => "$FromDateLocal", "op" => "eq"));
    } else {
        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "usuario_saldo.fecha", "data" => "$FromDateLocal", "op" => "ge"));

        }

        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "usuario_saldo.fecha", "data" => "$ToDateLocal", "op" => "le"));
        }

    }


    /* Condicionalmente agrega reglas de filtrado según región y país en un array. */
    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }

    if ($pais_id != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$pais_id", "op" => "eq"));
    }


    /* Se agregan condiciones para filtrar usuarios por PlayerId o UserId. */
    if ($PlayerId != "") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => "$PlayerId", "op" => "eq"));

    }

    if ($UserId != "") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => "$UserId", "op" => "eq"));

    }


    /* Se añaden reglas basadas en la selección de país y la sesión del usuario. */
    if ($CountrySelect != "" && $CountrySelect != "0") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
    }

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global

    /* Añade reglas de filtrado según la sesión de mandante y su estado. */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }
    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    /* agrega reglas de filtrado y transforma datos en una consulta SQL JSON. */
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $select = "       SUM(saldo_final) saldo_final,
              SUM(saldo_inicial) + SUM(saldo_recarga) - SUM(saldo_apuestas) + SUM(saldo_premios) - SUM(saldo_apuestas_casino) saldo_apuestas_casino
           + SUM(saldo_premios_casino) saldo_premios_casino - SUM(saldo_notaret_creadas) + SUM(saldo_notaret_eliminadas) +
       SUM(saldo_ajustes_entrada) - sum(saldo_ajustes_salida) + sum(saldo_bono) + sum(saldo_bono_casino_vivo)-SUM(saldo_final) desfase,usuario_saldo.*,usuario.nombre,pais.iso ";


    /* Selecciona y ordena saldos, calculando desfases de usuarios en base a diversos saldos. */
    $select = "       (saldo_final) saldo_final,
              (saldo_inicial) + (saldo_recarga) - (saldo_apuestas) + (saldo_premios)  - (saldo_apuestas_casino)
           + (saldo_premios_casino) - (saldo_notaret_creadas) + (saldo_notaret_eliminadas) +
       (saldo_ajustes_entrada) - (saldo_ajustes_salida) + (saldo_bono) + (saldo_bono_casino_vivo) -(saldo_final) desfase,usuario_saldo.*,usuario.nombre,pais.iso ";

    $order = " desfase ";

    /* ajusta el tipo de orden según el valor de "Dir" recibido. */
    $orderType = "desc";


    if ($_REQUEST["Dir"] == "0") {
        $orderType = "asc";

    }


    /* asigna un valor a `$order` según el parámetro `Sort` recibido. */
    if ($_REQUEST['Sort'] == '1') {
        $order = " desfase ";
    }


    if ($_REQUEST['Sort'] == '2') {
        $order = " saldo_apuestas - saldo_premios ";
    }


    /* asigna un orden específico si la solicitud es '3' y configura variables. */
    if ($_REQUEST['Sort'] == '3') {
        $order = " saldo_apuestas_casino - saldo_premios_casino ";
    }

    $grouping = "";
    $conRecargas = false;

    if ($Type == 1) {
        $select = "
                    pais.iso,
                    usuario_saldo.fecha,
       SUM(saldo_inicial) saldo_inicial,
       SUM(saldo_recarga) saldo_recarga,
       SUM(saldo_notaret_creadas) saldo_notaret_creadas,
       SUM(saldo_notaret_eliminadas) saldo_notaret_eliminadas,
       SUM(saldo_notaret_pagadas) saldo_notaret_pagadas,
       SUM(saldo_notaret_pend) saldo_notaret_pend,
       SUM(saldo_notaret_pagadas) saldo_notaret_pagadas,
       
       SUM(saldo_apuestas) saldo_apuestas,
       SUM(saldo_premios) saldo_premios,
       
       SUM(saldo_impuestos_premios_deportivas) saldo_impuestos_premios_deportivas,
       SUM(saldo_impuestos_depositos) saldo_impuestos_depositos,
       SUM(saldo_impuestos_apuestas_deportivas) saldo_impuestos_apuestas_deportivas,
       SUM(saldo_impuestos_apuestas_casino) saldo_impuestos_apuestas_casino,
       SUM(saldo_premios_jackpot_casino) saldo_premios_jackpot_casino,
       SUM(saldo_premios_jackpot_deportivas) saldo_premios_jackpot_deportivas,
       SUM(saldo_ventas_loteria) saldo_ventas_loteria,
       SUM(saldo_rollbacks_loteria) saldo_rollbacks_loteria,
       
       SUM(saldo_apuestas_casino) saldo_apuestas_casino,
       SUM(saldo_bono_casino_free_ganado) saldo_bono_casino_free_ganado,
       SUM(saldo_premios_casino) saldo_premios_casino,
       SUM(saldo_bono) saldo_bono,
       SUM(saldo_bono_casino_vivo) saldo_bono_casino_vivo,
       
       SUM(saldo_bono_free_ganado) saldo_bono_free_ganado,
       SUM(saldo_ajustes_entrada) saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida) saldo_ajustes_salida,
       
       SUM(saldo_final) saldo_final,
              SUM(saldo_inicial) + SUM(saldo_recarga) - SUM(saldo_apuestas) + SUM(saldo_premios)  - SUM(saldo_apuestas_casino)
           + SUM(saldo_premios_casino) - SUM(saldo_notaret_creadas) + SUM(saldo_notaret_eliminadas) +
       SUM(saldo_ajustes_entrada) - sum(saldo_ajustes_salida) + sum(saldo_bono) + SUM(saldo_bono_casino_vivo) -SUM(saldo_final) desfase,

       SUM(saldo_creditos_base_final) saldo_creditos_base_final,
       SUM(saldo_creditos_final) saldo_creditos_final,
       SUM(usuario_recargas.usuarios_recargas)      usuarios_recargas,
       SUM(usuario_recargas.cantidad_recargas)      cantidad_recargas";


        /* establece un agrupamiento y una variable booleana para recargas. */
        $grouping = "usuario.pais_id,usuario_saldo.fecha";

        $conRecargas = true;

    } else {
        /* muestra una estructura condicional que incluye comentarios sobre agrupaciones y ordenamientos. */

        //$grouping = "usuario.usuario_id,usuario_saldo.fecha";

        //$order = " desfase ";
        //$orderType = "asc";

    }


    /* determina el orden de los resultados según solicitudes de clasificación. */
    if ($_REQUEST["sort"] != "") {

        if ($_REQUEST["sort"]["AmountWin"] != "") {
            $order = "saldo_premios";
            $orderType = ($_REQUEST["sort[AmountWin]"] == "asc") ? "asc" : "desc";

        }
        if ($_REQUEST["sort"]["AmountDeposits"] != "") {
            $order = "saldo_recarga";
            $orderType = ($_REQUEST["sort[AmountDeposits]"] == "asc") ? "asc" : "desc";
        }

    }


    /* asigna un orden de clasificación según el valor de 'Sort' recibido. */
    if ($_REQUEST['Sort'] == '1') {
        $order = " desfase ";
    }


    if ($_REQUEST['Sort'] == '2') {
        $order = " saldo_apuestas - saldo_premios ";
    }


    /* ajusta la consulta SQL según condiciones específicas relacionadas con la fecha y ordenamiento. */
    if ($_REQUEST['Sort'] == '3') {
        $order = " saldo_apuestas_casino - saldo_premios_casino ";
    }
    if ($ToDateLocal <= '2024-10-28 00:00:00') {

        $select = str_replace('SUM(saldo_impuestos_premios_deportivas) saldo_impuestos_premios_deportivas,', '', $select);
        $select = str_replace('SUM(saldo_impuestos_apuestas_deportivas) saldo_impuestos_apuestas_deportivas,', '', $select);
    }


    /* elimina ciertas sumas del select dependiendo de una fecha específica. */
    if ($ToDateLocal <= '2025-01-08 00:00:00') {
        $select = str_replace('SUM(saldo_impuestos_depositos) saldo_impuestos_depositos,', '', $select);
    }
    if ($ToDateLocal <= '2025-01-08 00:00:00') {
        $select = str_replace('SUM(saldo_impuestos_apuestas_casino) saldo_impuestos_apuestas_casino,', '', $select);
    }


    /* ajusta consultas SQL según condiciones de fecha y entorno de producción. */
    if ($ToDateLocal <= '2024-12-04 00:00:00' && $ConfigurationEnvironment->isProduction()) {
        $select = str_replace('SUM(saldo_premios_jackpot_casino) saldo_premios_jackpot_casino,', '', $select);
        $select = str_replace('SUM(saldo_premios_jackpot_deportivas) saldo_premios_jackpot_deportivas,', '', $select);
    }

    if ($ToDateLocal <= '2024-12-26 00:00:00' && $ConfigurationEnvironment->isProduction()) {
        $select = str_replace('SUM(saldo_ventas_loteria) saldo_ventas_loteria,', '', $select);
        $select = str_replace('SUM(saldo_rollbacks_loteria) saldo_rollbacks_loteria,', '', $select);
    }


    /* Se obtiene y decodifica saldo de usuario en formato JSON. */
    $UsuarioSaldo = new UsuarioSaldo();
    $data = $UsuarioSaldo->getUsuarioSaldosCustom($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping, '', $conRecargas, true, $FromDateLocal, $ToDateLocal);
    $data = json_decode($data);


    $final = [];


    /* Inicializa variables para llevar el control de apuestas, premios y conteo. */
    $papuestas = 0;
    $ppremios = 0;
    $pcont = 0;

    foreach ($data->data as $key => $value) {

        if ($Type == 0) {


            /* Inicializa un array con datos extraídos y formateados de un objeto. */
            $array["Id"] = $value->{"usuario_saldo.ususaldo_id"};
            $array["UserId"] = $value->{"usuario_saldo.usuario_id"};
            $array["Country"] = strtolower($value->{"pais.iso"});
            $array["UserName"] = $value->{"usuario.nombre"};
            $array["CreatedLocalDate"] = $value->{"usuario_saldo.fecha"};

            $array["BalanceInitial"] = round($value->{"usuario_saldo.saldo_inicial"}, 2);

            /* Se calcula y redondea el saldo y retenciones de un usuario en apuestas. */
            $array["AmountDeposits"] = round($value->{"usuario_saldo.saldo_recarga"}, 2);
            $array["AmountBets"] = round($value->{"usuario_saldo.saldo_apuestas"}, 2);
            $array["AmountWin"] = round($value->{"usuario_saldo.saldo_premios"}, 2);

            $array["retentionOnBets"] = round($value->{"usuario_saldo.saldo_impuestos_apuestas_deportivas"}, 2);
            $array["retentionOnBetsCasino"] = round($value->{"usuario_saldo.saldo_impuestos_apuestas_casino"}, 2);

            /* Asigna y redondea valores de saldo a un array para premios y depósitos. */
            $array["retentionsPrizesSports"] = round($value->{"usuario_saldo.saldo_impuestos_premios_deportivas"}, 2);
            $array["retentionsDeposit"] = round($value->{"usuario_saldo.saldo_impuestos_depositos"}, 2);
            $array["AwardsJackpotCasino"] = round($value->{"usuario_saldo.saldo_premios_jackpot_casino"}, 2);
            $array["AwardsJackpotSport"] = round($value->{"usuario_saldo.saldo_premios_jackpot_deportivas"}, 2);
            $array["StakesLotteries"] = round($value->{"usuario_saldo.saldo_ventas_loteria"}, 2);
            $array["AwardsLotteries"] = round($value->{"usuario_saldo.saldo_rollbacks_loteria"}, 2);

            /* redondea y almacena saldos relacionados con apuestas y retiros de casino. */
            $array["AmountBetsCasino"] = round($value->{"usuario_saldo.saldo_apuestas_casino"}, 2);
            $array["BonusFreeCasino"] = round($value->{"usuario_saldo.saldo_bono_casino_free_ganado"}, 2);
            $array["AmountWinCasino"] = round($value->{"usuario_saldo.saldo_premios_casino"}, 2);
            $array["WithdrawCreates"] = round($value->{"usuario_saldo.saldo_notaret_creadas"}, 2);
            $array["WithdrawPaid"] = round($value->{"usuario_saldo.saldo_notaret_pagadas"}, 2);
            $array["WithdrawPend"] = round($value->{"usuario_saldo.saldo_notaret_pend"}, 2);

            /* redondea y asigna valores de saldo a un array asociativo. */
            $array["AdjustmentE"] = round($value->{"usuario_saldo.saldo_ajustes_entrada"}, 2);
            $array["AdjustmentS"] = round($value->{"usuario_saldo.saldo_ajustes_salida"}, 2);
            $array["Bonus"] = round($value->{"usuario_saldo.saldo_bono"}, 2);
            $array["BonusCasino"] = round($value->{"usuario_saldo.saldo_bono_casino_vivo"}, 2);
            $array["BalanceEnd"] = round($value->{"usuario_saldo.saldo_final"}, 2);
            $array["BalanceEnd"] = round($value->{"usuario_saldo.saldo_creditos_base_final"} + $value->{"usuario_saldo.saldo_creditos_final"}, 2);

            /* Calcula y redondea valores financieros para un usuario en un sistema de apuestas. */
            $array["BonusFreeWin"] = round($value->{"usuario_saldo.saldo_bono_free_ganado"}, 2);
            $array["WithdrawDeletes"] = round($value->{"usuario_saldo.saldo_notaret_eliminadas"}, 2);

            $array["DepositsUsersCount"] = round($value->{".usuarios_recargas"}, 2);
            $array["DepositsCount"] = round($value->{".cantidad_recargas"}, 2);

            $array["BalanceEndCalc"] = round($array["BalanceInitial"] + $array["AmountDeposits"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["Bonus"] + $array["BonusCasino"] - $array["retentionsPrizesSports"] - $array["retentionOnBets"] - $array["retentionOnBetsCasino"] + $array["AwardsJackpotCasino"] + $array["AwardsJackpotSport"] - $array["StakesLotteries"] + $array["AwardsLotteries"], 2);


            /* Calcula el GGR restando apuestas y ganancias, guardando los resultados en un array. */
            $array["GgrAmountWin"] = round(floatval($array["AmountBets"]) - floatval($array["AmountWin"]), 2);
            $array["GgrAmountWinCasino"] = round(floatval($array["AmountBetsCasino"]) - floatval($array["AmountWinCasino"]), 2);

            array_push($final, $array);

        } else {

            /* asigna valores a un arreglo asociado con propiedades de un objeto. */
            $array["Id"] = 0;
            $array["UserId"] = 0;
            $array["Country"] = strtolower($value->{"pais.iso"});

            $array["UserName"] = '';
            $array["CreatedLocalDate"] = $value->{"usuario_saldo.fecha"};


            /* redondea y asigna valores financieros a un array. */
            $array["BalanceInitial"] = round($value->{".saldo_inicial"}, 2);
            $array["AmountDeposits"] = round($value->{".saldo_recarga"}, 2);

            $array["AmountBets"] = round($value->{".saldo_apuestas"}, 2);
            $array["AmountWin"] = round($value->{".saldo_premios"}, 2);

            $array["retentionOnBets"] = round($value->{".saldo_impuestos_apuestas_deportivas"}, 2);

            /* redondea y almacena valores de impuestos y premios en un arreglo. */
            $array["retentionOnBetsCasino"] = round($value->{".saldo_impuestos_apuestas_casino"}, 2);
            $array["retentionsPrizesSports"] = round($value->{".saldo_impuestos_premios_deportivas"}, 2);
            $array["retentionsDeposit"] = round($value->{".saldo_impuestos_depositos"}, 2);
            $array["AwardsJackpotCasino"] = round($value->{".saldo_premios_jackpot_casino"}, 2);
            $array["AwardsJackpotSport"] = round($value->{".saldo_premios_jackpot_deportivas"}, 2);
            $array["StakesLotteries"] = round($value->{".saldo_ventas_loteria"}, 2);

            /* redondea y asigna valores financieros a un arreglo asociativo. */
            $array["AwardsLotteries"] = round($value->{".saldo_rollbacks_loteria"}, 2);
            $array["AmountBetsCasino"] = round($value->{".saldo_apuestas_casino"}, 2);
            $array["BonusFreeCasino"] = round($value->{".saldo_bono_casino_free_ganado"}, 2);
            $array["AmountWinCasino"] = round($value->{".saldo_premios_casino"}, 2);
            $array["WithdrawCreates"] = round($value->{".saldo_notaret_creadas"}, 2);
            $array["WithdrawPaid"] = round($value->{".saldo_notaret_pagadas"}, 2);

            /* redondea y asigna distintos saldos a un array. */
            $array["WithdrawPend"] = round($value->{".saldo_notaret_pend"}, 2);
            $array["AdjustmentE"] = round($value->{".saldo_ajustes_entrada"}, 2);
            $array["AdjustmentS"] = round($value->{".saldo_ajustes_salida"}, 2);
            $array["Bonus"] = round($value->{".saldo_bono"}, 2);
            $array["BonusCasino"] = round($value->{".saldo_bono_casino_vivo"}, 2);
            $array["BalanceEnd"] = round($value->{".saldo_final"}, 2);

            /* Calcula y redondea diversos saldos y ajustes financieros en un array. */
            $array["BalanceEnd"] = round($value->{".saldo_creditos_base_final"} + $value->{".saldo_creditos_final"}, 2);

            $array["BonusFreeWin"] = round($value->{".saldo_bono_free_ganado"}, 2);
            $array["WithdrawDeletes"] = round($value->{".saldo_notaret_eliminadas"}, 2);
            $array["BalanceEndCalc"] = round($array["BalanceInitial"] + $array["AmountDeposits"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["Bonus"] + $array["BonusCasino"] - $array["retentionsPrizesSports"] - $array["retentionOnBets"] - $array["retentionOnBetsCasino"] + $array["AwardsJackpotCasino"] + $array["AwardsJackpotSport"] - $array["StakesLotteries"] + $array["AwardsLotteries"], 2);

            $array["DepositsUsersCount"] = round($value->{".usuarios_recargas"}, 2);

            /* Calcula y almacena valores redondeados de depósitos y ganancias en un array final. */
            $array["DepositsCount"] = round($value->{".cantidad_recargas"}, 2);

            $array["GgrAmountWin"] = round(floatval($array["AmountBets"]) - floatval($array["AmountWin"]), 2); // creo que es esta linea la que debo editar
            $array["GgrAmountWinCasino"] = round(floatval($array["AmountBetsCasino"]) - floatval($array["AmountWinCasino"]), 2);

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


    /* inicializa una respuesta sin errores y define una posición de filas. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["pos"] = $SkeepRows;

    /* asigna un conteo basado en la condición del tipo. */
    if ($Type == 0) {

        $response["total_count"] = $data->count[0]->{".count"};;
    } else {
        $response["total_count"] = oldCount($final);

    }

    /* Asignación del valor de $final a la clave "data" del arreglo $response. */
    $response["data"] = $final;


} else {
    /* estructura una respuesta sin errores, indicando éxito y datos vacíos. */

    $response["HasError"] = false;
    $response["AlertType"] = "success2";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
