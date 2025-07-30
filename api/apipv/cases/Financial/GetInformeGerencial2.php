<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\BodegaInformeGerencial;
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
use PayPal\Api\Invoice;
use SendGrid\Exception\InvalidRequest;

/**
 *
 * @param string $dateFrom : Descripción: Fecha de inicio para el informe gerencial.
 * @param string $dateTo : Descripción: Fecha de fin para el informe gerencial.
 * @param int $PaymentSystemId : Descripción: Identificador del sistema de pago.
 * @param int $CashDeskId : Descripción: Identificador de la caja.
 * @param int $ClientId : Descripción: Identificador del cliente.
 * @param float $AmountFrom : Descripción: Monto mínimo para el informe gerencial.
 * @param float $AmountTo : Descripción: Monto máximo para el informe gerencial.
 * @param int $CurrencyId : Descripción: Identificador de la moneda.
 * @param string $ExternalId : Descripción: Identificador externo.
 * @param int $Id : Descripción: Identificador del informe gerencial.
 * @param bool $IsDetails : Descripción: Indicador para obtener información detallada.
 * @param int $CountrySelect : Descripción: Identificador del país seleccionado.
 * @param int $MaxRows : Descripción: Número máximo de filas a devolver.
 * @param int $OrderedItem : Descripción: Ítem ordenado.
 * @param int $SkeepRows : Descripción: Número de filas a omitir en la consulta.
 * @param int $TypeUser : Descripción: Tipo de usuario.
 * @param int $TypeBet : Descripción: Tipo de apuesta.
 * @param int $WalletId : Descripción: Identificador de la billetera.
 *
 * @Description Obtener los detalles para el informe gerencial.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos del informe gerencial.
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
 *
 */

/**
 * @OA\Post(path="apipv/Financial/GetInformeGerencial", tags={"Financial"}, description = "",
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
 *                   property="TypeUser",
 *                   description="",
 *                   type="integer",
 *                   example= "3"
 *               ),
 *               @OA\Property(
 *                   property="TypeBet",
 *                   description="",
 *                   type="integer",
 *                   example= "5"
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
 * )
 */


/* crea un objeto y obtiene una fecha en formato JSON desde la entrada. */
$PuntoVenta = new PuntoVenta();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->dateTo;


/* Convierte fechas de solicitud a formato local con ajuste de zona horaria y genera variables. */
$ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));

$FromDateLocal = $params->dateFrom;

$FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


$PaymentSystemId = $params->PaymentSystemId;

/* Asignación de parámetros a variables para procesamiento de transacciones. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* Asignación de variables y verificación de tipo de usuario en PHP. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;


if ($_REQUEST["TypeUser"] == 2) {
    $TypeUser = 2;
} else {
    /* Asignación de valor a $TypeUser basado en la condición de TypeUser en la solicitud. */

    if ($_REQUEST["TypeUser"] == 1) {
        $TypeUser = 1;
    } else {
        $TypeUser = '';

    }
}

/* Asigna valores a variables basadas en datos recibidos de una solicitud HTTP. */
$TypeBet = ($_REQUEST["TypeBet"] == 2) ? 2 : 1;
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';

$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* valida fechas recibidas y ajusta la variable $seguir según condiciones. */
$seguir = true;

if ($_REQUEST["dateFrom"] == "" || $_REQUEST["dateTo"] == "") {
    $seguir = false;
}

if ($FromDateLocal == "") {
    $seguir = false;


    $FromDateLocal = date("Y-m-d", strtotime(time() . $timezone . ' hour '));

}

/* establece `$ToDateLocal` como mañana si está vacío. */
if ($ToDateLocal == "") {
    $seguir = false;

    $ToDateLocal = date("Y-m-d", strtotime(time() . ' +1 day' . $timezone . ' hour '));


}

/* Se inicializan un arreglo vacío y una variable para el total acumulado. */
$final = [];
$totalm = 0;

if ($seguir) {

    /* Convierte fechas de entrada a formato "Y-m-d" considerando la zona horaria especificada. */
    $ToDateLocal = $params->dateTo;

    $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));

    $FromDateLocal = $params->dateFrom;

    $FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


    /* Se crean reglas para filtrar datos basados en condiciones específicas. */
    $rules = [];

    array_push($rules, array("field" => "bodega_informe_gerencial.fecha", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "bodega_informe_gerencial.fecha", "data" => "$ToDateLocal", "op" => "le"));

    // if ($CurrencyId != "") {
    //     array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    // }
    // if ($ExternalId != "") {
    //     array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
    // }
    // if ($Id != "") {
    //     array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
    // }

    if ($TypeBet == 2) {
        array_push($rules, array("field" => "bodega_informe_gerencial.tipo_fecha", "data" => "2", "op" => "eq"));

    } else {
        /* Agrega una regla al arreglo si no se cumple una condición previa. */

        array_push($rules, array("field" => "bodega_informe_gerencial.tipo_fecha", "data" => "1", "op" => "eq"));

    }


    /* agrega reglas basadas en el tipo de usuario. */
    if ($TypeUser == 2) {
        array_push($rules, array("field" => "bodega_informe_gerencial.tipo_usuario", "data" => "2", "op" => "eq"));

    } else if ($TypeUser == 1) {
        array_push($rules, array("field" => "bodega_informe_gerencial.tipo_usuario", "data" => "1", "op" => "eq"));

    }


    /* Asigna valor a $Pais según selección de país o condición de sesión. */
    if ($CountrySelect != "" && $CountrySelect != "0") {
        $Pais = $CountrySelect;
    }

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        $Pais = $_SESSION['pais_id'];
    }

    // Si el usuario esta condicionado por el mandante y no es de Global

    /* verifica condiciones de sesión para agregar reglas a un array. */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "bodega_informe_gerencial.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "bodega_informe_gerencial.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }


    /* Agrega una regla al array si el país no está vacío. */
    if ($Pais != "") {
        array_push($rules, array("field" => "bodega_informe_gerencial.pais_id", "data" => "$Pais", "op" => "eq"));
    }

    if (!empty($Pais) && !empty($FromDateLocal)) {

        /* Inicializa un objeto y extrae año y mes de una fecha dada. */
        $ModeloFiscal = new ModeloFiscal();
        $dateParts = explode('-', $FromDateLocal);
        $Year = $dateParts[0];
        $Mounth = $dateParts[1];

        $rules_modelo = [];


        /* crea reglas de filtro y las convierte en formato JSON. */
        array_push($rules_modelo, ['field' => 'modelo_fiscal.pais_id', 'data' => $Pais, 'op' => 'eq']);
        array_push($rules_modelo, ['field' => 'modelo_fiscal.estado', 'data' => 'A', 'op' => 'eq']);
        array_push($rules_modelo, ['field' => 'modelo_fiscal.mandante', 'data' => $_SESSION['mandante'], 'op' => 'eq']);
        array_push($rules_modelo, ['field' => 'modelo_fiscal.mes', 'data' => $Mounth, 'op' => 'eq']);
        array_push($rules_modelo, ['field' => 'modelo_fiscal.anio', 'data' => $Year, 'op' => 'eq']);

        $filtro_modelo = json_encode(['rules' => $rules_modelo, 'groupOp' => 'AND']);


        /* Obtiene y decodifica información fiscal personalizada en formato JSON. */
        $modeloFiscal = $ModeloFiscal->getModeloFiscalCustom(' modelo_fiscal.*, clasificador.*', 'modelo_fiscal.modelofiscal_id', 'asc', 0, 100000, $filtro_modelo, true);

        $modeloFiscal = json_decode($modeloFiscal);

        foreach ($modeloFiscal->data as $key => $value) {
            switch ($value->{'clasificador.abreviado'}) {
                case 'PORCENVADEPO':
                    /* Asigna el valor entero de 'modelo_fiscal.valor' a $PercentDepositValue. */

                    $PercentDepositValue = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVARETR':
                    /* Asignación del valor de retiro percentual a una variable, convirtiéndolo a entero. */

                    $PercentRetirementValue = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVAAPUESDEPOR':
                    /* Asigna un valor entero a la variable PercentValueSportsBets desde el modelo fiscal. */

                    $PercentValueSportsBets = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVAAPUESNODEPOR':
                    /* Asignación del valor entero de un modelo fiscal a una variable para apuestas no deportivas. */

                    $PercentValueNonSportBets = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVAPREMDEPOR':
                    /* Asignación de valor entero a variable de porcentaje para premios deportivos. */

                    $PercentValueSportsAwards = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVAPREMNODEPOR':
                    /* Asigna un valor entero a la variable de porcentaje no deportivo. */

                    $PercentValueNonSportsAwards = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVABONDEPOR':
                    /* Se obtiene un valor entero de "modelo_fiscal.valor" para la variable "PercentValueSportsBonds". */

                    $PercentValueSportsBonds = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVABONNODEPOR':
                    /* Asigna el valor entero de un campo a una variable según un caso específico. */

                    $PercentValueNonSportsBounds = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVATICKET':
                    /* Asigna un valor entero a $PercentValueTickets desde el objeto $value. */

                    $PercentValueTickets = intval($value->{'modelo_fiscal.valor'});
                    break;
                default:
                    break;
            }
        }
    }


    /* Agrega una regla para filtrar por país en un reporte gerencial. */
    array_push($rules, array("field" => "bodega_informe_gerencial.pais_id", "data" => "1", "op" => "ne"));

    $grouping = "";
    $select = "";
    if ($IsDetails) {

    } else {
        /* define variables para agrupar y seleccionar datos de un informe gerencial. */

        $grouping = " bodega_informe_gerencial.mandante,bodega_informe_gerencial.pais_id,bodega_informe_gerencial.fecha ";
        $select = "bodega_informe_gerencial.fecha,SUM(bodega_informe_gerencial.cantidad) cantidad,SUM(bodega_informe_gerencial.saldo_apuestas) saldo_apuestas,SUM(bodega_informe_gerencial.saldo_premios) saldo_premios,SUM(bodega_informe_gerencial.usuarios_registrados) usuarios_registrados,SUM(bodega_informe_gerencial.primeros_depositos) primeros_depositos,SUM(bodega_informe_gerencial.saldo_bono) saldo_bono,bodega_informe_gerencial.mandante,pais.* ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));

    }


    /* Configura un filtro para reglas y maneja valores por defecto de filas y orden. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Establece un valor predeterminado y convierte un filtro a formato JSON. */
    if ($MaxRows == "") {
        $MaxRows = 5;
    }

    $json = json_encode($filtro);

    $BodegaInformeGerencial = new BodegaInformeGerencial();

    /* Se obtienen y decodifican transacciones desde un informe gerencial personalizado. */
    $transacciones = $BodegaInformeGerencial->getBodegaInformeGerencialCustom($select, "bodega_informe_gerencial.fecha", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);


    $transacciones = json_decode($transacciones);

    foreach ($transacciones->data as $key => $value) {

        /* inicializa un arreglo con información sobre país y fecha. */
        $array = [];

        $array["Pais"] = (new ConfigurationEnvironment())->quitar_tildes($value->{"pais.pais_nom"});
        $array["CountryIcon"] = strtolower($value->{"pais.iso"});

        if ($TypeBet == 2) {
            $array["Fecha"] = $value->{"bodega_informe_gerencial.fecha"};

        } else {
            /* Asignación de una fecha a un array desde un objeto en PHP. */

            $array["Fecha"] = $value->{"bodega_informe_gerencial.fecha"};

        }


        /* asigna valores calculados a un array basado en propiedades de un objeto. */
        $array["Moneda"] = $value->{"pais.moneda"};
        $array["CantidadTickets"] = isset($PercentValueTickets) ? ($value->{".cantidad"} / 100) * $PercentValueTickets : $value->{'.cantidad'};//PORCENVATICKET

        $array["Stake"] = isset($PercentValueSportsBets) ? ($value->{".saldo_apuestas"} / 100) * $PercentValueSportsBets : $value->{'.saldo_apuestas'};//PORCENVAAPUESDEPOR
        $array["StakePromedio"] = 0;

        $array["Payout"] = isset($PercentValueSportsAwards) ? ($value->{".saldo_premios"} / 100) * $PercentValueSportsAwards : $value->{'.saldo_premio'};//PORCENVAPREMDEPOR


        /* asigna valores de un objeto a un array, calculando bonos y GGR. */
        $array["UsersRegistered"] = $value->{".usuarios_registrados"};
        $array["FirstDeposits"] = $value->{".primeros_depositos"};

        $array["Bonos"] = isset($PercentValueSportsBonds) ? ($value->{'.saldo_bono'} / 100) * $PercentValueSportsBonds : (empty($value->{'.saldo_bonos'}) ? 0 : $value->{'.saldo_bonos'});//PORCENVABONDEPOR

        $array["Ggr"] = $array["Stake"] - $array["Payout"] - $array["Bonos"];

        /* Calcula el porcentaje de ganancias y guarda el socio en un arreglo final. */
        $array["GgrPorc"] = ($array["Ggr"] / $array["Stake"]) * 100;

        $array["Partner"] = $value->{"bodega_informe_gerencial.mandante"};

        array_push($final, $array);
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    /* asigna valores a un array de respuesta en PHP. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = oldCount($final);
    $response["data"] = $final;
} else {
    /* inicializa una respuesta vacía cuando no se cumple una condición. */

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = [];
}

if ($seguir && date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour ')) == date("Y-m-d", time())) {

    /* Se crea un array de reglas para filtrar datos según condiciones específicas. */
    $rules = [];
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

    if ($PaymentSystemId != "") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
    }


    /* agrega reglas de filtrado basadas en ID de caja y cliente. */
    if ($CashDeskId != "") {
        array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
    }
    if ($ClientId != "") {
        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }


    /* Agrega reglas de filtrado basadas en valores de recarga definidos por el usuario. */
    if ($AmountFrom != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
    }
    if ($AmountTo != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
    }


    /* Agrega reglas de filtrado según campos de moneda y ID externo si no están vacíos. */
    if ($CurrencyId != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }
    if ($ExternalId != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
    }

    /* añade reglas dependiendo de condiciones sobre variables `$Id` y `$CountrySelect`. */
    if ($Id != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
    }

    if ($CountrySelect != "" && $CountrySelect != "0") {
        $Pais = $CountrySelect;
    }


    /* define condiciones para agrupar datos en una consulta SQL. */
    $grouping = "";
    $select = "";
    if ($IsDetails) {

    } else {
        $grouping = " usuario_recarga.puntoventa_id,producto.producto_id ";
        $select = "SUM(usuario_recarga.valor) valoru,SUM(transaccion_producto.valor) valor, ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));

    }


    /* Se establece un filtro y se inicializan variables para procesamiento de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Establece un máximo de filas y verifica la condición del país del usuario. */
    if ($MaxRows == "") {
        $MaxRows = 5;
    }

    $json = json_encode($filtro);

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        $Pais = $_SESSION['pais_id'];
    }

    // Si el usuario esta condicionado por el mandante y no es de Global

    /* asigna un valor a $Mandante según la sesión activa. */
    if ($_SESSION['Global'] == "N") {
        $Mandante = strtolower($_SESSION['mandante']);
    } else {
        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            $Mandante = $_SESSION["mandanteLista"];
        }

    }


    /* Obtiene transacciones en un rango de fechas y las convierte a formato JSON. */
    $FromDateLocal = date("Y-m-d");
    $ToDateLocal = date("Y-m-d");


    $transacciones = $PuntoVenta->getInformeGerencial($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $TypeBet, $Pais, $Mandante, $TypeUser);


    $transacciones = json_decode($transacciones);

    foreach ($transacciones->data as $key => $value) {

        /* Se crea un array con información de país y fecha, dependiendo del tipo de apuesta. */
        $array = [];

        $array["Pais"] = (new ConfigurationEnvironment())->quitar_tildes($value->{"x.pais_nom"});
        $array["CountryIcon"] = strtolower($value->{"x.pais_iso"});

        if ($TypeBet == 2) {
            $array["Fecha"] = $value->{"x.fecha_cierre"};

        } else {
            /* Asigna la fecha de creación a un array si no se cumple una condición previa. */

            $array["Fecha"] = $value->{"x.fecha_crea"};

        }

        /* asigna valores a un array basado en condiciones y porcentajes. */
        $array["Moneda"] = $value->{"x.moneda"};

        $array["CantidadTickets"] = isset($PercentValueTickets) ? ($value->{"x.cant_tickets"} / 100) * $PercentValueTickets : $value->{"x.cant_tickets"};//PORCENVATICKET

        $array["Stake"] = isset($PercentValueSportsBets) ? ($value->{"x.valor_apostado"} / 100) * $PercentValueSportsBets : $value->{"x.valor_apostado"};//PORCENVAAPUESDEPOR

        $array["StakePromedio"] = isset($PercentValueSportsBets) ? ($value->{"x.valor_ticket_prom"} / 100) * $PercentValueSportsBets : $value->{"x.valor_ticket_prom"};//PORCENVAAPUESDEPOR


        /* asigna valores a un arreglo basado en condiciones y propiedades de un objeto. */
        $array["Payout"] = isset($PercentValueSportsAwards) ? ($value->{"x.valor_premios"} / 100) * $PercentValueSportsAwards : $value->{"x.valor_premios"};//PORCENVAPREMDEPOR

        $array["UsersRegistered"] = $value->{"pl3.registros"};
        $array["FirstDeposits"] = $value->{"pl4.primerdepositos"};
        $array["Partner"] = $value->{"x.mandante"};

        $array["Bonos"] = isset($PercentValueSportsBonds) ? ($value->{"pl2.bonos"} / 100) * $PercentValueSportsBonds : (empty($value->{"pl2.bonos"}) ? 0 : $value->{"pl2.bonos"});//PORCENVABONDEPOR

        // $array["BonosCasino"] = isset($PercentValueNonSportsBounds) ? ($value->{"pl2.bonoscasino"} / 100) * $PercentValueNonSportsBounds : (empty($value->{"pl2.bonoscasino"}) ? 0 : $value->{"pl4.bonoscasino"});//PORCENVABONNODEPOR


        /* Calcula el GGR y su porcentaje, luego lo agrega a un arreglo final. */
        $array["Ggr"] = $array["Stake"] - $array["Payout"] - $array["Bonos"];
        $array["GgrPorc"] = ($array["Ggr"] / ($array["Stake"] > 0 ? $array['Stake'] : 1)) * 100;


        array_push($final, $array);
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);

    // $response["pos"] = $SkeepRows;
    // $response["total_count"] = $transacciones->count[0]->{".count"};
    // $response["data"] = $final;
}


/* inicializa una respuesta con posición, recuento total y datos finales. */
$response["pos"] = 0;
$response["total_count"] = oldCount($final);
$response["data"] = $final;

?>
