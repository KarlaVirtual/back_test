<?php

/**
 * Clase EVOPLAYSERVICES
 *
 * Este archivo contiene la implementación de servicios relacionados con EVOPLAY.
 * Proporciona métodos para gestionar juegos, generar claves y firmas, realizar solicitudes HTTP,
 * y manejar bonificaciones como giros gratis.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @version    1.0.0
 * @since      2025-04-27
 * @author     Desconocido
 */

namespace Backend\integrations\casino;

use Exception;
use \CurlWrapper;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\dto\ProveedorMandante;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase EVOPLAYSERVICES
 *
 * Esta clase proporciona métodos para interactuar con los servicios de EVOPLAY,
 * incluyendo la gestión de juegos, generación de claves y firmas, solicitudes HTTP,
 * y manejo de bonificaciones como giros gratis.
 */
class EVOPLAYSERVICES
{
    /**
     * Constructor de la clase EVOPLAYSERVICES.
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
     * Obtiene la URL de un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego (es, en, pt).
     * @param boolean $play_for_fun  Indica si es modo de prueba.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en el sistema.
     * @param boolean $isMobile      Indica si es para móvil.
     * @param string  $usumandanteId ID del usuario mandante.
     *
     * @return object Respuesta con la URL del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "EVOPLAY");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken(substr($token, 0, strlen($token) - strlen("vssv" . $migameid)) . "vssv" . $migameid);
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
                    $UsuarioToken->setToken(substr($token, 0, strlen($token) - strlen("vssv" . $migameid)) . "vssv" . $migameid);
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

            $UsuarioMandante = new UsuarioMandante($usumandanteId);

            $mode = 'real';
            if ($lang == "en") {
                $lang = "en";
            } elseif ($lang == "pt") {
                $lang = "pt";
            } else {
                $lang = "es";
            }

            $Mandante = new Mandante($UsuarioMandante->getMandante());
            $exitUrl = $Mandante->baseUrl . 'new-casino';

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $PROJECT_LAUNCH = $credentials->PROJECT_LAUNCH;
            $HASH_LAUNCH = $credentials->HASH_LAUNCH;
            $VERSION = $credentials->VERSION;
            $URL = $credentials->URL;

            $parmsstring = '&' . 'token=' . $UsuarioToken->getToken() . '&game=' . $gameid . '&settings[user_id]=' . $UsuarioToken->getUsuarioId() . '&settings[exit_url]=' . $exitUrl . '&denomination=' . '1' . '&currency=' . $UsuarioMandante->getMoneda() . '&return_url_info=' . '1' . '&callback_version=' . '2';
            $url_params = explode("&", $parmsstring);

            $valuesstring = '';
            for ($c = 0; $c < oldCount($url_params); $c++) {
                $kv = explode("=", $url_params[$c]);
                if ($kv[1] != '') {
                    $valuesstring = $valuesstring . '*' . $kv[1];
                }
            }

            $pos_https = strpos($valuesstring, 'https');
            $pos_asterisk_before_https = strrpos($valuesstring, '*', -(strlen($valuesstring) - $pos_https));
            if ($pos_asterisk_before_https !== false) {
                $valuesstring = substr_replace($valuesstring, ':', $pos_asterisk_before_https, 1);
            }

            $data = $this->requestGET($URL, 'Game/getURL?' . 'project=' . $PROJECT_LAUNCH . '&' . 'version=' . $VERSION . '&' . 'signature=' . md5($PROJECT_LAUNCH . '*' . $VERSION . $valuesstring . '*' . $HASH_LAUNCH) . $parmsstring);

            if ($_REQUEST['isDebug'] == '1') {
                print_r($PROJECT_LAUNCH . '*' . $VERSION . $valuesstring . '*' . $HASH_LAUNCH);
                print_r(PHP_EOL);
                print_r(PHP_EOL);
                print_r($data);
            }

            $data = json_decode($data);

            $array = array(
                "error" => false,
                "response" => preg_replace("/\bhttps?\b/", 'https', $data->data->link),
            );
            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }


    /**
     * Genera una clave única basada en el jugador.
     *
     * @param string $player Identificador del jugador.
     *
     * @return string Clave generada.
     */
    function generateKey($player)
    {
        $hash = md5($player . md5("TMP" . $player));
        $hash = substr($hash, 0, 12);
        return ($hash);
    }

    /**
     * Genera una firma HMAC basada en los parámetros y un secreto.
     *
     * @param array  $params Parámetros a incluir en la firma.
     * @param string $secret Clave secreta para la firma.
     *
     * @return string Firma generada.
     */
    function generateSig($params, $secret)
    {
        $sig = null;
        foreach (explode(",", GET_PARAMS_NOT_IN_SIG) as $key) { //exclude params not to calculate
            unset($params[$key]);
        }


        ksort($params);
        foreach ($params as $key => $val) {
            $sig .= "|$key=$val";
        }

        return hash_hmac('sha256', $sig, $secret, false);
    }

    /**
     * Realiza una solicitud HTTP GET.
     *
     * @param string $URL  URL base de la solicitud.
     * @param string $text Parámetros adicionales para la solicitud.
     *
     * @return string Respuesta de la solicitud.
     */
    public function requestGET($URL, $text)
    {
        //InicializarlaclaseCurlWrapper
        $curl = new CurlWrapper($URL . $text);

        $curl->setOptionsArray(array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],

        ));
        $result = $curl->execute();
        return ($result);
    }

    /**
     * Realiza una solicitud HTTP POST.
     *
     * @param string $text Parámetros adicionales para la solicitud.
     *
     * @return string Respuesta de la solicitud.
     */
    public function requestPOST($text)
    {
        //InicializarlaclaseCurlWrapper
        $curl = new CurlWrapper($this->URL . $text);

        $curl->setOptionsArray(array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ));

        $result = $curl->execute();

        return ($result);
    }

    /**
     * Agrega giros gratis a un usuario.
     *
     * @param integer $bonoId      ID del bono.
     * @param integer $roundsFree  Número de giros gratis.
     * @param float   $roundsValue Valor de cada giro.
     * @param string  $StartDate   Fecha de inicio.
     * @param string  $EndDate     Fecha de fin.
     * @param string  $user        Usuario al que se asignan los giros.
     * @param array   $games       Juegos asociados.
     * @param integer $aditionalIdentifier   ID del bono del usuario.
     * @param string  $nombreBono  Nombre del bono.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoId, $roundsFree, $roundsValue, $StartDate, $EndDate, $user, $games, $aditionalIdentifier, $nombreBono)
    {
        try {
            $Usuario = new Usuario($user);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            $currency = $UsuarioMandante->moneda;
            $user = $UsuarioMandante->usumandanteId;

            $Proveedor = new Proveedor("", "EVOPLAY");
            $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $PROJECT_BONUS = $credentials->PROJECT_LAUNCH;
            $HASH_BONUS = $credentials->HASH_BONUS;
            $VERSION = $credentials->VERSION;
            $URL = $credentials->URL;

            $token = hash('sha256', $user);
            $usuBonoId = $bonoId . $aditionalIdentifier . $Usuario->usuarioId;

            $signature = md5($PROJECT_BONUS . '*' . $VERSION . '*' . $token . '*' . $games[0] . '*' . $currency . '*' . $roundsFree . ':' . $roundsValue . '*' . $user . ':' . $EndDate . ':' . $usuBonoId . '*' . $HASH_BONUS);

            $data = 'project=' . $PROJECT_BONUS . '&version=' . $VERSION . '&signature=' . $signature . "&token=" . $token . "&game=" . $games[0] . "&currency=" . $currency . "&extra_bonuses[bonus_spins][spins_count]=" . $roundsFree . "&extra_bonuses[bonus_spins][bet_in_money]=" . $roundsValue . "&settings[user_id]=" . $user . "&settings[expire]=" . $EndDate . "&settings[registration_id]=" . $usuBonoId;

            $path = 'Game/registerBonus';

            $response = $this->SendFreespins($URL, $path, $data);
            
            syslog(LOG_WARNING, "EVOPLAY BONO DATA: " . $data . " RESPONSE: " . $response);

            $response = json_decode($response);

            if ($response->status != 'ok') {
                $return = array(
                    "code" => 1,
                    "response_code" => $response->data->registry_id,
                    "response_message" => 'Error'
                );
            } else {
                $return = array(
                    "code" => 0,
                    "data" => $response->data->registry_id,
                    "response_message" => 'OK',
                    "bonusId" => $bonoId
                );
            }
        } catch (Exception $e) {
            $return = array(
                "code" => 1,
                "response_code" => 0,
                "response_message" => 'Error'
            );
        }

        return $return;
    }

    /**
     * Envía giros gratis a través de una solicitud HTTP POST.
     *
     * @param string $URL  URL base de la solicitud.
     * @param string $path Ruta del endpoint.
     * @param string $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta de la solicitud.
     */
    public function SendFreespins($URL, $path, $data)
    {
        $curl = new CurlWrapper($URL . $path);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = $curl->execute();
        return $response;
    }
}