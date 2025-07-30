<?php

/**
 * Clase Hub
 *
 * Esta clase maneja la integración con el servicio Hub, permitiendo realizar consultas y transacciones específicas.
 *
 * @category Red
 * @package  Backend\integrations\general\hub
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-17
 */

namespace Backend\integrations\general\hub;

use Backend\dto\ConfigurationEnvironment;

/**
 * Clase Hub para la integración con el servicio de Hub de Desarrollador.
 *
 * Esta clase permite realizar consultas a la API de Hub, incluyendo la verificación de cédulas y la obtención de información del usuario.
 */
class Hub
{
    /**
     * URL base para el entorno actual.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL base para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://ws.hubdodesenvolvedor.com.br/v2/';

    /**
     * URL base para el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://ws.hubdodesenvolvedor.com.br/v2/';

    /**
     * Login para el entorno actual.
     *
     * @var string
     */
    private $login = '';

    /**
     * Login para el entorno de desarrollo.
     *
     * @var string
     */
    private $loginDEV = 'financeiromilbets@gmail.com';

    /**
     * Login para el entorno de producción.
     *
     * @var string
     */
    private $loginPRO = 'financeiromilbets@gmail.com';

    /**
     * Contraseña para el entorno actual.
     *
     * @var string
     */
    private $password = "";

    /**
     * Contraseña para el entorno de desarrollo.
     *
     * @var string
     */
    private $passwordDEV = "Milbets$1727";

    /**
     * Contraseña para el entorno de producción.
     *
     * @var string
     */
    private $passwordPRO = "Milbets$1727";

    /**
     * Token para el entorno actual.
     *
     * @var string
     */
    private $token = "";

    /**
     * Token para el entorno de desarrollo.
     *
     * @var string
     */
    private $tokenDev = "120313460VclUQryRzS217222304";

    /**
     * Token para el entorno de producción.
     *
     * @var string
     */
    private $tokenProd = "120313460VclUQryRzS217222304";

    /**
     * Constructor de la clase Hub.
     *
     * Inicializa las variables de entorno según el entorno de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->login = $this->loginDEV;
            $this->password = $this->passwordDEV;
            $this->token = $this->tokenDev;
            $this->URL = $this->URLDEV;
        } else {
            $this->login = $this->loginPRO;
            $this->password = $this->passwordPRO;
            $this->token = $this->tokenProd;
            $this->URL = $this->URLPROD;
        }
    }

    /**
     * Consulta información de un usuario por cédula.
     *
     * @param string $cedula          Número de cédula del usuario.
     * @param string $rid             Identificador único de la transacción.
     * @param string $fechaNacimiento Fecha de nacimiento del usuario.
     *
     * @return array Datos de la transacción, incluyendo nombre, apellido y fecha de nacimiento.
     */
    public function cedula($cedula, $rid, $fechaNacimiento)
    {
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $data["code"] = 1;


        $dataB = "?cpf=$cedula&data=" . $fechaNacimiento . "&token=" . $this->token;


        syslog(LOG_WARNING, "Hub DATA" . $dataB);

        $Result = $this->connection($dataB, $this->URL, 'cpf/');

        syslog(LOG_WARNING, "Hub RESPONSE" . json_encode($Result));


        if ($Result != '' && $Result->status == true) {
            $nombre = explode(' ', $Result->result->nome_da_pf);


            $first_name = $nombre[0];
            $last_name = implode(' ', array_slice($nombre, 1));
            $data = array();
            $data["code"] = 0;
            $data["rid"] = $rid;

            $data["data"] = array();
            array_push($data["data"], array(
                'cpf' => $Result->result->numero_de_cpf,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'birth_date' => $Result->result->data_nascimento,

            ));
        }

        return ($data);
    }

    /**
     * Genera un token de autenticación con el proveedor.
     *
     * @param array  $data Datos necesarios para la autenticación.
     * @param string $url  URL base del proveedor.
     * @param string $path Ruta específica para la generación del token.
     *
     * @return object Respuesta del proveedor con el token generado.
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

    /**
     * Establece una conexión CURL con el proveedor.
     *
     * @param string $data Datos necesarios para la consulta o transacción.
     * @param string $url  URL base del proveedor.
     * @param string $path Ruta específica para el proceso requerido.
     *
     * @return object Respuesta del proveedor con los datos solicitados.
     */
    public function connection($data, $url, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path . $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER => false,
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        return json_decode($response);
    }
}