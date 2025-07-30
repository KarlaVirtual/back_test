<?php

/**
 * Clase que proporciona servicios de integración con VivoGaming.
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
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use phpDocumentor\Reflection\DocBlock\Location;

/**
 * Clase VIVOGAMINGSERVICES
 *
 * Proporciona métodos para gestionar la integración con los servicios de VivoGaming.
 */
class VIVOGAMINGSERVICES
{
    /**
     * URL de redirección utilizada en los servicios.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Constructor de la clase.
     *
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
     * Obtiene la URL del juego basado en los parámetros proporcionados.
     *
     * @param integer $gameid        ID del juego.
     * @param string  $lang          Idioma del juego (es, en, pt).
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en el sistema.
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid, $isMobile = false, $usumandanteId = "")
    {
        // Validación del idioma
        if ($lang == "en") {
            $lang = "en";
        } elseif ($lang == "pt") {
            $lang = "pt";
        } else {
            $lang = "es";
        }

        try {
            // Obtención del ID del usuario mandante si no se proporciona
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "VIVOGAMING");
            $Producto = new Producto("", $gameid, $Proveedor->proveedorId);

            try {
                // Creación y actualización del token del usuario
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken(substr($token, 0, strlen($token) - strlen("vssv" . $migameid)) . "vssv" . $migameid);
                $UsuarioToken->setProductoId($Producto->productoId);
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                // Manejo de errores específicos
                if ($e->getCode() == 21) {
                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($usumandanteId);
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken(substr($token, 0, strlen($token) - strlen("vssv" . $migameid)) . "vssv" . $migameid);
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
            $UsuarioMandante = new UsuarioMandante($usumandanteId);
            try {
                // Obtención de detalles del producto
                $ProductoDetalle = new ProductoDetalle("", $Producto->productoId, 'GAMEID');
                $gameid2 = $ProductoDetalle->pValue;
            } catch (Exception $e) {
                $gameid2 = "roulette";
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $URL = $credentials->URL;
            $URL_BINGO = $credentials->URL_BINGO;
            $SERVER_ID = $credentials->SERVER_ID;
            $OPERATOR_ID = $credentials->OPERATOR_ID;

            // Construcción de la URL de respuesta según el dispositivo
            if ($isMobile) {
                if ($gameid == 257) {
                    $array = array(
                        "error" => false,
                        "response" => $URL_BINGO . '?Tableguid=JKF3407JKFDFJFKDD3EEWWD33' . '&Token=' . $UsuarioToken->getToken() . '&operatorID=' . $OPERATOR_ID . '',
                    );
                } else {
                    $array = array(
                        "error" => false,
                        "response" => $URL . '?token=' . $UsuarioToken->getToken() . '&tableId=' . $gameid . '&operatorID=' . $OPERATOR_ID . '&ServerID=' . $SERVER_ID . '&application=' . $gameid2 . '&language=' . $lang . '',
                    );
                }
            } else {
                if ($gameid == 257) {
                    $array = array(
                        "error" => false,
                        "response" => $URL_BINGO . '?Tableguid=JKF3407JKFDFJFKDD3EEWWD33' . '&Token=' . $UsuarioToken->getToken() . '&operatorID=' . $OPERATOR_ID . '',
                    );
                } else {
                    $array = array(
                        "error" => false,
                        "response" => $URL . '?token=' . $UsuarioToken->getToken() . '&tableId=' . $gameid . '&operatorID=' . $OPERATOR_ID . '&ServerID=' . $SERVER_ID . '&application=' . $gameid2 . '&language=' . $lang . '',
                    );
                }
            }

            // Redirección especial para un usuario específico
            if ($UsuarioToken->usuarioId == '45340') {
                header("Location: " . 'https://games.vivogaming.com/?token=ICE2016-2&operatorid=1453&&serverid=51681981&language=' . $lang . '&Application=' . $gameid2);
                die();
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }
}
