<?php

// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}
$realm = 'Restricted area';

//user => password
$users = array('admin' => 'l0wR78Aye1e9');


if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="'.$realm.
        '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

    die('Text to send if user hits Cancel button');
}


// analyze the PHP_AUTH_DIGEST variable
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
    !isset($users[$data['username']]))
    die('Wrong Credentials!');


// generate the valid response
$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if ($data['response'] != $valid_response)
    die('Wrong Credentials!');

header('Content-Type: text/html');


if($_SERVER['SERVER_ADDR'] !='192.168.69.19' ){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://admin3.local/system-sha/index.php',
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
    echo $response;
    exit();
}

?>


<style>
    @charset "UTF-8";
    body {
        font-family: 'Open Sans', sans-serif;
        background: #efefef;
        text-transform: uppercase;
    }

    h1 {
        margin-top: 50px;
        margin-bottom: 15px;
        margin-right: 12.5%;
        text-align: right;
        font-size: 1.5em;
        color: #bbb;
        letter-spacing: 0.1em;
    }

    td, a {
        transition: color 333ms ease-in-out;
    }

    a {
        color: #407DB8;
        text-decoration: none;
        font-weight: 400;
    }

    .table {
        width: 75%;
        margin: 0 12.5% 50px 12.5%;
    }

    tr {
        height: 40px;
        transition: background 333ms ease-in-out;
    }

    tr:nth-child(even) {
        background: #e6f0f6;
    }

    tr:nth-child(odd) {
        background: #fff;
    }

    tr:hover {
        background: #ddd;
    }

    tr:hover:nth-child(even) {
        background: #ddd;
    }

    tr:hover td {
        color: #888;
    }

    tr:hover a {
        color: #666;
    }

    tr td {
        color: #888;
        font-size: 0.78em;
        font-weight: 300;
        letter-spacing: 0.2em;
    }

    tr td:first-child {
        padding-left: 25px;
    }

    tr td:last-child {
        padding-right: 25px;
        text-align: right;
    }

    /* Icons */
    .folder:before, .file:before {
        margin-right: 7px;
        font-size: 0.7em;
    }

    .folder:before {
        content: '▶';
        opacity: 0.6;
    }

    .file:before {
        content: '▶';
        opacity: 0.2;
    }

    /* Top Bar */
    tr:first-child {
        background: #666;
    }

    tr:first-child td {
        color: #eee;
        font-size: 0.75em;
        letter-spacing: 0.3em;
    }

    tr:first-child,
    tr:first-child td {
        transition: none;
    }

    /* Download Bar */
    .download {
        width: 100%;
        background: #437EB6;
        text-align: center;
        padding: 8px 0;
    }

    .download a,.download button {
        color: white;
        font-weight: 700;
    }

    .download a:after ,.download button:after{
        content: "▶";
        margin-left: 7px;
        font-size: 0.8em;
        opacity: 0.7;
    }

    .download a:hover,.download button:hover {
        color: #163f65;
    }
</style>
<body>
<style>* {
        padding: 0;
        margin: 0;
    }</style>

<!--<div class="download">
    <form action="" method="post" id="formHash">
        <input type="text" name="time" value="<?/*= time(); */?>" hidden/>
        <p>
            <a href="#" onclick="document.getElementById('formHash').submit()">Generate Hash NOW !</a>

        </p>
    </form>
</div>-->

<div class="download">
    <form action="" method="post" id="formHash">
        <input type="text" name="time" value="<?/*= time(); */?>" hidden/>
        <p style="color: white">
            BACKEND

        </p>
    </form>
</div>

<h1>HASH Directory</h1>
<table cellspacing="0" class="table">
    <tbody>
    <tr>
        <td class="head">Date</td>
        <td class="head">Type</td>
        <td class="head">Size</td>
        <td class="head">Check</td>
    </tr>

    <?php
    $path =  "/home/log-sha/backend/";
    $files = scandir($path);



    /* $lastFile = scandir(__DIR__ . "/../../log-sha/backendOriginal/", SCANDIR_SORT_DESCENDING);
     $lastFile = $lastFile[0];*/


    foreach ($files as &$value) {

        $value2=base64_encode(str_replace('.sha256','',$value));


        //https://devadmin.doradobet.com/api/log-sha/" . $value . "
        if ($value != '.' && $value != '..' && date('Y-m-d H:i:s',explode('_',explode('.sha256',$value)[0])[0]) != '') {

            echo "<tr>
        <td><a href='/system-sha/files/".$value2."' target='_blank' >" . date('Y-m-d H:i:s',explode('_',explode('.sha256',$value)[0])[0]) . "</a></td>
        <td> Hash </td>
        <td>" . round(filesize($path . $value) / 1000000,2) . " MB</td>
                <td> " ;

            if(explode('_',$value)[1] == 'OK'){
                echo "<div style=\"background: greenyellow;color: black;\">OK</div>";
            }else{
                echo "<div style=\"background: red;color: white;\">ERROR</div>";
            }
            echo   " </td>

    </tr>";
        }else{
        }
    }

    ?>


    </tbody>
</table>




</body>

