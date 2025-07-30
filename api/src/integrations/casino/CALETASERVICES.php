<?php

/**
 * Clase CALETASERVICES
 *
 * Este archivo contiene la implementación de servicios relacionados con la integración de juegos de casino
 * del proveedor CALETA. Proporciona métodos para obtener URLs de juegos, manejar tokens de usuario y realizar
 * solicitudes HTTP con autenticación.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase que proporciona servicios relacionados con la integración de juegos de casino
 * del proveedor CALETA. Incluye métodos para manejar tokens, obtener URLs de juegos
 * y realizar solicitudes HTTP autenticadas.
 */
class CALETASERVICES
{
    /**
     * URL de redirección para el juego.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * URL para realizar depósitos.
     *
     * @var string
     */
    private $URLDEPOSIT = '';

    /**
     * Firma de autenticación para solicitudes.
     *
     * @var string
     */
    private $XAuth = "";

    /**
     * Clave pública en formato PEM.
     *
     * @var string
     */
    private $public_key_pem = <<<EOD
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAudEt670VRD2gdDpeyY4j
z5PIDJtlc8cqPhZ/73FJ1sVRYEA/lvo0gsQ6YcEwERW33bpCUC+IQGvQ7R1Uknto
zBn3Oa4eflfJgXS8Ml0CM6Fp58+dVqhSf3P5VribuObpmopmDhzMcf8isc9u9mTz
2WDVzts19neljXe8B3u6Tkv5LvVxeOKRtbAHSX8l+ds/XiKkjg8lIg7DRwFY0qwR
VKi0KPNrPfJFTePOTng8OQc2hdOfePU+Ssm+mNUth9ve/zv06euHXDl44h2xhqYT
Y/kOJfySEJKaxvTkdoaCbxnrasCgV0+/guoP+7wSXOx2Rnlsj1tpvCfbxHi3CYJI
/QIDAQAB
-----END PUBLIC KEY-----
EOD;

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
     * Obtiene la URL de un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $GAMEID        ID del juego en formato entero.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $productoId    ID del producto.
     * @param boolean $isMobile      Indica si el juego es para dispositivos móviles.
     * @param string  $usumandanteId ID del usuario mandante.
     *
     * @return object Respuesta con la URL del juego.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $GAMEID, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            $Proveedor = new Proveedor("", "CALETA");
            $Producto = new Producto('', $gameid, $Proveedor->getProveedorId());

            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => "https://staging.the-rgs.com/api/game/url?&lobbyURL=" . $this->URLREDIRECTION . "&lang=ES" . "&game_code=" . $gameid . "&game_code=" . $gameid . "&deposit_url=" . $this->URLDEPOSIT
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
                        $UsuarioToken->setProductoId($productoId);

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
                    $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
                    $this->URLDEPOSIT = $Mandante->baseUrl . "gestion/deposito";
                }

                $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $array = array(
                    "user" => "user" . $UsuarioMandante->usumandanteId,
                    "token" => $UsuarioToken->getToken(),
                    "sub_partner_id" => $Credentials->PARTNER_ID,
                    "operator_id" => $Credentials->OPERATOR_ID,
                    "lobbyURL" => $this->URLREDIRECTION,
                    "lang" => "es",
                    "game_id" => intval($GAMEID),
                    "game_code" => $gameid,
                    "deposit_url" => $this->URLDEPOSIT,
                    "currency" => $UsuarioMandante->moneda,
                    "country" => $Pais->iso
                );

                $data = json_encode($array);

                $binary_signature = "";
                openssl_sign($data, $binary_signature, base64_decode($Credentials->PRIVATE_KEY), "SHA256");

                $this->XAuth = base64_encode($binary_signature);

                $response = $this->Request($data, $Credentials->URL);

                $array = array(
                    "error" => false,
                    "response" => $response->url
                );
            }
            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una solicitud HTTP POST.
     *
     * @param string $data Datos a enviar en la solicitud.
     * @param string $URL  URL del endpoint.
     *
     * @return object Respuesta del servidor.
     */
    public function Request($data, $URL)
    {
        if ($_ENV['debug']) {
            print_r($data);
        }

        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "X-Auth-Signature:" . $this->XAuth));

        $result = json_decode(curl_exec($ch));

        if ($_ENV['debug']) {
            print_r($result);
        }
        return ($result);
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
