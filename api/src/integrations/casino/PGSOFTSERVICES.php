<?php

/**
 * Clase PGSOFTSERVICES
 *
 * Esta clase proporciona servicios relacionados con la integración de juegos de casino del proveedor PGSOFT.
 * Incluye métodos para obtener juegos, lanzar juegos, agregar giros gratis y manejar credenciales.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use \CurlWrapper;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase PGSOFTSERVICES
 *
 * Esta clase contiene métodos para la integración con los servicios del proveedor de juegos PGSOFT.
 * Proporciona funcionalidades como obtención de juegos, lanzamiento de juegos, asignación de giros gratis,
 * y manejo de credenciales relacionadas con los usuarios y productos.
 */
class PGSOFTSERVICES
{
    /**
     * URL base para las solicitudes.
     *
     * @var string
     */
    private $URLR = "";

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
     * Obtiene un juego del proveedor PGSOFT.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $Producto      Producto asociado al juego.
     * @param boolean $isMobile      Indica si el juego es para dispositivos móviles.
     * @param string  $usumandanteId ID del usuario mandante.
     *
     * @return object Respuesta con la URL del juego o un error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $Producto = '', $isMobile, $usumandanteId = "")
    {
        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Proveedor = new Proveedor("", "PGSOFT");
            $Producto = new Producto($Producto);
            $Usumandante = new UsuarioMandante($usumandanteId);
            $ProdMandante = new ProductoMandante($Producto->productoId, $Usumandante->mandante);
            $SubProveedor = new Subproveedor($Producto->getSubproveedorId());
            $TipoSubProveedor = $SubProveedor->getTipo();

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usumandante->mandante, $Usumandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $patch = "new-casino/proveedor/PGSOFT";

            if ($TipoSubProveedor == 'LIVECASINO') {
                $patch = "live-casino-vivo/proveedor/PGSOFTLIVE";
            }

            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => "",
                );
            } else {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

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
                $PaisMandante = new PaisMandante("", $UsuarioMandante->mandante, $UsuarioMandante->paisId);

                if ($PaisMandante->baseUrl != '') {
                    $this->URLR = $PaisMandante->baseUrl . $patch;
                }

                $ip = explode(',', $this->get_client_ip());
                $ip = $ip[0];

                $trace_id = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

                $data = 'operator_token=' . $Credentials->OPERATOR;
                $data = $data . '&path=%2F' . $gameid . '%2Findex.html';
                $data = $data . '&extra_args=';
                $data = $data . 'ops%3D' . $UsuarioToken->getToken();
                $data = $data . '%26btt%3D' . 1;
                $data = $data . '%26op%3D' . $UsuarioMandante->usumandanteId;
                $data = $data . '%26f%3D' . urlencode($this->URLR);
                $data = $data . '%26oc%3D' . 1;
                $data = $data . '%26l%3D' . $lang;
                $data = $data . '&url_type=game-entry';
                $data = $data . '&client_ip=' . $ip;

                $patch = '/external-game-launcher/api/v1/GetLaunchURLHTML';
                $response = $this->LaunchGame($data, $Credentials->URL . $patch, $trace_id);

                if ($ConfigurationEnvironment->isDevelopment()) {
                    $urlMobile = 'https://apidev.virtualsoft.tech/casino/game/play/';
                } else {
                    $urlMobile = 'https://casino.virtualsoft.tech/game/play/';
                }

                $isFun = "demo";
                if ($play_for_fun === false) {
                    $isFun = "real";
                }

                $isMobil = "false";
                if ($isMobile == true) {
                    $isMobil = "true";
                }

                $lan = explode('__', $lang);
                $lang = $lan[0];
                $Mobil = $lan[1];

                $urlMobile = $urlMobile . '?gameid=' . $ProdMandante->prodmandanteId;
                $urlMobile = $urlMobile . '&mode=' . $isFun;
                $urlMobile = $urlMobile . '&provider=PGSOFT';
                $urlMobile = $urlMobile . '&lan=' . $lan . '__isMobile';
                $urlMobile = $urlMobile . '&partnerid=' . $Usumandante->mandante;
                $urlMobile = $urlMobile . '&token=' . $usuarioToken;
                $urlMobile = $urlMobile . '&balance=' . '0';
                $urlMobile = $urlMobile . '&currency=' . $Usumandante->moneda;
                $urlMobile = $urlMobile . '&userid=' . $Usumandante->usuarioMandante;
                $urlMobile = $urlMobile . '&isMobile=' . $isMobil;

                if ($isMobile == true && $Mobil != "isMobile") {
                    $Launch = $urlMobile;
                } else {
                    $Launch = $response;
                }

                $array = array(
                    "error" => false,
                    "response" => $Launch
                );
            }
            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Lanza un juego utilizando cURL.
     *
     * @param string $data     Datos de la solicitud.
     * @param string $url      URL del servicio.
     * @param string $trace_id ID de rastreo para la solicitud.
     *
     * @return string Respuesta del servicio.
     */
    public function LaunchGame($data, $url, $trace_id)
    {
        $curl = new CurlWrapper($url . '?trace_id=' . $trace_id);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . '?trace_id=' . $trace_id,
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

    /**
     * Agrega giros gratis a un usuario.
     *
     * @param string  $bonoId              ID del bono.
     * @param integer $roundsFree          Número de giros gratis.
     * @param float   $roundvalue          Valor de cada giro.
     * @param string  $StartDate           Fecha de inicio del bono.
     * @param string  $EndDate             Fecha de finalización del bono.
     * @param string  $user                Usuario al que se asignan los giros.
     * @param array   $games               Juegos asociados al bono.
     * @param string  $aditionalIdentifier Identificador adicional del freeSpin.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoId, $roundsFree, $roundvalue, $StartDate, $EndDate, $user, $games, $aditionalIdentifier)
    {
        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $Proveedor = new Proveedor("", "PGSOFT");
        $Producto = new Producto("", $games[0], $Proveedor->proveedorId);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $array = [
            'operator_token' => $Credentials->OPERATOR,
            'secret_key' => $Credentials->SECRET_KEY,
            'currency' => $UsuarioMandante->moneda,
            'free_game_name' => $bonoId,
            'expired_date' => strtotime($EndDate) * 1000,
            'conversion_type' => 'B',
            'bet_amount' => $roundvalue,
            'game_count' => $roundsFree,
            'player_name' => $UsuarioMandante->usumandanteId,
            'is_event' => 'true',
            'bonus_ratio' => 1,
            'transaction_id' => $bonoId . $aditionalIdentifier . $Usuario->usuarioId,
        ];

        $game = '';
        foreach ($games as $valor) {
            $game .= '&game_ids=' . $valor;
        }

        $paramString = '';
        foreach ($array as $key => $value) {
            if (!empty($value)) {
                $paramString .= $key . '=' . $value . '&';
            }
        }

        $params = rtrim($paramString, '&') . '&bonus_type=0' . $game;

        $trace_id = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));
        $patch = '/external/FreeGame/v1/CreateFreeGameByBetAmount';

        $response = $this->SendFreespins($params, $Credentials->URL . $patch, $trace_id);

        syslog(LOG_WARNING, "PGSOFT BONO DATA: " . json_encode($array) . " RESPONSE: " . $response);

        $response = json_decode($response);

        if ($response->error != null) {
            $return = array(
                "code" => 1,
                "response_code" => '',
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => '',
                "response_message" => 'OK',
                "bonusId" => $bonoId
            );
        }
        return $return;
    }

    /**
     * Envía la solicitud para agregar giros gratis.
     *
     * @param string $data     Datos de la solicitud.
     * @param string $url      URL del servicio.
     * @param string $trace_id ID de rastreo para la solicitud.
     *
     * @return string Respuesta del servicio.
     */
    public function SendFreespins($data, $url, $trace_id)
    {
        $curl = new CurlWrapper($url . '?trace_id=' . $trace_id);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . '?trace_id=' . $trace_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
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

    /**
     * Obtiene la dirección IP del cliente.
     *
     * @return string Dirección IP del cliente.
     */
    public function get_client_ip()
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
