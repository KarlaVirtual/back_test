<?php

/**
 * Esta clase proporciona métodos para interactuar con servicios de juegos en línea.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */

namespace Backend\integrations\virtual;

use Exception;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\Clasificador;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase XPRESSSERVICES
 *
 * Esta clase proporciona métodos para interactuar con servicios de juegos en línea.
 * Incluye funcionalidades para generar URLs de redirección para juegos, manejar tokens de usuario,
 * y obtener información del cliente, entre otras.
 */
class XPRESSSERVICES
{
    /**
     * URL de redirección base para los servicios.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Constructor de la clase.
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
     * Obtiene la URL de un juego basado en los parámetros proporcionados.
     *
     * Este metodo genera una URL para redirigir a un juego, ya sea en modo "jugar por diversión"
     * o en modo real. También maneja la creación y actualización de tokens de usuario, y
     * personaliza la URL según el perfil del usuario y el dispositivo utilizado.
     *
     * @param string                $gameid           ID del juego.
     * @param string                $lang             Idioma del juego.
     * @param boolean               $play_for_fun     Indica si el juego es en modo "jugar por diversión".
     * @param string                $usuarioToken     Token del usuario.
     * @param string                $migameid         Opcional ID alternativo del juego.
     * @param string                $usumandanteId    Opcional ID del usuario mandante.
     * @param boolean               $isMobile         Opcional Indica si el cliente es móvil.
     * @param ProductoMandante|null $ProductoMandante Opcional Objeto ProductoMandante.
     *
     * @return object Respuesta con la URL generada y otros datos relevantes.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid = "", $usumandanteId = "", $isMobile = false, $ProductoMandante = null)
    {
        try {
            $lang = 'es';
            $mode = '1';
            $group = 'master';
            $clientPlatform = 'desktop';

            if ($isMobile) {
                $clientPlatform = 'mobile';
            }

            if ($play_for_fun) {
                $URlFINAL = '' . 'Launcher?token=' . '';
                $URlFINAL = $URlFINAL . '&game=' . $gameid;
                $URlFINAL = $URlFINAL . '&backurl=' . '';
                $URlFINAL = $URlFINAL . '&mode=' . $mode;
                $URlFINAL = $URlFINAL . '&language=' . $lang;
                $URlFINAL = $URlFINAL . '&group=' . $group;
                $URlFINAL = $URlFINAL . '&clientPlatform=' . $clientPlatform;
                $URlFINAL = $URlFINAL . '&cashierurl=' . '';
                $URlFINAL = $URlFINAL . '&h=' . MD5('' . $gameid . $this->URLREDIRECTION . $mode . $lang . $group . $clientPlatform . '' . '');

                $array = array(
                    "error" => false,
                    "response" => $URlFINAL,
                );
            } else {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "XPRESS");
                $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

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
                        $UsuarioToken->setProductoId($Producto->productoId);

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
                $Mandante = new Mandante($UsuarioMandante->getMandante());

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
                }

                if ($UsuarioPerfil->getPerfilId() == 'PUNTOVENTA') {
                    $group = 'puntopropio';
                } elseif ($UsuarioPerfil->getPerfilId() == 'CAJERO') {
                    $group = 'agentes';
                } else {
                    $group = 'master';
                }

                $cashierurl = $Mandante->baseUrl . "gestion/deposito";
                $isPv = false;

                if (in_array($UsuarioPerfil->getPerfilId(), ['PUNTOVENTA', 'CAJERO'])) {
                    $clientPlatform = 'retail';
                    $isPv = true;
                }

                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                $URlFINAL = $Credentials->URL . '?token=' . $UsuarioToken->getToken();
                $URlFINAL = $URlFINAL . '&game=' . $gameid;
                $URlFINAL = $URlFINAL . '&backurl=' . $this->URLREDIRECTION;
                $URlFINAL = $URlFINAL . '&mode=' . $mode;
                $URlFINAL = $URlFINAL . '&language=' . $lang;
                $URlFINAL = $URlFINAL . '&group=' . $group;
                $URlFINAL = $URlFINAL . '&clientPlatform=' . $clientPlatform;
                $URlFINAL = $URlFINAL . '&cashierurl=' . $cashierurl;
                $URlFINAL = $URlFINAL . '&h=' . MD5($UsuarioToken->getToken() . $gameid . $this->URLREDIRECTION . $mode . $lang . $group . $clientPlatform . $cashierurl . $Credentials->PRIVATE_KEY);

                $array = array(
                    "error" => false,
                    "response" => array(
                        "proveedor" => "XPRESS",
                        "url" => $URlFINAL,
                        "isPv" => $isPv
                    ),
                );

                if ($isPv) {
                    try {
                        $Clasificador = new Clasificador('', 'RELATIONSHIPUSERONLINE');
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->usuarioMandante, 'A', $Clasificador->getClasificadorId());
                        $IdUsuarioRelacionado = $UsuarioConfiguracion->valor;
                        $registro = new Registro('', $IdUsuarioRelacionado);

                        $array["response"]["dni"] = $registro->cedula;
                        $array["response"]["userID"] = $IdUsuarioRelacionado;
                    } catch (Exception $ex) {
                    }
                }
            }
            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }


    /**
     * Obtiene la dirección IP del cliente.
     *
     * Este metodo detecta y devuelve la dirección IP del cliente que realiza la solicitud.
     * Si no se puede determinar la IP, devuelve "UNKNOWN".
     *
     * @return string Dirección IP del cliente.
     */
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }
}
