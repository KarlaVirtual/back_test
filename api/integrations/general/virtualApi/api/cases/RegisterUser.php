<?php

/**
 * Recurso para registrar un usuario a través de WhatsApp en el sistema.
 * @author David Álvarez <juan.alvarez@virtualsoft.tech>
 * @since 2025-05-26
 * 
 * @param object $json Datos del usuario en formato JSON con la siguiente estructura:
 * @param object $json->userInfo Información del usuario
 * @param string $json->userInfo->firstName Nombre del usuario
 * @param string $json->userInfo->middleName Segundo nombre del usuario
 * @param string $json->userInfo->lastName Apellido paterno
 * @param string $json->userInfo->secondLastName Apellido materno
 * @param string $json->userInfo->documentTypeId Tipo de documento de identidad
 * @param string $json->userInfo->documentNumber Número de documento
 * @param string $json->userInfo->birthDate Fecha de nacimiento (YYYY-MM-DD)
 * @param int    $json->userInfo->phone Número de teléfono
 * @param string $json->userInfo->address Dirección del usuario
 * @param string $json->userInfo->email Correo electrónico
 * @param string $json->userInfo->password Contraseña del usuario
 * @param string $json->userInfo->promoCode Código de promoción (opcional)
 * @param string $json->salePointId ID del punto de venta encargado del registro
 * @param string $json->token Token del punto de venta encargado del registro
 */

use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaAsociada;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\PaisMoneda;
use Backend\dto\Registro;
use Backend\dto\SitioTracking;
use Backend\dto\Template;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioRestriccion;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CuentaAsociadaMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SitioTrackingMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;

$json = file_get_contents('php://input');
$json = json_decode($json);

// Validación de campos requeridos
$camposRequeridos = ['firstName', 'lastName', 'documentTypeId', 'documentNumber', 'birthDate', 'phone', 'address', 'email', 'password'];
$camposFaltantes = [];

foreach ($camposRequeridos as $campo) {
  if (!isset($json->userInfo->$campo) || empty($json->userInfo->$campo)) {
    $camposFaltantes[] = $campo;
  }
}

if (!empty($camposFaltantes)) {
  http_response_code(400);
  echo json_encode([
    'error' => true,
    'message' => 'Fields missing',
    'fields' => $camposFaltantes
  ]);
  exit;
}

$salePointId = $json->salePointId;
$token = $json->token;

$rules = array();

/* Se crean reglas de filtrado para una consulta de base de datos. */
array_push($rules, array("field" => "usuario.usuario_id", "data" => $salePointId, "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte un filtro a formato JSON y define una consulta SQL. */
$filters = json_encode($filtro);

$select = " usuario.mandante,usuario.pais_id,usuario_token_interno.* ";

/* Se obtiene datos de usuario, se decodifica en JSON y se establece respuesta sin errores. */
$UsuarioTokenInterno = new UsuarioTokenInterno();
$data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", 0, 1, $filters, true);

$data = json_decode($data);

if (count($data->data) == 0) {
  $response["code"] = 300170;
  $response["error"] = true;
  $response["message"] = "Incorrect authentication";
  $response["data"] = []; 
  return;
}

$site_id = $data->data[0]->{'usuario.mandante'};

$ConfigurationEnvironment = new ConfigurationEnvironment();
$userInfo = $json->userInfo;
$giftType = $json->giftType;

$estadoUsuarioDefault = 'A';

/* Asigna valores de $userInfo a variables específicas para su posterior uso. */
$usuidReferente = null;
$address = $userInfo->address;
$address = $ConfigurationEnvironment->DepurarCaracteres($address);
$birthDate = $userInfo->birthDate;

//Pais de residencia
$countryId = $data->data[0]->{'usuario.pais_id'};

/* Se extraen datos de un objeto de usuario, como límite de depósito y datos personales. */
$firstName = $userInfo->firstName;
$middleName = $userInfo->middleName;
$lastName = $userInfo->lastName;
$secondLastName = $userInfo->secondLastName;
$password = $userInfo->password;
$phone = $userInfo->phone;
$gender = $userInfo->gender ?? 'M';
$documentNumber = $userInfo->documentNumber;
$documentTypeId = $userInfo->documentTypeId;
$promoCode = $userInfo->promoCode;

$name = $firstName . " " . $middleName . " " . $lastName . " " . $secondLastName;
$passwordActive = GenerarClaveTicket(15);

$lang = 'ES';
$origen = 0;
$origen_fondos = "0";
$origen_fondosString = '';

/* Validación de longitud del número de teléfono */
if (strlen($phone) != 9) {
  $response["code"] = 300167;
  $response["error"] = true;
  $response["message"] = "The phone number must have 9 digits";
  $response["data"] = [];
  return;
}


if (strpos($_SERVER['HTTP_REFERER'], "acropolis") !== FALSE) {
  $origen = 2;
}

$email = $userInfo->email;
if (strpos($email, '\ud') !== false) {
  $email = json_decode('"' . $email . '"');
}

/* limpia y depura caracteres de dos direcciones de correo electrónico. */
$email = trim($email);
$email = $ConfigurationEnvironment->DepurarCaracteres($email);
$email = $ConfigurationEnvironment->remove_emoji($email);
$unwanted_array = array(
  '©' => 'c',
  '®' => 'r',
  '̊' => '',
  '̧' => '',
  '̨' => '',
  '̄' => '',
  '̱' => '',
  'Á' => 'a',
  'á' => 'a',
  'À' => 'a',
  'à' => 'a',
  'Ă' => 'a',
  'ă' => 'a',
  'ắ' => 'a',
  'Ắ' => 'A',
  'Ằ' => 'A',
  'ằ' => 'a',
  'ẵ' => 'a',
  'Ẵ' => 'A',
  'ẳ' => 'a',
  'Ẳ' => 'A',
  'Â' => 'a',
  'â' => 'a',
  'ấ' => 'a',
  'Ấ' => 'A',
  'ầ' => 'a',
  'Ầ' => 'a',
  'ẩ' => 'a',
  'Ẩ' => 'A',
  'Ǎ' => 'a',
  'ǎ' => 'a',
  'Å' => 'a',
  'å' => 'a',
  'Ǻ' => 'a',
  'ǻ' => 'a',
  'Ä' => 'a',
  'ä' => 'a',
  'ã' => 'a',
  'Ã' => 'A',
  'Ą' => 'a',
  'ą' => 'a',
  'Ā' => 'a',
  'ā' => 'a',
  'ả' => 'a',
  'Ả' => 'a',
  'Ạ' => 'A',
  'ạ' => 'a',
  'ặ' => 'a',
  'Ặ' => 'A',
  'ậ' => 'a',
  'Ậ' => 'A',
  'Æ' => 'ae',
  'æ' => 'ae',
  'Ǽ' => 'ae',
  'ǽ' => 'ae',
  'ẫ' => 'a',
  'Ẫ' => 'A',
  'Ć' => 'c',
  'ć' => 'c',
  'Ĉ' => 'c',
  'ĉ' => 'c',
  'Č' => 'c',
  'č' => 'c',
  'Ċ' => 'c',
  'ċ' => 'c',
  'Ç' => 'c',
  'ç' => 'c',
  'Ď' => 'd',
  'ď' => 'd',
  'Ḑ' => 'D',
  'ḑ' => 'd',
  'Đ' => 'd',
  'đ' => 'd',
  'Ḍ' => 'D',
  'ḍ' => 'd',
  'Ḏ' => 'D',
  'ḏ' => 'd',
  'ð' => 'd',
  'Ð' => 'D',
  'É' => 'e',
  'é' => 'e',
  'È' => 'e',
  'è' => 'e',
  'Ĕ' => 'e',
  'ĕ' => 'e',
  'ê' => 'e',
  'ế' => 'e',
  'Ế' => 'E',
  'ề' => 'e',
  'Ề' => 'E',
  'Ě' => 'e',
  'ě' => 'e',
  'Ë' => 'e',
  'ë' => 'e',
  'Ė' => 'e',
  'ė' => 'e',
  'Ę' => 'e',
  'ę' => 'e',
  'Ē' => 'e',
  'ē' => 'e',
  'ệ' => 'e',
  'Ệ' => 'E',
  'Ə' => 'e',
  'ə' => 'e',
  'ẽ' => 'e',
  'Ẽ' => 'E',
  'ễ' => 'e',
  'Ễ' => 'E',
  'ể' => 'e',
  'Ể' => 'E',
  'ẻ' => 'e',
  'Ẻ' => 'E',
  'ẹ' => 'e',
  'Ẹ' => 'E',
  'ƒ' => 'f',
  'Ğ' => 'g',
  'ğ' => 'g',
  'Ĝ' => 'g',
  'ĝ' => 'g',
  'Ǧ' => 'G',
  'ǧ' => 'g',
  'Ġ' => 'g',
  'ġ' => 'g',
  'Ģ' => 'g',
  'ģ' => 'g',
  'H̲' => 'H',
  'h̲' => 'h',
  'Ĥ' => 'h',
  'ĥ' => 'h',
  'Ȟ' => 'H',
  'ȟ' => 'h',
  'Ḩ' => 'H',
  'ḩ' => 'h',
  'Ħ' => 'h',
  'ħ' => 'h',
  'Ḥ' => 'H',
  'ḥ' => 'h',
  'Ỉ' => 'I',
  'Í' => 'i',
  'í' => 'i',
  'Ì' => 'i',
  'ì' => 'i',
  'Ĭ' => 'i',
  'ĭ' => 'i',
  'Î' => 'i',
  'î' => 'i',
  'Ǐ' => 'i',
  'ǐ' => 'i',
  'Ï' => 'i',
  'ï' => 'i',
  'Ḯ' => 'I',
  'ḯ' => 'i',
  'Ĩ' => 'i',
  'ĩ' => 'i',
  'İ' => 'i',
  'Į' => 'i',
  'į' => 'i',
  'Ī' => 'i',
  'ī' => 'i',
  'ỉ' => 'I',
  'Ị' => 'I',
  'ị' => 'i',
  'Ĳ' => 'ij',
  'ĳ' => 'ij',
  'ı' => 'i',
  'Ĵ' => 'j',
  'ĵ' => 'j',
  'Ķ' => 'k',
  'ķ' => 'k',
  'Ḵ' => 'K',
  'ḵ' => 'k',
  'Ĺ' => 'l',
  'ĺ' => 'l',
  'Ľ' => 'l',
  'ľ' => 'l',
  'Ļ' => 'l',
  'ļ' => 'l',
  'Ł' => 'l',
  'ł' => 'l',
  'Ŀ' => 'l',
  'ŀ' => 'l',
  'Ń' => 'n',
  'ń' => 'n',
  'Ň' => 'n',
  'ň' => 'n',
  'Ñ' => 'N',
  'ñ' => 'n',
  'Ņ' => 'n',
  'ņ' => 'n',
  'Ṇ' => 'N',
  'ṇ' => 'n',
  'Ŋ' => 'n',
  'ŋ' => 'n',
  'Ó' => 'o',
  'ó' => 'o',
  'Ò' => 'o',
  'ò' => 'o',
  'Ŏ' => 'o',
  'ŏ' => 'o',
  'Ô' => 'o',
  'ô' => 'o',
  'ố' => 'o',
  'Ố' => 'O',
  'ồ' => 'o',
  'Ồ' => 'O',
  'ổ' => 'o',
  'Ổ' => 'O',
  'Ǒ' => 'o',
  'ǒ' => 'o',
  'Ö' => 'o',
  'ö' => 'o',
  'Ő' => 'o',
  'ő' => 'o',
  'Õ' => 'o',
  'õ' => 'o',
  'Ø' => 'o',
  'ø' => 'o',
  'Ǿ' => 'o',
  'ǿ' => 'o',
  'Ǫ' => 'O',
  'ǫ' => 'o',
  'Ǭ' => 'O',
  'ǭ' => 'o',
  'Ō' => 'o',
  'ō' => 'o',
  'ỏ' => 'o',
  'Ỏ' => 'O',
  'Ơ' => 'o',
  'ơ' => 'o',
  'ớ' => 'o',
  'Ớ' => 'O',
  'ờ' => 'o',
  'Ờ' => 'O',
  'ở' => 'o',
  'Ở' => 'O',
  'ợ' => 'o',
  'Ợ' => 'O',
  'ọ' => 'o',
  'Ọ' => 'O',
  'ọ' => 'o',
  'Ọ' => 'O',
  'ộ' => 'o',
  'Ộ' => 'O',
  'ỗ' => 'o',
  'Ỗ' => 'O',
  'ỡ' => 'o',
  'Ỡ' => 'O',
  'Œ' => 'oe',
  'œ' => 'oe',
  'ĸ' => 'k',
  'Ŕ' => 'r',
  'ŕ' => 'r',
  'Ř' => 'r',
  'ř' => 'r',
  'ṙ' => 'r',
  'Ŗ' => 'r',
  'ŗ' => 'r',
  'Ṛ' => 'R',
  'ṛ' => 'r',
  'Ṟ' => 'R',
  'ṟ' => 'r',
  'S̲' => 'S',
  's̲' => 's',
  'Ś' => 's',
  'ś' => 's',
  'Ŝ' => 's',
  'ŝ' => 's',
  'Š' => 's',
  'š' => 's',
  'Ş' => 's',
  'ş' => 's',
  'Ṣ' => 'S',
  'ṣ' => 's',
  'Ș' => 'S',
  'ș' => 's',
  'ſ' => 'z',
  'ß' => 'ss',
  'Ť' => 't',
  'ť' => 't',
  'Ţ' => 't',
  'ţ' => 't',
  'Ṭ' => 'T',
  'ṭ' => 't',
  'Ț' => 'T',
  'ț' => 't',
  'Ṯ' => 'T',
  'ṯ' => 't',
  '™' => 'tm',
  'Ŧ' => 't',
  'ŧ' => 't',
  'Ú' => 'u',
  'ú' => 'u',
  'Ù' => 'u',
  'ù' => 'u',
  'Ŭ' => 'u',
  'ŭ' => 'u',
  'Û' => 'u',
  'û' => 'u',
  'Ǔ' => 'u',
  'ǔ' => 'u',
  'Ů' => 'u',
  'ů' => 'u',
  'Ü' => 'u',
  'ü' => 'u',
  'Ǘ' => 'u',
  'ǘ' => 'u',
  'Ǜ' => 'u',
  'ǜ' => 'u',
  'Ǚ' => 'u',
  'ǚ' => 'u',
  'Ǖ' => 'u',
  'ǖ' => 'u',
  'Ű' => 'u',
  'ű' => 'u',
  'Ũ' => 'u',
  'ũ' => 'u',
  'Ų' => 'u',
  'ų' => 'u',
  'Ū' => 'u',
  'ū' => 'u',
  'Ư' => 'u',
  'ư' => 'u',
  'ứ' => 'u',
  'Ứ' => 'U',
  'ừ' => 'u',
  'Ừ' => 'U',
  'ử' => 'u',
  'Ử' => 'U',
  'ự' => 'u',
  'Ự' => 'U',
  'ụ' => 'u',
  'Ụ' => 'U',
  'ủ' => 'u',
  'Ủ' => 'U',
  'ữ' => 'u',
  'Ữ' => 'U',
  'Ŵ' => 'w',
  'ŵ' => 'w',
  'Ý' => 'y',
  'ý' => 'y',
  'ỳ' => 'y',
  'Ỳ' => 'Y',
  'Ŷ' => 'y',
  'ŷ' => 'y',
  'ÿ' => 'y',
  'Ÿ' => 'y',
  'ỹ' => 'y',
  'Ỹ' => 'Y',
  'ỷ' => 'y',
  'Ỷ' => 'Y',
  'Z̲' => 'Z',
  'z̲' => 'z',
  'Ź' => 'z',
  'ź' => 'z',
  'Ž' => 'z',
  'ž' => 'z',
  'Ż' => 'z',
  'ż' => 'z',
  'Ẕ' => 'Z',
  'ẕ' => 'z',
  'þ' => 'p',
  'ŉ' => 'n',
  'А' => 'a',
  'а' => 'a',
  'Б' => 'b',
  'б' => 'b',
  'В' => 'v',
  'в' => 'v',
  'Г' => 'g',
  'г' => 'g',
  'Ґ' => 'g',
  'ґ' => 'g',
  'Д' => 'd',
  'д' => 'd',
  'Е' => 'e',
  'е' => 'e',
  'Ё' => 'jo',
  'ё' => 'jo',
  'Є' => 'e',
  'є' => 'e',
  'Ж' => 'zh',
  'ж' => 'zh',
  'З' => 'z',
  'з' => 'z',
  'И' => 'i',
  'и' => 'i',
  'І' => 'i',
  'і' => 'i',
  'Ї' => 'i',
  'ї' => 'i',
  'Й' => 'j',
  'й' => 'j',
  'К' => 'k',
  'к' => 'k',
  'Л' => 'l',
  'л' => 'l',
  'М' => 'm',
  'м' => 'm',
  'Н' => 'n',
  'н' => 'n',
  'О' => 'o',
  'о' => 'o',
  'П' => 'p',
  'п' => 'p',
  'Р' => 'r',
  'р' => 'r',
  'С' => 's',
  'с' => 's',
  'Т' => 't',
  'т' => 't',
  'У' => 'u',
  'у' => 'u',
  'Ф' => 'f',
  'ф' => 'f',
  'Х' => 'h',
  'х' => 'h',
  'Ц' => 'c',
  'ц' => 'c',
  'Ч' => 'ch',
  'ч' => 'ch',
  'Ш' => 'sh',
  'ш' => 'sh',
  'Щ' => 'sch',
  'щ' => 'sch',
  'Ъ' => '-',
  'ъ' => '-',
  'Ы' => 'y',
  'ы' => 'y',
  'Ь' => '-',
  'ь' => '-',
  'Э' => 'je',
  'э' => 'je',
  'Ю' => 'ju',
  'ю' => 'ju',
  'Я' => 'ja',
  'я' => 'ja',
  'א' => 'a',
  'ב' => 'b',
  'ג' => 'g',
  'ד' => 'd',
  'ה' => 'h',
  'ו' => 'v',
  'ז' => 'z',
  'ח' => 'h',
  'ט' => 't',
  'י' => 'i',
  'ך' => 'k',
  'כ' => 'k',
  'ל' => 'l',
  'ם' => 'm',
  'מ' => 'm',
  'ן' => 'n',
  'נ' => 'n',
  'ס' => 's',
  'ע' => 'e',
  'ף' => 'p',
  'פ' => 'p',
  'ץ' => 'C',
  'צ' => 'c',
  'ק' => 'q',
  'ר' => 'r',
  'ש' => 'w',
  'ת' => 't'
);

/* reemplaza caracteres no deseados y filtra caracteres no ASCII en correos electrónicos. */
$email = strtr($email, $unwanted_array);

$email = preg_replace('/[^(\x20-\x7F)]*/', '', $email);

/* Validación del formato del email */
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $response["code"] = 300166;
  $response["error"] = true;
  $response["message"] = "Invalid format. An email is expected";
  $response["data"] = [];
  return;
}

/* depura una contraseña y define una función para validar usuarios restringidos. */
$password = $ConfigurationEnvironment->DepurarCaracteres($password);

$Mandante = new Mandante($site_id);

if ($countryId != '' && $countryId != '0' && $countryId != null) {

  $Pais = new Pais($countryId);
  $PaisMoneda = new PaisMoneda($countryId);
  $moneda_default = $PaisMoneda->moneda;

  $Pais = new Pais($countryId);
  $PaisMandante = new PaisMandante('', $site_id, $Pais->paisId);

  if ($PaisMandante->estado != 'A') {
    $response["code"] = 100001;
    $response["error"] = true;
    $response["message"] = "Inusual Detected";
    $response["data"] = [];
    return;
  }
  $moneda_default = $PaisMandante->moneda;
}

try {
  $Clasificador = new Clasificador("", "REGISTERACTIVATION");

  $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $Clasificador->getClasificadorId(), $Pais->paisId, 'A');

  $estadoUsuarioDefault = (intval($MandanteDetalle->getValor()) == 1) ? "A" : "I";
} catch (Exception $e) {
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
    $response["code"] = 300163;
    $response["error"] = true;
    $response["message"] = "O e-mail já está cadastrado";
    $response["data"] = [];
    return;
  }
  if ($site_id == "17") {
    $response["code"] = 300163;
    $response["error"] = true;
    $response["message"] = "O e-mail já está cadastrado";
    $response["data"] = [];
    return;
  }
  $response["code"] = 300163;
  $response["error"] = true;
  $response["message"] = "El correo ya está registrado";
  $response["data"] = [];
  return;
}

/* verifica si el celular existe antes de continuar con el proceso. */
$seguirCelular = false;

/* sanitiza y establece un número de documento en un registro. */
$Registro = new Registro();
$documentNumber = mb_substr($documentNumber, 0, 19);
$documentNumber = preg_replace('/[^(\x20-\x7F)]*/', '', $documentNumber);
$phone = preg_replace('/[^(\x20-\x7F)]*/', '', $phone);
$promoCode = preg_replace('/[^(\x20-\x7F)]*/', '', $promoCode);

$Registro->setCedula($documentNumber);

/* Define variables y establece condiciones basadas en el valor del mandante. */
$Registro->setCelular($phone);
$Registro->setMandante($Mandante->mandante);

if ($Mandante->mandante == 13) {
  $validacionCedula = true;
} else {
  /* Verifica si $documentNumber no está vacío y si la cédula no existe. */
  if ($documentNumber != "") {
    if (!$Registro->existeCedula()) {
      $validacionCedula = true;
    }
  }
}

if ($validacionCedula) {

  /* verifica si el celular existe antes de continuar con el proceso. */
  $validacionTelefono = true;

  if ($phone != "") {
    if (!$Registro->existeCelular()) {
      $validacionTelefono = false;
    }
  }

  /* Verifica existencia de celular y gestiona consecutivos para usuarios en la base de datos. */
  if ($validacionTelefono) {
    $response["code"] = 300165;
    $response["error"] = true;
    $response["message"] = "El número de celular ya está registrado." . $phone;
    $response["data"] = [];
    return;
  }

  $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
  $BonoInterno = new BonoInterno();

  /* Se obtienen Transacciones y se inicializan variables para premios máximos. */
  $Transaction = $BonoInternoMySqlDAO->getTransaction();

  /* Consulta y verifica un código promocional activo en la base de datos. */
  if ($promoCode != "") {

    $apmin2Sql = "select codpromocional_id,usuario_id,link_id from codigo_promocional where codigo='" . $promoCode . "' AND estado='A'  AND mandante='" . $Mandante->mandante . "'";
    $apmin2_RS = $BonoInterno->execQuery($Transaction, $apmin2Sql);

    if (($apmin2_RS[0]->{'codigo_promocional.codpromocional_id'} != "")) {
      if ($linkid == '' || $linkid == null || $linkid == '0') {
        $afiliador = $apmin2_RS[0]->{'codigo_promocional.usuario_id'};
      }
      $codigoBD = $apmin2_RS[0]->{'codigo_promocional.codpromocional_id'};
    }
  }

  /* Genera un token y obtiene la dirección IP del usuario almacenada en una variable. */
  $token_itainment = GenerarClaveTicket22(12);

  $dir_ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
  $dir_ip = explode(",", $dir_ip)[0];

  $Usuario->login = $email;
  $Usuario->nombre = $name;
  $Usuario->estado = $estadoUsuarioDefault ?? 'I';
  $Usuario->fechaUlt = date('Y-m-d H:i:s');
  $Usuario->claveTv = '';
  $Usuario->estadoAnt = 'I';
  $Usuario->intentos = 0;
  $Usuario->estadoEsp = $estadoUsuarioDefault ?? 'I';
  $Usuario->observ = '';
  $Usuario->dirIp = $dir_ip;
  $Usuario->eliminado = 'N';
  $Usuario->mandante = $Mandante->mandante;
  $Usuario->usucreaId = '0';
  $Usuario->usumodifId = '0';
  $Usuario->claveCasino = '';
  $Usuario->tokenItainment = $token_itainment;
  $Usuario->fechaClave = '';
  $Usuario->retirado = 'N';
  $Usuario->fechaRetiro = '';
  $Usuario->horaRetiro = '';
  $Usuario->usuretiroId = '0';
  $Usuario->bloqueoVentas = 'N';
  $Usuario->infoEquipo = '';
  $Usuario->estadoJugador = 'NN';
  $Usuario->tokenCasino = '';
  $Usuario->sponsorId = 0;
  $Usuario->verifCorreo = 'N';
  $Usuario->paisId = $countryId;
  $Usuario->moneda = $moneda_default;
  $Usuario->idioma = $lang;
  $Usuario->permiteActivareg = 'N';
  $Usuario->test = 'N';
  $Usuario->tiempoLimitedeposito = 0;
  $Usuario->tiempoAutoexclusion = 0;
  $Usuario->cambiosAprobacion = 'S';
  $Usuario->timezone = '-5';
  $Usuario->puntoventaId = 0;
  $Usuario->usucreaId = 0;
  $Usuario->usumodifId = 0;
  $Usuario->usuretiroId = 0;
  $Usuario->sponsorId = (0);
  $Usuario->puntoventaId = 0;
  $Usuario->fechaCrea = date('Y-m-d H:i:s');
  $Usuario->origen = $origen;
  $Usuario->fechaActualizacion = $Usuario->fechaCrea;
  $Usuario->documentoValidado = "I";
  $Usuario->fechaDocvalido = $Usuario->fechaCrea;
  $Usuario->usuDocvalido = 0;
  $Usuario->estadoValida = 'N';
  $Usuario->usuvalidaId = 0;
  $Usuario->fechaValida = date('Y-m-d H:i:s');
  $Usuario->contingencia = 'I';
  $Usuario->contingenciaDeportes = 'I';
  $Usuario->contingenciaCasino = 'I';
  $Usuario->contingenciaCasvivo = 'I';
  $Usuario->contingenciaVirtuales = 'I';
  $Usuario->contingenciaPoker = 'I';
  $Usuario->restriccionIp = 'I';
  $Usuario->ubicacionLongitud = '';
  $Usuario->ubicacionLatitud = '';
  $Usuario->usuarioIp = '';
  $Usuario->tokenGoogle = "I";
  $Usuario->tokenLocal = "I";
  $Usuario->saltGoogle = '';
  $Usuario->skype = '';
  $Usuario->plataforma = 0;

  $Usuario->fechaActualizacion = $Usuario->fechaCrea;
  $Usuario->documentoValidado = "I";
  $Usuario->fechaDocvalido = '1970-01-01 00:00:00';


  $Usuario->usuDocvalido = 0;
  $Usuario->equipoId = intval($team);

  if ($Mandante->mandante == 14) {
    $Usuario->verifCelular = 'S';
    $Usuario->fechaVerifCelular = date('Y-m-d H:i:s');
  }

  if ($Usuario->mandante == 14 && date('Y-m-d H:i:s') >= '2023-05-27 00:00:00') {
    $Usuario->contingenciaRetiro = 'A';
  }

  if ($Usuario->mandante == 0 && $Usuario->paisId == 46 && date('Y-m-d H:i:s') >= '2023-05-29 00:00:00' && date('Y-m-d H:i:s') <= '2023-05-31 23:59:59') {
    $Usuario->contingenciaRetiro = 'A';
  }

  if ($Usuario->mandante == 0 && $Usuario->paisId == 46 && date('Y-m-d H:i:s') >= '2024-01-17 00:00:00') {
    $Usuario->contingenciaRetiro = 'A';
  }

  if ($Usuario->mandante == 0 && $Usuario->paisId == 66 && date('Y-m-d H:i:s') >= '2024-01-17 00:00:00') {
    $Usuario->contingenciaRetiro = 'A';
  }

  if (
    $Usuario->mandante == 0 && $Usuario->paisId == 2
    && date('Y-m-d H:i:s') >= '2023-04-01 00:00:00'
    && date('Y-m-d H:i:s') <= '2023-04-02 23:59:59'
  ) {
    $Usuario->contingenciaRetiro = 'A';
  }

  $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
  $UsuarioMySqlDAO->insert($Usuario);

  $consecutivo_usuario = $Usuario->usuarioId;

  /* Se establece información en un objeto Registro, incluyendo nombre, email y estado. */
  $Registro->setNombre($name);
  $Registro->setEmail($email);
  $Registro->setClaveActiva($passwordActive);
  $Registro->setEstado($estadoUsuarioDefault ?? 'I');
  $Registro->usuarioId = $consecutivo_usuario;
  $Registro->setCelular($phone);

  /* Código que inicializa valores de créditos y asigna un ID de ciudad al registro. */
  $Registro->setCreditosBase(0);
  $Registro->setCreditos(0);
  $Registro->setCreditosAnt(0);
  $Registro->setCreditosBaseAnt(0);
  $Registro->setCiudadId(0);

  /* establece propiedades de un objeto Registro y normaliza el nombre. */
  $Registro->setCasino(0);
  $Registro->setCasinoBase(0);
  $Registro->setMandante($Mandante->mandante);
  $firstName = $ConfigurationEnvironment->DepurarCaracteres($firstName);
  $firstName = mb_substr($firstName, 0, 19);

  $Registro->setNombre1($firstName);

  /* Se depuran y limitan caracteres del segundo nombre y apellido antes de asignar. */
  $middleName = $ConfigurationEnvironment->DepurarCaracteres($middleName);

  $middleName = mb_substr($middleName, 0, 19);

  $Registro->setNombre2($middleName);

  $lastName = $ConfigurationEnvironment->DepurarCaracteres($lastName);

  /* recorta y depura apellidos para ajustarlos a un límite de caracteres. */
  $lastName = mb_substr($lastName, 0, 19);
  $Registro->setApellido1($lastName);

  $secondLastName = $ConfigurationEnvironment->DepurarCaracteres($secondLastName);

  $secondLastName = mb_substr($secondLastName, 0, 19);

  /* Código para establecer atributos de un objeto de registro. */
  $Registro->setApellido2($secondLastName);

  $Registro->setSexo($gender);
  $Registro->setTipoDoc($documentTypeId);
  $Registro->setDireccion($address);
  $Registro->setTelefono($phone);

  /* asigna valores a propiedades de un objeto $Registro. */
  $Registro->setCiudnacimId(0);
  $Registro->setNacionalidadId($countryId);
  $Registro->setDirIp($dir_ip);
  $Registro->setOcupacionId(0);
  $Registro->setRangoingresoId(0);
  $Registro->setOrigenfondosId($origen_fondos);

  /* establece múltiples propiedades en un objeto llamado $Registro. */
  $Registro->setOrigenFondos($origen_fondosString);
  $Registro->setPaisnacimId(0);
  $Registro->setPuntoVentaId(0);
  $Registro->setPreregistroId(0);
  $Registro->setCreditosBono(0);
  $Registro->setCreditosBonoAnt(0);

  /* establece valores en un objeto Registro, incluyendo ID, fecha y código postal. */
  $Registro->setPreregistroId(0);
  $Registro->setUsuvalidaId(0);
  $Registro->setFechaValida($fecha_actual);
  $Registro->setCodigoPostal(0);

  $Registro->setCiudexpedId(0);

  /* establece una fecha de expedición basada en una condición. */
  $Registro->setFechaExped('--');

  /* Establece el ID de punto de venta y valida estado según el mandante. */
  $Registro->setPuntoventaId(0);

  $EstadoValidaRegistro = 'I';

  if ($Mandante->mandante == '13') {
    $EstadoValidaRegistro = 'A';
  }

  /* verifica si 'afiliador' es un entero y establece su valor si no lo es. */
  $Registro->setEstadoValida($EstadoValidaRegistro);

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

  /* Valida si $afiliador es numérico; si no, lo asigna a 0 y verifica un código promocional. */
  if (!is_numeric($afiliador)) {
    $afiliador = 0;
  }

  if ($afiliador == "2903228") {
    $promoCode = 'DONFUTBOL';
  }

  /* verifica un código promocional y asigna valores relacionados al afiliador. */
  if ($afiliador == "2952248") {
    $promoCode = 'KIKEJAV';
  }

  if ($bannerid == 0 && $linkid == 0) {
    if ($codigo != "") {
      //Trae la apuesta minima para el usuario online

      $apmin2Sql = "select codpromocional_id,usuario_id,link_id from codigo_promocional where codigo='" . $codigo . "' AND estado='A'  AND mandante='" . $Mandante->mandante . "'";
      $apmin2_RS = $BonoInterno->execQuery($Transaction, $apmin2Sql);

      if (($apmin2_RS[0]->{'codigo_promocional.codpromocional_id'} != "")) {
        $codigoBD = $apmin2_RS[0]->{"codigo_promocional.codpromocional_id"};
        if ($linkid == '' || $linkid == null || $linkid == '0') {
          $afiliador = $apmin2_RS[0]->{"codigo_promocional.usuario_id"};
          $linkid = $apmin2_RS[0]->{"codigo_promocional.link_id"};
        }
      }
    }
  }

  /* Consulta y verifica un código promocional activo en la base de datos. */
  if ($promoCode != "") {

    $apmin2Sql = "select codpromocional_id,usuario_id,link_id from codigo_promocional where codigo='" . $promoCode . "' AND estado='A'  AND mandante='" . $Mandante->mandante . "'";
    $apmin2_RS = $BonoInterno->execQuery($Transaction, $apmin2Sql);


    if (($apmin2_RS[0]->{'codigo_promocional.codpromocional_id'} != "")) {
      if ($linkid == '' || $linkid == null || $linkid == '0') {
        $afiliador = $apmin2_RS[0]->{'codigo_promocional.usuario_id'};
      }
      $codigoBD = $apmin2_RS[0]->{'codigo_promocional.codpromocional_id'};
    }
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
      $afiliadorGlobal = $afiliador;

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

  $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);
  $RegistroMySqlDAO->insert($Registro);

  $usuidReferente = '0';

  $UsuarioOtrainfo = new UsuarioOtrainfo();

  /* Asignación de valores a atributos de un objeto relacionado con un usuario. */
  $UsuarioOtrainfo->usuarioId = $consecutivo_usuario;
  $UsuarioOtrainfo->fechaNacim = $birthDate;
  $UsuarioOtrainfo->mandante = $Mandante->mandante;
  $UsuarioOtrainfo->info2 = null;
  $UsuarioOtrainfo->bancoId = '0';
  $UsuarioOtrainfo->numCuenta = '0';

  /* Se están asignando valores a un objeto y creando un DAO para interacción con MySQL. */
  $UsuarioOtrainfo->anexoDoc = 'N';
  $UsuarioOtrainfo->direccion = $address;
  $UsuarioOtrainfo->tipoCuenta = '0';
  $UsuarioOtrainfo->usuidReferente = $usuidReferente;


  $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaction);

  /* Inserta información de usuario y establece su perfil en la base de datos. */
  $UsuarioOtrainfoMySqlDAO->insert($UsuarioOtrainfo);

  $sitioTracking = new SitioTracking();
  $sitioTracking->setTabla('Register_user');
  $sitioTracking->setTablaId($consecutivo_usuario);
  $sitioTracking->setTvalue(0);
  $sitioTracking->setUsucreaId($consecutivo_usuario);
  $sitioTracking->setTipo(2);
  $sitioTracking->valueInd = 'API_WHATSAPP';

  $SitioTrackingMySqlDAO = new SitioTrackingMySqlDAO($Transaction);
  $SitioTrackingMySqlDAO->insert($sitioTracking);

  $UsuarioPerfil = new UsuarioPerfil();

  $UsuarioPerfil->setUsuarioId($consecutivo_usuario);

  /* Configura un perfil de usuario y crea un acceso a la base de datos. */
  $UsuarioPerfil->setPerfilId('USUONLINE');
  $UsuarioPerfil->setMandante($Mandante->mandante);
  $UsuarioPerfil->setPais('N');
  $UsuarioPerfil->setGlobal('N');

  $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaction);

  /* Se inserta un perfil de usuario y se inicializa una variable de premio máximo. */
  $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);

  $UsuarioPremiomax = new UsuarioPremiomax();

  /* Se definen variables para gestionar premios, apuestas y valores en un juego. */
  $premio_max1 = 0;
  $premio_max2 = 0;
  $premio_max3 = 0;
  $apuesta_min = 0;
  $cant_lineas = 0;
  $valor_directo = 0;
  $valor_evento = 0;
  $valor_diario = 0;

  /* Se inicializa una variable y se asignan valores a un objeto de usuario. */
  $UsuarioPremiomax->usuarioId = $consecutivo_usuario;
  $UsuarioPremiomax->premioMax = $premio_max1;
  $UsuarioPremiomax->usumodifId = '0';
  $UsuarioPremiomax->cantLineas = $cant_lineas;
  $UsuarioPremiomax->premioMax1 = $premio_max1;
  $UsuarioPremiomax->premioMax2 = $premio_max2;
  $UsuarioPremiomax->premioMax3 = $premio_max3;
  $UsuarioPremiomax->apuestaMin = $apuesta_min;
  $UsuarioPremiomax->valorDirecto = $valor_directo;
  $UsuarioPremiomax->premioDirecto = $valor_directo;
  $UsuarioPremiomax->mandante = $Mandante->mandante;
  $UsuarioPremiomax->optimizarParrilla = 'N';
  $UsuarioPremiomax->valorEvento = $valor_evento;
  $UsuarioPremiomax->valorDiario = $valor_diario;

  $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($Transaction);
  $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

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

    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
    $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);
  }

  /* Crea un objeto UsuarioMandante y asigna propiedades desde otro objeto Usuario. */
  $UsuarioMandante = new UsuarioMandante();

  $UsuarioMandante->mandante = $Usuario->mandante;

  $UsuarioMandante->nombres = "$firstName $middleName";
  $UsuarioMandante->apellidos = "$lastName $secondLastName";

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

  $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaction);
  $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);

  /* confirma una transacción y cambia la contraseña del usuario. */
  $Transaction->commit();
  $Usuario->changeClave($password);


  if ($site_id == 21) { // este condicional es para realizar el registro a cammanbet

    /* intercambia valores de identificación según condiciones de residencia. */
    $IdFirstUser = $Usuario->usuarioId;

    if ($countryId == 243) {
      $countryId = 232;
    } else if ($countryId == 232) {
      $countryId = 243;
    }

    /* Se crea un objeto de país y se asignan valores a usuario basado en datos proporcionados. */
    $PaisMandante = new PaisMandante('', $site_id, $countryId);

    $moneda_default = $PaisMandante->moneda;

    //$Usuario->usuarioId = $consecutivo_usuario;

    $Usuario->login = $email;


    /* Código asigna valores a propiedades de un objeto Usuario, estableciendo atributos específicos. */
    $Usuario->nombre = $name;

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

    $Usuario->paisId = $countryId;


    /* Se asignan valores predeterminados a propiedades del objeto Usuario. */
    $Usuario->moneda = $moneda_default;

    $Usuario->idioma = $lang;

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

    if (
      $Usuario->mandante == 0 && $Usuario->paisId == 2
      && date('Y-m-d H:i:s') >= '2023-04-01 00:00:00'
      && date('Y-m-d H:i:s') <= '2023-04-02 23:59:59'
    ) {
      $Usuario->contingenciaRetiro = 'A';
    }

    $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
    $UsuarioMySqlDAO->insert($Usuario);

    $consecutivo_usuario = $Usuario->usuarioId;

    /* Código establece propiedades de un objeto Registro con datos del usuario. */
    $Registro->setNombre($name);
    $Registro->setEmail($email);
    $Registro->setClaveActiva($passwordActive);
    $Registro->setEstado($estadoUsuarioDefault);
    $Registro->usuarioId = $consecutivo_usuario;
    $Registro->setCelular($phone);

    /* establece valores iniciales para créditos y asigna un ID de ciudad. */
    $Registro->setCreditosBase(0);
    $Registro->setCreditos(0);
    $Registro->setCreditosAnt(0);
    $Registro->setCreditosBaseAnt(0);
    $Registro->setCiudadId(0);

    /* establece valores en un objeto Registro y depura el nombre. */
    $Registro->setCasino(0);
    $Registro->setCasinoBase(0);
    $Registro->setMandante($Mandante->mandante);
    $firstName = $ConfigurationEnvironment->DepurarCaracteres($firstName);
    $firstName = mb_substr($firstName, 0, 19);

    $Registro->setNombre1($firstName);


    /* depura y limita la longitud del segundo nombre y apellido. */
    $middleName = $ConfigurationEnvironment->DepurarCaracteres($middleName);

    $middleName = mb_substr($middleName, 0, 19);

    $Registro->setNombre2($middleName);

    $lastName = $ConfigurationEnvironment->DepurarCaracteres($lastName);


    /* Se limitan y depuran los apellidos a 19 caracteres para su almacenamiento. */
    $lastName = mb_substr($lastName, 0, 19);
    $Registro->setApellido1($lastName);

    $secondLastName = $ConfigurationEnvironment->DepurarCaracteres($secondLastName);

    $secondLastName = mb_substr($secondLastName, 0, 19);

    /* configura atributos de un objeto de registro personal. */
    $Registro->setApellido2($secondLastName);

    $Registro->setSexo($gender);
    $Registro->setTipoDoc($documentTypeId);
    $Registro->setDireccion($address);
    $Registro->setTelefono($phone);

    /* asigna valores a diferentes propiedades de un objeto "Registro". */
    $Registro->setCiudnacimId(0);
    $Registro->setNacionalidadId($countryId);
    $Registro->setDirIp($dir_ip);
    $Registro->setOcupacionId(0);
    $Registro->setRangoingresoId(0);
    $Registro->setOrigenfondosId($origen_fondos);

    /* Asigna valores a propiedades de un objeto Registro en PHP. */
    $Registro->setOrigenFondos($origen_fondosString);
    $Registro->setPaisnacimId(0);
    $Registro->setPuntoVentaId(0);
    $Registro->setPreregistroId(0);
    $Registro->setCreditosBono(0);
    $Registro->setCreditosBonoAnt(0);

    /* Código configura un registro estableciendo varios atributos, como IDs y fecha. */
    $Registro->setPreregistroId(0);
    $Registro->setUsuvalidaId(0);
    $Registro->setFechaValida($fecha_actual);
    $Registro->setCodigoPostal(0);

    $Registro->setCiudexpedId(0);

    /* establece una fecha de expedición, basándose en la fecha indicada. */
    $Registro->setFechaExped('--');

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
        } else {
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
    $Transaction = $RegistroMySqlDAO->getTransaction();
    $usuidReferente = '0';

    $UsuarioOtrainfo = new UsuarioOtrainfo();

    /* Asigna información del usuario a un objeto, incluyendo ID y datos personales. */
    $UsuarioOtrainfo->usuarioId = $consecutivo_usuario;
    $UsuarioOtrainfo->fechaNacim = $birthDate;
    $UsuarioOtrainfo->mandante = $Mandante->mandante;
    $UsuarioOtrainfo->info2 = null;
    $UsuarioOtrainfo->bancoId = '0';
    $UsuarioOtrainfo->numCuenta = '0';

    /* asigna valores a propiedades de un objeto y crea una instancia DAO. */
    $UsuarioOtrainfo->anexoDoc = 'N';
    $UsuarioOtrainfo->direccion = $address;
    $UsuarioOtrainfo->tipoCuenta = '0';
    $UsuarioOtrainfo->usuidReferente = $usuidReferente;


    $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaction);

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


    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaction);
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
    $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($Transaction);
    //$UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO();
    $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);
    //$UsuarioPremiomaxMySqlDAO->getTransaction()->commit();


    if ($Mandante->mandante == 2 || $Mandante->mandante == 1) {

      /* Asignación de variables para almacenar información de usuario y sus archivos. */
      $ClientId = $consecutivo_usuario;


      $file = $userInfo->file;
      $file2 = $userInfo->file2;
      $file3 = $userInfo->file3;

      /* procesa un archivo Base64, decodificándolo y preparándolo para ser almacenado. */
      $file4 = $userInfo->file4;
      $type = $userInfo->type;
      $tipo = 'USUDNIANTERIOR';

      if ($file != "" && $file != "undefined") {
        $file = str_replace(" ", "+", $file);
        $type = 'A';
        $tipo = 'USUDNIANTERIOR';

        $pos = strpos($file, 'base64,');
        $file_contents1 = base64_decode(mb_substr($file, $pos + 7));
        $file_contents1 = addslashes($file_contents1);
      }

      /* verifica un archivo y decodifica su contenido en base64. */
      if ($file2 != "" && $file2 != "undefined") {
        $file2 = str_replace(" ", "+", $file2);
        $type = 'P';
        $tipo = 'USUDNIPOSTERIOR';

        $pos = strpos($file2, 'base64,');
        $file_contents2 = base64_decode(mb_substr($file2, $pos + 7));
        $file_contents2 = addslashes($file_contents2);
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
        $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLogMySqlDAO->insert($UsuarioLog);
      }
      if ($file_contents2 != '') {

        /* Se asigna el valor 'P' a la variable $estadoLog, posiblemente representando un estado. */
        $estadoLog = 'P';

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
        $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLogMySqlDAO->insert($UsuarioLog);
      }
      if ($file_contents3 != '') {

        /* Se establece una variable llamada $estadoLog con el valor 'P'. */
        $estadoLog = 'P';

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
        $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLogMySqlDAO->insert($UsuarioLog);
      }
      if ($file_contents4 != '') {

        /* Se asigna el valor 'P' a la variable $estadoLog. */
        $estadoLog = 'P';

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
        $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
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

      $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
      $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);
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

    $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaction);
    $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);


    /* realiza una transacción, cambia la clave de usuario y crea una cuenta asociada. */
    $Transaction->commit();

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

  /* Se asigna el valor de la variable `$email` a `$destinatarios`. */
  $destinatarios = $email;

  if ($ConfigurationEnvironment->isProduction()) {

    try {


      /* Envía un correo si el mandante no coincide con ciertos valores específicos. */
      if ($Usuario->mandante != 6 && $Usuario->mandante != 23 && $Usuario->mandante != 15 && $Usuario->mandante != 9 && $Usuario->mandante != 17 && $Usuario->mandante != 18 && $Usuario->mandante != 20 && $Usuario->mandante != 13 && ($Usuario->mandante != 0 && $Usuario->paisId != 46) && ($Usuario->mandante != 0 && $Usuario->paisId != 66)) {
        //Envia el mensaje de correo
        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $mensaje_txt, "", "", "", $Mandante->mandante);
      } else {


        /* Se crea un mensaje con una plantilla personalizada basada en el clasificador y usuario. */
        $mensaje_txt2 = $mensaje_txt;
        $mensaje_txt = "";

        try {
          $clasificador = new Clasificador("", "TEMEMREG");

          $template = new Template('', $Mandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

          $mensaje_txt .= $template->templateHtml;
        } catch (Exception $e) {
        }

        if ($mensaje_txt != '') {

          /* Se establece una URL específica si se cumplen ciertas condiciones del usuario. */
          if ($Usuario->mandante == '18' && $Usuario->paisId == '146') {
            $Mandante->baseUrl = 'https://gangabet.mx/';
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
          $mensaje_txt = str_replace("#link#", $Mandante->baseUrl . "verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId), $mensaje_txt);
          $mensaje_txt = str_replace('>#banners#<', '>' . $banner . '<', $mensaje_txt);

          $envio = $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, 'noreply@doradobet.com', 'Doradobet', $msubjetc, 'mail_registro.php', $msubjetc, $mensaje_txt, $dominio, $compania, $color_email, $Usuario->mandante);
        } else {
          /* envía un correo utilizando una configuración específica y un mensaje definido. */

          $mensaje_txt = $mensaje_txt2;
          $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $mensaje_txt, "", "", "", $Mandante->mandante);
        }
      }
    } catch (Exception $e) {
      /* Bloque de código que captura excepciones sin realizar ninguna acción. */
    }
  }

  try {
    if ($giftType != '') {

      /* Código para crear un objeto 'SitioTracking' y establecer sus propiedades específicas. */
      $SitioTracking = new \Backend\dto\SitioTracking();

      $SitioTracking->setTabla('registro_type_gift');
      $SitioTracking->setTablaId($consecutivo_usuario);
      $SitioTracking->setTipo('2');
      $SitioTracking->setTvalue($giftType);

      /* establece valores en un objeto y crea una instancia de DAO MySQL. */
      $SitioTracking->valueInd = $giftType;
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
        if ($Usuario->mandante == '18' && $Usuario->paisId == '146') {
          $Mandante->baseUrl = 'https://gangabet.mx/';
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
        $mensaje_txt = str_replace("#link#", $Mandante->baseUrl . "verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId), $mensaje_txt);
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

        /* Código para insertar un mensaje de usuario en la base de datos MySQL. */
        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
        $UsuarioMensajeMySqlDAO->getTransaction()->commit();
      }
    }
    if ($giftType != '' && $site_id == 21) { // este condicional es para realizar el registro a cammanbet

      $consecutivo_usuario = $IdFirstUser;
      $Usuario = new Usuario($consecutivo_usuario);
      $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

      /* Código para crear un objeto 'SitioTracking' y establecer sus propiedades específicas. */
      $SitioTracking = new \Backend\dto\SitioTracking();

      $SitioTracking->setTabla('registro_type_gift');
      $SitioTracking->setTablaId($consecutivo_usuario);
      $SitioTracking->setTipo('2');
      $SitioTracking->setTvalue($giftType);

      /* establece valores en un objeto y crea una instancia de DAO MySQL. */
      $SitioTracking->valueInd = $giftType;
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
        if ($Usuario->mandante == '18' && $Usuario->paisId == '146') {
          $Mandante->baseUrl = 'https://gangabet.mx/';
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
        $mensaje_txt = str_replace("#link#", $Mandante->baseUrl . "verificar-email/" . $Usuario->login . "/" . $ConfigurationEnvironment->encrypt($Usuario->usuarioId), $mensaje_txt);
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

        /* Código para insertar un mensaje de usuario en la base de datos MySQL. */
        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
        $UsuarioMensajeMySqlDAO->getTransaction()->commit();
      }
    }
  } catch (Exception $e) {
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
          "UserBalance" => "0"
        ),
        "WalletCode" => $Mandante->walletcodeItainment
      );


      /* envía datos JSON a una API usando cURL en PHP. */
      $dataD = json_encode($dataD);


      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sb2bonus-integration-altenar2.biahosted.com/api/Bonus/CreateUser/json',
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
        "WalletCode" => $Mandante->walletcodeItainment,
        "BonusCode" => "FreebetcadastroMilbets",
        "Deposit" => "500"
      );


      /* envía datos JSON a una API usando cURL en PHP. */
      $dataD = json_encode($dataD);


      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sb2bonus-integration-altenar2.biahosted.com/api/Bonus/CreateBonusByCode/json',
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
          "UserBalance" => "0"
        ),
        "WalletCode" => $Mandante->walletcodeItainment
      );


      /* envía una solicitud POST con datos JSON a una API. */
      $dataD = json_encode($dataD);


      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sb2bonus-integration-altenar2.biahosted.com/api/Bonus/CreateUser/json',
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
        "WalletCode" => $Mandante->walletcodeItainment,
        "BonusCode" => "FREEBETCHILEREGISTRO",
        "Deposit" => "300000"
      );


      /* envía una solicitud POST en JSON a una API para crear un bonus. */
      $dataD = json_encode($dataD);


      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sb2bonus-integration-altenar2.biahosted.com/api/Bonus/CreateBonusByCode/json',
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
          "UserBalance" => "0"
        ),
        "WalletCode" => $Mandante->walletcodeItainment
      );


      /* Se prepara y envía una solicitud POST con datos JSON usando cURL. */
      $dataD = json_encode($dataD);


      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sb2bonus-integration-altenar2.biahosted.com/api/Bonus/CreateUser/json',
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
        "WalletCode" => $Mandante->walletcodeItainment,
        "BonusCode" => "BONOGUA",
        "Deposit" => "2500"
      );


      /* Envía datos JSON mediante una solicitud POST a una API usando cURL. */
      $dataD = json_encode($dataD);


      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sb2bonus-integration-altenar2.biahosted.com/api/Bonus/CreateBonusByCode/json',
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
          "UserBalance" => "0"
        ),
        "WalletCode" => $Mandante->walletcodeItainment
      );


      /* Envía datos JSON a una API usando cURL en PHP para crear un usuario. */
      $dataD = json_encode($dataD);


      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sb2bonus-integration-altenar2.biahosted.com/api/Bonus/CreateUser/json',
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
        "WalletCode" => $Mandante->walletcodeItainment,
        "BonusCode" => "BONUSTRI",
        "Deposit" => "5000"
      );


      /* envía datos JSON a una API mediante una solicitud POST usando cURL. */
      $dataD = json_encode($dataD);


      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sb2bonus-integration-altenar2.biahosted.com/api/Bonus/CreateBonusByCode/json',
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

  if ($estadoUsuarioDefault == 'A') {
    try {
      /* Se crea un token de usuario con datos específicos de sesión y proveedor. */
      $UsuarioToken = new UsuarioToken();

      $UsuarioToken->setRequestId(null);
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
    }
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
        "PaisUSER" => $countryId,
        "DepartamentoUSER" => 0,
        "CiudadUSER" => 0,
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
  }

  $response = array();
  $response["code"] = 200;
  $response["error"] = false;
  $response["message"] = "User registered successfully";
  $response["data"] = [
    "user_id" => $Usuario->usuarioId
  ];
} else {
  /* lanza excepciones si un documento ya existe en el sistema. */
  if ($site_id == "14") {
    $response["code"] = 300164;
    $response["error"] = true;
    $response["message"] = "O documento já existe: " . $documentNumber;
    $response["data"] = [];
    return;
  }
  $response["code"] = 300164;
  $response["error"] = true;
  $response["message"] = "El número de identificación ya existe " . $documentNumber;
  $response["data"] = [];
  return;
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


/**
 * Generar clave para ticket
 *
 * @param int $length length
 * @return string $randomString clave para ticket
 * @access public
 * @since 2025-05-27
 */
function GenerarClaveTicket($length)
{
  $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}

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
