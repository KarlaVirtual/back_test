<?php namespace Backend\mysql;
/** 
* Clase 'RangoIngresoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'RangoIngreso'
* 
* Ejemplo de uso: 
* $RangoIngresoMySqlDAO = new RangoIngresoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class RangoIngresoMySqlDAO implements RangoIngresoDAO
{

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
		$sql = 'SELECT * FROM rango_ingreso WHERE rangoingreso_id = ?';
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
		$sql = 'SELECT * FROM rango_ingreso';
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
		$sql = 'SELECT * FROM rango_ingreso ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
    
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $rangoingreso_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($rangoingreso_id){
		$sql = 'DELETE FROM rango_ingreso WHERE rangoingreso_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($rangoingreso_id);
		return $this->executeUpdate($sqlQuery);
	}
    
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object rangoIngreso rangoIngreso
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($rangoIngreso){
		$sql = 'INSERT INTO rango_ingreso (descripcion, estado, orden) VALUES (?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($rangoIngreso->descripcion);
		$sqlQuery->set($rangoIngreso->estado);
		$sqlQuery->set($rangoIngreso->orden);

		$id = $this->executeInsert($sqlQuery);	
		$rangoIngreso->rangoingresoId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object rangoIngreso rangoIngreso
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($rangoIngreso){
		$sql = 'UPDATE rango_ingreso SET descripcion = ?, estado = ?, orden = ? WHERE rangoingreso_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($rangoIngreso->descripcion);
		$sqlQuery->set($rangoIngreso->estado);
		$sqlQuery->set($rangoIngreso->orden);

		$sqlQuery->set($rangoIngreso->rangoingresoId);
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
		$sql = 'DELETE FROM rango_ingreso';
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
		$sql = 'SELECT * FROM rango_ingreso WHERE descripcion = ?';
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
		$sql = 'SELECT * FROM rango_ingreso WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna orden sea igual al valor pasado como parámetro
     *
     * @param String $value orden requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByOrden($value){
		$sql = 'SELECT * FROM rango_ingreso WHERE orden = ?';
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
		$sql = 'DELETE FROM rango_ingreso WHERE descripcion = ?';
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
		$sql = 'DELETE FROM rango_ingreso WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna orden sea igual al valor pasado como parámetro
     *
     * @param String $value orden requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByOrden($value){
		$sql = 'DELETE FROM rango_ingreso WHERE orden = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	



    /**
     * Crear y devolver un objeto del tipo RangoIngreso
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $rangoIngreso RangoIngreso
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$rangoIngreso = new RangoIngreso();
		
		$rangoIngreso->rangoingresoId = $row['rangoingreso_id'];
		$rangoIngreso->descripcion = $row['descripcion'];
		$rangoIngreso->estado = $row['estado'];
		$rangoIngreso->orden = $row['orden'];

		return $rangoIngreso;
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