<?php namespace Backend\mysql;
/** 
* Clase 'CiudadTmpMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'CiudadTmp'
* 
* Ejemplo de uso: 
* $CiudadTmpMySqlDAO = new CiudadTmpMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CiudadTmpMySqlDAO implements CiudadTmpDAO{

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
		$sql = 'SELECT * FROM ciudad_tmp WHERE ciudad_id = ?';
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
		$sql = 'SELECT * FROM ciudad_tmp';
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
		$sql = 'SELECT * FROM ciudad_tmp ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $ciudad_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($ciudad_id){
		$sql = 'DELETE FROM ciudad_tmp WHERE ciudad_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($ciudad_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object ciudad ciudad
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($ciudadTmp){
		$sql = 'INSERT INTO ciudad_tmp (ciudad_cod, ciudad_nom, depto_id, longitud, latitud) VALUES (?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($ciudadTmp->ciudadCod);
		$sqlQuery->set($ciudadTmp->ciudadNom);
		$sqlQuery->set($ciudadTmp->deptoId);
		$sqlQuery->set($ciudadTmp->longitud);
		$sqlQuery->set($ciudadTmp->latitud);

		$id = $this->executeInsert($sqlQuery);	
		$ciudadTmp->ciudadId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object ciudad ciudad
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($ciudadTmp){
		$sql = 'UPDATE ciudad_tmp SET ciudad_cod = ?, ciudad_nom = ?, depto_id = ?, longitud = ?, latitud = ? WHERE ciudad_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($ciudadTmp->ciudadCod);
		$sqlQuery->set($ciudadTmp->ciudadNom);
		$sqlQuery->set($ciudadTmp->deptoId);
		$sqlQuery->set($ciudadTmp->longitud);
		$sqlQuery->set($ciudadTmp->latitud);

		$sqlQuery->set($ciudadTmp->ciudadId);
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
		$sql = 'DELETE FROM ciudad_tmp';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ciudad_cod sea igual al valor pasado como parámetro
     *
     * @param String $value ciudad_cod requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByCiudadCod($value){
		$sql = 'SELECT * FROM ciudad_tmp WHERE ciudad_cod = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ciudad_nom sea igual al valor pasado como parámetro
     *
     * @param String $value ciudad_nom requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByCiudadNom($value){
		$sql = 'SELECT * FROM ciudad_tmp WHERE ciudad_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna depto_id sea igual al valor pasado como parámetro
     *
     * @param String $value depto_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByDeptoId($value){
		$sql = 'SELECT * FROM ciudad_tmp WHERE depto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna longitud sea igual al valor pasado como parámetro
     *
     * @param String $value longitud requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByLongitud($value){
		$sql = 'SELECT * FROM ciudad_tmp WHERE longitud = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna latitud sea igual al valor pasado como parámetro
     *
     * @param String $value latitud requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByLatitud($value){
		$sql = 'SELECT * FROM ciudad_tmp WHERE latitud = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}







    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ciudad_cod sea igual al valor pasado como parámetro
     *
     * @param String $value ciudad_cod requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByCiudadCod($value){
		$sql = 'DELETE FROM ciudad_tmp WHERE ciudad_cod = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ciudad_nom sea igual al valor pasado como parámetro
     *
     * @param String $value ciudad_nom requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByCiudadNom($value){
		$sql = 'DELETE FROM ciudad_tmp WHERE ciudad_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna depto_id sea igual al valor pasado como parámetro
     *
     * @param String $value depto_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByDeptoId($value){
		$sql = 'DELETE FROM ciudad_tmp WHERE depto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna longitud sea igual al valor pasado como parámetro
     *
     * @param String $value longitud requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByLongitud($value){
		$sql = 'DELETE FROM ciudad_tmp WHERE longitud = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna latitud sea igual al valor pasado como parámetro
     *
     * @param String $value latitud requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByLatitud($value){
		$sql = 'DELETE FROM ciudad_tmp WHERE latitud = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}








    /**
     * Crear y devolver un objeto del tipo CiudadTmp
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $CiudadTmp CiudadTmp
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$ciudadTmp = new CiudadTmp();
		
		$ciudadTmp->ciudadId = $row['ciudad_id'];
		$ciudadTmp->ciudadCod = $row['ciudad_cod'];
		$ciudadTmp->ciudadNom = $row['ciudad_nom'];
		$ciudadTmp->deptoId = $row['depto_id'];
		$ciudadTmp->longitud = $row['longitud'];
		$ciudadTmp->latitud = $row['latitud'];

		return $ciudadTmp;
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