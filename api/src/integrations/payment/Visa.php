<?php

/**
 * Clase Visa para la integración de pagos con la pasarela de Visa.
 *
 * Este archivo contiene la implementación de la clase Visa, que permite realizar
 * operaciones como la creación de tokens, autorizaciones y confirmaciones de transacciones
 * con la pasarela de pagos Visa.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\TransaccionProducto;
use Backend\dto\ConfigurationEnvironment;

/**
 * Clase Visa para manejar la integración con la pasarela de pagos Visa.
 *
 * Esta clase permite realizar operaciones como la creación de tokens,
 * autorizaciones y confirmaciones de transacciones con la API de Visa.
 */
class Visa
{
    /**
     * ID del comercio.
     *
     * @var string
     */
    private $merchantId = '';

    /**
     * Usuario para autenticación.
     *
     * @var string
     */
    private $Usuario = '';

    /**
     * Contraseña para autenticación.
     *
     * @var string
     */
    private $Password = '';

    /**
     * Clave de acceso proporcionada por Visa.
     *
     * @var string
     */
    private $accessKey = '';

    /**
     * Clave secreta proporcionada por Visa.
     *
     * @var string
     */
    private $secretKey = '';

    /**
     * Token de sesión.
     *
     * @var string
     */
    private $sessionToken;

    /**
     * Monto de la transacción.
     *
     * @var float
     */
    private $amount;

    /**
     * Token de la transacción.
     *
     * @var string
     */
    private $transactionToken;

    /**
     * ID del producto de la transacción.
     *
     * @var string
     */
    private $transproductoId;

    /**
     * URL base de la API de Visa.
     *
     * @var string
     */
    private $url = "";

    /**
     * Constructor de la clase Visa.
     *
     * @param string $sessionToken     Token de sesión.
     * @param string $transactionToken Token de la transacción.
     * @param float  $amount           Monto de la transacción.
     * @param string $transproductoId  ID del producto de la transacción.
     * @param string $Url              URL base de la API.
     * @param string $merchant         ID del comercio.
     * @param string $Key              Clave de acceso.
     * @param string $secretKey        Clave secreta.
     * @param string $user             Usuario para autenticación.
     * @param string $password         Contraseña para autenticación.
     */
    public function __construct($sessionToken, $transactionToken, $amount, $transproductoId = "", $Url = "", $merchant = "", $Key = "", $secretKey = "", $user = "", $password = "")
    {
        $this->sessionToken = $sessionToken;
        $this->transactionToken = $transactionToken;
        $this->amount = $amount;
        $this->transproductoId = $transproductoId;

        $this->url = $Url;
        $this->merchantId = $merchant;
        $this->accessKey = $Key;
        $this->secretKey = $secretKey;
        $this->Usuario = $user;
        $this->Password = $password;
    }

    /**
     * Crea un token de sesión.
     *
     * @return string Respuesta de la API.
     */
    public function createToken()
    {
        return $this->sendRequest("createtoken");
    }

    /**
     * Realiza la autorización de una transacción.
     *
     * @param string $token Token de autorización.
     *
     * @return string Respuesta de la API.
     */
    public function Authorization($token = "")
    {
        return $this->sendRequest("authorization", $token);
    }

    /**
     * Obtiene el resultado de la autorización.
     *
     * @return string Respuesta de la API.
     */
    public function AuthorizationResult()
    {
        return $this->sendRequest("authorizationResult");
    }

    /**
     * Obtiene el ID del comercio.
     *
     * @return string ID del comercio.
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * Envía una solicitud a la API de Visa.
     *
     * @param string $tipo  Tipo de solicitud (createtoken, authorization, authorizationResult).
     * @param string $token Token de autorización (opcional).
     *
     * @return string Respuesta de la API.
     */
    public function sendRequest($tipo, $token = "")
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($tipo === "authorization") {
            $TransaccionProducto = new TransaccionProducto($this->transproductoId);
            $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
            $Registro = new Registro("", $TransaccionProducto->getUsuarioId());

            $patch = "/api.ecommerce/v2/ecommerce/token/session/{$this->merchantId}";
            $url = $this->url . $patch;

            $array = array();
            $array["amount"] = $this->amount;
            $array["antifraud"] = array();
            $array["antifraud"]["clientIp"] = $ConfigurationEnvironment->get_client_ip();
            $array["antifraud"]["merchantDefineData"] = array();
            $array["antifraud"]["merchantDefineData"]["MDD4"] = $Usuario->login;
            $array["antifraud"]["merchantDefineData"]["MDD32"] = $Registro->cedula;
            $array["antifraud"]["merchantDefineData"]["MDD31"] = $Registro->celular;
            $array["antifraud"]["merchantDefineData"]["MDD33"] = "DNI";
            $array["antifraud"]["merchantDefineData"]["MDD34"] = $Registro->cedula;
            $array["antifraud"]["merchantDefineData"]["MDD37"] = 1;
            $array["antifraud"]["merchantDefineData"]["MDD21"] = 0;
            $array["antifraud"]["merchantDefineData"]["MDD89"] = 2;

            $array["antifraud"]["merchantDefineData"]["MDD70"] = "SI";
            $array["antifraud"]["merchantDefineData"]["MDD75"] = "Registrado";

            $now = time();
            $your_date = strtotime($Usuario->fechaCrea);
            $datediff = $now - $your_date;

            $array["antifraud"]["merchantDefineData"]["MDD77"] = round($datediff / (60 * 60 * 24));

            $array["channel"] = "web";
            $array["recurrenceMaxAmount"] = "1000.00";

            $request_body = json_encode($array);

            $header = array("Content-Type: application/json", "Authorization:" . $token);
            $method = "POST";
        } elseif ($tipo === "authorizationResult") {
            $TransaccionProducto = new TransaccionProducto($this->transproductoId);
            $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
            $Registro = new Registro("", $TransaccionProducto->getUsuarioId());

            $Usuario = new Usuario($TransaccionProducto->getUsuarioId());

            $patch = "/api.security/v1/security";
            $url = $this->url . $patch;

            $request_body = "";

            $header = array("Content-Type: application/json");
            $method = "GET";
            syslog(LOG_WARNING, " VISA DATA 1 REQ " . $request_body);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$this->Usuario:$this->Password");
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);

            syslog(LOG_WARNING, " VISA DATA 1 RES " . $response);

            $patch = "/api.authorization/v3/authorization/ecommerce/{$this->merchantId}";
            $url = $this->url . $patch;

            $array = array();

            $array["antifraud"] = null;
            $array["captureType"] = "manual";
            $array["cardHolder"] = array();
            $array["cardHolder"]["documentNumber"] = $Registro->cedula;
            $array["cardHolder"]["documentType"] = "0";
            $array["channel"] = "web";
            $array["countable"] = true;

            $array["order"] = array();
            $array["order"]["amount"] = number_format($TransaccionProducto->getValor() + $TransaccionProducto->getImpuesto(), 2);
            $array["order"]["currency"] = $Usuario->moneda;
            $array["order"]["purchaseNumber"] = $TransaccionProducto->transproductoId . '';
            $array["order"]["tokenId"] = $this->transactionToken;
            $array["sponsored"] = null;

            $request_body = json_encode($array);

            $header = array("Content-Type: application/json", "Authorization:" . $response);
            $method = "POST";
        } elseif ($tipo === "createtoken") {
            $patch = "/api.security/v1/security";
            $url = $this->url . $patch;

            $request_body = "";

            $header = array("Content-Type: application/json");
            $method = "POST";
        }

        syslog(LOG_WARNING, " VISA DATA 2 REQ " . $request_body);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->Usuario:$this->Password");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        syslog(LOG_WARNING, " VISA DATA 2 RES " . $response);

        return $response;
    }

    /**
     * Confirma el estado de una transacción.
     *
     * @param string $response Respuesta de la API.
     *
     * @return string|null Respuesta procesada o null en caso de error.
     */
    public function confirmation($response)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->sessionToken;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($response);

        $code = '';

        if (json_decode($response)->dataMap != '') {
            $code = json_decode($response)->dataMap->ACTION_CODE;
            $AMOUNT = json_decode($response)->dataMap->AMOUNT;
        } else {
            $code = '';
        }

        switch ($code) {
            case "000":
                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Visa ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                if (floatval($AMOUNT) != ($TransaccionProducto->getValor() + $TransaccionProducto->getImpuesto())) {
                    $resp = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario . ' VALOR DE API DIFERENTE AL VALOR REGISTRADO ', $t_value, 0);
                } else {
                    $resp = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $transaccion_id);
                }

                $Usuario = new Usuario($TransaccionProducto->getUsuarioId());

                $array = array(
                    "name" => $Usuario->nombre
                );

                return json_encode($array);

                break;

            default:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Visa ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;
        }
    }
}
