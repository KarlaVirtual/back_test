<?php namespace Backend\mysql;
/** 
* Clase 'UsuarioRecargaEliminadoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioRecargaEliminado'
* 
* Ejemplo de uso: 
* $UsuarioRecargaEliminadoMySqlDAO = new UsuarioRecargaEliminadoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioRecargaEliminadoMySqlDAO implements UsuarioRecargaEliminadoDAO
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
		$sql = 'SELECT * FROM usuario_recarga_eliminado WHERE recargaelimina_id = ?';
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
		$sql = 'SELECT * FROM usuario_recarga_eliminado';
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
		$sql = 'SELECT * FROM usuario_recarga_eliminado ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $recargaelimina_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($recargaelimina_id){
		$sql = 'DELETE FROM usuario_recarga_eliminado WHERE recargaelimina_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($recargaelimina_id);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioRecargaEliminado usuarioRecargaEliminado
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($usuarioRecargaEliminado){
		$sql = 'INSERT INTO usuario_recarga_eliminado (usuario_id, fecha_crea, puntoventa_id, valor, fecha_elimina, usuelimina_id, recarga_id, mandante) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($usuarioRecargaEliminado->usuarioId);
		$sqlQuery->set($usuarioRecargaEliminado->fechaCrea);
		$sqlQuery->set($usuarioRecargaEliminado->puntoventaId);
		$sqlQuery->set($usuarioRecargaEliminado->valor);
		$sqlQuery->set($usuarioRecargaEliminado->fechaElimina);
		$sqlQuery->set($usuarioRecargaEliminado->usueliminaId);
		$sqlQuery->set($usuarioRecargaEliminado->recargaId);
		$sqlQuery->set($usuarioRecargaEliminado->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$usuarioRecargaEliminado->recargaeliminaId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioRecargaEliminado usuarioRecargaEliminado
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($usuarioRecargaEliminado){
		$sql = 'UPDATE usuario_recarga_eliminado SET usuario_id = ?, fecha_crea = ?, puntoventa_id = ?, valor = ?, fecha_elimina = ?, usuelimina_id = ?, recarga_id = ?, mandante = ? WHERE recargaelimina_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($usuarioRecargaEliminado->usuarioId);
		$sqlQuery->set($usuarioRecargaEliminado->fechaCrea);
		$sqlQuery->set($usuarioRecargaEliminado->puntoventaId);
		$sqlQuery->set($usuarioRecargaEliminado->valor);
		$sqlQuery->set($usuarioRecargaEliminado->fechaElimina);
		$sqlQuery->set($usuarioRecargaEliminado->usueliminaId);
		$sqlQuery->set($usuarioRecargaEliminado->recargaId);
		$sqlQuery->set($usuarioRecargaEliminado->mandante);

		$sqlQuery->set($usuarioRecargaEliminado->recargaeliminaId);
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
		$sql = 'DELETE FROM usuario_recarga_eliminado';
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
		$sql = 'SELECT * FROM usuario_recarga_eliminado WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
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
		$sql = 'SELECT * FROM usuario_recarga_eliminado WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM usuario_recarga_eliminado WHERE puntoventa_id = ?';
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
		$sql = 'SELECT * FROM usuario_recarga_eliminado WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_elimina sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_elimina requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaElimina($value){
		$sql = 'SELECT * FROM usuario_recarga_eliminado WHERE fecha_elimina = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuelimina_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuelimina_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsueliminaId($value){
		$sql = 'SELECT * FROM usuario_recarga_eliminado WHERE usuelimina_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna recarga_id sea igual al valor pasado como parámetro
     *
     * @param String $value recarga_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByRecargaId($value){
		$sql = 'SELECT * FROM usuario_recarga_eliminado WHERE recarga_id = ?';
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
		$sql = 'SELECT * FROM usuario_recarga_eliminado WHERE mandante = ?';
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
		$sql = 'DELETE FROM usuario_recarga_eliminado WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
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
		$sql = 'DELETE FROM usuario_recarga_eliminado WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM usuario_recarga_eliminado WHERE puntoventa_id = ?';
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
		$sql = 'DELETE FROM usuario_recarga_eliminado WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuelimina_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuelimina_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaElimina($value){
		$sql = 'DELETE FROM usuario_recarga_eliminado WHERE fecha_elimina = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuelimina_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuelimina_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsueliminaId($value){
		$sql = 'DELETE FROM usuario_recarga_eliminado WHERE usuelimina_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna recarga_id sea igual al valor pasado como parámetro
     *
     * @param String $value recarga_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByRecargaId($value){
		$sql = 'DELETE FROM usuario_recarga_eliminado WHERE recarga_id = ?';
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
		$sql = 'DELETE FROM usuario_recarga_eliminado WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}












    /**
     * Crear y devolver un objeto del tipo UsuarioRecargaEliminado
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioRecargaEliminado UsuarioRecargaEliminado
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$usuarioRecargaEliminado = new UsuarioRecargaEliminado();
		
		$usuarioRecargaEliminado->recargaeliminaId = $row['recargaelimina_id'];
		$usuarioRecargaEliminado->usuarioId = $row['usuario_id'];
		$usuarioRecargaEliminado->fechaCrea = $row['fecha_crea'];
		$usuarioRecargaEliminado->puntoventaId = $row['puntoventa_id'];
		$usuarioRecargaEliminado->valor = $row['valor'];
		$usuarioRecargaEliminado->fechaElimina = $row['fecha_elimina'];
		$usuarioRecargaEliminado->usueliminaId = $row['usuelimina_id'];
		$usuarioRecargaEliminado->recargaId = $row['recarga_id'];
		$usuarioRecargaEliminado->mandante = $row['mandante'];

		return $usuarioRecargaEliminado;
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