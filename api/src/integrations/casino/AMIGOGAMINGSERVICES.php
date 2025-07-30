<?php

/**
 * Clase AMIGOGAMINGSERVICES
 *
 * Este archivo contiene la implementación de servicios relacionados con AMIGOGAMING.
 * Proporciona métodos para obtener juegos, agregar giros gratis y realizar solicitudes HTTP
 * utilizando proxies y control de tiempo entre solicitudes.
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
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase que implementa los servicios relacionados con AMIGOGAMING.
 * Proporciona métodos para gestionar juegos, giros gratis y solicitudes HTTP.
 */
class AMIGOGAMINGSERVICES
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
     * Obtiene un juego basado en los parámetros proporcionados.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID del mini juego.
     * @param boolean $isMobile      Indica si el juego es para móvil.
     * @param string  $usumandanteId ID del usuario mandante.
     * @param boolean $minigame      Indica si es un mini juego.
     *
     * @return object Respuesta con los datos del juego.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $UsuarioToken, $migameid, $isMobile = false, $usumandanteId = "", $minigame = false)
    {
        $Proveedor = new Proveedor("", "AMIGOGAMING");
        $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

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
                $UsuarioToken->setToken(md5($token));
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
                    $UsuarioToken->setToken(md5($UsuarioToken->createToken()));
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

            $launchParams = [
                'token' => strtoupper($UsuarioToken->getToken()),
                'symbol' => $gameid,
                'lobbyUrl' => $this->URLREDIRECTION,
            ];

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $STYLE_NAME = $credentials->STYLE_NAME;
            $URL = $credentials->URL;

            $predecode = urldecode(http_build_query($launchParams));
            $request = urlencode($predecode);
            if ($isMobile) {
                $array = array(
                    "error" => false,
                    "response" => $URL . 'stylename=' . $STYLE_NAME . "&key=" . $request
                );
            } else {
                $array = array(
                    "error" => false,
                    "response" => $URL . 'stylename=' . $STYLE_NAME . "&key=" . $request
                );
            }

            return json_decode(json_encode($array));
        }
    }


    /**
     * Agrega giros gratis a un usuario.
     *
     * @param string  $bonoId              ID del bono.
     * @param integer $roundsFree          Número de giros gratis.
     * @param float   $roundvalue          Valor de cada giro.
     * @param string  $StartDate           Fecha de inicio del bono.
     * @param string  $EndDate             Fecha de finalización del bono.
     * @param string  $user                Usuario al que se asignan los giros.
     * @param array   $games               Lista de juegos aplicables.
     * @param string  $aditionalIdentifier Identificador adicional del freeSpin.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoId, $roundsFree, $roundvalue, $StartDate, $EndDate, $user, $games, $aditionalIdentifier)
    {
        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $Proveedor = new Proveedor("", "AMIGOGAMING");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $Cureency = 'EUR';
        } else {
            $Cureency = $UsuarioMandante->moneda;
        }


        $array = array(
            "gameList" => array()
        );

        foreach ($games as $game) {
            $game = array(
                "gameId" => $game,
                "betValues" => array(
                    array(
                        "currency" => $Cureency,
                        "totalBet" => $roundvalue
                    )
                )
            );
            array_push($array["gameList"], $game);
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URL_BONUS = $credentials->URL_BONUS;
        $SECURE = $credentials->SECURE;
        $SECRET = $credentials->SECRET;

        $inse_format = $this->formatTo32Chars($bonoId . $aditionalIdentifier . $Usuario->usuarioId);

        $Params = [
            'secureLogin' => $SECURE,
            'bonusCode' => $inse_format,
            'startDate' => strtotime($StartDate),
            'expirationDate' => strtotime($EndDate),
            'rounds' => $roundsFree,
            'playerId' => $UsuarioMandante->usumandanteId,
            'currency' => $Cureency
        ];
        ksort($Params);
        $paramString = '';
        foreach ($Params as $key => $value) {
            if ( ! empty($value)) {
                $paramString .= $key . '=' . $value . '&';
            }
        }

        $hash = md5(rtrim($paramString, '&') . $SECRET);

        $Params2 = [
            'secureLogin' => $SECURE,
            'bonusCode' => $inse_format,
            'startDate' => strtotime($StartDate),
            'expirationDate' => strtotime($EndDate),
            'rounds' => $roundsFree,
            'playerId' => $UsuarioMandante->usumandanteId,
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

        $request = rtrim($paramString, '&');

        $patch = '/IntegrationService/v3/http/FreeRoundsBonusAPI/v2/bonus/player/create';
        
        $response = $this->SendFreespins(json_encode($array), $URL_BONUS . $patch . '?' . $request);
        
        syslog(LOG_WARNING, "AMIGOGAMING BONO DATA: " . $request . ' ' . json_encode($array) . " RESPONSE: " . json_encode($response));

        $responseCode = http_response_code();

        if ($response->error != 0) {
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
                "bonusId" => $bonoId
            );
        }
        return $return;
    }

    /**
     * Formatea un código a 32 caracteres.
     *
     * @param mixed $aditionalIdentifier Código a formatear.
     *
     * @return string Código formateado.
     */
    public function formatTo32Chars($aditionalIdentifier)
    {
        $aditionalIdentifier = is_float($aditionalIdentifier)
            ? sprintf('%.0f', $aditionalIdentifier)
            : (string)$aditionalIdentifier;

        $aditionalIdentifier .= "_";
        $aditionalIdentifier = str_pad($aditionalIdentifier, 32, "0", STR_PAD_RIGHT);

        return substr($aditionalIdentifier, 0, 32);
    }

    /**
     * Envía una solicitud HTTP para asignar giros gratis.
     *
     * @param string $data Datos de la solicitud en formato JSON.
     * @param string $url  URL del servicio.
     *
     * @return object Respuesta del servicio.
     */
    public function SendFreespins($data, $url)
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
        return json_decode($response);
    }
}
