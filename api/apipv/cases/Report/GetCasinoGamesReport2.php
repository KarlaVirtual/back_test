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
use Backend\dto\UsucasinoDetalleResumen;
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
 * Report/GetCasinoGamesReport
 * 
 * Obtiene el reporte de apuestas y transacciones de casino, incluyendo información detallada
 * de juegos, proveedores, montos y métricas.
 *
 * @param int $count Número total de registros a obtener
 * @param int $start Índice inicial para la paginación
 * @param bool $IsDetails Indica si se debe retornar el detalle de las transacciones
 * @param string $CurrencyId Identificador de la moneda para conversiones
 * @param bool $IsTest Indica si son transacciones de prueba
 * @param string $ProductId ID del producto/juego a filtrar
 * @param int $ProviderId ID del proveedor a filtrar
 * @param string $FromDate Fecha inicial del reporte
 * @param string $ToDate Fecha final del reporte
 * @param string $TypeReport Tipo de reporte (casino, livecasino, etc)
 *
 * @return array {
 *   "HasError": bool,
 *   "AlertType": string,
 *   "AlertMessage": string,
 *   "ModelErrors": array,
 *   "pos": int,
 *   "total_count": int,
 *   "data": array {
 *     "Game": string,
 *     "ProviderName": string,
 *     "SubProviderName": string, 
 *     "Bets": int,
 *     "Stakes": float,
 *     "Winnings": float,
 *     "StakesBonus": float,
 *     "WinningsBonus": float,
 *     "FreeBalance": float,
 *     "Profit": float,
 *     "Profitness": float,
 *     "Bonus": float,
 *     "TotalJackpotPrizeSum": float,
 *     "BonusCashBack": float,
 *     "CurrencyId": string,
 *     "CountryIcon": string
 *   }
 * }
 *
 * @access public
 */

/**
 * @OA\Post(path="apipv/Report/GetCasinoGamesReport", tags={"Report"}, description = "",
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
 *                   property="IsDetails",
 *                   description="IsDetails",
 *                   type="integer",
 *                   example= 2359
 *               ),
 *               @OA\Property(
 *                   property="CurrencyId",
 *                   description="CurrencyId",
 *                   type="integer",
 *                   example= 5609
 *               ),
 *               @OA\Property(
 *                   property="IsTest",
 *                   description="IsTest",
 *                   type="integer",
 *                   example= 2439
 *               ),
 *               @OA\Property(
 *                   property="ProductId",
 *                   description="ProductId",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="ProviderId",
 *                   description="ProviderId",
 *                   type="integer",
 *                   example= 235
 *               ),
 *               @OA\Property(
 *                   property="Region",
 *                   description="Region",
 *                   type="string",
 *                   example= "A"
 *               ),
 *               @OA\Property(
 *                   property="dateTo",
 *                   description="dateTo",
 *                   type="integer",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="dateFrom",
 *                   description="dateFrom",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="ValueMinimum",
 *                   description="ValueMinimum",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="ValueMaximum",
 *                   description="ValueMaximum",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="PlayerId",
 *                   description="PlayerId",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="CountrySelect",
 *                   description="CountrySelect",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="SubProviderId",
 *                   description="SubProviderId",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Type",
 *                   description="Type",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Identifier",
 *                   description="Identifier",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="sort",
 *                   description="sort",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="Id",
 *                   description="Id",
 *                   type="string",
 *                   example= ""
 *               )
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
 *               )
 *             )
 *         )
 *     )
 * )
 */



// Inicializa el objeto Usuario para acceder a la base de datos
$Usuario = new Usuario();

// Obtiene y decodifica los parámetros enviados en el cuerpo de la petición
$params = file_get_contents('php://input');
$params = json_decode($params);
$IsDetails = $params->IsDetails;
$CurrencyId = $params->CurrencyId;
$IsTest = $params->IsTest;
$ProductId = $params->ProductId;
$ProviderId = $params->ProviderId;
$Region = $params->Region;

// Obtiene parámetros de paginación y ordenamiento
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;
$ToDateLocal = $params->dateTo;
$FromDateLocal = $params->dateFrom;
$ValueMinimum = $params->ValueMinimum;
$ValueMaximum = $params->ValueMaximum;
$Categorie = $params->Categorie;

// Procesa las fechas desde y hasta si vienen en el request
if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}
if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));
}

// Obtiene y valida parámetros adicionales del request
$PlayerId = $_REQUEST['PlayerId'];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$SubProviderId = ($_REQUEST["SubProviderId"] > 0 && is_numeric($_REQUEST["SubProviderId"]) && $_REQUEST["SubProviderId"] != '') ? $_REQUEST["SubProviderId"] : '';
$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';
$ProductId = $_REQUEST["ProductId"];
$IsDetails = $_REQUEST["IsDetails"];
$Type = $_REQUEST["Type"];

// Obtiene valores mínimos y máximos e identificadores
$ValueMinimum = $_REQUEST["ValueMinimum"];
$ValueMaximum = $_REQUEST["ValueMaximum"];
$Identifier = $_REQUEST["Identifier"];
$Id = $_REQUEST['Id'];
$sort = $_REQUEST['sort'];

// Configura la paginación y validaciones finales
if ($Type == 1) {
    $IsDetails = true;
}

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($IsDetails == 1) {
    $MaxRows = 1000000;
}
$seguir = true;

// Valida que los parámetros requeridos estén presentes
if ($SkeepRows == "") {
    $seguir = false;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $seguir = false;
}

// Verifica si se debe continuar con el procesamiento
if ($seguir) {

    // Invierte el valor de IsDetails para el procesamiento
    if ($IsDetails == 1) {
        $IsDetails = false;
    } else {
        $IsDetails = true;
    }

    // Verifica si hay país seleccionado y fecha inicial para procesar el modelo fiscal
    if(!empty($CountrySelect) && !empty($FromDateLocal)) {
        $ModeloFiscal = new ModeloFiscal();
        $dateParts = explode('-', $FromDateLocal);
        $Year = $dateParts[0];
        $Mounth = $dateParts[1];

        // Prepara las reglas para filtrar el modelo fiscal
        $rules_model = [];
        array_push($rules_model, ['field' => 'modelo_fiscal.pais_id', 'data' => $CountrySelect, 'op' => 'eq']);
        array_push($rules_model, ['field' => 'modelo_fiscal.estado', 'data' => 'A', 'op' => 'eq']);
        array_push($rules_model, ['field' => 'modelo_fiscal.mandante', 'data' => $_SESSION['mandante'], 'op'=> 'eq']);
        array_push($rules_model, ['field' => 'modelo_fiscal.mes', 'data' => $Mounth, 'op' => 'eq']);
        array_push($rules_model, ['field' => 'modelo_fiscal.anio', 'data' => $Year, 'op' => 'eq']);

        // Ejecuta la consulta del modelo fiscal
        $filtro_modelo = json_encode(['rules' => $rules_model, 'groupOp' => 'AND']);
        $modeloFiscal = $ModeloFiscal->getModeloFiscalCustom(' modelo_fiscal.*, clasificador.*', 'modelo_fiscal.modelofiscal_id', 'asc', 0, 100000, $filtro_modelo, true);
        $modeloFiscal = json_decode($modeloFiscal);

        // Procesa los resultados y asigna los porcentajes según el tipo de clasificador
        foreach($modeloFiscal->data as $key => $value) {
            switch($value->{'clasificador.abreviado'}) {
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
    if($IsDetails) {
        // Inicializa el array de reglas para filtrar las transacciones
        $rules = [];

        // Aplica filtros de fecha para el rango seleccionado
        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            // array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));
        }

        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
            // array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));
        }

        // Aplica filtros por producto según si es usuario global o no
        if ($ProductId != "") {
            if ($_SESSION['Global'] == "S") {
                array_push($rules, array("field" => "producto.producto_id", "data" => "$ProductId", "op" => "eq"));
            } else {
                array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$ProductId", "op" => "eq"));
            }
        }

        // Aplica filtros por región, proveedor y subproveedor
        if ($Region != "") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($ProviderId != "") {
            array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
        }

        if ($SubProviderId != "") {
            array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
        }

        // Aplica filtros por jugador y tipo de juego
        if ($PlayerId != "") {
            array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => "$PlayerId", "op" => "eq"));
        }

        if ($Type != "") {
            if ($Type == "N") {
                array_push($rules, array("field" => "transaccion_juego.tipo", "data" => "NORMAL,''", "op" => "in"));
            }
            if ($Type == "FS") {
                array_push($rules, array("field" => "transaccion_juego.tipo", "data" => "FREESPIN", "op" => "eq"));
            }
        }

        // Aplica filtros por país y valores mínimos/máximos
        if ($CountrySelect != "" && $CountrySelect != "0") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
        }

        if ($ValueMaximum != "" && $ValueMaximum != "0") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$ValueMaximum", "op" => "le"));
        }

        if ($ValueMinimum != "" && $ValueMinimum != "0") {
            array_push($rules, array("field" => "transaccion_juego.valor_ticket", "data" => "$ValueMinimum", "op" => "ge"));
        }

        // Aplica restricciones de seguridad según el tipo de usuario
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }

        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {
            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }
        }

        // Aplica filtros por identificador y ID de transacción
        if ($Identifier != '') {
            array_push($rules, array("field" => "transaccion_juego.ticket_id", "data" => $Identifier, "op" => "cn"));
        }

        if ($Id != '') {
            array_push($rules, array("field" => "transaccion_juego.transjuego_id", "data" => $Id, "op" => "eq"));
        }

        // Configura el ordenamiento según los parámetros recibidos
        if ($_REQUEST["Sort"] != "") {
            if($_REQUEST["Sort"]["Date"]!= ""){
                $order = "saldo_premios";
                $orderType = ($_REQUEST["Sort[Date]"] == "asc") ? "asc" : "desc";
            }

            if($_REQUEST["Sort"]["Username"]!= ""){
                $order = "fecha_crea";
                $orderType = ($_REQUEST["Sort[Username]"] == "asc") ? "asc" : "desc";
            }

            if($_REQUEST["Sort"]["Stakes"]!= ""){
                $order = "apuestas";
                $orderType = ($_REQUEST["Sort[Stakes]"] == "asc") ? "asc" : "desc";
            }

            if($_REQUEST["Sort"]["Winnings"]!= ""){
                $order = "premios";
                $orderType = ($_REQUEST["Sort[Winnings]"] == "asc") ? "asc" : "desc";
            }
        }

        // Excluye país con ID 1
        array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "1", "op" => "ne"));

        // Prepara el filtro final y lo convierte a JSON
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        // Configura la consulta según si se requieren detalles o no
        if (!$IsDetails) {
            $grouping = "producto.producto_id,usuario_mandante.moneda";
            $select = " usuario_mandante.moneda,producto.*, COUNT(transaccion_juego.transjuego_id) count,SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END) apuestas, SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END) premios,SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END) apuestasBonus, SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END) premiosBonus,proveedor.* ";
        } else {
            $select = " usuario_mandante.moneda,producto.*, transaccion_juego.ticket_id,transaccion_juego.tipo,transaccion_juego.fecha_crea,transaccion_juego.transjuego_id,transaccion_juego.usuario_id,usuario_mandante.usuario_mandante, 1 count,CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END apuestas,CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END premios,CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END apuestasBonus,CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END premiosBonus,proveedor.* ";
        }

        // Ejecuta la consulta y obtiene los resultados
        $TransaccionJuego = new TransaccionJuego();
        $data = $TransaccionJuego->getTransaccionesCustom($select, "", "", $SkeepRows, $MaxRows, $json, true, $grouping,"",true,false);
        $data = json_decode($data);

        // Inicializa variables para procesar los resultados
        $final = [];
        $papuestas = 0;
        $ppremios = 0;
        $pcont = 0;

        // Procesa cada registro obtenido
        foreach ($data->data as $key => $value) {
            $CurrencyId = $value->{"usuario_mandante.moneda"};
            $array = [];

            if ($IsDetails) {
                // Procesa los detalles de cada transacción
                $array["Identifier"] = $value->{"transaccion_juego.ticket_id"};
                $array["Id"] = $value->{"transaccion_juego.transjuego_id"};
                $array["Date"] = $value->{"transaccion_juego.fecha_crea"};
                $array["Username"] = $value->{"usuario_mandante.usuario_mandante"};
                $array["Game"] = $value->{"producto.descripcion"};
                $array["ProviderName"] = $value->{"proveedor.descripcion"};
                $array["Bets"] = $value->{".count"};

                // Calcula los valores ajustados según porcentajes fiscales
                $Stakes = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                $array["Stakes"] = isset($PercentValueNonSportBets) ? ($Stakes / 100) * $PercentValueNonSportBets : $Stakes;//PORCENVAAPUESNODEPOR

                $Winnings = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                $array["Winnings"] = isset($PercentValueNonSportsAwards) ? ($Winnings / 100) * $PercentValueNonSportsAwards : $Winnings;//PORCENVAPREMNODEPOR

                $StakesBonus = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestasBonus"}, 2));
                $array["StakesBonus"] = isset($PercentValueNonSportsBounds) ? ($StakesBonus / 100) * $PercentValueNonSportsBounds : $StakesBonus;//PORCENVABONNODEPOR

                $WinningsBonus = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premiosBonus"}, 2));
                $array["WinningsBonus"] = isset($PercentValueNonSportsBounds) ? ($WinningsBonus / 100) * $PercentValueNonSportsBounds : $WinningsBonus;//PORCENVABONNODEPOR

                // Calcula métricas adicionales
                $array["Profit"] = $array["Stakes"] - $array["Winnings"] - $array["WinningsBonus"];
                if ($array["Stakes"] == 0) {
                    $array["Profitness"] = 0;
                } else {
                    $array["Profitness"] = (($array["Stakes"] - $array["Winnings"] - $array["WinningsBonus"]) / $array["Stakes"]) * 100;
                }
                $array["BonusCashBack"] = 0;
                $array["CurrencyId"] = $value->{"usuario_mandante.moneda"};
                if ($array["CurrencyId"] == "PEN") {
                    $array["CountryIcon"] = "pe";
                }

                $array["Type"] = 'N';
                if ($value->{"transaccion_juego.tipo"} == "FREESPIN") {
                    $array["Type"] = 'FS';
                }

                array_push($final, $array);
            } else {
                // Procesa los resúmenes agregados
                $array["Identifier"] = $value->{"transaccion_juego.ticket_id"};
                $array["Game"] = $value->{"producto.descripcion"};
                $array["ProviderName"] = $value->{"proveedor.descripcion"};
                $array["Bets"] = $value->{".count"};

                // Calcula los valores ajustados según porcentajes fiscales
                $Stakes = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                $array["Stakes"] = isset($PercentValueNonSportBets) ? ($Stakes / 100) * $PercentValueNonSportBets : $Stakes;//PORCENVAAPUESNODEPOR

                $Winnings = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                $array["Winnings"] = isset($PercentValueNonSportsAwards) ? ($Winnings / 100) * $PercentValueNonSportsAwards : $Winnings;//PORCENVAPREMNODEPOR

                $StakesBonus = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestasBonus"}, 2));
                $array["StakesBonus"] = isset($PercentValueNonSportsBounds) ? ($StakesBonus / 100) * $PercentValueNonSportsBounds : $StakesBonus;//PORCENVABONNODEPOR

                $WinningsBonus = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premiosBonus"}, 2));
                $array["WinningsBonus"] = isset($PercentValueNonSportsBounds) ? ($WinningsBonus / 100) * $PercentValueNonSportsBounds : $WinningsBonus ;//PORCENVABONNODEPOR

                // Calcula métricas adicionales
                $array["Profit"] = $array["Stakes"] - $array["Winnings"] - $array["WinningsBonus"];
                if ($array["Stakes"] == 0) {
                    $array["Profitness"] = 0;
                } else {
                    $array["Profitness"] = (($array["Stakes"] - $array["Winnings"] - $array["WinningsBonus"]) / $array["Stakes"]) * 100;
                }
                $array["BonusCashBack"] = 0;
                $array["CurrencyId"] = $value->{"usuario_mandante.moneda"};
                if ($array["CurrencyId"] == "PEN") {
                    $array["CountryIcon"] = "pe";
                }


                array_push($final, $array);

                $papuestas = 0;
                $ppremios = 0;
                $pcont = 0;
                $papuestas = $papuestas + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                $ppremios = $ppremios + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                $pcont = $pcont + $value->{".count"};

                $prod = $value;
                /*if (($prod->{"producto.producto_id"} != $value->{"producto.producto_id"}) && ($prod != null)) {
    $array["ProviderName"] = $prod->{"proveedor.descripcion"};
                    $array["Bets"] = $pcont;
                    $array["Stakes"] = $papuestas;
                    $array["Winnings"] = $ppremios;
                    $array["Profit"] = $papuestas - $ppremios;
                    $array["Profitness"] = (($papuestas - $ppremios) / $papuestas) * 100;
                    $array["BonusCashBack"] = 0;
                    $array["CurrencyId"] = $value->{"usuario_mandante.moneda"};

                    array_push($final, $array);

                    $papuestas = 0;
                    $ppremios = 0;
                    $pcont = 0;
                    $papuestas = $papuestas + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                    $ppremios = $ppremios + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                    $pcont = $pcont + $value->{".count"};

                } else {
                    $papuestas = $papuestas + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                    $ppremios = $ppremios + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                    $pcont = $pcont + $value->{".count"};
                    $prod = $value;
                }*/


            }


        }
    }else{
        // Inicializa el array de reglas para los filtros
        $rules = [];

        // Aplica filtros de fecha
        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "usucasino_detalle_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
        }

        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "usucasino_detalle_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
        }

        // Aplica filtros de producto según permisos globales
        if ($ProductId != "") {
            if ($_SESSION['Global'] == "S") {
                array_push($rules, array("field" => "producto.producto_id", "data" => "$ProductId", "op" => "eq"));
            } else {
                array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$ProductId", "op" => "eq"));
            }
        }

        // Aplica filtros de región y proveedor
        if ($Region != "") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($ProviderId != "") {
            array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
        }

        if ($SubProviderId != "") {
            array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
        }

        // Aplica filtros de jugador y tipo de juego
        if ($PlayerId != "") {
            array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => "$PlayerId", "op" => "eq"));
        }

        if ($Type != "") {
            if ($Type == "N") {
                array_push($rules, array("field" => "usucasino_detalle_resumen.tipo", "data" => "'DEBIT','CREDIT','ROLLBACK'", "op" => "in"));
            }
            if ($Type == "FS") {
                array_push($rules, array("field" => "usucasino_detalle_resumen.tipo", "data" => "'DEBITFREESPIN','CREDITFREESPIN','ROLLBACKFREESPIN'", "op" => "in"));
            }
        }

        // Aplica filtros adicionales de país y valores
        if ($CountrySelect != "" && $CountrySelect != "0") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
        }

        if ($ValueMaximum != "" && $ValueMaximum != "0") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$ValueMaximum", "op" => "le"));
        }

        if ($ValueMinimum != "" && $ValueMinimum != "0") {
            array_push($rules, array("field" => "usucasino_detalle_resumen.valor", "data" => "$ValueMinimum", "op" => "ge"));
        }

        // Aplica restricciones de permisos de usuario
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }

        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {
            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }
        }

        array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "1", "op" => "ne"));

        // Prepara y ejecuta la consulta
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $grouping = "producto.producto_id,usuario_mandante.moneda";
        $select = " usuario_mandante.moneda,producto.*, SUM(CASE WHEN usucasino_detalle_resumen.tipo IN ('DEBIT','DEBITFREESPIN','DEBITFREECASH') THEN usucasino_detalle_resumen.cantidad ELSE 0 END) count,SUM(CASE WHEN usucasino_detalle_resumen.tipo IN ('DEBIT','DEBITFREECASH') THEN usucasino_detalle_resumen.valor ELSE 0 END) apuestas, SUM(CASE WHEN usucasino_detalle_resumen.tipo IN( 'CREDIT','ROLLBACK','CREDITFREECASH') THEN usucasino_detalle_resumen.valor ELSE 0 END) premios,SUM(CASE WHEN usucasino_detalle_resumen.tipo = 'DEBITFREESPIN' THEN usucasino_detalle_resumen.valor ELSE 0 END) apuestasBonus,SUM(CASE WHEN usucasino_detalle_resumen.tipo = 'DEBITFREECASH' THEN usucasino_detalle_resumen.valor_premios ELSE 0 END) apuestaFreecash, SUM(CASE WHEN usucasino_detalle_resumen.tipo IN ('CREDITFREESPIN') THEN usucasino_detalle_resumen.valor ELSE 0 END) premiosBonus,proveedor.* ";

        // Obtiene y procesa los datos del resumen
        $UsucasinoDetalleResumen = new UsucasinoDetalleResumen();
        $data = $UsucasinoDetalleResumen->getUsucasinoDetalleResumenCustom($select, "usucasino_detalle_resumen.usucasdetresume_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);
        $data = json_decode($data);

        // Inicializa variables para el procesamiento de datos
        $final = [];
        $papuestas = 0;
        $ppremios = 0;
        $pcont = 0;

        // Procesa cada registro del resumen
        foreach ($data->data as $key => $value) {
            $CurrencyId = $value->{"usuario_mandante.moneda"};
            $array = [];

            // Asigna valores básicos del juego
            $array["Game"] = $value->{"producto.descripcion"};
            $array["ProviderName"] = $value->{"proveedor.descripcion"};
            $array["Bets"] = $value->{".count"};

            // Calcula y ajusta valores monetarios según configuración
            $Stakes = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
            $array["Stakes"] = isset($PercentValueNonSportBets) ? ($Stakes / 100) * $PercentValueNonSportBets : $Stakes;//PORCENVAAPUESNODEPOR

            $Winnings = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
            $array["Winnings"] = isset($PercentValueNonSportsAwards) ? ($Winnings / 100) * $PercentValueNonSportsAwards : $Winnings ;//PORCENVAPREMNODEPOR

            // Procesa bonos y balance libre
            $StakesBonus = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestasBonus"}, 2));
            $array["StakesBonus"] = isset($PercentValueNonSportsBounds) ? ($StakesBonus / 100) * $PercentValueNonSportsBounds : $StakesBonus;//PORCENVABONNODEPOR

            $WinningsBonus = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premiosBonus"}, 2));
            $array["WinningsBonus"] = isset($PercentValueNonSportsBounds) ? ($WinningsBonus / 100) * $PercentValueNonSportsBounds : $WinningsBonus;//PORCENVABONNODEPOR

            $array["FreeBalance"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestaFreecash"}, 2));
            $array["Stakes"]= floatval($array["FreeBalance"])+floatval($array["Stakes"]);

            // Calcula ganancias y rentabilidad
            $array["Profit"] = $array["Stakes"] - $array["Winnings"] - $array["WinningsBonus"];
            if ($array["Stakes"] == 0) {
                $array["Profitness"] = 0;
            } else {
                $array["Profitness"] = (($array["Stakes"] - $array["Winnings"] - $array["WinningsBonus"]) / $array["Stakes"]) * 100;
            }

            // Asigna valores finales y procesa moneda
            $array["BonusCashBack"] = 0;
            $array["CurrencyId"] = $value->{"usuario_mandante.moneda"};
            if ($array["CurrencyId"] == "PEN") {
                $array["CountryIcon"] = "pe";
            }

            array_push($final, $array);

            // Reinicia contadores para el siguiente registro
            $papuestas = 0;
            $ppremios = 0;
            $pcont = 0;
            $papuestas = $papuestas + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
            $ppremios = $ppremios + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
            $pcont = $pcont + $value->{".count"};

            $prod = $value;
        }

        // Verifica si la fecha final es hoy para procesar datos en tiempo real
        if (date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour ')) == date("Y-m-d", time())) {

            $rules = [];

            // Configura filtros de fecha usando timestamp
            if ($FromDateLocal != "") {
                array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));
            }

            if ($ToDateLocal != "") {
                array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));
            }

            // Aplica filtros por producto según permisos del usuario
            if ($ProductId != "") {
                if ($_SESSION['Global'] == "S") {
                    array_push($rules, array("field" => "producto.producto_id", "data" => "$ProductId", "op" => "eq"));
                } else {
                    array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$ProductId", "op" => "eq"));
                }
            }

            // Agrega filtros por región, proveedor y subproveedor
            if ($Region != "") {
                array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$Region", "op" => "eq"));
            }

            if ($ProviderId != "") {
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
            }

            if ($SubProviderId != "") {
                array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
            }

            // Configura filtros por jugador y tipo de juego
            if ($PlayerId != "") {
                array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => "$PlayerId", "op" => "eq"));
            }

            if ($Type != "") {
                if ($Type == "N") {
                    array_push($rules, array("field" => "transaccion_juego.tipo", "data" => "NORMAL,''", "op" => "in"));
                }
                if ($Type == "FS") {
                    array_push($rules, array("field" => "transaccion_juego.tipo", "data" => "FREESPIN", "op" => "eq"));
                }
            }

            // Aplica filtros por país y valores límite
            if ($CountrySelect != "" && $CountrySelect != "0") {
                array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
            }

            if ($ValueMaximum != "" && $ValueMaximum != "0") {
                array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$ValueMaximum", "op" => "le"));
            }

            if ($ValueMinimum != "" && $ValueMinimum != "0") {
                array_push($rules, array("field" => "transaccion_juego.valor_ticket", "data" => "$ValueMinimum", "op" => "ge"));
            }

            // Aplica restricciones por país y mandante según permisos
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {
                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }
            }

            // Agrega filtros adicionales por identificador y país
            if ($Identifier != '') {
                array_push($rules, array("field" => "transaccion_juego.ticket_id", "data" => $Identifier, "op" => "cn"));
            }

            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "1", "op" => "ne"));

            // Prepara el filtro final y la consulta
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            // Configura la consulta según si es detalle o resumen
            if (!$IsDetails) {
                $grouping = "producto.producto_id,usuario_mandante.moneda";
                $select = " usuario_mandante.moneda,producto.*, COUNT(transaccion_juego.transjuego_id) count,SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END) apuestas, SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END) premios,SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END) apuestasBonus, SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END) premiosBonus,SUM(transaccion_juego.valor_gratis) apuestasSaldogratis,proveedor.* ";
            } else {
                $select = " usuario_mandante.moneda,producto.*,transaccion_juego.ticket_id, transaccion_juego.tipo,transaccion_juego.fecha_crea,transaccion_juego.transjuego_id,transaccion_juego.usuario_id,usuario_mandante.usuario_mandante, 1 count,CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END apuestas,CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END premios,CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END apuestasBonus,CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END premiosBonus,SUM(transaccion_juego.valor_gratis) apuestasSaldogratis,proveedor.* ";
            }

            // Ejecuta la consulta y procesa resultados
            $TransaccionJuego = new TransaccionJuego();
            $data = $TransaccionJuego->getTransaccionesCustom($select, "transaccion_juego.transjuego_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping,"",false,true);
            $data = json_decode($data);

            // Inicializa contadores
            $papuestas = 0;
            $ppremios = 0;
            $pcont = 0;

            // Procesa cada registro de la consulta
            foreach ($data->data as $key => $value) {
                $CurrencyId = $value->{"usuario_mandante.moneda"};
                $array = [];

                // Procesa datos para reporte detallado
                if ($IsDetails) {
                    $array["Identifier"] = $value->{"transaccion_juego.ticket_id"};
                    $array["Id"] = $value->{"transaccion_juego.transjuego_id"};
                    $array["Date"] = $value->{"transaccion_juego.fecha_crea"};
                    $array["Username"] = $value->{"usuario_mandante.usuario_mandante"};
                    $array["Game"] = $value->{"producto.descripcion"};
                    $array["ProviderName"] = $value->{"proveedor.descripcion"};
                    $array["Bets"] = $value->{".count"};

                    // Calcula valores monetarios con conversión de moneda
                    $Stakes = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                    $array["Stakes"] = isset($PercentValueNonSportBets) ? ($Stakes / 100) * $PercentValueNonSportBets : $Stakes;//PORCENVAAPUESNODEPOR

                    $Winnings = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                    $array["Winnings"] = isset($PercentValueNonSportsAwards) ? ($Winnings / 100) * $PercentValueNonSportsAwards : $Winnings ;//PORCENVAPREMNODEPOR

                    $StakesBonus = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestasBonus"}, 2));
                    $array["StakesBonus"] = isset($$PercentValueNonSportsBounds) ? ($StakesBonus / 100) * $PercentValueNonSportsBounds : $StakesBonus;//PORCENVABONNODEPOR

                    $WinningsBonus = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premiosBonus"}, 2));
                    $array["WinningsBonus"] = isset($PercentValueNonSportsBounds) ? ($WinningsBonus / 100) * $PercentValueNonSportsBounds : $WinningsBonus;//PORCENVABONNODEPOR

                    // Calcula ganancias y rentabilidad
                    $array["Profit"] = $array["Stakes"] - $array["Winnings"] - $array["WinningsBonus"];
                    if ($array["Stakes"] == 0) {
                        $array["Profitness"] = 0;
                    } else {
                        $array["Profitness"] = (($array["Stakes"] - $array["Winnings"] - $array["WinningsBonus"]) / $array["Stakes"]) * 100;
                    }

                    // Asigna valores adicionales
                    $array["BonusCashBack"] = 0;
                    $array["CurrencyId"] = $value->{"usuario_mandante.moneda"};
                    if ($array["CurrencyId"] == "PEN") {
                        $array["CountryIcon"] = "pe";
                    }
                    $array["FreeBalance"] = round($value->{".apuestasSaldogratis"}, 2);

                    // Determina tipo de juego
                    $array["Type"] = 'N';
                    if ($value->{"transaccion_juego.tipo"} == "FREESPIN") {
                        $array["Type"] = 'FS';
                    }

                    array_push($final, $array);
                } else {
                    // Procesa datos para reporte resumido
                    $array["Identifier"] = $value->{"transaccion_juego.ticket_id"};
                    $array["Game"] = $value->{"producto.descripcion"};
                    $array["ProviderName"] = $value->{"proveedor.descripcion"};
                    $array["Bets"] = $value->{".count"};

                    // Calcula valores monetarios con conversión de moneda
                    $Stakes = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                    $array["Stakes"] = isset($PercentValueNonSportBets) ? ($Stakes / 100) * $PercentValueNonSportBets : $Stakes;//PORCENVAAPUESNODEPOR

                    $Winnings = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                    $array["Winnings"] = isset($PercentValueNonSportsAwards) ? ($Winnings / 100) * $PercentValueNonSportsAwards : $Winnings ;//PORCENVAPREMNODEPOR


                    $StakesBonus = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestasBonus"}, 2));
                    $array["StakesBonus"] = isset($$PercentValueNonSportsBounds) ? ($StakesBonus / 100) * $PercentValueNonSportsBounds : $StakesBonus;//PORCENVABONNODEPOR

                    $WinningsBonus = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premiosBonus"}, 2));
                    $array["WinningsBonus"] = isset($PercentValueNonSportsBounds) ? ($WinningsBonus / 100) * $PercentValueNonSportsBounds : $WinningsBonus;//PORCENVABONNODEPOR

                    // Calcula ganancias y rentabilidad
                    $array["Profit"] = $array["Stakes"] - $array["Winnings"] - $array["WinningsBonus"];
                    if ($array["Stakes"] == 0) {
                        $array["Profitness"] = 0;
                    } else {
                        $array["Profitness"] = (($array["Stakes"] - $array["Winnings"] - $array["WinningsBonus"]) / $array["Stakes"]) * 100;
                    }

                    // Asigna valores adicionales
                    $array["BonusCashBack"] = 0;
                    $array["CurrencyId"] = $value->{"usuario_mandante.moneda"};
                    if ($array["CurrencyId"] == "PEN") {
                        $array["CountryIcon"] = "pe";
                    }
                    $array["FreeBalance"] = round($value->{".apuestasSaldogratis"}, 2);

                    array_push($final, $array);

                    // Reinicia contadores y actualiza totales
                    $papuestas = 0;
                    $ppremios = 0;
                    $pcont = 0;
                    $papuestas = $papuestas + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                    $ppremios = $ppremios + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                    $pcont = $pcont + $value->{".count"};

                    $prod = $value;
                }
            }
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


    setlocale(LC_ALL, 'czech');

    if (!$IsDetails) {
        /*
                $rules = [];

                array_push($rules, array("field" => "ApiTransactions.trnFecReg", "data" => "$FromDateLocal ", "op" => "ge"));
                array_push($rules, array("field" => "ApiTransactions.trnFecReg", "data" => "$ToDateLocal", "op" => "le"));

                if ($Region != "") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
                }


                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $ApiTransaction = new ApiTransaction();
                $casino2 = $ApiTransaction->getTransaccionesCustom(" usuario.moneda,'VivoGaming' ProviderName, COUNT(ApiTransactions.trnID) count,SUM(CASE WHEN ApiTransactions.trnType = 'BET' THEN ApiTransactions.trnMonto ELSE 0 END) apuestas,SUM(CASE WHEN ApiTransactions.trnType = 'WIN' THEN ApiTransactions.trnMonto ELSE 0 END) premios", "ApiTransactions.trnID", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

                $casino2 = json_decode($casino2);

                $papuestas = 0;
                $ppremios = 0;
                $pcount = 0;

                $pcont = 0;
                foreach ($casino2->data as $key => $value) {
                    $papuestas = $papuestas + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                    $ppremios = $ppremios + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                    $pcont = $pcont + $value->{".count"};


                }

                if ($pcont != 0) {
                    $array = [];

                    $array["Game"] = 'Vivogaming';
                    $array["ProviderName"] = 'Vivogaming';
                    $array["Bets"] = $pcont;
                    $array["Stakes"] = $papuestas;
                    $array["Winnings"] = $ppremios;
                    $array["Profit"] = 0;
                    $array["BonusCashBack"] = 0;


                    array_push($final, $array);
                }
        */

    }


    // Configura la respuesta base con valores exitosos
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    // Prepara la respuesta según si es detalle o no
    if (!$IsDetails) {
        // Para reporte resumido, devuelve solo los datos finales
        $response["pos"] = 0;
        $response["data"] = $final;
    } else {
        // Para reporte detallado, incluye metadata adicional
        $response["pos"] = $SkeepRows;
        $response["total_count"] = $data->count[0]->{".count"};
        $response["data"] = $final;
    }

} else {
    // Configura respuesta vacía cuando no hay datos
    $response["HasError"] = false;
    $response["AlertType"] = "success2";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    // Inicializa respuesta con valores vacíos
    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
?>
