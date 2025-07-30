<?php

use Backend\dto\BonoInterno;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\LealtadDetalle;
use Backend\dto\LealtadHistorial;
use Backend\dto\LealtadInterna;
use Backend\dto\MandanteDetalle;
use Backend\dto\Registro;
use Backend\dto\Template;
use Backend\dto\UsuarioLealtad;
use Backend\dto\UsuarioMandante;
use Backend\dto\Clasificador;
use Backend\dto\Usuario;
use Backend\dto\Ciudad;
use Backend\dto\PuntoVenta;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioMensajecampana;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\LealtadInternaMySqlDAO;
use Backend\mysql\UsuarioMensajecampanaMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioLealtadMySqlDAO;
use Backend\utils\RedisConnectionTrait;
use Dompdf\Dompdf;

/**
 * activate_bonuses
 *
 * Activa un bono de lealtad para un usuario, validando las condiciones y generando un PDF si es necesario.
 * @param int $params->bonusId ID del bono a activar.
 * @param int $params->betShop ID de la tienda de apuestas.
 * @param array $params->Form Datos del formulario.
 *
 * @return array $response Respuesta en formato JSON con los siguientes valores:
 *  - int $code Código de respuesta.
 *  - string $rid ID de la solicitud.
 *  - array $data Datos adicionales, incluyendo el PDF generado.
 *
 * @throws Exception Si el bono ya fue redimido o no está disponible.
 */

//code...
//error_reporting(E_ALL);
//ini_set("display_errors","ON");


/* Se crea un usuario mandante y se obtienen lealtad y betShop IDs del JSON. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

$lealtadId = $json->params->bonusId;
$betShopId = $json->params->betShop;

/* extrae parámetros JSON y establece una conexión con Redis. */
$Form = $json->params->Form;


try {
    $Clasificador = new Clasificador("","LOYALTYVERIFICATION"); /*Se realiza una instancia de la clase Clasificador para obtener el id*/
    $MandanteDetalle = new MandanteDetalle("",$Usuario->mandante,$Clasificador->getClasificadorId(),$Usuario->paisId,"A");/*Se realiza una instancia de la clase MandanteDetalle para obtener los detalles del partner*/
    $RequiereVerificacion = $MandanteDetalle->getValor(); /*Obtenemos si el partner necesita verificacion como requisito para reclamar premios leealtad*/

}catch (Exception $e){

}

$UsuarioVerificado = false; /*Inicializamos en false si el usuario esta verificado o no*/


/*Se realiza verificacion, en caso del usuario estar verificado asignamos true y en caso contrario asignamos false */
if ($Usuario->verificado == "S" || ($Usuario->verifcedulaAnt == "S" && $Usuario->verifcedulaPost == "S")) {
    $UsuarioVerificado = true;
} else {
    $UsuarioVerificado = false;
}


/**
 * Verifica si el usuario cumple con los requisitos para canjear puntos de lealtad.
 *
 * - Si el valor de `$RequiereVerificacion` es "A" (indica que se requiere verificación)
 *   y el usuario no está verificado (`$UsuarioVerificado == false`), se lanza una excepción.
 *
 * @throws Exception no cumple requisito de verificación
 */

if($RequiereVerificacion == "A" and $UsuarioVerificado == false){
    throw new exception("¡Atención!
Para canjear tus puntos, primero debes verificar tu cuenta.
Completa tu verificación y sigue disfrutando de tus beneficios.",300155);
}


$redisParam = ['ex' => 300];

$redisPrefix = "LealtadID+" . $lealtadId;

$redis = RedisConnectionTrait::getRedisInstance(true);


/* Verifica caché en Redis y ejecuta un script si hay un error. */
if ($redis != null) {

    $cachedKey = $redisPrefix . 'UID+' . $UsuarioMandante->getUsuarioMandante();
    $cachedValue = ($redis->get($cachedKey));
}

if (!empty($cachedValue)) {
    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'LEALTADPROBLEM " . $cachedKey . "' '#dev' > /dev/null & ");
  //throw new Exception("Error en los parametros enviados", "100001");
}

/* establece un valor en Redis si la conexión es válida y obtiene nombres de JSON. */
if ($redis != null) {

    $redis->set($redisPrefix . 'UID+' . $UsuarioMandante->getUsuarioMandante(), '1', $redisParam);
}

$Names = $json->params->Names;

/* Extrae datos de un objeto JSON a variables en PHP. */
$Surnames = $json->params->Surnames;
$Identification = $json->params->Identification;
$Phone = $json->params->Phone;
$City = $json->params->City;
$Province = $json->params->Province;
$Address = $json->params->Address;

/* Asignación de variables y creación de objetos en un contexto de historial de lealtad. */
$Team = $json->params->Team;


$lealtadHistorial = new LealtadHistorial();
$id_premio = $lealtadHistorial->lealtadHistorialId;


$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

/* inicializa variables y objetos para gestionar datos del usuario y ciudad. */
$Registro = new Registro('', $Usuario->usuarioId);
$UsuarioId = $Usuario->usuarioId;
$PuntosUsuario = $Usuario->getPuntosLealtad();
$CiudadMySqlDAO = new CiudadMySqlDAO();
$Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);


$SkeepRows = "";
$OrderedItem = "";
$MaxRows = "";

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asigna valores predeterminados a variables si están vacías o no definidas. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100;
}


/* Consulta usuarios leales con condiciones específicas, procesando resultados en formato JSON. */
$json2 = '{"rules" : [{"field" : "usuario_lealtad.estado", "data": "L","op":"eq"},{"field" : "lealtad_interna.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';

$UsuarioLealtad = new UsuarioLealtad();
$UsuariosLealtad = $UsuarioLealtad->getUsuarioLealtadCustom(" usuario_lealtad.*,lealtad_interna.* ", "usuario_lealtad.usulealtad_id", "asc", $SkeepRows, $MaxRows, $json2, true);


$UsuariosLealtad = json_decode($UsuariosLealtad);


/* Verifica si hay usuarios leales y asigna su ID a una variable. */
if (oldCount($UsuariosLealtad) > 0) {
    $codeUsuarioLealtad = $UsuariosLealtad->data[0]->{'usuario_lealtad.lealtad_id'};
}

$detalles = array(
    "Depositos" => 0,
    "DepositoEfectivo" => false,
    "MetodoPago" => 0,
    "ValorDeposito" => 0,
    "PaisPV" => 0,
    "DepartamentoPV" => 0,
    "CiudadPV" => 0,
    "PuntoVenta" => 0,
    "PaisUSER" => $Usuario->paisId,
    "DepartamentoUSER" => $Ciudad->deptoId,
    "CiudadUSER" => $Registro->ciudadId,
    "MonedaUSER" => $Usuario->moneda,


    "Form" => $Form,
    "betShopId" => $betShopId,
    "Names" => $Names,
    "Surnames" => $Surnames,
    "Identification" => $Identification,
    "Phone" => $Phone,
    "City" => $City,
    "Province" => $Province,
    "Address" => $Address,
    "Team" => $Team

);


try {


    /* Crea un objeto UsuarioLealtad con identificación y lealtad especificadas. */
    $UsuarioLealtad = new UsuarioLealtad("", $UsuarioId, $lealtadId);

    if($UsuarioLealtad->getEstado() == "R"){

        /* Variable booleana que indica si una acción de redención es posible o no. */
        $puedeRedimir=false;
        if($UsuarioMandante->mandante == '0'){

            $puedeRedimir = true;


        }

        /* Lanza una excepción si no se puede redimir el regalo. */
        if (!$puedeRedimir) {
            throw new Exception('Regalo ya redimido.', '10009');

        }
    }

} catch (Exception $e) {
    /* Manejo de excepciones en PHP: vuelve a lanzar errores específicos mediante su código. */

    if ($e->getCode() == 10009) {
        throw $e;
    }
}


/* Se crean objetos para manejar lealtad interna y transacciones en la base de datos. */
$LealtadInterna = new LealtadInterna();
$LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO();


$detalles = json_decode(json_encode($detalles));

$Transaction = $LealtadInternaMySqlDAO->getTransaction();


/* Se verifica si se agrega una lealtad y se actualiza una variable boolean. */
$Respuesta = (object)$LealtadInterna->agregarLealtad($lealtadId, '', $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);


$existeLealtad = false;

if ($Respuesta->WinLealtad) {

    $existeLealtad = true;

}

if ($existeLealtad) {
    $status_messagePDF = '';

    try {


        /* Inicializa objetos para gestionar la lealtad y configuración del usuario. */
        $configurationEnviroment = new ConfigurationEnvironment();
        $UsuarioLealtad = new UsuarioLealtad($Respuesta->WinLealtadId);
        $password = $UsuarioLealtad->usulealtadId;
        $IdPuntoVenta = $UsuarioLealtad->puntoventaentrega;

        $LealtadInterna2 = new LealtadInterna($lealtadId);


        /* Asigna "Tercero" o "Propio" a $tipo_premio2 según puntoventaPropio. */
        $tipo_premio2 = $LealtadInterna2->puntoventaPropio;

        if ($tipo_premio2 == 0) {
            $tipo_premio2 = "Tercero";
        } else {
            $tipo_premio2 = "Propio";
        }

//$Usuariolealtad2 = new UsuarioLealtad($password);
//$IdPuntoVenta = $Usuariolealtad2->puntoventaentrega;


        if ($IdPuntoVenta != "" && $IdPuntoVenta != "null" && $IdPuntoVenta != null && $IdPuntoVenta != 0) {


            /* crea un objeto y recupera información de un punto de venta y usuario. */
            $puntoVenta = new puntoventa("", $IdPuntoVenta);
            $DireccionPV = $puntoVenta->direccion;
            $Ciudad = $puntoVenta->ciudadId;
            $City = new Ciudad($Ciudad);
            $deliveryCity = $City->ciudadNom;

            $password = $configurationEnviroment->encryptCusNum(intval($UsuarioLealtad->usulealtadId));


            /* Crea una tabla HTML para mostrar un mensaje de estado en PDF. */
            $status_message = "";

            $method = "pdf";
            $status_message = '<table style="width:430px;height: 355px;/* border:1px solid black; */">
    <tbody><tr><td align="center" valign="top"><div style="height:5px;">&nbsp;</div></td></tr>';

            $status_message = $status_message . "<tr>";

            /* Genera un mensaje de estado en formato HTML sobre un reclamo de premio. */
            $status_message = $status_message . "<td align='center' valign='top'>";
            $status_message = $status_message . "<font style='padding-left:5px;text-align:center;font-size:14px;'>Pdf Regalo</font>";
            $status_message = $status_message . "</td>";
            $status_message = $status_message . "</tr><tr><td align='center' valign='top'><div style='height:2px;'>&nbsp;</div></td></tr>";


            $status_message .= '
    <tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">Reclamo premio</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Id de premio.:&nbsp;&nbsp;' . $lealtadId . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Password:&nbsp;&nbsp;' . $password . '</font></td></tr></tbody></table>';


            /* crea un template HTML utilizando un clasificador y un usuario. */
            try {
//code...
                $clasificador = new Clasificador("", "PREMLEALTAD");

                $Template = new Template("", $UsuarioMandante->getMandante(), $clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), strtolower($Usuario->idioma));


// $Template = new Template('',$Usuario->mandante,$Clasificador->clasificadorId,$Usuario->paisId,strtolower($Usuario->idioma));

                $html_barcode .= $Template->templateHtml;
                $html_barcode .= $Template->templateHtmlCSSPrint;
                $html_barcode .= '<style>.bodytmp {width: 300px !important;}</style>';


            } catch (Exception $e) {
                /* Captura excepciones en PHP y muestra el mensaje de error. */

                echo $e;
            }

// $html_barcode .= ("Id premio: "." ".$lealtadId) . "<br>";
// $html_barcode .= ("Contraseña premio:"." ".$password);

// $clasificador = new Clasificador("","PREMLEALTAD");


            /* Sustituye marcadores en HTML por valores de usuario y lealtad correspondientes. */
            $html_barcode = str_replace("#IdPrice#", $lealtadId, $html_barcode);
            $html_barcode = str_replace("#billingNoteNumber#", $UsuarioLealtad->usulealtadId, $html_barcode); // N° nota de cobro preguntar si va ser correspondiente al campo usuariolealtad o lealtad interna
// $html_barcode = str_replace("#PasswordPrice#",$UsuarioLealtad->usulealtadId,$html_barcode);


            $html_barcode = str_replace("#numberCustomer#", $Usuario->usuarioId, $html_barcode);

            /* Sustituye marcadores en una plantilla HTML con datos de usuario y lealtad. */
            $html_barcode = str_replace("#nameCustomer#", $Usuario->nombre, $html_barcode);
            $html_barcode = str_replace("#Date#", $UsuarioLealtad->fechaCrea, $html_barcode); // preguntar si esta fecha viene de la tabla lealtad interna o usuario_lealtad


            $html_barcode = str_replace("#Key#", $password, $html_barcode);
            $html_barcode = str_replace("#PrizeToClaim#", $LealtadInterna2->nombre, $html_barcode);


            /* Reemplaza marcadores en un HTML para generación de código de barras usando Dompdf. */
            $html_barcode = str_replace("#PrizeType#", $tipo_premio2, $html_barcode);
            $html_barcode = str_replace("#deliveryCity#", $deliveryCity, $html_barcode);
            $html_barcode = str_replace("#Premises#", $DireccionPV, $html_barcode);
// hasta aca esta funcionando


// $html_barcode = str_replace("#deliveryCity#",$Ciudad,$html_barcode);
// $html_barcode = str_replace("#Premises#",$DireccionPV,$html_barcode);


            $dompdf = new Dompdf();

            /* Carga HTML para un código de barras y define dimensiones del papel en puntos. */
            $dompdf->loadHtml($html_barcode);


            $width = 90; //mm!
            $height = 150; //mm!
//convert mm to points
            $paper_format = array(0, 0, ($width / 25.4) * 72, ($height / 25.4) * 72);

            /* configura y genera un PDF usando Dompdf en PHP. */
            $dompdf->setPaper($paper_format);

            $dompdf->render();

// Output the generated PDF to Browser

// Instantiate canvas instance
            $canvas = $dompdf->getCanvas();


// Get height and width of page

            /* Se obtienen ancho y alto del lienzo, luego se especifica la imagen de marca de agua. */
            $w = $canvas->get_width();
            $h = $canvas->get_height();


// Specify watermark image
            $imageURL = $Mandante->logoPdf;

            /* Código establece dimensiones de imagen y ajusta la opacidad del lienzo a 30%. */
            $imgWidth = 200;
            $imgHeight = 100;


// Set image opacity
            $canvas->set_opacity(.3);


            /* establece la opacidad del canvas y calcula la posición de una imagen. */
            $canvas->set_opacity(.2);
            $imgHeight = 70;


            $x = (($w - $imgWidth) / 2);
            $y = (($h - $imgHeight) / 2) - 30;


            /* genera un PDF en base64 y lo almacena en variables. */
            $data = $dompdf->output();

            $base64 = 'data:application/pdf;base64,' . base64_encode($data);

            $status_messagePDF = base64_encode($data);

            $status_messageHTML = $html_barcode;


            /* Se crea un objeto y se decodifican detalles antes de obtener una transacción. */
            $LealtadInterna = new LealtadInterna();

            $detalles = json_decode(json_encode($detalles));

            $Transaction = $LealtadInternaMySqlDAO->getTransaction();


        } elseif ($LealtadInterna->tipoPremio == '0' && $LealtadInterna->puntoventaPropio == '0' && $IdPuntoVenta == null) {


            /* cifra un número de usuario y prepara un mensaje de estado en HTML. */
            $password = $configurationEnviroment->encryptCusNum(intval($UsuarioLealtad->usulealtadId));
            $status_message = "";

            $method = "pdf";
            $status_message = '<table style="width:430px;height: 355px;/* border:1px solid black; */">
    <tbody><tr><td align="center" valign="top"><div style="height:5px;">&nbsp;</div></td></tr>';


            /* Construye un mensaje HTML con detalles sobre un premio y su acceso. */
            $status_message = $status_message . "<tr>";
            $status_message = $status_message . "<td align='center' valign='top'>";
            $status_message = $status_message . "<font style='padding-left:5px;text-align:center;font-size:14px;'>Pdf Regalo</font>";
            $status_message = $status_message . "</td>";
            $status_message = $status_message . "</tr><tr><td align='center' valign='top'><div style='height:2px;'>&nbsp;</div></td></tr>";


            $status_message .= '
    <tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">Reclamo premio</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Id de premio.:&nbsp;&nbsp;' . $lealtadId . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Password:&nbsp;&nbsp;' . $password . '</font></td></tr></tbody></table>';


            /* crea un template HTML con estilos para imprimir códigos de barras. */
            try {
//code...
                $clasificador = new Clasificador("", "PREMSINTIENDA");

                $Template = new Template("", $UsuarioMandante->getMandante(), $clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), strtolower($Usuario->idioma));


// $Template = new Template('',$Usuario->mandante,$Clasificador->clasificadorId,$Usuario->paisId,strtolower($Usuario->idioma));

                $html_barcode .= $Template->templateHtml;
                $html_barcode .= $Template->templateHtmlCSSPrint;
                $html_barcode .= '<style>.bodytmp {width: 300px !important;}</style>';


            } catch (Exception $e) {
                /* Manejo de excepciones en PHP, mostrando el mensaje de error capturado. */

                echo $e;
            }

// $html_barcode .= ("Id premio: "." ".$lealtadId) . "<br>";
// $html_barcode .= ("Contraseña premio:"." ".$password);

// $clasificador = new Clasificador("","PREMLEALTAD");


            /* reemplaza marcadores en un HTML por valores específicos de usuarios. */
            $html_barcode = str_replace("#IdPrice#", $lealtadId, $html_barcode);
            $html_barcode = str_replace("#billingNoteNumber#", $UsuarioLealtad->usulealtadId, $html_barcode); // N° nota de cobro preguntar si va ser correspondiente al campo usuariolealtad o lealtad interna
// $html_barcode = str_replace("#PasswordPrice#",$UsuarioLealtad->usulealtadId,$html_barcode);


            $html_barcode = str_replace("#numberCustomer#", $Usuario->usuarioId, $html_barcode);

            /* Reemplaza marcadores en HTML con datos de usuario, fecha, clave y premio. */
            $html_barcode = str_replace("#nameCustomer#", $Usuario->nombre, $html_barcode);
            $html_barcode = str_replace("#Date#", $UsuarioLealtad->fechaCrea, $html_barcode); // preguntar si esta fecha viene de la tabla lealtad interna o usuario_lealtad


            $html_barcode = str_replace("#Key#", $password, $html_barcode);
            $html_barcode = str_replace("#PrizeToClaim#", $LealtadInterna2->nombre, $html_barcode);


            /* Reemplaza marcadores en HTML con variables y genera un documento PDF usando Dompdf. */
            $html_barcode = str_replace("#PrizeType#", $tipo_premio2, $html_barcode);

// $html_barcode = str_replace("#deliveryCity#",$deliveryCity,$html_barcode);
// $html_barcode = str_replace("#Premises#",$DireccionPV,$html_barcode);
// hasta aca esta funcionando

// $html_barcode = str_replace("#deliveryCity#",$Ciudad,$html_barcode);
// $html_barcode = str_replace("#Premises#",$DireccionPV,$html_barcode);

            $dompdf = new Dompdf();

            /* carga HTML para crear un documento en PDF con dimensiones específicas. */
            $dompdf->loadHtml($html_barcode);


            $width = 90; //mm!
            $height = 150; //mm!
//convert mm to points
            $paper_format = array(0, 0, ($width / 25.4) * 72, ($height / 25.4) * 72);

            /* Configura el formato de papel y genera un PDF para visualizar en el navegador. */
            $dompdf->setPaper($paper_format);

            $dompdf->render();

// Output the generated PDF to Browser

// Instantiate canvas instance
            $canvas = $dompdf->getCanvas();


// Get height and width of page

            /* Cálcula el ancho y alto del lienzo, y especifica la imagen de marca de agua. */
            $w = $canvas->get_width();
            $h = $canvas->get_height();


// Specify watermark image
            $imageURL = $Mandante->logoPdf;

            /* Establece dimensiones de la imagen y ajusta su opacidad al 30%. */
            $imgWidth = 200;
            $imgHeight = 100;


// Set image opacity
            $canvas->set_opacity(.3);


            /* establece la opacidad de un lienzo y calcula coordenadas centradas. */
            $canvas->set_opacity(.2);
            $imgHeight = 70;


            $x = (($w - $imgWidth) / 2);
            $y = (($h - $imgHeight) / 2) - 30;


            /* Genera un PDF, lo codifica en base64 y prepara mensajes HTML y PDF. */
            $data = $dompdf->output();

            $base64 = 'data:application/pdf;base64,' . base64_encode($data);

            $status_messagePDF = base64_encode($data);


            $status_messageHTML = $html_barcode;


            /* Se crea una instancia y se recupera una transacción utilizando un DAO en PHP. */
            $LealtadInterna = new LealtadInterna();

            $detalles = json_decode(json_encode($detalles));

            $Transaction = $LealtadInternaMySqlDAO->getTransaction();
        }


    } catch (Exception $e) {
        /* Captura excepciones y relanza si el código de error es 10009. */

        if ($e->getCode() == 10009) {
            throw $e;
        }
    }
}
if ($existeLealtad) {


    /* Se crea un arreglo de respuesta JSON con estado y datos sobre un PDF. */
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => true,
        "pdf" => $status_messagePDF

    );


    if ($UsuarioMandante->usuarioMandante == 4171521) {
        try {

            /* Se crean objetos relacionados con lealtad y bonos, y se procesa un valor. */
            $LealtadInterna = new LealtadInterna($lealtadId);
            $BonoInterno = new BonoInterno($LealtadInterna->bonoId);

            $LealtadDetalle = new LealtadDetalle('', $lealtadId, 'MARKETINGCAMPAING');
            $valueId = trim($LealtadDetalle->valor, "[]");
            $valueId = trim($valueId, '"');

            /* separa una cadena en elementos usando comas como delimitadores y los convierte en un array. */
            $valueId = explode(',', $valueId);
            foreach ($valueId as $value) {

                /* Se crean objetos y se verifica un tipo de bono para asignar una respuesta. */
                $UsuarioMensaje = new UsuarioMensaje();
                $UsuarioMensajecampana = new UsuarioMensajecampana($value);

                $full_name = $UsuarioMandante->nombres . ' ' . $UsuarioMandante->apellidos;

                if ($BonoInterno->tipo == 8) $response['freeSpin'] = true;

//Si es FreeCasino, FreeBet o FreeSpin notificará al usuario sobre la redención del bono

                /* inserta un mensaje si el tipo de bono coincide con criterios específicos. */
                $bonusTypesForNotification = [5, 6, 8];
                if (in_array($BonoInterno->tipo, $bonusTypesForNotification) && isset($UsuarioMensajecampana)) {
//Inserción de mensaje
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = $UsuarioMensajecampana->body;
                    $UsuarioMensaje->msubject = $UsuarioMensajecampana->descripcion;
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->tipo = $UsuarioMensajecampana->tipo;
                    $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                    $UsuarioMensaje->fechaExpiracion = date('Y-m-d H:i:s', strtotime('+1 week'));
                    $UsuarioMensaje->usumencampanaId = $value;
                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                }
            }


        } catch (Exception $ex) {
            /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque catch. */

        }
    }


} else {
    /* lanza excepción si el bono no está disponible y maneja una respuesta. */


    throw new Exception("Bono no disponible", "30020");

    if ($Respuesta->estado == "R") {
        $response = array();
        $response["code"] = 200;
        $response["rid"] = $json->rid;
        $response["data"] = array(
            "reason" => "Regalo ya redimido."
        );
    }
}






