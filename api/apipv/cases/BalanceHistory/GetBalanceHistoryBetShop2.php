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


/**
 * Procesa la solicitud de historial de balance de una tienda de apuestas.
 *
 * @param object $params Parámetros de entrada decodificados desde JSON, incluyendo:
 * @param int $params ->IsDetails Indica si se deben incluir detalles adicionales.
 * @param string $params ->CurrencyId Identificador de la moneda.
 * @param string $params ->IsTest Indica si es una prueba.
 * @param string $params ->ProductId Identificador del producto.
 * @param string $params ->ProviderId Identificador del proveedor.
 * @param string $params ->Region Región del usuario.
 * @param int $params ->MaxRows Número máximo de filas a devolver.
 * @param string $params ->OrderedItem Elemento ordenado.
 * @param int $params ->SkeepRows Número de filas a omitir.
 * @param string $params ->dateTo Fecha de fin en formato local.
 * @param string $params ->dateFrom Fecha de inicio en formato local.
 *
 * @return array $response Respuesta del procesamiento, incluyendo:
 * - bool $HasError Indica si hubo un error.
 * - string $AlertType Tipo de alerta.
 * - string $AlertMessage Mensaje de alerta.
 * - array $ModelErrors Errores del modelo.
 * - int $pos Posición de las filas.
 * - int $total_count Número total de registros.
 * - array $data Datos procesados.
 *
 * @throws Exception Si ocurre un error durante el procesamiento.
 */

/**
 * @OA\Post(path="apipv/BalanceHistory/GetBalanceHistoryBetShop", tags={"BalanceHistory"}, description = "",
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
 *              @OA\Property(
 *                   property="PlayerId",
 *                   description="",
 *                   type="integer",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="UserId",
 *                   description="",
 *                   type="integer",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="ProviderId",
 *                   description="",
 *                   type="integer",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="IsDetails",
 *                   description="",
 *                   type="integer",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="Type",
 *                   description="",
 *                   type="string",
 *                   example= "desc"
 *               ),
 *              @OA\Property(
 *                   property="dateTo",
 *                   description="",
 *                   type="string",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="Dir",
 *                   description="Dir",
 *                   type="integer",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="sort",
 *                  @OA\Items(),
 *                   description="sort",
 *                   type="array",
 *                   example= ""
 *               ),
 *
 *              @OA\Property(
 *                   property="dateFrom",
 *                   description="dateFrom",
 *                   type="string",
 *                   example= "2020-09-25 00:00:00"
 *               ),
 *
 *              @OA\Property(
 *                   property="CurrencyId",
 *                   description="CurrencyId",
 *                   type="string",
 *                   example= ""
 *               ),
 *
 *              @OA\Property(
 *                   property="IsTest",
 *                   description="IsTest",
 *                   type="string",
 *                   example= ""
 *               ),
 *
 *              @OA\Property(
 *                   property="ProductId",
 *                   description="ProductId",
 *                   type="string",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="Region",
 *                   description="Region",
 *                   type="string",
 *                   example= ""
 *               ),
 *              @OA\Property(
 *                   property="CountrySelect",
 *                   description="CountrySelect",
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
 *                   property="HasError",
 *                   description="Hay error",
 *                   type="boolean",
 *                   example= false
 *               ),
 *               @OA\Property(
 *                   property="AlertType",
 *                   description="Mensaje de la API",
 *                   type="string",
 *                   example= "success"
 *               ),
 *               @OA\Property(
 *                   property="AlertMessage",
 *                   description="Mensaje con el error especifico",
 *                   type="string",
 *                   example= "0"
 *               ),
 *               @OA\Property(
 *                   property="ModelErrors",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
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
 *      )
 * )
 */


/* Se crea un nuevo objeto Usuario y se decodifican parámetros JSON desde la entrada. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);
$IsDetails = $params->IsDetails;
$CurrencyId = $params->CurrencyId;

/* asigna valores de parámetros a variables en un script. */
$IsTest = $params->IsTest;
$ProductId = $params->ProductId;
$ProviderId = $params->ProviderId;
$Region = $params->Region;

$MaxRows = $params->MaxRows;

/* asigna parámetros de entrada a variables en un script. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;
$ToDateLocal = $params->dateTo;

$BetShopOwn = $params->BetShopOwn;


$FromDateLocal = $params->dateFrom;


/* procesa fechas de entrada y las convierte a formato local. */
if ($_REQUEST["dateFrom"] != "") {
    //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
    $FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));

}
if ($_REQUEST["dateTo"] != "") {
    //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
    $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));

}


/* obtiene y valida datos de solicitud relacionados con un jugador y su identificación. */
$PlayerId = $_REQUEST['PlayerId'];
$UserId = $_REQUEST['UserId'];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';

$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';
$ProductId = $_REQUEST["ProductId"];

/* obtiene parámetros de solicitud HTTP para procesar apuestas. */
$IsDetails = $_REQUEST["IsDetails"];
$Type = $_REQUEST["Type"];
$BetShopOwn = $_REQUEST["BetShopOwn"];

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* verifica condiciones y establece variables dependiendo de su contenido. */
$seguir = true;

if ($SkeepRows == "") {
    $seguir = false;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* verifica si $MaxRows está vacío y establece $seguir en falso. */
if ($MaxRows == "") {
    $seguir = false;
}

if ($seguir) {


    /* alterna el valor de $IsDetails entre true y false. */
    if ($IsDetails == 1) {
        $IsDetails = false;

    } else {
        $IsDetails = true;
    }

    if (!empty($CountrySelect) && !empty($FromDateLocal)) {

        /* Separa una fecha en año y mes para crear reglas de modelo fiscal. */
        $ModeloFiscal = new ModeloFiscal();
        $dateParts = explode('-', $FromDateLocal);
        $Year = $dateParts[0];
        $Mounth = $dateParts[1];

        $model_rules = [];


        /* Se crean reglas de filtro para un modelo fiscal y se codifican en JSON. */
        array_push($model_rules, ['field' => 'modelo_fiscal.pais_id', 'data' => $CountrySelect, 'op' => 'eq']);
        array_push($model_rules, ['field' => 'modelo_fiscal.estado', 'data' => 'A', 'op' => 'eq']);
        array_push($model_rules, ['field' => 'modelo_fiscal.mandante', 'data' => $_SESSION['mandante'], 'op' => 'eq']);
        array_push($model_rules, ['field' => 'modelo_fiscal.mes', 'data' => $Mounth, 'op' => 'eq']);
        array_push($model_rules, ['field' => 'modelo_fiscal.anio', 'data' => $Year, 'op' => 'eq']);

        $filtro_modelo = json_encode(['rules' => $model_rules, 'groupOp' => 'AND']);


        /* Se obtiene y decodifica un modelo fiscal personalizado en formato JSON. */
        $modeloFiscal = $ModeloFiscal->getModeloFiscalCustom(' modelo_fiscal.*, clasificador.*', 'modelo_fiscal.modelofiscal_id', 'asc', 0, 100000, $filtro_modelo, true);

        $modeloFiscal = json_decode($modeloFiscal);

        foreach ($modeloFiscal->data as $key => $value) {
            switch ($value->{'clasificador.abreviado'}) {
                case 'PORCENVADEPO':
                    /* Asigna un valor entero a $PercentDepositValue desde un objeto llamado $value. */

                    $PercentDepositValue = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVARETR':
                    /* Asigna el valor entero del modelo fiscal a $PercentRetirementValue. */

                    $PercentRetirementValue = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVAAPUESDEPOR':
                    /* Asigna un valor entero de un modelo fiscal a una variable específica. */

                    $PercentValueSportsBets = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVAAPUESNODEPOR':
                    /* Asigna un valor entero a la variable de apuestas no deportivas en base a un modelo fiscal. */

                    $PercentValueNonSportBets = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVAPREMDEPOR':
                    /* Asigna un valor entero de 'modelo_fiscal.valor' a la variable $PercentValueSportsAwards. */

                    $PercentValueSportsAwards = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVAPREMNODEPOR':
                    /* Asigna un valor entero a la variable para un caso específico en un switch. */

                    $PercentValueNonSportsAwards = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVABONDEPOR':
                    /* Asigna un valor porcentual a una variable según un modelo fiscal específico. */

                    $PercentValueSportsBonds = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVABONNODEPOR':
                    /* Se asigna un valor entero a una variable basada en un modelo fiscal específico. */

                    $PercentValueNonSportsBounds = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVATICKET':
                    /* Asigna el valor del modelo fiscal a la variable PercentValueTickets si es 'PORCENVATICKET'. */

                    $PercentValueTickets = intval($value->{'modelo_fiscal.valor'});
                    break;
                default:
                    break;
            }
        }
    }


    /* genera reglas para filtrar fechas según condiciones específicas. */
    $rules = [];

    if ($FromDateLocal == $ToDateLocal && $FromDateLocal != "") {
        array_push($rules, array("field" => "usuario_saldo.fecha", "data" => "$FromDateLocal ", "op" => "eq"));
    } else {
        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "usuario_saldo.fecha", "data" => "$FromDateLocal ", "op" => "ge"));

        }

        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "usuario_saldo.fecha", "data" => "$ToDateLocal", "op" => "le"));
        }

    }


    /* agrega reglas a un arreglo si las condiciones son verdaderas. */
    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }

    if ($PlayerId != "") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => "$PlayerId", "op" => "eq"));

    }


    /* Agrega condiciones a un array de reglas basado en variables dadas. */
    if ($UserId != "") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => "$UserId", "op" => "eq"));

    }

    if ($BetShopOwn == 'S') {
        array_push($rules, array("field" => "punto_venta.propio", "data" => "S", "op" => "eq"));
    }


    /* Agrega reglas a un arreglo basadas en condiciones de propiedad y país seleccionado. */
    if ($BetShopOwn == 'N') {
        array_push($rules, array("field" => "punto_venta.propio", "data" => "N", "op" => "eq"));

    }


    if ($CountrySelect != "" && $CountrySelect != "0") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
    }

    // Si el usuario esta condicionado por País

    /* agrega reglas basadas en condiciones de sesión del usuario. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Verifica la sesión y añade reglas basadas en "mandanteLista". */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }


    /* agrega reglas de usuario según su perfil en la sesión activa. */
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));

    }

    if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));

    }


    /* Se añaden reglas basadas en el perfil del usuario a las sesiones. */
    if ($_SESSION["win_perfil2"] == "CAJERO") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));

    }


    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));

    }

    // Inactivamos reportes para el país Colombia

    /* Se añaden reglas de filtrado para usuarios y perfiles a un array. */
    array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'PUNTOVENTA','CAJERO'", "op" => "in"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte un array o objeto PHP en formato JSON. */
    $json = json_encode($filtro);


    $select = "
       SUM(saldo_inicial) saldo_inicial,
              SUM(saldo_creditos_inicial) saldo_creditos_inicial,
       SUM(saldo_creditos_base_inicial) saldo_creditos_base_inicial,


       SUM(saldo_recarga) saldo_recarga,
       SUM(saldo_notaret_creadas) saldo_notaret_creadas,
       SUM(saldo_notaret_eliminadas) saldo_notaret_eliminadas,
       SUM(saldo_notaret_pagadas) saldo_notaret_pagadas,
       SUM(saldo_notaret_pend) saldo_notaret_pend,
       SUM(saldo_notaret_pagadas) saldo_notaret_pagadas,
       
       SUM(saldo_apuestas) saldo_apuestas,
       SUM(saldo_premios) saldo_premios,
       SUM(saldo_apuestas_casino) saldo_apuestas_casino,
       SUM(saldo_premios_casino) saldo_premios_casino,
       SUM(saldo_bono) saldo_bono,
       SUM(saldo_bono_free_ganado) saldo_bono_free_ganado,
       SUM(saldo_ajustes_entrada) saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida) saldo_ajustes_salida,
       
       SUM(saldo_final) saldo_final,
       
       SUM(saldo_creditos_base_final) saldo_creditos_base_final,
       (saldo_final) saldo_final,
              (saldo_inicial) - (saldo_recarga) + saldo_notaret_pagadas - saldo_apuestas + saldo_premios - saldo_apuestas_casino + saldo_premios_casino - saldo_notaret_creadas + saldo_notaret_eliminadas + saldo_ajustes_entrada - saldo_ajustes_salida + saldo_notaret_pend desfase,
       SUM(saldo_creditos_final) saldo_creditos_final,usuario.nombre,pais.iso,usuario_punto_venta.nombre,usuario_punto_venta.usuario_id,usuario_saldo.fecha ";


    /* Define agrupamiento y ordenamiento de datos por fecha y punto de venta. */
    $grouping = "usuario_saldo.fecha,usuario.puntoventa_id";


    $order = "usuario_saldo.fecha";
    $orderType = "asc";
    if ($Type == 1) {
        $select = "
                    pais.iso,
                    usuario_saldo.fecha,
       SUM(saldo_inicial) saldo_inicial,
              SUM(saldo_creditos_inicial) saldo_creditos_inicial,
       SUM(saldo_creditos_base_inicial) saldo_creditos_base_inicial,


       SUM(saldo_recarga) saldo_recarga,
       SUM(saldo_notaret_creadas) saldo_notaret_creadas,
       SUM(saldo_notaret_eliminadas) saldo_notaret_eliminadas,
       SUM(saldo_notaret_pagadas) saldo_notaret_pagadas,
       SUM(saldo_notaret_pend) saldo_notaret_pend,
       SUM(saldo_notaret_pagadas) saldo_notaret_pagadas,
       
       SUM(saldo_apuestas) saldo_apuestas,
       SUM(saldo_premios) saldo_premios,
       SUM(saldo_apuestas_casino) saldo_apuestas_casino,
       SUM(saldo_premios_casino) saldo_premios_casino,
       SUM(saldo_bono) saldo_bono,
       SUM(saldo_bono_free_ganado) saldo_bono_free_ganado,
       SUM(saldo_ajustes_entrada) saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida) saldo_ajustes_salida,
       
       SUM(saldo_final) saldo_final,
       
       SUM(saldo_creditos_base_final) saldo_creditos_base_final,
       
       SUM(saldo_creditos_final) saldo_creditos_final
       
       ";


        /* Agrupa datos por país y fecha de saldo del usuario en la consulta. */
        $grouping = "usuario_punto_venta.pais_id,usuario_saldo.fecha";
    } else {
        /* Configura el orden de los resultados como "desfase" en orden descendente. */

        $order = " desfase ";
        $orderType = "desc";

    }


    /* Ordena resultados según montos de ganancias o depósitos, en orden ascendente o descendente. */
    if ($_REQUEST["sort[AmountWin]"] != "") {
        $order = "saldo_premios";
        $orderType = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

    }


    if ($_REQUEST["sort[AmountDeposits]"] != "") {
        $order = "saldo_recarga";
        $orderType = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";
    }


    /* Se obtiene el saldo de usuarios, se decodifica y se prepara para uso. */
    $UsuarioSaldo = new UsuarioSaldo();
    $data = $UsuarioSaldo->getUsuarioSaldosCustom($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping, '1');
    $data = json_decode($data);


    $final = [];


    /* Inicializa contadores para apuestas, premios y un contador en cero. */
    $papuestas = 0;
    $ppremios = 0;
    $pcont = 0;

    foreach ($data->data as $key => $value) {

        if ($Type == 0) {


            /* asigna datos de un objeto a un array asociativo. */
            $array["Id"] = $value->{"usuario_saldo.ususaldo_id"};
            $array["UserId"] = $value->{"usuario_punto_venta.usuario_id"};

            $array["Country"] = strtolower($value->{"pais.iso"});
            $array["UserName"] = $value->{"usuario_punto_venta.nombre"};
            $array["CreatedLocalDate"] = $value->{"usuario_saldo.fecha"};


            /* redondea y almacena saldos en un array asociativo. */
            $array["InitialRechargeQuota"] = round($value->{".saldo_creditos_inicial"}, 2);
            $array["InitialGameQuota"] = round($value->{".saldo_creditos_base_inicial"}, 2);
            $array["EndGameQuota"] = round($value->{".saldo_creditos_base_final"}, 2);
            $array["EndRechargeQuota"] = round($value->{".saldo_creditos_final"}, 2);

            $array["BalanceInitial"] = round($value->{".saldo_inicial"}, 2);


            /* Calcula y almacena montos de depósitos, apuestas y premios, considerando porcentajes opcionales. */
            $AmountDeposits = round($value->{".saldo_recarga"}, 2);
            $array["AmountDeposits"] = isset($PercentDepositValue) ? ($AmountDeposits / 100) * $PercentDepositValue : $AmountDeposits;//PORCENVADEPO

            $AmountBets = round($value->{".saldo_apuestas"}, 2);
            $array["AmountBets"] = isset($PercentValueSportsBets) ? ($AmountBets / 100) * $PercentValueSportsBets : $AmountBets;//PORCENVAAPUESDEPOR

            $AmountWin = round($value->{".saldo_premios"}, 2);

            /* Calcula montos ganados y apostados en deportes y casinos, ajustando por porcentajes. */
            $array["AmountWin"] = isset($PercentValueSportsAwards) ? ($AmountWin / 100) * $PercentValueSportsAwards : $AmountWin;//PORCENVAPREMDEPOR

            $AmountBetsCasino = round($value->{".saldo_apuestas_casino"}, 2);
            $array["AmountBetsCasino"] = isset($PercentValueNonSportBets) ? ($AmountBetsCasino / 100) * $PercentValueNonSportBets : $AmountBetsCasino;//PORCENVAAPUESNODEPOR

            $AmountWinCasino = round($value->{".saldo_premios_casino"}, 2);

            /* Calcula montos basados en porcentajes para ganancias y retiros de casino. */
            $array["AmountWinCasino"] = isset($PercentValueNonSportsAwards) ? ($AmountWinCasino / 100) * $PercentValueNonSportsAwards : $AmountWinCasino;//PORCENVAPREMNODEPOR

            $WithdrawCreates = round($value->{".saldo_notaret_creadas"}, 2);
            $array["WithdrawCreates"] = isset($PercentRetirementValue) ? ($WithdrawCreates / 100) * $PercentRetirementValue : $WithdrawCreates;//PORCENVARETR

            $WithdrawPaid = round($value->{".saldo_notaret_pagadas"}, 2);

            /* Calcula y almacena valores de retiros y ajustes basados en un porcentaje. */
            $array["WithdrawPaid"] = isset($PercentRetirementValue) ? ($WithdrawPaid / 100) * $PercentRetirementValue : $WithdrawPaid;//PORCENVARETR

            $WithdrawPend = round($value->{".saldo_notaret_pend"}, 2);
            $array["WithdrawPend"] = isset($PercentRetirementValue) ? ($WithdrawPend / 100) * $PercentRetirementValue : $WithdrawPend;//PORCENVARETR

            $array["AdjustmentE"] = round($value->{".saldo_ajustes_entrada"}, 2);

            /* calcula y redondea ajustes, bonos y saldo final en un array. */
            $array["AdjustmentS"] = round($value->{".saldo_ajustes_salida"}, 2);

            $Bonus = round($value->{".saldo_bono"}, 2);
            $array["Bonus"] = isset($PercentValueSportsBonds) ? ($Bonus / 100) * $PercentValueSportsBonds : $Bonus;//PORCENVABONDEPOR

            $array["BalanceEnd"] = round($value->{".saldo_final"}, 2);

            /* realiza cálculos financieros y asignaciones en un array utilizando valores redondeados. */
            $array["BalanceEnd"] = round($value->{".saldo_creditos_base_final"} + $value->{".saldo_creditos_final"}, 2);

            $BonusFreeWin = round($value->{".saldo_bono_free_ganado"}, 2);
            $array["BonusFreeWin"] = isset($PercentValueSportsBonds) ? ($BonusFreeWin / 100) * $PercentValueSportsBonds : $BonusFreeWin;//PORCENVABONDEPOR

            $WithdrawDeletes = -round($value->{".saldo_notaret_eliminadas"}, 2);

            /* Calcula saldos y cuotas finales utilizando diversas transacciones financieras almacenadas en un array. */
            $array["WithdrawDeletes"] = isset($PercentRetirementValue) ? ($WithdrawDeletes / 100) * $PercentRetirementValue : $WithdrawDeletes;//PORCENVARETR

            $array["BalanceEndCalc"] = round($array["BalanceInitial"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["WithdrawPend"], 2);
            $array["EndRechargeQuotaCalc"] = round($array["InitialRechargeQuota"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"], 2);
            $array["EndGameQuotaCalc"] = round($array["InitialGameQuota"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"], 2);


            array_push($final, $array);

        } else {

            /* Se crea un array con información de usuario y saldo inicial desde un objeto. */
            $array["Id"] = 0;
            $array["UserId"] = 0;
            $array["Country"] = strtolower($value->{"pais.iso"});
            $array["UserName"] = '';
            $array["CreatedLocalDate"] = $value->{"usuario_saldo.fecha"};

            $array["InitialRechargeQuota"] = round($value->{".saldo_creditos_inicial"}, 2);

            /* Asigna valores redondeados a un array desde propiedades de un objeto. */
            $array["InitialGameQuota"] = round($value->{".saldo_creditos_base_inicial"}, 2);
            $array["EndGameQuota"] = round($value->{".saldo_creditos_base_final"}, 2);
            $array["EndRechargeQuota"] = round($value->{".saldo_creditos_final"}, 2);

            $array["BalanceInitial"] = round($value->{".saldo_inicial"}, 2);

            $AmountDeposits = round($value->{".saldo_recarga"}, 2);

            /* Calcula montos de depósitos y apuestas aplicando porcentajes condicionalmente. */
            $array["AmountDeposits"] = isset($PercentDepositValue) ? ($AmountDeposits / 100) * $PercentDepositValue : $AmountDeposits;//PORCENVADEPO

            $AmountBets = round($value->{".saldo_apuestas"}, 2);
            $array["AmountBets"] = isset($PercentValueSportsBets) ? ($AmountBets / 100) * $PercentValueSportsBets : $AmountBets;//PORCENVAAPUESDEPOR

            $AmountWin = round($value->{".saldo_premios"}, 2);

            /* Calcula montos ganados y apostados en deportes y casino basados en porcentajes. */
            $array["AmountWin"] = isset($PercentValueSportsAwards) ? ($AmountWin / 100) * $PercentValueSportsAwards : $AmountWin;//PORCENVAPREMDEPOR

            $AmountBetsCasino = round($value->{".saldo_apuestas_casino"}, 2);
            $array["AmountBetsCasino"] = isset($PercentValueNonSportBets) ? ($AmountBetsCasino / 100) * $PercentValueNonSportBets : $AmountBetsCasino;//PORCENVAAPUESNODEPOR

            $AmountWinCasino = round($value->{".saldo_premios_casino"}, 2);

            /* Cálculo de montos en función de porcentajes, con valores seguros por defecto. */
            $array["AmountWinCasino"] = isset($PercentValueNonSportsAwards) ? ($AmountWinCasino / 100) * $PercentValueNonSportsAwards : $AmountWinCasino;//PORCENVAPREMNODEPOR

            $WithdrawCreates = round($value->{".saldo_notaret_creadas"}, 2);
            $array["WithdrawCreates"] = isset($PercentRetirementValue) ? ($WithdrawCreates / 100) * $PercentRetirementValue : $WithdrawCreates;//PORCENVARETR

            $WithdrawPaid = round($value->{".saldo_notaret_pagadas"}, 2);

            /* Calcula y almacena valores relacionados con retiros y ajustes en un array. */
            $array["WithdrawPaid"] = isset($PercentRetirementValue) ? ($WithdrawPaid / 100) * $PercentRetirementValue : $WithdrawPaid;//PORCENVARETR

            $WithdrawPend = round($value->{".saldo_notaret_pend"}, 2);
            $array["WithdrawPend"] = isset($PercentRetirementValue) ? ($WithdrawPend / 100) * $PercentRetirementValue : $WithdrawPend;//PORCENVARETR

            $array["AdjustmentE"] = round($value->{".saldo_ajustes_entrada"}, 2);

            /* calcula y almacena ajustes, bonos y saldo final redondeados. */
            $array["AdjustmentS"] = round($value->{".saldo_ajustes_salida"}, 2);

            $Bonus = round($value->{".saldo_bono"}, 2);
            $array["Bonus"] = isset($PercentValueSportsBonds) ? ($Bonus / 100) * $PercentValueSportsBonds : $Bonus;//PORCENVABONDEPOR

            $array["BalanceEnd"] = round($value->{".saldo_final"}, 2);

            /* Calcula y asigna saldo final, bonificaciones y retiros a un array. */
            $array["BalanceEnd"] = round($value->{".saldo_creditos_base_final"} + $value->{".saldo_creditos_final"}, 2);

            $BonusFreeWin = round($value->{".saldo_bono_free_ganado"}, 2);
            $array["BonusFreeWin"] = isset($PercentValueSportsBonds) ? ($BonusFreeWin / 100) * $PercentValueSportsBonds : $BonusFreeWin;//PORCENVABONDEPOR

            $WithdrawDeletes = -round($value->{".saldo_notaret_eliminadas"}, 2);

            /* Calcula balances y cuotas finales actualizando valores según diversas transacciones. */
            $array["WithdrawDeletes"] = isset($PercentRetirementValue) ? ($WithdrawDeletes / 100) * $PercentRetirementValue : $WithdrawDeletes;//PORCENVARETR

            $array["BalanceEndCalc"] = round($array["BalanceInitial"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["WithdrawPend"], 2);
            $array["EndRechargeQuotaCalc"] = round($array["InitialRechargeQuota"] - $array["AmountDeposits"] + $array["WithdrawPaid"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"], 2);
            $array["EndGameQuotaCalc"] = round($array["InitialGameQuota"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"], 2);

            array_push($final, $array);

        }


    }
    /*if (!$IsDetails) {
        if ($pcont > 0) {
            $array["Game"] = $prod->{"producto.descripcion"};
            $array["ProviderName"] = $prod->{"proveedor.descripcion"};
            $array["Bets"] = $pcont;
            $array["Stakes"] = $papuestas;
            $array["Winnings"] = $ppremios;
            $array["Profit"] = 0;
            $array["BonusCashBack"] = 0;
            $array["CurrencyId"] = $prod->{"usuario_mandante.moneda"};

            array_push($final, $array);
        }
    }*/


    /* configura una respuesta sin errores y almacena la posición de filas. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["pos"] = $SkeepRows;

    /* asigna un valor a "total_count" basado en la variable "$Type".  */
    if ($Type == 0) {

        $response["total_count"] = $data->count[0]->{".count"};
    } else {
        $response["total_count"] = oldCount($final);

    }

    /* Asigna el valor de $final a la clave "data" del array $response. */
    $response["data"] = $final;


} else {
    /* inicializa una respuesta exitosa y sin errores en un sistema. */

    $response["HasError"] = false;
    $response["AlertType"] = "success2";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}

?>
