<?php

/**
 * Clase para la integración con los servicios de bonos de Red Rake.
 *
 * Proporciona métodos para otorgar, verificar, cancelar y obtener información
 * sobre bonos de giros gratis (Free Rounds Bonus) en la plataforma de Red Rake.
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
use Throwable;
use \CurlWrapper;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase REDRAKESERVICESBONUS
 *
 * Esta clase proporciona métodos para interactuar con los servicios de bonos
 * de giros gratis (Free Rounds Bonus) ofrecidos por Red Rake.
 * Incluye funcionalidades para otorgar, verificar, cancelar y obtener información
 * sobre los bonos.
 */
class REDRAKESERVICESBONUS
{
    /**
     * Constructor de la clase.
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
     * Método para otorgar un bono de rondas gratis.
     * 
     * @param string $extBonusId          ID externo del bono.
     * @param int    $rounds              Número de rondas.
     * @param float  $roundValue          Valor de cada ronda.
     * @param string $activationDate      Fecha de activación del bono.
     * @param string $expirationDate      Fecha de expiración del bono.
     * @param string $freeRoundsMaxDays   Días máximos para rondas gratis.
     * @param array  $awardedUsersIds     IDs de los usuarios a los que se les otorga el bono.
     * @param array  $games               Juegos asociados al bono.
     * @param int    $aditionalIdentifier Identificador adicional para los free spin.
     * 
     * @return array Resultado de la operación.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function awardFRBonus($extBonusId, $rounds, $roundValue, $activationDate, $expirationDate, $freeRoundsMaxDays = "", $awardedUsersIds, $games, $aditionalIdentifier) :array {
        try {
            $Usuario = new Usuario($awardedUsersIds[0]);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

            $Proveedor = new Proveedor("", "REDRAKE");
            $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $arrayIds = array();

            foreach ($awardedUsersIds as $key => $value) {
                $Usuario = new Usuario($value);
                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                $users = "Usuario" . $UsuarioMandante->usumandanteId;
                array_push($arrayIds, $users);
            }

            $activationTimestamp = strtotime($activationDate);
            $expirationTimestamp = strtotime($expirationDate);
            $timeDiffInSeconds = $expirationTimestamp - $activationTimestamp;

            // Convierte la diferencia de tiempo a días
            $timeDiffInDays = floor($timeDiffInSeconds / (60 * 60 * 24));

            // Asigna el resultado a la variable $freeRoundsMaxDays
            $freeRoundsMaxDays = $timeDiffInDays;

            $array = array(
                "id" => "awardFRBonus",
                "user" => $credentials->OPERATOR,
                "password" => $credentials->PASSWORD,
                "params" => array(
                    "extBonusId" => $extBonusId . "B" . $aditionalIdentifier . $Usuario->usuarioId,
                    "rounds" => floatval($rounds),
                    "roundValue" => floatval($roundValue),
                    "activationDate" => $activationTimestamp,
                    "expirationDate" => $expirationTimestamp,
                    "freeRoundsMaxDays" => $freeRoundsMaxDays,
                    "awardedUsersIds" => $arrayIds,
                    "games" => $games,
                    "jurisdiction" => intval($credentials->JURISDICTION)
                )
            );

            $response = $this->connection(json_encode($array), $credentials->API_URL, $credentials->OPERATOR, $credentials->PASSWORD);
            syslog(LOG_WARNING, "REDRAKE BONO DATA: " . json_encode($array) . ' RESPONSE: ' . $response);
            
            $response = json_decode($response);
            $respon = $this->resultCodeConvert($response->status);

            if ($respon["code"] != "OK") {
                $return = array(
                    "code" => 1,
                    "response_code" => $respon["code"],
                    "response_message" => $respon["message"]
                );
            } else {
                $return = array(
                    "code" => 0,
                    "response_code" => $respon["code"],
                    "response_message" => $respon["message"],
                    "bonusId" => $response->data->bonusId
                );
            }
            
            return $return;

        } catch (Throwable $th) {
            throw $th;
        }
    }


    public function resultCodeConvert($code)
    {
        switch ($code) {
            case "OK":
                $codeproveedor = "OK";
                $message = "OK, The operation has been properly executed";
                break;

            case 1:
                $codeproveedor = 1;
                $message = "ERROR_PARSE_ID - Error found when parsing the message document";
                break;

            case 2:
                $codeproveedor = 2;
                $message = "ERROR_PARSE_PARAMS";
                break;

            case 3:
                $codeproveedor = 3;
                $message = "ERROR_UNKNOWN_FUNCTION";
                break;

            case 4:
                $codeproveedor = 4;
                $message = "ERROR_SUBFUNCTION";
                break;

            case 5:
                $codeproveedor = 5;
                $message = "ERROR_NO_PARAMS";
                break;

            case 6:
                $codeproveedor = 6;
                $message = "ERROR_PARAM_NOT_VALID";
                break;

            case 7:
                $codeproveedor = 7;
                $message = "ERROR_INVALID_PARAMS";
                break;

            case 8:
                $codeproveedor = 8;
                $message = "ERROR_UNKNOWN_USER";
                break;

            case 9:
                $codeproveedor = 9;
                $message = "ERROR_UNKNOWN_GAME";
                break;

            case 10:
                $codeproveedor = 10;
                $message = "ERROR_UNKNOWN";
                break;

            case 11:
                $codeproveedor = 11;
                $message = "ERROR_UNAUTHORIZED";
                break;

            case 12:
                $codeproveedor = 12;
                $message = "ERROR_REPEATED_EXTBONUSID";
                break;

            case 13:
                $codeproveedor = 13;
                $message = "ERROR_PARAM_EXTBONUSID_NOT_VALID";
                break;
        }

        return $array = array(
            "code" => $codeproveedor,
            "message" => $message
        );
    }

    /**
     * Verifica el estado de un bono de giros gratis.
     *
     * @param string  $id           ID de la operación.
     * @param string  $user         Usuario para la autenticación.
     * @param string  $password     Contraseña para la autenticación.
     * @param string  $extBonusId   ID externo del bono.
     * @param integer $jurisdiction Jurisdicción del bono.
     *
     * @return mixed Respuesta del servicio.
     */
    public function checkFRBonus($id, $user, $password, $extBonusId, $jurisdiction)
    {
        $array = array(
            "id" => $id,
            "user" => $user,
            "password" => $password,
            "params" => array(
                "extBonusId" => $extBonusId,
                "jurisdiction" => intval($jurisdiction)
            )
        );

        $response = $this->connection(json_encode($array));

        return $response;
    }

    /**
     * Cancela un bono de giros gratis.
     *
     * @param string  $id           ID de la operación.
     * @param string  $user         Usuario para la autenticación.
     * @param string  $password     Contraseña para la autenticación.
     * @param string  $extBonusId   ID externo del bono.
     * @param integer $jurisdiction Jurisdicción del bono.
     *
     * @return mixed Respuesta del servicio.
     */
    public function cancelFRBonus($id, $user, $password, $extBonusId, $jurisdiction)
    {
        $array = array(
            "id" => $id,
            "user" => $user,
            "password" => $password,
            "params" => array(
                "extBonusId" => $extBonusId,
                "jurisdiction" => intval($jurisdiction)
            )
        );

        $response = $this->connection(json_encode($array));

        return $response;
    }

    /**
     * Obtiene información sobre un bono de giros gratis.
     *
     * @param string  $id           ID de la operación.
     * @param string  $user         Usuario para la autenticación.
     * @param string  $password     Contraseña para la autenticación.
     * @param string  $extBonusId   ID externo del bono.
     * @param integer $jurisdiction Jurisdicción del bono.
     * @param integer $limit        Límite de resultados.
     * @param integer $offset       Desplazamiento de resultados.
     *
     * @return mixed Respuesta del servicio.
     */
    public function getInfoFRBonus($id, $user, $password, $extBonusId, $jurisdiction, $limit, $offset)
    {
        $array = array(
            "id" => $id,
            "user" => $user,
            "password" => $password,
            "params" => array(
                "extBonusId" => $extBonusId,
                "jurisdiction" => intval($jurisdiction),
                "limit" => $limit,
                "offset" => $offset
            )
        );

        $response = $this->connection(json_encode($array));

        return $response;
    }

    /**
     * Obtiene los bonos activos de un usuario.
     *
     * @param string  $id           ID de la operación.
     * @param string  $user         Usuario para la autenticación.
     * @param string  $password     Contraseña para la autenticación.
     * @param integer $userId       ID del usuario.
     * @param integer $jurisdiction Jurisdicción del bono.
     *
     * @return mixed Respuesta del servicio.
     */
    public function getUserActiveFRBonus($id, $user, $password, $userId, $jurisdiction)
    {
        $array = array(
            "id" => $id,
            "user" => $user,
            "password" => $password,
            "params" => array(
                "userId" => $userId,
                "jurisdiction" => intval($jurisdiction),
            )
        );

        $response = $this->connection(json_encode($array));

        return $response;
    }

    /**
     * Obtiene información sobre los bonos de un usuario.
     *
     * @param string  $id           ID de la operación.
     * @param string  $user         Usuario para la autenticación.
     * @param string  $password     Contraseña para la autenticación.
     * @param integer $userId       ID del usuario.
     * @param integer $jurisdiction Jurisdicción del bono.
     *
     * @return mixed Respuesta del servicio.
     */
    public function getUserInfoFRBonus($id, $user, $password, $userId, $jurisdiction)
    {
        $array = array(
            "id" => $id,
            "user" => $user,
            "password" => $password,
            "params" => array(
                "userId" => $userId,
                "jurisdiction" => intval($jurisdiction),
            )
        );

        $response = $this->connection(json_encode($array));

        return $response;
    }

    /**
     * Obtiene la lista de juegos disponibles.
     *
     * @param string $id       ID de la operación.
     * @param string $user     Usuario para la autenticación.
     * @param string $password Contraseña para la autenticación.
     *
     * @return mixed Respuesta del servicio.
     */
    public function getGameList($id, $user, $password)
    {
        $array = array(
            "id" => $id,
            "user" => $user,
            "password" => $password,
            "params" => array()
        );

        $response = $this->connection(json_encode($array));

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

    /**
     * Realiza una conexión HTTP utilizando cURL.
     *
     * @param string $array    Datos a enviar en la solicitud.
     * @param string $url      URL del servicio.
     * @param string $user     Usuario para la autenticación.
     * @param string $password Contraseña para la autenticación.
     *
     * @return mixed Respuesta del servicio.
     */
    public function connection($array, $url = "", $user = "", $password = "")
    {
        $headers = array(
            'Content-Type:application/json'
        );

        $time = time();

        $curl = new CurlWrapper($url);

        //Configurar opciones
        $curl->setOptionsArray(array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $user . ":" . $password,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_POSTFIELDS => $array
        ));

        $result = $curl->execute();

        syslog(LOG_WARNING, "REDRAKE DATA  " . $time . ' ' . json_encode($array));
        syslog(LOG_WARNING, "REDRAKE RESPONSE " . $time . ' ' . $result);

        return ($result);
    }
}
