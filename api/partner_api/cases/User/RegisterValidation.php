<?php


use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\dto\Mandante;


/**
 * Este script valida el registro de un usuario y envía un correo de activación.
 * 
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param string $email Correo electrónico del usuario.
 * @param int|string $partner ID del socio asociado al usuario.
 * @param string $type Tipo de operación (opcional).
 * @param string $clave_activa Clave de activación para completar el registro.
 * 
 * @return array $response Contiene el estado de la operación, mensajes de error o éxito.
 */

/* obtiene datos JSON y la dirección IP del cliente. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ConfigurationEnvironment = new ConfigurationEnvironment();

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

/* Se extraen valores de una cadena y parámetros para su uso posterior. */
$ip = explode(",", $ip)[0];

$email = $params->email;
$partner = $params->partner;
$type = $params->type;
$clave_activa = $params->clave_activa;


/* Verifica si $partner no es numérico; si es así, lo asigna como una cadena vacía. */
if (!is_numeric($partner)) {
    $partner = '';
}

try {
    if ($email != "") {

        /* Se verifica si el usuario existe en la plataforma, manejando errores potenciales. */
        $Usuario = new Usuario();
        $Usuario->login = $email;
        $Usuario->mandante = $partner;


        if (!$Usuario->exitsLogin('0', $partner)) {
            $response["HasError"] = true;
            $response["AlertMessage"] = 'No existe el usuario en la plataforma';

        } else {


            /* Se crea un usuario y un mandante a partir de los datos del usuario. */
            $Usuario = new Usuario('', $email, '0', $partner);

            $email = $Usuario->login;

            $Mandante = new Mandante($Usuario->mandante);

            $msj_complementario = "";

            switch (strtolower($Usuario->idioma)) {


                case "pt":


                    //Arma el mensaje para el usuario que se registra

                    /* Genera un mensaje de activación de registro con un enlace personalizado. */
                    $mensaje_txt = "You are welcome. Please click <a href='https://" . $Mandante->baseUrl . "/registro-activar/" . $email . "/" . $clave_activa . "'>here</a> or in the following link to complete the registration" . $msj_complementario . "<br><br>";
                    $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;' href='" . $Mandante->baseUrl . "/registro-activar/" . $email . "/" . $clave_activa . "'><div style=\"
    /* height: 60px; */
    margin: 10px auto;
    border-radius: 10px;
    padding: 5px;
    color: white !important;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
\">" . $Mandante->baseUrl . "/registro-activar/" . $email . "/" . $clave_activa . "</div></a> <br><br>";

                    $mensaje_txt = $mensaje_txt . "Remember that your credentials for access are as follows:" . "<br><br>";

                    /* Construye un mensaje de registro con recomendaciones de seguridad y datos del usuario. */
                    $mensaje_txt = $mensaje_txt . "Username: " . $email . "<br>";
                    $mensaje_txt = $mensaje_txt . "Important note: we suggest that once you access the system for the first time, change the password immediately; Also as an additional recommendation, secure your account by changing that password regularly." . "<br><br>";


                    $msubjetc = 'Register ' . $Mandante->nombre;
                    $mtitle = 'Register ' . $Mandante->nombre;

                    break;

                case "en":

                    //Arma el mensaje para el usuario que se registra

                    /* Genera un mensaje de activación de registro con enlaces personalizados y estilo. */
                    $mensaje_txt = "You are welcome. Please click <a href='https://" . $Mandante->baseUrl . "/registro-activar/" . $email . "/" . $clave_activa . "'>here</a> or in the following link to complete the registration" . $msj_complementario . "<br><br>";
                    $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;' href='" . $Mandante->baseUrl . "/registro-activar/" . $email . "/" . $clave_activa . "'><div style=\"
    /* height: 60px; */
    margin: 10px auto;
    border-radius: 10px;
    padding: 5px;
    color: white !important;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
\">" . $Mandante->baseUrl . "/registro-activar/" . $email . "/" . $clave_activa . "</div></a> <br><br>";

                    $mensaje_txt = $mensaje_txt . "Remember that your credentials for access are as follows:" . "<br><br>";

                    /* Construye un mensaje de registro sugiriendo cambiar la contraseña tras el primer acceso. */
                    $mensaje_txt = $mensaje_txt . "Username: " . $email . "<br>";
                    $mensaje_txt = $mensaje_txt . "Important note: we suggest that once you access the system for the first time, change the password immediately; Also as an additional recommendation, secure your account by changing that password regularly." . "<br><br>";


                    $msubjetc = 'Register ' . $Mandante->nombre;
                    $mtitle = 'Register ' . $Mandante->nombre;
                    break;

                default:
                    //Arma el mensaje para el usuario que se registra

                    /* Genera un mensaje de bienvenida con enlace para completar el registro y credenciales. */
                    $mensaje_txt = "You are welcome. Please click <a href='https://" . $Mandante->baseUrl . "/registro-activar/" . $email . "/" . $clave_activa . "'>here</a> or in the following link to complete the registration" . $msj_complementario . "<br><br>";
                    $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;' href='" . $Mandante->baseUrl . "/registro-activar/" . $email . "/" . $clave_activa . "'><div style=\"
    /* height: 60px; */
    margin: 10px auto;
    border-radius: 10px;
    padding: 5px;
    color: white !important;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
\">" . $Mandante->baseUrl . "/registro-activar/" . $email . "/" . $clave_activa . "</div></a> <br><br>";

                    $mensaje_txt = $mensaje_txt . "Remember that your credentials for access are as follows:" . "<br><br>";

                    /* Construye un mensaje de registro sugiriendo cambios de contraseña para seguridad. */
                    $mensaje_txt = $mensaje_txt . "Username: " . $email . "<br>";
                    $mensaje_txt = $mensaje_txt . "Important note: we suggest that once you access the system for the first time, change the password immediately; Also as an additional recommendation, secure your account by changing that password regularly." . "<br><br>";


                    $msubjetc = 'Register ' . $Mandante->nombre;
                    $mtitle = 'Register ' . $Mandante->nombre;
                    break;
            }


            /* Envía un correo utilizando la configuración y parámetros especificados en el código. */
            print_r($Mandante);
            //Destinatarios
            $destinatarios = $email;

            //Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $mtitle, $mensaje_txt, $dominio, $compania, $color_email, $Mandante->mandante);


            /* crea una respuesta estructurada indicando éxito y ausencia de errores. */
            $response["status"] = true;
            $response["result"] = $envio;
            $response["success"] = true;


            $response["HasError"] = false;

        }

    } else {
        /* crea un objeto y envía un correo, comentando varias líneas. */

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        //print_r($ConfigurationEnvironment->GenerarClaveTicket2(12));
        //Envia el mensaje de correo
        // $envio = $ConfigurationEnvironment->EnviarCorreo($destinatarios, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $mtitle, $mensaje_txt, $dominio, $compania, $color_email,$Mandante->mandante);

        // print_r($Mandante);
    }
} catch (Exception $e) {
    /* captura excepciones y las vuelve a lanzar sin modificarlas. */

    throw $e;
}