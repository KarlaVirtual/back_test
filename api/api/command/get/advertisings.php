<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * Este script obtiene las publicidades activas asociadas a un usuario.
 * 
 * @param object $json Objeto JSON que contiene:
 * @param object $json->session Objeto que contiene información de la sesión del usuario:
 * @param int $json->session->usuario Objeto que contiene información del usuario en sesión.
 * @param string $json->session->sid ID de sesión.
 * @param int $json->rid ID único de la solicitud.
 * 
 * @return array $response Respuesta en formato JSON que incluye:
 * - code: Código de estado de la operación (0 si es exitosa).
 * - rid: ID único de la solicitud.
 * - data: Objeto que contiene:
 *   - subid: ID único generado para la solicitud.
 *   - data: Objeto con:
 *     - advertisings: Array de publicidades con los siguientes campos:
 *       - attachment_id: ID del archivo adjunto.
 *       - url: URL de la publicidad.
 *       - isYouTubeVideo: Booleano que indica si es un video de YouTube.
 *       - isVideoLocal: Booleano que indica si es un video local.
 * 
 * @throws Exception Si ocurre un error durante la consulta de datos o el procesamiento de publicidades.
 */

/* inicializa un arreglo vacío para almacenar mensajes recibidos. */
$mensajesRecibidos = [];

try {

    /* Se crean objetos de usuario y se construye un JSON para reglas de filtrado. */
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);


    $UsuarioPublicidad = new UsuarioPublicidad();


    $json2 = '{"rules" : [{"field" : "usuario_publicidad.usuario_id", "data": "' . $UsuarioMandante->usuarioMandante . '","op":"eq"},{"field" : "usuario_publicidad.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';


    /* Se obtienen publicidades de usuarios y se decodifican en formato JSON. */
    $usuariopublicidades = $UsuarioPublicidad->getUsuarioPublicidadesCustom("usuario_publicidad.*", "usuario_publicidad.usupublicidad_id", "asc", 0, 100, $json2, true);
    $usuariopublicidades = json_decode($usuariopublicidades);


    foreach ($usuariopublicidades->data as $key => $value) {


        /* crea un array asociativo con datos de un usuario de publicidad. */
        $array = [];

        $array["attachment_id"] = $value->{"usuario_publicidad.usupublicidad_id"};
        $array["url"] = ($value->{"usuario_publicidad.valor"});
        $array["isYouTubeVideo"] = false;
        $array["isVideoLocal"] = false;


        /* verifica si una URL es de YouTube o contiene un video local. */
        if (strpos($array["url"], "youtube") !== false) {
            $array["isYouTubeVideo"] = true;

        }

        if (strpos($array["url"], "mp4") !== false) {
            $array["isVideoLocal"] = true;

        }


        /* Añade un nuevo elemento al final del array $mensajesRecibidos. */
        array_push($mensajesRecibidos, $array);

    }


    /* Genera un array de respuesta con datos de sesión y mensajes recibidos. */
    $response = array();


    $response["data"] = array(
        "subid" => "7040" . $json->session->sid . "6",
        "data" => array("advertisings" => $mensajesRecibidos)
    );
} catch (Exception $e) {
    /* Maneja excepciones en PHP, permitiendo que el código continúe sin interrupciones. */


}


/* Crea un array de respuesta con un subid y datos de publicidad recibidos. */
$response = array();

$response["data"] = array(
    "subid" => "7040" . $json->session->sid . "6",
    "data" => array("advertisings" => $mensajesRecibidos)
);

/* Asigna valores a un arreglo de respuesta usando datos JSON. */
$response["code"] = 0;
$response["rid"] = $json->rid;
