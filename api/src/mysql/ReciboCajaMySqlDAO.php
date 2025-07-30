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
class ReciboCajaMySqlDAO implements ReciboCajaDAO{

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
		$sql = 'SELECT * FROM recibo_caja WHERE recibo_id = ?';
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
		$sql = 'SELECT * FROM recibo_caja';
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
		$sql = 'SELECT * FROM recibo_caja ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $recibo_id llave primaria
 	 *
     * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($recibo_id){
		$sql = 'DELETE FROM recibo_caja WHERE recibo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($recibo_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto reciboCaja reciboCaja
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($reciboCaja){
		$sql = 'INSERT INTO recibo_caja (fecha_crea, hora_crea, usucrea_id, depositante_id, valor, mandante) VALUES (?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($reciboCaja->fechaCrea);
		$sqlQuery->set($reciboCaja->horaCrea);
		$sqlQuery->set($reciboCaja->usucreaId);
		$sqlQuery->set($reciboCaja->depositanteId);
		$sqlQuery->set($reciboCaja->valor);
		$sqlQuery->set($reciboCaja->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$reciboCaja->reciboId = $id;
		return $id;
	}
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto reciboCaja reciboCaja
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($reciboCaja){
		$sql = 'UPDATE recibo_caja SET fecha_crea = ?, hora_crea = ?, usucrea_id = ?, depositante_id = ?, valor = ?, mandante = ? WHERE recibo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($reciboCaja->fechaCrea);
		$sqlQuery->set($reciboCaja->horaCrea);
		$sqlQuery->set($reciboCaja->usucreaId);
		$sqlQuery->set($reciboCaja->depositanteId);
		$sqlQuery->set($reciboCaja->valor);
		$sqlQuery->set($reciboCaja->mandante);

		$sqlQuery->set($reciboCaja->reciboId);
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
		$sql = 'DELETE FROM recibo_caja';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna fecha_crea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_crea requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM recibo_caja WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna hora_crea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value hora_crea requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByHoraCrea($value){
		$sql = 'SELECT * FROM recibo_caja WHERE hora_crea = ?';
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
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM recibo_caja WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna depositante_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value depositante_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByDepositanteId($value){
		$sql = 'SELECT * FROM recibo_caja WHERE depositante_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value valor requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByValor($value){
		$sql = 'SELECT * FROM recibo_caja WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value mandante requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM recibo_caja WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}






	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna fecha_crea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_crea requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM recibo_caja WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna hora_crea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value hora_crea requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByHoraCrea($value){
		$sql = 'DELETE FROM recibo_caja WHERE hora_crea = ?';
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
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByUsucreaId($value){
		$sql = 'DELETE FROM recibo_caja WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna depositante_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value depositante_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByDepositanteId($value){
		$sql = 'DELETE FROM recibo_caja WHERE depositante_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value valor requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByValor($value){
		$sql = 'DELETE FROM recibo_caja WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value mandante requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM recibo_caja WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}






	
	/**
 	 * Crear y devolver un objeto del tipo ReciboCaja
 	 * con los valores de una consulta sql
 	 * 
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Objeto $reciboCaja ReciboCaja
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row){
		$reciboCaja = new ReciboCaja();
		
		$reciboCaja->reciboId = $row['recibo_id'];
		$reciboCaja->fechaCrea = $row['fecha_crea'];
		$reciboCaja->horaCrea = $row['hora_crea'];
		$reciboCaja->usucreaId = $row['usucrea_id'];
		$reciboCaja->depositanteId = $row['depositante_id'];
		$reciboCaja->valor = $row['valor'];
		$reciboCaja->mandante = $row['mandante'];

		return $reciboCaja;
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