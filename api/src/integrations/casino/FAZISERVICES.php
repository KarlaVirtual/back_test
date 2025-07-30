<?php

/**
 * Clase FAZISERVICES
 *
 * Esta clase proporciona servicios relacionados con la integración de juegos de casino
 * para el proveedor FAZI. Incluye métodos para obtener información de juegos y gestionar
 * tokens de usuario.
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
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase FAZISERVICES
 *
 * Proporciona servicios para la integración de juegos de casino
 * del proveedor FAZI, incluyendo la gestión de tokens y generación
 * de URLs de integración.
 */
class FAZISERVICES
{
    /**
     * URL de redirección para el casino.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Constructor de la clase FAZISERVICES.
     *
     * Inicializa el entorno de configuración y realiza ajustes según el entorno
     * (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la información de un juego y genera la URL de integración.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo "jugar por diversión".
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en el sistema.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     *
     * @return object                 Respuesta con la URL de integración o un error.
     *
     * @throws Exception              Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid, $usumandanteId = "", $isMobile = false)
    {
        try {
            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => ''
                );
            } else {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "FAZI");
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

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
                $Mandante = new Mandante($UsuarioMandante->getMandante());

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
                }

                $stringMobile = "&platform=desktop";
                if ($isMobile) {
                    $stringMobile = "&platform=mobile";
                }

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $array = array(
                    "error" => false,
                    "response" => $Credentials->URL . 'token=' . $UsuarioToken->getToken() . '&integrationType=42&moneyType=1&gameName=' . $gameid . $stringMobile . '&lobbyUrl=' . $this->URLREDIRECTION . '&languageCode=spa&currency=' . $UsuarioMandante->moneda,
                );
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }
}
