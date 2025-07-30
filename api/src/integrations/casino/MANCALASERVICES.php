<?php

/**
 * Clase MANCALASERVICES para la integración con el proveedor de juegos MANCALA.
 *
 * Este archivo contiene métodos para gestionar juegos, agregar giros gratis,
 * realizar solicitudes HTTP y generar claves de autenticación.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use DateTime;
use Exception;
use Throwable;
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
 * Clase principal para manejar la integración con los servicios de MANCALA.
 */
class MANCALASERVICES
{
    /**
     * Constructor de la clase.
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }

    /**
     * Obtiene un juego desde el proveedor MANCALA.
     *
     * @param string  $GameCode      Código del juego.
     * @param string  $lang          Idioma del juego.
     * @param bool    $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario (opcional).
     * @param integer $ProductoId    ID del producto.
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     */
    public function getGame($GameCode, $lang, $play_for_fun, $usuarioToken = "", $ProductoId, $isMobile, $usumandanteId = "")
    {
        if ($play_for_fun) {
            $array = array(
                "error" => false,
                "response" => $this->URL
            );
            return json_decode(json_encode($array));
        } else {
            try {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '6'); //De acuerdo con la base de Datos el proveedorId para ezugi es 6
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "MANCALA");

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId, "", "", $ProductoId);

                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);

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
                        $UsuarioToken->setProductoId($ProductoId);
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
                    $this->URLREDIRECTION = $Mandante->baseUrl . "casino"; //Revisar esta linea.
                }

                $Producto = new Producto($ProductoId);
                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $Lang = strtoupper($lang);

                if ($play_for_fun == true) {
                    $array = array(
                        "ClientGuid" => $credentials->CLIENT_GUID,
                        "GameId" => $GameCode,
                        "UserId" => $usumandanteId,
                        "Currency" => $UsuarioMandante->moneda,
                        "Lang" => $Lang,
                        "ApiVersion" => "v2",
                        "Hash" => $this->generateKey($play_for_fun, $UsuarioMandante->moneda, $GameCode, $usumandanteId, $credentials->CLIENT_GUID, $credentials->SECRET_KEY, "GetToken/"),
                        "DemoMode" => $play_for_fun,
                        "ExtraData" => " "
                    );
                } elseif ($play_for_fun == false) {
                    $array = array(
                        "ClientGuid" => $credentials->CLIENT_GUID,
                        "GameId" => $GameCode,
                        "DemoMode" => $play_for_fun,
                        "UserId" => $usumandanteId,
                        "Currency" => $UsuarioMandante->moneda,
                        "Lang" => $Lang,
                        "ApiVersion" => "v2",
                        "ExtraData" => " ",
                        "Hash" => $this->generateKey($play_for_fun, $UsuarioMandante->moneda, $GameCode, $usumandanteId, $credentials->CLIENT_GUID, $credentials->SECRET_KEY, "GetToken/")
                    );
                }

                $response = $this->Request(json_encode($array), $credentials->URL);

                $UsuarioToken->setToken($response->Token);

                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();

                switch ($lang) {
                    case 'en':
                        $lang = 'en-EN';
                        break;
                    default:
                        $lang = 'es-ES';

                        break;
                }

                $array = array(
                    "error" => false,
                    "response" => $response->IframeUrl . "&language=" . $lang . "&backurl=" . $this->URLREDIRECTION
                );

                return json_decode(json_encode($array));
            } catch (Exception $e) {
            }
        }
    }

    /**
     * Asigna los bonos freeSpin a los usuarios.
     * 
     * @param string     $bonoId              Id del bono.
     * @param int|string $roundsFree          Cantidad de rondas gratis.
     * @param int|string $roundsValue         Valor de las rondas gratis.
     * @param string     $StartDate           Fecha de inicio del bono.
     * @param string     $EndDate             Fecha de finalización del bono.
     * @param array      $users               Lista de usuarios a los que se asigna el bono.
     * @param array      $games               Lista de juegos asociados al bono, solo se toma el primero.
     * @param string     $aditionalIdentifier Id que relaciona al usuario con el bono.
     * @param string     $NombreBono          Nombre del bono.
     * 
     * @throws Throwable Si ocurre un error durante el proceso.
     * @return array Retorna un array con el código de respuesta y mensaje.
     */
    public function AddFreespins($bonoId, $roundsFree, $roundsValue, $StartDate, $EndDate, $users, $games, $aditionalIdentifier, $NombreBono){
        try {
            $Usuario = new Usuario($users[0]);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

            $Proveedor = new Proveedor("", "MANCALA");
            $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $hash = md5("AddRule/" . $credentials->CLIENT_GUID . $UsuarioMandante->moneda . $credentials->SECRET_KEY);
            $hashBet = md5("GetAvailableBets/" . $credentials->CLIENT_GUID . $games[0] . $UsuarioMandante->moneda . $credentials->SECRET_KEY);

            $Data = array(
                'ClientGuid' => $credentials->CLIENT_GUID,
                'GameId' => $games[0],
                'Currency' => $UsuarioMandante->moneda,
                'Hash' => $hashBet,
            );

            $BetValue = $this->GetBets(json_encode($Data), $credentials->URL);
            $betValue = json_decode($BetValue);

            $dateStart = new DateTime($StartDate);
            $startDate = $dateStart->format("Y-m-d\TH:i:s");

            $dateEnd = new DateTime($EndDate);
            $endDate = $dateEnd->format("Y-m-d\TH:i:s");
            
            $userMandanteIds = [];
            foreach ($users as $user){
                $Usuario = new Usuario($user);
                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                $userMandanteIds[] = $UsuarioMandante->usumandanteId;
            }

            $Params = [
                'ClientGuid' => $credentials->CLIENT_GUID,
                'Name' => $bonoId . '_' . $aditionalIdentifier . '_FS' . $Usuario->usuarioId,
                'GameIds' => array(intval($games[0])),
                'UserIds' => $userMandanteIds,
                "RuleSettings" => [
                    "BetPerLine" => $betValue->Bets[0],
                    "FreeSpinAmount" => intval($roundsFree),
                ],
                "TriggerSettings" => [
                    "FirstGameLaunchTrigger" => true
                ],
                'Hash' => $hash,
                'StartDate' => $startDate,
                'EndDate' => $endDate,
                'Currency' => $UsuarioMandante->moneda,
                'ExternalId' => $bonoId . "B" . $aditionalIdentifier . $Usuario->usuarioId,
            ];

            $path = 'bonuses/FreeSpin/AddRule';
            $response = $this->SendFreeSpin(json_encode($Params), $credentials->URL . $path);
            
            syslog(LOG_WARNING, "MANCALA BONO DATA: " . json_encode($Params) . " RESPONSE: " . $response);
            $response = json_decode($response);

            if ($response->StatusCode != '0') {
                $return = array(
                    "code" => 1,
                    "response_code" => $response->StatusCode,
                    "response_message" => $response->Message
                );
            } else {
                $return = array(
                    "code" => 0,
                    "response_code" => $response->StatusCode,
                    "response_message" => $response->Message
                );
            }

            return $return;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Realiza una solicitud HTTP al proveedor MANCALA.
     *
     * @param array $array_tmp array_tmp
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function Request($Data, $Url)
    {
        $headers = array(
            "Content-type: application/json",
        );

        // Inicializar la clase CurlWrapper
        $curl = new CurlWrapper($Url . "partnersV2/GetToken");

        $curl->setOptionsArray(array(
            CURLOPT_URL => $Url . "partnersV2/GetToken",
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $Data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        ));

        // Ejecutar la solicitud
        $response = $curl->execute();
        return json_decode($response);
    }

    /**
     * Genera una clave de autenticación para las solicitudes.
     *
     * @param boolean $DemoMode   Indica si es modo demo.
     * @param string  $Currency   Moneda utilizada.
     * @param string  $GameCode   Código del juego.
     * @param integer $UserId     ID del usuario.
     * @param string  $ClientGuid GUID del cliente.
     * @param string  $API_KEY    Clave secreta del API.
     * @param string  $path       Ruta del servicio.
     *
     * @return string Clave generada.
     */
    public function generateKey($DemoMode, $Currency, $GameCode, $UserId, $ClientGuid, $API_KEY, $path)
    {
        if ($DemoMode === true) {
            $hash = md5("GetToken/" . $ClientGuid . $GameCode . $API_KEY);
        } else {
            $hash = md5($path . $ClientGuid . $GameCode . $UserId . $Currency . $API_KEY);
        }
        return ($hash);
    }

    /**
     * Envía una solicitud para agregar giros gratis.
     *
     * @param string $data Datos en formato JSON.
     * @param string $url  URL del servicio.
     *
     * @return string Respuesta del servicio.
     */
    public function SendFreeSpin($data, $url)
    {
        // Inicializar la clase CurlWrapper
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

        // Ejecutar la solicitud
        $response = $curl->execute();
        return $response;
    }

    /**
     * Obtiene las apuestas disponibles para un juego.
     *
     * @param string $data Datos en formato JSON.
     * @param string $Url  URL del servicio.
     *
     * @return string Respuesta del servicio.
     */
    public function GetBets($data, $Url)
    {
        $url = $Url . 'partnersV2/GetAvailableBets';

        // Inicializar la clase CurlWrapper
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

        // Ejecutar la solicitud
        $response = $curl->execute();
        return $response;
    }
}

