<?php

/**
 * Clase BELATRASERVICES para la integración con el proveedor BELATRA.
 *
 * Este archivo contiene métodos para gestionar juegos, redirecciones, depósitos,
 * y la asignación de giros gratis (freespins) en la plataforma BELATRA.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Department;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\BonoDetalle;
use Backend\dto\UsuarioBono;
use Backend\dto\Departamento;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase principal para manejar la integración con los servicios de BELATRA.
 */
class BELATRASERVICES
{
    /**
     * URL de redirección para los servicios de BELATRA.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * URL para realizar depósitos en los servicios de BELATRA.
     *
     * @var string
     */
    private $URLDEPOSIT = "";

    /**
     * Constructor de la clase BELATRASERVICES.
     *
     * Inicializa la configuración del entorno para determinar si se está
     * ejecutando en un entorno de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }


    /**
     * Obtiene la URL de inicio de un juego en BELATRA.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID del minijuego.
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante.
     * @param boolean $minigame      Indica si es un minijuego.
     *
     * @return object Respuesta con la URL del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $UsuarioToken, $migameid, $isMobile = false, $usumandanteId = "", $minigame = false)
    {
        $Proveedor = new Proveedor("", "BELATRA");
        $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($UsuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
            $token = $UsuarioToken->createToken();
            $UsuarioToken->setToken($token);
            $UsuarioToken->setProductoId($Producto->productoId);
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
                $UsuarioToken->setToken($UsuarioToken->createToken());
                $UsuarioToken->setSaldo(0);
                $UsuarioToken->setEstado('A');
                $UsuarioToken->setProductoId($Producto->productoId);

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
            $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
        }

        if ($Mandante->baseUrl != '') {
            $this->URLDEPOSIT = $Mandante->baseUrl . "/gestion/deposito";
        }

        $Ip = explode(",", $this->get_client_ip());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $CASINO_ID = $credentials->CASINO_ID;
        $AUTH_TOKEN = $credentials->AUTH_TOKEN;
        $URL = $credentials->URL;

        $launchParams = array(
            "casino_id" => $CASINO_ID,
            "game" => $gameid,
            "currency" => $UsuarioMandante->moneda,
            "locale" => strtolower($lang),
            "ip" => $Ip[0],
            "urls" => array(
                "depositURL" => $this->URLDEPOSIT,
                "redirectURL" => $this->URLREDIRECTION
            ),
            "user" => array(
                "id" => $UsuarioMandante->usumandanteId,
                "email" => $UsuarioMandante->email,
                "firstname" => $UsuarioMandante->nombres,
                "lastname" => $UsuarioMandante->apellidos,
                "nickname" => $UsuarioMandante->nombres,
                "city" => null,
                "country" => null,
                "date_of_birth" => null,
                "gender" => null,
                "registererd_at" => null,
            )
        );

        $signature = hash_hmac('sha256', json_encode($launchParams), $AUTH_TOKEN);
        $response = $this->LaunchUrl(json_encode($launchParams), $URL . '/sessions', $signature);

        $response = json_decode($response);

        $UsuarioToken = new UsuarioToken($UsuarioToken->getToken(), '', '', '', '', $Producto->productoId);
        $token = $response->session_id;
        $UsuarioToken->setToken($token);
        $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
        $UsuarioToken->setProductoId($Producto->productoId);
        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
        $UsuarioTokenMySqlDAO->update($UsuarioToken);
        $UsuarioTokenMySqlDAO->getTransaction()->commit();

        $array = array(
            "error" => false,
            "response" => $response->launch_options->game_url
        );

        if ($_REQUEST['debug'] == '1') {
            print_r("\r\n");
            print_r('****DATA USER****');
            print_r(json_encode($launchParams));
            print_r("\r\n");
            print_r('****LAUNCH URL****');
            print_r(json_encode($array));
            print_r("\r\n");
            print_r('****LAUNCH URL****');
            print_r(json_encode($response));
        }
        return json_decode(json_encode($array));
    }

    /**
     * Lanza una solicitud HTTP POST para iniciar un juego.
     *
     * @param string $data      Datos en formato JSON para la solicitud.
     * @param string $url       URL del servicio de BELATRA.
     * @param string $signature Firma HMAC para la autenticación.
     *
     * @return string Respuesta del servicio.
     */
    public function LaunchUrl($data, $url, $signature)
    {
        $curl = new CurlWrapper($url);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-REQUEST-SIGN: ' . $signature
            ),
        ));

        $response = $curl->execute();
        return $response;
    }

    /**
     * Asigna giros gratis (freespins) a un usuario.
     *
     * @param string  $bonoElegido         Bono seleccionado.
     * @param integer $roundsFree          Cantidad de giros gratis.
     * @param float   $roundsValue         Valor de cada giro.
     * @param string  $StartDate           Fecha de inicio de la validez del bono.
     * @param string  $EndDate             Fecha de fin de la validez del bono.
     * @param string  $user                Usuario al que se asignan los giros.
     * @param array   $games               Lista de juegos aplicables.
     * @param integer $aditionalIdentifier Identificador adicional para el FreeSpin.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoElegido, $roundsFree, $roundsValue, $StartDate, $EndDate, $user, $games, $aditionalIdentifier)
    {
        $Proveedor = new Proveedor("", "BELATRA");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $CASINO_ID = $credentials->CASINO_ID;
        $AUTH_TOKEN = $credentials->AUTH_TOKEN;
        $URL = $credentials->URL;

        $Params = [
            'casino_id' => $CASINO_ID,
            'issue_id' => $bonoElegido . $aditionalIdentifier . $Usuario->usuarioId,
            'currency' => $UsuarioMandante->moneda,
            'games' => array_map(fn($game) => "belatra:" . $game, $games),
            'valid_until' => date('Y-m-d\TH:i:s\Z', strtotime($EndDate)),
            'bet_level' => intval($roundsValue),
            'freespins_quantity' => intval($roundsFree),
            "user" => array(
                "id" => $UsuarioMandante->usumandanteId,
                "email" => $UsuarioMandante->email,
                "firstname" => $UsuarioMandante->nombres,
                "lastname" => $UsuarioMandante->apellidos,
                "nickname" => $UsuarioMandante->nombres,
                "city" => "",
                "gender" => ""
            )

        ];

        $signature = hash_hmac('sha256', json_encode($Params), $AUTH_TOKEN);

        $path = '/freespins/issue';
        
        $response = $this->SendFreespins(json_encode($Params), $URL . $path, $signature);
        
        syslog(LOG_WARNING, "BELATRA BONO DATA: " . json_encode($Params) . " RESPONSE: " . $response);

        $response = json_decode($response);
        $responseCode = http_response_code();

        if ($responseCode != '200') {
            $return = array(
                "code" => 1,
                "response_code" => 0,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response,
                "response_message" => 'OK'
            );
        }
        return $return;
    }


    /**
     * Envía una solicitud HTTP POST para asignar giros gratis.
     *
     * @param string $data      Datos en formato JSON para la solicitud.
     * @param string $url       URL del servicio de BELATRA.
     * @param string $signature Firma HMAC para la autenticación.
     *
     * @return string Respuesta del servicio.
     */
    public function SendFreespins($data, $url, $signature)
    {
        $curl = new CurlWrapper($url);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-REQUEST-SIGN: ' . $signature
            ),
        ));

        $response = $curl->execute();
        return $response;
    }

    /**
     * Obtiene la dirección IP del cliente.
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
