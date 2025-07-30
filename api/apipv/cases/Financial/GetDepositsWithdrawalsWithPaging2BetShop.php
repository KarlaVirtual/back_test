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
 * Obtiene el listado de depósitos y retiros con paginación para casas de apuestas
 *
 * @param object $params {
 *   @type string $ToCreatedDateLocal Fecha final en formato Y-m-d H:i:s
 *   @type string $FromCreatedDateLocal Fecha inicial en formato Y-m-d H:i:s
 *   @type string $PaymentSystemId ID del sistema de pago
 *   @type string $CashDeskId ID de la caja
 *   @type string $ClientId ID del cliente
 *   @type float $AmountFrom Monto mínimo
 *   @type float $AmountTo Monto máximo
 *   @type string $CurrencyId ID de la moneda
 *   @type string $ExternalId ID externo
 *   @type string $Id ID de la transacción
 *   @type bool $IsDetails Indica si se requieren detalles
 *   @type string $OrderedItem Campo de ordenamiento
 * }
 * 
 * @return object {
 *   @type int $pos Posición actual en la paginación
 *   @type int $total_count Total de registros
 *   @type array $data Lista de transacciones {
 *     @type string $Provider Proveedor del servicio
 *     @type string $Id ID de la transacción
 *     @type float $Tax Impuesto
 *     @type string $ClientId ID del cliente
 *     @type string $CreatedLocal Fecha de creación
 *     @type string $ModifiedLocal Fecha de modificación
 *     @type float $Amount Monto
 *     @type string $ExternalId ID externo
 *     @type float $Commission Comisión
 *     @type string $PaymentSystemName Sistema de pago
 *     @type string $TypeName Tipo de transacción
 *     @type string $CurrencyId Moneda
 *     @type string $CashDeskId ID de caja
 *     @type string $Ip Dirección IP
 *     @type string $State Estado
 *     @type string $Note Nota
 *     @type string $Partner Socio
 *     @type string $Country País
 *     @type string $CountryIcon Ícono del país
 *   }
 * }
 *
 * @access public
 */


$UsuarioRecarga = new UsuarioRecarga();
/**
 * Procesamiento de parámetros de entrada
 * 
 * Se obtienen los parámetros del request en formato JSON y se extraen los valores
 * necesarios para el filtrado de transacciones:
 * - Fechas de inicio y fin con formato Y-m-d H:i:s
 * - IDs de sistema de pago, caja y cliente
 * - Rangos de montos
 * - Moneda
 * - IDs externos y de transacción
 * - Flag para obtener detalles
 */

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ToCreatedDateLocal;

if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
}


$FromDateLocal = $params->FromCreatedDateLocal;


if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}

$PaymentSystemId = $params->PaymentSystemId;
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;

//Fijamos para obtener siempre detalles
$IsDetails = true;

$FromId = $_REQUEST["FromId"];
$PlayerId = $_REQUEST["PlayerId"];
$Ip = $_REQUEST["Ip"];
$IsDetails = $_REQUEST["IsDetails"];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$PaymentSystemId = $_REQUEST["PaymentSystemId"];
$ProviderId = $_REQUEST["ProviderId"];

$ValueMinimum = $_REQUEST["ValueMinimum"];
$ValueMaximum = $_REQUEST["ValueMaximum"];
$BetShopId = $_REQUEST["BetShopId"];


$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$seguir = true;

if ($MaxRows == "") {
    $seguir = false;
}

if ($SkeepRows == "") {
    $seguir = false;
}

if ($seguir) {


    $rules = [];

    /**
     * Configuración de reglas de filtrado para la consulta de transacciones
     * 
     * Este bloque de código establece las reglas de filtrado para la consulta de transacciones
     * basándose en diferentes parámetros:
     * 
     * - Filtros de fechas (FromDateLocal y ToDateLocal)
     * - Filtros de sistema de pago (PaymentSystemId)
     * - Filtros de caja/punto de venta (CashDeskId, BetShopId) 
     * - Filtros de cliente y montos (ClientId, AmountFrom/To, ValueMinimum/Maximum)
     * - Filtros de moneda e IDs (CurrencyId, ExternalId, Id)
     * - Filtros de país (CountrySelect)
     * - Filtros de proveedor (ProviderId)
     * 
     * También incluye reglas específicas según el perfil del usuario:
     * - Reglas para concesionarios (niveles 1, 2 y 3)
     * - Restricciones por país del usuario
     * - Restricciones por mandante
     * 
     * Las reglas se agregan al array $rules usando array_push() y cada regla
     * contiene el campo a filtrar, el valor y el operador de comparación
     */

    if ($FromDateLocal != "") {
        //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
        //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));
    }
    if ($ToDateLocal != "") {
        //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
        //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));
    }


    if ($PaymentSystemId != "") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
    }

    if ($CashDeskId != "") {
        array_push($rules, array("field" => "punto_venta.usuario_id", "data" => "$CashDeskId", "op" => "eq"));
    }

    if ($BetShopId != "") {
        array_push($rules, array("field" => "usuario_recarga.puntoventa_id", "data" => "$BetShopId", "op" => "eq"));
    }
    if ($ClientId != "") {
        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }

    if ($AmountFrom != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
    }
    if ($AmountTo != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
    }

    if ($ValueMinimum != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$ValueMinimum", "op" => "ge"));
    }
    if ($ValueMaximum != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$ValueMaximum", "op" => "le"));
    }


    if ($CurrencyId != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }
    if ($ExternalId != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
    }
    if ($Id != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
    }
    if ($CountrySelect != '') {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }

    if ($ProviderId != '') {
        array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
    }


    if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }

    if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }


    if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }

    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    }else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    if ($FromId != "") {

        $UsuarioPerfil = new UsuarioPerfil($FromId, "");

        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
            array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => "$FromId", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => "$FromId", "op" => "eq"));
        }
        //array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$FromIdGetBetHistory", "op" => "eq"));
    }

    if ($_SESSION["win_perfil2"] == "CAJERO") {

        array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }

    if ($PlayerId != "") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
    }

    if ($Ip != "") {
        array_push($rules, array("field" => "usuario_recarga.dir_ip", "data" => "$Ip", "op" => "cn"));

    }

    $grouping = "";
    $select = "";
    if ($IsDetails == 1) {
        $MaxRows = 10000;
        $grouping = " usuario.mandante,usuario.pais_id,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d'),usuario_recarga.puntoventa_id,producto.producto_id ";
        $select = "usuario.mandante,usuario.pais_id,pais.iso,pais.pais_nom,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') fecha_crea,SUM(usuario_recarga.valor) valoru,usuario.moneda,SUM(transaccion_producto.valor) valor,producto.descripcion,proveedor.descripcion,usuario_recarga.impuesto ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));
        array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));

    } else {
        $select = " usuario.pais_id,pais.pais_nom,pais.iso,usuario.mandante,usuario_punto.login,usuario_punto.nombre,transaccion_producto.*,producto.*,proveedor.*,usuario.moneda,usuario_recarga.* ";

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

    $order = "usuario_recarga.recarga_id";
    $orderType = "desc";

    if ($_REQUEST["sort"] != "") {

        if ($_REQUEST["sort"]["ClientId"] != "") {
            $order = "usuario.usuario_id";
            $orderType = ($_REQUEST["sort"]["ClientId"] == "asc") ? "asc" : "desc";

        }
    }

    /**
     * Obtiene y procesa las transacciones de depósitos y retiros
     * 
     * Consulta las transacciones usando el método getUsuarioRecargasCustom con los parámetros de filtrado
     * Decodifica la respuesta JSON de las transacciones
     * Procesa cada transacción para construir un array con el formato requerido:
     *  - Calcula totales
     *  - Formatea datos según sea una transacción de punto de venta o proveedor
     *  - Agrega información de moneda, estado, notas y datos del país
     * Construye la respuesta final con:
     *  - Paginación (pos, total_count)
     *  - Datos formateados (data)
     *  - En caso de no haber resultados, retorna una respuesta vacía
     */

    $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping,"");

    $transacciones = json_decode($transacciones);

    $final = [];
    $totalm = 0;
    foreach ($transacciones->data as $key => $value) {
        $array = [];
        if ($IsDetails == 1) {
            $totalm = $totalm + $value->{".valoru"};


        } else {
            $totalm = $totalm + $value->{"transaccion_producto.valor"};

        }
        if ($value->{"producto.descripcion"} == "") {
            $array["Provider"] = "Punto Venta".'-'.$value->{"usuario.mandante"};

            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Provider"] = "Betshop".'-'.$value->{"usuario.mandante"};
            }

            $array["Id"] = $value->{"usuario_recarga.recarga_id"};
            $array["Tax"] = $value->{"usuario_recarga.impuesto"};

            $array["ClientId"] = $value->{"usuario_recarga.usuario_id"};
            $array["UserName"] = $value->{"usuario_punto.login"};
            $array["CreatedLocal"] = $value->{"usuario_recarga.fecha_crea"};
            $array["ModifiedLocal"] = $value->{"usuario_recarga.fecha_crea"};

            if ($IsDetails == 1) {
                $array["Amount"] = $value->{".valoru"};
                $array["CreatedLocal"] = $value->{".fecha_crea"};
                $array["ExternalId"] = "";

            } else {
                $array["Amount"] = $value->{"usuario_recarga.valor"};


            }
            $array["PaymentSystemName"] = "Efectivo - P.V." . $value->{"usuario_punto.nombre"};

            if(strtolower($_SESSION["idioma"])=="en"){
                $array["PaymentSystemName"] = "Cash - P.V." . $value->{"usuario_punto.nombre"};
            }

            $array["TypeName"] = "Payment";

            $array["CurrencyId"] = $value->{"usuario.moneda"}.'-'.$value->{"usuario.mandante"};
            $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
            $array["State"] = $value->{"usuario_recarga.estado"};
            $array["Note"] = "T";
            $array["ExternalId"] = "";

            $array["Partner"] = $value->{"usuario.mandante"};
            $array["Country"] = $value->{"pais.pais_nom"}.'-'.$value->{"usuario.mandante"};
            $array["CountryIcon"] = strtolower($value->{"pais.iso"});


        } else {
            $array["Provider"] = $value->{"proveedor.descripcion"}.'-'.$value->{"usuario.mandante"};

            $array["Id"] = $value->{"usuario_recarga.recarga_id"};
            $array["Tax"] = $value->{"usuario_recarga.impuesto"};
            $array["ClientId"] = $value->{"transaccion_producto.usuario_id"};
            $array["CreatedLocal"] = $value->{"usuario_recarga.fecha_crea"};
            $array["ModifiedLocal"] = $value->{"transaccion_producto.fecha_modif"};

            if ($IsDetails == 1) {
                $array["Amount"] = $value->{".valoru"};
                $array["ExternalId"] = "";
                $array["CreatedLocal"] = $value->{".fecha_crea"};

            } else {

                $array["Amount"] = $value->{"transaccion_producto.valor"};
                $array["ExternalId"] = $value->{"transaccion_producto.externo_id"};


            }

            $array["PaymentSystemName"] = $value->{"producto.descripcion"}.'-'.$value->{"usuario.mandante"};
            $array["TypeName"] = "Payment";

            $array["CurrencyId"] = $value->{"usuario.moneda"}.'-'.$value->{"usuario.mandante"};
            $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
            $array["Ip"] = $value->{"usuario_recarga.dir_ip"};
            $array["State"] = $value->{"transaccion_producto.estado_producto"};
            $array["Note"] = "";


            $array["Partner"] = $value->{"usuario.mandante"};
            $array["Country"] = $value->{"pais.pais_nom"}.'-'.$value->{"usuario.mandante"};
            $array["CountryIcon"] = strtolower($value->{"pais.iso"});

        }
        array_push($final, $array);
    }

    if ($IsDetails == 1) {
        $response["pos"] = 0;
        $response["data"] = $final;

    } else {
        $response["pos"] = $SkeepRows;
        $response["total_count"] = $transacciones->count[0]->{".count"};
        $response["data"] = $final;

    }


} else {
    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
