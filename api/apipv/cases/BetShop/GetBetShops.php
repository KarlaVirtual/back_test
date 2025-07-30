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
use Backend\Backend\dto\Helpers;


/**
 * Obtiene una lista de puntos de venta basándose en los filtros y parámetros de paginación proporcionados.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param int $params ->count Número máximo de registros a devolver. Por defecto, 100.
 * @param int $params ->start Número de registros a omitir para la paginación. Por defecto, 0.
 * @param string $params ->Name Nombre del punto de venta para filtrar.
 * @param string $params ->Login Login del usuario asociado al punto de venta.
 * @param int $params ->CountrySelect Identificador del país asociado.
 * @param int $params ->UserId Identificador del usuario asociado al punto de venta.
 * @param string $params ->ContactName Nombre del contacto del punto de venta.
 * @param string $params ->Email Correo electrónico del punto de venta.
 * @param string $params ->CityName Nombre de la ciudad asociada al punto de venta.
 * @param string $params ->District Distrito asociado al punto de venta.
 * @param string $params ->Region Región asociada al punto de venta.
 * @param string $params ->RegionName Nombre de la región asociada al punto de venta.
 * @param string $params ->dateFrom Fecha de inicio para filtrar los puntos de venta (formato 'Y-m-d').
 * @param string $params ->dateTo Fecha de fin para filtrar los puntos de venta (formato 'Y-m-d').
 * @param string $params ->Address Dirección del punto de venta.
 * @param string $params ->Ip Dirección IP asociada al punto de venta.
 * @param string $params ->ManagerPhone Teléfono del gerente del punto de venta.
 * @param string $params ->Document Documento asociado al punto de venta.
 * @param string $params ->Zone Zona asociada al punto de venta.
 * @param string $params ->State Estado del punto de venta ('A' para activo, 'I' para inactivo).
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *
 *
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - pos (int): Posición inicial de los datos devueltos.
 *                         - total_count (int): Número total de registros encontrados.
 *                         - data (array): Datos de los puntos de venta, incluyendo:
 *                             - Id (int): Identificador del punto de venta.
 *                             - Name (string): Nombre del punto de venta.
 *                             - Login (string): Login del usuario asociado.
 *                             - Phone (string): Teléfono del punto de venta.
 *                             - Email (string): Correo electrónico del punto de venta.
 *                             - CityName (string): Nombre de la ciudad asociada.
 *                             - DepartmentName (string): Nombre del departamento asociado.
 *                             - RegionName (string): Nombre de la región asociada.
 *                             - Country (string): País asociado al punto de venta.
 *                             - CurrencyId (string): Moneda asociada al punto de venta.
 *                             - Address (string): Dirección del punto de venta.
 *                             - CreatedDate (string): Fecha de creación del punto de venta.
 *                             - LastLoginDateLabel (string): Fecha del último inicio de sesión.
 *                             - Type (string): Tipo del punto de venta.
 *                             - MinBet (float): Apuesta mínima permitida.
 *                             - PreMatchPercentage (float): Porcentaje de comisión para apuestas pre-match.
 *                             - LivePercentage (float): Porcentaje de comisión para apuestas en vivo.
 *                             - RecargasPercentage (float): Porcentaje de comisión para recargas.
 *                             - Amount (float): Cupo de recarga.
 *                             - AmountBetting (float): Créditos base para apuestas.
 *                             - Zone (string): Zona asociada al punto de venta.
 *                             - Document (string): Documento asociado al punto de venta.
 *                             - IPIdentification (string): Identificación IP ('S' para sí, 'N' para no).
 *                             - Facebook (string): Facebook asociado al punto de venta.
 *                             - FacebookVerification (string): Verificación de Facebook ('S' para sí, 'N' para no).
 *                             - Instagram (string): Instagram asociado al punto de venta.
 *                             - InstagramVerification (string): Verificación de Instagram ('S' para sí, 'N' para no).
 *                             - WhatsApp (string): WhatsApp asociado al punto de venta.
 *                             - WhatsAppVerification (string): Verificación de WhatsApp ('S' para sí, 'N' para no).
 *                             - OtherSocialMedia (string): Otras redes sociales asociadas.
 *                             - OtherSocialMediaVerification (string): Verificación de otras redes sociales ('S' para sí, 'N' para no).
 *                             - ComissionsPayment (string): Pago de comisiones.
 *                             - UserIdAgent (int): Identificador del agente asociado.
 *                             - UserIdAgent2 (int): Identificador del segundo agente asociado.
 */


$Helpers = new \Backend\dto\Helpers();


/* Ejecuta un script PHP y procesa parámetros de solicitud para manejar filas y nombres. */
exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'CONSULTA BETSHOPS " . $_SESSION['usuario'] . "  " . $_SESSION["win_perfil"] . "  " . $_SESSION["nombre"] . "' '#virtualsoft-cron2' > /dev/null & ");

$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$MaxRows = ($_REQUEST["count"] == "") ? $_REQUEST["?count"] : $_REQUEST["count"];
$seguir = true;

$Name = $_REQUEST["Name"];

/* recoge datos enviados mediante solicitudes HTTP para su procesamiento. */
$Login = $_REQUEST["Login"];
$CountrySelect = $_REQUEST["CountrySelect"];
$UserId = $_REQUEST["UserId"];

$ContactName = $_REQUEST["ContactName"];
$Email = $_REQUEST["Email"];

/* almacena datos de entrada de una solicitud en variables de PHP. */
$CityName = $_REQUEST["CityName"];
$District = $_REQUEST["District"];
$Region = $_REQUEST["Region"];
$RegionPerfil = $_REQUEST["regionperfil"];
$RegionName = $_REQUEST["RegionName"];
$dateFrom = $_REQUEST["dateFrom"];

/* recibe parámetros y almacena una consulta de agente en sesión. */
$dateTo = $_REQUEST["dateTo"];
$UserIdAgent = $_REQUEST["UserIdAgent"];
$UserIdAgent1 = $_REQUEST["UserIdAgent1"];
$UserIdAgent2 = $_REQUEST["UserIdAgent2"];

$consultaAgente = $_SESSION['consultaAgente'];


/* Convierte fechas de formulario a formato estándar con horas inicial y final. */
if ($dateFrom != "") {
    $dateFrom = date("Y-m-d 00:00:00", strtotime($dateFrom));
}
if ($dateTo != "") {
    $dateTo = date("Y-m-d 23:59:59", strtotime($dateTo));

}


/* recibe y procesa datos de una solicitud HTTP. */
$Address = $_REQUEST["Address"];
$Ip = $_REQUEST["Ip"];
$ManagerPhone = $_REQUEST["ManagerPhone"];
$Document = $_REQUEST["Document"];
$IPIdentification = $_REQUEST["IPIdentification"];
switch ($IPIdentification) {
    case '0':
        $IPIdentification = "0";
        break;
    case '1':
        $IPIdentification = "1";
        break;
    default:
        $IPIdentification = '';
        break;
}


/* obtiene parámetros de solicitud y valida variables para continuar un proceso. */
$Zone = $_REQUEST["Zone"];

$State = ($_REQUEST["State"] != 'A' && $_REQUEST["State"] != 'I') ? '' : $_REQUEST["State"];

$Mandante = new Mandante();

if ($SkeepRows == "" || $MaxRows == "") {

    $seguir = false;
}


/* asigna un valor a $OrderedItem y verifica el perfil del usuario. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($_SESSION["win_perfil"] == "PUNTOVENTA" || $_SESSION["win_perfil"] == "CAJERO") {
    $seguir = false;

}


/* verifica condiciones de sesión para asignar un perfil regional. */
if ($_SESSION['regionperfil'] != "0" && $_SESSION['regionperfil'] != null) {
    if ($_SESSION["win_perfil"] != "CONCESIONARIO" && $_SESSION["win_perfil"] != "CONCESIONARIO2" && $_SESSION["win_perfil"] != "CONCESIONARIO3" && $_SESSION["win_perfil"] != "PUNTOVENTA") {

        if ($_SESSION["regionperfil"] != "0" && $_SESSION["regionperfil"] != null) {
            $RegionPerfil = $_SESSION["regionperfil"];


        }

    }
} else {
    /* verifica si $RegionName no está vacío y asigna su valor a $RegionPerfil. */

    if ($RegionName != '') {
        $RegionPerfil = $RegionName;

    }
}


if ($seguir) {


    /* Crea reglas basadas en el perfil de usuario para concesionarios. */
    $rules = [];

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));
    }


    /* Verifica el perfil del usuario y agrega reglas relacionadas a concesionarios. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* Verifica el perfil de usuario y agrega reglas a un arreglo si coincide. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }

    /* Se agregan reglas de filtrado basadas en condiciones específicas para usuarios. */
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
    if ($RegionPerfil != "") {

        array_push($rules, array("field" => "usuario_perfil.region", "data" => $RegionPerfil, "op" => "eq"));
    }

    if ($UserIdAgent != "") {
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UserIdAgent, "op" => "eq"));
    }

    /* Agrega reglas basadas en la condición de usuarios agentes no vacíos. */
    if ($UserIdAgent1 != "") {
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UserIdAgent1, "op" => "eq"));
    }
    if ($UserIdAgent2 != "") {
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UserIdAgent2, "op" => "eq"));
    }


    /* agrega reglas basadas en el perfil del usuario y sesión activa. */
    if ($_SESSION["usuario"] == 4089418) {

        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => 693966, "op" => "eq"));
    }
    if ($consultaAgente != "0" && $consultaAgente != null && $consultaAgente != '') {

        $UsuarioPerfil = new UsuarioPerfil($consultaAgente);

        if ($UsuarioPerfil->perfilId == "CONCESIONARIO2") {

            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $consultaAgente, "op" => "eq"));

        } else if ($UsuarioPerfil->perfilId == "CONCESIONARIO") {

            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $consultaAgente, "op" => "eq"));

        }

    }


    /* procesa y limpia datos, agregando reglas para filtreo de usuario. */
    if ($Name != "") {
        $Name = str_replace("'", '', $Name);
        array_push($rules, array("field" => "usuario.nombre", "data" => $Name, "op" => "cn"));
    }

    if ($Login != "") {
        array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));
    }


    /* Agrega reglas de validación según si el email o documento están presentes. */
    if ($Email != "") {
        array_push($rules, array("field" => "punto_venta.email", "data" => $Email, "op" => "cn"));
    }


    if ($Document != "") {
        array_push($rules, array("field" => "punto_venta.cedula", "data" => $Document, "op" => "eq"));
    }

    /* Agrega una regla sobre identificación IP a un array si no está vacía. */
    if ($IPIdentification != "") {
        if ($IPIdentification == '0') {
            array_push($rules, array("field" => "punto_venta.identificacion_ip", "data" => "$IPIdentification", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "punto_venta.identificacion_ip", "data" => "$IPIdentification", "op" => "eq"));

        }
    }

    /* Agrega reglas a un arreglo si los nombres de contacto y ciudad no están vacíos. */
    if ($ContactName != "") {
        array_push($rules, array("field" => "punto_venta.nombre_contacto", "data" => $ContactName, "op" => "cn"));
    }
    if ($CityName != "") {
        array_push($rules, array("field" => "ciudad.ciudad_nom", "data" => $CityName, "op" => "cn"));
    }

    /* Agrega reglas de filtrado si el distrito o región no están vacíos. */
    if ($District != "") {
        array_push($rules, array("field" => "punto_venta.barrio", "data" => $District, "op" => "cn"));
    }
    if ($Region != "") {
        array_push($rules, array("field" => "departamento.depto_nom", "data" => $Region, "op" => "cn"));
    }


    /* Agrega reglas de filtrado por fechas si se proporcionan valores no vacíos. */
    if ($dateFrom != "") {
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateFrom", "op" => "ge"));

    }
    if ($dateTo != "") {
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateTo", "op" => "le"));

    }


    /* Agrega reglas de validación según dirección y teléfono del gerente si no están vacíos. */
    if ($Address != "") {
        array_push($rules, array("field" => "punto_venta.direccion", "data" => $Address, "op" => "cn"));
    }

    if ($ManagerPhone != "") {
        array_push($rules, array("field" => "punto_venta.telefono", "data" => $ManagerPhone, "op" => "cn"));
    }


    /* Agrega reglas para filtrar por estado e IP si tienen valores definidos. */
    if ($State != "") {
        array_push($rules, array("field" => "usuario.estado", "data" => $State, "op" => "cn"));
    }

    if ($Ip != "") {
        array_push($rules, array("field" => "usuario.dir_ip", "data" => $Ip, "op" => "cn"));
    }


    /* Añade reglas a un arreglo si $UserId o $Zone no están vacíos. */
    if ($UserId != "") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));
    }

    if ($Zone != "") {
        array_push($rules, array("field" => "punto_venta.clasificador4_id", "data" => $Zone, "op" => "eq"));
    }


    /* Se agregan reglas de filtrado por país si se cumplen ciertas condiciones. */
    if ($CountrySelect != "" && $CountrySelect != "0") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global

    /* ajusta reglas según el estado de la sesión y mandante. */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    /* Agrega reglas de filtrado a un array y las muestra si está en modo debug. */
    array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

    if ($_ENV['debug']) {
        print_r($rules);
    }

    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    /* Se crea un filtro en formato JSON y se establece un orden ascendente. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $order = "punto_venta.puntoventa_id";
    $orderType = "asc";


    /* Ordena resultados basados en parámetros de solicitud para identificar el tipo de orden. */
    if ($_REQUEST["sort"]["Id"] != "") {
        $order = "usuario.usuario_id";
        $orderType = ($_REQUEST["sort"]["Id"] == "asc") ? "asc" : "desc";

    }

  /*if ($_REQUEST["sort"]["Login"] != "") {
        $order = "usuario.login";
        $orderType = ($_REQUEST["sort"]["Login"] == "asc") ? "asc" : "desc";
    }

    if ($_REQUEST["sort"]["Name"] != "") {
        $order = "usuario.nombre";
        $orderType = ($_REQUEST["sort"]["Name"] == "asc") ? "asc" : "desc";
    }*/


    $PuntoVenta = new PuntoVenta();


    /* obtiene datos personalizados de puntos de venta y los decodifica en JSON. */
    $mandantes = $PuntoVenta->getPuntoVentasCustom("clasificador4.descripcion,ciudad.ciudad_nom,departamento.depto_nom,usuario.nombre,usuario.login,usuario.mandante,usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*,pais.*,concesionario.usupadre_id,concesionario.usupadre2_id, usuario.dir_ip,usuario_perfil.region", $order, $orderType, $SkeepRows, $MaxRows, $json, true);

    $mandantes = json_decode($mandantes);

    $final = [];

    foreach ($mandantes->data as $key => $value) {


        /* verifica el estado del usuario y asigna acciones según el perfil. */
        $array = [];
        $array["StateValidate"] = $value->{"usuario.estado_valida"};

        if ($_SESSION["win_perfil2"] != "CONCESIONARIO" && $_SESSION["win_perfil2"] != "CONCESIONARIO2" && $_SESSION["win_perfil2"] != "CONCESIONARIO3") {
            $array["Action"] = $value->{"usuario.estado_valida"};

        } else {
            /* asigna una cadena vacía a la clave "Action" del array si no se cumple la condición. */

            $array["Action"] = '';
        }

        /* asigna valores de un objeto a un array asociativo. */
        $array["UserId"] = $value->{"punto_venta.usuario_id"};

        $array["id"] = $value->{"punto_venta.usuario_id"};
        $array["Id"] = $value->{"punto_venta.usuario_id"};

        $array["Name"] = $value->{"usuario.nombre"};
        $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME',true);
        $array["Name"] = $Helpers->decode_data($array["Name"]);



        $array["Login"] = $value->{"usuario.login"};
        $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN',true);
        $array["Login"] = $Helpers->decode_data($array["Login"]);


        /* Asigna valores a un array desde propiedades de un objeto en PHP. */
        $array["Phone"] = $value->{"punto_venta.telefono"};
        $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE',true);
        $array["Phone"] = $Helpers->decode_data($array["Phone"]);

        $array["Email"] = $value->{"usuario.email"};
        $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN',true);
        $array["Email"] = $Helpers->decode_data($array["Email"]);

        $array["CityName"] = $value->{"ciudad.ciudad_nom"};
        $array["DepartmentName"] = $value->{"departamento.depto_nom"};
        $array["RegionName"] = $value->{"pais.pais_nom"};
        $array["Region"] = $value->{"usuario_perfil.region"};

        /* asigna datos a un arreglo asociativo sobre usuarios y países. */
        $array["Country"] = $value->{"usuario.mandante"} . '-' . $value->{"pais.pais_nom"};
        $array["CountryIcon"] = strtolower($value->{"pais.iso"});
        $array["CurrencyId"] = $value->{"usuario.moneda"};

        $array["Address"] = $value->{"punto_venta.direccion"};
        $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_ADDRESS',true);
        $array["Address"] = $Helpers->decode_data($array["Address"]);


        $array["CreatedDate"] = $value->{"usuario.fecha_crea"};;
        $array["LastLoginDateLabel"] = $value->{"usuario.fecha_ult"};;

        /* asigna valores a un array desde un objeto de entrada. */
        $array["Partner"] = $value->{"usuario.mandante"};
        $array["Ip"] = $value->{"usuario.dir_ip"};

        $array["Type"] = $value->{"tipo_punto.descripcion"};
        $array["MinBet"] = $value->{"usuario_premiomax.apuesta_min"};
        $array["PreMatchPercentage"] = $value->{"punto_venta.porcen_comision"};

        /* Asigna valores de objetos a un array mediante claves específicas. */
        $array["LivePercentage"] = $value->{"punto_venta.porcen_comision"};
        $array["RecargasPercentage"] = $value->{"punto_venta.porcen_comision2"};

        $array["Amount"] = ($value->{"punto_venta.cupo_recarga"});
        $array["AmountBetting"] = $value->{"punto_venta.creditos_base"};
        $array["Zone"] = $value->{"clasificador4.descripcion"};


        /* Asigna valores a un array y determina identificación IP mediante un switch. */
        $array["Document"] = $value->{"punto_venta.cedula"};
        $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_DOCUMENT',true);
        $array["Document"] = $Helpers->decode_data($array["Document"]);

        switch ($value->{"punto_venta.identificacion_ip"}) {
            case '0':
                $IPIdentification = "N";
                break;
            case '1':
                $IPIdentification = "S";
                break;
        }

        /* asigna valores a un array y verifica la autenticidad de Facebook. */
        $array["IPIdentification"] = $IPIdentification;

        $array["Facebook"] = $value->{"punto_venta.facebook"};
        switch ($value->{"punto_venta.facebook_verificacion"}) {
            case '0':
                $FacebookVerification = "N";
                break;
            case '1':
                $FacebookVerification = "S";
                break;
        }

        /* asigna verificaciones de Facebook e Instagram a un array. */
        $array["FacebookVerification"] = $FacebookVerification;

        $array["Instagram"] = $value->{"punto_venta.instagram"};
        switch ($value->{"punto_venta.instagram_verificacion"}) {
            case '0':
                $InstagramVerification = "N";
                break;
            case '1':
                $InstagramVerification = "S";
                break;
        }

        /* Se asignan verificaciones de Instagram y WhatsApp basadas en valores específicos. */
        $array["InstagramVerification"] = $InstagramVerification;

        $array["WhatsApp"] = $value->{"punto_venta.whatsApp"};
        switch ($value->{"punto_venta.whatsApp_verificacion"}) {
            case '0':
                $WhatsAppVerification = "N";
                break;
            case '1':
                $WhatsAppVerification = "S";
                break;
        }

        /* asigna valores de verificación a un array basado en condiciones específicas. */
        $array["WhatsAppVerification"] = $WhatsAppVerification;

        $array["OtherSocialMedia"] = $value->{"punto_venta.otraredessociales"};
        switch ($value->{"punto_venta.otraredessociales_verificacion"}) {
            case '0':
                $OtherSocialMediaVerification = "N";
                break;
            case '1':
                $OtherSocialMediaVerification = "S";
                break;
        }

        /* Se añaden elementos a un array final con datos del usuario y pagos. */
        $array["OtherSocialMediaVerification"] = $OtherSocialMediaVerification;
        $array["ComissionsPayment"] = $value->{"usuario.pago_comisiones"};
        $array["UserIdAgent"] = $value->{"concesionario.usupadre_id"};
        $array["UserIdAgent2"] = $value->{"concesionario.usupadre2_id"};

        array_push($final, $array);

    }


    /* configura una respuesta sin errores, incluyendo tipo y mensaje de alerta. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    //$response["Data"] = array();
    //$response["Data"]["Objects"] = $final;

    $response["pos"] = $SkeepRows;

    /* asigna el total de mandantes y datos finales a una respuesta. */
    $response["total_count"] = $mandantes->count[0]->{".count"};
    $response["data"] = $final;

    //Objects

} else {
    /* inicializa una respuesta vacía si no se cumple una condición. */

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}


/**
 * Convierte una cadena que representa un número flotante a su forma normalizada.
 *
 * @param string $floatAsString La cadena que representa el número flotante.
 * @return string La representación normalizada del número flotante.
 */
function convertFloat($floatAsString)
{
    $norm = strval(floatval($floatAsString));

    if (($e = strrchr($norm, 'E')) === false) {
        return $norm;
    }

    /* Formatea un número basado en la longitud de una cadena, ajustando decimales negativos. */
    return number_format($norm, -intval(substr($e, 1)));
}