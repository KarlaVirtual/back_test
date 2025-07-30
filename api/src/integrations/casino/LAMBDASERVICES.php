<?php

/**
 * Clase LAMBDASERVICES
 *
 * Este archivo contiene la implementación de servicios relacionados con la integración de juegos de casino
 * para el proveedor LAMBDA. Incluye métodos para obtener juegos, lanzar URLs, agregar giros gratis,
 * y manejar tokens de usuario.
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

use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use \CurlWrapper;

/**
 * Clase que implementa los servicios relacionados con la integración de juegos de casino
 * para el proveedor LAMBDA. Proporciona métodos para gestionar juegos, URLs, giros gratis
 * y tokens de usuario.
 */
class LAMBDASERVICES
{
    /**
     * URL de redirección para el casino.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * Constructor de la clase LAMBDASERVICES.
     */
    public function __construct() {}

    /**
     * Obtiene la URL de lanzamiento de un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en miniatura.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param boolean $minigame      Indica si es un minijuego.
     *
     * @return object Respuesta con la URL de lanzamiento del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $UsuarioToken, $migameid, $usumandanteId = "", $isMobile = false, $minigame = false)
    {
        $Proveedor = new Proveedor("", "LAMBDA");
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

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $platform = "";

        if ($isMobile) {
            $platform = "mobile";
        } else {
            $platform = "web";
        }

        $array = array(
            "error" => false,
            "response" => $credentials->launchUrl . "?token=" . $UsuarioToken->token . "&gameid=" . $gameid . "&mode=" . $credentials->mode . "&operatorcode=" . $credentials->operatorCode . "&platform=" . $platform . "&language=" . $lang . "&casinoCode=" . strtok($Mandante->descripcion, ".") . "&homeurl=" . $this->URLREDIRECTION
        );

        return json_decode(json_encode($array));
    }

    /**
     * Realiza una solicitud POST a una URL específica.
     *
     * @param string $data Datos a enviar en la solicitud.
     * @param string $url  URL de destino.
     *
     * @return string Respuesta de la solicitud.
     */
    public function LaunchUrl($data, $url)
    {
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
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * Agrega giros gratis a un usuario.
     *
     * @param string  $bonoElegido Bono seleccionado.
     * @param integer $roundsFree  Número de giros gratis.
     * @param string  $user        Usuario al que se asignan los giros.
     * @param array   $games       Juegos asociados a los giros.
     *
     * @return array Respuesta con el resultado de la operación.
     */
    public function AddFreespins($bonoElegido, $roundsFree, $user, $games, $StartDate, $EndDate)
    {

        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Proveedor = new Proveedor("", "LAMBDA");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $userName = $credentials->userName;
        $password = $credentials->password;

        $data = json_encode([
            'userName' => $userName,
            'password' => $password,
        ]);

        $URLBONUS = $credentials->UrlBonus;
        $path = '/users/Login';
        $responseBearer = $this->getBearer($URLBONUS . $path, $data);
        $Bearer = $responseBearer;

        $amountTypeId = 1;

        $startDate = new DateTime($StartDate);
        $endDate = new DateTime($EndDate);
        $interval = $startDate->diff($endDate);
        $numberOfDays = (int)$interval->format('%a');

        $dataFreeSpin = json_encode([
            'operatorCode' => $credentials->operatorCode,
            'gameTypeId' => intval($games[0]),
            'playerId' => $UsuarioMandante->usumandanteId,
            'amountTypeId' => $amountTypeId,
            'amount' => null,
            'quantity' => intval($roundsFree),
            'validDaysCount' => $numberOfDays,
            'validTill' => $endDate,
            'currencyCode' => $UsuarioMandante->moneda,
            'transactionReference' => $bonoElegido,
            'Description' => null,

        ]);
        
        $dataArray = json_decode($dataFreeSpin, true);

        $concatenatedString = $dataArray['operatorCode']
            . $dataArray['gameTypeId']
            . $dataArray['playerId']
            . $dataArray['amountTypeId']
            . $dataArray['amount']
            . $dataArray['quantity']
            . $dataArray['validDaysCount']
            . $dataArray['currencyCode']
            . $dataArray['transactionReference']
            . $dataArray['Description'];


        $privkey_pem = "-----BEGIN RSA PRIVATE KEY-----\n$credentials->private\n-----END RSA PRIVATE KEY-----";
        $privateKey = openssl_pkey_get_private($privkey_pem);

        openssl_sign($concatenatedString, $signature, $privateKey, OPENSSL_ALGO_SHA512);
        openssl_free_key($privateKey);

        $base64Signature = base64_encode($signature);

        $pathFreeSpin = '/DepositFreeSpin';
        $response = $this->createFreeSpin($URLBONUS . $pathFreeSpin, $dataArray, $Bearer, $base64Signature, $userName, $password);
        
        syslog(LOG_WARNING, "LAMBDA BONO DATA: " . $dataFreeSpin . " RESPONSE: " . $response);

        $response = json_decode($response);

        if ($response->ID != "") {
            $return = array(
                "code" => 0,
                "response_code" => $response,
                "response_message" => 'OK'
            );
        } else {
            $return = array(
                "code" => 1,
                "response_code" => 0,
                "response_message" => 'Error'
            );
        }
        return $return;
    }

    /**
     * Obtiene un token Bearer para autenticación.
     *
     * @param string $url  URL de autenticación.
     * @param string $data Datos de autenticación.
     *
     * @return string Token Bearer.
     */
    public function getBearer($url, $data)
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
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();

        return $response;
    }

    /**
     * Crea giros gratis en el sistema.
     *
     * @param string $url             URL del servicio de giros gratis.
     * @param array  $data            Datos de la solicitud.
     * @param string $Bearer          Token Bearer para autenticación.
     * @param string $base64Signature Firma en base64.
     * @param string $userName        Nombre de usuario.
     * @param string $password        Contraseña.
     *
     * @return string Respuesta del servicio.
     */
    public function createFreeSpin($url, $data, $Bearer, $base64Signature, $userName, $password)
    {
        $queryData = http_build_query($data);

        $urlWithParams = $url . '?' . $queryData;

        $curl = new CurlWrapper($url);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $urlWithParams,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'authorization: Bearer ' . $Bearer,
                'signature: ' . $base64Signature,
                'UserName: ' . $userName,
                'Password: ' . $password
            ),
        ));

        //Ejecutarlasolicitud
        $response = $curl->execute();

        return $response;
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
