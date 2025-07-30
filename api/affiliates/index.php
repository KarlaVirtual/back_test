<?php
/**
 * Index de la api 'affiliates'
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */



use Backend\dto\Banco;
use Backend\dto\Banner;
use Backend\dto\BonoDetalle;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\Concesionario;
use Backend\dto\ConfigMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\PaisMoneda;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\Producto;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\Submenu;
use Backend\dto\Template;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBanner;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLink;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMarketing;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\dto\BonoInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\integrations\casino\Playngo;
use Backend\mysql\BannerMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLinkMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMarketingMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\utils\SessionGeneral;


use Google_Client;


/* activa la depuración de errores si se cumple una condición específica. */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X'){
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["NOLOYALTY"] = 1;

$dir_ip=getenv('HTTP_CLIENT_IP');
if($dir_ip != '' && strpos($dir_ip,'172.105.16.250','') !== false && false){


    print_r($_SERVER);
// Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
// Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
// you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

// Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
// may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

    echo "You have CORS!";
    exit();
}


/* configura encabezados CORS para permitir solicitudes de diferentes orígenes. */
$URI = $_SERVER["REQUEST_URI"];



header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');


/* Configura cabeceras CORS para permitir solicitudes desde orígenes específicos en PHP. */
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');



require_once "require.php";
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type, Bt, X-Token,x-token');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

ini_set('session.use_cookies', '1');

if(end(explode("/", current(explode("?", $_SERVER["REQUEST_URI"])))) != 'getDocument'){
    header('Content-Type: application/json');

}else{
    if($_ENV['debug']){
        print_r('entro1');
    }
    header("Content-type: image/jpeg");
}
$domainSession = ".virtualsoft.tech";

if (strpos($_SERVER['HTTP_REFERER'], "netabet.com.mx") !== FALSE) {
    $domainSession = ".netabet.com.mx";
}


if ($domainSession != "") {
    session_name('SessionName');
    session_set_cookie_params(['SameSite' => 'None', 'Secure' => true]);
    session_set_cookie_params(
        1800,
        ini_get('session.cookie_path'),
        $domainSession
    );
}
$currentCookieParams = session_get_cookie_params();

if (PHP_VERSION_ID >= 70300) {
    session_set_cookie_params([
        'lifetime' => $currentCookieParams["lifetime"],
        'path' => '/',
        'domain' => $domainSession,
        'secure' => "1",
        'httponly' => "1",
        'samesite' => 'None',
    ]);
} else {
    session_set_cookie_params(
        $currentCookieParams["lifetime"],
        '/; samesite=None',
        $domainSession,
        "1",
        "1"
    );
}



$session = new SessionGeneral();
$session->inicio_sesion('_s', false);



if (!function_exists('getallheaders')) {
    /**
     * Obtiene todos los encabezados HTTP de la solicitud actual.
     *
     * Esta función es útil para entornos donde la función `getallheaders`
     * no está disponible de forma nativa. Recorre las variables del servidor
     * y extrae los encabezados HTTP.
     *
     * @return array Un arreglo asociativo con los encabezados HTTP.
     */
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }}



/* obtiene el token de encabezados HTTP, considerando mayúsculas y minúsculas. */
$headers = getallheaders();

$xtoken = $headers['X-Token'];

if($xtoken==''){
    $xtoken = $headers['x-token'];
}

/* Verifica el valor de $xtoken antes de establecer una sesión. */
if ($xtoken != "" && $xtoken != null && $xtoken != 'null' && $xtoken != 'undefined') {
    session_id($xtoken);
}


$session = new SessionGeneral();

/* Inicia sesión, obtiene URI y lee parámetros JSON de la entrada. */
$session->inicio_sesion('_s', false);

$URI = $_SERVER["REQUEST_URI"];

$params = file_get_contents('php://input');
$params = json_decode($params);

/* Código define un arreglo vacío y URLs para APIs de afiliados. */
$response = array();


$urlApiAfiliados = "https://images.virtualsoft.tech/affiliates/";

$URL_AFFILIATES_API = "http://localhost/proyectos/affiliates/global/";

/* Configuración de API y clave de encriptación, manejando solicitudes OPTIONS en PHP. */
$URL_AFFILIATES_API = "https://afiliados.doradobet.com/app/global/";
$claveEncrypt_Retiro = "12hur12b";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}


/* Define una clave de encriptación segura y compleja para proteger datos sensibles. */
$ENCRYPTION_KEY = "D!@#$%^&*";


/*if ($_SESSION["usuario2"] == "") {
    if (end(explode("/", current(explode("?", $URI)))) != "Login" && end(explode("/", current(explode("?", $URI)))) != "getDocument" && end(explode("/", current(explode("?", $URI)))) != "register" && end(explode("/", current(explode("?", $URI)))) != "register") {


        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Usuario no logueado";
        $response["ModelErrors"] = [];
        $response["redirect_url"] = "https://afiliados.doradobet.com/";
        print_r(json_encode($response));

        exit();
    }
}*/

//$URI=str_replace("stst", "setBannerStatAll", $URI);
try {
    switch (end(explode("/", current(explode("?", $URI))))) {

        case 'pruebajson':
            /* procesa un JSON vacío y genera etiquetas XML para cada entrada SEO. */


            $stringjson = '';
            $json = json_decode($stringjson);

            foreach ($json->seo as $key => $jj) {
                print_r("<url>
    <loc>" . key($jj) . "</loc>
</url>");

            }


            break;
        case 'pruebacasino':
            try {

                /* envía un mensaje a Slack sobre una eliminación específica en cron. */
                exit();
                $message = "*CRON: (Eliminamos Ezugi RROLLBACK) * " . " - Fecha: " . date("Y-m-d H:i:s");

                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

                $rules = [];

                /* Define reglas de filtrado para transacciones en un array, con condiciones específicas. */
                array_push($rules, array("field" => "transaccion_api.tipo", "data" => "RROLLBACK", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_api.proveedor_id", "data" => "12", "op" => "eq"));
                array_push($rules, array("field" => "(transaccion_api.fecha_crea)", "data" => '2021-01-25 09:45:46', "op" => "ge"));
                array_push($rules, array("field" => "(transaccion_api.fecha_crea)", "data" => '2021-01-25 19:45:46', "op" => "le"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* Código PHP que convierte un filtro a JSON y crea una instancia de TransaccionApi. */
                $json = json_encode($filtro);


                $select = "transaccion_api.*";
                $grouping = "";


                $TransaccionApiMandante = new TransaccionApi();

                /* obtiene y decodifica transacciones personalizadas en formato JSON, almacenándolas en un array. */
                $data = $TransaccionApiMandante->getTransaccionesCustom($select, "transaccion_api.transapi_id", "asc", 0, 1000, $json, true, $grouping);
                $data = json_decode($data);

                $procesadas = array();
                foreach ($data->data as $key => $value) {
                    try {
                        if (!in_array($value->{'transaccion_api.identificador'}, $procesadas)) {

                            /* Agrega un identificador a un array y crea una nueva instancia de TransaccionJuego. */
                            array_push($procesadas, $value->{'transaccion_api.identificador'});
                            print_r($value->{'transaccion_api.identificador'});
                            $TransaccionJuego = new TransaccionJuego("", $value->{'transaccion_api.identificador'});
                            if ($TransaccionJuego->getEstado() == "A") {



                                /* Se crea una estructura de reglas en JSON para filtrar datos de transacciones. */
                                $rules = [];
                                array_push($rules, array("field" => "transjuego_log.transjuego_id", "data" => $TransaccionJuego->getTransjuegoId(), "op" => "eq"));


                                $filtro = array("rules" => $rules, "groupOp" => "AND");
                                $json = json_encode($filtro);



                                /* Código para obtener registros de transacciones de juego desde la base de datos. */
                                $select = "transjuego_log.*";
                                $grouping = "transjuego_log.transjuegolog_id";


                                $TransjuegoLog = new TransjuegoLog();
                                $data = $TransjuegoLog->getTransjuegoLogsCustom($select, "transjuego_log.transjuegolog_id", "asc", 0, 100, $json, true, $grouping);

                                /* Convierte una cadena JSON en un objeto o array de PHP. */
                                $data = json_decode($data);


                                if (oldCount($data->data) == 1) {

                                    /* Asigna el primer elemento del array 'data' a la variable '$value'. */
                                    $value = $data->data[0];
                                    if (strpos($value->{"transjuego_log.tipo"}, "DEBIT") !== false) {


                                        /* Código para crear un registro de auditoría de transacciones de juego. */
                                        $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();


//  Creamos el log de la transaccion juego para auditoria
                                        $TransjuegoLog2 = new TransjuegoLog();
                                        $TransjuegoLog2->setTransjuegoId($TransaccionJuego->getTransjuegoId());

                                        /* Configura un objeto de transacción para un rollback manual en un juego. */
                                        $TransjuegoLog2->setTransaccionId("ROLLBACK" . $value->{'transjuego_log.transaccion_id'});
                                        $TransjuegoLog2->setTipo('ROLLBACKMANUAL');
                                        $TransjuegoLog2->setTValue(json_encode(array()));
                                        $TransjuegoLog2->setUsucreaId(0);
                                        $TransjuegoLog2->setUsumodifId(0);
                                        $TransjuegoLog2->setValor($value->{'transjuego_log.valor'});


                                        /* Inserta un registro de transacción y establece su valor y estado. */
                                        $TransjuegoLog_id = $TransjuegoLogMySqlDAO->insert($TransjuegoLog2);


                                        $TransaccionJuego->setValorPremio($value->{'transjuego_log.valor'});
                                        $TransaccionJuego->setEstado('I');

                                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                                        /* crea un usuario, registra un crédito y establece su historial. */
                                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                                        $Usuario->creditWin($value->{'transjuego_log.valor'}, $TransjuegoLogMySqlDAO->getTransaction());

                                        $UsuarioHistorial = new UsuarioHistorial();
                                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);

                                        /* Se establece un historial de usuario con parámetros específicos y un valor obtenido. */
                                        $UsuarioHistorial->setDescripcion('');
                                        $UsuarioHistorial->setMovimiento('C');
                                        $UsuarioHistorial->setUsucreaId(0);
                                        $UsuarioHistorial->setUsumodifId(0);
                                        $UsuarioHistorial->setTipo(30);
                                        $UsuarioHistorial->setValor($TransjuegoLog2->getValor());

                                        /* Se inserta un historial de usuario en la base de datos usando transacciones. */
                                        $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
                                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                                        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());

                                        /* Actualiza la transacción del juego y obtiene el registro de la misma. */
                                        $TransaccionJuegoMySqlDAO->update($TransaccionJuego);


                                        $TransjuegoLogMySqlDAO->getTransaction()->commit();


                                    }
                                }
                            }

                        }
                    } catch (Exception $e) {
                        /* Captura excepciones en PHP sin realizar ninguna acción en caso de error. */


                    }
                }


            } catch (Exception $e) {
                /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque. */


            }

            break;


        case 'Logout':
            /* Código para cerrar sesión: limpia datos de sesión y devuelve respuesta exitosa. */

            $_SESSION = array();
            session_destroy();
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = true;

            break;

        case "pruebawwss":


            /* crea instancias de usuario y sesión para ejecutar un reinicio del sistema. */
            $Id = "242068";
            $Command = "sudo reboot";

            $Usuario = new Usuario($Id);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);


            $UsuarioSession = new UsuarioSession();

            /* Se crea un filtro de reglas para filtrar datos de sesiones de usuario. */
            $rules = [];

            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Convierte datos a JSON, obtiene usuarios filtrados y decodifica el resultado. */
            $json = json_encode($filtro);


            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

            $usuarios = json_decode($usuarios);


            /* Envía un mensaje a través de WebSocket para cada usuario en la lista. */
            $usuariosFinal = [];

            foreach ($usuarios->data as $key => $value) {

                $data = array(
                    "messageIntern" => "execCommand",
                    "value" => $Command

                );

                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});

            }



            /* Código para manejar la actualización de saldo de un usuario vía WebSocket. */
            $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

            /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
            $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */


            $UsuarioSession = new UsuarioSession();

            /* Se definen reglas y filtros para consultas basadas en condiciones específicas. */
            $rules = [];

            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte un filtro a JSON y obtiene usuarios personalizados. */
            $json2 = json_encode($filtro);


            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json2, true);

            $usuarios = json_decode($usuarios);


            /* Se procesa una lista de usuarios, enviando un mensaje por WebSocket a cada uno. */
            $usuariosFinal = [];

            foreach ($usuarios->data as $key => $value) {

                $dataF = str_replace("7040" . $UsuarioToken->getRequestId() . "1", "7040" . $value->{'usuario_session.request_id'} . "1", $data);
                print_r($dataF);
                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $dataF);
                $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});

                print_r("2");
            }
            break;


        case 'pruebaTicket':


            /* Se crean instancias y variables para manejar un usuario y un ticket. */
            $UsuarioMandante = new UsuarioMandante("", "17884", "0");
            $ticket = '406686940';

            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;

            try {
                /*
                $ticket = "ITN" . $ticket;

                $TransaccionSportsbook = new \Backend\dto\TransaccionSportsbook('', $ticket, '');

                if ($TransaccionSportsbook->getPremiado() == "S") {

                if ($TransaccionSportsbook->getPremioPagado() == "N") {

                $responseTicket = $TransaccionSportsbook->pagar($UsuarioMandante);

                if ($responseTicket) {

                $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                $WebsocketUsuario->sendWSMessage();

                $response["data"]["result"] = 1;
                }


                } else {

                }

                } else {

                }*/


                try {

                    /* Se crea un objeto `ItTicketEnc` con un ticket y se asigna a la respuesta. */
                    $ItTicketEnc = new ItTicketEnc($ticket);
                    $response["data"]["tt"] = $ticket;

                    if ($ItTicketEnc->premiado == "S" && $ItTicketEnc->premioPagado == "N") {


                        /* Se registran datos de un pago, incluyendo fecha y usuario que modifica. */
                        $ItTicketEnc->premioPagado = 'S';
                        $ItTicketEnc->usumodificaId = $UsuarioMandante->usuarioMandante;
                        $ItTicketEnc->fechaPago = date('Y-m-d');
                        $ItTicketEnc->horaPago = date('H:i:s');

                        $ItTicketEnc->fechaModifica = $ItTicketEnc->fechaPago . ' ' . $ItTicketEnc->horaPago;

                        /* inicializa propiedades de un objeto relacionado con tickets y crea un DAO. */
                        $ItTicketEnc->beneficiarioId = 0;
                        $ItTicketEnc->tipoBeneficiario = 0;
                        $ItTicketEnc->beneficiarioId = 0;
                        $ItTicketEnc->impuesto = '0';

                        $ItTicketEncMySqlDAO = new \Backend\mysql\ItTicketEncMySqlDAO();

                        /* Actualiza ticket, obtiene transacción y acredita premio al usuario. */
                        $ItTicketEncMySqlDAO->update($ItTicketEnc);
                        $Transaction = $ItTicketEncMySqlDAO->getTransaction();

                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $oldBalance = $Usuario->getBalance();
                        $Usuario->credit($ItTicketEnc->vlrPremio, $Transaction);


                        /*$Consecutivo = new Consecutivo("", "REC", "");

                        $consecutivo_recarga = $Consecutivo->numero;
                        $consecutivo_recarga++;

                        $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                        $Consecutivo->setNumero($consecutivo_recarga);


                        $ConsecutivoMySqlDAO->update($Consecutivo);

                        $ConsecutivoMySqlDAO->getTransaction()->commit();*/


                        /* Se crea un objeto UsuarioRecarga y se establecen sus propiedades. */
                        $UsuarioRecarga = new UsuarioRecarga();
//$UsuarioRecarga->setRecargaId($consecutivo_recarga);
                        $UsuarioRecarga->setUsuarioId($UsuarioMandante->usuarioMandante);
                        $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
                        $UsuarioRecarga->setPuntoventaId($UsuarioMandante->usuarioMandante);
                        $UsuarioRecarga->setValor($ItTicketEnc->vlrPremio);

                        /* Se configuran varias propiedades del objeto UsuarioRecarga a cero. */
                        $UsuarioRecarga->setPorcenRegaloRecarga(0);
                        $UsuarioRecarga->setDirIp(0);
                        $UsuarioRecarga->setPromocionalId(0);
                        $UsuarioRecarga->setValorPromocional(0);
                        $UsuarioRecarga->setHost(0);
                        $UsuarioRecarga->setMandante(0);

                        /* Se inicializan atributos de UsuarioRecarga y se crea su DAO para operaciones. */
                        $UsuarioRecarga->setPedido(0);
                        $UsuarioRecarga->setPorcenIva(0);
                        $UsuarioRecarga->setMediopagoId(0);
                        $UsuarioRecarga->setValorIva(0);
                        $UsuarioRecarga->setEstado('A');

                        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);

                        /* Inserta un registro de recarga en MySQL y confirma la transacción realizada. */
                        $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);
                        $consecutivo_recarga = $UsuarioRecarga->recargaId;


// Commit de la transacción
                        $Transaction->commit();


                        /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */

                        /* crea un token de usuario y envía un mensaje WebSocket para actualizar el saldo. */
                        $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                        /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                        $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

                        $UsuarioSession = new UsuarioSession();

                        /* Se crean reglas para filtrar usuarios activos según un ID específico. */
                        $rules = [];

                        array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        /* convierte datos a JSON, consulta usuarios y decodifica la respuesta. */
                        $json2 = json_encode($filtro);


                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json2, true);

                        $usuarios = json_decode($usuarios);


                        /* procesa usuarios y envía datos a través de WebSocket. */
                        $usuariosFinal = [];

                        foreach ($usuarios->data as $key => $value) {

                            $dataF = str_replace("7040" . $UsuarioToken->getRequestId() . "1", "7040" . $value->{'usuario_session.request_id'} . "1", $data);
                            $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $dataF);
                            $WebsocketUsuario->sendWSMessage();

                        }


                    }

                } catch (Exception $e) {
                    /* Bloque que captura excepciones en PHP sin realizar acciones específicas. */

                }


            } catch (Exception $e) {
                /* Bloque de código que captura excepciones y maneja errores sin realizar acciones. */


            }

            break;
        case 'prueba222':
            /* es un fragmento de un bloque 'case' sin acciones definidas. */


            break;

        case 'getDocument':


            /* desactiva la visualización de errores y obtiene un parámetro "r" de la solicitud. */
            error_reporting(0);
            ini_set("display_errors", "OFF");


            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $parameterR = ($_REQUEST["r"]);

            /* reemplaza espacios por '+' y realiza encriptación y desencriptación. */
            $parameterR = (str_replace(" ", "+", $parameterR));

            $payload = $ConfigurationEnvironment->encrypt($parameterR);

            $payload = $ConfigurationEnvironment->decrypt($parameterR);
            if (false) {



                /* verifica si hay una imagen JPEG y procesa su contenido. */
                if (strpos($payload, ".jpg") !== false) {
                    print_r($payload);
//header('Content-Type:' . 'image/jpeg');

                    $fp = fopen("gs://cedulas-1/hello_stream.txt", 'w');
                    $img = file_get_contents(
                        'gs://cedulas-1/' . $payload);

                    $data = base64_encode('gs://cedulas-1/' . $payload);


                    /*if (file_exists(__DIR__ . '/../../../../images/c/' . $payload)) {
                    header('Content-Type:' . 'image/jpeg');
                    $file = '/home/home2/backend/images/c/' . $payload;
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    }*/

                }


                /* verifica si un payload contiene ".png" y envía la imagen correspondiente. */
                if (strpos($payload, ".png") !== false) {

                    header('Content-Type:' . 'image/png');
                    $img = file_get_contents(
                        'gs://cedulas-1/c/' . $payload);

                    $data = base64_encode('gs://cedulas-1/c/' . $payload);

                    /*    if (file_exists(__DIR__ . '/../../../../images/c/' . $payload)) {
                    header('Content-Type:' . 'image/png');
                    $file = '/home/home2/backend/images/c/' . $payload;
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    }*/


                }
            }

            if (true) {


                if (strpos($payload, ".jpg") !== false || strpos($payload, ".png") !== false) {
                    try {



                        /* Muestra información del servidor si el modo debug está habilitado y define variables. */
                        if($_ENV['debug']){
                            print_r($_SERVER);
                        }

                        $bucketName = 'cedulas-1';
                        $objectName = 'c/' . $payload;
// Authenticate your API Client

                        /* Código para configurar un cliente de Google Cloud Storage y autenticarlo. */
                        $client = new Google_Client();
                        $client->setAuthConfig('/etc/private/virtual.json');
                        $client->useApplicationDefaultCredentials();
                        $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

                        $storage = new Google_Service_Storage($client);


                        /* Código para recuperar y guardar una imagen de Google Cloud Storage en un archivo local. */
                        try {
// Google Cloud Storage API request to retrieve the list of objects in your project.
                            $object = $storage->objects->get($bucketName, $objectName);

                            if($_ENV['debug']){
                                print_r($objectName);
                                exit();
                                $response = $storage->objects->get($bucketName, $objectName, ['alt' => 'media']);
// Guarda la imagen en un archivo local
                                $imageData = $response->getBody()->getContents();

                                file_put_contents('/tmp/image'.time().'.png', $imageData);

                            }
                        } catch (Google_Service_Exception $e) {
                            /* Maneja excepciones de Google_Service; lanza error si el bucket no existe. */

// The bucket doesn't exist!
                            if ($e->getCode() == 404) {
//exit(sprintf("Invalid bucket or object names (\"%s\", \"%s\")\n", $bucketName, $objectName));
                            }
                            throw $e;
                        }

                        /* Imprime el objeto si está en modo depuración y construye una URL de descarga. */
                        if($_ENV['debug']){
                            print_r($objectName);
                        }

// build the download URL
                        $uri = sprintf('https://storage.googleapis.com/%s/%s?alt=media&generation=%s', $bucketName, $objectName, $object->generation);

                        /* autoriza un cliente, realiza una solicitud y verifica el estado de respuesta. */
                        $http = $client->authorize();
                        $response = $http->get($uri);

                        if ($response->getStatusCode() != 200) {
                            exit('download failed!' . $response->getBody());
                        }

                        /* Imprime el contenido de la respuesta HTTP como una cadena. */
                        print_r((string)$response->getBody()->getContents());


                    } catch (Exception $e) {
                        /* Maneja excepciones y muestra detalles si el modo "debug" está habilitado. */

                        if($_ENV['debug']){
                            print_r($e);
                        }
//print_r($e);
                    }
                }
            }

            break;

        case 'getFile':

            /* establece el encabezado para descargar un archivo ZIP y recibe un parámetro. */
            header('Content-Type: application/zip');



            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $parameterR = ($_REQUEST["r"]);

            /* reemplaza espacios por '+' y luego cifra y descifra el parámetro. */
            $parameterR = (str_replace(" ", "+", $parameterR));

            $payload = $ConfigurationEnvironment->encrypt($parameterR);

            $payload = $ConfigurationEnvironment->decrypt($parameterR);

            if (true) {

                try {



                    /* Código para autenticar un cliente API de Google y definir un bucket y objeto. */
                    $bucketName = 'cedulas-1';
                    $objectName = 'machine/' . $payload;
// Authenticate your API Client
                    $client = new Google_Client();
                    $client->setAuthConfig('/etc/private/virtual.json');
                    $client->useApplicationDefaultCredentials();

                    /* Código para acceder y listar objetos en Google Cloud Storage usando su API. */
                    $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

                    $storage = new Google_Service_Storage($client);

                    try {
// Google Cloud Storage API request to retrieve the list of objects in your project.
                        $object = $storage->objects->get($bucketName, $objectName);
                    } catch (Google_Service_Exception $e) {
                        /* Maneja excepciones de Google Service, validando si el bucket no existe. */

// The bucket doesn't exist!
                        if ($e->getCode() == 404) {
//exit(sprintf("Invalid bucket or object names (\"%s\", \"%s\")\n", $bucketName, $objectName));
                        }
                        throw $e;
                    }

// build the download URL

                    /* Descarga un objeto de Google Cloud Storage y verifica si fue exitoso. */
                    $uri = sprintf('https://storage.googleapis.com/%s/%s?alt=media&generation=%s', $bucketName, $objectName, $object->generation);
                    $http = $client->authorize();
                    $response = $http->get($uri);

                    if ($response->getStatusCode() != 200) {
                        exit('download failed!' . $response->getBody());
                    }

                    /* Imprime el contenido del cuerpo de la respuesta como una cadena. */
                    print_r((string)$response->getBody());

                } catch (Exception $e) {
                    /* Captura excepciones en PHP, evitando que interrumpan la ejecución del script. */

//print_r($e);
                }
            }

            break;

        /**
         * CheckAuthentication
         *
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
        case 'CheckAuthentication':

//Verifica si ya hubo un logueo

            /* Código que maneja errores de autenticación y redirige a usuarios no logueados. */
            if (!$_SESSION['logueado']) {
                $response["HasError"] = true;
                $response["AlertType"] = "danger";
                $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
                $response["ModelErrors"] = [];
                $response["redirect_url"] = "";

                $response["Data"] = array(
                    "AuthenticationStatus" => 0,

                    "PermissionList" => array(),
                );

            } else {

                try {


                    /* Se crean instancias de UsuarioMandante y Usuario utilizando datos de sesión. */
                    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    /*
                    $UsuarioToken = new UsuarioToken("", $responseU->user_id);

                    $UsuarioToken->setRequestId($json->session->sid);
                    $UsuarioToken->setCookie(encrypt($responseU->user_id . "#" . time()));

                    $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->update($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    */

                    /*
                    "ViewClients","ViewMenuDashBoard","ManageClients","ViewPaymentReport","ViewDepositWithdrawalReport","ViewBalance","ViewSalesReport","ViewClientTurnoverReport","ViewMenuReport",
                    */

                    /*
                    "ViewAgentMenu", "ViewAgentSystem", "ViewAgent", "ViewAgentSubAccounts", "ViewAgentMembers", "ViewMessages", "ViewEmailTemplates", "ManageMessages", "ViewTranslation", "ViewSettings", "ViewStream", "PMViewPartner", "PMViewProduct", "PMViewSale", "hjkhjkhjk", "ViewOddsFeed", "ViewMatch", "ViewSportLimits", "ViewPartnersBooking", "ViewMenuReport", "ViewCasinoReport", "ViewCashDeskReport", "ViewCRM", "ViewSegment", "CreateSegment", "ViewBetShops", "ViewClients", "ManageClients",
                    */


                    /* define una respuesta de éxito y configura una URL según condiciones específicas. */
                    $response["HasError"] = false;
                    $response["AlertType"] = "success";
                    $response["AlertMessage"] = "";
                    $response["ModelErrors"] = [];
                    $Mandante = new Mandante(strtolower($_SESSION["mandante"]));



                    /* Asigna una URL según el mandante y país del usuario en un código PHP. */
                    try {
                        $PaisMandante = new PaisMandante('', strtolower($UsuarioMandante->mandante), $UsuarioMandante->paisId);

                        /* Validación para encontrar la URL en la columna base_url de base de datos*/
                        if (empty($PaisMandante->baseUrl)) {
                            throw new Exception("No se encontró base_url para Mandante ID {$UsuarioMandante->mandante} y País ID {$UsuarioMandante->paisId}.", 300046);
                        }
                        $Mandante->baseUrl = $PaisMandante->baseUrl;
                    } catch (Exception $e) {
                        /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
                    }



                    /* Se asigna el logo del Mandante a dos variables para diferentes usos. */
                    $logoImg = $Mandante->logo;
                    $logoSideNavImg = $Mandante->logo;


//if ($_SESSION['usuario2'] == "5") {
                    if ($_SESSION['usuario2'] == "163") {


                        /* La variable almacena el resultado de la función 'obtenerMenu()', que genera un menú. */
                        $menus_string = obtenerMenu();


                        $response["Data"] = array(
                            "AuthenticationStatus" => 0,
                            "PartnerLimitType" => 1,
                            "FirstName" => $Usuario->nombre,
                            "Settings" => array(
                                "Language" => strtolower($Usuario->idioma),
                                "ReportCurrency" => $Usuario->moneda,
                                "TimeZone" => $Usuario->timezone,

                            ),
                            "logo" => $logoImg,
                            "logoSideNav" => $logoSideNavImg,
                            "LangId" => strtolower($Usuario->idioma),
                            "UserName" => $Usuario->nombre,
                            "CurrencyId" => $Usuario->moneda,
                            "UserId" => $Usuario->usuarioId,
                            "AgentId" => $Usuario->usuarioId,
                            "PermissionList" => $menus_string,
                            "PermissionList2" => array_merge(array("ManageDepositRequests",
                                "ManageWithdrawalRequests", "ManageUsers", "ViewClientBonuses", "ViewPlayers", "ViewAddHocReport", "ViewScout", "ViewCMS", "ViewAffiliate", "SGPlayersView", "SGStatisticsRake", "ViewFinancialReports", "ViewPaymentReport", "AssignAgentCredit", "ManageAgentCredit", "ViewAgentGroups", "ViewAgentCommissionGroups", "ViewAgentPtGroups", "ViewAgentBetLimitGroups", "ViewAgentGroups", "ViewAgentGroups", "ManageAgentCommissionGroups", "ManageAgentBetLimitGroups", "ManageAgentGroups", "ManageClientCredit", "ViewGames", "ViewClientSportBets", "ViewClientTransactions", "ViewClientLogins", "ViewClientCasinoBets", "ViewSportReport", "ViewMenuDashBoard", "ViewDashBoardActivePlayers", "ViewDashBoardNewRegistrations", "ViewDashBoardSportBets", "ViewDashBoardCasinoBets", "ViewDashBoardTopFiveGames", "ViewDashBoardTopSportsByStake", "ViewDashBoardTopFiveSportsbookPlayers", "ViewDashBoardTopFivePlayers", "ViewUsers", "ViewUsersMenu", "ViewUsersLogs", "ViewAgentTransfers", "ViewBalance", "ViewDepositWithdrawalReport", "PMManageSale", "PMManageProduct", "ViewSalesReport", "ViewTurnoverTaxReport", "ViewDepositRequests", "ViewWithdrawalRequests", "ViewDocuments", "ViewFinancialOperations", "ManageAgent", "ViewBetShopUsers", "ViewCashDesks", "ManageBetShopUsers", "ViewClientMessage", "ViewVerificationStep", "ResetClientPassword", "ViewAgentMenu", "ViewAgentSystem", "ViewAgent", "ViewAgentSubAccounts", "ViewAgentMembers", "ViewMessages", "ViewEmailTemplates", "ManageMessages", "ViewTranslation", "ViewSettings", "ViewStream", "PMViewPartner", "PMViewProduct", "PMViewSale", "hjkhjkhjk", "jhkhjkhjk", "ViewOddsFeed", "ViewMatch", "ViewSportLimits", "ViewPartnersBooking", "ViewMenuReport", "ViewCasinoReport", "ViewCashDeskReport", "ViewCRM", "ViewSegment", "CreateSegment", "ViewBetShops", "ViewClients", "ManageClients", "ViewMenuSecurity", "ViewMenuTeacher", "AdminUser", "Contingencia", "Menu", "Perfil", "PerfilOpciones", "Submenu", "UsuarioPerfil", "Clasificador", "Concesionario", "ViewAddHocReport", "ViewMenuManagement", "ActivarRegistros", "AjustarSaldo", "AsignacionCupo", "Bonos", "CuentaCobroEliminar", "GestionRed", "RegistroRapido", "ChequeReimpresion", "RecargaReversion", "GestionContacto", "ViewMenuCash", "FlujoCaja", "PagoPremio", "PagoNotaRetiro", "RecargarCredito", "ViewMenuQueries", "FlujoCajaHistorico", "FlujoCajaResumido", "InformeCasino", "InformeGerencial", "ListadoRecargasRetiros", "PremiosPendientesPagar", "ConsultaOnlineDetalle", "ConsultaOnlineResumen", "MakeCorrection", "Trabajaconnosotros", "ViewSportsBookReports", "ViewBetReport", "ViewSportReport", "ViewCompetitionReport", "ViewMarketReport", "ViewSports", "ViewCompetitions", "ViewClientLogHistory", "ManageTranslation", "ManageProviders", "ManagePartnerProducts"

                            ), $menus_string),

                        );
                    } else {


                        /* Se obtiene un menú y se inicializa un objeto DocumentoUsuario con el ID del usuario. */
                        $menus_string = obtenerMenu();


                        $documentData = "";
                        $DocumentoUsuario = new DocumentoUsuario();
                        $DocumentoUsuario->usuarioId = $Usuario->usuarioId;

                        /* Se obtienen documentos no procesados y se configuran datos según el país del usuario. */
                        $Documentos = $DocumentoUsuario->getDocumentosNoProcesados(1);

                        $langData =array();
                        try{
                            $ConfigMandante = new ConfigMandante('', $Usuario->mandante);
                            $config = json_decode($ConfigMandante->getConfig(), true);

                            $Pais = new Pais($UsuarioMandante->paisId);

                            $langData = $config['languagesDataBackoffice'][strtolower($Pais->iso)];


                        }catch (Exception $e){
                            /* Manejo de excepciones en PHP, captura errores sin interrumpir la ejecución del programa. */


                        }




                        /* verifica documentación y extrae información en formato JSON. */
                        if (oldCount($Documentos) > 0) {
                            $Documentos = json_decode(json_encode($Documentos))[0];
                            $documentData = array(
                                "accept" => false,
                                "slug" => $Documentos->{'descarga.ruta'},
                                "id" => intval($Documentos->{'descarga.descarga_id'}),
                                "checksum" => $Documentos->{'descarga.descarga_id'}
                            );
                        } else {
                            /* establece un arreglo con la clave "accept" como verdadero si no se cumple la condición previa. */

                            $documentData = array(
                                "accept" => true
                            );
                        }

                        /* Asigna una URL según el mandante y país del usuario en un código PHP. */
                        try {
                            $PaisMandante = new PaisMandante('', strtolower($Usuario->mandante), $Usuario->paisId);

                            /* Validación para encontrar la URL en la columna base_url de base de datos*/
                            if (empty($PaisMandante->baseUrl)) {
                                throw new Exception("No se encontró base_url para Mandante ID {$UsuarioMandante->mandante} y País ID {$UsuarioMandante->paisId}.", 300046);
                            }
                            $Mandante->baseUrl = $PaisMandante->baseUrl;
                        } catch (Exception $e) {
                            /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
                        }

                        $response["Data"] = array(
                            "AuthenticationStatus" => 0,
                            "Partner" => intval($_SESSION["mandante"]),
                            "PartnerLimitType" => 1,
                            "FirstName" => $Usuario->nombre,
                            "Settings" => array(
                                "Language" => strtolower($Usuario->idioma),
                                "ReportCurrency" => $Usuario->moneda,
                                "TimeZone" => $Usuario->timezone,

                            ),
                            "LangData" => $langData,
                            "logo" => $logoImg,
                            "logoSideNav" => $logoSideNavImg,
                            "LangId" => strtolower($Usuario->idioma),
                            "UserName" => $Usuario->nombre,
                            "Document" => $documentData,
                            "CurrencyId" => $Usuario->moneda,
                            "UserId" => $Usuario->usuarioId,
                            "UserId2" => $_SESSION['usuario2'],
                            "AgentId" => $Usuario->usuarioId,
                            "UrlAffiliation" => str_replace('//','/',$Mandante->baseUrl ."/?btag=" . encrypt($_SESSION['usuario'] . "_0", $ENCRYPTION_KEY)),
                            "PermissionList" => $menus_string,
                        );
//                            "UrlAffiliation" => "https://doradobet.com/#/?btag=" . encrypt($_SESSION['usuario2'] . "_0", $ENCRYPTION_KEY),

                    }

                } catch (Exception $e) {
                    /* Manejo de errores en autenticación, informa sobre credenciales inválidas y estado. */


                    $response["HasError"] = true;
                    $response["AlertType"] = "danger";
                    $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
                    $response["ModelErrors"] = [];

                    $response["Data"] = array(
                        "AuthenticationStatus" => 0,

                        "PermissionList" => array(),
                    );

                }

            }

            break;

        case "setDocumentUser":

            /* asigna valores de parámetros a las variables $id y $type. */
            $id = $params->Id;
            $type = $params->Type;

            if (is_numeric($id) && is_numeric($type)) {


                /* Registra un documento de usuario si el estado de descarga es activo. */
                $ClientId = $_SESSION["usuario"];

                if ($type == 1) {
                    $Descarga = new Descarga($id);

                    if ($Descarga->estado == "A") {
                        $DocumentoUsuario = new DocumentoUsuario();

                        $DocumentoUsuario->usuarioId = $ClientId;
                        $DocumentoUsuario->documentoId = $Descarga->descargaId;
                        $DocumentoUsuario->version = $Descarga->version;
                        $DocumentoUsuario->estadoAprobacion = "A";

                        $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
                        $DocumentoUsuarioMySqlDAO->insert($DocumentoUsuario);
                        $DocumentoUsuarioMySqlDAO->getTransaction()->commit();
                    }
                }

                /* inserta un registro de documento para un usuario si la descarga está activa. */
                if ($type == 0) {
                    $Descarga = new Descarga($id);

                    if ($Descarga->estado == "A") {
                        $DocumentoUsuario = new DocumentoUsuario();

                        $DocumentoUsuario->usuarioId = $ClientId;
                        $DocumentoUsuario->documentoId = $Descarga->descargaId;
                        $DocumentoUsuario->version = $Descarga->version;
                        $DocumentoUsuario->estadoAprobacion = "R";

                        $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
                        $DocumentoUsuarioMySqlDAO->insert($DocumentoUsuario);
                        $DocumentoUsuarioMySqlDAO->getTransaction()->commit();
                    }

                }

                /* asigna un valor verdadero a la clave "status" en el arreglo "$response". */
                $response["status"] = true;

            } else {
                /* Manejo de errores que indica un problema y sugiere contactar soporte técnico. */

                $response["status"] = false;
                $response["error"] = "Ocurrio un error, comuniquese con soporte.";

            }

            break;

        /**
         * change-password
         *
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
        case "change-password":


            /* Verifica la contraseña antigua del usuario y cambia a la nueva si es correcta. */
            $oldPassword = $params->oldPassword;
            $newPassword = $params->newPassword;


            $Usuario = new Usuario ($_SESSION["usuario"]);

            if ($Usuario->checkClave($oldPassword)) {
                $Usuario->changeClave($newPassword);

                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = true;
                $response["notification"] = array();

            } else {
                /* establece un estado de respuesta negativa y vacía para notificaciones. */

                $response["status"] = false;
                $response["html"] = "";
                $response["result"] = false;
                $response["notification"] = array();

            }

            break;
        case "changePassword":


            /* Asigna contraseñas viejas y nuevas, considerando posibles diferentes capitalizaciones en los parámetros. */
            $oldPassword = $params->oldPassword;
            $newPassword = $params->newPassword;
            if($oldPassword ==''){
                $oldPassword = $params->OldPassword;
                $newPassword = $params->NewPassword;

            }



            /* verifica y cambia la contraseña del usuario autenticado en sesión. */
            $Usuario = new Usuario ($_SESSION["usuario"]);

            if (true) {
                $Usuario->checkClave($oldPassword);
                $Usuario->changeClave($newPassword);

                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = true;
                $response["notification"] = array();

            } else {
                /* define una respuesta negativa con elementos vacíos en caso de error. */

                $response["status"] = false;
                $response["html"] = "";
                $response["result"] = false;
                $response["notification"] = array();

            }

            break;
        case 'resetPassword':


            /* Se reemplazan espacios en el token y se asigna como código de activación. */
            $token = $params->token;
            $password = $params->password;

            $token = str_replace(" ", "+", $token);

            $activation_code = $token;

            /* verifica si el código de activación contiene un guion bajo y continúa. */
            $code = (decrypt($activation_code, $ENCRYPTION_KEY));

            $seguir = true;

            if (strpos($code, "_") == -1) {
                $seguir = false;
            }

            if ($seguir) {

                /* valida si el ID de usuario es numérico, generando un error si no lo es. */
                $usuariologId = explode("_", $code)[0];

                if (!is_numeric($usuariologId)) {
                    $response["success"] = false;
                    $response["error"] = "Ocurrio un error, comuniquese con soporte.1 ";
                } else {

                    /* Calcula la diferencia en horas entre la fecha de creación y el tiempo actual. */
                    $UsuarioLog = new UsuarioLog($usuariologId);

                    $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

                    if ($seguir) {

                        /* Verifica si el código de activación coincide y maneja errores en la respuesta. */
                        if (str_replace("==", "", $UsuarioLog->getValorAntes()) != $activation_code && false) {
                            $response["success"] = false;
                            $response["error"] = "Ocurrio un error, comuniquese con soporte.2 ";

                            $seguir = false;
                        } else {



                            /* Cambia la contraseña del usuario y resetea intentos si son positivos. */
                            $Usuario = new Usuario($UsuarioLog->usuarioId);
                            $Usuario->changeClave($password);


                            if ($Usuario->intentos > 0) {
                                $Usuario->intentos = 0;
                            }


                            /* Cambia el estado de un usuario de 'I' a 'A' y registra el cambio. */
                            if ($Usuario->estado == 'I') {
                                $Usuario->estado = 'A';
                            }


                            $UsuarioLog->setEstado('A');

                            /* Actualiza registros de usuario y log en base de datos mediante DAO. */
                            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                            $UsuarioLogMySqlDAO->update($UsuarioLog);

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($UsuarioLogMySqlDAO->getTransaction());
                            $UsuarioMySqlDAO->update($Usuario);

                            $UsuarioLogMySqlDAO->getTransaction()->commit();


                            /* Establece que la respuesta de una operación fue exitosa. */
                            $response["success"] = true;

                        }
                    }
                }
            } else {
                /* maneja un error, estableciendo el éxito como falso y informando al usuario. */

                $response["success"] = false;
                $response["error"] = "Ocurrio un error, comuniquese con soporte. ";

            }


            break;

        case 'resetpassword':


            /* reemplaza espacios en un token y lo asigna a una variable. */
            $token = $params->token;
            $password = $params->password;

            $token = str_replace(" ", "+", $token);

            $activation_code = $token;

            /* Se decripta un código y se verifica la ausencia de un guion bajo. */
            $code = (decrypt($activation_code, $ENCRYPTION_KEY));

            $seguir = true;

            if (strpos($code, "_") == -1) {
                $seguir = false;
            }

            if ($seguir) {

                /* Se extrae un ID y se valida si es numérico; reporta error si no lo es. */
                $usuariologId = explode("_", $code)[0];

                if (!is_numeric($usuariologId)) {
                    $response["success"] = false;
                    $response["error"] = "Ocurrio un error, comuniquese con soporte.1 ";
                } else {

                    /* Calcula la diferencia en horas entre la fecha de creación y el tiempo actual. */
                    $UsuarioLog = new UsuarioLog($usuariologId);

                    $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

                    if ($seguir) {

                        /* Verifica si el código de activación es incorrecto y responde con un error. */
                        if (str_replace("==", "", $UsuarioLog->getValorAntes()) != $activation_code && false) {
                            $response["success"] = false;
                            $response["error"] = "Ocurrio un error, comuniquese con soporte.2 ";

                            $seguir = false;
                        } else {



                            /* Crea un usuario, cambia su contraseña y reinicia intentos si son positivos. */
                            $Usuario = new Usuario($UsuarioLog->usuarioId);
                            $Usuario->changeClave($password);


                            if ($Usuario->intentos > 0) {
                                $Usuario->intentos = 0;
                            }


                            /* cambia el estado de un usuario inactivo ('I') a activo ('A'). */
                            if ($Usuario->estado == 'I') {
                                $Usuario->estado = 'A';
                            }


                            $UsuarioLog->setEstado('A');

                            /* Código para actualizar datos de usuario y registro en una base de datos MySQL. */
                            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                            $UsuarioLogMySqlDAO->update($UsuarioLog);

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($UsuarioLogMySqlDAO->getTransaction());
                            $UsuarioMySqlDAO->update($Usuario);

                            $UsuarioLogMySqlDAO->getTransaction()->commit();


                            /* asigna el valor verdadero a la clave "success" en un arreglo llamado $response. */
                            $response["success"] = true;

                        }
                    }
                }
            } else {
                /* maneja un error y devuelve un mensaje de soporte al usuario. */

                $response["success"] = false;
                $response["error"] = "Ocurrio un error, comuniquese con soporte. ";

            }


            break;
        case 'verifyCodeReset':


            /* Verifica si el código de activación contiene un guion bajo para continuar. */
            $activation_code = $params->activation_code;
            $code = (decrypt($activation_code, $ENCRYPTION_KEY));

            $seguir = true;

            if (strpos($code, "_") == -1) {
                $seguir = false;
            }

            if ($seguir) {

                /* verifica si $usuariologId es numérico y maneja un error si no lo es. */
                $usuariologId = explode("_", $code)[0];

                if (!is_numeric($usuariologId)) {
                    $response["success"] = false;
                    $response["error"] = "Ocurrio un error, comuniquese con soporte.1 ";
                } else {

                    /* Verifica si un recurso de usuario ha expirado o no es válido. */
                    $UsuarioLog = new UsuarioLog($usuariologId);

                    $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

                    if ($hourdiff > 24 || $UsuarioLog->getEstado() != 'P') {
                        $response["success"] = false;
                        $response["error"] = "El recurso de recuperación ha expirado.";
                        $seguir = false;

                    }


                    /* Verifica un código de activación y actualiza el estado del usuario si es válido. */
                    if ($seguir) {
                        if (str_replace("==", "", $UsuarioLog->getValorAntes()) != $activation_code && false) {
                            $response["success"] = false;
                            $response["error"] = "Ocurrio un error, comuniquese con soporte.2 ";

                            $seguir = false;
                        } else {

                            $UsuarioLog->setEstado('A');
                            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                            $UsuarioLogMySqlDAO->update($UsuarioLog);
                            $UsuarioLogMySqlDAO->getTransaction()->commit();


                            $response["status"] = true;

                        }
                    }
                }
            } else {
                /* maneja un error, asignando un mensaje de falla a la respuesta. */

                $response["success"] = false;
                $response["error"] = "Ocurrio un error, comuniquese con soporte. ";

            }


            break;

        case 'forgotpassword':


            /* obtiene la IP del cliente, un correo y un socio desde parámetros dados. */
            $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $ip = explode(",", $ip)[0];

            $email = $params->email;
            $partner = $params->partner;

            $ConfigurationEnvironment = new ConfigurationEnvironment();



            /* depura un email eliminando caracteres no deseados y lo convierte a minúsculas. */
            $email = $ConfigurationEnvironment->DepurarCaracteres($email);

            $email = $ConfigurationEnvironment->DepurarCaracteres($email);

            $email=preg_replace('/\\\\/', '', $email);

            $partner = strtolower($partner);


            /* Establece un valor predeterminado para $partner y crea un objeto Usuario con un email. */
            if ($partner == "" || $partner == null) {
                $partner = '0';
            }

            $Usuario = new Usuario();
            $Usuario->login = $email;



            /* Verifica si el usuario existe y devuelve un mensaje de error si no es así. */
            if (!$Usuario->exitsLogin(1)) {
                $response["success"] = false;
                $response["error"] = "El usuario no existe. ";

            } else {

                /* Se crean instancias de Usuario y UsuarioLog, estableciendo propiedades relevantes. */
                $Usuario = new Usuario('', $email, 1, $partner);


                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp($ip);


                /* Registro de usuario con IP, tipo de acción y estado para auditoría. */
                $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog->setUsuariosolicitaIp($ip);

                $UsuarioLog->setTipo("TOKENPASS");
                $UsuarioLog->setEstado("P");
                $UsuarioLog->setValorAntes('');

                /* Se inicializan propiedades de $UsuarioLog y se crea un nuevo UsuarioLogMySqlDAO. */
                $UsuarioLog->setValorDespues('');
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);


                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

                /* Inserta un registro de usuario y almacena un código encriptado asociado. */
                $usuariologId = $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();

                $code = (encrypt($usuariologId . "_" . time(), $ENCRYPTION_KEY));


                $UsuarioLog->setValorAntes($code);

                /* actualiza un registro de usuario y confirma la transacción en MySQL. */
                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $usuariologId = $UsuarioLogMySqlDAO->update($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();


                $email = $Usuario->login;


                /* establece una URL y un objeto según condiciones específicas de usuario. */
                $urlAfiliados = "https://afiliados.doradobet.com/password-new/";


                $Mandante = new Mandante($Usuario->mandante);


                /* Asigna una URL según el mandante y país del usuario en un código PHP. */
                try {
                    $PaisMandante = new PaisMandante('', strtolower($Usuario->mandante), $Usuario->paisId);

                    /* Validación para encontrar la URL en la columna base_url de base de datos*/
                    if (empty($PaisMandante->baseUrl)) {
                        throw new Exception("No se encontró base_url para Mandante ID {$Usuario->mandante} y País ID {$Usuario->paisId}.", 300046);
                    }
                    $Mandante->baseUrl = $PaisMandante->baseUrl;
                } catch (Exception $e) {
                    /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
                }


                /* Verifica si el mandante es válido y genera un mensaje para cambio de contraseña. */
                if ($Mandante->mandante != '0') {
                    $urlAfiliados = $Mandante->baseUrl . "/password-new/";

                    $urlAfiliados = str_replace("https://", "https://afiliados.", $urlAfiliados);
                }

//Arma el mensaje para el usuario que se registra
                $mensaje_txt = "Recientemente usted solicito un cambio de contrase&#241;a, es muy f&#225;cil, solo haz click en el siguiente boton y puede cambiar la contrase&#241;a.<br><br> ";

                /* Construye un mensaje HTML para cambiar contraseña con enlace y instrucciones. */
                $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;' href='" . $urlAfiliados . "" . $code . "'><div style=\"
    /* height: 60px; */
    width: 200px;
    margin: 10px auto;
    background: #9E9E9E;
    border-radius: 10px;
    padding: 5px;
    color: white !important;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
    \">Cambiar Contrase&#241;a</div></a> <br><br> Si no te funciona, puedes ingresar este link en tu navegador: " . $urlAfiliados . "" . $code . " <br><br> ";

                $mensaje_txt = $mensaje_txt . "<p>Si no puede cambiar su contrase&#241;a, por favor p&#243;ngase en contacto con nosotros.</p><br><br>";

                /* Se genera un mensaje HTML con el nombre del afiliado alineado a la izquierda. */
                $mensaje_txt = $mensaje_txt . "<p style='text-align: left;'><b>Equipo afiliados.</b> </p>" . "<br>";
                $mensaje_txt = $mensaje_txt . "<p style='text-align: left;'>" . $Mandante->nombre . ". </p>" . "";


                $subject='Recuperaci&#243;n de clave de afiliados';

                switch (strtolower($Usuario->idioma)) {


                    case "pt":
//Arma el mensaje para el usuario que se registra

                        /* Código HTML para enviar instrucciones de recuperación de contraseña a afiliados. */
                        $mensaje_txt = "
Recuperação de Senha - Afiliados<br><br>


Para alterar a sua senha, basta clicar no botão abaixo para redefinir.<br><br>

<a style='text-decoration: blink;' href='" . $urlAfiliados . "" . $code . "'><div style=\"
    /* height: 60px; */
    width: 200px;
    margin: 10px auto;
    background: #9E9E9E;
    border-radius: 10px;
    padding: 5px;
    color: white !important;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
    \">ALTERAR SENHA</div></a>


Caso não obtenha sucesso ao clicar no botão acima, é possível acessar o link abaixo:<br><br>
" . $urlAfiliados . "" . $code . "

* Caso ainda não obtenha êxito ao redefinir a sua senha, por favor, contate um dos nossos atendentes em nosso Chat de Suporte, no canto inferior do nosso website.<br><br>

- Equipe Afiliados " . $Mandante->nombre . "

";
                        $subject='Recuperação de Senha - Afiliados ' . $Mandante->nombre;

                        break;

                }
//$email='danielftg@hotmail.com';
//Destinatarios

                /* Se asigna una variable y se crea un nuevo objeto de configuración. */
                $destinatarios = $email;


                $ConfigurationEnvironment = new ConfigurationEnvironment();


                /* Crea y envía un correo personalizado usando un template y datos del usuario. */
                try {
                    $Mandante = new Mandante($Usuario->mandante);

                    $clasificador = new Clasificador("","TEMPCONTAFI");

                    $template = new Template("",$Mandante->mandante,$clasificador->getClasificadorId(),$Usuario->paisId,$Usuario->idioma);

                    $mensaje_txt =$template->templateHtml;

                    $mensaje_txt = str_replace("#userid#",$Usuario->usuarioId,$mensaje_txt);
                    $mensaje_txt = str_replace("#link#",$urlAfiliados . "" . $code,$mensaje_txt);
                    $mensaje_txt = str_replace("#name#",$Usuario->nombre,$mensaje_txt);
                    $mensaje_txt = str_replace("#partner#",$Mandante->descripcion,$mensaje_txt);
                    $mensaje_txt = str_replace("#email#",$Usuario->login,$mensaje_txt);

                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                    $envio = $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, 'noreply@milbets.com', 'Milbets', 'Registro Afiliados', 'mail_registro.php', 'Registro afiliados', $mensaje_txt, $dominio, $compania, $color_email, $Usuario->mandante);

                } catch (\Exception $e) {
                    /* Maneja excepciones y envía un correo si ocurre un error en el proceso. */


//Envia el mensaje de correo
                    $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, 'noreply@doradobet.com', 'Doradobet', $subject, 'mail_registro.php', $subject, $mensaje_txt, $dominio, $compania, $color_email, $Usuario->mandante);
                }




                /* asigna valores a un arreglo de respuesta, indicando éxito en una operación. */
                $response["status"] = true;
                $response["result"] = $envio;
                $response["success"] = true;


            }


            break;

        /**
         * Login
         *
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
        case 'Login':


            /* Valida si el parámetro 'partner' es numérico; de lo contrario, lanza una excepción. */
            $plataforma = 1;
            $partner = $params->partner;

            if(!is_numeric($partner)){
                throw new Exception("Inusual Detected", "100001");

            }

            /* Valida el formato del username y asigna valores según condiciones específicas. */
            if (strpos($params->username, '@') !== false && strpos($params->username, 'afiliados@doradobet.com') === false) {
//VAFILV
                $usuario = "" . $params->username;

            } else {
                $usuario = $params->username;

                $plataforma = 0;
                $partner = "";
            }

            /* asigna valores de parámetros a variables y establece una condición inicial. */
            $partner = $params->partner;

            $clave = $params->password;


            $seguir = true;


            /* Validación de usuario y contraseña; genera alerta si están vacíos. */
            if ($clave == "" || $usuario == "") {
//$usuario = $params->username;
//$clave = $params->password;

                if ($clave == "" || $usuario == "") {

                    $response["HasError"] = true;
                    $response["AlertType"] = "danger";
                    $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
                    $response["ModelErrors"] = [];
                    $seguir = false;

                }
            }




            /* Se depuran los caracteres de las variables usuario y clave para sanitización. */
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $usuario = $ConfigurationEnvironment->DepurarCaracteres($usuario);
            $clave = $ConfigurationEnvironment->DepurarCaracteres($clave);

            $usuario = $ConfigurationEnvironment->DepurarCaracteres($usuario);

            /* Se eliminan caracteres de escape de las variables $usuario y $clave. */
            $clave = $ConfigurationEnvironment->DepurarCaracteres($clave);

            $usuario=preg_replace('/\\\\/', '', $usuario);
            $clave=preg_replace('/\\\\/', '', $clave);

            if ($seguir) {

                /* inicia sesión de un usuario en una plataforma específica. */
                $Usuario = new Usuario();


                $responseU = $Usuario->login($usuario, $clave, $plataforma, $partner);

                $urlMand = "afiliados.doradobet.com";


                /* Condiciona la URL según el valor del mandante para acceder al dashboard. */
                if ($Usuario->mandante == '8') {
                    $urlMand = "afiliados.ecuabet.com";
                }


                $urlAfiliados = "https://" . $urlMand . "/app/#/dashboard";


                /* configura una URL de afiliados basada en el mandante del usuario. */
                $Mandante = new Mandante($Usuario->mandante);



                /* Asigna una URL según el mandante y país del usuario en un código PHP. */
                try {
                    $PaisMandante = new PaisMandante('', strtolower($Usuario->mandante), $Usuario->paisId);

                    /* Validación para encontrar la URL en la columna base_url de base de datos*/
                    if (empty($PaisMandante->baseUrl)) {
                        throw new Exception("No se encontró base_url para Mandante ID {$Usuario->mandante} y País ID {$Usuario->paisId}.", 300046);
                    }
                    $Mandante->baseUrl = $PaisMandante->baseUrl;
                } catch (Exception $e) {
                    /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
                }

                if ($Mandante->mandante != '0') {

                    if ($Usuario->mandante == '18' && $Usuario->paisId == '146') {
//$Mandante->baseUrl = 'https://gangabet.mx/';
                    }

                    $urlAfiliados = $Mandante->baseUrl . "/app/#/dashboard";

                    $urlAfiliados = str_replace("https://", "https://afiliados.", $urlAfiliados);
                }



                /* define una respuesta exitosa con detalles de autenticación y redirección. */
                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = "";
                $response["ModelErrors"] = [];
                $response["redirect_url"] = $urlAfiliados;
                $response["utoken"] = $responseU->auth_token;

                /* Se obtiene el ID de sesión y se configura el encabezado de autenticación. */
                $response["Sess"] = session_id();


                header('Authentication: ' . $responseU->auth_token);

                $response["Data"] = array(
                    "AuthenticationStatus" => 0,
                    "PermissionList" => array(),
                );


            }

            break;

        /**
         * sendCorreo
         *
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
        case "sendCorreo":

            /* Genera un mensaje de bienvenida con un enlace de activación para nuevos usuarios. */
            exit();

            $email = "danielftg@hotmail.com";
            $code = (encrypt($email, $ENCRYPTION_KEY));

//Arma el mensaje para el usuario que se registra
            $mensaje_txt = "Bienvenido a afiliados.  Por favor ingresa a este link  <a href='https://admin.doradobet.com/affiliates/activate>Aquí</a> o en el siguiente vinculo ";

            /* Código para enviar un mensaje de verificación de correo electrónico con un enlace y recomendaciones. */
            $mensaje_txt = $mensaje_txt . "<a href='https://admin.doradobet.com/affiliates/activate'>https://admin.doradobet.com/affiliates/activate</a> e ingresa el siguiente codigo para verificar tu correo electronico: <br><br>";
            $mensaje_txt = $mensaje_txt . "<b>Código: </b> " . $code . "<br><br>";

            $mensaje_txt = $mensaje_txt . "Nota importante: sugerimos que una vez acceda al sistema por primera vez, cambie la clave inmediatamente; ademas como recomendacion adicional, asegure su cuenta cambiando dicha clave regularmente." . "<br><br>";

//Destinatarios
            $destinatarios = $email;

//Envia el mensaje de correo

            /* envía un correo de bienvenida a afiliados de Doradobet. */
            $envio = EnviarCorreo($destinatarios, 'admin@doradobet.com', 'Doradobet', 'Bienvenido a Afiliados Doradobet', 'mail_registro.php', 'Bienvenido a Afiliados Doradobet', $mensaje_txt, $dominio, $compania, $color_email);


            break;

        /**
         * verify
         *
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
        case "verify":



            /* descifra un código de activación usando una función de desencriptación. */
            $activation_code = $params->activation_code;

            $decode = decrypt($activation_code);

            try {

                /* Se crea un nuevo objeto 'Usuario' con datos decodificados. */
                $Usuario = new Usuario("", "" . $decode);

                if ($Usuario->verifCorreo == "N") {

                    /* Actualiza la verificación de correo de un usuario en la base de datos. */
                    $Usuario->verifCorreo = "S";

                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                    $UsuarioMySqlDAO->update($Usuario);
                    $UsuarioMySqlDAO->getTransaction()->commit();

//Arma el mensaje para el usuario que se registra

                    /* Envía un correo de validación exitosa a los destinatarios especificados. */
                    $mensaje_txt = "Saludos, la validación ha sido exitosa; puedes ingresar cuando la cuenta sea activada por nuestros operadores. ";

//Destinatarios
                    $destinatarios = $decode;

//Envia el mensaje de correo
                    $envio = EnviarCorreo($destinatarios, 'admin@doradobet.com', 'Doradobet', 'Afiliados Doradobet - Validación exitosa', 'mail_registro.php', 'Validación exitosa', $mensaje_txt, $dominio, $compania, $color_email);



                    /* asigna el valor verdadero a la clave "success" en el arreglo $response. */
                    $response["success"] = true;

                } else {
                    /* indica que la operación no fue exitosa asignando un valor falso a "success". */

                    $response["success"] = false;

                }

            } catch (Exception $e) {
                /* Manejo de excepciones en PHP que indica fallo en la respuesta. */

                $response["success"] = false;

            }


            break;

        /**
         * register
         *
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
        case "register":


            /*$log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . trim(file_get_contents('php://input'));
            //Save string to log, use FILE_APPEND to append.

            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);*/



            /* Se asignan valores de parámetros a variables, incluyendo validación de divisas. */
            $countryf = $params->countryf;

            $country = $params->country;
            $currency = ($params->currency != 'PEN' && $params->currency != 'USD' && $params->currency != 'EUR' && $params->currency != 'CRC' && $params->currency != 'CLP' && $params->currency != 'BRL') ? "PEN" : $params->currency;
            $email = "" . $params->email;
            $password = $params->password;

            /* Asignación de variables a partir de parámetros recibidos, probablemente en un formulario. */
            $site = $params->site;
            $skype = $params->skype;
            $name = $params->name;
            $lastname = $params->lastname;
            $phone = $params->phone;

            $partner = $params->partner;

            /* Convierte la variable $partner a minúsculas y asigna '0' si está vacía. */
            $partner = strtolower($partner);

            if ($partner == "" || $partner == null) {
                $partner = '0';
            }



            $Address = '';

            /* Código para inicializar variables relacionadas con ciudad, país, moneda, documento y email. */
            $CityId = 0;
            $CountryId = 0;
            $CurrencyId = $currency;
            $DocumentLegalID = $params->DocumentLegalID;
            $Email = $email;
            $GroupId = 0;

            /* Variables para almacenar información de IP, ubicación y datos de un manager. */
            $IP = '000:000:000:000';
            $Latitud = 0;
            $Longitud = 0;
            $ManagerDocument = '';
            $ManagerName = '';
            $ManagerPhone = '00';

            /* Asignación de variables para información de usuario en un script. */
            $MobilePhone = $phone;
            $Name = $name;
            $Login = $email;
            $Phone = $phone;
            $RegionId = 0;

            $Type = 1;

            /* Se define un tipo de usuario según un valor de variable `$Type`. */
            $tipoUsuario = "";
            $seguir = true;


            if ($Type == 1) {
                $tipoUsuario = 'AFILIADOR';
            }


            /* Código establece variables para documento, nombre y teléfono de representante legal y dirección. */
            $RepresentLegalDocument = '';
            $RepresentLegalName = '';
            $RepresentLegalPhone = '';


            $Address = $Address;

            /* Asignación de variables a partir de parámetros para manipulación de datos de usuario. */
            $CurrencyId = $CurrencyId;
            $Email = $Email;
            $FirstName = $Name;
            $Id = $params->Id;
            $IsSuspended = false;
            $LastLoginIp = "";

            /* Variables en blanco para almacenar información de usuario y del sistema. */
            $LastLoginLocalDate = "";
            $LastName = "";
            $clave = '';
            $SystemName = '';
            $UserId = '';
            $UserName = $email;

            /* inicializa variables y crea un objeto Usuario con parámetros específicos. */
            $Phone = $phone;

            $login = $Login;
            $Password = $password;

            $Usuario = new Usuario("", "", '1', $partner);

            /* Verifica si el inicio de sesión ya existe y devuelve un error si es así. */
            $Usuario->login = $login;
            $Usuario->mandante = $partner;


            if ($Usuario->exitsLogin(1)) {
                $response["success"] = false;
                $response["error"] = array(
                    "email" => "El email ya esta en uso, por favor ingresa otro diferente. ");

            } elseif ($Id != "" && $UserId != "" && $seguir) {
                /* Condición que ejecuta código si $Id y $UserId no son vacíos y $seguir es verdadero. */


            } elseif ($seguir) {



                /* establece variables booleanas y cambia el valor de `$CanReceipt` si es verdadero. */
                $CanReceipt = false;
                $CanDeposit = false;
                $CanActivateRegister = false;

                if ($CanReceipt == true) {
                    $CanReceipt = 'S';
                } else {
                    /* Asigna 'N' a la variable $CanReceipt si la condición previa no se cumple. */

                    $CanReceipt = 'N';
                }


                /* asigna 'S' o 'N' según condiciones de depósito y activación. */
                if ($CanDeposit == true) {
                    $CanDeposit = 'S';
                } else {
                    $CanDeposit = 'N';
                }

                if ($CanActivateRegister == true) {
                    $CanActivateRegister = 'S';
                } else {
                    /* asigna 'N' a $CanActivateRegister si no se cumple una condición anterior. */

                    $CanActivateRegister = 'N';
                }


                /* inicializa un consecutivo y crea una instancia de PuntoVenta. */
                $consecutivo_usuario = 0;
                /*$Consecutivo = new Consecutivo("", "USU", "");

                $consecutivo_usuario = $Consecutivo->numero;

                $consecutivo_usuario++;

                $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                $Consecutivo->setNumero($consecutivo_usuario);


                $ConsecutivoMySqlDAO->update($Consecutivo);

                $ConsecutivoMySqlDAO->getTransaction()->commit();*/

                $PuntoVenta = new PuntoVenta($CashDeskId);


                /* intenta obtener el ID de un país usando una clase llamada "Pais". */
                $countryId = 0;
                try {
                    $Pais = new Pais("", $country);

                    $countryId = $Pais->paisId;
                } catch (Exception $e) {
                    /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


                }

                /* Crea objetos de país y moneda según un país definido en la variable. */
                if($countryf != ''){
                    $Pais = new Pais($countryf);

                    $countryId = $Pais->paisId;

                    $PaisMandante = new PaisMoneda($countryId);
                    $PaisMandante = new PaisMandante('',strtolower($partner),$countryId);

                    $currency = $PaisMandante->moneda;
                }

                /* determina el idioma según el mandante del usuario. */
                if ($partner == '0') {
//$currency='PEN';
//$countryId="173";
                }

                $lang='ES';

                if($Usuario->mandante == 14){
                    $lang='PT';
                }

//$Usuario->usuarioId = $consecutivo_usuario;



                /* Se asignan valores a las propiedades de un objeto Usuario en PHP. */
                $Usuario->nombre = $FirstName;

                $Usuario->estado = 'I';

                $Usuario->fechaUlt = date('Y-m-d H:i:s');

                $Usuario->claveTv = '';


                /* Se asignan valores iniciales a propiedades del objeto Usuario para gestionar su estado. */
                $Usuario->estadoAnt = 'I';

                $Usuario->intentos = 0;

                $Usuario->estadoEsp = 'A';

                $Usuario->observ = '';


                /* Se inicializan propiedades de un objeto usuario en un sistema. */
                $Usuario->dirIp = '';

                $Usuario->eliminado = 'N';

                $Usuario->mandante = $partner;

                $Usuario->usucreaId = '0';


                /* Se asignan valores a atributos del objeto Usuario, incluyendo un token generado. */
                $Usuario->usumodifId = '0';

                $Usuario->claveCasino = '';
                $token_itainment = GenerarClaveTicket2(12);

                $Usuario->tokenItainment = $token_itainment;


                /* Se asignan valores vacíos a propiedades del objeto Usuario relacionadas con retiro. */
                $Usuario->fechaClave = '';

                $Usuario->retirado = '';

                $Usuario->fechaRetiro = '';

                $Usuario->horaRetiro = '';


                /* Se establecen atributos del objeto Usuario, como retiro, bloqueo y estado. */
                $Usuario->usuretiroId = '0';

                $Usuario->bloqueoVentas = 'N';

                $Usuario->infoEquipo = '';

                $Usuario->estadoJugador = 'AC';


                /* Se inicializan propiedades del objeto $Usuario para gestionar información del usuario. */
                $Usuario->tokenCasino = '';

                $Usuario->sponsorId = 0;

                $Usuario->verifCorreo = 'N';

                $Usuario->paisId = $countryId;


                /* Se asignan valores a propiedades del objeto Usuario, configurando moneda, idioma y permisos. */
                $Usuario->moneda = $currency;

                $Usuario->idioma = $lang;

                $Usuario->permiteActivareg = $CanActivateRegister;

                $Usuario->test = 'N';


                /* Configuración de variables para un usuario, incluyendo tiempo de depósito y exclusión. */
                $Usuario->tiempoLimitedeposito = '0';

                $Usuario->tiempoAutoexclusion = '0';

                $Usuario->cambiosAprobacion = 'S';

                $Usuario->timezone = '-5';


                /* inicializa propiedades de un objeto Usuario con valores específicos y actuales. */
                $Usuario->puntoventaId = 0;

                $Usuario->fechaCrea = date('Y-m-d H:i:s');

                $Usuario->origen = 0;

                $Usuario->fechaValida = date('Y-m-d H:i:s');

                /* Se asignan valores de estado y contingencia a un objeto usuario. */
                $Usuario->estadoValida = 'N';
                $Usuario->usuvalidaId = 0;
                $Usuario->fechaValida = date('Y-m-d H:i:s');
                $Usuario->estadoValida = 'N';
                $Usuario->contingencia = 'I';
                $Usuario->contingenciaDeportes = 'I';

                /* Se asignan valores de contingencia y ubicación a un objeto Usuario. */
                $Usuario->contingenciaCasino = 'I';
                $Usuario->contingenciaCasvivo = 'I';
                $Usuario->contingenciaVirtuales = 'I';
                $Usuario->contingenciaPoker = 'I';
                $Usuario->restriccionIp = 'I';
                $Usuario->ubicacionLongitud = '';

                /* Código que asigna valores a propiedades de un objeto 'Usuario'. */
                $Usuario->ubicacionLatitud = '';
                $Usuario->usuarioIp = '';
                $Usuario->tokenGoogle = 'I';
                $Usuario->tokenLocal = 'I';
                $Usuario->saltGoogle = '';
                $Usuario->skype = $skype;

                /* Asigna valores a atributos del objeto Usuario, incluyendo plataforma y fechas. */
                $Usuario->plataforma = 1;


                $Usuario->fechaActualizacion = $Usuario->fechaCrea;
                $Usuario->documentoValidado = "A";
                $Usuario->fechaDocvalido = $Usuario->fechaCrea;

                /* Se inicializa un usuario y se inserta en la base de datos MySQL. */
                $Usuario->usuDocvalido = 0;

                $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                $UsuarioMySqlDAO->insert($Usuario);

                $consecutivo_usuario = $Usuario->usuarioId;


                /* Se configura un objeto UsuarioConfig con propiedades relacionadas a permisos y datos del usuario. */
                $UsuarioConfig = new UsuarioConfig();
                $UsuarioConfig->permiteRecarga = $CanDeposit;
                $UsuarioConfig->pinagent = '';
                $UsuarioConfig->reciboCaja = $CanReceipt;
                $UsuarioConfig->mandante = $partner;
                $UsuarioConfig->usuarioId = $consecutivo_usuario;



                /* Se crea una nueva instancia de la clase Concesionario en PHP. */
                $Concesionario = new Concesionario();

                if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

                    /* Se crea un objeto UsuarioPerfil con datos de usuario, perfil, mandante y país. */
                    $UsuarioPerfil = new UsuarioPerfil();
                    $UsuarioPerfil->usuarioId = $consecutivo_usuario;

                    $UsuarioPerfil->perfilId = $tipoUsuario;
                    $UsuarioPerfil->mandante = $partner;
                    $UsuarioPerfil->pais = 'N';

                    /* establece una propiedad y asigna un ID de usuario a un concesionario. */
                    $UsuarioPerfil->global = 'N';


// $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);

                    $Concesionario->setUsupadreId($_SESSION["usuario"]);

                    /* Código que configura identificadores y porcentajes para un concesionario en un sistema. */
                    $Concesionario->setUsuhijoId($consecutivo_usuario);
                    $Concesionario->setusupadre2Id(0);
                    $Concesionario->setusupadre3Id(0);
                    $Concesionario->setusupadre4Id(0);
                    $Concesionario->setPorcenhijo(0);
                    $Concesionario->setPorcenpadre1(0);

                    /* Se inicializan varios atributos del objeto Concesionario a cero. */
                    $Concesionario->setPorcenpadre2(0);
                    $Concesionario->setPorcenpadre3(0);
                    $Concesionario->setPorcenpadre4(0);
                    $Concesionario->setProdinternoId(0);
                    $Concesionario->setMandante(0);
                    $Concesionario->setUsucreaId(0);

                    /* establece el ID de usuario modificador y el estado del concesionario. */
                    $Concesionario->setUsumodifId(0);
                    $Concesionario->setEstado("A");
                } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {

                    /* Se crea un objeto UsuarioPerfil y se asignan sus propiedades correspondientes. */
                    $UsuarioPerfil = new UsuarioPerfil();
                    $UsuarioPerfil->usuarioId = $consecutivo_usuario;
                    $UsuarioPerfil->perfilId = $tipoUsuario;
                    $UsuarioPerfil->mandante = $partner;
                    $UsuarioPerfil->pais = 'N';
                    $UsuarioPerfil->global = 'N';



                    /* Se crea un objeto Concesionario y se establecen sus identificadores de usuario. */
                    $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);

                    $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
                    $Concesionario->setUsuhijoId($consecutivo_usuario);
                    $Concesionario->setusupadre2Id($_SESSION["usuario"]);
                    $Concesionario->setusupadre3Id(0);

                    /* Se están estableciendo valores iniciales de porcentaje en un objeto Concesionario. */
                    $Concesionario->setusupadre4Id(0);
                    $Concesionario->setPorcenhijo(0);
                    $Concesionario->setPorcenpadre1(0);
                    $Concesionario->setPorcenpadre2(0);
                    $Concesionario->setPorcenpadre3(0);
                    $Concesionario->setPorcenpadre4(0);

                    /* establece propiedades iniciales para un objeto "Concesionario". */
                    $Concesionario->setProdinternoId(0);
                    $Concesionario->setMandante(0);
                    $Concesionario->setUsucreaId(0);
                    $Concesionario->setUsumodifId(0);
                    $Concesionario->setEstado("A");
                } else {


                    /* Se crea un nuevo perfil de usuario con atributos específicos asignados. */
                    $UsuarioPerfil = new UsuarioPerfil();
                    $UsuarioPerfil->usuarioId = $consecutivo_usuario;
                    $UsuarioPerfil->perfilId = $tipoUsuario;
                    $UsuarioPerfil->mandante = $partner;
                    $UsuarioPerfil->pais = 'N';
                    $UsuarioPerfil->global = 'N';


                    /* Configuración de IDs y porcentaje en un objeto Concesionario. */
                    $Concesionario->setUsupadreId(0);
                    $Concesionario->setUsuhijoId($consecutivo_usuario);
                    $Concesionario->setusupadre2Id(0);
                    $Concesionario->setusupadre3Id(0);
                    $Concesionario->setusupadre4Id(0);
                    $Concesionario->setPorcenhijo(0);

                    /* Establece valores iniciales para porcentajes y parámetros del concesionario. */
                    $Concesionario->setPorcenpadre1(0);
                    $Concesionario->setPorcenpadre2(0);
                    $Concesionario->setPorcenpadre3(0);
                    $Concesionario->setPorcenpadre4(0);
                    $Concesionario->setProdinternoId(0);
                    $Concesionario->setMandante(0);

                    /* establece valores iniciales para un concesionario: usuarios creador, modificador y estado. */
                    $Concesionario->setUsucreaId(0);
                    $Concesionario->setUsumodifId(0);
                    $Concesionario->setEstado("A");
                }



                /* Se crea un objeto UsuarioPremiomax y se inicializan sus propiedades. */
                $UsuarioPremiomax = new UsuarioPremiomax();


                $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

                $UsuarioPremiomax->premioMax = 0;


                /* Inicializa propiedades de un objeto $UsuarioPremiomax con valores predeterminados. */
                $UsuarioPremiomax->usumodifId = 0;

                $UsuarioPremiomax->fechaModif = "";

                $UsuarioPremiomax->cantLineas = 0;

                $UsuarioPremiomax->premioMax1 = 0;


                /* Inicializa variables de un objeto con valores numéricos en cero. */
                $UsuarioPremiomax->premioMax2 = 0;

                $UsuarioPremiomax->premioMax3 = 0;

                $UsuarioPremiomax->apuestaMin = 0;

                $UsuarioPremiomax->valorDirecto = 0;


                /* inicializa propiedades de un objeto relacionado con un usuario y su premio. */
                $UsuarioPremiomax->premioDirecto = 0;

                $UsuarioPremiomax->mandante = $partner;

                $UsuarioPremiomax->optimizarParrilla = "N";

                $UsuarioPremiomax->textoOp1 = "";


                /* Se asignan valores vacíos a propiedades de un objeto en PHP. */
                $UsuarioPremiomax->textoOp2 = "";

                $UsuarioPremiomax->urlOp2 = "";

                $UsuarioPremiomax->textoOp3 = 0;

                $UsuarioPremiomax->urlOp3 = 0;


                /* Se inicializan valores de eventos y diarios para un usuario en un punto de venta. */
                $UsuarioPremiomax->valorEvento = 0;

                $UsuarioPremiomax->valorDiario = 0;


                $PuntoVenta = new PuntoVenta();

                /* asigna valores a propiedades de un objeto llamado PuntoVenta. */
                $PuntoVenta->descripcion = $site;
                $PuntoVenta->nombreContacto = $ManagerName;
                $PuntoVenta->ciudadId = $CityId->Id;
                $PuntoVenta->ciudadId = $CityId;
                $PuntoVenta->direccion = $Address;
                $PuntoVenta->barrio = '';

                /* Se asignan valores a propiedades del objeto $PuntoVenta en PHP. */
                $PuntoVenta->telefono = $Phone;
                $PuntoVenta->email = $Email;
                $PuntoVenta->periodicidadId = 0;
                $PuntoVenta->clasificador1Id = 0;
                $PuntoVenta->clasificador2Id = 0;
                $PuntoVenta->clasificador3Id = 0;

                /* Inicializa valores y comisiones en el objeto PuntoVenta para su uso posterior. */
                $PuntoVenta->valorRecarga = 0;
                $PuntoVenta->valorCupo = '0';
                $PuntoVenta->valorCupo2 = '0';
                $PuntoVenta->porcenComision = '0';
                $PuntoVenta->porcenComision2 = '0';
                $PuntoVenta->estado = 'A';

                /* Se asignan propiedades a un objeto $PuntoVenta, configurando usuario, mandante, moneda e idioma. */
                $PuntoVenta->usuarioId = '0';
                $PuntoVenta->mandante = $partner;
                $PuntoVenta->moneda = $CurrencyId;
//$PuntoVenta->moneda = $CurrencyId->Id;
                $PuntoVenta->idioma = 'ES';
                $PuntoVenta->cupoRecarga = 0;

                /* Inicializa variables de créditos y configura un usuario en una base de datos. */
                $PuntoVenta->creditosBase = 0;
                $PuntoVenta->creditos = 0;
                $PuntoVenta->creditosAnt = 0;
                $PuntoVenta->creditosBaseAnt = 0;
                $PuntoVenta->usuarioId = $consecutivo_usuario;


                $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO($UsuarioMySqlDAO->getTransaction());


                /* Código que inserta datos en bases de datos utilizando transacciones de MySQL. */
                $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO($UsuarioMySqlDAO->getTransaction());

                $ConcesionarioMySqlDAO->insert($Concesionario);

                $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($UsuarioMySqlDAO->getTransaction());

                $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);


                /* Código que gestiona la inserción de datos de usuario y punto de venta en MySQL. */
                $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($UsuarioMySqlDAO->getTransaction());

                $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

                $UsuarioConfigMySqlDAO->insert($UsuarioConfig);

                $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($UsuarioMySqlDAO->getTransaction());

                /* Inserta un punto de venta, confirma transacción y actualiza la clave del usuario. */
                $PuntoVentaMySqlDAO->insert($PuntoVenta);

                $UsuarioMySqlDAO->getTransaction()->commit();


                $UsuarioMySqlDAO->updateClave($Usuario, $Password);


                /* Código que configura respuesta y mensaje de bienvenida para nuevo usuario registrado. */
                $response["id"] = $consecutivo_usuario;

                $response["success"] = true;

                $Mandante = new Mandante($Usuario->mandante);


//Arma el mensaje para el usuario que se registra
                $mensaje_txt = "Bienvenido al plataforma de afiliados de " . $Mandante->nombre . ". Los operadores verificaran la información y activaran la cuenta, le avisaremos cuando esta este activada. ";

//Destinatarios

                /* Asignación de la propiedad login del objeto Usuario a la variable destinatarios. */
                $destinatarios = $Usuario->login;

//Envia el mensaje de correo
//$envio = EnviarCorreo($destinatarios, 'admin@doradobet.com', 'Doradobet', 'Afiliados Doradobet - Registro exitoso', 'mail_registro.php', 'Bienvenido', $mensaje_txt, $dominio, $compania, $color_email);
                $subject='Registro de Afiliados ' . $Mandante->nombre;


                /* genera un mensaje de bienvenida en portugués para nuevos usuarios registrados. */
                switch (strtolower($Usuario->idioma)) {


                    case "pt":
//Arma el mensaje para el usuario que se registra
                        $mensaje_txt = "
Bem vindo à plataforma de Afiliados da ".$Mandante->nombre."!<br><br>

Os nossos operadores verificaram a informação e validaram a conta. Enviaremos uma notificação quando estiver ativada!

";
                        $subject='Registro de Afiliados ' . $Mandante->nombre;



                        break;

                }


                /* Se crea una nueva instancia de la clase ConfigurationEnvironment. */
                $ConfigurationEnvironment = new ConfigurationEnvironment();




                /* envía un correo electrónico utilizando un template con datos del usuario. */
                try {
                    $clasificador = new clasificador("","TEMEMREGAFI");

                    $template = new Template("",$Mandante->mandante,$clasificador->getClasificadorId(),$Usuario->paisId,$Usuario->idioma);

                    $mensaje_txt =$template->templateHtml;

                    $mensaje_txt = str_replace("#userid#",$Usuario->usuarioId,$mensaje_txt);
                    $mensaje_txt = str_replace("#password#",$Password,$mensaje_txt);
                    $mensaje_txt = str_replace("#Name#",$Usuario->nombre,$mensaje_txt);
                    $mensaje_txt = str_replace("#Mandante#",$Mandante->mandante,$mensaje_txt);
                    $mensaje_txt = str_replace("#Email#",$Usuario->login,$mensaje_txt);
                    $mensaje_txt = str_replace("#Country#",$Usuario->paisId,$mensaje_txt);

                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                    $envio = $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, 'noreply@milbets.com', 'Milbets', 'Registro Afiliados', 'mail_registro.php', 'Registro afiliados', $mensaje_txt, $dominio, $compania, $color_email, $Usuario->mandante);

                } catch (\Exception $e) {
                    /* envía un correo en caso de capturar una excepción. */


//Envia el mensaje de correo
                    $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, 'noreply@doradobet.com', 'Doradobet', $subject, 'mail_registro.php', $subject, $mensaje_txt, $dominio, $compania, $color_email, $Usuario->mandante);
                }

            } else {
                /* maneja un error, estableciendo un mensaje de éxito y sin errores de modelo. */

                $response["HasError"] = true;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = "Error no puede crearlo";
                $response["ModelErrors"] = [];

            }


            break;

        /**
         * getWidgetsData
         *
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
        case "getWidgetsData":



            /* Se crea un objeto UsuarioMandante y se establece un filtro de fecha. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $filter = $params->filter;
            $action = $filter->action;

            $dateFilter1 = date("Y-m-d 00:00:00", strtotime('-1 days'));

            /* Se configuran fechas y se inicializa un reporte de productos con valores en cero. */
            $dateFilter2 = date("Y-m-d 23:59:59", strtotime('-1 days'));

            $dateFilterFrom = date("Y-m-d H:i:s", strtotime($filter->date->from));
// $dateFilterFrom = "2018-01-01 00::s";
            $dateFilterTo = date("Y-m-d 23:59:59", strtotime($filter->date->to));


            $productsReportPlayersTotal = array(array(
                "administrativeCost" => 0,
                "deposit" => 0,
                "bets" => 0,
                "wins" => 0,
                "grossRevenue" => 0,
                "expences" => 0,
                "convertedBonuses" => 0,
                "netRevenue" => 0,
                "bonus" => 0,
                "tax" => 0,
                "commission" => 0,

            ));



            /* Inicializa un array para estadísticas de productos y obtiene parámetros de configuración. */
            $TotalProducsStatistics = array();


//Obtenemos los montos de los productos en las fechas
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;

            /* asigna valores predeterminados a variables si están vacías. */
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* Establece un límite de filas y define reglas para filtrar datos. */
            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));

            /* Construye un filtro JSON con reglas basadas en fechas y usuarios. */
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));

            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            /* Se crea un resumen de comisiones y se decodifica en formato JSON. */
            $UsucomisionResumen = new UsucomisionResumen();
            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total,usucomision_resumen.tipo,producto_interno.abreviado ", "usucomision_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.tipo");
            $UsucomisionResumens = json_decode($UsucomisionResumens);

            $final = array();
            $array1 = array(

                "bettingGames " => "0",
                "bettingGamesCommission " => "0",
                "bettingGamesProfit " => "0",
                "brandId " => "0",
                "brandIdCommission " => "0",
                "brandIdProfit " => "0",
                "liveGames " => "0",
                "liveGamesCommission " => "0",
                "liveGamesProfit " => "0",
                "nativePoker " => "0",
                "nativePokerCommission " => "0",
                "nativePokerProfit " => "0",
                "poolBettingGames " => "0",
                "poolBettingGamesCommission " => "0",
                "poolBettingGamesProfit " => "0",
                "skillGames " => "0",
                "skillGamesCommission " => "0",
                "skillGamesProfit " => "0",
                "slots " => "0",
                "slotsCommission " => "0",
                "slotsProfit " => "0",
                "sportsbook " => "0",
                "sportsbookCommission " => "0",
                "sportsbookProfit " => "0",
                "tableGames " => "0",
                "tableGamesCommission " => "0",
                "tableGamesProfit " => "0",
                "total " => "0",
                "totalCommission " => "0",
                "totalProfit " => "0",
                "videoPoker " => "0",
                "videoPokerCommission " => "0",
                "videoPokerProfit " => "0",
                "virtualGames " => "0",
                "virtualGamesCommission " => "0",
                "virtualGamesProfit " => "0"

            );
            foreach ($UsucomisionResumens->data as $key => $value) {


                switch ($value->{'producto_interno.abreviado'}) {

                    case "BETSPORT":
                        /* Actualiza totales de apuestas y ganancias en un reporte de productos deportivos. */

                        $productsReportPlayersTotal[0]["bets"] = $productsReportPlayersTotal[0]["bets"] + $value->{'.total'};
                        $array1["sportsbook"] = $array1["sportsbook"] + $value->{'.total'};
                        $array1["total"] = $array1["total"] + $value->{'.total'};
//$array1["sportsbookProfit"] = $array1[0]["sportsbookProfit"] + $value->{'.total'};
                        break;


                    case "WINSPORT":
                        /* Actualiza estadísticas de ganancias en un reporte y ajusta balances de apuestas. */

                        $productsReportPlayersTotal[0]["wins"] = $productsReportPlayersTotal[0]["wins"] + $value->{'.total'};
                        $array1["sportsbook"] = $array1["sportsbook"] - $value->{'.total'};
                        $array1["total"] = $array1["total"] - $value->{'.total'};

// $array1["sportsbookProfit"] = $array1[0]["sportsbookProfit"] + $value->{'.total'};
                        break;

                    case "DEPOSITO":
                        /* Suma el valor del depósito al total en un informe de productos. */

                        $productsReportPlayersTotal[0]["deposit"] = $productsReportPlayersTotal[0]["deposit"] + $value->{'.total'};

                        break;


                }


            }


            /* añade datos a un arreglo y establece una variable para reportes de productos. */
            array_push($TotalProducsStatistics, $array1);


            $productsReportByPlayersTotals = array();


//Obtenemos el monto por producto por fecha


            $MaxRows = $params->MaxRows;

            /* Se asignan variables y se inicializa $SkeepRows si está vacío. */
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Construye un filtro con reglas para consultar datos basado en fechas y usuario. */
            $rules = [];
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* procesa un resumen de comisiones y lo convierte a formato JSON. */
            $json = json_encode($filtro);

            $UsucomisionResumen = new UsucomisionResumen();
            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total,usucomision_resumen.tipo,producto_interno.abreviado,DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m-%d') fecha ", "DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m-%d')", "asc ", $SkeepRows, $MaxRows, $json, true, "DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m-%d'),usucomision_resumen.tipo");
            $UsucomisionResumens = json_decode($UsucomisionResumens);

            $final = array();

            /* Variable en PHP que inicializa una cadena vacía para almacenar una fecha. */
            $fecha = "";

            foreach ($UsucomisionResumens->data as $key => $value) {

                if ($fecha != $value->{'.fecha'}) {

                    /* agrega un arreglo a $final si $fecha no está vacío. */
                    if ($fecha != "") {
                        array_push($final, $array1);
                    }
                    $fecha = $value->{'.fecha'};
                    $array1 = array(
                        "administrativeCost" => "0",
                        "bets" => "0",
                        "bonus" => "0",
                        "commission" => "0",
                        "convertedBonuses" => "0",
                        "date" => $fecha,
                        "deposit" => "0",
                        "expences" => "0",
                        "grossRevenue" => "0",
                        "netRevenue" => "0",
                        "tax" => "0",
                        "wins" => "0"
                    );

                }



                /* suma totales a arrays según el tipo de producto. */
                switch ($value->{'producto_interno.abreviado'}) {

                    case "BETSPORT":
                        $array1["bets"] = $array1["bets"] + $value->{'.total'};
                        break;

                    case "WINSPORT":
                        $array1["wins"] = $array1["wins"] + $value->{'.total'};
                        break;

                    case "DEPOSITO":
                        $array1["deposit"] = $array1["deposit"] + $value->{'.total'};

                        break;


                }


            }


            /* Se añade un array a otro y se prepara un reporte de jugadores. */
            array_push($final, $array1);


//Obtenemos TOP Jugadores
            $productsReportByPlayersTotals = $final;

            $MediaStat = array();


//Obtenemos el monto por producto por fecha



            /* asigna valores de parámetros y establece un valor predeterminado para $SkeepRows. */
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* asigna valores predeterminados a variables si están vacías. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Se crean reglas de filtrado para consultas con fechas y usuario específico. */
            $rules = [];
            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
            array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte datos a JSON y obtiene un resumen de marketing personalizado. */
            $json = json_encode($filtro);

            $UsumarketingResumen = new UsumarketingResumen();
            $UsumarketingResumens = $UsumarketingResumen->getUsumarketingResumenCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo,DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d') fecha ", "DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d')", "asc ", $SkeepRows, $MaxRows, $json, true, "DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d'),usumarketing_resumen.tipo", "DATE_FORMAT(usumarketing_resumen.fecha_crea, '%Y-%m-%d'),usumarketing_resumen.tipo");
            $UsumarketingResumens = json_decode($UsumarketingResumens);

            $final = array();

            /* Se inicializan una cadena vacía y un arreglo vacío en PHP. */
            $fecha = "";
            $array1 = array();

            foreach ($UsumarketingResumens->data as $key => $value) {


                /* compara fechas y almacena datos en un arreglo si son diferentes. */
                if ($fecha != $value->{'.fecha'}) {
                    if ($fecha != "") {
                        array_push($final, $array1);
                    }
                    $fecha = $value->{'.fecha'};
                    $array1 = array(
                        "date" => $fecha
                    );

                }



                /* Se actualizan contadores de visitas y clics según el tipo de evento recibido. */
                switch ($value->{'usumarketing_resumen.tipo'}) {

                    case "LINKVISIT":
                        $array1["visits"] = $array1["visits"] + $value->{'.total'};
                        break;

                    case "CLICKBANNER":
                        $array1["clicks"] = $array1["clicks"] + $value->{'.total'};
                        break;


                }


            }


            /* Se agrega $array1 a $final y se preparan datos para obtener los mejores jugadores. */
            array_push($final, $array1);

//Obtenemos TOP Jugadores
            $MediaStat = $final;


            $TopUsers = array();


            /* asigna valores y gestiona un caso para filas a omitir. */
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* inicializa $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Se crean reglas de filtrado para consultas con condiciones específicas. */
            $rules = [];
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Se codifica y decodifica un resumen de comisiones de usuarios en PHP. */
            $json = json_encode($filtro);

            $UsucomisionResumen = new UsucomisionResumen();
            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total,usucomision_resumen.usuarioref_id ", "usucomision_resumen.usuarioref_id", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usuarioref_id");
            $UsucomisionResumens = json_decode($UsucomisionResumens);


            foreach ($UsucomisionResumens->data as $key => $value) {
                $array = array(
                    "playerId" => $value->{'usucomision_resumen.usuarioref_id'},
                    "profit" => $value->{'.total'},
                    "commission" => $value->{'.totalcomision'}
                );

                array_push($TopUsers, $array);


            }


            /* Variables inicializan contadores para clics y registros, tanto actuales como del día anterior. */
            $sumClick = 0;
            $sumClickAyer = 0;
            $sumClickTodos = 0;

            $sumRegistro = 0;
            $sumRegistroAyer = 0;

            /* Inicializa variables para sumar registros y comisiones en distintos contextos. */
            $sumRegistroTodos = 0;

            $sumComision = 0;
            $sumComisionAyer = 0;
            $sumComisionTodos = 0;


//Obtenemos el marketing en las fechas


            $MaxRows = $params->MaxRows;

            /* asigna valores a OrderedItem y SkeepRows, estableciendo 0 si está vacío. */
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Se define un filtro con reglas de comparación para realizar consultas de datos. */
            $rules = [];
            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
            array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Se codifica un filtro JSON y se obtiene un resumen de datos agrupados. */
            $json = json_encode($filtro);

            $UsumarketingResumen = new UsumarketingResumen();
            $UsuarioMarketings = $UsumarketingResumen->getUsumarketingResumenGroupCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo ", "usumarketing_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usumarketing_resumen.tipo");
            $UsuarioMarketings = json_decode($UsuarioMarketings);


            foreach ($UsuarioMarketings->data as $key => $value) {

                switch ($value->{'usumarketing_resumen.tipo'}) {
                    case "LINKVISIT":
                        /* asigna el total de clics a la variable $sumClick si el caso es "LINKVISIT". */


                        $sumClick = $value->{'.total'};

                        break;

                    case "CLICKBANNER":
                        /* Captura el total de clics en un banner y lo asigna a la variable $sumClick. */


                        $sumClick = $value->{'.total'};

                        break;


                    case "REGISTRO":
                        /* extrae un total de registros de una estructura de datos. */


                        $sumRegistro = $value->{'.total'};

                        break;


                }

            }


//Obtenemos los clicks


            /* Se crean reglas de filtrado para una consulta de datos. */
            $rules = [];

            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilter1, "op" => "ge"));
            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilter2, "op" => "le"));
            array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Codifica un filtro JSON y obtiene un resumen de marketing agrupado. */
            $json = json_encode($filtro);


            $UsumarketingResumen = new UsumarketingResumen();
            $UsuarioMarketings = $UsumarketingResumen->getUsumarketingResumenGroupCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo ", "usumarketing_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usumarketing_resumen.tipo");
            $UsuarioMarketings = json_decode($UsuarioMarketings);


            foreach ($UsuarioMarketings->data as $key => $value) {


                /* clasifica y suma datos según el tipo de evento: clic o registro. */
                switch ($value->{'usumarketing_resumen.tipo'}) {
                    case "CLICKBANNER":

                        $sumClickTodos = $value->{'.total'};
                        $sumClickAyer = $value->{'.total'};

                        break;

                    case "REGISTRO":

                        $sumRegistroAyer = $value->{'.total'};
                        $sumRegistroTodos = $value->{'.total'};

                        break;


                }

            }

//Obtenemos los clicks


            /* Se crean reglas de filtrado para consultar datos de 'usumarketing_resumen'. */
            $rules = [];

            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilter1, "op" => "ge"));
            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilter2, "op" => "le"));
            array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte datos a JSON, consulta un resumen y los decodifica. */
            $json = json_encode($filtro);


            $UsumarketingResumen = new UsumarketingResumen();
            $UsuarioMarketings = $UsumarketingResumen->getUsumarketingResumenGroupCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo ", "usumarketing_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usumarketing_resumen.tipo");
            $UsuarioMarketings = json_decode($UsuarioMarketings);



            /* suma totales según el tipo de actividad de marketing. */
            foreach ($UsuarioMarketings->data as $key => $value) {

                switch ($value->{'usumarketing_resumen.tipo'}) {
                    case "CLICKBANNER":

                        $sumClickTodos = $value->{'.total'};

                        break;

                    case "REGISTRO":

                        $sumRegistroTodos = $value->{'.total'};

                        break;


                }

            }



            /* Código establece reglas para filtrar datos según fecha, limitando resultados a una fila. */
            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilter1, "op" => "ge"));

            /* Se crean reglas para filtrar datos y se convierten a formato JSON. */
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilter2, "op" => "le"));
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsucomisionResumen = new UsucomisionResumen();

            /* calcula y almacena comisiones totales agrupadas por usuario en formato JSON. */
            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total ", "usucomision_resumen.usuario_id", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usuario_id");
            $UsucomisionResumens = json_decode($UsucomisionResumens);

            $sumComisionAyer = $UsucomisionResumens->data[0]->{'.totalcomision'};
            $sumComision = $UsucomisionResumens->data[0]->{'.totalcomision'};
            $sumComisionTodos = $UsucomisionResumens->data[0]->{'.totalcomision'};


            /* asigna un estado verdadero y una cadena vacía a un array de respuesta. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "charts" => array(
// Grafico de estadisticas
                    "getMediaStats" => array(
                        array(
                            "activeMedia" => 0,
                            "sumClick" => $sumClick,
                            "sumSignUps" => $sumRegistro,
                            "sumUnique" => 0,
                            "sumView" => 0,

                        )

                    ),
                    "getTotalProductStatistics" => $TotalProducsStatistics,
                    "productsReportByPlayersTotals" => array(
                        "records" => $productsReportByPlayersTotals

                    ),
                    "MediaStat" => array(
                        "records" => $MediaStat

                    ),
                    "getUsersStatistics" => array(
                        array(
                            "count" => 0,
                            "name" => "signUps",
                            "title" => "Sign Ups",

                        ),
                        array(
                            "count" => 0,
                            "name" => "depositing",
                            "title" => "Depositing",

                        ), array(
                            "count" => 0,
                            "name" => "firstDepositing",
                            "title" => "firstDepositing",

                        ), array(
                            "count" => 0,
                            "name" => "activeUsers",
                            "title" => "activeUsers",

                        ), array(
                            "count" => 0,
                            "name" => "firstActiveUsers",
                            "title" => "firstActiveUsers",

                        ),

                    )
                ),
                "widgets" => array(
                    "activeBannersCount" => array(
                        "activeBannersCount" => 0,
                        "yesterdayBannersCount" => $sumClickAyer,

                        "allBannersCount" => $sumClickTodos

                    ),

                    "commissionsForYesterday" => array(
                        "allCommission" => $sumComisionAyer,
                        "yesterdayCommission" => $sumComisionTodos

                    ),
                    "getTopUsers" => $TopUsers,
                    "getNewRegisteredPlayersCount" => array(
                        "count" => $sumRegistroAyer,
                        "totalPlayers" => $sumRegistroTodos

                    ),
                    "productsReportByPlayersTotals" => array(
                        "records" => $productsReportPlayersTotal,
                        "titles" => "",
                        "total" => "",
                        "totalRecordsCount" => "1",
                    ),
                    "getAcceptedWithdrawCount" => array(
                        "count" => "0",
                        "total" => "$ 0"

                    ),
                    "getDeniedWithdrawCount" => array(
                        "count" => "0",
                        "total" => "$ 0"

                    ),
                    "getPendingWithdrawCount" => array(
                        "count" => "0",
                        "total" => "$ 0"

                    ),

                )
            );


            /* Se inicializa un array vacío en la clave "notification" de la variable $response. */
            $response["notification"] = array();

            break;


        case "getKpi2":


            /* asigna un valor vacío a $agentId si es igual a cero. */
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }

            $arrayf = array();


            /* Define reglas para validar un campo si $agentId no está vacío. */
            $rules = [];

            if ($agentId != "") {
                array_push($rules, array("field" => "data.afiliador_id", "data" => "$agentId", "op" => "eq"));

            }


            /* Crea un filtro JSON y obtiene un resumen de usuarios afiliados personalizados. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $Usuario = new Usuario();
            $usuarios = $Usuario->getUsuariosResumenAfiliadosCustom(" data.tipo, SUM(ayer) ayer, SUM(mes_actual) mes_actual, SUM(mes_anterior) mes_anterior, SUM(acumulado_anio) acumulado_anio ", "usuario.usuario_id", "asc", 0, 100000, $json, true, "tipo");


            /* decodifica un JSON y formatea datos financieros en un array. */
            $usuarios = json_decode($usuarios);

            foreach ($usuarios->data as $datum) {
                $array = array(
                    "Text" => $datum->{'data.tipo'},
                    "Yesterday" => number_format($datum->{'.ayer'}, 2),
                    "CurrentMonth" => number_format($datum->{'.mes_actual'}, 2),
                    "LastMonth" => number_format($datum->{'.mes_anterior'}, 2),
                    "AccumulatedCurrentYear" => number_format($datum->{'.acumulado_anio'}, 2),
                );

                array_push($arrayf, $array);

            }



            /* define una respuesta en formato JSON con estado y datos específicos. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $arrayf,
                "titles" => "",
                "total" => 1,
                "totalRecordsCount" => 10,

            );



            /* Inicializa un arreglo vacío para las notificaciones en la respuesta. */
            $response["notification"] = array();

            break;

        /**
         * getLandingPageNames
         *
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
        case "getLandingPageNames":
            /* genera una respuesta con nombres de páginas y sus identificadores. */


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                array("name" => "Home", "id" => "0"),
                array("name" => "Deportes", "id" => "1"),
                array("name" => "Registro", "id" => "2")


            );

            $response["notification"] = array();

            break;

        /**
         * getMarketingSourcesNames
         *
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
        case "getMarketingSourcesNames":
            /* genera una respuesta con nombres y IDs de fuentes de marketing. */


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                array("name" => "Test", "marketingSourceId" => "1"),
                array("name" => "sports", "marketingSourceId" => "2")


            );

            $response["notification"] = array();

            break;

        /**
         * createLink
         *
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
        case "createLink":

            /* verifica un perfil de sesión y establece valores de respuesta si no coincide. */
            if ($_SESSION["win_perfil"] != "AFILIADOR") {
                $response["status"] = false;
                $response["html"] = "";
                $response["result"] = "1234";
                $response["notification"] = array();

            }else {




                /* Se asignan valores de sesión y parámetros para inicializar variables relacionadas. */
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

                $linkData = $params->linkData;
                $linkName = $linkData->linkName;
                $landingPageId = (!is_numeric($linkData->landingPageId)) ? 0 : $linkData->landingPageId;
                $marketingSourceId = $linkData->marketingSourceId;

                /* accede a propiedades de un objeto $linkData para configuraciones de enlace. */
                $siteId = $linkData->siteId;
                $withBtag = $linkData->withBtag;
                $utmSource = $linkData->utmSource;
                $utmMedium = $linkData->utmMedium;
                $utmCampaing = $linkData->utmCampaing;
                $urlCustom = $linkData->urlCustom;


                /* Se crea un objeto UsuarioLink y se configuran sus propiedades. */
                $UsuarioLink = new UsuarioLink();

                $UsuarioLink->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                $UsuarioLink->setUsucreaId(0);
                $UsuarioLink->setUsumodifId(0);
                $UsuarioLink->setNombre($linkName);


                /* establece parámetros de enlace y estado para un objeto UsuarioLink. */
                $UsuarioLink->setUtmSource($utmSource);
                $UsuarioLink->setUtmMedium($utmMedium);
                $UsuarioLink->setUtmCampaing($utmCampaing);
//$UsuarioLink->setUrlPersonalizada($landingPageId == '' ? str_replace(' ', '', $urlCustom) : '');



                $UsuarioLink->setEstado('A');

                /* Inserta un enlace de usuario en la base de datos y confirma la transacción. */
                $UsuarioLink->setLink($landingPageId != '' ? $landingPageId : '');

                $UsuarioLinkMySqlDAO = new UsuarioLinkMySqlDAO();
                $UsuarioLinkMySqlDAO->insert($UsuarioLink);
                $UsuarioLinkMySqlDAO->getTransaction()->commit();


                $response["status"] = true;

                /* Se establece una respuesta con valores HTML, resultado y un array de notificaciones vacío. */
                $response["html"] = "";
                $response["result"] = "1234";
                $response["notification"] = array();
            }
//{"status":true,"html":"","result":"18946","notification":[]}

            break;

        /**
         * addMarketingSource
         *
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
        case "addMarketingSource":

            /* Se crea un objeto UsuarioMandante y se inicializan variables para usuario y sitio. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);


            $name = $params->name;
            $site = $linkData->site;


            $UsuarioLink = new UsuarioLink();


            /* Código en PHP que establece propiedades de un objeto UsuarioLink. */
            $UsuarioLink->setUsuarioId($UsuarioMandante->getUsuarioMandante());
            $UsuarioLink->setUsucreaId(0);
            $UsuarioLink->setUsumodifId(0);
            $UsuarioLink->setNombre($linkName);
            $UsuarioLink->setEstado('A');
            $UsuarioLink->setLink('');


            /* Se inserta un usuario en la base de datos y se confirma la transacción. */
            $UsuarioLinkMySqlDAO = new UsuarioLinkMySqlDAO();
            $UsuarioLinkMySqlDAO->insert($UsuarioLink);
            $UsuarioLinkMySqlDAO->getTransaction()->commit();


            $response["status"] = true;

            /* Se crea un array de respuesta con HTML vacío, un resultado y notificaciones. */
            $response["html"] = "";
            $response["result"] = "1234";
            $response["notification"] = array();

//{"status":true,"html":"","result":"18946","notification":[]}

            break;

        case "DeleteBanner":

            /* actualiza el estado de un banner en la base de datos. */
            $bannerId = $params->bannerId;
            $response = [];
            try {
                $Banner = new Banner($bannerId);
                $Banner->setEstado('E');

                $BannerMySqlDAO = new BannerMySqlDAO();
                $BannerMySqlDAO->update($Banner);
                $BannerMySqlDAO->getTransaction()->commit();


                $response['status'] = true;
                $response['html'] = '';
                $response['result'] = [];
                $response['notification'] = [];
            } catch (Exception $ex) {
                /* Manejo de excepciones que inicializa un arreglo de respuesta en caso de error. */


                $response['status'] = false;
                $response['html'] = '';
                $response['result'] = [];
                $response['notification'] = [];
            }
            break;
        case "AddBanner22":
            /* añade un banner y copia una imagen a Google Cloud Storage. */

            try{
                print_r('entr2p');
                print_r($_SERVER);
                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp /home/home2/backend/images/affiliates/1681842097.jpeg gs://virtualcdnrealbucket/affiliates/');

            }catch (Exception $e){
                print_r($e);
            }


            break;
        case "AddBanner":


            /* Valida el tipo de archivo asegurando que contenga un formato válido antes de procesarlo. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $fileType = $params->mediaType;

            if (strpos($fileType, '/') === false) {
                throw new Exception("El tipo de archivo no tiene un formato válido", 161561458);
            }


            /* Convierte y asigna fechas y parámetros de un objeto a variables en PHP. */
            $expireDate = date("Y-m-d H:i:s", strtotime(str_replace("T", " ", $params->expirationDate)));
            $name = $params->name;
            $width = $params->width;
            $height = $params->height;
            $fileSize = $params->fileSize;
            $uploadDate = $params->uploadDate;

            /* define variables y una lista de extensiones de imagen permitidas. */
            $language = $params->lenguage;
            $visible = $params->visible;
            $image = $params->image;
            $region = $params->region;
            $productId = 0;


            $AllowedExtension = ["png","jpg","jpeg","gif"];


            /* divide una cadena por '/' y obtiene la última parte como extensión. */
            $parts = explode('/', $fileType);
            $extension = end($parts);

            if(in_array($extension,$AllowedExtension)) {


                /* crea un nombre de archivo basado en el tipo y la hora actual. */
                $filetype = $fileType;

                $filename = time() . '.' . explode("/", $filetype)[1];
                $dirsave = '/home/home2/backend/images/affiliates/' . $filename;


                $Banner = new Banner();


                /* Código que configura propiedades de un objeto Banner con atributos específicos. */
                $Banner->setUsuarioId($UsuarioMandante->getUsumandanteId());
                $Banner->setEstado('A');
                $Banner->setHeight($height);
                $Banner->setWidth($width);
                $Banner->setIdioma($language);
                $Banner->setNombre($name);

                /* Código para configurar propiedades de un objeto Banner en un sistema de gestión. */
                $Banner->setProductointernoId(0);
                $Banner->setTipo('IMAGE');
                $Banner->setFechaExpiracion($expireDate);
                $Banner->setPublico('S');
                $Banner->setUsucreaId(0);
                $Banner->setUsumodifId(0);

                /* Código que define propiedades de un objeto Banner y lo inicializa con un DAO. */
                $Banner->setBsize($fileSize);
                $Banner->setFilename($filename);
                $Banner->setPaisId($region);
                $Banner->setMandante($UsuarioMandante->mandante);


                $BannerMySqlDAO = new BannerMySqlDAO();

                /* Inserta un banner en la base de datos y realiza la confirmación de transacción. */
                $BannerMySqlDAO->insert($Banner);
                $BannerMySqlDAO->getTransaction()->commit();

                $data = $image;

                list($type, $data) = explode(';', $data);

                /* decodifica datos y los guarda en Google Cloud Storage usando gsutil. */
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);

                file_put_contents($dirsave, $data);
//$out =  shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://virtualcdnrealbucket/affiliates/');

                shell_exec('export BOTO_CONFIG=/home/afiliados/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://virtualcdnrealbucket/affiliates/');
            }else{
                /* lanza una excepción con el mensaje "nueva" en caso de un error. */

                throw new exception ("nueva");
            }


            /* Código que inicializa una respuesta con varios elementos para una interacción en línea. */
            $response["status"] = true;
            $response["html"] = "";
            $response["dirsave"] = $dirsave;
            $response["result"] = array();
            $response["notification"] = array();

            break;

        /**
         * uploadBanner
         *
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
        case "uploadBanner":


            /* captura datos de un formulario POST y los asigna a variables. */
            $fileType = $_POST["fileType"];
            $expireDate = $_POST["expireDate"];
            $name = $_POST["name"];
            $width = $_POST["width"];
            $height = $_POST["height"];
            $fileSize = $_POST["fileSize"];

            /* recoge datos de un formulario para procesar archivos subidos. */
            $mediaType = $_POST["mediaType"];
            $uploadDate = $_POST["uploadDate"];
            $language = $_POST["language"];
            $visible = $_POST["visible"];
            $productId = $_POST["productId"];


            $filename = $_FILES['file']['name'];

            /* procesa un archivo subido y lo guarda con un nombre basado en la fecha. */
            $filetype = $_FILES['file']['type'];

            $filename = time() . '.' . $_POST["fileType"];
            $dirsave = '/home/backend/images/affiliates/' . $filename;

            $Banner = new Banner();


            /* Se establecen propiedades para un objeto Banner, definiendo su usuario, estado, dimensiones e idioma. */
            $Banner->setUsuarioId(1);
            $Banner->setEstado('A');
            $Banner->setHeight($height);
            $Banner->setWidth($width);
            $Banner->setIdioma($language);
            $Banner->setNombre($name);

            /* Configura un banner con atributos específicos, como tipo, fechas y usuarios. */
            $Banner->setProductointernoId(1);
            $Banner->setTipo('IMAGE');
            $Banner->setFechaExpiracion('2018-06-06 00:00:00');
            $Banner->setPublico('S');
            $Banner->setUsucreaId(0);
            $Banner->setUsumodifId(0);

            /* Código para establecer atributos de un banner y guardarlo en la base de datos. */
            $Banner->setBsize('19');
            $Banner->setFilename($filename);

            $BannerMySqlDAO = new BannerMySqlDAO();
            $BannerMySqlDAO->insert($Banner);
            $BannerMySqlDAO->getTransaction()->commit();


            /* verifica el tipo de archivo antes de moverlo a una ubicación específica. */
            if ($filetype == 'image/jpeg' or $filetype == 'image/png' or $filetype == 'image/gif') {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $dirsave)) {

                } else {

                }

            }

            /* Código inicializa un array de respuesta con estado, HTML vacío y arrays para resultados y notificaciones. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array();
            $response["notification"] = array();

            break;

        /**
         * getAffiliatePaymentSystems
         *
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
        case "getAffiliatePaymentSystems":


            /* inicializa una respuesta con estado verdadero y contenido HTML vacío. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                array(
                    "systemId" => "369",
                    "className" => "AstroPay",
                    "fields" => '{"amount":{"type":"text","title":"Amount:"},"email":{"type":"text","title":"Email:"},"x_name":{"type":"text","title":"Name Surname:"},"x_document":{"type":"text","title":"Document number:"}}',
                    "name" => "Astropay",
                    "status" => 1,
                ),
                array(
                    "systemId" => "370",
                    "className" => "skrill",
                    "fields" => '{"email":{"type":"text","title":"Email:"},"amount":{"type":"text","title":"Amount:"}}',
                    "name" => "skrill",
                    "status" => 1,
                ),
                array(
                    "systemId" => "371",
                    "className" => "Neteller",
                    "fields" => '{"amount":{"type":"text","title":"Amount:"},"email":{"type":"text","title":"Email:"}}',
                    "name" => "Neteller",
                    "status" => 1,
                )
                /*,

                array(
                "systemId" => "372",
                "className" => "EcoPayz",
                "fields" => '{"amount":{"type":"text","title":"Amount:"},"ClientAccountNumber":{"type":"text","title":"Wallet id:"}}',
                "name" => "EcoPayz",
                "status" => 1,
                ),
                array(
                "systemId" => "373",
                "className" => "UpayCard",
                "fields" => '{"amount":{"type":"text","title":"Amount"}}',
                "name" => "UpayCard",
                "status" => 1,
                ),
                array(
                "systemId" => "897",
                "className" => "Jeton",
                "fields" => '{"amount":{"title":"amount"}}',
                "name" => "Jeton",
                "status" => 1,
                )
                */

            );


            /* Crea un array vacío para almacenar notificaciones en la respuesta. */
            $response["notification"] = array();
            break;

        /**
         * getAffiliateDefaultWithdraw
         *
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
        case "getAffiliateDefaultWithdraw":
            /* Código que maneja una solicitud para obtener datos de retiro de afiliados. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array();

            $response["notification"] = array();

            break;

        /**
         * makeFavorite
         *
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
        case "makeFavorite":

            /* inicializa un objeto y asigna un ID de banner a una respuesta. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $bannerId = $params->bannerId;
            $response["notification"] = array();

            if ($bannerId != "") {

                /* alterna el estado de favorito de un banner de usuario en una base de datos. */
                try {
                    $UsuarioBanner = new UsuarioBanner("", $UsuarioMandante->getUsuarioMandante(), $bannerId);

                    if ($UsuarioBanner->getFavorito() == "S") {
                        $UsuarioBanner->setFavorito('N');

                    } else {
                        $UsuarioBanner->setFavorito('S');

                    }

                    $UsuarioBannerMySqlDAO = new UsuarioBannerMySqlDAO();

                    $UsuarioBannerMySqlDAO->update($UsuarioBanner);
                    $UsuarioBannerMySqlDAO->getTransaction()->commit();

                } catch (Exception $e) {

                    /* Inserta un nuevo registro de favorito en la base de datos si el código es "33". */
                    if ($e->getCode() == "33") {
                        $UsuarioBanner = new UsuarioBanner();
                        $UsuarioBanner->setFavorito('S');
                        $UsuarioBanner->setBannerId($bannerId);
                        $UsuarioBanner->setEstado('A');
                        $UsuarioBanner->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                        $UsuarioBanner->setUsumodifId(0);
                        $UsuarioBanner->setUsucreaId(0);
                        $UsuarioBanner->setLandingId(0);
                        $UsuarioBanner->setMandante(0);

                        $UsuarioBannerMySqlDAO = new UsuarioBannerMySqlDAO();

                        $UsuarioBannerMySqlDAO->insert($UsuarioBanner);
                        $UsuarioBannerMySqlDAO->getTransaction()->commit();

                    } else {
                        /* El bloque lanza una excepción si no se cumplen ciertas condiciones previas. */

                        throw $e;
                    }
                }

                /* inicializa un arreglo de respuesta con estado, HTML, resultados y notificaciones. */
                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = array();
                $response["notification"] = array();

            }

            break;

        /**
         * makeFavorite
         *
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
        case "UpdateBanner":

            /* Código que inicializa un objeto UsuarioMandante y prepara una respuesta de notificación. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $bannerId = $params->id;
            $response["notification"] = array();

            if ($bannerId != "") {

                /* asigna un estado 'A' o 'I' basado en la variable $state. */
                $state = $params->state;
                $name = $params->name;
                $estado = 'A';

                if ($state == false) {
                    $estado = 'I';
                }

                /* Actualiza un banner en la base de datos y confirmando la transacción. */
                try {
                    $Banner = new Banner($bannerId);
                    $Banner->setEstado($estado);
                    $Banner->setNombre($name);


                    $BannerMySqlDAO = new BannerMySqlDAO();
                    $BannerMySqlDAO->update($Banner);
                    $BannerMySqlDAO->getTransaction()->commit();

                    $response["status"] = true;
                    $response["html"] = "";
                    $response["result"] = array();
                    $response["notification"] = array();

                } catch (Exception $e) {
                    /* Maneja excepciones, establece respuesta con estado falso y datos vacíos. */

                    $response["status"] = false;
                    $response["html"] = "";
                    $response["result"] = array();
                    $response["notification"] = array();

                }

            }

            break;

        /**
         * activateBanner
         *
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
        case "activateBanner":


            /* Se inicializa un objeto UsuarioMandante y se preparan variables para respuesta. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $bannerId = $params->bannerId;
            $landingPageId = $params->landingPageId;
            $withBtag = $params->withBtag;

            $response["notification"] = array();

            if ($bannerId != "") {

                /* Código que actualiza un objeto UsuarioBanner en la base de datos y confirma la transacción. */
                try {
                    $UsuarioBanner = new UsuarioBanner("", $UsuarioMandante->getUsuarioMandante(), $bannerId);
                    $UsuarioBanner->setUsumodifId(0);
                    $UsuarioBanner->setUsucreaId(0);
                    $UsuarioBanner->setLandingId($landingPageId);
                    $UsuarioBanner->setMandante(0);


                    $UsuarioBannerMySqlDAO = new UsuarioBannerMySqlDAO();

                    $UsuarioBannerMySqlDAO->update($UsuarioBanner);
                    $UsuarioBannerMySqlDAO->getTransaction()->commit();

                } catch (Exception $e) {

                    /* inserta un nuevo registro de banner para un usuario específico. */
                    if ($e->getCode() == "33") {
                        $UsuarioBanner = new UsuarioBanner();
                        $UsuarioBanner->setFavorito('N');
                        $UsuarioBanner->setBannerId($bannerId);
                        $UsuarioBanner->setEstado('A');
                        $UsuarioBanner->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                        $UsuarioBanner->setUsumodifId(0);
                        $UsuarioBanner->setUsucreaId(0);
                        $UsuarioBanner->setLandingId($landingPageId);
                        $UsuarioBanner->setMandante(0);

                        $UsuarioBannerMySqlDAO = new UsuarioBannerMySqlDAO();

                        $UsuarioBannerMySqlDAO->insert($UsuarioBanner);
                        $UsuarioBannerMySqlDAO->getTransaction()->commit();

                    } else {
                        /* lanza una excepción si ocurre un error en la ejecución. */

                        throw $e;
                    }
                }



                /* inicializa un banner y configura su URL según el usuario. */
                $Banner = new Banner($UsuarioBanner->getBannerId());

                $Mandante = new Mandante($Banner->mandante);



                /* Asigna una URL según el mandante y país del usuario en un código PHP. */
                try {
                    $PaisMandante = new PaisMandante('', strtolower($UsuarioMandante->mandante), $UsuarioMandante->paisId);

                    /* Validación para encontrar la URL en la columna base_url de base de datos*/
                    if (empty($PaisMandante->baseUrl)) {
                        throw new Exception("No se encontró base_url para Mandante ID {$UsuarioMandante->mandante} y País ID {$UsuarioMandante->paisId}.", 300046);
                    }
                    $Mandante->baseUrl = $PaisMandante->baseUrl;
                } catch (Exception $e) {
                    /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
                }


                /* Genera un enlace de seguimiento basado en el ID de destino del usuario. */
                $trackingLink = $Mandante->baseUrl . "/";

                switch ($UsuarioBanner->landingId) {
                    case 0:
                        $trackingLink = $trackingLink;
                        break;
                    case 1:
                        $trackingLink = $trackingLink . "apuestas";
                        break;
                    case 2:
                        $trackingLink = $trackingLink . "registro";
                        break;
                }


                $arrayBanner = array(
                    "bannerPath" => $urlApiAfiliados . "" . $Banner->getFilename(),
                    "height" => $Banner->getHeight(),
                    "origin" => "",
                    "trackingLink" => $trackingLink . "?btag=" . encrypt($UsuarioBanner->getUsuarioId() . "_" . $UsuarioBanner->getUsubannerId(), $ENCRYPTION_KEY),
                    "typeName" => "Image",
                    "width" => $Banner->getWidth(),

                );


                $script = "<script>setTimeout(function(){(function(){var ba=(function(){var
        accountId=null,parentContainer,useSetContainer=true,q=[],lId='',mId='',u=\"\",i=0;if(typeof mediaToTrack==='undefined'){mediaToTrack=[];}
        var prefix='_ba';var isMsIe=function(){var ua=navigator.userAgent;return(ua!=null&&ua.indexOf(\"MSIE\")!=-1)?true:false;};var _setAccount=function(param){accountId=param;};var _setUrl=function(param){u=param;};var _mId=function(param){mId=param;put();};var _lId=function(param){lId=param;};var _setContainer=function(containerData){useSetContainer=false;parentContainer=document.querySelector('[data-ti=\"'+containerData+'\"]')||document.getElementById(containerData)||document.querySelector('[ban_id=\"'+containerData+'\"]');if(!parentContainer||parentContainer==null){throw{errorId:'unableToFindMediaContainer',msg:'Unable to find media container'}}};var _mData=function(params){mId=params[0]||0;lId=params[1]||0;put();};var put=function(){try{if(useSetContainer===true){if(mId&&mId!==''){_setContainer(accountId+'_'+mId);}}
            createMediaContainer();useSetContainer=true;}catch(e){throw e.msg;}};var keepInStorage=function(item,value,customPrefix){var stored=false;prefix=customPrefix||prefix;item=item||'_impr';if(typeof window.localStorage!==undefined){var impressionsStr=window.localStorage.getItem(prefix+item),impressions=JSON.parse(impressionsStr)||[];if(impressions.length>0){impressions.forEach(function(){if(impressions.indexOf(value)==-1){impressions.push(value);stored=true;}});}else{impressions.push(value);stored=true;}
        window.localStorage.setItem(prefix+item,JSON.stringify(impressions));}else{throw\"LocalStorage not supporting\";}
        return stored;};var trackClick=function(objectToTrack){if(!mId||mId=='')return;objectToTrack.addEventListener('click',function(e){e.preventDefault();var stored=keepInStorage('_clk',objectToTrack.getAttribute('data-ti'));var url=u;if(stored===true){ajax({url:url+'',data:{mId:mId,type:'click'},dataType:'json',headers:{\"Content-Type\":\"application/json\"}});}
        window.open(objectToTrack.getAttribute('href'),'_blank');},false);};var trackImpressions=function(objectToTrack){var mediaId=objectToTrack.getAttribute('data-mid')||null;if(!mediaId)return;var url=u;ajax({url:url+'',data:{mId:mediaId,type:'impr'},dataType:'json',headers:{\"Content-Type\":\"application/json\"}});};var viewPort=function(){return{isVisible:function(object){var scroll=(window.pageYOffset!==undefined)?window.pageYOffset:(document.documentElement||document.body.parentNode||document.body).scrollTop;return(object.offsetTop>=scroll&&object.offsetTop<=scroll+document.body.offsetHeight);}};};var trackAllMediaImpressions=function(){var vp=new viewPort();if(mediaToTrack.length>0){mediaToTrack.forEach(functio n(element){if(vp.isVisible(element)===true){var stored=keepInStorage('_imprs',element.getAttribute('data-ti'));if(stored===true){trackImpressions(element);}}});}};var getMedia=function(){var param={url:u+'api/banners/getMediaById',data:{mediaParams:{mId:mId,lId:lId}},dataType:'json',headers:{\"Content-Type\":\"application/json\"}};return ajax(param);};var createMediaContainer=function(){var mediaObj;mediaObj=document.createElement('img');mediaObj.src=\"" . $arrayBanner["bannerPath"] . "\";mediaObj.width=\"" . $arrayBanner["width"] . "\";mediaObj.height=\"" . $arrayBanner["height"] . "\";mediaObj=document.createElement('img');mediaObj.src=\"" . $arrayBanner["bannerPath"] . "\";var mediaTrackLink=document.createElement('a'),mediaTrackId=accountId+'_'+mId+'_'+lId;parentContainer.setAttribute('class','affMediaContainer_'+mediaTrackId);mediaTrackLink.setAttribute('data-ti',mediaTrackId);mediaTrackLink.setAttribute('data-mid',mId);mediaTrackLink.setAttribute('data-origin',u);mediaTrackLink.href=\"" . $arrayBanner["trackingLink"] . "\";mediaTrackLink.target='_blank';mediaTrackLink.appendChild(mediaObj);parentContainer.appendChild(mediaTrackLink);};var ajax=function(params){var client=new XMLHttpRequest(),method=params.method||'POST',url=params.url||'',promise=new Promise(function(resolve,reject){client.open(method,url);if(params.headers){for(var header in params.headers){client.setRequestHeader(header,params.headers[header]);}}
            client.withCredentials=true;client.send(JSON.stringify(params.data));client.onload=function(){if(this.status==200){if(params.dataType==='json'&&typeof this.response==='string'){resolve(JSON.parse(this.response));}else{resolve(this.response);}}else{if(params.dataType==='json'&&typeof this.response==='string'){resolve(JSON.parse(this.statusText));}else{resolve(this.statusText);}}};client.onerror=function(){if(params.dataType==='json'&&typeof this.response==='string'){resolve(JSON.parse(this.statusText));}else{resolve(this.statusText);}}});return promise;};var getBh5Script=function(scriptUrl){var bh5=document.createElement('script'),s=document.getElementsByTagName('script')[0];bh5.async=true;bh5.src=(scriptUrl||u)+'plugins/bh5/js/main.js';if(typeof bh5.src==null){throw\"Can't get bh5 script\";}
                !document.querySelector('script[src=\"'+bh5.src+'\"]')&&s.parentNode.insertBefore(bh5,s);};var init=function(){var q=[],fn,i;u=null;if(q.length>0){for(i in q){fn=q[i][0];delete q[i][0];if(typeof this[fn]==='function'&&this.hasOwnProperty(fn)){this[fn].apply(this,[q[i][1]]);var dir=document.querySelector('script[src$=\"banner.js\"]').getAttribute('src');var name=dir.split('/').pop();dir=dir.replace('/'+name,\"\");getBh5Script(dir+\"/../\");}}}
                window.onload=function(){trackAllMediaImpressions();};window.onscroll=function(){trackAllMediaImpressions();}};return{q:q,init:init,_mId:_mId,_lId:_lId,_mData:_mData,_setAccount:_setAccount,_setContainer:_setContainer,_setUrl:_setUrl,put:put,u:u};})();ba.init();window.ba=ba;})(window,document);ba._setAccount(" . $UsuarioMandante->getUsuarioMandante() . ");ba._mId(" . $UsuarioBanner->usubannerId . ");}, 3000);</script><div data-ti=\"" . $UsuarioMandante->getUsuarioMandante() . "_" . $UsuarioBanner->usubannerId . "\"></div>";


                /* asigna un script a la respuesta en formato JSON. */
                $response["status"] = true;
                $response["html"] = "";
//$response["result"] = array("script" => createScript($URL_AFFILIATES_API, $UsuarioMandante->getUsuarioMandante(), $UsuarioBanner->usubannerId));
                $response["result"] = $script;
                $response["result"] = array("script" => $script);
                $response["script"] = $script;

                /* Asigna una URL y una notificación vacía a un arreglo de respuesta. */
                $response["url"] = $script;
                $response["notification"] = array();

            }


            break;

        /**
         * getMediaById
         *
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
        case "getMediaById":


            /* Registra una advertencia y extrae identificadores de medios y listas de parámetros. */
            syslog(LOG_WARNING, "GETMEDIAID :" . json_encode($params));
            exit();

            $mediaParams = $params->mediaParams;
            $mId = $mediaParams->mId;
            $lId = $mediaParams->lId;

            if ($mId != "") {

                /* Crea instancias de UsuarioBanner y Banner, y prepara respuesta con estado exitoso. */
                $UsuarioBanner = new UsuarioBanner($mId);
                $Banner = new Banner($UsuarioBanner->getBannerId());

                $Mandante = new Mandante($Banner->mandante);



                /* Asigna una URL según el mandante y país del usuario en un código PHP. */
                try {
                    $PaisMandante = new PaisMandante('', strtolower($Usuario->mandante), $Usuario->paisId);

                    /* Validación para encontrar la URL en la columna base_url de base de datos*/
                    if (empty($PaisMandante->baseUrl)) {
                        throw new Exception("No se encontró base_url para Mandante ID {$Usuario->mandante} y País ID {$Usuario->paisId}.", 300046);
                    }
                    $Mandante->baseUrl = $PaisMandante->baseUrl;
                } catch (Exception $e) {
                    /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
                }

                $response["status"] = true;

                /* Construye un array con detalles sobre un banner y un enlace de seguimiento. */
                $response["html"] = "";
                $response["result"] = array(
                    "bannerPath" => $urlApiAfiliados . "" . $Banner->getFilename(),
                    "height" => $Banner->getHeight(),
                    "origin" => "",
                    "trackingLink" => $Mandante->baseUrl . "/#/?btag=" . encrypt($UsuarioBanner->getUsuarioId() . "_" . $UsuarioBanner->getUsubannerId(), $ENCRYPTION_KEY),
                    "typeName" => "Image",
                    "width" => $Banner->getWidth(),

                );


                /* Crea un arreglo vacío para almacenar notificaciones en la respuesta. */
                $response["notification"] = array();


            } else {
                /* configura una respuesta en formato JSON con atributos predeterminados. */

                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = array(
                    "bannerPath" => "",
                    "origin" => "",
                    "trackingLink" => null

                );

                $response["notification"] = array();

            }


            break;


        case "descryptOnline":
            /* decodifica y desencripta datos recibidos en una solicitud. */


            print_r(urldecode($_REQUEST["data"]));

            print_r(decrypt2(urldecode($_REQUEST["data"]), $ENCRYPTION_KEY));

            break;

        case "descryptOnline2":
            /* Desencripta datos usando AES-128-CTR con una clave y un vector de inicialización. */

            $data = str_replace("vSfTp", "/", $_REQUEST["data"]);

            $passEncryt = 'li1296-151.members.linode.com|3232279913';

            $iv_strlen = (openssl_cipher_iv_length('AES-128-CTR'));
            print_r("iv_strlen");
            print_r($iv_strlen);
            if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
                list(, $iv, $crypted_string) = $regs;
                $decrypted_string = openssl_decrypt($crypted_string, 'AES-128-CTR', $passEncryt, 0, hex2bin($iv));
                print $decrypted_string;
            } else {
                print 2;
            }

            break;


        case "encryptOnline":
            /* define un caso "encryptOnline" en una estructura switch, pero no realiza acciones. */



            break;


        case "encryptOnline2":
            /* cifra datos usando una función dentro de un entorno de configuración. */


            $ConfigurationEnvironment = new ConfigurationEnvironment();
            print_r($ConfigurationEnvironment->encrypt_decrypt2('encrypt', $_REQUEST["data"]));
            break;


        /**
         * setBannerStatAll
         *
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
        case "setBannerStatAll":


            /* obtiene la dirección IP del cliente y asigna parámetros de entrada. */
            $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];


            exit();
            $mId = $params->mId;
            $type = $params->type;

            if ($mId != "") {

                if ($type == "click") {

                    /* Código para crear objetos de banner y marketing utilizando identificadores de usuario. */
                    $UsuarioBanner = new UsuarioBanner($mId);
                    $Banner = new Banner($UsuarioBanner->getBannerId());

                    $UsuarioMarketing = new UsuarioMarketing();
                    $UsuarioMarketing->setUsuarioId($UsuarioBanner->getUsuarioId());
                    $UsuarioMarketing->setUsucreaId($UsuarioBanner->getUsuarioId());

                    /* Configuración de usuario marketing con datos de referencia y acciones de clic en banner. */
                    $UsuarioMarketing->setUsumodifId($UsuarioBanner->getUsuarioId());
                    $UsuarioMarketing->setValor(1);
                    $UsuarioMarketing->setTipo('CLICKBANNER');
                    $UsuarioMarketing->setExternoId($mId);
                    $UsuarioMarketing->setIp($dir_ip);
                    $UsuarioMarketing->setUsuariorefId(0);

                    /* inserta un objeto UsuarioMarketing en una base de datos MySQL. */
                    $UsuarioMarketing->setLinkId(0);
                    $UsuarioMarketing->setBannerId($mId);

                    $UsuarioMarketingMySqlDAO = new UsuarioMarketingMySqlDAO();
                    $UsuarioMarketingMySqlDAO->insert($UsuarioMarketing);
                    $UsuarioMarketingMySqlDAO->getTransaction()->commit();


                }

                if ($type == "linkvisit") {

                    /* Código para crear y configurar objetos UsuarioLink y UsuarioMarketing con identificadores de usuario. */
                    $UsuarioLink = new UsuarioLink($mId);


                    $UsuarioMarketing = new UsuarioMarketing();
                    $UsuarioMarketing->setUsuarioId($UsuarioLink->getUsuarioId());
                    $UsuarioMarketing->setUsucreaId($UsuarioLink->getUsuarioId());

                    /* Asigna valores a un objeto de marketing según datos de un usuario y contexto. */
                    $UsuarioMarketing->setUsumodifId($UsuarioLink->getUsuarioId());
                    $UsuarioMarketing->setValor(1);
                    $UsuarioMarketing->setTipo('LINKVISIT');
                    $UsuarioMarketing->setExternoId($mId);
                    $UsuarioMarketing->setIp($dir_ip);
                    $UsuarioMarketing->setUsuariorefId(0);

                    /* inserta un registro de usuario en la base de datos MySQL. */
                    $UsuarioMarketing->setLinkId($mId);
                    $UsuarioMarketing->setBannerId(0);

                    $UsuarioMarketingMySqlDAO = new UsuarioMarketingMySqlDAO();
                    $UsuarioMarketingMySqlDAO->insert($UsuarioMarketing);
                    $UsuarioMarketingMySqlDAO->getTransaction()->commit();


                }



                /* inicializa un array de respuesta con estado y valores vacíos. */
                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = "";
                $response["notification"] = array();

            } else {
                /* establece una respuesta con estado verdadero y propiedades vacías. */

                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = "";
                $response["notification"] = array();

            }

            break;

        /**
         * stst
         *
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
        case "decryptOnline":
            /* decodifica y desencripta datos recibidos a través de una solicitud HTTP. */


            $bt = $_REQUEST['data'];
            $bt = urldecode($bt);
            $bt = str_replace(" ", "+", $bt);

            print_r($bt);
            $data = decrypt($bt, "");
            print_r($data);

            break;

        /**
         * stst
         *
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
        case "stst":

            /* obtiene, modifica y descifra un encabezado específico llamado 'Bt'. */
            $headers = getallheaders();
            $bt = $headers['Bt'];
            $bt = str_replace(" ", "+", $bt);


            $data = decrypt($bt, "");


            /* obtiene la IP y descompone datos de entrada en variables. */
            $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];


            $mId = explode("__", $data)[1];
            $type = $params->type;

            if ($mId != "" && is_numeric($mId)) {


                /* crea instancias de UsuarioLink y UsuarioMarketing, vinculando sus IDs. */
                $UsuarioLink = new UsuarioLink($mId);


                $UsuarioMarketing = new UsuarioMarketing();
                $UsuarioMarketing->setUsuarioId($UsuarioLink->getUsuarioId());
                $UsuarioMarketing->setUsucreaId($UsuarioLink->getUsuarioId());

                /* configura propiedades de un objeto UsuarioMarketing con datos específicos. */
                $UsuarioMarketing->setUsumodifId($UsuarioLink->getUsuarioId());
                $UsuarioMarketing->setValor(1);
                $UsuarioMarketing->setTipo('LINKVISIT');
                $UsuarioMarketing->setExternoId($mId);
                $UsuarioMarketing->setIp($dir_ip);
                $UsuarioMarketing->setUsuariorefId(0);

                /* establece valores y guarda datos en la base de datos usando un DAO. */
                $UsuarioMarketing->setLinkId($mId);
                $UsuarioMarketing->setBannerId(0);

                $UsuarioMarketingMySqlDAO = new UsuarioMarketingMySqlDAO();
                $UsuarioMarketingMySqlDAO->insert($UsuarioMarketing);
                $UsuarioMarketingMySqlDAO->getTransaction()->commit();



                /* inicializa un arreglo de respuesta con varios campos vacíos y estado verdadero. */
                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = "";
                $response["notification"] = array();

            } else {
                /* inicializa una respuesta con estado verdadero y propiedades vacías. */

                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = "";
                $response["notification"] = array();

            }

            break;


        case "getAgentsSystem":

            /* verifica si el usuario no está logueado y luego crea objetos. */
            if (!$_SESSION['logueado']) {
                exit();
            }

            $UsuarioPerfil = new UsuarioPerfil();
            $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


            /* obtiene parámetros de consulta y establece valores predeterminados si es necesario. */
            $Perfil_id = $_GET["roleId"];
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Asigna valores por defecto a $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 100000000;
            }


            /* asigna reglas basadas en la moneda del país del usuario. */
            $mismenus = "0";

            $rules = [];


            if ($_SESSION['PaisCond'] == "S") {
                $Pais = new Pais($_SESSION['pais_id']);
                $PaisMoneda = new PaisMoneda($_SESSION['pais_id']);

                $moneda = $PaisMoneda->moneda;

                array_push($rules, array("field" => "usuario.moneda", "data" => $moneda, "op" => "eq"));
            }


            /* Filtra usuarios según condiciones de sesión y codifica el resultado en JSON. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            }

            if ($_SESSION["win_perfil"] == "") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field"=>"usuario.eliminado","data"=>"N","op"=>"eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json2 = json_encode($filtro);

                $usuarios = $UsuarioPerfil->getChilds(" usuario.usuario_id,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario.nombre", "asc", $SkeepRows, $MaxRows, $json2, true);
            } else {
                /* Se adicionan reglas de filtrado y se obtienen perfiles de usuario desde la base de datos. */

                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));
                array_push($rules, array("field"=>"usuario.eliminado","data"=>"N","op"=>"eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json2 = json_encode($filtro);

                $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario.nombre", "asc", $SkeepRows, $MaxRows, $json2, true);

            }



            /* Convierte usuarios JSON a un array con id y nombre formateado. */
            $usuarios = json_decode($usuarios);
            $arrayf = [];

            foreach ($usuarios->data as $key => $value) {
                $array = [];
                $array["id"] = $value->{"usuario.usuario_id"};
                $array["name"] = $array["id"] . ' - ' . $value->{"usuario.nombre"} . '';

                array_push($arrayf, $array);
            }



            /* crea un arreglo de respuesta con estado y datos de usuarios. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $arrayf,
                "titles" => "",
                "total" => $usuarios->count[0]->{".count"},
                "totalRecordsCount" => $usuarios->count[0]->{".count"},

            );


            /* Inicializa un array vacío para almacenar notificaciones en la variable de respuesta. */
            $response["notification"] = array();


            break;


        /**
         * getAvailableBanners
         *
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
        case "getAvailableBanners":


            /* verifica si el usuario está logueado antes de crear instancias de objetos. */
            if (!$_SESSION['logueado']) {
                exit();
            }

            $Banner = new Banner();
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);


            /* verifica si el usuario es nulo y lanza una excepción si es así. */
            if($UsuarioMandante == null){
                throw new Exception("Inusual Detected", "100001");

            }

            $RegionId = $params->RegionId;

            /* asigna parámetros a variables para su procesamiento posterior. */
            $Languages = $params->Languages;


            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;


            /* obtiene parámetros para gestionar la paginación y búsqueda de datos. */
            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            $search = $params->search;

            if ($start != "") {
                $SkeepRows = $start;

            }


            /* Establece valores para $MaxRows y $SkeepRows si están definidos. */
            if ($length != "") {
                $MaxRows = $length;

            }


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* crea reglas para filtrar datos basados en condiciones específicas. */
            $rules = [];
//array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));

            if ($RegionId != "") {
                array_push($rules, array("field" => "banner.pais_id", "data" => "$RegionId", "op" => "eq"));

            } else {
                /* Condicional que agrega reglas a un array según país de un usuario. */

                if ($UsuarioMandante->paisId != 173) {
                    array_push($rules, array("field" => "banner.pais_id", "data" => "'" . $UsuarioMandante->paisId . "','','0'", "op" => "in"));

                }

            }

            /* Agrega reglas de filtrado para idioma y nombre si se proporcionan. */
            if ($Languages != "") {
                array_push($rules, array("field" => "banner.idioma", "data" => "$Languages", "op" => "eq"));

            }

            if ($search != "") {
                if ($search->value != "") {
                    array_push($rules, array("field" => "banner.nombre", "data" => "$search->value", "op" => "cn"));

                }
            }

            /* Se agregan reglas de filtrado a un array y se codifican en JSON. */
            array_push($rules, array("field" => "banner.estado", "data" => "A", "op" => "eq"));

            array_push($rules, array("field" => "banner.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            /* Se obtienen banners personalizados para un usuario específico y se decodifican en JSON. */
            $Banner = new Banner();
            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.* ", "banner.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);

            $final = array();

            foreach ($Banners->data as $key => $value) {

                /* Se define si un usuario es 'favorito' según un valor específico. */
                $isfavorito = 0;
                $isActivate = 0;

                if ($value->{"usuario_banner.favorito"} == 'S') {
                    $isfavorito = 1;
                }

                /* verifica si un identificador no es cero y asigna una variable. */
                if ($value->{"usuario_banner.usubanner_id"} != 0) {
                    $isActivate = 1;
                }

                $array = array();
                $array = array(
                    "activateBanner" => $isActivate,
                    "affiliateId" => $UsuarioMandante->getUsumandanteId(),
                    "canDelete" => 0,
                    "canEdit" => 1,
                    "ctr" => "50",
                    "expireDate" => "",
                    "favorite" => $isfavorito,
                    "filename" => $value->{"banner.filename"},
                    "height" => $value->{"banner.height"},
                    "id" => $value->{"banner.banner_id"},
                    "isPublished" => "1",
                    "language" => $value->{"banner.idioma"},
                    "languages" => $value->{"banner.idioma"},
                    "mine" => 0,
                    "name" => $value->{"banner.nombre"},
                    "oldFileName" => "",
                    "oldType" => "",
                    "params" => "",
                    "partnerId" => "288",
                    "path" => $urlApiAfiliados . $value->{"banner.filename"},
                    "preview" => 0,
                    "productId" => "1",
                    "productName" => "Sportsbook",
                    "size" => $value->{"banner.bsize"},
                    "status" => "OK",
                    "typeId" => "2",
                    "typeName" => "Image",
                    "updateDate" => $value->{"banner.fecha_modif"},
                    "uploadDate" => $value->{"banner.fecha_crea"},
                    "username" => $UsuarioMandante->getUsuarioMandante(),
                    "width" => $value->{"banner.width"},
                    "expirationDate" => $value->{"banner.fecha_expiracion"}, "state" => ($value->{"banner.estado"} == 'A') ? true : false
                );


                /* Añade un elemento al final de un arreglo en PHP. */
                array_push($final, $array);

            }


            /* crea una respuesta estructurada con estado, HTML y datos de registros. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => $Banners->count[0]->{".count"},
                "totalRecordsCount" => $Banners->count[0]->{".count"},

            );


            /* Crea un array vacío llamado "notification" en la variable $response. */
            $response["notification"] = array();


            break;

        /**
         * GetBalance
         *
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
        case "GetBalance":
            /* obtiene el saldo de créditos de un usuario según su sesión. */

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

            $response["status"] = true;
            $response["result"] = $Usuario->creditosAfiliacion;

            break;

        /**
         * GetDashboards
         *
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
        case "GetDashboards":


            /* Se definen variables para almacenar parámetros relacionados con un gráfico de datos. */
            $arrayDataLabelsGraph = array();

            $ToDateLocal = $params->ResultToDate;
            $FromDateLocal = $params->ResultFromDate;
            $BonusDefinitionIds = $params->BonusDefinitionIds;
            $PlayerExternalId = $params->PlayerExternalId;


            /* define parámetros de paginación y selección de datos en un sistema. */
            $MaxRows = $params->Limit;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = ($params->Offset) * $MaxRows;

            $Id = $params->Id;
            $TypeAmount = $params->TypeAmount;

            /* asigna parámetros a variables y convierte fechas al formato adecuado. */
            $State = $params->State;
            $TypeReport = $params->TypeReport;
            $Currency = $params->Currency;
            $DateFrom = $params->DateFrom;
            $FromDateLocal = date("Y-m-d 00:00:00", strtotime($DateFrom));

            $DateTo = $params->DateTo;

            /* establece fechas locales y crea un objeto de usuario. */
            $ToDateLocal = date("Y-m-d 23:59:59", strtotime($DateTo));

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $response["DateFrom"] = $FromDateLocal;
            $response["DateTo"] = $ToDateLocal;


            /* Asigna valores de parámetros a variables, ajustando agentId si es 0. */
            $linkId = $params->linkId;
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }

            /* inicializa variables en blanco si su valor es cero. */
            if ($linkId == 0) {
                $linkId = "";
            }

            $linkSelectId = $params->linkSelectId;


            if ($linkSelectId == 0) {
                $linkSelectId = "";
            }

            if ($Id == 0) {

                /* Se define un conjunto de reglas para validar datos relacionados con monedas y fechas. */
                $rules = [];
// array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));


                $fechaSql = "DATE_FORMAT(usucomision_resumen.fecha_crea,";

                switch ($TypeReport) {
                    case "0":
                        /* formatea fechas en un rango específico usando SQL y PHP. */

                        $fechaSql = $fechaSql . "'%Y-%m')";

                        $FromDateLocal = date("Y-m", strtotime($DateFrom));
                        $ToDateLocal = date("Y-m", strtotime($DateTo));

                        break;
                    case "2":
                        /* Código que formatea fechas para consultas SQL, estableciendo un rango horario. */

                        $fechaSql = $fechaSql . "'%Y-%m-%d %H')";
                        $FromDateLocal = date("Y-m-d 00", strtotime($DateFrom));
                        $ToDateLocal = date("Y-m-d 23", strtotime($DateTo));
                        break;

                    default:

                        /* Concatena una fecha en formato SQL y convierte fechas locales a formato 'Y-m-d'. */
                        $fechaSql = $fechaSql . "'%Y-%m-%d')";

                        $FromDateLocal = date("Y-m-d", strtotime($DateFrom));
                        $ToDateLocal = date("Y-m-d", strtotime($DateTo));

                        break;

                }


                /* selecciona una consulta SQL según el tipo de monto definido. */
                switch ($TypeAmount) {

                    case "1":
                        $select = "SUM(usucomision_resumen.comision) valor, " . $fechaSql . " fecha";
                        break;

                    case "2":
                        $select = "COUNT(usuario_bono.usuario_id) valor, " . $fechaSql . " fecha";
                        break;
                    default:
                        $select = "SUM(usucomision_resumen.comision) valor, " . $fechaSql . " fecha";

                        break;
                }

                /* agrega reglas de comparación en un array según ciertas condiciones. */
                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

                if ($linkId != "") {
                    array_push($rules, array("field" => "usucomision_resumen.externo_id", "data" => $linkId, "op" => "eq"));

                }


                /* Condiciona la adición de reglas según la presencia del ID del agente. */
                if ($agentId != "") {
                    array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $agentId, "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }

//array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));


                /* Se inicializan parámetros de filtro y valores predeterminados para procesamiento de datos. */
                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }


                /* establece un límite máximo de filas y codifica un filtro en JSON. */
                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }

                $json = json_encode($filtro);


                $UsucomisionResumen = new UsucomisionResumen();

                /* obtiene datos, los decodifica en JSON y prepara variables finales. */
                $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom($select, "usucomision_resumen.usucomresumen_id", "asc ", $SkeepRows, $MaxRows, $json, true, $fechaSql);
                $UsucomisionResumens = json_decode($UsucomisionResumens);


                $finalLabel = [];
                $finalAmount = [];


                /* Suma valores de comisiones y almacena fechas y montos en arrays. */
                $amount = 0;

                foreach ($UsucomisionResumens->data as $key => $value) {
                    $array = array(
                        "start" => strtotime($value->{'.fecha'})
                    );
                    array_push($finalLabel, $array);
                    array_push($finalAmount, $value->{'.valor'});


                    $amount = $amount + $value->{'.valor'};
                }



                /* Código define reglas y formatea fechas en una consulta SQL. */
                $rules = [];
// array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));


                $fechaSql = "DATE_FORMAT(usucomision_resumen.fecha_crea,";

                switch ($TypeReport) {
                    case "0":
                        /* Código que formatea fechas en un formato específico para realizar consultas SQL. */

                        $fechaSql = $fechaSql . "'%Y-%m')";

                        $FromDateLocal = date("Y-m", strtotime($DateFrom));
                        $ToDateLocal = date("Y-m", strtotime($DateTo));

                        break;
                    case "2":
                        /* formatea fechas para consultas SQL en un caso específico. */

                        $fechaSql = $fechaSql . "'%Y-%m-%d %H')";
                        $FromDateLocal = date("Y-m-d 00", strtotime($DateFrom));
                        $ToDateLocal = date("Y-m-d 23", strtotime($DateTo));
                        break;

                    default:

                        /* formatea fechas a un formato SQL "Y-m-d". */
                        $fechaSql = $fechaSql . "'%Y-%m-%d')";

                        $FromDateLocal = date("Y-m-d", strtotime($DateFrom));
                        $ToDateLocal = date("Y-m-d", strtotime($DateTo));

                        break;

                }


                /* selecciona un cálculo basado en el tipo de monto especificado. */
                switch ($TypeAmount) {

                    case "1":
                        $select = "SUM(usucomision_resumen.comision) valor, " . $fechaSql . " fecha";
                        break;

                    case "2":
                        $select = "COUNT(usuario_bono.usuario_id) valor, " . $fechaSql . " fecha";
                        break;
                    default:
                        $select = "SUM(usucomision_resumen.comision) valor, " . $fechaSql . " fecha";

                        break;
                }

                /* Se agregan reglas a un array para filtrar datos según condiciones específicas. */
                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

                if ($linkId != "") {
                    array_push($rules, array("field" => "usucomision_resumen.externo_id", "data" => $linkId, "op" => "eq"));

                }


                /* agrega condiciones a un arreglo según si $agentId está vacío. */
                if ($agentId != "") {
                    array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $agentId, "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }

//array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));


                /* Configura un filtro y valores predeterminados para la paginación de datos. */
                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }


                /* establece un límite de filas y codifica un filtro en JSON. */
                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }

                $json = json_encode($filtro);



                $rules = [];

                /* manipula fechas y reglas para filtrar usuarios por perfil y depósito. */
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace("T", " ", $ToDateLocal)));
                $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace("T", " ", $FromDateLocal)));

                array_push($rules, array("field" => "usuario.fecha_primerdeposito", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "usuario.fecha_primerdeposito", "data" => "$ToDateLocal", "op" => "le"));



                /* Añade condiciones a un arreglo si las variables no están vacías. */
                if ($linkId != "") {
                    array_push($rules, array("field" => "registro.link_id", "data" => $linkId, "op" => "eq"));

                }

                if ($agentId != "") {
                    array_push($rules, array("field" => "registro.afiliador_id", "data" => $agentId, "op" => "eq"));

                } else {
                    /* Agrega una regla al array si la condición no se cumple. */

                    array_push($rules, array("field" => "registro.afiliador_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }

// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


                /* Código para sumar montos de depósitos y filtrar resultados en JSON. */
                $select = "SUM(usuario.monto_primerdeposito) valor";

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);
                $SkeepRows=0;
                $MaxRows=1000000;

                /* Crea un nuevo usuario y obtiene una lista personalizada de usuarios en formato JSON. */
                $Usuario = new Usuario();
                $usuarios = $Usuario->getUsuariosCustom($select, "usuario.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);

                $usuarios = json_decode($usuarios);



// $finalLabel = [];
//  $finalAmount = [];

                $amountPrimerDeposito = 0;


                /* Itera sobre usuarios, acumulando valores y convertiendo fechas en formato timestamp. */
                foreach ($usuarios->data as $key => $value) {
                    $array = array(
                        "start" => strtotime($value->{'.fecha'})
                    );
//  array_push($finalLabel, $array);
//   array_push($finalAmount, $value->{'.valor'});


                    $amountPrimerDeposito = $amountPrimerDeposito + $value->{'.valor'};
                }


                /* crea un array estructurado con comisiones y depósitos formateados. */
                $final = [];
                $final["Comission"] = [];
                $final["Comission"]["Total"] = number_format($amount, 2, ',', '.');
                $final["Comission"]["Amount"] = number_format($amount, 2, ',', '.');
                $final["FirstDeposits"] = [];
                $final["FirstDeposits"]["Total"] = number_format($amountPrimerDeposito, 2, ',', '.');

                /* Formatea el monto del primer depósito con dos decimales, usando comas y puntos. */
                $final["FirstDeposits"]["Amount"] = number_format($amountPrimerDeposito, 2, ',', '.');

            }
            if ($Id == 1) {


                /* define reglas y configura formatos de fecha según el tipo de reporte. */
                $rules = [];

//array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";
                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }

                /* Código en PHP para construir una consulta de fecha para filtrar datos. */
                $SkeepRows = 0;
                $MaxRows = 100;

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;
                $fechaSql = "usuario_marketing.fecha_crea";

                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));

                /* Se definen reglas de filtro y se inicializa el número de filas a omitir. */
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

//array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }


                /* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }


                /* convierte un filtro a JSON y verifica si $linkId no está vacío. */
                $json = json_encode($filtro);

                if ($linkId != "") {
// array_push($rules, array("field" => "registro.externo_id", "data" => $linkId, "op" => "eq"));

                }


                /* Agrega condiciones a un array dependiendo si $agentId está vacío o no. */
                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }

                switch ($TypeAmount) {

                    case "1":
                        /* Genera un filtro JSON y consulta datos de marketing de usuarios en una base de datos. */

                        array_push($rules, array("field" => "tipo", "data" => "CLICKBANNER", "op" => "eq"));
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;


                    case "2":
                        /* configura reglas y solicita datos de marketing de usuarios en formato JSON. */

                        array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "eq"));
                        array_push($rules, array("field" => "banner_id", "data" => "0", "op" => "ne"));
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;

                }


                /* convierte datos JSON en arreglos de etiquetas y montos. */
                $data = json_decode($data);

                $finalLabel = [];
                $finalAmount = [];

                foreach ($data->data as $key => $value) {
                    $array = array(
                        "start" => strtotime($value->{'.fecha'})
                    );
                    array_push($finalLabel, $array);
                    array_push($finalAmount, $value->{'.valor'});
                }


                /* define reglas y configura formatos de fecha según el tipo de reporte. */
                $rules = [];
//array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";
                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }

                /* Código para establecer reglas de filtrado en una consulta según el ID de agente. */
                $SkeepRows = 0;
                $MaxRows = 100;


                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    /* Agrega reglas de validación para el campo "usuario_id" con un valor específico. */

                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }


                /* Crea reglas de filtrado para fechas en una consulta SQL usando PHP. */
                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;
                $fechaSql = "usuario_marketing.fecha_crea";

                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

//array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");


                /* asigna valores predeterminados a variables si están vacías. */
                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }


                /* establece un límite por defecto y prepara un filtro con reglas. */
                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }

// array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "le"));
                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* convierte datos a JSON, formatea fechas y define una consulta SQL. */
                $json = json_encode($filtro);

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                $select = "SUM(usuario_marketing.valor) valor, usuario_marketing.tipo";
                $UsuarioMarketing = new UsuarioMarketing();

                /* obtiene datos de usuarios de marketing y los procesa según su tipo. */
                $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario_marketing.tipo");
                $data = json_decode($data);


                $final = [];

                foreach ($data->data as $datum) {
                    switch ($datum->{'usuario_marketing.tipo'}) {

                        case "CLICKBANNER":
                            $final["Clicks"] = [];
                            $final["Clicks"]["Total"] = $datum->{'.valor'};
                            $final["Clicks"]["Amount"] = $datum->{'.valor'};

                            break;
                    }
                }



                /* Define reglas y genera formato de fecha SQL según tipo de reporte especificado. */
                $rules = [];
//array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";
                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }

                /* establece reglas para filtrar usuarios por fecha de creación en SQL. */
                $SkeepRows = 0;
                $MaxRows = 100;

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;
                $fechaSql = "usuario_marketing.fecha_crea";

                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));

                /* crea un arreglo de reglas para filtros, utilizando condiciones lógicas. */
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

//array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }


                /* asigna valores predeterminados a variables si están vacías. */
                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }



                /* Condiciona la adición de reglas según la presencia de un ID de agente. */
                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }


                /* Se crean reglas de filtrado y se convierten a formato JSON para SQL. */
                array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "le"));
                array_push($rules, array("field" => "banner_id", "data" => "0", "op" => "ne"));
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;


                /* realiza un cálculo y formatea datos de marketing de usuarios. */
                $select = "SUM(usuario_marketing.valor) valor, usuario_marketing.tipo";
                $UsuarioMarketing = new UsuarioMarketing();
                $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, "");
                $data = json_decode($data);


                foreach ($data->data as $datum) {
                    switch ($datum->{'usuario_marketing.tipo'}) {
                        case "REGISTRO":
                            $final["Players"] = [];
                            $final["Players"]["Total"] = $datum->{'.valor'};
                            $final["Players"]["Amount"] = $datum->{'.valor'};

                            break;
                    }
                }


            }

            if ($Id == 2 && ($TypeAmount == "" || $TypeAmount == "0")) {


                /*
                * Consultamos los clicks en los links
                */


                /* Inicializa variables para click y cantidad; define un array para reglas. */
                $TypeAmount = 1;
                $final["Clicks"] = [];
                $final["Clicks"]["Total"] = 0;
                $final["Clicks"]["Amount"] = 0;

                $rules = [];

//array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));


                /* asigna formatos de fecha según el tipo de reporte seleccionado. */
                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";

                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }

                /* configura variables y formatea una fecha según un tipo específico. */
                $SkeepRows = 0;
                $MaxRows = 100;

                if ($TypeAmount == '3') {
                    $fechaSql = "DATE_FORMAT(usuario.fecha_primerdeposito,'%Y-%m-%d')";
                    $fechaSql = "usuario.fecha_primerdeposito";

                } else {
                    /* establece una variable SQL para formatear una fecha en un usuario. */

                    $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea,'%Y-%m-%d')";
                    $fechaSql = "usuario_marketing.fecha_crea";

                }


                /* Se crean reglas de filtro con condiciones para una consulta utilizando "AND". */
                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

//array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");


                /* inicializa variables si están vacías, asignando valores por defecto. */
                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }


                /* establece un límite de filas y codifica datos en JSON. */
                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }

                $json = json_encode($filtro);


                if ($linkId != "") {
                    array_push($rules, array("field" => "usuario_marketing.link_id", "data" => $linkId, "op" => "eq"));

                }


                /* Añade reglas a un array basadas en condiciones de identificación. */
                if ($linkSelectId != "") {
                    array_push($rules, array("field" => "usuario_marketing.link_id", "data" => $linkSelectId, "op" => "eq"));
                }

                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    /* Agrega una regla al array si no se cumple una condición previa. */

                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }

                switch ($TypeAmount) {

                    case "1":
                        /* Construye un filtro en formato JSON y realiza una consulta a la base de datos. */

                        array_push($rules, array("field" => "tipo", "data" => "LINKVISIT", "op" => "eq"));
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;


                    case "2":
                        /* Construye un filtro para consultar registros de marketing en formato JSON. */


                        array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "eq"));
                        array_push($rules, array("field" => "link_id", "data" => "0", "op" => "ne"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;


                    case "3":
                        /* Se construye un filtro y consulta datos de usuarios con ciertas condiciones. */

                        array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "eq"));
                        array_push($rules, array("field" => "link_id", "data" => "0", "op" => "ne"));
                        array_push($rules, array("field" => "usuario.fecha_primerdeposito", "data" => "", "op" => "nisnull"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario.fecha_primerdeposito," . $fechaSql2;

                        $select = "COUNT(usuario.usuario_id) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;

                }



                /* Decodifica datos JSON y establece propiedades para etiquetas de visualización. */
                $data = json_decode($data);

                $finalLabelName = 'Clicks';
                $finalLabelColor = '#22c88a';
                $finalLabelBackground = 'rgb(34, 200, 138';
                $finalLabel = [];

                /* procesa datos, almacenando fechas y valores en arrays estructurados. */
                $finalAmount = [];

                foreach ($data->data as $key => $value) {
                    $array = array(
                        "start" => strtotime($value->{'.fecha'})
                    );
                    array_push($finalLabel, $array);
                    array_push($finalAmount, $value->{'.valor'});

                    $final["Clicks"]["Total"] = intval($value->{'.valor'});
                    $final["Clicks"]["Amount"] = intval(
                        $value->{'.valor'}
                    );

                }

                /* crea un array con datos sobre etiquetas de gráfico en PHP. */
                $arrayDataLabelsGraph["Data"] = array(
                    "Name" => $finalLabelName,
                    "Color" => $finalLabelColor,
                    "Background" => $finalLabelBackground,
                    "Label" => $finalLabel,
                    "Amount" => $finalAmount,
                    "Total" => $final
                );


                /*
                * Obtenemos los usuarios registrados en las fechas seleccionadas
                */

                /* Inicializa variables y estructuras para manejar información sobre jugadores y sus cantidades. */
                $TypeAmount = 2;
                $final["Players"] = [];
                $final["Players"]["Total"] = 0;
                $final["Players"]["Amount"] = 0;

                $rules = [];



                /* define reglas y ajusta el formato de fecha según el tipo de reporte. */
                $rules = [];

//array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";

                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }

                /* Establece variables para omitir y limitar filas, ajustando fecha según tipo. */
                $SkeepRows = 0;
                $MaxRows = 100;

                if ($TypeAmount == '3') {
                    $fechaSql = "DATE_FORMAT(usuario.fecha_primerdeposito,'%Y-%m-%d')";
                    $fechaSql = "usuario.fecha_primerdeposito";

                } else {
                    /* Condicional para definir formato de fecha en consulta SQL según una condición. */

                    $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea,'%Y-%m-%d')";
                    $fechaSql = "usuario_marketing.fecha_crea";

                }


                /* Se añaden reglas de filtrado a un array para consultas condicionales. */
                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }


                /* Inicializa variables si están vacías, asignando valores por defecto a $OrderedItem y $MaxRows. */
                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }


                /* Convierte un filtro a JSON y agrega una regla si 'linkId' no está vacío. */
                $json = json_encode($filtro);


                if ($linkId != "") {
                    array_push($rules, array("field" => "usuario_marketing.link_id", "data" => $linkId, "op" => "eq"));

                }


                /* Agrega reglas a un array basado en condiciones de variables definidas. */
                if ($linkSelectId != "") {
                    array_push($rules, array("field" => "usuario_marketing.link_id", "data" => $linkSelectId, "op" => "eq"));
                }

                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    /* Agrega una regla comparando el ID de usuario de marketing con el mandante. */

                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }
                switch ($TypeAmount) {

                    case "1":
                        /* Crea un filtro, construye una consulta SQL y obtiene datos de marketing de usuarios. */

                        array_push($rules, array("field" => "tipo", "data" => "LINKVISIT", "op" => "eq"));
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;


                    case "2":
                        /* crea reglas de filtrado y obtiene datos de marketing de usuarios. */

                        array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_marketing.link_id", "data" => "0", "op" => "ne"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;


                    case "3":
                        /* Construye un filtro en JSON para consultar usuarios de marketing en una base de datos. */

                        array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_marketing.link_id", "data" => "0", "op" => "ne"));
                        array_push($rules, array("field" => "usuario.fecha_primerdeposito", "data" => "", "op" => "nisnull"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario.fecha_primerdeposito," . $fechaSql2;

                        $select = "COUNT(usuario.usuario_id) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;

                }


                /* decodifica JSON y define parámetros para una etiqueta visual. */
                $data = json_decode($data);

                $finalLabelName = 'Registros';
                $finalLabelColor = '#4422c8';
                $finalLabelBackground = 'rgb(68, 34, 200';
                $finalLabel = [];

                /* calcula totales y almacena datos de fechas y valores en arrays. */
                $finalAmount = [];

                foreach ($data->data as $key => $value) {
                    $array = array(
                        "start" => strtotime($value->{'.fecha'})
                    );
                    array_push($finalLabel, $array);
                    array_push($finalAmount, $value->{'.valor'});

                    $final["Players"]["Total"] += intval($value->{'.valor'});
                    $final["Players"]["Amount"] += intval($value->{'.valor'});

                }

                /* Crea un arreglo asociativo para almacenar etiquetas y valores de un gráfico. */
                $arrayDataLabelsGraph["Data"] = array(
                    "Name" => $finalLabelName,
                    "Color" => $finalLabelColor,
                    "Background" => $finalLabelBackground,
                    "Label" => $finalLabel,
                    "Amount" => $finalAmount,
                    "Total" => $final
                );


                /*
                Consultamos los primeros depositos generados en las fechas seleccionadas
                */


                /* Se inicializan variables para manejar depósitos y reglas en un sistema. */
                $TypeAmount = 3;
                $final["FirstDeposits"] = [];
                $final["FirstDeposits"]["Total"] = 0;
                $final["FirstDeposits"]["Amount"] = 0;

                $rules = [];

//array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));


                /* asigna formatos de fecha SQL según el tipo de informe seleccionado. */
                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";

                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }

                /* define variables y ajusta una consulta según el tipo de monto. */
                $SkeepRows = 0;
                $MaxRows = 100;

                if ($TypeAmount == '3') {
                    $fechaSql = "DATE_FORMAT(usuario.fecha_primerdeposito,'%Y-%m-%d')";
                    $fechaSql = "usuario.fecha_primerdeposito";

                } else {
                    /* Condicional que asigna formato de fecha SQL a la variable $fechaSql. */

                    $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea,'%Y-%m-%d')";
                    $fechaSql = "usuario_marketing.fecha_crea";

                }


                /* agrega reglas de filtrado a un arreglo y define condiciones de salto. */
                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }


                /* establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }


                /* convierte un filtro a JSON y añade una regla si linkId no es vacío. */
                $json = json_encode($filtro);


                if ($linkId != "") {
                    array_push($rules, array("field" => "usuario_marketing.link_id", "data" => $linkId, "op" => "eq"));

                }


                /* Agrega condiciones a un array de reglas basadas en variables no vacías. */
                if ($linkSelectId != "") {
                    array_push($rules, array("field" => "usuario_marketing.link_id", "data" => $linkSelectId, "op" => "eq"));
                }

                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    /* Añade una regla de comparación para el usuario de marketing en un array. */

                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }
                switch ($TypeAmount) {

                    case "1":
                        /* construye y ejecuta una consulta SQL utilizando ciertas reglas y filtros. */

                        array_push($rules, array("field" => "tipo", "data" => "LINKVISIT", "op" => "eq"));
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;


                    case "2":
                        /* genera un filtro en JSON para una consulta SQL sobre marketing. */

                        array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_marketing.link_id", "data" => "0", "op" => "ne"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;


                    case "3":
                        /* Construye reglas de filtrado y ejecuta consulta para usuarios en marketing. */

                        array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_marketing.link_id", "data" => "0", "op" => "ne"));
                        array_push($rules, array("field" => "usuario.fecha_primerdeposito", "data" => "", "op" => "nisnull"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario.fecha_primerdeposito," . $fechaSql2;

                        $select = "COUNT(usuario.usuario_id) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;

                }


                /* procesa datos JSON para calcular totales de depósitos iniciales. */
                $data = json_decode($data);

                $finalLabelName = 'Primer Deposito';
                $finalLabelColor = '#c82222';
                $finalLabelBackground = 'rgb(200, 34, 34';
//$finalLabel = [];
// $finalAmount = [];

                foreach ($data->data as $key => $value) {
                    $array = array(
                        "start" => strtotime($value->{'.fecha'})
                    );
//array_push($finalLabel, $array);
//array_push($finalAmount, $value->{'.valor'});


                    $final["FirstDeposits"]["Total"] += intval($value->{'.valor'});
                    $final["FirstDeposits"]["Amount"] += intval($value->{'.valor'});

                }

                /* Crea un arreglo asociativo con datos para un gráfico. */
                $arrayDataLabelsGraph["Data2"] = array(
                    "Name" => $finalLabelName,
                    "Color" => $finalLabelColor,
                    "Background" => $finalLabelBackground,
                    "Label" => $finalLabel,
                    "Amount" => $finalAmount,
                    "Total" => $final
                );


            } elseif ($Id == 2 && ($TypeAmount != "" && $TypeAmount != "0")) {


                /* define reglas de filtrado y establece un formato de fecha según el tipo de informe. */
                $rules = [];

//array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";

                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }

                /* configura variables y define una consulta SQL basada en condiciones. */
                $SkeepRows = 0;
                $MaxRows = 100;

                if ($TypeAmount == '3') {
                    $fechaSql = "DATE_FORMAT(usuario.fecha_primerdeposito,'%Y-%m-%d')";
                    $fechaSql = "usuario.fecha_primerdeposito";

                } else {
                    /* Se define una variable SQL para formatear o asignar una fecha de creación. */

                    $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea,'%Y-%m-%d')";
                    $fechaSql = "usuario_marketing.fecha_crea";

                }


                /* Se agregan reglas de filtrado para fechas en un array estructurado. */
                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

//array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");


                /* asegura que variables estén inicializadas con valores predeterminados si están vacías. */
                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }


                /* inicializa $MaxRows y agrega condiciones a una consulta JSON. */
                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }

                $json = json_encode($filtro);


                if ($linkId != "") {
                    array_push($rules, array("field" => "usuario_marketing.link_id", "data" => $linkId, "op" => "eq"));

                }


                /* Se agregan reglas de filtro basadas en condiciones de variables no vacías. */
                if ($linkSelectId != "") {
                    array_push($rules, array("field" => "usuario_marketing.link_id", "data" => $linkSelectId, "op" => "eq"));
                }

                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    /* Agrega una regla al arreglo si se cumple una condición. */

                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }


                switch ($TypeAmount) {

                    case "1":
                        /* Genera una consulta SQL con filtros y agrupa resultados de marketing por fecha. */

                        array_push($rules, array("field" => "tipo", "data" => "LINKVISIT", "op" => "eq"));
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;


                    case "2":


                        /* Se crea un arreglo de reglas para validar el registro, si 'linkId' no está vacío. */
                        $rulesRegister = [];

                        if ($linkId != "") {
                            array_push($rulesRegister, array("field" => "registro.link_id", "data" => $linkId, "op" => "eq"));

                        }


                        /* Agrega reglas de filtrado basadas en variables no vacías. */
                        if ($linkSelectId != "") {
                            array_push($rulesRegister, array("field" => "registro.link_id", "data" => $linkSelectId, "op" => "eq"));
                        }

                        if ($agentId != "") {
                            array_push($rulesRegister, array("field" => "registro.afiliador_id", "data" => "$agentId", "op" => "eq"));

                        } else {
                            /* Añade una regla al array si no se cumplen condiciones previas. */

                            array_push($rulesRegister, array("field" => "registro.afiliador_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                        }


                        /* Se añaden reglas de filtrado para realizar consultas sobre registros de usuario. */
                        array_push($rulesRegister, array("field" => "usuario.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
                        array_push($rulesRegister, array("field" => "usuario.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

// array_push($rulesRegister, array("field" => "tipo", "data" => "REGISTRO", "op" => "eq"));
                        array_push($rulesRegister, array("field" => "registro.link_id", "data" => "0", "op" => "ne"));

                        $filtroRegister = array("rules" => $rulesRegister, "groupOp" => "AND");

                        /* genera un JSON y construye una consulta SQL para contar usuarios. */
                        $jsonRegister = json_encode($filtroRegister);

                        $fechaSql = "DATE_FORMAT(usuario.fecha_crea," . $fechaSql2;

                        $select = "COUNT(usuario.usuario_id) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
//$data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        /* Crea un objeto Usuario y obtiene datos de usuarios personalizados según parámetros específicos. */
                        $Usuario = new Usuario();

                        $data = $Usuario->getUsuariosCustom($select, "usuario.usuario_id", "desc", 0, 100000, $jsonRegister, true, $fechaSql);


                        break;


                    case "3":
                        /* Se crean reglas de filtrado para una consulta SQL sobre usuarios registrados. */

                        array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "eq"));
                        array_push($rules, array("field" => "link_id", "data" => "0", "op" => "ne"));
                        array_push($rules, array("field" => "usuario.fecha_primerdeposito", "data" => "", "op" => "nisnull"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario.fecha_primerdeposito," . $fechaSql2;

                        $select = "COUNT(usuario.usuario_id) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;

                }


                /* Decodifica datos JSON y establece propiedades para una etiqueta de clics. */
                $data = json_decode($data);

                $finalLabelName = 'Clicks';
                $finalLabelColor = '#22c88a';
                $finalLabelBackground = 'rgb(34, 200, 138';
                $finalLabel = [];

                /* crea arreglos para etiquetas y valores a partir de datos procesados. */
                $finalLabel = [];

                $finalLabel = [];
                $finalAmount = [];

                foreach ($data->data as $key => $value) {
                    $array = array(
                        "start" => strtotime($value->{'.fecha'})
                    );
                    array_push($finalLabel, $array);
                    array_push($finalAmount, $value->{'.valor'});
                }


                /* Define reglas de reporte según tipo y configura formato de fecha SQL. */
                $rules = [];
//array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";
                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }

                /* define variables y establece reglas para filtrar fechas en consultas SQL. */
                $SkeepRows = 0;
                $MaxRows = 100;

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;
                $fechaSql = "usuario_marketing.fecha_crea";

                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));

                /* configura reglas de filtrado en un arreglo para consultas. */
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

//array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }


                /* Asignación de valores predeterminados a variables si están vacías. */
                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }


                /* Condicionales que agregan reglas basadas en variables $linkId y $linkSelectId. */
                if ($linkId != "") {
                    array_push($rules, array("field" => "usuario_marketing.link_id", "data" => $linkId, "op" => "eq"));

                }


                if ($linkSelectId != "") {
                    array_push($rules, array("field" => "usuario_marketing.link_id", "data" => $linkSelectId, "op" => "eq"));
                }


                /* Condicional que agrega reglas según si $agentId está vacío o no. */
                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }


// array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "le"));

                /* Se crea un filtro JSON y se prepara una consulta SQL para sumar valores. */
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                $select = "SUM(usuario_marketing.valor) valor, usuario_marketing.tipo";

                /* Se crea un objeto y se obtienen datos de usuario en formato JSON. */
                $UsuarioMarketing = new UsuarioMarketing();
                $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.tipo", "asc", $SkeepRows, $MaxRows, $json, true, "");
                $data = json_decode($data);


                $final = [];


                /* Código que procesa datos de clics y acumula totales según el tipo de usuario. */
                $final["Clicks"] = [];
                $final["Clicks"]["Total"] = 0;
                $final["Clicks"]["Amount"] = 0;

                foreach ($data->data as $datum) {
                    switch ($datum->{'usuario_marketing.tipo'}) {

                        case "LINKVISIT":
                            $final["Clicks"] = [];
                            $final["Clicks"]["Total"] = $datum->{'.valor'};
                            $final["Clicks"]["Amount"] = $datum->{'.valor'};

                            break;
                    }
                }


                /* establece reglas y configura una fecha SQL según el tipo de reporte. */
                $rules = [];
//array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";
                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }

                /* configura filtros para seleccionar registros según la fecha de creación. */
                $SkeepRows = 0;
                $MaxRows = 100;

                $fechaSql = "DATE_FORMAT(usuario.fecha_crea,'%Y-%m-%d')";
                $fechaSql = "usuario.fecha_crea";

                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));

                /* Agrega reglas a un filtro para aplicar condiciones en una consulta. */
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

//array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }


                /* Inicializa variables si están vacías, asignando valores predeterminados. */
                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }



                /* Agrega reglas de filtro basadas en condiciones de $linkId y $linkSelectId. */
                if ($linkId != "") {
                    array_push($rules, array("field" => "registro.link_id", "data" => $linkId, "op" => "eq"));

                }

                if ($linkSelectId != "") {
                    array_push($rules, array("field" => "registro.link_id", "data" => $linkSelectId, "op" => "eq"));
                }


                /* Condicional que agrega reglas según si $agentId está vacío o no. */
                if ($agentId != "") {
                    array_push($rules, array("field" => "registro.afiliador_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "registro.afiliador_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }


                /* Se crean reglas de filtrado para consultar fechas y condición de registro. */
                array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

// array_push($rulesRegister, array("field" => "tipo", "data" => "REGISTRO", "op" => "eq"));
                array_push($rules, array("field" => "registro.link_id", "data" => "0", "op" => "ne"));

                $filtroRegister = array("rules" => $rules, "groupOp" => "AND");

                /* convierte datos a JSON y prepara una consulta SQL para contar usuarios. */
                $jsonRegister = json_encode($filtroRegister);

                $fechaSql = "DATE_FORMAT(usuario.fecha_crea," . $fechaSql2;

                $select = "COUNT(usuario.usuario_id) valor, " . $fechaSql . " fecha";
                $UsuarioMarketing = new UsuarioMarketing();
//$data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                /* Crea un nuevo usuario y obtiene datos decodificados en formato JSON. */
                $Usuario = new Usuario();

                $data = $Usuario->getUsuariosCustom($select, "usuario.usuario_id", "desc", 0, 100000, $jsonRegister, true);


                $data = json_decode($data);



                /* Inicializa un array y actualiza valores de jugadores desde un conjunto de datos. */
                $final["Players"] = [];
                $final["Players"]["Total"] = 0;
                $final["Players"]["Amount"] = 0;


                foreach ($data->data as $datum) {
                    $final["Players"] = [];
                    $final["Players"]["Total"] = $datum->{'.valor'};
                    $final["Players"]["Amount"] = $datum->{'.valor'};

                }



                /* Define reglas y formatea fecha SQL según el tipo de reporte indicado. */
                $rules = [];
//array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";
                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }

                /* configura parámetros para filtrar datos por fecha en SQL. */
                $SkeepRows = 0;
                $MaxRows = 100;

                $fechaSql = "DATE_FORMAT(usuario.fecha_primerdeposito," . $fechaSql2;
                $fechaSql = "usuario.fecha_primerdeposito";

                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));

                /* construye un filtro de reglas para una consulta, ajustando parámetros y condiciones. */
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

//array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }


                /* Asigna valores por defecto a $OrderedItem y $MaxRows si están vacíos. */
                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }



                /* Condicionales que agregan reglas a un arreglo basado en variables no vacías. */
                if ($linkId != "") {
                    array_push($rules, array("field" => "registro.link_id", "data" => $linkId, "op" => "eq"));

                }


                if ($linkSelectId != "") {
//array_push($rules, array("field" => "usuario_marketing.externo_id", "data" => $linkSelectId, "op" => "eq"));
                    array_push($rules, array("field" => "registro.link_id", "data" => $linkSelectId, "op" => "eq"));
                }



                /* Condicional que añade reglas según si $agentId está vacío o no. */
                if ($agentId != "") {
                    array_push($rules, array("field" => "registro.afiliador_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "registro.afiliador_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }


                /* Se crean reglas de filtrado y se convierten a formato JSON para consultas. */
                array_push($rules, array("field" => "registro.link_id", "data" => "0", "op" => "ne"));
                array_push($rules, array("field" => "usuario.fecha_primerdeposito", "data" => "", "op" => "nisnull"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $fechaSql = "DATE_FORMAT(usuario.fecha_primerdeposito," . $fechaSql2;


                /* cuenta usuarios y obtiene resultados en formato JSON. */
                $select = "COUNT(usuario.usuario_id) valor,'REGISTRO' tipo";

                $Usuario = new Usuario();

                $data = $Usuario->getUsuariosCustom($select, "usuario.usuario_id", "desc", 0, 100000, $json, true);

                $data = json_decode($data);


                /* inicializa y actualiza total y cantidad de depósitos desde un conjunto de datos. */
                $final["FirstDeposits"] = [];
                $final["FirstDeposits"]["Total"] = 0;
                $final["FirstDeposits"]["Amount"] = 0;

                foreach ($data->data as $datum) {
                    $final["FirstDeposits"] = [];
                    $final["FirstDeposits"]["Total"] = $datum->{'.valor'};
                    $final["FirstDeposits"]["Amount"] = $datum->{'.valor'};

                }


            }


            /* crea un array de respuesta si no hay datos previos. */
            if (oldCount($arrayDataLabelsGraph) == 0) {
                $response["Data"] = array(
                    "Name" => $finalLabelName,
                    "Color" => $finalLabelColor,
                    "Background" => $finalLabelBackground,
                    "Label" => $finalLabel,
                    "Amount" => $finalAmount,
                    "Total" => $final
                );
            } else {
                /* combina dos arreglos en uno solo usando `array_merge`. */

                $response = array_merge(
                    $response, $arrayDataLabelsGraph
                );
            }


            /* estructura una respuesta indicando éxito y sin errores. */
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Result"] = $final;


            break;

        /**
         * getMyMedia
         *
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
        case "getMyMedia":


            /* Inicializa objetos y variables para manejar parámetros de usuario y paginación de datos. */
            $Banner = new Banner();
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* inicializa variables si no tienen valores asignados. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            /* Verifica si el usuario es nulo y configura reglas según el país en sesión. */
            if($UsuarioMandante == null){
                throw new Exception("Inusual Detected", "100001");
            }

            $rules = [];
//array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));


            if ($_SESSION['PaisCond'] == "S") {
                $Pais = new Pais($_SESSION['pais_id']);
                $PaisMoneda = new PaisMoneda($_SESSION['pais_id']);

                $moneda = $PaisMoneda->moneda;

                array_push($rules, array("field" => "banner.pais_id", "data" => $moneda, "op" => "eq"));
            }


            /* Crea un filtro JSON condicionado a una sesión específica en PHP. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "banner.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);



            /* Se obtienen banners personalizados de un usuario y se decodifican en JSON. */
            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.*  ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);

            $final = array();

            foreach ($Banners->data as $key => $value) {

                /* verifica si un usuario tiene un banner favorito y actualiza una variable. */
                $isfavorito = 0;

                $isActivate = 0;

                if ($value->{"usuario_banner.favorito"} == 'S') {
                    $isfavorito = 1;
                }

                /* verifica un valor y establece una variable si la condición se cumple. */
                if ($value->{"usuario_banner.landing_id"} != 0) {
                    $isActivate = 1;
                }

                $array = array();
                $array = array(
                    "activateBanner" => $isActivate,
                    "affiliateId" => $UsuarioMandante->getUsumandanteId(),
                    "canDelete" => 0,
                    "canEdit" => 0,
                    "ctr" => "50",
                    "expireDate" => "",
                    "favorite" => $isfavorito,
                    "filename" => $value->{"banner.filename"},
                    "height" => $value->{"banner.height"},
                    "id" => $value->{"banner.banner_id"},
                    "isPublished" => "1",
                    "language" => $value->{"banner.idioma"},
                    "languages" => $value->{"banner.idioma"},
                    "mine" => 0,
                    "name" => $value->{"banner.banner_id"},
                    "oldFileName" => "",
                    "oldType" => "",
                    "params" => "",
                    "partnerId" => "288",
                    "path" => $urlApiAfiliados . $value->{"banner.filename"},
                    "preview" => 0,
                    "productId" => "1",
                    "productName" => "Sportsbook",
                    "size" => $value->{"banner.bsize"},
                    "status" => "OK",
                    "typeId" => "2",
                    "typeName" => "Image",
                    "updateDate" => $value->{"banner.fecha_modif"},
                    "uploadDate" => $value->{"banner.fecha_crea"},
                    "username" => $UsuarioMandante->getNombres(),
                    "width" => $value->{"banner.width"},
                    "expirationDate" => $value->{"banner.fecha_expiracion"}, "state" => ($value->{"banner.estado"} == 'A') ? true : false
                );


                /* agrega un array a otro y establece una respuesta positiva. */
                array_push($final, $array);

            }

            $response["status"] = true;
            $response["html"] = "";

            /* asigna un arreglo de respuesta con registros y conteo total. */
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );


            /* Inicializa un array vacío llamado "notification" en la variable de respuesta. */
            $response["notification"] = array();

            break;

        /**
         * getActiveBanners
         *
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
        case "getActiveBanners":


            /* Se crean instancias de Banner y UsuarioMandante con parámetros de sesión y configuración. */
            $Banner = new Banner();
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;


            /* valida la existencia de un usuario antes de continuar su ejecución. */
            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            if($UsuarioMandante == null){
                throw new Exception("Inusual Detected", "100001");
            }


            /* asigna valores a $SkeepRows y $MaxRows si no están vacíos. */
            if ($start != "") {
                $SkeepRows = $start;

            }

            if ($length != "") {
                $MaxRows = $length;

            }


            /* asigna columnas y orden, y establece filas a omitir. */
            $columns = $params->columns;
            $order = $params->order;


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* inicializa variables si están vacías, asignando valores predeterminados. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Se definen reglas de filtrado para una consulta utilizando operaciones lógicas. */
            $rules = [];
            /*            array_push($rules, array("field" => "usuario_banner.landing_id", "data" => "0", "op" => "ne"));*/
            array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, ['field' => 'banner.estado', 'data' => 'A', 'op' => 'eq']);

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Convierte datos a JSON, obtiene banners personalizados y decodifica el resultado. */
            $json = json_encode($filtro);


            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.*  ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);


            $final = array();

            foreach ($Banners->data as $key => $value) {


                /* Se inicializan variables y se asigna valor según condición de 'favorito'. */
                $isfavorito = 0;
                $isActivate = 0;

                if ($value->{"usuario_banner.favorito"} == 'S') {
                    $isfavorito = 1;
                }

                /* verifica un valor y asigna 1 a $isActivate si cumple la condición. */
                if ($value->{"usuario_banner.landing_id"} != 0) {
                    $isActivate = 1;
                }

                $array = array();
                $array = array(
                    "activateBanner" => $isActivate,

                    "affiliateId" => 0,
                    "canDelete" => 0,
                    "canEdit" => 0,
                    "ctr" => "50",
                    "expireDate" => "",
                    "favorite" => $isfavorito,
                    "filename" => $value->{"banner.filename"},
                    "height" => $value->{"banner.height"},
                    "id" => $value->{"banner.banner_id"},
                    "isPublished" => "1",
                    "language" => $value->{"banner.idioma"},
                    "languages" => $value->{"banner.idioma"},
                    "mine" => 0,
                    "name" => $value->{"banner.nombre"},
                    "oldFileName" => "",
                    "oldType" => "",
                    "params" => "",
                    "partnerId" => "288",
                    "path" => $urlApiAfiliados . $value->{"banner.filename"},
                    "preview" => 0,
                    "productId" => "1",
                    "productName" => "Sportsbook",
                    "size" => $value->{"banner.bsize"},
                    "status" => "OK",
                    "typeId" => "2",
                    "typeName" => "Image",
                    "updateDate" => $value->{"banner.fecha_modif"},
                    "uploadDate" => $value->{"banner.fecha_crea"},
                    "username" => $UsuarioMandante->getUsuarioMandante(),
                    "width" => $value->{"banner.width"},
                    "expirationDate" => $value->{"banner.fecha_expiracion"}, "state" => ($value->{"banner.estado"} == 'A') ? true : false
                );


                /* Agrega el contenido de `$array` al final del arreglo `$final`. */
                array_push($final, $array);

            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );

            $response["notification"] = array();

            break;

        case "GetKpi":


            /* Se inicializan objetos y se formatean fechas a partir de parámetros recibidos. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $UsucomisionResumen = new UsucomisionResumen();

            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace("T", " ", $params->EndDate)));
            $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace("T", " ", $params->BeginDate)));

            /* Asignación de variables desde un objeto $params en un sistema de gestión de apuestas. */
            $BetShopId = $params->BetShopId;
            $ClientId = $params->ClientId;
            $PaymentTypeId = $params->PaymentTypeId;
            $State = $params->State;
            $WithDrawTypeId = $params->WithDrawTypeId;
            $ByAllowDate = $params->ByAllowDate;


            /* convierte una variable a booleano y asigna valores de parámetros. */
            $ByAllowDate = (bool)($ByAllowDate);

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $draw = $params->draw;

            /* establece variables y prepara parámetros para ordenar datos de una consulta. */
            $length = $params->length;
            $start = $params->start;

            $OrderedItem = "usucomision_resumen.fecha_crea";
            $OrderType = "asc";


            if ($start != "") {
                $SkeepRows = $start;

            }


            /* asigna longitud y calcula la diferencia de fechas. */
            if ($length != "") {
                $MaxRows = $length;

            }

            $datediff = strtotime(str_replace("T", " ", $params->EndDate)) - strtotime(str_replace("T", " ", $params->BeginDate));


            /* Calcula diferencias de días y ajusta fechas basadas en parámetros ingresados. */
            $daysDiff = round($datediff / (60 * 60 * 24));


            $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace("T", " ", $params->BeginDate) . ' + ' . intval($SkeepRows) . ' day'));

            $sum = intval($SkeepRows) + $MaxRows;

            /* calcula una fecha y la compara con otra para asignarla. */
            $FromDateLocal2 = date("Y-m-d 23:59:59", strtotime(str_replace("T", " ", $params->BeginDate) . ' + ' . $sum . ' day'));

            if ($FromDateLocal2 > $ToDateLocal) {

            } else {
                $ToDateLocal = $FromDateLocal2;
            }



            /* Calcula la diferencia en días entre dos fechas usando Unix timestamps. */
            $datediff2 = strtotime($ToDateLocal) - strtotime($FromDateLocal);

            $daysDiff2 = round($datediff2 / (60 * 60 * 24));


            /*
            $columns = $params->columns;
            $order = $params->order;


            foreach ($order as $item) {

            switch ($columns[$item->column]->data) {
            case "Id":
            $OrderedItem = "usucomision_resumen.usucomresumen_id";
            $OrderType = $item->dir;
            break;

            case "Date":
            $OrderedItem = "usucomision_resumen.fecha_crea";
            $OrderType = $item->dir;
            break;


            case "AmountBase":
            $OrderedItem = "usucomision_resumen.valor_base";
            $OrderType = $item->dir;
            break;


            case "Commissions":
            $OrderedItem = "usucomision_resumen.comision";
            $OrderType = $item->dir;
            break;


            case "NetAmount":
            $OrderedItem = "usucomision_resumen.comision";
            $OrderType = $item->dir;
            break;

            }

            }*/


            /* inicializa variables si están vacías, asignando valores predeterminados. */
            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* establece un valor máximo por defecto y define condiciones basadas en fechas. */
            if ($MaxRows == "") {
                $MaxRows = 10000;
            }

            $rules = [];
//array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));

            if (!$ByAllowDate || $ByAllowDate == "false") {
//  array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
//  array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

            } else {
                /* Condición que agrega reglas de filtrado de fechas a un array. */

//    array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
//   array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

            }


            /* agrega reglas para filtrar datos de una consulta según criterios específicos. */
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));


            if ($State != "") {
                array_push($rules, array("field" => "usucomision_resumen.estado", "data" => "$State", "op" => "eq"));
            }



            /* Crea un filtro JSON y consulta comisiones agrupadas por fecha en la base de datos. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" usucomision_resumen.fecha_crea,SUM(usucomision_resumen.comision) comision ", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.fecha_crea");
            $UsucomisionResumens = json_decode($UsucomisionResumens);


            /* Se crea un array con fechas y valores iniciales por días. */
            $final2 = array();
            $final = array();
            for ($i = 0; $i < $daysDiff2; $i++) {
                $sum = $i + intval($SkeepRows);
                $array = [];
                $array["Date"] = date("Y-m-d", strtotime(str_replace("T", " ", $params->BeginDate) . ' + ' . $sum . ' day'));
                $array["Commission"] = 0;
                $array["Clicks"] = 0;
                $array["Register"] = 0;


                array_push($final2, $array);

            }



            /* Itera datos, compara fechas y asigna valores a un array final. */
            foreach ($final2 as $item) {

                foreach ($UsucomisionResumens->data as $key => $value) {


                    print_r($value);
                    if ($item["Date"] == date("Y-m-d", strtotime($value->{"usucomision_resumen.fecha_crea"}))) {

                        $item["Commission"] = $value->{".comision"};
                        $item["Clicks"] = 0;
                        $item["Register"] = 0;

                    }
                }
                array_push($final, $item);


            }


            /* Se definen reglas de filtrado para consultar registros de marketing. */
            $rules = [];
            array_push($rules, array("field" => "usuario_marketing.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
            array_push($rules, array("field" => "usuario_marketing.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
            array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte un filtro a JSON y prepara una consulta SQL para sumas. */
            $json = json_encode($filtro);

            $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea,'%Y-%m-%d')";

            $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha, usuario_marketing.tipo";
            $UsuarioMarketing = new UsuarioMarketing();

            /* obtiene y decodifica datos de usuario marketing, luego inicializa variables. */
            $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "fecha", "asc", $SkeepRows, $MaxRows, $json, true, "usuario_marketing.tipo," . $fechaSql);
            $data = json_decode($data);

            $final2 = $final;
            $final = array();
            $fecha = "";

            /* Se declara un arreglo vacío llamado `$array1` en PHP. */
            $array1 = array();

            foreach ($final2 as $item) {


                foreach ($data->data as $key => $value) {

                    /* actualiza contadores según el tipo de acción en una fecha específica. */
                    if ($item["Date"] == $value->{'.fecha'}) {


                        switch ($value->{'usuario_marketing.tipo'}) {

                            case "LINKVISIT":
                                $item["Clicks"] = $item["Clicks"] + $value->{'.valor'};
                                break;

                            case "CLICKBANNER":
                                $item["Clicks"] = $item["Clicks"] + $value->{'.valor'};
                                break;

                            case "REGISTRO":
                                $item["Register"] = $item["Register"] + $value->{'.valor'};
                                break;


                        }
                    }

                }



                /* Añade un elemento al final de un array en PHP. */
                array_push($final, $item);


            }



            /* crea una respuesta estructurada con estado, HTML y datos de registros. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => $daysDiff,
                "totalRecordsCount" => $daysDiff,

            );


            /* Se inicializa un array vacío llamado "notification" dentro del arreglo "$response". */
            $response["notification"] = array();

            break;

        /**
         * GetCommisions
         *
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
        case "GetCommisions":


            /* Inicializa objetos y define rangos de fecha a partir de los parámetros recibidos. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $UsucomisionResumen = new UsucomisionResumen();

            $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
            $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));

            /* Asignación de valores de parámetros a variables en un sistema de apuestas. */
            $BetShopId = $params->BetShopId;
            $ClientId = $params->ClientId;
            $PaymentTypeId = $params->PaymentTypeId;
            $State = $params->State;
            $WithDrawTypeId = $params->WithDrawTypeId;
            $ByAllowDate = $params->ByAllowDate;


            /* convierte variables y asigna parámetros a nuevas variables. */
            $ByAllowDate = (bool)($ByAllowDate);

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $draw = $params->draw;

            /* asigna valores y configura la paginación para una consulta. */
            $length = $params->length;
            $start = $params->start;

            $OrderedItem = "usucomision_resumen.usucomresumen_id";
            $OrderType = "desc";


            if ($start != "") {
                $SkeepRows = $start;

            }


            /* Asigna $length a $MaxRows si no está vacío y obtiene columnas de parámetros. */
            if ($length != "") {
                $MaxRows = $length;

            }

            $columns = $params->columns;

            /* Asigna el valor de "order" desde $params a la variable $order. */
            $order = $params->order;


            foreach ($order as $item) {

                switch ($columns[$item->column]->data) {
                    case "Id":
                        /* La sección de código asigna un valor y dirección de orden según el caso. */

                        $OrderedItem = "usucomision_resumen.usucomresumen_id";
                        $OrderType = $item->dir;
                        break;

                    case "Date":
                        /* asigna valores a $OrderedItem y $OrderType según el caso "Date". */

                        $OrderedItem = "usucomision_resumen.fecha_crea";
                        $OrderType = $item->dir;
                        break;


                    case "AmountBase":
                        /* asigna valores a variables basadas en la opción "AmountBase". */

                        $OrderedItem = "usucomision_resumen.valor_base";
                        $OrderType = $item->dir;
                        break;


                    case "Commissions":
                        /* asigna valores según la opción "Commissions" en una estructura de control. */

                        $OrderedItem = "usucomision_resumen.comision";
                        $OrderType = $item->dir;
                        break;


                    case "NetAmount":
                        /* asigna valores a variables basadas en una condición específica. */

                        $OrderedItem = "usucomision_resumen.comision";
                        $OrderType = $item->dir;
                        break;

                }

            }


            /* Establece valores predeterminados para variables si están vacías en PHP. */
            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* Establece un límite para filas y condiciones de filtrado en datos. */
            if ($MaxRows == "") {
                $MaxRows = 10000;
            }

            $rules = [];
//array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));

            if (!$ByAllowDate || $ByAllowDate == "false") {
//  array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
//  array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

            } else {
                /* agrega reglas sobre fechas a un array si no se cumple la condición. */

//    array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
//   array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

            }


            /* Agrega reglas a un array según condiciones específicas relacionadas con usuario y estado. */
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => "7073", "op" => "eq"));


            if ($State != "") {
                array_push($rules, array("field" => "usucomision_resumen.estado", "data" => "$State", "op" => "eq"));
            }



            /* Genera un filtro JSON para obtener un resumen de comisiones agrupado y ordenado. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" usucomision_resumen.*,usuario.login,usuario.nombre,clasificador.* ", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usucomresumen_id");
            $UsucomisionResumens = json_decode($UsucomisionResumens);



            /* Recopila datos de usuarios y genera un resumen de comisiones en un array. */
            $final = array();
            foreach ($UsucomisionResumens->data as $key => $value) {

                $array = [];

                $array["Id"] = $value->{"usucomision_resumen.usucomresumen_id"};
                $array["Date"] = $value->{"usucomision_resumen.fecha_crea"};

                $array["Description"] = $value->{"clasificador.descripcion"};
                $array["AmountBase"] = $value->{"usucomision_resumen.valor"};
                $array["Commissions"] = $value->{"usucomision_resumen.comision"};
                $array["Tax"] = 0;
                $array["NetAmount"] = $value->{"usucomision_resumen.valor"} - $array["Tax"];

                $array["State"] = "1";

                array_push($final, $array);
            }


            /* verifica si un valor es numérico y lo asigna a una variable. */
            $count = 0;

            if (is_numeric($UsucomisionResumens->count[0]->{'.count'})) {
                $count = $UsucomisionResumens->count[0]->{'.count'};
            }

            $response["status"] = true;

            /* crea una respuesta con datos estructurados en formato JSON. */
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => $count,
                "totalRecordsCount" => $count,

            );


            /* Se inicializa un array vacío para almacenar notificaciones en la variable $response. */
            $response["notification"] = array();

            break;

        /**
         * getMarketingSourcesReport
         *
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
        case "getMarketingSourcesReport":
            /* Genera un informe de fuentes de marketing con datos predefinidos en formato JSON. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(/*array("affiliateId" => "39705", "marketingSourceId" => "7614", "name" => "First Added", "site" => "http=>\/\/luckybet.com", "date" => "2018-04-13 16=>31=>01", "click" => "0", "impressions" => "0", "signUp" => "0", "CTR" => "0.00", "CR" => "0.00")*/
                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "1",

            );

            $response["notification"] = array();


            break;

        /**
         * getPlayersLinksStatistics
         *
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
        case "getPlayersLinksStatistics":
            /* Genera una respuesta JSON con estadísticas de enlaces de jugadores en el sistema. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(/*
array("affiliateId" => "39705", "name" => "luckybet", "linkId" => "18946", "createDate" => "2018-06-23 22=>57=>34", "marketingSourceName" => "First Added", "website" => "https=>\/\/luckybet.com\/", "marketingSourceId" => "7614", "clickLink" => "0", "signUp" => "0", "ratio" => "0.00", "playersCount" => "0", "deposits" => "0.00", "turnover" => "0.00", "profitness" => "0.00", "commissions" => "0.00", "grossRevenue" => "0.00", "netRevenue" => "0.00", "NDDACC" => "0.00", "NDACC" => "0")*/),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "1",

            );

            $response["notification"] = array();


            break;

        /**
         * getMediaStatisticsPro
         *
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
        case "getMediaStatisticsPro":
            /* genera una respuesta en formato JSON con estadísticas de medios solicitadas. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(/*
array("affiliateId" => "39705", "marketingSourceId" => "7614", "name" => "First Added", "site" => "http=>\/\/luckybet.com", "date" => "2018-04-13 16=>31=>01", "click" => "0", "impressions" => "0", "signUp" => "0", "CTR" => "0.00", "CR" => "0.00")*/
                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "1",

            );

            $response["notification"] = array();


            break;

        /**
         * getFavoriteBanners
         *
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
        case "getFavoriteBanners":



            /* Se crean objetos `Banner` y `UsuarioMandante` usando parámetros de sesión y configuración. */
            $Banner = new Banner();
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;


            /* asigna parámetros relacionados a la paginación de datos obtenidos. */
            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            if ($start != "") {
                $SkeepRows = $start;

            }


            /* establece `$MaxRows` si `$length` no está vacío y asigna `$columns`. */
            if ($length != "") {
                $MaxRows = $length;

            }

            $columns = $params->columns;

            /* asigna un valor a $order y establece $SkeepRows como cero si está vacío. */
            $order = $params->order;


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Se establece un valor predeterminado para $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Se define un filtro JSON para obtener banners favoritos de un usuario específico. */
            $rules = [];
            array_push($rules, array("field" => "usuario_banner.favorito", "data" => "S", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.*  ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);

            /* Se decodifica un JSON en PHP y se crea un array vacío. */
            $Banners = json_decode($Banners);

            $final = array();

            foreach ($Banners->data as $key => $value) {



                /* Código que asigna 1 a isfavorito si usuario_banner.favorito es 'S'. */
                $isfavorito = 0;
                $isActivate = 0;

                if ($value->{"usuario_banner.favorito"} == 'S') {
                    $isfavorito = 1;
                }

                /* asigna 1 a $isActivate si landing_id no es cero y crea un array. */
                if ($value->{"usuario_banner.landing_id"} != 0) {
                    $isActivate = 1;
                }

                $array = array();
                $array = array(
                    "activateBanner" => $isActivate,

                    "affiliateId" => 0,
                    "canDelete" => 0,
                    "canEdit" => 0,
                    "ctr" => "50",
                    "expireDate" => "",
                    "favorite" => $isfavorito,
                    "filename" => $value->{"banner.filename"},
                    "height" => $value->{"banner.height"},
                    "id" => $value->{"banner.banner_id"},
                    "isPublished" => "1",
                    "language" => $value->{"banner.idioma"},
                    "languages" => $value->{"banner.idioma"},
                    "mine" => 0,
                    "name" => $value->{"banner.banner_id"},
                    "oldFileName" => "",
                    "oldType" => "",
                    "params" => "",
                    "partnerId" => "288",
                    "path" => $urlApiAfiliados . $value->{"banner.filename"},
                    "preview" => 0,
                    "productId" => "1",
                    "productName" => "Sportsbook",
                    "size" => $value->{"banner.bsize"},
                    "status" => "OK",
                    "typeId" => "2",
                    "typeName" => "Image",
                    "updateDate" => $value->{"banner.fecha_modif"},
                    "uploadDate" => $value->{"banner.fecha_crea"},
                    "username" => $UsuarioMandante->getUsuarioMandante(),
                    "width" => $value->{"banner.width"},
                    "expirationDate" => $value->{"banner.fecha_expiracion"}, "state" => ($value->{"banner.estado"} == 'A') ? true : false


                );


                /* Agrega el contenido de `$array` al final del arreglo `$final`. */
                array_push($final, $array);

            }


            /* Código que prepara una respuesta estructurada con estado, HTML y resultados de registros. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );


            /* Se inicializa un array para almacenar notificaciones en la respuesta. */
            $response["notification"] = array();

            break;

        /**
         * createScriptForBanners
         *
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
        case "createScriptForBanners":


            /* Se inicializa un objeto UsuarioMandante y se recuperan parámetros relacionados con medios. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $mediaId = $params->mediaId;

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;

            /* asigna valores predeterminados si las variables están vacías. */
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* Establece un valor predeterminado y define reglas para filtrar datos de usuario. */
            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usuario_banner.banner_id", "data" => $mediaId, "op" => "eq"));

            /* Se crea un filtro JSON para reglas de usuario en un banner. */
            array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $Banner = new Banner();

            /* Se obtienen y decodifican banners asociados a un usuario específico. */
            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.*  ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);
            $usubanner_id = $Banners->data[0]->{"usuario_banner.usubanner_id"};


            $UsuarioBanner = new UsuarioBanner($usubanner_id);

            /* Configura la URL del mandante según el usuario y su país. */
            $Banner = new Banner($UsuarioBanner->getBannerId());

            $Mandante = new Mandante($Banner->mandante);



            /* Asigna una URL según el mandante y país del usuario en un código PHP. */
            try {
                $PaisMandante = new PaisMandante('', strtolower($UsuarioMandante->mandante), $UsuarioMandante->paisId);

                /* Validación para encontrar la URL en la columna base_url de base de datos*/
                if (empty($PaisMandante->baseUrl)) {
                    throw new Exception("No se encontró base_url para Mandante ID {$UsuarioMandante->mandante} y País ID {$UsuarioMandante->paisId}.", 300046);
                }
                $Mandante->baseUrl = $PaisMandante->baseUrl;
            } catch (Exception $e) {
                /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
            }


            /* genera un enlace de seguimiento basado en el id de destino del usuario. */
            $trackingLink = $Mandante->baseUrl . "/";

            switch ($UsuarioBanner->landingId) {
                case 0:
                    $trackingLink = $trackingLink;
                    break;
                case 1:
                    $trackingLink = $trackingLink . "apuestas";
                    break;
                case 2:
                    $trackingLink = $trackingLink . "registro";
                    break;
            }

            $arrayBanner = array(
                "bannerPath" => $urlApiAfiliados . "" . $Banner->getFilename(),
                "height" => $Banner->getHeight(),
                "origin" => "",
                "trackingLink" => $trackingLink . "?btag=" . encrypt($UsuarioBanner->getUsuarioId() . "_" . $UsuarioBanner->getUsubannerId(), $ENCRYPTION_KEY),
                "typeName" => "Image",
                "width" => $Banner->getWidth(),

            );


            $script = "<script>setTimeout(function(){(function(){var ba=(function(){var
        accountId=null,parentContainer,useSetContainer=true,q=[],lId='',mId='',u=\"\",i=0;if(typeof mediaToTrack==='undefined'){mediaToTrack=[];}
        var prefix='_ba';var isMsIe=function(){var ua=navigator.userAgent;return(ua!=null&&ua.indexOf(\"MSIE\")!=-1)?true:false;};var _setAccount=function(param){accountId=param;};var _setUrl=function(param){u=param;};var _mId=function(param){mId=param;put();};var _lId=function(param){lId=param;};var _setContainer=function(containerData){useSetContainer=false;parentContainer=document.querySelector('[data-ti=\"'+containerData+'\"]')||document.getElementById(containerData)||document.querySelector('[ban_id=\"'+containerData+'\"]');if(!parentContainer||parentContainer==null){throw{errorId:'unableToFindMediaContainer',msg:'Unable to find media container'}}};var _mData=function(params){mId=params[0]||0;lId=params[1]||0;put();};var put=function(){try{if(useSetContainer===true){if(mId&&mId!==''){_setContainer(accountId+'_'+mId);}}
            createMediaContainer();useSetContainer=true;}catch(e){throw e.msg;}};var keepInStorage=function(item,value,customPrefix){var stored=false;prefix=customPrefix||prefix;item=item||'_impr';if(typeof window.localStorage!==undefined){var impressionsStr=window.localStorage.getItem(prefix+item),impressions=JSON.parse(impressionsStr)||[];if(impressions.length>0){impressions.forEach(function(){if(impressions.indexOf(value)==-1){impressions.push(value);stored=true;}});}else{impressions.push(value);stored=true;}
        window.localStorage.setItem(prefix+item,JSON.stringify(impressions));}else{throw\"LocalStorage not supporting\";}
        return stored;};var trackClick=function(objectToTrack){if(!mId||mId=='')return;objectToTrack.addEventListener('click',function(e){e.preventDefault();var stored=keepInStorage('_clk',objectToTrack.getAttribute('data-ti'));var url=u;if(stored===true){ajax({url:url+'',data:{mId:mId,type:'click'},dataType:'json',headers:{\"Content-Type\":\"application/json\"}});}
        window.open(objectToTrack.getAttribute('href'),'_blank');},false);};var trackImpressions=function(objectToTrack){var mediaId=objectToTrack.getAttribute('data-mid')||null;if(!mediaId)return;var url=u;ajax({url:url+'',data:{mId:mediaId,type:'impr'},dataType:'json',headers:{\"Content-Type\":\"application/json\"}});};var viewPort=function(){return{isVisible:function(object){var scroll=(window.pageYOffset!==undefined)?window.pageYOffset:(document.documentElement||document.body.parentNode||document.body).scrollTop;return(object.offsetTop>=scroll&&object.offsetTop<=scroll+document.body.offsetHeight);}};};var trackAllMediaImpressions=function(){var vp=new viewPort();if(mediaToTrack.length>0){mediaToTrack.forEach(function(element){if(vp.isVisible(element)===true){var stored=keepInStorage('_imprs',element.getAttribute('data-ti'));if(stored===true){trackImpressions(element);}}});}};var getMedia=function(){var param={url:u+'api/banners/getMediaById',data:{mediaParams:{mId:mId,lId:lId}},dataType:'json',headers:{\"Content-Type\":\"application/json\"}};return ajax(param);};var createMediaContainer=function(){var mediaObj;mediaObj=document.createElement('img');mediaObj.src=\"" . $arrayBanner["bannerPath"] . "\";mediaObj.width=\"" . $arrayBanner["width"] . "\";mediaObj.height=\"" . $arrayBanner["height"] . "\";mediaObj=document.createElement('img');mediaObj.src=\"" . $arrayBanner["bannerPath"] . "\";var mediaTrackLink=document.createElement('a'),mediaTrackId=accountId+'_'+mId+'_'+lId;parentContainer.setAttribute('class','affMediaContainer_'+mediaTrackId);mediaTrackLink.setAttribute('data-ti',mediaTrackId);mediaTrackLink.setAttribute('data-mid',mId);mediaTrackLink.setAttribute('data-origin',u);mediaTrackLink.href=\"" . $arrayBanner["trackingLink"] . "\";mediaTrackLink.target='_blank';mediaTrackLink.appendChild(mediaObj);parentContainer.appendChild(mediaTrackLink);};var ajax=function(params){var client=new XMLHttpRequest(),method=params.method||'POST',url=params.url||'',promise=new Promise(function(resolve,reject){client.open(method,url);if(params.headers){for(var header in params.headers){client.setRequestHeader(header,params.headers[header]);}}
            client.withCredentials=true;client.send(JSON.stringify(params.data));client.onload=function(){if(this.status==200){if(params.dataType==='json'&&typeof this.response==='string'){resolve(JSON.parse(this.response));}else{resolve(this.response);}}else{if(params.dataType==='json'&&typeof this.response==='string'){resolve(JSON.parse(this.statusText));}else{resolve(this.statusText);}}};client.onerror=function(){if(params.dataType==='json'&&typeof this.response==='string'){resolve(JSON.parse(this.statusText));}else{resolve(this.statusText);}}});return promise;};var getBh5Script=function(scriptUrl){var bh5=document.createElement('script'),s=document.getElementsByTagName('script')[0];bh5.async=true;bh5.src=(scriptUrl||u)+'plugins/bh5/js/main.js';if(typeof bh5.src==null){throw\"Can't get bh5 script\";}
                !document.querySelector('script[src=\"'+bh5.src+'\"]')&&s.parentNode.insertBefore(bh5,s);};var init=function(){var q=[],fn,i;u=null;if(q.length>0){for(i in q){fn=q[i][0];delete q[i][0];if(typeof this[fn]==='function'&&this.hasOwnProperty(fn)){this[fn].apply(this,[q[i][1]]);var dir=document.querySelector('script[src$=\"banner.js\"]').getAttribute('src');var name=dir.split('/').pop();dir=dir.replace('/'+name,\"\");getBh5Script(dir+\"/../\");}}}
                window.onload=function(){trackAllMediaImpressions();};window.onscroll=function(){trackAllMediaImpressions();}};return{q:q,init:init,_mId:_mId,_lId:_lId,_mData:_mData,_setAccount:_setAccount,_setContainer:_setContainer,_setUrl:_setUrl,put:put,u:u};})();ba.init();window.ba=ba;})(window,document);ba._setAccount(" . $UsuarioMandante->getUsuarioMandante() . ");ba._mId(" . $usubanner_id . ");}, 3000);</script><div data-ti=\"" . $UsuarioMandante->getUsuarioMandante() . "_" . $usubanner_id . "\"></div>";


            /* inicializa una respuesta y asigna un script a la variable "result". */
            $final = array();

            $response["status"] = true;
            $response["html"] = "";
//$response["result"] = createScript($URL_AFFILIATES_API, $UsuarioMandante->getUsuarioMandante(), $usubanner_id);
            $response["result"] = $script;


            /* Se inicializa un arreglo vacío llamado "notification" dentro de la variable "$response". */
            $response["notification"] = array();

            break;

        /**
         * getAllLanguagesInSystem
         *
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
        case "getAllLanguagesInSystem":
            /* define un caso para obtener todos los idiomas en el sistema. */


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                array("locale" => "ar_IQ", "name" => "Arabic", "swarmKey" => "arb", "active" => "1"), array("locale" => "az", "name" => "Azerbaijani", "swarmKey" => "aze", "active" => "0"), array("locale" => "bg", "name" => "Bulgarian", "swarmKey" => "bgr", "active" => "0"), array("locale" => "cs_CZ", "name" => "Czech", "swarmKey" => "cze", "active" => "1"), array("locale" => "de_DE", "name" => "German", "swarmKey" => "ger", "active" => "1"), array("locale" => "el_GR", "name" => "Greek", "swarmKey" => "gre", "active" => "1"), array("locale" => "en_GB", "name" => "English", "swarmKey" => "eng", "active" => "1"), array("locale" => "es_ES", "name" => "Español", "swarmKey" => "spa", "active" => "1"), array("locale" => "et", "name" => "Estonian", "swarmKey" => "est", "active" => "0"), array("locale" => "fa_IR", "name" => "Persian", "swarmKey" => "far", "active" => "1"), array("locale" => "fr_FR", "name" => "Français", "swarmKey" => "fra", "active" => "1"), array("locale" => "he_IL", "name" => "Hebrew", "swarmKey" => "heb", "active" => "1"), array("locale" => "hy", "name" => "Armenian", "swarmKey" => "arm", "active" => "0"), array("locale" => "id", "name" => "Indonesian", "swarmKey" => "ind", "active" => "0"), array("locale" => "it_IT", "name" => "Italiano", "swarmKey" => "ita", "active" => "1"), array("locale" => "ja", "name" => "Japanese", "swarmKey" => "jpn", "active" => "0"), array("locale" => "ka_GE", "name" => "Georgian", "swarmKey" => "geo", "active" => "1"), array("locale" => "ko_KR", "name" => "한국어", "swarmKey" => "kor", "active" => "1"), array("locale" => "ku", "name" => "Kurdish", "swarmKey" => "kur", "active" => "0"), array("locale" => "lt", "name" => "Lithuanian", "swarmKey" => "lit", "active" => "0"), array("locale" => "lv", "name" => "Latvian", "swarmKey" => "lav", "active" => "0"), array("locale" => "ms", "name" => "Malay", "swarmKey" => "msa", "active" => "0"), array("locale" => "nl", "name" => "Dutch", "swarmKey" => "nld", "active" => "0"), array("locale" => "no", "name" => "Norway", "swarmKey" => "nor", "active" => "0"), array("locale" => "pl_PL", "name" => "Polish", "swarmKey" => "pol", "active" => "0"), array("locale" => "pt_PT", "name" => "Portuguese", "swarmKey" => "por", "active" => "1"), array("locale" => "ro", "name" => "Romanian", "swarmKey" => "ron", "active" => "0"), array("locale" => "ru_RU", "name" => "Русский", "swarmKey" => "rus", "active" => "1"), array("locale" => "sk", "name" => "Slovak", "swarmKey" => "slo", "active" => "0"), array("locale" => "sl", "name" => "Slovene", "swarmKey" => "slv", "active" => "0"), array("locale" => "sr", "name" => "Serbian", "swarmKey" => "srp", "active" => "0"), array("locale" => "sv_SE", "name" => "Swedish", "swarmKey" => null, "active" => "1"), array("locale" => "tr_TR", "name" => "Türkçe", "swarmKey" => "tur", "active" => "1"), array("locale" => "uk", "name" => "Ukrainian", "swarmKey" => "ukr", "active" => "0"), array("locale" => "zh_CN", "name" => "Chinese", "swarmKey" => "zho", "active" => "1")

            );
            $response["result"] = array(
                array("locale" => "es", "name" => "Español", "swarmKey" => "spa", "active" => "1"),
                array("locale" => "en", "name" => "Ingles", "swarmKey" => "eng", "active" => "1"),
                array("locale" => "pt", "name" => "Portuguese", "swarmKey" => "por", "active" => "1")
            );
            $response["notification"] = array();

            break;

        /**
         * get-social-links
         *
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
        case "get-social-links":
            /* Retorna enlaces de redes sociales con su estado y configuración en formato JSON. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                array("id" => "1", "key" => "facebook", "name" => "Facebook", "isShared" => "1", "url" => "", "active" => "0"), array("id" => "2", "key" => "twitter", "name" => "Twitter", "isShared" => "1", "url" => "", "active" => "0"), array("id" => "3", "key" => "google-plus", "name" => "Google Plus", "isShared" => "1", "url" => "", "active" => "0"), array("id" => "4", "key" => "vkontakte", "name" => "VK", "isShared" => "0", "url" => "", "active" => "0"), array("id" => "5", "key" => "instagram", "name" => "Instagram", "isShared" => "0", "url" => "", "active" => "0"), array("id" => "6", "key" => "youtube", "name" => "Youtube", "isShared" => "0", "url" => "", "active" => "0"), array("id" => "7", "key" => "odnoklassniki", "name" => "Odnoklassniki", "isShared" => "0", "url" => "", "active" => "0"), array("id" => "8", "key" => "linkedin", "name" => "LinkedIn", "isShared" => "1", "url" => "", "active" => "0"), array("id" => "9", "key" => "vimeo", "name" => "Vimeo", "isShared" => "0", "url" => "", "active" => "0"), array("id" => "10", "key" => "telegram", "name" => "Telegram", "isShared" => "1", "url" => "", "active" => "0")
            );

            $response["notification"] = array();

            break;

        /**
         * getWithdrawLis
         *
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
        case "getWithdrawLis":
            /* Código que inicializa respuesta para una solicitud "getWithdrawLis" con datos vacíos. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getCorrectionLogStatistics
         *
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
        case "getCorrectionLogStatistics":
            /* inicializa un array de respuesta para estadísticas de corrección. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getInboxMessages
         *
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
        case "getInboxMessages":


            /* Código para inicializar usuario y parámetros de fila en un sistema. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* Si no se establece $MaxRows, se asigna un valor predeterminado de 100. */
            if ($MaxRows == "") {
                $MaxRows = 100;
            }

            $mensajesEnviados = [];
            $mensajesRecibidos = [];



            /* Código genera una consulta JSON para obtener mensajes de usuarios filtrados. */
            $json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"}] ,"groupOp" : "AND"}';

            $UsuarioMensaje = new UsuarioMensaje();
            $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true);

            $usuarios = json_decode($usuarios);



            /* recorre usuarios, extrayendo datos y almacenándolos en un arreglo. */
            foreach ($usuarios->data as $key => $value) {

                $array = [];

                $array["text"] = $value->{"usuario_mensaje.body"};
                $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
                $array["open"] = false;
                $array["date"] = $value->{"usuario_mensaje.fecha_crea"};
                $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
                $array["subject"] = $value->{"usuario_mensaje.msubject"};
                $array["thread_id"] = $value->{"usuario_mensaje.parent_id"};

                array_push($mensajesRecibidos, $array);

            }



            /* Crea un arreglo de respuesta con estado, HTML y registros de mensajes recibidos. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $mensajesRecibidos,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );


            /* Se inicializa un arreglo vacío dentro de la clave "notification" en la respuesta. */
            $response["notification"] = array();

            break;

        /**
         * getSentBoxMessages
         *
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
        case "getSentBoxMessages":


            /* Se inicializan variables para un usuario y configuración de filas y elementos ordenados. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* establece un valor predeterminado de 100 para $MaxRows si está vacío. */
            if ($MaxRows == "") {
                $MaxRows = 100;
            }

            $mensajesEnviados = [];
            $mensajesRecibidos = [];



            /* Construye una consulta para obtener mensajes de usuario usando JSON como filtro. */
            $json2 = '{"rules" : [{"field" : "usuario_mensaje.usufrom_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"}] ,"groupOp" : "AND"}';

            $UsuarioMensaje = new UsuarioMensaje();
            $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true);

            $usuarios = json_decode($usuarios);



            /* Itera usuarios, creando un array con datos de mensajes enviados para cada usuario. */
            foreach ($usuarios->data as $key => $value) {

                $array = [];

                $array["text"] = $value->{"usuario_mensaje.body"};
                $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
                $array["date"] = 1514649066;
                $array["id"] = 123213213;
                $array["subject"] = $value->{"usuario_mensaje.msubject"};
                $array["thread_id"] = null;

                array_push($mensajesEnviados, $array);

            }


            /* configura una respuesta con estado, HTML y un arreglo de resultados. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $mensajesEnviados,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );


            /* Se inicializa una array llamado "notification" dentro de la variable "$response". */
            $response["notification"] = array();

            break;

        /**
         * getCommissionPlanLogs
         *
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
        case "getCommissionPlanLogs":
            /* Se inicializa una respuesta con datos vacíos para obtener registros de comisiones. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getPartnerBonusCostsPerProduct
         *
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
        case "getPartnerBonusCostsPerProduct":
            /* inicializa una respuesta para obtener costos de bonificación por producto. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(
                    array("percent" => 0)
                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getCostsLogs
         *
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
        case "getCostsLogs":
            /* prepara una respuesta vacía para obtener registros de costos. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getTaxesList
         *
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
        case "getTaxesList":
            /* inicializa una respuesta vacía para obtener una lista de impuestos. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getCarryOverLogByAffiliate
         *
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
        case "getCarryOverLogByAffiliate":
            /* establece una respuesta inicial para obtener un registro de afiliados. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getAffiliateCommissionPlansByProduct
         *
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
        case "getAffiliateCommissionPlansByProduct":
            /* devuelve un plan de comisiones para un producto específico. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                array(
                    "agentMax" => "35",
                    "agentMin" => "25",
                    "parentMax" => "0",
                    "parentMin" => "0",
                    "period" => "monthly",
                    "productId" => "1",
                    "productName" => "Sportsbook"
                )
            );

            $response["notification"] = array();

            break;

        /**
         * getAffiliatesFlatFee
         *
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
        case "getAffiliatesFlatFee":
            /* genera una respuesta estándar para la solicitud "getAffiliatesFlatFee". */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "amount" => "0.00"

            );

            $response["notification"] = array();

            break;

        case "GetMyAccount":



            /* crea objetos de usuario, registro y país utilizando sesión de usuario. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
            $Registro = new Registro('', $UsuarioMandante->getUsuarioMandante());

            $Pais = new Pais($Usuario->paisId);

//$PuntoVenta = new PuntoVenta("",$UsuarioMandante->getUsuarioMandante());


            $MaxRows = 1;

            /* Se definen reglas para filtrar datos de una tabla según condiciones específicas. */
            $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "usucomision_resumen.estado", "data" => "A", "op" => "eq"));


            /* Se crea un filtro JSON y se obtienen datos resumidos de comisiones. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsucomisionResumen = new UsucomisionResumen();
            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total ", "usucomision_resumen.usuario_id", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usuario_id");
            $UsucomisionResumens = json_decode($UsucomisionResumens);



            /* inicializa un arreglo de respuesta con estado verdadero y contenido HTML vacío. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "addInBazalt" => "OK",
                "address" => $Registro->getDireccion(),
                "affiliateId" => $Usuario->usuarioId,
//"BalanceAvailable" => $PuntoVenta->getCreditosBase(),
//"BalanceCPA" => $PuntoVenta->getCreditosBase(),
                "CommissionPending" => ($UsucomisionResumens->data[0]->{'.totalcomision'} > 0 ? $UsucomisionResumens->data[0]->{'.totalcomision'} : 0),
//"BalanceCurrent" => $PuntoVenta->getCreditosBase(),
                "affiliate_id" => null,
                "agentRole" => "0",
                "availableWallet" => "0",
                "birthday" => null,
                "cellPhone" => $Registro->getCelular(),
                "city" => "Bogota",
                "companyName" => null,
                "contactPhone" => null,
                "countryCode" => $Pais->iso,
                "country" => $Pais->paisNom,
                "email" => $Usuario->login,
                "gender" => "MALE",
                "hideCompleteNotification" => "0",
                "isAgent" => "0",
                "lastLogin" => $Usuario->fechaUlt,
                "lastName" => $Registro->getApellido1(),
                "left" => "38422",
                "level" => "1",
                "locale" => "es_ES",
                "login" => "NOK",
                "mainCurrency" => $Usuario->moneda,
                "name" => $Usuario->nombre,
                "noNegative" => null,
                "parentAffiliateId" => "0",
                "parentAffiliate_id" => "0",
                "partnerId" => "288",
                "planId" => "2",
                "planTypeId" => "0",
                "promoCode" => null,
                "registerDate" => $Registro->fechaValida,
                "registerIp" => $Usuario->dirIp,
                "reportCurrency" => null,
                "right" => "38423",
                "role" => "2",
                "secondLastName" => null,
                "secondName" => null,
                "session" => null,
                "sites" => null,
                "status" => "2",
                "terminalId" => null,
                "timezone" => "Asia/Yerevan",
                "tmpDateAvailable" => "2015-01-01 00:00:00",
                "username" => $Usuario->login,
                "verified" => "YES",
                "zipCode" => "50100"

            );


            /* Se inicializa un arreglo vacío para almacenar notificaciones en la respuesta. */
            $response["notification"] = array();

            break;

        /**
         * get-current-user-info
         *
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
        case "get-current-user-info":



            /* Se crean instancias de usuario y registro utilizando datos de sesión y país. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
            $Registro = new Registro('', $UsuarioMandante->getUsuarioMandante());

            $Pais = new Pais($Usuario->paisId);

//$PuntoVenta = new PuntoVenta("",$UsuarioMandante->getUsuarioMandante());


            $MaxRows = 1;

            /* Se define un conjunto de reglas de filtrado para usuarios y estado. */
            $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "usucomision_resumen.estado", "data" => "A", "op" => "eq"));


            /* crea un filtro JSON y obtiene un resumen de comisiones de usuarios. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsucomisionResumen = new UsucomisionResumen();
            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total ", "usucomision_resumen.usuario_id", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usuario_id");
            $UsucomisionResumens = json_decode($UsucomisionResumens);



            /* inicializa un estado exitoso y una cadena HTML vacía en la respuesta. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "addInBazalt" => "OK",
                "address" => $Registro->getDireccion(),
                "affiliateId" => $Usuario->usuarioId,
//"BalanceAvailable" => $PuntoVenta->getCreditosBase(),
//"BalanceCPA" => $PuntoVenta->getCreditosBase(),
                "CommissionPending" => ($UsucomisionResumens->data[0]->{'.totalcomision'} > 0 ? $UsucomisionResumens->data[0]->{'.totalcomision'} : 0),
//"BalanceCurrent" => $PuntoVenta->getCreditosBase(),
                "affiliate_id" => null,
                "agentRole" => "0",
                "availableWallet" => "0",
                "birthday" => null,
                "cellPhone" => $Registro->getCelular(),
                "city" => "Bogota",
                "companyName" => null,
                "contactPhone" => null,
                "countryCode" => $Pais->iso,
                "country" => $Pais->paisNom,
                "email" => $Usuario->login,
                "gender" => "MALE",
                "hideCompleteNotification" => "0",
                "isAgent" => "0",
                "lastLogin" => $Usuario->fechaUlt,
                "lastName" => $Registro->getApellido1(),
                "left" => "38422",
                "level" => "1",
                "locale" => "es_ES",
                "login" => "NOK",
                "mainCurrency" => $Usuario->moneda,
                "name" => $Usuario->nombre,
                "noNegative" => null,
                "parentAffiliateId" => "0",
                "parentAffiliate_id" => "0",
                "partnerId" => "288",
                "passHash" => "be23e4ece20a44250a651ee75488c3e09e76fa630999be0ff9f49b4abcc66f66",
                "passSalt" => "aOiusQFccFotXTXy6xyKNQ==",
                "planId" => "2",
                "planTypeId" => "0",
                "promoCode" => null,
                "registerDate" => $Registro->fechaValida,
                "registerIp" => $Usuario->dirIp,
                "reportCurrency" => null,
                "right" => "38423",
                "role" => "2",
                "secondLastName" => null,
                "secondName" => null,
                "session" => null,
                "sites" => null,
                "status" => "2",
                "terminalId" => null,
                "timezone" => "Asia/Yerevan",
                "tmpDateAvailable" => "2015-01-01 00:00:00",
                "username" => $Usuario->login,
                "verified" => "YES",
                "zipCode" => "50100"

            );


            /* Se inicializa un arreglo vacío para almacenar notificaciones en la respuesta. */
            $response["notification"] = array();

            break;

        /**
         * getPartnerAllAffiliates
         *
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
        case "getPartnerAllAffiliates":

            /* inicializa una respuesta positiva y un contenido HTML vacío. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(
                    "addInBazalt" => "OK",
                    "address" => "calle 6",
                    "affiliateId" => "39705",
                    "affiliate_id" => null,
                    "agentRole" => "0",
                    "availableWallet" => "0",
                    "birthday" => null,
                    "cellPhone" => "3012376249",
                    "city" => "Bogota",
                    "companyName" => null,
                    "contactPhone" => null,
                    "countryCode" => "CO",
                    "email" => "danielftg@hotmail.com",
                    "gender" => "MALE",
                    "hideCompleteNotification" => "0",
                    "isAgent" => "0",
                    "lastLogin" => $Usuario->fechaUlt,
                    "lastName" => "Tamayo",
                    "left" => "38422",
                    "level" => "1",
                    "locale" => "es_ES",
                    "login" => "NOK",
                    "mainCurrency" => "USD",
                    "name" => "Daniel",
                    "noNegative" => null,
                    "parentAffiliateId" => "0",
                    "parentAffiliate_id" => "0",
                    "partnerId" => "288",
                    "passHash" => "be23e4ece20a44250a651ee75488c3e09e76fa630999be0ff9f49b4abcc66f66",
                    "passSalt" => "aOiusQFccFotXTXy6xyKNQ==",
                    "planId" => "2",
                    "planTypeId" => "0",
                    "promoCode" => null,
                    "registerDate" => $Usuario->fechaCrea,
                    "registerIp" => "162.158.122.28",
                    "reportCurrency" => null,
                    "right" => "38423",
                    "role" => "2",
                    "secondLastName" => null,
                    "secondName" => null,
                    "session" => null,
                    "sites" => null,
                    "status" => "2",
                    "terminalId" => null,
                    "timezone" => "Asia/Yerevan",
                    "tmpDateAvailable" => "2015-01-01 00:00:00",
                    "username" => "danielftg@hotmail.com",
                    "verified" => "YES",
                    "zipCode" => "50100"
                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );


            /* Crea un array vacío para almacenar notificaciones en la variable $response. */
            $response["notification"] = array();

            break;

        /**
         * getProductReport
         *
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
        case "getProductReport":

            /* inicializa un arreglo de respuesta con estado verdadero y contenido HTML vacío. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(
                    array("GGR" => "0.00",
                        "RAKE" => "",
                        "activePlayersCount" => "0",
                        "bonus" => "0.00",
                        "bonusBet" => "0.00",
                        "commission" => "0.00",
                        "expences" => "0.00",
                        "netRevenue" => "0.00",
                        "productId" => "1",
                        "productName" => "Sportsbook",
                        "totalBets" => "0.00",
                        "totalWins" => "0.00",
                    ),
                    array(
                        "GGR" => "0.00",
                        "RAKE" => "",
                        "activePlayersCount" => "0",
                        "bonus" => "0.00",
                        "bonusBet" => "0.00",
                        "commission" => "0.00",
                        "expences" => "0.00",
                        "netRevenue" => "0.00",
                        "productId" => "2",
                        "productName" => "Live Games",
                        "totalBets" => "0.00",
                        "totalWins" => "0.00",
                    )
                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );


            /* Inicializa un array vacío para almacenar notificaciones en la respuesta. */
            $response["notification"] = array();
            break;

        /**
         * getWithdrawStatisticsForChart
         *
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
        case "getWithdrawStatisticsForChart":

            /* inicializa un arreglo con estado verdadero y un HTML vacío. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(
                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-01",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "01/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),
                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-02",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "02/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),
                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-03",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "03/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),
                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-04",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "04/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),
                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-05",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "05/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),

                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-06",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "06/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),

                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-07",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "07/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),

                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-08",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "08/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),

                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-09",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "05/09",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),

                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-10",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "10/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),

                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-11",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "11/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    )

                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );


            /* Crea un arreglo vacío llamado "notification" dentro del array "$response". */
            $response["notification"] = array();

            break;


        case "EditCommision":



            /* Asigna el valor de `$params` a la variable `$param`. */
            $param = $params;
//foreach ($params as $param) {
            if (true) {

                /* Asignación de variables extraídas de un objeto de parámetros relacionados a comisiones. */
                $Id = $param->Id;
                $Commissions = $param->Commissions;
                $ProductId = $Commissions->Id;

                $ProductName = $Commissions->ProductName;
                $ComissionLevel1 = $Commissions->ComissionLevel1;

                /* Asignación de niveles de comisión y un identificador a variables. */
                $ComissionLevel2 = $Commissions->ComissionLevel2;
                $ComissionLevel3 = $Commissions->ComissionLevel3;
                $ComissionLevel4 = $Commissions->ComissionLevel4;
                $ComissionLevelBetShop = $Commissions->ComissionLevelBetShop;


                $FromId = $Id;
                try {

                    /* Se crean instancias de la clase Concesionario y se configuran sus estados y relaciones. */
                    $ConcesionarioU = new Concesionario($FromId);
                    $ConcesionarioAntes = new Concesionario($FromId, $ProductId);
                    $Concesionario = new Concesionario();

                    $ConcesionarioAntes->setEstado('I');

                    $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());

                    /* Asignación de valores de un objeto a otro con comisiones y relaciones. */
                    $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
                    $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
                    $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
                    $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
                    $Concesionario->setPorcenhijo($ComissionLevelBetShop);
                    $Concesionario->setPorcenpadre1($ComissionLevel1);

                    /* Configura parámetros de concesionario, incluyendo comisiones, estado y producto interno. */
                    $Concesionario->setPorcenpadre2($ComissionLevel2);
                    $Concesionario->setPorcenpadre3($ComissionLevel3);
                    $Concesionario->setPorcenpadre4($ComissionLevel4);
                    $Concesionario->setProdinternoId($ProductId);
                    $Concesionario->setEstado('A');
                    $Concesionario->setMandante(0);

                    /* inicializa un concesionario y comienza una transacción en MySQL. */
                    $Concesionario->setUsucreaId(0);
                    $Concesionario->setUsumodifId(0);

                    $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();

                    $ConcesionarioMySqlDAO->getTransaction();

                    /* Actualiza e inserta datos de concesionario en la base de datos mediante transacciones. */
                    $ConcesionarioMySqlDAO->update($ConcesionarioAntes);
                    $ConcesionarioMySqlDAO->insert($Concesionario);

                    $ConcesionarioMySqlDAO->getTransaction()->commit();

                } catch (Exception $e) {
                    if ($e->getCode() == "48") {

                        /* Se crean dos objetos "Concesionario" y se copian sus IDs relacionados. */
                        $ConcesionarioU = new Concesionario($FromId);
                        $Concesionario = new Concesionario($FromId);

                        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
                        $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
                        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());

                        /* Se configuran distintos niveles de comisión y usuarios para un concesionario. */
                        $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
                        $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
                        $Concesionario->setPorcenhijo($ComissionLevelBetShop);
                        $Concesionario->setPorcenpadre1($ComissionLevel1);
                        $Concesionario->setPorcenpadre2($ComissionLevel2);
                        $Concesionario->setPorcenpadre3($ComissionLevel3);

                        /* Código que configura propiedades de un objeto 'Concesionario' con varios parámetros. */
                        $Concesionario->setPorcenpadre4($ComissionLevel4);
                        $Concesionario->setProdinternoId($ProductId);
                        $Concesionario->setMandante(0);
                        $Concesionario->setUsucreaId(0);
                        $Concesionario->setUsumodifId(0);
                        $Concesionario->setEstado('A');


                        /* Se crea un DAO para gestionar transacciones de concesionarios en MySQL. */
                        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();

                        $ConcesionarioMySqlDAO->getTransaction();
                        $ConcesionarioMySqlDAO->insert($Concesionario);

                        $ConcesionarioMySqlDAO->getTransaction()->commit();
                    } else {
                        /* lanza una excepción si se produce un error en el bloque anterior. */

                        throw $e;

                    }
                }

            }



            /* Código PHP que crea una respuesta exitosa en formato JSON. */
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "Operation has completed successfully";
            $response["ModelErrors"] = [];


            $response["Data"] = array();


            break;


        case "GetAgentComissionItems":


            /* Verifica si el usuario está logueado y responde con un mensaje de éxito. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "Operation has completed successfully";

            /* Se inicializa un arreglo para errores y se define una variable para un ID. */
            $response["ModelErrors"] = [];

            $FromId = $params->Id;

            $result_array = array();

            $campos = "";

            /* Definición de reglas de validación mediante un arreglo en PHP. */
            $cont = 0;

            $rules = [];

            array_push($rules, array("field" => "clasificador.tipo", "data" => "PCOM", "op" => "eq"));
            array_push($rules, array("field" => "clasificador.estado", "data" => "A", "op" => "eq"));

            /* Se crean condiciones para filtrar datos y se codifican en formato JSON. */
            array_push($rules, array("field" => "clasificador.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "concesionario.estado", "data" => "DISP", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $Concesionario = new Concesionario();

            /* Transforma datos de concesionarios en un formato JSON estructurado para su uso posterior. */
            $productos = $Concesionario->getConcesionariosProductoInternoCustom("clasificador.clasificador_id,clasificador.descripcion, concesionario.porcenpadre1,concesionario.porcenpadre2,concesionario.porcenpadre3,concesionario.porcenpadre4,concesionario.porcenhijo  ", "clasificador.clasificador_id", "asc", 0, 10000, $jsonfiltro, true, $FromId);
            $productos = json_decode($productos);


            $final = array();

            foreach ($productos->data as $producto) {

                $array = array(
                    "Id" => $producto->{"clasificador.clasificador_id"},
                    "ProductName" => $producto->{"clasificador.descripcion"},
                    "ComissionLevel1" => ($producto->{"concesionario.porcenpadre1"} == "") ? 0 : floatval($producto->{"concesionario.porcenpadre1"}),
                    "ComissionLevel2" => ($producto->{"concesionario.porcenpadre2"} == "") ? 0 : floatval($producto->{"concesionario.porcenpadre2"}),
                    "ComissionLevel3" => ($producto->{"concesionario.porcenpadre3"} == "") ? 0 : floatval($producto->{"concesionario.porcenpadre3"}),
                    "ComissionLevel4" => ($producto->{"concesionario.porcenpadre4"} == "") ? 0 : floatval($producto->{"concesionario.porcenpadre4"}),
                    "ComissionLevelBetShop" => ($producto->{"concesionario.porcenhijo"} == "") ? 0 : floatval($producto->{"concesionario.porcenhijo"})

                );
                array_push($final, $array);

            }



            /* crea una respuesta con estado, HTML y registros contables. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => oldCount($final),
                "totalRecordsCount" => oldCount($final)

            );

            break;


        /**
         * GetPlayersDashboards
         *
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
        case "GetPlayersDashboards":


            /* verifica si el usuario está logueado y crea un objeto según su ID. */
            if (!$_SESSION['logueado']) {
                exit();
            }

            if ($_SESSION["usuario2"] == 5) {
// $UsuarioMandante = new UsuarioMandante(5637);
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                /* Se crea una instancia de UsuarioMandante usando información de la sesión del usuario. */

                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }


            /* asigna valores de parámetros a variables en un sistema. */
            $BannerId = $params->BannerId;
            $LinkId = $params->LinkId;

            $linkId = $params->linkId;
            $agentId = $params->agentId;
            $registrationBeginDate = $params->registrationBeginDate;

            /* Asigna valores a $agentId y $linkId basados en condiciones específicas. */
            $registrationEndDate = $params->registrationEndDate;

            if ($agentId == 0) {
                $agentId = "";
            }

            if ($linkId == 0) {
                $linkId = "";
            }


            /* Se define un conjunto de reglas para validar información de usuarios. */
            $Usuario = new Usuario();


            $rules = [];
//array_push($rules, array("field" => "registro.usuario_id", "data" => "0", "op" => "ge"));
            array_push($rules, array("field" => "registro.afiliador_id", "data" => '0', "op" => "ne"));



            /* Agrega condiciones a un arreglo según valores de $linkId y $agentId. */
            if ($linkId != "") {
                array_push($rules, array("field" => "registro.link_id", "data" => $linkId, "op" => "eq"));

            }

            if ($agentId != "") {
                array_push($rules, array("field" => "registro.afiliador_id", "data" => $agentId, "op" => "eq"));

            } else {
                /* agrega reglas de validación para un perfil específico en la sesión. */

                if ($_SESSION["win_perfil"] == "AFILIADOR") {

                    array_push($rules, array("field" => "registro.afiliador_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                }
            }


            /* Agrega reglas de comparación para banner y link si sus IDs no están vacíos. */
            if ($BannerId != "") {
                array_push($rules, array("field" => "registro.banner_id", "data" => $BannerId, "op" => "eq"));

            }

            if ($LinkId != "") {
                array_push($rules, array("field" => "registro.link_id", "data" => $LinkId, "op" => "eq"));

            }



            /* Agrega reglas de filtrado por fechas de creación de usuario en un array. */
            if ($registrationBeginDate != "") {

                array_push($rules, array("field" => "usuario.fecha_crea", "data" => date('Y-m-d H:i:s',strtotime($registrationBeginDate. ' 00:00:00')), "op" => "ge"));

            }
            if ($registrationEndDate != "") {
                array_push($rules, array("field" => "usuario.fecha_crea", "data" => date('Y-m-d H:i:s',strtotime($registrationEndDate. ' 23:59:59')), "op" => "le"));

            }

// Si el usuario esta condicionado por País

            /* Condiciona reglas según el país y mandante del usuario en sesión. */
            if ($_SESSION['PaisCond'] == "S") {

                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

// Si el usuario esta condicionado por el mandante y no es de Global
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {
                /* verifica condiciones y agrega reglas a un arreglo según la sesión. */


                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }


            /* Crea un filtro en JSON y obtiene usuarios únicos según reglas específicas. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $usuarios = $Usuario->getUsuariosCustom(" COUNT( DISTINCT (usuario.usuario_id)) usuarios ", "usuario.usuario_id", "asc", 0, 100000, $json, true);

            $usuarios = json_decode($usuarios);



            /* inicializa un arreglo de jugadores y prepara una respuesta JSON. */
            $final = [];
            $final["Players"] = [];
            $final["Players"]["Total"] = $usuarios->data[0]->{".usuarios"};

            $response["status"] = true;
            $response["html"] = "";


            /* asigna un resultado final y crea una notificación vacía en un arreglo. */
            $response["result"] = $final;

            $response["notification"] = array();

            break;

        case "setAgentStateValidate":


            /* Verifica si el usuario está logueado antes de continuar con la acción. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $Id = $params->Id;
            $State = $params->Action;

            if ($Id != "" && ($State == "A" || $State == "I")) {


                /* Código que actualiza el estado de un usuario a "A" si es "N". */
                $Usuario = new Usuario($Id);

                if ($State == "A") {
                    if ($Usuario->estadoValida == "N") {
                        $Usuario->estadoValida = 'A';

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();
                    }

                } else {
                    /* valida y actualiza el estado de un usuario en la base de datos. */

                    if ($Usuario->estadoValida == "N") {
                        $Usuario->estadoValida = 'I';

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();

                    }

                }


                /* inicializa una respuesta en formato estructura para manejar estados y notificaciones. */
                $response["status"] = true;
                $response["html"] = "";
                $response["notification"] = array();

            } else {
                /* maneja un error asignando un mensaje y estado en la respuesta. */

                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;

        case "setAgentChangeExpiration":


            /* Verifica si la sesión está activa y obtiene parámetros si está logueado. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $Id = $params->Id;
            $MaximumCommission = $params->MaximumCommission;
            $TimeExpiration = $params->TimeExpiration;


            /* Asigna 0 a $TimeExpiration si está vacío o no definido. */
            if ($TimeExpiration == '') {
                $TimeExpiration = 0;
            }

            if ($Id != "") {


                /* Se inicializa un usuario y se actualiza su comisión máxima si está definida. */
                $Usuario = new Usuario($Id);
                $cambios = false;

                if ($MaximumCommission != '') {
                    $cambios = true;
                    $Usuario->maximaComision = $MaximumCommission;


                }


                /* verifica si hay un tiempo de expiración, asignándolo a un usuario. */
                if ($TimeExpiration != '') {
                    $cambios = true;

                    $Usuario->tiempoComision = $TimeExpiration;

                }


                /* Actualiza usuario en base de datos si existen cambios y confirma la transacción. */
                if ($cambios) {
                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $UsuarioMySqlDAO->update($Usuario);
                    $UsuarioMySqlDAO->getTransaction()->commit();

                }



                /* Se inicializan variables de respuesta para un sistema, incluyendo estado, HTML y notificaciones. */
                $response["status"] = true;
                $response["html"] = "";
                $response["notification"] = array();

            } else {
                /* maneja un error en la respuesta, indicando estado fallido y mensaje de alerta. */

                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;


        case "setAgentChangeExpiration":


            /* Verifica si el usuario está logueado; si no, termina la ejecución del script. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $Id = $params->Id;
            $MaximumCommission = $params->MaximumCommission;
            $TimeExpiration = $params->TimeExpiration;


            /* Asigna 0 a $TimeExpiration si su valor es una cadena vacía. */
            if ($TimeExpiration == '') {
                $TimeExpiration = 0;
            }

            if ($Id != "") {


                /* crea un objeto Usuario y actualiza su comisión máxima si es necesario. */
                $Usuario = new Usuario($Id);
                $cambios = false;

                if ($MaximumCommission != '') {
                    $cambios = true;
                    $Usuario->maximaComision = $MaximumCommission;


                }


                /* Asigna un tiempo de comisión al usuario si TimeExpiration no está vacío. */
                if ($TimeExpiration != '') {
                    $cambios = true;

                    $Usuario->tiempoComision = $TimeExpiration;

                }


                /* Actualiza un usuario en la base de datos si hay cambios y confirma la transacción. */
                if ($cambios) {
                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $UsuarioMySqlDAO->update($Usuario);
                    $UsuarioMySqlDAO->getTransaction()->commit();

                }



                /* inicializa una respuesta con estado verdadero, HTML vacío y notificaciones vacías. */
                $response["status"] = true;
                $response["html"] = "";
                $response["notification"] = array();

            } else {
                /* Código que maneja un error, estableciendo estado, mensaje y contenido en la respuesta. */

                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;

        case "setDeleteAgent":


            /* Verifica si el usuario está logueado antes de continuar con el procesamiento. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $Id = $params->Id;
            $State = $params->State;

            if ($Id != "") {


                /* marca un usuario como eliminado y actualiza la base de datos. */
                $Usuario = new Usuario($Id);
                if ($Usuario->eliminado == 'N') {
                    $Usuario->eliminado = 'S';

                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $UsuarioMySqlDAO->update($Usuario);
                    $UsuarioMySqlDAO->getTransaction()->commit();

                    $response["status"] = true;
                    $response["html"] = "";
                    $response["notification"] = array();

                } else {
                    /* establece una respuesta indicando que no se encontró al usuario. */

                    $response["status"] = false;
                    $response["html"] = "";
                    $response["AlertMessage"] = "Usuario no encontrado.";
                }


            } else {
                /* Código que maneja una respuesta de error en una solicitud. */

                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;

        case "changeStateAgent":


            /* Verifica si el usuario está logueado, y si no, termina el script. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $Id = $params->Id;
            $State = $params->State;

            if ($Id != "" && ($State == true || $State == false)) {


                /* activa un usuario inactivo y actualiza su estado en la base de datos. */
                $Usuario = new Usuario($Id);

                if ($State) {
                    if ($Usuario->estado == "I") {
                        $Usuario->estado = 'A';

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();
                    }

                } else {
                    /* desactiva un usuario si su estado es "A" y actualiza en la base. */

                    if ($Usuario->estado == "A") {
                        $Usuario->estado = 'I';

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();

                    }

                }


                /* inicializa una respuesta con estado verdadero, HTML vacío y notificaciones en un array. */
                $response["status"] = true;
                $response["html"] = "";
                $response["notification"] = array();

            } else {
                /* maneja un error, configurando un mensaje de alerta y respuesta vacía. */

                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;


        case "changeDragNegativeAgent":


            /* Verifica sesión activa y obtiene parámetros de la solicitud si el usuario está logueado. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $Id = $params->Id;
            $DragNegative = $params->DragNegative;

            if ($Id != "" && ($DragNegative == true || $DragNegative == false)) {


                /* Código que actualiza la propiedad "arrastraNegativo" de un usuario en base a condiciones. */
                $Usuario = new Usuario($Id);

                if ($DragNegative) {
                    if ($Usuario->arrastraNegativo == "0") {
                        $Usuario->arrastraNegativo = '1';

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();
                    }

                } else {
                    /* actualiza un estado negativo de un usuario en la base de datos. */

                    if ($Usuario->arrastraNegativo == "1") {
                        $Usuario->arrastraNegativo = '0';

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();

                    }

                }


                /* Inicializa un array de respuesta con estado, contenido HTML y notificaciones vacías. */
                $response["status"] = true;
                $response["html"] = "";
                $response["notification"] = array();

            } else {
                /* maneja un error, asignando un mensaje de alerta y un estado falso. */

                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;

        case "getAgentsTwoLevels":


            /* Valida sesión activa y permisos de usuario antes de ejecutar el código. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            if ($_SESSION["win_perfil"] != "ADMINAFILIADOS" && $_SESSION["win_perfil"] != "ADMIN" && $_SESSION["win_perfil"] != "SA" && $_SESSION["win_perfil"] != "ADMIN2" && $_SESSION["win_perfil"] != "CUSTOM" && $_SESSION["win_perfil"] != "RIESGO") {
                exit();
            }



            /* Se crea un objeto de `UsuarioPerfil` y `UsuarioMandante` si la condición se cumple. */
            $UsuarioPerfil = new UsuarioPerfil();

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                /* Crea un objeto UsuarioMandante usando la información del usuario en la sesión. */

                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }


            /* valida y asigna parámetros de entrada de tipo GET. */
            $Perfil_id = $_GET["roleId"];
            $Type = ($_GET["Type"] != 1 && $_GET["Type"] != 0) ? '' : $_GET["Type"];
            $Type = 1;
            $IsRegisterActivate = ($_GET["IsRegisterActivate"] != "A" && $_GET["IsRegisterActivate"] != "I" && $_GET["IsRegisterActivate"] != "N" && $_GET["IsRegisterActivate"] != "R") ? '' : $_GET["IsRegisterActivate"];
            $IsActivate = ($_GET["IsActivate"] != "A" && $_GET["IsActivate"] != "I") ? '' : $_GET["IsActivate"];

            $IsRegisterActivate = ($params->IsRegisterActivate != "A" && $params->IsRegisterActivate != "I" && $params->IsRegisterActivate != "N" && $params->IsRegisterActivate != "R") ? '' : $params->IsRegisterActivate;

            /* Se asignan valores de parámetros a variables y se valida el estado de activación. */
            $IsActivate = ($params->IsActivate != "A" && $params->IsActivate != "I") ? '' : $params->IsActivate;
            $Name = $params->Name;
            $Id = $params->Id;
            $Email = $params->Email;
            $Skype = $params->Skype;

            $tipoUsuario = "";


            /* asigna parámetros y omite filas basadas en el valor de inicio. */
            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            if ($start != "") {
                $SkeepRows = $start;
            } else {
                /* establece $SkeepRows a 0 si no se cumple la condición anterior. */

                $SkeepRows = 0;

            }


            /* asigna un valor a $MaxRows si $length no está vacío y obtiene columnas y orden. */
            if ($length != "") {
                $MaxRows = $length;
            }

            $columns = $params->columns;
            $order = $params->order;


            /* Se asignan las columnas y el orden de parámetros a variables en PHP. */
            $columns = $params->columns;
            $order = $params->order;

            $OrderedItem='usuario.usuario_id';
            $OrderedItem='asc';

            foreach ($order as $item) {

                switch ($columns[$item->column]->data) {
                    case "Id":
                        /* asigna un ID de usuario y tipo de orden según condiciones. */

                        $OrderedItem = "usuario.usuario_id";
                        $OrderType = $item->dir;
                        break;

                    case "Name":
                        /* Se ordena un ítem por el nombre del usuario según la dirección especificada. */

                        $OrderedItem = "usuario.nombre";
                        $OrderType = $item->dir;
                        break;

                    case "Email":
                        /* asigna valores a variables según el caso de "Email". */

                        $OrderedItem = "usuario.login";
                        $OrderType = $item->dir;
                        break;

                    case "Site":
                        /* asigna un valor a $OrderedItem y $OrderType dependiendo del caso "Site". */

                        $OrderedItem = "punto_venta.descripcion";
                        $OrderType = $item->dir;

                        break;
                    case "Skype":
                        /* Asigna valores a variables según el caso "Skype" en un flujo de programación. */

                        $OrderedItem = "punto_venta.skype";
                        $OrderType = $item->dir;

                        break;
                    case "Phone":
                        /* asigna valores a variables según el tipo de pedido "Phone". */

                        $OrderedItem = "punto_venta.telefono";
                        $OrderType = $item->dir;

                        break;
                    case "CreatedDate":
                        /* asigna valores a variables según el caso "CreatedDate". */

                        $OrderedItem = "usuario.fecha_crea";
                        $OrderType = $item->dir;

                        break;

                    case "LastLoginDateLabel":
                        /* Configura el orden de un item según la última fecha de login del usuario. */

                        $OrderedItem = "usuario.fecha_ult";
                        $OrderType = $item->dir;

                        break;

                    default:

                        /* Asignación de variables para ordenar elementos por ID de usuario y dirección especificada. */
                        $OrderedItem = "usuario.usuario_id";
                        $OrderType = $item->dir;

                        break;

                }

            }



            /* verifica si $MaxRows y $SkeepRows son números; de no serlo, detiene la ejecución. */
            $seguir = true;

            if ((!is_numeric($MaxRows) || !is_numeric($SkeepRows))) {
                $seguir = false;


            }


            /* Condicional que establece si el perfil de usuario es "PUNTOVENTA" o "CAJERO". */
            if ($_SESSION["win_perfil"] == "PUNTOVENTA" || $_SESSION["win_perfil"] == "CAJERO") {
                $seguir = false;

            }
            /*
                        if ($SkeepRows == "") {
                            $SkeepRows = 0;
                        }

                        if ($OrderedItem == "") {
                            $OrderedItem = 1;
                        }

                        if ($MaxRows == "") {
                            $MaxRows = 100000000;
                        }*/
            if ($seguir) {



                $mismenus = "0";

                $rules = [];
                array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));
                array_push($rules, array("field" => "usuario.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));


                if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

                    /* Agrega condiciones a un array de reglas basadas en el tipo y datos específicos. */
                    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

                    if ($Type == "1") {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

                    } else {
                        /* añade una regla a un arreglo si no se cumple una condición. */

                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
                    }


                    /* Añade reglas de validación basadas en los estados de registro y activación. */
                    if ($IsRegisterActivate != "") {
                        array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
                    }

                    if ($IsActivate != "") {
                        array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
                    }

                    if ($Name != "") {
                        array_push($rules, array("field" => "usuario.nombre", "data" => "$Name", "op" => "cn"));
                    }

                    if(!empty($Email)) array_push($rules, ['field' => 'usuario.login', 'data' => $Email, 'op' => 'cn']);
                    if(!empty($Skype)) array_push($rules, ['field' => 'usuario.skype', 'data' => $Skype, 'op' => 'cn']);



                    /* Se crea un filtro en formato JSON para consultar perfiles de usuarios. */
                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);

                    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.skype,usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario.usuario_id", "desc", $SkeepRows, $MaxRows, $json2, true);

                } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                    /* Condicional que verifica si el perfil de sesión es "CONCESIONARIO2". */

                    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

                    if ($Type == "1") {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

                    } else {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
                    }

                    if ($IsRegisterActivate != "") {
                        array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
                    }

                    if ($IsActivate != "") {
                        array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
                    }

                    if ($Name != "") {
                        array_push($rules, array("field" => "usuario.nombre", "data" => "$Name", "op" => "cn"));
                    }

                    if(!empty($Email)) array_push($rules, ['field' => 'usuario.login', 'data' => $Email, 'op' => 'cn']);
                    if(!empty($Skype)) array_push($rules, ['field' => 'usuario.skype', 'data' => $Skype, 'op' => 'cn']);

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);

                    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.skype,usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*   ", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json2, true);

                } elseif ($_SESSION["win_perfil"] == "ADMINAFILIADOS") {


                    /* Verifica si la variable $Id no está vacía antes de ejecutar el siguiente bloque. */
                    if(!empty($Id)) {
                        array_push($rules, ['field' => 'usuario.usuario_id', 'data' => $Id, 'op' => 'eq']);
                    }

                    if(!empty($Email)) array_push($rules, ['field' => 'usuario.login', 'data' => $Email, 'op' => 'cn']);
                    if(!empty($Skype)) array_push($rules, ['field' => 'usuario.skype', 'data' => $Skype, 'op' => 'cn']);


                    /* agrega reglas basadas en el valor de la variable $Type. */
                    if ($Type == "1") {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

                    } elseif (($Type == "1")) {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO','CONCESIONARIO2'", "op" => "in"));
                    } else {
                        /* agrega una regla para verificar si el perfil está en una lista específica. */

                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'AFILIADOR','CONCESIONARIO','CONCESIONARIO2'", "op" => "in"));

                    }


                    /* Añade condiciones a un array de reglas dependiendo de variables de activación. */
                    if ($IsRegisterActivate != "") {
                        array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
                    }

                    if ($IsActivate != "") {
                        array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
                    }


                    /* Agrega reglas de filtrado según condiciones de usuario y país usando arrays. */
                    if ($Name != "") {
                        array_push($rules, array("field" => "usuario.nombre", "data" => "$Name", "op" => "cn"));
                    }

                    if(!empty($Email)) array_push($rules, ['field' => 'usuario.login', 'data' => $Email, 'op' => 'cn']);
                    if(!empty($Skype)) array_push($rules, ['field' => 'usuario.skype', 'data' => $Skype, 'op' => 'cn']);
// Si el usuario esta condicionado por País
                    if ($_SESSION['PaisCond'] == "S") {
                        $Pais = new Pais($_SESSION['pais_id']);
                        $PaisMoneda = new PaisMoneda($_SESSION['pais_id']);

                        $moneda = $PaisMoneda->moneda;

                        array_push($rules, array("field" => "usuario.moneda", "data" => $moneda, "op" => "eq"));
                    }
// Si el usuario esta condicionado por el mandante y no es de Global

                    /* Agrega reglas de filtro según el estado de la sesión y mandante correspondiente. */
                    if ($_SESSION['Global'] == "N") {
                        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
                    } else {

                        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                        }

                    }


                    /* Construye un filtro JSON para obtener perfiles de usuario según ciertas reglas. */
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);

                    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.skype,usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);


                } else {


                    /* Verifica si la variable $Id no está vacía antes de ejecutar el código. */
                    if(!empty($Id)) {
                        array_push($rules, ['field' => 'usuario.usuario_id', 'data' => $Id, 'op' => 'eq']);
                    }

                    if(!empty($Email)) array_push($rules, ['field' => 'usuario.login', 'data' => $Email, 'op' => 'cn']);
                    if(!empty($Skype)) array_push($rules, ['field' => 'usuario.skype', 'data' => $Skype, 'op' => 'cn']);


                    /* Condicional para agregar reglas según el tipo de usuario en un array. */
                    if ($Type == "1") {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

                    } elseif (($Type == "1")) {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO','CONCESIONARIO2'", "op" => "in"));
                    } else {
                        /* Añade una regla que verifica si "perfil_id" pertenece a ciertos valores permitidos. */

                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'AFILIADOR','CONCESIONARIO','CONCESIONARIO2'", "op" => "in"));

                    }


                    /* Condiciona la adición de reglas basadas en el estado de activación. */
                    if ($IsRegisterActivate != "") {
                        array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
                    }

                    if ($IsActivate != "") {
                        array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
                    }


                    /* Condiciona reglas de validación basadas en nombre y país del usuario. */
                    if ($Name != "") {
                        array_push($rules, array("field" => "usuario.nombre", "data" => "$Name", "op" => "cn"));
                    }

                    if ($_SESSION['PaisCond'] == "S") {
                        $Pais = new Pais($_SESSION['pais_id']);
                        $PaisMoneda = new PaisMoneda($_SESSION['pais_id']);

                        $moneda = $PaisMoneda->moneda;

                        array_push($rules, array("field" => "usuario.moneda", "data" => $moneda, "op" => "eq"));
                    }

// Si el usuario esta condicionado por el mandante y no es de Global

                    /* añade reglas basadas en la sesión del usuario para filtrar datos. */
                    if ($_SESSION['Global'] == "N") {
                        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
                    } else {

                        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                        }

                    }


                    /* Se define un filtro JSON para consultar perfiles de usuarios con condiciones específicas. */
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);


                    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.maxima_comision,usuario.tiempo_comision,usuario.arrastra_negativo,usuario.skype,usuario.estado_valida,usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.moneda,usuario.fecha_ult,usuario.fecha_crea,punto_venta.*,departamento.*,ciudad.*,pais.*,usuario.skype  ", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json2, true);

                }

                $usuarios = json_decode($usuarios);
                $arrayf = [];

                $balanceAgent = 0;

                foreach ($usuarios->data as $key => $value) {

                    if ($isList != 1) {


                        /* crea un array con datos de usuario según condiciones de sesión. */
                        $array = [];
                        $array["id"] = $value->{"usuario.usuario_id"};
                        $array["Id"] = $value->{"usuario.usuario_id"};
                        $array["StateValidate"] = $value->{"usuario.estado_valida"};

                        if ($_SESSION["win_perfil2"] != "CONCESIONARIO" && $_SESSION["win_perfil2"] != "CONCESIONARIO2") {
                            $array["Action"] = $value->{"usuario.estado_valida"};

                        } else {
                            /* asigna un valor vacío a "Action" si no se cumple una condición previa. */

                            $array["Action"] = '';
                        }

                        /* Se asignan valores a un array desde un objeto, utilizando condiciones para un campo específico. */
                        $array["Site"] = $value->{"punto_venta.descripcion"};
                        $array["Skype"] = $value->{"usuario.skype"};

                        $array["State"] = $value->{"usuario.estado"};
                        $array["StateSwitch"] = ($value->{"usuario.estado"} == "A") ? true : false;

                        $array["MaximumCommission"] = $value->{"usuario.maxima_comision"};

                        /* asigna valores de un objeto a un array, modificando algunos datos. */
                        $array["TimeExpiration"] = $value->{"usuario.tiempo_comision"};
                        $array["DragNegative"] = ($value->{"usuario.arrastra_negativo"} == '0') ? false : true;

                        $array["UserName"] = str_replace("VAFILV", '', $value->{"usuario.login"});
                        $array["Name"] = $value->{"usuario.nombre"};
                        $array["Email"] = str_replace("VAFILV", "", $value->{"punto_venta.email"});

                        /* Se asignan valores a un array desde un objeto con propiedades específicas. */
                        $array["Phone"] = $value->{"punto_venta.telefono"};
                        $array["Address"] = $value->{"punto_venta.direccion"};
                        $array["CurrencyId"] = $value->{"usuario.moneda"};
                        $array["RegionName"] = $value->{"pais.pais_nom"};
                        $array["DepartmentName"] = $value->{"departamento.depto_nom"};
                        $array["CityName"] = $value->{"ciudad.ciudad_nom"};

                        /* Código que asigna valores de un objeto a un array asociativo en PHP. */
                        $array["SystemName"] = 22;
                        $array["IsSuspended"] = ($value->{"usuario.estado"} == 'A' ? false : true);
                        $array["AgentBalance"] = $value->{"punto_venta.creditos_base"};
                        $array["PlayerCount"] = 0;
                        $array["LastLoginDateLabel"] = $value->{"usuario.fecha_ult"};
                        $array["CreatedDate"] = $value->{"usuario.fecha_crea"};

                        /* Se crea un arreglo vacío en PHP para almacenar elementos bajo la clave "Children". */
                        $array["Children"] = array();

                        if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

                            /* Se definen reglas de filtro para consultas sobre concesionarios y usuarios. */
                            $rules2 = array();

                            array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                            array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
                            $filtro = array("rules" => $rules2, "groupOp" => "AND");

                            /* convierte datos de usuarios a formato JSON y los procesa en un array. */
                            $json2 = json_encode($filtro);

                            $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
                            $usuariosdetalle = json_decode($usuariosdetalle);


                            foreach ($usuariosdetalle->data as $key2 => $value2) {
                                $array2 = [];

                                $array2["Id"] = $value2->{"usuario.usuario_id"};

                                $array2["UserName"] = $value2->{"usuario.login"};
                                $array2["Name"] = $value2->{"usuario.nombre"};

                                $array2["SystemName"] = 22;
                                $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : truee);
                                $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};
                                $array2["PlayerCount"] = 0;
                                array_push($array["Children"], $array2);


                            }

                        } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                            /* Condicional para verificar si el perfil de usuario es "CONCESIONARIO2". */


                        } else {

                            /* Define un conjunto de reglas de filtrado para concesionarios y perfiles de usuario. */
                            $rules2 = array();

                            array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                            array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
                            $filtro = array("rules" => $rules2, "groupOp" => "AND");

                            /* Codifica un filtro a JSON y obtiene detalles de usuarios personalizados. */
                            $json2 = json_encode($filtro);

                            $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
                            $usuariosdetalle = json_decode($usuariosdetalle);

                            foreach ($usuariosdetalle->data as $key2 => $value2) {

                                /* crea un array asociativo con datos de un usuario específico. */
                                $array2 = [];

                                $array2["Id"] = $value2->{"usuario.usuario_id"};
                                $array2["UserName"] = $value2->{"usuario.login"};
                                $array2["Name"] = $value2->{"usuario.nombre"};

                                $array2["SystemName"] = 22;

                                /* asigna valores a un array basado en propiedades de un objeto. */
                                $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : truee);
                                $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};
                                $array2["PlayerCount"] = 0;
                                $array["LastLoginDateLabel"] = $value2->{"usuario.fecha_ult"};
                                $array2["Children"] = array();

                                if (true) {

                                    /* Se crean reglas de filtro para validación de concesionarios y usuarios. */
                                    $rules3 = array();

                                    array_push($rules3, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                                    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                                    array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
                                    $filtro = array("rules" => $rules3, "groupOp" => "AND");

                                    /* procesa datos de usuarios, creando un arreglo con información específica. */
                                    $json3 = json_encode($filtro);

                                    $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json3, true);
                                    $usuariosdetalle = json_decode($usuariosdetalle);


                                    foreach ($usuariosdetalle->data as $key3 => $value3) {
                                        $array2 = [];

                                        $array2["Id"] = $value3->{"usuario.usuario_id"};
                                        $array2["UserName"] = $value3->{"usuario.login"};
                                        $array2["Name"] = $value3->{"usuario.nombre"};
                                        $array2["SystemName"] = 22;
                                        $array2["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : truee);
                                        $array2["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                                        $array2["PlayerCount"] = 0;
                                        array_push($array2["Children"], $array3);


                                    }

                                }


                                /* Agrega el contenido de `$array2` al final del elemento "Children" de `$array`. */
                                array_push($array["Children"], $array2);


                            }


                        }
                    } else {
                        /* crea un arreglo con ID y nombre de usuario desde un objeto. */

                        $array = [];
                        $array["id"] = $value->{"usuario.usuario_id"};
                        $array["Name"] = $value->{"usuario.nombre"};

                    }


                    /* Se añaden elementos a un array y se actualiza el balance de un agente. */
                    array_push($arrayf, $array);

                    $balanceAgent = $balanceAgent + $value->{"punto_venta.creditos_base"};
                }


                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = array(
                    "records" => $arrayf,
                    "titles" => "",
                    "total" => $usuarios->count[0]->{".count"},
                    "totalRecordsCount" => $usuarios->count[0]->{".count"}

                );

                $response["notification"] = array();


                /*
                            $response["HasError"] = false;
                            $response["AlertType"] = "success";
                            $response["AlertMessage"] = "";
                            $response["ModelErrors"] = [];

                            $response["Data"] = array(
                                "DownStreamChildrenCount"=>100,
                                "DownStreamChildrenBalanceSum"=>1000,
                                "DownStreamPlayerCount"=>100,
                                "DownStreamPlayerBalanceSum"=>100,
                                "Children"=>array(
                                    array(
                                        "UserName"=>"test",
                                        "AgentId"=>1,
                                        "SystemName"=>1,
                                        "PlayerCount"=>100,
                                        "AgentBalance"=>1000,
                                        "Children"=>array(
                                            array(
                                                "UserName"=>"test2",
                                                "SystemName"=>1,

                                                "PlayerCount"=>100,
                                                "AgentBalance"=>1000,

                                            )
                                        )
                                    )
                                )
                            );
                */
            }
            break;


        /**
         * getPlayers
         *
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
        case "getPlayers":


            /* Verifica si el usuario está logueado y crea un objeto si cumple condición. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                /* Se crea una instancia de UsuarioMandante con el usuario de la sesión actual. */

                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }



            /* Se crea un objeto Usuario y se inicializan variables desde los parámetros dados. */
            $Usuario = new Usuario();


            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            /* Define fechas de inicio y fin de registro, y asigna un identificador de usuario. */
            $registrationBeginDate = $params->registrationBeginDate;
            $registrationEndDate = $params->registrationEndDate;



            $OrderedItem = "usuario.usuario_id";

            /* configura parámetros para paginación y ordenamiento en consultas de datos. */
            $OrderType = "desc";

            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            if ($start != "") {
                $SkeepRows = $start;

            }


            /* verifica si $length no está vacío y lo asigna a $MaxRows. */
            if ($length != "") {
                $MaxRows = $length;

            }

            $columns = $params->columns;

            /* Asigna el valor de la propiedad "order" de "$params" a la variable "$order". */
            $order = $params->order;

            foreach ($order as $item) {

                switch ($columns[$item->column]->data) {
                    case "playerId":
                        /* Asignación de variables según la dirección indicada para el идентификатор jugador. */

                        $OrderedItem = "usuario.usuario_id";
                        $OrderType = $item->dir;
                        break;

                    case "registrationDate":
                        /* Se establece un orden para la fecha de creación del usuario. */

                        $OrderedItem = "usuario.fecha_crea";
                        $OrderType = $item->dir;
                        break;

                    case "country":
                        /* Ordena elementos por país usando la dirección especificada en la variable $item. */

                        $OrderedItem = "usuario.pais_id";
                        $OrderType = $item->dir;
                        break;

                    case "Amount":
                        /* asigna valores a variables basadas en una condición específica. */

                        $OrderedItem = "it_ticket_enc_info1.valor";
                        $OrderType = $item->dir;

                        break;
                }

            }


            /* asigna valores de parámetros a variables en un script. */
            $LinkId = $params->LinkId;
            $BannerId = $params->BannerId;

            $linkId = $params->linkId;
            $agentId = $params->agentId;

            $playerId = $params->playerId;


            /* reemplaza '0' en variables por cadenas vacías. */
            if ($agentId == '0') {
                $agentId = "";
            }

            if ($linkId == "0") {
                $linkId = "";
            }

            /* asigna valores a variables según condiciones de identificación y datos de filas. */
            if ($playerId == '0') {
                $linkId = "";
            }

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Se crean reglas para validar condiciones en registros, utilizando arrays en PHP. */
            $rules = [];
//array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));
            array_push($rules, array("field" => "registro.afiliador_id", "data" => '0', "op" => "ne"));

            if ($linkId != "") {
                array_push($rules, array("field" => "registro.link_id", "data" => $linkId, "op" => "eq"));

            }


            /* agrega reglas de filtrado basadas en el ID del agente o perfil. */
            if ($agentId != "") {
                array_push($rules, array("field" => "registro.afiliador_id", "data" => $agentId, "op" => "eq"));

            } else {

                if ($_SESSION["win_perfil"] == "AFILIADOR") {

                    array_push($rules, array("field" => "registro.afiliador_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                }
            }



            /* Agrega condiciones a un arreglo basado en la existencia de variables LinkId y playerId. */
            if ($LinkId != "") {
                array_push($rules, array("field" => "registro.link_id", "data" => $LinkId, "op" => "eq"));

            }
            if ($playerId != "") {
                array_push($rules, array("field" => "usuario.usuario_id", "data" => $playerId, "op" => "eq"));

            }


            /* Agrega reglas de filtro para un banner y fecha de registro en un array. */
            if ($BannerId != "") {
                array_push($rules, array("field" => "registro.banner_id", "data" => $BannerId, "op" => "eq"));

            }
            if ($registrationBeginDate != "") {

                array_push($rules, array("field" => "usuario.fecha_crea", "data" => date('Y-m-d H:i:s',strtotime($registrationBeginDate. ' 00:00:00')), "op" => "ge"));

            }

            /* Agrega reglas de validación sobre la fecha de registro y país del usuario. */
            if ($registrationEndDate != "") {
                array_push($rules, array("field" => "usuario.fecha_crea", "data" => date('Y-m-d H:i:s',strtotime($registrationEndDate. ' 23:59:59')), "op" => "le"));

            }



// Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {

                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

// Si el usuario esta condicionado por el mandante y no es de Global

            /* agrega reglas a un arreglo basado en condiciones de sesión. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }


            /* Se crea un filtro JSON y se obtiene usuarios personalizados de la base de datos. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),d.ciudad_nom ciudad, usuario.fecha_ult,registro.banner_id,registro.link_id,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.ciudad_id,registro.estado_valida,registro.nombre1,registro.nombre2,registro.apellido1,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,c.*,g.*,usuario.tiempo_limitedeposito,usuario.tiempo_autoexclusion,usuario.cambios_aprobacion,registro.creditos,registro.creditos_base ", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true);

            $usuarios = json_decode($usuarios);


            /* transforma datos de usuarios en un nuevo formato estructurado. */
            $usuariosFinal = [];

            foreach ($usuarios->data as $key => $value) {

                $array = [];

                $array["playerId"] = $value->{"usuario.usuario_id"};
                $array["city"] = $value->{"d.ciudad"};
                $array["country"] = $value->{"usuario.pais_id"};
                $array["device"] = '';
                $array["registrationDate"] = $value->{"usuario.fecha_crea"};
                $array["bannerId"] = $value->{"registro.banner_id"};
                $array["linkId"] = $value->{"registro.link_id"};


                array_push($usuariosFinal, $array);

            }


            /* crea una respuesta estructurada con datos de usuarios y su conteo. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $usuariosFinal,
                "titles" => "",
                "total" => $usuarios->count[0]->{".count"},
                "totalRecordsCount" => $usuarios->count[0]->{".count"}

            );


            /* Se inicializa un array vacío llamado "notification" en la variable "$response". */
            $response["notification"] = array();
            break;

        /**
         * getPlayersStatisticsPro
         *
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
        case "getPlayersStatisticsPro":


            /* verifica sesión activa y crea instancias de usuario según el estado. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);


            $Usuario = new Usuario();


            /* recibe datos JSON y extrae 'MaxRows' y 'OrderedItem'. */
            $params = file_get_contents('php://input');
            $params = json_decode($params);


            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;

            /* establece valores predeterminados para variables según condiciones. */
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* inicializa `$MaxRows` y define reglas para la validación de datos. */
            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));



            /* Se define un filtro de reglas para obtener usuarios personalizados mediante una consulta SQL. */
            array_push($rules, array("field" => "registro.afiliador_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),d.ciudad_nom ciudad, usuario.fecha_ult,registro.banner_id,registro.link_id,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.ciudad_id,registro.estado_valida,registro.nombre1,registro.nombre2,registro.apellido1,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,c.*,g.*,usuario.tiempo_limitedeposito,usuario.tiempo_autoexclusion,usuario.cambios_aprobacion,registro.creditos,registro.creditos_base ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);


            /* Se convierte un JSON de usuarios a un arreglo estructurado con datos específicos. */
            $usuarios = json_decode($usuarios);

            $usuariosFinal = [];

            foreach ($usuarios->data as $key => $value) {

                $array = [];

                $array["playerId"] = $value->{"usuario.usuario_id"};
                $array["city"] = $value->{"d.ciudad"};
                $array["country"] = $value->{"usuario.pais_id"};
                $array["device"] = '';
                $array["registrationDate"] = $value->{"usuario.fecha_crea"};
                $array["bannerId"] = $value->{"registro.banner_id"};
                $array["linkId"] = $value->{"registro.link_id"};


                array_push($usuariosFinal, $array);

            }


            /* estructura una respuesta en formato JSON con información sobre usuarios. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $usuariosFinal,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );


            /* Inicializa un arreglo vacío para almacenar notificaciones en la variable $response. */
            $response["notification"] = array();
            break;

        /**
         * getDeletedPlayerLog
         *
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
        case "getDeletedPlayerLog":
            /* prepara una respuesta vacía para un log de jugadores eliminados. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "0",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        case "getAgentsLinks":


            /* verifica la sesión y crea un objeto si el usuario es válido. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                /* Crea una instancia de UsuarioMandante si no se cumple una condición especificada. */

                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }

            /* asigna valores a variables y maneja la omisión de filas. */
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $AgentId = $params->AgentId;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* asigna valores predeterminados a variables si están vacías. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* crea reglas basadas en un ID de agente no vacío. */
            $rules = [];

            if ($AgentId != "") {
                array_push($rules, array("field" => "usuario_link.usuario_id", "data" => "$AgentId", "op" => "eq"));

            }


            /* Se crea un filtro JSON y se obtienen enlaces de usuario personalizados. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsuarioLink = new UsuarioLink();

            $UsuarioLink = $UsuarioLink->getUsuarioLinksCustom(" usuario_link.*,usuario_mandante.* ", "usuario_link.nombre", "asc", $SkeepRows, $MaxRows, $json, true);

            /* Convierte un objeto JSON en un array asociativo con ID y nombre de usuarios. */
            $UsuarioLink = json_decode($UsuarioLink);

            $final = array();


            foreach ($UsuarioLink->data as $key => $value) {

                $array = array(
                    "id" => $value->{"usuario_link.usulink_id"},
                    "name" => $value->{"usuario_link.nombre"}
                );

                array_push($final, $array);

            }


            /* crea un arreglo de respuesta con datos y conteos específicos. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );



            /* Inicializa un array vacío llamado "notification" en la variable $response. */
            $response["notification"] = array();

            break;


        /**
         * getLinks
         *
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
        case "getLinks":


            /* verifica si el usuario está logueado y crea un objeto si es un usuario específico. */
            if (!$_SESSION['logueado']) {
                exit();
            }

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                /* Crea un objeto UsuarioMandante utilizando la información del usuario almacenado en sesión. */

                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }

            /* asigna valores de parámetros a variables para su uso posterior. */
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $BannerId = $params->BannerId;
            $LinkId = $params->LinkId;

            /* asigna valores de parámetros a variables en un contexto de programación. */
            $name = $params->name;

            $linkId = $params->linkId;

            $createBeginDate = $params->createBeginDate;
            $createEndDate = $params->createEndDate;


            /* Asigna el ID del agente; si es cero, lo convierte en una cadena vacía. */
            $id = $params->id;
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }


            /* asigna una cadena vacía a variables si su valor es cero. */
            if ($linkId == 0) {
                $linkId = "";
            }

            if ($id == 0) {
                $id = "";
            }



            /* asigna valores de columnas y orden a variables desde un objeto $params. */
            $columns = $params->columns;
            $order = $params->order;

            foreach ($order as $item) {

                switch ($columns[$item->column]->data) {
                    case "id":
                        /* Asigna un valor basado en la clave "id" y define el tipo de orden. */

                        $OrderedItem = "usuario_link.usulink_id";
                        $OrderType = $item->dir;
                        break;

                    case "name":
                        /* Código que ordena items basado en el nombre del usuario y dirección especificada. */

                        $OrderedItem = "usuario_link.nombre";
                        $OrderType = $item->dir;
                        break;

                    case "creator":
                        /* asigna valores a variables según un caso específico en una estructura de control. */

                        $OrderedItem = "usuario_link.usuario_id";
                        $OrderType = $item->dir;
                        break;

                    case "createDate":
                        /* Asigna la fecha de creación a $OrderedItem según el tipo de orden. */

                        $OrderedItem = "usuario_link.fecha_crea";
                        $OrderType = $item->dir;

                        break;

                }

            }

            /* Asignación de parámetros y condicional para definir filas a omitir en un proceso. */
            $length = $params->length;
            $start = $params->start;

            if ($start != "") {
                $SkeepRows = $start;

            }


            /* ajusta el número máximo de filas y establece filas a omitir. */
            if ($length != "") {
                $MaxRows = $length;

            }


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Se crea un arreglo de reglas basadas en la existencia de $linkId. */
            $rules = [];

            if ($linkId != "") {
                array_push($rules, array("field" => "usuario_link.usulink_id", "data" => $linkId, "op" => "eq"));

            }

            /* Agrega reglas de filtrado según condiciones de $id y $name en un array. */
            if ($id != "") {
                array_push($rules, array("field" => "usuario_link.usulink_id", "data" => $id, "op" => "eq"));

            }
            if ($name != "") {
                array_push($rules, array("field" => "usuario_link.nombre", "data" => $name, "op" => "cn"));

            }



            /* Condiciona fechas para agregar reglas de comparación a un arreglo en PHP. */
            if ($createEndDate != "") {
                array_push($rules, array("field" => "usuario_link.fecha_crea", "data" => $createEndDate . ' 23:59:59', "op" => "le"));


            }

            if ($createBeginDate != "") {
                array_push($rules, array("field" => "usuario_link.fecha_crea", "data" => $createBeginDate . ' 00:00:00', "op" => "ge"));


            }


            /* Se añade una regla al array si $agentId no está vacío. */
            if ($agentId != "") {
                array_push($rules, array("field" => "usuario_link.usuario_id", "data" => $agentId, "op" => "eq"));

            } else {

                /* gestiona reglas de acceso basadas en el perfil y condiciones del usuario. */
                if($_SESSION['win_perfil'] =='AFILIADOR' || $_SESSION['win_perfil'] =='AFILIADO' || $_SESSION['win_perfil'] =='CONCESIONARIO' || $_SESSION['win_perfil'] =='CONCESIONARIO2'){
                    array_push($rules, array("field" => "usuario_link.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                }else{
// Si el usuario esta condicionado por País
                    if ($_SESSION['PaisCond'] == "S") {
                        array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                    }
// Si el usuario esta condicionado por el mandante y no es de Global
                    if ($_SESSION['Global'] == "N") {
                        array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
                    } else {

                        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                            array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                        }

                    }
                }

            }


            /* Se construye un filtro JSON para obtener enlaces de usuario según reglas específicas. */
            array_push($rules, array("field" => "usuario_link.estado", "data" => 'A', "op" => "eq"));
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);
            $UsuarioLink = new UsuarioLink();

            $UsuarioLink = $UsuarioLink->getUsuarioLinksCustom(" usuario_link.*,usuario_mandante.* ", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true);

            /* decodifica un JSON y inicializa un arreglo vacío. */
            $UsuarioLink = json_decode($UsuarioLink);

            $final = array();


            foreach ($UsuarioLink->data as $key => $value) {

                /* establece una URL base según condiciones del usuario y mandante. */
                $trackingLink = "";
                $Mandante = new Mandante($value->{"usuario_mandante.mandante"});



                /* Asigna una URL según el mandante y país del usuario en un código PHP. */
                try {
                    $PaisMandante = new PaisMandante('', strtolower($UsuarioMandante->mandante), $UsuarioMandante->paisId);

                    /* Validación para encontrar la URL en la columna base_url de base de datos*/
                    if (empty($PaisMandante->baseUrl)) {
                        throw new Exception("No se encontró base_url para Mandante ID {$UsuarioMandante->mandante} y País ID {$UsuarioMandante->paisId}.", 300046);
                    }
                    $Mandante->baseUrl = $PaisMandante->baseUrl;
                } catch (Exception $e) {
                    /* Manejo de excepciones en PHP, captura errores sin realizar acciones adicionales. */
                }

                /* Se construye un enlace de seguimiento basado en condiciones del objeto $Mandante. */
                if ($Mandante->mandante != '0') {
                    $trackingLink = $Mandante->baseUrl . "/";
                }


                if ($Mandante->mandante == '14') {

                    switch ($value->{"usuario_link.link"}) {
                        case 0:
                            $trackingLink = $trackingLink;
                            break;
                        case 1:
                            $trackingLink = $trackingLink . "apuestas";
                            break;
                        case 2:
                            $trackingLink = $trackingLink . "landing/registro-corto-loto";
                            break;
                    }

                }elseif ($Mandante->mandante == '8'){
                    /* Condicional que ajusta la variable $trackingLink según el valor de $usuario_link. */


                    switch ($value->{"usuario_link.link"}) {
                        case 0:
                            $trackingLink = $trackingLink;
                            break;
                        case 1:
                            $trackingLink = $trackingLink . "apuestas";
                            break;
                        case 2:
                            $trackingLink = $trackingLink . "landing/registro-corto";
                            break;
                    }
                }elseif ($Mandante->mandante == '13'){
                    /* asigna un valor a $trackingLink según el caso del usuario. */


                    switch ($value->{"usuario_link.link"}) {
                        case 0:
                            $trackingLink = $trackingLink;
                            break;
                        case 1:
                            $trackingLink = $trackingLink . "apuestas";
                            break;
                        case 2:
                            $trackingLink = $trackingLink . "landing/registro-corto";
                            break;
                    }
                }elseif ($Mandante->mandante == '17'){
                    /* Condicional que modifica $trackingLink según el estado de "usuario_link.link". */


                    switch ($value->{"usuario_link.link"}) {
                        case 0:
                            $trackingLink = $trackingLink;
                            break;
                        case 1:
                            $trackingLink = $trackingLink . "apuestas";
                            break;
                        case 2:
                            $trackingLink = $trackingLink . "landing/registro-corto";
                            break;
                    }
                }elseif ($Mandante->mandante == '18' && $UsuarioMandante->paisId == '173'){
                    /* Condiciona la URL de seguimiento según el mandante y el país del usuario. */

                    $trackingLink = $Mandante->baseUrl;
                    switch ($value->{"usuario_link.link"}) {
                        case 0:
                            $trackingLink = $trackingLink;
                            break;
                        case 1:
                            $trackingLink = $trackingLink . "apuestas";
                            break;
                        case 2:
                            $trackingLink = $trackingLink . "registro";
                            break;
                    }
                }else{
                    /* ajusta una variable de enlace según el valor del usuario. */


                    switch ($value->{"usuario_link.link"}) {
                        case 0:
                            $trackingLink = $trackingLink;
                            break;
                        case 1:
                            $trackingLink = $trackingLink . "apuestas";
                            break;
                        case 2:
                            $trackingLink = $trackingLink . "registro";
                            break;
                    }
                }


                /* Asigna valores predeterminados a variables si no existen en el objeto. */
                $utmSource = $value->{'usuario_link.utm_source'} ?: 'Afiliados';
                $utmMedium = $value->{'usuario_link.utm_medium'} ?: 'Link';
                $utmCampaing = $value->{'usuario_link.utm_campaing'} ?: $UsuarioMandante->nombres . '_' . $value->{"usuario_link.nombre"};

                if($utmSource ==""){
                    $utmSource="Afiliados";
                }


                /* Asigna valores predeterminados a $utmMedium y $utmCampaing si están vacíos. */
                if($utmMedium ==""){
                    $utmMedium="Link";
                }
                if($utmCampaing ==""){
                    $utmCampaing= $UsuarioMandante->nombres . "_" . $value->{"usuario_link.nombre"};
                }

                /* Crea un array con información de usuario y un enlace de seguimiento personalizado. */
                $string = "&utm_source={$utmSource}&utm_medium={$utmMedium}&utm_campaign={$utmCampaing}";

                $array = array();
                $array = array(
                    "affiliateId" => $value->{"usuario_link.usuario_id"},
                    "createDate" => $value->{"usuario_link.fecha_crea"},
                    "creator" => $value->{"usuario_link.usuario_id"},
                    "id" => $value->{"usuario_link.usulink_id"},
                    "marketingSourceName" => "1",
                    "name" => $value->{"usuario_link.nombre"},
                    "siteId" => "",
                    "tag1" => "",
                    "tag2" => "",
                    "tag3" => "288",
                    "trackingLink" => $trackingLink . "?btag=" . encrypt($value->{"usuario_link.usuario_id"} . "__" . $value->{"usuario_link.usulink_id"}, $ENCRYPTION_KEY) . $string,
                );


                /* La función agrega un elemento al final de un array en PHP. */
                array_push($final, $array);

            }


            /* crea una respuesta en formato JSON con estado y resultados específicos. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => $UsuarioLink->count[0]->{".count"},

            );



            /* Inicializa un array vacío para almacenar notificaciones en la respuesta. */
            $response["notification"] = array();

            break;


        case "setDeleteLink":


            /* Código que actualiza el estado de un enlace de usuario si está logueado. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $LinkId = $params->LinkId;

            if ($LinkId != "" ) {

                $UsuarioLink = new UsuarioLink($LinkId);

                $UsuarioLink->setEstado('I');



                $UsuarioLinkMySqlDAO = new UsuarioLinkMySqlDAO();
                $UsuarioLinkMySqlDAO->update($UsuarioLink);
                $UsuarioLinkMySqlDAO->getTransaction()->commit();

                $response["status"] = true;
                $response["html"] = "";
                $response["notification"] = array();

            } else {
                /* Código que maneja errores asignando un mensaje y estado a la respuesta. */

                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;
        case "setEditLink":


            /* valida sesión, actualiza un enlace de usuario y confirma la transacción. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $LinkId = $params->LinkId;
            $name = $params->name;

            if ($LinkId != "" && $name != "") {

                $UsuarioLink = new UsuarioLink($LinkId);

                $UsuarioLink->setNombre($name);



                $UsuarioLinkMySqlDAO = new UsuarioLinkMySqlDAO();
                $UsuarioLinkMySqlDAO->update($UsuarioLink);
                $UsuarioLinkMySqlDAO->getTransaction()->commit();

                $response["status"] = true;
                $response["html"] = "";
                $response["notification"] = array();

            } else {
                /* maneja un error, estableciendo un mensaje y un estado en falso. */

                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;


        case "getActiveBanner":


            /* Verifica si el usuario está logueado y crea un objeto UsuarioMandante si cumple condición. */
            if (!$_SESSION['logueado']) {
                exit();
            }

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                /* Crea un nuevo objeto UsuarioMandante si no se cumple la condición anterior. */

                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }


            /* asigna valores de parámetros y ajusta el agentId si es cero. */
            $linkId = $params->linkId;
            $agentId = $params->agentId;
            $Id = $params->Id;
            $BannerId = $params->BannerId;
            $ActiveDate = $params->ActiveBanner;

            if ($agentId == 0) {
                $agentId = "";
            }


            /* asigna una cadena vacía a $linkId si es igual a cero. */
            if ($linkId == 0) {
                $linkId = "";
            }


            $MaxRows = $params->MaxRows;

            /* Asignación de variables y manejo de valor predeterminado en caso de vacío. */
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Código que añade una regla de filtrado si el $agentId no está vacío. */
            $rules = [];

            if ($agentId != "") {
                array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $agentId, "op" => "eq"));

            } else {
                /* Añade una regla a un array si la condición no se cumple. */

                array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            }


            /* añade condiciones a un arreglo si las variables no están vacías. */
            if ($Id != "") {
                array_push($rules, array("field" => "usuario_banner.usubanner_id", "data" => $Id, "op" => "eq"));

            }
            if ($BannerId != "") {
                array_push($rules, array("field" => "usuario_banner.banner_id", "data" => $BannerId, "op" => "eq"));

            }


            /* Condiciona la adición de reglas al filtro basado en la fecha activa. */
            if ($ActiveDate != "") {
                array_push($rules, array("field" => "usuario_banner.fecha_crea", "data" => $ActiveDate, "op" => "eq"));

            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Genera un JSON de filtros y obtiene banners personalizados de un usuario. */
            $json = json_encode($filtro);


            $Banner = new Banner();
            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.* ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);


            /* organiza datos de banners en un array final estructurado. */
            $final = array();

            foreach ($Banners->data as $key => $value) {

                $array = array();
                $array = array(
                    "Id" => $value->{"usuario_banner.usubanner_id"},
                    "BannerId" => $value->{"usuario_banner.banner_id"},
                    "Description" => $value->{"banner.nombre"},
                    "ActiveDate" => $value->{"usuario_banner.fecha_crea"}
                );

                array_push($final, $array);

            }



            /* configura una respuesta con estado, HTML y datos de registros. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );



            /* Se inicializa un array vacío para almacenar notificaciones en la variable $response. */
            $response["notification"] = array();

            break;


        case "getAllBanner":


            /* Verifica si el usuario está logueado y crea un objeto UsuarioMandante. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $linkId = $params->linkId;

            /* asigna valores vacíos a $agentId y $linkId si son cero. */
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }

            if ($linkId == 0) {
                $linkId = "";
            }



            /* asigna valores de parámetros y maneja el caso de filas a omitir. */
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* establece valores predeterminados para variables si están vacías. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Crea reglas de filtrado para comparar un usuario específico en una consulta. */
            $rules = [];


            array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte un filtro a JSON y recupera banners personalizados. */
            $json = json_encode($filtro);


            $Banner = new Banner();
            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante()," banner.* ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);


            /* Se inicializa un array vacío llamado "final" en PHP. */
            $final = array();


            foreach ($Banners->data as $key => $value) {


                /* asigna 1 a $isfavorito si el valor es 'S'. */
                $isfavorito = 0;
                $isActivate = 0;

                if ($value->{"usuario_banner.favorito"} == 'S') {
                    $isfavorito = 1;
                }

                /* verifica un ID de banner y asigna activación a una variable. */
                if ($value->{"usuario_banner.usubanner_id"} != 0) {
                    $isActivate = 1;
                }

                $array = array();
                $array = array(
                    "Id" => $value->{"banner.banner_id"},
                    "Description" => $value->{"banner.nombre"},
                    "ActiveDate" => $value->{"banner.fecha_crea"},
                    "State" => $value->{"banner.estado"},
                    "ExpirationDate" => $value->{"banner.fecha_expiracion"},
                    "activateBanner" => $isActivate,
                    "canDelete" => 0,
                    "canEdit" => 1,
                    "ctr" => "50",
                    "expireDate" => "",
                    "favorite" => $isfavorito,
                    "filename" => $value->{"banner.filename"},
                    "height" => $value->{"banner.height"},
                    "id" => $value->{"banner.banner_id"},
                    "isPublished" => "1",
                    "language" => $value->{"banner.idioma"},
                    "languages" => $value->{"banner.idioma"},
                    "mine" => 0,
                    "name" => $value->{"banner.banner_id"},
                    "oldFileName" => "",
                    "oldType" => "",
                    "params" => "",
                    "partnerId" => "288",
                    "path" => $urlApiAfiliados . $value->{"banner.filename"},
                    "preview" => 0,
                    "productId" => "1",
                    "productName" => "Sportsbook",
                    "size" => $value->{"banner.bsize"},
                    "status" => "OK",
                    "typeId" => "2",
                    "typeName" => "Image",
                    "updateDate" => $value->{"banner.fecha_modif"},
                    "uploadDate" => $value->{"banner.fecha_crea"},
                    "width" => $value->{"banner.width"},
                    "expirationDate" => $value->{"banner.fecha_expiracion"}, "state" => ($value->{"banner.estado"} == 'A') ? true : false
                );



                /* Agrega el contenido de $array al final del array $final. */
                array_push($final, $array);

            }



            /* Crea una respuesta estructurada con estado, HTML y datos de registros. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );



            /* Crea un array vacío para almacenar notificaciones en la respuesta. */
            $response["notification"] = array();

            break;


        /**
         * GetLinksDashboards
         *
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
        case "GetLinksDashboards":


            /* Verifica si el usuario está logueado antes de crear un objeto UsuarioMandante. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                /* Se crea un objeto UsuarioMandante utilizando la sesión de usuario almacenada. */

                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }

            /* asigna parámetros a variables para su posterior uso en un script. */
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;


            $linkId = $params->linkId;

            /* asigna valores vacíos a variables si son iguales a cero. */
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }

            if ($linkId == 0) {
                $linkId = "";
            }



            /* asigna valores predeterminados a variables si están vacías. */
            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* establece un límite de filas y crea reglas basadas en condiciones. */
            if ($MaxRows == "") {
                $MaxRows = 10000;
            }

            $rules = [];

            if ($linkId != "") {
                array_push($rules, array("field" => "usuario_link.link_id", "data" => $linkId, "op" => "eq"));

            }


            /* añade condiciones a un arreglo según la existencia de $agentId. */
            if ($agentId != "") {
                array_push($rules, array("field" => "usuario_link.usuario_id", "data" => $agentId, "op" => "eq"));

            } else {
                array_push($rules, array("field" => "usuario_link.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            }


            /* Se crea un filtro en JSON con reglas para un usuario en estado 'A'. */
            array_push($rules, array("field" => "usuario_link.estado", "data" => 'A', "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsuarioLink = new UsuarioLink();


            /* obtiene y decodifica enlaces de usuario, almacenándolos en un array final. */
            $UsuarioLink = $UsuarioLink->getUsuarioLinksCustom(" COUNT(usuario_link.usulink_id) links ", "usuario_link.usulink_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $UsuarioLink = json_decode($UsuarioLink);

            $final = [];
            $final["Links"] = [];
            $final["Links"]["Total"] = $UsuarioLink->data[0]->{".links"};



            /* Se establece una respuesta con estado, contenido HTML, resultado final y notificaciones vacías. */
            $response["status"] = true;
            $response["html"] = "";

            $response["result"] = $final;

            $response["notification"] = array();

            break;

        case "GetActiveBannerDashboards":


            /* verifica sesión y crea un objeto UsuarioMandante si el usuario es válido. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                /* Se crea una instancia de UsuarioMandante usando la sesión del usuario2. */

                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }



            /* Asignación de variables y ajuste de valor de $agentId según condición. */
            $linkId = $params->linkId;
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }


            /* inicializa `$linkId` como cadena si es igual a 0 y asigna `$MaxRows`. */
            if ($linkId == 0) {
                $linkId = "";
            }


            $MaxRows = $params->MaxRows;

            /* Asigna un valor a SkeepRows si está vacío, utilizando OrderedItem de params. */
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 10000;
            }


            /* agrega reglas de filtrado basadas en el ID del agente proporcionado. */
            $rules = [];


            if ($agentId != "") {
                array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $agentId, "op" => "eq"));

            } else {
                /* Añade una regla al array si no se cumple una condición previa. */

                array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            }



            /* Codifica filtros y obtiene banners personalizados del usuario mediante consulta a base de datos. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $Banner = new Banner();

            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.* ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);

            /* convierte un JSON a objeto y extrae enlaces de banners activos. */
            $Banners = json_decode($Banners);


            $final = [];
            $final["ActiveBanner"] = [];
            $final["ActiveBanner"]["Total"] = $Banners->data[0]->{".links"};



            /* Se prepara una respuesta estructurada con estado, HTML, resultado y notificaciones. */
            $response["status"] = true;
            $response["html"] = "";

            $response["result"] = $final;

            $response["notification"] = array();

            break;

        /**
         * getCurrentAffiliate
         *
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
        case "getCurrentAffiliate":


            /* verifica si el usuario está logueado y crea un objeto de usuario. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);


            $response["status"] = true;

            /* inicializa una respuesta con HTML vacío y obtiene el ID del usuario. */
            $response["html"] = "";
            $response["result"] = $UsuarioMandante->getUsumandanteId();

            $response["notification"] = array();

            break;

        /**
         * getGeneralLinkClicks
         *
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
        case "getGeneralLinkClicks":
            /* maneja una solicitud, estableciendo respuesta y estructura inicial en un array. */

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "clickCount" => ""
            );

            $response["notification"] = array();

            break;

        /**
         * getMarketingSources
         *
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
        case "getMarketingSources":

            /* Código que define una respuesta con datos estructurados sobre registros de afiliados. */
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(
                    array(
                        "affiliateId" => 39705,
                        "createDate" => "2018-04-13 16:31:01",
                        "creator" => "danielftg@hotmail.com",
                        "marketingSourceId" => "7614",
                        "name" => "First Added",
                        "partnerId" => "288",
                        "site" => "http://www.com",
                        "updateDate" => "0000-00-00 00:00:00",

                    )

                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );


            /* Inicializa un arreglo vacío para almacenar notificaciones en la respuesta. */
            $response["notification"] = array();
            break;

        /**
         * getSocialShareList
         *
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
        case "getSocialShareList":
            /* Devuelve un JSON con estado, resultados vacíos y sin notificaciones. */


            print_r('{"status":true,"html":"","result":{"records":[],"totalRecordsCount":"0","titles":null,"total":null},"notification":[]}');
            break;

        /**
         * AddBankAccount
         *
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
        case "AddBankAccount":

            /* verifica si el usuario está logueado y crea instancias de UsuarioMandante y Usuario. */
            if (!$_SESSION['logueado']) {
                exit();
            }

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());



            /* crea un objeto y verifica restricciones sobre un mandante específico. */
            $tieneRestricciones = 0;
            $cumpleRestricciones = false;

            try {
                $Clasificador = new Clasificador("", "MAXACCOUNTSBANK");

                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                $tieneRestricciones = $MandanteDetalle->getValor();


            } catch (Exception $e) {
                /* Manejo de excepciones filtrando códigos específicos para ajustar restricciones. */


                if ($e->getCode() == 34) {
                    $tieneRestricciones = 0;
                } elseif ($e->getCode() == 41) {
                    $tieneRestricciones = 0;
                } else {
                    throw $e;
                }
            }


            if ($tieneRestricciones > 0) {

                /* Se crea un filtro con reglas para validar usuarios activos en la base de datos. */
                $rules = [];
                array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* Convierte un filtro a JSON, recupera y decodifica configuraciones de usuario. */
                $json2 = json_encode($filtro);

                $UsuarioBanco = new UsuarioBanco();

                $configuraciones = $UsuarioBanco->getUsuarioBancosCustom(" usuario_banco.* ", "usuario_banco.usubanco_id", "asc", 0, 100, $json2, true);

                $configuraciones = json_decode($configuraciones);


                /* Condicional que verifica si se cumplen restricciones basadas en configuraciones. */
                if (intval($configuraciones->count[0]->{'.count'}) >= intval($tieneRestricciones)) {

                } else {
                    $cumpleRestricciones = true;
                }
            } else {
                /* Establece la variable $cumpleRestricciones a verdadero si no se cumplen condiciones previas. */

                $cumpleRestricciones = true;
            }

            if ($cumpleRestricciones) {

                /* Se asignan valores de parámetros a variables específicas relacionadas con cuentas bancarias. */
                $account = $params->Account;
                $account_type = ($params->TypeAccount == 1) ? 1 : 0;
                $bank = $params->Bank;
                $client_type = 0;
                $cod_interbank = $params->InterbankCode;
                $conf_account = $params->ConfirmAccount;



                /* crea un objeto UsuarioBanco y establece sus propiedades específicas. */
                $UsuarioBanco = new UsuarioBanco();
                $UsuarioBanco->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                $UsuarioBanco->setBancoId($bank);
                $UsuarioBanco->setCuenta($account);
                $UsuarioBanco->setTipoCuenta($account_type);
                $UsuarioBanco->setTipoCliente($client_type);

                /* configura un objeto UsuarioBanco con datos del UsuarioMandante y estado activos. */
                $UsuarioBanco->setUsucreaId($UsuarioMandante->getUsuarioMandante());
                $UsuarioBanco->setUsumodifId($UsuarioMandante->getUsuarioMandante());
                $UsuarioBanco->setEstado('A');
                $UsuarioBanco->setCodigo($cod_interbank);

                $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();


                /* Inserta un usuario en la base de datos y confirma la transacción. */
                $UsuarioBancoMySqlDAO->insert($UsuarioBanco);
                $UsuarioBancoMySqlDAO->getTransaction()->commit();


                $response["status"] = true;
                $response["html"] = "";
            } else {
                /* asigna un estado verdadero y un HTML vacío a la respuesta. */

                $response["status"] = true;
                $response["html"] = "";
            }


            break;

        /**
         * GetBanks
         *
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
        case "GetBanks":


            /* verifica si un usuario está logueado; si no, termina la ejecución. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
//$Pais = new Pais($Usuario->paisId);

            $MaxRows = $params->MaxRows;

            /* Asigna valores de parámetros a variables y asegura que SkeepRows no esté vacío. */
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* asigna valores predeterminados a variables si están vacías. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Se crean reglas de filtrado para consultar información bancaria según país y estado. */
            $rules = [];
            array_push($rules, array("field" => "banco.pais_id", "data" => "$Usuario->paisId", "op" => "eq"));
            array_push($rules, array("field" => "banco.estado", "data" => "A", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte un filtro a JSON y obtiene bancos desde la base de datos. */
            $json2 = json_encode($filtro);

            $Banco = new Banco();

            $Bancos = $Banco->getBancosCustom(" banco.* ", "banco.banco_id", "asc", $SkeepRows, $MaxRows, $json2, true);


            $Bancos = json_decode($Bancos);


            /* Esto transforma datos de bancos en un arreglo estructurado para su uso posterior. */
            $BancosData = array();

            foreach ($Bancos->data as $key => $value) {


                $arraybanco = array();
                $arraybanco["Id"] = ($value->{"banco.banco_id"});
                $arraybanco["Name"] = ($value->{"banco.descripcion"});

                array_push($BancosData, $arraybanco);


            }



            /* Crea un arreglo de respuesta con estado, HTML y datos de registros. */
            $response = array();
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $BancosData
            );


            break;

        case "GetRegions":


            /* Se crea un objeto 'Pais' y se configura un filtro JSON para consulta. */
            $Pais = new Pais();

            $SkeepRows = 0;
            $MaxRows = 1000000;
            $mandante=$params->partner;

            $json = '{"rules" : [{"field" : "pais.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';


            /* Se definen reglas para validar condiciones de país en función de la sesión. */
            $rules = [];
            array_push($rules, array("field" => "pais.estado", "data" => 'A', "op" => "eq"));
            array_push($rules, array("field" => "pais_mandante.estado", "data" => 'A', "op" => "eq"));

            if ($_SESSION['PaisCond'] == "S" && $_SESSION['logueado'] ) {

                array_push($rules, array("field" => "pais.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }


            /* verifica condiciones de sesión y define un filtro con reglas. */
            if ($_SESSION['Global'] == "N" && $_SESSION['logueado']) {
                $mandante= $_SESSION['mandante'] ;
            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Se codifica un filtro a JSON y se obtienen países personalizados según parámetros. */
            $json2 = json_encode($filtro);

            $paises = $Pais->getPaisesCustom("pais.pais_id", "asc", $SkeepRows, $MaxRows, $json2, true,strtolower($mandante));

            $paises = json_decode($paises);


            $PaisesData = array();


            /* Recorre países, extrae ID y nombre, y los almacena en un nuevo array. */
            foreach ($paises->data as $key => $value) {


                $arraybanco = array();
                $arraybanco["Id"] = ($value->{"pais.pais_id"});
                $arraybanco["Name"] = ($value->{"pais.pais_nom"});

                array_push($PaisesData, $arraybanco);


            }


            /* Crea una respuesta estructurada en formato array con estado y datos de países. */
            $response = array();
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $PaisesData
            );


            break;

        case "GetComissions":


            /* verifica la sesión y obtiene identificadores de usuario y agente. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $UserId = $params->UserId;
            $agentId = $params->agentId;

            $rules = [];


            /* Se definen reglas de filtrado y se codifican en JSON. */
            array_push($rules, ['field' => 'clasificador.tipo', 'data' => 'PCOM', 'op' => 'eq']);
            array_push($rules, ['field' => 'clasificador.abreviado', 'data' => "'SPORTNGRAFF','SPORTAFF', 'CASINONGRAFF', 'CASINOAFF'", 'op' => 'in']);
            array_push($rules, ['field' => 'clasificador.estado', 'data' => 'A', 'op' => 'eq']);
            array_push($rules, ['field' => 'concesionario.estado', 'data' => 'A', 'op' => 'eq']);
            array_push($rules, ['field' => 'concesionario.estado', 'data' => 'DISP', 'op' => 'eq']);

            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);


            /* Se crea un concesionario, se obtienen productos y se inicializan comisiones. */
            $Concesionario = new Concesionario();
            $products = $Concesionario->getConcesionariosProductoInternoCustom('clasificador.clasificador_id, clasificador.descripcion, clasificador.abreviado, concesionario.porcenhijo  ', 'clasificador.clasificador_id', 'asc', 0, 100, $filters, true, $agentId ?: $UserId);

            $products = json_decode($products, true);

            $comissions = [
                'SportbookNGR' => 0,
                'CasinoNGR' => 0
            ];

            foreach($products['data'] as $key => $value) {

                /* Código que asigna un porcentaje a comisiones si se cumplen ciertas condiciones. */
                if($value['clasificador.abreviado'] == 'SPORTAFF' ){
                    if($comissions['SportbookNGR'] == '0'){
                        $comissions['SportbookNGR'] = floatval($value['concesionario.porcenhijo'] ?: 0);

                    }

                }

                /* asigna un porcentaje de comisión si se cumplen ciertas condiciones. */
                if($value['clasificador.abreviado'] == 'SPORTNGRAFF' ){
                    if($comissions['SportbookNGR'] == '0'){
                        $comissions['SportbookNGR'] = floatval($value['concesionario.porcenhijo'] ?: 0);

                    }
                }

                /* Condicional para asignar comisiones basadas en criterios específicos de clasificador y valor. */
                if($value['clasificador.abreviado'] == 'SPORTNGRAFF' ){
                    if($comissions['SportbookNGR'] == '0'){
                        $comissions['SportbookNGR'] = floatval($value['concesionario.porcenhijo'] ?: 0);
                    }

                }

                /* Asigna comisiones según el clasificador abreviado 'CASINOAFF' o 'CASINONGRAFF'. */
                if($value['clasificador.abreviado'] == 'CASINOAFF' ){
                    $comissions['CasinoNGR'] = floatval($value['concesionario.porcenhijo'] ?: 0);

                }
                if($value['clasificador.abreviado'] == 'CASINONGRAFF' ){
                    $comissions['CasinoNGR'] = floatval($value['concesionario.porcenhijo'] ?: 0);

                }
            }



            /* crea un array de respuesta con estado y resultados de comisiones. */
            $response = [];
            $response['status'] = true;
            $response['result'] = $comissions;


            break;


        /**
         * CreateWithdraw
         *
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
        case "CreateWithdraw":


            /* Verifica si el usuario está logueado; si no, termina la ejecución del script. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $AccountBank = $params->AccountBank;
            $Type = $params->Type;
            $Value = $params->Value;

            /* Asigna valores de `$params` y inicializa variables para cálculos posteriores. */
            $ConfirmValue = $params->ConfirmValue;


            $valorFinal = $Value;
            $valorImpuesto = 0;
            $valorPenalidad = 0;

            /* asigna un valor a créditos y crea un objeto de usuario. */
            $creditos = $Value;


            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $ClientId = $UsuarioMandante->getUsuarioMandante();

            /* Verifica si el usuario tiene suficientes créditos antes de realizar una acción. */
            $Usuario = new Usuario($ClientId);

            if ($creditos > 0) {

                if ($Usuario->creditosAfiliacion < $creditos) {
                    throw new Exception("Insufficient balance", "58");
                }
            } else {
                /* Lanza una excepción si el saldo es insuficiente en una operación. */

                throw new Exception("Insufficient balance", "58");
            }


            /* Verifica si el usuario de la cuenta bancaria corresponde al usuario indicado. */
            $UsuarioBanco = new UsuarioBanco($AccountBank);


            if ($UsuarioBanco->usuarioId != $Usuario->usuarioId) {
                throw new Exception("No existe Cuenta Bancaria", "67");

            }

            /* Se inicializan variables para manejar información de una transacción bancaria. */
            $amount = $Value;
            $service = "UserBank";
            $id = $AccountBank;
            $balance = 0;


//$UsuarioMandante = new UsuarioMandante($json->session->usuario);
//$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());

//Verificamos limite de minimo retiro
            /*$Clasificador = new Clasificador("", "MINWITHDRAW");
            $minimoMontoPremios = 0;
            try {
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $minimoMontoPremios = $MandanteDetalle->getValor();
            } catch (Exception $e) {
            }

            if ($amount < $minimoMontoPremios) {
            throw new Exception("MINIMO MONTO PARA RETIROS" . $amount . "-" . $minimoMontoPremios, "54");
            }

            //Verificamos limite de maximo retiro
            $Clasificador = new Clasificador("", "MAXWITHDRAW");
            $maximooMontoPremios = -1;
            try {
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $maximooMontoPremios = $MandanteDetalle->getValor();
            } catch (Exception $e) {
            }

            if ($amount > $maximooMontoPremios && $maximooMontoPremios != -1) {
            throw new Exception("MAXIMO MONTO PARA RETIROS" . $amount . "-" . $minimoMontoPremios, "55");
            }*/


//Verificamos impuesto retiro

//Si es de Saldo Premios
            if ($creditos > 0) {


                /* Se intenta obtener un valor de impuesto usando clases relacionadas a usuarios y clasificaciones. */
                $impuesto = -1;
                try {
                    $Clasificador = new Clasificador("", "TAXWITHDRAWAWARD");

                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
                    $impuesto = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                    /* Bloque de captura en PHP para manejar excepciones sin realizar ninguna acción. */

                }


                /* Calcula y ajusta un valor final aplicando impuestos según condiciones específicas. */
                if ($impuesto > 0) {
                    $impuestoDesde = -1;
                    try {
                        $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
                        $impuestoDesde = $MandanteDetalle->getValor();
                    } catch (Exception $e) {
                    }

                    if ($impuestoDesde != -1) {
                        if ($amount >= $impuestoDesde) {
                            $valorImpuesto = ($impuesto / 100) * $valorFinal;
                            $valorFinal = $valorFinal - $valorImpuesto;
                        }
                    }
                }
            }


            /* Crea un objeto "Consecutivo" y incrementa su número para utilizarlo en base de datos. */
            $Consecutivo = new Consecutivo("", "RET", "");

            $consecutivo_recarga = $Consecutivo->numero;

            $consecutivo_recarga++;

            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();


            /* Actualiza un consecutivo en la base de datos usando un objeto de acceso a datos. */
            $Consecutivo->setNumero($consecutivo_recarga);


            $ConsecutivoMySqlDAO->update($Consecutivo);

            $ConsecutivoMySqlDAO->getTransaction()->commit();



            /* Se crea una nueva instancia de CuentaCobro con identificadores específicos. */
            $CuentaCobro = new CuentaCobro();


            $CuentaCobro->cuentaId = $consecutivo_recarga;

            $CuentaCobro->usuarioId = $ClientId;


            /* Código asigna valores a un objeto CuentaCobro, incluyendo fecha y usuario. */
            $CuentaCobro->valor = $valorFinal;

            $CuentaCobro->fechaPago = '';

            $CuentaCobro->fechaCrea = date('Y-m-d H:i:s');


            $CuentaCobro->usucambioId = 0;

            /* inicializa propiedades de un objeto CuentaCobro con valores predeterminados. */
            $CuentaCobro->usurechazaId = 0;
            $CuentaCobro->usupagoId = 0;

            $CuentaCobro->fechaCambio = $CuentaCobro->fechaCrea;
            $CuentaCobro->fechaAccion = $CuentaCobro->fechaCrea;


            $CuentaCobro->estado = 'A';

            /* Genera una clave encriptada y asigna valores a propiedades de CuentaCobro. */
            $clave = GenerarClaveTicket2(5);

            $CuentaCobro->clave = "aes_encrypt('" . $clave . "','" . $claveEncrypt_Retiro . "')";

            $CuentaCobro->mandante = '0';

            $CuentaCobro->dirIp = '';


            /* Se asignan valores a propiedades de un objeto 'CuentaCobro' en PHP. */
            $CuentaCobro->impresa = 'S';

            $CuentaCobro->mediopagoId = 0;
            $CuentaCobro->puntoventaId = 0;

            $CuentaCobro->costo = $valorPenalidad;

            /* Asignación de valores a propiedades del objeto CuentaCobro y preparación de una variable. */
            $CuentaCobro->impuesto = $valorImpuesto;
            $CuentaCobro->creditos = $creditos;
            $CuentaCobro->creditosBase = 0;

            $CuentaCobro->transproductoId = 0;

            $method = "";
            switch ($service) {
                case "local":
                    /* Genera una tabla HTML para una nota de retiro con detalles específicos. */

                    $method = "pdf";
                    $status_message = '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody><tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">NOTA DE RETIRO</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nota de retiro No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">No. de Cliente:&nbsp;&nbsp;' . $ClientId . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nombre:&nbsp;&nbsp;' . $Usuario->nombre . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . $CuentaCobro->fechaCrea . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Clave:&nbsp;&nbsp;' . $clave . '</font></td></tr><tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a retirar:&nbsp;&nbsp;' . $amount . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Impuesto:&nbsp;&nbsp;' . $valorImpuesto . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Costo:&nbsp;&nbsp;' . $valorPenalidad . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar:&nbsp;&nbsp;' . $valorFinal . '</font></td></tr>

    </tbody></table>';
                    break;

                /**
                 * UserBank
                 *
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
                case "UserBank":
                    /* Asignación de valores a propiedades en una estructura de control para "UserBank". */

                    $method = "0";
                    $status_message = "";

                    $CuentaCobro->mediopagoId = $id;

                    break;
            }



            /* Inserta una cuenta de cobro y actualiza créditos del usuario si son positivos. */
            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

            $CuentaCobroMySqlDAO->insert($CuentaCobro);


            if ($creditos > 0) {
                $Usuario->creditosAfiliacion = "creditos_afiliacion - " . $creditos;

                $UsuarioMySqlDAO = new UsuarioMySqlDAO($CuentaCobroMySqlDAO->getTransaction());

                $UsuarioMySqlDAO->update($Usuario);

            }



            /* Se realiza un commit de transacción y se prepara respuesta exitosa en formato HTML. */
            $CuentaCobroMySqlDAO->getTransaction()->commit();

            $response["status"] = true;
            $response["html"] = "";

            break;


        /**
         * GetWithdrawls
         *
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
        case "GetWithdrawls":


            /* Verifica si el usuario está logueado y obtiene datos del usuario correspondiente. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);


            $agentId = $params->agentId;

            /* Se asignan variables a partir de parámetros y datos en formato JSON. */
            $BeginDate = $params->BeginDate;
            $EndDate = $params->EndDate;
            $AccountBank = $params->AccountBank;
            $State = $json->params->State;


            $MaxRows = $params->MaxRows;

            /* asigna parámetros de entrada a variables para procesamiento posterior. */
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;


            /* asigna valores a variables si no están vacías. */
            if ($start != "") {
                $SkeepRows = $start;

            }

            if ($length != "") {
                $MaxRows = $length;

            }



            /* asigna valores predeterminados a las variables si están vacías. */
            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* Se establece un límite de filas y reglas de acceso según el perfil del usuario. */
            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            if ($_SESSION["win_perfil"] != "ADMINAFILIADOS" && $_SESSION["win_perfil"] != "ADMIN" && $_SESSION["win_perfil"] != "SA" && $_SESSION["win_perfil"] != "ADMIN2") {
                array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            }


            /* Agrega reglas de fecha inicial y final a un array si están definidas. */
            if ($BeginDate != "") {
                array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => $BeginDate, "op" => "ge"));

            }

            if ($EndDate != "") {
                array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => $EndDate, "op" => "le"));

            }


            /* Agrega reglas basadas en condiciones de cuenta bancaria y estado de pago. */
            if ($AccountBank != "") {
                array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => $AccountBank, "op" => "le"));

            }

            if ($State == "0") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "A", "op" => "le"));

            }


            /* Agrega reglas basadas en el estado de "cuenta_cobro" según condiciones específicas. */
            if ($State == "1") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "P", "op" => "le"));

            }

            if ($State == "3") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "le"));

            }


            /* Añade una regla al array si el estado es "4" y crea un filtro. */
            if ($State == "4") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "R", "op" => "le"));

            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Codifica un filtro en JSON y obtiene cuentas de cobro con orden y límites. */
            $json2 = json_encode($filtro);

            $CuentaCobro = new CuentaCobro();

            $cuentas = $CuentaCobro->getCuentasCobroCustom(" usuario.nombre,usuario_banco.cuenta,cuenta_cobro.fecha_pago,cuenta_cobro.impuesto,cuenta_cobro.cuenta_id,cuenta_cobro.estado,cuenta_cobro.valor,cuenta_cobro.fecha_crea ", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json2, true, "cuenta_cobro.cuenta_id");

            $cuentas = json_decode($cuentas);


            /* Se inicializa un array vacío llamado cuentasData en PHP. */
            $cuentasData = array();

            foreach ($cuentas->data as $key => $value) {



                /* Se construye un arreglo asociativo con datos de usuario y cuentas de cobro. */
                $arraybet = array();
                $arraybet["UserName"] = ($value->{"usuario.nombre"});
                $arraybet["Id"] = ($value->{"cuenta_cobro.cuenta_id"});
                $arraybet["AmountBase"] = ($value->{"cuenta_cobro.valor"});
                $arraybet["Tax"] = ($value->{"cuenta_cobro.impuesto"});
                $arraybet["NetAmount"] = ($value->{"cuenta_cobro.valor"});

                /* Asigna valores a un array dependiendo de las propiedades de un objeto. */
                $arraybet["CreationDate"] = ($value->{"cuenta_cobro.fecha_crea"});
                $arraybet["AccountBank"] = ($value->{"usuario_banco.cuenta"});
                $arraybet["payment_system_name"] = 'local';

                if ($value->{"cuenta_cobro.estado"} == "I") {
                    $arraybet["State"] = 3;

                } elseif ($value->{"cuenta_cobro.estado"} == "A") {

                    /* Se asigna un valor al estado en función de la condición de cuenta de cobro. */
                    $arraybet["State"] = 0;

                } elseif ($value->{"cuenta_cobro.estado"} == "P") {
                    $arraybet["State"] = 2;

                } elseif ($value->{"cuenta_cobro.estado"} == "R") {

                    /* Se asigna un estado y fecha de pago a un array, luego se agrega a otro array. */
                    $arraybet["State"] = 4;

                }
                $arraybet["PayDate"] = ($value->{"cuenta_cobro.fecha_pago"});

                array_push($cuentasData, $arraybet);


            }



            /* verifica si un valor es numérico y lo asigna a una variable. */
            $count = 0;

            if (is_numeric($cuentas->count[0]->{'.count'})) {
                $count = $cuentas->count[0]->{'.count'};
            }

            $response["status"] = true;

            /* crea una respuesta estructurada con datos y totales para una consulta. */
            $response["html"] = "";
            $response["result"] = array(
                "records" => $cuentasData,
                "titles" => "",
                "total" => $count,
                "totalRecordsCount" => $count,

            );


            /* Crea un arreglo vacío llamado "notification" dentro de la variable "$response". */
            $response["notification"] = array();


            break;

        /**
         * GetBankAccounts
         *
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
        case "GetBankAccounts":

            /* verifica si el usuario está logueado antes de continuar procesando. */
            if (!$_SESSION['logueado']) {
                exit();
            }
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $agentId = $params->agentId;

            /* asigna parámetros de entrada a variables para procesamiento posterior. */
            $Account = $params->Account;
            $Bank = $params->Bank;
            $InterbankCode = $params->InterbankCode;
            $TypeAccount = $params->TypeAccount;
            $State = $json->params->State;

            $MaxRows = $params->MaxRows;

            /* asigna valores de parámetros a variables para procesamiento posterior. */
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;


            /* asigna valores a variables si no están vacías. */
            if ($start != "") {
                $SkeepRows = $start;

            }

            if ($length != "") {
                $MaxRows = $length;

            }



            /* inicializa variables si están vacías: $SkeepRows a 0, $OrderedItem a 1. */
            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* establece un límite de filas y define reglas de acceso basadas en permisos. */
            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];

            if ($_SESSION["win_perfil"] != "ADMINAFILIADOS" && $_SESSION["win_perfil"] != "ADMIN" && $_SESSION["win_perfil"] != "SA" && $_SESSION["win_perfil"] != "ADMIN2") {
                array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            }


            /* Agrega reglas de filtro para usuario y cuenta si están definidos. */
            if ($agentId != "") {
                array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $agentId, "op" => "eq"));

            }

            if ($Account != "") {
                array_push($rules, array("field" => "usuario_banco.cuenta", "data" => $Account, "op" => "eq"));

            }


            /* Agrega reglas de filtro si los valores de banco y código interbancario no están vacíos. */
            if ($Bank != "") {
                array_push($rules, array("field" => "banco.banco_id", "data" => $Bank, "op" => "eq"));

            }

            if ($InterbankCode != "") {
                array_push($rules, array("field" => "usuario_banco.codigo", "data" => $InterbankCode, "op" => "eq"));

            }


            /* agrega reglas basadas en el tipo de cuenta y estado proporcionados. */
            if ($TypeAccount != "") {
                array_push($rules, array("field" => "usuario_banco.tipo_cuenta", "data" => $TypeAccount, "op" => "eq"));

            }

            if ($State == "1") {
                array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));
            }


            /* Crea un filtro en JSON y obtiene configuraciones de usuario desde la base de datos. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            $UsuarioBanco = new UsuarioBanco();

            $configuraciones = $UsuarioBanco->getUsuarioBancosCustom(" usuario.nombre,usuario_banco.*,banco.* ", "usuario_banco.estado", "asc", $SkeepRows, $MaxRows, $json2, true);



            /* Decodifica un JSON en PHP y crea un array vacío para datos. */
            $configuraciones = json_decode($configuraciones);

            $configuracionesData = array();

            foreach ($configuraciones->data as $key => $value) {


                /* asigna datos de un objeto a un array asociativo en PHP. */
                $arraybanco = array();
                $arraybanco["UserName"] = ($value->{"usuario.nombre"});
                $arraybanco["Id"] = ($value->{"usuario_banco.usubanco_id"});
                $arraybanco["Account"] = ($value->{"usuario_banco.cuenta"});
                $arraybanco["InterbankCode"] = ($value->{"usuario_banco.codigo"});
                $arraybanco["TypeAccount"] = $value->{"usuario_banco.tipo_cuenta"};

                /* asigna descripciones de banco y tipos de cuenta a un arreglo. */
                $arraybanco["Bank"] = $value->{"banco.descripcion"};

                switch ($arraybanco["TypeAccount"]) {
                    case "0":
                        $arraybanco["TypeAccount"] = "Ahorros";
                        break;

                    case "1":
                        $arraybanco["TypeAccount"] = "Corriente";

                        break;
                }


                /* Asigna un tipo de cliente basado en un valor utilizando un switch. */
                $arraybanco["client_type"] = $value->{"usuario_banco.tipo_cliente"};

                switch ($arraybanco["client_type"]) {
                    case "1":
                        $arraybanco["client_type"] = "Person";
                        break;

                    case "0":
                        $arraybanco["client_type"] = "Current";

                        break;
                }


                /* asigna valores a un array basado en el estado de un usuario. */
                $arraybanco["State"] = ($value->{"usuario_banco.estado"} == "A") ? 0 : 1;
                $arraybanco["coin"] = 'PEN';

                if ($arraybanco["state"] == "A") {
// $arraybanco["state"] = '1';

                } elseif ($arraybanco["state"] == "I") {
                    /* cambia el estado de "I" a "C" en un arreglo específico. */

                    $arraybanco["state"] = 'C';

                }

                /* Agrega el arreglo $arraybanco al final del arreglo $configuracionesData. */
                array_push($configuracionesData, $arraybanco);


            }



            /* Verifica si el valor es numérico y lo asigna a la variable $count. */
            $count = 0;

            if (is_numeric($configuraciones->count[0]->{'.count'})) {
                $count = $configuraciones->count[0]->{'.count'};
            }

            $response["status"] = true;

            /* crea una respuesta estructurada con datos y recuentos específicos. */
            $response["html"] = "";
            $response["result"] = array(
                "records" => $configuracionesData,
                "titles" => "",
                "total" => $count,
                "totalRecordsCount" => $count,

            );


            /* Inicializa un array vacío para almacenar notificaciones en la variable de respuesta. */
            $response["notification"] = array();

            break;

        default:

            # code...
            break;
    }
} catch (Exception $e) {


    /* Muestra errores si el entorno está en modo depuración y registra advertencias en syslog. */
    if($_ENV['debug']){
        print_r($e);
    }
    syslog(LOG_WARNING, "ERRORAFILIADOS :".  $e->getCode() . ' - '. $e->getMessage() .  json_encode($params)  .  json_encode($_SERVER) .  json_encode($_REQUEST) );

    switch ($e->getCode()) {
        case 50:
            /* Maneja un error de autenticación de usuario, proporcionando un mensaje específico. */

            $response["HasError"] = true;

            $response["AlertType"] = "danger";
            $response["AlertMessage"] = 'El usuario o la contraseña son incorrectos, revisa los datos ingresados.';
// $response["AlertMessage"] = '-|' . "" . ' |-' . '(' . $e->getCode() . ')';
            $response["ModelErrors"] = [];
            $response["status"] = false;
            $response["result"] = false;
            $response["success"] = false;

            break;

        default:

            /* Código que maneja un error, asignando valores de estado y mensaje de alerta. */
            $response["HasError"] = true;
            $response["status"] = false;
            $response["success"] = false;
            $response["result"] = false;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = '-|' . $e->getMessage() . ' |-' . '(' . $e->getCode() . ')';
// $response["AlertMessage"] = '-|' . "" . ' |-' . '(' . $e->getCode() . ')';

            /* gestiona errores, mostrando mensajes específicos según el error recibido. */
            $response["AlertMessage"] = 'Error en la solicitud. Comunicate con soporte, Reporta el codigo de error: ' . $e->getCode();

            if($e->getCode() == 30003){
                $response["AlertMessage"] = 'Login o contraseña incorrecta' ;

            }

            /* Inicializa un arreglo vacío para almacenar errores del modelo en la respuesta. */
            $response["ModelErrors"] = [];

            break;
    }
}
if (json_encode($response) != "[]") {
    print_r(json_encode($response));

}

/**
 * Convierte una cantidad de una moneda a otra utilizando una API externa.
 *
 * @param string $from_Currency Moneda de origen (código ISO 4217).
 * @param string $to_Currency Moneda de destino (código ISO 4217).
 * @param float $amount Cantidad a convertir.
 * @return float Cantidad convertida en la moneda de destino.
 */
function currencyConverter($from_Currency, $to_Currency, $amount)
{
    $from_Currency = urlencode($from_Currency);
    $to_Currency = urlencode($to_Currency);
    $encode_amount = 1;

    $rawdata = file_get_contents("http://api.currencies.zone/v1/quotes/$from_Currency/$to_Currency/json?quantity=$amount&key=44|YSqBgkAbvbGfenoxx62OaSnoD~rF8rw~");
    $rawdata = json_decode($rawdata);

    return $rawdata->result->amount;
}

/**
 * Obtiene una lista de deportes desde una API externa.
 *
 * @return array Arreglo de deportes con sus identificadores y nombres.
 */
function getSports()
{
    $rawdata = file_get_contents("http://datafeeds-itainment.biahosted.com/public-api/Export/GetEvents?importerId=1&from=2017-07-3&to=2017-07-3");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {
        $item_data = array(
            "Id" => $item->SportId,
            "Name" => $item->Name
        );
        array_push($array, $item_data);
    }

    return $array;
}

/**
 * Obtiene los tipos de mercado disponibles para un deporte específico.
 *
 * @param int $sport Identificador del deporte.
 * @return array Arreglo de tipos de mercado con sus identificadores y nombres.
 */
function getMarketTypes($sport)
{
    $rawdata = file_get_contents("http://datafeeds-itainment.biahosted.com/public-api/Export/GetEvents?importerId=1&from=2017-07-3&to=2017-07-3");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {
        if ($sport == $item->SportId) {
            $rawdata2 = file_get_contents("http://datafeeds-itainment.biahosted.com/public-api/Export/GetMarkets?importerId=1&eventId=" . $item->Categories[0]->Championships[0]->Events[0]->EventId);
            $datos2 = json_decode($rawdata2);

            foreach ($datos2 as $item2) {
                $item_data = array(
                    "Id" => $item->SportId . "M" . $item2->MarketTypeid,
                    "Name" => $item2->Name
                );
                array_push($array, $item_data);
            }
        }
    }

    return $array;
}

/**
 * Obtiene las regiones asociadas a un deporte específico.
 *
 * @param int $sport Identificador del deporte.
 * @return array Arreglo de regiones con sus identificadores y nombres.
 */
function getRegions($sport)
{
    $rawdata = file_get_contents("http://datafeeds-itainment.biahosted.com/public-api/Export/GetEvents?importerId=1&from=2017-07-3&to=2017-07-3");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {
        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                $item_data = array(
                    "Id" => $item2->CategoryId,
                    "Name" => $item2->Name
                );
                array_push($array, $item_data);
            }
        }
    }

    return $array;
}

/**
 * Obtiene las competiciones asociadas a un deporte y región específicos.
 *
 * @param int $sport Identificador del deporte.
 * @param int $region Identificador de la región.
 * @return array Arreglo de competiciones con sus identificadores y nombres.
 */
function getCompetitions($sport, $region)
{
    $rawdata = file_get_contents("http://datafeeds-itainment.biahosted.com/public-api/Export/GetEvents?importerId=1&from=2017-07-3&to=2017-07-3");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {
        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                if ($item2->CategoryId == $region) {
                    foreach ($item2->Championships as $item3) {
                        $item_data = array(
                            "Id" => $item3->ChampionshipId,
                            "Name" => $item3->Name
                        );
                        array_push($array, $item_data);
                    }
                }
            }
        }
    }

    return $array;
}

/**
 * Obtiene los partidos asociados a un deporte, región y competición específicos.
 *
 * @param int $sport Identificador del deporte.
 * @param int $region Identificador de la región.
 * @param int $competition Identificador de la competición.
 * @return array Arreglo de partidos con sus identificadores y nombres.
 */
function getMatches($sport, $region, $competition)
{
    $rawdata = file_get_contents("http://datafeeds-itainment.biahosted.com/public-api/Export/GetEvents?importerId=1&from=2017-07-3&to=2017-07-3");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {
        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                if ($item2->CategoryId == $region) {
                    foreach ($item2->Championships as $item3) {
                        if ($item3->ChampionshipId == $competition) {
                            foreach ($item3->Events as $item4) {
                                $item_data = array(
                                    "Id" => $item4->EventId,
                                    "Name" => $item4->Name
                                );
                                array_push($array, $item_data);
                            }
                        }
                    }
                }
            }
        }
    }

    return $array;
}

/**
 * Genera una clave aleatoria de una longitud específica.
 *
 * @param int $length Longitud de la clave a generar.
 * @return string Clave generada aleatoriamente.
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
 * Crea un script HTML para integrar un banner de afiliados.
 *
 * @param string $URL_AFFILIATES_API URL de la API de afiliados.
 * @param int $account Identificador de la cuenta.
 * @param int $mId Identificador del módulo.
 * @return string Script HTML generado.
 */
function createScript($URL_AFFILIATES_API, $account, $mId)
{
    $script = '<script type="text/javascript">!function(e,t,a,n,c,s){e.affScriptCount = e.affScriptCount == undefined ? 0 : e.affScriptCount+1;if(e.affScriptUrl === undefined){e.affScriptUrl = {};}e.affScriptUrl[e.affScriptCount] = n;s = s + "_" + e.affScriptCount;e.bcAnalyticsObject=c,e[c]=e[c]||function(){(e[c].q=e[c].q||[]).push(arguments),e[c].u=e[c].u||n};var i=t.createElement(a),o=t.getElementsByTagName(a)[0];i.async=!0,i.src=n+"analytics/banner.js",i.id=s,!t.getElementById(s)&&o.parentNode.insertBefore(i,o)}(window,document,"script","' . $URL_AFFILIATES_API . '","ba","bafTrSc"),ba("_setUrl","' . "https://api.doradobet.com/affiliates/" . '"),ba("_setAccount",' . $account . '),ba("_mId",' . $mId . ');</script><div data-ti="' . $account . '_' . $mId . '"></div>';

    return $script;
}


/**
 * Encripta una cadena de texto utilizando el algoritmo AES-128-CTR.
 *
 * @param string $data Cadena de texto a encriptar.
 * @param string $encryption_key Clave de encriptación (opcional).
 * @return string Cadena encriptada.
 */
function encrypt($data, $encryption_key = "")
{
    $passEncryt = 'li1296-151.members.linode.com|3232279913';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', $passEncryt, 0, $iv);
    $encrypted_string = str_replace("/", "vSfTp", $encrypted_string);
    return $encrypted_string;
}

/**
 * Encripta una cadena de texto utilizando el algoritmo AES-128-CTR con un IV fijo.
 *
 * @param string $data Cadena de texto a encriptar.
 * @param string $encryption_key Clave de encriptación.
 * @return string Cadena encriptada.
 */
function encrypt2($data, $encryption_key = "")
{
    print_r(gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']));
    print_r("TEST");
    $iv = '1234Dorado';
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', $encryption_key, 0, $iv);
    return $encrypted_string;
}

/**
 * Desencripta una cadena de texto previamente encriptada con AES-128-CTR.
 *
 * @param string $data Cadena de texto encriptada.
 * @param string $encryption_key Clave de encriptación (opcional).
 * @return string|false Cadena desencriptada o FALSE si falla.
 */
function decrypt($data, $encryption_key = "")
{
    $data = str_replace("vSfTp", "/", $data);
    $passEncryt = 'li1296-151.members.linode.com|3232279913';

    if ($data == "a17627d4cddfa7086c831da71e08701fMiw%209oHr3wpioQ%3D%3D" || $data == "a17627d4cddfa7086c831da71e08701fMiw 9oHr3wpioQ==" || $data == "a17627d4cddfa7086c831da71e08701fMiw 9oHr3wpioQ%3D%3D") {
        $data = "73543a712cc0221442390a0d05f508ebYV5AXzWRDutCdw==";
    }

    $iv_strlen = 2 * openssl_cipher_iv_length('AES-128-CTR');
    if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
        list(, $iv, $crypted_string) = $regs;
        $decrypted_string = openssl_decrypt($crypted_string, 'AES-128-CTR', $passEncryt, 0, hex2bin($iv));
        return $decrypted_string;
    } else {
        return FALSE;
    }
}

/**
 * Desencripta una cadena de texto previamente encriptada con AES-128-CTR utilizando un IV fijo.
 *
 * @param string $data Cadena de texto encriptada.
 * @param string $encryption_key Clave de encriptación.
 * @return string|false Cadena desencriptada o FALSE si falla.
 */
function decrypt2($data, $encryption_key = "")
{
    $iv_strlen = 2 * openssl_cipher_iv_length('AES-128-CTR');
    if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
        list(, $iv, $crypted_string) = $regs;
        $decrypted_string = openssl_decrypt($crypted_string, 'AES-128-CTR', "li1296-151.members.linode.com|3232288592", 0, hex2bin($iv));
        return $decrypted_string;
    } else {
        return FALSE;
    }
}

/**
 * Genera una clave aleatoria de longitud específica compuesta únicamente por números.
 *
 * @param int $length Longitud de la clave a generar.
 * @return string Clave generada aleatoriamente.
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

/**
 * Enviar un correo
 *
 * @param String $c_address c_address
 * @param String $c_from c_from
 * @param String $c_fromname c_fromname
 * @param String $c_subject c_subject
 * @param String $c_include c_include
 * @param String $c_mensaje c_mensaje
 * @param String $c_dominio c_dominio
 * @param String $c_compania c_compania
 * @param String $c_color c_color
 *
 * @return boolean $ resultado de la operación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function EnviarCorreo2($c_address, $c_from, $c_fromname, $c_subject, $c_include, $c_mensaje, $c_dominio, $c_compania, $c_color)
{


    print_r("entroo");
    require("../src/imports/phpmailer/class.phpmailer.php");
    require("../src/imports/phpmailer/class.smtp.php");
    print_r("entroo2");


    //Crea las instancias y el cuerpo del correo
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = "localhost";
    $mail->SMTPDebug = 0;
    $mail->From = $c_from;
    $mail->FromName = $c_fromname;
    $mail->Subject = $c_subject;

    $correo_mensaje = $c_mensaje;
    $correo_dominio = $c_dominio;
    $correo_compania = $c_compania;
    $correo_color = $c_color;

    $message = '<html>

<head>
    <title><? echo $correo_compania;?> - Registro</title>
    <script src="https://use.fontawesome.com/cf4a881f9a.js"></script>
    <style type="text/css" media="screen">
        .blanco {
            color: #579438;
        }

        a.blanco:hover{
            color: silver;
        }
    </style>
</head>

<body>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
    <tbody>
    <tr style="background: #434041;">
        <td align="left" valign="top">
            <img src="https://doradobet.com/assets/images/logo.png" width="250" height="250" align="right" style="/* margin-left:10px; *//* position: absolute; *//* margin-top: -120px; */width: 120px;height: 100px;float: left;/* display: inline-block; *//* left: 0px; *//* position: relative; */" "="">
        </td>
    </tr>
    <tr>
        <td align="center" valign="top" bgcolor="#f0f0f0" style="background-color:#f0f0f0; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000000; padding:10px 10px 50px 10px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:40px;">
                <tbody>
                <tr>
                    <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#525252;">

                        <div style="font-size:28px;">Registro - <? echo $correo_dominio;?></div>
                        <br>
                        <div><? echo $correo_mensaje;?></div>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td align="left" valign="top" style="background: #DAA903;" class="gradient">
            <table width="100%" border="0" cellspacing="0" cellpadding="15">
                <tbody>
                <tr>
                    <td align="left" valign="top" style="color:#ffffff; font-family:Arial, Helvetica, sans-serif; font-size:13px;"
                        class="success">Atentamente,
                        <br>Servicio al Cliente
                        <br>Sitio Web: <a href="https://<? echo $correo_dominio;?>" target="_blank" style="color:#ffffff; text-decoration:none;">https://<? echo $correo_dominio;?></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>

</html>
';
    $mail->msgHTML($message);
    $mail->AddAddress($c_address, $c_address);
    $mail->SMTPAuth = false;

    //Verifica si el correo se envió satisfactoriamente
    $enviado = false;
    if ($mail->Send()) {
        print_r("RESP2");
        $enviado = true;

    }

    //Retorna la respuesta
    print_r("RESP");
    print_r($enviado);
}


/**
 * Enviar un correo
 *
 * @param String $c_address c_address
 * @param String $c_from c_from
 * @param String $c_fromname c_fromname
 * @param String $c_subject c_subject
 * @param String $c_include c_include
 * @param String $c_title c_title
 * @param String $c_mensaje c_mensaje
 * @param String $c_dominio c_dominio
 * @param String $c_compania c_compania
 * @param String $c_color c_color
 *
 * @return boolean $ resultado de la operación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function EnviarCorreo($c_address, $c_from, $c_fromname, $c_subject, $c_include, $c_title, $c_mensaje, $c_dominio, $c_compania, $c_color)
{


    require("../src/imports/phpmailer/class.phpmailer.php");
    require("../src/imports/phpmailer/class.smtp.php");


    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'localhost';  // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;                               // Enable SMTP authentication
        //$mail->From = 'aa@aa.com';
        //$mail->FromName = "daniel";
        $mail->Subject = "tEST";
        $mail->SMTPDebug = 1;

        //Recipients
        $mail->setFrom($c_from, $c_fromname);
        $mail->addAddress($c_address, $c_address);     // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        $message = '
        <!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style>
		@import url(https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css);

		body {
			font-family: \'Roboto\', sans-serif;
			text-decoration: none;
			font-size: 14px;
		}
		p {
			padding: 2rem;
			margin: 0;
		}
		.container {
			height: 600px;
			width: 100%;
		}
		.container .header{
			height: 330px;
			width: 100%;

		}
		.container .header div{
			height: 330px;
			background-size:  auto 102%;
			background-repeat: no-repeat;
			background-position: bottom;
		}


		.contain{
			height: auto;
		}
		.contain p{
			text-align: center;
			color: grey;
			line-height: 20px;
			padding-top: 1rem;
			padding-bottom: 1rem;
		}
		.contain h1{
			text-align: center;
			color: #b48303;
			margin: 0;
			padding-top: 15px;
		}

		.footer{
			height: 50px;
			background: #b48303;
		}
		.contain .social {
			height: 40px;
		}
		.contain div:first-child{
			height: auto;
		}
		.contain .social #l1 li{
			display:list-item;
			xlist-style:none;
		}

		.contain .social #l2 li{
			display: inline;
		}

		.contain .social #l1, .contain .social #l2{
			text-align: center;
			padding: 0;
			margin: 0;
		}

		.contain .social .social-icons li {
			font-size: 1.2em;
			padding: 0.8em;
			margin: 0;
		}
		.contain .social .social-icons a{
			color: #848484;
		}
		.footer p {
			font-weight: 300;
			text-align: center;
			color: white;
			padding: 1rem 2rem;
		}
	</style>
</head>
<body style="    max-width: 500px;">
	<div class="container">
		<div class="header" style="height: auto;">
<img sr="" src="https://images.doradobet.com/site/doradobet/email/bg.jpg" style="
    width: 100%;
">		
		</div>

		<div class="contain">
			<div>
				<h1>' . $c_title . '</h1>
				<p>' . $c_mensaje . '</p>
			</div>
			
			<div class="social">
				<ul class="social-icons" id="l2">
					  <li><a target="_blank" href="https://www.facebook.com/doradobetcom/" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-facebook" ><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 96.227 96.227" style="enable-background:new 0 0 96.227 96.227;" xml:space="preserve">
<g>
	<path d="M73.099,15.973l-9.058,0.004c-7.102,0-8.477,3.375-8.477,8.328v10.921h16.938l-0.006,17.106H55.564v43.895H37.897V52.332   h-14.77V35.226h14.77V22.612C37.897,7.972,46.84,0,59.9,0L73.1,0.021L73.099,15.973L73.099,15.973z" fill="#084848"/>
</g>

</svg>
					  </i></a></li>
					  <li><a target="_blank" href="https://twitter.com/doradobet/" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-twitter"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 94.135 94.135" style="enable-background:new 0 0 94.135 94.135;" xml:space="preserve">
<g>
	<path d="M39.11,67.145c2.201,2.27,4.872,3.404,8.011,3.404h22.612c3.135,0,5.83,1.159,8.072,3.475   c2.245,2.312,3.364,5.084,3.364,8.32s-1.119,6.018-3.364,8.324c-2.242,2.311-4.928,3.467-8.07,3.467H47.131   c-9.416,0-17.462-3.445-24.143-10.344c-6.686-6.895-10.026-15.202-10.026-24.919v-47.07c0-3.329,1.114-6.13,3.34-8.4   C18.527,1.136,21.247,0,24.457,0c3.115,0,5.796,1.155,8.016,3.473c2.229,2.309,3.344,5.081,3.344,8.321v11.791h33.885   c3.148,0,5.847,1.158,8.098,3.471c2.253,2.311,3.373,5.086,3.373,8.325c0,3.233-1.12,6.009-3.365,8.321   c-2.242,2.311-4.936,3.468-8.072,3.468H35.814v11.691C35.814,62.107,36.911,64.867,39.11,67.145z" fill="#084848"/>
</g>

</svg>
					  </i></a></li>
					  <li><a target="_blank" href="https://www.instagram.com/doradobetlatam/" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-instagram"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 97.395 97.395" style="enable-background:new 0 0 97.395 97.395;" xml:space="preserve">
<g>
	<path d="M12.501,0h72.393c6.875,0,12.5,5.09,12.5,12.5v72.395c0,7.41-5.625,12.5-12.5,12.5H12.501C5.624,97.395,0,92.305,0,84.895   V12.5C0,5.09,5.624,0,12.501,0L12.501,0z M70.948,10.821c-2.412,0-4.383,1.972-4.383,4.385v10.495c0,2.412,1.971,4.385,4.383,4.385   h11.008c2.412,0,4.385-1.973,4.385-4.385V15.206c0-2.413-1.973-4.385-4.385-4.385H70.948L70.948,10.821z M86.387,41.188h-8.572   c0.811,2.648,1.25,5.453,1.25,8.355c0,16.2-13.556,29.332-30.275,29.332c-16.718,0-30.272-13.132-30.272-29.332   c0-2.904,0.438-5.708,1.25-8.355h-8.945v41.141c0,2.129,1.742,3.872,3.872,3.872h67.822c2.13,0,3.872-1.742,3.872-3.872V41.188   H86.387z M48.789,29.533c-10.802,0-19.56,8.485-19.56,18.953c0,10.468,8.758,18.953,19.56,18.953   c10.803,0,19.562-8.485,19.562-18.953C68.351,38.018,59.593,29.533,48.789,29.533z" fill="#084848"/>
</g>
<g>
</g>
</svg>
					  </i></a></li>
					  <li><a target="_blank" href="https://www.youtube.com/channel/UCuxJjrf89zWId29oOBq7Iqg" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-youtube"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 90.677 90.677" style="enable-background:new 0 0 90.677 90.677;" xml:space="preserve">
<g>
	<g>
		<path d="M82.287,45.907c-0.937-4.071-4.267-7.074-8.275-7.521c-9.489-1.06-19.098-1.065-28.66-1.06    c-9.566-0.005-19.173,0-28.665,1.06c-4.006,0.448-7.334,3.451-8.27,7.521c-1.334,5.797-1.35,12.125-1.35,18.094    c0,5.969,0,12.296,1.334,18.093c0.936,4.07,4.264,7.073,8.272,7.521c9.49,1.061,19.097,1.065,28.662,1.061    c9.566,0.005,19.171,0,28.664-1.061c4.006-0.448,7.336-3.451,8.272-7.521c1.333-5.797,1.34-12.124,1.34-18.093    C83.61,58.031,83.62,51.704,82.287,45.907z M28.9,50.4h-5.54v29.438h-5.146V50.4h-5.439v-4.822H28.9V50.4z M42.877,79.839h-4.629    v-2.785c-1.839,2.108-3.585,3.136-5.286,3.136c-1.491,0-2.517-0.604-2.98-1.897c-0.252-0.772-0.408-1.994-0.408-3.796V54.311    h4.625v18.795c0,1.084,0,1.647,0.042,1.799c0.111,0.718,0.462,1.082,1.082,1.082c0.928,0,1.898-0.715,2.924-2.166v-19.51h4.629    L42.877,79.839L42.877,79.839z M60.45,72.177c0,2.361-0.159,4.062-0.468,5.144c-0.618,1.899-1.855,2.869-3.695,2.869    c-1.646,0-3.234-0.914-4.781-2.824v2.474h-4.625V45.578h4.625v11.189c1.494-1.839,3.08-2.769,4.781-2.769    c1.84,0,3.078,0.969,3.695,2.88c0.311,1.027,0.468,2.715,0.468,5.132V72.177z M77.907,67.918h-9.251v4.525    c0,2.363,0.773,3.543,2.363,3.543c1.139,0,1.802-0.619,2.066-1.855c0.043-0.251,0.104-1.279,0.104-3.134h4.719v0.675    c0,1.491-0.057,2.518-0.099,2.98c-0.155,1.024-0.519,1.953-1.08,2.771c-1.281,1.854-3.179,2.768-5.595,2.768    c-2.42,0-4.262-0.871-5.599-2.614c-0.981-1.278-1.485-3.29-1.485-6.003v-8.941c0-2.729,0.447-4.725,1.43-6.015    c1.336-1.747,3.177-2.617,5.54-2.617c2.321,0,4.161,0.87,5.457,2.617c0.969,1.29,1.432,3.286,1.432,6.015v5.285H77.907z" fill="#084848"/>
		<path d="M70.978,58.163c-1.546,0-2.321,1.181-2.321,3.541v2.362h4.625v-2.362C73.281,59.344,72.508,58.163,70.978,58.163z" fill="#084848"/>
		<path d="M53.812,58.163c-0.762,0-1.534,0.36-2.307,1.125v15.559c0.772,0.774,1.545,1.14,2.307,1.14    c1.334,0,2.012-1.14,2.012-3.445V61.646C55.824,59.344,55.146,58.163,53.812,58.163z" fill="#084848"/>
		<path d="M56.396,34.973c1.705,0,3.479-1.036,5.34-3.168v2.814h4.675V8.82h-4.675v19.718c-1.036,1.464-2.018,2.188-2.953,2.188    c-0.626,0-0.994-0.37-1.096-1.095c-0.057-0.153-0.057-0.722-0.057-1.817V8.82h-4.66v20.4c0,1.822,0.156,3.055,0.414,3.836    C53.854,34.363,54.891,34.973,56.396,34.973z" fill="#084848"/>
		<path d="M23.851,20.598v14.021h5.184V20.598L35.271,0h-5.242l-3.537,13.595L22.812,0h-5.455c1.093,3.209,2.23,6.434,3.323,9.646    C22.343,14.474,23.381,18.114,23.851,20.598z" fill="#084848"/>
		<path d="M42.219,34.973c2.342,0,4.162-0.881,5.453-2.641c0.981-1.291,1.451-3.325,1.451-6.067v-9.034    c0-2.758-0.469-4.774-1.451-6.077c-1.291-1.765-3.11-2.646-5.453-2.646c-2.33,0-4.149,0.881-5.443,2.646    c-0.993,1.303-1.463,3.319-1.463,6.077v9.034c0,2.742,0.47,4.776,1.463,6.067C38.069,34.092,39.889,34.973,42.219,34.973z     M39.988,16.294c0-2.387,0.724-3.577,2.231-3.577c1.507,0,2.229,1.189,2.229,3.577v10.852c0,2.387-0.722,3.581-2.229,3.581    c-1.507,0-2.231-1.194-2.231-3.581V16.294z" fill="#084848"/>
	</g>
</g>

</svg>
					  </i></a></li>
					  <li><a target="_blank" href="https://plus.google.com/u/0/109119436366679125879/" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-google-plus"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 96.669 96.669" style="enable-background:new 0 0 96.669 96.669;" xml:space="preserve">
<g>
	<g>
		<path d="M50.91,55.189l-4.533-3.522c-1.38-1.144-3.27-2.656-3.27-5.422c0-2.778,1.889-4.544,3.527-6.18    c5.279-4.157,10.557-8.581,10.557-17.903c0-9.586-6.031-14.629-8.923-17.022h7.795L64.244,0H39.459    C32.658,0,22.856,1.608,15.68,7.533c-5.408,4.666-8.046,11.099-8.046,16.892c0,9.831,7.548,19.798,20.88,19.798    c1.259,0,2.636-0.124,4.022-0.252c-0.623,1.515-1.252,2.777-1.252,4.917c0,3.905,2.006,6.299,3.774,8.567    c-5.663,0.39-16.237,1.018-24.033,5.809c-7.424,4.415-9.684,10.84-9.684,15.377c0,9.334,8.8,18.028,27.045,18.028    c21.636,0,33.089-11.971,33.089-23.823C61.477,64.139,56.447,59.854,50.91,55.189z M34.431,40.691    c-10.824,0-15.727-13.992-15.727-22.434c0-3.288,0.623-6.682,2.763-9.333C23.486,6.4,27,4.762,30.281,4.762    c10.434,0,15.846,14.118,15.846,23.197c0,2.271-0.251,6.296-3.144,9.207C40.96,39.187,37.574,40.691,34.431,40.691z     M34.555,91.387c-13.46,0-22.139-6.438-22.139-15.392c0-8.949,8.048-11.978,10.816-12.979c5.281-1.777,12.076-2.024,13.21-2.024    c1.258,0,1.887,0,2.889,0.126c9.568,6.81,13.721,10.203,13.721,16.65C53.053,85.573,46.635,91.387,34.555,91.387z" fill="#084848"/>
		<polygon points="82.679,40.499 82.679,27.894 76.455,27.894 76.455,40.499 63.869,40.499 63.869,46.793 76.455,46.793     76.455,59.477 82.679,59.477 82.679,46.793 95.328,46.793 95.328,40.499   " fill="#084848"/>
	</g>
</g>

</svg></i></a></li>
				</ul>
			</div>	
		</div>

		<div class="footer">
				<p>© 2017 Doradobet. Todos los derechos reservados.</p>			
		</div>
	</div>
</body>
</html>

';
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $c_subject;
        //$mail->Body = 'This is the HTML message body <b>in bold!</b>';
        $mail->msgHTML($message);

        $ret = $mail->send();

        return true;
    } catch (Exception $e) {

        return false;
    }

}

/**
 * Función obtenerMenu
 *
 * Esta función genera un conjunto de permisos y menús basados en el perfil y usuario actual.
 * Realiza múltiples consultas y transformaciones para construir una estructura jerárquica de menús, submenús y permisos.
 *
 * @return array Retorna un arreglo con todos los permisos y menús procesados.
 *
 * Detalles del proceso:
 * - Obtiene menús y submenús asociados al perfil del usuario desde la base de datos.
 * - Aplica reglas específicas según el perfil y mandante del usuario.
 * - Filtra y organiza los datos en una estructura jerárquica.
 * - Excluye ciertos menús y submenús según configuraciones predefinidas.
 * - Combina permisos genéricos y específicos del usuario.
 * - Ordena los menús y submenús por su orden definido.
 * - Devuelve una lista plana de permisos y menús, eliminando datos innecesarios.
 *
 * Notas:
 * - Utiliza objetos como `PerfilSubmenu` y `UsuarioMandante` para realizar consultas personalizadas.
 * - Maneja condiciones específicas para perfiles personalizados y mandantes particulares.
 * - Incluye lógica para evitar duplicados y asegurar que los permisos genéricos se integren correctamente.
 *
 * Variables de sesión utilizadas:
 * - `$_SESSION['win_perfil']`: Perfil principal del usuario.
 * - `$_SESSION['win_perfil2']`: Perfil secundario del usuario.
 * - `$_SESSION['usuario']`: ID del usuario actual.
 * - `$_SESSION['usuario2']`: ID alternativo del usuario.
 * - `$_SESSION['mandante']`: Mandante actual del usuario.
 * - `$_SESSION['PaisCond']`: Condición del país.
 * - `$_SESSION['PaisCondS']`: País alternativo.
 *
 * Exclusiones:
 * - Menús excluidos: `['Afiliados']`.
 * - Submenús excluidos: `['Herramientas', 'Maquinas', 'Mensajes', 'Contabilidad', 'Mi Configuracion']`.
 */
function obtenerMenu()
{

    $PerfilSubmenu = new PerfilSubmenu();

    $SkeepRows = 0;
    $OrderItems = 1;
    $MaxRows = 100000;

    $rules = [];

    array_push($rules, ['field' => 'perfil_submenu.perfil_id', 'data' => $_SESSION['win_perfil'], 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
    array_push($rules, ['field' => 'perfil_submenu.usuario_id', 'data' => '0', 'op' => 'eq']);

    if ($_SESSION['win_perfil'] == "CUSTOM") {
        array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
    } else{
        if ($_SESSION["win_perfil2"] != "SA") {
            if ($_SESSION["mandante"] == '6' && ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO")) {
                array_push($rules, array("field" => "perfil_submenu.mandante", "data" => '6', "op" => "eq"));
            }else{
                if ($_SESSION["mandante"] == '8' && $_SESSION["win_perfil2"] == "PUNTOVENTA" ){
                    array_push($rules, array("field" => "perfil_submenu.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));

                }elseif ($_SESSION["mandante"] == '16' && $_SESSION["win_perfil2"] == "PUNTOVENTA" ){
                    array_push($rules, array("field" => "perfil_submenu.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));

                }else{
                    array_push($rules, array("field" => "perfil_submenu.mandante", "data" => '-1', "op" => "eq"));

                }
            }

        }else{
            array_push($rules, array("field" => "perfil_submenu.mandante", "data" => '-1', "op" => "eq"));

        }
    }

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $query_permissions = $PerfilSubmenu->getPerfilSubmenusCustom('menu.*, submenu.*, perfil_submenu.*', 'submenu.submenu_id', 'asc', 0, 1000000, $filters, true);

    $query_permissions = json_decode($query_permissions);

    $rules = [];

    array_push($rules, ['field' => 'perfil_submenu.perfil_id', 'data' => 'CUSTOM', 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
    array_push($rules, ['field' => 'perfil_submenu.usuario_id', 'data' => $_SESSION['usuario'], 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $query_permissions_custom = $PerfilSubmenu->getPerfilSubmenusCustom('menu.*, submenu.*, perfil_submenu.*', 'submenu.submenu_id', 'asc', 0, 1000000, $filters, true);

    $query_permissions_custom = json_decode($query_permissions_custom);

    foreach($query_permissions_custom->data as $key => $value) {
        if(!in_array($value->{'submenu.submenu_id'}, array_column(json_decode(json_encode($query_permissions->data),true), 'submenu.submenu_id'))) {
            array_push($query_permissions->data, $value);
        }
    }

    $menus = array_filter($query_permissions->data, function($item) {
        if(empty($item->{'submenu.descripcion'}) and empty($item->{'submenu.pagina'})) return $item;
    });

    $permissions_menu = [];

    foreach($menus as $key => $value) {
        $data = [];
        $data['index'] = $value->{'submenu.submenu_id'};
        $data['id'] = $value->{'menu.pagina'};
        $data['value'] = $value->{'menu.descripcion'};
        $data['icon'] = $value->{'menu.icon'};
        $data['order'] = $value->{'menu.orden'};
        $data['menu'] = $value->{'menu.menu_id'};
        $data['add'] = ($value->{'perfil_submenu.adicionar'}== "true") ? true : false;
        $data['edit'] = ($value->{'perfil_submenu.editar'}== "true") ? true : false;
        $data['delete'] = ($value->{'perfil_submenu.eliminar'}== "true") ? true : false;

        array_push($permissions_menu, $data);
    }

    $submenus = array_filter($query_permissions->data, function($item) {
        if(!empty($item->{'submenu.descripcion'}) and $item->{'submenu.parent'} == 0) return $item;
    });

    $permissions_submenu = [];

    foreach($submenus as $key => $value) {
        $data = [];
        $data['index'] = $value->{'submenu.submenu_id'};
        $data['id'] = $value->{'submenu.pagina'};
        $data['value'] = $value->{'submenu.descripcion'};
        $data['menu'] = $value->{'submenu.menu_id'};
        $data['order'] = $value->{'submenu.orden'};
        $data['add'] = ($value->{'perfil_submenu.adicionar'}== "true") ? true : false;
        $data['edit'] = ($value->{'perfil_submenu.editar'}== "true") ? true : false;
        $data['delete'] = ($value->{'perfil_submenu.eliminar'}== "true") ? true : false;

        array_push($permissions_submenu, $data);
    }

    $permissions = array_filter($query_permissions->data, function($item) {
        if($item->{'submenu.orden'} == 0 and $item->{'submenu.parent'} != 0) return $item;
    });

    $sub_permissions = [];

    foreach($permissions as $key => $value) {
        $data = [];
        $data['index'] = $value->{'submenu.submenu_id'};
        $data['id'] = $value->{'submenu.pagina'};
        $data['value'] = $value->{'submenu.descripcion'};
        $data['parent'] = $value->{'submenu.parent'};
        $data['add'] = ($value->{'perfil_submenu.adicionar'}== "true") ? true : false;
        $data['edit'] = ($value->{'perfil_submenu.editar'}== "true") ? true : false;
        $data['delete'] = ($value->{'perfil_submenu.eliminar'}== "true") ? true : false;

        array_push($sub_permissions, $data);
    }

    $all_permissions = [];

    $rules = [];

    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

    if($_SESSION['PaisCond'] === 'S') {
        $pais_id = $UsuarioMandante->getPaisId();
    } else {
        $pais_id = !empty($_SESSION['PaisCondS']) ? $_SESSION['PaisCondS'] : '0';
    }

    array_push($rules, ['field' => 'reporte_dinamico.pais_id', 'data' => $pais_id, 'op' => 'eq']);
    array_push($rules, ['field' => 'reporte_dinamico.mandante', 'data' => $_SESSION['mandante'], 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);



    $excludeMenus = ['Afiliados'];
    $excludeSubMenus = ['Herramientas', 'Maquinas', 'Mensajes', 'Contabilidad', 'Mi Configuracion'];

    foreach($permissions_menu as $key => $value) {
        $data = [];
        $data['id'] = $value['id'];
        $data['value'] = $value['value'];
        $data['icon'] = $value['icon'];
        $data['order'] = $value['order'];
        $data['add'] = ($value['add']== "true") ? true : false;
        $data['edit'] = ($value['edit']== "true") ? true : false;
        $data['delete'] = ($value['delete']== "true") ? true : false;
        $data['show'] = in_array($value['value'], $excludeMenus) ? false : true;
        $data['data'] = [];

        $sub = array_filter($permissions_submenu, function($item) use($value) {
            if($value['menu'] === $item['menu']) return $item;
        });


        if(oldCount($sub) > 0) {
            foreach($sub as $key => $sub_value) {

                $sub_data = [];
                $sub_data['id'] = $sub_value['id'];
                $sub_data['value'] = $sub_value['value'];
                $sub_data['order'] = $sub_value['order'];
                $sub_data['add'] = ($sub_value['add']== "true") ? true : false;
                $sub_data['edit'] = ($sub_value['edit']== "true") ? true : false;
                $sub_data['delete'] = ($sub_value['delete']== "true") ? true : false;
                if(preg_match('/([0-9])+/', $sub_value['value']) == true) $sub_data['value'] = preg_replace('/([0-9])+/', '-', $sub_data['value']);
                if(in_array($data['value'], $excludeSubMenus)) {
                    $sub_data['show'] = true;
                } else {
                    $sub_data['show'] = strpos($sub_value['id'], '.') ? false : true;
                }

                $sub_data['data'] = [];

                $perms = array_filter($sub_permissions, function($item) use($sub_value) {
                    if($sub_value['index'] === $item['parent']) return $item;
                });



                if(oldCount($perms) > 0) {
                    foreach($perms as $key => $perm_value) {
                        $perm_data = [];
                        $perm_data['id'] = $perm_value['id'];
                        $perm_data['value'] = $perm_value['value'];
                        $perm_data['add'] = ($perm_value['add']== "true") ? true : false;
                        $perm_data['edit'] = ($perm_value['edit']== "true") ? true : false;
                        $perm_data['delete'] = ($perm_value['delete']== "true") ? true : false;
                        $perm_data['show'] = false;



                        array_push($sub_data['data'], $perm_data);
                    }
                }

                array_push($data['data'], $sub_data);
            }
        } else {
            $perms = array_filter($sub_permissions, function($item) use($value) {
                if($value['index'] === $item['parent']) return $item;
            });

            if(oldCount($perms) > 0) {
                foreach($perms as $key => $perm_value) {
                    $perm_data = [];
                    $perm_data['id'] = $perm_value['id'];
                    $perm_data['value'] = $perm_value['value'];
                    $perm_data['add'] = ($perm_value['add']== "true") ? true : false;
                    $perm_data['edit'] = ($perm_value['edit']== "true") ? true : false;
                    $perm_data['delete'] = ($perm_value['delete']== "true") ? true : false;
                    $perm_data['show'] = false;


                    array_push($data['data'], $perm_data);
                }


            }
        }

        array_push($all_permissions, $data);
    }

    $rules = [];

    array_push($rules, ['field' => 'perfil_submenu.perfil_id', 'data' => $_SESSION['win_perfil'], 'op' => 'eq']);
    array_push($rules, ['field' => 'perfil_submenu.usuario_id', 'data' => '0', 'op' => 'eq']);
    array_push($rules, ['field' => 'perfil_submenu.mandante', 'data' => '-1', 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.menu_id', 'data' => '0', 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.orden', 'data' => '0', 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $query_generic = $PerfilSubmenu->getPerfilGenericCustom('submenu.*, perfil_submenu.*', 'submenu.submenu_id', 'asc', 0, 1000000, $filters, true);

    $query_generic = json_decode($query_generic);

    $rules = [];

    array_push($rules, ['field' => 'perfil_submenu.perfil_id', 'data' => 'CUSTOM', 'op' => 'eq']);
    array_push($rules, ['field' => 'perfil_submenu.usuario_id', 'data' => $_SESSION['usuario'], 'op' => 'eq']);
    //array_push($rules, ['field' => 'perfil_submenu.mandante', 'data' => '-1', 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.menu_id', 'data' => '0', 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.orden', 'data' => '0', 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $query_generic_customs = $PerfilSubmenu->getPerfilGenericCustom('submenu.*, perfil_submenu.*', 'submenu.submenu_id', 'asc', 0, 1000000, $filters, true);

    $query_generic_customs = json_decode($query_generic_customs);

    if(oldCount($query_generic_customs->data) > 0) $query_generic->data = array_merge($query_generic->data, $query_generic_customs->data);

    $all_generic_permissions = [];

    foreach($query_generic->data as $key => $value) {
        $data = [];
        $data['index'] = $value->{'submenu.submenu_id'};
        $data['id'] = $value->{'submenu.pagina'};
        $data['value'] = $value->{'submenu.descripcion'};
        $data['add'] = ($value->{'perfil_submenu.adicionar'}== "true") ? true : false;
        $data['edit'] = ($value->{'perfil_submenu.editar'}== "true") ? true : false;
        $data['delete'] = ($value->{'perfil_submenu.eliminar'}== "true") ? true : false;
        $data['show'] = false;
        $newPerm = true;

        if(array_search($value->{'submenu.parent'}, array_column($all_generic_permissions, 'index')) !== false) {
            $index = array_search($value->{'submenu.parent'}, array_column($all_generic_permissions, 'index'));
            if(!isset($all_generic_permissions[$index]['data'])) $all_generic_permissions[$index]['data'] = [];
            array_push($all_generic_permissions[$index]['data'], $data);
            $newPerm = false;
        }

        if($newPerm) array_push($all_generic_permissions, $data);
    }

    $all_generic_permissions = array_map(function($item) {
        unset($item['index']);
        if(isset($item['data'])) {
            $item['data'] = array_map(function($sub_item) {
                unset($sub_item['index']);
                return $sub_item;
            }, $item['data']);
        }
        return $item;
    }, $all_generic_permissions);

    usort($all_permissions, function($a, $b) {
        if($a['order'] > $b['order']) return 1;

        return 0;
    });

    $all_permissions = array_map(function($item) {
        if(oldCount($item['data']) > 0) {
            usort($item['data'], function($a, $b) {
                if($a['order'] > $b['order']) return 1;

                return 0;
            });

            $item['data'] = array_map(function($sub_item) {
                unset($sub_item['order']);
                return $sub_item;
            }, $item['data']);
        }
        unset($item['order']);
        return $item;
    },$all_permissions);

    if(oldCount($all_generic_permissions) > 0) $all_permissions = array_merge($all_permissions, $all_generic_permissions);


    $all_permissionsTemp =array();
    foreach ($all_permissions as $all_permission) {
        array_push($all_permissionsTemp, $all_permission);

        if (isset($all_permission['data'])) {
            foreach ($all_permission['data'] as $datum) {
                array_push($all_permissionsTemp, $datum);
                if (isset($datum['data'])) {
                    foreach ($datum['data'] as $datum2) {
                        array_push($all_permissionsTemp, $datum2);

                    }
                }
            }
        }
    }

    $all_permissionsTemp2 =array();

    foreach ($all_permissionsTemp as $all_permission) {

        if(isset($all_permission['data'])){
            $all_permission['data'] = array();

        }
        array_push($all_permissionsTemp2,$all_permission);

    }
    return ($all_permissionsTemp2);

}

/**
 * Función obtenerMenu2
 *
 * Esta función genera un menú dinámico basado en los permisos y configuraciones del usuario.
 * Utiliza datos codificados en JSON y datos obtenidos de la base de datos para construir
 * un menú estructurado con submenús y permisos específicos.
 *
 * @return array Retorna un arreglo con la estructura del menú generado.
 *
 * Detalles del proceso:
 * - Decodifica múltiples cadenas JSON que contienen configuraciones de menús.
 * - Obtiene los submenús y permisos asociados al perfil del usuario desde la base de datos.
 * - Filtra y organiza los menús y submenús según los permisos del usuario.
 * - Agrega permisos de acciones como agregar, editar y eliminar a cada elemento del menú.
 * - Combina los menús codificados en JSON con los obtenidos dinámicamente para generar el menú final.
 *
 * Variables importantes:
 * - `$Perfil_id`: Identificador del perfil del usuario.
 * - `$Usuario_id`: Identificador del usuario.
 * - `$MaxRows`, `$OrderedItem`, `$SkeepRows`: Parámetros de paginación y ordenamiento.
 * - `$menus_string`: Arreglo que contiene los identificadores de los menús permitidos.
 * - `$submenus`: Arreglo que contiene los submenús con sus permisos.
 *
 * Notas:
 * - Si el perfil del usuario es "CUSTOM", se filtran los menús específicos para ese usuario.
 * - Los permisos de los menús y submenús se determinan según los datos obtenidos de la base de datos.
 * - Los menús que no están permitidos para el usuario se eliminan de la estructura final.
 */
function obtenerMenu2()
{

    $menus_string = json_decode(
        '[
                {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
                
                {
                    "id": "productsFather", "icon": "icon-cubes", "value": "Products", "data": [
                        {"id": "providers", "value": "Providers"},
                        {"id": "products", "value": "Products"},
                        {"id": "partnersProducts", "value": "Partners Products"},
                        {"id": "partnersProductsCountry", "value": "Partners Products Country"},
                        {"id": "categories", "value": "Categories"},
                        {"id": "categoriesProducts", "value": "CategoriesProducts"}
                        
                    ]
                },
                {"id": "players", "icon": "icon-players", "value": "Players"},
                {
                    "id": "reports", "icon": "icon-pie-chart", "value": "Reports", "data": [
                        {"id": "depositReport", "value": "Deposit Report"},
                        {"id": "autoexclusionUsers", "value": "Autoexclusion Users"},
                        {"id": "casinoGamesReport", "value": "Casino Games Report"},
                        {"id": "bonusReport", "value": "Bonus Report"},
                        {"id": "playersReport", "value": "Players Report"},
                        {"id": "historicalCashFlow", "value": "Historical Cash Flow"},
                        {"id": "summaryCashFlow", "value": "Summary Cash Flow"},
                        {"id": "informeGerencial", "value": "Gerencial Report"},
                        {"id": "betsReport", "value": "Bets Report"}
                    ]
                },
                {
                    "id": "security", "icon": "icon-security", "value": "Security", "data": [
                        {"id": "adminUser", "value": "Admin User"},
                        {"id": "contingency", "value": "Contingency"},
                        {"id": "menus", "value": "Menus"},
                        {"id": "profile", "value": "Profile"},
                        {"id": "profileOptions", "value": "Profile - Options"},
                        {"id": "subMenu", "value": "Sub Menu"},
                        {"id": "userProfile", "value": "User Profile"}
                    ]
                },
                {
                    "id": "teacher", "icon": "icon-storage", "value": "Teacher", "data": [
                        {"id": "qualifying", "value": "Qualifying"},
                        {"id": "franchisee", "value": "Franchisee"},
                        {"id": "registeredDocuments", "value": "Registered Documents"}
                    ]
                },
                {
                    "id": "Management", "icon": "icon-database", "value": "Management", "data": [
                        {"id": "adjustPayment", "value": "Adjust Payment"},
                        {"id": "assignmentQuota", "value": "assignment Quota"},
                        {"id": "bonus", "value": "Bonus"},
                        {"id": "eliminateNoteWithdraw", "value": "Eliminate Note Withdraw"},
                        {"id": "managementNetwork", "value": "Management Network"},
                        {"id": "registerFast", "value": "Register Fast"},
                        {"id": "reprintCheck", "value": "Reprint Check"},
                        {"id": "reversionReload", "value": "Reversion Reload"},
                        {"id": "managementContact", "value": "Management Contact"}
                    ]
                },
                {
                    "id": "Cash", "icon": "icon-banknote", "value": "Cash", "data": [
                        {"id": "flujoCaja", "value": "Flujo de Caja"},
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"}
                    ]
                },
                {
                    "id": "queries", "icon": "icon-file-text", "value": "Queries", "data": [
                        {"id": "flujoCajaHistorico", "value": "Flujo Caja Historico"},
                        {"id": "flujoCajaResumido", "value": "Flujo Caja Resumido"},
                        {"id": "informeCasino", "value": "Informe Casino"},
                        {"id": "informeGerencial", "value": "Informe Gerencial"},
                        {"id": "listadoRecargasRetiros", "value": "Listado Recargas Retiros"},
                        {"id": "premiosPendientesPagar", "value": "Premios Pendientes Pagar"},
                        {"id": "consultaOnlineDetalle", "value": "Consulta Online Detalle"},
                        {"id": "consultaOnlineResumen", "value": "Consulta Online Resumen"}
                    ]
                },
                {
                    "id": "betShopManagement", "icon": "icon-shop", "value": "Bet Shop Management", "data": [
                        {"id": "betShop", "value": "Bet Shop"},
                        {"id": "managePointsGraphics", "value": "Manage Points Graphics"}
                    ]
                },
                {
                    "id": "agentSystem", "icon": "icon-user-secret", "value": "Agent System", "data": [
                        {"id": "myAccount", "value": "My Account"},
                        {"id": "agentList", "value": "Agent List"},
                        {"id": "agentsTree", "value": "Agents Tree"},
                        {"id": "subAccounts", "value": "Sub Accounts"},
                        {"id": "playersList", "value": "Players List"},
                        {"id": "transfers", "value": "Transfers"},
                        {"id": "groupManagement", "value": "Group Management"}
                    ]
                },
                {
                    "id": "financial", "icon": "icon-financial", "value": "Financial", "data": [
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"},
                        {"id": "depositRequests", "value": "Deposit Requests"},
                        {"id": "withdrawalRequests", "value": "Withdrawal Requests"},
                        {"id": "transactionss", "value": "Transactions"}
                    ]
                },
                {
                    "id": "tools", "icon": "icon-tools", "value": "Tools", "data": [
                        {"id": "partnerSettings", "value": "Partner Settings"},
                        {"id": "translationManager", "value": "Translation Manager"},
                        {"id": "emailTemplate", "value": "Email Template"},
                        {"id": "messagesList", "value": "Messages List"}
                    ]
                },

                {"id": "transactions", "value": "Transactions", "icon": "mdi mdi-cart"},
                {"id": "customers", "value": "Customers", "icon": "mdi mdi-account-box"},
                {"id": "payhistoryview", "value": "Payment History", "icon": "mdi mdi-chart-areaspline"},
                {"id": "widgets", "value": "Widgets", "icon": "mdi mdi-widgets"},
                {"id": "demos", "value": "Demos", "icon": "mdi mdi-monitor-dashboard"},
                {"id": "prices", "value": "Prices", "icon": "mdi mdi-currency-usd"},
                {"id": "tutorials", "value": "Tutorials", "icon": "mdi mdi-school"}
            ]'
    );

    $menu_string = json_decode(
        '[
                {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
{
                    "id": "myconfiguration", "icon": "icon-tools", "value": "Mi Configuracion", "data": [
                        {"id": "myConfiguration.myInformation", "value": "Mi Informacion"},
                        {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                        {"id": "myConfiguration.qrgoogle", "value": "QR Google"}
                        
                    ]
                },
                
                {
                    "id": "productsFather", "icon": "icon-cubes", "value": "Products", "data": [
                        {"id": "providers", "value": "Providers"},
                        {"id": "products", "value": "Products"},
                        {"id": "partnersProviders", "value": "Partners Proveedores"},
                        {"id": "partnersTypeProduct", "value": "Partners Tipo Producto"},
                        {"id": "partnersProducts", "value": "Partners Products"},
                        {"id": "partnersProductsCountry", "value": "Partners Products Country"},
                        {"id": "categories", "value": "Categories"},
                        {"id": "categoriesProducts", "value": "CategoriesProducts"}
                        
                    ]
                },
                {"id": "adminUserManagement", "icon": "icon-players", "show":"false", "value": "adminUserManagement"},
                {"id": "customers", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "customersAggregator", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "agentListManagement", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "addAgentListManagement", "icon": "icon-players", "show":"false", "value": "addAgentListManagement"},
                {"id": "addBetShopManagement", "icon": "icon-players", "show":"false", "value": "addBetShopManagement"},
                {"id": "addAdminUserManagement", "icon": "icon-players", "show":"false", "value": "addAdminUserManagement"},
                {"id": "settings", "icon": "icon-players", "show":"false", "value": "settings"},

                {"id": "playersList", "icon": "icon-players", "value": "Jugadores"},
                {"id": "playersListAggregator", "icon": "icon-players", "value": "Jugadores"},
                {
                    "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                        {"id": "depositReport", "value": "Reporte de depósitos"},
                        {"id": "autoexclusionUsers", "value": "Autoexclusiones de Usuario"},
                        {"id": "casinoGamesReport", "value": "Reporte de casino "},
                        {"id": "bonusReport", "value": "Reporte de bonos"},
                        {"id": "playersReport", "value": "Reporte de Jugadores"},
                        {"id": "historicalCashFlow", "value": "Flujo de Caja Histórico"},
                        {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                        {"id": "informeGerencial", "value": "Informe Gerencial"},
                        {"id": "betsReport", "value": "Reporte de Apuestas"},
                        {"id": "usuarioOnlineResumido", "value": "Usuario online Resumido"},
                        {"id": "relationUserAggregator", "value": "Usuario - Agregator"},
                        {"id": "paidPendingAwards", "value": "premiosPendientesPagar"}

                    ]
                },
                
                
                {
                    "id": "accounting", "icon": "icon-security", "value": "Contabilidad", "data": [
                        {"id": "accounting.costCenter", "value": "Centros de costo", "add": true},
                        {"id": "accounting.area", "value": "Area", "add": true},
                        {"id": "accounting.position", "value": "Cargo", "add": true},
                        {"id": "accounting.typeCenterPosition", "value": "Tipos", "add": true},
                        {"id": "accounting.employees", "value": "Empleados", "add": true},
                        {"id": "accounting.expenses", "value": "Egresos", "add": true},
                        {"id": "accounting.incomes", "value": "Ingresos", "add": true},
                        {"id": "accounting.providers", "value": "Proveedores terceros", "add": true},
                        {"id": "accounting.concepts", "value": "Conceptos", "add": true},
                        {"id": "accounting.accounts", "value": "Cuentas", "add": true},
                        {"id": "accounting.productsThirdBetShop", "value": "Productos terceros", "add": true},
                        {"id": "accounting.productsThirdByBetShop", "value": "Productos terceros Punto de venta", "add": true},
                        {"id": "accounting.closingDayReport", "value": "Reporte cierre de dia"},
                        {"id": "accounting.squareDayReport", "value": "Reporte de cuadre de dia", "add": true}

                    ]
                },
                {
                    "id": "security", "icon": "icon-security", "value": "Security", "data": [
                        {"id": "approvalLogs", "value": "Aprobar logs", "add": true},

                        {"id": "adminUser", "value": "Admin User"},
                        {"id": "usuariosbloqueados", "value": "Usuarios Bloqueados"},
                        {"id": "contingency", "value": "Contingency"},
                        {"id": "menus", "value": "Menus"},
                        {"id": "profile", "value": "Profile"},
                        {"id": "profileOptions", "value": "Profile - Options"},
                        {"id": "subMenu", "value": "Sub Menu"},
                        {"id": "userProfile", "value": "User Profile"},
                        {"id": "competitors.competitors", "value": "Competidores"}
                    ]
                },
                {
                    "id": "management", "icon": "icon-security", "value": "Gestion", "data": [
                        {"id": "activateRegistration", "value": "Activar Registros", "add": true}
                    ]
                },
                {
                    "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                        {"id": "flujoCaja", "value": "Flujo de Caja"},
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"}
                    ]
                },
                
                {
                    "id": "messages", "icon": "icon-pie-chart", "value": "Mensajes", "data": [
                        {"id": "messages.messageList", "value": "Lista"}
                                            ]
                },
                
                {
                    "id": "tools", "icon": "icon-pie-chart", "value": "Herramientas", "data": [
                        {"id": "tools.translationManager", "value": "Traducción"},
                                                {"id": "tools.uploadImage", "value": "Subir imagen"}

                                            ]
                },
                
                
                {
                    "id": "betShopManagement", "icon": "icon-shop", "value": "Gestión Punto de Venta", "data": [
                        {"id": "betShop", "value": "Punto de Venta"},
                        {"id": "cashiers", "value": "Cajeros"},
                        {"id": "managePointsGraphics", "value": "Gestión Puntos Gráfico"},
                        {"id": "betShopCompetence", "value": "Puntos de venta Competencia"}
                    ]
                },
                {
                    "id": "agentSystem", "icon": "icon-user-secret", "value": "Agentes", "data": [
                        {"id": "agentList", "value": "Lista de Agentes"},
                        {"id": "agentsTree", "value": "Árbol de Agentes"},
                        {"id": "agentsInform", "value": "Informe de Agentes"},
                        {"id": "agentTransfers", "value": "Transferencias"},
                        {"id": "agent.requestsAgent", "value": "Solicitudes"},
                        {"id": "agent.requirementsAgent", "value": "Requisitos"}

                    ]
                },
                {
                    "id": "financial", "icon": "icon-financial", "value": "Financiero", "data": [
                        {"id": "depositRequests", "value": "Solicitudes de Deposito"},
                        {"id": "withdrawalRequests", "value": "Solicitudes de Retiro"}
                    ]
                }   ,
                {"id": "closeBox", "icon": "icon-financial", "value": "Cierre de caja"}
                         ]'
    );

    $PerfilSubmenu = new PerfilSubmenu();

    $Perfil_id = $_SESSION["win_perfil2"];
    $Usuario_id = $_SESSION["usuario"];
    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 100000;
    }

    $mismenus = "0";

    $rules = [];

    array_push($rules, array("field" => "menu.version", "data" => "2", "op" => "eq"));
    array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => "$Perfil_id", "op" => "eq"));

    if ($Perfil_id == "CUSTOM") {
        array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => "$Usuario_id", "op" => "eq"));
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);



    $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $menus = json_decode($menus);

    $menus3 = [];
    $arrayf = [];
    $submenus = [];

    $menus_string = array();


    foreach ($menus->data as $key => $value) {

        $m = [];
        $m["Id"] = $value->{"menu.menu_id"};
        $m["Name"] = $value->{"menu.descripcion"};

        $array = [];

        $array["Id"] = $value->{"submenu.submenu_id"};
        $array["Name"] = $value->{"submenu.descripcion"};
        $array["Pagina"] = $value->{"submenu.pagina"};
        $array["IsGiven"] = true;
        $array["Action"] = "view";
        $array["add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
        $array["edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
        $array["delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;
        $array["Add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
        $array["Edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
        $array["Delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;

        $mismenus = $mismenus . "," . $array["Id"];

        if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
            array_push($menus_string, $arrayf["Pagina"]);

            $arrayf["Permissions"] = $submenus;
            array_push($menus3, $arrayf);
            // $submenus = [];
        }
        array_push($menus_string, $array["Pagina"]);

        $arrayf["Id"] = $value->{"menu.menu_id"};
        $arrayf["Name"] = $value->{"menu.descripcion"};
        $arrayf["Pagina"] = $value->{"menu.pagina"};

        array_push($submenus, $array);
    }
    array_push($menus_string, $arrayf["Pagina"]);


    if ($Perfil_id != "CUSTOM") {

        $rules = [];

        array_push($rules, array("field" => "menu.version", "data" => "2", "op" => "eq"));
        array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => "$Perfil_id", "op" => "eq"));

        array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => "$Usuario_id", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $menus = json_decode($menus);

        $menus3 = [];
        $arrayf = [];

        foreach ($menus->data as $key => $value) {

            $m = [];
            $m["Id"] = $value->{"menu.menu_id"};
            $m["Name"] = $value->{"menu.descripcion"};

            $array = [];

            $array["Id"] = $value->{"submenu.submenu_id"};
            $array["Name"] = $value->{"submenu.descripcion"};
            $array["Pagina"] = $value->{"submenu.pagina"};
            $array["IsGiven"] = true;
            $array["Action"] = "view";
            $array["add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
            $array["edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
            $array["delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;
            $array["Add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
            $array["Edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
            $array["Delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;

            $mismenus = $mismenus . "," . $array["Id"];

            if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
                array_push($menus_string, $arrayf["Pagina"]);

                $arrayf["Permissions"] = $submenus;
                array_push($menus3, $arrayf);
                // $submenus = [];
            }
            array_push($menus_string, $array["Pagina"]);

            $arrayf["Id"] = $value->{"menu.menu_id"};
            $arrayf["Name"] = $value->{"menu.descripcion"};
            $arrayf["Pagina"] = $value->{"menu.pagina"};

            array_push($submenus, $array);
        }
        array_push($menus_string, $arrayf["Pagina"]);


        $rules = [];

        array_push($rules, array("field" => "menu.version", "data" => "2", "op" => "eq"));
        array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => "CUSTOM", "op" => "eq"));

        array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => "$Usuario_id", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $menus = json_decode($menus);

        $menus3 = [];
        $arrayf = [];

        foreach ($menus->data as $key => $value) {

            $m = [];
            $m["Id"] = $value->{"menu.menu_id"};
            $m["Name"] = $value->{"menu.descripcion"};

            $array = [];

            $array["Id"] = $value->{"submenu.submenu_id"};
            $array["Name"] = $value->{"submenu.descripcion"};
            $array["Pagina"] = $value->{"submenu.pagina"};
            $array["IsGiven"] = true;
            $array["Action"] = "view";
            $array["add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
            $array["edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
            $array["delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;
            $array["Add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
            $array["Edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
            $array["Delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;

            $mismenus = $mismenus . "," . $array["Id"];

            if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
                array_push($menus_string, $arrayf["Pagina"]);

                $arrayf["Permissions"] = $submenus;
                array_push($menus3, $arrayf);
                // $submenus = [];
            }
            array_push($menus_string, $array["Pagina"]);

            $arrayf["Id"] = $value->{"menu.menu_id"};
            $arrayf["Name"] = $value->{"menu.descripcion"};
            $arrayf["Pagina"] = $value->{"menu.pagina"};

            array_push($submenus, $array);
        }
        array_push($menus_string, $arrayf["Pagina"]);


    }


    $submenus = json_decode(json_encode($submenus));


    foreach ($menu_string as $key => $item) {
        $continuar = true;

        if (!in_array($item->id, $menus_string)) {

            unset($menu_string[$key]);
            $continuar = false;

        } else {
            $searchedValue = $item->id;
            $item2 = reset(array_filter(
                $submenus,
                function ($e) use (&$searchedValue) {
                    return $e->Pagina == $searchedValue;
                }
            ));

            $item->add = $item2->add;
            $item->edit = $item2->edit;
            $item->delete = $item2->delete;
            $item->add = true;
            $item->edit = true;
            $item->delete = true;

        }

        if ($continuar) {
            if (oldCount($item->data) > 0) {

                foreach ($item->data as $key2 => $datum) {
                    if (!in_array($datum->id, $menus_string)) {
                        unset($menu_string[$key]->data[$key2]);

                    } else {
                        $searchedValue = $datum->id;
                        $item3 = reset(array_filter(
                            $submenus,
                            function ($e) use (&$searchedValue) {
                                return $e->Pagina == $searchedValue;
                            }
                        ));

                        $datum->add = $item3->add;
                        $datum->edit = $item3->edit;
                        $datum->delete = $item3->delete;
                        $datum->add = true;
                        $datum->edit = true;
                        $datum->delete = true;

                    }


                }

            }
        }
    }


    foreach ($submenus as $key => $item) {
        $continuar = true;

        $searchedValue = $item->Pagina;

        $item2 = reset(array_filter(
            $menu_string,
            function ($e) use (&$searchedValue) {
                return $e->Pagina == $searchedValue;
            }
        ));

        if ($item2 == null || $item2->id == null || $item2->id == "") {
            $itemD = array(
                "id" => $item->Pagina,
                "add" => true,
                "edit" => true,
                "delete" => true,
                "show" => "false"

            );
            array_push($menu_string, $itemD);

        }
    }


    $menu_string2 = array();
    foreach ($menu_string as $key => $item) {
        array_push($menu_string2, $item);

        if (oldCount($item->data) > 0) {
            $arr = $item->data;
            $menu_string2[oldCount($menu_string2) - 1]->data = array();

            foreach ($arr as $key2 => $datum) {
                array_push($menu_string2[oldCount($menu_string2) - 1]->data, $datum);
            }

        }
    }


    $menu_string3 = json_decode(
        '[
                {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
{
                    "id": "myconfiguration", "icon": "icon-tools", "value": "Mi Configuracion", "data": [
                        {"id": "myConfiguration.myInformation", "value": "Mi Informacion"},
                        {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                        {"id": "myConfiguration.qrgoogle", "value": "QR Google"}
                        
                    ]
                },
                
                {
                    "id": "productsFather", "icon": "icon-cubes", "value": "Products", "data": [
                        {"id": "providers", "value": "Providers"},
                        {"id": "products", "value": "Products"},
                        {"id": "partnersProviders", "value": "Partners Proveedores"},
                        {"id": "partnersTypeProduct", "value": "Partners Tipo Producto"},
                        {"id": "partnersProducts", "value": "Partners Products"},
                        {"id": "partnersProductsCountry", "value": "Partners Products Country"},
                        {"id": "categories", "value": "Categories"},
                        {"id": "categoriesProducts", "value": "CategoriesProducts"}
                        
                    ]
                },
                {"id": "adminUserManagement", "icon": "icon-players", "show":"false", "value": "adminUserManagement"},
                {"id": "customers", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "agentListManagement", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "addAgentListManagement", "icon": "icon-players", "show":"false", "value": "addAgentListManagement"},
                {"id": "addBetShopManagement", "icon": "icon-players", "show":"false", "value": "addBetShopManagement"},
                {"id": "addAdminUserManagement", "icon": "icon-players", "show":"false", "value": "addAdminUserManagement"},
                {"id": "leagues.addLeagueManagement", "icon": "icon-players", "show":"false", "value": "Añadir ligas"},
                {"id": "machine.addMachineManagement", "icon": "icon-players", "show":"false", "value": "Añadir Maquina"},
                {"id": "machine.machineManagement", "icon": "icon-players", "show":"false", "value": "Detalles maquina "},
                {"id": "settings", "icon": "icon-players", "show":"false", "value": "settings"},
                {"id": "withdrawalRequestsApprove", "icon": "icon-players", "show":"false", "value": "withdrawalRequestsApprove"},

                {"id": "playersList", "icon": "icon-players", "value": "Jugadores"},
                 {
                    "id": "partner", "icon": "icon-partner", "value": "Partner", "data": [
                        {"id": "partner.PartnerSettings", "value": "PartnerSettings"}
                                            ]
                },
                                {"id": "leagues.leaguesManagement", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {
                        "id": "leagues", "icon": "icon-pie-chart", "value": "Ligas", "data": [
                            {"id": "leagues.leaguesList", "value": "Lista de Ligas"}
                                                ]
                    },
                {
                    "id": "requests", "icon": "icon-pie-chart", "value": "Request", "data": [
                        {"id": "requests.registrationRequests", "value": "registrationRequests"}
                                            ]
                },
                {
                    "id": "machine", "icon": "icon-pie-chart", "value": "Maquinas", "data": [
                        {"id": "machine.machineRegister", "value": "Lista"},
                        {"id": "machine.information", "value": "Registrar"},
                        {"id": "machine.pagoPremioMaquina", "value": "Pago Premio"},
                        {"id": "machine.pagoNotaCobro", "value": "Pago Premio"},
                                                {"id": "machine.managePointsGraphics", "value": "Maquinas Grafica"}

                                            ]
                },
                {
                    "id": "messages", "icon": "icon-pie-chart", "value": "Mensajes", "data": [
                        {"id": "messages.messageList", "value": "Lista"}
                                            ]
                },
                {
                    "id": "tools", "icon": "icon-pie-chart", "value": "Herramientas", "data": [
                        {"id": "tools.translationManager", "value": "Traducción"},
                        {"id": "tools.uploadImage", "value": "Subir imagen"}
                                            ]
                },
                
                
                {
                    "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                        {"id": "depositReport", "value": "Reporte de depósitos"},
                        {"id": "autoexclusionUsers", "value": "Autoexclusiones de Usuario"},
                        {"id": "casinoGamesReport", "value": "Reporte de casino "},
                        {"id": "bonusReport", "value": "Reporte de bonos"},
                        {"id": "playersReport", "value": "Reporte de Jugadores"},
                        {"id": "historicalCashFlow", "value": "Flujo de Caja Histórico"},
                        {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                        {"id": "informeGerencial", "value": "Informe Gerencial"},
                        {"id": "betsReport", "value": "Reporte de Apuestas"},
                        {"id": "usuarioOnlineResumido", "value": "Usuario online Resumido"},
                        {"id": "promotionalCodes", "value": "Codigos Promocionales"},
                        {"id": "relationUserAggregator", "value": "Usuario - Agregator"},
                        {"id": "paidPendingAwards", "value": "premiosPendientesPagar"}
                        
                    ]
                },
                
                {
                    "id": "accounting", "icon": "icon-security", "value": "Contabilidad", "data": [
                        {"id": "accounting.costCenter", "value": "Centros de costo", "add": true},
                        {"id": "accounting.area", "value": "Area", "add": true},
                        {"id": "accounting.position", "value": "Cargo", "add": true},
                        {"id": "accounting.typeCenterPosition", "value": "Tipos", "add": true},
                        {"id": "accounting.employees", "value": "Empleados", "add": true},
                        {"id": "accounting.expenses", "value": "Egresos", "add": true},
                        {"id": "accounting.incomes", "value": "Ingresos", "add": true},
                        {"id": "accounting.providers", "value": "Proveedores terceros", "add": true},
                        {"id": "accounting.concepts", "value": "Conceptos", "add": true},
                        {"id": "accounting.accounts", "value": "Cuentas", "add": true},
                        {"id": "accounting.productsThirdBetShop", "value": "Productos terceros", "add": true},
                        {"id": "accounting.productsThirdByBetShop", "value": "Productos terceros Punto de venta", "add": true},
                        {"id": "accounting.closingDayReport", "value": "Reporte cierre de dia"},
                        {"id": "accounting.squareDayReport", "value": "Reporte de cuadre de dia", "add": true}

                    ]
                },
                {
                    "id": "security", "icon": "icon-security", "value": "Security", "data": [
                        {"id": "approvalLogs", "value": "Aporbar Logs", "add": true},
                        {"id": "adminUser", "value": "Admin User", "add": true},
                        {"id": "usuariosbloqueados", "value": "Usuarios Bloqueados"},
                        {"id": "contingency", "value": "Contingency"},
                        {"id": "menus", "value": "Menus"},
                        {"id": "profile", "value": "Profile"},
                        {"id": "profileOptions", "value": "Profile - Options"},
                        {"id": "subMenu", "value": "Sub Menu"},
                        {"id": "userProfile", "value": "User Profile"},
                        {"id": "competitors.competitors", "value": "Competidores"}
                    ]
                },
                {
                    "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                        {"id": "flujoCaja", "value": "Flujo de Caja"},
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"}
                    ]
                },
                {
                    "id": "betShopManagement", "icon": "icon-shop", "value": "Gestión Punto de Venta", "data": [
                        {"id": "betShop", "value": "Punto de Venta", "add": true},
                        {"id": "cashiers", "value": "Cajeros"},
                        {"id": "managePointsGraphics", "value": "Gestión Puntos Gráfico"},
                        {"id": "betShopCompetence", "value": "Puntos de venta Competencia"}
                    ]
                },
                {
                    "id": "agentSystem", "icon": "icon-user-secret", "value": "Agentes", "data": [
                        {"id": "agentList", "value": "Lista de Agentes"},
                        {"id": "agentsTree", "value": "Árbol de Agentes"},
                        {"id": "agentsInform", "value": "Informe de Agentes"},
                        {"id": "agentTransfers", "value": "Transferencias"},
                        {"id": "agent.requestsAgent", "value": "Solicitudes"},
                        {"id": "agent.requirementsAgent", "value": "Requisitos"}

                    ]
                },
                {
                    "id": "financial", "icon": "icon-financial", "value": "Financiero", "data": [
                        {"id": "depositRequests", "value": "Solicitudes de Deposito"},
                        {"id": "withdrawalRequests", "value": "Solicitudes de Retiro","Edit":"true"}
                    ]
                }            ]'
    );
    /*{
                        "id": "leagues", "icon": "icon-pie-chart", "value": "Ligas", "data": [
                            {"id": "leagues.leaguesList", "value": "Lista de Ligas"}
                                                ]
                    },*/

    return ($menu_string2);

}
