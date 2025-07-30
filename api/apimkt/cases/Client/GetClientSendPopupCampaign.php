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
 * Client/GetClientSendPopupCampaign
 *
 * Obtención de mensajes personalizados para una campaña
 *
 * Este bloque de código gestiona la obtención y organización de mensajes personalizados para una campaña,
 * basándose en diversos parámetros como la activación, la fecha, el país y el cliente. Además, organiza los
 * mensajes en función de su estado de lectura y las reglas globales y locales definidas.
 *
 * @param object $params : Objeto que contiene los parámetros de configuración y filtros de la consulta.
 * - *length* (int): Número máximo de registros a obtener.
 * - *OrderedItem* (int): Indicador del elemento ordenado.
 * - *start* (int): Número de filas a omitir (inicio de paginación).
 * - *IsGlobal* (string): Indica si se aplican reglas globales ('C' para global).
 * - *Id* (string): ID de la campaña.
 * - *IsActivate* (string): Estado de activación ('A' para activo, 'I' para inactivo).
 * - *CountrySelect* (int): ID del país seleccionado.
 * - *CountryId* (int): ID del país.
 * - *DateFrom* (string): Fecha de inicio.
 * - *DateTo* (string): Fecha final.
 *
 * @return array $response : Estructura con los mensajes procesados y otras variables de respuesta.
 * El objeto $response contiene los siguientes atributos:
 *  - *data* (array): Lista de mensajes procesados con su respectiva información.
 *  - *pos* (int): Número de fila inicial para la paginación.
 *  - *total_count* (int): Número total de registros disponibles.
 *
 * Ejemplo de estructura de mensaje:
 * [
 *   "Incrementador" => int,
 *   "Id" => string,
 *   "Title" => string,
 *   "DateExpiration" => string,
 *   "DateFrom" => string,
 *   "CountrySelect" => string,
 *   "Message" => string,
 *   "Description" => string,
 *   "T_Value" => object,
 *   "IsActivate" => string ('A' o 'I')
 * ]
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Variables asignan valores de parámetros para gestionar filas y orden de elementos. */
$MaxRows = $params->length;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->start;
$IsGlobal = $params->IsGlobal;
$IdCampa = $params->Id;
$IsActivate = $params->IsActivate;

/* asigna valores de parámetros y verifica si hay una fecha inicial definida. */
$CountrySelect = $params->CountrySelect;
$CountryId = $params->CountryId;

if ($params->DateFrom != "") {
    $DateFrom = $params->DateFrom;
}


/* Asigna valores a variables basadas en parámetros y condiciones específicas. */
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

/* obtiene datos de una solicitud y maneja condiciones específicas. */
$ClientIdTo = $_REQUEST["ClientIdTo"];
$Read = ($_REQUEST["Read"] != "1" && $_REQUEST["Read"] != "0") ? '' : $_REQUEST["Read"];
$GlobalId = $_REQUEST["GlobalId"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}


/* Variables inicializan un arreglo vacío para mensajes y otro para reglas. */
$mensajesRecibidos = [];

$rules = [];

if ($IsGlobal != "") {

    /* añade reglas basadas en condiciones globales y selección de país. */
    if ($IsGlobal == 'C' && $CountrySelect == "0") {
        array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => 0, "op" => "eq"));
        array_push($rules, array("field" => "usuario_mensajecampana.parent_id", "data" => 0, "op" => "eq"));
        //array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => -1, "op" => "eq"));
    }
    if ($IsGlobal == 'C' && $CountrySelect != 0 && $IdCampa == '') {
        array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => 0, "op" => "eq"));
        array_push($rules, array("field" => "usuario_mensajecampana.parent_id", "data" => 0, "op" => "eq"));
        // array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => -1, "op" => "eq"));
        array_push($rules, array("field" => "usuario_mensajecampana.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }


    /* Condicionales que agregan reglas a un arreglo según variables específicas. */
    if ($IsGlobal == 'C' && $IdCampa != '') {

        array_push($rules, array("field" => "usuario_mensajecampana.usumencampana_id", "data" => $IdCampa, "op" => "eq"));
    }

    if ($IsGlobal == 'C' && $DateFrom != "") {

        //  array_push($rules, array("field" => "usuario_mensajecampana.fecha_expiracion", "data" => "$DateFrom 00:00:00" , "op" => "ge"));
        array_push($rules, array("field" => "usuario_mensajecampana.fecha_envio", "data" => "$DateFrom 00:00:00", "op" => "ge"));
    }

    /* Condiciona la adición de reglas basadas en fechas y estado global. */
    if ($IsGlobal == 'C' && $DateTo != "") {
        // array_push($rules, array("field" => "usuario_mensajecampana.fecha_expiracion", "data" => "$DateTo 00:00:00" , "op" => "le"));
        array_push($rules, array("field" => "usuario_mensajecampana.fecha_envio", "data" => "$DateTo 23:59:00", "op" => "le"));
    }

}

/* agrega reglas basadas en el estado de activación. */
if ($IsActivate != '') {
    if ($IsActivate == 'I') {
        array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => 1, "op" => "eq"));
    }
    if ($IsActivate == 'A') {
        array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => 0, "op" => "eq"));
    }
}


/* Condiciona la adición de reglas según el valor de $ClientIdFrom. */
if ($ClientIdFrom != "") {
    if ($ClientIdFrom == '0') {

        array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => $ClientIdFrom, "op" => "eq"));

    } else {

        array_push($rules, array("field" => "usufrom.usuario_mandante", "data" => $ClientIdFrom, "op" => "eq"));

    }
}


/* Condiciona reglas basadas en el valor de $ClientIdTo para filtrar datos. */
if ($ClientIdTo != "") {
    if ($ClientIdTo == '0') {

        array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => $ClientIdTo, "op" => "eq"));

    } else {
        array_push($rules, array("field" => "usuto.usuario_mandante", "data" => $ClientIdTo, "op" => "eq"));

    }
}


/* Agrega condiciones a un array de reglas si las variables no están vacías. */
if ($Read != "") {
    array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => $Read, "op" => "eq"));
}


if ($GlobalId != "") {
    array_push($rules, array("field" => "usuario_mensajecampana.externo_id", "data" => $GlobalId, "op" => "eq"));
}


/* Agrega una regla que verifica si el tipo de usuario es "BANNERINV". */
array_push($rules, array("field" => "usuario_mensajecampana.tipo", "data" => "BANNERINV", "op" => "eq"));

/*array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => '0', "op" => "in"));*/


/* gestiona condiciones de usuario basadas en país y mandante para reglas. */
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
    /* Condiciona reglas basadas en el país y el mandante del usuario. */

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


/* Se crea un filtro JSON y se obtienen usuarios de campaña personalizados. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$UsuarioMensajecampana = new UsuarioMensajecampana();
$usuariosCampana = $UsuarioMensajecampana->getUsuarioMensajesCustom("pais.pais_nom,usuario_mensajecampana.*,usufrom.*,usuto.* ", "usuario_mensajecampana.usumencampana_id", "desc", $SkeepRows, $MaxRows, $json2, true);

/* convierte una cadena JSON en un objeto PHP y define un incrementador. */
$usuariosCampana = json_decode($usuariosCampana);

$Incrementador = 1;
foreach ($usuariosCampana->data as $key => $value) {

    if ($IsGlobal == 'C' && $IsActivate == "") {


        /* Crea un arreglo asociativo con datos de un usuario y sus atributos específicos. */
        $array = [];
        $array["Incrementador"] = $Incrementador++;
        $array["Id"] = $value->{"usuario_mensajecampana.usumencampana_id"};
        $array["Title"] = $value->{"usuario_mensajecampana.nombre"};
        $array["DateExpiration"] = $value->{"usuario_mensajecampana.fecha_expiracion"};
        $array["DateFrom"] = $value->{"usuario_mensajecampana.fecha_envio"};

        /* asigna valores de un objeto a un array, gestionando el estado de lectura. */
        $array["CountrySelect"] = $value->{"pais.pais_nom"};
        $array["Message"] = $value->{"usuario_mensajecampana.body"};
        $array["Description"] = $value->{"usuario_mensajecampana.descripcion"};
        $array["T_Value"] = json_decode($value->{"usuario_mensajecampana.t_value"});
        if ($value->{"usuario_mensajecampana.is_read"} == '0') {
            $array["IsActivate"] = 'A';
        } elseif ($value->{"usuario_mensajecampana.is_read"} == '1') {

            /* Código añade un elemento al array 'mensajesRecibidos' con estado 'IsActivate' como 'I'. */
            $array["IsActivate"] = 'I';
        }

        array_push($mensajesRecibidos, $array);

    } else if ($IsGlobal == 'C' && $IsActivate == 'A') {


        /* verifica si un mensaje no leído y lo almacena en un array. */
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


        /* Crea un array de mensajes leídos con propiedades específicas y estado de activación. */
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


/* Se crea un arreglo `$response` con un subarreglo para mensajes vacíos. */
$response = array();


$response["data"] = array(
    "messages" => array()
);


/* Asignación de datos a un array de respuesta en un formato estructurado. */
$response["Data"] = $mensajesRecibidos;
$response["data"] = $mensajesRecibidos;

$response["pos"] = $SkeepRows;
$response["total_count"] = $usuariosCampana->count[0]->{".count"};

