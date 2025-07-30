<?php namespace Backend\mysql;
/** 
* Clase 'DepartamentoCopyMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'DepartamentoCopy'
* 
* Ejemplo de uso: 
* $DepartamentoCopyMySqlDAO = new DepartamentoCopyMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class DepartamentoCopyMySqlDAO implements DepartamentoCopyDAO{

    /**
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $cupolog_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM departamento_copy WHERE depto_id = ?';
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
		$sql = 'SELECT * FROM departamento_copy';
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
		$sql = 'SELECT * FROM departamento_copy ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $depto_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($depto_id){
		$sql = 'DELETE FROM departamento_copy WHERE depto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($depto_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object departamentoCopy departamentoCopy
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($departamentoCopy){
		$sql = 'INSERT INTO departamento_copy (depto_cod, depto_nom, pais_id) VALUES (?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($departamentoCopy->deptoCod);
		$sqlQuery->set($departamentoCopy->deptoNom);
		$sqlQuery->set($departamentoCopy->paisId);

		$id = $this->executeInsert($sqlQuery);	
		$departamentoCopy->deptoId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object departamentoCopy departamentoCopy
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($departamentoCopy){
		$sql = 'UPDATE departamento_copy SET depto_cod = ?, depto_nom = ?, pais_id = ? WHERE depto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($departamentoCopy->deptoCod);
		$sqlQuery->set($departamentoCopy->deptoNom);
		$sqlQuery->set($departamentoCopy->paisId);

		$sqlQuery->set($departamentoCopy->deptoId);
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
		$sql = 'DELETE FROM departamento_copy';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna depto_cod sea igual al valor pasado como parámetro
     *
     * @param String $value depto_cod requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDeptoCod($value){
		$sql = 'SELECT * FROM departamento_copy WHERE depto_cod = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna depto_nom sea igual al valor pasado como parámetro
     *
     * @param String $value depto_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDeptoNom($value){
		$sql = 'SELECT * FROM departamento_copy WHERE depto_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna pais_id sea igual al valor pasado como parámetro
     *
     * @param String $value pais_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPaisId($value){
		$sql = 'SELECT * FROM departamento_copy WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}






    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna depto_cod sea igual al valor pasado como parámetro
     *
     * @param String $value depto_cod requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDeptoCod($value){
		$sql = 'DELETE FROM departamento_copy WHERE depto_cod = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna depto_nom sea igual al valor pasado como parámetro
     *
     * @param String $value depto_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDeptoNom($value){
		$sql = 'DELETE FROM departamento_copy WHERE depto_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna pais_id sea igual al valor pasado como parámetro
     *
     * @param String $value pais_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPaisId($value){
		$sql = 'DELETE FROM departamento_copy WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Crear y devolver un objeto del tipo DepartamentoCopy
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $DepartamentoCopy DepartamentoCopy
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$departamentoCopy = new DepartamentoCopy();
		
		$departamentoCopy->deptoId = $row['depto_id'];
		$departamentoCopy->deptoCod = $row['depto_cod'];
		$departamentoCopy->deptoNom = $row['depto_nom'];
		$departamentoCopy->paisId = $row['pais_id'];

		return $departamentoCopy;
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