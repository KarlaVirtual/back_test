<?php

use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Registro;
use Backend\dto\Template;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\UsuarioLog2;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;

//Recepción de parámetros
$cookie = $json->params->ck;
$site_id = $json->params->site_id;
$site_id = strtolower($site_id);

$email = $json->params->email;
$code = $json->params->code;
$ConfigurationEnvironment = new ConfigurationEnvironment();
//Sanitización de parámetros
$email=trim($email);

$email = $ConfigurationEnvironment->DepurarCaracteres($email);
$email = $ConfigurationEnvironment->remove_emoji($email);

$email = preg_replace('/[^(\x20-\x7F)]*/','', $email);
$site_id = $ConfigurationEnvironment->DepurarCaracteres($site_id);
$code = $ConfigurationEnvironment->DepurarCaracteres($code);

$decode = $ConfigurationEnvironment->decrypt($code);
if($decode ==''){
    throw new Exception("Error en los parametros enviados", "100001");

}

$Usuario = new Usuario($decode);

if($Usuario->mandante != $site_id){
    throw new Exception("Error en los parametros enviados", "100001");

}

if($Usuario->login != $email){
    throw new Exception("Error en los parametros enviados", "100001");

}
if ($Usuario->verifCorreo == "N") {
    $Usuario->verifCorreo = "S";

    // Verifica si el mandante es 6 y actualiza el estado del usuario
    if($Usuario->mandante==6){
        $Usuario->estado = "A";
        $Usuario->estadoEsp = "A";
    }

    // Verifica si el mandante es 18 y actualiza el estado del usuario
    if($Usuario->mandante==18){
        $Usuario->estado = "A";
        $Usuario->estadoEsp = "A";
    }

    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
    $Transaction = $UsuarioMySqlDAO->getTransaction();

    // Crea un registro de log para el usuario
    $UsuarioLog2 = new UsuarioLog2();
    $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
    $UsuarioLog2->setUsuarioIp('');
    $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
    $UsuarioLog2->setUsuariosolicitaIp($Usuario->usuarioIp);
    $UsuarioLog2->setTipo('USUEMAIL');
    $UsuarioLog2->setEstado('A');
    $UsuarioLog2->setValorAntes($Usuario->login);
    $UsuarioLog2->setValorDespues($Usuario->login);
    $UsuarioLog2->setUsucreaId(0);

    $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
    $UsuarioLog2MySqlDAO->insert($UsuarioLog2);

    // Actualiza el usuario en la base de datos
    $UsuarioMySqlDAO->update($Usuario);

    // Confirma la transacción
    $Transaction->commit();

    // Crea un nuevo objeto Mandante basado en el site_id
    $Mandante = new Mandante($site_id);

    // Establece los destinatarios para el correo
    $destinatarios = $Usuario->login;

    $Mandante = new Mandante($Usuario->mandante);
    $Registro = new Registro('', $Usuario->usuarioId);
    $Clasificador = new Clasificador('', 'TEMPEMAILCONFIR');
    $Template = new Template('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));
    $html = $Template->templateHtml;

    $subject = '';
    /**
     * Este bloque de código se encarga de establecer el asunto del correo electrónico basado en el idioma del usuario,
     * reemplazar marcadores en un template HTML con datos del usuario y enviar un correo a través de la clase
     * ConfigurationEnvironment.
     */
    switch(strtoupper($Usuario->idioma)) {
        case 'PT':
            $subject = 'Validação de e-mail bem-sucedida na ';
            break;
        case 'EN':
            $subject = 'Successful email validation in ';
            break;
        default:
            $subject = 'Validación de email Exitosa en ';
            break;
    }
    $subject.= $Mandante->nombre;

    $ConfigurationEnvironment = new ConfigurationEnvironment();

    //Reemplazando etiquetas del template
    $html = str_replace('#userid#', $Usuario->usuarioId, $html); // Reemplaza el marcador #userid# con el ID de usuario
    $html = str_replace('#name#', $Registro->nombre1, $html); // Reemplaza el marcador #name# con el primer nombre del registro
    $html = str_replace('#identification#', $Registro->cedula, $html); // Reemplaza el marcador #identification# con la cédula del registro
    $html = str_replace('#lastname#', $Registro->apellido1, $html); // Reemplaza el marcador #lastname# con el primer apellido del registro
    $html = str_replace('#login#', $Usuario->login, $html); // Reemplaza el marcador #login# con el nombre de usuario
    $html = str_replace('#fullname#', $Usuario->nombre, $html); // Reemplaza el marcador #fullname# con el nombre completo del usuario

    //Envío de la notificación mediante email
    $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, '', '', $subject, '', $subject, $html, '', '', '', $Mandante->mandante);


    //Formateo de la respuesta
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array();
} else {
    //Formateo de la respuesta
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array();
}
