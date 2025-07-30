<?php

/**
 * Clase REDRAKESERVICES
 *
 * Esta clase proporciona servicios relacionados con la integración de juegos del proveedor REDRAKE.
 * Incluye métodos para obtener información de juegos y generar firmas de autenticación.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @version    1.0.0
 * @since      2025-04-27
 * @author     Desconocido
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
 * Clase REDRAKESERVICES
 *
 * Proporciona servicios relacionados con la integración de juegos del proveedor REDRAKE.
 * Incluye métodos para obtener información de juegos y generar firmas de autenticación.
 */
class REDRAKESERVICES
{
    /**
     * Constructor de la clase REDRAKESERVICES.
     *
     * Inicializa el entorno de configuración para determinar si se está en un entorno de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la información de un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo de prueba.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en el sistema.
     * @param boolean $mobile        Indica si el juego es para dispositivos móviles (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la información del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid, $mobile = false, $usumandanteId = "")
    {
        if ($play_for_fun) {
            $array = array(
                "error" => false,
                "response" => ''
            );
            return json_decode(json_encode($array));
        } else {
            try {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "REDRAKE");
                $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken(substr($token, 0, strlen($token) - strlen("vssv" . $migameid)) . "vssv" . $migameid);
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
                        $UsuarioToken->setToken(substr($token, 0, strlen($token) - strlen("vssv" . $migameid)) . "vssv" . $migameid);
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
                $mode = 'real';

                if ($Mandante->mandante == '2') {
                    $Mandante->baseUrl = "https://www.acropolisonline.com/";
                }

                if ($Mandante->baseUrl != '') {
                    $urlhome = $Mandante->baseUrl . "new-casino";
                }

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $parmsstring = "gameid=" . $gameid . "&sessionid=" . $UsuarioToken->getToken() . "&accountid=Usuario" . $UsuarioToken->getUsuarioId() . "&lang=" . $lang . "&currency=" . $UsuarioMandante->getMoneda() . "&mode=" . $mode . "&urlhome=" . $urlhome . "&jurisdiction=" . $credentials->JURISDICTION;
                $url_params = explode("&", $parmsstring);

                for ($c = 0; $c < oldCount($url_params); $c++) {
                    $kv = explode("=", $url_params[$c]);
                    $parms[$kv[0]] = $kv[1];
                }

                $array = array(
                    "error" => false,
                    "response" => $credentials->URL . $parmsstring . "&sig=" . $this->generateSig($parms, $credentials->HASH) . "&operator=" . $credentials->OPERATOR
                );
                return json_decode(json_encode($array));
            } catch (Exception $e) {
                // print_r($e);
            }
        }
    }

    /**
     * Genera una firma de autenticación.
     *
     * @param array  $params Parámetros a incluir en la firma.
     * @param string $secret Clave secreta para generar la firma.
     *
     * @return string Firma generada.
     */
    function generateSig($params, $secret)
    {
        $sig = null;
        foreach (explode(",", 'GET_PARAMS_NOT_IN_SIG') as $key) {
            unset($params[$key]);
        }

        ksort($params);
        foreach ($params as $key => $val) {
            $sig .= "|$key=$val";
        }

        return hash_hmac('sha256', $sig, $secret, false);
    }


}
