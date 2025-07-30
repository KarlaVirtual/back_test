<?php

/**
 * Clase Flynode para manejar la integración con la API de Flynode.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-18
 */

namespace Backend\integrations\mensajeria;

use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Proveedor;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use \CurlWrapper;

/**
 * Clase Flynode para manejar la integración con la API de Flynode.
 */
class Flynode
{
    /**
     * URL de la API de Flynode.
     *
     * @var string
     */
    private $URL = 'https://api.flynode.net/v2/messaging/sms';

    /**
     * Clave de API para autenticación.
     *
     * @var string
     */
    private $APIKEY = 'b4m9loqBDXYmiGjIBJMyRgyut3nvJVEoLAkE';

    /**
     * Token de API para autenticación.
     *
     * @var string
     */
    private $APITOKEN = '9JNujTWbOJs2LjDggHaa6bNE028eyVXzRCaO';

    /**
     * Constructor de la clase Flynode.
     */
    public function __construct()
    {
    }

    /**
     * Envía un mensaje SMS utilizando la API de Flynode.
     *
     * @param string         $phone          Número de teléfono del destinatario (sin el prefijo de país).
     * @param string         $message        Contenido del mensaje a enviar.
     * @param UsuarioMensaje $UsuarioMensaje Objeto que contiene información del usuario y mensaje.
     *
     * @return string Respuesta de la API de Flynode.
     *
     * @throws Exception Si ocurre un error al procesar la solicitud o al decodificar la respuesta.
     */
    public function sendMessage($phone, $message, UsuarioMensaje $UsuarioMensaje)
    {
        $Proveedor = new Proveedor("", "FLYNODE");
        $UsuarioMandante = new UsuarioMandante($UsuarioMensaje->getUsutoId());
        $mandante = new Mandante($UsuarioMandante->mandante);
        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

        $Pais = new Pais($Usuario->paisId);

        switch ($UsuarioMandante->mandante) {
            case 22:
                $countryCode = $Pais->prefijoCelular; // Código de país para México
                $countryCode = str_replace("+", "", $countryCode);
                break;

            case 25:
                $countryCode = $Pais->prefijoCelular; // Código de país para México
                $countryCode = str_replace("+", "", $countryCode);

                $this->APIKEY = 'QGGqo2ezN1farZD8SVqVIR8PCQw2bzXGSJmL';
                $this->APITOKEN = 'FF7y4e2IzjrmIx401295aUR0qbrIEFLQyV8O';
                break;

            case 6:
                $countryCode = $Pais->prefijoCelular; // Código de país para México
                $countryCode = str_replace("+", "", $countryCode);

                $this->APIKEY = '0ZB7xK5mJ37346tGCqTMWF07usWQ3yFb3Bsz';
                $this->APITOKEN = 'xnfRHlCPAb9Ip391zj3KMTpftm9EQyY02X0j';
                break;
        };


        $arrayReq = array(
            "api_key" => $this->APIKEY,
            "api_token" => $this->APITOKEN,
            "destination" => $countryCode . $phone,
            "message" => $message,
        );

        $request = json_encode($arrayReq);

        syslog(LOG_WARNING, "FLYNODEDATA : " . $request);

        // Inicializar la clase CurlWrapper
        $curl = new CurlWrapper($this->URL);

        // Configurar opciones
        $curl->setOptionsArray(array(
            CURLOPT_URL => $this->URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        // Ejecutar la solicitud
        $response = $curl->execute();

        if ($response === false) {
            throw new Exception("Error al ejecutar la solicitud CURL: " . $curl->getError());
        }

        syslog(LOG_WARNING, "FLYNODERESPONSE : " . $response);
        $UsuarioMensaje->setValor1($request . $response);

        $result = json_decode($response);

        $UsuarioMensaje->setExternoId($result->msgId);
        $UsuarioMensaje->setProveedorId($Proveedor->getProveedorId());

        return $UsuarioMensaje;
    }
}
