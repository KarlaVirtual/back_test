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
 * Client/GetClientMessages
 *
 * Obtener los mensajes enviados a un usuario con opciones de filtrado y paginación.
 *
 * @param int $MaxRows : Número máximo de registros a devolver. Si está vacío, se establece en 10.
 * @param int $OrderedItem : Parámetro para ordenar los mensajes.
 * @param int $SkeepRows : Número de registros a omitir. Si está vacío, se establece en 0.
 * @param string $IsGlobal : Indica si el mensaje es global ('D') o no.
 * @param string $IdCampa : ID de la campaña.
 * @param string $ClientIdFrom : ID del usuario remitente.
 * @param string $ClientIdTo : ID del usuario destinatario.
 * @param string $Read : Indica si el mensaje ha sido leído ('1') o no leído ('0').
 * @param string $GlobalId : ID global del mensaje.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *url* (string): URL relacionada con el mensaje.
 *  - *success* (string): Indica el éxito de la operación.
 *  - *data* (array): Contiene los mensajes recibidos.
 *      - *messages* (array): Lista de mensajes obtenidos.
 *      - *total_count* (int): Número total de mensajes que cumplen los filtros aplicados.
 *
 *
 * Ejemplo de respuesta en caso de error:
 * ```php
 * $response = [
 *    "HasError" => true,
 *    "AlertType" => "danger",
 *    "AlertMessage" => "Error al obtener los mensajes",
 *    "url" => "",
 *    "success" => "false",
 *    "data" => [],
 *    "total_count" => 0
 * ];
 * ```
 *
 * @throws Exception En caso de error al obtener los mensajes.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
try {


    /* obtiene y verifica parámetros para gestionar la paginación de datos. */
    $MaxRows = $params->length;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->start;
    $IsGlobal = $params->IsGlobal;
    $IdCampa = $params->Id;
    if ($MaxRows == "") {

        $MaxRows = $params->length;
    }


    /* asigna un valor a $SkeepRows si está vacío y obtiene ClientIdFrom. */
    if ($SkeepRows == "") {

        $SkeepRows = $params->start;
    }

    $ClientIdFrom = $_REQUEST["ClientIdFrom"];

    /* establece variables basadas en solicitudes y verifica condiciones para omitir filas. */
    $ClientIdTo = $_REQUEST["ClientIdTo"];
    $Read = ($_REQUEST["Read"] != "1" && $_REQUEST["Read"] != "0") ? '' : $_REQUEST["Read"];
    $GlobalId = $_REQUEST["GlobalId"];


    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }


    /* Asignación de valores predeterminados a variables si están vacías en PHP. */
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 10;
    }


    /* establece condiciones y reglas para filtrar mensajes recibidos según parámetros específicos. */
    $mensajesRecibidos = [];

    $rules = [];

    if ($IsGlobal != "") {

        if ($IsGlobal == 'D') {
            array_push($rules, array("field" => "usuario_mensaje.usumencampana_id", "data" => $IdCampa, "op" => "eq"));
            array_push($rules, array("field" => "usuario_mensaje.usuto_id", "data" => -1, "op" => "ne"));

        }
    }


    /* agrega reglas a un array basado en condiciones del identificador del cliente. */
    if ($ClientIdFrom != "") {
        if ($ClientIdFrom == '0') {

            array_push($rules, array("field" => "usuario_mensaje.usuto_id", "data" => $ClientIdFrom, "op" => "eq"));

        } else {

            array_push($rules, array("field" => "usufrom.usuario_mandante", "data" => $ClientIdFrom, "op" => "eq"));

        }
    }


    /* agrega reglas a un arreglo dependiendo del valor de $ClientIdTo. */
    if ($ClientIdTo != "") {
        if ($ClientIdTo == '0') {

            array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => $ClientIdTo, "op" => "eq"));

        } else {
            array_push($rules, array("field" => "usuto.usuario_mandante", "data" => $ClientIdTo, "op" => "eq"));

        }
    }


    /* Se añaden reglas basadas en condiciones de lectura y ID global. */
    if ($Read != "") {
        array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => $Read, "op" => "eq"));
    }


    if ($GlobalId != "") {
        array_push($rules, array("field" => "usuario_mensaje.externo_id", "data" => $GlobalId, "op" => "eq"));
    }


    /* Agrega una regla al array que verifica si el tipo de mensaje es "MENSAJE". */
    array_push($rules, array("field" => "usuario_mensaje.tipo", "data" => "MENSAJE", "op" => "eq"));

    /*array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => '0', "op" => "in"));*/


    /* establece condiciones para filtrar mensajes según país y mandante. */
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
        /* Condiciona reglas de acceso según país y mandante del usuario en sesión. */

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


    /* Se crea un filtro JSON para obtener mensajes de usuario desde la base de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);


    $UsuarioMensaje = new UsuarioMensaje();
    $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom("pais.pais_nom,usuario_mensaje.*,usufrom.*,usuto.* ", "usuario_mensaje.usumensaje_id", "desc", $SkeepRows, $MaxRows, $json2, true);

    /* Convierte una cadena JSON en un objeto o array de PHP. */
    $usuarios = json_decode($usuarios);


    foreach ($usuarios->data as $key => $value) {

        if ($IsGlobal == 'D') {

            if ($value->{"usuario_mensaje.usumensaje_id"} = $IdCampa) {

                /* Crea un array con identificadores y asigna 0 si el mandante está vacío. */
                $array = [];
                $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
                if ($value->{"usufrom.usuario_mandante"} == "") {
                    $value->{"usufrom.usuario_mandante"} = 0;
                }
                $array["GlobalId"] = $value->{"usuario_mensaje.externo_id"};

                /* asigna fechas y IDs de cliente a un array. */
                $array["DateExpiration"] = $value->{"usuario_mensaje.fecha_expiracion"};
                $array["DateRead"] = $value->{"usuario_mensaje.fecha_modif"};


                $array["ClientIdFrom"] = $value->{"usufrom.usuario_mandante"};
                $array["ClientIdTo"] = $value->{"usuto.usuario_mandante"};

                /* Asigna valores a un array según propiedades de un objeto según condiciones específicas. */
                $array["Login"] = $value->{"usuario_mensaje.usufrom_id"};
                $array["FirstName"] = $value->{"usuto.nombres"};
                $array["LastName"] = $value->{"usufrom.apellidos"};
                if ($value->{"usuario_mensaje.is_read"} == 1) {
                    $array["State"] = '1';

                } else {
                    /* Asignar '0' a "State" en el array si no se cumple una condición. */

                    $array["State"] = '0';

                }

                /* Se almacenan valores en un array y se añaden a una lista de mensajes. */
                $array["Title"] = $value->{"usuario_mensaje.msubject"};
                $array["Message"] = $value->{"usuario_mensaje.body"};
                $array["CountrySelect"] = $value->{"pais.pais_nom"};

                array_push($mensajesRecibidos, $array);
            }
        }
    }


    /* Crea una respuesta estructurada con datos y mensajes recibidos en un arreglo. */
    $response = array();
    $response["data"] = array(
        "messages" => array()
    );

    $response["Data"] = $mensajesRecibidos;

    /* asigna datos y conteos a una respuesta estructurada en un array. */
    $response["data"] = $mensajesRecibidos;

    $response["pos"] = $SkeepRows;
    $response["total_count"] = $usuarios->count[0]->{".count"};
} catch (Exception $e) {
    /* Captura excepciones y muestra detalles del error en formato legible. */

    print_r($e
    );
}