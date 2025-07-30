<?php namespace Backend\dto;
use Backend\mysql\CupoLogMySqlDAO;
use Exception;

/**
* Clase 'Concepto'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Concepto'
* 
* Ejemplo de uso: 
* $Concepto = new Concepto();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CupoLog
{
		
    /**
    * Representación de la columna 'cupologId' de la tabla 'CupoLog'
    *
    * @var string
    */
	var $cupologId;
    
    /**
    * Representación de la columna 'usuarioId' de la tabla 'CupoLog'
    *
    * @var string
    */
	var $usuarioId;
    
    /**
    * Representación de la columna 'fechaCrea' de la tabla 'CupoLog'
    *
    * @var string
    */
	var $fechaCrea;
    
    /**
    * Representación de la columna 'tipoId' de la tabla 'CupoLog'
    *
    * @var string
    */
	var $tipoId;
    
    /**
    * Representación de la columna 'valor' de la tabla 'CupoLog'
    *
    * @var string
    */
	var $valor;
    
    /**
    * Representación de la columna 'usucreaId' de la tabla 'CupoLog'
    *
    * @var string
    */
	var $usucreaId;
    
    /**
    * Representación de la columna 'mandante' de la tabla 'CupoLog'
    *
    * @var string
    */
	var $mandante;
    
    /**
    * Representación de la columna 'tipocupoId' de la tabla 'CupoLog'
    *
    * @var string
    */
    var $tipocupoId;
    
    /**
    * Representación de la columna 'observacion' de la tabla 'CupoLog'
    *
    * @var string
    */
    var $observacion;

    /**
    * Representación de la columna 'recarga_id' de la tabla 'CupoLog'
    *
    * @var string
    */
    var $recargaId;


    /**
     * Representación de la columna 'numero_transaccion' de la tabla 'CupoLog'
     *
     * @var string
     */
    var $numeroTransaccion;

    /**
     * Representación de la columna 'nombre_banco2' de la tabla 'CupoLog'
     *
     * @var string
     */

    var $nombreBanco2;


    /**
    * Constructor de clase
    *
    *
    * @param String $cupologId cupologId
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($cupologId = '',$transactionId = '') {
        if(!empty($cupologId)) {
            $CupoLogMySqlDAO = new CupoLogMySqlDAO();
            $CupoLog = $CupoLogMySqlDAO->load($cupologId);

            if($CupoLog !== '') {
                $this->setCupoLogId($CupoLog->getCupologId());
                $this->setUsuarioId($CupoLog->getUsuarioId());
                $this->setFechaCrea($CupoLog->getFechaCrea());
                $this->setTipoId($CupoLog->getTipoId());
                $this->setValor($CupoLog->getValor());
                $this->setUsucreaId($CupoLog->getUsucreaId());
                $this->setMandante($CupoLog->getMandante());
                $this->setTipocupoId($CupoLog->getTipocupoId());
                $this->setObservacion($CupoLog->getObservacion());
                $this->setRecargaId($CupoLog->getRecargaId());
                $this->setNumeroTransaccion($CupoLog->getNumeroTransaccion());
                $this->setNombreBanco2($CupoLog->getNombreBanco2());
            } else {
                throw new Exception('No existe', '01');
            }
        }else if ($transactionId != ""){
            $CupoLogMySqlDAO = new CupoLogMySqlDAO();
            $CupoLog = $CupoLogMySqlDAO->queryByTransactionId($transactionId);
            $CupoLog = $CupoLog[0];




            if($CupoLog != ""){
                $this->setCupoLogId($CupoLog->getCupologId());
                $this->setUsuarioId($CupoLog->getUsuarioId());
                $this->setFechaCrea($CupoLog->getFechaCrea());
                $this->setTipoId($CupoLog->getTipoId());
                $this->setValor($CupoLog->getValor());
                $this->setUsucreaId($CupoLog->getUsucreaId());
                $this->setMandante($CupoLog->getMandante());
                $this->setTipocupoId($CupoLog->getTipocupoId());
                $this->setObservacion($CupoLog->getObservacion());
                $this->setRecargaId($CupoLog->getRecargaId());
                $this->setNumeroTransaccion($CupoLog->getNumeroTransaccion());
                $this->setNombreBanco2($CupoLog->getNombreBanco2());
            } else {
                throw new Exception('No existe', '01');
            }




        }
    }






    /**
     * Establece el ID de CupoLog.
     *
     * @param mixed $cupologId El ID de CupoLog a establecer.
     */
    public function setCupoLogId($cupologId) {
        $this->cupologId = $cupologId;
    }


    /**
     * Obtener el campo cupologId de un objeto
     *
     * @return String cupologId cupologId
     * 
     */
    public function getCupologId()
    {
        return $this->cupologId;
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
     * Obtener el campo tipoId de un objeto
     *
     * @return String tipoId tipoId
     * 
     */
    public function getTipoId()
    {
        return $this->tipoId;
    }

    /**
     * Modificar el campo 'tipoId' de un objeto
     *
     * @param String $tipoId tipoId
     *
     * @return no
     *
     */
    public function setTipoId($tipoId)
    {
        $this->tipoId = $tipoId;
    }

    /**
     * Obtener el campo valor de un objeto
     *
     * @return String valor valor
     * 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Modificar el campo 'valor' de un objeto
     *
     * @param String $valor valor
     *
     * @return no
     *
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
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
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     * 
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param String $mandante mandante
     *
     * @return no
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }



    /**
     * Obtener el campo tipocupoId de un objeto
     *
     * @return String tipocupoId tipocupoId
     * 
     */
    public function getTipocupoId()
    {
        return $this->tipocupoId;
    }

    /**
     * Modificar el campo 'tipocupoId' de un objeto
     *
     * @param String $tipocupoId tipocupoId
     *
     * @return no
     *
     */
    public function setTipocupoId($tipocupoId)
    {
        $this->tipocupoId = $tipocupoId;
    }

    /**
     * Obtener el campo observacion de un objeto
     *
     * @return String observacion observacion
     * 
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Modificar el campo 'observacion' de un objeto
     *
     * @param String $observacion observacion
     *
     * @return no
     *
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    }

    /**
     * Obtiene el ID de recarga.
     *
     * @return mixed El ID de recarga.
     */
    public function getRecargaId() {
        return $this->recargaId;
    }

    /**
     * Establece el número de transacción.
     *
     * @param mixed $value El número de transacción.
     */
    public function setNumeroTransaccion($value) {
        $this->numeroTransaccion = $value;
    }

    /**
     * Obtiene el número de transacción.
     *
     * @return mixed El número de transacción.
     */
    public function getNumeroTransaccion() {
        return $this->numeroTransaccion;
    }

    /**
     * Obtiene el nombre del banco 2.
     *
     * @return mixed El nombre del banco 2.
     */
    public function getNombreBanco2() {
        return $this->nombreBanco2;
    }

    /**
     * Establece el nombre del banco 2.
     *
     * @param mixed $nombreBanco2 El nombre del banco 2.
     */
    public function setNombreBanco2($nombreBanco2) {
        $this->nombreBanco2 = $nombreBanco2;
    }

    /**
     * Establece el ID de recarga.
     *
     * @param mixed $recargaId El ID de recarga.
     */
    public function setRecargaId($recargaId) {
        $this->recargaId = $recargaId;
    }


    /**
    * Realizar una consulta en la tabla de CupoLogs 'CupoLog'
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
    * @throws Exception si los usuarios no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
        public function getCupoLogsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
        {

            $CupoLogMySqlDAO = new CupoLogMySqlDAO();

            $usuarios = $CupoLogMySqlDAO->queryCupoLogsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

            if ($usuarios != null && $usuarios != "") 
            {
                return $usuarios;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "01");
            }


        }
		
	}
?>
