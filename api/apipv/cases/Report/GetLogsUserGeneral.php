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
use Backend\dto\Consecutivo;use Backend\dto\ConfigurationEnvironment;
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
use Backend\dto\GeneralLog;
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
use Backend\mysql\GeneralLogMySqlDAO;
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
 * Report/GetLogsUserGeneral
 * 
 * Obtiene el historial de movimientos y logs generales de los usuarios según los filtros especificados
 *
 * @param array $params {
 *   "PlayerId": int,            // ID del jugador
 *   "UserId": int,              // ID del usuario
 *   "CountrySelect": int,       // ID del país seleccionado
 *   "ProviderId": int,          // ID del proveedor
 *   "ProductId": int,           // ID del producto
 *   "IsDetails": boolean,       // Indica si se requieren detalles
 *   "Type": string,             // Tipo de registro
 *   "IP": string,               // Dirección IP
 *   "dateFrom": string,         // Fecha inicial en formato Y-m-d
 *   "dateTo": string,           // Fecha final en formato Y-m-d
 *   "count": int,               // Cantidad de registros a retornar
 *   "start": int                // Registro inicial (paginación)
 * }
 *
 * @return array {
 *   "HasError": boolean,         // Indica si hubo error
 *   "AlertType": string,         // Tipo de alerta (success, error)
 *   "AlertMessage": string,      // Mensaje de alerta
 *   "ModelErrors": array,        // Errores del modelo
 *   "data": array {
 *     "Id": int,                 // ID del registro
 *     "Date": string,            // Fecha del registro
 *     "Type": string,            // Tipo de movimiento
 *     "Amount": float,           // Monto del movimiento
 *     "Description": string,     // Descripción del movimiento
 *     "ExternalId": string,      // ID externo
 *     "CreatedLocalDate": string,// Fecha de creación local
 *     "BalanceDeposit": float,   // Balance de depósito
 *     "BalanceWithdrawal": float,// Balance de retiro
 *     "IP": string              // Dirección IP
 *   }[],
 *   "pos": int,                 // Posición actual
 *   "total_count": int          // Total de registros
 * }
 */

// Inicializa el objeto Usuario para manejar la información del usuario
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
$Type = $params->Type;
$Ip = $params->IP;

// Obtiene los parámetros de paginación y ordenamiento
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;
$ToDateLocal = $params->dateTo;
$FromDateLocal = $params->dateFrom;

// Procesa las fechas de inicio y fin del reporte, aplicando el timezone
if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}
if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));
}

// Obtiene los parámetros principales de filtrado desde la request
$PlayerId = $_REQUEST['PlayerId'];
$UserId = $_REQUEST['UserId'];
$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';
$ProductId = $_REQUEST["ProductId"];
$IsDetails = $_REQUEST["IsDetails"];
$Type = $_REQUEST["Type"];
$Ip = $_REQUEST["Ip"];

// Configura los parámetros de paginación
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

// Valida que los parámetros requeridos estén presentes
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

// Procesa la lógica principal si los parámetros son válidos
if ($seguir) {

    if ($IsDetails == 1) {
        $IsDetails = false;
    } else {
        $IsDetails = true;
    }

    // Inicializa el array de reglas para el filtrado
    $rules = [];

    // Agrega reglas de filtrado por fechas
    if ($FromDateLocal != "") {
        array_push($rules, array("field" => "general_log.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    }

    if ($ToDateLocal != "") {
        array_push($rules, array("field" => "general_log.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
    }

    // Agrega reglas de filtrado por IP y producto
    if($Ip != ""){
        array_push($rules,array("field"=>"general_log.usuario_ip","data"=>"$Ip","op"=>"eq"));
    }

    if ($ProductId != "") {
        if ($_SESSION['Global'] == "S") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$ProductId", "op" => "eq"));
        } else {
            array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$ProductId", "op" => "eq"));
        }
    }

    // Agrega reglas de filtrado por región y usuarios
    if ($Region != "") {
        array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$Region", "op" => "eq"));
    }

    if ($PlayerId != "") {
        array_push($rules, array("field" => "general_log.usuario_id", "data" => "$PlayerId", "op" => "eq"));
    }

    if ($UserId != "") {
        array_push($rules, array("field" => "general_log.usuario_id", "data" => "$UserId", "op" => "eq"));
    }

    // Agrega reglas de filtrado por tipo de registro
    if($Type == 0){
        array_push($rules,array("field"=>"general_log.tipo","data"=>'CHANGEFIELD',"op"=>"eq"));
    }

    switch ($Type) {
        case 0:
           array_push($rules,array("field"=>"general_log.tipo","data"=>"LOGININCORRECTO","op"=>"eq"));
            break;
        case 1:
            array_push($rules,array("field"=>"general_log.tipo","data"=>"ESTADOUSUARIO","op"=>"eq"));
        case 2:
            array_push($rules,array("field"=>"general_log.tipo","data"=>"CONTINGENCIAUSUARIO","op"=>"eq"));
        default:
            break;
    }

    // Agrega reglas de filtrado por país y mandante
    if ($CountrySelect != "" && $CountrySelect != "0") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
    }

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        //array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "general_log.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    }else {
        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "general_log.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }
    }

    // Prepara y ejecuta la consulta
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);
    $select = "general_log.* ";
    $GeneralLog = new GeneralLog();
    $data = $GeneralLog->getGeneralLogsCustom($select,"general_log.generallog_id","desc",$SkeepRows,$MaxRows,$json,true);
    $data = json_decode($data);

    // Procesa los resultados de la consulta
    $final = [];
    $papuestas = 0;
    $ppremios = 0;
    $pcont = 0;

    foreach ($data->data as $key => $value) {
        $array["Id"] = $value->{"general_log.generallog_id"};
        $array["UserId"] = $value->{"general_log.usuario_id"};
        $array["Type"] = $value->{"general_log.tipo"};

        switch ($value->{"general_log.tipo"}) {
            case "CHANGEFIELD":
                $array["Type"] = '0';
                break;
        }

        // Asigna valores adicionales al array de resultados
        $array["Date"] = $value->{"general_log.fecha_crea"};
        $array["UserModified"] = $value->{"general_log.usuariosolicita_id"};
        $array["Table"] = $value->{"general_log.fecha_crea"};
        $array["Field"] = $value->{"general_log.campo"};
        $array["Table"] = $value->{"general_log.tabla"};
        $array["ExternalId"] = $value->{"general_log.externo_id"};
        $array["Reason"] = $value->{"general_log.explicacion"};
        $array["ValuePrevius"] = ConvertirCampoAPalabra($value->{"general_log.valor_antes"},$array["Table"]);
        $array["Value"] = ConvertirCampoAPalabra($value->{"general_log.valor_despues"},$array["Table"]);

        switch ($array["Table"]){
            case "producto_mandante":
                $array["Table"]='Producto Mandante';
                break;
        }

        $array["IP"] = $value->{"general_log.usuariosolicita_ip"};
        $array["OS"] = $value->{"general_log.soperativo"};

        array_push($final, $array);
    }

    // Prepara la respuesta exitosa
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $data->count[0]->{".count"};
    $response["data"] = $final;

} else {
    // Prepara la respuesta para el caso de parámetros inválidos
    $response["HasError"] = false;
    $response["AlertType"] = "success2";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();
}

// Función auxiliar para convertir valores de campos específicos
function ConvertirCampoAPalabra($valor,$tabla){
    switch ($tabla){
        case "producto_mandante":
            if($valor == "A"){
                $valor='Activo';
            }
            if($valor == "I"){
                $valor='Inactivo';
            }
            break;
    }
    return $valor;
}