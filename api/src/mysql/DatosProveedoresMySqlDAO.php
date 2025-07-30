<?php namespace Backend\mysql;
/** 
* Clase 'DatosProveedoresMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'DatosProveedores'
* 
* Ejemplo de uso: 
* $DatosProveedoresMySqlDAO = new DatosProveedoresMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class DatosProveedoresMySqlDAO implements DatosProveedoresDAO{

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
		$sql = 'SELECT * FROM datosProveedores WHERE id = ?';
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
		$sql = 'SELECT * FROM datosProveedores';
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
		$sql = 'SELECT * FROM datosProveedores ORDER BY '.$orderColumn;
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
		$sql = 'DELETE FROM datosProveedores WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object datosProveedore datosProveedore
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($datosProveedore){
		$sql = 'INSERT INTO datosProveedores (localID, remoteID, request, response, timestamp) VALUES (?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($datosProveedore->localID);
		$sqlQuery->set($datosProveedore->remoteID);
		$sqlQuery->set($datosProveedore->request);
		$sqlQuery->set($datosProveedore->response);
		$sqlQuery->set($datosProveedore->timestamp);

		$id = $this->executeInsert($sqlQuery);	
		$datosProveedore->id = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object datosProveedore datosProveedore
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($datosProveedore){
		$sql = 'UPDATE datosProveedores SET localID = ?, remoteID = ?, request = ?, response = ?, timestamp = ? WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($datosProveedore->localID);
		$sqlQuery->set($datosProveedore->remoteID);
		$sqlQuery->set($datosProveedore->request);
		$sqlQuery->set($datosProveedore->response);
		$sqlQuery->set($datosProveedore->timestamp);

		$sqlQuery->set($datosProveedore->id);
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
		$sql = 'DELETE FROM datosProveedores';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna localID sea igual al valor pasado como parámetro
     *
     * @param String $value localID requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByLocalID($value){
		$sql = 'SELECT * FROM datosProveedores WHERE localID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna remoteID sea igual al valor pasado como parámetro
     *
     * @param String $value remoteID requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByRemoteID($value){
		$sql = 'SELECT * FROM datosProveedores WHERE remoteID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna request sea igual al valor pasado como parámetro
     *
     * @param String $value request requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByRequest($value){
		$sql = 'SELECT * FROM datosProveedores WHERE request = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna response sea igual al valor pasado como parámetro
     *
     * @param String $value response requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByResponse($value){
		$sql = 'SELECT * FROM datosProveedores WHERE response = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna timestamp sea igual al valor pasado como parámetro
     *
     * @param String $value timestamp requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTimestamp($value){
		$sql = 'SELECT * FROM datosProveedores WHERE timestamp = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}







    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna localID sea igual al valor pasado como parámetro
     *
     * @param String $value localID requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByLocalID($value){
		$sql = 'DELETE FROM datosProveedores WHERE localID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna remoteID sea igual al valor pasado como parámetro
     *
     * @param String $value remoteID requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByRemoteID($value){
		$sql = 'DELETE FROM datosProveedores WHERE remoteID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna request sea igual al valor pasado como parámetro
     *
     * @param String $value request requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByRequest($value){
		$sql = 'DELETE FROM datosProveedores WHERE request = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna response sea igual al valor pasado como parámetro
     *
     * @param String $value response requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByResponse($value){
		$sql = 'DELETE FROM datosProveedores WHERE response = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna timestamp sea igual al valor pasado como parámetro
     *
     * @param String $value timestamp requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTimestamp($value){
		$sql = 'DELETE FROM datosProveedores WHERE timestamp = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}








    /**
     * Crear y devolver un objeto del tipo DatosProveedore
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $DatosProveedore DatosProveedore
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$datosProveedore = new DatosProveedore();
		
		$datosProveedore->id = $row['id'];
		$datosProveedore->localID = $row['localID'];
		$datosProveedore->remoteID = $row['remoteID'];
		$datosProveedore->request = $row['request'];
		$datosProveedore->response = $row['response'];
		$datosProveedore->timestamp = $row['timestamp'];

		return $datosProveedore;
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