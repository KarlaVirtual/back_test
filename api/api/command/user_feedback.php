<?php


use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Pais;
use Backend\dto\Mandante;

use Backend\dto\Contacto;
use Backend\mysql\ContactoMySqlDAO;

/**
 * command/user_feedback
 *
 * Rellenar formulario con la información enviada por el usuario para la queja o reclamo
 *
 * @param object $json : Objeto que contiene la información del usuario referente a la queja o reclamo.
 * @param string $json ->params->establecimiento : Establecimiento comercial o canal donde se genera la Queja o Reclamo
 * @param string $json ->params->nombre : Nombre completo del usuario
 * @param string $json ->params->apellido1 : Primer apellido del usuario
 * @param string $json ->params->apellido2 : Segundo apellido del usuario
 * @param string $json ->params->tipo_doc : Tipo de documento del usuario
 * @param string $json ->params->cedula : Número de cedula del usuario
 * @param string $json ->params->direccion : Dirección del usuario
 * @param string $json ->params->telefono : Telefono del usuario
 * @param string $json ->params->celular : Telefono celular del usuario
 * @param string $json ->params->email : Correo electrónico del usuario
 * @param string $json ->params->notificado : Si el usuario será notificado por domicilio o correo electrónico
 * @param string $json ->params->tipo_contratado : Identificación del producto o servicio contratado
 * @param string $json ->params->detalle : Detalle del Reclamo o Queja
 * @param string $json ->params->monto : Monto del producto o servicio objeto de reclamo
 * @param string $json ->params->tipo_reclamacion : Si es Queja o reclamo
 * @param string $json ->params->detalle2 : Detalle del Reclamo o Queja
 * @param string $json ->params->pedido : Pedido
 * @param string $site_id = $json->params->site_id : Partner del usuario
 * @param string $body = $json->params->body : Body de la queja o reclamo
 * @param string $name = $json->params->name : Nombre del usuario
 * @param string $phone = $json->params->phone : Telefono del usuario
 * @param string $tipoForm = $json->params->tipoForm : Tipo de formulario
 *
 *
 * @return object El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor 0 en caso de exito
 *  - *data* (array): Contiene el mensaje de aprobación del proceso 1 en caso de exito.
 *
 *
 *
 * @throws Exception Inusual Detected (100001) - Si no se encuentra body, telefono, email o el sitio online de la petición
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/**
 * Obtiene los encabezados de la solicitud HTTP.
 *
 * Esta función extrae y devuelve todos los encabezados de la solicitud HTTP actual.
 * Los nombres de los encabezados se formatean correctamente reemplazando los guiones bajos
 * con espacios y aplicando formato de título.
 *
 * @return array Un array asociativo con los nombres de los encabezados como claves y sus valores correspondientes.
 * @throws no No contiene manejo de excepciones.
 */
function getRequestHeaders()
{
    $headers = array();
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}


/* obtiene el país del encabezado y normaliza el site_id a minúsculas. */
$headers = getRequestHeaders();
$countryCode = strtolower($headers["Cf-Ipcountry"]);


$site_id = $json->params->site_id;
$site_id = strtolower($site_id);



/* lanza una excepción si $site_id está vacío, luego accede a $body. */
if ($site_id == "") {
    throw new Exception("Inusual Detected0", "100001");
}


/* Obtención parámetros de la solicitud */
$body = $json->params->body;
$email = $json->params->email;
$name = $json->params->name;
$phone = $json->params->phone;
$country = $json->params->country ?: "";

$tipoForm = $json->params->tipoForm;

if ($tipoForm == "1") {

    $Mandante = new Mandante($site_id);

    $messageJson = str_replace("\u0022", "\\\\\"", json_encode($json->params, JSON_HEX_QUOT));
    $messageJson = str_replace(array("\r", "\n"), ' ', $messageJson);
    //$json->params= json_decode($messageJson);


    $Contacto = new Contacto();
    $Pais = new Pais('',strtoupper($country));

    $Contacto->setTelefono("");
    $Contacto->setMandante($Mandante->mandante);
    $Contacto->setEmail("");
    $Contacto->setNombre("");
    $Contacto->setMensaje($messageJson);
    $Contacto->setFechaCrea(date('Y-m-d H:i:s'));
    $Contacto->setTipo("1");
    $Contacto->setPaisId($Pais->paisId);

    $ContactoMySqlDAO = new ContactoMySqlDAO();

    $ContactoMySqlDAO->insert($Contacto);
    $ContactoMySqlDAO->getTransaction()->commit();


    $Pais = new Pais();

    $SkeepRows = 0;
    $MaxRows = 1000000;

    $json2 = '{"rules" : [{"field" : "ciudad.ciudad_id", "data": "' . $json->params->ciudad_id . '","op":"eq"}] ,"groupOp" : "AND"}';

    $paises = $Pais->getPaises("departamento.depto_nom,ciudad.ciudad_nom,pais.pais_nom", "asc", $SkeepRows, $MaxRows, $json2, true);
    $paises = json_decode($paises);
    $paises = $paises->data[0];

    $formulariotxt = "
 <form style='    background: white;
    max-width: 800px;
    padding: 10px 20px;
    border-radius: 5px;
    border: 2px solid #f0b709;' id=\"form_reclamaciones\" class=\"msform form_reclamaciones ng-pristine ng-valid\" name=\"form_reclamaciones\" method=\"\">

<div class=\"form-row-1 header-text\">
<div class=\"form-col \">
<div class=\"extra-info\"><span>Hoja de reclamacion No " . $Contacto->contactoId . "</span></div>
<div class=\"extra-info\"><span class=\"ng-binding\">Fecha de registro: " . date('d') . "-" . date('m') . "-" . date('Y') . "</span></div>
<div translate=\"\" class=\"form-col-title div-inline-block ng-scope\">INTERPLAY WORD S.A.C. (DORADOBET), identificado con RUC Nro. 20602190103, con domicilio legal en la calle Schell Nro. 374, Urb. Leuro, Distrito de Miraflores, provincia y departamento de Lima.
De conformidad a lo establecido en el Código de Protección y Defensa del Consumidor, ponemos a disposición del consumidor nuestro Libro de Reclamaciones para que puedas registrar tu queja o reclamo sobre alguno de nuestros productos o servicios brindados.</div>
</div>
</div>
<div class=\"form-row-1\" style=\"text-align: left;\">
<div class=\"form-col width-middle\" style='width: 48%;display: inline-block;'>
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Establecimiento comercial o canal donde se genera la Queja o Reclamo:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled value='" . str_replace('"', '', $json->params->establecimiento) . "'>
</div>
</div>
</div>
<div class=\"steps-r\"><span>1. Datos del Consumidor</span></div>
<div class=\"form-row-1\">
<div class=\"form-col\">
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Nombres:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled class=\"descripcion\" type=\"text\" id=\"nombre\" name=\"nombre\" value='" . str_replace('"', '', $json->params->nombre) . "' placeholder=\"Nombres:\" maxlength=\"100\">
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Apellido Paterno:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled class=\"descripcion\" type=\"text\" id=\"apellido1\" name=\"apellido1\" value='" . str_replace('"', '', $json->params->apellido1) . "' placeholder=\"Nombre completo:\" maxlength=\"100\">
</div>
</div>
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Apellido Materno:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled class=\"descripcion\" type=\"text\" id=\"apellido2\" name=\"apellido2\" value='" . str_replace('"', '', $json->params->apellido2) . "' placeholder=\"Nombre completo:\" maxlength=\"100\">
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div translate=\"\" class=\"form-col-title div-inline-block ng-scope\">Tipo de Documento de identificación:</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled value='" . ($json->params->tipo_doc == "C" ? "Cedula" : ($json->params->tipo_doc == "P" ? "Pasaporte" : "Cedula Extranjeria")) . "'>
</div>
</div>
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Numero de Identificación:</span> <span class=\"asterisc-form\">* </span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<i class=\"fa fa-address-card fa-lg\" aria-hidden=\"true\" style=\"margin-top: 10px;margin-left: 9px;display: block;position: absolute;float: left;color: #009688;font-size: 15px;\"></i>
<input disabled class=\"descripcion cedula-\" type=\"text\" id=\"cedula\" name=\"cedula\" placeholder=\"Numero de Identificación:\" value='" . str_replace('"', '', $json->params->cedula) . "' style=\"border-left: 30px solid #dedcdc;\" maxlength=\"15\">
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">País de Residencia:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled value='" . $paises->{"pais.pais_nom"} . "'>
</div>
</div>
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Departamento:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled value='" . $paises->{"departamento.depto_nom"} . "'>
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div class=\"form-col-title div-inline-block\" style='width: 48%;display: inline-block;'>
<span translate=\"\" class=\"ng-scope\">Provincia de Residencia:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\" style='width: 48%;display: inline-block;'>
<input disabled value='" . $paises->{"ciudad.ciudad_nom"} . "'>
</div>
</div>
<div class=\"form-col width-middle \">
<div translate=\"\" class=\"form-col-title div-inline-block ng-scope\" style='width: 48%;display: inline-block;'>Distrito:</div>
<div class=\"form-col-content div-input div-inline-block\" style='width: 48%;display: inline-block;'>
<i class=\"fa fa-map-marker fa-lg\" aria-hidden=\"true\" style=\"margin-top: 10px;margin-left: 9px;display: block;position: absolute;float: left;color: #009688;\"></i>
<input disabled class=\"descripcion\" type=\"text\" id=\"distrito\" name=\"distrito\" value='" . str_replace('"', '', $json->params->distrito) . "' style=\"border-left: 30px solid #dedcdc;\" placeholder=\"Distrito:\" maxlength=\"150\">
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col width-middle \" >
<div translate=\"\" class=\"form-col-title div-inline-block ng-scope\" style='width: 48%;display: inline-block;'>Dirección del Domicilio:</div>
<div class=\"form-col-content div-input div-inline-block\" style='width: 48%;display: inline-block;'>
<i class=\"fa fa-map-marker fa-lg\" aria-hidden=\"true\" style=\"margin-top: 10px;margin-left: 9px;display: block;position: absolute;float: left;color: #009688;\"></i>
<input disabled class=\"descripcion\" type=\"text\" id=\"direccion\" name=\"direccion\" value='" . str_replace('"', '', $json->params->direccion) . "' style=\"border-left: 30px solid #dedcdc;\" placeholder=\"Dirección del Domicilio:\" maxlength=\"150\">
</div>
</div>
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div translate=\"\" class=\"form-col-title div-inline-block ng-scope\" style='width: 48%;display: inline-block;'>Teléfono Fijo:</div>
<div class=\"form-col-content div-input div-inline-block\" style='width: 48%;display: inline-block;'>
<input disabled class=\"descripcion\" type=\"text\" id=\"telefono\" name=\"telefono\" value='" . str_replace('"', '', $json->params->telefono) . "' placeholder=\"Teléfono Fijo:\" maxlength=\"20\">
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col width-middle\">
<div class=\"form-col-title\"><span translate=\"\" class=\"ng-scope\" style='width: 48%;display: inline-block;'>Teléfono Celular:</span>
<span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input\" style='width: 48%;display: inline-block;'>
<i class=\"fa fa-mobile fa-lg\" aria-hidden=\"true\" style=\"margin-top: 8px;margin-left: 9px;display: block;position: absolute;float: left;color: #009688;font-size: 25px;\"></i>
<input disabled class=\"descripcion\" type=\"text\" id=\"celular\" name=\"celular\" value='" . str_replace('"', '', $json->params->celular) . "' style=\"border-left: 30px solid #dedcdc;\" placeholder=\"Teléfono Celular:\" onkeypress=\"return event.charCode >= 48 &amp;&amp; event.charCode <= 57\" maxlength=\"20\">
</div>
</div>
<div class=\"form-col width-middle email\">
<div class=\"form-col-title\"  style='width: 48%;display: inline-block;'><span translate=\"\" class=\"ng-scope\">Correo electrónico:</span>
<span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input\" style='width: 48%;display: inline-block;'>
<i class=\"fa fa-envelope fa-lg\" aria-hidden=\"true\" style=\"margin-top: 10px;margin-left: 9px;display: block;position: absolute;float: left;color: #009688;font-size: 15px;\"></i>
<input disabled class=\"descripcion\" type=\"text\" id=\"email\" name=\"email\" style=\"border-left: 30px solid #dedcdc;\" value='" . str_replace('"', '', $json->params->email) . "' placeholder=\"Correo electrónico:\" maxlength=\"150\">
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col  sexo\">
<div class=\"form-col-title div-inline-block\"><span translate=\"\" class=\"ng-scope\">A fin de recibir una respuesta sobre mi queja o reclamo, requiero que el mismo sea notificado por la siguiente vía:</span>
<span class=\"asterisc-form\">*</span></div>
<div class=\"form-col-content div-input div-inline-block text-center\">
<div style=\"width: 45%;display: inline-block; text-align: center\"><input disabled type=\"radio\" name=\"notificado\" " . ($json->params->notificado == "domicilio" ? "checked" : "") . " value=\"domicilio\" style=\"height: 25px!important;min-height: 0px !important;border-radius: 23px;padding: 0px !important;\" checked=\"\"> <i class=\"fa fa-map-marker fa-lg\"></i>&nbsp;&nbsp;<span translate=\"\" class=\"ng-scope\">Domicilio</span>
</div>
<div style=\"width: 45%;display: inline-block; text-align: center\"><input disabled translate=\"\" type=\"radio\" name=\"notificado\"  " . ($json->params->notificado == "email" ? "checked" : "") . "  value=\"email\" style=\"height: 25px!important;min-height: 0px !important;border-radius: 23px;padding: 0px !important;\" class=\"ng-scope\">
<i class=\"fa fa-envelope-o\"></i>&nbsp;&nbsp;<span translate=\"\" class=\"ng-scope\">Correo electrónico</span>
</div>

</div>
</div>
</div>
<div class=\"steps-r\"><span>2. Identificación del producto o servicio contratado </span></div>
<div class=\"form-col sexo\">
<div class=\"form-col-content div-input div-inline-block text-center\">
<div style=\"width: 45%;display: inline-block;text-align: center;\"><input disabled type=\"radio\" name=\"tipo_contratado\"  " . ($json->params->tipo_contratado == "producto" ? "checked" : "") . " value=\"producto\" style=\"height: 25px!important;min-height: 0px !important;border-radius: 23px;padding: 0px !important;\" > <i class=\"\"></i>&nbsp;&nbsp;<span translate=\"\" class=\"ng-scope\">Producto</span>
</div>
<div style=\"width: 45%;display: inline-block;text-align: center;\"><input disabled translate=\"\" type=\"radio\" name=\"tipo_contratado\"  " . ($json->params->tipo_contratado == "servicio" ? "checked" : "") . " value=\"servicio\" style=\"height: 25px!important;min-height: 0px !important;border-radius: 23px;padding: 0px !important;\" class=\"ng-scope\">
<i class=\"\"></i>&nbsp;&nbsp;<span translate=\"\" class=\"ng-scope\">Servicio</span>
</div>

</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col \">
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Detalle:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<textarea disabled class=\"descripcion\" type=\"text\" id=\"detalle\" name=\"detalle\" value='" . str_replace('"', '', $json->params->detalle) . "' placeholder=\"Detalle:\" cols=\"60\" rows=\"5\" maxlength=\"1000\">" . str_replace('"', '', $json->params->detalle) . "</textarea>
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col\">
<div translate=\"\" class=\"form-col-title div-inline-block ng-scope\">Monto del producto o servicio objeto de reclamo: *</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled class=\"descripcion\" type=\"text\" id=\"monto\" name=\"monto\" value='" . str_replace('"', '', $json->params->monto) . "' placeholder=\"Monto:\" maxlength=\"150\">
</div>
</div>
</div>
<div class=\"steps-r\"><span>3. Detalle de la Reclamación</span></div>
<div class=\"form-col sexo middle\">
<div class=\"form-col-title div-inline-block\"><span translate=\"\" class=\"ng-scope\">Tipo:</span>
<span class=\"asterisc-form\">*</span></div>
<div class=\"form-col-content div-input div-inline-block text-center\">
<div style=\"width: 45%;display: inline-block; text-align: center; vertical-align: top;\"><input disabled type=\"radio\" name=\"tipo_reclamacion\"  " . ($json->params->tipo_reclamacion == "reclamo" ? "checked" : "") . " value=\"reclamo\" style=\"height: 25px!important;min-height: 0px !important;border-radius: 23px;padding: 0px !important;\" > <i class=\"\"></i>&nbsp;&nbsp;<span translate=\"\" class=\"ng-scope\">Reclamo</span>
<span class=\"det\">Disconformidad relacionada a los productos o servicios.</span>
</div>
<div style=\"width: 45%;display: inline-block; text-align: center; vertical-align: top;\"><input disabled translate=\"\" type=\"radio\" name=\"tipo_reclamacion\"  " . ($json->params->tipo_reclamacion == "queja" ? "checked" : "") . " value=\"queja\" style=\"height: 25px!important;min-height: 0px !important;border-radius: 23px;padding: 0px !important;\" class=\"ng-scope\">
<i class=\"\"></i>&nbsp;&nbsp;<span translate=\"\" class=\"ng-scope\">Queja</span>
<span class=\"det\">Disconformidad no relacionada a los productos o servicios; o, malestar o descontento respecto a la atención al público.</span>
</div>

</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col \">
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Detalle del Reclamo o Queja:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<textarea disabled class=\"descripcion\" type=\"text\" id=\"detalle2\" name=\"detalle2\" value='" . str_replace('"', '', $json->params->detalle2) . "' placeholder=\"Detalle:\" cols=\"60\" rows=\"5\" maxlength=\"1000\">" . str_replace('"', '', $json->params->detalle2) . "</textarea>
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col \">
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Pedido:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<textarea disabled class=\"descripcion\" type=\"text\" id=\"pedido\" name=\"pedido\" value='" . str_replace('"', '', $json->params->pedido) . "' placeholder=\"Pedido :\" cols=\"60\" rows=\"5\" maxlength=\"1000\">" . str_replace('"', '', $json->params->pedido) . "</textarea>
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col div-complete\">
<div class=\"form-col-title\"></div>

</div>
</div>
<br><br>
</form>
 
 ";
    $textoAEnviar = "Estimado (a) señor(a) " . str_replace('"', '', $json->params->nombre) . "<br><br>

Es grato saludarle e infórmale a través de este medio que se ha registrado su Reclamo en nuestra página web con Hoja de Reclamación No " . $Contacto->contactoId . ", el día " . date('d') . " del mes " . date('m') . " del año " . date('Y') . ", el cual adjuntamos al presente correo.
<br><br>
Asu vez, mencionarle que en caso desee presentar documentación adicional a su Reclamo/Queja, usted se podrá comunicar con nosotros al correo contacto@doradobet.com indicando el numero de su Hoja de Reclamación.
<br><br>
Finalmente, informarle que de acuerdo con lo establecido en el artículo 24° del Código de Protección y Defensa al Consumidor y en el artículo 6° del Reglamento del Libro de Reclamaciones - D.S. Nro. 101-2022-PCM, contamos con un plazo de atención de 15 días hábiles.
<br><br>
Atentamente, 
<br><br>
INTERPLAY WORD S.A.C. - DORADOBET.

<br><br>

" . $formulariotxt;

    $msubjetc = "Libro de reclamaciones " . $Mandante->nombre . ' - ' . str_replace('"', '', $json->params->nombre);
    $mtitle = "Libro de reclamaciones";

    $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

    if ($Contacto->getMandante() == '0' && $Contacto->getPaisId() == '173') {

        //Destinatarios
        $destinatarios = str_replace('"', '', $json->params->email);

        //Envia el mensaje de correo
        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $textoAEnviar, "", "", "", $Mandante->mandante);

        //Destinatarios
        $destinatarios = "legal1@doradobet.com";

        //Envia el mensaje de correo
        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $textoAEnviar, "", "", "", $Mandante->mandante);

        //Destinatarios
        $destinatarios = "contacto@doradobet.com";

        //Envia el mensaje de correo
        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $textoAEnviar, "", "", "", $Mandante->mandante);


    }


    // Verifica si el mandante es '0' y el país es '60' para enviar un correo a un destinatario específico.
    if ($Contacto->getMandante() == '0' && $Contacto->getPaisId() == '60') {

        //Destinatarios
        $destinatarios = "servicioalcliente@doradobet.cr";

        // Se envía un correo electrónico utilizando la configuración del entorno especificado.
        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $textoAEnviar, "", "", "", $Mandante->mandante);


    }

    // Verifica si el mandante es '18' y el país es '173' para crear un formulario.
    if ($Contacto->getMandante() == '18' && $Contacto->getPaisId() == '173') {


        $formulariotxt = "
 <form style='    background: white;
    max-width: 800px;
    padding: 10px 20px;
    border-radius: 5px;
    border: 2px solid #f0b709;' id=\"form_reclamaciones\" class=\"msform form_reclamaciones ng-pristine ng-valid\" name=\"form_reclamaciones\" method=\"\">

<div class=\"form-row-1 header-text\">
<div class=\"form-col \">
<div class=\"extra-info\"><span>Hoja de reclamacion No " . $Contacto->contactoId . "</span></div>
<div class=\"extra-info\"><span class=\"ng-binding\">Fecha de registro: " . date('d') . "-" . date('m') . "-" . date('Y') . "</span></div>

</div>
</div>
<div class=\"form-row-1\" style=\"text-align: left;\">
<div class=\"form-col width-middle\" style='width: 48%;display: inline-block;'>
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Establecimiento comercial o canal donde se genera la Queja o Reclamo:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled value='" . str_replace('"', '', $json->params->establecimiento) . "'>
</div>
</div>
</div>
<div class=\"steps-r\"><span>1. Datos del Consumidor</span></div>
<div class=\"form-row-1\">
<div class=\"form-col\">
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Nombres:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled class=\"descripcion\" type=\"text\" id=\"nombre\" name=\"nombre\" value='" . str_replace('"', '', $json->params->nombre) . "' placeholder=\"Nombres:\" maxlength=\"100\">
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Apellido Paterno:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled class=\"descripcion\" type=\"text\" id=\"apellido1\" name=\"apellido1\" value='" . str_replace('"', '', $json->params->apellido1) . "' placeholder=\"Nombre completo:\" maxlength=\"100\">
</div>
</div>
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Apellido Materno:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled class=\"descripcion\" type=\"text\" id=\"apellido2\" name=\"apellido2\" value='" . str_replace('"', '', $json->params->apellido2) . "' placeholder=\"Nombre completo:\" maxlength=\"100\">
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div translate=\"\" class=\"form-col-title div-inline-block ng-scope\">Tipo de Documento de identificación:</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled value='" . ($json->params->tipo_doc == "C" ? "Cedula" : ($json->params->tipo_doc == "P" ? "Pasaporte" : "Cedula Extranjeria")) . "'>
</div>
</div>
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Numero de Identificación:</span> <span class=\"asterisc-form\">* </span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<i class=\"fa fa-address-card fa-lg\" aria-hidden=\"true\" style=\"margin-top: 10px;margin-left: 9px;display: block;position: absolute;float: left;color: #009688;font-size: 15px;\"></i>
<input disabled class=\"descripcion cedula-\" type=\"text\" id=\"cedula\" name=\"cedula\" placeholder=\"Numero de Identificación:\" value='" . str_replace('"', '', $json->params->cedula) . "' style=\"border-left: 30px solid #dedcdc;\" maxlength=\"15\">
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">País de Residencia:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled value='" . $paises->{"pais.pais_nom"} . "'>
</div>
</div>
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Departamento:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled value='" . $paises->{"departamento.depto_nom"} . "'>
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div class=\"form-col-title div-inline-block\" style='width: 48%;display: inline-block;'>
<span translate=\"\" class=\"ng-scope\">Provincia de Residencia:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\" style='width: 48%;display: inline-block;'>
<input disabled value='" . $paises->{"ciudad.ciudad_nom"} . "'>
</div>
</div>
<div class=\"form-col width-middle \">
<div translate=\"\" class=\"form-col-title div-inline-block ng-scope\" style='width: 48%;display: inline-block;'>Distrito:</div>
<div class=\"form-col-content div-input div-inline-block\" style='width: 48%;display: inline-block;'>
<i class=\"fa fa-map-marker fa-lg\" aria-hidden=\"true\" style=\"margin-top: 10px;margin-left: 9px;display: block;position: absolute;float: left;color: #009688;\"></i>
<input disabled class=\"descripcion\" type=\"text\" id=\"distrito\" name=\"distrito\" value='" . str_replace('"', '', $json->params->distrito) . "' style=\"border-left: 30px solid #dedcdc;\" placeholder=\"Distrito:\" maxlength=\"150\">
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col width-middle \" >
<div translate=\"\" class=\"form-col-title div-inline-block ng-scope\" style='width: 48%;display: inline-block;'>Dirección del Domicilio:</div>
<div class=\"form-col-content div-input div-inline-block\" style='width: 48%;display: inline-block;'>
<i class=\"fa fa-map-marker fa-lg\" aria-hidden=\"true\" style=\"margin-top: 10px;margin-left: 9px;display: block;position: absolute;float: left;color: #009688;\"></i>
<input disabled class=\"descripcion\" type=\"text\" id=\"direccion\" name=\"direccion\" value='" . str_replace('"', '', $json->params->direccion) . "' style=\"border-left: 30px solid #dedcdc;\" placeholder=\"Dirección del Domicilio:\" maxlength=\"150\">
</div>
</div>
<div class=\"form-col width-middle\" style='    width: 48%;
    display: inline-block;'>
<div translate=\"\" class=\"form-col-title div-inline-block ng-scope\" style='width: 48%;display: inline-block;'>Teléfono Fijo:</div>
<div class=\"form-col-content div-input div-inline-block\" style='width: 48%;display: inline-block;'>
<input disabled class=\"descripcion\" type=\"text\" id=\"telefono\" name=\"telefono\" value='" . str_replace('"', '', $json->params->telefono) . "' placeholder=\"Teléfono Fijo:\" maxlength=\"20\">
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col width-middle\">
<div class=\"form-col-title\"><span translate=\"\" class=\"ng-scope\" style='width: 48%;display: inline-block;'>Teléfono Celular:</span>
<span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input\" style='width: 48%;display: inline-block;'>
<i class=\"fa fa-mobile fa-lg\" aria-hidden=\"true\" style=\"margin-top: 8px;margin-left: 9px;display: block;position: absolute;float: left;color: #009688;font-size: 25px;\"></i>
<input disabled class=\"descripcion\" type=\"text\" id=\"celular\" name=\"celular\" value='" . str_replace('"', '', $json->params->celular) . "' style=\"border-left: 30px solid #dedcdc;\" placeholder=\"Teléfono Celular:\" onkeypress=\"return event.charCode >= 48 &amp;&amp; event.charCode <= 57\" maxlength=\"20\">
</div>
</div>
<div class=\"form-col width-middle email\">
<div class=\"form-col-title\"  style='width: 48%;display: inline-block;'><span translate=\"\" class=\"ng-scope\">Correo electrónico:</span>
<span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input\" style='width: 48%;display: inline-block;'>
<i class=\"fa fa-envelope fa-lg\" aria-hidden=\"true\" style=\"margin-top: 10px;margin-left: 9px;display: block;position: absolute;float: left;color: #009688;font-size: 15px;\"></i>
<input disabled class=\"descripcion\" type=\"text\" id=\"email\" name=\"email\" style=\"border-left: 30px solid #dedcdc;\" value='" . str_replace('"', '', $json->params->email) . "' placeholder=\"Correo electrónico:\" maxlength=\"150\">
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col  sexo\">
<div class=\"form-col-title div-inline-block\"><span translate=\"\" class=\"ng-scope\">A fin de recibir una respuesta sobre mi queja o reclamo, requiero que el mismo sea notificado por la siguiente vía:</span>
<span class=\"asterisc-form\">*</span></div>
<div class=\"form-col-content div-input div-inline-block text-center\">
<div style=\"width: 45%;display: inline-block; text-align: center\"><input disabled type=\"radio\" name=\"notificado\" " . ($json->params->notificado == "domicilio" ? "checked" : "") . " value=\"domicilio\" style=\"height: 25px!important;min-height: 0px !important;border-radius: 23px;padding: 0px !important;\" checked=\"\"> <i class=\"fa fa-map-marker fa-lg\"></i>&nbsp;&nbsp;<span translate=\"\" class=\"ng-scope\">Domicilio</span>
</div>
<div style=\"width: 45%;display: inline-block; text-align: center\"><input disabled translate=\"\" type=\"radio\" name=\"notificado\"  " . ($json->params->notificado == "email" ? "checked" : "") . "  value=\"email\" style=\"height: 25px!important;min-height: 0px !important;border-radius: 23px;padding: 0px !important;\" class=\"ng-scope\">
<i class=\"fa fa-envelope-o\"></i>&nbsp;&nbsp;<span translate=\"\" class=\"ng-scope\">Correo electrónico</span>
</div>

</div>
</div>
</div>
<div class=\"steps-r\"><span>2. Identificación del producto o servicio contratado </span></div>
<div class=\"form-col sexo\">
<div class=\"form-col-content div-input div-inline-block text-center\">
<div style=\"width: 45%;display: inline-block;text-align: center;\"><input disabled type=\"radio\" name=\"tipo_contratado\"  " . ($json->params->tipo_contratado == "producto" ? "checked" : "") . " value=\"producto\" style=\"height: 25px!important;min-height: 0px !important;border-radius: 23px;padding: 0px !important;\" > <i class=\"\"></i>&nbsp;&nbsp;<span translate=\"\" class=\"ng-scope\">Producto</span>
</div>
<div style=\"width: 45%;display: inline-block;text-align: center;\"><input disabled translate=\"\" type=\"radio\" name=\"tipo_contratado\"  " . ($json->params->tipo_contratado == "servicio" ? "checked" : "") . " value=\"servicio\" style=\"height: 25px!important;min-height: 0px !important;border-radius: 23px;padding: 0px !important;\" class=\"ng-scope\">
<i class=\"\"></i>&nbsp;&nbsp;<span translate=\"\" class=\"ng-scope\">Servicio</span>
</div>

</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col \">
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Detalle:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<textarea disabled class=\"descripcion\" type=\"text\" id=\"detalle\" name=\"detalle\" value='" . str_replace('"', '', $json->params->detalle) . "' placeholder=\"Detalle:\" cols=\"60\" rows=\"5\" maxlength=\"1000\">" . str_replace('"', '', $json->params->detalle) . "</textarea>
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col\">
<div translate=\"\" class=\"form-col-title div-inline-block ng-scope\">Monto del producto o servicio objeto de reclamo: *</div>
<div class=\"form-col-content div-input div-inline-block\">
<input disabled class=\"descripcion\" type=\"text\" id=\"monto\" name=\"monto\" value='" . str_replace('"', '', $json->params->monto) . "' placeholder=\"Monto:\" maxlength=\"150\">
</div>
</div>
</div>
<div class=\"steps-r\"><span>3. Detalle de la Reclamación</span></div>
<div class=\"form-col sexo middle\">
<div class=\"form-col-title div-inline-block\"><span translate=\"\" class=\"ng-scope\">Tipo:</span>
<span class=\"asterisc-form\">*</span></div>
<div class=\"form-col-content div-input div-inline-block text-center\">
<div style=\"width: 45%;display: inline-block; text-align: center; vertical-align: top;\"><input disabled type=\"radio\" name=\"tipo_reclamacion\"  " . ($json->params->tipo_reclamacion == "reclamo" ? "checked" : "") . " value=\"reclamo\" style=\"height: 25px!important;min-height: 0px !important;border-radius: 23px;padding: 0px !important;\" > <i class=\"\"></i>&nbsp;&nbsp;<span translate=\"\" class=\"ng-scope\">Reclamo</span>
<span class=\"det\">Disconformidad relacionada a los productos o servicios.</span>
</div>
<div style=\"width: 45%;display: inline-block; text-align: center; vertical-align: top;\"><input disabled translate=\"\" type=\"radio\" name=\"tipo_reclamacion\"  " . ($json->params->tipo_reclamacion == "queja" ? "checked" : "") . " value=\"queja\" style=\"height: 25px!important;min-height: 0px !important;border-radius: 23px;padding: 0px !important;\" class=\"ng-scope\">
<i class=\"\"></i>&nbsp;&nbsp;<span translate=\"\" class=\"ng-scope\">Queja</span>
<span class=\"det\">Disconformidad no relacionada a los productos o servicios; o, malestar o descontento respecto a la atención al público.</span>
</div>

</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col \">
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Detalle del Reclamo o Queja:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<textarea disabled class=\"descripcion\" type=\"text\" id=\"detalle2\" name=\"detalle2\" value='" . str_replace('"', '', $json->params->detalle2) . "' placeholder=\"Detalle:\" cols=\"60\" rows=\"5\" maxlength=\"1000\">" . str_replace('"', '', $json->params->detalle2) . "</textarea>
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col \">
<div class=\"form-col-title div-inline-block\">
<span translate=\"\" class=\"ng-scope\">Pedido:</span> <span class=\"asterisc-form\">*</span>
</div>
<div class=\"form-col-content div-input div-inline-block\">
<textarea disabled class=\"descripcion\" type=\"text\" id=\"pedido\" name=\"pedido\" value='" . str_replace('"', '', $json->params->pedido) . "' placeholder=\"Pedido :\" cols=\"60\" rows=\"5\" maxlength=\"1000\">" . str_replace('"', '', $json->params->pedido) . "</textarea>
</div>
</div>
</div>
<div class=\"form-row-1\">
<div class=\"form-col div-complete\">
<div class=\"form-col-title\"></div>

</div>
</div>
<br><br>
</form>
 
 ";
        $textoAEnviar = "Estimado (a) señor(a) " . str_replace('"', '', $json->params->nombre) . "<br><br>

Es grato saludarle e infórmale a través de este medio que se ha registrado su Reclamo en nuestra página web con Hoja de Reclamación No " . $Contacto->contactoId . ", el día " . date('d') . " del mes " . date('m') . " del año " . date('Y') . ", el cual adjuntamos al presente correo.
<br><br>
Atentamente, 
<br><br>
GANGABET
<br><br>

" . $formulariotxt;

        //Destinatarios
        $destinatarios = str_replace('"', '', $json->params->email);

        //Envia el mensaje de correo
        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $textoAEnviar, "", "", "", $Mandante->mandante);

        //Destinatarios
        $destinatarios = "gerencia@gangabet.com";


    }

    if ($Contacto->getPaisId() == '16') {


        //Destinatarios
        $destinatarios = "tecnologiatemp3@gmail.com";

        //Envia el mensaje de correo
        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $textoAEnviar, "", "", "", $Mandante->mandante);


    }

    $response = array(
        "code" => 0,
        "data" => array(
            "result" => 1
        ),
        "result" => 1
    );

} else {
    // Verifica que el cuerpo del mensaje, el correo electrónico y el nombre no estén vacíos
    if ($body != "" && $email != "" && $name != "") {

        /* depura y elimina emojis de la variable mensaje */
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $body = $ConfigurationEnvironment->remove_emoji($body);


        // Crea una nueva instancia de Mandante con el site_id proporcionado
        $Mandante = new Mandante($site_id);
        $Pais = new Pais('',strtoupper($country));

        // Crea una nueva instancia de Contacto
        $Contacto = new Contacto();

        // Establece valores para el objeto Contacto
        $Contacto->setTelefono($phone);
        $Contacto->setMandante($Mandante->mandante);
        $Contacto->setEmail($email);
        $Contacto->setNombre($name);
        $Contacto->setMensaje($body);
        $Contacto->setFechaCrea(date('Y-m-d H:i:s'));
        $Contacto->setPaisId($Pais->paisId);


        // Crea una nueva instancia del Data Access Object para Contacto
        $ContactoMySqlDAO = new ContactoMySqlDAO();

        // Inserta el objeto Contacto en la base de datos
        $ContactoMySqlDAO->insert($Contacto);
        $ContactoMySqlDAO->getTransaction()->commit();

        // Verifica si el mandante es específico
        if($Mandante->mandante =='6'){
            $msubjetc="Contactenos ".$Mandante->nombre . ' - ' . $json->params->nombre;
            $mtitle = "Contactenos";

            // Se define el contenido del correo a enviar, incluyendo información del contacto
            $textoAEnviar = "<div style='text-align: left'>Han enviado una nueva solicitud de Contactenos<br><br>

Email: " . $Contacto->email . "
<br><br>
Nombre: " . $Contacto->nombre . "
<br><br>
Telefono: " . $Contacto->telefono . "
<br><br>
Mensaje: " . $Contacto->mensaje . "
<br><br>
Fecha: " . $Contacto->fechaCrea . "
<br><br><div style='font-weight: bold;text-transform: uppercase;text-align: center;'>" . $Mandante->nombre . "</div></div>
";

            // Destinatarios que recibirán el correo
            $destinatarios = "info@netabet.com.mx";

            // Se crea una instancia de ConfigurationEnvironment para poder enviar correos
            $ConfigurationEnvironment = new ConfigurationEnvironment();
            // Envía el mensaje de correo utilizando la configuración establecida
            $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $textoAEnviar, "", "", "", $Mandante->mandante);

        }
        if ($Contacto->getMandante() == '0' && $Contacto->getPaisId() == '60') {
            $msubjetc = "Contactenos " . $Mandante->nombre . ' - ' . $json->params->nombre;
            $mtitle = "Contactenos";

            // Se define el contenido del correo a enviar, incluyendo información del contacto
            $textoAEnviar = "<div style='text-align: left'>Han enviado una nueva solicitud de Contactenos<br><br>

Email: " . $Contacto->email . "
<br><br>
Nombre: " . $Contacto->nombre . "
<br><br>
Telefono: " . $Contacto->telefono . "
<br><br>
Mensaje: " . $Contacto->mensaje . "
<br><br>
Fecha: " . $Contacto->fechaCrea . "
<br><br><div style='font-weight: bold;text-transform: uppercase;text-align: center;'>" . $Mandante->nombre . "</div></div>
";

            // Destinatarios del correo
            $destinatarios = "servicioalcliente@doradobet.cr";

            // Creación de un nuevo entorno de configuración
            $ConfigurationEnvironment = new ConfigurationEnvironment();
            // Envío del mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $textoAEnviar, "", "", "", $Mandante->mandante);

        }
        if ($Mandante->mandante == '12' || $Mandante->mandante == '15' || $Mandante->mandante == '20') {
            $msubjetc = "Contactenos " . $Mandante->nombre . ' - ' . $json->params->nombre;
            $mtitle = "Contactenos";

            // Preparación del texto a enviar
            $textoAEnviar = "<div style='text-align: left'>Han enviado una nueva solicitud de Contactenos<br><br>

Email: " . $Contacto->email . "
<br><br>
Nombre: " . $Contacto->nombre . "
<br><br>
Telefono: " . $Contacto->telefono . "
<br><br>
Mensaje: " . $Contacto->mensaje . "
<br><br>
Fecha: " . $Contacto->fechaCrea . "
<br><br><div style='font-weight: bold;text-transform: uppercase;text-align: center;'>" . $Mandante->nombre . "</div></div>
";


            if ($Mandante->mandante == '12') {
                $destinatarios = "soporte@powerbet.la";
            }
            if ($Mandante->mandante == '15') {
                $destinatarios = "soporte@hondubet.com";
            }
            if ($Mandante->mandante == '20') {
                $destinatarios = "soporte@sivarbet.com";
            }


            $ConfigurationEnvironment = new ConfigurationEnvironment();
            //Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $textoAEnviar, "", "", "", $Mandante->mandante);

        }

        $response = array(
            "code" => 0,
            "data" => array(
                "result" => 1
            ),
            "result" => 1
        );

    } else {
        throw new Exception("Inusual Detected", "100001");

    }
}
