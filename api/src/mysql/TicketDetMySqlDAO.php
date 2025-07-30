<?php namespace Backend\mysql;
/** 
* Clase 'TicketDetMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'TicketDet'
* 
* Ejemplo de uso: 
* $TicketDetMySqlDAO = new TicketDetMySqlDAO();
*	
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TicketDetMySqlDAO implements TicketDetDAO{

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
		$sql = 'SELECT * FROM ticket_det WHERE ticketdet_id = ?';
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
		$sql = 'SELECT * FROM ticket_det';
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
		$sql = 'SELECT * FROM ticket_det ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $ticketdet_id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($ticketdet_id){
		$sql = 'DELETE FROM ticket_det WHERE ticketdet_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($ticketdet_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto ticketDet ticketDet
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($ticketDet){
		$sql = 'INSERT INTO ticket_det (ticket_id, apuestadet_id, logro, estado, abreviado, usumodifica_id, fecha_modifica, premiado, logro_consolidado, vlr_apuesta, tipo, logro_base, lista_id, logro_consolidado_base, accion, mandante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($ticketDet->ticketId);
		$sqlQuery->set($ticketDet->apuestadetId);
		$sqlQuery->set($ticketDet->logro);
		$sqlQuery->set($ticketDet->estado);
		$sqlQuery->set($ticketDet->abreviado);
		$sqlQuery->set($ticketDet->usumodificaId);
		$sqlQuery->set($ticketDet->fechaModifica);
		$sqlQuery->set($ticketDet->premiado);
		$sqlQuery->set($ticketDet->logroConsolidado);
		$sqlQuery->set($ticketDet->vlrApuesta);
		$sqlQuery->set($ticketDet->tipo);
		$sqlQuery->set($ticketDet->logroBase);
		$sqlQuery->set($ticketDet->listaId);
		$sqlQuery->set($ticketDet->logroConsolidadoBase);
		$sqlQuery->set($ticketDet->accion);
		$sqlQuery->set($ticketDet->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$ticketDet->ticketdetId = $id;
		return $id;
	}
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto ticketDet ticketDet
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($ticketDet){
		$sql = 'UPDATE ticket_det SET ticket_id = ?, apuestadet_id = ?, logro = ?, estado = ?, abreviado = ?, usumodifica_id = ?, fecha_modifica = ?, premiado = ?, logro_consolidado = ?, vlr_apuesta = ?, tipo = ?, logro_base = ?, lista_id = ?, logro_consolidado_base = ?, accion = ?, mandante = ? WHERE ticketdet_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($ticketDet->ticketId);
		$sqlQuery->set($ticketDet->apuestadetId);
		$sqlQuery->set($ticketDet->logro);
		$sqlQuery->set($ticketDet->estado);
		$sqlQuery->set($ticketDet->abreviado);
		$sqlQuery->set($ticketDet->usumodificaId);
		$sqlQuery->set($ticketDet->fechaModifica);
		$sqlQuery->set($ticketDet->premiado);
		$sqlQuery->set($ticketDet->logroConsolidado);
		$sqlQuery->set($ticketDet->vlrApuesta);
		$sqlQuery->set($ticketDet->tipo);
		$sqlQuery->set($ticketDet->logroBase);
		$sqlQuery->set($ticketDet->listaId);
		$sqlQuery->set($ticketDet->logroConsolidadoBase);
		$sqlQuery->set($ticketDet->accion);
		$sqlQuery->set($ticketDet->mandante);

		$sqlQuery->set($ticketDet->ticketdetId);
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
		$sql = 'DELETE FROM ticket_det';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}











	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ticket_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ticket_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTicketId($value){
		$sql = 'SELECT * FROM ticket_det WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna apuestadet_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apuestadet_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByApuestadetId($value){
		$sql = 'SELECT * FROM ticket_det WHERE apuestadet_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna logro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value logro requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByLogro($value){
		$sql = 'SELECT * FROM ticket_det WHERE logro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value estado requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM ticket_det WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna abreviado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value abreviado requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByAbreviado($value){
		$sql = 'SELECT * FROM ticket_det WHERE abreviado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna usumodifica_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value usumodifica_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByUsumodificaId($value){
		$sql = 'SELECT * FROM ticket_det WHERE usumodifica_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna fecha_modifica sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_modifica requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByFechaModifica($value){
		$sql = 'SELECT * FROM ticket_det WHERE fecha_modifica = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna premiado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value premiado requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByPremiado($value){
		$sql = 'SELECT * FROM ticket_det WHERE premiado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna logro_consolidado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value logro_consolidado requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByLogroConsolidado($value){
		$sql = 'SELECT * FROM ticket_det WHERE logro_consolidado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna vlr_apuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value vlr_apuesta requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByVlrApuesta($value){
		$sql = 'SELECT * FROM ticket_det WHERE vlr_apuesta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTipo($value){
		$sql = 'SELECT * FROM ticket_det WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna logro_base sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value logro_base requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByLogroBase($value){
		$sql = 'SELECT * FROM ticket_det WHERE logro_base = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna lista_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value lista_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByListaId($value){
		$sql = 'SELECT * FROM ticket_det WHERE lista_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna logro_consolidado_base sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value logro_consolidado_base requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByLogroConsolidadoBase($value){
		$sql = 'SELECT * FROM ticket_det WHERE logro_consolidado_base = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna accion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value accion requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByAccion($value){
		$sql = 'SELECT * FROM ticket_det WHERE accion = ?';
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
		$sql = 'SELECT * FROM ticket_det WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}













	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ticket_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ticket_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByTicketId($value){
		$sql = 'DELETE FROM ticket_det WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna apuestadet_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apuestadet_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByApuestadetId($value){
		$sql = 'DELETE FROM ticket_det WHERE apuestadet_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna logro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value logro requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByLogro($value){
		$sql = 'DELETE FROM ticket_det WHERE logro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value estado requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByEstado($value){
		$sql = 'DELETE FROM ticket_det WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna abreviado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value abreviado requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByAbreviado($value){
		$sql = 'DELETE FROM ticket_det WHERE abreviado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna usumodifica_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value usumodifica_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByUsumodificaId($value){
		$sql = 'DELETE FROM ticket_det WHERE usumodifica_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna fecha_modifica sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_modifica requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByFechaModifica($value){
		$sql = 'DELETE FROM ticket_det WHERE fecha_modifica = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna premiado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value premiado requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByPremiado($value){
		$sql = 'DELETE FROM ticket_det WHERE premiado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna logro_consolidado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value logro_consolidado requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByLogroConsolidado($value){
		$sql = 'DELETE FROM ticket_det WHERE logro_consolidado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna vlr_apuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value vlr_apuesta requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByVlrApuesta($value){
		$sql = 'DELETE FROM ticket_det WHERE vlr_apuesta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByTipo($value){
		$sql = 'DELETE FROM ticket_det WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna logro_base sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value logro_base requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByLogroBase($value){
		$sql = 'DELETE FROM ticket_det WHERE logro_base = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna lista_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value lista_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByListaId($value){
		$sql = 'DELETE FROM ticket_det WHERE lista_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna logro_consolidado_base sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value logro_consolidado_base requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByLogroConsolidadoBase($value){
		$sql = 'DELETE FROM ticket_det WHERE logro_consolidado_base = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna accion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value accion requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function deleteByAccion($value){
		$sql = 'DELETE FROM ticket_det WHERE accion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
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
	public function deleteByMandante($value){
		$sql = 'DELETE FROM ticket_det WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	






	
	/**
 	 * Crear y devolver un objeto del tipo TicketDet
 	 * con los valores de una consulta sql
 	 * 
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Objeto $ticketDet TicketDet
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row){
		$ticketDet = new TicketDet();
		
		$ticketDet->ticketdetId = $row['ticketdet_id'];
		$ticketDet->ticketId = $row['ticket_id'];
		$ticketDet->apuestadetId = $row['apuestadet_id'];
		$ticketDet->logro = $row['logro'];
		$ticketDet->estado = $row['estado'];
		$ticketDet->abreviado = $row['abreviado'];
		$ticketDet->usumodificaId = $row['usumodifica_id'];
		$ticketDet->fechaModifica = $row['fecha_modifica'];
		$ticketDet->premiado = $row['premiado'];
		$ticketDet->logroConsolidado = $row['logro_consolidado'];
		$ticketDet->vlrApuesta = $row['vlr_apuesta'];
		$ticketDet->tipo = $row['tipo'];
		$ticketDet->logroBase = $row['logro_base'];
		$ticketDet->listaId = $row['lista_id'];
		$ticketDet->logroConsolidadoBase = $row['logro_consolidado_base'];
		$ticketDet->accion = $row['accion'];
		$ticketDet->mandante = $row['mandante'];

		return $ticketDet;
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