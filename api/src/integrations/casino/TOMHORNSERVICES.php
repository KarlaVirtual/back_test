<?php

/**
 * Clase para integrar servicios del proveedor TOMHORN en la API.
 *
 * Este archivo contiene métodos para gestionar sesiones, obtener información
 * de juegos, crear campañas de freespins y realizar solicitudes a la API del proveedor.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use \CurlWrapper;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase principal para manejar la integración con los servicios de TOMHORN.
 */
class TOMHORNSERVICES
{

    private $METHOD = '';

    /**
     * Constructor de la clase.
     *
     * Inicializa el entorno de configuración para determinar si se está
     * ejecutando en un entorno de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene información de un juego y gestiona las sesiones del usuario.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      Opcional ID del juego en el sistema interno.
     * @param string  $usumandanteId Opcional ID del usuario mandante.
     *
     * @return object Respuesta con los parámetros del juego o error.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid = "", $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "TOMHORN");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

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
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
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
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $PARTNER_ID = $credentials->PARTNER_ID;
            $SIGN_KEY = $credentials->SIGN_KEY;
            $URL_API = $credentials->URL_API;

            $array = array(
                "partnerID" => $PARTNER_ID,
                "name" => 'Usuario' . $UsuarioMandante->usumandanteId
            );

            $array["sign"] = $this->GetSign($SIGN_KEY, $array["partnerID"] . $array["name"]);

            $this->METHOD = "/GetIdentity";


            $response = $this->Request($array, $URL_API);

            $response = json_decode($response);

            if ($response->Code == 1003) {
                $array = array(
                    "partnerID" => $PARTNER_ID,
                    "name" => 'Usuario' . $UsuarioMandante->usumandanteId,
                    "displayName" => $UsuarioMandante->getNombres(),
                    "currency" => $UsuarioMandante->moneda,
                    "parent" => '',
                    "type" => '',
                    "password" => '',
                    "details" => ''
                );

                $array["sign"] = $this->GetSign($SIGN_KEY, $array["partnerID"] . $array["name"] . $array["displayName"] . $array["currency"] . $array["parent"] . $array["type"] . $array["password"] . $array["details"]);

                $this->METHOD = "/CreateIdentity";

                $response = $this->Request($array, $URL_API);

                $response = json_decode($response);
                if ($response->Code == 0) {
                } else {
                    throw new Exception("Error general", "100000");
                }
            }

            if ($UsuarioToken->token != '') {
                $array = array(
                    "partnerID" => $PARTNER_ID,
                    "sessionID" => $UsuarioToken->token
                );

                $array["sign"] = $this->GetSign($SIGN_KEY, $array["partnerID"] . $array["sessionID"]);

                $this->METHOD = "/CloseSession";

                $response = $this->Request($array, $URL_API);
                $response = json_decode($response);

                if ($response->Code == 0 || $response->Code == 1 || $response->Code == 1007 || $response->Code == 1006) {
                } else {
                    throw new Exception("Error general", "100000");
                }
            }

            $array = array(
                "partnerID" => $PARTNER_ID,
                "name" => 'Usuario' . $UsuarioMandante->usumandanteId
            );

            $array["sign"] = $this->GetSign($SIGN_KEY, $array["partnerID"] . $array["name"]);

            $this->METHOD = "/CreateSession";

            $response = $this->Request($array, $URL_API);

            $response = json_decode($response);
            if ($response->Code == '0') {
                $UsuarioToken->setToken($response->Session->ID);
                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                $UsuarioToken->setProductoId($Producto->productoId);

                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();

                $array = array(
                    "partnerID" => $PARTNER_ID,
                    "sessionID" => $UsuarioToken->getToken(),
                    "module" => $gameid
                );
                $array["sign"] = $this->GetSign($SIGN_KEY, $array["partnerID"] . $array["sessionID"] . $array["module"]);

                $this->METHOD = "/GetModuleInfo";

                $response = $this->Request($array, $URL_API);

                $response = json_decode($response);

                if ($response->Code == '0') {
                    $Parameters = $response->Parameters;

                    $parametersWeb = array();

                    foreach ($Parameters as $item) {
                        $parametersWeb[$item->Key] = $item->Value;
                    }
                    $parametersWeb["width"] = "100%";
                    $parametersWeb["height"] = "100%";

                    $parametersWeb["proveedor"] = "TOMHORN";

                    $array = array(
                        "error" => false,
                        "proveedor" => "TOMHORN",
                        "response" => $parametersWeb,

                    );
                    return json_decode(json_encode($array));
                } else {
                    throw new Exception("Error general", "100000");
                }
            } else {
                if ($response->Code == 1005) {
                    $array = array(
                        "partnerID" => $PARTNER_ID,
                        "sessionID" => $response->Session->ID
                    );

                    $array["sign"] = $this->GetSign($SIGN_KEY, $array["partnerID"] . $array["sessionID"]);

                    $this->METHOD = "/CloseSession";

                    $response = $this->Request($array, $URL_API);
                    $response = json_decode($response);

                    if ($response->Code == 0) {
                        $array = array(
                            "partnerID" => $PARTNER_ID,
                            "name" => 'Usuario' . $UsuarioMandante->usumandanteId
                        );

                        $array["sign"] = $this->GetSign($SIGN_KEY, $array["partnerID"] . $array["name"]);

                        $this->METHOD = "/CreateSession";

                        $response = $this->Request($array, $URL_API);
                        $response = json_decode($response);
                        if ($response->Code == 0) {
                            $UsuarioToken->setToken($response->Session->ID);
                            $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                            $UsuarioToken->setProductoId($Producto->productoId);

                            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                            $UsuarioTokenMySqlDAO->update($UsuarioToken);
                            $UsuarioTokenMySqlDAO->getTransaction()->commit();


                            $array = array(
                                "partnerID" => $PARTNER_ID,
                                "sessionID" => $UsuarioToken->getToken(),
                                "module" => $gameid
                            );

                            $array["sign"] = $this->GetSign($SIGN_KEY, $array["partnerID"] . $array["sessionID"] . $array["module"]);

                            $this->METHOD = "/GetModuleInfo";

                            $response = $this->Request($array, $URL_API);
                            $response = json_decode($response);


                            if ($response->Code == 0) {
                                $Parameters = $response->Parameters;

                                $parametersWeb = array();

                                foreach ($Parameters as $item) {
                                    $parametersWeb[$item->Key] = $item->Value;
                                }

                                $parametersWeb["width"] = "100%";
                                $parametersWeb["height"] = "100%";
                                $parametersWeb["proveedor"] = "TOMHORN";

                                $array = array(
                                    "error" => false,
                                    "proveedor" => "TOMHORN",
                                    "response" => $parametersWeb,

                                );
                                return json_decode(json_encode($array));
                            } else {
                                throw new Exception("Error general", "100000");
                            }
                        } else {
                            throw new Exception("Error general", "100000");
                        }
                    } else {
                        throw new Exception("Error general", "100000");
                    }
                } else {
                    throw new Exception("Error general", "100000");
                }
            }
        } catch (Exception $e) {
            throw new $e;
        }
    }

    /**
     * Realiza una solicitud HTTP POST a la API del proveedor.
     *
     * @param array  $data    Datos a enviar en la solicitud.
     * @param string $URL_API URL de la API del proveedor.
     *
     * @return string Respuesta de la API.
     */
    public function Request($data, $URL_API)
    {
        //InicializarlaclaseCurlWrapper
        $curl = new CurlWrapper($URL_API . $this->METHOD);

        //Configurar opciones
        $curl->setOptionsArray(array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $result = $curl->execute();

        if ($_ENV['debug']) {
            print_r($result);
        }

        return ($result);
    }


    /**
     * Crea una campaña de freeSpin y asigna jugadores a ella.
     *      - Primero crea la campaña y luego en otra solicitud asigna los jugadores.
     *      - Los jugadores son asignados a la campaña en un máximo de 10 jugadores por solicitud.
     *
     * @param string         $productoId     Objeto para obtener externolId.
     * @param string         $usumandanteId  Objeto para tener UsuarioMandante.
     * @param string         $players        Arreglo de jugadores.
     * @param string         $campaignName   Parametro de nombre de campaña.
     * @param string         $gamesPerPlayer Cantidad total de juegos gratis.
     * @param string         $coinSize       Valor de cada juego gratis.
     * @param string         $timeFrom       Desde cuando.
     * @param string         $timeTo         Hasta cuando.
     * @param string|integer $campaignCode   Es el código de la campaña creada, la primera vez que se llame la función
     * será null, después de crear la campaña siempre llegará el mismo código de la campaña.
     * 
     * @return array Retorna un arreglo con la respuesta de la API y el código de la campaña.
     * @throws Exception Si ocurre un error durante la creación de la campaña o la asignación de jugadores.
     */
    public function CreateFreespin($producto, $campaignName, $gamesPerPlayer, $coinSize, $timeFrom, $timeTo, $users, $bonoId, $mandante = '0', $aditionalIdentifier, $campaignCode)
    {
        $Usuario = new Usuario($users[0]);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $currency = $UsuarioMandante->moneda;
        $Proveedor = new Proveedor("", "TOMHORN");
        $Producto = new Producto("", $producto, $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $PARTNER_ID = $credentials->PARTNER_ID;
        $SIGN_KEY = $credentials->SIGN_KEY;
        $URL_API = $credentials->URL_API;

        if (is_null($campaignCode)){
            //Estructura de la petición para procesar
            $array = array(
                "partnerID" => $PARTNER_ID,
                "campaignName" => $bonoId . "-" . $aditionalIdentifier . $Usuario->usuarioId,
                "module" => $producto,
                "currency" => $currency,
                "gamesPerPlayer" => strval($gamesPerPlayer),
                "coinSize" => strval($coinSize),
                "timeFrom" => str_replace(" ", "T", $timeFrom),
                "timeTo" => str_replace(" ", "T", $timeTo)
            );

            $array["sign"] = $this->GetSign($SIGN_KEY, $array["partnerID"] . $array["campaignName"] . $array["module"] . $array["currency"] . $array["gamesPerPlayer"]  . $array["timeFrom"] . $array["timeTo"]);
            $this->METHOD = "/CreateCampaign";
            $response = $this->Request($array, $URL_API);
            
            syslog(LOG_WARNING, "TOMHORN CREATE CAMPAIGN: " . json_encode($array) . " RESPONSE: " . $response);
            $response = json_decode($response);

            //validamos que la respuesta
            if ($response->Code != 0) {
                throw new Exception($response->Code . " " . $response->Message, "10000");
            }

            //obtenemos el codigo de la campaña creada
            $campaignCode = $response->Campaign->Code;
        }
        
        $usuarios_convertidos = array();
        foreach ($users as $user) {
            $Usuario = new Usuario($user);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            $usuario_convertido = 'Usuario' . $UsuarioMandante->usumandanteId;

            $usuarios_convertidos[] = $usuario_convertido;
        }

        $Players = implode(';', $usuarios_convertidos);

        //Estructura de la petición para procesar
        $array = array(
            "partnerID" => $PARTNER_ID,
            "players" => $Players,
            "campaignCode" => $campaignCode,
            "currency" => $currency
        );

        $array["sign"] = $this->GetSign($SIGN_KEY, $array["partnerID"] . $array["players"] . $array["campaignCode"] . $array["currency"]);
        $this->METHOD = "/AssignPlayerToCampaign";
        $response = $this->Request($array, $URL_API);
        
        syslog(LOG_WARNING, "TOMHORN ASSIGNMENT DATA: " . json_encode($array) . " RESPONSE: " . $response);
        $response = json_decode($response);

        //validamos la respuesta obtenida
        if ($response->Code != 0) {
            throw new Exception($response->Code . " " . $response->Message, "10000");
        }

        if ($response->Code == 0) {
            $array = array(
                "error" => false,
                "code" => 0,
                "response" => $response,
                "campaignCode" => $campaignCode 
            );
        } else {
            $array = array(
                "error" => true,
                "code" => 1,
                "response" => "Error en la asignación de freespin",
                "campaignCode" => $campaignCode
            );
        }

        return $array;
    }

    /**
     * Genera una firma HMAC-SHA256 para autenticar las solicitudes.
     *
     * @param string $key     Clave secreta para la firma.
     * @param string $message Mensaje a firmar.
     *
     * @return string Firma generada en formato hexadecimal.
     */
    function GetSign($key, $message)
    {
        return strtoupper(hash_hmac('sha256', pack('A*', $message), pack('A*', $key)));
    }
}
