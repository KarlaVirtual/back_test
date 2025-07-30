<?php


use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\dto\Mandante;


/**
 * Maneja la solicitud de recuperación de contraseña para un usuario en la plataforma.
 *
 * Este script procesa una solicitud de recuperación de contraseña, valida la existencia del usuario,
 * registra un log de actividad y envía un correo electrónico con un enlace para cambiar la contraseña.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes valores:
 * @param string $params->email Correo electrónico del usuario.
 * @param string $params->partner Identificador del socio (puede ser numérico o vacío).
 * @param string $params->type Tipo de solicitud (por ejemplo, 'import').
 *
 * @return array $response Respuesta que incluye:
 * - bool $HasError Indica si ocurrió un error.
 * - string $AlertMessage Mensaje de alerta en caso de error.
 * - bool $status Indica si la operación fue exitosa.
 * - mixed $result Resultado del envío del correo electrónico.
 * - bool $success Indica si la operación fue exitosa.
 */

/* obtiene datos JSON de una solicitud HTTP y obtiene la IP del cliente. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ConfigurationEnvironment = new ConfigurationEnvironment();

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

/* Se procesa un IP, se extraen parámetros y se valida el formato del socio. */
$ip = explode(",", $ip)[0];

$email = $params->email;
$partner = $params->partner;
$type = $params->type;

if (!is_numeric($partner)) {
    $partner = '';
}

try {
    if ($email != "") {


        /* Se verifica si existe un usuario en la plataforma con el correo proporcionado. */
        $Usuario = new Usuario();
        $Usuario->login = $email;
        $Usuario->mandante = $partner;


        if (!$Usuario->exitsLogin('0', $partner)) {
            $response["HasError"] = true;
            $response["AlertMessage"] = 'No existe el usuario en la plataforma';

        } else {


            /* Se crea un objeto Usuario y se registra un log de su actividad. */
            $Usuario = new Usuario('', $email, '0', $partner);


            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp($ip);


            /* asigna valores a un objeto de registro de usuario dependiendo del tipo. */
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp($ip);

            if ($type == 'import') {
                $UsuarioLog->setTipo("TOKENPASSIMPORT");
            } else {
                /* Asignación del tipo "TOKENPASS" al objeto $UsuarioLog en caso contrario. */

                $UsuarioLog->setTipo("TOKENPASS");
            }


            /* Configura un registro de usuario con estado "P" y valores iniciales vacíos. */
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues('');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);


            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            /* Se inserta un registro de usuario y se guarda un código cifrado relacionado. */
            $usuariologId = $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

            $code = ($ConfigurationEnvironment->encrypt($usuariologId . "_" . time(), $ENCRYPTION_KEY));


            $UsuarioLog->setValorAntes($code);

            /* Actualiza un registro de usuario y confirma la transacción en la base de datos. */
            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
            $usuariologId = $UsuarioLogMySqlDAO->update($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();


            $email = $Usuario->login;


            /* Se crea una instancia de la clase Mandante utilizando un usuario existente. */
            $Mandante = new Mandante($Usuario->mandante);
            switch (strtolower($Usuario->idioma)) {


                case "pt":
                    //Arma el mensaje para el usuario que se registra

                    /* Genera un mensaje para la recuperación de contraseña con un enlace. */
                    $mensaje_txt = "Recentemente, voc&ecirc; solicitou uma altera&ccedil;&atilde;o de senha, &eacute; muito f&aacute;cil, basta clicar no bot&atilde;o abaixo e voc&ecirc; pode alterar a senha.<br><br> ";
                    $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;color: white;' href='" . $Mandante->baseUrl . "recuperar-clave-validar/" . $code . "'><div style=\"
    /* height: 60px; */
    width: 200px;
    margin: 10px auto;
    background: #9E9E9E;
    border-radius: 10px;
    padding: 5px;
    color: white !important;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
\">Mudar senha</div></a> <br><br>";

                    $mensaje_txt = $mensaje_txt . "<p>Se voc&ecirc; n&atilde;o pode alterar sua senha, entre em contato conosco.</p><br><br>";


                    /* Asignación de variables para título y asunto de recuperación de contraseña. */
                    $mtitle = 'Recupera&ccedil;&atilde;o de senha ' . $Mandante->nombre;
                    $msubjetc = 'Recuperação de Senha ' . $Mandante->nombre;

                    break;

                case "en":
                    //Arma el mensaje para el usuario que se registra

                    /* Genera un mensaje de correo electrónico para solicitar un cambio de contraseña. */
                    $mensaje_txt = "Recently you requested a password change, it is very easy, just click on the button below and you can change the password.<br><br> ";
                    $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;color: white;' href='" . $Mandante->baseUrl . "recuperar-clave-validar/" . $code . "'><div style=\"
    /* height: 60px; */
    width: 200px;
    margin: 10px auto;
    background: #9E9E9E;
    border-radius: 10px;
    padding: 5px;
    color: white !important;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
\">Change Password</div></a> <br><br>";

                    $mensaje_txt = $mensaje_txt . "<p>If you cannot change your password, please contact us.</p><br><br>";


                    /* Se definen variables para el asunto y título del correo de recuperación de contraseña. */
                    $msubjetc = 'Password recovery ' . $Mandante->nombre;
                    $mtitle = 'Password recovery ' . $Mandante->nombre;
                    break;

                default:

                    //Arma el mensaje para el usuario que se registra

                    /* Construye un mensaje HTML para cambiar contraseñas, incluyendo un botón de enlace. */
                    $mensaje_txt = "Recientemente usted solicito un cambio de contrase&#241;a, es muy f&#225;cil, solo haz click en el siguiente boton y puede cambiar la contrase&#241;a.<br><br> ";
                    $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;color: white;' href='" . $Mandante->baseUrl . "recuperar-clave-validar/" . $code . "'><div style=\"
    /* height: 60px; */
    width: 200px;
    margin: 10px auto;
    background: #9E9E9E;
    border-radius: 10px;
    padding: 5px;
    color: white !important;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
\">Cambiar Contrase&#241;a</div></a> <br><br>";

                    $mensaje_txt = $mensaje_txt . "<p>Si no puede cambiar su contrase&#241;a, por favor p&#243;ngase en contacto con nosotros.</p><br><br>";
                    //$mensaje_txt = $mensaje_txt . "<p style='text-align: left;'>Doradobet. </p>" . "";


                    /* Asigna un título y un asunto de email para recuperación de clave del usuario. */
                    $mtitle = 'Recuperaci&#243;n de clave ' . $Mandante->nombre;
                    $msubjetc = 'Recuperacion de clave ' . $Mandante->nombre;
                    break;
            }

            //Destinatarios

            /* Envía un correo electrónico utilizando una configuración y parámetros específicos. */
            $destinatarios = $email;

            //Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $mtitle, $mensaje_txt, $dominio, $compania, $color_email, $Mandante->mandante);

            $response["status"] = true;

            /* asigna valores a un array de respuesta, indicando éxito y sin errores. */
            $response["result"] = $envio;
            $response["success"] = true;


            $response["HasError"] = false;

        }

    } else {
        /* crea un entorno de configuración y envía un correo electrónico. */

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        //print_r($ConfigurationEnvironment->GenerarClaveTicket2(12));
        //Envia el mensaje de correo
        // $envio = $ConfigurationEnvironment->EnviarCorreo($destinatarios, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $mtitle, $mensaje_txt, $dominio, $compania, $color_email,$Mandante->mandante);

        // print_r($Mandante);
    }
} catch (Exception $e) {
    /* captura una excepción y la vuelve a lanzar sin modificarla. */

    throw $e;
}