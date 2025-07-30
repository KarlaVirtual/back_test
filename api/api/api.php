<?php
/**
 * API
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 24.09.17
 *
 */

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
use Backend\mysql\BonoInternoMySqlDAO;
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
use Backend\utils\RedisConnectionTrait;
use Backend\websocket\WebsocketUsuario;

ini_set('display_errors', 'off');

require_once __DIR__ . '../../vendor/autoload.php';

date_default_timezone_set('America/Bogota');

/**
 * Resolver API
 *
 *
 * @param array $json json
 *
 * @return String
 * @throws Exception si el token está vacío
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function resolverAPI($json, $UsuarioTokenSite = '', $UsuarioMandanteSite = '')
{

    if ($UsuarioMandanteSite == '' && $json->session->usuario != '') {
        $UsuarioMandanteSite = new UsuarioMandante($json->session->usuario);
        if ($UsuarioMandanteSite->mandante != 0 && $UsuarioMandanteSite->mandante != 8 && $UsuarioMandanteSite->mandante != 14) {
            $_ENV['checkCache'] = 1;
        }
        $_ENV['checkCache'] = 1;

    }
    if ($UsuarioMandanteSite == '' && $json->session->usuario != '') {
        $UsuarioMandanteSite = new UsuarioMandante($json->session->usuario);
        if ($UsuarioMandanteSite->mandante == 0) {
            $_ENV['enabledCRMQuery'] = false;
        }

    }
    $_ENV['enabledCRMQuery'] = false;


    $arrayForRedis = array(
        'exist_user_favorite_games' => 21600,
        'get_casino_favorite_ids' => 21600,
        'get_countries' => 86400
    );

    if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
        error_reporting(E_ALL);
        ini_set("display_errors", "ON");
        $_ENV['debug'] = true;
    }
    $fecha_hoy = date('Y-m-d', time());
    try {

        //$arraySuper[oldCount($arraySuper) - 2] = ucfirst($arraySuper[oldCount($arraySuper) - 2]);

        $extension = "";
        if ($json->params->source != "") {
            $json->params->source = str_replace(".", "-", $json->params->source);
            $extension = "/" . $json->params->source;
        }
        if ($json->command == "withdraw_allbalance") {
            //syslog(LOG_WARNING, "COMMANDwithdraw_allbalance :" . ' - '."ERRORAPI :" .  json_encode($_SERVER) .  json_encode($_REQUEST));

        }


        //syslog(LOG_WARNING, "_COMMAND_ " . $json->command . " _SERVER_ "  . json_encode($_SERVER) . " _REQUEST_ "  . json_encode($_REQUEST) . " _BODY_ "  . json_encode($json));


        $filename = __DIR__ . '/command/' . $json->command . $extension . ".php";

        if (file_exists($filename)) {


            if (in_array($json->command, array_keys($arrayForRedis))) {


                $redisParam = ['ex' => $arrayForRedis[$json->command]];

                $redisPrefix = 'GET+' . $json->command;

                /* Conecta a Redis y recupera un valor basado en un clave generada. */
                $redis = RedisConnectionTrait::getRedisInstance(true);

                if ($redis != null) {


                    if($json->command =='get_countries'){
                        $cachedKey = $redisPrefix . 'UID+' . $json->params->site_id;

                    }elseif($json->command =='get_countries2'){
                        $cachedKey = $redisPrefix . 'UID+' . $json->params->site_id;

                    }else{
                        $cachedKey = $redisPrefix . 'UID+' . $UsuarioMandanteSite->getUsuarioMandante();

                    }

                    $cachedValue = ($redis->get($cachedKey));
                }

                /* Verifica y ejecuta un script si un valor no está vacío, manejando excepciones. */
                if (!empty($cachedValue)) {

                    $response = json_decode($cachedValue, true);


                } else {
                    require $filename;
                }
            } else {
                require $filename;
            }


        } else {
            $response["code"] = 12;
            $response['msg'] = "General Error";
        }


    } catch (Exception $e) {
        if ($_ENV['debug']) {
            print_r($e);
        }

        if ($e->getCode() != "21") {
            $commandStr = '';
            if ($json->command != '') {
                $commandStr = $json->command;
            }
            syslog(LOG_WARNING, "COMMAND :" . $commandStr . ' - ' . "ERRORAPI :" . $e->getCode() . ' - ' . $e->getMessage() . json_encode($_SERVER) . json_encode($_REQUEST) . json_encode($json));
        }
        if ($e->getCode() == 50) {
            $ClientId = $json->params->username;

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Usuario = $UsuarioMySqlDAO->queryByLogin($ClientId);
            $Usuario = $Usuario[0];
            if ($Usuario != "") {
                $UsuarioLog = new UsuarioLog();

                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp($json->session->usuarioip);
                $UsuarioLog->setUsuariosolicitaId(0);
                $UsuarioLog->setUsuariosolicitaIp('');
                $UsuarioLog->setTipo("LOGIN");
                $UsuarioLog->setEstado("F");
                $UsuarioLog->setValorAntes($json->session->usuarioip);
                $UsuarioLog->setValorDespues($json->session->usuarioip);
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->insert($UsuarioLog);

                $UsuarioLogMySqlDAO->getTransaction()->commit();

            }
        }


        $response = array();

        $response['code'] = 12;
        $response['msg'] = "";

        if ($e->getCode() == 20000) {
            $response['code'] = 20000;

        }
        $response['error_code'] = $e->getCode();
        //$response['error_msj'] = $e->getMessage();

        $response['msg'] = $e->getMessage() . '. ';

        if (strpos($response['msg'], 'Backend') !== false) {
            $response['msg'] = '';
        }
        if (strpos($response['msg'], 'backend') !== false) {
            $response['msg'] = '';
        }
        if (strpos($response['msg'], 'mysql') !== false) {
            $response['msg'] = '';
        }
        if (strpos($response['msg'], 'SQL Error') !== false) {
            $response['msg'] = '';
        }

        if ($response['error_code'] == '30011') {
            $response['error_code'] = 'QFBET';
        }

        if ($response['error_code'] == '30000') {
            $response['error_code'] = $e->getMessage();
        }

        if ($response['error_code'] == '21015') {
            $response['msg'] = $e->getMessage();
        }

        switch ($json->command) {


            /**
             * update_user_password
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "update_user_password":
                if ($e->getCode() == 30003) {

                    $response['code'] = 12;
                    $response['data'] = "12";
                    $response['msg'] = "";
                    $response['error_code'] = $e->getCode();
                    // $response['error_msj'] = $e->getMessage();
                }

                break;

            /**
             * login
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "login":

                switch ($e->getCode()) {
                    case  "30001":

                        $response['code'] = 12;
                        $response['data'] = "12";
                        $response['msg'] = "";
                        $response['data'] = array(
                            "status" => 1022,
                            "details" => "You account has been locked. Please try again later"
                        );

                        break;

                    case  "30002":

                        $response['code'] = 12;
                        $response['data'] = "12";
                        $response['msg'] = "";
                        $response['data'] = array(
                            "status" => 1002,
                            "details" => "Username or email invalid"
                        );

                        break;

                    case  "20003":

                        $response['code'] = 12;
                        $response['data'] = "12";
                        $response['msg'] = "";
                        $response['data'] = array(
                            "status" => 1030,
                            "details" => "Account Inactive");

                        break;


                    case  "5":

                        $response['code'] = -1002;
                        $response['data'] = -1002;
                        $response['result'] = -1002;
                        $response['msg'] = "";
                        $response['data'] = array(
                            "status" => -1002,
                            "details" => "Wrong Password");

                        break;

                    case  "30003":

                        $response['code'] = 12;
                        $response['data'] = "12";
                        $response['msg'] = "";
                        $response['data'] = array(
                            "status" => 1002,
                            "details" => "Invalid username or password."
                        );

                        break;

                    case  "30005":

                        $response['code'] = 12;
                        $response['data'] = "12";
                        $response['msg'] = "";
                        $response['data'] = array(
                            "status" => 1030,
                            "details" => "The user account has not been activated");

                        break;

                    case  "30001":

                        $response['code'] = 12;
                        $response['data'] = "12";
                        $response['msg'] = "";
                        $response['data'] = array(
                            "status" => 2008,
                            "details" => "Your account is self-excluded. Any information communicate with support."
                        );

                        break;

                    default:

                        $response['data'] = array(
                            "status" => 2008,
                            "details" => "Your account is self-excluded. Any information communicate with support."
                        );

                        break;

                }


                break;

            /**
             * update_user
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "update_user":

                switch ($e->getCode()) {


                    case  "5":

                        $response['code'] = 0;

                        $response['result'] = 0;
                        $response['msg'] = "";
                        $response['data'] = array(
                            "result" => '-1002'
                        );

                        break;

                    default:

                        $response['data'] = array(
                            "status" => 2008,
                            "details" => "Your account is self-excluded. Any information communicate with support."
                        );

                        break;

                }


            /**
             * withdraw
             *
             * @param no
             *
             * @return no
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */
            case "withdraw":

                switch ($e->getCode()) {


                    case  "54":

                        $response['code'] = 0;

                        $response['result'] = 0;
                        $response['msg'] = "Error";
                        $response['details'] = array(
                            "error" => 54,
                            "message" => "Error"
                        );

                        $response['data'] = array(
                            "details" => array(
                                "error" => 54,
                                "message" => "Amount is less than minimum allowed"
                            )
                        );

                        break;

                    case  "55":

                        $response['code'] = 0;

                        $response['result'] = 0;
                        $response['msg'] = "Error";
                        $response['details'] = array(
                            "error" => 54,
                            "message" => "Error"
                        );

                        $response['data'] = array(
                            "details" => array(
                                "error" => 54,
                                "message" => "Amount is greater than maximum allowed"
                            )
                        );

                        break;

                    case  "57":

                        $response['code'] = 0;

                        $response['result'] = 0;
                        $response['msg'] = "Error";
                        $response['details'] = array(
                            "error" => 57,
                            "message" => "Error"
                        );

                        $response['data'] = array(
                            "details" => array(
                                "error" => 57,
                                "message" => "Insufficient balance"
                            )
                        );

                        break;

                    case  "58":

                        $response['code'] = 0;

                        $response['result'] = 0;
                        $response['msg'] = "Error";
                        $response['details'] = array(
                            "error" => 54,
                            "message" => "Error"
                        );

                        $response['data'] = array(
                            "details" => array(
                                "error" => 58,
                                "message" => "Insufficient balance"
                            )
                        );

                        break;

                    default:

                        $response['data'] = array(
                            "status" => 2008,
                            "details" => "Your account is self-excluded. Any information communicate with support."
                        );

                        break;

                }


                break;


        }


    }

    $response["rid"] = $json->rid;

    if ($response['code']==0) {
        if (in_array($json->command, array_keys($arrayForRedis))) {
            if($redis != null){

                $redisPrefix = 'GET+' . $json->command;

                $redisParam = ['ex' => $arrayForRedis[$json->command]];
                if($json->command =='get_countries'){
                    $cachedKey = $redisPrefix . 'UID+' . $json->params->site_id;

                }elseif($json->command =='get_countries2'){
                    $cachedKey = $redisPrefix . 'UID+' . $json->params->site_id;

                }else{
                    $cachedKey = $redisPrefix . 'UID+' . $UsuarioMandanteSite->getUsuarioMandante();

                }

                $redis->set($cachedKey, json_encode($response), $redisParam);
            }
        }
    }



    return (json_encode($response));

}

/**
 * Validar campo de seguridad
 *
 *
 * @param String $string string
 * @param String $espacios espacios
 *
 * @return boolaen $ resultado de la validación
 * @throws Exception si hay algo inusual
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function validarCampoSecurity($string, $espacios)
{

    if ($espacios) {
        if (strpos($string, ' ') !== false) {
            throw new Exception("Inusual Detected", "11");

        }
    }

    return DepurarCaracteres($string);
}


/**
 * Depurar caracteres de una cadena de texto
 *
 *
 * @param String $texto_depurar texto_depurar
 *
 * @return String $texto_depurar texto_depurar
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function DepurarCaracteres($texto_depurar)
{

    $texto_depurar = str_replace("'", "", $texto_depurar);
    $texto_depurar = str_replace('"', "", $texto_depurar);
    $texto_depurar = str_replace(">", "", $texto_depurar);
    $texto_depurar = str_replace("<", "", $texto_depurar);
    $texto_depurar = str_replace("[", "", $texto_depurar);
    $texto_depurar = str_replace("]", "", $texto_depurar);
    $texto_depurar = str_replace("{", "", $texto_depurar);
    $texto_depurar = str_replace("}", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("`", "", $texto_depurar);
    $texto_depurar = str_replace("|", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("%", "", $texto_depurar);
    $texto_depurar = str_replace("&", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("~", "", $texto_depurar);
    $texto_depurar = str_replace("+", "", $texto_depurar);
    $texto_depurar = str_replace("^", "", $texto_depurar);
    $texto_depurar = str_replace("/", "", $texto_depurar);
    //$texto_retornar = addslashes($texto_depurar);

    $c = null;
    return $texto_depurar;
}

/**
 * Encriptar una cadena con el método aes-256-cbc
 *
 *
 * @param String $string string
 *
 * @return String $encrypted cadena encriptada
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function encrypt($string)
{

    $key = 'zsdfdsartw4saerR'; //Aquí pon lo que quieras y guárdalo en algún sitio dónde solo TU tengas acceso.

    $method = "aes-256-cbc";

    $encrypted = openssl_encrypt($string, $method, $key);

    return $encrypted;
}

/**
 * Desencriptar una cadena con el método aes-256-cbc
 *
 *
 * @param String $string string
 *
 * @return String $decrypted cadena desencriptada
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function decrypt($string)
{

    $key = 'zsdfdsartw4saerR'; //Aquí pon lo que quieras y guárdalo en algún sitio dónde solo TU tengas acceso.
    $method = "aes-256-cbc";

    $decrypted = openssl_decrypt($string, $method, $key);

    return $decrypted;

}

/**
 * Validar campo
 *
 *
 * @param String $valor valor
 * @param String $obligatorio obligatorio
 * @param String $tipo_dato tipo_dato
 * @param int $longitud longitud
 *
 * @return boolean $ resultado de la validación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function ValidarCampo($valor, $obligatorio, $tipo_dato, $longitud)
{
    //Pregunta si el campo es obligatorio
    if ($obligatorio == "S") {
        //Valida que el campo contenga alg�n valor y que su tama�o no sobrepase el permitido
        if (strlen($valor) <= 0 or strlen($valor) > $longitud) {
            return false;
        }

        //Valida el tipo de campo
        switch ($tipo_dato) {
            case "N": //Tipo n�mero
                if (!is_numeric($valor)) {
                    return false;
                }

                break;
            case "E": //Tipo email
                if (!filter_var(filter_var($valor, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)) {
                    return false;
                }

                break;
            case "F": //Tipo fecha
                if (strlen($valor) != "10") {
                    return false;
                } else {
                    if (!validateDate($valor)) {
                        return false;
                    }

                }
                break;
            case "H": //Tipo hora
                if (strlen($valor) != 5) {
                    return false;
                } else {
                    $separado = split("[:]", $valor);
                    if ((floatval($separado[0]) < 0 and floatval($separado[0]) > 23) or (floatval($separado[1]) < 0 and floatval($separado[1]) > 59)) {
                        return false;
                    }

                }
                break;
        }
    } else {
        //Depura valor
        $valor = str_replace("_empty", "", $valor);

        //No es obligatorio pero contiene alg�n valor
        if (strlen($valor) > 0) {
            //Valida que no sobrepase la longitud maxima del campo
            if (strlen($valor) > $longitud) {
                return false;
            }

            //Valida el tipo de campo
            switch ($tipo_dato) {
                case "N": //Tipo n�mero
                    if (!is_numeric($valor)) {
                        return false;
                    }

                    break;
                case "E": //Tipo email
                    if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                        return false;
                    }

                    break;
                case "F": //Tipo fecha
                    if (strlen($valor) != 10) {
                        return false;
                    } else {
                        if (!validateDate($valor)) {
                            return false;
                        }

                    }
                    break;
                case "H": //Tipo fecha
                    if (strlen($valor) != 5) {
                        return false;
                    } else {
                        $separado = split("[:]", $valor);
                        if ((floatval($separado[0]) < 0 and floatval($separado[0]) > 23) or (floatval($separado[1]) < 0 and floatval($separado[1]) > 59)) {
                            return false;
                        }

                    }
                    break;
            }
        }
    }

    //Retorna campo OK
    return true;
}

/**
 * Validar fecha
 *
 *
 * @param String $date date
 *
 * @return boolean $ resultado de la validación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function validateDate($date)
{
    list($year, $month, $day) = explode('-', $date);
    if (is_numeric($year) && is_numeric($month) && is_numeric($day)) {
        return checkdate($month, $day, $year);
    }

    return false;
}

/**
 * Obtener la ip del cliente
 *
 *
 * @return String $ipaddress ip del cliente
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/**
 * Traducir mercado
 *
 *
 * @param String $mercado mercado
 * @param String $idioma idioma
 *
 * @return String $mercado mercado
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function traduccionMercado($mercado, $idioma)
{
    switch (strtolower($mercado)) {
        case "draw":

            return "Empate";

            break;

        case "hd":

            return "1X";

            break;

        case "ha":

            return "12";

            break;

        case "da":

            return "X2";

            break;

        default:
            if (strpos($mercado, 'Under') !== false) {
                return str_replace("Under ", "Menos ", $mercado);

            }

            if (strpos($mercado, 'Over') !== false) {
                return str_replace("Over ", "Mas ", $mercado);

            }

            return $mercado;
    }
}

/**
 * Generar clave para ticket
 *
 *
 * @param int $length length
 *
 * @return String $randomString clave para ticket
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
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
 * Generar clave para ticket
 *
 *
 * @param int $length length
 *
 * @return String $randomString clave para ticket
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function GenerarClaveTicket2($length)
{
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
