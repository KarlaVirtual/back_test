<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\BodegaFlujoCaja;
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
 * get_flow_cash
 *
 * Obtener el flujo de caja
 *
 * @param no
 *
 * @param int $MaxRows : Número máximo de filas a devolver.
 * @param int $OrderedItem : Ítem ordenado.
 * @param int $SkeepRows : Número de filas a omitir en la con
 * @param string $ToDateLocal : Fecha de corte
 * @param string $FromDateLocal : Fecha de inicio
 * @param bool $IsDetails : Si devuelve los detalles o no
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *code* (int): codigo de error
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos del flujo de caja.
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/*Declaración e instancia de múltiples objetos*/
$PuntoVenta = new PuntoVenta();


$ConfigurationEnvironment = new ConfigurationEnvironment();

$UsuarioMandante = new UsuarioMandante($json->session->usuario);
//$UsuarioMandante = new UsuarioMandante(1);
$Mandante = new Mandante($UsuarioMandante->getMandante());

$UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

//Actualización de parámetros globales
$_SESSION["win_perfil2"] = $UsuarioPerfil->perfilId;
$_SESSION['usuario'] = $UsuarioMandante->usuarioMandante;

$PuntoVenta = new PuntoVenta();

$params = $json->params;


/*Manipulación parámetros para filtrado*/
$ToDateLocal = $params->endDate;

if ($ToDateLocal != "" && $ToDateLocal != "null") {
    $ToDateLocal = date("Y-m-d", $ToDateLocal);

}

$FromDateLocal = $params->startDate;

if ($FromDateLocal != "" && $FromDateLocal != "null") {

    $FromDateLocal = date("Y-m-d", $FromDateLocal);
}

$MaxRows = strtolower($params->count);
$SkeepRows = strtolower($params->start);

$OrderedItem = $params->OrderedItem;


$IsDetails = ($params->IsDetails == true) ? true : false;


$seguir = true;

if ($SkeepRows == "" || $MaxRows == "") {
    $seguir = false;

}

$final = [];
$totalm = 0;

/*Verificación caso de consulta en tiempo real*/
if ($seguir && $ToDateLocal == date("Y-m-d", time())) {

    $FromDateLocal = date("Y-m-d");
    $ToDateLocal = date("Y-m-d");

    $rules = [];

    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


    /*Definición columnas para la implementación de la consulta */
    $grouping = "";
    $select = "";
    if ($IsDetails) {

    } else {
        if ($TypeTotal == 0) {
            $grouping = 0;

        } else {
            $grouping = 1;

        }

        $select = "SUM(usuario_recarga.valor) valoru,SUM(transaccion_producto.valor) valor, ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));

    }


    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 1000000;
    }


    if ($_REQUEST["UserId"] != "") {
        $BetShopId = $_REQUEST["UserId"];

    }

    /*Solicitud personalizada y dinámica por perfil del usuario*/
    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        $transacciones = $PuntoVenta->getFlujoCajaResumidoConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CAJERO") {

        $transacciones = $PuntoVenta->getFlujoCajaResumidoConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO") {


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario'], "", "", "", "", $BetShopId);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario'], "", "", "", $BetShopId);

    }


    $transacciones = json_decode($transacciones);

    /*Itereación del resultado obtenido*/
    foreach ($transacciones->data as $key => $value) {
        /*Almacenamiento y formato objetos de respuesta*/
        $array = [];
        $array["Punto"] = "PUNTO";

        if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
            $array["Punto"] = $value->{"y.login"};

        } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
            $array["Punto"] = $value->{"y.login"};

        } else {
            $array["Punto"] = $value->{"y.punto_venta"};

        }

        $array["UserId"] = $value->{"y.usuario_id"};

        $array["date"] = $value->{"y.fecha_crea"};
        $array["currency"] = $value->{"y.moneda"};
        $array["CountryId"] = $value->{"y.pais_nom"} . ' - ' . $value->{"y.mandante"};
        $array["Partner"] = $value->{"y.mandante"};
        $array["CountryIcon"] = strtolower($value->{"y.pais_iso"});
        $array["Agent"] = $value->{"uu.agente"} . ' - ' . $array["Moneda"];

        $array["amountTickets"] = $value->{".cant_tickets"};
        $array["valueInputCash"] = $value->{".valor_entrada_efectivo"};
        $array["valueEntriesBonustc"] = $value->{".valor_entrada_bono"};
        $array["valueInputsRecharge"] = $value->{".valor_entrada_recarga"};

        $array["valueInputsTransfers"] = $value->{".valor_entrada_traslado"};
        $array["valueOutputsCash"] = $value->{".valor_salida_efectivo"};
        $array["valueOutputsTransfers"] = $value->{".valor_salida_traslado"};
        $array["valueOutputsNotesRetirement"] = $value->{".valor_salida_notaret"};
        $array["balance"] = $array["valueInputCash"] + $array["valueEntriesBonustc"] + $array["valueInputsRecharge"] + $array["valueInputsTransfers"] - $array["valueOutputsCash"] - $array["valueOutputsTransfers"] - $array["valueOutputsNotesRetirement"];
        $array["MMoneda"] = $value->{"y.punto_venta"};
        $array["taxes"] = $value->{".impuestos"};


        $array["VoidedPlacedBets"] = $value->{".apuestas_void"};
        $array["VoidedPaidBets"] = $value->{".premios_void"};

        $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPlacedBets"];
        $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

        /*Redimensión cálculo entradas efectivo para partners específicos*/
        if ($array["Partner"] == 1 || $array["Partner"] == 2) {
            $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPaidBets"];
            $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - 0;
            $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

        }

        if ($TypeTotal == 1) {

            $array["Punto"] = $value->{"y.punto_venta"} . $array["Agent"];

        }
        if ($array["UserId"] != '') {
            array_push($final, $array);

        }

    }
}


try {

    if ($seguir) {

        /*Asignación de filtrado*/
        $ToDateLocal = $params->endDate;

        if ($ToDateLocal != "" && $ToDateLocal != "null") {
            $ToDateLocal = date("Y-m-d", $ToDateLocal);

        }

        $FromDateLocal = $params->startDate;

        if ($FromDateLocal != "" && $FromDateLocal != "null") {

            $FromDateLocal = date("Y-m-d", $FromDateLocal);
        }


        $FromDateLocal2 = date("Y-m-d", strtotime(time() . $timezone . ' hour '));
        $ToDateLocal2 = date("Y-m-d", strtotime(time() . '' . $timezone . ' hour '));

        if ($FromDateLocal != "") {

            $FromDateLocal2 = $FromDateLocal;
        }
        if ($ToDateLocal != "") {
            $ToDateLocal2 = $ToDateLocal;
        }


        $rules = [];

        array_push($rules, array("field" => "bodega_flujo_caja.fecha", "data" => "$FromDateLocal2", "op" => "ge"));
        array_push($rules, array("field" => "bodega_flujo_caja.fecha", "data" => "$ToDateLocal2", "op" => "le"));


        $grouping = "";
        $select = "";
        if ($TypeTotal == '0') {
            /*Definición solicitud para acumulado por totales respecto a usuario y fecha*/
            $select = 'bodega_flujo_caja.usuario_id,
       bodega_flujo_caja.fecha,
       bodega_flujo_caja.pais_id,
       bodega_flujo_caja.mandante,
       bodega_flujo_caja.concesionario_id,
       SUM(bodega_flujo_caja.cant_tickets) cant_tickets,
       SUM(bodega_flujo_caja.valor_entrada_efectivo) valor_entrada_efectivo,
       SUM(bodega_flujo_caja.valor_entrada_bono) valor_entrada_bono,
       SUM(bodega_flujo_caja.valor_entrada_recarga) valor_entrada_recarga,
       SUM(bodega_flujo_caja.valor_entrada_traslado) valor_entrada_traslado,
       SUM(bodega_flujo_caja.valor_salida_traslado) valor_salida_traslado,
       SUM(bodega_flujo_caja.valor_salida_notaret) valor_salida_notaret,
       SUM(bodega_flujo_caja.valor_entrada) valor_entrada,
       SUM(bodega_flujo_caja.valor_salida) valor_salida,
       SUM(bodega_flujo_caja.valor_salida_efectivo) valor_salida_efectivo,
       SUM(bodega_flujo_caja.premios_pend) premios_pend,
       SUM(bodega_flujo_caja.impuestos) impuestos,
       SUM(bodega_flujo_caja.apuestas_void) apuestas_void,
       SUM(bodega_flujo_caja.premios_void) premios_void,
       
       usuario.usuario_id,
       usuario.login,
       usuario.moneda,
       punto_venta.descripcion,
       
       pais.*,
       
       agente.nombre,
       agente2.nombre,agente.usuario_id,agente2.usuario_id';

            $grouping = 'bodega_flujo_caja.usuario_id,bodega_flujo_caja.fecha';
        } else {
            /*Definición solicitud para acumulado por totales respecto a concesionario y fecha*/
            $select = 'bodega_flujo_caja.usuario_id,
       bodega_flujo_caja.fecha,
       bodega_flujo_caja.pais_id,
       bodega_flujo_caja.mandante,
       bodega_flujo_caja.concesionario_id,
       SUM(bodega_flujo_caja.cant_tickets) cant_tickets,
       SUM(bodega_flujo_caja.valor_entrada_efectivo) valor_entrada_efectivo,
       SUM(bodega_flujo_caja.valor_entrada_bono) valor_entrada_bono,
       SUM(bodega_flujo_caja.valor_entrada_recarga) valor_entrada_recarga,
       SUM(bodega_flujo_caja.valor_entrada_traslado) valor_entrada_traslado,
       SUM(bodega_flujo_caja.valor_salida_traslado) valor_salida_traslado,
       SUM(bodega_flujo_caja.valor_salida_notaret) valor_salida_notaret,
       SUM(bodega_flujo_caja.valor_entrada) valor_entrada,
       SUM(bodega_flujo_caja.valor_salida) valor_salida,
       SUM(bodega_flujo_caja.valor_salida_efectivo) valor_salida_efectivo,
       SUM(bodega_flujo_caja.premios_pend) premios_pend,
       SUM(bodega_flujo_caja.impuestos) impuestos,
       SUM(bodega_flujo_caja.apuestas_void) apuestas_void,
       SUM(bodega_flujo_caja.premios_void) premios_void,
       
       usuario.usuario_id,
       usuario.login,
       usuario.moneda,
       punto_venta.descripcion,
       
       pais.*,
       
       agente.nombre,
       agente2.nombre,agente.usuario_id,agente2.usuario_id
       
       ';

            $grouping = 'bodega_flujo_caja.mandante,concesionario.usupadre_id,bodega_flujo_caja.fecha';

        }


        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 1000000;
        }

        //Definición alcance de la consulta por perfil de usuario
        if ($_SESSION["win_perfil2"] == "CAJERO") {

            array_push($rules, array("field" => "bodega_flujo_caja.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "CAJERO") {

            array_push($rules, array("field" => "bodega_flujo_caja.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO") {


            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {


            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 5;
        }

        $json = json_encode($filtro);

        /*Obtención consulta dinámica*/
        $BodegaFlujoCaja = new BodegaFlujoCaja();
        $transacciones = $BodegaFlujoCaja->getBodegaFlujoCajaCustom($select, "punto_venta.descripcion asc,bodega_flujo_caja.fecha", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);


        $transacciones = json_decode($transacciones);

        //$final = [];
        $totalm = 0;
        foreach ($transacciones->data as $key => $value) {
            /*Generación de respuesta respecto a filtros dinámicos definidos*/
            $array = [];
            $array["Punto"] = "PUNTO";

            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $array["Punto"] = $value->{"usuario.login"};

            } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
                $array["Punto"] = $value->{"usuario.login"};

            } else {
                if ($TypeTotal == '0') {

                    if ($value->{"bodega_flujo_caja.mandante"} == '2') {
                        $array["Punto"] = $value->{"punto_venta.descripcion"};


                    } else {
                        $array["Punto"] = $value->{"punto_venta.descripcion"} . ' - ' . $value->{"usuario.usuario_id"};

                    }


                } else {

                    $array["Punto"] = 'Punto Venta ' . $value->{"usuario.moneda"} . $value->{"agente.usuario_id"};

                }
            }
            /*Llenado objeto de respuesta*/
            $array["UserId"] = $value->{"usuario.usuario_id"};

            $array["date"] = $value->{"bodega_flujo_caja.fecha"};
            $array["currency"] = $value->{"usuario.moneda"};
            $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"bodega_flujo_caja.mandante"};
            $array["Partner"] = $value->{"bodega_flujo_caja.mandante"};
            $array["CountryIcon"] = strtolower($value->{"pais.iso"});
            $array["Agent"] = $value->{"agente.nombre"} . ' - ' . $array["Moneda"];
            $array["amountTickets"] = $value->{".cant_tickets"};
            $array["valueInputCash"] = $value->{".valor_entrada_efectivo"};
            $array["valueEntriesBonustc"] = $value->{".valor_entrada_bono"};
            $array["valueInputsRecharge"] = $value->{".valor_entrada_recarga"};

            $array["valueInputsTransfers"] = $value->{".valor_entrada_traslado"};
            $array["valueOutputsCash"] = $value->{".valor_salida_efectivo"};
            $array["valueOutputsTransfers"] = $value->{".valor_salida_traslado"};
            $array["valueOutputsNotesRetirement"] = $value->{".valor_salida_notaret"};
            $array["balance"] = $array["valueInputCash"] + $array["valueEntriesBonustc"] + $array["valueInputsRecharge"] + $array["valueInputsTransfers"] - $array["valueOutputsCash"] - $array["valueOutputsTransfers"] - $array["valueOutputsNotesRetirement"];
            $array["MMoneda"] = $value->{"usuario.moneda"};
            $array["taxes"] = $value->{".impuestos"};


            $array["VoidedPlacedBets"] = $value->{".apuestas_void"};
            $array["VoidedPaidBets"] = $value->{".premios_void"};

            $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPlacedBets"];
            $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

            if ($array["Partner"] == 1 || $array["Partner"] == 2) {
                $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPaidBets"];
                $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - 0;
                $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

            }

            if ($array["UserId"] != '') {
                array_push($final, $array);

            }
        }

        /*Generación formato de respuesta*/
        $response["code"] = 0;

        $response["pos"] = $SkeepRows;
        $response["total_count"] = null;
        $response["data"] = $final;

    }

} catch (Exception $e) {
    /*Lanzamiento de errores*/
    throw  $e;
}

//Generación de respuesta vacía
$response["code"] = 0;

$response["pos"] = $SkeepRows;
$response["total_count"] = null;
$response["data"] = $final;
