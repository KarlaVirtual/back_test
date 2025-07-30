<?php

/**
 * Clase BRAGGSERVICES
 *
 * Esta clase proporciona servicios relacionados con la integración de BRAGG, incluyendo
 * la obtención de juegos, la asignación de giros gratis y la comunicación con servicios externos.
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
use \CurlWrapper;
use DateTimeZone;
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
 * Clase BRAGGSERVICES
 *
 * Proporciona métodos para la integración con los servicios de BRAGG,
 * incluyendo la obtención de juegos y la asignación de giros gratis.
 */
class BRAGGSERVICES
{
    /**
     * Constructor de la clase BRAGGSERVICES.
     *
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
     * Obtiene la URL de un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $productoId    Opcional ID del producto.
     * @param string  $usumandanteId Opcional ID del usuario mandante.
     *
     * @return object Respuesta con la URL del juego.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId = "", $usumandanteId = "")
    {
        try {
            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => '' . $gameid . "/open?token=" . strtolower($lang),
                );
            } else {
                $Proveedor = new Proveedor("", "BRAGG");
                $Producto = new Producto('', $gameid, $Proveedor->proveedorId);

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

                $lobbyUrl = '';
                if ($Mandante->baseUrl != '') {
                    $lobbyUrl = $Mandante->baseUrl . "new-casino";
                }

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $array = array(
                    "error" => false,
                    "response" => $Credentials->URL . $gameid . "/open?token=" . $UsuarioToken->getToken() . "&languageCode=" . strtolower($lang) . "&playMode=REAL&lobbyUrl=" . $lobbyUrl,
                );
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Asigna giros gratis a un usuario.
     *
     * @param string  $aditionalIdentifier ID del bono del usuario.
     * @param integer $roundsFree          Número de giros gratis.
     * @param float   $roundsValue         Valor de cada giro.
     * @param string  $StartDate           Fecha de inicio en formato ISO.
     * @param string  $EndDate             Fecha de fin en formato ISO.
     * @param string  $user                Usuario al que se asignan los giros.
     * @param array   $games               Lista de juegos aplicables.
     * @param string  $bonoElegido         Id del bono que se va a otorgar
     *
     * @return array Respuesta con el resultado de la operación.
     */
    public function AddFreespins($aditionalIdentifier, $roundsFree, $roundsValue, $StartDate, $EndDate, $user, $games, $bonoElegido)
    {
        $dateStart = new DateTime($StartDate, new DateTimeZone('UTC'));
        $dateEnd = new DateTime($EndDate, new DateTimeZone('UTC'));

        $isoStartDate = $dateStart->format('Y-m-d\TH:i:s\Z');
        $isoEndDate = $dateEnd->format('Y-m-d\TH:i:s\Z');

        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $game = $games[0];

        $Proveedor = new Proveedor("", "BRAGG");
        $Producto = new Producto('', $games[0], $Proveedor->proveedorId);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $Params = array(
            "playerId" => $UsuarioMandante->usumandanteId,
            "externalId" => $bonoElegido . $aditionalIdentifier . $Usuario->usuarioId,
            "startDate" => $isoStartDate,
            "endDate" => $isoEndDate,
            "game" => $game,
            "type" => "NUM_ROUNDS",
            "roundsInAward" => intval($roundsFree),
            "betAmount" => floatval($roundsValue),
        );

        $path = 'direct/award';
        $response = $this->SendFreespins(json_encode($Params), $Credentials->URL_BONUS . $path, $Credentials->USERNAME, $Credentials->PASSWORD);
        syslog(LOG_WARNING, "BRAGG BONO DATA: " . json_encode($Params) . " RESPONSE: " . $response);

        $response = json_decode($response);

        if (isset($response->betAmount)) {
            $return = array(
                "code" => 0,
                "message" => 'Success',
                "response_message" => 'OK'
            );
        } else {
            $return = array(
                "code" => 1,
                "response_code" => 0,
                "response_message" => 'Error'
            );
        }
        return $return;
    }

    /**
     * Envía una solicitud para asignar giros gratis.
     *
     * @param string $data     Datos de la solicitud en formato JSON.
     * @param string $url      URL del servicio externo.
     * @param string $username Nombre de usuario para autenticación.
     * @param string $password Contraseña para autenticación.
     *
     * @return string Respuesta del servicio externo.
     */
    public function SendFreespins($data, $url, $username, $password)
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
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($username . ":" . $password)
            ),
        ));

        $response = $curl->execute();
        return $response;
    }
}
