<?php

/**
 * Clase TVBETSERVICES
 *
 * Esta clase proporciona servicios relacionados con el proveedor de juegos "TVBET".
 * Contiene métodos para gestionar tokens de usuario, obtener información de juegos
 * y manejar credenciales de subproveedores.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use \SoapClient;
use Backend\dto\Pais;
use \SimpleXMLElement;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\dto\ProveedorMandante;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase TVBETSERVICES
 *
 * Proporciona servicios relacionados con el proveedor de juegos "TVBET".
 * Incluye métodos para gestionar tokens de usuario, obtener información de juegos
 * y manejar credenciales de subproveedores.
 */
class TVBETSERVICES
{
    /**
     * Constructor de la clase TVBETSERVICES.
     *
     * Inicializa el entorno de configuración para determinar si se está
     * ejecutando en un entorno de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la información de un juego específico.
     *
     * Este método gestiona tokens de usuario, valida credenciales de subproveedores
     * y genera la URL necesaria para acceder al juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo "jugar por diversión".
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $productoId    ID del producto.
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object                 Respuesta con la información del juego.
     * @throws Exception              Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            $Proveedor = new Proveedor("", "TVBET");
            if ($usumandanteId == "") {
                $UsuarioMandante = new UsuarioMandante($usumandanteId);
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            try {
                $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());
                $ProductoDetalle = new ProductoDetalle('', $Producto->productoId, 'GAMEID');
                $prod = $ProductoDetalle->pValue;
                $Jacktop = strtoupper($prod);
            } catch (Exception $e) {
                $Jacktop = "0";
            }

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $UsuarioToken->setEstado('I');
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

            $UsuarioToken->setToken($UsuarioToken->createToken());
            $UsuarioToken->setProductoId($Producto->productoId);
            $UsuarioToken->setEstado('A');

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->insert($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $CLIENT_ID_LIVE = $credentials->CLIENT_ID_LIVE;
            $URL_LIVE = $credentials->URL_LIVE;

            $CLIENT_ID = $credentials->CLIENT_ID;
            $URL = $credentials->URL;

            if ($Jacktop == 'JACKTOP') {
                $CLIENT_ID_LIVE = $credentials->CLIENT_ID_LIVE;
                $URL_LIVE = $credentials->URL_LIVE;
            } else {
                $CLIENT_ID = $credentials->CLIENT_ID;
                $URL = $credentials->URL;
            }

            if ($Jacktop == 'JACKTOP') {
                $url = 'https://web.jacktop.win';
                $array = array(
                    "error" => false,
                    "response" => array(
                        "proveedor" => 'TVBET',
                        "url" => $url . "/assets/jacktop-frame.js",
                        "lng" => $lang,
                        "clientId" => $CLIENT_ID_LIVE,
                        "tokenAuth" => $UsuarioToken->getToken(),
                        "server" => $URL_LIVE,
                        "floatTop" => '#fTop',
                        "containerId" => 'jacktop-game',
                        "game_id" => $gameid
                    )
                );
            } else {
                $url = $URL;
                $array = array(
                    "error" => false,
                    "response" => array(
                        "proveedor" => 'TVBET',
                        "url" => $url . "/assets/frame.js",
                        "lng" => $lang,
                        "clientId" => $CLIENT_ID,
                        "tokenAuth" => $UsuarioToken->getToken(),
                        "server" => $url,
                        "floatTop" => '',
                        "containerId" => 'tvbet-iframe',
                        "game_id" => $gameid
                    )
                );
            }
            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }
}
