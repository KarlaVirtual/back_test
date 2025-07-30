<?php

/**
 * Directa24Streamline
 *
 * Esta clase proporciona métodos para interactuar con la API de Directa24.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */

use Google\Service\Dfareporting\Country;

/**
 * Directa24Streamline
 *
 * Esta clase proporciona métodos para interactuar con la API de Directa24,
 * incluyendo la creación de pagos, consulta de estado, conversión de divisas
 * y obtención de bancos por país.
 */
class Directa24Streamline
{
    /**
     * Credenciales de inicio de sesión para la API de Directa24.
     *
     * @var string
     */
    private $x_login = '';

    /**
     * Clave de transacción para la API de Directa24.
     *
     * @var string
     */
    private $x_trans_key = '';

    /**
     * Credenciales de inicio de sesión para consultar el estado de pagos en la API de Directa24.
     *
     * @var string
     */
    private $x_login_for_webpaystatus = '';

    /**
     * Clave de transacción para consultar el estado de pagos en la API de Directa24.
     *
     * @var string
     */
    private $x_trans_key_for_webpaystatus = '';

    /**
     * Clave secreta utilizada para generar el control hash.
     *
     * @var string
     */
    private $secret_key = '';

    /**
     * URL de la API de Directa24.
     *
     * @var array
     */
    private $url = array(
        'newinvoice' => '',
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
     * Constructor de la clase Directa24Streamline.
     *
     * Inicializa las credenciales y URLs necesarias para interactuar con la API de Directa24.
     *
     * @param string  $Url                          URL base para las solicitudes estándar de la API.
     * @param string  $urlTupay                     URL base para las solicitudes específicas de TuPay.
     * @param string  $x_login                      Credencial de inicio de sesión para la API.
     * @param string  $x_trans_key                  Clave de transacción para la API.
     * @param string  $x_login_for_webpaystatus     Credencial de inicio de sesión para consultar el estado de pagos.
     * @param string  $x_trans_key_for_webpaystatus Clave de transacción para consultar el estado de pagos.
     * @param string  $secret_key                   Clave secreta utilizada para generar el control hash.
     * @param boolean $keyTuPay                     Indica si se deben usar las URLs de TuPay.
     */
    public function __construct(
        $Url = '',
        $urlTupay = '',
        $x_login = '',
        $x_trans_key = '',
        $x_login_for_webpaystatus = '',
        $x_trans_key_for_webpaystatus = '',
        $secret_key = '',
        $keyTuPay = false
    ) {
        $this->errors = 0;

        if ($x_login != '') {
            $this->x_login = $x_login;
        }

        if ($x_trans_key != '') {
            $this->x_trans_key = $x_trans_key;
        }

        if ($x_login_for_webpaystatus != '') {
            $this->x_login_for_webpaystatus = $x_login_for_webpaystatus;
        }

        if ($x_trans_key_for_webpaystatus != '') {
            $this->x_trans_key_for_webpaystatus = $x_trans_key_for_webpaystatus;
        }

        if ($secret_key != '') {
            $this->secret_key = $secret_key;
        }

        if ($keyTuPay == true) {
            $this->url['newinvoice'] = $urlTupay . '/v3/deposits';
        } else {
            $this->url['newinvoice'] = $Url . '/api_curl/streamline/newinvoice';
            $this->url['status'] = $Url . '/apd/webpaystatus';
            $this->url['exchange'] = $Url . '/apd/webcurrencyexchange';
            $this->url['banks'] = $Url . '/api_curl/apd/get_banks_by_country';
        }
    }

    /**
     * Crea una nueva factura utilizando la API de Directa24.
     *
     * Este metodo envía una solicitud para generar una nueva factura con los parámetros proporcionados.
     * Se genera un hash de control para garantizar la seguridad de la transacción.
     *
     * @param string $invoice          Identificador único de la factura.
     * @param float  $amount           Monto de la factura.
     * @param string $bank             Código del banco utilizado para el pago.
     * @param string $country          Código del país (ISO 3166-1 alfa-2).
     * @param int    $iduser           Identificador del usuario.
     * @param string $cpf              Documento de identificación del usuario (por ejemplo, CPF en Brasil).
     * @param string $name             Nombre completo del usuario.
     * @param string $email            Correo electrónico del usuario.
     * @param string $currency         Opcional Moneda utilizada en la transacción.
     * @param string $description      Opcional Descripción de la factura.
     * @param string $bdate            Opcional Fecha de nacimiento del usuario.
     * @param string $address          Opcional Dirección del usuario.
     * @param string $zip              Opcional Código postal del usuario.
     * @param string $city             Opcional Ciudad del usuario.
     * @param string $state            Opcional Estado o región del usuario.
     * @param string $return_url       Opcional URL de retorno después del pago.
     * @param string $confirmation_url Opcional URL para recibir confirmaciones de pago.
     *
     * @return mixed Respuesta de la API de Directa24.
     */
    public function newinvoice($invoice, $amount, $bank, $country, $iduser, $cpf, $name, $email, $currency = '', $description = '', $bdate = '', $address = '', $zip = '', $city = '', $state = '', $return_url = '', $confirmation_url = '')
    {
        $params_array = array(
            //Mandatory
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

        if ($_ENV['debug']) {
            print_r(json_encode($params_array));
            print_r("////----////");
            print_r("Entroooooooo");
        }

        return $response;
    }

    /**
     * Crea una nueva factura utilizando el servicio TuPay.
     *
     * Este metodo envía una solicitud para generar una nueva factura con los parámetros proporcionados.
     * Se genera un hash de control para garantizar la seguridad de la transacción.
     *
     * @param string  $invoice          Identificador único de la factura.
     * @param float   $amount           Monto de la factura.
     * @param string  $bank             Código del banco utilizado para el pago.
     * @param string  $country          Código del país (ISO 3166-1 alfa-2).
     * @param integer $iduser           Identificador del usuario.
     * @param string  $cpf              Documento de identificación del usuario (por ejemplo, CPF en Brasil).
     * @param string  $name             Nombre completo del usuario.
     * @param string  $email            Correo electrónico del usuario.
     * @param string  $currency         Opcional. Moneda utilizada en la transacción.
     * @param string  $description      Opcional. Descripción de la factura.
     * @param string  $bdate            Opcional. Fecha de nacimiento del usuario.
     * @param string  $address          Opcional. Dirección del usuario.
     * @param string  $zip              Opcional. Código postal del usuario.
     * @param string  $city             Opcional. Ciudad del usuario.
     * @param string  $state            Opcional. Estado o región del usuario.
     * @param string  $return_url       Opcional. URL de retorno después del pago.
     * @param string  $confirmation_url Opcional. URL para recibir confirmaciones de pago.
     * @param string  $first_name       Nombre del usuario.
     * @param string  $last_name        Apellido del usuario.
     * @param string  $formattedDate    Fecha formateada para la solicitud.
     *
     * @return mixed Respuesta de la API de TuPay.
     */
    public function newinvoiceTuPay($invoice, $amount, $bank, $country, $iduser, $cpf, $name, $email, $currency = '', $description = '', $bdate = '', $address = '', $zip = '', $city = '', $state = '', $return_url = '', $confirmation_url = '', $first_name, $last_name, $formattedDate)
    {
        /**
         * Convierte todos los valores de un arreglo a codificación UTF-8.
         *
         * @param array $array Arreglo a convertir.
         *
         * @return array Arreglo con los valores convertidos a UTF-8.
         */
        function convertArrayToUtf8($array)
        {
            array_walk_recursive($array, function (&$item, $key) {
                if (is_string($item)) {
                    $item = mb_convert_encoding($item, 'UTF-8', 'auto');
                }
            });
            return $array;
        }

        $data = [
            'country' => $country,
            'currency' => $currency,
            'amount' => $amount,
            'success_url' => $return_url,
            'payment_method' => $bank,
            'notification_url' => $confirmation_url,
            'payer' => [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'document' => $cpf,
                'email' => $email,
            ],
            'invoice_id' => $invoice,
        ];

        syslog(LOG_WARNING, "REQUEST TUPAY: " . (json_encode($data)));

        $params_array = convertArrayToUtf8($data);

        $xDate = $formattedDate;

        $xLogin = $this->x_login;
        $secretKey = $this->secret_key;

        $jsonPayload = json_encode($params_array);

        $data = $xDate . $xLogin . $jsonPayload;

        $hash = hash_hmac('sha256', $data, $secretKey);

        $response = $this->curlTuPay($this->url['newinvoice'], $params_array, $formattedDate, $hash, $xLogin);

        if ($_ENV['debug']) {
            print_r(json_encode($params_array));
            print_r(PHP_EOL);
            print_r("APIKEY: $xLogin");
            print_r(PHP_EOL);
            print_r("SECRETKEYTUPAY: $secretKey");
            print_r("////----////");
            print_r("Entroooooooo");
        }

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
     * @param string $country Código del país (por defecto 'BR').
     * @param float  $amount  Monto a convertir (por defecto 1).
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
     * @param string $country Código del país (por defecto 'BR').
     * @param string $type    Tipo de respuesta (json o xml) (por defecto 'json').
     *
     * @return array Respuesta de la API.
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
     * Realiza una solicitud cURL a la API de Directa24.
     *
     * @param string $url          URL de la API.
     * @param array  $params_array Parámetros de la solicitud.
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

    /**
     * Realiza una solicitud cURL a la API de TuPay.
     *
     * @param string $url           URL de la API.
     * @param array  $params_array  Parámetros de la solicitud.
     * @param string $formattedDate Fecha formateada para la solicitud.
     * @param string $hash          Hash de control para la solicitud.
     * @param string $secret_key    Clave secreta utilizada para generar el hash.
     *
     * @return string Respuesta de la API.
     */
    private function curlTuPay($url, $params_array, $formattedDate, $hash, $secret_key)
    {
        $header = [
            'X-Login: ' . $secret_key,
            'X-Date: ' . $formattedDate,
            'Authorization: D24 ' . $hash,
            'Content-Type: application/json'
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($params_array),
            CURLOPT_HTTPHEADER => $header,
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
