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
 * Client/GetClientMessagescampaign
 *
 * Obtener mensajes de campaña
 *
 * Este recurso obtiene los mensajes asociados a una campaña específica, aplicando filtros según los parámetros proporcionados.
 *
 * @param string $MaxRows : Número máximo de filas a recuperar.
 * @param string $OrderedItem : Identificador del elemento ordenado.
 * @param string $SkeepRows : Número de filas a omitir.
 * @param string $IsGlobal : Indica si la consulta es global ('C' para global, vacío para no global).
 * @param string $IdCampa : Identificador de la campaña.
 * @param string $IsActivate : Estado de activación de los mensajes ('I' para leídos, 'A' para no leídos).
 * @param string $CountrySelect : Identificador del país seleccionado.
 * @param string $DateFrom : Fecha inicial del rango de consulta.
 * @param string $DateTo : Fecha final del rango de consulta.
 * @param string $ClientIdFrom : Identificador del cliente emisor.
 * @param string $ClientIdTo : Identificador del cliente receptor.
 * @param string $Read : Estado de lectura del mensaje ('1' para leído, '0' para no leído).
 * @param string $GlobalId : Identificador global del mensaje.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *result* (string): Contiene el mensaje de error.
 *  - *data* (array): Contiene el resultado de la consulta, incluyendo los mensajes de campaña filtrados.
 *
 * Objeto en caso de error:
 *
 * "code" => [Código de error],
 * "result" => "[Mensaje de error]",
 * "data" => array(),
 *
 * @throws Exception Error en la consulta de mensajes de campaña.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Asignación de parámetros a variables en un script, probablemente para manejo de datos. */
$MaxRows = $params->length;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->start;
$IsGlobal = $params->IsGlobal;
$IdCampa = $params->Id;
$IsActivate = $params->IsActivate;

/* selecciona un país y asigna fechas si están definidas en parámetros. */
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


/* obtiene parámetros de solicitud y maneja condiciones para establecer valores. */
$ClientIdFrom = $_REQUEST["ClientIdFrom"];
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


/* Se inicializan un array vacío para mensajes y un array para reglas. */
$mensajesRecibidos = [];

$rules = [];

if ($IsGlobal != "") {

    /* Condiciona reglas de filtrado basadas en la globalidad y selección de país. */
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


    /* Agrega reglas basadas en condiciones globales y fechas para un conjunto de datos. */
    if ($IsGlobal == 'C' && $IdCampa != '') {

        array_push($rules, array("field" => "usuario_mensajecampana.usumencampana_id", "data" => $IdCampa, "op" => "eq"));
    }
    if ($IsGlobal == 'C' && $DateFrom != "") {

        //  array_push($rules, array("field" => "usuario_mensajecampana.fecha_expiracion", "data" => "$DateFrom 00:00:00" , "op" => "ge"));
        array_push($rules, array("field" => "usuario_mensajecampana.fecha_envio", "data" => "$DateFrom 00:00:00", "op" => "ge"));
    }

    /* Condicional que agrega reglas basadas en la fecha y condición global. */
    if ($IsGlobal == 'C' && $DateTo != "") {
        // array_push($rules, array("field" => "usuario_mensajecampana.fecha_expiracion", "data" => "$DateTo 00:00:00" , "op" => "le"));
        array_push($rules, array("field" => "usuario_mensajecampana.fecha_envio", "data" => "$DateTo 23:59:00", "op" => "le"));
    }
}


/* condiciona reglas según el estado de activación del usuario. */
if ($IsActivate != '') {
    if ($IsActivate == 'I') {
        array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => 1, "op" => "eq"));
    }
    if ($IsActivate == 'A') {
        array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => 0, "op" => "eq"));
    }
}


/* Condicionalmente agrega reglas a un array según el valor de $ClientIdFrom. */
if ($ClientIdFrom != "") {
    if ($ClientIdFrom == '0') {

        array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => $ClientIdFrom, "op" => "eq"));

    } else {

        array_push($rules, array("field" => "usufrom.usuario_mandante", "data" => $ClientIdFrom, "op" => "eq"));

    }
}


/* agrega reglas a un arreglo basado en la condición de $ClientIdTo. */
if ($ClientIdTo != "") {
    if ($ClientIdTo == '0') {

        array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => $ClientIdTo, "op" => "eq"));

    } else {
        array_push($rules, array("field" => "usuto.usuario_mandante", "data" => $ClientIdTo, "op" => "eq"));

    }
}


/* Agrega condiciones a un arreglo si las variables no están vacías. */
if ($Read != "") {
    array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => $Read, "op" => "eq"));
}


if ($GlobalId != "") {
    array_push($rules, array("field" => "usuario_mensajecampana.externo_id", "data" => $GlobalId, "op" => "eq"));
}


/* Agrega una regla para verificar si el tipo de mensaje es igual a "MENSAJE". */
array_push($rules, array("field" => "usuario_mensajecampana.tipo", "data" => "MENSAJE", "op" => "eq"));

/*array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => '0', "op" => "in"));*/


/* establece reglas según condiciones de país y mandante en la sesión. */
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
    /* Condiciones de acceso basadas en país y mandante para usuarios en una campaña. */

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


/* Código PHP que filtra y obtiene mensajes de usuarios en una campaña. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$UsuarioMensajecampana = new UsuarioMensajecampana();
$usuariosCampana = $UsuarioMensajecampana->getUsuarioMensajesCustom("pais.pais_nom,usuario_mensajecampana.*,usufrom.*,usuto.* ", "usuario_mensajecampana.usumencampana_id", "desc", $SkeepRows, $MaxRows, $json2, true);

/* Convierte un JSON de usuarios en un objeto PHP y establece un incrementador. */
$usuariosCampana = json_decode($usuariosCampana);

$Incrementador = 1;

foreach ($usuariosCampana->data as $key => $value) {

    if ($IsGlobal == 'C') {


        /* crea un array con datos de un objeto, incluyendo un incrementador. */
        $array = [];
        $array["Incrementador"] = $Incrementador++;
        $array["Id"] = $value->{"usuario_mensajecampana.usumencampana_id"};
        $array["Title"] = $value->{"usuario_mensajecampana.nombre"};
        $array["DateExpiration"] = $value->{"usuario_mensajecampana.fecha_expiracion"};
        $array["DateFrom"] = $value->{"usuario_mensajecampana.fecha_envio"};

        /* Asigna valores de un objeto a un array y verifica el estado de lectura. */
        $array["CountrySelect"] = $value->{"pais.pais_nom"};
        $array["CountryId"] = $value->{"usuario_mensajecampana.pais_id"};
        $array["Message"] = $value->{"usuario_mensajecampana.body"};
        $array["Description"] = $value->{"usuario_mensajecampana.descripcion"};
        if ($value->{"usuario_mensajecampana.is_read"} == '0') {
            $array["IsActivate"] = 'A';
        } elseif ($value->{
            /* Se verifica si un mensaje ha sido leído a través de una condición. */
            "usuario_mensajecampana.is_read"} == '1') {

            /* Código que asigna un valor a un arreglo y realiza una consulta SQL. */
            $array["IsActivate"] = 'I';
        }


        $sql = "SELECT usuto_id from usuario_mensajecampana where usumencampana_id = $array[Id]";

        $usuario = new Usuario();

        /* establece una transacción para manejar usuarios en una base de datos MySQL. */
        $usuarioMySqlDAO = new UsuarioMySqlDAO();
        $transaccion = $usuarioMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();

        //$usuto = $usuario->execQuery($transaccion, $sql);

        //$usuto = $usuto[0][0];
        $usuto = $value->{"usuario_mensajecampana.usuto_id"};

        $sql2 = "SELECT
        Pagina,
        Pais,
        Fecha,
        Nombre,
        SUM(Usuarios) AS Usuarios,
        SUM(Leidos) AS Leidos
    FROM
        (
        -- Envios a usuarios en específico
        SELECT
            umc.usumencampana_id AS ID_Campana,
            m.nombre AS Pagina,
            p.pais_nom AS Pais,
            DATE(umc.fecha_envio) AS Fecha,
            umc.nombre AS Nombre,
            COUNT(um.usuto_id) AS Usuarios,
            0 AS Leidos
        FROM
            usuario_mensaje um
        JOIN usuario_mensajecampana umc ON
            um.usumencampana_id = umc.usumencampana_id
        JOIN usuario_mandante umt ON
            umt.usumandante_id = um.usuto_id
        JOIN mandante m on
            umt.mandante = m.mandante
        JOIN pais p on
            um.pais_id = p.pais_id
        WHERE
            1 = 1
            AND umc.usumencampana_id=$array[Id]
            AND um.tipo = 'MENSAJE'
            # AND um.pais_id IN (2, 33, 46, 60, 66, 94, 146, 173)
            # AND umt.mandante IN (0, 8, 13, 14)
            AND umc.usuto_id = -1
            -- Para enviarle mensajes solo a los usuarios seleccionados
    UNION
        -- Leidos de usuarios en específico
        SELECT
            umc.usumencampana_id AS ID_Campana,
            m.nombre AS Pagina,
            p.pais_nom AS Pais,
            DATE(umc.fecha_modif) AS Fecha,
            umc.nombre AS Nombre,
            0 AS Usuarios,
            SUM(um.is_read) AS Leidos
        FROM
            usuario_mensaje um
        JOIN usuario_mensajecampana umc ON
            um.usumencampana_id = umc.usumencampana_id
        JOIN usuario_mandante umt ON
            umt.usumandante_id = um.usuto_id
        JOIN mandante m on
            umt.mandante = m.mandante
        JOIN pais p on
            um.pais_id = p.pais_id
        WHERE
            1 = 1
            AND umc.usumencampana_id=$array[Id]
            AND um.tipo = 'MENSAJE'
            # AND um.pais_id IN (2, 33, 46, 60, 66, 94, 146, 173)
            # AND umt.mandante IN (0, 8, 13, 14)
            AND umc.usuto_id = -1
            -- Para enviarle mensajes solo a los usuarios seleccionados
        ) x
    GROUP BY
        ID_Campana";


        /* Esto consulta datos de usuario y asigna valores según una condición específica. */
        $data = $usuario->execQuery($transaccion, $sql2);

        $valorLeidos = $data[0]['.Leidos'];
        $valorEnviados = $data[0][".Usuarios"];

        if ($usuto == "-1") {
            $array["TotalSent"] = $valorEnviados;
            $array["AmountRead"] = $valorLeidos;
        } else if ($usuto == "0") {
            /* Condición que asigna valores a un arreglo si $usuto es igual a "0". */

            $array["TotalSent"] = "All";
            $array["AmountRead"] = $valorLeidos;
        }


        /* Añade un array al final del array $mensajesRecibidos en PHP. */
        array_push($mensajesRecibidos, $array);


    } else if ($IsGlobal == 'C' && $IsActivate == 'A') {


        /* verifica si un mensaje no leído y lo agrega a un array. */
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
            if ($value->{"usuario_mensajecampana.is_read"} == '0') {
                $array["IsActivate"] = 'A';
            } elseif ($value->{"usuario_mensajecampana.is_read"} == '1') {
                $array["IsActivate"] = 'I';
            }

            array_push($mensajesRecibidos, $array);

        }
    } else if ($IsGlobal == 'C' && $IsActivate == 'I') {


        /* Se crea un array con datos de mensajes leídos y su estado de activación. */
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
            if ($value->{"usuario_mensajecampana.is_read"} == '0') {
                $array["IsActivate"] = 'A';
            } elseif ($value->{"usuario_mensajecampana.is_read"} == '1') {
                $array["IsActivate"] = 'I';
            }

            array_push($mensajesRecibidos, $array);
        }
    }

}


/* crea un arreglo de respuesta para almacenar mensajes recibidos. */
$response = array();

$response["data"] = array(
    "messages" => array()
);

$response["Data"] = $mensajesRecibidos;

/* asigna datos y conteos a un arreglo de respuesta en PHP. */
$response["data"] = $mensajesRecibidos;

$response["pos"] = $SkeepRows;
$response["total_count"] = $usuariosCampana->count[0]->{".count"};

