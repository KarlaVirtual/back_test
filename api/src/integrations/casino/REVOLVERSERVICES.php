<?php

/**
 * Clase para integrar servicios del proveedor REVOLVER en el sistema.
 *
 * Este archivo contiene la implementación de métodos para gestionar la integración
 * con el proveedor REVOLVER, incluyendo la obtención de juegos y la conexión
 * con sus servicios mediante solicitudes HTTP.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase principal para manejar los servicios de REVOLVER.
 */
class REVOLVERSERVICES
{
    /**
     * URL de redirección utilizada para los servicios de REVOLVER.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Constructor de la clase REVOLVERSERVICES.
     *
     * Inicializa la configuración del entorno para determinar si se encuentra
     * en un entorno de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene un juego del proveedor REVOLVER.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo gratuito.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en el sistema.
     * @param boolean $isMobile      Indica si el juego es para dispositivos móviles.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "REVOLVER");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken($token);
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
                    $UsuarioToken->setEstado('A');
                    $UsuarioToken->setProductoId($Producto->productoId);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
            }

            if ($isMobile == true) {
                $variant = "mobile";
            } else {
                if ($isMobile == false) {
                    $variant = "desktop";
                }
            }

            if ($play_for_fun == false) {
                $freeplay = "false";
            } else {
                if ($play_for_fun == true) {
                    $freeplay = "true";
                }
            }


            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $OPERATOR = $credentials->OPERATOR;
            $URL = $credentials->URL;

            $url = $URL . '?operator=' . $OPERATOR . '&exit_url=' . $this->URLREDIRECTION . '&game=' . $gameid . '&token=' . $UsuarioToken->getToken() . '&lang=' . $lang . '&variant=' . $variant . '&freeplay=' . $freeplay;


            $respuesta = $this->connection($url);

            if ($_ENV['debug']) {
                print_r('URL: ');
                print_r($url);
                print_r('RESPONSE: ');
                print_r($respuesta);
            }
            $array = array(
                "error" => false,
                "response" => $respuesta->data->URL
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una conexión HTTP utilizando cURL.
     *
     * @param string $url URL a la que se realizará la solicitud.
     *
     * @return object Respuesta decodificada en formato JSON.
     */
    public function connection($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response);
    }


}

