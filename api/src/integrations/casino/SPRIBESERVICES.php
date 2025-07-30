<?php

/**
 * Clase para integrar servicios de SPRIBE en el sistema.
 *
 * Este archivo contiene la implementación de la clase `SPRIBESERVICES`,
 * que permite gestionar la integración con los servicios de SPRIBE,
 * incluyendo la configuración de entornos, generación de claves y
 * obtención de juegos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
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
 * Clase SPRIBESERVICES
 *
 * Proporciona métodos para interactuar con los servicios de SPRIBE,
 * incluyendo la configuración de entornos y la obtención de juegos.
 */
class SPRIBESERVICES
{
    /**
     * Constructor de la clase SPRIBESERVICES.
     *
     * Configura las variables de entorno dependiendo de si el entorno
     * es de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la URL para lanzar un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en el sistema.
     * @param boolean $isMobile      Indica si el usuario está en un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid, $isMobile = false, $usumandanteId = "")
    {
        try {
            $currency = "USD";
            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => '' . $gameid . "?currency=" . $currency . "&lang=" . $lang
                );
                return json_decode(json_encode($array));
            } else {

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "SPRIBE");
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

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $currency = $UsuarioMandante->moneda;

                $array = array(
                    "error" => false,
                    "response" => $Credentials->URL . $gameid . "?user=" . $usumandanteId . "&token=" . $UsuarioToken->getToken() . "&lang=" . $lang . "&currency=" . $currency . "&operator=" . $Credentials->OPERATOR_KEY
                );

                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
        }
    }
}
