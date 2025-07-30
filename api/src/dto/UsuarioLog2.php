<?php namespace Backend\dto;

use Backend\imports\MobileDetect\MobileDetect;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Exception;

/**
 * Clase 'UsuarioLog2'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'UsuarioLog2'
 *
 * Ejemplo de uso:
 * $UsuarioLog2 = new UsuarioLog2();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioLog2
{

    /**
     * Representación de la columna 'usuariolog2Id' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $usuariolog2Id;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'usuarioaprobarId' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $usuarioaprobarId;

    /**
     * Representación de la columna 'usuarioIp' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $usuarioIp;

    /**
     * Representación de la columna 'usuarioaprobarIp' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $usuarioaprobarIp;

    /**
     * Representación de la columna 'usuariosolicitaId' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $usuariosolicitaId;

    /**
     * Representación de la columna 'usuariosolicitaIp' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $usuariosolicitaIp;

    /**
     * Representación de la columna 'tipo' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'estado' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'valorAntes' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $valorAntes;

    /**
     * Representación de la columna 'valorDespues' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $valorDespues;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'dispositivo' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $dispositivo;

    /**
     * Representación de la columna 'soperativo' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $soperativo;

    /**
     * Representación de la columna 'sversion' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $sversion;

    /**
     * Representación de la columna 'imagen' de la tabla 'UsuarioLog2'
     *
     * @var string
     */
    var $imagen;


    /**
     * Constructor de clase
     *
     *
     * @param String $usuariolog2Id usuariolog2Id
     *
     * @return no
     * @throws Exception si UsuarioLog2 no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($usuariolog2Id = "")
    {

        if ($usuariolog2Id != "") {

            $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();

            $UsuarioLog2 = $UsuarioLog2MySqlDAO->load($usuariolog2Id);

            if ($UsuarioLog2 != null && $UsuarioLog2 != "") {
                $this->usuariolog2Id = $UsuarioLog2->usuariolog2Id;
                $this->usuarioId = $UsuarioLog2->usuarioId;
                $this->usuarioaprobarId = $UsuarioLog2->usuarioaprobarId;
                $this->usuariosolicitaIp = $UsuarioLog2->usuariosolicitaIp;
                $this->usuariosolicitaId = $UsuarioLog2->usuariosolicitaId;

                $this->usuarioIp = $UsuarioLog2->usuarioIp;
                $this->usuarioaprobarIp = $UsuarioLog2->usuarioaprobarIp;
                $this->tipo = $UsuarioLog2->tipo;
                $this->valor = $UsuarioLog2->valor;
                $this->valorAntes = $UsuarioLog2->valorAntes;
                $this->valorDespues = $UsuarioLog2->valorDespues;
                $this->usucreaId = $UsuarioLog2->usucreaId;
                $this->fechaCrea = $UsuarioLog2->fechaCrea;
                $this->usumodifId = $UsuarioLog2->usumodifId;
                $this->dispositivo = $UsuarioLog2->dispositivo;
                $this->soperativo = $UsuarioLog2->soperativo;
                $this->sversion = $UsuarioLog2->sversion;
                $this->imagen = $UsuarioLog2->imagen;
                $this->estado = $UsuarioLog2->estado;
            } else {
                throw new Exception("No existe " . get_class($this), "100");
            }
        }


    }


    /**
     * Realizar una consulta en la tabla de UsuarioLog2 'UsuarioLog2'
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
     * @throws Exception si el usuario no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuarioLog2sCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();

        $Usuario = $UsuarioLog2MySqlDAO->queryUsuarioLog2sCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Usuario != null && $Usuario != "") {
            return $Usuario;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    //

    /**
     * Realizar una consulta en la tabla de UsuarioLog2 'UsuarioLog2'
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
     * @throws Exception si el usuario no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuarioLog2sCustomPuntoVenta($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();

        $Usuario = $UsuarioLog2MySqlDAO->queryUsuarioLog2sCustomPuntoVenta($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Usuario != null && $Usuario != "") {
            return $Usuario;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }

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
     * Obtener el campo usuarioaprobarId de un objeto
     *
     * @return String usuarioaprobarId usuarioaprobarId
     *
     */
    public function getProveedorId()
    {
        return $this->usuarioaprobarId;
    }

    /**
     * Modificar el campo 'usuarioaprobarId' de un objeto
     *
     * @param String $usuarioaprobarId usuarioaprobarId
     *
     * @return no
     *
     */
    public function setProveedorId($usuarioaprobarId)
    {
        $this->usuarioaprobarId = $usuarioaprobarId;
    }

    /**
     * Obtener el campo tipo de un objeto
     *
     * @return String tipo tipo
     *
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Modificar el campo 'tipo' de un objeto
     *
     * @param String $tipo tipo
     *
     * @return no
     *
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        $MobileDetect = new MobileDetect();

        $dispositivo = "Desktop";
        $soperativo = "";
        $sversion = "";

        if ($MobileDetect->isMobile()) {
            $dispositivo = "Mobile";
        }

        if ($MobileDetect->isTablet()) {
            $dispositivo = "Tablet";
        }

        if ($MobileDetect->is('iOs')) {
            $soperativo = "iOS";

        }


        $this->setDispositivo($dispositivo);
        $this->setSoperativo($soperativo);
        $this->setSversion($sversion);

    }

    /**
     * Obtener el campo valorAntes de un objeto
     *
     * @return String valorAntes valorAntes
     *
     */
    public function getValorAntes()
    {
        return $this->valorAntes;
    }

    /**
     * Modificar el campo 'valorAntes' de un objeto
     *
     * @param String $valorAntes valorAntes
     *
     * @return no
     *
     */
    public function setValorAntes($valorAntes)
    {
        $this->valorAntes = $valorAntes;
    }

    /**
     * Obtener el campo valorDespues de un objeto
     *
     * @return String valorDespues valorDespues
     *
     */
    public function getValorDespues()
    {
        return $this->valorDespues;
    }

    /**
     * Modificar el campo 'valorDespues' de un objeto
     *
     * @param String $valorDespues valorDespues
     *
     * @return no
     *
     */
    public function setValorDespues($valorDespues)
    {
        $this->valorDespues = $valorDespues;
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
     * Obtener el campo usuarioaprobarId de un objeto
     *
     * @return String usuarioaprobarId usuarioaprobarId
     *
     */
    public function getUsuarioaprobarId()
    {
        return $this->usuarioaprobarId;
    }

    /**
     * Modificar el campo 'usuarioaprobarId' de un objeto
     *
     * @param String $usuarioaprobarId usuarioaprobarId
     *
     * @return no
     *
     */
    public function setUsuarioaprobarId($usuarioaprobarId)
    {
        $this->usuarioaprobarId = $usuarioaprobarId;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     *
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
     *
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo usuarioIp de un objeto
     *
     * @return String usuarioIp usuarioIp
     *
     */
    public function getUsuarioIp()
    {
        return $this->usuarioIp;
    }

    /**
     * Modificar el campo 'usuarioIp' de un objeto
     *
     * @param String $usuarioIp usuarioIp
     *
     * @return no
     *
     */
    public function setUsuarioIp($usuarioIp)
    {
        $this->usuarioIp = $usuarioIp;
    }

    /**
     * Obtener el campo usuarioaprobarIp de un objeto
     *
     * @return String usuarioaprobarIp usuarioaprobarIp
     *
     */
    public function getUsuarioaprobarIp()
    {
        return $this->usuarioaprobarIp;
    }

    /**
     * Modificar el campo 'usuarioaprobarIp' de un objeto
     *
     * @param String $usuarioaprobarIp usuarioaprobarIp
     *
     * @return no
     *
     */
    public function setUsuarioaprobarIp($usuarioaprobarIp)
    {
        $this->usuarioaprobarIp = $usuarioaprobarIp;
    }

    /**
     * Obtener el campo usuariosolicitaId de un objeto
     *
     * @return String usuariosolicitaId usuariosolicitaId
     *
     */
    public function getUsuariosolicitaId()
    {
        return $this->usuariosolicitaId;
    }

    /**
     * Modificar el campo 'usuariosolicitaId' de un objeto
     *
     * @param String $usuariosolicitaId usuariosolicitaId
     *
     * @return no
     *
     */
    public function setUsuariosolicitaId($usuariosolicitaId)
    {
        $this->usuariosolicitaId = $usuariosolicitaId;
    }

    /**
     * Obtener el campo usuariosolicitaIp de un objeto
     *
     * @return String usuariosolicitaIp usuariosolicitaIp
     *
     */
    public function getUsuariosolicitaIp()
    {
        return $this->usuariosolicitaIp;
    }

    /**
     * Modificar el campo 'usuariosolicitaIp' de un objeto
     *
     * @param String $usuariosolicitaIp usuariosolicitaIp
     *
     * @return no
     *
     */
    public function setUsuariosolicitaIp($usuariosolicitaIp)
    {
        $this->usuariosolicitaIp = $usuariosolicitaIp;
    }

    /**
     * Obtener el campo dispositivo de un objeto
     *
     * @return String dispositivo dispositivo
     *
     */
    public function getDispositivo()
    {
        return $this->dispositivo;
    }

    /**
     * Modificar el campo 'dispositivo' de un objeto
     *
     * @param String $dispositivo dispositivo
     *
     * @return no
     *
     */
    public function setDispositivo($dispositivo)
    {
        $this->dispositivo = $dispositivo;
    }

    /**
     * Obtener el campo soperativo de un objeto
     *
     * @return String soperativo soperativo
     *
     */
    public function getSoperativo()
    {
        return $this->soperativo;
    }

    /**
     * Modificar el campo 'soperativo' de un objeto
     *
     * @param String $soperativo soperativo
     *
     * @return no
     *
     */
    public function setSoperativo($soperativo)
    {
        $this->soperativo = $soperativo;
    }

    /**
     * Obtener el campo sversion de un objeto
     *
     * @return String sversion sversion
     *
     */
    public function getSversion()
    {
        return $this->sversion;
    }

    /**
     * Modificar el campo 'sversion' de un objeto
     *
     * @param String $sversion sversion
     *
     * @return no
     *
     */
    public function setSversion($sversion)
    {
        $this->sversion = $sversion;
    }



    /**
     * Obtener el campo imagen de un objeto
     *
     * @return String imagen sversion
     *
     */
    public function getImagen()
    {
        return $this->imagen;
    }



    /**
     * Modificar el campo 'imagen' de un objeto
     *
     * @param String $imagen sversion
     *
     * @return no
     *
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }


}

?>
