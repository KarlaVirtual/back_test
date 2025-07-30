<?php

/**
 * Clase para integrar servicios de casino END2END.
 *
 * Este archivo contiene la implementación de la clase `END2ENDSERVICES`,
 * que permite la integración con servicios de casino, incluyendo la gestión
 * de juegos, usuarios y transacciones.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;

/**
 * Clase principal para la integración con los servicios de casino END2END.
 */
class END2ENDSERVICES
{
    /**
     * URL base para las solicitudes HTTP.
     *
     * @var string|null
     */
    private $url = null;

    /**
     * URL de desarrollo para las solicitudes HTTP.
     *
     * @var string
     */
    private $urlDev = "http://i.s1.end2end.com.ar/f7823ef6c345c3/";

    /**
     * URL de producción para las solicitudes HTTP.
     *
     * @var string|null
     */
    private $urlProd = null;

    /**
     * Identificador único para las solicitudes.
     *
     * @var string|null
     */
    private $identifier = null;

    /**
     * Identificador único de desarrollo para las solicitudes.
     *
     * @var string
     */
    private $identifierDev = "2cb7ef79981f1acd29ec216c6fec9b973785a42a";

    /**
     * Identificador único de producción para las solicitudes.
     *
     * @var string|null
     */
    private $identifierProd = null;

    /**
     * Método HTTP utilizado en las solicitudes.
     *
     * @var string|null
     */
    private $method = null;

    /**
     * Constructor de la clase.
     *
     * Inicializa las variables de entorno dependiendo del ambiente
     * (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->url = $this->urlDev;
            $this->identifier = $this->identifierDev;
        } else {
            $this->url = $this->urlProd;
            $this->identifier = $this->identifierProd;
        }
    }

    /**
     * Registra una lista de juegos en la base de datos.
     *
     * @param object $data Datos de los juegos a registrar.
     *
     * @return void
     * @throws Exception Si ocurre un error durante la transacción.
     */
    public function setGameList($data)
    {
        $response = $data;

        $error = false;
        $games = $response->applications;

        if ( ! $error) {
            $ProductoMySqlDAO = new ProductoMySqlDAO();
            $Transaction = $ProductoMySqlDAO->getTransaction();

            try {
                $Proveedor = new Proveedor("", "E2E");

                if ($Proveedor != null && $Proveedor != "") {
                    foreach ($games as $clave => $game) {
                        if ($Transaction->isIsconnected()) {
                            $Producto = new Producto();

                            $Producto->setEstado("A");
                            $Producto->setProveedorId($Proveedor->getProveedorId());
                            $Producto->setDescripcion(str_replace("'", "", $game->name[0]->en));
                            $Producto->setImageUrl("http://games.inbetgames.com/static/" . $game->preview);
                            $Producto->setVerifica("I");
                            $Producto->setExternoId($game->source);
                            $Producto->setUsucreaId(0);
                            $Producto->setUsumodifId(0);

                            if ( ! $Producto->existsExternoId()) {
                                $producto_id = $Producto->insert($Transaction);

                                $slug = "";

                                switch ($game->type) {
                                    case "slot":
                                        $slug = "slots";
                                        break;

                                    default:
                                        $slug = "catinbet";
                                        break;
                                }

                                $Categoria = new Categoria("", "", $slug);

                                $CategoriaProducto = new CategoriaProducto();

                                $CategoriaProducto->setProductoId($producto_id);
                                $CategoriaProducto->setCategoriaId($Categoria->getCategoriaId());
                                $CategoriaProducto->setUsumodifId(0);
                                $CategoriaProducto->setUsucreaId(0);

                                $CategoriaProducto->insert($Transaction);


                                $ProductoDetalle = new ProductoDetalle();
                                $ProductoDetalle->setProductoId($producto_id);
                                $ProductoDetalle->setPKey("GAMEID");
                                $ProductoDetalle->setPValue($clave);
                                $ProductoDetalle->setUsucreaId(0);
                                $ProductoDetalle->setUsumodifId(0);

                                $ProductoDetalle->insert($Transaction);

                                $ProductoDetalle = new ProductoDetalle();
                                $ProductoDetalle->setProductoId($producto_id);
                                $ProductoDetalle->setPKey("TYPE");
                                $ProductoDetalle->setPValue($game->type);
                                $ProductoDetalle->setUsucreaId(0);
                                $ProductoDetalle->setUsumodifId(0);

                                $ProductoDetalle->insert($Transaction);

                                $ProductoDetalle = new ProductoDetalle();
                                $ProductoDetalle->setProductoId($producto_id);
                                $ProductoDetalle->setPKey("IMAGE_BACKGROUND");
                                $ProductoDetalle->setPValue("https://i.pinimg.com/originals/33/92/f9/3392f99c1dd718211711848b811dd8da.jpg");
                                $ProductoDetalle->setUsucreaId(0);
                                $ProductoDetalle->setUsumodifId(0);

                                $ProductoDetalle->insert($Transaction);


                                foreach ($game as $key => $val) {
                                    if ($key != "id" && $key != "type") {
                                        $ProductoDetalle = new ProductoDetalle();
                                        $ProductoDetalle->setProductoId($producto_id);
                                        print_r($key);
                                        $ProductoDetalle->setPKey(strtoupper($key));

                                        if (is_object($val)) {
                                            $ProductoDetalle->setPValue(str_replace("'", "", json_encode($val)));
                                        } else {
                                            $ProductoDetalle->setPValue(str_replace("'", "", $val));
                                        }

                                        $ProductoDetalle->setUsucreaId(0);
                                        $ProductoDetalle->setUsumodifId(0);

                                        $ProductoDetalle->insert($Transaction);
                                    }
                                }
                            }
                        }
                    }

                    $Transaction->commit();
                }
            } catch (Exception $e) {
                print_r($e);
                $Transaction->rollback();
            }
        }
    }

    /**
     * Obtiene la URL de un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es para diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID alternativo del juego (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object|null Respuesta con la URL del juego o null en caso de error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid = "", $usumandanteId = "")
    {
        try {
            $forFun = "";

            try {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                $Proveedor = new Proveedor("", "E2E");

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
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

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }


                $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
                $Pais = new Pais($UsuarioMandante->paisId);
                $Mandante = new Mandante($UsuarioMandante->getMandante());

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
                }

                $array = array(
                    "userData" => [
                        "id" => $UsuarioMandante->usuarioMandante,
                        "name" => $UsuarioMandante->nombres,
                        "password" => sha1("password"),
                        "currencyCode" => $UsuarioMandante->moneda,
                        "languageCode" => $lang,
                        "userType" => 1
                    ],
                    "bingoData" => [
                        "mode" => 1,
                        "bingoRoomId" => null,
                        "enableChat" => true,
                        "customProperties" => ""
                    ]

                );
                $this->method = "launchBingoGame/";


                $response = $this->connection($array);
                $arrayUrl = explode('=', $response->url);
                $token = $arrayUrl[1];

                $UsuarioToken->setToken($token);

                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();

                $array = array(
                    "error" => false,
                    "response" => $response->url,
                );

                return json_decode(json_encode($array));
            } catch (Exception $e) {
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una conexión HTTP con los datos proporcionados.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return object|null Respuesta decodificada de la conexión o null en caso de error.
     */
    public function connection($data)
    {
        $headers = array(
            "Content-type: application/json",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
        );


        $ch = curl_init($this->url . $this->method . "?accountIdentifier=" . $this->identifier);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $result = json_decode(curl_exec($ch));

        return ($result);
    }

    /**
     * Genera una clave única basada en el identificador del jugador.
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
     * Obtiene la dirección IP del cliente.
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


}

