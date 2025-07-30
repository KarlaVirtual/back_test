<?php

/**
 * Clase 'G21VIRALSERVICES'
 *
 * Esta clase provee funciones para la API 'G21VIRALSERVICES'.
 *
 * @category API
 * @package  Ninguno
 * @author   Sebastián Rico Pérez <sebastian.rico@virtualsoft.tech>
 * @version  1.0
 * @since    03.04.29
 */

namespace Backend\integrations\casino;

use Backend\dto\Pais;
use Backend\dto\Usuario;
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
 * Clase principal para la integración con la API de G21VIRALSERVICES.
 *
 * Proporciona métodos para interactuar con los servicios de G21VIRALSERVICES,
 * incluyendo la obtención de juegos y la gestión de tokens de usuario.
 */
class G21VIRALSERVICES
{
    /**
     * URL de redirección para la API.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    public function __construct()
    {
    }

    /**
     * Obtiene un juego basado en su ID y otros parámetros de usuario.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si se juega en modo gratuito.
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID interno del juego.
     * @param string  $usumandanteId ID del mandante del usuario (opcional).
     * @param boolean $isMobile      Indica si el usuario accede desde móvil.
     * @param boolean $minigame      Indica si el juego es un minijuego.
     *
     * @return object Respuesta con la URL del juego.
     * @throws Exception En caso de error en la transacción.
     */
    public function getGame($gameid, $lang, $play_for_fun, $UsuarioToken, $migameid, $usumandanteId = "", $isMobile = false, $minigame = false)
    {
        $Proveedor = new Proveedor("", "21VIRAL");
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

        $isMobile = false;
        if ($isMobile == true) {
            $isMobile = "Mobile";
        } else {
            $isMobile = "Desktop";
        }

        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
        $Mandante = new Mandante($UsuarioMandante->mandante);
        $Pais = new Pais($UsuarioMandante->paisId);

        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
        $Balance = round($Usuario->getBalance(), 2);

        if ($Mandante->baseUrl != '') {
            $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $timestapp = time();

        $data = [
            "playerId" => (string)$UsuarioMandante->getUsumandanteId(),
            "currency" => (string)$UsuarioMandante->getMoneda(),
            "gameId" => $Producto->externoId,
            "gameMode" => "Real",
            "timestamp" => $timestapp,
            "localeCode" => (strtolower($Pais->idioma)) . "-" . $Pais->iso,
            "playerDeviceType" => (string)$isMobile,
            "balance" => strval($Balance),
            "playerUserName" => (string)$UsuarioMandante->getNombres(),
            "countryCode" => (string)$Pais->iso,
            "lobbyUrl" => $this->URLREDIRECTION,
            "depositUrl" => $this->URLREDIRECTION
        ];

        // Canonicalizar el JSON según RFC 8785
        $canonicalJson = $this->canonicalizeJson($data);

        // Firmar con HMAC-SHA256
        $signature = hash_hmac('sha256', $canonicalJson, $credentials->OPERATOR_SECRET);
        $data = json_encode($data);

        $response = $this->Request($data, $credentials, $signature);
        $result = json_decode($response);
        // Extraer la URL desde la respuesta
        $gameStartUrl = $result->gameStartUrl;
        // Obtener los parámetros de la URL
        $urlParts = parse_url($gameStartUrl);
        parse_str($urlParts['query'], $queryParams);
        // Extraer el authToken
        $authToken = $queryParams['authToken'] ?? null;

        $UsuarioToken->setToken($authToken);
        $UsuarioToken->setProductoId($UsuarioToken->productoId);
        $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
        $UsuarioTokenMySqlDAO->update($UsuarioToken);
        $UsuarioTokenMySqlDAO->getTransaction()->commit();

        $array = array(
            "error" => false,
            "response" => $result->gameStartUrl
        );

        return json_decode(json_encode($array));
    }

    /**
     * Realiza una solicitud HTTP POST utilizando cURL.
     *
     * @param string $data        Datos en formato JSON que se enviarán en el cuerpo de la solicitud.
     * @param object $credentials Objeto que contiene las credenciales necesarias para la solicitud, incluyendo la URL.
     * @param string $signature   Firma HMAC-SHA256 generada para autenticar la solicitud.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    public function Request($data, $credentials, $signature)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $credentials->URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: HMAC-SHA256 ' . $credentials->AUTH_USERNAME . ":" . $signature,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    /**
     * Canonicaliza un array JSON según el estándar RFC 8785.
     *
     * Este método ordena recursivamente las claves del array y lo codifica
     * como un JSON sin espacios ni caracteres escapados innecesarios.
     *
     * @param array $data Array de datos a ser canonicalizado.
     *
     * @return string JSON canonicalizado.
     */
    public function canonicalizeJson(array $data): string
    {
        // Ordena recursivamente las claves del array
        ksort($data);
        foreach ($data as &$value) {
            if (is_array($value)) {
                $value = $this->canonicalizeJson($value); // aplicar recursivo
            }
        }
        // Codifica como JSON sin espacios y sin escapado innecesario
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

