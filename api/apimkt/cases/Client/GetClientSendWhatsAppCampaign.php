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
 * Client/GetClientSendWhatsAppCampaign
 *
 * Procesa y gestiona los mensajes de campaña para usuarios.
 *
 * Esta función recibe un conjunto de parámetros relacionados con los mensajes de campaña, organiza la información de los mensajes según el estado de activación, y crea un array de respuestas con los mensajes obtenidos de la base de datos.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada necesarios para procesar la consulta de los mensajes. Este objeto incluye propiedades como:
 *   - *length* (int): Número máximo de filas a recuperar.
 *   - *OrderedItem* (int): Identificador del ítem ordenado.
 *   - *start* (int): Número de filas a omitir desde el inicio.
 *   - *IsGlobal* (string): Indicador de si la consulta es global o específica.
 *   - *Id* (string): Identificador de la campaña.
 *   - *IsActivate* (string): Indicador del estado de activación de los mensajes.
 *   - *CountrySelect* (int): Identificador del país seleccionado.
 *   - *CountryId* (int): Identificador del país.
 *   - *DateFrom* (string): Fecha de inicio para filtrar los mensajes.
 *   - *DateTo* (string): Fecha de fin para filtrar los mensajes.
 *   - *ClientIdFrom* (int): Identificador del cliente origen.
 *   - *ClientIdTo* (int): Identificador del cliente destino.
 *   - *Read* (int): Estado de lectura de los mensajes.
 *   - *GlobalId* (int): Identificador global para los mensajes.
 *
 * @return array $response : Array de respuesta que contiene los mensajes obtenidos y el total de mensajes.
 *   - *messages* (array): Lista de los mensajes procesados.
 *   - *Data* (array): Contiene los mensajes obtenidos y organizados en base a los filtros aplicados.
 *   - *pos* (int): Número de fila desde donde se empieza la consulta.
 *   - *total_count* (int): Número total de mensajes encontrados.
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna parámetros a variables para gestionar datos en una aplicación. */
$MaxRows = $params->length;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->start;
$IsGlobal = $params->IsGlobal;
$IdCampa = $params->Id;
$IsActivate = $params->IsActivate;

/* Asigna valores de parámetros a variables y verifica si se proporciona una fecha. */
$CountrySelect = $params->CountrySelect;
$CountryId = $params->CountryId;

if ($params->DateFrom != "") {
    $DateFrom = $params->DateFrom;
}


/* Asignación de valores a variables según condiciones de parámetros proporcionados. */
if ($params->DateTo != "") {
    $DateTo = $params->DateTo;
}
if ($MaxRows == "") {

    $MaxRows = $params->length;
}


/* inicializa `$SkeepRows` y obtiene el `ClientIdFrom` de la solicitud. */
if ($SkeepRows == "") {

    $SkeepRows = $params->start;
}

$ClientIdFrom = $_REQUEST["ClientIdFrom"];

/* captura parámetros de solicitud y valida un valor específico. */
$ClientIdTo = $_REQUEST["ClientIdTo"];
$Read = ($_REQUEST["Read"] != "1" && $_REQUEST["Read"] != "0") ? '' : $_REQUEST["Read"];
$GlobalId = $_REQUEST["GlobalId"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}


/* Se inicializan dos variables: una lista para mensajes y un arreglo para reglas. */
$mensajesRecibidos = [];

$rules = [];

if ($IsGlobal != "") {

    /* Condiciona reglas basadas en parámetros globales y selección de país. */
    if ($IsGlobal == 'C' && $CountrySelect == "0") {
        array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => 0, "op" => "eq"));
        array_push($rules, array("field" => "usuario_mensajecampana.parent_id", "data" => 0, "op" => "eq"));
        array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => -1, "op" => "eq"));
    }
    if ($IsGlobal == 'C' && $CountrySelect != 0 && $IdCampa == '') {
        array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => 0, "op" => "eq"));
        array_push($rules, array("field" => "usuario_mensajecampana.parent_id", "data" => 0, "op" => "eq"));
        array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => -1, "op" => "eq"));
        array_push($rules, array("field" => "usuario_mensajecampana.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }


    /* Condiciona reglas basadas en variables para filtrar mensajes de campaña. */
    if ($IsGlobal == 'C' && $IdCampa != '') {

        array_push($rules, array("field" => "usuario_mensajecampana.usumencampana_id", "data" => $IdCampa, "op" => "eq"));
    }

    if ($IsGlobal == 'C' && $DateFrom != "") {

        //  array_push($rules, array("field" => "usuario_mensajecampana.fecha_expiracion", "data" => "$DateFrom 00:00:00" , "op" => "ge"));
        array_push($rules, array("field" => "usuario_mensajecampana.fecha_envio", "data" => "$DateFrom 00:00:00", "op" => "ge"));
    }

    /* Condicional que agrega regla si es global y hay una fecha específica. */
    if ($IsGlobal == 'C' && $DateTo != "") {
        // array_push($rules, array("field" => "usuario_mensajecampana.fecha_expiracion", "data" => "$DateTo 00:00:00" , "op" => "le"));
        array_push($rules, array("field" => "usuario_mensajecampana.fecha_envio", "data" => "$DateTo 23:59:00", "op" => "le"));
    }

}

/* añade reglas según el estado de activación del mensaje. */
if ($IsActivate != '') {
    if ($IsActivate == 'I') {
        array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => 1, "op" => "eq"));
    }
    if ($IsActivate == 'A') {
        array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => 0, "op" => "eq"));
    }
}


/* verifica el valor de $ClientIdFrom y agrega reglas a un array. */
if ($ClientIdFrom != "") {
    if ($ClientIdFrom == '0') {

        array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => $ClientIdFrom, "op" => "eq"));

    } else {

        array_push($rules, array("field" => "usufrom.usuario_mandante", "data" => $ClientIdFrom, "op" => "eq"));

    }
}


/* agrega reglas basadas en el valor de $ClientIdTo. */
if ($ClientIdTo != "") {
    if ($ClientIdTo == '0') {

        array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => $ClientIdTo, "op" => "eq"));

    } else {
        array_push($rules, array("field" => "usuto.usuario_mandante", "data" => $ClientIdTo, "op" => "eq"));

    }
}


/* agrega condiciones a un arreglo solo si las variables no están vacías. */
if ($Read != "") {
    array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => $Read, "op" => "eq"));
}


if ($GlobalId != "") {
    array_push($rules, array("field" => "usuario_mensajecampana.externo_id", "data" => $GlobalId, "op" => "eq"));
}


/* Agrega una regla para verificar si el tipo es "WHATSAPP" en la campaña. */
array_push($rules, array("field" => "usuario_mensajecampana.tipo", "data" => "WHATSAPP", "op" => "eq"));

/*array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => '0', "op" => "in"));*/


/* Condiciones de reglas basadas en país y mandante para la gestión de usuarios. */
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
    /* Agrega reglas de filtrado basadas en país y mandante del usuario. */

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


/* Se crea un filtro en JSON y se obtienen mensajes de usuarios de campaña. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$UsuarioMensajecampana = new UsuarioMensajecampana();
$usuariosCampana = $UsuarioMensajecampana->getUsuarioMensajesCustom("pais.pais_nom,usuario_mensajecampana.*,usufrom.*,usuto.* ", "usuario_mensajecampana.usumencampana_id", "desc", $SkeepRows, $MaxRows, $json2, true);

/* decodifica un JSON y prepara un contador inicial para usuarios. */
$usuariosCampana = json_decode($usuariosCampana);

$Incrementador = 1;


foreach ($usuariosCampana->data as $key => $value) {

    if ($IsGlobal == 'C' && $IsActivate == "") {


        /* Crear un array con información de usuario y fechas de campaña. */
        $array = [];
        $array["Incrementador"] = $Incrementador++;
        $array["Id"] = $value->{"usuario_mensajecampana.usumencampana_id"};
        $array["Title"] = $value->{"usuario_mensajecampana.nombre"};
        $array["DateExpiration"] = $value->{"usuario_mensajecampana.fecha_expiracion"};
        $array["DateFrom"] = $value->{"usuario_mensajecampana.fecha_envio"};

        /* Asigna datos a un array según condiciones de un objeto JSON. */
        $array["CountrySelect"] = $value->{"pais.pais_nom"};
        $array["Message"] = $value->{"usuario_mensajecampana.body"};
        $array["Description"] = $value->{"usuario_mensajecampana.descripcion"};
        $array["T_Value"] = json_decode($value->{"usuario_mensajecampana.t_value"});
        if ($value->{"usuario_mensajecampana.is_read"} == '0') {
            $array["IsActivate"] = 'A';
        } elseif ($value->{"usuario_mensajecampana.is_read"} == '1') {

            /* Se agrega un nuevo mensaje al array con el estado 'IsActivate' como 'I'. */
            $array["IsActivate"] = 'I';
        }

        array_push($mensajesRecibidos, $array);

    } else if ($IsGlobal == 'C' && $IsActivate == 'A') {


        /* Se verifica si un mensaje no se ha leído y se organiza información en un array. */
        if ($value->{"usuario_mensajecampana.is_read"} == '0') {
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


        /* crea un arreglo con detalles de mensajes leídos, según condiciones específicas. */
        if ($value->{"usuario_mensajecampana.is_read"} == '1') {
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


/* Se crea un array response que contiene un array de mensajes vacío. */
$response = array();


$response["data"] = array(
    "messages" => array()
);


/* asigna datos y cuenta a un arreglo de respuesta en PHP. */
$response["Data"] = $mensajesRecibidos;
$response["data"] = $mensajesRecibidos;

$response["pos"] = $SkeepRows;
$response["total_count"] = $usuariosCampana->count[0]->{".count"};

