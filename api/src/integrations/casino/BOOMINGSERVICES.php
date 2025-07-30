<?php

namespace Backend\integrations\casino;

use Exception;
use Throwable;
use \CurlWrapper;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase 'BOOMINGSERVICES'
 *
 * Esta clase provee funciones para la api 'BOOMINGSERVICES'
 *
 * Ejemplo de uso:
 * $BOOMINGSERVICES = new BOOMINGSERVICES();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date: 14.09.17
 *
 */
class BOOMINGSERVICES
{

    /**
     * Representación de 'UrlCallback'
     *
     * @var string
     * @access private
     */
    private $UrlCallback = '';
    private $UrlCallbackDev = 'https://apidevintegrations.virtualsoft.tech/integrations/casino/booming/api/';
    private $UrlCallbackProd = 'https://integrations.virtualsoft.tech/casino/booming/api/';
    
    /**
     * Método constructor
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
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->UrlCallback = $this->UrlCallbackDev;
        } else {
            $this->UrlCallback = $this->UrlCallbackProd;
        }
    }

    /**
     * Obtener la información de un juego mediante una petición
     * con su llave primaria, su lenguaje, si está jugando por diversión
     * y el token del usuario
     *
     * @param String $gameid gameid
     * @param String $lang lang
     * @param String $play_for_fun play_for_fun
     * @param String $usuarioToken usuarioToken
     * @param String $migameid migameid
     * @param boolean $isMobile isMobile
     *
     * @return array $response response
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getGame($GameCode, $lang, $play_for_fun, $usuarioToken = "", $ProductoId, $isMobile, $usumandanteId = "")
    {
        try {

            if ($usumandanteId == "") {

                $UsuarioTokenSite = new UsuarioToken($usuarioToken, "", $usumandanteId, "", "", $ProductoId);
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "BOOMING");
            $Producto = new Producto("", $GameCode, $Proveedor->getProveedorId());

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId, "", "", $ProductoId);
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
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $URL = $credentials->URL;
            $API_KEY = $credentials->API_KEY;
            $API_SECRET = $credentials->API_SECRET;

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $platform = '';
            if ($isMobile == false) {
                $platform = 'desktop';
            } else {
                $platform = 'mobile';
            }

            $ip = explode(',', $this->get_client_ip());
            $ip = $ip[0];


            $array = array(
                "game_id" => $GameCode,
                "balance" => $saldo,
                "locale" => $lang,
                "variant" => $platform,
                "demo" => false,
                "currency" => $responseG->moneda,
                "player_id" => $UsuarioMandante->getUsumandanteId(),
                "player_ip" => $ip,
                "callback" => $this->UrlCallback . "callback",
                "rollback_callback" => $this->UrlCallback . "rollback_callback"
            );

            if ($_ENV['debug']) {
                print_r('JERSON: ');
                print_r(json_encode($array));
                print_r('//··//');
                print_r('RESPONSE: ');
            }


            $nonce = number_format(microtime(true), 4, '', ""); //OK
            $request_body = hash('sha256', stripslashes(json_encode($array)));
            $request_path = '/v3/session';
            $X_Bg_Signature = hash_hmac('sha512', $request_path . $nonce . $request_body, $API_SECRET);


            $response = $this->Request($API_KEY, $nonce, $X_Bg_Signature, $request_path, $array, $URL);

            if ($_ENV['debug']) {
                print_r('DATA: ');
                print_r(json_encode($array));
                print_r('//··//');
                print_r('RESPONSE: ');
                print_r($response);
            }

            $response = json_decode($response);

            $UsuarioToken->setToken($response->session_id);
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $array = array(
                "error" => false,
                "response" => $response->play_url,
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Añadir un bono de giros gratis a los usuarios.
     * 
     * @param string $bonoId              Identificador del bono.
     * @param int    $roundsFree          Número de giros gratis.
     * @param float  $roundsValue         Valor de cada giro gratis.
     * @param string $StartDate           Fecha de inicio del bono.
     * @param string $EndDate             Fecha de finalización del bono.
     * @param array  $users               Lista de usuarios a los que se les asignará el bono.
     * @param array  $games               Lista de juegos asociados al bono.
     * @param string $aditionalIdentifier Identificador del subbono.
     * @param string $nombreBono          Nombre del bono.
     * 
     * @return array Resultado de la operación.
     * @throws Throwable Si ocurre un error durante el proceso.
     */
    public function AddFreespins($bonoId, $roundsFree, $roundsValue, $StartDate, $EndDate, $users, $games, $aditionalIdentifier, $nombreBono) :array {
        try {
            $Users = '';
            foreach ($users as $user) {
                $Usuario = new Usuario($user);
                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                $Users .= $UsuarioMandante->usumandanteId . ',';
                $currency = $UsuarioMandante->moneda;
            }

            $Proveedor = new Proveedor("", "BOOMING");
            $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $URL = $credentials->URL;
            $API_KEY = $credentials->API_KEY;
            $API_SECRET = $credentials->API_SECRET;

            list($fecha, $hora) = explode(' ', $StartDate);
            $fecha_start = $fecha . 'T' . $hora . '+00:00';

            list($fecha, $hora) = explode(' ', $EndDate);
            $fecha_end = $fecha . 'T' . $hora . '+00:00';

            $game_bet_options = [];

            foreach ($games as $game_id) {
                $game_bet_options[$game_id] = [
                    [
                        "currency" => $currency,
                        "bet_per_spin" => $roundsValue
                    ]
                ];
            }

            $Data = array(
                "name" => $bonoId . "B" . $aditionalIdentifier . '_' . $Usuario->usuarioId,
                "campaign_operator_data" => $bonoId,
                "start_date" => $fecha_start,
                "end_date" => $fecha_end,
                "open_to_all" => false,
                "total_spins" => intval($roundsFree),
                "player_list" => rtrim($Users, ','),
                "game_bet_options" => $game_bet_options,
            );

            $id = $aditionalIdentifier . $Usuario->usuarioId;
            $nonce = time() . str_pad(substr($id, -4), 4, "0", STR_PAD_LEFT);

            $request_body = hash('sha256', stripslashes(json_encode($Data)));
            $path = '/v3/campaigns';
            $X_Bg_Signature = hash_hmac('sha512', $path . $nonce . $request_body, $API_SECRET);
            
            $response = $this->SendFreespins($API_KEY, $nonce, $X_Bg_Signature, $path, $Data, $URL);
            syslog(LOG_WARNING, "BOOMING BONO DATA: " . json_encode($Data) . ' | RESPONSE: ' . $response);
            $response = json_decode($response);

            if ($response->campaign_id == null) {
                $return = array(
                    "code" => 1,
                    "response_code" => $response->campaign_id,
                    "response_message" => 'Error'
                );
            } else {
                $return = array(
                    "code" => 0,
                    "response_code" => $response->campaign_id,
                    "response_message" => 'OK',
                    "bonusId" => $bonoId
                );
            }

            return $return;

        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Envía una solicitud para agregar giros gratis.
     *
     * @param string $apikey         Clave de la API.
     * @param string $nonce          Valor único para la solicitud.
     * @param string $X_Bg_Signature Firma de la solicitud.
     * @param string $path           Ruta de la API.
     * @param array  $array          Datos de la solicitud.
     * @param string $url            URL base de la API.
     *
     * @return string Respuesta de la API.
     */
    public function SendFreespins($apikey, $nonce, $X_Bg_Signature, $path, $array, $url)
    {
        $curl = new CurlWrapper($url . $path);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => stripslashes(json_encode($array)),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/vnd.api+json',
                'X-Bg-Api-Key: ' . $apikey,
                'X-Bg-Nonce: ' . $nonce,
                'X-Bg-Signature: ' . $X_Bg_Signature
            ),
        ));

        $response = $curl->execute();
        return $response;
    }


    /**
     * Realiza una solicitud HTTP a la API de BOOMING.
     *
     * @param string $apikey         Clave de la API.
     * @param string $nonce          Valor único para la solicitud.
     * @param string $X_Bg_Signature Firma de la solicitud.
     * @param string $path           Ruta de la API.
     * @param array  $array          Datos de la solicitud.
     * @param string $url            URL base de la API.
     *
     * @return string Respuesta de la API.
     */
    public function Request($apikey, $nonce, $X_Bg_Signature, $path, $array, $url)
    {
        $curl = new CurlWrapper($url . $path);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => stripslashes(json_encode($array)),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/vnd.api+json',
                'X-Bg-Api-Key: ' . $apikey,
                'X-Bg-Nonce: ' . $nonce,
                'X-Bg-Signature: ' . $X_Bg_Signature
            ),
        ));

        $response = $curl->execute();
        return $response;
    }

    /**
     * Obtener la ip de la conexión actual
     *
     *
     * @return String $ipaddress ip
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
}
