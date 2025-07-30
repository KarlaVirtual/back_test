<?php

if($_SERVER['SERVER_ADDR'] !='192.168.69.19') {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://admin3.local/system-sha/check-backend/index.php',
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
$timee = time();
//exec("cd /home/api/ && ./src/dto/Usuario.php  | xargs -0 -n1 -i sha256sum {} > /home/log-sha/backend/".$timee.".sha256");
exec("cd /home/home2/backend/api/ && find ./src/dto -type f  -name 'Usuario.php' -print0  | sort | xargs -0 -n1 -i sha256sum {} >> /home/log-sha/backend/" . $timee . ".sha256");
exec("cd /home/home2/backend/api/ && find ./src/sql -type f  ! -name 'ConnectionProperty.php' ! -name 'ConnectionFactory.php' -print0  | sort | xargs -0 -n1 -i sha256sum {} >> /home/log-sha/backend/" . $timee . ".sha256");

$path = "/home/log-sha/backend/";
$files = scandir($path);

$lastFile = scandir("/home/log-sha/backendOriginal/", SCANDIR_SORT_DESCENDING);
$lastFile = $lastFile[0];

$cont = 0;


foreach ($files as &$value) {

    try {

        if ($value != '.' && $value != '..' && explode('_', explode('.sha256', $value)[0])[1] == '') {

            if (md5_file("/home/log-sha/backend/" . $value) == md5_file("/home/log-sha/backendOriginal/" . $lastFile)) {
                rename("/home/log-sha/backend/" . $value, "/home/log-sha/backend/" . explode('_', explode('.sha256', $value)[0])[0] . '_OK_' . $lastFile);
            } else {
                rename("/home/log-sha/backend/" . $value, "/home/log-sha/backend/" . explode('_', explode('.sha256', $value)[0])[0] . '_ERROR_' . $lastFile);
                if ($value != '.' && $value != '..' && explode('_', explode('.sha256', $value)[0])[1] == '') {
                    if (md5_file("/home/log-sha/backend/" . $value) == md5_file("/home/log-sha/backendOriginal/" . $lastFile)) {
                        rename("/home/log-sha/backend/" . $value, "/home/log-sha/backend/" . explode('_', explode('.sha256', $value)[0])[0] . '_OK_' . $lastFile);
                    } else {
                        rename("/home/log-sha/backend/" . $value, "/home/log-sha/backend/" . explode('_', explode('.sha256', $value)[0])[0] . '_ERROR_' . $lastFile);
                    }
                }
            }
        }

    }catch
            (Exception $e){
            }
    if ($cont == 1000) {
        break;
    }
    $cont++;
}

