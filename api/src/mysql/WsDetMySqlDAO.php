<?php namespace Backend\mysql;
/** 
* Clase 'WsDetMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'WsDet'
* 
* Ejemplo de uso: 
* $WsDetMySqlDAO = new WsDetMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class WsDetMySqlDAO implements WsDetDAO
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
		$sql = 'SELECT * FROM ws_det WHERE det_id = ?';
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
		$sql = 'SELECT * FROM ws_det';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
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
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM ws_det ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $det_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($det_id){
		$sql = 'DELETE FROM ws_det WHERE det_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($det_id);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto wsDet wsDet
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($wsDet){
		$sql = 'INSERT INTO ws_det (ticket_id, evento_id, descripcion, opcion, logro, premiado, estado, fecha_crea, usucrea_id, fecha_modif, usumodif_id, mandante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($wsDet->ticketId);
		$sqlQuery->set($wsDet->eventoId);
		$sqlQuery->set($wsDet->descripcion);
		$sqlQuery->set($wsDet->opcion);
		$sqlQuery->set($wsDet->logro);
		$sqlQuery->set($wsDet->premiado);
		$sqlQuery->set($wsDet->estado);
		$sqlQuery->set($wsDet->fechaCrea);
		$sqlQuery->set($wsDet->usucreaId);
		$sqlQuery->set($wsDet->fechaModif);
		$sqlQuery->set($wsDet->usumodifId);
		$sqlQuery->set($wsDet->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$wsDet->detId = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto wsDet wsDet
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($wsDet){
		$sql = 'UPDATE ws_det SET ticket_id = ?, evento_id = ?, descripcion = ?, opcion = ?, logro = ?, premiado = ?, estado = ?, fecha_crea = ?, usucrea_id = ?, fecha_modif = ?, usumodif_id = ?, mandante = ? WHERE det_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($wsDet->ticketId);
		$sqlQuery->set($wsDet->eventoId);
		$sqlQuery->set($wsDet->descripcion);
		$sqlQuery->set($wsDet->opcion);
		$sqlQuery->set($wsDet->logro);
		$sqlQuery->set($wsDet->premiado);
		$sqlQuery->set($wsDet->estado);
		$sqlQuery->set($wsDet->fechaCrea);
		$sqlQuery->set($wsDet->usucreaId);
		$sqlQuery->set($wsDet->fechaModif);
		$sqlQuery->set($wsDet->usumodifId);
		$sqlQuery->set($wsDet->mandante);

		$sqlQuery->set($wsDet->detId);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioToken usuarioToken
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function clean(){
		$sql = 'DELETE FROM ws_det';
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
		$sql = 'SELECT * FROM ws_det WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna evento_id sea igual al valor pasado como parámetro
     *
     * @param String $value evento_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByEventoId($value){
		$sql = 'SELECT * FROM ws_det WHERE evento_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByDescripcion($value){
		$sql = 'SELECT * FROM ws_det WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna opcion sea igual al valor pasado como parámetro
     *
     * @param String $value opcion requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByOpcion($value){
		$sql = 'SELECT * FROM ws_det WHERE opcion = ?';
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
		$sql = 'SELECT * FROM ws_det WHERE logro = ?';
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
		$sql = 'SELECT * FROM ws_det WHERE premiado = ?';
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
		$sql = 'SELECT * FROM ws_det WHERE estado = ?';
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
		$sql = 'SELECT * FROM ws_det WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM ws_det WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM ws_det WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM ws_det WHERE usumodif_id = ?';
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
		$sql = 'SELECT * FROM ws_det WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ticket_id sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByTicketId($value){
		$sql = 'DELETE FROM ws_det WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna evento_id sea igual al valor pasado como parámetro
     *
     * @param String $value evento_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByEventoId($value){
		$sql = 'DELETE FROM ws_det WHERE evento_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByDescripcion($value){
		$sql = 'DELETE FROM ws_det WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna opcion sea igual al valor pasado como parámetro
     *
     * @param String $value opcion requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByOpcion($value){
		$sql = 'DELETE FROM ws_det WHERE opcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna logro sea igual al valor pasado como parámetro
     *
     * @param String $value logro requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByLogro($value){
		$sql = 'DELETE FROM ws_det WHERE logro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premiado sea igual al valor pasado como parámetro
     *
     * @param String $value premiado requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPremiado($value){
		$sql = 'DELETE FROM ws_det WHERE premiado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByEstado($value){
		$sql = 'DELETE FROM ws_det WHERE estado = ?';
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
		$sql = 'DELETE FROM ws_det WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM ws_det WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM ws_det WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM ws_det WHERE usumodif_id = ?';
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
		$sql = 'DELETE FROM ws_det WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


    /**
     * Crear y devolver un objeto del tipo WsDet
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $wsDet WsDet
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$wsDet = new WsDet();
		
		$wsDet->detId = $row['det_id'];
		$wsDet->ticketId = $row['ticket_id'];
		$wsDet->eventoId = $row['evento_id'];
		$wsDet->descripcion = $row['descripcion'];
		$wsDet->opcion = $row['opcion'];
		$wsDet->logro = $row['logro'];
		$wsDet->premiado = $row['premiado'];
		$wsDet->estado = $row['estado'];
		$wsDet->fechaCrea = $row['fecha_crea'];
		$wsDet->usucreaId = $row['usucrea_id'];
		$wsDet->fechaModif = $row['fecha_modif'];
		$wsDet->usumodifId = $row['usumodif_id'];
		$wsDet->mandante = $row['mandante'];

		return $wsDet;
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