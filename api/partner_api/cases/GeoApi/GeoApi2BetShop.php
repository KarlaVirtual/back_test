<?php
use Backend\dto\Usuario;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$params = file_get_contents('php://input');
$params = json_decode($params);

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];
/*$url = "http://freegeoip.net/json/$ip";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
$data = curl_exec($ch);
curl_close($ch);

if ($data) {
    $location = json_decode($data);

    $latitude = $location->latitude;
    $longitude = $location->longitude;

    $sun_info = date_sun_info(time(), $lat, $lon);
}*/
/*
$new_arr[] = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));

$latitude = $new_arr[0]['geoplugin_latitude'];
$longitude = $new_arr[0]['geoplugin_longitude'];
$countryCode = $new_arr[0]['geoplugin_countryCode'];
$Region = $new_arr[0]['geoplugin_region'];
$City = $new_arr[0]['geoplugin_city'];
$ismobile = false;*/

function getRequestHeaders()
{
    $headers = array();
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}

$headers = getRequestHeaders();


$new_arr = new stdClass();

$latitude = $new_arr->latitude;
$longitude = $new_arr->longitude;
$countryCode = strtolower($headers["Cf-Ipcountry"]);
$Region = $new_arr->region_code;
$City = $new_arr->city;
$ismobile = false;

$useragent = $_SERVER['HTTP_USER_AGENT'];

if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {

    $ismobile = true;

}

if ($countryCode == 'co') {
    $countryCode = 'pe';
}

if ($countryCode == 'us') {
    $countryCode = 'pe';
}
$isBetShop = 0;
$casinoEnabled=1;
if ($ip != '' ) {

    $partner ="";

    if(strtolower($params->partner) != "" && strtolower($params->partner) != null && (strtolower($params->partner) != "2"  && (strtolower($params->partner) == "0" || strtolower($params->partner) == "8" ) ) ){
        $partner=strtolower($params->partner);

        $Usuario = new Usuario();

        $UsuarioCheck = $Usuario->checkIPUsuario($ip, 'PUNTOVENTA',$partner);

        if ($UsuarioCheck != null && $UsuarioCheck != '') {
            $isBetShop = 1;
        }
    }
    if( strtolower($params->partner) == "2" && false ) {
        $casinoEnabled=0;
    }

}

if($isBetShop == 1){
    $casinoEnabled=0;
}



print_r('{"statusCode":"OK","statusMessage":"","ipAddress":"' . $ip . '","countryCode":"' . $countryCode . '","countryName":"' . $countryCode . '","departmentName":"' . $Region . '","cityName":"' . $City . '","zipCode":"","latitude":"' . $latitude . '","longitude":"' . $longitude . '","mobile":"' . $ismobile . '","casinoEnabled":' . $casinoEnabled . '}');
//print_r('{}');

$response["statusCode"] = "OK";

exit();
