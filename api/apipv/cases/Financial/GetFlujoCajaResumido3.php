<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\BodegaFlujoCaja;
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
use Backend\dto\ModeloFiscal;
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
use UsingRefs\Model;

/**
 * Obtener el flujo de caja resumido.
 *
 * @param string $dateTo Descripción: Fecha de fin para el reporte de flujo de caja.
 * @param string $dateFrom Descripción: Fecha de inicio para el reporte de flujo de caja.
 * @param int $PaymentSystemId Descripción: Identificador del sistema de pago.
 * @param int $CashDeskId Descripción: Identificador de la caja.
 * @param int $ClientId Descripción: Identificador del cliente.
 * @param float $AmountFrom Descripción: Monto mínimo para el flujo de caja.
 * @param float $AmountTo Descripción: Monto máximo para el flujo de caja.
 * @param int $CurrencyId Descripción: Identificador de la moneda.
 * @param string $ExternalId Descripción: Identificador externo.
 * @param int $Id Descripción: Identificador del flujo de caja.
 * @param bool $IsDetails Descripción: Indicador para obtener información detallada.
 * @param int $CountrySelect Descripción: Identificador del país seleccionado.
 * @param int $BetShopId Descripción: Identificador de la tienda de apuestas.
 * @param int $MaxRows Descripción: Número máximo de filas a devolver.
 * @param int $OrderedItem Descripción: Ítem ordenado.
 * @param int $SkeepRows Descripción: Número de filas a omitir en la consulta.
 * @param string $FromId Descripción: Identificador de la fuente.
 * @param string $PlayerId Descripción: Identificador del jugador.
 * @param string $Ip Descripción: Dirección IP.
 * @param string $NoTicket Descripción: Número de ticket.
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
 */
/**
 * Este script procesa la información de venta obtenida a través de una solicitud en formato JSON.
 *
 * Se crea una instancia de la clase PuntoVenta y se obtienen parámetros de la solicitud para su posterior uso.
 * Los parámetros incluyen fechas, identificadores de sistemas de pago, caja, cliente, montos y moneda.
 */

/**
 * @OA\Post(path="apipv/Financial/GetFlujoCajaResumido", tags={"Financial"}, description = "",
 *
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(required={},
 *               @OA\Property(
 *                   property="PaymentSystemId",
 *                   description="PaymentSystemId",
 *                   type="string",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="CashDeskId",
 *                   description="CashDeskId",
 *                   type="string",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="ClientId",
 *                   description="ClientId",
 *                   type="string",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="AmountFrom",
 *                   description="AmountFrom",
 *                   type="string",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="AmountTo",
 *                   description="AmountTo",
 *                   type="string",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="CurrencyId",
 *                   description="CurrencyId",
 *                   type="string",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="ExternalId",
 *                   description="ExternalId",
 *                   type="string",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="Id",
 *                   description="Id",
 *                   type="string",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="IsDetails",
 *                   description="IsDetails",
 *                   type="string",
 *                   example= ""
 *               ),
 *              @OA\Property(
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
 *                   property="UserId",
 *                   description="",
 *                   type="integer",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="UserIdAgent",
 *                   description="",
 *                   type="integer",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="UserIdAgent2",
 *                   description="",
 *                   type="integer",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="WithPaymentGateways",
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
 *               @OA\Property(
 *                   property="WithCanceledBets",
 *                   description="WithCanceledBets",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="WithPendingAwards",
 *                   description="WithPendingAwards",
 *                   type="string",
 *                   example= ""
 *               )
 *             )
 *         )
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
 * )
 */


/* Se crea una instancia de PuntoVenta y se obtiene una fecha de entrada en formato JSON. */
$PuntoVenta = new PuntoVenta();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->dateTo;


/* Convierte fechas de entrada a formato local considerando la zona horaria especificada. */
$ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . '' . $timezone . ' hour '));

$FromDateLocal = $params->dateFrom;

$FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


$PaymentSystemId = $params->PaymentSystemId;

/* Variables recopiladas de parámetros para procesar transacciones en un sistema de caja. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* valida y asigna parámetros de entrada a variables específicas. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;

$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$BetShopId = (is_numeric($_REQUEST["BetShopId"])) ? $_REQUEST["BetShopId"] : '';
$UserId = (is_numeric($_REQUEST["UserId"])) ? $_REQUEST["UserId"] : '';

/* Código que valida y asigna valores desde la solicitud HTTP. */
$TypeDetail = ($_REQUEST["TypeDetail"] == "0") ? 0 : 1;
$TypeTotal = ($_REQUEST["Type"] == "0") ? 0 : 1;

$UserIdAgent = (is_numeric($_REQUEST["UserIdAgent"])) ? $_REQUEST["UserIdAgent"] : '';
$UserIdAgent2 = (is_numeric($_REQUEST["UserIdAgent2"])) ? $_REQUEST["UserIdAgent2"] : '';

$MaxRows = $_REQUEST["count"];

/* extrae parámetros de petición para procesar pedidos y opciones adicionales. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$WithPaymentGateways = $_REQUEST["WithPaymentGateways"];

$WithCanceledBets = $_REQUEST["WithCanceledBets"];

/* verifica condiciones para continuar o detener un proceso basado en variables. */
$WithPendingAwards = $_REQUEST["WithPendingAwards"];

$seguir = true;

if ($SkeepRows == "" || $MaxRows == "") {
    $seguir = false;

}


/* inicializa un array y una variable, asignando un país basado en la sesión. */
$final = [];
$totalm = 0;

$Pais = $_SESSION['PaisCond'] == 'S' ? $_SESSION['pais_id'] : $CountrySelect;

if (!empty($Pais) && !empty($FromDateLocal)) {

    /* Inicializa un modelo fiscal y obtiene el año y mes de una fecha. */
    $ModeloFiscal = new ModeloFiscal();
    $dateParts = explode('-', $FromDateLocal);
    $Year = $dateParts[0];
    $Mounth = $datePart[1];

    $rules_modelo = [];


    /* Se definen reglas de filtrado para modelos fiscales y se convierten a JSON. */
    array_push($rules_modelo, ['field' => 'modelo_fiscal.pais_id', 'data' => $Pais, 'op' => 'eq']);
    array_push($rules_modelo, ['field' => 'modelo_fiscal.estado', 'data' => 'A', 'op' => 'eq']);
    array_push($rules_modelo, ['field' => 'modelo_fiscal.mandante', 'data' => $_SESSION['mandante'], 'op' => 'eq']);
    array_push($rules_modelo, ['field' => 'modelo_fiscal.mes', 'data' => $Mounth, 'op' => 'eq']);
    array_push($rules_modelo, ['field' => 'modelo_fiscal.anio', 'data' => $Year, 'op' => 'eq']);

    $filtros_modelo = json_encode($rules_model);


    /* Obtiene y decodifica un modelo fiscal personalizado en formato JSON. */
    $modeloFiscal = $ModeloFiscal->getModeloFiscalCustom(' modelo_fiscal.*, clasificador.*', 'modelo_fiscal.modelofiscal_id', 'asc', 0, 100000, $filtros_modelo, true);

    $modeloFiscal = json_decode($modeloFiscal);

    foreach ($modeloFiscal->data as $key => $value) {
        switch ($value->{'clasificador.abreviado'}) {
            case 'PORCENVADEPO':
                /* Asignación del valor de porcentaje de depósito desde un objeto a una variable entera. */

                $PercentDepositValue = intval($value->{'modelo_fiscal.valor'});
                break;
            case 'PORCENVARETR':
                /* asigna un valor entero a `$PercentRetirementValue` desde un objeto. */

                $PercentRetirementValue = intval($value->{'modelo_fiscal.valor'});
                break;
            case 'PORCENVAAPUESDEPOR':
                /* Asigna un valor entero a la variable desde un objeto específico. */

                $PercentValueSportsBets = intval($value->{'modelo_fiscal.valor'});
                break;
            case 'PORCENVAAPUESNODEPOR':
                /* Almacena un valor entero de apuestas no deportivas en una variable específica. */

                $PercentValueNonSportBets = intval($value->{'modelo_fiscal.valor'});
                break;
            case 'PORCENVAPREMDEPOR':
                /* Se asigna un valor entero a la variable basada en el modelo fiscal. */

                $PercentValueSportsAwards = intval($value->{'modelo_fiscal.valor'});
                break;
            case 'PORCENVAPREMNODEPOR':
                /* Asigna un valor entero a la variable basada en un campo específico de un objeto. */

                $PercentValueNonSportsAwards = intval($value->{'modelo_fiscal.valor'});
                break;
            case 'PORCENVABONDEPOR':
                /* Asigna un valor entero a PercentValueSportsBonds desde modelo_fiscal.valor en un caso específico. */

                $PercentValueSportsBonds = intval($value->{'modelo_fiscal.valor'});
                break;
            case 'PORCENVABONNODEPOR':
                /* Asigna un valor entero a la variable desde el objeto según un caso específico. */

                $PercentValueNonSportsBounds = intval($value->{'modelo_fiscal.valor'});
                break;
            case 'PORCENVATICKET':
                /* Extrae y convierte a entero el valor fiscal de tickets en un caso específico. */

                $PercentValueTickets = intval($value->{'modelo_fiscal.valor'});
                break;
            default:
                break;
        }
    }
}

if ($seguir && date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour ')) == date("Y-m-d", time())) {


    /* Se inicializan fechas y se preparan reglas para filtrado en una consulta. */
    $FromDateLocal = date("Y-m-d");
    $ToDateLocal = date("Y-m-d");

    $rules = [];

    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


    $grouping = "";

    /* Código que determina la consulta SQL según condiciones de tipo y detalles. */
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


    /* Asigna valores predeterminados a variables si están vacías. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece un valor predeterminado y asigna un identificador de usuario condicionado. */
    if ($MaxRows == "") {
        $MaxRows = 1000000;
    }
    $MaxRows = 1000000;


    if ($_REQUEST["UserId"] != "") {
        $BetShopId = $_REQUEST["UserId"];

    }


    /* Condicional que obtiene transacciones según el perfil de usuario en sesión. */
    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        $transacciones = $PuntoVenta->getFlujoCajaResumidoConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CAJERO") {

        $transacciones = $PuntoVenta->getFlujoCajaResumidoConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        /* verifica un perfil y obtiene datos de transacciones en un concesionario. */


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario'], "", "", "", "", $BetShopId);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        /* Condicional que ejecuta una consulta de transacciones si se cumple un perfil específico. */


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario'], "", "", "", $BetShopId);

    } else {


        /* Asigna un país basado en la selección del usuario o la sesión activa. */
        $Pais = "";

        if ($CountrySelect != "" && $CountrySelect != "0") {
            $Pais = $CountrySelect;
        }

        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            $Pais = $_SESSION['pais_id'];
        }


        /* Asigna un valor a $Mandante según la condición del usuario y su mandante. */
        $Mandante = "";
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            $Mandante = strtolower($_SESSION["mandante"]);
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                $Mandante = $_SESSION["mandanteLista"];
            }

        }


        // Inactivamos reportes para el país Colombia

        /* Agrega una regla para filtrar transacciones en función del país del usuario. */
        array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $UserIdAgent, $UserIdAgent2, "", $Pais, $Mandante, $BetShopId);

    }


    /* convierte una cadena JSON en un objeto o array de PHP. */
    $transacciones = json_decode($transacciones);

    foreach ($transacciones->data as $key => $value) {

        /* Condicional que modifica un elemento del array según la sesión actual. */
        $array = [];
        $array["Punto"] = "PUNTO";

        if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
            $array["Punto"] = $value->{"y.login"};

        } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
            /* Condicional que asigna un valor al arreglo si el perfil es "CAJERO". */

            $array["Punto"] = $value->{"y.login"};

        } else {
            /* Asigna un valor a "Punto" en el array si se cumple una condición. */

            $array["Punto"] = $value->{"y.punto_venta"};

        }


        /* Asigna propiedades de un objeto a un array,Organizando información del usuario y transacciones. */
        $array["UserId"] = $value->{"y.usuario_id"};

        $array["Fecha"] = $value->{"y.fecha_crea"};
        $array["Moneda"] = $value->{"y.moneda"};
        $array["CountryId"] = $value->{"y.pais_nom"} . ' - ' . $value->{"y.mandante"};
        $array["Partner"] = $value->{"y.mandante"};

        /* asigna valores a un arreglo basado en ciertas condiciones y cálculos. */
        $array["CountryIcon"] = strtolower($value->{"y.pais_iso"});
        $array["Agent"] = $value->{"uu.agente"} . ' - ' . $array["Moneda"];

        $array["CantidadTickets"] = isset($PercentValueTickets) ? ($value->{".cant_tickets"} / 100) * $PercentValueTickets : $value->{".cant_tickets"};//PORCENVATICKET

        $array["ValorEntradasEfectivo"] = isset($PercentValueSportsBets) ? ($value->{".valor_entrada_efectivo"} / 100) * $PercentValueSportsBets : $value->{".valor_entrada_efectivo"};//PORCENVAAPUESDEPOR


        /* Calcula valores de entradas según porcentajes definidos o utiliza valores predeterminados. */
        $array["ValorEntradasBonoTC"] = isset($PercentValueSportsBonds) ? ($value->{".valor_entrada_bono"} / 100) * $PercentValueSportsBonds : $value->{".valor_entrada_bono"};//PORCENVABONDEPOR

        $array["ValorEntradasRecargas"] = isset($PercentDepositValue) ? ($value->{".valor_entrada_recarga"} / 100) * $PercentDepositValue : $value->{".valor_entrada_recarga"};//PORCENVADEPO

        $array["ValorEntradasRecargasAgentes"] = isset($PercentDepositValue) ? ($value->{".valor_entrada_recarga_agentes"} / 100) * $PercentDepositValue : $value->{".valor_entrada_recarga_agentes"};//PORCENVADEPO

        $array["ValorEntradasTraslados"] = $value->{".valor_entrada_traslado"};


        /* calcula valores financieros y el saldo de un array. */
        $array["ValorSalidasEfectivo"] = isset($PercentValueSportsAwards) ? ($value->{".valor_salida_efectivo"} / 100) * $PercentValueSportsAwards : $value->{".valor_salida_efectivo"};//PORCENVAPREMDEPOR

        $array["ValorSalidasTraslados"] = $value->{".valor_salida_traslado"};

        $array["ValorSalidasNotasRetiro"] = isset($PercentRetirementValue) ? ($value->{".valor_salida_notaret"} / 100) * $PercentRetirementValue : $value->{".valor_salida_notaret"};//PORCENVARETR

        $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"] + $array["ValorEntradasRecargasAgentes"];

        /* Asignación de valores a un array desde un objeto utilizando propiedades específicas. */
        $array["MMoneda"] = $value->{"y.punto_venta"};
        $array["Tax"] = $value->{".impuestos"};


        $array["VoidedPlacedBets"] = $value->{".apuestas_void"};
        $array["VoidedPaidBets"] = $value->{".premios_void"};


        /* Calcula entradas y salidas efectivas netas, ajustando por apuestas anuladas según el socio. */
        $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPlacedBets"];
        $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

        if ($array["Partner"] == 1 || $array["Partner"] == 2) {
            $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPaidBets"];
            $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - 0;
            $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

        }


        /* verifica condiciones y modifica un array antes de agregarlo a otro. */
        if ($TypeTotal == 1) {

            $array["Punto"] = $value->{"y.punto_venta"} . $array["Agent"];

        }
        if ($array["UserId"] != '') {
            array_push($final, $array);

        }

    }
}


/* Condicional que verifica si el usuario no es 449 para detener el proceso. */
if ($_SESSION["usuario"] != 449) {
    //$seguir = false;
}
try {

    if ($seguir) {


        /* Convierte fechas de entrada en formato "Y-m-d" considerando la zona horaria. */
        $FromDateLocal2 = date("Y-m-d", strtotime(time() . $timezone . ' hour '));
        $ToDateLocal2 = date("Y-m-d", strtotime(time() . '' . $timezone . ' hour '));


        if ($_REQUEST["dateFrom"] != "") {


            $FromDateLocal2 = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));

        }

        /* Convierte una fecha recibida en formato específico a formato "Y-m-d" considerando zona horaria. */
        if ($_REQUEST["dateTo"] != "") {

            $ToDateLocal2 = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . '' . $timezone . ' hour '));


        }

        /* define reglas para filtrar datos basados en fechas y un ID de usuario. */
        $rules = [];

        array_push($rules, array("field" => "bodega_flujo_caja.fecha", "data" => "$FromDateLocal2", "op" => "ge"));
        array_push($rules, array("field" => "bodega_flujo_caja.fecha", "data" => "$ToDateLocal2", "op" => "le"));


        if ($_REQUEST["UserId"] != "") {

            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_REQUEST["UserId"], "op" => "eq"));


        }


        /* Agrega condiciones a un array según los parámetros recibidos en la solicitud. */
        if ($_REQUEST["BetShopId"] != "") {
            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_REQUEST["BetShopId"], "op" => "eq"));
        }


        if ($UserIdAgent != "") {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UserIdAgent, "op" => "eq"));
        }


        /* Agrega una regla al arreglo si $UserIdAgent2 no está vacío. */
        if ($UserIdAgent2 != "") {
            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UserIdAgent2, "op" => "eq"));
        }


        $grouping = "";

        /* La variable `$select` se inicializa como una cadena vacía en el código. */
        $select = "";
        if ($TypeTotal == '0') {
            $select = 'bodega_flujo_caja.usuario_id,
       bodega_flujo_caja.fecha,
       bodega_flujo_caja.pais_id,
       bodega_flujo_caja.mandante,
       bodega_flujo_caja.concesionario_id,
       SUM(bodega_flujo_caja.cant_tickets) cant_tickets,
       SUM(bodega_flujo_caja.valor_entrada_efectivo) valor_entrada_efectivo,
       SUM(bodega_flujo_caja.valor_entrada_bono) valor_entrada_bono,
       SUM(bodega_flujo_caja.valor_entrada_recarga) valor_entrada_recarga,
       SUM(bodega_flujo_caja.valor_entrada_traslado) valor_entrada_traslado,
       SUM(bodega_flujo_caja.valor_salida_traslado) valor_salida_traslado,
       SUM(bodega_flujo_caja.valor_salida_notaret) valor_salida_notaret,
       SUM(bodega_flujo_caja.valor_entrada) valor_entrada,
       SUM(bodega_flujo_caja.valor_salida) valor_salida,
       SUM(bodega_flujo_caja.valor_salida_efectivo) valor_salida_efectivo,
       SUM(bodega_flujo_caja.premios_pend) premios_pend,
       SUM(bodega_flujo_caja.impuestos) impuestos,
       SUM(bodega_flujo_caja.apuestas_void) apuestas_void,
       SUM(bodega_flujo_caja.premios_void) premios_void,
       
       usuario.usuario_id,
       usuario.login,
       usuario.moneda,
       punto_venta.descripcion,
       
       pais.*,
       
       agente.nombre,
       agente2.nombre,agente.usuario_id,agente2.usuario_id';


            /* Se define un agrupamiento para datos de usuario y fecha en un flujo de caja. */
            $grouping = 'bodega_flujo_caja.usuario_id,bodega_flujo_caja.fecha';
        } else {

            $select = 'bodega_flujo_caja.usuario_id,
       bodega_flujo_caja.fecha,
       bodega_flujo_caja.pais_id,
       bodega_flujo_caja.mandante,
       bodega_flujo_caja.concesionario_id,
       SUM(bodega_flujo_caja.cant_tickets) cant_tickets,
       SUM(bodega_flujo_caja.valor_entrada_efectivo) valor_entrada_efectivo,
       SUM(bodega_flujo_caja.valor_entrada_bono) valor_entrada_bono,
       SUM(bodega_flujo_caja.valor_entrada_recarga) valor_entrada_recarga,
       SUM(bodega_flujo_caja.valor_entrada_traslado) valor_entrada_traslado,
       SUM(bodega_flujo_caja.valor_salida_traslado) valor_salida_traslado,
       SUM(bodega_flujo_caja.valor_salida_notaret) valor_salida_notaret,
       SUM(bodega_flujo_caja.valor_entrada) valor_entrada,
       SUM(bodega_flujo_caja.valor_salida) valor_salida,
       SUM(bodega_flujo_caja.valor_salida_efectivo) valor_salida_efectivo,
       SUM(bodega_flujo_caja.premios_pend) premios_pend,
       SUM(bodega_flujo_caja.impuestos) impuestos,
       SUM(bodega_flujo_caja.apuestas_void) apuestas_void,
       SUM(bodega_flujo_caja.premios_void) premios_void,
       
       usuario.usuario_id,
       usuario.login,
       usuario.moneda,
       punto_venta.descripcion,
       
       pais.*,
       
       agente.nombre,
       agente2.nombre,agente.usuario_id,agente2.usuario_id
       
       ';


            /* Código que define un grupo de columnas para un análisis de datos en una consulta. */
            $grouping = 'bodega_flujo_caja.mandante,concesionario.usupadre_id,bodega_flujo_caja.fecha';

        }


        /* asigna valores predeterminados si las variables están vacías. */
        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }


        /* establece un límite de filas y agrega reglas de usuario para cajeros. */
        if ($MaxRows == "") {
            $MaxRows = 1000000;
        }
        $MaxRows = 1000000;

        if ($_SESSION["win_perfil2"] == "CAJERO") {

            array_push($rules, array("field" => "bodega_flujo_caja.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
            /* Verifica el perfil y añade una regla para el punto de venta en un array. */


            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
            /* Añade una regla de usuario al flujo de caja si el perfil es "CAJERO". */


            array_push($rules, array("field" => "bodega_flujo_caja.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
            /* Condicional que agrega una regla para concesionarios en un arreglo de reglas. */


            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
            /* Condiciona la inserción de una regla basada en el perfil del usuario. */


            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        }


        /* asigna un país basado en selecciones de usuario o sesión. */
        $Pais = "";

        if ($CountrySelect != "" && $CountrySelect != "0") {
            $Pais = $CountrySelect;
        }

        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            $Pais = $_SESSION['pais_id'];
        }


        /* Asigna el valor de 'Mandante' según condiciones de sesión del usuario. */
        $Mandante = "";
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            $Mandante = $_SESSION["mandante"];
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                $Mandante = $_SESSION["mandanteLista"];
            }

        }


        /* agrega reglas condicionales a un arreglo basándose en variables no vacías. */
        if ($Pais != '') {
            array_push($rules, array("field" => "bodega_flujo_caja.pais_id", "data" => $Pais, "op" => "in"));
        }

        if ($Mandante != '') {
            array_push($rules, array("field" => "bodega_flujo_caja.mandante", "data" => $Mandante, "op" => "in"));
        }


        // Inactivamos reportes para el país Colombia

        /* Agrega una regla a un array y define un filtro con condiciones. */
        array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }


        /* Asigna valores predeterminados si las variables están vacías. */
        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 5;
        }


        /* obtiene y decodifica transacciones de flujos de caja en formato JSON. */
        $json = json_encode($filtro);


        $BodegaFlujoCaja = new BodegaFlujoCaja();
        $transacciones = $BodegaFlujoCaja->getBodegaFlujoCajaCustom($select, "punto_venta.descripcion asc,bodega_flujo_caja.fecha", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);


        $transacciones = json_decode($transacciones);

        //$final = [];

        /* Inicializa la variable $totalm con un valor de cero en programación. */
        $totalm = 0;
        foreach ($transacciones->data as $key => $value) {

            /* Se crea un array con un valor condicional basado en una sesión de usuario. */
            $array = [];
            $array["Punto"] = "PUNTO";

            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $array["Punto"] = $value->{"usuario.login"};

            } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
                /* Condicional que asigna un valor al array si el perfil es "CAJERO". */

                $array["Punto"] = $value->{"usuario.login"};

            } else {
                /* asigna valores a un arreglo dependiendo de condiciones específicas. */

                if ($TypeTotal == '0') {

                    if ($value->{"bodega_flujo_caja.mandante"} == '2') {
                        $array["Punto"] = $value->{"punto_venta.descripcion"};


                    } else {
                        $array["Punto"] = $value->{"punto_venta.descripcion"} . ' - ' . $value->{"usuario.usuario_id"};

                    }


                } else {

                    $array["Punto"] = 'Punto Venta ' . $value->{"usuario.moneda"} . $value->{"agente.usuario_id"};

                }
            }


            /* asigna valores a un array a partir de un objeto. */
            $array["UserId"] = $value->{"usuario.usuario_id"};

            $array["Fecha"] = $value->{"bodega_flujo_caja.fecha"};
            $array["Moneda"] = $value->{"usuario.moneda"};
            $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"bodega_flujo_caja.mandante"};
            $array["Partner"] = $value->{"bodega_flujo_caja.mandante"};

            /* asigna valores a un array basado en datos de un objeto. */
            $array["CountryIcon"] = strtolower($value->{"pais.iso"});
            $array["Agent"] = $value->{"agente.nombre"} . ' - ' . $array["Moneda"];

            $array["CantidadTickets"] = isset($PercentValueTickets) ? ($value->{".cant_tickets"} / 100) * $PercentValueTickets : $value->{".cant_tickets"};//PORCENVATICKET


            $array["ValorEntradasEfectivo"] = isset($PercentValueSportsBets) ? ($value->{".valor_entrada_efectivo"} / 100) * $PercentValueSportsBets : $value->{".valor_entrada_efectivo"};//PORCENVAAPUESDEPOR


            /* Cálculo condicional de valores para bonos y recargas, según porcentajes definidos. */
            $array["ValorEntradasBonoTC"] = isset($PercentValueSportsBonds) ? ($value->{".valor_entrada_bono"} / 100) * $PercentValueSportsBonds : $value->{".valor_entrada_bono"};//PORCENVABONDEPOR


            $array["ValorEntradasRecargas"] = isset($PercentDepositValue) ? ($value->{".valor_entrada_recarga"} / 100) * $PercentDepositValue : $value->{".valor_entrada_recarga"};//PORCENVADEPO

            $array["ValorEntradasRecargasAgentes"] = isset($PercentDepositValue) ? ($value->{".valor_entrada_recarga_agentes"} / 100) * $PercentDepositValue : $value->{".valor_entrada_recarga_agentes"};//PORCENVADEPO


            /* Asignación de valores a un array según condiciones y variables específicas. */
            $array["ValorEntradasTraslados"] = $value->{".valor_entrada_traslado"};

            $array["ValorSalidasEfectivo"] = isset($PercentValueSportsAwards) ? ($value->{".valor_salida_efectivo"} / 100) * $PercentValueSportsAwards : $value->{".valor_salida_efectivo"};//PORCENVAPREMDEPOR

            $array["ValorSalidasTraslados"] = $value->{".valor_salida_traslado"};

            $array["ValorSalidasNotasRetiro"] = isset($PercentRetirementValue) ? ($value->{".valor_salida_notaret"} / 100) * $PercentRetirementValue : $value->{".valor_salida_notaret"};//PORCENVARETR


            /* Calcula el saldo y otros valores financieros de un usuario mediante un arreglo asociativo. */
            $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"] + $array["ValorEntradasRecargasAgentes"];
            $array["MMoneda"] = $value->{"usuario.moneda"};
            $array["Tax"] = $value->{".impuestos"};


            $array["VoidedPlacedBets"] = $value->{".apuestas_void"};

            /* Calcula valores netos de entradas y salidas de efectivo, considerando apuestas anuladas. */
            $array["VoidedPaidBets"] = $value->{".premios_void"};

            $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPlacedBets"];
            $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

            if ($array["Partner"] == 1 || $array["Partner"] == 2) {
                $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPaidBets"];
                $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - 0;
                $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

            }


            /* Añade elementos a $final si "UserId" en $array no está vacío. */
            if ($array["UserId"] != '') {
                array_push($final, $array);

            }
        }


        if ($BetShopId == "" && $UserId == "" && $WithPaymentGateways == '0') {


            /* Se crea un objeto UsuarioRecarga y se decodifica JSON de la entrada. */
            $UsuarioRecarga = new UsuarioRecarga();

            $params = file_get_contents('php://input');
            $params = json_decode($params);

            $ToDateLocal = $params->ToCreatedDateLocal;


            /* Convierte una fecha de solicitud a formato local y establece un rango temporal. */
            if ($_REQUEST["dateTo"] != "") {
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
            }


            $FromDateLocal = $params->FromCreatedDateLocal;


            /* procesa una fecha y asigna identificadores de sistema de pago y caja. */
            if ($_REQUEST["dateFrom"] != "") {
                $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
            }

            $PaymentSystemId = $params->PaymentSystemId;
            $CashDeskId = $params->CashDeskId;

            /* Extrae parámetros de un objeto para su uso posterior en una aplicación. */
            $ClientId = $params->ClientId;
            $AmountFrom = $params->AmountFrom;
            $AmountTo = $params->AmountTo;
            $CurrencyId = $params->CurrencyId;
            $ExternalId = $params->ExternalId;
            $Id = $params->Id;

            /* establece un parámetro booleano y asigna un identificador desde la solicitud. */
            $IsDetails = ($params->IsDetails == true) ? true : false;

            //Fijamos para obtener siempre detalles
            $IsDetails = true;

            $FromId = $_REQUEST["FromId"];

            /* obtiene datos de una solicitud HTTP, incluyendo identificadores y una dirección IP. */
            $PlayerId = $_REQUEST["PlayerId"];
            $Ip = $_REQUEST["Ip"];
            $IsDetails = 1;
            $CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';


            $MaxRows = $_REQUEST["count"];

            /* maneja la solicitud de elementos ordenados y verifica condiciones de paginación. */
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
            $seguir = true;

            if ($MaxRows == "") {
                $seguir = false;
            }


            /* verifica condiciones para modificar la variable $seguir según el perfil y $SkeepRows. */
            if ($SkeepRows == "") {
                $seguir = false;
            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO") {
                $seguir = false;
            }


            /* verifica si el usuario es "16758" y detiene el proceso. */
            if ($_SESSION["usuario"] == "16758") {
                $seguir = false;
            }

            if ($seguir) {


                /* Código que crea reglas basadas en una fecha de inicio, aplicando una conversión de zona horaria. */
                $rules = [];

                if ($FromDateLocal != "") {
                    //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
                    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
                    array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal . ' ' . 'America/Bogota'), "op" => "ge"));
                }

                /* agrega condiciones a un array basado en fechas y un sistema de pago. */
                if ($ToDateLocal != "") {
                    //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
                    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
                    array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal . ' ' . 'America/Bogota'), "op" => "le"));
                }


                if ($PaymentSystemId != "") {
                    array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
                }


                /* Agrega condiciones a un array de reglas si los identificadores no están vacíos. */
                if ($CashDeskId != "") {
                    array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
                }
                if ($ClientId != "") {
                    array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
                }


                /* Agrega condiciones de filtrado para valores de recarga en un array de reglas. */
                if ($AmountFrom != "") {
                    array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
                }
                if ($AmountTo != "") {
                    array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
                }


                /* Añade reglas de filtrado basadas en CurrencyId y ExternalId si no están vacíos. */
                if ($CurrencyId != "") {
                    array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
                }
                if ($ExternalId != "") {
                    array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
                }

                /* Agrega reglas a un arreglo si las condiciones de $Id y $CountrySelect se cumplen. */
                if ($Id != "") {
                    array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
                }
                if ($CountrySelect != '') {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
                }


                /* Código para agregar reglas según el perfil del usuario en sesión. */
                if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

                }

                if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

                }

                // Si el usuario esta condicionado por País

                /* agrega reglas de filtrado basadas en condiciones de sesión del usuario. */
                if ($_SESSION['PaisCond'] == "S") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                }

                // Si el usuario esta condicionado por el mandante y no es de Global
                if ($_SESSION['Global'] == "N") {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
                } else {
                    /* Agrega una regla al arreglo si "mandanteLista" no está vacía ni es "-1". */


                    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                    }

                }


                // Inactivamos reportes para el país Colombia

                /* Agrega reglas de validación basadas en el perfil del usuario y condiciones específicas. */
                array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


                if ($FromId != "") {

                    $UsuarioPerfil = new UsuarioPerfil($FromId, "");

                    if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
                        array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => "$FromId", "op" => "eq"));

                    } else {
                        array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => "$FromId", "op" => "eq"));
                    }
                    //array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$FromIdGetBetHistory", "op" => "eq"));
                }


                /* Agrega reglas de filtrado basadas en PlayerId e IP si no están vacíos. */
                if ($PlayerId != "") {
                    array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
                }

                if ($Ip != "") {
                    array_push($rules, array("field" => "usuario_recarga.dir_ip", "data" => "$Ip", "op" => "cn"));

                }


                /* Crea consultas SQL para agrupar y seleccionar datos según detalles específicos. */
                $grouping = "";
                $select = "";
                if ($IsDetails == 1) {
                    $MaxRows = 10000;
                    $grouping = "usuario.mandante,usuario.pais_id,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d'),proveedor.proveedor_id ";
                    $select = "usuario.mandante,pais.*,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') fecha_crea,SUM(usuario_recarga.valor) valoru,usuario.moneda,SUM(transaccion_producto.valor) valor,producto.descripcion,proveedor.descripcion ";
                    //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));
                    array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));

                } else {
                    /* construye una consulta SQL seleccionando diversos campos de múltiples tablas. */

                    $select = " pais.*,usuario_punto.login,usuario_punto.nombre,transaccion_producto.*,producto.*,proveedor.*,usuario.moneda,usuario_recarga.* ";

                }

                /* Se añade una regla a un filtro y se inicializa $SkeepRows si está vacío. */
                array_push($rules, array("field" => "usuario_recarga.puntoventa_id", "data" => "0", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }


                /* inicializa variables si están vacías, asignando valores predeterminados. */
                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 5;
                }


                /* Codifica un filtro a JSON y obtiene transacciones personalizadas del usuario. */
                $json = json_encode($filtro);

                $transacciones2 = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.recarga_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);

                $transacciones2 = json_decode($transacciones2);

                $totalm = 0;
                foreach ($transacciones2->data as $key => $value) {

                    /* inicializa un arreglo y suma un valor si se cumplen condiciones específicas. */
                    $array = [];
                    if ($IsDetails == 1) {
                        $totalm = $totalm + $value->{".valoru"};


                    } else {
                        /* Suma el valor de la transacción al total acumulado en caso contrario. */

                        $totalm = $totalm + $value->{"transaccion_producto.valor"};

                    }


                    /* crea un array asociativo con información de proveedor y fecha. */
                    $array = [];


                    $array["Punto"] = $value->{"proveedor.descripcion"} . ' - ' . $value->{"usuario.moneda"};

                    $array["Fecha"] = $value->{".fecha_crea"};

                    /* Asigna datos relacionados con moneda y país a un array en PHP. */
                    $array["MMoneda"] = $value->{"usuario.moneda"};
                    $array["Moneda"] = $value->{"usuario.moneda"};
                    $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"usuario.mandante"};
                    $array["CountryIcon"] = strtolower($value->{"pais.iso"});
                    $array["Agent"] = "Pasarelas de Pago - " . $value->{"usuario.moneda"};
                    $array["CantidadTickets"] = 0;

                    /* Inicializa valores y calcula entradas basadas en porcentaje de depósitos. */
                    $array["ValorEntradasEfectivo"] = 0;
                    $array["ValorEntradasBonoTC"] = 0;

                    $array["ValorEntradasRecargasAgentes"] = isset($PercentDepositValue) ? ($value->{".valoru"} / 100) * $PercentDepositValue : $value->{".valoru"};//PORCENVADEPO

                    $array["ValorEntradasRecargasAgentes"] = 0;

                    /* Inicializa un array con valores y saldo para un sistema financiero. */
                    $array["ValorEntradasTraslados"] = 0;
                    $array["ValorSalidasEfectivo"] = 0;
                    $array["ValorSalidasTraslados"] = 0;
                    $array["ValorSalidasNotasRetiro"] = 0;
                    $array["Saldo"] = $array["ValorEntradasRecargas"];
                    $array["Tax"] = 0;

                    /* asigna un valor a un arreglo y lo agrega a otro arreglo. */
                    $array["Partner"] = $value->{"usuario.mandante"};


                    array_push($final, $array);
                }


                /* Crea una instancia de CuentaCobro y procesa datos JSON de entrada. */
                $CuentaCobro = new CuentaCobro();

                $params = file_get_contents('php://input');
                $params = json_decode($params);

                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $ToDateLocal)));

                /* formatea una fecha y asigna un valor de fecha de un parámetro. */
                $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal)));
                //$Region = $params->Region;
                //$CurrencyId = $params->CurrencyId;
                //$IsNewRegistered = $params->IsNewRegistered;


                $ToDateLocal = $params->ToCreatedDateLocal;


                /* obtiene una fecha final ajustada por zona horaria a partir de una solicitud. */
                if ($_REQUEST["dateTo"] != "") {
                    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
                }


                $FromDateLocal = $params->FromCreatedDateLocal;


                /* verifica una fecha y la formatea según la zona horaria especificada. */
                if ($_REQUEST["dateFrom"] != "") {
                    $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
                }


                $MaxRows = $params->MaxRows;

                /* asigna valores de parámetros y asegura que SkeepRows tenga un valor numérico. */
                $OrderedItem = $params->OrderedItem;
                $SkeepRows = $params->SkeepRows;

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }


                /* establece valores predeterminados para variables si están vacías. */
                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000;
                }


                /* Se definen reglas de filtrado para una consulta, incluyendo estado y fechas. */
                $rules = [];
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
                array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "0", "op" => "ne"));
                array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));
                array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));

                if ($Region != "") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
                }


                /* Agrega reglas a un arreglo basadas en condiciones específicas de moneda y perfil. */
                if ($Currency != "") {
                    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
                }


                if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                    array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                }


                /* Agrega reglas según el perfil del usuario almacenado en sesión. */
                if ($_SESSION["win_perfil2"] == "CAJERO") {
                    array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                }


                if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                }


                /* agrega reglas de filtrado según el perfil y país del usuario. */
                if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                }

                // Si el usuario esta condicionado por País
                if ($_SESSION['PaisCond'] == "S") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                }

                // Si el usuario esta condicionado por el mandante y no es de Global

                /* Agrega reglas a un array según condiciones de sesión del usuario y mandante. */
                if ($_SESSION['Global'] == "N") {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
                } else {

                    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                    }

                }

                // Inactivamos reportes para el país Colombia

                /* Se crea un filtro de reglas y se obtienen cuentas de cobro personalizadas. */
                array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);


                $cuentas = $CuentaCobro->getCuentasCobroCustom("usuario.mandante,usuario.pais_id,pais.pais_nom,pais.iso,COUNT(*) count,SUM(cuenta_cobro.valor) valor,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d') fecha_crea,producto.descripcion,usuario.moneda", "cuenta_cobro.fecha_pago", "desc", $SkeepRows, $MaxRows, $json, true, "usuario.mandante,usuario.pais_id,transaccion_producto.producto_id,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d'),usuario.moneda");


                /* decodifica datos JSON y inicializa variables para gestionar retiros. */
                $cuentas = json_decode($cuentas);

                $valor_convertidoretiros = 0;
                $totalretiros = 0;
                foreach ($cuentas->data as $key => $value) {


                    /* Asigna una descripción predeterminada y construye una cadena en un array. */
                    $array = [];

                    if ($value->{"producto.descripcion"} == "") {
                        $value->{"producto.descripcion"} = "Fisicamente";
                    }

                    $array["Punto"] = "Cuentas - Giros - " . $value->{"producto.descripcion"} . '-' . $value->{"usuario.moneda"};


                    /* Asigna valores a un array basado en propiedades de un objeto. */
                    $array["Fecha"] = $value->{".fecha_crea"};
                    $array["MMoneda"] = $value->{"usuario.moneda"};
                    $array["Moneda"] = $value->{"usuario.moneda"};
                    $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"usuario.mandante"};
                    $array["CountryIcon"] = strtolower($value->{"pais.iso"});
                    $array["Agent"] = "Cuentas Bancarias y Giros - " . $value->{"usuario.moneda"};

                    /* Inicializa un array con valores cero para diferentes tipos de entradas y tickets. */
                    $array["CantidadTickets"] = 0;
                    $array["ValorEntradasEfectivo"] = 0;
                    $array["ValorEntradasBonoTC"] = 0;
                    $array["ValorEntradasRecargas"] = 0;
                    $array["ValorEntradasRecargasAgentes"] = 0;
                    $array["ValorEntradasTraslados"] = 0;

                    /* Calcula y actualiza valores de salidas y saldo en un arreglo. */
                    $array["ValorSalidasEfectivo"] = 0;
                    $array["ValorSalidasTraslados"] = 0;

                    $array["ValorSalidasNotasRetiro"] = isset($PercentRetirementValue) ? ($value->{".valor"} / 100) * $PercentRetirementValue : $value->{".valor"};//PORCENVARETR

                    $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"] + $array["ValorEntradasRecargasAgentes"];

                    /* asigna valores a un array y lo agrega a una colección final. */
                    $array["Tax"] = 0;
                    $array["Partner"] = $value->{"usuario.mandante"};


                    array_push($final, $array);


                }


            } else {
                /* El bloque "else" se usa para ejecutar código cuando la condición previa es falsa. */


            }
        }


        // $response["HasError"] = false;
        // $response["AlertType"] = "success";
        // $response["AlertMessage"] = "";
        // $response["ModelErrors"] = [];

        // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


        /* asigna valores a un arreglo de respuesta: posiciones, conteo total y datos. */
        $response["pos"] = $SkeepRows;
        $response["total_count"] = null;
        $response["data"] = $final;

    }

} catch (Exception $e) {
    /* Captura excepciones en PHP sin realizar ninguna acción específica en el bloque. */


}


/* Asignación de datos a un arreglo de respuesta en formato JSON. */
$response["pos"] = $SkeepRows;
$response["total_count"] = null;
$response["data"] = $final;
?>
