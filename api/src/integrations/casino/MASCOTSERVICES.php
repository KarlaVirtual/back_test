<?php

/**
 * Clase para la integración con los servicios de Mascot Gaming.
 *
 * Este archivo contiene métodos para gestionar sesiones de juego,
 * asignación de bonos y configuración de jugadores en la plataforma Mascot Gaming.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Producto;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\Usuario;
use Exception;
use mascotgaming\Client;
use Backend\dto\Mandante;
use Backend\dto\Proveedor;
use Backend\dto\BonoInterno;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use \CurlWrapper;

/**
 * Clase principal que gestiona las operaciones relacionadas con Mascot Gaming.
 */
class MASCOTSERVICES
{
    /**
     * Constructor de la clase MASCOTSERVICES.
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }

    /**
     * Obtiene una sesión de juego para un usuario.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si es una sesión de prueba.
     * @param string  $usuarioToken  Token del usuario.
     * @param integer $productoId    ID del producto.
     * @param boolean $isMobile      Indica si es una sesión móvil (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL de la sesión o un error.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            $Producto = new Producto($productoId);
            $UsuarioMandante = new UsuarioMandante($usumandanteId);
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            if ($play_for_fun) {
                $data = array(
                    "jsonrpc" => "2.0",
                    "method" => "Session.CreateDemo",
                    "id" => time(),
                    "params" => array(
                        "BankGroupId" => "",
                        "GameId" => $gameid,
                        "StartBalance" => "",
                    ),
                    "BonusId" => "",
                    "AlternativeId" => "",
                    "language" => $lang,

                );

                $response = $this->Request($data, $credentials->URL, $credentials->API_KEYS);
                $response = json_decode($response);

                $array = array(
                    "error" => false,
                    "response" => $response->result->SessionUrl
                );
                return json_decode(json_encode($array));
            } else {
                $Proveedor = new Proveedor("", "MASCOT");
                $Subproveedor = new Subproveedor("", "MASCOT");

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
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
                        $UsuarioToken->setProductoId(0);


                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                $BonoInterno = new BonoInterno();

                $ProductoMandante = new ProductoMandante($productoId, $UsuarioMandante->mandante, "", $UsuarioMandante->paisId);

                $Bonos = $BonoInterno->DataBonosFreespin($UsuarioMandante->usuarioMandante, $UsuarioMandante->moneda, $ProductoMandante->prodmandanteId);
                if ($_ENV['debug']) {
                    print_r($Bonos);
                }
                $isBonus = false;
                if (oldCount($Bonos) > 0) {
                    $isBonus = true;
                    $bonoId = ($Bonos[0])->{'usuario_bono.bono_id'};
                    $denominacion = ($Bonos[0])->{"bono_detalle.valor"};
                }


                if ($isBonus == true) {
                    $data = array(
                        "jsonrpc" => "2.0",
                        "method" => "Session.Create",
                        "id" => time(),
                        "params" => array(
                            "PlayerId" => "Usuario" . $UsuarioMandante->usumandanteId,
                            "GameId" => $gameid,
                            "BonusId" => strval($bonoId),
                            "Params" => array(
                                "freeround_denomination" => floatval($denominacion)
                            )
                        )
                    );
                } else {
                    $data = array(
                        "jsonrpc" => "2.0",
                        "method" => "Session.Create",
                        "id" => time(),
                        "params" => array(
                            "PlayerId" => "Usuario" . $UsuarioMandante->usumandanteId,
                            "GameId" => $gameid,
                            "RestorePolicy" => 'Restore'
                        )
                    );
                }


                $resp = $this->SetPlayer($UsuarioMandante, $Mandante, $credentials->URL, $credentials->API_KEYS);
                $response = $this->Request($data, $credentials->URL, $credentials->API_KEYS);

                //validar el response sea OK y  actualizar token

                if ($_ENV['debug']) {
                    print_r("Data Request:");
                    print_r(json_encode($data));
                    print_r("SetPlayer Response:");
                    print_r(json_encode($resp));
                    print_r("Data Response:");
                    print_r(json_encode($response));
                }

                if ($response["SessionUrl"] != "") {
                    $UsuarioToken->setToken($response["SessionId"]);
                    $UsuarioToken->setEstado("A");
                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->update($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();

                    $array = array(
                        "error" => false,
                        "response" => $response["SessionUrl"]
                    );

                    return json_decode(json_encode($array));
                } else {
                    throw new Exception("Error General", "1");
                }
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Configura un jugador en la plataforma Mascot Gaming.
     *
     * @param UsuarioMandante $UsuarioMandante Objeto del usuario mandante.
     * @param Mandante        $Mandante        Objeto del mandante.
     * @param string          $Url             URL del servicio.
     * @param string          $Key             Clave de autenticación.
     *
     * @return array Respuesta del servicio.
     */
    public function SetPlayer(UsuarioMandante $UsuarioMandante, Mandante $Mandante, $Url, $Key)
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $Mandante->mandante = 0;
        }

        $BankGroupId = $Mandante->mandante . '_' . $UsuarioMandante->paisId;

        $data = array(
            "jsonrpc" => "2.0",
            "method" => "Player.Set",
            "id" => time(),
            "params" => array(
                "Id" => "Usuario" . $UsuarioMandante->usumandanteId,
                "Nick" => "Usuario" . $UsuarioMandante->usumandanteId,
                "BankGroupId" => $BankGroupId
            )
        );

        $request = json_encode($data);

        $curl = new CurlWrapper($Url);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $Url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Content-Length: ' . strlen($request)
            ),
            CURLOPT_SSLCERT => __DIR__ . '/../../imports/mascotgaming/ssl/seamless/' . $Key,
        ));

        $response = $curl->execute();
        $response = json_decode($response);

        if ($response->error->code == 10402) {
            $data = array(
                "jsonrpc" => "2.0",
                "method" => "BankGroup.Set",
                "id" => time(),
                "params" => array(
                    "Id" => $BankGroupId,
                    "Currency" => $UsuarioMandante->moneda,
                )
            );

            $request = json_encode($data);

            $curl = new CurlWrapper($Url);
            $curl->setOptionsArray(array(
                CURLOPT_URL => $Url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Content-Length: ' . strlen($request)
                ),
                CURLOPT_SSLCERT => __DIR__ . '/../../imports/mascotgaming/ssl/seamless/' . $Key,
            ));

            $response = $curl->execute();

            $this->SetPlayer($UsuarioMandante, $Mandante, $Url, $Key);
        }

        return $response;
    }

    /**
     * Realiza una solicitud HTTP al servicio Mascot Gaming.
     *
     * @param array  $data Datos de la solicitud.
     * @param string $Url  URL del servicio.
     * @param string $Key  Clave de autenticación.
     *
     * @return array Respuesta del servicio.
     */
    public function Request($data, $Url, $Key)
    {
        $request = json_encode($data);

        $curl = new CurlWrapper($Url);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $Url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Content-Length: ' . strlen($request),
                'charset: UTF-8'
            ),

            CURLOPT_SSLCERT => __DIR__ . '/../../imports/mascotgaming/ssl/seamless/' . $Key,
        ));

        $response = $curl->execute();
        $response = json_decode($response, true);
        return $response['result'];
    }

    /**
     * Genera una clave única para un jugador.
     *
     * @param string $player Identificador del jugador.
     *
     * @return string Clave generada.
     */
    function generateKey($player)
    {
        $hash = md5($player . md5("TMP" . $player));
        $hash = substr($hash, 0, 12);
        return ($hash);
    }

    /**
     * Asigna un bono a un jugador.
     *
     * @param string  $bonoId     ID del bono (opcional).
     * @param integer $roundsFree Número de rondas gratuitas.
     * @param array   $ids        IDs de los jugadores.
     * @param array   $games      Juegos asociados al bono.
     *
     * @return array Respuesta con el estado de la asignación del bono.
     */
    public function SetBonus($bonoId = "", $roundsFree, $ids = "", $games = "")
    {
        $Proveedor = new Proveedor("", "MASCOT");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());
        $Usuario = new Usuario($ids[0]);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $data = array(
            "jsonrpc" => "2.0",
            "method" => "Bonus.Set",
            "id" => time(),
            "params" => array(
                "Id" => $bonoId
            )
        );

        $request = json_encode($data);
        
        $curl = new CurlWrapper($credentials->URL);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $credentials->URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Content-Length: ' . strlen($request),
            ),
            CURLOPT_SSLCERT => __DIR__ . '/../../imports/mascotgaming/ssl/seamless/' . $credentials->API_KEYS,
        ));
        
        $response = $curl->execute();
        
        syslog(LOG_WARNING, "MASCOT BONO REQUEST: " . json_encode($data) . " RESPONSE: " . json_encode($response));

        $response = json_decode($response);

        if ($response != "") {
            $array = array(
                "error" => false,
                "code" => 0,
                "response" => $response,
            );
        } else {
            $array = array(
                "error" => true,
                "code" => 1,
                "response" => "Error en la asignación de freespin",
            );
        }
        return $array;
    }
}

