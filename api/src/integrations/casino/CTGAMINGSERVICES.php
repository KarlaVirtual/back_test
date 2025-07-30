<?php

/**
 * Clase CTGAMINGSERVICES
 *
 * Este archivo contiene la implementación de la clase `CTGAMINGSERVICES`, que proporciona servicios relacionados con la integración de juegos de casino
 * para el proveedor CTGAMING. Incluye métodos para obtener juegos, firmar solicitudes, realizar peticiones HTTP, generar claves y obtener la IP del cliente.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
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
use Backend\dto\BonoInterno;
use Backend\dto\Subproveedor;
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
 * Clase CTGAMINGSERVICES
 *
 * Proporciona servicios relacionados con la integración de juegos de casino
 * para el proveedor CTGAMING. Incluye métodos para obtener juegos, firmar solicitudes,
 * realizar peticiones HTTP, generar claves y obtener la IP del cliente.
 */
class CTGAMINGSERVICES
{
    /**
     * Constructor de la clase.
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
     * Obtiene la URL de un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $productoId    ID del producto.
     * @param boolean $isMobile      Indica si el juego es para dispositivos móviles.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego y el proveedor.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            $UsuarioMandante = new UsuarioMandante($usumandanteId);
            $Mandante = $UsuarioMandante->mandante;

            $Proveedor = new Proveedor("", "CTGAMING");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $API_KEY = $credentials->API_KEY;
            $PSK = $credentials->PSK;
            $URL = $credentials->URL;

            $SubProveedor = new Subproveedor("", "CTGAMING");
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId, "", "", $productoId);
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

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Mandante = new Mandante($UsuarioMandante->mandante);

            $UsuarioToken->setToken($UsuarioToken->createToken());

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            if ($Mandante->baseUrl != '') {
                $URLREDIRECTION = $Mandante->baseUrl . "new-casino";
            }
            date_default_timezone_set('America/Bogota');


            $sql =
                "SELECT count(*) count 
FROM usuario_bono
         INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)
         INNER JOIN bono_detalle ON (bono_detalle.bono_id = bono_interno.bono_id)
         LEFT OUTER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id)
         LEFT OUTER JOIN bono_log ON (bono_log.id_externo = usuario_bono.usubono_id AND bono_log.estado = 'W')
WHERE 1 = 1
  AND usuario_bono.usuario_id = $UsuarioMandante->usumandanteId
  AND bono_interno.tipo = '8'
  AND bono_detalle.tipo IN ('CONDSUBPROVIDER')
  AND bono_detalle.valor = $SubProveedor->subproveedorId
  AND usuario_bono.estado = 'A'


         ";


            $BonoInterno = new BonoInterno();

            $UsuarioBonoMySqlDAO = new \Backend\mysql\UsuarioBonoMySqlDAO();
            $transaccion = $UsuarioBonoMySqlDAO->getTransaction();

            $Count = $BonoInterno->execQuery($transaccion, $sql);


            $sql =
                "SELECT usuario_bono.*,bono_detalle.*
FROM usuario_bono
         INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)
         INNER JOIN bono_detalle ON (bono_detalle.bono_id = bono_interno.bono_id)
         LEFT OUTER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id)
         LEFT OUTER JOIN bono_log ON (bono_log.id_externo = usuario_bono.usubono_id AND bono_log.estado = 'W')
WHERE 1 = 1
  AND usuario_bono.usuario_id = $UsuarioMandante->usumandanteId
  AND bono_interno.tipo = '8'
  AND bono_detalle.tipo IN ('CONDSUBPROVIDER','MAXPAGO')
  AND usuario_bono.estado = 'A'
         ";

            $BonoInterno = new BonoInterno();

            $UsuarioBonoMySqlDAO = new \Backend\mysql\UsuarioBonoMySqlDAO();
            $transaccion = $UsuarioBonoMySqlDAO->getTransaction();

            $Bonos = $BonoInterno->execQuery($transaccion, $sql);


            $Bonos = json_encode($Bonos);
            $Bonos = json_decode($Bonos);

            $hora = strtotime("+5 hour", time());

            $UTC = str_replace(" ", "T", date("Y-m-d H:i:s", $hora)) . "Z";


            if ($Count->{".count"} != "0") {
                foreach ($Bonos as $key => $value2) {
                    $bonoId = $value2->{"usuario_bono.bono_id"};

                    if ($value2->{"bono_detalle.tipo"} == "MAXPAGO") {
                        if ($value2->{"bono_detalle.moneda"} == $UsuarioMandante->moneda) {
                            $denominacion = $value2->{"bono_detalle.valor"};
                        }
                    }
                    if ($value2->{"bono_detalle.tipo"} == "CONDSUBPROVIDER") {
                        $ProveedorId = $value2->{"bono_detalle.valor"};
                    }
                }


                if ($ProveedorId == $SubProveedor->subproveedorId) {
                    $array = array(
                        "icasino_token" => $UsuarioToken->getToken(),
                        "icasino_account_id" => $usumandanteId,
                        "icasino_account_currency" => $UsuarioMandante->moneda,
                        "real_money_session" => 1,
                        "version" => 5,
                        "launched_at_utc" => $UTC,
                        "has_freerounds" => 1

                    );

                    $PAYLOAD = $this->signRequest($array["icasino_token"] . "-" . $array["icasino_account_id"] . "-" . $array["icasino_account_currency"] . "-" . $array["real_money_session"] . "-" . $array["launched_at_utc"] . "-" . $array["has_freerounds"], $PSK);
                    $Url = $URL . $API_KEY . "?game_id=" . $gameid . "&ic=" . $API_KEY . "&checksum=" . $PAYLOAD . "&icasino_token=" . $UsuarioToken->getToken() . "&real_money_session=1" . "&icasino_account_currency=" . $UsuarioMandante->moneda . "&icasino_account_id=" . $usumandanteId . "&version=5&launched_at_utc=" . $UTC . "&has_freerounds=1&exit_url=" . $URLREDIRECTION . "&fullscreen=0";
                } else {
                    $array = array(
                        "icasino_token" => $UsuarioToken->getToken(),
                        "icasino_account_id" => $usumandanteId,
                        "icasino_account_currency" => $UsuarioMandante->moneda,
                        "real_money_session" => 1,
                        "version" => 5,
                        "launched_at_utc" => $UTC
                    );
                    $PAYLOAD = $this->signRequest($array["icasino_token"] . "-" . $array["icasino_account_id"] . "-" . $array["icasino_account_currency"] . "-" . $array["real_money_session"] . "-" . $array["launched_at_utc"], $PSK);
                    $Url = $URL . $API_KEY . "?game_id=" . $gameid . "&ic=" . $API_KEY . "&checksum=" . $PAYLOAD . "&icasino_token=" . $UsuarioToken->getToken() . "&real_money_session=1" . "&icasino_account_currency=" . $UsuarioMandante->moneda . "&icasino_account_id=" . $usumandanteId . "&version=5&launched_at_utc=" . $UTC . "&exit_url=" . $URLREDIRECTION . "&fullscreen=0";
                }
            } else {
                $array = array(
                    "icasino_token" => $UsuarioToken->getToken(),
                    "icasino_account_id" => $usumandanteId,
                    "icasino_account_currency" => $UsuarioMandante->moneda,
                    "real_money_session" => 1,
                    "version" => 5,
                    "launched_at_utc" => $UTC
                );

                $PAYLOAD = $this->signRequest($array["icasino_token"] . "-" . $array["icasino_account_id"] . "-" . $array["icasino_account_currency"] . "-" . $array["real_money_session"] . "-" . $array["launched_at_utc"], $PSK);
                $Url = $URL . $API_KEY . "?game_id=" . $gameid . "&ic=" . $API_KEY . "&checksum=" . $PAYLOAD . "&icasino_token=" . $UsuarioToken->getToken() . "&real_money_session=1" . "&icasino_account_currency=" . $UsuarioMandante->moneda . "&icasino_account_id=" . $usumandanteId . "&version=5&launched_at_utc=" . $UTC . "&exit_url=" . $URLREDIRECTION . "&fullscreen=0";
            }

            $array = array(
                "error" => false,
                "response" => array(
                    "proveedor" => $Proveedor->getAbreviado(),
                    "url" => $Url
                )
            );
            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Genera una firma para una solicitud.
     *
     * @param string $PAYLOAD Datos a firmar.
     * @param string $PSK     Clave secreta para la firma.
     *
     * @return string Firma generada.
     */
    public function signRequest($PAYLOAD, $PSK)
    {
        $key = $PSK;
        $signature = urlencode(base64_encode(hash_hmac('SHA1', $PAYLOAD, $key, true)));

        return $signature;
    }

    /**
     * Realiza una petición HTTP POST.
     *
     * @param array  $array_tmp Datos a enviar en la petición.
     * @param string $URL       URL del servicio.
     * @param string $API_KEY   Clave API para la autenticación.
     *
     * @return string Respuesta del servicio.
     */
    public function Request($array_tmp, $URL, $API_KEY)
    {
        $data = array();

        $data = array_merge($data, $array_tmp);

        $data = json_encode($data);

        $ch = curl_init($URL . $API_KEY);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $result = (curl_exec($ch));

        print_r(
            $result
        );
        return ($result);
    }

    /**
     * Genera una clave única basada en el identificador del jugador.
     *
     * @param string $player Identificador del jugador.
     *
     * @return string Clave generada de 12 caracteres.
     */
    function generateKey($player)
    {
        $hash = md5($player . md5("TMP" . $player));
        $hash = substr($hash, 0, 12);
        return ($hash);
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * Este método verifica varias variables de entorno para determinar
     * la dirección IP del cliente. Si no se encuentra ninguna dirección IP,
     * devuelve 'UNKNOWN'.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
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

    /**
     * Genera una firma HMAC-SHA256.
     *
     * @param string $key     Clave para la firma.
     * @param string $message Mensaje a firmar.
     *
     * @return string Firma generada en mayúsculas.
     */
    public function GetSign($key, $message)
    {
        return strtoupper(hash_hmac('sha256', pack('A*', $message), pack('A*', $key)));
    }
}
