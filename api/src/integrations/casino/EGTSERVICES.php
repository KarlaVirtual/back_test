<?php

/**
 * Este archivo contiene la clase `EGTSERVICES` que proporciona servicios relacionados con la integración de juegos de casino.
 * Incluye métodos para obtener juegos, agregar giros gratis y enviar solicitudes relacionadas con bonos.
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
use Department;
use \CurlWrapper;
use DateTimeZone;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\BonoDetalle;
use Backend\dto\UsuarioBono;
use Backend\dto\Departamento;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\dto\ProveedorMandante;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase `EGTSERVICES`.
 * 
 * Proporciona servicios relacionados con la integración de juegos de casino,
 * incluyendo métodos para obtener juegos, agregar giros gratis y gestionar solicitudes de bonos.
 */
class EGTSERVICES
{
    /**
     * URL de redirección utilizada por defecto.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * URL de redirección para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLREDIRECTION_DEV = 'https://devfrontend.virtualsoft.tech/doradobet/new-casino';

    /**
     * URL de redirección para el entorno de producción.
     *
     * @var string
     */
    private $URLREDIRECTION_PROD = 'https://doradobet.com/new-casino';

    /**
     * Constructor de la clase `EGTSERVICES`.
     * 
     * Inicializa el entorno de configuración para determinar si es un entorno de desarrollo o producción.
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
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en miniatura.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     * @param boolean $isMobile      Indica si el juego es para dispositivos móviles (opcional).
     * @param boolean $minigame      Indica si es un minijuego (opcional).
     *
     * @return object Respuesta con la URL de redirección y otros datos.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $UsuarioToken, $migameid, $usumandanteId = "", $isMobile = false, $minigame = false)
    {
        $Proveedor = new Proveedor("", "EGT");
        $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($UsuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

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

        if ($Mandante->baseUrl != '') {
            $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $play_for_fun = "false";
        } else {
            $play_for_fun = "false";
        }

        $array = array(
            "error" => false,
            "response" => $credentials->launchUrl . "?sessionToken=" . $UsuarioToken->token . "&casinoId=" . $credentials->casinoId . "&playerId=" . $UsuarioMandante->usumandanteId . "&gameKey=" . $Producto->externoId . "&currencyCode=" . $UsuarioMandante->moneda . "&language=" . $lang . "&closeUrl=" . $this->URLREDIRECTION . "&mode=Desktop&demo=" . $play_for_fun
        );

        return json_decode(json_encode($array));
    }

    /**
     * Agrega giros gratis a un usuario para un conjunto de juegos.
     *
     * @param integer $bonoElegido ID del bono elegido.
     * @param integer $roundsFree  Número de giros gratis.
     * @param float   $roundsValue Valor de cada giro.
     * @param string  $StartDate   Fecha de inicio de la campaña.
     * @param string  $EndDate     Fecha de finalización de la campaña.
     * @param array   $user        Lista de usuarios.
     * @param array   $games       Lista de juegos.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoElegido, $roundsFree, $roundsValue, $StartDate, $EndDate, $user, $games)
    {
        $Usuario = new Usuario($user[0]);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Proveedor = new Proveedor("", "EGT");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URLBONUS = $credentials->UrlBonus;
        $username = $credentials->username;
        $password = $credentials->password;

        $StartDate = (new DateTime($StartDate, new DateTimeZone('Europe/Athens')))->format('Y-m-d\TH:i:s.vP');
        $EndDate = (new DateTime($EndDate, new DateTimeZone('Europe/Athens')))->format('Y-m-d\TH:i:s.vP');

        $players = [];
        $gameKeys = [];

        foreach ($user as $playerId) {
            $Usuario = new Usuario($playerId);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

            $players[] = [
                'playerId' => $UsuarioMandante->usumandanteId,
                'currency' => $UsuarioMandante->moneda,
            ];
        }

        foreach ($games as $game) {
            $gameKeys[] = [
                'gameKey' => $game,
            ];
        }

        $dataFreeSpin = json_encode([
            'requestId' => (string)$bonoElegido,
            'name' => "Free_Spin_Campaign",
            'bonusCode' => (string)$bonoElegido,
            'type' => "GIFT_SPIN",
            'count' => intval($roundsFree),
            'description' => "GIFT_SPIN_CAMPAIGN",
            'startDate' => $StartDate,
            'endDate' => $EndDate,
            'validityDate' => $EndDate,
            'games' => $gameKeys,
            'players' => $players,
            'bet' => [
                (string)$UsuarioMandante->moneda => $roundsValue
            ],
        ]);

        $authToken = base64_encode($username . ":" . $password);
        $response = $this->SendFreespins($URLBONUS, $dataFreeSpin, $authToken);
        syslog(LOG_WARNING, "EGT BONO DATA: " . $dataFreeSpin . " RESPONSE: " . $response);

        $response = json_decode($response);
        $responseCode = http_response_code();

        if ($responseCode != '200') {
            $return = array(
                "code" => 1,
                "response_code" => 0,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response,
                "response_message" => 'OK'
            );
        }
        return $return;
    }

    /**
     * Envía una solicitud para agregar giros gratis a través de una API externa.
     *
     * @param string $url          URL de la API.
     * @param string $dataFreeSpin Datos de la solicitud en formato JSON.
     * @param string $authToken    Token de autenticación en formato Base64.
     *
     * @return string Respuesta de la API.
     */
    public function SendFreespins($url, $dataFreeSpin, $authToken)
    {
        $curl = new CurlWrapper($url);

        $curl->setOptionsArray([
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $dataFreeSpin,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . $authToken,
                'Content-Type: application/json',
            ],
        ]);

        $response = $curl->execute();

        return $response;
    }
}
