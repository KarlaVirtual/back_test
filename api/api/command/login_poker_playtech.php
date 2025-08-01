<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Usuario;

/**
 * Maneja el proceso de login y redirección de usuarios para el cliente nativo de Playtech.
 *
 * @param object $json Objeto JSON que contiene los parámetros de entrada.
 * @param object $json->params Parámetros de entrada.
 * @param string $json->params->username Nombre de usuario.
 * @param string $json->params->password Contraseña del usuario.
 * @param int $json->params->site_id ID del sitio.
 * @param int $json->rid ID de la solicitud.

 * @return array Arreglo de respuesta para la solicitud JSON.
 *  -code:int Código de respuesta.
 *  -rid:int ID de la solicitud.
 *  -data:array Datos de la respuesta.
 *    -auth_token:string Token de autenticación.
 *    -user_id:int ID del usuario.
 *
 * @throws Exception Captura excepciones en PHP para manejar errores sin interrumpir la ejecución del programa.
 */

/** Recepción de parámetros */

/* extrae y sanitiza parámetros de un objeto JSON para su uso. */
$params = $json->params;
$username = $params->username;
$password = $params->password;
$site_id = $params->site_id;

//Sanitización de los parámetros
$ConfigurationEnvironment = new ConfigurationEnvironment();
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
    'ף' => 'p', 'פ' => 'p', 'ץ' => 'C', 'צ' => 'c', 'ק' => 'q', 'ר' => 'r', 'ש' => 'w', 'ת' => 't');


/* limpia y transforma datos de usuario antes de iniciar sesión. */
$username = strtr($username, $unwanted_array);
$username = $ConfigurationEnvironment->DepurarCaracteres($username);
$password = $ConfigurationEnvironment->DepurarCaracteres($password);
$site_id = intval($site_id);

/** Ejecutando Login */
$Usuario = new Usuario();

/* gestiona la respuesta de un inicio de sesión, incluyendo autenticación y balance. */
$loginResponse = $Usuario->loginPlaytech($username, $password, $site_id);

$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = [
    'auth_token' => $loginResponse->token,
    'user_id' => $loginResponse->usumandanteId,
    'balance' => $loginResponse->balance
];

?>