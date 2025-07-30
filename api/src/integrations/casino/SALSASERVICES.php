<?php

/**
 * Clase SALSASERVICES
 *
 * Esta clase proporciona servicios de integración con la plataforma de juegos Salsa.
 * Incluye métodos para gestionar listas de juegos, crear jugadores, verificar su existencia,
 * iniciar sesión y obtener URLs de juegos, entre otros.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\ConfigurationEnvironment;

/**
 * Clase que proporciona servicios de integración con la plataforma de juegos Salsa.
 */
class SALSASERVICES
{

    /**
     * ID del operador.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Constructor de la clase.
     * Configura la URL base dependiendo del entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }

    /**
     * Obtiene la URL de un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo gratuito.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del mandante del usuario (opcional).
     *
     * @return mixed Respuesta con la URL del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {


        try {
            $Proveedor = new Proveedor("", "PTG");
            $Producto = new Producto('', $gameid, $Proveedor->getProveedorId());
            $SubProveedor = new Subproveedor($Producto->subproveedorId);

            $LaunchURL = '';

            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }


            try {

                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
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

            $SubproveedorMandantePais = new SubproveedorMandantePais("", $SubProveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
            }

            $depositurl = $this->URLREDIRECTION;

            if ($SubProveedor->abreviado == 'HIGH5GAMES' || $SubProveedor->abreviado == 'PFBM' || $SubProveedor->abreviado == 'PZITRO') {

                if ($SubProveedor->abreviado == 'PFBM') {
                    $data = explode("gpi#", $gameid);
                    if ($data[0] == "") {
                        $gameid = $data[1];
                    }
                }

                $LaunchURL = $Credentials->URLHP;
                if ($SubProveedor->abreviado == 'PZITRO') {
                    $LaunchURL = $Credentials->URLZ;
                }

                $array = array(
                    "error" => false,
                    "response" => $LaunchURL . "&game=" . $gameid . "&token=" . $UsuarioToken->getToken() . "&depositurl=" . $depositurl . "&lang=" . strtolower($lang)
                );
            } else {

                $LaunchURL = $Credentials->URLP;

                if (is_string($gameid)) {
                    if (explode('gpi#', $gameid)[0] == "") {
                        $array = array(
                            "error" => false,
                            "response" => $LaunchURL . "&game=" . explode('gpi#', $gameid)[1] . "&token=" . $UsuarioToken->getToken() . "&depositurl=" . $depositurl . "&lang=" . strtolower($lang),
                        );
                    } else {
                        $array = array(
                            "error" => false,
                            "response" => $LaunchURL . "&game=" . $gameid . "&token=" . $UsuarioToken->getToken() . "&lang=" . strtolower($lang),
                        );
                    }
                } else {
                    $array = array(
                        "error" => false,
                        "response" => $LaunchURL . "&game=" . $gameid . "&token=" . $UsuarioToken->getToken() . "&lang=" . strtolower($lang),
                    );
                }
            }

            if ($_ENV['debug']) {
                print_r($array);
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }
}
