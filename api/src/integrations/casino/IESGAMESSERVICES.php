<?php

/**
 * Clase `IESGAMESSERVICES` para la integración con servicios de juegos de casino.
 *
 * Este archivo contiene la implementación de una clase que interactúa con la API de IES Games,
 * permitiendo realizar operaciones como autenticación, apuestas externas, consultas de resultados,
 * y obtención de información sobre rifas y salas de bingo.
 *
 * @category Integración
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
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase `IESGAMESSERVICES`.
 *
 * Esta clase proporciona métodos para interactuar con la API de IES Games,
 * incluyendo autenticación, gestión de juegos, apuestas externas, y consultas
 * relacionadas con rifas y salas de bingo.
 */
class IESGAMESSERVICES
{
    /**
     * URL base genérica.
     *
     * @var string
     */
    private $URL = '';

    /**
     * Nombre de usuario genérico.
     *
     * @var string
     */
    private $userName = "";

    /**
     * Contraseña genérica.
     *
     * @var string
     */
    private $password = "";

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token = "";

    /**
     * Tipo de operación o endpoint.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Constructor de la clase.
     *
     * Configura las URLs y credenciales según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la configuración de un juego.
     *
     * @param string  $GameCode      Código del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si es un juego de prueba.
     * @param string  $usuarioToken  Token del usuario.
     * @param integer $productoId    ID del producto.
     * @param boolean $isMobile      Indica si es un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante.
     *
     * @return object Configuración del juego.
     */
    public function getGame($GameCode, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($play_for_fun) {
                $array = array(
                    "GameCode" => $GameCode,
                    "CurrencyCode" => "",
                    "Platform" => 0,
                    "LanguageCode" => $lang,
                    "RoomsUrl" => "",
                    "RafflesUrl" => "",
                    "PlayerIP" => "",
                    "PlayerId" => "",
                    "userName" => "",
                    "password" => "",
                    "TotalBalance" => "",
                    "CountryCode" => ""
                );

                return json_decode(json_encode($array));
            } else {

                $Proveedor = new Proveedor("", "IESGAMES");
                $Producto = new Producto($productoId);

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
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante(), "", "", $Mandante->mandante);

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $userName = $Credentials->USERNAME;
                $password = $Credentials->PASSWORD;
                $this->URL = $Credentials->URL;

                $Pais = new Pais($UsuarioMandante->paisId);

                $this->tipo = "/server/launchgame";

                $UserIp = explode(",", $Usuario->dirIp);
                $UserIp = $UserIp[0];

                switch (strtoupper($Pais->iso)) {
                    case "PE":
                        $Pais->iso = "PER";
                        break;
                    case "EC":
                        $Pais->iso = "ECU";
                        break;
                    case "CL":
                        $Pais->iso = "CHI";
                        break;
                    case "NI":
                        $Pais->iso = "NIC";
                        break;
                    case "CR":
                        $Pais->iso = "CRC";
                        break;
                }

                $mobile = 0;
                if ($isMobile) {
                    $mobile = 1;
                }

                $array = array(
                    "platform" => $mobile,
                    "gameCode" => $GameCode,
                    "playerIp" => $UserIp,
                    "playerId" => $UsuarioMandante->usumandanteId,
                    "currencyCode" => $UsuarioMandante->moneda,
                    "languageCode" => "ES",
                    "countryCode" => $Pais->iso,
                    "totalBalance" => floatval($Usuario->getBalance()),
                    "account" => array(
                        "userName" => $userName,
                        "password" => $password
                    )
                );

                $response = $this->Request($array);

                if ($response->url != "") {
                    $array = array(
                        "error" => false,
                        "url" => $response->url
                    );
                    return json_decode(json_encode($array));
                } else {
                    throw new Exception("Error General", "1");
                }
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Autentica al usuario en la API.
     *
     * @param integer $PaisId   ID del país.
     * @param integer $mandante ID del mandante.
     *
     * @return object Respuesta de autenticación.
     */
    public function Authenticate($PaisId, $mandante)
    {
        $Subproveedor = new Subproveedor("", "IESGAMES");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandante, $PaisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $userName = $Credentials->API_USERNAME;
        $password = $Credentials->API_PASSWORD;
        $this->URL = $Credentials->URL;

        $this->tipo = "/authenticate";

        $array = array(
            "username" => $userName,
            "password" => $password
        );

        $response = $this->Request($array);

        $array = array(
            "error" => false,
            "response" => $response->id_token
        );
        return json_decode(json_encode($array));
    }

    /**
     * Obtiene las salas de bingo disponibles.
     *
     * @param array   $data    Filtros para la consulta.
     * @param integer $site_id ID del sitio.
     * @param integer $paisId  ID del país.
     *
     * @return string JSON con las salas disponibles.
     */
    public function GetRooms($data = "", $mandante = "", $PaisId = "")
    {
        $Subproveedor = new Subproveedor("", "IESGAMES");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandante, $PaisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $userName = $Credentials->USERNAME;
        $password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;

        $this->tipo = "/server/bingo/getrooms";

        $array = array(
            "account" => array(
                "userName" => $userName,
                "password" => $password
            ),
            "filter" => $data["filter"]

        );

        $response = $this->Request($array);
        $response = json_encode($response);
        return $response;
    }

    /**
     * Obtiene las rifas disponibles para un sitio y país específicos.
     *
     * Este método realiza una solicitud a la API para obtener las rifas disponibles
     * basándose en los datos proporcionados, el ID del sitio y el ID del país.
     *
     * @param array   $data    Datos de la solicitud, incluyendo filtros y nombre de la sala.
     * @param integer $site_id ID del sitio (mandante).
     * @param integer $paisId  ID del país.
     *
     * @return string JSON con las rifas disponibles.
     */
    public function GetRaffles($data, $mandante = "", $PaisId = "")
    {
        $Subproveedor = new Subproveedor("", "IESGAMES");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandante, $PaisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $userName = $Credentials->USERNAME;
        $password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;

        $this->tipo = "/server/bingo/getraffles";

        $array = array(
            "account" => array(
                "userName" => $userName,
                "password" => $password
            ),
            "roomName" => $data["roomName"],
            "filter" => $data["filter"]
        );

        $response = $this->Request($array);
        $response = json_encode($response);
        return $response;
    }

    /**
     * Obtiene todas las rifas disponibles para un usuario.
     *
     * Este método realiza una solicitud a la API para obtener todas las rifas disponibles
     * basándose en los datos proporcionados y la información del usuario.
     *
     * @param object  $data    Datos de la solicitud, incluyendo filtros.
     * @param Usuario $Usuario Objeto que representa al usuario.
     *
     * @return string JSON con las rifas disponibles.
     */
    public function GetAllRaffles($data, Usuario $Usuario = null)
    {
        $Subproveedor = new Subproveedor("", "IESGAMES");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $userName = $Credentials->USERNAME;
        $password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;

        $this->tipo = "/server/bingo/getallraffles";
        $array = array(
            "account" => array(
                "userName" => $userName,
                "password" => $password
            ),
            "filter" => $data->filter
        );

        $response = $this->Request($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_encode($array);
    }

    /**
     * Obtiene las rifas básicas disponibles para un usuario.
     *
     * Este método realiza una solicitud a la API para obtener las rifas básicas
     * basándose en los datos proporcionados y la información del usuario.
     *
     * @param object  $data    Datos de la solicitud, incluyendo filtros y nombre de la sala.
     * @param Usuario $Usuario Objeto que representa al usuario.
     *
     * @return string JSON con las rifas básicas disponibles.
     */
    public function GetBasicRaffles($data, Usuario $Usuario = null)
    {
        $Subproveedor = new Subproveedor("", "IESGAMES");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $userName = $Credentials->USERNAME;
        $password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;

        $this->tipo = "/server/bingo/getbasicraffles";
        $array = array(
            "account" => array(
                "userName" => $userName,
                "password" => $password
            ),
            "RoomName" => $data->roomName,
            "filter" => $data->filter
        );

        $response = $this->Request($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_encode($array);
    }

    /**
     * Obtiene el historial de rifas para un usuario.
     *
     * Este método realiza una solicitud a la API para obtener el historial de rifas
     * basándose en los datos proporcionados y la información del usuario.
     *
     * @param object  $data    Datos de la solicitud, incluyendo filtros.
     * @param Usuario $Usuario Objeto que representa al usuario.
     *
     * @return string JSON con el historial de rifas.
     */
    public function Historical($data, Usuario $Usuario = null)
    {
        $Subproveedor = new Subproveedor("", "IESGAMES");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $userName = $Credentials->USERNAME;
        $password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;

        $this->tipo = "/server/bingo/historical";
        $array = array(
            "account" => array(
                "userName" => $userName,
                "password" => $password
            ),
            "filter" => $data->filter
        );

        $response = $this->Request($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_encode($array);
    }

    /**
     * Obtiene las rifas asociadas a un usuario.
     *
     * Este método realiza una solicitud a la API para obtener las rifas disponibles
     * basándose en los datos proporcionados y la información del usuario.
     *
     * @param object  $data    Datos de la solicitud, incluyendo filtros.
     * @param Usuario $Usuario Objeto que representa al usuario.
     *
     * @return string JSON con las rifas disponibles.
     */
    public function MyRaffles($data, Usuario $Usuario = null)
    {
        $Subproveedor = new Subproveedor("", "IESGAMES");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $userName = $Credentials->USERNAME;
        $password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;

        $this->tipo = "/server/bingo/myraffles";
        $array = array(
            "account" => array(
                "userName" => $userName,
                "password" => $password
            ),
            "filter" => $data->filter
        );

        $response = $this->Request($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_encode($array);
    }

    /**
     * Obtiene las transacciones de un jugador.
     *
     * Este método realiza una solicitud a la API para obtener las transacciones
     * asociadas a un jugador específico, basándose en los datos proporcionados.
     *
     * @param object $data Objeto que contiene los datos de la solicitud:
     *                     - playerId: ID del jugador.
     *                     - filter: Filtros aplicados a la consulta.
     *
     * @return string JSON con las transacciones del jugador.
     */
    public function PlayerTransactions($data)
    {
        $this->tipo = "/server/playertransactions";
        $array = array(
            "account" => array(
                "userName" => $this->userName,
                "password" => $this->password
            ),
            "playerId" => $data->playerId,
            "filter" => $data->filter
        );

        $response = $this->Request($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_encode($array);
    }

    /**
     * Obtiene información completa de las rifas.
     *
     * Este método realiza una solicitud a la API para obtener información detallada
     * sobre las rifas disponibles, basándose en los filtros proporcionados.
     *
     * @param object $data Objeto que contiene los datos de la solicitud:
     *                     - filter: Filtros aplicados a la consulta.
     *
     * @return string JSON con la información completa de las rifas.
     */
    public function GetCompleteRafflesInfo($data)
    {
        $this->tipo = "/server/bingo/getcompleterafflesinfo";
        $array = array(
            "account" => array(
                "userName" => $this->userName,
                "password" => $this->password
            ),
            "filter" => $data->filter
        );

        $response = $this->Request($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_encode($array);
    }

    /**
     * Realiza una apuesta externa.
     *
     * @param object  $data    Datos de la apuesta.
     * @param Usuario $Usuario Usuario que realiza la apuesta.
     *
     * @return string JSON con la respuesta de la apuesta.
     */
    public function ExternalBet($data, Usuario $Usuario = null)
    {
        $Subproveedor = new Subproveedor("", "IESGAMES");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $userName = $Credentials->USERNAME;
        $password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;

        $this->tipo = "/server/bingo/bet";
        $array = array(
            "account" => array(
                "userName" => $userName,
                "password" => $password
            ),
            "playerId" => $data->playerId,
            "currency" => $data->currency,
            "amount" => $data->amount,
            "gameCode" => $data->gameCode,
            "platformTransactionId" => $data->platformTransactionId,
            "numCards" => $data->numCards
        );

        $response = $this->RequestServe($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_encode($array);
    }

    /**
     * Registra una apuesta externa secuencial.
     *
     * Este método realiza una solicitud a la API para registrar una apuesta externa
     * basada en los datos proporcionados, el usuario y el producto.
     *
     * @param array   $data     Datos de la apuesta, incluyendo:
     *                          - gameCode: Código del juego.
     *                          - amount: Monto de la apuesta.
     *                          - numCards: Número de cartas.
     *                          - playerId: ID del jugador.
     *                          - platformTransactionId: ID de la transacción en la plataforma.
     * @param Usuario $Usuario  Objeto que representa al usuario que realiza la apuesta.
     * @param object  $Producto Objeto que representa el producto asociado a la apuesta.
     *
     * @return object Respuesta de la API con el resultado de la apuesta.
     */
    public function SecuencialExternalBet($data, Usuario $Usuario = null, $Producto = null)
    {
        $Pais = new Pais($Usuario->paisId);

        $productoId  = $Producto->productoId;
        $sql = "
        SELECT *
                FROM prodmandante_pais 
                WHERE prodmandante_pais.producto_id = $productoId
                AND prodmandante_pais.estado = 'A'
                AND prodmandante_pais.pais_id = $Pais->paisId
        ";

        $ProdmandantePais = new \Backend\dto\ProdmandantePais();
        $ProdmandantePaisMySqlDAO = new \Backend\mysql\ProdmandantePaisMySqlDAO();
        $transaccion = $ProdmandantePaisMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();
        $datas = $ProdmandantePais->execQuery($transaccion, $sql);

        foreach ($datas as $datanum) {
            $token = $datanum->{'prodmandante_pais.extra_info'};
            $ProdmandantePais->execQuery($transaccion, $sql);
        }

        $this->token = $token;

        $Subproveedor = new Subproveedor("", "IESGAMES");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $userName = $Credentials->USERNAME;
        $password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;

        $this->tipo = "/server/bingo/registrybet";

        $array = array(
            "account" => array(
                "userName" => $userName,
                "password" => $password
            ),
            "gameCode" => $data["gameCode"],
            "amount" => $data["amount"],
            "numCards" => $data["numCards"],
            "playerId" => $data["playerId"],
            "platformTransactionId" => $data["platformTransactionId"]
        );

        $response = $this->RequestServe($array);
        $response = json_decode(json_encode($response));

        if ($response->status->errorCode == '0'  && $response->status->errorMsg == "Success") {
            $array = array(
                "error" => false,
                "response" => $response
            );

            return json_decode(json_encode($array));
        } else {
            $array = array(
                "error" => true,
                "response" => $response->status->errorMsg
            );

            return json_decode(json_encode($array));
        }
    }

    /**
     * Consulta el resultado de una apuesta externa.
     *
     * Este método realiza una solicitud a la API para obtener los datos
     * relacionados con una transacción de apuesta externa específica.
     *
     * @param object $data Objeto que contiene los datos de la solicitud:
     *                     - idIesTransaction: ID de la transacción en IES.
     *
     * @return string JSON con la respuesta de la consulta.
     */
    public function QueryResultExternalBet($data)
    {
        $this->tipo = "/server/bingo/betdata";
        $array = array(
            "account" => array(
                "userName" => $this->userName,
                "password" => $this->password
            ),
            "idIesTransaction" => $data->idIesTransaction
        );

        $response = $this->RequestServe($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_encode($array);
    }

    /**
     * Obtiene la vista de un juego.
     *
     * Este método realiza una solicitud a la API para obtener información
     * relacionada con la vista de un juego, incluyendo detalles como el tipo
     * de vista, código del juego, moneda, plataforma, idioma y país.
     *
     * @return string JSON con la respuesta de la vista del juego.
     */
    public function GameView()
    {
        $this->tipo = "/server/gameview";
        $array = array(
            "account" => array(
                "userName" => $this->userName,
                "password" => $this->password
            ),
            "ViewType" => " ",
            "GameCode" => " ",
            "CurrencyCode" => " ",
            "Platform" => " ",
            "LanguageCode" => " ",
            "countryCode" => " "
        );

        $response = $this->Request($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_encode($array);
    }

    /**
     * Consulta el resultado de una apuesta externa para un operador.
     *
     * Este método realiza una solicitud a la API para obtener los datos
     * relacionados con una transacción de apuesta externa específica,
     * incluyendo el ID de la transacción en IES y el ID de la transacción
     * del operador.
     *
     * @param object $data Objeto que contiene los datos de la solicitud:
     *                     - idIesTransaction: ID de la transacción en IES.
     *                     - idOperatorTransaction: ID de la transacción del operador.
     *
     * @return string JSON con la respuesta de la consulta.
     */
    public function QueryOperatorResultExternalBet($data)
    {
        $this->tipo = "/server/bingo/betdata/operator";

        $array = array(
            "account" => array(
                "userName" => $this->userName,
                "password" => $this->password
            ),
            "idIesTransaction" => $data->idIesTransaction,
            "idOperatorTransaction" => $data->idOperatorTransaction
        );

        $response = $this->RequestServe($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_encode($array);
    }

    /**
     * Realiza una venta por rango.
     *
     * Este método realiza una solicitud a la API para registrar una venta
     * basada en un rango de tablas, el jugador y otros datos proporcionados.
     *
     * @return string JSON con la respuesta de la venta por rango.
     */
    public function SellByRange()
    {
        $this->tipo = "/server/bingo/sellbyrange";
        $array = array(
            "Token" => " ",
            "account" => array(
                "userName" => $this->userName,
                "password" => $this->password
            ),
            "PlayerId" => " ",
            "tablaIni" => " ",
            "tablaFin" => '',
            "amount" => " ",
            "gameCode" => " ",
            "platformTransactionId" => ''
        );

        $response = $this->Request($array);

        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_encode($array);
    }

    /**
     * Realiza una solicitud a la API.
     *
     * @param array $data Datos de la solicitud.
     *
     * @return object Respuesta de la API.
     */
    public function Request($data)
    {
        $data =  json_encode($data);
        $curl = new CurlWrapper($this->URL . $this->tipo);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $this->URL . $this->tipo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();
        $response = json_decode($response);
        return $response;
    }

    /**
     * Realiza una solicitud a la API con autenticación por token.
     *
     * @param array $data Datos de la solicitud.
     *
     * @return object Respuesta de la API.
     */
    public function RequestServe($data)
    {
        $data =  json_encode($data);
        $curl = new CurlWrapper($this->URL . $this->tipo);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $this->URL . $this->tipo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization:Bearer " . $this->token
            ),
        ));

        $response = $curl->execute();
        $response = json_decode($response);
        return $response;
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * Este método verifica varias cabeceras del servidor para determinar
     * la dirección IP del cliente. Si no se encuentra ninguna dirección IP válida,
     * devuelve 'UNKNOWN'.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
     */
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}
