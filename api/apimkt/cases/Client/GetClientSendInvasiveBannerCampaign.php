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
 * Client/GetClientSendInvasiveBannerCampaign
 *
 * Obtención de mensajes de campaña de usuario
 *
 * Este recurso recupera mensajes de usuario en campañas, aplicando filtros y reglas basadas en parámetros
 * como fechas, activación, globalidad, país, cliente, entre otros. Se genera un JSON con las reglas aplicadas
 * y se consulta la base de datos para obtener los mensajes correspondientes.
 *
 * @param int $params ->length         : Cantidad máxima de registros a obtener.
 * @param int $params ->OrderedItem    : Índice de ordenamiento de los resultados.
 * @param int $params ->start          : Número de registros a omitir (paginación).
 * @param string $params ->IsGlobal       : Define si la consulta es global ('C') o no.
 * @param int $params ->Id             : ID de la campaña si aplica.
 * @param string $params ->IsActivate     : Estado de activación de los mensajes ('A' para activos, 'I' para inactivos).
 * @param int $params ->CountrySelect  : ID del país seleccionado.
 * @param string $params ->DateFrom       : Fecha de inicio del rango de búsqueda (formato YYYY-MM-DD).
 * @param string $params ->DateTo         : Fecha de fin del rango de búsqueda (formato YYYY-MM-DD).
 * @param int $_REQUEST ["ClientIdFrom"] : ID del cliente remitente.
 * @param int $_REQUEST ["ClientIdTo"]   : ID del cliente destinatario.
 * @param string $_REQUEST ["Read"]         : Estado de lectura del mensaje ('0' no leído, '1' leído).
 * @param int $_REQUEST ["GlobalId"]     : ID global del mensaje.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *result* (string): Contiene el mensaje de error o éxito.
 *  - *data* (array): Contiene el listado de mensajes obtenidos.
 *  - *pos* (int): Indica el número de registros omitidos (paginación).
 *  - *total_count* (int): Total de registros disponibles en la consulta.
 *
 * Objeto en caso de error:
 *
 * "code" => [Código de error],
 * "result" => "[Mensaje de error]",
 * "data" => array(),
 *
 * @throws Exception Si ocurre un error en la consulta de mensajes.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Asignación de parámetros a variables para su uso en un código. */
$MaxRows = $params->length;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->start;
$IsGlobal = $params->IsGlobal;
$IdCampa = $params->Id;
$IsActivate = $params->IsActivate;

/* asigna fechas y país a variables si están presentes en parámetros. */
$CountrySelect = $params->CountrySelect;

if ($params->DateFrom != "") {
    $DateFrom = $params->DateFrom;
}

if ($params->DateTo != "") {
    $DateTo = $params->DateTo;
}

/* Asigna valores predeterminados a $MaxRows y $SkeepRows si están vacíos. */
if ($MaxRows == "") {

    $MaxRows = $params->length;
}

if ($SkeepRows == "") {

    $SkeepRows = $params->start;
}


/* procesa solicitudes y asigna variables a parámetros HTTP. */
$ClientIdFrom = $_REQUEST["ClientIdFrom"];
$ClientIdTo = $_REQUEST["ClientIdTo"];
$Read = ($_REQUEST["Read"] != "1" && $_REQUEST["Read"] != "0") ? '' : $_REQUEST["Read"];
$GlobalId = $_REQUEST["GlobalId"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}


/* Se inicializan dos variables: un array vacío para mensajes y otro para reglas. */
$mensajesRecibidos = [];

$rules = [];

if ($IsGlobal != "") {

    /* Se añaden reglas basadas en condiciones relacionadas con la globalidad y selección de país. */
    if ($IsGlobal == 'C' && $CountrySelect == "0") {
        array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => 0, "op" => "eq"));
        array_push($rules, array("field" => "usuario_mensajecampana.parent_id", "data" => 0, "op" => "eq"));
        //array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => -1, "op" => "eq"));
    }
    if ($IsGlobal == 'C' && $CountrySelect != "0" && $IdCampa == '') {
        array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => 0, "op" => "eq"));
        array_push($rules, array("field" => "usuario_mensajecampana.parent_id", "data" => 0, "op" => "eq"));
        //array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => -1, "op" => "eq"));
        array_push($rules, array("field" => "usuario_mensajecampana.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }


    /* Agrega reglas basadas en condiciones sobre campañas y fechas para filtrar datos. */
    if ($IsGlobal == 'C' && $IdCampa != '') {

        array_push($rules, array("field" => "usuario_mensajecampana.usumencampana_id", "data" => $IdCampa, "op" => "eq"));
    }
    if ($IsGlobal == 'C' && $DateFrom != "") {

        //  array_push($rules, array("field" => "usuario_mensajecampana.fecha_expiracion", "data" => "$DateFrom 00:00:00" , "op" => "ge"));
        array_push($rules, array("field" => "usuario_mensajecampana.fecha_envio", "data" => "$DateFrom 00:00:00", "op" => "ge"));
    }

    /* Agrega una regla si $IsGlobal es 'C' y $DateTo no está vacío. */
    if ($IsGlobal == 'C' && $DateTo != "") {
        // array_push($rules, array("field" => "usuario_mensajecampana.fecha_expiracion", "data" => "$DateTo 00:00:00" , "op" => "le"));
        array_push($rules, array("field" => "usuario_mensajecampana.fecha_envio", "data" => "$DateTo 23:59:00", "op" => "le"));
    }
}

/* modifica reglas según el estado de activación de un usuario. */
if ($IsActivate != '') {
    if ($IsActivate == 'I') {
        array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => 1, "op" => "eq"));
    }
    if ($IsActivate == 'A') {
        array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => 0, "op" => "eq"));
    }
}


/* condiciona reglas basadas en el valor de $ClientIdFrom. */
if ($ClientIdFrom != "") {
    if ($ClientIdFrom == '0') {

        array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => $ClientIdFrom, "op" => "eq"));

    } else {

        array_push($rules, array("field" => "usufrom.usuario_mandante", "data" => $ClientIdFrom, "op" => "eq"));

    }
}


/* Condicional que agrega reglas a un array según el valor de $ClientIdTo. */
if ($ClientIdTo != "") {
    if ($ClientIdTo == '0') {

        array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => $ClientIdTo, "op" => "eq"));

    } else {
        array_push($rules, array("field" => "usuto.usuario_mandante", "data" => $ClientIdTo, "op" => "eq"));

    }
}


/* Añade reglas a un arreglo según condiciones de variables no vacías. */
if ($Read != "") {
    array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => $Read, "op" => "eq"));
}


if ($GlobalId != "") {
    array_push($rules, array("field" => "usuario_mensajecampana.externo_id", "data" => $GlobalId, "op" => "eq"));
}


/* Agrega una regla al array $rules para el campo tipo de mensaje. */
array_push($rules, array("field" => "usuario_mensajecampana.tipo", "data" => "MESSAGEINV", "op" => "eq"));

/*array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => '0', "op" => "in"));*/


/* Condiciona reglas basadas en país y mandante, excluyendo reportes para Colombia. */
if ($ClientIdTo == '0') {
    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario_mensaje.pais_id", "data" => $_SESSION["pais_id"] . '', "op" => "in"));
    }
// Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario_mensaje.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario_mensajecampana.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }// Inactivamos reportes para el país Colombia
    array_push($rules, array("field" => "usuario_mensajecampana.pais_id", "data" => "1", "op" => "ne"));

} else {
    /* Condiciones para agregar reglas basadas en país y mandante del usuario. */

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


/* Crea un filtro JSON y obtiene usuarios de mensajes de campaña según criterios específicos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$UsuarioMensajecampana = new UsuarioMensajecampana();
$usuariosCampana = $UsuarioMensajecampana->getUsuarioMensajesCustom("pais.pais_nom,usuario_mensajecampana.*,usufrom.*,usuto.* ", "usuario_mensajecampana.usumencampana_id", "desc", $SkeepRows, $MaxRows, $json2, true);

/* Se decodifica un JSON y se inicializa un incrementador en 1. */
$usuariosCampana = json_decode($usuariosCampana);

$Incrementador = 1;
foreach ($usuariosCampana->data as $key => $value) {

    if ($IsGlobal == 'C') {


        /* Crea un array asociativo con información de un mensaje de campaña. */
        $array = [];
        $array["Incrementador"] = $Incrementador++;
        $array["Id"] = $value->{"usuario_mensajecampana.usumencampana_id"};
        $array["Title"] = $value->{"usuario_mensajecampana.nombre"};
        $array["DateExpiration"] = $value->{"usuario_mensajecampana.fecha_expiracion"};
        $array["DateFrom"] = $value->{"usuario_mensajecampana.fecha_envio"};

        /* asigna valores de un objeto a un array y verifica si está leído. */
        $array["CountrySelect"] = $value->{"pais.pais_nom"};
        $array["Message"] = $value->{"usuario_mensajecampana.body"};
        $array["Description"] = $value->{"usuario_mensajecampana.descripcion"};
        $array["T_Value"] = json_decode($value->{"usuario_mensajecampana.t_value"});
        if ($value->{"usuario_mensajecampana.is_read"} == '0') {
            $array["IsActivate"] = 'A';
        } elseif ($value->{"usuario_mensajecampana.is_read"} == '1') {

            /* Añade un nuevo elemento al arreglo 'mensajesRecibidos' con estado 'IsActivate' como 'I'. */
            $array["IsActivate"] = 'I';
        }

        array_push($mensajesRecibidos, $array);


    } else if ($IsGlobal == 'C' && $IsActivate == 'A') {


        /* crea un array de mensajes no leídos, asignando propiedades específicas. */
        if ($value->{"usuario_mensajecampana.is_read"} == 0) {
            $array = [];
            $array["Incrementador"] = $Incrementador++;
            $array["Id"] = $value->{"usuario_mensajecampana.usumencampana_id"};
            $array["Title"] = $value->{"usuario_mensajecampana.nombre"};
            $array["DateExpiration"] = $value->{"usuario_mensajecampana.fecha_expiracion"};
            $array["DateFrom"] = $value->{"usuario_mensajecampana.fecha_envio"};
            $array["CountrySelect"] = $value->{"pais.pais_nom"};
            $array["Message"] = $value->{"usuario_mensajecampana.body"};
            $array["Description"] = $value->{"usuario_mensajecampana.descripcion"};
            $array["T_Value"] = json_decode($value->{"usuario_mensajecampana.t_value"});
            if ($value->{"usuario_mensajecampana.is_read"} == '0') {
                $array["IsActivate"] = 'A';
            } elseif ($value->{"usuario_mensajecampana.is_read"} == '1') {
                $array["IsActivate"] = 'I';
            }

            array_push($mensajesRecibidos, $array);
        }
    } else if ($IsGlobal == 'C' && $IsActivate == 'I') {


        /* Condicional que procesa datos de mensajes leídos y los organiza en un array. */
        if ($value->{"usuario_mensajecampana.is_read"} == 1) {
            $array = [];
            $array["Incrementador"] = $Incrementador++;
            $array["Id"] = $value->{"usuario_mensajecampana.usumencampana_id"};
            $array["Title"] = $value->{"usuario_mensajecampana.nombre"};
            $array["DateExpiration"] = $value->{"usuario_mensajecampana.fecha_expiracion"};
            $array["DateFrom"] = $value->{"usuario_mensajecampana.fecha_envio"};
            $array["CountrySelect"] = $value->{"pais.pais_nom"};
            $array["Message"] = $value->{"usuario_mensajecampana.body"};
            $array["Description"] = $value->{"usuario_mensajecampana.descripcion"};
            $array["T_Value"] = json_decode($value->{"usuario_mensajecampana.t_value"});
            if ($value->{"usuario_mensajecampana.is_read"} == '0') {
                $array["IsActivate"] = 'A';
            } elseif ($value->{"usuario_mensajecampana.is_read"} == '1') {
                $array["IsActivate"] = 'I';
            }

            array_push($mensajesRecibidos, $array);
        }
    }
}


/* Crea un array vacío para almacenar mensajes en la respuesta. */
$response = array();


$response["data"] = array(
    "messages" => array()
);


/* asigna datos y conteos a un arreglo de respuesta. */
$response["Data"] = $mensajesRecibidos;
$response["data"] = $mensajesRecibidos;

$response["pos"] = $SkeepRows;
$response["total_count"] = $usuariosCampana->count[0]->{".count"};

