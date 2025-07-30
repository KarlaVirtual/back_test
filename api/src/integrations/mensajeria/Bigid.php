<?php

/**
 * Clase Bigid para la integración con el servicio de mensajería BigID.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-18
 */

namespace Backend\integrations\mensajeria;

use Backend\dto\Pais;
use Backend\dto\Proveedor;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\UsuarioMensaje;
use Exception;

/**
 * Clase Bigid para la integración con el servicio de mensajería BigID.
 *
 * Proporciona métodos para enviar mensajes SMS, verificar códigos y generar tokens
 * de autenticación utilizando las APIs de BigID.
 */
class Bigid
{
    /**
     * URL para la generación de tokens.
     *
     * @var string
     */
    private $URLTOKEN = 'https://accesstoken.bigdatacorp.com.br';

    /**
     * URL base para el envío de mensajes.
     *
     * @var string
     */
    private $URL = 'https://bigid.bigdatacorp.com.br';

    /**
     * Login del usuario.
     *
     * @var string
     */
    private $login = '';

    /**
     * Contraseña del usuario.
     *
     * @var string
     */
    private $password = '';

    /**
     * Tiempo de expiración del token en segundos.
     *
     * @var integer
     */
    private $expires = 87500;

    /**
     * Constructor de la clase Bigid.
     */
    public function __construct()
    {
    }

    /**
     * Envía un mensaje SMS a un número de teléfono.
     *
     * @param string $phone    Número de teléfono al que se enviará el mensaje.
     * @param string $rid      Identificador único de la solicitud.
     * @param int    $Mandante Identificador del mandante (por defecto 14).
     *
     * @return array Respuesta con el estado del envío.
     * @throws Exception Si ocurre un error durante el envío.
     */
    public function sendMessage($phone, $rid, $Mandante = 14)
    {
        if ($Mandante == 14) {
            $this->login = "lotosports.bet@gmail.com";
            $this->password = "9x8ofp7nw";
        }
        if ($Mandante == 17) {
            $this->login = "arnaldinho81@gmail.com";
            $this->password = "Milbets$1727";
        }

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $data["code"] = 21016;
        $data["error_code"] = 21016;
        $data["rid"] = $rid;

        $dataT = array();
        $dataT['login'] = $this->login;
        $dataT['password'] = $this->password;
        $dataT['expires'] = $this->expires;
        $respuesta = $this->token($dataT, $this->URLTOKEN, '/Generate');
        $tokenT = $respuesta->token;

        if ($Mandante == 17) {
        }
        $dataB = array();
        $dataB['Token'] = $tokenT;
        $dataB['Phone'] = $phone;
        $Result = $this->sendsms($dataB, $this->URL, '/SendSMS');
        $resultCode = $Result->resultCode;

        if ($resultCode == 1400) {
            $response = $Result;
            $data = array();
            $data["success"] = true;
            $data["code"] = 0;
            $data["ticked"] = $response->ticketId;
            $data["rid"] = $rid;
        } else {
            if ($Result->resultMessage != '') {
                throw new Exception($Result->resultMessage, "21015"); //21015
            }
        }

        return $data;
    }

    /**
     * Realiza una solicitud HTTP POST para enviar un SMS.
     *
     * @param array  $data Datos del mensaje a enviar.
     * @param string $url  URL base del servicio.
     * @param string $path Ruta del endpoint.
     *
     * @return object Respuesta decodificada del servidor.
     */
    public function sendsms($data, $url, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $time = time();
        syslog(LOG_WARNING, " BIGIDDATA :" . $time . " " . json_encode($data));

        $response = curl_exec($curl);

        syslog(LOG_WARNING, " BIGIDDATAR : " . $time . " " . $response);

        curl_close($curl);
        return json_decode($response);
    }

    /**
     * Verifica un código enviado a través de SMS.
     *
     * @param string $ticketId Identificador del ticket.
     * @param string $code_    Código a verificar.
     * @param string $rid      Identificador único de la solicitud.
     * @param int    $Mandante Identificador del mandante (por defecto 14).
     *
     * @return array Respuesta con el estado de la verificación.
     * @throws Exception Si ocurre un error durante la verificación.
     */
    public function sendCheck($ticketId, $code_, $rid, $Mandante = 14)
    {

        if ($Mandante == 14) {
            $this->login = "lotosports.bet@gmail.com";
            $this->password = "9x8ofp7nw";
        }
        if ($Mandante == 17) {
            $this->login = "arnaldinho81@gmail.com";
            $this->password = "Milbets$1727";
        }
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $data["code"] = 1;
        $data["resultMessage"] = 'Error';
        $data["rid"] = $rid;

        $dataT = array();
        $dataT['login'] = $this->login;
        $dataT['password'] = $this->password;
        $dataT['expires'] = $this->expires;
        $respuesta = $this->token($dataT, $this->URLTOKEN, '/Generate');
        $tokenT = $respuesta->token;

        $dataB = array();
        $dataB['Token'] = $tokenT;
        $dataB['TicketId'] = $ticketId;
        $dataB['Code'] = $code_;
        $Result = $this->smscheck($dataB, $this->URL, '/SMSCheck');
        $resultCode = $Result->resultCode;

        if ($resultCode == 1401) {
            $response = $Result;
            $data = array();
            $data["success"] = true;
            $data["code"] = 0;
            $data["rid"] = $rid;
            //$data["ticked"] = $response->ticketId;
            //$data["resultMessage"] = $response->resultMessage;
        } else {
            if ($Result->resultMessage != '') {
                throw new Exception($Result->resultMessage, "21015"); //21015
            }
        }

        return $data;
    }

    /**
     * Realiza una solicitud HTTP POST para verificar un SMS.
     *
     * @param array  $data Datos de la verificación.
     * @param string $url  URL base del servicio.
     * @param string $path Ruta del endpoint.
     *
     * @return object Respuesta decodificada del servidor.
     */
    public function smscheck($data, $url, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $time = time();
        syslog(LOG_WARNING, " BIGIDDATACHECK : " . $time . " " . json_encode($data));

        $response = curl_exec($curl);
        syslog(LOG_WARNING, " BIGIDDATACHECKR : " . $time . " " . $response);

        curl_close($curl);
        return json_decode($response);
    }

    /**
     * Genera un token de autenticación.
     *
     * @param array  $data Datos necesarios para la generación del token.
     * @param string $url  URL base del servicio.
     * @param string $path Ruta del endpoint.
     *
     * @return object Respuesta decodificada del servidor.
     */
    public function token($data, $url, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }


}
