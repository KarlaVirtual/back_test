<?php

/**
 * Este archivo contiene la clase `SISVENPROLSERVICES`, que proporciona servicios relacionados con la integración de juegos
 * y la gestión de tokens de usuario para el proveedor SISVENPROL.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Karla Ramirez <karla.ramirez@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-04-27
 */
namespace Backend\integrations\casino;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\ProdmandantePais;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\dto\Registro;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use \CurlWrapper;
use DateTime;
use Exception;

/**
 * Clase `SISVENPROLSERVICES`.
 * Proporciona servicios relacionados con la integración de juegos y la gestión de tokens de usuario
 * para el proveedor SISVENPROL.
 */
class SISVENPROLSERVICES
{
    /**
     * Constructor de la clase `SISVENPROLSERVICES`.
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }

    /**
     * Obtiene la URL del juego o genera un token de usuario para el proveedor AADVARK.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo "jugar por diversión".
     * @param string  $UsuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en miniatura.
     * @param boolean $isMobile      Indica si el acceso es desde un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     * @param boolean $minigame      Indica si es un mini-juego.
     *
     * @return object Respuesta con la URL del juego o un error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $UsuarioToken, $migameid, $isMobile = false, $usumandanteId = "", $minigame = false)
    {
        try {

        $Proveedor = new Proveedor("", "SISVENPROL");
        $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => ''
                );

                return json_decode(json_encode($array));

            } else {

                try {

                    if ($usumandanteId == "") {
                        $UsuarioTokenSite = new UsuarioToken($UsuarioToken, '0');
                        $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                    }

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
                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $credentials = json_decode($SubproveedorMandantePais->getCredentials());
                $Registro = new Registro('', $UsuarioMandante->usuarioMandante);
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $ProdMandante = new ProductoMandante($Producto->productoId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $ExtraInfo = json_decode($ProdMandante->extrainfo);
                $fechaActual = (new DateTime())->format('Y-m-d H:i:s.u');
                $fechaActualVal = new DateTime();

                if ($ExtraInfo != '') {
                    $Ini_Time = $ExtraInfo->ini_time;
                    $token_api = $ExtraInfo->token;
                    $token_type = $ExtraInfo->token_type;

                    $fechaValidar = new DateTime($Ini_Time);
                    $diferenciaSegundos = abs($fechaActualVal->getTimestamp() - $fechaValidar->getTimestamp());

                    if ($diferenciaSegundos > 86400) {
                        $TokenApi = $this->AuthToken($credentials, $ProdMandante, $fechaActual);
                        $Token_Api = json_decode($TokenApi);
                        $token_api = $Token_Api->token;
                        $token_type = $Token_Api->token_type;
                    }
                } else {
                    $TokenApi = $this->AuthToken($credentials, $ProdMandante, $fechaActual);
                    $Token_Api = json_decode($TokenApi);
                    $token_api = $Token_Api->token;
                    $token_type = $Token_Api->token_type;
                }

                $path = 'api/product/open-box-office';
                if ($UsuarioMandante->moneda == 'USD') {
                    $path = 'api/product/usd/open-box-office ';
                }

                $data = [
                    "intermediary_id" => $credentials->INTERMEDIARY_ID,
                    "user_id" => intval($UsuarioMandante->usumandanteId),
                    "balance" => $Usuario->getBalance(),
                    "cedula" => intval($Registro->cedula)
                ];


                $Result = $this->Launch($credentials->URL . $path, json_encode($data), $token_api, $token_type, $credentials->USER_AGENT);
                $URL = json_decode($Result);

                $array = array(
                    "error" => false,
                    "response" => $URL->box_office_url
                );

                return json_decode(json_encode($array));

            }
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
        }

    }

    /**
     * Realiza una solicitud HTTP al servicio Sisvenprol para obtener un token de autenticación
     *
     * @param array  $Data Datos de la solicitud.
     * @param string $Url  URL del servicio.
     * @return array Respuesta del servicio.
     */

    public function GetToken($Url, $Data, $UserAgent)
    {
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
            CURLOPT_POSTFIELDS => $Data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'User-agent:' . $UserAgent
            ),
        ));
        //Ejecutarlasolicitud
        $response = $curl->execute();
        return $response;
    }

    /**
     * Realiza una solicitud HTTP al servicio Sisvenprol.
     *
     * @param array  $Data   Datos de la solicitud.
     * @param string $Url    URL del servicio.
     * @param string $Token  Token de autenticación.
     * @param string $Type   Tipo de Token generado.
     * @return array         Respuesta del servicio.
     */
    public function Launch($Url, $Data, $Token, $Type, $UserAgent)
    {

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
            CURLOPT_POSTFIELDS => $Data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: ' . $Type . ' ' . $Token,
                'User-agent:' . $UserAgent
            ),
        ));
        //Ejecutarlasolicitud
        $response = $curl->execute();
        return $response;
    }


    /**
     * Genera un token, valida que no esté expitado y devuelve un arreglo con la información del token.
     *
     * @param ProdMandante    $ProdMandante    Objeto del producto mandante.
     * @param array           $credentials     Objeto con las credenciales.
     * @param date            $fechaActual     URL del servicio.
     * @return array Respuesta del servicio.
     */
    public function AuthToken($credentials, $ProdMandante, $fechaActual)
    {
        $Path = 'api/authorization/token';
        $Data = [
            'grant_type' => $credentials->GRAND_TYPE,
            'client_id' => $credentials->USER,
            'client_secret' => $credentials->PASSWORD
        ];

        $Token = $this->GetToken($credentials->URL . $Path, json_encode($Data), $credentials->USER_AGENT);
        $result = json_decode($Token);
        $token_api = $result->access_token;
        $token_type = $result->token_type;

        $array_token = [
            "token" => $token_api,
            "ini_time" => $fechaActual,
            "token_type" => $token_type
        ];

        $ProdMandante->setextrainfo(json_encode($array_token));
        $ProductoMandanteMysqlDAO = new ProductoMandanteMysqlDAO();
        $ProductoMandanteMysqlDAO->update($ProdMandante);
        $ProductoMandanteMysqlDAO->getTransaction()->commit();

        return json_encode($array_token);
    }

    /**
     * Obtiene la dirección IP del cliente que realiza la solicitud.
     *
     * Este método verifica varias variables de entorno para determinar
     * la dirección IP del cliente, devolviendo 'UNKNOWN' si no se encuentra ninguna.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
     */
    public function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

}

