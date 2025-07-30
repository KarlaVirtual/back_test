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
 * Client/GetClientSendFringe
 *
 * Obtención de mensajes de usuario con filtros avanzados
 *
 * Este recurso obtiene los mensajes de usuario aplicando filtros específicos
 * según los parámetros proporcionados, incluyendo fecha, estado de lectura,
 * clientes emisores y receptores, país, y otros criterios.
 *
 * @param object $params : Objeto con los parámetros de filtrado:
 *  - *length* (int): Cantidad máxima de registros a retornar.
 *  - *OrderedItem* (int): Ítem por el cual se ordenan los registros.
 *  - *start* (int): Número de registros a omitir en la consulta.
 *  - *IsGlobal* (string): Indica si la consulta es global ('D' si aplica).
 *  - *Id* (int): Identificador de campaña asociado a los mensajes.
 *  - *State* (int): Estado del mensaje (1 para leído, 0 para no leído).
 *  - *DateFrom* (string): Fecha de inicio para filtrar mensajes.
 *  - *DateTo* (string): Fecha de finalización para filtrar mensajes.
 *
 * @param array $_REQUEST : Datos adicionales recibidos en la solicitud:
 *  - *ClientIdFrom* (int): ID del cliente emisor del mensaje.
 *  - *ClientIdTo* (int): ID del cliente receptor del mensaje.
 *  - *Read* (string): Indica si el mensaje ha sido leído (1 o 0).
 *  - *GlobalId* (int): ID global de referencia del mensaje.
 *
 * @param array $_SESSION : Variables de sesión utilizadas en la consulta:
 *  - *PaisCond* (string): Indica si el usuario está condicionado por país.
 *  - *pais_id* (int): ID del país del usuario en sesión.
 *  - *Global* (string): Indica si el usuario pertenece a un grupo global.
 *  - *mandante* (string): Identificador del mandante del usuario.
 *  - *mandanteLista* (string): Lista de mandantes a los que el usuario tiene acceso.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *result* (string): Contiene el mensaje de error o éxito de la operación.
 *  - *data* (array): Contiene la lista de mensajes filtrados.
 *
 * Objeto en caso de error:
 *
 * "code" => [Código de error],
 * "result" => "[Mensaje de error]",
 * "data" => array(),
 *
 * @throws Exception Si ocurre un error en la ejecución de la consulta o procesamiento de datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Código que asigna parámetros a variables para su posterior uso en procesamiento. */
$MaxRows = $params->length;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->start;
$IsGlobal = $params->IsGlobal;
$IdCampa = $params->Id;
$IsActivate = $params->State;

/* asigna fechas si los parámetros no están vacíos. */
if ($params->DateFrom != "") {
    $DateFrom = $params->DateFrom;
}

if ($params->DateTo != "") {
    $DateTo = $params->DateTo;
}

/* Asignación de valores predeterminados a $MaxRows y $SkeepRows si están vacíos. */
if ($MaxRows == "") {

    $MaxRows = $params->length;
}

if ($SkeepRows == "") {

    $SkeepRows = $params->start;
}


/* obtiene valores de una solicitud y maneja condiciones específicas para ellos. */
$ClientIdFrom = $_REQUEST["ClientIdFrom"];
$ClientIdTo = $_REQUEST["ClientIdTo"];
$Read = ($_REQUEST["Read"] != "1" && $_REQUEST["Read"] != "0") ? '' : $_REQUEST["Read"];
$GlobalId = $_REQUEST["GlobalId"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables si están vacías: $OrderedItem y $MaxRows. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}


/* Se genera un conjunto de reglas basadas en condiciones específicas relacionadas con mensajes. */
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

/* agrega reglas basadas en la activación de mensajes leídos. */
if ($IsActivate != '') {
    if ($IsActivate == 1) {
        array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => $IsActivate, "op" => "eq"));
    }
    if ($IsActivate == 0) {
        array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => $IsActivate, "op" => "eq"));
    }
}


/* Se agregan reglas a un array según el valor de $ClientIdFrom. */
if ($ClientIdFrom != "") {
    if ($ClientIdFrom == '0') {

        array_push($rules, array("field" => "usuario_mensaje.usuto_id", "data" => $ClientIdFrom, "op" => "eq"));

    } else {

        array_push($rules, array("field" => "usufrom.usuario_mandante", "data" => $ClientIdFrom, "op" => "eq"));

    }
}


/* Condicional para agregar reglas basadas en el valor de $ClientIdTo. */
if ($ClientIdTo != "") {
    if ($ClientIdTo == '0') {

        array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => $ClientIdTo, "op" => "eq"));

    } else {
        array_push($rules, array("field" => "usuto.usuario_mandante", "data" => $ClientIdTo, "op" => "eq"));

    }
}


/* agrega condiciones a un arreglo según variables no vacías. */
if ($Read != "") {
    array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => $Read, "op" => "eq"));
}


if ($GlobalId != "") {
    array_push($rules, array("field" => "usuario_mensaje.externo_id", "data" => $GlobalId, "op" => "eq"));
}


/* Agrega una regla que verifica si "usuario_mensaje.tipo" es igual a "STRIPETOP". */
array_push($rules, array("field" => "usuario_mensaje.tipo", "data" => "STRIPETOP", "op" => "eq"));

/*array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => '0', "op" => "in"));*/


/* Condiciona reglas según país y mandante en función de la sesión del usuario. */
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
    /* Condiciona reglas basadas en país y mandante, excluyendo reportes para Colombia. */

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


/* crea un filtro JSON y recupera mensajes de usuario según criterios específicos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$UsuarioMensaje = new UsuarioMensaje();
$usuarios = $UsuarioMensaje->getUsuarioMensajesCustom("pais.pais_nom,usuario_mensaje.*,usufrom.*,usuto.* ", "usuario_mensaje.usumensaje_id", "desc", $SkeepRows, $MaxRows, $json2, true);

/* decodifica un JSON y establece un valor inicial para un contador. */
$usuarios = json_decode($usuarios);

$Incrementador = 1;
foreach ($usuarios->data as $key => $value) {

    if ($IsGlobal == 'D') {

        if ($value->{"usuario_mensaje.usumensaje_id"} = $IdCampa) {

            /* crea un array con incrementador y verifica un valor de usuario. */
            $array = [];
            $array["Incrementador"] = $Incrementador++;
            $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
            if ($value->{"usufrom.usuario_mandante"} == "") {
                $value->{"usufrom.usuario_mandante"} = 0;
            }

            /* asigna valores de un objeto a un arreglo asociativo. */
            $array["GlobalId"] = $value->{"usuario_mensaje.externo_id"};
            $array["DateExpiration"] = $value->{"usuario_mensaje.fecha_expiracion"};
            $array["DateSend"] = $value->{"usuario_mensajecampana.fecha_crea"};

            $array["ClientIdFrom"] = $value->{"usufrom.usuario_mandante"};
            $array["ClientIdTo"] = $value->{"usuto.usuario_mandante"};

            /* Asigna datos de un objeto a un arreglo, dependiendo del estado de lectura. */
            $array["Login"] = $value->{"usuario_mensaje.usufrom_id"};
            $array["FirstName"] = $value->{"usuto.nombres"};
            $array["LastName"] = $value->{"usufrom.apellidos"};
            if ($value->{"usuario_mensaje.is_read"} == 1) {
                $array["State"] = '1';
                $array["DateRead"] = $value->{"usuario_mensaje.fecha_modif"};
            } else {
                /* asigna '0' al estado en el array si la condición no se cumple. */

                $array["State"] = '0';

            }

            /* Asigna propiedades de un objeto a un array y lo agrega a otro array. */
            $array["Title"] = $value->{"usuario_mensaje.msubject"};
            $array["Message"] = $value->{"usuario_mensaje.body"};
            $array["CountrySelect"] = $value->{"pais.pais_nom"};

            array_push($mensajesRecibidos, $array);
        }
    } else if ($IsGlobal == 'D' && $IsActivate == 1) {

        if ($value->{"usuario_mensaje.usumensaje_id"} = $IdCampa) {

            /* inicializa un array y asigna valores de un objeto y un incrementador. */
            $array = [];
            $array["Incrementador"] = $Incrementador++;
            $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
            if ($value->{"usufrom.usuario_mandante"} == "") {
                $value->{"usufrom.usuario_mandante"} = 0;
            }

            /* Asigna valores de un objeto a un array en formato clave-valor. */
            $array["GlobalId"] = $value->{"usuario_mensaje.externo_id"};
            $array["DateExpiration"] = $value->{"usuario_mensaje.fecha_expiracion"};
            $array["DateSend"] = $value->{"usuario_mensajecampana.fecha_crea"};

            $array["ClientIdFrom"] = $value->{"usufrom.usuario_mandante"};
            $array["ClientIdTo"] = $value->{"usuto.usuario_mandante"};

            /* Crea un arreglo con información de usuario y estado de un mensaje. */
            $array["Login"] = $value->{"usuario_mensaje.usufrom_id"};
            $array["FirstName"] = $value->{"usuto.nombres"};
            $array["LastName"] = $value->{"usufrom.apellidos"};
            if ($value->{"usuario_mensaje.is_read"} == 1) {
                $array["State"] = '1';
                $array["DateRead"] = $value->{"usuario_mensaje.fecha_modif"};
            } else {
                /* Asignación del valor '0' al índice "State" del arreglo si se cumple una condición. */

                $array["State"] = '0';

            }

            /* Asigna valores de un objeto a un array y lo agrega a una lista. */
            $array["Title"] = $value->{"usuario_mensaje.msubject"};
            $array["Message"] = $value->{"usuario_mensaje.body"};
            $array["CountrySelect"] = $value->{"pais.pais_nom"};

            array_push($mensajesRecibidos, $array);
        }
    } else if ($IsGlobal == 'D' && $IsActivate == 0) {

        if ($value->{"usuario_mensaje.usumensaje_id"} = $IdCampa) {

            /* Código que inicializa un array con incrementador y propiedades de un objeto. */
            $array = [];
            $array["Incrementador"] = $Incrementador++;
            $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
            if ($value->{"usufrom.usuario_mandante"} == "") {
                $value->{"usufrom.usuario_mandante"} = 0;
            }

            /* Asignación de valores de un objeto a un array asociativo en PHP. */
            $array["GlobalId"] = $value->{"usuario_mensaje.externo_id"};
            $array["DateExpiration"] = $value->{"usuario_mensaje.fecha_expiracion"};
            $array["DateSend"] = $value->{"usuario_mensajecampana.fecha_crea"};

            $array["ClientIdFrom"] = $value->{"usufrom.usuario_mandante"};
            $array["ClientIdTo"] = $value->{"usuto.usuario_mandante"};

            /* Asigna valores a un array basado en propiedades de un objeto y su estado. */
            $array["Login"] = $value->{"usuario_mensaje.usufrom_id"};
            $array["FirstName"] = $value->{"usuto.nombres"};
            $array["LastName"] = $value->{"usufrom.apellidos"};
            if ($value->{"usuario_mensaje.is_read"} == 1) {
                $array["State"] = '1';
                $array["DateRead"] = $value->{"usuario_mensaje.fecha_modif"};
            } else {
                /* Asignación del valor '0' al estado en un arreglo si se cumple cierta condición. */

                $array["State"] = '0';

            }

            /* Asigna datos de mensajes a un array y lo agrega a una lista. */
            $array["Title"] = $value->{"usuario_mensaje.msubject"};
            $array["Message"] = $value->{"usuario_mensaje.body"};
            $array["CountrySelect"] = $value->{"pais.pais_nom"};

            array_push($mensajesRecibidos, $array);
        }
    }
}


/* crea una respuesta estructurada con mensajes recibidos. */
$response = array();
$response["data"] = array(
    "messages" => array()
);

$response["Data"] = $mensajesRecibidos;

/* asigna datos y conteos a un arreglo de respuesta en PHP. */
$response["data"] = $mensajesRecibidos;

$response["pos"] = $SkeepRows;
$response["total_count"] = $usuarios->count[0]->{".count"};

