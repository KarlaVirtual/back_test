<?php

/**
 * Este archivo contiene la clase `VIBRAGAMINGSERVICES`, que proporciona servicios de integración
 * con la plataforma de juegos Vibragaming. Incluye métodos para obtener información de juegos
 * y gestionar tokens de usuario.
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
 * Clase `VIBRAGAMINGSERVICES`
 *
 * Proporciona métodos para interactuar con la plataforma Vibragaming, incluyendo
 * la obtención de juegos y la gestión de tokens de usuario.
 */
class VIBRAGAMINGSERVICES
{
    /**
     * URL de redirección utilizada en las respuestas.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Constructor de la clase.
     *
     * Inicializa el entorno de configuración y realiza configuraciones
     * específicas según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la URL de un juego específico en la plataforma Vibragaming.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego (por ejemplo, 'en', 'es', 'pt').
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión (true) o real (false).
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $LaunchId      ID de lanzamiento del juego.
     * @param boolean $isMobile      Indica si el juego se ejecuta en un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego y otros datos relevantes.
     *
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $LaunchId, $isMobile = false, $usumandanteId = "")
    {
        $GameMode = "";

        if ($play_for_fun == true) {
            $GameMode = "FUN";
        } else {
            if ($play_for_fun == false) {
                $GameMode = "REAL";
            }
        }

        if ($isMobile == false) {
            $channel = 'desktop';
        } else {
            if ($isMobile == true) {
                $channel = 'mobile';
            }
        }

        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "VIBRAGAMING");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId, "", "", $Producto->productoId);
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

            if ($lang == 'pt') {
                $lang = 'pt_br';
            }

            $this->URLREDIRECTION = $Mandante->baseUrl . 'new-casino/proveedor/VIBRAGAMING';

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $SITE_ID = $credentials->SITE_ID;
            $URL = $credentials->URL;

            $array = array(
                "error" => false,
                "response" => $URL . 'siteId=' . $SITE_ID . '&gameMode=' . $GameMode . '&currency=' . $UsuarioMandante->moneda . '&channel=' . $channel . '&locale=' . $lang . '&gameId=' . $gameid . '&userId=' . $UsuarioMandante->getUsumandanteId() . '&lobbyURL=' . $this->URLREDIRECTION . '&token=' . $UsuarioToken->getToken() . '&lobbyTarget=_top',
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }
}
