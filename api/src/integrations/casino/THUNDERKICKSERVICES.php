<?php

/**
 * Clase para la integración con los servicios de Thunderkick.
 *
 * Este archivo contiene la implementación de la clase `THUNDERKICKSERVICES`,
 * que permite la interacción con la API de Thunderkick para gestionar juegos,
 * registro y login de jugadores, y otras funcionalidades relacionadas.
 *
 * @category Red
 * @package  API
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

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
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\Registro;
use Exception;

/**
 * Clase THUNDERKICKSERVICES
 *
 * Proporciona métodos para interactuar con la API de Thunderkick, incluyendo
 * la gestión de juegos, registro y login de jugadores, y el lanzamiento de juegos.
 */
class THUNDERKICKSERVICES
{

    /**
     * Método utilizado en las solicitudes a la API.
     *
     * @var string
     */
    private $method;

    /**
     * Login seguro utilizado en el entorno actual.
     *
     * @var string
     */
    private $secureLogin = 'drb_doradobet';

    /**
     * Login seguro utilizado en el entorno de desarrollo.
     *
     * @var string
     */
    private $secureLoginDEV = 'drb_doradobet';

    /**
     * Login seguro utilizado en el entorno de producción.
     *
     * @var string
     */
    private $secureLoginPROD = 'drb_doradobet';

    /**
     * URL base utilizada en el entorno actual.
     *
     * @var string
     */
    private $URL = '';

    /**
     * URL base utilizada en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://ext-qa-gameservice.thunderkick.com/gamelauncher/play/generic?';

    /**
     * URL base utilizada en el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://game-p1.thunderkick.com/gamelauncher/play/generic?';

    /**
     * URL base para el registro y login de jugadores en el entorno actual.
     *
     * @var string
     */
    private $URLregisterAndLogin = '';

    /**
     * URL base para el registro y login de jugadores en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLregisterAndLoginDEV = 'https://qa-ext-casino.thunderkick.com/casino/';

    /**
     * URL base para el registro y login de jugadores en el entorno de producción.
     *
     * @var string
     */
    private $URLregisterAndLoginPROD = 'https://api-p1.thunderkick.com/casino/';

    /**
     * URL de redirección en el entorno actual.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * URL de redirección en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLREDIRECTIONDEV = 'https%3A%2F%2Fdev.doradobet.com/new-casino';

    /**
     * URL de redirección en el entorno de producción.
     *
     * @var string
     */
    private $URLREDIRECTIONPROD = 'https%3A%2F%2Fdev.doradobet.com/new-casino';

    /**
     * Identificador del operador en el entorno actual.
     *
     * @var string
     */
    private $OperatorId = '';

    /**
     * Usuario de la API del casino en el entorno actual.
     *
     * @var string
     */
    private $CasinoAPIuser = '';

    /**
     * Contraseña de la API del casino en el entorno actual.
     *
     * @var string
     */
    private $CasinoAPIpass = '';

    /**
     * Identificador del operador en el entorno de desarrollo.
     *
     * @var integer
     */
    private $OperatorIdDEV = 1353;

    /**
     * Usuario de la API del casino en el entorno de desarrollo.
     *
     * @var string
     */
    private $CasinoAPIuserDEV = "virtualsoftApi";

    /**
     * Contraseña de la API del casino en el entorno de desarrollo.
     *
     * @var string
     */
    private $CasinoAPIpassDEV = "restPassword";

    /**
     * Identificador del operador en el entorno de producción.
     *
     * @var integer
     */
    private $OperatorIdPROD = 1353;

    /**
     * Usuario de la API del casino en el entorno de producción.
     *
     * @var string
     */
    private $CasinoAPIuserPROD = "virtualsoftApi";

    /**
     * Contraseña de la API del casino en el entorno de producción.
     *
     * @var string
     */
    private $CasinoAPIpassPROD = "jmtJP32Ew7FJWaEd";


    /**
     * Constructor de la clase.
     *
     * Inicializa las variables de entorno dependiendo de si el entorno es de desarrollo
     * o producción, configurando las URLs y credenciales correspondientes.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->secureLogin = $this->secureLoginDEV;
            $this->URLREDIRECTION = $this->URLREDIRECTIONDEV;
            $this->CasinoAPIuser = $this->CasinoAPIuserDEV;
            $this->CasinoAPIpass = $this->CasinoAPIpassDEV;
            $this->OperatorId = $this->OperatorIdDEV;
            $this->URLregisterAndLogin = $this->URLregisterAndLoginDEV;
        } else {
            $this->URL = $this->URLPROD;
            $this->secureLogin = $this->secureLoginPROD;
            $this->URLREDIRECTION = $this->URLREDIRECTIONPROD;
            $this->CasinoAPIuser = $this->CasinoAPIuserPROD;
            $this->CasinoAPIpass = $this->CasinoAPIpassPROD;
            $this->OperatorId = $this->OperatorIdPROD;
            $this->URLregisterAndLogin = $this->URLregisterAndLoginPROD;
        }
    }

    /**
     * Obtiene la URL para lanzar un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo o real.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en el sistema.
     * @param boolean $isMobile      Indica si el dispositivo es móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return void
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "THUNDERKICK");

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);

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
                $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
            }

            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

            $data = array(
                "player" => array(
                    "userName" => $Usuario->usuarioId,
                    "password" => $UsuarioMandante->getUsumandanteId(),
                    "currencyCode" => $UsuarioMandante->moneda,
                    "externalReference" => $Usuario->usuarioId
                ),
                "operatorSessionToken" => $UsuarioToken->token
            );

            $dato = json_encode($data);

            $registerAndLogin = $this->registerAndLogin($dato);

            $registerAndLogin = json_decode($registerAndLogin);

            $playerId = $registerAndLogin->playerId;
            $playerSessionToken = $registerAndLogin->playerSessionToken;


            $device = "";
            if ($isMobile == true) {
                $device = 'mobile';
            } elseif ($isMobile == false) {
                $device = 'desktop';
            }

            $playMode = "real";

            if ($play_for_fun == true) {
                $playMode = "demo";
            } elseif ($play_for_fun == false) {
                $playMode = "real";
            }

            $data2 = array(
                "gameId" => $gameid,
                "device" => $device,
                "langIso" => $lang,
                "currencyIso" => $UsuarioMandante->moneda,
                "operatorId" => $this->OperatorId,
                "playerSessionId" => $playerSessionToken,
                "playMode" => $playMode

            );

            $data2 = json_encode($data2);

            $response = $this->GameLauncher($data2);

            print_r($response);
        } catch (Exception $e) {
        }
    }

    /**
     * Registra y autentica a un jugador en la API de Thunderkick.
     *
     * @param string $data Datos del jugador en formato JSON.
     *
     * @return string Respuesta de la API.
     */
    function registerAndLogin($data)
    {
        $curl = curl_init();
        $this->method = "registerAndLogin";
        $url = $this->URLregisterAndLogin . $this->OperatorId . '/player/' . $this->method;
        $Authorization = base64_encode($this->CasinoAPIuser . ':' . $this->CasinoAPIpass);

        $headers = array(
            'Authorization: Basic ' . $Authorization,
            'Content-Type: application/json'
        );


        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /**
     * Lanza un juego utilizando la API de Thunderkick.
     *
     * @param string $data Datos del juego en formato JSON.
     *
     * @return string Respuesta de la API.
     */
    function GameLauncher($data)
    {
        $curl = curl_init();

        $data = json_decode($data);

        $gameId = $data->gameId;
        $device = $data->device;
        $langIso = $data->langIso;
        $currencyIso = $data->currencyIso;
        $operatorId = $data->operatorId;
        $playerSessionId = $data->playerSessionId;
        $playMode = $data->playMode;
        $url = $this->URL . 'gameId=' . $gameId . '&device=' . $device . '&langIso=' . $langIso . '&' . 'currencyIso=' . $currencyIso . '&operatorId=' . $operatorId . '&playerSessionId=' . $playerSessionId . '&playMode=' . $playMode;


        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: JSESSIONID=505A5DB0B5C71D2F3B815341C293E3C8'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }


}

