<?php

/**
 * Esta clase proporciona servicios relacionados con la integración de juegos y usuarios
 * para la plataforma MOBADOO
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */

namespace Backend\integrations\virtual;

use Exception;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase MOBADOOSERVICES
 *
 * Esta clase proporciona servicios relacionados con la integración de juegos y usuarios
 * para la plataforma MOBADOO. Incluye métodos para gestionar listas de juegos, obtener
 * información de juegos, realizar solicitudes a la API y generar claves.
 */
class MOBADOOSERVICES
{

    /**
     * Constructor de la clase.
     *
     * Configura las URLs de redirección y el modo sandbox según el entorno.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene información de un juego específico.
     *
     * @param string      $gameid           ID del juego.
     * @param string      $lang             Idioma solicitado.
     * @param boolean     $play_for_fun     Indica si el juego es en modo "jugar por diversión".
     * @param string      $usuarioToken     Token del usuario.
     * @param object|null $ProductoMandante Información del producto mandante (opcional).
     * @param boolean     $isMobile         Indica si el cliente es móvil.
     * @param string      $usumandanteId    ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la información del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $ProductoMandante = null, $isMobile = false, $usumandanteId = "")
    {
        try {
            $Proveedor = new Proveedor("", "MOBADOO");
            $Producto = new Producto('', $gameid, $Proveedor->proveedorId);
            $ProductoDetalle = new ProductoDetalle('', $ProductoMandante, 'GAMEID');
            $landing = $ProductoDetalle->pValue;

            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
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

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
            $Mandante = new Mandante($UsuarioMandante->getMandante());

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $profile = $Mandante->nombre;
            $hash = $UsuarioToken->getToken();

            $sandbox = "";
            $ConfigurationEnvironment = new ConfigurationEnvironment();
            if ($ConfigurationEnvironment->isDevelopment()) {
                $sandbox = "sandbox: " . $Credentials->SANDBOX . ",";
            }

            try {
                $array = array(
                    "error" => false,
                    "response" => array(
                        "proveedor" => "MOBADOO",
                        "profile" => strtolower($profile),
                        "client_uid" => $Credentials->CLIENT_UID,
                        "hash" => $hash,
                        "sandbox" => $sandbox,
                        "script" => $Credentials->URL,
                        "landing" => $landing,
                        "lang" => $lang
                    ),
                );

                return json_decode(json_encode($array));
            } catch (Exception $e) {
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * @return string Dirección IP del cliente.
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
