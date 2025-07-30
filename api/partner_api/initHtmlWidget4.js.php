<?php
/**
 * DORADOBET - MAQUINA
 *
 * Este script inicializa un widget HTML para la API 'partner'. 
 * Procesa parámetros de entrada, configura URLs y carga scripts necesarios para la integración.
 *
 *
 * @param array $params Parámetros de entrada recibidos a través de $_REQUEST:
 * @param string $params->product Producto solicitado (e.g., 'sport', 'live').
 * @param string $params->AuthToken Token de autenticación del usuario.
 * @param string $params->game Juego solicitado.
 * @param string $params->selection Selección específica dentro del producto.
 * @param string $params->mobile Indica si el dispositivo es móvil ('true' o 'false').
 * @param int $params->typeApp Tipo de aplicación (1 para lite, otro valor para completo).
 * @param string $params->lang Idioma solicitado (e.g., 'EN', 'ES').
 * @param string $params->containerID ID del contenedor HTML donde se cargará el widget.
 * @param string $params->provider Proveedor del servicio (e.g., 'ezugi', 'betgamestv').
 * 
 *
 * @return void Este script no retorna valores directamente, pero genera una salida HTML/JavaScript dinámica.
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST,    OPTIONS,PUT');

require(__DIR__ . '../../vendor/autoload.php');

use Backend\cms\CMSProveedor;
use Backend\cms\CMSCategoria;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\Usuario;
use Backend\dto\Proveedor;
use Backend\mysql\UsuarioTokenMySqlDAO;

/* depura caracteres de entradas de usuario para seguridad. */
$ConfigurationEnvironment = new ConfigurationEnvironment();


/* depura parámetros y asigna valores de idioma en función del input. */
$product = $ConfigurationEnvironment->DepurarCaracteres($_REQUEST["product"]);
$token = $ConfigurationEnvironment->DepurarCaracteres($_REQUEST["AuthToken"]);
$game = $ConfigurationEnvironment->DepurarCaracteres($_REQUEST["game"]);
$selection = $ConfigurationEnvironment->DepurarCaracteres($_REQUEST["selection"]);
$mobile = $ConfigurationEnvironment->DepurarCaracteres($_REQUEST["mobile"]);
$typeApp = $ConfigurationEnvironment->DepurarCaracteres($_REQUEST["typeApp"]);

$lang = ($_REQUEST["lang"]);

switch (strtoupper($lang)) {
    case "EN":
        $lang = "'en-EN'";
        break;
    case "ES":
        $lang = "'es-ES'";
        break;
    case "ENG":
        $lang = "'en-EN'";
        break;
    case "SPA":
        $lang = "'es-ES'";
        break;
}


/* asigna valores predeterminados a las variables $lang y $product si están vacías. */
if ($lang == "") {
    $lang = "'es-ES'";

}

if ($product == "") {
    $product = $_REQUEST["page"];
}


/* Se definen URLs para archivos JavaScript de diferentes servicios de apuestas deportivas. */
$urlItainment = '"https://sports-itainment-uat.biahosted.com/StaticResources/betinactionApi.js"';
$urlItainmentMobile = '"https://msports-itainment-uat.biahosted.com/StaticResources/betinactionApi.js"';
$urlItainment2 = '"https://sb1client-altenar-stage.biahosted.com/frontend/static/BetinactionApi.js"';

$urlItainment = '"https://sb1client-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
$urlItainmentMobile = '"https://sb1client-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';



/* Define URLs para un script de JavaScript de la plataforma Altenar Sportsbook. */
$urlItainment = '"https://operamini-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
$urlItainmentMobile = '"https://operamini-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';


if ($ConfigurationEnvironment->isDevelopment()) {

    if ($product == "sport" || $product == "live") {


        /* define URLs y un identificador de skin según el tipo de aplicación. */
        if ($typeApp == 1) {
            $urlItainment = '"https://operamini-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
            $urlItainmentMobile = '"https://operamini-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
            $skinId = "doradobetlite";


        } else {
            /* Define URLs y skinId para un sitio de apuestas en línea. */

            $urlItainment = '"https://sb1client-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
            $urlItainmentMobile = '"https://sb1client-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
            $skinId = "doradobet2";

        }



        /* Asignación de valores a variables que representan un código de billetera y un token. */
        $walletCode = '030817';

        $token_string = "332489910271384";
        $token_string = 215651983771;

        if ($token != "" && $token != "anonymous") {
            if ($ConfigurationEnvironment->isDevelopment()) {


                /* Se crean instancias de clases para gestionar usuarios y proveedores en el sistema. */
                $UsuarioToken = new UsuarioToken($token, "0");
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
                //$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());


                $Proveedor = new Proveedor("", "ITN");

                /* Se crea un objeto UsuarioToken con identificadores de usuario y proveedor. */
                $UsuarioTokenSite = new UsuarioToken('', '0', $UsuarioMandante->usumandanteId);


                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioTokenSite->getUsuarioId());

                } catch (Exception $e) {


                    /* Inserta un nuevo token de usuario en la base de datos si el código es 21. */
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
                        /* Se lanza una excepción si se encuentra un error en el bloque previo. */

                        throw $e;
                    }
                }

                /* Obtiene el token del usuario usando el método `getToken()` de la clase `UsuarioToken`. */
                $token_string = $UsuarioToken->getToken();
            } else {
                /* Crea objetos de usuario utilizando un token y obtiene el token correspondiente. */

                $UsuarioToken = new UsuarioToken($token, "0");
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                $token_string = $Usuario->tokenItainment;

            }
        }
    } else {

        /* Se inicializa una cadena vacía llamada token_string en el código. */
        $token_string = "";

        if ($token != "") {



            /* Se crea un objeto UsuarioToken y se obtiene su identificador y token asociado. */
            $UsuarioToken = new UsuarioToken($token, "0");

            try {
                $UsuarioToken2 = new UsuarioToken($UsuarioToken->getUsuarioId(), "0");
                $token = $UsuarioToken2->getToken();

            } catch (Exception $e) {


                /* Crea y almacena un nuevo token de usuario si ocurre un error específico. */
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
                    /* Captura una excepción y la vuelve a lanzar para su manejo posterior. */

                    throw $e;
                }

            }


        }

    }


} else {


    /* Condicional que define URLs y skinId según el valor de $typeApp. */
    if ($typeApp == 1) {
        $urlItainment = '"https://operamini-altenar.biahosted.com/static/AltenarSportsbook.js"';
        $urlItainmentMobile = '"https://operamini-altenar.biahosted.com/static/AltenarSportsbook.js"';
        $skinId = "doradobetlite";

    } else {
        /* asigna URLs a variables para un sportsbook, indicando un skin específico. */

        $urlItainment = '"https://sb1client-altenar.biahosted.com/static/AltenarSportsbook.js"';
        $urlItainmentMobile = '"https://sb1client-altenar.biahosted.com/static/AltenarSportsbook.js"';

        $skinId = "doradobet2";

    }



    /* verifica productos y asigna tokens para un usuario en función del tipo. */
    if ($product == "sport" || $product == "live") {

        //$skinId = "doradobet2";

        $walletCode = '030817';
        $walletCode = '190582';


        $UsuarioToken = new UsuarioToken($token, "0");

        $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        $token_string = $Usuario->tokenItainment;
    } else {


        /* Inicializa una variable vacía llamada $token_string para almacenar datos posteriormente. */
        $token_string = "";

        if ($token != "") {

            /* Se crea un objeto UsuarioToken y se extrae un nuevo token dentro de un bloque try. */
            $UsuarioToken = new UsuarioToken($token, "0");

            try {
                $UsuarioToken2 = new UsuarioToken($UsuarioToken->getUsuarioId(), "0");
                $token = $UsuarioToken2->getToken();

            } catch (Exception $e) {


                /* Se crea y almacena un nuevo token de usuario si el código de error es 21. */
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
                    /* lanza una excepción si se cumple una condición específica en el flujo. */

                    throw $e;
                }

            }
        }

    }

    if ($token_string != '') {

        /* Configura URLs de scripts según el tipo de aplicación y usuario mandante. */
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

/* asigna imágenes de logo y determina si el dispositivo es móvil. */
$ismobile = false;

$logoLoading = '<?=$logoLoading?>';
$logoLoading = 'https://images.doradobet.com/site/doradobet/logo-doradobet.png';
$logoLoading = 'https://images.doradobet.com/site/doradobet/logo-horizontal.png';

if ($mobile == "true") {
    $ismobile = true;
}


/* Asigna valores por defecto a variables si están vacías en PHP. */
if ($game == "") {
    $game = "''";
}

if ($product == "") {
    $product = "sport";
}


/* Asigna una URL específica si el dispositivo es móvil. */
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

if (!existe && false) {

    var s = document.createElement("script");
    s.type = "text/javascript";
    s.src = "https://code.jquery.com/jquery-1.11.2.min.js";
    //$("head").append(s);
    var head = document.getElementsByTagName('head')[0];
    head.appendChild(s);

    s.onload = function () {

        loadWidgetFunction();

        var $ = window.jQuery;
        // Use $ here...
    };

} else {
    loadWidgetFunction();
}

function loadWidgetFunction() {

    <?
    switch ($product) {

    case 'sport2':

    ?>



    <?php

    break;
    case 'sport':

    ?>


    //<script class="ng-scope">
    //<![CDATA[
    $(document).ready(function () {
//https://dev.doradobet.com/products/livecasino/
        $('#<?php echo $_REQUEST["containerID"];?>').append('<iframe src="https://operamini-altenar.biahosted.com?token=&walletcode=190582&lang=es-ES&skinId=doradobetlite&timezoneOffset=-180&oddsFormat=&configId=1" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

    });


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

    if (true) {

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
            lang: <?=$lang?>,
            fixed: false<?php if($ismobile){?>,
            mobile: true<?php } ?>
        };

        getScript(<?= $urlItainment ?>, function () {
            var BIA = new AltenarSportsbook('<?php echo (($typeApp == 1) ? '' : '#') . $_REQUEST["containerID"];?>', options);


            var refreshId = setInterval(function () {
                if (true) {

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
            lang: <?=$lang?>,
            fixed: false<?php if($ismobile){?>,
            mobile: true<?php } ?>
        };
        var BIA = new AltenarSportsbook('<?php echo (($typeApp == 1) ? '' : '#') . $_REQUEST["containerID"];?>', options);


        var refreshId = setInterval(function () {
            if (true) {

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
            lang: <?=$lang?>,
            fixed: false<?php if($ismobile){?>,
            mobile: true<?php } ?>
        };

        getScript(<?= $urlItainment ?>, function () {
            var BIA = new AltenarSportsbook('<?php echo (($typeApp == 1) ? '' : '#') . $_REQUEST["containerID"];?>', options);


            var refreshId = setInterval(function () {
                if (true) {

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
            lang: <?=$lang?>,
            fixed: false<?php if($ismobile){?>,
            mobile: true<?php } ?>
        };
        var BIA = new AltenarSportsbook('<?php echo (($typeApp == 1) ? '' : '#') . $_REQUEST["containerID"];?>', options);


        var refreshId = setInterval(function () {
            if (true) {

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
                lang: <?=$lang?>,
                fixed: false,
                mobile: true
            };

            getScript('https://sports-itainment-uat.biahosted.com/StaticResources/betinactionApi.js', function () {
                var BIA = new AltenarSportsbook('<?php echo (($typeApp == 1) ? '' : '#') . $_REQUEST["containerID"];?>', options);
            });


        } else {
            var options = {
                token: '<?php echo $token_string; ?>',
                skinid: '<?=$skinId?>',
                walletcode: '<?=$walletCode?>',
                full: true,
                page: 'prelive',
                lang: <?=$lang?>,
                fixed: false,
                mobile: true
            };
            var BIA = new AltenarSportsbook('<?php echo (($typeApp == 1) ? '' : '#') . $_REQUEST["containerID"];?>', options);
        }

        $('#<?php echo $_REQUEST["containerID"];?> iframe').attr('scrolling', 'yes');


        // BIA.setSelection(<?php echo $selection; ?>);
        <?php

        break;

        case 'casino':
        $string = "?in_app=1";
        $string = $string . "&category=all" . "&token=" . $token;

        ?>

        <?php
        if($ConfigurationEnvironment->isDevelopment()){


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


            document.getElementById('<?php echo $_REQUEST["containerID"];?>').innerHTML += ('<iframe src="https://devadmin.doradobet.com/products/casino/doradobet.comAPP/#/casino/<?php echo $string;?>" onload="onCasinoLoad(this)" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

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



        <?php
        }
        ?>

        <?php
        if(!$ConfigurationEnvironment->isDevelopment()){


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


            document.getElementById('<?php echo $_REQUEST["containerID"];?>').innerHTML += ('<iframe src="https://partnerlobby.virtualsoft.tech/casino/doradobet.com/#/casino/<?php echo $string;?>" onload="onCasinoLoad(this)" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

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



        <?php
        }
        ?>


        //]]>
        //</script>
        <?php

        break;

        case 'livecasino':

        $string = "?token=" . $token;

        ?>

        <?php
        if($ConfigurationEnvironment->isDevelopment()){


        ?>

        //<script class="ng-scope">
        //<![CDATA[
        $(document).ready(function () {
//https://dev.doradobet.com/products/livecasino/
            $('#<?php echo $_REQUEST["containerID"];?>').append('<iframe src="https://devadmin.doradobet.com/products/livecasino/doradobet.com/#/livecasino/<?php echo $string;?>" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

        });
        <?php
        }
        ?>


        <?php
        if(!$ConfigurationEnvironment->isDevelopment()){


        ?>

        //<script class="ng-scope">
        //<![CDATA[
        $(document).ready(function () {
//https://dev.doradobet.com/products/livecasino/
            $('#<?php echo $_REQUEST["containerID"];?>').append('<iframe src="https://partnerlobby.virtualsoft.tech/livecasino/doradobet.com12/#/livecasino/<?php echo $string;?>" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

        });
        <?php
        }
        ?>

//]]>
//</script>
        <?php

        break;

        case 'livecasinov2':
        $urlFinal = "";

        $provider = $_REQUEST["provider"];

        switch ($provider) {
            case "ezugi":
                $urlFinal = "https://casino.virtualsoft.tech/game/play/?gameGid=509&mode=real&provider=Ezugi&lang=es&mode=real&partnerid=0";

                break;
            case "betgamestv":
                $urlFinal = "https://casino.virtualsoft.tech/game/play/?gameGid=5722&mode=real&provider=BETGAMESTV&lang=es&mode=real&partnerid=0";

                break;
            case "evolution":
                $urlFinal = "https://casino.virtualsoft.tech/game/play/?gameGid=5741&mode=real&provider=Betixon&lang=es&mode=real&partnerid=0";

                break;
            case "vivogaming":
                $urlFinal = "https://casino.virtualsoft.tech/game/play/?gameGid=5813&mode=real&provider=VIVOGAMING&lang=es&mode=real&partnerid=0";

                break;
        }

        $string = "&token=" . $token;

        ?>

        <?php
        if($ConfigurationEnvironment->isDevelopment()){


        ?>

        //<script class="ng-scope">
        //<![CDATA[
        $(document).ready(function () {
//https://dev.doradobet.com/products/livecasino/
            $('#<?php echo $_REQUEST["containerID"];?>').append('<iframe src="<?php echo $urlFinal . $string;?>" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

        });
        <?php
        }
        ?>


        <?php
        if(!$ConfigurationEnvironment->isDevelopment()){


        ?>

        //<script class="ng-scope">
        //<![CDATA[
        $(document).ready(function () {
//https://dev.doradobet.com/products/livecasino/
            $('#<?php echo $_REQUEST["containerID"];?>').append('<iframe src="<?php echo $urlFinal . $string;?>" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

        });
        <?php
        }
        ?>

//]]>
//</script>
        <?php

        break;




        case 'virtualsports':

        $string = "?token=" . $token;

        ?>


        <?php
        if($ConfigurationEnvironment->isDevelopment()){


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
        }
        ?>



        <?php
        if(!$ConfigurationEnvironment->isDevelopment()){


        ?>

        //<script class="ng-scope">
        //<![CDATA[
        $(document).ready(function () {
//https://dev.doradobet.com/products/livecasino/
            document.getElementById('<?php echo $_REQUEST["containerID"];?>').innerHTML += ('<iframe src="https://partnerlobby.virtualsoft.tech/doradobet/virtuales/#/game/399/provider/GDR<?php echo $string;?>" frameborder="1" width="100%" height="101%" scrolling="yes" id="frameCasino"></iframe>');

        });


//]]>
//</script>

        <?php
        }
        ?>

        <?php

        break;

        default:
            # code...
            break;
        }
        ?>
    }

    function getScript(u, c) {
        var d = document, t = 'script',
            o = d.createElement(t),
            s = d.getElementsByTagName(t)[0];
        o.src = u;

        if (c) {
            o.addEventListener('load', function (e) {
                c(null, e);
            }, false);
        }
        s.parentNode.insertBefore(o, s);
    }



