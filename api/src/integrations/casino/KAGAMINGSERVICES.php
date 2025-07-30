<?php

/**
 * Clase `KAGAMINGSERVICES` para la integración con el proveedor de juegos KAGAMING.
 *
 * Este archivo contiene métodos para gestionar juegos, agregar giros gratis y obtener
 * información del cliente, entre otras funcionalidades relacionadas con la integración
 * de servicios de casino.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use \CurlWrapper;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase `KAGAMINGSERVICES`.
 *
 * Esta clase proporciona métodos para la integración con el proveedor de juegos KAGAMING,
 * incluyendo la gestión de juegos, asignación de giros gratis y obtención de información
 * del cliente, entre otras funcionalidades relacionadas con servicios de casino.
 */
class KAGAMINGSERVICES
{
    /**
     * URL de redirección para los juegos.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * Constructor de la clase.
     *
     * Inicializa el entorno de configuración y realiza acciones según el entorno
     * (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la URL del juego basado en los parámetros proporcionados.
     *
     * @param string   $gameId        ID del juego.
     * @param string   $lang          Idioma del juego.
     * @param boolean  $play_for_fun  Indica si el juego es en modo "jugar por diversión".
     * @param string   $usuarioToken  Token del usuario.
     * @param Producto $Producto      Objeto del producto.
     * @param boolean  $isMobile      Indica si el juego es para dispositivos móviles.
     * @param string   $usumandanteId ID del usuario mandante.
     *
     * @return object Respuesta con la URL del juego o un error.
     */
    public function getGame($gameId, $lang, $play_for_fun, $usuarioToken, $Producto, $isMobile, $usumandanteId)
    {
        try {
            $Proveedor = new Proveedor("", "KAGAMING");
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $patch = "new-casino/proveedor/KAGAMING";

            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => '' . "&token=FUNFUNFUN&selectGame=" . $gameId . "&clientType=html5&language=" . strtolower($lang) . "&operatorId=" . '',
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

                $hasBonus = false;
                try {
                    $UsuarioBono = new UsuarioBono("", $UsuarioMandante->usuarioMandante, "", $Producto->getSubproveedorId(), "R");
                    $hasBonus = true;
                } catch (Exception $e) {
                    $hasBonus = false;
                }

                if ($hasBonus == true) {
                    $externo_bono = $UsuarioBono->externoBono;
                }

                $user = $UsuarioMandante->usumandanteId;
                $token = $UsuarioToken->getToken();
                $currency = $UsuarioMandante->moneda;

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . $patch;
                }

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $URlFINAL = '';
                $URlFINAL = $URlFINAL . '/?g=' . $gameId;
                $URlFINAL = $URlFINAL . '&p=' . $Credentials->PARTNER_NAME;
                $URlFINAL = $URlFINAL . '&u=' . $user;
                $URlFINAL = $URlFINAL . '&t=' . $token;
                $URlFINAL = $URlFINAL . '&ak=' . $Credentials->ACCESS_KEY;
                $URlFINAL = $URlFINAL . '&loc=' . $lang;
                $URlFINAL = $URlFINAL . '&cr=' . $currency;
                $URlFINAL = $URlFINAL . '&l=' . $this->URLREDIRECTION;
                $URlFINAL = $URlFINAL . '&psp=' . $externo_bono;

                $array = array(
                    "error" => false,
                    "response" => $Credentials->URL . $URlFINAL
                );
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Agrega giros gratis a un usuario.
     *
     * @param integer $roundsFree  Número de giros gratis.
     * @param float   $roundsValue Valor de cada giro.
     * @param string  $StartDate   Fecha de inicio de la promoción.
     * @param string  $EndDate     Fecha de finalización de la promoción.
     * @param string  $user        ID del usuario.
     * @param array   $games       Lista de juegos aplicables.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($roundsFree, $roundsValue, $StartDate, $EndDate, $user, $games)
    {
        $StartDate = date('Y-m-d H:i:s');
        $Usuario = new Usuario($user);

        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $currency = $UsuarioMandante->moneda;
        $fecha_inicial = $StartDate;
        $timestamp = strtotime($fecha_inicial);
        $nueva_fecha = date('Y-m-d H:i:s', $timestamp + (5 * 3600));

        $Proveedor = new Proveedor("", "KAGAMING");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $Params = [
            'partnerName' => $Credentials->PARTNER_NAME,
            'currency' => $currency,
            'playerId' => $UsuarioMandante->usumandanteId,
            'numberSpins' => $roundsFree,
            'betLevel' => intval($roundsValue),
            'endDate' => $EndDate,
            'startDate' => $nueva_fecha,
            'games' => $games
        ];

        $hash = hash_hmac('sha256', json_encode($Params), $Credentials->SECRET_KEY);

        $path = '/promotionspin/create?hash=' . $hash;

        $response = $this->SendFreespins(json_encode($Params), $Credentials->URL_BONUS . $path);

        syslog(LOG_WARNING, "KAGAMING BONO DATA: " . json_encode($Params) . " RESPONSE: " . $response);

        $response = json_decode($response);

        if ($response->status != 'success') {
            $return = array(
                "code" => 1,
                "response_code" => 0,
                "message" => $response->status,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response->promotionSpinId,
                "message" => 'Success',
                "response_message" => 'OK'
            );
        }
        return $return;
    }

    /**
     * Envía los datos de giros gratis al servicio externo.
     *
     * @param string $data Datos en formato JSON.
     * @param string $url  URL del servicio externo.
     *
     * @return string Respuesta del servicio externo.
     */
    public function SendFreespins($data, $url)
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
     * Obtiene la dirección IP del cliente.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
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
