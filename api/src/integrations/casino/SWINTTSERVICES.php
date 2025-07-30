<?php

/**
 * Clase SWINTTSERVICES
 *
 * Esta clase proporciona servicios relacionados con la integración de juegos del proveedor SWINTT.
 * Contiene métodos para gestionar tokens de usuario, obtener información de juegos y construir URLs
 * para redirección y depósito.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
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
use Backend\dto\ProveedorMandante;
use Backend\dto\Registro;
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
use \SoapClient;
use \SimpleXMLElement;

/**
 * Clase que proporciona servicios relacionados con la integración de juegos del proveedor SWINTT.
 * Contiene métodos para gestionar tokens de usuario, obtener información de juegos y construir URLs
 * para redirección y depósito.
 */
class SWINTTSERVICES
{
    /**
     * URL para redirección de usuarios.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * URL para la página de depósitos.
     *
     * @var string
     */
    private $URLDEPOSIT = '';

    /**
     * Constructor de la clase SWINTTSERVICES.
     *
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }

    /**
     * Obtiene la URL del juego para un usuario específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $productoId    ID del producto.
     * @param boolean $isMobile      Indica si el cliente es móvil (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($play_for_fun) {
            } else {
                $Proveedor = new Proveedor("", "SWINTT");

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId, "", "", $productoId);

                    $UsuarioToken->setToken($UsuarioToken->createToken());

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
                        $UsuarioToken->setProductoId($productoId);

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }

                $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                $Mandante = new Mandante($UsuarioMandante->mandante);
                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $credentials = json_decode($SubproveedorMandantePais->getCredentials());
                $token = $UsuarioToken->getToken();

                $Producto = new Producto($productoId, "", $Proveedor->getProveedorId());

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
                    $this->URLDEPOSIT = $Mandante->baseUrl . "gestion/deposito";
                }

                $Client = '';

                if ($isMobile) {
                    $Client = "mobile";
                } else {
                    $Client = "desktop";
                }

                $URL = $credentials->URL . 'gameloader' . "?operatorId=" . $credentials->partnerId . '&gameId=' . $Producto->externoId . '&languageId=' . $lang . '&client=' . $Client . '&funMode=0' . '&token=' . $token . '&lobbyUrl=' . $this->URLREDIRECTION . '&cashierUrl=' . $this->URLDEPOSIT;

                $array = array(
                    "error" => false,
                    "response" => $URL
                );

                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
        }
    }

}
