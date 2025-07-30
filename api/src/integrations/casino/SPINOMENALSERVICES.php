<?php

/**
 * Clase SPINOMENALSERVICES
 *
 * Este archivo contiene la implementación de servicios relacionados con el proveedor SPINOMENAL.
 * Proporciona métodos para gestionar juegos, lanzar URLs, asignar giros gratis y obtener la IP del cliente.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\SubproveedorMandantePais;
use \CurlWrapper;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;
use Department;
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
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase que implementa los servicios relacionados con el proveedor SPINOMENAL.
 * Proporciona métodos para gestionar juegos, asignar giros gratis y más.
 */
class SPINOMENALSERVICES
{
    /**
     * URL de redirección para el proveedor.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * URL para depósitos en el proveedor.
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
     * Obtiene la URL para lanzar un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si es modo demo.
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en miniatura.
     * @param boolean $isMobile      Indica si es para móvil.
     * @param string  $usumandanteId ID del usuario mandante.
     * @param boolean $minigame      Indica si es un minijuego.
     *
     * @return object Respuesta con la URL del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $UsuarioToken, $migameid, $isMobile = false, $usumandanteId = "", $minigame = false)
    {
        $Proveedor = new Proveedor("", "SPINOMENAL");
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
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $credentials = json_decode($SubproveedorMandantePais->getCredentials());

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
                    $this->URLDEPOSIT = $Mandante->baseUrl . "gestion/deposito";
                }

                date_default_timezone_set('UTC');
                $timestamp = date("YmdHis");
                $signature = md5($timestamp . $UsuarioMandante->usumandanteId . $credentials->PK);

                if ($lang == "es") {
                    $lang = $lang . "_ES";
                } elseif ($lang == "en") {
                    $lang = $lang . "_EN";
                } elseif ($lang == "pt") {
                    $lang = $lang . "_PT";
                }

                $launchParams = array(
                    "PartnerId" => $credentials->partnerId,
                    "IsDemoMode" => false,
                    "LangCode" => $lang,
                    "GameCode" => $gameid,
                    "GameToken" => $UsuarioToken->getToken(),
                    "UrlsInput" => array(
                        "HomeUrl" => $this->URLREDIRECTION,
                        "CashierUrl" => $this->URLDEPOSIT,
                    ),
                    "PlayerInput" => array(
                        "PlayerId" => $UsuarioMandante->usumandanteId,
                        "Currency" => $Usuario->moneda,
                        "TypeId" => 0,
                        "TimeStamp" => $timestamp,
                        "Sig" => $signature,
                        "SessionOptions" => array(
                            "DisableBuyFeature" => false
                        )
                    )
                );

                $path = $credentials->URL . '/GameLauncher/LaunchGame';
                $response = $this->LaunchUrl(json_encode($launchParams), $path);
                $response = json_decode($response);
                $array = array(
                    "error" => false,
                    "response" => $response->Url
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
     * Lanza una URL mediante una solicitud POST.
     *
     * @param string $data Datos en formato JSON.
     * @param string $url  URL a la que se enviará la solicitud.
     *
     * @return string Respuesta de la solicitud.
     */
    public function LaunchUrl($data, $url)
    {
        $curl = curl_init();
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
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * Asigna giros gratis a un usuario.
     *
     * @param string  $bonoElegido Código del bono.
     * @param integer $roundsFree  Cantidad de giros gratis.
     * @param float   $roundsValue Valor de cada giro.
     * @param string  $StartDate   Fecha de inicio.
     * @param string  $EndDate     Fecha de finalización.
     * @param string  $user        Usuario al que se asignan los giros.
     * @param array   $games       Juegos aplicables.
     * @param string  $aditionalIdentifier   ID del bono del usuario.
     * @param string  $NombreBono  Nombre del bono.
     *
     * @return array Respuesta con el estado de la asignación.
     */
    public function AddFreespins($bonoElegido, $roundsFree, $roundsValue, $StartDate, $EndDate, $user, $games, $aditionalIdentifier, $NombreBono)
    {
        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $Proveedor = new Proveedor("", "SPINOMENAL");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        // Convertir las fechas a objetos DateTime para sacar la diferencia en dias.
        $inicio = new DateTime($StartDate);
        $final = new DateTime($EndDate);
        $diferencia = $inicio->diff($final);
        $diffDays = $diferencia->days;

        $inicio = new DateTime('now', new DateTimeZone('UTC'));
        $inicio->add(new DateInterval('PT1M'));
        $intervalo = new DateInterval('P' . $diffDays . 'D');
        $fin = clone $inicio;
        $fin->add($intervalo);

        // Formatear las fechas para SPINOMENAL en el formato requerido
        $startDateUTC = $inicio->format('YmdHis');
        $endtDateUTC = $fin->format('YmdHis');

        // Obtener el timestamp actual en UTC+0
        $utcTimezone = new DateTimeZone('UTC');
        $timestamp = new DateTime('now', $utcTimezone);
        $Timestamp = $timestamp->format("YmdHis");
        
        $aditionalIdentifier = $bonoElegido . $aditionalIdentifier . $Usuario->usuarioId;
        $signature = md5($Timestamp . $credentials->partnerId . $aditionalIdentifier . $credentials->PK);
        $stake = floatval($roundsValue);
        $stake = number_format($roundsValue, 2);

        $Params = [
            'FreeRoundsName' => $NombreBono,
            'FreeRoundsCode' => $bonoElegido,
            'FreeRoundsAmount' => intval($roundsFree),
            'PartnerId' => $credentials->partnerId,
            'Sig' => $signature,
            'TimeStamp' => $Timestamp,
            'PlayerId' => $UsuarioMandante->usumandanteId,
            'Currency' => $UsuarioMandante->moneda,
            'AssignCode' => $aditionalIdentifier,
            "GamesSettings" => [
                [
                    "GameCode" => $games[0],
                    "Stake" => $stake,
                ]
            ],
            'StartDate' => $startDateUTC,
            'ExpireDate' => $endtDateUTC
        ];
        $path = '/FreeRounds/Create';
        
        $response = $this->SendFreespins(json_encode($Params), $credentials->URL . $path);
        syslog(LOG_WARNING, "SPINOMENAL BONO DATA: " . json_encode($Params) . " RESPONSE: " . $response);
        $response = json_decode($response);

        if ($response->ErrorCode != '0') {
            $return = array(
                "code" => 1,
                "response_code" => $response->FreeRoundsId,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response->FreeRoundsId,
                "response_message" => 'OK'
            );
        }
        return $return;
    }

    /**
     * Envía una solicitud para asignar giros gratis.
     *
     * @param string $data Datos en formato JSON.
     * @param string $url  URL del servicio.
     *
     * @return string Respuesta de la solicitud.
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
        //Ejecutarlasolicitud
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