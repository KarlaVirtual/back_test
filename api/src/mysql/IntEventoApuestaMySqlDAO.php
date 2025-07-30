<?php namespace Backend\mysql;
use Backend\dao\IntEventoApuestaDAO;
use Backend\dto\IntEventoApuesta;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'IntEventoApuestaMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'IntEventoApuesta'
* 
* Ejemplo de uso: 
* $IntEventoApuestaMySqlDAO = new IntEventoApuestaMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class IntEventoApuestaMySqlDAO implements IntEventoApuestaDAO{

    /**
    * Atributo Transaction transacción
    *
    * @var object
    */
    private $transaction;

    /**
     * Obtener la transacción de un objeto
     *
     * @return Objeto Transaction transacción
     *
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Modificar el atributo transacción del objeto
     *
     * @param Objeto $Transaction transacción
     *
     * @return no
     *
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
    * Constructor de clase
    *
    *
    * @param Objeto $transaction transaccion
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
	public function __construct($transaction="")
	{
		if ($transaction == "") 
		{
			$transaction = new Transaction();
			$this->transaction = $transaction;
		}
		else 
		{
			$this->transaction = $transaction;
		}
	}





    /**
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $apuesta_id llave primaria
     *
     * @return Array resultado de la consulta
     *  
     */
	public function load($id){
		$sql = 'SELECT * FROM int_evento_apuesta WHERE eventoapuesta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM int_evento_apuesta';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Obtener todos los registros
     * ordenadas por el nombre de la columna 
     * que se pasa como parámetro
     *
     * @param String $orderColumn nombre de la columna
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM int_evento_apuesta ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $eventoapuesta_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($eventoapuesta_id){
		$sql = 'DELETE FROM int_evento_apuesta WHERE eventoapuesta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($eventoapuesta_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object intEventoApuesta intEventoApuesta
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($intEventoApuesta){
		$sql = 'INSERT INTO int_evento_apuesta (evento_id, apuesta_id, nombre, estado, estado_apuesta, usucrea_id, usumodif_id) VALUES (?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($intEventoApuesta->eventoId);
		$sqlQuery->setNumber($intEventoApuesta->apuestaId);
		$sqlQuery->set($intEventoApuesta->nombre);
		$sqlQuery->set($intEventoApuesta->estado);
		$sqlQuery->set($intEventoApuesta->estadoApuesta);
		$sqlQuery->setNumber($intEventoApuesta->usucreaId);
		$sqlQuery->setNumber($intEventoApuesta->usumodifId);

		$id = $this->executeInsert($sqlQuery);	
		$intEventoApuesta->eventoapuestaId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object intEventoApuesta intEventoApuesta
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($intEventoApuesta){
		$sql = 'UPDATE int_evento_apuesta SET evento_id = ?, apuesta_id = ?, nombre = ?, estado = ?, estado_apuesta = ?, usucrea_id = ?, usumodif_id = ? WHERE eventoapuesta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($intEventoApuesta->eventoId);
		$sqlQuery->setNumber($intEventoApuesta->apuestaId);
		$sqlQuery->set($intEventoApuesta->nombre);
		$sqlQuery->set($intEventoApuesta->estado);
		$sqlQuery->set($intEventoApuesta->estadoApuesta);
		$sqlQuery->setNumber($intEventoApuesta->usucreaId);
		$sqlQuery->setNumber($intEventoApuesta->usumodifId);

		$sqlQuery->setNumber($intEventoApuesta->eventoapuestaId);
		return $this->executeUpdate($sqlQuery);
	}




    /**
    * Realizar una consulta en la tabla de intEventoApuesta 'intEventoApuesta'
    * de una manera personalizada
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    * @param String $grouping columna para agrupar
    *
    * @return Array resultado de la consulta
    *
    */
    public function queryEventoApuestasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {


        $where = " where 1=1 ";


        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $rule->field;
                $fieldData = $rule->data;
                switch ($rule->op) {
                    case "eq":
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                    case "ne":
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                    case "lt":
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                    case "gt":
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                    case "le":
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '" . $fieldData . "'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $sql = 'SELECT count(*) count FROM  int_evento_apuesta INNER JOIN int_apuesta ON (int_apuesta.apuesta_id=int_evento_apuesta.apuesta_id) INNER JOIN int_evento ON (int_evento.evento_id=int_evento_apuesta.evento_id)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM  int_evento_apuesta INNER JOIN int_apuesta ON (int_apuesta.apuesta_id=int_evento_apuesta.apuesta_id) INNER JOIN int_evento ON (int_evento.evento_id=int_evento_apuesta.evento_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }




    /**
     * Eliminar todas los registros de la base de datos
     *
     * @param no
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function clean(){
		$sql = 'DELETE FROM int_evento_apuesta';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}




    /**
     * Obtener todos los registros donde se encuentre que
     * la columna evento_id sea igual al valor pasado como parámetro
     *
     * @param String $value evento_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByEventoId($value){
		$sql = 'SELECT * FROM int_evento_apuesta WHERE evento_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna apuesta_id sea igual al valor pasado como parámetro
     *
     * @param String $value apuesta_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByApuestaId($value){
		$sql = 'SELECT * FROM int_evento_apuesta WHERE apuesta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByNombre($value){
		$sql = 'SELECT * FROM int_evento_apuesta WHERE nombre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM int_evento_apuesta WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna estado_apuesta sea igual al valor pasado como parámetro
     *
     * @param String $value estado_apuesta requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByEstadoApuesta($value){
		$sql = 'SELECT * FROM int_evento_apuesta WHERE estado_apuesta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param String $value usucrea_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM int_evento_apuesta WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM int_evento_apuesta WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM int_evento_apuesta WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM int_evento_apuesta WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna evento_id sea igual al valor pasado como parámetro
     *
     * @param String $value evento_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByEventoId($value){
		$sql = 'DELETE FROM int_evento_apuesta WHERE evento_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna apuesta_id sea igual al valor pasado como parámetro
     *
     * @param String $value apuesta_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByApuestaId($value){
		$sql = 'DELETE FROM int_evento_apuesta WHERE apuesta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByNombre($value){
		$sql = 'DELETE FROM int_evento_apuesta WHERE nombre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByEstado($value){
		$sql = 'DELETE FROM int_evento_apuesta WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna estado_apuesta sea igual al valor pasado como parámetro
     *
     * @param String $value estado_apuesta requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByEstadoApuesta($value){
		$sql = 'DELETE FROM int_evento_apuesta WHERE estado_apuesta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param String $value usucrea_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsucreaId($value){
		$sql = 'DELETE FROM int_evento_apuesta WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM int_evento_apuesta WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM int_evento_apuesta WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM int_evento_apuesta WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	
    /**
     * Crear y devolver un objeto del tipo IntEventoApuesta
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $IntEventoApuesta IntEventoApuesta
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$intEventoApuesta = new IntEventoApuesta();
		
		$intEventoApuesta->eventoapuestaId = $row['eventoapuesta_id'];
		$intEventoApuesta->eventoId = $row['evento_id'];
		$intEventoApuesta->apuestaId = $row['apuesta_id'];
		$intEventoApuesta->nombre = $row['nombre'];
		$intEventoApuesta->estado = $row['estado'];
		$intEventoApuesta->estadoApuesta = $row['estado_apuesta'];
		$intEventoApuesta->usucreaId = $row['usucrea_id'];
		$intEventoApuesta->usumodifId = $row['usumodif_id'];
		$intEventoApuesta->fechaCrea = $row['fecha_crea'];
		$intEventoApuesta->fechaModif = $row['fecha_modif'];

		return $intEventoApuesta;
	}
	
    /**
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo 
     * 
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ret arreglo indexado
     *
     * @access protected
     *
     */
	protected function getList($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		$ret = array();
		for($i=0;$i<oldCount($tab);$i++){
			$ret[$i] = $this->readRow($tab[$i]);
		}
		return $ret;
	}
    
    /**
     * Ejecutar una consulta sql y devolver el resultado como un arreglo
     * 
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
	protected function getRow($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		if(oldCount($tab)==0){
			return null;
		}
		return $this->readRow($tab[0]);		
	}

    /**
     * Ejecutar una consulta sql
     * 
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function execute($sqlQuery){
        return QueryExecutor::execute($this->transaction,$sqlQuery);
    }


     /**
     * Ejecutar una consulta sql
     * 
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como update
     * 
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function executeUpdate($sqlQuery){
        return QueryExecutor::executeUpdate($this->transaction,$sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como select
     * 
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function querySingleResult($sqlQuery){
        return QueryExecutor::queryForString($this->transaction,$sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como insert
     * 
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function executeInsert($sqlQuery){
        return QueryExecutor::executeInsert($this->transaction,$sqlQuery);
    }
}
?>