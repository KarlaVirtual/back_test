<?php

/**
 * Clase para integrar servicios de IMOON en el sistema.
 *
 * Este archivo contiene la implementación de la clase `IMOONSERVICES`, que
 * proporciona métodos para interactuar con la API de IMOON, incluyendo
 * la gestión de juegos, redirecciones y bonificaciones.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\integrations\casino\Game;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase principal para la integración de servicios de IMOON.
 *
 * Esta clase proporciona métodos para interactuar con la API de IMOON,
 * incluyendo la gestión de juegos, redirecciones y bonificaciones.
 */
class IMOONSERVICES
{
    /**
     * Token utilizado para la autenticación en la API.
     *
     * @var string
     */
    private $Token = '';

    /**
     * URL base utilizada para las solicitudes a la API.
     *
     * @var string
     */
    private $URL = '';

    /**
     * Constructor de la clase IMOONSERVICES.
     *
     * Inicializa las variables de entorno dependiendo de si el sistema
     * está en modo desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la URL de redirección para un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $LaunchId      ID de lanzamiento.
     * @param boolean $isMobile      Indica si el dispositivo es móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL de redirección.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $LaunchId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "IMOON");
            $Producto = new Producto('', $gameid, $Proveedor->proveedorId);

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $UsuarioToken->setToken($UsuarioToken->createToken());
                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                $UsuarioToken->setProductoId($Producto->productoId);
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($usumandanteId);
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
                    $UsuarioToken->setSaldo(0);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
            $this->URL = $Credentials->URL;
            $this->Token = $Credentials->TOKEN;

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $ip = explode(',', $this->get_client_ip());
            $ip = $ip[0];
            $Pais = new Pais($UsuarioMandante->paisId);
            $player = $UsuarioMandante->getUsumandanteId();
            $moneda = $responseG->moneda;
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));
            $token = $UsuarioToken->getToken();

            $data = array(
                "balance" => intval($saldo),
                "country" => $Pais->iso,
                "currency" => $moneda,
                "gameId" => $gameid,
                "ip" => $ip,
                "playerId" => $player,
                "playerToken" => $token,
                "uniqueId" => $token
            );

            $resp = $this->launch(json_encode($data));

            $array = array(
                "error" => false,
                "response" => $resp->url,
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Lanza una solicitud a la API para iniciar un juego.
     *
     * @param string $data Datos en formato JSON para la solicitud.
     *
     * @return object Respuesta de la API.
     */
    public function launch($data)
    {
        //Inicializar la clase CurlWrapper
        $curl = new CurlWrapper($this->URL . '/launch');

        $curl->setOptionsArray(array(
            CURLOPT_URL => $this->URL . '/launch',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'authorization: ' . $this->Token
            ),
        ));

        // Ejecutar la solicitud
        $response = $curl->execute();
        return json_decode($response);
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
     */
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }
}
