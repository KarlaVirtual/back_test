<?php
/**
 * Index de la api 'poker'
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 20.09.17
 *
 */
ini_set('display_errors', 'OFF');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST,    OPTIONS,PUT');


require(__DIR__ . '../../../../vendor/autoload.php');

use Backend\cms\CMSProveedor;
use Backend\cms\CMSCategoria;


$gameid = 119;
$mode = $_GET["mode"];
$provider = "JOINPOKER";
$lan = $_GET["lan"];
$partnerid = $_GET["partnerid"];
$user_token = $_GET["token"];
$isMobile = $_GET["isMobile"];




$Game = new \Backend\integrations\casino\Game($gameid, $mode, $provider, $lan, $partnerid, $user_token);


$URL = $Game->getURL();
$proveedor = $URL->proveedor;



if ($proveedor != null) {

    if ($proveedor === "INB") {


    }


} else {
    if($provider=="EZZG"){
        if($isMobile == "true"){
            echo '<script>window.top.top.location.href = "'.$URL.'";</script>';


        }
    }

    if($provider=="GDR") {

        if($isMobile =="true"){
            echo '<div id="golden-race-mobile-app"></div>
<script src="https://test-virtual.golden-race.net/mobile-v2/golden-race-mobile-loader.js" id="golden-race-mobile-loader"></script>
<script>
    document.addEventListener(\'DOMContentLoaded\', function() {
        var grLoader = grMobileLoader({
            onlineHash:      "'. $URL->loginHash .'"// Credentials for external API login.
        });
     });
</script>';

        }else{


            echo '
         <div id="golden-race-online-app"></div>
          <div id="golden-race-app"></div>
 
 
  <script src="https://test-virtual.golden-race.net/web-v2/golden-race-online-loader.js" id="golden-race-online-loader"></script>
<!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
 --><script>
document.addEventListener(\'DOMContentLoaded\', function () {
    var loader = grOnlineLoader({
        onlineHash: "'. $URL->loginHash .'"
    });
});


</script>
';
        }
    }else{
        print('<iframe frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 95%;"></iframe>');

    }


}





