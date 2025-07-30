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
 * Financial/GetComissionsResume
 *
 * Obtener el resumen de las comisiones de los usuarios
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
/**
 *
 * @param string $ToCreatedDateLocal : Descripción: Fecha de fin para el reporte de comisiones en hora local.
 * @param string $FromCreatedDateLocal : Descripción: Fecha de inicio para el reporte de comisiones en hora local.
 * @param int $PaymentSystemId : Descripción: Identificador del sistema de pago.
 * @param int $CashDeskId : Descripción: Identificador de la caja.
 * @param int $ClientId : Descripción: Identificador del cliente.
 * @param float $AmountFrom : Descripción: Monto mínimo para la comisión.
 * @param float $AmountTo : Descripción: Monto máximo para la comisión.
 * @param int $CurrencyId : Descripción: Identificador de la moneda.
 * @param string $ExternalId : Descripción: Identificador externo.
 * @param int $Id : Descripción: Identificador de la comisión.
 * @param bool $IsDetails : Descripción: Indicador para obtener información detallada.
 * @param int $FromId : Descripción: Identificador de la fuente.
 * @param int $PlayerId : Descripción: Identificador del jugador.
 * @param string $Ip : Descripción: Dirección IP.
 * @param int $CountrySelect : Descripción: Identificador del país seleccionado.
 * @param int $MaxRows : Descripción: Número máximo de filas a devolver.
 * @param int $OrderedItem : Descripción: Ítem ordenado.
 * @param int $SkeepRows : Descripción: Número de filas a omitir en la consulta.
 * @param int $Type : Descripción: Indicador para agrupar total o detallado.
 * @param int $OnlyBetShop : Descripción: Indicador para filtrar solo por tiendas de apuestas.
 * @param int $BetShopId : Descripción: Identificador de la tienda de apuestas.
 * @param int $GroupedOnlyAffiliateUser : Descripción: Indicador para agrupar solo por usuarios afiliados.
 *
 * @Description Este recurso permite obtener el resumen de las comisiones de los usuarios en el sistema, filtrando por varios criterios como fechas, sistema de pago, caja, cliente, monto, moneda, identificador externo, producto, tipo, fuente, jugador, IP y país.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en la operación.
 * - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 * - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 * - *ModelErrors* (array): Lista de errores de validación del modelo.
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos del resumen de comisiones.
 *
 * Objeto en caso de error:
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'danger';
 * $response['AlertMessage'] = 'Invalid';
 * $response['ModelErrors'] = [];
 */


/* Crea un objeto, obtiene y decodifica datos JSON de la entrada PHP. */
$UsucomisionResumen = new UsucomisionResumen();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ToCreatedDateLocal;


/* obtiene una fecha final local formateada a partir de una entrada del usuario. */
if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
}


$FromDateLocal = $params->FromCreatedDateLocal;


/* Convierte una fecha de entrada en formato específico considerando una zona horaria. */
if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}


$PaymentSystemId = $params->PaymentSystemId;

/* asigna valores de parámetros a variables para uso posterior. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* asigna valores de parámetros y siempre establece IsDetails en verdadero. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;

//Fijamos para obtener siempre detalles
$IsDetails = true;

$FromId = $_REQUEST["FromId"];

/* obtiene datos de entrada de una solicitud HTTP. */
$PlayerId = $_REQUEST["PlayerId"];
$Ip = $_REQUEST["Ip"];
$IsDetails = $_REQUEST["IsDetails"];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$Type = $_REQUEST["Type"];
$OnlyBetShop = $_REQUEST["OnlyBetShop"];

/* obtiene parámetros de entrada para gestionar una solicitud de apuestas. */
$BetShopId = $_REQUEST["BetShopId"];

$GroupedOnlyAffiliateUser = $_REQUEST["GroupedOnlyAffiliateUser"];
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

/* Verifica si MaxRows y SkeepRows están vacíos, ajustando la variable $seguir. */
$seguir = true;

if ($MaxRows == "") {
    $seguir = false;
}

if ($SkeepRows == "") {
    $seguir = false;
}

if ($seguir) {


    /* agrega una regla de filtrado si $FromDateLocal no está vacío. */
    $rules = [];

    if ($FromDateLocal != "") {
        //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
        array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    }

    /* Se añaden reglas de filtrado según fechas y IDs de cliente en un array. */
    if ($ToDateLocal != "") {
        //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
        array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
    }


    if ($ClientId != "") {
        array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }


    /* Agrega reglas de filtrado para comisiones basadas en rangos de montos. */
    if ($AmountFrom != "") {
        array_push($rules, array("field" => "usucomision_resumen.comision", "data" => "$AmountFrom", "op" => "ge"));
    }
    if ($AmountTo != "") {
        array_push($rules, array("field" => "usucomision_resumen.comision", "data" => "$AmountTo", "op" => "le"));
    }


    /* Agrega reglas de filtrado según la moneda y país seleccionados. */
    if ($CurrencyId != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }

    if ($CountrySelect != '') {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }


    /* Verifica si el perfil es "CONCESIONARIO" y agrega reglas a un array. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        if ($FromId != "") {
        } else {
            //array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        }
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
    }


    /* verifica condiciones de sesión y agrega reglas a un arreglo. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        if ($FromId != "") {
            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        } else {
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        }
    }


    /* Condicional que agrega reglas basadas en el perfil de sesión y el FromId. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        if ($FromId != "") {
            array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        } else {
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        }
    }


    /* Agrega reglas de filtro dependiendo de condiciones sobre usuario y BetShop. */
    if ($OnlyBetShop == '1') {
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

    }
    if ($BetShopId != '') {
        array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $BetShopId, "op" => "eq"));

    }


    // Si el usuario esta condicionado por País

    /* Condiciona reglas basadas en sesión para filtrar usuarios según país y mandante. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }

    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));

        if ($_SESSION['mandante'] == '8') {
            //array_push($rules, array("field" => "usucomision_resumen.comision", "data" => '0', "op" => "ge"));

        }

    } else {
        /* verifica una sesión y agrega reglas a un arreglo basado en condiciones. */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuarioref.pais_id", "data" => "1", "op" => "ne"));


    /* Se establece un conjunto de reglas basado en el perfil del usuario y condiciones. */
    if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
        array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
    }

    if ($FromId != "") {

        $UsuarioPerfil = new UsuarioPerfil($FromId, "");

        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => "$FromId", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => "$FromId", "op" => "eq"));
        }
        //array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$FromIdGetBetHistory", "op" => "eq"));
    }


    /* Agrega una regla al arreglo si PlayerId no está vacío. */
    if ($PlayerId != "") {
        array_push($rules, array("field" => "usucomision_resumen.usuarioref_id", "data" => "$PlayerId", "op" => "eq"));
    }

    $MaxRows = 1000000;

    $grouping = "";

    /* Configura una consulta SQL condicional para obtener información sobre transacciones de usuarios. */
    $select = "";
    if ($IsDetails == 1) {
        $MaxRows = 1000000;
        $grouping = " DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d'),usuario_recarga.puntoventa_id,producto.producto_id ";
        $select = "usuario.login,usuario.nombre,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') fecha_crea,SUM(usuario_recarga.valor) valoru,usuario.moneda,SUM(transaccion_producto.valor) valor,producto.descripcion,proveedor.descripcion,usuario.moneda ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));
        array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));


        if ($GroupedOnlyAffiliateUser == '1') {
            $grouping = "usuario_recarga.puntoventa_id,producto.producto_id ";

        }

    } else {
        /* configura consultas SQL para resumir datos de comisiones y usuarios. */

        $select = "usucomision_resumen.valor1,usucomision_resumen.valor2,usucomision_resumen.valor3,usucomision_resumen.estado, usucomision_resumen.usuario_id,usucomision_resumen.usuarioref_id,DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m') fecha,SUM(usucomision_resumen.valor) valor,SUM(usucomision_resumen.comision) comision,SUM(usucomision_resumen.valor_pagado) valor_pagado,clasificador.*,usuarioref.moneda,usuarioref.nombre ";
        $grouping = "usucomision_resumen.tipo,DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m'),usucomision_resumen.usuarioref_id,usucomision_resumen.usuario_id";
        $select = "usucomision_resumen.valor1,usucomision_resumen.valor2,usucomision_resumen.valor3,usuario.login,usuario.nombre,usucomision_resumen.usucomresumen_id,usucomision_resumen.estado, usucomision_resumen.usuario_id,usucomision_resumen.usuarioref_id,usucomision_resumen.fecha_crea,usucomision_resumen.valor valor,usucomision_resumen.comision comision,usucomision_resumen.valor_pagado valor_pagado,clasificador.*,usuarioref.moneda,usuarioref.nombre,usuario.moneda ";
        $grouping = "usucomision_resumen.usucomresumen_id";


        if ($Type == "1") {
            $select = " usucomision_resumen.*,clasificador.*,usuarioref.moneda,usuario.moneda ";
            $grouping = "usuario.login,usucomision_resumen.usucomresumen_id";

        }
    }


    /* Valida el perfil de usuario y asigna reglas según la región en sesión. */
    if ($_SESSION['regionperfil'] != "0" && $_SESSION['regionperfil'] != null) {
        if ($_SESSION["win_perfil"] != "CONCESIONARIO" && $_SESSION["win_perfil"] != "CONCESIONARIO2" && $_SESSION["win_perfil"] != "CONCESIONARIO3" && $_SESSION["win_perfil"] != "PUNTOVENTA") {

            array_push($rules, array("field" => "usuario_perfil.region", "data" => $_SESSION['regionperfil'], "op" => "eq"));
        }
    }


    /* establece filtros y valores predeterminados para la paginación de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece un límite de filas y obtiene un resumen de comisiones. */
    if ($MaxRows == "") {
        $MaxRows = 5;
    }

    $json = json_encode($filtro);

    $transacciones = $UsucomisionResumen->getUsucomisionResumenCustom($select, "usucomision_resumen.usucomresumen_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);


    /* decodifica un JSON y inicializa un arreglo y un total. */
    $transacciones = json_decode($transacciones);

    $final = [];
    $totalm = 0;
    foreach ($transacciones->data as $key => $value) {

        /* Se inicializa un arreglo vacío en PHP. */
        $array = [];

        if ($Type == "1") {

            /* Asigna valores a un array a partir de propiedades de un objeto. */
            $array["Id"] = $value->{"usucomision_resumen.usucomresumen_id"};
            $array["UserId"] = $value->{"usucomision_resumen.usuario_id"};
            $array["NameAffiliate"] = $value->{"clasificador.clasificador_id"} . '-' . $value->{"usuarioref.moneda"} . ' - ' . $value->{"usuario.login"};
            $array["NameAffiliate2"] = $value->{"usuario.nombre"};
            $array["ClientId"] = $value->{"usucomision_resumen.usuario_id"};
            $array["UserName"] = $value->{"usucomision_resumen.usuarioref_id"};

            /* Se asignan fechas y condiciones según el valor de $IsDetails en un array. */
            $array["CreatedLocal"] = $value->{"usucomision_resumen.fecha_crea"};
            $array["ModifiedLocal"] = $value->{"usucomision_resumen.fecha_crea"};

            if ($IsDetails == 1) {
                $array["Amount"] = $value->{".valoru"};
                $array["CreatedLocal"] = $value->{".fecha_crea"};
                $array["ExternalId"] = "";

            } else {
                /* asigna valores a un array desde un objeto PHP. */

                $array["AmountBase"] = $value->{"usucomision_resumen.valor"};
                $array["Amount"] = $value->{"usucomision_resumen.comision"};
                $array["AmountPaid"] = $value->{"usucomision_resumen.valor_pagado"};


            }


            /* establece valores en un array basado en condiciones y propiedades de un objeto. */
            if ($array["Amount"] == 0) {
                $array["AmountBase"] = 0;
            }


            $array["TypeName"] = $value->{"clasificador.descripcion"} . ' - ' . $value->{"usuarioref.moneda"};


            /* Asigna valores específicos a un array usando propiedades de un objeto. */
            $array["CurrencyId"] = $value->{"usuarioref.moneda"};
            $array["CashDeskId"] = $value->{"usucomision_resumen.usuario_id"};
            $array["State"] = $value->{"usucomision_resumen.estado"};


            $array["Bets"] = $value->{"usucomision_resumen.valor1"};

            /* Se asignan valores a un arreglo según condiciones específicas de clasificador. */
            $array["Winnings"] = $value->{"usucomision_resumen.valor2"};
            $array["Bonus"] = $value->{"usucomision_resumen.valor3"};

            $array["TypeG"] = "Otros Tipos";

            if (in_array($value->{"clasificador.abreviado"}, array("SPORTNGRAFF", "SPORTPVIP", "SPORTPV", "BETSPORTPV", "SPORTAFF", "BETSPORTAFF", "WINSPORTAFF", "WINSPORTPV", "CASINOAFF", "CASINOPV", "CASINONGRAFF"))) {
                $array["TypeG"] = "Casino - Sportbook";
            }


            /* Asigna valores a un array si "ClientRef" es cero o nulo. */
            if ($array["ClientRef"] == '0' || $array["ClientRef"] == null) {
                $array["CurrencyId"] = $value->{"usuario.moneda"};
                $array["TypeName"] = $value->{"clasificador.descripcion"} . ' - ' . $value->{"usuario.moneda"};
                $array["NameAffiliate"] = $value->{"clasificador.clasificador_id"} . '-' . $value->{"usuario.moneda"} . ' - ' . $value->{"usuario.login"};
            }
        } else {

            /* Código que asigna valores a un array a partir de un objeto. */
            $array["Id"] = $value->{'usucomision_resumen.usucomresumen_id'};
            $array["UserId"] = $value->{"usucomision_resumen.usuario_id"};
            $array["NameAffiliate"] = $value->{"clasificador.clasificador_id"} . '-' . $value->{"usuarioref.moneda"} . ' - ' . $value->{"usuario.login"};
            $array["NameAffiliate2"] = $value->{"usuario.nombre"};
            $array["ClientId"] = $value->{"usucomision_resumen.usuario_id"};
            $array["ClientRef"] = $value->{"usucomision_resumen.usuarioref_id"};

            /* asigna valores de un objeto a un arreglo asociativo en PHP. */
            $array["NameClientRef"] = $value->{"usuarioref.nombre"};
            $array["UserName"] = $value->{"usucomision_resumen.usuarioref_id"};

            $array["CreatedLocal"] = $value->{"usucomision_resumen.fecha_crea"};
            $array["ModifiedLocal"] = $value->{"usucomision_resumen.fecha_modif"};

            $array["AmountBase"] = $value->{"usucomision_resumen.valor"};

            /* Asignación de valores de comisión y pago a un array, con verificación de cero. */
            $array["Amount"] = $value->{"usucomision_resumen.comision"};
            $array["AmountPaid"] = $value->{"usucomision_resumen.valor_pagado"};

            if ($array["Amount"] == 0) {
                $array["AmountBase"] = 0;
            }


            /* Asigna valores de un objeto a un array usando claves específicas para cada dato. */
            $array["Bets"] = $value->{"usucomision_resumen.valor1"};
            $array["Winnings"] = $value->{"usucomision_resumen.valor2"};
            $array["Bonus"] = $value->{"usucomision_resumen.valor3"};


            $array["TypeName"] = $value->{"clasificador.descripcion"} . ' - ' . $value->{"usuarioref.moneda"};


            /* asigna tipos de juego y moneda a un arreglo basado en condiciones. */
            $array["TypeG"] = "Otros Tipos";

            if (in_array($value->{"clasificador.abreviado"}, array("SPORTNGRAFF", "SPORTPVIP", "SPORTPV", "BETSPORTPV", "SPORTAFF", "BETSPORTAFF", "WINSPORTAFF", "WINSPORTPV", "CASINOAFF", "CASINOPV", "CASINONGRAFF"))) {
                $array["TypeG"] = "Casino - Sportbook";
            }


            $array["CurrencyId"] = $value->{"usuarioref.moneda"};

            /* asigna valores a un arreglo basado en condiciones y datos específicos. */
            $array["CashDeskId"] = $value->{"usucomision_resumen.usuario_id"};
            $array["State"] = $value->{"usucomision_resumen.estado"};

            if ($array["ClientRef"] == '0' || $array["ClientRef"] == null) {
                $array["CurrencyId"] = $value->{"usuario.moneda"};
                $array["TypeName"] = $value->{"clasificador.descripcion"} . ' - ' . $value->{"usuario.moneda"};
                $array["NameAffiliate"] = $value->{"clasificador.clasificador_id"} . '-' . $value->{"usuario.moneda"} . ' - ' . $value->{"usuario.login"};
            }

        }


        /* Agrega elementos del array al final de otro array en PHP. */
        array_push($final, $array);
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    /* ajusta la respuesta según el valor de $IsDetails y $SkeepRows. */
    if ($IsDetails == 1) {
        $response["pos"] = 0;
        $response["data"] = $final;

    } else {
        $response["pos"] = $SkeepRows;
        $response["total_count"] = $transacciones->count[0]->{".count"};
        $response["data"] = $final;

    }


} else {
    /* define una respuesta vacía con posición y conteo inicializados. */

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
