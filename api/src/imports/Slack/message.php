<?php
/**
 * Created by PhpStorm.
 * User: danielfelipetamayogarcia
 * Date: 14/10/18
 * Time: 4:38 PM
 */



$config = [
    'token' => 'xoxp-426497232785-428108758519-455856894643-1a0224019b68cb0039a4204b0c350549',
    'team' => 'virtualsoft-team',
    'username' => 'Daniel',
    'icon' => 'ICON', // Auto detects if it's an icon_url or icon_emoji
    'parse' => '', // __construct function in Client.php calls for the parse parameter
];


if($_REQUEST["msg"] != ""){
    $msg = $_REQUEST["msg"];

}else{
    $msg = $argv[1];
}
if($argv[4] == 'base64' ) {
    $msg = base64_decode($msg);

}
if($argv[2] == '#virtualsoft-cron-error' ){
    $variable=$argv[3];

    $msg = $msg.' T '.time();
    $msg = $msg.' S '.$variable;


    if($argv[5] != '' && $argv[5] != null){
        $msg = $msg.' '.base64_decode($argv[5]);

    }
    if($argv[6] != '' && $argv[6] != null){
        $msg = $msg.' '.base64_decode($argv[6]);

    }
    if($argv[7] != '' && $argv[7] != null){
        $msg = $msg.' '.base64_decode($argv[7]);

    }
}
if($argv[2] == '#slow-api' ){
    $variable=$argv[3];

    $msg = $msg.' T '.time();
    $msg = $msg.' S '.$variable;


    if($argv[5] != '' && $argv[5] != null){
        $msg = $msg.' '.base64_decode($argv[5]);

    }
    if($argv[6] != '' && $argv[6] != null){
        $msg = $msg.' '.base64_decode($argv[6]);

    }
    if($argv[7] != '' && $argv[7] != null){
        $msg = $msg.' '.base64_decode($argv[7]);

    }
}

if($argv[2] == '#alertas-integraciones' ){
    $ip_servidor = gethostbyname(gethostname());

    $variable=$argv[3];

    $msg = $msg.' T '.time();
    $msg = $msg.' S '.$variable;
    $msg = $msg.' SERVER '.$ip_servidor;


    if($argv[5] != '' && $argv[5] != null){
        $msg = $msg.' '.base64_decode($argv[5]);

    }
    if($argv[6] != '' && $argv[6] != null){
        $msg = $msg.' '.base64_decode($argv[6]);

    }
    if($argv[7] != '' && $argv[7] != null){
        $msg = $msg.' '.base64_decode($argv[7]);

    }
}
if($argv[2] == '#virtualsoft-cron' ){
    $variable=$argv[3];

    $msg = $msg.' S'.$variable;

}
$icon_emoji ='';

if($argv[2] == '#virtualsoft-cron-error-urg' ){
    $variable=$argv[3];

    $msg = $msg.' S'.$variable;

    $variable=json_encode($_REQUEST);
    $msg = $msg.' S'.$variable;

    if($argv[5] != '' && $argv[5] != null){
        $msg = $msg.' '.base64_decode($argv[5]);

    }
    if($argv[6] != '' && $argv[6] != null){
        $msg = $msg.' '.base64_decode($argv[6]);

    }
    if($argv[7] != '' && $argv[7] != null){
        $msg = $msg.' '.base64_decode($argv[7]);

    }

    $icon_emoji =':warning:';

}
if($_ENV['ENV_TYPE'] =='dev'){
    $argv[2]=$argv[2].'-dev';
}
if($_ENV['ENV_TYPE'] =='qa'){
    $argv[2]=$argv[2].'-qa';
}
$ch = curl_init("https://slack.com/api/chat.postMessage");
$data = http_build_query([
    "token" => "xoxp-426497232785-428108758519-455856894643-1a0224019b68cb0039a4204b0c350549",
    "channel" => $argv[2], //"#mychannel",
    "text" => $msg, //"Hello, Foo-Bar channel message.",
    "username" => "Daniel",
    "icon_emoji" => $icon_emoji,
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close($ch);
print_r($argv) ;

print_r($result) ;
/*
include 'vendor/autoload.php';

use ThreadMeUp\Slack\Client;

$config = [
    'token' => 'xoxp-426497232785-428108758519-455856894643-1a0224019b68cb0039a4204b0c350549',
    'team' => 'virtualsoft-team',
    'username' => 'Daniel',
    'icon' => 'ICON', // Auto detects if it's an icon_url or icon_emoji
    'parse' => '', // __construct function in Client.php calls for the parse parameter
];


if($_REQUEST["msg"] != ""){
    $msg = $_REQUEST["msg"];

}else{
    $msg = $argv[1];
}
if($argv[2] == '#virtualsoft-cron-error' ){
    $variable=$argv[3];

    $msg = $msg.' T '.time();
    $msg = $msg.' S '.$variable;
}
if($argv[2] == '#virtualsoft-cron' ){
    $variable=$argv[3];

    $msg = $msg.' S'.$variable;
}
print_r($msg);

$slack = new Client($config);

$chat = $slack->chat($argv[2]);
if($msg != ""){
    $return=$chat->send($msg);
}*/

