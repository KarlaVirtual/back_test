<?php namespace Backend\mysql;
/** 
* Clase 'SesionesMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'SesionesMySql'
* 
* Ejemplo de uso: 
* $SesionesMySqlDAO = new SesionesMySqlDAO();
*	
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class SesionesMySqlDAO implements SesionesDAO{

	/**
	 * Obtener todos los registros condicionados por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param String $id llave primaria
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function load($id){
		$sql = 'SELECT * FROM sesiones WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Obtener todos los registros de la base datos
	 *
	 * @return Array $ resultado de la consulta
     *
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM sesiones';
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
	 * @return Array $ resultado de la consulta
     *
	 */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM sesiones ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($id){
		$sql = 'DELETE FROM sesiones WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto sesione sesione
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($sesione){
		$sql = 'INSERT INTO sesiones (horario, data, clave_sesion) VALUES (?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($sesione->horario);
		$sqlQuery->set($sesione->data);
		$sqlQuery->set($sesione->claveSesion);

		$id = $this->executeInsert($sqlQuery);	
		$sesione->id = $id;
		return $id;
	}
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto sesione sesione
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($sesione){
		$sql = 'UPDATE sesiones SET horario = ?, data = ?, clave_sesion = ? WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($sesione->horario);
		$sqlQuery->set($sesione->data);
		$sqlQuery->set($sesione->claveSesion);

		$sqlQuery->set($sesione->id);
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
		$sql = 'DELETE FROM sesiones';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}













	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna horario sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value horario requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByHorario($value){
		$sql = 'SELECT * FROM sesiones WHERE horario = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna data sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value data requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByData($value){
		$sql = 'SELECT * FROM sesiones WHERE data = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna clave_sesion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value clave_sesion requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByClaveSesion($value){
		$sql = 'SELECT * FROM sesiones WHERE clave_sesion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}






	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna horario sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value horario requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByHorario($value){
		$sql = 'DELETE FROM sesiones WHERE horario = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna data sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value data requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByData($value){
		$sql = 'DELETE FROM sesiones WHERE data = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna clave_sesion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value clave_sesion requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByClaveSesion($value){
		$sql = 'DELETE FROM sesiones WHERE clave_sesion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}





	
	
	/**
 	 * Crear y devolver un objeto del tipo Sesione
 	 * con los valores de una consulta sql
 	 * 
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Objeto $sesione Sesione
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row){
		$sesione = new Sesione();
		
		$sesione->id = $row['id'];
		$sesione->horario = $row['horario'];
		$sesione->data = $row['data'];
		$sesione->claveSesion = $row['clave_sesion'];

		return $sesione;
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