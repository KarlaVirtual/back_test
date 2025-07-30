<?php


use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\dto\Mandante;
use Backend\dto\PuntoVenta;

/**
 * Account/ResetPassword
 *
 * Recuperación de Contraseña
 *
 * Este recurso permite a un usuario solicitar la recuperación de su contraseña.
 * Se valida la existencia del usuario y se genera un token seguro que se envía
 * por correo electrónico para restablecer la contraseña.
 *
 * @param string $params : JSON con los datos de entrada, incluyendo:
 *  - *Email* (string): Correo electrónico del usuario que solicita la recuperación.
 *  - *Partnert* (int|string): Identificador del socio comercial asociado al usuario.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *status* (bool): Indica si la operación fue exitosa o no.
 *  - *result* (mixed): Contiene el resultado del envío del correo electrónico.
 *  - *success* (bool): Especifica si la recuperación de contraseña se procesó correctamente.
 *  - *HasError* (bool): Indica si hubo un error en el proceso.
 *
 * Objeto en caso de error:
 *
 * "status" => false,
 * "success" => false,
 * "HasError" => true,
 *
 * @throws Exception Si ocurre un error en la validación de datos o en la generación del token.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* recibe datos JSON y prepara un entorno de configuración. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ENCRYPTION_KEY = "";

$ConfigurationEnvironment = new ConfigurationEnvironment();


/* obtiene la dirección IP y valida el parámetro "partner". */
$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];

$email = $params->Email;
$partner = $params->Partnert;
//$type = $params->type;


if (!is_numeric($partner)) {
    $partner = '';
}

try {
    if ($email != "" && $partner != '' && $partner != '-1') {


        /* Se define un conjunto de reglas para filtrar datos basados en condiciones específicas. */
        $rules = [];

        array_push($rules, array("field" => "usuario.mandante", "data" => $partner, "op" => "eq"));
        array_push($rules, array("field" => "punto_venta.email", "data" => $email, "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* Convierte datos a JSON y obtiene puntos de venta personalizados mediante una clase. */
        $jsonbetshop = json_encode($filtro);


        $PuntoVenta = new PuntoVenta();


        $puntosventas = $PuntoVenta->getPuntoVentasCustom("usuario.usuario_id", "punto_venta.puntoventa_id", "asc", 0, 1, $jsonbetshop, true);


        /* Convierte un JSON a objeto y extrae el ID de usuario como entero. */
        $puntosventas = json_decode($puntosventas);

        $usuarioId = intval($puntosventas->data[0]->{'usuario.usuario_id'});

        if ($usuarioId != '' && $usuarioId > 0) {


            /* Se crean instancias de Usuario y PuntoVenta para gestionar la información del usuario. */
            $Usuario = new Usuario($usuarioId);
            $PuntoVenta = new PuntoVenta("", $Usuario->usuarioId);

            $Usuario->login = $PuntoVenta->email;
            $Usuario->mandante = $partner;


            $UsuarioLog = new UsuarioLog();

            /* registra información del usuario y su IP en un objeto de log. */
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp($ip);

            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp($ip);

            /* if($type == 'import'){
                 $UsuarioLog->setTipo("TOKENPASSIMPORT");
             }else{
                 $UsuarioLog->setTipo("TOKENPASS");
             }*/

            $UsuarioLog->setTipo("TOKENPASS");

            /* establece valores de un objeto `UsuarioLog` y crea un DAO correspondiente. */
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues('');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);


            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            /* inserta un registro y cifra su ID junto con un timestamp. */
            $usuariologId = $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

            $code = ($ConfigurationEnvironment->encrypt($usuariologId . "_" . time(), $ENCRYPTION_KEY));


            $UsuarioLog->setValorAntes($code);

            /* Actualiza un registro de usuario y realiza una transacción en MySQL. */
            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
            $usuariologId = $UsuarioLogMySqlDAO->update($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();


            $email = $PuntoVenta->email;


            /* Se crea un objeto Mandante con datos del usuario y se inicializa baseUrl. */
            $Mandante = new Mandante($Usuario->mandante);
            $Mandante->baseUrl = '';
            switch ($Mandante->mandante) {
                case 0:
                    /* Asigna una URL base al objeto Mandante para el caso 0. */

                    $Mandante->baseUrl = 'https://caja.doradobet.com/#!/doradobet/';
                    break;
                case 1:
                    /* Asigna una URL específica al objeto $Mandante en el caso 1. */

                    $Mandante->baseUrl = 'https://cashier.ibetsupreme.com/#!/justbet/';
                    break;
                case 2:
                    /* Establece la URL base de un sistema de apuestas en línea para el mandante. */

                    $Mandante->baseUrl = 'https://cashier.justbetja.com/#!/ibetsupreme/';

                    break;
                case 3:
                    /* configura la URL base para el caso 3 en un sistema. */

                    $Mandante->baseUrl = 'https://caja.casinomiravallepalace.com/#!/casinopalace/';
                    break;
                case 4:
                    /* establece una URL base para un mandante específico en una estructura de caso. */

                    $Mandante->baseUrl = 'https://caja.casinogranpalaciomx.com/#!/casinomiravallepalace/';
                    break;
                case 5:
                    /* Asigna una URL base a un objeto según el caso 5 en un switch. */

                    $Mandante->baseUrl = 'https://caja.casinointercontinentalmx.com/#!/casinopalace/';
                    break;
                case 6:
                    /* Asignación de una URL base a la propiedad `baseUrl` del objeto `$Mandante`. */

                    $Mandante->baseUrl = 'https://caja.netabet.com.mx/#!/netabet/';
                    break;
                case 7:
                    /* Asigna una URL específica al objeto $Mandante en el caso 7. */

                    $Mandante->baseUrl = 'https://caja.casinoastoriamx.com/#!/casinoastoriamx/';
                    break;
                case 8:
                    /* Configura la URL base para el mandante en el caso 8 de un switch. */

                    $Mandante->baseUrl = 'https://caja.ecuabet.com/#!/ecuabet/';
                    break;
                case 9:
                    /* es parte de una estructura de control, específicamente un "switch" en programación. */

                    break;
                case 10:
                    /* Es un fragmento de código que representa una instrucción de control de flujo "case". */

                    break;
                case 11:
                    /* Es un bloque de código que termina una opción en una estructura switch. */

                    break;
                case 12:
                    /* representa un caso en una estructura switch, terminando sin realizar acciones. */

                    break;
                case 13:
                    /* asigna una URL a la propiedad baseUrl del objeto Mandante. */

                    $Mandante->baseUrl = 'https://caja.eltribet.com/#!/tribet/';
                    break;
                case 14:
                    /* interrumpe un caso en una estructura de control switch. */

                    break;
            }

            switch (strtolower($Usuario->idioma)) {


                case "pt":
                    //Arma el mensaje para el usuario que se registra

                    /* Genera un mensaje de correo para solicitar un cambio de contraseña con enlace. */
                    $mensaje_txt = "Recentemente, voc&ecirc; solicitou uma altera&ccedil;&atilde;o de senha, &eacute; muito f&aacute;cil, basta clicar no bot&atilde;o abaixo e voc&ecirc; pode alterar a senha.<br><br> ";
                    $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;color: white;' href='" . $Mandante->baseUrl . "reset-password/" . $code . "'><div style=\"
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


                    /* genera un título y asunto para un correo de recuperación de contraseña. */
                    $mtitle = 'Recupera&ccedil;&atilde;o de senha ' . $Mandante->nombre;
                    $msubjetc = 'Recuperação de Senha ' . $Mandante->nombre;

                    break;

                case "en":
                    //Arma el mensaje para el usuario que se registra

                    /* Genera un mensaje de cambio de contraseña con un enlace estilizado. */
                    $mensaje_txt = "Recently you requested a password change, it is very easy, just click on the button below and you can change the password.<br><br> ";
                    $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;color: white;' href='" . $Mandante->baseUrl . "reset-password/" . $code . "'><div style=\"
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


                    /* Se asigna un asunto y título para la recuperación de contraseña. */
                    $msubjetc = 'Password recovery ' . $Mandante->nombre;
                    $mtitle = 'Password recovery ' . $Mandante->nombre;
                    break;

                default:

                    //Arma el mensaje para el usuario que se registra

                    /* Genera un mensaje de cambio de contraseña con un enlace estilizado para el usuario. */
                    $mensaje_txt = "Recientemente usted solicito un cambio de contrase&#241;a, es muy f&#225;cil, solo haz click en el siguiente boton y puede cambiar la contrase&#241;a.<br><br> ";
                    $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;color: white;' href='" . $Mandante->baseUrl . "reset-password/" . $code . "'><div style=\"
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


                    /* Se crea un título y un asunto para un correo de recuperación de clave. */
                    $mtitle = 'Recuperaci&#243;n de clave ' . $Mandante->nombre;
                    $msubjetc = 'Recuperacion de clave ' . $Mandante->nombre;
                    break;
            }

            //Destinatarios

            /* Envía un correo electrónico utilizando la configuración y parámetros definidos. */
            $destinatarios = $email;

            //Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $mtitle, $mensaje_txt, $dominio, $compania, $color_email, $Mandante->mandante);

            $response["status"] = true;

            /* asigna valores a un arreglo de respuesta con éxito y sin errores. */
            $response["result"] = $envio;
            $response["success"] = true;


            $response["HasError"] = false;


        } else {
            /* indica que ha ocurrido un error en la respuesta del sistema. */


            $response["status"] = false;

            $response["success"] = false;


            $response["HasError"] = true;
        }
    } else {
        /* gestiona el flujo de errores y configura respuestas en un entorno de configuración. */

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        //print_r($ConfigurationEnvironment->GenerarClaveTicket2(12));
        //Envia el mensaje de correo
        // $envio = $ConfigurationEnvironment->EnviarCorreo($destinatarios, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $mtitle, $mensaje_txt, $dominio, $compania, $color_email,$Mandante->mandante);

        // print_r($Mandante);


        $response["status"] = false;

        $response["success"] = false;


        $response["HasError"] = true;

    }
} catch (Exception $e) {
    /* Captura excepciones y las vuelve a lanzar sin modificaciones. */

    throw $e;
}
