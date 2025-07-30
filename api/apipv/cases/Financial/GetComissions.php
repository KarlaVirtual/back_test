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
 * Financial/GetComissions
 *
 * Obtener las comisiones de los usuarios
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
 * Este recurso permite obtener las comisiones de los usuarios en el sistema, filtrando por varios criterios como fechas, sistema de pago, caja, cliente, monto, moneda, identificador externo, producto, tipo, fuente, jugador, IP y país.
 *
 * @param string $ToCreatedDateLocal : Fecha de fin para el reporte de comisiones en hora local.
 * @param string $FromCreatedDateLocal : Fecha de inicio para el reporte de comisiones en hora local.
 * @param int $PaymentSystemId : Identificador del sistema de pago.
 * @param int $CashDeskId : Identificador de la caja.
 * @param int $ClientId : Identificador del cliente.
 * @param float $AmountFrom : Monto mínimo para la comisión.
 * @param float $AmountTo : Monto máximo para la comisión.
 * @param int $CurrencyId : Identificador de la moneda.
 * @param string $ExternalId : Identificador externo.
 * @param int $Id : Identificador de la comisión.
 * @param bool $IsDetails : Indicador para obtener información detallada.
 * @param string $Product : Tipo de producto.
 * @param string $Type : Tipo de comisión.
 * @param int $FromId : Identificador de la fuente.
 * @param int $PlayerId : Identificador del jugador.
 * @param string $Ip : Dirección IP.
 * @param int $CountrySelect : Identificador del país seleccionado.
 * @param int $MaxRows : Número máximo de filas a devolver.
 * @param int $OrderedItem : Ítem ordenado.
 * @param int $SkeepRows : Número de filas a omitir en la consulta.
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
 * - *data* (array): Datos de las comisiones.
 *
 * Objeto en caso de error:
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'danger';
 * $response['AlertMessage'] = 'Invalid';
 * $response['ModelErrors'] = [];
 *
 */


/* Se crea un objeto de tipo UsuarioComision y se decodifican parámetros JSON. */
$UsuarioComision = new UsuarioComision();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ToCreatedDateLocal;


/* Convierte una fecha proporcionada en un formato local a su última hora del día. */
if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
}


$FromDateLocal = $params->FromCreatedDateLocal;


/* obtiene una fecha y asigna identificadores de sistema de pago y caja. */
if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}

$PaymentSystemId = $params->PaymentSystemId;
$CashDeskId = $params->CashDeskId;

/* Asigna valores de parámetros a variables en un script de programación. */
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;
$Id = $params->Id;

/* establece que siempre se obtendrán detalles del producto solicitado. */
$IsDetails = ($params->IsDetails == true) ? true : false;

//Fijamos para obtener siempre detalles
$IsDetails = true;

$Product = $_REQUEST["Product"];

/* obtiene valores de entrada a través de solicitudes HTTP. */
$Type = $_REQUEST["Type"];

$FromId = $_REQUEST["FromId"];
$PlayerId = $_REQUEST["PlayerId"];
$Ip = $_REQUEST["Ip"];
$IsDetails = $_REQUEST["IsDetails"];

/* asigna valores de entrada a variables según condiciones especificadas. */
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';


$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

/* verifica si $MaxRows y $SkeepRows están vacíos, estableciendo $seguir en falso. */
$seguir = true;

if ($MaxRows == "") {
    $seguir = false;
}

if ($SkeepRows == "") {
    $seguir = false;
}

if ($seguir) {


    /* verifica una fecha y agrega una regla para filtrado si es válida. */
    $rules = [];

    if ($FromDateLocal != "") {
        //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
        array_push($rules, array("field" => "usuario_comision.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    }

    /* Condicionales que agregan reglas para filtrar datos según fechas y client IDs. */
    if ($ToDateLocal != "") {
        //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
        array_push($rules, array("field" => "usuario_comision.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
    }


    if ($ClientId != "") {
        array_push($rules, array("field" => "usuario_comision.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }


    /* Se añaden reglas de comisiones según los valores de $AmountFrom y $AmountTo. */
    if ($AmountFrom != "") {
        array_push($rules, array("field" => "usuario_comision.comision", "data" => "$AmountFrom", "op" => "ge"));
    }
    if ($AmountTo != "") {
        array_push($rules, array("field" => "usuario_comision.comision", "data" => "$AmountTo", "op" => "le"));
    }


    /* Añade reglas de validación según moneda y país seleccionados por el usuario. */
    if ($CurrencyId != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }

    if ($CountrySelect != '') {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }

    /* Agrega reglas basadas en el producto y el perfil del usuario en sesión. */
    if ($Product != '') {
        array_push($rules, array("field" => "usuario_comision.tipo", "data" => $Product, "op" => "eq"));
    }


    if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }


    /* verifica el perfil y añade reglas de acceso correspondientes a la sesión. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }


    if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }

    // Si el usuario esta condicionado por País

    /* Se añaden reglas de filtrado según condiciones de sesión del usuario. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }

    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Añade una regla a la lista si "mandanteLista" no está vacía ni es "-1". */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    /* verifica el perfil del usuario y agrega reglas a un array. */
    if ($FromId != "") {

        $UsuarioPerfil = new UsuarioPerfil($FromId, "");

        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
            array_push($rules, array("field" => "usuario_comision.usuario_id", "data" => "$FromId", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "usuario_comision.usuario_id", "data" => "$FromId", "op" => "eq"));
        }
        //array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$FromIdGetBetHistory", "op" => "eq"));
    }


    /* Se añade una regla si PlayerId no está vacío. Se inicializa agrupamiento. */
    if ($PlayerId != "") {
        array_push($rules, array("field" => "usuario_comision.usuarioref_id", "data" => "$PlayerId", "op" => "eq"));
    }


    $grouping = "";

    /* Código que construye una consulta SQL para obtener sumas de comisiones según tipo. */
    $select = "";
    if ($Type == 1) {
        $MaxRows = 10000;
        $select = " SUM(usuario_comision.valor1) valor1,SUM(usuario_comision.valor2) valor2,SUM(usuario_comision.valor3) valor3,
        SUM(usuario_comision.valor) valor_base,SUM(usuario_comision.comision) valoru,
        clasificador.descripcion,usuario_comision.tiene_comision,usuarioref.moneda,usuario_comision.usuario_id,usuario_comision.usuarioref_id";

        $grouping = "usuario_comision.usuario_id,usuario_comision.usuarioref_id,clasificador.descripcion";

    } else {
        /* selecciona datos de comisiones y referencias según condiciones específicas. */

        $select = " usuario_comision.*,clasificador.descripcion ,usuarioref.moneda";
        $grouping = "usuario_comision.usucomision_id";

    }


    /* Configura un filtro y define valores predeterminados para "SkeepRows" y "OrderedItem". */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece un valor predeterminado y obtiene transacciones en formato JSON. */
    if ($MaxRows == "") {
        $MaxRows = 5;
    }

    $json = json_encode($filtro);

    $transacciones = $UsuarioComision->getUsuarioComisionCustom($select, "usuario_comision.usucomision_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);


    /* Se decodifica un JSON y se inicializa un array y un contador. */
    $transacciones = json_decode($transacciones);

    $final = [];
    $totalm = 0;
    foreach ($transacciones->data as $key => $value) {

        /* asigna valores de un objeto a un array asociativo en PHP. */
        $array = [];


        $array["State"] = $value->{"usuario_comision.estado"};


        $array["Bets"] = $value->{"usuario_comision.valor1"};

        /* Asigna valores de comisión y usuario a un arreglo en PHP. */
        $array["Winnings"] = $value->{"usuario_comision.valor2"};
        $array["Bonus"] = $value->{"usuario_comision.valor3"};


        $array["Id"] = $value->{"usuario_comision.usucomision_id"};
        $array["UserId"] = $value->{"usuario_comision.usuario_id"};

        /* Asignación de datos a un array según condiciones específicas en PHP. */
        $array["ClientId"] = $value->{"usuario_comision.usuarioref_id"};
        $array["UserName"] = $value->{"usuario_comision.usuarioref_id"};
        $array["CreatedLocal"] = $value->{"usuario_comision.fecha_crea"};
        $array["ModifiedLocal"] = $value->{"usuario_comision.fecha_crea"};
        if($value->{"usuario_comision.tiene_comision"} == "N") {
            $array["NTC"] = "NO";
        }else{
            $array["NTC"] = "SI";
        }

        if ($Type == 1) {
            $array["Amount"] = $value->{".comision"};
            $array["CreatedLocal"] = $value->{".fecha_crea"};
            $array["ExternalId"] = "";

            $array["Bets"] = $value->{".valor1"};
            $array["Winnings"] = $value->{".valor2"};
            $array["Bonus"] = $value->{".valor3"};
            $array["AmountBase"] = $value->{".valor_base"};


        } else {
            /* Asigna valores de comisiones a un array según condiciones previas. */

            $array["AmountBase"] = $value->{"usuario_comision.valor"};
            $array["Amount"] = $value->{"usuario_comision.comision"};

        }

        /* Asigna valores a un array asociativo desde un objeto utilizando claves específicas. */
        $array["TypeName"] = $value->{"clasificador.descripcion"};

        $array["CurrencyId"] = $value->{"usuarioref.moneda"};
        $array["CashDeskId"] = $value->{"usuario_comision.usuario_id"};
        $array["State"] = $value->{"usuario_comision.estado"};
        $array["ExternalId"] = $value->{"usuario_comision.externo_id"};


        /* Agrega el contenido de `$array` al final del array `$final`. */
        array_push($final, $array);
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    /* determina la respuesta según la variable $IsDetails y establece valores específicos. */
    if ($IsDetails == 1) {
        $response["pos"] = 0;
        $response["data"] = $final;

    } else {
        $response["pos"] = $SkeepRows;
        $response["total_count"] = $transacciones->count[0]->{".count"};
        $response["data"] = $final;

    }


} else {
    /* asigna valores a la respuesta sin errores y estructura datos relacionados. */

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}