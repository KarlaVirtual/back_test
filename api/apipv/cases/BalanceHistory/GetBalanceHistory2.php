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
use SendGrid\Exception\InvalidRequest;
use Slim\Interfaces\RouteInterface;

/**
 * BalanceHistory/GetBalanceHistory
 *
 * Obtener el historial de saldo de los usuarios
 *
 * @param object $params Objeto con los parámetros de entrada:
 * @param int $params ->count Número total de registros.
 * @param string $params ->start Indice de posición de registros.
 * @param int $params ->PlayerId Identificador del jugador.
 * @param int $params ->UserId Identificador del usuario.
 * @param int $params ->ProviderId Identificador del proveedor.
 * @param int $params ->IsDetails Indica si se deben obtener detalles.
 * @param string $params ->Type Tipo de consulta.
 * @param string $params ->dateTo Fecha final.
 * @param int $params ->Dir Dirección de ordenamiento.
 * @param array $params ->Sort Ordenamiento de los resultados.
 * @param string $params ->dateFrom Fecha inicial.
 *
 *
 * @return array Respuesta con los siguientes valores:
 * - bool $HasError Indica si hubo un error.
 * - string $AlertType Tipo de alerta.
 * - string $AlertMessage Mensaje de alerta.
 * - array $ModelErrors Errores del modelo.
 * - int $pos Posición de las filas.
 * - int $total_count Conteo total de registros.
 * - array $data Datos obtenidos.
 */

/**
 * @OA\Post(path="apipv/BalanceHistory/GetBalanceHistory", tags={"BalanceHistory"}, description = "",
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
 *                   property="Sort",
 *                  @OA\Items(),
 *                   description="Sort",
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
 *
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
 *                   property="Pdf",
 *                   description="",
 *                   type="string",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="PdfPOS",
 *                   description="",
 *                   type="string",
 *                   example= {}
 *               )
 *             )
 *         )
 *     )
 * )
 */


/* Se crea un subtítulo de menú y se inicializa un perfil basado en la sesión. */
$Submenu = new Submenu("", "balanceHistory", '3');


try {
    $PerfilSubmenu = new PerfilSubmenu($_SESSION["win_perfil"], $Submenu->getSubmenuId());

} catch (Exception $e) {
    /* Manejo de excepción que crea un objeto PerfilSubmenu con parámetros específicos. */

    $PerfilSubmenu = new PerfilSubmenu('CUSTOM', $Submenu->getSubmenuId(), $_SESSION["usuario"]);
}


/* crea un objeto Usuario y obtiene parámetros JSON de una solicitud. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);
$IsDetails = $params->IsDetails;
$CurrencyId = $params->CurrencyId;

/* Se extraen parámetros de una solicitud para su posterior procesamiento. */
$IsTest = $params->IsTest;
$ProductId = $params->ProductId;
$ProviderId = $params->ProviderId;
$Region = $params->Region;

$MaxRows = $params->MaxRows;

/* Asigna fechas y datos de parámetros, ajustando el rango basado en la solicitud. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$ToDateLocal = $params->dateTo;
$FromDateLocal = $params->dateFrom;


if ($_REQUEST["dateFrom"] != "") {
    //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
    $FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));

}

/* asigna una fecha local a partir de un parámetro de entrada si no está vacío. */
if ($_REQUEST["dateTo"] != "") {
    //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
    $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));

}

$PlayerId = $_REQUEST['PlayerId'];

/* Asigna valores a variables basándose en condiciones de entrada del usuario. */
$UserId = $_REQUEST['UserId'];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';

//Si ProviderID es mayor que cero y es un numero o un string numerico y es diferente de vacio entonces a
//$ProviderID llevele $_REQUEST["ProviderId"] si no se cumple llevele ''

$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';

/* recoge parámetros de solicitud para manejar productos y datos de paginación. */
$ProductId = $_REQUEST["ProductId"];
$IsDetails = $_REQUEST["IsDetails"];
$Type = $_REQUEST["Type"];

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* verifica condiciones y ajusta variables para controlar el flujo del programa. */
$seguir = true;

if ($SkeepRows == "") {
    $seguir = false;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $seguir = false;
}

if ($seguir) {

    if ($IsDetails == 1) {
        $IsDetails = false;

    } else {
        $IsDetails = true;
    }

    $rules = [];

    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, ['field' => 'usuario.mandante', 'data' => $_SESSION['mandante'], 'op' => 'eq']);
    } else {
        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, ['field' => 'usuario.mandante', 'data' => $_SESSION["mandanteLista"], 'op' => 'in']);
        }
    }

    $Pais = $_SESSION['PaisCound'] == 'S' ? $_SESSION['pais_id'] : $CountrySelect;

    if ($FromDateLocal == $ToDateLocal && $FromDateLocal != "") {
        array_push($rules, array("field" => "usuario_saldo.fecha", "data" => "$FromDateLocal", "op" => "eq"));
    } else {
        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "usuario_saldo.fecha", "data" => "$FromDateLocal", "op" => "ge"));

        }

        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "usuario_saldo.fecha", "data" => "$ToDateLocal", "op" => "le"));
        }

    }

    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }

    if ($PlayerId != "") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => "$PlayerId", "op" => "eq"));
    }

    if ($UserId != "") {
        array_push($rules, array("field" => "usuario_saldo.usuario_id", "data" => "$UserId", "op" => "eq"));
    }

    if (!empty($Pais)) array_push($rules, ['field' => 'usuario.pais_id', 'data' => $Pais, 'op' => 'eq']);

    // Inactivamos reportes para el país Colombia
    array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);
    if(!empty($Pais) && !empty($FromDateLocal)) {
        /**
         * Se instancia un objeto de la clase ModeloFiscal.
         * Luego, se extraen el año y el mes de una fecha proporcionada en formato 'YYYY-MM-DD'.
         * Se construye un conjunto de reglas para consulta de datos fiscales.
         */
        $ModeloFiscal = new ModeloFiscal();
        $dateParts = explode('-', $FromDateLocal);
        $Year = $dateParts[0];
        $Mounth = $dateParts[1];

        $rules_model = [];

        // Se agregan reglas de filtrado a la consulta.
        array_push($rules_model, ['field' => 'modelo_fiscal.pais_id', 'data' => $Pais, 'op' => 'eq']);
        array_push($rules_model, ['field' => 'modelo_fiscal.estado', 'data' => 'A', 'op' => 'eq']);
        array_push($rules_model, ['field' => 'modelo_fiscal.mandante', 'data' => $_SESSION['mandante'], 'op' => 'eq']);
        array_push($rules_model, ['field' => 'modelo_fiscal.mes', 'data' => $Mounth, 'op' => 'eq']);
        array_push($rules_model, ['field' => 'modelo_fiscal.anio', 'data' => $Year, 'op' => 'eq']);

        // Se codifica en formato JSON el filtro de detalle.
        $filtro_detalle = json_encode(['rules' => $rules_model, 'groupOp' => 'AND']);

        // Se obtiene el modelo fiscal utilizando un método de la clase ModeloFiscal, aplicando los filtros establecidos.
        $modeloFiscal = $ModeloFiscal->getModeloFiscalCustom('modelo_fiscal.*, clasificador.*', 'modelo_fiscal.modelofiscal_id', 'asc', 0, 100000, $filtro_detalle, true);

        // Se decodifica el resultado en formato JSON.
        $modeloFiscal = json_decode($modeloFiscal);

        foreach ($modeloFiscal->data as $key => $value) {
            switch ($value->{'clasificador.abreviado'}) {
                case 'PORCENVADEPO':
                    $PercentDepositValue = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVARETR':
                    $PercentRetirementValue = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVAAPUESDEPOR':
                    $PercentValueSportsBets = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVAAPUESNODEPOR':
                    $PercentValueNonSportBets = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVAPREMDEPOR':
                    $PercentValueSportsAwards = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVAPREMNODEPOR':
                    $PercentValueNonSportsAwards = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVABONDEPOR':
                    $PercentValueSportsBonds = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVABONNODEPOR':
                    $PercentValueNonSportsBounds = intval($value->{'modelo_fiscal.valor'});
                    break;
                case 'PORCENVATICKET':
                    $PercentValueTickets = intval($value->{'modelo_fiscal.valor'});
                    break;
                default:
                    break;
            }
        }
    }

    // Se crea una consulta para calcular el saldo final y desfase de usuarios.
    $select = "       SUM(saldo_final) saldo_final,
                  SUM(saldo_inicial) + SUM(saldo_recarga) - SUM(saldo_apuestas) + SUM(saldo_premios) - SUM(saldo_apuestas_casino)
               + SUM(saldo_premios_casino) - SUM(saldo_notaret_creadas) + SUM(saldo_notaret_eliminadas) +
           SUM(saldo_ajustes_entrada) - sum(saldo_ajustes_salida) + sum(saldo_bono)-SUM(saldo_final) desfase,usuario_saldo.*,usuario.nombre,pais.iso ";

    // Se vuelve a definir una consulta similar, pero en este caso utilizando paréntesis.
    $select = "       (saldo_final) saldo_final,
                  (saldo_inicial) + (saldo_recarga) - (saldo_apuestas) + (saldo_premios) - (saldo_apuestas_casino)
               + (saldo_premios_casino) - (saldo_notaret_creadas) + (saldo_notaret_eliminadas) +
           (saldo_ajustes_entrada) - (saldo_ajustes_salida) + (saldo_bono)-(saldo_final) desfase,usuario_saldo.*,usuario.nombre,pais.iso ";

    // Definición del orden de la consulta
    $order = " desfase "; //Columna a ordenar ascendente o descendente
    $orderType = "desc"; //Se predetermina como descente.

    //Aqui elijo si la consulta fue ascendente porque si no entonces de manera predeterminada se muestra descendente.
    if ($_REQUEST["Dir"] == "0") {
        $orderType = "asc";

    }

    // Inicialización de variables para agrupamiento y recargas
    $grouping = "";
    $conRecargas = false;

    //Type indica si la consulta fue para totales o detallado
    //Cuando Type es 1 es porque se eligio totales.
    if ($Type == 1) {
        $select = "
                        pais.iso,
                        usuario_saldo.fecha,
           SUM(saldo_inicial) saldo_inicial,
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
                  SUM(saldo_inicial) + SUM(saldo_recarga) - SUM(saldo_apuestas) + SUM(saldo_premios) - SUM(saldo_apuestas_casino)
               + SUM(saldo_premios_casino) - SUM(saldo_notaret_creadas) + SUM(saldo_notaret_eliminadas) +
           SUM(saldo_ajustes_entrada) - sum(saldo_ajustes_salida) + sum(saldo_bono)-SUM(saldo_final) desfase,
    
           SUM(saldo_creditos_base_final) saldo_creditos_base_final,
           SUM(saldo_creditos_final) saldo_creditos_final,
           SUM(usuario_recargas.usuarios_recargas)      usuarios_recargas,
           SUM(usuario_recargas.cantidad_recargas)      cantidad_recargas";

        $grouping = "usuario.pais_id,usuario_saldo.fecha";

        $conRecargas = true;

    } else {
        //$grouping = "usuario.usuario_id,usuario_saldo.fecha";

        //$order = " desfase ";
        //$orderType = "asc";

    }
    /**
     * Verifica si se ha solicitado un orden específico en la petición.
     * Si se proporciona un valor en los parámetros de orden, se establece la columna y el tipo de orden.
     */
    if ($_REQUEST["Sort"] != "") {

        // Verifica si se ha solicitado ordenar por la cantidad de ganancias.
        if($_REQUEST["Sort"]["AmountWin"]!= ""){
            $order = "saldo_premios"; // Establece la columna a ordenar.
            $orderType = ($_REQUEST["Sort[AmountWin]"] == "asc") ? "asc" : "desc"; // Define el tipo de orden.
        }

        // Verifica si se ha solicitado ordenar por la cantidad de depósitos.
        if($_REQUEST["Sort"]["AmountDeposits"]!= ""){
            $order = "saldo_recarga"; // Establece la columna a ordenar.
            $orderType = ($_REQUEST["Sort[AmountDeposits]"] == "asc") ? "asc" : "desc"; // Define el tipo de orden.
        }

        // Verifica si se ha solicitado ordenar por la cantidad de ganancias en el casino.
        if($_REQUEST["Sort"]["AmountWinCasino"]!= ""){
            $order = "saldo_premios_casino"; // Establece la columna a ordenar.
            $orderType = ($_REQUEST["Sort[AmountWinCasino]"] == "asc") ? "asc" : "desc"; // Define el tipo de orden.
        }

    }

    // Se crea una instancia de la clase UsuarioSaldo.
    $UsuarioSaldo = new UsuarioSaldo();
    // Se obtienen los saldos de usuario con las opciones de orden y paginación.
    $data = $UsuarioSaldo->getUsuarioSaldosCustom($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping, '',$conRecargas);
    // Se decodifica el JSON obtenido.
    $data = json_decode($data);


    $final = [];

    $papuestas = 0;
    $ppremios = 0;
    $pcont = 0;

    foreach ($data->data as $key => $value) {
        /*Almacenamiento y formato objetos de respuesta*/
        if ($Type == 0) {

            $array["Id"] = $value->{"usuario_saldo.ususaldo_id"};
            $array["UserId"] = $value->{"usuario_saldo.usuario_id"};
            $array["Country"] = strtolower($value->{"pais.iso"});
            $array["UserName"] = $value->{"usuario.nombre"};
            $array["CreatedLocalDate"] = $value->{"usuario_saldo.fecha"};

            $array["BalanceInitial"] = round($value->{"usuario_saldo.saldo_inicial"}, 2);

            $array["AmountDeposits"] = isset($PercentDepositValue) ? round(($value->{"usuario_saldo.saldo_recarga"} / 100) * $PercentDepositValue, 2) : round($value->{'usuario_saldo.saldo_recarga'}, 2);//PORCENVADEPO

            $array["AmountBets"] = isset($PercentValueSportsBets) ? round(($value->{"usuario_saldo.saldo_apuestas"} / 100) * $PercentValueSportsBets, 2) : round($value->{'usuario_saldo.saldo_apuestas'}, 2);//PORCENVAAPUESDEPOR

            $array["AmountWin"] = isset($PercentValueSportsAwards) ? round(($value->{"usuario_saldo.saldo_premios"} / 100) * $PercentValueSportsAwards, 2) : round($value->{'usuario_saldo.saldo_premios'}, 2);//PORCENVAPREMDEPOR

            $array["AmountBetsCasino"] = isset($PercentValueNonSportBets) ? round(($value->{"usuario_saldo.saldo_apuestas_casino"} / 100) * $PercentValueNonSportBets, 2) : round($value->{'usuario_saldo.saldo_apuestas_casino'}, 2);//PORCENVAAPUESNODEPOR

            $array["AmountWinCasino"] = isset($PercentValueNonSportsAwards) ? round(($value->{"usuario_saldo.saldo_premios_casino"} / 100) * $PercentValueNonSportsAwards, 2) : round($value->{'usuario_saldo.saldo_premios_casino'}, 2);//PORCENVAPREMNODEPOR

            $array["WithdrawCreates"] = isset($PercentRetirementValue) ? round(($value->{"usuario_saldo.saldo_notaret_creadas"} / 100) * $PercentRetirementValue, 2) : round($value->{"usuario_saldo.saldo_notaret_creadas"}, 2);//PORCENVARETR

            $array["WithdrawPaid"] = isset($PercentRetirementValue) ? round(($value->{"usuario_saldo.saldo_notaret_pagadas"} / 100) * $PercentRetirementValue, 2) : round($value->{"usuario_saldo.saldo_notaret_pagadas"}, 2);//PORCENVARETR

            $array["WithdrawPend"] = isset($PercentRetirementValue) ? round(($value->{"usuario_saldo.saldo_notaret_pend"} / 100) * $PercentRetirementValue, 2) : round($value->{"usuario_saldo.saldo_notaret_pend"}, 2);//PORCENVARETR

            $array["AdjustmentE"] = round($value->{"usuario_saldo.saldo_ajustes_entrada"}, 2);
            $array["AdjustmentS"] = round($value->{"usuario_saldo.saldo_ajustes_salida"}, 2);

            $array["Bonus"] = isset($PercentValueSportsBonds) ? (round($value->{"usuario_saldo.saldo_bono"}, 2) / 100) * $PercentValueSportsBonds : round($value->{'usuario_saldo.saldo_bono'}, 2);//PORCENVABONDEPOR

            // $array["BalanceEnd"] = round($value->{"usuario_saldo.saldo_final"}, 2);
            $array["BalanceEnd"] = round($value->{"usuario_saldo.saldo_creditos_base_final"} + $value->{"usuario_saldo.saldo_creditos_final"}, 2);

            $array["BonusFreeWin"] = isset($PercentValueNonSportsBounds) ? round(($value->{"usuario_saldo.saldo_bono_free_ganado"} / 100) * $PercentValueNonSportsBounds, 2) : round($value->{'usuario_saldo.saldo_bono_free_ganad'}, 2);//PORCENVABONNODEPOR

            $array["WithdrawDeletes"] = isset($PercentRetirementValue) ? round(($value->{"usuario_saldo.saldo_notaret_eliminadas"} / 100) * $PercentRetirementValue, 2) : $value->{"usuario_saldo.saldo_notaret_eliminadas"};//PORCENVARETR

            $array["DepositsUsersCount"] = round($value->{".usuarios_recargas"}, 2);
            $array["DepositsCount"] = round($value->{".cantidad_recargas"}, 2);

            $array["BalanceEndCalc"] = round($array["BalanceInitial"] + $array["AmountDeposits"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["Bonus"], 2);

            array_push($final, $array);

        } else {
            $array["Id"] = 0;
            $array["UserId"] = 0;
            $array["Country"] = strtolower($value->{"pais.iso"});

            $array["UserName"] = '';
            $array["CreatedLocalDate"] = $value->{"usuario_saldo.fecha"};

            $array["BalanceInitial"] = round($value->{".saldo_inicial"}, 2);

            $array["AmountDeposits"] = isset($PercentDepositValue) ? round(($value->{".saldo_recarga"} / 100) * $PercentDepositValue, 2) : round($value->{'.saldo_recarga'}, 2);//PORCENVADEPO

            $array["AmountBets"] = isset($PercentValueSportsBets) ? round(($value->{".saldo_apuestas"} / 100) * $PercentValueSportsBets, 2) : round($value->{'.saldo_apuestas'}, 2);//PORCENVAAPUESDEPOR

            $array["AmountWin"] = isset($PercentValueSportsAwards) ? round(($value->{".saldo_premios"} / 100) * $PercentValueSportsAwards, 2) : round($value->{'.saldo_recarga'}, 2);//PORCENVAPREMDEPOR

            $array["AmountBetsCasino"] = isset($PercentValueNonSportBets) ? round(($value->{".saldo_apuestas_casino"} / 100) * $PercentValueNonSportBets, 2) : round($value->{'.saldo_apuestas_casino'}, 2);//PORCENVAAPUESNODEPOR

            $array["AmountWinCasino"] = isset($PercentValueNonSportsAwards) ? round(($value->{".saldo_premios_casino"} / 100) * $PercentValueNonSportsAwards, 2) : round($value->{'.saldo_premios_casino'}, 2);//PORCENVAPREMNODEPOR

            $array["WithdrawCreates"] = isset($PercentRetirementValue) ? round(($value->{".saldo_notaret_creadas"} / 100) * $PercentRetirementValue, 2) : round($value->{".saldo_notaret_creadas"}, 2);//PORCENVARETR

            $array["WithdrawPaid"] = isset($PercentRetirementValue) ? round(($value->{".saldo_notaret_pagadas"} / 100) * $PercentRetirementValue, 2) : round($value->{".saldo_notaret_pagadas"}, 2);//PORCENVARETR

            $array["WithdrawPend"] = isset($PercentRetirementValue) ? round(($value->{"saldo_notaret_pend"} / 100) * $PercentRetirementValue, 2) : round($value->{".saldo_notaret_pend"}, 2);//PORCENVARETR

            $array["AdjustmentE"] = round($value->{".saldo_ajustes_entrada"}, 2);
            $array["AdjustmentS"] = round($value->{".saldo_ajustes_salida"}, 2);

            $array["Bonus"] = isset($PercentValueSportsBonds) ? (round($value->{".saldo_bono"}, 2) / 100) * $PercentValueSportsBonds : round($value->{'.saldo_bno'}, 2);//PORCENVABONDEPOR

            $array["BalanceEnd"] = round($value->{".saldo_final"}, 2);
            $array["BalanceEnd"] = round($value->{".saldo_creditos_base_final"} + $value->{".saldo_creditos_final"}, 2);

            $array["BonusFreeWin"] = isset($PercentValueNonSportsBounds) ? round(($value->{".saldo_bono_free_ganado"} / 100) * $PercentValueNonSportsBounds, 2) : round($value->{'.saldo_bono_free_ganado'}, 2);//PORCENVABONNODEPOR

            $array["WithdrawDeletes"] = isset($PercentRetirementValue) ? round(($value->{".saldo_notaret_eliminadas"} / 100) * $PercentRetirementValue, 2) : $value->{".saldo_notaret_eliminadas"};//PORCENVARETR

            $array["BalanceEndCalc"] = round($array["BalanceInitial"] + $array["AmountDeposits"] - $array["AmountBets"] + $array["AmountWin"] - $array["AmountBetsCasino"] + $array["AmountWinCasino"] - $array["WithdrawCreates"] + $array["WithdrawDeletes"] + $array["AdjustmentE"] - $array["AdjustmentS"] + $array["Bonus"], 2);

            $array["DepositsUsersCount"] = round($value->{".usuarios_recargas"}, 2);
            $array["DepositsCount"] = round($value->{".cantidad_recargas"}, 2);

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


    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["pos"] = $SkeepRows;
    if ($Type == 0) {

        $response["total_count"] = $data->count[0]->{".count"};;
    } else {
        $response["total_count"] = oldCount($final);

    }
    $response["data"] = $final;


} else {
    /* Generación de formato de respuesta*/

    $response["HasError"] = false;
    $response["AlertType"] = "success2";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();
}
?>
