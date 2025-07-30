<?php

use Backend\dto\Usuario;
use Backend\dto\Template;
use Backend\dto\Clasificador;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioVerificacion;
use Backend\dto\Registro;
use Backend\dto\Mandante;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\UsuarioVerificacionMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\TemplateMySqlDAO;

/**
 * command/verification_account
 *
 * Proceso de verificación de usuario y generación de mensaje de estado
 *
 * Este código maneja la verificación de un usuario mediante distintos estados y motivos. Dependiendo del estado
 * de verificación (aceptado, pendiente, rechazado o iniciado), se genera un mensaje personalizado con el estado
 * de la verificación, observaciones (motivos) y otros detalles del usuario. Además, se asignan plantillas específicas
 * basadas en la verificación y estado del usuario.
 *
 * @param object $json : Objeto que contiene la información de la solicitud
 * @param string $json ->session->usuario : Usuario que realiza la solicitud.
 *
 * El objeto `$response` es un array con los siguientes atributos:
 *  - *code* (int): Código de respuesta del sistema. 0 para éxito, cualquier otro valor indica un error.
 *  - *rid* (string): Identificador de solicitud para seguimiento.
 *  - *data* (array): Contiene el resultado de la consulta, con el mensaje generado y estado de la verificación.
 *    - *Template* (string): Mensaje generado con la plantilla correspondiente según el estado.
 *    - *Verification_status* (int): Estado de la verificación (0 para aceptada, 1 para rechazada, 2 para pendiente o iniciada).
 *    - *Reason* (string): Motivo de rechazo o verificación (si aplica).
 *
 * Objeto en caso de error:
 *
 * "code" => 0,
 * "rid" => [Identificador de solicitud],
 * "data" => array(
 *   "Template" => "Por favor, complete la verificación de su cuenta para continuar con el proceso de registro y acceder a nuestros servicios.
 *                  Sólo debes hacer clic en el botón Verificar Cuenta. Esta etapa es crucial para asegurar la integridad de su cuenta y brindarle
 *                  una experiencia segura.",
 *   "Verification_status" => null
 * ),
 *
 * @throws Exception En caso de error en el proceso de verificación o al generar el mensaje.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Variable que almacena un motivo como cadena de texto vacía. */
$Motivo = '';
try {


    /* Se crean objetos de usuario y mandante a partir de detalles en formato JSON. */
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
    $Registro = new Registro('', $Usuario->usuarioId);
    $Mandante = new Mandante($Usuario->mandante);
    $NameMandante = $Mandante->mandante;


    /* Se definen variables para ordenar, limitar y saltar filas en una consulta. */
    $Order = "desc";
    $Maxrows = 1;
    $SkeepRows = 0;


    $rules = [];


    /* Se crea un filtro para verificar usuarios usando condiciones en un array. */
    array_push($rules, array("field" => "usuario_verificacion.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
    array_push($rules, array("field" => "usuario_verificacion.tipo", "data" => "USUVERIFICACION", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $UsuarioVerificacion = new UsuarioVerificacion();

    /* Extrae y transforma datos de usuario verificación en un formato específico. */
    $Datos = $UsuarioVerificacion->getUsuarioVerificacionCustom("usuario_verificacion.*", "usuario_verificacion.usuverificacion_id", $Order, $SkeepRows, $Maxrows, $json, true);
    $Datos = json_decode($Datos);


    $final = [];
    foreach ($Datos->data as $key => $value) {
        $array = [];

        $array["estado"] = $value->{"usuario_verificacion.estado"};
        $array["Motivo"] = $value->{"usuario_verificacion.observacion"};

        array_push($final, $array);
    }


    /* Asigna valores de un array a variables y inicializa una cadena vacía. */
    $Estado = $final[0]['estado'];
    $Motivo = $final[0]['Motivo'];


    $mensaje_txt = "";

    if ($Estado == "A") {


        /* Creación de un clasificador y template, luego se establece el estado de aceptación. */
        $Clasificador = new Clasificador("", "APROBJUMIO");

        $Template = new Template("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, $Usuario->idioma);

        $mensaje_txt .= $Template->templateHtml;

        if ($Estado == "A") {
            $estado = "aceptada";
        }


        /* reemplaza etiquetas en un texto con propiedades del objeto Usuario. */
        $state = 0;


        $mensaje_txt = str_replace("#IdUser#", $Usuario->usuarioId, $mensaje_txt);
        $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
        $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);

        /* Reemplaza marcadores en un mensaje con datos de usuario y socio. */
        $mensaje_txt = str_replace("#state#", $UsuarioVerificacion->getEstado(), $mensaje_txt);
        $mensaje_txt = str_replace("#partner#", $Mandante->descripcion, $mensaje_txt);


    } else if ($Estado == "P") {
        /* Genera un mensaje con datos del usuario y estado "Pendiente". */


        $Clasificador = new Clasificador("", "PENDACCOUNT");
        $Template = new Template("", $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, $Usuario->idioma);
        $mensaje_txt .= $Template->templateHtml;

        $state = 2;

        if ($Estado == "P") {
            $estado = "Pendiente";
        }

        $mensaje_txt = str_replace("#IdUser#", $Usuario->usuarioId, $mensaje_txt);
        $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
        $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
        $mensaje_txt = str_replace("#state#", $estado, $mensaje_txt);
        $mensaje_txt = str_replace("#partner#", $Mandante->descripcion, $mensaje_txt);

    } else if ($Estado == "R" && $Motivo == "Rechazado por usabilidad") {

        /* Se crea un clasificador y un template, manejando el estado "Rechazada". */
        $Clasificador = new Clasificador("", "rejectionDueToUsability");
        $Template = new Template("", $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, $Usuario->idioma);
        $mensaje_txt .= $Template->templateHtml;

        if ($Estado == "R") {
            $estado = "Rechazada";
            $Motivo = $UsuarioVerificacion->getObservacion();
        }


        /* Se reemplazan marcadores en un texto por datos del usuario correspondiente. */
        $state = 1;

        $mensaje_txt = str_replace("#IdUser#", $Usuario->usuarioId, $mensaje_txt);
        $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
        $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
        $mensaje_txt = str_replace("#state#", $estado, $mensaje_txt);

        /* Reemplaza placeholders en el texto del mensaje con valores específicos. */
        $mensaje_txt = str_replace("#motivo#", $Motivo, $mensaje_txt);
        $mensaje_txt = str_replace("#partner#", $Mandante->descripcion, $mensaje_txt);


    } else if ($Estado == "R" && $Motivo == "Rechazado por datos") {

        /* Crea un template y define el estado de un clasificador basado en condiciones. */
        $Clasificador = new Clasificador("", "RejectionDueToDataInTheBackOffice");
        $Template = new Template("", $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, $Usuario->idioma);
        $mensaje_txt .= $Template->templateHtml;


        if ($Estado == "R") {
            $estado = "Rechazada";
            $Motivo = $UsuarioVerificacion->getObservacion();
        }


        /* Reemplaza marcadores en un mensaje con información del usuario correspondiente. */
        $state = 1;

        $mensaje_txt = str_replace("#IdUser#", $Usuario->usuarioId, $mensaje_txt);
        $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
        $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
        $mensaje_txt = str_replace("#state#", $estado, $mensaje_txt);

        /* Reemplaza marcadores en un texto con valores de variables específicas. */
        $mensaje_txt = str_replace("#motivo#", $Motivo, $mensaje_txt);
        $mensaje_txt = str_replace("#partner#", $Mandante->descripcion, $mensaje_txt);

    } else if ($Estado == "R" && $Motivo == "Rechazado por imagen") {

        /* Se crea un clasificador y una plantilla, luego se gestiona un estado de rechazo. */
        $Clasificador = new Clasificador("", "RejectionByImageInTheBackOffice");
        $Template = new Template("", $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, $Usuario->idioma);
        $mensaje_txt .= $Template->templateHtml;


        if ($Estado == "R") {
            $estado = "Rechazada";
            $Motivo = $UsuarioVerificacion->getObservacion();
        }


        /* Reemplaza marcadores en un texto de mensaje con datos del usuario. */
        $state = 1;

        $mensaje_txt = str_replace("#IdUser#", $Usuario->usuarioId, $mensaje_txt);
        $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
        $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
        $mensaje_txt = str_replace("#state#", $estado, $mensaje_txt);

        /* Reemplaza marcadores en un texto con valores específicos de variables. */
        $mensaje_txt = str_replace("#motivo#", $Motivo, $mensaje_txt);
        $mensaje_txt = str_replace("#partner#", $Mandante->descripcion, $mensaje_txt);


    } else if ($Estado == "R" && $Motivo == "Rechazado por extracción de datos") {

        /* crea un clasificador y un template, luego verifica un estado de rechazo. */
        $Clasificador = new Clasificador("", "RejectionDueToDataExtraction");

        $Template = new Template("", $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, $Usuario->idioma);
        $mensaje_txt .= $Template->templateHtml;


        if ($Estado == "R") {
            $estado = "Rechazada";
            $Motivo = $UsuarioVerificacion->getObservacion();
        }


        /* Reemplaza marcadores en el mensaje con datos del usuario. */
        $state = 1;

        $mensaje_txt = str_replace("#IdUser#", $Usuario->usuarioId, $mensaje_txt);
        $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
        $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
        $mensaje_txt = str_replace("#state#", $estado, $mensaje_txt);

        /* Reemplaza marcadores en un texto con valores específicos como motivo y descripción. */
        $mensaje_txt = str_replace("#motivo#", $Motivo, $mensaje_txt);
        $mensaje_txt = str_replace("#partner#", $Mandante->descripcion, $mensaje_txt);

    } else if ($Estado == "I") {
        /* Genera un mensaje HTML basado en estado "I" y datos del usuario. */


        $Clasificador = new Clasificador("", "STARTEDVERIFICATION");
        $Template = new Template("", $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, $Usuario->idioma);
        $mensaje_txt .= $Template->templateHtml;

        $estado = "inciada";
        $state = 2;

        $mensaje_txt = str_replace("#IdUser#", $Usuario->usuarioId, $mensaje_txt);
        $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
        $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
        $mensaje_txt = str_replace("#state#", $estado, $mensaje_txt);
        $mensaje_txt = str_replace("#partner#", $Mandante->descripcion, $mensaje_txt);

    } else if ($Estado == "R") {
        /* Proceso de rechazo con creación de plantilla y personalización de mensaje para usuario. */

        $Clasificador = new Clasificador("", "REJECTIONJUMIO");
        $Template = new Template("", $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, $Usuario->idioma);
        $mensaje_txt .= $Template->templateHtml;

        $estado = "Rechazada";
        $state = 1;

        $mensaje_txt = str_replace("#IdUser#", $Usuario->usuarioId, $mensaje_txt);
        $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
        $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
        $mensaje_txt = str_replace("#state#", $estado, $mensaje_txt);
        $mensaje_txt = str_replace("#partner#", $Mandante->descripcion, $mensaje_txt);

    } else {
        /* Crea un mensaje HTML personalizado utilizando datos de usuario y un clasificador específico. */


        $Clasificador = new Clasificador("", "ACCOUNTWITHOUTVERIFICATION");
        $Template = new Template("", $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, $Usuario->idioma);
        $mensaje_txt .= $Template->templateHtml;

        $estado = null;

        $mensaje_txt = str_replace("#IdUser#", $Usuario->usuarioId, $mensaje_txt);
        $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
        $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
        $mensaje_txt = str_replace("#partner#", $Mandante->descripcion, $mensaje_txt);
    }


    /* Crea un array de respuesta con información de verificación y un mensaje. */
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "Template" => $mensaje_txt,
        "Verification_status" => $state,
        "Reason" => $Motivo
    );


} catch (Exception $e) {
    /* Manejo de excepciones que devuelve un mensaje de verificación de cuenta en JSON. */


    $response = array();
    $response["code"] = 0;
    $response["data"] = array(
        "Template" => "Por favor, complete la verificación de su cuenta para continuar con el proceso de registro y acceder a nuestros servicios. Sólo debes hacer clic en el botón Verificar Cuenta. Esta etapa es crucial para asegurar la integridad de su cuenta y brindarle una experiencia segura.",
        "Verification_status" => null
    );
    $response["rid"] = $json->rid;
}
