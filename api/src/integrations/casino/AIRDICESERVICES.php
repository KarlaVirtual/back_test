<?php

/**
 * Clase AIRDICESERVICES
 *
 * Este archivo contiene la implementación de servicios relacionados con la integración de AIRDICE.
 * Proporciona métodos para obtener juegos, agregar giros gratis y realizar solicitudes específicas
 * a la API de AIRDICE.
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
 * Clase que proporciona servicios relacionados con la integración de AIRDICE.
 * Contiene métodos para gestionar juegos, giros gratis y solicitudes a la API de AIRDICE.
 */
class AIRDICESERVICES
{
    /**
     * URL de redirección para los juegos.
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
     * Obtiene la URL de un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $productoId    ID del producto.
     * @param boolean $isMobile      Indica si el juego es para móvil (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object|null Respuesta con la URL del juego o null en caso de error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            $launchURL = '';

            if ($play_for_fun) {
                $mode = "demo";
                $array = array(
                    "error" => false,
                    "response" => $launchURL . $gameid . $mode . strtolower($lang),
                );
            } else {
                // Lógica para modo real
                $Proveedor = new Proveedor("", "AIRDICE");
                $Producto = new Producto('', $gameid, $Proveedor->proveedorId);

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId, "", "", $productoId);
                    $UsuarioToken->setToken($UsuarioToken->createToken());
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setProductoId($Producto->productoId);
                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->update($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } catch (Exception $e) {
                    if ($e->getCode() == 21) {
                        // Manejo de excepción específica
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

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                $Mandante = new Mandante($UsuarioMandante->mandante);

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
                }

                $mode = "real";
                $url = $Credentials->URL . "/" . $gameid . "/" . $mode . "/" . $Credentials->CUSTOMER . "/?token=" . $UsuarioToken->getToken() . "&lang=" . strtolower($lang) . "&exitUrl=" . $this->URLREDIRECTION;

                $array = array(
                    "error" => false,
                    "response" => $url
                );

                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Agrega giros gratis a una campaña.
     *
     * @param string  $bonoId              ID del bono.
     * @param integer $roundsFree          Número de giros gratis.
     * @param float   $roundvalue          Valor de cada giro.
     * @param string  $StartDate           Fecha de inicio de la campaña.
     * @param string  $EndDate             Fecha de fin de la campaña.
     * @param string  $user                Usuario asociado.
     * @param array   $games               Juegos incluidos en la campaña.
     * @param string  $aditionalIdentifier Identificador adicional del freeSpin.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoId, $roundsFree, $roundvalue, $StartDate, $EndDate, $user, $games, $aditionalIdentifier)
    {
        $EndDate = date("Y-m-d H:i:s", strtotime($EndDate));

        // Convertir la fecha a marca de tiempo Unix (en segundos)
        $timestamp = strtotime($EndDate);

        // Multiplicar por 1000 para obtener los milisegundos
        $EndDateUnix = $timestamp * 1000;

        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $Proveedor = new Proveedor("", "AIRDICE");
        $Producto = new Producto("", $games[0], $Proveedor->proveedorId);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        // Data para creación de oferta
        $array = [
            "customer" => $Credentials->OPERATOR_ID,
            "code" => $bonoId . $UsuarioMandante->usumandanteId,
            "games" => $games,
            "username" => $UsuarioMandante->usumandanteId,
            "spins" => $roundsFree,
            "spinValue" => floatval($roundvalue),
            "currency" => $Usuario->moneda,
            "endTime" => $EndDateUnix
        ];

        $path = "/freespincampaignapi/CreateCampaign";
        $urlApi = $Credentials->URL;

        $hash = hash_hmac('sha256', json_encode($array), $Credentials->CUSTOMER_BONUS_KEY);

        $response = $this->airdiceFREESPINRequest($array, $path, $urlApi, $hash, $Credentials->CUSTOMER);

        syslog(LOG_WARNING, "AIRDICE DATA: " . json_encode($array) . " RESPONSE: " . $response);

        $response = json_decode($response);

        if ($response->success != true) {
            $return = array(
                "code" => 1,
                "response_code" => $response->success,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response->success,
                "response_message" => 'OK',
            );
        }
        return $return;
    }

    /**
     * Realiza una solicitud a la API de AIRDICE para giros gratis.
     *
     * @param array  $array    Datos de la solicitud.
     * @param string $path     Ruta de la API.
     * @param string $urlApi   URL base de la API.
     * @param string $hash     Hash de autenticación.
     * @param string $Customer Identificador del cliente.
     *
     * @return string Respuesta de la API.
     */
    public function airdiceFREESPINRequest($array, $path, $urlApi, $hash, $Customer)
    {
        $curl = new CurlWrapper($urlApi . $path);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $urlApi . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($array),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json; charset=UTF-8",
                "x-freespincampaignapi-auth: " . $Customer,
                "x-freespincampaignapi-signature: " . $hash
            ),
        ));
        // Ejecutar la solicitud
        $response = $curl->execute();
        return ($response);
    }
}
