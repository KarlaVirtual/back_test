<?php

if($_SERVER['SERVER_ADDR'] !='192.168.69.19') {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://admin3.local/system-sha/check-frontend/index.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    exit();
}
$path = "/home/log-sha/frontend/";
$files = scandir($path);

$lastFile = scandir("/home/log-sha/frontendOriginal/", SCANDIR_SORT_DESCENDING);
$lastFile = $lastFile[0];
print_r($lastFile);

$cont=0;
foreach ($files as &$value) {


    if ($value != '.' && $value != '..' && explode('_',explode('.sha256',$value)[0])[1] == '') {
        if (md5_file("/home/log-sha/frontend/".$value) == md5_file("/home/log-sha/frontendOriginal/" . $lastFile)) {
            rename ("/home/log-sha/frontend/".$value, "/home/log-sha/frontend/".explode('_',explode('.sha256',$value)[0])[0] .'_OK_'.$lastFile);
        }else{
            rename ("/home/log-sha/frontend/".$value, "/home/log-sha/frontend/".explode('_',explode('.sha256',$value)[0])[0] .'_ERROR_'.$lastFile);
        }
    }
    if($cont ==10){
        break;
    }
    $cont++;
}
