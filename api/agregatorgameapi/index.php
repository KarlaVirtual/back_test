<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * Procesa una solicitud y maneja la lógica para implementación de juegos de los proveedores.
 *
 * @param object $_REQUEST Datos de entrada en formato JSON.
 *  -site:int Código del sitio.
 *  -game:int Código del juego.
 * -token:string Token de usuario.
 *
 *
 * @return array $response Respuesta con el estado de la solicitud y mensajes de error si los hay.
 *   -code:int Código de la solicitud.
 *   -error:boolean Indica si ocurrió un error.
 * @throws Exception Si los campos 'site' o 'game' están vacíos o no son numéricos.
 */

/* Configuración de encabezados HTTP para permitir CORS y manejar solicitudes JSON. */
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');
ini_set('memory_limit', '-1');

/* establece un encabezado CORS y gestiona la zona horaria de la sesión. */
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

$timezone = $_SESSION["timezone"];
$timezone = -5 - ($timezone);
$timezone = 0;


$URI = $_SERVER["REQUEST_URI"];

/* define una URL y obtiene datos de entrada en formato PHP. */
$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';

$currencies_valor = array();


$params = file_get_contents('php://input');

/* Decodifica parámetros JSON, inicializa respuesta y define clave de encriptación. */
$params = json_decode($params);
$response = array();
$response["error"] = false;
$response["code"] = 0;

$ENCRYPTION_KEY = "D!@#$%^&*";


/* maneja solicitudes OPTIONS y procesa una URI dividiéndola en un array. */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

$arraySuper = explode("/", current(explode("?", $URI)));


try {


    /* valida que la variable 'site' no esté vacía y sea numérica. */
    $site = $_REQUEST['ptn'];
    $token = $_REQUEST['token'];
    $game = $_REQUEST['game'];

    if ($site == "" || !is_numeric($site)) {
        throw new Exception("Field: Site", "50001");
    }


    /* Validación de un campo de juego y creación de un objeto de tipo de producto. */
    if ($game == "" || !is_numeric($game)) {
        throw new Exception("Field: Game", "50001");
    }


    //Revisamos si existe el Tipo de producto para el mandante
    $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $site, "", '');


    /* Lanza una excepción si el estado de $ProdMandanteTipo es "I". */
    if ($ProdMandanteTipo->estado == "I") {
        throw new Exception("Field: Game", "50001");
    }

    switch ($ProdMandanteTipo->tipo) {
        case "CASINO":


            /* Verifica la coincidencia de mandantes entre productos, lanzando excepción si no coinciden. */
            $ProductoMandante = new ProductoMandante("", "", $game);

            //Revisamos si existe el producto para el mandante
            if ($ProductoMandante->mandante != $ProdMandanteTipo->mandante) {
                throw new Exception("No coinciden ", "50001");
            }

            //Obtenemos el mandante y revisamos si es propio

            /* Verifica si el mandante es propio antes de realizar una acción específica. */
            $Mandante = new Mandante($ProductoMandante->mandante);

            if ($Mandante->propio == "S") {

            } else {

                //Detalles del partner

                /* Se asignan variables de configuración de API para un proveedor específico. */
                $urlApi = $ProdMandanteTipo->urlApi;
                $siteId = $ProdMandanteTipo->siteId;
                $siteKey = $ProdMandanteTipo->siteKey;

                $mode = "real";
                $provider = "";

                /* Código que inicializa un objeto "Game" con parámetros de configuración específicos. */
                $lan = "es";
                $partnerid = $ProductoMandante->mandante;
                $user_token = $token;
                $isMobile = "false";
                $gameid = $ProductoMandante->prodmandanteId;

                $Game = new \Backend\integrations\casino\Game($gameid, $mode, $provider, $lan, $partnerid, $user_token, $isMobile);


                /* establece el tipo de contenido como HTML y obtiene el HTML del juego. */
                header('Content-Type: html');

                $URL = $Game->getGameHtml();


            }


            break;
    }

} catch (Exception $e) {
    if($_ENV['debug']){
        print_r($e);
    }

    $code = $e->getCode();

    $codeProveedor = "";
    $messageProveedor = "";

    $response = array();


    switch ($code) {
        case 50001:
            $codeProveedor = "2";
            $messageProveedor = "Data Incorrect. (" . $e->getMessage() . ")";

            break;
        case 61:
            $codeProveedor = "3";
            $messageProveedor = "Incorrect login details.";

            break;

        default:
            $codeProveedor = '1';
            $messageProveedor = "Unexpected error.";

            break;
    }


    $response["error"] = true;
    $response["code"] = $codeProveedor;
    $response["message"] = $messageProveedor;

    $html_retornar = '<div class="central-body">
<div class="title" style="
    ">
    Ocurrio un error
</div>                  <a href="#" class="btn-go-home" target="_blank">Retornar</a>
            </div>';

    $html = '
<head><meta name="viewport" content="width=device-width, user-scalable=no">
</head>
<style>

/*
VIEW IN FULL SCREEN MODE
FULL SCREEN MODE: http://salehriaz.com/404Page/404.html

DRIBBBLE: https://dribbble.com/shots/4330167-404-Page-Lost-In-Space
*/

@import url(\'https://fonts.googleapis.com/css?family=Dosis:300,400,500\');

@-moz-keyframes rocket-movement { 100% {-moz-transform: translate(1200px,-600px);} }
@-webkit-keyframes rocket-movement {100% {-webkit-transform: translate(1200px,-600px); } }
@keyframes rocket-movement { 100% {transform: translate(1200px,-600px);} }
@-moz-keyframes spin-earth { 100% { -moz-transform: rotate(-360deg); transition: transform 20s;  } }
@-webkit-keyframes spin-earth { 100% { -webkit-transform: rotate(-360deg); transition: transform 20s;  } }
@keyframes spin-earth{ 100% { -webkit-transform: rotate(-360deg); transform:rotate(-360deg); transition: transform 20s; } }

@-moz-keyframes move-astronaut {
    100% { -moz-transform: translate(-160px, -160px);}
}
@-webkit-keyframes move-astronaut {
    100% { -webkit-transform: translate(-160px, -160px);}
}
@keyframes move-astronaut{
    100% { -webkit-transform: translate(-160px, -160px); transform:translate(-160px, -160px); }
}
@-moz-keyframes rotate-astronaut {
    100% { -moz-transform: rotate(-720deg);}
}
@-webkit-keyframes rotate-astronaut {
    100% { -webkit-transform: rotate(-720deg);}
}
@keyframes rotate-astronaut{
    100% { -webkit-transform: rotate(-720deg); transform:rotate(-720deg); }
}

@-moz-keyframes glow-star {
    40% { -moz-opacity: 0.3;}
    90%,100% { -moz-opacity: 1; -moz-transform: scale(1.2);}
}
@-webkit-keyframes glow-star {
    40% { -webkit-opacity: 0.3;}
    90%,100% { -webkit-opacity: 1; -webkit-transform: scale(1.2);}
}
@keyframes glow-star{
    40% { -webkit-opacity: 0.3; opacity: 0.3;  }
    90%,100% { -webkit-opacity: 1; opacity: 1; -webkit-transform: scale(1.2); transform: scale(1.2); border-radius: 999999px;}
}

.spin-earth-on-hover{
    
    transition: ease 200s !important;
    transform: rotate(-3600deg) !important;
}

html, body{
    margin: 0;
    width: 100%;
    height: 100%;
    font-family: \'Dosis\', sans-serif;
    font-weight: 300;
    -webkit-user-select: none; /* Safari 3.1+ */
    -moz-user-select: none; /* Firefox 2+ */
    -ms-user-select: none; /* IE 10+ */
    user-select: none; /* Standard syntax */
}

.bg-purple{
    background: black;
    background-repeat: repeat-x;
    background-size: cover;
    background-position: left top;
    height: 100%;
    overflow: hidden;
    
}

.custom-navbar{
    padding-top: 15px;
}

.brand-logo{
    margin-left: 25px;
    margin-top: 5px;
    display: inline-block;
}

.navbar-links{
    display: inline-block;
    float: right;
    margin-right: 15px;
    text-transform: uppercase;
    
    
}

ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
/*    overflow: hidden;*/
    display: flex; 
    align-items: center; 
}

li {
    float: left;
    padding: 0px 15px;
}

li a {
    display: block;
    color: white;
    text-align: center;
    text-decoration: none;
    letter-spacing : 2px;
    font-size: 12px;
    
    -webkit-transition: all 0.3s ease-in;
    -moz-transition: all 0.3s ease-in;
    -ms-transition: all 0.3s ease-in;
    -o-transition: all 0.3s ease-in;
    transition: all 0.3s ease-in;
}

li a:hover {
    color: #ffcb39;
}

.btn-request{
    padding: 10px 25px;
    border: 1px solid #FFCB39;
    border-radius: 100px;
    font-weight: 400;
}

.btn-request:hover{
    background-color: #FFCB39;
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0px 20px 20px rgba(0,0,0,0.1);
}

.btn-go-home{
    position: relative;
    z-index: 200;
    margin: 15px auto;
    width: 100px;
    padding: 10px 15px;
    border: 1px solid #FFCB39;
    border-radius: 100px;
    font-weight: 400;
    display: block;
    color: white;
    text-align: center;
    text-decoration: none;
    letter-spacing : 2px;
    font-size: 11px;
    
    -webkit-transition: all 0.3s ease-in;
    -moz-transition: all 0.3s ease-in;
    -ms-transition: all 0.3s ease-in;
    -o-transition: all 0.3s ease-in;
    transition: all 0.3s ease-in;
}

.btn-go-home:hover{
    background-color: #FFCB39;
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0px 20px 20px rgba(0,0,0,0.1);
}

.central-body{
/*    width: 100%;*/
    padding: 30% 5% 10% 5%;
    text-align: center;
}

.objects img{
    z-index: 90;
    pointer-events: none;
}

.object_rocket{
    z-index: 95;
    position: absolute;
    transform: translateX(-50px);
    top: 75%;
    pointer-events: none;
    animation: rocket-movement 200s linear infinite both running;
}

.object_earth{
    position: absolute;
    top: 20%;
    left: 15%;
    z-index: 90;
/*    animation: spin-earth 100s infinite linear both;*/
}

.object_moon{
    position: absolute;
    top: 12%;
    left: 25%;
/*
    transform: rotate(0deg);
    transition: transform ease-in 99999999999s;
*/
}

.earth-moon{
    
}

.object_astronaut{
    animation: rotate-astronaut 200s infinite linear both alternate;
}

.box_astronaut{
    z-index: 110 !important;
    position: absolute;
    top: 60%;
    right: 20%;
    will-change: transform;
    animation: move-astronaut 50s infinite linear both alternate;
}

.image-404{
    position: relative;
    z-index: 100;
    pointer-events: none;
}

.stars{
    background: url(http://salehriaz.com/404Page/img/overlay_stars.svg);
    background-repeat: repeat;
    background-size: contain;
    background-position: left top;
    height: 100%;
}

.glowing_stars .star{
    position: absolute;
    border-radius: 100%;
    background-color: #fff;
    width: 3px;
    height: 3px;
    opacity: 0.3;
    will-change: opacity;
}

.glowing_stars .star:nth-child(1){
    top: 80%;
    left: 25%;
    animation: glow-star 2s infinite ease-in-out alternate 1s;
}
.glowing_stars .star:nth-child(2){
    top: 20%;
    left: 40%;
    animation: glow-star 2s infinite ease-in-out alternate 3s;
}
.glowing_stars .star:nth-child(3){
    top: 25%;
    left: 25%;
    animation: glow-star 2s infinite ease-in-out alternate 5s;
}
.glowing_stars .star:nth-child(4){
    top: 75%;
    left: 80%;
    animation: glow-star 2s infinite ease-in-out alternate 7s;
}
.glowing_stars .star:nth-child(5){
    top: 90%;
    left: 50%;
    animation: glow-star 2s infinite ease-in-out alternate 9s;
}

@media only screen and (max-width: 600px){
    .navbar-links{
        display: none;
    }
    
    .custom-navbar{
        text-align: center;
    }
    
    .brand-logo img{
        width: 120px;
    }
    
    .box_astronaut{
        top: 70%;
    }
    
    .central-body{
        padding-top: 60%;
    }
    .btn-go-home{
        background-color: #FFCB39;
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0px 20px 20px rgba(0,0,0,0.1);
    }
}

.title{
    color: white;
}
</style><section class="page_404">
	<!--
VIEW IN FULL SCREEN MODE
FULL SCREEN MODE: http://salehriaz.com/404Page/404.html

DRIBBBLE: https://dribbble.com/shots/4330167-404-Page-Lost-In-Space
-->

<body class="bg-purple">
        
        <div class="stars">
            <div class="custom-navbar">
            </div>
            ' . $html_retornar . '
            <div class="objects">
              <div class="earth-moon">
                    <img class="object_earth" src="http://salehriaz.com/404Page/img/earth.svg" width="100px">
                    <img class="object_moon" src="http://salehriaz.com/404Page/img/moon.svg" width="80px">
                </div>
                <div class="box_astronaut">
                    <img class="object_astronaut" src="http://salehriaz.com/404Page/img/astronaut.svg" width="140px">
                </div>
            </div>
            <div class="glowing_stars">
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>

            </div>

        </div>

    </body>';
    header('Content-Type: html');

    echo($html);
}


/*if (json_encode($response) != "[]") {
    print_r(json_encode($response));

}*/

/**
 * Enviar una peticiónn
 *
 * @param String $url url
 * @param String $method method
 * @param array $array_tmp array_tmp
 *
 * @return String $result result
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function sendRequest($url, $method, $array_tmp)
{
    $data = array();

    $data = array_merge($data, $array_tmp);

    $data = json_encode($data);


    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    //$rs = curl_exec($ch);
    $result = json_decode(curl_exec($ch));

    return ($result);

}

/**
 * Convertir divisas
 *
 * @param array $from_Currency from_Currency
 * @param String $to_Currency to_Currency
 * @param String $amount amounts
 *
 * @return String $convertido convertido
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function currencyConverter($from_Currency, $to_Currency, $amount)
{

    if ($from_Currency == $to_Currency) {
        return $amount;
    }
    global $currencies_valor;
    $convertido = -1;
    $bool = false;

    foreach ($currencies_valor as $key => $valor) {
        if ($key == ($from_Currency . "" . $to_Currency)) {
            $convertido = $amount * $valor;
            $bool = true;
        } elseif ($key == ($from_Currency . "" . $to_Currency)) {
            $convertido = ($amount) / $valor;
            $bool = true;
        }
    }
    if (!$bool) {
        $from_Currency = urlencode($from_Currency);
        $to_Currency = urlencode($to_Currency);
        $encode_amount = 1;

        $rawdata = file_get_contents("http://api.currencies.zone/v1/quotes/$from_Currency/$to_Currency/json?quantity=$encode_amount&key=44|YSqBgkAbvbGfenoxx62OaSnoD~rF8rw~");
        if ($_SESSION["usuario2"] == 5) {

        }
        $rawdata = json_decode($rawdata);
        $currencies_valor += [$from_Currency . "" . $to_Currency => $rawdata->result->amount];

        $convertido = $amount * $rawdata->result->amount;

    }


    return $convertido;
}

/**
 * Obtener información sobre un deporte
 *
 * @param String $sport sport
 * @param String $region region
 * @param String $encryption_key encryption_key
 * @param String $encryption_key encryption_key
 * @param String $encryption_key encryption_key
 *
 * @return String|boolean $decrypted_string resultado de la operación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getMatches($sport, $region, $competition, $fecha_inicial, $fecha_final)
{
    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=1&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
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
 * Generar una clave alfanumérica del ticket
 *
 * @param int $length length
 *
 * @return String $randomString randomString
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
 * Generar una clave númera de ticket
 *
 * @param int $length length
 *
 * @return String $randomString randomString
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


/**
 * Encriptar o desencriptar según el caso, con el método AES-256-CBC
 *
 * @param String $action action
 * @param String $string string
 *
 * @return String $output output
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function encrypt_decrypt($action, $string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'D0RAD0';
    $secret_iv = 'D0RAD0';
    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
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
 * Crear arreglo unico a partir de uno multidimensiona
 *
 * @param array $array array
 * @param String $key key
 *
 * @return String $temp_array temp_array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

/**
 * Quitar tildes
 *
 * @param String $cadena cadena con tildes
 *
 * @return String $texto cadena sin tildes
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function quitar_tildes($cadena)
{
    $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
    $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
    $texto = str_replace($no_permitidas, $permitidas, $cadena);
    return $texto;
}


/**
 * Encriptar con el método AES-128-CTR
 *
 * @param array $data data
 * @param String $encryption_key encryption_key
 *
 * @return String $encrypted_string resultado de la operación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function encrypt($data, $encryption_key = "")
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']), 0, $iv);
    return $encrypted_string;
}


/**
 * Desencriptar con el método AES-128-CTR
 *
 * @param array $data data
 * @param String $encryption_key encryption_key
 *
 * @return String|boolean $decrypted_string resultado de la operación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function decrypt($data, $encryption_key = "")
{

    $iv_strlen = 2 * openssl_cipher_iv_length('AES-128-CTR');
    if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
        list(, $iv, $crypted_string) = $regs;
        $decrypted_string = openssl_decrypt($crypted_string, 'AES-128-CTR', gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']), 0, hex2bin($iv));
        return $decrypted_string;
    } else {
        return FALSE;
    }
}
