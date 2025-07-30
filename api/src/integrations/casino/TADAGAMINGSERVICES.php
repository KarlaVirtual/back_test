<?php

/**
 * Clase TADAGAMINGSERVICES
 *
 * Esta clase proporciona servicios de integración con el proveedor TADAGAMING.
 * Incluye métodos para gestionar juegos, redirecciones, depósitos, giros gratis y más.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;
use DateTime;
use Exception;
use \CurlWrapper;
use DateTimeZone;
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
 * Clase TADAGAMINGSERVICES
 *
 * Proporciona métodos para la integración con el proveedor TADAGAMING,
 * incluyendo gestión de juegos, redirecciones, depósitos, giros gratis, entre otros.
 */
class TADAGAMINGSERVICES
{
    /**
     * URL para redirección.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * URL para depósitos.
     *
     * @var string
     */
    private $URLDEPOSIT = "";

    /**
     * Constructor de la clase.
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }

    /**
     * Obtiene un juego desde el proveedor TADAGAMING.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si es un juego de prueba.
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en miniatura.
     * @param boolean $isMobile      Indica si es un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante.
     * @param boolean $minigame      Indica si es un minijuego.
     *
     * @return object Respuesta del proveedor.
     */
    public function getGame($gameid, $lang, $play_for_fun, $UsuarioToken, $migameid, $isMobile = false, $usumandanteId = "", $minigame = false)
    {
        $Proveedor = new Proveedor("", "TADAGAMING");
        $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

        try {
            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => ''
                );

                return json_decode(json_encode($array));
            } else {
                try {
                    if ($usumandanteId == "") {
                        $UsuarioTokenSite = new UsuarioToken($UsuarioToken, '0');
                        $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                    }

                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
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
                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

                $credentials = json_decode($SubproveedorMandantePais->getCredentials());
                $token = $UsuarioToken->getToken();

                if ($lang == "es") {
                    $lang = $lang . "-AR";
                } elseif ($lang == "en") {
                    $lang = $lang . "-US";
                } elseif ($lang == "pt") {
                    $lang = $lang . "-BR";
                }

                $zonaHoraria = new DateTimeZone('America/Caracas'); // UTC-4
                $Date = (new DateTime('now', $zonaHoraria))->format('y') .
                    (new DateTime('now', $zonaHoraria))->format('m') .
                    (new DateTime('now', $zonaHoraria))->format('j');
                $KeyG = md5($Date . $credentials->AgentId . $credentials->AgentKey);

                $Codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                $letras = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $Codigo2 = substr(str_shuffle($letras), 0, 6);

                $params = 'Token=' . $token . '&GameId=' . $gameid . "&Lang=" . $lang . '&AgentId=' . $credentials->AgentId;
                $Md5String = md5($params . $KeyG);

                $Key = $Codigo . $Md5String . $Codigo2;

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
                    $this->URLDEPOSIT = $Mandante->baseUrl . "gestion/deposito";
                }

                $Data = $params . '&Key=' . $Key;
                $Path = 'singleWallet/LoginWithoutRedirect';

                syslog(LOG_WARNING, "TADAGAMING DATA:" . json_encode($Data));

                $response = $this->LaunchUrl($Data, $credentials->URL . $Path);

                syslog(LOG_WARNING, "TADAGAMING RESPONSE:" . $response);

                $response = json_decode($response);

                $error = false;
                if ($response->ErrorCode != 0) {
                    $error = true;
                }

                $array = array(
                    "error" => $error,
                    "response" => $response->Data
                );

                if ($_REQUEST['debug'] == '1') {
                    print_r("\r\n");
                    print_r('****DATA USER****');
                    print_r(json_encode($Data));
                    print_r("\r\n");
                    print_r('****LAUNCH URL****');
                    print_r(json_encode($response));
                }

                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Lanza una URL utilizando cURL.
     *
     * @param string $Data Datos a enviar.
     * @param string $Url  URL de destino.
     *
     * @return string Respuesta de la solicitud.
     */
    public function LaunchUrl($Data, $Url) {
        $curl = new CurlWrapper($Url);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $Url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $Data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = $curl->execute($curl);
        return $response;
    }

    /**
     * Agrega giros gratis a los usuarios.
     *
     * @param string  $bonoElegido Bono seleccionado.
     * @param integer $roundsFree  Número de giros gratis.
     * @param float   $roundsValue Valor de cada giro.
     * @param string  $StartDate   Fecha de inicio.
     * @param string  $EndDate     Fecha de finalización.
     * @param array   $users       Lista de usuarios.
     * @param array   $games       Lista de juegos.
     * @param string  $aditionalIdentifier   ID del bono del usuario.
     * @param string  $NombreBono  Nombre del bono.
     *
     * @return array Respuesta del proveedor.
     */
    public function AddFreespins($bonoElegido, $roundsFree, $roundsValue, $StartDate, $EndDate, $users, $games, $aditionalIdentifier, $NombreBono)
    {
        try {
            $UsuariosArray = [];
            foreach ($users as $user) {
                $Usuario = new Usuario($user);
                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                $UsuariosArray[] = 'user' . $UsuarioMandante->usumandanteId;
            }
            $Users = implode(',', $UsuariosArray);

            $BonoId = '';
            foreach ($users as $user) {
                $Usuario = new Usuario($user);
                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                $BonoId .= $aditionalIdentifier . '_' . $UsuarioMandante->usumandanteId . ',';
            }
            $BonoId = rtrim($BonoId, ',');

            $Games = '';
            foreach ($games as $game) {
                $Games .= $game . ',';
            }
            $Games = rtrim($Games, ',');

            $Proveedor = new Proveedor("", "TADAGAMING");
            $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

            $StarDateObj = new DateTime($StartDate);
            $starDateProv = $StarDateObj->format("Y-m-d\TH:i:s");

            $EndDateObj = new DateTime($EndDate);
            $endDateProv = $EndDateObj->format("Y-m-d\TH:i:s");

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $zonaHoraria = new DateTimeZone('America/Caracas'); // UTC-4
            $Date = (new DateTime('now', $zonaHoraria))->format('y') .
                (new DateTime('now', $zonaHoraria))->format('m') .
                (new DateTime('now', $zonaHoraria))->format('j');
            $KeyG = md5($Date . $credentials->AgentId . $credentials->AgentKey);
            $Codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $letras = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $Codigo2 = substr(str_shuffle($letras), 0, 6);

            $params = 'Accounts=' . $Users . '&Currency=' . $Usuario->moneda . "&ReferenceIds=" . $BonoId . "&FreeSpinValidity=" . $endDateProv . '&NumberOfRounds=' . $roundsFree . '&GameIds=' . $games[0] . '&AgentId=' . $credentials->AgentId;

            $Md5String = md5($params . $KeyG);
            $Key = $Codigo . $Md5String . $Codigo2;

            $Data = array(
                "Accounts" => $Users,
                "Currency" => $Usuario->moneda,
                "ReferenceIds" => $BonoId,
                "FreeSpinValidity" => $endDateProv,
                "NumberOfRounds" => intval($roundsFree),
                "GameIds" => $games[0],
                "BetValue" => doubleval($roundsValue),
                "AgentId" => $credentials->AgentId,
                "Key" => $Key
            );

            $path = 'CreateFreeSpinForMany';
            
            $response = $this->SendFreespins($Data, $credentials->URL . $path);
            syslog(LOG_WARNING, "TADAGAMING BONO DATA:" . json_encode($Data) . ' | RESPONSE: ' . $response);
            $response = json_decode($response);

            if ($response->ErrorCode != '0') {
                $return = array(
                    "code" => 1,
                    "response_code" => $response->CreateTime,
                    "response_message" => 'Error'
                );
            } else {
                $return = array(
                    "code" => 0,
                    "response_code" => $response->CreateTime,
                    "response_message" => 'OK'
                );
            }
        } catch (Exception $e) {
        }

        return $return;
    }

    /**
     * Envía giros gratis al proveedor.
     *
     * @param array  $Data Datos a enviar.
     * @param string $Url  URL de destino.
     *
     * @return string Respuesta de la solicitud.
     */
    public function SendFreespins($Data, $Url)
    {
        $curl = new CurlWrapper($Url);
        $curl->setOptionsArray([
            CURLOPT_URL => $Url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($Data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ]);

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