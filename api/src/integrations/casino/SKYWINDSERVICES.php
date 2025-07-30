<?php

/**
 * Clase para integrar servicios de Skywind en la API.
 *
 * Este archivo contiene la implementación de la clase `SKYWINDSERVICES`,
 * que permite la integración con los servicios de Skywind, incluyendo
 * autenticación, obtención de juegos y manejo de tokens de usuario.
 *
 * @category Integración
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;

/**
 * Clase principal para manejar la integración con los servicios de Skywind.
 */
class SKYWINDSERVICES
{

    /**
     * Identificador de inicio de sesión seguro para el entorno actual.
     *
     * @var string
     */
    private $secureLogin = 'drb_doradobet';

    /**
     * Identificador de inicio de sesión seguro para el entorno de desarrollo.
     *
     * @var string
     */
    private $secureLoginDEV = 'drb_doradobet';

    /**
     * Identificador de inicio de sesión seguro para el entorno de producción.
     *
     * @var string
     */
    private $secureLoginPROD = 'drb_doradobet';

    /**
     * Clave secreta para el entorno actual.
     *
     * @var string
     */
    private $secretKey = '';

    /**
     * Clave secreta para el entorno de desarrollo.
     *
     * @var string
     */
    private $secretKeyDev = '36797761-6a98-450a-a225-72c9a6061d07';

    /**
     * Clave secreta para el entorno de producción.
     *
     * @var string
     */
    private $secretKeyProd = '86de7f18-49d4-47e1-97d1-c475227961bb';

    /**
     * URL base para el entorno actual.
     *
     * @var string
     */
    private $URL = '';

    /**
     * URL base para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://operator-eu.ss211208.com/v1/';

    /**
     * URL base para el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://operator-eu.sw420101.com/v1/'; //OK

    /**
     * URL de redirección para el entorno actual.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * URL de redirección para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLREDIRECTIONDEV = 'https%3A%2F%2Fdev.doradobet.com/new-casino';

    /**
     * URL de redirección para el entorno de producción.
     *
     * @var string
     */
    private $URLREDIRECTIONPROD = 'https%3A%2F%2Fdoradobet.com/new-casino';

    /**
     * Nombre de usuario para el entorno actual.
     *
     * @var string
     */
    private $username = '';

    /**
     * Nombre de usuario para el entorno de desarrollo.
     *
     * @var string
     */
    private $usernameDev = 'Virtualsoft_CW_api_stg';

    /**
     * Nombre de usuario para el entorno de producción.
     *
     * @var string
     */
    private $usernameProd = 'sw_virtualsoft_apiprod';

    /**
     * Contraseña para el entorno actual.
     *
     * @var string
     */
    private $password = '';

    /**
     * Contraseña para el entorno de desarrollo.
     *
     * @var string
     */
    private $passwordDev = 'NhVY3YfmxqBT4WCH';

    /**
     * Contraseña para el entorno de producción.
     *
     * @var string
     */
    private $passwordProd = 'saQk4AEzHnGwWUf9';

    /**
     * Tipo de operación actual.
     *
     * @var string
     */
    private $tipo = '';

    /**
     * Constructor de la clase `SKYWINDSERVICES`.
     *
     * Este constructor inicializa las propiedades de la clase según el entorno
     * configurado (desarrollo o producción). Utiliza la clase `ConfigurationEnvironment`
     * para determinar el entorno actual y asignar los valores correspondientes
     * a las propiedades como URL, credenciales de inicio de sesión y claves secretas.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->secureLogin = $this->secureLoginDEV;
            $this->URLREDIRECTION = $this->URLREDIRECTIONDEV;
            $this->secretKey = $this->secretKeyDev;
            $this->username = $this->usernameDev;
            $this->password = $this->passwordDev;
        } else {
            $this->URL = $this->URLPROD;
            $this->secureLogin = $this->secureLoginPROD;
            $this->URLREDIRECTION = $this->URLREDIRECTIONPROD;
            $this->secretKey = $this->secretKeyProd;
            $this->username = $this->usernameProd;
            $this->password = $this->passwordProd;
        }
    }

    /**
     * Obtiene un juego específico basado en el código del juego y otros parámetros.
     *
     * Este método maneja la lógica para obtener un juego de Skywind, ya sea en modo
     * de juego real o por diversión. También gestiona la creación y actualización
     * de tokens de usuario, así como la configuración de credenciales según el entorno.
     *
     * @param string  $GameCode      Código del juego a obtener.
     * @param string  $lang          Idioma en el que se mostrará el juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión (true) o real (false).
     * @param string  $usuarioToken  Token del usuario (opcional).
     * @param integer $ProductoId    ID del producto asociado al juego.
     * @param boolean $isMobile      Indica si el juego se ejecuta en un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object|null Respuesta con la URL del juego o null en caso de error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($GameCode, $lang, $play_for_fun, $usuarioToken = "", $ProductoId, $isMobile, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0'); //De acuerdo con la base de Datos el proveedorId para ezugi es 6
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }
            $Proveedor = new Proveedor("", "SKYWIND");


            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId, "", "", $ProductoId);

                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken($token);

                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                $UsuarioTokenMySqlDAO->update($UsuarioToken);

                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($usumandanteId);
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
                    $UsuarioToken->setSaldo(0);
                    $UsuarioToken->setProductoId($ProductoId);
                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "casino"; //Revisar esta linea.
            }


            $ConfigurationEnvironment = new ConfigurationEnvironment();
            $partner = $UsuarioMandante->getMandante();

            if ($ConfigurationEnvironment->isDevelopment()) {
                $this->username = "Virtualsoft_CW_api_stg";
                $this->password = "NhVY3YfmxqBT4WCH";
                $this->secretKey = "36797761-6a98-450a-a225-72c9a6061d07";

                if ($partner == "0") {
                    $this->username = "sw_dorabet_apistg";
                    $this->password = "9HCpPZVNZK5j6V3T";
                    $this->secretKey = "ac364266-c8e1-4409-8052-37dc2ca6da3b";
                } elseif ($partner == "6") {
                    //$this->username=""; //Cambiar
                    //$this->password=""; //Cambiar
                } elseif ($partner == "8") {
                    //$this->username=""; //Cambiar
                    //$this->password=""; //Cambiar
                }
            } else {
                if ($partner == "0") {
                    $this->username = "sw_doradobet_apiprod";
                    $this->password = "cspGGz7fDj4n7e4S";
                    $this->secretKey = "577e46c7-0a24-4b32-a059-6bfc27acdd78";
                } elseif ($partner == "6") {
                    //$this->username=""; //Cambiar
                    //$this->password=""; //Cambiar
                } elseif ($partner == "8") {
                    //$this->username=""; //Cambiar
                    //$this->password=""; //Cambiar
                }
            }

            $login = $this->login();

            if ($play_for_fun == true) {
                $array = array(

                    //cashier" => $this->PartnerID, //Modificar variable
                    "playerCode" => 'Usuario' . $usumandanteId,
                    "gameCode" => $GameCode,
                    "ticket" => $UsuarioToken->token, //Verificar parametro. A que hace referencia el token.
                    //"type" => $UsuarioMandante->moneda, //Modificar parametro
                    "playmode" => "fun",
                    "language" => $lang,
                    "lobby" => $this->URLREDIRECTION,
                    "ip" => $this->get_client_ip(),
                    "accessToken" => $login->accessToken
                );
            } elseif ($play_for_fun == false) {
                $array = array(

                    //cashier" => $this->PartnerID, //Modificar variable
                    "playerCode" => 'Usuario' . $usumandanteId,
                    "gameCode" => $GameCode,
                    "ticket" => $UsuarioToken->token, //Verificar parametro. A que hace referencia el token.
                    //"type" => $UsuarioMandante->moneda, //Modificar parametro
                    "playmode" => "real",
                    "language" => $lang,
                    "lobby" => $this->URLREDIRECTION,
                    "ip" => $this->get_client_ip(),
                    "accessToken" => $login->accessToken

                );
            }

            $response = $this->connectionGet($array);

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $array = array(
                "error" => false,
                "response" => $response->url,
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
            //print_r($e);
        }
    }

    /**
     * Realiza el proceso de inicio de sesión con las credenciales configuradas.
     *
     * Este método utiliza las credenciales actuales (clave secreta, nombre de usuario y contraseña)
     * para realizar una solicitud POST al servicio de inicio de sesión de Skywind.
     *
     * @return object|null Respuesta del servicio de inicio de sesión, que incluye el token de acceso.
     */
    public function login()
    {
        $data = array(
            "secretKey" => $this->secretKey,
            "username" => $this->username,
            "password" => $this->password
        );


        $this->tipo = "login";

        $response = $this->connectionPOST($data);

        return $response;
    }

    /**
     * Realiza una solicitud POST al servicio configurado.
     *
     * Este método envía datos en formato JSON a la URL configurada, utilizando
     * el método HTTP POST. Se espera que la URL y el tipo de operación estén
     * configurados previamente en las propiedades de la clase.
     *
     * @param array $data Datos a enviar en la solicitud POST.
     *
     * @return object|null Respuesta decodificada del servicio o null en caso de error.
     */
    public function connectionPOST($data)
    {
        $data = json_encode($data);

        $headers = array(
            "Content-type: application/json",
        );

        $ch = curl_init($this->URL . $this->tipo);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $result = (curl_exec($ch));

        $result = json_decode($result);

        return ($result);
    }

    /**
     * Realiza una solicitud GET al servicio configurado para obtener información de un juego.
     *
     * Este método construye una URL basada en los datos proporcionados, incluyendo el código
     * del jugador, el código del juego y el ticket. Luego, realiza una solicitud GET utilizando
     * cURL y devuelve la respuesta decodificada.
     *
     * @param array $data Datos necesarios para la solicitud, incluyendo:
     *                    - playerCode: Código del jugador.
     *                    - gameCode: Código del juego.
     *                    - ticket: Token del usuario.
     *                    - playmode: Modo de juego (real o diversión).
     *                    - language: Idioma del juego.
     *                    - lobby: URL del lobby.
     *                    - ip: Dirección IP del cliente.
     *                    - accessToken: Token de acceso para la autenticación.
     *
     * @return object|null Respuesta decodificada del servicio o null en caso de error.
     */
    public function connectionGet($data)
    {
        $data = json_decode(json_encode($data));
        $playerCode = $data->playerCode;
        $gameCode = $data->gameCode;
        $ticket = $data->ticket; //Verificar parametro. A que hace referencia el token.
        $type = $data->type; //Modificar parametro
        $playmode = $data->playmode;
        $language = $data->language;
        $lobby = $data->lobby;
        $ip = $data->ip;

        $data = json_decode(json_encode($data));

        $accessToken = 'x-access-token:' . $data->accessToken;

        $header = array(
            $accessToken,
            'Content-Type: application/json'
        );

        $SuperURL = $this->URL . 'players/' . $playerCode . '/games/' . $gameCode . '?ticket=' . $ticket;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $SuperURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $header,
        ));
        if ($_ENV['debug']) {
            print_r($SuperURL);
        }
        $response = curl_exec($curl);
        if ($_ENV['debug']) {
            print_r($response);
        }
        $response = json_decode($response);
        curl_close($curl);

        return $response;
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * Este método verifica varias variables de entorno para determinar la dirección IP
     * del cliente que realiza la solicitud. Si no se encuentra ninguna dirección IP válida,
     * devuelve 'UNKNOWN'.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
     */
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

}



