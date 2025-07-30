<?php
/**
 * Inicializa widgets HTML para la API 'partner'.
 *
 * Este script genera dinámicamente iframes para diferentes productos (deportes, casino, etc.)
 * basados en los parámetros proporcionados en la solicitud.
 *
 * @param string $_REQUEST["product"] Producto solicitado (por ejemplo, 'sport', 'casino').
 * @param string $_REQUEST["AuthToken"] Token de autenticación del usuario.
 * @param string $_REQUEST["containerID"] ID del contenedor donde se insertará el iframe.
 * @param string $_REQUEST["lang"] Idioma del widget.
 * @param string $_REQUEST["widget"] Configuración del widget.
 *
 * @return void Este script no devuelve valores, pero genera contenido HTML dinámico.
 */

/* Configura el manejo de errores y encabezados para solicitudes CORS en PHP. */
ini_set('display_errors', 'off');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

header('Access-Control-Allow-Headers: Content-Type');

/* configura métodos HTTP permitidos y carga dependencias necesarias para el backend. */
header('Access-Control-Allow-Methods: GET, POST,    OPTIONS,PUT');

require(__DIR__ . '../../vendor/autoload.php');

use Backend\cms\CMSProveedor;
use Backend\cms\CMSCategoria;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\Usuario;


$product = $_REQUEST["product"];

/* obtiene datos de solicitud y asigna un valor por defecto a $game. */
$token = $_REQUEST["AuthToken"];
$game = $_REQUEST["game"];
$selection = $_REQUEST["selection"];



if ($game == "") {
    $game = "''";
}

switch ($product)
{

case 'sport':

?>


/* inserta un iframe dinámico en un contenedor específico utilizando jQuery. */
$('#<?php echo $_REQUEST["containerID"];?>').append('<iframe src="http://devadmin.doradobet.com/api/api//partner_api/sports.php?containerID=<?=$_REQUEST["containerID"]?>&lang=<?=$_REQUEST["lang"]?>&AuthToken=<?=$_REQUEST["AuthToken"]?>&widget=<?=$_REQUEST["widget"]?>&skinName=<?=$_REQUEST["skinName"]?>&page=<?=$_REQUEST["page"]?>&gmt=-05&beforeInit=<?=$_REQUEST["beforeInit"]?>&product=<?=$_REQUEST["product"]?>&t=<?=$_REQUEST["t"]?>" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');


<?php

break;

case 'live':


?>

$('#<?php echo $_REQUEST["containerID"];?>').append('<iframe src="https://devadmin.doradobet.com/api/api//partner_api/sports.php?containerID=<?=$_REQUEST["containerID"]?>&lang=<?=$_REQUEST["lang"]?>&AuthToken=<?=$_REQUEST["AuthToken"]?>&widget=<?=$_REQUEST["widget"]?>&skinName=<?=$_REQUEST["skinName"]?>&page=<?=$_REQUEST["page"]?>&gmt=-05&beforeInit=<?=$_REQUEST["beforeInit"]?>&product=<?=$_REQUEST["product"]?>&t=<?=$_REQUEST["t"]?>" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');


<?php


break;

case 'virtualsports':

?>

/* Código jQuery que inserta un iframe basado en solicitudes de parámetros PHP. */
$('#<?php echo $_REQUEST["containerID"];?>').append('<iframe src="https://devadmin.doradobet.com/api/api//partner_api/sports.php?containerID=<?=$_REQUEST["containerID"]?>&lang=<?=$_REQUEST["lang"]?>&AuthToken=<?=$_REQUEST["AuthToken"]?>&widget=<?=$_REQUEST["widget"]?>&skinName=<?=$_REQUEST["skinName"]?>&page=<?=$_REQUEST["page"]?>&gmt=-05&beforeInit=<?=$_REQUEST["beforeInit"]?>&product=<?=$_REQUEST["product"]?>&t=<?=$_REQUEST["t"]?>" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

<?php

break;

case 'casino':

$token_string = "";


/* verifica un token y crea nuevos objetos de UsuarioToken. */
if ($token != "") {

    $UsuarioToken = new UsuarioToken($token, "1");
    $UsuarioToken2 = new UsuarioToken("", "0", $UsuarioToken->getUsuarioId());
    $token = $UsuarioToken2->getToken();
}

/* inserta un iframe en un contenedor especificado con un token generado. */
$string = "?token=" . $token;

?>

//<script class="ng-scope">
//<![CDATA[
$(document).ready(function () {

    $('#<?php echo $_REQUEST["containerID"];?>').append('<iframe src="https://dev.doradobet.com/products/casino/#/casino/<?php echo $string;?>" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

});


//]]>
//</script>
<?php

break;

case 'livecasino':

/* Genera un nuevo token de usuario basado en el anterior, si existe. */
$token_string = "";

if ($token != "") {

    $UsuarioToken = new UsuarioToken($token, "1");
    $UsuarioToken2 = new UsuarioToken($UsuarioToken->getUsuarioId(), "1");
    $token = $UsuarioToken2->getToken();
}

/* inserta un iframe en un contenedor específico con un token dinámico. */
$string = "?token=" . $token;

?>

//<script class="ng-scope">
//<![CDATA[
$(document).ready(function () {

    $('#<?php echo $_REQUEST["containerID"];?>').append('<iframe src="https://dev.doradobet.com/products/livecasino/#/livecasino/<?php echo $string;?>" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

});


//]]>
//</script>
<?php

break;
default:
    # code...
    break;
}
?>
