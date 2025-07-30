<?php

/**
 * Clase EXPANSESERVICES
 *
 * Esta clase proporciona servicios relacionados con la integración de casinos, incluyendo
 * la obtención de juegos, la asignación de giros gratis y la interacción con APIs externas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use DateTime;
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
 * Clase que proporciona servicios relacionados con la integración de casinos.
 * Incluye funcionalidades como la obtención de juegos, asignación de giros gratis,
 * y la interacción con APIs externas.
 */
class EXPANSESERVICES
{
    /**
     * URL de redirección para el casino.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * URL para la gestión de depósitos.
     *
     * @var string
     */
    private $URLDEPOSIT = "";

    /**
     * Constructor de la clase.
     * Inicializa la configuración del entorno.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }

    /**
     * Obtiene un juego específico y genera la URL de lanzamiento.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en miniatura.
     * @param boolean $isMobile      Indica si el dispositivo es móvil.
     * @param string  $usumandanteId ID del usuario mandante.
     * @param boolean $minigame      Indica si es un minijuego.
     *
     * @return object Respuesta con la URL del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $UsuarioToken, $migameid, $isMobile = false, $usumandanteId = "", $minigame = false)
    {
        $Proveedor = new Proveedor("", "EXPANSE");
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
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $credentials = json_decode($SubproveedorMandantePais->getCredentials());
                $token = $UsuarioToken->getToken();

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
                    $this->URLDEPOSIT = $Mandante->baseUrl . "gestion/deposito";
                }

                if ($isMobile) {
                    $isMobile = "mobile";
                } else {
                    $isMobile = "desktop";
                }
                $Brand = strtok($Mandante->descripcion, '.');

                $launchParams = array(
                    "mode" => "real",
                    "platform" => $isMobile,
                    "player_id" => $UsuarioMandante->usumandanteId,
                    "language" => $lang,
                    "currency" => $Usuario->moneda,
                    "token" => $UsuarioToken->getToken(),
                    "brand" => $Brand
                );

                $path = 'clients/' . $credentials->ClientID . '/markets/' . $credentials->MarketID . '/games/' . $gameid . '/launch';

                syslog(LOG_WARNING, "EXPANSE DATA:" . json_encode($launchParams));

                $response = $this->LaunchUrl(json_encode($launchParams), $credentials->ApiUrl . $path, $credentials->ApiUser, $credentials->ApiPass);

                syslog(LOG_WARNING, "EXPANSE RESPONSE:" . $response);

                $response = json_decode($response);

                $array = array(
                    "error" => false,
                    "response" => $response->url
                );

                if ($_REQUEST['debug'] == '1') {
                    print_r("\r\n");
                    print_r('****DATA USER****');
                    print_r(json_encode($launchParams));
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
     * Realiza una solicitud POST para lanzar un juego.
     *
     * @param string $Data    Datos de la solicitud en formato JSON.
     * @param string $Url     URL de la API.
     * @param string $ApiUser Usuario de la API.
     * @param string $ApiPass Contraseña de la API.
     *
     * @return string Respuesta de la API.
     */
    public function LaunchUrl($Data, $Url, $ApiUser, $ApiPass){
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
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($ApiUser . ':' . $ApiPass)
            ),
        ));

        $response = $curl->execute($curl);
        return $response;
    }

    /**
     * Asigna giros gratis a los usuarios.
     *
     * @param string  $bonoElegido         Bono seleccionado.
     * @param integer $roundsFree          Número de giros gratis.
     * @param float   $roundsValue         Valor de cada giro.
     * @param string  $StartDate           Fecha de inicio.
     * @param string  $EndDate             Fecha de fin.
     * @param array   $users               Lista de usuarios.
     * @param array   $games               Lista de juegos.
     * @param string  $aditionalIdentifier ID del bono del usuario.
     * @param string  $NombreBono          Nombre del bono.
     * @param float   $multiplier          Multiplicador de ganancias.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoElegido, $roundsFree, $roundsValue, $StartDate, $EndDate, $users, $games, $aditionalIdentifier, $NombreBono, $multiplier) :array {
        try {
            $Usuario = new Usuario($users[0]);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            $Proveedor = new Proveedor("", "EXPANSE");
            $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $StarDateObj = new DateTime($StartDate);
            $starDate = $StarDateObj->format('Y-m-d\TH:i:sO');

            $EndDateObj = new DateTime($EndDate);
            $endDate = $EndDateObj->format('Y-m-d\TH:i:sO');

            $valid_days = $StarDateObj->diff($EndDateObj);
            $total_days = $valid_days->days;

            $multiplicador = $roundsValue * $roundsFree;
            $Multiplier = $multiplicador * $multiplier;

            $Path = 'fsb/clients/' . $credentials->ClientID . '/markets/' . $credentials->MarketID . '/games';
            $Values = $this->GameList($credentials->ApiUrl . $Path, $credentials->ApiUser, $credentials->ApiPass);
            $data = json_decode($Values, true);

            $stakeListTotal = null;
            foreach ($data["fsb_games"] as $game) {
                if ($game["code"] == $games[0]) {
                    $stakeListTotal = $game["stake_list_total"];
                    break;
                }
            }

            $spinValues = array(
                "USD" => $stakeListTotal['USD'][0],
                "BRL" => $stakeListTotal['BRL'][0],
                "CLP" => $stakeListTotal['CLP'][0],
                "GTQ" => $stakeListTotal['GTQ'][0],
                "HNL" => $stakeListTotal['HNL'][0],
                "CRC" => $stakeListTotal['CRC'][0],
                "PEN" => $stakeListTotal['PEN'][0]
            );

            foreach ($spinValues as $moneda => $valor) {
                if ($Usuario->moneda == $moneda) {
                    unset($spinValues[$Usuario->moneda]);
                    break;
                }
            }

            $spinValues[$Usuario->moneda] = doubleval($roundsValue);
            $spinWin = [];
            $cancelBalance = [];
            foreach ($spinValues as $moneda => $valor) {
                $spinWin[$moneda] = 0;
                $cancelBalance[$moneda] = 10000;
            }

            $code = $bonoElegido . $aditionalIdentifier . $UsuarioMandante->usumandanteId;
            $Data = array(
                "name" => $NombreBono,
                "code" => "$code",
                "market_id" => intval($credentials->MarketID),
                "exists_from" => $starDate,
                "exists_to" => $endDate,
                "platform" => "any",
                "bonus_type" => "SPIN_NUMBER",
                "games" => array($games[0]),
                "spin_number" => doubleval($roundsFree),
                "spin_win_limits" => $spinWin,
                "spin_values" => $spinValues,
                "wagering_requirement" => 0,
                "cancel_balance" => (object)[],
                "valid_days" => $total_days,
                "max_win_multiplier" => $Multiplier
            );

            $path = 'fsb/clients/' . $credentials->ClientID . '/markets/' . $credentials->MarketID . '/templates';
            $response = $this->SendFreespins($Data, $credentials->ApiUrl . $path, $credentials->ApiUser, $credentials->ApiPass);
            syslog(LOG_WARNING, "EXPANSE BONO DATA:" . json_encode($Data) . ' | RESPONSE: ' . $response);

            $response = json_decode($response);
            if ($response->err_code != '') {
                $return = array(
                    "code" => 1,
                    "response_code" => $response->player_list->id,
                    "response_message" => 'Error'
                );

            } else {
                $PlayerList = [];
                foreach ($users as $user) {
                    $Usuario = new Usuario($user);
                    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                    $PlayerData = [
                        "id" => intval($UsuarioMandante->usumandanteId),
                        "currency" => $Usuario->moneda
                    ];
                    array_push($PlayerList, $PlayerData);
                }

                $DataPlayers = array(
                    "player_list" => $PlayerList
                );

                $pathUsers = 'fsb/clients/' . $credentials->ClientID . '/markets/' . $credentials->MarketID . '/templates/' . $response->fsb_template->id . '/bonuses';
                $response = $this->SendFreespins($DataPlayers, $credentials->ApiUrl . $pathUsers, $credentials->ApiUser, $credentials->ApiPass);
                syslog(LOG_WARNING, "EXPANSE ASSIGNMENT DATA:" . json_encode($Data) . ' | RESPONSE: ' . $response);

                $response = json_decode($response);
                if ($response->err_code != '') {
                    $return = array(
                        "code" => 1,
                        "response_code" => $response->player_list->id,
                        "response_message" => 'Error'
                    );

                } else {
                    $return = array(
                        "code" => 0,
                        "response_code" => $response->player_list->id,
                        "response_message" => 'OK'
                    );
                }
            }

            return $return;

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Envía datos para asignar giros gratis a través de una solicitud POST.
     *
     * @param array  $Data    Datos de la solicitud.
     * @param string $Url     URL de la API.
     * @param string $ApiUser Usuario de la API.
     * @param string $ApiPass Contraseña de la API.
     *
     * @return string Respuesta de la API.
     */
    public function SendFreespins($Data, $Url, $ApiUser, $ApiPass){
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
            CURLOPT_POSTFIELDS => json_encode($Data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($ApiUser . ':' . $ApiPass)
            ),
        ));

        $response = $curl->execute($curl);
        return $response;
    }

    /**
     * Obtiene la lista de juegos desde la API.
     *
     * @param string $Url     URL de la API.
     * @param string $ApiUser Usuario de la API.
     * @param string $ApiPass Contraseña de la API.
     *
     * @return string Respuesta de la API con la lista de juegos.
     */
    public function gameList($Url, $ApiUser, $ApiPass) {
        $curl = new CurlWrapper($Url);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $Url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($ApiUser . ':' . $ApiPass)
            ),
        ));

        $response = $curl->execute($curl);
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