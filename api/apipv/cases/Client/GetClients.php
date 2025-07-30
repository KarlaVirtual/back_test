<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Helpers;
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
 * Client/GetClients
 *
 * Obtener los usuarios registrados en la plataforma
 *
 * @param int $params->Id Id del usuario
 * @param string $params->Login Login del usuario
 * @param string $params->IsActivate Estado del usuario
 * @param string $params->DocumentStatus Estado del documento
 * @param string $params->dateFrom Fecha de inicio
 * @param string $params->dateTo Fecha de fin
 * @param string $params->MinLastTimeLoginDateLocal Fecha de inicio de la última sesión
 * @param string $params->MaxLastTimeLoginDateLocal Fecha de fin de la última sesión
 * @param string $params->FirstName Nombre del usuario
 * @param string $params->LastName Apellido del usuario
 * @param string $params->MiddleName Segundo nombre del usuario
 * @param string $params->OriginRegistry Origen del registro
 * @param string $params->DocumentNumber Número de documento
 * @param string $params->Region Región
 * @param string $params->Ip Dirección IP
 * @param string $params->Site Sitio
 * @param string $params->verifyPreviousDNI Verificar DNI anterior
 * @param string $params->verifySubsequentDNI Verificar DNI posterior
 * @param string $params->MobilePhone Teléfono móvil
 * @param string $params->Site Sitio
 * @param string $params->StateContingencyWithdrawals Estado de contingencia de retiros
 * @param string $params->StateContingency Estado de contingencia
 * @param string $params->StateContingencyDeportivas Estado de contingencia deportivas
 * @param string $params->StateContingencyCasino Estado de contingencia casino
 * @param string $params->StateContingencyCasinoVivo Estado de contingencia casino en vivo
 * @param string $params->StateContingencyVirtuales Estado de contingencia virtuales
 * @param string $params->StateContingencyPoker Estado de contingencia poker
 * @param string $params->RiskStatus Estado de riesgo
 * @param string $params->IdCodePromotional Id del código promocional
 * @param string $params->RegionName Nombre de la región
 * @param string $params->dateFrom Fecha de inicio
 * @param string $params->dateTo Fecha de fin
 * @param int $params->MaxRows Máximo de filas
 * @param int $params->OrderedItem Ítem ordenado
 * @param int $params->SkeepRows Filas omitidas
 *
 * @return $response array
 *  -HasError bool Indica si ocurrió un error
 *  -Message string Mensaje de error
 *  -pos int Posición
 *  -Total int Total de registros
 *  -Data array Datos
 */

/* registra un mensaje de advertencia con datos de sesión del usuario. */
syslog(LOG_WARNING, " GETCLIENTS " . $_SESSION['usuario'] . " GETCLIENTS ");

$userNow = $_SESSION['usuario2'];
if ($_SESSION['usuario2'] == "") {
    //$userNow = 5;
}

/* crea instancias de Usuario y Mandante, y decodifica datos JSON. */
$Usuario = new Usuario();
$UsuarioMandante = new UsuarioMandante($userNow);
$Mandante = new Mandante($UsuarioMandante->getMandante());

$params = file_get_contents('php://input');
$params = json_decode($params);


/* Asignación de parámetros a variables en un código, probablemente en PHP. */
$Id = $params->Id;
$Login = $params->Login;
$IsActivate = $params->IsActivate;
$DocumentStatus = $params->DocumentStatus;
$dateFrom = $params->dateFrom;
$dateTo = $params->dateTo;

/* Se asignan valores de parámetros a variables para procesar información de usuarios. */
$MinLastTimeLoginDateLocal = $params->MinLastTimeLoginDateLocal;
$MaxLastTimeLoginDateLocal = $params->MaxLastTimeLoginDateLocal;
$FirstName = $params->FirstName;
$LastName = $params->LastName;
$MiddleName = $params->MiddleName;
$OriginRegistry = $params->OriginRegistry;

/* Asignación de parámetros de entrada y validación de un número de identificación. */
$DocumentNumber = $params->DocumentNumber;
$Region = $params->Region;
$ip = $params->Ip;
$Site = $params->Site;


$Id = (is_numeric($_REQUEST["Id"])) ? $_REQUEST["Id"] : '';

/* Se valida la entrada de datos de registro y activación antes de asignar valores. */
$Login = $_REQUEST["Login"];
$IsActivate = ($_REQUEST["IsActivate"] != "A" && $_REQUEST["IsActivate"] != "I" && $_REQUEST["IsActivate"] != "R") ? '' : $_REQUEST["IsActivate"];
$IsRegisterActivate = ($_REQUEST["IsRegisterActivate"] != "A" && $_REQUEST["IsRegisterActivate"] != "I" && $_REQUEST["IsRegisterActivate"] != "R") ? '' : $_REQUEST["IsRegisterActivate"];
$RegisterMedia = ($_REQUEST["RegisterMedia"] != "1" && $_REQUEST["RegisterMedia"] != "2") ? '' : $_REQUEST["RegisterMedia"];

$DocumentNumber = $_REQUEST["DocNumber"];

/* recopila datos de un formulario utilizando el método $_REQUEST en PHP. */
$FirstName = $_REQUEST["FirstName"];
$LastName = $_REQUEST["LastName"];
$MiddleName = $_REQUEST["MiddleName"];
$CountrySelect = $_REQUEST["CountrySelect"];
$Ip = $_REQUEST["Ip"];
$StateConditionPep = $_REQUEST["StateConditionPep"];

/* recoge fechas y ejecuta un script PHP con parámetros específicos. */
$AcceptanceDateTo = $_REQUEST["AcceptanceDateTo"];
$AcceptanceDateFrom = $_REQUEST["AcceptanceDateFrom"];

exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'CONSULTA GetClientSpById " . ' ID ' . $Id . "  " . $_SESSION['usuario'] . "  " . $_SESSION["win_perfil"] . "  " . $_SESSION["nombre"] . "' '#virtualsoft-cron2' > /dev/null & ");

$verifyPreviousDNI = (!empty($_REQUEST["verifyPreviousDNI"]) && in_array($_REQUEST["verifyPreviousDNI"], ['N', 'S'])) ? $_REQUEST["verifyPreviousDNI"] : '';

/* valida y asigna valores de entrada a variables específicas. */
$verifySubsequentDNI = (!empty($_REQUEST["verifySubsequentDNI"]) && in_array($_REQUEST["verifySubsequentDNI"], ['N', 'S'])) ? $_REQUEST["verifySubsequentDNI"] : '';
$MobilePhone = $_REQUEST["MobilePhone"];

$Site = $_REQUEST["Site"];

$StateContingencyWithdrawals = $_REQUEST["StateContingencyWithdrawals"];

/* captura datos de una solicitud para diferentes estados de contingencia. */
$StateContingency = $_REQUEST["StateContingency"];
$StateContingencyDeportivas = $_REQUEST["StateContingencyDeportivas"];
$StateContingencyCasino = $_REQUEST["StateContingencyCasino"];
$StateContingencyCasinoVivo = $_REQUEST["StateContingencyCasinoVivo"];
$StateContingencyVirtuales = $_REQUEST["StateContingencyVirtuales"];
$StateContingencyPoker = $_REQUEST["StateContingencyPoker"];

/* Transforma el valor de "RiskStatus" según su letra correspondiente. */
$RiskStatus = $_REQUEST["RiskStatus"];


switch ($RiskStatus) {
    case "B":
        $RiskStatus = 1;
        break;
    case "M":
        $RiskStatus = 2;
        break;
    case "A":
        $RiskStatus = 3;
        break;
    default:
        $RiskStatus = "";
}


/* obtiene valores de entrada de un formulario HTML mediante la variable $_REQUEST. */
$IdCodePromotional = $_REQUEST["IdCodePromotional"];
$RegionName = $_REQUEST["RegionName"];


$dateFrom = $_REQUEST["dateFrom"];
$dateTo = $_REQUEST["dateTo"];


/* verifica si alguna variable está llena y luego inicializa fechas vacías. */
if ($Id != '' || $Login != '' || $DocumentNumber != '' || $FirstName != '' || $LastName != '' || $MobilePhone != '' || $RiskStatus != '') {
    $dateFrom = '';
    $dateTo = '';
}

$seguir = true;

/* verifica si las fechas de entrada están vacías, estableciendo una condición. */
if ($_REQUEST["dateFrom"] == "" || $_REQUEST["dateTo"] == "") {
    $seguir = false;
}

if ($seguir) {


    /* ajusta las fechas para incluir rangos completos de tiempo. */
    if ($dateFrom == $dateTo && $dateFrom != "") {
        $dateFrom = $dateFrom . " 00:00:00";
        $dateTo = $dateTo . " 23:59:59";
    } else {
        if ($dateFrom != "") {
            $dateFrom = $dateFrom . " 00:00:00";
        }

        if ($dateTo != "") {
            $dateTo = $dateTo . " 23:59:59";

        }

    }


    /* Establece límites de tiempo para fechas de inicio y fin en la validación. */
    if ($MinLastTimeLoginDateLocal == $MaxLastTimeLoginDateLocal && $MinLastTimeLoginDateLocal != "") {
        $MinLastTimeLoginDateLocal = $MinLastTimeLoginDateLocal . " 00:00:00";
        $MaxLastTimeLoginDateLocal = $MaxLastTimeLoginDateLocal . " 23:59:59";
    } else {
        if ($MinLastTimeLoginDateLocal != "") {
            $MinLastTimeLoginDateLocal = $MinLastTimeLoginDateLocal . " 00:00:00";

        }
        if ($MaxLastTimeLoginDateLocal != "") {

            $MaxLastTimeLoginDateLocal = $MaxLastTimeLoginDateLocal . " 00:00:00";
        }
    }


    /* verifica si solo se incluyen ciertas claves en la solicitud. */
    $seguirFechas = true;

    foreach ($_REQUEST as $key => $value) {
        if ($key != 'start' && $key != 'count' && $key != 'dateFrom' && $key != 'dateTo' && $key != 'Partner') {
            $seguirFechas = false;
        }
    }


    /* procesa fechas desde y hasta, ajustándolas según la zona horaria. */
    if ($seguirFechas) {


        if ($_REQUEST["dateFrom"] != "") {
            $dateFrom = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
        }


        $FromDateLocal = $params->FromCreatedDateLocal;


        if ($_REQUEST["dateTo"] != "") {
            $dateTo = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
        }
    }


    /* asigna variables a parámetros y valores de solicitudes HTTP. */
    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    $MaxRows = $_REQUEST["count"];
    $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


    /* asigna valores predeterminados a variables si están vacías. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Asigna 1 a $MaxRows si no tiene valor asignado. */
    if ($MaxRows == "") {
        $MaxRows = 1;
    }

    if ($Mandante->propio == "S") {


        /* Se crean reglas de validación para verificar el perfil y usuario. */
        $rules = [];
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));

        if ($Id != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Id", "op" => "in"));
        }


        /* añade reglas de validación si $FirstName o $Ip no están vacíos. */
        if ($FirstName != "") {
            array_push($rules, array("field" => "registro.nombre1", "data" => "$FirstName", "op" => "cn"));

        }

        if ($Ip != "") {
            array_push($rules, array("field" => "usuario.dir_ip", "data" => "$Ip", "op" => "cn"));
        }

        /* Condición que agrega reglas a un array dependiendo de variables específicas. */
        if ($StateConditionPep != "" && ($StateConditionPep == "S" || $StateConditionPep == "N")) {
            array_push($rules, array("field" => "c.persona_expuesta", "data" => $StateConditionPep, "op" => "eq"));
            if ($AcceptanceDateFrom != "") {
                $AcceptanceDateFrom = $AcceptanceDateFrom . ' 00:00:00';
                array_push($rules, array("field" => "c.fecha_aceptacion", "data" => $AcceptanceDateFrom, "op" => "ge"));
            }

            if ($AcceptanceDateTo != "") {
                $AcceptanceDateTo = $AcceptanceDateTo . ' 23:59:59';
                array_push($rules, array("field" => "c.fecha_aceptacion", "data" => $AcceptanceDateTo, "op" => "le"));
            }

        }


        /* Condicionales que agregan reglas basadas en el estado de riesgo y nombre intermedio. */
        if ($RiskStatus != "" and $RiskStatus != null) {
            array_push($rules, array("field" => "usuario.clave_tv", "data" => $RiskStatus, "op" => "eq"));
        }


        if ($MiddleName != "") {
            array_push($rules, array("field" => "registro.nombre2", "data" => "$MiddleName", "op" => "cn"));

        }


        /* Agrega reglas de filtrado basadas en el nombre de la región y apellido. */
        if ($RegionName != "") {
            array_push($rules, array("field" => "usuario_perfil.region", "data" => "$RegionName", "op" => "eq"));

        }
        if ($LastName != "") {
            array_push($rules, array("field" => "registro.apellido1", "data" => "$LastName", "op" => "cn"));

        }


        /* agrega reglas de validación si los campos no están vacíos. */
        if ($Login != "") {
            array_push($rules, array("field" => "usuario.login", "data" => "$Login", "op" => "cn"));

        }

        if ($MobilePhone != "") {
            array_push($rules, array("field" => "registro.celular", "data" => "$MobilePhone", "op" => "cn"));

        }


        /* Agrega reglas para filtrar fechas en función de las variables $dateFrom y $dateTo. */
        if ($dateFrom != "") {
            array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateFrom", "op" => "ge"));

        }
        if ($dateTo != "") {
            array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateTo", "op" => "le"));

        }


        /* Se agregan reglas de comparación para fechas de inicio de sesión en un array. */
        if ($MinLastTimeLoginDateLocal != "") {
            array_push($rules, array("field" => "data_completa2.ultimo_inicio_sesion", "data" => "$MinLastTimeLoginDateLocal", "op" => "ge"));

        }
        if ($MaxLastTimeLoginDateLocal != "") {
            array_push($rules, array("field" => "data_completa2.ultimo_inicio_sesion", "data" => "$MaxLastTimeLoginDateLocal", "op" => "le"));

        }


        /* agrega reglas a un arreglo basado en condiciones de documento y estado. */
        if ($DocumentNumber != "") {
            array_push($rules, array("field" => "registro.cedula", "data" => "$DocumentNumber", "op" => "eq"));

        }

        if ($IsActivate != "" && ($IsActivate == "A" || $IsActivate == "I" || $IsActivate == "R")) {
            array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
        }


        /* verifica el estado de registro y documento, añadiendo reglas si son válidos. */
        if ($IsRegisterActivate != "" && ($IsRegisterActivate == "A" || $IsRegisterActivate == "I" || $IsRegisterActivate == "R")) {
            array_push($rules, array("field" => "registro.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
        }

        if ($DocumentStatus != "" && ($DocumentStatus == "A" || $DocumentStatus == "I" || $DocumentStatus == "R")) {
            array_push($rules, array("field" => "usuario.documento_validado", "data" => "$DocumentStatus", "op" => "eq"));
        }


        /* Agrega reglas de filtrado según el origen y país seleccionados. */
        if ($OriginRegistry != "" && $OriginRegistry != "null") {
            array_push($rules, array("field" => "usuario.origen", "data" => "$OriginRegistry", "op" => "cn"));

        }

        if ($CountrySelect != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));

        }


        /* añade reglas a un arreglo basado en condiciones específicas. */
        if ($RegisterMedia != "") {
            array_push($rules, array("field" => "registro.codpromocional_id", "data" => "0", "op" => "ne"));

        }

        if ($Site != "" && $Site != "0" && in_array($Site, array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10)) && is_numeric($Site)) {
            if ($Site == '1') {
                $Site = '0';
            }
            array_push($rules, array("field" => "usuario.origen", "data" => "$Site", "op" => "eq"));

        }


        /* Se añaden reglas para verificar DNI anterior y posterior si no están vacíos. */
        if ($verifyPreviousDNI != "") {
            array_push($rules, array("field" => "usuario.verifcedula_ant", "data" => "$verifyPreviousDNI", "op" => "eq"));

        }


        if ($verifySubsequentDNI != "") {
            array_push($rules, array("field" => "usuario.verifcedula_post", "data" => "$verifySubsequentDNI", "op" => "eq"));

        }


        /* Validación de variable $Site antes de agregar reglas en un array. */
        if ($Site != "" && $Site != "0" && in_array($Site, array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10)) && is_numeric($Site)) {
            if ($Site == '1') {
                $Site = '0';
            }
            array_push($rules, array("field" => "usuario.origen", "data" => "$Site", "op" => "eq"));

        }


        /* Añade reglas a un array basado en condiciones de código promocional y estado de contingencia. */
        if ($IdCodePromotional != "" && is_numeric($IdCodePromotional)) {
            array_push($rules, array("field" => "registro.codpromocional_id", "data" => $IdCodePromotional, "op" => "eq"));

        }

        if ($StateContingency != "" && $StateContingency != '0') {
            $StateContingency = ($StateContingency == "S") ? "A" : "I";
            array_push($rules, array("field" => "usuario.contingencia", "data" => "$StateContingency", "op" => "eq"));

        }


        /* valida y asigna valores a contingencias antes de agregarlas a un arreglo. */
        if ($StateContingencyWithdrawals != "" && $StateContingencyWithdrawals != '0') {
            $StateContingencyWithdrawals = $StateContingencyWithdrawals == "S" ? "A" : "I";
            array_push($rules, array("field" => "usuario.contingencia_retiro", "data" => $StateContingencyWithdrawals, "op" => "eq"));
        }

        if ($StateContingencyDeportivas != "" && $StateContingencyDeportivas != '0') {
            $StateContingencyDeportivas = ($StateContingencyDeportivas == "S") ? "A" : "I";
            array_push($rules, array("field" => "usuario.contingencia_deportes", "data" => "$StateContingencyDeportivas", "op" => "eq"));

        }


        /* Modifica valores de contingencia y los agrega a un array de reglas. */
        if ($StateContingencyCasino != "" && $StateContingencyCasino != '0') {
            $StateContingencyCasino = ($StateContingencyCasino == "S") ? "A" : "I";
            array_push($rules, array("field" => "usuario.contingencia_casino", "data" => "$StateContingencyCasino", "op" => "eq"));

        }

        if ($StateContingencyCasinoVivo != "" && $StateContingencyCasinoVivo != '0') {
            $StateContingencyCasinoVivo = ($StateContingencyCasinoVivo == "S") ? "A" : "I";
            array_push($rules, array("field" => "usuario.contingencia_casvivo", "data" => "$StateContingencyCasinoVivo", "op" => "eq"));

        }


        /* valida y asigna estados para contingencias virtuales y poker. */
        if ($StateContingencyVirtuales != "" && $StateContingencyVirtuales != '0') {
            $StateContingencyVirtuales = ($StateContingencyVirtuales == "S") ? "A" : "I";
            array_push($rules, array("field" => "usuario.contingencia_virtuales", "data" => "$StateContingencyVirtuales", "op" => "eq"));

        }

        if ($StateContingencyPoker != "" && $StateContingencyPoker != '0') {
            $StateContingencyPoker = ($StateContingencyPoker == "S") ? "A" : "I";
            array_push($rules, array("field" => "usuario.contingencia_poker", "data" => "$StateContingencyPoker", "op" => "eq"));

        }

        /* Añade reglas para filtrar usuarios según su estado y perfil en la sesión. */
        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


        if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

        }

        // Si el usuario esta condicionado por País

        /* Agrega reglas basadas en condiciones de sesión del usuario en un array. */
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {
            /* Añade reglas de validación si "mandanteLista" no está vacía ni es "-1". */


            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }

        // Inactivamos reportes para el país Colombia
        //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        /* Genera un filtro JSON y obtiene usuarios personalizados desde la base de datos. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $usuarios = $Usuario->getUsuariosCustom("  usuario.mandante, (usuario.usuario_id),usuario.origen,usuario.verifcedula_ant,usuario.clave_tv,usuario.verifcedula_post,usuario.documento_validado,data_completa2.ultimo_inicio_sesion,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.ciudad_id,registro.estado_valida,registro.codpromocional_id,registro.nombre1,registro.nombre2,registro.apellido1,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,c.*,g.*,usuario.tiempo_limitedeposito,usuario.tiempo_autoexclusion,usuario.cambios_aprobacion,registro.creditos,registro.creditos_base,registro.origen_fondos,usuario.verif_celular ", "usuario.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);

        $usuarios = json_decode($usuarios);


        /* Se inicializa un array vacío llamado $usuariosFinal para almacenar usuarios. */
        $usuariosFinal = [];

        foreach ($usuarios->data as $key => $value) {


            /* verifica el estado de un usuario y establece si está bloqueado. */
            $Islocked = false;

            if ($value->{"usuario.estado"} == "I") {
                $Islocked = true;
            }

            $array = [];


            /* asigna datos de un objeto a un array asociativo en PHP. */
            $array["id"] = $value->{"usuario.usuario_id"};
            $array["Id"] = $value->{"usuario.usuario_id"};
            $array["Ip"] = $value->{"usuario.dir_ip"};
            $array["Login"] = $value->{"usuario.login"};
            $array["Estado"] = array($value->{"usuario.estado"});
            $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};

            /* asigna valores de un objeto a un array asociativo en PHP. */
            $array["Idioma"] = $value->{"a.idioma"};
            $array["Nombre"] = $value->{"a.nombre"};
            $array["FirstName"] = $value->{"registro.nombre1"};
            $array["MiddleName"] = $value->{"registro.nombre2"};
            $array["LastName"] = $value->{"registro.apellido1"};
            $array["Email"] = $value->{"registro.email"};

            /* Asignación de valores a un array desde un objeto, para diversos atributos de usuario. */
            $array["Address"] = $value->{"registro.direccion"};
            $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
            $array["Intentos"] = $value->{"usuario.intentos"};
            $array["Observaciones"] = $value->{"usuario.observ"};
            $array["Moneda"] = $value->{"usuario.moneda"};
            $array["Site"] = $value->{"usuario.mandante"};

            /* asigna un estado de riesgo basado en la clave de usuario. */
            if (!isset($value->{"usuario.clave_tv"}) || $value->{"usuario.clave_tv"} === null) {
                $array["RiskStatus"] = "";
            } else {

                /* Asigna un estado de riesgo basado en el valor de "usuario.clave_tv". */
                switch ($value->{"usuario.clave_tv"}) {
                    case 1:
                        $array["RiskStatus"] = "B";
                        break;
                    case 2:
                        $array["RiskStatus"] = "M";
                        break;
                    case 3:
                        $array["RiskStatus"] = "A";
                        break;
                    default:
                        $array["RiskStatus"] = "";
                        break;
                }
            }


            /* Condicional que asigna un nombre de sitio según el usuario y su origen. */
            if ($value->{"usuario.mandante"} == '2') {
                $array["Site"] = 'Justbet';
                if ($value->{"usuario.origen"} == '2') {
                    $array["Site"] = 'Acropolis';
                }
            }


            /* asigna valores a un arreglo desde un objeto según diferentes atributos. */
            $array["Pais"] = $value->{"usuario.pais_id"};
            $array["City"] = $value->{"g.ciudad_nom"};

            $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};

            $array["IsLocked"] = $Islocked;

            /* Asigna datos de nacimiento a un array usando propiedades de un objeto. */
            $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
            $array["BirthDate"] = $value->{"c.fecha_nacim"};

            $array["BirthDepartment"] = $value->{"g.depto_id"};
            $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};
            $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};

            /* Asigna valores a un array desde un objeto, extrayendo diferentes datos. */
            $array["Balance"] = $value->{"registro.creditos"} + $value->{"registro.creditos_base"};

            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["DocNumber"] = $value->{"registro.cedula"};
            $array["Gender"] = $value->{"registro.sexo"};
            $array["Language"] = $value->{"usuario.idioma"};

            /* Asigna valores a un array utilizando propiedades de un objeto $value. */
            $array["Phone"] = $value->{"registro.telefono"};
            $array["MobilePhone"] = $value->{"registro.celular"};
            $array["LastLoginLocalDate"] = $value->{"data_completa2.ultimo_inicio_sesion"};
            $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};
            $array["Province"] = $value->{"registro.ciudad_id"};
            $array["RegionId"] = $value->{"usuario.pais_id"};

            /* asigna propiedades a un array basado en los datos de un objeto. */
            $array["CountryName"] = $value->{"usuario.pais_id"};
            $array["ZipCode"] = $value->{"registro.codigo_postal"};
            $array["IsVerified"] = true;
            $array["IsActivate"] = ($value->{"usuario.estado"});
            $array["IsPhoneVerification"] = ($value->{"usuario.verif_celular"} == 'S') ? 'A' : 'I';
            $array["IsRegisterActivate"] = ($value->{"registro.estado_valida"});

            /* Asigna valores de un objeto a un array para procesamiento posterior. */
            $array["IsDocumentActivate"] = ($value->{"usuario.documento_validado"});

            $array["DaysChangeLimitDeposit"] = $value->{"usuario.tiempo_limitedeposito"};
            $array["DaysChangeLimitSelfExclusion"] = $value->{"usuario.tiempo_autoexclusion"};
            $array["ChangesToApproval"] = ($value->{"usuario.cambios_aprobacion"} == "S" ? true : false);


            $array["VerifdnAnt"] = ($value->{"usuario.verifcedula_ant"});

            /* Asigna valores a un array basado en propiedades de un objeto. */
            $array["VerifdniPost"] = ($value->{"usuario.verifcedula_post"});
            $array["DNI"] = ($value->{"usuario.verifcedula_post"} == 'S' && $value->{"usuario.verifcedula_post"} == 'S') ? 'S' : 'N';
            $array["CodePromotional"] = $value->{"registro.codpromocional_id"};
            $array["IdCodePromotional"] = $value->{"registro.codpromocional_id"};

            $array["PostalCode"] = $value->{"registro.codigo_postal"};

            /* asigna valores a un array y verifica configuraciones de usuario relacionadas con "PEP". */
            $array["RFC"] = $value->{"registro.origen_fondos"};

            $array["AcceptanceDate"] = "";
            $array["IsPep"] = "N";

            try {
                $Clasificador = new Clasificador("", "PEP");
                $UsuarioConfiguracion = new UsuarioConfiguracion($value->{"usuario.usuario_id"}, "A", $Clasificador->getClasificadorId());
                if ($UsuarioConfiguracion->getValor() == 'S') {
                    $array["IsPep"] = 'S';
                    $array["AcceptanceDate"] = $UsuarioConfiguracion->fechaModif;
                }
            } catch (Exception $e) {
                /* Manejo de excepciones en PHP, ignorando errores sin realizar acciones adicionales. */
            }


            /* Agrega el contenido de $array al final del array $usuariosFinal. */
            array_push($usuariosFinal, $array);

        }
    } else {


        /* Se crea un objeto y se definen reglas para validar un usuario específico. */
        $UsuarioMandante = new UsuarioMandante();
        $rules = [];

        if ($Id != "") {
            array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => "$Id", "op" => "in"));
        }
        // Si el usuario esta condicionado por País

        /* Añade reglas condicionales basadas en la sesión del usuario en un array. */
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {
            /* Condicional que verifica y agrega reglas basadas en la sesión "mandanteLista". */


            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }


        /* Agrega reglas de filtrado y obtiene usuarios mandantes usando un formato JSON. */
        array_push($rules, array("field" => "usuario_mandante.propio", "data" => "N", "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $usuarios = $UsuarioMandante->getUsuariosMandantesCustom(" usuario_mandante.* ", "usuario_mandante.usumandante_id", "asc", $SkeepRows, $MaxRows, $json, true);


        /* Se decodifica un JSON de usuarios y se inicializa un array vacío. */
        $usuarios = json_decode($usuarios);

        $usuariosFinal = [];

        foreach ($usuarios->data as $key => $value) {


            /* Código inicializa un arreglo con ID de un usuario mandante. */
            $Islocked = false;

            $array = [];

            $array["id"] = $value->{"usuario_mandante.usuario_mandante"};
            $array["Id"] = $value->{"usuario_mandante.usuario_mandante"};

            /* Se asignan valores de un objeto a un array asociativo en PHP. */
            $array["Ip"] = $value->{"ausuario.dir_ip"};
            //$array["Login"] = $value->{"usuario.login"};
            // $array["Estado"] = array($value->{"usuario.estado"});
            // $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
            //  $array["Idioma"] = $value->{"a.idioma"};
            $array["Name"] = $value->{"usuario_mandante.nombres"};
            // $array["FirstName"] = $value->{"usuario_mandante.nombre"};
            // $array["MiddleName"] = $value->{"registro.nombre2"};

            /* Extrae datos del objeto y los agrega a un array de usuarios final. */
            $array["LastName"] = $value->{"usuario_mandante.apellidos"};
            $array["Email"] = $value->{"usuario_mandante.email"};
            $array["Currency"] = $value->{"usuario_mandante.moneda"};
            $array["CreatedLocalDate"] = $value->{"usuario_mandante.fecha_crea"};

            array_push($usuariosFinal, $array);

        }

    }
}


/* Configura una respuesta JSON con éxito, sin errores y datos de usuarios. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "Objects" => $usuariosFinal,
    "Count" => $usuarios->count[0]->{".count"},

);


/* asigna variables a una respuesta estructurada con datos de usuarios. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $usuarios->count[0]->{".count"};
$response["data"] = $usuariosFinal;
