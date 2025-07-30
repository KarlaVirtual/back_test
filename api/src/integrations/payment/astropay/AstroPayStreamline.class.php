<?php

/**
 * Clase AstroPayStreamline
 *
 * Esta clase proporciona métodos para interactuar con la API de AstroPay, incluyendo la creación de facturas,
 * consulta de estado de pagos, consulta de tasas de cambio y obtención de bancos por país.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */
class AstroPayStreamline
{

    /**
     * Credenciales de inicio de sesión para la API de AstroPay.
     *
     * @var string
     */
    private $x_login = 'LFpcS1HkNW';

    /**
     * Clave de transacción para la API de AstroPay.
     *
     * @var string
     */
    private $x_trans_key = 'SSiDwaLeOa';

    /**
     * Credenciales de inicio de sesión para consultar el estado de pagos en la API de AstroPay.
     *
     * @var string
     */
    private $x_login_for_webpaystatus = 'tRfGtdl7oE';

    /**
     * Clave de transacción para consultar el estado de pagos en la API de AstroPay.
     *
     * @var string
     */
    private $x_trans_key_for_webpaystatus = 'GuffDj1j2A';

    /**
     * Clave secreta utilizada para generar el control hash.
     *
     * @var string
     */
    private $secret_key = '86QqBjg1RdZ24mhL71jOD6FIVfeQm9mNb';

    /**
     * Indica si se utiliza el entorno de pruebas (sandbox).
     *
     * @var boolean
     */
    private $sandbox = false;


    private $url = array(
        'newinvoice' => '',
        'status' => '',
        'exchange' => '',
        'banks' => ''
    );
    private $errors = 0;

    /**
     * Constructor de la clase.
     *
     * Inicializa las URLs de la API dependiendo del entorno (producción o sandbox).
     */
    public function __construct()
    {
        $this->errors = 0;

        $this->url['newinvoice'] = 'https://astropaycard.com/api_curl/streamline/newinvoice/';
        $this->url['status'] = 'https://astropaycard.com/apd/webpaystatus';
        $this->url['exchange'] = 'https://astropaycard.com/apd/webcurrencyexchange';
        $this->url['banks'] = 'https://astropaycard.com/api_curl/apd/get_banks_by_country';

        if ($this->sandbox) {
            $this->url['newinvoice'] = 'https://sandbox.astropaycard.com/api_curl/streamline/newinvoice';
            $this->url['status'] = 'https://sandbox.astropaycard.com/apd/webpaystatus';
            $this->url['exchange'] = 'https://sandbox.astropaycard.com/apd/webcurrencyexchange';
            $this->url['banks'] = 'https://sandbox.astropaycard.com/api_curl/apd/get_banks_by_country';
        }
    }

    /**
     * Crea una nueva factura en la API de AstroPay.
     *
     * @param string $invoice          Número de factura.
     * @param float  $amount           Monto de la factura.
     * @param string $bank             Banco seleccionado.
     * @param string $country          Código del país.
     * @param string $iduser           ID del usuario.
     * @param string $cpf              CPF del usuario (Brasil).
     * @param string $name             Nombre del usuario.
     * @param string $email            Correo electrónico del usuario.
     * @param string $currency         Opcional Moneda de la transacción.
     * @param string $description      Opcional Descripción de la transacción.
     * @param string $bdate            Opcional Fecha de nacimiento del usuario.
     * @param string $address          Opcional Dirección del usuario.
     * @param string $zip              Opcional Código postal del usuario.
     * @param string $city             Opcional Ciudad del usuario.
     * @param string $state            Opcional Estado del usuario.
     * @param string $return_url       Opcional URL de retorno.
     * @param string $confirmation_url Opcional URL de confirmación.
     *
     * @return mixed                   Respuesta de la API.
     */
    public function newinvoice(
        $invoice,
        $amount,
        $bank,
        $country,
        $iduser,
        $cpf,
        $name,
        $email,
        $currency = '',
        $description = '',
        $bdate = '',
        $address = '',
        $zip = '',
        $city = '',
        $state = '',
        $return_url = '',
        $confirmation_url = ''
    ) {
        $params_array = array(
            'x_login' => $this->x_login,
            'x_trans_key' => $this->x_trans_key,
            'x_invoice' => $invoice,
            'x_amount' => $amount,
            'x_bank' => $bank,
            'type' => 'json',
            'x_country' => $country,
            'x_iduser' => $iduser,
            'x_cpf' => $cpf,
            'x_name' => $name,
            'x_email' => $email,
        );

        if ( ! empty($currency)) {
            $params_array['x_currency'] = $currency;
        }
        if ( ! empty($description)) {
            $params_array['x_description'] = $description;
        }
        if ( ! empty($bdate)) {
            $params_array['x_bdate'] = $bdate;
        }
        if ( ! empty($address)) {
            $params_array['x_address'] = $address;
        }
        if ( ! empty($zip)) {
            $params_array['x_zip'] = $zip;
        }
        if ( ! empty($city)) {
            $params_array['x_city'] = $city;
        }
        if ( ! empty($state)) {
            $params_array['x_state'] = $state;
        }
        if ( ! empty($return_url)) {
            $params_array['x_return'] = $return_url;
        }
        if ( ! empty($confirmation_url)) {
            $params_array['x_confirmation'] = $confirmation_url;
        }


        $message = $invoice . 'V' . $amount . 'I' . $iduser . '2' . $bank . '1' . $cpf . 'H' . $bdate . 'G' . $email . 'Y' . $zip . 'A' . $address . 'P' . $city . 'S' . $state . 'P';
        $control = strtoupper(hash_hmac('sha256', pack('A*', $message), pack('A*', $this->secret_key)));
        $params_array['control'] = $control;

        $response = $this->curl($this->url['newinvoice'], $params_array);
        return $response;
    }


    /**
     * Consulta el estado de una factura en la API de AstroPay.
     *
     * @param string $invoice Número de factura.
     *
     * @return mixed          Respuesta de la API.
     */
    public
    function get_status(
        $invoice
    ) {
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
     * Consulta la tasa de cambio en la API de AstroPay.
     *
     * @param string $country Código del país (por defecto 'BR').
     * @param float  $amount  Monto para calcular la tasa de cambio (por defecto 1).
     *
     * @return mixed          Respuesta de la API.
     */
    public
    function get_exchange(
        $country = 'BR',
        $amount = 1
    ) {
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
     * Obtiene la lista de bancos disponibles por país desde la API de AstroPay.
     *
     * @param string $country Código del país (por defecto 'BR').
     * @param string $type    Tipo de respuesta (por defecto 'json').
     *
     * @return mixed          Respuesta de la API.
     */
    public
    function get_banks_by_country(
        $country = 'BR',
        $type = 'json'
    ) {
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
    private
    function curl(
        $url,
        $params_array
    ) {
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
