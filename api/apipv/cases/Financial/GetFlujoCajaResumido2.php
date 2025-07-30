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


/*
 * Documentación de la función
 *
 * @param string $dateTo : Descripción: Fecha de fin para el reporte de flujo de caja.
 * @param string $dateFrom : Descripción: Fecha de inicio para el reporte de flujo de caja.
 * @param int $PaymentSystemId : Descripción: Identificador del sistema de pago.
 * @param int $CashDeskId : Descripción: Identificador de la caja.
 * @param int $ClientId : Descripción: Identificador del cliente.
 * @param float $AmountFrom : Descripción: Monto mínimo para el flujo de caja.
 * @param float $AmountTo : Descripción: Monto máximo para el flujo de caja.
 * @param int $CurrencyId : Descripción: Identificador de la moneda.
 * @param string $ExternalId : Descripción: Identificador externo.
 * @param int $Id : Descripción: Identificador del flujo de caja.
 * @param bool $IsDetails : Descripción: Indicador para obtener información detallada.
 * @param int $CountrySelect : Descripción: Identificador del país seleccionado.
 * @param int $BetShopId : Descripción: Identificador de la tienda de apuestas.
 * @param int $MaxRows : Descripción: Número máximo de filas a devolver.
 * @param int $OrderedItem : Descripción: Ítem ordenado.
 * @param int $SkeepRows : Descripción: Número de filas a omitir en la consulta.
 * @param string $FromId : Descripción: Identificador de la fuente.
 * @param string $PlayerId : Descripción: Identificador del jugador.
 * @param string $Ip : Descripción: Dirección IP.
 * @param string $NoTicket : Descripción: Número de ticket.
 *
 * @Description Obtener el flujo de caja resumido sumando los valores de las transacciones y tambien obteniendo el total de la suma de la columna valor de la tabla usuario_recarga
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en la operación.
 * - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 * - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 * - *url* (string): URL de redirección en caso de éxito.
 * - *success* (string): Mensaje de éxito de la operación.
 * - *data* (array): Datos del flujo de caja.
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 *
 * Objeto en caso de error:
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'danger';
 * $response['AlertMessage'] = 'Invalid';
 * $response['url'] = '';
 * $response['success'] = '';
 *
 * @throws Exception Permiso denegado
 * @throws Exception Inusual Detectado
 *
 */
/**
 * Inicializa una nueva instancia de la clase PuntoVenta y procesa parámetros de entrada.
 *
 * Se obtienen parámetros JSON desde la entrada de PHP y se procesan para configurar
 * las variables necesarias para el trabajo con ventas. Se realiza formato de fecha
 * y validación de algunos parámetros.
 */


/* Se crea un objeto PuntoVenta y se obtiene una fecha desde la entrada JSON. */
$PuntoVenta = new PuntoVenta();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->dateTo;


/* convierte fechas de solicitudes en formato adecuado según una zona horaria. */
$ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . '' . $timezone . ' hour '));

$FromDateLocal = $params->dateFrom;

$FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


$PaymentSystemId = $params->PaymentSystemId;

/* Asignación de parámetros a variables para procesar transacciones en un sistema. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* asigna valores a variables según condiciones específicas de entrada. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;

$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$BetShopId = (is_numeric($_REQUEST["BetShopId"])) ? $_REQUEST["BetShopId"] : '';
$TypeDetail = ($_REQUEST["TypeDetail"] == "0") ? 0 : 1;

/* asigna valores según solicitudes y establece variables para manejo de datos. */
$TypeTotal = ($_REQUEST["Type"] == "0") ? 0 : 1;

$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$seguir = true;


/* verifica si las variables están vacías y establece la continuidad en falso. */
if ($SkeepRows == "" || $MaxRows == "") {
    $seguir = false;

}

if ($seguir) {

    /* Asigna una fecha por defecto si $FromDateLocal está vacío, ajustando según la zona horaria. */
    if ($FromDateLocal == "") {


        $FromDateLocal = date("Y-m-d 00:00:00", strtotime(time() . $timezone . ' hour '));

    }

    /* Asigna la fecha y hora actuales en caso de que $ToDateLocal esté vacío. */
    if ($ToDateLocal == "") {

        $ToDateLocal = date("Y-m-d 23:59:59", strtotime(time() . '' . $timezone . ' hour '));


    }


    /* Se definen reglas para filtrar fechas en un arreglo vacío de reglas. */
    $rules = [];

    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


    $grouping = "";

    /* determina agrupación y construye una consulta SQL para sumar valores. */
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


    /* inicializa variables si están vacías, asignando valores predeterminados. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Establece un límite de filas y obtiene transacciones según el perfil de usuario. */
    if ($MaxRows == "") {
        $MaxRows = 1000000;
    }
    $MaxRows = 1000000;

    if ($_SESSION["win_perfil2"] == "CAJERO") {

        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario'], "", "", "", "", $BetShopId);

    } elseif ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
        /* Valida el perfil "PUNTOVENTA" y obtiene un resumen de transacciones del punto de venta. */


        $transacciones = $PuntoVenta->getFlujoCajaResumidoConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
        /* Se obtienen transacciones resumidas para el perfil de usuario "CAJERO". */


        $transacciones = $PuntoVenta->getFlujoCajaResumidoConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        /* Consulta de transacciones de concesionarios en un sistema de flujo de caja. */


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario'], "", "", "", "", $BetShopId);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        /* Condicional que obtiene transacciones si el usuario es "CONCESIONARIO2". */


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario'], "", "", "", $BetShopId);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        /* Condición para obtener transacciones de un concesionario específico en un sistema de puntos de venta. */


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", "", $_SESSION['usuario'], "", "", $BetShopId);

    } else {


        /* asigna un país basado en selección o condición de sesión. */
        $Pais = "";

        if ($CountrySelect != "" && $CountrySelect != "0") {
            $Pais = $CountrySelect;
        }

        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            $Pais = $_SESSION['pais_id'];
        }


        /* Asignación de un valor a $Mandante según condiciones de sesión del usuario. */
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


        /* Obtiene un resumen de transacciones utilizando varios parámetros de filtrado y agrupación. */
        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", "", "", $Pais, $Mandante, $BetShopId);

    }


    /* Decodifica transacciones JSON y prepara variables para almacenar resultados y total. */
    $transacciones = json_decode($transacciones);

    $final = [];
    $totalm = 0;
    foreach ($transacciones->data as $key => $value) {

        /* asigna un valor a un array según condición de sesión específica. */
        $array = [];
        $array["Punto"] = "PUNTO";

        if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
            $array["Punto"] = $value->{"y.login"};

        } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
            /* Se asigna valor a "Punto" si el perfil de sesión es "CAJERO". */

            $array["Punto"] = $value->{"y.login"};

        } else {
            /* Asigna un valor a "Punto" en el arreglo si no se cumple una condición. */

            $array["Punto"] = $value->{"y.punto_venta"};

        }


        /* Asigna valores de un objeto a un array asociativo en PHP. */
        $array["UserId"] = $value->{"y.usuario_id"};

        $array["Fecha"] = $value->{"y.fecha_crea"};
        $array["Moneda"] = $value->{"y.moneda"};
        $array["CountryId"] = $value->{"y.pais_nom"} . ' - ' . $value->{"y.mandante"};
        $array["Partner"] = $value->{"y.mandante"};

        /* asigna valores de un objeto a un array asociativo. */
        $array["CountryIcon"] = strtolower($value->{"y.pais_iso"});
        $array["Agent"] = $value->{"uu.agente"} . ' - ' . $array["Moneda"];
        $array["CantidadTickets"] = $value->{".cant_tickets"};
        $array["ValorEntradasEfectivo"] = $value->{".valor_entrada_efectivo"};
        $array["ValorEntradasBonoTC"] = $value->{".valor_entrada_bono"};
        $array["ValorEntradasRecargas"] = $value->{".valor_entrada_recarga"};


        /* Asignación de valores a un array y cálculo de saldo en PHP. */
        $array["ValorEntradasTraslados"] = $value->{".valor_entrada_traslado"};
        $array["ValorSalidasEfectivo"] = $value->{".valor_salida_efectivo"};
        $array["ValorSalidasTraslados"] = $value->{".valor_salida_traslado"};
        $array["ValorSalidasNotasRetiro"] = $value->{".valor_salida_notaret"};
        $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"];
        $array["MMoneda"] = $value->{"y.punto_venta"};

        /* Asigna valores de impuestos y apuestas void a un array y calcula entradas netas. */
        $array["Tax"] = $value->{".impuestos"};


        $array["VoidedPlacedBets"] = $value->{".apuestas_void"};
        $array["VoidedPaidBets"] = $value->{".premios_void"};

        $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPlacedBets"];

        /* Calcula salidas y entradas netas de efectivo considerando apuestas anuladas para socios específicos. */
        $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

        if ($array["Partner"] == 1 || $array["Partner"] == 2) {
            $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPaidBets"];
            $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - 0;
            $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

        }


        /* Añade elementos a un array final si "UserId" no está vacío. */
        if ($array["UserId"] != '') {
            array_push($final, $array);

        }
    }


    if ($BetShopId == "") {


        /* Crea un objeto de usuario y decodifica datos JSON desde la entrada. */
        $UsuarioRecarga = new UsuarioRecarga();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ToCreatedDateLocal;


        /* establece fechas límite y formato usando datos de una solicitud HTTP. */
        if ($_REQUEST["dateTo"] != "") {
            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
        }


        $FromDateLocal = $params->FromCreatedDateLocal;


        /* procesa una fecha solicitada y asigna identificadores de sistema de pago y caja. */
        if ($_REQUEST["dateFrom"] != "") {
            $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
        }

        $PaymentSystemId = $params->PaymentSystemId;
        $CashDeskId = $params->CashDeskId;

        /* Asigna valores de parámetros a variables en un script, para su procesamiento posterior. */
        $ClientId = $params->ClientId;
        $AmountFrom = $params->AmountFrom;
        $AmountTo = $params->AmountTo;
        $CurrencyId = $params->CurrencyId;
        $ExternalId = $params->ExternalId;
        $Id = $params->Id;

        /* asigna un valor booleano a `$IsDetails` y obtiene un parámetro de la solicitud. */
        $IsDetails = ($params->IsDetails == true) ? true : false;

        //Fijamos para obtener siempre detalles
        $IsDetails = true;

        $FromId = $_REQUEST["FromId"];

        /* recoge datos de un jugador mediante solicitudes HTTP para su procesamiento. */
        $PlayerId = $_REQUEST["PlayerId"];
        $Ip = $_REQUEST["Ip"];
        $IsDetails = 1;
        $CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';


        $MaxRows = $_REQUEST["count"];

        /* procesa pedidos y controla la paginación de resultados en una solicitud. */
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


            /* Agrega reglas de filtrado basadas en fechas a un arreglo si la fecha existe. */
            $rules = [];

            if ($FromDateLocal != "") {
                //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            }

            /* agrega condiciones a un arreglo según variables no vacías. */
            if ($ToDateLocal != "") {
                //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
            }


            if ($PaymentSystemId != "") {
                array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
            }


            /* Condiciona la adición de reglas basadas en la existencia de identificadores. */
            if ($CashDeskId != "") {
                array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
            }
            if ($ClientId != "") {
                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
            }


            /* Agrega reglas de comparación a un array según valores de cantidad ingresados. */
            if ($AmountFrom != "") {
                array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
            }
            if ($AmountTo != "") {
                array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
            }


            /* Agrega reglas a un array si las variables no están vacías. */
            if ($CurrencyId != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
            }
            if ($ExternalId != "") {
                array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
            }

            /* Condiciones que agregan reglas a un array basadas en variables no vacías. */
            if ($Id != "") {
                array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
            }
            if ($CountrySelect != '') {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
            }


            /* Verifica el perfil de usuario y agrega reglas según el tipo de concesionario. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

            }


            /* añade reglas basadas en condiciones de sesión para usuario y país. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));

            }

            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

            // Si el usuario esta condicionado por el mandante y no es de Global

            /* gestiona reglas para un campo de usuario basado en sesiones. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }


            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* verifica el perfil de usuario y agrega reglas a un array. */
            if ($FromId != "") {

                $UsuarioPerfil = new UsuarioPerfil($FromId, "");

                if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
                    array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => "$FromId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => "$FromId", "op" => "eq"));
                }
                //array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$FromIdGetBetHistory", "op" => "eq"));
            }


            /* Agrega condiciones de filtrado para el jugador y la IP en un arreglo de reglas. */
            if ($PlayerId != "") {
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
            }

            if ($Ip != "") {
                array_push($rules, array("field" => "usuario_recarga.dir_ip", "data" => "$Ip", "op" => "cn"));

            }


            /* Configura consultas SQL para obtener detalles de usuarios y transacciones específicas. */
            $grouping = "";
            $select = "";
            if ($IsDetails == 1) {
                $MaxRows = 10000;
                $grouping = "usuario.mandante,usuario.pais_id,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d'),proveedor.proveedor_id ";
                $select = "usuario.mandante,pais.*,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') fecha_crea,SUM(usuario_recarga.valor) valoru,usuario.moneda,SUM(transaccion_producto.valor) valor,producto.descripcion,proveedor.descripcion ";
                //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));
                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));

            } else {
                /* Selecciona columnas específicas de varias tablas en una consulta SQL condicional. */

                $select = " pais.*,usuario_punto.login,usuario_punto.nombre,transaccion_producto.*,producto.*,proveedor.*,usuario.moneda,usuario_recarga.* ";

            }

            /* añade reglas de filtrado a un array y establece condiciones. */
            array_push($rules, array("field" => "usuario_recarga.puntoventa_id", "data" => "0", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 5;
            }


            /* Codifica un filtro a JSON, recupera datos y los decodifica para su uso. */
            $json = json_encode($filtro);

            $transacciones2 = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.recarga_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);

            $transacciones2 = json_decode($transacciones2);

            $totalm = 0;
            foreach ($transacciones2->data as $key => $value) {

                /* suma un valor a $totalm si $IsDetails es igual a 1. */
                $array = [];
                if ($IsDetails == 1) {
                    $totalm = $totalm + $value->{".valoru"};


                } else {
                    /* Suma el valor de una transacción a la variable totalm si no se cumple una condición. */

                    $totalm = $totalm + $value->{"transaccion_producto.valor"};

                }


                /* crea un arreglo asociativo con descripciones de proveedor y fecha. */
                $array = [];


                $array["Punto"] = $value->{"proveedor.descripcion"} . ' - ' . $value->{"usuario.moneda"};

                $array["Fecha"] = $value->{".fecha_crea"};

                /* Se crea un array con información sobre moneda, país y pagos. */
                $array["Moneda"] = $value->{"usuario.moneda"};
                $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"usuario.mandante"};
                $array["CountryIcon"] = strtolower($value->{"pais.iso"});
                $array["Agent"] = "Pasarelas de Pago - " . $value->{"usuario.moneda"};
                $array["CantidadTickets"] = 0;
                $array["ValorEntradasEfectivo"] = 0;

                /* Inicializa un arreglo con valores de entradas y salidas en cero y otro valor. */
                $array["ValorEntradasBonoTC"] = 0;
                $array["ValorEntradasRecargas"] = $value->{".valoru"};

                $array["ValorEntradasTraslados"] = 0;
                $array["ValorSalidasEfectivo"] = 0;
                $array["ValorSalidasTraslados"] = 0;

                /* Inicializa un array con valores y los agrega a un arreglo final. */
                $array["ValorSalidasNotasRetiro"] = 0;
                $array["Saldo"] = $array["ValorEntradasRecargas"];
                $array["MMoneda"] = 0;
                $array["Tax"] = 0;


                array_push($final, $array);
            }


            /* crea un objeto y obtiene datos JSON de la entrada HTTP. */
            $CuentaCobro = new CuentaCobro();

            $params = file_get_contents('php://input');
            $params = json_decode($params);

            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $ToDateLocal)));

            /* Convierte fechas en formato local y asigna una fecha final desde parámetros. */
            $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal)));
            //$Region = $params->Region;
            //$CurrencyId = $params->CurrencyId;
            //$IsNewRegistered = $params->IsNewRegistered;


            $ToDateLocal = $params->ToCreatedDateLocal;


            /* define dos variables de fecha, procesando una fecha de entrada. */
            if ($_REQUEST["dateTo"] != "") {
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
            }


            $FromDateLocal = $params->FromCreatedDateLocal;


            /* verifica una fecha de entrada y la convierte en formato local. */
            if ($_REQUEST["dateFrom"] != "") {
                $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
            }


            $MaxRows = $params->MaxRows;

            /* Asignación de valores de parámetros y control de filas a omitir en un proceso. */
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Inicializa `$OrderedItem` y `$MaxRows` si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Se establecen reglas para filtrar datos de "cuenta_cobro" con condiciones específicas. */
            $rules = [];
            array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
            array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "0", "op" => "ne"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));

            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }


            /* agrega reglas basadas en moneda y perfil de usuario a un arreglo. */
            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
            }


            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* verifica permisos de usuario y añade reglas a un array según su perfil. */
            if ($_SESSION["win_perfil2"] == "CAJERO") {
                array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* agrega reglas basadas en el perfil del usuario en sesión. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            // Si el usuario esta condicionado por País

            /* Condiciona reglas basadas en la sesión del usuario y su país o mandante. */
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

            // Si el usuario esta condicionado por el mandante y no es de Global
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {
                /* Agrega una regla a "rules" basada en la sesión de "mandanteLista". */


                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* Se define un filtro, se obtienen cuentas de cobro y se decodifican en JSON. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $cuentas = $CuentaCobro->getCuentasCobroCustom("usuario.mandante,usuario.pais_id,pais.pais_nom,COUNT(*) count,SUM(cuenta_cobro.valor) valor,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d') fecha_crea,producto.descripcion,usuario.moneda", "cuenta_cobro.fecha_pago", "desc", $SkeepRows, $MaxRows, $json, true, "usuario.mandante,usuario.pais_id,transaccion_producto.producto_id,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d'),usuario.moneda");

            $cuentas = json_decode($cuentas);


            /* Inicializa variables para almacenar valores de conversion y total de retiros. */
            $valor_convertidoretiros = 0;
            $totalretiros = 0;
            foreach ($cuentas->data as $key => $value) {


                /* Asigna una descripción por defecto si está vacía y crea un array. */
                $array = [];

                if ($value->{"producto.descripcion"} == "") {
                    $value->{"producto.descripcion"} = "Fisicamente";
                }

                $array["Punto"] = "Cuentas - Giros - " . $value->{"producto.descripcion"} . '-' . $value->{"usuario.moneda"};


                /* asigna valores a un array basado en propiedades de un objeto. */
                $array["Fecha"] = $value->{".fecha_crea"};
                $array["Moneda"] = $value->{"usuario.moneda"};
                $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"usuario.mandante"};
                $array["CountryIcon"] = strtolower('pe');
                $array["Agent"] = "Cuentas Bancarias y Giros - " . $value->{"usuario.moneda"};
                $array["CantidadTickets"] = 0;

                /* inicializa valores de entradas y salidas en un array. */
                $array["ValorEntradasEfectivo"] = 0;
                $array["ValorEntradasBonoTC"] = 0;
                $array["ValorEntradasRecargas"] = 0;

                $array["ValorEntradasTraslados"] = 0;
                $array["ValorSalidasEfectivo"] = 0;

                /* Se calcula el saldo y se agrega un array a la lista final. */
                $array["ValorSalidasTraslados"] = 0;
                $array["ValorSalidasNotasRetiro"] = $value->{".valor"};
                $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"];
                $array["MMoneda"] = 0;
                $array["Tax"] = 0;


                array_push($final, $array);


            }


        } else {
            /* presenta una estructura condicional 'else' vacía sin instrucciones dentro. */


        }
    }


    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    /* Se crean y asignan valores a las claves del arreglo de respuesta. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $transacciones->count[0]->{".count"};
    $response["data"] = $final;
} else {
    /* Inicializa una respuesta vacía si no se cumplen ciertas condiciones. */

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = [];

}