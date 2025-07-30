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
 * Report/GetClientBonusReport
 *
 * Obtiene el reporte de bonos de los clientes
 *
 * @param array $params {
 *   "FromDateLocal": string,     // Fecha inicial en formato Y-m-d
 *   "ToDateLocal": string,       // Fecha final en formato Y-m-d
 *   "ClientId": int,             // ID del cliente
 *   "IsDetails": boolean,        // Indica si se requiere el detalle
 *   "PartnerBonusId": int,      // ID del bono del partner
 *   "OrderedItem": int,          // Campo de ordenamiento
 *   "PlayerId": string,          // ID del jugador
 *   "ClientBonusId": int,        // ID del bono del cliente
 *   "BonusType": int,           // Tipo de bono (2,3,6)
 *   "CountrySelect": int,        // ID del país seleccionado
 *   "State": string,            // Estado del bono (A,I,E,R,L,P)
 *   "CampaingCategory": string, // Categoría de la campaña
 *   "CampaingDetails": string   // Detalles de la campaña
 * }
 *
 * @return array {
 *   "HasError": boolean,         // Indica si hubo error
 *   "AlertType": string,         // Tipo de alerta (success, error)
 *   "AlertMessage": string,      // Mensaje de alerta
 *   "ModelErrors": array,        // Errores del modelo
 *   "data": array {
 *     "BonusId": int,           // ID del bono
 *     "ClientId": int,          // ID del cliente
 *     "BonusType": int,         // Tipo de bono
 *     "Amount": float,          // Monto del bono
 *     "State": string,          // Estado del bono
 *     "RolloverRequired": float,// Rollover requerido
 *     "RolloverWagered": float, // Rollover apostado
 *     "RolloverRemaining": float,// Rollover restante
 *     "UsuidReferred": string   // ID de usuario referido
 *   },
 *   "pos": int,                 // Posición actual
 *   "total_count": int          // Total de registros
 * }
 */


// Obtiene los parámetros principales de la solicitud desde el objeto $params
$FromDateLocal = $params->FromDateLocal;
$ToDateLocal = $params->ToDateLocal;
$ClientId = $params->ClientId;
$IsDetails = $params->IsDetails;
$PartnerBonusId = $params->PartnerBonusId;
$CampaingDetails = $params->CampaingDetails;
$CampaingCategory = $params->CampaingCategory;

// Obtiene parámetros adicionales de la solicitud HTTP ($_REQUEST)
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$PlayerId = $_REQUEST["PlayerId"];
$PartnerBonusId = $_REQUEST["PartnerBonusId"];
$ClientBonusId = $_REQUEST["ClientBonusId"];

// Valida y asigna tipos de bonos y estados permitidos
$BonusType = ($_REQUEST["BonusType"] != 2 && $_REQUEST["BonusType"] != 3 && $_REQUEST["BonusType"] != 6) ? '' : $_REQUEST["BonusType"];
$CountrySelect = intval($_REQUEST["CountrySelect"]);
$State = ($_REQUEST["State"] != 'A' && $_REQUEST["State"] != 'I' && $_REQUEST["State"] != 'E' && $_REQUEST["State"] != 'R' && $_REQUEST["State"] != 'L' && $_REQUEST["State"] != 'P') ? '' : $_REQUEST["State"];
$CampaingCategory = $_REQUEST["CampaingCategory"];
$CampaingDetails =  $_REQUEST["CampaingDetails"];

// Realiza validaciones básicas para continuar con el proceso
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

// Procesa las fechas y ajusta la zona horaria si pasa las validaciones
if ($seguir) {
    $FromDateLocal = $params->dateFrom;
    if ($_REQUEST["dateFrom"] != "") {
        $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
    }

    $ToDateLocal = $params->dateTo;
    if ($_REQUEST["dateTo"] != "") {
        $ToDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +1 day' . $timezone . ' hour '));
    }

    // Reprocesa la fecha inicial para asegurar consistencia
    $FromDateLocal = $params->dateFrom;
    if ($_REQUEST["dateFrom"] != "") {
        $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
    }

    // Manejo de fechas vacías (comentado)
    if ($FromDateLocal == "") {
        //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
    }
    if ($ToDateLocal == "") {
        //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
    }

    // Inicializa array para reglas de filtrado
    $rules = [];

    // Ajusta zona horaria para mandantes específicos
    if(in_array($_SESSION['mandante'] ,array(3,4,5,6,7,10,22,25))){
        $timezone = '+ 6 ';
        $FromDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $FromDateLocal) . $timezone . ' hour '));
        $ToDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $ToDateLocal) . $timezone . ' hour '));
    }

    // Agrega reglas de filtrado para fechas según el estado
    if ($FromDateLocal != "") {
        if($State != 'R'){
            array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
        }else{
            array_push($rules, array("field" => "usuario_bono.fecha_modif", "data" => "$FromDateLocal ", "op" => "ge"));
        }
    }

    // Agrega reglas de filtrado para categorías y detalles de campaña
    if($CampaingCategory != ""){
        array_push($rules, array("field" => "bono_interno.categoria_campaña", "data" => $CampaingCategory, "op" => "eq"));
    }

    if($CampaingDetails != ""){
        array_push($rules, array("field" => "bono_interno.detalle_campaña", "data" => $CampaingDetails, "op" => "eq"));
    }

    // Agrega reglas de filtrado para fecha final
    if ($ToDateLocal != "") {
        if($State != 'R'){
            array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
        }else{
            array_push($rules, array("field" => "usuario_bono.fecha_modif", "data" => "$ToDateLocal", "op" => "le"));
        }
    }

    // Agrega reglas de filtrado adicionales según parámetros
    if ($PartnerBonusId != "") {
        array_push($rules, array("field" => "usuario_bono.bono_id", "data" => "$PartnerBonusId", "op" => "eq"));
    }

    if ($ClientId != "") {
        array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }

    if ($ClientBonusId != "") {
        array_push($rules, array("field" => "usuario_bono.usubono_id", "data" => "$ClientBonusId", "op" => "eq"));
    }

    // Agrega reglas de filtrado para jugador y tipo de bono
    if ($PlayerId != "") {
        array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => "$PlayerId", "op" => "eq"));
    }
    if ($BonusType != "") {
        array_push($rules, array("field" => "bono_interno.tipo", "data" => "$BonusType", "op" => "eq"));
    }

    // Agrega reglas de filtrado para país y estado
    if ($CountrySelect != "" && $CountrySelect != "0") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
    }

    if ($State != "") {
        array_push($rules, array("field" => "usuario_bono.estado", "data" => "$State", "op" => "eq"));
    }

    // Agrega reglas de filtrado según condiciones de usuario y mandante
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }

    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    }else {
        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }
    }

    // Prepara y ejecuta la consulta final
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);
    setlocale(LC_ALL, 'czech');
    $select = " usuario_bono.*,usuario.moneda,bono_interno.*,bono_log.valor ";

    // Crea una instancia de UsuarioBono y obtiene los datos de bonos según los filtros
    $UsuarioBono = new UsuarioBono();
    $data = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

    $data = json_decode($data);

    // Inicializa arreglos para almacenar resultados y contador de monto total
    $final = array();
    $totalAmount = 0;

    // Itera sobre cada registro de bono obtenido para procesarlo
    foreach ($data->data as $value) {
        $array = array();

        // Asigna valores básicos del bono al arreglo de resultados
        $array["Id"] = $value->{"usuario_bono.usubono_id"};
        $array["AcceptanceDateLocal"] = $value->{"usuario_bono.fecha_crea"};
        $array["ClientId"] = $value->{"usuario_bono.usuario_id"};
        $array["PartnerBonusId"] = $value->{"usuario_bono.bono_id"};
        $array["PartnerBonusName"] = $value->{"bono_interno.descripcion"};
        $array["Name"] = $value->{"bono_interno.descripcion"};
        $array["CampaingDetails"] = $value->{"bono_interno.detalle_campaña"};
        $array["CampaingCategory"] = $value->{"bono_interno.categoria_campaña"};

        // Procesa y traduce las categorías de campaña a texto legible
        switch ($value->{"bono_interno.categoria_campaña"}){
            case 353:
                $array["CampaingCategory"] = "Reactivacion";
                break;
            case 351:
                $array["CampaingCategory"] = "Adquisicion";
                break;
            case 352:
                $array["CampaingCategory"] = "Retencion";
                break;
            case 354:
                $array["CampaingCategory"] = "Retencion de saldo";
                break;
        }

        // Procesa y traduce los detalles de campaña a texto legible
        switch ($value->{"bono_interno.detalle_campaña"}){
            case 355:
                $array["CampaingDetails"] = "Bono de bienvenida";
                break;
            case 356:
                $array["CampaingDetails"] = "Bono de registro";
                break;
            case 357:
                $array["CampaingDetails"] = "Bono extra por registro";
                break;
            case 358:
                $array["CampaingDetails"] = "Campaña local";
                break;
            case 359:
                $array["CampaingDetails"] = "Bono Torneos";
                break;
            case 360:
                $array["CampaingDetails"] = "Fidelización";
                break;
            case 361:
                $array["CampaingDetails"] = "Lealtad";
                break;
            case 362:
                $array["CampaingDetails"] = "CRM Fidelización";
                break;
            case 363:
                $array["CampaingDetails"] = "Bono Cumpleaños";
                break;
            case 364:
                $array["CampaingDetails"] = "Bono próximo depósito";
                break;
            case 365:
                $array["CampaingDetails"] = "Bono Sorteo";
                break;
            case 366:
                $array["CampaingDetails"] = "Bono Ruleta";
                break;
            case 367:
                $array["CampaingDetails"] = "CRM Activación";
                break;
            case 368:
                $array["CampaingDetails"] = "Activación";
                break;
        }

        // Determina y asigna el tipo de bono según su código
        if ($value->{"bono_interno.tipo"} == "2") {
            $array["BonusType"] = "Deposito";
        } else {
            if ($value->{"bono_interno.tipo"} == "3") {
                $array["BonusType"] = "No Deposito";
            } else if ($value->{"bono_interno.tipo"} == "6") {
                $array["BonusType"] = "FreeBet";
            }else if ($value->{"bono_interno.tipo"} == "5") {
                $array["BonusType"] = "FreeCash";
            } else {
                $array["BonusType"] = "";
            }
        }

        // Procesa el tipo de aceptación y resultado según el estado del bono
        if ($value->{"usuario_bono.estado"} == "A") {
            $array["AcceptanceType"] = "2";
            $array["ResultType"] = "0";
        } else {
            if ($value->{"usuario_bono.estado"} == "R") {
                $array["AcceptanceType"] = "2";
                $array["ResultType"] = "1";
            } else {
                if ($value->{"usuario_bono.estado"} == "E") {
                    $array["AcceptanceType"] = "4";
                    $array["ResultType"] = "4";
                } else {
                    $array["AcceptanceType"] = "0";
                    $array["ResultType"] = "0";
                }
            }
        }

        // Asigna valores monetarios y fechas del bono
        $array["ClientCurrency"] = $value->{"usuario.moneda"};
        $array["Amount"] = floatval($value->{"usuario_bono.valor"});
        $array["AmountConverted"] = floatval($value->{"bono_log.valor"});
        $array["BeginningDate"] = $value->{"bono_interno.fecha_inicio"};
        $array["CreatedLocal"] = $value->{"usuario_bono.fecha_modif"};
        $array["ClientBonusExpirationDateLocal"] = "";
        $array["RolloverRequired"] = $value->{"usuario_bono.rollower_requerido"};
        $array["RolloverWagered"] = $value->{"usuario_bono.apostado"};
        $array["RolloverRemaining"] = $array["RolloverRequired"] - $array["RolloverWagered"];
        $array["State"] = $value->{"usuario_bono.estado"};

        // Procesa casos especiales para bonos tipo 5 (FreeCash)
        if ($value->{"bono_interno.tipo"} == "5") {
            $array["Amount"] = floatval($value->{"usuario_bono.valor_base"});
            $array["RolloverRequired"] = floatval($value->{"usuario_bono.valor_base"});
            $array["RolloverWagered"] = floatval($value->{"usuario_bono.valor"});
            $array["RolloverRemaining"] = $array["RolloverRequired"] - $array["RolloverWagered"];
            $array["RolloverRequired"] = floatval('0');
        }

        // Traduce los estados del bono a texto legible
        switch ($value->{"usuario_bono.estado"}) {
            case "A":
                $array["State"] = "Activo";
                break;
            case "I":
                $array["State"] = "Inactivo";
                break;
            case "R":
                $array["State"] = "Redimido";
                break;
            case "L":
                $array["State"] = "Libre";
                break;
            case "E":
                $array["State"] = "Expirado";
                break;
            case "P":
                $array["State"] = "Pendiente";
                break;
        }

        // Procesa el ID de usuario referido y actualiza totales
        $array['UsuidReferred'] = !empty($value->{'usuario_bono.usuid_referido'}) ? $value->{'usuario_bono.usuid_referido'}: '0';
        $totalAmount = $totalAmount + $array["Amount"];
        array_push($final, $array);
    }

    // Prepara la respuesta exitosa con los datos procesados
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Operation has completed successfully";
    $response["ModelErrors"] = [];
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $data->count[0]->{".count"};
    $response["data"] = $final;
} else {
    // Prepara una respuesta vacía si no se cumplen las validaciones iniciales
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Operation has completed successfully";
    $response["ModelErrors"] = [];
    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();
}
