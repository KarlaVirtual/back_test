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
use Backend\dto\UsuarioMensajecampana;
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
 * Client/getStadistChart
 *
 * Asignación y procesamiento de parámetros para el filtrado de mensajes.
 *
 * Esta función maneja la asignación y procesamiento de parámetros de entrada relacionados con los mensajes de usuario, incluyendo la fecha, sección, cliente y otros valores.
 * A partir de estos parámetros, se generan reglas de filtrado para realizar una consulta a la base de datos con el fin de obtener los mensajes, y luego se procesan los resultados para presentarlos de manera estructurada.
 * El resultado final es un conjunto de datos que se utilizarán para mostrar la información de los mensajes en un formato adecuado para el usuario.
 *
 * @param object $params : Objeto que contiene los parámetros necesarios para la consulta y filtrado de mensajes.
 * @param string $params->length : Número máximo de filas a obtener.
 * @param string $params->Section : Sección específica para la cual se realiza el filtrado de mensajes.
 * @param string $params->start : Número de fila desde la cual comenzar el filtrado.
 * @param bool $params->IsGlobal : Indica si los filtros deben aplicarse globalmente.
 * @param string $params->DateFrom : Fecha de inicio para el filtrado de mensajes.
 * @param string $params->DateTo : Fecha de fin para el filtrado de mensajes.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna array vacío en caso de éxito o contiene errores del modelo.
 *  - *Data* (array): Contiene los resultados de los mensajes procesados, incluyendo las fechas y el número de mensajes no leídos.
 *
 * Objeto en caso de error:
 *
 * "HasError" => true,
 * "AlertType" => "error",
 * "AlertMessage" => "Error en el procesamiento de los mensajes",
 * "ModelErrors" => [],
 * "Data" => [],
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */



/* Código que asigna parámetros y verifica fecha para procesamiento de datos. */
$MaxRows = $params->length;
$Section = $params->Section;
$SkeepRows = $params->start;
$IsGlobal = $params->IsGlobal;


if ($params->DateFrom != "") {
    $DateFrom = $params->DateFrom;
}


/* asigna valores a variables según condiciones de los parámetros entregados. */
if ($params->DateTo != "") {
    $DateTo = $params->DateTo;
}

if ($MaxRows == "") {

    $MaxRows = $params->length;
}


/* asigna un valor a $SkeepRows si está vacío y obtiene ClientIdFrom. */
if ($SkeepRows == "") {

    $SkeepRows = $params->start;
}

$ClientIdFrom = $_REQUEST["ClientIdFrom"];

/* obtiene parámetros de una solicitud y verifica condiciones específicas para su uso. */
$ClientIdTo = $_REQUEST["ClientIdTo"];
$Read = ($_REQUEST["Read"] != "1" && $_REQUEST["Read"] != "0") ? '' : $_REQUEST["Read"];
$GlobalId = $_REQUEST["GlobalId"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* establece un valor por defecto y prepara arreglos para mensajes y reglas. */
if ($MaxRows == "") {
    $MaxRows = 10;
}

$mensajes = [];

$rules = [];


/* Filtra mensajes por fecha y estado de lectura, agregando reglas a un arreglo. */
if ($DateFrom != "") {
    array_push($rules, array("field" => "usuario_mensajecampana.fecha_crea", "data" => "$DateFrom 00:00:00 ", "op" => "ge"));
    array_push($rules, array("field" => "usuario_mensajecampana.fecha_crea", "data" => "$DateFrom 23:59:00", "op" => "le"));
}


array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => 0, "op" => "eq"));

/* Se añaden reglas a un array según condiciones y valores de parámetros específicos. */
array_push($rules, array("field" => "usuario_mensajecampana.parent_id", "data" => 0, "op" => "eq"));
array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => -1, "op" => "eq"));


if ($ClientIdFrom != "") {
    if ($ClientIdFrom == '0') {

        array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => $ClientIdFrom, "op" => "eq"));

    } else {

        array_push($rules, array("field" => "usufrom.usuario_mandante", "data" => $ClientIdFrom, "op" => "eq"));

    }
}


/* Condiciona la adición de reglas según el valor de $ClientIdTo. */
if ($ClientIdTo != "") {
    if ($ClientIdTo == '0') {

        array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => $ClientIdTo, "op" => "eq"));

    } else {
        array_push($rules, array("field" => "usuto.usuario_mandante", "data" => $ClientIdTo, "op" => "eq"));

    }
}


/* agrega reglas condicionales basadas en variables globales y secciones específicas. */
if ($GlobalId != "") {
    array_push($rules, array("field" => "usuario_mensajecampana.externo_id", "data" => $GlobalId, "op" => "eq"));
}

if ($Section != "") {
    if ($Section == "messenger") {

        array_push($rules, array("field" => "usuario_mensajecampana.tipo", "data" => "MENSAJE", "op" => "eq"));
    }
    if ($Section == "popup") {

        array_push($rules, array("field" => "usuario_mensajecampana.tipo", "data" => "BANNERINV", "op" => "eq"));
    }
    if ($Section == "bannerInv") {

        array_push($rules, array("field" => "usuario_mensajecampana.tipo", "data" => "MESSAGEINV", "op" => "eq"));
    }
    if ($Section == "franjaSup") {

        array_push($rules, array("field" => "usuario_mensajecampana.tipo", "data" => "STRIPETOP", "op" => "eq"));
    }
}

/*array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => '0', "op" => "in"));*/


/* configura reglas de filtrado según país y mandante en sesiones de usuario. */
if ($ClientIdTo == '0') {
    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario_mensajecampana.pais_id", "data" => $_SESSION["pais_id"] . '', "op" => "in"));
    }
// Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario_mensajecampana.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario_mensajecampana.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }// Inactivamos reportes para el país Colombia
    array_push($rules, array("field" => "usuario_mensajecampana.pais_id", "data" => "1", "op" => "ne"));

} else {
    /* gestiona reglas basadas en condiciones de país y mandante del usuario. */

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario_mensajecampana.pais_id", "data" => $_SESSION["pais_id"] . '', "op" => "in"));
    }
// Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario_mensajecampana.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario_mensajecampana.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

}


/* Se crea un filtro JSON y se obtienen mensajes de usuarios con condiciones específicas. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$UsuarioMensajecampana = new UsuarioMensajecampana();
$usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom2("DATE_FORMAT(usuario_mensajecampana.fecha_crea,'%d-%m-%Y') AS Fechas, count(CASE WHEN  usuario_mensajecampana.is_read = 0 THEN  1 else NULL end) AS activas", "Fechas", "desc", $SkeepRows, $MaxRows, $json2, true, '', 'Fechas');

/* Decodifica un JSON y almacena fechas y leídos de usuarios no leídos. */
$usuarios = json_decode($usuarios);

$ArrayFecha = [];
$ArrayLeidos = [];

foreach ($usuarios->data as $key => $value) {

    if ($value->{"usuario_mensajecampana.is_read"} == 0) {
        $Fecha = $value->{".Fechas"};
        $Leidos = intval($value->{".activas"});

        array_push($ArrayFecha, $Fecha);
        array_push($ArrayLeidos, $Leidos);

    }

}


/* Se inicializan un arreglo y un objeto vacío en PHP. */
$respuesta = array();
$response = new stdClass();

$respuesta = array(
    "labels" => $ArrayFecha,

    "datasets" => array(
        array(
            "data" => $ArrayLeidos,

            "label" => "Campañas Activas",
            "backgroundColor" => array(
                strval('rgba(54,162,235,0.2)'),

            ),
            "borderColor" => array(
                strval('rgba(54,162,235,1)'),

            ),
            "borderWidth" => 1
        ),
    )

);

/* Asigna datos a la respuesta y establece que no hubo errores. */
$response->data = $respuesta;
$response->HasError = false;

