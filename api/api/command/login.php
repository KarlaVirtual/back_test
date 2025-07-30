<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Realiza el proceso de login de un usuario y genera una respuesta con los datos de autenticación.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 *  - params:object Parámetros de la solicitud.
 *    - in_app:bool Indica si la solicitud se realiza desde una aplicación.
 *    - username:string Nombre de usuario.
 *    - password:string Contraseña del usuario.
 *    - site_id:int Identificador del sitio.
 *  - session:object Información de la sesión del usuario.
 *    - sid:string Identificador de la sesión.
 *  - rid:string Identificador de la respuesta.
 *
 * @return array Respuesta con el código de estado, identificador de respuesta y datos de autenticación.
 *  - code:int Código de respuesta.
 *  - rid:string Identificador de respuesta.
 *  - redirectUrl:string URL de redirección.
 *  - data:array Datos de la respuesta.
 *    - auth_token:string Token de autenticación.
 *    - user_id:int Identificador del usuario.
 *    - id_platform:int Identificador de la plataforma.
 *    - channel_id:int Identificador del canal.
 *    - tokenSB:string Token de seguridad.
 *    - user_menus:array Menú de usuario.
 *    - in_app:bool Indica si la solicitud se realiza desde una aplicación.
 *
 * @throws Exception Si ocurre un error durante el proceso de login.
 * @throws Exception Si el nombre de usuario o la contraseña no son válidos.
 * @throws Exception Si el identificador del sitio no es válido.
 * @throws Exception Si el usuario no tiene un mandante asociado.
 */

/* asigna valores a variables según la conexión y parámetros existentes. */
$_ENV["enabledConnectionGlobal"] = 1;


$inApp = $json->params->in_app;
if ($inApp == true) {
    $inApp = 1;
    $_ENV['LOGINAPP'] = 1;
}



/* decodifica caracteres JSON en el nombre de usuario si contiene '\ud'. */
$usuario = $json->params->username;
$clave = $json->params->password;
$site_id = $json->params->site_id;


if (strpos($usuario, '\ud') !== false) {
    $usuario = json_decode('"' . $usuario . '"');

}

if ($_SERVER['HTTP_REFERER'] == '' && $site_id =='0') {
    throw new Exception("El usuario ha sido bloqueado por el sistema debido a que excedió el número de intentos permitidos con clave errónea.  ", "300017");
}

/* verifica caracteres Unicode y limpia el nombre de usuario de caracteres no ASCII. */
if (strpos($clave, '\ud') !== false) {
    $clave = json_decode('"' . $clave . '"');
}

$usuario = preg_replace('/[^(\x20-\x7F)]*/', '', $usuario);


/* Se eliminan espacios del usuario y se valida para seguridad. */
$usuario = str_replace(" ", "", $usuario);
$usuario = validarCampoSecurity($usuario, true);

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

/* reemplaza caracteres no deseados en `$usuario` y depura `$clave`. */
$usuario = strtr($usuario, $unwanted_array);

$ConfigurationEnvironment = new ConfigurationEnvironment();


$clave = $ConfigurationEnvironment->DepurarCaracteres($clave);


/* verifica el usuario y lanza excepciones si no es uno autorizado. */
$Usuario = new Usuario();

$Mandante = new Mandante($site_id);

if ($usuario != 'tecnologiatemp3@gmail.com' && $usuario != 'tecnologiatemp5@gmail.com' && $usuario != 'tecnologiatemp2@gmail.com') {
    //throw new Exception('We are currently in the process of maintaining the site.', 30004);

    //  throw new Exception("No puede iniciar sesion en el sitio. ", "30010");

}


/* Limita el acceso a dos sitios específicos según el usuario. */
if ($site_id == '14' && $usuario != 'tecnologiatemp5@gmail.com' && $usuario != 'comercial2@virtualsoft.tech' && $usuario != 'ghenrique90@gmail.com' && $usuario != 'am.lotosports@virtualsoft.tech') {
    //  throw new Exception("No puede iniciar sesion en el sitio. ", "30010");
}
if ($site_id == '20' && $usuario != 'testpruebasivarbet@gmail.com' && $usuario != 'tecnologiatemp5@gmail.com' && $usuario != 'comercial2@virtualsoft.tech' && $usuario != 'ghenrique90@gmail.com' && $usuario != 'am.lotosports@virtualsoft.tech') {
    //throw new Exception("No puede iniciar sesion en el sitio. ", "30010");
}
//$Usuario->dirIp = $json->session->usuarioip;
//session_start();


/* depura y elimina emojis de las variables de usuario y clave. */
$ConfigurationEnvironment = new ConfigurationEnvironment();

$usuario = $ConfigurationEnvironment->DepurarCaracteres($usuario);
$clave = $ConfigurationEnvironment->DepurarCaracteres($clave);

$usuario = $ConfigurationEnvironment->remove_emoji($usuario);


/* Se depuran variables de usuario y clave, restringiendo el inicio de sesión para mandante 12. */
$usuario = $ConfigurationEnvironment->DepurarCaracteres($usuario);
$clave = $ConfigurationEnvironment->DepurarCaracteres($clave);

$usuario = preg_replace('/\\\\/', '', $usuario);
$clave = preg_replace('/\\\\/', '', $clave);


if ($Mandante->mandante == 12) {
    throw new Exception("No puede iniciar sesion en el sitio. ", "30010");
}

/* intenta realizar un login de usuario con datos proporcionados. */
try {
    $responseU = $Usuario->login($usuario, $clave, '', $Mandante->mandante, $json);

} catch (Exception $e) {

    if ($e->getCode() == 30001) {


        /* verifica si un usuario está bloqueado por intentos fallidos de acceso. */
        try {
            $Clasificador = new Clasificador("", "ACTIVATESENDEMAIL");

            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, "A");
            $IsActivatedSendEmail = $MandanteDetalle->valor;

            if ($IsActivatedSendEmail == "A") {
                throw new Exception("El usuario ha sido bloqueado por el sistema debido a que excedió el número de intentos permitidos con clave errónea.  ", "300017");
            }
        } catch (Exception $ee) {
            /* Maneja excepciones: vuelve a lanzar si el código de error es 300017. */

            if ($ee->getCode() == "300017") {
                throw $ee;

            }
        }
        throw $e;


    }
    throw $e;


}


try {
    /*$UsuarioToken = new UsuarioToken();

    $UsuarioToken->setRequestId($json->session->sid);
    $UsuarioToken->setProveedorId('0');
    $UsuarioToken->setUsuarioId($responseU->user_id);
    $UsuarioToken->setToken($UsuarioToken->createToken());

    $UsuarioToken->setCookie(encrypt($responseU->user_id . "#" . time()));
    $UsuarioToken->setUsumodifId(0);
    $UsuarioToken->setUsucreaId(0);
    $UsuarioToken->setSaldo(0);


    $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
    $UsuarioTokenMySqlDAO->insert($UsuarioToken);
    $UsuarioTokenMySqlDAO->getTransaction()->commit();*/


    /* $UsuarioToken = new UsuarioToken("", '0', $responseU->user_id);

     $UsuarioToken->setRequestId($json->session->sid);
     $UsuarioToken->setCookie(encrypt($responseU->user_id . "#" . time()));
     $UsuarioToken->setToken($UsuarioToken->createToken());

     $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
     $UsuarioTokenMySqlDAO->update($UsuarioToken);
     $UsuarioTokenMySqlDAO->getTransaction()->commit();*/

    /*  $UsuarioToken = new UsuarioToken("", '0', $responseU->user_id);

      $UsuarioToken->setEstado('I');

      $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
      $UsuarioTokenMySqlDAO->update($UsuarioToken);
      $UsuarioTokenMySqlDAO->getTransaction()->commit();

      throw new Exception("No existe ", "21");*/

} catch (Exception $e) {


    /*  if ($e->getCode() == "21") {

          $UsuarioToken = new UsuarioToken();

          $UsuarioToken->setRequestId($json->session->sid);
          $UsuarioToken->setProveedorId('0');
          $UsuarioToken->setUsuarioId($responseU->user_id);
          $UsuarioToken->setToken($UsuarioToken->createToken());

          $UsuarioToken->setCookie(encrypt($responseU->user_id . "#" . time()));
          $UsuarioToken->setUsumodifId(0);
          $UsuarioToken->setUsucreaId(0);
          $UsuarioToken->setSaldo(0);
          $UsuarioToken->setEstado('A');


          $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
          $UsuarioTokenMySqlDAO->insert($UsuarioToken);
          $UsuarioTokenMySqlDAO->getTransaction()->commit();

      }*/

}

/* asigna una URL de redirección para deportes con un parámetro de formulario. */
$redirectUrl = '/deportes?frm=lgn';


try {

    /* redirige según el tipo de usuario y referencia de la URL. */
    $UsuarioMandante = new UsuarioMandante($responseU->user_id);
    if ($UsuarioMandante->mandante == 2) {
        $redirectUrl = '/home?frm=lgn';
        if (strpos($_SERVER['HTTP_REFERER'], "acropolis") !== FALSE) {
            $redirectUrl = '/new-casino?frm=lgn';
        }

    }


    /* Redirige usuarios según ciertos criterios de mandante y tiempo de sesión. */
    if ($UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId == 60) {
        $redirectUrl = '/home?frm=lgn';
    }

    if ($UsuarioMandante->mandante == 13) {
        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

        if ($Usuario->fechaUlt >= date('Y-m-d H:i:s', strtotime('-8 seconds'))) {
            $redirectUrl = '/gestion/deposito?frm=lgn';

            $dataSend = array(
                "redirectUrl" => $redirectUrl
            );
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

        }
    }

    /* Verifica condiciones para redirigir un usuario en fechas recientes a una URL específica. */
    if ($UsuarioMandante->mandante == 14) {
        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

        if ($Usuario->fechaUlt >= date('Y-m-d H:i:s', strtotime('-8 seconds'))) {
            $redirectUrl = '/gestion/deposito?frm=lgn';

            $dataSend = array(
                "redirectUrl" => $redirectUrl
            );
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

        }
    }


    /* Condicional que verifica mandantes y usuarios para enviar una notificación de cierre de sesión. */
    if ((in_array($UsuarioMandante->mandante, array('0', '6', '8', '2', '12', 3, 4, 5, 6, 7)) || true) && !in_array($UsuarioMandante->usuarioMandante, array(17884, 242068, 255499, 255528, 255547, 255584)) && false) {

        $dataSend = array(
            "logout" => true
        );
        $WebsocketUsuario = new WebsocketUsuario('', '');
        $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);
    }

} catch (Exception $e) {
    /* Captura excepciones en PHP para manejar errores sin interrumpir la ejecución del programa. */


}

if ($_ENV['LOGINAPP'] == '1') {


    /* Crea un registro de log de usuario con información específica del mandante. */
    $UsuarioLog = new UsuarioLog();
    $UsuarioLog->setUsuarioId($UsuarioMandante->usuarioMandante);
    $UsuarioLog->setUsuarioIp('');
    $UsuarioLog->setUsuariosolicitaId($UsuarioMandante->usuarioMandante);
    $UsuarioLog->setUsuariosolicitaIp('');
    $UsuarioLog->setUsuarioaprobarId(0);

    /* Registro de usuario con tipo, estado y valores antes y después establecidos. */
    $UsuarioLog->setTipo('APPLOGINAPP');
    $UsuarioLog->setEstado("A");
    $UsuarioLog->setValorAntes('1');
    $UsuarioLog->setValorDespues('1');

    $UsuarioLog->setSoperativo('');

    /* Se inicializan propiedades del objeto UsuarioLog y se crea su DAO correspondiente. */
    $UsuarioLog->setSversion('');

    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);

    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();


    /* Inserta un registro de usuario y obtiene la transacción actual en MySQL. */
    $UsuarioLogMySqlDAO->insert($UsuarioLog);
    $UsuarioLogMySqlDAO->getTransaction()->commit();

}


/* decodifica un menú de usuarios en formato JSON. */
$usersMenu = json_decode((
'[{"MENU_ID":"3","MENU_TITLE":"Gesti\u00f3n","MENU_SLUG":"gestion","MENU_EDITAR":"true","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"136","SUBMENU_URL":"deposito","SUBMENU_TITLE":"Depositar"},{"SUBMENU_ID":"102","SUBMENU_URL":"cuenta_cobro_anular","SUBMENU_TITLE":"Anular Nota Retiro"},{"SUBMENU_ID":"189","SUBMENU_URL":"cuentasbancarias","SUBMENU_TITLE":"Cuentas bancarias"},{"SUBMENU_ID":"41","SUBMENU_URL":"cuenta_cobro","SUBMENU_TITLE":"Retirar"},{"SUBMENU_ID":"500","SUBMENU_URL":"verificar_cuenta","SUBMENU_TITLE":"Verificar Cuenta"},{"SUBMENU_ID":"121","SUBMENU_URL":"cambiar-clave","SUBMENU_TITLE":"Cambiar Contrase\u00f1a"},{"SUBMENU_ID":"195","SUBMENU_URL":"misbonos","SUBMENU_TITLE":"Mis Bonos"},{"SUBMENU_ID":"87","SUBMENU_URL":"gestion_cuenta","SUBMENU_TITLE":"Mi Cuenta"}]},{"MENU_ID":"5","MENU_TITLE":"Consultas","MENU_SLUG":"consulta","MENU_EDITAR":"false","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"100","SUBMENU_URL":"consulta_tickets_online","SUBMENU_TITLE":"Consulta de apuestas deportivas"},{"SUBMENU_ID":"184","SUBMENU_URL":"consulta_tickets_casino","SUBMENU_TITLE":"Informe de apuestas casino"},{"SUBMENU_ID":"186","SUBMENU_URL":"consulta_depositos","SUBMENU_TITLE":"Consultar depositos"},{"SUBMENU_ID":"188","SUBMENU_URL":"consulta_retiros","SUBMENU_TITLE":"Consultar retiros"}]}]'
));

$response = array();
$response["code"] = 0;

/* asigna datos a un arreglo de respuesta para una solicitud JSON. */
$response["rid"] = $json->rid;
$response["redirectUrl"] = $redirectUrl;


$response["data"] = array(
    "auth_token" => $responseU->auth_token,
    "user_id" => $responseU->user_id,
    "id_platform" => $responseU->user_id2,
    "channel_id" => $responseU->user_id,
    "tokenSB" => $responseU->token_itn,
    "user_menus" => $usersMenu,

    "redirectUrl" => $redirectUrl,
    "in_app" => $inApp
);
/*
            if($UsuarioToken->getUsuarioId()==1){
                $response["data"]["status"]="2008";
                $response["code"] = 13;

            }*/
