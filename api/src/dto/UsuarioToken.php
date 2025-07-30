<?php

namespace Backend\dto;

use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;

/** 
 * Clase 'UsuarioToken'
 * 
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'UsuarioToken'
 * 
 * Ejemplo de uso: 
 * $UsuarioToken = new UsuarioToken();
 *   
 * 
 * @package ninguno 
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public 
 * @see no
 * 
 */
class UsuarioToken
{

    /**
     * Representación de la columna 'usutokenId' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $usutokenId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $usuarioId;

    /**
     * Representación de la columna 'proveedorId' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $proveedorId;

    /**
     * Representación de la columna 'token' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $token;

    /**
     * Representación de la columna 'requestId' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $requestId;

    /**
     * Representación de la columna 'cookie' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $cookie;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $usucreaId;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $fechaCrea;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $usumodifId;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $fechaModif;

    /**
     * Representación de la columna 'usuarioProveedor' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $usuarioProveedor;

    /**
     * Representación de la columna 'estado' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $estado;

    /**
     * Representación de la columna 'success' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $success;

    /**
     * Representación de la columna 'saldo' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $saldo;

    /**
     * Representación de la columna 'saldo' de la tabla 'UsuarioToken'
     *
     * @var string
     */
    public $productoId;





    /**
     * Constructor de clase
     *
     *
     * @param String $token token
     * @param String $proveedorId proveedorId
     * @param String $usuarioid usuarioid
     * @param String $cookie cookie
     * @param String $usuarioProveedor usuarioProveedor
     *
     * @return no
     * @throws Exception si UsuarioToken no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($token = "", $proveedorId = "", $usuarioid = "", $cookie = "", $usuarioProveedor = "", $productoId = "", $usutokenId = "", $estado = "")
    {

        if ($token != "" && $productoId != "") {

            $this->token = $token;

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO("");

            $UsuarioToken = $UsuarioTokenMySqlDAO->queryByTokenAndProductoId($token, $productoId, $estado);

            $UsuarioToken = $UsuarioToken[0];

            $this->success = false;

            if ($UsuarioToken != null && $UsuarioToken != "") {

                $this->usutokenId = $UsuarioToken->usutokenId;
                $this->usuarioId = $UsuarioToken->usuarioId;
                $this->requestId = $UsuarioToken->requestId;
                $this->cookie = $UsuarioToken->cookie;
                $this->usuarioProveedor = $UsuarioToken->usuarioProveedor;
                $this->proveedorId = $UsuarioToken->proveedorId;

                $this->productoId = $UsuarioToken->productoId;

                $this->usucreaId = $UsuarioToken->usucreaId;
                $this->fechaCrea = $UsuarioToken->fechaCrea;
                $this->usumodifId = $UsuarioToken->usumodifId;
                $this->fechaModif = $UsuarioToken->fechaModif;
                $this->estado = $UsuarioToken->estado;
                $this->saldo = $UsuarioToken->saldo;


                if ($this->usuarioProveedor == "") {
                    $this->usuarioProveedor = 0;
                }


                if ($this->saldo == "") {
                    $this->saldo = 0;
                }

                $this->success = true;
            } else {
                throw new Exception("No existe " . get_class($this), "21");
            }
        } elseif ($usuarioid != "" && $productoId != "") {


            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO("");

            $UsuarioToken = $UsuarioTokenMySqlDAO->queryByUsuarioAndProductoId($usuarioid, $productoId, $estado);

            $UsuarioToken = $UsuarioToken[0];

            $this->success = false;

            if ($UsuarioToken != null && $UsuarioToken != "") {

                $this->usutokenId = $UsuarioToken->usutokenId;
                $this->usuarioId = $UsuarioToken->usuarioId;
                $this->requestId = $UsuarioToken->requestId;
                $this->cookie = $UsuarioToken->cookie;
                $this->usuarioProveedor = $UsuarioToken->usuarioProveedor;
                $this->proveedorId = $UsuarioToken->proveedorId;

                $this->productoId = $UsuarioToken->productoId;

                $this->usucreaId = $UsuarioToken->usucreaId;
                $this->fechaCrea = $UsuarioToken->fechaCrea;
                $this->usumodifId = $UsuarioToken->usumodifId;
                $this->fechaModif = $UsuarioToken->fechaModif;
                $this->estado = $UsuarioToken->estado;
                $this->saldo = $UsuarioToken->saldo;
                $this->token = $UsuarioToken->token;


                if ($this->usuarioProveedor == "") {
                    $this->usuarioProveedor = 0;
                }


                if ($this->saldo == "") {
                    $this->saldo = 0;
                }

                $this->success = true;
            } else {
                throw new Exception("No existe " . get_class($this), "21");
            }
        } elseif ($token != "" && $proveedorId != "") {

            $this->token = $token;

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO("");

            $UsuarioToken = $UsuarioTokenMySqlDAO->queryByTokenAndProveedorId($token, $proveedorId, $estado);

            $UsuarioToken = $UsuarioToken[0];

            $this->success = false;

            if ($UsuarioToken != null && $UsuarioToken != "") {

                $this->usutokenId = $UsuarioToken->usutokenId;
                $this->usuarioId = $UsuarioToken->usuarioId;
                $this->requestId = $UsuarioToken->requestId;
                $this->cookie = $UsuarioToken->cookie;
                $this->usuarioProveedor = $UsuarioToken->usuarioProveedor;
                $this->proveedorId = $UsuarioToken->proveedorId;

                $this->productoId = $UsuarioToken->productoId;

                $this->usucreaId = $UsuarioToken->usucreaId;
                $this->fechaCrea = $UsuarioToken->fechaCrea;
                $this->usumodifId = $UsuarioToken->usumodifId;
                $this->fechaModif = $UsuarioToken->fechaModif;
                $this->estado = $UsuarioToken->estado;
                $this->saldo = $UsuarioToken->saldo;


                if ($this->usuarioProveedor == "") {
                    $this->usuarioProveedor = 0;
                }


                if ($this->saldo == "") {
                    $this->saldo = 0;
                }

                $this->success = true;
            } else {
                throw new Exception("No existe " . get_class($this), "21");
            }
        } elseif ($usuarioid != "" && $proveedorId != "") {

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

            $UsuarioToken = $UsuarioTokenMySqlDAO->queryByUsuarioIdAndProveedorId($usuarioid, $proveedorId, $estado);

            $UsuarioToken = $UsuarioToken[0];

            $this->success = false;

            if ($UsuarioToken != null && $UsuarioToken != "") {

                $this->usutokenId = $UsuarioToken->usutokenId;

                $this->usuarioId = $UsuarioToken->usuarioId;
                $this->token = $UsuarioToken->token;
                $this->cookie = $UsuarioToken->cookie;
                $this->requestId = $UsuarioToken->requestId;
                $this->usuarioProveedor = $UsuarioToken->usuarioProveedor;
                $this->proveedorId = $UsuarioToken->proveedorId;

                $this->productoId = $UsuarioToken->productoId;

                $this->usucreaId = $UsuarioToken->usucreaId;
                $this->fechaCrea = $UsuarioToken->fechaCrea;
                $this->usumodifId = $UsuarioToken->usumodifId;
                $this->fechaModif = $UsuarioToken->fechaModif;
                $this->estado = $UsuarioToken->estado;
                $this->saldo = $UsuarioToken->saldo;

                if ($this->saldo == "") {
                    $this->saldo = 0;
                }

                if ($this->usuarioProveedor == "") {
                    $this->usuarioProveedor = 0;
                }

                $this->success = true;
            } else {
                throw new Exception("No existe " . $usuarioid . " " . get_class($this), "21");
            }
        } elseif ($cookie != "") {

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

            $UsuarioToken = $UsuarioTokenMySqlDAO->queryByCookie($cookie);

            $UsuarioToken = $UsuarioToken[0];

            $this->success = false;

            if ($UsuarioToken != null && $UsuarioToken != "") {

                $this->usutokenId = $UsuarioToken->usutokenId;

                $this->usuarioId = $UsuarioToken->usuarioId;
                $this->token = $UsuarioToken->token;
                $this->requestId = $UsuarioToken->requestId;
                $this->cookie = $UsuarioToken->cookie;
                $this->usuarioProveedor = $UsuarioToken->usuarioProveedor;
                $this->proveedorId = $UsuarioToken->proveedorId;

                $this->productoId = $UsuarioToken->productoId;

                $this->usucreaId = $UsuarioToken->usucreaId;
                $this->fechaCrea = $UsuarioToken->fechaCrea;
                $this->usumodifId = $UsuarioToken->usumodifId;
                $this->fechaModif = $UsuarioToken->fechaModif;
                $this->usuarioProveedor = $UsuarioToken->usuarioProveedor;
                $this->estado = $UsuarioToken->estado;
                $this->saldo = $UsuarioToken->saldo;

                if ($this->saldo == "") {
                    $this->saldo = 0;
                }

                if ($this->usuarioProveedor == "") {
                    $this->usuarioProveedor = 0;
                }

                $this->success = true;
            } else {
                throw new Exception("No existe " . $cookie . " " . get_class($this), "21");
            }
        } elseif ($usuarioProveedor != "" && $proveedorId != "") {

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

            $UsuarioToken = $UsuarioTokenMySqlDAO->queryByUsuarioProveedor($usuarioProveedor, $proveedorId);

            $UsuarioToken = $UsuarioToken[0];

            $this->success = false;

            if ($UsuarioToken != null && $UsuarioToken != "") {

                $this->usutokenId = $UsuarioToken->usutokenId;

                $this->usuarioId = $UsuarioToken->usuarioId;
                $this->token = $UsuarioToken->token;
                $this->requestId = $UsuarioToken->requestId;
                $this->cookie = $UsuarioToken->cookie;
                $this->usuarioProveedor = $UsuarioToken->usuarioProveedor;
                $this->proveedorId = $UsuarioToken->proveedorId;

                $this->productoId = $UsuarioToken->productoId;

                $this->usucreaId = $UsuarioToken->usucreaId;
                $this->fechaCrea = $UsuarioToken->fechaCrea;
                $this->usumodifId = $UsuarioToken->usumodifId;
                $this->fechaModif = $UsuarioToken->fechaModif;
                $this->usuarioProveedor = $UsuarioToken->usuarioProveedor;
                $this->estado = $UsuarioToken->estado;
                $this->saldo = $UsuarioToken->saldo;

                if ($this->usuarioProveedor == "") {
                    $this->usuarioProveedor = 0;
                }

                if ($this->saldo == "") {
                    $this->saldo = 0;
                }

                $this->success = true;
            } else {
                throw new Exception("No existe " . $usuarioProveedor . " " . get_class($this), "21");
            }
        } elseif ($usutokenId != "") {

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

            $UsuarioToken = $UsuarioTokenMySqlDAO->load($usutokenId);

            $this->success = false;

            if ($UsuarioToken != null && $UsuarioToken != "") {

                $this->usutokenId = $UsuarioToken->usutokenId;

                $this->usuarioId = $UsuarioToken->usuarioId;
                $this->token = $UsuarioToken->token;
                $this->requestId = $UsuarioToken->requestId;
                $this->cookie = $UsuarioToken->cookie;
                $this->usuarioProveedor = $UsuarioToken->usuarioProveedor;
                $this->proveedorId = $UsuarioToken->proveedorId;

                $this->productoId = $UsuarioToken->productoId;

                $this->usucreaId = $UsuarioToken->usucreaId;
                $this->fechaCrea = $UsuarioToken->fechaCrea;
                $this->usumodifId = $UsuarioToken->usumodifId;
                $this->fechaModif = $UsuarioToken->fechaModif;
                $this->usuarioProveedor = $UsuarioToken->usuarioProveedor;
                $this->estado = $UsuarioToken->estado;
                $this->saldo = $UsuarioToken->saldo;

                if ($this->usuarioProveedor == "") {
                    $this->usuarioProveedor = 0;
                }

                if ($this->saldo == "") {
                    $this->saldo = 0;
                }

                $this->success = true;
            } else {
                throw new Exception("No existe " . get_class($this), "21");
            }
        }
    }





    /**
     * Obtener el campo getUsutokenId de un objeto
     *
     * @return String getUsutokenId getUsutokenId
     * 
     */
    public function getUsutokenId()
    {
        return $this->usutokenId;
    }

    /**
     * Modificar el campo 'usutokenId' de un objeto
     *
     * @param String $usutokenId usutokenId
     *
     * @return no
     *
     */
    public function setUsutokenId($usutokenId)
    {
        $this->usutokenId = $usutokenId;
    }

    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     * 
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId usuarioId
     *
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo proveedorId de un objeto
     *
     * @return String proveedorId proveedorId
     * 
     */
    public function getProveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * Modificar el campo 'proveedorId' de un objeto
     *
     * @param String $proveedorId proveedorId
     *
     * @return no
     *
     */
    public function setProveedorId($proveedorId)
    {
        $this->proveedorId = $proveedorId;
    }

    /**
     * Obtener el campo token de un objeto
     *
     * @return String token token
     * 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Modificar el campo 'token' de un objeto
     *
     * @param String $token token
     *
     * @return no
     *
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId usucreaId
     * 
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo 'usucreaId' de un objeto
     *
     * @param String $usucreaId usucreaId
     *
     * @return no
     *
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     * 
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param String $fechaCrea fechaCrea
     *
     * @return no
     *
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String usumodifId usumodifId
     * 
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo 'usumodifId' de un objeto
     *
     * @param String $usumodifId usumodifId
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el campo fechaModif de un objeto
     *
     * @return String fechaModif fechaModif
     * 
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Modificar el campo 'fechaModif' de un objeto
     *
     * @param String $fechaModif fechaModif
     *
     * @return no
     *
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtener el campo requestId de un objeto
     *
     * @return String requestId requestId
     * 
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * Modificar el campo 'requestId' de un objeto
     *
     * @param String $requestId requestId
     *
     * @return no
     *
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     * Obtener el campo cookie de un objeto
     *
     * @return String cookie cookie
     * 
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Modificar el campo 'cookie' de un objeto
     *
     * @param String $cookie cookie
     *
     * @return no
     *
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * Obtener el campo saldo de un objeto
     *
     * @return String saldo saldo
     * 
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Modificar el campo 'saldo' de un objeto
     *
     * @param String $saldo saldo
     *
     * @return no
     *
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
    }

    /**
     * Obtener el campo usuarioProveedor de un objeto
     *
     * @return String usuarioProveedor usuarioProveedor
     * 
     */
    public function getUsuarioProveedor()
    {
        return $this->usuarioProveedor;
    }

    /**
     * Modificar el campo 'usuarioProveedor' de un objeto
     *
     * @param String $usuarioProveedor usuarioProveedor
     *
     *
     */
    public function setUsuarioProveedor($usuarioProveedor)
    {
        $this->usuarioProveedor = $usuarioProveedor;
    }

    /**
     * Obtener el campo productoId de un objeto
     *
     * @return String productoId productoId
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * Modificar el campo 'productoId' de un objeto
     *
     * @param String $productoId productoId
     *
     * @return no
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     *
     * @param String $estado estado
     *
     * @return no
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }






    /**
     * Realizar una consulta en la tabla de UsuarioToken 'UsuarioToken'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta 
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return Array resultado de la consulta
     * @throws Exception si los deportes no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

        $Usuarios = $UsuarioTokenMySqlDAO->queryUsuariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Usuarios != null && $Usuarios != "") {
            return $Usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    /**
     * Crear un token
     *
     *
     * @param no
     *
     * @return String $ token
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function createToken()
    {
        return $this->proveedorId . 'P' . $this->usuarioId . 'P' . $this->get_rand_alphanumeric(28  - (strlen($this->usuarioId) + strlen($this->proveedorId)));
    }

    /**
     * Generar una cadena alfanumérica aleatoria
     *
     *
     * @param int $length largo de la cadena
     *
     * @return String $rand_id cadena aleatoria alfanumérica
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function get_rand_alphanumeric($length)
    {
        if ($length > 0) {
            $rand_id = "";
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((float)microtime() * 1000000);
                $num = mt_rand(1, 36);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return $rand_id;
    }


    /**
     * Retornar una letra a partir de un número
     *
     *
     * @param int $num número en cuestión
     *
     * @return String $rand_value valor de la letra
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function assign_rand_value($num)
    {

        // accepts 1 - 36
        switch ($num) {
            case "1":
                $rand_value = "a";
                break;
            case "2":
                $rand_value = "b";
                break;
            case "3":
                $rand_value = "c";
                break;
            case "4":
                $rand_value = "d";
                break;
            case "5":
                $rand_value = "e";
                break;
            case "6":
                $rand_value = "f";
                break;
            case "7":
                $rand_value = "g";
                break;
            case "8":
                $rand_value = "h";
                break;
            case "9":
                $rand_value = "i";
                break;
            case "10":
                $rand_value = "j";
                break;
            case "11":
                $rand_value = "k";
                break;
            case "12":
                $rand_value = "l";
                break;
            case "13":
                $rand_value = "m";
                break;
            case "14":
                $rand_value = "n";
                break;
            case "15":
                $rand_value = "o";
                break;
            case "16":
                $rand_value = "p";
                break;
            case "17":
                $rand_value = "q";
                break;
            case "18":
                $rand_value = "r";
                break;
            case "19":
                $rand_value = "s";
                break;
            case "20":
                $rand_value = "t";
                break;
            case "21":
                $rand_value = "u";
                break;
            case "22":
                $rand_value = "v";
                break;
            case "23":
                $rand_value = "w";
                break;
            case "24":
                $rand_value = "x";
                break;
            case "25":
                $rand_value = "y";
                break;
            case "26":
                $rand_value = "z";
                break;
            case "27":
                $rand_value = "0";
                break;
            case "28":
                $rand_value = "1";
                break;
            case "29":
                $rand_value = "2";
                break;
            case "30":
                $rand_value = "3";
                break;
            case "31":
                $rand_value = "4";
                break;
            case "32":
                $rand_value = "5";
                break;
            case "33":
                $rand_value = "6";
                break;
            case "34":
                $rand_value = "7";
                break;
            case "35":
                $rand_value = "8";
                break;
            case "36":
                $rand_value = "9";
                break;
        }
        return $rand_value;
    }

    /**
     * Generar una cadena numérica aleatoria
     *
     *
     * @param int $length largo de la cadena
     *
     * @return String $rand_id cadena aleatoria numérica
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function get_rand_numbers($length)
    {
        if ($length > 0) {
            $rand_id = "";
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((float)microtime() * 1000000);
                $num = mt_rand(27, 36);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return $rand_id;
    }

    /**
     * Generar una cadena de letras aleatoria
     *
     *
     * @param int $length largo de la cadena
     *
     * @return String $rand_id cadena aleatoria de letras
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function get_rand_letters($length)
    {
        if ($length > 0) {
            $rand_id = "";
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((float)microtime() * 1000000);
                $num = mt_rand(1, 26);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return $rand_id;
    }
}
