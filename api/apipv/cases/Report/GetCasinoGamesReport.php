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
 * Obtiene el historial de apuestas realizadas en juegos de casino con filtros
 *
 * @param object $params {
 *   @type bool   $IsDetails     Indica si se requieren detalles adicionales
 *   @type string $CurrencyId    ID de la moneda
 *   @type bool   $IsTest        Indica si es ambiente de pruebas
 *   @type int    $ProductId     ID del producto/juego
 *   @type int    $ProviderId    ID del proveedor
 *   @type string $Region        Región a filtrar
 *   @type int    $MaxRows       Número máximo de registros
 *   @type string $OrderedItem   Campo de ordenamiento
 *   @type int    $SkeepRows     Registros a omitir
 *   @type string $dateTo        Fecha final (Y-m-d)
 *   @type string $dateFrom      Fecha inicial (Y-m-d)
 *   @type float  $ValueMinimum  Valor mínimo de apuesta
 *   @type float  $ValueMaximum  Valor máximo de apuesta
 *   @type string $VerticalsId   Verticales seleccionadas para el filtrado de las transacciones
 * }
 *
 * @return array {
 *   "HasError": boolean,
 *   "AlertType": string,
 *   "AlertMessage": string,
 *   "ModelErrors": array,
 *   "pos": int,
 *   "total_count": int,
 *   "data": array {
 *     "Id": int,
 *     "PlayerId": string,
 *     "GameId": string,
 *     "GameName": string,
 *     "Provider": string,
 *     "BetAmount": float,
 *     "WinAmount": float,
 *     "Currency": string,
 *     "CreatedAt": string,
 *     "Status": string
 *   }[]
 * }
 *
 * @throws Exception Si ocurre un error al procesar la consulta
 *
 * @access public
 */


// Inicializa el objeto Usuario para acceder a la información del usuario
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

// Obtiene los parámetros de paginación y ordenamiento
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;
$ToDateLocal = $params->dateTo;
$FromDateLocal = $params->dateFrom;
$ValueMinimum = $params->ValueMinimum;
$ValueMaximum = $params->ValueMaximum;

// Procesa las fechas desde los parámetros de la URL si están presentes
if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}
if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));
}

// Obtiene y valida los parámetros adicionales de filtrado
$PlayerId = $_REQUEST['PlayerId'];
$FromId = $_REQUEST['FromId'];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$SubProviderId = ($_REQUEST["SubProviderId"] > 0 && is_numeric($_REQUEST["SubProviderId"]) && $_REQUEST["SubProviderId"] != '') ? $_REQUEST["SubProviderId"] : '';
$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';
$ProductId = $_REQUEST["ProductId"];
$IsDetails = $_REQUEST["IsDetails"];
$Type = $_REQUEST["Type"];

// Obtiene los parámetros de rango de valores y otros filtros
$ValueMinimum = $_REQUEST["ValueMinimum"];
$ValueMaximum = $_REQUEST["ValueMaximum"];
$Identifier = $_REQUEST["Identifier"];
$Id = $_REQUEST["Id"];
$TypeReport = $_REQUEST["TypeReport"];
$Categorie = $_REQUEST["Categorie"];
$verticalsId = $_REQUEST["VerticalsId"];

// Ajusta los parámetros de paginación según el tipo de reporte
if ($Type == 1) {
    $IsDetails = true;
}

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($IsDetails == 1) {
    $MaxRows = 10000000000;
}

// Valida los parámetros requeridos para continuar
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

// Inicializa variables de ordenamiento
$order = "";
$orderType = "";

// Procesa los parámetros de ordenamiento desde la solicitud JSON
$data=$_REQUEST;
$data=json_encode($data);
$data=preg_replace('/\\\\/', '', $data);
$data=json_decode($data);

// Configura el ordenamiento según los parámetros recibidos
if ($data->sort != "") {
    if ($data->sort->Winnings != "") {
        $order = "transaccion_juego.valor_premio";
        $orderType = ($data->sort->Winnings == "asc") ? "asc" : "desc";
    }
    if ($data->sort->Stakes != "") {
        $order = "transaccion_juego.valor_ticket";
        $orderType = ($data->sort->Stakes == "asc") ? "asc" : "desc";
    }
    if ($data->sort->Username != "") {
        $order = "transaccion_juego.usuario_id";
        $orderType = ($data->sort->Username == "asc") ? "asc" : "desc";
    }
    if ($data->sort->date != "") {
        $order = "transaccion_juego.fecha_crea";
        $orderType = ($data->sort->date == "asc") ? "asc" : "desc";
    }
    if ($data->sort->Date != "") {
        $order = "transaccion_juego.fecha_crea";
        $orderType = ($data->sort->Date == "asc") ? "asc" : "desc";
    }
}

// Ajusta el ordenamiento para reportes detallados
if ($IsDetails) {
    $order = "usucasino_detalle_resumen.usucasdetresume_id";
    $orderType = "desc";

    if ($data->sort->Date != "") {
        $order = "usucasino_detalle_resumen.fecha_crea";
        $orderType = ($data->sort->Date == "asc") ? "asc" : "desc";
    }

    if ($data->sort->Stakes != "") {
        $order = "usucasino_detalle_resumen.valor";
        $orderType = ($data->sort->Stakes == "asc") ? "asc" : "desc";
    }
}


// Verifica si se debe continuar con el procesamiento
if ($seguir) {

    // Maneja el estado de detalles basado en el valor actual
    if ($IsDetails == 1) {
        $IsDetails = false;
    } else {
        $IsDetails = true;
    }

    // Si está en modo detalles, configura las reglas de filtrado
    if ($IsDetails) {
        $rules = [];

        // Agrega reglas de filtrado por fecha inicial si está definida
        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            // array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));
        }

        // Agrega reglas de filtrado por fecha final si está definida
        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
            // array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));
        }

        // Configura filtros por producto según permisos globales
        if ($ProductId != "") {
            if ($_SESSION['Global'] == "S") {
                array_push($rules, array("field" => "producto.producto_id", "data" => "$ProductId", "op" => "eq"));
            } else {
                array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$ProductId", "op" => "eq"));
            }
        }

        // Agrega filtros por región y proveedor
        if ($Region != "") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($ProviderId != "") {
            array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
        }

        // Configura filtros adicionales por subproveedor y jugador
        if ($SubProviderId != "") {
            array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
        }

        if ($PlayerId != "") {
            array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => "$PlayerId", "op" => "eq"));
        }

        // Maneja filtros por ID y tipo de juego
        if ($FromId) {
            array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => "$FromId", "op" => "eq"));
        }

        if ($Type != "") {
            if ($Type == "N") {
                array_push($rules, array("field" => "transaccion_juego.tipo", "data" => "'NORMAL',''", "op" => "in"));
            }
            if ($Type == "FS") {
                array_push($rules, array("field" => "transaccion_juego.tipo", "data" => "FREESPIN", "op" => "eq"));
            }
        }

        // Aplica filtros por país según permisos y configuración
        if ($CountrySelect != "" && $CountrySelect != "0") {
            if ($_SESSION['Global'] != "S") {
                if ($_SESSION['mandante'] != "14") {
                    array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
                }
            }
        }

        // Configura filtros por valores máximos y mínimos
        if ($ValueMaximum != "" && $ValueMaximum != "0") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$ValueMaximum", "op" => "le"));
        }

        if ($ValueMinimum != "" && $ValueMinimum != "0") {
            array_push($rules, array("field" => "transaccion_juego.valor_ticket", "data" => "$ValueMinimum", "op" => "ge"));
        }

        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            if ($_SESSION['mandante'] != "14") {
                array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
        }

        // Configura filtros según permisos globales y mandante
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {
            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "1", "op" => "ne"));
        }

        // Agrega filtros por identificador y ID de transacción
        if ($Identifier != '') {
            array_push($rules, array("field" => "transaccion_juego.ticket_id", "data" => $Identifier, "op" => "cn"));
        }

        if ($Id != '') {
            array_push($rules, array("field" => "transaccion_juego.transjuego_id", "data" => $Id, "op" => "eq"));
        }

        // Configura reglas adicionales basadas en ordenamiento
        if ($data->sort != "") {
            if ($data->sort->Winnings != "") {
                array_push($rules, array("field" => "transaccion_juego.valor_premio", "data" => "0", "op" => "ge"));
            }
            if ($data->sort->Stakes != "") {
                array_push($rules, array("field" => "transaccion_juego.valor_ticket", "data" => "0", "op" => "ge"));
            }
            if ($data->sort->Username != "") {
                array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => "0", "op" => "ge"));
            }
            if ($data->sort->date != "") {
            }
        }

        // Aplica filtros por tipo de reporte
        if ($TypeReport != '') {
            switch ($TypeReport){
                case 'informationCasino':
                    array_push($rules, array("field" => "subproveedor.tipo", "data" => 'CASINO', "op" => "eq"));
                    break;
                case 'informationLiveCasino':
                    array_push($rules, array("field" => "subproveedor.tipo", "data" => 'LIVECASINO', "op" => "eq"));
                    break;
                case 'informationVirtual':
                    array_push($rules, array("field" => "subproveedor.tipo", "data" => 'VIRTUAL', "op" => "eq"));
                    break;
                case 'informationHipica':
                    array_push($rules, array("field" => "subproveedor.tipo", "data" => 'HIPICA', "op" => "eq"));
                    break;
                    case 'informationLoteria':
                    array_push($rules, array("field" => "subproveedor.tipo", "data" => 'LOTTERY', "op" => "eq"));
                    break;
            }
        }

        if (!empty($verticalsId)) {
            /* Aplicación de filtrado por tipo de vertical (Similiar a TypeReport) Acepta múltiples verticales de forma concurrente */
            /*Separa los IDs de verticales seleccionados*/
            $verticalsChoicedOptions = explode(',', $verticalsId);

            /*Inicializa un string que contendrá las verticales a filtrar mediante los subproveedores*/
            $verticalsFilterablesBySubprovider = "";
            $pokerExternals = [];
            $filterablesBySubprovidersMap = [
                1 => 'CASINO',
                2 => 'LIVECASINO',
                3 => 'VIRTUAL',
                4 => 'LOTTERY'
            ];

            /*Si la verticalOption corresponde a una llave en el map, se filtrará con el valor almacenado en el array */
            foreach ($verticalsChoicedOptions as $verticalOption) {
                if (array_key_exists($verticalOption, $filterablesBySubprovidersMap)) {
                    $verticalsFilterablesBySubprovider .= (!empty($verticalsFilterablesBySubprovider) ? "," : "") . "'$filterablesBySubprovidersMap[$verticalOption]'";
                    continue;
                }
            }

            /* Generación de las SQL para el filtrado por verticales */
            if (!empty($verticalsFilterablesBySubprovider)) {
                $rules[] = ["field" => "subproveedor.tipo", "data" => $verticalsFilterablesBySubprovider, "op" => "in"];
            }
        }

        // Prepara el filtro final y lo convierte a JSON
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        // Configura los campos de selección y agrupamiento según el modo
        if (!$IsDetails) {
            $grouping = "producto.producto_id,usuario_mandante.moneda";
            $select = " usuario_mandante.moneda,producto.*, COUNT(transaccion_juego.transjuego_id) count,SUM(transaccion_juego.impuesto) impuesto,SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END) apuestas, SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END) premios,SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END) apuestasBonus, SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END) premiosBonus,SUM(transaccion_juego.valor_gratis) apuestasSaldogratis,proveedor.*,subproveedor.descripcion";
        } else {
            $select = " usuario_mandante.moneda,producto.*, transaccion_juego.ticket_id,transaccion_juego.tipo,transaccion_juego.fecha_crea,transaccion_juego.transjuego_id,transaccion_juego.impuesto,transaccion_juego.usuario_id,usuario_mandante.usuario_mandante, 1 count,CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END apuestas,CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END premios,CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END apuestasBonus,CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END premiosBonus,(transaccion_juego.valor_gratis) apuestasSaldogratis,proveedor.*,subproveedor.descripcion";
        }

        // Ejecuta la consulta y procesa los resultados
        $TransaccionJuego = new TransaccionJuego();
        $data = $TransaccionJuego->getTransaccionesCustom($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping, "", true, false);
        $data = json_decode($data);

        // Inicializa variables para el procesamiento de resultados
        $final = [];
        $papuestas = 0;
        $ppremios = 0;
        $pcont = 0;

        // Itera sobre los datos obtenidos para procesarlos y formatearlos
        foreach ($data->data as $key => $value) {

            $CurrencyId = $value->{"usuario_mandante.moneda"};
            $array = [];

            // Procesa los datos en modo detallado incluyendo información específica de cada transacción
            if ($IsDetails) {
                $array["Identifier"] = $value->{"transaccion_juego.ticket_id"};
                $array["Id"] = $value->{"transaccion_juego.transjuego_id"};
                $array["TaxBet"] = $value->{"transaccion_juego.impuesto"};
                $array["UserId"] = $value->{"usuario_mandante.usuario_mandante"};
                $array["Date"] = $value->{"transaccion_juego.fecha_crea"};
                $array["Username"] = $value->{"usuario_mandante.usuario_mandante"};
                $array["Game"] = $value->{"producto.descripcion"};
                $array["ProviderName"] = $value->{"proveedor.descripcion"};

                // Calcula los valores monetarios convirtiendo las monedas según sea necesario
                $array["SubProviderName"] = $value->{"subproveedor.descripcion"};
                $array["Bets"] = $value->{".count"};
                $array["Stakes"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                $array["Winnings"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                $array["StakesBonus"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestasBonus"}, 2));
                $array["WinningsBonus"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premiosBonus"}, 2));
                $array["FreeBalance"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{"transaccion_juego.apuestasSaldogratis"}, 2));
                if($FromDateLocal < "2025-03-01 00:00:00"){
                    $array["Stakes"]= floatval($array["FreeBalance"])+floatval($array["Stakes"]);
                }
                //  $array["Stakes"]= floatval($array["FreeBalance"])+floatval($array["Stakes"]);

                // Calcula métricas de rentabilidad y ganancias
                $array["Bonus"] = 0;
                $array["Profit"] = $array["Stakes"] - $array["Winnings"] - $array["WinningsBonus"];
                if ($array["Stakes"] == 0) {
                    $array["Profitness"] = 0;
                } else {
                    $array["Profitness"] = (($array["Stakes"] - $array["Winnings"] - $array["WinningsBonus"]) / ($array["Stakes"])) * 100;
                }

                // Configura información adicional de la transacción
                $array["BonusCashBack"] = 0;
                $array["CurrencyId"] = $value->{"usuario_mandante.moneda"};
                if ($array["CurrencyId"] == "PEN") {
                    $array["CountryIcon"] = "pe";
                }

                $array["Type"] = 'N';
                if ($value->{"transaccion_juego.tipo"} == "FREESPIN") {
                    $array["Type"] = 'FS';
                }
                $array["AwardValue"] = $value->{".premios"};
                array_push($final, $array);

            } else {
                // Procesa los datos en modo resumen agrupando por juego
                $array["Identifier"] = $value->{"transaccion_juego.ticket_id"};
                $array["TaxBet"] = $value->{".impuesto"};
                $array["Game"] = $value->{"producto.descripcion"};
                $array["ProviderName"] = $value->{"proveedor.descripcion"};
                $array["SubProviderName"] = $value->{"subproveedor.descripcion"};

                // Calcula totales y métricas agregadas
                $array["Bets"] = $value->{".count"};
                $array["Stakes"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                $array["Winnings"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                $array["StakesBonus"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestasBonus"}, 2));
                $array["WinningsBonus"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premiosBonus"}, 2));
                $array["FreeBalance"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestasSaldogratis"}, 2));
                if($FromDateLocal < "2025-03-01 00:00:00") {
                    $array["Stakes"]= floatval($array["FreeBalance"])+floatval($array["Stakes"]);
                }
                //$array["Stakes"]= floatval($array["FreeBalance"])+floatval($array["Stakes"]);

                // Calcula métricas de rentabilidad para el resumen
                $array["Profit"] = $array["Stakes"]   - $array["Winnings"] - $array["WinningsBonus"];
                if ($array["Stakes"] == 0) {
                    $array["Profitness"] = 0;
                } else {
                    $array["Profitness"] = (($array["Stakes"]   - $array["Winnings"] - $array["WinningsBonus"]) / ($array["Stakes"] )) * 100;
                }

                // Configura valores adicionales del resumen
                $array["Bonus"] = 0;
                $array["TotalJackpotPrizeSum"] = 0;
                $array["BonusCashBack"] = 0;
                $array["CurrencyId"] = $value->{"usuario_mandante.moneda"};
                if ($array["CurrencyId"] == "PEN") {
                    $array["CountryIcon"] = "pe";
                }

                $array["AwardValue"] = $value->{".premios"};
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
    } else {

        // Inicializa el array de reglas para los filtros
        $rules = [];

        // Agrega reglas de filtrado por fecha inicial si está definida
        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "usucasino_detalle_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
        }

        // Agrega reglas de filtrado por fecha final si está definida
        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "usucasino_detalle_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
        }

        // Configura filtros por producto según permisos globales
        if ($ProductId != "") {
            if ($_SESSION['Global'] == "S") {
                array_push($rules, array("field" => "producto.producto_id", "data" => "$ProductId", "op" => "eq"));
            } else {
                array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$ProductId", "op" => "eq"));
            }
        }

        // Agrega filtros por región y proveedor
        if ($Region != "") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($ProviderId != "") {
            array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
        }

        // Configura filtros adicionales por subproveedor y jugador
        if ($SubProviderId != "") {
            array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
        }

        if ($PlayerId != "") {
            array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => "$PlayerId", "op" => "eq"));
        }

        // Maneja filtros por tipo de transacción (Normal o FreeSpin)
        if ($Type != "") {
            if ($Type == "N") {
                array_push($rules, array("field" => "usucasino_detalle_resumen.tipo", "data" => "'DEBIT','CREDIT','ROLLBACK'", "op" => "in"));
            }
            if ($Type == "FS") {
                array_push($rules, array("field" => "usucasino_detalle_resumen.tipo", "data" => "'DEBITFREESPIN','CREDITFREESPIN','ROLLBACKFREESPIN'", "op" => "in"));
            }
        }

        // Aplica filtros por país según configuración y permisos
        if ($CountrySelect != "" && $CountrySelect != "0") {
            if ($_SESSION['PaisCond'] != "S") {
                if ($_SESSION['mandante'] != "14") {
                    array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
                }
            }
        }

        // Agrega filtros por valores máximos y mínimos
        if ($ValueMaximum != "" && $ValueMaximum != "0") {
            array_push($rules, array("field" => "usucasino_detalle_resumen.valor", "data" => "$ValueMaximum", "op" => "le"));
        }

        if ($ValueMinimum != "" && $ValueMinimum != "0") {
            array_push($rules, array("field" => "usucasino_detalle_resumen.valor", "data" => "$ValueMinimum", "op" => "ge"));
        }

        // Configura filtros adicionales basados en permisos de usuario
        if ($_SESSION['PaisCond'] == "S") {
                if ($_SESSION['mandante'] != "14") {
                    array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                }
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {
            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }
        }

        // Aplica filtros por tipo de reporte
        if ($TypeReport != '') {
            switch ($TypeReport){
                case 'informationCasino':
                    array_push($rules, array("field" => "subproveedor.tipo", "data" => 'CASINO', "op" => "eq"));
                    break;
                case 'informationLiveCasino':
                    array_push($rules, array("field" => "subproveedor.tipo", "data" => 'LIVECASINO', "op" => "eq"));
                    break;
                case 'informationVirtual':
                    array_push($rules, array("field" => "subproveedor.tipo", "data" => 'VIRTUAL', "op" => "eq"));
                    break;
                case 'informationHipica':
                    array_push($rules, array("field" => "subproveedor.tipo", "data" => 'HIPICA', "op" => "eq"));
                    break;
                case 'informationLoteria':
                array_push($rules, array("field" => "subproveedor.tipo", "data" => 'LOTTERY', "op" => "eq"));
                break;
            }
        }

        if (!empty($verticalsId)) {
            /* Aplicación de filtrado por tipo de vertical (Similiar a TypeReport) Acepta múltiples verticales de forma concurrente */
            /*Separa los IDs de verticales seleccionados*/
            $verticalsChoicedOptions = explode(',', $verticalsId);

            /*Inicializa un string que contendrá las verticales a filtrar mediante los subproveedores*/
            $verticalsFilterablesBySubprovider = "";
            $pokerExternals = [];
            $filterablesBySubprovidersMap = [
                1 => 'CASINO',
                2 => 'LIVECASINO',
                3 => 'VIRTUAL',
                4 => 'LOTTERY'
            ];

            /*Si la verticalOption corresponde a una llave en el map, se filtrará con el valor almacenado en el array */
            foreach ($verticalsChoicedOptions as $verticalOption) {
                if (array_key_exists($verticalOption, $filterablesBySubprovidersMap)) {
                    $verticalsFilterablesBySubprovider .= (!empty($verticalsFilterablesBySubprovider) ? "," : "") . "'$filterablesBySubprovidersMap[$verticalOption]'";
                    continue;
                }
            }

            /* Generación de las SQL para el filtrado por verticales */
            if (!empty($verticalsFilterablesBySubprovider)) {
                $rules[] = ["field" => "subproveedor.tipo", "data" => $verticalsFilterablesBySubprovider, "op" => "in"];
            }
        }


        // Prepara el filtro final y lo convierte a JSON
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        // Configura los campos de selección y agrupamiento
        $select = " usuario_mandante.moneda,producto.*, SUM(CASE WHEN usucasino_detalle_resumen.tipo IN ('DEBIT','DEBITFREESPIN','DEBITFREECASH' ) THEN usucasino_detalle_resumen.cantidad ELSE 0 END) count,SUM(CASE WHEN usucasino_detalle_resumen.tipo IN ('DEBIT','DEBITFREECASH' ) THEN usucasino_detalle_resumen.valor ELSE 0 END) apuestas, SUM(CASE WHEN usucasino_detalle_resumen.tipo IN( 'CREDIT','ROLLBACK','CREDITFREECASH') THEN usucasino_detalle_resumen.valor ELSE 0 END) premios,SUM(CASE WHEN usucasino_detalle_resumen.tipo = 'DEBITFREESPIN' THEN usucasino_detalle_resumen.valor ELSE 0 END) apuestasBonus,SUM(CASE WHEN usucasino_detalle_resumen.tipo = 'DEBITFREECASH' THEN usucasino_detalle_resumen.valor_premios ELSE 0 END) apuestaFreecash, SUM(CASE WHEN usucasino_detalle_resumen.tipo IN ('CREDITFREESPIN') THEN usucasino_detalle_resumen.valor ELSE 0 END) premiosBonus,proveedor.*,subproveedor.descripcion ";


        $grouping = "producto.producto_id,usuario_mandante.moneda";
        //$select = " usuario_mandante.moneda,producto.*, SUM(CASE WHEN usucasino_detalle_resumen.tipo IN ('DEBIT','DEBITFREESPIN') THEN usucasino_detalle_resumen.cantidad ELSE 0 END) count,SUM(CASE WHEN usucasino_detalle_resumen.tipo IN ('DEBIT') THEN usucasino_detalle_resumen.valor ELSE 0 END) apuestas, SUM(CASE WHEN usucasino_detalle_resumen.tipo IN( 'CREDIT','ROLLBACK','CREDITFREECASH') THEN usucasino_detalle_resumen.valor ELSE 0 END) premios,SUM(CASE WHEN usucasino_detalle_resumen.tipo = 'DEBITFREESPIN' THEN usucasino_detalle_resumen.valor ELSE 0 END) apuestasBonus,SUM(CASE WHEN usucasino_detalle_resumen.tipo = 'DEBITFREECASH' THEN usucasino_detalle_resumen.valor_premios ELSE 0 END) apuestaFreecash, SUM(CASE WHEN usucasino_detalle_resumen.tipo IN ('CREDITFREESPIN') THEN usucasino_detalle_resumen.valor ELSE 0 END) premiosBonus,proveedor.*,subproveedor.descripcion ";

        // Ejecuta la consulta y procesa los resultados
        $UsucasinoDetalleResumen = new UsucasinoDetalleResumen();
        $data = $UsucasinoDetalleResumen->getUsucasinoDetalleResumenCustom($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping);
        $data = json_decode($data);

        // Inicializa variables para el procesamiento de resultados
        $final = [];
        $papuestas = 0;
        $ppremios = 0;
        $pcont = 0;

        // Itera sobre los datos obtenidos para procesarlos
        foreach ($data->data as $key => $value) {

            $CurrencyId = $value->{"usuario_mandante.moneda"};
            $array = [];

            // Extrae la información básica del juego y proveedor
            $array["Game"] = $value->{"producto.descripcion"};
            $array["ProviderName"] = $value->{"proveedor.descripcion"};
            $array["SubProviderName"] = $value->{"subproveedor.descripcion"};
            $array["Bets"] = $value->{".count"};

            // Realiza las conversiones de moneda para los valores monetarios
            $array["Stakes"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
            $array["Winnings"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
            $array["StakesBonus"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestasBonus"}, 2));
            $array["WinningsBonus"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premiosBonus"}, 2));
            $array["FreeBalance"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestaFreecash"}, 2));

            if($FromDateLocal < "2025-03-01 00:00:00"){
                $array["Stakes"]= floatval($array["FreeBalance"])+floatval($array["Stakes"]); //en esta linea esta sumando a apuestas el saldo gratis
                $array["Profit"] = $array["Stakes"]  - $array["Winnings"] - $array["WinningsBonus"] ;
                if ($array["Stakes"] == 0) {
                    $array["Profitness"] = 0;
                } else {
                    $array["Profitness"] = (($array["Stakes"]   - $array["Winnings"] - $array["WinningsBonus"] ) / ($array["Stakes"] )) * 100;
                }

            }else{
                $array["Profit"] = $array["Stakes"]  - $array["Winnings"] - $array["WinningsBonus"] -$array['StakesBonus']; // modificamos esta linea GGR= Apuestas−Premios−Bonos−Premios Bonos
                if ($array["Stakes"] == 0) {
                    $array["Profitness"] = 0;
                } else {
                    $array["Profitness"] = (($array["Stakes"]   - $array["Winnings"] - $array["WinningsBonus"]-$array['StakesBonus'] ) / ($array["Stakes"] )) * 100;
                }
            }

            //$array["Stakes"]= floatval($array["FreeBalance"])+floatval($array["Stakes"]); en esta linea esta sumando a apuestas el saldo gratis

            // Calcula métricas de bonificación y ganancias
            $array["Bonus"] = 0;
            $array["TotalJackpotPrizeSum"] = 0;

            //$array["Profit"] = $array["Stakes"]  - $array["Winnings"] - $array["WinningsBonus"] -$array['StakesBonus']; // modificamos esta linea GGR= Apuestas−Premios−Bonos−Premios Bonos
           /* if ($array["Stakes"] == 0) {
                $array["Profitness"] = 0;
            } else {
                $array["Profitness"] = (($array["Stakes"]   - $array["Winnings"] - $array["WinningsBonus"]-$array['StakesBonus'] ) / ($array["Stakes"] )) * 100;
            }
           */

            // Configura información adicional de moneda y país
            $array["BonusCashBack"] = 0;
            $array["CurrencyId"] = $value->{"usuario_mandante.moneda"};
            if ($array["CurrencyId"] == "PEN") {
                $array["CountryIcon"] = "pe";
            }

            // Agrega el registro procesado al array final
            array_push($final, $array);

            // Reinicia y actualiza contadores totales
            $papuestas = 0;
            $ppremios = 0;
            $pcont = 0;
            $papuestas = $papuestas + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
            $ppremios = $ppremios + (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
            $pcont = $pcont + $value->{".count"};

            $prod = $value;
        }

        // Verifica si es el reporte del día actual y procesa filtros adicionales
        if ($TypeReport == '' && date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour ')) == date("Y-m-d", time())) {

            $rules = [];

            // Aplica filtros de fecha
            if ($FromDateLocal != "") {
                array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
            }

            if ($ToDateLocal != "") {
                 array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
            }

            // Aplica filtros de producto y región
            if ($ProductId != "") {
                if ($_SESSION['Global'] == "S") {
                    array_push($rules, array("field" => "producto.producto_id", "data" => "$ProductId", "op" => "eq"));
                } else {
                    array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$ProductId", "op" => "eq"));
                }
            }

            if ($Region != "") {
                array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$Region", "op" => "eq"));
            }

            // Aplica filtros de proveedor y subproveedor
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
                    array_push($rules, array("field" => "transaccion_juego.tipo", "data" => "'NORMAL',''", "op" => "in"));
                }
                if ($Type == "FS") {
                    array_push($rules, array("field" => "transaccion_juego.tipo", "data" => "FREESPIN", "op" => "eq"));
                }
            }

            // Aplica filtros de país y valores
            if ($CountrySelect != "" && $CountrySelect != "0") {
                if ($_SESSION['PaisCond'] != "S") {
                    if ($_SESSION['mandante'] != "14") {
                        array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
                    }
                }
            }

            if ($ValueMaximum != "" && $ValueMaximum != "0") {
                array_push($rules, array("field" => "transaccion_juego.valor_ticket", "data" => "$ValueMaximum", "op" => "le"));
            }

            if ($ValueMinimum != "" && $ValueMinimum != "0") {
                array_push($rules, array("field" => "transaccion_juego.valor_ticket", "data" => "$ValueMinimum", "op" => "ge"));
            }

            // Aplica condiciones de país y mandante según la sesión
            if ($_SESSION['PaisCond'] == "S") {
                    if ($_SESSION['mandante'] != "14") {
                        array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                    }
            }
            // Si el usuario esta condicionado por el mandante y no es de Global
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {
                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }
            }

            // Aplica filtros de identificador y tipo de reporte
            if ($Identifier != '') {
                array_push($rules, array("field" => "transaccion_juego.ticket_id", "data" => $Identifier, "op" => "cn"));
            }

            if ($TypeReport != '') {
                switch ($TypeReport){
                    case 'informationCasino':
                        array_push($rules, array("field" => "subproveedor.tipo", "data" => 'CASINO', "op" => "eq"));
                        break;
                    case 'informationLiveCasino':
                        array_push($rules, array("field" => "subproveedor.tipo", "data" => 'LIVECASINO', "op" => "eq"));
                        break;
                    case 'informationVirtual':
                        array_push($rules, array("field" => "subproveedor.tipo", "data" => 'VIRTUAL', "op" => "eq"));
                        break;
                    case 'informationHipica':
                        array_push($rules, array("field" => "subproveedor.tipo", "data" => 'HIPICA', "op" => "eq"));
                        break;
                    case 'informationLoteria':
                        array_push($rules, array("field" => "subproveedor.tipo", "data" => 'LOTTERY', "op" => "eq"));
                        break;
                }
            }

            if (!empty($verticalsId)) {
                /* Aplicación de filtrado por tipo de vertical (Similiar a TypeReport) Acepta múltiples verticales de forma concurrente */
                /*Separa los IDs de verticales seleccionados*/
                $verticalsChoicedOptions = explode(',', $verticalsId);

                /*Inicializa un string que contendrá las verticales a filtrar mediante los subproveedores*/
                $verticalsFilterablesBySubprovider = "";
                $pokerExternals = [];
                $filterablesBySubprovidersMap = [
                    1 => 'CASINO',
                    2 => 'LIVECASINO',
                    3 => 'VIRTUAL',
                    4 => 'LOTTERY'
                ];

                /*Si la verticalOption corresponde a una llave en el map, se filtrará con el valor almacenado en el array */
                foreach ($verticalsChoicedOptions as $verticalOption) {
                    if (array_key_exists($verticalOption, $filterablesBySubprovidersMap)) {
                        $verticalsFilterablesBySubprovider .= (!empty($verticalsFilterablesBySubprovider) ? "," : "") . "'$filterablesBySubprovidersMap[$verticalOption]'";
                        continue;
                    }
                }

                /* Generación de las SQL para el filtrado por verticales */
                if (!empty($verticalsFilterablesBySubprovider)) {
                    $rules[] = ["field" => "subproveedor.tipo", "data" => $verticalsFilterablesBySubprovider, "op" => "in"];
                }
            }


            // Aplica filtros finales y prepara la consulta
            if ($Id != '') {
                array_push($rules, array("field" => "transaccion_juego.transjuego_id", "data" => $Id, "op" => "eq"));
            }
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "1", "op" => "ne"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            // Configura los campos de selección según el tipo de reporte
            if (!$IsDetails) {
                $grouping = "producto.producto_id,usuario_mandante.pais_id";
                $select = " usuario_mandante.moneda,producto.*, COUNT(transaccion_juego.transjuego_id) count,SUM(transaccion_juego.impuesto) impuesto,SUM(CASE WHEN transaccion_juego.tipo NOT IN ('FREESPIN','FREECASH') THEN transaccion_juego.valor_ticket ELSE 0 END) apuestas, SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END) premios,SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END) apuestasBonus, SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END) premiosBonus,SUM(transaccion_juego.valor_gratis) apuestasSaldogratis,proveedor.*,subproveedor.descripcion ";
            } else {
                $select = " usuario_mandante.moneda,producto.*,transaccion_juego.ticket_id,transaccion_juego.impuesto, transaccion_juego.tipo,transaccion_juego.fecha_crea,transaccion_juego.transjuego_id,transaccion_juego.usuario_id,usuario_mandante.usuario_mandante, 1 count,CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END apuestas,CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END premios,CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END apuestasBonus,CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END premiosBonus,SUM(transaccion_juego.valor_gratis) apuestasSaldogratis,proveedor.*,subproveedor.descripcion ";
            }

            // Ejecuta la consulta final
            $TransaccionJuego = new TransaccionJuego();
            $data = $TransaccionJuego->getTransaccionesCustom($select, "transaccion_juego.transjuego_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping, "", false, true);
            $data = json_decode($data);

            // Reinicia contadores para el nuevo conjunto de datos
            $papuestas = 0;
            $ppremios = 0;
            $pcont = 0;

            // Itera sobre los datos obtenidos de la consulta
            foreach ($data->data as $key => $value) {

                $CurrencyId = $value->{"usuario_mandante.moneda"};
                $array = [];

                // Procesa los detalles si IsDetails es verdadero
                if ($IsDetails) {
                    // Asigna valores básicos de la transacción
                    $array["Identifier"] = $value->{"transaccion_juego.ticket_id"};
                    $array["Id"] = $value->{"transaccion_juego.transjuego_id"};
                    $array["TaxBet"] = $value->{"transaccion_juego.impuesto"};
                    $array["Date"] = $value->{"transaccion_juego.fecha_crea"};
                    $array["Username"] = $value->{"usuario_mandante.usuario_mandante"};
                    $array["Game"] = $value->{"producto.descripcion"};
                    $array["ProviderName"] = $value->{"proveedor.descripcion"};
                    $array["SubProviderName"] = $value->{"subproveedor.descripcion"};

                    // Calcula valores monetarios y conversiones de moneda
                    $array["Bets"] = $value->{".count"};
                    $array["Stakes"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                    $array["Winnings"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                    $array["StakesBonus"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestasBonus"}, 2));
                    $array["WinningsBonus"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premiosBonus"}, 2));
                    $array["FreeBalance"] = round($value->{".apuestasSaldogratis"}, 2);
                    //$array["Stakes"]= floatval($array["FreeBalance"])+floatval($array["Stakes"]);
                    if($FromDateLocal < "2025-03-01 00:00:00"){
                        $array["Stakes"]= floatval($array["FreeBalance"])+floatval($array["Stakes"]);
                    }

                    // Calcula ganancias y rentabilidad
                    $array["Profit"] = $array["Stakes"]   - $array["Winnings"] - $array["WinningsBonus"] ;
                    if ($array["Stakes"] == 0) {
                        $array["Profitness"] = 0;
                    } else {
                        $array["Profitness"] = (($array["Stakes"]  - $array["Winnings"] - $array["WinningsBonus"] ) / ($array["Stakes"] )) * 100;
                    }

                    // Configura valores adicionales y bonificaciones
                    $array["Bonus"] = 0;
                    $array["TotalJackpotPrizeSum"] = 0;
                    $array["BonusCashBack"] = 0;
                    $array["CurrencyId"] = $value->{"usuario_mandante.moneda"};
                    if ($array["CurrencyId"] == "PEN") {
                        $array["CountryIcon"] = "pe";
                    }

                    $array["Bonus"] = 0;

                    // Determina el tipo de transacción
                    $array["Type"] = 'N';
                    if ($value->{"transaccion_juego.tipo"} == "FREESPIN") {
                        $array["Type"] = 'FS';
                    }

                    array_push($final, $array);

                } else {
                    // Procesa datos para resumen (no detalles)
                    // Asigna valores básicos del resumen
                    $array["Identifier"] = $value->{"transaccion_juego.ticket_id"};
                    $array["TaxBet"] = $value->{".impuesto"};
                    $array["Game"] = $value->{"producto.descripcion"};
                    $array["ProviderName"] = $value->{"proveedor.descripcion"};
                    $array["SubProviderName"] = $value->{"subproveedor.descripcion"};

                    // Calcula valores monetarios y conversiones
                    $array["Bets"] = $value->{".count"};
                    $array["Stakes"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestas"}, 2));
                    $array["Winnings"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premios"}, 2));
                    $array["StakesBonus"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".apuestasBonus"}, 2));
                    $array["WinningsBonus"] = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $CurrencyId, round($value->{".premiosBonus"}, 2));
                    $array["FreeBalance"] = round($value->{".apuestasSaldogratis"}, 2);
                    //$array["Stakes"]= floatval($array["FreeBalance"])+floatval($array["Stakes"]);
                    if($FromDateLocal < "2025-03-01 00:00:00"){
                        $array["Stakes"]= floatval($array["FreeBalance"])+floatval($array["Stakes"]);
                    }

                    // Calcula totales y ganancias
                    $array["TotalJackpotPrizeSum"] = 0;
                    $array["Profit"] = $array["Stakes"]   - $array["Winnings"] - $array["WinningsBonus"] ;

                    // Calcula rentabilidad
                    if ($array["Stakes"] == 0) {
                        $array["Profitness"] = 0;
                    } else {
                        $array["Profitness"] = (($array["Stakes"]   - $array["Winnings"] - $array["WinningsBonus"] ) / ($array["Stakes"])) * 100;
                    }

                    // Configura valores adicionales
                    $array["Bonus"] = 0;
                    $array["BonusCashBack"] = 0;
                    $array["CurrencyId"] = $value->{"usuario_mandante.moneda"};
                    if ($array["CurrencyId"] == "PEN") {
                        $array["CountryIcon"] = "pe";
                    }

                    array_push($final, $array);

                    // Actualiza contadores totales
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

        // Verifica si se debe procesar información de bonos
        if($TypeReport == '' && $FromDateLocal>='2021-10-01 00:00:00') {

            // Inicializa variables y reglas para consulta de bonos
            $rules = [];
            $SkeepRows=0;
            $MaxRows=100;

            // Aplica filtros de fecha para bonos
            if ($FromDateLocal != "") {
                array_push($rules, array("field" => "bono_log.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            }

            if ($ToDateLocal != "") {
                array_push($rules, array("field" => "bono_log.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
            }

            // Aplica filtros adicionales para bonos
            if ($PlayerId != "") {
                array_push($rules, array("field" => "bono_log.usuario_id", "data" => "$PlayerId", "op" => "eq"));
            }

            // Procesa filtros por país y región
            if ($CountrySelect != "" && $CountrySelect != "0") {
                if ($_SESSION['mandante'] != "14") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
                }
            }

            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }

            // Aplica reglas de sesión y mandante
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {
                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }
            }

            array_push($rules, array("field" => "bono_log.estado", "data" => "L", "op" => "eq"));

            /*Variable corresponde a los tipos de logs por vertical*/
            $bonoLogsForBonus = [
                'CASINO' => "'TC','SC','DC','NC'",
                'LIVECASINO' => "'TL','SCV','SL','DL','NL'",
                'VIRTUAL' => "'TV','DV','NV'",
            ];

            $requiredLogs = "";
            if (!empty($verticalsId)) {
                /* Aplicación de filtrado por tipo de vertical (Similiar a TypeReport) Acepta múltiples verticales de forma concurrente */
                /*Separa los IDs de verticales seleccionados*/
                $verticalsChoicedOptions = explode(',', $verticalsId);

                $filterablesBySubprovidersMap = [
                    1 => 'CASINO',
                    2 => 'LIVECASINO',
                    3 => 'VIRTUAL',
                    4 => 'LOTTERY'
                ];

                foreach ($verticalsChoicedOptions as $option) {
                    /*Verifica que la opción corresponda a una vertical válida*/
                    if (!isset($filterablesBySubprovidersMap[$option])) continue;
                    $currentVertical = $filterablesBySubprovidersMap[$option];

                    /*Verifica que la vertical tenga logs correspondientes y genera la cadena de consulta*/
                    if (!isset($bonoLogsForBonus[$currentVertical])) continue;
                    $requiredLogs .= (!empty($requiredLogs) ? "," : "") . $bonoLogsForBonus[$currentVertical];
                }
            }
            else {
                $requiredLogs = implode(",", $bonoLogsForBonus);
            }

            /*En caso de no existir tipos relacionados a la vertical requerida en bonos, se salta al final sin hacer la consulta*/
            if (empty($requiredLogs)) goto EndBonusRequest;
            array_push($rules, array("field" => "bono_log.tipo", "data" => "$requiredLogs", "op" => "in"));

            // Prepara y ejecuta consulta de bonos
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);
            $select = " SUM(bono_log.valor) valor,usuario.moneda ";

            $BonoLog = new BonoLog();
            $data = $BonoLog->getBonoLogsCustom($select, "bono_log.bonolog_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");
            $data = json_decode($data);

            // Procesa resultados de bonos
            foreach ($data->data as $value) {
                $CurrencyId = $value->{"usuario_mandante.moneda"};
                $array = [];

                // Configura valores para registro de bonos
                $array["Game"] = 'Bonos Casino';
                $array["ProviderName"] = 'Bonos Casino';
                $array["SubProviderName"] = "";
                $array["Bets"] = 0;
                $array["Stakes"] = 0;
                $array["Winnings"] = 0;
                $array["StakesBonus"] = 0;
                $array["WinningsBonus"] = 0;
                $array["FreeBalance"] = 0;
                $array["Bonus"] = $value->{".valor"};
                $array["Profit"] = -($value->{".valor"});
                $array["Profitness"] = -$array["Bonus"];
                $array["BonusCashBack"] = 0;
                $array["CurrencyId"] = $value->{"usuario.moneda"};
                if ($array["CurrencyId"] == "PEN") {
                    $array["CountryIcon"] = "pe";
                }

                array_push($final, $array);
            }
            EndBonusRequest:

            /** Consultando ganancias de jackpot */
            //Se hace con base en los filtros de Bonos Casino y removiendo el filtro específico de los bono_log de bonos
            $jackpotRules = array_filter($rules, function ($rule) {
                if ($rule["field"] != 'bono_log.tipo') return $rule;
            });
            $jackpotRules = array_values($jackpotRules);

            /*Variable corresponde a los tipos de logs por vertical para jackpots*/
            $bonoLogsForJackpot = [
                'CASINO' => "'JS'",
                'LIVECASINO' => "'JL'",
                'VIRTUAL' => "'JV'",
            ];
            $requiredLogs = "";
            if (!empty($verticalsId)) {
                /* Aplicación de filtrado por tipo de vertical (Similiar a TypeReport) Acepta múltiples verticales de forma concurrente */
                /*Separa los IDs de verticales seleccionados*/
                $verticalsChoicedOptions = explode(',', $verticalsId);

                $filterablesBySubprovidersMap = [
                    1 => 'CASINO',
                    2 => 'LIVECASINO',
                    3 => 'VIRTUAL',
                    4 => 'LOTTERY'
                ];

                foreach ($verticalsChoicedOptions as $option) {
                    /*Verifica que la opción corresponda a una vertical válida*/
                    if (!isset($filterablesBySubprovidersMap[$option])) continue;
                    $currentVertical = $filterablesBySubprovidersMap[$option];

                    /*Verifica que la vertical tenga logs correspondientes y genera la cadena de consulta*/
                    if (!isset($bonoLogsForJackpot[$currentVertical])) continue;
                    $requiredLogs .= (!empty($requiredLogs) ? "," : "") . $bonoLogsForJackpot[$currentVertical];
                }
            }
            else {
                $requiredLogs = implode(",", $bonoLogsForJackpot);
            }

            /*En caso de no existir tipos relacionados a la vertical requerida en jackpots, se salta al final sin hacer la consulta*/
            if (empty($requiredLogs)) goto EndJackpotRequest;
            $jackpotRules[] = ['field' => 'bono_log.tipo', 'data' => "$requiredLogs", 'op' => 'in']; //JS (Jackpot slots), JL (Jackpot LiveCasino), JV (Jackpot Virtuales)

            // Prepara y ejecuta consulta de jackpot
            $filtro = array("rules" => $jackpotRules, "groupOp" => "AND");
            $json = json_encode($filtro);
            $select = " SUM(bono_log.valor) valor,usuario.moneda ";
            $data = $BonoLog->getBonoLogsCustom($select, "bono_log.bonolog_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");
            $data = json_decode($data);

            // Procesa resultados de jackpot
            foreach ($data->data as $value) {
                $array = [];

                // Configura valores para registro de jackpot
                $array["Game"] = 'Premio Jackpot';
                $array["ProviderName"] = 'Premio Jackpot';
                $array["SubProviderName"] = "";
                $array["Bets"] = 0;
                $array["Stakes"] = 0;
                $array["Winnings"] = 0;
                $array["StakesBonus"] = 0;
                $array["WinningsBonus"] = 0;
                $array["FreeBalance"] = 0;
                $array["Bonus"] = 0;
                $array["TotalJackpotPrizeSum"] = (string) round($value->{".valor"}, 2, PHP_ROUND_HALF_DOWN);
                $array["Profit"] = 0;
                $array["Profitness"] = 0;
                $array["BonusCashBack"] = 0;
                $array["CurrencyId"] = $value->{"usuario.moneda"};
                if ($array["CurrencyId"] == "PEN") {
                    $array["CountryIcon"] = "pe";
                }

                array_push($final, $array);
            }
            EndJackpotRequest:
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


    // Configura la respuesta base sin errores
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
