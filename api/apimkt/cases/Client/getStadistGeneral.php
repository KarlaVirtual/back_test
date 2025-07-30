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
 * Client/getStadistGeneral
 *
 * Obtiene datos de mensajes de usuario según parámetros especificados.
 *
 * Este recurso obtiene información sobre los mensajes no leídos y activos de los usuarios, filtrando por fechas, clientes,
 * secciones y otros parámetros. Los datos se devuelven en un formato adecuado para la visualización en un gráfico.
 *
 * @param object $params : Objeto que contiene los parámetros para la consulta de mensajes. Debe incluir los siguientes atributos:
 * - length (int): Número máximo de registros a devolver.
 * - Section (string): Sección a filtrar en los mensajes (por ejemplo, "messenger", "popup").
 * - start (int): Número de registros a omitir (paginación).
 * - IsGlobal (bool): Indicador si la consulta es global o específica.
 * - DateFrom (string): Fecha de inicio para filtrar los mensajes.
 * - DateTo (string): Fecha de finalización para filtrar los mensajes.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *labels* (array): Fechas de los mensajes no leídos agrupadas.
 *  - *datasets* (array): Datos correspondientes a los mensajes activos por fecha.
 *    - *data* (array): Número de mensajes no leídos para cada fecha.
 *    - *label* (string): Etiqueta del conjunto de datos (en este caso, "Campañas Activas").
 *    - *backgroundColor* (array): Colores de fondo para el gráfico.
 *    - *borderColor* (array): Colores del borde para el gráfico.
 *    - *borderWidth* (int): Grosor del borde para el gráfico.
 *
 * Objeto en caso de error:
 *
 * "HasError" => true,
 * "AlertType" => "danger",
 * "AlertMessage" => "[Mensaje de error]",
 * "ModelErrors" => [],
 * "Data" => array(),
 *
 * @throws Exception Detalles sobre el error ocurrido durante la ejecución de la consulta.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


try {

    /* asigna valores de parámetros a variables para su uso posterior. */
    $MaxRows = $params->length;
    $Section = $params->Section;
    $SkeepRows = $params->start;
    $IsGlobal = $params->IsGlobal;


    if ($params->DateFrom != "") {
        $DateFrom = $params->DateFrom;
    }


    /* asigna valores a variables si están disponibles en los parámetros dados. */
    if ($params->DateTo != "") {
        $DateTo = $params->DateTo;
    }

    if ($MaxRows == "") {

        $MaxRows = $params->length;
    }


    /* asigna valor a $SkeepRows si está vacío y obtiene ClientIdFrom. */
    if ($SkeepRows == "") {

        $SkeepRows = $params->start;
    }

    $ClientIdFrom = $_REQUEST["ClientIdFrom"];

    /* recibe parámetros de solicitud y establece variables para procesar datos. */
    $ClientIdTo = $_REQUEST["ClientIdTo"];
    $Read = ($_REQUEST["Read"] != "1" && $_REQUEST["Read"] != "0") ? '' : $_REQUEST["Read"];
    $GlobalId = $_REQUEST["GlobalId"];

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }


    /* Inicializa $MaxRows a 10 si está vacío y define arrays para mensajes y reglas. */
    if ($MaxRows == "") {
        $MaxRows = 10;
    }

    $mensajes = [];

    $rules = [];


    /* Agrega reglas de fecha para filtrar mensajes en una campaña. */
    if ($DateFrom != "") {
        array_push($rules, array("field" => "usuario_mensajecampana.fecha_crea", "data" => "$DateFrom 00:00:00 ", "op" => "ge"));
    }

    if ($DateTo != "") {
        array_push($rules, array("field" => "usuario_mensajecampana.fecha_crea", "data" => "$DateTo 23:59:00", "op" => "le"));
    }

    /* Agrega reglas de fecha a un arreglo según condiciones de variables de fecha. */
    if ($DateFrom != "" && $DateTo == "") {
        array_push($rules, array("field" => "usuario_mensajecampana.fecha_crea", "data" => "$DateFrom 23:59:00 ", "op" => "le"));
    }
    if ($DateFrom == "" && $DateTo != "") {
        array_push($rules, array("field" => "usuario_mensajecampana.fecha_crea", "data" => "$DateTo 00:00:00 ", "op" => "ge"));
    }


    /* Agrega reglas de filtrado a un array basado en condiciones de usuario y estado. */
    array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => 0, "op" => "eq"));
    array_push($rules, array("field" => "usuario_mensajecampana.parent_id", "data" => 0, "op" => "eq"));
    array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => -1, "op" => "eq"));


    if ($ClientIdFrom != "") {
        if ($ClientIdFrom == '0') {

            array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => $ClientIdFrom, "op" => "eq"));

        } else {

            array_push($rules, array("field" => "usufrom.usuario_mandante", "data" => $ClientIdFrom, "op" => "eq"));

        }
    }


    /* Código que añade reglas de filtrado según el valor de $ClientIdTo. */
    if ($ClientIdTo != "") {
        if ($ClientIdTo == '0') {

            array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => $ClientIdTo, "op" => "eq"));

        } else {
            array_push($rules, array("field" => "usuto.usuario_mandante", "data" => $ClientIdTo, "op" => "eq"));

        }
    }


    /* Condiciona la adición de reglas basadas en ID global y secciones específicas. */
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


    /* agrega reglas de filtrado según condiciones de país y mandante. */
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
        /* gestiona condiciones basadas en país y mandante en sesiones PHP. */

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


    /* Se crea un filtro y se obtiene un conteo de mensajes no leídos por fecha. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);


    $UsuarioMensajecampana = new UsuarioMensajecampana();
    $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom("DATE_FORMAT(usuario_mensajecampana.fecha_crea,'%d-%m-%Y') AS Fechas, count(CASE WHEN  is_read = 0 THEN  1 else NULL end) AS activas", "Fechas", "desc", $SkeepRows, $MaxRows, $json2, true, '', 'Fechas');

    /* Decodifica datos JSON y almacena fechas y mensajes no leídos en arrays. */
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


    /* Se inicializan un array y un objeto estándar en PHP para almacenar respuestas. */
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

    /* asigna una respuesta y señala que no hay errores. */
    $response->data = $respuesta;
    $response->HasError = false;
} catch (Exception $e) {
    /* Captura excepciones y muestra detalles del error ocurrido en el código PHP. */

    print_r($e
    );
}
