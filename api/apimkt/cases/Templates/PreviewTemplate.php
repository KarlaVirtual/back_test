<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Template;
use Dompdf\Dompdf;

use Backend\dto\Usuario;

/**
 * Templates/PreviewTemplate
 *
 * Procesar parámetros de entrada y generar un correo o PDF en función del tipo de mensaje.
 *
 * Este recurso procesa los parámetros recibidos en la solicitud, transformando los datos y generando un mensaje de correo o
 * un archivo PDF según la configuración y las plantillas proporcionadas. Si el parámetro `isEmail` es igual a '1', se envía
 * un correo con un mensaje generado dinámicamente, de lo contrario, se genera un archivo PDF a partir de una plantilla.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada para procesar el mensaje o PDF.
 * @param string $params->CountryId : ID del país.
 * @param string $params->TypeName : Nombre del tipo.
 * @param string $params->Section : Sección a la que pertenece el mensaje.
 * @param string $params->TypeId : ID del tipo.
 * @param string $params->LanguageId : ID del idioma seleccionado.
 * @param object $params->Template : Plantilla HTML para el mensaje o documento PDF.
 * @param object $params->Template2 : Plantilla alternativa para el mensaje o PDF.
 * @param string $params->Message : Mensaje que se incluirá en el correo o PDF.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista (por ejemplo, "success").
 *  - *AlertMessage* (string): Contiene el mensaje de alerta que se mostrará.
 *  - *ModelErrors* (array): Retorna un array vacío en caso de no haber errores en el modelo.
 *  - *Data* (array): Contiene los datos del proceso, como el PDF generado.
 *  - *html* (string): El HTML generado para el mensaje o documento PDF.
 *  - *Pdf* (string): PDF generado en formato base64.
 *  - *PdfPOS* (string): PDF generado en formato base64, con posible variante de uso POS.
 *  - *status_messagePdf* (string): Base64 codificado del estado del mensaje PDF generado.
 *
 * Ejemplo de respuesta en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "Error al generar el PDF.";
 * $response["ModelErrors"] = ["Error en la plantilla"];
 * $response["Data"] = [];
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


if ($_REQUEST['isEmail'] == '1') {


    /* Decodifica datos recibidos, transformando entidades HTML y eliminando caracteres no deseados. */
    $params = file_get_contents('php://input');
    $params = base64_decode($params);
    $params = html_entity_decode($params);


    $unwanted_array = array(
        '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
        '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
        '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');

    /* transforma y decodifica parámetros JSON, extrayendo CountryId, TypeName y Section. */
    $params = strtr($params, $unwanted_array);

    $params = json_decode($params);
    $CountryId = $params->CountryId;
    $Name = $params->TypeName;
    $Section = $params->Section;

    /* Asignación y codificación de parámetros para manejar plantillas en formato JSON. */
    $Type = $params->TypeId;
    $LanguageSelect = $params->LanguageId;
    $TemplateArray = json_encode($params->Template);
    $TemplateArray2 = ($params->Template2);

    if ($TemplateArray2 != '') {
        $TemplateArray = $TemplateArray2;
    }

    /* Construye un botón en HTML con estilo para enviar un mensaje. */
    $Message = ($params->Message);
    $mandante = $_SESSION["mandante"];
//$TemplateArray = $params->Template;
    $mensaje_txt = ('<table id="u_content_button_1" class="u_content_button" style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td class="v-container-padding-padding" style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">
        
<div class="v-text-align" align="center">
    <a href="Hola" target="_blank"  style="word-break: break-word; word-wrap:break-word; mso-border-alt: none;border-top-width: 3px; border-top-style: dotted; border-top-color: #CCC; border-left-width: 3px; border-left-style: dotted; border-left-color: #CCC; border-right-width: 3px; border-right-style: dotted; border-right-color: #CCC; border-bottom-width: 3px; border-bottom-style: dotted; border-bottom-color: #CCC;">
      <span class="v-line-height v-padding" style="display:block;padding:10px 20px;line-height:120%;"><span style="font-size: 14px; line-height: 16.8px;">Button Text</span></span>
    </a>
</div>

      </td>
    </tr>
  </tbody>
</table>');


    /* verifica un usuario y modifica su login si es 'daniel'. */
    $Usuario = new Usuario($_SESSION["usuario"]);
    if (strtolower($Usuario->login) == 'daniel') {
        $Usuario->login = 'it@virtualsoft.tech';
    }

    $ConfigurationEnvironment = new ConfigurationEnvironment();


    /* envía un correo y establece una respuesta sin errores. */
    $envio = $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, '', '', 'Mensaje de prueba', '', '', $mensaje_txt, '', '', '', $mandante);


    $response["html"] = '';


    $response["HasError"] = false;

    /* inicializa un array de respuesta con alertas y errores. */
    $response["AlertType"] = "success";
    $response["ModelErrors"] = [];

    $response["Data"] = [];

} else {


    /* Decodifica datos de entrada en PHP y reemplaza entidades HTML por caracteres correspondientes. */
    $params = file_get_contents('php://input');
    $params = base64_decode($params);
    $params = html_entity_decode($params);
    $unwanted_array = array(
        '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
        '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
        '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');

    /* reemplaza valores, decodifica JSON y asigna plantillas a variables. */
    $params = strtr($params, $unwanted_array);

    $params = json_decode($params);

    $TemplateArray = json_encode($params->Template);
    $TemplateArray2 = ($params->Template2);


    /* asigna un valor a `$TemplateArray` y crea una instancia de Dompdf. */
    if ($TemplateArray2 != '') {
        $TemplateArray = $TemplateArray2;
    }
    $Message = ($params->Template);

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();

    /* Carga un template HTML y lo configura para imprimir con dompdf. */
    $Template = new Template();
    $Message .= $Template->templateHtmlCSSPrint;
    $Message = '<div class="bodytmp">' . $Message . '</div>';

    $dompdf->loadHtml($Message);

    // (Optional) Setup the paper size and orientation
    $width = 80; //mm!

    /* Código que establece dimensiones en puntos para generar un PDF con Dompdf. */
    $height = 150; //mm!

    //convert mm to points
    $paper_format = array(0, 0, ($width / 25.4) * 72, ($height / 25.4) * 72);
    $dompdf->setPaper($paper_format);

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser


    if (false) {


    // Instantiate canvas instance

        /* obtiene el tamaño de página y especifica una imagen de marca de agua. */
        $canvas = $dompdf->getCanvas();

    // Get height and width of page
        $w = $canvas->get_width();
        $h = $canvas->get_height();

    // Specify watermark image
        $imageURL = 'https://images.virtualsoft.tech/site/doradobet/logo-invoice.png';

        /* establece dimensiones, opacidad y posición para una imagen en un lienzo. */
        $imgWidth = 200;
        $imgHeight = 100;

    // Set image opacity
        $canvas->set_opacity(.5);

    // Specify horizontal and vertical position
        $x = (($w - $imgWidth) / 2);

        /* Crea una imagen centrada en un PDF utilizando coordenadas calculadas. */
        $y = (($h - $imgHeight) / 2);

    // Add an image to the pdf
        $canvas->image($imageURL, $x, $y, $imgWidth, $imgHeight);

    }


    /* Genera un PDF y lo codifica en base64 para su respuesta. */
    $data = $dompdf->output();

    $base64 = 'data:application/pdf;base64,' . base64_encode($data);

    $response["Pdf"] = base64_encode($data);
    $response["PdfPOS"] = base64_encode($data);

    /* asigna un mensaje y datos codificados a una respuesta JSON sin errores. */
    $response["html"] = $Message;

    $response["status_messagePdf"] = base64_encode($data);

    $response["HasError"] = false;
    $response["AlertType"] = "success";

    /* Inicializa arrays vacíos para errores de modelo y datos en la respuesta. */
    $response["ModelErrors"] = [];

    $response["Data"] = [];
}


