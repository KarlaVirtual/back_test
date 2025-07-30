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
 * Client/GetClientSendInvasiveBanner
 *
 * Gestión de mensajes en campañas
 *
 * Este script obtiene y filtra mensajes de usuario dentro de una campaña específica.
 * Se encarga de manejar parámetros de paginación, estados de lectura, y condiciones
 * específicas para la segmentación de mensajes.
 *
 * @param int $MaxRows : Número máximo de filas a obtener.
 * @param int $OrderedItem : Indica el orden de los ítems.
 * @param int $SkeepRows : Número de filas a omitir en la paginación.
 * @param string $IsGlobal : Indica si la consulta es global ('D' para campañas).
 * @param int $IdCampa : Identificador de la campaña.
 * @param int $IsActivate : Estado de activación (1 para activos, 0 para inactivos).
 * @param string $DateFrom : Fecha de inicio para el filtro.
 * @param string $DateTo : Fecha de finalización para el filtro.
 * @param int $ClientIdFrom : Identificador del cliente emisor.
 * @param int $ClientIdTo : Identificador del cliente receptor.
 * @param string $Read : Indica si el mensaje ha sido leído ('1' leído, '0' no leído).
 * @param int $GlobalId : Identificador global del mensaje.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *data* (array): Contiene los mensajes recibidos tras aplicar los filtros.
 *  - *pos* (int): Posición en la paginación de los resultados.
 *  - *total_count* (int): Cantidad total de registros obtenidos.
 *
 * Objeto en caso de error:
 *
 * "code" => [Código de error],
 * "result" => "[Mensaje de error]",
 * "data" => array(),
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Asignación de parámetros para gestionar filas y propiedades de un ítem en una campaña. */
$MaxRows = $params->length;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->start;
$IsGlobal = $params->IsGlobal;
$IdCampa = $params->Id;
$IsActivate = $params->State;

/* asigna valores de fechas si no están vacíos. */
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


/* obtiene parámetros de una solicitud y controla valores específicos. */
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


/* Se configuran reglas de filtrado para mensajes según condiciones específicas. */
$mensajesRecibidos = [];

$rules = [];

if ($IsGlobal != "") {

    if ($IsGlobal == 'D') {
        array_push($rules, array("field" => "usuario_mensaje.usumencampana_id", "data" => $IdCampa, "op" => "eq"));
        array_push($rules, array("field" => "usuario_mensaje.usuto_id", "data" => -1, "op" => "ne"));

    }
    if ($IsGlobal == 'D' && $DateFrom != "") {

        array_push($rules, array("field" => "usuario_mensaje.fecha_modif", "data" => "$DateFrom 00:00:00", "op" => "ge"));
        array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => 1, "op" => "eq"));
    }
    if ($IsGlobal == 'D' && $DateTo != "") {
        array_push($rules, array("field" => "usuario_mensaje.fecha_modif", "data" => "$DateTo 23:59:00", "op" => "le"));

    }
}

/* Se añaden reglas según el estado de activación en un array. */
if ($IsActivate != '') {
    if ($IsActivate == 1) {
        array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => $IsActivate, "op" => "eq"));
    }
    if ($IsActivate == 0) {
        array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => $IsActivate, "op" => "eq"));
    }
}


/* Se añaden reglas basadas en el valor de $ClientIdFrom. */
if ($ClientIdFrom != "") {
    if ($ClientIdFrom == '0') {

        array_push($rules, array("field" => "usuario_mensaje.usuto_id", "data" => $ClientIdFrom, "op" => "eq"));

    } else {

        array_push($rules, array("field" => "usufrom.usuario_mandante", "data" => $ClientIdFrom, "op" => "eq"));

    }
}


/* agrega reglas basadas en el valor de $ClientIdTo. */
if ($ClientIdTo != "") {
    if ($ClientIdTo == '0') {

        array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => $ClientIdTo, "op" => "eq"));

    } else {
        array_push($rules, array("field" => "usuto.usuario_mandante", "data" => $ClientIdTo, "op" => "eq"));

    }
}


/* Agrega reglas a un arreglo si las variables no están vacías. */
if ($Read != "") {
    array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => $Read, "op" => "eq"));
}


if ($GlobalId != "") {
    array_push($rules, array("field" => "usuario_mensaje.externo_id", "data" => $GlobalId, "op" => "eq"));
}


/* Añade una regla que verifica si "tipo" de "usuario_mensaje" es igual a "MESSAGEINV". */
array_push($rules, array("field" => "usuario_mensaje.tipo", "data" => "MESSAGEINV", "op" => "eq"));

/*array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => '0', "op" => "in"));*/


/* Condiciona reglas según país y mandante, excluyendo reportes específicos de Colombia. */
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
            array_push($rules, array("field" => "usuario_mensaje.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }// Inactivamos reportes para el país Colombia
    array_push($rules, array("field" => "usuario_mensaje.pais_id", "data" => "1", "op" => "ne"));

} else {
    /* gestiona reglas de acceso según país y mandante de usuario. */

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuto.pais_id", "data" => $_SESSION["pais_id"] . '', "op" => "in"));
    }
// Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuto.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuto.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }
    // Inactivamos reportes para el país Colombia
    array_push($rules, array("field" => "usuto.pais_id", "data" => "1", "op" => "ne"));

}


/* Se crea un filtro JSON para obtener mensajes de usuario personalizados de una base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$UsuarioMensaje = new UsuarioMensaje();
$usuarios = $UsuarioMensaje->getUsuarioMensajesCustom("pais.pais_nom,usuario_mensaje.*,usufrom.*,usuto.* ", "usuario_mensaje.usumensaje_id", "desc", $SkeepRows, $MaxRows, $json2, true);

/* convierte datos JSON en un objeto PHP y establece un contador. */
$usuarios = json_decode($usuarios);


$Incrementador = 1;
foreach ($usuarios->data as $key => $value) {

    if ($IsGlobal == 'D') {

        if ($value->{"usuario_mensaje.usumensaje_id"} = $IdCampa) {

            /* Código que inicializa un array con valores incrementales y maneja condiciones de usuario. */
            $array = [];
            $array["Incrementador"] = $Incrementador++;
            $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
            if ($value->{"usufrom.usuario_mandante"} == "") {
                $value->{"usufrom.usuario_mandante"} = 0;
            }

            /* Asigna valores de un objeto a un arreglo asociativo en PHP. */
            $array["GlobalId"] = $value->{"usuario_mensaje.externo_id"};
            $array["DateExpiration"] = $value->{"usuario_mensaje.fecha_expiracion"};
            $array["DateSend"] = $value->{"usuario_mensajecampana.fecha_crea"};

            $array["ClientIdFrom"] = $value->{"usufrom.usuario_mandante"};
            $array["ClientIdTo"] = $value->{"usuto.usuario_mandante"};

            /* Asigna valores a un array según condiciones de un objeto y sus propiedades. */
            $array["Login"] = $value->{"usuario_mensaje.usufrom_id"};
            $array["FirstName"] = $value->{"usuto.nombres"};
            $array["LastName"] = $value->{"usufrom.apellidos"};
            if ($value->{"usuario_mensaje.is_read"} == 1) {
                $array["State"] = '1';
                $array["DateRead"] = $value->{"usuario_mensaje.fecha_modif"};
            } else {
                /* asigna '0' al estado si no se cumple una condición. */

                $array["State"] = '0';

            }

            /* asigna valores a un array y lo agrega a otro array. */
            $array["Title"] = $value->{"usuario_mensaje.msubject"};
            $array["Message"] = $value->{"usuario_mensaje.body"};
            $array["CountrySelect"] = $value->{"pais.pais_nom"};

            array_push($mensajesRecibidos, $array);
        }
    } else if ($IsGlobal == 'D' && $IsActivate == 1) {

        if ($value->{"usuario_mensaje.usumensaje_id"} = $IdCampa) {

            /* Se crea un array con un incrementador y un ID de usuario mensajero. */
            $array = [];
            $array["Incrementador"] = $Incrementador++;
            $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
            if ($value->{"usufrom.usuario_mandante"} == "") {
                $value->{"usufrom.usuario_mandante"} = 0;
            }

            /* asigna valores de un objeto a un arreglo asociativo en PHP. */
            $array["GlobalId"] = $value->{"usuario_mensaje.externo_id"};
            $array["DateExpiration"] = $value->{"usuario_mensaje.fecha_expiracion"};
            $array["DateSend"] = $value->{"usuario_mensajecampana.fecha_crea"};

            $array["ClientIdFrom"] = $value->{"usufrom.usuario_mandante"};
            $array["ClientIdTo"] = $value->{"usuto.usuario_mandante"};

            /* Asignación de valores a un array según condiciones de un objeto recibido. */
            $array["Login"] = $value->{"usuario_mensaje.usufrom_id"};
            $array["FirstName"] = $value->{"usuto.nombres"};
            $array["LastName"] = $value->{"usufrom.apellidos"};
            if ($value->{"usuario_mensaje.is_read"} == 1) {
                $array["State"] = '1';
                $array["DateRead"] = $value->{"usuario_mensaje.fecha_modif"};
            } else {
                /* asigna '0' a "State" si no se cumple la condición previa. */

                $array["State"] = '0';

            }

            /* asigna valores a un array y lo agrega a otro array. */
            $array["Title"] = $value->{"usuario_mensaje.msubject"};
            $array["Message"] = $value->{"usuario_mensaje.body"};
            $array["CountrySelect"] = $value->{"pais.pais_nom"};

            array_push($mensajesRecibidos, $array);
        }
    } else if ($IsGlobal == 'D' && $IsActivate == 0) {

        if ($value->{"usuario_mensaje.usumensaje_id"} = $IdCampa) {

            /* Se crea un array con un incrementador y un ID, manejando valores vacíos. */
            $array = [];
            $array["Incrementador"] = $Incrementador++;
            $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
            if ($value->{"usufrom.usuario_mandante"} == "") {
                $value->{"usufrom.usuario_mandante"} = 0;
            }

            /* Asignación de valores a un arreglo desde un objeto, utilizando propiedades específicas. */
            $array["GlobalId"] = $value->{"usuario_mensaje.externo_id"};
            $array["DateExpiration"] = $value->{"usuario_mensaje.fecha_expiracion"};
            $array["DateSend"] = $value->{"usuario_mensajecampana.fecha_crea"};

            $array["ClientIdFrom"] = $value->{"usufrom.usuario_mandante"};
            $array["ClientIdTo"] = $value->{"usuto.usuario_mandante"};

            /* Extrae y organiza datos de un objeto en un array según condiciones específicas. */
            $array["Login"] = $value->{"usuario_mensaje.usufrom_id"};
            $array["FirstName"] = $value->{"usuto.nombres"};
            $array["LastName"] = $value->{"usufrom.apellidos"};
            if ($value->{"usuario_mensaje.is_read"} == 1) {
                $array["State"] = '1';
                $array["DateRead"] = $value->{"usuario_mensaje.fecha_modif"};
            } else {
                /* Asigna el valor '0' a "State" si la condición del if no se cumple. */

                $array["State"] = '0';

            }

            /* Se asignan valores de un objeto a un array y se añade a otro array. */
            $array["Title"] = $value->{"usuario_mensaje.msubject"};
            $array["Message"] = $value->{"usuario_mensaje.body"};
            $array["CountrySelect"] = $value->{"pais.pais_nom"};

            array_push($mensajesRecibidos, $array);
        }
    }
}


/* crea una respuesta estructurada con mensajes recibidos en un array. */
$response = array();
$response["data"] = array(
    "messages" => array()
);

$response["Data"] = $mensajesRecibidos;

/* asigna datos, posición y conteo total a una respuesta estructurada. */
$response["data"] = $mensajesRecibidos;

$response["pos"] = $SkeepRows;
$response["total_count"] = $usuarios->count[0]->{".count"};

