<?php

/**
 * Clase que proporciona servicios para la integración con EvenBet.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-21
 */

namespace Backend\integrations\poker;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use RecursiveArrayIterator;
use Backend\dto\UsuarioToken;
use RecursiveIteratorIterator;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase que proporciona servicios para la integración con EvenBet.
 */
class EVENBETSERVICES
{
    /**
     * Firma generada para la autenticación en la API.
     *
     * @var string
     */
    private $signature = '';

    /**
     * URL de redirección utilizada en las solicitudes.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Constructor de la clase EVENBETSERVICES.
     *
     * Inicializa las propiedades de la clase dependiendo del entorno
     * (desarrollo o producción) utilizando la configuración del entorno.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene un juego desde la API de EvenBet.
     *
     * @param string $gameid        ID del juego.
     * @param string $lang          Idioma del juego.
     * @param bool   $play_for_fun  Indica si el juego es para diversión.
     * @param string $usuarioToken  Token del usuario.
     * @param string $migameid      ID del juego (opcional).
     * @param string $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta de la API con la URL de redirección.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid = "", $usumandanteId = "")
    {
        try {

            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "EVENBET");
            $Producto = new Producto('', $gameid, $Proveedor->proveedorId);

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
                    $UsuarioToken->setProductoId($Producto->productoId);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
            $Mandante = new Mandante($UsuarioMandante->getMandante());

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $data = array(
                "clientId" => $Credentials->CLIENT_ID,
                "userId" => $usumandanteId,
                "nick" => 'U' . $usumandanteId,
                "lang" => $lang,
                "currency" => $UsuarioMandante->moneda
            );

            $this->signature = $this->GenerateSignature($data, $Credentials->SECRET_KEY);

            $response = $this->Request($data, $Credentials->URL);

            $array = array(
                "error" => false,
                "response" => $response->data->attributes->{'redirect-url'}
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }


    public function GenerateSignature($data, $SecretKey)
    {
        $sortFlags = SORT_REGULAR;
        // Sort array by parameter name
        ksort($data, $sortFlags);

        // Sort nested arrays, if any
        foreach ($data as &$value) {
            if (is_array($value)) {
                sort($value, $sortFlags);
            }
        }

        if (array_key_exists("clientId", $data)) {
            unset($data['clientId']);
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($data));
        $paramString = implode('', iterator_to_array($iterator));

        $paramString = $paramString . $SecretKey;

        $sign = hash('sha256', $paramString);

        return ($sign);
    }


    /**
     * Realiza una solicitud a la API de EvenBet.
     *
     * Este método envía una solicitud POST a la API de EvenBet utilizando cURL.
     * Los datos de la solicitud se generan a partir de los parámetros proporcionados
     * y se incluyen encabezados personalizados, como la firma generada.
     *
     * @param array $array_tmp Datos que se incluirán en la solicitud.
     *
     * @return object Respuesta de la API decodificada como un objeto JSON.
     */
    public function Request($array_tmp, $Url)
    {
        $data = array();
        $data = array_merge($data, $array_tmp);

        $datos = "nick=" . $data["nick"] . "&lang=" . $data["lang"] . "&currency=" . $data["currency"];

        $curl = new CurlWrapper($Url . "v2/app/users/" . $data["userId"] . "/session?clientId=" . $data["clientId"]);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $Url . "v2/app/users/" . $data["userId"] . "/session?clientId=" . $data["clientId"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $datos,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/vnd.api+json',
                'sign:' . $this->signature
            ],
        ));

        $response = $curl->execute();
        return json_decode($response);
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
