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
 *
 * command/user_messages
 *
 * Procesamiento de mensajes de usuario
 *
 * Este recurso permite obtener los mensajes enviados y recibidos por un usuario mandante.
 * Se filtran los mensajes según el tipo especificado y el usuario correspondiente.
 *
 * @param string $usuario : Nombre de usuario en la sesión activa.
 * @param string $type : Tipo de consulta (0 para mensajes recibidos, otro valor para enviados).
 * @param int $count : Número máximo de registros a recuperar (por defecto 100, se ajusta a 20 en la ejecución).
 * @param int $start : Número de registros a omitir en la consulta (por defecto 0).
 *
 * @return object  $response es un array con los siguientes atributos:
 *  - *code* (int): Código de éxito o error de la operación.
 *  - *data* (array): Contiene los mensajes obtenidos.
 *      - *messages* (array): Lista de mensajes procesados.
 *          - *body* (string): Contenido del mensaje.
 *          - *checked* (bool): Indica si el mensaje ha sido leído (true/false).
 *          - *open* (bool): Estado del mensaje en la interfaz (false por defecto).
 *          - *date* (string): Fecha de envío o creación del mensaje.
 *          - *id* (int): Identificador único del mensaje.
 *          - *subject* (string): Asunto del mensaje.
 *          - *thread_id* (int|null): Identificador del hilo del mensaje.
 *          - *global* (bool): Indica si el mensaje es global (true si el destinatario es 0).
 *  - *total_count* (int): Cantidad total de mensajes encontrados.
 *  - *rid* (string): Identificador de la solicitud.
 *  - *pos* (int): Número de registros omitidos en la consulta.
 *
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


if (true) {


    /* extrae parámetros de un objeto JSON para usarlos en una consulta. */
    $MaxRows = $json->params->count;
    $SkeepRows = $json->params->start;

    $type = $json->params->where->type;

    $UsuarioMandante = $UsuarioMandanteSite;

    /* Se inicializa un objeto Usuario y se definen valores por defecto para variables. */
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* define el número máximo de filas y crea un arreglo vacío. */
    if ($MaxRows == "") {
        $MaxRows = 100;
    }
    $MaxRows = 20;

    $mensajesEnviados = [];

    /* Código que construye una consulta JSON según el tipo de mensaje. */
    $mensajesRecibidos = [];

    if ($type == 0) {
        $json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.usufrom_id", "data": "0","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "MENSAJE","op":"eq"}] ,"groupOp" : "AND"}';

    } else {
        /* Genera un objeto JSON con reglas de filtrado para mensajes de usuario. */

        $json2 = '{"rules" : [{"field" : "usuario_mensaje.usufrom_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "MENSAJE","op":"eq"}] ,"groupOp" : "AND"}';

    }

    if (true) {


        /* obtiene y decodifica mensajes de usuario de una campaña específica. */
        $UsuarioMensaje = new UsuarioMensaje();
//$usuarios = $UsuarioMensaje->getUsuarioMensajesCustomCampana(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "desc", $SkeepRows, $MaxRows, $json2, true);
        $usuarios = $UsuarioMensaje->getUsuarioMensajesCustomCampana($UsuarioMandante->getUsuarioMandante(), $UsuarioMandante->getPaisId(), $Usuario->fechaCrea, $Usuario->mandante, $UsuarioMandante->usumandanteId);


        $usuarios = json_decode($usuarios);

        /*foreach ($usuarios->data as $key => $value) {

            $array = [];

            $array["body"] = $value->{"usuario_mensaje.body"};
            $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
            $array["open"] = false;
            $array["date"] = $value->{"usuario_mensaje.fecha_crea"};
            $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
            $array["subject"] = $value->{"usuario_mensaje.msubject"};
            $array["thread_id"] = $value->{"usuario_mensaje.parent_id"};

            array_push($mensajesRecibidos, $array);

        }*/

        foreach ($usuarios->data as $key => $value) {

            /* Verifica y organiza datos de mensajes en un array si cumplen ciertas condiciones. */
            if ($value->{"m.usumensaje_id"} != '' && $value->{"m.usumensaje_id"} != '0') {

                $array = [];

                $array["body"] = $value->{"m.body"};
                $array["checked"] = ($value->{"m.is_read"} == 1) ? true : false;
                $array["open"] = false;
                $array["date"] = $value->{"umc.fecha_envio"};
                $array["id"] = $value->{"m.usumensaje_id"};
                $array["subject"] = $value->{"m.msubject"};
                $array["thread_id"] = $value->{"m.parent_id"};
                $array['global'] = ($value->{"m.usuto_id"} == 0) ? true : false;

                array_push($mensajesRecibidos, $array);
            } else {
                /* crea un array de mensajes con atributos específicos y lo añade a una lista. */


                $array = [];

                $array["body"] = $value->{"umc.body"};
                $array["checked"] = false;
                $array["open"] = false;
                $array["date"] = $value->{"umc.fecha_crea"};
                $array["id"] = $value->{"umc.usumensaje_id"};
                $array["subject"] = $value->{"umc.descripcion"};
                $array["thread_id"] = $value->{"umc.parent_id"};
                $array['global'] = ($value->{"umc.usuto_id"} == 0) ? true : false;

                array_push($mensajesRecibidos, $array);
            }

        }

    } else {


        /* Se obtiene y decodifica un mensaje de usuario según parámetros específicos. */
        $UsuarioMensaje = new UsuarioMensaje();
//$usuarios = $UsuarioMensaje->getUsuarioMensajesCustomCampana(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "desc", $SkeepRows, $MaxRows, $json2, true);
        $usuarios = $UsuarioMensaje->getUsuarioMensajesCustomCampana($UsuarioMandante->getUsuarioMandante(), $UsuarioMandante->getPaisId(), $Usuario->fechaCrea, $Usuario->mandante);

        $usuarios = json_decode($usuarios);

        /*foreach ($usuarios->data as $key => $value) {

            $array = [];

            $array["body"] = $value->{"usuario_mensaje.body"};
            $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
            $array["open"] = false;
            $array["date"] = $value->{"usuario_mensaje.fecha_crea"};
            $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
            $array["subject"] = $value->{"usuario_mensaje.msubject"};
            $array["thread_id"] = $value->{"usuario_mensaje.parent_id"};

            array_push($mensajesRecibidos, $array);

        }*/


        /* Recorre usuarios, creando un array de mensajes con sus atributos en cada iteración. */
        foreach ($usuarios->data as $key => $value) {

            $array = [];

            $array["body"] = $value->{"m.body"};
            $array["checked"] = ($value->{"m.is_read"} == 1) ? true : false;
            $array["open"] = false;
            $array["date"] = $value->{"m.fecha_crea"};
            $array["id"] = $value->{"m.usumensaje_id"};
            $array["subject"] = $value->{"m.msubject"};
            $array["thread_id"] = $value->{"m.parent_id"};
            $array['global'] = ($value->{"m.usuto_id"} == 0) ? true : false;

            array_push($mensajesRecibidos, $array);

        }

    }
    /*$json2 = '{"rules" : [{"field" : "usuario_mensaje.usufrom_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"}] ,"groupOp" : "AND"}';

    $UsuarioMensaje = new UsuarioMensaje();
    $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true);

    $usuarios = json_decode($usuarios);


    foreach ($usuarios->data as $key => $value) {

        $array = [];

        $array["body"] = $value->{"usuario_mensaje.body"};
        $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
        $array["date"] = 1514649066;
        $array["id"] = 123213213;
        $array["subject"] = $value->{"usuario_mensaje.msubject"};
        $array["thread_id"] = null;

        array_push($mensajesEnviados, $array);

    }


    $response = array();*/


    /* inicializa y luego reemplaza el array de mensajes en el response. */
    $response["data"] = array(
        "messages" => array()
    );

    $response["data"] = array(
        "messages" => $mensajesRecibidos
    );

    /* asigna valores a un array de respuesta con datos de usuarios y posiciones. */
    $response["total_count"] = $usuarios->count[0]->{".count"};;


    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["pos"] = $SkeepRows;


} else {
    /* inicializa una respuesta JSON vacía en caso de una condición específica. */


    $response["data"] = array(
        "messages" => array()
    );

    $response["data"] = array(
        "messages" => array()
    );
    $response["total_count"] = 0;


    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["pos"] = 0;

}