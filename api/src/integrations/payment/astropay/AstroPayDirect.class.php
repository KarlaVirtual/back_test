<?php

/**
 * Clase AstroPayDirect
 *
 * Esta clase proporciona métodos para interactuar con la API de AstroPay,
 * incluyendo la creación de pagos, consulta de estado, conversión de divisas
 * y obtención de bancos por país.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */
class AstroPayDirect
{

    /**
     * Credenciales de inicio de sesión para la API de AstroPay.
     *
     * @var string
     */
    private $x_login = '***';

    /**
     * Clave de transacción para la API de AstroPay.
     *
     * @var string
     */
    private $x_trans_key = '***';

    /**
     * Credenciales de inicio de sesión para consultar el estado de pagos en la API de AstroPay.
     *
     * @var string
     */
    private $x_login_for_webpaystatus = '***';

    /**
     * Clave de transacción para consultar el estado de pagos en la API de AstroPay.
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
     * URLs de los diferentes endpoints de la API de AstroPay.
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
     * Constructor de la clase AstroPayDirect.
     *
     * Inicializa las URLs de la API dependiendo del entorno (sandbox o producción).
     */
    public function __construct()
    {
        $this->errors = 0;

        $this->url['create'] = 'https://astropaycard.com/api_curl/apd/create';
        $this->url['status'] = 'https://astropaycard.com/apd/webpaystatus';
        $this->url['exchange'] = 'https://astropaycard.com/apd/webcurrencyexchange';
        $this->url['banks'] = 'https://astropaycard.com/api_curl/apd/get_banks_by_country';

        if ($this->sandbox) {
            $this->url['create'] = 'https://sandbox.astropaycard.com/api_curl/apd/create';
            $this->url['status'] = 'https://sandbox.astropaycard.com/apd/webpaystatus';
            $this->url['exchange'] = 'https://sandbox.astropaycard.com/apd/webcurrencyexchange';
            $this->url['banks'] = 'https://sandbox.astropaycard.com/api_curl/apd/get_banks_by_country';
        }
    }

    /**
     * Crea un nuevo pago en la API de AstroPay.
     *
     * @param string $invoice          Número de factura.
     * @param float  $amount           Monto del pago.
     * @param string $iduser           ID del usuario.
     * @param string $bank             Banco seleccionado.
     * @param string $country          País del usuario.
     * @param string $currency         Moneda del pago.
     * @param string $description      Descripción del pago.
     * @param string $cpf              CPF del usuario (opcional).
     * @param string $sub_code         Código adicional (opcional).
     * @param string $return_url       URL de retorno.
     * @param string $confirmation_url URL de confirmación.
     * @param string $response_type    Tipo de respuesta (por defecto 'json').
     *
     * @return mixed                   Respuesta de la API.
     */
    public function create(
        $invoice,
        $amount,
        $iduser,
        $bank,
        $country,
        $currency,
        $description,
        $cpf,
        $sub_code,
        $return_url,
        $confirmation_url,
        $response_type = 'json'
    ) {
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
     * Obtiene el estado de un pago en la API de AstroPay.
     *
     * @param string $invoice Número de factura.
     *
     * @return mixed          Respuesta de la API.
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
     * Obtiene la tasa de cambio de divisas en la API de AstroPay.
     *
     * @param string $country País para la conversión (por defecto 'BR').
     * @param float  $amount  Monto para la conversión (por defecto 1).
     *
     * @return mixed          Respuesta de la API.
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
     * Obtiene la lista de bancos disponibles por país en la API de AstroPay.
     *
     * @param string $country Código del país (por defecto 'BR').
     * @param string $type    Tipo de respuesta (por defecto 'json').
     *
     * @return mixed          Respuesta de la API.
     */
    public function get_banks_by_country($country = 'BR', $type = 'json')
    {
        $params_array = array(
            //Mandatory
            'x_login' => $this->x_login,
            'x_trans_key' => $this->x_trans_key,
            'country_code' => $country,
            'type' => $type
        );

        $response = $this->curl($this->url['banks'], $params_array);
        return $response;
    }


    /**
     * Realiza una solicitud cURL a la API de AstroPay.
     *
     * @param string $url          URL de la API.
     * @param array  $params_array Parámetros de la solicitud.
     *
     * @return mixed               Respuesta de la API.
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
