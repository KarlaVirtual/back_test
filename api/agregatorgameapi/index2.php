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

include "includes.php";


/* Configura cabeceras de CORS y tipo de contenido para una API en JSON. */
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');
ini_set('memory_limit', '-1');

/* permite el acceso CORS y maneja la zona horaria del usuario. */
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

$timezone = $_SESSION["timezone"];
$timezone = -5 - ($timezone);
$timezone = 0;


$URI = $_SERVER["REQUEST_URI"];

/* Código establece una URL y obtiene datos de entrada en formato PHP. */
$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';

$currencies_valor = array();


$params = file_get_contents('php://input');

/* Se decodifica un JSON y se inicializa un array de respuesta sin errores. */
$params = json_decode($params);
$response = array();
$response["error"] = false;
$response["code"] = 0;

$ENCRYPTION_KEY = "D!@#$%^&*";


/* maneja solicitudes OPTIONS y extrae un array de la URL. */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

$arraySuper = explode("/", current(explode("?", $URI)));


try {

    switch ($arraySuper[oldCount($arraySuper) - 2] . "/" . $arraySuper[oldCount($arraySuper) - 1]) {

        case 'casino/game':


            /* Valida si el campo 'site' es numérico y no está vacío. */
            $site = $_REQUEST['ptn'];
            $token = $_REQUEST['token'];
            $game = $_REQUEST['game'];

            if ($site == "" || !is_numeric($site)) {
                throw new Exception("Field: Site", "50001");
            }


            /* valida un campo y verifica la existencia de un tipo de producto. */
            if ($game == "" || !is_numeric($game)) {
                throw new Exception("Field: Game", "50001");
            }


            //Revisamos si existe el Tipo de producto para el mandante
            $ProdMandanteTipo = new ProdMandanteTipo("", "", "", $site);

            switch ($ProdMandanteTipo->tipo) {
                case "CASINO":


                    /* Se crea un objeto y se verifica la coincidencia del mandante para lanzar una excepción. */
                    $ProductoMandante = new ProductoMandante("", "", $game);

                    //Revisamos si existe el producto para el mandante
                    if ($ProductoMandante->mandante != $ProdMandanteTipo->mandante) {
                        throw new Exception("No coinciden ", "50001");
                    }

                    //Obtenemos el mandante y revisamos si es propio

                    /* Se crea un objeto Mandante y se evalúa si es propio. */
                    $Mandante = new Mandante($ProductoMandante->mandante);

                    if ($Mandante->propio == "S") {

                    } else {

                        //Detalles del partner

                        /* Código para configurar autenticación en una API usando credenciales y datos necesarios. */
                        $urlApi = $ProdMandanteTipo->urlApi;
                        $siteId = $ProdMandanteTipo->siteId;
                        $siteKey = $ProdMandanteTipo->siteKey;

                        $method = "/authenticate";
                        $data = array(
                            "site" => $siteId,
                            "key" => $siteKey,
                            "token" => $token,
                            "game" => $game
                        );


                        /* Se envía una solicitud POST y se maneja un error si no hay respuesta. */
                        $result = sendRequest($urlApi . $method, "POST", $data);


                        if ($result == "") {
                            throw new Exception("No coinciden ", "50001");
                        }


                        /* Verifica errores y lanza excepción si no hay coincidencias; obtiene usuario y saldo. */
                        if (strtolower($result->error) == "" || strtolower($result->error) == '1') {
                            throw new Exception("No coinciden ", "50001");
                        }

                        $userid = $result->player->userid;
                        $balance = $result->player->balance;

                        /* Se extraen datos de un objeto jugador en variables separadas. */
                        $name = $result->player->name;
                        $lastname = $result->player->lastname;
                        $currency = $result->player->currency;
                        $userid = $result->player->userid;
                        $dirip = $result->player->ip;
                        $country = $result->player->country;

                        /* Se obtiene el email del jugador y se verifica la validez del ID de usuario. */
                        $email = $result->player->email;


                        $balance = floatval(round($balance, 2));

                        if ($userid == "" || !is_numeric($userid)) {
                            throw new Exception("No coinciden ", "50001");
                        }


                        /* valida el balance y nombre antes de continuar, lanzando excepciones si son inválidos. */
                        if ($balance == "" || !is_float($balance)) {
                            throw new Exception("No coinciden ", "50001");
                        }

                        if ($name == "") {
                            throw new Exception("No coinciden ", "50001");
                        }


                        /* lanza excepciones si la moneda o el país están vacíos. */
                        if ($currency == "") {
                            throw new Exception("No coinciden ", "50001");
                        }

                        if ($country == "") {
                            throw new Exception("No coinciden ", "50001");
                        }


                        /* Código para actualizar información de un usuario en una base de datos con manejo de transacciones. */
                        $Pais = new Pais('', strtoupper($country));


                        try {
                            $UsuarioMandante = new UsuarioMandante("", $userid, $Mandante->mandante);
                            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            $UsuarioMandante->tokenExterno = $token;

                            $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
                            $UsuarioMandanteMySqlDAO->update($UsuarioMandante);
                            $UsuarioMandanteMySqlDAO->getTransaction()->commit();

                        } catch (Exception $e) {

                            if ($e->getCode() == 22) {


                                /* Se crea un objeto UsuarioMandante y se asignan propiedades relevantes. */
                                $UsuarioMandante = new UsuarioMandante();

                                $UsuarioMandante->mandante = $Mandante->mandante;
                                $UsuarioMandante->dirIp = $dir_ip;
                                $UsuarioMandante->nombres = $name;
                                $UsuarioMandante->apellidos = $lastname;

                                /* Asignación de propiedades a un objeto UsuarioMandante con información del usuario. */
                                $UsuarioMandante->estado = 'A';
                                $UsuarioMandante->email = $email;
                                $UsuarioMandante->moneda = $currency;
                                $UsuarioMandante->paisId = $Pais->paisId;
                                $UsuarioMandante->saldo = $balance;
                                $UsuarioMandante->usuarioMandante = $userid;

                                /* Se inicializan propiedades de un objeto UsuarioMandante y se crea un DAO. */
                                $UsuarioMandante->usucreaId = 0;
                                $UsuarioMandante->usumodifId = 0;
                                $UsuarioMandante->propio = 'N';
                                $UsuarioMandante->tokenExterno = $token;

                                $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();

                                /* Inserta un usuario en la base de datos y confirma la transacción. */
                                $usuario_id = $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);

                                $UsuarioMandanteMySqlDAO->getTransaction()->getConnection()->commit();

                                $UsuarioToken = new UsuarioToken();

                                $UsuarioToken->setRequestId('');

                                /* Establece propiedades en un objeto UsuarioToken, incluyendo token y cookie cifrada. */
                                $UsuarioToken->setProveedorId(0);
                                $UsuarioToken->setToken($UsuarioToken->createToken());
                                $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
                                $UsuarioToken->setCookie(encrypt($UsuarioMandante->getUsumandanteId() . "#" . time()));
                                $UsuarioToken->setUsumodifId(0);
                                $UsuarioToken->setUsucreaId(0);

                                /* Establece saldo a cero y guarda un usuario en la base de datos MySQL. */
                                $UsuarioToken->setSaldo(0);

                                $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                                $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                                $UsuarioTokenMySqlDAO->getTransaction()->commit();
                            }

                        }

                        /* inicializa variables para configurar un entorno de usuario y compatibilidad de idioma. */
                        $mode = "real";
                        $provider = "";
                        $lan = "es";
                        $partnerid = $UsuarioMandante->getMandante();
                        $user_token = $UsuarioToken->getToken();
                        $isMobile = "false";


                        /* Crea una instancia de un juego y obtiene su HTML para mostrarlo. */
                        $gameid = $ProductoMandante->prodmandanteId;

                        try {
                            $Game = new \Backend\integrations\casino\Game($gameid, $mode, $provider, $lan, $partnerid, $user_token, $isMobile);


                            $Game->getGameHtml();
                        } catch (Exception $e) {
                            /* Bloque de código para manejar excepciones sin realizar ninguna acción específica. */

                        }


                    }


                    break;
            }


            break;


        default:
            # code...
            break;
    }
} catch (Exception $e) {
    /*Manejo de excepciones*/


    print_r($e);

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

    $html = '<style>

/*======================
    404 page
=======================*/


.page_404{ padding:40px 0; background:#fff; font-family: \'Arvo\', serif;
}

.page_404  img{ width:100%;}

.four_zero_four_bg{
 
 background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
    height: 400px;
    background-position: center;
 }
 
 
 .four_zero_four_bg h1{
 font-size:80px;
 }
 
  .four_zero_four_bg h3{
			 font-size:80px;
			 }
			 
			 .link_404{			 
	color: #fff!important;
    padding: 10px 20px;
    background: #39ac31;
    margin: 20px 0;
    display: inline-block;}
	.contant_box_404{ margin-top:-50px;}
</style><section class="page_404">
	<div class="container">
		<div class="row">	
		<div class="col-sm-12 ">
		<div class="col-sm-10 col-sm-offset-1  text-center">
		<div class="four_zero_four_bg">
			<h1 class="text-center ">404</h1>
		
		
		</div>
		
		<div class="contant_box_404">
		<h3 class="h2">
		Look like you\'re lost
		</h3>
		
		<p>the page you are looking for not avaible!</p>
		
		<a href="" class="link_404">Go to Home</a>
	</div>
		</div>
		</div>
		</div>
	</div>
</section>';

    print_r($html);
}


/*if (json_encode($response) != "[]") {
    print_r(json_encode($response));

}*/

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


function GenerarClaveTicket($length)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function GenerarClaveTicket2($length)
{
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


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

function quitar_tildes($cadena)
{
    $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
    $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
    $texto = str_replace($no_permitidas, $permitidas, $cadena);
    return $texto;
}


/**
 * Returns an encrypted & utf8-encoded
 */
function encrypt($data, $encryption_key = "")
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']), 0, $iv);
    return $encrypted_string;
}

/**
 * Returns decrypted original string
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
