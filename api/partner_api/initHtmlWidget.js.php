<?php
/**
 * Inicializa widgets HTML para la API 'partner'.
 *
 * Este script genera dinámicamente widgets para diferentes productos (deportes, casino, etc.)
 * y maneja la autenticación del usuario mediante tokens.
 *
 * @param string $_REQUEST["product"] Producto solicitado (por ejemplo, 'sport', 'casino').
 * @param string $_REQUEST["AuthToken"] Token de autenticación del usuario.
 * @param string $_REQUEST["containerID"] ID del contenedor donde se insertará el widget.
 * @param string $_REQUEST["mobile"] Indica si el dispositivo es móvil ('true' o 'false').
 *
 * @return void Este script no devuelve valores, pero genera contenido HTML dinámico.
 */

/* Configuración de encabezados HTTP para permitir CORS en el servidor PHP. */
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');

require(__DIR__ . '../../vendor/autoload.php');

use Backend\cms\CMSProveedor;
use Backend\cms\CMSCategoria;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\Usuario;
use Backend\dto\Proveedor;
use Backend\mysql\UsuarioTokenMySqlDAO;

/* Asigna valores de solicitudes HTTP a variables y maneja entradas vacías. */
$product = $_REQUEST["product"];
$token = $_REQUEST["AuthToken"];
$game = $_REQUEST["game"];
$selection = $_REQUEST["selection"];
$mobile = ($_REQUEST["mobile"]);

if ($product == "") {
    $product = $_REQUEST["page"];
}

/* Se definen URLs para scripts de apuestas deportivas en diferentes entornos. */
$urlItainment = '"https://sports-itainment-uat.biahosted.com/StaticResources/betinactionApi.js"';
$urlItainmentMobile = '"https://msports-itainment-uat.biahosted.com/StaticResources/betinactionApi.js"';
$urlItainment2 = '"https://sb1client-altenar-stage.biahosted.com/frontend/static/BetinactionApi.js"';

$urlItainment = '"https://sb1client-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
$urlItainmentMobile = '"https://sb1client-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';

/* Se crea una nueva instancia de la clase ConfigurationEnvironment para su uso. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
if ($ConfigurationEnvironment->isDevelopment()) {
    if ($product == "sport" || $product == "live") {


        /* Define URLs y asigna identificadores para diferentes skins y un código de billetera. */
        $urlItainment = '"https://sb1client-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
        $urlItainmentMobile = '"https://sb1client-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';

        $skinId = "betlatam";
        $skinId = "doradobet";

        $walletCode = '030817';



        /* Se asigna un nuevo valor numérico a la variable $token_string en PHP. */
        $token_string = "332489910271384";
        $token_string = 215651983771;

        if ($token != "" && $token != "anonymous") {
            if ($ConfigurationEnvironment->isDevelopment()) {


                /* Código que crea instancias de UsuarioToken, UsuarioMandante y Proveedor en PHP. */
                $UsuarioToken = new UsuarioToken($token, "1");
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
                //$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());


                $Proveedor = new Proveedor("", "ITN");

                /* Se crea un token de usuario basado en un proveedor y un mandante específico. */
                $UsuarioTokenSite = new UsuarioToken('', '0', $UsuarioMandante->usumandanteId);


                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioTokenSite->getUsuarioId());

                } catch (Exception $e) {


                    /* Crea e inserta un nuevo token de usuario si se cumple una condición específica. */
                    if ($e->getCode() == 21) {

                        $UsuarioToken = new UsuarioToken();
                        $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                        $UsuarioToken->setCookie('0');
                        $UsuarioToken->setRequestId('0');
                        $UsuarioToken->setUsucreaId(0);
                        $UsuarioToken->setUsumodifId(0);
                        $UsuarioToken->setUsuarioId($UsuarioTokenSite->getUsuarioId());
                        $UsuarioToken->setToken($UsuarioToken->createToken());
                        $UsuarioToken->setSaldo(0);

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                        $UsuarioTokenMySqlDAO->getTransaction()->commit();


                    } else {
                        /* Maneja una excepción, lanzándola nuevamente si no se cumple una condición previa. */

                        throw $e;
                    }
                }

                /* Se obtiene un token de usuario llamando al método `getToken()` del objeto `$UsuarioToken`. */
                $token_string = $UsuarioToken->getToken();
            } else {
                /* crea objetos de usuario y obtiene un token para procesamiento. */

                $UsuarioToken = new UsuarioToken($token, "0");
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                $token_string = $Usuario->tokenItainment;

            }
        }
    } else {

        /* Se inicializa una variable vacía para almacenar un token en formato de cadena. */
        $token_string = "";

        if ($token != "") {


            /* Código que maneja tokens de usuario para autenticación y acceso. */
            $UsuarioToken = new UsuarioToken($token, "1");

            try {
                $UsuarioToken2 = new UsuarioToken($UsuarioToken->getUsuarioId(), "0");
                $token = $UsuarioToken2->getToken();

            } catch (Exception $e) {


                /* Crea y guarda un nuevo token de usuario si el código de error es 21. */
                if ($e->getCode() == 21) {
                    $UsuarioToken2 = new UsuarioToken();
                    $UsuarioToken2->setProveedorId('0');
                    $UsuarioToken2->setCookie('0');
                    $UsuarioToken2->setRequestId('0');
                    $UsuarioToken2->setUsucreaId(0);
                    $UsuarioToken2->setUsumodifId(0);
                    $UsuarioToken2->setUsuarioId($UsuarioToken->getUsuarioId());
                    $UsuarioToken2->setToken($UsuarioToken2->createToken());
                    $UsuarioToken2->setSaldo(0);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken2);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    $token = $UsuarioToken2->getToken();

                } else {
                    /* lanza una excepción si se encuentra un error en el bloque anterior. */

                    throw $e;
                }

            }


        }

    }


} else {

    /* Código PHP que asigna URLs y gestiona tokens para productos de apuestas deportivas. */
    $urlItainment = '"https://sb1client-altenar.biahosted.com/static/AltenarSportsbook.js"';
    $urlItainmentMobile = '"https://sb1client-altenar.biahosted.com/static/AltenarSportsbook.js"';

    if ($product == "sport" || $product == "live") {

        $skinId = "betlatam";
        $skinId = "doradobet2";

        $walletCode = '030817';
        $walletCode = '190582';


        $UsuarioToken = new UsuarioToken($token, "0");

        $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        $token_string = $Usuario->tokenItainment;
    } else {


        /* Variable inicializada para almacenar una cadena de tokens. */
        $token_string = "";

        if ($token != "") {

            /* Se crea un objeto UsuarioToken y se obtiene un nuevo token basado en su ID. */
            $UsuarioToken = new UsuarioToken($token, "1");

            try {
                $UsuarioToken2 = new UsuarioToken($UsuarioToken->getUsuarioId(), "0");
                $token = $UsuarioToken2->getToken();

            } catch (Exception $e) {


                /* Crea y almacena un nuevo token de usuario si se cumple una condición específica. */
                if ($e->getCode() == 21) {
                    $UsuarioToken2 = new UsuarioToken();
                    $UsuarioToken2->setProveedorId('0');
                    $UsuarioToken2->setCookie('0');
                    $UsuarioToken2->setRequestId('0');
                    $UsuarioToken2->setUsucreaId(0);
                    $UsuarioToken2->setUsumodifId(0);
                    $UsuarioToken2->setUsuarioId($UsuarioToken->getUsuarioId());
                    $UsuarioToken2->setToken($UsuarioToken2->createToken());
                    $UsuarioToken2->setSaldo(0);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken2);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    $token = $UsuarioToken2->getToken();

                } else {
                    /* lanza una excepción si se cumple la condición en el bloque "else". */

                    throw $e;
                }

            }
        }

    }


    if ($token_string != '') {

        /* Código que determina URLs y skinId basado en el tipo de aplicación del usuario. */
        $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());

        if ($UsuarioMandante->getMandante() == '0') {
            if ($typeApp == 1) {
                $urlItainment = '"https://operamini-altenar.biahosted.com/static/AltenarSportsbook.js"';
                $urlItainmentMobile = '"https://operamini-altenar.biahosted.com/static/AltenarSportsbook.js"';
                $skinId = "doradobetlite";

                $urlItainment = '';
                $urlItainmentMobile = '';

            } else {
                $urlItainment = '"https://sb1client-altenar.biahosted.com/static/AltenarSportsbook.js"';
                $urlItainmentMobile = '"https://sb1client-altenar.biahosted.com/static/AltenarSportsbook.js"';

                $skinId = "doradobet";
                $urlItainment = '';
                $urlItainmentMobile = '';

            }
        }
    }
}
//
//$skinId = "wplay2";

/* Código que define variables de logo y verifica si es un dispositivo móvil. */
$ismobile = false;

$logoLoading = '<?=$logoLoading?>';
$logoLoading = 'https://images.doradobet.com/site/doradobet/logo-doradobet.png';
$logoLoading = 'https://images.doradobet.com/site/doradobet/logo-horizontal.png';

if ($mobile == "true") {
    $ismobile = true;
}


/* Asigna valores predeterminados a variables si están vacías en PHP. */
if ($game == "") {
    $game = "''";
}

if ($product == "") {
    $product = "sport";
}


/* verifica si jQuery está ya cargado y establece una URL para móviles. */
if ($ismobile) {
    $urlItainment = $urlItainmentMobile;
}

?>

var list = document.scripts;
var existe = false;

for (var i = 0; i < list.length; i++) {
    var list2 = list[i];
    if (list2.src === "https://code.jquery.com/jquery-1.11.2.min.js") {
        //list2.remove();
        existe = true;
    }
}


/* verifica si jQuery no existe y lo añade dinámicamente al documento. */
if (!existe) {

    var s = document.createElement("script");
    s.type = "text/javascript";
    s.src = "https://code.jquery.com/jquery-1.11.2.min.js";
    //$("head").append(s);
    var head = document.getElementsByTagName('head')[0];
    head.appendChild(s);
}

<?
switch ($product) {

case 'sport2':

?>



<?php

break;
case 'sport':

?>

document.getElementById('<?php echo $_REQUEST["containerID"];?>').parentNode.innerHTML += ('<div id="div-wait">  <div id="div-preloader" ng-hide="conf" class="div-preloader" style="\n' +
    '    position: fixed;\n' +
    '    width: 100%;\n' +
    '    height: 100%;\n' +
    '    z-index: 100000000;\n' +
    '    background: black;\n' +
    '    position: absolute;\n' +
    '    width: 100%;\n' +
    '    height: 100%;\n' +
    '    left: 0;\n' +
    '">\n' +
    '    <style>\n' +
    '      @keyframes preloaderSpinner {\n' +
    '        0% {\n' +
    '          -webkit-transform: rotate(0deg);\n' +
    '          -ms-transform: rotate(0deg);\n' +
    '          transform: rotate(0deg)\n' +
    '        }\n' +
    '        to {\n' +
    '          -webkit-transform: rotate(1turn);\n' +
    '          -ms-transform: rotate(1turn);\n' +
    '          transform: rotate(1turn)\n' +
    '        }\n' +
    '      }\n' +
    '\n' +
    '      @-webkit-keyframes preloaderShadow {\n' +
    '        0% {\n' +
    '          opacity: 0\n' +
    '        }\n' +
    '        50% {\n' +
    '          opacity: .6\n' +
    '        }\n' +
    '        to {\n' +
    '          opacity: 0\n' +
    '        }\n' +
    '      }\n' +
    '\n' +
    '      @keyframes preloaderShadow {\n' +
    '        0% {\n' +
    '          opacity: 0\n' +
    '        }\n' +
    '        50% {\n' +
    '          opacity: .6\n' +
    '        }\n' +
    '        to {\n' +
    '          opacity: 0\n' +
    '        }\n' +
    '      }\n' +
    '\n' +
    '      div#div-preloader.ng-hide {\n' +
    '        opacity: 0;\n' +
    '        display: block !important;\n' +
    '        /* -webkit-animation: flipOutX 2.5s; */\n' +
    '        /* animation: flipOutX 2.5s; */\n' +
    '        transition: all linear 0.5s;\n' +
    '        z-index: -1000 !important;\n' +
    '      }\n' +
    '    </style>\n' +
    '\n' +
    '    <div class="preloader cover cover_abs row_h-center row_v-center">\n' +
    '      <div class="preloader__icon" style="\n' +
    '    /* width: 220px; */\n' +
    '    /* height: 220px; */\n' +
    '    /* position: relative; */\n' +
    '">\n' +
    '        <div class="preloader__spinner-img"></div>\n' +
    '        <div class="preloader__crown-img" style="\n' +
    '"></div><i class="preloader__spinner" style="\n' +
    'width: 190px;\n' +
    'height: 190px;\n' +
    'margin-left: -95px;\n' +
    'margin-top: -110px;\n' +
    '    transform-origin: center;\n' +
    '    position: absolute;\n' +
    '    top: 50%;\n' +
    '    left: 50%;\n' +
    '    display: inline-block;\n' +
    '    animation: preloaderSpinner 1.6s linear infinite;\n' +
    '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 321 320"><linearGradient id="SVGID_spinner" x1="25.575" x2="271.272" y1="71.231" y2="277.395" gradientUnits="userSpaceOnUse"><stop offset=".155" stop-color="#FFEAB5"></stop><stop offset=".541" class="stop_opacity-8" stop-color="#FAB50B"></stop><stop offset="1" class="stop_opacity" stop-color="#000000"></stop></linearGradient><path fill="url(#SVGID_spinner)" d="M282.1 258.3c-28.6 35.2-72.3 57.8-121.1 57.8h-1.1c-86 0-156-70-156-156.1C3.8 74 73.8 3.9 159.9 3.9h1.1V0h-1.2C71.6 0-.1 71.8-.1 160s71.8 160 160 160h1.1c50.2 0 95-23.2 124.4-59.4.3-.3.5-.7.5-.7l-3.2-2.3s-.4.5-.6.7z"></path></svg></i>\n' +
    '        <i class="preloader__crown" style="\n' +
    '        width: 115px;\n' +
    '            height: 115px;\n' +
    '            margin-left: -54px;\n' +
    '            margin-top: -67px;\n' +
    '    transform-origin: center;\n' +
    '    position: absolute;\n' +
    '    top: 50%;\n' +
    '    left: 50%;\n' +
    '    display: inline-block;\n' +
    '    /* animation: preloaderShadow 2.6s linear infinite; */\n' +
    '"><div style="\n' +
    '  background: url(<?=$logoLoading?>) no-repeat 0 50%/contain;\n' +
    '  width: 100%;\n' +
    '  height: 100%;\n' +
    '  /* position: absolute; */\n' +
    '  /* left: 50%; */\n' +
    '  /* top: 50%; */\n' +
    '"></div></i>\n' +
    '        <i class="preloader__shadow" style="\n' +
    '    width: 115px;\n' +
    '    height: 115px;\n' +
    '    margin-left: -54px;\n' +
    '    margin-top: -67px;\n' +
    '    transform-origin: center;\n' +
    '    position: absolute;\n' +
    '    top: 50%;\n' +
    '    left: 50%;\n' +
    '    display: inline-block;\n' +
    '    z-index: -1;\n' +

    '"><div style="\n' +
    '  background: url(<?=$logoLoading?>) no-repeat 0 50%/contain;\n' +
    '  width: 100%;\n' +
    '  height: 100%;\n' +
    '  /* position: absolute; */\n' +
    '  /* left: 50%; */\n' +
    '  /* top: 50%; */\n' +
    '"></div></i> </div>\n' +
    '    </div>\n' +
    '  </div></div>');



var d = document.getElementById('<?php echo $_REQUEST["containerID"];?>').style.display = "none";


var list = document.scripts;
var existe = false;

for (var i = 0; i < list.length; i++) {
    var list2 = list[i];
    if (list2.src === <?= $urlItainment ?>) {
        //list2.remove();
        existe = true;
    }
}

if (!existe) {

    var s = document.createElement("script");
    s.type = "text/javascript";
    s.src = <?= $urlItainment ?>;
    var head = document.getElementsByTagName('head')[0];
    head.appendChild(s);


    var options = {
        token: '<?php echo $token_string; ?>',
        skinid: '<?=$skinId?>',
        walletcode: '<?=$walletCode?>',
        full: true,
        page: 'prelive',
        lang: 'es-ES',
        fixed: false<?php if($ismobile){?>,
        mobile: true<?php } ?>
    };

    getScript(<?= $urlItainment ?>, function () {
        var BIA = new AltenarSportsbook('#<?php echo $_REQUEST["containerID"];?>', options);


        var refreshId = setInterval(function () {
            if (BIA.iframe.isConnected) {

                var d = document.getElementById('<?php echo $_REQUEST["containerID"];?>').style.display = "block";
                //d.className += " otherclass";

                //$('#<?php echo $_REQUEST["containerID"];?>').css("display", "block");

                var elem = document.getElementById("div-wait");
                elem.parentNode.removeChild(elem);

                clearInterval(refreshId);

            }
        }, 3000);


    });


} else {


    var options = {
        token: '<?php echo $token_string; ?>',
        skinid: '<?=$skinId?>',
        walletcode: '<?=$walletCode?>',
        full: true,
        page: 'prelive',
        lang: 'es-ES',
        fixed: false<?php if($ismobile){?>,
        mobile: true<?php } ?>
    };
    var BIA = new AltenarSportsbook('#<?php echo $_REQUEST["containerID"];?>', options);


    var refreshId = setInterval(function () {
        if (BIA.iframe.isConnected) {

            var d = document.getElementById('<?php echo $_REQUEST["containerID"];?>').style.display = "block";
            //d.className += " otherclass";

            //$('#<?php echo $_REQUEST["containerID"];?>').css("display", "block");

            var elem = document.getElementById("div-wait");
            elem.parentNode.removeChild(elem);

            clearInterval(refreshId);

        }
    }, 3000);
}


// BIA.setSelection(<?php echo $selection; ?>);


//]]>
//</script>



<?php

break;

case 'live':


?>

document.getElementById('<?php echo $_REQUEST["containerID"];?>').parentNode.innerHTML += ('<div id="div-wait">  <div id="div-preloader" ng-hide="conf" class="div-preloader" style="\n' +
    '    position: fixed;\n' +
    '    width: 100%;\n' +
    '    height: 100%;\n' +
    '    z-index: 100000000;\n' +
    '    background: black;\n' +
    '    position: absolute;\n' +
    '    width: 100%;\n' +
    '    height: 100%;\n' +
    '    left: 0;\n' +
    '">\n' +
    '    <style>\n' +
    '      @keyframes preloaderSpinner {\n' +
    '        0% {\n' +
    '          -webkit-transform: rotate(0deg);\n' +
    '          -ms-transform: rotate(0deg);\n' +
    '          transform: rotate(0deg)\n' +
    '        }\n' +
    '        to {\n' +
    '          -webkit-transform: rotate(1turn);\n' +
    '          -ms-transform: rotate(1turn);\n' +
    '          transform: rotate(1turn)\n' +
    '        }\n' +
    '      }\n' +
    '\n' +
    '      @-webkit-keyframes preloaderShadow {\n' +
    '        0% {\n' +
    '          opacity: 0\n' +
    '        }\n' +
    '        50% {\n' +
    '          opacity: .6\n' +
    '        }\n' +
    '        to {\n' +
    '          opacity: 0\n' +
    '        }\n' +
    '      }\n' +
    '\n' +
    '      @keyframes preloaderShadow {\n' +
    '        0% {\n' +
    '          opacity: 0\n' +
    '        }\n' +
    '        50% {\n' +
    '          opacity: .6\n' +
    '        }\n' +
    '        to {\n' +
    '          opacity: 0\n' +
    '        }\n' +
    '      }\n' +
    '\n' +
    '      div#div-preloader.ng-hide {\n' +
    '        opacity: 0;\n' +
    '        display: block !important;\n' +
    '        /* -webkit-animation: flipOutX 2.5s; */\n' +
    '        /* animation: flipOutX 2.5s; */\n' +
    '        transition: all linear 0.5s;\n' +
    '        z-index: -1000 !important;\n' +
    '      }\n' +
    '    </style>\n' +
    '\n' +
    '    <div class="preloader cover cover_abs row_h-center row_v-center">\n' +
    '      <div class="preloader__icon" style="\n' +
    '    /* width: 220px; */\n' +
    '    /* height: 220px; */\n' +
    '    /* position: relative; */\n' +
    '">\n' +
    '        <div class="preloader__spinner-img"></div>\n' +
    '        <div class="preloader__crown-img" style="\n' +
    '"></div><i class="preloader__spinner" style="\n' +
    'width: 190px;\n' +
    'height: 190px;\n' +
    'margin-left: -95px;\n' +
    'margin-top: -110px;\n' +
    '    transform-origin: center;\n' +
    '    position: absolute;\n' +
    '    top: 50%;\n' +
    '    left: 50%;\n' +
    '    display: inline-block;\n' +
    '    animation: preloaderSpinner 1.6s linear infinite;\n' +
    '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 321 320"><linearGradient id="SVGID_spinner" x1="25.575" x2="271.272" y1="71.231" y2="277.395" gradientUnits="userSpaceOnUse"><stop offset=".155" stop-color="#FFEAB5"></stop><stop offset=".541" class="stop_opacity-8" stop-color="#FAB50B"></stop><stop offset="1" class="stop_opacity" stop-color="#000000"></stop></linearGradient><path fill="url(#SVGID_spinner)" d="M282.1 258.3c-28.6 35.2-72.3 57.8-121.1 57.8h-1.1c-86 0-156-70-156-156.1C3.8 74 73.8 3.9 159.9 3.9h1.1V0h-1.2C71.6 0-.1 71.8-.1 160s71.8 160 160 160h1.1c50.2 0 95-23.2 124.4-59.4.3-.3.5-.7.5-.7l-3.2-2.3s-.4.5-.6.7z"></path></svg></i>\n' +
    '        <i class="preloader__crown" style="\n' +
    '        width: 115px;\n' +
    '            height: 115px;\n' +
    '            margin-left: -54px;\n' +
    '            margin-top: -67px;\n' +
    '    transform-origin: center;\n' +
    '    position: absolute;\n' +
    '    top: 50%;\n' +
    '    left: 50%;\n' +
    '    display: inline-block;\n' +
    '    /* animation: preloaderShadow 2.6s linear infinite; */\n' +
    '"><div style="\n' +
    '  background: url(<?=$logoLoading?>) no-repeat 0 50%/contain;\n' +
    '  width: 100%;\n' +
    '  height: 100%;\n' +
    '  /* position: absolute; */\n' +
    '  /* left: 50%; */\n' +
    '  /* top: 50%; */\n' +
    '"></div></i>\n' +
    '        <i class="preloader__shadow" style="\n' +
    '    width: 115px;\n' +
    '    height: 115px;\n' +
    '    margin-left: -54px;\n' +
    '    margin-top: -67px;\n' +
    '    transform-origin: center;\n' +
    '    position: absolute;\n' +
    '    top: 50%;\n' +
    '    left: 50%;\n' +
    '    display: inline-block;\n' +
    '    z-index: -1;\n' +

    '"><div style="\n' +
    '  background: url(<?=$logoLoading?>) no-repeat 0 50%/contain;\n' +
    '  width: 100%;\n' +
    '  height: 100%;\n' +
    '  /* position: absolute; */\n' +
    '  /* left: 50%; */\n' +
    '  /* top: 50%; */\n' +
    '"></div></i> </div>\n' +
    '    </div>\n' +
    '  </div></div>');

var d = document.getElementById('<?php echo $_REQUEST["containerID"];?>').style.display = "none";

var list = document.scripts;
var existe = false;

for (var i = 0; i < list.length; i++) {
    var list2 = list[i];
    if (list2.src === <?= $urlItainment ?>) {
        //list2.remove();
        existe = true;
    }
}

if (!existe) {

    var s = document.createElement("script");
    s.type = "text/javascript";
    s.src = <?= $urlItainment ?>;
    var head = document.getElementsByTagName('head')[0];
    head.appendChild(s);

    var options = {
        token: '<?php echo $token_string; ?>',
        skinid: '<?=$skinId?>',
        walletcode: '<?=$walletCode?>',
        full: true,
        page: 'live',
        lang: 'es-ES',
        fixed: false<?php if($ismobile){?>,
        mobile: true<?php } ?>
    };

    getScript(<?= $urlItainment ?>, function () {
        var BIA = new AltenarSportsbook('#<?php echo $_REQUEST["containerID"];?>', options);


        var refreshId = setInterval(function () {
            if (BIA.iframe.isConnected) {

                //$('#<?php echo $_REQUEST["containerID"];?>').css("display", "block");
                var d = document.getElementById('<?php echo $_REQUEST["containerID"];?>').style.display = "block";

                var elem = document.getElementById("div-wait");
                elem.parentNode.removeChild(elem);

                clearInterval(refreshId);

            }
        }, 3000);


    });


} else {


    var options = {
        token: '<?php echo $token_string; ?>',
        skinid: '<?=$skinId?>',
        walletcode: '<?=$walletCode?>',
        full: true,
        page: 'live',
        lang: 'es-ES',
        fixed: false<?php if($ismobile){?>,
        mobile: true<?php } ?>
    };
    var BIA = new AltenarSportsbook('#<?php echo $_REQUEST["containerID"];?>', options);


    var refreshId = setInterval(function () {
        if (BIA.iframe.isConnected) {

            //$('#<?php echo $_REQUEST["containerID"];?>').css("display", "block");
            var d = document.getElementById('<?php echo $_REQUEST["containerID"];?>').style.display = "block";

            var elem = document.getElementById("div-wait");
            elem.parentNode.removeChild(elem);

            clearInterval(refreshId);

        }
    }, 3000);
}


// BIA.setSelection(<?php echo $selection; ?>);


//]]>
//</script>
<?php


break;

case 'virtualsports2':

?>

$(document).ready(function () {

    var list = document.scripts;
    var existe = false;

    for (var i = 0; i < list.length; i++) {
        var list2 = list[i];
        if (list2.src === "https://msports-itainment.biahosted.com/staticResources/betinactionApi.js") {
            //list2.remove();
            existe = true;
        }
    }

    if (!existe) {

        var s = document.createElement("script");
        s.type = "text/javascript";
        s.src = "https://msports-itainment.biahosted.com/staticResources/betinactionApi.js";
        //$("head").append(s);
        var head = document.getElementsByTagName('head')[0];
        head.appendChild(s);


        var options = {
            token: '<?php echo $token_string; ?>',
            skinid: '<?=$skinId?>',
            walletcode: '<?=$walletCode?>',
            full: true,
            page: 'prelive',
            lang: 'es-ES',
            fixed: false,
            mobile: true
        };

        getScript('https://sports-itainment-uat.biahosted.com/StaticResources/betinactionApi.js', function () {
            var BIA = new AltenarSportsbook('#<?php echo $_REQUEST["containerID"];?>', options);
        });


    } else {
        var options = {
            token: '<?php echo $token_string; ?>',
            skinid: '<?=$skinId?>',
            walletcode: '<?=$walletCode?>',
            full: true,
            page: 'prelive',
            lang: 'es-ES',
            fixed: false,
            mobile: true
        };
        var BIA = new AltenarSportsbook('#<?php echo $_REQUEST["containerID"];?>', options);
    }

    $('#<?php echo $_REQUEST["containerID"];?> iframe').attr('scrolling', 'yes');


    // BIA.setSelection(<?php echo $selection; ?>);
    <?php

    break;

    case 'casino':

    $string = "?token=" . $token;

    ?>

    //<script class="ng-scope">
    //<![CDATA[
    $(document).ready(function () {


        document.getElementById('<?php echo $_REQUEST["containerID"];?>').parentNode.innerHTML += ('<div id="div-wait" style="\n' +
            '    background: transparent url(https://dev.doradobet.com/waitCasino' +
            '.gif) no-repeat center center;\n' +
            '    background-size: 60%;\n' +
            '    width: 100%;\n' +
            '    height: 100%;\n' +
            '    max-width: 500px;\n' +
            '    margin: 0 auto;\n' +
            '"></div>');

        document.getElementById('<?php echo $_REQUEST["containerID"];?>').style.display = "none";


        document.getElementById('<?php echo $_REQUEST["containerID"];?>').innerHTML += ('<iframe src="https://devadmin.doradobet.com/products/casino/doradobet.com/#/casino/<?php echo $string;?>" onload="onCasinoLoad(this)" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

        /*        document.getElementById('<?php /*echo $_REQUEST["containerID"];*/?>').append('<script>function onCasinoLoad() {\n' +
            '\n' +
            '                document.getElementById(\'<?php /*echo $_REQUEST["containerID"];*/?>\').style.display="block";\n' +
            '\n' +
            '                //document.getElementById(\'<?php /*echo $_REQUEST["containerID"];*/?>\').css({"display" :"block","-webkit-overflow-scrolling" :"touch"});\n' +
            '\n' +
            '                var elem = document.getElementById("div-wait");\n' +
            '                elem.parentNode.removeChild(elem);\n' +
            '            };</script>');
*/
        $('#<?php echo $_REQUEST["containerID"];?>').append('<script>function onCasinoLoad() {\n' +
            '\n' +
            '                $(\'#<?php echo $_REQUEST["containerID"];?>\').css({"display" :"block","-webkit-overflow-scrolling" :"touch"});\n' +
            '\n' +
            '                var elem = document.getElementById("div-wait");\n' +
            '                elem.parentNode.removeChild(elem);\n' +
            '            };</script>');


    });


    //]]>
    //</script>
    <?php

    break;

    case 'livecasino':

    $string = "?token=" . $token;

    ?>

    //<script class="ng-scope">
    //<![CDATA[
    $(document).ready(function () {
//https://dev.doradobet.com/products/livecasino/
        $('#<?php echo $_REQUEST["containerID"];?>').append('<iframe src="https://devadmin.doradobet.com/products/livecasino/doradobet.com/#/livecasino/<?php echo $string;?>" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

    });


//]]>
//</script>
    <?php

    break;


    case 'virtualsports':

    $string = "?token=" . $token;

    ?>

    //<script class="ng-scope">
    //<![CDATA[
    $(document).ready(function () {
//https://dev.doradobet.com/products/livecasino/
        document.getElementById('<?php echo $_REQUEST["containerID"];?>').innerHTML += ('<iframe src="https://devadmin.doradobet.com/products/virtuales/doradobet.com/#/game/27/provider/GDR<?php echo $string;?>" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

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

/**
     * Carga un script de forma asíncrona y ejecuta un callback cuando se ha cargado.
     *
     * @param {string} source - La URL del script a cargar.
     * @param {function} callback - La función a ejecutar una vez que el script se ha cargado.
     */
    function getScript(source, callback) {
        var script = document.createElement('script');
        var prior = document.getElementsByTagName('script')[0];
        script.async = 1;

        script.onload = script.onreadystatechange = function (_, isAbort) {
            if (isAbort || !script.readyState || /loaded|complete/.test(script.readyState)) {
                script.onload = script.onreadystatechange = null;
                script = undefined;

                if (!isAbort) {
                    if (callback) callback();
                }
            }
        };

        script.src = source;
        prior.parentNode.insertBefore(script, prior);
    }


