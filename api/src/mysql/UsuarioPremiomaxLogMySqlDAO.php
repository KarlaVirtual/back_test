<?php namespace Backend\mysql;
/** 
* Clase 'UsuarioPremiomaxLogMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioPremiomaxLog'
* 
* Ejemplo de uso: 
* $UsuarioPremiomaxLogMySqlDAO = new UsuarioPremiomaxLogMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioPremiomaxLogMySqlDAO implements UsuarioPremiomaxLogDAO
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
		$sql = 'SELECT * FROM usuario_premiomax_log WHERE premiomaxlog_id = ?';
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
		$sql = 'SELECT * FROM usuario_premiomax_log';
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
		$sql = 'SELECT * FROM usuario_premiomax_log ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $premiomaxlog_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($premiomaxlog_id){
		$sql = 'DELETE FROM usuario_premiomax_log WHERE premiomaxlog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($premiomaxlog_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioPremiomaxLog usuarioPremiomaxLog
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($usuarioPremiomaxLog){
		$sql = 'INSERT INTO usuario_premiomax_log (usuario_id, fecha, valor, mandante) VALUES (?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($usuarioPremiomaxLog->usuarioId);
		$sqlQuery->set($usuarioPremiomaxLog->fecha);
		$sqlQuery->set($usuarioPremiomaxLog->valor);
		$sqlQuery->set($usuarioPremiomaxLog->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$usuarioPremiomaxLog->premiomaxlogId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioPremiomaxLog usuarioPremiomaxLog
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($usuarioPremiomaxLog){
		$sql = 'UPDATE usuario_premiomax_log SET usuario_id = ?, fecha = ?, valor = ?, mandante = ? WHERE premiomaxlog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($usuarioPremiomaxLog->usuarioId);
		$sqlQuery->set($usuarioPremiomaxLog->fecha);
		$sqlQuery->set($usuarioPremiomaxLog->valor);
		$sqlQuery->set($usuarioPremiomaxLog->mandante);

		$sqlQuery->set($usuarioPremiomaxLog->premiomaxlogId);
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
		$sql = 'DELETE FROM usuario_premiomax_log';
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
		$sql = 'SELECT * FROM usuario_premiomax_log WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha sea igual al valor pasado como parámetro
     *
     * @param String $value fecha requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFecha($value){
		$sql = 'SELECT * FROM usuario_premiomax_log WHERE fecha = ?';
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
		$sql = 'SELECT * FROM usuario_premiomax_log WHERE valor = ?';
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
		$sql = 'SELECT * FROM usuario_premiomax_log WHERE mandante = ?';
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
		$sql = 'DELETE FROM usuario_premiomax_log WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha sea igual al valor pasado como parámetro
     *
     * @param String $value fecha requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFecha($value){
		$sql = 'DELETE FROM usuario_premiomax_log WHERE fecha = ?';
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
		$sql = 'DELETE FROM usuario_premiomax_log WHERE valor = ?';
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
		$sql = 'DELETE FROM usuario_premiomax_log WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Crear y devolver un objeto del tipo UsuarioPremiomaxLog
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioPremiomaxLog UsuarioPremiomaxLog
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$usuarioPremiomaxLog = new UsuarioPremiomaxLog();
		
		$usuarioPremiomaxLog->premiomaxlogId = $row['premiomaxlog_id'];
		$usuarioPremiomaxLog->usuarioId = $row['usuario_id'];
		$usuarioPremiomaxLog->fecha = $row['fecha'];
		$usuarioPremiomaxLog->valor = $row['valor'];
		$usuarioPremiomaxLog->mandante = $row['mandante'];

		return $usuarioPremiomaxLog;
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