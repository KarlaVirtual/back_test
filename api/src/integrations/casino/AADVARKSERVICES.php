<?php

/**
 * Este archivo contiene la clase `AADVARKSERVICES`, que proporciona servicios relacionados con la integración de juegos
 * y la gestión de tokens de usuario para el proveedor AADVARK.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;

/**
 * Clase `AADVARKSERVICES`.
 * Proporciona servicios relacionados con la integración de juegos y la gestión de tokens de usuario
 * para el proveedor AADVARK.
 */
class AADVARKSERVICES
{
    /**
     * Constructor de la clase `AADVARKSERVICES`.
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }

    /**
     * Obtiene la URL del juego o genera un token de usuario para el proveedor AADVARK.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo "jugar por diversión".
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en miniatura.
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     * @param boolean $minigame      Indica si es un mini-juego.
     *
     * @return object Respuesta con la URL del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $UsuarioToken, $migameid, $isMobile = false, $usumandanteId = "", $minigame = false)
    {
        $Proveedor = new Proveedor("", "AADVARK");
        $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

        try {
            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => ''
                );

                return json_decode(json_encode($array));
            } else {
                try {
                    if ($usumandanteId == "") {
                        $UsuarioTokenSite = new UsuarioToken($UsuarioToken, '0');
                        $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                    }

                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
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
                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $urlFinal = $credentials->URL . "/operators/" . $credentials->OPERATOR_UUID . "/providers/" . $credentials->PROVIDER . "/url?token=" . $UsuarioToken->getToken();

                $array = array(
                    "error" => false,
                    "response" => $urlFinal
                );

                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
        }
    }

    /**
     * Obtiene la dirección IP del cliente que realiza la solicitud.
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

