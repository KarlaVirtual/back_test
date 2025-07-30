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
use Backend\dto\Template;
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
use Backend\dto\PaisMandante;
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
 * command/reset_user_password
 *
 * Recurso para enviar correo de recuperación de clave al usuario
 *
 * @param string $email : Email del usuario
 * @param boolean $forActivate : Si es para actiar la recuperación de la clave
 * @param string $ip : Ip del usuario
 * @param int $site_id : Partner del usuario
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor
 *  - *rid* (string): Contiene el mensaje de error.
 *  - *data* (array): Contiene el mensaje de aprobación del proceso.
 *
 *
 * @throws Exception No existe Usuario
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* crea una respuesta estructurada con un código, ID y datos específicos. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => "-1119"

);


/* extrae datos JSON y crea una instancia de ConfigurationEnvironment. */
$email = $json->params->email;
$forActivate = $json->params->forActivate;
$ConfigurationEnvironment = new ConfigurationEnvironment();

if ($forActivate) {


    /* limpia y depura una dirección de correo electrónico y obtiene parámetros del usuario. */
    $email = trim($email);
    $email = $ConfigurationEnvironment->DepurarCaracteres($email);
    $email = $ConfigurationEnvironment->remove_emoji($email);

    $ip = $json->params->usuarioip;
    $site_id = $json->params->site_id;

    /* valida si un usuario existe mediante su email, lanzando una excepción si no. */
    $site_id = strtolower($site_id);

    $Usuario = new Usuario();
    $Usuario->login = $email;

    if (!$Usuario->exitsLogin(0)) {
        throw new Exception("No existe Usuario", "24");

    } else {


        /* Se crean objetos para usuario y registro de actividad con datos pertinentes. */
        $Usuario = new Usuario('', $email, '', $site_id);

        $UsuarioLog = new UsuarioLog();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp($ip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);

        /* Código que guarda información de un usuario solicitando un token con IP. */
        $UsuarioLog->setUsuariosolicitaIp($ip);

        $UsuarioLog->setTipo("TOKENPASS");
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes('');
        $UsuarioLog->setValorDespues('');

        /* Se crean registros de usuario con identificadores para creación y modificación, luego se insertan. */
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);


        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $usuariologId = $UsuarioLogMySqlDAO->insert($UsuarioLog);

        /* Códigos para manejar transacciones y encriptar identificadores de usuario en una base de datos. */
        $UsuarioLogMySqlDAO->getTransaction()->commit();

        $code = ($ConfigurationEnvironment->encrypt($usuariologId . "_" . time(), $ENCRYPTION_KEY));

        $UsuarioLog->setValorAntes($code);
        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

        /* Actualiza un registro de usuario y realiza una transacción en la base de datos. */
        $usuariologId = $UsuarioLogMySqlDAO->update($UsuarioLog);
        $UsuarioLogMySqlDAO->getTransaction()->commit();


        $email = $Usuario->login;

        /* Lógica para redireccionamiento correctamente de las URL correspondiente a cada pais de su mandante*/
        try {
            $Mandante = new Mandante($Usuario->mandante);
            $PaisMandante = new PaisMandante(null, $Usuario->mandante, $Usuario->paisId);
            /* Validación para encontrar la URL en la columna base_url de base de datos*/
            if (empty($PaisMandante->baseUrl)) {
                throw new Exception("No se encontró base_url para Mandante ID {$Usuario->mandante} y País ID {$Usuario->paisId}.", 300046);
            }
            $Mandante->baseUrl = $PaisMandante->baseUrl;
            /* Se concatena la URL encontrada en la columna base_url con la vista especifica*/
            $baseurl = $Mandante->baseUrl . 'recuperar-clave-activar/';
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
        }


        if ($Mandante->mandante == '0') {
            //$baseurl='https://m.doradobet.com/menu/recover-password/';
        }

        /* convierte una cadena en formato URL, reemplazando caracteres especiales por códigos seguros. */
        $code = urlencode($code);


        /* Envía un correo de recuperación de contraseña utilizando un template personalizado. */
        try {

            $Clasificador = new Clasificador("", "RECOVERACCOUNT");
            $Template = new Template("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, $Usuario->idioma);
            $mensaje_txt = '';
            $mensaje_txt .= $Template->templateHtml;


            $mensaje_txt = str_replace("#enlace#", $baseurl . $code, $mensaje_txt);
            $mensaje_txt = str_replace("#usuario#", $Usuario->nombre, $mensaje_txt);
            $mensaje_txt = str_replace("#mandante#", $Mandante->descripcion, $mensaje_txt);

            $mtitle = 'Recuperacion de clave ' . $Mandante->nombre;
            $msubjetc = 'Recuperacion de clave ' . $Mandante->nombre;
            $destinatarios = $email;

            $envio = $ConfigurationEnvironment->EnviarCorreoVersion4($Usuario->login, 'noreply@doradobet.com', $Mandante->descripcion, 'Recuperacion de clave', 'mail_registro.php', 'Recuperaci&#243;n de clave', $mensaje_txt, $dominio, $compania, $color_email, $Usuario->mandante, false, false, $Usuario->paisId);


        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */


        }

    }

} else {

    $email = trim($email);
    $email = $ConfigurationEnvironment->DepurarCaracteres($email);
    // Se eliminan los emojis del correo electrónico mediante el método remove_emoji
    $email = $ConfigurationEnvironment->remove_emoji($email);

    // Se obtiene la dirección IP del usuario desde el objeto JSON
    $ip = $json->params->usuarioip;

    // Se obtiene el ID del sitio desde el objeto JSON y se convierte a minúsculas
    $site_id = $json->params->site_id;
    $site_id = strtolower($site_id);

    // Se crea una nueva instancia de la clase Usuario
    $Usuario = new Usuario();
    $Usuario->login = $email;

    // Se verifica si el usuario existe en la base de datos
    if (!$Usuario->exitsLogin(0)) {
        // Se lanza una excepción si el usuario no existe
        throw new Exception("No existe Usuario", "24");
    } else {
    // Se crea una nueva instancia de la clase Usuario con los parámetros proporcionados.
    $Usuario = new Usuario('', $email,'',$site_id);

        // Se crea una nueva instancia de la clase UsuarioLog.
        $UsuarioLog = new UsuarioLog();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp($ip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($ip);

        $UsuarioLog->setTipo("TOKENPASS");
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes('');
        $UsuarioLog->setValorDespues('');
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);


        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $usuariologId = $UsuarioLogMySqlDAO->insert($UsuarioLog);
        $UsuarioLogMySqlDAO->getTransaction()->commit();

        $code = ($ConfigurationEnvironment->encrypt($usuariologId . "_" . time(), $ENCRYPTION_KEY));

        $UsuarioLog->setValorAntes($code);
        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $usuariologId = $UsuarioLogMySqlDAO->update($UsuarioLog);
        $UsuarioLogMySqlDAO->getTransaction()->commit();

    // Se asigna el correo electrónico del usuario a la variable $email.
    $email = $Usuario->login;

        /* Lógica para redireccionamiento correctamente de las URL correspondiente a cada pais de su mandante */
        try {
            // Se crea una nueva instancia de la clase Mandante con el ID del mandante del usuario.
            $Mandante = new Mandante($Usuario->mandante);

            // Se crea una nueva instancia de la clase PaisMandante que busca en base de datos.
            $PaisMandante = new PaisMandante(null, $Usuario->mandante, $Usuario->paisId);

            // Validación para encontrar la URL en la columna base_url de base de datos.
            if (empty($PaisMandante->baseUrl)) {
                throw new Exception("No se encontró base_url para Mandante ID {$Usuario->mandante} y País ID {$Usuario->paisId}.", 300046);
            }

            // Asignamos la baseUrl al Mandante
            $Mandante->baseUrl = $PaisMandante->baseUrl;

            // Se establece la URL de recuperación de clave utilizando la URL base del mandante.
            $baseurl = $Mandante->baseUrl . 'recuperar-clave-validar/';

        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
        }


        if ($Mandante->mandante == '0') {
            //$baseurl='https://m.doradobet.com/menu/recover-password/';
        }
        $code = urlencode($code);
        // Se codifica el código encriptado para su uso en una URL.
        switch (strtolower($Usuario->idioma)) {


            case "pt":
                /**
                 * Arma el mensaje para el usuario que se registra.
                 * Se construye un mensaje de recuperación de contraseña en portugués,
                 * con un enlace para que el usuario pueda cambiar su contraseña.
                 */
                $mensaje_txt = "Recentemente, voc&ecirc; solicitou uma altera&ccedil;&atilde;o de senha, &eacute; muito f&aacute;cil, basta clicar no bot&atilde;o abaixo e voc&ecirc; pode alterar a senha.<br><br> ";
                $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;color: white;' href='" . $baseurl . "" . $code . "'><div style=\"
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

                if ($Usuario->mandante == 14) {
                    $mensaje_txt = '
                Recuperação de senha Lotosports

Para redefinir a sua senha, basta clicar no botão abaixo e escolher a opção "Mudar Senha".

' . "<a style='text-decoration: blink;color: white;' href='" . $baseurl . "" . $code . "'><div style=\"
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
\">MUDAR SENHA</div></a> <br><br>" . '


* Caso não obtenha sucesso ao redefinir a sua senha, por favor, contate um dos nossos atendentes em nosso Chat de Suporte, no canto inferior do nosso website.
                
                ';
                }

                $mtitle = 'Recupera&ccedil;&atilde;o de senha ' . $Mandante->nombre;
                $msubjetc = 'Recuperação de Senha ' . $Mandante->nombre;

                break;

            case "en":
                //Arma el mensaje para el usuario que se registra
                $mensaje_txt = "Recently you requested a password change, it is very easy, just click on the button below and you can change the password.<br><br> ";
                $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;color: white;' href='" . $baseurl . "" . $code . "'><div style=\"
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


                $msubjetc = 'Password recovery ' . $Mandante->nombre;
                $mtitle = 'Password recovery ' . $Mandante->nombre;
                break;

            default:

                //Arma el mensaje para el usuario que se registra
                $mensaje_txt = "Recientemente usted solicito un cambio de contrase&#241;a, es muy f&#225;cil, solo haz click en el siguiente boton y puede cambiar la contrase&#241;a.<br><br> ";
                $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;color: white;' href='" . $baseurl . "" . $code . "'><div style=\"
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


                $mtitle = 'Recuperaci&#243;n de clave ' . $Mandante->nombre;
                $msubjetc = 'Recuperacion de clave ' . $Mandante->nombre;
                break;
        }

        //Destinatarios
        $destinatarios = $email;

        if ($Usuario->mandante != 13 && $Usuario->mandante != 20) {

            //Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $mtitle, $mensaje_txt, $dominio, $compania, $color_email, $Mandante->mandante,false, false, $Usuario->paisId);

        } else {


            $mensaje_txt = "";

            try {
                $clasificador = new Clasificador("", "TEMEMPASS");

                $template = new Template('', $Mandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

            // Añade el HTML de la plantilla al mensaje.
            $mensaje_txt .= $template->templateHtml;

            } catch (Exception $e) {
                echo $e;
            }

            /**
             * Se inicializa un objeto Pais con el id del país del usuario.
             */

            $Pais = new Pais($Usuario->paisId);
            $devUrl = 'https://apidev.virtualsoft.tech/publicity/banners/GetPoublicityBanners?partner=';
            $prodUrl = 'https://publicapi.virtualplay.co/publicity/banners/GetPoublicityBanners?partner=';
            $url = $ConfigurationEnvironment->isDevelopment() ? $devUrl : $prodUrl;
            $url .= $Usuario->mandante . '&country=' . strtolower($Pais->iso) . '&language=' . strtolower($Usuario->idioma) . '&type=';
            $banner = '<a href="' . $url . '1"><img src="' . $url . '2" alt="banner"></a>';

            $mensaje_txt = str_replace("#IdUser#", $Usuario->usuarioId, $mensaje_txt);
            $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
            $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
            $mensaje_txt = str_replace("#link#", $baseurl . $code, $mensaje_txt);
            $mensaje_txt = str_replace('>#banners#<', '>' . $banner . '<', $mensaje_txt);

            //Arma el mensaje para el usuario que se registra
//    $mensaje_txt = "Recientemente usted solicito un cambio de contrase&#241;a, es muy f&#225;cil, solo haz click en el siguiente boton y puede cambiar la contrase&#241;a.<br><br> ";
//    $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;' href='https://cert.virtualsoft.tech/#/?action=reset_password&code=".$code."'><div style=\"
//    /* height: 60px; */
//    width: 200px;
//    margin: 10px auto;
//    background: #9E9E9E;
//    border-radius: 10px;
//    padding: 5px;
//    color: white !important;
//    text-transform: uppercase;
//    font-weight: bold;
//    text-align: center;
//\">Cambiar Contrase&#241;a</div></a> <br><br>";

//    $mensaje_txt = $mensaje_txt ."<p>Si no puede cambiar su contrase&#241;a, por favor p&#243;ngase en contacto con nosotros.</p><br><br>";
//    $mensaje_txt = $mensaje_txt . "<p style='text-align: left;'>Doradobet. </p>" . "";
//
//    //$email='danielftg@hotmail.com';
//    //Destinatarios
//    $destinatarios = $email;
//
//    //Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, 'noreply@doradobet.com', 'Doradobet', 'Recuperacion de clave', 'mail_registro.php', 'Recuperaci&#243;n de clave', $mensaje_txt, $dominio, $compania, $color_email, $Usuario->mandante);


            //Arma el mensaje para el usuario que se registra
//    $mensaje_txt = "Recientemente usted solicito un cambio de contrase&#241;a, es muy f&#225;cil, solo haz click en el siguiente boton y puede cambiar la contrase&#241;a.<br><br> ";
//    $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;' href='https://cert.virtualsoft.tech/#/?action=reset_password&code=".$code."'><div style=\"
//    /* height: 60px; */
//    width: 200px;
//    margin: 10px auto;
//    background: #9E9E9E;
//    border-radius: 10px;
//    padding: 5px;
//    color: white !important;
//    text-transform: uppercase;
//    font-weight: bold;
//    text-align: center;
//\">Cambiar Contrase&#241;a</div></a> <br><br>";

            //    $mensaje_txt = $mensaje_txt ."<p>Si no puede cambiar su contrase&#241;a, por favor p&#243;ngase en contacto con nosotros.</p><br><br>";
            //    $mensaje_txt = $mensaje_txt . "<p style='text-align: left;'>Doradobet. </p>" . "";
            //
            //    //$email='danielftg@hotmail.com';
            //    //Destinatarios
            //    $destinatarios = $email;
            //
            //    //Envia el mensaje de correo
            //    $envio = $ConfigurationEnvironment->EnviarCorreo($destinatarios, 'noreply@doradobet.com', 'Doradobet', 'Recuperacion de clave', 'mail_registro.php', 'Recuperaci&#243;n de clave', $mensaje_txt, $dominio, $compania, $color_email);
        }
        $response["data"] = array(
            "result" => 0
        );
    }
}

