<?php
/** 
* Clase 'PaysafecardPaymentController'
* 
* Esta clase provee funciones para la api 'PaysafecardPaymentController'
* 
* Ejemplo de uso: 
* $PaysafecardPaymentController = new PaysafecardPaymentController();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PaysafecardPaymentController
{

    /**
    * Representación de 'response'
    *
    * @var string
    * @access private
    */    
    private $response;

    /**
    * Representación de 'request'
    *
    * @var string
    * @access private
    */
    private $request = array();

    /**
    * Representación de 'curl'
    *
    * @var string
    * @access private
    */
    private $curl;

    /**
    * Representación de 'key'
    *
    * @var string
    * @access private
    */
    private $key         = "";

    /**
    * Representación de 'url'
    *
    * @var string
    * @access private
    */
    private $url         = "";

    /**
    * Representación de 'environment'
    *
    * @var string
    * @access private
    */
    private $environment = 'TEST';

    /**
     * Método constructor
     *
     * @param String $key key
     * @param String $environment environment
     * 
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($key = "", $environment = "TEST")
    {
        $this->key         = $key;
        $this->environment = $environment;
        $this->setEnvironment();
    }


    /**
     * Realizar una petición
     *
     *
     * @param String $curlparam curlparam
     * @param String $method method
     * @param array $headers headers
     *
     * @access private
     * @see no
     * @since no
     * @deprecated no
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
            if (!empty($curlparam)) {
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
            $requestURL               = $this->url . $curlparam;
            $curlparam                = array();
            $curlparam['request_url'] = $requestURL;
        }
        $this->request  = $curlparam;
        $this->response = json_decode(curl_exec($ch), true);

        $this->curl["info"]        = curl_getinfo($ch);
        $this->curl["error_nr"]    = curl_errno($ch);
        $this->curl["error_text"]  = curl_error($ch);
        $this->curl["http_status"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // reset URL do default
        $this->setEnvironment();
    }


    /**
    * Chequear estado de una petición
    *
    *
    * @return boolean $ estado de la petición
    *
    * @access public
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
    * Obtener una petición
    *
    *
    * @return String $ request
    *
    * @access public
    */
    public function getRequest()
    {
        return $this->request;
    }


    /**
    * Obtener curl
    *
    *
    * @return String $ curl
    *
    * @access public
    */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
    * Crear un pago
    *
    *
    * @param double $amount amount
    * @param string $currency currency
    * @param string $customer_id customer_id
    * @param string $customer_ip customer_ip
    * @param string $success_url success_url
    * @param string $failure_url failure_url
    * @param string $notification_url notification_url
    * @param string|double $correlation_id correlation_id
    * @param string $country_restriction country_restriction
    * @param string $kyc_restriction kyc_restriction
    * @param int|string $min_age min_age
    * @param int|string $shop_id shop_id
    * @param string $submerchant_id submerchant_id
    *
    * @return array|bool $ resultado de la operación
    */
    public function createPayment($amount, $currency, $customer_id, $customer_ip, $success_url, $failure_url, $notification_url, $correlation_id = "", $country_restriction = "", $kyc_restriction = "", $min_age = "", $shop_id = "", $submerchant_id = "")
    {
        $amount = str_replace(',', '.', $amount);

        $customer = array(
            "id" => $customer_id,
            "ip" => $customer_ip,
        );
        if ($country_restriction != "") {
            array_push($customer, 
                "country_restriction", $country_restriction
            );
        }

        if ($kyc_restriction != "") {
            array_push($customer,
                "kyc_level", $kyc_restriction
            );
        }

        if ($min_age != "") {
            array_push($customer,
                "min_age" , $min_age
            );
        }

        $jsonarray = array(
            "currency"         => $currency,
            "amount"           => $amount,
            "customer"         => $customer,
            "redirect"         => array(
                "success_url" => $success_url,
                "failure_url" => $failure_url,
            ),
            "type"             => "PAYSAFECARD",
            "notification_url" => $notification_url,
            "shop_id"          => $shop_id,
        );

        if ($submerchant_id != "") {
            array_push($jsonarray, 
                "submerchant_id" , $submerchant_id
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
     * Obtener un pago mediante su id
     *
     * @param String $payment_id payment_id
     * @return array|bool $ resultado de la operación
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
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
     * Obtener un pago mediante su id
     *
     * @param String $payment_id payment_id
     *
     * @return array|bool $ resultado de la operación
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
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
     * Obtener una respuesta
     *
     * @return array $ resultado de la operación
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Establecer un ambiente
     *
     * @return boolean $ resultado de la operación
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    private function setEnvironment()
    {
        if ($this->environment == "TEST") {
            $this->url = "https://apitest.paysafecard.com/v1/payments/";
        } else if ($this->environment == "PRODUCTION") {
            $this->url = "https://api.paysafecard.com/v1/payments/";
        } else {
            echo "Environment not supported";
            return false;
        }
        return true;
    }

    /**
     * Obtener el error
     *
     * @return array $ error
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getError()
    {
        if (!isset($this->response["number"])) {
            switch ($this->curl["info"]['http_code']) {
                case 400:
                    $this->response["number"]  = "HTTP:400";
                    $this->response["message"] = 'Logical error. Please check logs.';
                    break;
                case 403:
                    $this->response["number"]  = "HTTP:403";
                    $this->response["message"] = 'Transaction could not be initiated due to connection problems. The IP from the server is not whitelisted! Server IP:' . $_SERVER["SERVER_ADDR"];
                    break;
                case 500:
                    $this->response["number"]  = "HTTP:500";
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
