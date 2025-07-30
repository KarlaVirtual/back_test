<?php

if($_REQUEST['getstatus']){

    $hn =$_REQUEST['getstatus'];

    $response='OK';
    $response=      file_get_contents(__DIR__.'/../../logSit/enabled');
    switch ($hn){
        case 'dallasadmin01':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled1');
            break;
        case 'dallasadmin02':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled2');
            break;
        case 'dallasadmin03':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled3');
            break;
        case 'dallasadmin04':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled4');
            break;
        case 'dallasadmin05':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled5');
            break;
        case 'dallasadmin06':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled6');
            break;
        case 'dallasadmin02-clone':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled7');
            break;
        case '8':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled8');
            break;
        case '1':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled1');
            break;
        case '2':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled2');
            break;
        case '3':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled3');
            break;
        case '4':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled4');
            break;
        case '5':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled5');
            break;
        case '6':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled6');
            break;
        case '7':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled7');
            break;
        case '8':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled8');
            break;
        case '9':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled9');
            break;
        case '10':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled10');
            break;
        case '11':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled11');
            break;
        case '12':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled12');
            break;
        case '13':
            $response=      file_get_contents(__DIR__.'/../../logSit/enabled13');
            break;

    }
    print_r($response);

    exit();
}
if($_REQUEST['setstatus']){
    if($_REQUEST['hn']){
        $statuss='';
        if ($_REQUEST['setstatus'] == '1') {
            $statuss='OK';

        }
        if ($_REQUEST['setstatus'] == '2') {
            $statuss='BLOCKED';
        }

        switch ($_REQUEST['hn']){
            case '1':
                file_put_contents(__DIR__ . '/../../logSit/enabled1', $statuss);
                break;
            case '2':
                file_put_contents(__DIR__ . '/../../logSit/enabled2', $statuss);
                break;
            case '3':
                file_put_contents(__DIR__ . '/../../logSit/enabled3', $statuss);
                break;
            case '4':
                file_put_contents(__DIR__ . '/../../logSit/enabled4', $statuss);
                break;
            case '5':
                file_put_contents(__DIR__ . '/../../logSit/enabled5', $statuss);
                break;
            case '6':
                file_put_contents(__DIR__ . '/../../logSit/enabled6', $statuss);
                break;
            case '7':
                file_put_contents(__DIR__ . '/../../logSit/enabled7', $statuss);
                break;
            case '8':
                file_put_contents(__DIR__ . '/../../logSit/enabled8', $statuss);
                break;
            case '9':
                file_put_contents(__DIR__ . '/../../logSit/enabled9', $statuss);
                break;
            case '10':
                file_put_contents(__DIR__ . '/../../logSit/enabled10', $statuss);
                break;
            case '11':
                file_put_contents(__DIR__ . '/../../logSit/enabled11', $statuss);
                break;
            case '12':
                file_put_contents(__DIR__ . '/../../logSit/enabled12', $statuss);
                break;
            case '13':
                file_put_contents(__DIR__ . '/../../logSit/enabled13', $statuss);
                break;

        }
    }else {


        if ($_REQUEST['setstatus'] == '1') {
            file_put_contents(__DIR__ . '/../../logSit/enabled', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled1', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled2', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled3', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled4', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled5', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled6', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled7', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled8', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled9', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled10', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled11', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled12', 'OK');
            file_put_contents(__DIR__ . '/../../logSit/enabled13', 'OK');
        }
        if ($_REQUEST['setstatus'] == '2') {
            file_put_contents(__DIR__ . '/../../logSit/enabled', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled1', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled2', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled3', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled4', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled5', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled6', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled7', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled8', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled9', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled10', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled11', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled12', 'BLOCKED');
            file_put_contents(__DIR__ . '/../../logSit/enabled13', 'BLOCKED');
        }
    }
    $response=      file_get_contents(__DIR__.'/../../logSit/enabled');

    exit();
}

$hn =$_REQUEST['hn'];
if($hn ==''){
    $hn =$_SERVER['hn'];

}
$response='OK';
$response=      file_get_contents(__DIR__.'/../../logSit/enabled');
switch ($hn){
    case 'dallasadmin01':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled1');
        break;
    case 'dallasadmin02':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled2');
        break;
    case 'dallasadmin03':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled3');
        break;
    case 'dallasadmin04':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled4');
        break;
    case 'dallasadmin05':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled5');
        break;
    case 'dallasadmin06':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled6');
        break;
    case 'dallasadmin02-clone':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled7');
        break;
    case '8':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled8');
        break;
    case '9':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled9');
        break;
    case '10':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled10');
        break;
    case '11':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled11');
        break;
    case '12':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled12');
        break;
    case '13':
        $response=      file_get_contents(__DIR__.'/../../logSit/enabled13');
        break;

}
print_r($response);