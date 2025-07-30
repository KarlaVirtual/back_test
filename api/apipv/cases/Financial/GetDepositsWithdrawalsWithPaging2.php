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
use Backend\dto\UsuarioNota;
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
 * Obtiene el listado de depósitos y retiros con paginación
 *
 * Este endpoint permite obtener un listado paginado de depósitos y retiros
 * aplicando diferentes filtros como rango de fechas, sistema de pago,
 * cliente, montos, etc.
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
 *   "IsDetails": boolean            // Indica si se requieren detalles
 * }
 *
 * @return array {
 *   "HasError": boolean,      // Indica si hubo errores
 *   "AlertType": string,      // Tipo de alerta (success, error, warning)
 *   "AlertMessage": string,   // Mensaje descriptivo
 *   "Data": array            // Listado de transacciones
 * }
 *
 * @throws Exception Si hay errores en la consulta a la base de datos
 *
 * @access public
 */


$UsuarioRecarga = new UsuarioRecarga();

$params = file_get_contents('php://input');
$params = json_decode($params);

$Partner = '';

if(is_string($params)) {
    $params = json_decode(base64_decode($params), true);
    if($params['sitebuilder'] == 1) $Partner = base64_decode($params['data']);
}

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
$State = $_REQUEST['State'];
$ExternalId = $_REQUEST['ExternalId'];


$Id = $_REQUEST['Id'];


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


    /**
     * Configuración de reglas de filtrado para la consulta
     * Se construyen las reglas según los parámetros recibidos
     */
    $rules = [];

    /**
     * Filtro por fecha inicial
     * Si se proporciona una fecha inicial, se agrega como regla de filtrado
     */
    if ($FromDateLocal != "") {
        //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
        //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));
    }

    /**
     * Filtro por fecha final
     * Si se proporciona una fecha final, se agrega como regla de filtrado
     */
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

    /**
     * Filtros adicionales por valores mínimos y máximos
     */
    if ($ValueMinimum != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$ValueMinimum", "op" => "ge"));
    }
    if ($ValueMaximum != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$ValueMaximum", "op" => "le"));
    }


    /**
     * Filtros por moneda, ID externo y otros parámetros
     */
    if ($CurrencyId != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }
    if ($ExternalId != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
    }
    if ($Id != "") {
        array_push($rules, array("field" => "usuario_recarga.recarga_id", "data" => "$Id", "op" => "eq"));
    }
    if ($CountrySelect != '') {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }
    if ($State != '') {
        if($State =='A'){
            array_push($rules, array("field" => "usuario_recarga.estado", "data" => 'A', "op" => "eq"));

        }
        if($State =='I'){
            array_push($rules, array("field" => "usuario_recarga.estado", "data" => 'I', "op" => "eq"));

        }
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
        array_push($rules, array("field" => "usuario.mandante", "data" => $Partner ?: $_SESSION['mandante'], "op" => "eq"));
    }else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $Partner ?: $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    if($_SESSION["usuario"] == 4089418){
        array_push($rules, array("field" => "producto.proveedor_id", "data" => "74,214,135", "op" => "ni"));

    }
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

    /**
     * Configuración de agrupación y selección según el tipo de detalles requeridos
     */
    $grouping = "";
    $select = "";
    if ($IsDetails == 1) {
        $MaxRows = 10000;
        $grouping = " usuario.mandante,usuario.puntoventa_id,usuario.pais_id,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d'),usuario_recarga.puntoventa_id,producto.producto_id ";
        $select = "usuario.puntoventa_id,usuario_recarga.valor_iva,usuario.mandante,usuario.pais_id,pais.iso,pais.pais_nom,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') fecha_crea,SUM(usuario_recarga.valor) valoru, SUM(usuario_recarga.valor_iva) comision, usuario.moneda,SUM(transaccion_producto.valor) valor,producto.descripcion,proveedor.descripcion,usuario_recarga.impuesto ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));
       // array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "usuario_recarga.estado", "data" => "A", "op" => "eq"));

    } else {
        $select = " usuario.puntoventa_id,usuario.pais_id,pais.pais_nom,pais.iso,usuario.mandante,usuario_punto.login,usuario_punto.nombre,transaccion_producto.*,producto.*,proveedor.*,usuario.moneda,usuario_recarga.* ";
    }
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /**
     * Configuración de paginación
     */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 5;
    }

    /**
     * Configuración de ordenamiento
     */
    $json = json_encode($filtro);

    $order = "usuario_recarga.recarga_id";
    $orderType = "desc";

    /**
     * Procesamiento de parámetros de ordenamiento
     */
    $data=$_REQUEST;
    $data=json_encode($data);

    $data=preg_replace('/\\\\/', '', $data);

    $data=json_decode($data);

    if ($data->sort != "") {


        if ($data->sort->ClientId != "") {
            $order = "usuario.usuario_id";
            $orderType = ($data->sort->ClientId == "asc") ? "asc" : "desc";

        }
        if ($data->sort->Amount != "") {
            $order = "usuario_recarga.valor";
            $orderType = ($data->sort->Amount == "asc") ? "asc" : "desc";

        }
        if ($data->sort->CreatedLocal != "") {
            $order = "usuario_recarga.fecha_crea";
            $orderType = ($data->sort->CreatedLocal == "asc") ? "asc" : "desc";

        }

        //Previniendo duplicidad de resultados en diferentes intervalos de consulta
        $orderType .= ', usuario_recarga.recarga_id DESC';
    }


    $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping,"");

    /**
     * Procesamiento de resultados
     */
    $transacciones = json_decode($transacciones);

    $final = [];
    $totalm = 0;

    /**
     * Procesamiento de cada transacción
     * Se agregan detalles y se formatean los datos según el tipo de consulta
     */
    foreach ($transacciones->data as $key => $value) {

        $NoteRec='';

        $rules = [];

        array_push($rules, ['field' => 'usuario_nota.ref_id', 'data' => $value->{"usuario_recarga.recarga_id"}, 'op' => 'eq']);

        $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        $UsurioNota = new UsuarioNota();

        $notes = $UsurioNota->getUsuarioNotaCustom('usuario_nota.*', 'usuario_nota.usunota_id', 'asc', 0, 1, $filter, true);

        $notes = json_decode($notes, true);

        if($notes['data'] != null && $notes['data'][0] != null){
            $NoteRec=($notes['data'][0])['usuario_nota.descripcion'];

        }

        $array = [];
        if ($IsDetails == 1) {
            $totalm = $totalm + $value->{".valoru"};


        } else {
            $totalm = $totalm + $value->{"transaccion_producto.valor"};

        }
        if ($value->{"producto.descripcion"} == "") {


            $array["Provider"] = "Punto Venta".'-'.$value->{"usuario.mandante"}.'-'.$value->{"usuario.moneda"};
            if($value->{"usuario.puntoventa_id"} != '' && $value->{"usuario.puntoventa_id"} != '0') {
                $array["Provider"] = "Agencias - Punto Venta".'-'.$value->{"usuario.mandante"}.'-'.$value->{"usuario.moneda"};

            }
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Provider"] = "Betshop".'-'.$value->{"usuario.mandante"};
            }

            $array["Id"] = $value->{"usuario_recarga.recarga_id"};
            $array["Tax"] = $value->{"usuario_recarga.impuesto"};
            $array["ClientId"] = $value->{"usuario_recarga.usuario_id"};
            $array["UserName"] = $value->{"usuario_punto.login"};
            $array["CreatedLocal"] = $value->{"usuario_recarga.fecha_crea"};
            $array["ModifiedLocal"] = $value->{"usuario_recarga.fecha_elimina"};
            if($array["ModifiedLocal"] ==''){
                $array["ModifiedLocal"] = $value->{"usuario_recarga.fecha_crea"};

            }
            if($value->{"usuario.mandante"} == 18){
            $array["Name"] = $value->{"usuario.nombre"};
            $array["LastName"] = $value->{"registro.apellido1"};
            }


            if ($IsDetails == 1) {
                $array["Amount"] = $value->{".valoru"};
                $array["CreatedLocal"] = $value->{".fecha_crea"};
                $array["ExternalId"] = "";

            } else {
                $array["Amount"] = $value->{"usuario_recarga.valor"};


            }
            $array["PaymentSystemName"] = "Efectivo - P.V." . $value->{"usuario_punto.nombre"};

            if($value->{"usuario.puntoventa_id"} != '' && $value->{"usuario.puntoventa_id"} != '0') {
                $array["PaymentSystemName"] = "Agencias - Efectivo - P.V." . $value->{"usuario_punto.nombre"};
            }

            if(strtolower($_SESSION["idioma"])=="en"){
                $array["PaymentSystemName"] = "Cash - P.V." . $value->{"usuario_punto.nombre"};
            }

            $array["TypeName"] = "Payment";

            $array["CurrencyId"] = $value->{"usuario.moneda"}.'-'.$value->{"usuario.mandante"};
            $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
            $array["State"] = $value->{"usuario_recarga.estado"};
            $array["Note"] = $NoteRec;
            $array["ExternalId"] = "";

            $array["Partner"] = $value->{"usuario.mandante"};
            $array["Country"] = $value->{"pais.pais_nom"}.'-'.$value->{"usuario.mandante"};
            $array["CountryIcon"] = strtolower($value->{"pais.iso"});



        } else {
            $array["Provider"] = $value->{"proveedor.descripcion"}.'-'.$value->{"usuario.mandante"}.'-'.$value->{"usuario.moneda"};

            $array["Id"] = $value->{"usuario_recarga.recarga_id"};
            $array["Tax"] = $value->{"usuario_recarga.impuesto"};
            $array["ClientId"] = $value->{"transaccion_producto.usuario_id"};
            $array["CreatedLocal"] = $value->{"usuario_recarga.fecha_crea"};
            $array["ModifiedLocal"] = $value->{"transaccion_producto.fecha_modif"};

            if ($IsDetails == 1) {
                $array["ExternalId"] = "";
                $array["Amount"] = $value->{".valoru"};
                $array["CreatedLocal"] = $value->{".fecha_crea"};
                $array["Commission"] = $value->{".comision"}; // retorna comision
            } else {
                $array["Amount"] = $value->{"usuario_recarga.valor"};
                $array["ExternalId"] = $value->{"transaccion_producto.externo_id"};
                $array["Commission"] = $value->{"usuario_recarga.valor_iva"}; // retorna comision
            }

            $array["PaymentSystemName"] = $value->{"producto.descripcion"}.'-'.$value->{"usuario.mandante"};
            $array["TypeName"] = "Payment";

            $array["CurrencyId"] = $value->{"usuario.moneda"}.'-'.$value->{"usuario.mandante"};
            $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
            $array["Ip"] = $value->{"usuario_recarga.dir_ip"};
            $array["State"] = $value->{"transaccion_producto.estado_producto"};
            $array["Note"] = $NoteRec;


            $array["Partner"] = $value->{"usuario.mandante"};
            $array["Country"] = $value->{"pais.pais_nom"}.'-'.$value->{"usuario.mandante"};
            $array["CountryIcon"] = strtolower($value->{"pais.iso"});

        }
        if($value->{"usuario.puntoventa_id"} != '' && $value->{"usuario.puntoventa_id"} != '0'){
            $array["CurrencyId"]='Agencias  - '.$array["CurrencyId"];
            $array["Provider"] =$array["Provider"] .' - Agencias';
        }
        array_push($final, $array);
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);

    if ($IsDetails == 1) {
        $response["pos"] = 0;
        $response["data"] = $final;

    } else {
        $response["pos"] = $SkeepRows;
        $response["total_count"] = $transacciones->count[0]->{".count"};
        $response["data"] = $final;

    }


} else {
    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
