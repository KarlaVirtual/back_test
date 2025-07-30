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
use Backend\dto\UsuarioSession;
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
 * Machine/GetAdvertising
 *
 * Este script permite guardar la publicidad de una máquina, actualizar su estado, descripción, 
 * ruta y orden, y enviar mensajes a través de WebSocket para actualizar la información.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param int $params->BetshopId Identificador del punto de venta.
 * @param string $params->IsActivate Estado de la publicidad ('A' para activo, 'I' para inactivo).
 * @param string $params->Name Descripción de la publicidad.
 * @param string $params->Route Ruta o valor asociado a la publicidad.
 * @param int $params->Order Orden de la publicidad.
 * @param int $params->Id Identificador de la publicidad.
 * @param int $params->FromId Identificador del usuario que solicita la publicidad.
 * 
 *
 * @return array $response Respuesta del sistema con los siguientes valores:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 */


/* asigna valores de un objeto $params a variables específicas. */
$params = $params;

$BetshopId = $params->BetshopId;
$IsActivate = ($params->IsActivate == "A") ? "A" : "I";
$Name = $params->Name;
$Route = $params->Route;

/* Asignación de valores a variables basadas en condiciones y parámetros recibidos. */
$Order = ($params->Order == "") ? 0 : $params->Order;
$Id = $params->Id;
$FromId = $params->FromId;


if ($BetShopId == "") {
    $BetShopId = $FromId;
}

if (($Id != "" && is_numeric($Id)) && is_numeric($BetShopId)) {


/**
 * Se crea una instancia de la clase Usuario utilizando el $BetShopId proporcionado.
 */
$Usuario = new Usuario($BetShopId);

/**
 * Se crea una instancia de UsuarioMandante con el usuario creado y su información.
 */
$UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

    /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */


    $UsuarioSession = new UsuarioSession();
    $rules = [];

    /**
     * Agregamos reglas para filtrar las sesiones de usuario.
     */
    array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    /**
     * Obtenemos las sesiones de usuario personalizadas utilizando los filtros definidos.
     */
    $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json2, true);

    $usuarios = json_decode($usuarios);

    $usuariosFinal = [];

    /**
     * Iteramos sobre cada usuario obtenido para enviar el mensaje a través de WebSocket.
     */
    foreach ($usuarios->data as $key => $value) {

        $dataF = str_replace("7040" . $UsuarioToken->getRequestId() . "1", "7040" . $value->{'usuario_session.request_id'} . "1", $data);
        $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $dataF);
        $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});

    }

/**
 * Se crea una instancia de UsuarioPublicidad utilizando el $Id proporcionado.
 */
$UsuarioPublicidad = new UsuarioPublicidad($Id);

/**
 * Comprobamos el estado actual de UsuarioPublicidad y lo actualizamos si es necesario.
 */
if ($UsuarioPublicidad->getEstado() != $IsActivate) {
    $UsuarioPublicidad->setEstado($IsActivate);
}

    /**
     * Comprobamos la descripción actual de UsuarioPublicidad y la actualizamos si es necesario.
     */
    if ($UsuarioPublicidad->getDescripcion() != $Name) {
        $UsuarioPublicidad->setDescripcion($Name);
    }

    // Verifica si el valor actual es diferente del nuevo valor de ruta, y lo actualiza si es necesario
    if ($UsuarioPublicidad->getValor() != $Route) {
        $UsuarioPublicidad->setValor($Route);
    }

    // Verifica si el orden actual es diferente del nuevo orden, y lo actualiza si es necesario
    if ($UsuarioPublicidad->getOrden() != $Order) {
        $UsuarioPublicidad->setOrden($Order);
    }

    // Crea una instancia del DAO para operaciones sobre la base de datos de UsuarioPublicidad
    $UsuarioPublicidadMySqlDAO = new UsuarioPublicidadMySqlDAO();
    $UsuarioPublicidadMySqlDAO->update($UsuarioPublicidad);
    $UsuarioPublicidadMySqlDAO->getTransaction()->commit();

    // Crea un nuevo objeto UsuarioPublicidad
    $UsuarioPublicidad = new UsuarioPublicidad();

    $mensajesRecibidos = [];

    // Define un JSON para la consulta que filtra por usuario_id y estado
    $json2 = '{"rules" : [{"field" : "usuario_publicidad.usuario_id", "data": "' . $BetShopId . '","op":"eq"},{"field" : "usuario_publicidad.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';

    // Obtiene las publicidades de usuario basado en parámetros personalizados de consulta
    $usuariopublicidades = $UsuarioPublicidad->getUsuarioPublicidadesCustom("usuario_publicidad.*", "usuario_publicidad.usupublicidad_id", "asc", 0, 100, $json2, true);
    $usuariopublicidades = json_decode($usuariopublicidades);

    // Itera sobre cada publicidad recibida
    foreach ($usuariopublicidades->data as $key => $value) {

        $array = [];

        $array["attachment_id"] = $value->{"usuario_publicidad.usupublicidad_id"};
        $array["url"] = ($value->{"usuario_publicidad.valor"});
        $array["isYouTubeVideo"] = false;
        $array["isVideoLocal"] = false;

        // Verifica si la URL contiene "youtube" para marcarla como un video de YouTube
        if (strpos($array["url"], "youtube") !== false) {
            $array["isYouTubeVideo"] = true;

        }

        // Verifica si la URL contiene "mp4" para marcarla como un video local
        if (strpos($array["url"], "mp4") !== false) {
            $array["isVideoLocal"] = true;

        }

        // Agrega la información de la publicidad al array de mensajes recibidos
        array_push($mensajesRecibidos, $array);

    }

    $UsuarioMandante = new UsuarioMandante("", $BetShopId, "0");
    /*

    $UsuarioToken2 = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

    $data = array(
        "7040" . $UsuarioToken2->getRequestId() . "6" => array("advertisings" => $mensajesRecibidos)

    );

    print_r($data);
    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo
    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken2->getRequestId(), $data);
    $WebsocketUsuario->sendWSMessage();*/

    /*
    Se crea una nueva sesión de usuario y se inicializa un arreglo de reglas.
    */
    $UsuarioSession = new UsuarioSession();
    $rules = [];

    // Se añaden reglas para filtrar por estado y id de usuario
    array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    /*
    Se obtienen los usuarios personalizados con base en las reglas definidas anteriormente.
    */
    $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

    $usuarios = json_decode($usuarios);

    $usuariosFinal = [];

    /*
    Se recorre la lista de usuarios obtenidos para enviar mensajes Websocket.
    */
    foreach ($usuarios->data as $key => $value) {

        $data = array(
            "7040" . $value->{'usuario_session.request_id'} . "6" => array("advertisings" => $mensajesRecibidos)

        );
        $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
        $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});

    }

    /*
    Se prepara la respuesta final indicando que no hubo error y se establecen los mensajes de alerta.
    */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


} elseif ($BetShopId != "" && is_numeric($BetShopId)) {

    $Usuario = new Usuario($BetShopId);

    $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

    /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */

    /**
     * Se crea una instancia de UsuarioSession.
     */
    $UsuarioSession = new UsuarioSession();
    $rules = [];

    /**
     * Se añaden reglas para filtrar los usuarios de la sesión.
     */
    array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);


    $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json2, true);

    $usuarios = json_decode($usuarios);

    $usuariosFinal = [];

    foreach ($usuarios->data as $key => $value) {

        $dataF = str_replace("7040" . $UsuarioToken->getRequestId() . "1", "7040" . $value->{'usuario_session.request_id'} . "1", $data);
        $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $dataF);
        $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});

    }

    // Se crea una nueva instancia de la clase UsuarioPublicidad
    $UsuarioPublicidad = new UsuarioPublicidad();

    // Se establecen los valores del objeto UsuarioPublicidad
    $UsuarioPublicidad->setUsuarioId($BetShopId); // Establece el ID del usuario
    $UsuarioPublicidad->setEstado($IsActivate); // Establece el estado de la publicidad
    $UsuarioPublicidad->setDescripcion($Name); // Establece la descripción de la publicidad
    $UsuarioPublicidad->setValor($Route); // Establece el valor (ruta) de la publicidad
    $UsuarioPublicidad->setOrden($Order); // Establece el orden de la publicidad

    // Se crea una nueva instancia del acceso a datos para UsuarioPublicidad
    $UsuarioPublicidadMySqlDAO = new UsuarioPublicidadMySqlDAO();
    $UsuarioPublicidadMySqlDAO->insert($UsuarioPublicidad);
    $UsuarioPublicidadMySqlDAO->getTransaction()->commit();


    $UsuarioPublicidad = new UsuarioPublicidad();

    // Inicialización de un array para almacenar mensajes recibidos
    $mensajesRecibidos = [];


    $json2 = '{"rules" : [{"field" : "usuario_publicidad.usuario_id", "data": "$BetShopId","op":"eq"},{"field" : "usuario_publicidad.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';

    // Se obtienen las publicidades de usuario personalizadas utilizando el método getUsuarioPublicidadesCustom
    $usuariopublicidades = $UsuarioPublicidad->getUsuarioPublicidadesCustom("usuario_publicidad.*", "usuario_publicidad.usupublicidad_id", "asc", 0, 100, $json2, true);
    $usuariopublicidades = json_decode($usuariopublicidades);


    foreach ($usuariopublicidades->data as $key => $value) {

        // Se inicializa un array para cada mensaje recibido
        $array = [];

        // Se asignan atributos al array desde el objeto $value
        $array["attachment_id"] = $value->{"usuario_publicidad.usupublicidad_id"}; // Se asigna el ID de la publicidad
        $array["url"] = ($value->{"usuario_publicidad.valor"}); // Se asigna la URL de la publicidad
        $array["isYouTubeVideo"] = false; // Inicializa la verificación de si es un video de YouTube
        $array["isVideoLocal"] = false; // Inicializa la verificación de si es un video local

        // Se verifica si la URL incluye "youtube"
        if (strpos($array["url"], "youtube") !== false) {
            $array["isYouTubeVideo"] = true;

        }

        // Se verifica si la URL incluye "mp4"
        if (strpos($array["url"], "mp4") !== false) {
            $array["isVideoLocal"] = true;

        }

        // Se agrega el array de mensajes recibidos a la lista
        array_push($mensajesRecibidos, $array);

    }

    // Se crea una nueva instancia de UsuarioMandante
    $UsuarioMandante = new UsuarioMandante("", $BetShopId, "0");

    /*$UsuarioToken2 = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

    $data = array(
        "7040" . $UsuarioToken2->getRequestId() . "6" => array("advertisings" => $mensajesRecibidos)

    );

    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo
    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken2->getRequestId(), $data);
    $WebsocketUsuario->sendWSMessage();*/

    $UsuarioSession = new UsuarioSession();
    $rules = [];

    array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    /**
     * Se obtienen los usuarios personalizados de la sesión según el filtro definido anteriormente.
     * Se especifican los campos a seleccionar, así como el orden y el límite de los resultados.
     */
    $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

    $usuarios = json_decode($usuarios);

    $usuariosFinal = [];

    /**
     * Se recorre cada usuario obtenido y se envía un mensaje a través de Websocket con la información correspondiente.
     */
    foreach ($usuarios->data as $key => $value) {

        $data = array(
            "7040" . $value->{'usuario_session.request_id'} . "6" => array("advertisings" => $mensajesRecibidos)

        );
        $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
        $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});

    }

    // Se configura la respuesta del sistema indicando que no ha habido errores.
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

} else {
    /**
     * Inicializa la respuesta de la operación con valores predeterminados.
     */
    $response["HasError"] = true;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}
