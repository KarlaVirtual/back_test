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

/**
 * Procesa la solicitud de la API y genera una respuesta en formato JSON.
 *
 * @param object $params Objeto JSON decodificado que contiene los parámetros de entrada.
 * @param string $params->site Identificador del sitio, debe ser un valor numérico.
 * @param string $params->key Clave de autenticación, no debe estar vacía.
 * @param string $params->player (Opcional) Identificador del jugador, no debe estar vacío.
 *
 * @return array $response Arreglo que contiene la respuesta de la API.
 * @return bool $response["error"] Indica si hubo un error en la solicitud.
 * @return int $response["code"] Código de error o éxito de la operación.
 * @return string $response["message"] Mensaje descriptivo del resultado de la operación.
 * @return array $response["response"] (Opcional) Datos de respuesta en caso de éxito.
 *
 * @throws Exception Si el campo 'site' está vacío o no es numérico.
 * @throws Exception Si el campo 'key' está vacío.
 * @throws Exception Si el campo 'player' está vacío (solo en el caso 'casino/createUrl').
 */

/* Configura encabezados de CORS y ajusta el límite de memoria en PHP. */
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');
ini_set('memory_limit', '-1');

/* Configura CORS, ajusta la zona horaria y obtiene la URI de la solicitud. */
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

$timezone = $_SESSION["timezone"];
$timezone = -5 - ($timezone);
$timezone = 0;


$URI = $_SERVER["REQUEST_URI"];

/* obtiene datos de una URL y recibe parámetros de entrada en PHP. */
$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';

$currencies_valor = array();


$params = file_get_contents('php://input');

/* Se decodifica JSON, inicializa respuesta y establece una clave de encriptación. */
$params = json_decode($params);
$response = array();
$response["error"] = false;
$response["code"] = 0;

$ENCRYPTION_KEY = "D!@#$%^&*";


/* verifica el método HTTP y procesa una URI, creando un array. */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

$arraySuper = explode("/", current(explode("?", $URI)));


try {

    switch ($arraySuper[oldCount($arraySuper) - 2] . "/" . $arraySuper[oldCount($arraySuper) - 1]) {

        /**
         * casino/getGames
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
        case 'casino/getGames':


            /* Valida si 'site' está vacío o no es numérico, lanzando excepción si es incorrecto. */
            $site = $params->site;
            $key = $params->key;

            if ($site == "" || !is_numeric($site)) {
                throw new Exception("Field: Site", "50001");
            }


            /* valida un campo vacío y crea una instancia de ProdMandanteTipo. */
            if ($key == "") {
                throw new Exception("Field: Key", "50001");

            }

            $ProdMandanteTipo = new ProdMandanteTipo("CASINO", "", "", $site, $key);


            switch ($ProdMandanteTipo->tipo) {
                case "CASINO":


                    /* Se crean instancias de Mandante y ProductoMandante, y se definen límites de filas. */
                    $Mandante = new Mandante($ProdMandanteTipo->mandante);


                    $ProductoMandante = new ProductoMandante();
                    $SkeepRows = 0;
                    $MaxRows = 10000000;

                    /* Define reglas de filtrado para productos basado en condiciones específicas. */
                    $rules = [];
                    array_push($rules, array("field" => "producto_mandante.mandante", "data" => $ProdMandanteTipo->mandante, "op" => "eq"));
                    array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO", "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* Se obtienen y decodifican productos filtrados mediante JSON en PHP. */
                    $jsonfiltro = json_encode($filtro);

                    $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,producto.*,mandante.*,proveedor.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true);

                    $productos = json_decode($productos);


                    $final = [];


                    /* itera sobre productos, formando un array con detalles relevantes de cada uno. */
                    foreach ($productos->data as $key => $value) {

                        $array = [];

                        $array["id"] = $value->{"producto_mandante.prodmandante_id"};
                        $array["providerName"] = $value->{"proveedor.descripcion"};
                        $array["gameName"] = $value->{"producto.descripcion"};
                        $array["gameCode"] = $value->{"producto_mandante.prodmandante_id"};
                        $array["image"] = $value->{"producto.image_url"};

                        array_push($final, $array);

                    }


                    /* Asigna el valor de $final a la clave "response" del array $response. */
                    $response["response"] = $final;


                    break;
            }


            break;

        /**
         * casino/createUrl
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
        case 'casino/createUrl':


            /* Valida que 'site' no esté vacío y sea numérico, lanzando una excepción si no. */
            $site = $params->site;
            $key = $params->key;
            $player = $params->player;

            if ($site == "" || !is_numeric($site)) {
                throw new Exception("Field: Site", "50001");
            }


            /* lanza excepciones si las variables clave o jugador están vacías. */
            if ($key == "") {
                throw new Exception("Field: Key", "50001");

            }

            if ($player == "") {
                throw new Exception("Field: Player", "50001");

            }


            /* Se crea una instancia de la clase ProdMandanteTipo con variables y parámetros especificados. */
            $ProdMandanteTipo = new ProdMandanteTipo("", "", "", $site, $key);


            switch ($ProdMandanteTipo->tipo) {
                case "CASINO":


                    /* Se inicializan objetos y variables para manejar productos y mandantes. */
                    $Mandante = new Mandante($ProdMandanteTipo->mandante);


                    $ProductoMandante = new ProductoMandante();
                    $SkeepRows = 0;
                    $MaxRows = 10000000;

                    /* Se crea un filtro de reglas para validar condiciones específicas en productos. */
                    $rules = [];
                    array_push($rules, array("field" => "producto_mandante.mandante", "data" => $ProdMandanteTipo->mandante, "op" => "eq"));
                    array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO", "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* Codifica un filtro JSON y obtiene productos personalizados desde una base de datos. */
                    $jsonfiltro = json_encode($filtro);

                    $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,producto.*,mandante.*,proveedor.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true);

                    $productos = json_decode($productos);


                    $final = [];


                    /* Recorre productos y crea un array con detalles de cada uno. */
                    foreach ($productos->data as $key => $value) {

                        $array = [];

                        $array["id"] = $value->{"producto_mandante.prodmandante_id"};
                        $array["providerName"] = $value->{"proveedor.descripcion"};
                        $array["gameName"] = $value->{"producto.descripcion"};
                        $array["gameCode"] = $value->{"producto_mandante.prodmandante_id"};
                        $array["image"] = $value->{"producto.image_url"};

                        array_push($final, $array);

                    }


                    /* Asigna el valor de $final a la clave "response" del arreglo $response. */
                    $response["response"] = $final;


                    break;
            }


            break;


        default:
            # code...
            break;
    }
} catch (Exception $e) {


    /* imprime detalles del error si la depuración está habilitada. */
    if ($_ENV['debug']) {
        print_r($e);
    }


    $code = $e->getCode();


    /* Asignación de códigos y mensajes según errores específicos en un switch. */
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


    /* Código que estructura una respuesta de error en formato JSON. */
    $response["error"] = true;
    $response["code"] = $codeProveedor;
    $response["message"] = $messageProveedor;
}


/* verifica y muestra un JSON de respuesta no vacío y define una función de conversión de divisas. */
if (json_encode($response) != "[]") {
    print_r(json_encode($response));

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


    /* verifica si las divisas son iguales y retorna el monto original. */
    if ($from_Currency == $to_Currency) {
        return $amount;
    }
    global $currencies_valor;
    $convertido = -1;
    $bool = false;


    /* Convierte montos entre divisas utilizando un array de valores y claves. */
    foreach ($currencies_valor as $key => $valor) {
        if ($key == ($from_Currency . "" . $to_Currency)) {
            $convertido = $amount * $valor;
            $bool = true;
        } elseif ($key == ($from_Currency . "" . $to_Currency)) {
            $convertido = ($amount) / $valor;
            $bool = true;
        }
    }

    /* convierte una cantidad de dinero entre dos divisas usando una API. */
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

    /* decodifica datos JSON y los almacena en un arreglo. */
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {

        if ($sport == $item->SportId) {

            /* Recorre categorías y campeonatos, recopilando datos de eventos específicos en un array. */
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

    /* encripta un string usando AES-256-CBC y codifica en base64. */
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        /* desencripta una cadena usando OpenSSL y un método de cifrado. */

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
    /*Obtiene la IP del usuario mediante la manipulación de múltiples cabeceras HTTP*/
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

    /* elimina duplicados de un array basado en una clave específica. */
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
 * Encriptar con el metodo AES-128-CTR
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
 * Desencriptar con el metodo AES-128-CTR
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
