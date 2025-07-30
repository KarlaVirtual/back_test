<?php

/**
 * Clase RUBYPLAYSERVICES
 *
 * Este archivo contiene la implementación de servicios relacionados con el proveedor RUBYPLAY.
 * Proporciona métodos para obtener juegos, gestionar rondas gratuitas y realizar solicitudes a la API del proveedor.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use DateTime;
use Exception;
use CurlWrapper;
use DateInterval;
use DateTimeZone;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase que implementa los servicios relacionados con el proveedor RUBYPLAY.
 * Proporciona métodos para interactuar con la API del proveedor, gestionar juegos
 * y campañas de rondas gratuitas.
 */
class RUBYPLAYSERVICES
{
    /**
     * Constructor de la clase RUBYPLAYSERVICES.
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
     * Obtiene la URL de lanzamiento de un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si es modo de prueba.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $LaunchId      ID de lanzamiento.
     * @param boolean $isMobile      Indica si es para móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $LaunchId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "RUBYPLAY");
            $Producto = new Producto('', $gameid, $Proveedor->getProveedorId());

            try {
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
            $token = $UsuarioToken->getToken();

            $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $array = array(
                "error" => false,
                "response" => $Credentials->LAUNCH_URL . 'launcher?gamename=' . $gameid . '&server_url=' . $Credentials->API_URL . '&operator=' . $Credentials->USER_NAME . '&lang=' . $lang . '&playerSession=' . $token
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
            // print_r($e);
        }
    }


    /**
     * Crea una campaña de rondas gratuitas.
     *
     * @param integer $bonoId            ID del bono.
     * @param string  $bonoName          Nombre del bono.
     * @param integer $round             Número de rondas.
     * @param float   $roundvalue        Valor de cada ronda.
     * @param string  $StartDate         Fecha de inicio.
     * @param string  $EndDate           Fecha de fin.
     * @param integer $freeRoundsMaxDays Máximo de días para las rondas gratuitas.
     * @param array   $ids               IDs de los usuarios.
     * @param array   $games             Juegos asociados.
     * @param boolean $IsCRM             Indica si es gestionado por CRM.
     *
     * @return array Respuesta con el estado de la campaña.
     */
    public function FreeRounds($bonoId, $bonoName, $round, $roundvalue, $StartDate, $EndDate, $freeRoundsMaxDays, $ids, $games, $IsCRM)
    {
        $EndDate = date("Y-m-d H:i:s", strtotime($EndDate) + (0.01 * 3600));
        $StartDate = date("Y-m-d H:i:s", strtotime($StartDate) + (0.01 * 3600));

        //Convert UTC+0
        $startDateProv = $this->convertTimeToUTCPlusZero($StartDate);
        $startDateProv = str_replace(" ", "T", $startDateProv);

        //Convert UTC+0
        $endtDateProv = $this->convertTimeToUTCPlusZero($EndDate);
        $endtDateProv = str_replace(" ", "T", $endtDateProv);

        //Diferencia de Días
        // Convertir las fechas a objetos DateTime
        $inicio = new DateTime($startDateProv);
        $final = new DateTime($endtDateProv);

        // Calcular la diferencia entre las fechas
        $diferencia = $inicio->diff($final);

        // Obtener la diferencia en días
        $freeRoundsMaxDays = $diferencia->days;

        $arrayIds = array();

        foreach ($ids as $key => $value) {
            $Usuario = new Usuario($value);

            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

            array_push($arrayIds, $UsuarioMandante->usumandanteId);
        }

        $Proveedor = new Proveedor("", "RUBYPLAY");
        $Producto = new Producto('', $games[0], $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $url = $Credentials->API_URL;
        $patch = '/api/v1/freeround/campaign';

        $inicio = new DateTime('now', new DateTimeZone('UTC'));
        $intervalo = new DateInterval('P' . $freeRoundsMaxDays . 'D');
        $fin = clone $inicio;
        $fin->add($intervalo);
        $startDateTime = $inicio->format('Y-m-d\TH:i:s');
        $endDateTime = $fin->format('Y-m-d\TH:i:s');

        $array = [
            "name" => $bonoName . $bonoId,
            "gameId" => $games[0],
            "startDateTime" => $startDateTime,
            "endDateTime" => $endDateTime,
            "expiresInDays" => $freeRoundsMaxDays,
            "bet" => floatval($roundvalue),
            "currencyCode" => $Usuario->moneda,
            "betNumber" => intval($round),
            "strategy" => 'PLAYER_LIST',
            "strategyData" => [
                "playerIds" => $arrayIds
            ],
        ];

        $response = $this->SendFreespins(json_encode($array), $url . $patch, $Credentials->USER_NAME, $Credentials->PASSWORD);
        syslog(LOG_WARNING, "RUBYPLAY BONO DATA: " . json_encode($array) . " RESPONSE: " . json_encode($response));

        if ($response->status != 'ONGOING') {
            $return = array(
                "code" => 1,
                "response_code" => $response->status,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response->status,
                "response_message" => 'OK',
            );
        }

        return $return;
    }

    /**
     * Envía una solicitud de rondas gratuitas a la API del proveedor.
     *
     * @param string $data     Datos de la solicitud en formato JSON.
     * @param string $url      URL de la API.
     * @param string $userName Nombre de usuario para autenticación.
     * @param string $password Contraseña para autenticación.
     *
     * @return object Respuesta de la API.
     */
    public function SendFreespins($data, $url, $userName, $password)
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
                'Authorization: Basic ' . base64_encode($userName . ':' . $password)
            ),
        ));

        $response = $curl->execute();
        return json_decode($response);
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
