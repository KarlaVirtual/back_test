<?php

/**
 * Clase PRAGMATICSERVICES
 *
 * Esta clase proporciona servicios de integración con el proveedor PRAGMATIC,
 * incluyendo la gestión de juegos, creación de bonos de giros gratis, y obtención
 * de valores de apuestas.
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
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\CategoriaProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase que proporciona servicios de integración con el proveedor PRAGMATIC.
 * Incluye funcionalidades como la gestión de juegos, creación de bonos de giros gratis,
 * y obtención de valores de apuestas.
 */
class PRAGMATICSERVICES
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
     * @param string  $lang          Idioma del juego (es, en, pt).
     * @param boolean $play_for_fun  Indica si es modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego interno.
     * @param boolean $isMobile      Indica si es para móvil.
     * @param string  $usumandanteId ID del usuario mandante.
     * @param boolean $minigame      Indica si es un minijuego.
     * @param string  $miniGameUrl   URL del minijuego.
     * @param boolean $minimode      Indica si es modo mini.
     *
     * @return object Respuesta con la URL del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid, $isMobile = false, $usumandanteId = "", $minigame = false, $miniGameUrl = '', $minimode = false)
    {
        try {
            $Producto = new Producto($migameid);
            $SubProveedor = new Subproveedor($Producto->getSubproveedorId());

            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "PRAGMATIC");

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken(substr($token, 0, strlen($token) - strlen("vssv" . $migameid)) . "vssv" . $migameid);
                $UsuarioToken->setEstado('A');
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
                    $UsuarioToken->setToken(substr($token, 0, strlen($token) - strlen("vssv" . $migameid)) . "vssv" . $migameid);
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

            if ($Mandante->mandante == '2') {
                $Mandante->baseUrl = "https://www.acropolisonline.com/";
            }

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
            }

            if ($lang == "en") {
                $lang = "en";
            } elseif ($lang == "pt") {
                $lang = "pt";
            } else {
                $lang = "es";
            }

            if ($isMobile) {
                $platform = 'MOBILE';
            } else {
                $platform = 'WEB';
            }

            if ($minimode == 1) {
                $modemini = '1';
            } else {
                $modemini = '0';
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            if ($SubProveedor->abreviado == 'PRAGMATICBINGO') {
                $array = array(
                    "error" => false,
                    "response" => $credentials->URL_BINGO . '?key=token%3D' . $UsuarioToken->getToken() . '%26lobbyUrl%3D' . urlencode($this->URLREDIRECTION) . '%26cashierUrl%3D' . urlencode(str_replace("new-casino", "gestion/deposito", $this->URLREDIRECTION)) . "%26room%3D" . $gameid . "%26technology%3DH5%26platform%3D" . $platform . "%26language%3D" . $lang . '%26gamesLobby%3D' . $miniGameUrl . '&stylename=' . $credentials->LOGIN_BINGO,
                );
            } else {
                if ($play_for_fun) {
                    $array = array(
                        "error" => false,
                        "response" => 'https://demogamesfree.pragmaticplay.net' . '/gs2c/openGame.do?gameSymbol=' . $gameid . '&lang=' . $lang . '&cur=USD&lobbyURL=' . urlencode($this->URLREDIRECTION)
                    );
                } else {
                    $Params = [
                        'secureLogin' => $credentials->LOGIN,
                        'symbol' => $gameid,
                        'language' => $lang,
                        'currency' => $UsuarioMandante->moneda,
                        'token' => $UsuarioToken->getToken(),
                        'lobbyUrl' => $this->URLREDIRECTION,
                        'cashierUrl' => $Mandante->baseUrl . "gestion/deposito",
                        'technology' => 'H5',
                        'stylename' => $credentials->LOGIN,
                        'platform' => $platform,
                        'minimode' => $modemini
                    ];

                    ksort($Params);
                    $paramString = '';
                    foreach ($Params as $key => $value) {
                        if ( ! empty($value)) {
                            $paramString .= $key . '=' . $value . '&';
                        }
                    }

                    $launch = $paramString;
                    $hash = md5(rtrim($paramString, '&') . $credentials->KEY);
                    $data = $launch . 'hash=' . $hash;
                    $path = '/IntegrationService/v3/http/CasinoGameAPI/game/url/';

                    $response = $this->LaunchUrl($data, $credentials->URL . $path);
                    $data = json_decode($response);

                    if ($_ENV['debug']) {
                        print_r($response);
                    }

                    $array = array(
                        "error" => false,
                        "response" => $data->gameURL,
                    );
                }
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Lanza una solicitud CURL para obtener la URL del juego.
     *
     * @param string $data Datos de la solicitud.
     * @param string $URL  URL del servicio.
     *
     * @return string Respuesta del servicio.
     */
    public function LaunchUrl($data, $URL)
    {
        $curl = new CurlWrapper($URL);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = $curl->execute();

        return $response;
    }

    /**
     * Crea un bono de giros gratis.
     *
     * @param integer $bonoId              ID del bono.
     * @param integer $roundsFree          Número de giros gratis.
     * @param float   $roundvalue          Valor de cada giro.
     * @param string  $StartDate           Fecha de inicio.
     * @param string  $EndDate             Fecha de fin.
     * @param array   $users               Lista de usuarios.
     * @param array   $games               Lista de juegos.
     * @param string  $aditionalIdentifier Identificador adicional para los free spin.
     *
     * @return array Respuesta del servicio.
     */
    public function AddFreespins($bonoId, $roundsFree, $roundvalue, $StartDate, $EndDate, $users, $games, $aditionalIdentifier)
    {
        $Usuario = new Usuario($users[0]);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Cureency = $UsuarioMandante->moneda;

        $Proveedor = new Proveedor("", "PRAGMATIC");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $array = array(
            "gameList" => array()
        );

        foreach ($games as $gameId) {
            $game = array(
                "gameId" => $gameId,
                "betValues" => array(
                    array(
                        "totalBet" => floatval($roundvalue),
                        "currency" => $Cureency
                    )
                )
            );
            array_push($array["gameList"], $game);
        }

        $aditionalIdentifier = $bonoId . $aditionalIdentifier . $Usuario->usuarioId;
        $Params = [
            'secureLogin' => $credentials->LOGIN,
            'bonusCode' => $aditionalIdentifier,
            'startDate' => strtotime($StartDate),
            'expirationDate' => strtotime($EndDate),
            'validityDate' => strtotime($EndDate),
            'rounds' => $roundsFree,
            'currency' => $Cureency
        ];


        ksort($Params);
        $paramString = '';
        foreach ($Params as $key => $value) {
            if ($value !== null && $value !== '') {
                $paramString .= $key . '=' . $value . '&';
            }
        }
        $paramString = rtrim($paramString, '&');
        $paramString .= $credentials->KEY;
        $hash = md5($paramString);

        $Params2 = [
            'secureLogin' => $credentials->LOGIN,
            'bonusCode' => $aditionalIdentifier,
            'startDate' => strtotime($StartDate),
            'expirationDate' => strtotime($EndDate),
            'validityDate' => strtotime($EndDate),
            'rounds' => $roundsFree,
            'currency' => $Cureency,
            'hash' => $hash
        ];

        ksort($Params2);
        $paramString = '';
        foreach ($Params2 as $key => $value) {
            if ( ! empty($value)) {
                $paramString .= $key . '=' . $value . '&';
            }
        }
        $predecode = urldecode(http_build_query($Params));
        $request = urlencode($predecode);
        $request = rtrim($paramString, '&');

        $patch = '/IntegrationService/v3/http/FreeRoundsBonusAPI/v2/bonus/create';
        
        $response = $this->SendFreespins(json_encode($array), $credentials->URL . $patch . '?' . $request);
        syslog(LOG_WARNING, "PRAGMATIC BONO DATA: " . $request . ' ' . json_encode($array) . " RESPONSE: " . $response);
        $response = json_decode($response);

        if ($response->error != '0') {
            $return = array(
                "code" => 1,
                "response_code" => $response->error,
                "response_message" => 'Error'
            );
        } else {
            $addPlayer = $this->AddPlayers($users, $aditionalIdentifier, $credentials->LOGIN, $credentials->KEY, $credentials->URL);
            $response = json_decode($addPlayer);
            if ($response->error != '0') {
                $return = array(
                    "code" => 1,
                    "response_code" => $response->error,
                    "response_message" => 'Error'
                );
            } else {
                $return = array(
                    "code" => 0,
                    "response_code" => $response->error,
                    "response_message" => 'OK',
                    "bonusId" => $bonoId
                );
            }
        }
        return $return;
    }

    /**
     * Envía los datos del bono de giros gratis al servicio.
     *
     * @param string $data Datos del bono.
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
            CURLOPT_TIMEOUT => 60,
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
     * Agrega jugadores a un bono de giros gratis.
     *
     * @param array  $users                Lista de usuarios.
     * @param string $aditionalIdentifier  Código del bono.
     * @param string $login                Credenciales de inicio de sesión.
     * @param string $skey                 Clave de seguridad.
     * @param string $url                  URL del servicio.
     *
     * @return string Respuesta del servicio.
     */
    public function AddPlayers($users, $aditionalIdentifier, $login, $skey, $url)
    {
        $playerList = array(
            "playerList" => array(),
        );
        foreach ($users as $user) {
            $Usuario = new Usuario($user);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            $mandante = $UsuarioMandante->mandante;
            $userId = $UsuarioMandante->usumandanteId . "_" . $UsuarioMandante->moneda;
            array_push($playerList["playerList"], $userId);
        }

        $Params = [
            'secureLogin' => $login,
            'bonusCode' => $aditionalIdentifier
        ];
        ksort($Params);
        $paramString = '';
        foreach ($Params as $key => $value) {
            if ($value !== null && $value !== '') {
                $paramString .= $key . '=' . $value . '&';
            }
        }
        $paramString = rtrim($paramString, '&');
        $paramString .= $skey;
        $hash = md5($paramString);

        $Params2 = [
            'secureLogin' => $login,
            'bonusCode' => $aditionalIdentifier,
            'hash' => $hash
        ];
        ksort($Params2);
        $paramString = '';
        foreach ($Params2 as $key => $value) {
            if ( ! empty($value)) {
                $paramString .= $key . '=' . $value . '&';
            }
        }
        $predecode = urldecode(http_build_query($Params));
        $request = urlencode($predecode);
        $request = rtrim($paramString, '&');

        $patch = '/IntegrationService/v3/http/FreeRoundsBonusAPI/v2/players/add/';

        syslog(LOG_WARNING, "PRAGMATIC BONO DATA USER: " . $request . ' ' . json_encode($playerList));

        $response = $this->AddUser(json_encode($playerList), $url . $patch . '?' . $request);

        syslog(LOG_WARNING, "PRAGMATIC BONO RESPONSE USER: " . json_encode($response));

        return $response;
    }

    /**
     * Envía los datos de un jugador al servicio.
     *
     * @param string $data Datos del jugador.
     * @param string $url  URL del servicio.
     *
     * @return string Respuesta del servicio.
     */
    public function AddUser($data, $url)
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
     * Obtiene los valores de apuestas para una lista de juegos.
     *
     * @param array   $games     Lista de juegos.
     * @param string  $mandante  ID del mandante.
     * @param integer $CountryId ID del país.
     *
     * @return string Respuesta con los valores de apuestas.
     */
    public function getValues($games, $mandante, $CountryId)
    {
        $Proveedor = new Proveedor("", "PRAGMATIC");
        $ProductoMandante = new ProductoMandante("", $mandante, $games[0], $CountryId);
        $Producto = new Producto($ProductoMandante->productoId);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $mandante, $CountryId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $Pais = new Pais($CountryId);

        $Externos = '';
        foreach ($games as $game) {
            $ProductoMandante = new ProductoMandante("", $mandante, $game, $CountryId);
            $Producto = new Producto($ProductoMandante->productoId);
            $Externos .= $Producto->externoId . ',';
        }
        $Externos = rtrim($Externos, ',');

        $Params = [
            'secureLogin' => $credentials->LOGIN,
            'gameIDs' => $Externos,
            'currencies' => $Pais->moneda
        ];

        ksort($Params);
        $paramString = '';
        foreach ($Params as $key => $value) {
            if ($value !== null && $value !== '') {
                $paramString .= $key . '=' . $value . '&';
            }
        }
        $paramString = rtrim($paramString, '&');
        $paramString .= $credentials->KEY;
        $hash = md5($paramString);

        $Params['hash'] = $hash;

        ksort($Params);
        $paramString = '';
        foreach ($Params as $key => $value) {
            if ( ! empty($value)) {
                $paramString .= $key . '=' . $value . '&';
            }
        }
        $predecode = urldecode(http_build_query($Params));
        $request = urlencode($predecode);
        $request = rtrim($paramString, '&');

        $patch = '/IntegrationService/v3/http/CasinoGameAPI/getBetScales/';

        syslog(LOG_WARNING, "PRAGMATIC VALUES DATA: " . $request);

        $response = $this->GetListGames($request, $credentials->URL . $patch);

        syslog(LOG_WARNING, "PRAGMATIC VALUES RESPONSE: " . $response);

        $data = json_decode($response);

        $return = [];
        $return["data"] = [];
        if ($data->error == '0' && isset($data->gameList) && ! empty($data->gameList)) {
            $dataList = [];
            foreach ($data->gameList as $value) {
                $Producto = new Producto("", $value->gameID, $Proveedor->getProveedorId());

                $totalBetScales = array_map(function ($scale) {
                    if (floor($scale) == $scale) {
                        return (strpos((string)$scale, '.') === false) ? (string)$scale . '.0' : (string)$scale;
                    } else {
                        return rtrim(rtrim((string)$scale, '0'), '.');
                    }
                }, $value->betScaleList[0]->totalBetScales);

                $List = [
                    'Id' => $Producto->getProductoId(),
                    'Name' => $Producto->descripcion,
                    'Values' => $totalBetScales
                ];
                array_push($dataList, $List);
            }
            $return["error"] = false;
            $return["msg"] = $data->description;
            $return["data"] = $dataList;
        } else {
            $return["error"] = true;
            $return["msg"] = $data->description;
        }

        return json_encode($return, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Lanza una solicitud CURL para obtener la lista de juegos.
     *
     * @param string $data Datos de la solicitud.
     * @param string $url  URL del servicio.
     *
     * @return string Respuesta del servicio.
     */
    public function GetListGames($data, $url)
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
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = $curl->execute();
        return $response;
    }
}
