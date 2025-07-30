<?php
/**
 * Servicios para la api NSOFTSERVICES.
 *
 * Gestiona la comunicación con el proveedor de juegos, incluyendo generación de tokens,
 * firma de solicitudes y obtención de URLs para iniciar partidas.
 *
 * @category Red.
 * @package  API.
 * @author   nicolas.guato@virtualsoft.tech.
 * @version  1.0.0.
 * @since    21.04.25.
 */

namespace Backend\integrations\virtual;

use Exception;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;

/**
 * Clase 'NSOFTSERVICES'
 *
 * Esta clase provee funciones para la api 'NSOFTSERVICES'.
 */
class NSOFTSERVICES
{
    /**
     * Constructor de la clase NSOFTSERVICES.
     *
     * Inicializa la configuración del entorno para determinar si se encuentra
     * en un entorno de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene la URL del juego para el proveedor NSOFT.
     *
     * Este método construye la solicitud para obtener el enlace de redirección al juego,
     * firmando digitalmente los datos requeridos por el proveedor NSOFT y gestionando
     * los tokens de sesión del usuario.
     *
     * @param string  $gameid           ID del juego proporcionado por NSOFT.
     * @param string  $lang             Idioma del juego (por ejemplo: "en", "es").
     * @param boolean $play_for_fun     Indica si es modo demo o real (sin uso directo aquí, pero puede ser útil para lógica futura).
     * @param string  $usuarioToken     Token del usuario de sesión previa o inicial.
     * @param string  $ProductoMandante Objeto opcional relacionado con el producto mandante.
     * @param boolean $isMobile         Define si la plataforma es móvil (true) o escritorio (false).
     * @param string  $usumandanteId    ID del usuario mandante, opcional (si no se proporciona, se obtiene del token).
     *
     * @return object Retorna un objeto JSON con la siguiente estructura:
     *                - error (bool): Indica si hubo error o no.
     *                - response (string): URL de redirección para iniciar el juego.
     *
     * @throws Exception Si ocurre algún error al generar el token o al realizar la solicitud.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $ProductoMandante = null, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "NSOFT");
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
            $Producto = new Producto("", $gameid, $Proveedor->proveedorId);

            if ($Producto->descripcion == "Kolor" || $Producto->descripcion == "Lightning Lucky Six" || $Producto->descripcion == "Lightning Roulette") {
                $ProductoDetalle = new ProductoDetalle("", $Producto->productoId, "PRODUCTID");
                $Type = "Lightning";
            } else {
                $ProductoDetalle = new ProductoDetalle("", $Producto->productoId, "LAUNCHCODE");
                $Type = "Standard";
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $URL = $credentials->URL;
            $URL_LIGHTNING = $credentials->URL_LIGHTNING;
            $TENANT_ID = $credentials->TENANT_ID;

            if ($isMobile) {
                if ($Type == "Standard") {
                    $url = $URL . "/$ProductoDetalle->pValue?" . "auth=b2b" . "&token=" . $UsuarioToken->getToken() . "&tenantId=" . $TENANT_ID . "&id=" . $UsuarioMandante->usumandanteId . "&authStrategy=token" . "&lang=" . strtolower($lang) . "&currency=" . $UsuarioMandante->moneda;
                } else {
                    $url = $URL_LIGHTNING . "/?" . "platform=seven&clientType=token&tenantId=" . $TENANT_ID . "&playerId=" . $UsuarioMandante->usumandanteId . "&locale=" . strtolower($lang) . "&currency=" . $UsuarioMandante->moneda . "&productName=" . $gameid . "&productId=" . $ProductoDetalle->pValue . "&token=" . $UsuarioToken->getToken();
                }

                $array = array(
                    "error" => false,
                    "response" => $url
                );
            } else {
                if ($Type == "Standard") {
                    $url = $URL . "/$ProductoDetalle->pValue?" . "auth=b2b" . "&token=" . $UsuarioToken->getToken() . "&tenantId=" . $TENANT_ID . "&id=" . $UsuarioMandante->usumandanteId . "&authStrategy=token" . "&lang=" . strtolower($lang) . "&currency=" . $UsuarioMandante->moneda;
                } else {
                    $url = $URL_LIGHTNING . "/?" . "platform=seven&clientType=token&tenantId=" . $TENANT_ID . "&playerId=" . $UsuarioMandante->usumandanteId . "&locale=" . strtolower($lang) . "&currency=" . $UsuarioMandante->moneda . "&productName=" . $gameid . "&productId=" . $ProductoDetalle->pValue . "&token=" . $UsuarioToken->getToken();
                }

                $array = array(
                    "error" => false,
                    "response" => $url
                );
            }

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }
}