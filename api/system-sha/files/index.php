<?php

if($_SERVER['SERVER_ADDR'] !='192.168.69.19') {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://admin3.local/system-sha/files/index.php',
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

    print_r($response);
    exit();
}

$URI = $_SERVER["REQUEST_URI"];

$arraySuper = explode("/", current(explode("?", $URI)));
$arraySuper[count($arraySuper) - 2] = ucfirst($arraySuper[count($arraySuper) - 2]);

$filename = base64_decode($arraySuper[count($arraySuper) - 1]) . ".sha256";
if(file_exists(__DIR__  . "/home/log-sha/backend"."/".$filename)){
    $fh = fopen(__DIR__  . "/home/log-sha/backend"."/".$filename, 'r');

    $pageText = fread($fh, 25000);

    echo nl2br($pageText);


}