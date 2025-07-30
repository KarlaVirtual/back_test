<?php

/**
 * Clase PLAYTECHSERVICES
 *
 * Esta clase proporciona servicios de integración con la plataforma Playtech, incluyendo
 * funcionalidades para obtener juegos, otorgar giros gratis y realizar conexiones seguras
 * con la API de Playtech.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use DateTime;
use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase PLAYTECHSERVICES
 *
 * Proporciona métodos para la integración con la plataforma Playtech,
 * incluyendo funcionalidades como obtención de juegos, otorgamiento de
 * giros gratis y manejo de conexiones seguras con la API.
 */
class PLAYTECHSERVICES
{
    /**
     * URL de redirección para la integración.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * Contraseña utilizada para la conexión SSL.
     *
     * @var string
     */
    private $Password = '';

    /**
     * Código de plantilla para las solicitudes.
     *
     * @var string
     */
    private $TemplateCode = '';

    /**
     * URL para depósitos.
     *
     * @var string
     */
    private $URLDEPOSIT = '';

    /**
     * Identificador de entorno (producción o desarrollo).
     *
     * @var string
     */
    private $DOR = '';
    /**
     * Identificador de entorno para desarrollo.
     *
     * @var string
     */
    private $DORDEV = "DOR__";

    /**
     * Identificador de entorno para producción.
     *
     * @var string
     */
    private $DORROD = "";

    /**
     * Constructor de la clase.
     * Configura el entorno de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->DOR = $this->DORDEV;
        } else {
            $this->DOR = $this->DORROD;
        }
    }

    /**
     * Obtiene un juego de la plataforma Playtech.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo "jugar por diversión".
     * @param string  $usuarioToken  Token del usuario.
     * @param integer $productoId    ID del producto.
     * @param boolean $isMobile      Indica si el juego es para dispositivos móviles.
     * @param string  $usumandanteId ID del usuario mandante.
     *
     * @return object Respuesta con los datos del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            $Proveedor = new Proveedor("", "PLAYTECH");

            $UsuarioMandante = new UsuarioMandante($usumandanteId);
            $Mandante = new Mandante($UsuarioMandante->mandante);

            $Producto = new Producto($productoId);
            $SubProveedor = new Subproveedor($Producto->getSubproveedorId());
            $TipoSubProveedor = $SubProveedor->getTipo();

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());
            $ProductoDetalle = new ProductoDetalle('', $Producto->productoId, 'GAMEID');

            $patch = "new-casino/proveedor/PLAYTECH";

            if ($TipoSubProveedor == 'LIVECASINO') {
                $gameid = $ProductoDetalle->pValue;
                $game = explode("_", $gameid);
                $gameid = $game[0] . ';' . $gameid;
                $patch = "live-casino-vivo/proveedor/PLAYTECHLIVE";
                $gameid = $ProductoDetalle->pValue;
            }

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . $patch;
                $this->URLDEPOSIT = $Mandante->baseUrl . 'gestion/deposito';
            }

            if ($isMobile == true) {
                $Platform = "mobile"; //Mobile
            } else {
                $Platform = 'web'; //Desktop
            }

            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => $Credentials->URL . "/GameLauncher?gameCodeName=" . $gameid . "&username=" . $this->DOR . $usumandanteId . "&externalToken=" . $usuarioToken . "&casino=" . $Credentials->CASINO . "&clientPlatform=" . $Platform . "&language=" . $lang . "&playMode=1" . "&integration=ucip&lobbyUrl=" . $this->URLREDIRECTION . "&depositUrl=" . $this->URLDEPOSIT
                );
                return json_decode(json_encode($array));
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
                        $UsuarioToken->setProductoId(0);

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                switch (strtolower($lang)) {
                    case 'es';
                        $lang = 'es';
                        break;
                    case 'en';
                        $lang = 'en';
                        break;
                    case 'pt';
                        $lang = 'pt_br';
                        break;
                }

                $array = array(
                    "error" => false,
                    "response" => $Credentials->URL . "/GameLauncher?gameCodeName=" . $gameid . "&username=" . $this->DOR . $UsuarioMandante->usumandanteId . "&externalToken=" . $UsuarioToken->getToken() . "&casino=" . $Credentials->CASINO . "&clientPlatform=" . $Platform . "&language=" . $lang . "&playMode=1" . "&lobbyUrl=" . $this->URLREDIRECTION . "&depositUrl=" . $this->URLDEPOSIT
                );
                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Otorga giros gratis o fichas doradas a un usuario.
     *
     * @param string  $bonoId              ID del bono.
     * @param integer $rounds              Número de giros.
     * @param float   $value               Valor de cada giro.
     * @param string  $EndDate             Fecha de finalización del bono.
     * @param string  $user                Usuario al que se otorgan los giros.
     * @param array   $games               Juegos asociados al bono.
     * @param string  $aditionalIdentifier Identificador adicional para el freeSpin.
     * @param boolean $GoldenChip          Indica si se otorgan fichas doradas.
     * @param string  $TemplateCode        Código de plantilla.
     * @param array   $games2              Juegos adicionales.
     *
     * @return array Respuesta con el estado del bono.
     */
    public function givefreespins($bonoId, $rounds, $value, $EndDate, $user, $games, $aditionalIdentifier, $GoldenChip, $TemplateCode, $games2)
    {
        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $user = $UsuarioMandante->usumandanteId;
        $Pais = new Pais($UsuarioMandante->paisId);
        $usuario = $this->DOR . $user;
        $this->TemplateCode = $TemplateCode;

        $Proveedor = new Proveedor("", "PLAYTECH");
        $Producto = new Producto("", $games2[0], $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $this->Password = $Credentials->PASSWORD;

        date_default_timezone_set('GMT+0');
        $time = new DateTime('now');
        $date = $time->format('Y-m-d H:i:s.v');

        date_default_timezone_set('America/Bogota');

        $aditionalIdentifier = $aditionalIdentifier . $bonoId . $Usuario->usuarioId;

        $array = array(
            "requestId" => $bonoId,
            "username" => $usuario,
            "templateCode" => $this->TemplateCode,
            "count" => $rounds,
            "value" => $value,
            "remoteBonusCode" => $aditionalIdentifier,
            "remoteBonusDate" => $date,
            "gameCodeNames" => $games,
            "playerData" =>
                array(
                    "currencyCode" => $UsuarioMandante->moneda,
                    "countryCode" => $Pais->iso,
                )
        );

        if ($GoldenChip) {
            $array = array(
                "requestId" => $bonoId,
                "username" => $usuario,
                "templateCode" => $this->TemplateCode,
                "count" => $rounds,
                "value" => $value,
                "remoteBonusCode" => $aditionalIdentifier,
                "remoteBonusDate" => $date,
                "gameCodeNames" => $games,
            );
            $patch = '/product/gameslink/service/givegoldenchips';
        } else {
            $patch = '/product/gameslink/service/givefreespins';
        }

        if ( ! $GoldenChip) {
            unset($array['value']);
        }

        $response = $this->connection(json_encode($array), $Credentials->URL_BONUS . $patch, $Credentials->SSL_CERT, $Credentials->SSL_KEY);

        syslog(LOG_WARNING, "PLAYTECH BONO DATA: " . $UsuarioMandante->usuarioMandante . " " . json_encode($array) . ' RESPONSE: ' . $response);

        $response = json_decode($response);

        if ($response->error->code == 'ERR_PLAYER_NOT_FOUND') {
            $array2 = array(
                "requestId" => $bonoId,
                "username" => $usuario,
                "templateCode" => $this->TemplateCode,
                "count" => $rounds,
                "value" => $value,
                "remoteBonusCode" => $aditionalIdentifier,
                "remoteBonusDate" => $date,
                "gameCodeNames" => $games,
                "playerData" =>
                    array(
                        "currencyCode" => $UsuarioMandante->moneda,
                        "countryCode" => $Pais->iso,
                    )
            );

            if ( ! $GoldenChip) {
                unset($array2['value']);
            }

            $response2 = $this->connection(json_encode($array2), $Credentials->URL_BONUS . $patch, $Credentials->SSL_CERT, $Credentials->SSL_KEY);

            syslog(LOG_WARNING, "PLAYTECH BONO DATA ERROR: " . $UsuarioMandante->usuarioMandante . " " . json_encode($array2) . ' RESPONSE: ' . $response2);

            $response = json_decode($response2);
        }

        if ($response->bonusInstanceCode == "") {
            $return = array(
                "code" => 1,
                "response_code" => $response->error->code,
                "response_message" => $response->error->description
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response->bonusInstanceCode,
                "response_message" => 'OK',
                "bonusId" => $response->requestId
            );
        }
        return $return;
    }

    /**
     * Realiza una conexión segura con la API de Playtech.
     *
     * @param string $data        Datos a enviar en la solicitud.
     * @param string $apiEndpoint URL del endpoint de la API.
     * @param string $ssl_Cert    Ruta del certificado SSL.
     * @param string $ssl_Key     Ruta de la clave SSL.
     *
     * @return string Respuesta de la API.
     */
    public function connection($data, $apiEndpoint, $ssl_Cert, $ssl_Key)
    {
        $headers = [
            "Content-Type: application/json",
        ];

        $sep = DIRECTORY_SEPARATOR;
        $routes = explode($sep, dirname(__DIR__));
        array_pop($routes);
        $dir = implode($sep, $routes);

        $sslCert = $dir . $sep . 'imports' . $sep . 'playtech' . $sep . 'ssl' . $sep . 'seamless' . $sep . $ssl_Cert;
        $sslKey = $dir . $sep . 'imports' . $sep . 'playtech' . $sep . 'ssl' . $sep . 'seamless' . $sep . $ssl_Key;

        // Inicializar la clase CurlWrapper
        $curl = new CurlWrapper($apiEndpoint);

        // Configurar opciones
        $curl->setOptionsArray(array(
            CURLOPT_TIMEOUT => 30,
            CURLOPT_URL => $apiEndpoint,
            CURLOPT_SSLCERT => $sslCert,
            CURLOPT_SSLKEY => $sslKey,
            CURLOPT_ENCODING => $sslKey,
            CURLOPT_TIMEOUT => $sslKey,
            CURLOPT_MAXREDIRS => $sslKey,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSLCERTPASSWD => $this->Password
        ));

        // Ejecutar la solicitud
        $result = $curl->execute();
        return $result;
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
