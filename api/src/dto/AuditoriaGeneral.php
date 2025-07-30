<?php namespace Backend\dto;

use Backend\imports\MobileDetect\MobileDetect;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\sql\Transaction;
use Exception;

/**
 * Clase 'AuditoriaGeneral'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'AuditoriaGeneral'
 *
 * Ejemplo de uso:
 * $AuditoriaGeneral = new AuditoriaGeneral();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class AuditoriaGeneral
{

    /**
     * Representación de la columna 'AuditoriaGeneralId' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $AuditoriaGeneralId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'usuarioaprobarId' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $usuarioaprobarId;

    /**
     * Representación de la columna 'usuarioIp' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $usuarioIp;

    /**
     * Representación de la columna 'usuarioaprobarIp' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $usuarioaprobarIp;

    /**
     * Representación de la columna 'usuariosolicitaId' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $usuariosolicitaId;

    /**
     * Representación de la columna 'usuariosolicitaIp' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $usuariosolicitaIp;

    /**
     * Representación de la columna 'tipo' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'estado' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'valorAntes' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $valorAntes;

    /**
     * Representación de la columna 'valorDespues' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $valorDespues;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'dispositivo' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $dispositivo;

    /**
     * Representación de la columna 'soperativo' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $soperativo;

    /**
     * Representación de la columna 'sversion' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $sversion;

    /**
     * Representación de la columna 'imagen' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $imagen;
    /**
     * Representación de la columna 'observacion' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $observacion;

    /**
     * Representación de la columna 'data' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $data;

    /**
     * Representación de la columna 'campo' de la tabla 'AuditoriaGeneral'
     *
     * @var string
     */
    var $campo;


    /**
     * Constructor de clase
     *
     *
     * @param String $AuditoriaGeneralId AuditoriaGeneralId
     *
     * @return no
     * @throws Exception si AuditoriaGeneral no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($AuditoriaGeneralId = "")
    {

        if ($AuditoriaGeneralId != "") {

            $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();

            $AuditoriaGeneral = $AuditoriaGeneralMySqlDAO->load($AuditoriaGeneralId);

            if ($AuditoriaGeneral != null && $AuditoriaGeneral != "") {
                $this->AuditoriaGeneralId = $AuditoriaGeneral->AuditoriaGeneralId;
                $this->usuarioId = $AuditoriaGeneral->usuarioId;
                $this->usuarioaprobarId = $AuditoriaGeneral->usuarioaprobarId;
                $this->usuariosolicitaIp = $AuditoriaGeneral->usuariosolicitaIp;
                $this->usuariosolicitaId = $AuditoriaGeneral->usuariosolicitaId;

                $this->usuarioIp = $AuditoriaGeneral->usuarioIp;
                $this->usuarioaprobarIp = $AuditoriaGeneral->usuarioaprobarIp;
                $this->tipo = $AuditoriaGeneral->tipo;
                $this->valor = $AuditoriaGeneral->valor;
                $this->valorAntes = $AuditoriaGeneral->valorAntes;
                $this->valorDespues = $AuditoriaGeneral->valorDespues;
                $this->usucreaId = $AuditoriaGeneral->usucreaId;
                $this->fechaCrea = $AuditoriaGeneral->fechaCrea;
                $this->usumodifId = $AuditoriaGeneral->usumodifId;
                $this->dispositivo = $AuditoriaGeneral->dispositivo;
                $this->soperativo = $AuditoriaGeneral->soperativo;
                $this->sversion = $AuditoriaGeneral->sversion;
                $this->imagen = $AuditoriaGeneral->imagen;
                $this->estado = $AuditoriaGeneral->estado;
                $this->observacion = $AuditoriaGeneral->observacion;
                $this->data = $AuditoriaGeneral->data;
                $this->campo = $AuditoriaGeneral->campo;
            } else {
                throw new Exception("No existe " . get_class($this), "100");
            }
        }


    }


    /**
     * Realizar una consulta en la tabla de AuditoriaGeneral 'AuditoriaGeneral'
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
    public function getAuditoriaGeneralCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();

        $Usuario = $AuditoriaGeneralMySqlDAO->queryAuditoriaGeneralCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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
     * Establece el valor del campo.
     *
     * @param mixed $campo El valor a establecer en el campo.
     * @return void
     */
    public function setCampo($campo) {
        $this->campo = $campo;
    }

    /**
     * Obtener el campo 'campo' de un objeto
     *
     * @return mixed El valor de 'campo'
     */
    public function getCampo() {
        return $this->campo;
    }

    /**
     * Modificar el campo 'data' de un objeto
     *
     * @param mixed $data El valor a establecer en 'data'
     * @return void
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * Obtener el campo 'data' de un objeto
     *
     * @return mixed El valor de 'data'
     */
    public function getData() {
        return $this->data;
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
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * @param string $observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    }

    public function createLogProgReferidos(Transaction $Transaction, Usuario $Usuario, $valorAntes, $valorDespues, $clasificadorId) {
        $AuditoriaGeneralMySqlDAO  = new AuditoriaGeneralMySqlDAO($Transaction);
        $Clasificador = new Clasificador($clasificadorId);

        $this->usuarioId = $Usuario->getUsuarioId();
        $this->usuariosolicitaId = $Usuario->getUsuarioId();
        $this->tipo = 'REFERIDOS';
        $this->valorAntes = $valorAntes;
        $this->valorDespues = $valorDespues;
        $this->estado = 'A';
        $this->data = $Clasificador->getAbreviado();

        return $AuditoriaGeneralMySqlDAO->insert($this);
    }


}

?>
