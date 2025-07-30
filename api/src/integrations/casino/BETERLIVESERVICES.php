<?php

/**
 * Clase BETERLIVESERVICES
 *
 * Este archivo contiene la implementación de la clase BETERLIVESERVICES,
 * que se utiliza para gestionar la integración con el proveedor BETERLIVE.
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
use Backend\dto\Pais;
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
 * Clase BETERLIVESERVICES
 *
 * Esta clase gestiona la integración con el proveedor BETERLIVE,
 * proporcionando métodos para interactuar con sus servicios.
 */
class BETERLIVESERVICES
{
    /**
     * Constructor de la clase BETERLIVESERVICES.
     *
     * Inicializa el entorno de configuración para determinar si se encuentra en desarrollo o producción.
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
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      Opcional ID alternativo del juego.
     * @param string  $ismobile      Opcional Indica si es un dispositivo móvil.
     * @param string  $usumandanteId Opcional ID del usuario mandante.
     *
     * @return object Respuesta con la URL del juego y otros datos.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid = "", $ismobile = '', $usumandanteId = "")
    {
        try {
            try {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "BETERLIVE");
                $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

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

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
                $Mandante = new Mandante($UsuarioMandante->getMandante());

                if ($Mandante->baseUrl != '') {
                    $URLREDIRECTION = $Mandante->baseUrl . "new-casino";
                }
                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $URL_API = $credentials->URL_API;
                $CID = $credentials->CID;

                $array = array(
                    "error" => false,
                    "response" => $URL_API . "?token=" . $UsuarioToken->getToken() . "&cid=" . $CID . "&username=" . "Usuario" . $UsuarioMandante->usumandanteId . "&launchalias=" . $gameid . "&lang=" . $lang . "&homepage=" . $URLREDIRECTION . "&brand=" . $Mandante->nombre
                );


                return json_decode(json_encode($array));
            } catch (Exception $e) {
            }
        } catch (Exception $e) {
        }
    }
}
