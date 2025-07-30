<?php

/**
 * Este archivo contiene la clase `G7777GAMINGSERVICES` que implementa servicios relacionados con el proveedor 7777GAMING.
 * Proporciona métodos para gestionar juegos, crear bonificaciones de giros gratis y obtener información de monedas.
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
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase `G7777GAMINGSERVICES`
 *
 * Esta clase implementa servicios relacionados con el proveedor 7777GAMING,
 * incluyendo la gestión de juegos, creación de bonificaciones y obtención de información de monedas.
 */
class G7777GAMINGSERVICES
{
    /**
     * URL de redirección utilizada en los métodos de la clase.
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
     * Obtiene la URL de lanzamiento de un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es para diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $LaunchId      ID de lanzamiento.
     * @param boolean $isMobile      Indica si el juego es para dispositivos móviles.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL de lanzamiento del juego.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $LaunchId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "7777GAMING");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

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
                $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino/proveedor/7777GAMING";
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $LAUNCH_URL = $credentials->LAUNCH_URL;
            $MARKET = $credentials->MARKET;

            $moneda = $UsuarioMandante->moneda;
            $o_lt = $UsuarioToken->getToken();
            $origin = $this->URLREDIRECTION;

            $exitUrl = $Mandante->baseUrl . 'new-casino';
            $array = array(
                "error" => false,
                "response" => $LAUNCH_URL . '/game.html?game=' . $gameid . '&lang_label=' . $lang . '&currency_label=' . $moneda . '&o_lt=' . $o_lt . '&market_label=' . $MARKET . '&origin=' . $origin . "&homeURL=" . $exitUrl
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Crea una bonificación de giros gratis para los usuarios.
     *
     * @param string  $bonoId              ID del bono.
     * @param integer $roundsFree          Número de giros gratis.
     * @param string  $StartDate           Fecha de inicio de la bonificación.
     * @param string  $EndDate             Fecha de finalización de la bonificación.
     * @param array   $users               Lista de usuarios.
     * @param array   $games               Lista de juegos.
     * @param string  $aditionalIdentifier Identificador adicional.
     *
     * @return array Respuesta con el estado de la creación del bono.
     */
    public function AddFreespins($bonoId, $roundsFree, $StartDate, $EndDate, $users, $games, $aditionalIdentifier)
    {
        $Usuario = new Usuario($users[0]);

        $Proveedor = new Proveedor("", "7777GAMING");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $currency = $UsuarioMandante->moneda;
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $KEY = $credentials->KEY;
        $TOKEN = $credentials->TOKEN;
        $BONUS_URL = $credentials->BONUS_URL;
        $ACCESS_ID = $credentials->ACCESS_ID;
        $MARKET = $credentials->MARKET;

        $AccessChecksum = sha1($KEY . '###' . $TOKEN . '###' . $KEY);
        $betValuesResp = $this->getCoins($AccessChecksum, $currency, $games, $ACCESS_ID, $KEY, $BONUS_URL);
        $betValuesResp = json_decode($betValuesResp);
        $betValues = array($betValuesResp->List[0]->games[0]->bet_values[0]);
        $totalBonus = ($roundsFree) * ($betValues[0]);

        $possibleNominals = [
            [
                "nominalOptions" => $betValues,
                "bonusValue" => $totalBonus
            ]
        ];

        $timestampInicio = strtotime($StartDate);
        $timestampFin = strtotime($EndDate);
        $diferenciaSegundos = $timestampFin - $timestampInicio;
        $bonusActiveUntilDuration = $diferenciaSegundos / 3600;
        $roundedBonusActiveUntilDuration = round($bonusActiveUntilDuration);

        $appData = array(
            "AccessId" => $ACCESS_ID,
            "AccessChecksum" => $AccessChecksum,
            "requestId" => $bonoId . '_' . $aditionalIdentifier . '_' . $Usuario->usuarioId,
            "title" => 'free rounds text',
            "bonusType" => "free_rounds",
            "gamePrimeType" => 1,
            "currency" => $currency,
            "possibleNominals" => $possibleNominals,
            "flexibleValues" => 0,
            "includedGames" => $games,
            "minBonusAmount" => 0.01,
            "maxBonusAmount" => 2500.00,
            "maxBonusWinAmount" => 1000.00,
            "currentState" => "active",
            "bonusActiveUntilDate" => $EndDate,
            "bonusActiveUntilDuration" => $roundedBonusActiveUntilDuration,
            "bonusAssignmentStartDate" => $StartDate,
            "bonusAssignmentEndDate" => $EndDate
        );

        $encodedAppData = base64_encode(json_encode($appData));
        $signatureString = $KEY . '##' . $encodedAppData . '##' . $KEY;
        $signature = hash('sha256', $signatureString);
        $data = array(
            "app_data" => $encodedAppData,
            "signature" => $signature
        );

        $path = '/bonus_manager/create/';

        $response = $this->SendFreespins(json_encode($data), $BONUS_URL . $path);

        $responseCode = http_response_code();
        $response = json_decode($response, true);
        $app_data = base64_decode($response['app_data']);

        syslog(LOG_WARNING, "7777GAMING BONO CREATION DATA: " . json_encode($appData) . " | RESPONSE: " . $response);

        $app_data = json_decode($app_data, true);

        if (oldCount($app_data['Error']) > 0) {
            $return = array(
                "code" => 1,
                "response_code" => $responseCode,
                "response_message" => 'Error'
            );
        } else {
            //Assign Bonus Campaign to User Method
            $appDataUser = array(
                "AccessId" => $ACCESS_ID,
                "AccessChecksum" => $AccessChecksum,
                "requestId" => $aditionalIdentifier . '_' . $Usuario->usuarioId,
                "BonusId" => $app_data['BonusId'],
                "operatorId" => $MARKET,
                "currency" => $currency,
                "users" => array(),
            );

            foreach ($users as $user) {
                $Usuario = new Usuario($user);
                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                $userIn = array(
                    "nominalOptions" => $betValues,
                    "bonusValue" => $totalBonus,
                    "userId" => $UsuarioMandante->usumandanteId,
                );
                array_push($appDataUser["users"], $userIn);
            }

            $encodedAppDataUser = base64_encode(json_encode($appDataUser));

            $signatureString = $KEY . '##' . $encodedAppDataUser . '##' . $KEY;

            $signature = hash('sha256', $signatureString);

            $dataUser = array(
                "app_data" => $encodedAppDataUser,
                "signature" => $signature
            );

            $pathUser = '/bonuses/assign/';

            $response = $this->SendFreespins(json_encode($dataUser), $BONUS_URL . $pathUser);

            syslog(LOG_WARNING, "7777GAMING BONO-USER CREATION DATA: " . json_encode($appDataUser) . " | RESPONSE: " . $response);

            $responseCode = http_response_code();
            $response = json_decode($response, true);
            $app_data_user = base64_decode($response['app_data']);
            
            $app_data_user = json_decode($app_data_user, true);

            if (oldCount($app_data_user['Error']) > 0) {
                $return = array(
                    "code" => 1,
                    "response_code" => $responseCode,
                    "response_message" => 'Error'
                );
            } else {
                $return = array(
                    "code" => 0,
                    "response_code" => $responseCode,
                    "response_message" => 'OK',
                    "bonusId" => $response->app_data->BonusId
                );
            }
        }
        return $return;
    }


    /**
     * Obtiene información sobre las monedas disponibles para una campaña.
     *
     * @param string $AccessChecksum Checksum de acceso.
     * @param string $currency       Moneda utilizada.
     * @param array  $games          Lista de juegos.
     * @param string $ACCESS_ID      ID de acceso.
     * @param string $KEY            Clave de acceso.
     * @param string $BONUS_URL      URL del servicio de bonificaciones.
     *
     * @return string Información de las monedas en formato JSON.
     */
    public function getCoins($AccessChecksum, $currency, $games, $ACCESS_ID, $KEY, $BONUS_URL)
    {
        $appDataCoins = array(
            "AccessId" => $ACCESS_ID,
            "AccessChecksum" => $AccessChecksum,
            "gameIds" => $games,
            "currencyCodes" => [
                $currency
            ]
        );

        syslog(LOG_WARNING, "7777GAMING BONO COINS DATA: " . json_encode($appDataCoins));

        $encodedAppData = base64_encode(json_encode($appDataCoins));
        $signatureString = $KEY . '##' . $encodedAppData . '##' . $KEY;
        $signature = hash('sha256', $signatureString);
        $data = array(
            "app_data" => $encodedAppData,
            "signature" => $signature
        );

        $responseCoins = $this->SendFreespins(json_encode($data), $BONUS_URL . '/bonus_manager/info/coins/');

        $responseCoins = json_decode($responseCoins, true);
        $app_dataCoins = base64_decode($responseCoins['app_data']);

        return $app_dataCoins;
    }

    /**
     * Envía una solicitud para gestionar giros gratis.
     *
     * @param string $data Datos de la solicitud en formato JSON.
     * @param string $url  URL del servicio.
     *
     * @return string Respuesta del servicio.
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
}
