<?php 
namespace Backend\dto;

use Backend\imports\MobileDetect\MobileDetect;
use Backend\mysql\GeneralLogMySqlDAO;
use Exception;

/**
 * Clase 'GeneralLog'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'GeneralLog'
 *
 * Ejemplo de uso:
 * $GeneralLog = new GeneralLog();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class GeneralLog
{

    /**
     * Representación de la columna 'generallogId' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $generallogId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'usuarioaprobarId' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $usuarioaprobarId;

    /**
     * Representación de la columna 'usuarioIp' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $usuarioIp;

    /**
     * Representación de la columna 'usuarioaprobarIp' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $usuarioaprobarIp;

    /**
     * Representación de la columna 'usuariosolicitaId' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $usuariosolicitaId;

    /**
     * Representación de la columna 'usuariosolicitaIp' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $usuariosolicitaIp;

    /**
     * Representación de la columna 'tipo' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'estado' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'valorAntes' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $valorAntes;

    /**
     * Representación de la columna 'valorDespues' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $valorDespues;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'dispositivo' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $dispositivo;

    /**
     * Representación de la columna 'soperativo' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $soperativo;

    /**
     * Representación de la columna 'sversion' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $sversion;

    /**
     * Representación de la columna 'imagen' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $imagen;

    /**
     * Representación de la columna 'externo_id' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $externoId;

    /**
     * Representación de la columna 'tabla' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $tabla;

    /**
     * Representación de la columna 'campo' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $campo;


    /**
     * Representación de la columna 'campo' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $explicacion;


    /**
     * Representación de la columna 'campo' de la tabla 'GeneralLog'
     *
     * @var string
     */
    var $mandante;


    /**
     * Constructor de clase
     *
     *
     * @param String $generallogId generallogId
     *
     * @return no
     * @throws Exception si GeneralLog no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($generallogId = "")
    {

        if ($generallogId != "") {

            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO();

            $GeneralLog = $GeneralLogMySqlDAO->load($generallogId);

            if ($GeneralLog != null && $GeneralLog != "") {
                $this->generallogId = $GeneralLog->generallogId;
                $this->usuarioId = $GeneralLog->usuarioId;
                $this->usuarioaprobarId = $GeneralLog->usuarioaprobarId;
                $this->usuariosolicitaIp = $GeneralLog->usuariosolicitaIp;
                $this->usuariosolicitaId = $GeneralLog->usuariosolicitaId;

                $this->usuarioIp = $GeneralLog->usuarioIp;
                $this->usuarioaprobarIp = $GeneralLog->usuarioaprobarIp;
                $this->tipo = $GeneralLog->tipo;
                $this->valor = $GeneralLog->valor;
                $this->valorAntes = $GeneralLog->valorAntes;
                $this->valorDespues = $GeneralLog->valorDespues;
                $this->usucreaId = $GeneralLog->usucreaId;
                $this->usumodifId = $GeneralLog->usumodifId;
                $this->dispositivo = $GeneralLog->dispositivo;
                $this->soperativo = $GeneralLog->soperativo;
                $this->sversion = $GeneralLog->sversion;
                $this->imagen = $GeneralLog->imagen;
                $this->estado = $GeneralLog->estado;
                $this->externoId = $GeneralLog->externoId;
                $this->campo = $GeneralLog->campo;
                $this->tabla = $GeneralLog->tabla;
                $this->explicacion = $GeneralLog->explicacion;
                $this->mandante = $GeneralLog->mandante;
            } else {
                throw new Exception("No existe " . get_class($this), "101   ");
            }
        }


    }


    /**
     * Realizar una consulta en la tabla de GeneralLog 'GeneralLog'
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
    public function getGeneralLogsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO();

        $Usuario = $GeneralLogMySqlDAO->queryGeneralLogsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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

        if($this->getDispositivo() ==""){
            $this->setDispositivo($dispositivo);
        }

        if($this->getSoperativo() ==""){
            $this->setSoperativo($soperativo);
        }

        if($this->getSversion() ==""){
            $this->setSversion($sversion);
        }

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

    /**
     * Obtener el campo externoId del GeneralLog
     * @return string
     */
    public function getExternoId()
    {
        return $this->externoId;
    }

    /**
     * Modificar el campo 'externoId' del GeneralLog
     * @param string $externoId
     */
    public function setExternoId($externoId)
    {
        $this->externoId = $externoId;
    }

    /**
     * Obtener el campo generallogId del GeneralLog
     * @return string
     */
    public function getGenerallogId()
    {
        return $this->generallogId;
    }

    /**
     * Obtener el campo tabla del GeneralLog
     * @return string
     */
    public function getTabla()
    {
        return $this->tabla;
    }

    /**
     * Modificar el campo 'tabla' del GeneralLog
     * @param string $tabla
     */
    public function setTabla($tabla)
    {
        $this->tabla = $tabla;
    }

    /**
     * Obtener el campo campo del GeneralLog
     * @return string
     */
    public function getCampo()
    {
        return $this->campo;
    }

    /**
     * Modificar el campo 'campo' del GeneralLog
     * @param string $campo
     */
    public function setCampo($campo)
    {
        $this->campo = $campo;
    }

    /**
     * Obtener el campo explicacion del GeneralLog
     * @return string
     */
    public function getExplicacion()
    {
        return $this->explicacion;
    }

    /**
     * Modificar el campo 'explicacion' del GeneralLog
     * @param string $explicacion
     */
    public function setExplicacion($explicacion)
    {
        $this->explicacion = $explicacion;
    }

    /**
     * Obtener el campo mandante del GeneralLog
     * @return string
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' del GeneralLog
     * @param string $mandante
     * 
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }


}

?>