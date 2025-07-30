<?php

/**
 * API de Ezugi.
 * 
 * Archivo que provee servicios de lanzamiento de juegos de la API del proveedor Ezugi.
 * 
 *
 * @category Documentación.
 * @package  AutoDoc.
 * @author   nicolas.guato@virtualsoft.tech.
 * @version  1.0.0.
 * @since    23/04/2025.
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase EZUGISERVICES
 *
 * Esta clase provee funciones para la api 'EZUGISERVICES'
 *
 * @package ninguno.
 * @author  nicolas.guato@virtualsoft.tech.
 * @version 1.0.0.
 * @since  23/04/2025.
 */
class EZUGISERVICES
{
    /**
     * Constructor de la clase Ezugi.
     *
     * Inicializa las propiedades del usuario y configura el proveedor y el entorno.
     *
     * @param string $userId   ID del usuario (opcional).
     * @param string $userName Nombre del usuario (opcional).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }
    /**
     * Obtiene y prepara el juego solicitado para el usuario.
     *
     * Este método verifica o genera un token de sesión para el usuario, 
     * valida credenciales del proveedor TOMHORN, y se comunica con la API del subproveedor
     * para obtener o crear la identidad del jugador, devolviendo la configuración del juego lista para usarse.
     *
     * @param  string $gameid         ID del juego solicitado.
     * @param  string $lang           Idioma preferido del usuario.
     * @param  bool   $play_for_fun   Indica si el juego será iniciado en modo demo (jugar por diversión).
     * @param  string $usuarioToken   Token de autenticación del
     *                                usuario.
     * @param  string $migameid       ID alternativo del juego para migraciones o referencias cruzadas (Opcional).
     * @param  string $usumandanteId  ID del usuario mandante; si no se proporciona, se obtendrá desde el token (Opcional).
     * 
     * @return string $response       Respuesta del subproveedor TOMHORN que contiene información o URL del juego.
     * 
     * @throws Exception Si ocurre algún error durante el proceso de autenticación o comunicación con el proveedor.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "", $Producto = '')
    {

        try {

            $Proveedor = new Proveedor("", "EZZG");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());
    
            $Subproveedor = new Subproveedor($Producto->subproveedorId);
            $LaunchUR = "";
            $operatorId = "";

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

            $SubproveedorMandantePais = new SubproveedorMandantePais("", $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $LaunchUR = $Credentials->URL;
            $operatorId = $Credentials->OPERATOR_ID;

            if ($Subproveedor->getAbreviado() != 'EZZG' && $Subproveedor->getAbreviado() != 'EEVOLUTION') {
                if ($UsuarioMandante->mandante != 8 || $UsuarioMandante->mandante != 21 || $UsuarioMandante->mandante != 17) {
                    $operatorId = $Credentials->OPERATOR_ID_2;
                }
            }

            if (is_numeric($gameid)) {
                $method = 'openTable';
            } else {
                $method = 'selectGame';
            }

            $array = array(
                "error" => false,
                "response" => $LaunchUR . "&token=" . $UsuarioToken->getToken() . "&" . $method . "=" . $gameid . "&clientType=html5&language=" . strtolower($lang) . "&operatorId=" . $operatorId,
            );
            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }
}
