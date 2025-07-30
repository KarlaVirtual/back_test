<?php namespace Backend\mysql;
/** 
* Clase 'CasinoTransaccionMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'CasinoTransaccionD'
* 
* Ejemplo de uso: 
* $CasinoTransaccionMySqlDAO = new CasinoTransaccionMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CasinoTransaccionMySqlDAO implements CasinoTransaccionDAO{

	/**
	 * Obtener el registro condicionado por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param String $id llave primaria
	 *
     * @return Array $ resultado de la consulta
	 *
	 */
	public function load($id){
		$sql = 'SELECT * FROM casino_transaccion WHERE transaccion_id = ?';
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
		$sql = 'SELECT * FROM casino_transaccion';
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
		$sql = 'SELECT * FROM casino_transaccion ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $transaccion_id llave primaria
 	 *
     * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($transaccion_id){
		$sql = 'DELETE FROM casino_transaccion WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($transaccion_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Object casinoTransaccion casinoTransaccion
 	 *
     * @return String $id resultado de la consulta
     *
 	 */
	public function insert($casinoTransaccion){
		$sql = 'INSERT INTO casino_transaccion (id_user, id_operacion, valor, fecha_trans, tipo, mandante) VALUES (?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($casinoTransaccion->idUser);
		$sqlQuery->set($casinoTransaccion->idOperacion);
		$sqlQuery->set($casinoTransaccion->valor);
		$sqlQuery->set($casinoTransaccion->fechaTrans);
		$sqlQuery->set($casinoTransaccion->tipo);
		$sqlQuery->set($casinoTransaccion->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$casinoTransaccion->transaccionId = $id;
		return $id;
	}
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object casinoTransaccion casinoTransaccion
 	 *
     * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($casinoTransaccion){
		$sql = 'UPDATE casino_transaccion SET id_user = ?, id_operacion = ?, valor = ?, fecha_trans = ?, tipo = ?, mandante = ? WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($casinoTransaccion->idUser);
		$sqlQuery->set($casinoTransaccion->idOperacion);
		$sqlQuery->set($casinoTransaccion->valor);
		$sqlQuery->set($casinoTransaccion->fechaTrans);
		$sqlQuery->set($casinoTransaccion->tipo);
		$sqlQuery->set($casinoTransaccion->mandante);

		$sqlQuery->set($casinoTransaccion->transaccionId);
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
		$sql = 'DELETE FROM casino_transaccion';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna IdUser sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IdUser requerido
 	 *
     * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByIdUser($value){
		$sql = 'SELECT * FROM casino_transaccion WHERE id_user = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna IdOperacion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IdOperacion requerido
 	 *
     * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByIdOperacion($value){
		$sql = 'SELECT * FROM casino_transaccion WHERE id_operacion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 *
     * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByValor($value){
		$sql = 'SELECT * FROM casino_transaccion WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaTrans sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaTrans requerido
 	 *
     * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByFechaTrans($value){
		$sql = 'SELECT * FROM casino_transaccion WHERE fecha_trans = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 *
     * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByTipo($value){
		$sql = 'SELECT * FROM casino_transaccion WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 *
     * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM casino_transaccion WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna IdUser sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IdUser requerido
 	 *
     * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByIdUser($value){
		$sql = 'DELETE FROM casino_transaccion WHERE id_user = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna IdOperacion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IdOperacion requerido
 	 *
     * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByIdOperacion($value){
		$sql = 'DELETE FROM casino_transaccion WHERE id_operacion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 *
     * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByValor($value){
		$sql = 'DELETE FROM casino_transaccion WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaTrans sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaTrans requerido
 	 *
     * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFechaTrans($value){
		$sql = 'DELETE FROM casino_transaccion WHERE fecha_trans = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 *
     * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTipo($value){
		$sql = 'DELETE FROM casino_transaccion WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 *
     * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM casino_transaccion WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}






	/**
 	 * Crear y devolver un objeto del tipo CasinoTransaccion
 	 * con los valores de una consulta sql
 	 * 
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Object $CasinoTransaccion CasinoTransaccion
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row){
		$casinoTransaccion = new CasinoTransaccion();
		
		$casinoTransaccion->transaccionId = $row['transaccion_id'];
		$casinoTransaccion->idUser = $row['id_user'];
		$casinoTransaccion->idOperacion = $row['id_operacion'];
		$casinoTransaccion->valor = $row['valor'];
		$casinoTransaccion->fechaTrans = $row['fecha_trans'];
		$casinoTransaccion->tipo = $row['tipo'];
		$casinoTransaccion->mandante = $row['mandante'];

		return $casinoTransaccion;
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