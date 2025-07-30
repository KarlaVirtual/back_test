<?php namespace Backend\dto;

use Backend\imports\MobileDetect\MobileDetect;
use Backend\mysql\UsuarioLogMySqlDAO;
use Exception;

/**
 * Clase 'UsuarioLog'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'UsuarioLog'
 *
 * Ejemplo de uso:
 * $UsuarioLog = new UsuarioLog();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioLog
{

    /**
     * Representación de la columna 'usuariologId' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $usuariologId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'usuarioaprobarId' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $usuarioaprobarId;

    /**
     * Representación de la columna 'usuarioIp' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $usuarioIp;

    /**
     * Representación de la columna 'usuarioaprobarIp' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $usuarioaprobarIp;

    /**
     * Representación de la columna 'usuariosolicitaId' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $usuariosolicitaId;

    /**
     * Representación de la columna 'usuariosolicitaIp' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $usuariosolicitaIp;

    /**
     * Representación de la columna 'tipo' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'estado' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'valorAntes' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $valorAntes;

    /**
     * Representación de la columna 'valorDespues' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $valorDespues;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'dispositivo' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $dispositivo;

    /**
     * Representación de la columna 'soperativo' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $soperativo;

    /**
     * Representación de la columna 'sversion' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $sversion;

    /**
     * Representación de la columna 'imagen' de la tabla 'UsuarioLog'
     *
     * @var string
     */
    var $imagen;


    /**
     * Constructor de clase
     *
     *
     * @param String $usuariologId usuariologId
     *
     * @return no
     * @throws Exception si UsuarioLog no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($usuariologId = "")
    {

        if ($usuariologId != "") {

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            $UsuarioLog = $UsuarioLogMySqlDAO->load($usuariologId);

            if ($UsuarioLog != null && $UsuarioLog != "") {
                $this->usuariologId = $UsuarioLog->usuariologId;
                $this->usuarioId = $UsuarioLog->usuarioId;
                $this->usuarioaprobarId = $UsuarioLog->usuarioaprobarId;
                $this->usuariosolicitaIp = $UsuarioLog->usuariosolicitaIp;
                $this->usuariosolicitaId = $UsuarioLog->usuariosolicitaId;

                $this->usuarioIp = $UsuarioLog->usuarioIp;
                $this->usuarioaprobarIp = $UsuarioLog->usuarioaprobarIp;
                $this->tipo = $UsuarioLog->tipo;
                $this->valor = $UsuarioLog->valor;
                $this->valorAntes = $UsuarioLog->valorAntes;
                $this->valorDespues = $UsuarioLog->valorDespues;
                $this->usucreaId = $UsuarioLog->usucreaId;
                $this->fechaCrea = $UsuarioLog->fechaCrea;
                $this->usumodifId = $UsuarioLog->usumodifId;
                $this->dispositivo = $UsuarioLog->dispositivo;
                $this->soperativo = $UsuarioLog->soperativo;
                $this->sversion = $UsuarioLog->sversion;
                $this->imagen = $UsuarioLog->imagen;
                $this->estado = $UsuarioLog->estado;
            } else {
                throw new Exception("No existe " . get_class($this), "100");
            }
        }


    }


    /**
     * Realizar una consulta en la tabla de UsuarioLog 'UsuarioLog'
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
    public function getUsuarioLogsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

        $Usuario = $UsuarioLogMySqlDAO->queryUsuarioLogsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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