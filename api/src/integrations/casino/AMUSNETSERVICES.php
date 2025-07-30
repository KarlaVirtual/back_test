<?php

/**
 * Clase AMUSNETSERVICES
 *
 * Esta clase proporciona servicios relacionados con la integración de AMUSNET,
 * incluyendo la obtención de juegos, la asignación de giros gratis y la gestión de solicitudes HTTP.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use DateTime;
use Exception;
use Throwable;
use \CurlWrapper;
use DateInterval;
use DateTimeZone;
use Backend\dto\Pais;
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
 * Clase AMUSNETSERVICES
 *
 * Proporciona servicios relacionados con la integración de AMUSNET,
 * incluyendo la obtención de juegos, la asignación de giros gratis
 * y la gestión de solicitudes HTTP.
 */
class AMUSNETSERVICES
{
    /**
     * Constructor de la clase AMUSNETSERVICES.
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
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es para diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $productoId    ID del producto.
     * @param boolean $isMobile      Indica si el cliente es móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            $Proveedor = new Proveedor("", "AMUSNET");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId, "", "", $productoId);
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
            $Pais = new Pais($UsuarioMandante->paisId);

            if ($Mandante->baseUrl != '') {
                $URLREDIRECTION = $Mandante->baseUrl . "casino";
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $URL = $credentials->URL;
            $PORTAL_CODE = $credentials->PORTAL_CODE;

            $url = $URL . "?defenceCode=" . $UsuarioToken->getToken() . "&playerId=" . $UsuarioMandante->usumandanteId . "&portalCode=" . $PORTAL_CODE . "&screenName=" . $UsuarioMandante->nombres . "&language=" . strtolower($lang) . "&country=" . strtolower($Pais->iso) . "&gameId=" . $gameid . "&client=desktop" . "&closeurl=" . $URLREDIRECTION;

            $array = array(
                "error" => false,
                "response" => $url
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Añadir FreeSpins a un usuario.
     * 
     * @param string $bonoId              ID del bono.
     * @param string $Name                Nombre del bono.
     * @param int    $roundsFree          Número de rondas gratis.
     * @param float  $roundvalue          Valor de cada ronda.
     * @param string $StartDate           Fecha de inicio del bono.
     * @param string $EndDate             Fecha de finalización del bono.
     * @param array  $ids                 Array de IDs de usuarios a los que se les otorgará el bono.
     * @param array  $games               Array de IDs de juegos asociados al bono.
     * @param string $aditionalIdentifier Identificador adicional del freeSpin.
     * 
     * @return array Retorna un array con el resultado de la operación.
     * @throws Throwable Lanza una excepción en caso de error.
     */
    public function AddFreespins($bonoId, $Name, $roundsFree, $roundvalue, $StartDate, $EndDate, $ids, $games, $aditionalIdentifier) :array {
        try {
            $Proveedor = new Proveedor("", "AMUSNET");
            $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

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
            $startDateUTC = $inicio->format('Y-m-d H:i:s');
            $endtDateUTC = $fin->format('Y-m-d H:i:s');

            $Usuario = new Usuario($ids[0]);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

            $countUsers = count($ids);
            $totalFreeSpins = $roundsFree * $countUsers;

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $URL_BONUS = $credentials->URL_BONUS;
            $URL_CONFIGURATIONS = $credentials->URL_CONFIGURATIONS;
            $BONUS_USERNAME = $credentials->BONUS_USERNAME;
            $BONUS_PASSWORD = $credentials->BONUS_PASSWORD;
            $PORTAL_NAME = $credentials->PORTAL_NAME;
            $PORTAL_CODE = $credentials->PORTAL_CODE;
            $SERVER = $credentials->SERVER;

            $path = "/gamesConfiguration";

            $arrayGetGames = [
                "auth" => [
                    "username" => $BONUS_USERNAME,
                    "password" => $BONUS_PASSWORD,
                ],
                "payload" => [
                    "server" => $SERVER,
                    "gameId" => (int)$games[0],
                    "portalCode" => $PORTAL_CODE
                ]
            ];

            $response = $this->AMUSNETFREESPINRequest($arrayGetGames, $path, $URL_CONFIGURATIONS);

            syslog(LOG_WARNING, "AMUSNET GET GAMES DATA: " . json_encode($arrayGetGames) . " | RESPONSE: " . json_encode($response));

            $response = json_decode($response);

            $betOption = $response->payload->betOptions[0];
            $denomination = $response->payload->denominations[0];
            $lines = $response->payload->lines;

            $propiedades = [
                $PORTAL_CODE => [
                    "denomination" => $denomination,
                    "betOption" => $betOption,
                    "lines" => $lines
                ]
            ];

            //Data para creación de oferta
            $array = [
                "auth" => [
                    "username" => $BONUS_USERNAME,
                    "password" => $BONUS_PASSWORD,
                ],
                "payload" => [
                    "portalName" => $PORTAL_NAME,
                    "startTime" => $startDateUTC,
                    "endTime" => $endtDateUTC,
                    "gameId" => (int)$games[0],
                    "totalFreeSpins" => $totalFreeSpins,
                    "maxFreeSpinsPerPlayer" => $roundsFree,
                    "configuration" => [],
                    "description" => $Name
                ]
            ];
            $array['payload']['configuration'] = $propiedades;

            $path = "/create_campaign";

            //Conexión crear Campaña
            $response = $this->AMUSNETFREESPINRequest($array, $path, $URL_BONUS);

            syslog(LOG_WARNING, "AMUSNET CREATE BONO DATA: " . json_encode($array) . "| RESPONSE: " . json_encode($response));

            $response = json_decode($response, true);

            $campaign = $response['payload']['campaignUniqueCode'];

            // Otorgar FreeSpin
            if ($response->errorCode == 0) {
                $usuarioMandanteIds = [];
                foreach ($ids as $value) {
                    $Usuario = new Usuario($value);
                    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                    $usuarioMandanteIds[] = $UsuarioMandante->usumandanteId;
                }

                $arrayAward = 
                [
                    "auth" => [
                        "username" => $BONUS_USERNAME,
                        "password" => $BONUS_PASSWORD,
                    ],
                    "payload" => [
                        "identification" => $bonoId . "_" . $UsuarioMandante->usumandanteId,
                        "playerId" => implode(',', $usuarioMandanteIds),
                        "portalCode" => $PORTAL_CODE,
                        "amount" => $roundsFree,
                        "expirationTime" => $EndDate,
                        "campaignUniqueCode" => $campaign
                    ]
                ];

                $path = "/award_freespins";

                //Conexión Otorgar FreeSpin
                $response = $this->AMUSNETFREESPINRequest($arrayAward, $path, $URL_BONUS);

                syslog(LOG_WARNING, "AMUSNET AWARD BONO DATA: " . json_encode($arrayAward) . " | RESPONSE: " . json_encode($response));
            };

            $response = json_decode($response);

            if ($response->errorCode != 0) {
                $return = array(
                    "code" => 1,
                    "response_code" => $response->errorCode,
                    "response_message" => 'Error'
                );
            } else {
                $return = array(
                    "code" => 0,
                    "response_code" => $response->errorCode,
                    "response_message" => 'OK',
                    "bonusId" => $bonoId
                );
            }

            return $return;

        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Realiza una solicitud HTTP para la integración de AMUSNET.
     *
     * @param array  $array     Datos de la solicitud.
     * @param string $path      Ruta del endpoint.
     * @param string $URL_BONUS URL base del servicio.
     *
     * @return string Respuesta de la solicitud.
     */
    public function AMUSNETFREESPINRequest($array, $path, $URL_BONUS)
    {
        $curl = new CurlWrapper($URL_BONUS . $path);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL_BONUS . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($array),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
            ),
        ));

        //Ejecutarlasolicitud
        $response = $curl->execute();

        return ($response);
    }
    /**
     * Convierte una fecha y hora a UTC+0.
     *
     * @param string $fechaHora Fecha y hora en formato local.
     *
     * @return string Fecha y hora en formato UTC+0.
     */
    function convertTimeToUTCPlusZero($fechaHora)
    {
        $timestamp = strtotime($fechaHora); // Convierte la fecha y hora a un timestamp
        $horaUTCPlusZero = gmdate("H:i:s", $timestamp); // Obtiene la hora en UTC+0
        $fechaHoraUTCPlusZero = gmdate("Y-m-d", $timestamp) . " " . $horaUTCPlusZero;
        return $fechaHoraUTCPlusZero;
    }
}
