<?php

/**
 * Clase que proporciona servicios de integración con el proveedor GALAXSYS.
 *
 * Este archivo contiene métodos para gestionar juegos, agregar giros gratis y enviar solicitudes
 * relacionadas con el proveedor GALAXSYS. Utiliza diversas clases DTO y DAO para interactuar
 * con la base de datos y manejar credenciales y tokens de usuario.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\mysql\UsuarioTokenMySqlDAO;
use \CurlWrapper;
use DateTime;
use DateTimeZone;
use Exception;

/**
 * Clase principal para manejar servicios de GALAXSYS.
 */
class GALAXSYSSERVICES
{
    /**
     * Constructor de la clase GALAXSYSSERVICES.
     */
    public function __construct()
    {
    }

    /**
     * Obtiene la URL de redirección para un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $LaunchId      ID del lanzamiento.
     * @param boolean $isMobile      Indica si el dispositivo es móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $LaunchId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "GALAXSYS");
            $Producto = new Producto('', $gameid, $Proveedor->proveedorId);

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                $UsuarioToken->setProductoId($Producto->productoId);
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
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
                $this->URLREDIRECTION = str_replace('https://', '', $this->URLREDIRECTION);
            }

            $deviceType = "1";
            if ($isMobile == true) {
                $deviceType = "2"; //Mobile
            } else {
                $deviceType = $deviceType; //Desktop
            }

            $playMode = "real";
            if ($play_for_fun == true) {
                $playMode = "demo"; //"demo";
            } else {
                $playMode = $playMode; //real;
            }

            $array = array(
                "error" => false,
                "response" => $credentials->URL . 'gameid=' . $gameid . '&playMode=' . $playMode . '&token=' . $UsuarioToken->getToken() . '&displayType=1' . '&deviceType=' . $deviceType . '&lang=' . $lang . '&operatorId=' . $credentials->OPERATOR_ID . '&mainDomain=' . $this->URLREDIRECTION
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Agrega giros gratis a los usuarios para un juego específico.
     *
     * @param string  $bonoId              ID del bono.
     * @param integer $roundsFree          Número de giros gratis.
     * @param float   $roundsValue         Valor de cada giro.
     * @param string  $StartDate           Fecha de inicio de la campaña.
     * @param string  $EndDate             Fecha de fin de la campaña.
     * @param array   $users               Lista de IDs de usuarios.
     * @param array   $games               Lista de códigos de juegos.
     * @param string  $aditionalIdentifier Identificador adicional del bono.
     * @param string  $bonoName            Nombre del bono.
     *
     * @return array Respuesta con el estado de la operación.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function AddFreespins($bonoId, $roundsFree, $roundsValue, $StartDate, $EndDate, $users, $games, $aditionalIdentifier, $bonoName)
    {
        $Usuario = new Usuario($users[0]);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Proveedor = new Proveedor("", "GALAXSYS");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $playerIds = [];
        foreach ($users as $playerId) {
            $Usuario = new Usuario($playerId);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            $playerIds[] = strval($UsuarioMandante->usumandanteId);
        }

        $Data = array(
            'requestId' => $bonoId,
            'name' => $bonoId . '_' . $aditionalIdentifier . '_FS' . $Usuario->usuarioId,
            'betCount' => intval($roundsFree),
            'amount' => doubleval($roundsValue),
            'currency' => $Usuario->moneda,
            'bonusType' => 1,
            'winType' => 1,
        );

        $signature = hash_hmac('sha256', json_encode($Data), $credentials->SECRET_KEY);
        $Path = 'api/Bonus/CreateBonus';

        $Response = $this->SendFreespins($credentials->URLFS . $Path, json_encode($Data), $signature, $credentials->OPERATOR_ID);
        syslog(LOG_WARNING, "GALAXSYS BONO DATA CREATEBONUS" . json_encode($Data) . " RESPONSE" . $Response);

        $Result = json_decode($Response);
        $BonusId = $Result->data->bonus->id;

        $timezone = new DateTimeZone('UTC');
        $dataTime = new DateTime('now', $timezone);
        $dataTime->modify('+1 minutes');

        $endDate = new DateTime($EndDate, $timezone);
        $starDate = $dataTime->format('Y-m-d\TH:i:s.v\Z');
        $End_Date = $endDate->format('Y-m-d\TH:i:s.v\Z');

        $timestamp = microtime(true); // flotante, en segundos + microsegundos
        $hash = hash('crc32', $timestamp . random_int(0, 9999)); // hash corto
        $id = substr($hash, 0, 6);

        $dataFreeSpin = array(
            'requestId' => $bonoId . $id,
            'bonusId' => $BonusId,
            'playerIds' => $playerIds,
            'gameCodes' => $games,
            'campaignName' => $bonoId . '_' . $aditionalIdentifier . '_FS' . $Usuario->usuarioId,
            'startDate' => $starDate,
            'endDate' => $End_Date
        );

        $Signature = hash_hmac('sha256', json_encode($dataFreeSpin), $credentials->SECRET_KEY);

        $path = 'api/Bonus/CreateCampaign';
        $response = $this->SendFreespins($credentials->URLFS . $path, json_encode($dataFreeSpin), $Signature, $credentials->OPERATOR_ID);
        syslog(LOG_WARNING, "GALAXSYS BONO DATA" . json_encode($dataFreeSpin) . " RESPONSE" . $response);

        $response = json_decode($response);

        if ($response->data->errorCode == '0') {
            $return = array(
                "code" => 0,
                "response_code" => $response,
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
     * Envía una solicitud POST para registrar giros gratis.
     *
     * @param string $url        URL del servicio.
     * @param string $Data       Datos de la solicitud en formato JSON.
     * @param string $Hash       Firma HMAC para la autenticación.
     * @param string $OperatorId ID del operador.
     *
     * @return string Respuesta del servicio.
     */
    public function SendFreespins($url, $Data, $Hash, $OperatorId)
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
            CURLOPT_POSTFIELDS => $Data,
            CURLOPT_HTTPHEADER => array(
                'operator-id: ' . $OperatorId,
                'x-signature: ' . $Hash,
                'Content-Type: application/json',
            ),
        ));

        $response = $curl->execute();
        return $response;
    }

}

