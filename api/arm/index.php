<?php

if($_REQUEST['pwd'] != $_ENV['ARM_KEY_DANIEL']
    && $_REQUEST['pwd'] != $_ENV['ARM_KEY_JULIAN']
    && $_REQUEST['pwd'] !=$_ENV['ARM_KEY_JERSON']
    && $_REQUEST['pwd'] !=$_ENV['ARM_KEY_ALGARIN']
    && $_REQUEST['pwd'] !=$_ENV['ARM_KEY_PORRAS']
){

exit();
}



$class='';

if($_REQUEST['ss'] == '' &&($_REQUEST['setst'] == '1' || $_REQUEST['setst'] == '2') ){


    $message = "*CONTINGENCIA: ".$_REQUEST['setst']." * " . $_REQUEST['pwd'] . " - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#alarm-platform' > /dev/null & ");

    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?setstatus='.$_REQUEST['setst']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));
    header( "Location: https://arm.virtualsoft.tech/?pwd=".$_REQUEST['pwd'] );
    die;
}

$ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus='.$_SERVER['HOSTNAME']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 300);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
$result = (curl_exec($ch));

if($result =='BLOCKED'){
    $class='class="show"';

}

if($_REQUEST['controlbox'] !='' || true) {

    if($_REQUEST['ss'] != '' && ($_REQUEST['setst'] == '1' || $_REQUEST['setst'] == '2') ){


        $message = "*CONTINGENCIA: SERVER ".$_REQUEST['ss']." * " . $_REQUEST['setst']." * " . $_REQUEST['pwd']  . " - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#alarm-platform' > /dev/null & ");

        $ch = curl_init('http://admin3.local/cron/reqHabServ.php?setstatus='.$_REQUEST['setst'].'&hn='.$_REQUEST['ss']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
        $result = (curl_exec($ch));
        header( "Location: https://arm.virtualsoft.tech/?pwd=".$_REQUEST['pwd'] ."&controlbox=1" );
        die;
    }

    $checked1='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=1');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked1='';

    }
    $checked2='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=2');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked2='';

    }
    $checked3='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=3');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked3='';

    }
    $checked4='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=4');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked4='';

    }
    $checked5='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=5');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked5='';

    }
    $checked6='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=6');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked6='';

    }
    $checked7='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=7');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked7='';

    }
    $checked8='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=8');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked8='';

    }
    $checked9='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=9');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked9='';

    }
    $checked10='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=10');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked10='';

    }
    $checked11='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=11');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked11='';

    }
    $checked12='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=12');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked12='';

    }
    $checked13='checked';
    $ch = curl_init('http://admin3.local/cron/reqHabServ.php?getstatus=13');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if($result =='BLOCKED'){
        $checked13='';

    }
    ?>

    <fieldset>
        <div class='legend'><span>power switchs</span></div>
        <div class='checkboxGroup'>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 1</div>
            <label class='checkboxControl control-1'>
                <input type='checkbox' class="1" <?=$checked1?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 2</div>
            <label class='checkboxControl control-2'>
                <input type='checkbox' class="2"   <?=$checked2?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 3</div>
            <label class='checkboxControl control-3'>
                <input type='checkbox' class="3"   <?=$checked3?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 4</div>
            <label class='checkboxControl control-4'>
                <input type='checkbox' class="4"   <?=$checked4?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 5</div>
            <label class='checkboxControl control-5'>
                <input type='checkbox'  class="5"  <?=$checked5?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 6</div>
            <label class='checkboxControl control-6'>
                <input type='checkbox' class="6"   <?=$checked6?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 7</div>
            <label class='checkboxControl control-7'>
                <input type='checkbox' class="7"   <?=$checked7?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 8</div>
            <label class='checkboxControl control-8'>
                <input type='checkbox' class="8"   <?=$checked8?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 9</div>
            <label class='checkboxControl control-9'>
                <input type='checkbox' class="9"   <?=$checked9?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 10</div>
            <label class='checkboxControl control-10'>
                <input type='checkbox' class="10"   <?=$checked10?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 11</div>
            <label class='checkboxControl control-11'>
                <input type='checkbox' class="11"   <?=$checked11?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 12</div>
            <label class='checkboxControl control-13'>
                <input type='checkbox' class="12"   <?=$checked12?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
            <div style="color: #fcfc55;text-align: left;margin-bottom: 4px;">Server 13</div>
            <label class='checkboxControl control-13'>
                <input type='checkbox' class="13"   <?=$checked13?>/>
                <div>ΟΙ</div><b></b>
                <span class='indicator'></span>
            </label>
        </div>
    </fieldset>

    <div class="button-seld">


        <div class="warning"></div>
        <div class="base">
            <button id="activate">
                <span></span>
            </button>
        </div>
        <div class="box" id="cover">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>

        </div>
        <div class="hinges"></div>
        <div class="text">

        </div>
        <div id="panel">
            <div id="msg">DEVICE SELF-DESTRUCTION </div>
            <div id="time">9</div>
            <span id="abort">ABORT</span>
            <span id="detonate">DETONATE</span>
        </div>
        <div id="turn-off"></div>
        <div id="closing"></div>
        <div id="restart"><button id="reload"></button></div>
        <div id="mute"></div>
        <audio id="alarm">
            <source src="https://cdn.josetxu.com/audio/self-destruct-count.mp3" type="audio/mpeg">
        </audio>
        <style>
            .button-seld {
                margin: 0;
                padding: 0;
                width: 100vw;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                overflow: hidden;
                background-color: #4f9dad;
                border-top: 1px dotted #9eeffb;
            }

            .base {
                background: #cacaca;
                width: 20vmin;
                border-radius: 27vmin;
                box-shadow: 0 6vmin 0.15vmin 0vmin #777, 0 4vmin 0.15vmin 0vmin #777, 0 2vmin 0.15vmin 0vmin #777;
                padding: 0vmin 2vmin 2vmin 2vmin;
                z-index: 1;
                transform: rotateX(60deg) rotateZ(0deg);
                margin-top: -4.5vmin;
                height: 22vmin;
            }

            button#activate {
                background: #d60505;
                border: 0;
                width: 20vmin;
                height: 19vmin;
                border-radius: 100%;
                position: relative;
                cursor: pointer;
                outline: none;
                z-index: 2;
                box-shadow: 0 4vmin 0.15vmin 0vmin #af0000, 0 2vmin 0.15vmin 0vmin #af0000;
                top: -2.5vmin;
                border: 0.5vmin solid #af0000a1;
                transition: all 0.25s ease 0s;
            }

            button#activate:hover {
                box-shadow: 0 3vmin 0.15vmin 0vmin #af0000, 0 1vmin 0.15vmin 0vmin #af0000;
                top: -1.5vmin;
                transition: all 0.5s ease 0s;
            }
            button#activate:active, button#activate.pushed {
                box-shadow: 0 1vmin 0.15vmin 0vmin #af0000, 0 1vmin 0.15vmin 0vmin #af0000;
                top: 0.5vmin;
                transition: all 0.25s ease 0s;
            }
            button#activate.pushed {
                box-shadow: 0 0 20px 10px #ff3c3c, 0 0 100px 50px #ff2828;
                background: #ff0000;
                border-bottom: 3px solid #00000020;
            }


            .box {
                transform: rotateX(-35deg) rotateY(45deg) rotateZ(0deg) rotate3d(1, 0, 0, 90deg);
                transform-origin: center top;
                transform-style: preserve-3d;
                width: 45vmin;
                position: absolute;
                z-index: 5;
                margin-top: 27vmin;
                transition: transform 1s ease 0s;
                cursor: pointer;
                height: 45vmin;
                margin-left: -32vmin;
            }

            .box.opened {
                transform: rotateX(-35deg) rotateY(45deg) rotateZ(0deg) rotate3d(1, 0, 0, 180deg);
            }

            .box div {
                position: absolute;
                width: 45vmin;
                height: 45vmin;
                background: #00bcd47d;
                opacity: 0.5;
                border: 3px solid #00a4b9;
                border-radius: 3px;
                box-sizing: border-box;
                box-shadow: 0 0 3px 0 #00bcd48a;
            }

            .box > div:nth-child(1) {
                opacity: 0;
            }
            .box > div:nth-child(2) {
                transform: rotateX(90deg) translate3d(0px, 5vmin, 5vmin);
                height: 10vmin;
            }
            .box > div:nth-child(3) {
                transform: rotateX(0deg) translate3d(0, 0, 10vmin);
            }
            .box > div:nth-child(4) {
                transform: rotateX(270deg) translate3d(0px, -5vmin, 40vmin);
                height: 10vmin;
            }
            .box > div:nth-child(5) {
                transform: rotateY(90deg) translate3d(-5vmin, 0, 40vmin);
                width: 10vmin;
            }
            .box > div:nth-child(6) {
                transform: rotateY(-90deg) translate3d(5vmin, 0vmin, 5vmin);
                width: 10vmin;
            }




            .grid {
                background:repeating-linear-gradient(150deg, rgba(255,255,255,0) 0, rgba(255,255,255,0) 49px, rgb(255 255 255 / 10%) 50px ,rgb(0 0 0 / 30%) 51px , rgba(255,255,255,0) 55px ), repeating-linear-gradient(30deg, rgba(255,255,255,0) 0, rgba(255,255,255,0) 49px, rgb(255 255 255 / 10%) 50px ,rgb(0 0 0 / 30%) 51px , rgba(255,255,255,0) 55px );
                position: fixed;
                width: 200vw;
                height: 150vh;
            }


            .warning {
                position: absolute;
                z-index: 0;
                width: 45vmin;
                height: 45vmin;
                background: repeating-linear-gradient(-45deg, black, black 3vmin, yellow 3vmin, yellow 6vmin);
                transform: rotateX(-35deg) rotateY(45deg) rotateZ(0deg) rotate3d(1, 0, 0, 90deg);
                box-shadow: 0 0 0 3vmin #af0000;
            }

            .warning:before {
                content: "";
                width: 80%;
                height: 80%;
                background: linear-gradient(45deg, #000000 0%, #414141 74%);
                float: left;
                margin-top: 10%;
                margin-left: 10%;
                border: 1vmin solid yellow;
                border-radius: 1vmin;
                box-sizing: border-box;
            }

            .warning:after {
                content: "WARNING:\2009 DANGER";
                color: white;
                transform: rotate(90deg);
                float: left;
                background: #af0000;
                position: absolute;
                bottom: 18.5vmin;
                left: -35vmin;
                font-size: 5vmin;
                font-family: Arial, Helvetica, serif;
                width: 49vmin;
                text-align: center;
                padding: 1vmin;
                text-shadow: 0 0 1px #000, 0 0 1px #000, 0 0 1px #000;
            }





            .hinges {
                position: absolute;
                z-index: 3;
                transform: rotateX(-35deg) rotateY(45deg) rotateZ(0deg) rotate3d(1, 0, 0, 90deg);
            }


            .hinges:before, .hinges:after {
                content: "";
                background: #2b2b2b;
                width: 5vmin;
                height: 1.5vmin;
                position: absolute;
                margin-top: -24.5vmin;
                z-index: 5;
                border: 2px solid #00000010;
                border-radius: 5px 5px 0 0;
                box-sizing: border-box;
                margin-left: -16.25vmin;
            }
            .hinges:after {
                margin-left: 13.75vmin;
                margin-top: -24.5vmin;
            }


            .box > span:before, .box > span:after {
                content: "";
                width: 5vmin;
                height: 1.5vmin;
                background: #103e4480;
                position: absolute;
                margin-left: 6vmin;
                border-radius: 0 0 5px 5px;
            }
            .box > span:after  {
                margin-left: 36vmin;
            }

            .box > span {
                transform: rotateX(89deg) translate(0.3vmin, 0.3vmin);
                position: absolute;
            }





            .text {
                position: absolute;
                margin-top: 55vmin;
                color: white;
                font-family: Arial, Helvetica, serif;
                font-size: 5vmin;
                text-shadow: 0 0 1px #000, 0 0 1px #000, 0 0 1px #000;
                perspective-origin: left;
                background: #af0000;
                padding: 1vmin;
                transform: rotateX(-35deg) rotateY(45deg) rotateZ(0deg) rotate3d(1, 0, 0, 90deg) translate(33.5vmin, -2vmin);
                text-align: center;
                width: 49vmin;

            }

            div#panel:before {
                content: "WARNING";
                top: 3vmin;
                position: relative;
                font-size: 10vmin;
                width: 100vw;
                left: 0;
                z-index: 6;
                text-shadow: 0 0 1px #fff, 0 0 3px #fff;
                border-bottom: 1vmin dotted #fff;
            }

            #panel {
                position: absolute;
                background: #ff0000d0;
                color: #ffffff;
                font-family: Arial, Helvetica, serif;
                width: 90vmin;
                box-sizing: border-box;
                font-size: 3.25vmin;
                padding: 1vmin 2vmin;
                height: 60vmin;
                box-shadow: 0 0 0 100vmin #ff000060, 0 0 0 5vmin #ff000060;
                z-index: 5;
                display: none;
                text-align: center;
                text-shadow: 0 0 1px #fff, 0 0 3px #fff, 0 0 5px #fff;
                animation: warning-ligth 1s 0s infinite;
            }
            #panel.show {
                display: block !important;
            }

            #msg {
                margin-top: 5vmin;
                text-shadow: 0 0 2px #fff;
            }

            #time {
                font-size: 10vmin;
                background: #00000080;
                max-width: 35vmin;
                margin: 6vmin auto 5vmin !important;
                position: relative;
                border-radius: 0.25vmin;
                text-shadow: 0 0 3px #000, 0 0 2px #000, 0 0 3px #000, 0 0 4px #000, 0 0 5px #000;
                padding: 1vmin 0;
            }

            #time:before {
                content: "00:0";:
            }

            #abort {
                background: #ffffffb8;
                color: #d30303;
                cursor: pointer;
                padding: 1vmin 2.75vmin;
                font-size: 6vmin;
                border-radius: 0.25vmin;
                font-weight: bold;
                animation: highlight 1s 0s infinite;
            }

            #abort:hover {
                background: #ffffff;
                box-shadow: 0 0 15px 5px #fff;
            }




            @keyframes highlight {
                50% { box-shadow: 0 0 15px 5px #fff;}
            }








            div#turn-off {
                position: fixed;
                background: #ffffff80;
                left: 0;
                width: 100vw;
                height: 0vh;
                z-index: 7;
            }

            div#turn-off:before, div#turn-off:after {
                content: "";
                position: fixed;
                left: 0;
                top: 0;
                height: 0vh;
                background: #000;
                width: 100vw;
                transition: height 0.5s ease 0s;
            }
            div#turn-off:after {
                top: inherit;
                bottom: 0;
            }


            div#turn-off.close {
                height: 100vh;
            }

            div#turn-off.close:before, div#turn-off.close:after {
                transition: height 0.1s ease 0.1s;
                height: 49.75vh;
            }




            #time.crono {
                background: #ffffffba;
                transition: background 0.5s ease 0s;
                color: #ff0000;
                text-shadow: 0 0 1px #ffffff, 0 0 2px #ffffff, 0 0 2px #ffffff;
            }
            #detonate {
                display: none;
                color: #fff;
                z-index: 5;
                font-size: 8vmin;
                font-family: Arial, Helvetica, serif;
                text-shadow: 0 0 1px #fff, 0 0 2px #fff, 0 0 3px #fff;
            }
            #detonate.show {
                display: block;
                animation: blink 0.25s 0s infinite;
            }

            #abort.hide {
                display: none;
            }


            @keyframes blink {
                50% { opacity: 0;}
            }








            #closing {
                width: 100vw;
                height: 100vh;
                left: 0;
                position: absolute;
            }

            div#closing:before, div#closing:after {
                content: "";
                width: 50vw;
                height: 1.5vh;
                left: -50vw;
                top: 49vh;
                position: absolute;
                background: #000000;
                z-index: 7;
                transition: left 0.2s ease 0s;
            }

            div#closing:after {
                right: -50vw;
                transition: right 0.2s ease 0s;
                left: initial;
            }


            div#closing.close:before {
                left: 0;
                transition: left 0.2s ease 0.2s;
            }


            div#closing.close:after {
                right: 0;
                transition: right 0.2s ease 0.2s;
            }



            #restart {
                position: absolute;
                z-index: 8;
                display: none;
            }
            #reload {
                position: absolute;
                z-index: 8;
                width: 10vmin;
                height: 10vmin;
                border-radius: 100%;
                border: 0;
                margin-top: -5vmin;
                margin-left: -2.5vmin;
                opacity: 0;
                cursor: pointer;
                transform: rotate(0deg);
                transition: transform 0.5s ease 0s;
                outline: none;
            }
            #reload:hover {
                background: #ef0000;
                transform: rotate(360deg);
                transition: transform 0.5s ease 0s;
            }
            #restart.show {
                display: block;
            }

            #restart.show #reload {
                animation: refresh 3.5s 0s 1;
                opacity:1;
            }


            @keyframes refresh {
                0% { opacity: 0; }
                50% { opacity: 0; }
                100% { opacity: 1; }
            }


            button#reload:before {
                content: "";
                width: 6vmin;
                height: 6vmin;
                position: absolute;
                left: 2vmin;
                top: 2vmin;
                border-radius: 100%;
                border: 1vmin solid #000;
                box-sizing: border-box;
                border-bottom-color: transparent;
            }

            button#reload:after {
                content: "";
                border: 1.25vmin solid transparent;
                border-top: 2vmin solid black;
                position: absolute;
                transform: rotate(40deg) translate(0.5vmin, 1.25vmin);
            }





            @keyframes warning-ligth {
                0% { box-shadow: 0 0 0 100vmin #ff000060, 0 0 0 5vmin #ff000060; }
                50% { box-shadow: 0 0 0 100vmin #ff000020, 0 0 0 5vmin #ff000020; }
            }



            #mute {
                position: absolute;
                bottom: 1vmin;
                right: 1vmin;
                background: #8bc34a80;
                width: 6vmin;
                height: 6vmin;
                cursor: pointer;
                border: 0.5vmin solid #151515;
            }
            #mute.muted {
                background: #ff000080;
            }

            #mute:before {
                content: "";
                border: 0.75vmin solid transparent;
                height: 2vmin;
                border-right: 2vmin solid #151515;
                position: absolute;
                border-left-width: 0;
                top: 1.25vmin;
                right: 1.25vmin;
            }
            #mute:after {
                content: "";
                border: 0vmin solid transparent;
                height: 2vmin;
                border-right: 1.5vmin solid #151515;
                position: absolute;
                top: 2vmin;
                right: 3.5vmin;
            }
        </style>
        <script>
            var theCount;
            var alarm = document.getElementById("alarm");
            var panel = document.getElementById("panel");
            var turnOff = document.getElementById("turn-off");
            var turnOffHor = document.getElementById("closing");
            var detonate = document.getElementById("detonate");
            alarm.volume = 0.5; //volume level - (changed from 0.25 to 0.5)

            var time = document.getElementById("time");
            function showCountDown() {
                time.innerText = time.innerText - 1;
                if (time.innerText == 0) {
                    clearInterval(theCount);
                    time.classList.add("crono");
                    abort.classList.add("hide");
                    detonate.classList.add("show");
                    setTimeout(function () {
                        turnOff.classList.add("close");
                        turnOffHor.classList.add("close");
                        reload.classList.add("show");
                        alarm.pause();
                    }, 1500);
                }
            }

            var cover = document.getElementById("cover");
            cover.addEventListener("click", function () {
                if (this.className == "box") this.classList.add("opened");
                else this.classList.remove("opened");
            });

            var btn = document.getElementById("activate");
            activate.addEventListener("click", function () {
                this.classList.add("pushed");
                alarm.load();
                alarm.currentTime = 10.1;
                alarm.play();

                var url = window.location.href;
                if (url.indexOf('?') > -1){
                    url += '&setst=2'
                }else{
                    url += '?setst=2'
                }
                window.location.href = url;


                setTimeout(function () {
                    panel.classList.add("show");
                    theCount = setInterval(showCountDown, 1000);
                    alarm.load();
                    alarm.play();
                }, 500);
            });

            var abort = document.getElementById("abort");
            abort.addEventListener("click", function () {
                btn.classList.remove("pushed");
                panel.classList.remove("show");
                clearInterval(theCount);
                time.innerText = 9;
                alarm.pause();
                alarm.currentTime = 10;
                alarm.play();


                var url = window.location.href;
                if (url.indexOf('?') > -1){
                    url += '&setst=1'
                }else{
                    url += '?setst=1'
                }
                window.location.href = url;


            });

            var reload = document.getElementById("restart");
            reload.addEventListener("click", function () {
                panel.classList.remove("show");
                turnOff.classList.remove("close");
                turnOffHor.classList.remove("close");
                abort.classList.remove("hide");
                detonate.classList.remove("show");
                cover.classList.remove("opened");
                btn.classList.remove("pushed");
                this.classList.remove("show");
                time.classList.remove("crono");
                time.innerText = 9;
            });

            setTimeout(function () {
                cover.classList.remove("opened");
            }, 100);

            var mute = document.getElementById("mute");
            mute.addEventListener("click", function () {
                if (this.className == "muted") {
                    alarm.muted = false;
                    this.classList.remove("muted");
                } else {
                    alarm.muted = true;
                    this.classList.add("muted");
                }
            });

        </script>
    </div>

    <style>
        *{ margin:0; padding:0; }
        html, body{ height:100%; }
        body{ font:15px/1 arial; text-align:center; background:#509DAD; }
        body:before{ content:''; display:inline-block; height:100%; vertical-align:middle; }
        fieldset{ display:inline-block; vertical-align:middle; border:none; width:370px; }
        .legend{ color:rgba(0,0,0,.7); font-size:12px; margin-bottom:14px; height:15px; border-color:#2E6677; border-style:solid; border-width:1px 1px 0 1px; box-shadow:1px 1px 0 rgba(255,255,255,0.2) inset; text-shadow:0 1px rgba(255,255,255,.3); }
        .legend span{ text-transform:uppercase; position:relative; top:-5px; padding:0 10px; background:#509DAD; display:inline-block; }
        .checkboxGroup{ display:inline-block; vertical-align:middle; width:150px; border:none; }
        /*------- Horizontal power swtich ---------*/
        .checkboxControl{
            border:2px solid #102838; border-radius:7px; display:inline-block; width:100px; height:50px; padding-top:1px; position:relative; vertical-align:middle; margin:0 60px 10px 0; color:#297597;
            box-shadow: 0 0 5px rgba(255,255,255,.4),
            0 2px 1px -1px rgba(255,255,255,.7) inset,
            8px 0 5px -5px #02425C inset,
            -8px 0 5px -5px #02425C inset;
            -moz-user-select:none; -webkit-user-select:none;
            background:#80DCE9;
        }
        .checkboxControl input{ position:absolute; visibility:hidden; }
        .checkboxControl > div{
            background:-webkit-linear-gradient(left, #8FD9E4 0%,#A0F2FE 53%,#69DCF1 56%,#33AFCE 99%,#CEF5FF 100%);
            background:linear-gradient(to right, #8FD9E4 0%,#A0F2FE 53%,#69DCF1 56%,#33AFCE 99%,#CEF5FF 100%);
            box-shadow:-2px 0 1px 0 #A6F2FE inset;
            border-radius:5px; line-height:50px; font-weight:bold; cursor:pointer; position:relative; z-index:1; text-shadow:0 1px rgba(255,255,255,0.5);

            transform-origin:0 0; -webkit-transform-origin:0 0;
            transform:scaleX(0.93); -webkit-transform:scaleX(0.93);
            transition:.1s; -webkit-transition:0.1s;
        }
        .checkboxControl div:first-letter{ letter-spacing:55px; }

        .checkboxControl :checked ~ div{
            transform-origin:100% 0; -webkit-transform-origin:100% 0;
            box-shadow:2px 0 1px 0 #A6F2FE inset;
            background:-webkit-linear-gradient(left, #CEF5FF 0%,#33AFCE 1%,#69DCF1 47%,#A0F2FE 50%,#8FD9E4 100%);
            background:linear-gradient(to right, #CEF5FF 0%,#33AFCE 1%,#69DCF1 47%,#A0F2FE 50%,#8FD9E4 100%);
        }
        /* bottom shadow of 'upper' side of the button */
        .checkboxControl > b{ position:absolute; bottom:0; right:0; width:50%; height:100%; border-radius:8px; -webkit-transform:skewY(5deg); transform:skewY(5deg); box-shadow: 0 6px 8px -5px #000; }
        .checkboxControl :checked ~ b{ right:auto; left:0; -webkit-transform:skewY(-5deg); transform:skewY(-5deg); }
        /* the light indicator to the right of the button */
        .checkboxControl .indicator{ position:absolute; top:14px; right:-20px; width:8px; height:25px; box-shadow:0 0 8px #000 inset; border:1px solid rgba(255,255,255,0.1); border-radius:15px; transition:0.2s; -webkit-transition:0.2s; }
        .checkboxControl .indicator:before{ content:''; display:inline-block; margin-top:8px; width:2px; height:8px; border-radius:10px; transition:0.5s; -webkit-transition:0.5s; }
        .checkboxControl :checked ~ .indicator:before{ box-shadow:0 0 7px 6px #BAFC58; width:6px; background:#F0F9E3; transition:0.1s; -webkit-transition:0.1s; }

        /*------- Vertical power swtich ---------*/
        .checkboxControl2{
            border:2px solid #102838; border-radius:7px; display:inline-block; vertical-align:middle; font-weight:bold;
            width:60px; height:100px; position:relative; margin:0 5px;
            color:#12678C; box-shadow:0 0 5px rgba(255,255,255,.4);
        }
        .checkboxControl2 input{ position:absolute; visibility:hidden; }
        .checkboxControl2 > div{
            background:-webkit-linear-gradient(top, #002B44 0%, #0690AC 11%, #038EAA 14%, #A0F2FE 58%, #91DBE7 96%, #B9E8E8 100%);
            background:linear-gradient(to bottom, #002B44 0%, #0690AC 11%, #038EAA 14%, #A0F2FE 58%, #91DBE7 96%, #B9E8E8 100%);
            height:100%; border-radius:5px; line-height:50px; font-we0 0 3px 0px #F95757 inset, 0 0 12px 6px #F95757tion:relative; z-index:1; cursor:pointer; text-shadow:0 1px rgba(255,255,255,0.5);
        }
        .checkboxControl2 > div:after{
            content:'Ο'; display:block; height:50%; line-height:4;
            transform-origin:0 0; -webkit-transform-origin:0 0;
        }
        .checkboxControl2 > div:before{
            content:'Ι'; display:block; height:50%; line-height:2.5;
            border-radius:80%/5px;
            box-shadow:0 8px 12px -13px #89DFED inset, 0 -2px 2px -1px rgba(255,255,255,0.8);
            transform-origin:0 100%; -webkit-transform-origin:0 100%;
            transform:scaleY(0.7); -webkit-transform:scaleY(0.7);
        }

        .checkboxControl2 :checked ~ div{
            background:-webkit-linear-gradient(bottom, #002B44 0%, #0690AC 11%, #038EAA 14%, #A0F2FE 58%, #91DBE7 96%, #B9E8E8 100%);
            background:linear-gradient(to top, #002B44 0%, #0690AC 11%, #038EAA 14%, #A0F2FE 58%, #91DBE7 96%, #B9E8E8 100%);
        }
        .checkboxControl2 :checked ~ div:before{
            border-radius:0; box-shadow:none;
            transform:none; -webkit-transform:none;
        }
        .checkboxControl2 :checked ~ div:after{
            border-radius:80%/5px;
            box-shadow:0 -8px 12px -5px #89DFED inset, 0 2px 2px 0 #0690AC;
            transform:scaleY(0.7); -webkit-transform:scaleY(0.7);
        }
        /* the light indicator to the top of the button */
        .checkboxControl2 .indicator{ position:absolute; top:-20px; left:17px; width:25px; height:8px; box-shadow:0 0 8px #000 inset; border:1px solid rgba(255,255,255,0.1); border-radius:15px; transition:0.2s; -webkit-transition:0.2s; }
        .checkboxControl2 .indicator:before{ content:''; display:block; margin:2px auto; width:8px; height:5px; border-radius:10px; transition:0.5s; -webkit-transition:0.5s; }
        .checkboxControl2 :checked ~ .indicator:before{ box-shadow:0 0 2px 0px #F95757 inset, 0 0 12px 6px #F95757; background:#FFF; transition:0.1s; -webkit-transition:0.1s; }

    </style>


    <script>
        var snd = new Audio('https://www.freesfx.co.uk/rx2/mp3s/2/2710_1329133090.mp3');
        // delegated event on inputs of checkboxControl
        document.addEventListener('change', function(e){
            var ss=e.target.className;
            var setst='2';

            if (e.target.checked) {
                setst='1';
            }

            var url = window.location.href;
            if (url.indexOf('?') > -1){
                url += '&setst='+setst+'&ss=' +ss;
            }else{
                url += '?setst='+setst+'&ss=' +ss;
            }
            window.location.href = url;

            if(e.target.parentNode.className.indexOf('checkboxControl') != -1){
                snd.currentTime = 0;
                snd.play();
            }
        });
    </script>



    <?php


    exit();


}

?>

<div class="grid"></div>

<div class="warning"></div>

<div class="base">
    <button id="activate">
        <span></span>
    </button>
</div>

<div class="box opened" id="cover">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <span></span><span></span>
</div>

<div class="hinges"></div>

<div class="text">
    <!--SELF-&thinsp;DESTRUCT-->
</div>

<div id="panel" <?=$class?> >
    <div id="msg">DEVICE SELF-DESTRUCTION </div>
    <div id="time">9</div>
    <span id="abort">ABORT</span>
    <span id="detonate">DETONATE</span>
</div>

<div id="turn-off"></div>
<div id="closing"></div>

<div id="restart"><button id="reload"></button></div>

<div id="mute"></div>

<audio id="alarm">
    <source src="https://cdn.josetxu.com/audio/self-destruct-count.mp3" type="audio/mpeg">
</audio>

<style>
    body {
        margin: 0;
        padding: 0;
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        background-color: #151515;
    }

    .base {
        background: #cacaca;
        width: 20vmin;
        border-radius: 27vmin;
        box-shadow: 0 6vmin 0.15vmin 0vmin #777, 0 4vmin 0.15vmin 0vmin #777, 0 2vmin 0.15vmin 0vmin #777;
        padding: 0vmin 2vmin 2vmin 2vmin;
        z-index: 1;
        transform: rotateX(60deg) rotateZ(0deg);
        margin-top: -4.5vmin;
        height: 22vmin;
    }

    button#activate {
        background: #d60505;
        border: 0;
        width: 20vmin;
        height: 19vmin;
        border-radius: 100%;
        position: relative;
        cursor: pointer;
        outline: none;
        z-index: 2;
        box-shadow: 0 4vmin 0.15vmin 0vmin #af0000, 0 2vmin 0.15vmin 0vmin #af0000;
        top: -2.5vmin;
        border: 0.5vmin solid #af0000a1;
        transition: all 0.25s ease 0s;
    }

    button#activate:hover {
        box-shadow: 0 3vmin 0.15vmin 0vmin #af0000, 0 1vmin 0.15vmin 0vmin #af0000;
        top: -1.5vmin;
        transition: all 0.5s ease 0s;
    }
    button#activate:active, button#activate.pushed {
        box-shadow: 0 1vmin 0.15vmin 0vmin #af0000, 0 1vmin 0.15vmin 0vmin #af0000;
        top: 0.5vmin;
        transition: all 0.25s ease 0s;
    }
    button#activate.pushed {
        box-shadow: 0 0 20px 10px #ff3c3c, 0 0 100px 50px #ff2828;
        background: #ff0000;
        border-bottom: 3px solid #00000020;
    }


    .box {
        transform: rotateX(-35deg) rotateY(45deg) rotateZ(0deg) rotate3d(1, 0, 0, 90deg);
        transform-origin: center top;
        transform-style: preserve-3d;
        width: 45vmin;
        position: absolute;
        z-index: 5;
        margin-top: 27vmin;
        transition: transform 1s ease 0s;
        cursor: pointer;
        height: 45vmin;
        margin-left: -32vmin;
    }

    .box.opened {
        transform: rotateX(-35deg) rotateY(45deg) rotateZ(0deg) rotate3d(1, 0, 0, 180deg);
    }

    .box div {
        position: absolute;
        width: 45vmin;
        height: 45vmin;
        background: #00bcd47d;
        opacity: 0.5;
        border: 3px solid #00a4b9;
        border-radius: 3px;
        box-sizing: border-box;
        box-shadow: 0 0 3px 0 #00bcd48a;
    }

    .box > div:nth-child(1) {
        opacity: 0;
    }
    .box > div:nth-child(2) {
        transform: rotateX(90deg) translate3d(0px, 5vmin, 5vmin);
        height: 10vmin;
    }
    .box > div:nth-child(3) {
        transform: rotateX(0deg) translate3d(0, 0, 10vmin);
    }
    .box > div:nth-child(4) {
        transform: rotateX(270deg) translate3d(0px, -5vmin, 40vmin);
        height: 10vmin;
    }
    .box > div:nth-child(5) {
        transform: rotateY(90deg) translate3d(-5vmin, 0, 40vmin);
        width: 10vmin;
    }
    .box > div:nth-child(6) {
        transform: rotateY(-90deg) translate3d(5vmin, 0vmin, 5vmin);
        width: 10vmin;
    }




    .grid {
        background:repeating-linear-gradient(150deg, rgba(255,255,255,0) 0, rgba(255,255,255,0) 49px, rgb(255 255 255 / 10%) 50px ,rgb(0 0 0 / 30%) 51px , rgba(255,255,255,0) 55px ), repeating-linear-gradient(30deg, rgba(255,255,255,0) 0, rgba(255,255,255,0) 49px, rgb(255 255 255 / 10%) 50px ,rgb(0 0 0 / 30%) 51px , rgba(255,255,255,0) 55px );
        position: fixed;
        width: 200vw;
        height: 150vh;
    }


    .warning {
        position: absolute;
        z-index: 0;
        width: 45vmin;
        height: 45vmin;
        background: repeating-linear-gradient(-45deg, black, black 3vmin, yellow 3vmin, yellow 6vmin);
        transform: rotateX(-35deg) rotateY(45deg) rotateZ(0deg) rotate3d(1, 0, 0, 90deg);
        box-shadow: 0 0 0 3vmin #af0000;
    }

    .warning:before {
        content: "";
        width: 80%;
        height: 80%;
        background: linear-gradient(45deg, #000000 0%, #414141 74%);
        float: left;
        margin-top: 10%;
        margin-left: 10%;
        border: 1vmin solid yellow;
        border-radius: 1vmin;
        box-sizing: border-box;
    }

    .warning:after {
        content: "WARNING:\2009 DANGER";
        color: white;
        transform: rotate(90deg);
        float: left;
        background: #af0000;
        position: absolute;
        bottom: 18.5vmin;
        left: -35vmin;
        font-size: 5vmin;
        font-family: Arial, Helvetica, serif;
        width: 49vmin;
        text-align: center;
        padding: 1vmin;
        text-shadow: 0 0 1px #000, 0 0 1px #000, 0 0 1px #000;
    }





    .hinges {
        position: absolute;
        z-index: 3;
        transform: rotateX(-35deg) rotateY(45deg) rotateZ(0deg) rotate3d(1, 0, 0, 90deg);
    }


    .hinges:before, .hinges:after {
        content: "";
        background: #2b2b2b;
        width: 5vmin;
        height: 1.5vmin;
        position: absolute;
        margin-top: -24.5vmin;
        z-index: 5;
        border: 2px solid #00000010;
        border-radius: 5px 5px 0 0;
        box-sizing: border-box;
        margin-left: -16.25vmin;
    }
    .hinges:after {
        margin-left: 13.75vmin;
        margin-top: -24.5vmin;
    }


    .box > span:before, .box > span:after {
        content: "";
        width: 5vmin;
        height: 1.5vmin;
        background: #103e4480;
        position: absolute;
        margin-left: 6vmin;
        border-radius: 0 0 5px 5px;
    }
    .box > span:after  {
        margin-left: 36vmin;
    }

    .box > span {
        transform: rotateX(89deg) translate(0.3vmin, 0.3vmin);
        position: absolute;
    }





    .text {
        position: absolute;
        margin-top: 55vmin;
        color: white;
        font-family: Arial, Helvetica, serif;
        font-size: 5vmin;
        text-shadow: 0 0 1px #000, 0 0 1px #000, 0 0 1px #000;
        perspective-origin: left;
        background: #af0000;
        padding: 1vmin;
        transform: rotateX(-35deg) rotateY(45deg) rotateZ(0deg) rotate3d(1, 0, 0, 90deg) translate(33.5vmin, -2vmin);
        text-align: center;
        width: 49vmin;

    }

    div#panel:before {
        content: "WARNING";
        top: 3vmin;
        position: relative;
        font-size: 10vmin;
        width: 100vw;
        left: 0;
        z-index: 6;
        text-shadow: 0 0 1px #fff, 0 0 3px #fff;
        border-bottom: 1vmin dotted #fff;
    }

    #panel {
        position: absolute;
        background: #ff0000d0;
        color: #ffffff;
        font-family: Arial, Helvetica, serif;
        width: 90vmin;
        box-sizing: border-box;
        font-size: 3.25vmin;
        padding: 1vmin 2vmin;
        height: 60vmin;
        box-shadow: 0 0 0 100vmin #ff000060, 0 0 0 5vmin #ff000060;
        z-index: 5;
        display: none;
        text-align: center;
        text-shadow: 0 0 1px #fff, 0 0 3px #fff, 0 0 5px #fff;
        animation: warning-ligth 1s 0s infinite;
    }
    #panel.show {
        display: block !important;
    }

    #msg {
        margin-top: 5vmin;
        text-shadow: 0 0 2px #fff;
    }

    #time {
        font-size: 10vmin;
        background: #00000080;
        max-width: 35vmin;
        margin: 6vmin auto 5vmin !important;
        position: relative;
        border-radius: 0.25vmin;
        text-shadow: 0 0 3px #000, 0 0 2px #000, 0 0 3px #000, 0 0 4px #000, 0 0 5px #000;
        padding: 1vmin 0;
    }

    #time:before {
        content: "00:0";:
    }

    #abort {
        background: #ffffffb8;
        color: #d30303;
        cursor: pointer;
        padding: 1vmin 2.75vmin;
        font-size: 6vmin;
        border-radius: 0.25vmin;
        font-weight: bold;
        animation: highlight 1s 0s infinite;
    }

    #abort:hover {
        background: #ffffff;
        box-shadow: 0 0 15px 5px #fff;
    }




    @keyframes highlight {
        50% { box-shadow: 0 0 15px 5px #fff;}
    }








    div#turn-off {
        position: fixed;
        background: #ffffff80;
        left: 0;
        width: 100vw;
        height: 0vh;
        z-index: 7;
    }

    div#turn-off:before, div#turn-off:after {
        content: "";
        position: fixed;
        left: 0;
        top: 0;
        height: 0vh;
        background: #000;
        width: 100vw;
        transition: height 0.5s ease 0s;
    }
    div#turn-off:after {
        top: inherit;
        bottom: 0;
    }


    div#turn-off.close {
        height: 100vh;
    }

    div#turn-off.close:before, div#turn-off.close:after {
        transition: height 0.1s ease 0.1s;
        height: 49.75vh;
    }




    #time.crono {
        background: #ffffffba;
        transition: background 0.5s ease 0s;
        color: #ff0000;
        text-shadow: 0 0 1px #ffffff, 0 0 2px #ffffff, 0 0 2px #ffffff;
    }
    #detonate {
        display: none;
        color: #fff;
        z-index: 5;
        font-size: 8vmin;
        font-family: Arial, Helvetica, serif;
        text-shadow: 0 0 1px #fff, 0 0 2px #fff, 0 0 3px #fff;
    }
    #detonate.show {
        display: block;
        animation: blink 0.25s 0s infinite;
    }

    #abort.hide {
        display: none;
    }


    @keyframes blink {
        50% { opacity: 0;}
    }








    #closing {
        width: 100vw;
        height: 100vh;
        left: 0;
        position: absolute;
    }

    div#closing:before, div#closing:after {
        content: "";
        width: 50vw;
        height: 1.5vh;
        left: -50vw;
        top: 49vh;
        position: absolute;
        background: #000000;
        z-index: 7;
        transition: left 0.2s ease 0s;
    }

    div#closing:after {
        right: -50vw;
        transition: right 0.2s ease 0s;
        left: initial;
    }


    div#closing.close:before {
        left: 0;
        transition: left 0.2s ease 0.2s;
    }


    div#closing.close:after {
        right: 0;
        transition: right 0.2s ease 0.2s;
    }



    #restart {
        position: absolute;
        z-index: 8;
        display: none;
    }
    #reload {
        position: absolute;
        z-index: 8;
        width: 10vmin;
        height: 10vmin;
        border-radius: 100%;
        border: 0;
        margin-top: -5vmin;
        margin-left: -2.5vmin;
        opacity: 0;
        cursor: pointer;
        transform: rotate(0deg);
        transition: transform 0.5s ease 0s;
        outline: none;
    }
    #reload:hover {
        background: #ef0000;
        transform: rotate(360deg);
        transition: transform 0.5s ease 0s;
    }
    #restart.show {
        display: block;
    }

    #restart.show #reload {
        animation: refresh 3.5s 0s 1;
        opacity:1;
    }


    @keyframes refresh {
        0% { opacity: 0; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }


    button#reload:before {
        content: "";
        width: 6vmin;
        height: 6vmin;
        position: absolute;
        left: 2vmin;
        top: 2vmin;
        border-radius: 100%;
        border: 1vmin solid #000;
        box-sizing: border-box;
        border-bottom-color: transparent;
    }

    button#reload:after {
        content: "";
        border: 1.25vmin solid transparent;
        border-top: 2vmin solid black;
        position: absolute;
        transform: rotate(40deg) translate(0.5vmin, 1.25vmin);
    }





    @keyframes warning-ligth {
        0% { box-shadow: 0 0 0 100vmin #ff000060, 0 0 0 5vmin #ff000060; }
        50% { box-shadow: 0 0 0 100vmin #ff000020, 0 0 0 5vmin #ff000020; }
    }



    #mute {
        position: absolute;
        bottom: 1vmin;
        right: 1vmin;
        background: #8bc34a80;
        width: 6vmin;
        height: 6vmin;
        cursor: pointer;
        border: 0.5vmin solid #151515;
    }
    #mute.muted {
        background: #ff000080;
    }

    #mute:before {
        content: "";
        border: 0.75vmin solid transparent;
        height: 2vmin;
        border-right: 2vmin solid #151515;
        position: absolute;
        border-left-width: 0;
        top: 1.25vmin;
        right: 1.25vmin;
    }
    #mute:after {
        content: "";
        border: 0vmin solid transparent;
        height: 2vmin;
        border-right: 1.5vmin solid #151515;
        position: absolute;
        top: 2vmin;
        right: 3.5vmin;
    }
</style>

<script>
    var theCount;
    var alarm = document.getElementById("alarm");
    var panel = document.getElementById("panel");
    var turnOff = document.getElementById("turn-off");
    var turnOffHor = document.getElementById("closing");
    var detonate = document.getElementById("detonate");
    alarm.volume = 0.5; //volume level - (changed from 0.25 to 0.5)

    var time = document.getElementById("time");
    function showCountDown() {
        time.innerText = time.innerText - 1;
        if (time.innerText == 0) {
            clearInterval(theCount);
            time.classList.add("crono");
            abort.classList.add("hide");
            detonate.classList.add("show");
            setTimeout(function () {
                turnOff.classList.add("close");
                turnOffHor.classList.add("close");
                reload.classList.add("show");
                alarm.pause();
            }, 1500);
        }
    }

    var cover = document.getElementById("cover");
    cover.addEventListener("click", function () {
        if (this.className == "box") this.classList.add("opened");
        else this.classList.remove("opened");
    });

    var btn = document.getElementById("activate");
    activate.addEventListener("click", function () {
        this.classList.add("pushed");
        alarm.load();
        alarm.currentTime = 10.1;
        alarm.play();

        var url = window.location.href;
        if (url.indexOf('?') > -1){
            url += '&setst=2'
        }else{
            url += '?setst=2'
        }
        window.location.href = url;


        setTimeout(function () {
            panel.classList.add("show");
            theCount = setInterval(showCountDown, 1000);
            alarm.load();
            alarm.play();
        }, 500);
    });

    var abort = document.getElementById("abort");
    abort.addEventListener("click", function () {
        btn.classList.remove("pushed");
        panel.classList.remove("show");
        clearInterval(theCount);
        time.innerText = 9;
        alarm.pause();
        alarm.currentTime = 10;
        alarm.play();


        var url = window.location.href;
        if (url.indexOf('?') > -1){
            url += '&setst=1'
        }else{
            url += '?setst=1'
        }
        window.location.href = url;


    });

    var reload = document.getElementById("restart");
    reload.addEventListener("click", function () {
        panel.classList.remove("show");
        turnOff.classList.remove("close");
        turnOffHor.classList.remove("close");
        abort.classList.remove("hide");
        detonate.classList.remove("show");
        cover.classList.remove("opened");
        btn.classList.remove("pushed");
        this.classList.remove("show");
        time.classList.remove("crono");
        time.innerText = 9;
    });

    setTimeout(function () {
        cover.classList.remove("opened");
    }, 100);

    var mute = document.getElementById("mute");
    mute.addEventListener("click", function () {
        if (this.className == "muted") {
            alarm.muted = false;
            this.classList.remove("muted");
        } else {
            alarm.muted = true;
            this.classList.add("muted");
        }
    });

</script>
