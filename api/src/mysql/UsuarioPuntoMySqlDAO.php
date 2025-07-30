<?php namespace Backend\mysql;
/** 
* Clase 'UsuarioPuntoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioPunto'
* 
* Ejemplo de uso: 
* $UsuarioPuntoMySqlDAO = new UsuarioPuntoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioPuntoMySqlDAO implements UsuarioPuntoDAO
{

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
		$sql = 'SELECT * FROM usuario_punto WHERE usupunto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros condicionados por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM usuario_punto';
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
		$sql = 'SELECT * FROM usuario_punto ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usupunto_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($usupunto_id){
		$sql = 'DELETE FROM usuario_punto WHERE usupunto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($usupunto_id);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioPunto usuarioPunto
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($usuarioPunto){
		$sql = 'INSERT INTO usuario_punto (usuario_id, puntoventa_id, mandante) VALUES (?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($usuarioPunto->usuarioId);
		$sqlQuery->set($usuarioPunto->puntoventaId);
		$sqlQuery->set($usuarioPunto->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$usuarioPunto->usupuntoId = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioPunto usuarioPunto
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($usuarioPunto){
		$sql = 'UPDATE usuario_punto SET usuario_id = ?, puntoventa_id = ?, mandante = ? WHERE usupunto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($usuarioPunto->usuarioId);
		$sqlQuery->set($usuarioPunto->puntoventaId);
		$sqlQuery->set($usuarioPunto->mandante);

		$sqlQuery->set($usuarioPunto->usupuntoId);
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
		$sql = 'DELETE FROM usuario_punto';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}












    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM usuario_punto WHERE usuario_id = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByPuntoventaId($value){
		$sql = 'SELECT * FROM usuario_punto WHERE puntoventa_id = ?';
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
		$sql = 'SELECT * FROM usuario_punto WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}








    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsuarioId($value){
		$sql = 'DELETE FROM usuario_punto WHERE usuario_id = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPuntoventaId($value){
		$sql = 'DELETE FROM usuario_punto WHERE puntoventa_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna email sea igual al valor pasado como parámetro
     *
     * @param String $value email requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM usuario_punto WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}









    /**
     * Crear y devolver un objeto del tipo UsuarioPunto
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioPunto UsuarioPunto
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$usuarioPunto = new UsuarioPunto();
		
		$usuarioPunto->usupuntoId = $row['usupunto_id'];
		$usuarioPunto->usuarioId = $row['usuario_id'];
		$usuarioPunto->puntoventaId = $row['puntoventa_id'];
		$usuarioPunto->mandante = $row['mandante'];

		return $usuarioPunto;
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