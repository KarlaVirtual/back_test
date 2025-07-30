<?php



header('Content-Type: text/html; charset=UTF-8');

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionProducto;


$ConfigurationEnvironment = new ConfigurationEnvironment();

$_GET["id"] = str_replace(' ','+',$_GET["id"]);

$sessiontoken = $_GET["id"];

$sessiontoken= $ConfigurationEnvironment->decrypt($sessiontoken);

$sessiontokenEncrypt= $ConfigurationEnvironment->encrypt($sessiontoken);

if (isset($_GET["id"]) && $sessiontoken != '' && is_numeric($sessiontoken)) {
  if ($ConfigurationEnvironment->isDevelopment()) {


        $URLConfirm = 'https://admincert.virtualsoft.tech/api/api/integrations/payment/payphone/api/confirm/';
        $URLJS ='https://pay.payphonetodoesposible.com/api/button/js';
        $Identificador = "lMesviuYD0Kap5beZpeQpg";
        $IdClient = "amA812FmQUWaONDt0QJUg";
        $SecretKey = "acQcysvW0uG9YGoPV8ZUA";
         $token = "rwtG1bC8CdVKfv4UEPwVkb3dxYT_Cp76fPUdzhKgW1JpLQccDiCkPctsPAvpQA1pO7agBweMHHn7-3UNX_n-SoP0dBSm44SWJ6yEEfBM93E_RKdPNdcy0kE6UHGSZixi6YSKktaA5WwezPyS1HEd95_juPFIc0r1THh7NTXFeyFByx4o7TK6U9s8TdbWh3BHJh1JzQcxoYlFg2j4zmnqjmquo6QM7MeoRp1U6jAT3IK1_jGOPVgN9vfMEtsSoRSWIlnJWJFJYZoAiWx5_JipUasN-vN6nMsJuyGK6g4X8MQLD8_rJnJU635DTnEd5BMgZV_E64Fk9fqRBPvHOmOVaPlPd7A";
    }else{
        $URLConfirm = 'https://integrations.virtualsoft.tech/payment/payphone/api/confirm/';
        $URLJS ='https://pay.payphonetodoesposible.com/api/button/js';
        $Identificador = "lMesviuYD0Kap5beZpeQpg";
        $IdClient = "amA812FmQUWaONDt0QJUg";
         $SecretKey = "acQcysvW0uG9YGoPV8ZUA";
         $token = "rwtG1bC8CdVKfv4UEPwVkb3dxYT_Cp76fPUdzhKgW1JpLQccDiCkPctsPAvpQA1pO7agBweMHHn7-3UNX_n-SoP0dBSm44SWJ6yEEfBM93E_RKdPNdcy0kE6UHGSZixi6YSKktaA5WwezPyS1HEd95_juPFIc0r1THh7NTXFeyFByx4o7TK6U9s8TdbWh3BHJh1JzQcxoYlFg2j4zmnqjmquo6QM7MeoRp1U6jAT3IK1_jGOPVgN9vfMEtsSoRSWIlnJWJFJYZoAiWx5_JipUasN-vN6nMsJuyGK6g4X8MQLD8_rJnJU635DTnEd5BMgZV_E64Fk9fqRBPvHOmOVaPlPd7A";

    }



    $TransaccionProducto = new TransaccionProducto($sessiontoken);
    $valor = $TransaccionProducto->getValor();


    $Registro = new \Backend\dto\Registro("",$TransaccionProducto->getUsuarioId());
    $UsuarioMandante = new \Backend\dto\UsuarioMandante("","$Registro->usuarioId",$Registro->mandante);
    $Mandante = new \Backend\dto\Mandante($UsuarioMandante->getMandante());


    if($UsuarioMandante->usuarioMandante==81582 || $UsuarioMandante->usuarioMandante==73818 || date('Y-m-d H:i:s') >='2023-04-06 00:00:00'){
        $token = "nvlHbLUVsoIc70x-CeKUdR8V4YQ7W0KSjoOKMl2TAhfvD6tdcCFxIKtVSDH3IDbqnAoz4dre9Yo2Z75u7A_BWuCTcfBcbNPDZLTIu0glPaZ9sAOCKh-UsiHJP0rQbmNX3ZJvKivjbCvWT7lEPlhQh_agCtyNgyoIvVMv2XKZhVOU-G_e0I0RK3abugCezi6n8r73v-kTFl6ggCEbc-xcZwAfyI5Y4yOJYmQfGXzewreAeM-aA3VOvA6wMaKHvlCc0os98g--oqXe7KKjnhlK5tqme-iQNEyEbfDmA4_A1_h4f36C35EESLvv0-fbJ_eTZzOL-Zhc13pEySiLcDaRzuhqf4A";
        $token = "nvlHbLUVsoIc70x-CeKUdR8V4YQ7W0KSjoOKMl2TAhfvD6tdcCFxIKtVSDH3IDbqnAoz4dre9Yo2Z75u7A_BWuCTcfBcbNPDZLTIu0glPaZ9sAOCKh-UsiHJP0rQbmNX3ZJvKivjbCvWT7lEPlhQh_agCtyNgyoIvVMv2XKZhVOU-G_e0I0RK3abugCezi6n8r73v-kTFl6ggCEbc-xcZwAfyI5Y4yOJYmQfGXzewreAeM-aA3VOvA6wMaKHvlCc0os98g--oqXe7KKjnhlK5tqme-iQNEyEbfDmA4_A1_h4f36C35EESLvv0-fbJ_eTZzOL-Zhc13pEySiLcDaRzuhqf4A";

    }

    ?>
    <header style="
    /* margin-top: 30%; */
    position: relative;
    display: block;
"><img src="<?=$Mandante->logo?>" style="
    max-width: 250px;
    margin: 8px auto;
    position: relative;
    display: block;
    /* margin-top: 14px; */
    height: auto;
"></header>
    <body style="
    background: #fff;
    background: linear-gradient(135deg,#fff 0,#fff 46%,#e3f4fb 48%,#e3f4fb 100%);
">
    <div id="content" style="">
        <div id="pp-button"></div>
        <script src="<?=$URLJS."?appId=".$Identificador?>"></script>

        <script>
            window.onload = function () {
                payphone.Button ({

                    //token obtenido desde la consola de developer
                    token:"<?php echo $token; ?>",

                    //PARÁMETROS DE CONFIGURACIÓN
                    btnHorizontal: true,
                    btnCard: true,

                    createOrder: function(actions){

                        //Se ingresan los datos de la transaccion ej. monto, impuestos, etc
                        return actions.prepare({

                            amount: <?php echo intval(($valor)*100); ?>,
                            amountWithoutTax: <?php echo intval(($valor)*100); ?>,
                            clientTransactionId: "<?php echo $sessiontoken; ?>",
                            email: "<?php echo $Registro->getEmail(); ?>",
                            documentId: "<?php echo $Registro->getCedula(); ?>",
                        });

                    },
                    onComplete: function(model, actions) {

                        //Se confirma el pago realizado
                        actions.confirm({
                            id: model.id,
                            clientTxId: model.clientTxId
                        }).then(function(value){

                            //EN ESTA SECCIÓN SE RECIBE LA RESPUESTA Y SE MUESTRA AL USUARIO

                            if (value.transactionStatus == "Approved") {
                                window.location.href= '<?= $Mandante->baseUrl ?>/gestion/deposito/correcto';
                            }else {
                                window.location.href= '<?= $Mandante->baseUrl ?>/gestion/deposito/pendiente';

                            }
                        }).catch(function(err) {
                            console.log(err);
                        });

                    }
                }).render('#pp-button');

            }
        </script>
    </div>
    </body>



    <style>
        table {
            padding: 10px;
            border-radius: 5px;
            margin: 0 auto;
        }

        tr td:nth-child(2) {
            background: white;
        }


        tr td:nth-child(1) {
            background: #ffffff;
            color: black;
            padding: 5px;
            width: 269px;
            font-weight: bold;
        }

        tr td:nth-child(2) {
            background: white;
        }

        header, footer {
            background: #464646;
            padding: 15px;
        }

        div#content {
            border: 1px solid #a2a2a2;
            text-align: center;
            padding: 50px;
        }

        div#buttons div {
            width: 40%;
            border-radius: 8px;
            padding: 10px;
            float: left;
            text-align: center;
            margin: 0px 10px;
        }

        button {
            background: white;
            border-radius: 5px;
            padding: 10px 25px;
            cursor: pointer;
        }

        tr {
            border-bottom: 1px solid #000;
            display: block;
        }

        a {
            text-decoration: none;
            color: black;
            background: white;
            padding: 13px 10px;
            border-radius: 5px;
        }

        a:hover, button:hover {
            background: #000000;
            color: white;
        }

        body {
            background: #ffffff;
            width: 500px;
            margin: 0 auto;
            /* border: 1px solid #000; */
            border-radius: 5px;
        }
        button.default {
            /* width: 187px; */
            /* height: 40px; */
            /* font-size: 36px; */
            /* width: 200px; */
            height: 40px;
            width: 187px;
            background-size: cover !important;
        }

        body{
/*
            margin: 30% auto;
*/
            width: 100%;
            max-width: 600px;
        }
    </style>
    <?php

}else{
    ?>

    <style>
        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            padding: 0;
            margin: 0;
        }

        #notfound {
            position: relative;
            height: 100vh;
        }

        #notfound .notfound {
            position: absolute;
            left: 50%;
            top: 30%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .notfound {
            max-width: 410px;
            width: 100%;
            text-align: center;
        }

        .notfound .notfound-404 {
            height: 280px;
            position: relative;
            z-index: -1;
        }

        .notfound .notfound-404 h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 230px;
            margin: 0px;
            font-weight: 900;
            position: absolute;
            left: 50%;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            background: url('../img/bg.jpg') no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: cover;
            background-position: center;
        }


        .notfound h2 {
            font-family: 'Montserrat', sans-serif;
            color: #000;
            font-size: 24px;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 0;
        }

        .notfound p {
            font-family: 'Montserrat', sans-serif;
            color: #000;
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 20px;
            margin-top: 0px;
        }

        .notfound a {
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            text-decoration: none;
            text-transform: uppercase;
            background: #0046d5;
            display: inline-block;
            padding: 15px 30px;
            border-radius: 40px;
            color: #fff;
            font-weight: 700;
            -webkit-box-shadow: 0px 4px 15px -5px #0046d5;
            box-shadow: 0px 4px 15px -5px #0046d5;
        }


        @media only screen and (max-width: 767px) {
            .notfound .notfound-404 {
                height: 142px;
            }
            .notfound .notfound-404 h1 {
                font-size: 112px;
            }
        }

    </style>

    <div id="notfound">
        <div class="notfound">
            <h2>404 - Page not found</h2>
            <!--
                        <p>The page you are looking for might have been removed had its name changed or is temporarily unavailable.</p>
                        <a href="#">Go To Homepage</a>
    -->
        </div>
    </div>
    <?php
}
exit();


?>


