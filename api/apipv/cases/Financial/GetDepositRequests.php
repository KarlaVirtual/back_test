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
 * Obtiene las solicitudes de depósito con paginación
 *
 * Este endpoint permite obtener un listado paginado de solicitudes de depósito
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
 * 
 * @return array {
 *   "HasError": boolean,      // Indica si hubo errores en el proceso
 *   "AlertType": string,      // Tipo de alerta (success, error, warning)
 *   "AlertMessage": string,   // Mensaje descriptivo del resultado
 *   "Data": {
 *     "Transactions": array,  // Lista de transacciones
 *     "TotalRows": int,      // Total de registros
 *     "TotalAmount": float,  // Monto total
 *     "TotalPages": int,     // Total de páginas
 *     "CurrentPage": int     // Página actual
 *   }
 * }
 *
 * @throws Exception Si hay errores en la consulta a la base de datos
 *
 * @access public
 */



/* crea un objeto y obtiene datos JSON del cuerpo de la solicitud. */
$TransaccionProducto = new TransaccionProducto();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ToCreatedDateLocal;


/* Convierte una fecha de entrada en formato local y establece un límite de tiempo. */
if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
}


$FromDateLocal = $params->FromCreatedDateLocal;



/* procesa una fecha y extrae identificadores de sistema de pago y caja. */
if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}

$PaymentSystemId = $params->PaymentSystemId;
$CashDeskId = $params->CashDeskId;

/* asigna valores de parámetros a variables en un script. */
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;
$Id = $params->Id;

/* asigna siempre verdadero a la variable $IsDetails y obtiene FromId de la solicitud. */
$IsDetails = ($params->IsDetails == true) ? true : false;

//Fijamos para obtener siempre detalles
$IsDetails = true;

$FromId = $_REQUEST["FromId"];

/* recoge datos de solicitud web y valida ciertos parámetros específicos. */
$PlayerId = $_REQUEST["PlayerId"];
$Ip = $_REQUEST["Ip"];
$IsDetails = $_REQUEST["IsDetails"];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$PaymentSystemId = $_REQUEST["PaymentSystemId"];
$FinalId = $_REQUEST["FinalId"];


/* procesa solicitudes HTTP para obtener parámetros específicos como ExternalId e Id. */
$ExternalId = $_REQUEST["ExternalId"];
$Id = $_REQUEST["Id"];
$State = $_REQUEST["State"];;

$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;

/* determina si continuar según parámetros de solicitud y filas máximas definidas. */
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$seguir = true;

if ($MaxRows == "") {
    $seguir = false;
}


/* verifica condiciones para seguir procesando datos según variables establecidas. */
if ($SkeepRows == "") {
    $seguir = false;
}


if ($ExternalId != "" || $Id != "") {
    $FromDateLocal='';
    $ToDateLocal='';

}

if ($seguir) {


    /* Se crea una regla para filtrar transacciones por fecha si se proporciona. */
    $rules = [];

    if ($FromDateLocal != "") {
        //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
        array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    }
    
    /* agrega reglas condicionales basadas en variables para filtrar datos. */
    if ($ToDateLocal != "") {
        //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
        array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
    }


    if ($PaymentSystemId != "") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
    }

    
    /* Filtra reglas de búsqueda según IDs de caja y cliente si no están vacíos. */
    if ($CashDeskId != "") {
        array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
    }
    if ($ClientId != "") {
        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }

    
    /* Agrega reglas de filtrado basadas en los valores de $AmountFrom y $AmountTo. */
    if ($AmountFrom != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
    }
    if ($AmountTo != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
    }

    
    /* Agrega reglas a un arreglo basadas en variables no vacías. */
    if ($CurrencyId != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }
    if ($ExternalId != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
    }
    if ($State != "") {
        switch ($State){
            case 'R':
                /* Código agrega una regla para filtrar productos con estado 'R'. */

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "R", "op" => "eq"));

                break;
            case 'EX':
                /* Agrega una regla para filtrar por estado igual a "EX" en transacciones. */

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "EX", "op" => "eq"));

                break;
            case 'E':
                /* Agrega una regla para filtrar productos con estado 'E' en una transacción. */

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "E", "op" => "eq"));

                break;
            case 'A':
                /* Agrega una regla para validar que el estado del producto sea igual a "A". */

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));

                break;
            case 'I':
                /* Agrega una regla de comparación para el estado del producto en una transacción. */

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "II", "op" => "eq"));

                break;
            case 'C':
                /* Agrega una regla que verifica si el estado del producto es "C". */

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "C", "op" => "eq"));

                break;
        }
    }
    
    /* Agrega reglas a un array si las condiciones de identificación y país son válidas. */
    if ($Id != "") {
        array_push($rules, array("field" => "transaccion_producto.transproducto_id", "data" => "$Id", "op" => "eq"));
    }
    if ($CountrySelect != '') {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }


    
    /* Verifica el perfil del usuario y agrega reglas según su tipo. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }

    if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }

    
    /* verifica condiciones para agregar reglas basadas en sesión de usuario. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }


    
    /* Agrega reglas para filtrar transacciones según el 'FinalId' y estado del producto. */
    if ($FinalId != "") {
        array_push($rules, array("field" => "transaccion_producto.final_id", "data" => "$FinalId", "op" => "eq"));
        if($FinalId == "0"){
            array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));

        }
    }

    // Si el usuario esta condicionado por el mandante y no es de Global
    
    /* gestiona reglas de acceso según la sesión del usuario. */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    }else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }
    
    /* Añade reglas de filtrado para usuarios y pagos según condiciones específicas en PHP. */
    array_push($rules, array("field" => "proveedor.tipo", "data" => "PAYMENT", "op" => "eq"));


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


    
    /* Condiciona la adición de reglas basadas en el ID del jugador y IP. */
    if ($PlayerId != "") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
    }

    if ($Ip != "") {
        array_push($rules, array("field" => "usuario_recarga.dir_ip", "data" => "$Ip", "op" => "cn"));

    }


    /* inicia variables y define un filtro para una consulta. */
    $grouping = "";
    $select = "";
    // $select = " usuario_punto.login,usuario_punto.nombre,transaccion_producto.*,producto.*,proveedor.*,usuario.moneda,usuario_recarga.* ";



    $filtro = array("rules" => $rules, "groupOp" => "AND");

    
    /* Asigna valores predeterminados a $SkeepRows y $OrderedItem si están vacíos. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    
    /* Asignar valor predeterminado a $MaxRows y codificar $filtro en JSON. */
    if ($MaxRows == "") {
        $MaxRows = 5;
    }


    $json = json_encode($filtro);


    /* obtiene y decodifica transacciones personalizadas en formato JSON. */
    $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,proveedor.*,usuario.moneda,usuario.nombre, usuario_banco.cuenta, usuario_banco.tipo_cuenta ", "transaccion_producto.transproducto_id", "desc", $SkeepRows, $MaxRows, $json, true);


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

            /* Asigna un proveedor dependiendo del idioma de la sesión y un identificador de recarga. */
            $array["Provider"] = "Punto Venta";

            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Provider"] = "Betshop";
            }

            $array["Id"] = $value->{"usuario_recarga.recarga_id"};

            /* asigna valores a un arreglo basado en datos de un objeto. */
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
                /* Asigna el valor de "usuario_recarga.valor" a "Amount" en un array. */

                $array["Amount"] = $value->{"usuario_recarga.valor"};


            }

            /* asigna un nombre de sistema de pago según el idioma de sesión. */
            $array["PaymentSystemName"] = "Efectivo - P.V." . $value->{"usuario_punto.nombre"};

            if(strtolower($_SESSION["idioma"])=="en"){
                $array["PaymentSystemName"] = "Cash - P.V." . $value->{"usuario_punto.nombre"};
            }

            $array["TypeName"] = "Payment";


            /* Asignación de valores de un objeto a un array asociativo en PHP. */
            $array["FinalId"] = $value->{"transaccion_producto.final_id"};


            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
            $array["State"] = "A";

            /* Se asignan valores a un arreglo asociativo en PHP: "Note" y "ExternalId". */
            $array["Note"] = "T";
            $array["ExternalId"] = "";

        } else {

            /* Se extraen valores de un objeto y se almacenan en un array asociativo. */
            $array["Provider"] = $value->{"proveedor.descripcion"};

            $array["Id"] = $value->{"transaccion_producto.transproducto_id"};
            $array["Tax"] = $value->{"transaccion_producto.impuesto"};
            $array["ClientId"] = $value->{"transaccion_producto.usuario_id"};
            $array["CreatedLocal"] = $value->{"transaccion_producto.fecha_crea"};
            $array["DigitalBankAccount"] = $value->{"usuario_banco.tipo_cuenta"} == "Digital" ? $value->{"usuario_banco.cuenta"} : $array["ClientId"];
            /* asigna valores a un array dependiendo si se cumplen ciertas condiciones. */
            $array["ModifiedLocal"] = $value->{"transaccion_producto.fecha_modif"};

            if ($IsDetails == 1) {
                $array["Amount"] = $value->{".valoru"};
                $array["ExternalId"] = "";
                $array["CreatedLocal"] = $value->{".fecha_crea"};

            } else {
                /* asigna valores de una transacción a un arreglo en PHP. */


                $array["Amount"] = $value->{"transaccion_producto.valor"};
                $array["ExternalId"] = $value->{"transaccion_producto.externo_id"};


            }


            /* Asigna valores de un objeto a un array para procesar información de pagos. */
            $array["PaymentSystemName"] = $value->{"producto.descripcion"};
            $array["TypeName"] = "Payment";

            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
            $array["Ip"] = $value->{"usuario_recarga.dir_ip"};

            /* Asigna estado y final_id a un array, modificando estado bajo ciertas condiciones. */
            $array["State"] = $value->{"transaccion_producto.estado_producto"};
            $array["Note"] = "";

            $array["FinalId"] = $value->{"transaccion_producto.final_id"};

            if($array["FinalId"] =='0' && $array["State"] == 'A'){
                $array["State"]='P';
            }
        }
        
        /* Añade elementos de un array a otro en PHP. */
        array_push($final, $array);
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);

    
    /* condicional asigna valores a la respuesta dependiendo del estado de $IsDetails. */
    if ($IsDetails == 1) {
        $response["pos"] = 0;
        $response["data"] = $final;

    } else {
        $response["pos"] = $SkeepRows;
        $response["total_count"] = $transacciones->count[0]->{".count"};
        $response["data"] = $final;

    }


} else {
    /* Estructura de respuesta JSON con datos y conteo inicial, sin errores. */

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
