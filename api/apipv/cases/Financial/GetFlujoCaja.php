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
 * @Description Obtener el flujo de caja resumido.
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
 * @OA\Post(path="apipv/Financial/GetFlujoCaja", tags={"Financial"}, description = "",
 *
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(required={},
 *               @OA\Property(
 *                   property="count",
 *                   description="Número total de registros",
 *                   type="integer",
 *                   example= 469
 *               ),
 *               @OA\Property(
 *                   property="start",
 *                   description="Indice de posición de registros",
 *                   type="string",
 *                   example= "2"
 *               ),
 *               @OA\Property(
 *                   property="CountrySelect",
 *                   description="",
 *                   type="integer",
 *                   example= "2"
 *               ),
 *               @OA\Property(
 *                   property="Type",
 *                   description="",
 *                   type="integer",
 *                   example= "1"
 *               ),
 *               @OA\Property(
 *                   property="TypeDetail",
 *                   description="",
 *                   type="integer",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="BetShopId",
 *                   description="",
 *                   type="integer",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="dateTo",
 *                   description="",
 *                   type="string",
 *                   example= "2020-09-25 23:59:59"
 *               ),
 *               @OA\Property(
 *                   property="dateFrom",
 *                   description="",
 *                   type="string",
 *                   example= "2020-09-25 00:00:00"
 *               ),
 *             )
 *         ),
 *     ),
 *
 * @OA\Response (
 *      response="200",
 *      description="Success",
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(
 *               @OA\Property(
 *                   property="data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="pos",
 *                   description="",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="total_account",
 *                   description="Total de registros",
 *                   type="integer",
 *                   example= 20
 *               ),
 *             )
 *         ),
 *         )
 * ),
 * @OA\Response (response="404", description="Not found")
 * ),
 */


/* Se crea un objeto PuntoVenta y se procesa una fecha desde un JSON recibido. */
$PuntoVenta = new PuntoVenta();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->dateTo;


/* convierte fechas con un desfase horario y extrae un ID de sistema de pago. */
$ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . '' . $timezone . ' hour '));

$FromDateLocal = $params->dateFrom;

$FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


$PaymentSystemId = $params->PaymentSystemId;

/* asigna valores de parámetros a variables específicas para su procesamiento posterior. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* Captura y valida parámetros de entrada desde la solicitud para su uso posterior. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;

$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$BetShopId = (is_numeric($_REQUEST["BetShopId"])) ? $_REQUEST["BetShopId"] : '';
$TypeDetail = ($_REQUEST["TypeDetail"] == "0") ? 0 : 1;

/* Asigna valores basados en solicitudes de parámetros y verifica condiciones de entrada. */
$TypeTotal = ($_REQUEST["Type"] == "1") ? 0 : 1;
$FromId = $_REQUEST["FromId"];
$NoTicket = $_REQUEST["NoTicket"];

if ($FromId != '' && $FromId != 'undefined') {
    $BetShopId = $FromId;
}

/* Código que valida parámetros de solicitud para definir el número y salto de filas. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$seguir = true;

if ($SkeepRows == "" || $MaxRows == "") {
    $seguir = false;

}

if ($seguir) {

    /* Asigna la fecha actual a $FromDateLocal si está vacía. */
    if ($FromDateLocal == "") {


        $FromDateLocal = date("Y-m-d", strtotime(time() . $timezone . ' hour '));

    }

    /* Establece `$ToDateLocal` a la fecha actual si está vacía, considerando la zona horaria. */
    if ($ToDateLocal == "") {

        $ToDateLocal = date("Y-m-d", strtotime(time() . '' . $timezone . ' hour '));


    }


    if (in_array($_SESSION["mandante"], array(3, 4, 5, 6, 7, 10, 22, 25))) {


        /* Ajusta fechas locales considerando el tiempo y comparación con condiciones específicas. */
        $FromDateLocal = date("Y-m-d 06:00:00", strtotime($FromDateLocal . ' '));
        $ToDateLocal = date("Y-m-d 05:59:59", strtotime($ToDateLocal . '+1 days'));
        if ((date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]))) == date("Y-m-d", strtotime('-6 hour ')))) {
            if (date("H") < 6) {
                // $FromDateLocal = date("Y-m-d 06:00:00", strtotime($FromDateLocal.' -1 days'));
                // $ToDateLocal = date("Y-m-d 05:59:59", strtotime($ToDateLocal));
            } else {

            }

        }
        /* if(date("H") < 6){
             $FromDateLocal = date("Y-m-d 06:00:00", strtotime($FromDateLocal.' -1 days'));
             $ToDateLocal = date("Y-m-d 05:59:59", strtotime($ToDateLocal.'+1 days'));
         }else{
             $FromDateLocal = date("Y-m-d 06:00:00");
             $ToDateLocal = date("Y-m-d 05:59:59", strtotime($ToDateLocal.'+1 days'));

         }*/

        /* Se establece una variable booleana llamada $conHoras como verdadera. */
        $conHoras = true;

    }


    /* Se definen reglas y un agrupamiento para filtrar datos de recargas. */
    $rules = [];

    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


    $grouping = "";

    /* Código que calcula sumas según condiciones de detalle y tipo de total. */
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


    /* asigna valores predeterminados a variables si están vacías. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece filtros y obtiene transacciones según el perfil de usuario y condiciones. */
    if ($MaxRows == "") {
        $MaxRows = 1000000;
    }
    $MaxRows = 1000000;

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
        $rules = [];

        if ($NoTicket != '') {
            array_push($rules, array("field" => "x.ticket_id", "data" => "$NoTicket", "op" => "eq"));

        }
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $transacciones = $PuntoVenta->getFlujoCajaConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "x.fecha_crea asc,x.hora_crea", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
        /* define reglas de filtrado y obtiene transacciones para cajeros en una sesión. */

        $rules = [];

        if ($NoTicket != '') {
            array_push($rules, array("field" => "x.ticket_id", "data" => "$NoTicket", "op" => "eq"));

        }
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $transacciones = $PuntoVenta->getFlujoCajaConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "x.fecha_crea asc,x.hora_crea", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        /* Condiciona la obtención de datos según el perfil de usuario y ticket proporcionado. */

        $rules = [];

        if ($NoTicket != '') {
            array_push($rules, array("field" => "x.ticket_id", "data" => "$NoTicket", "op" => "eq"));

        }
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $transacciones = $PuntoVenta->getFlujoCaja($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "x.fecha_crea", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario'], "", "", "", "", $BetShopId);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        /* Se verifica el perfil y se define un filtro para obtener transacciones específicas. */

        $rules = [];

        if ($NoTicket != '') {
            array_push($rules, array("field" => "x.ticket_id", "data" => "$NoTicket", "op" => "eq"));

        }
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $transacciones = $PuntoVenta->getFlujoCaja($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "x.fecha_crea", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario'], "", "", "", $BetShopId);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        /* verifica un perfil de usuario y genera un filtro para consultas en base de datos. */

        $rules = [];

        if ($NoTicket != '') {
            array_push($rules, array("field" => "x.ticket_id", "data" => "$NoTicket", "op" => "eq"));

        }
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $transacciones = $PuntoVenta->getFlujoCaja($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "x.fecha_crea", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", "", $_SESSION['usuario'], "", "", $BetShopId);

    } else {


        /* Asignación de país basada en selección del usuario o sesión condicionada. */
        $Pais = "";

        if ($CountrySelect != "" && $CountrySelect != "0") {
            $Pais = $CountrySelect;
        }

        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            $Pais = $_SESSION['pais_id'];
        }


        /* Asignación de "Mandante" según condiciones de sesión del usuario. */
        $Mandante = "";
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            $Mandante = $_SESSION["mandante"];
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                $Mandante = $_SESSION["mandanteLista"];
            }

        }

        /* Se define un arreglo para reglas y se añade una condición si NoTicket no está vacío. */
        $rules = [];

        if ($NoTicket != '') {
            array_push($rules, array("field" => "x.ticket_id", "data" => "$NoTicket", "op" => "eq"));

        }

        /* Crea un filtro JSON basado en reglas y verifica el perfil de usuario. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $usuCreaId = '';
        if ($BetShopId != '' && $BetShopId != '0') {
            $UsuarioPerfil = new UsuarioPerfil($BetShopId);

            if ($UsuarioPerfil->perfilId == 'CAJERO') {
                $usuCreaId = $BetShopId;
                $BetShopId = '';

            }

        }


        // Inactivamos reportes para el país Colombia
        //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        /* obtiene transacciones de flujo de caja con múltiples parámetros y agrupaciones. */
        $transacciones = $PuntoVenta->getFlujoCaja($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "x.fecha_crea", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", "", "", $Pais, $Mandante, $BetShopId, $usuCreaId);

    }


    /* Se decodifica un JSON y se inicializan arreglos y variables para el procesamiento. */
    $transacciones = json_decode($transacciones);

    $final = [];
    $totalm = 0;
    foreach ($transacciones->data as $key => $value) {

        /* crea un array y asigna valores de un objeto usando notación de propiedad. */
        $array = [];
        $array["Punto"] = $value->{"d.puntoventa"};


        $saldo = 0;
        $array['Id'] = $value->{'x.flujocaja_id'};

        /* asigna valores a un array desde un objeto. */
        $array["Moneda"] = $value->{"d.moneda"};
        $array["Punto"] = $value->{"d.puntoventa"};
        $array["PuntoId"] = $value->{"d.pventa_id"};


        $array["Date"] = $value->{"x.fecha_crea"};
        $array["Hour"] = $value->{"x.hora_crea"};
        $array["NoTicket"] = $value->{".ticket_id"};

        /* Asigna valores de un objeto a un array asociativo en PHP. */
        $array["FormaPago1"] = $value->{".forma_pago1"};
        $array["PagoBonoTC"] = 0;
        $array["ValorEntradasEfectivo"] = $value->{".valor_entrada_efectivo"};
        $array["ValorEntradasBonoTC"] = $value->{".valor_entrada_bono"};
        $array["ValorEntradasTraslados"] = $value->{".valor_entrada_traslado"};
        $array["ValorEntradasRecargas"] = $value->{".valor_entrada_recarga"};

        /* calcula y organiza valores de entradas y salidas en un array. */
        $array["DevolucionRecargas"] = $value->{".valor_entrada_recarga_anuladas"};
        $array["ValorSalidasEfectivo"] = floatval($value->{".valor_salida_efectivo"}) - floatval($value->{".valor_entrada_recarga_anuladas"});
        $array["ValorSalidasTraslados"] = $value->{".valor_salida_traslado"};
        $array["ValorSalidasNotasRetiro"] = $value->{".valor_salida_notaret"};
        $array["ValorEntradasRecargasAgentes"] = $value->{".valor_entrada_recarga_agentes"};

        $saldo = floatval($array["ValorEntradasEfectivo"]) + floatval($array["ValorEntradasTraslados"]) + floatval($array["ValorEntradasRecargas"]) - floatval($array["ValorSalidasEfectivo"]) - floatval($array["ValorSalidasTraslados"]) - floatval($array["ValorSalidasNotasRetiro"] + floatval($array["ValorEntradasRecargasAgentes"]));

        /* asigna valores a un array y lo añade a otro array final. */
        $array["Saldo"] = $saldo;
        $array["Moneda"] = $value->{"d.moneda"};
        $array['AllowCancelTransaction'] = (empty($value->{'x.devolucion'}) && !empty($value->{'x.cupolog_id'})) ? true : false;

        /*Determinando vertical vinculada a la transacción*/
        if (!empty($value->{'.clienteid_apuesta_casino'}))
        {
            $array['Vertical'] = "virtual";
        }
        elseif (!empty($value->{'.clienteid_apuesta_deportiva'})) {
            $array['Vertical'] = "deportiva";
        }
        else {
            $array['Vertical'] = "indefinida";
        }

        $array["ClienteIdDinamico"] = $value->{'bu.cliente_id_plataforma'};
        $array["ClienteIdPlataforma"] = $value->{'bu.cliente_id_plataforma'};
        $array["TicketIdApuestaVirtual"] = $value->{'tji.casino_ticket'};

        /*Obtención TICKET utilizado por el proveedor de virtual para diferenciar la transacción*/
        if ($array['Vertical'] == "virtual") {
            $debitRequestJson = $value->{'ta.json_query'};
            $debitRequestObject = json_decode($debitRequestJson);

            if (empty($debitRequestObject)) $array["TicketIdProveedor"] = null;
            else {
                $array["TicketIdProveedor"] = $debitRequestObject->extData->ticketId;
            }
        }
        else $array["TicketIdProveedor"] = null;


        array_push($final, $array);
    }


    if ($BetShopId == "") {


        /* crea un objeto y obtiene datos JSON del input. */
        $UsuarioRecarga = new UsuarioRecarga();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ToCreatedDateLocal;


        /* establece una fecha final local basada en un rango de fechas proporcionado. */
        if ($_REQUEST["dateTo"] != "") {
            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
        }


        $FromDateLocal = $params->FromCreatedDateLocal;


        /* Convierte una fecha recibida en formato específico y extrae parámetros de un objeto. */
        if ($_REQUEST["dateFrom"] != "") {
            $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
        }

        $PaymentSystemId = $params->PaymentSystemId;
        $CashDeskId = $params->CashDeskId;

        /* Asignación de variables a partir de parámetros de entrada en un lenguaje de programación. */
        $ClientId = $params->ClientId;
        $AmountFrom = $params->AmountFrom;
        $AmountTo = $params->AmountTo;
        $CurrencyId = $params->CurrencyId;
        $ExternalId = $params->ExternalId;
        $Id = $params->Id;

        /* asigna siempre verdadero a $IsDetails y obtiene FromId de la solicitud. */
        $IsDetails = ($params->IsDetails == true) ? true : false;

        //Fijamos para obtener siempre detalles
        $IsDetails = true;

        $FromId = $_REQUEST["FromId"];

        /* recoge datos de entrada de un jugador desde la solicitud HTTP. */
        $PlayerId = $_REQUEST["PlayerId"];
        $Ip = $_REQUEST["Ip"];
        $IsDetails = 1;
        $CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';


        $MaxRows = $_REQUEST["count"];

        /* verifica parámetros y controla el flujo según condiciones específicas de entrada. */
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
        $seguir = true;

        if ($MaxRows == "") {
            $seguir = false;
        }


        /* verifica condiciones para detener un proceso según el perfil del usuario. */
        if ($SkeepRows == "") {
            $seguir = false;
        }

        if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3" || $_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO") {
            $seguir = false;
        }

        if ($seguir) {


            /* Código para agregar una regla de fecha a un arreglo si se especifica una fecha. */
            $rules = [];

            if ($FromDateLocal != "") {
                //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            }

            /* Añade reglas a un array basado en condiciones de fecha y sistema de pago. */
            if ($ToDateLocal != "") {
                //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
            }


            if ($PaymentSystemId != "") {
                array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
            }


            /* Agrega reglas a un arreglo según condiciones sobre identificadores de caja y cliente. */
            if ($CashDeskId != "") {
                array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
            }
            if ($ClientId != "") {
                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
            }


            /* Se añaden reglas de validación según los valores de $AmountFrom y $AmountTo. */
            if ($AmountFrom != "") {
                array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
            }
            if ($AmountTo != "") {
                array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
            }


            /* Agrega reglas a un array basadas en condiciones de variables no vacías. */
            if ($CurrencyId != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
            }
            if ($ExternalId != "") {
                array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
            }

            /* Agrega reglas al arreglo si $Id y $CountrySelect no están vacíos. */
            if ($Id != "") {
                array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
            }
            if ($CountrySelect != '') {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
            }


            /* añade reglas basadas en el perfil del usuario en sesión. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

            }


            /* verifica condiciones de sesión y ajusta reglas según el perfil y país. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));

            }

            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

            // Si el usuario esta condicionado por el mandante y no es de Global

            /* establece reglas de acceso según la sesión del usuario y su mandante. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }


            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* maneja reglas basadas en el perfil del usuario y su ID. */
            if ($FromId != "") {

                $UsuarioPerfil = new UsuarioPerfil($FromId, "");

                if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
                    array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => "$FromId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => "$FromId", "op" => "eq"));
                }
                //array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$FromIdGetBetHistory", "op" => "eq"));
            }


            /* agrega reglas de filtrado basadas en PlayerId e Ip. */
            if ($PlayerId != "") {
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
            }

            if ($Ip != "") {
                array_push($rules, array("field" => "usuario_recarga.dir_ip", "data" => "$Ip", "op" => "cn"));

            }


            /* Condicional que configura agrupaciones y selecciones para consultas basadas en detalles. */
            $grouping = "";
            $select = "";
            if ($IsDetails == 1) {
                $MaxRows = 10000;
                $grouping = "usuario.mandante,usuario.pais_id,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d'),proveedor.proveedor_id ";
                $select = "usuario.mandante,pais.*,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') fecha_crea,SUM(usuario_recarga.valor) valoru,usuario.moneda,SUM(transaccion_producto.valor) valor,producto.descripcion,proveedor.descripcion ";
                //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));
                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));

            } else {
                /* Código SQL que selecciona múltiples campos de varias tablas relacionadas. */

                $select = " pais.*,usuario_punto.login,usuario_punto.nombre,transaccion_producto.*,producto.*,proveedor.*,usuario.moneda,usuario_recarga.* ";

            }

            /* Se generan reglas de filtrado para consultas en una base de datos. */
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


            /* Convierte datos a JSON, obtiene recargas y decodifica el resultado. */
            $json = json_encode($filtro);

            $transacciones2 = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.recarga_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);

            $transacciones2 = json_decode($transacciones2);

            $totalm = 0;
            foreach ($transacciones2->data as $key => $value) {

                /* Inicializa un arreglo y suma un valor si $IsDetails es igual a 1. */
                $array = [];
                if ($IsDetails == 1) {
                    $totalm = $totalm + $value->{".valoru"};


                } else {
                    /* Suma el valor de transacciones a `totalm` si no cumple una condición previa. */

                    $totalm = $totalm + $value->{"transaccion_producto.valor"};

                }


                /* crea un arreglo con descripción del proveedor y fecha de creación. */
                $array = [];


                $array["Punto"] = $value->{"proveedor.descripcion"} . ' - ' . $value->{"usuario.moneda"};

                $array["Fecha"] = $value->{".fecha_crea"};

                /* asigna valores a un array basado en propiedades de un objeto. */
                $array["Moneda"] = $value->{"usuario.moneda"};
                $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"usuario.mandante"};
                $array["CountryIcon"] = strtolower($value->{"pais.iso"});
                $array["Agent"] = "Pasarelas de Pago - " . $value->{"usuario.moneda"};
                $array["CantidadTickets"] = 0;
                $array["ValorEntradasEfectivo"] = 0;

                /* Inicializa valores en un array para entradas y salidas financieras. */
                $array["ValorEntradasBonoTC"] = 0;
                $array["ValorEntradasRecargas"] = $value->{".valoru"};

                $array["ValorEntradasTraslados"] = 0;
                $array["ValorSalidasEfectivo"] = 0;
                $array["ValorSalidasTraslados"] = 0;

                /* Asignación de valores a un array y luego se añade a otro array final. */
                $array["ValorSalidasNotasRetiro"] = 0;
                $array["Saldo"] = $array["ValorEntradasRecargas"];
                $array["MMoneda"] = 0;


                array_push($final, $array);
            }


            /* Crea una nueva cuenta de cobro y procesa datos de entrada JSON. */
            $CuentaCobro = new CuentaCobro();

            $params = file_get_contents('php://input');
            $params = json_decode($params);

            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $ToDateLocal)));

            /* formatea una fecha de inicio y obtiene una fecha de fin. */
            $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal)));
            //$Region = $params->Region;
            //$CurrencyId = $params->CurrencyId;
            //$IsNewRegistered = $params->IsNewRegistered;


            $ToDateLocal = $params->ToCreatedDateLocal;


            /* ajusta una fecha final y asigna una fecha de inicio. */
            if ($_REQUEST["dateTo"] != "") {
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
            }


            $FromDateLocal = $params->FromCreatedDateLocal;


            /* verifica una fecha y la formatea según la zona horaria especificada. */
            if ($_REQUEST["dateFrom"] != "") {
                $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
            }


            $MaxRows = $params->MaxRows;

            /* asigna valores a $OrderedItem y $SkeepRows, estableciendo un valor por defecto. */
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* asigna valores predeterminados a variables si están vacías. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Se definen reglas de filtrado para una consulta de cuentas de cobro. */
            $rules = [];
            array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
            array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "0", "op" => "ne"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));

            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }


            /* Condiciona reglas para filtrar usuarios basado en moneda y perfil de punto de venta. */
            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
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


            /* agrega reglas de acceso según el perfil de usuario. */
            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CAJERO") {
                array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* agrega reglas basadas en el perfil de usuario de la sesión. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* añade reglas según condiciones de sesión de usuario y país. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

            // Si el usuario esta condicionado por el mandante y no es de Global

            /* manipula reglas basadas en la sesión del usuario y condiciones específicas. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }


            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* Se crea un filtro JSON y se consultan cuentas de cobro personalizadas. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $cuentas = $CuentaCobro->getCuentasCobroCustom("usuario.mandante,usuario.pais_id,pais.pais_nom,COUNT(*) count,SUM(cuenta_cobro.valor) valor,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d') fecha_crea,producto.descripcion,usuario.moneda", "cuenta_cobro.fecha_pago", "desc", $SkeepRows, $MaxRows, $json, true, "usuario.mandante,usuario.pais_id,transaccion_producto.producto_id,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d'),usuario.moneda");

            $cuentas = json_decode($cuentas);


            /* Inicializa variables para calcular retiros en un sistema financiero. */
            $valor_convertidoretiros = 0;
            $totalretiros = 0;
            foreach ($cuentas->data as $key => $value) {


                /* Se crea un arreglo con una descripción de producto y moneda. */
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

                /* Inicializa valores de entradas y salidas en un arreglo asociativo. */
                $array["ValorEntradasEfectivo"] = 0;
                $array["ValorEntradasBonoTC"] = 0;
                $array["ValorEntradasRecargas"] = 0;

                $array["ValorEntradasTraslados"] = 0;
                $array["ValorSalidasEfectivo"] = 0;

                /* Se calcula el saldo y se almacena en un array final. */
                $array["ValorSalidasTraslados"] = 0;
                $array["ValorSalidasNotasRetiro"] = $value->{".valor"};
                $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"];
                $array["MMoneda"] = 0;


                array_push($final, $array);


            }


        } else {
            /* muestra una estructura condicional "else" vacía sin ninguna instrucción definida. */


        }
    }


    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    /* organiza datos en una estructura de respuesta JSON para uso posterior. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $transacciones->count[0]->{".count"};
    $response["data"] = $final;
} else {
    /* inicializa un arreglo de respuesta con valores predeterminados en caso contrario. */

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = [];

}
