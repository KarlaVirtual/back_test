<head>
</head>

<div id="objectBIA"></div>
<style>
    iframe {
        height: 100% !important;
    }
</style>
<script>
    <?php
    header('Content-Type: text/html');

    /**
     * Inicializar html widget de la api 'partner'
     *
     *
     * @package ninguno
     * @author Daniel Tamayo <it@virtualsoft.tech>
     * @version ninguna
     * @access public
     * @see no
     *
     */

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');

    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: GET, POST,    OPTIONS,PUT');


    use Backend\cms\CMSProveedor;
    use Backend\cms\CMSCategoria;
    use Backend\dto\ConfigurationEnvironment;
    use Backend\dto\UsuarioMandante;
    use Backend\dto\UsuarioToken;
    use Backend\dto\Usuario;
    use Backend\dto\Proveedor;
    use Backend\mysql\UsuarioTokenMySqlDAO;

    $ConfigurationEnvironment = new ConfigurationEnvironment();


    $product = $ConfigurationEnvironment->DepurarCaracteres($_REQUEST["product"]);
    $token = $ConfigurationEnvironment->DepurarCaracteres($_REQUEST["AuthToken"]);
    $game = $ConfigurationEnvironment->DepurarCaracteres($_REQUEST["game"]);
    $selection = $ConfigurationEnvironment->DepurarCaracteres($_REQUEST["selection"]);
    $mobile = $ConfigurationEnvironment->DepurarCaracteres($_REQUEST["mobile"]);
    $typeApp = $ConfigurationEnvironment->DepurarCaracteres($_REQUEST["typeApp"]);
    $typeApp='1';
    $containerHTML = 'objectBIA';

    $lang = ($_REQUEST["lang"]);

    switch (strtoupper($lang)) {
        case "EN":
            $lang = "'en-EN'";
            break;
        case "ES":
            $lang = "'es-ES'";
            break;
    }

    if ($lang == "") {
        $lang = "'es-ES'";

    }

    if ($product == "") {
        $product = $_REQUEST["page"];
    }

    $urlItainment = '"https://sports-itainment-uat.biahosted.com/StaticResources/betinactionApi.js"';
    $urlItainmentMobile = '"https://msports-itainment-uat.biahosted.com/StaticResources/betinactionApi.js"';
    $urlItainment2 = '"https://sb1client-altenar-stage.biahosted.com/frontend/static/BetinactionApi.js"';

    $urlItainment = '"https://sb1client-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
    $urlItainmentMobile = '"https://sb1client-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';


    $urlItainment = '"https://operamini-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
    $urlItainmentMobile = '"https://operamini-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';


    if ($ConfigurationEnvironment->isDevelopment()) {

        if ($product == "sport" || $product == "live") {

            if ($typeApp == 1) {
                $urlItainment = '"https://operamini-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
                $urlItainmentMobile = '"https://operamini-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
                $skinId = "doradobetlite";


            } else {
                $urlItainment = '"https://sb1client-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
                $urlItainmentMobile = '"https://sb1client-altenar-stage.biahosted.com/static/AltenarSportsbook.js"';
                $skinId = "doradobet";

            }


            $walletCode = '030817';

            $token_string = "332489910271384";
            $token_string = 215651983771;

            if ($token != "" && $token != "anonymous") {
                if ($ConfigurationEnvironment->isDevelopment()) {

                    $UsuarioToken = new UsuarioToken($token, "0");
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
                    //$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());


                    $Proveedor = new Proveedor("", "ITN");
                    $UsuarioTokenSite = new UsuarioToken('', '0', $UsuarioMandante->usumandanteId);


                    try {
                        $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioTokenSite->getUsuarioId());

                    } catch (Exception $e) {

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
                            throw $e;
                        }
                    }
                    $token_string = $UsuarioToken->getToken();
                } else {
                    $UsuarioToken = new UsuarioToken($token, "0");
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                    $token_string = $Usuario->tokenItainment;

                }
            }
        } else {
            $token_string = "";

            if ($token != "") {


                $UsuarioToken = new UsuarioToken($token, "0");

                try {
                    $UsuarioToken2 = new UsuarioToken($UsuarioToken->getUsuarioId(), "0");
                    $token = $UsuarioToken2->getToken();

                } catch (Exception $e) {

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
                        throw $e;
                    }

                }


            }

        }


    } else {

        if ($typeApp == 1) {
            $urlItainment = '"https://operamini-altenar.biahosted.com/static/AltenarSportsbook.js"';
            $urlItainmentMobile = '"https://operamini-altenar.biahosted.com/static/AltenarSportsbook.js"';
            $skinId = "doradobetlite";

        } else {
            $urlItainment = '"https://sb1client-altenar.biahosted.com/static/AltenarSportsbook.js"';
            $urlItainmentMobile = '"https://sb1client-altenar.biahosted.com/static/AltenarSportsbook.js"';

            $skinId = "doradobet";

        }


        if ($product == "sport" || $product == "live") {

            //$skinId = "doradobet2";

            $walletCode = '030817';
            $walletCode = '190582';

            try {


                $UsuarioToken = new UsuarioToken($token, "0");

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                $token_string = $Usuario->tokenItainment;
            }catch (Exception $e){

            }
        } else {

            $token_string = "";

            if ($token != "") {
                $UsuarioToken = new UsuarioToken($token, "0");

                try {
                    $UsuarioToken2 = new UsuarioToken($UsuarioToken->getUsuarioId(), "0");
                    $token = $UsuarioToken2->getToken();

                } catch (Exception $e) {

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
                        throw $e;
                    }

                }
            }

        }

    }
    //
    //$skinId = "wplay2";
    $ismobile = false;

    $logoLoading = '<?=$logoLoading?>';
    $logoLoading = 'https://images.doradobet.com/site/doradobet/logo-doradobet.png';
    $logoLoading = 'https://images.doradobet.com/site/doradobet/logo-horizontal.png';

    if ($mobile == "true") {
        $ismobile = true;
    }

    if ($game == "") {
        $game = "''";
    }

    if ($product == "") {
        $product = "sport";
    }

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

    if (!existe ) {

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

        document.getElementById('<?php echo $containerHTML;?>').parentNode.innerHTML += ('<div id="div-wait">  <div id="div-preloader" ng-hide="conf" class="div-preloader" style="\n' +
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


        var d = document.getElementById('<?php echo $containerHTML;?>').style.display = "none";


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
                lang: <?=$lang?>,
                fixed: false<?php if($ismobile){?>,
                mobile: true<?php } ?>
            };

            getScript(<?= $urlItainment ?>, function () {
                var BIA = new AltenarSportsbook('<?php echo (($typeApp == 1) ? '' : '#') . $containerHTML;?>', options);


                var refreshId = setInterval(function () {
                    if (true) {

                        var d = document.getElementById('<?php echo $containerHTML;?>').style.display = "block";
                        //d.className += " otherclass";

                        //$('#<?php echo $containerHTML;?>').css("display", "block");

                        var elem = document.getElementById("div-wait");
                        elem.parentNode.removeChild(elem);

                        setTimeout(function(){
                            if(document.getElementsByTagName("iframe")[0].src != undefined){
                                window.location.href=document.getElementsByTagName("iframe")[0].src;

                            }
                        }, 3000);

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
            var BIA = new AltenarSportsbook('<?php echo (($typeApp == 1) ? '' : '#') . $containerHTML;?>', options);


            var refreshId = setInterval(function () {
                if (true) {

                    var d = document.getElementById('<?php echo $containerHTML;?>').style.display = "block";
                    //d.className += " otherclass";

                    //$('#<?php echo $containerHTML;?>').css("display", "block");

                    var elem = document.getElementById("div-wait");
                    elem.parentNode.removeChild(elem);


                    setTimeout(function(){
                        if(document.getElementsByTagName("iframe")[0].src != undefined){
                            window.location.href=document.getElementsByTagName("iframe")[0].src;

                        }
                    }, 3000);

                    clearInterval(refreshId);

                }
            }, 3000);
        }




        <?php

        break;

        case 'live':


        ?>

        document.getElementById('<?php echo $containerHTML;?>').parentNode.innerHTML += ('<div id="div-wait">  <div id="div-preloader" ng-hide="conf" class="div-preloader" style="\n' +
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

        var d = document.getElementById('<?php echo $containerHTML; ?>').style.display = "none";

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
                skinid: '<?= $skinId ?>',
                walletcode: '<?= $walletCode ?>',
                full: true,
                page: 'live',
                lang: <?= $lang ?>,
                fixed: false<?php if ($ismobile) { ?>,
                mobile: true<?php } ?>
            };

            getScript(<?= $urlItainment ?>, function () {
                var BIA = new AltenarSportsbook('<?php echo (($typeApp == 1) ? '' : '#') . $containerHTML; ?>', options);


                var refreshId = setInterval(function () {
                    if (true) {

//$('#<?php echo $containerHTML; ?>').css("display", "block");
                        var d = document.getElementById('<?php echo $containerHTML; ?>').style.display = "block";

                        var elem = document.getElementById("div-wait");
                        elem.parentNode.removeChild(elem);

                        clearInterval(refreshId);

                    }
                }, 3000);


            });


        } else {


            var options = {
                token: '<?php echo $token_string; ?>',
                skinid: '<?= $skinId ?>',
                walletcode: '<?= $walletCode ?>',
                full: true,
                page: 'live',
                lang: <?= $lang ?>,
                fixed: false<?php if ($ismobile) { ?>,
                mobile: true<?php } ?>
            };
            var BIA = new AltenarSportsbook('<?php echo (($typeApp == 1) ? '' : '#') . $containerHTML; ?>', options);


            var refreshId = setInterval(function () {
                if (true) {

//$('#<?php echo $containerHTML; ?>').css("display", "block");
                    var d = document.getElementById('<?php echo $containerHTML; ?>').style.display = "block";

                    var elem = document.getElementById("div-wait");
                    elem.parentNode.removeChild(elem);

                    clearInterval(refreshId);

                }
            }, 3000);
        }

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
                    var BIA = new AltenarSportsbook('<?php echo (($typeApp == 1) ? '' : '#') . $containerHTML;?>', options);
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
                var BIA = new AltenarSportsbook('<?php echo (($typeApp == 1) ? '' : '#') . $containerHTML;?>', options);
            }

            $('#<?php echo $containerHTML;?> iframe').attr('scrolling', 'yes');


            // BIA.setSelection(<?php echo $selection; ?>);
            <?php

            break;

            default:
                # code...
                break;
            }
            ?>
        }

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
            document.head.append(script);
        }


</script>