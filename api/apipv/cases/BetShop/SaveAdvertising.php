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
 * Guarda la publicidad de un punto de venta basándose en los datos proporcionados.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param int $params ->BetshopId Identificador del punto de venta.
 * @param string $params ->IsActivate Estado de la publicidad ('A' para activo, 'I' para inactivo).
 * @param string $params ->Name Nombre o descripción de la publicidad.
 * @param string $params ->Route Ruta o valor asociado a la publicidad.
 * @param int $params ->Order Orden de la publicidad. Por defecto, 0.
 * @param int|null $params ->Id Identificador de la publicidad (opcional para actualizaciones).
 * @param int|null $params ->FromId Identificador del usuario que solicita la operación.
 *
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - Data (array): Información de las publicidades, incluyendo:
 *                             - attachment_id (int): Identificador de la publicidad.
 *                             - url (string): URL asociada a la publicidad.
 *                             - isYouTubeVideo (bool): Indica si la URL es un video de YouTube.
 *                             - isVideoLocal (bool): Indica si la URL es un video local.
 *
 * @throws Exception Si ocurre un error al guardar o actualizar la publicidad.
 */


/* Asigna valores de parámetros a variables, determinando el estado de activación. */
$params = $params;

$BetshopId = $params->BetshopId;
$IsActivate = ($params->IsActivate == "A") ? "A" : "I";
$Name = $params->Name;
$Route = $params->Route;

/* Asignación de variables y manejo de condiciones para establecer valores en PHP. */
$Order = ($params->Order == "") ? 0 : $params->Order;
$Id = $params->Id;
$FromId = $params->FromId;


if ($BetShopId == "") {
    $BetShopId = $FromId;
}

if (($Id != "" && is_numeric($Id)) && is_numeric($BetShopId)) {

    /* Actualiza el estado y la descripción de un objeto UsuarioPublicidad según condiciones. */
    $UsuarioPublicidad = new UsuarioPublicidad($Id);

    if ($UsuarioPublicidad->getEstado() != $IsActivate) {
        $UsuarioPublicidad->setEstado($IsActivate);
    }

    if ($UsuarioPublicidad->getDescripcion() != $Name) {
        $UsuarioPublicidad->setDescripcion($Name);
    }


    /* Actualiza valor y orden en UsuarioPublicidad si son diferentes a los proporcionados. */
    if ($UsuarioPublicidad->getValor() != $Route) {
        $UsuarioPublicidad->setValor($Route);
    }

    if ($UsuarioPublicidad->getOrden() != $Order) {
        $UsuarioPublicidad->setOrden($Order);
    }


    /* actualiza un registro de usuario y confirma una transacción en MySQL. */
    $UsuarioPublicidadMySqlDAO = new UsuarioPublicidadMySqlDAO();
    $UsuarioPublicidadMySqlDAO->update($UsuarioPublicidad);
    $UsuarioPublicidadMySqlDAO->getTransaction()->commit();


    $UsuarioPublicidad = new UsuarioPublicidad();


    /* Se asigna un arreglo vacío y se obtienen publicidades de usuario en formato JSON. */
    $mensajesRecibidos = [];


    $json2 = '{"rules" : [] ,"groupOp" : "AND"}';

    $usuariopublicidades = $UsuarioPublicidad->getUsuarioPublicidadesCustom("usuario_publicidad.*", "usuario_publicidad.usupublicidad_id", "asc", 0, 100, $json2, true);

    /* Convierte una cadena JSON en un objeto PHP utilizando la función json_decode. */
    $usuariopublicidades = json_decode($usuariopublicidades);


    foreach ($usuariopublicidades->data as $key => $value) {


        /* Se crea un array con información sobre un usuario y su publicidad. */
        $array = [];

        $array["attachment_id"] = $value->{"usuario_publicidad.usupublicidad_id"};
        $array["url"] = ($value->{"usuario_publicidad.valor"});
        $array["isYouTubeVideo"] = false;
        $array["isVideoLocal"] = false;


        /* verifica si una URL es de YouTube o un video local. */
        if (strpos($array["url"], "youtube") !== false) {
            $array["isYouTubeVideo"] = true;

        }

        if (strpos($array["url"], "mp4") !== false) {
            $array["isVideoLocal"] = true;

        }


        /* añade un nuevo elemento al final del array $mensajesRecibidos. */
        array_push($mensajesRecibidos, $array);

    }


    /* Se crea un usuario y se estructura un array para mensajes recibidos. */
    $UsuarioMandante = new UsuarioMandante(45340);

    $UsuarioToken2 = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

    $data = array(
        "7040" . $UsuarioToken2->getRequestId() . "6" => array("advertisings" => $mensajesRecibidos)

    );

    /* imprime datos y envía un mensaje a través de WebSocket al usuario. */
    print_r($data);

    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
    //$WebsocketUsuario = new WebsocketUsuario($UsuarioToken2->getRequestId(), $data);
    //$WebsocketUsuario->sendWSMessage();

    $response["HasError"] = false;

    /* inicializa un array de respuesta con estado 'exitoso' y sin errores. */
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


} elseif ($BetShopId != "" && is_numeric($BetShopId)) {

    /* Crea un objeto "UsuarioPublicidad" y establece sus propiedades con valores dados. */
    $UsuarioPublicidad = new UsuarioPublicidad();

    $UsuarioPublicidad->setUsuarioId($BetShopId);
    $UsuarioPublicidad->setEstado($IsActivate);
    $UsuarioPublicidad->setDescripcion($Name);
    $UsuarioPublicidad->setValor($Route);

    /* Se guarda un objeto UsuarioPublicidad en la base de datos y se confirma la transacción. */
    $UsuarioPublicidad->setOrden($Order);

    $UsuarioPublicidadMySqlDAO = new UsuarioPublicidadMySqlDAO();
    $UsuarioPublicidadMySqlDAO->insert($UsuarioPublicidad);
    $UsuarioPublicidadMySqlDAO->getTransaction()->commit();


    $UsuarioPublicidad = new UsuarioPublicidad();


    /* Crea una consulta para obtener usuarios de publicidad basándose en reglas JSON definidas. */
    $mensajesRecibidos = [];


    $json2 = '{"rules" : [{"field" : "usuario_publicidad.usuario_id", "data": "$BetShopId","op":"eq"},{"field" : "usuario_publicidad.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';

    $usuariopublicidades = $UsuarioPublicidad->getUsuarioPublicidadesCustom("usuario_publicidad.*", "usuario_publicidad.usupublicidad_id", "asc", 0, 100, $json2, true);

    /* Convierte una cadena JSON en un objeto o arreglo PHP para su uso. */
    $usuariopublicidades = json_decode($usuariopublicidades);


    foreach ($usuariopublicidades->data as $key => $value) {


        /* crea un array con datos de publicidad de usuario. */
        $array = [];

        $array["attachment_id"] = $value->{"usuario_publicidad.usupublicidad_id"};
        $array["url"] = ($value->{"usuario_publicidad.valor"});
        $array["isYouTubeVideo"] = false;
        $array["isVideoLocal"] = false;


        /* Verifica si una URL es de YouTube o un video local en formato MP4. */
        if (strpos($array["url"], "youtube") !== false) {
            $array["isYouTubeVideo"] = true;

        }

        if (strpos($array["url"], "mp4") !== false) {
            $array["isVideoLocal"] = true;

        }


        /* Agrega un nuevo elemento al final del arreglo $mensajesRecibidos. */
        array_push($mensajesRecibidos, $array);

    }


    /* Se crea un objeto UsuarioMandante y se prepara datos para un usuario token. */
    $UsuarioMandante = new UsuarioMandante(45340);

    $UsuarioToken2 = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

    $data = array(
        "7040" . $UsuarioToken2->getRequestId() . "6" => array("advertisings" => $mensajesRecibidos)

    );

    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */

    /* Crea un objeto WebsocketUsuario y envía un mensaje, luego establece una respuesta exitosa. */
    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken2->getRequestId(), $data);
    $WebsocketUsuario->sendWSMessage();

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";

    /* Se inicializa un array vacío para almacenar errores del modelo en la respuesta. */
    $response["ModelErrors"] = [];

} else {
    /* maneja una respuesta sin errores, configurando alertas vacías y sin problemas. */

    $response["HasError"] = true;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}
