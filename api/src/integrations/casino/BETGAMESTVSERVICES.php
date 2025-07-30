<?php

/**
 * Clase BETGAMESTVSERVICES
 *
 * Esta clase proporciona servicios relacionados con juegos de casino para la integración con BETGAMESTV.
 * Incluye métodos para obtener información de juegos y gestionar tokens de usuario.
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
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase BETGAMESTVSERVICES
 *
 * Proporciona servicios relacionados con juegos de casino para la integración con BETGAMESTV.
 * Incluye métodos para obtener información de juegos y gestionar tokens de usuario.
 */
class BETGAMESTVSERVICES
{

    /**
     * Constructor de la clase BETGAMESTVSERVICES.
     *
     * Inicializa la configuración del entorno y realiza acciones específicas
     * si el entorno es de desarrollo.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        }
    }

    /**
     * Obtiene información de un juego y gestiona el token de usuario.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego (es, en, pt).
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en el sistema externo.
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con los parámetros del juego y el token de usuario.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid, $isMobile = false, $usumandanteId = "")
    {
        if ($lang == "en") {
            $lang = "en";
        } elseif ($lang == "pt") {
            $lang = "pt";
        } else {
            $lang = "es";
        }

        try {
            if ($usumandanteId == "") {
                if ($usuarioToken != '') {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }
            }

            $Proveedor = new Proveedor("", "BETGAMESTV");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            $tokenUser = '';
            if ($usumandanteId != '') {
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
                $tokenUser = $UsuarioToken->getToken();
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $JS_PARAM = $credentials->JS_PARAM;
            $SERVER_PARAM = $credentials->SERVER_PARAM;
            $PARTNER_PARAM = $credentials->PARTNER_PARAM;


            if ($tokenUser == "") {
                $tokenUser = '-';
            }
            if ($isMobile) {
                $array = array(
                    "error" => false,
                    "response" => $UsuarioToken->getToken(),
                );
                $array = array(
                    "error" => false,
                    "response" => array(
                        "token" => $tokenUser,
                        "serverParam" => $SERVER_PARAM,
                        "partnerParam" => $PARTNER_PARAM,
                        "languageParam" => $lang,
                        "timezoneParam" => '-5',
                        "jsURL" => $JS_PARAM,
                        "current_game" => $gameid
                    )
                );
            } else {
                $array = array(
                    "error" => false,
                    "response" => array(
                        "token" => $tokenUser,
                        "serverParam" => $SERVER_PARAM,
                        "partnerParam" => $PARTNER_PARAM,
                        "languageParam" => $lang,
                        "timezoneParam" => '-5',
                        "jsURL" => $JS_PARAM,
                        "current_game" => $gameid
                    )
                );
            }
            if ($_ENV['debug']) {
                print_r(json_decode(json_encode($array)));
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }
}
