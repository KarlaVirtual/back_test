<?php

use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Descarga;
use Backend\dto\Mandante;
use Backend\dto\Registro;
use Backend\dto\Template;
use Backend\dto\PaisMoneda;
use Backend\dto\UsuarioLog;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioLog2;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\LogroReferido;
use Backend\dto\SitioTracking;
use Backend\dto\UsuarioPerfil;
use Backend\dto\CuentaAsociada;
use Backend\dto\UsuarioMensaje;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\DocumentoUsuario;
use Backend\dto\UsuarioMarketing;
use Backend\dto\UsuarioPremiomax;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\dto\UsuarioRestriccion;
use Backend\mysql\RegistroMySqlDAO;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\SitioTrackingMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\CuentaAsociadaMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\UsuarioMarketingMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;

/* Crea un array de respuesta con código, identificador y resultado específico. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => "-1119"
);

/* extrae información del usuario y tipo de registro de un objeto JSON. */
$user_info = $json->params->user_info;
$type_register = $json->params->type_register;

$type_gift = $json->params->type_gift;

//Mandante
$site_id = $user_info->site_id;

/* Convierte `$site_id` a minúsculas y lo asigna si está vacío. */
$site_id = strtolower($site_id);

if ($site_id == "") {
    $site_id = $json->params->site_id;
    $site_id = strtolower($site_id);
}


/* Lanza una excepción si el identificador del sitio está vacío. */
if ($site_id == "") {
    throw new Exception("Inusual Detected0", "100001");
}

//$site_id = '0';


//Pais de residencia
$countryResident_id = $user_info->countryResident_id;
//Idioma

/* obtiene el código de idioma del usuario y lo convierte a mayúsculas. */
$lang_code = $user_info->lang_code;
$idioma = strtoupper($lang_code);


$Mandante = new Mandante($site_id);


/**
 * Convierte las cabeceras HTTP de PHP en un formato de arreglo más accesible.
 *
 * @return array Un arreglo asociativo de las cabeceras HTTP.
 */
function getRequestHeaders2()
{
    $headers = array();
    foreach ($_SERVER as $key => $value) {
        if (mb_substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(mb_substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}

/* verifica la validez del ID de un país y maneja excepciones. */
$headers2 = getRequestHeaders2();
$countryCode = strtolower($headers2["Cf-Ipcountry"]);


if ($countryResident_id->Id != '' && $countryResident_id->Id != '0' && $countryResident_id->Id != null) {

    $Pais = new Pais($countryResident_id->Id);
    $PaisMoneda = new PaisMoneda($countryResident_id->Id);
    $moneda_default = $PaisMoneda->moneda;

    $Pais = new Pais($countryResident_id->Id);
    //$PaisMoneda = new PaisMoneda($countryResident_id->Id);
    $PaisMandante = new PaisMandante('', $site_id, $Pais->paisId);

    if ($PaisMandante->estado != 'A') {
        throw new Exception("Inusual Detected0", "100001");
    }
    $moneda_default = $PaisMandante->moneda;
}


/* lanza una excepción si el mandante es '13' y establece un estado por defecto. */
if ($Mandante->mandante == '13') {
    throw new Exception("Inusual Detected0", "100001");
}

$estadoUsuarioDefault = 'I';


//$countryResident_id=0;

$referentLink = $user_info->referent_link;

/* Asigna valores de $user_info a variables específicas para su posterior uso. */
$usuidReferente = null;
$address = $user_info->address;
$birth_date = $user_info->birth_date;
$country_code = $user_info->country_code;
$currency_name = $user_info->currency_name;
$department_id = $user_info->department_id;

/* verifica y decodifica un email con caracteres Unicode si es necesario. */
$docnumber = $user_info->docnumber;
$personPoliticallyExposed = $user_info->person_politically_exposed;
$doctype_id = $user_info->doctype_id;
$email = $user_info->email;

if (strpos($email, '\ud') !== false) {
    $email = json_decode('"' . $email . '"');

}


/* Decodifica un email si contiene el carácter '\ud' en su cadena. */
$email2 = $user_info->email2;

if (strpos($email2, '\ud') !== false) {
    $email2 = json_decode('"' . $email2 . '"');

}


/* Extrae información de la fecha de expedición y el nombre del usuario. */
$expedition_day = $user_info->expedition_day;
$expedition_month = $user_info->expedition_month;
$expedition_year = $user_info->expedition_year;

$expedition_date = $user_info->expedition_date;


$first_name = $user_info->first_name;

/* Extrae información del objeto $user_info, asignando valores a variables específicas. */
$gender = $user_info->gender;
$landline_number = $user_info->landline_number;
$language = $user_info->language;
$last_name = $user_info->last_name;
$limit_deposit_day = $user_info->limit_deposit_day;
$limit_deposit_month = $user_info->limit_deposit_month;

/* Se extraen datos de un objeto de usuario, como límite de depósito y datos personales. */
$limit_deposit_week = $user_info->limit_deposit_week;
$middle_name = $user_info->middle_name;
$nationality_id = $user_info->nationality_id;
$password = $user_info->password;
$phone = $user_info->phone;
$second_last_name = $user_info->second_last_name;

/* Se asignan valores de un objeto `$user_info` a variables para uso posterior. */
$docnumber2 = $user_info->docnumber2;
$trackerId = $user_info->tracker_id;

$rfc = $user_info->rfc;


$city_id = $user_info->city_id;


/* Se extraen y limitan datos de nacimiento del usuario. */
$countrybirth_id = $user_info->countrybirth_id;
$departmentbirth_id = $user_info->departmentbirth_id;
$citybirth_id = $user_info->citybirth_id;
$cp = $user_info->cp;
$cp = mb_substr($cp, 0, 14);

$expdept_id = $user_info->expdept_id;

/* asigna valores y verifica la longitud del apellido del usuario. */
$expcity_id = $user_info->expcity_id;

$nombre = $first_name . " " . $middle_name . " " . $last_name . " " . $second_last_name;
$clave_activa = GenerarClaveTicket(15);

if (strlen($last_name) > 20) {
    $last_name = mb_substr($last_name, 0, 19);
}


/* valida y ajusta nombres y define un tipo de registro según condiciones. */
if (strlen($second_last_name) > 20) {
    $second_last_name = mb_substr($second_last_name, 0, 19);
}

if ($countryResident_id->Id == 60 && $Mandante->mandante == '0') {
    $type_register = '1';
}

/* Establece $type_register como '1' si se cumplen condiciones específicas de país. */
if ($countryResident_id->Id == 94 && $Mandante->mandante == '0') {
    $type_register = '1';
}
if ($countryResident_id->Id == 46 && $Mandante->mandante == '0') {
    $type_register = '1';
}

/* verifica condiciones y depura caracteres de una dirección en un entorno configurado. */
if ($countryResident_id->Id == 66 && $Mandante->mandante == '0') {
    $type_register = '1';
}

$ConfigurationEnvironment = new ConfigurationEnvironment();


$address = $ConfigurationEnvironment->DepurarCaracteres($address);


/* limpia y depura caracteres de dos direcciones de correo electrónico. */
$email = trim($email);
$email2 = trim($email2);

$email = $ConfigurationEnvironment->DepurarCaracteres($email);
$email2 = $ConfigurationEnvironment->DepurarCaracteres($email2);


$email = $ConfigurationEnvironment->remove_emoji($email);

/* elimina emojis del texto en la variable $email2. */
$email2 = $ConfigurationEnvironment->remove_emoji($email2);


$unwanted_array = array('©' => 'c', '®' => 'r',
    '̊' => '', '̧' => '', '̨' => '', '̄' => '', '̱' => '',
    'Á' => 'a', 'á' => 'a', 'À' => 'a', 'à' => 'a', 'Ă' => 'a', 'ă' => 'a', 'ắ' => 'a', 'Ắ' => 'A', 'Ằ' => 'A',
    'ằ' => 'a', 'ẵ' => 'a', 'Ẵ' => 'A', 'ẳ' => 'a', 'Ẳ' => 'A', 'Â' => 'a', 'â' => 'a', 'ấ' => 'a', 'Ấ' => 'A',
    'ầ' => 'a', 'Ầ' => 'a', 'ẩ' => 'a', 'Ẩ' => 'A', 'Ǎ' => 'a', 'ǎ' => 'a', 'Å' => 'a', 'å' => 'a', 'Ǻ' => 'a',
    'ǻ' => 'a', 'Ä' => 'a', 'ä' => 'a', 'ã' => 'a', 'Ã' => 'A', 'Ą' => 'a', 'ą' => 'a', 'Ā' => 'a', 'ā' => 'a',
    'ả' => 'a', 'Ả' => 'a', 'Ạ' => 'A', 'ạ' => 'a', 'ặ' => 'a', 'Ặ' => 'A', 'ậ' => 'a', 'Ậ' => 'A', 'Æ' => 'ae',
    'æ' => 'ae', 'Ǽ' => 'ae', 'ǽ' => 'ae', 'ẫ' => 'a', 'Ẫ' => 'A',
    'Ć' => 'c', 'ć' => 'c', 'Ĉ' => 'c', 'ĉ' => 'c', 'Č' => 'c', 'č' => 'c', 'Ċ' => 'c', 'ċ' => 'c', 'Ç' => 'c', 'ç' => 'c',
    'Ď' => 'd', 'ď' => 'd', 'Ḑ' => 'D', 'ḑ' => 'd', 'Đ' => 'd', 'đ' => 'd', 'Ḍ' => 'D', 'ḍ' => 'd', 'Ḏ' => 'D', 'ḏ' => 'd', 'ð' => 'd', 'Ð' => 'D',
    'É' => 'e', 'é' => 'e', 'È' => 'e', 'è' => 'e', 'Ĕ' => 'e', 'ĕ' => 'e', 'ê' => 'e', 'ế' => 'e', 'Ế' => 'E', 'ề' => 'e',
    'Ề' => 'E', 'Ě' => 'e', 'ě' => 'e', 'Ë' => 'e', 'ë' => 'e', 'Ė' => 'e', 'ė' => 'e', 'Ę' => 'e', 'ę' => 'e', 'Ē' => 'e',
    'ē' => 'e', 'ệ' => 'e', 'Ệ' => 'E', 'Ə' => 'e', 'ə' => 'e', 'ẽ' => 'e', 'Ẽ' => 'E', 'ễ' => 'e',
    'Ễ' => 'E', 'ể' => 'e', 'Ể' => 'E', 'ẻ' => 'e', 'Ẻ' => 'E', 'ẹ' => 'e', 'Ẹ' => 'E',
    'ƒ' => 'f',
    'Ğ' => 'g', 'ğ' => 'g', 'Ĝ' => 'g', 'ĝ' => 'g', 'Ǧ' => 'G', 'ǧ' => 'g', 'Ġ' => 'g', 'ġ' => 'g', 'Ģ' => 'g', 'ģ' => 'g',
    'H̲' => 'H', 'h̲' => 'h', 'Ĥ' => 'h', 'ĥ' => 'h', 'Ȟ' => 'H', 'ȟ' => 'h', 'Ḩ' => 'H', 'ḩ' => 'h', 'Ħ' => 'h', 'ħ' => 'h', 'Ḥ' => 'H', 'ḥ' => 'h',
    'Ỉ' => 'I', 'Í' => 'i', 'í' => 'i', 'Ì' => 'i', 'ì' => 'i', 'Ĭ' => 'i', 'ĭ' => 'i', 'Î' => 'i', 'î' => 'i', 'Ǐ' => 'i', 'ǐ' => 'i',
    'Ï' => 'i', 'ï' => 'i', 'Ḯ' => 'I', 'ḯ' => 'i', 'Ĩ' => 'i', 'ĩ' => 'i', 'İ' => 'i', 'Į' => 'i', 'į' => 'i', 'Ī' => 'i', 'ī' => 'i',
    'ỉ' => 'I', 'Ị' => 'I', 'ị' => 'i', 'Ĳ' => 'ij', 'ĳ' => 'ij', 'ı' => 'i',
    'Ĵ' => 'j', 'ĵ' => 'j',
    'Ķ' => 'k', 'ķ' => 'k', 'Ḵ' => 'K', 'ḵ' => 'k',
    'Ĺ' => 'l', 'ĺ' => 'l', 'Ľ' => 'l', 'ľ' => 'l', 'Ļ' => 'l', 'ļ' => 'l', 'Ł' => 'l', 'ł' => 'l', 'Ŀ' => 'l', 'ŀ' => 'l',
    'Ń' => 'n', 'ń' => 'n', 'Ň' => 'n', 'ň' => 'n', 'Ñ' => 'N', 'ñ' => 'n', 'Ņ' => 'n', 'ņ' => 'n', 'Ṇ' => 'N', 'ṇ' => 'n', 'Ŋ' => 'n', 'ŋ' => 'n',
    'Ó' => 'o', 'ó' => 'o', 'Ò' => 'o', 'ò' => 'o', 'Ŏ' => 'o', 'ŏ' => 'o', 'Ô' => 'o', 'ô' => 'o', 'ố' => 'o', 'Ố' => 'O', 'ồ' => 'o',
    'Ồ' => 'O', 'ổ' => 'o', 'Ổ' => 'O', 'Ǒ' => 'o', 'ǒ' => 'o', 'Ö' => 'o', 'ö' => 'o', 'Ő' => 'o', 'ő' => 'o', 'Õ' => 'o', 'õ' => 'o',
    'Ø' => 'o', 'ø' => 'o', 'Ǿ' => 'o', 'ǿ' => 'o', 'Ǫ' => 'O', 'ǫ' => 'o', 'Ǭ' => 'O', 'ǭ' => 'o', 'Ō' => 'o', 'ō' => 'o', 'ỏ' => 'o',
    'Ỏ' => 'O', 'Ơ' => 'o', 'ơ' => 'o', 'ớ' => 'o', 'Ớ' => 'O', 'ờ' => 'o', 'Ờ' => 'O', 'ở' => 'o', 'Ở' => 'O', 'ợ' => 'o', 'Ợ' => 'O',
    'ọ' => 'o', 'Ọ' => 'O', 'ọ' => 'o', 'Ọ' => 'O', 'ộ' => 'o', 'Ộ' => 'O', 'ỗ' => 'o', 'Ỗ' => 'O', 'ỡ' => 'o', 'Ỡ' => 'O',
    'Œ' => 'oe', 'œ' => 'oe',
    'ĸ' => 'k',
    'Ŕ' => 'r', 'ŕ' => 'r', 'Ř' => 'r', 'ř' => 'r', 'ṙ' => 'r', 'Ŗ' => 'r', 'ŗ' => 'r', 'Ṛ' => 'R', 'ṛ' => 'r', 'Ṟ' => 'R', 'ṟ' => 'r',
    'S̲' => 'S', 's̲' => 's', 'Ś' => 's', 'ś' => 's', 'Ŝ' => 's', 'ŝ' => 's', 'Š' => 's', 'š' => 's', 'Ş' => 's', 'ş' => 's',
    'Ṣ' => 'S', 'ṣ' => 's', 'Ș' => 'S', 'ș' => 's',
    'ſ' => 'z', 'ß' => 'ss', 'Ť' => 't', 'ť' => 't', 'Ţ' => 't', 'ţ' => 't', 'Ṭ' => 'T', 'ṭ' => 't', 'Ț' => 'T',
    'ț' => 't', 'Ṯ' => 'T', 'ṯ' => 't', '™' => 'tm', 'Ŧ' => 't', 'ŧ' => 't',
    'Ú' => 'u', 'ú' => 'u', 'Ù' => 'u', 'ù' => 'u', 'Ŭ' => 'u', 'ŭ' => 'u', 'Û' => 'u', 'û' => 'u', 'Ǔ' => 'u', 'ǔ' => 'u', 'Ů' => 'u', 'ů' => 'u',
    'Ü' => 'u', 'ü' => 'u', 'Ǘ' => 'u', 'ǘ' => 'u', 'Ǜ' => 'u', 'ǜ' => 'u', 'Ǚ' => 'u', 'ǚ' => 'u', 'Ǖ' => 'u', 'ǖ' => 'u', 'Ű' => 'u', 'ű' => 'u',
    'Ũ' => 'u', 'ũ' => 'u', 'Ų' => 'u', 'ų' => 'u', 'Ū' => 'u', 'ū' => 'u', 'Ư' => 'u', 'ư' => 'u', 'ứ' => 'u', 'Ứ' => 'U', 'ừ' => 'u', 'Ừ' => 'U',
    'ử' => 'u', 'Ử' => 'U', 'ự' => 'u', 'Ự' => 'U', 'ụ' => 'u', 'Ụ' => 'U', 'ủ' => 'u', 'Ủ' => 'U', 'ữ' => 'u', 'Ữ' => 'U',
    'Ŵ' => 'w', 'ŵ' => 'w',
    'Ý' => 'y', 'ý' => 'y', 'ỳ' => 'y', 'Ỳ' => 'Y', 'Ŷ' => 'y', 'ŷ' => 'y', 'ÿ' => 'y', 'Ÿ' => 'y', 'ỹ' => 'y', 'Ỹ' => 'Y', 'ỷ' => 'y', 'Ỷ' => 'Y',
    'Z̲' => 'Z', 'z̲' => 'z', 'Ź' => 'z', 'ź' => 'z', 'Ž' => 'z', 'ž' => 'z', 'Ż' => 'z', 'ż' => 'z', 'Ẕ' => 'Z', 'ẕ' => 'z',
    'þ' => 'p', 'ŉ' => 'n', 'А' => 'a', 'а' => 'a', 'Б' => 'b', 'б' => 'b', 'В' => 'v', 'в' => 'v', 'Г' => 'g', 'г' => 'g', 'Ґ' => 'g', 'ґ' => 'g',
    'Д' => 'd', 'д' => 'd', 'Е' => 'e', 'е' => 'e', 'Ё' => 'jo', 'ё' => 'jo', 'Є' => 'e', 'є' => 'e', 'Ж' => 'zh', 'ж' => 'zh', 'З' => 'z', 'з' => 'z',
    'И' => 'i', 'и' => 'i', 'І' => 'i', 'і' => 'i', 'Ї' => 'i', 'ї' => 'i', 'Й' => 'j', 'й' => 'j', 'К' => 'k', 'к' => 'k', 'Л' => 'l', 'л' => 'l',
    'М' => 'm', 'м' => 'm', 'Н' => 'n', 'н' => 'n', 'О' => 'o', 'о' => 'o', 'П' => 'p', 'п' => 'p', 'Р' => 'r', 'р' => 'r', 'С' => 's', 'с' => 's',
    'Т' => 't', 'т' => 't', 'У' => 'u', 'у' => 'u', 'Ф' => 'f', 'ф' => 'f', 'Х' => 'h', 'х' => 'h', 'Ц' => 'c', 'ц' => 'c', 'Ч' => 'ch', 'ч' => 'ch',
    'Ш' => 'sh', 'ш' => 'sh', 'Щ' => 'sch', 'щ' => 'sch', 'Ъ' => '-',
    'ъ' => '-', 'Ы' => 'y', 'ы' => 'y', 'Ь' => '-', 'ь' => '-',
    'Э' => 'je', 'э' => 'je', 'Ю' => 'ju', 'ю' => 'ju', 'Я' => 'ja', 'я' => 'ja', 'א' => 'a', 'ב' => 'b', 'ג' => 'g', 'ד' => 'd', 'ה' => 'h', 'ו' => 'v',
    'ז' => 'z', 'ח' => 'h', 'ט' => 't', 'י' => 'i', 'ך' => 'k', 'כ' => 'k', 'ל' => 'l', 'ם' => 'm', 'מ' => 'm', 'ן' => 'n', 'נ' => 'n', 'ס' => 's', 'ע' => 'e',
    'ף' => 'p', 'פ' => 'p', 'ץ' => 'C', 'צ' => 'c', 'ק' => 'q', 'ר' => 'r', 'ש' => 'w', 'ת' => 't'
);

/* reemplaza caracteres no deseados y filtra caracteres no ASCII en correos electrónicos. */
$email = strtr($email, $unwanted_array);
$email2 = strtr($email2, $unwanted_array);


$email = preg_replace('/[^(\x20-\x7F)]*/', '', $email);
$email2 = preg_replace('/[^(\x20-\x7F)]*/', '', $email2);


/* depura una contraseña y define una función para validar usuarios restringidos. */
$password = $ConfigurationEnvironment->DepurarCaracteres($password);


/**
 * Verifica si un usuario está restringido.
 *
 * @param string $doc Documento del usuario.
 * @param int $doc_type Tipo de documento (1 para 'C', 3 para 'P').
 * @param string $email Correo electrónico del usuario.
 * @param string $phone Teléfono del usuario.
 * @param string $name Nombre del usuario.
 * @param int $partner Identificador del socio.
 * @param int $country Identificador del país.
 * @return bool Retorna true si el usuario está restringido, de lo contrario false.
 */
function isRestrictedUser($doc, $doc_type, $email, $phone, $name, $partner, $country)
{
    $doc_type = $doc_type == 1 ? 'C' : ($doc_type == 3 ? 'P' : '');

    /* Consulta SQL para obtener restricciones de usuario basadas en varios criterios. */
    $select = 'SELECT * FROM usuario_restriccion';
    $doc_where = "usuario_restriccion.documento = '{$doc}' AND usuario_restriccion.tipo_doc = '{$doc_type}'";
    $email_where = "usuario_restriccion.email = '{$email}'";
    $phone_where = "usuario_restriccion.telefono = '{$phone}'";
    $mandatory_where = "usuario_restriccion.mandante IN(-1, {$partner}) AND pais_id IN(0, {$country}) AND estado = 'A'";

    $query = "{$select} WHERE (({$doc_where}) OR ({$email_where}) OR ({$phone_where})) AND {$mandatory_where} LIMIT 0, 1";

    /* Se crea un objeto y se ejecuta una consulta, luego se decodifica el resultado. */
    $UsuarioRestriccion = new UsuarioRestriccion();
    $data = $UsuarioRestriccion->getQueryCustom($query);
    $data = json_decode($data, true);

    if (count($data['result']) > 0) {
        // Almacenando coincidencia con una restricción en el registro para la petición en proceso

        /* Crea un objeto con información del usuario y la configuración del entorno. */
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioRestriccionInfo = (object)[
            'doctype' => $doc_type,
            'document' => $doc,
            'email' => $email,
            'phone' => $phone,
            'name' => $name
        ];

        // Sanitizando parámetros

        /* Depura información de usuario y guarda intentos de registro en Mincetur. */
        foreach ($UsuarioRestriccionInfo as $key => $value) {
            $UsuarioRestriccionInfo->$key = $ConfigurationEnvironment->depurarCaracteres($value);
        }

        // Almacenando intento de registro
        foreach ($data['result'] as $restrictionCoincidence) {
            $UsuarioRestriccion->saveRegistrationAttemptMincetur($UsuarioRestriccionInfo, $partner, $country);
        }
    }

    /* Verifica si hay resultados y asigna un tipo de registro basado en condiciones. */
    return count($data['result']) > 0 ? true : false;
}
$type_registerGlobal = '';
$Clasificador = new Clasificador("", "TYPEREGISTER");


try {
    $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $Clasificador->getClasificadorId(), $Pais->paisId, 'A');

    $type_registerGlobal = (intval($MandanteDetalle->getValor()) == 1) ? "C" : "L";


} catch (Exception $e) {
    /* Manejo de excepciones en PHP, verifica el código del error específico. */


    if ($e->getCode() == 34) {
    } else {
    }
}


/* inicializa objetos si las variables son nulas. */
if ($expcity_id == null) {
    $expcity_id = new stdClass();
}
if ($countrybirth_id == null) {
    $countrybirth_id = new stdClass();
}


/* Se convierte la nacionalidad a objeto si es nula o una cadena. */
if ($nationality_id == null) {
    $nationality_id = new stdClass();
}
if (is_string($nationality_id)) {
    $nationality_idTemp = $nationality_id;
    $nationality_id = new stdClass();

    $nationality_id->Id = $nationality_idTemp;

}

/* asigna objetos vacíos a variables nulas de ciudad y país. */
if ($city_id == null) {
    $city_id = new stdClass();
}
if ($countryResident_id == null) {
    $countryResident_id = new stdClass();
}

/* Crea un objeto vacío si la variable $citybirth_id es nula. */
if ($citybirth_id == null) {
    $citybirth_id = new stdClass();
}


/* Asignación de país y moneda según el ID del residente y validación del estado. */
if ($countryResident_id->Id == '') {
    $PaisMandante = new PaisMandante('', $site_id, '');
    $countryResident_id->Id = $PaisMandante->paisId;

    $Pais = new Pais($countryResident_id->Id);
    $PaisMoneda = new PaisMoneda($countryResident_id->Id);
    $moneda_default = $PaisMoneda->moneda;
    $pais_residencia = $Pais->paisId;


    $Pais = new Pais($countryResident_id->Id);
    //$PaisMoneda = new PaisMoneda($countryResident_id->Id);
    $PaisMandante = new PaisMandante('', $site_id, $Pais->paisId);

    if ($PaisMandante->estado != 'A') {
        throw new Exception("Inusual Detected0", "100001");
    }
    $moneda_default = $PaisMandante->moneda;

}

if ($type_register == 1) {

    /* asigna valores predeterminados y ajusta registros según un tipo específico. */
    if ($type_registerGlobal == 'C') {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;

        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;

    }


    /* Asignación de valores y verificación de condiciones basadas en país y género. */
    if ($countryResident_id->Id == 173) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        //$nationality_id->Id = 173;
        //$ciudad_id = 9295;
        //$city_id->Id = 9295;
        $expcity_id->Id = 0;

    }

    /* Condicional que asigna valores según el país y el género del residente. */
    if ($countryResident_id->Id == 60) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        //$nationality_id->Id = 173;
        //$ciudad_id = 9295;
        //$city_id->Id = 9295;
        $expcity_id->Id = 0;

    }

    /* establece condiciones para asignar valores según el país y género. */
    if ($countryResident_id->Id == 94) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        //$nationality_id->Id = 173;
        //$ciudad_id = 9295;
        //$city_id->Id = 9295;
        $expcity_id->Id = 0;

    }


    /* Condiciona la asignación de género y otras variables según el identificador del residente. */
    if ($countryResident_id->Id == 46) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        //$nationality_id->Id = 173;
        //$ciudad_id = 9295;
        //$city_id->Id = 9295;
        $expcity_id->Id = 0;

    }

    /* establece valores predeterminados si el país residente tiene ID 2. */
    if ($countryResident_id->Id == 2) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        //$nationality_id->Id = 2;
        //$ciudad_id = 9196;
        //$city_id->Id = 9196;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;

    }

    /* establece valores predeterminados para ciertas variables según condiciones específicas. */
    if ($countryResident_id->Id == 66) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        //$nationality_id->Id = 2;
        //$ciudad_id = 9196;
        //$city_id->Id = 9196;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;

    }

    /* Condicional para asignar valores según el sitio y género en un registro. */
    if ($site_id == 8) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        //$nationality_id->Id = 66;
        //$ciudad_id = 137840;
        //$city_id->Id = 137840;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;

    }

    /* asigna valores predeterminados según el sitio y género del usuario. */
    if ($site_id == 12) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        //$nationality_id->Id = 66;
        //$ciudad_id = 137840;
        //$city_id->Id = 137840;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;

    }

    /* verifica el ID del sitio y establece variables de género y ubicación. */
    if (in_array($site_id, array(3, 4, 5, 6, 7, 13))) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        //$nationality_id->Id = 146;
        //$ciudad_id = 9343;
        //$city_id->Id = 9343;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;

    }

} else {
    /* Asignación de valores y comprobaciones para el registro tipo 'C' en un sistema. */


    if ($type_registerGlobal == 'C') {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;

        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;

    }
}
/*if($site_id=='0' && $countryResident_id->Id == 2) {
    if($gender == ""){
        $gender = 'M';
    }
    $registroCorto = true;
    $nationality_id->Id = 2;
    //$ciudad_id = 9196;
    //$city_id->Id = 9196;
    $expcity_id->Id = 0;
    $citybirth_id->Id = 0;

}*/


/* Asignación de valores y verificación de condiciones para identificación de residencia y nacimiento. */
$pais_residencia = $countryResident_id->Id;
$depto_nacimiento = $departmentbirth_id->Id;
$ciudad_nacimiento = $citybirth_id->Id;
$idioma = 'ES';
$ciudad_id = $city_id->Id;
if ($ciudad_id == "" && $Mandante->mandante == 15) {
    $ciudad_id = '0';
}

/* asigna valores a variables basadas en condiciones específicas para una ciudad. */
if ($city_id == "" && $Mandante->mandante == 15) {
    $city_id = '0';
}
$origen = 0;
$origen_fondos = "";
$rangoingreso_id = "";

/* verifica país de residencia y referencia HTTP para asignar valores. */
$ocupacion = "";

if ($pais_residencia == 173 || $pais_residencia == 2) {
    //  $registroCorto = true;
}


if (strpos($_SERVER['HTTP_REFERER'], "acropolis") !== FALSE) {
    $origen = 2;
}


// Landing Registro


/* asigna valores relacionados con un usuario desde un objeto `$user_info`. */
$registroLanding = 0;
$landing = $user_info->landing;
$code_promo = $user_info->code_promo;
$code_promo = json_decode('"' . $code_promo . '"');

$btag = $user_info->btag;

/* reemplaza espacios y códigos de URL en variables de usuario. */
$codigo = $user_info->code;
$btag = str_replace(" ", "+", $btag);
$btag = str_replace("%20", "+", $btag);


$codigo2 = $user_info->code2;

/* Asigna valores y limpia variables según el valor de $btag. */
$codigo3 = $user_info->code3;

if ($btag == 'KzRhbEkvek9nWXU0Snd1Z2l0NGRsQT09') {
    $btag = '';
    $codigo2 = 'KzRhbEkvek9nWXU0Snd1Z2l0NGRsQT09';
    $codigo3 = '';

}


/* Verifica si 'C' está en $codigo y prepara para modificarlo si es necesario. */
if (strpos($codigo, 'C') !== false) {

    //$codigo = str_replace('C','',$codigo);
    //$codigo3='';

}


/* Verifica si $landing es un número positivo antes de asignarlo a $registroLanding. */
if (is_numeric($landing) && intval($landing) > 0 && $landing != "") {
    $registroLanding = intval($landing);
}

if ($registroLanding == 1) {
    //$gender = 'M';

    /* Asigna 'M' a $gender si está vacío y establece $registroCorto en verdadero. */
    if ($gender == "") {
        $gender = 'M';

    }


    $registroCorto = true;


    if ($site_id == 16) {


        /* Asigna valores por defecto a género y país de residencia si están vacíos. */
        if ($gender == "") {
            $gender = 'M';

        }


        if ($pais_residencia == "") {
            $pais_residencia = $Pais->paisId;
        }


        /* Asigna valores a variables si están vacías en un contexto de nacionalidad y ciudad. */
        if ($nationality_id->Id == "") {
            $nationality_id->Id = $Pais->paisId;
        }

        if ($ciudad_id == "") {
            $ciudad_id = "0";
        }


        /* asigna valores por defecto a identificadores de ciudad y país si están vacíos. */
        if ($city_id->Id == "") {
            $city_id->Id = "0";
        }

        if ($countryResident_id->Id == "") {
            $countryResident_id->Id = "0";
            if ($site_id == 16) {
                $countryResident_id->Id = 170;
            }
        }


        /* inicializa Id en "0" si está vacío para dos variables. */
        if ($expcity_id->Id == "") {
            $expcity_id->Id = "0";
        }


        if ($citybirth_id->Id == "") {
            $citybirth_id->Id = "0";
        }


        /* asigna "0" a Id si está vacío. */
        if ($countrybirth_id->Id == "") {
            $countrybirth_id->Id = "0";
        }
        if ($countrybirth_id->Id == "") {
            $countrybirth_id->Id = "0";
        }

        /* crea objetos para manejar información de usuario y su país. */
        $team = $user_info->team;
        $birth_date = $user_info->birth_date;

        $Pais = new Pais($countryResident_id->Id);
        //$PaisMoneda = new PaisMoneda($countryResident_id->Id);
        $PaisMandante = new PaisMandante('', $site_id, $Pais->paisId);


        /* Verifica el estado de $PaisMandante y asigna su moneda si es válido. */
        if ($PaisMandante->estado != 'A') {
            throw new Exception("Inusual Detected0", "100001");
        }
        $moneda_default = $PaisMandante->moneda;

    } else {


        /* Asignación de identidades de nacionalidad y ubicación en un sistema de gestión de datos. */
        $nationality_id->Id = 173;
        $pais_residencia = 173;
        $ciudad_id = 9295;
        $city_id->Id = 9295;
        $countryResident_id->Id = 173;
        $expcity_id->Id = 0;

        /* inicializa identificadores y obtiene la moneda del país residente. */
        $citybirth_id->Id = 0;
        $countrybirth_id->Id = $countryResident_id->Id;

        $Pais = new Pais($countryResident_id->Id);
        $PaisMoneda = new PaisMoneda($countryResident_id->Id);
        $moneda_default = $PaisMoneda->moneda;


        /* Asignación de un código promocional basado en condiciones de variables vacías. */
        if ($code_promo == "") {

            if ($btag == "") {
                //$code_promo = 'ALIANZA08';
            }
            if ($btag == "a5272da8b4934e078ca958882fdcc4b0%2F6ZWOEM5HFvkBQ%3D%3D" || $btag == "a5272da8b4934e078ca958882fdcc4b0%2F6ZWOEM5HFvkBQ==") {
                $code_promo = 'TJFREEBET';

            }
        }


        if ($site_id == 8) {

            /* Asigna 'M' a $gender si está vacío y establece $nationality_id->Id en 66. */
            if ($gender == "") {
                $gender = 'M';

            }
            $registroCorto = true;
            $nationality_id->Id = 66;

            /* Asignación de identificadores para país de residencia y ciudad en variables. */
            $pais_residencia = 66;
            $ciudad_id = 137840;
            $city_id->Id = 137840;
            $countryResident_id->Id = 66;
            $expcity_id->Id = 0;
            $citybirth_id->Id = 0;

            /* Asigna valores de país y moneda según la información del usuario. */
            $countrybirth_id->Id = $countryResident_id->Id;
            $team = $user_info->team;
            $birth_date = $user_info->birth_date;
            $Pais = new Pais($countryResident_id->Id);
            $PaisMoneda = new PaisMoneda($countryResident_id->Id);
            $moneda_default = $PaisMoneda->moneda;


        }

        /* Establece valores predeterminados para países y ciudades en base al site_id. */
        if (in_array($site_id, array(3, 4, 5, 6, 7))) {
            if ($gender == "") {
                $gender = 'M';

            }
            $registroCorto = true;
            $nationality_id->Id = 146;
            $pais_residencia = 146;
            $ciudad_id = 9343;
            $city_id->Id = 9343;
            $countryResident_id->Id = 146;
            $expcity_id->Id = 0;
            $citybirth_id->Id = 0;
            $countrybirth_id->Id = $countryResident_id->Id;

            $Pais = new Pais($countryResident_id->Id);
            $PaisMoneda = new PaisMoneda($countryResident_id->Id);
            $moneda_default = $PaisMoneda->moneda;

        }

        /* Configura datos de un usuario según su país y género en un sitio específico. */
        if (in_array($site_id, array(12))) {
            if ($gender == "") {
                $gender = 'M';

            }
            $registroCorto = true;
            $nationality_id->Id = 232;
            $pais_residencia = 232;
            $ciudad_id = 136500;
            $city_id->Id = 136500;
            $countryResident_id->Id = 232;
            $expcity_id->Id = 0;
            $citybirth_id->Id = 0;
            $countrybirth_id->Id = $countryResident_id->Id;

            $Pais = new Pais($countryResident_id->Id);
            $PaisMoneda = new PaisMoneda($countryResident_id->Id);
            $moneda_default = $PaisMoneda->moneda;

        }


        /* configura parámetros de registro dependiendo del sitio y género del usuario. */
        if (in_array($site_id, array(14))) {
            if ($gender == "") {
                $gender = 'M';

            }
            $registroCorto = true;
            $nationality_id->Id = 33;
            $pais_residencia = 33;
            $ciudad_id = 27897;
            $city_id->Id = 27897;
            $countryResident_id->Id = 33;
            $expcity_id->Id = 0;
            $citybirth_id->Id = 0;
            $countrybirth_id->Id = $countryResident_id->Id;

            $Pais = new Pais($countryResident_id->Id);
            $PaisMoneda = new PaisMoneda($countryResident_id->Id);
            $moneda_default = $PaisMoneda->moneda;

        }


        /* asigna valores predeterminados según condiciones específicas del usuario. */
        if (in_array($site_id, array(13))) {
            if ($gender == "") {
                $gender = 'M';

            }
            $registroCorto = true;
            $nationality_id->Id = 146;
            $pais_residencia = 146;
            $ciudad_id = 9343;
            $city_id->Id = 9343;
            $countryResident_id->Id = 146;
            $expcity_id->Id = 0;
            $citybirth_id->Id = 0;
            $countrybirth_id->Id = $countryResident_id->Id;

            $Pais = new Pais($countryResident_id->Id);
            $PaisMoneda = new PaisMoneda($countryResident_id->Id);
            $moneda_default = $PaisMoneda->moneda;

        }


        if (in_array($site_id, array(9))) {

            /* Asigna 'M' a $gender si está vacío y establece el Id de nationality_id. */
            if ($gender == "") {
                $gender = 'M';

            }
            $registroCorto = true;
            $nationality_id->Id = 232;

            /* Asignación de identificadores para país y ciudad en variables específicas. */
            $pais_residencia = 232;
            $ciudad_id = 131661;
            $city_id->Id = 131661;
            $countryResident_id->Id = 232;
            $expcity_id->Id = 0;
            $citybirth_id->Id = 0;

            /* Asignación de IDs y creación de objetos; se valida el estado del país mandante. */
            $countrybirth_id->Id = $countryResident_id->Id;

            $Pais = new Pais($countryResident_id->Id);
            $PaisMoneda = new PaisMoneda($countryResident_id->Id);
            $PaisMandante = new PaisMandante('', $site_id, $Pais->paisId);

            if ($PaisMandante->estado != 'A') {
                throw new Exception("Inusual Detected0", "100001");
            }

            /* Asignación de la moneda predeterminada del objeto $PaisMoneda a la variable $moneda_default. */
            $moneda_default = $PaisMoneda->moneda;

        }

    }

}


if ($registroLanding == 2) {
    //$gender = 'M';

    /* asigna valores a variables relacionadas con registros de nacionalidad y ubicación. */
    $registroCorto = true;
    $nationality_id->Id = 2;
    $pais_residencia = 2;
    $ciudad_id = 9196;
    $city_id->Id = 9295;
    $countryResident_id->Id = 173;

    /* Se inicializan identificadores y se crea un objeto "Pais" con el ID del país residente. */
    $expcity_id->Id = 0;
    $citybirth_id->Id = 0;
    $countrybirth_id->Id = $countryResident_id->Id;


    $Pais = new Pais($countryResident_id->Id);

    /* Se crea un objeto 'PaisMoneda' y se obtiene la moneda correspondiente. */
    $PaisMoneda = new PaisMoneda($countryResident_id->Id);
    $moneda_default = $PaisMoneda->moneda;


    if ($site_id == 8) {

        /* Asignación de género por defecto y configuración de nacionalidad en un registro. */
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        $nationality_id->Id = 66;

        /* Variables que almacenan identificadores de país y ciudad en un sistema. */
        $pais_residencia = 66;
        $ciudad_id = 137840;
        $city_id->Id = 137840;
        $countryResident_id->Id = 66;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;

        /* Asigna IDs de país y obtiene datos de usuario para crear un objeto 'Pais'. */
        $countrybirth_id->Id = $countryResident_id->Id;

        $team = $user_info->team;

        $birth_date = $user_info->birth_date;


        $Pais = new Pais($countryResident_id->Id);

        /* Se obtiene la moneda asociada al país del residente actual. */
        $PaisMoneda = new PaisMoneda($countryResident_id->Id);
        $moneda_default = $PaisMoneda->moneda;


    }

    /* Condicional para asignar valores dependiendo del ID de sitio y género. */
    if (in_array($site_id, array(3, 4, 5, 6, 7))) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        $nationality_id->Id = 146;
        $pais_residencia = 146;
        $ciudad_id = 9343;
        $city_id->Id = 9343;
        $countryResident_id->Id = 146;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;
        $countrybirth_id->Id = $countryResident_id->Id;

        $Pais = new Pais($countryResident_id->Id);
        $PaisMoneda = new PaisMoneda($countryResident_id->Id);
        $moneda_default = $PaisMoneda->moneda;

    }


    /* asigna valores específicos basados en condiciones relacionadas con el sitio y género. */
    if (in_array($site_id, array(14))) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        $nationality_id->Id = 33;
        $pais_residencia = 33;
        $ciudad_id = 27897;
        $city_id->Id = 27897;
        $countryResident_id->Id = 33;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;
        $countrybirth_id->Id = $countryResident_id->Id;

        $Pais = new Pais($countryResident_id->Id);
        $PaisMoneda = new PaisMoneda($countryResident_id->Id);
        $moneda_default = $PaisMoneda->moneda;

    }


    if (in_array($site_id, array(9))) {

        /* asigna 'M' a $gender si está vacío y establece un ID de nacionalidad. */
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        $nationality_id->Id = 232;

        /* Código que asigna identificadores a variables relacionadas con país y ciudad de residencia. */
        $pais_residencia = 232;
        $ciudad_id = 131661;
        $city_id->Id = 131661;
        $countryResident_id->Id = 232;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;

        /* asigna un ID y verifica el estado de un país. */
        $countrybirth_id->Id = $countryResident_id->Id;

        $Pais = new Pais($countryResident_id->Id);
        $PaisMoneda = new PaisMoneda($countryResident_id->Id);
        $PaisMandante = new PaisMandante('', $site_id, $Pais->paisId);

        if ($PaisMandante->estado != 'A') {
            throw new Exception("Inusual Detected0", "100001");
        }

        /* Se asigna la moneda de un país a la variable $moneda_default. */
        $moneda_default = $PaisMoneda->moneda;

    }
}

if ($registroLanding == 8) {

    /* define variables relacionadas con género, nacionalidad y residencia. */
    $gender = 'M';
    $registroCorto = true;
    $nationality_id->Id = 173;
    $pais_residencia = 173;
    $ciudad_id = 9295;
    $city_id->Id = 9295;

    /* Asigna valores de identificación a variables relacionadas con residencia y ciudad. */
    $countryResident_id->Id = 173;
    $expcity_id->Id = 0;
    $citybirth_id->Id = 0;
    $countrybirth_id->Id = $countryResident_id->Id;

    $team = $user_info->team;


    /* obtiene la fecha de nacimiento y la moneda del país residente del usuario. */
    $birth_date = $user_info->birth_date;
    $Pais = new Pais($countryResident_id->Id);
    $PaisMoneda = new PaisMoneda($countryResident_id->Id);
    $moneda_default = $PaisMoneda->moneda;


    if ($site_id == 8) {

        /* Asigna 'M' a género si está vacío y establece un ID de nacionalidad en 66. */
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        $nationality_id->Id = 66;

        /* Variables que almacenan identificadores de país y ciudad para procesamiento. */
        $pais_residencia = 66;
        $ciudad_id = 137840;
        $city_id->Id = 137840;
        $countryResident_id->Id = 66;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;

        /* asigna ID de país y obtiene información sobre un usuario y su moneda. */
        $countrybirth_id->Id = $countryResident_id->Id;
        $team = $user_info->team;
        $birth_date = $user_info->birth_date;
        $Pais = new Pais($countryResident_id->Id);
        $PaisMoneda = new PaisMoneda($countryResident_id->Id);
        $moneda_default = $PaisMoneda->moneda;


    }

    /* asigna valores a variables según condiciones específicas de sitio y género. */
    if (in_array($site_id, array(3, 4, 5, 6, 7))) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        $nationality_id->Id = 146;
        $pais_residencia = 146;
        $ciudad_id = 9343;
        $city_id->Id = 9343;
        $countryResident_id->Id = 146;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;
        $countrybirth_id->Id = $countryResident_id->Id;

        $Pais = new Pais($countryResident_id->Id);
        $PaisMoneda = new PaisMoneda($countryResident_id->Id);
        $moneda_default = $PaisMoneda->moneda;

    }


    /* asigna valores según ciertas condiciones para un registro específico. */
    if (in_array($site_id, array(14))) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        $nationality_id->Id = 33;
        $pais_residencia = 33;
        $ciudad_id = 27897;
        $city_id->Id = 27897;
        $countryResident_id->Id = 33;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;
        $countrybirth_id->Id = $countryResident_id->Id;

        $Pais = new Pais($countryResident_id->Id);
        $PaisMoneda = new PaisMoneda($countryResident_id->Id);
        $moneda_default = $PaisMoneda->moneda;

    }
    if ($site_id > 15) {


        /* asigna valores predeterminados si las variables están vacías. */
        if ($gender == "") {
            $gender = 'M';

        }


        if ($pais_residencia == "") {
            $pais_residencia = $Pais->paisId;
        }


        /* Asigna valores predeterminados si las variables están vacías en un código PHP. */
        if ($nationality_id->Id == "") {
            $nationality_id->Id = $Pais->paisId;
        }

        if ($ciudad_id == "") {
            $ciudad_id = "0";
        }


        /* asigna "0" si los IDs de ciudad o país son vacíos. */
        if ($city_id->Id == "") {
            $city_id->Id = "0";
        }

        if ($countryResident_id->Id == "") {
            $countryResident_id->Id = "0";
        }


        /* Asigna "0" a Id si está vacío en ambos objetos. */
        if ($expcity_id->Id == "") {
            $expcity_id->Id = "0";
        }


        if ($citybirth_id->Id == "") {
            $citybirth_id->Id = "0";
        }


        /* Verifica si Id está vacío y lo asigna a "0". */
        if ($countrybirth_id->Id == "") {
            $countrybirth_id->Id = "0";
        }
        if ($countrybirth_id->Id == "") {
            $countrybirth_id->Id = "0";
        }

        /* inicializa variables relacionadas con un usuario y su país correspondiente. */
        $team = $user_info->team;
        $birth_date = $user_info->birth_date;

        $Pais = new Pais($countryResident_id->Id);
        $PaisMoneda = new PaisMoneda($countryResident_id->Id);
        $PaisMandante = new PaisMandante('', $site_id, $Pais->paisId);


        /* verifica el estado del país y lanza una excepción si no está activo. */
        if ($PaisMandante->estado != 'A') {
            throw new Exception("Inusual Detected0", "100001");
        }
        $moneda_default = $PaisMoneda->moneda;

    }
}

if ($registroLanding == 9) {


    /* Asignación de valores predeterminados a variables si están vacías. */
    if ($gender == "") {
        $gender = 'M';

    }


    if ($pais_residencia == "") {
        $pais_residencia = $Pais->paisId;
    }


    /* asigna valores predeterminados si 'Id' o 'ciudad_id' están vacíos. */
    if ($nationality_id->Id == "") {
        $nationality_id->Id = $Pais->paisId;
    }

    if ($ciudad_id == "") {
        $ciudad_id = "0";
    }


    /* asigna "0" a Id si está vacío en city_id o countryResident_id. */
    if ($city_id->Id == "") {
        $city_id->Id = "0";
    }

    if ($countryResident_id->Id == "") {
        $countryResident_id->Id = "0";
    }


    /* Asigna "0" a Id si está vacío en objetos expcity_id y citybirth_id. */
    if ($expcity_id->Id == "") {
        $expcity_id->Id = "0";
    }


    if ($citybirth_id->Id == "") {
        $citybirth_id->Id = "0";
    }


    /* Asignación de "0" a Id si está vacío en $countrybirth_id. */
    if ($countrybirth_id->Id == "") {
        $countrybirth_id->Id = "0";
    }
    if ($countrybirth_id->Id == "") {
        $countrybirth_id->Id = "0";
    }

    /* Asignación de datos de usuario y creación de instancias de clases relacionadas con el país. */
    $team = $user_info->team;
    $birth_date = $user_info->birth_date;

    $Pais = new Pais($countryResident_id->Id);
    $PaisMoneda = new PaisMoneda($countryResident_id->Id);
    $PaisMandante = new PaisMandante('', $site_id, $Pais->paisId);


    /* verifica el estado del país y obtiene la moneda si es válido. */
    if ($PaisMandante->estado != 'A') {
        throw new Exception("Inusual Detected0", "100001");
    }
    $moneda_default = $PaisMoneda->moneda;
}


if (isRestrictedUser($docnumber, $doctype_id, $email, $phone, $nombre, $Mandante->mandante, $Pais->paisId)) throw new Exception('user restrict', 300066);


if ($site_id >= 16) {


    /* Configura valores predeterminados según el sitio y el género para un registro específico. */
    if (in_array($site_id, array(14))) {
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        $nationality_id->Id = 33;
        $pais_residencia = 33;
        $ciudad_id = 27897;
        $city_id->Id = 27897;
        $countryResident_id->Id = 33;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;
        $countrybirth_id->Id = $countryResident_id->Id;

        $Pais = new Pais($countryResident_id->Id);
        $PaisMoneda = new PaisMoneda($countryResident_id->Id);
        $moneda_default = $PaisMoneda->moneda;

    }


    /* Asigna valores predeterminados a género y país si no están definidos. */
    if ($gender == "") {
        $gender = 'M';

    }


    if ($pais_residencia == "") {
        $pais_residencia = $Pais->paisId;
    }


    /* asigna valores a variables si están vacías. */
    if ($nationality_id->Id == "") {
        $nationality_id->Id = $Pais->paisId;
    }

    if ($ciudad_id == "") {
        $ciudad_id = "0";
    }


    /* Asigna "0" a Id si está vacío en city_id y countryResident_id. */
    if ($city_id->Id == "") {
        $city_id->Id = "0";
    }

    if ($countryResident_id->Id == "") {
        $countryResident_id->Id = "0";
    }


    /* Asigna "0" a Id si está vacío en ambas variables. */
    if ($expcity_id->Id == "") {
        $expcity_id->Id = "0";
    }


    if ($citybirth_id->Id == "") {
        $citybirth_id->Id = "0";
    }


    /* asigna "0" al Id si está vacío en countrybirth_id. */
    if ($countrybirth_id->Id == "") {
        $countrybirth_id->Id = "0";
    }
    if ($countrybirth_id->Id == "") {
        $countrybirth_id->Id = "0";
    }

    /* asigna el equipo y la fecha de nacimiento del usuario a variables. */
    $team = $user_info->team;
    $birth_date = $user_info->birth_date;


    if (in_array($site_id, array(17))) {

        /* Asigna 'M' a $gender si está vacío y establece $nationality_id->Id a 33. */
        if ($gender == "") {
            $gender = 'M';

        }
        $registroCorto = true;
        $nationality_id->Id = 33;

        /* Asignación de identificadores para país y ciudad de residencia y nacimiento. */
        $pais_residencia = 33;
        $ciudad_id = 27897;
        $city_id->Id = 27897;
        $countryResident_id->Id = 33;
        $expcity_id->Id = 0;
        $citybirth_id->Id = 0;

        /* Se asigna el ID de residencia a nacimiento, creando objetos de país y moneda. */
        $countrybirth_id->Id = $countryResident_id->Id;

        $Pais = new Pais($countryResident_id->Id);
        $PaisMoneda = new PaisMoneda($countryResident_id->Id);
        $moneda_default = $PaisMoneda->moneda;
        $idioma = 'PT';

    }


    /* Se crean instancias de país y se verifica el estado, lanzando una excepción si es inusual. */
    $Pais = new Pais($countryResident_id->Id);
    $PaisMoneda = new PaisMoneda($countryResident_id->Id);
    $PaisMandante = new PaisMandante('', $site_id, $Pais->paisId);

    if ($PaisMandante->estado != 'A') {
        throw new Exception("Inusual Detected0", "100001");
    }

    /* Asignación de la moneda del país mandante a la variable $moneda_default. */
    $moneda_default = $PaisMandante->moneda;
}


/* Asignación de valores predeterminados si el registro es corto y ciertos campos están vacíos. */
if (($registroCorto) && $birth_date == "") {
    $origen = 1;
    $birth_date = '1970-01-01';
}

if (($registroCorto) && $depto_nacimiento == "") {
    $depto_nacimiento = '0';
}


/* Asigna '0' a ciudad_nacimiento y ocupacion si están vacíos y registroCorto es verdadero. */
if (($registroCorto) && $ciudad_nacimiento == "") {
    $ciudad_nacimiento = '0';
}

if (($registroCorto) && $ocupacion == "") {
    $ocupacion = '0';
}


/* Asigna '0' a variables si cumplen condiciones específicas y están vacías. */
if (($registroCorto) && $rangoingreso_id == "") {
    $rangoingreso_id = '0';
}

if (($registroCorto) && $origen_fondos == "") {
    $origen_fondos = '0';
}

/* Asigna '0' a variables vacías de ocupación y rango de ingreso. */
if ($ocupacion == "") {
    $ocupacion = '0';
}

if ($rangoingreso_id == "") {
    $rangoingreso_id = '0';
}


/* asigna '0' a variables si están vacías o no tienen valor. */
if ($origen_fondos == "") {
    $origen_fondos = '0';
}

if (($registroCorto) && ($countrybirth_id->Id == "")) {
    $paisnacim_id = '0';
    $countrybirth_id->Id = '0';
}


/* Asigna un idioma por defecto según condiciones de registro y moneda. */
if (($registroCorto) && $idioma == "") {
    $idioma = 'ES';
}
if ($moneda_default == 'BRL') {
    $idioma = 'PT';
}


/* verifica condiciones específicas para asignar valores a variables. */
if ($registroCorto && $countryResident_id->Id == 60) {
    $ciudad_id = '0';
    $address = "";
    $city_id->Id = '0';
}
if ($registroCorto && $countryResident_id->Id == 94) {
    $ciudad_id = '0';
    $address = "";
    $city_id->Id = '0';
}


/* asigna valores específicos si se cumplen ciertas condiciones geográficas. */
if ($registroCorto && $countryResident_id->Id == 46) {
    $ciudad_id = '0';
    $address = "";
    $city_id->Id = '0';
}
if ($registroCorto && $countryResident_id->Id == 66) {
    $ciudad_id = '0';
    $address = "";
    $city_id->Id = '0';
}


/* Condicional que asigna valores específicos si $Mandante->mandante es igual a 2. */
if ($Mandante->mandante == 2) {
    $depto_nacimiento = '0';
    $countrybirth_id->Id = '0';
    $nationality_id->Id = '0';
    $idioma = 'EN';
    $ciudad_nacimiento = '0';
    $expcity_id->Id = '0';
}

/* Condicional que asigna valores específicos cuando el mandante es igual a 9. */
if ($Mandante->mandante == 9) {
    $depto_nacimiento = '0';
    $countrybirth_id->Id = '0';
    $nationality_id->Id = '0';
    $idioma = 'EN';
    $ciudad_nacimiento = '0';
    $expcity_id->Id = '0';
}


/* asigna idioma y valores según el estado de $Mandante. */
if ($Mandante->mandante == 14) {
    $idioma = 'PT';
}


if ($Mandante->mandante == 1) {
    $depto_nacimiento = '0';
    $countrybirth_id->Id = '0';
    $nationality_id->Id = '0';
    $idioma = 'EN';
    $ciudad_nacimiento = '0';
    $expcity_id->Id = '0';

    $ciudad_id = 143872;
    $city_id->Id = 143872;

}


/* Asignar ID de país si está vacío y lanzar excepción si residencia está vacía. */
if ($nationality_id->Id == "") {
    $nationality_id->Id = $countryResident_id->Id;

}

if ($pais_residencia == "") {
    throw new Exception("Inusual Detected1", "100001");
}


/* Valida la fecha y departamento de nacimiento según condiciones específicas del mandante. */
if ($birth_date == "" && $Mandante->mandante != 1 && $Mandante->mandante != 12 && $Mandante->mandante != 9) {
    //throw new Exception("Inusual Detected2", "100001");
}

if ($depto_nacimiento == "" && $Mandante->mandante != 1 && $Mandante->mandante != 12 && $Mandante->mandante != 9 && $Mandante->mandante != 16) {
    // throw new Exception("Inusual Detected3", "100001");
}


/* Condiciones para lanzar excepciones si datos de nacimiento son inválidos y mandante específico. */
if ($ciudad_nacimiento == "" && $Mandante->mandante != 1 && $Mandante->mandante != 12 && $Mandante->mandante != 9) {
    //  throw new Exception("Inusual Detected4", "100001");
}

if ($countrybirth_id->Id == "" && $Mandante->mandante != 1 && $Mandante->mandante != 12 && $Mandante->mandante != 9) {
    //  throw new Exception("Inusual Detected5", "100001");
}


/* Asigna '0' a variables vacías de ciudad y departamento de nacimiento. */
if ($ciudad_nacimiento == '') {
    $ciudad_nacimiento = '0';
}

if ($depto_nacimiento == '') {
    $depto_nacimiento = '0';
}


/* asigna '0' si el Id de país o nacionalidad está vacío. */
if ($countrybirth_id->Id == '') {
    $countrybirth_id->Id = '0';
}

if ($nationality_id->Id == '') {
    $nationality_id->Id = '0';
}

/* Valida un ID vacío y lanza excepción si el idioma está vacío. */
if ($expcity_id->Id == '') {
    $expcity_id->Id = '0';
}

if ($idioma == "") {
    throw new Exception("Inusual Detected6", "100001");
}


/* asigna 'M' a género vacío y lanza excepción si falta nacionalidad. */
if ($gender == "") {
    //throw new Exception("Inusual Detected7", "100001");
    $gender = 'M';
}

if ($nationality_id->Id == "") {
    throw new Exception("Inusual Detected8", "100001");
}

/* asigna '0' a variables vacías de identificador de ciudad. */
if ($ciudad_id == "") {
    $ciudad_id = '0';
}
if ($city_id == "") {
    $city_id = '0';
}


/* Lanza excepciones si la ciudad está vacía y el mandante no es 15 o 16. */
if ($ciudad_id == "" && $Mandante->mandante != 15 && $Mandante->mandante != 16) {
    throw new Exception("Inusual Detected9", "100001");
}

if ($city_id == "" && $Mandante->mandante != 15 && $Mandante->mandante != 16) {
    throw new Exception("Inusual Detected10", "100001");
}

switch ($doctype_id) {
    case 1:
        /* asigna el valor "C" a la variable $doctype_id en el caso 1. */

        $doctype_id = "C";
        break;

    case 2:
        /* establece la variable $doctype_id como "E" en el caso 2. */

        $doctype_id = "E";

        break;

    case 3:
        /* asigna el valor "P" a la variable $doctype_id en el caso 3. */


        $doctype_id = "P";

        break;

    default:
        //throw new Exception("Inusual DetectedD", "100001");

        break;
}


/* Asigna un valor por defecto a $doctype_id y define $origen_fondosString si $rfc no está vacío. */
if ($doctype_id == "") {
    $doctype_id = "C";
}
$origen_fondosString = '';

if ($rfc != "") {
    $origen_fondosString = $rfc;
}


/* Valida vacío y formato del email; lanza excepción si hay errores. */
if ($email == '' || $password == '') {
    throw new Exception("Inusual Detected11", "100001");
}


if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
    throw new Exception("Inusual Detected11", "100001");
}


/* Valida si $phone y $docnumber contienen caracteres inusuales o están vacíos. */
if (preg_match('/[a-zA-Z]/', $phone) == 1 || preg_match('/\s/', $phone) == 1 || $phone == '') {
    throw new Exception("Inusual Detected11", "100001");
}

if (preg_match('/\s/', $docnumber) == 1 || $docnumber == '') {
    throw new Exception("Inusual Detected11", "100001");
}


/* Crea un clasificador y determina el estado del usuario según MandanteDetalle. */
$Clasificador = new Clasificador("", "REGISTERACTIVATION");

try {
    $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $Clasificador->getClasificadorId(), $Pais->paisId, 'A');

    $estadoUsuarioDefault = (intval($MandanteDetalle->getValor()) == 1) ? "A" : "I";


} catch (Exception $e) {
    /* Manejo de excepciones en PHP, diferenciando un error específico con código 34. */


    if ($e->getCode() == 34) {
    } else {
    }
}


/* Se crea un objeto Usuario y se verifica si el email ya existe. */
$Usuario = new Usuario();
$Usuario->login = $email;
$Usuario->mandante = $Mandante->mandante;

/* Verificamos si existe el email para el partner */
$checkLogin = $Usuario->exitsLogin();

/* Lanza excepciones si el correo ya está registrado en ciertos sitios. */
if ($checkLogin) {
    if ($site_id == "14") {
        throw new Exception("O e-mail já está cadastrado", "19001");

    }
    if ($site_id == "17") {
        throw new Exception("O e-mail já está cadastrado", "19001");

    }
    throw new Exception("El email ya está registrado", "19001");

    throw new Exception("Inusual Detected", "100001");

}

if ($email == "pruebalanding@email.com" || $json->isPanama == '1') {


    /*       $msj_complementario = ", nuestros operarios validaran el registro para que puedas acceder a tu cuenta.";

           if ($estadoUsuarioDefault == "A") {
               $msj_complementario = ", desde este momento podrá acceder a tu cuenta. Tus datos serán validados. ";

           }

           //Arma el mensaje para el usuario que se registra
           $mensaje_txt = "Bienvenido a ".$Mandante->nombre . $msj_complementario;
           $mensaje_txt = $mensaje_txt . "Tenemos muchas opciones de deposito para ti: <br><br> Medios de pagos via online 100% seguros. <br> Puntos de venta físicos, donde recargas con tu numero de documento legal.  <br><br>";
           $mensaje_txt = $mensaje_txt . "Recuerde que sus credenciales para el acceso son las siguientes:" . "<br><br>";
           $mensaje_txt = $mensaje_txt . "Usuario: " . $email . "<br>";
           $mensaje_txt = $mensaje_txt . "Clave: " . $password . "<br><br>";
           $mensaje_txt = $mensaje_txt . "Nota importante: sugerimos que una vez acceda al sistema por primera vez, cambie la clave inmediatamente; ademas como recomendacion adicional, asegure su cuenta cambiando dicha clave regularmente." . "<br><br>";



           $mtitle = 'Registro satisfactorio - ' . $Mandante->nombre;
           $msubjetc = 'Registro satisfactorio - ' . $Mandante->nombre;

   //Destinatarios
   $destinatarios = 'tecnologiatemp3@gmail.com';
   $ConfigurationEnvironment=new ConfigurationEnvironment();
   //Envia el mensaje de correo
   $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $mensaje_txt, "", "", "", $Mandante->mandante);*/


    /* crea un clasificador y procesa detalles del mandante para determinar estado. */
    $Clasificador = new Clasificador("", "REGISTERACTIVATION");

    try {
        $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $Clasificador->getClasificadorId(), $Pais->paisId, 'A');

        $estadoUsuarioDefault = (intval($MandanteDetalle->getValor()) == 1) ? "A" : "I";


    } catch (Exception $e) {
        /* Manejo de excepciones en PHP, respondiendo a un código de error específico. */


        if ($e->getCode() == 34) {
        } else {
        }
    }


    /* Código PHP que crea una respuesta conredirect URL y un token de autenticación. */
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["redirect"] = true;
    $response["redirectUrl"] = '/home';
    $response["auth_token"] = 12345;


    /* establece una respuesta con datos del usuario y redirección a la página principal. */
    $response["userid"] = 1;
    $response["stateDefault"] = $estadoUsuarioDefault;
    $response["data"] = array(
        "result" => "OK",
        "userid" => 1,
        "redirect" => true,
        "redirectUrl" => '/home'

    );

    /* convierte una respuesta a JSON y la imprime, luego lanza excepciones. */
    $respuesta = json_encode($response);

    print_r($respuesta);
    exit();
    throw new Exception("El email ya esta registrado222", "19001");

    throw new Exception("Inusual Detected", "100001");

}

/* sanitiza y establece un número de documento en un registro. */
$Registro = new Registro();
$docnumber = mb_substr($docnumber, 0, 19);
$docnumber = preg_replace('/[^(\x20-\x7F)]*/', '', $docnumber);
$phone = preg_replace('/[^(\x20-\x7F)]*/', '', $phone);
$code_promo = preg_replace('/[^(\x20-\x7F)]*/', '', $code_promo);

$Registro->setCedula($docnumber);

/* Define variables y establece condiciones basadas en el valor del mandante. */
$Registro->setCelular($phone);
$Registro->setMandante($Mandante->mandante);

$seguirCedula = false;

if ($Mandante->mandante == 13) {
    $seguirCedula = true;
} else {
    /* Verifica si $docnumber no está vacío y si la cédula no existe. */

    if ($docnumber != "") {
        if (!$Registro->existeCedula()) {
            $seguirCedula = true;

        }
    }
}

if ($seguirCedula) {


    /* verifica si el celular existe antes de continuar con el proceso. */
    $seguirCelular = true;

    if ($phone != "") {
        if (!$Registro->existeCelular()) {
            $seguirCelular = false;

        }
    }


    /* Verifica existencia de celular y gestiona consecutivos para usuarios en la base de datos. */
    if ($seguirCelular) {
        throw new Exception("El celular ya existe" . $phone, "19002");
    }

    $consecutivo_usuario = 0;

    /*$Consecutivo = new Consecutivo("", "USU", "");

    $consecutivo_usuario = $Consecutivo->numero;

    $consecutivo_usuario++;

    $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

    $Consecutivo->setNumero($consecutivo_usuario);


    $ConsecutivoMySqlDAO->update($Consecutivo);

    $ConsecutivoMySqlDAO->getTransaction()->commit();*/


    $RegistroMySqlDAO = new RegistroMySqlDAO();

    /* Se obtienen transacciones y se inicializan variables para premios máximos. */
    $Transaction = $RegistroMySqlDAO->getTransaction();
    $BonoInterno = new BonoInterno();


    $premio_max = "";
    $premio_max1 = "";

    /* Variables inicializadas como cadenas vacías para almacenar datos en un programa. */
    $premio_max2 = "";
    $premio_max3 = "";
    $cant_lineas = "";
    $lista_id = "";
    $regalo_registro = "";
    $valor_directo = "";

    /* Variables inicializadas para almacenar valores relacionados con eventos y apuestas. */
    $valor_evento = "";
    $valor_diario = "";
    $destin1 = "";
    $destin2 = "";
    $destin3 = "";

    $apuesta_min = "";


    /* Genera un token y obtiene la dirección IP del usuario almacenada en una variable. */
    $token_itainment = GenerarClaveTicket22(12);

    $dir_ip = $json->session->usuarioip;

    $dir_ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
    $dir_ip = explode(",", $dir_ip)[0];


    /* extrae valores de $btag si contiene '__' tras desencriptarlo. */
    $afiliadorGlobal = 0;

    $afiliador = 0;
    $bannerid = 0;
    $linkid = 0;
    $codigoBD = 0;


    if (strpos($ConfigurationEnvironment->decrypt($btag, ""), '__') !== false) {
        $afiliador = explode("__", $ConfigurationEnvironment->decrypt($btag, ""))[0];
        $linkid = explode("__", $ConfigurationEnvironment->decrypt($btag, ""))[1];
    } elseif (strpos($ConfigurationEnvironment->decrypt($btag, ""), '_') !== false) {
        /* verifica un delimitador y extrae valores de una cadena desencriptada. */

        $afiliador = explode("_", $ConfigurationEnvironment->decrypt($btag, ""))[0];
        $bannerid = explode("_", $ConfigurationEnvironment->decrypt($btag, ""))[1];
    }


    /* asigna el valor de `$codigo2` o `$codigo3` a `$afiliador`, según su contenido. */
    if ($codigo2 != "") {
        if($linkid=='' || $linkid == null || $linkid =='0') {
            $afiliador = $ConfigurationEnvironment->encrypt_decrypt2('decrypt', $codigo2);
            $bannerid = 0;
            $linkid = 0;
        }
    }

    if ($codigo3 != "") {
        if($linkid=='' || $linkid == null || $linkid =='0'){
            $afiliador = $codigo3;
        }
    }


    /* Valida si $afiliador es numérico; si no, lo asigna a 0 y verifica un código promocional. */
    if (!is_numeric($afiliador)) {
        $afiliador = 0;
    }


    if ($afiliador == "2903228") {
        $code_promo = 'DONFUTBOL';

    }


    /* verifica un código promocional y asigna valores relacionados al afiliador. */
    if ($afiliador == "2952248") {
        $code_promo = 'KIKEJAV';

    }


    if ($bannerid == 0 && $linkid == 0) {
        if ($codigo != "") {
            //Trae la apuesta minima para el usuario online

            $apmin2Sql = "select codpromocional_id,usuario_id,link_id from codigo_promocional where codigo='" . $codigo . "' AND estado='A'  AND mandante='" . $Mandante->mandante . "'";
            $apmin2_RS = $BonoInterno->execQuery($Transaction, $apmin2Sql);


            if (($apmin2_RS[0]->{'codigo_promocional.codpromocional_id'} != "")) {
                $codigoBD = $apmin2_RS[0]->{"codigo_promocional.codpromocional_id"};
                if($linkid=='' || $linkid == null || $linkid =='0') {
                    $afiliador = $apmin2_RS[0]->{"codigo_promocional.usuario_id"};
                    $linkid = $apmin2_RS[0]->{"codigo_promocional.link_id"};

                }

            }
        } else {

        }
    }


    /* Consulta y verifica un código promocional activo en la base de datos. */
    if ($code_promo != "") {

        $apmin2Sql = "select codpromocional_id,usuario_id,link_id from codigo_promocional where codigo='" . $code_promo . "' AND estado='A'  AND mandante='" . $Mandante->mandante . "'";
        $apmin2_RS = $BonoInterno->execQuery($Transaction, $apmin2Sql);


        if (($apmin2_RS[0]->{'codigo_promocional.codpromocional_id'} != "")) {
            if($linkid=='' || $linkid == null || $linkid =='0') {
                $afiliador = $apmin2_RS[0]->{'codigo_promocional.usuario_id'};
            }
            $codigoBD = $apmin2_RS[0]->{'codigo_promocional.codpromocional_id'};
            // $linkid = $apmin2_RS["link_id"];
        }
    }


    /* Obtiene la fecha actual y la dirección IP del usuario en PHP. */
    $fecha_actual = date('Y-m-d H:i:s');

    $dir_ip = $json->session->usuarioip;
    if ($json->session->usuarioip == '') {
        $dir_ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
        $dir_ip = explode(",", $dir_ip)[0];
        if ($dir_ip == '') {

            $dir_ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $dir_ip = explode(",", $dir_ip)[0];
        }

    }

    /* Recorta `$dir_ip` a 20 caracteres y asigna valores a las propiedades del usuario. */
    $dir_ip = mb_substr($dir_ip, 0, 20);

    //$Usuario->usuarioId = $consecutivo_usuario;

    $Usuario->login = $email;

    $Usuario->nombre = $nombre;


    /* Se asignan valores por defecto a propiedades del objeto Usuario. */
    $Usuario->estado = $estadoUsuarioDefault;

    $Usuario->fechaUlt = date('Y-m-d H:i:s');

    $Usuario->claveTv = '';

    $Usuario->estadoAnt = 'I';


    /* Se inicializan propiedades del objeto Usuario, como intentos, estado, observaciones y dirección IP. */
    $Usuario->intentos = 0;

    $Usuario->estadoEsp = $estadoUsuarioDefault;

    $Usuario->observ = '';

    $Usuario->dirIp = $dir_ip;


    /* Inicializa propiedades del objeto Usuario, asignando valores predeterminados y referencias. */
    $Usuario->eliminado = 'N';

    $Usuario->mandante = $Mandante->mandante;

    $Usuario->usucreaId = '0';

    $Usuario->usumodifId = '0';


    /* Se inicializan propiedades de un objeto "Usuario" relacionadas con el casino y autenticación. */
    $Usuario->claveCasino = '';

    $Usuario->tokenItainment = $token_itainment;

    $Usuario->fechaClave = '';

    $Usuario->retirado = 'N';


    /* Se inicializan propiedades del objeto Usuario relacionadas con retiradas y bloqueo de ventas. */
    $Usuario->fechaRetiro = '';

    $Usuario->horaRetiro = '';

    $Usuario->usuretiroId = '0';

    $Usuario->bloqueoVentas = 'N';


    /* Se inicializan propiedades de un objeto Usuario con valores por defecto. */
    $Usuario->infoEquipo = '';

    $Usuario->estadoJugador = 'NN';

    $Usuario->tokenCasino = '';

    $Usuario->sponsorId = 0;


    /* asigna valores a propiedades de un objeto usuario. */
    $Usuario->verifCorreo = 'N';

    $Usuario->paisId = $countryResident_id->Id;

    $Usuario->moneda = $moneda_default;

    $Usuario->idioma = $idioma;


    /* Establece propiedades de usuario relacionadas con permisos y limitaciones. */
    $Usuario->permiteActivareg = 'N';

    $Usuario->test = 'N';

    $Usuario->tiempoLimitedeposito = 0;

    $Usuario->tiempoAutoexclusion = 0;


    /* Código asigna valores a propiedades de un objeto Usuario en PHP. */
    $Usuario->cambiosAprobacion = 'S';

    $Usuario->timezone = '-5';

    $Usuario->puntoventaId = 0;
    $Usuario->usucreaId = 0;

    /* Inicializa propiedades de un objeto Usuario con valores predeterminados y la fecha actual. */
    $Usuario->usumodifId = 0;
    $Usuario->usuretiroId = 0;
    $Usuario->sponsorId = (0);

    $Usuario->puntoventaId = 0;

    $Usuario->fechaCrea = date('Y-m-d H:i:s');


    /* Se asignan valores a propiedades de un objeto Usuario, incluyendo origen y fechas. */
    $Usuario->origen = $origen;

    $Usuario->fechaActualizacion = $Usuario->fechaCrea;
    $Usuario->documentoValidado = "I";
    $Usuario->fechaDocvalido = $Usuario->fechaCrea;
    $Usuario->usuDocvalido = 0;


    /* Se inicializan propiedades de un objeto usuario relacionadas con su estado de validación. */
    $Usuario->estadoValida = 'N';
    $Usuario->usuvalidaId = 0;
    $Usuario->fechaValida = date('Y-m-d H:i:s');
    $Usuario->contingencia = 'I';
    $Usuario->contingenciaDeportes = 'I';
    $Usuario->contingenciaCasino = 'I';

    /* Asignación de variables de contingencia y restricción IP en un objeto Usuario. */
    $Usuario->contingenciaCasvivo = 'I';
    $Usuario->contingenciaVirtuales = 'I';
    $Usuario->contingenciaPoker = 'I';
    $Usuario->restriccionIp = 'I';
    $Usuario->ubicacionLongitud = '';
    $Usuario->ubicacionLatitud = '';

    /* Inicializa propiedades de un objeto Usuario con valores predeterminados. */
    $Usuario->usuarioIp = '';
    $Usuario->tokenGoogle = "I";
    $Usuario->tokenLocal = "I";
    $Usuario->saltGoogle = '';

    $Usuario->skype = '';

    /* inicializa atributos del objeto Usuario con valores predeterminados. */
    $Usuario->plataforma = 0;


    $Usuario->fechaActualizacion = $Usuario->fechaCrea;
    $Usuario->documentoValidado = "I";
    $Usuario->fechaDocvalido = '1970-01-01 00:00:00';

    /* Se inicializan propiedades y se verifica celular según el mandante específico. */
    $Usuario->usuDocvalido = 0;
    $Usuario->equipoId = intval($team);

    if ($Mandante->mandante == 14) {
        $Usuario->verifCelular = 'S';
        $Usuario->fechaVerifCelular = date('Y-m-d H:i:s');
    }


    /* Establece condiciones para actualizar la propiedad 'contingenciaRetiro' del objeto Usuario. */
    if ($Usuario->mandante == 14 && date('Y-m-d H:i:s') >= '2023-05-27 00:00:00') {
        $Usuario->contingenciaRetiro = 'A';
    }

    if ($Usuario->mandante == 0 && $Usuario->paisId == 46 && date('Y-m-d H:i:s') >= '2023-05-29 00:00:00' && date('Y-m-d H:i:s') <= '2023-05-31 23:59:59') {
        $Usuario->contingenciaRetiro = 'A';
    }


    /* asigna 'A' a contingenciaRetiro bajo ciertas condiciones de usuario y fecha. */
    if ($Usuario->mandante == 0 && $Usuario->paisId == 46 && date('Y-m-d H:i:s') >= '2024-01-17 00:00:00') {
        $Usuario->contingenciaRetiro = 'A';
    }

    if ($Usuario->mandante == 0 && $Usuario->paisId == 66 && date('Y-m-d H:i:s') >= '2024-01-17 00:00:00') {
        $Usuario->contingenciaRetiro = 'A';
    }


    if ($Usuario->mandante == 0 && $Usuario->paisId == 2
        && date('Y-m-d H:i:s') >= '2023-04-01 00:00:00'
        && date('Y-m-d H:i:s') <= '2023-04-02 23:59:59'
    ) {
        $Usuario->contingenciaRetiro = 'A';
    }


    $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
    //$UsuarioMySqlDAO = new UsuarioMySqlDAO();


    /* Verifica condición y luego inserta un objeto Usuario en la base de datos. */
    if ($Mandante->mandante == 2) {
        //$Usuario->verifcedulaAnt='S';
        //$Usuario->verifcedulaPost='S';
    }


    $UsuarioMySqlDAO->insert($Usuario);


    /* registra un usuario en el sistema de seguimiento si existe un trackerId. */
    $consecutivo_usuario = $Usuario->usuarioId;

    if ($trackerId != '' && $trackerId != null) {

        $sitioTracking = new SitioTracking();
        $sitioTracking->setTabla('Register_user');
        $sitioTracking->setTablaId($consecutivo_usuario);
        $sitioTracking->setTvalue($trackerId);
        $sitioTracking->setUsucreaId($consecutivo_usuario);
        $sitioTracking->setTipo(2);

        $SitioTrackingMySqlDAO = new SitioTrackingMySqlDAO($Transaction);
        $SitioTrackingMySqlDAO->insert($sitioTracking);

    }

    //$UsuarioMySqlDAO->getTransaction()->commit();


    /* Se establece información en un objeto Registro, incluyendo nombre, email y estado. */
    $Registro->setNombre($nombre);
    $Registro->setEmail($email);
    $Registro->setClaveActiva($clave_activa);
    $Registro->setEstado($estadoUsuarioDefault);
    $Registro->usuarioId = $consecutivo_usuario;
    $Registro->setCelular($phone);

    /* Código que inicializa valores de créditos y asigna un ID de ciudad al registro. */
    $Registro->setCreditosBase(0);
    $Registro->setCreditos(0);
    $Registro->setCreditosAnt(0);
    $Registro->setCreditosBaseAnt(0);
    //$Registro->setCiudadId($department_id->cities[0]->id);
    $Registro->setCiudadId($city_id->Id);

    /* establece propiedades de un objeto Registro y normaliza el nombre. */
    $Registro->setCasino(0);
    $Registro->setCasinoBase(0);
    $Registro->setMandante($Mandante->mandante);
    $first_name = $ConfigurationEnvironment->DepurarCaracteres($first_name);
    $first_name = mb_substr($first_name, 0, 19);

    $Registro->setNombre1($first_name);


    /* Se depuran y limitan caracteres del segundo nombre y apellido antes de asignar. */
    $middle_name = $ConfigurationEnvironment->DepurarCaracteres($middle_name);

    $middle_name = mb_substr($middle_name, 0, 19);

    $Registro->setNombre2($middle_name);

    $last_name = $ConfigurationEnvironment->DepurarCaracteres($last_name);


    /* recorta y depura apellidos para ajustarlos a un límite de caracteres. */
    $last_name = mb_substr($last_name, 0, 19);
    $Registro->setApellido1($last_name);

    $second_last_name = $ConfigurationEnvironment->DepurarCaracteres($second_last_name);

    $second_last_name = mb_substr($second_last_name, 0, 19);

    /* Código para establecer atributos de un objeto de registro. */
    $Registro->setApellido2($second_last_name);

    $Registro->setSexo($gender);
    $Registro->setTipoDoc($doctype_id);
    $Registro->setDireccion($address);
    $Registro->setTelefono($landline_number);

    /* asigna valores a propiedades de un objeto $Registro. */
    $Registro->setCiudnacimId($ciudad_nacimiento);
    $Registro->setNacionalidadId($nationality_id->Id);
    $Registro->setDirIp($dir_ip);
    $Registro->setOcupacionId($ocupacion);
    $Registro->setRangoingresoId($rangoingreso_id);
    $Registro->setOrigenfondosId($origen_fondos);

    /* establece múltiples propiedades en un objeto llamado $Registro. */
    $Registro->setOrigenFondos($origen_fondosString);
    $Registro->setPaisnacimId($countrybirth_id->Id);
    $Registro->setPuntoVentaId(0);
    $Registro->setPreregistroId(0);
    $Registro->setCreditosBono(0);
    $Registro->setCreditosBonoAnt(0);

    /* establece valores en un objeto Registro, incluyendo ID, fecha y código postal. */
    $Registro->setPreregistroId(0);
    $Registro->setUsuvalidaId(0);
    $Registro->setFechaValida($fecha_actual);
    $Registro->setCodigoPostal($cp);

    $Registro->setCiudexpedId($expcity_id->Id);


    /* establece una fecha de expedición basada en una condición. */
    if ($expedition_date != '') {
        $Registro->setFechaExped($expedition_date);

    } else {
        $Registro->setFechaExped($expedition_year . "-" . $expedition_month . "-" . $expedition_day);

    }

    /* Establece el ID de punto de venta y valida estado según el mandante. */
    $Registro->setPuntoventaId(0);

    $EstadoValidaRegistro = 'I';

    if ($Mandante->mandante == '13') {
        $EstadoValidaRegistro = 'A';
    }


    /* verifica si 'afiliador' es un entero y establece su valor si no lo es. */
    $Registro->setEstadoValida($EstadoValidaRegistro);

    if (!is_int($afiliador)) {
        // $afiliador = 0;

    }

    /* Verifica si bannerid y linkid son enteros; de no serlo, comenta asignaciones. */
    if (!is_int($bannerid)) {

        // $bannerid = 0;
    }
    if (!is_int($linkid)) {

        //$linkid = '0';
    }

    /* verifica si variables son enteras y asigna valores predeterminados. */
    if (!is_int($codigoBD)) {

        //$codigoBD = 0;
    }
    if (intval($linkid) == '') {
        $linkid = '0';
    }


    /* asigna '0' a variables si están vacías o no son enteros. */
    if (intval($bannerid) == '') {
        $bannerid = '0';
    }

    if ($afiliador == '') {
        $afiliador = '0';
    }


    /* Validación del afiliador, ajustando variables si no coincide la moneda predeterminada. */
    if ($afiliador != '0') {
        try {
            $afiliadorGlobal=$afiliador;

            $UsuarioAfiliador = new Usuario($afiliador);
            if ($UsuarioAfiliador->moneda != $moneda_default) {
                $afiliador = '0';
                $linkid = '0';
                $bannerid = '0';
            }
        } catch (Exception $e) {
            $afiliador = '0';
            $linkid = '0';
            $bannerid = '0';
        }
    }


    /* Se asignan valores a un objeto y se inserta en la base de datos. */
    $Registro->setAfiliadorId($afiliador);
    $Registro->setBannerId($bannerid);
    $Registro->setLinkId($linkid);
    $Registro->setCodpromocionalId($codigoBD);


    $RegistroMySqlDAO->insert($Registro);


    /* Código verifica programa de referidos, valida link, y registra logros del usuario referido. */
    $Transaccion = $RegistroMySqlDAO->getTransaction();

    $usuidReferente = '0';

    $UsuarioOtrainfo = new UsuarioOtrainfo();

    try {
        /** Disponibilidad e implementación programa de referidos */
        if (!empty($referentLink)) {
            //Validando programa y compatibilidad
            $PaisMandante->progReferidosDisponible();
            $usuidReferente = $PaisMandante->validarLinkReferenteCompatible($Usuario, $referentLink);
            //Insertando logros que debe cumplir el referido
            $LogroReferido = new LogroReferido();
            $UsuarioReferente = new Usuario($usuidReferente);
            $LogroReferido->insertarLogrosNuevoReferido($Transaccion, $UsuarioReferente, $Usuario);
        }
    } catch (Exception $e) {
        /* Manejo de excepciones en PHP, asignando '0' a $usuidReferente si hay error. */

        $usuidReferente = '0';
    }


    /* Asignación de valores a atributos de un objeto relacionado con un usuario. */
    $UsuarioOtrainfo->usuarioId = $consecutivo_usuario;
    $UsuarioOtrainfo->fechaNacim = $birth_date;
    $UsuarioOtrainfo->mandante = $Mandante->mandante;
    $UsuarioOtrainfo->info2 = $docnumber2;
    $UsuarioOtrainfo->bancoId = '0';
    $UsuarioOtrainfo->numCuenta = '0';

    /* Se están asignando valores a un objeto y creando un DAO para interacción con MySQL. */
    $UsuarioOtrainfo->anexoDoc = 'N';
    $UsuarioOtrainfo->direccion = $address;
    $UsuarioOtrainfo->tipoCuenta = '0';
    $UsuarioOtrainfo->usuidReferente = $usuidReferente;


    $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaccion);
    //$UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();


    /* Inserta información de usuario y establece su perfil en la base de datos. */
    $UsuarioOtrainfoMySqlDAO->insert($UsuarioOtrainfo);
    //$UsuarioOtrainfoMySqlDAO->getTransaction()->commit();

    $UsuarioPerfil = new UsuarioPerfil();

    $UsuarioPerfil->setUsuarioId($consecutivo_usuario);

    /* Configura un perfil de usuario y crea un acceso a la base de datos. */
    $UsuarioPerfil->setPerfilId('USUONLINE');
    $UsuarioPerfil->setMandante($Mandante->mandante);
    $UsuarioPerfil->setPais('N');
    $UsuarioPerfil->setGlobal('N');


    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaccion);
    //$UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();

    /* Se inserta un perfil de usuario y se inicializa una variable de premio máximo. */
    $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);
    //$UsuarioPerfilMySqlDAO->getTransaction()->commit();

    $UsuarioPremiomax = new UsuarioPremiomax();

    $premio_max1 = 0;

    /* Se definen variables para gestionar premios, apuestas y valores en un juego. */
    $premio_max2 = 0;
    $premio_max3 = 0;
    $apuesta_min = 0;
    $cant_lineas = 0;
    $valor_directo = 0;
    $valor_evento = 0;

    /* Se inicializa una variable y se asignan valores a un objeto de usuario. */
    $valor_diario = 0;

    $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

    $UsuarioPremiomax->premioMax = $premio_max1;

    $UsuarioPremiomax->usumodifId = '0';


    /* asigna valores a propiedades del objeto UsuarioPremiomax. */
    $UsuarioPremiomax->cantLineas = $cant_lineas;

    $UsuarioPremiomax->premioMax1 = $premio_max1;

    $UsuarioPremiomax->premioMax2 = $premio_max2;

    $UsuarioPremiomax->premioMax3 = $premio_max3;


    /* asigna valores a propiedades de un objeto UsuarioPremiomax. */
    $UsuarioPremiomax->apuestaMin = $apuesta_min;

    $UsuarioPremiomax->valorDirecto = $valor_directo;
    $UsuarioPremiomax->premioDirecto = $valor_directo;


    $UsuarioPremiomax->mandante = $Mandante->mandante;

    /* Se asignan valores y configuraciones a un objeto "UsuarioPremiomax". */
    $UsuarioPremiomax->optimizarParrilla = 'N';


    $UsuarioPremiomax->valorEvento = $valor_evento;

    $UsuarioPremiomax->valorDiario = $valor_diario;


    /* Se crea un objeto DAO para insertar un usuario en la base de datos. */
    $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($Transaccion);
    //$UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO();
    $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);
    //$UsuarioPremiomaxMySqlDAO->getTransaction()->commit();


    if ($Mandante->mandante == 2 || $Mandante->mandante == 1) {

        /* Asignación de variables a partir de información de usuario en un contexto específico. */
        $ClientId = $consecutivo_usuario;


        $file = $user_info->file;
        $file2 = $user_info->file2;
        $file3 = $user_info->file3;

        /* Asignación de variables desde el objeto $user_info en PHP. */
        $file4 = $user_info->file4;
        $type = $user_info->type;
        $tipo = 'USUDNIANTERIOR';


        if ($file != "" && $file != "undefined") {
            if (!empty($file) && $file !== "undefined") {


                /* Convierte un archivo en base64 a texto legible y lo prepara para su uso. */
                if (is_array($file)) {
                    $file = implode(" ", $file);
                }

                if (is_string($file)) {
                    $file = str_replace(" ", "+", $file);
                    $type = 'A';
                    $tipo = 'USUDNIANTERIOR';

                    $pos = strpos($file, 'base64,');
                    if ($pos !== false) {
                        $file_contents1 = base64_decode(mb_substr($file, $pos + 7));
                        $file_contents1 = addslashes($file_contents1);
                    } else {
                        $file_contents1 = '';
                    }
                } else {
                    /* asigna una cadena vacía a $file_contents1 en caso de que no se cumpla la condición. */

                    $file_contents1 = '';

                }
            }

            /*$name = $consecutivo_usuario . time() . $type . ".png";
            $data1 = $ConfigurationEnvironment->base64ToImage($file, $name);
            $data1 = file_get_contents($name);

            $file_contents1 = addslashes($data1);

            unlink($name);*/

        }

        /* verifica y decodifica un archivo en base64, reemplazando espacios. */
        if ($file2 != "" && $file2 != "undefined") {
            $file2 = str_replace(" ", "+", $file2);
            $type = 'P';
            $tipo = 'USUDNIPOSTERIOR';

            $pos = strpos($file2, 'base64,');
            $file_contents2 = base64_decode(mb_substr($file2, $pos + 7));
            $file_contents2 = addslashes($file_contents2);
            /*
            $name = $consecutivo_usuario . time() . $type . ".png";
            $data1 = $ConfigurationEnvironment->base64ToImage($file2, $name);
            $data1 = file_get_contents($name);

            $file_contents2 = addslashes($data1);

            unlink($name);*/

        }


        /* Verifica el archivo y decodifica contenido en base64, escapando caracteres especiales. */
        if ($file3 != "" && $file3 != "undefined") {
            $type = 'A';
            $tipo = 'USUTRNANTERIOR';

            $pos = strpos($file3, 'base64,');
            $file_contents3 = base64_decode(mb_substr($file3, $pos + 7));
            $file_contents3 = addslashes($file_contents3);

        }

        /* Verifica un archivo, decodifica su contenido en Base64 y lo escapa. */
        if ($file4 != "" && $file4 != "undefined") {
            $type = 'P';
            $tipo = 'USUTRNPOSTERIOR';

            $pos = strpos($file4, 'base64,');
            $file_contents4 = base64_decode(mb_substr($file4, $pos + 7));
            $file_contents4 = addslashes($file_contents4);

        }

        if ($file_contents1 != '') {

            /* Se define una variable llamada `$estadoLog` con el valor 'P'. */
            $estadoLog = 'P';

            if ($Mandante->mandante == 2) {
                //$estadoLog='A';
                // $Usuario->verifcedulaAnt='S';

                /* try{
                     $filename = "c" . $Usuario->usuarioId;
                     $filename = $filename . 'A';
                     $filename = $filename . '.png';
                     $bucketName = 'cedulas-1';
                     $objectName = 'c/'.$filename;
 // Authenticate your API Client
                     $client = new Google_Client();
                     $client->setAuthConfig('/etc/private/virtual.json');
                     $client->useApplicationDefaultCredentials();
                     $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

                     $storage = new Google_Service_Storage($client);

                     $file_name = 'c/'.$filename;

                     $dirsave =  $filename;
                     $file = fopen($dirsave, "wb");

                     fwrite($file, base64_decode($file_contents1));
                     fclose($file);

                     $obj = new Google_Service_Storage_StorageObject();
                     $obj->setName($file_name);
                     $obj->setMetadata(['contentType' => 'image/jpeg']);

                     $storage->objects->insert(
                         $bucketName,
                         $obj,
                         ['mimeType' => 'image/jpeg','name' => $file_name, 'data' => file_get_contents($dirsave), 'uploadType' => 'media']
                     );

                     unlink($dirsave);
                 }catch (Exception $e){

                 }*/

            }


            /* Se crea un registro de usuario con información de identificación y solicitud. */
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($consecutivo_usuario);
            $UsuarioLog->setUsuarioIp($dir_ip);
            $UsuarioLog->setUsuariosolicitaId($consecutivo_usuario);
            $UsuarioLog->setUsuariosolicitaIp($dir_ip);
            $UsuarioLog->setTipo("USUDNIANTERIOR");

            /* Registro de un usuario con estado y valores anteriores y posteriores establecidos. */
            $UsuarioLog->setEstado($estadoLog);
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues("");
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setImagen($file_contents1);

            /* Se crea un DAO para insertar un registro de usuario en la base de datos MySQL. */
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

        }
        if ($file_contents2 != '') {

            /* Variables en PHP, donde $estadoLog se inicializa con el valor 'P'. */
            $estadoLog = 'P';

            if ($Mandante->mandante == 2) {
                //$estadoLog='A';
                //$Usuario->verifcedulaPost='S';

                /*try{
                    $filename = "c" . $Usuario->usuarioId;
                    $filename = $filename . 'P';
                    $filename = $filename . '.png';
                    $bucketName = 'cedulas-1';
                    $objectName = 'c/'.$filename;
// Authenticate your API Client
                    $client = new Google_Client();
                    $client->setAuthConfig('/etc/private/virtual.json');
                    $client->useApplicationDefaultCredentials();
                    $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

                    $storage = new Google_Service_Storage($client);

                    $file_name = 'c/'.$filename;

                    $dirsave =  $filename;
                    $file = fopen($dirsave, "wb");

                    fwrite($file, base64_decode($file_contents2));
                    fclose($file);

                    $obj = new Google_Service_Storage_StorageObject();
                    $obj->setName($file_name);
                    $obj->setMetadata(['contentType' => 'image/jpeg']);

                    $storage->objects->insert(
                        $bucketName,
                        $obj,
                        ['mimeType' => 'image/jpeg','name' => $file_name, 'data' => file_get_contents($dirsave), 'uploadType' => 'media']
                    );

                    unlink($dirsave);
                }catch (Exception $e){

                }*/
            }


            /* Se crea un objeto UsuarioLog2 y se configuran sus propiedades. */
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($consecutivo_usuario);
            $UsuarioLog->setUsuarioIp($dir_ip);
            $UsuarioLog->setUsuariosolicitaId($consecutivo_usuario);
            $UsuarioLog->setUsuariosolicitaIp($dir_ip);
            $UsuarioLog->setTipo("USUDNIPOSTERIOR");

            /* establece propiedades de un objeto UsuarioLog, incluyendo estado e imagen. */
            $UsuarioLog->setEstado($estadoLog);
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues("");
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setImagen($file_contents2);

            /* Se crea un objeto DAO y se inserta un log de usuario en la base de datos. */
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

        }
        if ($file_contents3 != '') {

            /* Definición de una variable que almacena el estado de un log como 'P'. */
            $estadoLog = 'P';

            if ($Mandante->mandante == 2) {
                //$estadoLog='A';
                //$Usuario->verifcedulaAnt='S';

                try {

                    /* Genera un nombre de archivo PNG basado en el ID de usuario para almacenamiento en Google Cloud. */
                    $filename = "trn" . $Usuario->usuarioId;
                    $filename = $filename . 'A';
                    $filename = $filename . '.png';
                    $bucketName = 'cedulas-1';
                    $objectName = 'c/' . $filename;
// Authenticate your API Client
                    $client = new Google_Client();

                    /* Configuración de cliente para acceso completo a Google Cloud Storage mediante autenticación. */
                    $client->setAuthConfig('/etc/private/virtual.json');
                    $client->useApplicationDefaultCredentials();
                    $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

                    $storage = new Google_Service_Storage($client);

                    $file_name = 'c/' . $filename;


                    /* guarda un archivo decodificado en una ubicación temporal utilizando Google Cloud Storage. */
                    $dirsave = '/tmp/' . $filename;
                    $file = fopen($dirsave, "wb");

                    fwrite($file, base64_decode($file_contents3));
                    fclose($file);

                    $obj = new Google_Service_Storage_StorageObject();

                    /* Se sube una imagen JPEG a Google Cloud Storage con metadatos. */
                    $obj->setName($file_name);
                    $obj->setMetadata(['contentType' => 'image/jpeg']);

                    $storage->objects->insert(
                        $bucketName,
                        $obj,
                        ['mimeType' => 'image/jpeg', 'name' => $file_name, 'data' => file_get_contents($dirsave), 'uploadType' => 'media']
                    );


                    /* elimina un directorio o archivo especificado en la variable $dirsave. */
                    unlink($dirsave);
                } catch (Exception $e) {
                    /* maneja excepciones en PHP, capturando errores sin hacer ninguna acción adicional. */


                }

            }


            /* Crea un registro de log para un usuario específico con datos relevantes. */
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($consecutivo_usuario);
            $UsuarioLog->setUsuarioIp($dir_ip);
            $UsuarioLog->setUsuariosolicitaId($consecutivo_usuario);
            $UsuarioLog->setUsuariosolicitaIp($dir_ip);
            $UsuarioLog->setTipo("USUTRNANTERIOR");

            /* Configura un registro de usuario con estado y valores iniciales, incluyendo una imagen. */
            $UsuarioLog->setEstado($estadoLog);
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues("");
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setImagen($file_contents3);

            /* Código que inserta un registro de log de usuario en una base de datos MySQL. */
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

        }
        if ($file_contents4 != '') {

            /* Se inicializa la variable $estadoLog con el valor 'P' para representar un estado. */
            $estadoLog = 'P';
            if ($Mandante->mandante == 2) {
                //$estadoLog='A';
                //$Usuario->verifcedulaAnt='S';

                try {

                    /* genera un nombre de archivo PNG para un usuario y autentica un cliente de Google. */
                    $filename = "trn" . $Usuario->usuarioId;
                    $filename = $filename . 'P';
                    $filename = $filename . '.png';
                    $bucketName = 'cedulas-1';
                    $objectName = 'c/' . $filename;
// Authenticate your API Client
                    $client = new Google_Client();

                    /* Configura cliente de Google Storage usando credenciales y permisos necesarios para acceder. */
                    $client->setAuthConfig('/etc/private/virtual.json');
                    $client->useApplicationDefaultCredentials();
                    $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

                    $storage = new Google_Service_Storage($client);

                    $file_name = 'c/' . $filename;


                    /* guarda un archivo decodificado en formato base64 en un directorio temporal. */
                    $dirsave = '/tmp/' . $filename;
                    $file = fopen($dirsave, "wb");

                    fwrite($file, base64_decode($file_contents4));
                    fclose($file);

                    $obj = new Google_Service_Storage_StorageObject();

                    /* Sube una imagen JPEG a un almacenamiento en la nube, configurando nombre y metadatos. */
                    $obj->setName($file_name);
                    $obj->setMetadata(['contentType' => 'image/jpeg']);

                    $storage->objects->insert(
                        $bucketName,
                        $obj,
                        ['mimeType' => 'image/jpeg', 'name' => $file_name, 'data' => file_get_contents($dirsave), 'uploadType' => 'media']
                    );


                    /* elimina un directorio especificado por la variable $dirsave. */
                    unlink($dirsave);
                } catch (Exception $e) {
                    /* Manejo de excepciones en PHP, captura de errores sin realizar ninguna acción. */


                }

            }


            /* Crea un objeto de registro de usuario con información de identificación y tipo. */
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($consecutivo_usuario);
            $UsuarioLog->setUsuarioIp($dir_ip);
            $UsuarioLog->setUsuariosolicitaId($consecutivo_usuario);
            $UsuarioLog->setUsuariosolicitaIp($dir_ip);
            $UsuarioLog->setTipo("USUTRNPOSTERIOR");

            /* establece atributos de un objeto `UsuarioLog` relacionados con un registro. */
            $UsuarioLog->setEstado($estadoLog);
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues("");
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setImagen($file_contents4);

            /* Se crea un objeto DAO para insertar registros de usuario en MySQL. */
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

        }
    }


    /* configura un límite diario de depósito para un usuario específico. */
    if ($Mandante->mandante == 2) {
        $Clasificador = new Clasificador("", "LIMITEDEPOSITODIARIO");
        $tipo = $Clasificador->getClasificadorId();

        $UsuarioConfiguracion2 = new UsuarioConfiguracion();
        $UsuarioConfiguracion2->setUsuarioId($Usuario->usuarioId);
        $UsuarioConfiguracion2->setTipo($tipo);
        $UsuarioConfiguracion2->setValor('40000');
        $UsuarioConfiguracion2->setUsucreaId("0");
        $UsuarioConfiguracion2->setUsumodifId("0");
        $UsuarioConfiguracion2->setProductoId(0);
        $UsuarioConfiguracion2->setEstado("A");
        $UsuarioConfiguracion2->fechaModif = date("Y-m-d 00:00:00");

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaccion);
        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);

    }
    if ($limit_deposit_day != 0 && $limit_deposit_day != '') {

        /* Código para configurar un límite de depósito diario en un sistema. */
        $ClientId = $consecutivo_usuario;

        $Clasificador = new Clasificador("", "LIMITEDEPOSITODIARIO");
        $tipo = $Clasificador->getClasificadorId();

        /*$UsuarioConfiguracion2 = new UsuarioConfiguracion();
        $UsuarioConfiguracion2->setUsuarioId($ClientId);
        $UsuarioConfiguracion2->setTipo($tipo);
        $UsuarioConfiguracion2->setValor($limit_deposit_day);
        $UsuarioConfiguracion2->setUsucreaId("0");
        $UsuarioConfiguracion2->setUsumodifId("0");

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);*/

        $UsuarioLog = new UsuarioLog2();

        /* Configura datos del log de usuario, incluyendo ID y dirección IP. */
        $UsuarioLog->setUsuarioId($ClientId);
        $UsuarioLog->setUsuarioIp($dir_ip);
        $UsuarioLog->setUsuariosolicitaId($ClientId);
        $UsuarioLog->setUsuariosolicitaIp($dir_ip);
        $UsuarioLog->setTipo($tipo);
        $UsuarioLog->setEstado("P");

        /* registra cambios en el log de usuario en una base de datos. */
        $UsuarioLog->setValorAntes("");
        $UsuarioLog->setValorDespues($limit_deposit_day);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
        $UsuarioLogMySqlDAO->insert($UsuarioLog);

    }
    if ($limit_deposit_week != 0 && $limit_deposit_week != '') {

        /* Inicializa un cliente y configura su límite de depósitos semanales en el sistema. */
        $ClientId = $consecutivo_usuario;

        $Clasificador = new Clasificador("", "LIMITEDEPOSITOSEMANA");
        $tipo = $Clasificador->getClasificadorId();

        /*$UsuarioConfiguracion2 = new UsuarioConfiguracion();
        $UsuarioConfiguracion2->setUsuarioId($ClientId);
        $UsuarioConfiguracion2->setTipo($tipo);
        $UsuarioConfiguracion2->setValor($limit_deposit_week);
        $UsuarioConfiguracion2->setUsucreaId("0");
        $UsuarioConfiguracion2->setUsumodifId("0");

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);*/

        $UsuarioLog = new UsuarioLog2();

        /* Configura un objeto UsuarioLog con identificadores y estado específico del usuario. */
        $UsuarioLog->setUsuarioId($ClientId);
        $UsuarioLog->setUsuarioIp($dir_ip);
        $UsuarioLog->setUsuariosolicitaId($ClientId);
        $UsuarioLog->setUsuariosolicitaIp($dir_ip);
        $UsuarioLog->setTipo($tipo);
        $UsuarioLog->setEstado("P");

        /* Se registra un cambio en el usuario, insertando datos en la base de datos. */
        $UsuarioLog->setValorAntes("");
        $UsuarioLog->setValorDespues($limit_deposit_week);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
        $UsuarioLogMySqlDAO->insert($UsuarioLog);


    }


    if ($limit_deposit_month != 0 && $limit_deposit_month != '') {

        /* establece configuraciones de usuario y registra acciones en un sistema. */
        $ClientId = $consecutivo_usuario;

        $Clasificador = new Clasificador("", "LIMITEDEPOSITOMENSUAL");
        $tipo = $Clasificador->getClasificadorId();

        /*$UsuarioConfiguracion2 = new UsuarioConfiguracion();
        $UsuarioConfiguracion2->setUsuarioId($ClientId);
        $UsuarioConfiguracion2->setTipo($tipo);
        $UsuarioConfiguracion2->setValor($limit_deposit_month);
        $UsuarioConfiguracion2->setUsucreaId("0");
        $UsuarioConfiguracion2->setUsumodifId("0");

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);*/

        $UsuarioLog = new UsuarioLog2();

        /* Se registra un usuario con ID, IP, tipo y estado "P" en el sistema. */
        $UsuarioLog->setUsuarioId($ClientId);
        $UsuarioLog->setUsuarioIp($dir_ip);
        $UsuarioLog->setUsuariosolicitaId($ClientId);
        $UsuarioLog->setUsuariosolicitaIp($dir_ip);
        $UsuarioLog->setTipo($tipo);
        $UsuarioLog->setEstado("P");

        /* Se registran cambios en un log de usuario en la base de datos. */
        $UsuarioLog->setValorAntes("");
        $UsuarioLog->setValorDespues($limit_deposit_month);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
        $UsuarioLogMySqlDAO->insert($UsuarioLog);


    }

    if ($personPoliticallyExposed != '') {


        /* asigna "S" o "N" según si la persona es PEP. */
        if ($personPoliticallyExposed == "true") {
            $personPoliticallyExposed = "S";
        } else {
            $personPoliticallyExposed = "N";
        }

        $Clasificador = new Clasificador("", "PEP");


        /* Se crea un nuevo objeto UsuarioConfiguracion con datos del usuario y clasificador. */
        $UsuarioConfiguracion = new UsuarioConfiguracion();
        $UsuarioConfiguracion->usuarioId = $consecutivo_usuario;
        $UsuarioConfiguracion->tipo = $Clasificador->getClasificadorId();
        $UsuarioConfiguracion->valor = $personPoliticallyExposed;
        $UsuarioConfiguracion->usucreaId = $consecutivo_usuario;
        $UsuarioConfiguracion->usumodifId = 0;

        /* Se inserta un objeto de configuración de usuario en la base de datos. */
        $UsuarioConfiguracion->productoId = 0;
        $UsuarioConfiguracion->estado = 'A';
        $UsuarioConfiguracion->fechaModif = date('Y-m-d H:i:s');

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaccion);
        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

    }


    /* Crea un objeto UsuarioMandante y asigna propiedades desde otro objeto Usuario. */
    $UsuarioMandante = new UsuarioMandante();

    $UsuarioMandante->mandante = $Usuario->mandante;

    $UsuarioMandante->nombres = $Usuario->nombre;
    $UsuarioMandante->apellidos = $Usuario->nombre;

    /* Asigna propiedades del objeto $UsuarioMandante basadas en el objeto $Usuario. */
    $UsuarioMandante->estado = 'A';
    $UsuarioMandante->email = $Usuario->login;
    $UsuarioMandante->moneda = $Usuario->moneda;
    $UsuarioMandante->paisId = $Usuario->paisId;
    $UsuarioMandante->saldo = 0;
    $UsuarioMandante->usuarioMandante = $consecutivo_usuario;

    /* Se crea un objeto de usuario y se inserta en la base de datos. */
    $UsuarioMandante->usucreaId = 0;
    $UsuarioMandante->usumodifId = 0;
    $UsuarioMandante->propio = 'S';

    $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaccion);
    $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);


    /* Inserta un registro de landing en la base de datos si no está vacío. */
    if ($landing != '') {
        $sitioTracking2 = new SitioTracking();
        $sitioTracking2->setTabla('registro_landing');
        $sitioTracking2->setTablaId($consecutivo_usuario);
        $sitioTracking2->setTvalue($landing);
        $sitioTracking2->setUsucreaId($consecutivo_usuario);
        $sitioTracking2->setTipo(9);

        $SitioTrackingMySqlDAO2 = new SitioTrackingMySqlDAO($Transaction);
        $SitioTrackingMySqlDAO2->insert($sitioTracking2);
    }


    /* confirma una transacción y cambia la contraseña del usuario. */
    $Transaccion->commit();

    $Usuario->changeClave($password);

    if ($site_id == 21) { // este condicional es para realizar el registro a cammanbet

        /* intercambia valores de identificación según condiciones de residencia. */
        $IdFirstUser = $Usuario->usuarioId;

        if ($countryResident_id->Id == 243) {
            $countryResident_id->Id = 232;
        } else if ($countryResident_id->Id == 232) {
            $countryResident_id->Id = 243;
        }

        /* Se crea un objeto de país y se asignan valores a usuario basado en datos proporcionados. */
        $PaisMandante = new PaisMandante('', $site_id, $countryResident_id->Id);

        $moneda_default = $PaisMandante->moneda;

        //$Usuario->usuarioId = $consecutivo_usuario;

        $Usuario->login = $email;


        /* Código asigna valores a propiedades de un objeto Usuario, estableciendo atributos específicos. */
        $Usuario->nombre = $nombre;

        $Usuario->estado = $estadoUsuarioDefault;

        $Usuario->fechaUlt = date('Y-m-d H:i:s');

        $Usuario->claveTv = '';


        /* Inicializa propiedades de un objeto Usuario: estado, intentos, estado especial y observaciones. */
        $Usuario->estadoAnt = 'I';

        $Usuario->intentos = 0;

        $Usuario->estadoEsp = $estadoUsuarioDefault;

        $Usuario->observ = '';


        /* Se están asignando propiedades a un objeto Usuario con información específica. */
        $Usuario->dirIp = $dir_ip;

        $Usuario->eliminado = 'N';

        $Usuario->mandante = $Mandante->mandante;

        $Usuario->usucreaId = '0';


        /* Asignación de propiedades a un objeto usuario y generación de un token de entretenimiento. */
        $Usuario->usumodifId = '0';

        $Usuario->claveCasino = '';
        $token_itainment = GenerarClaveTicket22(12);

        $Usuario->tokenItainment = $token_itainment;


        /* Inicializa propiedades de un objeto Usuario relacionadas con fecha y estado de retiro. */
        $Usuario->fechaClave = '';

        $Usuario->retirado = 'N';

        $Usuario->fechaRetiro = '';

        $Usuario->horaRetiro = '';


        /* Código asigna valores a propiedades de un objeto 'Usuario', configurando su estado y restricciones. */
        $Usuario->usuretiroId = '0';

        $Usuario->bloqueoVentas = 'N';

        $Usuario->infoEquipo = '';

        $Usuario->estadoJugador = 'NN';


        /* Se asignan valores iniciales a propiedades del objeto $Usuario. */
        $Usuario->tokenCasino = '';

        $Usuario->sponsorId = 0;

        $Usuario->verifCorreo = 'N';

        $Usuario->paisId = $countryResident_id->Id;


        /* Se asignan valores predeterminados a propiedades del objeto Usuario. */
        $Usuario->moneda = $moneda_default;

        $Usuario->idioma = $idioma;

        $Usuario->permiteActivareg = 'N';

        $Usuario->test = 'N';


        /* Se definen propiedades del usuario relacionadas con tiempo, cambios y zona horaria. */
        $Usuario->tiempoLimitedeposito = 0;

        $Usuario->tiempoAutoexclusion = 0;

        $Usuario->cambiosAprobacion = 'S';

        $Usuario->timezone = '-5';


        /* asigna valores iniciales a múltiples propiedades del objeto `$Usuario`. */
        $Usuario->puntoventaId = 0;
        $Usuario->usucreaId = 0;
        $Usuario->usumodifId = 0;
        $Usuario->usuretiroId = 0;
        $Usuario->sponsorId = (0);

        $Usuario->puntoventaId = 0;


        /* establece fechas y propiedades para un objeto de usuario en PHP. */
        $Usuario->fechaCrea = date('Y-m-d H:i:s');

        $Usuario->origen = $origen;

        $Usuario->fechaActualizacion = $Usuario->fechaCrea;
        $Usuario->documentoValidado = "I";

        /* asigna valores iniciales a propiedades de un objeto Usuario. */
        $Usuario->fechaDocvalido = $Usuario->fechaCrea;
        $Usuario->usuDocvalido = 0;


        $Usuario->estadoValida = 'N';
        $Usuario->usuvalidaId = 0;

        /* Se establecen fechas y contingencias para el usuario en un formato específico. */
        $Usuario->fechaValida = date('Y-m-d H:i:s');
        $Usuario->contingencia = 'I';
        $Usuario->contingenciaDeportes = 'I';
        $Usuario->contingenciaCasino = 'I';
        $Usuario->contingenciaCasvivo = 'I';
        $Usuario->contingenciaVirtuales = 'I';

        /* Se definen propiedades de un objeto Usuario, con valores predeterminados y vacíos. */
        $Usuario->contingenciaPoker = 'I';
        $Usuario->restriccionIp = 'I';
        $Usuario->ubicacionLongitud = '';
        $Usuario->ubicacionLatitud = '';
        $Usuario->usuarioIp = '';
        $Usuario->tokenGoogle = "I";

        /* Se asignan valores a propiedades de un objeto "Usuario" en PHP. */
        $Usuario->tokenLocal = "I";
        $Usuario->saltGoogle = '';

        $Usuario->skype = '';
        $Usuario->plataforma = 0;


        $Usuario->fechaActualizacion = $Usuario->fechaCrea;

        /* asigna valores a propiedades de un objeto `$Usuario` basado en condiciones. */
        $Usuario->documentoValidado = "I";
        $Usuario->fechaDocvalido = '1970-01-01 00:00:00';
        $Usuario->usuDocvalido = 0;
        $Usuario->equipoId = intval($team);

        if ($Mandante->mandante == 14) {
            $Usuario->verifCelular = 'S';
            $Usuario->fechaVerifCelular = date('Y-m-d H:i:s');
        }


        /* Es un código que asigna un valor a "contingenciaRetiro" basado en condiciones específicas. */
        if ($Usuario->mandante == 14 && date('Y-m-d H:i:s') >= '2023-05-27 00:00:00') {
            $Usuario->contingenciaRetiro = 'A';
        }

        if ($Usuario->mandante == 0 && $Usuario->paisId == 46 && date('Y-m-d H:i:s') >= '2023-05-29 00:00:00' && date('Y-m-d H:i:s') <= '2023-05-31 23:59:59') {
            $Usuario->contingenciaRetiro = 'A';
        }


        if ($Usuario->mandante == 0 && $Usuario->paisId == 2
            && date('Y-m-d H:i:s') >= '2023-04-01 00:00:00'
            && date('Y-m-d H:i:s') <= '2023-04-02 23:59:59'
        ) {
            $Usuario->contingenciaRetiro = 'A';
        }


        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
        //$UsuarioMySqlDAO = new UsuarioMySqlDAO();


        /* verifica un valor y luego inserta un objeto Usuario en la base de datos. */
        if ($Mandante->mandante == 2) {
            //$Usuario->verifcedulaAnt='S';
            //$Usuario->verifcedulaPost='S';
        }


        $UsuarioMySqlDAO->insert($Usuario);


        /* registra un usuario en un sistema de seguimiento si se proporciona un trackerId. */
        $consecutivo_usuario = $Usuario->usuarioId;

        if ($trackerId != '' && $trackerId != null) {

            $sitioTracking = new SitioTracking();
            $sitioTracking->setTabla('Register_user');
            $sitioTracking->setTablaId($consecutivo_usuario);
            $sitioTracking->setTvalue($trackerId);
            $sitioTracking->setUsucreaId($consecutivo_usuario);
            $sitioTracking->setTipo(2);

            $SitioTrackingMySqlDAO = new SitioTrackingMySqlDAO($Transaction);
            $SitioTrackingMySqlDAO->insert($sitioTracking);

        }


        /* Inserta un registro en la tabla "registro_landing" si $landing no está vacío. */
        if ($landing != '') {
            $sitioTracking2 = new SitioTracking();
            $sitioTracking2->setTabla('registro_landing');
            $sitioTracking2->setTablaId($consecutivo_usuario);
            $sitioTracking2->setTvalue($landing);
            $sitioTracking2->setUsucreaId($consecutivo_usuario);
            $sitioTracking2->setTipo(9);

            $SitioTrackingMySqlDAO2 = new SitioTrackingMySqlDAO($Transaction);
            $SitioTrackingMySqlDAO2->insert($sitioTracking2);
        }

        //$UsuarioMySqlDAO->getTransaction()->commit();


        /* Código establece propiedades de un objeto Registro con datos del usuario. */
        $Registro->setNombre($nombre);
        $Registro->setEmail($email);
        $Registro->setClaveActiva($clave_activa);
        $Registro->setEstado($estadoUsuarioDefault);
        $Registro->usuarioId = $consecutivo_usuario;
        $Registro->setCelular($phone);

        /* establece valores iniciales para créditos y asigna un ID de ciudad. */
        $Registro->setCreditosBase(0);
        $Registro->setCreditos(0);
        $Registro->setCreditosAnt(0);
        $Registro->setCreditosBaseAnt(0);
        //$Registro->setCiudadId($department_id->cities[0]->id);
        $Registro->setCiudadId($city_id->Id);

        /* establece valores en un objeto Registro y depura el nombre. */
        $Registro->setCasino(0);
        $Registro->setCasinoBase(0);
        $Registro->setMandante($Mandante->mandante);
        $first_name = $ConfigurationEnvironment->DepurarCaracteres($first_name);
        $first_name = mb_substr($first_name, 0, 19);

        $Registro->setNombre1($first_name);


        /* depura y limita la longitud del segundo nombre y apellido. */
        $middle_name = $ConfigurationEnvironment->DepurarCaracteres($middle_name);

        $middle_name = mb_substr($middle_name, 0, 19);

        $Registro->setNombre2($middle_name);

        $last_name = $ConfigurationEnvironment->DepurarCaracteres($last_name);


        /* Se limitan y depuran los apellidos a 19 caracteres para su almacenamiento. */
        $last_name = mb_substr($last_name, 0, 19);
        $Registro->setApellido1($last_name);

        $second_last_name = $ConfigurationEnvironment->DepurarCaracteres($second_last_name);

        $second_last_name = mb_substr($second_last_name, 0, 19);

        /* configura atributos de un objeto de registro personal. */
        $Registro->setApellido2($second_last_name);

        $Registro->setSexo($gender);
        $Registro->setTipoDoc($doctype_id);
        $Registro->setDireccion($address);
        $Registro->setTelefono($landline_number);

        /* asigna valores a diferentes propiedades de un objeto "Registro". */
        $Registro->setCiudnacimId($ciudad_nacimiento);
        $Registro->setNacionalidadId($nationality_id->Id);
        $Registro->setDirIp($dir_ip);
        $Registro->setOcupacionId($ocupacion);
        $Registro->setRangoingresoId($rangoingreso_id);
        $Registro->setOrigenfondosId($origen_fondos);

        /* Asigna valores a propiedades de un objeto Registro en PHP. */
        $Registro->setOrigenFondos($origen_fondosString);
        $Registro->setPaisnacimId($countrybirth_id->Id);
        $Registro->setPuntoVentaId(0);
        $Registro->setPreregistroId(0);
        $Registro->setCreditosBono(0);
        $Registro->setCreditosBonoAnt(0);

        /* Código configura un registro estableciendo varios atributos, como IDs y fecha. */
        $Registro->setPreregistroId(0);
        $Registro->setUsuvalidaId(0);
        $Registro->setFechaValida($fecha_actual);
        $Registro->setCodigoPostal($cp);

        $Registro->setCiudexpedId($expcity_id->Id);


        /* establece una fecha de expedición, basándose en la fecha indicada. */
        if ($expedition_date != '') {
            $Registro->setFechaExped($expedition_date);

        } else {
            $Registro->setFechaExped($expedition_year . "-" . $expedition_month . "-" . $expedition_day);

        }

        /* establece un ID de punto de venta y valida el estado basado en condiciones. */
        $Registro->setPuntoventaId(0);

        $EstadoValidaRegistro = 'I';

        if ($Mandante->mandante == '13') {
            $EstadoValidaRegistro = 'A';
        }


        /* Configura el estado de un registro y verifica si afiliador es un entero. */
        $Registro->setEstadoValida($EstadoValidaRegistro);

        if (!is_int($afiliador)) {
            // $afiliador = 0;

        }

        /* Verifica si $bannerid y $linkid son enteros; si no, se comenta asignación. */
        if (!is_int($bannerid)) {

            // $bannerid = 0;
        }
        if (!is_int($linkid)) {

            //$linkid = '0';
        }

        /* verifica si "codigoBD" es un entero y ajusta "linkid" si es vacío. */
        if (!is_int($codigoBD)) {

            //$codigoBD = 0;
        }
        if (intval($linkid) == '') {
            $linkid = '0';
        }


        /* establece valores predeterminados para variables si están vacías. */
        if (intval($bannerid) == '') {
            $bannerid = '0';
        }

        if ($afiliadorGlobal == '') {
            $afiliadorGlobal = '0';
        }


        /* Verifica el afiliador y restablece valores si la moneda no coincide o hay error. */
        if ($afiliador != '0') {
            try {


                $UsuarioAfiliador = new Usuario($afiliadorGlobal);
                if ($UsuarioAfiliador->moneda != $moneda_default) {
                    $afiliador = '0';
                    $linkid = '0';
                    $bannerid = '0';
                }else{
                    $afiliador = $afiliadorGlobal;
                }
            } catch (Exception $e) {
                $afiliador = '0';
                $linkid = '0';
                $bannerid = '0';
            }
        }


        /* asigna valores a un objeto y lo inserta en la base de datos. */
        $Registro->setAfiliadorId($afiliador);
        $Registro->setBannerId($bannerid);
        $Registro->setLinkId($linkid);
        $Registro->setCodpromocionalId($codigoBD);


        $RegistroMySqlDAO->insert($Registro);


        /* gestiona un programa de referidos y registra logros de usuarios referidos. */
        $Transaccion = $RegistroMySqlDAO->getTransaction();
        $usuidReferente = '0';

        $UsuarioOtrainfo = new UsuarioOtrainfo();

        try {
            /** Disponibilidad e implementación programa de referidos */
            if (!empty($referentLink)) {
                //Validando programa y compatibilidad
                $PaisMandante->progReferidosDisponible();
                $usuidReferente = $PaisMandante->validarLinkReferenteCompatible($Usuario, $referentLink);
                //Insertando logros que debe cumplir el referido
                $LogroReferido = new LogroReferido();
                $UsuarioReferente = new Usuario($usuidReferente);
                $LogroReferido->insertarLogrosNuevoReferido($Transaccion, $UsuarioReferente, $Usuario);
            }
        } catch (Exception $e) {
            /* maneja excepciones, asignando '0' a $usuidReferente en caso de error. */

            $usuidReferente = '0';
        }


        /* Asigna información del usuario a un objeto, incluyendo ID y datos personales. */
        $UsuarioOtrainfo->usuarioId = $consecutivo_usuario;
        $UsuarioOtrainfo->fechaNacim = $birth_date;
        $UsuarioOtrainfo->mandante = $Mandante->mandante;
        $UsuarioOtrainfo->info2 = $docnumber2;
        $UsuarioOtrainfo->bancoId = '0';
        $UsuarioOtrainfo->numCuenta = '0';

        /* asigna valores a propiedades de un objeto y crea una instancia DAO. */
        $UsuarioOtrainfo->anexoDoc = 'N';
        $UsuarioOtrainfo->direccion = $address;
        $UsuarioOtrainfo->tipoCuenta = '0';
        $UsuarioOtrainfo->usuidReferente = $usuidReferente;


        $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaccion);
        //$UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();


        /* Se inserta un nuevo usuario y se configura su perfil en la base de datos. */
        $UsuarioOtrainfoMySqlDAO->insert($UsuarioOtrainfo);
        //$UsuarioOtrainfoMySqlDAO->getTransaction()->commit();

        $UsuarioPerfil = new UsuarioPerfil();

        $UsuarioPerfil->setUsuarioId($consecutivo_usuario);

        /* Se crea un objeto UsuarioPerfil y se configura con parámetros específicos. */
        $UsuarioPerfil->setPerfilId('USUONLINE');
        $UsuarioPerfil->setMandante($Mandante->mandante);
        $UsuarioPerfil->setPais('N');
        $UsuarioPerfil->setGlobal('N');


        $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaccion);
        //$UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();

        /* Inserta un perfil de usuario y crea un objeto de premio máximo inicializado a cero. */
        $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);
        //$UsuarioPerfilMySqlDAO->getTransaction()->commit();

        $UsuarioPremiomax = new UsuarioPremiomax();

        $premio_max1 = 0;

        /* Variables inicializan premios, apuesta mínima, líneas y valores relacionados en un juego. */
        $premio_max2 = 0;
        $premio_max3 = 0;
        $apuesta_min = 0;
        $cant_lineas = 0;
        $valor_directo = 0;
        $valor_evento = 0;

        /* Inicializa variables y asigna valores a propiedades de un objeto en PHP. */
        $valor_diario = 0;

        $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

        $UsuarioPremiomax->premioMax = $premio_max1;

        $UsuarioPremiomax->usumodifId = '0';


        /* Asigna valores a propiedades del objeto UsuarioPremiomax. */
        $UsuarioPremiomax->cantLineas = $cant_lineas;

        $UsuarioPremiomax->premioMax1 = $premio_max1;

        $UsuarioPremiomax->premioMax2 = $premio_max2;

        $UsuarioPremiomax->premioMax3 = $premio_max3;


        /* Asignación de valores a atributos de un objeto UsuarioPremiomax. */
        $UsuarioPremiomax->apuestaMin = $apuesta_min;

        $UsuarioPremiomax->valorDirecto = $valor_directo;
        $UsuarioPremiomax->premioDirecto = $valor_directo;


        $UsuarioPremiomax->mandante = $Mandante->mandante;

        /* Se asignan valores a las propiedades de un objeto UsuarioPremiomax. */
        $UsuarioPremiomax->optimizarParrilla = 'N';


        $UsuarioPremiomax->valorEvento = $valor_evento;

        $UsuarioPremiomax->valorDiario = $valor_diario;


        /* Se crea un objeto DAO y se inserta un registro de usuario en la base de datos. */
        $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($Transaccion);
        //$UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO();
        $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);
        //$UsuarioPremiomaxMySqlDAO->getTransaction()->commit();


        if ($Mandante->mandante == 2 || $Mandante->mandante == 1) {

            /* Asignación de variables para almacenar información de usuario y sus archivos. */
            $ClientId = $consecutivo_usuario;


            $file = $user_info->file;
            $file2 = $user_info->file2;
            $file3 = $user_info->file3;

            /* procesa un archivo Base64, decodificándolo y preparándolo para ser almacenado. */
            $file4 = $user_info->file4;
            $type = $user_info->type;
            $tipo = 'USUDNIANTERIOR';


            if ($file != "" && $file != "undefined") {
                $file = str_replace(" ", "+", $file);
                $type = 'A';
                $tipo = 'USUDNIANTERIOR';

                $pos = strpos($file, 'base64,');
                $file_contents1 = base64_decode(mb_substr($file, $pos + 7));
                $file_contents1 = addslashes($file_contents1);

                /*$name = $consecutivo_usuario . time() . $type . ".png";
                $data1 = $ConfigurationEnvironment->base64ToImage($file, $name);
                $data1 = file_get_contents($name);

                $file_contents1 = addslashes($data1);

                unlink($name);*/

            }

            /* verifica un archivo y decodifica su contenido en base64. */
            if ($file2 != "" && $file2 != "undefined") {
                $file2 = str_replace(" ", "+", $file2);
                $type = 'P';
                $tipo = 'USUDNIPOSTERIOR';

                $pos = strpos($file2, 'base64,');
                $file_contents2 = base64_decode(mb_substr($file2, $pos + 7));
                $file_contents2 = addslashes($file_contents2);
                /*
                $name = $consecutivo_usuario . time() . $type . ".png";
                $data1 = $ConfigurationEnvironment->base64ToImage($file2, $name);
                $data1 = file_get_contents($name);

                $file_contents2 = addslashes($data1);

                unlink($name);*/

            }


            /* Decodifica un archivo base64 y escapa sus caracteres especiales si está definido. */
            if ($file3 != "" && $file3 != "undefined") {
                $type = 'A';
                $tipo = 'USUTRNANTERIOR';

                $pos = strpos($file3, 'base64,');
                $file_contents3 = base64_decode(mb_substr($file3, $pos + 7));
                $file_contents3 = addslashes($file_contents3);

            }

            /* verifica y decodifica un archivo en base64, preparándolo para su uso. */
            if ($file4 != "" && $file4 != "undefined") {
                $type = 'P';
                $tipo = 'USUTRNPOSTERIOR';

                $pos = strpos($file4, 'base64,');
                $file_contents4 = base64_decode(mb_substr($file4, $pos + 7));
                $file_contents4 = addslashes($file_contents4);

            }

            if ($file_contents1 != '') {

                /* Se asigna el valor 'P' a la variable $estadoLog. */
                $estadoLog = 'P';

                if ($Mandante->mandante == 2) {
                    //$estadoLog='A';
                    // $Usuario->verifcedulaAnt='S';

                    /* try{
                         $filename = "c" . $Usuario->usuarioId;
                         $filename = $filename . 'A';
                         $filename = $filename . '.png';
                         $bucketName = 'cedulas-1';
                         $objectName = 'c/'.$filename;
     // Authenticate your API Client
                         $client = new Google_Client();
                         $client->setAuthConfig('/etc/private/virtual.json');
                         $client->useApplicationDefaultCredentials();
                         $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

                         $storage = new Google_Service_Storage($client);

                         $file_name = 'c/'.$filename;

                         $dirsave =  $filename;
                         $file = fopen($dirsave, "wb");

                         fwrite($file, base64_decode($file_contents1));
                         fclose($file);

                         $obj = new Google_Service_Storage_StorageObject();
                         $obj->setName($file_name);
                         $obj->setMetadata(['contentType' => 'image/jpeg']);

                         $storage->objects->insert(
                             $bucketName,
                             $obj,
                             ['mimeType' => 'image/jpeg','name' => $file_name, 'data' => file_get_contents($dirsave), 'uploadType' => 'media']
                         );

                         unlink($dirsave);
                     }catch (Exception $e){

                     }*/

                }


                /* Se registra un log de usuario con ID y dirección IP especificados. */
                $UsuarioLog = new UsuarioLog2();
                $UsuarioLog->setUsuarioId($consecutivo_usuario);
                $UsuarioLog->setUsuarioIp($dir_ip);
                $UsuarioLog->setUsuariosolicitaId($consecutivo_usuario);
                $UsuarioLog->setUsuariosolicitaIp($dir_ip);
                $UsuarioLog->setTipo("USUDNIANTERIOR");

                /* configura propiedades de un objeto UsuarioLog para registrar cambios. */
                $UsuarioLog->setEstado($estadoLog);
                $UsuarioLog->setValorAntes("");
                $UsuarioLog->setValorDespues("");
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setImagen($file_contents1);

                /* Se crea un DAO para insertar un registro de usuario en la base de datos. */
                $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
                $UsuarioLogMySqlDAO->insert($UsuarioLog);

            }
            if ($file_contents2 != '') {

                /* Se asigna el valor 'P' a la variable $estadoLog, posiblemente representando un estado. */
                $estadoLog = 'P';

                if ($Mandante->mandante == 2) {
                    //$estadoLog='A';
                    //$Usuario->verifcedulaPost='S';

                    /*try{
                        $filename = "c" . $Usuario->usuarioId;
                        $filename = $filename . 'P';
                        $filename = $filename . '.png';
                        $bucketName = 'cedulas-1';
                        $objectName = 'c/'.$filename;
    // Authenticate your API Client
                        $client = new Google_Client();
                        $client->setAuthConfig('/etc/private/virtual.json');
                        $client->useApplicationDefaultCredentials();
                        $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

                        $storage = new Google_Service_Storage($client);

                        $file_name = 'c/'.$filename;

                        $dirsave =  $filename;
                        $file = fopen($dirsave, "wb");

                        fwrite($file, base64_decode($file_contents2));
                        fclose($file);

                        $obj = new Google_Service_Storage_StorageObject();
                        $obj->setName($file_name);
                        $obj->setMetadata(['contentType' => 'image/jpeg']);

                        $storage->objects->insert(
                            $bucketName,
                            $obj,
                            ['mimeType' => 'image/jpeg','name' => $file_name, 'data' => file_get_contents($dirsave), 'uploadType' => 'media']
                        );

                        unlink($dirsave);
                    }catch (Exception $e){

                    }*/
                }


                /* Registro de usuario y su información en el sistema para posteriormente ser usado. */
                $UsuarioLog = new UsuarioLog2();
                $UsuarioLog->setUsuarioId($consecutivo_usuario);
                $UsuarioLog->setUsuarioIp($dir_ip);
                $UsuarioLog->setUsuariosolicitaId($consecutivo_usuario);
                $UsuarioLog->setUsuariosolicitaIp($dir_ip);
                $UsuarioLog->setTipo("USUDNIPOSTERIOR");

                /* Configura el estado y propiedades del objeto UsuarioLog para registrar cambios. */
                $UsuarioLog->setEstado($estadoLog);
                $UsuarioLog->setValorAntes("");
                $UsuarioLog->setValorDespues("");
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setImagen($file_contents2);

                /* Se crea un DAO para insertar un registro de log de usuario en MySQL. */
                $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
                $UsuarioLogMySqlDAO->insert($UsuarioLog);

            }
            if ($file_contents3 != '') {

                /* Se establece una variable llamada $estadoLog con el valor 'P'. */
                $estadoLog = 'P';

                if ($Mandante->mandante == 2) {
                    //$estadoLog='A';
                    //$Usuario->verifcedulaAnt='S';

                    try {

                        /* Código para generar un nombre de archivo PNG y autenticar cliente de Google. */
                        $filename = "trn" . $Usuario->usuarioId;
                        $filename = $filename . 'A';
                        $filename = $filename . '.png';
                        $bucketName = 'cedulas-1';
                        $objectName = 'c/' . $filename;
// Authenticate your API Client
                        $client = new Google_Client();

                        /* Configura un cliente para Google Cloud Storage usando autenticación y permisos completos. */
                        $client->setAuthConfig('/etc/private/virtual.json');
                        $client->useApplicationDefaultCredentials();
                        $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

                        $storage = new Google_Service_Storage($client);

                        $file_name = 'c/' . $filename;


                        /* guarda contenido decodificado en un archivo temporal para Google Storage. */
                        $dirsave = '/tmp/' . $filename;
                        $file = fopen($dirsave, "wb");

                        fwrite($file, base64_decode($file_contents3));
                        fclose($file);

                        $obj = new Google_Service_Storage_StorageObject();

                        /* Se sube una imagen JPEG a un bucket utilizando Google Cloud Storage. */
                        $obj->setName($file_name);
                        $obj->setMetadata(['contentType' => 'image/jpeg']);

                        $storage->objects->insert(
                            $bucketName,
                            $obj,
                            ['mimeType' => 'image/jpeg', 'name' => $file_name, 'data' => file_get_contents($dirsave), 'uploadType' => 'media']
                        );


                        /* elimina el directorio especificado en la variable $dirsave. */
                        unlink($dirsave);
                    } catch (Exception $e) {
                        /* Manejo de excepciones en PHP para capturar errores sin realizar ninguna acción. */


                    }

                }


                /* Registro de información del usuario y su IP para auditoría en el sistema. */
                $UsuarioLog = new UsuarioLog2();
                $UsuarioLog->setUsuarioId($consecutivo_usuario);
                $UsuarioLog->setUsuarioIp($dir_ip);
                $UsuarioLog->setUsuariosolicitaId($consecutivo_usuario);
                $UsuarioLog->setUsuariosolicitaIp($dir_ip);
                $UsuarioLog->setTipo("USUTRNANTERIOR");

                /* Se configuran propiedades de un registro de usuario en un log. */
                $UsuarioLog->setEstado($estadoLog);
                $UsuarioLog->setValorAntes("");
                $UsuarioLog->setValorDespues("");
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setImagen($file_contents3);

                /* Se crea un DAO y se inserta un registro de usuario en la base de datos. */
                $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
                $UsuarioLogMySqlDAO->insert($UsuarioLog);

            }
            if ($file_contents4 != '') {

                /* Se asigna el valor 'P' a la variable $estadoLog. */
                $estadoLog = 'P';
                if ($Mandante->mandante == 2) {
                    //$estadoLog='A';
                    //$Usuario->verifcedulaAnt='S';

                    try {

                        /* Genera un nombre de archivo PNG basado en el ID de usuario para Google Cloud Storage. */
                        $filename = "trn" . $Usuario->usuarioId;
                        $filename = $filename . 'P';
                        $filename = $filename . '.png';
                        $bucketName = 'cedulas-1';
                        $objectName = 'c/' . $filename;
// Authenticate your API Client
                        $client = new Google_Client();

                        /* Configura Google Cloud Storage usando credenciales y permisos específicos para acceso completo. */
                        $client->setAuthConfig('/etc/private/virtual.json');
                        $client->useApplicationDefaultCredentials();
                        $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

                        $storage = new Google_Service_Storage($client);

                        $file_name = 'c/' . $filename;


                        /* guarda un archivo decodificado en el directorio temporal del servidor. */
                        $dirsave = '/tmp/' . $filename;
                        $file = fopen($dirsave, "wb");

                        fwrite($file, base64_decode($file_contents4));
                        fclose($file);

                        $obj = new Google_Service_Storage_StorageObject();

                        /* Se establece un objeto y se inserta una imagen en un almacenamiento en la nube. */
                        $obj->setName($file_name);
                        $obj->setMetadata(['contentType' => 'image/jpeg']);

                        $storage->objects->insert(
                            $bucketName,
                            $obj,
                            ['mimeType' => 'image/jpeg', 'name' => $file_name, 'data' => file_get_contents($dirsave), 'uploadType' => 'media']
                        );


                        /* elimina un directorio o archivo especificado por la variable $dirsave. */
                        unlink($dirsave);
                    } catch (Exception $e) {
                        /* captura excepciones en PHP sin realizar ninguna acción específica. */


                    }

                }


                /* Instancia y configura un objeto UsuarioLog2 con información del usuario y su IP. */
                $UsuarioLog = new UsuarioLog2();
                $UsuarioLog->setUsuarioId($consecutivo_usuario);
                $UsuarioLog->setUsuarioIp($dir_ip);
                $UsuarioLog->setUsuariosolicitaId($consecutivo_usuario);
                $UsuarioLog->setUsuariosolicitaIp($dir_ip);
                $UsuarioLog->setTipo("USUTRNPOSTERIOR");

                /* Código para establecer propiedades del objeto UsuarioLog, incluyendo estado e imágenes. */
                $UsuarioLog->setEstado($estadoLog);
                $UsuarioLog->setValorAntes("");
                $UsuarioLog->setValorDespues("");
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setImagen($file_contents4);

                /* Se crea un objeto para manejar registros de usuario y se inserta un log. */
                $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
                $UsuarioLogMySqlDAO->insert($UsuarioLog);

            }
        }


        /* Condicional que configura límites de depósito diario para un usuario específico. */
        if ($Mandante->mandante == 2) {
            $Clasificador = new Clasificador("", "LIMITEDEPOSITODIARIO");
            $tipo = $Clasificador->getClasificadorId();

            $UsuarioConfiguracion2 = new UsuarioConfiguracion();
            $UsuarioConfiguracion2->setUsuarioId($Usuario->usuarioId);
            $UsuarioConfiguracion2->setTipo($tipo);
            $UsuarioConfiguracion2->setValor('40000');
            $UsuarioConfiguracion2->setUsucreaId("0");
            $UsuarioConfiguracion2->setUsumodifId("0");
            $UsuarioConfiguracion2->setProductoId(0);
            $UsuarioConfiguracion2->setEstado("A");
            $UsuarioConfiguracion2->fechaModif = date("Y-m-d 00:00:00");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaccion);
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);

        }
        if ($limit_deposit_day != 0 && $limit_deposit_day != '') {

            /* Código para configurar y registrar límites de depósito diario en un sistema de usuario. */
            $ClientId = $consecutivo_usuario;

            $Clasificador = new Clasificador("", "LIMITEDEPOSITODIARIO");
            $tipo = $Clasificador->getClasificadorId();

            /*$UsuarioConfiguracion2 = new UsuarioConfiguracion();
            $UsuarioConfiguracion2->setUsuarioId($ClientId);
            $UsuarioConfiguracion2->setTipo($tipo);
            $UsuarioConfiguracion2->setValor($limit_deposit_day);
            $UsuarioConfiguracion2->setUsucreaId("0");
            $UsuarioConfiguracion2->setUsumodifId("0");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);*/

            $UsuarioLog = new UsuarioLog2();

            /* Registro de información del usuario, incluyendo ID, IP y estado de solicitud. */
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($dir_ip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($dir_ip);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("P");

            /* Se registra un cambio en usuario, registrando valores antes y después en la base. */
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues($limit_deposit_day);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

        }
        if ($limit_deposit_week != 0 && $limit_deposit_week != '') {

            /* configura un usuario y establece su límite de depósitos semanales. */
            $ClientId = $consecutivo_usuario;

            $Clasificador = new Clasificador("", "LIMITEDEPOSITOSEMANA");
            $tipo = $Clasificador->getClasificadorId();

            /*$UsuarioConfiguracion2 = new UsuarioConfiguracion();
            $UsuarioConfiguracion2->setUsuarioId($ClientId);
            $UsuarioConfiguracion2->setTipo($tipo);
            $UsuarioConfiguracion2->setValor($limit_deposit_week);
            $UsuarioConfiguracion2->setUsucreaId("0");
            $UsuarioConfiguracion2->setUsumodifId("0");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);*/

            $UsuarioLog = new UsuarioLog2();

            /* Configuración de un registro de usuario con identificador, IP, tipo y estado. */
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($dir_ip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($dir_ip);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("P");

            /* Registro de cambios de usuario en base de datos, incluyendo valores antes y después. */
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues($limit_deposit_week);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);


        }


        if ($limit_deposit_month != 0 && $limit_deposit_month != '') {

            /* crea un clasificador y prepara la configuración del usuario para insertar en la base de datos. */
            $ClientId = $consecutivo_usuario;

            $Clasificador = new Clasificador("", "LIMITEDEPOSITOMENSUAL");
            $tipo = $Clasificador->getClasificadorId();

            /*$UsuarioConfiguracion2 = new UsuarioConfiguracion();
            $UsuarioConfiguracion2->setUsuarioId($ClientId);
            $UsuarioConfiguracion2->setTipo($tipo);
            $UsuarioConfiguracion2->setValor($limit_deposit_month);
            $UsuarioConfiguracion2->setUsucreaId("0");
            $UsuarioConfiguracion2->setUsumodifId("0");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);*/

            $UsuarioLog = new UsuarioLog2();

            /* Se establecen propiedades de un objeto UsuarioLog con información del cliente. */
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($dir_ip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($dir_ip);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("P");

            /* registra un cambio en los valores de un usuario en la base de datos. */
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues($limit_deposit_month);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaccion);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);


        }

        if ($personPoliticallyExposed != '') {


            /* verifica si una persona es políticamente expuesta y clasifica accordingly. */
            if ($personPoliticallyExposed == "true") {
                $personPoliticallyExposed = "S";
            } else {
                $personPoliticallyExposed = "N";
            }

            $Clasificador = new Clasificador("", "PEP");


            /* Se crea un objeto UsuarioConfiguracion con datos del usuario y clasificador. */
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->usuarioId = $consecutivo_usuario;
            $UsuarioConfiguracion->tipo = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion->valor = $personPoliticallyExposed;
            $UsuarioConfiguracion->usucreaId = $consecutivo_usuario;
            $UsuarioConfiguracion->usumodifId = 0;

            /* Se configura un usuario y se inserta en la base de datos MySQL. */
            $UsuarioConfiguracion->productoId = 0;
            $UsuarioConfiguracion->estado = 'A';
            $UsuarioConfiguracion->fechaModif = date('Y-m-d H:i:s');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaccion);
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

        }


        /* Crea un objeto UsuarioMandante y asigna atributos desde un objeto Usuario. */
        $UsuarioMandante = new UsuarioMandante();

        $UsuarioMandante->mandante = $Usuario->mandante;

        $UsuarioMandante->nombres = $Usuario->nombre;
        $UsuarioMandante->apellidos = $Usuario->nombre;

        /* Se asignan propiedades a un objeto UsuarioMandante basado en otro objeto Usuario. */
        $UsuarioMandante->estado = 'A';
        $UsuarioMandante->email = $Usuario->login;
        $UsuarioMandante->moneda = $Usuario->moneda;
        $UsuarioMandante->paisId = $Usuario->paisId;
        $UsuarioMandante->saldo = 0;
        $UsuarioMandante->usuarioMandante = $consecutivo_usuario;

        /* inserta un nuevo registro de usuario mandante en la base de datos. */
        $UsuarioMandante->usucreaId = 0;
        $UsuarioMandante->usumodifId = 0;
        $UsuarioMandante->propio = 'S';

        $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaccion);
        $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);


        /* realiza una transacción, cambia la clave de usuario y crea una cuenta asociada. */
        $Transaccion->commit();

        $Usuario->changeClave($password);


        $CuentaAsociada = new CuentaAsociada();

        /* establece IDs de usuario y crea una instancia de CuentaAsociadaMySqlDAO. */
        $CuentaAsociada->setUsuarioId($IdFirstUser);
        $CuentaAsociada->setUsuarioId2($Usuario->usuarioId);
        $CuentaAsociada->SetUsucreaId($IdFirstUser);
        $CuentaAsociada->setUsumodifId(0);

        $CuentaAsociadaMySqlDAO = new CuentaAsociadaMySqlDAO();

        /* Inserta un registro de cuenta asociada y obtiene la transacción correspondiente en MySQL. */
        $CuentaAsociadaMySqlDAO->insert($CuentaAsociada);
        $CuentaAsociadaMySqlDAO->getTransaction()->commit();
    }

    try {
        if ($linkid != '' && $linkid != '0' && $linkid != null) {


            /* Crea un objeto UsuarioMarketing y establece sus propiedades relacionadas con un afiliador. */
            $UsuarioMarketing = new UsuarioMarketing();
            $UsuarioMarketing->setUsuarioId($afiliador);
            $UsuarioMarketing->setUsucreaId($afiliador);
            $UsuarioMarketing->setUsumodifId($afiliador);
            $UsuarioMarketing->setValor(1);
            $UsuarioMarketing->setTipo('REGISTRO');
            $UsuarioMarketing->setExternoId($consecutivo_usuario);
            $UsuarioMarketing->setIp($dir_ip);
            $UsuarioMarketing->setUsuariorefId($consecutivo_usuario);
            $UsuarioMarketing->setLinkId($linkid);
            $UsuarioMarketing->setBannerId(0);

            $UsuarioMarketingMySqlDAO = new UsuarioMarketingMySqlDAO();

            /* Inserta un usuario en la base de datos y gestiona la transacción correspondiente. */
            $UsuarioMarketingMySqlDAO->insert($UsuarioMarketing);
            $UsuarioMarketingMySqlDAO->getTransaction()->commit();


        }
        if ($bannerid != '' && $bannerid != '0' && $bannerid != null) {


            /* Se instancia un objeto UsuarioMarketing y se establecen varios atributos relacionados. */
            $UsuarioMarketing = new UsuarioMarketing();
            $UsuarioMarketing->setUsuarioId($afiliador);
            $UsuarioMarketing->setUsucreaId($afiliador);
            $UsuarioMarketing->setUsumodifId($afiliador);
            $UsuarioMarketing->setValor(1);
            $UsuarioMarketing->setTipo('REGISTRO');
            $UsuarioMarketing->setExternoId($consecutivo_usuario);
            $UsuarioMarketing->setIp($dir_ip);
            $UsuarioMarketing->setUsuariorefId($consecutivo_usuario);
            $UsuarioMarketing->setLinkId(0);
            $UsuarioMarketing->setBannerId($bannerid);

            $UsuarioMarketingMySqlDAO = new UsuarioMarketingMySqlDAO();

            /* Inserta un usuario en la base de datos y maneja la transacción. */
            $UsuarioMarketingMySqlDAO->insert($UsuarioMarketing);
            $UsuarioMarketingMySqlDAO->getTransaction()->commit();


        }
    } catch (Exception $e) {
        /* Bloque que captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


    }


    try {

        if ($json->params->vs_utm_campaign != '' && $json->params->vs_utm_campaign != 'undefined_undefined') {


            try {


                /* Crea un objeto con parámetros UTM extraídos de un JSON. */
                $objectST = new stdClass();
                $objectST->vs_utm_campaign = $json->params->vs_utm_campaign;
                $objectST->vs_utm_campaign2 = $json->params->vs_utm_campaign2;
                $objectST->vs_utm_source = $json->params->vs_utm_source;
                $objectST->vs_utm_content = $json->params->vs_utm_content;
                $objectST->vs_utm_term = $json->params->vs_utm_term;

                /* Se asignan valores y se configura un objeto de seguimiento del sitio. */
                $objectST->vs_utm_medium = $json->params->vs_utm_medium;
                $SitioTracking = new \Backend\dto\SitioTracking();

                $SitioTracking->setTabla('registro');
                $SitioTracking->setTablaId($consecutivo_usuario);
                $SitioTracking->setTipo('2');

                /* Asignación de valores a un objeto y configuración de un DAO en PHP. */
                $SitioTracking->setTvalue(json_encode($objectST));
                $SitioTracking->valueInd = mb_substr($objectST->vs_utm_campaign, 0, 49);
                $SitioTracking->setUsucreaId('0');
                $SitioTracking->setUsumodifId('0');


                $SitioTrackingMySqlDAO = new \Backend\mysql\SitioTrackingMySqlDAO();

                /* Inserts data and retrieves transaction information from the SitioTracking MySQL Data Access Object. */
                $SitioTrackingMySqlDAO->insert($SitioTracking);
                $SitioTrackingMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                /* Bloque que captura excepciones en PHP, permitiendo manejar errores sin detener la ejecución. */


            }

        }
    } catch (Exception $e) {
        /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución del programa. */


    }

    /* Se declara una variable vacía llamada $auttoken en PHP para almacenar un token de autorización. */
    $auttoken = "";
    if ($estadoUsuarioDefault == 'A') {


        try {

            /* Se crea un token de usuario con datos específicos de sesión y proveedor. */
            $UsuarioToken = new UsuarioToken();

            $UsuarioToken->setRequestId($json->session->sid);
            $UsuarioToken->setProveedorId('0');
            $UsuarioToken->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioToken->setToken($UsuarioToken->createToken());


            /* configura valores iniciales para un objeto UsuarioToken y crea un DAO. */
            $UsuarioToken->setCookie('');
            $UsuarioToken->setUsumodifId(0);
            $UsuarioToken->setUsucreaId(0);
            $UsuarioToken->setSaldo(0);


            $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();

            /* Inserta un token de usuario en la base de datos y confirma la transacción. */
            $UsuarioTokenMySqlDAO->insert($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $auttoken = $UsuarioToken->getToken();

        } catch (Exception $e) {
            /* Bloque que maneja excepciones en código PHP, evitando que se detenga por errores. */


        }
    }

    /* Redirige a '/new-casino' si la referencia incluye "acropolis"; de lo contrario, a '/home'. */
    $redirectUrl = '/home';

    if (strpos($_SERVER['HTTP_REFERER'], "acropolis") !== FALSE) {
        $redirectUrl = '/new-casino';
    }


    if ($Usuario->mandante == 0 && $Usuario->paisId == 173) {
        try {
            //Recuperando información del solicitante

            /* obtiene la plataforma y la IP del usuario para un objeto de descarga. */
            $plaform = strval($_SERVER['HTTP_SEC_CH_UA_PLATFORM']);
            $plaform = str_replace('"', "", $plaform);
            $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $ip = explode(",", $ip)[0];

            $Descarga = new \Backend\mysql\DescargaMySqlDAO();


            /* Consulta SQL que obtiene información de descargas activas recientes por país y mandante. */
            $sql = "SELECT
            d.descarga_id,
            dv.version,
            d.estado
        FROM
            descarga d
        INNER JOIN
            descarga_version dv ON d.descarga_id = dv.documento_id
        WHERE
            d.pais_id =" . $Pais->paisId . "
            AND d.mandante = " . $Mandante->mandante . "
            AND d.estado = 'A'
            AND dv.fecha_crea = (
                SELECT MAX(fecha_crea)
                FROM descarga_version
                WHERE documento_id = d.descarga_id)";


            /* ejecuta una consulta SQL y decodifica el resultado JSON en un array. */
            $query = $Descarga->querycustom2($sql);
            $descargaDocuments = json_decode($query, true);

            foreach ($descargaDocuments as $document) {

                /* Creación y configuración de un objeto DocumentoUsuario con información del documento. */
                $DocumentoUsuario = new DocumentoUsuario();

                $DocumentoUsuario->usuarioId = $consecutivo_usuario;
                $DocumentoUsuario->setDocumentoId($document['d.descarga_id']);
                $DocumentoUsuario->version = $document['dv.version'];
                $DocumentoUsuario->estadoAprobacion = "A";


                /* Se inserta un documento y se registra la transacción en el log. */
                $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();

                $DocumentoUsuarioMySqlDAO->insert($DocumentoUsuario);
                $documentSignTransaction = $DocumentoUsuarioMySqlDAO->getTransaction();


                //Dejando LOG ante aprobación de documentos
                $UsuarioLog = new UsuarioLog();

                /* Se registran datos del usuario y su IP en un objeto de log. */
                $UsuarioLog->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                $UsuarioLog->setUsuarioIp($ip);
                $UsuarioLog->setUsuariosolicitaId($UsuarioMandante->getUsuarioMandante());
                $UsuarioLog->setUsuariosolicitaIp($ip);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setUsuarioaprobarIp(0);

                /* Registro de cambios en la aprobación de un documento por un usuario. */
                $UsuarioLog->setTipo('APRUEBADOCUMENTO');
                $UsuarioLog->setValorAntes('');
                $UsuarioLog->setValorDespues($DocumentoUsuario->getEstadoAprobacion());
                $UsuarioLog->setUsucreaId($UsuarioMandante->getUsuarioMandante());
                $UsuarioLog->setUsumodifId($UsuarioMandante->getUsuarioMandante());
                $UsuarioLog->setEstado('A');

                /* Inserta un registro de usuario con datos de dispositivo, sistema operativo y versión. */
                $UsuarioLog->setDispositivo('');
                $UsuarioLog->setSoperativo($plaform);
                $UsuarioLog->setSversion($DocumentoUsuario->docusuarioId);

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($documentSignTransaction);
                $UsuarioLogMySqlDAO->insert($UsuarioLog);


                /* finaliza y confirma una transacción de firma de documento en un sistema. */
                $documentSignTransaction->commit();
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP sin realizar ninguna acción en el bloque catch. */


        }
    }


    /* Crea un array de respuesta con información de redirección y usuario. */
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["redirect"] = true;
    $response["redirectUrl"] = $redirectUrl;
    $response["userid"] = $Usuario->usuarioId;

    /* crea una respuesta con información de autenticación y redirección. */
    $response["auth_token"] = $auttoken;
    $response["data"] = array(
        "result" => "OK",
        "userid" => $Usuario->usuarioId,
        "redirect" => true,
        "redirectUrl" => $redirectUrl,
        "auth_token" => $auttoken

    );


    try {

        switch (strtolower($Usuario->idioma)) {


            case "pt":

                /* genera un mensaje de acceso basado en el estado del usuario. */
                $msj_complementario = ", nossas operadoras irão validar o registro para que você possa acessar sua conta.";

                if ($estadoUsuarioDefault == "A") {
                    $msj_complementario = ", a partir deste momento você poderá acessar sua conta. Seus dados serão validados. ";

                }

                //Arma el mensaje para el usuario que se registra

                /* Genera un mensaje de bienvenida y verifica el correo si el mandante es 6. */
                $mensaje_txt = "Bem-vindo ao " . $Mandante->nombre . $msj_complementario;
                $mensaje_txt = $mensaje_txt . "Temos muitas opções de depósito para você:<br><br>Medios de pagos via online 100% seguros.<br>Betshop, onde você deposita com seu número de documento legal.<br><br>";

                if ($Usuario->mandante == 6) {
                    $mensaje_txt = $mensaje_txt . "Você deve validar seu e-mail clicando no seguinte link:" . " <a href='" . $PaisMandante->baseUrl . "/verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId) . "'>Clique aqui</a> ou copie e cole o seguinte link " . $PaisMandante->baseUrl . "/verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId) . "<br><br>";

                }


                /* genera un mensaje de bienvenida con credenciales y recomendaciones de seguridad. */
                $mensaje_txt = $mensaje_txt . "Lembre-se de que suas credenciais de acesso são as seguintes:" . "<br><br>";
                $mensaje_txt = $mensaje_txt . "E-mail: " . $email . "<br>";
                $mensaje_txt = $mensaje_txt . "Senha: " . $password . "<br><br>";
                $mensaje_txt = $mensaje_txt . "Observação importante: sugerimos que, assim que entrar no sistema pela primeira vez, altere a senha imediatamente; Além disso, como recomendação adicional, proteja sua conta alterando essa senha regularmente." . "<br><br>";


                if ($Usuario->mandante == 14) {
                    $mensaje_txt =
                        "Bem-vindo à Lotosports! O seu cadastro foi confirmado e agora você já está apto a conhecer a melhor plataforma de apostas online da América Latina! São inúmeras modalidades de esportes e mercados de apostas disponíveis!

<br><br>Depósitos e Saques em segundos, via PIX!*

<br><br>Lembre-se de que suas credenciais de acesso são essas abaixo:

<br><br>Usuário: " . $email . "
<br>Senha: " . $password . "
<br><br>
Nota importante: sugerimos que uma vez que você acesse o sistema pela primeira vez, altere a senha imediatamente!  Como recomendação adicional, proteja a sua conta alterando a senha periodicamente.
                    ";
                }


                /* define variables de título y asunto con el nombre de un 'Mandante'. */
                $mtitle = 'Bem-vindo à ' . $Mandante->nombre;
                $msubjetc = 'Bem-vindo à ' . $Mandante->nombre;

                break;

            case "en":

                /* asigna un mensaje adicional según el estado del usuario. */
                $msj_complementario = ", our operators will validate the registration so that you can access your account.";

                if ($estadoUsuarioDefault == "A") {
                    $msj_complementario = ", from this moment you will be able to access your account. Your data will be validated. ";

                }

                //Arma el mensaje para el usuario que se registra

                /* Crea un mensaje de bienvenida con instrucciones sobre depósito y validación de email. */
                $mensaje_txt = "Welcome to " . $Mandante->nombre . $msj_complementario;
                $mensaje_txt = $mensaje_txt . "We have many deposit options for you:<br><br>100% safety in online transactions through payment getaways.<br>Betshop, where you deposit with your legal document number.<br><br>";

                if ($Usuario->mandante == 6) {
                    $mensaje_txt = $mensaje_txt . "You must validate your email by clicking on the following link:" . " <a href='" . $PaisMandante->baseUrl . "/verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId) . "'>Click here</a> or copy and paste the following link " . $PaisMandante->baseUrl . "/verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId) . "<br><br>";

                }


                /* Genera un mensaje de bienvenida y verificación para nuevos usuarios registrados. */
                if ($Usuario->mandante == 2) {
                    //Arma el mensaje para el usuario que se registra

                    $mensaje_txt = "Hello!<br><br>Welcome to " . $Mandante->nombre . '.Your account has been sent for review and verification.<br><br>Please expect to be verified within the next 24hrs, please feel free to reach out to customer care if you have any questions.<br><br>';

                    $mensaje_txt = $mensaje_txt . "Welcome to " . $Mandante->nombre . $msj_complementario;
                    $mensaje_txt = $mensaje_txt . "We have many deposit options for you:<br><br>100% safety in online transactions through payment getaways.<br>Betshop, where you deposit with your legal document number.<br><br>";

                    $mensaje_txt = $mensaje_txt . "You must validate your email by clicking on the following link:" . " <a href='" . $PaisMandante->baseUrl . "/verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId) . "'>Click here</a> or copy and paste the following link " . $PaisMandante->baseUrl . "/verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId) . "<br><br>";

                }


                /* Construye un mensaje de confirmación con credenciales y recomendaciones de seguridad. */
                $mensaje_txt = $mensaje_txt . "Remember that your credentials for access are the following:" . "<br><br>";
                $mensaje_txt = $mensaje_txt . "User: " . $email . "<br>";
                $mensaje_txt = $mensaje_txt . "Password: " . $password . "<br><br>";
                $mensaje_txt = $mensaje_txt . "Important note: we suggest that once you log into the system for the first time, change the password immediately; Also as an additional recommendation, secure your account by changing this password regularly." . "<br><br>";


                $mtitle = 'Registration in ' . $Mandante->nombre;

                /* Se asigna un asunto de registro utilizando el nombre del objeto $Mandante. */
                $msubjetc = 'Registration in ' . $Mandante->nombre;
                break;

            default:

                /* ajusta un mensaje según el estado del usuario. */
                $msj_complementario = ", nuestros operarios validaran el registro para que puedas acceder a tu cuenta.";

                if ($estadoUsuarioDefault == "A") {
                    $msj_complementario = ", desde este momento podrá acceder a tu cuenta. Tus datos serán validados. ";

                }

                //Arma el mensaje para el usuario que se registra

                /* Genera un mensaje de bienvenida y solicitud de verificación de correo para usuarios específicos. */
                $mensaje_txt = "Bienvenido a " . $Mandante->nombre . $msj_complementario;
                $mensaje_txt = $mensaje_txt . "Tenemos muchas opciones de deposito para ti: <br><br> Medios de pagos via online 100% seguros. <br> Puntos de venta físicos, donde recargas con tu numero de documento legal.  <br><br>";

                if ($Usuario->mandante == 6) {
                    $mensaje_txt = $mensaje_txt . "Debe de validar su correo electronico haciendo click en el siguiente enlace:" . " <a href='" . $PaisMandante->baseUrl . "/verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId) . "'>Click Aqui</a> o copia y pega el siguiente enlace " . $PaisMandante->baseUrl . "/verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId) . "<br><br>";

                }

                /* Concatena un mensaje recordando las credenciales y recomendaciones de seguridad al usuario. */
                $mensaje_txt = $mensaje_txt . "Recuerde que sus credenciales para el acceso son las siguientes:" . "<br><br>";
                $mensaje_txt = $mensaje_txt . "Usuario: " . $email . "<br>";
                $mensaje_txt = $mensaje_txt . "Clave: " . $password . "<br><br>";
                $mensaje_txt = $mensaje_txt . "Nota importante: sugerimos que una vez acceda al sistema por primera vez, cambie la clave inmediatamente; ademas como recomendacion adicional, asegure su cuenta cambiando dicha clave regularmente." . "<br><br>";


                $mtitle = 'Registro en ' . $Mandante->nombre;

                /* Asigna un asunto al registro utilizando el nombre del mandante. */
                $msubjetc = 'Registro en ' . $Mandante->nombre;
                break;
        }

        //Destinatarios

        /* Se asigna el valor de la variable `$email` a `$destinatarios`. */
        $destinatarios = $email;

        if ($ConfigurationEnvironment->isProduction()) {

            try {




                    /* Se crea un mensaje con una plantilla personalizada basada en el clasificador y usuario. */
                    $mensaje_txt2 = $mensaje_txt;
                    $mensaje_txt = "";

                    try {
                        $clasificador = new Clasificador("", "TEMEMREG");

                        $template = new Template('', $Mandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));


                        $mensaje_txt .= $template->templateHtml;

                    } catch (Exception $e) {
                        /* Bloque de código en PHP que maneja excepciones sin realizar ninguna acción. */

                    }

                    if ($mensaje_txt != '') {


                        /* Asigna una URL según el mandante y país del usuario en un código PHP. */
                        try {
                            /* Validación para encontrar la URL en la columna base_url de base de datos*/
                            if (empty($PaisMandante->baseUrl)) {
                                throw new Exception("No se encontró base_url para Mandante ID {$Usuario->mandante} y País ID {$Usuario->paisId}.", 300046);
                            }
                            $Mandante->baseUrl = $PaisMandante->baseUrl;
                        } catch (Exception $e) {
                            /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
                        }

                        $Pais = new Pais($Usuario->paisId);
                        $devUrl = 'https://apidev.virtualsoft.tech/publicity/banners/GetPoublicityBanners?partner=';

                        /* Construye una URL para un banner publicitario según el entorno y datos del usuario. */
                        $prodUrl = 'https://publicapi.virtualplay.co/publicity/banners/GetPoublicityBanners?partner=';
                        $url = $ConfigurationEnvironment->isDevelopment() ? $devUrl : $prodUrl;
                        $url .= $Usuario->mandante . '&country=' . strtolower($Pais->iso) . '&language=' . strtolower($Usuario->idioma) . '&type=';
                        $banner = '<a href="' . $url . '1"><img src="' . $url . '2" alt="banner"></a>';

                        $mensaje_txt = str_replace("#userid#", $Usuario->usuarioId, $mensaje_txt);

                        /* Sustituye marcadores en un mensaje por información del usuario y mandante. */
                        $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
                        $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
                        $mensaje_txt = str_replace("#login#", $Usuario->login, $mensaje_txt);
                        $mensaje_txt = str_replace("#password#", $password, $mensaje_txt);
                        $mensaje_txt = str_replace("#identification#", $Registro->cedula, $mensaje_txt);
                        $mensaje_txt = str_replace("#Mandante#", $Mandante->descripcion, $mensaje_txt);

                        /* Sustituye enlaces y banners en un mensaje antes de enviarlo por correo. */
                        $mensaje_txt = str_replace("#link#", $PaisMandante->baseUrl . "verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId), $mensaje_txt);
                        $mensaje_txt = str_replace('>#banners#<', '>' . $banner . '<', $mensaje_txt);

                        $envio = $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $msubjetc, $mensaje_txt, $dominio, $compania, $color_email, $Usuario->mandante,$Usuario->paisId);
                    } else {
                        /* envía un correo utilizando una configuración específica y un mensaje definido. */
                        /* Asigna una URL según el mandante y país del usuario en un código PHP. */
                        try {
                            /* Validación para encontrar la URL en la columna base_url de base de datos*/
                            if (empty($PaisMandante->baseUrl)) {
                                throw new Exception("No se encontró base_url para Mandante ID {$Usuario->mandante} y País ID {$Usuario->paisId}.", 300046);
                            }
                            $Mandante->baseUrl = $PaisMandante->baseUrl;
                        } catch (Exception $e) {
                            /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
                        }
                        $mensaje_txt = $mensaje_txt2;
                        $envio = $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $msubjetc, $mensaje_txt, $dominio, $compania, $color_email, $Usuario->mandante,$Usuario->paisId);

                    }

            } catch (Exception $e) {
                /* Bloque de código que captura excepciones sin realizar ninguna acción. */


            }

        }


        try {


            if ($codigoBD != '' && $codigoBD > 0 && is_numeric($codigoBD) && $codigoBD != '2898') {


                /* Inicializa un arreglo con detalles de un depósito y datos del usuario. */
                $detalles = array(
                    "Depositos" => 0,
                    "DepositoEfectivo" => false,
                    "MetodoPago" => 0,
                    "ValorDeposito" => 0,
                    "PaisPV" => 0,
                    "DepartamentoPV" => 0,
                    "CiudadPV" => 0,
                    "PuntoVenta" => 0,
                    "PaisUSER" => $Usuario->paisId,
                    "DepartamentoUSER" => 0,
                    "CiudadUSER" => $Registro->ciudadId,
                    "MonedaUSER" => $Usuario->moneda,
                    "CodePromo" => $codigoBD
                );


                /* crea un bono interno utilizando datos y transacciones de MySQL. */
                $detalles = json_decode(json_encode($detalles));

                $BonoInterno = new BonoInterno();
                $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
                $Transaction = $BonoInternoMySqlDAO->getTransaction();


                $responseBonus = $BonoInterno->agregarBono("", $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);

                /* Realiza un commit en la transacción si hay un bono ganado. */
                if ($responseBonus->WinBonus) {
                    $Transaction->commit();
                }

            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, captura errores sin realizar acciones específicas. */


        }
        try {
            if ($type_gift != '') {


                /* Código para crear un objeto 'SitioTracking' y establecer sus propiedades específicas. */
                $SitioTracking = new \Backend\dto\SitioTracking();

                $SitioTracking->setTabla('registro_type_gift');
                $SitioTracking->setTablaId($consecutivo_usuario);
                $SitioTracking->setTipo('2');
                $SitioTracking->setTvalue($type_gift);

                /* establece valores en un objeto y crea una instancia de DAO MySQL. */
                $SitioTracking->valueInd = $type_gift;
                $SitioTracking->setUsucreaId('0');
                $SitioTracking->setUsumodifId('0');


                $SitioTrackingMySqlDAO = new \Backend\mysql\SitioTrackingMySqlDAO();

                /* Inserta un registro en la base de datos y prepara un mensaje de usuario. */
                $SitioTrackingMySqlDAO->insert($SitioTracking);
                $SitioTrackingMySqlDAO->getTransaction()->commit();

                $UsuarioMensaje = new UsuarioMensaje();
                $UsuarioMensaje->usufromId = 0;
                $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;

                /* establece un mensaje de bienvenida dependiendo del país y condiciones del usuario. */
                $UsuarioMensaje->isRead = 0;
                $UsuarioMensaje->body = '<div ><img src="https://images.virtualsoft.tech/m/msjT1692917289.png" style=" width: 250px; margin: 0 auto; margin-bottom: 20px !important; "><div><b style=" text-align: left; font-size: 20px; ">No te preocupes, aquí te explicamos cómo hacerlo :wink: </b></div><br><div>Para poder recibir tu regalo de bienvenida, deberás verificar tu cuenta previamente</div><br><div style=" font-size: 14px; padding: 0px 20px; ">Si elegiste el premio de apuesta deportiva gratis, deberás realizar una apuesta por valor de S/25 con cuota mínima de 2.5 en el deporte que más te guste. ¡Listo! Es hora de disfrutar el partido :soccer:</div><br><div style=" font-size: 14px; padding: 0 20px; ">Si elegiste la opción de giros gratis :slot_machine: deberás ingresar a la sección de casino y elegir el juego que más te guste de Playtech. ¡Eso es todo! Ahora puedes disfrutar de tus giros gratis.</div><br><div style=" font-size: 13px; ">*Los premios estarán disponibles hasta 7 días después de tu registro.</div></div>';

                if ($Usuario->mandante == 0 && $Usuario->paisId != 173) {
                    $UsuarioMensaje->body = '<div ><div><b style=" text-align: left; font-size: 20px; ">No te preocupes, aquí te explicamos cómo hacerlo :wink: </b></div><br><div>Para poder recibir tu regalo de bienvenida, deberás verificar tu cuenta previamente</div><br><div style=" font-size: 14px; padding: 0px 20px; ">Si elegiste el premio de apuesta deportiva gratis, deberás realizar una apuesta  con cuota mínima de 2.5 en el deporte que más te guste. ¡Listo! Es hora de disfrutar el partido :soccer:</div><br><div style=" font-size: 14px; padding: 0 20px; ">Si elegiste la opción de giros gratis :slot_machine: deberás ingresar a la sección de casino y elegir el juego que más te guste de Playtech. ¡Eso es todo! Ahora puedes disfrutar de tus giros gratis.</div><br><div style=" font-size: 13px; ">*Los premios estarán disponibles hasta 7 días después de tu registro.</div></div>';

                }


                /* Código que configura un mensaje de usuario con propiedades específicas y fecha de expiración. */
                $UsuarioMensaje->msubject = '';
                $UsuarioMensaje->tipo = "MESSAGEINV";
                $UsuarioMensaje->parentId = '0';
                $UsuarioMensaje->proveedorId = '0';
                $UsuarioMensaje->setExternoId('0');
                $UsuarioMensaje->fechaExpiracion = date('Y-m-d H:i:s', strtotime(' + ' . 7 . ' days'));


                /* Inserta un mensaje de usuario en la base de datos y reinicia la variable. */
                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                $mensaje_txt2 = $mensaje_txt;
                $mensaje_txt = "";

                /* Asignación de ID de campaña según mandante y país del usuario. */
                $msubjetc = '¿Aún no redimes tu regalo de Bienvenida?';
                $usumensajecampanaId = '';
                if ($Usuario->mandante == '0' && $Usuario->paisId == '173') {
                    $usumensajecampanaId = 79103;
                }
                if ($Usuario->mandante == '0' && $Usuario->paisId == '46') {
                    $usumensajecampanaId = '95692';
                }

                /* Asigna un ID de campaña según el mandante y el país del usuario. */
                if ($Usuario->mandante == '0' && $Usuario->paisId == '66') {
                    $usumensajecampanaId = '95694';
                }
                if ($Usuario->mandante == '0' && $Usuario->paisId == '94') {
                    $usumensajecampanaId = '122142';
                }

                /* Condicional que genera un mensaje HTML según parámetros definidos y maneja excepciones. */
                if ($usumensajecampanaId != '') {

                    try {
                        $clasificador = new Clasificador("", "TEMEMREGGIROS");

                        $template = new Template('', $Mandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));


                        $mensaje_txt .= $template->templateHtml;

                    } catch (Exception $e) {
                    }

                }
                if ($mensaje_txt != '') {

                    /* Asigna una URL según el mandante y país del usuario en un código PHP. */
                    try {
                        /* Validación para encontrar la URL en la columna base_url de base de datos*/
                        if (empty($PaisMandante->baseUrl)) {
                            throw new Exception("No se encontró base_url para Mandante ID {$Usuario->mandante} y País ID {$Usuario->paisId}.", 300046);
                        }
                        $Mandante->baseUrl = $PaisMandante->baseUrl;
                    } catch (Exception $e) {
                        /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
                    }

                    $Pais = new Pais($Usuario->paisId);
                    $devUrl = 'https://apidev.virtualsoft.tech/publicity/banners/GetPoublicityBanners?partner=';

                    /* Construye una URL para obtener banners publicitarios según el contexto y usuario. */
                    $prodUrl = 'https://publicapi.virtualplay.co/publicity/banners/GetPoublicityBanners?partner=';
                    $url = $ConfigurationEnvironment->isDevelopment() ? $devUrl : $prodUrl;
                    $url .= $Usuario->mandante . '&country=' . strtolower($Pais->iso) . '&language=' . strtolower($Usuario->idioma) . '&type=';
                    $banner = '<a href="' . $url . '1"><img src="' . $url . '2" alt="banner"></a>';

                    $mensaje_txt = str_replace("#userid#", $Usuario->usuarioId, $mensaje_txt);

                    /* Reemplaza marcadores en un mensaje por datos del usuario y otro registro. */
                    $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
                    $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
                    $mensaje_txt = str_replace("#login#", $Usuario->login, $mensaje_txt);
                    $mensaje_txt = str_replace("#password#", $password, $mensaje_txt);
                    $mensaje_txt = str_replace("#identification#", $Registro->cedula, $mensaje_txt);
                    $mensaje_txt = str_replace("#Mandante#", $Mandante->descripcion, $mensaje_txt);

                    /* Reemplaza enlaces y banners en un mensaje antes de enviarlo por correo. */
                    $mensaje_txt = str_replace("#link#", $PaisMandante->baseUrl . "verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId), $mensaje_txt);
                    $mensaje_txt = str_replace('>#banners#<', '>' . $banner . '<', $mensaje_txt);

                    $envio = $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $msubjetc, $mensaje_txt, $dominio, $compania, $color_email, $Usuario->mandante);
                }


                if ($usumensajecampanaId != '') {


                    /* Se inicializan objetos para mensajes y usuarios, extrayendo información relevante. */
                    $UsuarioMensajeCampana = new \Backend\dto\UsuarioMensajecampana($usumensajecampanaId);
                    $Message = $UsuarioMensajeCampana->body;


                    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);


                    $usutoId = $UsuarioMandante->usumandanteId;


                    /* Se crean instancias de la clase UsuarioMensaje y se inicializan con diferentes parámetros. */
                    $UsuarioMensaje2 = new UsuarioMensaje($UsuarioMensajeCampana->usumensajeId);


                    $mensajeTemp = $Message;

                    $UsuarioMensaje = new UsuarioMensaje();

                    /* Se asignan valores a un objeto de mensaje para enviar a un usuario. */
                    $UsuarioMensaje->usufromId = '0';
                    $UsuarioMensaje->usutoId = $usutoId;
                    $UsuarioMensaje->isRead = '0';
                    $UsuarioMensaje->body = $mensajeTemp;
                    $UsuarioMensaje->msubject = $UsuarioMensajeCampana->msubject;
                    $UsuarioMensaje->parentId = $UsuarioMensaje2->usumensajeId;

                    /* Se asignan valores a un objeto de mensaje usuario y se establece una fecha de expiración. */
                    $UsuarioMensaje->proveedorId = $Usuario->mandante;
                    $UsuarioMensaje->tipo = "MENSAJE";
                    $UsuarioMensaje->paisId = $Usuario->paisId;
                    $UsuarioMensaje->fechaExpiracion = date('Y-m-d H:i:s', strtotime(' + ' . 7 . ' days'));
                    $UsuarioMensaje->usumencampanaId = $UsuarioMensaje2->usumencampanaId;
                    $msg = "entro5";


                    /* Código para insertar un mensaje de usuario en la base de datos MySQL. */
                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                }

            }
            if ($type_gift != '' && $site_id == 21) { // este condicional es para realizar el registro a cammanbet


                $consecutivo_usuario = $IdFirstUser;
                $Usuario= new Usuario($consecutivo_usuario);
                $UsuarioMandante = new UsuarioMandante("",$Usuario->usuarioId,$Usuario->mandante);

                /* Código para crear un objeto 'SitioTracking' y establecer sus propiedades específicas. */
                $SitioTracking = new \Backend\dto\SitioTracking();

                $SitioTracking->setTabla('registro_type_gift');
                $SitioTracking->setTablaId($consecutivo_usuario);
                $SitioTracking->setTipo('2');
                $SitioTracking->setTvalue($type_gift);

                /* establece valores en un objeto y crea una instancia de DAO MySQL. */
                $SitioTracking->valueInd = $type_gift;
                $SitioTracking->setUsucreaId('0');
                $SitioTracking->setUsumodifId('0');


                $SitioTrackingMySqlDAO = new \Backend\mysql\SitioTrackingMySqlDAO();

                /* Inserta un registro en la base de datos y prepara un mensaje de usuario. */
                $SitioTrackingMySqlDAO->insert($SitioTracking);
                $SitioTrackingMySqlDAO->getTransaction()->commit();

                $UsuarioMensaje = new UsuarioMensaje();
                $UsuarioMensaje->usufromId = 0;
                $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;

                /* establece un mensaje de bienvenida dependiendo del país y condiciones del usuario. */
                $UsuarioMensaje->isRead = 0;
                $UsuarioMensaje->body = '<div ><img src="https://images.virtualsoft.tech/m/msjT1692917289.png" style=" width: 250px; margin: 0 auto; margin-bottom: 20px !important; "><div><b style=" text-align: left; font-size: 20px; ">No te preocupes, aquí te explicamos cómo hacerlo :wink: </b></div><br><div>Para poder recibir tu regalo de bienvenida, deberás verificar tu cuenta previamente</div><br><div style=" font-size: 14px; padding: 0px 20px; ">Si elegiste el premio de apuesta deportiva gratis, deberás realizar una apuesta por valor de S/25 con cuota mínima de 2.5 en el deporte que más te guste. ¡Listo! Es hora de disfrutar el partido :soccer:</div><br><div style=" font-size: 14px; padding: 0 20px; ">Si elegiste la opción de giros gratis :slot_machine: deberás ingresar a la sección de casino y elegir el juego que más te guste de Playtech. ¡Eso es todo! Ahora puedes disfrutar de tus giros gratis.</div><br><div style=" font-size: 13px; ">*Los premios estarán disponibles hasta 7 días después de tu registro.</div></div>';

                if ($Usuario->mandante == 0 && $Usuario->paisId != 173) {
                    $UsuarioMensaje->body = '<div ><div><b style=" text-align: left; font-size: 20px; ">No te preocupes, aquí te explicamos cómo hacerlo :wink: </b></div><br><div>Para poder recibir tu regalo de bienvenida, deberás verificar tu cuenta previamente</div><br><div style=" font-size: 14px; padding: 0px 20px; ">Si elegiste el premio de apuesta deportiva gratis, deberás realizar una apuesta  con cuota mínima de 2.5 en el deporte que más te guste. ¡Listo! Es hora de disfrutar el partido :soccer:</div><br><div style=" font-size: 14px; padding: 0 20px; ">Si elegiste la opción de giros gratis :slot_machine: deberás ingresar a la sección de casino y elegir el juego que más te guste de Playtech. ¡Eso es todo! Ahora puedes disfrutar de tus giros gratis.</div><br><div style=" font-size: 13px; ">*Los premios estarán disponibles hasta 7 días después de tu registro.</div></div>';

                }


                /* Código que configura un mensaje de usuario con propiedades específicas y fecha de expiración. */
                $UsuarioMensaje->msubject = '';
                $UsuarioMensaje->tipo = "MESSAGEINV";
                $UsuarioMensaje->parentId = '0';
                $UsuarioMensaje->proveedorId = '0';
                $UsuarioMensaje->setExternoId('0');
                $UsuarioMensaje->fechaExpiracion = date('Y-m-d H:i:s', strtotime(' + ' . 7 . ' days'));


                /* Inserta un mensaje de usuario en la base de datos y reinicia la variable. */
                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                $mensaje_txt2 = $mensaje_txt;
                $mensaje_txt = "";

                /* Asignación de ID de campaña según mandante y país del usuario. */
                $msubjetc = '¿Aún no redimes tu regalo de Bienvenida?';
                $usumensajecampanaId = '';
                if ($Usuario->mandante == '0' && $Usuario->paisId == '173') {
                    $usumensajecampanaId = 79103;
                }
                if ($Usuario->mandante == '0' && $Usuario->paisId == '46') {
                    $usumensajecampanaId = '95692';
                }

                /* Asigna un ID de campaña según el mandante y el país del usuario. */
                if ($Usuario->mandante == '0' && $Usuario->paisId == '66') {
                    $usumensajecampanaId = '95694';
                }
                if ($Usuario->mandante == '0' && $Usuario->paisId == '94') {
                    $usumensajecampanaId = '122142';
                }

                /* Condicional que genera un mensaje HTML según parámetros definidos y maneja excepciones. */
                if ($usumensajecampanaId != '') {

                    try {
                        $clasificador = new Clasificador("", "TEMEMREGGIROS");

                        $template = new Template('', $Mandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));


                        $mensaje_txt .= $template->templateHtml;

                    } catch (Exception $e) {
                    }

                }
                if ($mensaje_txt != '') {


                    /* Asigna una URL según el mandante y país del usuario en un código PHP. */
                    try {
                        /* Validación para encontrar la URL en la columna base_url de base de datos*/
                        if (empty($PaisMandante->baseUrl)) {
                            throw new Exception("No se encontró base_url para Mandante ID {$Usuario->mandante} y País ID {$Usuario->paisId}.", 300046);
                        }
                        $Mandante->baseUrl = $PaisMandante->baseUrl;
                    } catch (Exception $e) {
                        /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
                    }

                    $Pais = new Pais($Usuario->paisId);
                    $devUrl = 'https://apidev.virtualsoft.tech/publicity/banners/GetPoublicityBanners?partner=';

                    /* Construye una URL para obtener banners publicitarios según el contexto y usuario. */
                    $prodUrl = 'https://publicapi.virtualplay.co/publicity/banners/GetPoublicityBanners?partner=';
                    $url = $ConfigurationEnvironment->isDevelopment() ? $devUrl : $prodUrl;
                    $url .= $Usuario->mandante . '&country=' . strtolower($Pais->iso) . '&language=' . strtolower($Usuario->idioma) . '&type=';
                    $banner = '<a href="' . $url . '1"><img src="' . $url . '2" alt="banner"></a>';

                    $mensaje_txt = str_replace("#userid#", $Usuario->usuarioId, $mensaje_txt);

                    /* Reemplaza marcadores en un mensaje por datos del usuario y otro registro. */
                    $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
                    $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
                    $mensaje_txt = str_replace("#login#", $Usuario->login, $mensaje_txt);
                    $mensaje_txt = str_replace("#password#", $password, $mensaje_txt);
                    $mensaje_txt = str_replace("#identification#", $Registro->cedula, $mensaje_txt);
                    $mensaje_txt = str_replace("#Mandante#", $Mandante->descripcion, $mensaje_txt);

                    /* Reemplaza enlaces y banners en un mensaje antes de enviarlo por correo. */
                    $mensaje_txt = str_replace("#link#", $PaisMandante->baseUrl . "verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId), $mensaje_txt);
                    $mensaje_txt = str_replace('>#banners#<', '>' . $banner . '<', $mensaje_txt);

                    $envio = $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $msubjetc, $mensaje_txt, $dominio, $compania, $color_email, $Usuario->mandante);
                }


                if ($usumensajecampanaId != '') {


                    /* Se inicializan objetos para mensajes y usuarios, extrayendo información relevante. */
                    $UsuarioMensajeCampana = new \Backend\dto\UsuarioMensajecampana($usumensajecampanaId);
                    $Message = $UsuarioMensajeCampana->body;


                    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);


                    $usutoId = $UsuarioMandante->usumandanteId;


                    /* Se crean instancias de la clase UsuarioMensaje y se inicializan con diferentes parámetros. */
                    $UsuarioMensaje2 = new UsuarioMensaje($UsuarioMensajeCampana->usumensajeId);


                    $mensajeTemp = $Message;

                    $UsuarioMensaje = new UsuarioMensaje();

                    /* Se asignan valores a un objeto de mensaje para enviar a un usuario. */
                    $UsuarioMensaje->usufromId = '0';
                    $UsuarioMensaje->usutoId = $usutoId;
                    $UsuarioMensaje->isRead = '0';
                    $UsuarioMensaje->body = $mensajeTemp;
                    $UsuarioMensaje->msubject = $UsuarioMensajeCampana->msubject;
                    $UsuarioMensaje->parentId = $UsuarioMensaje2->usumensajeId;

                    /* Se asignan valores a un objeto de mensaje usuario y se establece una fecha de expiración. */
                    $UsuarioMensaje->proveedorId = $Usuario->mandante;
                    $UsuarioMensaje->tipo = "MENSAJE";
                    $UsuarioMensaje->paisId = $Usuario->paisId;
                    $UsuarioMensaje->fechaExpiracion = date('Y-m-d H:i:s', strtotime(' + ' . 7 . ' days'));
                    $UsuarioMensaje->usumencampanaId = $UsuarioMensaje2->usumencampanaId;
                    $msg = "entro5";


                    /* Código para insertar un mensaje de usuario en la base de datos MySQL. */
                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                }

            }

        } catch (Exception $e) {
            /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque catch. */


        }

    } catch (Exception $e) {
        /* captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


    }

    if ($Usuario->mandante == 0 && $Usuario->paisId == 173 && false) {
        try {
            //Recuperando información del solicitante

            /* Código que obtiene la plataforma y dirección IP del usuario para una descarga. */
            $plaform = strval($_SERVER['HTTP_SEC_CH_UA_PLATFORM']);
            $plaform = str_replace('"', "", $plaform);
            $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $ip = explode(",", $ip)[0];

            $Descarga = new Descarga(19);

            /* Se crea un nuevo DocumentoUsuario con datos del usuario y descarga especificados. */
            $DocumentoUsuario = new DocumentoUsuario();

            $DocumentoUsuario->usuarioId = $Usuario->usuarioId;
            $DocumentoUsuario->documentoId = $Descarga->descargaId;
            $DocumentoUsuario->version = $Descarga->version;
            $DocumentoUsuario->estadoAprobacion = "A";


            /* Se inserta un documento y se registra la transacción en el log de usuario. */
            $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
            $DocumentoUsuarioMySqlDAO->insert($DocumentoUsuario);
            $documentSignTransaction = $DocumentoUsuarioMySqlDAO->getTransaction();

            //Dejando LOG ante aprobación de documentos
            $UsuarioLog = new UsuarioLog();

            /* Establece parámetros de usuario y IP en un objeto de registro de usuario. */
            $UsuarioLog->setUsuarioId($UsuarioMandante->getUsuarioMandante());
            $UsuarioLog->setUsuarioIp($ip);
            $UsuarioLog->setUsuariosolicitaId($UsuarioMandante->getUsuarioMandante());
            $UsuarioLog->setUsuariosolicitaIp($ip);
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setUsuarioaprobarIp(0);

            /* registra cambios en el estado de un documento para un usuario específico. */
            $UsuarioLog->setTipo('APRUEBADOCUMENTO');
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues($DocumentoUsuario->getEstadoAprobacion());
            $UsuarioLog->setUsucreaId($UsuarioMandante->getUsuarioMandante());
            $UsuarioLog->setUsumodifId($UsuarioMandante->getUsuarioMandante());
            $UsuarioLog->setEstado('A');

            /* Se registra un nuevo log de usuario con información del dispositivo y versión. */
            $UsuarioLog->setDispositivo('');
            $UsuarioLog->setSoperativo($plaform);
            $UsuarioLog->setSversion($DocumentoUsuario->docusuarioId);

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($documentSignTransaction);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);


            /* finaliza y guarda los cambios en una transacción de firma de documento. */
            $documentSignTransaction->commit();
        } catch (Exception $e) {
            /* captura excepciones en PHP, evitando errores que detengan la ejecución. */


        }
    }

    $Subproveedor = new Subproveedor("", "ITN");
    $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
    $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
    $urlAltenar = $Credentials->URL2;
    $walletCode = $Credentials->WALLET_CODE;

    if ($Mandante->mandante == '14' && false) {


        if (true) {

            /* Asigna rutas y configuración de moneda para el usuario en un sistema. */
            $pathPartner = $Mandante->pathItainment;
            $pathFixed = $Pais->codigoPath;
            $usermoneda = $moneda_default;
            $userpath = $pathFixed;

            $pathFixed = '2:Web ' . $usermoneda . ',' . $userpath;


            /* Verifica el valor de 'mandante' y ajusta la ruta según condiciones específicas. */
            if ($Mandante->mandante != '') {
                if (is_numeric($Mandante->mandante)) {
                    if (intval($Mandante->mandante) > 2) {

                        $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . $userpath;
                        if (intval($Mandante->mandante) == 9) {

                            $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . 'W' . $userpath;

                        }

                    }
                }
            }


            /* verifica el ID de usuario y genera un array de datos del usuario. */
            $IdUsuarioAltenar = $Usuario->usuarioId;
            if ((intval($Usuario->usuarioId) > 73758) || (in_array(intval($Usuario->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                $IdUsuarioAltenar = $Usuario->usuarioId . "U";
            }
            $dataD = array(
                "ExtUser" => array(
                    "LoginName" => $Usuario->nombre,
                    "Currency" => $moneda_default,
                    "Country" => $Pais->iso,
                    "ExternalUserId" => $IdUsuarioAltenar,
                    "AffiliationPath" => $Usuario->getAffiliationPathAltenar(),
                    "UserCode" => "3",
                    "FirstName" => $Registro->nombre1,
                    "LastName" => $Registro->apellido1,
                    "UserBalance" => "0"),
                "WalletCode" => $walletCode
            );


            /* Código para enviar una solicitud POST con datos JSON a una API específica. */
            $dataD = json_encode($dataD);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateUser/json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataD,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            /* Registro de advertencias de tiempo y respuestas de cURL en el sistema de logs. */
            $time = time();
            syslog(LOG_WARNING, " ITNBONUS :" . $time . " " . ($dataD));

            $response2 = curl_exec($curl);
            syslog(LOG_WARNING, " ITNBONUSR :" . $time . " " . ($response2));

            curl_close($curl);


            /* valida y modifica el ID de usuario según ciertas condiciones. */
            $response2 = json_decode($response2);

            $IdUsuarioAltenar = $Usuario->usuarioId;
            if ((intval($Usuario->usuarioId) > 73758) || (in_array(intval($Usuario->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                $IdUsuarioAltenar = $Usuario->usuarioId . "U";
            }


            /* Se crea un arreglo con datos de usuario y detalles de una transacción. */
            $dataD = array(
                "ExtUserId" => $IdUsuarioAltenar,
                "WalletCode" => "190582",
                "BonusCode" => "FreebetCadastro6R",
                "Deposit" => "600"
            );


            /* configura y envía una solicitud POST en formato JSON. */
            $dataD = json_encode($dataD);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateBonusByCode/json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataD,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            /* registra advertencias y ejecuta una solicitud HTTP usando cURL. */
            syslog(LOG_WARNING, " ITNBONUS :" . $time . " " . ($dataD));

            $response2 = curl_exec($curl);
            syslog(LOG_WARNING, " ITNBONUSR :" . $time . " " . ($response2));

            curl_close($curl);


            /* Convierte una cadena JSON en un objeto o array en PHP. */
            $response2 = json_decode($response2);
        }

    }

    if ($Mandante->mandante == '17') {


        if (true) {

            /* asigna rutas y moneda para un usuario basado en datos específicos. */
            $pathPartner = $Mandante->pathItainment;
            $pathFixed = $Pais->codigoPath;
            $usermoneda = $moneda_default;
            $userpath = $pathFixed;

            $pathFixed = '2:Web ' . $usermoneda . ',' . $userpath;


            /* Condicionales que establecen un formato de ruta según el valor de $Mandante->mandante. */
            if ($Mandante->mandante != '') {
                if (is_numeric($Mandante->mandante)) {
                    if (intval($Mandante->mandante) > 2) {

                        $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . $userpath;
                        if (intval($Mandante->mandante) == 9) {

                            $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . 'W' . $userpath;

                        }

                    }
                }
            }


            /* genera un array con información del usuario basándose en condiciones específicas. */
            $IdUsuarioAltenar = $Usuario->usuarioId;
            if ((intval($Usuario->usuarioId) > 73758) || (in_array(intval($Usuario->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                $IdUsuarioAltenar = $Usuario->usuarioId . "U";
            }
            $dataD = array(
                "ExtUser" => array(
                    "LoginName" => $Usuario->nombre,
                    "Currency" => $moneda_default,
                    "Country" => $Pais->iso,
                    "ExternalUserId" => $IdUsuarioAltenar,
                    "AffiliationPath" => $Usuario->getAffiliationPathAltenar(),
                    "UserCode" => "3",
                    "FirstName" => $Registro->nombre1,
                    "LastName" => $Registro->apellido1,
                    "UserBalance" => "0"),
                "WalletCode" => $walletCode
            );


            /* envía datos JSON a una API usando cURL en PHP. */
            $dataD = json_encode($dataD);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateUser/json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataD,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            /* registra advertencias en el sistema y ejecuta una solicitud cURL. */
            $time = time();
            syslog(LOG_WARNING, " ITNBONUS :" . $time . " " . ($dataD));

            $response2 = curl_exec($curl);
            syslog(LOG_WARNING, " ITNBONUSR :" . $time . " " . ($response2));

            curl_close($curl);


            /* transforma y modifica un ID de usuario bajo ciertas condiciones. */
            $response2 = json_decode($response2);

            $IdUsuarioAltenar = $Usuario->usuarioId;
            if ((intval($Usuario->usuarioId) > 73758) || (in_array(intval($Usuario->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                $IdUsuarioAltenar = $Usuario->usuarioId . "U";
            }


            /* Se crea un array con información de usuario y bonificación para un sistema. */
            $dataD = array(
                "ExtUserId" => $IdUsuarioAltenar,
                "WalletCode" => $walletCode,
                "BonusCode" => "FreebetcadastroMilbets",
                "Deposit" => "500"
            );


            /* envía datos JSON a una API usando cURL en PHP. */
            $dataD = json_encode($dataD);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateBonusByCode/json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataD,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            /* Registra advertencias y ejecuta una solicitud cURL, cerrando la conexión después. */
            syslog(LOG_WARNING, " ITNBONUS :" . $time . " " . ($dataD));

            $response2 = curl_exec($curl);
            syslog(LOG_WARNING, " ITNBONUSR :" . $time . " " . ($response2));

            curl_close($curl);


            /* Convierte una cadena JSON en un objeto PHP mediante la función json_decode. */
            $response2 = json_decode($response2);
        }

    }
    if ($Mandante->mandante == '0' && $Usuario->paisId == 46) {


        if (true) {

            /* establece rutas y moneda para un usuario en un contexto específico. */
            $pathPartner = $Mandante->pathItainment;
            $pathFixed = $Pais->codigoPath;
            $usermoneda = $moneda_default;
            $userpath = $pathFixed;

            $pathFixed = '2:Web ' . $usermoneda . ',' . $userpath;


            /* Verifica el valor de 'mandante' y construye un path según condiciones. */
            if ($Mandante->mandante != '') {
                if (is_numeric($Mandante->mandante)) {
                    if (intval($Mandante->mandante) > 2) {

                        $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . $userpath;
                        if (intval($Mandante->mandante) == 9) {

                            $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . 'W' . $userpath;

                        }

                    }
                }
            }

            if ($pathPartner == '') {


                /* Asigna un valor a `$pathPartner` basado en la condición del objeto `$Mandante`. */
                $pathPartner = "1:colombia,S3";


                if ($Mandante->mandante == 1) {
                    $pathPartner = "1:ibet,S1";
                }


                /* Asigna diferentes rutas según el valor de $Mandante->mandante. */
                if ($Mandante->mandante == 2) {
                    $pathPartner = "1:justbetja,S2";
                }


                if ($Mandante->mandante == 3) {
                    $pathPartner = "1:miravalle,S7";
                }


                /* asigna un valor a $pathPartner según el mandante. */
                if ($Mandante->mandante == 4) {
                    $pathPartner = "1:casinogranpalacio,S20";
                }


                if ($Mandante->mandante == 5) {
                    $pathPartner = "1:casinointercontinental,S9";
                }


                /* Asigna un valor a $pathPartner según el valor de $Mandante->mandante. */
                if ($Mandante->mandante == 6) {
                    $pathPartner = "1:netabet,S10";
                }


                if ($Mandante->mandante == 7) {
                    $pathPartner = "1:casinoastoria,S11";
                }


                /* asigna rutas basadas en el valor de 'mandante'. */
                if ($Mandante->mandante == 8) {
                    $pathPartner = "1:ecuabet,S12";
                }

                if ($Mandante->mandante == 9) {
                    $pathPartner = "1:winbet,S13";
                }


                /* asigna una ruta basada en condiciones del mandante y país del usuario. */
                if ($Mandante->mandante == 0 && $Usuario->paisId == '60') {
                    $pathPartner = "1:doradobet,S0-60";
                }

                if ($Mandante->mandante == '0') {
                    $pathPartner = "1:doradobet,S" . $Mandante->mandante . "-" . $Usuario->paisId;
                }

                /* Condicional que asigna un valor a $pathPartner basado en el valor de $Mandante. */
                if ($Mandante->mandante == '8') {
                    $pathPartner = "1:ecuabet,S" . $Mandante->mandante;
                }


            }


            /* Código que define un array con información de usuario basada en condiciones específicas. */
            $IdUsuarioAltenar = $Usuario->usuarioId;
            if ((intval($Usuario->usuarioId) > 73758) || (in_array(intval($Usuario->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                $IdUsuarioAltenar = $Usuario->usuarioId . "U";
            }
            $dataD = array(
                "ExtUser" => array(
                    "LoginName" => $Usuario->nombre,
                    "Currency" => $moneda_default,
                    "Country" => $Pais->iso,
                    "ExternalUserId" => $IdUsuarioAltenar,
                    "AffiliationPath" => $Usuario->getAffiliationPathAltenar(),
                    "UserCode" => "3",
                    "FirstName" => $Registro->nombre1,
                    "LastName" => $Registro->apellido1,
                    "UserBalance" => "0"),
                "WalletCode" => $walletCode
            );


            /* envía una solicitud POST con datos JSON a una API. */
            $dataD = json_encode($dataD);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateUser/json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataD,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            /* Registra advertencias en el syslog sobre el tiempo y respuesta de cURL. */
            $time = time();
            syslog(LOG_WARNING, " ITNBONUS :" . $time . " " . ($dataD));

            $response2 = curl_exec($curl);
            syslog(LOG_WARNING, " ITNBONUSR :" . $time . " " . ($response2));

            curl_close($curl);


            /* decodifica una respuesta JSON y modifica un ID de usuario según condiciones específicas. */
            $response2 = json_decode($response2);


            $IdUsuarioAltenar = $Usuario->usuarioId;
            if ((intval($Usuario->usuarioId) > 73758) || (in_array(intval($Usuario->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                $IdUsuarioAltenar = $Usuario->usuarioId . "U";
            }

            /* Se crea un arreglo en PHP con datos para una transacción de bonificación. */
            $dataD = array(
                "ExtUserId" => $IdUsuarioAltenar,
                "WalletCode" => $walletCode,
                "BonusCode" => "FREEBETCHILEREGISTRO",
                "Deposit" => "300000"
            );


            /* envía una solicitud POST en JSON a una API para crear un bonus. */
            $dataD = json_encode($dataD);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateBonusByCode/json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataD,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            /* Registra advertencias en el sistema y ejecuta una solicitud cURL, cerrando luego la conexión. */
            syslog(LOG_WARNING, " ITNBONUS :" . $time . " " . ($dataD));

            $response2 = curl_exec($curl);
            syslog(LOG_WARNING, " ITNBONUSR :" . $time . " " . ($response2));

            curl_close($curl);


            /* convierte una cadena JSON en un objeto PHP utilizando json_decode. */
            $response2 = json_decode($response2);
        }

    }


    if ($Mandante->mandante == '0' && $Usuario->paisId == 94) {


        if (true) {

            /* Se define una ruta basada en valores de variables para su uso posterior. */
            $pathPartner = $Mandante->pathItainment;
            $pathFixed = $Pais->codigoPath;
            $usermoneda = $moneda_default;
            $userpath = $pathFixed;

            $pathFixed = '2:Web ' . $usermoneda . ',' . $userpath;


            /* Verifica el valor de "$Mandante->mandante" y construye una ruta según condiciones. */
            if ($Mandante->mandante != '') {
                if (is_numeric($Mandante->mandante)) {
                    if (intval($Mandante->mandante) > 2) {

                        $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . $userpath;
                        if (intval($Mandante->mandante) == 9) {

                            $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . 'W' . $userpath;

                        }

                    }
                }
            }

            if ($pathPartner == '') {


                /* Asignación de $pathPartner basada en el valor de $Mandante->mandante. */
                $pathPartner = "1:colombia,S3";


                if ($Mandante->mandante == 1) {
                    $pathPartner = "1:ibet,S1";
                }


                /* Asigna rutas diferentes según el valor de 'mandante' en el objeto $Mandante. */
                if ($Mandante->mandante == 2) {
                    $pathPartner = "1:justbetja,S2";
                }


                if ($Mandante->mandante == 3) {
                    $pathPartner = "1:miravalle,S7";
                }


                /* asigna diferentes rutas según el valor de "mandante". */
                if ($Mandante->mandante == 4) {
                    $pathPartner = "1:casinogranpalacio,S20";
                }


                if ($Mandante->mandante == 5) {
                    $pathPartner = "1:casinointercontinental,S9";
                }


                /* asigna diferentes rutas a $pathPartner según el valor de $Mandante->mandante. */
                if ($Mandante->mandante == 6) {
                    $pathPartner = "1:netabet,S10";
                }


                if ($Mandante->mandante == 7) {
                    $pathPartner = "1:casinoastoria,S11";
                }


                /* Asignación de rutas a variables según el valor de mandante. */
                if ($Mandante->mandante == 8) {
                    $pathPartner = "1:ecuabet,S12";
                }

                if ($Mandante->mandante == 9) {
                    $pathPartner = "1:winbet,S13";
                }


                /* Asignación de ruta según condiciones de mandante y país del usuario. */
                if ($Mandante->mandante == 0 && $Usuario->paisId == '60') {
                    $pathPartner = "1:doradobet,S0-60";
                }

                if ($Mandante->mandante == '0') {
                    $pathPartner = "1:doradobet,S" . $Mandante->mandante . "-" . $Usuario->paisId;
                }

                /* Condicional que asigna un valor a $pathPartner si $Mandante->mandante es '8'. */
                if ($Mandante->mandante == '8') {
                    $pathPartner = "1:ecuabet,S" . $Mandante->mandante;
                }


            }


            /* Genera un array con datos de usuario y condiciones específicas para su identificación. */
            $IdUsuarioAltenar = $Usuario->usuarioId;
            if ((intval($Usuario->usuarioId) > 73758) || (in_array(intval($Usuario->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                $IdUsuarioAltenar = $Usuario->usuarioId . "U";
            }

            $dataD = array(
                "ExtUser" => array(
                    "LoginName" => $Usuario->nombre,
                    "Currency" => $moneda_default,
                    "Country" => $Pais->iso,
                    "ExternalUserId" => $IdUsuarioAltenar,
                    "AffiliationPath" => $Usuario->getAffiliationPathAltenar(),
                    "UserCode" => "3",
                    "FirstName" => $Registro->nombre1,
                    "LastName" => $Registro->apellido1,
                    "UserBalance" => "0"),
                "WalletCode" => $walletCode
            );


            /* Se prepara y envía una solicitud POST con datos JSON usando cURL. */
            $dataD = json_encode($dataD);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateUser/json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataD,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            /* Registra advertencias en el sistema y ejecuta una solicitud cURL. */
            $time = time();
            syslog(LOG_WARNING, " ITNBONUS :" . $time . " " . ($dataD));

            $response2 = curl_exec($curl);
            syslog(LOG_WARNING, " ITNBONUSR :" . $time . " " . ($response2));

            curl_close($curl);


            /* Decodifica una respuesta JSON y modifica un ID de usuario bajo ciertas condiciones. */
            $response2 = json_decode($response2);

            $IdUsuarioAltenar = $Usuario->usuarioId;
            if ((intval($Usuario->usuarioId) > 73758) || (in_array(intval($Usuario->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                $IdUsuarioAltenar = $Usuario->usuarioId . "U";
            }


            /* Crea un arreglo con datos para una transacción, incluyendo usuario, cartera y bono. */
            $dataD = array(
                "ExtUserId" => $IdUsuarioAltenar,
                "WalletCode" => $walletCode,
                "BonusCode" => "BONOGUA",
                "Deposit" => "2500"
            );


            /* Envía datos JSON mediante una solicitud POST a una API usando cURL. */
            $dataD = json_encode($dataD);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateBonusByCode/json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataD,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            /* registra advertencias en el syslog sobre tiempo y respuestas de cURL. */
            syslog(LOG_WARNING, " ITNBONUS :" . $time . " " . ($dataD));

            $response2 = curl_exec($curl);
            syslog(LOG_WARNING, " ITNBONUSR :" . $time . " " . ($response2));

            curl_close($curl);


            /* decodifica una cadena JSON en un objeto PHP. */
            $response2 = json_decode($response2);
        }

    }

    if ($Mandante->mandante == '13') {


        if (true) {

            /* configura rutas y variables relacionadas con un usuario y su moneda. */
            $pathPartner = $Mandante->pathItainment;
            $pathFixed = $Pais->codigoPath;
            $usermoneda = $moneda_default;
            $userpath = $pathFixed;

            $pathFixed = '2:Web ' . $usermoneda . ',' . $userpath;


            /* Condicionales para crear un path basado en el valor de 'mandante'. */
            if ($Mandante->mandante != '') {
                if (is_numeric($Mandante->mandante)) {
                    if (intval($Mandante->mandante) > 2) {

                        $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . $userpath;
                        if (intval($Mandante->mandante) == 9) {

                            $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . 'W' . $userpath;

                        }

                    }
                }
            }

            if ($pathPartner == '') {


                /* Asignación condicional de la variable $pathPartner según el valor de $Mandante->mandante. */
                $pathPartner = "1:colombia,S3";


                if ($Mandante->mandante == 1) {
                    $pathPartner = "1:ibet,S1";
                }


                /* asigna valores a $pathPartner según el valor de $Mandante->mandante. */
                if ($Mandante->mandante == 2) {
                    $pathPartner = "1:justbetja,S2";
                }


                if ($Mandante->mandante == 3) {
                    $pathPartner = "1:miravalle,S7";
                }


                /* Se asigna un valor a $pathPartner según el valor de $Mandante->mandante. */
                if ($Mandante->mandante == 4) {
                    $pathPartner = "1:casinogranpalacio,S20";
                }


                if ($Mandante->mandante == 5) {
                    $pathPartner = "1:casinointercontinental,S9";
                }


                /* Asigna diferentes valores a $pathPartner según el valor de $Mandante->mandante. */
                if ($Mandante->mandante == 6) {
                    $pathPartner = "1:netabet,S10";
                }


                if ($Mandante->mandante == 7) {
                    $pathPartner = "1:casinoastoria,S11";
                }


                /* asigna un valor a `$pathPartner` según el valor de `$Mandante->mandante`. */
                if ($Mandante->mandante == 8) {
                    $pathPartner = "1:ecuabet,S12";
                }

                if ($Mandante->mandante == 9) {
                    $pathPartner = "1:winbet,S13";
                }


                /* Establece un pathPartner condicional basado en mandante y país del usuario. */
                if ($Mandante->mandante == 0 && $Usuario->paisId == '60') {
                    $pathPartner = "1:doradobet,S0-60";
                }

                if ($Mandante->mandante == '0') {
                    $pathPartner = "1:doradobet,S" . $Mandante->mandante . "-" . $Usuario->paisId;
                }

                /* Condicional que asigna una ruta basada en el valor de 'mandante'. */
                if ($Mandante->mandante == '8') {
                    $pathPartner = "1:ecuabet,S" . $Mandante->mandante;
                }


            }


            /* construye un arreglo de datos de usuario con condiciones específicas. */
            $IdUsuarioAltenar = $Usuario->usuarioId;
            if ((intval($Usuario->usuarioId) > 73758) || (in_array(intval($Usuario->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                $IdUsuarioAltenar = $Usuario->usuarioId . "U";
            }

            $dataD = array(
                "ExtUser" => array(
                    "LoginName" => $Usuario->nombre,
                    "Currency" => $moneda_default,
                    "Country" => $Pais->iso,
                    "ExternalUserId" => $IdUsuarioAltenar,
                    "AffiliationPath" => $Usuario->getAffiliationPathAltenar(),
                    "UserCode" => "3",
                    "FirstName" => $Registro->nombre1,
                    "LastName" => $Registro->apellido1,
                    "UserBalance" => "0"),
                "WalletCode" => $walletCode
            );


            /* Envía datos JSON a una API usando cURL en PHP para crear un usuario. */
            $dataD = json_encode($dataD);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateUser/json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataD,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            /* Registro de advertencias en el sistema con tiempo y respuesta de cURL. */
            $time = time();
            syslog(LOG_WARNING, " ITNBONUS :" . $time . " " . ($dataD));

            $response2 = curl_exec($curl);
            syslog(LOG_WARNING, " ITNBONUSR :" . $time . " " . ($response2));

            curl_close($curl);


            /* Código que modifica el identificador de usuario basado en condiciones específicas. */
            $response2 = json_decode($response2);


            $IdUsuarioAltenar = $Usuario->usuarioId;
            if ((intval($Usuario->usuarioId) > 73758) || (in_array(intval($Usuario->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                $IdUsuarioAltenar = $Usuario->usuarioId . "U";
            }

            /* Se crea un array PHP con información sobre un usuario y un depósito. */
            $dataD = array(
                "ExtUserId" => $IdUsuarioAltenar,
                "WalletCode" => $walletCode,
                "BonusCode" => "BONUSTRI",
                "Deposit" => "5000"
            );


            /* envía datos JSON a una API mediante una solicitud POST usando cURL. */
            $dataD = json_encode($dataD);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateBonusByCode/json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataD,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            /* registra advertencias y ejecuta una solicitud cURL para obtener datos. */
            syslog(LOG_WARNING, " ITNBONUS :" . $time . " " . ($dataD));

            $response2 = curl_exec($curl);
            syslog(LOG_WARNING, " ITNBONUSR :" . $time . " " . ($response2));

            curl_close($curl);


            /* Se decodifica una respuesta JSON a un objeto en PHP. */
            $response2 = json_decode($response2);
        }

    }


    try {

        /* obtiene información del usuario y la codifica en base64. */
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $jsonServer = json_encode($_SERVER);
        $serverCodif = base64_encode($jsonServer);


        $ismobile = '';


        /* detecta dispositivos móviles mediante expresiones regulares en el user agent. */
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', mb_substr($useragent, 0, 4))) {

            $ismobile = '1';

        }
        //Detect special conditions devices
        $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");

        /* Detecta si el usuario está utilizando un dispositivo móvil específico a través del User Agent. */
        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");


        //do something with this information
        if ($iPod || $iPhone) {
            $ismobile = '1';
        } else if ($iPad) {
            /* verifica si es un iPad y establece una variable para dispositivos móviles. */

            $ismobile = '1';
        } else if ($Android) {
            /* establece `$ismobile` en '1' si el dispositivo es Android. */

            $ismobile = '1';
        }


        //exec("php -f " . __DIR__ . "/../../src/integrations/crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "REGISTROCRM" . " " . $Usuario->usuarioId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");
    } catch (Exception $e) {
        /* Bloque que captura excepciones en PHP, permitiendo manejar errores sin interrumpir el flujo. */


    }


    try {


        if ($site_id == "17" || $site_id == "19") {

            /* Se define un array para almacenar detalles de un depósito financiero. */
            $detalles = array(
                "Depositos" => 0,
                "DepositoEfectivo" => false,
                "MetodoPago" => 0,
                "ValorDeposito" => 0,
                "PaisPV" => 0,
                "DepartamentoPV" => 0,
                "CiudadPV" => 0,
                "PuntoVenta" => 0,
                "PaisUSER" => $countryResident_id->Id,
                "DepartamentoUSER" => 0,
                "CiudadUSER" => $city_id->Id,
                "MonedaUSER" => $moneda_default,
                "CodePromo" => ''
            );


            /* agrega un bono utilizando detalles, usuario e información de transacción. */
            $detalles = json_decode(json_encode($detalles));

            $BonoInterno = new BonoInterno();
            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
            $Transaction = $BonoInternoMySqlDAO->getTransaction();


            $responseBonus = $BonoInterno->agregarBonoEstadoQ("", $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction, false, 'REGISTRO');

            /* confirma una transacción si se ha ganado un bono. */
            if ($responseBonus->WinBonus) {
                $Transaction->commit();
            }

        }
    } catch (Exception $e) {
        /* Bloque para capturar y manejar excepciones en PHP sin realizar acciones. */


    }


} else {
    /* lanza excepciones si un documento ya existe en el sistema. */

    if ($site_id == "14") {
        throw new Exception("O cartão já existe: " . $docnumber, "19000");

    }
    throw new Exception("La cédula ya existe " . $docnumber, "19000");

    $response["data"] = array(
        "result" => "-1123"

    );
}



/**
 * Genera una clave de ticket de longitud especificada.
 *
 * @param int $length La longitud de la clave a generar.
 * @return string La clave generada.
 */
function GenerarClaveTicket22($length)
{
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}