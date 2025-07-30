<?php namespace Backend\mysql;
/** 
* Clase 'LenguajePalabraMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'LenguajePalabra'
* 
* Ejemplo de uso: 
* $LenguajePalabraMySqlDAO = new LenguajePalabraMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PeriodicidadLiquidaMySqlDAO implements PeriodicidadLiquidaDAO{

    /**
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM periodicidad_liquida WHERE periodicidad_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM periodicidad_liquida';
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
		$sql = 'SELECT * FROM periodicidad_liquida ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	  
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $periodicidad_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($periodicidad_id){
		$sql = 'DELETE FROM periodicidad_liquida WHERE periodicidad_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($periodicidad_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object periodicidadLiquida periodicidadLiquida
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($periodicidadLiquida){
		$sql = 'INSERT INTO periodicidad_liquida (descripcion, dias, estado) VALUES (?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($periodicidadLiquida->descripcion);
		$sqlQuery->set($periodicidadLiquida->dias);
		$sqlQuery->set($periodicidadLiquida->estado);

		$id = $this->executeInsert($sqlQuery);	
		$periodicidadLiquida->periodicidadId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object periodicidadLiquida periodicidadLiquida
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($periodicidadLiquida){
		$sql = 'UPDATE periodicidad_liquida SET descripcion = ?, dias = ?, estado = ? WHERE periodicidad_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($periodicidadLiquida->descripcion);
		$sqlQuery->set($periodicidadLiquida->dias);
		$sqlQuery->set($periodicidadLiquida->estado);

		$sqlQuery->set($periodicidadLiquida->periodicidadId);
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
		$sql = 'DELETE FROM periodicidad_liquida';
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
		$sql = 'SELECT * FROM periodicidad_liquida WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna dias sea igual al valor pasado como parámetro
     *
     * @param String $value dias requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDias($value){
		$sql = 'SELECT * FROM periodicidad_liquida WHERE dias = ?';
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
		$sql = 'SELECT * FROM periodicidad_liquida WHERE estado = ?';
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
		$sql = 'DELETE FROM periodicidad_liquida WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna dias sea igual al valor pasado como parámetro
     *
     * @param String $value dias requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDias($value){
		$sql = 'DELETE FROM periodicidad_liquida WHERE dias = ?';
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
		$sql = 'DELETE FROM periodicidad_liquida WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}




	
    /**
     * Crear y devolver un objeto del tipo PeriodicidadLiquida
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $PeriodicidadLiquida PeriodicidadLiquida
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$periodicidadLiquida = new PeriodicidadLiquida();
		
		$periodicidadLiquida->periodicidadId = $row['periodicidad_id'];
		$periodicidadLiquida->descripcion = $row['descripcion'];
		$periodicidadLiquida->dias = $row['dias'];
		$periodicidadLiquida->estado = $row['estado'];

		return $periodicidadLiquida;
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