<?php namespace Backend\dto;
use Backend\mysql\BancoDetalleMySqlDAO;
use Backend\mysql\BancoMySqlDAO;
use Backend\sql\SqlQuery;
use Exception;
/** 
* Clase 'Banco'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Banco'
* 
* Ejemplo de uso: 
* $Banco = new Banco();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class BancoDetalle
{
		
    /**
    * Representación de la columna 'bancoId' de la tabla 'Banco'
    *
    * @var string
    */
    var $bancodetalleId;
	var $bancoId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Banco'
    *
    * @var string
    */   
	var $productoId;

    /**
    * Representación de la columna 'paisId' de la tabla 'Banco'
    *
    * @var string
    */   
    var $paisId;

    /**
    * Representación de la columna 'estado' de la tabla 'Banco'
    *
    * @var string
    */
    var $mandante;

    var $estado;
    var $fechaCrea;
    var $fechaModif;
    var $usucreaId;
    var $usumodifId;

    /**
    * Representación de la columna 'productoPago' de la tabla 'Banco'
    *
    * @var string
    */   

    /**
    * Constructor de clase
    *
    *
    * @param String $banco_id id del banco
    *
    * @throws Exception si el banco no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($bancodetalleId="")
    {

        if($bancodetalleId != "")
        {

            $BancoDetalleMySqlDAO = new BancoDetalleMySqlDAO();

            $BancoDetalle = $BancoDetalleMySqlDAO->load($bancodetalleId);

            $this->success = false;

            if ($BancoDetalle != null && $BancoDetalle != "")
            {
                $this->bancodetalleId = $BancoDetalle->bancodetalleId;
                $this->bancoId = $BancoDetalle->bancoId;
                $this->productoId = $BancoDetalle->productoId;
                $this->paisId = $BancoDetalle->paisId;
                $this->mandante = $BancoDetalle->mandante;
                $this->estado = $BancoDetalle->estado;
                $this->fechaCrea = $BancoDetalle->fechaCrea;
                $this->fechaModif = $BancoDetalle->fechaModif;
                $this->usucreaId = $BancoDetalle->usucreaId;
                $this->usumodifId = $BancoDetalle->usumodifId;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "35");
            }

        }

    }

    /**
     * @return string
     */
    public function getBancodetalleId()
    {
        return $this->bancodetalleId;
    }

    /**
     * @param string $bancodetalleId
     */
    public function setBancodetalleId($bancodetalleId)
    {
        $this->bancodetalleId = $bancodetalleId;
    }

    /**
     * @return string
     */
    public function getBancoId()
    {
        return $this->bancoId;
    }

    /**
     * @param string $bancoId
     */
    public function setBancoId($bancoId)
    {
        $this->bancoId = $bancoId;
    }

    /**
     * @return string
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * @param string $productoId
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
    }

    /**
     * @return string
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * @param string $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * @return string
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * @param string $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return false
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param false $success
     */
    public function setSuccess(bool $success)
    {
        $this->success = $success;
    }

    /**
     * @return mixed
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * @param mixed $fechaCrea
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * @return mixed
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * @param mixed $fechaModif
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * @return mixed
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * @param mixed $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * @return mixed
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * @param mixed $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }


    /**
    * Realizar una consulta en la tabla de banco 'Banco'
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
    * @throws Exception si el bono no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function queryBancodetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $BancoDetalleMySqlDAO= new BancoDetalleMySqlDAO();

        $bancosDetalles = $BancoDetalleMySqlDAO->queryBancodetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($bancosDetalles != null && $bancosDetalles != "")
        {
            return $bancosDetalles;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "35");
        }


    }

    /**
     * Realizar una consulta en la tabla de banco 'Banco'
     * de una manera personalizada con consultas en la tabla 'banco_mandante' o 'banco'
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $mandante para generar consulta por mandante
     * @param String $paisId para generar consulta por pais
     *
     * @return Array resultado de la consulta
     * @throws Exception si el bono no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getBancosCustomMandante($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandante,$paisId='')
    {
        $BancoDetalleMySqlDAO = new BancoDetalleMySqlDAO();

        $Bancos = $BancoDetalleMySqlDAO->queryBancosCustomMandante($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandante,$paisId);

        if ($Bancos != null && $Bancos != "")
        {
            return $Bancos;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * Realizar una consulta en la tabla de banco 'Banco_detalle'
     * de una manera personalizada con consultas en la tabla 'banco_mandante', 'producto'
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $mandante para generar consulta por mandante
     * @param String $paisId para generar consulta por pais
     *
     * @return Array resultado de la consulta
     * @throws Exception si el bono no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getBancosMandanteProductosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandante,$paisId='')
    {
        $BancoDetalleMySqlDAO = new BancoDetalleMySqlDAO();

        $Bancos = $BancoDetalleMySqlDAO->queryBancosMandanteProductosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandante,$paisId);

        if ($Bancos != null && $Bancos != "")
        {
            return $Bancos;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * Ejecutar una consulta sql
     *
     *
     *
     * @param Objeto $transaccion transacción
     * @param String $sql consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function execQuery($transaccion, $sql)
    {

        $BancoDetalleMySqlDAO = new BancoDetalleMySqlDAO($transaccion);
        $return = $BancoDetalleMySqlDAO->querySQL($sql);
        $return = json_decode(json_encode($return), FALSE);

        return $return;

    }

}
?>