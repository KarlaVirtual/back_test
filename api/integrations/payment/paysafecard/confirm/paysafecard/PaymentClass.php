<?php

/**
 * Clase para gestionar pagos con Paysafecard.
 *
 * Este archivo contiene la implementación de la clase `PaysafecardPaymentController`,
 * que permite realizar operaciones como crear, capturar y recuperar pagos
 * utilizando la API de Paysafecard.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Controlador para gestionar pagos con Paysafecard.
 *
 * Proporciona métodos para interactuar con la API de Paysafecard, incluyendo
 * la creación, captura y recuperación de pagos, así como la gestión de errores.
 */
class PaysafecardPaymentController
{

    /**
     * Respuesta de la última solicitud realizada.
     *
     * @var array
     */
    private $response;

    /**
     * Parámetros de la última solicitud realizada.
     *
     * @var array
     */
    private $request = array();

    /**
     * Información de cURL de la última solicitud realizada.
     *
     * @var array
     */
    private $curl;

    /**
     * Clave API para autenticación.
     *
     * @var string
     */
    private $key = "";

    /**
     * URL base de la API.
     *
     * @var string
     */
    private $url = "";

    /**
     * Entorno de la API (TEST o PRODUCTION).
     *
     * @var string
     */
    private $environment = 'TEST';

    /**
     * Constructor de la clase.
     *
     * Inicializa la clave API y el entorno (TEST o PRODUCTION) y configura la URL base.
     *
     * @param string $key         Clave API para autenticación.
     * @param string $environment Entorno de la API (TEST o PRODUCTION).
     */
    public function __construct($key = "", $environment = "TEST")
    {
        $this->key = $key;
        $this->environment = $environment;
        $this->setEnvironment();
    }

    /**
     * Realiza una solicitud HTTP a la API de Paysafecard.
     *
     * Configura y ejecuta una solicitud cURL con los parámetros proporcionados.
     *
     * @param array|string $curlparam Parámetros de la solicitud.
     * @param string       $method    Método HTTP (POST o GET).
     * @param array        $headers   Encabezados adicionales para la solicitud.
     *
     * @return void
     */
    private function doRequest($curlparam, $method, $headers = array())
    {
        $ch = curl_init();

        print_r(base64_encode($this->key));
        $header = array(
            "Authorization: Basic " . base64_encode($this->key),
            "Content-Type: application/json",
        );

        $header = array_merge($header, $headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlparam));
            curl_setopt($ch, CURLOPT_POST, true);
        } elseif ($method == 'GET') {
            if ( ! empty($curlparam)) {
                curl_setopt($ch, CURLOPT_URL, $this->url . $curlparam);
                curl_setopt($ch, CURLOPT_POST, false);
            } else {
                curl_setopt($ch, CURLOPT_URL, $this->url);
            }
        }
        curl_setopt($ch, CURLOPT_PORT, 443);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        if (is_array($curlparam)) {
            $curlparam['request_url'] = $this->url;
        } else {
            $requestURL = $this->url . $curlparam;
            $curlparam = array();
            $curlparam['request_url'] = $requestURL;
        }
        $this->request = $curlparam;
        $this->response = json_decode(curl_exec($ch), true);

        $this->curl["info"] = curl_getinfo($ch);
        $this->curl["error_nr"] = curl_errno($ch);
        $this->curl["error_text"] = curl_error($ch);
        $this->curl["http_status"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->setEnvironment();
    }

    /**
     * Verifica si la solicitud fue exitosa.
     *
     * Comprueba si no hubo errores y si el código de estado HTTP es menor a 300.
     *
     * @return boolean `true` si la solicitud fue exitosa, `false` en caso contrario.
     */
    public function requestIsOk()
    {
        if (($this->curl["error_nr"] == 0) && ($this->curl["http_status"] < 300)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Obtiene los parámetros de la última solicitud realizada.
     *
     * @return array Parámetros de la solicitud.
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Obtiene la información de cURL de la última solicitud realizada.
     *
     * @return array Información de cURL.
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * Crea un nuevo pago en la API de Paysafecard.
     *
     * Configura los parámetros necesarios para la creación de un pago y realiza
     * la solicitud correspondiente.
     *
     * @param float   $amount              Monto del pago.
     * @param string  $currency            Moneda del pago.
     * @param string  $customer_id         ID del cliente.
     * @param string  $customer_ip         Dirección IP del cliente.
     * @param string  $success_url         URL de redirección en caso de éxito.
     * @param string  $failure_url         URL de redirección en caso de fallo.
     * @param string  $notification_url    URL para notificaciones.
     * @param string  $correlation_id      Opcional ID de correlación.
     * @param string  $country_restriction Opcional Restricción por país.
     * @param string  $kyc_restriction     Opcional Restricción por nivel KYC.
     * @param integer $min_age             Opcional Edad mínima permitida.
     * @param string  $shop_id             Opcional ID de la tienda.
     * @param string  $submerchant_id      Opcional ID del subcomerciante.
     *
     * @return array|boolean Respuesta de la API si es exitosa, `false` en caso contrario.
     */
    public function createPayment($amount, $currency, $customer_id, $customer_ip, $success_url, $failure_url, $notification_url, $correlation_id = "", $country_restriction = "", $kyc_restriction = "", $min_age = "", $shop_id = "", $submerchant_id = "")
    {
        $amount = str_replace(',', '.', $amount);

        $customer = array(
            "id" => $customer_id,
            "ip" => $customer_ip,
        );
        if ($country_restriction != "") {
            array_push(
                $customer,
                "country_restriction", $country_restriction
            );
        }

        if ($kyc_restriction != "") {
            array_push(
                $customer,
                "kyc_level", $kyc_restriction
            );
        }

        if ($min_age != "") {
            array_push(
                $customer,
                "min_age", $min_age
            );
        }

        $jsonarray = array(
            "currency" => $currency,
            "amount" => $amount,
            "customer" => $customer,
            "redirect" => array(
                "success_url" => $success_url,
                "failure_url" => $failure_url,
            ),
            "type" => "PAYSAFECARD",
            "notification_url" => $notification_url,
            "shop_id" => $shop_id,
        );

        if ($submerchant_id != "") {
            array_push(
                $jsonarray,
                "submerchant_id", $submerchant_id
            );
        }

        if ($correlation_id != "") {
            $headers = ["Correlation-ID: " . $correlation_id];
        } else {
            $headers = [];
        }
        $this->doRequest($jsonarray, "POST", $headers);
        if ($this->requestIsOk() == true) {
            return $this->response;
        } else {
            return false;
        }
    }

    /**
     * Captura un pago existente en la API de Paysafecard.
     *
     * Realiza una solicitud para capturar un pago previamente creado.
     *
     * @param string $payment_id ID del pago a capturar.
     *
     * @return array|boolean Respuesta de la API si es exitosa, `false` en caso contrario.
     */
    public function capturePayment($payment_id)
    {
        $this->url = $this->url . $payment_id . "/capture";
        $jsonarray = array(
            'id' => $payment_id,
        );
        $this->doRequest($jsonarray, "POST");
        if ($this->requestIsOk() == true) {
            return $this->response;
        } else {
            return false;
        }
    }

    /**
     * Recupera la información de un pago existente.
     *
     * Realiza una solicitud para obtener los detalles de un pago utilizando su ID.
     *
     * @param string $payment_id ID del pago a recuperar.
     *
     * @return array|boolean Respuesta de la API si es exitosa, `false` en caso contrario.
     */
    public function retrievePayment($payment_id)
    {
        $this->url = $this->url . $payment_id;
        $jsonarray = array();
        $this->doRequest($jsonarray, "GET");
        if ($this->requestIsOk() == true) {
            return $this->response;
        } else {
            return false;
        }
    }

    /**
     * Obtiene la respuesta de la última solicitud realizada.
     *
     * @return array Respuesta de la API.
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Configura la URL base según el entorno seleccionado.
     *
     * Define la URL de la API dependiendo de si el entorno es TEST o PRODUCTION.
     *
     * @return boolean `true` si el entorno es válido, `false` en caso contrario.
     */
    private function setEnvironment()
    {
        if ($this->environment == "TEST") {
            $this->url = "https://apitest.paysafecard.com/v1/payments/";
        } else {
            if ($this->environment == "PRODUCTION") {
                $this->url = "https://api.paysafecard.com/v1/payments/";
            } else {
                echo "Environment not supported";
                return false;
            }
        }
        return true;
    }

    /**
     * Obtiene el mensaje de error de la última solicitud realizada.
     *
     * Analiza el código de error HTTP o el número de error devuelto por la API
     * y devuelve un mensaje descriptivo.
     *
     * @return array Respuesta con el número y mensaje de error.
     */
    public function getError()
    {
        if ( ! isset($this->response["number"])) {
            switch ($this->curl["info"]['http_code']) {
                case 400:
                    $this->response["number"] = "HTTP:400";
                    $this->response["message"] = 'Logical error. Please check logs.';
                    break;
                case 403:
                    $this->response["number"] = "HTTP:403";
                    $this->response["message"] = 'Transaction could not be initiated due to connection problems. The IP from the server is not whitelisted! Server IP:' . $_SERVER["SERVER_ADDR"];
                    break;
                case 500:
                    $this->response["number"] = "HTTP:500";
                    $this->response["message"] = 'Server error. Please check logs.';
                    break;
            }
        }
        switch ($this->response["number"]) {
            case 4003:
                $this->response["message"] = 'The amount for this transaction exceeds the maximum amount. The maximum amount is 1000 EURO (equivalent in other currencies)';
                break;
            case 3001:
                $this->response["message"] = 'Transaction could not be initiated due to connection problems. If the problem persists, please contact our support.';
                break;
            case 2002:
                $this->response["message"] = 'payment id is unknown.';
                break;
            case 2010:
                $this->response["message"] = 'Currency is not supported.';
                break;
            case 2029:
                $this->response["message"] = 'Amount is not valid. Valid amount has to be above 0.';
                break;
            default:
                $this->response["message"] = 'Transaction could not be initiated due to connection problems. If the problem persists, please contact our support. ';
                break;
        }
        return $this->response;
    }
}
