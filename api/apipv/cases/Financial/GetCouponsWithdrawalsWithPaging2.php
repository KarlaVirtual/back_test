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
 * Obtiene los depósitos y retiros con paginación
 *
 * Este endpoint permite obtener un listado paginado de depósitos y retiros
 * aplicando diferentes filtros como fechas, sistema de pago, caja, etc.
 *
 * @param string $FromCreatedDateLocal Fecha inicial en formato Y-m-d H:i:s
 * @param string $ToCreatedDateLocal Fecha final en formato Y-m-d H:i:s  
 * @param int $PaymentSystemId ID del sistema de pago
 * @param int $CashDeskId ID de la caja
 * @param string $ClientId ID del cliente
 * @param float $AmountFrom Monto mínimo
 * @param float $AmountTo Monto máximo
 * @param string $CurrencyId ID de la moneda
 * @param string $ExternalId ID externo
 * @param int $Id ID de la transacción
 * @param bool $IsDetails Indica si se requieren detalles
 * @param string $PlayerId ID del jugador
 * @param string $Ip Dirección IP
 * @param int $CountrySelect ID del país
 * @param int $ProviderId ID del proveedor
 *
 * @return array {
 *   "HasError": boolean,
 *   "AlertType": string,
 *   "AlertMessage": string,
 *   "Data": {
 *     "Transactions": array,
 *     "TotalRows": int,
 *     "TotalAmount": float,
 *     "TotalPages": int,
 *     "CurrentPage": int
 *   }
 * }
 *
 * @throws Exception Si hay errores en la consulta a la base de datos
 *
 * @access public
 */


/* Se crea un objeto y se procesa JSON recibido por entrada. */
$UsuarioRecarga = new UsuarioRecarga();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ToCreatedDateLocal;

/* establece una fecha final basada en un rango recibido en la solicitud. */
if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
}


$FromDateLocal = $params->FromCreatedDateLocal;


/* procesa una fecha y asigna identificadores de sistema de pago y caja. */
if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}

$PaymentSystemId = $params->PaymentSystemId;
$CashDeskId = $params->CashDeskId;

/* Variables asignan valores de parámetros para utilizar en un proceso posterior. */
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;
$Id = $params->Id;

/* asigna true a $IsDetails y obtiene FromId de la solicitud. */
$IsDetails = ($params->IsDetails == true) ? true : false;

//Fijamos para obtener siempre detalles
$IsDetails = true;

$FromId = $_REQUEST["FromId"];

/* Captura y valida datos de entrada del usuario en una aplicación web. */
$PlayerId = $_REQUEST["PlayerId"];
$Ip = $_REQUEST["Ip"];
$IsDetails = $_REQUEST["IsDetails"];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$PaymentSystemId = $_REQUEST["PaymentSystemId"];
$ProviderId = $_REQUEST["ProviderId"];


/* obtiene parámetros de una solicitud y valida el estado permitido. */
$ValueMinimum = $_REQUEST["ValueMinimum"];
$ValueMaximum = $_REQUEST["ValueMaximum"];
$BetShopId = $_REQUEST["BetShopId"];

$State = $_REQUEST["State"];
if ($State != "A" && $State != "I") {
    $State = "";
}

/* establece condiciones para procesar filas de datos solicitadas. */
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


/* crea reglas de filtrado basadas en una fecha proporcionada. */
    $rules = [];

    if ($FromDateLocal != "") {
        //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
        array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    }

/* agrega reglas a un arreglo basado en condiciones de fecha y sistema de pago. */
if ($ToDateLocal != "") {
        //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
        array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
    }


    if ($PaymentSystemId != "") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
    }

/* Agrega condiciones a un arreglo basado en las variables $CashDeskId y $ClientId. */
if ($CashDeskId != "") {
        array_push($rules, array("field" => "punto_venta.usuario_id", "data" => "$CashDeskId", "op" => "eq"));
    }

    if ($ClientId != "") {
        array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }

/* Añade reglas de filtrado basadas en valores de transacción si están definidos. */
if ($AmountFrom != "") {
        array_push($rules, array("field" => "transaccion_producto.valor", "data" => "$AmountFrom", "op" => "ge"));
    }
    if ($AmountTo != "") {
        array_push($rules, array("field" => "transaccion_producto.valor", "data" => "$AmountTo", "op" => "le"));
    }

/* Añade reglas de validación para valores mínimo y máximo en una transacción. */
    if ($ValueMinimum != "") {
        array_push($rules, array("field" => "transaccion_producto.valor", "data" => "$ValueMinimum", "op" => "ge"));
    }
    if ($ValueMaximum != "") {
        array_push($rules, array("field" => "transaccion_producto.valor", "data" => "$ValueMaximum", "op" => "le"));
    }


/* Añade reglas a un arreglo si las variables no están vacías. */
    if ($CurrencyId != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }
    if ($ExternalId != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
    }
    
/* Agrega reglas a un array si las condiciones de Id y CountrySelect se cumplen. */
    if ($Id != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
    }
    if ($CountrySelect != '') {
        //array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }

    
/* Condiciones para agregar reglas basadas en el proveedor y perfil de usuario. */
    if ($ProviderId != '') {
        array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
    }


    if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }

    
/* agrega reglas según el perfil del usuario en sesión. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }

    if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }

    /* Añade reglas de filtrado según el estado y mandante del usuario. */
    if ($State != "") {
        array_push($rules, array("field" => "transaccion_producto.estado", "data" => "$State", "op" => "eq"));
    }


    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "transaccion_producto.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    }else {
    /* Añade una regla si hay un valor válido en "mandanteLista". */

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "transaccion_producto.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    /* Se agregan reglas para filtrar productos y usuarios según condiciones específicas. */
    array_push($rules, array("field" => "producto.externo_id", "data" => "'CUPONESGT','PAGOTICO','MERCADOONL'", "op" => "in"));

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

    /* Condiciona reglas según el perfil del usuario en sesión: CAJERO o PUNTOVENTA. */
    if ($_SESSION["win_perfil2"] == "CAJERO") {

        array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }

    /* Se añaden reglas de filtrado basadas en el ID del jugador y la IP. */
    if ($PlayerId != "") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
    }

    if ($Ip != "") {
        array_push($rules, array("field" => "usuario_recarga.dir_ip", "data" => "$Ip", "op" => "cn"));

    }

    /* Configura agrupamiento y selección de datos según condiciones específicas de detalle. */
    $grouping = "";
    $select = "";
    if ($IsDetails == 1) {
        $MaxRows = 10000;
        $grouping = " transaccion_producto.mandante,DATE_FORMAT(transaccion_producto.fecha_crea,'%Y-%m-%d'),producto.producto_id ";
        $select = "usuario.mandante,usuario.pais_id,DATE_FORMAT(transaccion_producto.fecha_crea,'%Y-%m-%d') fecha_crea,SUM(transaccion_producto.valor) valoru,usuario.moneda,usuario.mandante,SUM(transaccion_producto.valor) valor,producto.descripcion,proveedor.descripcion ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));

    } else {
        $select = " usuario.pais_id,usuario.mandante,transaccion_producto.*,producto.*,proveedor.*,usuario.moneda,usuario.mandante ";

    }

    /* Se establece un filtro y se inicializan variables si están vacías. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    /* establece un límite de filas y codifica un filtro en JSON. */
    if ($MaxRows == "") {
        $MaxRows = 5;
    }

    $json = json_encode($filtro);

    $order = "transaccion_producto.transproducto_id";

    /* Configura el tipo de ordenamiento basado en solicitudes de usuario para clientes. */
    $orderType = "desc";

    if ($_REQUEST["sort"] != "") {

        if ($_REQUEST["sort"]["ClientId"] != "") {
            $order = "usuario.usuario_id";
            $orderType = ($_REQUEST["sort"]["ClientId"] == "asc") ? "asc" : "desc";

        }
    }


    /* Se crea un objeto y se obtienen transacciones personalizadas en formato JSON. */
    $TransaccionProducto= new TransaccionProducto();
    $transacciones = $TransaccionProducto->getTransaccionesCustom2($select, "", "", $SkeepRows, $MaxRows, $json, true,$grouping);

    $transacciones = json_decode($transacciones);

    $final = [];

    /* Inicializa la variable $totalm con un valor de cero en PHP. */
    $totalm = 0;
    foreach ($transacciones->data as $key => $value) {

        /* Suma un valor a totalm si IsDetails es igual a 1. */
        $array = [];
        if ($IsDetails == 1) {
            $totalm = $totalm + $value->{".valoru"};


        } else {
            /* Suma el valor de transacciones al total acumulado si se cumple una condición. */

            $totalm = $totalm + $value->{"transaccion_producto.valor"};

        }
        if ($value->{"producto.descripcion"} == "") {

            /* Asigna un proveedor basado en el idioma y obtiene un ID de transacción. */
            $array["Provider"] = "Punto Venta".'-'.$value->{"transaccion_producto.mandante"};

            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Provider"] = "Betshop".'-'.$value->{"transaccion_producto.mandante"};
            }

            $array["Id"] = $value->{"transaccion_producto.transproducto_id"};

            /* Asigna valores a un array según condiciones de transacciones y detalles de usuario. */
            $array["ClientId"] = $value->{"transaccion_producto.usuario_id"};
            $array["UserName"] = $value->{"usuario_punto.login"};
            $array["CreatedLocal"] = $value->{"transaccion_producto.fecha_crea"};
            $array["ModifiedLocal"] = $value->{"transaccion_producto.fecha_crea"};

            if ($IsDetails == 1) {
                $array["Amount"] = $value->{".valoru"};
                $array["CreatedLocal"] = $value->{"transaccion_producto.fecha_crea"};
                $array["ExternalId"] = "";

            } else {
                /* Asignación de valor a una clave en un array si la condición no se cumple. */

                $array["Amount"] = $value->{"transaccion_producto.valor"};


            }

            /* Asigna nombres de sistema de pago según el idioma de la sesión. */
            $array["PaymentSystemName"] = "Efectivo - P.V." . $value->{"usuario_punto.nombre"};

            if(strtolower($_SESSION["idioma"])=="en"){
                $array["PaymentSystemName"] = "Cash - P.V." . $value->{"usuario_punto.nombre"};
            }

            $array["TypeName"] = "Payment";


            /* Código PHP que asigna valores a un arreglo basado en propiedades de un objeto. */
            $array["CurrencyId"] = 'CRC'.'-'.$value->{"transaccion_producto.mandante"};
            $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
            $array["State"] = $value->{"usuario_recarga.estado"};
            $array["Note"] = "T";
            $array["ExternalId"] = "";

            $array["Partner"] = $value->{"transaccion_producto.mandante"};

            /* asigna país y su icono a un arreglo usando datos de un objeto. */
            $array["Country"] = $value->{"pais.pais_nom"}.'-'.$value->{"transaccion_producto.mandante"};
            $array["CountryIcon"] = strtolower($value->{"pais.iso"});


        } else {

            /* Asignación de valores de un objeto a un array asociativo en PHP. */
            $array["Provider"] = $value->{"proveedor.descripcion"}.'-'.$value->{"transaccion_producto.mandante"};

            $array["Id"] = $value->{"transaccion_producto.transproducto_id"};
            $array["ClientId"] = $value->{"transaccion_producto.usuario_id"};
            $array["CreatedLocal"] = $value->{"transaccion_producto.fecha_crea"};
            $array["ModifiedLocal"] = $value->{"transaccion_producto.fecha_modif"};

            /* Asigna valores a un array si $IsDetails es verdadero. */
            if ($IsDetails == 1) {
                $array["Amount"] = $value->{".valoru"};
                $array["ExternalId"] = "";
                $array["CreatedLocal"] = $value->{".fecha_crea"};

            } else {
                /* asigna valores a un array basado en propiedades de un objeto. */

                $array["Amount"] = $value->{"transaccion_producto.valor"};
                $array["ExternalId"] = $value->{"transaccion_producto.externo_id"};


            }


            /* Asigna valores a un arreglo sobre sistema de pagos y transacciones. */
            $array["PaymentSystemName"] = $value->{"producto.descripcion"}.'-'.$value->{"transaccion_producto.mandante"};
            $array["TypeName"] = "Payment";

            $array["CurrencyId"] = 'CRC'.'-'.$value->{"transaccion_producto.mandante"};
            $array["CashDeskId"] = 0;
            $array["Ip"] = '';

            /* asigna valores de un objeto a un arreglo asociativo en PHP. */
            $array["State"] = $value->{"transaccion_producto.estado_producto"};
            $array["Note"] = "";


            $array["Partner"] = $value->{"transaccion_producto.mandante"};
            $array["Country"] = $value->{"pais.pais_nom"}.'-'.$value->{"transaccion_producto.mandante"};

            /* Convierte el valor del campo "pais.iso" a minúsculas y lo asigna a "CountryIcon". */
            $array["CountryIcon"] = strtolower($value->{"pais.iso"});

        }
        
        /* Añade elementos del array al final de otro array en PHP. */
        array_push($final, $array);
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);

    
    /* asigna datos a la variable de respuesta según una condición. */
    if ($IsDetails == 1) {
        $response["pos"] = 0;
        $response["data"] = $final;

    } else {
        $response["pos"] = $SkeepRows;
        $response["total_count"] = $transacciones->count[0]->{".count"};
        $response["data"] = $final;

    }


} else {
    /* establece una respuesta vacía y sin errores en condiciones específicas. */

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
