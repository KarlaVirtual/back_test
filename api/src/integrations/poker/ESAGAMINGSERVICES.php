<?php

/**
 * Proporciona métodos para interactuar con los servicios de ESA Gaming.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-21
 */

namespace Backend\integrations\poker;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\integrations\poker\ESAGAMING;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;

/**
 * Clase ESAGAMINGSERVICES
 * Proporciona métodos para interactuar con los servicios de ESA Gaming.
 */
class ESAGAMINGSERVICES
{
    // Atributos privados de la clase
    /**
     * Metodo de la solicitud.
     *
     * @var string|null
     */
    private $method = null;

    /**
     * Identificador de la skin utilizado para las solicitudes.
     *
     * @var mixed|null
     */
    private $skinId = null;

    /**
     * Identificador del usuario utilizado para las solicitudes.
     *
     * @var mixed|null
     */
    private $userid = null;

    /**
     * Lista de parámetros utilizados en las solicitudes.
     *
     * @var array
     */
    private $params = [];

    /**
     * Indica si hay un error en la solicitud.
     *
     * @var boolean
     */
    private $error = false;

    /**
     * URL del servicio.
     *
     * @var string
     */
    private $url = "";

    /**
     * URL del servicio en desarrollo.
     *
     * @var string
     */
    private $urlDev = "https://testapi.esagaming.it/backend_atomo_qa_poker/thirdparty_service.php";

    /**
     * URL del servicio en producción.
     *
     * @var string
     */
    private $urlProd = "https://testapi.esagaming.it/backend_atomo_qa/thirdparty_service.php";

    /**
     * URL del servicio de poker.
     *
     * @var string
     */
    private $urlPoker = "";

    /**
     * URL del servicio de poker en desarrollo.
     *
     * @var string
     */
    private $urlPokerDev = "https://doradobet-test.egamingc.com/";

    /**
     * URL del servicio de poker en producción.
     *
     * @var string
     */
    private $urlPokerProd = "";

    /**
     * Usuario utilizado para la autenticación.
     *
     * @var string|null
     */
    private $user = null;

    /**
     * Usuario utilizado para la autenticación en desarrollo.
     *
     * @var string
     */
    private $userDev = "doradobet";

    /**
     * Usuario utilizado para la autenticación en producción.
     *
     * @var string|null
     */
    private $userProd = null;

    /**
     * Contraseña utilizada para la autenticación.
     *
     * @var string|null
     */
    private $password = null;

    /**
     * Contraseña utilizada para la autenticación en desarrollo.
     *
     * @var string
     */
    private $passwordDev = "nrqFU2lZn7";

    /**
     * Contraseña utilizada para la autenticación en producción.
     *
     * @var string|null
     */
    private $passwordProd = null;

    /**
     * Constructor de la clase.
     * Configura las URLs y credenciales según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->url = $this->urlDev;
            $this->user = $this->userDev;
            $this->password = $this->passwordDev;
            $this->urlPoker = $this->urlPokerDev;
        } else {
            $this->url = $this->urlProd;
            $this->user = $this->userProd;
            $this->password = $this->passwordProd;
            $this->urlPoker = $this->urlPokerProd;
        }
    }

    /**
     * Establece la lista de juegos en la base de datos.
     *
     * Este metodo procesa una lista de juegos proporcionada en el parámetro `$data`,
     * y realiza las siguientes acciones:
     * - Inserta los juegos en la base de datos si no existen.
     * - Asocia los juegos con categorías y detalles adicionales.
     * - Maneja transacciones para garantizar la consistencia de los datos.
     *
     * @param object $data Objeto que contiene los datos de los juegos a procesar.
     *
     * @return void
     * @throws Exception Si ocurre un error durante la transacción.
     */
    public function setGameList($data)
    {
        $response = $data;

        $error = false;
        $games = $response->applications;

        if ( ! $error) {
            $ProductoMySqlDAO = new ProductoMySqlDAO();
            $Transaction = $ProductoMySqlDAO->getTransaction();

            try {
                $Proveedor = new Proveedor("", "INB");

                if ($Proveedor != null && $Proveedor != "") {
                    foreach ($games as $clave => $game) {
                        if ($Transaction->isIsconnected()) {
                            $Producto = new Producto();

                            $Producto->setEstado("A");
                            $Producto->setProveedorId($Proveedor->getProveedorId());
                            $Producto->setDescripcion(str_replace("'", "", $game->name[0]->en));
                            $Producto->setImageUrl("http://games.inbetgames.com/static/" . $game->preview);
                            $Producto->setVerifica("I");
                            $Producto->setExternoId($game->source);
                            $Producto->setUsucreaId(0);
                            $Producto->setUsumodifId(0);

                            if ( ! $Producto->existsExternoId()) {
                                $producto_id = $Producto->insert($Transaction);

                                $slug = "";

                                switch ($game->type) {
                                    case "slot":
                                        $slug = "slots";
                                        break;

                                    default:
                                        $slug = "catinbet";
                                        break;
                                }

                                $Categoria = new Categoria("", "", $slug);

                                $CategoriaProducto = new CategoriaProducto();

                                $CategoriaProducto->setProductoId($producto_id);
                                $CategoriaProducto->setCategoriaId($Categoria->getCategoriaId());
                                $CategoriaProducto->setUsumodifId(0);
                                $CategoriaProducto->setUsucreaId(0);

                                $CategoriaProducto->insert($Transaction);


                                $ProductoDetalle = new ProductoDetalle();
                                $ProductoDetalle->setProductoId($producto_id);
                                $ProductoDetalle->setPKey("GAMEID");
                                $ProductoDetalle->setPValue($clave);
                                $ProductoDetalle->setUsucreaId(0);
                                $ProductoDetalle->setUsumodifId(0);

                                $ProductoDetalle->insert($Transaction);

                                $ProductoDetalle = new ProductoDetalle();
                                $ProductoDetalle->setProductoId($producto_id);
                                $ProductoDetalle->setPKey("TYPE");
                                $ProductoDetalle->setPValue($game->type);
                                $ProductoDetalle->setUsucreaId(0);
                                $ProductoDetalle->setUsumodifId(0);

                                $ProductoDetalle->insert($Transaction);

                                $ProductoDetalle = new ProductoDetalle();
                                $ProductoDetalle->setProductoId($producto_id);
                                $ProductoDetalle->setPKey("IMAGE_BACKGROUND");
                                $ProductoDetalle->setPValue("https://i.pinimg.com/originals/33/92/f9/3392f99c1dd718211711848b811dd8da.jpg");
                                $ProductoDetalle->setUsucreaId(0);
                                $ProductoDetalle->setUsumodifId(0);

                                $ProductoDetalle->insert($Transaction);


                                foreach ($game as $key => $val) {
                                    if ($key != "id" && $key != "type") {
                                        $ProductoDetalle = new ProductoDetalle();
                                        $ProductoDetalle->setProductoId($producto_id);
                                        print_r($key);
                                        $ProductoDetalle->setPKey(strtoupper($key));

                                        if (is_object($val)) {
                                            $ProductoDetalle->setPValue(str_replace("'", "", json_encode($val)));
                                        } else {
                                            $ProductoDetalle->setPValue(str_replace("'", "", $val));
                                        }

                                        $ProductoDetalle->setUsucreaId(0);
                                        $ProductoDetalle->setUsumodifId(0);

                                        $ProductoDetalle->insert($Transaction);
                                    }
                                }
                            }
                        }
                    }

                    $Transaction->commit();
                }
            } catch (Exception $e) {
                print_r($e);
                $Transaction->rollback();
            }
        }
    }

    /**
     * Obtiene la URL de un juego específico.
     *
     * @param string $gameid        ID del juego.
     * @param string $lang          Idioma del juego.
     * @param bool   $play_for_fun  Indica si es un juego de prueba.
     * @param string $usuarioToken  Token del usuario.
     * @param string $migameid      ID del juego (opcional).
     * @param string $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid = "", $usumandanteId = "")
    {
        try {
            try {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "ESAGAMING");

                try {
                    //valida si entra por primera vez el usuario
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
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

                /* $Usuario = new Usuario($usumandanteId);
                 $UsuarioMandante = new UsuarioMandante($usumandanteId);
                 $ESAGAMING = new ESAGAMING($usumandanteId);

                 $response2 = $ESAGAMING->load('userAuthenticate');
                 $request = array(
                     "method"=> "userAuthenticate",
                     "username"=> $Usuario->login,
                     "password"=> $Usuario->clave,
                     "partnerId"=> $UsuarioMandante->mandante,
                     "source"=> $UsuarioMandante->dirIp
                 );

                 $response2 = $ESAGAMING->load(json_encode($request));

                */

                if ($gameid === "POKER") {
                    $array = array(
                        "error" => false,
                        "response" => $this->urlPoker,
                    );
                } else {
                    $request = [
                        "method" => "GetGameURL",
                        "skinid" => 1,
                        "userid" => $usumandanteId,
                        "gameid" => $gameid,
                        "language" => $lang,
                        "option" => 2,
                    ];

                    $response = $this->load(json_encode($request));
                    $array = array(
                        "error" => false,
                        "response" => $response,
                    );
                }


                // $this->METHOD = "/Server/LaunchGame";
                // $response = $this->Request($array);

                // $UsuarioToken->setToken($response->Token);
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();


                return json_decode(json_encode($array));
            } catch (Exception $e) {
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Genera una clave única para un jugador.
     *
     * @param string $player Identificador del jugador.
     *
     * @return string Clave generada.
     */
    function generateKey($player)
    {
        $hash = md5($player . md5("TMP" . $player));
        $hash = substr($hash, 0, 12);
        return ($hash);
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

    /**
     * Realiza la conexión con el servicio externo.
     *
     * @return string Respuesta del servicio.
     */
    public function connection()
    {
        $string = $this->convertUrlParams();

        $headers = array(
            "Content-type: multipart/form-data",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
        );
        $ch = curl_init($this->url . $string);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_USERPWD, $this->user . ":" . $this->password);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params);

        $result = (curl_exec($ch));
        return ($result);
    }

    /**
     * Convierte los parámetros de la URL en una cadena de consulta.
     *
     * @return string Cadena de consulta generada.
     */
    function convertUrlParams()
    {
        $string = "?action=" . $this->method;

        foreach ($this->params as $param => $value) {
            $string .= "&" . $param . "=" . $value;
        }

        return $string;
    }

    /**
     * Procesa una solicitud JSON y ejecuta el metodo correspondiente.
     *
     * Este metodo decodifica una solicitud JSON, identifica el metodo solicitado
     * y ejecuta la lógica asociada. Si no se encuentra el metodo, devuelve un error.
     *
     * @param string $request Solicitud en formato JSON.
     *
     * @return mixed Respuesta procesada o un error si el metodo no es válido.
     */
    public function load($request)
    {
        $request = json_decode($request);
        $this->method = $request->method;

        switch ($this->method) {
            case 'GetGameURL':
                $this->GetGameURL($request->skinid, $request->userid, $request->gameId, $request->language, $request->option);
                break;
            case 'BalanceQuery':
                $this->balanceQuery($request->time);
                break;
            case 'GetPlayersActivity':
                $this->getPlayersActivity($request->fromData, $request->toData);
                break;
            case 'GetWalletedGameURL':
                $this->getWalletedGameURL($request);
                break;
            case 'GetGameHistoryURL':
                $this->getGameHistoryURL($request->SessionId, $request->language);
                break;
            case 'GetSessions':
                $this->getSessions($request->count, $request->fromSessionId, $request->active);
                break;
            case 'CloseSession':
                $this->closeSession($request->sessionId);
                break;
            case 'GetSessionInfo':
                $this->getSessionInfo($request->SessionId);
                break;
            case 'GetHistory':
                $this->getHistory($request->count, $request->DateTime);
                break;
            case 'GetAllGames':
                $this->getAllGames($request->IsMobile);
                break;
            case 'GetCampaignDetails':
                $this->getCampaignDetails($request->GameId, $request->option);
                break;
            case 'CancelCampaign':
                $this->cancelCampaign($request->compaignId);
                break;
            case 'UpdateModuleHash':
                $this->updateModuleHash($request->type, $request->AAMSCode, $request->version, $request->subversion, $request->name, $request->checksum);
                break;
            case 'GetWagerInfo':
                $this->getWagerInfo();
                break;
            case 'AddFundsToSession':
                $this->addFundsToSession($request->sessionId, $request->amount);
                break;
            case 'GetUserInfo':
                $this->getUserInfo();
                break;
            case 'ValidateTicket':
                $this->validateTicket($request->otp);
                break;
            case 'GetBonusBalances':
                $this->getBonusBalances();
                break;
            case 'UserTransaction':
                $this->UserTransaction($request->amount, $request->currency, $request->context, $request->description, $request->extref);
                break;
            default:
                return $this->hasError(true, "Metodo no valido");
                break;
        }

        if ( ! $this->error) {
            $result = $this->connection();
            $response = $this->objResponse($result);

            return $response;
        } else {
            return $this->hasError(true, "Faltan campos por completar");
        }
    }

    /**
     * Convierte un código de resultado en un mensaje descriptivo.
     *
     * @param int $code Código de resultado.
     *
     * @return string Mensaje descriptivo.
     */
    public function resultCodeConvert($code)
    {
        switch ($code) {
            case -5:
                $message = "Only one game session is allowed (returned only for specific providers)";
                break;
            case -4:
                $message = "Player blocked for API";
                break;
            case -3:
                $message = "User not found (in response to GetGameURL) or session not found (in response to GetGameHistoryURL)";
                break;
            case -2:
                $message = "Invalid skinid";
                break;
            case 0:
                $message = "Unspecified error";
                break;
            default:
                $message = "Success";
                break;
        }

        return $message;
    }

    /**
     * Procesa la respuesta del servicio externo y la convierte en un objeto.
     *
     * Este metodo toma una respuesta en formato de cadena, la descompone en pares clave-valor,
     * y la convierte en un objeto JSON. Dependiendo del método solicitado, procesa la respuesta
     * y devuelve un resultado estructurado.
     *
     * @param string $response Respuesta en formato de cadena.
     *
     * @return object Objeto con la respuesta procesada.
     */
    public function objResponse($response)
    {
        $response = explode("&", $response);
        $responseData = [];

        foreach ($response as $value) {
            $res = explode("=", $value);
            if (isset($res[1])) {
                if ($res[0] != "") {
                    $responseData[$res[0]] = $res[1];
                } else {
                    $responseData["code"] = $res[1];
                }
            }
        }

        $response = json_decode(json_encode($responseData));

        if (isset($response->exception)) {
            return $response;
        }

        switch ($this->method) {
            case 'GetGameURL':
                $message = $this->resultCodeConvert($response->resultCode);
                $return = [
                    "resultCode" => $response->resultCode,
                    "url" => $response->url,
                    "message" => $message,
                ];
                break;
            case 'BalanceQuery':
                $players = [];
                foreach ($response as $player) {
                    $finalPlayer = [
                        "userid" => $player->userid,
                        "skinid" => $player->skinid,
                        "currency" => $player->currency,
                        "balance" => $player->balance,
                        "inPlayBalance" => $player->in_play_balance,
                        "created" => $player->created
                    ];

                    array_push($players, $finalPlayer);
                }
                $return = $players;
                break;
            case 'GetPlayersActivity':
                $players = [];
                foreach ($response as $player) {
                    $finalPlayer = [
                        "userid" => $player->userid,
                        "skinid" => $player->skinid,
                        "currency" => $player->currency,
                        "bet" => $player->bet,
                        "win" => $player->win,
                        "rake" => $player->rake,
                        "tbet" => $player->tbet,
                        "twin" => $player->twin,
                        "fees" => $player->fees,
                        "bonusAmount" => $player->bonus_amount,
                        "bonusCount" => $player->bonus_count
                    ];

                    array_push($players, $finalPlayer);
                }
                $return = $players;
                break;
            case 'GetWalletedGameURL':
                $message = $this->resultCodeConvert($response->resultCode);
                $return = [
                    "resultCode" => $response->resultCode,
                    "message" => $message,
                    "url" => $response->url,
                    "sessionId" => $response->session_id,
                    "wallet_sessionId" => $response->wallet_session_id,
                    "wallet_ticketId" => $response->wallet_ticket_id
                ];
                break;
            case 'GetGameHistoryURL':
                $message = $this->resultCodeConvert($response->resultCode);
                $return = [
                    "resultCode" => $response->resultCode,
                    "message" => $message,
                    "url" => $response->url
                ];
                break;
            case 'GetSessions':
                $sessions = [];
                foreach ($response as $session) {
                    $finalSessions = [
                        "id" => $session->id,
                        "thirdpartySessionId" => $session->thirdparty_session_id,
                        "rounds" => $session->rounds
                    ];

                    array_push($sessions, $finalSessions);
                }
                $return = $sessions;
                break;
            case 'CloseSession':
                switch ($response) {
                    case -5:
                        $message = "Session has a pending request";
                        break;
                    case -4:
                        $message = "AAMS error";
                        break;
                    case -3:
                        $message = "Third party wallet error";
                        break;
                    case -2:
                        $message = "User not found or invalid";
                        break;
                    case -1:
                        $message = "Session not found";
                        break;
                    case 0:
                        $message = "Failure";
                        break;
                    default:
                        $message = "Success";
                        break;
                }
                $return = [
                    "resultCode" => $response,
                    "message" => $message
                ];
                break;
            case 'GetSessionInfo':
                $return = [
                    "id" => $response->id,
                    "thirdpartySessionId" => $response->thirdparty_session_id,
                    "rounds" => $response->rounds,
                    "won" => $response->won,
                    "bet" => $response->bet,
                    "start" => $response->start,
                    "end" => $response->end,
                    "extra" => [
                        "gameId" => $response->extra->game_id,
                        "gameName" => $response->extra->game_name,
                        "providerId" => $response->extra->provider_id,
                        "providerName" => $response->extra->provider_name,
                        "contains" => $response->extra->contains,
                        "aamsSession" => $response->extra->aams_session,
                        "aamsTicket" => $response->extra->aams_ticket
                    ],
                ];
                break;
            case 'GetHistory':
                $transactions = [];
                foreach ($response as $transaction) {
                    $finalTransaction = [
                        "id" => $transaction->id,
                        "sessionId" => $transaction->session_id,
                        "amount" => $transaction->amount,
                        "currency" => $transaction->currency,
                        "status" => $transaction->status,
                        "type" => $transaction->type,
                        "createTime" => $transaction->createtime,
                        "finishTime" => $transaction->finishtime,
                        "extra" => [
                            "aams_session" => $transaction->extra->aams_session,
                            "aams_ticket" => $transaction->extra->aams_ticket
                        ]
                    ];

                    array_push($transactions, $finalTransaction);
                }
                $return = $transactions;
                break;
            case 'GetAllGames':
                $games = [];
                foreach ($response as $game) {
                    $finalGame = [
                        "gameId" => $game->game_id,
                        "gameData" => [
                            "gameName" => $game->game_data['game_name'],
                            "provider" => $game->game_data['provider'],
                            "gameImage" => $game->game_data['game_image']
                        ]
                    ];

                    array_push($game, $finalGame);
                }
                $return = $games;
                break;
            case 'GetCampaignDetails':
                if ($response->type == 1) {
                    $nameType = "fun bonus";
                } else {
                    $nameType = "wagering bonus";
                }
                $return = [
                    "campaignId" => $response->campaign_id,
                    "campaignName" => $campaign_name,
                    "type" => $response->type,
                    "nameType" => $nameType,
                    "isrestricted" => $response->isrestricted,
                    "amount" => $response->amount,
                    "maxBets" => $response->max_bets,
                    "expiration" => $response->expiration
                ];
                break;
            case 'CancelCampaign':
                if ($response) {
                    $message = "Success";
                } else {
                    $message = "Failture";
                }
                $return = [
                    "resutlCode" => $response,
                    "message" => $message
                ];
                break;
            case 'UpdateModuleHash':
                if ($response) {
                    $message = "Success";
                } else {
                    $message = "Failture";
                }
                $return = [
                    "resultCode" => $response,
                    "message" => $message
                ];
                break;
            case 'GetWagerInfo':
                $return = [
                    "bonusAmount" => $response->bonus_amount,
                    "multiplier" => $response->multiplier,
                    "milestone" => $response->milestone,
                    "weekdays" => $response->weekdays,
                    "sumSlotWager" => $response->sum_slot_wager
                ];
                break;
            case 'AddFundsToSession':
                switch ($response) {
                    case -13:
                        $message = "AAMS error";
                        break;
                    case -12:
                        $message = "Third party wallet error or insufficient funds";
                        break;
                    case -11:
                        $message = "Third party wallet timeout";
                        break;
                    case -10:
                        $message = "Session already has a pending request";
                        break;
                    case -8:
                        $message = "Game blocked";
                        break;
                    case -7:
                        $message = "Total funds above 1000 euro";
                        break;
                    case -6:
                        $message = "Invalid or negative amount";
                        break;
                    case -5:
                        $message = "Session belongs to a campaign";
                        break;
                    case -4:
                        $message = "Session already closed";
                        break;
                    case -3:
                        $message = " Player blocked for API";
                        break;
                    case -2:
                        $message = "User not found";
                        break;
                    case -1:
                        $message = "Session not found or doesn't belong to the user";
                        break;
                    case 0:
                        $message = "Failure";
                        break;
                    case 1:
                        $message = "Success";
                        break;
                }
                $return = [
                    "resultCode" => $response->resultCode,
                    "message" => $message,
                    "balance" => $response->balance,
                    "seq" => $response->seq
                ];
                break;
            case 'GetUserInfo':
                $return = [
                    "userid" => $response->userid,
                    "username" => $response->username,
                    "externUsername" => $response->extern_username,
                    "firstname" => $response->firstname,
                    "lastname" => $response->lastname,
                    "email" => $response->email,
                    "currency" => $response->currency,
                    "balance" => $response->balance
                ];
                break;
            case 'ValidateTicket':
                $return = [
                    "userid" => $response->userid,
                    "username" => $response->username,
                    "externUsername" => $response->extern_username,
                    "firstname" => $response->firstname,
                    "lastname" => $response->lastname,
                    "email" => $response->email,
                    "currency" => $response->currency,
                    "balance" => $response->balance
                ];
                break;
            case 'GetBonusBalances':
                switch ($$response->device) {
                    case 0:
                        $device = "desktop";
                        break;
                    case 1:
                        $device = "mobile";
                        break;
                    default:
                        $device = "all";
                        break;
                }

                if ($response->type == 1) {
                    $type = "fun bonus";
                } else {
                    $type = "real bonus";
                }
                $return = [
                    "id" => $response->id,
                    "type" => $type,
                    "typeCode" => $response->type,
                    "device" => $device,
                    "deviceCode" => $response->device,
                    "amount" => $response->firstname,
                    "lastname" => $response->lastname,
                    "email" => $response->email,
                    "currency" => $response->currency,
                    "balance" => $response->balance
                ];
                break;
            case 'UserTransaction':
                switch ($response->code) {
                    case -5:
                        $message = "Missing extref";
                        break;
                    case -4:
                        $message = "Value is between -1 and 1";
                        break;
                    case -3:
                        $message = "User not found";
                        break;
                    case -2:
                        $message = "Invalid skinid";
                        break;
                    case -1:
                        $message = "Unsupported currency";
                        break;
                    case 0:
                        $message = "Transaction failed";
                        break;
                    case 1:
                        $message = "Transaction succeeded";
                        break;
                }
                $return = [
                    "resultCode" => $response->code,
                    "message" => $message
                ];

                break;
            default:
                return $this->hasError(true, "Metodo no valido");
                break;
        }

        return json_decode(json_encode($return));
    }

    /**
     * Valida los datos de entrada.
     *
     * @param array $request Datos a validar.
     *
     * @return void
     */
    public function validateData($request)
    {
        foreach ($request as $key) {
            if ($key == "") {
                $this->error = true;
            }
        }
    }

    /**
     * Genera un mensaje de error.
     *
     * @param bool   $error   Indica si hay un error.
     * @param string $message Mensaje de error.
     *
     * @return array Mensaje de error formateado.
     */
    public function hasError($error = true, $message)
    {
        return [
            "error" => true,
            "message" => $message
        ];
    }

    /**
     * Obtiene la información del usuario.
     *
     * Este metodo configura los parámetros necesarios para realizar
     * una solicitud al servicio externo y obtener la información del usuario.
     *
     * @return void
     */
    public function getUserInfo()
    {
        $this->params = [
            "skinid" => $this->skinId,
            "userid" => $this->userid
        ];
    }

    /**
     * Valida un ticket.
     *
     * @param string $otp Código OTP.
     *
     * @return void
     */
    public function validateTicket($otp)
    {
        $this->validateData([$otp]);

        $this->params = [
            "skinid" => $this->skinId,
            "userid" => $this->userid,
            "otp" => $otp
        ];
    }

    /**
     * Obtiene los balances de bonificación del usuario.
     *
     * Este metodo configura los parámetros necesarios para realizar
     * una solicitud al servicio externo y obtener los balances de bonificación
     * asociados al usuario actual.
     *
     * @return void
     */
    public function getBonusBalances()
    {
        $this->params = [
            "skinid" => $this->skinId,
            "userid" => $this->userid,
        ];
    }

    /**
     * Realiza una transacción de usuario.
     *
     * @param float  $amount       Monto de la transacción.
     * @param string $currency     Moneda de la transacción.
     * @param string $context      Contexto de la transacción.
     * @param string $description  Descripción de la transacción.
     * @param string $sessionState Estado de la sesión.
     * @param string $extref       Referencia externa.
     *
     * @return void
     */
    public function UserTransaction(

        $amount,
        $currency,
        $context,
        $description,
        $sessionState,
        $extref
    ) {
        $this->params = [
            "skinid" => $this->skinId,
            "userid" => $this->userid,
            "amount" => $amount,
            "currency" => $currency,
            "context" => $context,
            "description" => $description,
            "sessionState" => $sessionState,
            "extref" => $extref,
        ];
    }

    /**
     * Consulta el balance de un usuario en un momento específico.
     *
     * Este metodo valida los datos de entrada y configura los parámetros necesarios
     * para realizar una solicitud al servicio externo que consulta el balance.
     *
     * @param string $time Tiempo en formato adecuado para la consulta.
     *
     * @return void
     */
    public function balanceQuery($time)
    {
        $this->validateData([$time]);

        $this->params = [
            "skinid" => $this->skinId,
            "time" => $time
        ];
    }

    /**
     * Cierra una sesión.
     *
     * @param string $session_id ID de la sesión.
     *
     * @return void
     */
    public function CloseSession($session_id)
    {
        //$this->validateData([$time]);

        $this->params = [
            "session_id" => $session_id
        ];
    }

    /**
     * Obtiene la actividad de los jugadores.
     *
     * @param string $fromData Fecha de inicio.
     * @param string $toData   Fecha de fin.
     *
     * @return void
     */
    public function getPlayersActivity($fromData, $toData)
    {
        $this->validateData([$fromData, $toData]);

        $this->params = [
            "skinid" => $this->skinId,
            "from" => $fromData,
            "to" => $toData
        ];
    }

    /**
     * Obtiene la URL de un juego específico.
     *
     * Este metodo configura los parámetros necesarios para realizar
     * una solicitud al servicio externo y obtener la URL del juego.
     *
     * @param int    $skin     Identificador de la skin.
     * @param int    $user     Identificador del usuario.
     * @param string $gameid   Identificador del juego.
     * @param string $language Idioma del juego.
     * @param int    $option   Opción específica para la solicitud.
     *
     * @return void
     */
    public function GetGameURL($skin, $user, $gameid, $language, $option)
    {
        $this->params = [
            "skinid" => $skin,
            "userid" => $user,
            "gameid" => $gameid,
            "language" => $language,
            "option" => $option
        ];
    }

    /**
     * Obtiene la URL del historial de un juego específico.
     *
     * Este metodo configura los parámetros necesarios para realizar
     * una solicitud al servicio externo y obtener la URL del historial
     * de un juego basado en el ID de la sesión y el idioma proporcionados.
     *
     * @param string $session_id ID de la sesión del juego.
     * @param string $language   Idioma en el que se desea la información.
     *
     * @return void
     */
    public function getGameHistoryURL($session_id, $language)
    {
        $this->validateData([
            $session_id,
            $language
        ]);

        $this->params = [
            "session_id" => $session_id,
            "language" => $language
        ];
    }

    /**
     * Obtiene las sesiones de juego.
     *
     * @param int    $count          Cantidad de sesiones.
     * @param string $from_sessionid ID de la sesión inicial.
     * @param bool   $active         Indica si la sesión está activa.
     *
     * @return void
     */
    public function getSessions($count, $from_sessionid, $active)
    {
        $this->validateData([
            $count,
            $from_sessionid,
            $active
        ]);

        $this->params = [
            "skinid" => $this->skinId,
            "userid" => $this->userid,
            "count" => $count,
            "from_sessionid" => $from_sessionid,
            "active" => $active
        ];
    }

    /**
     * Cierra una sesión de juego.
     *
     * @param string $session_id ID de la sesión.
     *
     * @return void
     */
    public function loseSession($session_id)
    {
        $this->validateData([
            $session_id
        ]);

        $this->params = [
            "session_id" => $session_id
        ];
    }

    /**
     * Obtiene información de una sesión de juego.
     *
     * @param string $session_id ID de la sesión.
     *
     * @return void
     */
    public function getSessionInfo($session_id)
    {
        $this->validateData([
            $session_id
        ]);

        $this->params = [
            "session_id" => $session_id
        ];
    }

    /**
     * Obtiene el historial de juego.
     *
     * @param int    $count    Cantidad de registros.
     * @param string $datetime Fecha y hora del registro.
     *
     * @return void
     */
    public function getHistory($count, $datetime)
    {
        $this->validateData([
            $count,
            $datetime
        ]);

        $this->params = [
            "skinid" => $this->skinId,
            "userid" => $this->userid,
            "count" => $count,
            "datetime" => $datetime
        ];
    }

    /**
     * Obtiene todos los juegos disponibles.
     *
     * @param bool $is_mobile Indica si es para móvil.
     *
     * @return void
     */
    public function getAllGames($is_mobile)
    {
        $this->validateData([
            $is_mobile
        ]);

        $this->params = [
            "skinid" => $this->skinId,
            "is_mobile" => $is_mobile
        ];
    }

    /**
     * Obtiene los detalles de una campaña.
     *
     * @param string $game_id ID del juego.
     * @param string $option  Opción de la campaña.
     *
     * @return void
     */
    public function getCampaignDetails($game_id, $option)
    {
        $this->validateData([
            $game_id,
            $option,
        ]);

        $this->params = [
            "skinid" => $this->skinId,
            "userid" => $this->userid,
            "game_id" => $game_id,
            "option" => $option
        ];
    }

    /**
     * Cancela una campaña.
     *
     * @param string $campaign_id ID de la campaña.
     *
     * @return void
     */
    public function cancelCampaign($campaign_id)
    {
        $this->validateData([
            $campaign_id
        ]);

        $this->params = [
            "skinid" => $this->skinId,
            "userid" => $this->userid,
            "campaign_id" => $campaign_id
        ];
    }

    /**
     * Actualiza el hash del módulo.
     *
     * @param string $type       Tipo de módulo.
     * @param string $AAMSCode   Código AAMS.
     * @param string $version    Versión del módulo.
     * @param string $subversion Subversión del módulo.
     * @param string $name       Nombre del módulo.
     * @param string $checksum   Suma de verificación.
     *
     * @return void
     */
    public function updateModuleHash($type, $AAMSCode, $version, $subversion, $name, $checksum)
    {
        $this->validateData([
            $type,
            $AAMSCode,
            $version,
            $subversion,
            $name,
            $checksum
        ]);

        $this->params = [
            "type" => $type,
            "AAMSCode" => $AAMSCode,
            "version" => $version,
            "subversion" => $subversion,
            "name" => $name,
            "checksum" => $checksum
        ];
    }

    /**
     * Obtiene información de la apuesta.
     *
     * @return void
     */
    public function getWagerInfo()
    {
        $this->params = [
            "skinid" => $this->skinId,
            "userid" => $this->userid
        ];
    }

    /**
     * Agrega fondos a una sesión.
     *
     * @param string $sessionid ID de la sesión.
     * @param float  $amount    Monto a agregar.
     *
     * @return void
     */
    public function addFundsToSession($sessionid, $amount)
    {
        $this->validateData([
            $sessionid,
            $amount
        ]);

        $this->params = [
            "skinid" => $this->skinId,
            "userid" => $this->userid,
            "sessionid" => $sessionid,
            "amount" => $amount
        ];
    }

    /**
     * Obtiene la URL del juego con billetera.
     *
     * @param string $gameid      ID del juego.
     * @param float  $amount      Monto a agregar.
     * @param string $language    Idioma del juego.
     * @param string $option      Opción del juego.
     * @param string $ip          Dirección IP del usuario.
     * @param string $campaign_id ID de la campaña.
     * @param string $platform    Plataforma del juego.
     *
     * @return void
     */
    public function GetWalletedGameURL($gameid, $amount, $language, $option, $ip, $campaign_id, $platform)
    {
        $this->validateData([
            $gameid,
            $amount,
            $language,
            $option,
            $ip,
            $campaign_id,
            $platform,
        ]);

        $sessionid = '';
        $this->params = [
            "skinid" => $this->skinId,
            "userid" => $this->userid,
            "gameid" => $gameid,
            "amount" => $amount,
            "language" => $language,
            "option" => $option,
            "ip" => $ip,
            "campaign_id" => $campaign_id,
            "platform" => $platform,
            "sessionid" => $sessionid,
            "amount" => $amount
        ];
    }

}

