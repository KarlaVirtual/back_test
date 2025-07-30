<?php

/**
 * Clase para la integración con los servicios de Pascal en un entorno de casino.
 *
 * Proporciona métodos para gestionar juegos, agregar giros gratis y realizar solicitudes
 * a través de una API externa. Incluye manejo de usuarios, mandantes, productos y credenciales.
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
use Backend\dto\Pais;
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
 * Clase PASCALSERVICES
 *
 * Contiene métodos para interactuar con los servicios de Pascal, incluyendo la obtención
 * de juegos, la asignación de giros gratis y la realización de solicitudes HTTP.
 */
class PASCALSERVICES
{
    /**
     * URL de redirección para los servicios de Pascal.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Constructor de la clase PASCALSERVICES.
     *
     * Inicializa la configuración del entorno y define el comportamiento según
     * si el entorno es de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la URL de redirección para un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID alternativo del juego (opcional).
     * @param string  $ismobile      Indica si es un dispositivo móvil (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Objeto con la URL de redirección o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid = "", $ismobile = '', $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "PASCAL");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

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
                $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $array = [
                "error" => false,
                "response" => $credentials->LAUNCH_URL . "?gameId=" . $gameid . "&culture=" . $lang . "&partnerKey=" . $credentials->PARTNER_KEY . "&Token=" . $UsuarioToken->getToken() . "&IsBetShop=false&Mode=real"
            ];

            if ($_ENV['debug']) {
                print_r(PHP_EOL);
                print_r('****URL LAUNCH****');
                print_r(json_encode($array));
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Agrega giros gratis a un usuario o grupo de usuarios.
     *
     * @param integer $bonoId              ID del bono.
     * @param string  $Name                Nombre del bono.
     * @param integer $roundsFree          Número de giros gratis.
     * @param float   $roundvalue          Valor de cada giro.
     * @param string  $StartDate           Fecha de inicio del bono.
     * @param string  $EndDate             Fecha de finalización del bono.
     * @param array   $ids                 IDs de los usuarios.
     * @param array   $games               IDs de los juegos.
     * @param integer $aditionalIdentifier Identificador adicional del freeSpin.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoId, $Name, $roundsFree, $roundvalue, $StartDate, $EndDate, $ids, $games, $aditionalIdentifier)
    {
        $Usuario = new Usuario($ids[0]);  //OK
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante); //OK
        $Mandante = new Mandante($UsuarioMandante->mandante); //partnerId OK
        $Pais = new Pais($UsuarioMandante->paisId); //OK
        $Currency = $UsuarioMandante->moneda;

        $StartDate = date("Y-m-d H:i:s", strtotime($StartDate));
        $EndDate = date("Y-m-d H:i:s", strtotime($EndDate));

        $StartDateFormat = date("Y-m-d\TH:i:s\Z", strtotime($StartDate));
        $EndDateFormat = date("Y-m-d\TH:i:s\Z", strtotime($EndDate));

        $winType = 0;

        $Proveedor = new Proveedor("", "PASCAL");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $sing = hash("sha512", $credentials->PARTNER_KEY . $credentials->SECRET_KEY, false);

        $arrayIds = array();
        foreach ($ids as $key => $value) {
            $Usuario = new Usuario($value);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            array_push($arrayIds, $UsuarioMandante->usumandanteId);
        }

        $game = '';
        foreach ($games as $valor) {
            $game .= $valor;
        }

        $array = [
            "startDate" => $StartDateFormat,
            "expireDate" => $EndDateFormat,
            "webDeleteDate" => $EndDateFormat,
            "gameId" => $games[0],
            "winType" => $winType,
            "currencyCode" => $Currency,
            "currencyConfigs" => [
                [
                    "amount" => $roundvalue,
                    "count" => $roundsFree,
                    "currencyCode" => $Currency,
                ]
            ],
            "playerIds" => $arrayIds,
            "partnerKey" => $credentials->PARTNER_KEY,
            "partnerKeyHash" => $sing,
        ];

        $response = $this->Request($array, $credentials->API_URL);
        $response = json_decode($response);
        
        syslog(LOG_WARNING, " PASCALSERVICE DATA: " . json_encode($array) . " RESPONSE: " . json_encode($response));

        if ($response->errorCode != 0) {
            $return = array(
                "code" => 1,
                "response_code" => $response->errorCode,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response->errorCode,
                "response_message" => 'OK',
                "bonusId" => $bonoId
            );
        }
        return $return;
    }

    /**
     * Realiza una solicitud HTTP POST a una URL específica.
     *
     * @param array  $array Datos a enviar en la solicitud.
     * @param string $URL   URL del endpoint.
     *
     * @return string Respuesta de la solicitud.
     */
    public function Request($array, $URL)
    {
        $curl = new CurlWrapper($URL);

        //Configurar opciones
        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($array),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        //Ejecutarlasolicitud
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
