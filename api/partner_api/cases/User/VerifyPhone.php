<?php


use Backend\dto\Registro;
use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\dto\Mandante;

/**
 * Este script verifica el teléfono de un usuario y envía un código de validación.
 * 
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param string $email Correo electrónico del usuario.
 * @param int|string $partner ID del socio asociado al usuario.
 * @param string $type Tipo de operación (opcional).
 * 
 * @return array $response Contiene el estado de la operación, mensajes de error o éxito.
 */

/* recibe y decodifica datos JSON, obteniendo la dirección IP del cliente. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ConfigurationEnvironment = new ConfigurationEnvironment();

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

/* Separa la primera dirección IP y valida que el partner sea numérico. */
$ip = explode(",", $ip)[0];

$email = $params->email;
$partner = $params->partner;
$type = $params->type;

if (!is_numeric($partner)) {
    $partner = '';
}

try {
    if ($email != "") {

        /* Verifica si un usuario existe en la plataforma antes de iniciar sesión. */
        $Usuario = new Usuario();
        $Usuario->login = $email;
        $Usuario->mandante = $partner;


        if (!$Usuario->exitsLogin('0', $partner)) {
            $response["HasError"] = true;
            $response["AlertMessage"] = 'No existe el usuario en la plataforma';

        } else {


            /* crea instancias de Usuario, Registro y UsuarioMandante con datos específicos. */
            $Usuario = new Usuario('', $email, '0', $partner);
            $Registro = new Registro('', $Usuario->usuarioId);
            $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);


            $UsuarioLog = new UsuarioLog();

            /* Se registra un log de usuario con información sobre la verificación del teléfono. */
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp($ip);

            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp($ip);

            $UsuarioLog->setTipo("VERIFYPHONE");


            /* Se configura un objeto UsuarioLog y se inicializa un DAO para interactuar con MySQL. */
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues('');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);


            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            /* Inserta un registro de log de usuario y genera una clave encriptada. */
            $usuariologId = $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

            $codigo = $ConfigurationEnvironment->GenerarClaveTicket2(6);

            $code = ($ConfigurationEnvironment->encryptWithoutRandom($Usuario->usuarioId . "_" . $codigo, $ENCRYPTION_KEY));


            /* Actualiza el registro de log de usuario y confirma la transacción en MySQL. */
            $UsuarioLog->setValorDespues($code);
            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
            $usuariologId = $UsuarioLogMySqlDAO->update($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();


            $email = $Usuario->login;


            /* Se crea una nueva instancia de la clase Mandante usando un atributo del usuario. */
            $Mandante = new Mandante($Usuario->mandante);
            switch (strtolower($Usuario->idioma)) {


                case "pt":
                    /* genera un mensaje de registro en portugués para validar un teléfono celular. */

                    //Arma el mensaje para el usuario que se registra
                    $mensaje_txt = "Olá, digite este código para validar seu telefone celular:";


                    break;

                case "en":
                    /* Genera un mensaje de validación para el registro de teléfonos en inglés. */

                    //Arma el mensaje para el usuario que se registra
                    $mensaje_txt = "Hello, enter this code to validate your cell phone:";
                    break;

                default:

                    //Arma el mensaje para el usuario que se registra

                    /* Código que genera un mensaje de validación para teléfonos móviles de DoradoBet. */
                    $mensaje_txt = "DoradoBet | xxxxxx es el código de validación de tu teléfono móvil. Para cualquier consulta ingresa al chat en www.doradobet.com";
                    break;
            }


            /* envía un mensaje reemplazando un marcador de texto con un código. */
            $mensaje_txt = str_replace("xxxxxx", $codigo, $mensaje_txt);
            //Destinatarios
            $destinatarios = $email;

            //Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);


            /* Código que configura una respuesta exitosa sin errores en formato JSON. */
            $response["status"] = true;
            $response["result"] = $envio;
            $response["success"] = true;


            $response["HasError"] = false;

        }

    } else {
        /* gestiona la creación de un entorno de configuración y envía un correo. */

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        //print_r($ConfigurationEnvironment->GenerarClaveTicket2(12));
        //Envia el mensaje de correo
        // $envio = $ConfigurationEnvironment->EnviarCorreo($destinatarios, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $mtitle, $mensaje_txt, $dominio, $compania, $color_email,$Mandante->mandante);

        // print_r($Mandante);
    }
} catch (Exception $e) {
    /* Maneja excepciones imprimiéndolas y luego vuelve a lanzarlas. */

    print_r($e);
    throw $e;
}