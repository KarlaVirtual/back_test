<?php

/**
 * Este archivo contiene la clase EVERYMATRIXSERVICES, que proporciona métodos para interactuar
 * con servicios de juegos, incluyendo la obtención de URLs de juegos y el lanzamiento de solicitudes
 * a través de cURL.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase EVERYMATRIXSERVICES.
 * Proporciona métodos para interactuar con servicios de juegos, como la obtención de URLs de juegos
 * y el manejo de solicitudes HTTP.
 */
class EVERYMATRIXSERVICES
{
    /**
     * URL de redirección utilizada en las operaciones de la clase.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * Constructor de la clase EVERYMATRIXSERVICES.
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la URL de un juego basado en los parámetros proporcionados.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID del mini juego.
     * @param boolean $isMobile      Indica si el juego es para dispositivos móviles.
     * @param string  $usumandanteId ID del usuario mandante.
     * @param boolean $minigame      Indica si es un mini juego.
     *
     * @return object Respuesta con la URL del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun = false, $UsuarioToken, $migameid, $isMobile = false, $usumandanteId = "", $minigame = false)
    {
        try {
            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => '' . 'Loader/Start/' . '' . "/" . $gameid . "&language=" . strtolower($lang) . "&funMode=True&casinolobbyurl=" . $this->URLREDIRECTION,
                );
            } else {
                $Proveedor = new Proveedor("", "EVERYMATRIX");
                $Producto = new Producto('', $gameid, $Proveedor->proveedorId);

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($UsuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

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
                        $UsuarioToken->setToken($UsuarioToken->createToken());
                        $UsuarioToken->setSaldo(0);
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

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $url = $Credentials->URL . 'Loader/Start/' . $Credentials->OPERATOR_ID . "/" . $gameid . "?language=" . strtolower($lang) . "&funMode=False&_sid=" . $UsuarioToken->token . "&casinolobbyurl=" . $this->URLREDIRECTION;

                $array = array(
                    "error" => false,
                    "response" => $url
                );
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Lanza una solicitud HTTP POST a una URL específica utilizando cURL.
     *
     * @param string $url         URL a la que se enviará la solicitud.
     * @param string $OPERATOR_ID ID del operador.
     * @param array  $data        Datos a enviar en el cuerpo de la solicitud.
     *
     * @return string Respuesta de la solicitud.
     */
    public function LaunchUrl($url, $OPERATOR_ID, $data)
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
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-OPERATOR-ID: ' . $OPERATOR_ID,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
