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
 * Client/GetClientEmailCampaign
 *
 * Obtener mensajes de usuario en una campaña
 *
 * Este script obtiene mensajes de usuario en una campaña específica, aplicando filtros
 * según los parámetros de entrada y las condiciones de la sesión del usuario.
 *
 * @param object $params : Objeto con los parámetros de entrada.
 * @param int $params->length : Número máximo de filas a recuperar.
 * @param int $params->OrderedItem : Orden de los ítems.
 * @param int $params->start : Número de filas a omitir.
 * @param string $params->IsGlobal : Indica si la búsqueda es global.
 * @param int $params->Id : ID de la campaña.
 * @param string $params->IsActivate : Indica si el mensaje está activo o inactivo.
 *
 * @param string $_REQUEST["ClientIdFrom"] : ID del cliente de origen.
 * @param string $_REQUEST["ClientIdTo"] : ID del cliente de destino.
 * @param string $_REQUEST["Read"] : Indica si el mensaje ha sido leído (0 o 1).
 * @param string $_REQUEST["GlobalId"] : ID global asociado.
 *
 * @param array $_SESSION : Contiene información de sesión del usuario.
 * @param string $_SESSION["PaisCond"] : Indica si el usuario está condicionado por país.
 * @param string $_SESSION["Global"] : Indica si el usuario tiene alcance global.
 * @param string $_SESSION["pais_id"] : ID del país del usuario.
 * @param string $_SESSION["mandante"] : Mandante del usuario.
 * @param string $_SESSION["mandanteLista"] : Lista de mandantes disponibles para el usuario.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 *   - *data* (array): Contiene los mensajes recibidos con los siguientes atributos:
 *     - *Id* (int): ID del mensaje.
 *     - *Title* (string): Título del mensaje.
 *     - *DateExpiration* (string): Fecha de expiración del mensaje.
 *     - *CountrySelect* (string): Nombre del país asociado al mensaje.
 *     - *Message* (string): Descripción del mensaje.
 *     - *IsActivate* (bool): Indica si el mensaje está activo.
 *   - *pos* (int): Posición inicial de los registros consultados.
 *   - *total_count* (int): Total de registros disponibles.
 *
 * @throws Exception Captura excepciones y muestra información sobre el error.
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
try {


    /* inicializa variables a partir de parámetros de entrada. */
    $MaxRows = $params->length;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->start;
    $IsGlobal = $params->IsGlobal;
    $IdCampa = $params->Id;
    $IsActivate = $params->IsActivate;

    /* establece valores predeterminados para MaxRows y SkeepRows si están vacíos. */
    if ($MaxRows == "") {

        $MaxRows = $params->length;
    }

    if ($SkeepRows == "") {

        $SkeepRows = $params->start;
    }


    /* obtiene datos de la solicitud y valida el número de filas a omitir. */
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


    /* configura reglas de filtrado basadas en condiciones globales y específicas. */
    $mensajesRecibidos = [];

    $rules = [];

    if ($IsGlobal != "") {
        if ($IsGlobal == 'C') {
            array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => 0, "op" => "eq"));
            array_push($rules, array("field" => "usuario_mensajecampana.parent_id", "data" => 0, "op" => "eq"));
            array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => -1, "op" => "eq"));
        }
        if ($IsGlobal == 'C' && $IdCampa != '') {
            array_push($rules, array("field" => "usuario_mensajecampana.usumencampana_id", "data" => $IdCampa, "op" => "eq"));
        }
    }

    /* agrega reglas basadas en el estado de activación de un usuario. */
    if ($IsActivate != '') {
        if ($IsActivate == 'I') {
            array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => 1, "op" => "eq"));
        }
        if ($IsActivate == 'A') {
            array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => 0, "op" => "eq"));
        }
    }


    /* verifica el valor de $ClientIdFrom y establece reglas de filtrado. */
    if ($ClientIdFrom != "") {
        if ($ClientIdFrom == '0') {

            array_push($rules, array("field" => "usuario_mensajecampana.usuto_id", "data" => $ClientIdFrom, "op" => "eq"));

        } else {

            array_push($rules, array("field" => "usufrom.usuario_mandante", "data" => $ClientIdFrom, "op" => "eq"));

        }
    }


    /* Condicional que agrega reglas basadas en el valor de $ClientIdTo. */
    if ($ClientIdTo != "") {
        if ($ClientIdTo == '0') {

            array_push($rules, array("field" => "usuario_mensajecampana.usufrom_id", "data" => $ClientIdTo, "op" => "eq"));

        } else {
            array_push($rules, array("field" => "usuto.usuario_mandante", "data" => $ClientIdTo, "op" => "eq"));

        }
    }


    /* Condiciones que añaden reglas a un arreglo si las variables tienen valor. */
    if ($Read != "") {
        array_push($rules, array("field" => "usuario_mensajecampana.is_read", "data" => $Read, "op" => "eq"));
    }


    if ($GlobalId != "") {
        array_push($rules, array("field" => "usuario_mensajecampana.externo_id", "data" => $GlobalId, "op" => "eq"));
    }


    /* Agrega una regla de comparación a un arreglo, verificando el tipo de mensaje. */
    array_push($rules, array("field" => "usuario_mensajecampana.tipo", "data" => "MENSAJE", "op" => "eq"));

    /*array_push($rules, array("field" => "usuario_mensaje.usufrom_id", "data" => '0', "op" => "in"));*/


    /* Condiciona reglas basadas en país y mandante para usuarios específicos en la sesión. */
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
        /* gestiona reglas según país y mandante en una sesión de usuario. */

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


    /* Se crea un filtro JSON para obtener usuarios de una campaña específica. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);


    $UsuarioMensajecampana = new UsuarioMensajecampana();
    $usuariosCampana = $UsuarioMensajecampana->getUsuarioMensajesCustom("pais.pais_nom,usuario_mensajecampana.*,usufrom.*,usuto.* ", "usuario_mensajecampana.usumencampana_id", "desc", $SkeepRows, $MaxRows, $json2, true);

    /* Convierte una cadena JSON a un objeto o arreglo en PHP. */
    $usuariosCampana = json_decode($usuariosCampana);


    foreach ($usuariosCampana->data as $key => $value) {


        /* verifica condiciones y agrega información a un array si se cumplen. */
        if ($IsGlobal == 'C') {

            $array = [];
            $array["Id"] = $value->{"usuario_mensajecampana.usumencampana_id"};
            $array["Title"] = $value->{"usuario_mensajecampana.nombre"};
            $array["DateExpiration"] = $value->{"usuario_mensajecampana.fecha_expiracion"};
            $array["CountrySelect"] = $value->{"pais.pais_nom"};
            $array["Message"] = $value->{"usuario_mensajecampana.descripcion"};
            if ($value->{"usuario_mensajecampana.is_read"} == '0') {
                $array["IsActivate"] = true;
            } elseif ($value->{"usuario_mensajecampana.is_read"} == '1') {
                $array["IsActivate"] = false;
            }

            array_push($mensajesRecibidos, $array);


        } else if ($IsGlobal == 'C' && $IsActivate == 'A') {
            /* Condicional que procesa mensajes no leídos y crea un arreglo con sus detalles. */


            if ($value->{"usuario_mensajecampana.is_read"} == 0) {
                $array = [];
                $array["Id"] = $value->{"usuario_mensajecampana.usumencampana_id"};
                $array["Title"] = $value->{"usuario_mensajecampana.nombre"};
                $array["DateExpiration"] = $value->{"usuario_mensajecampana.fecha_expiracion"};
                $array["CountrySelect"] = $value->{"pais.pais_nom"};
                $array["Message"] = $value->{"usuario_mensajecampana.descripcion"};
                if ($value->{"usuario_mensajecampana.is_read"} == '0') {
                    $array["IsActivate"] = true;
                } elseif ($value->{"usuario_mensajecampana.is_read"} == '1') {
                    $array["IsActivate"] = false;
                }

                array_push($mensajesRecibidos, $array);

            }
        } else if ($IsGlobal == 'C' && $IsActivate == 'I') {
            /* Condicional que genera un arreglo con información de mensajes leídos y no leídos. */


            if ($value->{"usuario_mensajecampana.is_read"} == 1) {
                $array = [];
                $array["Id"] = $value->{"usuario_mensajecampana.usumencampana_id"};
                $array["Title"] = $value->{"usuario_mensajecampana.nombre"};
                $array["DateExpiration"] = $value->{"usuario_mensajecampana.fecha_expiracion"};
                $array["CountrySelect"] = $value->{"pais.pais_nom"};
                $array["Message"] = $value->{"usuario_mensajecampana.descripcion"};
                if ($value->{"usuario_mensajecampana.is_read"} == '0') {
                    $array["IsActivate"] = true;
                } elseif ($value->{"usuario_mensajecampana.is_read"} == '1') {
                    $array["IsActivate"] = false;
                }

                array_push($mensajesRecibidos, $array);
            }
        }

    }


    /* Crea un arreglo de respuesta con mensajes recibidos y estructura definida. */
    $response = array();

    $response["data"] = array(
        "messages" => array()
    );

    $response["Data"] = $mensajesRecibidos;

    /* asigna datos y conteos a un array de respuesta. */
    $response["data"] = $mensajesRecibidos;

    $response["pos"] = $SkeepRows;
    $response["total_count"] = $usuariosCampana->count[0]->{".count"};
} catch (Exception $e) {
    /* Captura excepciones y muestra información sobre el error en formato legible. */

    print_r($e
    );
}