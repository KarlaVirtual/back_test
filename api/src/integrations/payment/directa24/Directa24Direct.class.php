<?php

/**
 * Clase Directa24Direct
 *
 * Esta clase proporciona métodos para interactuar con la API de Directa24,
 * incluyendo la creación de pagos, consulta de estado, conversión de divisas
 * y obtención de bancos por país.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */
class Directa24Direct
{

    /**
     * Credenciales de inicio de sesión para la API de Directa24.
     *
     * @var string
     */
    private $x_login = '***';

    /**
     * Clave de transacción para la API de Directa24.
     *
     * @var string
     */
    private $x_trans_key = '***';

    /**
     * Credenciales de inicio de sesión para consultar el estado de pagos en la API de Directa24.
     *
     * @var string
     */
    private $x_login_for_webpaystatus = '***';

    /**
     * Clave de transacción para consultar el estado de pagos en la API de Directa24.
     *
     * @var string
     */
    private $x_trans_key_for_webpaystatus = '***';

    /**
     * Clave secreta utilizada para generar el control hash.
     *
     * @var string
     */
    private $secret_key = '***';

    /**
     * Indica si se utiliza el entorno de pruebas (sandbox).
     *
     * @var boolean
     */
    private $sandbox = true;

    /**
     * URL de la API de Directa24.
     *
     * @var array
     */
    private $url = array(
        'create' => '',
        'status' => '',
        'exchange' => '',
        'banks' => ''
    );

    /**
     * Contador de errores en las solicitudes cURL.
     *
     * @var integer
     */
    private $errors = 0;

    /**
     * Constructor de la clase Directa24Direct.
     *
     * Inicializa las URLs de la API y establece el contador de errores.
     */
    public function __construct()
    {
        $this->errors = 0;

        $this->url['create'] = 'https://api.directa24.com/api_curl/apd/create';
        $this->url['status'] = 'https://api.directa24.com/apd/webpaystatus';
        $this->url['exchange'] = 'https://api.directa24.com/apd/webcurrencyexchange';
        $this->url['banks'] = 'https://api.directa24.com/api_curl/apd/get_banks_by_country';

        if ($this->sandbox) {
            $this->url['create'] = 'https://api-stg.directa24.com/api_curl/apd/create';
            $this->url['status'] = 'https://api-stg.directa24.com/apd/webpaystatus';
            $this->url['exchange'] = 'https://api-stg.directa24.com/apd/webcurrencyexchange';
            $this->url['banks'] = 'https://api-stg.directa24.com/api_curl/apd/get_banks_by_country';
        }
    }

    /**
     * Crea un nuevo pago en Directa24.
     *
     * @param string $invoice          Número de factura.
     * @param float  $amount           Monto a pagar.
     * @param string $iduser           ID del usuario.
     * @param string $bank             Banco a utilizar (opcional).
     * @param string $country          País del usuario (opcional).
     * @param string $currency         Moneda a utilizar (opcional).
     * @param string $description      Descripción del pago (opcional).
     * @param string $cpf              CPF del usuario (opcional).
     * @param string $sub_code         Código de suscripción (opcional).
     * @param string $return_url       URL de retorno (opcional).
     * @param string $confirmation_url URL de confirmación (opcional).
     * @param string $response_type    Tipo de respuesta (json o xml) (opcional).
     *
     * @return array Respuesta de la API.
     */
    public function create($invoice, $amount, $iduser, $bank, $country, $currency, $description, $cpf, $sub_code, $return_url, $confirmation_url, $response_type = 'json')
    {
        $params_array = array(
            //Mandatory
            'x_login' => $this->x_login,
            'x_trans_key' => $this->x_trans_key,
            'x_invoice' => $invoice,
            'x_amount' => $amount,
            'x_iduser' => $iduser,

            //Optional
            'x_bank' => $bank,
            'x_country' => $country,
            'x_currency' => $currency,
            'x_description' => $description,
            'x_cpf' => $cpf,
            'x_sub_code' => $sub_code,
            'x_return' => $return_url,
            'x_confirmation' => $confirmation_url,
            'type' => $response_type
        );

        $message_to_control = $invoice . 'D' . $amount . 'P' . $iduser . 'A';
        $control = strtoupper(hash_hmac('sha256', pack('A*', $message_to_control), pack('A*', $this->secret_key)));

        $params_array['control'] = $control;

        $response = $this->curl($this->url['create'], $params_array);
        return $response;
    }

    /**
     * Obtiene el estado de un pago en Directa24.
     *
     * @param string $invoice Número de factura.
     *
     * @return array Respuesta de la API.
     */
    public function get_status($invoice)
    {
        $params_array = array(
            //Mandatory
            'x_login' => $this->x_login_for_webpaystatus,
            'x_trans_key' => $this->x_trans_key_for_webpaystatus,
            'x_invoice' => $invoice
        );

        $response = $this->curl($this->url['status'], $params_array);
        return $response;
    }

    /**
     * Obtiene el tipo de cambio de una moneda a otra.
     *
     * @param string $country Código del país (opcional).
     * @param float  $amount  Monto a convertir (opcional).
     *
     * @return array Respuesta de la API.
     */
    public function get_exchange($country = 'BR', $amount = 1)
    {
        $params_array = array(
            //Mandatory
            'x_login' => $this->x_login_for_webpaystatus,
            'x_trans_key' => $this->x_trans_key_for_webpaystatus,
            'x_country' => $country,
            'x_amount' => $amount
        );

        $response = $this->curl($this->url['exchange'], $params_array);
        return $response;
    }

    /**
     * Obtiene la lista de bancos disponibles por país.
     *
     * @param string $country Código del país (opcional).
     * @param string $type    Tipo de respuesta (json o xml) (opcional).
     *
     * @return array Respuesta de la API.
     */
    public function get_banks_by_country($country = 'BR', $type = 'json')
    {
        $params_array = array(
            'x_login' => $this->x_login,
            'x_trans_key' => $this->x_trans_key,
            'country_code' => $country,
            'type' => $type
        );

        $response = $this->curl($this->url['banks'], $params_array);
        return $response;
    }

    /**
     * Realiza una solicitud cURL a la API de Directa24.
     *
     * @param string $url          URL de la API.
     * @param array  $params_array Parámetros para la solicitud.
     *
     * @return string Respuesta de la API.
     */
    private function curl($url, $params_array)
    {
        $params = array();
        foreach ($params_array as $key => $value) {
            $params[] = "{$key}={$value}";
        }
        $params = join('&', $params);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        if (($error = curl_error($ch)) != false) {
            $this->errors++;

            if ($this->errors >= 5) {
                die("Error in curl: $error");
            }

            sleep(1);
            $this->curl($url, $params_array);
        }
        curl_close($ch);

        $this->errors = 0;
        return $response;
    }

}
