<?php

/**
 * Clase SOFTSWISSSERVICES
 *
 * Esta clase proporciona servicios de integración con el proveedor SOFTSWISS.
 * Incluye métodos para obtener juegos, lanzar sesiones, agregar giros gratis y manejar solicitudes HTTP.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase SOFTSWISSSERVICES
 *
 * Proporciona métodos para la integración con el proveedor SOFTSWISS,
 * incluyendo la gestión de juegos, sesiones, giros gratis y solicitudes HTTP.
 */
class SOFTSWISSSERVICES
{
    /**
     * Constructor de la clase.
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene un juego y lanza una sesión para el usuario.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $LaunchId      ID de lanzamiento.
     * @param boolean $isMobile      Indica si el dispositivo es móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $LaunchId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "SOFTSWISS");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken($token);
                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
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
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
                    $UsuarioToken->setSaldo(0);
                    $UsuarioToken->setProductoId($Producto->productoId);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Registro = new Registro("", $Usuario->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            $saldo = str_replace(',', '', number_format(round($Usuario->getBalance(), 2), 2, '.', null));

            if ($Mandante->baseUrl != '') {
                $origin = $Mandante->baseUrl . "new-casino";
                $deposit = $Mandante->baseUrl . "gestion/deposito";
            }

            $deviceType = "desktop";
            if ($isMobile == true) {
                $deviceType = "mobile";
            }

            $playMode = "sessions";
            if ($play_for_fun == true) {
                $playMode = "demo";
            }

            $ip = explode(',', $this->get_client_ip());
            $ip = $ip[0];

            $Pais = new Pais($UsuarioMandante->paisId);

            try {
                $Ciudad = new Ciudad($Registro->ciudadId);
                $ciudad = $Ciudad->ciudadNom;
            } catch (Exception $e) {
                $ciudad = 'Ciudad';
            }

            $UserInfo = new UsuarioOtrainfo($Usuario->usuarioId);
            $registered = explode(' ', $Usuario->fechaCrea);

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $data = array();
            $data['casino_id'] = $credentials->CASINO_ID;
            $data['game'] = $gameid;
            $data['currency'] = $UsuarioMandante->moneda;
            $data['locale'] = $lang;
            $data['ip'] = $ip;

            if ($play_for_fun == true) {
                $data['client_type'] = $deviceType;
                $data['urls'] = [
                    'return_url' => $origin,
                ];
                $data['jurisdiction'] = $Pais->iso;
            } else {
                $data['balance'] = intval($saldo) * 100;
                $data['client_type'] = $deviceType;
                $data['urls'] = [
                    'deposit_url' => $deposit,
                    'return_url' => $origin,
                ];
                $data['jurisdiction'] = $Pais->iso;
                $data['user'] = [
                    'id' => $UsuarioMandante->usumandanteId,
                    'external_id' => $UsuarioToken->getToken(),
                    'email' => $Registro->email,
                    'firstname' => $Registro->nombre,
                    'lastname' => $Registro->apellido1,
                    'nickname' => $Registro->nombre1,
                    'city' => $ciudad,
                    'country' => $Pais->iso,
                    'date_of_birth' => $UserInfo->fechaNacim,
                    'gender' => $Registro->sexo,
                    'registered_at' => $registered[0],
                ];
            }

            $AutchSign = hash_hmac('sha256', json_encode($data), $credentials->AUTH_TOKEN);

            $Patch = '/' . $playMode;

            $Launch = $this->Launch(json_encode($data), $AutchSign, $credentials->URL . $Patch);
            $LaunchUrl = $Launch->launch_options;

            if ($_REQUEST['isDebug'] == 1) {
                print_r(json_encode($data));
                print_r('/////###/////');
                print_r(json_encode($Launch));
            }

            $array = array(
                "error" => false,
                "response" => $LaunchUrl->game_url
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
        }
    }

    /**
     * Lanza una solicitud HTTP para iniciar una sesión de juego.
     *
     * @param string $data      Datos de la solicitud en formato JSON.
     * @param string $AutchSign Firma de autenticación.
     * @param string $URL       URL del servicio.
     *
     * @return object Respuesta del servicio.
     */
    public function Launch($data, $AutchSign, $URL)
    {
        $curl = new CurlWrapper($URL);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'X-REQUEST-SIGN: ' . $AutchSign,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();
        return json_decode($response);
    }

    /**
     * Agrega giros gratis a un usuario.
     *
     * @param string  $bonoId              ID del bono.
     * @param integer $roundsFree          Cantidad de giros gratis.
     * @param float   $roundvalue          Valor de cada giro.
     * @param string  $EndDate             Fecha de expiración de los giros.
     * @param string  $user                ID del usuario.
     * @param array   $games               Lista de juegos aplicables.
     * @param string  $aditionalIdentifier Identificador adicional del free spin.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoId, $roundsFree, $roundvalue, $EndDate, $user, $games, $aditionalIdentifier)
    {
        $Usuario = new Usuario($user);
        $Registro = new Registro("", $Usuario->usuarioId);
        try {
            $Ciudad = new Ciudad($Registro->ciudadId);
            $ciudad = $Ciudad->ciudadNom;
        } catch (Exception $e) {
            $ciudad = 'Ciudad';
        }
        $UserInfo = new UsuarioOtrainfo($Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Pais = new Pais($UsuarioMandante->paisId);
        $registered = explode(' ', $Usuario->fechaCrea);

        $Proveedor = new Proveedor("", "SOFTSWISS");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $timestamp = strtotime($EndDate);
        $microtime = microtime(true);
        $microseconds = sprintf("%06d", ($microtime - floor($microtime)) * 1000000);
        $fechaFormateada = gmdate('Y-m-d\TH:i:s', $timestamp) . '.' . $microseconds . 'Z';

        $array = array(
            "casino_id" => $credentials->CASINO_ID,
            "issue_id" => $bonoId . $aditionalIdentifier . '_' . $UsuarioMandante->usumandanteId . '_' . $games[0] . '_' . $UsuarioMandante->moneda,
            "currency" => $UsuarioMandante->moneda,
            "games" => $games,
            "valid_until" => '2023-07-28T05:00:00.676455Z', //$fechaFormateada,
            "bet_level" => 1,
            "freespins_quantity" => $roundsFree,
            "user" => array(
                "id" => $UsuarioMandante->usumandanteId,
                "email" => $UsuarioMandante->email,
                "firstname" => $Registro->nombre,
                "lastname" => $Registro->apellido1,
                "nickname" => $Registro->nombre1,
                "city" => $ciudad,
                "country" => $Pais->iso,
                "date_of_birth" => $UserInfo->fechaNacim,
                "gender" => $Registro->sexo,
                "registered_at" => $registered[0],
            )
        );

        $AutchSign = hash_hmac('sha256', json_encode($array), $credentials->AUTH_TOKEN);
        $patch = '/freespins/issue';

        
        $response = $this->SendFreespins(json_encode($array), $credentials->URL . $patch, $AutchSign);
        
        syslog(LOG_WARNING, "SOFTSWISS BONO DATA: " . json_encode($array) . " RESPONSE: " . json_encode($response));

        $responseCode = http_response_code();

        if ($response->code != '') {
            $return = array(
                "code" => 1,
                "response_code" => $response->code,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response->code,
                "response_message" => 'OK',
                "bonusId" => $bonoId
            );
        }
        return $return;
    }

    /**
     * Envía una solicitud HTTP para agregar giros gratis.
     *
     * @param string $data      Datos de la solicitud en formato JSON.
     * @param string $url       URL del servicio.
     * @param string $AutchSign Firma de autenticación.
     *
     * @return object Respuesta del servicio.
     */
    public function SendFreespins($data, $url, $AutchSign)
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
                'X-REQUEST-SIGN: ' . $AutchSign,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();
        return json_decode($response);
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * @return string Dirección IP del cliente.
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
