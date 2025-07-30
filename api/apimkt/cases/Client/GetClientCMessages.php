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
 * Client/GetClientChat
 *
 * Obtener los mensajes enviados a un usuario.
 *
 * @param string $ClientIdFrom : ID del usuario que envía el mensaje.
 * @param string $ClientIdTo : ID del usuario que recibe el mensaje.
 * @param string $Read : Indica si el mensaje ha sido leído (1) o no leído (0).
 * @param string $GlobalId : ID global del mensaje.
 * @param int $MaxRows : Número máximo de registros a devolver.
 * @param int $OrderedItem : Índice de ordenamiento.
 * @param int $SkeepRows : Número de registros a omitir.
 * @param string $Id : ID específico del mensaje.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *url* (string): URL relacionada con el mensaje.
 *  - *success* (string): Indica el éxito de la operación.
 *
 * Ejemplo de respuesta en caso de error:
 *
 * ```php
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["url"] = "";
 * $response["success"] = "false";
 * ```
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros a variables para manejar filas y elementos ordenados. */
$MaxRows = $params->length;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->start;
$Id = $params->Id;

if ($MaxRows == "") {

    $MaxRows = $params->length;
}


/* asigna un valor a $SkeepRows y obtiene ClientIdFrom de la solicitud. */
if ($SkeepRows == "") {

    $SkeepRows = $params->start;
}

$ClientIdFrom = $_REQUEST["ClientIdFrom"];

/* procesa solicitudes HTTP y valida parámetros como ClientIdTo y Read. */
$ClientIdTo = $_REQUEST["ClientIdTo"];
$Read = ($_REQUEST["Read"] != "1" && $_REQUEST["Read"] != "0") ? '' : $_REQUEST["Read"];
$GlobalId = $_REQUEST["GlobalId"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables vacías con valores predeterminados si no tienen contenido. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}


/* Se inicializan arrays y se añaden reglas basadas en un ID recibido. */
$mensajesRecibidos = [];

$rules = [];


if ($Id != "") {
    array_push($rules, array("field" => "usuario_mensaje.usumensaje_id", "data" => $Id, "op" => "eq"));
}


/* agrega reglas basadas en el valor de ClientIdTo. */
if ($ClientIdTo != "") {
    if ($ClientIdTo == '0') {

        // array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" =>$ClientIdTo, "op" => "eq"));
    } else {
        array_push($rules, array("field" => "usuto.usuario_mandante", "data" => $ClientIdTo, "op" => "eq"));

    }
}


/* Agrega condiciones a un array si las variables no están vacías. */
if ($Read != "") {
    array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => $Read, "op" => "eq"));
}


if ($GlobalId != "") {
    array_push($rules, array("field" => "usuario_mensaje.externo_id", "data" => $GlobalId, "op" => "eq"));
}


/* Agrega una regla al array "rules" para verificar el tipo de mensaje. */
array_push($rules, array("field" => "usuario_mensaje.tipo", "data" => "MENSAJE", "op" => "eq"));

/*array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => '0', "op" => "in"));*/


/* Condiciona reglas según el país y mandante del usuario en una sesión. */
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
    /* Estructura condicional en programación que maneja el caso contrario a la condición previa. */


}


/* Código para filtrar y obtener mensajes de usuario en formato JSON. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$UsuarioMensaje = new UsuarioMensaje();
$usuarios = $UsuarioMensaje->getUsuarioMensajesCustom("usuario_mensaje.*,usufrom.*,usuto.* ", "usuario_mensaje.usumensaje_id", "desc", $SkeepRows, $MaxRows, $json2, true);

/* Convierte un string JSON a un objeto o array en PHP. */
$usuarios = json_decode($usuarios);


foreach ($usuarios->data as $key => $value) {


    /* crea un array de mensajes si el usuario es válido. */
    if ($value->{"usuario_mensaje.usufrom_id"} != 0) {
        $array = [];
        $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
        $array["Date"] = $value->{"usuario_mensaje.fecha_crea"};
        $array["userId"] = $value->{"usuario_mensaje.usufrom_id"};
        $array["UserName"] = $value->{"usufrom.apellidos"};
        $array["TypeUser"] = "U";
        $array["Message"] = $value->{"usuario_mensaje.body"};

        array_push($mensajesRecibidos, $array);
    }


    /* Condicional para crear un array de mensajes si ambos IDs son cero. */
    if ($value->{"usuario_mensaje.usufrom_id"} == 0 && $value->{"usuario_mensaje.usuto"} == 0) {
        $array = [];
        $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
        $array["Date"] = $value->{"usuario_mensaje.fecha_crea"};
        $array["userId"] = $value->{"usuario_mensaje.usufrom_id"};
        $array["UserName"] = $value->{"usufrom.apellidos"};
        $array["TypeUser"] = "O";
        $array["Message"] = $value->{"usuario_mensaje.body"};

        array_push($mensajesRecibidos, $array);
    }

}


/* establece variables a partir de parámetros y maneja la longitud de filas. */
$MaxRows = $params->length;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->start;
$Id = $params->Id;

if ($MaxRows == "") {

    $MaxRows = $params->length;
}


/* asigna un valor a $SkeepRows si está vacío y obtien ClientId. */
if ($SkeepRows == "") {

    $SkeepRows = $params->start;
}

$ClientIdFrom = $_REQUEST["ClientIdFrom"];

/* procesa solicitudes, verificando valores y asignando variables. */
$ClientIdTo = $_REQUEST["ClientIdTo"];
$Read = ($_REQUEST["Read"] != "1" && $_REQUEST["Read"] != "0") ? '' : $_REQUEST["Read"];
$GlobalId = $_REQUEST["GlobalId"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados si las variables están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}


/* agrega una regla si el Id no está vacío. */
$rules = [];


if ($Id != "") {

    array_push($rules, array("field" => "usuario_mensaje.parent_id", "data" => $Id, "op" => "eq"));
}


/* verifica un ID y modifica un arreglo de reglas según su valor. */
if ($ClientIdTo != "") {
    if ($ClientIdTo == '0') {

        // array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" =>$ClientIdTo, "op" => "eq"));
    } else {
        array_push($rules, array("field" => "usuto.usuario_mandante", "data" => $ClientIdTo, "op" => "eq"));

    }
}


/* Agrega condiciones a un arreglo según valores de variables. */
if ($Read != "") {
    array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => $Read, "op" => "eq"));
}


if ($GlobalId != "") {
    array_push($rules, array("field" => "usuario_mensaje.externo_id", "data" => $GlobalId, "op" => "eq"));
}


/* Agrega reglas condicionales a un array basado en el estado de sesión del usuario. */
array_push($rules, array("field" => "usuario_mensaje.tipo", "data" => "MENSAJE", "op" => "eq"));


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

}


/* Se crea un filtro JSON para obtener mensajes de usuarios personalizados. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$UsuarioMensaje = new UsuarioMensaje();
$usuarios = $UsuarioMensaje->getUsuarioMensajesCustom("usuario_mensaje.*,usufrom.*,usuto.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true);

/* Convierte una cadena JSON a un objeto o arreglo en PHP. */
$usuarios = json_decode($usuarios);


foreach ($usuarios->data as $key => $value) {


    /* verifica un usuario y añade un mensaje recibido a un array. */
    if ($value->{"usuario_mensaje.usufrom_id"} != 0) {
        $array = [];
        $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
        $array["Date"] = $value->{"usuario_mensaje.fecha_crea"};
        $array["userId"] = $value->{"usuario_mensaje.usufrom_id"};
        $array["UserName"] = $value->{"usufrom.apellidos"};
        $array["TypeUser"] = "U";
        $array["Message"] = $value->{"usuario_mensaje.body"};

        array_push($mensajesRecibidos, $array);
    }


    /* verifica condiciones y almacena mensajes en un array si se cumple. */
    if ($value->{"usuario_mensaje.usufrom_id"} == 0 && $value->{"usuario_mensaje.usuto"} == 0) {
        $array = [];
        $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
        $array["Date"] = $value->{"usuario_mensaje.fecha_crea"};
        $array["userId"] = $value->{"usuario_mensaje.usufrom_id"};
        $array["UserName"] = $value->{"usufrom.apellidos"};
        $array["TypeUser"] = "O";
        $array["Message"] = $value->{"usuario_mensaje.body"};

        array_push($mensajesRecibidos, $array);
    }

}


/* Código para estructurar una respuesta con mensajes recibidos en un array. */
$response = array();
$response["data"] = array(
    "messages" => array()
);

$response["Data"] = $mensajesRecibidos;

/* asigna mensajes y cuenta total a un array de respuesta. */
$response["data"] = $mensajesRecibidos;

$response["total_count"] = $usuarios->count[0]->{".count"};

