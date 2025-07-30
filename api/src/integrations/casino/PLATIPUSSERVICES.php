<?php

/**
 * Clase para integrar servicios del proveedor Platipus en el sistema.
 *
 * Este archivo contiene métodos para gestionar juegos, agregar giros gratis y realizar solicitudes HTTP
 * relacionadas con los servicios de Platipus. Se utiliza en el contexto de un sistema de casino en línea.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
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
 * Clase principal para manejar la integración con los servicios de Platipus.
 *
 * Esta clase contiene métodos para interactuar con la API de Platipus, incluyendo
 * la obtención de URLs de juegos, la asignación de giros gratis y la realización
 * de solicitudes HTTP.
 */
class PLATIPUSSERVICES
{
    /**
     * URL de redirección para los servicios de Platipus.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Constructor de la clase.
     *
     * Inicializa el entorno de configuración para determinar si se está en un entorno de desarrollo o producción.
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
     * @param integer $ProductoId    ID del producto.
     * @param boolean $isMobile      Indica si el juego es para dispositivos móviles.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object                Respuesta con la URL del juego o un error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $ProductoId, $isMobile = false, $usumandanteId = "")
    {
        if ($play_for_fun) {
            $array = array(
                "error" => false,
                "response" => 'https://wbg.platipusgaming.com'
            );
            return json_decode(json_encode($array));
        } else {
            try {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "PLATIPUS");
                $Producto = new Producto($ProductoId);

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
                        $token = $UsuarioToken->createToken();
                        $UsuarioToken->setToken($token);
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

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino"; //Revisar
                }

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $array = array(
                    "error" => false,
                    "response" => $credentials->URL . '/BIGBOSS/connect.do?key=' . $credentials->API_KEY . '&userid=' . $UsuarioToken->getUsuarioId() . '&gameconfig=' . $gameid . '&lang=' . $lang . '&lobby=' . $this->URLREDIRECTION . '&token=' . $UsuarioToken->getToken(),
                );

                if ($_ENV['debug']) {
                    print_r(' key ');
                    print_r($credentials->API_KEY);
                    print_r(' url');
                    print_r(json_encode($array));
                }

                return json_decode(json_encode($array));
            } catch (Exception $e) {
                print_r($e);
            }
        }
    }

    /**
     * Agrega giros gratis a un usuario.
     *
     * @param integer $bonoId              ID del bono.
     * @param integer $roundsFree          Número de giros gratis.
     * @param float   $roundvalue          Valor de cada giro.
     * @param string  $StartDate           Fecha de inicio del bono.
     * @param string  $EndDate             Fecha de expiración del bono.
     * @param string  $user                Usuario al que se asignan los giros.
     * @param array   $games               Juegos aplicables.
     * @param integer $aditionalIdentifier Identificador adicional del freeSpin.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoId, $roundsFree, $roundvalue, $StartDate, $EndDate, $user, $games, $aditionalIdentifier)
    {
        $Usuario = new Usuario($user);

        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Mandante = new Mandante($UsuarioMandante->mandante);

        $EndDateFormat = date("Y-m-d\TH:i:s\Z", strtotime($EndDate));

        $Proveedor = new Proveedor("", "PLATIPUS");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $game = '';
        foreach ($games as $valor) {
            $game .= $valor;
        }

        $data = $credentials->URL . '/BIGBOSS/FREESPIN.DO?key=' . $credentials->API_KEY . '&userid=' . $UsuarioMandante->usumandanteId . '&games=' . $game . '&freespin_id=' . $bonoId . $aditionalIdentifier . $Usuario->usuarioId . '&freespin_bet=' . $roundvalue . '&freespin_amount=' . $roundsFree . '&expire=' . $EndDateFormat;
        if ($_ENV['debug']) {
            print_r($data);
        }

        $response = $this->Request($data);

        syslog(LOG_WARNING, " PLATIPUSSERVICE DATA: " . $data . " RESPONSE: " . $response);
        $response = json_decode($response);

        if ($response->successCode != 'OK') {
            $return = array(
                "code" => 1,
                "response_code" => $response->successCode,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response->successCode,
                "response_message" => 'OK',
                "bonusId" => $bonoId
            );
        }
        return $return;
    }

    /**
     * Realiza una solicitud HTTP utilizando CurlWrapper.
     *
     * @param string $data URL y parámetros de la solicitud.
     *
     * @return string       Respuesta de la solicitud.
     */
    public function Request($data)
    {
        $curl = new CurlWrapper($data);

        $curl->setOptionsArray(array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/plain',
                'Content-Length: 0'
            )
        ));

        $response = $curl->execute();

        return $response;
    }
}
