<?php

/**
 * Este archivo contiene la clase `ZENITHSERVICES` que proporciona servicios relacionados con juegos de casino.
 * Incluye métodos para obtener información de juegos, realizar solicitudes HTTP y generar identificadores únicos.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\UsuarioToken;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\UsuarioMandante;
use Backend\dto\Mandante;
use Backend\dto\SubproveedorMandantePais;
use \CurlWrapper;

/**
 * Clase `ZENITHSERVICES` que proporciona servicios relacionados con juegos de casino.
 * Incluye métodos para obtener información de juegos, realizar solicitudes HTTP y generar identificadores únicos.
 */
class ZENITHSERVICES
{
    /**
     * URL de redirección para el casino.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * Constructor de la clase `ZENITHSERVICES`.
     */
    public function __construct()
    {
    }

    /**
     * Obtiene la URL del juego y actualiza el token del usuario.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID del mini juego.
     * @param string  $usumandanteId Opcional ID del usuario mandante.
     * @param boolean $isMobile      Opcional Indica si el acceso es desde móvil.
     * @param boolean $minigame      Opcional Indica si es un mini juego.
     *
     * @return object Respuesta con la URL del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $UsuarioToken, $migameid, $usumandanteId = "", $isMobile = false, $minigame = false)
    {
        $Proveedor = new Proveedor("", "ZENITH");
        $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($UsuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

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
        $Mandante = new Mandante($UsuarioMandante->mandante);

        if ($Mandante->baseUrl != '') {
            $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $Ip = explode(",", get_client_ip());

        $array = (object)[
            "username" => $UsuarioMandante->usumandanteId,
            "traceId" => $UsuarioToken->getToken(),
            "gameCode" => $gameid,
            "language" => $lang,
            "platform" => "web",
            "currency" => $UsuarioMandante->moneda,
            "lobbyUrl" => $this->URLREDIRECTION,
            "ipAddress" => $Ip[0],
        ];
        $data = json_encode($array);

        $traceId = generateUUIDv4();
        $requestBody = str_replace($UsuarioToken->getToken(), $traceId, $data);

        $response = $this->Request($requestBody, $credentials->URL, $credentials->API_KEY, $credentials->SECRET_KEY);

        $result = json_decode($response);

        $UsuarioToken->setToken($result->data->token);
        $UsuarioToken->setProductoId($UsuarioToken->productoId);
        $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
        $UsuarioTokenMySqlDAO->update($UsuarioToken);
        $UsuarioTokenMySqlDAO->getTransaction()->commit();

        $array = array(
            "error" => false,
            "response" => $result->data->gameUrl
        );

        return json_decode(json_encode($array));
    }

    /**
     * Realiza una solicitud HTTP POST con los datos proporcionados.
     *
     * @param string $data       Datos en formato JSON para enviar en la solicitud.
     * @param string $URL        URL del endpoint.
     * @param string $API_KEY    Clave de la API.
     * @param string $SECRET_KEY Clave secreta para la firma.
     *
     * @return string Respuesta del servidor.
     */
    public function Request($data, $URL, $API_KEY, $SECRET_KEY)
    {
        $SIGNATURE = hash_hmac('sha256', $data, $SECRET_KEY);

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
                'Content-Type: application/json',
                'X-Signature: ' . $SIGNATURE,
                'X-API-Key: ' . $API_KEY
            ),
        ));

        $response = $curl->execute();
        return $response;
    }
}

/**
 * Genera un identificador único (UUID) versión 4.
 *
 * @return string UUID generado.
 */
function generateUUIDv4()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
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
