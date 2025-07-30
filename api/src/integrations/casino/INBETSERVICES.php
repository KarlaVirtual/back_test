<?php

/**
 * Clase para integrar servicios de casino con el proveedor INBETSERVICES.
 *
 * Este archivo contiene métodos para gestionar la lista de juegos, obtener información
 * de un juego específico y generar claves únicas para jugadores.
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
use Backend\dto\Mandante;
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
 * Clase principal para la integración con INBETSERVICES.
 */
class INBETSERVICES
{

    /**
     * Identificador de la factura o transacción.
     *
     * @var string
     */
    private $Billing = "pi8deDae";

    /**
     * Constructor de la clase INBETSERVICES.
     */
    public function __construct()
    {
    }

    /**
     * Procesa y almacena la lista de juegos proporcionada por el proveedor.
     *
     * @param object $data Datos de la lista de juegos proporcionados por el proveedor.
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
                $Proveedor = new Proveedor("", "INB");
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
     * Obtiene la información de un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo "jugar por diversión".
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId Opcional ID del usuario mandante.
     *
     * @return object Respuesta con los datos del juego.
     * @throws Exception Si ocurre un error al obtener la información del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {
        try {
            $forFun = "";

            if ($play_for_fun) {
                $forFun = "|FUN";

                $array = array(
                    "error" => false,
                    "response" => array(
                        "game" => $gameid,
                        "proveedor" => "INB",
                        "billing" => $this->Billing,
                        "token" => "F246531u54321N" . $forFun,
                        "currency" => '',
                        "language" => $lang
                    )
                );
                return json_decode(json_encode($array));
            }


            try {
                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }


                $Proveedor = new Proveedor("", "INB");

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

                $array = array(
                    "error" => false,
                    "response" => array(
                        "game" => $gameid,
                        "proveedor" => "INB",
                        "billing" => $this->Billing,
                        "token" => $UsuarioToken->getToken() . $forFun,
                        "currency" => $UsuarioMandante->getMoneda(),
                        "language" => $lang
                    )
                );
                return json_decode(json_encode($array));
            } catch (Exception $e) {
                print_r($e);
            }
        } catch (Exception $e) {
        }
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

}

