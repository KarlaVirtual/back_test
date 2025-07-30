<?php
header('Content-Type: text/html');

use Backend\dto\ProveedorMandante;
use Backend\dto\Registro;
use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;
use Backend\dto\Pais;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionProducto;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\dto\Mandante;
use Backend\dto\Producto;

$ConfigurationEnvironment = new ConfigurationEnvironment();

$x_invoice = str_replace(" ","+",$_POST['x_invoice']);



if ($_GET['id'] != '' && $x_invoice == '') {
    $x_invoice = $_GET['id'];
}
$x_invoice = $ConfigurationEnvironment->DepurarCaracteres($x_invoice);


if($ConfigurationEnvironment->isProduction()) {
    $x_invoice = str_replace(" ","+",$x_invoice);
    $x_invoice = $ConfigurationEnvironment->decrypt($x_invoice);
}
try {

    if ($x_invoice != '' && is_numeric($x_invoice)) {

        $merchant='TESTb2c_psjljbet';
        $merchantPassword='afa5074fa7f61a80c591dbb16bb36dca';

        if($ConfigurationEnvironment->isProduction()){
            $merchant='b2c_psjljbet';
            $merchantPassword='a0340608c509f3da1bfed4b526ceffeb';

        }

        $TransaccionProducto = new TransaccionProducto($x_invoice);

        if ($TransaccionProducto->getEstado() != "A") {
            ?>
            <html>

            <head>

            </head>

            <body>

            <!-- CREATE THE HTML FOR THE PAYMENT PAGE -->
            <div style="
    width: 100%;
    margin: 0 auto;
    background: white;
    text-align: center;
    /* height: 400px; */
    display: block;
    position: relative;
    padding-top: 20px;
">
                <img id="header-logo" src="https://images.virtualsoft.tech/site/justbet/logo.png"
                     width="180"
                     alt="Logo">
                <p> The deposit has already been processed</p>

                <a href="https://mobile.justbetja.com/gestion/deposito" style="
    background: #03823c;
    padding: 16px;
    margin-top: 11px;
    position: relative;
    display: block;
    width: 150px;
    border-radius: 5px;
    margin: 0 auto;
    color: white;
    text-decoration: initial;
"> Retornar al comercio </a>
            </div>
            </body>
            </html>
            <?php
            exit();
        }

        $Producto = new Producto($TransaccionProducto->getProductoId());

        $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
        $Registro = new Registro('', $Usuario->usuarioId);

        $Mandante = new Mandante($Usuario->mandante);

        $Pais = new Pais($Usuario->paisId);

        $Producto = new Producto($TransaccionProducto->getProductoId());
        //$ProveedorMandante = new ProveedorMandante($Producto->getProveedorId(), $Usuario->mandante);

        // $detalle = json_decode($ProveedorMandante->detalle);

        // $PBFPubKey = $detalle->public_key;

        $customer_email = $Usuario->login;
        $customer_firstname = $Registro->nombre1;
        $customer_lastname = $Registro->apellido1;

        $custom_description = 'Deposito';

        $custom_logo = $Mandante->logo;

        $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';


        if ($Mandante->mandante == 0) {
            $custom_logo = 'https://images.virtualsoft.tech/site/doradobet/logo-doradobet.png';
        }

        $custom_title = $Mandante->descripcion;

        $sessionId = $TransaccionProducto->getExternoId();

        $amount = $TransaccionProducto->getValor();
        $customer_phone = $Registro->getCelular();
        $country = strtoupper($Pais->iso);

        $currency = strtoupper($Usuario->moneda);

        $MaxRows = 10;
        $OrderedItem = 0;
        $SkeepRows = 0;

        $rules = [];
        array_push($rules, array("field" => "usuario_tarjetacredito.usuario_id", "data" => $TransaccionProducto->getUsuarioId(), "op" => "eq"));
        array_push($rules, array("field" => "usuario_tarjetacredito.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "usuario_tarjetacredito.proveedor_id", "data" => $Producto->getProveedorId(), "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $UsuarioTarjetacredito = new UsuarioTarjetacredito();

        $bancos = $UsuarioTarjetacredito->getUsuarioTarjetasCustom("usuario_tarjetacredito.*", "usuario_tarjetacredito.usutarjetacredito_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $bancos = json_decode($bancos);


        $final = array();
        foreach ($bancos->data as $key => $value) {
            $array = array();

            $array["Id"] = $value->{"usuario_tarjetacredito.usutarjetacredito_id"};
            $array["Account"] = $value->{"usuario_tarjetacredito.cuenta"};
            $array["Brand"] = $value->{"usuario_tarjetacredito.descripcion"};
            array_push($final, $array);
        }

        ?>


        <html>

        <head>
            <!-- INCLUDE SESSION.JS JAVASCRIPT LIBRARY -->
            <script src="https://sagicorbank.gateway.mastercard.com/form/version/50/merchant/<?=$merchant?>/session.js"></script>
            <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>

            <script src="https://devfrontend.virtualsoft.tech/justbetframe/default/assets/js/sweetalert.min.js?v=100098"></script>

            <link rel="stylesheet" href="https://devfrontend.virtualsoft.tech/justbetframe/css/sweetalert.css?v=100098">

            <!-- APPLY CLICK-JACKING STYLING AND HIDE CONTENTS OF THE PAGE -->
            <style id="antiClickjack">
                body {
                    display: none !important;
                }

            </style>
            <style>
                input {
                    height: 30px;
                    width: 200px;
                }
            </style>
        </head>

        <body>

        <!-- CREATE THE HTML FOR THE PAYMENT PAGE -->
        <div style="
    width: 100%;
    margin: 0 auto;
    background: white;
    text-align: center;
    /* height: 400px; */
    display: block;
    position: relative;
    padding-top: 20px;
">
            <img id="header-logo" src="https://devfrontend.virtualsoft.tech/justbetframe/images/justbet.png" width="180"
                 alt="Logo">
            <p> Merchant: JUST BET- Hosted Payment Page</p>


            <?php if (oldCount($final) > 0) { ?>
                <div style="
    width: 100%;
    display: inline-block;
    padding: 10px 10px;

    overflow-x: scroll;
    position: relative;
    white-space: nowrap;
">

                    <p style="
    font-weight: bold;
    border-top: 1px dotted;
    padding-top: 10px;
    max-width: 10px;
    margin: 0 auto;
    margin-bottom: 10px;
">Pay with your recently used cards</p>
                    <?php

                    foreach ($final as $item) {
                        echo ' <div class="accountuser" onclick="payWithCardSave(' . $_GET["id"] . ',' . $item['Id'] . ')" style="
    background: #2b8b46f0;
    width: 150px;
    /* text-align: center; */
    color: white;
    padding: 20px;
    border-radius: 10px;
    cursor: pointer;
    display: inline-block;    margin: 0px 10px;    box-shadow: 1px 1px 0 rgba(25, 25, 112, 0.3), -1px -1px 0 rgba(255, 255, 255, 0.4) inset, 0 0 3vw rgba(25, 25, 112, 0.5);
">
                    <div class="brand" style="
    margin: 5px 0px;
    font-weight: bold;text-transform: uppercase;
">
                        ' . $item['Brand'] . '
                    </div>
                    <div class="account" style="
    background: rgb(255 255 255 / 18%);
    border-radius: 5px;
">
                        ' . $item['Account'] . '
                    </div>

                </div>';

                    }


                    ?>


                </div>

            <?php } ?>

            <div>Please enter your payment details:</div>
            ...........................................................................

            <div style="text-align: left;margin-bottom: 10px;width: 70%;margin: 0 auto;">
                <b>ID:</b> <?= $TransaccionProducto->transproductoId ?></div>
            <div style="text-align: left;margin-bottom: 10px;width: 70%;margin: 0 auto;">
                <b>User:</b> <?= $Usuario->usuarioId ?></div>
            <div style="text-align: left;margin-bottom: 10px;width: 70%;margin: 0 auto;">
                <b>Name:</b> <?= $Usuario->nombre ?></div>
            <div style="text-align: left;margin-bottom: 10px;width: 70%;margin: 0 auto;">
                <b>Amount to be paid:</b> <?= $TransaccionProducto->valor ?></div>
            <div style="text-align: left;margin-bottom: 10px;width: 70%;margin: 0 auto;">
                <b>Fee (3.5%):</b> <?= floatval(floatval($TransaccionProducto->valor)*(0.035))  ?></div>
            <div style="text-align: left;margin-bottom: 10px;width: 70%;margin: 10px auto;font-size: 18px;">
                <b>Final amount that will be added to the balance:</b> <?= floatval(floatval($TransaccionProducto->valor)-floatval($TransaccionProducto->valor)*(0.035))  ?></div>
            <div style="text-align: left;margin-bottom: 10px;width: 70%;margin: 10px auto;font-size: 14px;">
                <b>Important note:</b>A commission of 3.5% will be charged to the value paid, the value paid will be deposited to the account less the value of the commission</div>

            <div style="
    margin-top: 0px;
    position: relative;
    width: 100%;
    height: 100%;
">

                <div class='credit-card'>
                    <div style="
    margin-top: 50px;
    font-size: 30px;
">CREDIT CARD
                    </div>
                    <div class="number multifield">
                        <span style="
                            width: 100%;
                            /* height: 20px; */
                            display: inline-block;
                            color: white;
                            float: left;
                            font-size: 16px;
                            text-align: left;
                            margin-top: -5px;
                            margin-bottom: 5px;
                            padding-left: 5px;
                        ">Card Number</span>
                        <!--<input type="text" maxlength="4" inputmode='numeric' pattern="\d+">
                        <input type="text" maxlength="4" inputmode='numeric' pattern="\d+">
                        <input type="text" maxlength="4" inputmode='numeric' pattern="\d+">
                        <input type="text" maxlength="4" inputmode='numeric' pattern="\d+">-->
                        <div>

                            <input style="width: 400px" type="text" id="card-number" class="input-field" value=""
                                   readonly>

                        </div>
                    </div>

                    <div class='date multifield'>
                        <span>Security<br>Code</span>
                        <input type="text" id="security-code" class="input-field" value="" readonly>
                        <span>good thru<br>last day of</span>
                        <input type="text" id="expiry-month" maxlength="2" inputmode='numeric' pattern="\d+">
                        /
                        <input type="text" id="expiry-year" maxlength="2" inputmode='numeric' pattern="\d+">
                        <input type="hidden" required="required" name="creditDate">
                    </div>
                    <button id="payButton"
                            style="width:200px;height: 40px;background-color: #ffffff;color: #2c8a45;border:none;"
                            onclick="pay();">Pay Now
                    </button>
                    <div style="
    position: absolute;
    bottom: -66px;
    left: 160px;
    color: black;
">PRIME SPORTS JAMAICA</div>
                </div>

            </div>

            <div>
                <table style="table-layout: fixed;" height="500" width="700">
                    <tr>
                        <td style="word-wrap: break-word" id="output"></td>
                    </tr>
            </div>

        </div>
        <style>
            @charset "UTF-8";
            body {
                background: #cfd8dc;
            }

            .credit-card {
                color: #FFF;
                font-family: Verdana;
            }

            .credit-card button {
                bottom: 22px;
                right: 34px;
                cursor: pointer;
                padding: 5px 20px;
            }

            .credit-card input {
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                padding: 5px 5px;
                margin: 0 1%;
                font-size: 25px;
                color: #FFF;
                text-shadow: 0 2px 1px rgba(0, 0, 0, 0.5);
                text-align: center;
                width: 23%;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 5px;
                border: 1px solid white;
                transition: 0.2s;
            }

            .credit-card input:invalid {
                background: rgba(135, 30, 30, 0.33);
            }

            .credit-card input:focus {
                background: rgba(255, 255, 255, 0.05);
                outline: none;
            }

            .credit-card .number {
                text-align: center;
                position: absolute;
                top: 150px;
                left: 32px;
                right: 32px;
                font-size: 0;
                /* fix for whitespace bug */
            }

            .credit-card .number:after {
                font-size: 18px;
                position: absolute;
                bottom: -30px;
                left: 3px;
            }

            .credit-card .date {
                position: absolute;
                bottom: 70px;
                left: 110px;
            }

            .credit-card .date input {
                font-size: 1.2em;
                padding: 5px 5px;
                margin: 0;
                width: 12%;
            }

            .credit-card .date span {
                display: inline-block;
                font-size: 12px;
                letter-spacing: -1px;
                vertical-align: text-bottom;
                text-transform: uppercase;
                line-height: 12px;
            }

            .credit-card .date span:after {
                content: '▶';
                float: right;
                margin: -5px 5px 0 5px;
            }

            :-moz-submit-invalid {
                box-shadow: none;
            }

            :-moz-ui-invalid {
                box-shadow: none;
            }

            /* just the graphics */
            .credit-card {
                -webkit-backface-visibility: hidden;
                width: 500px;
                height: 311px;
                margin: 0px auto;
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                box-shadow: 1px 1px 0 rgba(25, 25, 112, 0.3), -1px -1px 0 rgba(255, 255, 255, 0.4) inset, 0 0 3vw rgba(25, 25, 112, 0.5);
                border-radius: 10px;
                transition: 3s;
                background: #2b8b46;
            }

            .credit-card.valid {
                background-color: #3f8f26;
                transition: 0.12s;
            }

            .credit-card.invalid {
                background-color: #8c2020;
                -webkit-animation: 0.5s 1 shake linear;
                animation: 0.5s 1 shake linear;
                transition: 0.12s;
            }

            @keyframes shake {
                20% {
                    transform: translateX(-10px);
                }
                40% {
                    transform: translateX(9px);
                }
                60% {
                    transform: translateX(-5px);
                }
                80% {
                    transform: translateX(4px);
                }
            }

            @-webkit-keyframes shake {
                20% {
                    -webkit-transform: translateX(-10px);
                }
                40% {
                    -webkit-transform: translateX(9px);
                }
                60% {
                    -webkit-transform: translateX(-5px);
                }
                80% {
                    -webkit-transform: translateX(4px);
                }
            }
        </style>
        <script>
            // multifield - connects several input fields to each-other
            // By Yair Even Or / 2011 / dropthebit.com
            ;(function () {
                var fixedEvent = 0;

                /* fix a bug in Chrome where 'keypress' isn't fired for "non-visisble" keys */


                function funnel(e) {
                    // some pre-validation using HTML5 pattern attribute to allow only digits
                    if (e.charCode && this.pattern) {
                        var regex = this.pattern,
                            char = String.fromCharCode(e.charCode),
                            valid = new RegExp("^" + regex + "$").test(char);
                        console.log(valid);
                        if (!valid)
                            return false;
                    }

                    fixedEvent++;
                    var that = this;
                    setTimeout(function () {
                        keypress.call(that, e);
                        fixedEvent = 0;
                    }, 0);
                }

                function keypress(e) {
                    var nextPrevField,
                        sel = [this.selectionStart, this.selectionEnd];

                    if (!e.charCode && e.keyCode != 37 && e.keyCode != 39 && e.keyCode != 8)
                        return;

                    // if hit Backspace key when caret was at the beginning, or if the 'left' arrow key was pressed and the caret was at the start -> go back to previous field
                    else if ((e.keyCode == 8 && sel[1] == 0) || (e.keyCode == 37 && sel[1] == 0))
                        setCaret($(this).prev(':text')[0], 100);

                    // if the 'right' arrow key was pressed and caret was at the end -> advance to the next field
                    else if (e.keyCode == 39 && sel[1] == this.value.length)
                        setCaret($(this).next(':text')[0], 0);

                    // automatically move to the next field once user has filled the current one completely
                    else if (e.charCode && sel[1] == sel[0] && sel[0] == this.maxLength)
                        setCaret($(this).next(':text')[0], 100);

                    function setCaret(input, pos) {
                        if (!input) return;
                        if (input.setSelectionRange) {
                            input.focus();
                            input.setSelectionRange(pos, pos);
                        } else if (input.createTextRange) {
                            var range = input.createTextRange();
                            range.collapse(true);
                            range.moveEnd('character', pos);
                            range.moveStart('character', pos);
                            range.select();
                        }
                    }

                    combine.apply(this);
                };

                // After each 'change' event of any of the fields, combine all the values to the hidden input.
                function combine() {
                    var hidden = $(this).siblings('input[type=hidden]').val('')[0];
                    $(this.parentNode).find('input:not(:hidden)').each(function () {
                        hidden.value += this.value;
                    });
                }

                $('div.multifield').on({
                    'keydown.multifeild': funnel,
                    'keypress.multifeild': funnel,
                    'change.multifeild': combine
                }, 'input');
            })();

            // Mod-10 general validator
            // By Yair Even Or / 2011 / Dropthebit.com
            function mod10_validation(num) {
                if (!num) return false;
                num = num.replace(/-/g, '');

                var calc, i, check, checksum = 0, r = [2, 1]; // alternating routing table (cnofigured for credit cards)

                // iterate on all the numbers in 'num'
                for (i = num.length - 1; i--;) {
                    calc = num.charAt(i) * r[i % r.length];
                    // handle cases where it's a 2 digits number
                    calc = ((calc / 10) | 0) + (calc % 10);
                    checksum += calc;
                }
                check = (10 - (checksum % 10)) % 10; // make sure to get '0' if checksum is '10'
                checkDigit = num % 10;

                return check == checkDigit;
            }

            // a quick validation just for this dem
            var timer;
            $('button').on('click', function () {
            });
        </script>
        <!-- JAVASCRIPT FRAME-BREAKER CODE TO PROVIDE PROTECTION AGAINST IFRAME CLICK-JACKING -->
        <script type="text/javascript">
            if (self === top) {
                var antiClickjack = document.getElementById("antiClickjack");
                antiClickjack.parentNode.removeChild(antiClickjack);
            } else {
                top.location = self.location;
            }

            PaymentSession.configure({
                session: "<?=$sessionId?>",
                fields: {
                    // ATTACH HOSTED FIELDS TO YOUR PAYMENT PAGE FOR A CREDIT CARD
                    card: {
                        number: "#card-number",
                        securityCode: "#security-code",
                        expiryMonth: "#expiry-month",
                        expiryYear: "#expiry-year"
                    }
                },
                //SPECIFY YOUR MITIGATION OPTION HERE
                frameEmbeddingMitigation: ["javascript"],
                callbacks: {
                    initialized: function (response) {
                        // HANDLE INITIALIZATION RESPONSE
                    },
                    formSessionUpdate: function (response) {
                        // HANDLE RESPONSE FOR UPDATE SESSION
                        if (response.status) {
                            if ("ok" == response.status) {
                                window.location.href = "https://partnerapi.virtualsoft.tech/Payment/Return?id=<?=$_GET["id"]?>";

                            } else if ("fields_in_error" == response.status) {
                                var state = 'invalid';


                                $('.credit-card').addClass(state);
                                clearTimeout(timer);
                                timer = setTimeout(function () {
                                    $('.credit-card').removeClass(state);
                                }, 1000);

                                var errors='';
                                if (response.errors.cardNumber) {
                                    errors = ("Card number invalid or missing.");
                                }
                                if (response.errors.expiryYear) {
                                    errors = ("Expiry year invalid or missing.");
                                }
                                if (response.errors.expiryMonth) {
                                    errors = ("Expiry month invalid or missing.");
                                }
                                if (response.errors.securityCode) {
                                    errors = ("Security code invalid.");
                                }

                                swal({
                                    title: "Error!",
                                    text: 'Error, please check the fields entered, or the session has expired. ' + errors,
                                    timer: 10000,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Ok'

                                });

                                /* console.log("Session update failed with field errors.");
                                 if (response.errors.cardNumber) {
                                     console.log("Card number invalid or missing.");
                                 }
                                 if (response.errors.expiryYear) {
                                     console.log("Expiry year invalid or missing.");
                                 }
                                 if (response.errors.expiryMonth) {
                                     console.log("Expiry month invalid or missing.");
                                 }
                                 if (response.errors.securityCode) {
                                     console.log("Security code invalid.");
                                 }*/
                            } else if ("request_timeout" == response.status) {
                                var state = 'invalid';


                                $('.credit-card').addClass(state);
                                clearTimeout(timer);
                                timer = setTimeout(function () {
                                    $('.credit-card').removeClass(state);
                                }, 1000);

                                swal({
                                    title: "Error!",
                                    text: 'Error, We have a problem in communication with Sagicor, please try again later',
                                    timer: 10000,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Ok'

                                });
                                //console.log("Session update failed with request timeout: " + response.errors.message);
                            } else if ("system_error" == response.status) {

                                var state = 'invalid';


                                $('.credit-card').addClass(state);
                                clearTimeout(timer);
                                timer = setTimeout(function () {
                                    $('.credit-card').removeClass(state);
                                }, 1000);

                                swal({
                                    title: "Error!",
                                    text: 'Error, We have found a problem, try again later. ' + response.errors.message,
                                    timer: 10000,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Ok'

                                });
                                //console.log("Session update failed with system error: " + response.errors.message);
                            }
                        } else {
                            var state = 'invalid';


                            $('.credit-card').addClass(state);
                            clearTimeout(timer);
                            timer = setTimeout(function () {
                                $('.credit-card').removeClass(state);
                            }, 1000);

                            swal({
                                title: "Error!",
                                text: 'Error, please check the fields entered, or the session has expired',
                                timer: 10000,
                                showConfirmButton: true,
                                confirmButtonText: 'Ok'

                            });
                            //console.log("Session update failed: " + response);
                        }
                    }
                }
            });

            function pay() {
                // UPDATE THE SESSION WITH THE INPUT FROM HOSTED FIELDS
                PaymentSession.updateSessionFromForm('card');
            }


            function payWithCardSave(id, card) {
                $.ajax({
                    //Valida las credenciales de acceso
                    type: 'POST',
                    url: "https://partnerapi.virtualsoft.tech/Payment/ReturnSagicorCard",
                    dataType: "text",
                    cache: false,
                    data: {
                        id: id,
                        card: card
                    },
                    error: function (msg) {
                        swal({
                            title: "Error!",
                            text: "Ocurrio un error, intentalo de nuevo mas tardde",
                            timer: 10000,
                            showConfirmButton: true,
                            confirmButtonText: "OK"

                        });
                    },
                    success: function (resultado_string) {
                        var resultado = JSON.parse(resultado_string);
                        //Verifica cual fue el resultado del cierre
                        console.log(resultado);
                        if (resultado.HasError == "false" || resultado.HasError == false) {
                            window.location.href = resultado.Redirection;

                        } else {
                            swal({
                                title: "Error!",
                                text: "Ocurrio un error, intentalo de nuevo mas tardde",
                                timer: 10000,
                                showConfirmButton: true,
                                confirmButtonText: "OK"

                            });
                        }
                    }
                });
            }
        </script>

        <style>
            body {
                background: #2c8c47 !important;
            }
        </style>
        </body>
        </html>


        <?php
    }
} catch (Exception $e) {
    print_r($e);
}
?>
