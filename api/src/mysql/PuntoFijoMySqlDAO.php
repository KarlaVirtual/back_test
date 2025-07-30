<?php namespace Backend\mysql;
/** 
* Clase 'PuntoFijoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'PuntoFijo'
* 
* Ejemplo de uso: 
* $PuntoFijoMySqlDAO = new PuntoFijoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PuntoFijoMySqlDAO implements PuntoFijoDAO
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
		$sql = 'SELECT * FROM punto_fijo WHERE puntofijo_id = ?';
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
		$sql = 'SELECT * FROM punto_fijo';
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
		$sql = 'SELECT * FROM punto_fijo ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $puntofijo_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($puntofijo_id){
		$sql = 'DELETE FROM punto_fijo WHERE puntofijo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($puntofijo_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object puntoFijo puntoFijo
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($puntoFijo){
		$sql = 'INSERT INTO punto_fijo (nodo_id, puntoventa_id, mandante) VALUES (?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($puntoFijo->nodoId);
		$sqlQuery->set($puntoFijo->puntoventaId);
		$sqlQuery->set($puntoFijo->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$puntoFijo->puntofijoId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object puntoFijo puntoFijo
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($puntoFijo){
		$sql = 'UPDATE punto_fijo SET nodo_id = ?, puntoventa_id = ?, mandante = ? WHERE puntofijo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($puntoFijo->nodoId);
		$sqlQuery->set($puntoFijo->puntoventaId);
		$sqlQuery->set($puntoFijo->mandante);

		$sqlQuery->set($puntoFijo->puntofijoId);
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
		$sql = 'DELETE FROM punto_fijo';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nodo_id sea igual al valor pasado como parámetro
     *
     * @param String $value nodo_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByNodoId($value){
		$sql = 'SELECT * FROM punto_fijo WHERE nodo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna puntoventa_id sea igual al valor pasado como parámetro
     *
     * @param String $value puntoventa_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPuntoventaId($value){
		$sql = 'SELECT * FROM punto_fijo WHERE puntoventa_id = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM punto_fijo WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nodo_id sea igual al valor pasado como parámetro
     *
     * @param String $value nodo_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByNodoId($value){
		$sql = 'DELETE FROM punto_fijo WHERE nodo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna puntoventa_id sea igual al valor pasado como parámetro
     *
     * @param String $value puntoventa_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPuntoventaId($value){
		$sql = 'DELETE FROM punto_fijo WHERE puntoventa_id = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM punto_fijo WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	



    /**
     * Crear y devolver un objeto del tipo PuntoFijo
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $PuntoFijo PuntoFijo
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$puntoFijo = new PuntoFijo();
		
		$puntoFijo->puntofijoId = $row['puntofijo_id'];
		$puntoFijo->nodoId = $row['nodo_id'];
		$puntoFijo->puntoventaId = $row['puntoventa_id'];
		$puntoFijo->mandante = $row['mandante'];

		return $puntoFijo;
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