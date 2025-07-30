<?php

/**
 * Clase ContainerMedia
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-03-19
 */

namespace Backend\integrations\crm;

use Backend\dto\BonoInterno;
use Backend\dto\CategoriaMandante;
use Backend\dto\CategoriaProducto;
use Backend\dto\Ciudad;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\dto\SubproveedorMandante;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;

/**
 *  Esta clase maneja la integración con el sistema ContainerMedia, proporcionando métodos para realizar diversas
 *  operaciones como autenticación y envío de datos en tiempo real.
 */
class ContainerMedia
{
    /**
     * Nombre de usuario para el entorno actual.
     *
     * @var string.
     */
    private $username = "";

    /**
     * Nombre de usuario para el entorno de desarrollo.
     *
     * @var string.
     */
    private $usernameDEV = "";

    /**
     * Nombre de usuario para el entorno de producción.
     *
     * @var string.
     */
    private $usernamePROD = "";

    /**
     * URL para el entorno actual.
     *
     * @var string.
     */
    private $URL = "";

    /**
     * URL para el entorno de desarrollo.
     *
     * @var string.
     */
    private $URLDEV = 'https://ctag.containermedia.net/api/s2s/secure/';

    /**
     * URL de autenticación para el entorno de desarrollo.
     *
     * @var string.
     */
    private $URLDEVAUTH = "";

    /**
     * URL para el entorno de producción.
     *
     * @var string.
     */
    private $URLPROD = 'https://ctag.containermedia.net/api/s2s/secure/';

    /**
     * URL de autenticación para el entorno de producción.
     *
     * @var string.
     */
    private $URLPRODAUTH = "";

    /**
     * Token para autenticación.
     *
     * @var string.
     */
    private $token = "";

    /**
     * Metodo para la solicitud.
     *
     * @var string.
     */
    private $metodo = "";

    /**
     * URL de callback para el entorno actual.
     *
     * @var string.
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string.
     */
    private $callback_urlDEV = "";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string.
     */
    private $callback_urlPROD = "";

    /**
     * Constructor de la clase ContainerMedia.
     *
     * Inicializa las variables de entorno según el entorno de desarrollo o producción.
     */
    function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->username = $this->usernameDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->URL = $this->URLDEV;
            $this->URLAUTH = $this->URLDEVAUTH;
        } else {
            $this->username = $this->usernamePROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->URL = $this->URLPROD;
            $this->URLAUTH = $this->URLPRODAUTH;
        }
    }

    /**
     * Envía datos en tiempo real a Optimove.
     *
     * @param object $data Datos a enviar.
     *
     * @return mixed Respuesta de la solicitud.
     */
    function EventRealTimeOptimove($data)
    {
        $headers = array(
            'Content-type: application/json',
        );
        $data = str_replace(
            ' ',
            '%20',
            "?id=6503240d9bf9c23958d8a9d5&campaignID=$data->campaignID&channelID=$data->channelID&userId=$data->userId&name=$data->name&casinoUserId=$data->casinoUserId&email=$data->email&phone=$data->phone&country=$data->country&partner=$data->partner&device=$data->device"
        );

        $time = time();
        syslog(LOG_WARNING, "REQUEST CONTAINERMEDIA : " . $time . ' ' . $data);
        $curl = curl_init($this->URL . $data);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        syslog(LOG_WARNING, "RESPUESTA CONTAINERMEDIA  : " . $result);
        curl_close($curl);

        return $result;
    }

    /**
     * Envía datos a Optimove.
     *
     * @param object $data Datos a enviar.
     *
     * @return mixed Respuesta de la solicitud.
     */
    function EventOptimove($data)
    {
        $headers = array(
            'Content-type: application/json',
        );
        $data = str_replace(
            ' ',
            '%20',
            "id=650324029bf9c23958d8a92e&campaignID=$data->campaignID&channelID=$data->channelID&userId=$data->userId&name=$data->name&casinoUserId=$data->casinoUserId&email=$data->email&phone=$data->phone&country=$data->country&partner=$data->partner"
        );

        $time = time();
        syslog(LOG_WARNING, "REQUEST CONTAINERMEDIA : " . $time . ' ' . $data);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, ($this->URL . '?' . $data));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $result = curl_exec($ch);
        curl_close($ch);
        print_r(($this->URL . $data));
        print_r($result);
        syslog(LOG_WARNING, "RESPUESTA CONTAINERMEDIA  : " . $result);

        return $result;
    }

    /**
     * Genera un hash HMAC para los datos proporcionados.
     *
     * @param string $data Datos a encriptar.
     *
     * @return string Hash HMAC generado.
     */
    function Encrypta($data)
    {
        $data = json_decode($data);
        $Hash = hash_hmac('sha256', $data, $this->token);
        return $Hash;
    }

    /**
     * Realiza una solicitud de autenticación.
     *
     * @param string $data Datos a enviar.
     *
     * @return mixed Respuesta de la solicitud.
     */
    function connectionAutentica($data)
    {
        $data = json_encode($data);

        $curl = curl_init($this->URL . $this->metodo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    /**
     * /**
     * Realiza una solicitud GET.
     *
     * @return mixed Respuesta de la solicitud.
     */
    function connectionGET()
    {
        $headers = array(
            'Content-type: application/json',
            'X-Optimove-Signature-Version: 1',
            'X-Optimove-Signature-Content:' . $this->Auth
        );

        $curl = curl_init($this->URL . $this->metodo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
     * /**
     * Realiza una solicitud POST.
     *
     * @param string $data Datos a enviar.
     *
     * @return mixed Respuesta de la solicitud.
     */
    function connectionPOST($data)
    {
        $headers = array(
            'Content-type: application/json',
        );

        $time = time();
        syslog(LOG_WARNING, "REQUEST CONTAINERMEDIA : " . $time . ' ' . $data);
        $curl = curl_init($this->URL);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $result = curl_exec($curl);
        syslog(LOG_WARNING, "RESPUESTA CONTAINERMEDIA  : " . $result);
        curl_close($curl);
        return $result;
    }


    /**
     * /**
     * Realiza una solicitud PUT.
     *
     * @param string $data Datos a enviar.
     *
     * @return mixed Respuesta de la solicitud.
     */
    function connectionPUT($data)
    {
        $data = json_encode($data);

        $headers = array(
            'Authorization:  ' . $this->Auth,
            'Content-type: application/json',
            'Accept: application/json'
        );

        $curl = curl_init($this->URL . $this->metodo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);

        curl_close($result);
        return $result;
    }
}
