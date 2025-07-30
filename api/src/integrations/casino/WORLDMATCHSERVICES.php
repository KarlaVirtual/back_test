<?php

/**
 * Este archivo contiene la clase `WORLDMATCHSERVICES` que proporciona servicios relacionados con juegos
 * en la plataforma WorldMatch. Incluye métodos para obtener información y lanzar juegos.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
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
 * Clase `WORLDMATCHSERVICES`.
 * Proporciona servicios relacionados con la integración de juegos en la plataforma WorldMatch.
 */
class WORLDMATCHSERVICES
{
    /**
     * Constructor de la clase WORLDMATCHSERVICES.
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }

    /**
     * Obtiene la URL de lanzamiento de un juego basado en los parámetros proporcionados.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo "jugar por diversión".
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId Opcional ID del usuario mandante.
     *
     * @return object Respuesta con la URL de lanzamiento del juego.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $Proveedor = new Proveedor("", "WMT");
        $Producto = new Producto('', $gameid, $Proveedor->getProveedorId());

        $LaunchUR = '';
        $skin = '';

        if ($play_for_fun) {
            $array = array(
                "error" => false,
                "response" => $LaunchUR . "/fun/" . explode("-", $gameid)[0] . "/" . explode("-", $gameid)[1] . "/" . explode("-", $gameid)[2] . "/" . "?authkey=&authuser=&language=" . strtolower($lang) . "&authskin=" . $skin . "&age=false&display=iframe&launch_mode=fun",
            );
            return json_decode(json_encode($array));
        } else {
            try {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
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
                
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $LaunchUR = $Credentials->URL;
                $skin = $Credentials->skin;

                $url_launch = $LaunchUR . "real/" . $gameid . "?authkey=" . $UsuarioToken->getToken() . "&authuser=" . $UsuarioToken->getUsuarioId() . "&language=" . strtolower($lang) . "&authskin=" . $skin . "&age=false&display=iframe";

                $array = array(
                    "error" => false,
                    "response" => $url_launch,
                );

                return json_decode(json_encode($array));
            } catch (Exception $e) {
            }
        }
    }
}
