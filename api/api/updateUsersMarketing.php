<?php
/**
* Update users marketing
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* @date 23.05.17
* 
*/
use Backend\dto\Usuario;
use Backend\imports\Mautic\Auth\ApiAuth;

ini_set('memory_limit', '-1');


require_once __DIR__ . '../../vendor/autoload.php';
date_default_timezone_set('America/Bogota');
print_r("ENTRO");

$SkeepRows = 0;
$OrderedItem = 1;
$MaxRows = 100;

$rules = [];

array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));
array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$Usuario = new Usuario();

$usuarios = $Usuario->getUsuariosCustom("  DATE_FORMAT(usuario.fecha_crea,'%Y-%m-%d') fecha_crea,usuario.login,usuario.usuario_id,registro.nombre1,registro.nombre2,registro.celular,registro.apellido1,registro.apellido2,c.*,usuario.pais_id ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);


$usuarios = json_decode($usuarios);



$final = [];

$cont =0;

$array = [];

$array["grant_type"] = "authorization_code";
$array["response_type"] = "code";

$array["client_id"] = '1_29if6xje31z4o4c4og404ww4c80o4000o0sgc4sgs4ogw0kgok';
$array["client_secret"] = 'hxurj3octs840k04koo0g04sw4o4ow0wkscg08skko8g88g48';
$array["redirect_uri"] = 'https://devadmin.doradobet.com';
$array["code"] = 'UNIQUE_CODE_STRING';

array_push($data, $array);


// $initAuth->newAuth() will accept an array of OAuth settings
$settings = array(
    'baseUrl'      => 'https://devadmin.doradobet.com/mautic/api',
    'version'      => 'OAuth2',
    'clientKey'    => $array["client_id"] ,
    'clientSecret' => $array["client_secret"],
    'callback'     => 'https://devadmin.doradobet.com'
);

// Initiate the auth object
$initAuth = new ApiAuth();
$auth     = $initAuth->newAuth($settings);

// Initiate process for obtaining an access token; this will redirect the user to the authorize endpoint and/or set the tokens when the user is redirected back after granting authorization

if ($auth->validateAccessToken()) {
    if ($auth->accessTokenUpdated()) {
        $accessTokenData = $auth->getAccessTokenData();

        print_r($accessTokenData);
        //store access token data however you want
    }
}

print_r($result);
exit;

foreach ($usuarios->data as $key => $value) {

    $array = [];

    $array["email"] = $value->{"usuario.login"};
    $array["name"] = $value->{"registro.nombre1"} . " ". $value->{"registro.nombre2"};
    $array["lastname"] = $value->{"registro.apellido1"} . " ". $value->{"registro.apellido2"};
    $array["mobile"] = $value->{"registro.celular"};
    $array["id"] = $value->{"usuario.usuario_id"};
    $array["profile_image_url"] = "https://images.doradobet.com/site/doradobet/logo-d-white.png";
    $array["dateBirth"] = $value->{"c.fecha_nacim"};
    $array["dateCreated"] = $value->{".fecha_crea"};
    $array["country"] = $value->{"usuario.pais_id"};

    array_push($final, $array);

    $cont++;

    if($cont==100 && false){

        print_r(json_encode($final));



        $payload = json_encode($final);

        $ch = curl_init("https://devwss.doradobet.com/api/createUserDoradobet");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

// Set HTTP Header for POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload))
        );

        print_r("ACA");
        print_r($payload);
        print_r("https://devwss.doradobet.com/api/createUserDoradobet");
//$rs = curl_exec($ch);
        $result = (curl_exec($ch));

        // Close cURL session handle
        curl_close($ch);


        print_r($result);

        $final = [];
        $cont=0;

    }

    if($cont==100) {
        $data=[];
        $array = [];

        $email = $value->{"usuario.login"};
        $nombre1 = $value->{"registro.nombre1"};
        $nombre2=$value->{"registro.nombre2"};
        $apellido1 = $value->{"registro.apellido1"} ;
        $apellido2=$value->{"registro.apellido2"};
        $celular = $value->{"registro.celular"};
        $usuario_id = $value->{"usuario.usuario_id"};
        $fecha_nacim = $value->{"c.fecha_nacim"};
        $fecha_crea = $value->{".fecha_crea"};

        $array["country"] = $value->{"usuario.pais_id"};

        switch( $array["country"]){
            case "2":
                $array["country"] = 'Nicaragua';
                break;

            case "173":
                $array["country"] = 'Peru';

                break;
        }
        $array["email"] = $email;
        $array["firstname"] = $nombre1 . " " . $nombre2;
        $array["lastname"] = $apellido1 . " " . $apellido2;
        $array["phone"] = $celular;
        $array["doradobet_id"] = $usuario_id;
        $array["ipAddress"] = '';
        $array["date_birth"] = $fecha_nacim;
        $array["doradobet_date_created"] = $fecha_crea;

        $array["client_id"] = '1_29if6xje31z4o4c4og404ww4c80o4000o0sgc4sgs4ogw0kgok';
        $array["client_secret"] = 'hxurj3octs840k04koo0g04sw4o4ow0wkscg08skko8g88g48';

        array_push($data, $array);


        $payload = json_encode($array);

        print_r($payload);
        $ch = curl_init("https://devadmin.doradobet.com/mautic/api/contacts/new");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

// Set HTTP Header for POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload))
        );

        $rs = curl_exec($ch);
        $result = (curl_exec($ch));

        // Close cURL session handle
        curl_close($ch);


        print_r($result);

    }


}


