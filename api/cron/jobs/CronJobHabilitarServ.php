<?php
$minute = intval(date('i'));

if($_SERVER['HOSTNAME'] ==''){
    $_SERVER['HOSTNAME'] =$argv[1];
}

switch ($argv[1]){

    case '9':
        $_SERVER['HOSTNAME'] =$argv[1];
        break;
    case '10':
        $_SERVER['HOSTNAME'] =$argv[1];
        break;
    case '11':
        $_SERVER['HOSTNAME'] =$argv[1];
        break;
    default:
        break;
}

if($_SERVER['HOSTNAME'] != 'dallasadmin01'
    &&  $_SERVER['HOSTNAME'] != 'dallasadmin02'
    &&  $_SERVER['HOSTNAME'] != 'dallasadmin03'
    &&  $_SERVER['HOSTNAME'] != 'dallasadmin05'
    &&  $_SERVER['HOSTNAME'] != 'dallasadmin04'
    &&  $_SERVER['HOSTNAME'] != 'dallasadmin06'
    &&  $_SERVER['HOSTNAME'] != 'dallasadmin02-clone'
    &&  $_SERVER['HOSTNAME'] != 'dallasadmin02-clone'

    &&  $_SERVER['HOSTNAME'] != 'Gadmin9'
    &&  $_SERVER['HOSTNAME'] != 'Gadmin10'
    &&  $_SERVER['HOSTNAME'] != 'Gadmin11'
    &&  $_SERVER['HOSTNAME'] != '9'
    &&  $_SERVER['HOSTNAME'] != '10'
    &&  $_SERVER['HOSTNAME'] != '11'

    &&  $_SERVER['HOSTNAME'] != 'admin1'
    &&  $_SERVER['HOSTNAME'] != 'admin2'
    &&  $_SERVER['HOSTNAME'] != 'admin3'
    &&  $_SERVER['HOSTNAME'] != 'admin4'
    &&  $_SERVER['HOSTNAME'] != 'admin5'
    &&  $_SERVER['HOSTNAME'] != 'admin6'
    &&  $_SERVER['HOSTNAME'] != 'admin7'
    &&  $_SERVER['HOSTNAME'] != 'admin8'


){
    exit();
}

switch ($_SERVER['HOSTNAME']){

    case 'admin1':
        if(in_array($minute,array(5,15,25,35,45,55))){
            exit();
        }
        if(in_array($minute,array(1,11,21,31,41,51))){
            exit();
        }
        break;
    case 'admin2':
        if(in_array($minute,array(1,11,21,31,41,51))){
            exit();
        }
        if(in_array($minute,array(5,15,25,35,45,55))){
            exit();
        }
        break;
    case 'admin3':
        if(in_array($minute,array(2,12,22,32,42,52))){
            exit();
        }
        if(in_array($minute,array(3,13,23,33,43,53))){
            exit();
        }
        break;
    case 'admin4':
        if(in_array($minute,array(3,13,23,33,43,53))){
            exit();
        }
        if(in_array($minute,array(2,12,22,32,42,52))){
            exit();
        }
        break;
    case 'admin5':
        if(in_array($minute,array(4,14,24,34,44,54))){
            exit();
        }
        if(in_array($minute,array(6,16,26,36,46,56))){
            exit();
        }
        break;
    case 'admin6':
        if(in_array($minute,array(6,16,26,36,46,56))){
            exit();
        }
        if(in_array($minute,array(4,14,24,34,44,54))){
        exit();

        }
        break;
    case 'admin7':

        if(in_array($minute,array(5,15,25,35,45,55))){
            exit();
        }
        if(in_array($minute,array(7,17,27,37,47,57))){
            exit();
        }
        break;
    case 'admin8':

        if(in_array($minute,array(5,15,25,35,45,55))){
            exit();
        }
        if(in_array($minute,array(7,17,27,37,47,57))){
            exit();
        }
        break;
    default:
        exit();
        break;
}

$data = array(
);

$ch = curl_init('http://admin11.local/cron/reqHabServ.php?hn='.$_SERVER['HOSTNAME']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 300);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//$rs = curl_exec($ch);
$result = (curl_exec($ch));


if (!file_exists(__DIR__.'/../../logSit/')) {
    mkdir(__DIR__.'/../../logSit/', 0755, true);
}

$log = 'TIME:' .date('Y-m-d H:i:s') . ' -- '.$result. PHP_EOL;
fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


file_put_contents(__DIR__.'/../../logSit/enabled', $result);
