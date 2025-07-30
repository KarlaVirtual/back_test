<?php namespace Backend\mysql;
/** 
* Clase 'PromocionalMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Promocional'
* 
* Ejemplo de uso: 
* $PromocionalMySqlDAO = new PromocionalMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PromocionalMySqlDAO implements PromocionalDAO{

    /**
     * Obtener el registro condicionado por los campos
     * promocional_id y mandante pasados como parametro
     *
     * @param String $promocionalId promocionalId
     * @param String $mandante mandante
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($promocionalId, $mandante){
		$sql = 'SELECT * FROM promocional WHERE promocional_id = ?  AND mandante = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($promocionalId);
		$sqlQuery->setNumber($mandante);

		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM promocional';
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
		$sql = 'SELECT * FROM promocional ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados por los campos
     * promocional_id y mandante pasados como parametro
     *
     * @param String $promocionalId promocionalId
     * @param String $mandante mandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($promocionalId, $mandante){
		$sql = 'DELETE FROM promocional WHERE promocional_id = ?  AND mandante = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($promocionalId);
		$sqlQuery->setNumber($mandante);

		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object promocional promocional
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($promocional){
		$sql = 'INSERT INTO promocional (descripcion, estado, fecha_modif, usumodif_id, promocional_id, mandante) VALUES (?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($promocional->descripcion);
		$sqlQuery->set($promocional->estado);
		$sqlQuery->set($promocional->fechaModif);
		$sqlQuery->set($promocional->usumodifId);

		
		$sqlQuery->setNumber($promocional->promocionalId);

		$sqlQuery->setNumber($promocional->mandante);

		$this->executeInsert($sqlQuery);	
		//$promocional->id = $id;
		//return $id;
	}
	
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object promocional promocional
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($promocional){
		$sql = 'UPDATE promocional SET descripcion = ?, estado = ?, fecha_modif = ?, usumodif_id = ? WHERE promocional_id = ?  AND mandante = ? ';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($promocional->descripcion);
		$sqlQuery->set($promocional->estado);
		$sqlQuery->set($promocional->fechaModif);
		$sqlQuery->set($promocional->usumodifId);

		
		$sqlQuery->setNumber($promocional->promocionalId);

		$sqlQuery->setNumber($promocional->mandante);

		return $this->executeUpdate($sqlQuery);
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
		$sql = 'DELETE FROM promocional';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDescripcion($value){
		$sql = 'SELECT * FROM promocional WHERE descripcion = ?';
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
		$sql = 'SELECT * FROM promocional WHERE estado = ?';
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
		$sql = 'SELECT * FROM promocional WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
		$sql = 'SELECT * FROM promocional WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}






    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDescripcion($value){
		$sql = 'DELETE FROM promocional WHERE descripcion = ?';
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
		$sql = 'DELETE FROM promocional WHERE estado = ?';
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
		$sql = 'DELETE FROM promocional WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
		$sql = 'DELETE FROM promocional WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}





	
    /**
     * Crear y devolver un objeto del tipo Promocional
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Promocional Promocional
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$promocional = new Promocional();
		
		$promocional->promocionalId = $row['promocional_id'];
		$promocional->descripcion = $row['descripcion'];
		$promocional->estado = $row['estado'];
		$promocional->mandante = $row['mandante'];
		$promocional->fechaModif = $row['fecha_modif'];
		$promocional->usumodifId = $row['usumodif_id'];

		return $promocional;
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