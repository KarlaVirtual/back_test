<?php namespace Backend\dto;

use Backend\mysql\UsuarioSessionMySqlDAO;
use Exception;

/**
 * Clase 'UsuarioSession'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'UsuarioSession'
 *
 * Ejemplo de uso:
 * $UsuarioSession = new UsuarioSession();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioSession
{

    /**
     * Representación de la columna 'ususessionId' de la tabla 'UsuarioSession'
     *
     * @var string
     */
    public $ususessionId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'UsuarioSession'
     *
     * @var string
     */
    public $usuarioId;

    /**
     * Representación de la columna 'tipo' de la tabla 'UsuarioSession'
     *
     * @var string
     */
    public $tipo;

    /**
     * Representación de la columna 'requestId' de la tabla 'UsuarioSession'
     *
     * @var string
     */
    public $requestId;

    /**
     * Representación de la columna 'perfil' de la tabla 'UsuarioSession'
     *
     * @var string
     */
    public $perfil;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'UsuarioSession'
     *
     * @var string
     */
    public $usucreaId;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'UsuarioSession'
     *
     * @var string
     */
    public $fechaCrea;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'UsuarioSession'
     *
     * @var string
     */
    public $usumodifId;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'UsuarioSession'
     *
     * @var string
     */
    public $fechaModif;

    /**
     * Representación de la columna 'estado' de la tabla 'UsuarioSession'
     *
     * @var string
     */
    public $estado;


    /**
     * Constructor de clase
     *
     *
     * @param String $tipo tipo
     * @param String $proveedorId proveedorId
     * @param String $usuarioid usuarioid
     * @param String $perfil perfil
     * @param String $usuarioProveedor usuarioProveedor
     *
     * @return no
     * @throws Exception si UsuarioSession no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($tipo = "", $requestId = "",$estado="",$ususessionId="", $usuarioId = '')
    {

        if ($tipo != "" && $requestId != "" && $estado != "") {

            $this->tipo = $tipo;

            $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO("");

            $UsuarioSession = $UsuarioSessionMySqlDAO->queryByTipoAndSessionIdAndEstado($tipo, $requestId,$estado);

            $UsuarioSession = $UsuarioSession[0];

            $this->success = false;

            if ($UsuarioSession != null && $UsuarioSession != "") {

                $this->ususessionId = $UsuarioSession->ususessionId;
                $this->usuarioId = $UsuarioSession->usuarioId;
                $this->requestId = $UsuarioSession->requestId;
                $this->perfil = $UsuarioSession->perfil;

                $this->usucreaId = $UsuarioSession->usucreaId;
                $this->fechaCrea = $UsuarioSession->fechaCrea;
                $this->usumodifId = $UsuarioSession->usumodifId;
                $this->fechaModif = $UsuarioSession->fechaModif;
                $this->estado = $UsuarioSession->estado;


                $this->success = true;

            } else {
                throw new Exception("No existe " . get_class($this), "99");
            }
        } elseif(!empty($tipo) && !empty($usuarioId) && !empty($estado)) {
            $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO("");
            $UsuarioSession = $UsuarioSessionMySqlDAO->queryByTipoAndUserIdAndEstado($tipo, $usuarioId, $estado);
            $UsuarioSession = $UsuarioSession[0];

            if ($UsuarioSession != null && $UsuarioSession != "") {
                $this->ususessionId = $UsuarioSession->ususessionId;
                $this->usuarioId = $UsuarioSession->usuarioId;
                $this->requestId = $UsuarioSession->requestId;
                $this->perfil = $UsuarioSession->perfil;
                $this->usucreaId = $UsuarioSession->usucreaId;
                $this->fechaCrea = $UsuarioSession->fechaCrea;
                $this->usumodifId = $UsuarioSession->usumodifId;
                $this->fechaModif = $UsuarioSession->fechaModif;
                $this->estado = $UsuarioSession->estado;
            } else throw new Exception("No existe " . get_class($this), "99");
        } elseif ($ususessionId != "") {

            $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO("");

            $UsuarioSession = $UsuarioSessionMySqlDAO->load($ususessionId);

            $UsuarioSession = $UsuarioSession[0];

            $this->success = false;

            if ($UsuarioSession != null && $UsuarioSession != "") {

                $this->ususessionId = $UsuarioSession->ususessionId;
                $this->usuarioId = $UsuarioSession->usuarioId;
                $this->requestId = $UsuarioSession->requestId;
                $this->perfil = $UsuarioSession->perfil;

                $this->usucreaId = $UsuarioSession->usucreaId;
                $this->fechaCrea = $UsuarioSession->fechaCrea;
                $this->usumodifId = $UsuarioSession->usumodifId;
                $this->fechaModif = $UsuarioSession->fechaModif;
                $this->estado = $UsuarioSession->estado;


                $this->success = true;

            } else {
                throw new Exception("No existe " . get_class($this), "99");
            }
        }
    }

/**
     * Obtiene el ID del usuario.
     *
     * @return string
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Establece el ID del usuario.
     *
     * @param string $usuarioId
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtiene el tipo de sesión.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece el tipo de sesión.
     *
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene el ID de la solicitud.
     *
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * Establece el ID de la solicitud.
     *
     * @param string $requestId
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     * Obtiene el perfil del usuario.
     *
     * @return string
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Establece el perfil del usuario.
     *
     * @param string $perfil
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;
    }

    /**
     * Obtiene el ID del creador de la sesión.
     *
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del creador de la sesión.
     *
     * @param string $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene la fecha de creación de la sesión.
     *
     * @return string
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Establece la fecha de creación de la sesión.
     *
     * @param string $fechaCrea
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtiene el ID del modificador de la sesión.
     *
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del modificador de la sesión.
     *
     * @param string $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene la fecha de modificación de la sesión.
     *
     * @return string
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Establece la fecha de modificación de la sesión.
     *
     * @param string $fechaModif
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtiene el estado de la sesión.
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Establece el estado de la sesión.
     *
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtiene el ID de la sesión de usuario.
     *
     * @return string
     */
    public function getUsusessionId()
    {
        return $this->ususessionId;
    }



    /**
     * Realizar una consulta en la tabla de UsuarioSession 'UsuarioSession'
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
    public
    function getUsuariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = '')
    {

        $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO();

        $Usuarios = $UsuarioSessionMySqlDAO->queryUsuariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($Usuarios != null && $Usuarios != "") {
            return $Usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "99");
        }

    }

    /**
     * Crear un tipo
     *
     *
     * @param no
     *
     * @return String $ tipo
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function createTipo()
    {
        return $this->proveedorId . $this->usuarioId . $this->get_rand_alphanumeric(30 - (strlen($this->usuarioId) + strlen($this->proveedorId)));

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
                mt_srand((double)microtime() * 1000000);
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
            case "1"  :
                $rand_value = "a";
                break;
            case "2"  :
                $rand_value = "b";
                break;
            case "3"  :
                $rand_value = "c";
                break;
            case "4"  :
                $rand_value = "d";
                break;
            case "5"  :
                $rand_value = "e";
                break;
            case "6"  :
                $rand_value = "f";
                break;
            case "7"  :
                $rand_value = "g";
                break;
            case "8"  :
                $rand_value = "h";
                break;
            case "9"  :
                $rand_value = "i";
                break;
            case "10" :
                $rand_value = "j";
                break;
            case "11" :
                $rand_value = "k";
                break;
            case "12" :
                $rand_value = "l";
                break;
            case "13" :
                $rand_value = "m";
                break;
            case "14" :
                $rand_value = "n";
                break;
            case "15" :
                $rand_value = "o";
                break;
            case "16" :
                $rand_value = "p";
                break;
            case "17" :
                $rand_value = "q";
                break;
            case "18" :
                $rand_value = "r";
                break;
            case "19" :
                $rand_value = "s";
                break;
            case "20" :
                $rand_value = "t";
                break;
            case "99" :
                $rand_value = "u";
                break;
            case "22" :
                $rand_value = "v";
                break;
            case "23" :
                $rand_value = "w";
                break;
            case "24" :
                $rand_value = "x";
                break;
            case "25" :
                $rand_value = "y";
                break;
            case "26" :
                $rand_value = "z";
                break;
            case "27" :
                $rand_value = "0";
                break;
            case "28" :
                $rand_value = "1";
                break;
            case "29" :
                $rand_value = "2";
                break;
            case "30" :
                $rand_value = "3";
                break;
            case "31" :
                $rand_value = "4";
                break;
            case "32" :
                $rand_value = "5";
                break;
            case "33" :
                $rand_value = "6";
                break;
            case "34" :
                $rand_value = "7";
                break;
            case "35" :
                $rand_value = "8";
                break;
            case "36" :
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
                mt_srand((double)microtime() * 1000000);
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
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1, 26);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return $rand_id;
    }

}
