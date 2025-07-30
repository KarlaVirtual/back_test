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
 * Obtener el historial de saldo de los usuarios.
 *
 * @param array $params Parámetros de entrada para la consulta del historial de saldo.
 * @param $params ->IsTest boolean Indica si la consulta es de prueba.
 * @param $params ->ProductId int ID del producto.
 * @param $params ->ProviderId int ID del proveedor.
 * @param $params ->Region int ID de la región.
 * @param $params ->MaxRows int Número máximo de filas.
 * @param $params ->OrderedItem int Ítem ordenado.
 * @param $params ->SkeepRows int Filas omitidas.
 * @param $params ->dateTo string Fecha final.
 * @param $params ->BetShopOwn string Propio de la tienda de apuestas.
 * @param $params ->dateFrom string Fecha de inicio.
 *
 *
 * @return array $response Respuesta con los datos del historial de saldo, incluyendo:
 * - HasError: booleano indicando si hubo un error.
 * - AlertType: tipo de alerta.
 * - AlertMessage: mensaje de alerta.
 * - ModelErrors: errores del modelo.
 * - pos: posición de los datos.
 * - total_count: conteo total de registros.
 * - data: datos del historial de saldo.
 */


/* crea un objeto Usuario y procesa datos JSON de una entrada PHP. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);
$IsDetails = $params->IsDetails;
$CurrencyId = $params->CurrencyId;

/* asigna parámetros de entrada a variables para su uso posterior. */
$IsTest = $params->IsTest;
$ProductId = $params->ProductId;
$ProviderId = $params->ProviderId;
$Region = $params->Region;

$MaxRows = $params->MaxRows;

/* asigna parámetros a variables para su posterior uso en procesamiento de datos. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;
$ToDateLocal = $params->dateTo;

$BetShopOwn = $params->BetShopOwn;


$FromDateLocal = $params->dateFrom;


/* verifica perfil y crea un submenu según la sesión del usuario. */
if ($_SESSION["win_perfil"] == 'PUNTOVENTA') {

    $Submenu = new Submenu("", "balanceHistoryForBetShop", '3');


    try {
        $PerfilSubmenu = new PerfilSubmenu($_SESSION["win_perfil"], $Submenu->getSubmenuId());

    } catch (Exception $e) {
        $PerfilSubmenu = new PerfilSubmenu('CUSTOM', $Submenu->getSubmenuId(), $_SESSION["usuario"]);
    }

} else {
    /* Crea un perfil de submenú basado en condiciones de sesión y manejo de excepciones. */


    $Submenu = new Submenu("", "balanceHistoryBetShop", '3');


    try {
        $PerfilSubmenu = new PerfilSubmenu($_SESSION["win_perfil"], $Submenu->getSubmenuId());

    } catch (Exception $e) {
        $PerfilSubmenu = new PerfilSubmenu('CUSTOM', $Submenu->getSubmenuId(), $_SESSION["usuario"]);
    }

}

/* Convierte fechas recibidas en formato de solicitud a formato "Y-m-d" con zona horaria. */
if ($_REQUEST["dateFrom"] != "") {
    //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
    $FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));

}
if ($_REQUEST["dateTo"] != "") {
    //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
    $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));

}


/* captura y valida parámetros de solicitud del usuario. */
$PlayerId = $_REQUEST['PlayerId'];
$UserId = $_REQUEST['UserId'];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';

$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';
$ProductId = $_REQUEST["ProductId"];

/* recoge parámetros de solicitud para manejar detalles de apuestas. */
$IsDetails = $_REQUEST["IsDetails"];
$Type = $_REQUEST["Type"];
$BetShopOwn = $_REQUEST["BetShopOwn"];

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* Código que verifica condiciones y ajusta valores de variables según su estado. */
$seguir = true;

if ($SkeepRows == "") {
    $seguir = false;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* verifica si $MaxRows está vacío y establece $seguir como falso. */
if ($MaxRows == "") {
    $seguir = false;
}

if ($seguir) {


    /* Verifica el perfil de usuario y asigna un tipo específico si coincide. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3" || $_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO") {
        $Type = 0;
    }

    if ($seguir && $Type == 1) {


        /* alterna el valor de la variable $IsDetails entre verdadero y falso. */
        if ($IsDetails == 1) {
            $IsDetails = false;

        } else {
            $IsDetails = true;
        }


        /* Genera reglas para filtrar fechas en función de condiciones especificadas. */
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


        /* Agrega reglas de filtro basadas en la región y país seleccionados. */
        if ($Region != "") {
            array_push($rules, array("field" => "bodega_usuario_saldo.pais_id", "data" => "$Region", "op" => "eq"));
        }


        if ($CountrySelect != "" && $CountrySelect != "0") {
            array_push($rules, array("field" => "bodega_usuario_saldo.pais_id", "data" => "$CountrySelect", "op" => "eq"));
        }

        // Si el usuario esta condicionado por País

        /* Agrega reglas basadas en condiciones de sesión para filtrar datos. */
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "bodega_usuario_saldo.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        } else {
            if ($_SESSION["PaisCondS"] != '') {
                array_push($rules, array("field" => "bodega_usuario_saldo.pais_id", "data" => $_SESSION['PaisCondS'], "op" => "eq"));
            }
        }


        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "bodega_usuario_saldo.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {
            /* Agrega una regla si "mandanteLista" no está vacío ni es "-1". */


            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "bodega_usuario_saldo.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }


        /* Verifica la región del perfil de usuario y agrega reglas si no es concesionario. */
        if ($_SESSION['regionperfil'] != "0" && $_SESSION['regionperfil'] != null) {
            if ($_SESSION["win_perfil"] != "CONCESIONARIO" && $_SESSION["win_perfil"] != "CONCESIONARIO2" && $_SESSION["win_perfil"] != "CONCESIONARIO3" && $_SESSION["win_perfil"] != "PUNTOVENTA") {

                array_push($rules, array("field" => "usuario_perfil.region", "data" => $_SESSION['regionperfil'], "op" => "eq"));
            }
        }
        // Inactivamos reportes para el país Colombia
        //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        /* Agrega reglas de filtrado y ordenación para una consulta en JSON. */
        array_push($rules, array("field" => "bodega_usuario_saldo.tipo", "data" => "2,3", "op" => "in"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $order = " bodega_usuario_saldo.fecha ";

        /* establece el tipo de orden según la solicitud recibida. */
        $orderType = "desc";


        if ($_REQUEST["Dir"] == "0") {
            $orderType = "asc";

        }


        /* Se inicializa la variable $grouping como una cadena vacía. */
        $grouping = "";


        $select = "
                    pais.iso,
                    bodega_usuario_saldo.fecha,
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
       SUM(saldo_impuestos_depositos) saldo_impuestos_depositos,
       SUM(saldo_impuestos_apuestas_deportivas) saldo_impuestos_apuestas_deportivas,
       SUM(saldo_impuestos_apuestas_casino) saldo_impuestos_apuestas_casino,
       SUM(saldo_apuestas_casino) saldo_apuestas_casino,
       SUM(saldo_premios_casino) saldo_premios_casino,
       SUM(saldo_bono) saldo_bono,
       SUM(saldo_bono_free_ganado) saldo_bono_free_ganado,
       SUM(saldo_ajustes_entrada) saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida) saldo_ajustes_salida,
       
       SUM(saldo_final) saldo_final,
       
       SUM(saldo_creditos_base_final) saldo_creditos_base_final,
              (saldo_inicial) - (saldo_recarga) + saldo_notaret_pagadas - saldo_apuestas + saldo_premios - saldo_apuestas_casino + saldo_premios_casino - saldo_notaret_creadas + saldo_notaret_eliminadas + saldo_ajustes_entrada - saldo_ajustes_salida + saldo_notaret_pend - saldo_creditos_final - saldo_creditos_base_final desfase,

       SUM(saldo_creditos_base_final) saldo_creditos_base_final,
       SUM(saldo_creditos_final) saldo_creditos_final";
//,
//       SUM(usuario_recargas.usuarios_recargas)      usuarios_recargas,
//       SUM(usuario_recargas.cantidad_recargas)      cantidad_recargas

        /* determina el orden de precios basado en solicitudes de clasificación. */
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


        /* Se crea un objeto y se obtienen saldos de usuario en formato JSON. */
        $UsuarioSaldo = new UsuarioSaldo();
        $data = $UsuarioSaldo->getUsuarioSaldosCustomBodega($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping, '', $conRecargas);
        $data = json_decode($data);


        $final = [];


        /* Variables inicializan contadores para apuestas, premios y un conteo general. */
        $papuestas = 0;
        $ppremios = 0;
        $pcont = 0;

        foreach ($data->data as $key => $value) {


            /* asigna valores a un array, manipulando propiedades y redondeando datos. */
            $array["Id"] = 0;
            $array["UserId"] = 0;
            $array["Country"] = strtolower($value->{"pais.iso"});

            $array["InitialRechargeQuota"] = round($value->{".saldo_creditos_inicial"}, 2);
            $array["InitialGameQuota"] = round($value->{".saldo_creditos_base_inicial"}, 2);

            /* asigna valores redondeados a un array y obtiene información de un objeto. */
            $array["EndGameQuota"] = round($value->{".saldo_creditos_base_final"}, 2);
            $array["EndRechargeQuota"] = round($value->{".saldo_creditos_final"}, 2);


            $array["UserName"] = '';
            $array["CreatedLocalDate"] = $value->{"bodega_usuario_saldo.fecha"};


            /* redondea y asigna valores financieros a un array. */
            $array["BalanceInitial"] = round($value->{".saldo_inicial"}, 2);
            $array["AmountDeposits"] = round($value->{".saldo_recarga"}, 2);

            $array["AmountBets"] = round($value->{".saldo_apuestas"}, 2);
            $array["AmountWin"] = round($value->{".saldo_premios"}, 2);
            $array["retentionOnBets"] = round($value->{".saldo_impuestos_apuestas_deportivas"}, 2);

            /* redondea y almacena valores financieros en un array asociativo. */
            $array["retentionOnBetsCasino"] = round($value->{".saldo_impuestos_apuestas_casino"}, 2);
            $array["retentionsPrizesSports"] = round($value->{".saldo_impuestos_premios_deportivas"}, 2);
            $array["retentionsDeposit"] = round($value->{".saldo_impuestos_depositos"}, 2);
            $array["AmountBetsCasino"] = round($value->{".saldo_apuestas_casino"}, 2);
            $array["AmountWinCasino"] = round($value->{".saldo_premios_casino"}, 2);
            $array["WithdrawCreates"] = round($value->{".saldo_notaret_creadas"}, 2);

            /* Se asignan saldos redondeados a un array a partir de un objeto. */
            $array["WithdrawPaid"] = round($value->{".saldo_notaret_pagadas"}, 2);
            $array["WithdrawPend"] = round($value->{".saldo_notaret_pend"}, 2);
            $array["AdjustmentE"] = round($value->{".saldo_ajustes_entrada"}, 2);
            $array["AdjustmentS"] = round($value->{".saldo_ajustes_salida"}, 2);
            $array["Bonus"] = round($value->{".saldo_bono"}, 2);
            $array["BonusCasino"] = round($value->{".saldo_bono_casino_vivo"}, 2);

            /* Calcula y redondea saldos y ajustes financieros para un balance final. */
            $array["BalanceEnd"] = round($value->{".saldo_final"}, 2);
            $array["BalanceEnd"] = round($value->{".saldo_creditos_base_final"} + $value->{".saldo_creditos_final"}, 2);

            $array["BonusFreeWin"] = round($value->{".saldo_bono_free_ganado"}, 2);
            $array["WithdrawDeletes"] = -round($value->{".saldo_notaret_eliminadas"}, 2);
            $array["BalanceEndCalc"] = round($array["BalanceInitial"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["AmountBets"] - $array["retentionOnBets"] - $array["retentionOnBetsCasino"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["WithdrawPend"], 2);


            /* Calcula cuotas de recarga y juego, ajustando montos y ganancias, y almacena resultados. */
            $array["EndRechargeQuotaCalc"] = round($array["InitialRechargeQuota"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"], 2);

            $array["EndGameQuotaCalc"] = $array["EndRechargeQuotaCalc"] + round($array["InitialGameQuota"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"], 2);

            $array["GgrAmountWin"] = round(floatval($array["AmountBets"]) - floatval($array["AmountWin"]) - floatval($array["Bonus"]), 2);

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


        /* Configura una respuesta con éxito y sin errores, incluyendo posiciones omitidas. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["pos"] = $SkeepRows;

        /* asigna un valor a "total_count" según el tipo de datos. */
        if ($Type == 0) {

            $response["total_count"] = $data->count[0]->{".count"};;
        } else {
            $response["total_count"] = oldCount($final);

        }

        /* Asigna el valor de $final a la clave "data" del array $response. */
        $response["data"] = $final;


    } else {


        /* alterna el valor de $IsDetails entre verdadero y falso. */
        if ($IsDetails == 1) {
            $IsDetails = false;

        } else {
            $IsDetails = true;
        }


        /* genera reglas para comparar fechas en un arreglo. */
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


        /* Se agregan reglas al arreglo basado en condiciones de región y ID de jugador. */
        if ($Region != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($PlayerId != "") {
            array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => "$PlayerId", "op" => "eq"));

        }


        /* Añade reglas a un array basadas en condiciones de usuario y propiedad de apuesta. */
        if ($UserId != "") {
            array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => "$UserId", "op" => "eq"));

        }

        if ($BetShopOwn == 'S') {
            array_push($rules, array("field" => "punto_venta.propio", "data" => "S", "op" => "eq"));
        }


        /* Añade reglas a un array según condiciones de propiedad y país seleccionados. */
        if ($BetShopOwn == 'N') {
            array_push($rules, array("field" => "punto_venta.propio", "data" => "N", "op" => "eq"));

        }


        if ($CountrySelect != "" && $CountrySelect != "0") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
        }

        // Si el usuario esta condicionado por País

        /* agrega reglas a un array dependiendo de condiciones de sesión del usuario. */
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {
            /* Agrega una regla si "mandanteLista" no está vacía ni es "-1". */


            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }


        /* valida el perfil del usuario y agrega reglas a un arreglo. */
        if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));

        }

        if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));

        }


        /* Verifica el perfil del usuario y agrega reglas correspondientes a un array. */
        if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
            array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));

        }


        if ($_SESSION["win_perfil2"] == "CAJERO") {
            array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));

        }


        /* modifica reglas de acceso según el perfil del usuario en sesión. */
        if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
            array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));

        }

        // Inactivamos reportes para el país Colombia
        //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'PUNTOVENTA','CAJERO'", "op" => "in"));


        /* Se crea un filtro en formato JSON con reglas y operador lógico "AND". */
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
       SUM(saldo_impuestos_depositos) saldo_impuestos_depositos,
       SUM(saldo_impuestos_apuestas_casino) saldo_impuestos_apuestas_casino,
       SUM(saldo_impuestos_apuestas_deportivas) saldo_impuestos_apuestas_deportivas,
       SUM(saldo_apuestas_casino) saldo_apuestas_casino,
       SUM(saldo_premios_casino) saldo_premios_casino,
       SUM(saldo_bono) saldo_bono,
       SUM(saldo_bono_free_ganado) saldo_bono_free_ganado,
       SUM(saldo_ajustes_entrada) saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida) saldo_ajustes_salida,
       
       SUM(saldo_final) saldo_final,
       
       SUM(saldo_creditos_base_final) saldo_creditos_base_final,
       (saldo_final) saldo_final,
              (saldo_inicial) - (saldo_recarga) + saldo_notaret_pagadas - saldo_apuestas + saldo_premios - saldo_apuestas_casino + saldo_premios_casino - saldo_notaret_creadas + saldo_notaret_eliminadas + saldo_ajustes_entrada - saldo_ajustes_salida + saldo_notaret_pend - usuario_saldo.saldo_creditos_final-usuario_saldo.saldo_creditos_base_final desfase,
       SUM(saldo_creditos_final) saldo_creditos_final,usuario.nombre,pais.iso,usuario_punto_venta.nombre,usuario_punto_venta.usuario_id,usuario_saldo.fecha ";


        /* Define criterios de agrupamiento y ordenamiento para datos de usuario y saldo. */
        $grouping = "usuario_saldo.fecha,usuario.puntoventa_id";


        $order = "usuario_saldo.fecha";
        $orderType = "asc";

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
       SUM(saldo_impuestos_depositos) saldo_impuestos_depositos,
       SUM(saldo_impuestos_apuestas_casino) saldo_impuestos_apuestas_casino,
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


            /* Agrupa datos por país y fecha en la consulta del saldo del usuario. */
            $grouping = "usuario_punto_venta.pais_id,usuario_saldo.fecha";
        } else {
            /* establece un orden descendente para una variable llamada "desfase". */

            $order = " desfase ";
            $orderType = "desc";

        }


        /* Ordena resultados según montos de premios o depósitos y tipo de orden solicitado. */
        if ($_REQUEST["sort[AmountWin]"] != "") {
            $order = "saldo_premios";
            $orderType = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

        }


        if ($_REQUEST["sort[AmountDeposits]"] != "") {
            $order = "saldo_recarga";
            $orderType = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";
        }


        /* Condicionales que modifican una consulta SQL según la fecha indicada. */
        if ($ToDateLocal <= '2024-10-28 00:00:00') {

            $select = str_replace('SUM(saldo_impuestos_premios_deportivas) saldo_impuestos_premios_deportivas,', '', $select);
            $select = str_replace('SUM(saldo_impuestos_apuestas_deportivas) saldo_impuestos_apuestas_deportivas,', '', $select);
        }

        if ($ToDateLocal <= '2025-01-08 00:00:00') {

            $select = str_replace('SUM(saldo_impuestos_depositos) saldo_impuestos_depositos,', '', $select);
        }

        /* modifica una consulta SQL si la fecha es anterior al 8 de enero de 2025. */
        if ($ToDateLocal <= '2025-01-08 00:00:00') {

            $select = str_replace('SUM(saldo_impuestos_apuestas_casino) saldo_impuestos_apuestas_casino,', '', $select);
        }

        $UsuarioSaldo = new UsuarioSaldo();

        /* Se obtiene y procesa el saldo de usuario de manera personalizada y estructurada. */
        $data = $UsuarioSaldo->getUsuarioSaldosCustom($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping, '1', false, true, $FromDateLocal, $ToDateLocal);
        $data = json_decode($data);


        $final = [];

        $papuestas = 0;

        /* Inicializa dos variables, $ppremios y $pcont, en cero. */
        $ppremios = 0;
        $pcont = 0;

        foreach ($data->data as $key => $value) {

            if ($Type == 0) {


                /* Asigna valores de un objeto a un array con claves específicas y formatos. */
                $array["Id"] = $value->{"usuario_saldo.ususaldo_id"};
                $array["UserId"] = $value->{"usuario_punto_venta.usuario_id"};

                $array["Country"] = strtolower($value->{"pais.iso"});
                $array["UserName"] = $value->{"usuario_punto_venta.nombre"};
                $array["CreatedLocalDate"] = $value->{"usuario_saldo.fecha"};


                /* redondea y almacena diferentes saldos de usuario en un array. */
                $array["InitialRechargeQuota"] = round($value->{"usuario_saldo.saldo_creditos_inicial"}, 2);
                $array["InitialGameQuota"] = round($value->{"usuario_saldo.saldo_creditos_base_inicial"}, 2);

                $array["EndGameQuota"] = round($value->{"usuario_saldo.saldo_creditos_base_final"}, 2);
                $array["EndRechargeQuota"] = round($value->{"usuario_saldo.saldo_creditos_final"}, 2);


                $array["BalanceInitial"] = round($value->{"usuario_saldo.saldo_inicial"}, 2);

                /* redondea y asigna distintos saldos a un arreglo. */
                $array["AmountDeposits"] = round($value->{"usuario_saldo.saldo_recarga"}, 2);
                $array["AmountBets"] = round($value->{"usuario_saldo.saldo_apuestas"}, 2);
                $array["AmountWin"] = round($value->{"usuario_saldo.saldo_premios"}, 2);
                $array["retentionOnBets"] = round($value->{".saldo_impuestos_apuestas_deportivas"}, 2);
                $array["retentionOnBetsCasino"] = round($value->{".saldo_impuestos_apuestas_casino"}, 2);
                $array["retentionsPrizesSports"] = round($value->{".saldo_impuestos_premios_deportivas"}, 2);

                /* Asignación de valores redondeados a un array desde un objeto de usuario. */
                $array["retentionsDeposit"] = round($value->{".saldo_impuestos_depositos"}, 2);
                $array["AmountBetsCasino"] = round($value->{"usuario_saldo.saldo_apuestas_casino"}, 2);
                $array["AmountWinCasino"] = round($value->{"usuario_saldo.saldo_premios_casino"}, 2);
                $array["WithdrawCreates"] = round($value->{"usuario_saldo.saldo_notaret_creadas"}, 2);
                $array["WithdrawPaid"] = round($value->{"usuario_saldo.saldo_notaret_pagadas"}, 2);
                $array["WithdrawPend"] = round($value->{"usuario_saldo.saldo_notaret_pend"}, 2);

                /* redondea y asigna valores de saldo a un array asociativo. */
                $array["AdjustmentE"] = round($value->{"usuario_saldo.saldo_ajustes_entrada"}, 2);
                $array["AdjustmentS"] = round($value->{"usuario_saldo.saldo_ajustes_salida"}, 2);
                $array["Bonus"] = round($value->{"usuario_saldo.saldo_bono"}, 2);
                $array["BalanceEnd"] = round($value->{"usuario_saldo.saldo_final"}, 2);
                $array["BalanceEnd"] = round($value->{"usuario_saldo.saldo_creditos_base_final"} + $value->{"usuario_saldo.saldo_creditos_final"}, 2);
                $array["BonusFreeWin"] = round($value->{"usuario_saldo.saldo_bono_free_ganado"}, 2);

                /* Calcula y redondea varios saldos y ajustes financieros en un arreglo. */
                $array["WithdrawDeletes"] = round($value->{"usuario_saldo.saldo_notaret_eliminadas"}, 2);

                $array["BalanceEndCalc"] = round($array["BalanceInitial"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["AmountBets"] - $array["retentionOnBets"] - $array["retentionOnBetsCasino"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["WithdrawPend"], 2);

                $array["InitialRechargeQuota"] = round($value->{".saldo_creditos_inicial"}, 2);
                $array["InitialGameQuota"] = round($value->{".saldo_creditos_base_inicial"}, 2);

                /* redondea y asigna valores de saldos a un array. */
                $array["EndGameQuota"] = round($value->{".saldo_creditos_base_final"}, 2);
                $array["EndRechargeQuota"] = round($value->{".saldo_creditos_final"}, 2);

                $array["BalanceInitial"] = round($value->{".saldo_inicial"}, 2);
                $array["AmountDeposits"] = round($value->{".saldo_recarga"}, 2);

                $array["AmountBets"] = round($value->{".saldo_apuestas"}, 2);

                /* Asigna valores redondeados a las claves de un array relacionadas con saldos. */
                $array["AmountWin"] = round($value->{".saldo_premios"}, 2);
                $array["AmountBetsCasino"] = round($value->{".saldo_apuestas_casino"}, 2);
                $array["AmountWinCasino"] = round($value->{".saldo_premios_casino"}, 2);
                $array["WithdrawCreates"] = round($value->{".saldo_notaret_creadas"}, 2);
                $array["WithdrawPaid"] = round($value->{".saldo_notaret_pagadas"}, 2);
                $array["WithdrawPend"] = round($value->{".saldo_notaret_pend"}, 2);

                /* redondea y almacena valores financieros en un array. */
                $array["AdjustmentE"] = round($value->{".saldo_ajustes_entrada"}, 2);
                $array["AdjustmentS"] = round($value->{".saldo_ajustes_salida"}, 2);
                $array["Bonus"] = round($value->{".saldo_bono"}, 2);
                $array["BalanceEnd"] = round($value->{".saldo_final"}, 2);
                $array["BalanceEnd"] = round($value->{".saldo_creditos_base_final"} + $value->{".saldo_creditos_final"}, 2);

                $array["BonusFreeWin"] = round($value->{".saldo_bono_free_ganado"}, 2);

                /* Calcula saldos y cuotas finales a partir de transacciones financieras. */
                $array["WithdrawDeletes"] = -round($value->{".saldo_notaret_eliminadas"}, 2);
                $array["BalanceEndCalc"] = round($array["BalanceInitial"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["AmountBets"] - $array["retentionOnBets"] - $array["retentionOnBetsCasino"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["WithdrawPend"], 2);
                $array["EndRechargeQuotaCalc"] = round($array["InitialRechargeQuota"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"], 2);
                $array["EndGameQuotaCalc"] = round($array["InitialGameQuota"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"], 2);


                array_push($final, $array);

            } else {

                /* Código para inicializar un array con datos de usuario y atributos relacionados. */
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


                /* redondea y asigna valores de impuestos y apuestas a un array. */
                $array["retentionOnBets"] = round($value->{".saldo_impuestos_apuestas_deportivas"}, 2);
                $array["retentionOnBetsCasino"] = round($value->{".saldo_impuestos_apuestas_casino"}, 2);
                $array["retentionsPrizesSports"] = round($value->{".saldo_impuestos_premios_deportivas"}, 2);
                $array["retentionsDeposit"] = round($value->{".saldo_impuestos_depositos"}, 2);

                $array["AmountBets"] = round($value->{".saldo_apuestas"}, 2);

                /* redondea y asigna valores de saldo a un array. */
                $array["AmountWin"] = round($value->{".saldo_premios"}, 2);
                $array["AmountBetsCasino"] = round($value->{".saldo_apuestas_casino"}, 2);
                $array["AmountWinCasino"] = round($value->{".saldo_premios_casino"}, 2);
                $array["WithdrawCreates"] = round($value->{".saldo_notaret_creadas"}, 2);
                $array["WithdrawPaid"] = round($value->{".saldo_notaret_pagadas"}, 2);
                $array["WithdrawPend"] = round($value->{".saldo_notaret_pend"}, 2);

                /* redondea y almacena valores financieros en un array asociativo. */
                $array["AdjustmentE"] = round($value->{".saldo_ajustes_entrada"}, 2);
                $array["AdjustmentS"] = round($value->{".saldo_ajustes_salida"}, 2);
                $array["Bonus"] = round($value->{".saldo_bono"}, 2);
                $array["BalanceEnd"] = round($value->{".saldo_final"}, 2);
                $array["BalanceEnd"] = round($value->{".saldo_creditos_base_final"} + $value->{".saldo_creditos_final"}, 2);

                $array["BonusFreeWin"] = round($value->{".saldo_bono_free_ganado"}, 2);

                /* Calcula saldos y cuotas finales ajustados a partir de diversas transacciones financieras. */
                $array["WithdrawDeletes"] = -round($value->{".saldo_notaret_eliminadas"}, 2);
                $array["BalanceEndCalc"] = round($array["BalanceInitial"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["AmountBets"] - $array["retentionOnBets"] - $array["retentionOnBetsCasino"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["WithdrawPend"], 2);
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


        /* inicializa un arreglo de respuesta para una operación exitosa. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["pos"] = $SkeepRows;

        /* asigna un conteo total basado en el tipo de variable `$Type`. */
        if ($Type == 0) {

            $response["total_count"] = $data->count[0]->{".count"};
        } else {
            $response["total_count"] = oldCount($final);

        }

        /* Asigna el valor de $final al índice "data" del array $response. */
        $response["data"] = $final;
    }

} else {
    /* maneja una respuesta sin errores, inicializando propiedades relevantes. */

    $response["HasError"] = false;
    $response["AlertType"] = "success2";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
