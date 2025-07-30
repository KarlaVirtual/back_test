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
 * Obtener el resumen de las comisiones de los usuarios
 * obtiene el resumen de las comisiones de los usuarios. Filtra las transacciones según varios criterios,
 * como fechas, sistema de pago, caja, cliente, monto, moneda, identificador externo, detalles, país, y mandante.
 *
 * @param string $dateFrom : Fecha de inicio para el resumen de comisiones.
 * @param string $dateTo : Fecha de fin para el resumen de comisiones.
 * @param int $PaymentSystemId : Identificador del sistema de pago.
 * @param int $CashDeskId : Identificador de la caja.
 * @param int $ClientId : Identificador del cliente.
 * @param float $AmountFrom : Monto mínimo para el resumen de comisiones.
 * @param float $AmountTo : Monto máximo para el resumen de comisiones.
 * @param int $CurrencyId : Identificador de la moneda.
 * @param string $ExternalId : Identificador externo.
 * @param int $Id : Identificador del resumen de comisiones.
 * @param bool $IsDetails : Indicador para obtener información detallada.
 * @param int $CountrySelect : Identificador del país seleccionado.
 * @param int $MaxRows : Número máximo de filas a devolver.
 * @param int $OrderedItem : Ítem ordenado.
 * @param int $SkeepRows : Número de filas a omitir en la consulta.
 * @param int $Type : Tipo de resumen.
 * @param int $OnlyBetShop : Indicador para obtener solo datos de tiendas de apuestas.
 * @param int $BetShopId : Identificador de la tienda de apuestas.
 * @param int $GroupedOnlyAffiliateUser : Indicador para agrupar solo usuarios afiliados.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
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
 * @throws Exception Permiso denegado
 * @throws Exception Inusual Detectado
 */

/* crea un objeto y obtiene parámetros JSON de una solicitud HTTP. */
$UsucomisionResumen = new UsucomisionResumen();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ToCreatedDateLocal;


/* Se asigna una fecha límite basada en la entrada del usuario en formato específico. */
if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
}


$FromDateLocal = $params->FromCreatedDateLocal;


/* Crea una fecha a partir de una entrada y establece un ID del sistema de pago. */
if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}


$PaymentSystemId = $params->PaymentSystemId;

/* Asignación de parámetros relacionados con transacciones a variables específicas en PHP. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* asigna valores de parámetros y fuerza la variable de detalles a verdadero. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;

//Fijamos para obtener siempre detalles
$IsDetails = true;

$FromId = $_REQUEST["FromId"];

/* captura datos de una solicitud HTTP en variables PHP. */
$PlayerId = $_REQUEST["PlayerId"];
$Ip = $_REQUEST["Ip"];
$IsDetails = $_REQUEST["IsDetails"];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$Type = $_REQUEST["Type"];
$OnlyBetShop = $_REQUEST["OnlyBetShop"];

/* procesa solicitudes HTTP y obtiene parámetros relacionados con apuestas. */
$BetShopId = $_REQUEST["BetShopId"];

$GroupedOnlyAffiliateUser = $_REQUEST["GroupedOnlyAffiliateUser"];
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

/* establece condiciones para continuar basadas en variables de filas máximas y saltadas. */
$seguir = true;

if ($MaxRows == "") {
    $seguir = false;
}

if ($SkeepRows == "") {
    $seguir = false;
}

if ($seguir) {


    /* Se crea una regla de filtro basada en la fecha de inicio proporcionada. */
    $rules = [];

    if ($FromDateLocal != "") {
        //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
        array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    }

    /* Condicionalmente agrega reglas a un array basado en fechas y un ID de cliente. */
    if ($ToDateLocal != "") {
        //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
        array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
    }


    if ($ClientId != "") {
        array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }


    /* Añade reglas de filtrado basado en comisiones si se proporcionan montos. */
    if ($AmountFrom != "") {
        array_push($rules, array("field" => "usucomision_resumen.comision", "data" => "$AmountFrom", "op" => "ge"));
    }
    if ($AmountTo != "") {
        array_push($rules, array("field" => "usucomision_resumen.comision", "data" => "$AmountTo", "op" => "le"));
    }


    /* Añade reglas de validación basadas en moneda y país si están definidas. */
    if ($CurrencyId != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }

    if ($CountrySelect != '') {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }


    /* Agrega reglas de filtro según condiciones específicas relacionadas con usuarios y perfiles. */
    array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    if ($OnlyBetShop == '1') {
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

    }

    /* Condiciona reglas basadas en el ID de la casa de apuestas y el país del usuario. */
    if ($BetShopId != '') {
        array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $BetShopId, "op" => "eq"));

    }


    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        // array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }

    // Si el usuario esta condicionado por el mandante y no es de Global

    /* Condiciona reglas para acceder a usuarios según la sesión y valores específicos. */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    // Inactivamos reportes para el país Colombia

    /* Se añaden reglas a un array según el perfil de usuario en sesión. */
    array_push($rules, array("field" => "usuarioref.pais_id", "data" => "1", "op" => "ne"));


    if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
        array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
    }


    /* Verifica el perfil de usuario y agrega reglas de filtrado según su tipo. */
    if ($FromId != "") {

        $UsuarioPerfil = new UsuarioPerfil($FromId, "");

        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => "$FromId", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => "$FromId", "op" => "eq"));
        }
        //array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$FromIdGetBetHistory", "op" => "eq"));
    }


    /* Condicional que agrega una regla si $PlayerId no está vacío y establece parámetros. */
    if ($PlayerId != "") {
        array_push($rules, array("field" => "usucomision_resumen.usuarioref_id", "data" => "$PlayerId", "op" => "eq"));
    }

    $MaxRows = 1000000;

    $grouping = "";

    /* Condicionalmente genera una consulta SQL para obtener detalles sobre recargas y transacciones. */
    $select = "";
    if ($IsDetails == 1) {
        $MaxRows = 1000000;
        $grouping = " DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d'),usuario_recarga.puntoventa_id,producto.producto_id ";
        $select = "usuario.login,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') fecha_crea,SUM(usuario_recarga.valor) valoru,usuario.moneda,SUM(transaccion_producto.valor) valor,producto.descripcion,proveedor.descripcion ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));
        array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));


        if ($GroupedOnlyAffiliateUser == '1') {
            $grouping = "usuario_recarga.puntoventa_id,producto.producto_id ";

        }

    } else {
        /* define consultas SQL para seleccionar y agrupar datos de comisiones. */

        $select = "usucomision_resumen.valor1,usucomision_resumen.valor2,usucomision_resumen.valor3,usucomision_resumen.estado, usucomision_resumen.usuario_id,usucomision_resumen.usuarioref_id,DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m') fecha,SUM(usucomision_resumen.valor) valor,SUM(usucomision_resumen.comision) comision,SUM(usucomision_resumen.valor_pagado) valor_pagado,clasificador.*,usuarioref.moneda ";
        $grouping = "usucomision_resumen.tipo,DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m'),usucomision_resumen.usuarioref_id,usucomision_resumen.usuario_id";
        $select = "usucomision_resumen.valor1,usucomision_resumen.valor2,usucomision_resumen.valor3,usuario.login,usucomision_resumen.usucomresumen_id,usucomision_resumen.estado, usucomision_resumen.usuario_id,usucomision_resumen.usuarioref_id,usucomision_resumen.fecha_crea,usucomision_resumen.valor valor,usucomision_resumen.comision comision,usucomision_resumen.valor_pagado valor_pagado,clasificador.*,usuarioref.moneda ";
        $grouping = "usucomision_resumen.usucomresumen_id";


        if ($Type == "1") {
            $select = " usucomision_resumen.*,clasificador.*,usuarioref.moneda ";
            $grouping = "usuario.login,usucomision_resumen.usucomresumen_id";

        }
    }


    /* establece filtros y valores predeterminados para manejar datos y ordenamientos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece un valor predeterminado y obtiene un resumen de comisiones. */
    if ($MaxRows == "") {
        $MaxRows = 5;
    }

    $json = json_encode($filtro);

    $transacciones = $UsucomisionResumen->getUsucomisionResumenCustom($select, "usucomision_resumen.usucomresumen_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);


    /* Se decodifica un JSON y se inicializan un arreglo y una variable total. */
    $transacciones = json_decode($transacciones);

    $final = [];
    $totalm = 0;
    foreach ($transacciones->data as $key => $value) {

        /* Se inicializa un arreglo vacío en PHP para almacenar valores posteriormente. */
        $array = [];

        if ($Type == "1") {

            /* Se asignan valores de un objeto a un array asociativo en PHP. */
            $array["Id"] = $value->{"usucomision_resumen.usucomresumen_id"};
            $array["UserId"] = $value->{"usucomision_resumen.usuario_id"};
            $array["NameAffiliate"] = $value->{"clasificador.clasificador_id"} . '-' . $value->{"usuarioref.moneda"} . ' - ' . $value->{"usuario.login"};
            $array["ClientId"] = $value->{"usucomision_resumen.usuarioref_id"};
            $array["UserName"] = $value->{"usucomision_resumen.usuarioref_id"};
            $array["CreatedLocal"] = $value->{"usucomision_resumen.fecha_crea"};

            /* Asigna valores a un arreglo basándose en condiciones específicas del objeto $value. */
            $array["ModifiedLocal"] = $value->{"usucomision_resumen.fecha_crea"};

            if ($IsDetails == 1) {
                $array["Amount"] = $value->{".valoru"};
                $array["CreatedLocal"] = $value->{".fecha_crea"};
                $array["ExternalId"] = "";

            } else {
                /* Asigna valores a un array desde un objeto si no se cumple una condición. */

                $array["AmountBase"] = $value->{"usucomision_resumen.valor"};
                $array["Amount"] = $value->{"usucomision_resumen.comision"};
                $array["AmountPaid"] = $value->{"usucomision_resumen.valor_pagado"};


            }


            /* Asigna valores a un arreglo basado en condiciones y propiedades de un objeto. */
            if ($array["Amount"] == 0) {
                $array["AmountBase"] = 0;
            }


            $array["TypeName"] = $value->{"clasificador.descripcion"} . ' - ' . $value->{"usuarioref.moneda"};


            /* asigna valores de un objeto a un arreglo asociativo. */
            $array["CurrencyId"] = $value->{"usuarioref.moneda"};
            $array["CashDeskId"] = $value->{"usucomision_resumen.usuario_id"};
            $array["State"] = $value->{"usucomision_resumen.estado"};


            $array["Bets"] = $value->{"usucomision_resumen.valor1"};

            /* Asigna valores a un arreglo desde propiedades de un objeto. */
            $array["Winnings"] = $value->{"usucomision_resumen.valor2"};
            $array["Bonus"] = $value->{"usucomision_resumen.valor3"};

        } else {

            /* Asigna valores a un array desde un objeto utilizando propiedades específicas de datos. */
            $array["Id"] = $value->{'usucomision_resumen.usucomresumen_id'};
            $array["UserId"] = $value->{"usucomision_resumen.usuario_id"};
            $array["NameAffiliate"] = $value->{"clasificador.clasificador_id"} . '-' . $value->{"usuarioref.moneda"} . ' - ' . $value->{"usuario.login"};
            $array["ClientId"] = $value->{"usucomision_resumen.usuarioref_id"};
            $array["ClientRef"] = $value->{"usucomision_resumen.usuarioref_id"};
            $array["UserName"] = $value->{"usucomision_resumen.usuarioref_id"};

            /* asigna valores de un objeto a un array asociativo. */
            $array["CreatedLocal"] = $value->{"usucomision_resumen.fecha_crea"};
            $array["ModifiedLocal"] = $value->{"usucomision_resumen.fecha_modif"};

            $array["AmountBase"] = $value->{"usucomision_resumen.valor"};
            $array["Amount"] = $value->{"usucomision_resumen.comision"};
            $array["AmountPaid"] = $value->{"usucomision_resumen.valor_pagado"};


            /* verifica si el monto es cero y asigna valores de apuestas y ganancias. */
            if ($array["Amount"] == 0) {
                $array["AmountBase"] = 0;
            }

            $array["Bets"] = $value->{"usucomision_resumen.valor1"};
            $array["Winnings"] = $value->{"usucomision_resumen.valor2"};

            /* Se asignan valores a un array desde un objeto, incluyendo bonus y tipo de moneda. */
            $array["Bonus"] = $value->{"usucomision_resumen.valor3"};


            $array["TypeName"] = $value->{"clasificador.descripcion"} . ' - ' . $value->{"usuarioref.moneda"};

            $array["CurrencyId"] = $value->{"usuarioref.moneda"};

            /* Se asignan valores específicos a un array desde un objeto en PHP. */
            $array["CashDeskId"] = $value->{"usucomision_resumen.usuario_id"};
            $array["State"] = $value->{"usucomision_resumen.estado"};

        }


        /* Agrega el contenido de `$array` al final del array `$final`. */
        array_push($final, $array);
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    /* configura una respuesta basada en el valor de $IsDetails. */
    if ($IsDetails == 1) {
        $response["pos"] = 0;
        $response["data"] = $final;

    } else {
        $response["pos"] = $SkeepRows;
        $response["total_count"] = $transacciones->count[0]->{".count"};
        $response["data"] = $final;

    }


} else {
    /* inicializa la respuesta con datos vacíos y valores por defecto. */

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}