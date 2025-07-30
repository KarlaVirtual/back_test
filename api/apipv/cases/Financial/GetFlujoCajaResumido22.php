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
 * Financial/GetFlujoCajaResumido22
 *
 * Obtener el flujo de caja resumido versión 22
 *
 * @param no
 *
 * @return no
 * {"HasError":boolean,"AlertType": string,"AlertMessage": string,"url": string,"success": string}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se crea una instancia de PuntoVenta y se decodifican parámetros JSON de entrada. */
$PuntoVenta = new PuntoVenta();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->dateTo;


/* convierte fechas de entrada a la zona horaria local y las formatea. */
$ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . '' . $timezone . ' hour '));

$FromDateLocal = $params->dateFrom;

$FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


$PaymentSystemId = $params->PaymentSystemId;

/* Asignación de parámetros a variables para su posterior uso en un sistema. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* asigna valores de parámetros y verifica tipos de datos de entrada. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;

$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$BetShopId = (is_numeric($_REQUEST["BetShopId"])) ? $_REQUEST["BetShopId"] : '';
$TypeDetail = ($_REQUEST["TypeDetail"] == "0") ? 0 : 1;


/* valida parámetros de entrada para un procesamiento posterior. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$seguir = true;

if ($SkeepRows == "" || $MaxRows == "") {
    $seguir = false;

}

if ($seguir) {

    /* asigna una fecha local si no está definida previamente. */
    if ($FromDateLocal == "") {


        $FromDateLocal = date("Y-m-d 00:00:00", strtotime(time() . $timezone . ' hour '));

    }

    /* Asigna la fecha actual con hora específica si $ToDateLocal está vacío. */
    if ($ToDateLocal == "") {

        $ToDateLocal = date("Y-m-d 23:59:59", strtotime(time() . '' . $timezone . ' hour '));


    }


    /* Código define reglas de filtrado y agrupación para consultas de datos. */
    $rules = [];

    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


    $grouping = "";

    /* Código que construye una consulta SQL según la condición de 'IsDetails'. */
    $select = "";
    if ($IsDetails) {

    } else {
        $grouping = " usuario_recarga.puntoventa_id,producto.producto_id ";
        $select = "SUM(usuario_recarga.valor) valoru,SUM(transaccion_producto.valor) valor, ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));

    }


    /* asigna valores predeterminados si las variables están vacías. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Establece un límite de transacciones y obtiene datos según el perfil de usuario. */
    if ($MaxRows == "") {
        $MaxRows = 1000000;
    }
    $MaxRows = 1000000;


    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        $transacciones = $PuntoVenta->getFlujoCajaResumidoConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
        /* Verifica el perfil del usuario y obtiene transacciones resumidas si es cajero. */


        $transacciones = $PuntoVenta->getFlujoCajaResumidoConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        /* verifica un perfil de sesión y obtiene transacciones resumidas. */


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario'], "", "", "", "", $BetShopId);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        /* Condicional que obtiene transacciones si el perfil de usuario es "CONCESIONARIO2". */


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario'], "", "", "", $BetShopId);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        /* Condicional para recuperar transacciones según perfil de concesionario en sesión. */


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", "", $_SESSION['usuario'], "", "", $BetShopId);

    } else {


        /* asigna un país basado en selección o sesión del usuario. */
        $Pais = "";

        if ($CountrySelect != "" && $CountrySelect != "0") {
            $Pais = $CountrySelect;
        }

        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            $Pais = $_SESSION['pais_id'];
        }


        /* asigna un valor a $Mandante basado en condiciones de sesión. */
        $Mandante = "";
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            $Mandante = $_SESSION["mandante"];
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                $Mandante = $_SESSION["mandanteLista"];
            }

        }

        // Inactivamos reportes para el país Colombia
        //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        /* Consulta datos de transacciones, productos y usuarios en un punto de venta. */
        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", "", "", $Pais, $Mandante, $BetShopId);

    }


    /* Se decodifica un JSON y se inicializan un arreglo y un total acumulado. */
    $transacciones = json_decode($transacciones);

    $final = [];
    $totalm = 0;
    foreach ($transacciones->data as $key => $value) {

        /* asigna un valor a un array basado en una condición de sesión. */
        $array = [];
        $array["Punto"] = "PUNTO";

        if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
            $array["Punto"] = $value->{"y.login"};

        } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
            /* Condicional que asigna un valor al array si el perfil es "CAJERO". */

            $array["Punto"] = $value->{"y.login"};

        } else {
            /* Asigna al array "Punto" el valor de "y.punto_venta" en caso contrario. */

            $array["Punto"] = $value->{"y.punto_venta"};

        }


        /* asigna datos a un array desde un objeto llamado $value. */
        $array["Fecha"] = $value->{"y.fecha_crea"};
        $array["Moneda"] = $value->{"y.moneda"};
        $array["CountryId"] = $value->{"y.pais_nom"};
        $array["CountryIcon"] = strtolower($value->{"y.pais_iso"});
        $array["Agent"] = $value->{"uu.agente"};
        $array["CantidadTickets"] = $value->{".cant_tickets"};

        /* Asigna valores de un objeto a claves específicas en un array. */
        $array["ValorEntradasEfectivo"] = $value->{".valor_entrada_efectivo"};
        $array["ValorEntradasBonoTC"] = $value->{".valor_entrada_bono"};
        $array["ValorEntradasRecargas"] = $value->{".valor_entrada_recarga"};

        $array["ValorEntradasTraslados"] = $value->{".valor_entrada_traslado"};
        $array["ValorSalidasEfectivo"] = $value->{".valor_salida_efectivo"};

        /* calcula el saldo y almacena datos en un arreglo. */
        $array["ValorSalidasTraslados"] = $value->{".valor_salida_traslado"};
        $array["ValorSalidasNotasRetiro"] = $value->{".valor_salida_notaret"};
        $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"];
        $array["MMoneda"] = $value->{"y.punto_venta"};


        array_push($final, $array);
    }


    if ($BetShopId == "") {


        /* crea un objeto y decodifica una entrada JSON. */
        $UsuarioRecarga = new UsuarioRecarga();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ToCreatedDateLocal;


        /* manipula fechas recibidas y ajusta el formato según la zona horaria. */
        if ($_REQUEST["dateTo"] != "") {
            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
        }


        $FromDateLocal = $params->FromCreatedDateLocal;


        /* procesa una fecha y obtiene identificadores de sistema de pago y caja. */
        if ($_REQUEST["dateFrom"] != "") {
            $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
        }

        $PaymentSystemId = $params->PaymentSystemId;
        $CashDeskId = $params->CashDeskId;

        /* Asigna valores de $params a variables para su uso posterior en el código. */
        $ClientId = $params->ClientId;
        $AmountFrom = $params->AmountFrom;
        $AmountTo = $params->AmountTo;
        $CurrencyId = $params->CurrencyId;
        $ExternalId = $params->ExternalId;
        $Id = $params->Id;

        /* asigna verdadero a IsDetails y obtiene FromId de la solicitud. */
        $IsDetails = ($params->IsDetails == true) ? true : false;

        //Fijamos para obtener siempre detalles
        $IsDetails = true;

        $FromId = $_REQUEST["FromId"];

        /* recoge datos del jugador y su configuración de solicitud. */
        $PlayerId = $_REQUEST["PlayerId"];
        $Ip = $_REQUEST["Ip"];
        $IsDetails = 1;
        $CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';


        $MaxRows = $_REQUEST["count"];

        /* verifica parámetros de entrada y controla el flujo según condiciones específicas. */
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
        $seguir = true;

        if ($MaxRows == "") {
            $seguir = false;
        }


        /* verifica condiciones para establecer la variable $seguir como falsa. */
        if ($SkeepRows == "") {
            $seguir = false;
        }

        if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3" || $_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO") {
            $seguir = false;
        }

        if ($seguir) {


            /* Se crea un arreglo de reglas basado en la fecha proporcionada. */
            $rules = [];

            if ($FromDateLocal != "") {
                //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
                array_push($rules, array("field" => "usuario_recarga_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            }

            /* añade reglas a un arreglo basado en condiciones específicas de entrada. */
            if ($ToDateLocal != "") {
                //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
                array_push($rules, array("field" => "usuario_recarga_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
            }


            if ($PaymentSystemId != "") {
                array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
            }


            /* Agrega reglas a un array basadas en IDs de escritorio y cliente. */
            if ($CashDeskId != "") {
                array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
            }
            if ($ClientId != "") {
                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
            }


            /* Agrega reglas de filtrado para valores de recarga entre AmountFrom y AmountTo. */
            if ($AmountFrom != "") {
                array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
            }
            if ($AmountTo != "") {
                array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
            }


            /* Agrega reglas de filtrado basadas en los valores de moneda y ID externo. */
            if ($CurrencyId != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
            }
            if ($ExternalId != "") {
                array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
            }

            /* Agrega reglas condicionales a un arreglo basado en variables no vacías. */
            if ($Id != "") {
                array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
            }
            if ($CountrySelect != '') {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
            }


            /* verifica el perfil de usuario y ajusta reglas según su tipo. */
            $innerProducto = false;
            $innerConcesionario = false;

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                $innerConcesionario = true;

                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

            }


            /* Verifica si el usuario es "CONCESIONARIO2" y agrega una regla a la lista. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                $innerConcesionario = true;

                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

            }


            /* Verifica el perfil de usuario y añade una regla de acceso si es necesario. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                $innerConcesionario = true;

                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));

            }

            // Si el usuario esta condicionado por País

            /* condiciona reglas basadas en la sesión del usuario y su país o mandante. */
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

            // Si el usuario esta condicionado por el mandante y no es de Global
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {
                /* Condición que añade reglas basadas en la variable de sesión "mandanteLista". */


                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* establece reglas y construye una consulta SQL para obtener datos específicos. */
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));


            $grouping = "";
            $select = "";

            $select = "pais.*,SUM(usuario_recarga_resumen.cantidad) cantidad,DATE_FORMAT(usuario_recarga_resumen.fecha_crea,'%Y-%m-%d') fecha_crea,SUM(usuario_recarga_resumen.valor) valoru,usuario.moneda,SUM(usuario_recarga_resumen.valor) valor,producto.descripcion,proveedor.descripcion ";

            /* Agrupa datos de usuarios y filtros relacionados en una estructura de consulta. */
            $grouping = "usuario.pais_id,DATE_FORMAT(usuario_recarga_resumen.fecha_crea,'%Y-%m-%d'),proveedor.proveedor_id ";

            array_push($rules, array("field" => "usuario_recarga_resumen.puntoventa_id", "data" => "0", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* asigna valores predeterminados a variables si están vacías. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 5;
            }


            /* Se inicializa una variable, se codifica un filtro y se crea un objeto. */
            $innerProducto = true;


            $json = json_encode($filtro);

            $UsuarioRecargaResumen = new UsuarioRecargaResumen();

            /* Se obtienen y decodifican transacciones de usuario para resumen financiero. */
            $transacciones2 = $UsuarioRecargaResumen->getUsuarioRecargaResumenCustom($select, "usuario.moneda", "desc", $SkeepRows, $MaxRows, $json, true, $grouping, $innerProducto, $innerConcesionario);

            $transacciones2 = json_decode($transacciones2);

            $totalm = 0;
            foreach ($transacciones2->data as $key => $value) {

                /* Se inicializa un array y se suma un valor si se cumplen ciertas condiciones. */
                $array = [];
                if ($IsDetails == 1) {
                    $totalm = $totalm + $value->{".valoru"};


                } else {
                    /* suma un valor específico a la variable totalm en caso contrario. */

                    $totalm = $totalm + $value->{"usuario_recarga_resumen.valor"};

                }


                /* Asigna valores a un array basado en descripciones de un objeto $value. */
                $array = [];


                $array["Punto"] = $value->{"proveedor.descripcion"} . ' - ' . $value->{"usuario.moneda"};

                $array["Fecha"] = $value->{".fecha_crea"};

                /* Asigna valores a un array desde un objeto, utilizando diferentes propiedades del mismo. */
                $array["Moneda"] = $value->{"usuario.moneda"};
                $array["CountryId"] = $value->{"pais.pais_nom"};
                $array["CountryIcon"] = strtolower($value->{"pais.iso"});
                $array["Agent"] = "Pasarelas de Pago - " . $value->{"usuario.moneda"};
                $array["CantidadTickets"] = $value->{".cantidad"};
                $array["ValorEntradasEfectivo"] = 0;

                /* inicializa un arreglo con valores específicos relacionados a entradas y salidas. */
                $array["ValorEntradasBonoTC"] = 0;
                $array["ValorEntradasRecargas"] = $value->{".valoru"};

                $array["ValorEntradasTraslados"] = 0;
                $array["ValorSalidasEfectivo"] = 0;
                $array["ValorSalidasTraslados"] = 0;

                /* inicializa un arreglo y lo agrega a otro arreglo final. */
                $array["ValorSalidasNotasRetiro"] = 0;
                $array["Saldo"] = $array["ValorEntradasRecargas"];
                $array["MMoneda"] = 0;


                array_push($final, $array);
            }


            /* Se crea un objeto "CuentaCobro" y se procesa JSON desde la entrada PHP. */
            $CuentaCobro = new CuentaCobro();

            $params = file_get_contents('php://input');
            $params = json_decode($params);

            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $ToDateLocal)));

            /* Convierte una fecha de entrada a formato específico y asigna otra fecha desde parámetros. */
            $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal)));
            //$Region = $params->Region;
            //$CurrencyId = $params->CurrencyId;
            //$IsNewRegistered = $params->IsNewRegistered;


            $ToDateLocal = $params->ToCreatedDateLocal;


            /* Convierte una fecha recibida y establece un rango temporal para consultas. */
            if ($_REQUEST["dateTo"] != "") {
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
            }


            $FromDateLocal = $params->FromCreatedDateLocal;


            /* verifica una fecha y la formatea con la zona horaria especificada. */
            if ($_REQUEST["dateFrom"] != "") {
                $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
            }


            $MaxRows = $params->MaxRows;

            /* asigna valores a variables y maneja un valor predeterminado para SkeepRows. */
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Se definen reglas de filtrado para un resumen de usuario basado en condiciones. */
            $rules = [];
            array_push($rules, array("field" => "usuario_retiro_resumen.producto_id", "data" => "0", "op" => "ne"));
            array_push($rules, array("field" => "usuario_retiro_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "usuario_retiro_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }


            /* añade reglas basadas en moneda y perfil de usuario a un array. */
            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
            }


            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* define reglas de acceso según el perfil del usuario en sesión. */
            if ($_SESSION["win_perfil2"] == "CAJERO") {
                array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }
            $innerProducto = false;
            $innerConcesionario = false;


            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                $innerConcesionario = true;
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* verifica el perfil de usuario y agrega reglas correspondientes a un array. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                $innerConcesionario = true;
                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                $innerConcesionario = true;
                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            // Si el usuario esta condicionado por País

            /* Condiciona reglas basadas en país y mandante del usuario en sesión. */
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

            // Si el usuario esta condicionado por el mandante y no es de Global
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {
                /* Añade una regla si "mandanteLista" de sesión no está vacía ni es "-1". */


                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }


            /* Código para configurar un filtro de exclusión en reportes de Colombia. */
            $innerProducto = true;

            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* realiza un resumen de retiros de usuario en formato JSON. */
            $json = json_encode($filtro);

            $UsuarioRetiroResumen = new UsuarioRetiroResumen();

            $cuentas = $UsuarioRetiroResumen->getUsuarioRetiroResumenCustom("SUM(usuario_retiro_resumen.cantidad) cantidad,SUM(usuario_retiro_resumen.valor) valor,DATE_FORMAT(usuario_retiro_resumen.fecha_crea,'%Y-%m-%d') fecha_crea", "usuario_retiro_resumen.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true, "DATE_FORMAT(usuario_retiro_resumen.fecha_crea,'%Y-%m-%d'),usuario.moneda", $innerProducto, $innerConcesionario);

            $cuentas = json_decode($cuentas);


            /* Inicializa variables para el total de retiros y su valor convertido. */
            $valor_convertidoretiros = 0;
            $totalretiros = 0;
            foreach ($cuentas->data as $key => $value) {


                /* crea un arreglo con información de cuentas y fechas de creación. */
                $array = [];


                $array["Punto"] = "Cuentas - Giros - " . $value->{"usuario.moneda"};

                $array["Fecha"] = $value->{".fecha_crea"};

                /* asigna valores a un array relacionado con transacciones financieras en Perú. */
                $array["Moneda"] = $value->{"usuario.moneda"};
                $array["CountryId"] = 'Perú';
                $array["CountryIcon"] = strtolower('pe');
                $array["Agent"] = "Cuentas Bancarias y Giros - " . $value->{"usuario.moneda"};
                $array["CantidadTickets"] = 0;
                $array["ValorEntradasEfectivo"] = 0;

                /* Inicializa variables para registrar entradas y salidas monetarias en un array. */
                $array["ValorEntradasBonoTC"] = 0;
                $array["ValorEntradasRecargas"] = 0;

                $array["ValorEntradasTraslados"] = 0;
                $array["ValorSalidasEfectivo"] = 0;
                $array["ValorSalidasTraslados"] = 0;

                /* Se calcula el saldo y se agrega a un array final. */
                $array["ValorSalidasNotasRetiro"] = $value->{".valor"};
                $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"];
                $array["MMoneda"] = 0;


                array_push($final, $array);


            }


        } else {
            /* muestra una estructura condicional sin instrucciones dentro del bloque "else". */


        }
    }


    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    /* guarda datos en un array de respuesta para ser utilizados posteriormente. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $transacciones->count[0]->{".count"};
    $response["data"] = $final;
} else {
    /* inicializa una respuesta vacía cuando no hay datos disponibles. */

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = [];

}
