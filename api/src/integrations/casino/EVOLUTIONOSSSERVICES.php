<?php

/**
 * Este archivo contiene la clase `EVOLUTIONOSSSERVICES` que gestiona la integración con el proveedor EVOLUTIONOSS.
 * Proporciona métodos para obtener juegos y realizar autenticaciones de usuario.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase `EVOLUTIONOSSSERVICES`.
 * Gestiona la integración con el proveedor EVOLUTIONOSS, proporcionando métodos
 * para obtener juegos y realizar autenticaciones de usuario.
 */
class EVOLUTIONOSSSERVICES
{
    /**
     * URL de redirección utilizada en las configuraciones de juego.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

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
     * Obtiene la información necesaria para lanzar un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $Producto      Producto asociado al juego.
     * @param boolean $isMobile      Indica si el juego se ejecuta en un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL de lanzamiento del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $Producto = '', $isMobile, $usumandanteId = "")
    {
        try {
            $Proveedor = new Proveedor("", "EVOLUTIONOSS");
            $Producto = new Producto($Producto);
            $SubProveedor = new Subproveedor($Producto->getSubproveedorId());
            $TipoSubProveedor = $SubProveedor->getTipo();

            $patch = "new-casino/proveedor/EVOLUTIONOSS";

            if ($TipoSubProveedor == 'LIVECASINO') {
                $patch = "live-casino-vivo/proveedor/EVOLUTIONOSSLIVE";
            }

            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => '',
                );
            } else {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                    $UsuarioToken->setToken($UsuarioToken->createToken());
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
                $Mandante = new Mandante($UsuarioMandante->mandante);
                $Pais = new Pais($UsuarioMandante->paisId);

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . $patch;
                }

                $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $Ip = explode(",", $this->get_client_ip());
                $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

                $data = array();
                $data['uuid'] = $uuid;
                $data['player'] = [
                    'id' => $UsuarioMandante->usumandanteId,
                    'update' => false,
                    'firstName' => $UsuarioMandante->nombres,
                    'lastName' => $UsuarioMandante->apellidos,
                    'nickname' => $UsuarioMandante->nombres,
                    'language' => $lang,
                    'country' => $Pais->iso,
                    'currency' => $UsuarioMandante->moneda,
                    'session' => [
                        'id' => $UsuarioToken->getToken(),
                        'ip' => $Ip[0],
                    ],
                ];
                $data['config'] = [
                    'game' => [
                        'table' => [
                            'id' => $gameid,
                        ],
                    ],
                    'channel' => [
                        'wrapped' => false,
                        'mobile' => $isMobile,
                    ],
                    'urls' => [
                        'cashier' => $this->URLREDIRECTION,
                        'responsibleGaming' => $this->URLREDIRECTION,
                        'lobby' => $this->URLREDIRECTION,
                        'sessionTimeout' => $this->URLREDIRECTION,
                    ],
                ];

                $userAuth = $this->userAuth($data, $Credentials->URL, $Credentials->CASINO, $Credentials->TOKEN);
                $launchURL = json_decode($userAuth);
                $launchURL = $launchURL->entryEmbedded;

                if ($_ENV['debug']) {
                    print_r("\r\n");
                    print_r('****DATA****');
                    print_r(json_encode($data));
                    print_r("\r\n");
                    print_r('****LAUNCH URL****');
                    print_r($userAuth);
                }

                $array = array(
                    "error" => false,
                    "response" => $Credentials->URL . $launchURL
                );
            }
            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza la autenticación del usuario con el proveedor.
     *
     * @param array  $data   Datos del usuario y configuración del juego.
     * @param string $url    URL del proveedor.
     * @param string $Casino Identificador del casino.
     * @param string $Token  Token de autenticación.
     *
     * @return string Respuesta del proveedor en formato JSON.
     */
    function userAuth($data, $url, $Casino, $Token)
    {
        $url = $url . '/ua/v1/' . $Casino . '/' . $Token;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

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
