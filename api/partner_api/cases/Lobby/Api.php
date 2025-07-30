<?php

/**
 * Index de la Api 'Lobby/Api'
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 18.05.18
 *
 */

use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\MandanteDetalle;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;

/**
 * Procesa solicitudes relacionadas con la API del lobby.
 *
 * Este script maneja la autenticación del usuario, la validación de tokens y la ejecución de comandos
 * específicos enviados desde el cliente. También gestiona sesiones y verifica el estado del usuario.
 *
 * @param object $data Objeto JSON decodificado que contiene los siguientes valores:
 * @param string $data->command Comando a ejecutar.
 * @param object $data->params Parámetros adicionales para el comando.
 * @param string $data->command Comando a ejecutar.
 *
 * @return object $respuesta Respuesta que incluye:
 * - int $code Código de estado de la operación.
 * - mixed $result Resultado del comando ejecutado.
 * - object $data Datos adicionales, como tokens de sesión.
 */

require_once __DIR__ . '/../../../api/api.php';

//error_reporting(E_ALL);
//ini_set("display_errors","on");

/* Define una función para obtener todos los encabezados HTTP si no existe. */
if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

/* habilita el modo de depuración si se proporciona una clave específica. */
$start = microtime(true);   // marca el inicio de la ejecución

$_ENV['enabledCRMQuery']=false;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}


/* verifica una solicitud y establece una variable de entorno si coincide. */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
    $_ENV["debugFixed2"] = '1';
}

$body = file_get_contents('php://input');

if ($body != "") {

    /* verifica y modifica identificadores de sitio en datos JSON. */
    $data = json_decode($body);


    if ($data->params->site_id == "0P" || $data->site_id == "0P") {
        $data->params->site_id = '0';
        $data->site_id = '0';

        $data->isPanama = '1';
    }


    /* Convierte a minúsculas los identificadores de sitio si no están vacíos. */
    if ($data->params->site_id != "") {
        $data->params->site_id = strtolower($data->params->site_id);
    }
    if ($data->site_id != "") {
        $data->site_id = strtolower($data->site_id);
    }


    /* obtiene un token de sesión de los encabezados HTTP recibidos. */
    $headers = getallheaders();

    $tokenUsuario = $headers['swarm-session'];

    if ($tokenUsuario == "") {
        $tokenUsuario = $headers['Swarm-Session'];
    }

    /* reinicia el token de usuario si es igual a 'FUN'. */
    if ($tokenUsuario == 'FUN') {

        $tokenUsuario = '';
    }
    if ($tokenUsuario != "" && ($tokenUsuario != 'FUN')) {

        try {

            /* Código crea un entorno de configuración y verifica el ID del sitio. */
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($data->params->site_id != 0 && $data->params->site_id != 8 && $data->params->site_id != 14) {
                $_ENV['checkCache'] = 1;
            }

            try {
                $UsuarioToken = new UsuarioToken($tokenUsuario, '0');

            } catch (Exception $e) {
                /* Se captura una excepción y se crea un nuevo objeto UsuarioToken. */

                $UsuarioToken = new UsuarioToken($tokenUsuario, '1');

            }

            /* Calcula la diferencia de tiempo entre la fecha actual y una fecha dada. */
            $diff = abs(time() - strtotime($UsuarioToken->getFechaModif()));
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
            $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);


            /* verifica el estado de un usuario y ajusta una variable de cumplimiento. */
            $cumple = true;
            if ($UsuarioToken->estado != "NR") {
                if ($ConfigurationEnvironment->isDevelopment()) {
                    //$cumple = false;
                }

            }

            /* Crea una sesión de usuario si se cumple una condición específica. */
            if ($cumple) {
                $data->session = (object)[
                    'usuario' => $UsuarioToken->getUsuarioId(),
                    'logueado' => true
                ];
            }

            /* Crea un objeto UsuarioMandante y almacena datos en sesión tras validación. */
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
            $site_id = strtolower($data->params->site_id);

            try {
                $_SESSION['usuario'] = $UsuarioMandante->usuarioMandante;
                $_SESSION['usuario2'] = $UsuarioMandante->usumandanteId;
            } catch (Exception $e) {
                /* Manejo de excepciones en PHP, captura de errores sin realizar acciones específicas. */

            }


            /* verificasite_id y lanza excepción si es numérico. */
            if ($data->params->site_id != 0 && $data->params->site_id != 8 && $data->params->site_id != 14) {
                $_ENV['checkCache'] = 1;
            }

            if (is_numeric($site_id)) {
                throw new Exception("Inusual Detected", "100001");

            }


            /* registra un aviso y verifica condiciones para desactivar un token de usuario. */
            syslog(LOG_WARNING, "REQFRONTEND  : " . $UsuarioMandante->mandante . ' ' . $UsuarioMandante->moneda);

            if (floatval($minutes) > 30 && $UsuarioMandante->mandante == 18 && $UsuarioMandante->paisId == 173) {
                $UsuarioToken->setEstado('I');

                $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();

                throw new Exception("No existe Token", "21");

            }


            /* verifica condiciones y actualiza el estado del token del usuario. */
            if (floatval($days) > 1 && $UsuarioMandante->mandante != 14 && !in_array($UsuarioMandante->usuarioMandante, array(17884, 242068, 255499, 255528, 255547, 255584))) {
                $UsuarioToken->setEstado('I');

                $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();

                throw new Exception("No existe Token", "21");
            }


            /* Valida condiciones y actualiza estado de usuario si se cumplen criterios específicos. */
            if (floatval($days) > 5 && $UsuarioMandante->mandante == 14 && !in_array($UsuarioMandante->usuarioMandante, array(17884, 242068, 255499, 255528, 255547, 255584))) {
                $UsuarioToken->setEstado('I');

                $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();

                throw new Exception("No existe Token", "21");
            }


            /* Valida el ID del sitio y lanza una excepción si no coincide. */
            if ($site_id != "" && $site_id != null && $UsuarioMandante->mandante != $site_id) {

                throw new Exception("No puede iniciar sesion en el sitio. ", "21");
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            // CONTINGENCY //


            /* verifica el estado del usuario y lanza excepciones si hay problemas. */
            if ($Usuario->estado == "I") {
                throw new Exception("El usuario ingresado se encuentra inactivo Estado.  ", "21");
            }
            if ($Usuario->contingencia == "A") {
                throw new Exception("No puede iniciar sesion en el sitio.30010 ", "21");
            }


            /* verifica el estado de un usuario y lanza excepciones si está inactivo o eliminado. */
            if ($Usuario->estadoEsp == "I") {

                throw new Exception("El usuario ingresado se encuentra inactivo.20003  ", "21");

            }

            if ($Usuario->eliminado == "S") {

                throw new Exception("El usuario no esta registrado en la plataforma.20029", "21");

            }

            /* Valida condiciones antes de procesar un comando en el sistema. */
            if ($UsuarioMandante->mandante == 2 && $UsuarioToken->cookie != 0 && $UsuarioToken->cookie != 1 && $data->command != "wallet_config") {

                /*$response = array("code" => 30009, "msg" => "billetera no configurada");
                $respuesta= (($response));

                $respuesta = json_encode($respuesta);

                print_r($respuesta);

                exit();*/
            }


            /* verifica un ID de usuario y devuelve una respuesta JSON si coincide. */
            if ($UsuarioToken->getUsuarioId() == 96838 && false) {
                $response['code'] = 20000;

                $response['result'] = 0;
                $respuesta = (($response));

                $respuesta = json_encode($respuesta);

                print_r($respuesta);

                exit();
            }
        } catch (Exception $e) {
            /* Manejo de errores en PHP que devuelve una respuesta JSON para un código específico. */


            if ($e->getCode() == "21") {
                $response['code'] = 20000;

                $response['result'] = 0;
                $respuesta = (($response));

                $respuesta = json_encode($respuesta);

                print_r($respuesta);

                exit();

            }
        }
    }
        /* Verifica si un comando no está en una lista específica de comandos permitidos. */
    if (!in_array($data->command, array('get_registration_data', 'email_valid', 'login', 'login_poker_playtech', 'login2', 'register_user', 'whats_up', 'change_pais', 'change_provincia', 'close-request'
        , 'create_new_user', 'exists_email', 'exists_identificator', 'get_config', 'get_countries', 'get_countries2', 'get_departments'
        , 'get_filtre', 'get_loyalty', 'get_points', 'get_tournaments', 'get_type_games', 'loyalty_information',
            'request_session', 'reset_password', 'reset_user_password', 'restore_login', 'restore_login_site', 'user_feedback',
            'work_with_us', 'get_rooms', 'get_raffles', 'get_lotteries', 'buy_lotterie', 'get_user_bigboost', 'update_user2', 'verify_phone', 'get_teams', 'exists_phone', 'get_raffles', 'shop_bonuses', 'get_lottery', 'road_with_prizes', 'get_stickers', 'get_ward_win', 'user_query', 'register_user_account', 'external_auth', 'exists_phone', 'get_user_hub',
            'validate_cpf', 'get_lottery_wards', 'verify_phone', 'get_coupons', 'get_lottery_wards', 'draw_record', 'get_lottery_betshop', 'get_tickets', 'get_lottery_betshop', 'register_user_new', 'exist_docnumber', 'get_jackpot', 'get_payments2', 'platform_browser', 'mark_referent_invitation'
        )) && ($UsuarioToken == null || $UsuarioMandante == null)) {
        /* Verifica condiciones nulas y prepara una respuesta JSON con código y resultado. */
        $response['code'] = 20000;

        $response['result'] = 0;
        $respuesta = (($response));

        $respuesta = json_encode($respuesta);


        /* imprime una respuesta y establece la zona horaria según el usuario. */
        print_r($respuesta);

        exit();
    }

    if ($UsuarioMandante != null) {

        if ($UsuarioMandante->usuarioMandante == '2944061') {
            $_ENV["TIMEZONE"] = "-03:00";
        }

        if ($UsuarioMandante->usuarioMandante == '2807668') {
            $_ENV["TIMEZONE"] = "-03:00";
        }
    }


    /* llama a una API, decodifica la respuesta y modifica un objeto JSON. */
    $respuesta = resolverAPI($data, $UsuarioToken, $UsuarioMandante);
    $respuesta = json_decode($respuesta);

    if (is_object($respuesta->data)) {
        $respuesta->data->sid = 1;

    }


    /* Verifica un comando y, si es "restore_login", prepara una respuesta con tokens. */
    if ($data->command != "") {
        if ($data->command == "restore_login") {
            $respuesta->data->sid = $respuesta->data->auth_token;
            $respuesta->data->data = (object)[
                'auth_token' => $respuesta->data->auth_token,
                'sid' => $respuesta->data->sid
            ];

        }


    }

    /* convierte una respuesta a JSON y evalúa su tiempo de ejecución. */
    $respuesta = json_encode($respuesta);

    print_r($respuesta);


    try {
        $tmmm = microtime(true) - $start;
        if ($tmmm >= 5) {
            //syslog(LOG_WARNING, "SLOWAPI  :" . json_encode($data) . ' '.$_SERVER['REQUEST_URI'] . ' '. microtime(true) - $tmmm);

        }

    } catch (Exception $e) {
        /* Bloque de código para manejar excepciones en PHP sin realizar ninguna acción. */


    }
}
