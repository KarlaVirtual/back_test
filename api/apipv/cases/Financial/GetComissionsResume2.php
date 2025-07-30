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
use Backend\dto\UsucomisionusuarioResumen;
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
use Backend\mysql\UsucomisionusuarioResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
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
 * @param string $MyCommission : Descripción: Tipo de comisión.
 * @param string $ComissionsPayment : Descripción: Pago de comisiones.
 * @param string $TypeUser : Descripción: Tipo de usuario.
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
 *
 */
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


/* Se crea un objeto y se obtienen parámetros de entrada en formato JSON. */
$UsucomisionusuarioResumen = new UsucomisionusuarioResumen();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ToCreatedDateLocal;


/* procesa fechas, ajustando el formato y la zona horaria según el input. */
if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
}


$FromDateLocal = $params->FromCreatedDateLocal;


/* procesa una fecha desde una solicitud y asigna un ID de sistema de pago. */
if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}


$PaymentSystemId = $params->PaymentSystemId;

/* asigna valores de parámetros a variables relacionadas con transacciones. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* asigna y asegura que $IsDetails siempre sea verdadero, obteniendo $FromId de una solicitud. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;

//Fijamos para obtener siempre detalles
$IsDetails = true;

$FromId = $_REQUEST["FromId"];

/* Código PHP que captura datos de una solicitud HTTP. */
$PlayerId = $_REQUEST["PlayerId"];
$Ip = $_REQUEST["Ip"];
$IsDetails = $_REQUEST["IsDetails"];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$Type = $_REQUEST["Type"];
$TypeUser = $_REQUEST["TypeUser"];

/* recibe datos de una solicitud HTTP y los almacena en variables. */
$OnlyBetShop = $_REQUEST["OnlyBetShop"];
$BetShopId = $_REQUEST["BetShopId"];
$MyCommission = $_REQUEST["MyCommission"];
$ComissionsPayment = $_REQUEST["ComissionsPayment"];

$GroupedOnlyAffiliateUser = $_REQUEST["GroupedOnlyAffiliateUser"];

/* verifica parámetros de solicitudes y controla la continuación del proceso. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$seguir = true;

if ($MaxRows == "") {
    $seguir = false;
}


/* Verifica si $SkeepRows está vacío y establece $seguir como falso. */
if ($SkeepRows == "") {
    $seguir = false;
}

if ($seguir) {


    /* Código que crea reglas para filtrar fechas en un arreglo dependiendo de una condición. */
    $rules = [];

    if ($FromDateLocal != "") {
        //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
        array_push($rules, array("field" => "usucomisionusuario_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    }

    /* Se agregan reglas de filtro basadas en fechas y cliente en un arreglo. */
    if ($ToDateLocal != "") {
        //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
        array_push($rules, array("field" => "usucomisionusuario_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
    }


    if ($ClientId != "") {
        array_push($rules, array("field" => "usucomisionusuario_resumen.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }


    /* agrega reglas de filtro basadas en los montos especificados. */
    if ($AmountFrom != "") {
        array_push($rules, array("field" => "usucomisionusuario_resumen.comision", "data" => "$AmountFrom", "op" => "ge"));
    }
    if ($AmountTo != "") {
        array_push($rules, array("field" => "usucomisionusuario_resumen.comision", "data" => "$AmountTo", "op" => "le"));
    }


    /* Se agregan reglas de validación basadas en moneda y país seleccionados. */
    if ($CurrencyId != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }

    if ($CountrySelect != '') {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }


    /* agrega reglas de validación basadas en condiciones de sesión y comisiones. */
    if ($ComissionsPayment != "") {
        array_push($rules, array("field" => "usuario.pago_comisiones", "data" => "$ComissionsPayment", "op" => "eq"));
    }


    if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        if ($MyCommission != "1") {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        } else {
            array_push($rules, array("field" => "usucomisionusuario_resumen.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        }
    }


    /* ajusta reglas según la comisión y perfil del usuario en sesión. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        if ($MyCommission != "1") {
            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        } else {
            array_push($rules, array("field" => "usucomisionusuario_resumen.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        }

    }


    /* Verifica el perfil y ajusta las reglas de comisiones según condición. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        if ($MyCommission != "1") {
            array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        } else {
            array_push($rules, array("field" => "usucomisionusuario_resumen.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        }
    }


    /* Agrega reglas a un array según condiciones de BetShop y usuario. */
    if ($OnlyBetShop == '1') {
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

    }
    if ($BetShopId != '') {
        array_push($rules, array("field" => "usucomisionusuario_resumen.usuario_id", "data" => $BetShopId, "op" => "eq"));

    }

    /* asigna reglas de usuario según el tipo de usuario definido. */
    if ($TypeUser != '') {
        if ($TypeUser == '0') {
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO", "op" => "eq"));

        }
        if ($TypeUser == '1') {
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO2','CONCESIONARIO3'", "op" => "in"));

        }
        if ($TypeUser == '2') {
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

        }
        if ($TypeUser == '3') {
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));
        }
    }


    // Si el usuario esta condicionado por País

    /* verifica condiciones de sesión y agrega reglas para filtrado de datos. */
    if ($_SESSION['PaisCond'] == "S") {
        // array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }

    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Agrega una regla si "mandanteLista" no está vacío ni es "-1". */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    // Inactivamos reportes para el país Colombia

    /* Se añaden reglas de validación basadas en condiciones de sesión. */
    array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
        array_push($rules, array("field" => "usucomisionusuario_resumen.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
    }


    /* Se verifica un ID de usuario y se agregan reglas según su perfil. */
    if ($FromId != "") {

        $UsuarioPerfil = new UsuarioPerfil($FromId, "");

        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
            array_push($rules, array("field" => "usucomisionusuario_resumen.usuario_id", "data" => "$FromId", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "usucomisionusuario_resumen.usuario_id", "data" => "$FromId", "op" => "eq"));
        }
        //array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$FromIdGetBetHistory", "op" => "eq"));
    }


    /* Agrega una regla si $PlayerId no está vacío y define el máximo de filas. */
    if ($PlayerId != "") {
        array_push($rules, array("field" => "usucomisionusuario_resumen.usuarioref_id", "data" => "$PlayerId", "op" => "eq"));
    }

    $MaxRows = 1000000;

    $grouping = "";

    /* Selecciona datos de usuarios y transacciones según condiciones específicas y agrupaciones. */
    $select = "";
    if ($IsDetails == 1) {
        $MaxRows = 1000000;
        $grouping = " DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d'),usuario_recarga.puntoventa_id,producto.producto_id ";
        $select = "usuario.pago_comisiones,usuario.login,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') fecha_crea,SUM(usuario_recarga.valor) valoru,usuario.moneda,SUM(transaccion_producto.valor) valor,producto.descripcion,proveedor.descripcion ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));
        array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));


        if ($GroupedOnlyAffiliateUser == '1') {
            $grouping = "usuario_recarga.puntoventa_id,producto.producto_id ";

        }

    } else {
        /* selecciona y agrupa datos de comisiones de usuarios en base a condiciones. */

        $select = "usucomisionusuario_resumen.valor1,usucomisionusuario_resumen.valor2,usucomisionusuario_resumen.valor3,usucomisionusuario_resumen.estado, usucomisionusuario_resumen.usuario_id,usucomisionusuario_resumen.usuarioref_id,DATE_FORMAT(usucomisionusuario_resumen.fecha_crea,'%Y-%m') fecha,SUM(usucomisionusuario_resumen.valor) valor,SUM(usucomisionusuario_resumen.comision) comision,SUM(usucomisionusuario_resumen.valor_pagado) valor_pagado,clasificador.*,usuario.moneda ";
        $grouping = "usucomisionusuario_resumen.tipo,DATE_FORMAT(usucomisionusuario_resumen.fecha_crea,'%Y-%m'),usucomisionusuario_resumen.usuarioref_id,usucomisionusuario_resumen.usuario_id";
        $select = "usuario.pago_comisiones,usuario.usuario_id,usucomisionusuario_resumen.valor1,usucomisionusuario_resumen.valor2,usucomisionusuario_resumen.valor3,usuario.login,usucomisionusuario_resumen.usucomusuresumen_id,usucomisionusuario_resumen.estado, usucomisionusuario_resumen.usuario_id,usucomisionusuario_resumen.usuarioref_id,usucomisionusuario_resumen.fecha_crea,usucomisionusuario_resumen.valor valor,usucomisionusuario_resumen.comision comision,usucomisionusuario_resumen.valor_pagado valor_pagado,clasificador.*,usuario.moneda,concesionario.* ";
        $grouping = "usucomisionusuario_resumen.usucomusuresumen_id";


        if ($Type == "1") {
            $select = " usucomisionusuario_resumen.*,clasificador.*,usuario.moneda ";
            $grouping = "usuario.pago_comisiones,usuario.login,usucomisionusuario_resumen.usucomusuresumen_id";

        }
    }


    /* Configura un filtro y establece valores predeterminados para filas y elementos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Configura `$MaxRows` y obtiene un resumen de comisiones de usuarios en formato JSON. */
    if ($MaxRows == "") {
        $MaxRows = 5;
    }

    $json = json_encode($filtro);

    $transacciones = $UsucomisionusuarioResumen->getUsucomisionusuarioResumenCustom($select, "usucomisionusuario_resumen.usucomusuresumen_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);


    /* decodifica un JSON y inicializa un array y un contador. */
    $transacciones = json_decode($transacciones);

    $final = [];
    $totalm = 0;
    foreach ($transacciones->data as $key => $value) {

        /* Se inicializa un array vacío en PHP para almacenar elementos posteriormente. */
        $array = [];

        if ($Type == "1") {

            /* asigna valores a un array basado en propiedades de un objeto. */
            $array["Id"] = $value->{"usucomisionusuario_resumen.usucomusuresumen_id"};
            $array["UserId"] = $value->{"usucomisionusuario_resumen.usuario_id"};
            $array["NameAffiliate"] = $value->{"usuario.moneda"} . ' - ' . $value->{"usuario.usuario_id"} . ' - ' . $value->{"usuario.login"};
            $array["ClientId"] = $value->{"usucomisionusuario_resumen.usuario_id"};
            $array["UserName"] = $value->{"usucomisionusuario_resumen.usuarioref_id"};
            $array["CreatedLocal"] = $value->{"usucomisionusuario_resumen.fecha_crea"};

            /* asigna valores a un array, manejando un caso especial para "Agent1". */
            $array["ModifiedLocal"] = $value->{"usucomisionusuario_resumen.fecha_crea"};

            $array["Agent1"] = $value->{"concesionario.usupadre_id"};
            if ($array["Agent1"] == '0') {
                $array["Agent1"] = 'Directo';
            }

            /* asigna responsables 'Directo' si el ID es '0'. */
            $array["Agent2"] = $value->{"concesionario.usupadre2_id"};
            if ($array["Agent2"] == '0') {
                $array["Agent2"] = 'Directo';
            }
            $array["Agent3"] = $value->{"concesionario.usupadre3_id"};
            if ($array["Agent3"] == '0') {
                $array["Agent3"] = 'Directo';
            }

            /* Concatena valores de agentes y asigna detalles si se cumple una condición. */
            $array["NameAffiliate"] = $array["NameAffiliate"] . ' - ' . $array["Agent1"] . ' - ' . $array["Agent2"] . ' - ' . $array["Agent3"];
            if ($IsDetails == 1) {
                $array["Amount"] = $value->{".valoru"};
                $array["CreatedLocal"] = $value->{".fecha_crea"};
                $array["ExternalId"] = "";

            } else {
                /* asigna valores de un objeto a un array en PHP. */

                $array["AmountBase"] = $value->{"usucomisionusuario_resumen.valor"};
                $array["Amount"] = $value->{"usucomisionusuario_resumen.comision"};
                $array["AmountPaid"] = $value->{"usucomisionusuario_resumen.valor_pagado"};


            }


            /* asigna cero a "AmountBase" si "Amount" es cero y asigna "TypeName". */
            if ($array["Amount"] == 0) {
                $array["AmountBase"] = 0;
            }


            $array["TypeName"] = $value->{"clasificador.descripcion"};


            /* Asigna valores de un objeto a un array asociativo en PHP. */
            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["CashDeskId"] = $value->{"usucomisionusuario_resumen.usuario_id"};
            $array["State"] = $value->{"usucomisionusuario_resumen.estado"};


            $array["Bets"] = $value->{"usucomisionusuario_resumen.valor1"};

            /* asigna valores a un array y clasifica tipos según criterios específicos. */
            $array["Winnings"] = $value->{"usucomisionusuario_resumen.valor2"};
            $array["Bonus"] = $value->{"usucomisionusuario_resumen.valor3"};

            $array["TypeG"] = "Otros Tipos";
            $array["ComissionsPayment"] = $value->{"usuario.pago_comisiones"};


            if (in_array($value->{"clasificador.abreviado"}, array("SPORTNGRAFF", "SPORTPVIP", "SPORTPV", "BETSPORTPV", "SPORTAFF", "BETSPORTAFF", "WINSPORTAFF", "WINSPORTPV", "CASINOAFF", "CASINOPV", "CASINONGRAFF"))) {
                $array["TypeG"] = "Casino - Sportbook";

                $array["TypeName"] = '(SC#) ' . $array["TypeName"];
            }
        } else {

            /* Asigna valores a un arreglo asociativo usando datos de un objeto recibido. */
            $array["Id"] = $value->{'usucomisionusuario_resumen.usucomusuresumen_id'};
            $array["UserId"] = $value->{"usucomisionusuario_resumen.usuario_id"};
            $array["NameAffiliate"] = $value->{"usuario.moneda"} . ' - ' . $value->{"usuario.usuario_id"} . ' - ' . $value->{"usuario.login"};
            $array["ClientId"] = $value->{"usucomisionusuario_resumen.usuario_id"};
            $array["ClientRef"] = $value->{"usucomisionusuario_resumen.usuarioref_id"};
            $array["UserName"] = $value->{"usucomisionusuario_resumen.usuarioref_id"};

            /* asigna fechas y agentes a un array, modificando "Agent1" si es '0'. */
            $array["CreatedLocal"] = $value->{"usucomisionusuario_resumen.fecha_crea"};
            $array["ModifiedLocal"] = $value->{"usucomisionusuario_resumen.fecha_modif"};

            $array["Agent1"] = $value->{"concesionario.usupadre_id"};
            if ($array["Agent1"] == '0') {
                $array["Agent1"] = 'Directo';
            }

            /* Asigna identificadores a agentes y los reemplaza con 'Directo' si son '0'. */
            $array["Agent2"] = $value->{"concesionario.usupadre2_id"};
            if ($array["Agent2"] == '0') {
                $array["Agent2"] = 'Directo';
            }
            $array["Agent3"] = $value->{"concesionario.usupadre3_id"};
            if ($array["Agent3"] == '0') {
                $array["Agent3"] = 'Directo';
            }

            /* Concatena nombres de afiliados y asigna valores de comisiones a un array. */
            $array["NameAffiliate"] = $array["NameAffiliate"] . ' - ' . $array["Agent1"] . ' - ' . $array["Agent2"] . ' - ' . $array["Agent3"];

            $array["AmountBase"] = $value->{"usucomisionusuario_resumen.valor"};
            $array["Amount"] = $value->{"usucomisionusuario_resumen.comision"};
            $array["AmountPaid"] = $value->{"usucomisionusuario_resumen.valor_pagado"};

            if ($array["Amount"] == 0) {
                $array["AmountBase"] = 0;
            }


            /* asigna valores a un array desde un objeto en PHP. */
            $array["Bets"] = $value->{"usucomisionusuario_resumen.valor1"};
            $array["Winnings"] = $value->{"usucomisionusuario_resumen.valor2"};
            $array["Bonus"] = $value->{"usucomisionusuario_resumen.valor3"};


            $array["TypeName"] = $value->{"clasificador.descripcion"};


            /* Asigna un tipo basado en clasificador, ajustando el arreglo según condiciones específicas. */
            $array["TypeG"] = "Otros Tipos";

            if (in_array($value->{"clasificador.abreviado"}, array("SPORTNGRAFF", "SPORTPVIP", "SPORTPV", "BETSPORTPV", "SPORTAFF", "BETSPORTAFF", "WINSPORTAFF", "WINSPORTPV", "CASINOAFF", "CASINOPV", "CASINONGRAFF"))) {
                $array["TypeG"] = "Casino - Sportbook";
                $array["TypeName"] = '(SC#) ' . $array["TypeName"];
            }


            /* asigna valores de un objeto a un array asociativo en PHP. */
            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["CashDeskId"] = $value->{"usucomisionusuario_resumen.usuario_id"};
            $array["State"] = $value->{"usucomisionusuario_resumen.estado"};
            $array["ComissionsPayment"] = $value->{"usuario.pago_comisiones"};


        }


        /* Agrega el contenido de `$array` al final de `$final` utilizando `array_push`. */
        array_push($final, $array);
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    /* asigna valores a la respuesta según el estado de $IsDetails. */
    if ($IsDetails == 1) {
        $response["pos"] = 0;
        $response["data"] = $final;

    } else {
        $response["pos"] = $SkeepRows;
        $response["total_count"] = $transacciones->count[0]->{".count"};
        $response["data"] = $final;

    }


} else {
    /* inicializa una respuesta estructurada en caso de no haber errores. */

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
