<?php

/**
 * Clase SMARTSOFTSERVICES
 *
 * Este archivo contiene la implementación de la clase SMARTSOFTSERVICES, que integra funcionalidades
 * relacionadas con la gestión de juegos y bonificaciones en un entorno de casino en línea.
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
use Backend\dto\Pais;
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase que implementa la integración con los servicios de SMARTSOFT.
 * Proporciona métodos para gestionar juegos, bonificaciones y otras funcionalidades
 * relacionadas con un entorno de casino en línea.
 */
class SMARTSOFTSERVICES
{
    /**
     * URL de redirección para los juegos.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

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
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param integer $productoId    ID del producto.
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            $Proveedor = new Proveedor("", "SMARTSOFT");
            $Producto = new Producto($productoId, "", $Proveedor->getProveedorId());

            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            try {
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

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
            }

            $ProductoDetalle = new ProductoDetalle("", "$productoId", "GAMEID");

            $GameCagory = $ProductoDetalle->getPValue();

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $URL = $credentials->URL;
            $PORTAL_NAME = $credentials->PORTAL_NAME;

            $urlFinal = $URL . "?GameCategory=" . $GameCagory . "&GameName=" . $Producto->getExternoId() . "&Token=" . $UsuarioToken->getToken() . "&PortalName=" . $PORTAL_NAME . "&lang=" . $lang . "&ReturnUrl=" . $this->URLREDIRECTION;

            if ($_ENV['debug']) {
                print_r('URL: ');
                print_r($urlFinal);
            }

            $array = array(
                "error" => false,
                "response" => $urlFinal
            );
            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Agrega giros gratis (freespins) a un usuario.
     *
     * @param integer $bonoId              ID del bono.
     * @param integer $roundsFree          Número de giros gratis.
     * @param float   $roundvalue          Valor de cada giro.
     * @param string  $StartDate           Fecha de inicio de la promoción.
     * @param string  $EndDate             Fecha de fin de la promoción.
     * @param integer $user                ID del usuario.
     * @param array   $games               Lista de juegos aplicables.
     * @param string  $aditionalIdentifier Identificador adicional del bono.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoId, $roundsFree, $roundvalue, $StartDate, $EndDate, $user, $games, $aditionalIdentifier)
    {
        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Proveedor = new Proveedor("", "SMARTSOFT");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URL_BONUS = $credentials->URL_BONUS;
        $SECRET_KEY = $credentials->SECRET_KEY;
        $PORTAL_NAME = $credentials->PORTAL_NAME;

        $Registro = new Registro("", $Usuario->usuarioId);

        $timestamp = strtotime($EndDate);
        $EndDate = gmdate('Y-m-d\TH:i:s', $timestamp);

        $timestamp2 = strtotime($StartDate);
        $StartDate = gmdate('Y-m-d\TH:i:s', $timestamp2);

        $array = array(
            "Client" => array(
                "ClientExternalKey" => $UsuarioMandante->usumandanteId,
                "UserName" => $Registro->nombre,
                "CurrencyCode" => $UsuarioMandante->moneda
            ),
            'Gifts' => array(),
            'PortalName' => $PORTAL_NAME
        );

        foreach ($games as $gameP) {
            $game = array(
                'GameName' => $gameP,
                'BetLevel' => $roundvalue,
                'Quantity' => $roundsFree,
                'ActivationDate' => $StartDate,
                'ExpirationDate' => $EndDate,
                'GiftKey' => $bonoId . $aditionalIdentifier . $Usuario->usuarioId
            );
            if ($game['GameName'] != 'JetX') {
                $game['Lines'] = 1;
            }
            array_push($array["Gifts"], $game);
        }

        $string = $SECRET_KEY . "|POST|" . json_encode($array);
        $AutchSign = md5($string);

        $response = $this->SendFreespins(json_encode($array), $URL_BONUS, $AutchSign);
        
        syslog(LOG_WARNING, "SMARTSOFT BONO DATA: " . json_encode($array) . " RESPONSE: " . json_encode($response));

        if ($response->ErrorCode != 0) {
            $return = array(
                "code" => 1,
                "response_code" => $response->ErrorCode,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response->ErrorCode,
                "response_message" => 'OK',
                "bonusId" => $bonoId
            );
        }
        return $return;
    }

    /**
     * Envía la solicitud de giros gratis a un servicio externo.
     *
     * @param string $data      Datos de la solicitud en formato JSON.
     * @param string $url       URL del servicio externo.
     * @param string $AutchSign Firma de autenticación.
     *
     * @return mixed Respuesta del servicio externo.
     */
    public function SendFreespins($data, $url, $AutchSign)
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
                'X-Signature: ' . $AutchSign,
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
